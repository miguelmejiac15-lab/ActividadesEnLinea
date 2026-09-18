<?php
/**
 * admin/bloques/index.php — Administrar los bloques del catálogo
 *
 * Un bloque agrupa actividades afines dentro de una categoría, para que
 * una categoría grande no se presente como una lista interminable.
 *
 * Es una agrupación de presentación: no cambia quién puede jugar qué.
 * El acceso se sigue decidiendo actividad por actividad.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$errores  = [];
$editando = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $id     = (int) ($_POST['id'] ?? 0) ?: null;

    if ($accion === 'guardar') {
        $r = guardarBloque($_POST, $id);
        if ($r['ok']) {
            mensaje('ok', $id ? 'Bloque actualizado.' : 'Bloque creado.');
            redirigir('admin/bloques/');
        }
        $errores  = $r['errores'];
        $editando = $id;

    } elseif ($accion === 'eliminar' && $id) {
        $nombre  = traerValor('SELECT name FROM collections WHERE id = ?', [$id]);
        $sueltas = eliminarBloque($id);
        mensaje('ok', $sueltas > 0
            ? "Se eliminó «{$nombre}». {$sueltas} actividades quedaron sin bloque (no se borran)."
            : "Se eliminó «{$nombre}».");
        redirigir('admin/bloques/');

    } elseif ($accion === 'alternar' && $id) {
        ejecutar('UPDATE collections SET is_active = 1 - is_active WHERE id = ?', [$id]);
        redirigir('admin/bloques/');
    }
}

$bloques = traerTodo(
    'SELECT b.*, c.name AS categoria, c.icon AS categoria_icon, c.sort_order AS cat_orden,
            COUNT(a.id) AS total
       FROM collections b
  LEFT JOIN categories c ON c.id = b.category_id
  LEFT JOIN activities a ON a.collection_id = b.id
   GROUP BY b.id
   ORDER BY c.sort_order, b.sort_order, b.name'
);

$categorias = traerTodo('SELECT id, name, icon FROM categories ORDER BY sort_order');

// Actividades publicadas que aún no están en ningún bloque.
$sinBloque = traerTodo(
    'SELECT a.id, a.title, a.icon, c.name AS categoria
       FROM activities a
  LEFT JOIN categories c ON c.id = a.category_id
      WHERE a.collection_id IS NULL AND a.status = "published"
   ORDER BY c.sort_order, a.title'
);

$f = ['name' => '', 'slug' => '', 'description' => '', 'icon' => '',
      'category_id' => '', 'sort_order' => count($bloques) + 1, 'is_active' => 1];

if ($editando !== null) {
    foreach (array_keys($f) as $k) {
        $f[$k] = $_POST[$k] ?? '';
    }
    $f['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
} elseif (($ed = getEntero('editar')) > 0) {
    $fila = traerUno('SELECT * FROM collections WHERE id = ?', [$ed]);
    if ($fila) {
        $editando = (int) $fila['id'];
        $f = [
            'name' => $fila['name'], 'slug' => $fila['slug'],
            'description' => $fila['description'] ?? '', 'icon' => $fila['icon'] ?? '',
            'category_id' => $fila['category_id'], 'sort_order' => (int) $fila['sort_order'],
            'is_active' => (int) $fila['is_active'],
        ];
    }
}

$titulo    = 'Bloques';
$panelZona = 'bloques';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Bloques</h1>
        <p>Agrupan las actividades dentro de una categoría, para que una categoría grande no se vea como una lista interminable.</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;align-items:start">

    <div>
        <div class="caja">
            <div class="cabeza"><h2><?= count($bloques) ?> bloques</h2></div>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr><th>Bloque</th><th>Categoría</th><th>Actividades</th><th>Estado</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($bloques as $b): ?>
                            <tr>
                                <td>
                                    <b><?= e($b['icon']) ?> <?= e($b['name']) ?></b><br>
                                    <small style="color:var(--texto-tenue)"><?= e($b['description'] ?? $b['slug']) ?></small>
                                </td>
                                <td class="compacta">
                                    <?= e(($b['categoria_icon'] ?? '') . ' ' . ($b['categoria'] ?? '—')) ?>
                                </td>
                                <td class="compacta"><?= (int) $b['total'] ?></td>
                                <td class="compacta">
                                    <span class="distintivo <?= (int) $b['is_active'] === 1 ? 'verde' : 'gris' ?>">
                                        <?= (int) $b['is_active'] === 1 ? 'Activo' : 'Oculto' ?>
                                    </span>
                                </td>
                                <td>
                                    <div class="acciones-celda">
                                        <a class="btn-mini" target="_blank"
                                           href="<?= e(url('actividades/?bloque=' . urlencode($b['slug']))) ?>">Ver ↗</a>

                                        <form class="enlinea" method="post">
                                            <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="alternar">
                                            <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                            <button class="btn-mini" type="submit">
                                                <?= (int) $b['is_active'] === 1 ? 'Ocultar' : 'Activar' ?>
                                            </button>
                                        </form>

                                        <a class="btn-mini" href="<?= e(url('admin/bloques/?editar=' . (int) $b['id'])) ?>">Editar</a>

                                        <form class="enlinea" method="post"
                                              onsubmit="return confirm('¿Eliminar «<?= e(addslashes($b['name'])) ?>»?\n\n<?= (int) $b['total'] ?> actividades quedarán sin bloque (no se borran).')">
                                            <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="eliminar">
                                            <input type="hidden" name="id" value="<?= (int) $b['id'] ?>">
                                            <button class="btn-mini peligro" type="submit">✕</button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <?php if ($sinBloque): ?>
            <div class="caja">
                <div class="cabeza">
                    <h2><?= count($sinBloque) ?> actividades sin bloque</h2>
                </div>
                <div class="cuerpo">
                    <p style="font-size:.89rem;color:var(--texto-tenue);margin-bottom:14px">
                        No es un problema: en categorías pequeñas un bloque no aporta nada y solo
                        añadiría un título por cada dos tarjetas. Estas se muestran juntas al final
                        del catálogo, bajo «Otras actividades».
                    </p>
                    <div style="display:flex;flex-wrap:wrap;gap:7px">
                        <?php foreach ($sinBloque as $a): ?>
                            <a class="btn-mini"
                               href="<?= e(url('admin/actividades/editar.php?id=' . (int) $a['id'])) ?>">
                                <?= e($a['icon']) ?> <?= e($a['title']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <div class="caja">
        <div class="cabeza">
            <h2><?= $editando ? 'Editar bloque' : 'Nuevo bloque' ?></h2>
            <?php if ($editando): ?>
                <a class="btn-mini" href="<?= e(url('admin/bloques/')) ?>">Cancelar</a>
            <?php endif; ?>
        </div>
        <div class="cuerpo">

            <?php if ($errores): ?>
                <div class="aviso mal">
                    <ul style="margin:0 0 0 18px">
                        <?php foreach ($errores as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
                    </ul>
                </div>
            <?php endif; ?>

            <form method="post" class="formulario">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="guardar">
                <?php if ($editando): ?>
                    <input type="hidden" name="id" value="<?= (int) $editando ?>">
                <?php endif; ?>

                <div class="campo <?= isset($errores['name']) ? 'error' : '' ?>">
                    <label for="blo_name">Nombre *</label>
                    <input id="blo_name" name="name" type="text" required maxlength="120"
                           value="<?= e((string) $f['name']) ?>" placeholder="Bosque de Vocales">
                </div>

                <div class="campo <?= isset($errores['category_id']) ? 'error' : '' ?>">
                    <label for="blo_cat">Categoría *</label>
                    <select id="blo_cat" name="category_id" required>
                        <option value="">— Elige una —</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= (int) $c['id'] ?>"
                                <?= (int) $f['category_id'] === (int) $c['id'] ? 'selected' : '' ?>>
                                <?= e($c['icon'] . ' ' . $c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="blo_desc">Descripción</label>
                    <input id="blo_desc" name="description" type="text" maxlength="300"
                           value="<?= e((string) $f['description']) ?>"
                           placeholder="Las cinco vocales, una a una.">
                </div>

                <div class="pareja">
                    <div class="campo">
                        <label for="blo_icon">Ícono</label>
                        <input id="blo_icon" name="icon" type="text" maxlength="16"
                               value="<?= e((string) $f['icon']) ?>" placeholder="🌳">
                    </div>
                    <div class="campo">
                        <label for="blo_sort">Orden</label>
                        <input id="blo_sort" name="sort_order" type="number" min="0" max="999"
                               value="<?= (int) $f['sort_order'] ?>">
                    </div>
                </div>

                <div class="campo">
                    <label for="blo_slug">Slug</label>
                    <input id="blo_slug" name="slug" type="text" maxlength="60"
                           value="<?= e((string) $f['slug']) ?>" placeholder="se genera del nombre">
                </div>

                <div class="campo casilla">
                    <input id="blo_activo" name="is_active" type="checkbox" value="1"
                           <?= (int) $f['is_active'] === 1 ? 'checked' : '' ?>>
                    <label for="blo_activo">Activo · visible en el catálogo</label>
                </div>

                <div class="pie-formulario">
                    <button class="btn btn-principal btn-bloque" type="submit">
                        <?= $editando ? 'Guardar cambios' : 'Crear bloque' ?>
                    </button>
                </div>
            </form>

            <p class="ayuda" style="margin-top:14px">
                Para poner una actividad en un bloque, edítala y elige el bloque en su formulario.
            </p>

        </div>
    </div>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
