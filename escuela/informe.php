<?php
/**
 * escuela/informe.php — Cómo le está yendo a cada uno
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA DIFERENCIA CON «PROGRESO»
 * ─────────────────────────────────────────────────────────────────────
 *
 * `progreso.php` responde **cuánto** lleva cada niño. Esta pantalla
 * responde **cómo le fue**, que no es lo mismo y a veces es lo
 * contrario: dos niños con las diez estaciones terminadas pueden ser uno
 * que acertó a la primera y otro que repitió hasta que salió. En la
 * rejilla de avance se ven idénticos.
 *
 * Por eso la columna que manda aquí es la precisión, y por eso la tabla
 * viene ordenada por señal: arriba lo que hay que mirar hoy.
 *
 * No lleva ninguna nota. Un porcentaje de aciertos en un juego no es una
 * calificación, y presentarlo como tal invitaría a ponerlo en el boletín.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

$alumnos     = informeDeCurso($cursoId);
$actividades = informePorActividad($cursoId);

// Para ordenar por lo que necesita atención sin perder el orden
// alfabético dentro de cada grupo.
$peso = [
    'avanza_con_dificultad' => 0,
    'le_cuesta'             => 1,
    'en_marcha'             => 2,
    'va_muy_bien'           => 3,
    'sin_empezar'           => 4,
];

usort($alumnos, static function (array $a, array $b) use ($peso): int {
    $d = ($peso[$a['senal']] ?? 9) <=> ($peso[$b['senal']] ?? 9);
    return $d !== 0 ? $d : strcmp((string) $a['name'], (string) $b['name']);
});

$requierenAtencion = count(array_filter(
    $alumnos,
    static fn(array $a): bool => in_array($a['senal'], ['avanza_con_dificultad', 'le_cuesta'], true)
));

$titulo      = 'Desempeño · ' . $curso['name'];
$escuelaZona = 'informe';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1>Cómo les está yendo</h1>
        <p class="bajada">
            <?= e($curso['name']) ?> · <?= count($alumnos) ?> estudiantes ·
            <?= count($actividades) ?> actividades asignadas
        </p>
    </div>
    <a class="btn btn-secundario btn-chico"
       href="<?= e(url('escuela/progreso.php?curso=' . $cursoId)) ?>">
        Ver avance →
    </a>
</header>

<?php if (!$alumnos): ?>

    <section class="bloque-panel">
        <h2>Todavía no hay estudiantes</h2>
        <p class="nota-panel">Cuando matricules a tu curso, aquí verás cómo le va a cada uno.</p>
        <a class="btn btn-principal btn-chico"
           href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">Añadir estudiantes →</a>
    </section>

<?php else: ?>

    <?php if ($requierenAtencion > 0): ?>
        <div class="aviso info" style="margin-bottom:18px">
            <b><?= $requierenAtencion ?>
                <?= $requierenAtencion === 1 ? 'estudiante necesita' : 'estudiantes necesitan' ?>
                una mirada.</b>
            Están arriba en la tabla. No es que no trabajen: es que están acertando
            menos del 70%, y eso no se nota en el avance.
        </div>
    <?php endif; ?>

    <!-- ── Por estudiante ───────────────────────────────────────────── -->
    <section class="bloque-panel">
        <h2>Por estudiante</h2>
        <p class="nota-panel">
            <b>Precisión</b> es qué porcentaje acertó en lo que terminó.
            <b>Intentos</b> es cuántas veces necesitó cada estación: repetir no es malo,
            pero repetir mucho para llegar al mismo sitio dice algo.
        </p>

        <div class="tabla-envoltura">
            <table class="tabla-panel tabla-informe">
                <thead>
                    <tr>
                        <th>Estudiante</th>
                        <th></th>
                        <th class="num">Avance</th>
                        <th class="num">Precisión</th>
                        <th class="num">Intentos</th>
                        <th class="num">Tiempo</th>
                        <th class="num">⭐</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($alumnos as $a): ?>
                    <?php $s = etiquetaDeSenal($a['senal']); ?>
                    <tr class="fila-<?= e($s['clase']) ?>">
                        <td><b><?= e($a['name']) ?></b></td>
                        <td class="senal">
                            <span aria-hidden="true"><?= $s['icono'] ?></span>
                            <span class="<?= e($s['clase']) ?>"><?= e($s['texto']) ?></span>
                        </td>
                        <td class="num">
                            <?= (int) $a['avance'] ?>%
                            <small class="tenue"><?= (int) $a['hechas'] ?>/<?= (int) $a['total'] ?></small>
                        </td>
                        <td class="num">
                            <?php if ($a['precision_media'] === null): ?>
                                <span class="tenue">—</span>
                            <?php else: ?>
                                <b class="<?= $a['precision_media'] < 70 ? 'alerta' : '' ?>">
                                    <?= (int) $a['precision_media'] ?>%
                                </b>
                            <?php endif; ?>
                        </td>
                        <td class="num">
                            <?= $a['intentos_media'] === null
                                ? '<span class="tenue">—</span>'
                                : e(number_format((float) $a['intentos_media'], 1)) ?>
                        </td>
                        <td class="num tenue"><?= e(duracionLegible((int) $a['segundos'])) ?></td>
                        <td class="num"><?= (int) $a['estrellas'] ?></td>
                        <td class="num">
                            <a class="btn-mini"
                               href="<?= e(url('escuela/estudiante.php?curso=' . $cursoId . '&id=' . (int) $a['id'])) ?>">
                                Ver
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </section>

    <!-- ── Por actividad ────────────────────────────────────────────── -->
    <?php if ($actividades): ?>
        <section class="bloque-panel">
            <h2>Por actividad</h2>
            <p class="nota-panel">
                De la que peor va a la que mejor. Si media clase tiene precisión baja en
                la misma actividad, el problema no está en los niños: está en que hace
                falta volver a explicar ese tema, o en que esa actividad no era para
                este momento del año.
            </p>

            <div class="tabla-envoltura">
                <table class="tabla-panel tabla-informe">
                    <thead>
                        <tr>
                            <th>Actividad</th>
                            <th class="num">La trabajaron</th>
                            <th class="num">Estaciones hechas</th>
                            <th class="num">Precisión</th>
                            <th class="num">Intentos</th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($actividades as $a): ?>
                        <?php $p = $a['precision_media'] === null ? null : (int) $a['precision_media']; ?>
                        <tr class="<?= $p !== null && $p < 70 ? 'fila-alerta' : '' ?>">
                            <td><?= e(($a['icon'] ?? '') . ' ' . $a['title']) ?></td>
                            <td class="num"><?= (int) $a['estudiantes'] ?></td>
                            <td class="num tenue">
                                <?= (int) $a['hechas'] ?>
                                <small>de <?= (int) $a['estaciones'] * max(1, count($alumnos)) ?></small>
                            </td>
                            <td class="num">
                                <?php if ($p === null): ?>
                                    <span class="tenue">nadie la ha tocado</span>
                                <?php else: ?>
                                    <b class="<?= $p < 70 ? 'alerta' : '' ?>"><?= $p ?>%</b>
                                <?php endif; ?>
                            </td>
                            <td class="num">
                                <?= $a['intentos_media'] === null
                                    ? '<span class="tenue">—</span>'
                                    : e(number_format((float) $a['intentos_media'], 1)) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </section>
    <?php endif; ?>

    <p class="nota-panel">
        <b>Esto no es una nota.</b> Un porcentaje de aciertos en un juego sirve para
        decidir a quién acompañar esta semana, no para poner en un boletín. El niño no
        ve ninguno de estos números.
    </p>

<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
