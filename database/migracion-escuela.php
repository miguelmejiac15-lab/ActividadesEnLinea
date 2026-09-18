<?php
/**
 * migracion-escuela.php — Fase 5 · Área Escuela
 *
 *     php database/migracion-escuela.php             # simulación
 *     php database/migracion-escuela.php --aplicar   # escribe
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ `courses.school_id` TIENE QUE ADMITIR NULL
 * ─────────────────────────────────────────────────────────────────────
 *
 * El esquema original daba por hecho que todo curso pertenece a un
 * colegio contratado: `school_id INT UNSIGNED NOT NULL` con clave foránea
 * a `schools`. Eso venía de pensar la plataforma solo en modo B2B.
 *
 * Pero el panel de docente lo usa también quien no está en ningún
 * colegio: una profesora particular, alguien que enseña a cinco niños en
 * casa, un colegio que todavía no firmó nada y está probando. Esa persona
 * no tiene `school_id`, y forzarla a tenerlo obliga a una de dos
 * mentiras: inventar un colegio falso por cada docente —ensuciando una
 * tabla que representa un cliente con contrato y cupo— o insertar
 * `school_id = 0`, que la clave foránea rechaza de plano.
 *
 * Así que la columna pasa a NULL: «este curso no es de ningún colegio»
 * es un hecho real, y NULL es exactamente cómo se escribe eso.
 *
 * La clave foránea se rehace con ON DELETE CASCADE igual que antes: si se
 * borra un colegio, sus cursos se van con él. Los cursos con NULL no
 * dependen de ninguna fila, así que no les afecta.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  ROLES
 * ─────────────────────────────────────────────────────────────────────
 *
 * `puedeEntrarAEscuela()` acepta el rol `teacher`. El rol ya existía en
 * el ENUM de `users` pero no había forma de asignarlo desde ningún sitio;
 * esta migración no lo reparte —eso lo hace el administrador— solo deja
 * constancia de que a partir de aquí significa algo.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por línea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 5 - Escuela\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

$pdo     = db();
$cambios = 0;


// =====================================================================
//  1. `courses.school_id` PASA A ADMITIR NULL
// =====================================================================

echo "-- courses.school_id ------------------------------------------\n";

$col = traerUno(
    "SELECT IS_NULLABLE, COLUMN_TYPE
       FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE()
        AND TABLE_NAME   = 'courses'
        AND COLUMN_NAME  = 'school_id'"
);

if (!$col) {
    echo "  [!] No existe la tabla courses o la columna. Corre schema.sql primero.\n";
} elseif ($col['IS_NULLABLE'] === 'YES') {
    echo "  [ok] Ya admite NULL. Nada que hacer.\n";
} else {
    echo "  [->] Esta en NOT NULL. Hay que soltar la clave foranea,\n";
    echo "       cambiar la columna y volver a ponerla.\n";

    if ($aplicar) {
        // El nombre de la restriccion se busca en vez de darlo por hecho:
        // una base restaurada de un volcado puede tenerla con otro nombre.
        $fk = traerValor(
            "SELECT CONSTRAINT_NAME
               FROM information_schema.KEY_COLUMN_USAGE
              WHERE TABLE_SCHEMA = DATABASE()
                AND TABLE_NAME   = 'courses'
                AND COLUMN_NAME  = 'school_id'
                AND REFERENCED_TABLE_NAME = 'schools'
              LIMIT 1"
        );

        if ($fk) {
            $pdo->exec('ALTER TABLE `courses` DROP FOREIGN KEY `' . $fk . '`');
            echo "       - clave foranea $fk soltada\n";
        }

        $pdo->exec(
            "ALTER TABLE `courses`
             MODIFY `school_id` INT UNSIGNED NULL
             COMMENT 'NULL = curso de un docente independiente, sin colegio detras'"
        );
        echo "       - columna ahora NULL\n";

        $pdo->exec(
            'ALTER TABLE `courses`
             ADD CONSTRAINT `fk_courses_school`
             FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE'
        );
        echo "       - clave foranea repuesta\n";
        echo "  [ok] Hecho.\n";
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  2. CURSOS HUERFANOS CON school_id = 0
// =====================================================================

/*
 * Si alguna versión anterior llegó a insertar ceros —con las claves
 * foráneas desactivadas, por ejemplo— apuntan a un colegio que no existe.
 * Ahora que la columna admite NULL, ese cero se convierte en lo que de
 * verdad significaba.
 */

echo "-- Cursos con school_id = 0 -----------------------------------\n";

$ceros = (int) traerValor('SELECT COUNT(*) FROM courses WHERE school_id = 0');

if ($ceros === 0) {
    echo "  [ok] Ninguno.\n";
} else {
    echo "  [->] $ceros curso(s) con school_id = 0. Pasan a NULL.\n";
    if ($aplicar) {
        ejecutar('UPDATE courses SET school_id = NULL WHERE school_id = 0');
        echo "  [ok] Corregidos.\n";
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  3. COMPROBACION FINAL
// =====================================================================

echo "-- Estado del area --------------------------------------------\n";
printf("  Cursos:                  %d\n", (int) traerValor('SELECT COUNT(*) FROM courses'));
printf("  Matriculas:              %d\n", (int) traerValor('SELECT COUNT(*) FROM course_students'));
printf("  Actividades asignadas:   %d\n", (int) traerValor('SELECT COUNT(*) FROM course_activities'));
printf("  Cuentas con rol teacher: %d\n",
    (int) traerValor("SELECT COUNT(*) FROM users WHERE role = 'teacher'"));

echo "\n";
echo $aplicar
    ? "Listo. $cambios cambio(s) aplicado(s).\n\n"
    : "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
