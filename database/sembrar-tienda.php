<?php
/**
 * sembrar-tienda.php — Personajes y accesorios canjeables
 *
 *     php database/sembrar-tienda.php            (simulación)
 *     php database/sembrar-tienda.php --aplicar  (escribe)
 *
 * Los diez animales son los mismos del sitio anterior (`Registro.html`):
 * un niño que vuelve debe reencontrar su personaje, no elegir otro.
 *
 * PRECIOS
 * Están puestos para que la primera compra llegue pronto y las últimas
 * cuesten. Una estación bien resuelta da del orden de 5 a 8 monedas, así
 * que:
 *     · los cuatro primeros personajes son gratis — elegir personaje no
 *       se compra, se elige;
 *     · el primer accesorio cae hacia la tercera o cuarta estación;
 *     · lo más caro pide varias semanas.
 * Si la primera recompensa tarda, el niño deja de mirar el contador.
 *
 * Dos piezas no se compran con monedas sino con constancia: piden racha.
 * El dinero de juego no debe poder comprarlo todo, o volver cada día deja
 * de tener sentido.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);


/** slug, nombre, tipo, emoji, precio, racha necesaria, descripción */
const ARTICULOS = [

    // ── Personajes ───────────────────────────────────────────────────
    // Los cuatro primeros gratis: nadie debería empezar sin poder ser
    // alguien.
    ['leon',      'León',      'avatar', '🦁',   0, 0, 'El de siempre'],
    ['panda',     'Panda',     'avatar', '🐼',   0, 0, 'Tranquilo y curioso'],
    ['zorro',     'Zorro',     'avatar', '🦊',   0, 0, 'Rápido para las pistas'],
    ['rana',      'Rana',      'avatar', '🐸',   0, 0, 'Salta de reto en reto'],

    ['tigre',     'Tigre',     'avatar', '🐯',  25, 0, 'Para los que no se rinden'],
    ['mono',      'Mono',      'avatar', '🐵',  25, 0, 'Le encanta trepar niveles'],
    ['buho',      'Búho',      'avatar', '🦉',  40, 0, 'El que más lee'],
    ['koala',     'Koala',     'avatar', '🐨',  40, 0, 'Se toma su tiempo'],
    ['pinguino',  'Pingüino',  'avatar', '🐧',  60, 0, 'Nada le da frío'],
    ['conejo',    'Conejo',    'avatar', '🐰',  60, 0, 'El más veloz'],

    // Dos que no se compran: se ganan volviendo.
    ['dragon',    'Dragón',    'avatar', '🐲',   0, 7, 'Solo para quien vuelve una semana seguida'],
    ['unicornio', 'Unicornio', 'avatar', '🦄',   0, 14, 'Dos semanas seguidas. Casi nadie lo tiene'],

    // ── Accesorios ───────────────────────────────────────────────────
    // El primero cuesta poco a propósito: es la primera vez que el niño
    // ve que las monedas sirven para algo.
    ['gorra',     'Gorra',        'accesorio', '🧢',  10, 0, 'Para el sol'],
    ['sombrero',  'Sombrero',     'accesorio', '🎩',  20, 0, 'Elegante'],
    ['gafas',     'Gafas',        'accesorio', '🕶️',  20, 0, 'Para ver mejor las pistas'],
    ['corona',    'Corona',       'accesorio', '👑',  50, 0, 'De campeón'],
    ['casco',     'Casco',        'accesorio', '⛑️',  30, 0, 'Seguridad ante todo'],
    ['bufanda',   'Bufanda',      'accesorio', '🧣',  30, 0, 'Para el frío'],
    ['mochila',   'Mochila',      'accesorio', '🎒',  35, 0, 'Para llevarlo todo'],
    ['medalla',   'Medalla',      'accesorio', '🏅',  70, 0, 'La lleva quien la gana'],
    ['flor',      'Flor',         'accesorio', '🌸',  15, 0, 'Sencilla y bonita'],
    ['estrella',  'Estrella',     'accesorio', '⭐',  45, 0, 'Brilla desde lejos'],

    ['cohete',    'Cohete',       'accesorio', '🚀',   0, 3, 'Tres días seguidos'],
    ['trofeo',    'Trofeo',       'accesorio', '🏆',   0, 10, 'Diez días seguidos'],
];


echo str_repeat('=', 76), "\n";
echo "  TIENDA: PERSONAJES Y ACCESORIOS\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 76), "\n\n";

// La tabla puede no existir en una instalación anterior a este cambio.
if ($aplicar) {
    ejecutar(
        'CREATE TABLE IF NOT EXISTS `shop_items` (
            `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `slug`         VARCHAR(60)  NOT NULL,
            `name`         VARCHAR(120) NOT NULL,
            `kind`         ENUM("avatar","accesorio") NOT NULL DEFAULT "avatar",
            `emoji`        VARCHAR(16)  NOT NULL,
            `price_coins`  SMALLINT UNSIGNED NOT NULL DEFAULT 0,
            `needs_streak` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
            `description`  VARCHAR(200) NULL,
            `sort_order`   SMALLINT     NOT NULL DEFAULT 0,
            `is_active`    TINYINT(1)   NOT NULL DEFAULT 1,
            `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_shop_slug` (`slug`),
            KEY `idx_shop_kind` (`kind`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    ejecutar(
        'CREATE TABLE IF NOT EXISTS `user_items` (
            `user_id`    INT UNSIGNED NOT NULL,
            `item_id`    INT UNSIGNED NOT NULL,
            `paid_coins` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`user_id`, `item_id`),
            KEY `idx_user_items_item` (`item_id`),
            CONSTRAINT `fk_ui_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)      ON DELETE CASCADE,
            CONSTRAINT `fk_ui_item` FOREIGN KEY (`item_id`) REFERENCES `shop_items` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    // La columna del accesorio no existía.
    $tiene = traerValor(
        "SELECT COUNT(1) FROM information_schema.columns
          WHERE table_schema = DATABASE() AND table_name = 'users' AND column_name = 'accessory'"
    );

    if (!$tiene) {
        ejecutar("ALTER TABLE `users` ADD COLUMN `accessory` VARCHAR(60) NULL
                  COMMENT 'Accesorio puesto sobre el personaje' AFTER `avatar`");
        echo "  Se añadió la columna users.accessory\n\n";
    }
}

$orden = 0;
$nuevos = 0;
$actualizados = 0;

foreach (ARTICULOS as [$slug, $nombre, $tipo, $emoji, $precio, $rachaMin, $desc]) {
    $orden++;

    if (!$aplicar) {
        printf("  %-10s %-12s %-4s %-10s %s\n",
               $tipo, $nombre, $emoji,
               $rachaMin > 0 ? "racha $rachaMin" : ($precio > 0 ? "$precio 🪙" : 'gratis'),
               $desc);
        continue;
    }

    $existe = traerUno('SELECT id FROM shop_items WHERE slug = ?', [$slug]);

    if ($existe) {
        ejecutar(
            'UPDATE shop_items SET name = ?, kind = ?, emoji = ?, price_coins = ?,
                    needs_streak = ?, description = ?, sort_order = ?, is_active = 1
              WHERE id = ?',
            [$nombre, $tipo, $emoji, $precio, $rachaMin, $desc, $orden, $existe['id']]
        );
        $actualizados++;
    } else {
        insertar(
            'INSERT INTO shop_items (slug, name, kind, emoji, price_coins, needs_streak,
                                     description, sort_order, is_active)
             VALUES (?, ?, ?, ?, ?, ?, ?, ?, 1)',
            [$slug, $nombre, $tipo, $emoji, $precio, $rachaMin, $desc, $orden]
        );
        $nuevos++;
    }
}

if ($aplicar) {
    /*
     * Los personajes gratuitos se le dan a todo el mundo de una vez: si
     * no, la tienda le pediría al niño «comprar» por 0 monedas algo que
     * ya es suyo, y eso solo añade un paso sin sentido.
     */
    ejecutar(
        'INSERT IGNORE INTO user_items (user_id, item_id, paid_coins)
         SELECT u.id, i.id, 0
           FROM users u
           CROSS JOIN shop_items i
          WHERE i.price_coins = 0 AND i.needs_streak = 0 AND i.is_active = 1'
    );

    // Quien no tenga personaje elegido, empieza con el león.
    ejecutar("UPDATE users SET avatar = 'leon' WHERE avatar IS NULL OR avatar = ''");

    printf("\n  Artículos nuevos      : %d\n", $nuevos);
    printf("  Artículos actualizados: %d\n", $actualizados);
    printf("  Personajes            : %d\n",
           (int) traerValor("SELECT COUNT(1) FROM shop_items WHERE kind = 'avatar'"));
    printf("  Accesorios            : %d\n",
           (int) traerValor("SELECT COUNT(1) FROM shop_items WHERE kind = 'accesorio'"));
    printf("  Entregados de salida  : %d\n",
           (int) traerValor('SELECT COUNT(1) FROM user_items'));
}

echo $aplicar
    ? "\n  Aplicado.\n"
    : "\n  Simulación. Para escribir: php database/sembrar-tienda.php --aplicar\n";

echo str_repeat('=', 76), "\n";
