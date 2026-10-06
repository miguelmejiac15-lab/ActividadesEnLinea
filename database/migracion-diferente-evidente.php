<?php
/**
 * migracion-diferente-evidente.php — «¿Cuál es diferente?» con diferencias que se vean
 *
 *     php database/migracion-diferente-evidente.php             # simulacion
 *     php database/migracion-diferente-evidente.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE
 * ---------------------------------------------------------------------
 *
 * En «Encuentra el diferente» (preescolar) varias preguntas pedían
 * distinguir emojis casi iguales: 😀 y 😃, ⭐ y 🌟, 🚗 y 🚙, 🟥 y 🟧.
 * Para un niño de cuatro años eso no es atención, es adivinar; y como
 * cada teléfono dibuja los emojis a su manera, en algunos la diferencia
 * ni siquiera existe. Se cambia solo el intruso por uno que se distinga
 * de un vistazo, sin mover la respuesta de su sitio.
 *
 * En «Mundo de contrastes» la fila 🍎🍎🍎🍊 iba como un solo dibujo
 * gigante que en el teléfono se partía en dos líneas, y las opciones
 * hablan de «la primera», «la segunda»… Pasa a casillas, una por objeto.
 *
 * Reconoce cada pregunta por sus opciones exactas: si ya se cambió, no
 * coincide y no se toca. Solo `activity_stations.config` (catálogo).
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Diferente evidente\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

// Intruso casi igual => intruso que se ve.
const CAMBIOS = [
    '🌟' => '🌙',   // estrella brillante  -> luna
    '🔷' => '🔺',   // rombo azul          -> triángulo rojo
    '🚙' => '🚲',   // otro carro          -> bicicleta
    '😃' => '😢',   // otra cara feliz     -> cara triste
    '🌺' => '🌻',   // otra flor rosada    -> girasol
    '🟧' => '🟦',   // naranja junto a rojo -> azul
];

$cambiadas = 0;

$estaciones = traerTodo(
    "SELECT s.id, s.position, s.game_type, s.config, a.slug
       FROM activity_stations s JOIN activities a ON a.id = s.activity_id
      WHERE a.slug IN ('encuentra-el-diferente', 'mundo-de-contrastes')
        AND s.game_type IN ('opcion_multiple', 'desafio_final')"
);

foreach ($estaciones as $st) {
    $config = json_decode((string) $st['config'], true);
    $datos  = $config['datos'] ?? null;
    if (!is_array($datos)) continue;

    $om = $st['game_type'] === 'opcion_multiple';
    $tocada = false;

    foreach ($datos as $k => $it) {
        $texto = (string) ($om ? ($it['enunciado'] ?? '') : ($it['q'] ?? ''));
        $okey  = $om ? 'opciones' : 'opts';

        // Intruso casi igual.
        if ($st['slug'] === 'encuentra-el-diferente' && $texto === '¿Cuál es diferente?') {
            foreach ($datos[$k][$okey] as $j => $op) {
                if (isset(CAMBIOS[$op])) {
                    echo "  [->] {$st['slug']} E{$st['position']} P" . ($k + 1) . ": $op -> " . CAMBIOS[$op] . "\n";
                    $datos[$k][$okey][$j] = CAMBIOS[$op];
                    unset($datos[$k]['dibujos']);
                    $tocada = true;
                    $cambiadas++;
                }
            }
        }

        // Fila pegada en un solo dibujo: a casillas.
        if ($st['slug'] === 'mundo-de-contrastes' && str_contains($texto, 'diferente a l')) {
            $fila = $om ? ($it['visual'] ?? null) : ($it['e'] ?? null);
            if (is_string($fila) && mb_strlen($fila) >= 3 && !preg_match('/[\p{L}\p{N}\s]/u', $fila)) {
                $dibujos = mb_str_split($fila);
                $datos[$k]['visual']     = $dibujos;
                $datos[$k]['tipoVisual'] = 'serie';
                unset($datos[$k]['e']);
                echo "  [->] {$st['slug']} E{$st['position']} P" . ($k + 1) . ": $fila -> casillas\n";
                $tocada = true;
                $cambiadas++;
            }
        }
    }

    if ($tocada && $aplicar) {
        $config['datos'] = $datos;
        ejecutar('UPDATE activity_stations SET config = ? WHERE id = ?',
            [json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), (int) $st['id']]);
    }
}

echo "\n$cambiadas pregunta(s) " . ($aplicar ? 'corregidas.' : 'por corregir.') . "\n";
if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
