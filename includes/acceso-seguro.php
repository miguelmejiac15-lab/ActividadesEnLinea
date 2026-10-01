<?php
/**
 * acceso-seguro.php — Que crear una cuenta lo haga una persona, con un correo real
 *
 * Aparecieron cuentas con correos que parecen inventados. Este archivo
 * reúne las defensas, de la más barata a la más fuerte:
 *
 *   1. CASILLA TRAMPA. Un campo que una persona no ve y un robot rellena.
 *   2. TIEMPO MÍNIMO. Nadie llena el formulario en menos de 3 segundos;
 *      un robot sí. El instante de carga viaja firmado: no se puede falsear.
 *   3. PREGUNTA DE COMPROBACIÓN. «¿Cuánto es cuatro más tres?», escrita en
 *      palabras. Trivial para una persona, y no la resuelve un robot
 *      genérico que rellena formularios a ciegas.
 *   4. FRENO DE INTENTOS. Por conexión (IP) y por correo. Las IP se guardan
 *      cifradas con la clave de la aplicación: sirven para contar, no para
 *      saber quién es nadie.
 *   5. CORREO CREÍBLE. El dominio tiene que existir y recibir correo, y se
 *      rechazan los servicios de correo desechable. Además se avisa de las
 *      erratas típicas («gmail.con»).
 *   6. CORREO VERIFICADO. Un enlace al correo que confirma que es suyo.
 *      Solo puede exigirse cuando el sitio tiene correo saliente; mientras
 *      tanto se envía si se puede y no se bloquea a nadie.
 *
 * Y la vía más fuerte de todas no está aquí sino en `google.php`: entrar
 * con Google, que solo acepta correos que Google ya verificó.
 */

declare(strict_types=1);

/** Segundos mínimos entre cargar el formulario y enviarlo. */
const ACCESO_TIEMPO_MINIMO = 3;

/** Vida máxima del formulario firmado (luego se pide recargar). */
const ACCESO_TIEMPO_MAXIMO = 7200;

/** Horas que vale el enlace de verificación. */
const VERIFICACION_HORAS = 48;


// =====================================================================
//  INSTALACIÓN
// =====================================================================

/** ¿Está aplicada la migración de acceso seguro? */
function accesoSeguroInstalado(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.TABLES
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "access_attempts"'
        ) && (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "users"
                AND COLUMN_NAME = "email_verified_at"'
        );
    }

    return $listo;
}


// =====================================================================
//  1-3 · CASILLA TRAMPA, TIEMPO MÍNIMO Y PREGUNTA
// =====================================================================

/** Firma un valor con la clave de la aplicación. */
function firmarAcceso(string $valor): string
{
    return hash_hmac('sha256', $valor, APP_KEY !== '' ? APP_KEY : 'ael-sin-clave');
}

/**
 * Los campos ocultos que van dentro del formulario: la casilla trampa y
 * el instante de carga firmado. Se llama una vez por formulario.
 */
function camposAntiRobot(): string
{
    $t = (string) time();

    /*
     * La casilla trampa se saca de la vista con posición, no con
     * `display:none`: algunos robots se saltan los campos ocultos con
     * display pero rellenan cualquier input que exista. `tabindex=-1` y
     * `aria-hidden` evitan que una persona con teclado o lector de
     * pantalla caiga en ella.
     */
    return '<div class="campo-trampa" aria-hidden="true">'
         . '<label for="sitio_web">No llenes este campo</label>'
         . '<input id="sitio_web" name="sitio_web" type="text" tabindex="-1" autocomplete="off">'
         . '</div>'
         . '<input type="hidden" name="formulario_t" value="' . e($t . '.' . firmarAcceso($t)) . '">';
}

/**
 * ¿El envío lo hizo una persona? Revisa la casilla trampa y el tiempo.
 * Devuelve un motivo para el registro interno, o null si todo va bien.
 */
function motivoRobot(): ?string
{
    if (trim((string) ($_POST['sitio_web'] ?? '')) !== '') {
        return 'casilla trampa rellena';
    }

    $partes = explode('.', (string) ($_POST['formulario_t'] ?? ''), 2);

    if (count($partes) !== 2 || !hash_equals(firmarAcceso($partes[0]), $partes[1])) {
        return 'sin marca de tiempo válida';
    }

    $pasados = time() - (int) $partes[0];

    if ($pasados < ACCESO_TIEMPO_MINIMO) {
        return 'enviado en ' . $pasados . ' s';
    }
    if ($pasados > ACCESO_TIEMPO_MAXIMO) {
        return 'formulario caducado';
    }

    return null;
}

/**
 * Una pregunta nueva de comprobación, guardada en la sesión.
 *
 * Los números van en PALABRAS: «4 + 3» lo resuelve cualquier script que
 * busque una cuenta en la página; «cuatro más tres» ya no es una
 * expresión que se pueda evaluar sin entender español.
 */
function nuevaPregunta(): string
{
    static $nombres = ['cero', 'uno', 'dos', 'tres', 'cuatro', 'cinco',
                       'seis', 'siete', 'ocho', 'nueve', 'diez'];

    $a = random_int(2, 9);
    $b = random_int(1, 9);

    if (random_int(0, 1) === 1 && $a > $b) {
        $texto = '¿Cuánto es ' . $nombres[$a] . ' menos ' . $nombres[$b] . '?';
        $respuesta = $a - $b;
    } else {
        $texto = '¿Cuánto es ' . $nombres[$a] . ' más ' . $nombres[$b] . '?';
        $respuesta = $a + $b;
    }

    $_SESSION['pregunta_acceso'] = ['r' => $respuesta, 'texto' => $texto];

    return $texto;
}

/** La pregunta vigente (o una nueva si no hay). */
function preguntaVigente(): string
{
    return (string) ($_SESSION['pregunta_acceso']['texto'] ?? nuevaPregunta());
}

/**
 * ¿Respondió bien? Se consume siempre: cada pregunta vale un intento,
 * así no se puede probar número por número con la misma.
 * Acepta la cifra («7») o la palabra («siete»).
 */
function respuestaCorrecta(string $dada): bool
{
    $esperada = $_SESSION['pregunta_acceso']['r'] ?? null;
    unset($_SESSION['pregunta_acceso']);

    if ($esperada === null) {
        return false;
    }

    static $palabras = ['cero' => 0, 'uno' => 1, 'dos' => 2, 'tres' => 3, 'cuatro' => 4,
        'cinco' => 5, 'seis' => 6, 'siete' => 7, 'ocho' => 8, 'nueve' => 9, 'diez' => 10,
        'once' => 11, 'doce' => 12, 'trece' => 13, 'catorce' => 14, 'quince' => 15,
        'dieciseis' => 16, 'dieciséis' => 16, 'diecisiete' => 17, 'dieciocho' => 18];

    $d = mb_strtolower(trim($dada));
    $n = ctype_digit($d) ? (int) $d : ($palabras[$d] ?? null);

    return $n !== null && $n === (int) $esperada;
}


// =====================================================================
//  4 · FRENO DE INTENTOS
// =====================================================================

/** Clave cifrada de un dato (IP o correo): sirve para contar, no para leer. */
function claveIntento(string $dato): string
{
    return firmarAcceso('intento|' . mb_strtolower(trim($dato)));
}

function ipCliente(): string
{
    return (string) ($_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
}

/** Anota un intento. */
function anotarIntento(string $tipo, string $dato, bool $ok): void
{
    if (!accesoSeguroInstalado()) {
        return;
    }

    ejecutar('INSERT INTO access_attempts (kind, key_hash, ok) VALUES (?, ?, ?)',
             [$tipo, claveIntento($dato), $ok ? 1 : 0]);

    // Limpieza de paso: lo de hace más de dos días ya no frena a nadie.
    if (random_int(1, 50) === 1) {
        ejecutar('DELETE FROM access_attempts WHERE created_at < NOW() - INTERVAL 2 DAY');
    }
}

/** Cuántos intentos (o solo los fallidos) hubo en los últimos minutos. */
function contarIntentos(string $tipo, string $dato, int $minutos, ?bool $ok = null): int
{
    if (!accesoSeguroInstalado()) {
        return 0;
    }

    $sql = 'SELECT COUNT(*) FROM access_attempts
             WHERE kind = ? AND key_hash = ? AND created_at >= NOW() - INTERVAL ? MINUTE';
    $p   = [$tipo, claveIntento($dato), $minutos];

    if ($ok !== null) {
        $sql .= ' AND ok = ?';
        $p[]  = $ok ? 1 : 0;
    }

    return (int) traerValor($sql, $p);
}

/**
 * ¿Esta conexión ya registró demasiadas cuentas?
 *
 * Los límites son holgados a propósito: una familia o un salón entero
 * puede registrarse desde la misma red. Lo que frena es la ráfaga: 20
 * intentos en una hora o 15 cuentas en un día desde la misma conexión no
 * son personas.
 */
function registroFrenado(): bool
{
    $ip = ipCliente();

    return contarIntentos('registro', $ip, 60) >= 20
        || contarIntentos('registro', $ip, 1440, true) >= 15;
}

/**
 * ¿Hay que frenar el ingreso? Cinco fallos con el mismo correo en quince
 * minutos, o treinta desde la misma conexión: es alguien probando
 * contraseñas.
 */
function ingresoFrenado(string $correo): bool
{
    return contarIntentos('ingreso', $correo, 15, false) >= 5
        || contarIntentos('ingreso', ipCliente(), 15, false) >= 30;
}


// =====================================================================
//  5 · CORREO CREÍBLE
// =====================================================================

/** Erratas frecuentes en el dominio → lo que seguramente quiso escribir. */
const DOMINIOS_ERRATAS = [
    'gmail.con' => 'gmail.com', 'gmail.co' => 'gmail.com', 'gmail.cm' => 'gmail.com',
    'gmial.com' => 'gmail.com', 'gmai.com' => 'gmail.com', 'gamil.com' => 'gmail.com',
    'gmaill.com' => 'gmail.com', 'gnail.com' => 'gmail.com', 'gmail.es' => 'gmail.com',
    'hotmail.con' => 'hotmail.com', 'hotmial.com' => 'hotmail.com', 'hotmai.com' => 'hotmail.com',
    'hotmal.com' => 'hotmail.com', 'hotmail.co' => 'hotmail.com',
    'outlok.com' => 'outlook.com', 'outlook.con' => 'outlook.com', 'otlook.com' => 'outlook.com',
    'yahoo.con' => 'yahoo.com', 'yaho.com' => 'yahoo.com', 'yahooo.com' => 'yahoo.com',
    'icloud.con' => 'icloud.com',
];

/**
 * Servicios de correo desechable: buzones de diez minutos que existen
 * justamente para crear cuentas sin dar un correo real.
 */
const DOMINIOS_DESECHABLES = [
    'mailinator.com', 'yopmail.com', 'yopmail.net', 'guerrillamail.com', 'guerrillamail.net',
    'guerrillamail.org', 'sharklasers.com', 'grr.la', '10minutemail.com', '10minutemail.net',
    'temp-mail.org', 'temp-mail.io', 'tempmail.com', 'tempmail.net', 'tempmailo.com',
    'tempr.email', 'discard.email', 'dispostable.com', 'getnada.com', 'nada.email',
    'maildrop.cc', 'mailnesia.com', 'mintemail.com', 'trashmail.com', 'trashmail.de',
    'throwawaymail.com', 'fakeinbox.com', 'moakt.com', 'mohmal.com', 'emailondeck.com',
    'spamgourmet.com', 'burnermail.io', 'mailcatch.com', 'mytemp.email', 'tmail.ws',
    'tmpmail.org', 'tmpmail.net', 'emailfake.com', 'fakemail.net', 'mail.tm',
    'inboxkitten.com', 'mailpoof.com', 'spam4.me', 'byom.de', 'cock.li', 'mvrht.com',
];

/**
 * ¿Este correo es creíble? Revisa erratas, correo desechable y que el
 * dominio exista y reciba correo.
 *
 * @return array{ok:bool, error:?string}
 */
function correoCreible(string $correo): array
{
    $c = correoValido($correo);

    if ($c === null) {
        return ['ok' => false, 'error' => 'El correo no tiene un formato válido.'];
    }

    $dominio = substr((string) strrchr($c, '@'), 1);

    if (isset(DOMINIOS_ERRATAS[$dominio])) {
        $bien = strstr($c, '@', true) . '@' . DOMINIOS_ERRATAS[$dominio];
        return ['ok' => false, 'error' => '¿Quisiste decir ' . $bien . '? Revisa cómo termina el correo.'];
    }

    if (in_array($dominio, DOMINIOS_DESECHABLES, true)) {
        return ['ok' => false, 'error' => 'Usa un correo personal: los correos temporales no sirven para recuperar la cuenta.'];
    }

    /*
     * El dominio tiene que existir y poder recibir correo (registro MX, o
     * al menos una dirección). «pepe@asdfgh.com» no pasa. Si el servidor
     * no puede consultar el DNS en ese momento no se castiga al usuario:
     * se deja pasar y el resto de defensas siguen ahí.
     */
    if (function_exists('checkdnsrr') && !dnsDisponible()) {
        return ['ok' => true, 'error' => null];
    }

    if (function_exists('checkdnsrr') && !checkdnsrr($dominio, 'MX') && !checkdnsrr($dominio, 'A')) {
        return ['ok' => false, 'error' => 'El dominio «' . $dominio . '» no existe o no recibe correos. Revisa el correo.'];
    }

    return ['ok' => true, 'error' => null];
}

/** ¿Funciona el DNS ahora mismo? Se prueba con un dominio que siempre existe. */
function dnsDisponible(): bool
{
    static $ok = null;

    if ($ok === null) {
        $ok = checkdnsrr('gmail.com', 'MX');
    }

    return $ok;
}


// =====================================================================
//  6 · CORREO VERIFICADO
// =====================================================================

/**
 * ¿Se exige el correo verificado para entrar?
 *
 * Solo si el ajuste está encendido Y hay correo saliente. Las dos cosas:
 * con el ajuste encendido y el correo roto, cada cuenta nueva quedaría
 * encerrada fuera esperando un enlace que no llega.
 */
function exigeCorreoVerificado(): bool
{
    return accesoSeguroInstalado()
        && ajuste('exigir_correo_verificado', '0') === '1'
        && correoConfigurado();
}

/** ¿Esta cuenta tiene el correo verificado? */
function correoVerificado(int $usuarioId): bool
{
    if (!accesoSeguroInstalado()) {
        return true;
    }

    return traerValor('SELECT email_verified_at FROM users WHERE id = ?', [$usuarioId]) !== null;
}

/** Marca el correo de una cuenta como verificado. */
function marcarCorreoVerificado(int $usuarioId): void
{
    if (accesoSeguroInstalado()) {
        ejecutar('UPDATE users SET email_verified_at = COALESCE(email_verified_at, NOW()) WHERE id = ?',
                 [$usuarioId]);
    }
}

/**
 * Envía el enlace de verificación. Devuelve si salió.
 *
 * En la tabla solo queda el hash del enlace: quien la lea no puede
 * verificar ninguna cuenta con lo que ve. Pedir otro anula el anterior.
 */
function enviarVerificacion(int $usuarioId): bool
{
    if (!accesoSeguroInstalado() || !correoConfigurado()) {
        return false;
    }

    $u = traerUno('SELECT id, name, email, email_verified_at FROM users WHERE id = ?', [$usuarioId]);

    if (!$u || $u['email_verified_at'] !== null) {
        return false;
    }

    // No más de tres correos por hora a la misma cuenta.
    if (contarIntentos('reenvio', (string) $u['email'], 60) >= 3) {
        return false;
    }
    anotarIntento('reenvio', (string) $u['email'], true);

    ejecutar('UPDATE email_verifications SET used_at = NOW() WHERE user_id = ? AND used_at IS NULL',
             [$usuarioId]);

    $testigo = bin2hex(random_bytes(32));

    ejecutar(
        'INSERT INTO email_verifications (user_id, token_hash, expires_at)
         VALUES (?, ?, NOW() + INTERVAL ? HOUR)',
        [$usuarioId, hash('sha256', $testigo), VERIFICACION_HORAS]
    );

    $enlace = url('verificar.php?t=' . urlencode($testigo));

    $cuerpo = '<p>Hola, ' . e((string) $u['name']) . '.</p>'
            . '<p>Para terminar de crear tu cuenta en Actividades en Línea, confirma que este '
            . 'correo es tuyo. El enlace vale ' . VERIFICACION_HORAS . ' horas.</p>';

    $r = enviarCorreo(
        (string) $u['email'],
        'Confirma tu correo',
        correoPlantilla('Confirma tu correo', $cuerpo, ['url' => $enlace, 'texto' => 'Confirmar mi correo'])
        . '<div style="max-width:520px;margin:14px auto 0;font-size:.82rem;color:#8b95a5;text-align:center">'
        . 'Si no creaste esta cuenta, ignora este mensaje.</div>'
    );

    if (!$r['ok']) {
        error_log('[verificacion] no se pudo enviar a la cuenta ' . $usuarioId . ': ' . $r['error']);
    }

    return $r['ok'];
}

/**
 * Consume un enlace de verificación. Un solo uso, con fecha de caducidad.
 *
 * @return int|null el id de la cuenta verificada, o null si no vale
 */
function verificarConTestigo(string $testigo): ?int
{
    if (!accesoSeguroInstalado() || !preg_match('/^[a-f0-9]{64}$/', $testigo)) {
        return null;
    }

    $fila = traerUno(
        'SELECT id, user_id FROM email_verifications
          WHERE token_hash = ? AND used_at IS NULL AND expires_at > NOW()',
        [hash('sha256', $testigo)]
    );

    if (!$fila) {
        return null;
    }

    // Se quema comprobando que seguía sin usar: dos clics a la vez no pasan los dos.
    if (ejecutar('UPDATE email_verifications SET used_at = NOW() WHERE id = ? AND used_at IS NULL',
                 [$fila['id']]) !== 1) {
        return null;
    }

    marcarCorreoVerificado((int) $fila['user_id']);

    return (int) $fila['user_id'];
}
