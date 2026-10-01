<?php
/**
 * verificar.php — Confirmar el correo, y pedir otro enlace
 *
 *   ?t=…        el enlace que llegó al correo
 *   POST correo pide un enlace nuevo
 *
 * La respuesta a «mándame otro enlace» es siempre la misma, exista o no
 * la cuenta: lo contrario convierte esta pantalla en un comprobador de
 * qué correos están registrados.
 */

require_once __DIR__ . '/config/config.php';

$testigo = trim((string) get('t'));

if ($testigo !== '') {
    $id = verificarConTestigo($testigo);

    if ($id === null) {
        mensaje('error', 'Ese enlace ya se usó o caducó. Pide uno nuevo abajo.');
        redirigir('verificar.php');
    }

    mensaje('ok', '¡Listo! Tu correo quedó confirmado.');
    redirigir(usuarioActual() ? destinoTrasLoginSinCompra() : 'login.php');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $correo = correoValido((string) post('correo'));

    if ($correo !== null && accesoSeguroInstalado()) {
        $id = (int) traerValor('SELECT id FROM users WHERE email = ? AND email_verified_at IS NULL', [$correo]);

        if ($id > 0) {
            enviarVerificacion($id);
        }
    }

    mensaje('info', 'Si ese correo tiene una cuenta pendiente de confirmar, te enviamos un enlace nuevo. '
                  . 'Revisa también la carpeta de spam.');
    redirigir('verificar.php');
}

$titulo = 'Confirma tu correo';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>

<h1>Confirma tu correo</h1>
<p class="sub">
    Te enviamos un enlace para confirmar que el correo es tuyo. Ábrelo desde tu bandeja de
    entrada; si no lo ves, revisa la carpeta de spam.
</p>

<form method="post">
    <?= campoCsrf() ?>
    <label for="correo">¿No te llegó? Escribe tu correo y te mandamos otro</label>
    <input id="correo" name="correo" type="email" required maxlength="190" autocomplete="email">
    <button type="submit">Enviar un enlace nuevo</button>
</form>

<p class="pie"><a href="<?= e(url('login.php')) ?>">Volver a iniciar sesión</a></p>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
