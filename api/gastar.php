<?php
/**
 * api/gastar.php — Cobra una pista o un salto
 *
 * El navegador pide la ayuda; aquí se decide si se puede pagar. El saldo
 * se calcula en el servidor a partir del progreso guardado, así que
 * manipular el JavaScript no da monedas: solo consigue que la respuesta
 * diga que no alcanzan.
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

// Gastar cambia el estado: nunca por GET, que cualquier prefetch podría
// disparar sin que el niño toque nada.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['ok' => false, 'error' => 'Método no permitido.'], 405);
}

if (!csrfValido()) {
    responder(['ok' => false, 'error' => 'Sesión del formulario expirada.'], 403);
}

if (usuarioActualId() === null) {
    responder(['ok' => false, 'error' => 'Necesitas una cuenta.'], 401);
}

$tipo       = post('tipo');
$estacionId = (int) ($_POST['estacion'] ?? 0) ?: null;

// La estación tiene que existir; si no, se guarda el gasto sin ella.
if ($estacionId !== null
    && !traerValor('SELECT 1 FROM activity_stations WHERE id = ?', [$estacionId])) {
    $estacionId = null;
}

$r = gastarMonedas($tipo, $estacionId);

responder([
    'ok'    => $r['ok'],
    'error' => $r['error'],
    'saldo' => $r['saldo'],
], $r['ok'] ? 200 : 400);
