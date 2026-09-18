<?php
/**
 * arreglar-contenido.php — Correcciones de contenido heredado
 *
 *     php database/arreglar-contenido.php            (simulación)
 *     php database/arreglar-contenido.php --aplicar  (escribe)
 *
 * Los migradores trajeron el contenido del sitio anterior tal como
 * estaba, y con él sus defectos. Este archivo corrige los ejercicios que
 * un niño NO PUEDE resolver aunque entienda perfectamente la pregunta,
 * que son los peores de todos: el niño concluye que se equivocó él.
 *
 * Cada corrección dice qué estaba mal y por qué. No se toca nada por
 * gusto estético.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);


/** Arma un ejercicio de «¿cuál es más grande?» con el grande en su sitio. */
function comparar(string $pequeno, string $grande): array
{
    // El grande va unas veces primero y otras después, para que no se
    // pueda acertar siempre tocando el mismo lado sin mirar.
    $opciones = mt_rand(0, 1) === 1 ? [$pequeno, $grande] : [$grande, $pequeno];

    return [
        'enunciado'  => '¿Cuál es más grande?',
        'visual'     => null,
        'tipoVisual' => 'ninguno',
        'opciones'   => $opciones,
        'correcta'   => (int) array_search($grande, $opciones, true),
    ];
}

mt_srand(20260827);   // mismo resultado en cada ejecución

$CORRECCIONES = [

    /*
     * Preescolar · Tamaños y Posiciones — estación 1
     *
     * Cuatro de las ocho comparaciones no tenían respuesta posible:
     *
     *   🏠 vs 🏡   son las dos una casa; la segunda solo añade un jardín
     *   ⛰️ vs 🏔️   la diferencia entre ambas es la nieve, no el tamaño
     *   🐂 vs 🐄   un buey y una vaca miden prácticamente lo mismo
     *   🌻 vs 🌸   depende de cómo dibuje cada tipografía las dos flores
     *
     * Se reemplazan por pares donde la diferencia de tamaño es evidente
     * para un niño de cuatro años y no depende de la fuente de emojis
     * que use su teléfono. Las cuatro que sí funcionaban se conservan.
     */
    [
        'actividad'  => 'tamanos-y-posiciones',
        'posicion'   => 1,
        'motivo'     => '4 de 8 comparaciones no tenían una respuesta observable',
        'datos'      => [
            comparar('🐭', '🐘'),
            comparar('🌱', '🌳'),
            comparar('🐟', '🐋'),
            comparar('🐱', '🦁'),
            comparar('🐜', '🐕'),
            comparar('🚲', '🚌'),
            comparar('🐔', '🐴'),
            comparar('🍒', '🍉'),
        ],
    ],

    /*
     * Preescolar · Tamaños y Posiciones — estación 4
     *
     * Pedía ordenar 🐱 · 🐈 · 🦁 del más pequeño al más grande. Los dos
     * primeros son el mismo animal (una es la cara del gato y la otra el
     * gato entero), así que el orden entre ellos era imposible de deducir
     * mirando: había que adivinar.
     *
     * Se cambia por una escala de vehículos, donde cada salto de tamaño
     * es real y además el niño ya conoce los objetos.
     */
    [
        'actividad'  => 'tamanos-y-posiciones',
        'posicion'   => 4,
        'motivo'     => 'pedía ordenar por tamaño 🐱 y 🐈, que son el mismo animal',
        'datos'      => [
            'title' => 'Ordena del más pequeño al más grande',
            'items' => ['🚲', '🚗', '🚌', '✈️', '🚢'],
        ],
    ],

    /*
     * Ciber-Código Robot — estación 1
     *
     * Preguntaba «Botto está en [0,0] y debe llegar a [0,3], ¿qué
     * secuencia necesita?» con cuatro opciones de texto. El niño tenía
     * que dibujarse la cuadrícula en la cabeza, y al fallar no sabía por
     * qué: no había nada que mirar.
     *
     * Ahora usa el motor `laberinto`: ve el tablero, ve al robot, ve la
     * meta, arma la secuencia con flechas y el robot la recorre delante
     * de él. Los cuatro niveles van de un movimiento recto a un giro.
     */
    [
        'actividad'  => 'ciber-codigo-robot',
        'posicion'   => 1,
        'tipo'       => 'laberinto',
        'titulo'     => 'Guía a Botto',
        'descripcion' => 'Arma el camino y mira al robot recorrerlo',
        // 🕹️ y no 🤖: es el ícono que llevaba esta pantalla en el sitio
        // anterior, y el robot ya está dentro del tablero.
        'icono'      => '🕹️',
        'motivo'     => 'pedía imaginar una cuadrícula que nunca se mostraba',

        /*
         * Los cuatro niveles son EXACTAMENTE los del sitio anterior
         * (`Ciber_Codigo_Robot.html`, constante `levels`). No se
         * reinventan: el juego ya existía y estaba bien pensado — lo que
         * faltaba era traerlo, porque la migración lo había convertido en
         * cuatro preguntas de texto sobre coordenadas.
         *
         *   1. recta de 3 pasos, sin muros
         *   2. subir y luego avanzar, con un muro en el medio
         *   3. escalera: derecha-arriba repetido
         *   4. escalera al revés: arriba-derecha repetido
         */
        'datos'      => [
            [
                'titulo' => 'Guía a Botto',
                'filas' => 4, 'columnas' => 4,
                'inicio' => [0, 0], 'meta' => [0, 3],
                'muros' => [],
            ],
            [
                'titulo' => 'Guía a Botto',
                'filas' => 4, 'columnas' => 4,
                'inicio' => [3, 0], 'meta' => [0, 3],
                'muros' => [[1, 1], [2, 1]],
            ],
            [
                'titulo' => 'Guía a Botto',
                'filas' => 4, 'columnas' => 4,
                'inicio' => [3, 0], 'meta' => [0, 3],
                'muros' => [[2, 1], [1, 2]],
            ],
            [
                'titulo' => 'Guía a Botto',
                'filas' => 4, 'columnas' => 4,
                'inicio' => [3, 0], 'meta' => [0, 3],
                'muros' => [[2, 2], [1, 1], [1, 3]],
            ],
        ],
    ],
];


// =====================================================================

echo str_repeat('=', 76), "\n";
echo "  CORRECCIONES DE CONTENIDO\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 76), "\n\n";

$hechas = 0;
$avisos = [];

foreach ($CORRECCIONES as $c) {

    $estacion = traerUno(
        'SELECT s.id, s.title, s.description, s.icon, s.game_type, s.config
           FROM activity_stations s
           JOIN activities a ON a.id = s.activity_id
          WHERE a.slug = ? AND s.position = ?',
        [$c['actividad'], $c['posicion']]
    );

    if (!$estacion) {
        $avisos[] = "{$c['actividad']} #{$c['posicion']}: no existe";
        continue;
    }

    // Se conserva todo lo demás del config (idioma, sonido…) y solo se
    // reemplazan los datos del ejercicio.
    $config = json_decode($estacion['config'] ?? 'null', true);
    $config = is_array($config) ? $config : [];
    $config['datos'] = $c['datos'];

    printf("  %-24s #%d  %s\n", $c['actividad'], $c['posicion'], $estacion['title']);
    printf("  %-24s     motivo: %s\n", '', $c['motivo']);

    // Algunas correcciones cambian de minijuego, no solo de datos.
    $cambiaTipo = isset($c['tipo']);
    if ($cambiaTipo) {
        printf("  %-24s     minijuego: %s → %s\n", '', $estacion['game_type'], $c['tipo']);
    }
    echo "\n";

    if ($aplicar) {
        if ($cambiaTipo) {
            ejecutar(
                'UPDATE activity_stations
                    SET config = ?, game_type = ?, title = ?, description = ?, icon = ?
                  WHERE id = ?',
                [
                    json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    $c['tipo'],
                    $c['titulo'] ?? $estacion['title'],
                    $c['descripcion'] ?? $estacion['description'],
                    $c['icono'] ?? $estacion['icon'],
                    $estacion['id'],
                ]
            );
        } else {
            ejecutar(
                'UPDATE activity_stations SET config = ? WHERE id = ?',
                [json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $estacion['id']]
            );
        }
    }

    $hechas++;
}

echo str_repeat('-', 76), "\n";
printf("  Estaciones corregidas: %d\n", $hechas);

if ($avisos) {
    echo "\n  Avisos:\n";
    foreach ($avisos as $a) {
        echo "    · $a\n";
    }
}

echo $aplicar
    ? "\n  Aplicado. Conviene volver a pasar validar-estaciones.php.\n"
    : "\n  Simulación. Para escribir: php database/arreglar-contenido.php --aplicar\n";

echo str_repeat('=', 76), "\n";
