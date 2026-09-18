<?php
/**
 * admin/cursos/index.php — Todos los cursos de la plataforma
 *
 * Los cursos los crean los docentes desde su área, y hasta ahora quien
 * administra no tenía forma de verlos: ni cuántos hay, ni de quién son,
 * ni si están vivos. Para dar soporte —«un profe dice que no le aparece
 * su clase»— hacía falta entrar a la base de datos.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO HAY UNA FICHA DE CURSO APARTE
 * ─────────────────────────────────────────────────────────────────────
 *
 * Esta pantalla lista y filtra, y para ver un curso por dentro lleva al
 * área Escuela, que es donde ya está todo: estudiantes, actividades,
 * progreso, acceso. `exigirCursoPropio()` deja al administrador abrir
 * cualquier curso justo para esto.
 *
 * Duplicar aquí esas cuatro pantallas significaría mantenerlas dos veces
 * y que se separen en cuanto una cambie. Lo que sí falta —y va aquí— es
 * lo que un docente no puede hacer: cambiarle el dueño a un curso o
 * moverlo de colegio.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $cursoId = (int) post('curso');
    $curso   = $cursoId > 0 ? traerUno('SELECT * FROM courses WHERE id = ?', [$cursoId]) : null;

    if (!$curso) {
        mensaje('error', 'Ese curso no existe.');
        redirigir('admin/cursos/');
    }

    // ── Cambiar de docente ───────────────────────────────────────────
    if (post('accion') === 'docente') {

        $nuevo = (int) post('docente_id');

        $esDocente = $nuevo > 0 && traerValor(
            'SELECT 1 FROM users WHERE id = ? AND status = "active"
               AND role IN ("teacher","school_admin","admin")', [$nuevo]
        );

        if (!$esDocente) {
            mensaje('error', 'Esa cuenta no puede llevar un curso. '
                           . 'Dale el tipo «Docente» desde Personas.');
        } else {
            ejecutar('UPDATE courses SET teacher_id = ? WHERE id = ?', [$nuevo, $cursoId]);

            $n = traerUno('SELECT name FROM users WHERE id = ?', [$nuevo]);
            mensaje('ok', 'El curso pasó a ' . $n['name'] . '.');
        }
    }

    // ── Mover de colegio ─────────────────────────────────────────────
    if (post('accion') === 'colegio') {

        $colegioId = (int) post('colegio_id');

        ejecutar('UPDATE courses SET school_id = ? WHERE id = ?',
                 [$colegioId ?: null, $cursoId]);

        mensaje('ok', $colegioId > 0
            ? 'Curso movido al colegio.'
            : 'Curso desligado de cualquier colegio.');
    }

    // ── Archivar o reactivar ─────────────────────────────────────────
    if (post('accion') === 'estado') {
        $nuevo = $curso['status'] === 'active' ? 'archived' : 'active';
        ejecutar('UPDATE courses SET status = ? WHERE id = ?', [$nuevo, $cursoId]);

        mensaje('ok', $nuevo === 'archived' ? 'Curso archivado.' : 'Curso reactivado.');
    }

    redirigir('admin/cursos/' . (get('colegio') !== '' ? '?colegio=' . urlencode(get('colegio')) : ''));
}

// ── Filtros ──────────────────────────────────────────────────────────
$fColegio = get('colegio');
$fDocente = getEntero('docente');
$fEstado  = get('estado');
$fBuscar  = trim(get('buscar'));

$sql = 'SELECT c.*, u.name AS docente, u.email AS docente_correo,
               s.name AS colegio, s.slug AS colegio_slug, s.es_prueba,
               (SELECT COUNT(*) FROM course_students cs WHERE cs.course_id = c.id) AS estudiantes,
               (SELECT COUNT(*) FROM course_activities ca WHERE ca.course_id = c.id) AS actividades,
               (SELECT MAX(p.updated_at)
                  FROM activity_progress p
                  JOIN course_students cs2 ON cs2.user_id = p.user_id
                                          AND cs2.course_id = c.id) AS ultimo_movimiento
          FROM courses c
     LEFT JOIN users   u ON u.id = c.teacher_id
     LEFT JOIN schools s ON s.id = c.school_id
         WHERE 1 = 1';
$params = [];

if ($fColegio === 'sin') {
    $sql .= ' AND c.school_id IS NULL';
} elseif ($fColegio !== '') {
    $sql .= ' AND s.slug = ?';
    $params[] = $fColegio;
}

if ($fDocente > 0)   { $sql .= ' AND c.teacher_id = ?'; $params[] = $fDocente; }
if ($fEstado !== '') { $sql .= ' AND c.status = ?';     $params[] = $fEstado; }

if ($fBuscar !== '') {
    $sql .= ' AND (c.name LIKE ? OR u.name LIKE ?)';
    $params[] = '%' . escaparLike($fBuscar) . '%';
    $params[] = '%' . escaparLike($fBuscar) . '%';
}

$sql .= ' ORDER BY c.status = "archived", s.name IS NULL, s.name, c.name LIMIT 200';

$cursos = traerTodo($sql, $params);

$listaColegios = colegiosInstalados()
    ? traerTodo('SELECT id, slug, name FROM schools ORDER BY name')
    : [];

$docentes = traerTodo(
    'SELECT DISTINCT u.id, u.name, u.email
       FROM users u
      WHERE u.status = "active" AND u.role IN ("teacher","school_admin","admin")
   ORDER BY u.name'
);

// Cifras de arriba: la foto de conjunto que no había en ninguna parte.
$total     = (int) traerValor('SELECT COUNT(*) FROM courses');
$activos   = (int) traerValor('SELECT COUNT(*) FROM courses WHERE status = "active"');
$conAlumnos = (int) traerValor(
    'SELECT COUNT(*) FROM courses c
      WHERE EXISTS (SELECT 1 FROM course_students cs WHERE cs.course_id = c.id)'
);
$sinNada = (int) traerValor(
    'SELECT COUNT(*) FROM courses c
      WHERE NOT EXISTS (SELECT 1 FROM course_students cs WHERE cs.course_id = c.id)
        AND NOT EXISTS (SELECT 1 FROM course_activities ca WHERE ca.course_id = c.id)'
);

$titulo    = 'Cursos';
$panelZona = 'cursos';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Cursos</h1>
        <p>Todos los cursos de la plataforma, los creen quien los cree.</p>
    </div>
</div>

<div class="cifras">
    <div class="cifra-caja">
        <div class="n"><?= $total ?></div>
        <div class="t">Cursos</div>
    </div>
    <div class="cifra-caja verde">
        <div class="n"><?= $activos ?></div>
        <div class="t">Activos</div>
    </div>
    <div class="cifra-caja naranja">
        <div class="n"><?= $conAlumnos ?></div>
        <div class="t">Con estudiantes</div>
    </div>
    <div class="cifra-caja <?= $sinNada > 0 ? 'rojo' : 'gris' ?>">
        <div class="n"><?= $sinNada ?></div>
        <div class="t">Vacíos del todo</div>
    </div>
</div>


<!-- ── Filtros ──────────────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>Buscar</h2></div>
    <div class="cuerpo">
        <form method="get" class="barra-filtros">

            <select name="colegio">
                <option value="">Todos los colegios</option>
                <option value="sin" <?= $fColegio === 'sin' ? 'selected' : '' ?>>
                    Sin colegio (docentes sueltos)
                </option>
                <?php foreach ($listaColegios as $c): ?>
                    <option value="<?= e($c['slug']) ?>" <?= $fColegio === $c['slug'] ? 'selected' : '' ?>>
                        <?= e($c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="docente">
                <option value="">Todos los docentes</option>
                <?php foreach ($docentes as $d): ?>
                    <option value="<?= (int) $d['id'] ?>" <?= $fDocente === (int) $d['id'] ? 'selected' : '' ?>>
                        <?= e($d['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="estado">
                <option value="">Todos</option>
                <option value="active"   <?= $fEstado === 'active' ? 'selected' : '' ?>>Activos</option>
                <option value="archived" <?= $fEstado === 'archived' ? 'selected' : '' ?>>Archivados</option>
            </select>

            <input type="search" name="buscar" value="<?= e($fBuscar) ?>"
                   placeholder="Curso o docente…">

            <button class="btn-mini solido" type="submit">Filtrar</button>

            <?php if ($fColegio !== '' || $fDocente > 0 || $fEstado !== '' || $fBuscar !== ''): ?>
                <a class="btn-mini" href="<?= e(url('admin/cursos/')) ?>">Quitar filtros</a>
            <?php endif; ?>
        </form>
    </div>
</div>


<!-- ── La lista ─────────────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2><?= count($cursos) ?> curso(s)</h2></div>
    <div class="cuerpo">

        <?php if (!$cursos): ?>
            <div class="sin-datos">
                <div class="ico">📚</div>
                <h3>No hay cursos con esos filtros</h3>
            </div>
        <?php else: ?>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Curso</th>
                            <th>Colegio</th>
                            <th>Docente</th>
                            <th class="cifra">Estud.</th>
                            <th class="cifra">Activ.</th>
                            <th>Entran por</th>
                            <th>Último movimiento</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cursos as $c): ?>
                        <tr class="<?= $c['status'] === 'archived' ? 'fila-tenue' : '' ?>">
                            <td>
                                <a href="<?= e(url('escuela/curso.php?id=' . (int) $c['id'])) ?>">
                                    <b><?= e($c['name']) ?></b>
                                </a>
                                <?php if ($c['status'] === 'archived'): ?>
                                    <span class="distintivo gris">archivado</span>
                                <?php endif; ?>
                                <div class="compacta" style="color:var(--texto-tenue)">
                                    <?= e($c['grade'] ?: '') ?>
                                    <?= $c['year'] ? '· ' . (int) $c['year'] : '' ?>
                                </div>
                            </td>

                            <td class="compacta">
                                <?php if ($c['colegio']): ?>
                                    <a href="<?= e(url('admin/cursos/?colegio=' . urlencode((string) $c['colegio_slug']))) ?>">
                                        <?= e($c['colegio']) ?>
                                    </a>
                                    <?php if ((int) $c['es_prueba'] === 1): ?>
                                        <span class="distintivo naranja">prueba</span>
                                    <?php endif; ?>
                                <?php else: ?>
                                    <span style="color:var(--texto-tenue)">—</span>
                                <?php endif; ?>
                            </td>

                            <td class="compacta">
                                <?php if ($c['docente']): ?>
                                    <a href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $c['teacher_id'])) ?>">
                                        <?= e($c['docente']) ?>
                                    </a>
                                <?php else: ?>
                                    <span class="distintivo rojo">sin docente</span>
                                <?php endif; ?>
                            </td>

                            <td class="cifra"><?= (int) $c['estudiantes'] ?></td>
                            <td class="cifra"><?= (int) $c['actividades'] ?></td>

                            <td class="compacta">
                                <?php if (aulaInstalada() && usaLista($c) && !empty($c['access_code'])): ?>
                                    <code><?= e($c['access_code']) ?></code>
                                    <span class="distintivo <?= claseAbierta($c) ? 'verde' : 'gris' ?>">
                                        <?= claseAbierta($c) ? 'abierta' : 'cerrada' ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color:var(--texto-tenue)">su cuenta</span>
                                <?php endif; ?>
                            </td>

                            <td class="compacta" style="color:var(--texto-tenue)">
                                <?= $c['ultimo_movimiento']
                                    ? e(date('d/m/Y', strtotime((string) $c['ultimo_movimiento'])))
                                    : 'nunca' ?>
                            </td>

                            <td class="acciones-celda">
                                <details class="menu-curso">
                                    <summary class="btn-mini">Cambiar</summary>
                                    <div class="menu-curso-cuerpo">

                                        <form method="post">
                                            <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="docente">
                                            <input type="hidden" name="curso" value="<?= (int) $c['id'] ?>">
                                            <label>Docente</label>
                                            <select name="docente_id">
                                                <?php foreach ($docentes as $d): ?>
                                                    <option value="<?= (int) $d['id'] ?>"
                                                        <?= (int) $c['teacher_id'] === (int) $d['id'] ? 'selected' : '' ?>>
                                                        <?= e($d['name']) ?>
                                                    </option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="btn-mini solido" type="submit">Cambiar dueño</button>
                                        </form>

                                        <?php if ($listaColegios): ?>
                                            <form method="post" style="margin-top:10px">
                                                <?= campoCsrf() ?>
                                                <input type="hidden" name="accion" value="colegio">
                                                <input type="hidden" name="curso" value="<?= (int) $c['id'] ?>">
                                                <label>Colegio</label>
                                                <select name="colegio_id">
                                                    <option value="">Sin colegio</option>
                                                    <?php foreach ($listaColegios as $s): ?>
                                                        <option value="<?= (int) $s['id'] ?>"
                                                            <?= (int) $c['school_id'] === (int) $s['id'] ? 'selected' : '' ?>>
                                                            <?= e($s['name']) ?>
                                                        </option>
                                                    <?php endforeach; ?>
                                                </select>
                                                <button class="btn-mini" type="submit">Mover</button>
                                            </form>
                                        <?php endif; ?>

                                        <form method="post" style="margin-top:10px">
                                            <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="estado">
                                            <input type="hidden" name="curso" value="<?= (int) $c['id'] ?>">
                                            <button class="btn-mini" type="submit">
                                                <?= $c['status'] === 'active' ? 'Archivar' : 'Reactivar' ?>
                                            </button>
                                        </form>
                                    </div>
                                </details>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <p class="aviso-suave" style="margin-top:16px">
                Al abrir un curso entras al <b>área Escuela</b>, que es donde está todo:
                estudiantes, actividades, progreso y acceso. No se duplica aquí para que
                no se separen las dos versiones.
            </p>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
