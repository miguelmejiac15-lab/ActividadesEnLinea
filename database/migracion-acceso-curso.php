<?php
/**
 * migracion-acceso-curso.php — Fase 9 · Como entran los estudiantes
 *
 *     php database/migracion-acceso-curso.php             # simulacion
 *     php database/migracion-acceso-curso.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE SE REHACE LO DE LA FASE 8
 * ---------------------------------------------------------------------
 *
 * La fase 8 dejo dos casillas sueltas —«permitir el codigo» y «pedir
 * PIN»— y el docente tenia que deducir de ellas como iba a entrar su
 * clase. No funciono: hay que decidir UNA cosa, no combinar dos.
 *
 * Ahora hay un solo ajuste con tres respuestas:
 *
 *   cuenta  Cada estudiante entra con su usuario y su contrasena.
 *           Sirve en casa y en el salon. Es lo de siempre.
 *
 *   lista   Solo se entra por la direccion de la clase, tocando el
 *           nombre. Comodo en el salon, imposible desde casa.
 *
 *   ambos   Las dos cosas. En clase tocan su nombre; en casa entran con
 *           su usuario.
 *
 * ---------------------------------------------------------------------
 *  ABRIR Y CERRAR LA CLASE
 * ---------------------------------------------------------------------
 *
 * El usuario pidio que la direccion sirviera «para trabajar en el salon»
 * y que en casa hiciera falta la cuenta. **No hay forma fiable de saber
 * desde el servidor si quien abre la pagina esta en el aula o en su
 * casa.** Mirar la IP falla en cuanto el colegio usa datos moviles o el
 * nino esta en el salon con su propio telefono; mirar la hora falla con
 * la jornada de la tarde.
 *
 * Asi que en vez de adivinarlo, lo decide el docente: **abre la clase**
 * al empezar y se cierra sola a las horas que el diga. Mientras esta
 * abierta, la direccion funciona; cerrada, no abre nada. Es honesto y es
 * como piensa quien da la clase.
 *
 * `clase_abierta_hasta` en NULL significa «siempre abierta»: para quien
 * no quiera estar pendiente de abrir y cerrar.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 9 - Como entran los estudiantes\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

$pdo     = db();
$cambios = 0;

function hayColumna(string $tabla, string $columna): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
        [$tabla, $columna]
    );
}

$columnas = [
    ['courses', 'acceso_modo',
     "ALTER TABLE `courses` ADD COLUMN `acceso_modo`
      ENUM('cuenta','lista','ambos') NOT NULL DEFAULT 'cuenta'
      COMMENT 'Como entran: con su cuenta, por la lista de la clase, o las dos'
      AFTER `access_code`"],

    ['courses', 'clase_abierta_hasta',
     "ALTER TABLE `courses` ADD COLUMN `clase_abierta_hasta` DATETIME NULL
      COMMENT 'La lista solo abre hasta esta hora. NULL = siempre abierta'
      AFTER `acceso_modo`"],
];

echo "-- Columnas ----------------------------------------------------\n";

foreach ($columnas as [$tabla, $columna, $sql]) {
    if (hayColumna($tabla, $columna)) {
        echo "  [ok] $tabla.$columna ya existe.\n";
        continue;
    }

    echo "  [->] Se anade $tabla.$columna\n";

    if ($aplicar) {
        $pdo->exec($sql);
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  TRASPASO DESDE LA FASE 8
// =====================================================================

/*
 * Un curso que tenia el codigo encendido pasa a «ambos»: es lo que
 * significaba de hecho, porque el acceso normal nunca dejo de existir.
 * Los demas quedan en «cuenta», que es como estaban.
 */

echo "-- Traspaso de los cursos existentes ---------------------------\n";

if (hayColumna('courses', 'aula_activa') && hayColumna('courses', 'acceso_modo')) {

    $pendientes = (int) traerValor(
        "SELECT COUNT(*) FROM courses WHERE aula_activa = 1 AND acceso_modo = 'cuenta'"
    );

    if ($pendientes === 0) {
        echo "  [ok] Nada que traspasar.\n";
    } else {
        echo "  [->] $pendientes curso(s) con el codigo encendido pasan a «ambos».\n";

        if ($aplicar) {
            ejecutar("UPDATE courses SET acceso_modo = 'ambos'
                       WHERE aula_activa = 1 AND acceso_modo = 'cuenta'");
            $cambios++;
        }
    }
} else {
    echo "  [!] Falta alguna columna: corre antes migracion-aula.php\n";
}

echo "\n";


// =====================================================================
//  ESTADO
// =====================================================================

echo "-- Estado ------------------------------------------------------\n";

if (hayColumna('courses', 'acceso_modo')) {
    foreach (traerTodo('SELECT acceso_modo, COUNT(*) n FROM courses GROUP BY acceso_modo') as $r) {
        printf("  %-8s : %d curso(s)\n", $r['acceso_modo'], $r['n']);
    }
    printf("  Clases con horario de cierre : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM courses WHERE clase_abierta_hasta IS NOT NULL'));
}

printf("  Estudiantes con PIN          : %d\n",
    (int) traerValor('SELECT COUNT(*) FROM users WHERE pin_hash IS NOT NULL'));

echo "\n";

echo $aplicar
    ? "Listo. $cambios cambio(s) aplicado(s).\n\n"
      . "La eleccion esta en Escuela -> el curso -> Acceso.\n\n"
    : "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
