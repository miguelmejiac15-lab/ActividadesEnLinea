<?php
/**
 * registro.php — Crear una cuenta
 *
 * PRIVACIDAD (Decreto 0769 de 2026): la plataforma trata datos de
 * menores. Por eso se pide solo el año de nacimiento (no la fecha
 * completa) y, si la cuenta es de un menor de 14 años, el correo de un
 * adulto responsable. No se pide ningún dato que no se vaya a usar.
 */

require_once __DIR__ . '/config/config.php';

if (haySesion() && usuarioActual()) {
    // Con sesión y con intención de comprar, a su plan. Pasa cuando se
    // abre el enlace de compra en otra pestaña.
    $yaCompra = trim((string) get('comprar'));

    redirigir($yaCompra !== '' && planPorSlug($yaCompra)
        ? 'planes/suscribir.php?plan=' . urlencode($yaCompra)
        : 'index.php');
}

/*
 * ---------------------------------------------------------------------
 *  ¿VIENE A COMPRAR O A PROBAR?
 * ---------------------------------------------------------------------
 *
 * Lo dice `?comprar=<plan>` y solo eso. Sin el parámetro, esta pantalla
 * es la de crear una cuenta gratis y no menciona el dinero por ninguna
 * parte — que es como tiene que ser: quien viene a probar no ha pedido
 * que le vendan nada.
 *
 * Estuvo en la sesión y fue un error: se quedaba pegada. Quien se asomaba
 * al precio, se lo pensaba mejor y venía aquí por el menú, se encontraba
 * el plan nombrado y un botón que decía «seguir al pago».
 */
$planCompra = ($c = trim((string) get('comprar'))) !== '' ? planPorSlug($c) : null;

if ($planCompra && (int) $planCompra['is_active'] !== 1) {
    $planCompra = null;
}

$errores = [];
$v = ['name' => '', 'email' => '', 'birth_year' => '', 'guardian_email' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $v['name']           = post('name');
    $v['email']          = post('email');
    $v['birth_year']     = post('birth_year');
    $v['guardian_email'] = post('guardian_email');

    $r = registrarUsuario(
        $v['name'],
        $v['email'],
        (string) ($_POST['password'] ?? ''),
        $v['guardian_email'] !== '' ? $v['guardian_email'] : null,
        $v['birth_year'] !== '' ? (int) $v['birth_year'] : null
    );

    if ($r['ok']) {
        // Se inicia sesión de una vez: una cuenta nueva no debería
        // obligar a escribir la contraseña dos veces seguidas.
        iniciarSesion($v['email'], (string) ($_POST['password'] ?? ''));

        /*
         * A dónde va ahora.
         *
         * Si vino a comprar, a la pantalla de su plan. Si no, a donde
         * fuera que iba —la actividad que quería jugar— y si no iba a
         * ningún sitio, a su espacio.
         *
         * `destinoTrasLoginSinCompra()` y no `destinoTrasLogin()` a
         * secas: esa descarta un destino que sea una pantalla de empezar
         * a comprar. Quien crea una cuenta gratis no puede acabar en una
         * caja registradora por haberse asomado antes.
         */
        if ($planCompra) {
            mensaje('ok', '¡Tu cuenta está lista! Ahora, el pago.');
            redirigir('planes/suscribir.php?plan=' . urlencode((string) $planCompra['slug']));
        }

        mensaje('ok', '¡Tu cuenta está lista! Ya puedes jugar.');
        redirigir(destinoTrasLoginSinCompra());
    }

    $errores = $r['errores'];
}

// `$planCompra` se resolvió arriba, desde `?comprar=`. Si viene, esta
// pantalla no es «crear una cuenta gratis»: es el primer paso de un pago,
// y decir lo contrario le haría dudar justo cuando iba a pagar.
$titulo = $planCompra ? 'Crear cuenta y pagar' : 'Crear cuenta';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>

<?php if ($planCompra): ?>

    <h1>Primero, tu cuenta</h1>
    <p class="sub">
        Estás contratando <b><?= e($planCompra['name']) ?></b>. Creas la cuenta aquí y
        sigues al pago sin salir de esta ventana.
    </p>

    <ol class="pasos-compra" aria-label="Pasos">
        <li class="ahora"><span>1</span> Tu cuenta</li>
        <li><span>2</span> Elegir cómo pagar</li>
        <li><span>3</span> Pagar</li>
    </ol>

<?php else: ?>

    <h1>Crear una cuenta gratis</h1>
    <p class="sub">Explora todas las actividades y juega la primera parte de cada una, sin tarjeta de crédito.</p>

<?php endif; ?>

<?php if ($errores): ?>
    <div class="aviso mal">
        <strong>Revisa estos campos:</strong>
        <ul><?php foreach ($errores as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<form method="post" autocomplete="on">
    <?= campoCsrf() ?>

    <label for="name">Nombre</label>
    <input id="name" name="name" class="<?= isset($errores['name']) ? 'campo-error' : '' ?>"
           value="<?= e($v['name']) ?>" required maxlength="120">

    <label for="email">Correo</label>
    <input id="email" name="email" type="email" class="<?= isset($errores['email']) ? 'campo-error' : '' ?>"
           value="<?= e($v['email']) ?>" required maxlength="190">

    <label for="password">Contraseña</label>
    <input id="password" name="password" type="password" class="<?= isset($errores['password']) ? 'campo-error' : '' ?>"
           required minlength="8">
    <p class="pista">Mínimo 8 caracteres.</p>

    <div class="fila">
        <div>
            <label for="birth_year">Año de nacimiento</label>
            <input id="birth_year" name="birth_year" type="number" inputmode="numeric"
                   class="<?= isset($errores['birth_year']) ? 'campo-error' : '' ?>"
                   value="<?= e($v['birth_year']) ?>"
                   min="<?= (int) date('Y') - 100 ?>" max="<?= (int) date('Y') ?>" placeholder="Opcional">
        </div>
        <div>
            <label for="guardian_email">Correo del adulto responsable</label>
            <input id="guardian_email" name="guardian_email" type="email"
                   class="<?= isset($errores['guardian_email']) ? 'campo-error' : '' ?>"
                   value="<?= e($v['guardian_email']) ?>" placeholder="Si eres menor de 14">
        </div>
    </div>
    <p class="pista">
        Solo pedimos el año, no la fecha completa. Si la cuenta es de un menor de 14 años,
        necesitamos el correo de un adulto responsable.
    </p>

    <button type="submit"><?= $planCompra ? 'Crear cuenta y seguir al pago' : 'Crear mi cuenta' ?></button>
</form>

<p class="pie">
    ¿Ya tienes cuenta?
    <?php if ($planCompra): ?>
        <a href="<?= e(url('login.php?comprar=' . urlencode((string) $planCompra['slug']))) ?>">
            Entra y sigue con el pago
        </a>
    <?php else: ?>
        <a href="<?= e(url('login.php')) ?>">Iniciar sesión</a>
    <?php endif; ?>
</p>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
