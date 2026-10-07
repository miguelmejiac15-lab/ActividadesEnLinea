<?php
/**
 * migracion-sombras-y-mitades.php — Crea «Cada uno con su sombra» y «Une las mitades»
 *
 *     php database/migracion-sombras-y-mitades.php             # simulacion
 *     php database/migracion-sombras-y-mitades.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE UNA MIGRACION Y NO EL SEMBRADOR
 * ---------------------------------------------------------------------
 *
 * El contenido vive en database/contenido/sombras-y-mitades.php, como el
 * resto. Pero `sembrar-contenido.php` recorre TODOS los archivos y
 * reemplaza las estaciones de cada actividad, lo que borra el progreso
 * guardado de los niños (ON DELETE CASCADE). En producción eso no se
 * hace para añadir dos actividades.
 *
 * Esta migración solo CREA: si la actividad ya existe —por su slug— no
 * la toca, ni a ella ni a ninguna otra. Repetirla no cambia nada. Los
 * datos de uso no se tocan; solo se insertan filas de catálogo.
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
echo "  MIGRACION - Sombras y mitades (preescolar)\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

mt_srand(20260827);   // igual que el sembrador

$contenido = require __DIR__ . '/contenido/sombras-y-mitades.php';

$categoriaId = (int) traerValor('SELECT id FROM categories WHERE slug = ?', [$contenido['categoria']['slug']]);
if (!$categoriaId) {
    exit("  [!!] No existe la categoría «{$contenido['categoria']['slug']}». Nada que hacer.\n");
}

$creadas = 0;

foreach ($contenido['actividades'] as $act) {

    if (traerValor('SELECT id FROM activities WHERE slug = ?', [$act['slug']])) {
        echo "  [ok] {$act['slug']} ya existe, no se toca.\n";
        continue;
    }

    $bloqueId = traerValor('SELECT id FROM collections WHERE slug = ?', [$act['bloque']]);
    $nivelId  = traerValor('SELECT id FROM levels WHERE slug = ?', [$act['nivel']]);
    $n        = count($act['estaciones']);
    $libres   = max(1, (int) round($n * 0.34));   // PROPORCION_LIBRE del sembrador

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
            [$act['slug'], $act['title'], $act['description'], $act['objective'],
             $categoriaId, $bloqueId ?: null, $nivelId ?: null, $act['icon'],
             $act['duracion'], $libres, 'juego']
        );

        $pos = 0;
        foreach ($act['estaciones'] as $e) {
            $pos++;
            insertar(
                'INSERT INTO activity_stations
                    (activity_id, position, title, description, icon, game_type, config, is_free)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                [$id, $pos, $e['titulo'], $e['descripcion'], $e['icono'], $e['tipo'],
                 json_encode(['datos' => ilustrar($e['datos'], (string) $e['tipo'])],
                             JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
            );
        }

        foreach ($act['tags'] ?? [] as $t) {
            $tagId = traerValor('SELECT id FROM tags WHERE slug = ?', [$t]);
            if ($tagId) {
                ejecutar('INSERT IGNORE INTO activity_tags (activity_id, tag_id) VALUES (?, ?)', [$id, $tagId]);
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
