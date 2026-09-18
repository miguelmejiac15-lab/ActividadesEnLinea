<?php
/**
 * admin/suscripciones/index.php — Consultar y administrar suscripciones
 *
 * Nota sobre la vigencia: una suscripción puede estar en estado `active`
 * y aun así haber caducado. El acceso lo decide la fecha, no el estado,
 * así que aquí se muestran ambas cosas por separado para que no haya
 * confusión al revisar un caso.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $id = (int) ($_POST['id'] ?? 0);

    if ($id > 0 && post('accion') === 'cancelar') {
        cancelarSuscripcion($id);
        mensaje('ok', 'Suscripción cancelada. El usuario vuelve al acceso gratuito.');
    }

    redirigir('admin/suscripciones/' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
}

$fEstado = get('estado');

$where  = ['1 = 1'];
$params = [];

if ($fEstado === 'vigentes') {
    $where[] = 's.status = "active" AND (s.expires_at IS NULL OR s.expires_at > NOW())';
} elseif ($fEstado === 'vencidas') {
    $where[] = 's.expires_at IS NOT NULL AND s.expires_at <= NOW()';
} elseif (in_array($fEstado, ['pending', 'cancelled'], true)) {
    $where[] = 's.status = ?';
    $params[] = $fEstado;
}

$suscripciones = traerTodo(
    'SELECT s.*, u.name AS usuario, u.email, p.name AS plan
       FROM subscriptions s
       JOIN users u ON u.id = s.user_id
       JOIN plans p ON p.id = s.plan_id
      WHERE ' . implode(' AND ', $where) . '
   ORDER BY s.created_at DESC
      LIMIT 200',
    $params
);

$resumen = [
    'Vigentes'  => (int) traerValor('SELECT COUNT(*) FROM subscriptions WHERE status = "active" AND (expires_at IS NULL OR expires_at > NOW())'),
    'Vencidas'  => (int) traerValor('SELECT COUNT(*) FROM subscriptions WHERE expires_at IS NOT NULL AND expires_at <= NOW()'),
    'Canceladas'=> (int) traerValor('SELECT COUNT(*) FROM subscriptions WHERE status = "cancelled"'),
];

$ingresos = (int) traerValor(
    'SELECT COALESCE(SUM(amount_cop), 0) FROM subscriptions
      WHERE status = "active" AND (expires_at IS NULL OR expires_at > NOW())'
);

// Suscripciones que vencen pronto: el momento de avisar al usuario.
$porVencer = traerTodo(
    'SELECT s.id, s.expires_at, u.name, u.email, p.name AS plan
       FROM subscriptions s
       JOIN users u ON u.id = s.user_id
       JOIN plans p ON p.id = s.plan_id
      WHERE s.status = "active"
        AND s.expires_at BETWEEN NOW() AND NOW() + INTERVAL 30 DAY
   ORDER BY s.expires_at'
);

$titulo    = 'Suscripciones';
$panelZona = 'suscripciones';

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Suscripciones</h1>
        <p>El acceso lo decide la fecha de vencimiento, no el estado guardado.</p>
    </div>
</div>

<div class="cifras">
    <div class="cifra-caja verde">
        <div class="n"><?= $resumen['Vigentes'] ?></div>
        <div class="t">Vigentes</div>
    </div>
    <div class="cifra-caja naranja">
        <div class="n"><?= $resumen['Vencidas'] ?></div>
        <div class="t">Vencidas</div>
    </div>
    <div class="cifra-caja rojo">
        <div class="n"><?= $resumen['Canceladas'] ?></div>
        <div class="t">Canceladas</div>
    </div>
    <div class="cifra-caja">
        <div class="n" style="font-size:1.35rem"><?= e(precioCop($ingresos)) ?></div>
        <div class="t">Facturado vigente</div>
    </div>
</div>

<?php if ($porVencer): ?>
    <div class="caja">
        <div class="cabeza"><h2>⏰ Vencen en los próximos 30 días</h2></div>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead><tr><th>Usuario</th><th>Plan</th><th>Vence</th><th>Faltan</th></tr></thead>
                <tbody>
                    <?php foreach ($porVencer as $s): ?>
                        <?php $dias = (int) ceil((strtotime($s['expires_at']) - time()) / 86400); ?>
                        <tr>
                            <td><b><?= e($s['name']) ?></b><br>
                                <small style="color:var(--texto-tenue)"><?= e($s['email']) ?></small></td>
                            <td><?= e($s['plan']) ?></td>
                            <td class="compacta"><?= e(fechaLarga($s['expires_at'])) ?></td>
                            <td class="compacta">
                                <span class="distintivo <?= $dias <= 7 ? 'rojo' : 'naranja' ?>">
                                    <?= $dias ?> <?= $dias === 1 ? 'día' : 'días' ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<div class="caja">

    <form class="barra-filtros" method="get">
        <select name="estado" aria-label="Estado">
            <option value="">Todas</option>
            <option value="vigentes"  <?= $fEstado === 'vigentes'  ? 'selected' : '' ?>>Vigentes</option>
            <option value="vencidas"  <?= $fEstado === 'vencidas'  ? 'selected' : '' ?>>Vencidas</option>
            <option value="cancelled" <?= $fEstado === 'cancelled' ? 'selected' : '' ?>>Canceladas</option>
            <option value="pending"   <?= $fEstado === 'pending'   ? 'selected' : '' ?>>Pendientes</option>
        </select>
        <button class="btn-mini solido" type="submit">Filtrar</button>
        <a class="btn-mini" href="<?= e(url('admin/suscripciones/')) ?>">Limpiar</a>
    </form>

    <?php if ($suscripciones): ?>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Usuario</th><th>Plan</th><th>Modalidad</th><th>Monto</th>
                        <th>Vigencia</th><th>Origen</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($suscripciones as $s): ?>
                        <?php
                        $vencida = $s['expires_at'] && strtotime($s['expires_at']) <= time();
                        $vigente = $s['status'] === 'active' && !$vencida;
                        ?>
                        <tr>
                            <td>
                                <b><?= e($s['usuario']) ?></b><br>
                                <small style="color:var(--texto-tenue)"><?= e($s['email']) ?></small>
                            </td>
                            <td class="compacta"><?= e($s['plan']) ?></td>
                            <td class="compacta"><?= $s['billing_cycle'] === 'yearly' ? 'Anual' : 'Mensual' ?></td>
                            <td class="compacta"><?= e(precioCop((int) $s['amount_cop'])) ?></td>
                            <td class="compacta">
                                <?php if ($vigente): ?>
                                    <span class="distintivo verde">Vigente</span><br>
                                <?php elseif ($s['status'] === 'cancelled'): ?>
                                    <span class="distintivo rojo">Cancelada</span><br>
                                <?php elseif ($vencida): ?>
                                    <span class="distintivo naranja">Vencida</span><br>
                                <?php else: ?>
                                    <span class="distintivo gris"><?= e($s['status']) ?></span><br>
                                <?php endif; ?>
                                <small style="color:var(--texto-tenue)">
                                    <?= $s['expires_at'] ? e(date('d/m/Y', strtotime($s['expires_at']))) : 'sin fecha' ?>
                                </small>
                            </td>
                            <td class="compacta">
                                <small style="color:var(--texto-tenue)">
                                    <?= e($s['payment_provider'] ?? '—') ?>
                                </small>
                            </td>
                            <td>
                                <?php if ($vigente): ?>
                                    <form class="enlinea" method="post"
                                          onsubmit="return confirm('¿Cancelar la suscripción de <?= e(addslashes($s['usuario'])) ?>?\n\nVolverá al acceso gratuito.')">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="cancelar">
                                        <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                        <button class="btn-mini peligro" type="submit">Cancelar</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="sin-datos">
            <span class="ico" aria-hidden="true">💳</span>
            <h3>Todavía no hay suscripciones</h3>
            <p>Puedes activar una a mano desde la ficha de un usuario, mientras no haya pasarela de pagos.</p>
        </div>
    <?php endif; ?>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
