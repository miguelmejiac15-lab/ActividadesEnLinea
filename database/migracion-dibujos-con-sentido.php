<?php
/**
 * migracion-dibujos-con-sentido.php — Dibujos que corresponden a lo que se pregunta
 *
 *     php database/migracion-dibujos-con-sentido.php             # simulacion
 *     php database/migracion-dibujos-con-sentido.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE
 * ---------------------------------------------------------------------
 *
 * El dibujo de muchas preguntas lo puso un diccionario que mira UNA
 * palabra del enunciado. Con palabras de doble sentido se equivocaba:
 *
 *   «¿En cuántas partes se divide el cuerpo de un insecto?»   🧍 una persona
 *   «¿Cuál de estos es un cuerpo geométrico?»                 🧍
 *   «¿Cuál de estos es primo?» (número primo)                 🧒 un niño
 *   «¿Para qué sirve una hoja de cálculo?»                    🍃 una hoja de árbol
 *   «¿Se puede tocar la red?» (vóleibol)                      🔴 «red» en inglés
 *   «Al golpe más fuerte se le llama…» (música)               🤕 una cara herida
 *   «¿Cómo se llama cada parte grande de una obra?»           🐘 «grande»
 *
 * Un niño que está aprendiendo no corrige el dibujo: lo cree. El
 * diccionario (contenido/dibujos.php) ya conoce esas frases; esta
 * migración arregla lo que está publicado.
 *
 * Además replantea la pregunta de las partes del insecto. «¿En cuántas
 * partes se divide?» depende de cómo se cuente (hay quien enseña dos
 * en arañas, tres en insectos, y los textos de primaria no siempre
 * coinciden), y con una persona dibujada al lado era imposible. Se
 * pregunta algo que no varía: en qué parte están las seis patas.
 *
 * Y en «Formas y colores · Ponte a prueba», las seis preguntas de
 * «¿Cómo se llama esta figura?» mostraban el mismo 🔷: ahora, la figura.
 *
 * Solo cambia preguntas que todavía tienen el dibujo equivocado.
 * Repetirla no cambia nada. Solo `activity_stations.config` (catálogo).
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/ayudas.php';
require_once __DIR__ . '/contenido/dibujos.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Dibujos con sentido\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

// Dibujos que salieron de leer mal una palabra…
const DIBUJOS_MAL_LEIDOS = ['🧍', '🧒', '🍃', '🚶', '🔴', '🤕', '🐘', '📏', '⚖️', '↔️'];
// …y los que el diccionario da ahora para esas mismas frases.
const DIBUJOS_DE_FRASE   = ['🐜', '📐', '🔢', '📊', '🏐', '🥁', '🎭', '📜', '🏺'];

const INSECTO_ANTES = '¿En cuántas partes se divide el cuerpo de un insecto?';
const INSECTO = [
    'texto'    => 'El cuerpo de un insecto tiene cabeza, tórax y abdomen. ¿En qué parte tiene sus seis patas?',
    'opciones' => ['En el tórax', 'En la cabeza', 'En el abdomen'],
    'correcta' => 0,
    'visual'   => '🐜',
];

const FIGURA = [
    'Círculo' => 'icono:figura-circulo', 'Cuadrado' => 'icono:figura-cuadrado',
    'Triángulo' => 'icono:figura-triangulo', 'Rectángulo' => 'icono:figura-rectangulo',
    'Estrella' => '⭐', 'Corazón' => '❤️',
];

$cambios = 0;

$estaciones = traerTodo(
    "SELECT s.id, s.position, s.game_type, s.config, a.slug
       FROM activity_stations s JOIN activities a ON a.id = s.activity_id
      WHERE a.status = 'published' AND s.game_type IN ('opcion_multiple', 'desafio_final')"
);

foreach ($estaciones as $st) {
    $config = json_decode((string) $st['config'], true);
    $datos  = $config['datos'] ?? null;
    if (!is_array($datos) || !array_is_list($datos)) continue;

    $om = $st['game_type'] === 'opcion_multiple';
    [$kT, $kV, $kO, $kA] = $om ? ['enunciado', 'visual', 'opciones', 'correcta'] : ['q', 'e', 'opts', 'a'];
    $tocada = false;

    foreach ($datos as $k => $it) {
        if (!is_array($it)) continue;
        $texto  = (string) ($it[$kT] ?? '');
        $visual = $it[$kV] ?? null;
        $donde  = "{$st['slug']} E{$st['position']} P" . ($k + 1);

        // La pregunta del insecto, replanteada.
        if ($texto === INSECTO_ANTES) {
            $datos[$k][$kT] = INSECTO['texto'];
            $datos[$k][$kO] = INSECTO['opciones'];
            $datos[$k][$kA] = INSECTO['correcta'];
            $datos[$k][$kV] = INSECTO['visual'];
            if ($om) $datos[$k]['tipoVisual'] = 'emoji';
            unset($datos[$k]['dibujos']);
            echo "  [->] $donde: pregunta del insecto replanteada\n";
            $tocada = true; $cambios++;
            continue;
        }

        // Formas y colores: la figura, no un 🔷 repetido.
        if (!$om && $texto === '¿Cómo se llama esta figura?' && $visual === '🔷') {
            $respuesta = $it['opts'][$it['a']] ?? null;
            if (isset(FIGURA[$respuesta])) {
                $datos[$k]['e'] = FIGURA[$respuesta];
                echo "  [->] $donde: 🔷 -> " . FIGURA[$respuesta] . "\n";
                $tocada = true; $cambios++;
            }
            continue;
        }

        // Dibujo puesto por una palabra leída fuera de su sentido.
        if (is_string($visual) && in_array($visual, DIBUJOS_MAL_LEIDOS, true)) {
            $nuevo = dibujoDe($texto);
            if ($nuevo !== null && $nuevo !== $visual && in_array($nuevo, DIBUJOS_DE_FRASE, true)) {
                $datos[$k][$kV] = $nuevo;
                echo "  [->] $donde: $visual -> $nuevo  «" . mb_substr($texto, 0, 60) . "»\n";
                $tocada = true; $cambios++;
            }
        }
    }

    if ($tocada && $aplicar) {
        $config['datos'] = $datos;
        ejecutar('UPDATE activity_stations SET config = ? WHERE id = ?',
            [json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), (int) $st['id']]);
    }
}

echo "\n$cambios pregunta(s) " . ($aplicar ? 'corregidas.' : 'por corregir.') . "\n";
if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
