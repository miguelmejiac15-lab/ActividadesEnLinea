<?php
/**
 * auditar-migracion.php — Qué quedó fuera del sitio anterior
 *
 *     php database/auditar-migracion.php
 *
 * Los migradores ponen `legacy_file = NULL` al terminar, así que la base
 * de datos ya no recuerda de qué archivo vino cada actividad. Esto hace
 * que sea imposible saber, mirando solo la base, si algo se quedó atrás.
 *
 * Este script recorre los HTML del sitio anterior, deduce el slug que le
 * correspondería a cada uno y comprueba si existe. Sirve para responder
 * una pregunta concreta: ¿migramos todo?
 *
 * No modifica nada. Solo lee.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$origen = 'D:/xampp/htdocs/ACTIVIDADES EN LINEA/sitio1-free';

if (!is_dir($origen)) {
    exit("No se encuentra la carpeta de origen:\n  $origen\n");
}

/**
 * Páginas del sitio anterior que NO son actividades.
 * Se listan a propósito para que no aparezcan como «faltantes».
 */
const NO_SON_ACTIVIDADES = [
    'index.html'        => 'portada',
    'mundos.html'       => 'índice de mundos',
    'premium.html'      => 'página comercial',
    'Registro.html'     => 'formulario de registro',
    'admin_login.html'  => 'acceso al panel',
    'panel_admin.html'  => 'panel de administración',
    'panel_parent.html' => 'panel de familias',
    'panel_teacher.html'=> 'panel de docentes',
];

/** Archivo del sitio anterior → slug con el que se migró. */
const EQUIVALENCIAS = [
    'Letra_GUI.html'              => 'letra-gue-gui',
    'Letra_Ñ.html'                => 'letra-n',
    'Paquete_Vocales_Parte1.html' => 'vocales-parte-1',
    'Paquete_Vocales_Parte2.html' => 'vocales-parte-2',
    'Pre_Contando.html'           => 'cuento-hasta-10',
    'Pre_Familias_Numeros.html'   => 'familias-de-numeros',
    'Pre_Formas_Colores.html'     => 'formas-y-colores',
    'Pre_Tamanos_Posiciones.html' => 'tamanos-y-posiciones',
    'Montaña_de_las_Silabas.html' => 'montana-de-las-silabas',

    // Estos tres se llamaron distinto al migrarlos. Sin la equivalencia,
    // la auditoría los daría por perdidos en cada revisión y el aviso
    // dejaría de significar nada.
    'Bosque_de_vocales.html'      => 'bosque-de-las-vocales',
    'Juegos_Vocales_1.html'       => 'juegos-de-vocales-1',
    'Juegos_Vocales_2.html'       => 'juegos-de-vocales-2',
];

/** Deduce el slug a partir del nombre del archivo. */
function slugDeArchivo(string $archivo): string
{
    if (isset(EQUIVALENCIAS[$archivo])) {
        return EQUIVALENCIAS[$archivo];
    }

    $base = preg_replace('/\.html$/i', '', $archivo);
    $base = str_replace('_', '-', $base);

    // Sin tildes ni ñ, en minúsculas: el mismo criterio de los slugs.
    $base = strtr($base, [
        'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ñ'=>'n',
        'Á'=>'A','É'=>'E','Í'=>'I','Ó'=>'O','Ú'=>'U','Ñ'=>'N',
    ]);

    return mb_strtolower($base);
}


// =====================================================================

echo str_repeat('=', 76), "\n";
echo "  AUDITORÍA DE LA MIGRACIÓN\n";
echo "  ¿Quedó algo del sitio anterior sin traer?\n";
echo str_repeat('=', 76), "\n\n";

$archivos = array_values(array_filter(
    scandir($origen),
    static fn($f) => preg_match('/\.html$/i', $f) === 1
));

$migradas = [];
$faltan   = [];
$paginas  = [];

foreach ($archivos as $archivo) {

    if (isset(NO_SON_ACTIVIDADES[$archivo])) {
        $paginas[$archivo] = NO_SON_ACTIVIDADES[$archivo];
        continue;
    }

    $slug = slugDeArchivo($archivo);

    $act = traerUno(
        'SELECT a.slug, a.title, a.status,
                (SELECT COUNT(1) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones
           FROM activities a WHERE a.slug = ?',
        [$slug]
    );

    if ($act) {
        $migradas[$archivo] = $act;
    } else {
        $faltan[$archivo] = $slug;
    }
}

printf("  Archivos revisados      : %d\n", count($archivos));
printf("  Páginas (no actividades): %d\n", count($paginas));
printf("  Migradas y presentes    : %d\n", count($migradas));
printf("  SIN MIGRAR              : %d\n\n", count($faltan));

if ($faltan) {
    echo "  ── Sin migrar ────────────────────────────────────────────────\n";
    foreach ($faltan as $archivo => $slug) {
        $peso = round(filesize($origen . '/' . $archivo) / 1024);
        printf("    %-32s %4d KB   (slug esperado: %s)\n", $archivo, $peso, $slug);
    }
    echo "\n";
}

// Las migradas que se quedaron sin estaciones son otro tipo de problema:
// existen en el catálogo pero no hay nada que jugar.
$vacias = array_filter($migradas, static fn($a) => (int) $a['estaciones'] === 0);

if ($vacias) {
    echo "  ── Migradas pero SIN estaciones ──────────────────────────────\n";
    foreach ($vacias as $archivo => $a) {
        printf("    %-32s → %s (%s)\n", $archivo, $a['slug'], $a['status']);
    }
    echo "\n";
}

$archivadas = array_filter($migradas, static fn($a) => $a['status'] === 'archived');

if ($archivadas) {
    echo "  ── Migradas pero archivadas ──────────────────────────────────\n";
    foreach ($archivadas as $archivo => $a) {
        printf("    %-32s → %s\n", $archivo, $a['slug']);
    }
    echo "\n";
}

echo str_repeat('=', 76), "\n";
