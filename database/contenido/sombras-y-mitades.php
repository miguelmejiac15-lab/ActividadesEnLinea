<?php
/**
 * sombras-y-mitades.php — Preescolar: unir cada dibujo con su sombra o con su otra mitad
 *
 * Dos actividades de observación para tres a seis años, con el minijuego
 * `parejas_dibujo`: a un lado los dibujos revueltos, al otro sus sombras
 * (o sus mitades), y el niño toca uno de cada lado para unirlos.
 *
 * Lo que entrena es reconocer una figura por su FORMA cuando falta el
 * color (sombras) o reconstruirla cuando falta un trozo (mitades): la
 * base de leer siluetas, letras y mapas más adelante.
 *
 * CÓMO SE ESCOGIERON LOS DIBUJOS
 *
 *   - Sombras: dentro de una misma ronda, siluetas que no se parezcan.
 *     🍎 y 🍊 son dos círculos negros idénticos; una ronda que los junte
 *     se resuelve a ciegas. Por eso la jirafa va con el caracol y el pato,
 *     no con el caballo.
 *   - Mitades: dentro de una ronda, colores bien distintos. Media sandía
 *     se reconoce por el verde y el rojo; si en la misma ronda hubiera
 *     medio kiwi, el niño tendría que adivinar.
 *
 * NIVELES (estándar de actividades de preescolar)
 *
 *   Básico   3 parejas por ronda
 *   Medio    4 parejas por ronda
 *   Avanzado 5 o 6 parejas por ronda
 *
 * Y un repaso de opción múltiple —«¿De quién es esta sombra?»— que pide
 * lo mismo al revés: ver la sombra sola y encontrar al dueño.
 *
 * Declara la categoría y los bloques de Pensamiento tal como los deja
 * `pensamiento-ampliacion.php`: el sembrador hace UPDATE sobre ambos y
 * copiarlos a mano sería invitar a que un día diverjan.
 */

declare(strict_types=1);

$pensamiento = require __DIR__ . '/pensamiento-ampliacion.php';

/** Una pareja: el dibujo y cómo se llama, para decirlo en voz alta. */
if (!function_exists('parejaDibujo')) {
function parejaDibujo(string $e, string $n): array
{
    return ['e' => $e, 'n' => $n];
}
}

return [

'categoria' => $pensamiento['categoria'],
'bloques'   => $pensamiento['bloques'],

'actividades' => [

[
    'slug'  => 'cada-uno-con-su-sombra',
    'title' => 'Cada uno con su sombra',
    'description' => 'Une cada dibujo con su sombra: la misma figura, pero toda en negro.',
    'objective' => 'Reconocer una figura por su forma cuando no tiene color, y relacionar objeto y silueta.',
    'icon' => '👤', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 8, 'tags' => ['observacion', 'atencion', 'juego'],
    'estaciones' => [

        // Básico: animales de silueta inconfundible, tres por ronda.
        est('Sombras de animales', 'Tres animales y sus sombras', '🐘', 'parejas_dibujo', [
            'modo'   => 'sombra',
            'rondas' => [
                [parejaDibujo('🐘', 'elefante'), parejaDibujo('🐇', 'conejo'),  parejaDibujo('🐟', 'pez')],
                [parejaDibujo('🦒', 'jirafa'),   parejaDibujo('🐌', 'caracol'), parejaDibujo('🦆', 'pato')],
                [parejaDibujo('🐢', 'tortuga'),  parejaDibujo('🦋', 'mariposa'), parejaDibujo('🐍', 'serpiente')],
            ],
        ]),

        // Medio: cosas de la casa, cuatro por ronda.
        est('Sombras de la casa', 'Cosas que usamos todos los días', '☂️', 'parejas_dibujo', [
            'modo'   => 'sombra',
            'rondas' => [
                [parejaDibujo('☂️', 'paraguas'), parejaDibujo('✂️', 'tijeras'), parejaDibujo('🔑', 'llave'),  parejaDibujo('🪑', 'silla')],
                [parejaDibujo('🎸', 'guitarra'), parejaDibujo('🚲', 'bicicleta'), parejaDibujo('👟', 'zapato'), parejaDibujo('💡', 'bombillo')],
            ],
        ]),

        // Avanzado: todo revuelto, seis por ronda.
        est('Sombras revueltas', 'Animales, cosas y comida juntos', '🌳', 'parejas_dibujo', [
            'modo'   => 'sombra',
            'rondas' => [
                [parejaDibujo('🐘', 'elefante'), parejaDibujo('🚗', 'carro'), parejaDibujo('🌳', 'árbol'),
                 parejaDibujo('⭐', 'estrella'), parejaDibujo('🍌', 'banano'), parejaDibujo('✈️', 'avión')],
                [parejaDibujo('🦀', 'cangrejo'), parejaDibujo('🏠', 'casa'),  parejaDibujo('🎈', 'globo'),
                 parejaDibujo('🐦', 'pájaro'),   parejaDibujo('🍐', 'pera'),  parejaDibujo('⛵', 'velero')],
            ],
        ]),

        // Repaso al revés: la sombra sola, y a buscar al dueño.
        est('¿De quién es la sombra?', 'Mira la sombra y busca su dibujo', '🔦', 'opcion_multiple', [
            omp('¿De quién es esta sombra?', ['🐘', '🐭', '🐟'], '🐘', '🐘', 'sombra'),
            omp('¿De quién es esta sombra?', ['🐌', '🦒', '🐢'], '🦒', '🦒', 'sombra'),
            omp('¿De quién es esta sombra?', ['🔑', '☂️', '🪑'], '☂️', '☂️', 'sombra'),
            omp('¿De quién es esta sombra?', ['🚲', '🚗', '✈️'], '✈️', '✈️', 'sombra'),
            omp('¿De quién es esta sombra?', ['🦀', '🦋', '🐇'], '🦋', '🦋', 'sombra'),
        ]),
    ],
],

[
    'slug'  => 'une-las-mitades',
    'title' => 'Une las mitades',
    'description' => 'Cada dibujo está partido en dos: junta cada mitad con la suya.',
    'objective' => 'Completar una figura a partir de sus partes, fijándose en colores y formas.',
    'icon' => '🧩', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 8, 'tags' => ['observacion', 'atencion', 'juego'],
    'estaciones' => [

        // Básico: frutas de colores muy distintos, tres por ronda.
        est('Mitades de frutas', 'Tres frutas partidas por la mitad', '🍉', 'parejas_dibujo', [
            'modo'   => 'mitad',
            'rondas' => [
                [parejaDibujo('🍉', 'sandía'), parejaDibujo('🍌', 'banano'), parejaDibujo('🍇', 'uvas')],
                [parejaDibujo('🍓', 'fresa'),  parejaDibujo('🍍', 'piña'),   parejaDibujo('🫐', 'arándanos')],
                [parejaDibujo('🍊', 'naranja'), parejaDibujo('🥝', 'kiwi'),  parejaDibujo('🍒', 'cerezas')],
            ],
        ]),

        // Medio: caras de animales, cuatro por ronda.
        est('Mitades de animales', 'Junta las dos mitades de cada cara', '🐶', 'parejas_dibujo', [
            'modo'   => 'mitad',
            'rondas' => [
                [parejaDibujo('🐶', 'perro'), parejaDibujo('🐸', 'rana'),  parejaDibujo('🐷', 'cerdo'), parejaDibujo('🐧', 'pingüino')],
                [parejaDibujo('🦁', 'león'),  parejaDibujo('🐼', 'panda'), parejaDibujo('🐙', 'pulpo'), parejaDibujo('🐝', 'abeja')],
            ],
        ]),

        // Avanzado: todo revuelto, cinco por ronda.
        est('Mitades revueltas', 'Cosas, animales y plantas', '🚀', 'parejas_dibujo', [
            'modo'   => 'mitad',
            'rondas' => [
                [parejaDibujo('🚗', 'carro'), parejaDibujo('🏠', 'casa'), parejaDibujo('🌈', 'arcoíris'),
                 parejaDibujo('🍄', 'hongo'), parejaDibujo('🚀', 'cohete')],
                [parejaDibujo('⚽', 'balón'), parejaDibujo('🌻', 'girasol'), parejaDibujo('🐢', 'tortuga'),
                 parejaDibujo('🎁', 'regalo'), parejaDibujo('🦋', 'mariposa')],
            ],
        ]),

        // Repaso: una mitad sola, y a decir de qué dibujo es.
        est('¿De qué es esta mitad?', 'Mira la mitad y busca el dibujo entero', '🔍', 'opcion_multiple', [
            omp('¿De qué dibujo es esta mitad?', ['🍌', '🍉', '🍇'], '🍉', '🍉', 'mitad'),
            omp('¿De qué dibujo es esta mitad?', ['🐸', '🐷', '🐶'], '🐸', '🐸', 'mitad'),
            omp('¿De qué dibujo es esta mitad?', ['🌻', '🍄', '🚗'], '🍄', '🍄', 'mitad'),
            omp('¿De qué dibujo es esta mitad?', ['🐼', '🦁', '🐝'], '🦁', '🦁', 'mitad'),
            omp('¿De qué dibujo es esta mitad?', ['🎁', '🚀', '🌈'], '🌈', '🌈', 'mitad'),
        ]),
    ],
],

],
];
