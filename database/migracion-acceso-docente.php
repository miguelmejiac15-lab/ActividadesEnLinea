<?php
/**
 * migracion-acceso-docente.php — El docente decide qué está disponible
 *
 *     php database/migracion-acceso-docente.php             # simulacion
 *     php database/migracion-acceso-docente.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  LO QUE PIDIO UN DOCENTE
 * ---------------------------------------------------------------------
 *
 * En una ruta en orden, la M solo se abría cuando el niño terminaba la A.
 * Pero la clase avanza aunque no todos terminen: hay estaciones —el
 * crucigrama— que a algunos todavía les cuestan, y al día siguiente toca
 * la M. El docente tiene que poder abrirla él.
 *
 * Una columna, `acceso`:
 *   auto     — como hasta ahora: la decide la ruta (por defecto).
 *   abierta  — disponible ya, termine o no lo anterior.
 *   cerrada  — no disponible todavía, aunque la ruta la abriera.
 *
 * Solo AÑADE la columna con valor por defecto 'auto': las asignaciones
 * que ya existen se comportan exactamente igual que antes.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - El docente abre o cierra actividades\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

$hayTabla = (bool) traerValor(
    'SELECT COUNT(*) FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "course_activities"');

if (!$hayTabla) {
    exit("  [!] No existe la tabla `course_activities`. Aplica antes migracion-escuela.php\n");
}

$hayColumna = (bool) traerValor(
    'SELECT COUNT(*) FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "course_activities" AND COLUMN_NAME = "acceso"');

if ($hayColumna) {
    exit("  [ok] course_activities.acceso ya existe. Nada que hacer.\n");
}

echo "  [->] Se anade course_activities.acceso (auto | abierta | cerrada)\n";

if ($aplicar) {
    ejecutar('ALTER TABLE `course_activities`
              ADD COLUMN `acceso` VARCHAR(10) NOT NULL DEFAULT "auto"
              COMMENT "auto = la decide la ruta; abierta / cerrada = la decide el docente"');
    echo "Listo.\n";
} else {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
