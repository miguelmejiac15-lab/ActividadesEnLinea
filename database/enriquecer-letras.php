<?php
/**
 * enriquecer-letras.php — Amplía el vocabulario de «Aventura de las Letras»
 *
 *     php database/enriquecer-letras.php            (simulación)
 *     php database/enriquecer-letras.php --aplicar  (escribe)
 *
 * QUÉ ARREGLA
 *
 * Las 23 letras y las 5 vocales se migraron del sitio anterior tal cual, y
 * ese sitio traía **tres palabras por estación**: la M entera se enseñaba
 * con MANO, MESA y MONO repetidas quince veces. El promedio de la
 * categoría era de 4,1 palabras por estación.
 *
 * Este script regenera las estaciones de vocabulario a partir del banco de
 * `contenido/vocabulario-letras.php`, subiendo ese promedio a unas diez.
 *
 * QUÉ NO TOCA, Y POR QUÉ
 *
 * De las quince estaciones de una letra, tres se dejan intactas:
 *
 *   · `ordenar_secuencia` — es una historia concreta, con sus escenas en
 *     un orden que significa algo. Añadirle elementos no la enriquece: la
 *     rompe.
 *   · `cuento` — igual: tiene su relato y sus preguntas escritas.
 *   · `desafio_final` — sus preguntas están redactadas una a una.
 *
 * Enriquecer es dar más vocabulario, no inflar todo lo que se pueda contar.
 *
 * Es idempotente: se puede volver a ejecutar y deja el mismo resultado,
 * porque cada estación se reconstruye entera desde el banco.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/ayudas.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

// Semilla fija: las barajadas y las sopas salen siempre iguales, así que
// volver a ejecutar no genera un contenido distinto por puro azar.
mt_srand(20260907);

$aplicar = in_array('--aplicar', $argv ?? [], true);
$banco   = require __DIR__ . '/contenido/vocabulario-letras.php';


// =====================================================================
//  CUÁNTAS PALABRAS LLEVA CADA ESTACIÓN
//
//  Los números no son iguales a propósito. Una estación de sí/no se
//  responde de un toque y admite catorce sin cansar; escribir catorce
//  palabras con el teclado sería un castigo. La media sale cerca de diez.
// =====================================================================

const CUPOS = [
    'sonido_letra'       => 12,
    'seleccion_imagenes' => 16,
    'juego_rapido'       => 16,
    'puzle_silabas'      => 10,
    'armar_palabras'     => 10,
    'teclado'            => 10,
    'ortografia'         => 12,
    'memoria'            => 12,
    'sopa_letras'        => 10,
    'pronunciacion'      => 10,
    'completar_palabra'  => 10,
    'emparejar'          => 10,
    'opcion_multiple'    => 10,
    'crucigrama'         => 8,
];

/**
 * Estaciones que se AÑADEN al final si la actividad no las tiene.
 *
 * El crucigrama de imágenes no existía en el sitio anterior, así que no
 * hay nada que regenerar: hay que crearlo. Va al final porque es la
 * estación más exigente —hay que escribir la palabra entera sin verla— y
 * porque añadirla en medio correría las posiciones y con ellas el corte
 * entre lo gratuito y lo premium.
 */
const NUEVAS = [
    ['crucigrama', 'Crucigrama de la {L}', '🔠',
     'Mira el dibujo de cada pista y escribe la palabra'],
];

/**
 * Actividades que usan el banco de otra.
 *
 * Los «paquetes por vocal» son un recorrido aparte por la misma vocal
 * —empieza-con, contar, completar, sílabas, parejas— así que comparten
 * vocabulario con su vocal sin ser la misma actividad.
 */
const ALIAS = [
    'paquete-vocal-a' => 'vocal-a',
    'paquete-vocal-e' => 'vocal-e',
    'paquete-vocal-i' => 'vocal-i',
    'paquete-vocal-o' => 'vocal-o',
    'paquete-vocal-u' => 'vocal-u',
];

/** Estaciones que se dejan como están (ver la cabecera). */
const INTOCABLES = ['ordenar_secuencia', 'cuento', 'desafio_final'];


// =====================================================================
//  AYUDANTES
// =====================================================================

/** Quita tildes y deja mayúsculas. La Ñ se conserva: es otra letra. */
function sinTildes(string $s): string
{
    return str_replace(
        ['Á', 'É', 'Í', 'Ó', 'Ú', 'Ü'],
        ['A', 'E', 'I', 'O', 'U', 'U'],
        mb_strtoupper($s)
    );
}

/**
 * ¿La palabra contiene la letra que se está enseñando?
 *
 * `$busca` es una lista porque no siempre coincide con la etiqueta de la
 * letra: «GUE-GUI» es un nombre, y lo que hay dentro de GUITARRA es «GUI».
 * Buscar la etiqueta literal daría siempre «no» y colaría palabras con la
 * letra como respuestas negativas.
 */
function llevaLetra(string $palabra, array $busca): bool
{
    $limpia = sinTildes($palabra);

    foreach ($busca as $b) {
        if (mb_strpos($limpia, sinTildes($b)) !== false) {
            return true;
        }
    }

    return false;
}

/** Las cadenas que de verdad hay que buscar dentro de las palabras. */
function cadenasDe(array $letra): array
{
    return $letra['busca'] ?? [$letra['letra']];
}

/**
 * Reparte la lista en dos montones: el de las cortas y el de las largas.
 *
 * Las estaciones vienen en pares —Puzle I y II, Teclado I y II— y la
 * segunda debe costar más que la primera; si las dos sacaran del mismo
 * montón revuelto, el «II» no significaría nada.
 *
 * Se ordena por número de sílabas y cada montón empieza por su extremo:
 * el fácil desde las más cortas, el difícil desde las más largas. Los dos
 * pueden llegar a solaparse en el medio, y así debe ser — con doce
 * palabras y un cupo de diez, partirlas en dos mitades estancas dejaría
 * las dos estaciones a medio llenar. Lo que importa es que empiecen por
 * extremos opuestos: el niño nota la diferencia en las primeras, que son
 * las que de verdad marcan el tono.
 */
function porDificultad(array $palabras): array
{
    $orden = $palabras;
    usort($orden, static fn($a, $b) => count($a['syls']) <=> count($b['syls'])
                                    ?: mb_strlen($a['w']) <=> mb_strlen($b['w']));

    return [$orden, array_reverse($orden)];
}

/**
 * Ejercicios de sí/no: unas cuantas palabras con la letra y otras sin ella.
 *
 * Los distractores se filtran contra la letra que se enseña. Sin ese
 * filtro, la M usaría «Cama» como respuesta «no» y el niño acertaría
 * marcando «sí» — el ejercicio estaría mal, no él.
 */
function ejerciciosSiNo(array $palabras, array $pozo, array $busca, int $cupo): array
{
    $buenas = array_map(
        static fn($p) => ['e' => $p['e'], 'n' => mb_convert_case($p['w'], MB_CASE_TITLE, 'UTF-8'), 'ok' => true],
        $palabras
    );

    $malas = [];
    foreach ($pozo as $d) {
        if (!llevaLetra($d['n'], $busca)) {
            $malas[] = ['e' => $d['e'], 'n' => $d['n'], 'ok' => false];
        }
    }

    // Mitad y mitad, sin pasarse de lo que hay disponible.
    $nBuenas = min(count($buenas), (int) ceil($cupo / 2));
    $nMalas  = min(count($malas),  $cupo - $nBuenas);

    $items = array_merge(
        array_slice(mezclar($buenas), 0, $nBuenas),
        array_slice(mezclar($malas),  0, $nMalas)
    );

    return mezclar($items);
}

/**
 * Variantes mal escritas de una palabra, cambiando una vocal.
 *
 * Es el mismo truco del sitio anterior (MANO / MONO / MENO) y funciona
 * porque el error que comete un niño al empezar a leer es justo ese.
 * Se descartan las variantes que coincidan con otra palabra real del
 * banco: si «MONO» apareciera como opción falsa de «MANO», las dos serían
 * correctas y una estaría marcada como error.
 */
function variantes(string $palabra, array $reales, int $cuantas = 2): array
{
    $vocales = ['A', 'E', 'I', 'O', 'U'];
    $letras  = preg_split('//u', sinTildes($palabra), -1, PREG_SPLIT_NO_EMPTY);
    $salida  = [];

    // Se recorren las posiciones de vocal de derecha a izquierda: cambiar
    // la última vocal deja la palabra más reconocible que cambiar la
    // primera, y así el ejercicio exige mirar la palabra entera.
    for ($i = count($letras) - 1; $i >= 0 && count($salida) < $cuantas; $i--) {
        if (!in_array($letras[$i], $vocales, true)) {
            continue;
        }
        foreach ($vocales as $v) {
            if ($v === $letras[$i]) {
                continue;
            }
            $copia = $letras;
            $copia[$i] = $v;
            $candidata = implode('', $copia);

            if (in_array($candidata, $reales, true) || in_array($candidata, $salida, true)) {
                continue;
            }
            $salida[] = $candidata;
            break;
        }
    }

    return $salida;
}


// =====================================================================
//  GENERACIÓN
// =====================================================================

/** Construye los datos de una estación según su tipo. */
function datosPara(string $tipo, array $letra, array $pozo, int $posicion): ?array
{
    $palabras = $letra['palabras'];
    $L        = $letra['letra'];
    $busca    = cadenasDe($letra);
    $cupo     = CUPOS[$tipo] ?? 8;

    // La consigna cambia según dónde va la letra en la palabra. Para la Ñ
    // o la X, «¿empieza con...?» sería falso.
    $inicial  = ($letra['posicion'] ?? 'inicial') === 'inicial';
    $pregunta = $inicial ? "¿Empieza con {$L}?" : "¿Lleva la {$L}?";
    $bajada   = $inicial
        ? "Mira el dibujo y decide si empieza con {$L}"
        : "Mira el dibujo y decide si lleva la {$L}";

    [$faciles, $dificiles] = porDificultad($palabras);

    // Las estaciones «II» son las que van en segundo lugar de su pareja.
    $duras = $posicion > 8;

    switch ($tipo) {

        case 'sonido_letra':
            return conTitulo($pregunta, 'Escucha la palabra y decide',
                ejerciciosSiNo($palabras, $pozo, $busca, $cupo));

        case 'seleccion_imagenes':
            $verbo = $inicial ? "empiezan con {$L}" : "llevan la {$L}";
            return conTitulo("Toca todas las que {$verbo}", 'Cuidado: hay intrusos',
                ejerciciosSiNo($palabras, $pozo, $busca, $cupo));

        case 'juego_rapido':
            return conTitulo($pregunta, $bajada,
                ejerciciosSiNo($palabras, $pozo, $busca, $cupo));

        // En los tres casos siguientes se CORTA primero y se baraja
        // después. Al revés, mezclar antes de cortar tiraría por tierra el
        // orden por dificultad y «Teclado II» acabaría con las mismas
        // palabras fáciles que «Teclado I».
        case 'puzle_silabas':
            // Una sola sílaba no es un puzle: no hay nada que ordenar.
            $fuente = array_values(array_filter(
                $duras ? $dificiles : $faciles,
                static fn($p) => count($p['syls']) >= 2
            ));
            return mezclar(array_map(
                static fn($p) => ['w' => $p['w'], 'e' => $p['e'], 'syls' => $p['syls']],
                array_slice($fuente, 0, $cupo)
            ));

        case 'armar_palabras':
            return mezclar(array_map(
                static fn($p) => ['e' => $p['e'], 'w' => $p['w']],
                array_slice($duras ? $dificiles : $faciles, 0, $cupo)
            ));

        case 'teclado':
            /*
             * ─────────────────────────────────────────────────────────
             *  CON TILDE
             * ─────────────────────────────────────────────────────────
             *
             * Antes se quitaban, con este argumento: «se escribe con el
             * teclado y exigir la tilde convertiría el ejercicio en una
             * trampa de acentuación».
             *
             * Es al revés. La pantalla enseña la palabra y el niño la
             * copia, así que lo que aparezca escrito es lo que aprende:
             * poner LEON no le evita una trampa, le enseña a escribir
             * LEON. La tilde en español no es un adorno, distingue
             * palabras.
             *
             * La dureza se compensa donde toca —el motor reconoce cuándo
             * lo único que falla es la tilde y lo dice—, no escribiendo
             * mal la palabra.
             */
            return mezclar(array_values(array_unique(array_map(
                static fn($p) => $p['w'],
                array_slice($duras ? $dificiles : $faciles, 0, $cupo)
            ))));

        case 'pronunciacion':
            // No se evalúa la voz: el valor está en escuchar y repetir.
            return mezclar(array_map(
                static fn($p) => ['e' => $p['e'], 'w' => mb_convert_case($p['w'], MB_CASE_TITLE, 'UTF-8')],
                array_slice(mezclar($palabras), 0, $cupo)
            ));

        case 'completar_palabra':
            // Se esconde la letra que se está enseñando, no una al azar:
            // la estación existe para fijar justo esa.
            $items = [];
            foreach (mezclar($palabras) as $p) {
                $limpia = sinTildes($p['w']);
                $corte  = mb_strpos($limpia, sinTildes($L));
                if ($corte === false || mb_strlen($L) > 1) {
                    continue;   // los dígrafos (CH, QU, GUE) no valen aquí
                }

                /*
                 * La posición se busca en la forma sin tildes —así la Á y
                 * la A cuentan como la misma letra— pero el trozo que se
                 * muestra se corta de la palabra DE VERDAD: quitar la
                 * tilde aquí dejaría al niño completando «AGUILA».
                 *
                 * Vale porque quitar tildes no cambia cuántas letras hay:
                 * las dos cadenas tienen las mismas posiciones.
                 */
                $items[] = [
                    'e'      => $p['e'],
                    'before' => mb_substr($p['w'], 0, $corte),
                    'after'  => mb_substr($p['w'], $corte + 1),
                    'a'      => mb_substr($limpia, $corte, 1),
                ];
                if (count($items) >= $cupo) {
                    break;
                }
            }
            return $items ?: null;

        case 'ortografia':
            $reales = array_map(static fn($p) => sinTildes($p['w']), $palabras);
            $items  = [];
            foreach (array_slice(mezclar($palabras), 0, $cupo) as $p) {
                $correcta = $p['w'];
                $plana    = sinTildes($correcta);
                $opts     = variantes($correcta, $reales);

                /*
                 * Cuando la palabra lleva tilde, la mejor opción falsa es
                 * la misma palabra sin ella.
                 *
                 * Es el error que un niño comete de verdad, y este
                 * minijuego se llama «ortografía»: distinguir ÁGUILA de
                 * AGUILA es exactamente lo que tiene que enseñar. Va la
                 * primera de la lista para que no se pierda si sobran
                 * variantes.
                 */
                if ($plana !== $correcta) {
                    array_unshift($opts, $plana);
                    $opts = array_slice($opts, 0, 2);
                }

                if (!$opts) {
                    continue;   // sin variante posible no hay ejercicio
                }
                $items[] = [
                    'e'       => $p['e'],
                    'correct' => $correcta,
                    'opts'    => mezclar(array_merge([$correcta], $opts)),
                ];
            }
            return $items ?: null;

        case 'memoria':
            // El motor busca las parejas por el símbolo, así que repetir
            // uno haría la pareja ambigua. Se deduplica antes de cortar.
            $simbolos = array_values(array_unique(array_map(
                static fn($p) => $p['e'], $palabras
            )));
            return array_slice(mezclar($simbolos), 0, $cupo);

        case 'emparejar':
            // Aquí el motor busca por la PALABRA, así que se deduplica por
            // ella y no por el dibujo.
            $vistas = $pares = [];
            foreach (mezclar($palabras) as $p) {
                if (in_array($p['w'], $vistas, true)) {
                    continue;
                }
                $vistas[] = $p['w'];
                $pares[]  = ['e' => $p['e'], 'w' => $p['w']];
                if (count($pares) >= $cupo) {
                    break;
                }
            }
            return count($pares) >= 2 ? $pares : null;

        case 'opcion_multiple':
            /*
             * El «¿cuántas A tiene AVIÓN?» de los paquetes por vocal.
             * Solo tiene sentido con una letra suelta: contar cuántos «CH»
             * hay en una palabra es otro ejercicio distinto.
             */
            if (mb_strlen($L) > 1) {
                return null;
            }
            $items = [];
            foreach (mezclar($palabras) as $p) {
                $limpia = sinTildes($p['w']);
                $veces  = mb_substr_count($limpia, sinTildes($L));
                if ($veces < 1) {
                    continue;
                }
                // Opciones alrededor del número correcto, sin repetirlo y
                // sin bajar de uno: «0» nunca es la respuesta aquí.
                $opciones = [$veces];
                for ($k = 1; count($opciones) < 3; $k++) {
                    if ($veces - $k >= 1) $opciones[] = $veces - $k;
                    if (count($opciones) < 3) $opciones[] = $veces + $k;
                }
                sort($opciones);
                $items[] = [
                    'enunciado'  => "¿Cuántas {$L} tiene {$p['w']}?",
                    'visual'     => $p['w'],
                    'tipoVisual' => 'texto',
                    'opciones'   => array_map('strval', $opciones),
                    'correcta'   => (int) array_search($veces, $opciones, true),
                ];
                if (count($items) >= $cupo) {
                    break;
                }
            }
            return $items ?: null;

        case 'crucigrama':
            /*
             * Crucigrama de imágenes: la pista es el dibujo, sin una sola
             * palabra escrita. Así lo puede resolver un niño que todavía
             * no lee enunciados, y es el ejercicio más completo de la
             * letra: hay que reconocer el dibujo, recordar la palabra y
             * escribirla entera de memoria.
             *
             * Sin tildes, porque se teclea. La Ñ sí se conserva: en la
             * letra Ñ es justo lo que se está practicando.
             */
            $entradas = [];
            foreach ($palabras as $p) {
                $limpia = sinTildes($p['w']);
                if (mb_strlen($limpia) >= 3 && mb_strlen($limpia) <= 9) {
                    $entradas[] = ['w' => $limpia, 'e' => $p['e']];
                }
            }

            if (count($entradas) < 4) {
                return null;   // con menos de cuatro no hay cruces que valgan
            }

            try {
                return crucigrama(array_slice(mezclar($entradas), 0, $cupo), 13);
            } catch (Throwable $ex) {
                // Puede pasar con letras de vocabulario muy corto: no
                // comparten letras suficientes para cruzarse.
                return null;
            }

        case 'sopa_letras':
            // Sin tildes ni palabras de una sílaba muy corta: la cuadrícula
            // se rellena con el alfabeto sin acentos y una palabra de dos
            // letras aparecería por casualidad en cualquier parte.
            $lista = [];
            foreach ($palabras as $p) {
                $limpia = sinTildes($p['w']);
                if (mb_strlen($limpia) >= 4 && mb_strlen($limpia) <= 10) {
                    $lista[] = $limpia;
                }
            }
            $lista = array_slice(mezclar(array_values(array_unique($lista))), 0, $cupo);
            return $lista ? sopa($lista, 12) : null;
    }

    return null;
}


// =====================================================================
//  RECORRIDO
// =====================================================================

echo str_repeat('=', 76), "\n";
echo "  ENRIQUECER EL VOCABULARIO DE AVENTURA DE LAS LETRAS\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 76), "\n\n";

$pozo    = $banco['distractores'];
$avisos  = [];
$totalEst = $totalNuevas = 0;
$antes = $despues = [];

printf("  %-16s %-8s %8s %8s %6s   %s\n",
    'actividad', 'letra', 'palabras', 'ests', 'nuevas', 'antes → después (prom.)');
echo '  ', str_repeat('-', 78), "\n";

// Cada actividad con su banco: las propias, más las que reutilizan otro.
$objetivos = $banco['letras'];
foreach (ALIAS as $slugAlias => $slugBanco) {
    if (isset($banco['letras'][$slugBanco])) {
        $objetivos[$slugAlias] = $banco['letras'][$slugBanco];
    }
}

foreach ($objetivos as $slug => $letra) {

    $actividad = traerUno('SELECT id, title FROM activities WHERE slug = ?', [$slug]);

    if (!$actividad) {
        $avisos[] = "$slug: la actividad no existe";
        continue;
    }

    $estaciones = traerTodo(
        'SELECT id, position, game_type, config FROM activity_stations
          WHERE activity_id = ? ORDER BY position',
        [$actividad['id']]
    );

    if (!$estaciones) {
        $avisos[] = "$slug: no tiene estaciones que enriquecer";
        continue;
    }

    $cuentaAntes = $cuentaDespues = [];
    $tocadas = 0;

    foreach ($estaciones as $e) {

        $config = json_decode($e['config'] ?? 'null', true) ?: [];
        $viejos = $config['datos'] ?? [];
        $nAntes = is_array($viejos)
            ? (isset($viejos['items']) ? count($viejos['items'])
               : (isset($viejos['words']) ? count($viejos['words'])
                  : (isset($viejos['slides']) ? count($viejos['slides']) + count($viejos['qs'] ?? [])
                     : count($viejos))))
            : 0;

        $cuentaAntes[] = $nAntes;

        if (in_array($e['game_type'], INTOCABLES, true)) {
            $cuentaDespues[] = $nAntes;
            continue;
        }

        $datos = datosPara($e['game_type'], $letra, $pozo, (int) $e['position']);

        /*
         * AUTOCOMPROBACIÓN de los ejercicios de sí/no.
         *
         * Cada `ok` tiene que coincidir con si la palabra lleva de verdad
         * la letra. Si no coincide, el niño acierta y el juego le dice que
         * se equivocó, que es el error más injusto que puede cometer una
         * actividad — y el más difícil de ver, porque nada falla.
         *
         * Ya estuvo a punto de pasar: el filtro buscaba la cadena literal
         * «GUE-GUI» dentro de las palabras y nunca la encontraba, así que
         * GUITARRA podía colarse como respuesta «no». Se salvó por el
         * azar de la semilla. Esto lo comprueba de verdad.
         */
        if (is_array($datos) && isset($datos['items'])) {
            foreach ($datos['items'] as $it) {
                if (!isset($it['ok'], $it['n'])) {
                    continue;
                }
                if (llevaLetra($it['n'], cadenasDe($letra)) !== (bool) $it['ok']) {
                    $avisos[] = sprintf(
                        '%s · estación %s: «%s» está marcada como %s y es al revés',
                        $slug, $e['position'], $it['n'], $it['ok'] ? 'correcta' : 'incorrecta'
                    );
                }
            }
        }

        if ($datos === null || $datos === []) {
            $avisos[] = "$slug · estación {$e['position']} ({$e['game_type']}): sin datos suficientes, se deja como estaba";
            $cuentaDespues[] = $nAntes;
            continue;
        }

        $nDespues = isset($datos['items']) ? count($datos['items'])
                  : (isset($datos['words']) ? count($datos['words']) : count($datos));
        $cuentaDespues[] = $nDespues;
        $tocadas++;

        if ($aplicar) {
            // Se conserva todo lo demás del config —el sonido de la letra,
            // por ejemplo— y solo se reemplazan los datos.
            $config['datos'] = $datos;
            if ($e['game_type'] === 'sonido_letra' && !empty($letra['sonido'])) {
                $config['sonido'] = $letra['sonido'];
            }

            ejecutar(
                'UPDATE activity_stations SET config = ? WHERE id = ?',
                [json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $e['id']]
            );
        }
    }

    // ── Estaciones nuevas ────────────────────────────────────────────
    //
    // Se añaden solo si la actividad todavía no las tiene, para que volver
    // a ejecutar el script no las duplique.
    $tiposQueTiene = array_column($estaciones, 'game_type');
    $ultima        = (int) max(array_column($estaciones, 'position'));
    $anadidas      = 0;

    foreach (NUEVAS as [$tipo, $titulo, $icono, $descripcion]) {

        if (in_array($tipo, $tiposQueTiene, true)) {
            continue;
        }

        $datos = datosPara($tipo, $letra, $pozo, $ultima + 1);

        if ($datos === null || $datos === []) {
            $avisos[] = "$slug: no se pudo armar el $tipo (vocabulario insuficiente)";
            continue;
        }

        $ultima++;
        $anadidas++;
        $cuentaDespues[] = isset($datos['palabras']) ? count($datos['palabras']) : count($datos);

        if ($aplicar) {
            insertar(
                'INSERT INTO activity_stations
                    (activity_id, position, title, description, icon, game_type, config, is_free)
                 VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                [
                    $actividad['id'], $ultima,
                    str_replace('{L}', $letra['letra'], $titulo),
                    $descripcion, $icono, $tipo,
                    json_encode(['datos' => $datos], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                ]
            );
        }
    }

    $promAntes   = array_sum($cuentaAntes) / count($cuentaAntes);
    $promDespues = array_sum($cuentaDespues) / count($cuentaDespues);
    $antes[]   = $promAntes;
    $despues[] = $promDespues;
    $totalEst += $tocadas;
    $totalNuevas += $anadidas;

    printf(
        "  %-16s %-8s %8d %8d %6s   %4.1f → %4.1f\n",
        $slug, $letra['letra'], count($letra['palabras']), $tocadas,
        $anadidas ? '+' . $anadidas : '·', $promAntes, $promDespues
    );
}


// =====================================================================
//  INFORME
// =====================================================================

echo "\n", str_repeat('-', 76), "\n";
printf("  Actividades tocadas   : %d\n", count($antes));
printf("  Estaciones regeneradas: %d\n", $totalEst);
printf("  Estaciones nuevas     : %d (crucigramas de imágenes)\n", $totalNuevas);

if ($antes) {
    printf(
        "  Promedio de palabras  : %.1f → %.1f por estación\n",
        array_sum($antes) / count($antes),
        array_sum($despues) / count($despues)
    );
}

if ($avisos) {
    echo "\n  Avisos:\n";
    foreach (array_unique($avisos) as $a) {
        echo "    · $a\n";
    }
}

echo $aplicar
    ? "\n  Aplicado. Las historias y los desafíos finales no se tocaron.\n"
    : "\n  Simulación. Para escribir: php database/enriquecer-letras.php --aplicar\n";

echo str_repeat('=', 76), "\n";
