<?php
/**
 * admin/pagos/listo.php — ¿Listo para cobrar?
 *
 * La lista de comprobación de la puesta en marcha, calculada sobre el
 * estado real de la instalación. Ver `includes/puesta-en-marcha.php`.
 *
 * Separa lo que IMPIDE cobrar de lo que solo conviene, porque son dos
 * conversaciones distintas: una es «hoy no entra un peso» y la otra es
 * «esto te va a doler en un mes».
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';
require_once RUTA_INCLUDES . '/puesta-en-marcha.php';

exigirRol('admin');

// Probar la conexión de verdad: es la única comprobación que gasta una
// llamada a la API, así que va con botón y no automática.
if ($_SERVER['REQUEST_METHOD'] === 'POST' && post('accion') === 'probar') {
    exigirCsrf();

    $r = mpApi('GET', '/users/me');

    mensaje($r['ok'] ? 'ok' : 'error', $r['ok']
        ? sprintf('Conexión correcta con Mercado Pago. Cuenta «%s», país %s.',
            (string) ($r['datos']['nickname'] ?? '?'), (string) ($r['datos']['site_id'] ?? '?'))
        : 'No se pudo conectar: ' . $r['error']);

    redirigir('admin/pagos/listo.php');
}

$bloques = listaDePuestaEnMarcha();
$resumen = resumenPuestaEnMarcha($bloques);

$titulo    = 'Listo para cobrar';
$panelZona = 'listo';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>¿Listo para cobrar?</h1>
        <p>Se calcula sobre esta instalación, ahora mismo. Nada de esto cambia nada.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/pagos/ajustes.php')) ?>">Ajustes de cobro</a>
        <a class="btn-mini" href="<?= e(url('admin/pagos/')) ?>">← Cobros</a>
    </div>
</div>


<?php if ($resumen['puede_cobrar']): ?>
    <div class="aviso ok">
        <b>Se puede cobrar.</b>
        No queda nada que lo impida<?= $resumen['convienen'] > 0
            ? '; quedan ' . $resumen['convienen'] . ' cosa(s) que conviene resolver.'
            : '.' ?>
    </div>
<?php else: ?>
    <div class="aviso mal">
        <b>Todavía no entra un peso.</b>
        Hay <?= $resumen['bloquean'] ?> cosa(s) que lo impiden. Están marcadas abajo en rojo.
    </div>
<?php endif; ?>


<div class="cifras">
    <div class="cifra-caja <?= $resumen['bloquean'] > 0 ? 'rojo' : 'verde' ?>">
        <div class="n"><?= $resumen['bloquean'] ?></div>
        <div class="t">Impiden cobrar</div>
    </div>
    <div class="cifra-caja naranja">
        <div class="n"><?= $resumen['convienen'] ?></div>
        <div class="t">Conviene resolver</div>
    </div>
    <div class="cifra-caja verde">
        <div class="n"><?= $resumen['listos'] ?></div>
        <div class="t">Resueltos</div>
    </div>
    <div class="cifra-caja">
        <div class="n"><?= $resumen['total'] ?></div>
        <div class="t">Comprobaciones</div>
    </div>
</div>


<?php foreach ($bloques as $bloque): ?>
    <div class="caja">
        <div class="cabeza"><h2><?= e($bloque['titulo']) ?></h2></div>
        <div class="cuerpo">

            <?php foreach ($bloque['puntos'] as $p): ?>
                <?php
                $color = $p['estado'] === PM_LISTO ? 'var(--verde)'
                       : ($p['estado'] === PM_BLOQUEA ? 'var(--rojo)' : 'var(--naranja)');
                $icono = $p['estado'] === PM_LISTO ? '✓'
                       : ($p['estado'] === PM_BLOQUEA ? '✕' : '!');
                ?>
                <div style="display:flex;gap:14px;padding:13px 0;border-bottom:1px solid var(--borde-suave)">

                    <span style="flex:none;width:26px;height:26px;border-radius:50%;
                                 display:grid;place-items:center;font-weight:700;
                                 color:#fff;background:<?= $color ?>"
                          aria-hidden="true"><?= $icono ?></span>

                    <div style="flex:1;min-width:0">
                        <b style="color:var(--oscuro)"><?= e($p['titulo']) ?></b>

                        <?php if ($p['detalle'] !== ''): ?>
                            <div style="font-size:.84rem;color:var(--texto-tenue);margin-top:3px;
                                        font-family:ui-monospace,monospace;word-break:break-all">
                                <?= e($p['detalle']) ?>
                            </div>
                        <?php endif; ?>

                        <?php /* El «qué hacer» solo cuando hay algo que hacer. */ ?>
                        <?php if (!$p['ok']): ?>
                            <div style="font-size:.88rem;color:var(--texto);margin-top:5px;line-height:1.55">
                                <?= e($p['hacer']) ?>
                                <?php if ($p['donde'] !== ''): ?>
                                    <a href="<?= e(url($p['donde'])) ?>">Ir →</a>
                                <?php endif; ?>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>

        </div>
    </div>
<?php endforeach; ?>


<!-- ── El orden en que se hace ──────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>El orden que funciona</h2></div>
    <div class="cuerpo">

        <p style="color:var(--texto);font-size:.9rem;line-height:1.7">
            Mercado Pago se conecta en este orden, y saltarse un paso cuesta una tarde:
        </p>

        <ol style="color:var(--texto);font-size:.9rem;line-height:1.85;padding-left:22px">
            <li>
                Crear la cuenta y entrar a
                <a href="https://www.mercadopago.com.co/developers/panel" target="_blank" rel="noopener">
                    Tus integraciones</a>. Crear una <b>aplicación</b> de tipo pagos en línea.
            </li>
            <li>
                Copiar las credenciales de <b>PRUEBA</b> —access token y public key— y
                pegarlas en <a href="<?= e(url('admin/pagos/ajustes.php')) ?>">Ajustes de cobro</a>.
                Dejar encendido «ambiente de pruebas».
            </li>
            <li>
                Pulsar <b>Probar la conexión</b>. Si no responde, no sigas: lo demás
                dependerá de un token que no sirve.
            </li>
            <li>
                Publicar el sitio en una dirección pública y ponerla en
                <code>URL_BASE</code>. <b>Hasta aquí no tiene sentido dar de alta el
                webhook</b>: Mercado Pago no puede avisar a un localhost.
            </li>
            <li>
                En Mercado Pago, <b>Notificaciones → Webhooks</b>, dar de alta
                <code><?= e(url('api/webhook-pago.php')) ?></code> para el evento
                <b>Pagos</b>. Copiar la <b>clave secreta</b> que genera y pegarla en
                los ajustes.
            </li>
            <li>
                Hacer un cobro de prueba de punta a punta con las
                <a href="https://www.mercadopago.com.co/developers/es/docs/checkout-pro/additional-content/your-integrations/test/cards"
                   target="_blank" rel="noopener">tarjetas de prueba</a>, y comprobar en
                <a href="<?= e(url('admin/pagos/')) ?>">Cobros</a> que el pago quedó
                confirmado y la suscripción concedida.
            </li>
            <li>
                Solo entonces: cambiar a las credenciales de <b>producción</b>, apagar
                «ambiente de pruebas» y repetir el punto 5 (el webhook de producción tiene
                su propia clave secreta).
            </li>
        </ol>

        <div class="aviso-suave" style="margin-top:16px">
            <b>Lo que no hay que hacer nunca:</b> creer el estado que llega en la barra de
            direcciones al volver del checkout. La plataforma no lo hace —le pregunta a la
            API de Mercado Pago y además comprueba el monto y la moneda—, y por eso un
            aviso falsificado no puede regalar un plan.
        </div>

        <?php if (mpConfigurada()): ?>
            <form method="post" style="margin-top:16px">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="probar">
                <button class="btn-mini solido" type="submit">Probar la conexión ahora</button>
            </form>
        <?php endif; ?>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
