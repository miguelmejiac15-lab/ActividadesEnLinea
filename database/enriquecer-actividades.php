<?php
/**
 * enriquecer-actividades.php — Más estaciones dentro de cada actividad
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EL PROBLEMA: SE ENTRA Y SE ACABA
 * ─────────────────────────────────────────────────────────────────────
 *
 * «Torre de Circuitos» tenía DOS estaciones. El niño entra, hace dos
 * minijuegos y se acabó la actividad. El promedio del catálogo era 4,6 y
 * el 92 % de las actividades no llegaba a seis.
 *
 * Las de letras sí: promedian 9,3. No porque estén mejor escritas, sino
 * porque hubo un `enriquecer-letras.php` que les generó estaciones a
 * partir de su propio contenido. Esto hace lo mismo para el resto.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE SE GENERA SALE DE LA PROPIA ACTIVIDAD
 * ─────────────────────────────────────────────────────────────────────
 *
 * No se inventa materia nueva. Se toman los ejercicios que la actividad
 * YA tiene y se vuelven a presentar con otra mecánica:
 *
 *   · REPASO       las preguntas de la actividad, como desafío final.
 *   · EMPAREJAR    los pares dibujo/palabra que ya usa.
 *   · MEMORIA      los dibujos que ya aparecen en ella.
 *   · SOPA         las palabras que ya nombra.
 *
 * Volver a ver lo mismo con otra mecánica no es relleno: es justo lo que
 * hace que algo se aprenda en vez de contestarse una vez. Pero hay que
 * decirlo con claridad — **esto es contenido derivado**, no escrito a
 * mano, y por eso cada estación generada lleva un título que lo dice.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  NO GENERA BASURA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Cada generador tiene un mínimo y si no lo alcanza NO produce nada. Una
 * memoria de tres cartas o una sopa con dos palabras son peores que no
 * tener la estación: el niño entra, ve que dura diez segundos y aprende
 * que estas estaciones no valen la pena.
 *
 * Y nunca se añade una mecánica que la actividad ya tenga: la gracia de
 * seis estaciones es que sean seis cosas distintas.
 *
 *     php database/enriquecer-actividades.php                 · simula
 *     php database/enriquecer-actividades.php --aplicar
 *     php database/enriquecer-actividades.php --minimo=8 --aplicar
 *     php database/enriquecer-actividades.php --actividad=torre-de-circuitos
 *
 * ─────────────────────────────────────────────────────────────────────
 *  SE VUELVE A CORRER DESPUÉS DE CADA SIEMBRA
 * ─────────────────────────────────────────────────────────────────────
 *
 * `sembrar-contenido.php` borra y reescribe las estaciones de lo que
 * viene de sus archivos, así que se lleva por delante lo generado. Es el
 * mismo caso que `ilustrar-estaciones.php`: sembrar, ilustrar,
 * enriquecer. Correrlo dos veces no duplica nada.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/ayudas.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$args    = $argv ?? [];
$aplicar = in_array('--aplicar', $args, true);
$rehacer = in_array('--rehacer', $args, true);

$minimo = 6;
$soloUna = '';

foreach ($args as $a) {
    if (str_starts_with((string) $a, '--minimo=')) {
        $minimo = max(2, min(16, (int) substr((string) $a, 9)));
    }
    if (str_starts_with((string) $a, '--actividad=')) {
        $soloUna = trim(substr((string) $a, 12));
    }
}

/** Marca que deja una estación generada, para reconocerla después. */
const MARCA_GENERADA = 'generada';


// =====================================================================
//  LEER LO QUE LA ACTIVIDAD YA TIENE
// =====================================================================

/**
 * Extrae de las estaciones todo lo reutilizable.
 *
 * @return array{preguntas:array, pares:array, dibujos:array, palabras:array}
 */
function cosecharDe(array $estaciones): array
{
    $preguntas = [];   // {q, opts, a, e}
    $pares     = [];   // {e, w}  — dibujo y su nombre, de verdad
    $dibujos   = [];   // emojis sueltos
    $palabras  = [];   // palabras limpias para la sopa

    /*
     * Lo que ya se preguntó en un desafío de la actividad.
     *
     * No es material: es lo contrario. Sirve para que un repaso nuevo no
     * vuelva a hacer una pregunta que el niño acaba de contestar.
     */
    $yaPreguntadas = [];

    $operaciones = [];   // {op, a, b} — para seguir practicando lo mismo
    $series      = [];   // {secuencia, falta}

    /** Un emoji utilizable: uno solo, y nada de iconos propios. */
    $emojiBueno = static function ($v): bool {
        $v = (string) $v;

        if ($v === '' || str_starts_with($v, 'icono:')) {
            return false;
        }

        // Ni letras ni números: tiene que ser un dibujo.
        return preg_match('/[\p{L}\p{N}]/u', $v) !== 1 && mb_strlen($v) <= 8;
    };

    /** Una palabra buena para la sopa de letras. */
    $palabraBuena = static function ($w): bool {
        $w = trim((string) $w);

        /*
         * Sin espacios, sin acentos y sin Ñ. El relleno de la sopa usa un
         * alfabeto sin esas letras, así que una palabra con tilde dejaría
         * su letra rara brillando en la rejilla: sería un chivatazo.
         */
        return preg_match('/^[A-Za-z]{4,9}$/', $w) === 1;
    };

    foreach ($estaciones as $e) {
        $cfg   = json_decode((string) $e['config'], true);
        $datos = $cfg['datos'] ?? null;
        $tipo  = (string) $e['game_type'];

        if (!is_array($datos)) {
            continue;
        }

        // ── Preguntas ────────────────────────────────────────────────
        if ($tipo === 'opcion_multiple') {
            foreach ($datos as $d) {
                if (!is_array($d) || !isset($d['enunciado'], $d['opciones'], $d['correcta'])) {
                    continue;
                }

                // Sin las que llevan un visual que no es un emoji: la
                // mezcla de colores y la lista de la compra no se pueden
                // volver a pintar dentro de un desafío.
                $v = $d['visual'] ?? null;
                if (is_array($v)) {
                    continue;
                }

                $preguntas[] = [
                    'q'    => (string) $d['enunciado'],
                    'opts' => array_values(array_map('strval', (array) $d['opciones'])),
                    'a'    => (int) $d['correcta'],
                    'e'    => $emojiBueno($v) ? (string) $v : null,
                ];
            }
        }

        /*
         * ── Lo que ya se preguntó ────────────────────────────────────
         *
         * Las preguntas de un desafío NO entran en `$preguntas`. Si
         * entraran, un repaso podría volver a plantear exactamente las
         * mismas: el niño las vería dos veces seguidas y la estación
         * nueva no le enseñaría nada.
         *
         * Se guardan aparte, solo para descartarlas, y sus dibujos sí se
         * aprovechan, que esos no se gastan.
         */
        if (in_array($tipo, ['desafio_final', 'cuento'], true)) {
            $lista = $datos['preguntas'] ?? (is_array($datos) ? $datos : []);

            foreach ($lista as $d) {
                if (is_array($d) && isset($d['q'])) {
                    $yaPreguntadas[] = normalizarPregunta((string) $d['q']);
                }
            }
        }

        // ── Memoria: la lista pelada de emojis ───────────────────────
        //
        // Sus datos son una lista de cadenas sueltas, sin clave, así que
        // el recorrido de más abajo —que busca `e`, `visual` e `img`— no
        // los veía. Son dibujos de la actividad como cualquier otro.
        if ($tipo === 'memoria') {
            foreach ($datos as $v) {
                if (is_string($v) && $emojiBueno($v)) {
                    $dibujos[] = $v;
                }
            }
        }

        // ── Completar texto: las palabras que faltan ─────────────────
        //
        // Los `huecos` son las respuestas correctas y son palabras
        // limpias del texto. Los `extra` son señuelos y no entran.
        if ($tipo === 'completar_texto') {
            foreach (($datos['items'] ?? []) as $it) {
                foreach ((array) ($it['huecos'] ?? []) as $w) {
                    if ($palabraBuena($w)) {
                        $palabras[] = mb_strtoupper(trim((string) $w));
                    }
                }
            }
        }

        // ── Matemáticas: lo que la actividad practica ────────────────
        if ($tipo === 'operacion') {
            foreach ($datos as $d) {
                if (is_array($d) && isset($d['op'], $d['a'])) {
                    $operaciones[] = ['op' => (string) $d['op'],
                                      'a'  => (int) $d['a'],
                                      'b'  => (int) ($d['b'] ?? 0)];
                }
            }
        }

        if ($tipo === 'secuencia_numerica') {
            foreach ($datos as $d) {
                if (is_array($d) && isset($d['secuencia'], $d['falta'])) {
                    $series[] = ['secuencia' => array_values((array) $d['secuencia']),
                                 'falta'     => (int) $d['falta']];
                }
            }
        }

        // ── Pares dibujo/palabra ─────────────────────────────────────
        //
        // Solo de donde SON un par de verdad: en «selecciona» y en
        // «rápido» cada elemento trae su dibujo y su nombre. Sacarlos de
        // una opción múltiple daría cosas como «🍓 ↔ Roja», que no es el
        // nombre de la fresa.
        if (in_array($tipo, ['seleccion_imagenes', 'juego_rapido', 'emparejar'], true)) {
            $lista = $datos['items'] ?? (is_array($datos) ? $datos : []);

            foreach ($lista as $it) {
                if (!is_array($it)) {
                    continue;
                }

                /*
                 * ─────────────────────────────────────────────────────
                 *  LOS DISTRACTORES NO SON NOMBRES
                 * ─────────────────────────────────────────────────────
                 *
                 * En «selecciona» y «rápido», cada elemento trae `ok`:
                 * si cumple o no cumple lo que se pide. Los que NO
                 * cumplen están ahí para descartarse, y su texto no
                 * tiene por qué describir el dibujo.
                 *
                 * Se vio en «carrera ortográfica», que usa faltas como
                 * distractores: el generador sacó «🐄 ↔ baca» y
                 * «🐝 ↔ aveja». Una estación que enseña que la vaca se
                 * escribe «baca» es mucho peor que una estación menos.
                 *
                 * Si el elemento no declara `ok`, es un par normal —así
                 * son los de «emparejar»— y vale.
                 */
                if (array_key_exists('ok', $it) && $it['ok'] !== true) {
                    continue;
                }

                $e2 = (string) ($it['e'] ?? '');
                $w  = trim((string) ($it['n'] ?? $it['w'] ?? ''));

                if ($emojiBueno($e2) && $w !== '' && mb_strlen($w) <= 16) {
                    $pares[] = ['e' => $e2, 'w' => $w];
                }
            }
        }

        // ── Dibujos y palabras, de donde sea ─────────────────────────
        array_walk_recursive($datos, static function ($v, $k) use (
            &$dibujos, &$palabras, $emojiBueno, $palabraBuena
        ): void {
            if (in_array($k, ['e', 'visual', 'img'], true) && $emojiBueno($v)) {
                $dibujos[] = (string) $v;
            }

            if (in_array($k, ['w', 'n'], true) && $palabraBuena($v)) {
                $palabras[] = mb_strtoupper(trim((string) $v));
            }
        });
    }

    // Únicos, conservando el orden en que aparecieron.
    $dibujos  = array_values(array_unique($dibujos));
    $palabras = array_values(array_unique($palabras));

    // Pares sin dibujo ni palabra repetidos: el motor une por la palabra
    // y dos iguales harían la unión ambigua.
    $vistosE = []; $vistosW = []; $limpios = [];
    foreach ($pares as $p) {
        if (in_array($p['e'], $vistosE, true) || in_array($p['w'], $vistosW, true)) {
            continue;
        }
        $vistosE[] = $p['e'];
        $vistosW[] = $p['w'];
        $limpios[] = $p;
    }

    return ['preguntas'   => $preguntas,
            'pares'       => $limpios,
            'dibujos'     => $dibujos,
            'palabras'    => $palabras,
            'operaciones' => $operaciones,
            'series'      => $series,
            'ya'          => array_values(array_unique($yaPreguntadas))];
}

/**
 * Una pregunta reducida a lo que la hace la misma pregunta.
 *
 * Sin tildes, sin signos y sin mayúsculas: así «¿Cuántos meses tienen 28
 * días?» y «Cuantos meses tienen 28 dias» se reconocen como una sola.
 */
function normalizarPregunta(string $q): string
{
    $q = mb_strtolower(trim($q));
    $q = strtr($q, ['á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o',
                    'ú' => 'u', 'ü' => 'u', 'ñ' => 'n']);

    return trim((string) preg_replace('/[^a-z0-9 ]/', '', $q));
}


// =====================================================================
//  LOS GENERADORES
// =====================================================================

/**
 * Repaso: las preguntas de la actividad, como desafío final.
 *
 * Se reparten por toda la actividad en vez de tomar las seis primeras:
 * las seis primeras son la primera estación entera, y repasar solo el
 * principio no repasa nada.
 */
function generarRepaso(array $cosecha, int $mitad = 0): ?array
{
    /*
     * Fuera las que un desafío de la actividad ya plantea.
     *
     * Sin esto, una actividad con seis ejercicios de opción múltiple y un
     * desafío que recoge esos mismos seis produciría un «repaso» que es
     * el desafío otra vez. Repetir la mecánica se puede defender —las
     * preguntas son otras—; repetir la pregunta, no.
     */
    $preguntas = array_values(array_filter(
        $cosecha['preguntas'],
        static fn(array $p): bool =>
            !in_array(normalizarPregunta($p['q']), $cosecha['ya'] ?? [], true)
    ));

    /*
     * Con muchas preguntas salen DOS estaciones, no una.
     *
     * No es repetir la misma: son preguntas distintas. Una actividad con
     * veinte ejercicios da de sobra para un repaso a mitad de camino y un
     * desafío al final, y esas dos cosas se sienten distintas aunque el
     * minijuego sea el mismo.
     *
     * `$mitad` es 0 para el repaso (primera mitad), 1 para el desafío
     * (segunda). Con pocas preguntas, solo hay repaso.
     */
    $hayDos = count($preguntas) >= 12;

    if (!$hayDos && $mitad === 1) {
        return null;
    }

    if ($hayDos) {
        $corte = (int) floor(count($preguntas) / 2);
        $preguntas = $mitad === 0
            ? array_slice($preguntas, 0, $corte)
            : array_slice($preguntas, $corte);
    }

    if (count($preguntas) < 5) {
        return null;
    }

    $cuantas = min(6, count($preguntas));
    $paso    = max(1, (int) floor(count($preguntas) / $cuantas));

    $elegidas = [];
    for ($i = 0; count($elegidas) < $cuantas && $i < count($preguntas); $i += $paso) {
        $p = $preguntas[$i];

        // Sin opciones repetidas: el motor compara por posición, pero un
        // desafío con dos botones iguales es imposible de entender.
        if (count($p['opts']) !== count(array_unique($p['opts']))) {
            continue;
        }

        $r = ['q' => $p['q'], 'opts' => $p['opts'], 'a' => $p['a']];
        if ($p['e'] !== null) { $r['e'] = $p['e']; }

        $elegidas[] = $r;
    }

    if (count($elegidas) < 4) {
        return null;
    }

    return $mitad === 0
        ? ['titulo'      => 'Repaso',
           'descripcion' => 'Lo que viste en esta actividad, todo junto',
           'icono'       => '🏆',
           'tipo'        => 'desafio_final',
           'datos'       => $elegidas]
        : ['titulo'      => 'Desafío final',
           'descripcion' => 'Las últimas, para cerrar',
           'icono'       => '🎯',
           'tipo'        => 'desafio_final',
           'datos'       => $elegidas,

           /*
            * El único que puede repetir mecánica. Se permite porque las
            * preguntas son OTRAS —la segunda mitad de la actividad— y
            * porque solo llega aquí cuando lo demás no alcanzó.
            */
           'repite'      => true];
}

/** Emparejar: los pares dibujo/palabra que la actividad ya usa. */
function generarEmparejar(array $cosecha): ?array
{
    $pares = array_slice($cosecha['pares'], 0, 6);

    if (count($pares) < 5) {
        return null;
    }

    return [
        'titulo'      => 'Une cada uno con su nombre',
        'descripcion' => 'Arrastra hasta encontrar la pareja',
        'icono'       => '🔗',
        'tipo'        => 'emparejar',
        'datos'       => $pares,
    ];
}

/** Memoria: los dibujos que ya aparecen en la actividad. */
function generarMemoria(array $cosecha): ?array
{
    $dibujos = array_slice($cosecha['dibujos'], 0, 6);

    if (count($dibujos) < 6) {
        return null;
    }

    return [
        'titulo'      => 'Memoria',
        'descripcion' => 'Encuentra las parejas',
        'icono'       => '🧠',
        'tipo'        => 'memoria',
        'datos'       => $dibujos,
    ];
}

/**
 * Crucigrama con dibujos por pista.
 *
 * Es el mejor de todos para los pequeños: la pista no es una definición
 * que haya que leer, es el dibujo. Se sabe qué va sin saber leer, y aun
 * así hay que escribir la palabra.
 *
 * `crucigrama()` lanza si no consigue cruzar al menos dos palabras, así
 * que se prueba y si no sale, no hay estación.
 */
function generarCrucigrama(array $cosecha): ?array
{
    $entradas = [];

    foreach ($cosecha['pares'] as $p) {
        $w = mb_strtoupper(trim((string) $p['w']));

        // Una sola palabra, sin tildes ni Ñ: la rejilla se llena con un
        // alfabeto que no las tiene y cantarían.
        if (preg_match('/^[A-Z]{3,9}$/', $w) !== 1) {
            continue;
        }

        $entradas[] = ['w' => $w, 'pista' => (string) $p['w'], 'e' => (string) $p['e']];

        if (count($entradas) >= 6) {
            break;
        }
    }

    if (count($entradas) < 3) {
        return null;
    }

    try {
        $datos = crucigrama($entradas);
    } catch (Throwable $e) {
        return null;   // no se cruzaron: no hay crucigrama
    }

    return [
        'titulo'      => 'Crucigrama de dibujos',
        'descripcion' => 'Escribe la palabra de cada dibujo',
        'icono'       => '🧩',
        'tipo'        => 'crucigrama',
        'datos'       => $datos,
    ];
}

/**
 * La otra cara de una mecánica que la actividad ya tiene.
 *
 * «Selecciona las imágenes» y «juego rápido» usan EXACTAMENTE los mismos
 * datos —dibujo, nombre y si cumple— y son dos experiencias distintas:
 * una es mirar con calma y tocar todas las que valen, la otra es decidir
 * sí o no deprisa, una por una.
 *
 * Convertir de una a la otra no inventa nada y de verdad se siente
 * distinto, que es lo que se busca al ampliar una actividad.
 */
function generarLaOtraCara(array $estaciones): ?array
{
    foreach ($estaciones as $e) {
        $tipo = (string) $e['game_type'];

        if (!in_array($tipo, ['seleccion_imagenes', 'juego_rapido'], true)) {
            continue;
        }

        $cfg   = json_decode((string) $e['config'], true);
        $datos = $cfg['datos'] ?? null;

        if (!is_array($datos)) {
            continue;
        }

        $items = $datos['items'] ?? (is_array($datos) ? $datos : []);

        // Con menos de seis no da para las dos versiones.
        if (count($items) < 6) {
            continue;
        }

        $esRapido = $tipo === 'juego_rapido';

        return [
            'titulo'      => $esRapido ? 'Míralas con calma' : 'Decide rápido',
            'descripcion' => $esRapido
                ? 'Ahora sin prisa: toca todas las que cumplen'
                : 'Las mismas, pero una a una y sin pensarlo mucho',
            'icono'       => $esRapido ? '👀' : '⚡',
            'tipo'        => $esRapido ? 'seleccion_imagenes' : 'juego_rapido',
            'datos'       => [
                't'     => (string) ($datos['t'] ?? $e['title']),
                's'     => $esRapido ? 'Tómate tu tiempo' : 'Sin pensarlo mucho',
                'items' => $items,
            ],
        ];
    }

    return null;
}

/**
 * Más operaciones, del mismo tipo y del mismo tamaño.
 *
 * En matemáticas «más material» no significa material nuevo: significa
 * más ejercicios. Un valle de sumas con cuatro estaciones no necesita que
 * le inventemos un tema, necesita más sumas del mismo rango — que es
 * exactamente lo que se practica.
 *
 * Los números se recorren en un orden fijo, no al azar: así dos pasadas
 * del script dan lo mismo y una estación no cambia bajo los pies de un
 * niño que la dejó a medias.
 *
 * `$tanda` 0 son operaciones del mismo tamaño; 1 estira el rango para que
 * la segunda cueste un poco más.
 */
function generarOperaciones(array $cosecha, int $tanda = 0): ?array
{
    $ops = $cosecha['operaciones'];

    if (count($ops) < 3) {
        return null;
    }

    // Qué se practica y hasta dónde llegan los números.
    $tipos = array_values(array_unique(array_column($ops, 'op')));
    $tope  = 0;
    $piso  = PHP_INT_MAX;

    foreach ($ops as $o) {
        $tope = max($tope, $o['a'], $o['b']);
        $piso = min($piso, max(1, $o['a']));
    }

    if ($tope < 2) {
        return null;
    }

    // Las que ya están, para no repetir ninguna.
    $vistas = [];
    foreach ($ops as $o) {
        $vistas[] = $o['op'] . ':' . $o['a'] . ':' . $o['b'];
    }

    $desde = $tanda === 1 ? $tope + 1 : $piso;
    $hasta = $tanda === 1 ? (int) round($tope * 1.6) + 2 : $tope;

    $nuevas = [];

    /** Guarda una si no estaba ya. */
    $anotar = static function (string $op, int $a, int $b) use (&$nuevas, &$vistas): void {
        $clave = "$op:$a:$b";

        if (in_array($clave, $vistas, true)) {
            return;
        }

        $vistas[] = $clave;

        $nuevas[] = ['op' => $op, 'a' => $a, 'b' => $b,
                     'resultado' => match ($op) {
                         'suma'           => $a + $b,
                         'resta'          => $a - $b,
                         'multiplicacion' => $a * $b,
                         default          => $a,   // contar
                     }];
    };

    foreach ($tipos as $op) {

        /*
         * «Contar» va aparte porque solo usa `a`: no hay segundo
         * operando que recorrer.
         *
         * Meterlo en el bucle de dos variables costó un cuelgue — al
         * saltarse un duplicado, `b` volvía a cero y se repetía el mismo
         * ejercicio para siempre.
         */
        if ($op === 'contar') {
            for ($a = $desde; $a <= $hasta && count($nuevas) < 6; $a++) {
                $anotar('contar', $a, 0);
            }
            continue;
        }

        for ($a = $hasta; $a >= $desde && count($nuevas) < 6; $a--) {
            for ($b = 1; $b <= $hasta && count($nuevas) < 6; $b++) {

                // Una resta nunca puede dar negativo: a esta edad no
                // existen los números bajo cero.
                if ($op === 'resta' && $b >= $a) {
                    continue;
                }

                // Una multiplicación por 1 o por 0 no enseña la tabla.
                if ($op === 'multiplicacion' && ($b < 2 || $a < 2)) {
                    continue;
                }

                if (!in_array($op, ['suma', 'resta', 'multiplicacion'], true)) {
                    break 2;   // una operación que no sabemos generar
                }

                $anotar($op, $a, $b);
            }
        }
    }

    if (count($nuevas) < 5) {
        return null;
    }

    $nuevas = array_slice($nuevas, 0, 6);

    return $tanda === 0
        ? ['titulo'      => 'Más ejercicios',
           'descripcion' => 'Otros seis para practicar lo mismo',
           'icono'       => '🔢',
           'tipo'        => 'operacion',
           'datos'       => $nuevas,
           'repite'      => true]
        : ['titulo'      => 'Ahora con números más grandes',
           'descripcion' => 'Lo mismo, pero sube un escalón',
           'icono'       => '📈',
           'tipo'        => 'operacion',
           'datos'       => $nuevas,
           'repite'      => true];
}

/**
 * Más series numéricas, con el mismo paso.
 *
 * Se respeta el paso que la actividad practica —de uno en uno, de dos en
 * dos, de diez en diez— porque ese es el contenido. Lo que cambia es por
 * dónde empieza la serie y dónde está el hueco.
 */
function generarSeries(array $cosecha, int $tanda = 0): ?array
{
    $series = $cosecha['series'];

    if (count($series) < 3) {
        return null;
    }

    $pasos = [];
    $tope  = 0;

    foreach ($series as $s) {
        $nums = array_values(array_filter($s['secuencia'], static fn($v) => $v !== null));

        foreach ($nums as $n) {
            $tope = max($tope, (int) $n);
        }

        // El paso se lee de dos números seguidos y conocidos.
        $seq = $s['secuencia'];
        for ($i = 0; $i < count($seq) - 1; $i++) {
            if ($seq[$i] !== null && $seq[$i + 1] !== null) {
                $pasos[] = (int) $seq[$i + 1] - (int) $seq[$i];
                break;
            }
        }
    }

    $pasos = array_values(array_filter($pasos, static fn(int $p): bool => $p !== 0));

    if (!$pasos || $tope < 4) {
        return null;
    }

    // El paso más frecuente: si la actividad mezcla, manda el que domina.
    $cuenta = array_count_values($pasos);
    arsort($cuenta);
    $paso = (int) array_key_first($cuenta);

    $largo = $tanda === 1 ? 6 : 5;
    $base  = $tanda === 1 ? (int) round($tope * 1.4) : max(abs($paso) * 2, 3);

    // Las que ya están, por su primer número y el hueco.
    $vistas = [];
    foreach ($series as $s) {
        $vistas[] = ($s['secuencia'][0] ?? '?') . ':' . $s['falta'];
    }

    $nuevas = [];
    $salto  = max(1, abs($paso) * 3);

    for ($k = 0; count($nuevas) < 6 && $k < 40; $k++) {
        $inicio = $base + $k * $salto;
        $hueco  = 1 + ($k % ($largo - 2));   // nunca el primero ni el último

        $seq   = [];
        $falta = 0;

        for ($i = 0; $i < $largo; $i++) {
            $v = $inicio + $i * $paso;

            if ($v < 0) { continue 2; }   // serie que se va bajo cero

            if ($i === $hueco) { $falta = $v; $seq[] = null; }
            else               { $seq[] = $v; }
        }

        if (in_array($seq[0] . ':' . $falta, $vistas, true)) {
            continue;
        }

        $vistas[]  = $seq[0] . ':' . $falta;
        $nuevas[]  = ['secuencia' => $seq, 'falta' => $falta];
    }

    if (count($nuevas) < 5) {
        return null;
    }

    return $tanda === 0
        ? ['titulo'      => 'Más series',
           'descripcion' => 'Encuentra el número que falta',
           'icono'       => '🔟',
           'tipo'        => 'secuencia_numerica',
           'datos'       => $nuevas,
           'repite'      => true]
        : ['titulo'      => 'Series más altas',
           'descripcion' => 'Las mismas series, con números mayores',
           'icono'       => '📊',
           'tipo'        => 'secuencia_numerica',
           'datos'       => $nuevas,
           'repite'      => true];
}

/**
 * Un repaso aunque la actividad ya tenga un desafío.
 *
 * Va el último de todos y solo entra si lo demás no llegó al mínimo. Se
 * defiende igual que el segundo desafío: `generarRepaso()` ya descarta
 * toda pregunta que el desafío existente plantea, así que estas son
 * necesariamente otras.
 */
function generarRepasoExtra(array $cosecha): ?array
{
    $est = generarRepaso($cosecha, 0);

    if ($est === null) {
        return null;
    }

    $est['titulo']      = 'Ponte a prueba';
    $est['descripcion'] = 'Otras preguntas de esta actividad';
    $est['icono']       = '❓';
    $est['repite']      = true;

    return $est;
}

/** Sopa de letras con las palabras que la actividad nombra. */
function generarSopa(array $cosecha): ?array
{
    $palabras = array_slice($cosecha['palabras'], 0, 6);

    if (count($palabras) < 5) {
        return null;
    }

    try {
        $datos = sopa($palabras);
    } catch (Throwable $e) {
        return null;
    }

    // Si la rejilla no pudo colocarlas casi todas, no sirve.
    if (count($datos['words'] ?? []) < 4) {
        return null;
    }

    return [
        'titulo'      => 'Sopa de palabras',
        'descripcion' => 'Busca las palabras de esta actividad',
        'icono'       => '🔤',
        'tipo'        => 'sopa_letras',
        'datos'       => $datos,
    ];
}


// =====================================================================
//  RECORRIDO
// =====================================================================

/*
 * ── Deshacer lo generado ─────────────────────────────────────────────
 *
 * Hace falta cuando cambia un generador: lo que ya está escrito se hizo
 * con las reglas viejas y no se corrige solo. Solo borra las estaciones
 * marcadas como generadas — lo escrito a mano no se toca nunca.
 */
if ($rehacer) {
    $marcadas = traerTodo(
        'SELECT s.id, s.activity_id FROM activity_stations s
           WHERE s.config LIKE \'%"origen":"' . MARCA_GENERADA . '"%\''
    );

    printf("\n  Estaciones generadas que se van a rehacer: %d\n", count($marcadas));

    if ($aplicar) {
        foreach ($marcadas as $m) {
            ejecutar('DELETE FROM activity_stations WHERE id = ?', [(int) $m['id']]);
        }

        /*
         * Y se renumeran las que quedan. Sin esto quedarían huecos en
         * `position`, y el orden del mapa de estaciones se apoya en ella.
         */
        foreach (array_unique(array_column($marcadas, 'activity_id')) as $actId) {
            $quedan = traerTodo(
                'SELECT id FROM activity_stations WHERE activity_id = ? ORDER BY position',
                [(int) $actId]);

            $n = 0;
            foreach ($quedan as $q) {
                $n++;
                ejecutar('UPDATE activity_stations SET position = ? WHERE id = ?',
                         [$n, (int) $q['id']]);
            }

            ejecutar('UPDATE activities SET free_stations = ? WHERE id = ?',
                     [max(1, (int) round($n * 0.34)), (int) $actId]);
        }

        echo "  Borradas y renumeradas.\n";
    } else {
        echo "  (simulación: no se borró nada)\n";
    }
}

$sql = 'SELECT a.id, a.slug, a.title, a.free_stations, c.slug AS cat,
               (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS est
          FROM activities a
     LEFT JOIN categories c ON c.id = a.category_id
         WHERE a.status = "published"';
$params = [];

if ($soloUna !== '') {
    $sql .= ' AND a.slug = ?';
    $params[] = $soloUna;
}

$sql .= ' HAVING est < ? ORDER BY est, a.slug';
$params[] = $minimo;

$actividades = traerTodo($sql, $params);

echo "\n" . str_repeat('=', 74) . "\n";
echo "  MÁS ESTACIONES EN CADA ACTIVIDAD\n";
echo str_repeat('=', 74) . "\n\n";

printf("  Mínimo por actividad : %d estaciones\n", $minimo);
printf("  Actividades por debajo: %d\n\n", count($actividades));

/*
 * El orden es el orden en que se ofrecen, así que es el orden de
 * preferencia. Primero lo que más aporta —el repaso cierra la actividad,
 * el crucigrama de dibujos es el que más les gusta— y al final lo que
 * solo reordena material que ya estaba.
 */
$generadores = [
    'repaso'     => static fn(array $c, array $est) => generarRepaso($c, 0),
    'crucigrama' => static fn(array $c, array $est) => generarCrucigrama($c),
    'emparejar'  => static fn(array $c, array $est) => generarEmparejar($c),
    'memoria'    => static fn(array $c, array $est) => generarMemoria($c),
    'sopa'       => static fn(array $c, array $est) => generarSopa($c),
    'otra-cara'  => static fn(array $c, array $est) => generarLaOtraCara($est),

    /*
     * ─────────────────────────────────────────────────────────────────
     *  A PARTIR DE AQUÍ SE REPITE MECÁNICA
     * ─────────────────────────────────────────────────────────────────
     *
     * Todo lo de abajo lleva `repite`, así que solo entra cuando las seis
     * mecánicas distintas de arriba no bastaron. Preferimos seis cosas
     * distintas; pero seis estaciones con más ejercicios son mejores que
     * una actividad de cuatro.
     *
     * En matemáticas esto no es un parche, es lo correcto: «más
     * contenido» en un valle de sumas significa más sumas.
     */
    'mates'      => static fn(array $c, array $est) => generarOperaciones($c, 0),
    'series'     => static fn(array $c, array $est) => generarSeries($c, 0),
    'mates-2'    => static fn(array $c, array $est) => generarOperaciones($c, 1),
    'series-2'   => static fn(array $c, array $est) => generarSeries($c, 1),

    /*
     * El desafío final va el ÚLTIMO de la lista a propósito: solo entra
     * si todo lo demás no bastó para llegar al mínimo. Preferimos seis
     * mecánicas distintas antes que dos tandas de preguntas, aunque las
     * preguntas sean otras.
     */
    'desafio'      => static fn(array $c, array $est) => generarRepaso($c, 1),
    'repaso-extra' => static fn(array $c, array $est) => generarRepasoExtra($c),
];

$nuevas   = 0;
$tocadas  = 0;
$porTipo  = [];
$sinNada  = [];

foreach ($actividades as $a) {

    $estaciones = traerTodo(
        'SELECT position, title, game_type, config FROM activity_stations
          WHERE activity_id = ? ORDER BY position', [(int) $a['id']]);

    $yaTiene  = array_column($estaciones, 'game_type');
    $titulos  = array_map('strval', array_column($estaciones, 'title'));
    $cosecha  = cosecharDe($estaciones);

    $faltan = $minimo - (int) $a['est'];
    $puestas = [];

    foreach ($generadores as $nombre => $fn) {
        if (count($puestas) >= $faltan) {
            break;
        }

        $est = $fn($cosecha, $estaciones);

        if ($est === null) {
            continue;
        }

        /*
         * Nunca una mecánica que ya tenga: la gracia de seis estaciones
         * es que sean seis cosas distintas, no la misma seis veces.
         *
         * La única excepción la declara el propio generador con
         * `repite`, y solo el desafío final la usa: sus preguntas son
         * otras y va el último, así que únicamente entra cuando todo lo
         * demás no dio para llegar al mínimo.
         */
        if (empty($est['repite']) && in_array($est['tipo'], $yaTiene, true)) {
            continue;
        }

        /*
         * Y nunca una estación que ya se generó.
         *
         * Sin esto el script NO sería idempotente: el desafío final lleva
         * `repite`, así que se saltaba la comprobación de mecánica y se
         * volvía a añadir en cada pasada. Correrlo tres veces después de
         * tres siembras habría dejado tres desafíos iguales.
         */
        if (in_array($est['titulo'], $titulos, true)) {
            continue;
        }

        $puestas[] = $est;
        $yaTiene[] = $est['tipo'];
        $titulos[] = $est['titulo'];
        $porTipo[$nombre] = ($porTipo[$nombre] ?? 0) + 1;
    }

    if (!$puestas) {
        $sinNada[] = sprintf('%s (%d estaciones, %d preguntas, %d dibujos, %d palabras)',
            $a['slug'], (int) $a['est'], count($cosecha['preguntas']),
            count($cosecha['dibujos']), count($cosecha['palabras']));
        continue;
    }

    $tocadas++;
    $nuevas += count($puestas);

    if ($aplicar) {
        $posicion = (int) $a['est'];

        foreach ($puestas as $e) {
            $posicion++;

            insertar(
                'INSERT INTO activity_stations
                    (activity_id, position, title, description, icon, game_type, config, is_free)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                [(int) $a['id'], $posicion, $e['titulo'], $e['descripcion'],
                 $e['icono'], $e['tipo'],
                 json_encode(['datos' => $e['datos'], 'origen' => MARCA_GENERADA],
                             JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
            );
        }

        /*
         * El modelo del 30 %.
         *
         * `free_stations` dice cuántas de las primeras son gratuitas. Al
         * añadir estaciones, dejarlo como estaba encogería la prueba
         * gratis de una de cada cuatro a una de cada ocho — un cambio de
         * precio encubierto que nadie decidió.
         */
        $totalAhora = $posicion;
        $libres     = max(1, (int) round($totalAhora * 0.34));

        ejecutar('UPDATE activities SET free_stations = ? WHERE id = ?',
                 [$libres, (int) $a['id']]);
    }
}

printf("  Actividades ampliadas : %d\n", $tocadas);
printf("  Estaciones nuevas     : %d\n\n", $nuevas);

if ($porTipo) {
    echo "  De cada tipo:\n";
    foreach ($porTipo as $t => $n) {
        printf("    %-12s %d\n", $t, $n);
    }
    echo "\n";
}

if ($sinNada) {
    printf("  Sin material para generar nada: %d\n", count($sinNada));
    foreach (array_slice($sinNada, 0, 12) as $s) {
        echo "    · $s\n";
    }
    if (count($sinNada) > 12) {
        printf("    … y %d más\n", count($sinNada) - 12);
    }
    echo "\n";
}

if ($aplicar) {
    $t = (int) traerValor('SELECT COUNT(*) FROM activities WHERE status = "published"');
    $s = (int) traerValor(
        'SELECT COUNT(*) FROM activity_stations s
           JOIN activities a ON a.id = s.activity_id WHERE a.status = "published"');

    printf("  Catálogo: %d actividades · %d estaciones · promedio %.1f\n\n",
        $t, $s, $t ? $s / $t : 0);

    echo "  Aplicado. Pasa ahora el validador:\n";
    echo "    php database/validar-estaciones.php\n\n";
} else {
    echo "  Simulación. Para escribir:\n";
    echo "    php database/enriquecer-actividades.php --aplicar\n\n";
}
