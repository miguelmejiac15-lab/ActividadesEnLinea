<?php
/**
 * escuela/curso.php — Resumen de un curso
 *
 * Lo primero que ve el docente al entrar: cuántos son, qué tienen asignado,
 * quién se movió esta semana y quién lleva más tiempo sin aparecer.
 *
 * El orden no es decorativo. Arriba van las cifras del grupo, y debajo —lo
 * más importante— **quién lleva más de una semana sin entrar**. Un panel
 * que solo muestra medias esconde justo al niño por el que uno pregunta.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('id');
$curso   = exigirCursoPropio($cursoId);

// ── Archivar o reactivar ─────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('accion') === 'estado') {
    exigirCsrf();

    $nuevo = $curso['status'] === 'active' ? 'archived' : 'active';
    ejecutar('UPDATE courses SET status = ? WHERE id = ?', [$nuevo, $cursoId]);

    mensaje('ok', $nuevo === 'archived'
        ? 'Curso archivado. Sigue estando aquí, solo deja de aparecer como activo.'
        : 'Curso reactivado.');
    redirigir('escuela/curso.php?id=' . $cursoId);
}

$resumen     = resumenDelCurso($cursoId);
$estudiantes = estudiantesDelCurso($cursoId);
$actividades = actividadesDelCurso($cursoId);

/*
 * Quién lleva una semana sin aparecer. Se calcula en PHP y no en SQL
 * porque los datos ya están cargados: pedirlos otra vez sería una consulta
 * más para la misma respuesta.
 */
$hace7dias = strtotime('-7 days');
$ausentes  = array_filter($estudiantes, static function (array $e) use ($hace7dias): bool {
    return $e['ultima_vez'] === null || strtotime($e['ultima_vez']) < $hace7dias;
});

$titulo      = $curso['name'];
$escuelaZona = 'curso';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1><?= e($curso['name']) ?></h1>
        <p class="bajada">
            <?php if ($curso['grade']): ?><?= e($curso['grade']) ?> · <?php endif; ?>
            <?= (int) $curso['year'] ?>
            <?php if ($curso['status'] === 'archived'): ?>
                · <span class="etiqueta-archivado">Archivado</span>
            <?php endif; ?>
        </p>
    </div>

    <div class="acciones-cabeza">
        <?php
        /*
         * Los dos destinos que se usan a diario, arriba y siempre. Antes
         * solo estaban dentro del bloque «Para empezar», que desaparece en
         * cuanto el curso tiene contenido — justo cuando más falta hacen.
         */
        ?>
        <a class="btn btn-principal btn-chico"
           href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">
            + Estudiantes
        </a>
        <a class="btn btn-principal btn-chico"
           href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">
            + Actividades
        </a>
        <?php if ($estudiantes && $actividades): ?>
            <a class="btn btn-secundario btn-chico"
               href="<?= e(url('escuela/progreso.php?curso=' . $cursoId)) ?>">
                📈 Progreso
            </a>
        <?php endif; ?>

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="estado">
            <button class="btn btn-secundario btn-chico" type="submit">
                <?= $curso['status'] === 'active' ? 'Archivar' : 'Reactivar' ?>
            </button>
        </form>
    </div>
</header>

<div class="tira-cifras">
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['estudiantes'] ?></span>
        <span class="cifra-eti">Estudiantes</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['actividades'] ?></span>
        <span class="cifra-eti">Actividades asignadas</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['activos_semana'] ?></span>
        <span class="cifra-eti">Jugaron esta semana</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['estaciones_hechas'] ?></span>
        <span class="cifra-eti">Estaciones terminadas</span>
    </div>
</div>

<?php
/*
 * Cómo entran los niños. Va en el Resumen porque es lo primero que se
 * mira cada mañana y lo que hay que copiar al tablero.
 */
?>
<?php if (aulaInstalada()): ?>
    <?php
    $codigoCurso = $curso['access_code'] ?? null;
    $abiertaHoy  = claseAbierta($curso);
    ?>

    <?php if (usaLista($curso) && $codigoCurso): ?>
        <section class="bloque-panel bloque-aula">
            <h2>Así entran tus estudiantes</h2>
            <p class="nota-panel">
                Escribe esta dirección en el tablero. Ellos la abren, ven la lista de la
                clase y tocan su nombre.
                <?php if ((int) ($curso['aula_pin'] ?? 0) === 1): ?>
                    Después escriben su PIN de 4 números.
                <?php endif; ?>
            </p>

            <div class="codigo-caja">
                <div>
                    <span class="rotulo-codigo">Dirección para el tablero</span>
                    <span class="url-aula"><?= e(urlDeAula((string) $codigoCurso)) ?></span>
                </div>
                <div>
                    <span class="rotulo-codigo">Código</span>
                    <span class="codigo-grande"><?= e($codigoCurso) ?></span>
                </div>
            </div>

            <div class="estado-clase <?= $abiertaHoy ? 'abierta' : 'cerrada' ?>">
                <span class="punto" aria-hidden="true"></span>
                <div>
                    <b><?= $abiertaHoy ? 'La clase está ABIERTA' : 'La clase está CERRADA' ?></b>
                    <span>
                        <?= $abiertaHoy
                            ? 'Pueden entrar ahora · ' . e(tiempoDeClase($curso))
                            : 'La dirección no abre nada hasta que la abras.' ?>
                    </span>
                </div>
            </div>

            <div class="pie-bloque">
                <a class="btn btn-principal btn-chico"
                   href="<?= e(url('escuela/acceso.php?curso=' . $cursoId)) ?>">
                    <?= $abiertaHoy ? 'Cerrar o cambiar' : 'Abrir la clase' ?>
                </a>
                <a class="btn btn-secundario btn-chico"
                   href="<?= e(urlDeAula((string) $codigoCurso)) ?>"
                   target="_blank" rel="noopener">Ver lo que ven ellos ↗</a>
                <a class="btn btn-secundario btn-chico"
                   href="<?= e(url('escuela/tarjetas.php?curso=' . $cursoId)) ?>">
                    🖨️ Hoja para repartir
                </a>
            </div>
        </section>

    <?php elseif ($estudiantes): ?>
        <section class="bloque-panel">
            <h2>¿Tus estudiantes son pequeños?</h2>
            <p class="nota-panel">
                Puedes darles una dirección corta para que entren <b>tocando su nombre</b>,
                sin teclear correo ni contraseña. Es lo que hace falta cuando son treinta
                niños de primero.
            </p>
            <a class="btn btn-principal btn-chico"
               href="<?= e(url('escuela/acceso.php?curso=' . $cursoId)) ?>">
                Elegir cómo entran →
            </a>
        </section>
    <?php endif; ?>
<?php endif; ?>


<?php if (!$estudiantes || !$actividades): ?>

    <section class="bloque-panel">
        <h2>Para empezar</h2>
        <ol class="pasos-escuela">
            <li class="<?= $estudiantes ? 'hecho' : '' ?>">
                <a href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">
                    Matricula a tus estudiantes
                </a>
                <?= $estudiantes ? '✅' : '' ?>
            </li>
            <li class="<?= $actividades ? 'hecho' : '' ?>">
                <a href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">
                    Asígnales actividades del catálogo
                </a>
                <?= $actividades ? '✅' : '' ?>
            </li>
            <li>Vuelve aquí para ver cómo va cada uno</li>
        </ol>
    </section>

<?php endif; ?>

<?php if ($ausentes): ?>
    <section class="bloque-panel aviso-atencion">
        <h2>Llevan una semana o más sin entrar</h2>
        <p class="nota-panel">
            No es una alarma: puede ser una semana de vacaciones o de exámenes.
            Está arriba porque es lo que un promedio no deja ver.
        </p>
        <ul class="lista-ausentes">
            <?php foreach ($ausentes as $a): ?>
                <li>
                    <a href="<?= e(url('escuela/estudiante.php?curso=' . $cursoId . '&id=' . (int) $a['id'])) ?>">
                        <?= e($a['avatar'] ? '' : '🧒') ?> <?= e($a['name']) ?>
                    </a>
                    <span class="tenue">
                        <?= $a['ultima_vez']
                            ? 'última vez el ' . e(fechaLarga($a['ultima_vez']))
                            : 'todavía no ha jugado nada' ?>
                    </span>
                </li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<?php if ($estudiantes): ?>
<section class="bloque-panel">
    <h2>Estudiantes</h2>

    <table class="tabla-panel">
        <thead>
            <tr>
                <th>Estudiante</th>
                <th class="num">Estaciones</th>
                <th class="num">Estrellas</th>
                <th>Última vez</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($estudiantes as $al): ?>
            <tr>
                <td><strong><?= e($al['name']) ?></strong></td>
                <td class="num"><?= (int) $al['estaciones_hechas'] ?></td>
                <td class="num">⭐ <?= (int) $al['estrellas'] ?></td>
                <td class="<?= $al['ultima_vez'] === null ? 'tenue' : '' ?>">
                    <?= $al['ultima_vez'] ? e(fechaLarga($al['ultima_vez'])) : 'Sin actividad' ?>
                </td>
                <td>
                    <a class="btn btn-secundario btn-chico"
                       href="<?= e(url('escuela/estudiante.php?curso=' . $cursoId . '&id=' . (int) $al['id'])) ?>">
                        Ver progreso
                    </a>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>

<?php if ($actividades): ?>
<section class="bloque-panel">
    <h2>Actividades asignadas</h2>

    <table class="tabla-panel">
        <thead>
            <tr>
                <th>Actividad</th>
                <th>Materia</th>
                <th class="num">La terminaron</th>
                <th>Para cuándo</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($actividades as $ac): ?>
            <tr>
                <td>
                    <?= e($ac['icon'] ?? '') ?>
                    <a href="<?= e(url('actividades/ver.php?a=' . urlencode($ac['slug']))) ?>">
                        <?= e($ac['title']) ?>
                    </a>
                </td>
                <td class="tenue"><?= e(($ac['categoria_icon'] ?? '') . ' ' . ($ac['categoria'] ?? '')) ?></td>
                <td class="num">
                    <?= (int) $ac['empezaron'] ?> de <?= count($estudiantes) ?>
                </td>
                <td class="<?= $ac['due_date'] ? '' : 'tenue' ?>">
                    <?= $ac['due_date'] ? e(fechaLarga($ac['due_date'])) : 'Sin fecha' ?>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
</section>
<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
