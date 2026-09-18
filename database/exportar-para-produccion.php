<?php
/**
 * exportar-para-produccion.php — El volcado que sí se puede subir
 *
 *     php database/exportar-para-produccion.php
 *     php database/exportar-para-produccion.php --salida=C:\ruta\ael.sql
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO SE USA `mysqldump` A SECAS
 * ─────────────────────────────────────────────────────────────────────
 *
 * Porque un volcado completo de esta base lleva tres cosas que no deben
 * salir de la máquina de desarrollo:
 *
 *  1. **26 usuarios de prueba**, con sus correos, sus contraseñas
 *     hasheadas y su progreso. Parte son cuentas de menores creadas para
 *     ensayar el módulo de escuela. Subirlas a producción es publicar
 *     datos personales de prueba en un sistema real, y el Decreto 0769
 *     de 2026 no distingue entre datos «de prueba» y datos de verdad
 *     cuando son de un menor identificable.
 *
 *  2. **Las credenciales de Mercado Pago**, que están en `settings`. En
 *     producción llegan por variables de entorno —`MP_ACCESS_TOKEN`,
 *     `MP_WEBHOOK_SECRETO`— y `mpConfig()` ya las prefiere sobre la base.
 *     Dejarlas en el volcado las convierte en un archivo .sql dando
 *     vueltas por el disco y por donde se suba.
 *
 *  3. **Cobros y suscripciones de ensayo**: cuatro pagos pendientes y
 *     quince suscripciones concedidas a mano. En producción confunden la
 *     contabilidad desde el primer día.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ SÍ SALE
 * ─────────────────────────────────────────────────────────────────────
 *
 * El catálogo, que es lo que no se puede reconstruir: 489 actividades y
 * 3.123 estaciones, buena parte migradas del sitio anterior desde
 * archivos que **no están en el repositorio** (`legacy/` se quedó vacío).
 * Volver a sembrar en el servidor daría un catálogo distinto y más pobre.
 *
 * De todas las tablas sale la ESTRUCTURA. De las de uso, solo eso.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

/**
 * Tablas cuyos DATOS se exportan.
 *
 * Es una lista blanca y no una negra a propósito: con una lista de
 * exclusiones, una tabla nueva —digamos `tutores`— se exportaría por
 * omisión, y el descuido se descubre cuando los datos ya están fuera. Así
 * el descuido cae del lado seguro: una tabla nueva sale vacía y se nota.
 */
const CONTENIDO = [
    'categories',
    'levels',
    'collections',
    'activities',
    'activity_stations',
    'tags',
    'activity_tags',
    'plans',
    'shop_items',
    'settings',          // se limpia más abajo
    'child_mode_activities',
];

/**
 * Ajustes que NO viajan, aunque `settings` sí lo haga.
 *
 * Las credenciales de la pasarela y todo lo que se derive de ellas. En
 * producción las pone el entorno; una copia vieja aquí solo puede
 * contradecirlo.
 *
 * `mp_cuenta_*` son notas sobre a quién pertenece el token: si el token
 * cambia, la nota miente, y `mpCuentaConocida()` decide con ella si se
 * está en pruebas. Una nota equivocada ahí puede hacer que un cobro real
 * se trate como de prueba.
 */
const AJUSTES_QUE_NO_VIAJAN = [
    'mp_access_token',
    'mp_public_key',
    'mp_webhook_secreto',
    'pagos_webhook_secreto',
    'mp_cuenta_apodo',
    'mp_cuenta_pais',
    'mp_cuenta_prueba',
    'mp_cuenta_visto',
    'mp_cuenta_huella',
    'mp_usuario_prueba',
];

// ── Argumentos ───────────────────────────────────────────────────────
$salida = '';

foreach ($argv as $a) {
    if (str_starts_with((string) $a, '--salida=')) {
        $salida = trim(substr((string) $a, 9));
    }
}

if ($salida === '') {
    $salida = dirname(__DIR__) . '/almacen/ael-produccion-' . date('Ymd-His') . '.sql';
}

$todas = array_map(
    static fn(array $f): string => (string) array_values($f)[0],
    traerTodo('SHOW TABLES')
);

echo "==========================================================\n";
echo "  VOLCADO PARA PRODUCCIÓN\n";
echo "==========================================================\n\n";

$sql  = "-- Actividades en Línea · volcado para producción\n";
$sql .= '-- Generado ' . date('Y-m-d H:i:s') . "\n";
$sql .= "--\n";
$sql .= "-- Contiene el catálogo. NO contiene usuarios, progreso, cobros,\n";
$sql .= "-- suscripciones ni credenciales de pasarela.\n\n";

$sql .= "SET NAMES utf8mb4;\n";
$sql .= "SET FOREIGN_KEY_CHECKS = 0;\n";
$sql .= "SET SQL_MODE = 'NO_AUTO_VALUE_ON_ZERO';\n\n";

$conDatos = 0;
$soloEstructura = 0;

foreach ($todas as $tabla) {

    // ── Estructura, siempre ──────────────────────────────────────────
    $crear = traerUno('SHOW CREATE TABLE `' . $tabla . '`');
    $crear = $crear['Create Table'] ?? null;

    if ($crear === null) {
        continue;
    }

    $sql .= "\n-- ─────────────────────────────────────────────────────\n";
    $sql .= "-- $tabla\n";
    $sql .= "-- ─────────────────────────────────────────────────────\n";

    /*
     * ─────────────────────────────────────────────────────────────────
     *  DOS TRATOS DISTINTOS, Y LA DIFERENCIA IMPORTA
     * ─────────────────────────────────────────────────────────────────
     *
     * Las tablas de CATÁLOGO se reemplazan: se borran y se vuelven a
     * crear. Es lo que se quiere — el volcado trae la versión buena y
     * debe pisar lo que hubiera.
     *
     * Las de USO, no. Y no es un matiz: la primera versión de esto hacía
     * `DROP TABLE` en todas, así que importar el catálogo **borraba la
     * cuenta de administrador** y cualquier usuario, progreso o cobro que
     * hubiera en producción. El volcado se anunciaba como «traer el
     * catálogo» y de paso vaciaba el sitio.
     *
     * Con `IF NOT EXISTS` y sin DROP, estas tablas se crean si faltan y
     * se dejan intactas si ya están. Importar dos veces es inofensivo.
     */
    $esCatalogo = in_array($tabla, CONTENIDO, true);

    if ($esCatalogo) {
        $sql .= "DROP TABLE IF EXISTS `$tabla`;\n";
        $sql .= $crear . ";\n\n";
    } else {
        // `CREATE TABLE` → `CREATE TABLE IF NOT EXISTS`, para no tocar
        // una tabla que ya tenga datos en el destino.
        $sql .= preg_replace(
            '/^CREATE TABLE /',
            'CREATE TABLE IF NOT EXISTS ',
            $crear
        ) . ";\n\n";

        $sql .= "-- Sin datos y sin DROP: tabla de uso. Si ya existe en el\n";
        $sql .= "-- destino, se queda como está (usuarios, progreso, cobros).\n";

        $soloEstructura++;
        printf("  %-26s estructura, se respeta lo que haya\n", $tabla);
        continue;
    }

    // ── Datos ────────────────────────────────────────────────────────
    $filas = traerTodo('SELECT * FROM `' . $tabla . '`');

    if ($tabla === 'settings') {
        $antes = count($filas);
        $filas = array_values(array_filter(
            $filas,
            static fn(array $f): bool => !in_array((string) ($f['key'] ?? ''), AJUSTES_QUE_NO_VIAJAN, true)
        ));
        printf("  %-26s %d filas (se omiten %d credenciales)\n",
            $tabla, count($filas), $antes - count($filas));
    } else {
        printf("  %-26s %d filas\n", $tabla, count($filas));
    }

    $conDatos++;

    if (!$filas) {
        continue;
    }

    $columnas = '`' . implode('`, `', array_keys($filas[0])) . '`';

    /*
     * En lotes y no una sentencia por fila: `activity_stations` tiene
     * 3.123 filas con la configuración de cada minijuego dentro, y un
     * INSERT por fila produce un archivo que MySQL tarda minutos en
     * tragar. En lotes de 100 son segundos.
     */
    foreach (array_chunk($filas, 100) as $lote) {
        $valores = [];

        foreach ($lote as $fila) {
            $celdas = array_map(static function ($v): string {
                if ($v === null) {
                    return 'NULL';
                }
                if (is_int($v) || is_float($v)) {
                    return (string) $v;
                }
                if (is_bool($v)) {
                    return $v ? '1' : '0';
                }

                // Se escapa con el propio driver: hacerlo a mano con
                // str_replace deja fuera casos —\0, \Z, el juego de
                // caracteres— y basta uno para corromper el volcado.
                return db()->quote((string) $v);
            }, $fila);

            $valores[] = '(' . implode(', ', $celdas) . ')';
        }

        $sql .= "INSERT INTO `$tabla` ($columnas) VALUES\n"
              . implode(",\n", $valores) . ";\n";
    }
}

$sql .= "\nSET FOREIGN_KEY_CHECKS = 1;\n";

if (!is_dir(dirname($salida))) {
    @mkdir(dirname($salida), 0755, true);
}

if (file_put_contents($salida, $sql) === false) {
    exit("\n✗ No se pudo escribir en $salida\n");
}

printf(
    "\n  %d tablas con datos · %d solo estructura\n",
    $conDatos,
    $soloEstructura
);
printf("  %s (%s)\n", $salida, number_format(filesize($salida) / 1048576, 2) . ' MB');

echo "\n──────────────────────────────────────────────────────────\n";
echo "  CÓMO IMPORTARLO\n";
echo "──────────────────────────────────────────────────────────\n\n";
echo "  En Coolify → el servicio de MariaDB → Terminal, o por SSH:\n\n";
echo "    mysql -u <usuario> -p <base> < ael-produccion-….sql\n\n";
echo "  Después, crear la cuenta de administrador:\n\n";
echo "    php database/crear-admin.php --correo=… --nombre=…\n\n";
echo "  Las credenciales de Mercado Pago NO están aquí: van en las\n";
echo "  variables de entorno de Coolify (MP_ACCESS_TOKEN,\n";
echo "  MP_PUBLIC_KEY, MP_WEBHOOK_SECRETO).\n\n";
