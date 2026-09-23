<?php
/**
 * api/pagar-tarjeta.php — Cobrar con la tarjeta, sin salir del sitio
 *
 * Lo llama el formulario de `planes/pagar.php` con el token que el SDK
 * de Mercado Pago generó en el navegador.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ LLEGA AQUÍ Y QUÉ NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Llega un `token` de un solo uso, el método (visa, master, …), el
 * emisor, las cuotas y el documento. **No llega el número de la
 * tarjeta**, ni el código de seguridad, ni el vencimiento: esos viven
 * en los iframes del SDK y el navegador los cambia por el token. Este
 * archivo no podría guardarlos aunque quisiera.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EL IMPORTE NO SE ACEPTA, SE BUSCA
 * ─────────────────────────────────────────────────────────────────────
 *
 * El navegador manda la referencia; el monto lo pone el servidor
 * leyendo `payments`. Si el importe viajara en la petición, bastaría
 * cambiar un número en el inspector para comprar el plan Escuela por
 * mil pesos.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  Y EL ACCESO NO LO CONCEDE ESTA RESPUESTA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Aunque Mercado Pago conteste «approved» aquí mismo, la suscripción se
 * aplica por el mismo camino que un aviso del webhook:
 * `verificarPagoMp()` vuelve a preguntar por el id, comprueba monto y
 * moneda, y solo entonces `aplicarAvisoPasarela()` concede. Es un
 * rodeo a propósito — así existe UN solo lugar donde se otorga acceso,
 * y está cubierto por las mismas comprobaciones venga de donde venga.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');

function responder(array $datos, int $codigo = 200): never
{
    http_response_code($codigo);
    echo jsonSeguro($datos);
    exit;
}

// ── Puertas ──────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['ok' => false, 'error' => 'Método no permitido.'], 405);
}

if (!haySesion() || !usuarioActual()) {
    responder(['ok' => false, 'error' => 'Tu sesión se cerró. Entra de nuevo.'], 401);
}

// Cobrar es una acción con consecuencias: exige el token de formulario.
if (!csrfValido()) {
    responder(['ok' => false, 'error' => 'La página caducó. Recárgala e inténtalo otra vez.'], 403);
}

if (!pagosInstalados() || pasarelaActiva() !== 'mercadopago') {
    responder(['ok' => false, 'error' => 'El cobro con tarjeta no está disponible.'], 503);
}

$usuario = usuarioActual();
$pago    = pagoPorReferencia(post('ref'));

/*
 * Un pago solo lo cobra su dueño. Sin esta línea, conocer una referencia
 * ajena permitiría pagarla —parece inofensivo, pero deja cobros cruzados
 * imposibles de conciliar y revela que esa referencia existe.
 */
if (!$pago || (int) $pago['user_id'] !== (int) $usuario['id']) {
    responder(['ok' => false, 'error' => 'No encontramos ese pago en tu cuenta.'], 404);
}

// Un pago ya confirmado no se vuelve a cobrar por un doble clic.
if ($pago['status'] !== PAGO_PENDIENTE) {
    responder([
        'ok'     => true,
        'estado' => $pago['status'],
        'listo'  => $pago['status'] === PAGO_CONFIRMADO,
        'aviso'  => 'Este pago ya estaba resuelto.',
    ]);
}

// ── El cobro ─────────────────────────────────────────────────────────
$r = mpCobrarConTarjeta($pago, [
    'token'          => post('token'),
    'metodo'         => post('metodo'),
    'emisor'         => post('emisor'),
    'cuotas'         => post('cuotas'),
    'documento'      => post('documento'),
    'tipo_documento' => post('tipo_documento'),
    'dispositivo'    => post('dispositivo'),
    // El correo de la cuenta, no uno que mande el navegador: es el que
    // recibe el comprobante y el que debe coincidir con la suscripción.
    'correo'         => (string) $usuario['email'],
]);

if (!$r['ok']) {
    // El detalle va al log; al cliente, algo que pueda resolver.
    error_log('[pagar-tarjeta] ' . $pago['reference'] . ': ' . $r['error']);

    registrarEventoPago((int) $pago['id'], 'nota', 'Intento con tarjeta fallido: ' . $r['error'], (int) $usuario['id']);

    responder(['ok' => false, 'error' => 'No pudimos procesar el pago. Inténtalo en un momento.'], 502);
}

$estado = (string) $r['estado'];

registrarEventoPago(
    (int) $pago['id'],
    'webhook',
    'Cobro con tarjeta: ' . $estado . ($r['detalle'] ? ' (' . $r['detalle'] . ')' : ''),
    (int) $usuario['id']
);

/*
 * El banco pide verificación. No se aplica nada todavía: el cliente
 * tiene que pasar por la pantalla de su banco y volver.
 */
if ($r['tresd'] !== null) {
    responder([
        'ok'          => true,
        'estado'      => 'verificar',
        'verificacion' => $r['tresd'],
        'transaccion' => $r['transaccion'],
    ]);
}

// ── Aplicar, por el mismo camino que el webhook ──────────────────────
$verificado = verificarPagoMp((string) $r['transaccion']);

if ($verificado['ok']) {
    aplicarAvisoPasarela($verificado['referencia'], (string) $verificado['estado'], [
        'provider'           => 'mercadopago',
        'provider_reference' => $verificado['transaccion'],
    ]);
} else {
    error_log('[pagar-tarjeta] verificación rechazada: ' . $verificado['error']);
}

$fresco = pagoPorReferencia((string) $pago['reference']);
$listo  = $fresco && $fresco['status'] === PAGO_CONFIRMADO;

if ($estado === 'approved' || $listo) {
    responder(['ok' => true, 'estado' => 'aprobado', 'listo' => $listo]);
}

if ($estado === 'in_process' || $estado === 'pending') {
    responder([
        'ok'     => true,
        'estado' => 'revisando',
        'aviso'  => 'Tu banco está revisando el pago. Te avisamos apenas responda; '
                  . 'no vuelvas a pagar.',
    ]);
}

// Rechazado.
responder([
    'ok'     => false,
    'estado' => 'rechazado',
    'error'  => mpMotivoLegible((string) $r['detalle']),
]);
