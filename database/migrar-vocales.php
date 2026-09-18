<?php
/**
 * migrar-vocales.php — Migración de las 5 vocales al motor por estaciones
 *
 *     php database/migrar-vocales.php            (simulación)
 *     php database/migrar-vocales.php --aplicar  (escribe)
 *
 * Las vocales también guardan sus datos en un objeto `V`, pero con
 * claves genéricas: `a1`, `a2`, … `a11` en lugar de nombres
 * descriptivos como `sylls1` o `story`. Por eso necesitan su propio
 * mapa: el índice no dice qué minijuego es.
 *
 * Los títulos, íconos y descripciones NO se inventan aquí: se leen del
 * propio archivo HTML, donde ya están escritos.
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


/**
 * Qué minijuego dibuja cada clave del objeto `V`.
 *
 * Este mapa se dedujo comparando los datos de cada clave con las
 * tarjetas del archivo: por ejemplo, `a3` trae {e, correct, opts}, que
 * es exactamente lo que necesita el minijuego de ortografía.
 */
const MAPA_VOCAL = [
    'a1'  => 'sonido_letra',
    'a2'  => 'seleccion_imagenes',
    'a3'  => 'ortografia',
    'a4'  => 'armar_palabras',
    'a5'  => 'juego_rapido',
    'a6'  => 'cuento',
    'a7'  => 'pronunciacion',
    'a8'  => 'completar_palabra',
    'a9'  => 'memoria',
    'a10' => 'sopa_letras',
    'a11' => 'desafio_final',
];

const VOCALES = [
    'Vocal_A.html' => 'vocal-a',
    'Vocal_E.html' => 'vocal-e',
    'Vocal_I.html' => 'vocal-i',
    'Vocal_O.html' => 'vocal-o',
    'Vocal_U.html' => 'vocal-u',
];


/**
 * Lee los títulos, íconos y descripciones de las tarjetas del archivo.
 * Así los nombres que ve el usuario son los que ya conocía.
 */
function tarjetasDelArchivo(string $html): array
{
    preg_match_all('/class="game-icon-square">([^<]+)</u', $html, $iconos);
    preg_match_all('/class="game-title">([^<]+)</u',        $html, $titulos);
    preg_match_all('/class="game-desc">([^<]+)</u',         $html, $descripciones);

    $tarjetas = [];
    foreach ($titulos[1] as $i => $t) {
        $tarjetas[] = [
            'icono'       => trim($iconos[1][$i] ?? ''),
            'titulo'      => trim($t),
            'descripcion' => trim($descripciones[1][$i] ?? ''),
        ];
    }

    return $tarjetas;
}


echo str_repeat('=', 66), "\n";
echo "  MIGRACIÓN DE LAS VOCALES AL MOTOR POR ESTACIONES\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 66), "\n\n";

$totalEstaciones = 0;
$migradas = 0;
$fallos   = [];

foreach (VOCALES as $archivo => $slug) {

    $ruta = $origen . '/' . $archivo;

    if (!is_file($ruta)) {
        $fallos[] = "$archivo: no existe";
        printf("  %-16s  ✗ archivo no encontrado\n", $archivo);
        continue;
    }

    $actividad = traerUno('SELECT id, title FROM activities WHERE slug = ?', [$slug]);
    if (!$actividad) {
        $fallos[] = "$slug: no está en la base de datos";
        printf("  %-16s  ✗ la actividad «%s» no existe\n", $archivo, $slug);
        continue;
    }

    $html = file_get_contents($ruta);
    $V    = leerConstante($html, 'V');

    if (!is_array($V) || !isset($V['letra'])) {
        $fallos[] = "$archivo: no se pudo leer el objeto V";
        printf("  %-16s  ✗ no se pudo leer el objeto V\n", $archivo);
        continue;
    }

    $letra    = (string) $V['letra'];
    $tarjetas = tarjetasDelArchivo($html);
    $creadas  = 0;
    $sinDatos = [];

    if ($aplicar) {
        db()->beginTransaction();
    }

    try {
        if ($aplicar) {
            ejecutar('DELETE FROM activity_stations WHERE activity_id = ?', [$actividad['id']]);
        }

        $posicion = 0;

        foreach (MAPA_VOCAL as $clave => $tipo) {

            $indice = $posicion;   // las tarjetas van en el mismo orden
            $posicion++;

            if (!isset($V[$clave])) {
                $sinDatos[] = $clave;
                continue;
            }

            $tarjeta = $tarjetas[$indice] ?? null;

            $titulo = $tarjeta['titulo'] ?? ucfirst(str_replace('_', ' ', $tipo));
            $icono  = $tarjeta['icono']  ?? '🎯';
            $desc   = $tarjeta['descripcion'] ?? null;

            // El ícono de la última tarjeta viene vacío en los archivos
            // originales; se le pone uno coherente en vez de dejarlo así.
            if ($icono === '') {
                $icono = '🏅';
            }

            $config = ['datos' => $V[$clave]];

            if ($aplicar) {
                insertar(
                    'INSERT INTO activity_stations
                        (activity_id, position, title, description, icon, game_type, config, is_free)
                     VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                    [
                        $actividad['id'], $posicion, $titulo, $desc, $icono, $tipo,
                        json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]
                );
            }

            $creadas++;
        }

        if ($aplicar) {
            ejecutar(
                'UPDATE activities
                    SET engine = ?, content = ?, legacy_file = NULL, free_stations = ?
                  WHERE id = ?',
                [
                    'estaciones',
                    json_encode(['letra' => $letra, 'clave' => mb_strtolower($letra)],
                                JSON_UNESCAPED_UNICODE),
                    // ≈30% de 11 estaciones
                    3,
                    $actividad['id'],
                ]
            );

            db()->commit();
        }

    } catch (Throwable $e) {
        if ($aplicar) {
            db()->rollBack();
        }
        $fallos[] = "$slug: " . $e->getMessage();
        printf("  %-16s  ✗ %s\n", $archivo, $e->getMessage());
        continue;
    }

    $totalEstaciones += $creadas;
    $migradas++;

    printf(
        "  %-16s  → %-10s  %2d estaciones%s\n",
        $archivo, $slug, $creadas,
        $sinDatos ? '  (sin datos: ' . implode(', ', $sinDatos) . ')' : ''
    );
}

echo "\n", str_repeat('-', 66), "\n";
printf("  Vocales procesadas : %d de %d\n", $migradas, count(VOCALES));
printf("  Estaciones         : %d\n", $totalEstaciones);

if ($fallos) {
    echo "\n  Problemas:\n";
    foreach ($fallos as $f) {
        echo "    · $f\n";
    }
}

echo $aplicar
    ? "\n  Migración aplicada. Los archivos originales no se tocaron.\n"
    : "\n  Simulación. Para escribir: php database/migrar-vocales.php --aplicar\n";

echo str_repeat('=', 66), "\n";
