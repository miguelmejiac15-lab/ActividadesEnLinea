<?php
/**
 * migrar-mundos.php — Migración de los mundos al motor por estaciones
 *
 *     php database/migrar-mundos.php            (simulación)
 *     php database/migrar-mundos.php --aplicar  (escribe)
 *
 * QUÉ SE DESCUBRIÓ AL ANALIZARLOS
 *
 * Los 28 mundos parecen muy distintos entre sí: uno suma frutas, otro
 * mezcla colores, otro arma circuitos. Pero al mirar sus datos, casi
 * todos comparten UNA MISMA MECÁNICA:
 *
 *     { ans: <respuesta correcta>, opts: [<opciones>], ...presentación }
 *
 * Lo que cambia es cómo se presenta la pregunta: unos traen un emoji
 * (`e`, `icon`), otros un color (`color`), otros una escena escrita
 * (`s`), otros una lista de compras (`items`). La mecánica —mostrar algo
 * y elegir la opción correcta— es la misma.
 *
 * Por eso no hacen falta veinte motores distintos: basta uno genérico de
 * opción múltiple, y este script normaliza cada forma a un mismo
 * contrato para que lo entienda.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/lector-js.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);
$soloEste = null;
foreach ($argv ?? [] as $a) {
    if (str_starts_with($a, '--solo=')) {
        $soloEste = substr($a, 7);
    }
}

$origen = 'D:/xampp/htdocs/ACTIVIDADES EN LINEA/sitio1-free';

if (!is_dir($origen)) {
    exit("No se encuentra la carpeta de origen:\n  $origen\n");
}


// =====================================================================
//  LECTURA DE LAS ESTACIONES DEL ARCHIVO
// =====================================================================

/**
 * Lee las estaciones declaradas en el HTML: número, ícono, título y
 * descripción. Los mundos las escriben todos con las mismas clases.
 */
function estacionesDelHtml(string $html): array
{
    // Cada estación es un bloque <div class="station ..."> ... </div>
    preg_match_all(
        '/<div class="station[^"]*"[^>]*>(.*?)<button/su',
        $html,
        $bloques
    );

    $estaciones = [];

    foreach ($bloques[1] as $b) {
        $icono  = preg_match('/class="station-icon">([^<]+)</u', $b, $m1) ? trim($m1[1]) : '';
        $titulo = preg_match('/class="station-title">([^<]+)</u', $b, $m2) ? trim($m2[1]) : '';
        $desc   = preg_match('/class="station-desc">([^<]+)</u',  $b, $m3) ? trim($m3[1]) : '';

        if ($titulo !== '') {
            $estaciones[] = ['icono' => $icono, 'titulo' => $titulo, 'descripcion' => $desc];
        }
    }

    return $estaciones;
}


// =====================================================================
//  NORMALIZACIÓN DE LOS DATOS
// =====================================================================

/**
 * Convierte un elemento de cualquier mundo al contrato común del motor
 * de opción múltiple.
 *
 * Contrato:
 *   enunciado   · el texto de la pregunta
 *   visual      · algo que acompaña (emoji, color, esquema…)
 *   tipoVisual  · emoji | color | dosColores | texto | lista | ninguno
 *   opciones    · lista de opciones a mostrar
 *   correcta    · índice de la opción correcta dentro de `opciones`
 *
 * Devuelve null si el elemento no encaja en esta mecánica; así el
 * script avisa en vez de guardar datos que el motor no sabría dibujar.
 */
function normalizar(array $it): ?array
{
    if (!isset($it['opts']) || !is_array($it['opts'])) {
        return null;
    }

    $opciones = array_values($it['opts']);

    // La respuesta correcta puede venir como valor (`ans`) o como
    // índice (`a`), según el mundo.
    if (array_key_exists('ans', $it)) {
        $correcta = array_search($it['ans'], $opciones, false);
        if ($correcta === false) {
            return null;   // la respuesta no está entre las opciones
        }
    } elseif (isset($it['a']) && is_int($it['a'])) {
        $correcta = $it['a'];
        if (!isset($opciones[$correcta])) {
            return null;
        }
    } else {
        return null;
    }

    // ── Presentación ────────────────────────────────────────────────
    $enunciado  = '';
    $visual     = null;
    $tipoVisual = 'ninguno';

    // Enunciado explícito
    foreach (['q', 's', 'word', 'circuit'] as $k) {
        if (!empty($it[$k]) && is_string($it[$k])) {
            $enunciado = $it[$k];
            break;
        }
    }

    // Acompañamiento visual
    if (!empty($it['color'])) {
        $visual     = $it['color'];
        $tipoVisual = 'color';

    } elseif (!empty($it['c1']) && !empty($it['c2'])) {
        // Mezcla de colores: dos muestras y el resultado esperado.
        $visual     = ['a' => $it['c1'], 'b' => $it['c2']];
        $tipoVisual = 'dosColores';
        if ($enunciado === '') {
            $enunciado = '¿Qué color sale de mezclar '
                . ($it['n1'] ?? '') . ' y ' . ($it['n2'] ?? '') . '?';
        }

    } elseif (!empty($it['items']) && is_array($it['items'])) {
        // Compra: lista de productos con precio.
        $visual     = $it['items'];
        $tipoVisual = 'lista';
        if ($enunciado === '') {
            $enunciado = '¿Cuánto cuesta todo junto?';
        }

    } elseif (!empty($it['icon'])) {
        $visual     = $it['icon'];
        $tipoVisual = 'emoji';

    } elseif (!empty($it['e'])) {
        $visual     = $it['e'];
        $tipoVisual = 'emoji';
    }

    // Si aún no hay enunciado, se construye con el nombre del elemento.
    if ($enunciado === '' && !empty($it['n'])) {
        $enunciado = '¿A qué grupo pertenece ' . $it['n'] . '?';
    }
    if ($enunciado === '' && !empty($it['circuit'])) {
        $enunciado = '¿Qué ocurre en este circuito?';
    }
    if ($enunciado === '') {
        $enunciado = 'Elige la respuesta correcta';
    }

    // Los mundos que traen `word` con una palabra suelta (no una
    // pregunta) la usan como enunciado del ejercicio.
    return [
        'enunciado'  => $enunciado,
        'visual'     => $visual,
        'tipoVisual' => $tipoVisual,
        'opciones'   => $opciones,
        'correcta'   => (int) $correcta,
    ];
}


/**
 * Normaliza una lista de opción múltiple.
 * @return array{items:array, descartados:int}
 */
function normalizarLista(array $lista): array
{
    $items = [];
    $descartados = 0;

    foreach ($lista as $it) {
        if (!is_array($it)) {
            $descartados++;
            continue;
        }
        $n = normalizar($it);
        if ($n === null) {
            $descartados++;
            continue;
        }
        $items[] = $n;
    }

    return ['items' => $items, 'descartados' => $descartados];
}


// =====================================================================
//  RECONOCIMIENTO DE MECÁNICAS
//
//  No todos los ejercicios son de opción múltiple. Al revisarlos
//  aparecieron otras mecánicas, cada una con una forma reconocible:
//
//      {a, b}                  suma            3 + 2
//      {total, remove}         resta           8 − 3
//      [a, b]                  multiplicación  4 × 5
//      {count}                 contar          ¿cuántos hay?
//      {seq, missing}          número que falta
//      {time, opts}            leer la hora
//      {syls, word}            puzle de sílabas
//      {word, emoji, hint}     escribir la palabra
//      {text, order}           ordenar los sucesos
//      {img, word}             emparejar imagen y palabra
//      "PALABRA"               escribir la palabra
//
//  Se reconocen por su ESTRUCTURA, no por el nombre del archivo ni de
//  la constante: así el reconocimiento sigue funcionando aunque otro
//  mundo use nombres distintos para los mismos datos.
// =====================================================================

/**
 * Decide qué minijuego corresponde a una lista de datos y la convierte.
 *
 * @return array{tipo:string, datos:array, descartados:int}|null
 */
function reconocerMecanica(array $lista): ?array
{
    $primero = null;
    foreach ($lista as $x) {
        $primero = $x;
        break;
    }

    if ($primero === null) {
        return null;
    }

    // ── Lista de palabras sueltas: escribir con el teclado ──────────
    if (is_string($primero)) {
        $palabras = array_values(array_filter($lista, 'is_string'));
        return $palabras
            ? ['tipo' => 'teclado', 'datos' => $palabras, 'descartados' => count($lista) - count($palabras)]
            : null;
    }

    // ── Multiplicación: pares [a, b] ────────────────────────────────
    if (is_array($primero) && !isset($primero['a']) && count($primero) === 2
        && isset($primero[0], $primero[1]) && is_numeric($primero[0]) && is_numeric($primero[1])) {

        $items = [];
        foreach ($lista as $p) {
            if (is_array($p) && isset($p[0], $p[1])) {
                $items[] = ['op' => 'multiplicacion', 'a' => (int) $p[0], 'b' => (int) $p[1],
                            'resultado' => (int) $p[0] * (int) $p[1]];
            }
        }
        return $items ? ['tipo' => 'operacion', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    if (!is_array($primero)) {
        return null;
    }

    // ── Suma: {a, b} sin opciones ───────────────────────────────────
    if (isset($primero['a'], $primero['b']) && !isset($primero['opts'])
        && is_numeric($primero['a']) && is_numeric($primero['b'])) {

        $items = [];
        foreach ($lista as $p) {
            if (isset($p['a'], $p['b'])) {
                $items[] = ['op' => 'suma', 'a' => (int) $p['a'], 'b' => (int) $p['b'],
                            'resultado' => (int) $p['a'] + (int) $p['b']];
            }
        }
        return $items ? ['tipo' => 'operacion', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Resta: {total, remove} ──────────────────────────────────────
    if (isset($primero['total'], $primero['remove'])) {
        $items = [];
        foreach ($lista as $p) {
            if (isset($p['total'], $p['remove'])) {
                $items[] = ['op' => 'resta', 'a' => (int) $p['total'], 'b' => (int) $p['remove'],
                            'resultado' => (int) $p['total'] - (int) $p['remove']];
            }
        }
        return $items ? ['tipo' => 'operacion', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Contar: {count} ─────────────────────────────────────────────
    if (isset($primero['count']) && count($primero) <= 2) {
        $items = [];
        foreach ($lista as $p) {
            if (isset($p['count'])) {
                $items[] = ['op' => 'contar', 'a' => (int) $p['count'], 'b' => null,
                            'resultado' => (int) $p['count']];
            }
        }
        return $items ? ['tipo' => 'operacion', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Número que falta: {seq, missing} ────────────────────────────
    if (isset($primero['seq'], $primero['missing'])) {
        $items = [];
        foreach ($lista as $p) {
            if (isset($p['seq'], $p['missing'])) {
                $items[] = ['secuencia' => $p['seq'], 'falta' => (int) $p['missing']];
            }
        }
        return $items ? ['tipo' => 'secuencia_numerica', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Leer la hora: {time, emoji, opts} ───────────────────────────
    // Ya trae opciones; solo falta señalar cuál es la correcta.
    if (isset($primero['time'], $primero['opts'])) {
        $items = [];
        foreach ($lista as $p) {
            if (!isset($p['time'], $p['opts'])) {
                continue;
            }
            $correcta = array_search($p['time'], $p['opts'], false);
            if ($correcta === false) {
                continue;
            }
            $items[] = [
                'enunciado'  => '¿Qué hora marca este reloj?',
                'visual'     => $p['emoji'] ?? '🕐',
                'tipoVisual' => 'emoji',
                'opciones'   => array_values($p['opts']),
                'correcta'   => (int) $correcta,
            ];
        }
        return $items ? ['tipo' => 'opcion_multiple', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Puzle de sílabas: {syls, word, meaning} ─────────────────────
    if (isset($primero['syls'], $primero['word'])) {
        $items = [];
        foreach ($lista as $p) {
            if (!isset($p['syls'], $p['word'])) {
                continue;
            }
            $items[] = [
                'w'    => mb_strtoupper((string) $p['word']),
                'e'    => $p['meaning'] ?? '🔤',
                'syls' => array_map(static fn($s) => mb_strtoupper((string) $s), $p['syls']),
            ];
        }
        return $items ? ['tipo' => 'puzle_silabas', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Escribir la palabra: {word, emoji, hint} ────────────────────
    if (isset($primero['word'], $primero['emoji'])) {
        $items = [];
        foreach ($lista as $p) {
            if (isset($p['word'])) {
                $items[] = mb_strtoupper((string) $p['word']);
            }
        }
        return $items ? ['tipo' => 'teclado', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Ordenar sucesos: {text, order} ──────────────────────────────
    if (isset($primero['text'], $primero['order'])) {
        $ordenados = $lista;
        usort($ordenados, static fn($x, $y) => ((int) ($x['order'] ?? 0)) <=> ((int) ($y['order'] ?? 0)));

        $items = [];
        foreach ($ordenados as $p) {
            if (isset($p['text'])) {
                $items[] = (string) $p['text'];
            }
        }
        return $items
            ? ['tipo' => 'ordenar_secuencia',
               'datos' => ['title' => 'Ordena lo que pasó en la historia', 'items' => $items],
               'descartados' => count($lista) - count($items)]
            : null;
    }

    // ── Emparejar imagen y palabra: {img, word} ─────────────────────
    if (isset($primero['img'], $primero['word'])) {
        $items = [];
        foreach ($lista as $p) {
            if (isset($p['img'], $p['word'])) {
                $items[] = ['e' => $p['img'], 'w' => mb_strtoupper((string) $p['word'])];
            }
        }
        return $items ? ['tipo' => 'emparejar', 'datos' => $items,
                         'descartados' => count($lista) - count($items)] : null;
    }

    // ── Opción múltiple, la mecánica más común ──────────────────────
    if (isset($primero['opts'])) {
        $r = normalizarLista($lista);
        return $r['items']
            ? ['tipo' => 'opcion_multiple', 'datos' => $r['items'], 'descartados' => $r['descartados']]
            : null;
    }

    return null;
}


// =====================================================================
//  QUÉ CONSTANTE ALIMENTA CADA ESTACIÓN, POR ARCHIVO
//
//  Los nombres de las constantes cambian de un mundo a otro
//  (g1Data, g1Items, g1Colors, g1Animals…). Este mapa dice, para cada
//  archivo, qué constante corresponde a la estación 1, a la 2 y a la 3.
// =====================================================================

const CONSTANTES = [
    'Camino_Paso_a_Paso.html'      => ['g1Items', 'g2Q'],
    'Canon_de_las_Restas.html'     => ['g1Data', 'g2Data', 'g3Problems'],
    'Carrera_de_Palabras.html'     => ['pairs', 'typeWords', 'g3Qs'],
    'Ciber_Codigo_Robot.html'      => ['levels', 'g2Questions', 'g3Questions'],
    'Ciudad_de_los_Cuentos.html'   => ['g1Qs', 'events', 'g3Qs'],
    'Detective_Digital.html'       => ['g1Items', 'g2Q'],
    'Estacion_Musical.html'        => ['g1Instruments', 'g2Notes', 'g3Families'],
    'Estadio_Multiplicacion.html'  => ['g1Data', 'g2Data', 'g3Data'],
    'Fabrica_de_Disfraces.html'    => ['g1Items', 'g2Q'],
    'Galeria_del_Arte.html'        => ['g1Colors', 'g2Mixes', 'g3Art'],
    'Isla_de_las_Figuras.html'     => ['g1Data', 'g2Data', 'g3Qs'],
    'La_Neo_Computadora.html'      => ['g1Parts', 'g2Words', 'g3Security'],
    'Laberinto_de_Logica.html'     => ['g1Patterns', 'g2Seqs', 'g3Problems'],
    'Laboratorio_de_Ciencias.html' => ['g1Animals', 'g2Objects', 'g3Senses'],
    'Mar_de_la_Ortografia.html'    => ['g1Qs', 'g2Qs', 'g3Qs'],
    'Mercado_de_Monedas.html'      => ['g1Questions', 'g2Purchases', 'g3Changes'],
    'Mina_de_los_Numeros.html'     => ['g1Data', 'g2Data', 'g3Data'],
    'Montaña_de_las_Silabas.html'  => ['syllablePairs', 'g2Questions', 'g3Questions'],
    'Mundo_de_Contrastes.html'     => ['g1Items', 'g2Q'],
    'Planeta_del_Espacio.html'     => ['g1Planets', 'g2Questions', 'g3Questions'],
    'Reloj_del_Tiempo.html'        => ['g1Clocks', 'g2Questions', 'g3Questions'],
    'Ritmo_de_Silabas.html'        => ['g1Items', 'g2Q'],
    'Sonidos_y_Senas.html'         => ['g1Items', 'g2Q'],
    'Taller_de_Escritura.html'     => ['spellWords', 'g3Qs'],
    'Taller_de_Manualidades.html'  => ['g1Items', 'g2Q'],
    'Teatro_de_los_Valores.html'   => ['g1Scenes', 'g2Scenes', 'g3Dilemmas'],
    'Torre_de_Circuitos.html'      => ['g1Items', 'g2Q'],
    'Valle_de_las_Sumas.html'      => ['g1Data', 'g2Data', 'g3Problems'],
];


// =====================================================================
//  MIGRACIÓN
// =====================================================================

echo str_repeat('=', 74), "\n";
echo "  MIGRACIÓN DE LOS MUNDOS AL MOTOR POR ESTACIONES\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 74), "\n\n";

$totalEstaciones = 0;
$totalItems      = 0;
$totalDescartes  = 0;
$migrados        = 0;
$avisos          = [];

// Se recorren las actividades pendientes que tengan mapa definido.
$pendientes = traerTodo(
    'SELECT id, slug, title, legacy_file
       FROM activities
      WHERE legacy_file IS NOT NULL
   ORDER BY title'
);

foreach ($pendientes as $act) {

    $archivo = $act['legacy_file'];

    if ($soloEste !== null && $archivo !== $soloEste) {
        continue;
    }
    if (!isset(CONSTANTES[$archivo])) {
        continue;   // no es un mundo de este tipo
    }

    $ruta = $origen . '/' . $archivo;

    if (!is_file($ruta)) {
        $avisos[] = "$archivo: archivo no encontrado";
        printf("  %-32s ✗ archivo no encontrado\n", $archivo);
        continue;
    }

    $html       = file_get_contents($ruta);
    $tarjetas   = estacionesDelHtml($html);
    $constantes = CONSTANTES[$archivo];

    $preparadas  = [];
    $descartados = 0;

    foreach ($constantes as $indice => $nombreConst) {

        $bruto = leerConstante($html, $nombreConst);

        if (!is_array($bruto) || !$bruto) {
            $avisos[] = "{$archivo}: no se pudo leer «{$nombreConst}»";
            continue;
        }

        $r = reconocerMecanica($bruto);

        if ($r === null) {
            $avisos[] = "$archivo · $nombreConst: mecánica no reconocida";
            continue;
        }

        $descartados += $r['descartados'];

        $tarjeta = $tarjetas[$indice] ?? null;

        $preparadas[] = [
            'titulo'      => $tarjeta['titulo'] ?? ('Estación ' . ($indice + 1)),
            'descripcion' => $tarjeta['descripcion'] ?? null,
            'icono'       => ($tarjeta['icono'] ?? '') !== '' ? $tarjeta['icono'] : '🎯',
            'tipo'        => $r['tipo'],
            'datos'       => $r['datos'],
        ];
    }

    if (!$preparadas) {
        $avisos[] = "$archivo: no se pudo preparar ninguna estación";
        printf("  %-32s ✗ sin estaciones utilizables\n", $archivo);
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

        foreach ($preparadas as $est) {
            $posicion++;

            // `ordenar_secuencia` guarda un objeto, no una lista.
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

        // Con 2 o 3 estaciones, liberar la primera es ≈33%: coherente
        // con el 30% del catálogo.
        if ($aplicar) {
            ejecutar(
                'UPDATE activities
                    SET engine = ?, legacy_file = NULL, free_stations = 1
                  WHERE id = ?',
                ['estaciones', $act['id']]
            );
            db()->commit();
        }

        $totalEstaciones += $posicion;
        $totalItems      += $items;
        $totalDescartes  += $descartados;
        $migrados++;

        printf(
            "  %-32s → %-24s %d estaciones · %3d ejercicios%s\n",
            $archivo, $act['slug'], $posicion, $items,
            $descartados > 0 ? "  ($descartados descartados)" : ''
        );

    } catch (Throwable $e) {
        if ($aplicar) {
            db()->rollBack();
        }
        $avisos[] = "$archivo: " . $e->getMessage();
        printf("  %-32s ✗ %s\n", $archivo, $e->getMessage());
    }
}

echo "\n", str_repeat('-', 74), "\n";
printf("  Mundos procesados : %d de %d\n", $migrados, count(CONSTANTES));
printf("  Estaciones        : %d\n", $totalEstaciones);
printf("  Ejercicios        : %d\n", $totalItems);

if ($totalDescartes > 0) {
    printf("  Descartados       : %d  (no encajaban en la mecánica de opción múltiple)\n", $totalDescartes);
}

if ($avisos) {
    echo "\n  Avisos:\n";
    foreach ($avisos as $a) {
        echo "    · $a\n";
    }
}

echo $aplicar
    ? "\n  Migración aplicada. Los archivos originales no se tocaron.\n"
    : "\n  Simulación. Para escribir: php database/migrar-mundos.php --aplicar\n";

echo str_repeat('=', 74), "\n";
