<?php
/**
 * migrar-letras.php — Migración de las 23 letras al motor por estaciones
 *
 * Se ejecuta desde la línea de comandos:
 *     php database/migrar-letras.php            (simulación, no escribe)
 *     php database/migrar-letras.php --aplicar  (escribe en la base de datos)
 *
 * QUÉ HACE Y POR QUÉ
 *
 * En el proyecto anterior hay 23 archivos `Letra_*.html` de unos 77 KB
 * cada uno. Comparando `Letra_M` con `Letra_P` se comprueba que tienen
 * la MISMA estructura: los mismos 15 minijuegos, el mismo motor, el
 * mismo diseño. Lo único que cambia de verdad es un objeto JavaScript
 * llamado `V` con las palabras, imágenes y preguntas de esa letra.
 *
 * Este script extrae ese objeto de cada archivo y lo convierte en filas:
 * una actividad y sus 15 estaciones. El resultado es que ~1,7 MB de
 * código casi idéntico se sustituye por un motor y datos.
 *
 * El archivo original NO se toca: se lee y se deja como está.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/lector-js.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

// Carpeta del proyecto anterior, que es la fuente de los datos.
$origen = 'D:/xampp/htdocs/ACTIVIDADES EN LINEA/sitio1-free';

if (!is_dir($origen)) {
    exit("No se encuentra la carpeta de origen:\n  $origen\n");
}


// La lectura de objetos JavaScript vive en lector-js.php, compartida
// con los demás scripts de migración.


/**
 * Extrae el objeto `V` de un archivo de letra.
 */
function extraerDatos(string $rutaArchivo): array
{
    $html = file_get_contents($rutaArchivo);
    if ($html === false) {
        throw new RuntimeException('No se pudo leer ' . $rutaArchivo);
    }

    $pos = strpos($html, 'const V');
    if ($pos === false) {
        throw new RuntimeException('No se encontró el objeto V.');
    }

    $llave = strpos($html, '{', $pos);
    if ($llave === false) {
        throw new RuntimeException('El objeto V está mal formado.');
    }

    $lector = new LectorJs(substr($html, $llave));
    $datos  = $lector->leer();

    if (!is_array($datos) || !isset($datos['letra'])) {
        throw new RuntimeException('El objeto V no tiene la forma esperada.');
    }

    return $datos;
}


// =====================================================================
//  LAS 15 ESTACIONES DE UNA LETRA
//
//  Cada fila dice: de qué clave de `V` salen los datos, cómo se llama la
//  estación, qué ícono lleva y qué motor de minijuego la dibuja.
//  Este mapa es el contrato entre los datos antiguos y el motor nuevo.
// =====================================================================

const ESTACIONES_LETRA = [
    ['intro',  'Conoce la {L}',        '🎧', 'sonido_letra',       'Escucha y aprende el sonido de la letra'],
    ['sel',    'Seleccionar imágenes', '🔍', 'seleccion_imagenes', 'Toca todas las que empiezan con {L}'],
    ['sylls1', 'Puzle de sílabas I',   '🧩', 'puzle_silabas',      'Ordena las sílabas para formar palabras'],
    ['build1', 'Armar palabras I',     '✍️', 'armar_palabras',     'Construye la palabra letra por letra'],
    ['type1',  'Teclado I',            '⌨️', 'teclado',            'Escribe las palabras con el teclado'],
    ['spell',  'Ortografía',           '🎯', 'ortografia',         'Elige la escritura correcta'],
    ['seq',    'Ordena la historia',   '🖼️', 'ordenar_secuencia',  'Pon las escenas en el orden correcto'],
    ['rapid',  'Juego rápido',         '🎮', 'juego_rapido',       'Responde rápido: ¿lleva {L} o no?'],
    ['sylls2', 'Puzle de sílabas II',  '🔤', 'puzle_silabas',      'Palabras más largas, más sílabas'],
    ['build2', 'Armar palabras II',    '✏️', 'armar_palabras',     'Construye palabras nuevas'],
    ['type2',  'Teclado II',           '💻', 'teclado',            'Escribe palabras más difíciles'],
    ['mem',    'Memoria visual',       '🧠', 'memoria',            'Encuentra las parejas escondidas'],
    ['sopa',   'Sopa de letras',       '📝', 'sopa_letras',        'Encuentra las palabras escondidas'],
    ['story',  'Cuento de la {L}',     '📖', 'cuento',             'Lee la historia y responde'],
    ['final',  'Desafío final',        '🏆', 'desafio_final',      'Demuestra todo lo que aprendiste'],
];

// Archivo original  =>  slug de la actividad en la base de datos
const LETRAS = [
    'Letra_M.html' => 'letra-m',   'Letra_P.html' => 'letra-p',
    'Letra_S.html' => 'letra-s',   'Letra_L.html' => 'letra-l',
    'Letra_T.html' => 'letra-t',   'Letra_D.html' => 'letra-d',
    'Letra_Ñ.html' => 'letra-n',   'Letra_B.html' => 'letra-b',
    'Letra_C.html' => 'letra-c',   'Letra_F.html' => 'letra-f',
    'Letra_G.html' => 'letra-g',   'Letra_H.html' => 'letra-h',
    'Letra_J.html' => 'letra-j',   'Letra_K.html' => 'letra-k',
    'Letra_R.html' => 'letra-r',   'Letra_V.html' => 'letra-v',
    'Letra_W.html' => 'letra-w',   'Letra_X.html' => 'letra-x',
    'Letra_Y.html' => 'letra-y',   'Letra_Z.html' => 'letra-z',
    'Letra_CH.html' => 'letra-ch', 'Letra_QU.html' => 'letra-qu',
    'Letra_GUI.html' => 'letra-gue-gui',
];


// =====================================================================
//  MIGRACIÓN
// =====================================================================

echo str_repeat('=', 66), "\n";
echo "  MIGRACIÓN DE LAS LETRAS AL MOTOR POR ESTACIONES\n";
echo '  Modo: ', ($aplicar ? 'APLICAR (escribe en la base de datos)' : 'SIMULACIÓN (no escribe nada)'), "\n";
echo str_repeat('=', 66), "\n\n";

$totalEstaciones = 0;
$migradas = 0;
$fallos   = [];

foreach (LETRAS as $archivo => $slug) {

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

    try {
        $V = extraerDatos($ruta);
    } catch (Throwable $e) {
        $fallos[] = "$archivo: " . $e->getMessage();
        printf("  %-16s  ✗ %s\n", $archivo, $e->getMessage());
        continue;
    }

    $letra = (string) $V['letra'];
    $creadas = 0;
    $vacias  = [];

    if ($aplicar) {
        db()->beginTransaction();
    }

    try {
        // Se borran las estaciones anteriores para que volver a ejecutar
        // el script no duplique nada.
        if ($aplicar) {
            ejecutar('DELETE FROM activity_stations WHERE activity_id = ?', [$actividad['id']]);
        }

        $posicion = 0;

        foreach (ESTACIONES_LETRA as [$clave, $titulo, $icono, $tipo, $descripcion]) {

            $posicion++;

            if (!isset($V[$clave])) {
                $vacias[] = $clave;
                continue;
            }

            $titulo      = str_replace('{L}', $letra, $titulo);
            $descripcion = str_replace('{L}', $letra, $descripcion);

            // El contenido del minijuego. Esto es lo que el servidor
            // retiene cuando la estación está bloqueada.
            $config = ['datos' => $V[$clave]];

            // El sonido de la letra lo necesita la estación de intro.
            if ($clave === 'intro' && isset($V['sonido'])) {
                $config['sonido'] = $V['sonido'];
            }

            if ($aplicar) {
                insertar(
                    'INSERT INTO activity_stations
                        (activity_id, position, title, description, icon, game_type, config, is_free)
                     VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                    [
                        $actividad['id'], $posicion, $titulo, $descripcion,
                        $icono, $tipo,
                        json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]
                );
            }

            $creadas++;
        }

        // Los datos comunes a toda la actividad (la letra, su sonido y
        // los juegos extra) van en `content`.
        if ($aplicar) {
            $contenido = [
                'letra'    => $letra,
                'clave'    => $V['key'] ?? mb_strtolower($letra),
                'sonido'   => $V['sonido']   ?? null,
                'ahorcado' => $V['ahorcado'] ?? null,
                'lluvia'   => $V['lluvia']   ?? null,
            ];

            ejecutar(
                'UPDATE activities
                    SET engine = ?, content = ?, legacy_file = NULL
                  WHERE id = ?',
                [
                    'estaciones',
                    json_encode($contenido, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
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
        "  %-16s  → %-14s  %2d estaciones%s\n",
        $archivo,
        $slug,
        $creadas,
        $vacias ? '  (sin datos: ' . implode(', ', $vacias) . ')' : ''
    );
}

echo "\n", str_repeat('-', 66), "\n";
printf("  Letras procesadas : %d de %d\n", $migradas, count(LETRAS));
printf("  Estaciones        : %d\n", $totalEstaciones);

if ($fallos) {
    echo "\n  Problemas:\n";
    foreach ($fallos as $f) {
        echo "    · $f\n";
    }
}

if (!$aplicar) {
    echo "\n  Esto fue una simulación. Para escribir de verdad:\n";
    echo "      php database/migrar-letras.php --aplicar\n";
} else {
    echo "\n  Migración aplicada. Los archivos HTML originales no se tocaron.\n";
}

echo str_repeat('=', 66), "\n";
