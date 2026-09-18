<?php
/**
 * migrar-extras.php — Migración de las actividades restantes
 *
 *     php database/migrar-extras.php            (simulación)
 *     php database/migrar-extras.php --aplicar  (escribe)
 *
 * Estas actividades no comparten plantilla entre sí: cada una guarda sus
 * datos a su manera. Por eso aquí hay un convertidor explícito por
 * archivo, en vez del reconocimiento por estructura que usan los mundos.
 *
 * Se prefiere ser explícito antes que adivinar: un dato mal interpretado
 * produce un ejercicio con la respuesta equivocada, y eso lo descubriría
 * un niño, no una prueba.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/lector-js.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);
$origen  = 'D:/xampp/htdocs/ACTIVIDADES EN LINEA/sitio1-free';

if (!is_dir($origen)) {
    exit("No se encuentra la carpeta de origen:\n  $origen\n");
}

const VOCALES_ES = ['A', 'E', 'I', 'O', 'U'];


// =====================================================================
//  AYUDANTES
// =====================================================================

/**
 * Construye un ejercicio de opción múltiple con la forma que espera el
 * motor, colocando la respuesta correcta en una posición al azar.
 */
function pregunta(string $enunciado, array $opciones, $correcta, $visual = null, string $tipoVisual = 'ninguno'): ?array
{
    $opciones = array_values($opciones);
    $indice = array_search($correcta, $opciones, false);

    if ($indice === false) {
        return null;
    }

    return [
        'enunciado'  => $enunciado,
        'visual'     => $visual,
        'tipoVisual' => $visual === null ? 'ninguno' : $tipoVisual,
        'opciones'   => $opciones,
        'correcta'   => (int) $indice,
    ];
}

/** Baraja conservando los valores. */
function barajado(array $a): array
{
    shuffle($a);
    return $a;
}


// =====================================================================
//  CONVERTIDORES, UNO POR ACTIVIDAD
//
//  Cada uno devuelve una lista de estaciones:
//    ['titulo', 'descripcion', 'icono', 'tipo', 'datos']
// =====================================================================

/** Ruleta de la Fortuna: preguntas agrupadas por categoría. */
function convertirRuleta(string $html): array
{
    $preguntas = leerConstante($html, 'questions');
    if (!is_array($preguntas)) {
        return [];
    }

    $estaciones = [];

    foreach ($preguntas as $categoria => $lista) {
        if (!is_array($lista)) {
            continue;
        }

        $items = [];
        foreach ($lista as $q) {
            if (!isset($q['q'], $q['ans'], $q['opts'])) {
                continue;
            }
            $p = pregunta($q['q'], $q['opts'], $q['ans']);
            if ($p) {
                $items[] = $p;
            }
        }

        if ($items) {
            $estaciones[] = [
                'titulo'      => (string) $categoria,
                'descripcion' => 'Preguntas de ' . mb_strtolower((string) $categoria),
                'icono'       => '🎡',
                'tipo'        => 'opcion_multiple',
                'datos'       => $items,
            ];
        }
    }

    return $estaciones;
}


/** Desafío de Memoria: un conjunto de emojis por nivel. */
function convertirMemoria(string $html): array
{
    $conjuntos = leerConstante($html, 'emojiSets');
    if (!is_array($conjuntos)) {
        return [];
    }

    $estaciones = [];
    $n = 0;

    foreach ($conjuntos as $set) {
        if (!is_array($set) || count($set) < 2) {
            continue;
        }
        $n++;

        // Se limita el tamaño para que el tablero quepa en un teléfono.
        $emojis = array_slice(array_values($set), 0, 8);

        $estaciones[] = [
            'titulo'      => 'Nivel ' . $n,
            'descripcion' => 'Encuentra las ' . count($emojis) . ' parejas',
            'icono'       => '🧠',
            'tipo'        => 'memoria',
            'datos'       => $emojis,
        ];
    }

    return $estaciones;
}


/** Paquete de Vocales · Parte 1 */
function convertirPaquete1(string $html): array
{
    $estaciones = [];

    // ¿Con qué vocal empieza la palabra?
    $a1 = leerConstante($html, 'A1_DATA');
    if (is_array($a1)) {
        $items = [];
        foreach ($a1 as $x) {
            if (!isset($x['word'], $x['v'])) {
                continue;
            }
            $p = pregunta('¿Con qué vocal empieza ' . $x['word'] . '?',
                          VOCALES_ES, $x['v'], $x['img'] ?? null, 'emoji');
            if ($p) {
                $items[] = $p;
            }
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'La vocal inicial',
                             'descripcion' => 'Descubre con qué vocal empieza cada palabra',
                             'icono' => '🔤', 'tipo' => 'opcion_multiple', 'datos' => $items];
        }
    }

    // ¿Qué vocal falta en la serie?
    $a3 = leerConstante($html, 'A3_DATA');
    if (is_array($a3)) {
        $items = [];
        foreach ($a3 as $x) {
            if (!isset($x['seq'], $x['ans'])) {
                continue;
            }
            $serie = implode('  ', array_map(
                static fn($s) => $s === '_' ? '?' : (string) $s,
                $x['seq']
            ));
            $p = pregunta('¿Qué vocal falta?   ' . $serie, VOCALES_ES, $x['ans']);
            if ($p) {
                $items[] = $p;
            }
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'La serie de las vocales',
                             'descripcion' => 'Completa el orden A · E · I · O · U',
                             'icono' => '🧩', 'tipo' => 'opcion_multiple', 'datos' => $items];
        }
    }

    // Emparejar imagen con palabra
    $a5 = leerConstante($html, 'A5_ITEMS');
    if (is_array($a5)) {
        $pares = [];
        foreach ($a5 as $x) {
            if (isset($x['img'], $x['word'])) {
                $pares[] = ['e' => $x['img'], 'w' => $x['word']];
            }
        }
        if (count($pares) >= 2) {
            $estaciones[] = ['titulo' => 'Une imagen y palabra',
                             'descripcion' => 'Empareja cada dibujo con su nombre',
                             'icono' => '🔗', 'tipo' => 'emparejar', 'datos' => $pares];
        }
    }

    return $estaciones;
}


/** Paquete de Vocales · Parte 2 */
function convertirPaquete2(string $html): array
{
    $estaciones = [];

    $a1 = leerConstante($html, 'A1_DATA');
    if (is_array($a1)) {
        $items = [];
        foreach ($a1 as $x) {
            if (!isset($x['word'], $x['v'])) {
                continue;
            }
            $p = pregunta('¿Con qué vocal empieza ' . $x['word'] . '?',
                          VOCALES_ES, $x['v'], $x['img'] ?? null, 'emoji');
            if ($p) {
                $items[] = $p;
            }
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'La vocal inicial',
                             'descripcion' => 'Repasa con qué vocal empieza cada palabra',
                             'icono' => '🔤', 'tipo' => 'opcion_multiple', 'datos' => $items];
        }
    }

    // ¿Qué vocal suena en esta sílaba?
    $a2 = leerConstante($html, 'A2_DATA');
    if (is_array($a2)) {
        $items = [];
        foreach ($a2 as $x) {
            if (!isset($x['s'], $x['v'])) {
                continue;
            }
            $p = pregunta('¿Qué vocal suena en «' . $x['s'] . '»?', VOCALES_ES, $x['v']);
            if ($p) {
                $items[] = $p;
            }
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'La vocal de la sílaba',
                             'descripcion' => 'Escucha qué vocal suena en cada sílaba',
                             'icono' => '🎵', 'tipo' => 'opcion_multiple', 'datos' => $items];
        }
    }

    // ¿Cuántas vocales tiene la palabra?
    $a3 = leerConstante($html, 'A3_DATA');
    if (is_array($a3)) {
        $items = [];
        foreach ($a3 as $x) {
            if (!isset($x['word'], $x['count'])) {
                continue;
            }
            $c = (int) $x['count'];
            $opciones = array_values(array_unique([$c, max(1, $c - 1), $c + 1, $c + 2]));
            sort($opciones);
            $p = pregunta('¿Cuántas vocales tiene ' . $x['word'] . '?', $opciones, $c);
            if ($p) {
                $items[] = $p;
            }
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'Cuenta las vocales',
                             'descripcion' => 'Cuenta cuántas vocales hay en cada palabra',
                             'icono' => '🔢', 'tipo' => 'opcion_multiple', 'datos' => $items];
        }
    }

    // Completar la palabra con la vocal que falta
    $a5 = leerConstante($html, 'A5_DATA');
    if (is_array($a5)) {
        $items = [];
        foreach ($a5 as $x) {
            if (!isset($x['display'], $x['ans'])) {
                continue;
            }
            $partes = explode('_', (string) $x['display'], 2);
            $items[] = [
                'before' => $partes[0] ?? '',
                'after'  => $partes[1] ?? '',
                'e'      => $x['emoji'] ?? '🔤',
                'a'      => (string) $x['ans'],
            ];
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'Completa la palabra',
                             'descripcion' => 'Pon la vocal que falta',
                             'icono' => '✏️', 'tipo' => 'completar_palabra', 'datos' => $items];
        }
    }

    // ¿Empieza con la vocal indicada? Sí o no.
    $a6 = leerConstante($html, 'A6_DATA');
    if (is_array($a6)) {
        $items = [];
        foreach ($a6 as $x) {
            if (!isset($x['word'], $x['v'])) {
                continue;
            }
            $items[] = [
                'e'  => $x['img'] ?? '🔤',
                'n'  => $x['word'],
                'ok' => !empty($x['yes']),
            ];
        }
        if ($items) {
            $estaciones[] = ['titulo' => 'Sí o no',
                             'descripcion' => 'Decide rápido si la palabra corresponde',
                             'icono' => '🎮', 'tipo' => 'juego_rapido', 'datos' => $items];
        }
    }

    return $estaciones;
}


/** Preescolar · Formas y Colores */
function convertirFormasColores(string $html): array
{
    $estaciones = [];

    $colores = leerConstante($html, 'COLS');
    $items   = leerConstante($html, 'COL_ITEMS');
    $formas  = leerConstante($html, 'SHAPES');

    // ¿De qué color es este objeto?
    if (is_array($colores) && is_array($items)) {
        $nombres = array_map(static fn($c) => $c['n'] ?? '', $colores);
        $ejercicios = [];

        foreach ($items as $it) {
            if (!isset($it['ci'], $it['e'])) {
                continue;
            }
            $nombre = $nombres[$it['ci']] ?? null;
            if ($nombre === null) {
                continue;
            }
            // Cuatro opciones: la correcta y tres colores distintos.
            $otros = array_values(array_filter($nombres, static fn($n) => $n !== $nombre));
            $opciones = array_slice(barajado($otros), 0, 3);
            $opciones[] = $nombre;

            $p = pregunta('¿De qué color es?', barajado($opciones), $nombre, $it['e'], 'emoji');
            if ($p) {
                $ejercicios[] = $p;
            }
        }

        if ($ejercicios) {
            $estaciones[] = ['titulo' => 'Los colores',
                             'descripcion' => 'Reconoce el color de cada objeto',
                             'icono' => '🎨', 'tipo' => 'opcion_multiple',
                             'datos' => array_slice($ejercicios, 0, 12)];
        }
    }

    // ¿Cómo se llama esta figura?
    if (is_array($formas)) {
        $nombres = array_values(array_filter(array_map(static fn($s) => $s['n'] ?? null, $formas)));
        $ejercicios = [];

        foreach ($formas as $f) {
            if (!isset($f['n'])) {
                continue;
            }
            $otros = array_values(array_filter($nombres, static fn($n) => $n !== $f['n']));
            $opciones = array_slice(barajado($otros), 0, 3);
            $opciones[] = $f['n'];

            $p = pregunta('¿Cómo se llama esta figura?', barajado($opciones), $f['n'],
                          $f['clr'] ?? null, 'color');
            if ($p) {
                $ejercicios[] = $p;
            }
        }

        if ($ejercicios) {
            $estaciones[] = ['titulo' => 'Las figuras',
                             'descripcion' => 'Reconoce círculos, cuadrados y triángulos',
                             'icono' => '🔺', 'tipo' => 'opcion_multiple', 'datos' => $ejercicios];
        }
    }

    // ¿Cuál es diferente?
    $a4 = leerConstante($html, 'A4_DATA');
    if (is_array($a4)) {
        $ejercicios = [];
        foreach ($a4 as $x) {
            if (!isset($x['items'], $x['odd'])) {
                continue;
            }
            $opciones = array_values($x['items']);
            $indice = (int) $x['odd'];
            if (!isset($opciones[$indice])) {
                continue;
            }
            $ejercicios[] = [
                'enunciado'  => '¿Cuál es diferente?',
                'visual'     => null,
                'tipoVisual' => 'ninguno',
                'opciones'   => $opciones,
                'correcta'   => $indice,
            ];
        }
        if ($ejercicios) {
            $estaciones[] = ['titulo' => 'El diferente',
                             'descripcion' => 'Encuentra el que no encaja',
                             'icono' => '🔍', 'tipo' => 'opcion_multiple', 'datos' => $ejercicios];
        }
    }

    return $estaciones;
}


/** Preescolar · Tamaños y Posiciones */
function convertirTamanos(string $html): array
{
    $estaciones = [];

    // ¿Cuál es más grande?
    $comparar = leerConstante($html, 'COMPARE');
    if (is_array($comparar)) {
        $ejercicios = [];
        foreach ($comparar as $c) {
            if (!isset($c['big'], $c['small'])) {
                continue;
            }
            $opciones = barajado([$c['big'], $c['small']]);
            $ejercicios[] = [
                'enunciado'  => '¿Cuál es más grande?',
                'visual'     => null,
                'tipoVisual' => 'ninguno',
                'opciones'   => $opciones,
                'correcta'   => (int) array_search($c['big'], $opciones, true),
            ];
        }
        if ($ejercicios) {
            $estaciones[] = ['titulo' => 'Grande y pequeño',
                             'descripcion' => 'Compara el tamaño de las cosas',
                             'icono' => '📏', 'tipo' => 'opcion_multiple', 'datos' => $ejercicios];
        }
    }

    // ¿Arriba o abajo?
    $posiciones = leerConstante($html, 'POSITIONS');
    if (is_array($posiciones)) {
        $ejercicios = [];
        foreach ($posiciones as $p) {
            if (!isset($p['emoji'])) {
                continue;
            }
            $correcta = !empty($p['isAbove']) ? 'Arriba' : 'Abajo';
            $q = pregunta(
                ($p['context'] ?? 'Mira el dibujo') . '. ¿Está arriba o abajo?',
                ['Arriba', 'Abajo'], $correcta, $p['emoji'], 'emoji'
            );
            if ($q) {
                $ejercicios[] = $q;
            }
        }
        if ($ejercicios) {
            $estaciones[] = ['titulo' => 'Arriba y abajo',
                             'descripcion' => 'Ubica dónde está cada cosa',
                             'icono' => '⬆️', 'tipo' => 'opcion_multiple', 'datos' => $ejercicios];
        }
    }

    // ¿Dentro o fuera?
    $dentroFuera = leerConstante($html, 'IN_OUT');
    if (is_array($dentroFuera)) {
        $ejercicios = [];
        foreach ($dentroFuera as $p) {
            if (!isset($p['emoji'])) {
                continue;
            }
            $correcta = !empty($p['isInside']) ? 'Dentro' : 'Fuera';
            $q = pregunta('¿Está dentro o fuera de la caja?',
                          ['Dentro', 'Fuera'], $correcta, $p['emoji'], 'emoji');
            if ($q) {
                $ejercicios[] = $q;
            }
        }
        if ($ejercicios) {
            $estaciones[] = ['titulo' => 'Dentro y fuera',
                             'descripcion' => 'Descubre dónde está cada objeto',
                             'icono' => '📦', 'tipo' => 'opcion_multiple', 'datos' => $ejercicios];
        }
    }

    // Ordenar de menor a mayor
    $orden = leerConstante($html, 'ORDER_DATA');
    if (is_array($orden) && isset($orden[0]['items'])) {
        $lista = $orden[0]['items'];
        usort($lista, static fn($a, $b) => ((float) ($a['s'] ?? 0)) <=> ((float) ($b['s'] ?? 0)));
        $items = array_values(array_filter(array_map(static fn($x) => $x['e'] ?? null, $lista)));

        if (count($items) > 1) {
            $estaciones[] = ['titulo' => 'Del más pequeño al más grande',
                             'descripcion' => 'Ordena por tamaño',
                             'icono' => '📐', 'tipo' => 'ordenar_secuencia',
                             'datos' => ['title' => 'Ordena del más pequeño al más grande',
                                         'items' => $items]];
        }
    }

    return $estaciones;
}


/**
 * Preescolar · Cuento hasta 10
 *
 * Este archivo genera sus ejercicios al vuelo con números al azar. Aquí
 * se produce un conjunto fijo siguiendo las mismas reglas, para que el
 * contenido quede guardado y sea el mismo para todos.
 */
function convertirContando(string $html): array
{
    $emojis = leerConstante($html, 'EMOJIS');
    $emojis = is_array($emojis) ? $emojis : ['⭐'];

    // Contar objetos del 1 al 10
    $contar = [];
    foreach ([3, 5, 2, 7, 4, 9, 6, 10, 1, 8] as $n) {
        $contar[] = ['op' => 'contar', 'a' => $n, 'b' => null, 'resultado' => $n];
    }

    // El número que falta en la serie
    $series = [];
    foreach ([[1, 2, null, 4, 5], [3, 4, 5, null, 7], [5, 6, null, 8, 9],
              [2, 3, 4, null, 6], [6, 7, 8, 9, null], [null, 2, 3, 4, 5]] as $s) {
        $indice  = array_search(null, $s, true);
        $anterior = $indice > 0 ? $s[$indice - 1] : null;
        $falta   = $anterior !== null ? $anterior + 1 : ($s[$indice + 1] - 1);
        $series[] = ['secuencia' => $s, 'falta' => $falta];
    }

    // ¿Cuál grupo tiene más?
    $comparar = [];
    foreach ([[3, 7], [5, 2], [1, 8], [6, 4], [2, 9], [4, 3]] as [$a, $b]) {
        $mayor = max($a, $b);
        $opciones = barajado([$a, $b]);
        $comparar[] = [
            'enunciado'  => '¿Qué número es mayor?',
            'visual'     => null,
            'tipoVisual' => 'ninguno',
            'opciones'   => $opciones,
            'correcta'   => (int) array_search($mayor, $opciones, true),
        ];
    }

    return [
        ['titulo' => 'Cuenta los objetos', 'descripcion' => 'Cuenta del 1 al 10',
         'icono' => '🔢', 'tipo' => 'operacion', 'datos' => $contar],
        ['titulo' => 'El número que falta', 'descripcion' => 'Completa la serie',
         'icono' => '🧩', 'tipo' => 'secuencia_numerica', 'datos' => $series],
        ['titulo' => 'Mayor y menor', 'descripcion' => 'Compara dos números',
         'icono' => '⚖️', 'tipo' => 'opcion_multiple', 'datos' => $comparar],
    ];
}


/**
 * Preescolar · Familias de Números
 *
 * También genera sus ejercicios al vuelo; se fija un conjunto siguiendo
 * las mismas reglas.
 */
function convertirFamilias(string $html): array
{
    // Descomponer un número: 5 = 2 + ?
    $descomponer = [];
    foreach ([[5, 2], [6, 4], [7, 3], [8, 5], [9, 6], [10, 4], [4, 1], [10, 7]] as [$total, $a]) {
        $falta = $total - $a;
        $opciones = array_values(array_unique([$falta, max(0, $falta - 1), $falta + 1, $falta + 2]));
        sort($opciones);
        $p = pregunta("$a + ?  =  $total", $opciones, $falta);
        if ($p) {
            $descomponer[] = $p;
        }
    }

    // Las decenas
    $decenas = [];
    foreach ([10, 20, 30, 40, 50, 60, 70, 80, 90, 100] as $d) {
        $opciones = array_values(array_unique([$d, max(10, $d - 10), $d + 10, $d + 20]));
        sort($opciones);
        $p = pregunta('¿Qué número es ' . str_repeat('🔟', min(10, intdiv($d, 10))) . '?', $opciones, $d);
        if ($p) {
            $decenas[] = $p;
        }
    }

    // Ordenar números
    $orden = ['title' => 'Ordena los números de menor a mayor',
              'items' => ['1', '2', '3', '4', '5', '6', '7', '8', '9', '10']];

    return [
        ['titulo' => 'Descompón el número', 'descripcion' => 'Encuentra la parte que falta',
         'icono' => '🏠', 'tipo' => 'opcion_multiple', 'datos' => $descomponer],
        ['titulo' => 'Las decenas', 'descripcion' => 'De 10 en 10 hasta 100',
         'icono' => '🔟', 'tipo' => 'opcion_multiple', 'datos' => $decenas],
        ['titulo' => 'Ordena del 1 al 10', 'descripcion' => 'Pon los números en orden',
         'icono' => '📊', 'tipo' => 'ordenar_secuencia', 'datos' => $orden],
    ];
}


// =====================================================================
//  QUÉ CONVERTIDOR USA CADA ARCHIVO
// =====================================================================

$CONVERTIDORES = [
    'Ruleta_de_la_Fortuna.html'    => 'convertirRuleta',
    'Desafio_de_Memoria.html'      => 'convertirMemoria',
    'Paquete_Vocales_Parte1.html'  => 'convertirPaquete1',
    'Paquete_Vocales_Parte2.html'  => 'convertirPaquete2',
    'Pre_Formas_Colores.html'      => 'convertirFormasColores',
    'Pre_Tamanos_Posiciones.html'  => 'convertirTamanos',
    'Pre_Contando.html'            => 'convertirContando',
    'Pre_Familias_Numeros.html'    => 'convertirFamilias',
];

/**
 * Actividades que NO se migran, y por qué.
 * Se listan a propósito: es información, no un olvido.
 */
const NO_MIGRABLES = [
    'Bosque_de_vocales.html' =>
        'es una página de navegación que lista las 5 vocales, no una actividad',
    'Reino_de_las_letras.html' =>
        'es una página de navegación que lista las letras, no una actividad',
    'Juegos_Vocales_1.html' =>
        'juego de acción (naves y nubes): genera todo al vuelo, no tiene ejercicios guardados',
    'Juegos_Vocales_2.html' =>
        'juego de acción (globos y pesca): genera todo al vuelo, no tiene ejercicios guardados',
];


// =====================================================================
//  MIGRACIÓN
// =====================================================================

echo str_repeat('=', 74), "\n";
echo "  MIGRACIÓN DE LAS ACTIVIDADES RESTANTES\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 74), "\n\n";

$totalEstaciones = 0;
$totalItems      = 0;
$migradas        = 0;
$avisos          = [];

$pendientes = traerTodo(
    'SELECT id, slug, title, legacy_file
       FROM activities WHERE legacy_file IS NOT NULL ORDER BY title'
);

foreach ($pendientes as $act) {

    $archivo = $act['legacy_file'];

    if (!isset($CONVERTIDORES[$archivo])) {
        continue;
    }

    $ruta = $origen . '/' . $archivo;
    if (!is_file($ruta)) {
        $avisos[] = "$archivo: archivo no encontrado";
        printf("  %-30s ✗ archivo no encontrado\n", $archivo);
        continue;
    }

    $html       = file_get_contents($ruta);
    $estaciones = $CONVERTIDORES[$archivo]($html);

    if (!$estaciones) {
        $avisos[] = "$archivo: el convertidor no produjo estaciones";
        printf("  %-30s ✗ sin estaciones\n", $archivo);
        continue;
    }

    if ($aplicar) {
        db()->beginTransaction();
    }

    try {
        if ($aplicar) {
            ejecutar('DELETE FROM activity_stations WHERE activity_id = ?', [$act['id']]);
        }

        $posicion = 0;
        $items    = 0;

        foreach ($estaciones as $est) {
            $posicion++;
            $items += isset($est['datos']['items'])
                ? count($est['datos']['items'])
                : count($est['datos']);

            if ($aplicar) {
                insertar(
                    'INSERT INTO activity_stations
                        (activity_id, position, title, description, icon, game_type, config, is_free)
                     VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                    [
                        $act['id'], $posicion, $est['titulo'], $est['descripcion'],
                        $est['icono'], $est['tipo'],
                        json_encode(['datos' => $est['datos']],
                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]
                );
            }
        }

        // Una estación libre de cada dos o tres ≈ 30%.
        $libres = max(1, (int) round($posicion * 0.34));

        if ($aplicar) {
            ejecutar(
                'UPDATE activities SET engine = ?, legacy_file = NULL, free_stations = ? WHERE id = ?',
                ['estaciones', $libres, $act['id']]
            );
            db()->commit();
        }

        $totalEstaciones += $posicion;
        $totalItems      += $items;
        $migradas++;

        printf("  %-30s → %-24s %d estaciones · %3d ejercicios\n",
               $archivo, $act['slug'], $posicion, $items);

    } catch (Throwable $e) {
        if ($aplicar) {
            db()->rollBack();
        }
        $avisos[] = "$archivo: " . $e->getMessage();
        printf("  %-30s ✗ %s\n", $archivo, $e->getMessage());
    }
}

echo "\n", str_repeat('-', 74), "\n";
printf("  Actividades migradas : %d de %d\n", $migradas, count($CONVERTIDORES));
printf("  Estaciones           : %d\n", $totalEstaciones);
printf("  Ejercicios           : %d\n", $totalItems);

echo "\n  No se migran a propósito:\n";
foreach (NO_MIGRABLES as $archivo => $motivo) {
    printf("    · %-28s %s\n", $archivo, $motivo);
}

if ($avisos) {
    echo "\n  Avisos:\n";
    foreach ($avisos as $a) {
        echo "    · $a\n";
    }
}

echo $aplicar
    ? "\n  Migración aplicada. Los archivos originales no se tocaron.\n"
    : "\n  Simulación. Para escribir: php database/migrar-extras.php --aplicar\n";

echo str_repeat('=', 74), "\n";
