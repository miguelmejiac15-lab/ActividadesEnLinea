<?php
/**
 * admin/pagos/exportar.php — El informe de pagos, en CSV
 *
 * Exporta exactamente lo que el listado tiene filtrado en pantalla. Que
 * sea el mismo filtro y no «todo» es deliberado: quien exporta acaba de
 * acotar por fechas y estado para cuadrar un mes, y darle el histórico
 * completo lo obliga a volver a filtrar en la hoja de cálculo.
 *
 * Dos detalles que evitan la llamada de «se ve todo raro en Excel»:
 *
 *   · Separador «;». Excel en español interpreta la coma como decimal
 *     y mete cada fila entera en una sola celda.
 *   · BOM UTF-8 al principio. Sin él, Excel abre el archivo como ANSI y
 *     todas las tildes y las ñ salen rotas.
 *
 * Los montos van como número entero sin puntos ni símbolo: en una hoja
 * de cálculo, "$99.000" es texto y no se puede sumar.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if (!pagosInstalados()) {
    mensaje('error', 'Falta ejecutar la migración de pagos.');
    redirigir('admin/pagos/');
}

// ── Mismos filtros que el listado ────────────────────────────────────
$where  = ['1 = 1'];
$params = [];

$fBuscar = mb_substr(get('buscar'), 0, 80);
$fEstado = get('estado');
$fMetodo = get('metodo');
$fPlan   = get('plan');
$fDesde  = get('desde');
$fHasta  = get('hasta');

if ($fBuscar !== '') {
    $where[] = '(pg.reference = ? OR u.name LIKE ? OR u.email LIKE ? OR pg.provider_reference LIKE ? OR pg.proof_reference LIKE ?)';
    $t = '%' . escaparLike($fBuscar) . '%';
    array_push($params, $fBuscar, $t, $t, $t, $t);
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

$filas = traerTodo(
    'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
      WHERE ' . implode(' AND ', $where) . '
   ORDER BY pg.created_at DESC
      LIMIT 20000',
    $params
);

$nombre = 'cobros-' . date('Y-m-d-Hi') . '.csv';

header('Content-Type: text/csv; charset=UTF-8');
header('Content-Disposition: attachment; filename="' . $nombre . '"');
header('Cache-Control: no-store');

$salida = fopen('php://output', 'w');

// BOM: le dice a Excel que el archivo es UTF-8.
fwrite($salida, "\xEF\xBB\xBF");

fputcsv($salida, [
    'Referencia', 'Fecha', 'Cliente', 'Correo', 'Plan', 'Modalidad',
    'Monto COP', 'Estado', 'Forma de pago', 'Pasarela', 'Ref. pasarela',
    'Comprobante', 'Confirmado', 'Confirmado por', 'Documento', 'Nota',
], ';');

$estados = estadosPago();

foreach ($filas as $f) {
    fputcsv($salida, [
        $f['reference'],
        date('d/m/Y H:i', strtotime($f['created_at'])),
        $f['usuario'],
        $f['usuario_email'],
        $f['plan'],
        etiquetaCiclo((string) $f['billing_cycle']),
        (int) $f['amount_cop'],
        $estados[$f['status']]['etiqueta'] ?? $f['status'],
        etiquetaMetodo($f['method']),
        $f['provider'] ?? '',
        $f['provider_reference'] ?? '',
        $f['proof_reference'] ?? '',
        $f['confirmed_at'] ? date('d/m/Y H:i', strtotime($f['confirmed_at'])) : '',
        $f['confirmado_por'] ?? '',
        $f['payer_document'] ?? '',
        // Los saltos de línea de una nota romperían la fila del CSV.
        str_replace(["\r", "\n"], ' ', (string) ($f['notes'] ?? '')),
    ], ';');
}

fclose($salida);
exit;
