<?php
/**
 * planes/pagar.php — La pantalla donde se paga
 *
 * Muestra una referencia concreta y responde tres preguntas: cuánto,
 * a dónde, y qué pasa después.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REFERENCIA ES EL CENTRO DE TODO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Una transferencia bancaria llega al extracto como un nombre y un
 * monto, y nada más. Si dos familias transfieren $99.000 el mismo día,
 * no hay forma de saber cuál es cuál. Por eso la referencia se pide
 * escrita en la descripción de la transferencia, se muestra en grande
 * y se repite en cada paso.
 *
 * Cuando el cliente marca «ya transferí», el pago NO se confirma solo:
 * se queda esperando. Confiar en el clic del comprador sería regalar
 * el catálogo a quien pulse el botón sin pagar. Lo que sí hace ese
 * clic es avisar al administrador y guardar el número de comprobante,
 * que es justo lo que hace falta para cuadrarlo con el banco.
 *
 * Cuando haya una pasarela conectada, esta misma pantalla lleva al
 * formulario del proveedor y la confirmación entra sola por el webhook.
 * El resto —la referencia, los estados, la espera— no cambia.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

// Quien entró tocando su nombre en la lista de clase no puede pagar.
exigirSesionPlena();

$usuario = usuarioActual();

$ref  = get('ref');
$pago = $ref !== '' && pagosInstalados() ? pagoPorReferencia($ref) : null;

// Un pago solo lo ve su dueño. Sin esta comprobación, cambiar la
// referencia en la barra de direcciones enseñaría los datos de
// facturación de otra persona.
if (!$pago || (int) $pago['user_id'] !== (int) $usuario['id']) {
    mensaje('error', 'No encontramos ese pago en tu cuenta.');
    redirigir('planes/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    if ($pago['status'] !== PAGO_PENDIENTE) {
        redirigir('planes/pagar.php?ref=' . urlencode($pago['reference']));
    }

    /*
     * Ir a la pasarela. La preferencia se crea AQUÍ, en el servidor y en
     * el momento de pulsar: si el monto viajara por el navegador —en un
     * campo oculto, por ejemplo— el navegador podría cambiarlo.
     *
     * Se crea una nueva cada vez que se pulsa, y no pasa nada: la clave
     * de idempotencia va atada a la referencia, así que Mercado Pago
     * devuelve la misma y no se cobra dos veces.
     */
    if (post('accion') === 'pasarela') {

        if (pasarelaActiva() !== 'mercadopago') {
            mensaje('error', 'El pago con tarjeta no está disponible ahora mismo.');
            redirigir('planes/pagar.php?ref=' . urlencode($pago['reference']));
        }

        $checkout = mpCrearPreferencia($pago);

        if (!$checkout['ok']) {
            // Al cliente no se le enseña el error de la API —puede citar
            // credenciales o campos internos— pero queda entero en el log
            // y en la historia del pago, que es donde sirve.
            error_log('[pagar] preferencia fallida para ' . $pago['reference'] . ': ' . $checkout['error']);

            registrarEventoPago(
                (int) $pago['id'],
                'nota',
                'No se pudo crear el checkout de Mercado Pago: ' . $checkout['error'],
                (int) $usuario['id']
            );

            mensaje('error', 'No pudimos abrir la pasarela de pago. Inténtalo en un momento '
                           . 'o paga por transferencia.');
            redirigir('planes/pagar.php?ref=' . urlencode($pago['reference']));
        }

        actualizarPago((int) $pago['id'], [
            'method'   => 'pasarela',
            'provider' => 'mercadopago',
        ], (int) $usuario['id']);

        registrarEventoPago(
            (int) $pago['id'],
            'nota',
            'El cliente fue enviado al checkout de Mercado Pago'
                . ($checkout['preferencia'] !== '' ? ' (preferencia ' . $checkout['preferencia'] . ')' : '') . '.',
            (int) $usuario['id']
        );

        // A Mercado Pago. La URL viene de su API, no del cliente.
        redirigir($checkout['url']);
    }

    if (post('accion') === 'aviso') {

        $comprobante = trim(post('comprobante'));

        actualizarPago((int) $pago['id'], ['proof_reference' => $comprobante], (int) $usuario['id']);

        registrarEventoPago(
            (int) $pago['id'],
            'nota',
            'El cliente avisó que ya transfirió'
                . ($comprobante !== '' ? '. Comprobante: ' . $comprobante : '.'),
            (int) $usuario['id']
        );

        mensaje('ok', 'Aviso recibido. Lo revisamos y te activamos el acceso.');
        redirigir('planes/pagar.php?ref=' . urlencode($pago['reference']));
    }

    if (post('accion') === 'anular') {
        anularPago((int) $pago['id'], 'El cliente canceló la contratación desde la web.', (int) $usuario['id']);
        mensaje('info', 'Contratación cancelada. No se cobró nada.');
        redirigir('planes/');
    }
}

$datos     = datosRecaudo();
$estado    = etiquetaEstadoPago($pago['status']);
$pendiente = $pago['status'] === PAGO_PENDIENTE;
$avisado   = (string) ($pago['proof_reference'] ?? '') !== '';

$titulo        = 'Pago ' . $pago['reference'];
$seccionActiva = 'planes';
$hojasExtra    = ['assets/css/pago.css'];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:660px">

        <?php if ($pago['status'] === PAGO_CONFIRMADO): ?>

            <?php /* ── Confirmado ─────────────────────────────────── */ ?>
            <div class="bloque espera">
                <span class="icono" aria-hidden="true">🎉</span>
                <h2>¡Listo! Ya tienes el catálogo completo</h2>
                <p>
                    Confirmamos tu pago de <b><?= e(precioCop((int) $pago['amount_cop'])) ?></b>
                    y el plan <b><?= e($pago['plan']) ?></b> está activo.
                </p>
                <?php
                $v = vencimientoSuscripcion();
                if ($v):
                    ?>
                    <p>Tu acceso llega hasta el <b><?= e(fechaLarga($v)) ?></b>.</p>
                <?php endif; ?>
                <div style="margin-top:22px;display:flex;gap:10px;justify-content:center;flex-wrap:wrap">
                    <a class="btn btn-principal" href="<?= e(url('actividades/')) ?>">Ir al catálogo</a>
                    <a class="btn btn-secundario" href="<?= e(url('usuario/pagos.php')) ?>">Ver mis pagos</a>
                </div>
            </div>

        <?php elseif (!$pendiente): ?>

            <?php /* ── Cerrado sin éxito ──────────────────────────── */ ?>
            <div class="bloque espera">
                <span class="icono" aria-hidden="true"><?= e($estado['icono']) ?></span>
                <h2>Este pago está <?= e(mb_strtolower($estado['etiqueta'])) ?></h2>
                <p>
                    La referencia <b><?= e($pago['reference']) ?></b> ya no está activa.
                    Si crees que es un error, escríbenos
                    <?php if ($datos['correo'] !== ''): ?>
                        a <b><?= e($datos['correo']) ?></b>
                    <?php endif; ?>
                    con ese número a mano y lo revisamos.
                </p>
                <div style="margin-top:22px">
                    <a class="btn btn-principal" href="<?= e(url('planes/')) ?>">Volver a los planes</a>
                </div>
            </div>

        <?php else: ?>

            <?php /* ── Pendiente: la pantalla de pagar ────────────── */ ?>

            <ol class="pasos-pago">
                <li class="hecho"><span class="num">✓</span> Plan elegido</li>
                <li class="<?= $avisado ? 'hecho' : 'ahora' ?>">
                    <span class="num"><?= $avisado ? '✓' : '2' ?></span> Pagar
                </li>
                <li class="<?= $avisado ? 'ahora' : '' ?>">
                    <span class="num">3</span> Se activa el acceso
                </li>
            </ol>

            <div class="recuadro-pago">
                <div class="franja">
                    <div>
                        <span class="rotulo">Referencia de tu pago</span>
                        <span class="valor"><?= e($pago['reference']) ?></span>
                    </div>
                    <div style="text-align:right">
                        <span class="rotulo">Total a transferir</span>
                        <span class="monto"><?= e(precioCop((int) $pago['amount_cop'])) ?></span>
                    </div>
                </div>

                <div class="cuerpo">
                    <div class="linea-dato">
                        <span>Plan</span>
                        <b><?= e($pago['plan']) ?> · <?= e(etiquetaCiclo((string) $pago['billing_cycle'])) ?></b>
                    </div>
                    <div class="linea-dato">
                        <span>A nombre de</span>
                        <b><?= e($pago['payer_name'] ?: $usuario['name']) ?></b>
                    </div>
                    <div class="linea-dato">
                        <span>Creado</span>
                        <b><?= e(fechaLarga($pago['created_at'])) ?></b>
                    </div>
                </div>
            </div>

            <?php
            /*
             * ── Pagar en el momento ───────────────────────────────────
             *
             * Va primero porque es lo que resuelve el pago en un minuto.
             * La transferencia queda debajo, no escondida: en Colombia
             * mucha familia la prefiere, y ofrecer solo tarjeta pierde
             * clientes tan silenciosamente como ofrecer solo transferencia.
             */
            ?>
            <?php if (pasarelaActiva() === 'mercadopago'): ?>

                <div class="bloque bloque-pasarela">
                    <h2>Pagar ahora</h2>
                    <p style="color:var(--texto);line-height:1.65;margin:9px 0 16px">
                        Con tarjeta de crédito o débito, PSE, o el saldo de tu cuenta de
                        Mercado Pago. El acceso se activa apenas se apruebe.
                    </p>

                    <ul class="medios-pago">
                        <li><span aria-hidden="true">💳</span> Tarjetas</li>
                        <li><span aria-hidden="true">🏦</span> PSE</li>
                        <li><span aria-hidden="true">📱</span> Mercado Pago</li>
                    </ul>

                    <?php if (mpEsPruebas()): ?>
                        <div class="aviso info">
                            <b>Ambiente de pruebas.</b> Este cobro no mueve dinero real.
                        </div>
                    <?php endif; ?>

                    <form method="post">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="pasarela">
                        <button class="btn btn-principal btn-bloque" type="submit">
                            Pagar <?= e(precioCop((int) $pago['amount_cop'])) ?> con Mercado Pago
                        </button>
                    </form>

                    <p class="fino" style="margin-top:12px">
                        Te llevamos al sitio de Mercado Pago. Los datos de tu tarjeta se
                        escriben allí: nosotros no los recibimos ni los guardamos.
                    </p>
                </div>

                <?php if (manualActivo() && recaudoConfigurado()): ?>
                    <div class="separador-o"><span>o si prefieres</span></div>
                <?php endif; ?>

            <?php endif; ?>

            <?php if (!manualActivo()): ?>

                <?php /* Solo pasarela: no hay nada más que enseñar aquí. */ ?>

            <?php elseif (!recaudoConfigurado()): ?>

                <?php if (!pasarelaConectada()): ?>
                    <div class="aviso mal">
                        <b>Los datos de la cuenta todavía no están publicados.</b>
                        Tu referencia <b><?= e($pago['reference']) ?></b> ya está guardada:
                        escríbenos con ese número y te decimos cómo pagar.
                    </div>
                <?php endif; ?>

            <?php else: ?>

                <div class="bloque">
                    <h2>A dónde transferir</h2>

                    <?php if ($datos['instrucciones'] !== ''): ?>
                        <p style="color:var(--texto);line-height:1.65;margin:10px 0 16px">
                            <?= e($datos['instrucciones']) ?>
                        </p>
                    <?php endif; ?>

                    <?php if ($pago['method'] === 'nequi' && $datos['nequi'] !== ''): ?>
                        <div class="linea-dato"><span>Nequi / Daviplata</span>
                            <b class="mono"><?= e($datos['nequi']) ?></b></div>
                        <div class="linea-dato"><span>A nombre de</span>
                            <b><?= e($datos['titular']) ?></b></div>
                    <?php else: ?>
                        <div class="linea-dato"><span>Banco</span>
                            <b><?= e($datos['banco']) ?></b></div>
                        <div class="linea-dato"><span>Tipo de cuenta</span>
                            <b><?= e($datos['tipo_cuenta']) ?></b></div>
                        <div class="linea-dato"><span>Número de cuenta</span>
                            <b class="mono"><?= e($datos['cuenta']) ?></b></div>
                        <div class="linea-dato"><span>Titular</span>
                            <b><?= e($datos['titular']) ?></b></div>
                        <?php if ($datos['documento'] !== ''): ?>
                            <div class="linea-dato"><span>NIT / cédula</span>
                                <b class="mono"><?= e($datos['documento']) ?></b></div>
                        <?php endif; ?>
                        <?php if ($datos['nequi'] !== ''): ?>
                            <div class="linea-dato"><span>También por Nequi</span>
                                <b class="mono"><?= e($datos['nequi']) ?></b></div>
                        <?php endif; ?>
                    <?php endif; ?>

                    <div class="nota-importante">
                        Escribe <b><?= e($pago['reference']) ?></b> en la descripción de la
                        transferencia. Es lo único que nos permite reconocer que ese dinero es
                        tuyo: al banco solo le llega un nombre y un monto, y varias familias
                        pueden transferir lo mismo el mismo día.
                    </div>
                </div>

            <?php endif; ?>

            <?php
            /*
             * El «ya transferí» solo tiene sentido si se ofrece la
             * transferencia. Con solo pasarela, un botón que dice «avísanos
             * que pagaste» invitaría a reclamar un acceso que la pasarela
             * concede sola.
             */
            ?>
            <?php if (!manualActivo() || !recaudoConfigurado()): ?>

                <?php /* Nada: el aviso manual no aplica. */ ?>

            <?php elseif ($avisado): ?>

                <div class="bloque espera">
                    <span class="icono" aria-hidden="true">⏳</span>
                    <h2>Recibimos tu aviso</h2>
                    <p>
                        Estamos comprobando la transferencia contra el extracto del banco.
                        En cuanto la veamos, tu plan se activa solo y te llega el acceso —
                        normalmente en menos de 24 horas hábiles.
                    </p>
                    <p style="color:var(--texto-tenue);font-size:.88rem;margin-top:12px">
                        Guarda tu referencia: <b><?= e($pago['reference']) ?></b>
                    </p>
                </div>

            <?php else: ?>

                <form method="post" class="bloque">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="aviso">

                    <h2>Ya transferí</h2>
                    <p style="color:var(--texto);line-height:1.6;margin:9px 0 16px">
                        Avísanos y lo revisamos. El acceso <b>no</b> se activa con este botón:
                        se activa cuando vemos el dinero en la cuenta.
                    </p>

                    <div class="campo-simple">
                        <label for="comp">Número de comprobante</label>
                        <input id="comp" name="comprobante" type="text" maxlength="120" required
                               placeholder="el que te dio el banco o la app">
                        <p class="pista">
                            Con este número encontramos tu transferencia en minutos en vez de en horas.
                        </p>
                    </div>

                    <button class="btn btn-principal" type="submit" style="margin-top:18px;width:100%">
                        Avisar que ya pagué
                    </button>
                </form>

            <?php endif; ?>

            <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:8px">
                <a class="btn btn-secundario" href="<?= e(url('usuario/pagos.php')) ?>">Mis pagos</a>

                <form method="post" style="display:inline"
                      onsubmit="return confirm('¿Cancelar esta contratación?\n\nNo se cobra nada. Puedes empezar de nuevo cuando quieras.')">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="anular">
                    <button class="btn btn-secundario" type="submit">Cancelar la contratación</button>
                </form>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
