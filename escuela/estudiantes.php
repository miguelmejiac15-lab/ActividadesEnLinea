<?php
/**
 * escuela/estudiantes.php — Matricular estudiantes
 *
 * Tres caminos, en el orden en que se usan de verdad:
 *
 *   1. **Crear las cuentas** pegando la lista de nombres. Es lo que hace
 *      un docente el primer día con treinta niños que no tienen cuenta.
 *   2. **Mis estudiantes**: los que ya tiene en otros cursos suyos, con
 *      un clic. El mismo grupo pasa de Matemáticas a Lengua y volver a
 *      teclear treinta correos no tiene ningún sentido.
 *   3. **Buscar por correo exacto**, para quien ya se registró por su
 *      cuenta —una familia que paga— y hay que sumar al curso.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO HAY UN BUSCADOR POR NOMBRE
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un buscador de personas por nombre dentro de una plataforma con datos
 * de menores es un directorio de menores: cualquiera con una cuenta de
 * docente podría teclear letras y listar niños.
 *
 * «Mis estudiantes» no es eso: solo devuelve a quien este mismo docente
 * matriculó él. Y el correo exacto obliga a saber a quién se busca, que
 * es lo que pasa de verdad cuando una familia te da su dirección.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS CREDENCIALES SE ENSEÑAN UNA VEZ
 * ─────────────────────────────────────────────────────────────────────
 *
 * Al crear cuentas se muestran usuario y contraseña para imprimir, y no
 * se guardan en ningún sitio: en la base solo queda el hash. Si se
 * pierden, se restablecen en un clic. Una contraseña que se puede volver
 * a consultar no es una contraseña.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

$docenteId = usuarioActualId();

/** Cuántas cuentas se pueden crear de una vez. */
const MAX_CUENTAS_POR_TANDA = 40;

$encontrada = null;
$buscado    = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');

    // ── Crear cuentas en tanda ───────────────────────────────────────
    if ($accion === 'crear') {

        $lineas = preg_split('/\r\n|\r|\n/', (string) post('nombres')) ?: [];
        $lineas = array_values(array_filter(array_map('trim', $lineas), fn($l) => $l !== ''));

        if (!$lineas) {
            mensaje('error', 'Escribe al menos un nombre.');

        } elseif (count($lineas) > MAX_CUENTAS_POR_TANDA) {
            mensaje('error', 'Son demasiados de una vez (máximo '
                           . MAX_CUENTAS_POR_TANDA . '). Pártelo en dos tandas.');

        } else {
            $creadas = [];
            $fallos  = [];

            foreach ($lineas as $linea) {
                /*
                 * Se acepta «Nombre» o «Nombre, correo@familia.com». La
                 * coma es lo que sale de pegar una lista de un Excel, y
                 * pedir un formato más estricto haría que el docente
                 * tuviera que limpiarla a mano.
                 */
                $partes  = array_map('trim', explode(',', $linea, 3));
                $nombre  = $partes[0] ?? '';
                $correo  = $partes[1] ?? '';

                $r = crearCuentaEstudiante($cursoId, $nombre, $correo);

                if ($r['ok']) {
                    $creadas[] = ['nombre' => $r['usuario']['name'],
                                  'correo' => $r['usuario']['email'],
                                  'clave'  => $r['clave']];
                } else {
                    $fallos[] = $r['error'];
                }
            }

            /*
             * Las credenciales van en la sesión y se enseñan UNA vez, al
             * recargar. Así no quedan en el historial del navegador como
             * respuesta a un POST reenviable.
             */
            if ($creadas) {
                $_SESSION['cuentas_creadas'] = $creadas;
                mensaje('ok', count($creadas) . ' cuenta(s) creada(s) y matriculada(s).');
            }

            foreach (array_slice($fallos, 0, 5) as $f) {
                mensaje('error', $f);
            }
            if (count($fallos) > 5) {
                mensaje('info', 'Y ' . (count($fallos) - 5) . ' más con problemas.');
            }
        }

        redirigir('escuela/estudiantes.php?curso=' . $cursoId);
    }

    // ── Traer de mis otros cursos ────────────────────────────────────
    if ($accion === 'traer') {

        $ids = array_map('intval', (array) ($_POST['alumnos'] ?? []));
        $n   = 0;

        foreach ($ids as $uid) {
            /*
             * Solo se acepta a quien YA está en algún curso de este
             * docente. Los ids vienen de un formulario: sin esta
             * comprobación, cambiar un número matricularía a cualquier
             * niño de la plataforma en tu clase.
             */
            $suyo = traerValor(
                'SELECT 1 FROM course_students cs
                   JOIN courses c ON c.id = cs.course_id
                  WHERE cs.user_id = ? AND c.teacher_id = ? LIMIT 1',
                [$uid, $docenteId]
            );

            if ($suyo && matricularEstudiante($cursoId, $uid)) {
                $n++;
            }
        }

        mensaje($n > 0 ? 'ok' : 'info',
            $n > 0 ? "$n estudiante(s) añadido(s) al curso."
                   : 'No se añadió a nadie: quizá ya estaban.');

        redirigir('escuela/estudiantes.php?curso=' . $cursoId);
    }

    // ── Traer el curso entero ────────────────────────────────────────
    //
    // El paso de año: el grupo de primero entero pasa a segundo. Con las
    // casillas de «mis otros cursos» eran treinta clics; aquí es uno.
    if ($accion === 'importar') {

        $r = importarEstudiantes($cursoId, (int) post('origen'), $docenteId);

        if (!$r['ok']) {
            mensaje('error', (string) $r['error']);

        } elseif ($r['nuevos'] > 0) {
            mensaje('ok', $r['nuevos'] . ' estudiante(s) traído(s) al curso.'
                . ($r['repetidos'] > 0 ? ' Otros ' . $r['repetidos'] . ' ya estaban.' : '')
                . ' Siguen matriculados en el curso de origen: su historial de allá no se toca.');

        } else {
            mensaje('info', $r['repetidos'] > 0
                ? 'Todos los de ese curso ya estaban aquí. Nada que traer.'
                : 'Ese curso no tiene estudiantes.');
        }

        redirigir('escuela/estudiantes.php?curso=' . $cursoId);
    }

    // ── Buscar por correo ────────────────────────────────────────────
    if ($accion === 'buscar') {
        $buscado    = trim(post('correo'));
        $encontrada = buscarCuentaPorCorreo($buscado);

        if (!$encontrada) {
            mensaje('info',
                'No hay ninguna cuenta activa con ese correo. Si el estudiante todavía '
                . 'no tiene cuenta, créasela arriba: es lo más rápido.');
        }
    }

    // ── Matricular a quien se encontró ───────────────────────────────
    if ($accion === 'matricular') {
        $usuarioId = (int) post('usuario');

        // Se vuelve a comprobar que la cuenta exista y esté activa: el id
        // llegó por un formulario y un formulario se puede editar.
        $cuenta = traerUno(
            'SELECT id, name FROM users WHERE id = ? AND status = "active"',
            [$usuarioId]
        );

        if (!$cuenta) {
            mensaje('error', 'Esa cuenta no existe o está desactivada.');
        // Sin `e()`: la plantilla ya escapa el texto del aviso al pintarlo.
        } elseif (matricularEstudiante($cursoId, (int) $cuenta['id'])) {
            mensaje('ok', $cuenta['name'] . ' quedó matriculado en el curso.');
        } else {
            mensaje('info', $cuenta['name'] . ' ya estaba en este curso.');
        }

        redirigir('escuela/estudiantes.php?curso=' . $cursoId);
    }

    // ── Restablecer la clave de un estudiante ────────────────────────
    if ($accion === 'clave') {
        $r = restablecerClaveDeEstudiante($cursoId, (int) post('usuario'));

        if ($r['ok']) {
            $alumno = traerUno('SELECT name, email FROM users WHERE id = ?', [(int) post('usuario')]);

            $_SESSION['cuentas_creadas'] = [[
                'nombre' => $alumno['name'], 'correo' => $alumno['email'], 'clave' => $r['clave'],
            ]];
            mensaje('ok', 'Contraseña nueva generada. Anótala: no se puede volver a ver.');
        } else {
            mensaje('error', $r['error']);
        }

        redirigir('escuela/estudiantes.php?curso=' . $cursoId);
    }

    /*
     * Todo lo del código de clase y los PIN se fue a `acceso.php`.
     *
     * Estaba aquí y era el sitio equivocado: mezclado con matricular
     * gente, el docente no encontraba ni la dirección ni los PIN. Cómo
     * entran es una decisión propia y tiene su pantalla.
     */

    // ── Retirar ──────────────────────────────────────────────────────
    if ($accion === 'retirar') {
        retirarEstudiante($cursoId, (int) post('usuario'));

        mensaje('ok', 'Estudiante retirado del curso. Su cuenta y su progreso no se borran.');
        redirigir('escuela/estudiantes.php?curso=' . $cursoId);
    }
}

// Credenciales recién generadas, para enseñarlas una sola vez.
$credenciales = $_SESSION['cuentas_creadas'] ?? [];
unset($_SESSION['cuentas_creadas']);

$estudiantes = estudiantesDelCurso($cursoId);
$disponibles = misEstudiantes($docenteId, $cursoId);
$importables = cursosParaImportar($cursoId, $docenteId);

$titulo      = 'Estudiantes · ' . $curso['name'];
$escuelaZona = 'estudiantes';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1>Estudiantes</h1>
        <p class="bajada"><?= e($curso['name']) ?> · <?= count($estudiantes) ?> matriculados</p>
    </div>
    <?php if ($estudiantes): ?>
        <a class="btn btn-secundario btn-chico"
           href="<?= e(url('escuela/actividades.php?curso=' . $cursoId)) ?>">
            Siguiente: asignar actividades →
        </a>
    <?php endif; ?>
</header>


<?php if ($credenciales): ?>
    <?php
    /*
     * Se enseña una vez y no vuelve. Va arriba del todo y con aviso
     * grande porque si el docente cierra la pestaña sin copiarlo, hay que
     * restablecer todas las contraseñas de nuevo.
     */
    ?>
    <section class="bloque-panel credenciales-nuevas">
        <h2>🔑 Anota estas contraseñas ahora</h2>
        <p class="nota-panel">
            <strong>No se pueden volver a ver.</strong> En la base solo queda cifrada, que
            es como debe ser. Si se pierden, se restablecen desde la lista de abajo.
        </p>

        <table class="tabla-panel">
            <thead>
                <tr><th>Estudiante</th><th>Usuario</th><th>Contraseña</th></tr>
            </thead>
            <tbody>
            <?php foreach ($credenciales as $c): ?>
                <tr>
                    <td><strong><?= e($c['nombre']) ?></strong></td>
                    <td class="mono"><?= e($c['correo']) ?></td>
                    <td class="mono clave-nueva"><?= e($c['clave']) ?></td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p class="nota-panel" style="margin-top:14px">
            Las direcciones <code>@<?= e(dominioDeAula()) ?></code> no reciben correo:
            son solo el usuario con el que entra cada niño.
        </p>

        <button class="btn btn-secundario btn-chico" type="button" onclick="window.print()">
            🖨️ Imprimir
        </button>
    </section>
<?php endif; ?>


<?php
/*
 * Cómo entran vive en `acceso.php`, no aquí. Esta pantalla es para
 * matricular gente; mezclarle la configuración del código hacía que no
 * se encontrara ni una cosa ni la otra.
 */
?>
<?php if (aulaInstalada() && usaLista($curso) && !empty($curso['access_code'])): ?>
    <p class="nota-panel" style="margin-bottom:18px">
        Estos estudiantes entran en
        <a href="<?= e(urlDeAula((string) $curso['access_code'])) ?>" target="_blank" rel="noopener">
            <b><?= e(urlDeAula((string) $curso['access_code'])) ?></b></a>
        tocando su nombre.
        <a href="<?= e(url('escuela/acceso.php?curso=' . $cursoId)) ?>">Cambiar cómo entran</a>
        ·
        <a href="<?= e(url('escuela/tarjetas.php?curso=' . $cursoId)) ?>">Hoja para repartir</a>
    </p>
<?php endif; ?>


<!-- ── 1. Crear cuentas ─────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Crear cuentas nuevas</h2>
    <p class="nota-panel">
        Un nombre por línea. Se crean las cuentas, se matriculan en este curso y te
        damos las contraseñas para repartir.
        Si alguien tiene correo, ponlo detrás separado por coma.
    </p>

    <form method="post">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="crear">

        <textarea name="nombres" rows="7" class="area-nombres"
                  placeholder="Ana Pérez&#10;Luis Gómez&#10;Sara Díaz, familia.diaz@correo.com"></textarea>

        <div class="pie-bloque">
            <button class="btn btn-principal" type="submit">Crear y matricular</button>
            <span class="tenue">Máximo <?= MAX_CUENTAS_POR_TANDA ?> por tanda.</span>
        </div>
    </form>
</section>


<!-- ── 2. Traer un curso entero ─────────────────────────────────────── -->

<?php
/*
 * El paso de año. Un docente abre «Segundo», elige «Primero» y vienen
 * todos: era el caso más frecuente del panel y el único que obligaba a
 * marcar treinta casillas a mano.
 *
 * Solo aparecen cursos con estudiantes, y se dice cuántos FALTAN por
 * traer: un curso del que ya se importó todo no tiene nada que ofrecer,
 * y ofrecerlo igual hace pulsar un botón que no va a hacer nada.
 */
?>
<?php if ($importables): ?>
<section class="bloque-panel">
    <h2>Traer los estudiantes de otro curso</h2>
    <p class="nota-panel">
        Para el paso de año: abre el curso nuevo y trae el grupo del año pasado de una vez.
        <strong>Siguen matriculados en el curso de origen</strong> — su historial de allá no
        se toca, así que el informe de ese curso sigue diciendo la verdad.
    </p>

    <form method="post" class="form-linea">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="importar">

        <label>
            Traer de
            <select name="origen" required>
                <?php foreach ($importables as $c): ?>
                    <option value="<?= (int) $c['id'] ?>" <?= (int) $c['nuevos'] === 0 ? 'disabled' : '' ?>>
                        <?= e($c['name']) ?><?php if ($c['year']): ?> · <?= (int) $c['year'] ?><?php endif; ?>
                        <?php if ($c['status'] === 'archived'): ?> (archivado)<?php endif; ?>
                        — <?= (int) $c['nuevos'] ?> por traer de <?= (int) $c['estudiantes'] ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <button class="btn btn-principal" type="submit">Traer al curso</button>
    </form>
</section>
<?php endif; ?>


<!-- ── 3. Mis estudiantes, uno a uno ────────────────────────────────── -->

<?php if ($disponibles): ?>
<section class="bloque-panel">
    <h2>Traer de mis otros cursos</h2>
    <p class="nota-panel">
        Estudiantes que ya tienes en otros cursos tuyos y todavía no están en este.
    </p>

    <form method="post">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="traer">

        <div class="rejilla-alumnos">
            <?php foreach ($disponibles as $a): ?>
                <label class="ficha-alumno">
                    <input type="checkbox" name="alumnos[]" value="<?= (int) $a['id'] ?>">
                    <span>
                        <b><?= e($a['name']) ?></b>
                        <small><?= (int) $a['cursos'] ?> curso(s) contigo</small>
                    </span>
                </label>
            <?php endforeach; ?>
        </div>

        <div class="pie-bloque">
            <button class="btn btn-principal" type="submit">Añadir los marcados</button>
            <button class="btn btn-secundario btn-chico" type="button"
                    onclick="document.querySelectorAll('[name=\'alumnos[]\']').forEach(c=>c.checked=true)">
                Marcar todos
            </button>
        </div>
    </form>
</section>
<?php endif; ?>


<!-- ── 3. Buscar por correo ─────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Buscar una cuenta que ya existe</h2>
    <p class="nota-panel">
        Para quien ya se registró por su cuenta. Hace falta el correo exacto.
    </p>

    <form method="post" class="form-linea">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="buscar">
        <label>
            Correo
            <input type="email" name="correo" required maxlength="190"
                   value="<?= e($buscado) ?>" placeholder="nombre@correo.com">
        </label>
        <button class="btn btn-secundario" type="submit">Buscar</button>
    </form>

    <?php if ($encontrada): ?>
        <div class="hallazgo">
            <div>
                <strong><?= e($encontrada['name']) ?></strong>
                <span class="tenue"><?= e($encontrada['email']) ?></span>
            </div>
            <form method="post">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="matricular">
                <input type="hidden" name="usuario" value="<?= (int) $encontrada['id'] ?>">
                <button class="btn btn-principal btn-chico" type="submit">
                    Matricular en <?= e($curso['name']) ?>
                </button>
            </form>
        </div>
    <?php endif; ?>
</section>


<!-- ── Los que ya están ─────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Matriculados</h2>

    <?php if (!$estudiantes): ?>
        <p class="nota-panel">Todavía no hay nadie en este curso.</p>
    <?php else: ?>
        <?php
        /*
         * Ordenados por avance, de más a menos.
         *
         * Es lo que un docente busca en esta lista: quién va tirando del
         * grupo y quién se está quedando. Por orden alfabético había que
         * leer treinta números y compararlos de cabeza.
         */
        $porAvance = $estudiantes;
        usort($porAvance, static fn($a, $b) =>
            (int) $b['porcentaje'] <=> (int) $a['porcentaje']
            ?: strcmp((string) $a['name'], (string) $b['name']));
        ?>

        <table class="tabla-panel">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Usuario</th>
                    <th style="min-width:190px">Avance del curso</th>
                    <th>Última vez</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($porAvance as $al): ?>
                <?php
                $pc     = (int) $al['porcentaje'];
                $hechas = (int) $al['estaciones_hechas'];
                $total  = (int) $al['estaciones_totales'];

                // Los mismos cortes que la rejilla de progreso, para que
                // el color signifique lo mismo en las dos pantallas.
                $tono = $pc >= 80 ? 'completa' : ($pc >= 40 ? 'media' : ($pc > 0 ? 'poca' : 'nada'));
                ?>
                <tr>
                    <td>
                        <a href="<?= e(url('escuela/estudiante.php?curso=' . $cursoId . '&id=' . (int) $al['id'])) ?>">
                            <strong><?= e($al['name']) ?></strong>
                        </a>
                    </td>
                    <td class="tenue mono"><?= e($al['email']) ?></td>
                    <td>
                        <?php if ($total === 0): ?>
                            <span class="tenue">sin actividades asignadas</span>
                        <?php else: ?>
                            <div class="barra-alumno <?= $tono ?>">
                                <span style="width:<?= $pc ?>%"></span>
                            </div>
                            <span class="tenue barra-cifra">
                                <?= $pc ?>% · <?= $hechas ?> de <?= $total ?>
                                <?php if ((int) $al['estaciones_de_casa'] > 0): ?>
                                    <span class="marca-casa" title="<?= (int) $al['estaciones_de_casa'] ?> de estas ya las había hecho por su cuenta">🏠</span>
                                <?php endif; ?>
                            </span>
                        <?php endif; ?>
                    </td>
                    <td class="tenue">
                        <?= $al['ultima_vez']
                            ? e(date('d/m/Y', strtotime((string) $al['ultima_vez'])))
                            : 'nunca' ?>
                    </td>
                    <td class="acciones-fila">
                        <?php /* Los PIN se gestionan en `acceso.php`. */ ?>
                        <form method="post"
                              onsubmit="return confirm('¿Generar una contraseña nueva para <?= e($al['name']) ?>?\n\nLa anterior dejará de servir.')">
                            <?= campoCsrf() ?>
                            <input type="hidden" name="accion" value="clave">
                            <input type="hidden" name="usuario" value="<?= (int) $al['id'] ?>">
                            <button class="btn btn-secundario btn-chico" type="submit"
                                    title="Generar una contraseña nueva">🔑</button>
                        </form>

                        <form method="post"
                              onsubmit="return confirm('¿Retirar a <?= e($al['name']) ?> del curso?\n\nSu cuenta y su progreso no se borran.')">
                            <?= campoCsrf() ?>
                            <input type="hidden" name="accion" value="retirar">
                            <input type="hidden" name="usuario" value="<?= (int) $al['id'] ?>">
                            <button class="btn btn-secundario btn-chico" type="submit">Retirar</button>
                        </form>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
