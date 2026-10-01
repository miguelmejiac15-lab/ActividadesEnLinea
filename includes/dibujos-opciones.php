<?php
/**
 * dibujos-opciones.php — Un dibujo pequeño en cada opción, para quien no lee
 *
 * «¿Cuál de los dos animales es más grande?» con los botones «El
 * elefante» y «El ratón» es una pregunta que un niño de cuatro años sabe
 * responder y no puede: oye la pregunta, pero no sabe cuál botón dice
 * elefante. Con 🐘 y 🐭 al lado de cada palabra, sí.
 *
 * El diccionario es el mismo con el que se ilustraron los enunciados al
 * sembrar el contenido (`database/contenido/dibujos.php`), así una
 * palabra se dibuja igual en la pregunta y en las opciones.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  CUÁNDO NO SE PONE NINGÚN DIBUJO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Las mismas dos reglas del diccionario, aplicadas a las opciones:
 *
 *   1. NUNCA DELATAR LA RESPUESTA. En «¿Cómo se escribe?» con «Huevo» y
 *      «Uevo», solo la buena está en el diccionario: el huevo saldría
 *      ilustrado y la mala no, y el ejercicio de ortografía dejaría de
 *      serlo. Si dos opciones se parecen mucho entre sí, la pregunta es
 *      de escritura y no se dibuja ninguna.
 *   2. MEJOR NINGUNO QUE UNO MALO. Solo palabras completas y concretas
 *      (animales, comida, cuerpo, cosas). Nada de adivinar con frases
 *      largas, y nada si menos de dos opciones tienen dibujo: una sola
 *      ilustrada destacaría como pista.
 *
 * Solo para los niveles que todavía no leen con soltura (preescolar,
 * primero y segundo). A partir de tercero el dibujo sobra y además le
 * quita al ejercicio parte de su lectura.
 */

declare(strict_types=1);

require_once RUTA_RAIZ . '/database/contenido/dibujos.php';

/** Niveles en los que las opciones llevan dibujo. */
const NIVELES_CON_DIBUJOS = ['preescolar', 'primaria-inicial'];

/**
 * Palabras del diccionario que NO se dibujan en una opción.
 *
 * Colores y figuras geométricas: en «¿De qué color es?» o «¿Cómo se llama
 * esta figura?» lo que se practica es justo el nombre, y un 🔴 al lado de
 * «Rojo» convierte el ejercicio en emparejar manchas. Además el
 * diccionario los dibuja mal fuera de contexto: «café» el color sale como
 * una taza, «corazón» la figura como el órgano, «el cuadrado rojo» como
 * un cuadrado blanco.
 */
const SIN_DIBUJO_EN_OPCIONES = [
    'rojo', 'roja', 'rojos', 'rojas', 'azul', 'azules', 'verde', 'verdes',
    'amarillo', 'amarilla', 'amarillos', 'morado', 'morada', 'violeta',
    'rosado', 'rosada', 'rosa', 'blanco', 'blanca', 'negro', 'negra',
    'gris', 'cafe', 'marron', 'celeste', 'naranja',
    'circulo', 'circulos', 'cuadrado', 'cuadrados', 'triangulo', 'triangulos',
    'rectangulo', 'rectangulos', 'rombo', 'ovalo', 'corazon', 'figura', 'figuras',
    // El diccionario los dibuja con el mismo vaso: «vacío» saldría lleno.
    'lleno', 'llena', 'vacio', 'vacia',
];

/**
 * Opciones que no nombran una cosa, sino una relación entre las otras:
 * no se dibujan y no cuentan para exigir que todas tengan dibujo.
 */
function esOpcionDeRelleno(string $texto): bool
{
    return preg_match(
        '/^(ninguno|ninguna|ningun[oa]s?\b.*|son iguales|iguales|aparecen juntas|no se sabe|no se puede saber)$/u',
        dibujosNormalizar($texto)
    ) === 1;
}

/**
 * El dibujo de un objeto concreto nombrado en el texto, o null.
 *
 * Como `dibujoDe()` pero SIN su segunda vuelta de «tareas» (contar,
 * comparar…): en una opción, un dibujo de tarea no ayuda a reconocerla.
 */
function dibujoDeObjeto(string $texto): ?string
{
    static $claves = null;

    if ($claves === null) {
        $claves = DIBUJOS;
        uksort($claves, static fn($a, $b) => mb_strlen((string) $b) <=> mb_strlen((string) $a));
    }

    $t = dibujosNormalizar($texto);

    foreach ($claves as $clave => $emoji) {
        $c = dibujosNormalizar((string) $clave);

        if (mb_strlen($c) < 3 || in_array($c, DIBUJOS_PROHIBIDOS, true)
            || in_array($c, SIN_DIBUJO_EN_OPCIONES, true)) {
            continue;
        }

        $patron = '/(?:^|[\s¿?¡!.,;:()«»"\'\-–—])'
                . preg_quote($c, '/')
                . '(?:$|[\s¿?¡!.,;:()«»"\'\-–—])/u';

        if (preg_match($patron, $t) === 1) {
            return (string) $emoji;
        }
    }

    return null;
}

/** ¿El texto ya trae un dibujo (emoji o símbolo)? */
function traeDibujo(string $texto): bool
{
    return preg_match('/[\x{1F300}-\x{1FAFF}\x{2600}-\x{27BF}]/u', $texto) === 1;
}

/**
 * Un dibujo por opción (o null en la posición que no tiene), o null si
 * esta pregunta no debe llevar dibujos en las opciones.
 *
 * @param string[]    $opciones
 * @param string|null $visual    El dibujo de la propia pregunta, si tiene.
 * @param string      $enunciado La pregunta.
 * @return array<int, ?string>|null
 */
function dibujosParaOpciones(array $opciones, ?string $visual = null, string $enunciado = ''): ?array
{
    $textos = array_map(static fn($o): string => trim((string) $o), array_values($opciones));

    if (count($textos) < 2) {
        return null;
    }

    /*
     * «¿Qué es?», «¿Qué siente?», «¿Cómo se siente?» con un dibujo
     * arriba: la pregunta ES nombrar lo que se ve. Con la cara o la
     * silueta repetida en una opción, el ejercicio desaparece. Una
     * pregunta así es corta y trae su dibujo; ahí no se dibujan opciones.
     */
    $palabrasPregunta = count(preg_split('/\s+/u', trim($enunciado)) ?: []);

    if ($visual !== null && trim($visual) !== '' && $palabrasPregunta <= 4) {
        return null;
    }

    foreach ($textos as $t) {
        // Ya ilustradas por quien escribió el contenido: se respetan.
        if (traeDibujo($t)) {
            return null;
        }
    }

    /*
     * Regla 1: opciones casi iguales = pregunta de escritura.
     * «huevo»/«uevo», «vaca»/«baca», «árbol»/«arbol» están a una o dos
     * letras; dos animales distintos, no.
     */
    $norm = array_map('dibujosNormalizar', $textos);

    for ($i = 0; $i < count($norm); $i++) {
        for ($j = $i + 1; $j < count($norm); $j++) {
            if (levenshtein($norm[$i], $norm[$j]) <= 2) {
                return null;
            }
        }
    }

    $dibujos   = [];
    $contenido = 0;

    foreach ($textos as $t) {
        if (esOpcionDeRelleno($t)) {
            $dibujos[] = null;
            continue;
        }

        $contenido++;

        // Una frase larga no es un objeto: «Porque necesita agua y sol».
        $palabras = preg_split('/\s+/u', $t) ?: [];
        $d = count($palabras) <= 3 ? dibujoDeObjeto($t) : null;

        /*
         * TODAS las opciones de contenido llevan dibujo, o ninguna. Si
         * solo una se queda sin él, destaca: en «¿cuál es el ave símbolo
         * de Colombia?» el cóndor sin dibujo entre un pingüino y una
         * gallina dibujados es la respuesta señalada con el dedo.
         */
        if ($d === null) {
            return null;
        }

        // El mismo dibujo que la pregunta: «¿qué animal es?» con 🐶
        // arriba y 🐶 en una opción es emparejar, no responder.
        if ($visual !== null && $visual !== '' && $d === trim($visual)) {
            return null;
        }

        $dibujos[] = $d;
    }

    // Regla 2: al menos dos con dibujo, y que no sean todos el mismo.
    $distintos = array_unique(array_filter($dibujos));

    if ($contenido < 2 || count($distintos) < 2) {
        return null;
    }

    return $dibujos;
}

/**
 * Añade `dibujos` a las preguntas de una estación, según su tipo.
 * Solo toca los minijuegos de opciones de texto; el resto pasa igual.
 */
function ilustrarOpciones(string $tipo, $contenido)
{
    if (!is_array($contenido)) {
        return $contenido;
    }

    if ($tipo === 'opcion_multiple') {
        foreach ($contenido as &$it) {
            if (is_array($it) && isset($it['opciones']) && is_array($it['opciones'])) {
                $visual = is_string($it['visual'] ?? null) ? $it['visual'] : null;
                $d = dibujosParaOpciones($it['opciones'], $visual, (string) ($it['enunciado'] ?? ''));
                if ($d !== null) {
                    $it['dibujos'] = $d;
                }
            }
        }
        unset($it);
    }

    if ($tipo === 'desafio_final') {
        foreach ($contenido as &$it) {
            if (is_array($it) && isset($it['opts']) && is_array($it['opts'])) {
                $d = dibujosParaOpciones($it['opts'], is_string($it['e'] ?? null) ? $it['e'] : null, (string) ($it['q'] ?? ''));
                if ($d !== null) {
                    $it['dibujos'] = $d;
                }
            }
        }
        unset($it);
    }

    return $contenido;
}
