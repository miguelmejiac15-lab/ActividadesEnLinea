<?php
/**
 * escuela/tarjetas.php — La hoja para repartir
 *
 * Se llama así y no `credenciales.php` porque el `.htaccess` de la raíz
 * bloquea cualquier archivo con ese nombre —una regla puesta para que
 * nadie pueda pedir `config/credenciales.php` por HTTP— y `<Files>`
 * casa por nombre en cualquier carpeta. Con el otro nombre, esta página
 * respondía 403 sin más explicación.
 *
 * Una tarjeta por niño, para recortar y pegar en el cuaderno: su nombre,
 * la dirección de la clase, su usuario y su PIN.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ UN PIN NO SE PUEDE «VOLVER A VER»
 * ─────────────────────────────────────────────────────────────────────
 *
 * En la base solo queda cifrado, igual que una contraseña, y eso no
 * tiene vuelta atrás por diseño. Así que esta hoja solo puede enseñar
 * los PIN que **se acaban de generar**.
 *
 * Se dice aquí, en grande, porque la versión anterior no lo decía y el
 * resultado fue exactamente el previsible: generar el PIN, perderlo de
 * vista y no encontrar dónde volver a mirarlo.
 *
 * El flujo que sí funciona es: generar → imprimir → repartir. Si un niño
 * pierde el suyo, se le rehace el de él solo. Rehacer los de toda la
 * clase deja fuera a los que ya se lo sabían de memoria, y por eso pide
 * confirmación.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

if (!aulaInstalada()) {
    mensaje('error', 'Falta la migración del aula.');
    redirigir('escuela/curso.php?id=' . $cursoId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');

    if ($accion === 'generar' || $accion === 'rehacer_todos') {
        $nuevos = generarPinesDelCurso($cursoId, $accion === 'generar');

        // Se guardan para la carga siguiente y se borran al leerlos: no
        // quedan como respuesta a un POST reenviable.
        $_SESSION['pines_hoja'] = [];
        foreach ($nuevos as $n) {
            $_SESSION['pines_hoja'][$n['nombre']] = $n['pin'];
        }

        mensaje($nuevos ? 'ok' : 'info',
            $nuevos ? count($nuevos) . ' PIN generados. Ya están en la hoja.'
                    : 'Todos tenían PIN. Usa «rehacer todos» si quieres cambiarlos.');

        redirigir('escuela/tarjetas.php?curso=' . $cursoId);
    }

    if ($accion === 'rehacer_uno') {
        $uid = (int) post('usuario');
        $r   = ponerPinDeEstudiante($cursoId, $uid);

        if ($r['ok']) {
            $a = traerUno('SELECT name FROM users WHERE id = ?', [$uid]);
            $_SESSION['pines_hoja'] = array_merge($_SESSION['pines_hoja'] ?? [],
                                                  [$a['name'] => $r['pin']]);
            mensaje('ok', 'PIN nuevo. Está en la hoja.');
        } else {
            mensaje('error', $r['error']);
        }

        redirigir('escuela/tarjetas.php?curso=' . $cursoId);
    }
}

$recien = $_SESSION['pines_hoja'] ?? [];
unset($_SESSION['pines_hoja']);

$modo   = modoAcceso($curso);
$conPin = (int) ($curso['aula_pin'] ?? 0) === 1;
$codigo = $curso['access_code'] ?? null;

$estudiantes = traerTodo(
    'SELECT u.id, u.name, u.email, u.role, u.avatar, u.accessory,
            (u.pin_hash IS NOT NULL) AS tiene_pin
       FROM course_students cs JOIN users u ON u.id = cs.user_id
      WHERE cs.course_id = ? ORDER BY u.name',
    [$cursoId]
);

$sinPin = count(array_filter($estudiantes,
    fn($a) => $a['role'] === 'student' && !$a['tiene_pin']));

$titulo      = 'Hoja para repartir · ' . $curso['name'];
$escuelaZona = 'acceso';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza no-imprimir">
    <div>
        <h1>Hoja para repartir</h1>
        <p class="bajada"><?= e($curso['name']) ?> · una tarjeta por estudiante</p>
    </div>
    <div class="acciones-cabeza">
        <button class="btn btn-principal btn-chico" type="button" onclick="window.print()">
            🖨️ Imprimir
        </button>
        <a class="btn btn-secundario btn-chico"
           href="<?= e(url('escuela/acceso.php?curso=' . $cursoId)) ?>">← Volver</a>
    </div>
</header>


<?php /* ── Lo que hay que saber, antes de la hoja ──────────────────── */ ?>
<section class="bloque-panel no-imprimir">
    <h2>Antes de imprimir</h2>

    <p class="nota-panel">
        <strong>Un PIN solo se puede ver una vez, justo al generarlo.</strong>
        En la base queda cifrado, igual que una contraseña, y eso no tiene vuelta atrás.
        Lo que funciona es: <b>generar → imprimir → repartir</b>. Si un niño pierde el
        suyo, se le rehace solo el de él con el botón de su tarjeta.
    </p>

    <?php if ($conPin): ?>
        <div class="pie-bloque">
            <?php if ($sinPin > 0): ?>
                <form method="post">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="generar">
                    <button class="btn btn-principal" type="submit">
                        Generar los <?= $sinPin ?> PIN que faltan
                    </button>
                </form>
            <?php endif; ?>

            <form method="post"
                  onsubmit="return confirm('¿Rehacer TODOS los PIN de la clase?\n\nLos niños que ya se sabían el suyo de memoria dejarán de poder entrar hasta que les des el nuevo.')">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="rehacer_todos">
                <button class="btn btn-secundario btn-chico" type="submit">
                    Rehacer todos los PIN
                </button>
            </form>
        </div>
    <?php else: ?>
        <p class="nota-panel">
            Este curso <b>no pide PIN</b>: los niños entran tocando su nombre y ya.
            Las tarjetas llevan solo la dirección y su usuario.
            <a href="<?= e(url('escuela/acceso.php?curso=' . $cursoId)) ?>">Cambiarlo</a>
        </p>
    <?php endif; ?>
</section>


<?php /* ── La hoja ──────────────────────────────────────────────────── */ ?>

<?php if (!$estudiantes): ?>
    <section class="bloque-panel no-imprimir">
        <p class="nota-panel">
            Todavía no hay estudiantes en este curso.
            <a href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">Matricúlalos</a>.
        </p>
    </section>
<?php else: ?>

    <div class="hoja-tarjetas">
        <?php foreach ($estudiantes as $a): ?>
            <?php
            $pinRecien = $recien[$a['name']] ?? null;
            $esCuenta  = $a['role'] !== 'student';
            ?>
            <article class="tarjeta-alumno">

                <div class="tarjeta-nombre">
                    <span class="tarjeta-cara"><?= personajeHtml($a, 'chico') ?></span>
                    <b><?= e($a['name']) ?></b>
                </div>

                <?php if ($modo !== 'cuenta' && $codigo): ?>
                    <div class="tarjeta-linea">
                        <span class="tl-eti">Entra en</span>
                        <span class="tl-val url"><?= e(urlDeAula((string) $codigo)) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($modo === 'lista'): ?>
                    <div class="tarjeta-linea">
                        <span class="tl-eti">Y toca tu nombre</span>
                    </div>
                <?php else: ?>
                    <div class="tarjeta-linea">
                        <span class="tl-eti">Usuario</span>
                        <span class="tl-val mono"><?= e($a['email']) ?></span>
                    </div>
                <?php endif; ?>

                <?php if ($conPin && !$esCuenta): ?>
                    <div class="tarjeta-linea pin">
                        <span class="tl-eti">Tu PIN</span>
                        <?php if ($pinRecien !== null): ?>
                            <span class="tl-pin"><?= e($pinRecien) ?></span>
                        <?php elseif ($a['tiene_pin']): ?>
                            <span class="tl-val tenue">•••• ya entregado</span>
                        <?php else: ?>
                            <span class="tl-val tenue">sin generar</span>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>

                <?php if ($esCuenta): ?>
                    <p class="tarjeta-nota">
                        Cuenta familiar: entra con su correo y su contraseña.
                    </p>
                <?php endif; ?>

                <?php if ($conPin && !$esCuenta): ?>
                    <form method="post" class="no-imprimir tarjeta-accion"
                          onsubmit="return confirm('¿Rehacer el PIN de <?= e($a['name']) ?>?')">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="rehacer_uno">
                        <input type="hidden" name="usuario" value="<?= (int) $a['id'] ?>">
                        <button class="btn btn-secundario btn-chico" type="submit">
                            <?= $a['tiene_pin'] ? 'Rehacer su PIN' : 'Generar su PIN' ?>
                        </button>
                    </form>
                <?php endif; ?>
            </article>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
