<?php
/**
 * admin/planes/index.php — Planes y precios
 *
 * Cambiar un precio no debería exigir abrir un archivo .php ni un
 * gestor de base de datos. Aquí se editan los cuatro planes, sus dos
 * precios, lo que promete cada tarjeta y si se ofrece o no.
 *
 * Dos cosas que NO se pueden hacer desde aquí, a propósito:
 *
 *   · Cambiar el `slug`. Es la clave por la que el control de acceso
 *     reconoce el plan y por la que apuntan las suscripciones vendidas.
 *     Renombrarlo dejaría sin acceso a todos sus clientes, y ninguna
 *     pantalla del panel lo delataría.
 *   · Borrar un plan. Un plan con historial no se borra: se desactiva.
 *     Borrarlo se llevaría por delante las suscripciones y los cobros
 *     que apuntan a él, que son el informe contable del año pasado.
 *
 * Bajar un precio tampoco reescribe lo ya vendido: cada suscripción y
 * cada pago congelan el monto que se cobró en su momento.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$errores = [];
$editando = getEntero('editar');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $id = (int) ($_POST['id'] ?? 0);
    $r  = guardarPlan($id, $_POST);

    if ($r['ok']) {
        mensaje('ok', 'Plan actualizado.');
        redirigir('admin/planes/');
    }

    $errores  = $r['errores'];
    $editando = $id;
    mensaje('error', 'Revisa los campos señalados.');
}

$planes = traerTodo('SELECT * FROM plans ORDER BY sort_order, id');

// Cuánta gente hay en cada plan: sin este dato, desactivar un plan es
// una decisión a ciegas.
$suscritos = [];
foreach (traerTodo(
    'SELECT plan_id, COUNT(DISTINCT user_id) AS total FROM subscriptions
      WHERE status = "active" AND (expires_at IS NULL OR expires_at > NOW())
   GROUP BY plan_id'
) as $f) {
    $suscritos[(int) $f['plan_id']] = (int) $f['total'];
}

$titulo    = 'Planes y precios';
$panelZona = 'planes';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Planes y precios</h1>
        <p>Lo que se cobra y lo que promete cada tarjeta de la página de precios.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('planes/')) ?>" target="_blank" rel="noopener">Ver la página pública ↗</a>
    </div>
</div>

<div class="aviso-suave" style="margin-bottom:18px">
    Cambiar un precio afecta solo a las <b>ventas futuras</b>. Cada suscripción y cada cobro
    ya registrados guardan el monto que se cobró entonces, así que bajar el precio no
    reescribe la contabilidad del año pasado ni le debe dinero a nadie.
</div>

<?php foreach ($planes as $p): ?>
    <?php
    $abierto  = $editando === (int) $p['id'];
    $gente    = $suscritos[(int) $p['id']] ?? 0;
    $features = $p['features'] ? (json_decode($p['features'], true) ?: []) : [];
    $ahorro   = ahorroAnual(
        $p['price_monthly_cop'] !== null ? (int) $p['price_monthly_cop'] : null,
        $p['price_yearly_cop']  !== null ? (int) $p['price_yearly_cop']  : null
    );
    ?>

    <details class="caja crear-usuario" <?= $abierto ? 'open' : '' ?>>
        <summary>
            <span aria-hidden="true"><?= (int) $p['is_active'] === 1 ? '🟢' : '⚪' ?></span>
            <?= e($p['name']) ?>
            <span class="distintivo gris" style="margin-left:8px"><?= e($p['slug']) ?></span>
            <?php if ((int) $p['is_recommended'] === 1): ?>
                <span class="distintivo azul">recomendado</span>
            <?php endif; ?>
            <?php if ($gente > 0): ?>
                <span class="distintivo verde"><?= $gente ?> <?= $gente === 1 ? 'suscrito' : 'suscritos' ?></span>
            <?php endif; ?>
            <span style="margin-left:auto;color:var(--texto-tenue);font-size:.86rem;font-weight:500">
                <?= e(precioCop($p['price_yearly_cop'] !== null ? (int) $p['price_yearly_cop'] : null)) ?>/año
                <?php if ($p['price_monthly_cop'] !== null): ?>
                    · <?= e(precioCop((int) $p['price_monthly_cop'])) ?>/mes
                <?php endif; ?>
            </span>
        </summary>

        <?php if ((int) $p['is_active'] === 0 && $gente > 0): ?>
            <div class="aviso info" style="margin:16px 20px 0">
                Este plan está desactivado pero todavía tiene <b><?= $gente ?></b> suscripciones
                vigentes. Siguen funcionando hasta su vencimiento: desactivar solo impide
                contratarlo de nuevo.
            </div>
        <?php endif; ?>

        <form method="post" class="formulario" style="padding:20px">
            <?= campoCsrf() ?>
            <input type="hidden" name="id" value="<?= (int) $p['id'] ?>">

            <div class="pareja">
                <div class="campo <?= isset($errores['name']) && $abierto ? 'error' : '' ?>">
                    <label for="n-<?= (int) $p['id'] ?>">Nombre</label>
                    <input id="n-<?= (int) $p['id'] ?>" name="name" type="text" required maxlength="80"
                           value="<?= e($p['name']) ?>">
                    <?php if (isset($errores['name']) && $abierto): ?>
                        <p class="error-texto"><?= e($errores['name']) ?></p>
                    <?php endif; ?>
                </div>

                <div class="campo">
                    <label for="t-<?= (int) $p['id'] ?>">Frase de la tarjeta</label>
                    <input id="t-<?= (int) $p['id'] ?>" name="tagline" type="text" maxlength="160"
                           value="<?= e($p['tagline'] ?? '') ?>">
                    <p class="ayuda">Una línea. Es lo que se lee bajo el nombre en la página de precios.</p>
                </div>
            </div>

            <div class="pareja">
                <div class="campo">
                    <label for="pm-<?= (int) $p['id'] ?>">Precio mensual (COP)</label>
                    <input id="pm-<?= (int) $p['id'] ?>" name="price_monthly_cop" type="number" min="0" step="500"
                           value="<?= $p['price_monthly_cop'] !== null ? (int) $p['price_monthly_cop'] : '' ?>">
                    <p class="ayuda">Vacío = no se ofrece al mes. No es lo mismo que cero.</p>
                </div>

                <div class="campo <?= isset($errores['price_yearly_cop']) && $abierto ? 'error' : '' ?>">
                    <label for="pa-<?= (int) $p['id'] ?>">Precio anual (COP)</label>
                    <input id="pa-<?= (int) $p['id'] ?>" name="price_yearly_cop" type="number" min="0" step="500"
                           value="<?= $p['price_yearly_cop'] !== null ? (int) $p['price_yearly_cop'] : '' ?>">
                    <?php if ($ahorro): ?>
                        <p class="ayuda">
                            Hoy el anual ahorra <b><?= e(precioCop($ahorro['monto'])) ?></b>
                            (<?= (int) $ahorro['porcentaje'] ?>%) frente a doce meses sueltos.
                        </p>
                    <?php endif; ?>
                    <?php if (isset($errores['price_yearly_cop']) && $abierto): ?>
                        <p class="error-texto"><?= e($errores['price_yearly_cop']) ?></p>
                    <?php endif; ?>
                </div>
            </div>

            <div class="campo">
                <label for="d-<?= (int) $p['id'] ?>">Descripción</label>
                <textarea id="d-<?= (int) $p['id'] ?>" name="description" maxlength="1000"><?= e($p['description'] ?? '') ?></textarea>
            </div>

            <div class="campo">
                <label for="f-<?= (int) $p['id'] ?>">Beneficios</label>
                <textarea id="f-<?= (int) $p['id'] ?>" name="features" rows="6"><?= e(implode("\n", $features)) ?></textarea>
                <p class="ayuda">Uno por línea. Aparecen como viñetas en la tarjeta de precios.</p>
            </div>

            <div class="separador"></div>

            <div class="campo">
                <label>Acceso al catálogo</label>
                <div class="opciones-acceso">
                    <label class="opcion-acceso">
                        <input type="radio" name="catalog_access" value="partial"
                               <?= $p['catalog_access'] === 'partial' ? 'checked' : '' ?>>
                        <span>
                            <b>Parcial</b>
                            <span>Solo lo marcado como libre: las primeras estaciones de cada actividad.</span>
                        </span>
                    </label>
                    <label class="opcion-acceso">
                        <input type="radio" name="catalog_access" value="full"
                               <?= $p['catalog_access'] === 'full' ? 'checked' : '' ?>>
                        <span>
                            <b>Completo</b>
                            <span>Las 154 actividades enteras, y lo que se publique mientras dure la suscripción.</span>
                        </span>
                    </label>
                </div>
            </div>

            <div class="trio">
                <div class="casilla">
                    <input type="checkbox" id="c-<?= (int) $p['id'] ?>" name="manages_courses" value="1"
                           <?= (int) $p['manages_courses'] === 1 ? 'checked' : '' ?>>
                    <label for="c-<?= (int) $p['id'] ?>">
                        Habilita el área Escuela
                        <span style="display:block;font-size:.79rem;color:var(--texto-tenue);font-weight:500">
                            Cursos, estudiantes y asignaciones.
                        </span>
                    </label>
                </div>

                <div class="casilla">
                    <input type="checkbox" id="r-<?= (int) $p['id'] ?>" name="is_recommended" value="1"
                           <?= (int) $p['is_recommended'] === 1 ? 'checked' : '' ?>>
                    <label for="r-<?= (int) $p['id'] ?>">
                        Destacar en la página
                        <span style="display:block;font-size:.79rem;color:var(--texto-tenue);font-weight:500">
                            Marca solo uno: si destacan todos, no destaca ninguno.
                        </span>
                    </label>
                </div>

                <div class="casilla">
                    <input type="checkbox" id="a-<?= (int) $p['id'] ?>" name="is_active" value="1"
                           <?= (int) $p['is_active'] === 1 ? 'checked' : '' ?>>
                    <label for="a-<?= (int) $p['id'] ?>">
                        A la venta
                        <span style="display:block;font-size:.79rem;color:var(--texto-tenue);font-weight:500">
                            Al quitarlo desaparece de la página. Las suscripciones vivas siguen valiendo.
                        </span>
                    </label>
                </div>
            </div>

            <div class="campo" style="max-width:180px">
                <label for="o-<?= (int) $p['id'] ?>">Orden</label>
                <input id="o-<?= (int) $p['id'] ?>" name="sort_order" type="number"
                       value="<?= (int) $p['sort_order'] ?>">
            </div>

            <div class="pie-formulario">
                <button class="btn-mini solido" type="submit">Guardar «<?= e($p['name']) ?>»</button>
            </div>
        </form>
    </details>

<?php endforeach; ?>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
