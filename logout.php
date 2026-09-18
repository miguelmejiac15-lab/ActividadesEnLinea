<?php
/**
 * logout.php — Cerrar sesión
 *
 * Solo por POST con token CSRF: si aceptara GET, bastaría con que
 * alguien indujera al usuario a cargar una imagen apuntando a esta URL
 * para sacarlo de su sesión.
 */

require_once __DIR__ . '/config/config.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();
    cerrarSesion();
    session_start();
    mensaje('ok', 'Cerraste sesión. ¡Hasta pronto!');
    redirigir('index.php');
}

$titulo = 'Cerrar sesión';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>

<h1>¿Cerrar sesión?</h1>
<p class="sub">Tu progreso queda guardado y estará aquí cuando vuelvas.</p>

<form method="post">
    <?= campoCsrf() ?>
    <button type="submit">Sí, cerrar sesión</button>
</form>

<p class="pie"><a href="<?= e(url('index.php')) ?>">Volver</a></p>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
