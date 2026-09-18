<?php
/**
 * admin/niveles/index.php — Administrar niveles y edades
 *
 * El nivel es un filtro DENTRO de cada categoría, no una categoría
 * aparte (ver docs/reorganizacion-home.md del proyecto anterior).
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
        $r = guardarNivel($_POST, $id);
        if ($r['ok']) {
            mensaje('ok', $id ? 'Nivel actualizado.' : 'Nivel creado.');
            redirigir('admin/niveles/');
        }
        $errores  = $r['errores'];
        $editando = $id;

    } elseif ($accion === 'eliminar' && $id) {
        $nombre  = traerValor('SELECT name FROM levels WHERE id = ?', [$id]);
        $sueltas = eliminarNivel($id);
        // Llaves obligatorias: sin ellas PHP absorbe el « » en el nombre de
        // la variable (los bytes altos son válidos en un identificador).
        mensaje('ok', $sueltas > 0
            ? "Se eliminó «{$nombre}». {$sueltas} actividades quedaron sin nivel."
            : "Se eliminó «{$nombre}».");
        redirigir('admin/niveles/');

    } elseif ($accion === 'alternar' && $id) {
        ejecutar('UPDATE levels SET is_active = 1 - is_active WHERE id = ?', [$id]);
        redirigir('admin/niveles/');
    }
}

$niveles = traerTodo(
    'SELECT l.*, COUNT(a.id) AS total
       FROM levels l
  LEFT JOIN activities a ON a.level_id = l.id
   GROUP BY l.id
   ORDER BY l.sort_order, l.name'
);

$f = ['name' => '', 'slug' => '', 'min_age' => '', 'max_age' => '',
      'sort_order' => count($niveles) + 1, 'is_active' => 1];

if ($editando !== null) {
    foreach (array_keys($f) as $k) {
        $f[$k] = $_POST[$k] ?? '';
    }
    $f['is_active'] = !empty($_POST['is_active']) ? 1 : 0;
} elseif (($ed = getEntero('editar')) > 0) {
    $fila = traerUno('SELECT * FROM levels WHERE id = ?', [$ed]);
    if ($fila) {
        $editando = (int) $fila['id'];
        $f = [
            'name' => $fila['name'], 'slug' => $fila['slug'],
            'min_age' => $fila['min_age'], 'max_age' => $fila['max_age'],
            'sort_order' => (int) $fila['sort_order'], 'is_active' => (int) $fila['is_active'],
        ];
    }
}

$titulo    = 'Niveles';
$panelZona = 'niveles';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Niveles y edades</h1>
        <p>La edad es un filtro dentro de cada categoría, no una categoría aparte.</p>
    </div>
</div>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;align-items:start">

    <div class="caja">
        <div class="cabeza"><h2><?= count($niveles) ?> niveles</h2></div>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead>
                    <tr><th>#</th><th>Nivel</th><th>Edad</th><th>Actividades</th><th>Estado</th><th></th></tr>
                </thead>
                <tbody>
                    <?php foreach ($niveles as $n): ?>
                        <tr>
                            <td class="compacta" style="color:var(--texto-tenue)"><?= (int) $n['sort_order'] ?></td>
                            <td>
                                <b><?= e($n['name']) ?></b><br>
                                <small style="color:var(--texto-tenue)"><?= e($n['slug']) ?></small>
                            </td>
                            <td class="compacta">
                                <?= $n['min_age'] && $n['max_age']
                                    ? (int) $n['min_age'] . '–' . (int) $n['max_age'] . ' años'
                                    : '—' ?>
                            </td>
                            <td class="compacta"><?= (int) $n['total'] ?></td>
                            <td class="compacta">
                                <span class="distintivo <?= (int) $n['is_active'] === 1 ? 'verde' : 'gris' ?>">
                                    <?= (int) $n['is_active'] === 1 ? 'Activo' : 'Oculto' ?>
                                </span>
                            </td>
                            <td>
                                <div class="acciones-celda">
                                    <form class="enlinea" method="post">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="alternar">
                                        <input type="hidden" name="id" value="<?= (int) $n['id'] ?>">
                                        <button class="btn-mini" type="submit">
                                            <?= (int) $n['is_active'] === 1 ? 'Ocultar' : 'Activar' ?>
                                        </button>
                                    </form>
                                    <a class="btn-mini" href="<?= e(url('admin/niveles/?editar=' . (int) $n['id'])) ?>">Editar</a>
                                    <form class="enlinea" method="post"
                                          onsubmit="return confirm('¿Eliminar «<?= e(addslashes($n['name'])) ?>»?\n\n<?= (int) $n['total'] ?> actividades quedarán sin nivel.')">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="eliminar">
                                        <input type="hidden" name="id" value="<?= (int) $n['id'] ?>">
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
            <h2><?= $editando ? 'Editar nivel' : 'Nuevo nivel' ?></h2>
            <?php if ($editando): ?>
                <a class="btn-mini" href="<?= e(url('admin/niveles/')) ?>">Cancelar</a>
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
                    <label for="niv_name">Nombre *</label>
                    <input id="niv_name" name="name" type="text" required maxlength="120"
                           value="<?= e((string) $f['name']) ?>" placeholder="Primero y segundo">
                </div>

                <div class="trio">
                    <div class="campo <?= isset($errores['min_age']) ? 'error' : '' ?>">
                        <label for="niv_min">Edad mínima</label>
                        <input id="niv_min" name="min_age" type="number" min="1" max="99"
                               value="<?= e((string) $f['min_age']) ?>">
                    </div>
                    <div class="campo">
                        <label for="niv_max">Edad máxima</label>
                        <input id="niv_max" name="max_age" type="number" min="1" max="99"
                               value="<?= e((string) $f['max_age']) ?>">
                    </div>
                    <div class="campo">
                        <label for="niv_sort">Orden</label>
                        <input id="niv_sort" name="sort_order" type="number" min="0" max="999"
                               value="<?= (int) $f['sort_order'] ?>">
                    </div>
                </div>

                <div class="campo">
                    <label for="niv_slug">Slug</label>
                    <input id="niv_slug" name="slug" type="text" maxlength="60"
                           value="<?= e((string) $f['slug']) ?>" placeholder="se genera del nombre">
                </div>

                <div class="campo casilla">
                    <input id="niv_activo" name="is_active" type="checkbox" value="1"
                           <?= (int) $f['is_active'] === 1 ? 'checked' : '' ?>>
                    <label for="niv_activo">Activo · visible en los filtros</label>
                </div>

                <div class="pie-formulario">
                    <button class="btn btn-principal btn-bloque" type="submit">
                        <?= $editando ? 'Guardar cambios' : 'Crear nivel' ?>
                    </button>
                </div>
            </form>

        </div>
    </div>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
