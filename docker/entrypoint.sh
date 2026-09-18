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

echo "─────────────────────────────────────────────────────────"

exec "$@"
