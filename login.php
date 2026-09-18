<?php
/**
 * login.php — Inicio de sesión
 *
 * Toda la lógica vive en includes/auth.php; aquí solo se recogen los
 * datos, se valida el token CSRF y se muestra el resultado.
 */

require_once __DIR__ . '/config/config.php';

/*
 * `destinoTrasLogin()` vive ahora en `includes/auth.php`, para que
 * `registro.php` use exactamente la misma. Ver el comentario de allí:
 * tenerla aquí dentro dejaba al registro leyendo el destino en crudo y
 * mandando a un 404 a quien acababa de crear su cuenta para pagar.
 */

/*
 * Quien ya tiene sesión no necesita este formulario — ni siquiera si
 * llega por POST.
 *
 * Eso último es lo que hace el login **idempotente**: si alguien pulsa
 * «entrar» dos veces, o la conexión va lenta y vuelve a darle, la segunda
 * petición no intenta autenticar otra vez ni enseña un error: lo lleva
 * donde iba, que es lo que esperaba.
 */
if (haySesion() && usuarioActual()) {
    // Con sesión y con intención de comprar, directo a su plan: es lo que
    // pasa cuando alguien abre el enlace de compra en otra pestaña.
    $yaCompra = trim((string) get('comprar'));

    if ($yaCompra !== '' && planPorSlug($yaCompra)) {
        redirigir('planes/suscribir.php?plan=' . urlencode($yaCompra));
    }

    redirigir($_SERVER['REQUEST_METHOD'] === 'POST' ? destinoTrasLoginSinCompra() : 'index.php');
}

$error  = null;
$correo = '';

/*
 * ¿Viene de la pantalla de compra? Lo dice `?comprar=<plan>` y solo eso.
 *
 * Se lee antes del POST porque el formulario tiene que devolverlo: sin
 * arrastrarlo, un error de contraseña perdería el hilo de la compra y el
 * cliente acabaría en su espacio preguntándose qué pasó con el pago.
 */
$planCompra = ($c = trim((string) get('comprar'))) !== '' ? planPorSlug($c) : null;

if ($planCompra && (int) $planCompra['is_active'] !== 1) {
    $planCompra = null;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $correo = post('email');
    $r = iniciarSesion($correo, (string) ($_POST['password'] ?? ''));

    if ($r['ok']) {
        if ($planCompra) {
            mensaje('ok', '¡Hola de nuevo! Sigamos con el pago.');
            redirigir('planes/suscribir.php?plan=' . urlencode((string) $planCompra['slug']));
        }

        mensaje('ok', '¡Hola de nuevo!');
        redirigir(destinoTrasLoginSinCompra());
    }

    $error = $r['error'];
}

$titulo = 'Iniciar sesión';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>

<h1>Iniciar sesión</h1>

<?php if ($planCompra): ?>
    <p class="sub">
        Entra con tu cuenta y sigues con el pago de
        <b><?= e($planCompra['name']) ?></b> en esta misma ventana.
    </p>
<?php else: ?>
    <p class="sub">Entra para guardar tu progreso y tus actividades favoritas.</p>
<?php endif; ?>

<?php if ($error): ?>
    <div class="aviso mal"><?= e($error) ?></div>
<?php endif; ?>

<form method="post" autocomplete="on">
    <?= campoCsrf() ?>

    <label for="email">Correo</label>
    <input id="email" name="email" type="email" value="<?= e($correo) ?>" required autofocus>

    <label for="password">Contraseña</label>
    <input id="password" name="password" type="password" required>

    <button type="submit">Entrar</button>
</form>

<?php /* Quien no recuerda su clave la busca aquí, no en el pie del sitio. */ ?>
<p class="pie" style="margin-top:14px">
    <a href="<?= e(url('olvide.php')) ?>">¿Olvidaste tu contraseña?</a>
</p>

<p class="pie">
    <?php if ($planCompra): ?>
        ¿Todavía no tienes cuenta?
        <a href="<?= e(url('registro.php?comprar=' . urlencode((string) $planCompra['slug']))) ?>">
            Créala y sigue con el pago
        </a>
    <?php else: ?>
        ¿Todavía no tienes cuenta?
        <a href="<?= e(url('registro.php')) ?>">Crear una cuenta gratis</a>
    <?php endif; ?>
</p>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
