<?php
/**
 * revisar-catalogo.php — Revisión de salud del catálogo
 *
 *     php database/revisar-catalogo.php
 *
 * `validar-estaciones.php` responde una pregunta: ¿se puede terminar cada
 * estación? Este script responde la otra, que no cubría nadie: ¿está el
 * catálogo entero en condiciones de mostrarse?
 *
 * Hacía falta porque el fallo ya ocurrió. «Juegos de Vocales 1» y «2»
 * estaban PUBLICADAS —con ficha, ícono y descripción— y sin una sola
 * estación: la migración las dejó a medias y nada lo dijo durante meses.
 * Un niño entraba desde el catálogo y encontraba «todavía no está lista».
 * Las estaciones que tenían validaban perfectamente, porque no tenían
 * ninguna.
 *
 * Todas las comprobaciones son de lectura: este script no escribe nada.
 * Devuelve 1 si encuentra algo, para poder encadenarlo tras una siembra.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$detalle = in_array('--detalle', $argv ?? [], true);

/*
 * Cada comprobación es una consulta que NO debería devolver nada. Se
 * escriben así —buscando lo roto, no contando lo sano— porque una lista
 * vacía es una respuesta inequívoca: no hay que interpretar un número ni
 * compararlo con lo que había ayer.
 *
 * El texto es lo que se verá en pantalla, así que dice qué está mal, no
 * qué se consultó.
 */
$comprobaciones = [

    // ── Lo que rompe la experiencia del niño ─────────────────────────
    'Actividades publicadas sin ninguna estación' =>
        'SELECT a.slug AS x FROM activities a
      LEFT JOIN activity_stations s ON s.activity_id = a.id
          WHERE a.status = "published"
       GROUP BY a.id HAVING COUNT(s.id) = 0',

    'Actividades publicadas que aún usan el motor antiguo' =>
        'SELECT CONCAT(slug, " → ", engine) AS x FROM activities
          WHERE status = "published" AND engine <> "estaciones"',

    'Actividades publicadas que apuntan a un HTML del sitio anterior' =>
        'SELECT CONCAT(slug, " → ", legacy_file) AS x FROM activities
          WHERE status = "published" AND legacy_file IS NOT NULL AND legacy_file <> ""',

    'Estaciones sin datos de minijuego' =>
        'SELECT CONCAT(a.slug, " · estación ", s.position) AS x
           FROM activity_stations s JOIN activities a ON a.id = s.activity_id
          WHERE s.config IS NULL OR s.config = "" OR s.config = "null"',

    // ── Lo que rompe el catálogo ─────────────────────────────────────
    'Actividades publicadas sin categoría' =>
        'SELECT slug AS x FROM activities WHERE status = "published" AND category_id IS NULL',

    'Actividades publicadas sin nivel' =>
        'SELECT slug AS x FROM activities WHERE status = "published" AND level_id IS NULL',

    // Sin ícono la tarjeta cae al de su materia, así que no es fatal,
    // pero doce tarjetas con el mismo dibujo dejan de distinguirse.
    'Actividades publicadas sin ícono propio' =>
        'SELECT slug AS x FROM activities
          WHERE status = "published" AND (icon IS NULL OR icon = "")',

    'Slugs repetidos' =>
        'SELECT CONCAT(slug, " × ", COUNT(*)) AS x FROM activities
       GROUP BY slug HAVING COUNT(*) > 1',

    /*
     * Dos actividades del mismo bloque con el mismo ícono se ven una al
     * lado de la otra en el catálogo y dejan de distinguirse. BINARY es
     * obligatorio: con utf8mb4_unicode_ci casi todos los emojis se
     * consideran iguales entre sí y un GROUP BY normal informaría de que
     * las 154 actividades comparten ícono.
     */
    'Íconos repetidos dentro de un mismo bloque' =>
        'SELECT CONCAT(col.slug, " · ", a.icon, " × ", COUNT(*)) AS x
           FROM activities a JOIN collections col ON col.id = a.collection_id
          WHERE a.status = "published" AND a.icon <> ""
       GROUP BY a.collection_id, BINARY a.icon HAVING COUNT(*) > 1',

    // ── Lo que rompe el modelo 30% ───────────────────────────────────
    'Actividades donde free_stations promete más estaciones de las que hay' =>
        // free_stations va dentro de MIN(): es constante para el grupo,
        // pero MariaDB no acepta una columna suelta en el HAVING.
        'SELECT CONCAT(a.slug, ": ", MIN(a.free_stations), " libres de ", COUNT(s.id)) AS x
           FROM activities a JOIN activity_stations s ON s.activity_id = a.id
          WHERE a.status = "published"
       GROUP BY a.id HAVING MIN(a.free_stations) > COUNT(s.id)',

    // El usuario Free debe poder entrar a todas: una actividad «partial»
    // sin ninguna estación abierta es premium disfrazada de prueba.
    'Actividades parciales sin ninguna estación gratuita' =>
        'SELECT slug AS x FROM activities a
          WHERE a.status = "published" AND a.access_type = "partial" AND a.free_stations = 0
            AND NOT EXISTS (SELECT 1 FROM activity_stations s
                             WHERE s.activity_id = a.id AND s.is_free = 1)',

    // ── Lo que rompe la promesa del menú ─────────────────────────────
    'Categorías activas con menos de diez actividades' =>
        'SELECT CONCAT(c.slug, ": ", COUNT(a.id)) AS x
           FROM categories c
      LEFT JOIN activities a ON a.category_id = c.id AND a.status = "published"
          WHERE c.is_active = 1 GROUP BY c.id HAVING COUNT(a.id) < 10',

    'Bloques activos sin ninguna actividad' =>
        'SELECT col.slug AS x FROM collections col
      LEFT JOIN activities a ON a.collection_id = col.id AND a.status = "published"
          WHERE col.is_active = 1 GROUP BY col.id HAVING COUNT(a.id) = 0',

    // Sin etiquetas la actividad solo aparece buscando su materia exacta.
    'Actividades publicadas sin ninguna etiqueta' =>
        'SELECT a.slug AS x FROM activities a
      LEFT JOIN activity_tags t ON t.activity_id = a.id
          WHERE a.status = "published" GROUP BY a.id HAVING COUNT(t.tag_id) = 0',

    /*
     * Estaciones que PROMETEN un texto y no lo muestran.
     *
     * «Ciudad de los Cuentos» decía «Lee el primer cuento y responde
     * preguntas» en una estación de opción múltiple, y preguntaba quién
     * despertó al león sin haber contado nunca la fábula. Un niño que no
     * la conociera de antes solo podía adivinar —una de cada cuatro
     * veces— y el juego le decía que se equivocaba por algo que jamás le
     * enseñó.
     *
     * Es el peor tipo de error del catálogo porque no falla: la actividad
     * carga, se juega y se termina. Solo está mal para quien la usa.
     *
     * Únicamente `cuento` y `completar_texto` muestran un texto antes de
     * preguntar. Si la descripción o el título prometen leer uno y el
     * motor es otro, el texto no existe.
     *
     * La regla es deliberadamente estrecha —«lee EL CUENTO», no «lee
     * bien»— porque medio catálogo dice «lee con calma» refiriéndose al
     * propio enunciado, y una comprobación que grita por eso se ignora a
     * la semana.
     */
    'Estaciones que prometen un texto que no muestran' =>
        'SELECT CONCAT(a.slug, " · estación ", s.position, " (", s.game_type, "): ",
                       s.description) AS x
           FROM activity_stations s
           JOIN activities a ON a.id = s.activity_id
          WHERE a.status = "published"
            AND s.game_type NOT IN ("cuento", "completar_texto")
            AND (s.description REGEXP "[Ll]ee (el|la|los|las) (cuento|fábula|fabula|historia|texto|poema|lectura|relato|leyenda)"
              OR s.description REGEXP "[Ll]ectura (del|de la) (cuento|fábula|fabula|historia|texto|poema)"
              OR s.title       REGEXP "[Ll]ee (el|la) (cuento|fábula|fabula|historia|texto|poema)")
       ORDER BY a.id, s.position',

    /*
     * ─────────────────────────────────────────────────────────────────
     *  EN PREESCOLAR, TODO SE OYE
     * ─────────────────────────────────────────────────────────────────
     *
     * Un niño de cinco años no lee. El motor le lee en voz alta la
     * instrucción de cada estación —`titular()` lo hace para los
     * veintiún minijuegos— y las opciones de las preguntas. Eso convierte
     * el largo del enunciado en un problema de diseño y no de estética:
     * una frase de noventa caracteres leída en voz alta dura ocho
     * segundos, y para cuando termina el niño ya no se acuerda de cómo
     * empezaba.
     *
     * Ochenta caracteres es el corte: una frase que un adulto dice de un
     * tirón. Por encima, hay que partirla en dos o apoyarla en el dibujo.
     *
     * Solo mira preescolar. En tercero el texto se lee con los ojos y se
     * relee las veces que haga falta; ahí un enunciado largo es normal.
     */
    /*
     * Va en PHP y no en SQL porque los enunciados viven dentro del JSON
     * de `config` y **MariaDB no tiene `JSON_TABLE`** para desplegarlos en
     * filas. Sacarlos con LIKE sobre el JSON crudo daría un resultado que
     * parece una comprobación y no lo es.
     */
    'Preguntas de preescolar demasiado largas para leerlas en voz alta' =>
        static function (): array {
            // Ochenta caracteres: una frase que un adulto dice de un tirón.
            $corte = 80;

            $filas = traerTodo(
                'SELECT a.slug, s.position, s.config
                   FROM activity_stations s
                   JOIN activities a ON a.id = s.activity_id
                   JOIN levels     l ON l.id = a.level_id
                  WHERE a.status = "published" AND l.slug = "preescolar"
               ORDER BY a.slug, s.position'
            );

            $malas = [];

            foreach ($filas as $f) {
                $cfg   = json_decode((string) $f['config'], true);
                $datos = $cfg['datos'] ?? null;

                if (!is_array($datos)) {
                    continue;
                }

                foreach ($datos as $d) {
                    if (!is_array($d)) {
                        continue;
                    }

                    // `enunciado` en opción múltiple, `q` en el desafío.
                    foreach (['enunciado', 'q'] as $campo) {
                        $t = trim((string) ($d[$campo] ?? ''));
                        $n = mb_strlen($t);

                        if ($n > $corte) {
                            $malas[] = ['x' => sprintf('%s · estación %d (%d): %s',
                                $f['slug'], (int) $f['position'], $n, mb_substr($t, 0, 70))];
                        }
                    }
                }
            }

            return $malas;
        },

    /*
     * ─────────────────────────────────────────────────────────────────
     *  EN PREESCOLAR, TODO SE VE
     * ─────────────────────────────────────────────────────────────────
     *
     * Un niño de cinco años no lee. Un ejercicio sin dibujo, para él, es
     * una pantalla en blanco: puede oírlo, pero no tiene dónde mirar
     * mientras piensa. En una actividad de identificar imágenes, sin
     * dibujo no hay actividad.
     *
     * Se mira por ACTIVIDAD y con un umbral, no ejercicio a ejercicio.
     * Quedan preguntas que no hablan de ninguna cosa —«¿qué hago si me
     * equivoco?»— y ahí el dibujo honesto es el de la tarea, que el
     * diccionario pone cuando reconoce el patrón. Exigir el 100 %
     * empujaría a ponerle un adorno cualquiera al resto, y un dibujo que
     * no viene a cuento es peor que ninguno: un niño que está
     * aprendiendo no lo corrige, lo memoriza.
     *
     * Lo que sí es un fallo es una actividad entera casi sin dibujos.
     */
    'Actividades de preescolar donde falta el dibujo en más de la mitad' =>
        static function (): array {
            $filas = traerTodo(
                'SELECT a.slug, s.game_type, s.config
                   FROM activity_stations s
                   JOIN activities a ON a.id = s.activity_id
                   JOIN levels     l ON l.id = a.level_id
                  WHERE a.status = "published" AND l.slug = "preescolar"
                    AND s.game_type IN ("opcion_multiple", "desafio_final")
               ORDER BY a.slug'
            );

            $cuenta = [];

            foreach ($filas as $f) {
                $cfg   = json_decode((string) $f['config'], true);
                $datos = $cfg['datos'] ?? null;

                if (!is_array($datos)) {
                    continue;
                }

                $slug = (string) $f['slug'];

                foreach ($datos as $d) {
                    if (!is_array($d)) {
                        continue;
                    }

                    $cuenta[$slug]['total'] = ($cuenta[$slug]['total'] ?? 0) + 1;

                    /*
                     * `visual` no siempre es texto: en las mezclas de
                     * color es `['a' => …, 'b' => …]` y en la lista de la
                     * compra es una lista de productos. Los dos SÍ se
                     * ven, así que cuentan — y convertirlos a cadena con
                     * `(string)` soltaba un aviso de PHP por cada uno.
                     */
                    $v = $d['visual'] ?? null;

                    $tiene = (string) $f['game_type'] === 'opcion_multiple'
                        ? (is_array($v) ? $v !== [] : trim((string) $v) !== '')
                        : trim((string) ($d['e'] ?? '')) !== '';

                    if (!$tiene) {
                        $cuenta[$slug]['sin'] = ($cuenta[$slug]['sin'] ?? 0) + 1;
                    }
                }
            }

            $malas = [];

            foreach ($cuenta as $slug => $c) {
                $total = (int) ($c['total'] ?? 0);
                $sin   = (int) ($c['sin'] ?? 0);

                if ($total >= 4 && $sin * 2 > $total) {
                    $malas[] = ['x' => sprintf('%s: %d de %d ejercicios sin dibujo',
                        $slug, $sin, $total)];
                }
            }

            return $malas;
        },
];


// =====================================================================
//  RECORRIDO
// =====================================================================

echo str_repeat('=', 74), "\n";
echo "  REVISIÓN DEL CATÁLOGO\n";
echo str_repeat('=', 74), "\n\n";

$conHallazgos = 0;

foreach ($comprobaciones as $titulo => $sql) {
    /*
     * Casi todas son una consulta que no debería devolver nada. Alguna es
     * una función, para lo que SQL no alcanza: los enunciados viven
     * dentro de un JSON y MariaDB no sabe desplegarlo en filas.
     */
    $filas = is_callable($sql) ? $sql() : traerTodo($sql);

    if (!$filas) {
        printf("  ✓ %s\n", $titulo);
        continue;
    }

    $conHallazgos++;
    printf("  ✗ %s — %d\n", $titulo, count($filas));

    // Sin --detalle se listan cinco: lo bastante para reconocer el
    // problema, no tanto como para tapar las demás comprobaciones.
    $muestra = $detalle ? $filas : array_slice($filas, 0, 5);

    foreach ($muestra as $f) {
        echo '        · ', $f['x'], "\n";
    }

    if (!$detalle && count($filas) > count($muestra)) {
        printf("        … y %d más (usa --detalle)\n", count($filas) - count($muestra));
    }
}


// =====================================================================
//  CIFRAS
// =====================================================================

$publicadas = (int) traerValor('SELECT COUNT(*) FROM activities WHERE status = "published"');
$estaciones = (int) traerValor(
    'SELECT COUNT(*) FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id WHERE a.status = "published"'
);

// La misma regla que aplica includes/acceso.php: libre si la estación lo
// dice a mano, o si está entre las primeras N de su actividad.
$gratuitas = (int) traerValor(
    'SELECT COUNT(*) FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
      WHERE a.status = "published"
        AND (s.is_free = 1 OR a.access_type = "free" OR s.position <= a.free_stations)'
);

echo "\n", str_repeat('-', 74), "\n";
printf("  Actividades publicadas : %d\n", $publicadas);
printf("  Estaciones             : %d\n", $estaciones);
printf("  Gratuitas              : %d (%.1f%%)\n",
       $gratuitas, $estaciones ? $gratuitas / $estaciones * 100 : 0);
printf("  Categorías activas     : %d\n",
       (int) traerValor('SELECT COUNT(*) FROM categories WHERE is_active = 1'));
printf("  Bloques activos        : %d\n",
       (int) traerValor('SELECT COUNT(*) FROM collections WHERE is_active = 1'));

echo "\n", $conHallazgos === 0
    ? "  ✅ El catálogo está en condiciones de mostrarse.\n"
    : "  $conHallazgos comprobación(es) con hallazgos.\n";

echo str_repeat('=', 74), "\n";

exit($conHallazgos === 0 ? 0 : 1);
