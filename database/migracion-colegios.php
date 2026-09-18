<?php
/**
 * migracion-colegios.php — Fase 10 · Licencia de colegio
 *
 *     php database/migracion-colegios.php             # simulacion
 *     php database/migracion-colegios.php --aplicar   # escribe
 *     php database/migracion-colegios.php --aplicar --sembrar-ds
 *
 * ---------------------------------------------------------------------
 *  QUE ES UNA LICENCIA DE COLEGIO
 * ---------------------------------------------------------------------
 *
 * Un colegio contrata (o prueba) la plataforma y todos los suyos
 * —docentes y estudiantes— tienen acceso completo sin pagar uno a uno.
 *
 * **No se inventa un camino nuevo de permisos.** El acceso de toda la
 * plataforma pasa por `suscripcionVigente()`, asi que la licencia hace
 * exactamente lo mismo que un pago: crear una fila en `subscriptions`,
 * con `school_id` puesto y sin `expires_at` si la licencia es indefinida.
 *
 * Eso importa mas de lo que parece. La alternativa —comprobar «¿es de un
 * colegio?» en cada sitio donde hoy se mira la suscripcion— seria
 * duplicar la logica de acceso en veinte archivos, y el dia que se
 * olvidara uno, alguien veria contenido que no le toca. Asi, si la
 * suscripcion existe, TODO funciona: el catalogo, el area Escuela, «Mi
 * espacio», el 30%.
 *
 * ---------------------------------------------------------------------
 *  MODO PRUEBA
 * ---------------------------------------------------------------------
 *
 * `es_prueba` no cambia lo que se puede hacer: cambia lo que se ve. Un
 * colegio en prueba se marca como tal en el panel para que nadie lo
 * confunda con un cliente que paga, y `licencia_hasta` en NULL significa
 * «sin fecha de fin», que es justo lo que hace falta para probar sin
 * estar renovando cada mes.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar  = in_array('--aplicar', $argv ?? [], true);
$sembrar  = in_array('--sembrar-ds', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 10 - Licencia de colegio\n";
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
    ['schools', 'plan_id',
     "ALTER TABLE `schools` ADD COLUMN `plan_id` INT UNSIGNED NULL
      COMMENT 'Plan que concede la licencia a todos los miembros' AFTER `student_quota`"],

    ['schools', 'licencia_hasta',
     "ALTER TABLE `schools` ADD COLUMN `licencia_hasta` DATETIME NULL
      COMMENT 'NULL = indefinida' AFTER `plan_id`"],

    ['schools', 'es_prueba',
     "ALTER TABLE `schools` ADD COLUMN `es_prueba` TINYINT(1) NOT NULL DEFAULT 0
      COMMENT 'Colegio de prueba: no es un cliente que paga' AFTER `licencia_hasta`"],

    ['schools', 'dominio',
     "ALTER TABLE `schools` ADD COLUMN `dominio` VARCHAR(120) NULL
      COMMENT 'Dominio de los usuarios generados. NULL = aula.local' AFTER `es_prueba`"],

    ['schools', 'notas',
     "ALTER TABLE `schools` ADD COLUMN `notas` VARCHAR(400) NULL
      COMMENT 'Nota interna: quien lo trajo, que se acordo' AFTER `dominio`"],
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
//  SEMBRAR «Ds-Schule BQ»
// =====================================================================

if ($sembrar) {

    echo "-- Organizacion de prueba «Piloto» -----------------------------\n";

    $plan = traerUno("SELECT * FROM plans WHERE slug = 'escuela'")
         ?: traerUno("SELECT * FROM plans WHERE slug = 'biblioteca'");

    if (!$plan) {
        echo "  [!] No hay ningun plan con el que dar la licencia.\n\n";
        exit(1);
    }

    $existe = traerUno("SELECT * FROM schools WHERE slug = 'piloto'");

    if ($existe) {
        echo "  [ok] Ya existe (id " . $existe['id'] . "). No se toca.\n";
    } else {
        printf("  [->] Se crea con el plan «%s», licencia INDEFINIDA y en modo prueba.\n",
            $plan['name']);

        if ($aplicar) {
            $id = insertar(
                "INSERT INTO schools
                    (name, slug, city, student_quota, status,
                     plan_id, licencia_hasta, es_prueba, dominio, notas)
                 VALUES (?, ?, ?, 0, 'active', ?, NULL, 1, ?, ?)",
                [
                    'Piloto',
                    'piloto',
                    'Barranquilla',
                    (int) $plan['id'],
                    'piloto.local',
                    'Primera organizacion de prueba. Licencia indefinida, sin cobro.',
                ]
            );

            echo "  [ok] Creada con id $id.\n";
            printf("       Direccion: %s\n", url('colegio/piloto'));
            $cambios++;
        }
    }

    echo "\n";
}


// =====================================================================
//  ESTADO
// =====================================================================

echo "-- Estado ------------------------------------------------------\n";

printf("  Colegios            : %d\n", (int) traerValor('SELECT COUNT(*) FROM schools'));

if (hayColumna('schools', 'es_prueba')) {
    printf("  En modo prueba      : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM schools WHERE es_prueba = 1'));
}

printf("  Miembros vinculados : %d\n", (int) traerValor('SELECT COUNT(*) FROM school_users'));
printf("  Suscripciones de colegio: %d\n",
    (int) traerValor('SELECT COUNT(*) FROM subscriptions WHERE school_id IS NOT NULL'));

echo "\n";

if ($aplicar) {
    echo "Listo. $cambios cambio(s) aplicado(s).\n\n";

    if (!$sembrar) {
        echo "Para crear el colegio de prueba:\n";
        echo "  php database/migracion-colegios.php --aplicar --sembrar-ds\n\n";
    } else {
        echo "Siguiente paso: Panel -> Colegios -> Ds-Schule BQ,\n";
        echo "y desde ahi se crean los docentes y los estudiantes.\n\n";
    }
} else {
    echo "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
}
