<?php
/**
 * ilustrar-estaciones.php — Dibujos para lo que el sembrador no alcanza
 *
 * Un niño de preescolar no lee. Un ejercicio sin dibujo, para él, es una
 * pantalla en blanco: puede oírlo, pero no tiene dónde mirar mientras
 * piensa. En una actividad de identificar imágenes, sin dibujo no hay
 * actividad.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ HACE FALTA ESTE SCRIPT Y NO BASTA CON SEMBRAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * `sembrar-contenido.php` ilustra todo lo que pasa por él. Pero una
 * parte grande del catálogo **no pasa**: las vocales, las letras y los
 * paquetes que crearon las migraciones viven en la base y no en ningún
 * archivo de contenido. Volver a sembrar no las toca.
 *
 * Este script las alcanza. Usa exactamente la misma función —`ilustrar()`
 * en `contenido/dibujos.php`— así que las dos vías dan el mismo
 * resultado y no hay dos criterios que se separen con el tiempo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  NO PISA NADA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Solo rellena lo que está vacío. Un dibujo puesto a mano gana siempre,
 * y correrlo dos veces no cambia nada la segunda: se puede repetir sin
 * miedo después de cada siembra.
 *
 *     php database/ilustrar-estaciones.php                 · solo mira
 *     php database/ilustrar-estaciones.php --aplicar
 *     php database/ilustrar-estaciones.php --nivel=preescolar --aplicar
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/dibujos.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$args    = $argv ?? [];
$aplicar = in_array('--aplicar', $args, true);

$nivel = '';
foreach ($args as $a) {
    if (str_starts_with((string) $a, '--nivel=')) {
        $nivel = trim(substr((string) $a, 8));
    }
}

$sql = 'SELECT s.id, s.game_type, s.config, a.slug, s.position, l.slug AS nivel
          FROM activity_stations s
          JOIN activities a ON a.id = s.activity_id
     LEFT JOIN levels     l ON l.id = a.level_id
         WHERE a.status = "published"
           AND s.game_type IN ("opcion_multiple", "desafio_final",
                               "ordenar_secuencia", "cuento")';
$params = [];

if ($nivel !== '') {
    $sql .= ' AND l.slug = ?';
    $params[] = $nivel;
}

$sql .= ' ORDER BY a.slug, s.position';

$filas = traerTodo($sql, $params);

echo "\n" . str_repeat('=', 74) . "\n";
echo "  DIBUJOS PARA LAS ESTACIONES\n";
echo str_repeat('=', 74) . "\n\n";

printf("  Estaciones que se revisan: %d%s\n\n", count($filas),
    $nivel !== '' ? " (nivel $nivel)" : '');

$tocadas  = 0;
$porNivel = [];
$muestra  = [];

foreach ($filas as $f) {
    $cfg = json_decode((string) $f['config'], true);

    if (!is_array($cfg) || !isset($cfg['datos'])) {
        continue;
    }

    $antes   = $cfg['datos'];
    $despues = ilustrar($antes, (string) $f['game_type']);

    /*
     * Se compara el JSON y no los arrays: `==` sobre arrays anidados con
     * claves en distinto orden puede decir que son iguales cuando no lo
     * son, y aquí interesa saber si el guardado va a cambiar de verdad.
     */
    if (json_encode($antes) === json_encode($despues)) {
        continue;
    }

    $tocadas++;
    $n = (string) ($f['nivel'] ?? '—');
    $porNivel[$n] = ($porNivel[$n] ?? 0) + 1;

    if (count($muestra) < 12) {
        $muestra[] = sprintf('%s · estación %d (%s)',
            $f['slug'], (int) $f['position'], $f['game_type']);
    }

    if ($aplicar) {
        $cfg['datos'] = $despues;

        ejecutar('UPDATE activity_stations SET config = ? WHERE id = ?',
                 [json_encode($cfg, JSON_UNESCAPED_UNICODE), (int) $f['id']]);
    }
}

printf("  Estaciones con dibujos nuevos: %d\n\n", $tocadas);

if ($porNivel) {
    echo "  Por nivel:\n";
    foreach ($porNivel as $n => $c) {
        printf("    %-20s %d\n", $n, $c);
    }
    echo "\n";
}

if ($muestra) {
    echo "  Algunas:\n";
    foreach ($muestra as $m) {
        echo "    · $m\n";
    }
    echo "\n";
}

echo $aplicar
    ? "  Aplicado.\n\n"
    : "  Simulación. Para escribir: php database/ilustrar-estaciones.php --aplicar\n\n";
