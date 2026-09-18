<?php
/**
 * migrar-paquetes-vocales.php — Los cinco paquetes por vocal
 *
 *     php database/migrar-paquetes-vocales.php            (simulación)
 *     php database/migrar-paquetes-vocales.php --aplicar  (escribe)
 *
 * `Paquete_Vocal_A.html` … `Paquete_Vocal_U.html` se quedaron fuera de la
 * migración. No es contenido menor: cada uno reúne seis u ocho minijuegos
 * distintos sobre la misma vocal —empieza-con, buscar imágenes, contar,
 * completar, sílabas, parejas, memoria y sopa— y se recorren de un tirón.
 * Es el formato «paquete» del sitio anterior, y era justamente lo que se
 * echaba de menos en el catálogo nuevo.
 *
 * Se los detectó con `auditar-migracion.php`, que compara los HTML del
 * sitio anterior contra los slugs que existen hoy.
 *
 * Ninguna actividad necesita motor nuevo: las ocho mecánicas ya existen.
 * Los archivos originales solo se leen; nunca se modifican.
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

mt_srand(20260827);

/**
 * Ícono de cada paquete.
 *
 * No puede repetir el de la vocal suelta —✈️ 🐘 🦎 🐻 🦄— porque las dos
 * tarjetas viven en el mismo bloque, «Bosque de Vocales», y se ven una al
 * lado de la otra. Cada uno sale de una palabra del propio paquete.
 */
const ICONOS = [
    'A' => ['🐝', 'ABEJA'],
    'E' => ['🦔', 'ERIZO'],
    'I' => ['🏝️', 'ISLA'],
    'O' => ['🐑', 'OVEJA'],   // no 🐙: un pulpo no es un oso ni una oveja
    'U' => ['🍇', 'UVA'],
];

/**
 * Comprueba que la palabra que justifica el ícono esté de verdad en el
 * paquete. Sin esto, el ícono sería una decoración elegida a ojo — que es
 * justo como se colaron los emojis que no representan su palabra.
 */
function iconoHonesto(string $html, string $palabra): bool
{
    return mb_strpos($html, $palabra) !== false;
}


// =====================================================================
//  CONVERSORES
//
//  Cada uno devuelve una estación lista, o null si el paquete no trae
//  esos datos (la A, por ejemplo, no tiene memoria ni sopa de letras).
// =====================================================================

/** ¿Empieza con la vocal? Sí o no, con la palabra leída en voz alta. */
function estEmpiezaCon(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A1_DATA');
    if (!is_array($datos)) {
        return null;
    }

    $items = [];
    foreach ($datos as $d) {
        if (!isset($d['word'], $d['emoji'])) {
            continue;
        }
        $items[] = ['e' => $d['emoji'], 'n' => $d['word'], 'ok' => !empty($d['yes'])];
    }

    return $items ? [
        'titulo' => "¿Empieza con $vocal?",
        'descripcion' => "Escucha cada palabra y decide si empieza con $vocal",
        'icono' => '🔊', 'tipo' => 'sonido_letra',
        'datos' => $items,
    ] : null;
}

/** Encontrar todas las imágenes que empiezan con la vocal. */
function estEncuentraImagenes(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A2_ITEMS');
    if (!is_array($datos)) {
        return null;
    }

    $items = [];
    foreach ($datos as $d) {
        if (!isset($d['word'], $d['emoji'])) {
            continue;
        }
        $items[] = ['e' => $d['emoji'], 'n' => $d['word'], 'ok' => !empty($d['ok'])];
    }

    return $items ? [
        'titulo' => '¡Encuentra las imágenes!',
        'descripcion' => "Toca todas las que empiezan con $vocal",
        'icono' => '🖼️', 'tipo' => 'seleccion_imagenes',
        // Encabezado propio: si no, el motor diría el texto genérico de
        // «Aventura de las Letras», que aquí no corresponde.
        'datos' => [
            't' => "Toca todas las que empiezan con $vocal",
            's' => 'Deja fuera las demás',
            'items' => $items,
        ],
    ] : null;
}

/** ¿Cuántas veces aparece la vocal en la palabra? */
function estCuantasTiene(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A3_DATA');
    if (!is_array($datos)) {
        return null;
    }

    $items = [];
    foreach ($datos as $d) {
        if (!isset($d['word'], $d['count'])) {
            continue;
        }

        $correcta = (int) $d['count'];

        // Cinco opciones alrededor de la correcta, como en el original.
        $opciones = [$correcta];
        for ($n = 1; $n <= 6 && count($opciones) < 5; $n++) {
            if (!in_array($n, $opciones, true)) {
                $opciones[] = $n;
            }
        }
        sort($opciones);

        $items[] = [
            'enunciado'  => "¿Cuántas $vocal tiene {$d['word']}?",
            'visual'     => $d['word'],
            'tipoVisual' => 'texto',
            'opciones'   => $opciones,
            'correcta'   => (int) array_search($correcta, $opciones, true),
        ];
    }

    return $items ? [
        'titulo' => "¿Cuántas $vocal tiene?",
        'descripcion' => 'Cuenta las veces que aparece la vocal',
        'icono' => '🔢', 'tipo' => 'opcion_multiple',
        'datos' => $items,
    ] : null;
}

/** Completar la palabra con la vocal que falta. */
function estCompletaPalabra(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A4_DATA');
    if (!is_array($datos)) {
        return null;
    }

    $items = [];
    foreach ($datos as $d) {
        if (!isset($d['display'], $d['answer'])) {
            continue;
        }
        $partes = explode('_', (string) $d['display'], 2);
        $items[] = [
            'before' => $partes[0] ?? '',
            'after'  => $partes[1] ?? '',
            'e'      => $d['emoji'] ?? '🔤',
            'a'      => (string) $d['answer'],
        ];
    }

    return $items ? [
        'titulo' => 'Completa la palabra',
        'descripcion' => 'Pon la vocal que falta',
        'icono' => '✏️', 'tipo' => 'completar_palabra',
        'datos' => $items,
    ] : null;
}

/** Ordenar las sílabas hasta formar la palabra. */
function estOrdenaSilabas(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A5_DATA');
    if (!is_array($datos)) {
        return null;
    }

    $items = [];
    foreach ($datos as $d) {
        if (!isset($d['word'], $d['sylls']) || count($d['sylls']) < 2) {
            continue;
        }
        $items[] = [
            'e'    => $d['emoji'] ?? '🔤',
            'syls' => array_values($d['sylls']),
            'w'    => $d['word'],
        ];
    }

    return $items ? [
        'titulo' => 'Ordena las sílabas',
        'descripcion' => 'Arma la palabra sílaba por sílaba',
        'icono' => '🔀', 'tipo' => 'puzle_silabas',
        'datos' => $items,
    ] : null;
}

/** Unir cada dibujo con su palabra. */
function estUneLaPalabra(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A6_PAIRS');
    if (!is_array($datos)) {
        return null;
    }

    $pares = [];
    $vistas = [];
    foreach ($datos as $d) {
        if (!isset($d['word'], $d['emoji']) || in_array($d['word'], $vistas, true)) {
            continue;
        }
        $pares[] = ['e' => $d['emoji'], 'w' => $d['word']];
        $vistas[] = $d['word'];
    }

    return count($pares) >= 2 ? [
        'titulo' => 'Une la palabra',
        'descripcion' => 'Empareja cada dibujo con su nombre',
        'icono' => '🔗', 'tipo' => 'emparejar',
        'datos' => $pares,
    ] : null;
}

/** Juego de memoria con los dibujos del paquete. */
function estMemoria(string $html, string $vocal): ?array
{
    $datos = leerConstante($html, 'A7_PAIRS');
    if (!is_array($datos)) {
        return null;
    }

    $emojis = [];
    foreach ($datos as $d) {
        // El motor de memoria empareja por el símbolo, así que un emoji
        // repetido haría ambigua la pareja.
        if (isset($d['emoji']) && !in_array($d['emoji'], $emojis, true)) {
            $emojis[] = $d['emoji'];
        }
    }

    return count($emojis) >= 2 ? [
        'titulo' => 'Juego de memoria',
        'descripcion' => 'Encuentra las parejas',
        'icono' => '🃏', 'tipo' => 'memoria',
        'datos' => $emojis,
    ] : null;
}

/** Sopa de letras: el original ya trae las casillas de cada palabra. */
function estSopa(string $html, string $vocal): ?array
{
    $grid    = leerConstante($html, 'WS_GRID');
    $palabras = leerConstante($html, 'WS_WORDS');

    if (!is_array($grid) || !is_array($palabras)) {
        return null;
    }

    $words = [];
    foreach ($palabras as $p) {
        if (!isset($p['word'], $p['cells'])) {
            continue;
        }
        $words[] = ['w' => $p['word'], 'cells' => $p['cells']];
    }

    return $words ? [
        'titulo' => 'Sopa de letras',
        'descripcion' => 'Encuentra las palabras escondidas',
        'icono' => '🔍', 'tipo' => 'sopa_letras',
        'datos' => ['grid' => $grid, 'words' => $words],
    ] : null;
}


// =====================================================================
//  MIGRACIÓN
// =====================================================================

echo str_repeat('=', 76), "\n";
echo "  PAQUETES POR VOCAL\n";
echo "  Cinco actividades que se habían quedado fuera de la migración\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 76), "\n\n";

// Dónde va cada paquete: junto a las vocales sueltas.
$categoriaId = (int) traerValor("SELECT id FROM categories  WHERE slug = 'letras'");
$bloqueId    = (int) traerValor("SELECT id FROM collections WHERE slug = 'bosque-de-vocales'");
$nivelId     = (int) traerValor("SELECT id FROM levels      WHERE slug = 'preescolar'");

if (!$categoriaId || !$bloqueId || !$nivelId) {
    exit("  No se encuentran la categoría, el bloque o el nivel de destino.\n");
}

$totalEstaciones = 0;
$totalEjercicios = 0;
$hechos = 0;
$avisos = [];

foreach (['A', 'E', 'I', 'O', 'U'] as $vocal) {

    $archivo = "Paquete_Vocal_$vocal.html";
    $ruta    = $origen . '/' . $archivo;

    if (!is_file($ruta)) {
        $avisos[] = "$archivo: no se encuentra";
        continue;
    }

    $html = file_get_contents($ruta);
    $slug = 'paquete-vocal-' . mb_strtolower($vocal);

    // Guarda contra colisiones: no se escribe sobre una actividad ajena.
    $previo = traerUno(
        'SELECT a.id, c.slug AS cat FROM activities a
      LEFT JOIN categories c ON c.id = a.category_id WHERE a.slug = ?',
        [$slug]
    );

    if ($previo && $previo['cat'] !== null && $previo['cat'] !== 'letras') {
        $avisos[] = "$slug: el slug ya lo usa una actividad de «{$previo['cat']}»; no se tocó";
        continue;
    }

    $estaciones = array_values(array_filter([
        estEmpiezaCon($html, $vocal),
        estEncuentraImagenes($html, $vocal),
        estCuantasTiene($html, $vocal),
        estCompletaPalabra($html, $vocal),
        estOrdenaSilabas($html, $vocal),
        estUneLaPalabra($html, $vocal),
        estMemoria($html, $vocal),
        estSopa($html, $vocal),
    ]));

    if (!$estaciones) {
        $avisos[] = "$archivo: no se pudo leer ninguna actividad";
        continue;
    }

    // Cuenta de ejercicios, solo para el informe.
    $ejercicios = 0;
    foreach ($estaciones as $e) {
        $d = $e['datos'];
        if (isset($d['items']))      { $ejercicios += count($d['items']); }
        elseif (isset($d['words']))  { $ejercicios += count($d['words']); }
        elseif (is_array($d))        { $ejercicios += count($d); }
    }

    [$icono, $palabraIcono] = ICONOS[$vocal];

    if (!iconoHonesto($html, $palabraIcono)) {
        $avisos[] = "$slug: «$palabraIcono» no aparece en el paquete; revisa el ícono $icono";
    }

    $numero = count($estaciones);
    $libres = max(1, (int) round($numero * 0.34));

    $descripcion = "Todo lo de la vocal $vocal en una sola aventura: $numero minijuegos "
                 . 'seguidos, del sonido inicial a la sopa de letras.';

    if ($aplicar) {
        db()->beginTransaction();

        try {
            if ($previo) {
                $actividadId = (int) $previo['id'];
                ejecutar(
                    'UPDATE activities
                        SET title = ?, description = ?, objective = ?,
                            category_id = ?, collection_id = ?, level_id = ?,
                            icon = ?, duration_minutes = ?, free_stations = ?,
                            activity_type = "paquete", engine = "estaciones",
                            access_type = "partial", status = "published",
                            legacy_file = NULL
                      WHERE id = ?',
                    [
                        "Paquete Vocal $vocal", $descripcion,
                        "Practicar a fondo la vocal $vocal con todas las mecánicas seguidas.",
                        $categoriaId, $bloqueId, $nivelId, $icono, 25, $libres, $actividadId,
                    ]
                );
            } else {
                $actividadId = (int) insertar(
                    'INSERT INTO activities
                        (slug, title, description, objective, category_id, collection_id,
                         level_id, icon, duration_minutes, free_stations, activity_type,
                         engine, access_type, status, published_at)
                     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "paquete",
                             "estaciones", "partial", "published", NOW())',
                    [
                        $slug, "Paquete Vocal $vocal", $descripcion,
                        "Practicar a fondo la vocal $vocal con todas las mecánicas seguidas.",
                        $categoriaId, $bloqueId, $nivelId, $icono, 25, $libres,
                    ]
                );
            }

            ejecutar('DELETE FROM activity_stations WHERE activity_id = ?', [$actividadId]);

            $posicion = 0;
            foreach ($estaciones as $e) {
                $posicion++;
                insertar(
                    'INSERT INTO activity_stations
                        (activity_id, position, title, description, icon, game_type, config, is_free)
                     VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                    [
                        $actividadId, $posicion, $e['titulo'], $e['descripcion'],
                        $e['icono'], $e['tipo'],
                        json_encode(['datos' => $e['datos']],
                                    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                    ]
                );
            }

            // Etiquetas, las mismas que llevan las vocales sueltas.
            ejecutar('DELETE FROM activity_tags WHERE activity_id = ?', [$actividadId]);
            foreach (['lectura', 'escritura', 'vocabulario', 'memoria'] as $t) {
                $tagId = traerValor('SELECT id FROM tags WHERE slug = ?', [$t]);
                if ($tagId) {
                    ejecutar('INSERT IGNORE INTO activity_tags (activity_id, tag_id) VALUES (?, ?)',
                             [$actividadId, $tagId]);
                }
            }

            db()->commit();

        } catch (Throwable $ex) {
            db()->rollBack();
            $avisos[] = "$slug: " . $ex->getMessage();
            printf("  %-22s ✗ %s\n", $archivo, $ex->getMessage());
            continue;
        }
    }

    $totalEstaciones += $numero;
    $totalEjercicios += $ejercicios;
    $hechos++;

    printf("  %-22s %s  %d estaciones · %3d ejercicios · %d libre(s)\n",
           $archivo, $icono, $numero, $ejercicios, $libres);

    foreach ($estaciones as $i => $e) {
        printf("      %d. %-24s %s\n", $i + 1, $e['titulo'], $e['tipo']);
    }
    echo "\n";
}

echo str_repeat('-', 76), "\n";
printf("  Paquetes migrados : %d de 5\n", $hechos);
printf("  Estaciones        : %d\n", $totalEstaciones);
printf("  Ejercicios        : %d\n", $totalEjercicios);

if ($avisos) {
    echo "\n  Avisos:\n";
    foreach ($avisos as $a) {
        echo "    · $a\n";
    }
}

echo $aplicar
    ? "\n  Aplicado. Conviene pasar validar-estaciones.php.\n"
    : "\n  Simulación. Para escribir: php database/migrar-paquetes-vocales.php --aplicar\n";

echo str_repeat('=', 76), "\n";
