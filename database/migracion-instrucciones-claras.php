<?php
/**
 * migracion-instrucciones-claras.php — Revisión global: que cada pregunta se entienda
 *
 *     php database/migracion-instrucciones-claras.php             # simulacion
 *     php database/migracion-instrucciones-claras.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE
 * ---------------------------------------------------------------------
 *
 * Después de arreglar «Patrones Mágicos» (migracion-enunciados-claros)
 * se auditaron todas las preguntas del catálogo publicado. Aparecieron
 * otros defectos que dejan a un niño sin saber qué hacer, o que le dan
 * la respuesta hecha:
 *
 *   1. Series escritas con emojis DENTRO del enunciado —«🔺 🔵 🔺 🔵 🔺
 *      ¿qué sigue?»— que salen del tamaño de una letra, con una ➡️ suelta
 *      como dibujo. Se pasan a una fila de casillas con su hueco.
 *   2. Conteos igual —«🐶🐶 ¿cuántos?»— y comparaciones —«🍎🍎🍎🍎 o 🍎🍎.
 *      ¿Dónde hay más?»— con un 🔢 o un 📍 de relleno.
 *   3. El dibujo de arriba ES la respuesta: «¿Cuál es la casa?» con 🏠
 *      encima de las opciones 🏠 🚗 🐶. Se quita el dibujo.
 *   4. Escenarios y afirmaciones que terminan sin pregunta: «Encuentras
 *      una cartera con dinero…» y cuatro conductas. Se añade la pregunta.
 *   5. Los «Repaso» y «Desafío final» solo sabían pintar emojis, y las
 *      preguntas de color llegaban sin el color: «¿Este color es claro u
 *      oscuro?» y nada que mirar. Se les copia el dibujo de su estación.
 *   6. Casos sueltos: «¿Esta palabra lleva tilde?» sin la palabra, «¿Lleva
 *      la letra?» sin decir cuál, rimas con un 💬 en vez de la palabra.
 *
 * Cada regla reconoce el defecto por su forma y deja el resultado en una
 * forma que ya no lo cumple: repetirla no cambia nada. Toca solo
 * `activity_stations.config` (catálogo). Nada de usuarios ni de progreso.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Instrucciones claras (revision global)\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";


// ── Utilidades ───────────────────────────────────────────────────────

/** Sin letras ni números: solo dibujos. */
function soloDibujos(string $t): bool
{
    $t = trim($t);
    return $t !== '' && !preg_match('/[\p{L}\p{N}]/u', $t);
}

/** Separa una tira de emojis en dibujos sueltos, con o sin espacios. */
function dibujosDe(string $tira): array
{
    $tira = trim(str_replace('…', '', $tira));

    // Con espacios, cada trozo es un dibujo.
    if (preg_match('/\s/u', $tira)) {
        return array_values(array_filter(preg_split('/\s+/u', $tira), static fn($g) => $g !== ''));
    }

    // Pegados —«🍎🍎🍎»—: carácter a carácter, sin separar lo que va
    // unido al anterior (variación ️, unión ZWJ, tono de piel, tecla ⃣).
    // No se usa \X: según la versión de PCRE junta varios emojis en uno.
    $out = [];
    $unir = false;
    foreach (mb_str_split($tira) as $ch) {
        $cp = mb_ord($ch);
        $modificador = in_array($cp, [0xFE0F, 0xFE0E, 0x200D, 0x20E3], true) || ($cp >= 0x1F3FB && $cp <= 0x1F3FF);
        if ($out && ($modificador || $unir)) {
            $out[count($out) - 1] .= $ch;
        } else {
            $out[] = $ch;
        }
        $unir = ($cp === 0x200D);
    }
    return $out;
}

// Preguntas que se quedaban en el aire: lo que les falta, por actividad.
const PREGUNTA_QUE_FALTA = [
    'teatro-de-los-valores'       => '¿Qué harías tú?',
    'estimar-antes-de-calcular'   => '¿Tiene sentido?',
    'mitos-sobre-la-alimentacion' => '¿Es mito o realidad?',
    'planeta-del-espacio'         => '¿Qué planeta es?',
    'quien-hizo-que'              => '¿Verdadero o falso?',
];

// «¿Esta palabra lleva tilde?»: la palabra solo estaba en las opciones.
const DIBUJO_DE_PALABRA = [
    'CAMION' => '🚚', 'ARBOL' => '🌳', 'LAPIZ' => '✏️',
    'CAMA'   => '🛏️', 'MUSICA' => '🎵', 'MESA' => 'icono:mesa',
];

// Figuras que solo se mostraban como un cuadro de color.
const DIBUJO_DE_FIGURA = [
    'Círculo' => 'icono:figura-circulo', 'Cuadrado' => 'icono:figura-cuadrado',
    'Triángulo' => 'icono:figura-triangulo', 'Rectángulo' => 'icono:figura-rectangulo',
    'Estrella' => '⭐', 'Corazón' => '❤️',
];

// Rimas: el dibujo de la palabra, en vez de un 💬.
const DIBUJO_DE_RIMA = [
    'Gato' => '🐱', 'Pelota' => '⚽', 'Ratón' => '🐭',
    'Luna' => '🌙', 'Flor' => '🌸', 'Casa' => '🏠',
];


/**
 * Aplica las reglas a una pregunta ya llevada a la forma común
 * {texto, visual, tipo, opciones, correcta}. Devuelve la regla que la
 * cambió, o null si no había nada que hacer.
 */
function arreglar(array &$p, string $slug): ?string
{
    $t = trim($p['texto']);
    $o = array_map('strval', $p['opciones']);
    $correcta = $o[$p['correcta']] ?? null;

    // 7 · «¿Cómo se llama esta figura?» mostrando solo un cuadro de color.
    if ($t === '¿Cómo se llama esta figura?' && $p['tipo'] === 'color' && isset(DIBUJO_DE_FIGURA[$correcta])) {
        $p['visual'] = DIBUJO_DE_FIGURA[$correcta];
        $p['tipo']   = 'emoji';
        return 'la figura, no un cuadro de color';
    }

    // 8 · «¿Qué color se asocia con la calma?» con la muestra azul: regalada.
    if ($p['tipo'] === 'color' && preg_match('/^¿(De )?[Qq]ué color/u', $t)) {
        $p['visual'] = null;
        $p['tipo']   = 'ninguno';
        return 'el color era la respuesta';
    }

    // 6a · Tilde sin la palabra: se pregunta cómo se escribe y se dibuja.
    if ($t === '¿Esta palabra lleva tilde?' && isset($o[0]) && str_contains($o[0], '→')) {
        $limpias = array_map(static fn($x) => trim(preg_replace('/^(Sí|No)\s*→\s*/u', '', $x)), $o);
        $sinTilde = strtr($limpias[0], ['Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U']);
        $p['texto']    = '¿Cómo se escribe bien?';
        $p['opciones'] = $limpias;
        $p['visual']   = DIBUJO_DE_PALABRA[$sinTilde] ?? null;
        $p['tipo']     = $p['visual'] ? 'emoji' : 'ninguno';
        $p['sinDibujosEnOpciones'] = true;
        return 'tilde sin la palabra';
    }

    // 6b · Rima con 💬: el dibujo de la palabra.
    if (preg_match('/^¿Qué palabra rima con "(\p{L}+)"\?$/u', $t, $m)
        && isset(DIBUJO_DE_RIMA[$m[1]]) && $p['visual'] !== DIBUJO_DE_RIMA[$m[1]]) {
        $p['visual'] = DIBUJO_DE_RIMA[$m[1]];
        $p['tipo']   = 'emoji';
        return 'rima con dibujo';
    }

    // 1b · «🐶 🐱 🐰 ¿cuál NO está? El pez o el gato…»
    if (preg_match('/^([^\p{L}\p{N}¿]+)¿cuál NO está\?/u', $t, $m)) {
        $p['texto']  = 'Mira la fila. ¿Cuál NO está en la fila?';
        $p['visual'] = dibujosDe($m[1]);
        $p['tipo']   = 'serie';
        return 'cuál no está, en fila';
    }

    // 1 · Serie dentro del texto: «🔺 🔵 🔺 🔵 🔺 … ¿qué sigue?»
    if (preg_match('/^([^\p{L}\p{N}¿]+)¿qué sigue\?$/u', $t, $m) && count(dibujosDe($m[1])) >= 2) {
        $p['texto']  = 'Mira la fila. ¿Qué sigue?';
        $p['visual'] = [...dibujosDe($m[1]), '?'];
        $p['tipo']   = 'serie';
        return 'serie en casillas';
    }

    // 2b · «🍎🍎🍎🍎 o 🍎🍎. ¿Dónde hay más?»
    if (preg_match('/^([^\p{L}\p{N}\s]+)\s+o\s+([^\p{L}\p{N}\s.]+)\.\s*¿Dónde hay más\?$/u', $t, $m)) {
        $p['texto']  = '¿Dónde hay más?';
        $p['visual'] = [dibujosDe($m[1]), dibujosDe($m[2])];
        $p['tipo']   = 'grupos';
        return 'comparar dos grupos';
    }

    // 2 · «🐶🐶 ¿cuántos?» / «🍎🍎🍎 ¿cuántas manzanas?»
    if (preg_match('/^([^\p{L}\p{N}¿]+)¿cuánt([oa]s)([^?]*)\?$/u', $t, $m)) {
        $p['texto']  = '¿Cuánt' . $m[2] . rtrim($m[3]) . ' hay? Cuéntalos.';
        if ($m[2] === 'as') $p['texto'] = '¿Cuánt' . $m[2] . rtrim($m[3]) . ' hay? Cuéntalas.';
        $p['visual'] = dibujosDe($m[1]);
        $p['tipo']   = 'serie';
        return 'contar en casillas';
    }

    // 4 · Escenario o afirmación sin pregunta.
    if (isset(PREGUNTA_QUE_FALTA[$slug]) && !str_contains($t, '?') && preg_match('/[.»]$/u', $t)) {
        $p['texto'] = $t . ' ' . PREGUNTA_QUE_FALTA[$slug];
        return 'faltaba la pregunta';
    }

    // 3 · El dibujo de arriba es la respuesta.
    if ($p['tipo'] === 'emoji' && is_string($p['visual']) && $correcta !== null
        && trim($p['visual']) === trim($correcta)
        && count(array_filter($o, 'soloDibujos')) === count($o)) {
        $p['visual'] = null;
        $p['tipo']   = 'ninguno';
        // Una palabra suelta —«Perro»— no dice qué hacer con ella.
        if (!preg_match('/[?¿:.!…]/u', $t)) {
            $p['texto'] = 'Busca y toca: ' . $t;
        }
        return 'el dibujo era la respuesta';
    }

    return null;
}


// ── Recorrido ────────────────────────────────────────────────────────

$porRegla = [];
$estacionesTocadas = 0;

$actividades = traerTodo("SELECT id, slug FROM activities WHERE status = 'published' ORDER BY slug");

foreach ($actividades as $act) {
    $slug = $act['slug'];
    $estaciones = traerTodo(
        "SELECT id, position, title, game_type, config FROM activity_stations
          WHERE activity_id = ? AND game_type IN ('opcion_multiple','desafio_final','juego_rapido')
          ORDER BY position",
        [$act['id']]
    );

    // Lo que cada pregunta de estación muestra, para dárselo después a
    // su copia en el reto. Clave: texto + opciones + respuesta.
    $dibujoDe = [];
    $clave = static fn(string $t, array $o, int $a) => $t . '|' . json_encode(array_map('strval', $o), JSON_UNESCAPED_UNICODE) . '|' . $a;

    // Opción múltiple primero, luego los retos (que copian de ellas).
    usort($estaciones, static fn($x, $y) => [$x['game_type'] === 'desafio_final', $x['position']] <=> [$y['game_type'] === 'desafio_final', $y['position']]);

    foreach ($estaciones as $st) {
        $config = json_decode((string) $st['config'], true);
        if (!is_array($config) || !isset($config['datos'])) {
            continue;
        }
        $datos  = $config['datos'];
        $cambio = false;

        // 6c · Juego rápido que pregunta «¿Lleva la letra?» sin decir cuál.
        if ($st['game_type'] === 'juego_rapido') {
            if ($slug === 'vocales-parte-2' && array_is_list($datos)) {
                // Las que valen son ELEFANTE, ESTRELLA y ERIZO: la E.
                // ERIZO estaba dos veces; la segunda pasa a ESCOBA.
                $vistos = [];
                foreach ($datos as $k => $it) {
                    if (($it['n'] ?? '') === 'ERIZO' && isset($vistos['ERIZO'])) {
                        $datos[$k] = ['e' => '🧹', 'n' => 'ESCOBA', 'ok' => true];
                    }
                    $vistos[$it['n'] ?? ''] = true;
                }
                $config['datos'] = ['t' => '¿Empieza con la E?', 's' => 'Mira el dibujo y toca Sí o No', 'items' => $datos];
                $porRegla['juego rápido sin la letra'][] = "$slug E{$st['position']}";
                $cambio = true;
            }
            if ($cambio) {
                guardar($st, $config, $aplicar);
                $estacionesTocadas++;
            }
            continue;
        }

        $om = $st['game_type'] === 'opcion_multiple';

        foreach ($datos as $k => $it) {
            if (!is_array($it)) continue;

            // Forma común.
            if ($om) {
                $p = ['texto' => (string) ($it['enunciado'] ?? ''), 'visual' => $it['visual'] ?? null,
                      'tipo' => $it['tipoVisual'] ?? 'ninguno', 'opciones' => $it['opciones'] ?? [], 'correcta' => (int) ($it['correcta'] ?? -1)];
            } else {
                $tipo = $it['tipoVisual'] ?? (isset($it['serie']) ? 'serie' : (isset($it['e']) ? 'emoji' : 'ninguno'));
                $p = ['texto' => (string) ($it['q'] ?? ''), 'visual' => $it['visual'] ?? ($it['serie'] ?? ($it['e'] ?? null)),
                      'tipo' => $tipo, 'opciones' => $it['opts'] ?? [], 'correcta' => (int) ($it['a'] ?? -1)];
            }
            $antes = $p;
            $regla = arreglar($p, $slug);

            // 5 · Reto sin dibujo: el de su pregunta de estación.
            if (!$om && $regla === null && $p['tipo'] === 'ninguno') {
                $de = $dibujoDe[$clave(trim($p['texto']), $p['opciones'], $p['correcta'])] ?? null;
                if ($de !== null) {
                    [$p['visual'], $p['tipo']] = $de;
                    $regla = 'el reto recupera su dibujo';
                }
            }

            if ($om && !in_array($p['tipo'], ['ninguno', ''], true) && $p['visual'] !== null) {
                $dibujoDe[$clave(trim($antes['texto']), $antes['opciones'], $antes['correcta'])] = [$p['visual'], $p['tipo']];
                $dibujoDe[$clave(trim($p['texto']), $p['opciones'], $p['correcta'])] = [$p['visual'], $p['tipo']];
            }

            if ($regla === null) continue;

            // De vuelta a la forma de cada minijuego.
            if ($om) {
                $datos[$k]['enunciado']  = $p['texto'];
                $datos[$k]['visual']     = $p['visual'];
                $datos[$k]['tipoVisual'] = $p['tipo'];
                $datos[$k]['opciones']   = $p['opciones'];
            } else {
                $datos[$k]['q']    = $p['texto'];
                $datos[$k]['opts'] = $p['opciones'];
                unset($datos[$k]['e'], $datos[$k]['serie']);
                if ($p['tipo'] === 'ninguno' || $p['visual'] === null) {
                    unset($datos[$k]['visual'], $datos[$k]['tipoVisual']);
                } elseif ($p['tipo'] === 'emoji') {
                    unset($datos[$k]['visual'], $datos[$k]['tipoVisual']);
                    $datos[$k]['e'] = $p['visual'];
                } else {
                    $datos[$k]['visual']     = $p['visual'];
                    $datos[$k]['tipoVisual'] = $p['tipo'];
                }
            }
            if (!empty($p['sinDibujosEnOpciones'])) {
                unset($datos[$k]['dibujos']);
            }

            $porRegla[$regla][] = "$slug E{$st['position']} P" . ($k + 1) . ": «{$antes['texto']}» → «{$p['texto']}»";
            $cambio = true;
        }

        if ($cambio) {
            $config['datos'] = $datos;
            guardar($st, $config, $aplicar);
            $estacionesTocadas++;
        }
    }
}

function guardar(array $st, array $config, bool $aplicar): void
{
    if ($aplicar) {
        ejecutar(
            'UPDATE activity_stations SET config = ? WHERE id = ?',
            [json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), (int) $st['id']]
        );
    }
}

$total = 0;
foreach ($porRegla as $regla => $lista) {
    $total += count($lista);
    echo "-- $regla (" . count($lista) . ")\n";
    foreach ($lista as $l) echo "   $l\n";
    echo "\n";
}

echo "$total pregunta(s) en $estacionesTocadas estacion(es) " . ($aplicar ? 'corregidas.' : 'por corregir.') . "\n";
if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
