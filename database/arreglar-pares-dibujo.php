<?php
/**
 * arreglar-pares-dibujo.php — Dibujos cambiados por el correcto
 *
 *     php database/arreglar-pares-dibujo.php            (simulación)
 *     php database/arreglar-pares-dibujo.php --aplicar  (escribe)
 *
 * Corrige los ejercicios donde una palabra lleva el dibujo de otra cosa
 * y SÍ existe el emoji correcto. Los detectó
 * `auditar-iconos-palabra.php`.
 *
 * Las palabras que no tienen emoji posible —mesa, xilófono, flauta— no
 * se tocan aquí: necesitan un dibujo propio, y eso va por otro camino
 * (ver docs/iconos/faltantes.md).
 *
 * El reemplazo es por PAR completo (palabra + dibujo viejo), nunca por
 * emoji suelto: cambiar todos los 🐦 del catálogo rompería los ejercicios
 * donde el pájaro sí es lo correcto.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);


/**
 * [palabra, dibujo equivocado, dibujo correcto, por qué]
 *
 * La comparación de la palabra no distingue mayúsculas ni tildes.
 */
const CAMBIOS = [
    // ── El emoji correcto ya existía, solo estaba mal puesto ─────────
    ['PATO',  '🐦', '🦆', 'un pájaro genérico para «pato», que tiene el suyo'],
    ['PATO',  '🐭', '🦆', 'un ratón para «pato»'],
    ['ERIZO', '🦉', '🦔', 'un búho para «erizo»'],

    // ── No existe emoji: se pasa a un dibujo propio ──────────────────
    // El prefijo `icono:` lo entiende el motor (ver `dibujo()` en
    // motor.js) y carga assets/iconos/<nombre>.svg.
    ['MESA',      '🪑', 'icono:mesa',      'una silla para «mesa»'],
    ['MESA',      '🍽️', 'icono:mesa',      'unos cubiertos para «mesa»'],
    ['XILOFONO',  '🎵', 'icono:xilofono',  'una nota musical para «xilófono»'],
    ['XILÓFONO',  '🎵', 'icono:xilofono',  'una nota musical para «xilófono»'],
    ['FLAUTA',    '🎻', 'icono:flauta',    'un violín para «flauta»'],
    ['ÑANDU',     '🦩', 'icono:nandu',     'un flamenco para «ñandú», y encima es el dibujo de la F'],
    ['ÑANDÚ',     '🦩', 'icono:nandu',     'un flamenco para «ñandú», y encima es el dibujo de la F'],
    ['ÑAME',      '🌿', 'icono:name',      'una hierba genérica que compartía con yuca, tallo y wasabi'],
];


/** Recorre la estructura cambiando los pares que coincidan. */
function corregir(&$datos, array $cambio, int &$hechos): void
{
    if (!is_array($datos)) {
        return;
    }

    [$palabra, $malo, $bueno] = $cambio;

    // Las cuatro formas en que se guarda el par dibujo/palabra.
    foreach ([['e', 'n'], ['e', 'w'], ['emoji', 'word']] as [$cd, $cp]) {
        if (isset($datos[$cd], $datos[$cp])
            && is_string($datos[$cd]) && is_string($datos[$cp])
            && $datos[$cd] === $malo
            && mb_strtoupper(trim($datos[$cp])) === $palabra) {

            $datos[$cd] = $bueno;
            $hechos++;
        }
    }

    foreach ($datos as &$hijo) {
        if (is_array($hijo)) {
            corregir($hijo, $cambio, $hechos);
        }
    }
    unset($hijo);
}


echo str_repeat('=', 76), "\n";
echo "  DIBUJOS CAMBIADOS POR EL CORRECTO\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 76), "\n\n";

$estaciones = traerTodo(
    "SELECT s.id, s.title, s.config, s.game_type, a.slug
       FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
      WHERE a.status = 'published'
   ORDER BY a.title, s.position"
);

$totalCambios = 0;
$estacionesTocadas = 0;

foreach ($estaciones as $e) {

    $config = json_decode($e['config'] ?? 'null', true);
    if (!is_array($config) || !isset($config['datos'])) {
        continue;
    }

    $hechos = 0;
    foreach (CAMBIOS as $c) {
        corregir($config['datos'], $c, $hechos);
    }

    if ($hechos === 0) {
        continue;
    }

    printf("  %-26s #%s  %-28s %d cambio(s)\n",
           $e['slug'], '', $e['title'], $hechos);

    if ($aplicar) {
        ejecutar(
            'UPDATE activity_stations SET config = ? WHERE id = ?',
            [json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $e['id']]
        );
    }

    $totalCambios += $hechos;
    $estacionesTocadas++;
}

echo "\n", str_repeat('-', 76), "\n";
printf("  Estaciones tocadas : %d\n", $estacionesTocadas);
printf("  Pares corregidos   : %d\n\n", $totalCambios);

foreach (CAMBIOS as [$palabra, $malo, $bueno, $porque]) {
    printf("    %-8s %s → %s   %s\n", $palabra, $malo, $bueno, $porque);
}

echo $aplicar
    ? "\n  Aplicado.\n"
    : "\n  Simulación. Para escribir: php database/arreglar-pares-dibujo.php --aplicar\n";

echo str_repeat('=', 76), "\n";
