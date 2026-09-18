<?php
/**
 * restablecer.php — Elegir una contraseña nueva con el enlace del correo
 *
 * El testigo llega por la dirección (`?t=…`) y se comprueba DOS veces:
 * al pintar el formulario y al recibirlo. Comprobarlo solo al principio
 * dejaría un hueco —el enlace puede caducar o usarse desde otra pestaña
 * mientras la persona escribe— y el segundo intento pasaría sin más.
 *
 * El testigo viaja en un campo oculto y no en la dirección del formulario
 * a propósito: así no acaba en el `Referer` que el navegador manda a
 * terceros si la página cargara algo externo.
 */

require_once __DIR__ . '/config/config.php';

$testigo  = trim(get('t'));
$error    = null;
$listo    = false;

// Se resuelve antes de nada: si el enlace no sirve, no hay formulario que
// enseñar y decirlo cuanto antes ahorra escribir una contraseña en vano.
$peticion = $testigo !== '' ? recuperacionPorTestigo($testigo) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $peticion) {
    exigirCsrf();

    $nueva   = (string) ($_POST['password'] ?? '');
    $repetir = (string) ($_POST['password2'] ?? '');

    if ($nueva !== $repetir) {
        $error = 'Las dos contraseñas no coinciden.';
    } else {
        $r = usarRecuperacion((string) post('t'), $nueva);

        if ($r['ok']) {
            /*
             * Se cierra la sesión actual del navegador antes de dar por
             * buena la nueva contraseña. Quien recupera su cuenta suele
             * hacerlo porque cree que alguien entró; empezar de cero es lo
             * que espera.
             */
            $_SESSION = [];
            session_regenerate_id(true);

            $listo = true;
        } else {
            $error = $r['error'];
        }
    }
}

$titulo = 'Elegir una contraseña nueva';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>

<?php if ($listo): ?>

    <h1>Contraseña cambiada</h1>
    <p class="sub">
        Ya puedes entrar con tu contraseña nueva. Si tenías la sesión abierta en otro
        dispositivo, tendrás que volver a entrar allí también.
    </p>

    <p class="pie">
        <a href="<?= e(url('login.php')) ?>">Iniciar sesión</a>
    </p>

<?php elseif (!$peticion): ?>

    <h1>Ese enlace ya no sirve</h1>
    <p class="sub">
        Los enlaces de recuperación caducan a los <?= RECUPERACION_MINUTOS ?> minutos y
        se pueden usar una sola vez. Puede que este ya se usara, o que pasara demasiado
        tiempo.
    </p>
    <p class="sub">No pasa nada: pide uno nuevo y te llega en un momento.</p>

    <p class="pie">
        <a href="<?= e(url('olvide.php')) ?>">Pedir un enlace nuevo</a>
    </p>

<?php else: ?>

    <h1>Elige una contraseña nueva</h1>
    <p class="sub">
        Para la cuenta de <b><?= e($peticion['name']) ?></b>.
    </p>

    <?php if ($error !== null): ?>
        <div class="aviso mal"><?= e($error) ?></div>
    <?php endif; ?>

    <form method="post" autocomplete="off">
        <?= campoCsrf() ?>
        <input type="hidden" name="t" value="<?= e($testigo) ?>">

        <label for="password">Contraseña nueva</label>
        <input id="password" name="password" type="password" required autofocus
               minlength="8" maxlength="200" autocomplete="new-password">

        <label for="password2">Repítela</label>
        <input id="password2" name="password2" type="password" required
               minlength="8" maxlength="200" autocomplete="new-password">

        <button type="submit">Guardar la contraseña</button>
    </form>

    <p class="pie">
        Al menos 8 caracteres. Mejor una frase que recuerdes que algo corto y raro.
    </p>

<?php endif; ?>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
