<?php
/**
 * migracion-aleman.php — Alemán en Idiomas (fase 1: Kindergarten y Klasse 1–2)
 *
 *     php database/migracion-aleman.php             # simulacion
 *     php database/migracion-aleman.php --aplicar   # escribe
 *
 * El contenido vive en database/contenido/idiomas-aleman.php. Como con
 * sombras y mitades, en producción no se usa el sembrador —reemplaza las
 * estaciones de todas las actividades y borraría el progreso—: esta
 * migración solo CREA lo que falta y no toca nada que ya exista.
 *
 *   - la etiqueta «Alemán»
 *   - los bloques Deutsch · Kindergarten y Deutsch · Erste Wörter
 *   - cada actividad cuyo slug todavía no existe, con sus estaciones
 *   - el lema de Idiomas, solo si sigue siendo el original (el que
 *     nombraba únicamente el inglés): si alguien lo cambió, se respeta
 *
 * Repetirla no cambia nada. Solo tablas de catálogo.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/ayudas.php';
require_once __DIR__ . '/contenido/dibujos.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Aleman (fase 1)\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

mt_srand(20260827);   // igual que el sembrador

$contenido = require __DIR__ . '/contenido/idiomas-aleman.php';
$cat       = $contenido['categoria'];

$categoria = traerUno('SELECT id, tagline FROM categories WHERE slug = ?', [$cat['slug']]);
if (!$categoria) {
    exit("  [!!] No existe la categoría «{$cat['slug']}». Nada que hacer.\n");
}
$categoriaId = (int) $categoria['id'];

// ── Lema de Idiomas ──────────────────────────────────────────────────
if ($categoria['tagline'] === 'Primeras palabras y frases en inglés, jugando') {
    echo "  [->] Lema de Idiomas: «{$cat['tagline']}»\n";
    if ($aplicar) {
        ejecutar('UPDATE categories SET tagline = ? WHERE id = ?', [$cat['tagline'], $categoriaId]);
    }
} else {
    echo "  [ok] Lema de Idiomas: se deja como está.\n";
}

// ── Etiqueta ─────────────────────────────────────────────────────────
$tagId = traerValor('SELECT id FROM tags WHERE slug = "aleman"');
if ($tagId) {
    echo "  [ok] Etiqueta «aleman» ya existe.\n";
} else {
    echo "  [->] Se crea la etiqueta «Alemán»\n";
    if ($aplicar) {
        $orden = (int) traerValor('SELECT sort_order FROM tags WHERE slug = "ingles"');
        insertar('INSERT INTO tags (slug, name, kind, icon, sort_order) VALUES (?, ?, ?, ?, ?)',
                 ['aleman', 'Alemán', 'tema', '🇩🇪', $orden + 1]);
    }
}

// ── Bloques ──────────────────────────────────────────────────────────
foreach ($contenido['bloques'] as $b) {
    if (traerValor('SELECT id FROM collections WHERE slug = ?', [$b['slug']])) {
        echo "  [ok] Bloque {$b['slug']} ya existe.\n";
        continue;
    }
    echo "  [->] Se crea el bloque «{$b['name']}»\n";
    if ($aplicar) {
        insertar('INSERT INTO collections (category_id, slug, name, description, icon, sort_order, is_active)
                  VALUES (?, ?, ?, ?, ?, ?, 1)',
                 [$categoriaId, $b['slug'], $b['name'], $b['description'], $b['icon'], $b['sort_order']]);
    }
}

// ── Actividades ──────────────────────────────────────────────────────
$creadas = 0;

foreach ($contenido['actividades'] as $act) {

    if (traerValor('SELECT id FROM activities WHERE slug = ?', [$act['slug']])) {
        echo "  [ok] {$act['slug']} ya existe, no se toca.\n";
        continue;
    }

    $n      = count($act['estaciones']);
    $libres = max(1, (int) round($n * 0.34));   // PROPORCION_LIBRE del sembrador
    echo "  [->] {$act['slug']}: $n estaciones, $libres libre(s)\n";

    if (!$aplicar) {
        continue;
    }

    db()->beginTransaction();
    try {
        $id = (int) insertar(
            'INSERT INTO activities
                (slug, title, description, objective, category_id, collection_id,
                 level_id, icon, duration_minutes, free_stations, activity_type,
                 engine, access_type, status, published_at)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, "estaciones", "partial", "published", NOW())',
            [$act['slug'], $act['title'], $act['description'], $act['objective'], $categoriaId,
             traerValor('SELECT id FROM collections WHERE slug = ?', [$act['bloque']]) ?: null,
             traerValor('SELECT id FROM levels WHERE slug = ?', [$act['nivel']]) ?: null,
             $act['icon'], $act['duracion'], $libres,
             in_array('reto', $act['tags'] ?? [], true) ? 'reto' : 'juego']
        );

        $pos = 0;
        foreach ($act['estaciones'] as $e) {
            $pos++;
            $config = ['datos' => ilustrar($e['datos'], (string) $e['tipo'])];
            if (!empty($e['idioma'])) {
                $config['idioma'] = $e['idioma'];
            }
            insertar(
                'INSERT INTO activity_stations
                    (activity_id, position, title, description, icon, game_type, config, is_free)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                [$id, $pos, $e['titulo'], $e['descripcion'], $e['icono'], $e['tipo'],
                 json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
            );
        }

        foreach ($act['tags'] ?? [] as $t) {
            $tid = traerValor('SELECT id FROM tags WHERE slug = ?', [$t]);
            if ($tid) {
                ejecutar('INSERT IGNORE INTO activity_tags (activity_id, tag_id) VALUES (?, ?)', [$id, $tid]);
            }
        }

        db()->commit();
        $creadas++;
    } catch (Throwable $ex) {
        db()->rollBack();
        echo "  [!!] {$act['slug']}: " . $ex->getMessage() . "\n";
        exit(1);
    }
}

echo "\n" . ($aplicar ? "$creadas actividad(es) creada(s).\n" : "Simulacion. Nada se escribio. Repite con --aplicar\n");
