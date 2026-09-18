<?php
/**
 * letras-juegos.php — Los dos packs de juegos de vocales
 *
 * Estas dos actividades venían del sitio anterior (`Juegos_Vocales_1.html`
 * y `Juegos_Vocales_2.html`) y fueron las únicas que la migración dejó a
 * medias: quedaron publicadas en el catálogo, con ficha y con ícono, pero
 * con `engine = legacy_html` y CERO estaciones. Un niño entraba a la ficha
 * y encontraba «todavía no está lista». Una actividad publicada que no se
 * puede jugar es peor que una que no aparece.
 *
 * No se portaron entonces porque los cuatro minijuegos originales —naves,
 * nubes, globos y pesca— eran de acción en tiempo real: objetos que caen o
 * cruzan la pantalla y hay que tocar los que llevan la vocal correcta.
 * Ninguno de los diecinueve motores hace eso.
 *
 * Pero la MECÁNICA PEDAGÓGICA de los cuatro es la misma y sí está cubierta:
 * «aparece algo, ¿lleva la vocal que busco?, decide rápido». Eso es
 * exactamente `juego_rapido` —contrarreloj, sí o no— y es lo que se
 * conserva. Lo que se pierde es la animación; lo que se gana es que el
 * progreso se guarda, las estrellas cuentan y funciona en un teléfono sin
 * depender de la puntería sobre un objeto en movimiento.
 *
 * La temática de cada estación (espacio, nubes, globos, pesca) se mantiene
 * porque es lo que el niño recuerda de la actividad, y porque el título de
 * la estación es lo único que lee antes de entrar.
 */

declare(strict_types=1);

return [

/*
 * La categoría y el bloque ya existen. Van aquí con sus valores actuales,
 * exactos, porque el sembrador hace UPDATE sobre ellos: copiarlos mal
 * renombraría «Aventura de las Letras» de rebote.
 */
'categoria' => [
    'slug'       => 'letras',
    'name'       => 'Aventura de las Letras',
    'tagline'    => 'Lectoescritura',
    'icon'       => '✏️',
    'color'      => '#29b6f6',
    'sort_order' => 1,
],

'bloques' => [
    ['slug' => 'bosque-de-vocales', 'name' => 'Bosque de Vocales', 'icon' => '🌳', 'sort_order' => 1,
     'description' => 'Las cinco vocales, una a una, con sus juegos y sus cuentos.'],
],

'actividades' => [

// =====================================================================
//  PACK 1 · ESPACIO Y NUBES
// =====================================================================

[
    'slug'  => 'juegos-de-vocales-1',
    'title' => 'Juegos de Vocales 1',
    'description' => 'Espacio y nubes: atrapa las vocales correctas mientras vuelas.',
    'objective' => 'Reconocer con qué vocal empieza una palabra y decidir rápido, sin detenerse a deletrear.',
    'icon' => '🚀', 'nivel' => 'preescolar', 'bloque' => 'bosque-de-vocales',
    'duracion' => 12, 'tags' => ['lectura', 'atencion', 'juego'],
    'estaciones' => [

        est('Misión espacial: la A', 'Atrapa solo las que empiezan con A', '🚀', 'juego_rapido',
            conTitulo('¿Empieza con A?', 'Responde antes de que se acabe el tiempo', [
                ['e' => '✈️', 'n' => 'Avión',    'ok' => true],
                ['e' => '🐝', 'n' => 'Abeja',    'ok' => true],
                ['e' => '⚓', 'n' => 'Ancla',    'ok' => true],
                ['e' => '🐘', 'n' => 'Elefante', 'ok' => false],
                ['e' => '💍', 'n' => 'Anillo',   'ok' => true],
                ['e' => '🍇', 'n' => 'Uva',      'ok' => false],
                ['e' => '🐻', 'n' => 'Oso',      'ok' => false],
                ['e' => '🕷️', 'n' => 'Araña',    'ok' => true],
            ])),

        est('Nubes con E', 'Toca las nubes que llevan la E', '☁️', 'juego_rapido',
            conTitulo('¿Empieza con E?', 'Mira el dibujo y decide', [
                ['e' => '🐘', 'n' => 'Elefante', 'ok' => true],
                ['e' => '⭐', 'n' => 'Estrella', 'ok' => true],
                ['e' => '🧹', 'n' => 'Escoba',   'ok' => true],
                ['e' => '✈️', 'n' => 'Avión',    'ok' => false],
                ['e' => '🪜', 'n' => 'Escalera', 'ok' => true],
                ['e' => '🐑', 'n' => 'Oveja',    'ok' => false],
                ['e' => '🧲', 'n' => 'Imán',     'ok' => false],
                ['e' => '🪞', 'n' => 'Espejo',   'ok' => true],
            ])),

        est('¿Con cuál empieza?', 'Elige la vocal correcta de cada palabra', '🔤', 'opcion_multiple', [
            omp('¿Con qué vocal empieza AVIÓN?',    ['A', 'E', 'O'], 'A', '✈️'),
            omp('¿Con qué vocal empieza ESTRELLA?', ['E', 'I', 'U'], 'E', '⭐'),
            omp('¿Con qué vocal empieza ISLA?',     ['I', 'A', 'O'], 'I', '🏝️'),
            omp('¿Con qué vocal empieza OSO?',      ['O', 'U', 'E'], 'O', '🐻'),
            omp('¿Con qué vocal empieza UVA?',      ['U', 'A', 'I'], 'U', '🍇'),
            omp('¿Con qué vocal empieza ABEJA?',    ['A', 'I', 'U'], 'A', '🐝'),
        ]),

        est('Memoria del cohete', 'Encuentra las parejas antes de despegar', '🧠', 'memoria',
            ['🚀', '⭐', '☁️', '🌙', '🛸', '🪐']),

        est('Desafío del piloto', 'Cinco preguntas para completar la misión', '🏆', 'desafio_final', [
            reto('¿Cuántas vocales hay en total?',            ['5', '3', '10'], '5'),
            reto('¿Cuál de estas NO es una vocal?',           ['M', 'A', 'U'], 'M'),
            reto('¿Cuál es la primera vocal del abecedario?', ['A', 'E', 'U'], 'A'),
            reto('ELEFANTE empieza con…',                     ['E', 'A', 'I'], 'E'),
            reto('¿Qué palabra empieza con U?',               ['Uva', 'Oso', 'Isla'], 'Uva'),
        ]),
    ],
],


// =====================================================================
//  PACK 2 · GLOBOS Y PESCA
// =====================================================================

[
    'slug'  => 'juegos-de-vocales-2',
    'title' => 'Juegos de Vocales 2',
    'description' => 'Globos y pesca: dos retos rápidos para reconocer vocales.',
    'objective' => 'Afianzar el reconocimiento de las cinco vocales al inicio de palabra y distinguirlas entre sí.',
    'icon' => '🎈', 'nivel' => 'preescolar', 'bloque' => 'bosque-de-vocales',
    'duracion' => 12, 'tags' => ['lectura', 'atencion', 'juego'],
    'estaciones' => [

        est('Globos con la I', 'Revienta solo los globos con I', '🎈', 'juego_rapido',
            conTitulo('¿Empieza con I?', 'Decide rápido: el globo se escapa', [
                ['e' => '🏝️', 'n' => 'Isla',       'ok' => true],
                ['e' => '🧲', 'n' => 'Imán',       'ok' => true],
                ['e' => '⛪', 'n' => 'Iglesia',    'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana',    'ok' => false],
                ['e' => '🖨️', 'n' => 'Impresora',  'ok' => true],
                ['e' => '🐘', 'n' => 'Elefante',   'ok' => false],
                ['e' => '⭐', 'n' => 'Estrella',   'ok' => false],
            ])),

        est('Pesca la O', 'Saca del agua las palabras con O', '🎣', 'juego_rapido',
            conTitulo('¿Empieza con O?', 'Pesca solo las correctas', [
                ['e' => '🐻', 'n' => 'Oso',      'ok' => true],
                ['e' => '👁️', 'n' => 'Ojo',      'ok' => true],
                ['e' => '🐑', 'n' => 'Oveja',    'ok' => true],
                ['e' => '🍇', 'n' => 'Uva',      'ok' => false],
                ['e' => '👂', 'n' => 'Oreja',    'ok' => true],
                ['e' => '🐝', 'n' => 'Abeja',    'ok' => false],
                ['e' => '🍲', 'n' => 'Olla',     'ok' => true],
                ['e' => '🏝️', 'n' => 'Isla',     'ok' => false],
            ])),

        est('El acuario de la U', 'Toca todo lo que empieza con U', '🌊', 'seleccion_imagenes',
            conTitulo('Toca todo lo que empieza con U', 'Cuidado: hay intrusos', [
                ['e' => '🍇', 'n' => 'Uva',       'ok' => true],
                ['e' => '🦄', 'n' => 'Unicornio', 'ok' => true],
                ['e' => '1️⃣', 'n' => 'Uno',       'ok' => true],
                ['e' => '💅', 'n' => 'Uña',       'ok' => true],
                ['e' => '🐻', 'n' => 'Oso',       'ok' => false],
                ['e' => '⚓', 'n' => 'Ancla',     'ok' => false],
                ['e' => '🧹', 'n' => 'Escoba',    'ok' => false],
                ['e' => '🧲', 'n' => 'Imán',      'ok' => false],
            ])),

        est('Une cada vocal', 'Empareja el dibujo con su palabra', '🔗', 'emparejar', [
            ['e' => '✈️', 'w' => 'Avión'],
            ['e' => '⭐', 'w' => 'Estrella'],
            ['e' => '🏝️', 'w' => 'Isla'],
            ['e' => '🐻', 'w' => 'Oso'],
            ['e' => '🍇', 'w' => 'Uva'],
            ['e' => '🐘', 'w' => 'Elefante'],
        ]),

        est('Desafío del pescador', 'El último reto del pack', '🏆', 'desafio_final', [
            reto('OVEJA empieza con…',                  ['O', 'A', 'E'], 'O'),
            reto('¿Qué palabra empieza con I?',         ['Isla', 'Uva', 'Oso'], 'Isla'),
            reto('UNICORNIO empieza con…',              ['U', 'I', 'O'], 'U'),
            reto('¿Cuál de estas empieza con vocal?',   ['Ancla', 'Perro', 'Casa'], 'Ancla'),
            reto('¿Cuál es la última vocal?',           ['U', 'O', 'A'], 'U'),
        ]),
    ],
],

],

// Nada que reasignar: las dos actividades ya estaban en su bloque.
'reasignar' => [],

];
