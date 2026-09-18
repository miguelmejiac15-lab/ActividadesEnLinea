<?php
/**
 * planes/retorno.php — Vuelta desde Mercado Pago
 *
 * Aquí aterriza el cliente después de pagar. Su único trabajo es
 * contarle qué pasó y, si hace falta, dejarle esperar tranquilo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  ESTA PÁGINA NO CONCEDE NADA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Mercado Pago devuelve al cliente con parámetros en la dirección:
 *
 *     ?payment_id=123&status=approved&external_reference=AEL-2026-000123
 *
 * Eso lo puede escribir cualquiera en la barra del navegador. Si esta
 * página concediera acceso al leer `status=approved`, el catálogo entero
 * sería gratis para quien supiera teclear la URL.
 *
 * Así que el estado se lee SIEMPRE de nuestra base de datos, nunca de la
 * dirección.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  PERO SÍ PREGUNTA, Y ESO ES OTRA COSA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Hay un hueco real: Mercado Pago redirige al cliente en cuanto aprueba,
 * y su aviso al webhook puede tardar unos segundos más. El cliente pagó y
 * ve «pendiente». Es el momento exacto en el que la gente escribe a
 * soporte.
 *
 * Cuando eso pasa, esta página le pregunta a la API de Mercado Pago por
 * el id que trae la dirección. Y aquí está la diferencia con el párrafo
 * anterior: **el id no se cree, se usa para preguntar.** La respuesta la
 * da Mercado Pago autenticado con nuestro token, y pasa por las mismas
 * comprobaciones de monto y moneda que el webhook. Un id inventado no
 * lleva a ninguna parte.
 *
 * Esto además hace que el cobro funcione aunque el webhook no llegue
 * nunca —el caso de un XAMPP en localhost, donde Mercado Pago no puede
 * llamarnos—, y por eso también sirve como red de seguridad en
 * producción si el webhook falla.
 *
 * El pago solo se puede sincronizar si es del usuario que está mirando.
 * Un id ajeno se ignora sin decir nada: confirmar el pago de otro no le
 * daría acceso a nadie —la suscripción va a su dueño— pero sí revelaría
 * que ese pago existe.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();
exigirSesionPlena();

$usuario = usuarioActual();

$ref  = get('ref');
$pago = $ref !== '' && pagosInstalados() ? pagoPorReferencia($ref) : null;

// Un pago solo lo ve su dueño.
if (!$pago || (int) $pago['user_id'] !== (int) $usuario['id']) {
    mensaje('error', 'No encontramos ese pago en tu cuenta.');
    redirigir('planes/');
}

/*
 * Mercado Pago llama al id de la transacción de dos formas según por
 * dónde vuelva el cliente. Se aceptan las dos.
 */
$idPasarela = trim(get('payment_id') ?: get('collection_id'));

$sincronizado = false;
$avisoSync    = null;

if ($pago['status'] === PAGO_PENDIENTE
    && $idPasarela !== ''
    && pasarelaActiva() === 'mercadopago') {

    $verificado = verificarPagoMp($idPasarela);

    if (!$verificado['ok']) {
        /*
         * No se le enseña el motivo al cliente: puede ser «el monto no
         * cuadra», que es información de nuestro sistema y además, si
         * alguien está probando ids, una pista. Queda en el log y —si el
         * pago es identificable— en su historia.
         */
        error_log('[retorno] ' . $idPasarela . ': ' . $verificado['error']);

        if (($verificado['pago'] ?? null) !== null) {
            registrarEventoPago(
                (int) $verificado['pago']['id'],
                'webhook',
                'Sincronización desde el retorno RECHAZADA: ' . $verificado['error'],
                null
            );
        }

    } elseif ((int) $verificado['pago']['user_id'] !== (int) $usuario['id']) {

        // Un id que resuelve al pago de otra persona. Se ignora.
        error_log('[retorno] id ' . $idPasarela . ' resuelve a un pago ajeno.');

    } else {

        $resultado = aplicarAvisoPasarela(
            (string) $verificado['referencia'],
            (string) $verificado['estado'],
            ['provider' => 'mercadopago', 'provider_reference' => $verificado['transaccion']]
        );

        registrarEventoPago(
            (int) $pago['id'],
            'nota',
            'El cliente volvió de Mercado Pago; se consultó el estado y respondió «'
                . $verificado['estado'] . '».',
            (int) $usuario['id']
        );

        $sincronizado = $resultado['ok'];

        // Se relee: `aplicarAvisoPasarela()` pudo cambiarlo.
        $pago = pagoPorReferencia((string) $pago['reference']);

        if (!$sincronizado && $pago['status'] === PAGO_PENDIENTE) {
            $avisoSync = 'Mercado Pago todavía está procesando el pago.';
        }
    }
}

$estado = etiquetaEstadoPago($pago['status']);

$titulo        = 'Pago ' . $pago['reference'];
$seccionActiva = 'planes';
$hojasExtra    = ['assets/css/pago.css'];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:660px">

        <?php if ($pago['status'] === PAGO_CONFIRMADO): ?>

            <div class="bloque espera">
                <span class="icono" aria-hidden="true">🎉</span>
                <h2>¡Listo! Ya tienes el catálogo completo</h2>
                <p>
                    Recibimos tu pago de <b><?= e(precioCop((int) $pago['amount_cop'])) ?></b>
                    con la referencia <b><?= e($pago['reference']) ?></b>.
                </p>
                <p>Tu acceso ya está activo. No hay que hacer nada más.</p>

                <div class="acciones">
                    <a class="btn btn-principal" href="<?= e(url('actividades/')) ?>">
                        Empezar a jugar
                    </a>
                    <a class="btn btn-secundario" href="<?= e(url('usuario/')) ?>">
                        Ver mi cuenta
                    </a>
                </div>
            </div>

        <?php elseif ($pago['status'] === PAGO_RECHAZADO): ?>

            <div class="bloque espera">
                <span class="icono" aria-hidden="true">😕</span>
                <h2>El pago no se pudo completar</h2>
                <p>
                    Mercado Pago rechazó la transacción de la referencia
                    <b><?= e($pago['reference']) ?></b>. <strong>No se te cobró nada.</strong>
                </p>
                <p>
                    Suele ser cosa del banco: fondos, un límite de compra por internet o
                    un dato mal escrito. Puedes intentarlo otra vez, con la misma tarjeta
                    o con otra forma de pago.
                </p>

                <div class="acciones">
                    <a class="btn btn-principal" href="<?= e(url('planes/')) ?>">
                        Intentarlo de nuevo
                    </a>
                </div>
            </div>

        <?php elseif (in_array($pago['status'], [PAGO_ANULADO, PAGO_REEMBOLSADO], true)): ?>

            <div class="bloque espera">
                <span class="icono" aria-hidden="true">↩️</span>
                <h2><?= e($estado['etiqueta']) ?></h2>
                <p>
                    El pago <b><?= e($pago['reference']) ?></b> quedó como
                    <b><?= e(mb_strtolower($estado['etiqueta'])) ?></b>.
                </p>
                <div class="acciones">
                    <a class="btn btn-principal" href="<?= e(url('planes/')) ?>">Ver los planes</a>
                </div>
            </div>

        <?php else: ?>

            <?php /* ── Pendiente ───────────────────────────────────── */ ?>
            <div class="bloque espera">
                <span class="icono" aria-hidden="true">⏳</span>
                <h2>Estamos confirmando tu pago</h2>

                <p>
                    Referencia <b><?= e($pago['reference']) ?></b> ·
                    <b><?= e(precioCop((int) $pago['amount_cop'])) ?></b>
                </p>

                <?php
                /*
                 * Se distingue con cuidado entre «aún no sabemos» y «se
                 * rechazó», porque para quien acaba de poner su tarjeta no
                 * es lo mismo ni de lejos. Mientras no haya una respuesta
                 * clara, se dice que no la hay.
                 */
                ?>
                <p>
                    <?php if ($avisoSync !== null): ?>
                        <?= e($avisoSync) ?>
                    <?php else: ?>
                        Algunos medios de pago —PSE, efectivo— tardan unos minutos en
                        confirmarse, y otros unas horas.
                    <?php endif; ?>
                    En cuanto llegue la confirmación, tu acceso se activa solo y te
                    llega el aviso.
                </p>

                <p class="fino">
                    <strong>No pagues otra vez.</strong> Si vuelves a pagar se cobrarían
                    dos veces y habría que devolverte una.
                </p>

                <div class="acciones">
                    <a class="btn btn-principal"
                       href="<?= e(url('planes/retorno.php?ref=' . urlencode((string) $pago['reference'])
                                       . ($idPasarela !== '' ? '&payment_id=' . urlencode($idPasarela) : ''))) ?>">
                        Volver a comprobar
                    </a>
                    <a class="btn btn-secundario" href="<?= e(url('usuario/')) ?>">
                        Ir a mi cuenta
                    </a>
                </div>
            </div>

        <?php endif; ?>

        <p class="fino" style="text-align:center;margin-top:18px">
            ¿Algo no cuadra? Escríbenos citando la referencia
            <b><?= e($pago['reference']) ?></b>.
        </p>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
