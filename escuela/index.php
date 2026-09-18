<?php
/**
 * escuela/index.php — Los cursos del docente
 *
 * La primera pantalla del área. Muestra los cursos que tiene a cargo y
 * deja crear uno nuevo sin salir de aquí: un docente que entra por primera
 * vez no tiene ningún curso, y mandarlo a otra página para crear el
 * primero convierte una pantalla vacía en un callejón.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$docenteId = usuarioActualId();

// ── Crear un curso ───────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('accion') === 'crear') {
    exigirCsrf();

    $nombre = trim(post('nombre'));
    $grado  = trim(post('grado'));
    $anio   = getEntero('anio') ?: (int) post('anio');

    if ($nombre === '') {
        mensaje('error', 'El curso necesita un nombre.');
    } else {
        $id = crearCurso($docenteId, $nombre, $grado, $anio ?: (int) date('Y'));
        mensaje('ok', 'Curso creado. Ahora matricula a tus estudiantes.');
        redirigir('escuela/curso.php?id=' . $id);
    }
}

$cursos = cursosDelDocente($docenteId);

$titulo      = 'Mis cursos';
$escuelaZona = 'cursos';
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1>Mis cursos</h1>
        <p class="bajada">Cada curso tiene sus estudiantes, sus actividades asignadas y su progreso.</p>
    </div>
</header>

<?php if (!$cursos): ?>

    <div class="vacio-escuela">
        <span class="vacio-emoji" aria-hidden="true">📚</span>
        <h2>Todavía no tienes ningún curso</h2>
        <p>
            Crea el primero abajo. Después podrás matricular a tus estudiantes por su
            correo, asignarles actividades del catálogo y ver cómo va cada uno.
        </p>

        <?php
        /*
         * Para quien coordina una institución esto no es un comienzo, es
         * un callejón: no da clase, así que aquí no va a tener nunca
         * nada. Lo suyo está un piso más arriba.
         */
        ?>
        <?php if ($institucion = institucionDeAdmin()): ?>
            <p>
                O ve a <a href="<?= e(url('escuela/institucion.php')) ?>"><strong><?=
                    e($institucion['name']) ?></strong></a>, donde están los cursos de todos
                sus docentes.
            </p>
        <?php endif; ?>
    </div>

<?php else: ?>

    <div class="rejilla-cursos">
        <?php foreach ($cursos as $c): ?>
            <a class="tarjeta-curso <?= $c['status'] === 'archived' ? 'archivado' : '' ?>"
               href="<?= e(url('escuela/curso.php?id=' . (int) $c['id'])) ?>">

                <h2><?= e($c['name']) ?></h2>

                <p class="curso-meta">
                    <?php if ($c['grade']): ?><?= e($c['grade']) ?> · <?php endif; ?>
                    <?= (int) $c['year'] ?>
                    <?php if ($c['status'] === 'archived'): ?>
                        · <span class="etiqueta-archivado">Archivado</span>
                    <?php endif; ?>
                </p>

                <div class="curso-cifras">
                    <span><strong><?= (int) $c['estudiantes'] ?></strong> estudiantes</span>
                    <span><strong><?= (int) $c['actividades'] ?></strong> actividades</span>
                </div>
            </a>
        <?php endforeach; ?>
    </div>

<?php endif; ?>

<section class="bloque-panel">
    <h2>Crear un curso</h2>

    <form method="post" class="form-linea">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="crear">

        <label>
            Nombre
            <input type="text" name="nombre" required maxlength="160"
                   placeholder="Matemáticas 3.º B">
        </label>

        <label>
            Grado
            <input type="text" name="grado" maxlength="40" placeholder="Tercero">
        </label>

        <label>
            Año
            <input type="number" name="anio" min="2020" max="2100"
                   value="<?= (int) date('Y') ?>">
        </label>

        <button class="btn btn-principal" type="submit">Crear curso</button>
    </form>
</section>

<?php
/*
 * Las guías, al final y no arriba.
 *
 * Quien ya sabe usar el panel no tropieza con ellas cada vez que entra; y
 * quien no sabe llega aquí justo después de mirar la pantalla sin
 * entenderla, que es cuando de verdad las busca.
 */
$misGuias = function_exists('guiasParaMi') ? guiasParaMi() : [];
?>

<?php if ($misGuias): ?>
    <section class="bloque-panel">
        <h2>¿Cómo se hace?</h2>
        <p style="color:var(--texto-tenue);font-size:.9rem;margin:-6px 0 16px">
            Guías de un minuto, con el ratón moviéndose por la pantalla.
        </p>

        <div class="guias-mazo">
            <?php foreach ($misGuias as $g): ?>
                <a class="guia-tarjeta"
                   href="<?= e(url('usuario/guia.php?g=' . urlencode($g['slug']))) ?>">
                    <span class="ico" aria-hidden="true"><?= e($g['icono']) ?></span>
                    <b><?= e($g['titulo']) ?></b>
                    <small><?= e($g['resumen']) ?></small>
                    <span class="duracion">▶ <?= e($g['duracion']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
