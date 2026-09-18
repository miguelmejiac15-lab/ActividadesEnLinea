<?php
/**
 * idiomas-ampliacion.php — English en preescolar
 *
 * Idiomas empezaba de verdad en primero. Cuatro actividades de
 * preescolar para una materia que en muchos colegios arranca en
 * transición.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  TODAS LAS ESTACIONES DECLARAN EL IDIOMA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Se usa `en()` y no `est()`. No es un detalle: con la voz española
 * «cat» suena «kat» y la actividad enseña justo lo contrario de lo que
 * pretende. `en()` existe para que no haya nada que olvidar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  A LOS CINCO AÑOS SE APRENDE POR LA OREJA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Nada de gramática, nada de escribir. Palabra + dibujo + sonido, y
 * mucha repetición en juegos distintos. El niño no traduce: asocia.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'idiomas',
    'name'       => 'Idiomas',
    'tagline'    => 'English: vocabulario, lectura y conversación desde primero',
    'icon'       => '🌐',
    'color'      => '#00897b',
    'sort_order' => 10,
],

'bloques' => [
    ['slug' => 'first-words', 'name' => 'First Words', 'icon' => '👋', 'sort_order' => 1,
     'description' => 'Las primeras palabras en inglés: colores, animales, cuerpo y saludos.'],
    ['slug' => 'my-world', 'name' => 'My World', 'icon' => '🏠', 'sort_order' => 4,
     'description' => 'Familia, casa, colegio y comunidad: las primeras palabras de lo que rodea al niño.'],
    ['slug' => 'nature-and-animals', 'name' => 'Nature & Animals', 'icon' => '🐾', 'sort_order' => 5,
     'description' => 'Animales, plantas, clima y el mundo natural en inglés.'],
],

'actividades' => [

[
    'slug'  => 'hello-and-goodbye',
    'title' => 'Hello and goodbye',
    'description' => 'Saludar, despedirse y decir cómo me llamo en inglés.',
    'objective' => 'Usar fórmulas básicas de saludo y presentación en inglés.',
    'icon' => '🗣️', 'nivel' => 'preescolar', 'bloque' => 'first-words',
    'duracion' => 8, 'tags' => ['ingles', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        en('Hello!', 'Saludar en inglés', '👋', 'opcion_multiple', [
            omp('«Hola» en inglés es…',        ['Hello', 'Goodbye', 'Please'], 'Hello', '👋'),
            omp('«Adiós» en inglés es…',       ['Goodbye', 'Hello', 'Thanks'], 'Goodbye', '👋'),
            omp('«Buenos días» es…',           ['Good morning', 'Good night', 'Good boy'], 'Good morning', '🌅'),
            omp('«Buenas noches» es…',         ['Good night', 'Good morning', 'Good day'], 'Good night', '🌙'),
            omp('«Gracias» en inglés es…',     ['Thank you', 'Please', 'Sorry'], 'Thank you'),
        ]),

        en('What is your name?', 'Decir mi nombre', '🙋', 'opcion_multiple', [
            omp('Para decir mi nombre digo…',  ['My name is…', 'My cat is…', 'I am five'], 'My name is…'),
            omp('«¿Cómo te llamas?» es…',      ['What is your name?', 'How are you?', 'Where are you?'], 'What is your name?'),
            omp('«¿Cómo estás?» es…',          ['How are you?', 'What is this?', 'Who are you?'], 'How are you?'),
            omp('Si estoy bien respondo…',     ['I am fine', 'I am a cat', 'Good night'], 'I am fine'),
            omp('«Por favor» en inglés es…',   ['Please', 'Thanks', 'Sorry'], 'Please'),
        ]),

        en('Listen and match', 'Une el saludo con el momento', '🔗', 'emparejar', [
            ['e' => '🌅', 'w' => 'Good morning'],
            ['e' => '🌙', 'w' => 'Good night'],
            ['e' => '👋', 'w' => 'Hello'],
            ['e' => '🚪', 'w' => 'Goodbye'],
            ['e' => '🙏', 'w' => 'Thank you'],
            ['e' => '😊', 'w' => 'Please'],
        ]),

        en('Say it!', 'Repite en voz alta', '🔊', 'pronunciacion', [
            ['w' => 'Hello',      'e' => '👋'],
            ['w' => 'Goodbye',    'e' => '👋'],
            ['w' => 'Thank you',  'e' => '🙏'],
            ['w' => 'Please',     'e' => '😊'],
            ['w' => 'My name is', 'e' => '🙋'],
        ]),
    ],
],

[
    'slug'  => 'colors-and-numbers',
    'title' => 'Colors and numbers',
    'description' => 'Los colores y los números del uno al diez en inglés.',
    'objective' => 'Reconocer colores básicos y numerales del 1 al 10 en inglés.',
    'icon' => '🌈', 'nivel' => 'preescolar', 'bloque' => 'first-words',
    'duracion' => 8, 'tags' => ['ingles', 'vocabulario', 'calculo'],
    'estaciones' => [

        en('Colors', 'Los colores en inglés', '🎨', 'opcion_multiple', [
            omp('🔴 es…',  ['Red', 'Blue', 'Green'], 'Red', '🔴'),
            omp('🔵 es…',  ['Blue', 'Red', 'Yellow'], 'Blue', '🔵'),
            omp('🟡 es…',  ['Yellow', 'Green', 'Black'], 'Yellow', '🟡'),
            omp('🟢 es…',  ['Green', 'Blue', 'White'], 'Green', '🟢'),
            omp('⚫ es…',  ['Black', 'White', 'Red'], 'Black', '⚫'),
        ]),

        en('Numbers 1 to 10', 'Contar en inglés', '🔢', 'opcion_multiple', [
            omp('1 es…',  ['One', 'Two', 'Ten'], 'One'),
            omp('3 es…',  ['Three', 'Four', 'Five'], 'Three'),
            omp('5 es…',  ['Five', 'Nine', 'One'], 'Five'),
            omp('8 es…',  ['Eight', 'Seven', 'Six'], 'Eight'),
            omp('10 es…', ['Ten', 'Two', 'Nine'], 'Ten'),
        ]),

        en('Match the color', 'Une el color con su palabra', '🔗', 'emparejar', [
            ['e' => '🔴', 'w' => 'Red'],
            ['e' => '🔵', 'w' => 'Blue'],
            ['e' => '🟡', 'w' => 'Yellow'],
            ['e' => '🟢', 'w' => 'Green'],
            ['e' => '🟠', 'w' => 'Orange'],
            ['e' => '🟣', 'w' => 'Purple'],
        ]),

        en('Count with me', 'Ordena los números', '🔢', 'ordenar_secuencia', [
            'title' => 'Order the numbers from one to six',
            'items' => ['One', 'Two', 'Three', 'Four', 'Five', 'Six'],
        ]),
    ],
],

[
    'slug'  => 'my-family-in-english',
    'title' => 'My family',
    'description' => 'Mamá, papá, hermano, hermana: la familia en inglés.',
    'objective' => 'Nombrar los miembros de la familia en inglés.',
    'icon' => '👪', 'nivel' => 'preescolar', 'bloque' => 'my-world',
    'duracion' => 8, 'tags' => ['ingles', 'familia', 'vocabulario'],
    'estaciones' => [

        en('Family words', 'Cada uno tiene su palabra', '👪', 'opcion_multiple', [
            omp('«Mamá» en inglés es…',    ['Mother', 'Father', 'Sister'], 'Mother', '👩'),
            omp('«Papá» en inglés es…',    ['Father', 'Brother', 'Mother'], 'Father', '👨'),
            omp('«Hermano» es…',           ['Brother', 'Sister', 'Baby'], 'Brother', '🧒'),
            omp('«Hermana» es…',           ['Sister', 'Brother', 'Mother'], 'Sister', '👧'),
            omp('«Bebé» es…',              ['Baby', 'Boy', 'Girl'], 'Baby', '👶'),
        ]),

        en('Match the family', 'Une el dibujo con la palabra', '🔗', 'emparejar', [
            ['e' => '👩', 'w' => 'Mother'],
            ['e' => '👨', 'w' => 'Father'],
            ['e' => '👧', 'w' => 'Sister'],
            ['e' => '🧒', 'w' => 'Brother'],
            ['e' => '👵', 'w' => 'Grandmother'],
            ['e' => '👴', 'w' => 'Grandfather'],
        ]),

        en('This is my house', 'Los cuartos de la casa', '🏠', 'opcion_multiple', [
            omp('«Casa» en inglés es…',     ['House', 'Horse', 'Mouse'], 'House', '🏠'),
            omp('«Cuarto» es…',             ['Bedroom', 'Kitchen', 'Garden'], 'Bedroom', '🛏️'),
            omp('«Cocina» es…',             ['Kitchen', 'Bathroom', 'Bedroom'], 'Kitchen', '🍳'),
            omp('«Baño» es…',               ['Bathroom', 'Kitchen', 'Living room'], 'Bathroom', '🛁'),
            omp('«Puerta» es…',             ['Door', 'Window', 'Wall'], 'Door', '🚪'),
        ]),

        en('Memory: family', 'Encuentra las parejas', '🧠', 'memoria',
            ['👩', '👨', '👧', '🧒', '👵', '👴']),
    ],
],

[
    'slug'  => 'animals-in-english',
    'title' => 'Animals',
    'description' => 'Cat, dog, bird, fish: los animales que ya conoce, en inglés.',
    'objective' => 'Reconocer nombres de animales comunes en inglés.',
    'icon' => '🦜', 'nivel' => 'preescolar', 'bloque' => 'nature-and-animals',
    'duracion' => 8, 'tags' => ['ingles', 'vocabulario', 'observacion'],
    'estaciones' => [

        en('Animal words', 'Cada animal, su palabra', '🐾', 'opcion_multiple', [
            omp('🐱 en inglés es…',  ['Cat', 'Dog', 'Bird'], 'Cat', '🐱'),
            omp('🐶 en inglés es…',  ['Dog', 'Cat', 'Fish'], 'Dog', '🐶'),
            omp('🐦 en inglés es…',  ['Bird', 'Bear', 'Cow'], 'Bird', '🐦'),
            omp('🐠 en inglés es…',  ['Fish', 'Frog', 'Duck'], 'Fish', '🐠'),
            omp('🐄 en inglés es…',  ['Cow', 'Horse', 'Pig'], 'Cow', '🐄'),
        ]),

        en('Match the animal', 'Une el dibujo con la palabra', '🔗', 'emparejar', [
            ['e' => '🐱', 'w' => 'Cat'],
            ['e' => '🐶', 'w' => 'Dog'],
            ['e' => '🐦', 'w' => 'Bird'],
            ['e' => '🐠', 'w' => 'Fish'],
            ['e' => '🐴', 'w' => 'Horse'],
            ['e' => '🐷', 'w' => 'Pig'],
        ]),

        en('Find the animals', 'Toca solo los animales', '🔍', 'seleccion_imagenes',
            conTitulo('Touch all the animals', 'Only animals', [
                ['e' => '🐱', 'n' => 'Cat',   'ok' => true],
                ['e' => '🌳', 'n' => 'Tree',  'ok' => false],
                ['e' => '🐶', 'n' => 'Dog',   'ok' => true],
                ['e' => '🏠', 'n' => 'House', 'ok' => false],
                ['e' => '🐰', 'n' => 'Rabbit','ok' => true],
                ['e' => '🚗', 'n' => 'Car',   'ok' => false],
                ['e' => '🦆', 'n' => 'Duck',  'ok' => true],
                ['e' => '⚽', 'n' => 'Ball',  'ok' => false],
            ])),

        en('Say the animals', 'Repite en voz alta', '🔊', 'pronunciacion', [
            ['w' => 'Cat',   'e' => '🐱'],
            ['w' => 'Dog',   'e' => '🐶'],
            ['w' => 'Bird',  'e' => '🐦'],
            ['w' => 'Fish',  'e' => '🐠'],
            ['w' => 'Horse', 'e' => '🐴'],
        ]),
    ],
],

],
];
