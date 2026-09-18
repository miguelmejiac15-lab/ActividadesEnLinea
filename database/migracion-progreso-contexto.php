<?php
/**
 * migracion-progreso-contexto.php — Fase 11 · Progreso personal e institucional
 *
 *     php database/migracion-progreso-contexto.php             # simulacion
 *     php database/migracion-progreso-contexto.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  QUE CAMBIA
 * ---------------------------------------------------------------------
 *
 * Hasta ahora habia UNA fila por (usuario, estacion), y la columna
 * `course_id` existia pero **nadie la escribia**. Es decir: el progreso
 * que un nino hace en casa y el que hace para la clase de su profe eran
 * exactamente la misma fila.
 *
 * A partir de aqui hay una fila por (usuario, estacion, CONTEXTO):
 *
 *   course_id = 0   lo hizo por su cuenta          (personal)
 *   course_id = N   lo hizo para el curso N        (institucional)
 *
 * ---------------------------------------------------------------------
 *  POR QUE 0 Y NO NULL
 * ---------------------------------------------------------------------
 *
 * MySQL considera que dos NULL son distintos dentro de un indice UNICO,
 * asi que con `course_id` en NULL se podrian crear filas personales
 * duplicadas para la misma estacion — que es justo lo que el indice
 * existe para impedir. Con 0 el indice funciona.
 *
 * La contrapartida es que 0 no puede tener clave foranea a `courses`. Se
 * asume: el precio de un cero suelto es mucho menor que el de un indice
 * que no impide nada.
 *
 * ---------------------------------------------------------------------
 *  QUE PASA CON LO QUE YA HABIA
 * ---------------------------------------------------------------------
 *
 * Todo el progreso existente pasa a `course_id = 0`, o sea PERSONAL. Es
 * la lectura honesta: se hizo cuando no existia el contexto de curso, asi
 * que nadie puede afirmar que fuera trabajo de clase.
 *
 * Consecuencia que hay que saber: en la rejilla del docente ese avance
 * anterior deja de contar como institucional. No se pierde —sigue en la
 * ficha del estudiante y en su gamificacion— pero el docente vera ceros
 * donde antes veia numeros. Es correcto, y es mejor decirlo que
 * disimularlo inventando un curso para datos que no lo tenian.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 11 - Progreso personal e institucional\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

$pdo     = db();
$cambios = 0;

/** Datos de una columna. */
function columna(string $tabla, string $col): ?array
{
    return traerUno(
        'SELECT IS_NULLABLE, COLUMN_DEFAULT, COLUMN_TYPE
           FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
        [$tabla, $col]
    );
}

/** ¿Existe el indice? */
function hayIndice(string $tabla, string $indice): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.STATISTICS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND INDEX_NAME = ?',
        [$tabla, $indice]
    );
}

echo "-- Estado actual -----------------------------------------------\n";

$col = columna('activity_progress', 'course_id');

if (!$col) {
    echo "  [!] No existe activity_progress.course_id.\n\n";
    exit(1);
}

printf("  course_id          : %s, por defecto %s\n",
    $col['IS_NULLABLE'] === 'YES' ? 'admite NULL' : 'NOT NULL',
    var_export($col['COLUMN_DEFAULT'], true));

printf("  Filas de progreso  : %d\n",
    (int) traerValor('SELECT COUNT(*) FROM activity_progress'));
printf("  Con course_id NULL : %d\n",
    (int) traerValor('SELECT COUNT(*) FROM activity_progress WHERE course_id IS NULL'));
printf("  Con course_id      : %d\n",
    (int) traerValor('SELECT COUNT(*) FROM activity_progress WHERE course_id IS NOT NULL'));

echo "\n";


// =====================================================================
//  1. LA CLAVE FORANEA ESTORBA
// =====================================================================

/*
 * `fk_prog_course` apunta a `courses` con ON DELETE SET NULL. Con la
 * columna en NOT NULL DEFAULT 0 esa regla no puede cumplirse, asi que se
 * suelta. El precio: borrar un curso ya no limpia sus filas de progreso
 * automaticamente. Es aceptable —y de hecho preferible— porque el
 * progreso de un nino no debe evaporarse porque se borre un curso; lo que
 * hay que hacer es archivarlo, que es lo que ofrece el panel.
 */

echo "-- Clave foranea del curso -------------------------------------\n";

$fk = traerValor(
    "SELECT CONSTRAINT_NAME FROM information_schema.KEY_COLUMN_USAGE
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'activity_progress'
        AND COLUMN_NAME = 'course_id' AND REFERENCED_TABLE_NAME = 'courses' LIMIT 1"
);

if (!$fk) {
    echo "  [ok] Ya no hay clave foranea que soltar.\n";
} else {
    echo "  [->] Se suelta `$fk`.\n";
    if ($aplicar) {
        $pdo->exec("ALTER TABLE `activity_progress` DROP FOREIGN KEY `$fk`");
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  2. NULL PASA A 0
// =====================================================================

echo "-- Progreso anterior: pasa a PERSONAL --------------------------\n";

$nulos = (int) traerValor('SELECT COUNT(*) FROM activity_progress WHERE course_id IS NULL');

if ($nulos === 0) {
    echo "  [ok] No hay filas con NULL.\n";
} else {
    printf("  [->] %d fila(s) pasan a course_id = 0 (personal).\n", $nulos);
    if ($aplicar) {
        ejecutar('UPDATE activity_progress SET course_id = 0 WHERE course_id IS NULL');
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  3. LA COLUMNA
// =====================================================================

echo "-- course_id NOT NULL DEFAULT 0 --------------------------------\n";

if ($col['IS_NULLABLE'] === 'NO' && (string) $col['COLUMN_DEFAULT'] === '0') {
    echo "  [ok] Ya esta.\n";
} else {
    echo "  [->] Se cambia.\n";
    if ($aplicar) {
        $pdo->exec(
            "ALTER TABLE `activity_progress`
             MODIFY `course_id` INT UNSIGNED NOT NULL DEFAULT 0
             COMMENT '0 = progreso personal; N = hecho para el curso N'"
        );
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  4. EL INDICE UNICO
// =====================================================================

echo "-- Indice unico ------------------------------------------------\n";

if (hayIndice('activity_progress', 'uk_progress_contexto')) {
    echo "  [ok] uk_progress_contexto ya existe.\n";
} else {
    echo "  [->] uk_progress (user_id, station_id)\n";
    echo "       pasa a uk_progress_contexto (user_id, station_id, course_id)\n";

    if ($aplicar) {

        /*
         * EL ORDEN IMPORTA, y no es evidente.
         *
         * `uk_progress` empieza por (user_id, station_id), así que MySQL
         * lo estaba usando también para sostener la clave foránea de
         * `station_id`. Soltarlo el primero falla con
         * «Cannot drop index: needed in a foreign key constraint».
         *
         * El índice nuevo empieza por user_id igual, pero eso no basta:
         * hace falta uno que empiece por `station_id`. Se crea antes de
         * soltar nada.
         */
        if (!hayIndice('activity_progress', 'idx_progress_station')) {
            $pdo->exec(
                'ALTER TABLE `activity_progress` ADD KEY `idx_progress_station` (`station_id`)'
            );
        }

        $pdo->exec(
            'ALTER TABLE `activity_progress`
             ADD UNIQUE KEY `uk_progress_contexto` (`user_id`, `station_id`, `course_id`)'
        );

        if (hayIndice('activity_progress', 'uk_progress')) {
            $pdo->exec('ALTER TABLE `activity_progress` DROP INDEX `uk_progress`');
        }

        // Para las consultas del docente, que siempre filtran por curso:
        // sin esto, la rejilla de un curso grande recorre la tabla entera.
        if (!hayIndice('activity_progress', 'idx_progress_curso_user')) {
            $pdo->exec(
                'ALTER TABLE `activity_progress`
                 ADD KEY `idx_progress_curso_user` (`course_id`, `user_id`, `status`)'
            );
        }

        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  5. COMPROBACION
// =====================================================================

echo "-- Como queda --------------------------------------------------\n";

$col = columna('activity_progress', 'course_id');

printf("  course_id          : %s, por defecto %s\n",
    $col['IS_NULLABLE'] === 'YES' ? 'admite NULL' : 'NOT NULL',
    var_export($col['COLUMN_DEFAULT'], true));
printf("  Indice de contexto : %s\n",
    hayIndice('activity_progress', 'uk_progress_contexto') ? 'si' : 'NO');
printf("  Progreso personal  : %d fila(s)\n",
    (int) traerValor('SELECT COUNT(*) FROM activity_progress WHERE course_id = 0'));
printf("  Progreso de curso  : %d fila(s)\n",
    (int) traerValor('SELECT COUNT(*) FROM activity_progress WHERE course_id > 0'));

echo "\n";

echo $aplicar
    ? "Listo. $cambios cambio(s) aplicado(s).\n\n"
      . "AVISO: todo el progreso anterior quedo como PERSONAL. En la rejilla\n"
      . "del docente ese avance ya no cuenta como trabajo de clase — sigue\n"
      . "estando, pero marcado como «ya lo sabia de casa».\n\n"
    : "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
