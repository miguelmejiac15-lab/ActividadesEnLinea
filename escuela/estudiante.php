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

// ── La familia: su correo y el resumen ───────────────────────────────
//
// Las dos comprobaciones de arriba ya cerraron el curso y el niño: aquí
// solo se llega con un curso propio y un estudiante matriculado en él.
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $volver = 'escuela/estudiante.php?curso=' . $cursoId . '&id=' . $alumnoId . '#familia';

    if (post('accion') === 'acudiente') {
        $r = guardarCorreoAcudiente($alumnoId, (string) post('correo_acudiente'));

        mensaje($r['ok'] ? 'ok' : 'error', $r['ok']
            ? (trim((string) post('correo_acudiente')) === ''
                ? 'Se borró el correo del acudiente.'
                : 'Correo del acudiente guardado.')
            : $r['error']);
    }

    if (post('accion') === 'resumen') {
        $r = enviarResumenFamilia($curso, $alumnoId);

        mensaje($r['ok'] ? 'ok' : ($r['motivo'] === 'reciente' ? 'info' : 'error'),
            $r['ok'] ? 'Resumen enviado a la familia.' : (string) $r['error']);
    }

    redirigir($volver);
}

$acudiente = (string) traerValor('SELECT guardian_email FROM users WHERE id = ?', [$alumnoId]);
$esCuentaDeFamilia = traerValor('SELECT role FROM users WHERE id = ?', [$alumnoId]) !== 'student';

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

<?php if (casaInstalada()): ?>
    <?php
    $tareasCasa = tareasDeCasa($alumnoId, $cursoId);
    $ultimo     = ultimoResumen($alumnoId, $cursoId);
    ?>
    <section class="bloque-panel" id="familia">
        <h2>🏠 Tareas para casa y familia</h2>

        <?php if ($tareasCasa): ?>
            <p class="nota-panel">
                <?php $faltan = count(array_filter($tareasCasa, static fn($t) => !$t['completa'])); ?>
                Tiene <?= count($tareasCasa) ?> tarea(s) para casa:
                <?= $faltan === 0 ? '<b>todas hechas ✅</b>' : '<b>le faltan ' . $faltan . '</b>' ?>.
            </p>
        <?php else: ?>
            <p class="nota-panel">
                Este curso no tiene tareas para casa. Se marcan en
                <a href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">Actividades</a>.
            </p>
        <?php endif; ?>

        <?php if ($esCuentaDeFamilia): ?>
            <p class="nota-panel">
                Esta es una cuenta de familia: la gestionan sus padres, así que el correo del
                acudiente no se cambia desde aquí.
            </p>
        <?php else: ?>
            <form method="post" class="form-linea">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="acudiente">
                <label>
                    Correo del acudiente
                    <input type="email" name="correo_acudiente" maxlength="190"
                           value="<?= e($acudiente) ?>" placeholder="familia@correo.com">
                </label>
                <button class="btn btn-secundario btn-chico" type="submit">Guardar</button>
            </form>
            <p class="nota-panel">
                Escribe solo un correo que la familia te haya dado para esto. Es un dato
                personal de un menor: se usa únicamente para enviarle este resumen.
            </p>
        <?php endif; ?>

        <?php if ($acudiente !== '' && !$esCuentaDeFamilia): ?>
            <form method="post" class="form-linea">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="resumen">
                <button class="btn btn-principal btn-chico" type="submit">
                    ✉️ Enviar resumen a la familia
                </button>
                <span class="tenue">
                    <?= $ultimo ? 'Último envío: ' . e(fechaLarga($ultimo)) : 'Todavía no se le ha enviado ninguno.' ?>
                </span>
            </form>
            <p class="nota-panel">
                Lleva las tareas para casa pendientes y hechas, lo que jugó esta semana y cómo
                entrar desde casa con su usuario. La contraseña nunca va en el correo.
            </p>
        <?php endif; ?>
    </section>
<?php endif; ?>

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
