<?php
/**
 * admin/pagos/ver.php — Ficha de un cobro
 *
 * Todo lo que se puede saber de un pago en una sola pantalla: quién,
 * cuánto, cómo, en qué estado y —sobre todo— qué le ha pasado desde que
 * nació.
 *
 * La línea de tiempo de abajo no es adorno. Con dinero de por medio, la
 * pregunta que llega por teléfono nunca es «¿está activo?» sino «¿quién
 * dijo que sí y cuándo?». Un estado guardado responde la primera; solo
 * un registro de eventos responde la segunda.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if (!pagosInstalados()) {
    mensaje('error', 'Falta ejecutar la migración de pagos.');
    redirigir('admin/pagos/');
}

$id   = getEntero('id');
$pago = $id > 0 ? pagoPorId($id) : null;

if (!$pago) {
    mensaje('error', 'Ese cobro no existe.');
    redirigir('admin/pagos/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $motivo = post('motivo');
    $yo     = usuarioActualId();

    switch ($accion) {

        case 'confirmar':
            $r = confirmarPago($id, $yo, [
                'provider_reference' => post('provider_reference'),
                'motivo'             => $motivo,
            ]);
            mensaje($r['ok'] ? 'ok' : 'error',
                $r['ok']
                    ? (!empty($r['repetido']) ? 'Ya estaba confirmado.' : 'Pago confirmado y acceso concedido.')
                    : $r['error']);
            break;

        case 'rechazar':
            $r = rechazarPago($id, $motivo, $yo);
            mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Pago rechazado.' : $r['error']);
            break;

        case 'anular':
            $r = anularPago($id, $motivo, $yo);
            mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Pago anulado.' : $r['error']);
            break;

        case 'reembolsar':
            $r = reembolsarPago($id, $motivo, $yo);
            mensaje($r['ok'] ? 'ok' : 'error',
                $r['ok'] ? 'Reembolso registrado. La suscripción quedó cancelada.' : $r['error']);
            break;

        case 'guardar':
            $r = actualizarPago($id, $_POST, $yo);
            if ($r['ok']) {
                mensaje('ok', !empty($r['sin_cambios']) ? 'No había nada que cambiar.' : 'Cambios guardados.');
            } else {
                mensaje('error', $r['error']);
            }
            break;

        case 'anotar':
            $r = anotarPago($id, post('nota'), $yo);
            mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Nota añadida.' : $r['error']);
            break;

        /*
         * Preguntarle a Mercado Pago por este pago.
         *
         * Es la salida cuando se pierde un aviso: el cliente pagó, la
         * pasarela avisó y el aviso no llegó —se cayó la red, el sitio
         * estaba caído, el secreto estaba mal copiado—. Sin este botón, el
         * único que podía desatascarlo era el propio cliente volviendo a
         * la pantalla de retorno, y la gente cierra la pestaña.
         *
         * NO concede nada por sí solo: llama a la API, comprueba monto y
         * moneda igual que el webhook, y solo entonces aplica.
         */
        case 'sincronizar':
            if (pasarelaActiva() !== 'mercadopago') {
                mensaje('error', 'No hay ninguna pasarela conectada a la que preguntar.');
                break;
            }

            $r = sincronizarPagoMp($pago);

            mensaje(
                $r['ok'] ? ($r['aplicado'] ? 'ok' : 'info') : 'error',
                $r['mensaje']
            );
            break;
    }

    redirigir('admin/pagos/ver.php?id=' . $id);
}

$eventos     = eventosDePago($id);
$estado      = etiquetaEstadoPago($pago['status']);
$pendiente   = $pago['status'] === PAGO_PENDIENTE;
$confirmado  = $pago['status'] === PAGO_CONFIRMADO;

// La suscripción que este pago concedió, si llegó a concederla.
$suscripcion = $pago['subscription_id']
    ? traerUno('SELECT s.*, p.name AS plan FROM subscriptions s
                  JOIN plans p ON p.id = s.plan_id WHERE s.id = ?', [(int) $pago['subscription_id']])
    : null;

// Otros pagos del mismo cliente: el contexto que evita confirmar dos
// veces la misma transferencia por venir con dos referencias.
$otros = traerTodo(
    'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
      WHERE pg.user_id = ? AND pg.id <> ?
   ORDER BY pg.created_at DESC LIMIT 6',
    [(int) $pago['user_id'], $id]
);

$titulo    = 'Cobro ' . $pago['reference'];
$panelZona = 'pagos';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<nav class="migas" aria-label="Ruta" style="margin-bottom:14px;font-size:.87rem;color:var(--texto-tenue)">
    <a href="<?= e(url('admin/pagos/')) ?>" style="color:var(--azul)">Cobros</a>
    <span>›</span>
    <span><?= e($pago['reference']) ?></span>
</nav>

<div class="ficha-cabeza">
    <div class="identidad">
        <h1>
            <span class="referencia"><?= e($pago['reference']) ?></span>
            <span class="distintivo <?= e($estado['color']) ?>">
                <?= e($estado['icono']) ?> <?= e($estado['etiqueta']) ?>
            </span>
        </h1>
        <p style="color:var(--texto-tenue);font-size:.9rem;margin-top:4px">
            Creado el <?= e(fechaLarga($pago['created_at'])) ?>
            a las <?= e(date('H:i', strtotime($pago['created_at']))) ?>
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $pago['user_id'])) ?>">
            👤 Ficha del cliente
        </a>
    </div>
</div>

<div class="rejilla-panel ancha-izq">

    <div>
        <!-- ── Resumen ────────────────────────────────────────────── -->
        <div class="caja">
            <div class="cabeza"><h2>Resumen del cobro</h2></div>
            <div class="cuerpo">
                <div class="datos-lista">
                    <div>
                        <div class="rotulo">Monto</div>
                        <div class="monto-grande"><?= e(precioCop((int) $pago['amount_cop'])) ?></div>
                    </div>
                    <div>
                        <div class="rotulo">Cliente</div>
                        <div class="dato"><?= e($pago['usuario']) ?></div>
                        <div class="dato suave" style="font-size:.85rem"><?= e($pago['usuario_email']) ?></div>
                    </div>
                    <div>
                        <div class="rotulo">Plan</div>
                        <div class="dato"><?= e($pago['plan']) ?></div>
                        <div class="dato suave" style="font-size:.85rem">
                            <?= e(etiquetaCiclo((string) $pago['billing_cycle'])) ?>
                        </div>
                    </div>
                    <div>
                        <div class="rotulo">Forma de pago</div>
                        <div class="dato"><?= e(iconoMetodo($pago['method']) . ' ' . etiquetaMetodo($pago['method'])) ?></div>
                    </div>
                    <div>
                        <div class="rotulo">Comprobante</div>
                        <div class="dato suave"><?= e($pago['proof_reference'] ?: '—') ?></div>
                    </div>
                    <?php
                    /*
                     * De qué pasarela vino y qué dijo, con sus palabras.
                     *
                     * `provider_status` guarda el estado CRUDO —«approved»,
                     * «in_process», «charged_back»— y no el nuestro. Es lo
                     * que hay que citar al abrir un caso con el soporte de
                     * la pasarela, y lo que distingue un «pendiente porque
                     * el PSE va lento» de un «pendiente porque el aviso
                     * nunca llegó».
                     */
                    ?>
                    <?php if ($pago['provider']): ?>
                        <div>
                            <div class="rotulo">Pasarela</div>
                            <div class="dato"><?= e($pago['provider']) ?></div>
                            <?php if (!empty($pago['provider_status'])): ?>
                                <div class="dato suave" style="font-size:.85rem">
                                    informó «<?= e($pago['provider_status']) ?>»
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>

                    <div>
                        <div class="rotulo">Referencia de la pasarela</div>
                        <div class="dato suave"><?= e($pago['provider_reference'] ?: '—') ?></div>
                    </div>
                    <?php if ($pago['confirmed_at']): ?>
                        <div>
                            <div class="rotulo">Confirmado</div>
                            <div class="dato"><?= e(date('d/m/Y H:i', strtotime($pago['confirmed_at']))) ?></div>
                            <div class="dato suave" style="font-size:.85rem">
                                por <?= e($pago['confirmado_por'] ?: 'la pasarela') ?>
                            </div>
                        </div>
                    <?php endif; ?>
                    <?php if ($suscripcion): ?>
                        <div>
                            <div class="rotulo">Acceso concedido</div>
                            <div class="dato">
                                hasta <?= e($suscripcion['expires_at'] ? date('d/m/Y', strtotime($suscripcion['expires_at'])) : 'sin límite') ?>
                            </div>
                            <div class="dato suave" style="font-size:.85rem">
                                <?= e($suscripcion['plan']) ?> ·
                                <?= $suscripcion['status'] === 'active' ? 'vigente' : e($suscripcion['status']) ?>
                            </div>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- ── Edición ────────────────────────────────────────────── -->
        <div class="caja">
            <div class="cabeza">
                <h2>Editar</h2>
                <?php if (!$pendiente): ?>
                    <span class="distintivo gris">monto bloqueado</span>
                <?php endif; ?>
            </div>
            <div class="cuerpo">

                <?php if (!$pendiente): ?>
                    <p class="aviso-suave" style="margin-bottom:16px">
                        El monto y la modalidad solo se pueden corregir mientras el pago está
                        <b>pendiente</b>. Cambiarlos después reescribiría un informe contable ya
                        emitido: si hay que rectificar, se reembolsa y se registra un cobro nuevo.
                    </p>
                <?php endif; ?>

                <form method="post" class="formulario">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="guardar">

                    <div class="pareja">
                        <div class="campo">
                            <label for="f-monto">Monto (COP)</label>
                            <input id="f-monto" name="amount_cop" type="number" min="0" step="500"
                                   value="<?= (int) $pago['amount_cop'] ?>"
                                   <?= $pendiente ? '' : 'disabled' ?>>
                            <?php if ($pendiente): ?>
                                <p class="ayuda">
                                    Ajústalo si la transferencia llegó con una diferencia por comisión.
                                </p>
                            <?php endif; ?>
                        </div>

                        <div class="campo">
                            <label for="f-ciclo">Modalidad</label>
                            <select id="f-ciclo" name="billing_cycle" <?= $pendiente ? '' : 'disabled' ?>>
                                <option value="yearly"  <?= $pago['billing_cycle'] === 'yearly'  ? 'selected' : '' ?>>Anual</option>
                                <option value="monthly" <?= $pago['billing_cycle'] === 'monthly' ? 'selected' : '' ?>>Mensual</option>
                            </select>
                        </div>
                    </div>

                    <div class="pareja">
                        <div class="campo">
                            <label for="f-metodo">Forma de pago</label>
                            <select id="f-metodo" name="method">
                                <?php foreach (metodosPago() as $k => $m): ?>
                                    <option value="<?= e($k) ?>" <?= $pago['method'] === $k ? 'selected' : '' ?>>
                                        <?= e($m['icono'] . ' ' . $m['etiqueta']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="campo">
                            <label for="f-comprobante">Nº de comprobante</label>
                            <input id="f-comprobante" name="proof_reference" type="text" maxlength="120"
                                   value="<?= e($pago['proof_reference'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="pareja">
                        <div class="campo">
                            <label for="f-pasarela">Pasarela</label>
                            <input id="f-pasarela" name="provider" type="text" maxlength="40"
                                   value="<?= e($pago['provider'] ?? '') ?>" placeholder="manual, wompi, payu…">
                        </div>

                        <div class="campo">
                            <label for="f-pref">Referencia de la pasarela</label>
                            <input id="f-pref" name="provider_reference" type="text" maxlength="120"
                                   value="<?= e($pago['provider_reference'] ?? '') ?>">
                            <p class="ayuda">Solo el identificador de la transacción. Nunca datos de tarjeta.</p>
                        </div>
                    </div>

                    <div class="separador"></div>

                    <div class="trio">
                        <div class="campo">
                            <label for="f-nombre">Nombre de facturación</label>
                            <input id="f-nombre" name="payer_name" type="text" maxlength="120"
                                   value="<?= e($pago['payer_name'] ?? '') ?>">
                        </div>
                        <div class="campo">
                            <label for="f-correo">Correo de facturación</label>
                            <input id="f-correo" name="payer_email" type="email" maxlength="190"
                                   value="<?= e($pago['payer_email'] ?? '') ?>">
                        </div>
                        <div class="campo">
                            <label for="f-doc">Documento / NIT</label>
                            <input id="f-doc" name="payer_document" type="text" maxlength="40"
                                   value="<?= e($pago['payer_document'] ?? '') ?>">
                        </div>
                    </div>

                    <div class="campo">
                        <label for="f-notas">Nota interna</label>
                        <textarea id="f-notas" name="notes" maxlength="2000"><?= e($pago['notes'] ?? '') ?></textarea>
                        <p class="ayuda">Solo la ve el equipo. El cliente nunca la lee.</p>
                    </div>

                    <div class="pie-formulario">
                        <button class="btn-mini solido" type="submit">Guardar cambios</button>
                        <a class="btn-mini derecha" href="<?= e(url('admin/pagos/')) ?>">Volver al listado</a>
                    </div>
                </form>
            </div>
        </div>

        <!-- ── Otros pagos del cliente ────────────────────────────── -->
        <?php if ($otros): ?>
            <div class="caja">
                <div class="cabeza"><h2>Otros cobros de <?= e($pago['usuario']) ?></h2></div>
                <div class="tabla-envoltorio">
                    <table class="tabla">
                        <thead><tr><th>Referencia</th><th>Plan</th><th class="cifra">Monto</th><th>Estado</th><th class="cifra">Fecha</th></tr></thead>
                        <tbody>
                            <?php foreach ($otros as $o): ?>
                                <?php $eo = etiquetaEstadoPago($o['status']); ?>
                                <tr>
                                    <td class="compacta">
                                        <a href="<?= e(url('admin/pagos/ver.php?id=' . (int) $o['id'])) ?>"
                                           style="font-family:ui-monospace,Consolas,monospace;font-size:.82rem;color:var(--azul)">
                                            <?= e($o['reference']) ?>
                                        </a>
                                    </td>
                                    <td class="compacta"><?= e($o['plan']) ?></td>
                                    <td class="cifra"><?= e(precioCop((int) $o['amount_cop'])) ?></td>
                                    <td class="compacta">
                                        <span class="distintivo <?= e($eo['color']) ?>"><?= e($eo['etiqueta']) ?></span>
                                    </td>
                                    <td class="cifra" style="color:var(--texto-tenue);font-size:.82rem">
                                        <?= e(date('d/m/Y', strtotime($o['created_at']))) ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>

    <!-- ── Columna derecha: decidir e historia ───────────────────── -->
    <div>

        <?php
        /*
         * Preguntarle a la pasarela antes de decidir a mano.
         *
         * Va ARRIBA del bloque de «Decidir» a propósito: si el pago se
         * puede resolver preguntando, eso es mejor que confirmarlo a ojo.
         * Confirmar a mano un pago de pasarela sin comprobarlo es conceder
         * acceso sin saber si el dinero llegó.
         *
         * Se ofrece también sobre pagos ya cerrados: sirve para consultar
         * un reembolso o un contracargo que se procesó del lado de la
         * pasarela y del que aquí no nos enteramos.
         */
        ?>
        <?php if (pasarelaActiva() === 'mercadopago'): ?>
            <div class="caja">
                <div class="cabeza"><h2>Preguntarle a Mercado Pago</h2></div>
                <div class="cuerpo">

                    <p style="color:var(--texto);font-size:.88rem;line-height:1.6;margin-bottom:14px">
                        Consulta el estado real de <b><?= e($pago['reference']) ?></b> en Mercado
                        Pago y lo aplica si cuadra el monto. Úsalo cuando el cliente diga que
                        pagó y el cobro siga pendiente: casi siempre es un aviso que se perdió.
                    </p>

                    <form method="post">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="sincronizar">
                        <button class="btn-mini" type="submit" style="width:100%;justify-content:center">
                            🔄 Consultar estado en Mercado Pago
                        </button>
                    </form>

                    <p style="color:var(--texto-tenue);font-size:.82rem;line-height:1.55;margin-top:12px">
                        No concede nada por sí solo: comprueba el monto y la moneda igual que
                        el aviso automático, y deja constancia en la historia.
                    </p>
                </div>
            </div>
        <?php endif; ?>

        <div class="caja">
            <div class="cabeza"><h2>Decidir</h2></div>
            <div class="cuerpo">

                <?php if ($pendiente): ?>

                    <?php if ($pago['method'] === 'pasarela'): ?>
                        <div class="aviso info" style="margin-bottom:16px">
                            Este cobro es de pasarela. <b>Antes de confirmarlo a mano</b>,
                            pregúntale a Mercado Pago con el botón de arriba: confirmar a ojo
                            un pago con tarjeta es dar acceso sin saber si el dinero llegó.
                        </div>
                    <?php endif; ?>

                    <form method="post" style="margin-bottom:18px"
                          onsubmit="return confirm('¿Confirmar <?= e($pago['reference']) ?> por <?= e(precioCop((int) $pago['amount_cop'])) ?>?\n\nSe concede el acceso de inmediato.')">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="confirmar">
                        <div class="campo" style="margin-bottom:11px">
                            <label for="c-ref">Referencia de la transacción <span style="font-weight:400;color:var(--texto-tenue)">(opcional)</span></label>
                            <input id="c-ref" name="provider_reference" type="text" maxlength="120"
                                   placeholder="la del extracto del banco">
                        </div>
                        <button class="btn-mini solido" type="submit" style="width:100%;justify-content:center">
                            ✅ Confirmar y dar acceso
                        </button>
                    </form>

                    <div class="separador"></div>

                    <form method="post" style="margin-top:16px">
                        <?= campoCsrf() ?>
                        <div class="campo" style="margin-bottom:11px">
                            <label for="r-motivo">Motivo</label>
                            <textarea id="r-motivo" name="motivo" required maxlength="600"
                                      placeholder="Qué pasó. Lo va a leer quien atienda la llamada del cliente."></textarea>
                        </div>
                        <div style="display:flex;gap:8px;flex-wrap:wrap">
                            <button class="btn-mini peligro" type="submit" name="accion" value="rechazar">
                                ❌ Rechazar
                            </button>
                            <button class="btn-mini" type="submit" name="accion" value="anular">
                                ⊘ Anular
                            </button>
                        </div>
                        <p class="ayuda" style="margin-top:9px">
                            <b>Rechazar</b>: el dinero no llegó o llegó mal.<br>
                            <b>Anular</b>: el cliente desistió y nadie transfirió nada.
                        </p>
                    </form>

                <?php elseif ($confirmado): ?>

                    <p class="aviso-suave" style="margin-bottom:15px">
                        Este cobro está <b>confirmado</b> y concedió el acceso. Un pago confirmado
                        no se rechaza: se reembolsa, y el reembolso <b>retira el acceso</b>.
                    </p>

                    <form method="post"
                          onsubmit="return confirm('¿Registrar el reembolso de <?= e($pago['reference']) ?>?\n\nSe cancelará la suscripción y el cliente volverá al plan gratuito.')">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="reembolsar">
                        <div class="campo" style="margin-bottom:11px">
                            <label for="rb-motivo">Motivo del reembolso</label>
                            <textarea id="rb-motivo" name="motivo" required maxlength="600"></textarea>
                        </div>
                        <button class="btn-mini peligro" type="submit" style="width:100%;justify-content:center">
                            ↩️ Registrar reembolso
                        </button>
                    </form>

                <?php else: ?>

                    <p class="aviso-suave">
                        Este cobro está <b><?= e(mb_strtolower($estado['etiqueta'])) ?></b> y ya no admite
                        cambios de estado. Si el cliente vuelve a intentarlo, se registra un cobro nuevo:
                        reabrir uno cerrado borraría el rastro de por qué se cerró.
                    </p>

                <?php endif; ?>
            </div>
        </div>

        <div class="caja">
            <div class="cabeza"><h2>Historia</h2></div>
            <div class="cuerpo">

                <ol class="linea-tiempo">
                    <?php foreach ($eventos as $ev): ?>
                        <li class="<?= e($ev['type']) ?>">
                            <span class="que"><?= e(ucfirst($ev['type'])) ?></span>
                            <?php if ($ev['detail']): ?>
                                <span class="detalle"><?= e($ev['detail']) ?></span>
                            <?php endif; ?>
                            <span class="cuando">
                                <?= e(date('d/m/Y H:i', strtotime($ev['created_at']))) ?>
                                · <?= e($ev['actor'] ?: 'sistema') ?>
                            </span>
                        </li>
                    <?php endforeach; ?>
                </ol>

                <div class="separador"></div>

                <form method="post" style="margin-top:14px">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="anotar">
                    <div class="campo" style="margin-bottom:10px">
                        <label for="n-nota">Añadir una nota</label>
                        <textarea id="n-nota" name="nota" required maxlength="600"
                                  placeholder="«El cliente avisó que transfiere el viernes.»"></textarea>
                    </div>
                    <button class="btn-mini" type="submit">Anotar</button>
                </form>
            </div>
        </div>

    </div>
</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
