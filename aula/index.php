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

/*
 * ─────────────────────────────────────────────────────────────────────
 *  EL ENLACE PERSONAL: UN SOLO NOMBRE
 * ─────────────────────────────────────────────────────────────────────
 *
 * `?yo=…` es el enlace que el docente manda a la casa de cada niño. La
 * página enseña solo su nombre —ni la lista de la clase ni los de sus
 * compañeros— y abre aunque la clase esté cerrada: es para practicar en
 * casa. Ver «EL ENLACE PERSONAL» en includes/aula.php.
 *
 * Un enlace personal que no cuadra NO cae en la lista de la clase: quien
 * lo tenga recibiría los nombres de todos, que es lo que se quería evitar.
 */
$yo          = trim((string) get('yo'));
$soloAlumno  = null;
$enlaceMalo  = false;

if ($yo !== '' && $codigo !== '') {
    $cursoCasa  = cursoDeEnlacePersonal($codigo);
    $soloAlumno = $cursoCasa ? alumnoDeEnlace($cursoCasa, $yo) : null;

    if ($soloAlumno) {
        $curso     = $cursoCasa;
        $resultado = ['curso' => $cursoCasa, 'motivo' => 'ok'];
    } else {
        $curso      = null;
        $resultado  = ['curso' => null, 'motivo' => 'enlace'];
        $enlaceMalo = true;
        $error      = 'Este enlace ya no sirve. Pídele a tu profe uno nuevo.';
        aulaFallo();
    }
}

if ($codigo !== '' && !$curso && !$enlaceMalo) {

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

/*
 * ─────────────────────────────────────────────────────────────────────
 *  EL PROFESOR PRUEBA EL ENLACE: VISTA PREVIA, NO CAMBIO DE SESIÓN
 * ─────────────────────────────────────────────────────────────────────
 *
 * El docente abre el enlace que va a mandar para ver cómo queda, toca un
 * nombre… y su sesión pasaba a ser la del niño: perdía el panel a mitad
 * de la clase. Con una sesión de docente abierta, esta página es una
 * vista previa: se ve igual, pero tocar un nombre no entra.
 *
 * Si de verdad quiere dejar que un niño entre en ESTE equipo, lo dice
 * con un botón aparte —«ceder»—, que cierra su sesión primero.
 */
$vistaDocente = usuarioActual() !== null && puedeEntrarAEscuela();
$previa       = null;   // el nombre que el docente tocó en la vista previa

// ── Tocar un nombre ──────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $curso) {
    exigirCsrf();

    $alumnoId = (int) post('alumno');
    $pin      = trim((string) post('pin'));

    // Con el enlace personal solo se puede entrar con SU nombre.
    if ($soloAlumno && $alumnoId !== (int) $soloAlumno['id']) {
        $alumnoId = 0;
    }

    if ($vistaDocente && post('ceder') === '1') {
        cerrarSesion();
        session_start();
        $vistaDocente = false;

    } elseif ($vistaDocente) {
        foreach (rosterDeAula((int) $curso['id']) as $r) {
            if ((int) $r['id'] === $alumnoId) { $previa = $r; }
        }
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && $curso && !$vistaDocente) {
    $alumnoId = (int) post('alumno');
    $pin      = trim((string) post('pin'));

    if ($soloAlumno && $alumnoId !== (int) $soloAlumno['id']) {
        $alumnoId = 0;
    }

    $r = entrarPorAula($curso, $alumnoId, $pin);

    if ($r['ok']) {
        /*
         * Si el docente puso la actividad de hoy, se entra directo a
         * ella. Es la diferencia entre un niño que ya está trabajando y
         * uno que levanta la mano para preguntar cuál era.
         *
         * Si no la puso —o la actividad se despublicó desde ayer—,
         * `actividadDeHoy()` devuelve null y se va al espacio de
         * siempre: por esto nadie se queda sin entrar.
         */
        $hoy = function_exists('actividadDeHoy') ? actividadDeHoy($curso) : null;

        if ($hoy) {
            mensaje('ok', '¡Hola! A jugar con ' . $hoy['title'] . '.');
            redirigir('actividades/jugar.php?a=' . urlencode((string) $hoy['slug']));
        }

        mensaje('ok', '¡Hola! A jugar.');
        redirigir('usuario/');
    }

    $error     = $r['error'];
    $pidePin   = $r['pide_pin'];
    $alumnoPin = $pidePin ? $alumnoId : null;
}

$roster = $soloAlumno ? [$soloAlumno] : ($curso ? rosterDeAula((int) $curso['id']) : []);

$titulo = $curso ? $curso['name'] : 'Entrar a mi clase';

// «Volver» lleva a donde se llegó: con el enlace personal, a ese mismo.
$volverA = $soloAlumno ? urlPersonalDeAula($curso, (int) $soloAlumno['id']) : urlDeAula($codigo);
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

<?php /* El favicon y la verificacion tambien aqui: estas dos paginas
         tienen <head> propio, y son las que mas ven los ninos. La
         verificacion no envia nada a Google; solo acredita el dominio. */ ?>
<link rel="icon" type="image/svg+xml" href="<?= e(urlRecurso('assets/marca/lapiz.svg')) ?>">
<?php if (function_exists('gscVerificacion') && gscVerificacion() !== ''): ?>
<meta name="google-site-verification" content="<?= e(gscVerificacion()) ?>">
<?php endif; ?>
</head>
<body>

<header class="aula-cabeza">
    <span class="aula-logo">🎓 <?= e(ajuste('sitio_nombre', 'Actividades en Línea')) ?></span>
    <?php if ($curso): ?>
        <span class="aula-clase"><?= e($curso['name']) ?></span>
    <?php endif; ?>
</header>

<main class="aula-cuerpo">

<?php if ($vistaDocente && $curso): ?>
    <div class="aula-aviso-docente" role="status">
        <b>👀 Vista previa del profesor.</b>
        Así verán esta página tus estudiantes. Tu sesión sigue abierta: tocar un nombre
        aquí no entra a la cuenta del niño.
        <a href="<?= e(url('escuela/')) ?>">← Volver al panel</a>
    </div>
<?php endif; ?>

<?php if ($previa): ?>

    <?php /* ── El profesor tocó un nombre en la vista previa ──────── */ ?>
    <div class="tarjeta-codigo">
        <div class="cara-grande"><?= personajeHtml($previa, 'grande') ?></div>
        <h1><?= e($previa['name']) ?></h1>
        <p>
            Aquí <?= e($previa['name']) ?> entraría a su espacio
            <?php if ((int) ($curso['aula_pin'] ?? 0) === 1): ?>después de escribir su PIN<?php endif; ?>.
            Como tienes tu sesión de profesor abierta, no te sacamos de ella.
        </p>
        <p class="aula-pie">
            Para probarlo como estudiante, abre el enlace en una ventana de incógnito
            o en otro navegador.
        </p>

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="alumno" value="<?= (int) $previa['id'] ?>">
            <input type="hidden" name="ceder" value="1">
            <button type="submit" class="boton-secundario"
                    onclick="return confirm('Se cerrará tu sesión de profesor en este equipo para que entre <?= e($previa['name']) ?>. ¿Seguir?')">
                Dejar entrar a <?= e($previa['name']) ?> en este equipo
            </button>
        </form>

        <p class="aula-pie">
            <a href="<?= e($volverA) ?>">← Volver a la lista</a>
        </p>
    </div>

<?php elseif ($cerrada): ?>

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
            <a href="<?= e($volverA) ?>">← Volver a la lista</a>
        </p>
    </div>

<?php else: ?>

    <?php /* ── La lista de la clase ────────────────────────────── */ ?>
    <div class="tarjeta-lista">

        <h1><?= $soloAlumno ? '¡Hola! Toca tu nombre para entrar' : 'Toca tu nombre' ?></h1>

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

        <?php if (!$soloAlumno): ?>
            <p class="aula-pie">
                ¿No eres de esta clase?
                <a href="<?= e(url('aula/')) ?>">Escribe otro código</a>
            </p>
        <?php endif; ?>
    </div>

<?php endif; ?>

</main>

</body>
</html>
