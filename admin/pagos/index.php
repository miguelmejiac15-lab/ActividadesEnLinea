<?php
/**
 * admin/pagos/index.php — Cobros
 *
 * El libro de caja. Aquí se ve todo lo que alguien intentó pagar, se
 * confirma lo que llegó y se cierra lo que no.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ CONFIRMAR SE HACE DESDE AQUÍ Y RECHAZAR NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Confirmar es un botón en la fila: es lo que se hace veinte veces
 * seguidas un lunes por la mañana con el extracto del banco al lado, y
 * obligar a entrar y salir de veinte fichas para eso sería un castigo.
 *
 * Rechazar no. Pide un motivo escrito, y un motivo escrito de verdad
 * no cabe en una fila de tabla. Además es la acción que alguien va a
 * cuestionar dentro de tres meses («¿por qué no me activaron?»), así
 * que merece una pantalla donde se vea el pago entero antes de decidir.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$instalado = pagosInstalados();

$errores  = [];
$nuevo    = ['email' => '', 'plan' => '', 'ciclo' => 'yearly',
             'metodo' => 'transferencia', 'proof_reference' => '', 'notes' => ''];

if ($instalado && $_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $id     = (int) ($_POST['id'] ?? 0);

    if ($accion === 'confirmar' && $id > 0) {
        $res = confirmarPago($id, usuarioActualId());
        if ($res['ok']) {
            mensaje('ok', !empty($res['repetido'])
                ? 'Ese pago ya estaba confirmado. No se concedió el acceso dos veces.'
                : 'Pago confirmado. El acceso ya está activo.');
        } else {
            mensaje('error', $res['error']);
        }
    }

    if ($accion === 'registrar') {

        foreach (array_keys($nuevo) as $c) {
            $nuevo[$c] = trim((string) ($_POST[$c] ?? $nuevo[$c]));
        }

        $usuario = traerUno('SELECT id, name FROM users WHERE email = ?', [$nuevo['email']]);

        if (!$usuario) {
            $errores['email'] = 'No hay ninguna cuenta con ese correo.';
        }

        if (!$errores) {
            $res = crearPago(
                (int) $usuario['id'],
                $nuevo['plan'],
                $nuevo['ciclo'],
                $nuevo['metodo'],
                [
                    'proof_reference' => $nuevo['proof_reference'],
                    'notes'           => $nuevo['notes'],
                ]
            );

            if ($res['ok']) {
                $pagoId = (int) $res['pago']['id'];

                /*
                 * Un cobro registrado a mano casi siempre es dinero que YA
                 * llegó —una consignación, un efectivo, una cortesía— así
                 * que se ofrece confirmarlo en el mismo envío. Registrarlo
                 * y tener que ir a buscarlo para confirmarlo serían dos
                 * pasos para una sola decisión que ya estaba tomada.
                 */
                if (post('confirmar_ya') === '1') {
                    $c = confirmarPago($pagoId, usuarioActualId(), [
                        'motivo' => 'Registrado y confirmado a mano desde el panel.',
                    ]);
                    mensaje($c['ok'] ? 'ok' : 'error',
                        $c['ok'] ? 'Cobro registrado y confirmado. El acceso ya está activo.' : $c['error']);
                } else {
                    mensaje('ok', 'Cobro registrado como pendiente: ' . $res['pago']['reference']);
                }

                redirigir('admin/pagos/');
            }

            $errores['general'] = $res['error'];
            mensaje('error', $res['error']);
        }
    }

    if (!$errores) {
        redirigir('admin/pagos/' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }
}


// ── Filtros ──────────────────────────────────────────────────────────
$fBuscar = mb_substr(get('buscar'), 0, 80);
$fEstado = get('estado');
$fMetodo = get('metodo');
$fPlan   = get('plan');
$fDesde  = get('desde');
$fHasta  = get('hasta');

$pagina  = max(1, getEntero('pagina', 1));
$porPag  = 40;

$where  = ['1 = 1'];
$params = [];

if ($fBuscar !== '') {
    // La referencia se busca entera y los datos de la persona por trozo:
    // nadie recuerda media referencia, pero sí medio apellido.
    $where[] = '(pg.reference = ? OR u.name LIKE ? OR u.email LIKE ? OR pg.provider_reference LIKE ? OR pg.proof_reference LIKE ?)';
    $t = '%' . escaparLike($fBuscar) . '%';
    $params[] = $fBuscar;
    $params[] = $t;
    $params[] = $t;
    $params[] = $t;
    $params[] = $t;
}
if (array_key_exists($fEstado, estadosPago())) {
    $where[] = 'pg.status = ?';
    $params[] = $fEstado;
}
if (array_key_exists($fMetodo, metodosPago())) {
    $where[] = 'pg.method = ?';
    $params[] = $fMetodo;
}
if ($fPlan !== '') {
    $where[] = 'p.slug = ?';
    $params[] = $fPlan;
}
if ($fDesde !== '' && strtotime($fDesde)) {
    $where[] = 'pg.created_at >= ?';
    $params[] = date('Y-m-d 00:00:00', strtotime($fDesde));
}
if ($fHasta !== '' && strtotime($fHasta)) {
    $where[] = 'pg.created_at <= ?';
    $params[] = date('Y-m-d 23:59:59', strtotime($fHasta));
}

$condicion = implode(' AND ', $where);

$pagos = $total = 0;
$lista = [];
$sumaFiltro = ['confirmado' => 0, 'pendiente' => 0];

if ($instalado) {
    $total = (int) traerValor(
        'SELECT COUNT(*)' . UNIONES_PAGO . ' WHERE ' . $condicion,
        $params
    );

    $lista = traerTodo(
        'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
          WHERE ' . $condicion . '
       ORDER BY pg.created_at DESC
          LIMIT ' . $porPag . ' OFFSET ' . (($pagina - 1) * $porPag),
        $params
    );

    // Los totales se calculan sobre el filtro completo, no sobre la
    // página que se está viendo: un total que cambia al pasar de página
    // no es un total.
    $sumas = traerTodo(
        'SELECT pg.status, SUM(pg.amount_cop) AS monto' . UNIONES_PAGO . '
          WHERE ' . $condicion . ' GROUP BY pg.status',
        $params
    );
    foreach ($sumas as $s) {
        if ($s['status'] === PAGO_CONFIRMADO) {
            $sumaFiltro['confirmado'] = (int) $s['monto'];
        } elseif ($s['status'] === PAGO_PENDIENTE) {
            $sumaFiltro['pendiente'] = (int) $s['monto'];
        }
    }
}

$paginas = max(1, (int) ceil($total / $porPag));
$planes  = traerTodo('SELECT slug, name, price_monthly_cop, price_yearly_cop
                        FROM plans WHERE slug <> "free" AND is_active = 1 ORDER BY sort_order');

/** Conserva los filtros al saltar de página. */
function enlacePagina(int $n): string
{
    $q = $_GET;
    $q['pagina'] = $n;
    return url('admin/pagos/?' . http_build_query($q));
}

$titulo    = 'Cobros';
$panelZona = 'pagos';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Cobros</h1>
        <p>
            <?= number_format($total, 0, ',', '.') ?>
            <?= $total === 1 ? 'movimiento' : 'movimientos' ?> con los filtros puestos ·
            <b style="color:#2e7d32"><?= e(precioCop($sumaFiltro['confirmado'])) ?></b> confirmados
            <?php if ($sumaFiltro['pendiente'] > 0): ?>
                · <b style="color:#e65100"><?= e(precioCop($sumaFiltro['pendiente'])) ?></b> por confirmar
            <?php endif; ?>
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/metricas/')) ?>">📈 Métricas</a>
        <a class="btn-mini" href="<?= e(url('admin/pagos/ajustes.php')) ?>">⚙️ Ajustes de cobro</a>
        <?php if ($total > 0): ?>
            <a class="btn-mini solido"
               href="<?= e(url('admin/pagos/exportar.php?' . http_build_query($_GET))) ?>">⬇ Exportar CSV</a>
        <?php endif; ?>
    </div>
</div>

<?php if (!$instalado): ?>

    <div class="aviso mal">
        <b>Falta crear las tablas de pagos.</b><br>
        Ejecuta <code>php database/migracion-pagos.php --aplicar</code> y vuelve a esta pantalla.
    </div>

<?php else: ?>

    <?php if (!recaudoConfigurado()): ?>
        <div class="aviso info">
            <b>Todavía no hay una cuenta de recaudo puesta.</b>
            Sin ella, el cliente llega a la pantalla de pago y no ve a dónde transferir.
            <a href="<?= e(url('admin/pagos/ajustes.php')) ?>" style="font-weight:600">Configurarla ahora →</a>
        </div>
    <?php endif; ?>

    <?php
    /*
     * Registrar un cobro a mano va plegado, igual que crear un usuario:
     * lo normal es confirmar lo que ya entró, no inventar movimientos.
     */
    ?>
    <details class="caja crear-usuario" <?= $errores ? 'open' : '' ?>>
        <summary><span aria-hidden="true">➕</span> Registrar un cobro a mano</summary>

        <p class="ayuda-crear">
            Para una consignación que llegó por fuera, un pago en efectivo o una cortesía.
            El monto lo pone el plan elegido: no se escribe aquí para que nadie cobre de
            menos por error.
        </p>

        <form method="post" class="rejilla-crear">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="registrar">

            <div class="campo ancho">
                <label for="p-email">Correo de la cuenta</label>
                <input id="p-email" name="email" type="email" required maxlength="190"
                       value="<?= e($nuevo['email']) ?>" placeholder="la cuenta ya debe existir">
                <?php if (isset($errores['email'])): ?>
                    <p class="error-campo"><?= e($errores['email']) ?></p>
                <?php endif; ?>
            </div>

            <div class="campo">
                <label for="p-plan">Plan</label>
                <select id="p-plan" name="plan" required>
                    <?php foreach ($planes as $p): ?>
                        <option value="<?= e($p['slug']) ?>" <?= $nuevo['plan'] === $p['slug'] ? 'selected' : '' ?>>
                            <?= e($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="p-ciclo">Modalidad</label>
                <select id="p-ciclo" name="ciclo">
                    <option value="yearly"  <?= $nuevo['ciclo'] === 'yearly'  ? 'selected' : '' ?>>Anual</option>
                    <option value="monthly" <?= $nuevo['ciclo'] === 'monthly' ? 'selected' : '' ?>>Mensual</option>
                </select>
            </div>

            <div class="campo">
                <label for="p-metodo">Forma de pago</label>
                <select id="p-metodo" name="metodo">
                    <?php foreach (metodosPago() as $k => $m): ?>
                        <option value="<?= e($k) ?>" <?= $nuevo['metodo'] === $k ? 'selected' : '' ?>>
                            <?= e($m['icono'] . ' ' . $m['etiqueta']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
            </div>

            <div class="campo">
                <label for="p-comprobante">Nº de comprobante</label>
                <input id="p-comprobante" name="proof_reference" type="text" maxlength="120"
                       value="<?= e($nuevo['proof_reference']) ?>" placeholder="opcional">
            </div>

            <div class="campo ancho">
                <label for="p-notas">Nota interna</label>
                <input id="p-notas" name="notes" type="text" maxlength="255"
                       value="<?= e($nuevo['notes']) ?>"
                       placeholder="Por qué se registra a mano. Lo agradecerá quien lo lea en seis meses.">
            </div>

            <div class="campo ancho">
                <div class="casilla" style="margin-bottom:12px">
                    <input type="checkbox" id="p-ya" name="confirmar_ya" value="1" checked>
                    <label for="p-ya">
                        Confirmarlo ya y conceder el acceso
                        <span style="display:block;font-size:.79rem;color:var(--texto-tenue);font-weight:500">
                            Quítalo si el dinero todavía no ha llegado.
                        </span>
                    </label>
                </div>
                <button class="btn-mini solido" type="submit">Registrar cobro</button>
            </div>
        </form>
    </details>

    <div class="caja">

        <form class="barra-filtros" method="get">
            <input type="search" name="buscar" value="<?= e($fBuscar) ?>"
                   placeholder="Referencia, nombre o correo…" aria-label="Buscar">

            <select name="estado" aria-label="Estado">
                <option value="">Todos los estados</option>
                <?php foreach (estadosPago() as $k => $et): ?>
                    <option value="<?= e($k) ?>" <?= $fEstado === $k ? 'selected' : '' ?>>
                        <?= e($et['etiqueta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="metodo" aria-label="Forma de pago">
                <option value="">Todas las formas</option>
                <?php foreach (metodosPago() as $k => $m): ?>
                    <option value="<?= e($k) ?>" <?= $fMetodo === $k ? 'selected' : '' ?>>
                        <?= e($m['etiqueta']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <select name="plan" aria-label="Plan">
                <option value="">Todos los planes</option>
                <?php foreach ($planes as $p): ?>
                    <option value="<?= e($p['slug']) ?>" <?= $fPlan === $p['slug'] ? 'selected' : '' ?>>
                        <?= e($p['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>

            <input type="date" name="desde" value="<?= e($fDesde) ?>" aria-label="Desde"
                   style="padding:8px 11px;border:2px solid var(--borde);border-radius:10px;font-family:inherit;font-size:.85rem">
            <input type="date" name="hasta" value="<?= e($fHasta) ?>" aria-label="Hasta"
                   style="padding:8px 11px;border:2px solid var(--borde);border-radius:10px;font-family:inherit;font-size:.85rem">

            <button class="btn-mini solido" type="submit">Filtrar</button>
            <a class="btn-mini" href="<?= e(url('admin/pagos/')) ?>">Limpiar</a>
        </form>

        <?php if ($lista): ?>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Referencia</th><th>Cliente</th><th>Plan</th>
                            <th class="cifra">Monto</th><th>Forma</th><th>Estado</th>
                            <th class="cifra">Fecha</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($lista as $pg): ?>
                            <?php $et = etiquetaEstadoPago($pg['status']); ?>
                            <tr>
                                <td class="compacta">
                                    <a href="<?= e(url('admin/pagos/ver.php?id=' . (int) $pg['id'])) ?>"
                                       style="font-family:ui-monospace,Consolas,monospace;font-size:.83rem;color:var(--azul);font-weight:600">
                                        <?= e($pg['reference']) ?>
                                    </a>
                                </td>

                                <td>
                                    <b><?= e($pg['usuario']) ?></b><br>
                                    <small style="color:var(--texto-tenue)"><?= e($pg['usuario_email']) ?></small>
                                </td>

                                <td class="compacta">
                                    <?= e($pg['plan']) ?><br>
                                    <small style="color:var(--texto-tenue)">
                                        <?= e(etiquetaCiclo((string) $pg['billing_cycle'])) ?>
                                    </small>
                                </td>

                                <td class="cifra"><b><?= e(precioCop((int) $pg['amount_cop'])) ?></b></td>

                                <td class="compacta">
                                    <span title="<?= e(etiquetaMetodo($pg['method'])) ?>">
                                        <?= e(iconoMetodo($pg['method'])) ?>
                                    </span>
                                    <small style="color:var(--texto-tenue)"><?= e(etiquetaMetodo($pg['method'])) ?></small>
                                </td>

                                <td class="compacta">
                                    <span class="distintivo <?= e($et['color']) ?>">
                                        <?= e($et['icono']) ?> <?= e($et['etiqueta']) ?>
                                    </span>
                                </td>

                                <td class="cifra" style="color:var(--texto-tenue);font-size:.82rem">
                                    <?= e(date('d/m/Y', strtotime($pg['created_at']))) ?>
                                </td>

                                <td>
                                    <div class="acciones-celda">
                                        <?php if ($pg['status'] === PAGO_PENDIENTE): ?>
                                            <form class="enlinea" method="post"
                                                  onsubmit="return confirm('¿Confirmar <?= e($pg['reference']) ?> por <?= e(precioCop((int) $pg['amount_cop'])) ?>?\n\nSe concede el acceso de inmediato.')">
                                                <?= campoCsrf() ?>
                                                <input type="hidden" name="accion" value="confirmar">
                                                <input type="hidden" name="id" value="<?= (int) $pg['id'] ?>">
                                                <button class="btn-mini solido" type="submit">Confirmar</button>
                                            </form>
                                        <?php endif; ?>
                                        <a class="btn-mini" href="<?= e(url('admin/pagos/ver.php?id=' . (int) $pg['id'])) ?>">Ver</a>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <?php if ($paginas > 1): ?>
                <div style="display:flex;gap:7px;align-items:center;padding:14px 20px;flex-wrap:wrap">
                    <?php if ($pagina > 1): ?>
                        <a class="btn-mini" href="<?= e(enlacePagina($pagina - 1)) ?>">← Anterior</a>
                    <?php endif; ?>
                    <span style="color:var(--texto-tenue);font-size:.85rem">
                        Página <?= $pagina ?> de <?= $paginas ?>
                    </span>
                    <?php if ($pagina < $paginas): ?>
                        <a class="btn-mini" href="<?= e(enlacePagina($pagina + 1)) ?>">Siguiente →</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

        <?php else: ?>
            <div class="sin-datos">
                <span class="ico" aria-hidden="true">🧾</span>
                <h3>No hay cobros con esos filtros</h3>
                <p>Cuando alguien contrate un plan, su pago aparecerá aquí esperando confirmación.</p>
            </div>
        <?php endif; ?>

    </div>

<?php endif; ?>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
