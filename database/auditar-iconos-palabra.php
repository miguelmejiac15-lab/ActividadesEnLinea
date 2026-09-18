<?php
/**
 * auditar-iconos-palabra.php — Dibujos que no dicen su palabra
 *
 *     php database/auditar-iconos-palabra.php
 *     php database/auditar-iconos-palabra.php --todo
 *
 * Dentro de los minijuegos, cada ejercicio empareja una palabra con un
 * dibujo. Cuando el emoji correcto no existe —«mesa» no lo tiene— quien
 * armó el contenido puso el más parecido que encontró. El niño ve un
 * mueble cualquiera y lee MESA, y no aprende: memoriza una asociación
 * falsa. En una actividad de lectoescritura eso es peor que no poner nada.
 *
 * Este script no sabe español: no puede decidir por sí solo si 🪑 vale
 * para «mesa». Lo que hace es señalar los indicios que casi siempre
 * delatan un dibujo prestado, para que un humano decida:
 *
 *   1. UN DIBUJO, VARIAS PALABRAS distintas — el mismo emoji sirviendo
 *      para «mesa» y para «silla» es un comodín, no un dibujo.
 *   2. UNA PALABRA, VARIOS DIBUJOS — la misma palabra ilustrada de dos
 *      formas distintas en dos actividades: una de las dos está mal.
 *   3. DIBUJOS COMODÍN conocidos (🔤, 📦, ❓…), que no representan nada.
 *
 * No modifica nada. Solo lee.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$todo = in_array('--todo', $argv ?? [], true);

/** Emojis que no ilustran nada: son relleno. */
const COMODINES = ['🔤', '📦', '❓', '⬜', '🎯', '🔣', '🅰️', '🔠'];

/**
 * Palabras que legítimamente comparten dibujo con otra.
 * Se listan para no repetirlas como hallazgo en cada revisión.
 */
const PAREJAS_ACEPTADAS = [
    'MAR|OLA',          // 🌊 sirve para las dos
    'OLA|MAR',
    'SOL|VERANO',
    'CASA|HOGAR',
];


// =====================================================================
//  RECOLECCIÓN
// =====================================================================

/** Recorre cualquier estructura y saca los pares {dibujo, palabra}. */
function recogerPares($datos, array &$pares, array $contexto): void
{
    if (!is_array($datos)) {
        return;
    }

    // Las cuatro formas en que el motor guarda un par dibujo/palabra.
    $dibujo  = $datos['e'] ?? $datos['emoji'] ?? null;
    $palabra = $datos['n'] ?? $datos['w'] ?? $datos['word'] ?? null;

    if (is_string($dibujo) && is_string($palabra) && $dibujo !== '' && $palabra !== '') {
        $pares[] = [
            'dibujo'  => $dibujo,
            'palabra' => mb_strtoupper(trim($palabra)),
            'donde'   => $contexto,
        ];
    }

    foreach ($datos as $hijo) {
        if (is_array($hijo)) {
            recogerPares($hijo, $pares, $contexto);
        }
    }
}

echo str_repeat('=', 78), "\n";
echo "  DIBUJOS QUE NO DICEN SU PALABRA\n";
echo str_repeat('=', 78), "\n\n";

$estaciones = traerTodo(
    "SELECT s.id, s.title, s.config, a.slug AS actividad, a.title AS titulo_actividad
       FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
      WHERE a.status = 'published'
   ORDER BY a.title, s.position"
);

$pares = [];

foreach ($estaciones as $e) {
    $config = json_decode($e['config'] ?? 'null', true);
    if (!is_array($config)) {
        continue;
    }
    recogerPares($config['datos'] ?? [], $pares, [
        'actividad' => $e['actividad'],
        'estacion'  => $e['title'],
    ]);
}

printf("  Pares dibujo/palabra encontrados : %d\n", count($pares));

// ── Índices ──────────────────────────────────────────────────────────
$porDibujo  = [];   // dibujo  => [palabra => [dónde…]]
$porPalabra = [];   // palabra => [dibujo  => [dónde…]]

foreach ($pares as $p) {
    $porDibujo[$p['dibujo']][$p['palabra']][]  = $p['donde'];
    $porPalabra[$p['palabra']][$p['dibujo']][] = $p['donde'];
}

printf("  Dibujos distintos                : %d\n", count($porDibujo));
printf("  Palabras distintas               : %d\n\n", count($porPalabra));


// =====================================================================
//  1. UN DIBUJO PARA VARIAS PALABRAS
// =====================================================================

echo str_repeat('─', 78), "\n";
echo "  1 · Un mismo dibujo para palabras distintas\n";
echo "      Casi siempre significa que a una de ellas le falta el suyo.\n";
echo str_repeat('─', 78), "\n\n";

$sospechosos = [];

foreach ($porDibujo as $dibujo => $palabras) {
    if (count($palabras) < 2) {
        continue;
    }

    $lista = array_keys($palabras);
    sort($lista);

    if (in_array(implode('|', $lista), PAREJAS_ACEPTADAS, true)) {
        continue;
    }

    $sospechosos[$dibujo] = $lista;
}

// Los que sirven a más palabras van primero: son los comodines peores.
uasort($sospechosos, static fn($a, $b) => count($b) <=> count($a));

$mostrados = 0;
foreach ($sospechosos as $dibujo => $lista) {
    if (!$todo && $mostrados >= 25) {
        printf("      … y %d más (usa --todo para verlos)\n", count($sospechosos) - $mostrados);
        break;
    }
    printf("    %-4s ×%d   %s\n", $dibujo, count($lista), implode(' · ', $lista));
    $mostrados++;
}

if (!$sospechosos) {
    echo "    ✅ Ninguno.\n";
}


// =====================================================================
//  2. UNA PALABRA CON VARIOS DIBUJOS
// =====================================================================

echo "\n", str_repeat('─', 78), "\n";
echo "  2 · Una misma palabra ilustrada de formas distintas\n";
echo "      Una de las dos está mal, o el catálogo es incoherente.\n";
echo str_repeat('─', 78), "\n\n";

$incoherentes = [];

foreach ($porPalabra as $palabra => $dibujos) {
    if (count($dibujos) > 1) {
        $incoherentes[$palabra] = array_keys($dibujos);
    }
}

ksort($incoherentes);

if (!$incoherentes) {
    echo "    ✅ Ninguna.\n";
} else {
    foreach ($incoherentes as $palabra => $dibujos) {
        printf("    %-18s %s\n", $palabra, implode('  ', $dibujos));
    }
}


// =====================================================================
//  3. COMODINES
// =====================================================================

echo "\n", str_repeat('─', 78), "\n";
echo "  3 · Dibujos comodín, que no ilustran nada\n";
echo str_repeat('─', 78), "\n\n";

$conComodin = 0;

foreach (COMODINES as $c) {
    if (!isset($porDibujo[$c])) {
        continue;
    }
    $palabras = array_keys($porDibujo[$c]);
    printf("    %-4s ×%d   %s\n", $c, count($palabras), implode(' · ', array_slice($palabras, 0, 12)));
    $conComodin++;
}

if (!$conComodin) {
    echo "    ✅ Ninguno.\n";
}

echo "\n", str_repeat('=', 78), "\n";
printf("  Total a revisar a mano: %d dibujos compartidos · %d palabras incoherentes\n",
       count($sospechosos), count($incoherentes));
echo str_repeat('=', 78), "\n";
