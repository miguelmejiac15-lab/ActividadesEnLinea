<?php
/**
 * migracion-acceso-seguro.php — Registro a prueba de robots y correo verificado
 *
 *     php database/migracion-acceso-seguro.php             # simulacion
 *     php database/migracion-acceso-seguro.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE
 * ---------------------------------------------------------------------
 *
 * Aparecieron cuentas con correos que parecen inventados. Crear una
 * cuenta no pedia nada mas que un formulario, sin freno de intentos ni
 * forma de saber si el correo existe. Esta migracion pone lo que hace
 * falta en la base para cerrarlo:
 *
 *   users.email_verified_at   cuando se confirmo que el correo es suyo
 *   users.google_sub          la cuenta de Google con que entra, si entra asi
 *   access_attempts           intentos de registro y de ingreso, para frenar
 *                             a quien los repite (por IP cifrada, nunca en claro)
 *   email_verifications       los enlaces de verificacion (solo su hash)
 *
 * Solo ANADE. Las cuentas que ya existen se dan por verificadas al crear
 * la columna: exigirles ahora un correo que quiza nunca les llega las
 * dejaria fuera de la noche a la manana. Las sospechosas se revisan a mano
 * desde el panel (filtro «sin verificar» y «nunca entro»).
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Acceso seguro\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

function hayColumna(string $tabla, string $columna): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
        [$tabla, $columna]
    );
}

function hayTabla(string $tabla): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.TABLES
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
        [$tabla]
    );
}

$cambios = 0;

echo "-- Columnas ---------------------------------------------------\n";

if (hayColumna('users', 'email_verified_at')) {
    echo "  [ok] users.email_verified_at ya existe.\n";
} else {
    echo "  [->] Se anade users.email_verified_at y se marcan como verificadas las cuentas existentes\n";

    if ($aplicar) {
        ejecutar('ALTER TABLE `users`
                  ADD COLUMN `email_verified_at` DATETIME NULL
                  COMMENT "Cuando se confirmo que el correo es de quien lo registro"
                  AFTER `email`');
        // Solo al crear la columna: las cuentas de antes no se bloquean.
        $n = ejecutar('UPDATE `users` SET `email_verified_at` = `created_at` WHERE `email_verified_at` IS NULL');
        echo "       $n cuenta(s) existentes marcadas como verificadas.\n";
        $cambios++;
    }
}

if (hayColumna('users', 'google_sub')) {
    echo "  [ok] users.google_sub ya existe.\n";
} else {
    echo "  [->] Se anade users.google_sub\n";

    if ($aplicar) {
        ejecutar('ALTER TABLE `users`
                  ADD COLUMN `google_sub` VARCHAR(64) NULL
                  COMMENT "Identificador estable de la cuenta de Google"
                  AFTER `email_verified_at`,
                  ADD UNIQUE KEY `uk_users_google_sub` (`google_sub`)');
        $cambios++;
    }
}

echo "\n-- Tablas -----------------------------------------------------\n";

if (hayTabla('access_attempts')) {
    echo "  [ok] access_attempts ya existe.\n";
} else {
    echo "  [->] Se crea access_attempts\n";

    if ($aplicar) {
        ejecutar('CREATE TABLE IF NOT EXISTS `access_attempts` (
            `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `kind`       ENUM("registro","ingreso","reenvio") NOT NULL,
            `key_hash`   CHAR(64) NOT NULL COMMENT "SHA-256 de la IP o del correo, nunca en claro",
            `ok`         TINYINT(1) NOT NULL DEFAULT 0,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_aa_busqueda` (`kind`, `key_hash`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $cambios++;
    }
}

if (hayTabla('email_verifications')) {
    echo "  [ok] email_verifications ya existe.\n";
} else {
    echo "  [->] Se crea email_verifications\n";

    if ($aplicar) {
        ejecutar('CREATE TABLE IF NOT EXISTS `email_verifications` (
            `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `user_id`    INT UNSIGNED NOT NULL,
            `token_hash` CHAR(64) NOT NULL,
            `expires_at` DATETIME NOT NULL,
            `used_at`    DATETIME NULL,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_ev_token` (`token_hash`),
            KEY `idx_ev_usuario` (`user_id`),
            CONSTRAINT `fk_ev_usuario` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $cambios++;
    }
}

echo "\n-- Ajustes ----------------------------------------------------\n";

/*
 * Exigir el correo verificado queda APAGADO hasta que haya correo
 * saliente: encenderlo sin poder enviar el enlace dejaria a cada cuenta
 * nueva encerrada fuera. Se enciende desde el panel (Ajustes).
 */
if (traerValor('SELECT COUNT(*) FROM settings WHERE `key` = "exigir_correo_verificado"')) {
    echo "  [ok] ajuste exigir_correo_verificado ya existe.\n";
} else {
    echo "  [->] Se crea el ajuste exigir_correo_verificado = 0\n";

    if ($aplicar) {
        ejecutar('INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)', [
            'exigir_correo_verificado', '0',
            'Exigir que las cuentas nuevas confirmen su correo antes de entrar (requiere correo saliente)',
        ]);
        $cambios++;
    }
}

echo "\n";

if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
} else {
    echo "Listo. $cambios cambio(s) aplicado(s).\n";
}
