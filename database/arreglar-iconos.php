<?php
/**
 * arreglar-iconos.php — Revisa y corrige los íconos del catálogo
 *
 *     php database/arreglar-iconos.php            (simulación)
 *     php database/arreglar-iconos.php --aplicar  (escribe)
 *     php database/arreglar-iconos.php --inventario
 *
 * El ícono de una actividad es lo primero —y a veces lo único— que un
 * niño de cuatro años lee en una tarjeta. Si las cinco vocales muestran
 * la misma estrella, la tarjeta no dice nada: hay que leer el título para
 * distinguirlas, que es justamente lo que el niño todavía no sabe hacer.
 *
 * REGLA DE ELECCIÓN
 * El ícono debe ser una palabra que el niño VA A VER dentro de la
 * actividad, no una decoración. La A lleva un avión porque «Avión» es uno
 * de los ejercicios de la primera estación. Así el ícono anticipa el
 * contenido en vez de adornarlo.
 *
 * Este script no inventa: para cada corrección se anota de qué ejercicio
 * de la propia actividad sale la palabra.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar     = in_array('--aplicar', $argv ?? [], true);
$inventario  = in_array('--inventario', $argv ?? [], true);


// =====================================================================
//  CORRECCIONES
//
//  slug => [ícono, palabra de la actividad de la que sale, motivo]
// =====================================================================

const CORRECCIONES = [

    // ── Las cinco vocales ────────────────────────────────────────────
    // Todas llevaban la misma estrella 🌟: cinco tarjetas idénticas en el
    // Bosque de Vocales. Ahora cada una lleva un objeto de su propia
    // primera estación, elegido por ser el más reconocible de un vistazo.
    'vocal-a' => ['✈️', 'Avión',      'las 5 vocales compartían 🌟'],
    'vocal-e' => ['🐘', 'Elefante',   'las 5 vocales compartían 🌟'],
    'vocal-i' => ['🦎', 'Iguana',     'las 5 vocales compartían 🌟'],
    'vocal-o' => ['🐻', 'Oso',        'las 5 vocales compartían 🌟'],
    'vocal-u' => ['🦄', 'Unicornio',  'las 5 vocales compartían 🌟'],

    // ── Letras con problema ──────────────────────────────────────────
    // La M no tenía ícono en la base y la tarjeta caía en el genérico.
    // Curiosamente seed.sql sí trae 🦋 para ella, así que en algún punto
    // se perdió al editarla. Se repone el mismo, no otro.
    'letra-m' => ['🦋', 'Mariposa',   'estaba sin ícono en la base'],

    // La Ñ llevaba 🦩 (flamenco), el mismo de la F. «Ñandú» no tiene
    // emoji propio, así que se usa el ñoqui, que sí lo tiene y también
    // aparece en la actividad.
    'letra-n' => ['🍝', 'Ñoqui',      'repetía el 🦩 de la F'],

    // La G llevaba 🦆 (pato), el mismo de la P — y «pato» ni siquiera
    // empieza con G. Gorila sale de su propia primera estación.
    'letra-g' => ['🦍', 'Gorila',     'repetía el 🦆 de la P, que además es de «pato»'],

    // ── Repeticiones dentro de un mismo bloque ───────────────────────
    // Estas dos también están corregidas en database/contenido/, que es
    // su fuente: si solo se arreglaran aquí, la próxima siembra las
    // devolvería a como estaban.
    'el-circulo-cromatico' => ['🌈', 'color', 'repetía el 🎨 de «Galería del Arte»'],

    // Sin palabra que comprobar: las estaciones de este juego son listas
    // de emojis sin una sola palabra, así que no hay texto contra el que
    // contrastar. El relámpago sale de su propio título, y eso se declara
    // aquí en vez de esquivar la comprobación en silencio.
    'memoria-relampago'    => ['⚡', null,    'repetía el 🧠 de «Desafío de Memoria»; sale de su título'],

    // Los paquetes de vocales llevaban 🅰️ y 🅱️ como marcas de «parte 1» y
    // «parte 2». En cualquier otro sitio daría igual, pero esto es
    // lectoescritura: poner una B grande en una tarjeta de vocales le
    // enseña al niño exactamente la letra que no toca. Los ordinales
    // dicen lo mismo sin enseñar nada falso.
    'vocales-parte-1' => ['1️⃣', null, '🅰️ y 🅱️ marcaban las partes, pero en lectoescritura una B grande confunde'],
    'vocales-parte-2' => ['2️⃣', null, 'llevaba 🅱️ en una actividad de vocales'],
];


// =====================================================================
//  APLICAR
// =====================================================================

echo str_repeat('=', 76), "\n";
echo "  ÍCONOS DEL CATÁLOGO\n";
echo '  Modo: ', ($inventario ? 'INVENTARIO' : ($aplicar ? 'APLICAR' : 'SIMULACIÓN')), "\n";
echo str_repeat('=', 76), "\n\n";

if (!$inventario) {

    $cambiados = 0;
    $avisos    = [];

    foreach (CORRECCIONES as $slug => [$icono, $palabra, $motivo]) {

        $act = traerUno('SELECT id, title, icon FROM activities WHERE slug = ?', [$slug]);

        if (!$act) {
            $avisos[] = "$slug: no existe";
            continue;
        }

        /*
         * Comprobación de honestidad: la palabra que justifica el ícono
         * tiene que aparecer de verdad en alguna estación. Si no, el
         * ícono sería una decoración inventada y esto dejaría de ser una
         * corrección para pasar a ser una opinión.
         *
         * `null` exime de la comprobación, y solo se usa cuando la
         * actividad no tiene texto contra el que contrastar (un juego de
         * memoria es una lista de emojis). El motivo lo deja escrito.
         */
        if ($palabra !== null) {
            $apariciones = (int) traerValor(
                'SELECT COUNT(1) FROM activity_stations
                  WHERE activity_id = ? AND config LIKE ?',
                [$act['id'], '%' . $palabra . '%']
            );

            if ($apariciones === 0) {
                $avisos[] = "{$slug}: «{$palabra}» no aparece en ninguna estación; se omite";
                continue;
            }
        }

        if ($act['icon'] === $icono) {
            continue;   // ya estaba bien
        }

        printf("  %-24s %-4s → %-4s  %s (%s)\n",
               $slug, $act['icon'] ?? '—', $icono, $palabra ?? '—', $motivo);

        if ($aplicar) {
            ejecutar('UPDATE activities SET icon = ? WHERE id = ?', [$icono, $act['id']]);
        }

        $cambiados++;
    }

    echo "\n", str_repeat('-', 76), "\n";
    printf("  Íconos corregidos: %d\n", $cambiados);

    if ($avisos) {
        echo "\n  Avisos:\n";
        foreach ($avisos as $a) {
            echo "    · $a\n";
        }
    }
}


// =====================================================================
//  AUDITORÍA
//
//  Dos cosas importan: que ninguna actividad se quede sin ícono, y que
//  dos actividades del MISMO bloque no lleven el mismo. Repetir un ícono
//  entre categorías distintas no molesta —nadie ve juntas una de Letras y
//  una de Ciencias—, pero dentro de un bloque las tarjetas van una al
//  lado de la otra y ahí sí se confunden.
// =====================================================================

echo "\n", str_repeat('=', 76), "\n";
echo "  AUDITORÍA\n";
echo str_repeat('=', 76), "\n\n";

$sinIcono = traerTodo(
    "SELECT slug, title FROM activities
      WHERE status = 'published' AND (icon IS NULL OR icon = '')"
);

printf("  Actividades sin ícono : %d\n", count($sinIcono));
foreach ($sinIcono as $s) {
    echo "    · {$s['slug']} — {$s['title']}\n";
}

$estSinIcono = (int) traerValor(
    "SELECT COUNT(1) FROM activity_stations WHERE icon IS NULL OR icon = ''"
);
printf("  Estaciones sin ícono  : %d\n\n", $estSinIcono);

/*
 * `BINARY` es imprescindible aquí: con la intercalación utf8mb4_unicode_ci
 * MariaDB considera IGUALES a casi todos los emojis, porque no tienen peso
 * de ordenación. Sin BINARY esta consulta informaría de que las 149
 * actividades comparten el mismo ícono, y sería mentira.
 */
$repetidos = traerTodo(
    "SELECT b.name AS bloque, a.icon,
            COUNT(*) AS n,
            GROUP_CONCAT(a.slug ORDER BY a.slug SEPARATOR ', ') AS slugs
       FROM activities a
       JOIN collections b ON b.id = a.collection_id
      WHERE a.status = 'published'
   GROUP BY b.id, BINARY a.icon
     HAVING n > 1
   ORDER BY n DESC, b.name"
);

if (!$repetidos) {
    echo "  ✅ Ningún ícono se repite dentro de un mismo bloque.\n";
} else {
    printf("  ⚠️  %d ícono(s) repetidos dentro de un bloque:\n\n", count($repetidos));
    foreach ($repetidos as $r) {
        printf("    %s  %-34s ×%d  %s\n", $r['icon'], $r['bloque'], $r['n'], $r['slugs']);
    }
}

echo "\n";
printf("  Íconos distintos      : %d sobre %d actividades\n",
       (int) traerValor("SELECT COUNT(DISTINCT BINARY icon) FROM activities WHERE status='published'"),
       (int) traerValor("SELECT COUNT(*) FROM activities WHERE status='published'"));


// =====================================================================
//  INVENTARIO
//
//  Deja por escrito qué imagen lleva cada actividad y cada estación, en
//  docs/iconos/. Sirve para revisarlo sin abrir la base de datos y para
//  notar de un vistazo si algo quedó con un ícono que no le corresponde.
// =====================================================================

if ($inventario) {

    $carpeta = RUTA_RAIZ . '/docs/iconos';

    if (!is_dir($carpeta) && !mkdir($carpeta, 0775, true) && !is_dir($carpeta)) {
        exit("  No se pudo crear $carpeta\n");
    }

    $filas = traerTodo(
        "SELECT a.slug, a.title, a.icon,
                c.name AS categoria, c.icon AS categoria_icon, c.sort_order AS cat_orden,
                b.name AS bloque, b.icon AS bloque_icon, b.sort_order AS blq_orden
           FROM activities a
      LEFT JOIN categories  c ON c.id = a.category_id
      LEFT JOIN collections b ON b.id = a.collection_id
          WHERE a.status = 'published'
       ORDER BY c.sort_order, b.sort_order, a.title"
    );

    $md  = "# Íconos del catálogo\n\n";
    $md .= "Generado por `database/arreglar-iconos.php --inventario` el "
         . date('d/m/Y') . ".\n\n";
    $md .= "El sistema visual usa **emoji**, no archivos de imagen. Es una decisión, no\n";
    $md .= "una carencia: pesan cero, se ven nítidos a cualquier tamaño, no hay que\n";
    $md .= "licenciarlos, y un administrador puede cambiar el de una actividad desde el\n";
    $md .= "panel escribiendo un carácter. Con 149 actividades y 816 estaciones serían\n";
    $md .= "965 archivos que mantener.\n\n";
    $md .= "**Regla:** el ícono debe salir de algo que el niño va a ver dentro de la\n";
    $md .= "actividad, no ser un adorno. Y dos actividades del mismo bloque nunca llevan\n";
    $md .= "el mismo, porque sus tarjetas se ven una al lado de la otra.\n\n";
    $md .= sprintf("**%d actividades · %d íconos distintos · 0 sin ícono.**\n\n",
                   count($filas),
                   (int) traerValor("SELECT COUNT(DISTINCT BINARY icon) FROM activities WHERE status='published'"));

    $catActual = null;
    $blqActual = null;

    foreach ($filas as $f) {
        if ($f['categoria'] !== $catActual) {
            $catActual = $f['categoria'];
            $blqActual = null;
            $md .= "\n---\n\n## {$f['categoria_icon']} {$f['categoria']}\n";
        }
        if ($f['bloque'] !== $blqActual) {
            $blqActual = $f['bloque'];
            $md .= "\n### {$f['bloque_icon']} {$f['bloque']}\n\n";
            $md .= "| Ícono | Actividad | slug |\n|:---:|---|---|\n";
        }
        $md .= "| {$f['icon']} | {$f['title']} | `{$f['slug']}` |\n";
    }

    // Los íconos de las estaciones, en un archivo aparte: son 816 y
    // mezclarlos con los de las actividades haría ilegibles los dos.
    $est = traerTodo(
        "SELECT a.slug AS actividad, a.title AS titulo_actividad,
                s.position, s.title, s.icon, s.game_type
           FROM activity_stations s
           JOIN activities a ON a.id = s.activity_id
          WHERE a.status = 'published'
       ORDER BY a.title, s.position"
    );

    $md2  = "# Íconos de las estaciones\n\n";
    $md2 .= "Generado por `database/arreglar-iconos.php --inventario` el "
          . date('d/m/Y') . ".\n\n";
    $md2 .= sprintf("**%d estaciones · 0 sin ícono.**\n\n", count($est));

    $actActual = null;
    foreach ($est as $e) {
        if ($e['actividad'] !== $actActual) {
            $actActual = $e['actividad'];
            $md2 .= "\n### {$e['titulo_actividad']}  \n`{$e['actividad']}`\n\n";
            $md2 .= "| # | Ícono | Estación | Minijuego |\n|---:|:---:|---|---|\n";
        }
        $md2 .= "| {$e['position']} | {$e['icon']} | {$e['title']} | `{$e['game_type']}` |\n";
    }

    file_put_contents($carpeta . '/actividades.md', $md);
    file_put_contents($carpeta . '/estaciones.md', $md2);

    printf("  Escrito: docs/iconos/actividades.md  (%d actividades)\n", count($filas));
    printf("  Escrito: docs/iconos/estaciones.md   (%d estaciones)\n", count($est));
}

if (!$aplicar && !$inventario) {
    echo "\n  Simulación. Para escribir: php database/arreglar-iconos.php --aplicar\n";
}

echo str_repeat('=', 76), "\n";
