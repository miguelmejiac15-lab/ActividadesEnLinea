<?php
/**
 * google-volver.php — Vuelta desde Google: aquí se decide si entra
 *
 * Orden de las comprobaciones, y ninguna sobra:
 *  1. ¿Hay credenciales? (una URL se puede escribir a mano)
 *  2. ¿Google devolvió un error? (la persona pudo cancelar)
 *  3. ¿El `state` coincide con el guardado? Sin esto, otra página podría
 *     provocar un ingreso.
 *  4. El código se canjea EN EL SERVIDOR por el perfil, y solo vale si
 *     Google dice que el correo está verificado.
 *
 * Esta es la dirección que se registra en Google Cloud como «URI de
 * redireccionamiento autorizado».
 */

require_once __DIR__ . '/config/config.php';

/** Vuelve al ingreso con un motivo legible. */
function cortarGoogle(string $motivo): never
{
    mensaje('error', $motivo);
    redirigir('login.php');
}

if (!googleConfigurado()) {
    cortarGoogle('Entrar con Google todavía no está disponible. Usa tu correo y contraseña.');
}

if (!empty($_GET['error'])) {
    cortarGoogle('No se completó el ingreso con Google.');
}

if (!googleStateValido(isset($_GET['state']) ? (string) $_GET['state'] : null)) {
    cortarGoogle('El ingreso con Google caducó o no empezó aquí. Inténtalo otra vez.');
}

$codigo = (string) ($_GET['code'] ?? '');

if ($codigo === '') {
    cortarGoogle('Google no devolvió el código de acceso.');
}

$r = googlePerfilDesdeCodigo($codigo);

if (!$r['ok']) {
    cortarGoogle('No pudimos verificar tu cuenta de Google: ' . $r['error']);
}

$cuenta = entrarConGoogle($r['perfil']);

if (!$cuenta['ok']) {
    cortarGoogle((string) $cuenta['error']);
}

$comprar = (string) ($_SESSION['google_comprar'] ?? '');
unset($_SESSION['google_comprar']);

if ($comprar !== '' && planPorSlug($comprar)) {
    redirigir('planes/suscribir.php?plan=' . urlencode($comprar));
}

mensaje('ok', $cuenta['nueva'] ? '¡Tu cuenta está lista! Ya puedes jugar.' : '¡Hola de nuevo!');
redirigir(destinoTrasLoginSinCompra());
