<?php
/**
 * migracion-aula.php — Fase 8 · Entrar por codigo de clase
 *
 *     php database/migracion-aula.php             # simulacion
 *     php database/migracion-aula.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  QUE RESUELVE
 * ---------------------------------------------------------------------
 *
 * Un nino de seis anos no teclea `anaperez4f2a@aula.local` ni una
 * contrasena. Con esto el docente escribe una direccion corta en el
 * tablero, el nino la abre, ve la lista de su clase con las caras de
 * todos, toca su nombre y entra.
 *
 * ---------------------------------------------------------------------
 *  LO QUE SE GANA Y LO QUE SE PAGA
 * ---------------------------------------------------------------------
 *
 * Se gana que treinta ninos de primero entren solos en un minuto, sin que
 * la profesora vaya mesa por mesa tecleando contrasenas.
 *
 * Se paga que **la lista de nombres de la clase queda detras de un codigo
 * y nada mas**. Quien tenga el codigo ve quienes son, y si el curso no
 * pide PIN, puede entrar como cualquiera de ellos.
 *
 * Por eso:
 *
 *   · El acceso por codigo esta APAGADO por defecto en cada curso. Se
 *     enciende a proposito, no por descuido.
 *   · El curso puede exigir un PIN de cuatro cifras. Lo decide el
 *     docente: en preescolar estorba, en tercero ya no.
 *   · El codigo se puede cambiar en cualquier momento, y cambiarlo
 *     invalida el anterior al instante.
 *   · Una sesion abierta asi NO puede pagar ni ver datos de facturacion.
 *     Un nino que toca su nombre no debe poder contratar nada.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 8 - Entrar por codigo de clase\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

$pdo     = db();
$cambios = 0;

/** ¿Existe la columna? */
function hayColumna(string $tabla, string $columna): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ? AND COLUMN_NAME = ?',
        [$tabla, $columna]
    );
}

$columnas = [
    ['courses', 'access_code',
     "ALTER TABLE `courses` ADD COLUMN `access_code` VARCHAR(12) NULL
      COMMENT 'Codigo corto para que los ninos entren sin teclear correo' AFTER `status`"],

    ['courses', 'aula_activa',
     "ALTER TABLE `courses` ADD COLUMN `aula_activa` TINYINT(1) NOT NULL DEFAULT 0
      COMMENT 'Apagado por defecto: encender expone la lista de la clase' AFTER `access_code`"],

    ['courses', 'aula_pin',
     "ALTER TABLE `courses` ADD COLUMN `aula_pin` TINYINT(1) NOT NULL DEFAULT 0
      COMMENT 'Si el curso exige PIN de 4 cifras al tocar un nombre' AFTER `aula_activa`"],

    ['users', 'pin_hash',
     "ALTER TABLE `users` ADD COLUMN `pin_hash` VARCHAR(255) NULL
      COMMENT 'PIN de aula, cifrado. Nunca se guarda en claro' AFTER `password`"],
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

// ── Indice unico del codigo ──────────────────────────────────────────
//
// UNIQUE y no solo KEY: dos cursos con el mismo codigo mandarian a los
// ninos de uno a la clase del otro.

echo "-- Indice del codigo -------------------------------------------\n";

$hayIndice = (bool) traerValor(
    "SELECT COUNT(*) FROM information_schema.STATISTICS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'courses'
        AND INDEX_NAME = 'uk_courses_codigo'"
);

if ($hayIndice) {
    echo "  [ok] Ya existe.\n";
} elseif (!hayColumna('courses', 'access_code')) {
    echo "  [!] Falta la columna: se creara en la proxima pasada.\n";
} else {
    echo "  [->] Se crea uk_courses_codigo (UNIQUE)\n";
    if ($aplicar) {
        $pdo->exec('ALTER TABLE `courses` ADD UNIQUE KEY `uk_courses_codigo` (`access_code`)');
        $cambios++;
    }
}

echo "\n";

// ── Estado ───────────────────────────────────────────────────────────

echo "-- Estado ------------------------------------------------------\n";

if (hayColumna('courses', 'access_code')) {
    printf("  Cursos                   : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM courses'));
    printf("  Con codigo generado      : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM courses WHERE access_code IS NOT NULL'));
    printf("  Con acceso de aula activo: %d\n",
        (int) traerValor('SELECT COUNT(*) FROM courses WHERE aula_activa = 1'));
}
if (hayColumna('users', 'pin_hash')) {
    printf("  Estudiantes con PIN      : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM users WHERE pin_hash IS NOT NULL'));
}

echo "\n";

echo $aplicar
    ? "Listo. $cambios cambio(s) aplicado(s).\n\n"
      . "El acceso por codigo queda APAGADO en todos los cursos. Se enciende\n"
      . "curso por curso desde Escuela -> el curso -> Estudiantes.\n\n"
    : "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
