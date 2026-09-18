<?php
/**
 * migracion-modo-nino.php — La cuenta prestada a un niño.
 *
 * ---------------------------------------------------------------------
 *  QUÉ RESUELVE
 * ---------------------------------------------------------------------
 *
 * Un suscriptor le presta la tableta a su hijo. Hoy el niño llega al
 * catálogo entero: 487 actividades de preescolar a sexto, la tienda, la
 * página de planes y los datos de la cuenta.
 *
 * El «modo niño» deja a la vista solo lo que el adulto eligió. Se enciende
 * con un PIN y **solo con ese PIN se apaga**, porque si se pudiera apagar
 * desde un botón no serviría para nada.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ NO SE USAN CURSOS
 * ---------------------------------------------------------------------
 *
 * La ruta del estudiante hace algo muy parecido, pero necesita DOS
 * cuentas: la del docente que asigna y la del niño que juega. Aquí hay una
 * sola — el adulto y el niño comparten usuario, comparten progreso,
 * comparten monedas y comparten personaje.
 *
 * Montarlo con cursos habría obligado a inventarle al padre una cuenta de
 * estudiante para su hijo, y con ella otra suscripción, otro progreso y
 * otro personaje. Esto es una preferencia de la cuenta, no una matrícula.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ NO SE REUSA `pin_hash`
 * ---------------------------------------------------------------------
 *
 * `users.pin_hash` ya existe, pero es el PIN con el que un estudiante
 * entra a su clase desde la lista: protege UNA ENTRADA. Este protege UNA
 * SALIDA, y son cosas distintas — si fueran la misma columna, cambiar el
 * PIN de clase de un niño le abriría el modo niño a otro.
 *
 * Uso:
 *     php database/migracion-modo-nino.php
 *     php database/migracion-modo-nino.php --aplicar
 */

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

$aplicar = in_array('--aplicar', $argv, true);

/** ¿Existe ya esta columna? */
function hayColumna(string $tabla, string $columna): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
        [$tabla, $columna]
    );
}

/** ¿Existe ya esta tabla? */
function hayTabla(string $tabla): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.TABLES
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
        [$tabla]
    );
}

echo "\n";
echo "==========================================================================\n";
echo "  MODO NIÑO\n";
echo "==========================================================================\n\n";

$pasos = [];

// ── Las dos columnas en `users` ──────────────────────────────────────
if (!hayColumna('users', 'child_mode_on')) {
    $pasos[] = [
        'que' => 'users.child_mode_on — si la cuenta está ahora en modo niño',
        'sql' => 'ALTER TABLE `users`
                    ADD COLUMN `child_mode_on` TINYINT(1) NOT NULL DEFAULT 0
                    AFTER `accessory`',
    ];
}

if (!hayColumna('users', 'child_mode_pin')) {
    $pasos[] = [
        'que' => 'users.child_mode_pin — el PIN con el que se sale (hasheado)',
        'sql' => 'ALTER TABLE `users`
                    ADD COLUMN `child_mode_pin` VARCHAR(255) NULL
                    AFTER `child_mode_on`',
    ];
}

/*
 * ── La selección ────────────────────────────────────────────────────
 *
 * Clave primaria compuesta (user_id, activity_id): la misma actividad no
 * puede estar dos veces en la selección de la misma cuenta, y eso lo
 * garantiza la base y no el código que la escribe.
 *
 * `ON DELETE CASCADE` en las dos: si se borra una actividad del catálogo
 * o se cierra una cuenta, su selección se va con ella. Una selección
 * apuntando a una actividad que ya no existe dejaría al niño con un hueco
 * sin explicación.
 */
if (!hayTabla('child_mode_activities')) {
    $pasos[] = [
        'que' => 'child_mode_activities — qué eligió el adulto',
        'sql' => 'CREATE TABLE `child_mode_activities` (
                      `user_id`     INT UNSIGNED NOT NULL,
                      `activity_id` INT UNSIGNED NOT NULL,
                      `sort_order`  SMALLINT NOT NULL DEFAULT 0,
                      `added_at`    DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
                      PRIMARY KEY (`user_id`, `activity_id`),
                      KEY `idx_orden` (`user_id`, `sort_order`),
                      CONSTRAINT `fk_cma_user`
                          FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)
                          ON DELETE CASCADE,
                      CONSTRAINT `fk_cma_activity`
                          FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`)
                          ON DELETE CASCADE
                  ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci',
    ];
}

if (!$pasos) {
    echo "  ✅ Ya estaba todo. No hay nada que hacer.\n\n";
    echo "==========================================================================\n\n";
    exit(0);
}

foreach ($pasos as $p) {
    printf("  · %s\n", $p['que']);
}

if (!$aplicar) {
    echo "\n  Simulación. Para escribir:\n";
    echo "    php database/migracion-modo-nino.php --aplicar\n\n";
    echo "==========================================================================\n\n";
    exit(0);
}

echo "\n";

/*
 * Sin transacción, y a propósito.
 *
 * En MySQL un ALTER o un CREATE hacen un commit implícito: envolverlos en
 * una transacción da la falsa sensación de poder deshacerlos y además
 * revienta con «There is no active transaction» al cerrarla. Cada paso se
 * hace y se informa por separado.
 */
$hechos = 0;

foreach ($pasos as $p) {
    try {
        ejecutar($p['sql']);
        $hechos++;
        printf("  ✓ %s\n", $p['que']);
    } catch (Throwable $e) {
        printf("  ✗ %s\n      %s\n", $p['que'], $e->getMessage());
    }
}

echo "\n--------------------------------------------------------------------------\n";
printf("  Pasos aplicados: %d de %d\n\n", $hechos, count($pasos));

if ($hechos === count($pasos)) {
    echo "  ✅ Listo. El modo niño se configura en «Mi cuenta».\n";
} else {
    echo "  ⚠ Quedaron pasos sin aplicar. Revisa los mensajes de arriba.\n";
}

echo "==========================================================================\n\n";
