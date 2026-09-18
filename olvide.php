<?php
/**
 * olvide.php — «He olvidado mi contraseña»
 *
 * Pide el correo y manda un enlace. La pantalla responde LO MISMO exista
 * la cuenta o no: si dijera «ese correo no está registrado», el
 * formulario se convertiría en un comprobador de direcciones con el que
 * averiguar qué familias usan la plataforma.
 *
 * La única excepción es un fallo TÉCNICO —el correo no está configurado,
 * el servidor SMTP no responde—. Eso sí se dice, porque no revela nada de
 * ninguna cuenta y porque callarlo deja a la persona esperando un mensaje
 * que no va a llegar nunca.
 */

require_once __DIR__ . '/config/config.php';

// Quien ya entró no necesita esto: cambia su clave desde su cuenta.
if (haySesion()) {
    redirigir('usuario/');
}

$correo = '';
$hecho  = false;
$error  = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $correo = trim(post('correo'));
    $r      = pedirRecuperacion($correo);

    if ($r['ok']) {
        $hecho = true;
    } else {
        $error = $r['error'];
    }
}

$titulo = 'Recuperar mi contraseña';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>

<?php if ($hecho): ?>

    <h1>Revisa tu correo</h1>
    <p class="sub">
        Si <b><?= e($correo) ?></b> corresponde a una cuenta nuestra, acabamos de enviarle
        un enlace para elegir una contraseña nueva.
    </p>

    <p class="sub">
        El enlace sirve <b>una sola vez</b> y caduca en <?= RECUPERACION_MINUTOS ?> minutos.
        Si no lo ves, mira en la carpeta de correo no deseado.
    </p>

    <p class="pie">
        <a href="<?= e(url('login.php')) ?>">Volver a iniciar sesión</a>
    </p>

<?php else: ?>

    <h1>Recuperar mi contraseña</h1>
    <p class="sub">
        Escribe el correo con el que te registraste y te enviamos un enlace para elegir
        una nueva.
    </p>

    <?php if ($error !== null): ?>
        <div class="aviso mal"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post">
        <?= campoCsrf() ?>

        <label for="correo">Tu correo</label>
        <input id="correo" name="correo" type="email" required autofocus
               maxlength="190" value="<?= e($correo) ?>" placeholder="nombre@correo.com">

        <button type="submit">Enviarme el enlace</button>
    </form>

    <p class="pie">
        <a href="<?= e(url('login.php')) ?>">Volver a iniciar sesión</a>
    </p>

<?php endif; ?>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
