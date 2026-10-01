<?php
/**
 * entrar-google.php — Manda a Google a elegir la cuenta
 *
 * Sirve para entrar y para crear cuenta: si el correo no existe todavía,
 * `google-volver.php` la crea ya verificada. Ver includes/google.php.
 */

require_once __DIR__ . '/config/config.php';

if (!googleConfigurado()) {
    mensaje('info', 'Entrar con Google todavía no está disponible. Usa tu correo y contraseña.');
    redirigir('login.php');
}

// Si venía a comprar, se conserva para después de entrar.
$comprar = trim((string) get('comprar'));
if ($comprar !== '' && planPorSlug($comprar)) {
    $_SESSION['google_comprar'] = $comprar;
}

header('Location: ' . googleUrlAutorizacion());
exit;
