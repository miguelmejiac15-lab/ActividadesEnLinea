<?php
/**
 * migracion-organizacion-piloto.php — «Ds-Schule BQ» pasa a ser «Piloto»
 *
 *     php database/migracion-organizacion-piloto.php             # simulacion
 *     php database/migracion-organizacion-piloto.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  ESTO ES UN CAMBIO DE DATOS, NO DE CODIGO
 * ---------------------------------------------------------------------
 *
 * Y esa es la parte importante. «Piloto» no es un caso especial dentro
 * del sistema: es **la primera fila de la tabla `schools`**, igual que
 * seran la segunda y la tercera.
 *
 * En el codigo no hay —ni debe haber— ninguna condicion del tipo
 * «si el colegio es Piloto». Todo lo institucional pasa por
 * `includes/colegio.php`, que trabaja con el colegio que le den:
 * `colegioPorSlug()`, `colegioDeUsuario()`, `licenciaVigente()`. Anadir
 * una organizacion nueva es crear una fila desde Panel -> Colegios, sin
 * tocar una linea.
 *
 * Por eso esta migracion solo renombra. Si hiciera falta cambiar codigo
 * para cambiarle el nombre a una organizacion, la arquitectura estaria
 * mal.
 *
 * ---------------------------------------------------------------------
 *  EL SLUG CAMBIA, Y ESO SE NOTA
 * ---------------------------------------------------------------------
 *
 * `ds-schule-bq` pasa a `piloto`, asi que la direccion que se reparte
 * cambia de `/colegio/ds-schule-bq` a `/colegio/piloto`. La anterior deja
 * de funcionar: si ya se habia repartido, hay que avisar.
 *
 * El dominio de los usuarios generados (`@ds-schule-bq.local`) **NO se
 * toca**: es el identificador con el que ya entran las cuentas creadas, y
 * cambiarlo las dejaria a todas fuera. Las nuevas usaran el dominio que
 * se ponga a partir de ahora.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - La organizacion pasa a llamarse «Piloto»\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

if (!colegiosInstalados()) {
    echo "  [!] Falta la fase 10: php database/migracion-colegios.php --aplicar\n\n";
    exit(1);
}

$viejo = colegioPorSlug('ds-schule-bq');
$nuevo = colegioPorSlug('piloto');

if ($nuevo && !$viejo) {
    echo "  [ok] Ya existe «Piloto» (id " . $nuevo['id'] . "). Nada que hacer.\n\n";
    exit(0);
}

if (!$viejo) {
    echo "  [!] No hay ningun colegio con slug «ds-schule-bq».\n";
    echo "      Colegios actuales:\n";

    foreach (colegios() as $c) {
        printf("        %d · %-24s /colegio/%s\n", $c['id'], $c['name'], $c['slug']);
    }

    echo "\n      Si quieres crear «Piloto» desde cero: Panel -> Colegios.\n\n";
    exit(1);
}

if ($nuevo) {
    echo "  [!] Ya hay OTRO colegio con el slug «piloto» (id " . $nuevo['id'] . ").\n";
    echo "      No se toca nada: renombrar aqui chocaria con el.\n\n";
    exit(1);
}

$id = (int) $viejo['id'];

printf("  Colegio            : %s (id %d)\n", $viejo['name'], $id);
printf("  Direccion actual   : %s\n", urlDeColegio($viejo));
printf("  Direccion nueva    : %s\n", url('colegio/piloto'));
printf("  Dominio (NO cambia): %s\n", dominioDeColegio($viejo));
printf("  Docentes           : %d\n", count(miembrosDeColegio($id, 'teacher')));
printf("  Estudiantes        : %d\n", count(miembrosDeColegio($id, 'student')));
printf("  Cursos             : %d\n", count(cursosDeColegio($id)));

echo "\n";

if (!$aplicar) {
    echo "Simulacion. Vuelve a correrlo con --aplicar para escribir.\n\n";
    exit(0);
}

ejecutar('UPDATE schools SET name = ?, slug = ? WHERE id = ?', ['Piloto', 'piloto', $id]);

/*
 * La referencia de las suscripciones lleva el slug dentro
 * («colegio:ds-schule-bq»). Se actualiza para que el historial siga
 * cuadrando: es lo que se mira cuando alguien pregunta de donde salio el
 * acceso de una cuenta.
 */
$n = ejecutar(
    'UPDATE subscriptions SET payment_reference = ?
      WHERE school_id = ? AND payment_reference = ?',
    ['colegio:piloto', $id, 'colegio:ds-schule-bq']
);

printf("  [ok] Renombrado. %d suscripcion(es) con la referencia al dia.\n\n", $n);

echo "AVISO: la direccion cambio.\n";
printf("  Antes : %s\n", url('colegio/ds-schule-bq'));
printf("  Ahora : %s\n", url('colegio/piloto'));
echo "La anterior ya no abre nada. Si la habias repartido, avisa.\n\n";
