<?php
/**
 * arreglar-secuencias.php — Secuencias con fichas repetidas
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ UNA FICHA REPETIDA ROMPE EL EJERCICIO
 * ─────────────────────────────────────────────────────────────────────
 *
 * En «ordenar la secuencia» el niño ve las fichas barajadas y las toca
 * en orden. Si dos fichas son idénticas, una de las dos es «la
 * siguiente» y la otra no — pero se ven exactamente igual. El niño toca
 * una, el juego le dice que va más adelante, toca la otra y acierta. No
 * ha aprendido nada: ha acertado por eliminación entre dos cosas que
 * para él eran la misma.
 *
 * Lo encontró el validador al ilustrar las secuencias, y llevaba ahí
 * desde la migración de las letras.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ HACE
 * ─────────────────────────────────────────────────────────────────────
 *
 * Quita la ficha repetida. Una secuencia de tres pasos se juega igual de
 * bien que una de cuatro, y es lo único que se puede hacer sin inventar
 * contenido: nadie sabe qué escena quiso poner quien la escribió.
 *
 * Las correcciones de CONTENIDO —cuando además de repetida la secuencia
 * dice algo falso— van escritas una a una más abajo, con su motivo.
 *
 *     php database/arreglar-secuencias.php            · solo mira
 *     php database/arreglar-secuencias.php --aplicar
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

/**
 * Secuencias que además de repetidas decían algo que no es verdad.
 *
 * Quitar la ficha repetida las dejaría jugables y falsas, así que estas
 * se reescriben enteras. Cada una lleva por qué.
 */
const CORREGIDAS = [

    /*
     * «Ordena el ciclo del Koala»: 🥚 🍼 🐨 🐨.
     *
     * Dos koalas iguales, y encima un huevo — el koala es un marsupial y
     * no pone huevos. La cría nace diminuta, termina de crecer en la
     * bolsa de la madre y después se sube al árbol. Eso sí se puede
     * contar, y con cuatro dibujos distintos.
     */
    'letra-k' => [
        'title' => 'Ordena el día del Koala 🐨',
        'items' => [
            ['w' => 'Duerme en el árbol', 'e' => '😴'],
            ['w' => 'Despierta',          'e' => '☀️'],
            ['w' => 'Come hojas',         'e' => '🌿'],
            ['w' => 'Trepa más alto',     'e' => '🌳'],
        ],
    ],
];

echo "\n" . str_repeat('=', 74) . "\n";
echo "  SECUENCIAS CON FICHAS REPETIDAS\n";
echo str_repeat('=', 74) . "\n\n";

$filas = traerTodo(
    'SELECT s.id, s.position, s.config, a.slug, a.title
       FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
      WHERE s.game_type = "ordenar_secuencia" AND a.status = "published"
   ORDER BY a.slug, s.position'
);

/** La palabra con la que se compara un paso. */
function clavePaso($p): string
{
    if (is_array($p)) {
        $w = trim((string) ($p['w'] ?? ''));
        return $w !== '' ? $w : trim((string) ($p['e'] ?? ''));
    }

    return trim((string) $p);
}

$tocadas = 0;

foreach ($filas as $f) {
    $cfg   = json_decode((string) $f['config'], true);
    $items = $cfg['datos']['items'] ?? [];

    if (count($items) < 2) {
        continue;
    }

    $claves = array_map('clavePaso', $items);

    if (count($claves) === count(array_unique($claves))) {
        continue;
    }

    $tocadas++;
    printf("  %s · estación %d\n", $f['slug'], (int) $f['position']);
    printf("      antes : %s\n", implode(' · ', $claves));

    if (isset(CORREGIDAS[$f['slug']])) {
        $cfg['datos'] = CORREGIDAS[$f['slug']];
        printf("      ahora : %s   (reescrita: el contenido era falso)\n",
            implode(' · ', array_map('clavePaso', $cfg['datos']['items'])));
    } else {
        // Se quita la repetida conservando el orden de la primera.
        $vistas = [];
        $limpios = [];

        foreach ($items as $it) {
            $c = clavePaso($it);
            if (in_array($c, $vistas, true)) { continue; }
            $vistas[]  = $c;
            $limpios[] = $it;
        }

        $cfg['datos']['items'] = array_values($limpios);
        printf("      ahora : %s\n", implode(' · ', $vistas));
    }

    if ($aplicar) {
        ejecutar('UPDATE activity_stations SET config = ? WHERE id = ?',
                 [json_encode($cfg, JSON_UNESCAPED_UNICODE), (int) $f['id']]);
    }

    echo "\n";
}

printf("  Secuencias con fichas repetidas: %d\n\n", $tocadas);

echo $aplicar
    ? "  Aplicado.\n\n"
    : "  Simulación. Para escribir: php database/arreglar-secuencias.php --aplicar\n\n";
