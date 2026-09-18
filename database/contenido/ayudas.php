<?php
/**
 * ayudas.php — Utilidades para escribir contenido nuevo
 *
 * Las actividades migradas del sitio anterior se leían de archivos HTML.
 * Estas categorías nuevas no vienen de ningún lado: se escriben aquí, a
 * mano, y estas funciones evitan repetir la misma estructura cientos de
 * veces y equivocarse en el camino.
 *
 * La forma que producen es exactamente la que espera assets/js/motor.js;
 * no hay ningún motor nuevo que programar.
 */

declare(strict_types=1);

/*
 * El diccionario de dibujos. Se carga aquí porque `omp()`, `reto()` y
 * `ordenar()` lo usan para ilustrar solos los ejercicios a los que nadie
 * les puso dibujo. Ver `dibujos.php`: explica por qué es un diccionario y
 * no seiscientas ediciones a mano.
 */
require_once __DIR__ . '/dibujos.php';


// =====================================================================
//  EJERCICIOS DE OPCIÓN MÚLTIPLE
//
//  Es la mecánica más común del catálogo. El motor espera:
//      {enunciado, visual, tipoVisual, opciones:[...], correcta: índice}
// =====================================================================

/**
 * Ejercicio de opción múltiple indicando la respuesta por su VALOR.
 *
 * Es la forma cómoda de escribir: uno pone la respuesta correcta y no
 * tiene que contar posiciones. Si la respuesta no está entre las
 * opciones se lanza un error en vez de guardar un ejercicio imposible:
 * más vale que falle la siembra a que un niño se quede sin poder acertar.
 *
 * @param string $tipoVisual emoji | color | dosColores | lista | texto
 */
function omp(string $enunciado, array $opciones, $correcta, $visual = null, string $tipoVisual = 'emoji'): array
{
    $opciones = array_values($opciones);
    $indice   = array_search($correcta, $opciones, true);

    if ($indice === false) {
        throw new RuntimeException(
            "La respuesta «" . var_export($correcta, true) . "» no está entre las opciones "
            . "de: $enunciado"
        );
    }

    /*
     * Si nadie puso dibujo, se busca uno en el ENUNCIADO.
     *
     * Del enunciado y jamás de la respuesta: ilustrar «¿Cuál es un
     * animal?» con 🐶 no ayuda, delata. El ejercicio se convertiría en
     * tocar el botón que coincide con el dibujo de arriba.
     *
     * Si el diccionario no encuentra nada claro, se queda sin dibujo:
     * uno equivocado es peor que ninguno, porque un niño que está
     * aprendiendo no lo corrige — lo memoriza.
     */
    if ($visual === null) {
        $deducido = dibujoDe($enunciado);

        if ($deducido !== null) {
            $visual     = $deducido;
            $tipoVisual = 'emoji';
        }
    }

    return [
        'enunciado'  => $enunciado,
        'visual'     => $visual,
        'tipoVisual' => $visual === null ? 'ninguno' : $tipoVisual,
        'opciones'   => $opciones,
        'correcta'   => (int) $indice,
    ];
}

/**
 * Igual, pero indicando la respuesta por su POSICIÓN.
 *
 * Hace falta cuando las opciones pueden repetirse — «¿cuál es distinto?»
 * entre cuatro emojis donde tres son idénticos — y buscar por valor
 * daría siempre la primera.
 */
function ompi(string $enunciado, array $opciones, int $indice, $visual = null, string $tipoVisual = 'emoji'): array
{
    $opciones = array_values($opciones);

    if (!isset($opciones[$indice])) {
        throw new RuntimeException("Posición $indice fuera de rango en: $enunciado");
    }

    return [
        'enunciado'  => $enunciado,
        'visual'     => $visual,
        'tipoVisual' => $visual === null ? 'ninguno' : $tipoVisual,
        'opciones'   => $opciones,
        'correcta'   => $indice,
    ];
}

/** Pregunta del desafío final o del cuento: {q, opts, a}. */
function reto(string $pregunta, array $opciones, $correcta, ?string $dibujo = null): array
{
    $opciones = array_values($opciones);
    $indice   = array_search($correcta, $opciones, true);

    if ($indice === false) {
        throw new RuntimeException("La respuesta no está entre las opciones de: $pregunta");
    }

    /*
     * El desafío también ilustra. Era el único minijuego de preguntas sin
     * dibujo —`reto()` ni siquiera tenía el campo— y en preescolar eso lo
     * dejaba convertido en un examen de lectura.
     */
    $e = $dibujo ?? dibujoDe($pregunta);

    $salida = ['q' => $pregunta, 'opts' => $opciones, 'a' => (int) $indice];

    if ($e !== null && $e !== '') {
        $salida['e'] = $e;
    }

    return $salida;
}

/**
 * Una secuencia para ordenar, con dibujo en cada paso.
 *
 * Se escribe `['Despertarse' => '⏰', 'Desayunar' => '🥣']`, o una lista
 * pelada y entonces el dibujo lo pone el diccionario.
 *
 * Antes un paso era solo texto, y para un niño que no lee eso es una
 * ficha en blanco: oía la instrucción pero no sabía qué decía cada botón,
 * así que ordenaba a ciegas. El motor acepta las dos formas, así que las
 * secuencias que ya existían siguen funcionando.
 */
function ordenar(string $titulo, array $pasos): array
{
    $items = [];

    foreach ($pasos as $clave => $valor) {
        // ['Paso' => '⏰']  ·  o bien  ['Paso', 'Paso']
        $palabra = is_int($clave) ? (string) $valor : (string) $clave;
        $e       = is_int($clave) ? dibujoDe($palabra) : (string) $valor;

        $items[] = ($e !== null && $e !== '')
            ? ['w' => $palabra, 'e' => $e]
            : ['w' => $palabra];
    }

    return ['title' => $titulo, 'items' => $items];
}

/** Baraja devolviendo una copia; el orden es reproducible (ver mt_srand). */
function mezclar(array $a): array
{
    shuffle($a);
    return $a;
}


// =====================================================================
//  ESTACIONES Y ACTIVIDADES
// =====================================================================

/**
 * Una estación (un minijuego dentro de la actividad).
 *
 * `$idioma` solo lo usan las actividades de Idiomas: le dice al motor en
 * qué lengua leer en voz alta. Sin él, todo se lee en español.
 */
function est(string $titulo, string $descripcion, string $icono, string $tipo, $datos, ?string $idioma = null): array
{
    return [
        'titulo'      => $titulo,
        'descripcion' => $descripcion,
        'icono'       => $icono,
        'tipo'        => $tipo,
        'datos'       => $datos,
        'idioma'      => $idioma,
    ];
}

/**
 * Una estación de inglés: igual que `est()`, pero declarando `en-US`.
 *
 * Existe porque el idioma no es un detalle opcional de estas estaciones,
 * es la mitad del ejercicio. Con la voz española «cat» suena «kat» y la
 * actividad enseña justo lo contrario de lo que pretende. Escribiendo
 * `'en-US'` a mano en cada estación, olvidarlo una vez es cuestión de
 * tiempo; así no hay nada que olvidar.
 */
function en(string $titulo, string $descripcion, string $icono, string $tipo, $datos): array
{
    return est($titulo, $descripcion, $icono, $tipo, $datos, 'en-US');
}

/**
 * Encabezado propio para los minijuegos que traían texto de lectoescritura
 * escrito dentro del motor. Ver `desempacar()` en motor.js.
 */
function conTitulo(string $titulo, string $subtitulo, array $items): array
{
    return ['t' => $titulo, 's' => $subtitulo, 'items' => $items];
}

/**
 * Un ejercicio de «¿qué número falta?»: la lista lleva un null.
 *
 * SOLO SIRVE PARA SERIES ARITMÉTICAS —de 2 en 2, de 5 en 5, hacia atrás—
 * y el paso se deduce dividiendo entre los intervalos de la lista entera.
 * Eso falla en dos casos, los dos en silencio:
 *
 *   · Una serie geométrica: 2, 4, 8, ?, 32 deduce paso 8 y pide 26.
 *   · Un hueco en un extremo, cuando el paso no es 1: en
 *     [?, 505, 510, 515] la diferencia conocida abarca dos intervalos
 *     pero se reparte entre tres, sale paso 3 y pide 502.
 *
 * En vez de prohibir esas formas —un hueco al principio con paso 1 es
 * perfectamente correcto y ya se usa en el catálogo— se comprueba el
 * RESULTADO: se reconstruye la serie con el paso deducido y tiene que
 * coincidir con la escrita. Comprobar la respuesta es más exacto que
 * adivinar por la forma, y no rechaza lo que sí funciona.
 *
 * Lanza en vez de guardar: un ejercicio con la respuesta equivocada no
 * falla al sembrarse, falla delante del niño, que acierta y el juego le
 * dice que no.
 */
function serie(array $numeros): array
{
    $indice = array_search(null, $numeros, true);

    if ($indice === false) {
        throw new RuntimeException('La serie no tiene ningún hueco (null).');
    }

    // El hueco se deduce del paso entre los números que sí están, para
    // que funcione con series de 2 en 2, de 5 en 5 o descendentes.
    $conocidos = array_values(array_filter($numeros, static fn($n) => $n !== null));
    $paso      = count($conocidos) > 1
        ? (int) round(($conocidos[count($conocidos) - 1] - $conocidos[0]) / (count($numeros) - 1))
        : 1;

    $ancla  = null;
    $anclaI = null;
    foreach ($numeros as $i => $n) {
        if ($n !== null) {
            $ancla  = $n;
            $anclaI = $i;
            break;
        }
    }

    $falta = (int) ($ancla + $paso * ($indice - $anclaI));

    // La comprobación del resultado (ver arriba): con el paso deducido, cada
    // número conocido tiene que caer donde está escrito.
    foreach ($numeros as $i => $n) {
        if ($n !== null && $n !== $ancla + $paso * ($i - $anclaI)) {
            throw new RuntimeException(
                'El número que falta no se puede deducir de esta serie: con el paso '
                . "deducido ($paso) los números conocidos no encajan. Serie: "
                . implode(', ', array_map(static fn($x) => $x ?? '?', $numeros))
            );
        }
    }

    return ['secuencia' => $numeros, 'falta' => $falta];
}


// =====================================================================
//  CRUCIGRAMA
// =====================================================================

/**
 * Coloca palabras cruzándose y devuelve la rejilla resuelta.
 *
 * El motor no resuelve nada: necesita saber de antemano dónde va cada
 * palabra, igual que en la sopa de letras. Aquí se calcula ese encaje.
 *
 * CÓMO SE COLOCA
 *
 * La primera palabra va horizontal en el centro. Cada siguiente busca una
 * letra que ya esté puesta y se cuelga de ella en perpendicular. Es el
 * método clásico y produce crucigramas compactos, que es lo que hace falta
 * en una pantalla de tableta.
 *
 * LAS TRES REGLAS QUE HACEN QUE UN CRUCIGRAMA SEA RESOLUBLE
 *
 * 1. Donde dos palabras se cruzan, la letra tiene que ser la misma.
 * 2. Antes de la primera letra y después de la última no puede haber otra
 *    letra: si no, al leer la fila aparecerían dos palabras pegadas y
 *    ninguna de las dos sería la que pide la pista.
 * 3. Las casillas de al lado (en perpendicular) deben estar libres, salvo
 *    en el cruce. Sin esta regla salen palabras paralelas pegadas que
 *    forman columnas de letras sin sentido.
 *
 * Si una palabra no encaja se OMITE y se sigue. Un crucigrama con una
 * palabra menos se puede resolver; uno con una palabra mal cruzada, no.
 *
 * @param array $entradas [['w' => 'GATO', 'pista' => '…', 'e' => '🐱'], …]
 * @return array{filas:int, columnas:int, palabras:array}
 */
function crucigrama(array $entradas, int $tam = 13): array
{
    $grid    = array_fill(0, $tam, array_fill(0, $tam, null));
    $puestas = [];

    /** Letras de una palabra, respetando acentos y Ñ. */
    $partir = static fn(string $w): array =>
        preg_split('//u', mb_strtoupper($w), -1, PREG_SPLIT_NO_EMPTY);

    /** ¿Está dentro de la rejilla? */
    $dentro = static fn(int $r, int $c): bool =>
        $r >= 0 && $c >= 0 && $r < $tam && $c < $tam;

    /**
     * Comprueba las tres reglas de arriba para una posición concreta.
     *
     * `$grid` va POR REFERENCIA a propósito: la rejilla cambia con cada
     * palabra colocada y esta comprobación tiene que ver el estado actual.
     * Capturada por valor —el modo por defecto— vería siempre el tablero
     * vacío, ninguna palabra encontraría con qué cruzarse y no se formaría
     * ni un crucigrama.
     */
    $cabe = static function (array $letras, int $fila, int $col, string $dir)
                    use (&$grid, $tam, $dentro): bool {

        $n  = count($letras);
        $dr = $dir === 'v' ? 1 : 0;
        $dc = $dir === 'h' ? 1 : 0;

        $finR = $fila + $dr * ($n - 1);
        $finC = $col  + $dc * ($n - 1);

        if (!$dentro($fila, $col) || !$dentro($finR, $finC)) {
            return false;
        }

        // Regla 2: los extremos deben respirar.
        $antesR = $fila - $dr;  $antesC = $col - $dc;
        $trasR  = $finR + $dr;  $trasC  = $finC + $dc;

        if ($dentro($antesR, $antesC) && $grid[$antesR][$antesC] !== null) return false;
        if ($dentro($trasR, $trasC)   && $grid[$trasR][$trasC]   !== null) return false;

        $cruces = 0;

        for ($k = 0; $k < $n; $k++) {
            $r = $fila + $dr * $k;
            $c = $col  + $dc * $k;

            $actual = $grid[$r][$c];

            if ($actual !== null) {
                // Regla 1: si ya hay letra, tiene que ser la misma.
                if ($actual !== $letras[$k]) {
                    return false;
                }
                $cruces++;
                continue;
            }

            // Regla 3: en casilla vacía, los lados perpendiculares libres.
            $lados = $dir === 'h' ? [[$r - 1, $c], [$r + 1, $c]]
                                  : [[$r, $c - 1], [$r, $c + 1]];

            foreach ($lados as [$lr, $lc]) {
                if ($dentro($lr, $lc) && $grid[$lr][$lc] !== null) {
                    return false;
                }
            }
        }

        // Salvo la primera palabra, toda palabra debe cruzar alguna otra:
        // una palabra suelta en el tablero no es un crucigrama.
        return $cruces > 0;
    };

    // Las largas primero: son las que más cruces ofrecen a las siguientes.
    usort($entradas, static fn($a, $b) => mb_strlen($b['w']) <=> mb_strlen($a['w']));

    foreach ($entradas as $i => $entrada) {

        $letras = $partir($entrada['w']);
        $n      = count($letras);

        if ($n < 2 || $n > $tam) {
            continue;
        }

        $colocada = null;

        if (!$puestas) {
            // La primera, horizontal y centrada.
            $fila = intdiv($tam, 2);
            $col  = max(0, intdiv($tam - $n, 2));
            $colocada = [$fila, $col, 'h'];

        } else {
            // Se prueban todos los cruces posibles con lo ya puesto y se
            // recorren en orden barajado, para que dos crucigramas con las
            // mismas palabras no salgan siempre idénticos.
            $opciones = [];

            foreach ($puestas as $ya) {
                $yaLetras = $partir($ya['w']);

                foreach ($yaLetras as $j => $letraYa) {
                    foreach ($letras as $k => $letraNueva) {
                        if ($letraYa !== $letraNueva) {
                            continue;
                        }

                        // Se cruza en perpendicular a la ya colocada.
                        if ($ya['dir'] === 'h') {
                            $opciones[] = [$ya['fila'] - $k, $ya['col'] + $j, 'v'];
                        } else {
                            $opciones[] = [$ya['fila'] + $j, $ya['col'] - $k, 'h'];
                        }
                    }
                }
            }

            shuffle($opciones);

            foreach ($opciones as [$f, $c, $d]) {
                if ($cabe($letras, $f, $c, $d)) {
                    $colocada = [$f, $c, $d];
                    break;
                }
            }
        }

        if ($colocada === null) {
            continue;   // no encaja: se omite y seguimos
        }

        [$fila, $col, $dir] = $colocada;
        $dr = $dir === 'v' ? 1 : 0;
        $dc = $dir === 'h' ? 1 : 0;

        for ($k = 0; $k < $n; $k++) {
            $grid[$fila + $dr * $k][$col + $dc * $k] = $letras[$k];
        }

        $puestas[] = [
            'w'     => mb_strtoupper($entrada['w']),
            'fila'  => $fila,
            'col'   => $col,
            'dir'   => $dir,
            'pista' => $entrada['pista'] ?? '',
            'e'     => $entrada['e'] ?? null,
        ];
    }

    if (count($puestas) < 2) {
        throw new RuntimeException(
            'No se pudieron cruzar al menos dos palabras. Con estas no hay '
            . 'letras en común suficientes: ' . implode(', ', array_column($entradas, 'w'))
        );
    }

    /*
     * Se recorta al rectángulo realmente usado. Sin esto la rejilla sería
     * siempre de 13×13 con la mitad vacía, y en una tableta las casillas
     * saldrían diminutas para nada.
     */
    $minF = $minC = $tam;
    $maxF = $maxC = 0;

    foreach ($puestas as $p) {
        $n  = mb_strlen($p['w']);
        $fF = $p['fila'] + ($p['dir'] === 'v' ? $n - 1 : 0);
        $fC = $p['col']  + ($p['dir'] === 'h' ? $n - 1 : 0);

        $minF = min($minF, $p['fila']);   $maxF = max($maxF, $fF);
        $minC = min($minC, $p['col']);    $maxC = max($maxC, $fC);
    }

    foreach ($puestas as &$p) {
        $p['fila'] -= $minF;
        $p['col']  -= $minC;
    }
    unset($p);

    return [
        'filas'    => $maxF - $minF + 1,
        'columnas' => $maxC - $minC + 1,
        'palabras' => $puestas,
    ];
}


// =====================================================================
//  SOPA DE LETRAS
// =====================================================================

/**
 * Arma una sopa de letras a partir de una lista de palabras.
 *
 * El motor no busca las palabras: necesita saber de antemano en qué
 * casillas quedó cada una, porque el niño la marca tocando la primera y
 * la última letra. Por eso aquí se devuelven las coordenadas.
 *
 * Las palabras se colocan en horizontal o vertical y pueden cruzarse si
 * comparten letra. Si alguna no cabe tras varios intentos, se omite y se
 * avisa: es preferible una sopa con una palabra menos que una sopa con
 * una palabra que no se puede encontrar.
 *
 * @return array{grid: array, words: array}
 */
function sopa(array $palabras, int $tam = 10): array
{
    $grid    = array_fill(0, $tam, array_fill(0, $tam, null));
    $ubicadas = [];

    // Primero las largas: las cortas encuentran hueco en cualquier parte.
    usort($palabras, static fn($a, $b) => mb_strlen($b) <=> mb_strlen($a));

    foreach ($palabras as $palabra) {
        $palabra = mb_strtoupper($palabra);
        $letras  = preg_split('//u', $palabra, -1, PREG_SPLIT_NO_EMPTY);
        $largo   = count($letras);

        if ($largo > $tam) {
            continue;
        }

        for ($intento = 0; $intento < 400; $intento++) {
            $horizontal = mt_rand(0, 1) === 1;
            $fila = mt_rand(0, $tam - ($horizontal ? 1 : $largo));
            $col  = mt_rand(0, $tam - ($horizontal ? $largo : 1));

            $casillas = [];
            $cabe     = true;

            for ($k = 0; $k < $largo; $k++) {
                $r = $horizontal ? $fila : $fila + $k;
                $c = $horizontal ? $col + $k : $col;

                // Se permite cruzar otra palabra solo si comparten letra.
                if ($grid[$r][$c] !== null && $grid[$r][$c] !== $letras[$k]) {
                    $cabe = false;
                    break;
                }
                $casillas[] = [$r, $c];
            }

            if (!$cabe) {
                continue;
            }

            foreach ($casillas as $k => [$r, $c]) {
                $grid[$r][$c] = $letras[$k];
            }

            $ubicadas[] = ['w' => $palabra, 'cells' => $casillas];
            break;
        }
    }

    // Relleno. Sin Ñ ni tildes: son las letras que nunca aparecerán en
    // las palabras buscadas y confundirían al buscarlas.
    $alfabeto = preg_split('//u', 'ABCDEFGHIJLMNOPRSTUVZ', -1, PREG_SPLIT_NO_EMPTY);

    for ($r = 0; $r < $tam; $r++) {
        for ($c = 0; $c < $tam; $c++) {
            if ($grid[$r][$c] === null) {
                $grid[$r][$c] = $alfabeto[mt_rand(0, count($alfabeto) - 1)];
            }
        }
    }

    return ['grid' => $grid, 'words' => $ubicadas];
}
