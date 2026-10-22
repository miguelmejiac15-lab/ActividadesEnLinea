<?php
/**
 * migracion-tienda-ampliada.php — Más accesorios y beneficios en la tienda
 *
 *     php database/migracion-tienda-ampliada.php             # simulacion
 *     php database/migracion-tienda-ampliada.php --aplicar   # escribe
 *
 * Los niños pidieron más cosas en las que gastar sus monedas. Esto:
 *
 *   - Añade el tipo `beneficio` a `shop_items.kind`.
 *   - Añade accesorios nuevos (cabeza, ojos, cuello, mano).
 *   - Añade cuatro beneficios: lupa, brújula, reloj de arena y cofre
 *     (ver `tieneBeneficio()` en includes/gamificacion.php).
 *
 * Solo AÑADE. Un artículo cuyo slug ya existe no se toca: así no cambia
 * el precio de nada que un niño ya compró. Es repetible.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Tienda ampliada\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

// ── El tipo «beneficio» ──────────────────────────────────────────────
$tipo = (string) traerValor(
    'SELECT COLUMN_TYPE FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "shop_items" AND COLUMN_NAME = "kind"');

if (strpos($tipo, "'beneficio'") === false) {
    echo "  [->] shop_items.kind admite ahora 'beneficio'\n";
    if ($aplicar) {
        ejecutar("ALTER TABLE shop_items
                  MODIFY kind ENUM('avatar','accesorio','beneficio') NOT NULL DEFAULT 'avatar'");
    }
} else {
    echo "  [ok] shop_items.kind ya admite 'beneficio'\n";
}

// ── Los artículos ────────────────────────────────────────────────────
//  [slug, nombre, tipo, emoji, precio, racha, descripción]
$nuevos = [
    // En la cabeza
    ['lazo',        'Moño',             'accesorio', '🎀', 15, 0, 'Un moño de fiesta en la cabeza.'],
    ['pamela',      'Sombrero de sol',  'accesorio', '👒', 25, 0, 'Para los días de mucho sol.'],
    ['audifonos',   'Audífonos',        'accesorio', '🎧', 35, 0, 'Para escuchar tu música favorita.'],
    ['birrete',     'Birrete',          'accesorio', '🎓', 0,  5, 'De graduado. Juega 5 días seguidos.'],

    // En los ojos
    ['lentes',      'Lentes',           'accesorio', '👓', 20, 0, 'Para leer de cerca.'],
    ['gafas-nadar', 'Gafas de nadar',   'accesorio', '🥽', 25, 0, 'Listo para bucear.'],

    // En el cuello
    ['corbata',     'Corbata',          'accesorio', '👔', 30, 0, 'Muy elegante.'],
    ['collar',      'Collar',           'accesorio', '📿', 25, 0, 'Un collar de cuentas.'],

    // En la mano
    ['globo',       'Globo',            'accesorio', '🎈', 15, 0, 'Un globo para celebrar.'],
    ['helado',      'Helado',           'accesorio', '🍦', 15, 0, 'Un helado bien frío.'],
    ['balon',       'Balón',            'accesorio', '⚽', 20, 0, 'Para jugar en el recreo.'],
    ['osito',       'Osito',            'accesorio', '🧸', 30, 0, 'Tu compañero de aventuras.'],
    ['varita',      'Varita mágica',    'accesorio', '🪄', 45, 0, '¡Abracadabra!'],
    ['guitarra',    'Guitarra',         'accesorio', '🎸', 55, 0, 'Para ser una estrella del rock.'],

    // Beneficios
    ['lupa',    'Lupa mágica',     'beneficio', '🔍', 40, 0, 'Las pistas te cuestan 2 monedas en vez de 5.'],
    ['brujula', 'Brújula',         'beneficio', '🧭', 50, 0, 'Te ofrecemos ayuda desde el segundo error.'],
    ['reloj',   'Reloj de arena',  'beneficio', '⏳', 35, 0, 'Más tiempo en los juegos contra reloj.'],
    ['cofre',   'Cofre del tesoro','beneficio', '💰', 80, 0, '+2 monedas cada vez que sacas 3 estrellas.'],
];

$orden  = (int) traerValor('SELECT COALESCE(MAX(sort_order), 0) FROM shop_items');
$puestos = 0;

foreach ($nuevos as [$slug, $nombre, $kind, $emoji, $precio, $racha, $desc]) {
    if (traerValor('SELECT COUNT(*) FROM shop_items WHERE slug = ?', [$slug])) {
        echo "  [ok] $slug ya existe\n";
        continue;
    }

    echo "  [+] $kind $emoji $nombre · $precio monedas" . ($racha ? " · racha $racha" : '') . "\n";
    $puestos++;

    if ($aplicar) {
        $orden++;
        ejecutar('INSERT INTO shop_items (slug, name, kind, emoji, price_coins, needs_streak, description, sort_order, is_active)
                  VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)',
                 [$slug, $nombre, $kind, $emoji, $precio, $racha, $desc, $orden]);
    }
}

echo "\n$puestos artículo(s) nuevo(s).\n";
echo $aplicar ? "Listo.\n" : "Simulacion. Nada se escribio. Repite con --aplicar\n";
