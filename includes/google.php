<?php
/**
 * google.php — Entrar con Google (OAuth 2.0, código de autorización)
 *
 * La forma más fuerte de saber que un correo es real: Google solo
 * entrega el perfil si el correo está VERIFICADO por ellos. Una cuenta
 * creada así no necesita enlace de verificación ni pregunta de
 * comprobación — un robot no tiene cuenta de Google que validar.
 *
 * Adaptado de la versión construida y probada en Ludia. cURL nativo, sin
 * librerías.
 *
 * Reglas que no se aflojan:
 *  · El `client_secret` vive en el entorno (Coolify) y nunca sale del servidor.
 *  · Sin credenciales, `googleConfigurado()` da false y el botón no se pinta:
 *    entrar con contraseña sigue igual.
 *  · La identidad se ata al `sub` de Google, no al correo: un correo puede
 *    cambiar de dueño con los años; el `sub` no.
 *  · Solo se acepta el perfil con `email_verified`. Sin eso, quien registrara
 *    una cuenta de Google con el correo de otra persona entraría en la suya.
 *  · El `state` es de un solo uso y vive en la sesión: sin él, otra página
 *    podría provocar un ingreso (CSRF sobre el propio login).
 */

declare(strict_types=1);

const GOOGLE_AUTORIZAR = 'https://accounts.google.com/o/oauth2/v2/auth';
const GOOGLE_TOKEN     = 'https://oauth2.googleapis.com/token';
const GOOGLE_PERFIL    = 'https://www.googleapis.com/oauth2/v3/userinfo';

/** Una variable de entorno, con los mismos tres orígenes que usa entorno.php. */
function googleEntorno(string $nombre): string
{
    $v = $_ENV[$nombre] ?? $_SERVER[$nombre] ?? getenv($nombre);

    return is_string($v) ? trim($v) : '';
}

function googleClienteId(): string     { return googleEntorno('GOOGLE_CLIENT_ID'); }
function googleClienteSecreto(): string { return googleEntorno('GOOGLE_CLIENT_SECRET'); }

/** ¿Hay credenciales? Sin ellas el botón ni aparece. */
function googleConfigurado(): bool
{
    return googleClienteId() !== '' && googleClienteSecreto() !== '' && accesoSeguroInstalado();
}

/** La dirección a la que Google devuelve: tiene que estar registrada en Google Cloud. */
function googleVuelta(): string
{
    return url('google-volver.php');
}

/** A dónde mandar al usuario para que Google le pregunte. */
function googleUrlAutorizacion(): string
{
    $state = bin2hex(random_bytes(16));
    $_SESSION['google_state'] = $state;

    return GOOGLE_AUTORIZAR . '?' . http_build_query([
        'client_id'     => googleClienteId(),
        'redirect_uri'  => googleVuelta(),
        'response_type' => 'code',
        'scope'         => 'openid email profile',
        'state'         => $state,
        'prompt'        => 'select_account',
    ]);
}

/** Comprueba el `state` y lo consume. */
function googleStateValido(?string $state): bool
{
    $esperado = (string) ($_SESSION['google_state'] ?? '');
    unset($_SESSION['google_state']);

    return $esperado !== '' && is_string($state) && hash_equals($esperado, $state);
}

/**
 * Cambia el código por el perfil.
 *
 * @return array{ok:bool, error:string, perfil:array}
 */
function googlePerfilDesdeCodigo(string $codigo): array
{
    $token = googlePeticion(GOOGLE_TOKEN, [
        'code'          => $codigo,
        'client_id'     => googleClienteId(),
        'client_secret' => googleClienteSecreto(),
        'redirect_uri'  => googleVuelta(),
        'grant_type'    => 'authorization_code',
    ]);

    if (!$token['ok'] || empty($token['datos']['access_token'])) {
        return ['ok' => false, 'error' => $token['error'] ?: 'Google no entregó el permiso', 'perfil' => []];
    }

    $perfil = googlePeticion(GOOGLE_PERFIL, null, (string) $token['datos']['access_token']);

    if (!$perfil['ok']) {
        return ['ok' => false, 'error' => $perfil['error'], 'perfil' => []];
    }

    $d     = $perfil['datos'];
    $sub   = (string) ($d['sub'] ?? '');
    $email = correoValido((string) ($d['email'] ?? ''));

    if ($sub === '' || $email === null) {
        return ['ok' => false, 'error' => 'Google no devolvió el correo de la cuenta', 'perfil' => []];
    }

    if (empty($d['email_verified'])) {
        return ['ok' => false, 'error' => 'Esa cuenta de Google no tiene el correo verificado', 'perfil' => []];
    }

    return ['ok' => true, 'error' => '', 'perfil' => [
        'sub'    => $sub,
        'email'  => $email,
        'nombre' => mb_substr(trim((string) ($d['name'] ?? '')) ?: (string) strstr($email, '@', true), 0, 120),
    ]];
}

/**
 * Entra (o crea la cuenta) con un perfil ya verificado por Google.
 *
 * Tres casos, en este orden:
 *   1. Ya entró antes con Google (mismo `sub`): entra.
 *   2. Hay una cuenta con ese correo: se le vincula Google. Es seguro
 *      porque Google acaba de verificar que el correo es de quien entra.
 *   3. No hay nada: se crea una cuenta de familia, con el correo ya
 *      verificado y una contraseña aleatoria que nadie conoce (puede fijar
 *      una con «olvidé mi contraseña» si algún día la quiere).
 *
 * @return array{ok:bool, error:?string, nueva:bool}
 */
function entrarConGoogle(array $perfil): array
{
    $u = traerUno('SELECT id, status FROM users WHERE google_sub = ?', [$perfil['sub']]);
    $nueva = false;

    if (!$u) {
        $u = traerUno('SELECT id, status, google_sub FROM users WHERE email = ?', [$perfil['email']]);

        if ($u) {
            // Una cuenta ya atada a OTRA identidad de Google no se reasigna.
            if (!empty($u['google_sub']) && $u['google_sub'] !== $perfil['sub']) {
                return ['ok' => false, 'nueva' => false,
                        'error' => 'Ese correo ya está vinculado a otra cuenta de Google.'];
            }

            ejecutar('UPDATE users SET google_sub = ? WHERE id = ?', [$perfil['sub'], $u['id']]);
        } else {
            $id = insertar(
                'INSERT INTO users (name, email, email_verified_at, google_sub, password, role, status)
                 VALUES (?, ?, NOW(), ?, ?, "user", "active")',
                [$perfil['nombre'], $perfil['email'], $perfil['sub'],
                 password_hash(bin2hex(random_bytes(24)), PASSWORD_DEFAULT)]
            );

            if (function_exists('entregarBienvenida')) {
                entregarBienvenida($id);
            }

            $u = ['id' => $id, 'status' => 'active'];
            $nueva = true;
        }
    }

    if ($u['status'] !== 'active') {
        return ['ok' => false, 'nueva' => false,
                'error' => 'Esta cuenta está desactivada. Escríbenos para reactivarla.'];
    }

    marcarCorreoVerificado((int) $u['id']);

    // La misma puerta que el ingreso con contraseña (ver iniciarSesion()).
    session_regenerate_id(false);
    $_SESSION['usuario_id'] = (int) $u['id'];
    ejecutar('UPDATE users SET last_login_at = NOW() WHERE id = ?', [$u['id']]);

    return ['ok' => true, 'error' => null, 'nueva' => $nueva];
}

/**
 * Petición a Google. Con $cuerpo hace POST de formulario; con $token, GET
 * autenticado. Nunca lanza: un fallo de red no tumba la página.
 *
 * @return array{ok:bool, datos:array, error:string}
 */
function googlePeticion(string $url, ?array $cuerpo = null, string $token = ''): array
{
    $ch = curl_init($url);
    $cabeceras = ['Accept: application/json'];

    if ($cuerpo !== null) {
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($cuerpo));
        $cabeceras[] = 'Content-Type: application/x-www-form-urlencoded';
    }
    if ($token !== '') {
        $cabeceras[] = 'Authorization: Bearer ' . $token;
    }

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER     => $cabeceras,
        CURLOPT_TIMEOUT        => 15,
    ]);

    $respuesta = curl_exec($ch);
    $http  = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $fallo = curl_error($ch);
    curl_close($ch);

    if ($respuesta === false) {
        error_log('[google] sin conexión: ' . $fallo);
        return ['ok' => false, 'datos' => [], 'error' => 'No se pudo contactar con Google'];
    }

    $datos = json_decode((string) $respuesta, true);
    $datos = is_array($datos) ? $datos : [];

    if ($http >= 400) {
        $mensaje = (string) ($datos['error_description'] ?? $datos['error'] ?? 'Google respondió ' . $http);
        error_log('[google] ' . $url . ' — HTTP ' . $http . ' — ' . $mensaje);
        return ['ok' => false, 'datos' => $datos, 'error' => $mensaje];
    }

    return ['ok' => true, 'datos' => $datos, 'error' => ''];
}
