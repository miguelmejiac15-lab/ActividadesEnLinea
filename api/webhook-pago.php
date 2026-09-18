<?php
/**
 * api/webhook-pago.php — Aviso de pago de una pasarela
 *
 * Cuando una pasarela aprueba, rechaza o anula un pago, avisa a esta
 * dirección. Es el único punto por el que una máquina puede conceder
 * acceso, así que es también el que más desconfía.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE CAMBIÓ, Y POR QUÉ IMPORTA
 * ─────────────────────────────────────────────────────────────────────
 *
 * La primera versión de este archivo esperaba una cabecera `X-AEL-Firma`
 * con el HMAC del cuerpo. Era un esquema nuestro, y ninguna pasarela real
 * lo manda: tal cual estaba, habría rechazado el 100% de los avisos de
 * Mercado Pago sin que nadie entendiera por qué los pagos no se
 * activaban.
 *
 * Mercado Pago firma OTRA COSA. No firma el cuerpo: firma un texto
 * construido con el id del recurso, el id de la petición y un timestamp
 * (ver `mpVerificarFirma()`). Y su aviso **no trae el estado del pago**,
 * solo un id — hay que ir a preguntarle a su API.
 *
 * Eso, que parece un rodeo, es lo que sostiene la seguridad de todo el
 * cobro: aunque alguien lograra falsificar un aviso entero, lo único que
 * conseguiría es que preguntáramos por un id, y la respuesta la da
 * Mercado Pago. El atacante no puede inventarse un «aprobado».
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS PUERTAS, EN ESTE ORDEN
 * ─────────────────────────────────────────────────────────────────────
 *
 *  1. Solo POST. Un GET lo dispara cualquier rastreador, cualquier
 *     prefetch del navegador y cualquiera que pegue la URL en un chat.
 *
 *  2. Firma válida, comparada en tiempo constante (`hash_equals`).
 *     Comparar con == deja escapar cuánto coincide cada intento por el
 *     tiempo que tarda en fallar, y con suficientes intentos eso
 *     reconstruye la firma.
 *
 *  3. El estado se consulta a la API, nunca se lee del cuerpo.
 *
 *  4. El monto y la moneda tienen que cuadrar con lo que pusimos a
 *     cobrar. Un pago aprobado de mil pesos sobre un plan de noventa y
 *     nueve mil está aprobado — y no debe abrir nada. Lo comprueba
 *     `verificarPagoMp()`.
 *
 * Se responde 200 en cuanto el aviso se procesa, incluso si el pago ya
 * estaba confirmado. Una pasarela que no recibe 200 reintenta durante
 * horas, y responder «error» a un aviso correcto por repetido acaba
 * llenando el registro de ruido — `confirmarPago()` ya es idempotente,
 * así que el reintento es inofensivo.
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

/** Deja constancia en el log del proyecto: un webhook mudo es indepurable. */
function registrar(string $texto): void
{
    error_log('[webhook-pago] ' . $texto);
}


// ── Puerta 1 · método ────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['ok' => false, 'error' => 'Método no permitido.'], 405);
}

if (!pagosInstalados()) {
    registrar('Aviso recibido sin la migración de pagos aplicada.');
    responder(['ok' => false, 'error' => 'El cobro no está habilitado.'], 503);
}

$cuerpo = file_get_contents('php://input') ?: '';

if (strlen($cuerpo) > 65536) {
    responder(['ok' => false, 'error' => 'Cuerpo demasiado grande.'], 400);
}

$datos = $cuerpo !== '' ? json_decode($cuerpo, true) : [];
$datos = is_array($datos) ? $datos : [];


// =====================================================================
//  MERCADO PAGO
// =====================================================================

if (pasarelaActiva() !== 'mercadopago') {
    registrar('Aviso recibido sin ninguna pasarela conectada.');
    responder(['ok' => false, 'error' => 'No hay pasarela conectada.'], 503);
}

if (!mpWebhookConfigurado()) {
    registrar('Falta el secreto del webhook: el aviso se rechaza.');
    responder(['ok' => false, 'error' => 'Webhook no configurado.'], 503);
}

/*
 * Mercado Pago manda el id de dos formas según el tipo de aviso: en la
 * cadena de consulta (`?data.id=123&type=payment`) o en el cuerpo
 * (`{"type":"payment","data":{"id":"123"}}`). Se aceptan las dos porque
 * las dos son suyas y cuál llega depende de cómo esté dado de alta el
 * webhook en su panel.
 */
$tipo = (string) ($_GET['type'] ?? $_GET['topic'] ?? $datos['type'] ?? $datos['topic'] ?? '');
$dataId = (string) ($_GET['data.id'] ?? $_GET['id'] ?? $datos['data']['id'] ?? '');

/*
 * Mercado Pago avisa de más cosas además de pagos: contracargos,
 * suscripciones, alertas de fraude. Se acusan recibo con 200 y se
 * ignoran. Responder error a un aviso que sencillamente no nos interesa
 * haría que lo reintentara durante horas.
 */
if ($tipo !== '' && $tipo !== 'payment') {
    registrar("Aviso de tipo «$tipo» ignorado.");
    responder(['ok' => true, 'ignorado' => true, 'tipo' => $tipo]);
}

if ($dataId === '') {
    registrar('Aviso sin id: ' . mb_substr($cuerpo, 0, 300));
    responder(['ok' => false, 'error' => 'Falta el id del pago.'], 400);
}


// ── Puerta 2 · firma ─────────────────────────────────────────────────
$firma = mpVerificarFirma(
    $dataId,
    (string) ($_SERVER['HTTP_X_SIGNATURE'] ?? ''),
    (string) ($_SERVER['HTTP_X_REQUEST_ID'] ?? '')
);

if (!$firma['ok']) {
    registrar(sprintf(
        'Firma rechazada (%s) para el id %s desde %s',
        $firma['error'],
        $dataId,
        $_SERVER['REMOTE_ADDR'] ?? '?'
    ));

    // Al emisor no se le dice QUÉ falló: distinguir «firma incorrecta» de
    // «falta la firma» ya es información útil para quien está probando.
    responder(['ok' => false, 'error' => 'No autorizado.'], 401);
}


// ── Puertas 3 y 4 · se le pregunta a Mercado Pago ────────────────────
$verificado = verificarPagoMp($dataId);

if (!$verificado['ok']) {

    registrar(sprintf('Pago %s rechazado: %s', $dataId, $verificado['error']));

    /*
     * Si el desajuste es sobre un pago NUESTRO identificable —el caso
     * grave: el monto no cuadra— queda escrito en su historia. Es
     * exactamente lo que alguien tiene que ver al día siguiente, y
     * perderlo en un log del servidor es perderlo.
     */
    if (($verificado['pago'] ?? null) !== null) {
        registrarEventoPago(
            (int) $verificado['pago']['id'],
            'webhook',
            'Aviso RECHAZADO: ' . $verificado['error'],
            null
        );
    }

    /*
     * 200 a propósito: el aviso llegó bien y está registrado. Que no
     * cuadre es un problema nuestro que no se arregla porque Mercado Pago
     * lo reintente cien veces.
     */
    responder(['ok' => false, 'aplicado' => false, 'error' => 'El aviso no se pudo aplicar.']);
}

$resultado = aplicarAvisoPasarela($verificado['referencia'], (string) $verificado['estado'], [
    'provider'           => 'mercadopago',
    'provider_reference' => $verificado['transaccion'],
]);

registrar(sprintf(
    '%s → %s (%s)',
    $verificado['referencia'],
    $verificado['estado'],
    $resultado['ok'] ? 'aplicado' : ('no aplicado: ' . ($resultado['error'] ?? '?'))
));

/*
 * 200 aunque la aplicación interna falle por una regla de negocio —un
 * pago ya reembolsado, por ejemplo—: el aviso SÍ se recibió y se
 * registró, y pedirle a la pasarela que reintente durante horas por algo
 * que no va a cambiar solo llena el log sin arreglar nada. El caso queda
 * en la historia del pago para que lo mire una persona.
 */
responder([
    'ok'         => true,
    'referencia' => $verificado['referencia'],
    'aplicado'   => $resultado['ok'],
]);
