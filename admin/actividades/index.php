<?php
/**
 * admin/actividades/index.php — Listado y gestión de actividades
 *
 * Filtros, acciones rápidas (publicar / despublicar / eliminar) y acceso
 * a la edición completa y a las estaciones de cada actividad.
 *
 * Toda acción que modifica datos va por POST con token CSRF. Un enlace
 * GET que borrara una actividad podría dispararse solo: bastaría con que
 * un rastreador o una precarga del navegador lo visitara.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

// ── Acciones ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $id     = (int) ($_POST['id'] ?? 0);

    if ($id > 0) {
        switch ($accion) {
            case 'publicar':
                cambiarEstadoActividad($id, 'published');
                mensaje('ok', 'Actividad publicada.');
                break;

            case 'despublicar':
                cambiarEstadoActividad($id, 'draft');
                mensaje('ok', 'Actividad despublicada. Ya no aparece en el catálogo.');
                break;

            case 'destacar':
                ejecutar('UPDATE activities SET is_featured = 1 - is_featured WHERE id = ?', [$id]);
                mensaje('ok', 'Cambió el estado de destacada.');
                break;

            case 'eliminar':
                $t = traerValor('SELECT title FROM activities WHERE id = ?', [$id]);
                if (eliminarActividad($id)) {
                    mensaje('ok', 'Se eliminó «' . $t . '» junto con sus estaciones y su progreso.');
                }
                break;
        }
    }

    // Se redirige tras escribir para que recargar la página no repita la
    // acción (patrón POST-Redirect-GET).
    redirigir('admin/actividades/' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
}

// ── Filtros ──────────────────────────────────────────────────────────
$fBuscar    = mb_substr(get('buscar'), 0, 80);
$fCategoria = getEntero('categoria');
$fEstado    = get('estado');
$fAcceso    = get('acceso');
$pagina     = max(1, getEntero('pagina', 1));
const POR_PAGINA_ADMIN = 30;

$where  = ['1 = 1'];
$params = [];

if ($fBuscar !== '') {
    $where[] = '(a.title LIKE ? OR a.slug LIKE ?)';
    $t = '%' . escaparLike($fBuscar) . '%';
    $params[] = $t;
    $params[] = $t;
}
if ($fCategoria > 0) {
    $where[] = 'a.category_id = ?';
    $params[] = $fCategoria;
}
if (in_array($fEstado, ['draft', 'published', 'archived'], true)) {
    $where[] = 'a.status = ?';
    $params[] = $fEstado;
}
if (in_array($fAcceso, [ACCESO_LIBRE, ACCESO_PARCIAL, ACCESO_PREMIUM], true)) {
    $where[] = 'a.access_type = ?';
    $params[] = $fAcceso;
}

$sqlWhere = 'WHERE ' . implode(' AND ', $where);

$total = (int) traerValor(
    "SELECT COUNT(*) FROM activities a $sqlWhere",
    $params
);
$totalPaginas = max(1, (int) ceil($total / POR_PAGINA_ADMIN));
$pagina = min($pagina, $totalPaginas);
$desde  = ($pagina - 1) * POR_PAGINA_ADMIN;

$actividades = traerTodo(
    "SELECT a.id, a.slug, a.title, a.icon, a.status, a.access_type, a.free_stations,
            a.is_featured, a.engine, a.published_at,
            c.name AS categoria, c.icon AS categoria_icon,
            l.name AS nivel,
            (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones
       FROM activities a
  LEFT JOIN categories c ON c.id = a.category_id
  LEFT JOIN levels     l ON l.id = a.level_id
     $sqlWhere
   ORDER BY a.updated_at DESC
      LIMIT " . POR_PAGINA_ADMIN . " OFFSET $desde",
    $params
);

$categorias = traerTodo('SELECT id, name, icon FROM categories ORDER BY sort_order');

/** URL del listado conservando los filtros. */
function urlAdmin(array $cambios = []): string
{
    $base = [
        'buscar'    => get('buscar'),
        'categoria' => getEntero('categoria') ?: '',
        'estado'    => get('estado'),
        'acceso'    => get('acceso'),
        'pagina'    => getEntero('pagina', 1),
    ];
    $n = array_merge($base, $cambios);
    if (!array_key_exists('pagina', $cambios)) {
        $n['pagina'] = 1;
    }
    $n = array_filter($n, static fn($v, $k) =>
        $v !== '' && $v !== null && !($k === 'pagina' && (int) $v === 1), ARRAY_FILTER_USE_BOTH);

    return url('admin/actividades/') . ($n ? '?' . http_build_query($n) : '');
}

$titulo    = 'Actividades';
$panelZona = 'actividades';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Actividades</h1>
        <p><?= (int) $total ?> <?= $total === 1 ? 'actividad' : 'actividades' ?> con los filtros actuales.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini solido" href="<?= e(url('admin/actividades/editar.php')) ?>">+ Nueva actividad</a>
    </div>
</div>

<div class="caja">

    <form class="barra-filtros" method="get">
        <input type="search" name="buscar" value="<?= e($fBuscar) ?>"
               placeholder="Buscar por título o slug…" aria-label="Buscar">

        <select name="categoria" aria-label="Categoría">
            <option value="">Todas las categorías</option>
            <?php foreach ($categorias as $c): ?>
                <option value="<?= (int) $c['id'] ?>" <?= $fCategoria === (int) $c['id'] ? 'selected' : '' ?>>
                    <?= e($c['icon'] . ' ' . $c['name']) ?>
                </option>
            <?php endforeach; ?>
        </select>

        <select name="estado" aria-label="Estado">
            <option value="">Todos los estados</option>
            <option value="published" <?= $fEstado === 'published' ? 'selected' : '' ?>>Publicadas</option>
            <option value="draft"     <?= $fEstado === 'draft'     ? 'selected' : '' ?>>Borradores</option>
            <option value="archived"  <?= $fEstado === 'archived'  ? 'selected' : '' ?>>Archivadas</option>
        </select>

        <select name="acceso" aria-label="Acceso">
            <option value="">Todos los accesos</option>
            <option value="free"    <?= $fAcceso === 'free'    ? 'selected' : '' ?>>🎁 Libre</option>
            <option value="partial" <?= $fAcceso === 'partial' ? 'selected' : '' ?>>🎯 Parcial</option>
            <option value="premium" <?= $fAcceso === 'premium' ? 'selected' : '' ?>>🔒 Premium</option>
        </select>

        <button class="btn-mini solido" type="submit">Filtrar</button>
        <a class="btn-mini" href="<?= e(url('admin/actividades/')) ?>">Limpiar</a>
    </form>

    <?php if ($actividades): ?>

        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Actividad</th>
                        <th>Categoría</th>
                        <th>Nivel</th>
                        <th>Acceso</th>
                        <th>Estaciones</th>
                        <th>Estado</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($actividades as $a): ?>
                        <tr>
                            <td>
                                <b><?= e($a['icon']) ?> <?= e($a['title']) ?></b>
                                <?php if ((int) $a['is_featured'] === 1): ?>
                                    <span class="distintivo naranja" style="margin-left:5px">★</span>
                                <?php endif; ?>
                                <br>
                                <small style="color:var(--texto-tenue)"><?= e($a['slug']) ?></small>
                            </td>

                            <td class="compacta"><?= e($a['categoria'] ?? '—') ?></td>
                            <td class="compacta"><?= e($a['nivel'] ?? '—') ?></td>

                            <td class="compacta">
                                <span class="distintivo <?= $a['access_type'] === 'free' ? 'verde'
                                    : ($a['access_type'] === 'partial' ? 'naranja' : 'morado') ?>">
                                    <?php if ($a['access_type'] === 'partial'): ?>
                                        🎯 <?= (int) $a['free_stations'] ?> gratis
                                    <?php elseif ($a['access_type'] === 'free'): ?>
                                        🎁 Libre
                                    <?php else: ?>
                                        🔒 Premium
                                    <?php endif; ?>
                                </span>
                            </td>

                            <td class="compacta">
                                <?php if ((int) $a['estaciones'] > 0): ?>
                                    <?= (int) $a['estaciones'] ?>
                                <?php else: ?>
                                    <span class="distintivo gris">sin cargar</span>
                                <?php endif; ?>
                            </td>

                            <td class="compacta">
                                <span class="distintivo <?= $a['status'] === 'published' ? 'verde' : 'gris' ?>">
                                    <?= $a['status'] === 'published' ? 'Publicada'
                                        : ($a['status'] === 'draft' ? 'Borrador' : 'Archivada') ?>
                                </span>
                            </td>

                            <td>
                                <div class="acciones-celda">
                                    <a class="btn-mini" href="<?= e(url('admin/actividades/estaciones.php?id=' . (int) $a['id'])) ?>">
                                        Estaciones
                                    </a>
                                    <a class="btn-mini" href="<?= e(url('admin/actividades/editar.php?id=' . (int) $a['id'])) ?>">
                                        Editar
                                    </a>

                                    <form class="enlinea" method="post">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                                        <?php if ($a['status'] === 'published'): ?>
                                            <input type="hidden" name="accion" value="despublicar">
                                            <button class="btn-mini" type="submit">Despublicar</button>
                                        <?php else: ?>
                                            <input type="hidden" name="accion" value="publicar">
                                            <button class="btn-mini solido" type="submit">Publicar</button>
                                        <?php endif; ?>
                                    </form>

                                    <form class="enlinea" method="post"
                                          onsubmit="return confirm('¿Eliminar «<?= e(addslashes($a['title'])) ?>»?\n\nSe borrarán también sus estaciones y el progreso de los usuarios. Esta acción no se puede deshacer.')">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="id" value="<?= (int) $a['id'] ?>">
                                        <input type="hidden" name="accion" value="eliminar">
                                        <button class="btn-mini peligro" type="submit">Eliminar</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>

        <?php if ($totalPaginas > 1): ?>
            <div class="cuerpo">
                <nav class="paginacion" style="margin:0">
                    <?php if ($pagina > 1): ?>
                        <a href="<?= e(urlAdmin(['pagina' => $pagina - 1])) ?>">← Anterior</a>
                    <?php endif; ?>
                    <span class="actual"><?= $pagina ?> de <?= $totalPaginas ?></span>
                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="<?= e(urlAdmin(['pagina' => $pagina + 1])) ?>">Siguiente →</a>
                    <?php endif; ?>
                </nav>
            </div>
        <?php endif; ?>

    <?php else: ?>

        <div class="sin-datos">
            <span class="ico" aria-hidden="true">🎯</span>
            <h3>No hay actividades con esos filtros</h3>
            <p>Prueba a limpiarlos o crea una actividad nueva.</p>
        </div>

    <?php endif; ?>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
