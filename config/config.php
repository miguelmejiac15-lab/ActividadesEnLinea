<?php
/**
 * config.php — Arranque de la aplicación
 *
 * Todo archivo público del sitio empieza incluyendo ESTE archivo, y solo
 * este. Se encarga de:
 *   1. cargar las credenciales (que viven fuera del código público),
 *   2. definir las constantes y rutas del proyecto,
 *   3. configurar el manejo de errores según el entorno,
 *   4. abrir una sesión segura,
 *   5. cargar las funciones comunes.
 *
 * De este modo no hay configuración repetida en cada página.
 */

declare(strict_types=1);

// ── 1. Rutas base del proyecto ───────────────────────────────────────
define('RUTA_RAIZ',     dirname(__DIR__));
define('RUTA_CONFIG',   RUTA_RAIZ . '/config');
define('RUTA_INCLUDES', RUTA_RAIZ . '/includes');
define('RUTA_ALMACEN',  RUTA_RAIZ . '/almacen');
define('RUTA_LOGS',     RUTA_ALMACEN . '/logs');
define('RUTA_LEGACY',   RUTA_RAIZ . '/legacy');

// ── 2. Credenciales ──────────────────────────────────────────────────
$archivoCredenciales = RUTA_CONFIG . '/credenciales.php';

/*
 * Dos orígenes posibles, y el archivo gana.
 *
 * En XAMPP existe `credenciales.php` y se usa ese. En un contenedor no
 * existe —no puede: la imagen es pública y un secreto dentro viaja con
 * ella— y la configuración llega por variables de entorno.
 *
 * El orden importa y es a propósito: si el entorno ganara, unas variables
 * olvidadas en el perfil del sistema podrían secuestrar la conexión de
 * una máquina de desarrollo y hacer que las pruebas escribieran en la
 * base de producción.
 */
require_once RUTA_CONFIG . '/entorno.php';

if (!is_file($archivoCredenciales) && hayConfiguracionEnEntorno()) {
    $config = configuracionDesdeEntorno();

} elseif (!is_file($archivoCredenciales)) {
    // La plataforma todavía no está instalada.
    //
    // Nota: aquí NO se responde con un código 500. Apache descarta el
    // cuerpo de las respuestas de error y sustituye su propia página, así
    // que el mensaje explicativo nunca llegaría y solo se vería una
    // pantalla en blanco.
    if (PHP_SAPI === 'cli') {
        exit("Falta config/credenciales.php. Ejecuta instalar.php o copia config/credenciales.example.php.\n");
    }

    // Si el instalador sigue disponible, se lleva al usuario directamente.
    if (is_file(RUTA_RAIZ . '/instalar.php') && basename($_SERVER['SCRIPT_NAME'] ?? '') !== 'instalar.php') {
        header('Location: instalar.php');
        exit;
    }

    header('Content-Type: text/html; charset=utf-8');
    exit(
        '<!DOCTYPE html><html lang="es"><head><meta charset="UTF-8">'
        . '<title>Configuración pendiente</title></head><body '
        . 'style="font-family:system-ui,sans-serif;max-width:620px;margin:60px auto;padding:0 20px;line-height:1.6;color:#445">'
        . '<h1 style="color:#2f3b52">Falta configurar la plataforma</h1>'
        . '<p>No se encuentra <code>config/credenciales.php</code>.</p>'
        . '<p>Copia <code>config/credenciales.example.php</code> como <code>config/credenciales.php</code> '
        . 'y ajusta los datos de conexión, o vuelve a subir <code>instalar.php</code> para usar el instalador.</p>'
        . '</body></html>'
    );
}

// Solo si vino del archivo: en el caso del contenedor ya está armada.
if (is_file($archivoCredenciales)) {
    $config = require $archivoCredenciales;
}

define('ENTORNO',  $config['entorno']  ?? 'produccion');
define('URL_BASE', rtrim($config['url_base'] ?? '', '/'));
define('APP_KEY',  $config['app_key']  ?? '');
define('ES_DESARROLLO', ENTORNO === 'desarrollo');

// La configuración de BD queda disponible para database.php y solo para él.
$GLOBALS['__config_db'] = $config['db'];

// ── 3. Errores ───────────────────────────────────────────────────────
// En producción nunca se muestran en pantalla: revelan rutas, consultas
// y a veces credenciales. Se registran en almacen/logs/.
if (!is_dir(RUTA_LOGS)) {
    @mkdir(RUTA_LOGS, 0755, true);
}

error_reporting(E_ALL);
ini_set('log_errors', '1');
ini_set('error_log', RUTA_LOGS . '/php-error.log');
ini_set('display_errors', ES_DESARROLLO ? '1' : '0');

date_default_timezone_set('America/Bogota');
mb_internal_encoding('UTF-8');

// ── 4. Sesión segura ─────────────────────────────────────────────────
// Solo cuando hay una petición web real. En línea de comandos (scripts
// de mantenimiento o pruebas) no hay cookies que enviar, y PHP considera
// las cabeceras ya enviadas desde el primer momento.
$hayPeticionWeb = PHP_SAPI !== 'cli' && !headers_sent();

if ($hayPeticionWeb && session_status() === PHP_SESSION_NONE) {

    /*
     * PHP acepta por defecto CUALQUIER identificador de sesión que le
     * mande el navegador, exista o no, y crea uno con ese nombre. Eso es
     * lo que hace posible la fijación de sesión: alguien planta un id
     * conocido en el navegador de la víctima y espera a que entre.
     *
     * `use_strict_mode` hace que PHP ignore un id que no ha emitido él y
     * genere uno nuevo. Es la defensa de raíz; la regeneración al entrar
     * (`iniciarSesion()`) es la segunda línea, no la única.
     */
    ini_set('session.use_strict_mode', '1');

    // El id solo viaja en la cookie, nunca en la URL: una dirección con
    // el identificador dentro acaba pegada en un chat o en un log.
    ini_set('session.use_only_cookies', '1');

    /*
     * ¿Se marca la cookie como `Secure`?
     *
     * ─────────────────────────────────────────────────────────────────
     *  POR QUÉ NO BASTA CON $_SERVER['HTTPS']
     * ─────────────────────────────────────────────────────────────────
     *
     * Antes se miraba solo eso, y en un despliegue detrás de proxy
     * —Coolify, Traefik, cualquier CDN— es falso aunque el visitante esté
     * en HTTPS: el TLS lo termina el proxy y a PHP le llega una petición
     * HTTP normal por la red interna. El resultado es que en producción
     * la cookie de sesión salía SIN la marca `Secure`, o sea que el
     * navegador la enviaría también por HTTP si algo degradara la
     * conexión. Es justo la protección que aquí se creía puesta.
     *
     * La fuente fiable es `URL_BASE`: dice con qué esquema se publica el
     * sitio, la escribimos nosotros en la configuración, y —a diferencia
     * de una cabecera `X-Forwarded-Proto`— no la puede tocar quien llama.
     * Confiar en la cabecera sería dejar que el cliente opine sobre su
     * propia seguridad.
     *
     * En XAMPP (`http://localhost/...`) sigue dando false, que es lo que
     * debe: con `Secure` puesto sobre HTTP el navegador descarta la
     * cookie y no se puede ni iniciar sesión.
     */
    $porHttps = str_starts_with(URL_BASE, 'https://') || !empty($_SERVER['HTTPS']);

    session_set_cookie_params([
        'lifetime' => 0,
        'path'     => '/',
        'secure'   => $porHttps,
        // El JavaScript del navegador no puede leer la cookie de sesión:
        // esto corta el robo de sesión por XSS.
        'httponly' => true,
        // Bloquea el envío de la cookie desde otros sitios (CSRF).
        'samesite' => 'Lax',
    ]);
    session_name('AEL_SESION');
    session_start();
}

// $_SESSION debe existir siempre, aunque no haya sesión real (CLI),
// para que las funciones que la consultan no fallen.
if (!isset($_SESSION)) {
    $_SESSION = [];
}

// ── 5. Constantes de negocio ─────────────────────────────────────────
// Modelo freemium: el usuario Free entra a todas las actividades pero
// solo juega las primeras estaciones de cada una.
define('ACCESO_LIBRE',   'free');      // toda la actividad es gratuita
define('ACCESO_PARCIAL', 'partial');   // primeras N estaciones gratuitas
define('ACCESO_PREMIUM', 'premium');   // requiere suscripción

define('PLAN_FREE',       'free');
define('PLAN_BIBLIOTECA', 'biblioteca');
define('PLAN_ESCUELA',    'escuela');
define('PLAN_DOCENTE',    'docente');

// ── 6. Dependencias comunes ──────────────────────────────────────────
require_once RUTA_CONFIG   . '/database.php';
require_once RUTA_INCLUDES . '/funciones.php';
require_once RUTA_INCLUDES . '/auth.php';
require_once RUTA_INCLUDES . '/acceso.php';
require_once RUTA_INCLUDES . '/catalogo.php';
require_once RUTA_INCLUDES . '/gamificacion.php';
require_once RUTA_INCLUDES . '/correo.php';
require_once RUTA_INCLUDES . '/recuperacion.php';

// El adaptador va ANTES que `pagos.php`: `pasarelaActiva()` pregunta si
// Mercado Pago está configurado, así que sus funciones tienen que existir.
require_once RUTA_INCLUDES . '/pasarela-mercadopago.php';
require_once RUTA_INCLUDES . '/pagos.php';
require_once RUTA_INCLUDES . '/escuela.php';
require_once RUTA_INCLUDES . '/aula.php';

/*
 * `ruta.php` después de `escuela.php`, y su relación con `acceso.php` es
 * al revés de lo que parece: `acceso.php` se carga antes y la llama con
 * `function_exists()`, porque la ruta CIERRA sobre lo que aquel decide.
 */
require_once RUTA_INCLUDES . '/ruta.php';

/*
 * Y `modo-nino.php` junto a ella, por lo mismo: también cierra sobre lo
 * que decide `acceso.php`, que lo llama con `function_exists()`.
 *
 * Son dos cosas distintas aunque se parezcan: la ruta es lo que un
 * docente asignó a la cuenta de un alumno; el modo niño es lo que un
 * adulto dejó a la vista en SU PROPIA cuenta antes de prestar la tableta.
 */
require_once RUTA_INCLUDES . '/modo-nino.php';

// Las guías van al final: `guiasParaMi()` pregunta por el rol y por
// `puedeEntrarAEscuela()`, así que necesita todo lo anterior cargado.
require_once RUTA_INCLUDES . '/guias.php';

// `colegio.php` va DESPUÉS de `aula.php` y `escuela.php`: usa
// `dominioDeAula()`, `claveMemorable()` y `claseAbierta()`.
require_once RUTA_INCLUDES . '/colegio.php';

// Y `institucion.php` después de `colegio.php`: reparte sus acciones.
require_once RUTA_INCLUDES . '/institucion.php';
require_once RUTA_INCLUDES . '/uso.php';

/*
 * Anota que quien tiene sesión abierta sigue por aquí.
 *
 * Va al final de la carga y no en una plantilla porque tiene que contar
 * también las llamadas a la API: un niño jugando pasa media hora sin
 * cargar una sola página, y sin esto esa media hora no existiría.
 *
 * Es barato: `registrarVisita()` toca la base como mucho una vez por
 * minuto y no hace nada si no hay sesión.
 */
registrarVisita();
