<?php
/**
 * admin/categorias/index.php — Administrar categorías
 *
 * Las categorías se editan aquí y salen de la base de datos en todo el
 * sitio: nunca están escritas dentro de un HTML. Cambiar un nombre o un
 * ícono aquí lo cambia en la home, el catálogo, los filtros y el pie.
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
        $r = guardarCategoria($_POST, $id);
        if ($r['ok']) {
            mensaje('ok', $id ? 'Categoría actualizada.' : 'Categoría creada.');
            redirigir('admin/categorias/');
        }
        $errores  = $r['errores'];
        $editando = $id;

    } elseif ($accion === 'eliminar' && $id) {
        $nombre = traerValor('SELECT name FROM categories WHERE id = ?', [$id]);
        $sueltas = eliminarCategoria($id);
        // Las llaves son necesarias: sin ellas PHP absorbe el « » dentro del
        // nombre de la variable, porque los bytes altos son válidos en un
        // identificador y «»» está en UTF-8 fuera del rango ASCII.
        mensaje('ok', $sueltas > 0
            ? "Se eliminó «{$nombre}». {$sueltas} actividades quedaron sin categoría: reasígnalas cuando puedas."
            : "Se eliminó «{$nombre}».");
        redirigir('admin/categorias/');

    } elseif ($accion === 'alternar' && $id) {
        ejecutar('UPDATE categories SET is_active = 1 - is_active WHERE id = ?', [$id]);
        redirigir('admin/categorias/');
    }
}

$categorias = traerTodo(
    'SELECT c.*, COUNT(a.id) AS total
       FROM categories c
  LEFT JOIN activities a ON a.category_id = c.id
   GROUP BY c.id
   ORDER BY c.sort_order, c.name'
);

// Valores del formulario
$f = ['name' => '', 'slug' => '', 'tagline' => '', 'icon' => '',
      'color' => '#29b6f6', 'sort_order' => count($categorias) + 1, 'is_active' => 1];

if ($editando !== null) {
    foreach (array_keys($f) as $k) {
        $f[$k] = $_POST[$k] ?? '';
    }
    $f['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
} elseif (($ed = getEntero('editar')) > 0) {
    $fila = traerUno('SELECT * FROM categories WHERE id = ?', [$ed]);
    if ($fila) {
        $editando = (int) $fila['id'];
        $f = [
            'name' => $fila['name'], 'slug' => $fila['slug'],
            'tagline' => $fila['tagline'] ?? '', 'icon' => $fila['icon'] ?? '',
            'color' => $fila['color'] ?? '#29b6f6',
            'sort_order' => (int) $fila['sort_order'], 'is_active' => (int) $fila['is_active'],
        ];
    }
}

$titulo    = 'Categorías';
$panelZona = 'categorias';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Categorías</h1>
        <p>Se administran aquí y se leen desde la base de datos en todo el sitio.</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;align-items:start">

    <div class="caja">
        <div class="cabeza"><h2><?= count($categorias) ?> categorías</h2></div>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead>
                    <tr><th>#</th><th>Categoría</th><th>Actividades</th><th>Estado</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($categorias as $c): ?>
                        <tr>
                            <td class="compacta" style="color:var(--texto-tenue)"><?= (int) $c['sort_order'] ?></td>
                            <td>
                                <b>
                                    <span style="display:inline-block;width:10px;height:10px;border-radius:50%;background:<?= e($c['color'] ?: '#ccc') ?>;margin-right:6px"></span>
                                    <?= e($c['icon']) ?> <?= e($c['name']) ?>
                                </b>
                                <br><small style="color:var(--texto-tenue)"><?= e($c['tagline'] ?? $c['slug']) ?></small>
                            </td>
                            <td class="compacta"><?= (int) $c['total'] ?></td>
                            <td class="compacta">
                                <span class="distintivo <?= (int) $c['is_active'] === 1 ? 'verde' : 'gris' ?>">
                                    <?= (int) $c['is_active'] === 1 ? 'Activa' : 'Oculta' ?>
                                </span>
                            </td>
                            <td>
                                <div class="acciones-celda">
                                    <form class="enlinea" method="post">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="alternar">
                                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
                                        <button class="btn-mini" type="submit">
                                            <?= (int) $c['is_active'] === 1 ? 'Ocultar' : 'Activar' ?>
                                        </button>
                                    </form>

                                    <a class="btn-mini" href="<?= e(url('admin/categorias/?editar=' . (int) $c['id'])) ?>">Editar</a>

                                    <form class="enlinea" method="post"
                                          onsubmit="return confirm('¿Eliminar «<?= e(addslashes($c['name'])) ?>»?\n\n<?= (int) $c['total'] ?> actividades quedarán sin categoría (no se borran).')">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $c['id'] ?>">
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

    <div class="caja">
        <div class="cabeza">
            <h2><?= $editando ? 'Editar categoría' : 'Nueva categoría' ?></h2>
            <?php if ($editando): ?>
                <a class="btn-mini" href="<?= e(url('admin/categorias/')) ?>">Cancelar</a>
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
                    <label for="cat_name">Nombre *</label>
                    <input id="cat_name" name="name" type="text" required maxlength="120"
                           value="<?= e((string) $f['name']) ?>">
                </div>

                <div class="campo">
                    <label for="cat_tagline">Bajada</label>
                    <input id="cat_tagline" name="tagline" type="text" maxlength="160"
                           value="<?= e((string) $f['tagline']) ?>" placeholder="Números y lógica">
                    <p class="ayuda">Se muestra bajo el nombre en la home y al filtrar.</p>
                </div>

                <div class="trio">
                    <div class="campo">
                        <label for="cat_icon">Ícono</label>
                        <input id="cat_icon" name="icon" type="text" maxlength="16"
                               value="<?= e((string) $f['icon']) ?>" placeholder="🔢">
                    </div>
                    <div class="campo <?= isset($errores['color']) ? 'error' : '' ?>">
                        <label for="cat_color">Color</label>
                        <input id="cat_color" name="color" type="text" maxlength="7"
                               value="<?= e((string) $f['color']) ?>" placeholder="#29b6f6">
                    </div>
                    <div class="campo">
                        <label for="cat_sort">Orden</label>
                        <input id="cat_sort" name="sort_order" type="number" min="0" max="999"
                               value="<?= (int) $f['sort_order'] ?>">
                    </div>
                </div>

                <div class="campo">
                    <label for="cat_slug">Slug</label>
                    <input id="cat_slug" name="slug" type="text" maxlength="60"
                           value="<?= e((string) $f['slug']) ?>" placeholder="se genera del nombre">
                    <p class="ayuda">Se usa en los enlaces de filtro del catálogo.</p>
                </div>

                <div class="campo casilla">
                    <input id="cat_activa" name="is_active" type="checkbox" value="1"
                           <?= (int) $f['is_active'] === 1 ? 'checked' : '' ?>>
                    <label for="cat_activa">Activa · visible en el sitio</label>
                </div>

                <div class="pie-formulario">
                    <button class="btn btn-principal btn-bloque" type="submit">
                        <?= $editando ? 'Guardar cambios' : 'Crear categoría' ?>
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
