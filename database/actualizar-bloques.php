<?php
/**
 * actualizar-bloques.php — Introduce los bloques dentro de las categorías
 *
 *     php database/actualizar-bloques.php            (simulación)
 *     php database/actualizar-bloques.php --aplicar  (escribe)
 *
 * POR QUÉ
 *
 * «Aventura de las Letras» tiene 39 actividades. Presentadas en una sola
 * lista, el visitante no distingue las vocales de las letras ni de los
 * mundos de lectura: solo ve una pared de tarjetas.
 *
 * Los bloques recuperan la organización que ya tenía el sitio anterior
 * con sus submundos (Bosque de Vocales, Reino de las Letras), pero ahora
 * como una estructura de datos y no como páginas sueltas.
 *
 * De paso resuelve una duplicación: «Bosque de las Vocales» y «Reino de
 * las Letras» nunca fueron actividades, eran páginas que listaban otras
 * actividades. Aquí pasan a ser lo que siempre fueron —bloques— y sus
 * fichas de actividad se archivan.
 *
 * Es idempotente: se puede ejecutar varias veces sin duplicar nada.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);


// =====================================================================
//  DEFINICIÓN DE LOS BLOQUES
//
//  Para cada bloque: a qué categoría pertenece, cómo se llama y qué
//  actividades agrupa (por slug).
// =====================================================================

$BLOQUES = [

    // ── Aventura de las Letras · 39 actividades ─────────────────────
    [
        'categoria'   => 'letras',
        'slug'        => 'bosque-de-vocales',
        'nombre'      => 'Bosque de Vocales',
        'descripcion' => 'Las cinco vocales, una a una, con sus juegos y sus cuentos.',
        'icono'       => '🌳',
        'orden'       => 1,
        'actividades' => [
            'vocal-a', 'vocal-e', 'vocal-i', 'vocal-o', 'vocal-u',
            'vocales-parte-1', 'vocales-parte-2',
            'juegos-de-vocales-1', 'juegos-de-vocales-2',
        ],
    ],
    [
        'categoria'   => 'letras',
        'slug'        => 'reino-de-las-letras',
        'nombre'      => 'Reino de las Letras',
        'descripcion' => 'Una aventura por cada letra del abecedario.',
        'icono'       => '🏰',
        'orden'       => 2,
        'actividades' => [
            'letra-m', 'letra-p', 'letra-s', 'letra-l', 'letra-t', 'letra-d',
            'letra-n', 'letra-b', 'letra-c', 'letra-f', 'letra-g', 'letra-h',
            'letra-j', 'letra-k', 'letra-r', 'letra-v', 'letra-w', 'letra-x',
            'letra-y', 'letra-z', 'letra-ch', 'letra-qu', 'letra-gue-gui',
        ],
    ],
    [
        'categoria'   => 'letras',
        'slug'        => 'mundos-de-lectura',
        'nombre'      => 'Mundos de Lectura y Escritura',
        'descripcion' => 'Sílabas, cuentos, ortografía y escritura en aventuras completas.',
        'icono'       => '📖',
        'orden'       => 3,
        'actividades' => [
            'montana-de-las-silabas', 'ciudad-de-los-cuentos', 'taller-de-escritura',
            'mar-de-la-ortografia', 'carrera-de-palabras',
        ],
    ],

    // ── Matemática · 12 actividades ─────────────────────────────────
    [
        'categoria'   => 'matematica',
        'slug'        => 'primeros-numeros',
        'nombre'      => 'Primeros Números',
        'descripcion' => 'El comienzo: contar, comparar y reconocer formas y tamaños.',
        'icono'       => '🌈',
        'orden'       => 1,
        'actividades' => [
            'formas-y-colores', 'tamanos-y-posiciones', 'cuento-hasta-10', 'familias-de-numeros',
        ],
    ],
    [
        'categoria'   => 'matematica',
        'slug'        => 'operaciones',
        'nombre'      => 'Operaciones',
        'descripcion' => 'Sumar, restar, multiplicar y manejar dinero.',
        'icono'       => '➕',
        'orden'       => 2,
        'actividades' => [
            'mina-de-los-numeros', 'valle-de-las-sumas', 'canon-de-las-restas',
            'estadio-multiplicacion', 'mercado-de-monedas',
        ],
    ],
    [
        'categoria'   => 'matematica',
        'slug'        => 'logica-y-medida',
        'nombre'      => 'Lógica y Medida',
        'descripcion' => 'Figuras, secuencias, tiempo y razonamiento.',
        'icono'       => '🧩',
        'orden'       => 3,
        'actividades' => [
            'isla-de-las-figuras', 'laberinto-de-logica', 'reloj-del-tiempo',
        ],
    ],
];

/**
 * Actividades que dejan de serlo porque en realidad eran páginas de
 * navegación: ahora existen como bloques.
 */
const A_ARCHIVAR = [
    'bosque-de-las-vocales' => 'ahora es el bloque «Bosque de Vocales»',
    'reino-de-las-letras'   => 'ahora es el bloque «Reino de las Letras»',
];


// =====================================================================
//  1. ESTRUCTURA
// =====================================================================

echo str_repeat('=', 70), "\n";
echo "  BLOQUES DENTRO DE LAS CATEGORÍAS\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 70), "\n\n";

$hayTabla = (int) traerValor(
    "SELECT COUNT(1) FROM information_schema.tables
      WHERE table_schema = DATABASE() AND table_name = 'collections'"
) > 0;

$hayColumna = (int) traerValor(
    "SELECT COUNT(1) FROM information_schema.columns
      WHERE table_schema = DATABASE()
        AND table_name = 'activities' AND column_name = 'collection_id'"
) > 0;

echo "1. Estructura\n";
echo '   tabla collections            : ', $hayTabla   ? 'ya existe' : 'hay que crearla', "\n";
echo '   columna activities.collection_id : ', $hayColumna ? 'ya existe' : 'hay que añadirla', "\n";

if ($aplicar) {

    if (!$hayTabla) {
        ejecutar(
            "CREATE TABLE `collections` (
                `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
                `category_id` INT UNSIGNED NULL,
                `slug`        VARCHAR(60)  NOT NULL,
                `name`        VARCHAR(120) NOT NULL,
                `description` VARCHAR(300) NULL,
                `icon`        VARCHAR(16)  NULL,
                `sort_order`  SMALLINT     NOT NULL DEFAULT 0,
                `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
                `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                PRIMARY KEY (`id`),
                UNIQUE KEY `uk_collections_slug` (`slug`),
                KEY `idx_collections_categoria` (`category_id`, `sort_order`),
                CONSTRAINT `fk_collections_category` FOREIGN KEY (`category_id`)
                    REFERENCES `categories` (`id`) ON DELETE CASCADE
             ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci"
        );
        echo "   → tabla collections creada\n";
    }

    if (!$hayColumna) {
        ejecutar(
            'ALTER TABLE `activities`
                ADD COLUMN `collection_id` INT UNSIGNED NULL
                    COMMENT "Bloque dentro de la categoría; opcional"
                    AFTER `category_id`,
                ADD KEY `idx_act_collection` (`collection_id`),
                ADD CONSTRAINT `fk_act_collection` FOREIGN KEY (`collection_id`)
                    REFERENCES `collections` (`id`) ON DELETE SET NULL'
        );
        echo "   → columna collection_id añadida\n";
    }
}


// =====================================================================
//  2. BLOQUES Y ASIGNACIONES
// =====================================================================

echo "\n2. Bloques\n";

$asignadas = 0;
$noEncontradas = [];

foreach ($BLOQUES as $b) {

    $categoriaId = traerValor('SELECT id FROM categories WHERE slug = ?', [$b['categoria']]);

    if (!$categoriaId) {
        echo "   ✗ no existe la categoría «{$b['categoria']}»\n";
        continue;
    }

    $existente = $hayTabla
        ? traerUno('SELECT id FROM collections WHERE slug = ?', [$b['slug']])
        : null;

    $bloqueId = $existente['id'] ?? null;

    if ($aplicar) {
        if ($bloqueId) {
            ejecutar(
                'UPDATE collections
                    SET category_id = ?, name = ?, description = ?, icon = ?, sort_order = ?, is_active = 1
                  WHERE id = ?',
                [$categoriaId, $b['nombre'], $b['descripcion'], $b['icono'], $b['orden'], $bloqueId]
            );
        } else {
            $bloqueId = insertar(
                'INSERT INTO collections (category_id, slug, name, description, icon, sort_order)
                 VALUES (?, ?, ?, ?, ?, ?)',
                [$categoriaId, $b['slug'], $b['nombre'], $b['descripcion'], $b['icono'], $b['orden']]
            );
        }
    }

    // ── Asignar las actividades al bloque ───────────────────────────
    $puestas = 0;

    foreach ($b['actividades'] as $slug) {
        $act = traerUno('SELECT id FROM activities WHERE slug = ?', [$slug]);

        if (!$act) {
            $noEncontradas[] = $slug;
            continue;
        }

        if ($aplicar && $bloqueId) {
            ejecutar('UPDATE activities SET collection_id = ? WHERE id = ?', [$bloqueId, $act['id']]);
        }

        $puestas++;
        $asignadas++;
    }

    printf("   %-2s %-34s %2d actividades\n", $b['icono'], $b['nombre'], $puestas);
}


// =====================================================================
//  3. ARCHIVAR LAS PÁGINAS DE NAVEGACIÓN
// =====================================================================

echo "\n3. Actividades que en realidad eran navegación\n";

foreach (A_ARCHIVAR as $slug => $motivo) {
    $act = traerUno('SELECT id, title, status FROM activities WHERE slug = ?', [$slug]);

    if (!$act) {
        printf("   · %-24s (no está en el catálogo)\n", $slug);
        continue;
    }

    if ($aplicar) {
        // Se archiva, no se borra: sigue en la base de datos y se puede
        // volver a publicar desde el panel con un clic.
        ejecutar("UPDATE activities SET status = 'archived', is_featured = 0 WHERE id = ?", [$act['id']]);
    }

    printf("   · %-24s archivada — %s\n", $act['title'], $motivo);
}


// =====================================================================
//  RESUMEN
// =====================================================================

echo "\n", str_repeat('-', 70), "\n";
printf("  Bloques definidos     : %d\n", count($BLOQUES));
printf("  Actividades agrupadas : %d\n", $asignadas);

if ($noEncontradas) {
    echo "\n  No se encontraron estos slugs:\n";
    foreach (array_unique($noEncontradas) as $s) {
        echo "    · $s\n";
    }
}

if ($aplicar) {
    $sueltas = traerTodo(
        'SELECT a.title, c.name AS categoria
           FROM activities a
      LEFT JOIN categories c ON c.id = a.category_id
          WHERE a.collection_id IS NULL AND a.status = "published"
       ORDER BY c.sort_order, a.title'
    );

    if ($sueltas) {
        echo "\n  Actividades sin bloque (" . count($sueltas) . "):\n";
        echo "  Es correcto: en categorías pequeñas un bloque no aporta nada.\n";
        foreach ($sueltas as $s) {
            printf("    · %-28s %s\n", $s['title'], $s['categoria'] ?? '—');
        }
    }
}

echo $aplicar
    ? "\n  Cambios aplicados.\n"
    : "\n  Simulación. Para escribir: php database/actualizar-bloques.php --aplicar\n";

echo str_repeat('=', 70), "\n";
