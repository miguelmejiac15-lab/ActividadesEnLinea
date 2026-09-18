<?php
/**
 * admin/actividades/estaciones.php — Estaciones de una actividad
 *
 * Aquí se define de qué está hecha una actividad y, sobre todo, dónde
 * cae el corte entre lo gratuito y lo premium. La línea punteada que
 * aparece en la lista muestra ese corte tal como lo verá un usuario Free.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$actividadId = getEntero('id');
$actividad = $actividadId > 0
    ? traerUno('SELECT * FROM activities WHERE id = ?', [$actividadId])
    : null;

if (!$actividad) {
    mensaje('error', 'Esa actividad no existe.');
    redirigir('admin/actividades/');
}

$errores  = [];
$editando = null;

// ── Acciones ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion      = post('accion');
    $estacionId  = (int) ($_POST['estacion_id'] ?? 0) ?: null;

    switch ($accion) {

        case 'guardar':
            $r = guardarEstacion($actividadId, $_POST, $estacionId);
            if ($r['ok']) {
                mensaje('ok', $estacionId ? 'Estación actualizada.' : 'Estación agregada.');
                redirigir('admin/actividades/estaciones.php?id=' . $actividadId);
            }
            $errores  = $r['errores'];
            $editando = $estacionId;
            break;

        case 'eliminar':
            if ($estacionId && eliminarEstacion($estacionId)) {
                renumerarEstaciones($actividadId);
                mensaje('ok', 'Estación eliminada y posiciones renumeradas.');
            }
            redirigir('admin/actividades/estaciones.php?id=' . $actividadId);
            break;

        case 'mover':
            if ($estacionId) {
                moverEstacion($estacionId, post('direccion') === 'arriba' ? 'arriba' : 'abajo');
            }
            redirigir('admin/actividades/estaciones.php?id=' . $actividadId);
            break;

        case 'alternar_libre':
            if ($estacionId) {
                ejecutar('UPDATE activity_stations SET is_free = 1 - is_free WHERE id = ? AND activity_id = ?',
                         [$estacionId, $actividadId]);
                mensaje('ok', 'Cambió la excepción de acceso de esa estación.');
            }
            redirigir('admin/actividades/estaciones.php?id=' . $actividadId);
            break;

        case 'renumerar':
            renumerarEstaciones($actividadId);
            mensaje('ok', 'Estaciones renumeradas de 1 en adelante.');
            redirigir('admin/actividades/estaciones.php?id=' . $actividadId);
            break;
    }
}

$estaciones = estacionesDe($actividadId);
$total      = count($estaciones);
$libres     = (int) $actividad['free_stations'];

// Se recalcula el estado de acceso con la lógica real, la misma que usa
// el sitio público: lo que se ve aquí es lo que verá el visitante.
$vistaPrevia = [];
foreach ($estaciones as $est) {
    // Se evalúa como lo haría un usuario Free, sin importar que quien
    // mira ahora sea administrador.
    $libreAqui = ((int) $est['is_free'] === 1)
        || $actividad['access_type'] === ACCESO_LIBRE
        || ($actividad['access_type'] === ACCESO_PARCIAL && (int) $est['position'] <= $libres);
    $vistaPrevia[(int) $est['id']] = $libreAqui;
}
$totalLibres = count(array_filter($vistaPrevia));

// Datos de la estación que se está editando.
$f = ['title' => '', 'description' => '', 'icon' => '', 'game_type' => '',
      'config' => '', 'position' => '', 'is_free' => 0];

if ($editando !== null) {
    foreach (array_keys($f) as $k) {
        $f[$k] = $_POST[$k] ?? '';
    }
    $f['is_free'] = !empty($_POST['is_free']) ? 1 : 0;
} elseif (($ed = getEntero('editar')) > 0) {
    $fila = traerUno('SELECT * FROM activity_stations WHERE id = ? AND activity_id = ?', [$ed, $actividadId]);
    if ($fila) {
        $editando = (int) $fila['id'];
        $f = [
            'title'       => $fila['title'],
            'description' => $fila['description'] ?? '',
            'icon'        => $fila['icon'] ?? '',
            'game_type'   => $fila['game_type'],
            'config'      => $fila['config'] ?? '',
            'position'    => $fila['position'],
            'is_free'     => (int) $fila['is_free'],
        ];
    }
}

$titulo    = 'Estaciones · ' . $actividad['title'];
$panelZona = 'actividades';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1><?= e($actividad['icon']) ?> <?= e($actividad['title']) ?></h1>
        <p>
            <?= $total ?> <?= $total === 1 ? 'estación' : 'estaciones' ?>
            <?php if ($total > 0): ?>
                · <b><?= $totalLibres ?></b> gratuitas
                (<?= (int) round($totalLibres * 100 / $total) ?>% de la actividad)
            <?php endif; ?>
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/actividades/editar.php?id=' . $actividadId)) ?>">Editar actividad</a>
        <a class="btn-mini" href="<?= e(url('admin/actividades/')) ?>">← Volver</a>
    </div>
</div>

<?php if ($actividad['access_type'] === ACCESO_PREMIUM): ?>
    <div class="aviso info">
        Esta actividad está marcada como <b>🔒 Solo Biblioteca Completa</b>: ninguna de sus
        estaciones es gratuita, sin importar lo que se configure aquí.
        Cámbiala a <b>parcial</b> si quieres liberar el comienzo.
    </div>
<?php elseif ($actividad['access_type'] === ACCESO_LIBRE): ?>
    <div class="aviso info">
        Esta actividad está marcada como <b>🎁 Libre por completo</b>: todas sus estaciones
        son gratuitas.
    </div>
<?php endif; ?>

<div style="display:grid;grid-template-columns:1.5fr 1fr;gap:20px;align-items:start">

    <!-- ── Lista de estaciones ─────────────────────────────────────── -->
    <div class="caja">
        <div class="cabeza">
            <h2>Recorrido</h2>
            <?php if ($total > 1): ?>
                <form class="enlinea" method="post">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="renumerar">
                    <button class="btn-mini" type="submit">Renumerar 1…<?= $total ?></button>
                </form>
            <?php endif; ?>
        </div>
        <div class="cuerpo">

            <?php if ($estaciones): ?>

                <?php
                $corteDibujado = false;
                foreach ($estaciones as $i => $est):
                    $libre = $vistaPrevia[(int) $est['id']];

                    // La línea del corte se dibuja justo antes de la
                    // primera estación bloqueada.
                    if (!$libre && !$corteDibujado && $actividad['access_type'] === ACCESO_PARCIAL):
                        $corteDibujado = true; ?>
                        <div class="corte-freemium">🔒 A partir de aquí, Biblioteca Completa</div>
                    <?php endif; ?>

                    <div class="fila-estacion <?= $libre ? 'libre' : 'cerrada' ?>">
                        <span class="puesto"><?= (int) $est['position'] ?></span>

                        <span class="info">
                            <b><?= e($est['icon'] ?? '') ?> <?= e($est['title']) ?></b>
                            <small>
                                <?= e($est['game_type']) ?>
                                <?php if ((int) $est['is_free'] === 1): ?>
                                    · <span style="color:var(--verde);font-weight:700">excepción: siempre libre</span>
                                <?php endif; ?>
                                <?php if (empty($est['config'])): ?>
                                    · <span style="color:var(--naranja);font-weight:700">sin contenido</span>
                                <?php endif; ?>
                            </small>
                        </span>

                        <span class="mandos">
                            <form class="enlinea" method="post">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="mover">
                                <input type="hidden" name="estacion_id" value="<?= (int) $est['id'] ?>">
                                <input type="hidden" name="direccion" value="arriba">
                                <button class="btn-mini" type="submit" title="Subir"
                                    <?= $i === 0 ? 'disabled' : '' ?>>↑</button>
                            </form>

                            <form class="enlinea" method="post">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="mover">
                                <input type="hidden" name="estacion_id" value="<?= (int) $est['id'] ?>">
                                <input type="hidden" name="direccion" value="abajo">
                                <button class="btn-mini" type="submit" title="Bajar"
                                    <?= $i === $total - 1 ? 'disabled' : '' ?>>↓</button>
                            </form>

                            <form class="enlinea" method="post">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="alternar_libre">
                                <input type="hidden" name="estacion_id" value="<?= (int) $est['id'] ?>">
                                <button class="btn-mini" type="submit"
                                        title="Forzar esta estación como siempre gratuita">
                                    <?= (int) $est['is_free'] === 1 ? '🔓' : '🔒' ?>
                                </button>
                            </form>

                            <a class="btn-mini"
                               href="<?= e(url('admin/actividades/estaciones.php?id=' . $actividadId . '&editar=' . (int) $est['id'])) ?>">
                                Editar
                            </a>

                            <form class="enlinea" method="post"
                                  onsubmit="return confirm('¿Eliminar la estación «<?= e(addslashes($est['title'])) ?>»?')">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="eliminar">
                                <input type="hidden" name="estacion_id" value="<?= (int) $est['id'] ?>">
                                <button class="btn-mini peligro" type="submit">✕</button>
                            </form>
                        </span>
                    </div>

                <?php endforeach; ?>

            <?php else: ?>

                <div class="sin-datos">
                    <span class="ico" aria-hidden="true">🗺️</span>
                    <h3>Esta actividad todavía no tiene estaciones</h3>
                    <p>
                        Agrégalas con el formulario de al lado. Mientras no las tenga, el corte
                        entre lo gratuito y lo premium no puede aplicarse dentro de la actividad.
                    </p>
                </div>

            <?php endif; ?>

        </div>
    </div>

    <!-- ── Formulario ──────────────────────────────────────────────── -->
    <div class="caja">
        <div class="cabeza">
            <h2><?= $editando ? 'Editar estación' : 'Agregar estación' ?></h2>
            <?php if ($editando): ?>
                <a class="btn-mini" href="<?= e(url('admin/actividades/estaciones.php?id=' . $actividadId)) ?>">
                    Cancelar
                </a>
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
                    <input type="hidden" name="estacion_id" value="<?= (int) $editando ?>">
                <?php endif; ?>

                <div class="campo <?= isset($errores['title']) ? 'error' : '' ?>">
                    <label for="est_title">Título *</label>
                    <input id="est_title" name="title" type="text" required maxlength="160"
                           value="<?= e((string) $f['title']) ?>" placeholder="Puzle de sílabas I">
                </div>

                <div class="pareja">
                    <div class="campo <?= isset($errores['game_type']) ? 'error' : '' ?>">
                        <label for="est_game_type">Tipo de minijuego *</label>
                        <input id="est_game_type" name="game_type" type="text" required maxlength="40"
                               list="tipos-juego" value="<?= e((string) $f['game_type']) ?>"
                               placeholder="puzle_silabas">
                        <datalist id="tipos-juego">
                            <option value="sonido_letra">
                            <option value="seleccion_imagenes">
                            <option value="puzle_silabas">
                            <option value="armar_palabras">
                            <option value="teclado">
                            <option value="ortografia">
                            <option value="ordenar_historia">
                            <option value="memoria">
                            <option value="sopa_letras">
                            <option value="cuento">
                            <option value="desafio_final">
                        </datalist>
                    </div>

                    <div class="campo">
                        <label for="est_icon">Ícono</label>
                        <input id="est_icon" name="icon" type="text" maxlength="16"
                               value="<?= e((string) $f['icon']) ?>" placeholder="🧩">
                    </div>
                </div>

                <div class="campo">
                    <label for="est_description">Descripción</label>
                    <input id="est_description" name="description" type="text" maxlength="300"
                           value="<?= e((string) $f['description']) ?>"
                           placeholder="Ordena las sílabas para formar palabras">
                </div>

                <div class="pareja">
                    <div class="campo <?= isset($errores['position']) ? 'error' : '' ?>">
                        <label for="est_position">Posición</label>
                        <input id="est_position" name="position" type="number" min="1" max="999"
                               value="<?= e((string) $f['position']) ?>" placeholder="al final">
                        <?php if (isset($errores['position'])): ?>
                            <p class="error-texto"><?= e($errores['position']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div class="campo casilla" style="align-items:center">
                        <input id="est_is_free" name="is_free" type="checkbox" value="1"
                               <?= (int) $f['is_free'] === 1 ? 'checked' : '' ?>>
                        <label for="est_is_free">Siempre gratuita</label>
                    </div>
                </div>

                <div class="campo <?= isset($errores['config']) ? 'error' : '' ?>">
                    <label for="est_config">Contenido del minijuego (JSON)</label>
                    <textarea id="est_config" name="config" class="codigo"
                              placeholder='{"palabras": ["mamá", "mesa", "mano"]}'><?= e((string) $f['config']) ?></textarea>
                    <?php if (isset($errores['config'])): ?>
                        <p class="error-texto"><?= e($errores['config']) ?></p>
                    <?php endif; ?>
                    <p class="ayuda">
                        <b>Este campo es contenido protegido.</b> Si la estación está bloqueada
                        para el visitante, el servidor no lo envía al navegador. Ahí van las
                        palabras, las respuestas y todo lo que no debe verse sin suscripción.
                    </p>
                </div>

                <div class="pie-formulario">
                    <button class="btn btn-principal btn-bloque" type="submit">
                        <?= $editando ? 'Guardar estación' : 'Agregar estación' ?>
                    </button>
                </div>

            </form>

        </div>
    </div>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
