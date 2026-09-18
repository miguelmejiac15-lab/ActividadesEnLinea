<?php
/**
 * validar-estaciones.php — Comprueba que cada estación pueda jugarse
 *
 *     php database/validar-estaciones.php
 *     php database/validar-estaciones.php --detalle
 *
 * Cada minijuego de motor.js espera sus datos con una forma concreta. Si
 * una estación no la cumple, el error no aparece aquí: aparece cuando un
 * niño la abre y la pantalla se queda en blanco, o —peor— cuando ninguna
 * de las respuestas es la correcta y él cree que se equivoca.
 *
 * Este script recorre TODAS las estaciones de la base de datos y verifica
 * lo que un jugador necesita para poder terminarlas:
 *
 *   · que los datos tengan la forma que el motor lee;
 *   · que exista al menos una respuesta correcta;
 *   · que la respuesta correcta esté realmente entre las opciones;
 *   · que no haya dos opciones idénticas siendo una la correcta
 *     (el niño acertaría tocando la equivocada y el juego diría que no);
 *   · que las palabras de una sopa de letras estén de verdad en la
 *     cuadrícula, en las casillas que dice.
 *
 * Sale con código 1 si encuentra algo, para poder encadenarlo.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$detalle = in_array('--detalle', $argv ?? [], true);

/** Problemas encontrados, agrupados por actividad. */
$fallos = [];
$revisadas = 0;
$porTipo = [];

/** Registra un fallo. */
function mal(array $e, string $mensaje): void
{
    global $fallos;
    $fallos[] = [
        'actividad' => $e['actividad'],
        'estacion'  => $e['position'] . '. ' . $e['title'],
        'tipo'      => $e['game_type'],
        'problema'  => $mensaje,
    ];
}

/** Los ejercicios, ya sea lista pelada o `{t, s, items}`. */
function items($datos): array
{
    if (is_array($datos) && isset($datos['items']) && is_array($datos['items'])) {
        return $datos['items'];
    }
    return is_array($datos) ? $datos : [];
}

/** Comprueba una lista de {e, n, ok}: debe haber al menos una correcta. */
function revisarSiNo(array $e, array $lista): void
{
    if (!$lista) {
        mal($e, 'no tiene ejercicios');
        return;
    }

    $correctas = 0;
    foreach ($lista as $i => $x) {
        if (!isset($x['n'])) {
            mal($e, "ejercicio $i sin nombre (n)");
        }
        if (!empty($x['ok'])) {
            $correctas++;
        }
    }

    if ($correctas === 0) {
        mal($e, 'ninguna opción es correcta: la estación no se puede terminar');
    }
}

/** Comprueba {q|enunciado, opts|opciones, a|correcta}. */
function revisarOpciones(array $e, array $lista, string $campoPregunta, string $campoOpciones, string $campoRespuesta): void
{
    if (!$lista) {
        mal($e, 'no tiene ejercicios');
        return;
    }

    foreach ($lista as $i => $x) {
        $opciones = $x[$campoOpciones] ?? null;

        if (!is_array($opciones) || count($opciones) < 2) {
            mal($e, "ejercicio $i: hacen falta al menos dos opciones");
            continue;
        }

        $indice = $x[$campoRespuesta] ?? null;

        if (!is_int($indice) || !array_key_exists($indice, array_values($opciones))) {
            mal($e, "ejercicio $i: la respuesta correcta apunta fuera de las opciones");
            continue;
        }

        $valores  = array_values($opciones);
        $correcta = $valores[$indice];

        // Dos opciones idénticas donde una es la correcta: el niño toca
        // la que se ve igual, el motor compara por posición y le dice
        // que se equivocó. Es el error más injusto posible.
        $repetida = 0;
        foreach ($valores as $v) {
            if ($v === $correcta) {
                $repetida++;
            }
        }
        if ($repetida > 1) {
            mal($e, "ejercicio $i: la respuesta «" . (string) $correcta . "» aparece repetida entre las opciones");
        }

        if (($x[$campoPregunta] ?? '') === '') {
            mal($e, "ejercicio $i: sin enunciado");
        }
    }
}

/** Comprueba que cada palabra de la sopa esté donde dice estar. */
function revisarSopa(array $e, $datos): void
{
    $grid  = $datos['grid']  ?? null;
    $words = $datos['words'] ?? null;

    if (!is_array($grid) || !$grid || !is_array($words)) {
        mal($e, 'la sopa no tiene cuadrícula o lista de palabras');
        return;
    }

    if (!$words) {
        mal($e, 'la sopa no tiene ninguna palabra colocada');
        return;
    }

    foreach ($words as $p) {
        $palabra  = $p['w'] ?? '';
        $casillas = $p['cells'] ?? [];

        if (!is_array($casillas) || !$casillas) {
            mal($e, "«{$palabra}» no tiene casillas");
            continue;
        }

        $letras = preg_split('//u', $palabra, -1, PREG_SPLIT_NO_EMPTY);

        if (count($letras) !== count($casillas)) {
            mal($e, "«{$palabra}» tiene " . count($letras) . ' letras pero ' . count($casillas) . ' casillas');
            continue;
        }

        $leido = '';
        foreach ($casillas as [$r, $c]) {
            if (!isset($grid[$r][$c])) {
                mal($e, "«{$palabra}» apunta a una casilla fuera de la cuadrícula");
                continue 2;
            }
            $leido .= $grid[$r][$c];
        }

        if ($leido !== $palabra) {
            mal($e, "«{$palabra}» no está en la cuadrícula: ahí se lee «{$leido}»");
        }
    }
}


// =====================================================================
//  RECORRIDO
// =====================================================================

echo str_repeat('=', 74), "\n";
echo "  VALIDACIÓN DE ESTACIONES\n";
echo str_repeat('=', 74), "\n\n";

$estaciones = traerTodo(
    'SELECT s.id, s.position, s.title, s.game_type, s.config,
            a.title AS actividad, a.slug AS actividad_slug, a.status
       FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
   ORDER BY a.id, s.position'
);

foreach ($estaciones as $e) {

    $revisadas++;
    $porTipo[$e['game_type']] = ($porTipo[$e['game_type']] ?? 0) + 1;

    $config = json_decode($e['config'] ?? 'null', true);

    if (json_last_error() !== JSON_ERROR_NONE) {
        mal($e, 'el config no es JSON válido: ' . json_last_error_msg());
        continue;
    }

    $datos = $config['datos'] ?? null;

    if ($datos === null) {
        mal($e, 'no tiene datos');
        continue;
    }

    switch ($e['game_type']) {

        case 'sonido_letra':
        case 'seleccion_imagenes':
        case 'juego_rapido':
            revisarSiNo($e, items($datos));
            break;

        case 'opcion_multiple':
            revisarOpciones($e, items($datos), 'enunciado', 'opciones', 'correcta');
            break;

        case 'desafio_final':
            revisarOpciones($e, items($datos), 'q', 'opts', 'a');
            break;

        case 'cuento':
            $slides = $datos['slides'] ?? [];
            $qs     = $datos['qs'] ?? [];
            if (!$slides) {
                mal($e, 'el cuento no tiene páginas');
            }
            foreach ($slides as $i => $s) {
                if (($s['text'] ?? '') === '') {
                    mal($e, "página $i sin texto");
                }
            }
            revisarOpciones($e, $qs, 'q', 'opts', 'a');
            break;

        case 'sopa_letras':
            revisarSopa($e, $datos);
            break;

        case 'emparejar':
        case 'pronunciacion':
            $lista = items($datos);
            if (count($lista) < 2) {
                mal($e, 'hacen falta al menos dos parejas');
            }
            $vistas = [];
            foreach ($lista as $i => $p) {
                if (($p['w'] ?? '') === '' || ($p['e'] ?? '') === '') {
                    mal($e, "pareja $i incompleta");
                }
                // En «emparejar» el motor busca por la palabra: dos
                // parejas con la misma palabra harían la unión ambigua.
                if ($e['game_type'] === 'emparejar' && in_array($p['w'] ?? '', $vistas, true)) {
                    mal($e, "la palabra «{$p['w']}» está repetida");
                }
                $vistas[] = $p['w'] ?? '';
            }
            break;

        case 'ordenar_secuencia':
            $lista = $datos['items'] ?? [];
            if (count($lista) < 2) {
                mal($e, 'hacen falta al menos dos elementos que ordenar');
            }

            /*
             * Un paso puede venir como `{w, e}` —palabra y dibujo— desde
             * que las secuencias se ilustran. Se compara por la PALABRA.
             *
             * Sin esto, `array_unique()` convierte cada paso a texto para
             * compararlo, todos los objetos valen «Array», todos parecen
             * repetidos, y el informe acusaba a treinta secuencias sanas
             * de un problema que no tenían. Es el mismo tropiezo que ya
             * está documentado unas líneas más abajo, en «memoria».
             */
            $lista = array_map(
                static fn($x) => is_array($x) ? (string) ($x['w'] ?? '') : (string) $x,
                $lista
            );
            // El motor compara por valor: un elemento repetido hace que
            // la secuencia se pueda "completar" en el orden equivocado.
            if (count($lista) !== count(array_unique($lista))) {
                mal($e, 'hay elementos repetidos en la secuencia');
            }
            break;

        case 'memoria':
            $lista = items($datos);

            if (count($lista) < 2) {
                mal($e, 'hacen falta al menos dos cartas');
                break;
            }

            /*
             * El motor espera una lista PLANA de símbolos y duplica cada
             * uno para formar la pareja. Si llegan objetos {e,n} —la forma
             * de `emparejar`, fácil de confundir— cada carta se pinta como
             * «[object Object]» y el juego es imposible.
             *
             * Se comprueba antes que la repetición porque `array_unique()`
             * convierte cada elemento a texto para compararlo: con arrays
             * dentro, todos valen «Array», todos parecen repetidos y el
             * informe acusaba de un problema que no era, escondiendo el
             * de verdad.
             */
            $noEscalares = array_filter($lista, static fn($x) => !is_scalar($x));

            if ($noEscalares) {
                mal($e, 'las cartas deben ser símbolos sueltos, no objetos: '
                      . 'llegaron ' . count($noEscalares) . ' de ' . count($lista)
                      . ' como estructura (¿se copió el formato de «emparejar»?)');
                break;
            }

            if (count($lista) !== count(array_unique($lista))) {
                mal($e, 'hay símbolos repetidos: las parejas quedarían ambiguas');
            }
            break;

        case 'secuencia_numerica':
            foreach (items($datos) as $i => $x) {
                $sec = $x['secuencia'] ?? [];
                $huecos = 0;
                foreach ($sec as $n) {
                    if ($n === null) {
                        $huecos++;
                    }
                }
                if ($huecos !== 1) {
                    mal($e, "ejercicio $i: la serie debe tener exactamente un hueco, tiene $huecos");
                }
                if (!isset($x['falta']) || (int) $x['falta'] <= 0) {
                    mal($e, "ejercicio $i: el número que falta debe ser mayor que cero");
                }
            }
            break;

        case 'ortografia':
            foreach (items($datos) as $i => $x) {
                $opts = $x['opts'] ?? [];
                $correcta = $x['correct'] ?? null;
                if (count($opts) < 2) {
                    mal($e, "ejercicio $i: hacen falta al menos dos formas");
                }
                if (!in_array($correcta, $opts, true)) {
                    mal($e, "ejercicio $i: la forma correcta no está entre las opciones");
                }
                if (count(array_keys($opts, $correcta, true)) > 1) {
                    mal($e, "ejercicio $i: la forma correcta aparece repetida");
                }
            }
            break;

        case 'teclado':
            foreach (items($datos) as $i => $w) {
                if (!is_string($w) || trim($w) === '') {
                    mal($e, "ejercicio $i: palabra vacía");
                }
            }
            break;

        case 'completar_palabra':
            foreach (items($datos) as $i => $x) {
                if (($x['a'] ?? '') === '') {
                    mal($e, "ejercicio $i: sin letra correcta");
                }
            }
            break;

        case 'puzle_silabas':
            foreach (items($datos) as $i => $x) {
                if (count($x['syls'] ?? []) < 2) {
                    mal($e, "ejercicio $i: hacen falta al menos dos sílabas");
                }
            }
            break;

        case 'armar_palabras':
            foreach (items($datos) as $i => $x) {
                if (($x['w'] ?? '') === '') {
                    mal($e, "ejercicio $i: sin palabra");
                }
            }
            break;

        case 'operacion':
            if (!items($datos)) {
                mal($e, 'no tiene operaciones');
            }
            break;

        /*
         * Completar huecos: el número de huecos escritos tiene que
         * coincidir con el número de marcas `___` del texto. Si sobra una
         * marca, queda un hueco que nunca se puede llenar y la estación no
         * se termina nunca.
         */
        case 'completar_texto':
            $lista = items($datos);

            if (!$lista) {
                mal($e, 'no tiene textos');
                break;
            }

            foreach ($lista as $i => $x) {
                $texto  = (string) ($x['texto'] ?? '');
                $huecos = $x['huecos'] ?? [];

                if ($texto === '') {
                    mal($e, "texto $i: vacío");
                    continue;
                }

                $marcas = substr_count($texto, '___');

                if ($marcas === 0) {
                    mal($e, "texto $i: no tiene ningún hueco (falta ___)");
                }
                if ($marcas !== count($huecos)) {
                    mal($e, "texto $i: hay $marcas huecos en el texto y "
                          . count($huecos) . ' respuestas');
                }
                foreach ($huecos as $h) {
                    if (trim((string) $h) === '') {
                        mal($e, "texto $i: una respuesta está vacía");
                    }
                }

                /*
                 * Un señuelo que coincide con una respuesta correcta haría
                 * que la ficha buena apareciera dos veces: el niño toca
                 * una, es válida, y la otra se queda muerta en el banco.
                 */
                foreach ($x['extra'] ?? [] as $señuelo) {
                    if (in_array($señuelo, $huecos, true)) {
                        mal($e, "texto $i: el señuelo «$señuelo» también es una respuesta correcta");
                    }
                }
            }
            break;

        /*
         * Crucigrama: se reconstruye la rejilla a partir de las palabras y
         * se comprueba que los cruces cuadren. Dos palabras que se cortan
         * con letras distintas hacen el crucigrama IMPOSIBLE, y nada lo
         * delataría hasta que un niño se quedara atascado.
         */
        case 'crucigrama':
            $palabras = $datos['palabras'] ?? [];
            $filas    = (int) ($datos['filas'] ?? 0);
            $columnas = (int) ($datos['columnas'] ?? 0);

            if (count($palabras) < 2) {
                mal($e, 'un crucigrama necesita al menos dos palabras cruzadas');
                break;
            }
            if ($filas < 1 || $columnas < 1) {
                mal($e, 'la rejilla no tiene tamaño');
                break;
            }

            $rejilla = [];
            $cruces  = [];

            foreach ($palabras as $i => $p) {
                $w   = (string) ($p['w'] ?? '');
                $dir = $p['dir'] ?? '';

                if ($w === '' || !in_array($dir, ['h', 'v'], true)) {
                    mal($e, "palabra $i: sin texto o sin dirección");
                    continue;
                }
                if (($p['pista'] ?? '') === '' && ($p['e'] ?? null) === null) {
                    mal($e, "«$w»: no tiene pista ni dibujo, no hay forma de adivinarla");
                }

                $letras = preg_split('//u', $w, -1, PREG_SPLIT_NO_EMPTY);

                foreach ($letras as $k => $letra) {
                    $r = (int) $p['fila'] + ($dir === 'v' ? $k : 0);
                    $c = (int) $p['col']  + ($dir === 'h' ? $k : 0);

                    if ($r < 0 || $c < 0 || $r >= $filas || $c >= $columnas) {
                        mal($e, "«$w» se sale de la rejilla");
                        continue 2;
                    }

                    $clave = "$r,$c";

                    if (isset($rejilla[$clave]) && $rejilla[$clave] !== $letra) {
                        mal($e, "«$w» cruza en ($r,$c) con «{$rejilla[$clave]}» "
                              . "pero ahí necesita «$letra»: el crucigrama no tiene solución");
                    }

                    $rejilla[$clave] = $letra;
                    $cruces[$clave]  = ($cruces[$clave] ?? 0) + 1;
                }
            }

            // Una palabra que no toca a ninguna otra no es parte del
            // crucigrama: es una palabra suelta flotando en la rejilla.
            foreach ($palabras as $p) {
                $w = (string) ($p['w'] ?? '');
                if ($w === '' || !isset($p['fila'], $p['col'])) {
                    continue;
                }
                $letras = preg_split('//u', $w, -1, PREG_SPLIT_NO_EMPTY);
                $tocada = false;

                foreach ($letras as $k => $letra) {
                    $r = (int) $p['fila'] + (($p['dir'] ?? 'h') === 'v' ? $k : 0);
                    $c = (int) $p['col']  + (($p['dir'] ?? 'h') === 'h' ? $k : 0);
                    if (($cruces["$r,$c"] ?? 0) > 1) {
                        $tocada = true;
                        break;
                    }
                }

                if (!$tocada) {
                    mal($e, "«$w» no se cruza con ninguna otra palabra");
                }
            }
            break;

        /*
         * Laberinto: aquí no basta con que los datos tengan la forma
         * correcta. Hay que comprobar que el nivel SE PUEDA RESOLVER —que
         * exista un camino del robot a la meta— porque un laberinto sin
         * salida deja al niño intentándolo indefinidamente sin que nada
         * le diga que el imposible no es culpa suya.
         */
        case 'laberinto':
            $niveles = items($datos);

            if (!$niveles) {
                mal($e, 'no tiene niveles');
                break;
            }

            foreach ($niveles as $i => $lv) {
                $filas = (int) ($lv['filas'] ?? 0);
                $cols  = (int) ($lv['columnas'] ?? 0);

                if ($filas < 1 || $cols < 1) {
                    mal($e, "nivel $i: tablero sin tamaño");
                    continue;
                }

                $inicio = $lv['inicio'] ?? null;
                $meta   = $lv['meta'] ?? null;

                if (!is_array($inicio) || !is_array($meta)) {
                    mal($e, "nivel $i: falta el inicio o la meta");
                    continue;
                }

                $dentro = static fn($p) => $p[0] >= 0 && $p[0] < $filas
                                        && $p[1] >= 0 && $p[1] < $cols;

                if (!$dentro($inicio)) {
                    mal($e, "nivel $i: el robot empieza fuera del tablero");
                    continue;
                }
                if (!$dentro($meta)) {
                    mal($e, "nivel $i: la meta está fuera del tablero");
                    continue;
                }

                $muros = [];
                foreach ($lv['muros'] ?? [] as $m) {
                    $muros[$m[0] . ',' . $m[1]] = true;
                }

                if (isset($muros[$inicio[0] . ',' . $inicio[1]])) {
                    mal($e, "nivel $i: el robot empieza dentro de un muro");
                    continue;
                }
                if (isset($muros[$meta[0] . ',' . $meta[1]])) {
                    mal($e, "nivel $i: la meta está tapada por un muro");
                    continue;
                }

                if ($inicio === $meta) {
                    mal($e, "nivel $i: el robot ya empieza en la meta");
                    continue;
                }

                // Recorrido en anchura: ¿hay camino de verdad?
                $cola     = [$inicio];
                $vistos   = [$inicio[0] . ',' . $inicio[1] => true];
                $alcanza  = false;

                while ($cola) {
                    [$r, $k] = array_shift($cola);

                    if ($r === $meta[0] && $k === $meta[1]) {
                        $alcanza = true;
                        break;
                    }

                    foreach ([[-1, 0], [1, 0], [0, -1], [0, 1]] as [$dr, $dk]) {
                        $nr = $r + $dr;
                        $nk = $k + $dk;
                        $clave = $nr . ',' . $nk;

                        if ($nr < 0 || $nr >= $filas || $nk < 0 || $nk >= $cols) {
                            continue;
                        }
                        if (isset($muros[$clave]) || isset($vistos[$clave])) {
                            continue;
                        }

                        $vistos[$clave] = true;
                        $cola[] = [$nr, $nk];
                    }
                }

                if (!$alcanza) {
                    mal($e, "nivel $i: no existe ningún camino hasta la meta");
                }
            }
            break;

        default:
            mal($e, "tipo de minijuego desconocido: {$e['game_type']}");
    }
}


// =====================================================================
//  INFORME
// =====================================================================

printf("  Estaciones revisadas : %d\n", $revisadas);
printf("  Tipos de minijuego   : %d\n\n", count($porTipo));

if ($detalle) {
    ksort($porTipo);
    foreach ($porTipo as $tipo => $n) {
        printf("    %-22s %4d\n", $tipo, $n);
    }
    echo "\n";
}

if (!$fallos) {
    echo "  ✅ Todas las estaciones se pueden jugar y terminar.\n";
    echo str_repeat('=', 74), "\n";
    exit(0);
}

printf("  ❌ %d problema(s):\n\n", count($fallos));

$actual = null;
foreach ($fallos as $f) {
    if ($f['actividad'] !== $actual) {
        $actual = $f['actividad'];
        echo "  $actual\n";
    }
    printf("    · %-34s [%s] %s\n", $f['estacion'], $f['tipo'], $f['problema']);
}

echo "\n", str_repeat('=', 74), "\n";
exit(1);
