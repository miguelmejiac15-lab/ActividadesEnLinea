<?php
/**
 * migracion-tareas-casa.php — Tareas para casa y resumen a la familia
 *
 *     php database/migracion-tareas-casa.php             # simulacion
 *     php database/migracion-tareas-casa.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  LO QUE PIDIO UNA DOCENTE
 * ---------------------------------------------------------------------
 *
 * Que en casa el nino haga lo que ella deja para la casa, y que la
 * familia pueda ver como va. Hacia falta distinguir, entre lo asignado,
 * que es trabajo de clase y que es tarea para la casa; y dejar escrito
 * cuando se le envio el resumen a cada familia, para no mandarle diez
 * correos el mismo dia.
 *
 * Solo ANADE: una columna con valor por defecto 0 —las asignaciones que
 * ya existen siguen siendo de clase, exactamente como hasta ahora— y una
 * tabla nueva. No toca ni una fila de uso.
 *
 * La tabla de envios NO guarda la direccion de correo: la direccion vive
 * en `users.guardian_email` y aqui basta con saber a que nino y cuando.
 * Un registro de envios con correos de familias seria una copia mas de
 * un dato personal que no hace falta (Decreto 0769 de 2026).
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Tareas para casa y resumen a la familia\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

/** ¿Existe ya esta columna? Es lo que hace repetible la migración. */
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

if (!hayTabla('course_activities')) {
    exit("  [!] No existe la tabla `course_activities`. Aplica antes migracion-escuela.php\n");
}

$cambios = 0;

echo "-- Columnas ---------------------------------------------------\n";

if (hayColumna('course_activities', 'para_casa')) {
    echo "  [ok] course_activities.para_casa ya existe.\n";
} else {
    echo "  [->] Se anade course_activities.para_casa\n";

    if ($aplicar) {
        ejecutar('ALTER TABLE `course_activities`
                  ADD COLUMN `para_casa` TINYINT(1) NOT NULL DEFAULT 0
                  COMMENT "1 = tarea para hacer en casa; 0 = trabajo de clase"
                  AFTER `due_date`');
        $cambios++;
    }
}

echo "\n-- Tablas -----------------------------------------------------\n";

if (hayTabla('guardian_reports')) {
    echo "  [ok] guardian_reports ya existe.\n";
} else {
    echo "  [->] Se crea guardian_reports\n";

    if ($aplicar) {
        ejecutar('CREATE TABLE IF NOT EXISTS `guardian_reports` (
            `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `course_id`   INT UNSIGNED NOT NULL,
            `student_id`  INT UNSIGNED NOT NULL,
            `sent_by`     INT UNSIGNED NULL,
            `ok`          TINYINT(1) NOT NULL DEFAULT 0,
            `error`       VARCHAR(255) NULL,
            `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_gr_alumno` (`student_id`, `created_at`),
            KEY `idx_gr_curso` (`course_id`, `created_at`),
            CONSTRAINT `fk_gr_curso`  FOREIGN KEY (`course_id`)  REFERENCES `courses` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_gr_alumno` FOREIGN KEY (`student_id`) REFERENCES `users` (`id`)   ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
        $cambios++;
    }
}

echo "\n-- Estado -----------------------------------------------------\n";

if (hayColumna('course_activities', 'para_casa')) {
    printf("  Asignaciones para casa        : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM course_activities WHERE para_casa = 1'));
}

printf("  Estudiantes con acudiente     : %d\n",
    (int) traerValor("SELECT COUNT(*) FROM users WHERE role = 'student' AND guardian_email IS NOT NULL AND guardian_email <> ''"));

echo "\n";

if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
} else {
    echo "Listo. $cambios cambio(s) aplicado(s).\n";
}
