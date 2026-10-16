<?php
/**
 * idiomas-primaria.php — English de 2.º a 6.º
 *
 * Escrito sobre las mallas de English (K2 a K6) de `MallasPrimaria/`, que
 * siguen el itinerario Cambridge: Starters en 3.º, Movers en 4.º y 5.º,
 * Flyers en 6.º. Las unidades de cada malla vienen formuladas como
 * preguntas —«How are families the same and different?», «How do animals
 * survive?»— y ese planteamiento se conserva en los títulos, porque es lo
 * que le da sentido al vocabulario: se aprenden las palabras de algo, no
 * una lista.
 *
 * DOS REGLAS DE ESTE ARCHIVO, y las dos importan:
 *
 * 1. Cada estación declara `en-US`. Sin eso el motor lee con voz española
 *    y «cat» suena «kat», que enseña exactamente lo contrario de lo que se
 *    pretende. El quinto argumento de `est()` existe para esto.
 *
 * 2. Las consignas van en español y el contenido en inglés. Un niño de
 *    ocho años que todavía no lee con soltura en su lengua no puede además
 *    descifrar la instrucción: si la consigna es el obstáculo, no se está
 *    evaluando el inglés.
 */

declare(strict_types=1);

// `en()` es `est()` con `en-US` puesto. Vive en contenido/ayudas.php.

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
    ['slug' => 'my-world', 'name' => 'My World', 'icon' => '🏠', 'sort_order' => 4,
     'description' => 'Familia, casa, colegio y comunidad: las primeras palabras de lo que rodea al niño.'],
    ['slug' => 'nature-and-animals', 'name' => 'Nature & Animals', 'icon' => '🐾', 'sort_order' => 5,
     'description' => 'Animales, plantas, clima y el mundo natural en inglés.'],
    ['slug' => 'daily-life', 'name' => 'Daily Life', 'icon' => '🍎', 'sort_order' => 6,
     'description' => 'Comida, ropa, salud, aficiones y emociones del día a día.'],
    ['slug' => 'time-and-discovery', 'name' => 'Time & Discovery', 'icon' => '🚀', 'sort_order' => 7,
     'description' => 'El pasado, los inventos, el espacio y las formas de comunicarnos.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · MY WORLD
// =====================================================================

[
    'slug'  => 'my-family',
    'title' => 'My Family',
    'description' => 'Los miembros de la familia en inglés y cómo presentarlos.',
    'objective' => 'Nombrar a los miembros de la familia en inglés y describir relaciones básicas.',
    'icon' => '👨‍👩‍👧', 'nivel' => 'primaria-inicial', 'bloque' => 'my-world',
    'duracion' => 11, 'tags' => ['ingles', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        en('Family words', 'Une el dibujo con la palabra en inglés', '🔗', 'emparejar', [
            ['e' => '👩', 'w' => 'Mother'],
            ['e' => '👨', 'w' => 'Father'],
            ['e' => '👧', 'w' => 'Sister'],
            ['e' => '👦', 'w' => 'Brother'],
            ['e' => '👵', 'w' => 'Grandmother'],
            ['e' => '👶', 'w' => 'Baby'],
        ]),

        en('Listen and repeat', 'Escucha y repite en voz alta', '🔊', 'pronunciacion', [
            ['e' => '👩', 'w' => 'Mother'],
            ['e' => '👨', 'w' => 'Father'],
            ['e' => '👧', 'w' => 'Sister'],
            ['e' => '👦', 'w' => 'Brother'],
            ['e' => '👨‍👩‍👧', 'w' => 'Family'],
        ]),

        en('Who is who?', 'Elige la palabra correcta', '❓', 'opcion_multiple', [
            omp('My mother\'s mother is my…',   ['Grandmother', 'Sister', 'Aunt'], 'Grandmother', '👵'),
            omp('My father\'s son is my…',      ['Brother', 'Uncle', 'Cousin'], 'Brother', '👦'),
            omp('My mother\'s sister is my…',   ['Aunt', 'Grandmother', 'Cousin'], 'Aunt', '👩'),
            omp('My aunt\'s son is my…',        ['Cousin', 'Brother', 'Uncle'], 'Cousin', '🧒'),
            omp('Mom, Dad, my sister and me. We are a…', ['Family', 'Friend', 'Home'], 'Family', '👨‍👩‍👧'),
        ]),

        en('Write the word', 'Escribe la palabra en inglés', '⌨️', 'teclado',
            ['MOTHER', 'FATHER', 'SISTER', 'BROTHER', 'FAMILY']),
    ],
],

[
    'slug'  => 'at-school',
    'title' => 'At School',
    'description' => 'Objetos del salón, materias y rutinas escolares en inglés.',
    'objective' => 'Nombrar objetos y rutinas del colegio en inglés y usar there is / there are.',
    'icon' => '🏫', 'nivel' => 'primaria-inicial', 'bloque' => 'my-world',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'lectura'],
    'estaciones' => [

        en('Classroom objects', 'Une el objeto con su palabra', '🔗', 'emparejar', [
            ['e' => '📕', 'w' => 'Book'],
            ['e' => '✏️', 'w' => 'Pencil'],
            ['e' => '🎒', 'w' => 'Backpack'],
            ['e' => '📏', 'w' => 'Ruler'],
            ['e' => '✂️', 'w' => 'Scissors'],
            ['e' => '🖍️', 'w' => 'Crayon'],
        ]),

        en('Find the school things', 'Toca todo lo del colegio', '🎯', 'seleccion_imagenes',
            conTitulo('Touch all the school things', 'Some do not belong at school', [
                ['e' => '📕', 'n' => 'Book',     'ok' => true],
                ['e' => '✏️', 'n' => 'Pencil',   'ok' => true],
                ['e' => '🛏️', 'n' => 'Bed',      'ok' => false],
                ['e' => '🎒', 'n' => 'Backpack', 'ok' => true],
                ['e' => '🍳', 'n' => 'Pan',      'ok' => false],
                ['e' => '📐', 'n' => 'Ruler',    'ok' => true],
                ['e' => '🚿', 'n' => 'Shower',   'ok' => false],
                ['e' => '🖍️', 'n' => 'Crayon',   'ok' => true],
            ])),

        en('There is / There are', 'Singular o plural', '🔢', 'opcion_multiple', [
            omp('___ a book on the desk.',      ['There is', 'There are', 'There am'], 'There is'),
            omp('___ three pencils here.',      ['There are', 'There is', 'There be'], 'There are'),
            omp('___ a teacher in the room.',   ['There is', 'There are', 'There have'], 'There is'),
            omp('___ many students today.',     ['There are', 'There is', 'There was'], 'There are'),
            omp('How many chairs ___ there?',   ['are', 'is', 'be'], 'are'),
        ]),

        en('School day', 'Ordena la rutina escolar', '🔢', 'ordenar_secuencia', [
            'title' => 'Order the school day',
            'items' => ['Wake up', 'Have breakfast', 'Go to school', 'Have lunch', 'Go home'],
        ]),
    ],
],

[
    'slug'  => 'my-home',
    'title' => 'My Home',
    'description' => 'Las habitaciones de la casa, los muebles y dónde está cada cosa.',
    'objective' => 'Describir las partes de una casa y usar preposiciones de lugar en inglés.',
    'icon' => '🛋️', 'nivel' => 'primaria-media', 'bloque' => 'my-world',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'observacion'],
    'estaciones' => [

        en('Rooms in the house', 'Une cada espacio con su nombre', '🔗', 'emparejar', [
            ['e' => '🛏️', 'w' => 'Bedroom'],
            ['e' => '🍳', 'w' => 'Kitchen'],
            ['e' => '🛁', 'w' => 'Bathroom'],
            ['e' => '🛋️', 'w' => 'Living room'],
            ['e' => '🌳', 'w' => 'Garden'],
            ['e' => '🚪', 'w' => 'Door'],
        ]),

        en('Where is it?', 'Preposiciones de lugar', '📍', 'opcion_multiple', [
            omp('The cat is ___ the table. (encima)',   ['on', 'in', 'under'], 'on', '🐱'),
            omp('The ball is ___ the box. (dentro)',    ['in', 'on', 'next to'], 'in', '⚽'),
            omp('The dog is ___ the bed. (debajo)',     ['under', 'on', 'in'], 'under', '🐶'),
            omp('The lamp and the sofa are side by side. The lamp is ___ the sofa.', ['next to', 'in', 'under'], 'next to', '💡'),
            omp('We cook ___ the kitchen.',             ['in', 'on', 'under'], 'in', '🍳'),
        ]),

        en('What do you do here?', 'Cada cuarto tiene su actividad', '🏠', 'opcion_multiple', [
            omp('Where do you sleep?',       ['In the bedroom', 'In the kitchen', 'In the garden'], 'In the bedroom'),
            omp('Where do you cook?',        ['In the kitchen', 'In the bathroom', 'In the bedroom'], 'In the kitchen'),
            omp('Where do you take a shower?', ['In the bathroom', 'In the living room', 'In the garden'], 'In the bathroom'),
            omp('Where do you watch TV?',    ['In the living room', 'In the bathroom', 'In the kitchen'], 'In the living room'),
            omp('Where do the plants grow?', ['In the garden', 'In the bathroom', 'In the bedroom'], 'In the garden'),
        ]),

        en('Spell the room', 'Escribe la palabra', '⌨️', 'teclado',
            ['KITCHEN', 'BEDROOM', 'BATHROOM', 'GARDEN', 'HOUSE']),
    ],
],

[
    'slug'  => 'my-city',
    'title' => 'My City',
    'description' => 'Lugares de la ciudad, cómo pedir direcciones y qué necesita una comunidad.',
    'objective' => 'Nombrar lugares de la ciudad y dar indicaciones sencillas en inglés.',
    'icon' => '🏙️', 'nivel' => 'primaria-superior', 'bloque' => 'my-world',
    'duracion' => 13, 'tags' => ['ingles', 'vocabulario', 'geografia'],
    'estaciones' => [

        en('Places in the city', 'Une cada lugar con su nombre', '🔗', 'emparejar', [
            ['e' => '🏥', 'w' => 'Hospital'],
            ['e' => '🏦', 'w' => 'Bank'],
            ['e' => '📚', 'w' => 'Library'],
            ['e' => '🏪', 'w' => 'Shop'],
            ['e' => '🚉', 'w' => 'Station'],
            ['e' => '🏞️', 'w' => 'Park'],
        ]),

        en('Where do you go?', 'Elige el lugar correcto', '🧭', 'opcion_multiple', [
            omp('You are sick. Where do you go?',        ['To the hospital', 'To the bank', 'To the park'], 'To the hospital'),
            omp('You want to borrow a book. Where?',     ['To the library', 'To the shop', 'To the station'], 'To the library'),
            omp('You want to take a train. Where?',      ['To the station', 'To the park', 'To the bank'], 'To the station'),
            omp('You want to buy bread. Where?',         ['To the bakery', 'To the hospital', 'To the library'], 'To the bakery'),
            omp('You want to play outside. Where?',      ['To the park', 'To the bank', 'To the hospital'], 'To the park'),
        ]),

        en('Giving directions', 'Cómo indicar el camino', '➡️', 'opcion_multiple', [
            omp('Look at the arrow. Where do you go?', ['Turn left', 'Turn right', 'Go back'], 'Turn left', '⬅️'),
            omp('Look at the arrow. Where do you go?', ['Go straight', 'Turn around', 'Stop here'], 'Go straight', '⬆️'),
            omp('Look at the arrow. Where do you go?', ['Turn right', 'Turn left', 'Go up'], 'Turn right', '➡️'),
            omp('Look: 🏪🏦. Where is the shop?', ['It\'s next to the bank', 'It\'s under the bank', 'It\'s the bank'], 'It\'s next to the bank'),
            omp('You are looking for the library. You ask…', ['Where is the library?', 'What is the library?', 'Who is the library?'], 'Where is the library?'),
        ]),

        en('A good community', 'Qué necesita un lugar para vivir bien', '🤝', 'opcion_multiple', [
            omp('What makes a good community?',      ['People helping each other', 'Big buildings only', 'Many cars'], 'People helping each other'),
            omp('Who takes care of sick people?',    ['Doctors and nurses', 'Bakers', 'Drivers'], 'Doctors and nurses'),
            omp('Who teaches children?',             ['Teachers', 'Firefighters', 'Farmers'], 'Teachers'),
            omp('Why do we need parks?',             ['To play and rest outdoors', 'To park cars', 'To study only'], 'To play and rest outdoors'),
            omp('What can you do for your community?', ['Keep it clean and help others', 'Nothing', 'Throw litter'], 'Keep it clean and help others'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · NATURE & ANIMALS
// =====================================================================

[
    'slug'  => 'animals-around-us',
    'title' => 'Animals Around Us',
    'description' => 'Animales de la granja, de la casa y salvajes, con los sonidos que hacen en inglés.',
    'objective' => 'Nombrar animales comunes en inglés y clasificarlos por su hábitat.',
    'icon' => '🐾', 'nivel' => 'primaria-inicial', 'bloque' => 'nature-and-animals',
    'duracion' => 11, 'tags' => ['ingles', 'vocabulario', 'clasificacion'],
    'estaciones' => [

        en('Animal names', 'Une el animal con su nombre', '🔗', 'emparejar', [
            ['e' => '🐶', 'w' => 'Dog'],
            ['e' => '🐱', 'w' => 'Cat'],
            ['e' => '🐦', 'w' => 'Bird'],
            ['e' => '🐟', 'w' => 'Fish'],
            ['e' => '🐴', 'w' => 'Horse'],
            ['e' => '🐄', 'w' => 'Cow'],
        ]),

        en('Listen and say', 'Escucha y repite', '🔊', 'pronunciacion', [
            ['e' => '🦁', 'w' => 'Lion'],
            ['e' => '🐘', 'w' => 'Elephant'],
            ['e' => '🐵', 'w' => 'Monkey'],
            ['e' => '🐻', 'w' => 'Bear'],
            ['e' => '🦒', 'w' => 'Giraffe'],
        ]),

        en('Farm or wild?', 'Toca los animales de granja', '🚜', 'seleccion_imagenes',
            conTitulo('Touch all the farm animals', 'Wild animals do not live on a farm', [
                ['e' => '🐄', 'n' => 'Cow',      'ok' => true],
                ['e' => '🐖', 'n' => 'Pig',      'ok' => true],
                ['e' => '🦁', 'n' => 'Lion',     'ok' => false],
                ['e' => '🐔', 'n' => 'Hen',      'ok' => true],
                ['e' => '🐘', 'n' => 'Elephant', 'ok' => false],
                ['e' => '🐑', 'n' => 'Sheep',    'ok' => true],
                ['e' => '🐊', 'n' => 'Crocodile', 'ok' => false],
                ['e' => '🐴', 'n' => 'Horse',    'ok' => true],
            ])),

        en('Can or can\'t?', 'Qué puede hacer cada animal', '💭', 'opcion_multiple', [
            omp('A bird ___ fly.',      ['can', 'can\'t', 'don\'t'], 'can', '🐦'),
            omp('A fish ___ walk.',     ['can\'t', 'can', 'does'], 'can\'t', '🐟'),
            omp('A monkey ___ climb.',  ['can', 'can\'t', 'isn\'t'], 'can', '🐵'),
            omp('An elephant ___ fly.', ['can\'t', 'can', 'do'], 'can\'t', '🐘'),
            omp('A dog ___ swim.',      ['can', 'can\'t', 'aren\'t'], 'can', '🐶'),
        ]),
    ],
],

[
    'slug'  => 'the-weather',
    'title' => 'The Weather',
    'description' => 'Hablar del clima en inglés y decir qué hacemos según el tiempo que haga.',
    'objective' => 'Describir el clima en inglés y relacionarlo con actividades y ropa.',
    'icon' => '🌤️', 'nivel' => 'primaria-media', 'bloque' => 'nature-and-animals',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'observacion'],
    'estaciones' => [

        en('Weather words', 'Une el símbolo con su palabra', '🔗', 'emparejar', [
            ['e' => '☀️', 'w' => 'Sunny'],
            ['e' => '🌧️', 'w' => 'Rainy'],
            ['e' => '☁️', 'w' => 'Cloudy'],
            ['e' => '💨', 'w' => 'Windy'],
            ['e' => '❄️', 'w' => 'Snowy'],
            ['e' => '🌡️', 'w' => 'Hot'],
        ]),

        en('What\'s the weather like?', 'Responde sobre el clima', '❓', 'opcion_multiple', [
            omp('It\'s raining. What do you need?',       ['An umbrella', 'Sunglasses', 'A fan'], 'An umbrella', '☂️'),
            omp('It\'s sunny and hot. What do you wear?', ['A T-shirt', 'A coat', 'Boots'], 'A T-shirt', '👕'),
            omp('It\'s cold. What do you wear?',          ['A jacket', 'Shorts', 'Sandals'], 'A jacket', '🧥'),
            omp('What is the weather like?', ['It\'s windy', 'It\'s sunny', 'It\'s rainy'], 'It\'s windy', '💨'),
            omp('You want to know about the weather. You ask…', ['What\'s the weather like?', 'What time is it?', 'How are you?'], 'What\'s the weather like?', '🌦️'),
        ]),

        en('The seasons', 'Las cuatro estaciones', '🍂', 'opcion_multiple', [
            omp('Which season is the coldest?',    ['Winter', 'Summer', 'Spring'], 'Winter', '❄️'),
            omp('Which season is the hottest?',    ['Summer', 'Winter', 'Autumn'], 'Summer', '☀️'),
            omp('When do flowers grow?',           ['In spring', 'In winter', 'Never'], 'In spring', '🌷'),
            omp('When do leaves fall?',            ['In autumn', 'In spring', 'In summer'], 'In autumn', '🍂'),
            omp('How many seasons are there?',     ['Four', 'Two', 'Six'], 'Four'),
        ]),

        en('Weather quiz', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('Is it true?', 'Answer fast', [
                ['e' => '☀️', 'n' => 'Sunny means «soleado»',  'ok' => true],
                ['e' => '🌧️', 'n' => 'Rainy means «ventoso»',  'ok' => false],
                ['e' => '❄️', 'n' => 'Snow is cold',           'ok' => true],
                ['e' => '☁️', 'n' => 'Cloudy means «nublado»', 'ok' => true],
                ['e' => '💨', 'n' => 'Windy means «lluvioso»', 'ok' => false],
                ['e' => '🌈', 'n' => 'A rainbow has colours',  'ok' => true],
            ])),
    ],
],

[
    'slug'  => 'living-things',
    'title' => 'Living Things',
    'description' => 'Seres vivos y no vivos, hábitats y cómo sobreviven los animales, en inglés.',
    'objective' => 'Describir seres vivos, sus necesidades y sus hábitats usando vocabulario en inglés.',
    'icon' => '🌿', 'nivel' => 'primaria-superior', 'bloque' => 'nature-and-animals',
    'duracion' => 13, 'tags' => ['ingles', 'comprension', 'clasificacion'],
    'estaciones' => [

        en('Living or non-living?', 'Toca los seres vivos', '🌱', 'seleccion_imagenes',
            conTitulo('Touch all the living things', 'Look carefully', [
                ['e' => '🌳', 'n' => 'Tree',     'ok' => true],
                ['e' => '🪨', 'n' => 'Rock',     'ok' => false],
                ['e' => '🐟', 'n' => 'Fish',     'ok' => true],
                ['e' => '🚗', 'n' => 'Car',      'ok' => false],
                ['e' => '🌻', 'n' => 'Flower',   'ok' => true],
                ['e' => '💧', 'n' => 'Water',    'ok' => false],
                ['e' => '🦋', 'n' => 'Butterfly', 'ok' => true],
                ['e' => '⛰️', 'n' => 'Mountain', 'ok' => false],
            ])),

        en('What do they need?', 'Necesidades básicas', '💧', 'opcion_multiple', [
            omp('What do all living things need?', ['Water and food', 'Money', 'Toys'], 'Water and food'),
            omp('What do plants need to grow?',    ['Light, water and soil', 'Only air', 'Nothing'], 'Light, water and soil'),
            omp('What do animals need to breathe?', ['Oxygen', 'Sugar', 'Plastic'], 'Oxygen'),
            omp('Where do fish live?',             ['In water', 'In trees', 'In sand'], 'In water', '🐟'),
            omp('What is a habitat?',              ['The place where a living thing lives', 'A kind of food', 'A season'], 'The place where a living thing lives'),
        ]),

        en('Habitats', 'Cada animal en su lugar', '🗺️', 'opcion_multiple', [
            omp('Where does a camel live?',   ['In the desert', 'In the ocean', 'In the snow'], 'In the desert', '🐫'),
            omp('Where does a polar bear live?', ['In the Arctic', 'In the jungle', 'In the desert'], 'In the Arctic', '🐻‍❄️'),
            omp('Where does a monkey live?',  ['In the forest', 'In the ocean', 'In the desert'], 'In the forest', '🐵'),
            omp('Where does a dolphin live?', ['In the ocean', 'In the forest', 'In the mountains'], 'In the ocean', '🐬'),
            omp('Why do animals adapt?',      ['To survive in their habitat', 'To look nice', 'For no reason'], 'To survive in their habitat'),
        ]),

        en('Animal survival', 'Cómo sobreviven', '🛡️', 'opcion_multiple', [
            omp('Why does a chameleon change colour?', ['To hide from predators', 'To be pretty', 'Because it is cold'], 'To hide from predators'),
            omp('Why do birds migrate?',               ['To find food and warmth', 'To exercise', 'To play'], 'To find food and warmth'),
            omp('Why does a bear sleep in winter?',    ['To save energy when food is scarce', 'Because it is lazy', 'To grow'], 'To save energy when food is scarce'),
            omp('What is camouflage?',                 ['Blending in with the surroundings', 'Running fast', 'Making noise'], 'Blending in with the surroundings'),
            omp('What is a predator?',                 ['An animal that hunts others', 'An animal that is hunted', 'A plant'], 'An animal that hunts others'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · DAILY LIFE
// =====================================================================

[
    'slug'  => 'food-and-drinks',
    'title' => 'Food and Drinks',
    'description' => 'Comida y bebida en inglés, gustos y hábitos saludables.',
    'objective' => 'Nombrar alimentos en inglés y expresar preferencias con like / don\'t like.',
    'icon' => '🍎', 'nivel' => 'primaria-inicial', 'bloque' => 'daily-life',
    'duracion' => 11, 'tags' => ['ingles', 'vocabulario', 'alimentacion'],
    'estaciones' => [

        en('Food words', 'Une la comida con su palabra', '🔗', 'emparejar', [
            ['e' => '🍎', 'w' => 'Apple'],
            ['e' => '🍌', 'w' => 'Banana'],
            ['e' => '🍞', 'w' => 'Bread'],
            ['e' => '🥛', 'w' => 'Milk'],
            ['e' => '🧀', 'w' => 'Cheese'],
            ['e' => '🍚', 'w' => 'Rice'],
        ]),

        en('I like / I don\'t like', 'Expresar gustos', '💚', 'opcion_multiple', [
            omp('You love apples. You say…', ['I like apples', 'I am apples', 'I have apples'], 'I like apples', '🍎'),
            omp('Milk? No, thank you! You say…', ['I don\'t like milk', 'I no like milk', 'I not like milk'], 'I don\'t like milk', '🥛'),
            omp('You ask your friend about rice. You say…', ['Do you like rice?', 'You like rice?', 'Are you rice?'], 'Do you like rice?', '🍚'),
            omp('Which one is correct?', ['My favourite food', 'My food favourite', 'The food my'], 'My favourite food', '🍕'),
            omp('Your tummy is empty. You say…', ['I\'m hungry', 'I\'m thirsty', 'I\'m tired'], 'I\'m hungry', '😋'),
        ]),

        en('Healthy or not?', 'Toca los alimentos saludables', '🥗', 'seleccion_imagenes',
            conTitulo('Touch the healthy food', 'Sweets are not the healthiest choice', [
                ['e' => '🥕', 'n' => 'Carrot',  'ok' => true],
                ['e' => '🍭', 'n' => 'Lollipop', 'ok' => false],
                ['e' => '🥦', 'n' => 'Broccoli', 'ok' => true],
                ['e' => '🍟', 'n' => 'Chips',   'ok' => false],
                ['e' => '🍎', 'n' => 'Apple',   'ok' => true],
                ['e' => '🍰', 'n' => 'Cake',    'ok' => false],
                ['e' => '🐟', 'n' => 'Fish',    'ok' => true],
                ['e' => '🥤', 'n' => 'Soda',    'ok' => false],
            ])),

        en('Spell the food', 'Escribe la palabra', '⌨️', 'teclado',
            ['APPLE', 'BREAD', 'MILK', 'WATER', 'RICE']),
    ],
],

[
    'slug'  => 'clothes-and-colours',
    'title' => 'Clothes and Colours',
    'description' => 'Ropa, colores y qué nos ponemos según el clima y la ocasión.',
    'objective' => 'Nombrar prendas y colores en inglés y describir lo que alguien lleva puesto.',
    'icon' => '👕', 'nivel' => 'primaria-media', 'bloque' => 'daily-life',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'observacion'],
    'estaciones' => [

        en('Clothes', 'Une la prenda con su nombre', '🔗', 'emparejar', [
            ['e' => '👕', 'w' => 'T-shirt'],
            ['e' => '👖', 'w' => 'Trousers'],
            ['e' => '👟', 'w' => 'Shoes'],
            ['e' => '🧥', 'w' => 'Jacket'],
            ['e' => '👗', 'w' => 'Dress'],
            ['e' => '🧢', 'w' => 'Cap'],
        ]),

        en('Colours', 'Los colores en inglés', '🎨', 'opcion_multiple', [
            omp('What color is it?', ['Red', 'Blue', 'Green'], 'Red', '#e53935', 'color'),
            omp('What color is it?', ['Blue', 'Black', 'Brown'], 'Blue', '#1e88e5', 'color'),
            omp('What color is it?', ['Green', 'Grey', 'Gold'], 'Green', '#43a047', 'color'),
            omp('What color is it?', ['Yellow', 'White', 'Purple'], 'Yellow', '#fdd835', 'color'),
            omp('What color is it?', ['Black', 'Blue', 'Beige'], 'Black', '#212121', 'color'),
        ]),

        en('What are you wearing?', 'Describir la ropa', '👀', 'opcion_multiple', [
            omp('Look at your T-shirt 👕. Which sentence is correct?',   ['I\'m wearing a blue T-shirt', 'I wear blue a T-shirt', 'I am blue T-shirt'], 'I\'m wearing a blue T-shirt'),
            omp('Which one is correct?', ['a red car', 'a car red', 'car a red'], 'a red car', '🚗'),
            omp('Which one is correct?', ['Black shoes', 'Shoes black', 'Shoe blacks'], 'Black shoes', '👞'),
            omp('What do you wear when it rains?',      ['A raincoat', 'A swimsuit', 'Sandals'], 'A raincoat'),
            omp('What do you wear to play football?',   ['Sports clothes', 'A suit', 'Pyjamas'], 'Sports clothes'),
        ]),

        en('Colour quiz', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('Is it correct?', 'Answer fast', [
                ['e' => '🍎', 'n' => 'An apple can be red',   'ok' => true],
                ['e' => '🌿', 'n' => 'Grass is purple',       'ok' => false],
                ['e' => '☁️', 'n' => 'Clouds are often white', 'ok' => true],
                ['e' => '🍌', 'n' => 'A banana is yellow',    'ok' => true],
                ['e' => '🌊', 'n' => 'The sea looks orange',  'ok' => false],
                ['e' => '🌙', 'n' => 'The night sky is dark', 'ok' => true],
            ])),
    ],
],

[
    'slug'  => 'hobbies-and-free-time',
    'title' => 'Hobbies and Free Time',
    'description' => 'Deportes, aficiones y cómo decir con qué frecuencia haces algo.',
    'objective' => 'Hablar de aficiones en inglés usando adverbios de frecuencia y el presente simple.',
    'icon' => '⚽', 'nivel' => 'primaria-media', 'bloque' => 'daily-life',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'juego'],
    'estaciones' => [

        en('Hobbies', 'Une la afición con su nombre', '🔗', 'emparejar', [
            ['e' => '⚽', 'w' => 'Playing football'],
            ['e' => '📖', 'w' => 'Reading'],
            ['e' => '🎨', 'w' => 'Painting'],
            ['e' => '🎸', 'w' => 'Playing guitar'],
            ['e' => '🏊', 'w' => 'Swimming'],
            ['e' => '🚴', 'w' => 'Cycling'],
        ]),

        en('How often?', 'Adverbios de frecuencia', '🔁', 'opcion_multiple', [
            omp('I brush my teeth every day. I ___ brush my teeth.', ['always', 'never', 'sometimes'], 'always', '🪥'),
            omp('I don\'t eat insects! I ___ eat insects.', ['never', 'often', 'always'], 'never', '🐛'),
            omp('I go to the cinema two times a year. I ___ go to the cinema.', ['sometimes', 'never', 'always'], 'sometimes', '🎬'),
            omp('I play football four times a week. I ___ play football.', ['often', 'rarely', 'never'], 'often', '⚽'),
            omp('Which sentence is correct?', ['I always eat breakfast', 'I eat always breakfast', 'Always I breakfast eat'], 'I always eat breakfast', '🥣'),
        ]),

        en('Present simple', 'Hablar de lo que haces siempre', '📝', 'opcion_multiple', [
            omp('I ___ football every Saturday.',  ['play', 'plays', 'playing'], 'play'),
            omp('She ___ books every night.',      ['reads', 'read', 'reading'], 'reads'),
            omp('They ___ in the park.',           ['run', 'runs', 'running'], 'run'),
            omp('He ___ the guitar very well.',    ['plays', 'play', 'playing'], 'plays'),
            omp('We ___ swimming on Sundays.',     ['go', 'goes', 'going'], 'go'),
        ]),

        en('Ask a friend', 'Preguntas sobre aficiones', '💬', 'opcion_multiple', [
            omp('You want to know your friend\'s hobby. You ask…', ['What\'s your favourite hobby?', 'Which you hobby?', 'How is your hobby?'], 'What\'s your favourite hobby?'),
            omp('You want to know if your friend likes the pool. You ask…', ['Do you like swimming?', 'You like swim?', 'Are you swimming?'], 'Do you like swimming?', '🏊'),
            omp('Which sentence is correct?', ['I play on Saturdays', 'I playing Saturdays', 'I am play Saturdays'], 'I play on Saturdays', '⚽'),
            omp('Which question is correct?', ['How often do you read?', 'How much you read?', 'When you read?'], 'How often do you read?', '📚'),
            omp('«Do you like it?» A short answer is…', ['Yes, I do', 'Yes, I like', 'Yes, I am'], 'Yes, I do'),
        ]),
    ],
],

[
    'slug'  => 'health-and-feelings',
    'title' => 'Health and Feelings',
    'description' => 'Partes del cuerpo, cómo decir que algo duele y cómo nombrar lo que sientes.',
    'objective' => 'Nombrar partes del cuerpo y emociones en inglés y expresar estados físicos.',
    'icon' => '💚', 'nivel' => 'primaria-superior', 'bloque' => 'daily-life',
    'duracion' => 13, 'tags' => ['ingles', 'cuerpo', 'emociones'],
    'estaciones' => [

        en('Body parts', 'Une la parte con su nombre', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'Eye'],
            ['e' => '👂', 'w' => 'Ear'],
            ['e' => '👃', 'w' => 'Nose'],
            ['e' => '✋', 'w' => 'Hand'],
            ['e' => '🦶', 'w' => 'Foot'],
            ['e' => '🦷', 'w' => 'Tooth'],
        ]),

        en('I have a…', 'Decir qué te duele', '🤒', 'opcion_multiple', [
            omp('Your head hurts. You say…', ['I have a headache', 'I have a stomach', 'I am headache'], 'I have a headache', '🤕'),
            omp('Your tummy hurts. You say…', ['I have a stomachache', 'I have a headache', 'I am stomach'], 'I have a stomachache', '🤢'),
            omp('Your tooth hurts. You say…', ['I have a toothache', 'I have a tooth', 'I am tooth'], 'I have a toothache', '🦷'),
            omp('You are very hot and sick. You say…', ['I have a fever', 'I am fever', 'I do fever'], 'I have a fever', '🤒'),
            omp('Your friend falls down. You ask…', ['Are you OK?', 'Do you OK?', 'Have you OK?'], 'Are you OK?', '🩹'),
        ]),

        en('Feelings', 'Nombrar lo que sientes', '😊', 'opcion_multiple', [
            omp('How does he feel?', ['Happy', 'Sad', 'Angry'], 'Happy', '😀'),
            omp('How does he feel?', ['Sad', 'Happy', 'Tired'], 'Sad', '😢'),
            omp('How does he feel?', ['Angry', 'Scared', 'Bored'], 'Angry', '😠'),
            omp('How does he feel?', ['Tired', 'Excited', 'Proud'], 'Tired', '😴'),
            omp('How does he feel?', ['Scared', 'Calm', 'Glad'], 'Scared', '😨'),
        ]),

        en('Healthy habits', 'Hábitos que cuidan el cuerpo', '🏆', 'desafio_final', [
            reto('How many hours should a child sleep?',   ['Nine to eleven', 'Three', 'Fifteen'], 'Nine to eleven'),
            reto('What should you do before eating?',      ['Wash your hands', 'Watch TV', 'Nothing'], 'Wash your hands'),
            reto('How often should you brush your teeth?', ['Twice a day or more', 'Once a week', 'Never'], 'Twice a day or more'),
            reto('What is good for your body?',            ['Exercise and water', 'Only sweets', 'Sitting all day'], 'Exercise and water'),
            reto('If you feel sad, what can you do?',      ['Talk to someone you trust', 'Keep it inside', 'Get angry'], 'Talk to someone you trust'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · TIME & DISCOVERY
// =====================================================================

[
    'slug'  => 'telling-the-time',
    'title' => 'Telling the Time',
    'description' => 'La hora, los días, los meses y cómo hablar de rutinas en inglés.',
    'objective' => 'Decir la hora en inglés y nombrar días, meses y momentos del día.',
    'icon' => '🕐', 'nivel' => 'primaria-media', 'bloque' => 'time-and-discovery',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'calculo'],
    'estaciones' => [

        en('Days of the week', 'Ordena los días', '📅', 'ordenar_secuencia', [
            'title' => 'Order the days of the week',
            'items' => ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'],
        ]),

        en('What time is it?', 'Decir la hora', '⏰', 'opcion_multiple', [
            omp('What time is it?', ['Three o\'clock', 'Three thirty', 'Half past three'], 'Three o\'clock', '3:00', 'texto'),
            omp('What time is it?', ['Half past four', 'Four o\'clock', 'Quarter past four'], 'Half past four', '4:30', 'texto'),
            omp('What time is it?', ['Quarter past five', 'Quarter to five', 'Half past five'], 'Quarter past five', '5:15', 'texto'),
            omp('What time is it?', ['Quarter to seven', 'Quarter past six', 'Half past six'], 'Quarter to seven', '6:45', 'texto'),
            omp('You want to know the time. You ask…', ['What time is it?', 'What hour is it?', 'How time is it?'], 'What time is it?', '⏰'),
        ]),

        en('Months and seasons', 'Los meses del año', '🗓️', 'opcion_multiple', [
            omp('Which is the first month of the year?', ['January', 'March', 'December'], 'January'),
            omp('Which is the last month of the year?', ['December', 'November', 'January'], 'December'),
            omp('How many months are there in a year?', ['Twelve', 'Ten', 'Fifteen'], 'Twelve'),
            omp('Which sentence is correct? 🎂', ['My birthday is in May', 'My birthday is on May', 'My birthday in May is'], 'My birthday is in May'),
            omp('Which month comes after July?', ['August', 'June', 'September'], 'August'),
        ]),

        en('Daily routine', 'Ordena tu día en inglés', '🔢', 'ordenar_secuencia', [
            'title' => 'Order the daily routine',
            'items' => ['I wake up', 'I have breakfast', 'I go to school', 'I do my homework', 'I go to bed'],
        ]),
    ],
],

[
    'slug'  => 'the-past',
    'title' => 'The Past',
    'description' => 'Hablar de lo que pasó: el pasado simple y cómo era la vida antes.',
    'objective' => 'Usar el pasado simple en inglés para narrar hechos y describir cambios en el tiempo.',
    'icon' => '⏮️', 'nivel' => 'primaria-superior', 'bloque' => 'time-and-discovery',
    'duracion' => 14, 'tags' => ['ingles', 'lectura', 'historia'],
    'estaciones' => [

        en('Was and were', 'El pasado del verbo to be', '🕰️', 'opcion_multiple', [
            omp('I ___ at home yesterday.',     ['was', 'were', 'is'], 'was'),
            omp('They ___ very happy.',         ['were', 'was', 'are'], 'were'),
            omp('She ___ my teacher last year.', ['was', 'were', 'am'], 'was'),
            omp('We ___ in the park.',          ['were', 'was', 'is'], 'were'),
            omp('It ___ a great day.',          ['was', 'were', 'are'], 'was'),
        ]),

        en('Regular past', 'Verbos regulares en pasado', '➕', 'opcion_multiple', [
            omp('play → ___',    ['played', 'plaied', 'playd'], 'played'),
            omp('watch → ___',   ['watched', 'watchd', 'watcheds'], 'watched'),
            omp('study → ___',   ['studied', 'studyed', 'studed'], 'studied'),
            omp('walk → ___',    ['walked', 'walkd', 'walking'], 'walked'),
            omp('In the past, regular verbs end in…', ['-ed', '-ing', '-s'], '-ed'),
        ]),

        en('Irregular past', 'Los que no siguen la regla', '⚠️', 'opcion_multiple', [
            omp('go → ___',     ['went', 'goed', 'gone'], 'went'),
            omp('eat → ___',    ['ate', 'eated', 'eaten'], 'ate'),
            omp('see → ___',    ['saw', 'seed', 'seen'], 'saw'),
            omp('have → ___',   ['had', 'haved', 'has'], 'had'),
            omp('make → ___',   ['made', 'maked', 'making'], 'made'),
        ]),

        en('Life in the past', 'Cómo era antes', '🕯️', 'opcion_multiple', [
            omp('How did people travel 200 years ago?',   ['By horse', 'By plane', 'By car'], 'By horse', '🐎'),
            omp('How did people light their homes?',      ['With candles', 'With LED lamps', 'With phones'], 'With candles', '🕯️'),
            omp('How did people send messages?',          ['By letter', 'By email', 'By video call'], 'By letter', '✉️'),
            omp('What can we learn from the past?',       ['How people lived and why things changed', 'Nothing', 'Only dates'], 'How people lived and why things changed'),
            omp('Which one is correct? ⏳',['A hundred years ago', 'Before hundred years', 'Since hundred years'], 'A hundred years ago'),
        ]),

        en('Past quiz', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('Which sentence is correct?',     ['I went to school yesterday', 'I goed to school yesterday', 'I go to school yesterday'], 'I went to school yesterday'),
            reto('Which sentence is correct?',     ['She was happy', 'She were happy', 'She are happy'], 'She was happy'),
            reto('What is the past of «write»?',   ['wrote', 'writed', 'written'], 'wrote'),
            reto('What is the past of «run»?',     ['ran', 'runned', 'running'], 'ran'),
            reto('«Didn\'t» is the short form of…', ['did not', 'do not', 'does not'], 'did not'),
        ]),
    ],
],

[
    'slug'  => 'inventions-and-space',
    'title' => 'Inventions and Space',
    'description' => 'Inventos que cambiaron la vida y la exploración del espacio, en inglés.',
    'objective' => 'Describir inventos y explorar vocabulario de ciencia y espacio en inglés.',
    'icon' => '🚀', 'nivel' => 'primaria-superior', 'bloque' => 'time-and-discovery',
    'duracion' => 14, 'tags' => ['ingles', 'tecnologia', 'comprension'],
    'estaciones' => [

        en('Great inventions', 'Une el invento con su uso', '🔗', 'emparejar', [
            ['e' => '💡', 'w' => 'Light bulb'],
            ['e' => '📞', 'w' => 'Telephone'],
            ['e' => '🚗', 'w' => 'Car'],
            ['e' => '✈️', 'w' => 'Aeroplane'],
            ['e' => '💻', 'w' => 'Computer'],
            ['e' => '🖨️', 'w' => 'Printer'],
        ]),

        en('Why do we invent?', 'Los inventos resuelven problemas', '💡', 'opcion_multiple', [
            omp('Why did people invent the wheel?',      ['To move heavy things', 'To decorate', 'To eat'], 'To move heavy things'),
            omp('Why did people invent the telephone?',  ['To talk from far away', 'To cook', 'To sleep'], 'To talk from far away'),
            omp('What problem does a fridge solve?',     ['Keeping food fresh', 'Cleaning floors', 'Making noise'], 'Keeping food fresh', '🧊'),
            omp('What did the internet change?',         ['How we find and share information', 'The weather', 'The seasons'], 'How we find and share information'),
            omp('Can an invention have bad effects too?', ['Yes, it depends how we use it', 'No, never', 'Only old ones'], 'Yes, it depends how we use it'),
        ]),

        en('Space words', 'Vocabulario del espacio', '🌌', 'opcion_multiple', [
            omp('What is this?', ['Star', 'Moon', 'Sun'], 'Star', '⭐'),
            omp('What is this?',    ['Planet', 'Rocket', 'Space'], 'Planet', '🪐'),
            omp('What is this?',       ['Moon', 'Sun', 'Sky'], 'Moon', '🌙'),
            omp('Who is this?', ['Astronaut', 'Pilot', 'Driver'], 'Astronaut', '👨‍🚀'),
            omp('What is this?',     ['Rocket', 'Plane', 'Car'], 'Rocket', '🚀'),
        ]),

        en('Talking about the future', 'Will y going to', '🔮', 'opcion_multiple', [
            omp('Which sentence is correct? 📅',['I will go tomorrow', 'I go tomorrow', 'I went tomorrow'], 'I will go tomorrow'),
            omp('You have a plan for tonight. You say…', ['I\'m going to study', 'I will studying', 'I go study'], 'I\'m going to study'),
            omp('You are not sure about the weather. You say…',['It might rain', 'It will rain sure', 'It rained'], 'It might rain'),
            omp('For a plan you already decided, you use…', ['Going to', 'Will always', 'Did'], 'Going to'),
            omp('Which one is correct? 🚀',['In the future', 'On the future', 'At future'], 'In the future'),
        ]),
    ],
],

[
    'slug'  => 'how-we-communicate',
    'title' => 'How We Communicate',
    'description' => 'Formas de comunicarnos, escribir una carta y hablar con respeto en inglés.',
    'objective' => 'Reconocer formas de comunicación y producir mensajes breves en inglés.',
    'icon' => '💬', 'nivel' => 'primaria-superior', 'bloque' => 'time-and-discovery',
    'duracion' => 13, 'tags' => ['ingles', 'escritura', 'convivencia'],
    'estaciones' => [

        en('Ways to communicate', 'Formas de comunicarnos', '📡', 'opcion_multiple', [
            omp('What is verbal communication?',    ['Using words', 'Using gestures only', 'Being silent'], 'Using words'),
            omp('What is non-verbal communication?', ['Gestures and facial expressions', 'Writing letters', 'Speaking loudly'], 'Gestures and facial expressions'),
            omp('A smile is an example of…',        ['Non-verbal communication', 'Verbal communication', 'Writing'], 'Non-verbal communication'),
            omp('Why is listening important?',      ['To understand others', 'To talk more', 'To win'], 'To understand others'),
            omp('What is a good conversation?',     ['Both people speak and listen', 'One person talks only', 'Nobody talks'], 'Both people speak and listen'),
        ]),

        en('Polite English', 'Hablar con cortesía', '🙏', 'opcion_multiple', [
            omp('How do you ask politely?', ['Could you help me, please?', 'Give me that', 'Help now'], 'Could you help me, please?'),
            omp('Someone helps you. You say…', ['Thank you', 'Never mind', 'Go away'], 'Thank you'),
            omp('You make a mistake. You say…', ['I\'m sorry', 'It\'s fine', 'So what'], 'I\'m sorry'),
            omp('It is morning. You say…', ['Good morning', 'Good night', 'Goodbye'], 'Good morning'),
            omp('You leave. You say…', ['Goodbye', 'Hello', 'Please'], 'Goodbye'),
        ]),

        en('Writing a letter', 'Las partes de una carta', '✉️', 'ordenar_secuencia', [
            'title' => 'Order the parts of a friendly letter',
            'items' => ['Date', 'Dear Anna,', 'Message', 'Best wishes,', 'Your name'],
        ]),

        en('Communication quiz', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('What does «Dear» mean at the start of a letter?', ['A polite greeting', 'Expensive', 'Goodbye'], 'A polite greeting'),
            reto('Which is more formal?',            ['Could you please…', 'Gimme', 'Hey you'], 'Could you please…'),
            reto('What should you do before sending a message?', ['Read it again', 'Send it fast', 'Nothing'], 'Read it again'),
            reto('Is it OK to write in capitals all the time?', ['No, it looks like shouting', 'Yes', 'Only at night'], 'No, it looks like shouting'),
            reto('Why learn English?',               ['To communicate with people around the world', 'To copy others', 'For no reason'], 'To communicate with people around the world'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'numbers-and-shapes',
    'title' => 'Numbers and Shapes',
    'description' => 'Números, formas y cómo preguntar cuántos hay, en inglés.',
    'objective' => 'Contar en inglés, nombrar formas y usar How many / How much.',
    'icon' => '🔢', 'nivel' => 'primaria-inicial', 'bloque' => 'my-world',
    'duracion' => 11, 'tags' => ['ingles', 'calculo', 'vocabulario'],
    'estaciones' => [

        en('Count in English', 'Los números del uno al diez', '🔟', 'emparejar', [
            ['e' => '1️⃣', 'w' => 'One'],
            ['e' => '2️⃣', 'w' => 'Two'],
            ['e' => '3️⃣', 'w' => 'Three'],
            ['e' => '4️⃣', 'w' => 'Four'],
            ['e' => '5️⃣', 'w' => 'Five'],
            ['e' => '🔟', 'w' => 'Ten'],
        ]),

        en('How many?', 'Preguntar cantidades', '❓', 'opcion_multiple', [
            omp('You want to know the number of books. You ask…', ['How many books?', 'How much books?', 'What many books?'], 'How many books?'),
            omp('You want to know the amount of water. You ask…', ['How much water?', 'How many water?', 'How many waters?'], 'How much water?'),
            omp('We use «how many» with…', ['things we can count', 'liquids', 'everything'], 'things we can count', '🔢'),
            omp('We use «how much» with…', ['things we cannot count one by one', 'books', 'nothing'], 'things we cannot count one by one', '🥛'),
            omp('How many apples are there?', ['There are five apples', 'There is one apple', 'There are no apples'], 'There are five apples', ['🍎', '🍎', '🍎', '🍎', '🍎'], 'serie'),
        ]),

        en('Shapes', 'Las formas en inglés', '🔷', 'opcion_multiple', [
            omp('What shape is it?',   ['Circle', 'Square', 'Triangle'], 'Circle', '🔴'),
            omp('What shape is it?',  ['Square', 'Circle', 'Star'], 'Square', '🟦'),
            omp('What shape is it?', ['Triangle', 'Rectangle', 'Circle'], 'Triangle', '🔺'),
            omp('What shape is it?',  ['Star', 'Heart', 'Square'], 'Star', '⭐'),
            omp('What shape is it?',   ['Heart', 'Star', 'Circle'], 'Heart', '❤️'),
        ]),

        en('Write the number', 'Escribe el número en inglés', '⌨️', 'teclado',
            ['ONE', 'THREE', 'SEVEN', 'TEN', 'TWELVE']),
    ],
],

[
    'slug'  => 'asking-questions',
    'title' => 'Asking Questions',
    'description' => 'Las palabras que abren una pregunta: what, where, when, who, why, how.',
    'objective' => 'Formular preguntas en inglés usando los pronombres interrogativos.',
    'icon' => '❔', 'nivel' => 'primaria-media', 'bloque' => 'daily-life',
    'duracion' => 12, 'tags' => ['ingles', 'lectura', 'vocabulario'],
    'estaciones' => [

        en('Question words', 'Cada una pregunta algo distinto', '🔗', 'opcion_multiple', [
            omp('Which word asks about a thing?', ['What', 'Where', 'When'], 'What'),
            omp('Which word asks about a place?', ['Where', 'What', 'Who'], 'Where'),
            omp('Which word asks about a time?', ['When', 'Where', 'Why'], 'When'),
            omp('Which word asks about a person?', ['Who', 'What', 'How'], 'Who'),
            omp('Which word asks about a reason?', ['Why', 'When', 'Where'], 'Why'),
        ]),

        en('Complete the question', 'Elige la palabra correcta', '✏️', 'opcion_multiple', [
            omp('___ is your name?',        ['What', 'Where', 'When'], 'What'),
            omp('___ do you live?',         ['Where', 'What', 'Who'], 'Where'),
            omp('___ is your birthday?',    ['When', 'Who', 'How'], 'When'),
            omp('___ is your best friend?', ['Who', 'What', 'Where'], 'Who'),
            omp('___ old are you?',         ['How', 'What', 'Why'], 'How'),
        ]),

        en('Short answers', 'Responder correctamente', '💬', 'opcion_multiple', [
            omp('«Do you like pizza?» Short answer: yes.', ['Yes, I do', 'Yes, I like', 'Yes, I am'], 'Yes, I do'),
            omp('«Are you happy?» Short answer: yes.', ['Yes, I am', 'Yes, I do', 'Yes, I have'], 'Yes, I am'),
            omp('«Can you swim?» Short answer: no.', ['No, I can\'t', 'No, I don\'t', 'No, I am not'], 'No, I can\'t'),
            omp('«Have you got a pet?» Short answer: yes.', ['Yes, I have', 'Yes, I do have', 'Yes, I am'], 'Yes, I have'),
            omp('«Is it raining?» Short answer: no.', ['No, it isn\'t', 'No, it doesn\'t', 'No, it can\'t'], 'No, it isn\'t'),
        ]),

        en('Match question and answer', 'Une la pregunta con su respuesta', '🔗', 'emparejar', [
            ['e' => '👤', 'w' => 'What is your name?'],
            ['e' => '🏠', 'w' => 'Where do you live?'],
            ['e' => '🎂', 'w' => 'How old are you?'],
            ['e' => '🐶', 'w' => 'Have you got a pet?'],
            ['e' => '🍕', 'w' => 'What food do you like?'],
            ['e' => '⚽', 'w' => 'What sport do you play?'],
        ]),
    ],
],

[
    'slug'  => 'comparing-things',
    'title' => 'Comparing Things',
    'description' => 'Comparativos y superlativos: más grande, el más grande, tan alto como.',
    'objective' => 'Formar y usar comparativos y superlativos en inglés.',
    'icon' => '📊', 'nivel' => 'primaria-superior', 'bloque' => 'nature-and-animals',
    'duracion' => 13, 'tags' => ['ingles', 'logica', 'vocabulario'],
    'estaciones' => [

        en('Comparatives', 'Comparar dos cosas', '⚖️', 'opcion_multiple', [
            omp('big → ___',       ['bigger', 'more big', 'biggest'], 'bigger'),
            omp('small → ___',     ['smaller', 'more small', 'smallest'], 'smaller'),
            omp('fast → ___',      ['faster', 'more fast', 'fastest'], 'faster'),
            omp('beautiful → ___', ['more beautiful', 'beautifuller', 'beautifulest'], 'more beautiful'),
            omp('good → ___',      ['better', 'gooder', 'more good'], 'better'),
        ]),

        en('Superlatives', 'El más de todos', '🥇', 'opcion_multiple', [
            omp('big → the ___',   ['biggest', 'bigger', 'most big'], 'biggest'),
            omp('tall → the ___',  ['tallest', 'taller', 'most tall'], 'tallest'),
            omp('good → the ___',  ['best', 'goodest', 'better'], 'best'),
            omp('bad → the ___',   ['worst', 'baddest', 'worse'], 'worst'),
            omp('interesting → the ___', ['most interesting', 'interestingest', 'more interesting'], 'most interesting'),
        ]),

        en('Compare the animals', 'Usa lo aprendido', '🐘', 'opcion_multiple', [
            omp('An elephant is ___ than a mouse.',     ['bigger', 'smaller', 'the biggest'], 'bigger', '🐘'),
            omp('A cheetah is the ___ land animal.',    ['fastest', 'faster', 'fast'], 'fastest', '🐆'),
            omp('A mouse is ___ than an elephant.',     ['smaller', 'bigger', 'the smallest'], 'smaller', '🐭'),
            omp('A giraffe is ___ than a horse.',       ['taller', 'shorter', 'the tallest'], 'taller', '🦒'),
            omp('Ana is 1.20 m. Tom is 1.20 m. Ana is ___ Tom.', ['as tall as', 'more tall as', 'the tallest as'], 'as tall as', '📏'),
        ]),

        en('English crossword', 'Cada pista está en inglés', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'TALLER',  'pista' => 'A giraffe is ___ than a horse'],
                ['w' => 'FASTEST', 'pista' => 'The cheetah is the ___ land animal'],
                ['w' => 'BIGGER',  'pista' => 'An elephant is ___ than a mouse'],
                ['w' => 'BEST',    'pista' => 'The superlative of «good»'],
                ['w' => 'SMALL',   'pista' => 'The opposite of «big»'],
                ['w' => 'HEAVY',   'pista' => 'Something difficult to lift is ___'],
            ])),

        en('Comparison quiz', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('Which is correct?',        ['My bag is heavier than yours', 'My bag is more heavy than yours', 'My bag is heaviest than yours'], 'My bag is heavier than yours'),
            reto('Which is correct?',        ['This is the best book', 'This is the goodest book', 'This is the more good book'], 'This is the best book'),
            reto('When do we add «-er»?',    ['With short adjectives', 'With long adjectives', 'Always'], 'With short adjectives'),
            reto('When do we use «more»?',   ['With long adjectives', 'With short adjectives', 'Never'], 'With long adjectives'),
            reto('Which symbol means «the same as»?', ['=', '>', '<'], '='),
        ]),
    ],
],

[
    'slug'  => 'reading-in-english',
    'title' => 'Reading in English',
    'description' => 'Un texto corto en inglés y preguntas para comprobar qué se entendió.',
    'objective' => 'Comprender un texto breve en inglés e identificar información clave.',
    'icon' => '📘', 'nivel' => 'primaria-superior', 'bloque' => 'time-and-discovery',
    'duracion' => 13, 'tags' => ['ingles', 'lectura', 'comprension'],
    'estaciones' => [

        en('A short story', 'Read and answer', '📖', 'cuento', [
            'slides' => [
                ['img' => '🏫', 'text' => 'Maya is nine years old. She lives in Cartagena, near the sea.'],
                ['img' => '🐢', 'text' => 'Every Saturday, Maya helps at the turtle centre on the beach.'],
                ['img' => '🥚', 'text' => 'The turtles lay their eggs in the sand. Maya protects the nests.'],
                ['img' => '🌊', 'text' => 'When the baby turtles hatch, Maya helps them reach the water.'],
                ['img' => '😊', 'text' => 'Maya wants to be a marine biologist when she grows up.'],
            ],
            'qs' => [
                reto('How old is Maya?',              ['Nine', 'Ten', 'Seven'], 'Nine'),
                reto('Where does she live?',          ['In Cartagena', 'In Bogotá', 'In Medellín'], 'In Cartagena'),
                reto('What does she do on Saturdays?', ['She helps at the turtle centre', 'She plays football', 'She studies'], 'She helps at the turtle centre'),
                reto('Where do turtles lay their eggs?', ['In the sand', 'In the water', 'In trees'], 'In the sand'),
                reto('What does Maya want to be?',    ['A marine biologist', 'A teacher', 'A doctor'], 'A marine biologist'),
            ],
        ]),

        en('New words', 'Vocabulario del texto', '🔗', 'emparejar', [
            ['e' => '🐢', 'w' => 'Turtle'],
            ['e' => '🥚', 'w' => 'Egg'],
            ['e' => '🏖️', 'w' => 'Beach'],
            ['e' => '🌊', 'w' => 'Sea'],
            ['e' => '🏝️', 'w' => 'Sand'],
            ['e' => '🔬', 'w' => 'Biologist'],
        ]),

        en('True or false', 'Comprueba lo que entendiste', '⚡', 'juego_rapido',
            conTitulo('Is it true about the story?', 'Answer fast', [
                ['e' => '🐢', 'n' => 'Maya helps turtles',        'ok' => true],
                ['e' => '🏔️', 'n' => 'Maya lives in the mountains', 'ok' => false],
                ['e' => '🌊', 'n' => 'The centre is on the beach', 'ok' => true],
                ['e' => '⚽', 'n' => 'Maya plays football on Saturdays', 'ok' => false],
                ['e' => '🥚', 'n' => 'Turtles lay eggs in the sand', 'ok' => true],
                ['e' => '👨‍🚀', 'n' => 'Maya wants to be an astronaut', 'ok' => false],
            ])),

        /*
         * Completar huecos en inglés. Aquí el ejercicio no es traducir una
         * palabra suelta: es entender la frase entera para saber cuál
         * encaja, que es lo que la malla llama «usar el contexto».
         */
        en('Complete the text', 'Coloca cada palabra en su hueco', '📝', 'completar_texto',
            conTitulo('Complete the text', 'Read the whole sentence before you choose', [
                [
                    'titulo' => 'Maya and the turtles',
                    'texto'  => 'Maya lives near the ___. Every Saturday she ___ at the turtle '
                              . 'centre. The turtles lay their ___ in the sand, and Maya protects the '
                              . 'nests. When the babies ___, she helps them reach the water.',
                    'huecos' => ['sea', 'helps', 'eggs', 'hatch'],
                    'extra'  => ['mountain', 'swims', 'wings'],
                ],
                [
                    'titulo' => 'A day at school',
                    'texto'  => 'I ___ up at seven o\'clock. I have ___ with my family and then I '
                              . '___ to school by bus. My favourite ___ is science.',
                    'huecos' => ['wake', 'breakfast', 'go', 'subject'],
                    'extra'  => ['sleep', 'dinner', 'teacher'],
                ],
            ])),

        en('Reading strategies', 'Cómo leer mejor en otro idioma', '💡', 'opcion_multiple', [
            omp('You don\'t know a word. What do you do first?', ['Keep reading and guess from the context', 'Stop reading', 'Close the book'], 'Keep reading and guess from the context', '📖'),
            omp('Do you need to understand every word?', ['No, the main idea is enough', 'Yes, every word', 'Only the title'], 'No, the main idea is enough', '💡'),
            omp('What helps you guess what a text is about?', ['The title and the pictures', 'The last line', 'Nothing'], 'The title and the pictures', '🖼️'),
            omp('Is it good to read a text two times?', ['Yes, it is easier the second time', 'No', 'Only if it is short'], 'Yes, it is easier the second time', '🔁'),
            omp('«Animal» and «color» look almost the same in English and in Spanish. They are…', ['cognates', 'mistakes', 'verbs'], 'cognates', '🔤'),
        ]),
    ],
],


[
    'slug'  => 'days-and-routines',
    'title' => 'Days and Routines',
    'description' => 'Contar lo que haces cada día en inglés, con verbos de rutina y horas.',
    'objective' => 'Describir la rutina diaria en inglés usando el presente simple.',
    'icon' => '🌅', 'nivel' => 'primaria-media', 'bloque' => 'daily-life',
    'duracion' => 12, 'tags' => ['ingles', 'secuencias', 'vocabulario'],
    'estaciones' => [

        en('Routine verbs', 'Une la acción con su verbo', '🔗', 'emparejar', [
            ['e' => '⏰', 'w' => 'Wake up'],
            ['e' => '🚿', 'w' => 'Take a shower'],
            ['e' => '🥣', 'w' => 'Have breakfast'],
            ['e' => '🏫', 'w' => 'Go to school'],
            ['e' => '📚', 'w' => 'Do homework'],
            ['e' => '😴', 'w' => 'Go to bed'],
        ]),

        en('My day', 'Cuenta tu rutina', '📝', 'opcion_multiple', [
            omp('It is 7:00 and you get up. You say…', ['I wake up at seven', 'I wake up in seven', 'I wake at seven'], 'I wake up at seven', '⏰'),
            omp('Which sentence is correct? 🚌',    ['I go to school by bus', 'I go school in bus', 'I going school bus'], 'I go to school by bus'),
            omp('Which sentence is correct? 🥐', ['She has breakfast at eight', 'She have breakfast at eight', 'She has breakfast in eight'], 'She has breakfast at eight'),
            omp('I get up ___ seven o\'clock.', ['at', 'in', 'on'], 'at', '⏰'),
            omp('I play football ___ Saturday.', ['on', 'at', 'in'], 'on', '⚽'),
        ]),

        en('Before and after', 'El orden de las acciones', '↔️', 'opcion_multiple', [
            omp('I have breakfast ___ I go to school.', ['before', 'after that I', 'while to'], 'before'),
            omp('I brush my teeth ___ breakfast.',      ['after', 'before that', 'in'], 'after'),
            omp('___, I wake up. Then I have breakfast.', ['First', 'Then', 'Finally'], 'First', '1️⃣'),
            omp('First I wake up. ___ I have breakfast.', ['Then', 'First', 'Before'], 'Then', '2️⃣'),
            omp('First I study. Then I eat. ___, I go to bed.', ['Finally', 'First', 'Then'], 'Finally', '3️⃣'),
        ]),

        en('Order the day', 'Ordena la rutina en inglés', '🔢', 'ordenar_secuencia', [
            'title' => 'Order the daily routine',
            'items' => ['I wake up', 'I take a shower', 'I have breakfast', 'I go to school', 'I go to bed'],
        ]),
    ],
],

[
    'slug'  => 'places-and-directions',
    'title' => 'Places and Directions',
    'description' => 'Preguntar dónde está algo y entender las indicaciones, en inglés.',
    'objective' => 'Pedir y dar indicaciones de ubicación en inglés.',
    'icon' => '🧭', 'nivel' => 'primaria-superior', 'bloque' => 'my-world',
    'duracion' => 12, 'tags' => ['ingles', 'geografia', 'vocabulario'],
    'estaciones' => [

        en('Where is it?', 'Preposiciones de lugar', '📍', 'opcion_multiple', [
            omp('The bank is ___ the school and the park. (entre)', ['between', 'behind', 'in front of'], 'between'),
            omp('The car is ___ the house. From the street you see the car first.',  ['in front of', 'behind', 'under'], 'in front of'),
            omp('The garden is ___ the house. From the street you cannot see it.', ['behind', 'in front of', 'on'], 'behind'),
            omp('The shop is where two streets meet. It is…', ['on the corner', 'in the corner of', 'at corner'], 'on the corner'),
            omp('To get to the library, you cross the street. Where is it?', ['across the street', 'in the street', 'under the street'], 'across the street', '🚸'),
        ]),

        en('Giving directions', 'Indicar el camino', '➡️', 'opcion_multiple', [
            omp('Which sentence is correct? ⬆️',  ['Go straight for two blocks', 'Go direct two blocks', 'Straight two blocks go'], 'Go straight for two blocks'),
            omp('Which sentence is correct? 🚦', ['Turn left at the traffic light', 'Turn left in traffic light', 'Left turn the light'], 'Turn left at the traffic light'),
            omp('Look at the arrow. Where is it?', ['It\'s on the right', 'It\'s in the right', 'It\'s at right'], 'It\'s on the right', '➡️'),
            omp('You are looking for the hospital. You ask…', ['Excuse me, where is the hospital?', 'Sorry, what is hospital?', 'Please, hospital where?'], 'Excuse me, where is the hospital?'),
            omp('The beach is 100 km away. You say…', ['It\'s very far', 'It\'s very near', 'It\'s very long'], 'It\'s very far', '🏖️'),
        ]),

        en('Transport', 'Cómo llegar', '🚌', 'emparejar', [
            ['e' => '🚌', 'w' => 'By bus'],
            ['e' => '🚗', 'w' => 'By car'],
            ['e' => '🚲', 'w' => 'By bike'],
            ['e' => '🚶', 'w' => 'On foot'],
            ['e' => '✈️', 'w' => 'By plane'],
            ['e' => '🚆', 'w' => 'By train'],
        ]),

        en('Directions quiz', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('Which sentence asks for directions?', ['How do I get to the park?', 'What is the park?', 'Who is the park?'], 'How do I get to the park?'),
            reto('«Next to» means…', ['beside', 'under', 'on top of'], 'beside'),
            reto('«Opposite» means…', ['across from', 'next to', 'inside'], 'across from'),
            reto('Someone says «turn right». Where do you go?', ['➡️', '⬆️', '↩️'], '➡️'),
            reto('How do you thank someone for directions?', ['Thank you very much', 'Please', 'Excuse me'], 'Thank you very much'),
        ]),
    ],
],


[
    'slug'  => 'jobs-and-people',
    'title' => 'Jobs and People',
    'description' => 'Oficios en inglés y cómo describir lo que hace cada persona.',
    'objective' => 'Nombrar oficios en inglés y describir actividades laborales.',
    'icon' => '👷', 'nivel' => 'primaria-media', 'bloque' => 'my-world',
    'duracion' => 12, 'tags' => ['ingles', 'oficios', 'vocabulario'],
    'estaciones' => [

        en('Jobs', 'Une el oficio con su nombre', '🔗', 'emparejar', [
            ['e' => '👩‍⚕️', 'w' => 'Doctor'],
            ['e' => '👩‍🏫', 'w' => 'Teacher'],
            ['e' => '👨‍🚒', 'w' => 'Firefighter'],
            ['e' => '👮', 'w' => 'Police officer'],
            ['e' => '👨‍🍳', 'w' => 'Cook'],
            ['e' => '👨‍🌾', 'w' => 'Farmer'],
        ]),

        en('What do they do?', 'Describir el trabajo', '💼', 'opcion_multiple', [
            omp('A doctor ___ sick people.',      ['helps', 'help', 'helping'], 'helps'),
            omp('A teacher works at a ___.',      ['school', 'hospital', 'farm'], 'school'),
            omp('A firefighter puts out ___.',    ['fires', 'books', 'plants'], 'fires'),
            omp('A farmer grows ___.',            ['food', 'houses', 'cars'], 'food'),
            omp('You want to know your mother\'s job. Which question is correct?',  ['What does your mother do?', 'What make your mother?', 'Who is your mother do?'], 'What does your mother do?'),
        ]),

        en('Describing people', 'Cómo es alguien', '👤', 'opcion_multiple', [
            omp('Which sentence is correct?', ['She is tall', 'She has tall', 'She tall'], 'She is tall', '👩'),
            omp('Which sentence is correct?', ['She has curly hair', 'She is curly hair', 'She have curly hair'], 'She has curly hair', '👩‍🦱'),
            omp('Which sentence is correct?', ['He is kind', 'He has kind', 'He kind'], 'He is kind', '😊'),
            omp('For hair and eyes we use…', ['have / has', 'be', 'do'], 'have / has'),
            omp('For words like «kind» or «tall» we use…',['be: am, is, are', 'have', 'do'], 'be: am, is, are'),
        ]),

        en('Jobs quiz', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('Who helps animals?',       ['A vet', 'A pilot', 'A baker'], 'A vet'),
            reto('Who flies a plane?',       ['A pilot', 'A driver', 'A nurse'], 'A pilot'),
            reto('Who builds houses?',       ['A builder', 'A dentist', 'A singer'], 'A builder'),
            reto('Who sells things in a shop?', ['A shop assistant', 'A doctor', 'A farmer'], 'A shop assistant'),
            reto('You want to build bridges when you grow up. You say…', ['I want to be an engineer', 'I want be engineer', 'I want to engineer'], 'I want to be an engineer'),
        ]),
    ],
],

[
    'slug'  => 'sports-and-games',
    'title' => 'Sports and Games',
    'description' => 'Deportes y juegos en inglés, con los verbos play, go y do.',
    'objective' => 'Nombrar deportes en inglés y usar correctamente play, go y do.',
    'icon' => '🏅', 'nivel' => 'primaria-media', 'bloque' => 'daily-life',
    'duracion' => 12, 'tags' => ['ingles', 'juego', 'vocabulario'],
    'estaciones' => [

        en('Sports', 'Une el deporte con su nombre', '🔗', 'emparejar', [
            ['e' => '⚽', 'w' => 'Football'],
            ['e' => '🏀', 'w' => 'Basketball'],
            ['e' => '🏐', 'w' => 'Volleyball'],
            ['e' => '🏊', 'w' => 'Swimming'],
            ['e' => '🎾', 'w' => 'Tennis'],
            ['e' => '🚴', 'w' => 'Cycling'],
        ]),

        en('Play, go or do?', 'Cada deporte lleva su verbo', '🔤', 'opcion_multiple', [
            omp('___ football',    ['play', 'go', 'do'], 'play'),
            omp('___ swimming',    ['go', 'play', 'do'], 'go'),
            omp('___ karate',      ['do', 'play', 'go'], 'do'),
            omp('___ basketball',  ['play', 'go', 'do'], 'play'),
            omp('___ running',     ['go', 'play', 'do'], 'go'),
        ]),

        en('Talking about sports', 'Expresar gustos y habilidad', '💬', 'opcion_multiple', [
            omp('You know how to swim. You say…', ['I can swim', 'I know swim', 'I swim can'], 'I can swim', '🏊'),
            omp('You don\'t know how to play tennis. You say…', ['I can\'t play tennis', 'I no play tennis', 'I don\'t can play tennis'], 'I can\'t play tennis'),
            omp('Which one is correct? 🏅', ['My favourite sport is…', 'My sport favourite is…', 'The favourite my sport…'], 'My favourite sport is…'),
            omp('Which sentence is correct? 📅', ['I play every Saturday', 'I playing every Saturday', 'I play all Saturday'], 'I play every Saturday'),
            omp('You ask a new friend about sports. You say…', ['Do you play any sport?', 'You play sport?', 'Are you play sport?'], 'Do you play any sport?'),
        ]),

        en('Sports quiz', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('Is it correct?', 'Answer fast', [
                ['e' => '⚽', 'n' => 'We play football',      'ok' => true],
                ['e' => '🏊', 'n' => 'We play swimming',      'ok' => false],
                ['e' => '🏀', 'n' => 'We play basketball',    'ok' => true],
                ['e' => '🥋', 'n' => 'We go karate',          'ok' => false],
                ['e' => '🚴', 'n' => 'We go cycling',         'ok' => true],
                ['e' => '🎾', 'n' => 'We do tennis',          'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'feelings-and-friends',
    'title' => 'Feelings and Friends',
    'description' => 'Hablar de emociones y de amistad en inglés, y ofrecer ayuda.',
    'objective' => 'Expresar emociones en inglés y usar fórmulas de apoyo y cortesía.',
    'icon' => '💛', 'nivel' => 'primaria-superior', 'bloque' => 'daily-life',
    'duracion' => 12, 'tags' => ['ingles', 'emociones', 'convivencia'],
    'estaciones' => [

        en('How do you feel?', 'Nombrar la emoción', '😊', 'emparejar', [
            ['e' => '😀', 'w' => 'Happy'],
            ['e' => '😢', 'w' => 'Sad'],
            ['e' => '😠', 'w' => 'Angry'],
            ['e' => '😨', 'w' => 'Scared'],
            ['e' => '😴', 'w' => 'Tired'],
            ['e' => '😲', 'w' => 'Surprised'],
        ]),

        en('Saying how you feel', 'Estructuras útiles', '💬', 'opcion_multiple', [
            omp('You want to sleep. You say…', ['I am tired', 'I have tired', 'I tired'], 'I am tired', '😴'),
            omp('You ask a friend about feelings. You say…',   ['How do you feel?', 'How are you feel?', 'What you feel?'], 'How do you feel?'),
            omp('You were sick. Now you are OK. You say…', ['I feel better', 'I feel gooder', 'I am feel better'], 'I feel better', '🙂'),
            omp('You think about the test all night. You say…', ['I am worried', 'I have worried', 'I worry am'], 'I am worried', '😟'),
            omp('Your friend says «Sorry!». You say…',        ['It\'s OK', 'It\'s nothing pass', 'No pass nothing'], 'It\'s OK'),
        ]),

        en('Helping a friend', 'Ofrecer apoyo', '🤝', 'opcion_multiple', [
            omp('Your friend is crying. You ask…', ['Are you OK?', 'Do you OK?', 'You are OK?'], 'Are you OK?', '😢'),
            omp('You want to help a friend. You ask…',    ['Can I help you?', 'Can I helping you?', 'I can help you?'], 'Can I help you?'),
            omp('You break your friend\'s pencil. You say…',           ['I\'m sorry', 'I sorry', 'Sorry I am not'], 'I\'m sorry'),
            omp('Your friend is nervous. You say…',     ['Don\'t worry', 'No worry', 'Not you worry'], 'Don\'t worry'),
            omp('Your friend needs help. You say…',      ['You can count on me', 'You count me', 'Count with me'], 'You can count on me'),
        ]),

        en('Friendship quiz', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('A friend is sad. What can you say?', ['Are you OK? Do you want to talk?', 'Go away', 'Nothing'], 'Are you OK? Do you want to talk?'),
            reto('Someone helped you. You say…',    ['Thank you for helping me', 'Thanks for help me', 'Thank for helping'], 'Thank you for helping me'),
            reto('Which face is «kind»?', ['😊', '😠', '😴'], '😊'),
            reto('«To share» means…', ['to give part of what you have', 'to hide something', 'to run fast'], 'to give part of what you have', '🤲'),
            reto('Why is it good to talk about feelings?', ['It helps us understand each other', 'It is not good', 'Only adults do it'], 'It helps us understand each other'),
        ]),
    ],
],

],

'reasignar' => [],

];
