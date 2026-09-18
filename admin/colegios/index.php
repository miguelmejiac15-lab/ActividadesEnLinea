<?php
/**
 * admin/colegios/index.php — Los colegios
 *
 * Solo administración de la plataforma. Un colegio concede licencia a
 * todos los suyos sin cobrarles uno a uno, así que crearlo es regalar
 * acceso: no es algo que deba poder hacer un docente.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if (!colegiosInstalados()) {
    mensaje('error', 'Falta la migración: php database/migracion-colegios.php --aplicar');
    redirigir('admin/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('accion') === 'crear') {
    exigirCsrf();

    $nombre = trim(post('nombre'));
    $slug   = slugificar(post('slug') !== '' ? post('slug') : $nombre);
    $planId = (int) post('plan_id');

    if ($nombre === '') {
        mensaje('error', 'El colegio necesita un nombre.');

    } elseif ($slug === '') {
        mensaje('error', 'La dirección del colegio no puede quedar vacía.');

    } elseif (traerValor('SELECT id FROM schools WHERE slug = ?', [$slug])) {
        mensaje('error', 'Ya hay un colegio con esa dirección. Elige otra.');

    } else {
        $id = (int) insertar(
            'INSERT INTO schools (name, slug, city, student_quota, status,
                                  plan_id, licencia_hasta, es_prueba, dominio, notas)
             VALUES (?, ?, ?, 0, "active", ?, ?, ?, ?, ?)',
            [
                $nombre,
                $slug,
                trim(post('city')),
                $planId ?: null,
                // Vacío significa indefinida, que es lo que hace falta
                // para probar sin estar renovando.
                trim(post('licencia_hasta')) !== '' ? trim(post('licencia_hasta')) : null,
                !empty($_POST['es_prueba']) ? 1 : 0,
                trim(post('dominio')) !== '' ? trim(post('dominio')) : null,
                trim(post('notas')),
            ]
        );

        mensaje('ok', 'Colegio creado. Ahora da de alta a sus docentes.');
        redirigir('admin/colegios/ver.php?id=' . $id);
    }
}

$lista  = colegios();
$planes = traerTodo('SELECT id, name, slug FROM plans WHERE is_active = 1 ORDER BY sort_order');

$titulo    = 'Colegios';
$panelZona = 'colegios';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Colegios</h1>
        <p>Un colegio da acceso completo a todos los suyos sin cobrarles uno a uno.</p>
    </div>
</div>

<?php if (!$lista): ?>
    <div class="sin-datos">
        <div class="ico">🏫</div>
        <h3>Todavía no hay ningún colegio</h3>
        <p>Créalo abajo. Después podrás dar de alta a sus docentes y estudiantes.</p>
    </div>
<?php else: ?>

    <div class="caja">
        <div class="cabeza"><h2><?= count($lista) ?> colegio(s)</h2></div>
        <div class="cuerpo">
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Colegio</th>
                            <th>Licencia</th>
                            <th class="cifra">Docentes</th>
                            <th class="cifra">Estudiantes</th>
                            <th class="cifra">Cursos</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($lista as $c): ?>
                        <tr>
                            <td>
                                <a href="<?= e(url('admin/colegios/ver.php?id=' . (int) $c['id'])) ?>">
                                    <b><?= e($c['name']) ?></b>
                                </a>
                                <?php if ((int) $c['es_prueba'] === 1): ?>
                                    <span class="distintivo naranja">prueba</span>
                                <?php endif; ?>
                                <?php if ($c['status'] !== 'active'): ?>
                                    <span class="distintivo gris"><?= e($c['status']) ?></span>
                                <?php endif; ?>
                                <div class="compacta" style="color:var(--texto-tenue)">
                                    /colegio/<?= e($c['slug']) ?>
                                </div>
                            </td>
                            <td class="compacta">
                                <?= e($c['plan_nombre'] ?? '—') ?>
                                <div style="color:var(--texto-tenue);font-size:.82rem">
                                    <?= e(textoLicencia($c)) ?>
                                </div>
                            </td>
                            <td class="cifra"><?= (int) $c['docentes'] ?></td>
                            <td class="cifra"><?= (int) $c['estudiantes'] ?></td>
                            <td class="cifra"><?= (int) $c['cursos'] ?></td>
                            <td class="compacta">
                                <a class="btn-mini" href="<?= e(url('colegio/' . $c['slug'])) ?>"
                                   target="_blank" rel="noopener">Ver su puerta ↗</a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="caja">
    <div class="cabeza"><h2>Crear un colegio</h2></div>
    <div class="cuerpo">

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="crear">

            <div class="formulario">
                <div class="campo">
                    <label for="c-nombre">Nombre</label>
                    <input id="c-nombre" name="nombre" type="text" required maxlength="160"
                           placeholder="Colegio San José">
                </div>

                <div class="campo">
                    <label for="c-slug">Dirección</label>
                    <input id="c-slug" name="slug" type="text" maxlength="160"
                           placeholder="colegio-san-jose">
                    <p class="ayuda">
                        Lo que va detrás de <code>/colegio/</code>. Si lo dejas vacío se
                        saca del nombre.
                    </p>
                </div>

                <div class="campo">
                    <label for="c-city">Ciudad</label>
                    <input id="c-city" name="city" type="text" maxlength="120">
                </div>

                <div class="campo">
                    <label for="c-plan">Plan de la licencia</label>
                    <select id="c-plan" name="plan_id">
                        <option value="">Sin licencia</option>
                        <?php foreach ($planes as $p): ?>
                            <option value="<?= (int) $p['id'] ?>"
                                    <?= $p['slug'] === 'escuela' ? 'selected' : '' ?>>
                                <?= e($p['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="ayuda">Lo que reciben todos sus miembros, sin pagar.</p>
                </div>

                <div class="campo">
                    <label for="c-hasta">Vence el</label>
                    <input id="c-hasta" name="licencia_hasta" type="date">
                    <p class="ayuda">
                        <b>Déjalo vacío para una licencia indefinida</b>, que es lo que
                        conviene para probar sin estar renovando.
                    </p>
                </div>

                <div class="campo">
                    <label for="c-dominio">Dominio de los usuarios</label>
                    <input id="c-dominio" name="dominio" type="text" maxlength="120"
                           placeholder="colegio-san-jose.local">
                    <p class="ayuda">
                        Los estudiantes se crean por nombre y se les genera un usuario con
                        este dominio. No recibe correo: es solo el identificador.
                    </p>
                </div>

                <div class="campo ancho">
                    <label for="c-notas">Nota interna</label>
                    <input id="c-notas" name="notas" type="text" maxlength="400"
                           placeholder="Quién lo trajo, qué se acordó…">
                </div>
            </div>

            <label class="casilla" style="margin-top:14px">
                <input type="checkbox" name="es_prueba" value="1" checked>
                <span>
                    <b>Es un colegio de prueba</b>
                    <span>Se marca en el panel para no confundirlo con un cliente que paga.
                          No cambia lo que puede hacer.</span>
                </span>
            </label>

            <div class="pie-formulario" style="margin-top:18px">
                <button class="btn-mini solido" type="submit">Crear colegio</button>
            </div>
        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
