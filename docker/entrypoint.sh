#!/bin/sh
# =====================================================================
#  Arranque del contenedor
# =====================================================================
#
# Tres cosas, en este orden: comprobar que la configuración está, esperar
# a que la base responda, y asegurar que existe la estructura.
#
# `set -e` para que un fallo aquí PARE el arranque. Sin esto el
# contenedor seguiría hasta levantar Apache y el sitio contestaría 500 a
# cada visita, lo que parece un problema del código y no de la
# configuración — el peor sitio donde dejar a alguien buscando.

set -e

echo "─────────────────────────────────────────────────────────"
echo "  Actividades en Línea · arrancando"
echo "─────────────────────────────────────────────────────────"

# ── 1. ¿Está la configuración? ───────────────────────────────────────
#
# Se comprueba ANTES de nada y con un mensaje que dice qué falta. El fallo
# alternativo —arrancar y que PHP muera al conectar— produce un 503 que no
# distingue «falta una variable» de «la base está caída».
faltan=""
for v in DB_HOST DB_DATABASE DB_USERNAME APP_URL APP_KEY; do
    eval "valor=\$$v"
    if [ -z "$valor" ]; then
        faltan="$faltan $v"
    fi
done

if [ -n "$faltan" ]; then
    echo "✗ Faltan variables de entorno:$faltan"
    echo ""
    echo "  Se ponen en Coolify → la aplicación → Environment Variables."
    echo "  APP_KEY es una cadena aleatoria larga; se genera con:"
    echo "      openssl rand -hex 32"
    exit 1
fi

# APP_URL tiene que ser https en producción, o la cookie de sesión sale
# sin la marca `Secure` (ver config/config.php).
case "$APP_URL" in
    https://*) : ;;
    *) echo "⚠ APP_URL no empieza por https:// — la cookie de sesión NO será Secure." ;;
esac

echo "✓ Configuración presente"
echo "  APP_URL : $APP_URL"
echo "  Base    : $DB_USERNAME@$DB_HOST:${DB_PORT:-3306}/$DB_DATABASE"

# ── 2. Esperar a la base ─────────────────────────────────────────────
#
# Docker arranca los contenedores a la vez, así que la primera vez la base
# casi nunca está lista cuando la aplicación ya lo está. Sin esta espera,
# el primer despliegue falla siempre y el segundo funciona — un
# comportamiento que hace perder una tarde buscando qué cambió.
echo "· Esperando a la base de datos…"

intento=0
until mysqladmin ping \
        --host="$DB_HOST" \
        --port="${DB_PORT:-3306}" \
        --user="$DB_USERNAME" \
        --password="$DB_PASSWORD" \
        --silent 2>/dev/null
do
    intento=$((intento + 1))

    if [ "$intento" -ge 30 ]; then
        echo "✗ La base no respondió en 60 segundos."
        echo "  Revisa que el servicio de MariaDB esté encendido en Coolify"
        echo "  y que DB_HOST sea el nombre interno del servicio, no una IP."
        exit 1
    fi

    sleep 2
done

echo "✓ Base de datos disponible"

# ── 3. La estructura ─────────────────────────────────────────────────
#
# Solo si la base está VACÍA. Nunca se toca una base con datos: el
# catálogo real (489 actividades, 3.123 estaciones) se importa desde un
# volcado, porque no se puede reconstruir desde el repositorio —las
# actividades migradas del sitio anterior salieron de archivos que no
# están aquí—. Aplicar `schema.sql` a ciegas sobre datos buenos sería la
# forma más rápida de perderlos.
tablas=$(mysql \
    --host="$DB_HOST" \
    --port="${DB_PORT:-3306}" \
    --user="$DB_USERNAME" \
    --password="$DB_PASSWORD" \
    --skip-column-names --silent \
    -e "SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = '$DB_DATABASE';" 2>/dev/null || echo 0)

if [ "$tablas" = "0" ]; then
    echo "· Base vacía: creando la estructura…"

    mysql --host="$DB_HOST" --port="${DB_PORT:-3306}" \
          --user="$DB_USERNAME" --password="$DB_PASSWORD" \
          "$DB_DATABASE" < /var/www/html/database/schema.sql

    echo "✓ Estructura creada"
    echo ""
    echo "  ⚠ El catálogo está VACÍO. Importa el volcado de producción:"
    echo "      database/exportar-para-produccion.php   (en local)"
    echo "  Hasta entonces el sitio arranca pero no hay actividades."
else
    echo "✓ La base ya tiene $tablas tablas: no se toca nada"
fi

# ── 4. Las migraciones ───────────────────────────────────────────────
#
# ─────────────────────────────────────────────────────────────────────
#  POR QUÉ ESTO NO ES OPCIONAL
# ─────────────────────────────────────────────────────────────────────
#
# `schema.sql` NO es el esquema completo: es la base sobre la que once
# migraciones han ido añadiendo columnas. Un despliegue nuevo que solo
# aplique `schema.sql` arranca con una base a medias —le faltan catorce
# columnas repartidas en `schools`, `courses` y `users`— y el sitio
# parece funcionar hasta que alguien entra a la parte que las usa.
#
# Y falla de la peor manera posible: no con un error de instalación, sino
# con una consulta a una columna que no existe. La página devuelve 500 y
# el menú de Colegios directamente NO SE DIBUJA, porque
# `colegiosInstalados()` pregunta por `schools.plan_id`. Desde fuera no
# parece una base incompleta: parece que la función no está hecha.
#
# Pasó exactamente así en el primer despliegue. Por eso corren solas: la
# alternativa es acordarse a mano cada vez, y eso no es una alternativa.
#
# Son idempotentes por construcción —cada `ALTER` va detrás de un
# `hayColumna()`, cada inserción detrás de su comprobación—, así que
# repetirlas en cada arranque no cuesta nada ni cambia nada.
if [ "${SALTAR_MIGRACIONES:-0}" != "1" ]; then
    echo "· Aplicando migraciones…"

    for m in /var/www/html/database/migracion-*.php; do
        [ -f "$m" ] || continue
        nombre=$(basename "$m")

        # `|| true`: una migración que falle no puede impedir que el sitio
        # arranque. Queda escrito aquí y se mira; dejar el sitio caído por
        # esto sería cambiar un problema parcial por uno total.
        if php "$m" --aplicar >/tmp/mig.log 2>&1; then
            printf '  ✓ %s\n' "$nombre"
        else
            printf '  ✗ %s\n' "$nombre"
            sed 's/^/      /' /tmp/mig.log | tail -5
        fi
    done

    rm -f /tmp/mig.log
    echo "✓ Migraciones al día"
fi

# ── 5. La primera cuenta de administrador ────────────────────────────
#
# Una instalación recién desplegada no tiene usuarios: el volcado de
# producción los deja fuera a propósito. Sin esto no hay con qué entrar al
# panel, y la única salida es abrir una terminal en el servidor — que es
# justo lo que no siempre se puede.
#
# Se crea solo si están las dos variables Y no hay ya un administrador.
# El guardián de verdad está dentro de `crear-admin.php`, que se niega y
# lista los que hay: así reiniciar el contenedor veinte veces no deja
# veinte cuentas. Es idempotente por construcción, no por cuidado.
#
# Cuando la cuenta ya existe, **quita las dos variables de Coolify**. No
# hacen falta nunca más y una contraseña guardada en la configuración de
# un panel es una contraseña de más.
if [ -n "${ADMIN_EMAIL:-}" ] && [ -n "${ADMIN_PASSWORD:-}" ]; then
    echo "· Comprobando la cuenta de administrador…"

    # `|| true` a propósito: si falla —la contraseña no cumple el mínimo,
    # el correo ya está usado— el contenedor tiene que arrancar igual. Un
    # sitio entero caído porque una cuenta no se pudo crear es peor que el
    # problema que intenta resolver, y el motivo queda escrito aquí arriba.
    php /var/www/html/database/crear-admin.php \
        --correo="$ADMIN_EMAIL" \
        --nombre="${ADMIN_NOMBRE:-Administrador}" \
        --clave="$ADMIN_PASSWORD" 2>&1 | sed 's/^/  /' || true
fi

echo "─────────────────────────────────────────────────────────"

exec "$@"
