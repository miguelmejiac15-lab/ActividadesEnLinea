# =====================================================================
#  Actividades en Línea · imagen de la aplicación
# =====================================================================
#
# ─────────────────────────────────────────────────────────────────────
#  ESTA IMAGEN SE CONSTRUYE EN GITHUB ACTIONS, NO EN EL SERVIDOR
# ─────────────────────────────────────────────────────────────────────
#
# El servidor es una t3.small: 2 vCPU y 2 GB de RAM, de los que Coolify
# ya ocupa buena parte. Construir aquí —instalar paquetes, compilar
# extensiones de PHP— compite por esa memoria con el sitio que está
# atendiendo visitas, y cuando se agota el que muere no es el build: es
# MySQL o Coolify, porque el kernel mata al proceso más grande.
#
# Así que Actions construye y publica en GHCR, y el servidor solo baja la
# imagen ya hecha y la enciende. La diferencia práctica es minutos de
# build contra segundos de `docker pull`.
#
# ─────────────────────────────────────────────────────────────────────
#  AQUÍ NO ENTRA NINGÚN SECRETO
# ─────────────────────────────────────────────────────────────────────
#
# La imagen se publica en un registro y sus capas quedan en caché en cada
# máquina que la baje. Un secreto escrito dentro no se puede sacar
# después: hay que rotarlo. Por eso `config/credenciales.php` está en
# `.dockerignore` y toda la configuración llega por variables de entorno
# al ARRANCAR (ver `config/entorno.php`).

FROM php:8.2-apache

# ── Extensiones de PHP ───────────────────────────────────────────────
# `pdo_mysql` es la única que falta: mbstring, curl y json vienen en la
# imagen base. `intl` no se instala porque el proyecto no la usa.
#
# `--no-install-recommends` y el borrado de las listas en la MISMA capa:
# en capas distintas los archivos borrados siguen ocupando sitio en la
# imagen, porque cada capa solo añade.
RUN set -eux; \
    apt-get update; \
    apt-get install -y --no-install-recommends \
        default-mysql-client \
    ; \
    docker-php-ext-install -j"$(nproc)" pdo_mysql opcache; \
    rm -rf /var/lib/apt/lists/*

# ── Apache ───────────────────────────────────────────────────────────
# Los módulos que usa el .htaccess del proyecto: sin `headers` se pierden
# las cabeceras de seguridad, sin `expires` y `deflate` el sitio sigue
# funcionando pero pesa el triple. `rewrite` para las rutas.
RUN a2enmod rewrite headers expires deflate remoteip

# `AllowOverride All`: el proyecto reparte su seguridad en .htaccess por
# carpeta —config/, almacen/, includes/ y database/ tienen uno que dice
# «Require all denied»—. Con el valor por defecto de esta imagen (None)
# Apache los IGNORA y esas carpetas quedarían servidas por web.
COPY docker/apache-ael.conf /etc/apache2/conf-available/ael.conf
RUN a2enconf ael

# ── PHP ──────────────────────────────────────────────────────────────
COPY docker/php-ael.ini /usr/local/etc/php/conf.d/zz-ael.ini

# ── La aplicación ────────────────────────────────────────────────────
WORKDIR /var/www/html
COPY --chown=www-data:www-data . /var/www/html

# `almacen/` es lo único que la aplicación escribe (logs). El resto queda
# de root y solo legible: si un fallo permitiera escribir un .php, no
# debería poder hacerlo dentro del código.
RUN set -eux; \
    mkdir -p almacen/logs; \
    chown -R www-data:www-data almacen; \
    chmod -R 755 almacen

COPY docker/entrypoint.sh /usr/local/bin/entrypoint.sh
RUN chmod +x /usr/local/bin/entrypoint.sh

# Lo que sirvió para construir no tiene que quedar servible por web. Van
# al webroot porque `COPY . .` copia el contexto entero; se sacan aquí.
# El .htaccess deniega .ini y .md, pero no .sh ni .conf.
RUN rm -rf /var/www/html/docker \
           /var/www/html/Dockerfile \
           /var/www/html/.gitattributes \
           /var/www/html/.gitignore

# Coolify comprueba este estado para saber si el despliegue funcionó.
HEALTHCHECK --interval=30s --timeout=5s --start-period=40s --retries=3 \
    CMD php -r 'exit(@file_get_contents("http://127.0.0.1/estado.php") === "ok" ? 0 : 1);'

EXPOSE 80
ENTRYPOINT ["/usr/local/bin/entrypoint.sh"]
CMD ["apache2-foreground"]
