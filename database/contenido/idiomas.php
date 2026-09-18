<?php
/**
 * idiomas.php — Idiomas
 *
 * La categoría se llama «Idiomas» y no «Inglés» a propósito: el contenido
 * que existe hoy es de inglés, pero el nombre no cierra la puerta a otras
 * lenguas más adelante. Cambiar de nombre una categoría con cientos de
 * actividades dentro es mucho más caro que elegirlo bien ahora.
 *
 * Regla de escritura: las instrucciones van en español y el contenido en
 * inglés. Un niño de 5 años que aún no lee bien en su lengua no puede
 * además descifrar la consigna; lo que debe encontrarse en inglés es la
 * palabra que está aprendiendo, no la orden de qué hacer.
 *
 * Las estaciones declaran `en-US` para que el motor las lea con voz
 * inglesa. Sin eso, «cat» sonaría «kat» y enseñaría lo contrario.
 */

declare(strict_types=1);

/** Todas las estaciones de esta categoría se leen en inglés. */
const VOZ_EN = 'en-US';

return [

'categoria' => [
    'slug'       => 'idiomas',
    'name'       => 'Idiomas',
    'tagline'    => 'Primeras palabras y frases en inglés, jugando',
    'icon'       => '🌐',
    'color'      => '#26c6da',
    'sort_order' => 10,
],

'bloques' => [
    ['slug' => 'first-words', 'name' => 'First Words', 'icon' => '👋', 'sort_order' => 1,
     'description' => 'Las primeras palabras en inglés: colores, animales, cuerpo y saludos.'],
    ['slug' => 'words-and-sentences', 'name' => 'Words & Sentences', 'icon' => '🔤', 'sort_order' => 2,
     'description' => 'De la palabra suelta a la frase completa.'],
    ['slug' => 'english-challenges', 'name' => 'English Challenges', 'icon' => '🏆', 'sort_order' => 3,
     'description' => 'Leer, entender y conversar en inglés.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE 1 · FIRST WORDS (3 a 5 años)
// =====================================================================

[
    'slug'  => 'colors-hunt',
    'title' => 'Colors Hunt',
    'description' => 'Aprende los colores en inglés escuchando, repitiendo y buscando.',
    'objective' => 'Reconocer y pronunciar los colores básicos en inglés.',
    'icon' => '🎨', 'nivel' => 'preescolar', 'bloque' => 'first-words',
    'duracion' => 10, 'tags' => ['ingles', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        est('Listen and repeat', 'Escucha cada color y repítelo en voz alta', '🔊', 'pronunciacion', [
            ['e' => '🔴', 'w' => 'Red'],
            ['e' => '🔵', 'w' => 'Blue'],
            ['e' => '🟡', 'w' => 'Yellow'],
            ['e' => '🟢', 'w' => 'Green'],
            ['e' => '⚫', 'w' => 'Black'],
            ['e' => '⚪', 'w' => 'White'],
        ], VOZ_EN),

        est('What color is it?', 'Elige el nombre correcto en inglés', '🎨', 'opcion_multiple', [
            omp('¿Cómo se dice este color en inglés?', ['Red', 'Blue', 'Green'], 'Red', '🔴'),
            omp('¿Cómo se dice este color en inglés?', ['Blue', 'Yellow', 'Black'], 'Blue', '🔵'),
            omp('¿Cómo se dice este color en inglés?', ['Yellow', 'White', 'Red'], 'Yellow', '🟡'),
            omp('¿Cómo se dice este color en inglés?', ['Green', 'Blue', 'Orange'], 'Green', '🟢'),
            omp('¿Cómo se dice este color en inglés?', ['Orange', 'Purple', 'Pink'], 'Orange', '🟠'),
            omp('¿Cómo se dice este color en inglés?', ['Purple', 'Brown', 'Green'], 'Purple', '🟣'),
        ], VOZ_EN),

        est('Match the color', 'Une cada color con su palabra', '🔗', 'emparejar', [
            ['e' => '🔴', 'w' => 'Red'],
            ['e' => '🔵', 'w' => 'Blue'],
            ['e' => '🟡', 'w' => 'Yellow'],
            ['e' => '🟢', 'w' => 'Green'],
            ['e' => '🟠', 'w' => 'Orange'],
            ['e' => '🟣', 'w' => 'Purple'],
        ], VOZ_EN),

        est('Color memory', 'Encuentra las parejas de colores', '🧠', 'memoria',
            ['🔴', '🔵', '🟡', '🟢', '🟠', '🟣']),
    ],
],

[
    'slug'  => 'animals-english',
    'title' => 'Animals',
    'description' => 'Los animales en inglés: escucha, repite y reconoce.',
    'objective' => 'Nombrar animales comunes en inglés y distinguir dónde viven.',
    'icon' => '🐶', 'nivel' => 'preescolar', 'bloque' => 'first-words',
    'duracion' => 10, 'tags' => ['ingles', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        est('Listen and repeat', 'Escucha cada animal y repítelo', '🔊', 'pronunciacion', [
            ['e' => '🐶', 'w' => 'Dog'],
            ['e' => '🐱', 'w' => 'Cat'],
            ['e' => '🐦', 'w' => 'Bird'],
            ['e' => '🐟', 'w' => 'Fish'],
            ['e' => '🐴', 'w' => 'Horse'],
            ['e' => '🐮', 'w' => 'Cow'],
        ], VOZ_EN),

        est('What animal is it?', 'Elige el nombre correcto', '🐾', 'opcion_multiple', [
            omp('¿Qué animal es?', ['Dog', 'Cat', 'Bird'], 'Dog', '🐶'),
            omp('¿Qué animal es?', ['Cat', 'Cow', 'Fish'], 'Cat', '🐱'),
            omp('¿Qué animal es?', ['Fish', 'Horse', 'Bird'], 'Fish', '🐟'),
            omp('¿Qué animal es?', ['Lion', 'Bear', 'Duck'], 'Lion', '🦁'),
            omp('¿Qué animal es?', ['Elephant', 'Monkey', 'Rabbit'], 'Elephant', '🐘'),
            omp('¿Qué animal es?', ['Monkey', 'Cow', 'Frog'], 'Monkey', '🐵'),
        ], VOZ_EN),

        est('Match animal and word', 'Une cada animal con su palabra en inglés', '🔗', 'emparejar', [
            ['e' => '🐰', 'w' => 'Rabbit'],
            ['e' => '🐸', 'w' => 'Frog'],
            ['e' => '🦆', 'w' => 'Duck'],
            ['e' => '🐷', 'w' => 'Pig'],
            ['e' => '🐻', 'w' => 'Bear'],
            ['e' => '🐝', 'w' => 'Bee'],
        ], VOZ_EN),

        est('Farm animals', 'Toca solo los animales de la granja', '🚜', 'seleccion_imagenes',
            conTitulo('Touch the farm animals', 'Toca solo los animales de la granja', [
                ['e' => '🐮', 'n' => 'Cow',      'ok' => true],
                ['e' => '🐷', 'n' => 'Pig',      'ok' => true],
                ['e' => '🐔', 'n' => 'Chicken',  'ok' => true],
                ['e' => '🐴', 'n' => 'Horse',    'ok' => true],
                ['e' => '🦁', 'n' => 'Lion',     'ok' => false],
                ['e' => '🐧', 'n' => 'Penguin',  'ok' => false],
                ['e' => '🐘', 'n' => 'Elephant', 'ok' => false],
                ['e' => '🦈', 'n' => 'Shark',    'ok' => false],
            ]), VOZ_EN),
    ],
],

[
    'slug'  => 'my-body-english',
    'title' => 'My Body',
    'description' => 'Las partes del cuerpo en inglés, señalando y repitiendo.',
    'objective' => 'Nombrar en inglés las partes principales del cuerpo y la cara.',
    'icon' => '🧍', 'nivel' => 'preescolar', 'bloque' => 'first-words',
    'duracion' => 10, 'tags' => ['ingles', 'vocabulario', 'cuerpo'],
    'estaciones' => [

        est('Listen and repeat', 'Escucha y señala esa parte en tu cuerpo', '🔊', 'pronunciacion', [
            ['e' => '👁️', 'w' => 'Eye'],
            ['e' => '👃', 'w' => 'Nose'],
            ['e' => '👄', 'w' => 'Mouth'],
            ['e' => '👂', 'w' => 'Ear'],
            ['e' => '✋', 'w' => 'Hand'],
            ['e' => '🦶', 'w' => 'Foot'],
        ], VOZ_EN),

        est('How do you say it?', 'Del español al inglés', '🔤', 'opcion_multiple', [
            omp('¿Cómo se dice «mano» en inglés?',   ['Hand', 'Foot', 'Head'], 'Hand', '✋'),
            omp('¿Cómo se dice «ojo» en inglés?',    ['Eye', 'Ear', 'Arm'], 'Eye', '👁️'),
            omp('¿Cómo se dice «boca» en inglés?',   ['Mouth', 'Nose', 'Hair'], 'Mouth', '👄'),
            omp('¿Cómo se dice «pie» en inglés?',    ['Foot', 'Leg', 'Hand'], 'Foot', '🦶'),
            omp('¿Cómo se dice «cabeza» en inglés?', ['Head', 'Heart', 'Hand'], 'Head', '🧠'),
            omp('¿Cómo se dice «oreja» en inglés?',  ['Ear', 'Eye', 'Arm'], 'Ear', '👂'),
        ], VOZ_EN),

        est('Match the body part', 'Une cada parte con su palabra', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'Eye'],
            ['e' => '👃', 'w' => 'Nose'],
            ['e' => '👄', 'w' => 'Mouth'],
            ['e' => '✋', 'w' => 'Hand'],
            ['e' => '🦵', 'w' => 'Leg'],
            ['e' => '💇', 'w' => 'Hair'],
        ], VOZ_EN),

        est('Parts of the face', 'Toca solo lo que está en la cara', '😀', 'seleccion_imagenes',
            conTitulo('Touch the parts of the face', 'Toca solo lo que está en la cara', [
                ['e' => '👁️', 'n' => 'Eye',   'ok' => true],
                ['e' => '👃', 'n' => 'Nose',  'ok' => true],
                ['e' => '👄', 'n' => 'Mouth', 'ok' => true],
                ['e' => '👂', 'n' => 'Ear',   'ok' => true],
                ['e' => '🦶', 'n' => 'Foot',  'ok' => false],
                ['e' => '✋', 'n' => 'Hand',  'ok' => false],
                ['e' => '🦵', 'n' => 'Leg',   'ok' => false],
            ]), VOZ_EN),
    ],
],

[
    'slug'  => 'hello-english',
    'title' => 'Hello!',
    'description' => 'Saludar, despedirse y dar las gracias en inglés.',
    'objective' => 'Usar saludos y fórmulas de cortesía básicas en inglés según la situación.',
    'icon' => '👋', 'nivel' => 'preescolar', 'bloque' => 'first-words',
    'duracion' => 10, 'tags' => ['ingles', 'convivencia', 'pronunciacion'],
    'estaciones' => [

        est('Listen and repeat', 'Escucha y repite cada saludo', '🔊', 'pronunciacion', [
            ['e' => '👋', 'w' => 'Hello'],
            ['e' => '🙋', 'w' => 'Good morning'],
            ['e' => '🌙', 'w' => 'Good night'],
            ['e' => '🙏', 'w' => 'Thank you'],
            ['e' => '🤲', 'w' => 'Please'],
            ['e' => '👋', 'w' => 'Goodbye'],
        ], VOZ_EN),

        est('What do you say?', 'Elige qué dirías en cada momento', '💬', 'opcion_multiple', [
            omp('Llegas al salón por la mañana. ¿Qué dices?',   ['Good morning!', 'Good night!', 'Goodbye!'], 'Good morning!', '🌅'),
            omp('Te vas para tu casa. ¿Qué dices?',             ['Goodbye!', 'Hello!', 'Please'], 'Goodbye!', '🚪'),
            omp('Alguien te da un regalo. ¿Qué dices?',         ['Thank you!', 'Goodbye!', 'Good night!'], 'Thank you!', '🎁'),
            omp('Quieres pedir algo con amabilidad. Dices…',    ['Please', 'Goodbye', 'Good night'], 'Please', '🤲'),
            omp('Te vas a dormir. ¿Qué dices?',                 ['Good night!', 'Good morning!', 'Thank you!'], 'Good night!', '🌙'),
            omp('Te preguntan «How are you?». Puedes decir…',   ['I am fine, thank you', 'Good night', 'Please'], 'I am fine, thank you', '🙂'),
        ], VOZ_EN),

        est('Order the conversation', 'Pon la conversación en orden', '💬', 'ordenar_secuencia', [
            'title' => 'Ordena esta conversación en inglés',
            'items' => ['Hello!', 'How are you?', 'I am fine, thank you', 'Goodbye!'],
        ], VOZ_EN),
    ],
],


// =====================================================================
//  BLOQUE 2 · WORDS & SENTENCES (6 a 8 años)
// =====================================================================

[
    'slug'  => 'match-the-word',
    'title' => 'Match the Word',
    'description' => 'Relaciona objetos, comidas y lugares con su palabra en inglés.',
    'objective' => 'Ampliar el vocabulario en inglés y empezar a escribirlo correctamente.',
    'icon' => '🔗', 'nivel' => 'primaria-inicial', 'bloque' => 'words-and-sentences',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'escritura'],
    'estaciones' => [

        est('School objects', 'Une cada objeto del colegio con su palabra', '🎒', 'emparejar', [
            ['e' => '📕', 'w' => 'Book'],
            ['e' => '✏️', 'w' => 'Pencil'],
            ['e' => '🎒', 'w' => 'Backpack'],
            ['e' => '📏', 'w' => 'Ruler'],
            ['e' => '✂️', 'w' => 'Scissors'],
            ['e' => '🪑', 'w' => 'Chair'],
        ], VOZ_EN),

        est('Food', 'Une cada alimento con su palabra', '🍎', 'emparejar', [
            ['e' => '🍎', 'w' => 'Apple'],
            ['e' => '🍌', 'w' => 'Banana'],
            ['e' => '🍞', 'w' => 'Bread'],
            ['e' => '🥛', 'w' => 'Milk'],
            ['e' => '🧀', 'w' => 'Cheese'],
            ['e' => '🥚', 'w' => 'Egg'],
        ], VOZ_EN),

        est('Translate it', 'Del español al inglés', '🔤', 'opcion_multiple', [
            omp('¿Cómo se dice «casa» en inglés?',    ['House', 'Horse', 'Mouse'], 'House', '🏠'),
            omp('¿Cómo se dice «agua» en inglés?',    ['Water', 'Winter', 'Wall'], 'Water', '💧'),
            omp('¿Cómo se dice «escuela» en inglés?', ['School', 'Shoe', 'Ship'], 'School', '🏫'),
            omp('¿Cómo se dice «amigo» en inglés?',   ['Friend', 'Family', 'Father'], 'Friend', '🧑‍🤝‍🧑'),
            omp('¿Cómo se dice «libro» en inglés?',   ['Book', 'Box', 'Ball'], 'Book', '📕'),
            omp('¿Cómo se dice «sol» en inglés?',     ['Sun', 'Son', 'Snow'], 'Sun', '☀️'),
        ], VOZ_EN),

        est('How is it written?', 'Elige la escritura correcta', '✍️', 'ortografia', [
            ['e' => '🍎', 'opts' => ['Apple', 'Aple', 'Appel'],    'correct' => 'Apple'],
            ['e' => '🏠', 'opts' => ['House', 'Hause', 'Hous'],    'correct' => 'House'],
            ['e' => '🏫', 'opts' => ['School', 'Scool', 'Shcool'], 'correct' => 'School'],
            ['e' => '🧀', 'opts' => ['Cheese', 'Chees', 'Chesse'], 'correct' => 'Cheese'],
            ['e' => '🧑‍🤝‍🧑', 'opts' => ['Friend', 'Freind', 'Frend'], 'correct' => 'Friend'],
            ['e' => '💧', 'opts' => ['Water', 'Wather', 'Watter'], 'correct' => 'Water'],
        ], VOZ_EN),
    ],
],

[
    'slug'  => 'build-the-sentence',
    'title' => 'Build the Sentence',
    'description' => 'Ordena las palabras hasta formar una frase completa en inglés.',
    'objective' => 'Reconocer el orden básico de la frase en inglés: sujeto, verbo y complemento.',
    'icon' => '🧩', 'nivel' => 'primaria-inicial', 'bloque' => 'words-and-sentences',
    'duracion' => 12, 'tags' => ['ingles', 'escritura', 'logica'],
    'estaciones' => [

        est('My family', 'Ordena las palabras de la frase', '🧩', 'ordenar_secuencia', [
            'title' => 'Ordena la frase: «Ella es mi hermana»',
            'items' => ['She', 'is', 'my', 'sister'],
        ], VOZ_EN),

        est('Which one is correct?', 'Solo una frase está bien escrita', '✅', 'opcion_multiple', [
            omp('¿Cuál frase está bien escrita?', ['I have a cat', 'Cat a have I', 'Have cat I a'], 'I have a cat', '🐱'),
            omp('¿Cuál frase está bien escrita?', ['The dog is black', 'Black dog the is', 'Is the black dog'], 'The dog is black', '🐶'),
            omp('¿Cuál frase está bien escrita?', ['My name is Ana', 'Name my Ana is', 'Is Ana my name'], 'My name is Ana', '🙋'),
            omp('¿Cuál frase está bien escrita?', ['I like apples', 'Apples I like the', 'Like I apples am'], 'I like apples', '🍎'),
            omp('¿Cuál frase está bien escrita?', ['She is my teacher', 'Teacher my is she', 'My she teacher is'], 'She is my teacher', '👩‍🏫'),
        ], VOZ_EN),

        est('Complete the sentence', 'Elige la palabra que falta', '🔤', 'opcion_multiple', [
            omp('I ___ a student.',        ['am', 'is', 'are'], 'am', '🧒'),
            omp('She ___ my friend.',      ['is', 'am', 'are'], 'is', '👧'),
            omp('They ___ happy.',         ['are', 'is', 'am'], 'are', '😀'),
            omp('The cat ___ black.',      ['is', 'am', 'are'], 'is', '🐈‍⬛'),
            omp('We ___ in the park.',     ['are', 'is', 'am'], 'are', '🏞️'),
            omp('He ___ my brother.',      ['is', 'are', 'am'], 'is', '👦'),
        ], VOZ_EN),

        est('At the park', 'Ordena una frase más larga', '🧩', 'ordenar_secuencia', [
            'title' => 'Ordena la frase: «Yo juego con mis amigos en el parque»',
            'items' => ['I', 'play', 'with', 'my', 'friends', 'in', 'the', 'park'],
        ], VOZ_EN),
    ],
],

[
    'slug'  => 'what-is-it',
    'title' => 'What is it?',
    'description' => 'Reconoce y escribe en inglés los objetos de la casa y del colegio.',
    'objective' => 'Asociar objeto y palabra en inglés, y escribirla sin ayuda.',
    'icon' => '❓', 'nivel' => 'primaria-inicial', 'bloque' => 'words-and-sentences',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'escritura'],
    'estaciones' => [

        est('In the classroom', '¿Qué objeto es?', '🏫', 'opcion_multiple', [
            omp('What is it?', ['A book', 'A chair', 'A door'], 'A book', '📕'),
            omp('What is it?', ['A chair', 'A table', 'A window'], 'A chair', '🪑'),
            omp('What is it?', ['A pencil', 'A ruler', 'A bag'], 'A pencil', '✏️'),
            omp('What is it?', ['A clock', 'A door', 'A book'], 'A clock', '🕐'),
            omp('What is it?', ['A window', 'A wall', 'A floor'], 'A window', '🪟'),
        ], VOZ_EN),

        est('At home', 'Objetos de la casa', '🏠', 'opcion_multiple', [
            omp('What is it?', ['A bed', 'A chair', 'A car'], 'A bed', '🛏️'),
            omp('What is it?', ['A door', 'A book', 'A cat'], 'A door', '🚪'),
            omp('What is it?', ['A sofa', 'A tree', 'A train'], 'A sofa', '🛋️'),
            omp('What is it?', ['A spoon', 'A shoe', 'A ship'], 'A spoon', '🥄'),
            omp('What is it?', ['A cup', 'A cap', 'A cat'], 'A cup', '☕'),
        ], VOZ_EN),

        est('Type the word', 'Escribe la palabra en inglés', '⌨️', 'teclado',
            ['BOOK', 'CHAIR', 'TABLE', 'DOOR', 'WINDOW', 'HOUSE'], VOZ_EN),

        est('Spelling check', 'Elige cómo se escribe', '✍️', 'ortografia', [
            ['e' => '🪟', 'opts' => ['Window', 'Windo', 'Windou'], 'correct' => 'Window'],
            ['e' => '🛋️', 'opts' => ['Sofa', 'Sofá', 'Soffa'],     'correct' => 'Sofa'],
            ['e' => '🚪', 'opts' => ['Door', 'Dor', 'Doar'],       'correct' => 'Door'],
            ['e' => '🛏️', 'opts' => ['Bed', 'Bad', 'Bedd'],        'correct' => 'Bed'],
            ['e' => '🥄', 'opts' => ['Spoon', 'Spon', 'Sponn'],    'correct' => 'Spoon'],
        ], VOZ_EN),
    ],
],

[
    'slug'  => 'my-daily-routine',
    'title' => 'My Daily Routine',
    'description' => 'Cuenta en inglés lo que haces cada día, de la mañana a la noche.',
    'objective' => 'Describir la rutina diaria en inglés usando verbos de uso frecuente.',
    'icon' => '⏰', 'nivel' => 'primaria-inicial', 'bloque' => 'words-and-sentences',
    'duracion' => 12, 'tags' => ['ingles', 'secuencias', 'vocabulario'],
    'estaciones' => [

        est('My day', 'Ordena tu día en inglés', '🌅', 'ordenar_secuencia', [
            'title' => 'Ordena las acciones del día, de la mañana a la noche',
            'items' => [
                'I wake up',
                'I brush my teeth',
                'I go to school',
                'I have lunch',
                'I do my homework',
                'I go to bed',
            ],
        ], VOZ_EN),

        est('When do you do it?', 'Mañana, tarde o noche', '🕐', 'opcion_multiple', [
            omp('When do you wake up?',        ['In the morning', 'At night', 'In the afternoon'], 'In the morning', '🌅'),
            omp('When do you sleep?',          ['At night', 'In the morning', 'At noon'], 'At night', '🌙'),
            omp('When do you have breakfast?', ['In the morning', 'At night', 'In the evening'], 'In the morning', '🥐'),
            omp('When do you have dinner?',    ['In the evening', 'In the morning', 'At noon'], 'In the evening', '🍽️'),
            omp('When do you go to school?',   ['In the morning', 'At midnight', 'At night'], 'In the morning', '🏫'),
        ], VOZ_EN),

        est('Match the action', 'Une cada acción con su palabra', '🔗', 'emparejar', [
            ['e' => '🛏️', 'w' => 'Sleep'],
            ['e' => '🍽️', 'w' => 'Eat'],
            ['e' => '📖', 'w' => 'Read'],
            ['e' => '🏃', 'w' => 'Run'],
            ['e' => '✏️', 'w' => 'Write'],
            ['e' => '🚿', 'w' => 'Shower'],
        ], VOZ_EN),

        est('Listen and repeat', 'Practica la pronunciación de los verbos', '🔊', 'pronunciacion', [
            ['e' => '🛏️', 'w' => 'I go to bed'],
            ['e' => '🌅', 'w' => 'I wake up'],
            ['e' => '🍽️', 'w' => 'I have lunch'],
            ['e' => '🏫', 'w' => 'I go to school'],
            ['e' => '📖', 'w' => 'I read a book'],
        ], VOZ_EN),
    ],
],


// =====================================================================
//  BLOQUE 3 · ENGLISH CHALLENGES (9 a 12 años)
// =====================================================================

[
    'slug'  => 'english-detective',
    'title' => 'English Detective',
    'description' => 'Lee una historia en inglés y descubre qué pasó.',
    'objective' => 'Comprender un texto corto en inglés y deducir información a partir de él.',
    'icon' => '🕵️', 'nivel' => 'primaria-media', 'bloque' => 'english-challenges',
    'duracion' => 15, 'tags' => ['ingles', 'comprension', 'logica', 'reto'],
    'estaciones' => [

        est('The missing cake', 'Lee la historia y responde', '🍰', 'cuento', [
            'slides' => [
                ['img' => '🍰', 'text' => 'Mom made a cake. She put it on the kitchen table.'],
                ['img' => '🐱', 'text' => 'The cat was sleeping on the sofa. The dog was in the garden.'],
                ['img' => '🚪', 'text' => 'Ana came home at four o\'clock. She was very hungry.'],
                ['img' => '😮', 'text' => 'At five o\'clock the cake was gone. There were crumbs on Ana\'s plate.'],
                ['img' => '🕵️', 'text' => 'The cat was still sleeping. The dog was still outside. Who ate the cake?'],
            ],
            'qs' => [
                reto('Where did Mom put the cake?', ['On the kitchen table', 'In the garden', 'On the sofa'], 'On the kitchen table'),
                reto('What time did Ana come home?', ['At four o\'clock', 'At five o\'clock', 'At six o\'clock'], 'At four o\'clock'),
                reto('Where was the dog?', ['In the garden', 'On the sofa', 'In the kitchen'], 'In the garden'),
                reto('Who ate the cake?', ['Ana', 'The cat', 'The dog'], 'Ana'),
                reto('Which clue tells you the answer?', ['The crumbs on Ana\'s plate', 'The sleeping cat', 'The kitchen table'], 'The crumbs on Ana\'s plate'),
            ],
        ], VOZ_EN),

        est('Words in context', '¿Qué significa cada palabra?', '📖', 'opcion_multiple', [
            omp('«Hungry» significa…',  ['Con hambre', 'Cansado', 'Feliz'], 'Con hambre', '🍽️'),
            omp('«Garden» significa…',  ['Jardín', 'Cocina', 'Cuarto'], 'Jardín', '🌷'),
            omp('«Gone» significa…',    ['Ya no está', 'Muy grande', 'Dormido'], 'Ya no está', '💨'),
            omp('«Crumbs» significa…',  ['Migas', 'Cucharas', 'Manchas'], 'Migas', '🍞'),
            omp('«Clue» significa…',    ['Pista', 'Puerta', 'Reloj'], 'Pista', '🔍'),
        ], VOZ_EN),

        // La sopa lleva idioma aunque no lo parezca: al encontrar una
        // palabra el motor la lee en voz alta, y sin esto «cake» sonaría
        // «ka-ke». Se había quedado sin declararlo.
        est('Word search', 'Encuentra las palabras del cuento', '🔍', 'sopa_letras',
            sopa(['CAKE', 'TABLE', 'GARDEN', 'HUNGRY', 'PLATE'], 10), VOZ_EN),

        est('Detective challenge', 'Demuestra que entendiste', '🏆', 'desafio_final', [
            reto('What is the opposite of «hungry»?',  ['Full', 'Happy', 'Fast'], 'Full'),
            reto('«She was sleeping» is in…',           ['The past', 'The future', 'The present'], 'The past'),
            reto('Choose the correct question:',        ['Where is the cake?', 'Where the cake is?', 'Is where the cake?'], 'Where is the cake?'),
            reto('«There were crumbs» — how many crumbs?', ['More than one', 'Only one', 'None'], 'More than one'),
        ], VOZ_EN),
    ],
],

[
    'slug'  => 'complete-the-conversation',
    'title' => 'Complete the Conversation',
    'description' => 'Responde bien en situaciones reales: la tienda, el colegio, un viaje.',
    'objective' => 'Sostener intercambios cortos en inglés eligiendo la respuesta adecuada.',
    'icon' => '💬', 'nivel' => 'primaria-media', 'bloque' => 'english-challenges',
    'duracion' => 15, 'tags' => ['ingles', 'comprension', 'convivencia'],
    'estaciones' => [

        est('Choose the reply', '¿Qué responderías?', '💬', 'opcion_multiple', [
            omp('«What is your name?»',        ['My name is Ana', 'I am fine', 'It is Monday'], 'My name is Ana', '🙋'),
            omp('«How old are you?»',          ['I am ten years old', 'I am from Colombia', 'It is blue'], 'I am ten years old', '🎂'),
            omp('«Where are you from?»',       ['I am from Colombia', 'I am fine', 'I have a cat'], 'I am from Colombia', '🌎'),
            omp('«What time is it?»',          ['It is three o\'clock', 'I am ten', 'It is my sister'], 'It is three o\'clock', '🕒'),
            omp('«Thank you!»',                ['You are welcome', 'Good night', 'I am sorry'], 'You are welcome', '🙏'),
            omp('«How is the weather today?»', ['It is sunny', 'I am eleven', 'She is my friend'], 'It is sunny', '☀️'),
        ], VOZ_EN),

        est('At the shop', 'Ordena la conversación en la tienda', '🛒', 'ordenar_secuencia', [
            'title' => 'Ordena esta conversación en la tienda',
            'items' => [
                'Good morning!',
                'Can I help you?',
                'I want two apples, please',
                'That is one dollar',
                'Here you are',
                'Thank you. Goodbye!',
            ],
        ], VOZ_EN),

        est('Asking questions', 'Elige la pregunta correcta', '❓', 'opcion_multiple', [
            omp('Quieres saber la hora. Preguntas…',        ['What time is it?', 'How are you?', 'Where is it?'], 'What time is it?', '🕐'),
            omp('Quieres saber dónde está el baño. Preguntas…', ['Where is the bathroom?', 'What is the bathroom?', 'Who is the bathroom?'], 'Where is the bathroom?', '🚻'),
            omp('Quieres saber cuánto cuesta. Preguntas…',  ['How much is it?', 'How many is it?', 'How old is it?'], 'How much is it?', '💵'),
            omp('Quieres saber su nombre. Preguntas…',      ['What is your name?', 'Where is your name?', 'How is your name?'], 'What is your name?', '🙋'),
            omp('No entendiste algo. Puedes decir…',        ['Can you repeat, please?', 'Goodbye!', 'You are welcome'], 'Can you repeat, please?', '🔁'),
        ], VOZ_EN),

        est('Travel challenge', 'Un reto final de conversación', '🏆', 'desafio_final', [
            reto('At the airport you say:', ['Here is my passport', 'Here is my dinner', 'Here is my homework'], 'Here is my passport'),
            reto('You are lost. You say:', ['Excuse me, where is the station?', 'I am ten years old', 'It is sunny today'], 'Excuse me, where is the station?'),
            reto('Someone helps you. You say:', ['Thank you very much', 'Good night', 'How much is it?'], 'Thank you very much'),
            reto('You want to order food. You say:', ['I would like a sandwich, please', 'I am a sandwich', 'Where is a sandwich?'], 'I would like a sandwich, please'),
            reto('You meet someone new. You say:', ['Nice to meet you', 'See you yesterday', 'I am welcome'], 'Nice to meet you'),
        ], VOZ_EN),
    ],
],

],
];
