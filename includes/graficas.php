<?php
/**
 * graficas.php — Gráficas del panel, sin librerías
 *
 * Todo lo que se dibuja aquí es HTML y CSS. No hay Chart.js, ni D3, ni
 * una petición a un CDN. Las razones son las mismas por las que el
 * catálogo usa emoji en vez de mil archivos de imagen:
 *
 *   · Una librería de gráficas pesa más que el panel entero.
 *   · Un CDN caído deja el panel sin cifras justo el día que se necesita.
 *   · Un `<div>` con una altura en porcentaje se imprime, se lee con
 *     lector de pantalla y se ve en un móvil sin configurar nada.
 *
 * Cada gráfica trae debajo su tabla de datos plegada. Una cifra que
 * solo se puede leer pasando el ratón por encima no existe para quien
 * navega con teclado, ni para quien imprime el informe.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LOS COLORES ESTÁN ELEGIDOS, NO IMPROVISADOS
 * ─────────────────────────────────────────────────────────────────────
 *
 * Los azules y verdes del sitio son claros a propósito —es un producto
 * para niños— pero sobre fondo blanco no llegan a 3:1 de contraste, y
 * una barra que no se distingue del fondo no informa de nada. Las
 * gráficas usan por eso los tonos oscuros de las MISMAS familias:
 * mismo idioma visual, contraste suficiente.
 *
 * El orden es fijo: el primer plan de pago siempre es azul, aunque un
 * filtro lo deje solo o lo mande al último lugar. Si el color siguiera
 * al puesto en la tabla, filtrar repintaría las series y quien había
 * aprendido «Biblioteca es azul» leería mal la siguiente pantalla.
 */

declare(strict_types=1);

/**
 * Paleta categórica, en orden fijo. Comprobada para daltonismo
 * (deuteranopía y tritanopía) y contraste sobre blanco.
 */
const COLORES_SERIE = ['#0288d1', '#ef6c00', '#8e24aa', '#2e7d32'];

/** Gris del «sin plan». No es una serie más: es la ausencia de una. */
const COLOR_NEUTRO = '#94a3b8';

/** Color único de las gráficas de una sola serie. */
const COLOR_UNICO = '#0288d1';

/** Color de la serie número $i, sin ciclar nunca. */
function colorSerie(int $i): string
{
    return COLORES_SERIE[$i] ?? COLOR_NEUTRO;
}


// =====================================================================
//  FORMATO
// =====================================================================

/** Da formato a un valor según el tipo de gráfica. */
function valorGrafica(int $v, string $formato): string
{
    if ($formato === 'moneda') {
        return precioCop($v);
    }
    return number_format($v, 0, ',', '.');
}

/**
 * Versión corta para el eje: 1.250.000 → 1,2 M
 *
 * Las etiquetas del eje se pisan unas a otras en cuanto la cifra tiene
 * siete dígitos, y en un eje la magnitud importa más que el peso exacto
 * —que sigue estando en el globo y en la tabla.
 */
function valorCorto(int $v, string $formato): string
{
    if ($formato !== 'moneda') {
        return number_format($v, 0, ',', '.');
    }
    if ($v >= 1000000) {
        return '$' . rtrim(rtrim(number_format($v / 1000000, 1, ',', '.'), '0'), ',') . ' M';
    }
    if ($v >= 1000) {
        return '$' . number_format($v / 1000, 0, ',', '.') . ' k';
    }
    return '$' . $v;
}


// =====================================================================
//  BARRAS EN EL TIEMPO
// =====================================================================

/**
 * Gráfica de barras verticales para una serie temporal.
 *
 * Una sola serie, un solo color. Pintar cada barra de un tono distinto
 * según su altura sería codificar dos veces lo mismo: la altura ya dice
 * cuál es la más grande, y el color quedaría gastado sin añadir nada.
 *
 * Se marcan con su cifra encima solo dos barras —la mayor y la última—
 * porque un número sobre cada una convierte la gráfica en una tabla mal
 * maquetada. El resto de valores están en el globo y en la tabla.
 *
 * @param array $serie   Filas con clave, etiqueta, largo, valor, conteo
 * @param array $o       titulo, formato ('moneda'|'numero'), unidad, alto, id
 */
function graficaBarras(array $serie, array $o = []): string
{
    $formato = $o['formato'] ?? 'numero';
    $unidad  = $o['unidad']  ?? '';
    $alto    = (int) ($o['alto'] ?? 170);
    $id      = $o['id'] ?? 'g' . substr(md5(serialize(array_column($serie, 'clave'))), 0, 6);
    $titulo  = $o['titulo'] ?? '';

    if (!$serie) {
        return '<p class="gr-vacia">Todavía no hay datos que dibujar.</p>';
    }

    $valores = array_map(static fn ($f) => (int) $f['valor'], $serie);
    $max     = max($valores);
    $maxEje  = $max > 0 ? $max : 1;

    // Índices que llevan etiqueta directa: el pico y el último punto.
    $iMax    = array_search($max, $valores, true);
    $iUltimo = count($serie) - 1;

    $h  = '<figure class="gr" style="--gr-alto:' . $alto . 'px">';

    // ── Guías. Hairlines sólidas, un tono por encima del fondo: tienen
    //    que poder leerse sin competir con los datos.
    $h .= '<div class="gr-lienzo">';
    $h .= '<div class="gr-guias" aria-hidden="true">';
    foreach ([1, 0.5, 0] as $fraccion) {
        $h .= '<div class="gr-guia" style="bottom:' . ($fraccion * 100) . '%">'
            . '<span>' . e(valorCorto((int) round($maxEje * $fraccion), $formato)) . '</span>'
            . '</div>';
    }
    $h .= '</div>';

    $h .= '<ol class="gr-barras">';

    foreach ($serie as $i => $f) {
        $v    = (int) $f['valor'];
        $pct  = $max > 0 ? ($v * 100 / $max) : 0;
        $texto = valorGrafica($v, $formato) . ($unidad ? ' ' . $unidad : '');
        $globo = e($f['largo'] ?? $f['etiqueta']) . ' · <b>' . e($texto) . '</b>';

        if (isset($f['conteo']) && $formato === 'moneda') {
            $globo .= '<br><span>' . (int) $f['conteo'] . ' '
                    . ((int) $f['conteo'] === 1 ? 'pago' : 'pagos') . '</span>';
        }

        $destacada = ($i === $iMax || $i === $iUltimo) && $v > 0;

        $h .= '<li class="gr-col' . ($destacada ? ' marcada' : '') . '" tabindex="0"'
            . ' aria-label="' . e(($f['largo'] ?? $f['etiqueta']) . ': ' . $texto) . '">';

        if ($destacada) {
            $h .= '<span class="gr-cifra">' . e(valorCorto($v, $formato)) . '</span>';
        }

        // La altura mínima de 3px deja ver que el mes existe y valió cero,
        // en vez de dejar un hueco que parece un fallo de la gráfica.
        $h .= '<span class="gr-barra' . ($v === 0 ? ' cero' : '') . '"'
            . ' style="height:' . round($pct, 2) . '%;background:' . COLOR_UNICO . '"></span>';

        $h .= '<span class="gr-eje">' . e($f['etiqueta']) . '</span>';
        $h .= '<span class="gr-globo" role="tooltip">' . $globo . '</span>';
        $h .= '</li>';
    }

    $h .= '</ol></div>';

    // ── La tabla gemela. Todo valor que está en la gráfica está aquí.
    $h .= tablaDeSerie($serie, $formato, $titulo, $id);
    $h .= '</figure>';

    return $h;
}

/** La tabla que acompaña a cada gráfica, plegada. */
function tablaDeSerie(array $serie, string $formato, string $titulo, string $id): string
{
    $h  = '<details class="gr-datos"><summary>Ver los datos'
        . ($titulo !== '' ? ' de ' . e(mb_strtolower($titulo)) : '') . '</summary>';
    $h .= '<table class="tabla" id="tabla-' . e($id) . '"><thead><tr>'
        . '<th>Periodo</th><th style="text-align:right">Valor</th></tr></thead><tbody>';

    foreach ($serie as $f) {
        $h .= '<tr><td>' . e($f['largo'] ?? $f['etiqueta']) . '</td>'
            . '<td class="cifra">' . e(valorGrafica((int) $f['valor'], $formato)) . '</td></tr>';
    }

    $h .= '</tbody></table></details>';
    return $h;
}


// =====================================================================
//  REPARTO
// =====================================================================

/**
 * Barra horizontal apilada al 100% con su leyenda.
 *
 * Se usa en lugar de una torta porque el reparto de planes casi siempre
 * es un trozo enorme —los gratuitos— y dos delgadísimos. En una torta
 * esos dos son gajos imposibles de comparar; en una barra apilada se
 * leen sus longitudes, y la leyenda lleva la cifra escrita al lado.
 *
 * Los segmentos no se separan con un borde sino con un hueco del color
 * del fondo: un borde oscurece el color del dato y en segmentos finos
 * llega a taparlo entero.
 *
 * @param array $partes  [['etiqueta'=>, 'valor'=>, 'color'=>], …]
 */
function graficaReparto(array $partes, array $o = []): string
{
    $formato = $o['formato'] ?? 'numero';
    $total   = array_sum(array_map(static fn ($p) => (int) $p['valor'], $partes));

    if ($total <= 0) {
        return '<p class="gr-vacia">Todavía no hay datos que repartir.</p>';
    }

    $h = '<div class="gr-reparto">';

    $h .= '<div class="gr-pila" role="img" aria-label="'
        . e(implode(' · ', array_map(
            static fn ($p) => $p['etiqueta'] . ': ' . $p['valor'],
            $partes
        ))) . '">';

    foreach ($partes as $p) {
        $v = (int) $p['valor'];
        if ($v <= 0) {
            continue;
        }
        $pct = $v * 100 / $total;
        $h .= '<span class="gr-trozo" style="width:' . round($pct, 3) . '%;background:'
            . e($p['color']) . '" title="' . e($p['etiqueta'] . ': ' . valorGrafica($v, $formato)) . '"></span>';
    }

    $h .= '</div>';

    // La leyenda lleva la cifra y el porcentaje escritos. Meterlos dentro
    // del segmento los recortaría en cuanto el trozo fuera estrecho.
    $h .= '<ul class="gr-leyenda">';
    foreach ($partes as $p) {
        $v   = (int) $p['valor'];
        $pct = $total > 0 ? round($v * 100 / $total) : 0;
        $h .= '<li>'
            . '<span class="punto" style="background:' . e($p['color']) . '" aria-hidden="true"></span>'
            . '<span class="nombre">' . e($p['etiqueta']) . '</span>'
            . '<b>' . e(valorGrafica($v, $formato)) . '</b>'
            . '<span class="pct">' . $pct . '%</span>'
            . '</li>';
    }
    $h .= '</ul></div>';

    return $h;
}

/**
 * Barra horizontal simple para una celda de tabla.
 * No es una gráfica: es un apoyo visual a un número que ya está escrito
 * al lado, para poder comparar filas de un vistazo.
 */
function barraFila(int $valor, int $max, string $color = COLOR_UNICO): string
{
    $pct = $max > 0 ? round($valor * 100 / $max, 1) : 0;
    return '<span class="gr-fila" aria-hidden="true">'
         . '<span style="width:' . $pct . '%;background:' . e($color) . '"></span></span>';
}

/**
 * Ficha grande con una sola cifra.
 *
 * Cuando la historia es un número, la gráfica correcta es el número.
 * `$pie` admite HTML ya escapado porque suele llevar una variación con
 * su flecha, y `$tono` colorea solo esa variación, nunca la cifra.
 */
function cifraDestacada(string $etiqueta, string $valor, string $pie = '', string $tono = ''): string
{
    return '<div class="cifra-caja ' . e($tono) . '">'
         . '<div class="n">' . e($valor) . '</div>'
         . '<div class="t">' . e($etiqueta) . '</div>'
         . ($pie !== '' ? '<div class="p">' . $pie . '</div>' : '')
         . '</div>';
}
