<?php
/**
 * migracion-enunciados-claros.php — Que cada pregunta diga qué hay que hacer
 *
 *     php database/migracion-enunciados-claros.php             # simulacion
 *     php database/migracion-enunciados-claros.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE
 * ---------------------------------------------------------------------
 *
 * Al pasar los mundos del sitio anterior al motor (migrar-mundos.php),
 * el normalizador solo reconocía el enunciado en `q`, `s`, `word` o
 * `circuit`. Ocho mundos lo guardaban en otra parte —`p` (problema),
 * `hint` (pista), `seq` (la serie del patrón), `obj`, `syllables`— y
 * esas preguntas quedaron con el texto de relleno «Elige la respuesta
 * correcta». Después, dibujos.php les puso el dibujo de esa frase: ✅.
 *
 * El resultado: en «Patrones Mágicos» el niño veía un ✅ y cuatro
 * bolitas de colores, sin la fila que tenía que continuar. Ni un adulto
 * sabía qué elegir. Lo mismo en los problemas de sumas y restas (sin el
 * problema), en las figuras (sin la figura) y en contar sílabas (sin la
 * palabra). Los «Repaso» y «Desafío final» generados a partir de esas
 * estaciones heredaron el mismo defecto.
 *
 * Aquí se devuelve a cada pregunta lo que el original mostraba, escrito
 * para un niño: una instrucción corta y lo que hay que mirar.
 *
 * Solo toca preguntas que todavía dicen «Elige la respuesta correcta», y
 * antes de escribir comprueba que la respuesta correcta guardada sea la
 * esperada: si alguien ya las corrigió a mano, o el contenido cambió, no
 * se pisa nada. Repetirla no cambia nada.
 *
 * Toca solo `activity_stations.config` (catálogo). Nada de usuarios ni
 * de progreso.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

const GENERICO = 'Elige la respuesta correcta';

echo "===============================================================\n";
echo "  MIGRACION - Enunciados claros\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";


/*
 * Cada pregunta: [respuesta correcta, enunciado, visual, tipoVisual]
 * y, si hace falta, un quinto elemento con opciones corregidas.
 *
 * El orden es el de la estación. La respuesta correcta sirve de control:
 * si la que hay guardada no coincide, esa pregunta no se toca.
 */
$S = static fn(string ...$v): array => [...$v, '?'];   // serie con hueco al final

$ARREGLOS = [

    'laberinto-de-logica' => [
        // Patrones Mágicos: sin la fila, no había pregunta.
        1 => [
            ['🔵', 'Mira la fila. ¿Qué va en el hueco?', $S('🔴', '🔵', '🔴', '🔵', '🔴'), 'serie'],
            ['🌙', 'Mira la fila. ¿Qué va en el hueco?', $S('⭐', '⭐', '🌙', '⭐', '⭐'), 'serie'],
            ['🐶', 'Mira la fila. ¿Qué va en el hueco?', $S('🐱', '🐶', '🐱', '🐶', '🐱'), 'serie'],
            ['🔵', 'Mira la fila. ¿Qué va en el hueco?', $S('🔺', '🔺', '🔵', '🔺', '🔺'), 'serie'],
            ['🍊', 'Mira la fila. ¿Qué va en el hueco?', $S('🍎', '🍌', '🍊', '🍎', '🍌'), 'serie'],
            ['☀️', 'Mira la fila. ¿Qué va en el hueco?', $S('🌧️', '☀️', '🌧️', '☀️', '🌧️'), 'serie'],
        ],
        // Secuencias de Números
        2 => [
            ['8',  '¿Qué número falta en la fila?', ['2', '4', '6', '?', '10'], 'serie'],
            ['9',  '¿Qué número falta en la fila?', ['1', '3', '5', '7', '?'], 'serie'],
            ['20', '¿Qué número falta en la fila?', ['5', '10', '15', '?', '25'], 'serie'],
            ['8',  '¿Qué número falta en la fila?', ['1', '2', '4', '?', '16'], 'serie'],
            ['7',  '¿Qué número falta en la fila?', ['10', '9', '8', '?', '6'], 'serie'],
            ['15', '¿Qué número falta en la fila?', ['3', '6', '9', '12', '?'], 'serie'],
            ['8',  '¿Qué número falta en la fila?', ['2', '4', '?', '16', '32'], 'serie'],
        ],
        // Gran Desafío Lógico: el problema se había perdido entero.
        3 => [
            ['4 patas', 'Todos los perros tienen 4 patas. Toby es un perro. ¿Cuántas patas tiene Toby?', '🐶', 'emoji'],
            ['Ayer', 'Hoy hace más calor que ayer. Mañana hará más calor que hoy. ¿Qué día hizo más frío?', '🌡️', 'emoji'],
            ['5', 'Ana tiene 10 caramelos. Le da la mitad a su hermana. ¿Cuántos le quedan a Ana?', '🍬', 'emoji'],
            ['6', 'Hay 3 llaves y 3 candados. Cada llave abre un solo candado. ¿Cuántas veces, como mucho, tienes que probar para abrirlos todos?', '🔑', 'emoji'],
            ['4 pollitos', 'Un huevo tarda 3 días en abrirse. Hoy ponemos 4 huevos. ¿Cuántos pollitos habrá en 3 días?', '🐣', 'emoji'],
        ],
    ],

    'canon-de-las-restas' => [
        3 => [
            ['6', 'Ana tenía 10 manzanas. Regaló 4 a sus amigos. ¿Cuántas le quedan?', '🍎', 'emoji'],
            ['9', 'En el parque había 15 globos. El viento se llevó 6. ¿Cuántos globos quedan?', '🎈', 'emoji'],
            ['7', 'El pescador tenía 12 peces. Devolvió 5 al mar. ¿Cuántos le quedan?', '🐟', 'emoji'],
            ['9', 'La biblioteca tenía 18 libros. Prestó 9. ¿Cuántos libros quedan?', '📚', 'emoji'],
            // El original decía «se ocultaron 11 nubes»: lo que se oculta son estrellas.
            ['9', 'En el cielo había 20 estrellas. Una nube tapó 11. ¿Cuántas estrellas se ven ahora?', '🌟', 'emoji'],
        ],
    ],

    'valle-de-las-sumas' => [
        3 => [
            ['8',  'Ana tiene 5 manzanas. Su mamá le regala 3 más. ¿Cuántas manzanas tiene ahora?', '🍎', 'emoji'],
            ['10', 'En un árbol hay 4 pájaros. Llegan 6 más. ¿Cuántos pájaros hay en total?', '🐦', 'emoji'],
            ['12', 'Un niño tiene 7 pelotas. Su amigo le presta 5. ¿Cuántas pelotas tienen entre los dos?', '⚽', 'emoji'],
            ['14', 'En la biblioteca hay 8 libros de cuentos y 6 de ciencias. ¿Cuántos libros hay?', '📚', 'emoji'],
            ['16', 'En el jardín hay 9 flores amarillas y 7 flores rojas. ¿Cuántas flores hay en total?', '🌼', 'emoji'],
        ],
    ],

    'carrera-de-palabras' => [
        3 => [
            ['Perro',  'Es un animal que ladra. ¿Cuál está bien escrita?', '🐕', 'emoji'],
            ['Libro',  'Sirve para leer. ¿Cuál está bien escrita?', '📚', 'emoji'],
            // «Bananna» estaba dos veces: dos botones iguales confunden.
            ['Banana', 'Es una fruta amarilla. ¿Cuál está bien escrita?', '🍌', 'emoji',
                ['Bananna', 'Banana', 'Vanana', 'Banama']],
            ['Lápiz',  'Sirve para escribir. ¿Cuál está bien escrita?', '✏️', 'emoji'],
            ['Sol',    'Brilla en el cielo. ¿Cuál está bien escrita?', '☀️', 'emoji'],
            ['Casa',   'Es donde vivimos. ¿Cuál está bien escrita?', '🏠', 'emoji'],
        ],
    ],

    'estacion-musical' => [
        // Aquí el dibujo sí había llegado; solo faltaba la pregunta.
        1 => array_map(
            static fn(array $p) => [$p[0], '¿Cómo se llama este instrumento?', $p[1], 'emoji'],
            [['Guitarra', '🎸'], ['Piano', '🎹'], ['Tambor / Batería', '🥁'], ['Trompeta', '🎺'],
             ['Violín', '🎻'], ['Acordeón', '🪗'], ['Saxofón', '🎷'], ['Bongó / Conga', '🪘']]
        ),
        3 => array_map(
            static fn(array $p) => [$p[0], '¿A qué familia pertenece este instrumento?', $p[1], 'texto'],
            [['Cuerdas 🎻', '🎸 Guitarra'], ['Percusión 🥁', '🥁 Tambor'], ['Viento 🎺', '🎺 Trompeta'],
             ['Teclas 🎹', '🎹 Piano'], ['Cuerdas 🎻', '🎻 Violín'], ['Viento 🎺', '🎷 Saxofón'],
             ['Percusión 🥁', '🪘 Bongó'], ['Teclas 🎹', '🪗 Acordeón']]
        ),
    ],

    'isla-de-las-figuras' => [
        // El original dibujaba cada figura en SVG; no había emoji para
        // rectángulo ni pentágono. Ahora son íconos propios.
        1 => array_map(
            static fn(array $p) => [$p[0], '¿Cómo se llama esta figura?', 'icono:figura-' . $p[1], 'emoji'],
            [['Círculo', 'circulo'], ['Cuadrado', 'cuadrado'], ['Triángulo', 'triangulo'],
             ['Rectángulo', 'rectangulo'], ['Diamante', 'diamante'], ['Pentágono', 'pentagono']]
        ),
        2 => [
            ['Círculo',    '¿Qué forma tiene la pizza?', '🍕', 'emoji'],
            ['Rectángulo', '¿Qué forma tiene la pantalla del televisor?', '📺', 'emoji'],
            ['Diamante',   '¿Qué forma tiene esta figura?', '🔶', 'emoji'],
            ['Cuadrado',   '¿Qué forma tiene cada cara del dado?', '🎲', 'emoji'],
            ['Triángulo',  '¿A qué figura se parece la montaña?', '🏔️', 'emoji'],
            ['Círculo',    '¿Qué forma tiene la moneda?', '🪙', 'emoji'],
        ],
    ],

    'la-neo-computadora' => [
        1 => [
            ['Monitor', 'Muestra las imágenes y las letras. ¿Cómo se llama?', '🖥️', 'emoji'],
            ['Teclado', 'Tiene muchas teclas con letras y números. ¿Cómo se llama?', '⌨️', 'emoji'],
            ['Mouse / Ratón', 'Lo mueves con la mano para mover la flecha de la pantalla. ¿Cómo se llama?', '🖱️', 'emoji'],
            ['Impresora', 'Pasa documentos y fotos al papel. ¿Cómo se llama?', '🖨️', 'emoji'],
            ['Disco Duro', 'Guarda toda la información del computador. ¿Cómo se llama?', '💾', 'emoji'],
            ['Wi-Fi / Router', 'Te conecta a internet sin cables. ¿Cómo se llama?', '📡', 'emoji'],
            ['Batería', 'Guarda energía para que el portátil funcione sin cable. ¿Cómo se llama?', '🔋', 'emoji'],
            ['Auriculares', 'Te los pones en las orejas para escuchar. ¿Cómo se llaman?', '🎧', 'emoji'],
        ],
    ],

    'ritmo-de-silabas' => [
        // El original mostraba la palabra partida y la decía en voz alta.
        1 => [
            ['1', 'Aplaude con cada parte: SOL. ¿Cuántas palmadas diste?', '☀️', 'emoji'],
            ['2', 'Aplaude con cada parte: CA - SA. ¿Cuántas palmadas diste?', '🏠', 'emoji'],
            ['3', 'Aplaude con cada parte: PE - LO - TA. ¿Cuántas palmadas diste?', '⚽', 'emoji'],
            ['4', 'Aplaude con cada parte: MA - RI - PO - SA. ¿Cuántas palmadas diste?', '🦋', 'emoji'],
            ['1', 'Aplaude con cada parte: PAN. ¿Cuántas palmadas diste?', '🍞', 'emoji'],
        ],
    ],
];


$cambiadas = 0;
$saltadas  = 0;

foreach ($ARREGLOS as $slug => $porEstacion) {
    $estaciones = traerTodo(
        'SELECT s.id, s.position, s.title, s.game_type, s.config
           FROM activity_stations s
           JOIN activities a ON a.id = s.activity_id
          WHERE a.slug = ?
          ORDER BY s.position',
        [$slug]
    );

    if (!$estaciones) {
        echo "  [--] $slug: no existe, se salta.\n";
        continue;
    }

    /*
     * Primero las estaciones de opción múltiple. Lo que se arregla en
     * cada una queda en $arregladas para reconocer después sus copias
     * en los retos generados.
     */
    $arregladas = [];   // lista de [opciones (texto), correcta, enunciado, visual, tipoVisual]
    $nuevas     = [];   // id => config nueva

    foreach ($estaciones as $st) {
        $pos = (int) $st['position'];
        if ($st['game_type'] !== 'opcion_multiple' || !isset($porEstacion[$pos])) {
            continue;
        }

        $config = json_decode((string) $st['config'], true);
        $datos  = $config['datos'] ?? null;
        if (!is_array($datos)) {
            continue;
        }

        $tocada = false;

        foreach ($porEstacion[$pos] as $k => $p) {
            [$ans, $enunciado, $visual, $tipo] = $p;
            $opcionesNuevas = $p[4] ?? null;

            $it = $datos[$k] ?? null;
            if (!is_array($it)) {
                continue;
            }

            $opcionesTexto = array_map('strval', $it['opciones'] ?? []);
            $correcta      = (int) ($it['correcta'] ?? -1);

            // Para reconocer las copias se usa lo que había antes.
            $arregladas[] = [$opcionesTexto, $correcta, $enunciado, $visual, $tipo, $opcionesNuevas];

            if (($it['enunciado'] ?? '') !== GENERICO) {
                continue;   // ya está bien
            }
            if (($opcionesTexto[$correcta] ?? null) !== $ans) {
                echo "  [!!] $slug E$pos pregunta " . ($k + 1) . ": la respuesta guardada no es «$ans», no se toca.\n";
                $saltadas++;
                continue;
            }

            $datos[$k]['enunciado']  = $enunciado;
            $datos[$k]['visual']     = $visual;
            $datos[$k]['tipoVisual'] = $tipo;
            if ($opcionesNuevas !== null) {
                $datos[$k]['opciones'] = $opcionesNuevas;
                $datos[$k]['correcta'] = (int) array_search($ans, $opcionesNuevas, true);
                unset($datos[$k]['dibujos']);
            }
            $tocada = true;
            $cambiadas++;
        }

        if ($tocada) {
            $config['datos'] = $datos;
            $nuevas[(int) $st['id']] = ['config' => $config, 'rotulo' => "E$pos {$st['title']}"];
        }
    }

    /*
     * Después los «Repaso» y «Desafío final» generados: copian preguntas
     * de las estaciones en formato {q, opts, a, e}. Se reconocen por sus
     * opciones y su respuesta, y cada original se usa una sola vez para
     * que dos preguntas con las mismas opciones no salgan iguales.
     */
    $usadas = [];

    foreach ($estaciones as $st) {
        if ($st['game_type'] !== 'desafio_final') {
            continue;
        }

        $config = json_decode((string) $st['config'], true);
        $datos  = $config['datos'] ?? null;
        if (!is_array($datos)) {
            continue;
        }

        $tocada = false;

        foreach ($datos as $k => $q) {
            if (!is_array($q) || ($q['q'] ?? '') !== GENERICO) {
                continue;
            }

            $opts = array_map('strval', $q['opts'] ?? []);
            $a    = (int) ($q['a'] ?? -1);

            $elegida = null;
            foreach ($arregladas as $n => $orig) {
                if (!isset($usadas[$st['id']][$n]) && $orig[0] === $opts && $orig[1] === $a) {
                    $elegida = $n;
                    break;
                }
            }

            if ($elegida === null) {
                echo "  [!!] $slug E{$st['position']} pregunta " . ($k + 1) . ": no se encontró su original, no se toca.\n";
                $saltadas++;
                continue;
            }

            $usadas[$st['id']][$elegida] = true;
            [, , $enunciado, $visual, $tipo, $opcionesNuevas] = $arregladas[$elegida];

            $datos[$k]['q'] = $enunciado;
            if ($tipo === 'serie') {
                $datos[$k]['serie'] = $visual;
                unset($datos[$k]['e']);
            } else {
                $datos[$k]['e'] = $visual;
            }
            if ($opcionesNuevas !== null) {
                $ans = $opts[$a];
                $datos[$k]['opts'] = $opcionesNuevas;
                $datos[$k]['a']    = (int) array_search($ans, $opcionesNuevas, true);
                unset($datos[$k]['dibujos']);
            }
            $tocada = true;
            $cambiadas++;
        }

        if ($tocada) {
            $config['datos'] = $datos;
            $nuevas[(int) $st['id']] = ['config' => $config, 'rotulo' => "E{$st['position']} {$st['title']}"];
        }
    }

    foreach ($nuevas as $id => $n) {
        echo "  [->] $slug · {$n['rotulo']}\n";
        if ($aplicar) {
            ejecutar(
                'UPDATE activity_stations SET config = ? WHERE id = ?',
                [json_encode($n['config'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES), $id]
            );
        }
    }

    if (!$nuevas) {
        echo "  [ok] $slug ya estaba bien.\n";
    }
}

echo "\n$cambiadas pregunta(s) " . ($aplicar ? 'corregidas' : 'por corregir') . ", $saltadas saltada(s).\n";

if (!$aplicar) {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
