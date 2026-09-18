<?php
/**
 * escuela/estudiante.php — Cómo va un estudiante, estación a estación
 *
 * La pantalla que se abre cuando el docente pregunta por un niño en
 * concreto. Muestra cada actividad asignada con sus estaciones y en qué
 * estado está cada una.
 *
 * Dos comprobaciones antes de enseñar nada: que el curso sea de quien
 * pregunta (`exigirCursoPropio`) y que el estudiante esté en ese curso
 * (`exigirEstudianteDelCurso`). Sin la segunda, cambiar el número de `?id=`
 * mostraría el progreso de cualquier niño de la plataforma.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE ESTA PANTALLA NO HACE
 * ─────────────────────────────────────────────────────────────────────
 *
 * No hay ranking ni comparación con el resto del grupo. Con niños de
 * primaria, un puesto en una lista no informa al docente de nada que no
 * vea ya, y sí convierte el panel en algo que nadie quiere que su hijo
 * tenga encima. Los números son del niño consigo mismo: qué hizo, cuánto
 * le costó y cuándo fue la última vez.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

$alumnoId = getEntero('id');
$alumno   = exigirEstudianteDelCurso($cursoId, $alumnoId);

/*
 * `detalleDeEstudiante()` devuelve una fila por estación. Se agrupa aquí
 * en memoria en vez de con una consulta por actividad: los datos ya
 * vinieron todos, y volver a preguntar por cada actividad sería una
 * consulta más para responder lo mismo.
 */
$filas  = detalleDeEstudiante($cursoId, $alumnoId);
$porAct = [];

foreach ($filas as $f) {
    $id = (int) $f['actividad_id'];

    if (!isset($porAct[$id])) {
        $porAct[$id] = [
            'titulo'     => $f['title'],
            'slug'       => $f['slug'],
            'icono'      => $f['icon'],
            'total'      => (int) $f['total_estaciones'],
            'hechas'     => 0,
            'estrellas'  => 0,
            'segundos'   => 0,
            'ultima'     => null,
            'estaciones' => [],
        ];
    }

    // Una actividad sin estaciones da una fila con `estacion_id` en NULL
    // por el LEFT JOIN. Es una actividad publicada y vacía: se muestra
    // como tal, no se cuenta como progreso.
    if ($f['estacion_id'] === null) {
        continue;
    }

    $completada = $f['status'] === 'completed';

    $porAct[$id]['estaciones'][] = [
        'nombre'    => $f['estacion'],
        'posicion'  => (int) $f['position'],
        'estado'    => $f['status'],
        'estrellas' => (int) $f['stars'],
        'intentos'  => (int) $f['attempts'],
        'segundos'  => (int) $f['time_spent_seconds'],
        'cuando'    => $f['completed_at'] ?: $f['updated_at'],
    ];

    if ($completada) {
        $porAct[$id]['hechas']++;
    }

    $porAct[$id]['estrellas'] += (int) $f['stars'];
    $porAct[$id]['segundos']  += (int) $f['time_spent_seconds'];

    if ($f['updated_at'] !== null
        && ($porAct[$id]['ultima'] === null || $f['updated_at'] > $porAct[$id]['ultima'])) {
        $porAct[$id]['ultima'] = $f['updated_at'];
    }
}

// ── Totales del estudiante ───────────────────────────────────────────
$totHechas    = 0;
$totEstrellas = 0;
$totSegundos  = 0;
$totActividadesCompletas = 0;
$ultimaVez    = null;

foreach ($porAct as $a) {
    $totHechas    += $a['hechas'];
    $totEstrellas += $a['estrellas'];
    $totSegundos  += $a['segundos'];

    if ($a['total'] > 0 && $a['hechas'] >= $a['total']) {
        $totActividadesCompletas++;
    }
    if ($a['ultima'] !== null && ($ultimaVez === null || $a['ultima'] > $ultimaVez)) {
        $ultimaVez = $a['ultima'];
    }
}

/** Minutos redondeados hacia arriba, para no mostrar «0 min» de algo que sí se jugó. */
function minutos(int $segundos): string
{
    if ($segundos <= 0)  return '—';
    if ($segundos < 60)  return 'menos de 1 min';
    return (int) ceil($segundos / 60) . ' min';
}

$titulo      = $alumno['name'] . ' · ' . $curso['name'];
$escuelaZona = 'estudiante';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1><?= e($alumno['name']) ?></h1>
        <p class="bajada">
            <?= e($curso['name']) ?>
            <?php if ($ultimaVez): ?>
                · última vez el <?= e(fechaLarga($ultimaVez)) ?>
            <?php else: ?>
                · todavía no ha jugado nada
            <?php endif; ?>
        </p>
    </div>

    <a class="btn btn-secundario btn-chico"
       href="<?= e(url('escuela/progreso.php?curso=' . $cursoId)) ?>">
        Ver todo el curso
    </a>
</header>

<div class="tira-cifras">
    <div class="cifra">
        <span class="cifra-num"><?= $totActividadesCompletas ?>/<?= count($porAct) ?></span>
        <span class="cifra-eti">Actividades terminadas</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= $totHechas ?></span>
        <span class="cifra-eti">Estaciones terminadas</span>
    </div>
    <div class="cifra">
        <span class="cifra-num">⭐ <?= $totEstrellas ?></span>
        <span class="cifra-eti">Estrellas</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= e(minutos($totSegundos)) ?></span>
        <span class="cifra-eti">Tiempo jugado</span>
    </div>
</div>

<?php if (!$porAct): ?>

    <section class="bloque-panel">
        <h2>Sin actividades asignadas</h2>
        <p class="nota-panel">
            Este curso todavía no tiene actividades.
            <a href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">Asígnale algunas</a>
            y aquí aparecerá cómo le va.
        </p>
    </section>

<?php else: ?>

    <?php foreach ($porAct as $actId => $a): ?>
        <?php
        $sinEstaciones = $a['total'] === 0;
        $completa      = !$sinEstaciones && $a['hechas'] >= $a['total'];
        $pct           = $sinEstaciones ? 0 : (int) round($a['hechas'] / $a['total'] * 100);
        ?>

        <section class="bloque-panel actividad-alumno <?= $completa ? 'completa' : '' ?>">

            <div class="cabeza-actividad">
                <h2>
                    <span aria-hidden="true"><?= e($a['icono'] ?? '🎯') ?></span>
                    <a href="<?= e(url('actividades/ver.php?a=' . urlencode($a['slug']))) ?>">
                        <?= e($a['titulo']) ?>
                    </a>
                </h2>

                <span class="marca-avance <?= $completa ? 'lista' : '' ?>">
                    <?= $a['hechas'] ?> de <?= $a['total'] ?> estaciones
                    <?= $completa ? '✅' : '' ?>
                </span>
            </div>

            <?php if ($sinEstaciones): ?>
                <p class="nota-panel">
                    Esta actividad no tiene estaciones todavía, así que no hay nada que jugar.
                </p>
            <?php else: ?>

                <div class="barra-avance"
                     role="img"
                     aria-label="<?= $pct ?> por ciento completado">
                    <span style="width: <?= $pct ?>%"></span>
                </div>

                <table class="tabla-panel tabla-estaciones">
                    <thead>
                        <tr>
                            <th class="num">#</th>
                            <th>Estación</th>
                            <th>Estado</th>
                            <th class="num">Estrellas</th>
                            <th class="num">Intentos</th>
                            <th class="num">Tiempo</th>
                            <th>Cuándo</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($a['estaciones'] as $es): ?>
                        <tr class="<?= $es['estado'] === null ? 'fila-tenue' : '' ?>">
                            <td class="num"><?= $es['posicion'] ?></td>
                            <td><?= e($es['nombre']) ?></td>
                            <td>
                                <?php if ($es['estado'] === 'completed'): ?>
                                    <span class="pastilla lista">Terminada</span>
                                <?php elseif ($es['estado'] === 'in_progress'): ?>
                                    <span class="pastilla curso">Empezada</span>
                                <?php else: ?>
                                    <span class="pastilla nada">Sin empezar</span>
                                <?php endif; ?>
                            </td>
                            <td class="num"><?= $es['estrellas'] > 0 ? '⭐ ' . $es['estrellas'] : '—' ?></td>
                            <td class="num"><?= $es['intentos'] > 0 ? $es['intentos'] : '—' ?></td>
                            <td class="num"><?= e(minutos($es['segundos'])) ?></td>
                            <td class="tenue">
                                <?= $es['cuando'] ? e(fechaLarga($es['cuando'])) : '—' ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>

            <?php endif; ?>
        </section>

    <?php endforeach; ?>

<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
