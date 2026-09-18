<?php
/**
 * aula/index.php — La lista de la clase
 *
 * Lo que ve un niño de seis años: la cara de todos sus compañeros y la
 * suya. Toca su nombre y entra.
 *
 * Está escrita para alguien que apenas lee: pocas palabras, muy grandes,
 * y una sola cosa que hacer en cada pantalla.
 *
 * Se llega por `/aula/Y66K7MR` (lo reescribe el .htaccess de al lado) o
 * por `/aula/` a secas, que pide el código.
 */

require_once dirname(__DIR__) . '/config/config.php';

$codigo = limpiarCodigo((string) (get('c') ?: get('codigo')));

$resultado = $codigo !== '' ? abrirPorCodigo($codigo) : ['curso' => null, 'motivo' => ''];
$curso     = $resultado['curso'];

$error     = null;
$cerrada   = false;
$pidePin   = false;
$alumnoPin = null;

if ($codigo !== '' && !$curso) {

    if (aulaFrenada()) {
        $error = 'Demasiados intentos. Espera ' . aulaEsperaMinutos() . ' minuto(s).';

    } elseif ($resultado['motivo'] === 'cerrada') {
        /*
         * «Cerrada» SÍ se distingue de «no existe», y es a propósito. Un
         * niño con el código bueno cuya clase todavía no abrió lo
         * teclearía diez veces creyendo que se equivocó. Decirle que
         * existe una clase con ese código no revela nada que no supiera:
         * el código se lo dio su profe.
         */
        $cerrada = true;

    } else {
        $error = 'Ese código no abre ninguna clase. Míralo otra vez.';
        aulaFallo();
    }
}

// ── Tocar un nombre ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $curso) {
    exigirCsrf();

    $alumnoId = (int) post('alumno');
    $pin      = trim((string) post('pin'));

    $r = entrarPorAula($curso, $alumnoId, $pin);

    if ($r['ok']) {
        mensaje('ok', '¡Hola! A jugar.');
        redirigir('usuario/');
    }

    $error     = $r['error'];
    $pidePin   = $r['pide_pin'];
    $alumnoPin = $pidePin ? $alumnoId : null;
}

$roster = $curso ? rosterDeAula((int) $curso['id']) : [];

$titulo = $curso ? $curso['name'] : 'Entrar a mi clase';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?></title>
<?php /* Una lista de menores no se indexa jamás. */ ?>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/aula.css')) ?>">
</head>
<body>

<header class="aula-cabeza">
    <span class="aula-logo">🎓 <?= e(ajuste('sitio_nombre', 'Actividades en Línea')) ?></span>
    <?php if ($curso): ?>
        <span class="aula-clase"><?= e($curso['name']) ?></span>
    <?php endif; ?>
</header>

<main class="aula-cuerpo">

<?php if ($cerrada): ?>

    <?php /* ── La clase todavía no está abierta ────────────────── */ ?>
    <div class="tarjeta-codigo">
        <span class="cara-grande" aria-hidden="true">⏰</span>
        <h1>Tu clase todavía no está abierta</h1>
        <p>
            El código está bien. Tu profe abrirá la clase cuando empiece.
            Vuelve a intentarlo en un rato.
        </p>

        <form method="get">
            <input type="hidden" name="c" value="<?= e($codigo) ?>">
            <button type="submit" class="boton-grande">Volver a probar</button>
        </form>

        <p class="aula-pie">
            ¿Tienes correo y contraseña?
            <a href="<?= e(url('login.php')) ?>">Entra por aquí</a>
        </p>
    </div>

<?php elseif (!$curso): ?>

    <?php /* ── Pedir el código ─────────────────────────────────── */ ?>
    <div class="tarjeta-codigo">
        <h1>Escribe el código de tu clase</h1>
        <p>Te lo dice tu profe. Son <?= AULA_LARGO ?> letras y números.</p>

        <?php if ($error !== null): ?>
            <p class="aula-error"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="get">
            <input type="text" name="c" class="campo-codigo"
                   value="<?= e($codigo) ?>"
                   maxlength="12" autofocus autocomplete="off"
                   autocapitalize="characters" spellcheck="false"
                   placeholder="ABC123"
                   aria-label="Código de la clase">
            <button type="submit" class="boton-grande">Entrar</button>
        </form>

        <p class="aula-pie">
            ¿Tienes correo y contraseña?
            <a href="<?= e(url('login.php')) ?>">Entra por aquí</a>
        </p>
    </div>

<?php elseif ($pidePin && $alumnoPin !== null): ?>

    <?php
    /* ── Pedir el PIN ─────────────────────────────────────────────
     *
     * Pantalla aparte, con el nombre del niño delante, para que sepa
     * que está entrando en SU cuenta y no en la de otro.
     */
    $quien = null;
    foreach ($roster as $r) {
        if ((int) $r['id'] === $alumnoPin) { $quien = $r; }
    }
    ?>

    <div class="tarjeta-codigo">
        <?php if ($quien): ?>
            <div class="cara-grande"><?= personajeHtml($quien, 'grande') ?></div>
            <h1><?= e($quien['name']) ?></h1>
        <?php endif; ?>

        <p>Escribe tu PIN de 4 números.</p>

        <?php if ($error !== null): ?>
            <p class="aula-error"><?= e($error) ?></p>
        <?php endif; ?>

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="alumno" value="<?= (int) $alumnoPin ?>">

            <input type="text" name="pin" class="campo-codigo campo-pin"
                   inputmode="numeric" pattern="[0-9]*" maxlength="4"
                   autofocus autocomplete="off" placeholder="••••"
                   aria-label="Tu PIN de cuatro números">

            <button type="submit" class="boton-grande">Entrar</button>
        </form>

        <p class="aula-pie">
            <a href="<?= e(urlDeAula($codigo)) ?>">← Volver a la lista</a>
        </p>
    </div>

<?php else: ?>

    <?php /* ── La lista de la clase ────────────────────────────── */ ?>
    <div class="tarjeta-lista">

        <h1>Toca tu nombre</h1>

        <?php if ($error !== null): ?>
            <p class="aula-error"><?= e($error) ?></p>
        <?php endif; ?>

        <?php if (!$roster): ?>
            <p class="aula-vacio">
                Todavía no hay nadie en esta clase.<br>
                Avisa a tu profe.
            </p>
        <?php else: ?>
            <ul class="lista-ninos">
                <?php foreach ($roster as $r): ?>
                    <li>
                        <form method="post">
                            <?= campoCsrf() ?>
                            <input type="hidden" name="alumno" value="<?= (int) $r['id'] ?>">
                            <button type="submit" class="fila-nino">
                                <span class="cara"><?= personajeHtml($r, 'chico') ?></span>
                                <span class="nombre"><?= e($r['name']) ?></span>
                                <span class="jugar">JUGAR</span>
                            </button>
                        </form>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>

        <p class="aula-pie">
            ¿No eres de esta clase?
            <a href="<?= e(url('aula/')) ?>">Escribe otro código</a>
        </p>
    </div>

<?php endif; ?>

</main>

</body>
</html>
