<?php
/**
 * admin/actividades/editar.php — Crear y editar una actividad
 *
 * Un mismo formulario sirve para las dos cosas: sin `id` crea, con `id`
 * edita. Así no hay dos formularios que se desincronicen cuando se
 * agregue un campo nuevo.
 *
 * Aquí es donde se define el modelo freemium de cada actividad.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$id  = getEntero('id') ?: null;
$esNueva = $id === null;

$actividad = null;
if (!$esNueva) {
    $actividad = traerUno('SELECT * FROM activities WHERE id = ?', [$id]);
    if (!$actividad) {
        mensaje('error', 'Esa actividad no existe.');
        redirigir('admin/actividades/');
    }
}

$errores = [];

// Valores del formulario: los guardados, o los de un envío fallido.
$v = [
    'title'            => $actividad['title']            ?? '',
    'slug'             => $actividad['slug']             ?? '',
    'description'      => $actividad['description']      ?? '',
    'objective'        => $actividad['objective']        ?? '',
    'instructions'     => $actividad['instructions']     ?? '',
    'category_id'      => $actividad['category_id']      ?? '',
    'collection_id'    => $actividad['collection_id']    ?? '',
    'level_id'         => $actividad['level_id']         ?? '',
    'icon'             => $actividad['icon']             ?? '',
    'activity_type'    => $actividad['activity_type']    ?? '',
    'duration_minutes' => $actividad['duration_minutes'] ?? '',
    'engine'           => $actividad['engine']           ?? 'legacy_html',
    'legacy_file'      => $actividad['legacy_file']      ?? '',
    'access_type'      => $actividad['access_type']      ?? ACCESO_PARCIAL,
    'free_stations'    => $actividad['free_stations']    ?? 2,
    'status'           => $actividad['status']           ?? 'draft',
    'is_featured'      => (int) ($actividad['is_featured'] ?? 0),
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    foreach (array_keys($v) as $campo) {
        if ($campo === 'is_featured') {
            $v[$campo] = !empty($_POST['is_featured']) ? 1 : 0;
        } else {
            $v[$campo] = post($campo);
        }
    }

    $r = guardarActividad($v, $id);

    if ($r['ok']) {
        mensaje('ok', $esNueva
            ? 'Actividad creada. Ahora puedes cargar sus estaciones.'
            : 'Cambios guardados.');

        // Tras crear, se lleva directo a las estaciones: es el paso que
        // hace falta para que el modelo por estaciones funcione.
        redirigir($esNueva
            ? 'admin/actividades/estaciones.php?id=' . (int) $r['id']
            : 'admin/actividades/editar.php?id=' . (int) $r['id']);
    }

    $errores = $r['errores'];
}

$categorias = traerTodo('SELECT id, name, icon FROM categories ORDER BY sort_order');
$niveles    = traerTodo('SELECT id, name, min_age, max_age FROM levels ORDER BY sort_order');

// Los bloques se listan agrupados por categoría, para que se vea de un
// vistazo cuál corresponde a la categoría elegida arriba.
$bloquesDisponibles = traerTodo(
    'SELECT b.id, b.name, b.icon, c.name AS categoria, c.icon AS categoria_icon
       FROM collections b
  LEFT JOIN categories c ON c.id = b.category_id
      WHERE b.is_active = 1
   ORDER BY c.sort_order, b.sort_order, b.name'
);

$totalEstaciones = $esNueva ? 0 : (int) traerValor(
    'SELECT COUNT(*) FROM activity_stations WHERE activity_id = ?', [$id]
);

$titulo    = $esNueva ? 'Nueva actividad' : 'Editar actividad';
$panelZona = 'actividades';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1><?= $esNueva ? 'Nueva actividad' : 'Editar actividad' ?></h1>
        <p>
            <?= $esNueva
                ? 'Agregar una actividad es llenar este formulario. No hace falta tocar código.'
                : e($actividad['title']) ?>
        </p>
    </div>
    <div class="acciones">
        <?php if (!$esNueva): ?>
            <a class="btn-mini" href="<?= e(url('admin/actividades/estaciones.php?id=' . (int) $id)) ?>">
                Estaciones (<?= $totalEstaciones ?>)
            </a>
            <?php if ($actividad['status'] === 'published'): ?>
                <a class="btn-mini" target="_blank"
                   href="<?= e(url('actividades/ver.php?a=' . urlencode($actividad['slug']))) ?>">Ver en el sitio ↗</a>
            <?php endif; ?>
        <?php endif; ?>
        <a class="btn-mini" href="<?= e(url('admin/actividades/')) ?>">← Volver</a>
    </div>
</div>

<?php if ($errores): ?>
    <div class="aviso mal">
        <b>Revisa estos campos:</b>
        <ul style="margin:6px 0 0 18px">
            <?php foreach ($errores as $er): ?><li><?= e($er) ?></li><?php endforeach; ?>
        </ul>
    </div>
<?php endif; ?>

<form method="post">
    <?= campoCsrf() ?>

    <!-- ── Información ─────────────────────────────────────────────── -->
    <div class="caja">
        <div class="cabeza"><h2>Información</h2></div>
        <div class="cuerpo formulario">

            <div class="campo <?= isset($errores['title']) ? 'error' : '' ?>">
                <label for="title">Título *</label>
                <input id="title" name="title" type="text" required maxlength="160"
                       value="<?= e((string) $v['title']) ?>">
                <?php if (isset($errores['title'])): ?>
                    <p class="error-texto"><?= e($errores['title']) ?></p>
                <?php endif; ?>
            </div>

            <div class="pareja">
                <div class="campo">
                    <label for="slug">Dirección (slug)</label>
                    <input id="slug" name="slug" type="text" maxlength="140"
                           value="<?= e((string) $v['slug']) ?>" placeholder="se genera del título">
                    <p class="ayuda">Se usa en la URL. Si lo dejas vacío se genera solo, y si ya existe se le añade un número.</p>
                </div>

                <div class="campo">
                    <label for="icon">Ícono</label>
                    <input id="icon" name="icon" type="text" maxlength="16"
                           value="<?= e((string) $v['icon']) ?>" placeholder="🎯">
                    <p class="ayuda">Un emoji. Se muestra en la tarjeta del catálogo.</p>
                </div>
            </div>

            <div class="campo">
                <label for="description">Descripción</label>
                <textarea id="description" name="description" maxlength="400"
                          placeholder="Una o dos frases que inviten a entrar."><?= e((string) $v['description']) ?></textarea>
                <p class="ayuda">Aparece en la tarjeta del catálogo y en la ficha.</p>
            </div>

            <div class="campo">
                <label for="objective">Objetivo pedagógico</label>
                <textarea id="objective" name="objective" maxlength="400"><?= e((string) $v['objective']) ?></textarea>
            </div>

            <div class="campo">
                <label for="instructions">Cómo se juega</label>
                <textarea id="instructions" name="instructions"><?= e((string) $v['instructions']) ?></textarea>
            </div>

        </div>
    </div>

    <!-- ── Clasificación ───────────────────────────────────────────── -->
    <div class="caja">
        <div class="cabeza"><h2>Clasificación</h2></div>
        <div class="cuerpo formulario">

            <div class="pareja">
                <div class="campo <?= isset($errores['category_id']) ? 'error' : '' ?>">
                    <label for="category_id">Categoría</label>
                    <select id="category_id" name="category_id">
                        <option value="">— Sin categoría —</option>
                        <?php foreach ($categorias as $c): ?>
                            <option value="<?= (int) $c['id'] ?>"
                                <?= (int) $v['category_id'] === (int) $c['id'] ? 'selected' : '' ?>>
                                <?= e($c['icon'] . ' ' . $c['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo <?= isset($errores['level_id']) ? 'error' : '' ?>">
                    <label for="level_id">Nivel o edad</label>
                    <select id="level_id" name="level_id">
                        <option value="">— Sin nivel —</option>
                        <?php foreach ($niveles as $n): ?>
                            <option value="<?= (int) $n['id'] ?>"
                                <?= (int) $v['level_id'] === (int) $n['id'] ? 'selected' : '' ?>>
                                <?= e($n['name']) ?>
                                <?php if ($n['min_age'] && $n['max_age']): ?>
                                    (<?= (int) $n['min_age'] ?>–<?= (int) $n['max_age'] ?> años)
                                <?php endif; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div class="campo <?= isset($errores['collection_id']) ? 'error' : '' ?>">
                <label for="collection_id">Bloque</label>
                <select id="collection_id" name="collection_id">
                    <option value="">— Sin bloque —</option>
                    <?php
                    $catAgrupada = '';
                    foreach ($bloquesDisponibles as $b):
                        if ($catAgrupada !== $b['categoria']):
                            if ($catAgrupada !== '') { echo '</optgroup>'; }
                            $catAgrupada = $b['categoria'];
                            echo '<optgroup label="' . e($b['categoria_icon'] . ' ' . $b['categoria']) . '">';
                        endif; ?>
                        <option value="<?= (int) $b['id'] ?>"
                            <?= (int) $v['collection_id'] === (int) $b['id'] ? 'selected' : '' ?>>
                            <?= e(($b['icon'] ?? '') . ' ' . $b['name']) ?>
                        </option>
                    <?php endforeach;
                    if ($catAgrupada !== '') { echo '</optgroup>'; } ?>
                </select>
                <?php if (isset($errores['collection_id'])): ?>
                    <p class="error-texto"><?= e($errores['collection_id']) ?></p>
                <?php endif; ?>
                <p class="ayuda">
                    Agrupa esta actividad dentro de su categoría en el catálogo. El bloque debe
                    ser de la misma categoría elegida arriba. Dejarlo vacío es correcto: en
                    categorías pequeñas los bloques no aportan nada.
                </p>
            </div>

            <div class="pareja">
                <div class="campo">
                    <label for="activity_type">Tipo</label>
                    <select id="activity_type" name="activity_type">
                        <?php
                        $tipos = ['' => '— Sin tipo —', 'juego' => 'Juego', 'reto' => 'Reto',
                                  'practica' => 'Práctica', 'cuento' => 'Cuento', 'paquete' => 'Paquete'];
                        foreach ($tipos as $clave => $etiqueta): ?>
                            <option value="<?= e($clave) ?>" <?= (string) $v['activity_type'] === $clave ? 'selected' : '' ?>>
                                <?= e($etiqueta) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="duration_minutes">Duración aproximada (minutos)</label>
                    <input id="duration_minutes" name="duration_minutes" type="number" min="1" max="600"
                           value="<?= e((string) $v['duration_minutes']) ?>">
                </div>
            </div>

        </div>
    </div>

    <!-- ── Acceso: el modelo freemium ──────────────────────────────── -->
    <div class="caja">
        <div class="cabeza">
            <h2>Acceso</h2>
            <span class="distintivo azul">Modo global: <?= e(modoCatalogo()) ?></span>
        </div>
        <div class="cuerpo formulario">

            <div class="campo">
                <div class="opciones-acceso">

                    <label class="opcion-acceso">
                        <input type="radio" name="access_type" value="<?= ACCESO_LIBRE ?>"
                               <?= $v['access_type'] === ACCESO_LIBRE ? 'checked' : '' ?>>
                        <span>
                            <b>🎁 Libre por completo</b>
                            <span>Cualquiera puede jugarla entera, con o sin cuenta.</span>
                        </span>
                    </label>

                    <label class="opcion-acceso">
                        <input type="radio" name="access_type" value="<?= ACCESO_PARCIAL ?>"
                               <?= $v['access_type'] === ACCESO_PARCIAL ? 'checked' : '' ?>>
                        <span>
                            <b>🎯 Parcial · las primeras estaciones son gratis</b>
                            <span>
                                El usuario Free entra y juega el comienzo; el resto lo abre la
                                Biblioteca Completa. Es el modelo por defecto de la plataforma.
                            </span>
                        </span>
                    </label>

                    <label class="opcion-acceso">
                        <input type="radio" name="access_type" value="<?= ACCESO_PREMIUM ?>"
                               <?= $v['access_type'] === ACCESO_PREMIUM ? 'checked' : '' ?>>
                        <span>
                            <b>🔒 Solo Biblioteca Completa</b>
                            <span>Requiere suscripción vigente para entrar siquiera.</span>
                        </span>
                    </label>

                </div>
            </div>

            <div class="campo <?= isset($errores['free_stations']) ? 'error' : '' ?>">
                <label for="free_stations">Estaciones gratuitas</label>
                <input id="free_stations" name="free_stations" type="number" min="0" max="255"
                       value="<?= (int) $v['free_stations'] ?>" style="max-width:160px">
                <?php if (isset($errores['free_stations'])): ?>
                    <p class="error-texto"><?= e($errores['free_stations']) ?></p>
                <?php endif; ?>
                <p class="ayuda">
                    Cuántas estaciones iniciales puede jugar un usuario Free cuando el acceso es
                    <b>parcial</b>. Como referencia, el 30% del catálogo equivale a unas
                    5 de 15 estaciones, o 3 de 11.
                    <?php if ($totalEstaciones > 0): ?>
                        <br>
                        Esta actividad tiene <b><?= $totalEstaciones ?> estaciones</b> cargadas:
                        con el valor actual se liberarían
                        <b><?= min((int) $v['free_stations'], $totalEstaciones) ?></b>
                        (<?= (int) round(min((int) $v['free_stations'], $totalEstaciones) * 100 / $totalEstaciones) ?>%).
                    <?php endif; ?>
                </p>
            </div>

        </div>
    </div>

    <!-- ── Motor y publicación ─────────────────────────────────────── -->
    <div class="caja">
        <div class="cabeza"><h2>Motor y publicación</h2></div>
        <div class="cuerpo formulario">

            <div class="pareja">
                <div class="campo">
                    <label for="engine">Motor</label>
                    <select id="engine" name="engine">
                        <?php
                        $motores = [
                            'legacy_html' => 'Archivo HTML original (sin portar)',
                            'estaciones'  => 'Motor por estaciones',
                        ];
                        foreach ($motores as $clave => $etiqueta): ?>
                            <option value="<?= e($clave) ?>" <?= (string) $v['engine'] === $clave ? 'selected' : '' ?>>
                                <?= e($etiqueta) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="ayuda">
                        El motor por estaciones es el que permite liberar solo una parte de la
                        actividad. Mientras una actividad siga como HTML original, el corte
                        freemium no puede aplicarse dentro de ella.
                    </p>
                </div>

                <div class="campo">
                    <label for="legacy_file">Archivo original</label>
                    <input id="legacy_file" name="legacy_file" type="text" maxlength="160"
                           value="<?= e((string) $v['legacy_file']) ?>" placeholder="Letra_M.html">
                    <p class="ayuda">Solo mientras la actividad no esté portada.</p>
                </div>
            </div>

            <hr class="separador">

            <div class="campo">
                <label for="status">Estado</label>
                <select id="status" name="status" style="max-width:260px">
                    <option value="draft"     <?= $v['status'] === 'draft'     ? 'selected' : '' ?>>Borrador · no aparece en el catálogo</option>
                    <option value="published" <?= $v['status'] === 'published' ? 'selected' : '' ?>>Publicada · visible para todos</option>
                    <option value="archived"  <?= $v['status'] === 'archived'  ? 'selected' : '' ?>>Archivada · retirada del catálogo</option>
                </select>
                <p class="ayuda">
                    Al publicar por primera vez se guarda la fecha automáticamente, y la actividad
                    entra en «Nuevas actividades». Republicar algo antiguo no la vuelve a marcar como nueva.
                </p>
            </div>

            <div class="campo casilla">
                <input id="is_featured" name="is_featured" type="checkbox" value="1"
                       <?= (int) $v['is_featured'] === 1 ? 'checked' : '' ?>>
                <label for="is_featured">
                    Destacada
                    <span class="ayuda" style="margin:0">Aparece en «Actividades destacadas» de la página de inicio.</span>
                </label>
            </div>

        </div>
    </div>

    <div class="pie-formulario">
        <button class="btn btn-principal" type="submit">
            <?= $esNueva ? 'Crear actividad' : 'Guardar cambios' ?>
        </button>
        <a class="btn btn-secundario" href="<?= e(url('admin/actividades/')) ?>">Cancelar</a>
    </div>

</form>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
