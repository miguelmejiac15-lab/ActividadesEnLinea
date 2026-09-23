<?php
/**
 * migracion-actividad-de-hoy.php — La actividad con la que se empieza
 *
 *     php database/migracion-actividad-de-hoy.php             # simulacion
 *     php database/migracion-actividad-de-hoy.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  EL PROBLEMA QUE RESUELVE UNA SOLA COLUMNA
 * ---------------------------------------------------------------------
 *
 * El aula ya existe: el docente abre la clase, escribe la direccion en
 * el tablero y los ninos entran tocando su nombre. Hasta ahi bien.
 *
 * Lo que fallaba es lo siguiente. Al entrar, el nino llega a su espacio
 * y todavia tiene que encontrar la actividad del dia entre las que le
 * asignaron. Con treinta ninos de primero eso son treinta preguntas
 * seguidas —«profe, cual es»— justo en el minuto en que el docente
 * necesita que empiecen solos.
 *
 * `actividad_hoy` guarda con que se empieza HOY. Cuando esta puesta, el
 * enlace del aula no lleva al espacio del nino: lleva directo a jugar
 * esa actividad. El nino abre el enlace, toca su nombre y ya esta
 * trabajando.
 *
 * Es NULL por defecto: un curso que no la use se comporta exactamente
 * como hasta ahora.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - La actividad de hoy\n";
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

if (!(bool) traerValor(
    'SELECT COUNT(*) FROM information_schema.TABLES
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "courses"'
)) {
    exit("  [!] No existe la tabla `courses`. Aplica antes migracion-escuela.php\n");
}

$cambios = 0;

echo "-- Columnas ---------------------------------------------------\n";

if (hayColumna('courses', 'actividad_hoy')) {
    echo "  [ok] courses.actividad_hoy ya existe.\n";
} else {
    echo "  [->] Se anade courses.actividad_hoy\n";

    if ($aplicar) {
        /*
         * Sin clave foranea a proposito.
         *
         * Si la hubiera con ON DELETE RESTRICT, despublicar una
         * actividad fallaria porque un curso la tiene puesta como la de
         * hoy — un dato de un dia bloqueando el catalogo. Y con CASCADE,
         * borrar la actividad borraria... nada util.
         *
         * La consulta que la lee ya hace JOIN con `activities`, asi que
         * un id que apunte a algo que ya no existe simplemente no
         * devuelve fila: el aula se comporta como si no hubiera
         * actividad de hoy, que es exactamente lo correcto.
         */
        ejecutar('ALTER TABLE `courses`
                  ADD COLUMN `actividad_hoy` INT UNSIGNED NULL
                  COMMENT "Actividad con la que se entra hoy; NULL = al espacio del nino"
                  AFTER `aula_activa`');
        $cambios++;
    }
}

echo "\n-- Estado -----------------------------------------------------\n";

if (hayColumna('courses', 'actividad_hoy')) {
    printf("  Cursos con actividad de hoy puesta : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM courses WHERE actividad_hoy IS NOT NULL'));
}

printf("  Cursos que entran por lista        : %d\n",
    (int) traerValor("SELECT COUNT(*) FROM courses WHERE acceso_modo IN ('lista','ambos')"));

echo "\n";

if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
} else {
    echo "Listo. $cambios cambio(s) aplicado(s).\n";
    echo "\nSe elige en Escuela -> el curso -> Como entran -> La actividad de hoy.\n";
}
