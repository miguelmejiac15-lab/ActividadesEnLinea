<?php
/**
 * migracion-ruta.php — La ruta de aprendizaje del estudiante
 *
 * Dos cambios, los dos pequeños:
 *
 *   1. `courses.ruta_modo` · «secuencial» o «libre». Lo decide el docente
 *      en cada curso, como ya decide cómo entran sus estudiantes.
 *
 *   2. `course_activities.sort_order` renumerado 1..N por curso. La
 *      columna existía desde el primer esquema —con el comentario
 *      «Define la secuencia o ruta»— pero nadie la ordenaba: al asignar
 *      un paquete entero todas entraban con el mismo número, y con
 *      empates no hay «la siguiente». Sin esto, una ruta secuencial no
 *      significa nada.
 *
 * Idempotente: se puede correr las veces que haga falta.
 *
 *   php database/migracion-ruta.php            (solo mira)
 *   php database/migracion-ruta.php --aplicar
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

$aplicar = in_array('--aplicar', $argv ?? [], true);

$pdo = db();

function hayColumna(string $tabla, string $columna): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
        [$tabla, $columna]
    );
}

echo "\n=== RUTA DE APRENDIZAJE ===\n\n";

// ── 1. El modo de la ruta ────────────────────────────────────────────
$faltaModo = !hayColumna('courses', 'ruta_modo');

echo $faltaModo
    ? "· courses.ruta_modo: FALTA\n"
    : "· courses.ruta_modo: ya está\n";

// ── 2. Cursos con el orden empatado ──────────────────────────────────
$empatados = traerTodo(
    'SELECT course_id, COUNT(*) AS n, COUNT(DISTINCT sort_order) AS distintos
       FROM course_activities
   GROUP BY course_id
     HAVING n > 1 AND distintos < n'
);

printf("· cursos con el orden empatado: %d\n", count($empatados));

$total = (int) traerValor('SELECT COUNT(*) FROM course_activities');
printf("· asignaciones en total: %d\n", $total);

if (!$aplicar) {
    echo "\nEsto es solo una revisión. Para aplicarlo:\n";
    echo "  php database/migracion-ruta.php --aplicar\n\n";
    exit(0);
}

echo "\nAplicando...\n";

try {
    /*
     * El ALTER va FUERA de la transacción a propósito: en MySQL una
     * sentencia DDL hace un commit implícito, así que meterla dentro no
     * la protege —y deja la transacción cerrada, de modo que el `commit()`
     * de después falla con «There is no active transaction» aunque todo
     * haya ido bien.
     */
    if ($faltaModo) {
        /*
         * Por defecto **secuencial**. Es lo que un docente espera al
         * mandar una lista de actividades a niños de seis años: que las
         * hagan en ese orden. Quien quiera otra cosa lo cambia en su
         * curso, pero el que no toque nada no debe encontrarse a la
         * clase entera saltando al final.
         */
        $pdo->exec(
            "ALTER TABLE `courses`
               ADD COLUMN `ruta_modo` ENUM('secuencial','libre')
               NOT NULL DEFAULT 'secuencial'
               COMMENT 'Si el estudiante avanza en orden o elige'
             AFTER `status`"
        );
        echo "  · courses.ruta_modo creada\n";
    }

    /*
     * Renumerar 1..N respetando el orden que ya se ve hoy: primero lo
     * que tenga sort_order más bajo y, en los empates, lo que se asignó
     * antes. Es exactamente el `ORDER BY` de `actividadesDelCurso()`, así
     * que el docente ve la misma lista de siempre: solo deja de estar
     * empatada.
     */
    // El renumerado sí es un cambio de datos, y ese sí va en transacción.
    $pdo->beginTransaction();

    $cursos = traerTodo('SELECT DISTINCT course_id FROM course_activities');
    $tocados = 0;

    foreach ($cursos as $c) {
        $filas = traerTodo(
            'SELECT id FROM course_activities
              WHERE course_id = ?
           ORDER BY sort_order, assigned_at, id',
            [(int) $c['course_id']]
        );

        $n = 0;
        foreach ($filas as $f) {
            $n++;
            ejecutar('UPDATE course_activities SET sort_order = ? WHERE id = ?',
                     [$n, (int) $f['id']]);
            $tocados++;
        }
    }

    printf("  · %d asignaciones renumeradas en %d curso(s)\n", $tocados, count($cursos));

    $pdo->commit();

} catch (Throwable $e) {
    if ($pdo->inTransaction()) {
        $pdo->rollBack();
    }
    echo "\n  FALLÓ: " . $e->getMessage() . "\n\n";
    exit(1);
}

echo "\nListo.\n\n";
