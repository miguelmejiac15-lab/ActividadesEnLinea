<?php
/**
 * escuela/progreso.php — La rejilla del curso
 *
 * Una fila por estudiante, una columna por actividad asignada, y en cada
 * celda cuántas estaciones lleva. Es la pantalla que un docente mira de
 * verdad: de un vistazo se ve quién va, quién no empezó y qué actividad se
 * le atragantó al grupo entero.
 *
 * La rejilla se arma con DOS consultas, no con una por celda: con treinta
 * estudiantes y diez actividades, una por celda serían trescientas
 * consultas para pintar una tabla.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

/*
 * Quitar una actividad DESDE la rejilla.
 *
 * Es donde se ve que algo no funciona —una columna entera en cero, o una
 * que ya terminó todo el mundo— así que es donde tiene que estar el
 * botón. Mandar al docente a otra pantalla a buscar lo que acaba de ver
 * aquí es hacerle repetir el trabajo.
 */
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    if (post('accion') === 'quitar') {
        quitarAsignacion($cursoId, (int) post('actividad'));
        mensaje('ok', 'Actividad retirada del curso. El progreso ya hecho se conserva.');
    }

    redirigir('escuela/progreso.php?curso=' . $cursoId);
}

$rejilla     = rejillaDeProgreso($cursoId);
$estudiantes = $rejilla['estudiantes'];
$actividades = $rejilla['actividades'];
$celdas      = $rejilla['celdas'];

/**
 * Color de la celda según lo avanzado.
 *
 * Se acompaña SIEMPRE del número y de un `title` en texto: quien no
 * distingue verde de rojo tiene que poder leer la tabla igual. El color
 * es un atajo para el resto, nunca la única información.
 */
function claseCelda(int $hechas, int $total): string
{
    if ($total <= 0 || $hechas <= 0) return 'nada';
    if ($hechas >= $total)           return 'completa';
    if ($hechas >= $total / 2)       return 'media';
    return 'poca';
}

$titulo      = 'Progreso · ' . $curso['name'];
$escuelaZona = 'progreso';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1>Progreso del curso</h1>
        <p class="bajada">
            <?= e($curso['name']) ?> ·
            <?= count($estudiantes) ?> estudiantes · <?= count($actividades) ?> actividades
        </p>
    </div>
    <div class="acciones-cabeza">
        <a class="btn btn-principal btn-chico"
           href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">
            + Añadir actividades
        </a>
        <a class="btn btn-secundario btn-chico"
           href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">
            + Estudiantes
        </a>
    </div>
</header>

<?php if (!$estudiantes || !$actividades): ?>

    <section class="bloque-panel">
        <h2>Todavía no hay nada que mostrar</h2>
        <p class="nota-panel">
            La rejilla necesita las dos cosas:
            <?php if (!$estudiantes): ?>
                <a href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">matricular estudiantes</a>
            <?php endif; ?>
            <?php if (!$estudiantes && !$actividades): ?> y <?php endif; ?>
            <?php if (!$actividades): ?>
                <a href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">asignar actividades</a>
            <?php endif; ?>.
        </p>
    </section>

<?php else: ?>

    <section class="bloque-panel">
        <p class="nota-panel">
            Cada celda muestra cuántas estaciones lleva ese estudiante <b>de trabajo para
            este curso</b>. El color acompaña al número, no lo sustituye.
            <br>
            La casita 🏠 marca lo que además ya había hecho <b>por su cuenta</b>, antes o
            fuera de clase. No cuenta como trabajo del curso —son cosas distintas— pero
            conviene verlo: ese niño ya se sabe la actividad.
        </p>

        <div class="leyenda-progreso">
            <span><i class="pt completa"></i> Terminada</span>
            <span><i class="pt media"></i> Más de la mitad</span>
            <span><i class="pt poca"></i> Empezada</span>
            <span><i class="pt nada"></i> Sin empezar</span>
            <span>🏠 También lo hizo en casa</span>
        </div>

        <div class="envoltorio-rejilla">
            <table class="rejilla-progreso">
                <thead>
                    <tr>
                        <th class="esquina">Estudiante</th>
                        <?php foreach ($actividades as $a): ?>
                            <th class="col-actividad" title="<?= e($a['title']) ?>">
                                <span class="col-icono" aria-hidden="true"><?= e($a['icon'] ?? '🎯') ?></span>
                                <span class="col-nombre"><?= e($a['title']) ?></span>

                                <?php /* Quitar la columna desde aquí, que es donde se ve que sobra. */ ?>
                                <form method="post" class="quitar-col"
                                      onsubmit="return confirm('¿Quitar «<?= e($a['title']) ?>» del curso?\n\nEl progreso ya hecho se conserva.')">
                                    <?= campoCsrf() ?>
                                    <input type="hidden" name="accion" value="quitar">
                                    <input type="hidden" name="actividad" value="<?= (int) $a['id'] ?>">
                                    <button type="submit" title="Quitar del curso">✕</button>
                                </form>
                            </th>
                        <?php endforeach; ?>
                        <th class="num">Total</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($estudiantes as $al): ?>
                    <tr>
                        <th class="celda-nombre">
                            <a href="<?= e(url('escuela/estudiante.php?curso=' . $cursoId . '&id=' . (int) $al['id'])) ?>">
                                <?= e($al['name']) ?>
                            </a>
                        </th>

                        <?php $suma = 0; ?>
                        <?php foreach ($actividades as $a): ?>
                            <?php
                            $c       = $celdas[(int) $al['id']][(int) $a['id']] ?? null;
                            $hechas  = $c['hechas'] ?? 0;
                            $deCasa  = $c['de_casa'] ?? 0;
                            $total   = (int) $a['estaciones'];
                            $suma   += $hechas;

                            /*
                             * La casita marca lo que el niño ya había hecho
                             * por su cuenta. No suma al trabajo de clase
                             * —son cosas distintas y por eso van separadas—
                             * pero sin ella el docente vería un cero y
                             * mandaría a repetir a quien ya se lo sabe.
                             */
                            $titulo = $al['name'] . ' · ' . $a['title'] . ': '
                                    . $hechas . ' de ' . $total . ' estaciones en clase';

                            if ($deCasa > 0) {
                                $titulo .= ' · ' . $deCasa . ' ya las había hecho en casa';
                            }
                            ?>
                            <td class="celda <?= claseCelda($hechas, $total) ?>"
                                title="<?= e($titulo) ?>">
                                <?= $hechas ?>/<?= $total ?><?php
                                if ($deCasa > 0): ?><span class="marca-casa"
                                    aria-label="también lo hizo en casa">🏠</span><?php
                                endif; ?>
                            </td>
                        <?php endforeach; ?>

                        <td class="num"><strong><?= $suma ?></strong></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
                <tfoot>
                    <tr>
                        <th class="celda-nombre">La terminaron</th>
                        <?php foreach ($actividades as $a): ?>
                            <td class="num tenue">
                                <?= (int) $a['empezaron'] ?>/<?= count($estudiantes) ?>
                            </td>
                        <?php endforeach; ?>
                        <td></td>
                    </tr>
                </tfoot>
            </table>
        </div>
    </section>

<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
