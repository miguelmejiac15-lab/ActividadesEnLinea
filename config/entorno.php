<?php
/**
 * entorno.php — Configuración tomada de las variables de entorno
 *
 * Es la alternativa a `credenciales.php` para cuando la plataforma corre
 * en un contenedor.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ HACEN FALTA LAS DOS FORMAS
 * ─────────────────────────────────────────────────────────────────────
 *
 * En XAMPP, `credenciales.php` es lo correcto: un archivo que se edita a
 * mano, protegido por `.htaccess`, que el instalador genera. Se queda.
 *
 * En un contenedor no sirve, y no por gusto: la imagen de Docker se
 * construye una vez y se publica en un registro. Cualquier secreto
 * escrito dentro viaja con ella —a GHCR, a la caché de capas de cada
 * máquina que la baje, al historial de builds— y no se puede sacar
 * después. Meter la contraseña de MySQL en la imagen es publicarla.
 *
 * Las variables de entorno se inyectan al ARRANCAR el contenedor, así
 * que nunca entran en la imagen. Es la misma razón por la que
 * `pasarela-mercadopago.php` ya prefería `MP_ACCESS_TOKEN` sobre la base
 * de datos.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EL ORDEN: EL ARCHIVO GANA
 * ─────────────────────────────────────────────────────────────────────
 *
 * `config.php` solo llama aquí cuando NO existe `credenciales.php`. Así,
 * en una máquina de desarrollo con el archivo puesto, unas variables de
 * entorno olvidadas en el perfil del sistema no pueden secuestrar la
 * conexión y hacer que las pruebas escriban en la base de producción.
 */

declare(strict_types=1);

/**
 * Una variable de entorno, probando varios nombres.
 *
 * Se aceptan alias porque quien inyecta las variables no siempre es
 * quien escribió esto: Coolify nombra las suyas al crear la base de
 * datos, y obligar a renombrarlas a mano es una fuente de errores que
 * solo se descubren cuando el contenedor ya no arranca.
 *
 * @param string[] $nombres En orden de preferencia
 */
function envUno(array $nombres, ?string $porDefecto = null): ?string
{
    foreach ($nombres as $n) {
        // `$_ENV` y `$_SERVER` antes que `getenv()`: con algunas
        // configuraciones de PHP-FPM `getenv()` no ve lo que pasó el
        // servidor web, y entonces la variable «no existe» aunque esté.
        $v = $_ENV[$n] ?? $_SERVER[$n] ?? getenv($n);

        if (is_string($v) && trim($v) !== '') {
            return trim($v);
        }
    }

    return $porDefecto;
}

/** ¿Hay suficiente en el entorno para arrancar sin `credenciales.php`? */
function hayConfiguracionEnEntorno(): bool
{
    return envUno(['DB_HOST', 'MYSQL_HOST', 'DATABASE_HOST']) !== null;
}

/**
 * Arma la misma estructura que devuelve `credenciales.php`.
 *
 * Devuelve exactamente las mismas claves y en el mismo formato, para que
 * `config.php` no tenga que saber de dónde vino la configuración.
 */
function configuracionDesdeEntorno(): array
{
    /*
     * La URL base no tiene valor por defecto razonable y conviene que se
     * note: si se queda vacía, todos los enlaces del sitio salen
     * relativos a la raíz del dominio equivocado, los correos de
     * recuperación apuntan a ningún sitio y Mercado Pago recibe una
     * `notification_url` inválida. Es mejor que falle al arrancar.
     */
    $urlBase = envUno(['APP_URL', 'URL_BASE', 'SERVICE_FQDN_WEB', 'COOLIFY_FQDN'], '');

    return [
        'db' => [
            'host'     => envUno(['DB_HOST', 'MYSQL_HOST', 'DATABASE_HOST'], '127.0.0.1'),
            'port'     => (int) envUno(['DB_PORT', 'MYSQL_PORT', 'DATABASE_PORT'], '3306'),
            'database' => envUno(['DB_DATABASE', 'DB_NAME', 'MYSQL_DATABASE'], 'actividades_en_linea'),
            'usuario'  => envUno(['DB_USERNAME', 'DB_USER', 'MYSQL_USER'], 'root'),
            'password' => envUno(['DB_PASSWORD', 'MYSQL_PASSWORD', 'MYSQL_ROOT_PASSWORD'], ''),
            'charset'  => envUno(['DB_CHARSET'], 'utf8mb4'),
        ],

        // Por defecto `produccion`: un contenedor sin decir nada NO debe
        // mostrar trazas de error en pantalla. El descuido tiene que caer
        // del lado seguro.
        'entorno'  => envUno(['APP_ENV', 'ENTORNO'], 'produccion') === 'desarrollo'
                        ? 'desarrollo'
                        : 'produccion',

        'url_base' => rtrim((string) $urlBase, '/'),
        'app_key'  => (string) envUno(['APP_KEY'], ''),
    ];
}
