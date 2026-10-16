<?php
/**
 * idiomas-aleman.php — Deutsch: Kindergarten y Klasse 1–2
 *
 * Primera fase del alemán, sobre las mallas del Colegio Alemán (Deutsche
 * Schule, `MallasPrimaria/ALEMAN`, Klasse 1 a 6). Klasse 1 y 2 son
 * alfabetización en alemán como segunda lengua (libro Zebra): sonidos
 * iniciales, primeras palabras, temas del día a día y las fiestas
 * alemanas comparadas con las colombianas. Preescolar no tiene malla en
 * la carpeta: toma los temas de Klasse 1 en su versión más sencilla,
 * como hace inglés.
 *
 * Mismas reglas que inglés (idiomas.php):
 *   - Instrucciones en español, contenido en alemán. El motor decide la
 *     voz frase por frase: lo que está en español se oye en español.
 *   - Toda estación declara `de-DE` con `deu()`.
 *
 * Y las del alemán:
 *   - Los sustantivos van SIEMPRE con su artículo (der, die, das) y con
 *     mayúscula: en alemán el artículo es parte de la palabra y la
 *     mayúscula de los sustantivos es la primera regla de ortografía.
 *   - Sin ß en sopas de letras ni crucigramas (en mayúscula pasa a «SS»).
 *     En los juegos de escribir el motor pone botones ä ö ü ß.
 *
 * Fase 2 (Klasse 3–6, A1 y A2) se escribe después de que un docente de
 * alemán revise esta.
 */

declare(strict_types=1);

$idiomas = require __DIR__ . '/idiomas-ampliacion.php';

return [

'categoria' => array_merge($idiomas['categoria'], [
    'tagline' => 'Primeras palabras y frases en inglés y alemán, jugando',
]),

'bloques' => [
    ['slug' => 'deutsch-kindergarten', 'name' => 'Deutsch · Kindergarten', 'icon' => '🇩🇪', 'sort_order' => 8,
     'description' => 'Las primeras palabras en alemán: colores, animales, cuerpo y saludos.'],
    ['slug' => 'deutsch-erste-woerter', 'name' => 'Deutsch · Erste Wörter', 'icon' => '🔤', 'sort_order' => 9,
     'description' => 'Alemán de primero y segundo: el colegio, los números, la familia, la comida, las fiestas y las primeras reglas de escritura.'],
],

'actividades' => [


// =====================================================================
//  DEUTSCH · KINDERGARTEN (3 a 5 años)
// =====================================================================

[
    'slug'  => 'deutsch-die-farben',
    'title' => 'Die Farben',
    'description' => 'Los colores en alemán: escucha, repite y busca.',
    'objective' => 'Reconocer y pronunciar los colores básicos en alemán.',
    'icon' => '🎨', 'nivel' => 'preescolar', 'bloque' => 'deutsch-kindergarten',
    'duracion' => 10, 'tags' => ['aleman', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha cada color y repítelo', '🔊', 'pronunciacion', [
            ['e' => '🔴', 'w' => 'rot'],
            ['e' => '🔵', 'w' => 'blau'],
            ['e' => '🟡', 'w' => 'gelb'],
            ['e' => '🟢', 'w' => 'grün'],
            ['e' => '⚫', 'w' => 'schwarz'],
            ['e' => '⚪', 'w' => 'weiß'],
        ]),

        deu('Welche Farbe ist das?', 'Elige el color en alemán', '🎨', 'opcion_multiple', [
            omp('¿Cómo se dice este color en alemán?', ['rot', 'blau', 'grün'], 'rot', '🔴'),
            omp('¿Cómo se dice este color en alemán?', ['blau', 'gelb', 'schwarz'], 'blau', '🔵'),
            omp('¿Cómo se dice este color en alemán?', ['gelb', 'weiß', 'rot'], 'gelb', '🟡'),
            omp('¿Cómo se dice este color en alemán?', ['grün', 'blau', 'orange'], 'grün', '🟢'),
            omp('¿Cómo se dice este color en alemán?', ['orange', 'lila', 'braun'], 'orange', '🟠'),
            omp('¿Cómo se dice este color en alemán?', ['lila', 'braun', 'grün'], 'lila', '🟣'),
        ]),

        deu('Farben verbinden', 'Une cada color con su palabra', '🔗', 'emparejar', [
            ['e' => '🟠', 'w' => 'orange'],
            ['e' => '🟣', 'w' => 'lila'],
            ['e' => '🟤', 'w' => 'braun'],
            ['e' => '🔵', 'w' => 'blau'],
            ['e' => '🟡', 'w' => 'gelb'],
            ['e' => '🟢', 'w' => 'grün'],
        ]),

        deu('Alles, was rot ist', 'Toca todo lo que es rojo', '🍎', 'seleccion_imagenes',
            conTitulo('Toca todo lo que es rojo', 'Alles, was rot ist', [
                ['e' => '🍎', 'n' => 'der Apfel',        'ok' => true],
                ['e' => '🍓', 'n' => 'die Erdbeere',     'ok' => true],
                ['e' => '🚒', 'n' => 'das Feuerwehrauto', 'ok' => true],
                ['e' => '❤️', 'n' => 'das Herz',         'ok' => true],
                ['e' => '🍌', 'n' => 'die Banane',       'ok' => false],
                ['e' => '🥒', 'n' => 'die Gurke',        'ok' => false],
                ['e' => '🐳', 'n' => 'der Wal',          'ok' => false],
                ['e' => '🌻', 'n' => 'die Sonnenblume',  'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'deutsch-die-tiere',
    'title' => 'Die Tiere',
    'description' => 'Los animales en alemán, cada uno con su artículo.',
    'objective' => 'Nombrar animales comunes en alemán con su artículo y distinguir los de la granja.',
    'icon' => '🐶', 'nivel' => 'preescolar', 'bloque' => 'deutsch-kindergarten',
    'duracion' => 10, 'tags' => ['aleman', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha cada animal y repítelo', '🔊', 'pronunciacion', [
            ['e' => '🐶', 'w' => 'der Hund'],
            ['e' => '🐱', 'w' => 'die Katze'],
            ['e' => '🐦', 'w' => 'der Vogel'],
            ['e' => '🐟', 'w' => 'der Fisch'],
            ['e' => '🐴', 'w' => 'das Pferd'],
            ['e' => '🐮', 'w' => 'die Kuh'],
        ]),

        deu('Welches Tier ist das?', 'Elige el nombre correcto', '🐾', 'opcion_multiple', [
            omp('¿Qué animal es?', ['der Hund', 'die Katze', 'der Vogel'], 'der Hund', '🐶'),
            omp('¿Qué animal es?', ['die Katze', 'die Kuh', 'der Fisch'], 'die Katze', '🐱'),
            omp('¿Qué animal es?', ['der Fisch', 'das Pferd', 'der Vogel'], 'der Fisch', '🐟'),
            omp('¿Qué animal es?', ['der Löwe', 'der Bär', 'die Ente'], 'der Löwe', '🦁'),
            omp('¿Qué animal es?', ['der Elefant', 'der Affe', 'der Hase'], 'der Elefant', '🐘'),
            omp('¿Qué animal es?', ['der Affe', 'die Kuh', 'der Frosch'], 'der Affe', '🐵'),
        ]),

        deu('Tiere verbinden', 'Une cada animal con su palabra en alemán', '🔗', 'emparejar', [
            ['e' => '🐰', 'w' => 'der Hase'],
            ['e' => '🐸', 'w' => 'der Frosch'],
            ['e' => '🦆', 'w' => 'die Ente'],
            ['e' => '🐷', 'w' => 'das Schwein'],
            ['e' => '🐻', 'w' => 'der Bär'],
            ['e' => '🐝', 'w' => 'die Biene'],
        ]),

        deu('Auf dem Bauernhof', 'Toca solo los animales de la granja', '🚜', 'seleccion_imagenes',
            conTitulo('Toca solo los animales de la granja', 'Tiere auf dem Bauernhof', [
                ['e' => '🐮', 'n' => 'die Kuh',      'ok' => true],
                ['e' => '🐷', 'n' => 'das Schwein',  'ok' => true],
                ['e' => '🐔', 'n' => 'das Huhn',     'ok' => true],
                ['e' => '🐴', 'n' => 'das Pferd',    'ok' => true],
                ['e' => '🦁', 'n' => 'der Löwe',     'ok' => false],
                ['e' => '🐧', 'n' => 'der Pinguin',  'ok' => false],
                ['e' => '🐘', 'n' => 'der Elefant',  'ok' => false],
                ['e' => '🦈', 'n' => 'der Hai',      'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'deutsch-mein-koerper',
    'title' => 'Mein Körper',
    'description' => 'Las partes del cuerpo en alemán.',
    'objective' => 'Nombrar las partes visibles del cuerpo en alemán con su artículo.',
    'icon' => '🖐️', 'nivel' => 'preescolar', 'bloque' => 'deutsch-kindergarten',
    'duracion' => 10, 'tags' => ['aleman', 'vocabulario', 'cuerpo'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha y repite cada parte del cuerpo', '🔊', 'pronunciacion', [
            ['e' => '👁️', 'w' => 'das Auge'],
            ['e' => '👂', 'w' => 'das Ohr'],
            ['e' => '👃', 'w' => 'die Nase'],
            ['e' => '👄', 'w' => 'der Mund'],
            ['e' => '✋', 'w' => 'die Hand'],
            ['e' => '🦶', 'w' => 'der Fuß'],
        ]),

        deu('Was ist das?', 'Elige la parte del cuerpo', '❓', 'opcion_multiple', [
            omp('¿Qué parte del cuerpo es?', ['das Ohr', 'die Nase', 'das Auge'], 'das Ohr', '👂'),
            omp('¿Qué parte del cuerpo es?', ['die Nase', 'der Mund', 'die Hand'], 'die Nase', '👃'),
            omp('¿Qué parte del cuerpo es?', ['der Mund', 'das Ohr', 'der Fuß'], 'der Mund', '👄'),
            omp('¿Qué parte del cuerpo es?', ['die Hand', 'der Fuß', 'der Arm'], 'die Hand', '✋'),
            omp('¿Qué parte del cuerpo es?', ['das Bein', 'der Arm', 'der Kopf'], 'das Bein', '🦵'),
            omp('¿Qué parte del cuerpo es?', ['der Arm', 'das Bein', 'die Hand'], 'der Arm', '💪'),
        ]),

        deu('Körper verbinden', 'Une cada dibujo con su palabra', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'das Auge'],
            ['e' => '👂', 'w' => 'das Ohr'],
            ['e' => '👃', 'w' => 'die Nase'],
            ['e' => '👄', 'w' => 'der Mund'],
            ['e' => '🦵', 'w' => 'das Bein'],
            ['e' => '🦶', 'w' => 'der Fuß'],
        ]),

        deu('Memory', 'Encuentra las parejas', '🧠', 'memoria',
            ['👁️', '👂', '👃', '👄', '✋', '🦶']),
    ],
],

[
    'slug'  => 'deutsch-hallo',
    'title' => 'Hallo!',
    'description' => 'Saludar, despedirse y dar las gracias en alemán.',
    'objective' => 'Usar saludos, despedidas y palabras de cortesía en alemán según el momento.',
    'icon' => '👋', 'nivel' => 'preescolar', 'bloque' => 'deutsch-kindergarten',
    'duracion' => 10, 'tags' => ['aleman', 'pronunciacion', 'convivencia'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha y repite cada saludo', '🔊', 'pronunciacion', [
            ['e' => '👋', 'w' => 'Hallo!'],
            ['e' => '🌅', 'w' => 'Guten Morgen!'],
            ['e' => '🌙', 'w' => 'Gute Nacht!'],
            ['e' => '🙏', 'w' => 'Danke!'],
            ['e' => '🤲', 'w' => 'Bitte!'],
            ['e' => '🚶', 'w' => 'Tschüss!'],
        ]),

        deu('Was sagst du?', '¿Qué dices en cada momento?', '💬', 'opcion_multiple', [
            omp('Llegas al colegio por la mañana. ¿Qué dices?', ['Guten Morgen!', 'Gute Nacht!', 'Tschüss!'], 'Guten Morgen!', '🌅'),
            omp('Te vas a dormir. ¿Qué dices?', ['Gute Nacht!', 'Guten Morgen!', 'Hallo!'], 'Gute Nacht!', '🌙'),
            omp('Te dan un regalo. ¿Qué dices?', ['Danke!', 'Tschüss!', 'Hallo!'], 'Danke!', '🎁'),
            omp('Te despides de tu amigo. ¿Qué dices?', ['Tschüss!', 'Danke!', 'Guten Morgen!'], 'Tschüss!', '🚶'),
            omp('Ves a tu amiga en el parque. ¿Qué dices?', ['Hallo!', 'Gute Nacht!', 'Bitte!'], 'Hallo!', '🛝'),
        ]),

        deu('Tag und Nacht', 'Une cada momento del día con su saludo', '🔗', 'emparejar', [
            ['e' => '🌅', 'w' => 'Guten Morgen'],
            ['e' => '☀️', 'w' => 'Guten Tag'],
            ['e' => '🌆', 'w' => 'Guten Abend'],
            ['e' => '🌙', 'w' => 'Gute Nacht'],
            ['e' => '👋', 'w' => 'Hallo'],
            ['e' => '🙏', 'w' => 'Danke'],
        ]),

        deu('Wie heißt du?', 'Preguntar y decir el nombre', '🙋', 'opcion_multiple', [
            omp('«¿Cómo te llamas?» en alemán es…', ['Wie heißt du?', 'Wie alt bist du?', 'Wo bist du?'], 'Wie heißt du?', '🙋'),
            omp('Para decir «Me llamo Ana»…', ['Ich heiße Ana.', 'Ich habe Ana.', 'Du heißt Ana.'], 'Ich heiße Ana.', '👧'),
            omp('Para decir «Me llamo Tom»…', ['Ich heiße Tom.', 'Ich bin sechs.', 'Tschüss, Tom.'], 'Ich heiße Tom.', '👦'),
        ]),
    ],
],


// =====================================================================
//  DEUTSCH · ERSTE WÖRTER — KLASSE 1
// =====================================================================

[
    'slug'  => 'deutsch-mein-klassenzimmer',
    'title' => 'Mein Klassenzimmer',
    'description' => 'Los útiles y objetos del salón en alemán, y el sonido con que empieza cada palabra.',
    'objective' => 'Nombrar los útiles escolares con su artículo e identificar el sonido inicial (Anlaut) de cada palabra.',
    'icon' => '🎒', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'lectura'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Los útiles del colegio en alemán', '🔊', 'pronunciacion', [
            ['e' => '✏️', 'w' => 'der Bleistift'],
            ['e' => '🖍️', 'w' => 'der Buntstift'],
            ['e' => '📕', 'w' => 'das Buch'],
            ['e' => '📓', 'w' => 'das Heft'],
            ['e' => '✂️', 'w' => 'die Schere'],
            ['e' => '🎒', 'w' => 'die Schultasche'],
        ]),

        deu('Was ist das?', 'Elige el nombre en alemán', '❓', 'opcion_multiple', [
            omp('¿Qué es?', ['der Bleistift', 'das Buch', 'die Schere'], 'der Bleistift', '✏️'),
            omp('¿Qué es?', ['das Lineal', 'das Heft', 'der Stuhl'], 'das Lineal', '📏'),
            omp('¿Qué es?', ['die Schultasche', 'die Schere', 'der Tisch'], 'die Schultasche', '🎒'),
            omp('¿Qué es?', ['der Stuhl', 'der Tisch', 'das Buch'], 'der Stuhl', '🪑'),
            omp('¿Qué es?', ['das Heft', 'das Lineal', 'die Tafel'], 'das Heft', '📓'),
            omp('¿Qué es?', ['die Schere', 'der Buntstift', 'die Schultasche'], 'die Schere', '✂️'),
        ]),

        deu('Der Anfangslaut', '¿Con qué sonido empieza la palabra?', '🔤', 'opcion_multiple', [
            omp('¿Con qué sonido empieza «Buch»?', ['B', 'D', 'P'], 'B', '📕'),
            omp('¿Con qué sonido empieza «Heft»?', ['H', 'F', 'E'], 'H', '📓'),
            omp('¿Con qué sonido empieza «Schere»?', ['Sch', 'S', 'Ch'], 'Sch', '✂️'),
            omp('¿Con qué sonido empieza «Lineal»?', ['L', 'N', 'I'], 'L', '📏'),
            omp('¿Con qué sonido empieza «Apfel»?', ['A', 'E', 'O'], 'A', '🍎'),
        ]),

        deu('Schreib das Wort', 'Escribe la palabra en alemán', '⌨️', 'teclado',
            ['Buch', 'Heft', 'Stift', 'Schere', 'Lineal']),
    ],
],

[
    'slug'  => 'deutsch-zahlen-und-kalender',
    'title' => 'Zahlen und Kalender',
    'description' => 'Los números hasta el 20, los días de la semana, los meses y las estaciones en alemán.',
    'objective' => 'Contar hasta 20 en alemán y nombrar en orden los días, los meses y las estaciones del año.',
    'icon' => '📅', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 13, 'tags' => ['aleman', 'vocabulario', 'calculo'],
    'estaciones' => [

        deu('Zählen bis zehn', 'Escucha y repite los números', '🔊', 'pronunciacion', [
            ['e' => '1️⃣', 'w' => 'eins'],
            ['e' => '2️⃣', 'w' => 'zwei'],
            ['e' => '3️⃣', 'w' => 'drei'],
            ['e' => '4️⃣', 'w' => 'vier'],
            ['e' => '5️⃣', 'w' => 'fünf'],
            ['e' => '6️⃣', 'w' => 'sechs'],
            ['e' => '7️⃣', 'w' => 'sieben'],
            ['e' => '8️⃣', 'w' => 'acht'],
            ['e' => '9️⃣', 'w' => 'neun'],
            ['e' => '🔟', 'w' => 'zehn'],
        ]),

        deu('Welche Zahl ist das?', 'Los números hasta el 20', '🔢', 'opcion_multiple', [
            omp('¿Qué número es?', ['sieben', 'acht', 'neun'], 'sieben', '7️⃣'),
            omp('¿Qué número es?', ['elf', 'eins', 'acht'], 'elf', '11', 'texto'),
            omp('¿Qué número es?', ['zwölf', 'zwei', 'zwanzig'], 'zwölf', '12', 'texto'),
            omp('¿Qué número es?', ['fünfzehn', 'fünf', 'fünfzig'], 'fünfzehn', '15', 'texto'),
            omp('¿Qué número es?', ['zwanzig', 'zwölf', 'zehn'], 'zwanzig', '20', 'texto'),
        ]),

        deu('Die Wochentage', 'Ordena los días de la semana', '📅', 'ordenar_secuencia', [
            'title' => 'Ordena los días de la semana',
            'items' => ['Montag', 'Dienstag', 'Mittwoch', 'Donnerstag', 'Freitag', 'Samstag', 'Sonntag'],
        ]),

        deu('Die Jahreszeiten', 'Une cada estación del año con su palabra', '🔗', 'emparejar', [
            ['e' => '🌷', 'w' => 'der Frühling'],
            ['e' => '☀️', 'w' => 'der Sommer'],
            ['e' => '🍂', 'w' => 'der Herbst'],
            ['e' => '❄️', 'w' => 'der Winter'],
            ['e' => '🎂', 'w' => 'der Geburtstag'],
            ['e' => '📅', 'w' => 'der Kalender'],
        ]),

        deu('Die Monate', 'Los meses del año', '🗓️', 'opcion_multiple', [
            omp('¿Qué mes viene después de «Januar»?', ['Februar', 'März', 'Dezember'], 'Februar', '🗓️'),
            omp('¿Cuál es el último mes del año?', ['Dezember', 'Januar', 'November'], 'Dezember'),
            omp('¿En qué mes es Navidad?', ['Dezember', 'Juli', 'April'], 'Dezember', '🎄'),
            omp('¿En qué estación del año nieva en Alemania?', ['im Winter', 'im Sommer', 'im Frühling'], 'im Winter', '❄️'),
            omp('¿Cuántos meses tiene un año?', ['zwölf', 'zehn', 'sieben'], 'zwölf'),
        ]),
    ],
],

[
    'slug'  => 'deutsch-meine-welt-ist-bunt',
    'title' => 'Meine Welt ist bunt',
    'description' => 'De qué color es cada cosa, y el juego «Veo, veo» en alemán.',
    'objective' => 'Describir objetos por su color en alemán y reconocer colores en frases completas.',
    'icon' => '🌈', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 11, 'tags' => ['aleman', 'vocabulario', 'observacion'],
    'estaciones' => [

        deu('Welche Farbe hat…?', '¿De qué color es?', '🎨', 'opcion_multiple', [
            omp('¿De qué color es el banano?', ['gelb', 'blau', 'rot'], 'gelb', '🍌'),
            omp('¿De qué color es la manzana?', ['rot', 'grün', 'lila'], 'rot', '🍎'),
            omp('¿De qué color es el árbol?', ['grün', 'gelb', 'weiß'], 'grün', '🌳'),
            omp('¿De qué color es el elefante?', ['grau', 'gelb', 'grün'], 'grau', '🐘'),
            omp('¿De qué color es la nube?', ['weiß', 'schwarz', 'orange'], 'weiß', '☁️'),
        ]),

        deu('Ich sehe was, was du nicht siehst', 'Veo, veo: escucha el color y toca la cosa', '👀', 'opcion_multiple', [
            omp('Ich sehe was, was du nicht siehst, und das ist rot.', ['🍓', '🍌', '🥒'], '🍓', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist gelb.', ['🌻', '🍆', '🐸'], '🌻', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist grün.', ['🐸', '🍊', '🧀'], '🐸', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist blau.', ['🐳', '🍒', '🐥'], '🐳', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist orange.', ['🍊', '🍇', '🥦'], '🍊', '👀'),
        ]),

        deu('Alles, was grün ist', 'Toca todo lo que es verde', '🐸', 'seleccion_imagenes',
            conTitulo('Toca todo lo que es verde', 'Alles, was grün ist', [
                ['e' => '🐸', 'n' => 'der Frosch',     'ok' => true],
                ['e' => '🥒', 'n' => 'die Gurke',      'ok' => true],
                ['e' => '🌳', 'n' => 'der Baum',       'ok' => true],
                ['e' => '🥦', 'n' => 'der Brokkoli',   'ok' => true],
                ['e' => '🍓', 'n' => 'die Erdbeere',   'ok' => false],
                ['e' => '🍌', 'n' => 'die Banane',     'ok' => false],
                ['e' => '🐷', 'n' => 'das Schwein',    'ok' => false],
                ['e' => '☀️', 'n' => 'die Sonne',      'ok' => false],
            ])),

        deu('Schreib die Farbe', 'Escribe el color en alemán', '⌨️', 'teclado',
            ['rot', 'blau', 'gelb', 'grün', 'weiß']),
    ],
],

[
    'slug'  => 'deutsch-wetter-und-kleidung',
    'title' => 'Wie ist das Wetter heute?',
    'description' => 'El clima y la ropa en alemán: qué tiempo hace y qué te pones.',
    'objective' => 'Describir el clima con frases sencillas en alemán y elegir la ropa adecuada.',
    'icon' => '🌦️', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 11, 'tags' => ['aleman', 'vocabulario', 'pronunciacion'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha y repite el clima', '🔊', 'pronunciacion', [
            ['e' => '☀️', 'w' => 'Es ist sonnig.'],
            ['e' => '🌧️', 'w' => 'Es regnet.'],
            ['e' => '❄️', 'w' => 'Es schneit.'],
            ['e' => '💨', 'w' => 'Es ist windig.'],
            ['e' => '☁️', 'w' => 'Es ist bewölkt.'],
        ]),

        deu('Wie ist das Wetter?', '¿Qué tiempo hace?', '🌦️', 'opcion_multiple', [
            omp('¿Qué tiempo hace?', ['Es ist sonnig.', 'Es regnet.', 'Es schneit.'], 'Es ist sonnig.', '☀️'),
            omp('¿Qué tiempo hace?', ['Es regnet.', 'Es ist windig.', 'Es ist sonnig.'], 'Es regnet.', '🌧️'),
            omp('¿Qué tiempo hace?', ['Es schneit.', 'Es regnet.', 'Es ist bewölkt.'], 'Es schneit.', '❄️'),
            omp('¿Qué tiempo hace?', ['Es ist windig.', 'Es schneit.', 'Es ist sonnig.'], 'Es ist windig.', '💨'),
            omp('¿Qué tiempo hace?', ['Es ist bewölkt.', 'Es ist sonnig.', 'Es schneit.'], 'Es ist bewölkt.', '☁️'),
        ]),

        deu('Die Kleidung', 'Une cada prenda con su palabra', '🔗', 'emparejar', [
            ['e' => '👕', 'w' => 'das T-Shirt'],
            ['e' => '👖', 'w' => 'die Hose'],
            ['e' => '👗', 'w' => 'das Kleid'],
            ['e' => '🧥', 'w' => 'die Jacke'],
            ['e' => '🧣', 'w' => 'der Schal'],
            ['e' => '👟', 'w' => 'der Schuh'],
        ]),

        deu('Was ziehst du an?', '¿Qué te pones con este clima?', '🧥', 'opcion_multiple', [
            omp('Hace mucho frío y nieva. ¿Qué te pones?', ['die Jacke', 'das T-Shirt', 'die Sonnenbrille'], 'die Jacke', '❄️'),
            omp('Hace sol y calor. ¿Qué te pones?', ['das T-Shirt', 'der Schal', 'die Jacke'], 'das T-Shirt', '☀️'),
            omp('Está lloviendo. ¿Qué llevas?', ['der Regenschirm', 'die Sonnenbrille', 'der Schal'], 'der Regenschirm', '🌧️'),
            omp('Hace viento y frío. ¿Qué te pones en el cuello?', ['der Schal', 'der Schuh', 'das Kleid'], 'der Schal', '💨'),
        ]),
    ],
],

[
    'slug'  => 'deutsch-feste',
    'title' => 'Feste in Deutschland und Kolumbien',
    'description' => 'Oktoberfest, San Nicolás, Navidad, Carnaval y Pascua: cómo se celebran en Alemania y en Colombia.',
    'objective' => 'Reconocer el vocabulario de las fiestas alemanas y comparar sus costumbres con las colombianas.',
    'icon' => '🎄', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'cultura'],
    'estaciones' => [

        deu('Feste verbinden', 'Une cada dibujo con su palabra', '🔗', 'emparejar', [
            ['e' => '🎄', 'w' => 'der Weihnachtsbaum'],
            ['e' => '🎅', 'w' => 'der Nikolaus'],
            ['e' => '🥨', 'w' => 'die Brezel'],
            ['e' => '🎭', 'w' => 'der Karneval'],
            ['e' => '🐰', 'w' => 'der Osterhase'],
            ['e' => '🥚', 'w' => 'das Osterei'],
        ]),

        deu('Hör zu und sprich nach', 'Los buenos deseos de cada fiesta', '🔊', 'pronunciacion', [
            ['e' => '🎄', 'w' => 'Frohe Weihnachten!'],
            ['e' => '🐰', 'w' => 'Frohe Ostern!'],
            ['e' => '🎂', 'w' => 'Herzlichen Glückwunsch!'],
            ['e' => '🎆', 'w' => 'Frohes neues Jahr!'],
        ]),

        deu('Feste in zwei Ländern', 'Lo que se hace en cada fiesta', '🌍', 'opcion_multiple', [
            omp('¿Qué día llega San Nicolás (der Nikolaus) en Alemania?', ['am 6. Dezember', 'am 24. Dezember', 'am 31. Oktober'], 'am 6. Dezember', '🎅'),
            omp('¿Cómo se dice «Feliz Navidad» en alemán?', ['Frohe Weihnachten!', 'Frohe Ostern!', 'Guten Morgen!'], 'Frohe Weihnachten!', '🎄'),
            omp('¿Qué esconde el conejo de Pascua (der Osterhase)?', ['Ostereier', 'Brezeln', 'Schuhe'], 'Ostereier', '🐰'),
            omp('¿Dónde es el Carnaval más famoso de Colombia?', ['in Barranquilla', 'in Berlin', 'in München'], 'in Barranquilla', '🎭'),
            omp('¿Qué pan con forma de lazo se come en el Oktoberfest?', ['die Brezel', 'die Arepa', 'der Pandebono'], 'die Brezel', '🥨'),
        ]),

        deu('Klasse 1 – Quiz', 'Repaso de primero en alemán', '🏆', 'desafio_final', [
            reto('¿Cómo se dice «el libro» en alemán?', ['das Buch', 'der Buch', 'die Buch'], 'das Buch', '📕'),
            reto('¿Qué número es «fünfzehn»?', ['15', '50', '5'], '15'),
            reto('¿Qué día va después de «Montag»?', ['Dienstag', 'Sonntag', 'Freitag'], 'Dienstag', '📅'),
            reto('«Es regnet» quiere decir…', ['Está lloviendo', 'Hace sol', 'Está nevando'], 'Está lloviendo', '🌧️'),
            reto('¿De qué color es «grün»?', ['Verde', 'Gris', 'Rojo'], 'Verde', '🟢'),
            reto('«Frohe Ostern!» se dice en…', ['Pascua', 'Navidad', 'Carnaval'], 'Pascua', '🐰'),
        ]),
    ],
],


// =====================================================================
//  DEUTSCH · ERSTE WÖRTER — KLASSE 2
// =====================================================================

[
    'slug'  => 'deutsch-in-der-schule',
    'title' => 'In der Schule: Lies mal! Komm her!',
    'description' => 'Las órdenes que se escuchan en clase en alemán, para entenderlas y seguirlas.',
    'objective' => 'Comprender y seguir instrucciones sencillas de clase en alemán (Aufforderungen).',
    'icon' => '🏫', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 11, 'tags' => ['aleman', 'comprension', 'pronunciacion'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Las órdenes de la clase', '🔊', 'pronunciacion', [
            ['e' => '📖', 'w' => 'Lies mal!'],
            ['e' => '👀', 'w' => 'Schau mal!'],
            ['e' => '👉', 'w' => 'Zeig mal!'],
            ['e' => '🙋', 'w' => 'Komm her!'],
            ['e' => '✍️', 'w' => 'Schreib!'],
            ['e' => '🤫', 'w' => 'Sei leise!'],
        ]),

        deu('Was machst du?', 'Escucha la orden y elige qué haces', '👂', 'opcion_multiple', [
            omp('Lies das Buch!', ['Leo el libro', 'Canto una canción', 'Me siento en la silla'], 'Leo el libro', '📖'),
            omp('Schreib deinen Namen!', ['Escribo mi nombre', 'Cierro el libro', 'Salto en un pie'], 'Escribo mi nombre', '✍️'),
            omp('Setz dich, bitte!', ['Me siento en la silla', 'Me levanto de la silla', 'Corro por el salón'], 'Me siento en la silla', '🪑'),
            omp('Mach das Buch zu!', ['Cierro el libro', 'Abro el libro', 'Leo el libro'], 'Cierro el libro', '📕'),
            omp('Hör gut zu!', ['Escucho con atención', 'Hablo muy fuerte', 'Corro por el salón'], 'Escucho con atención', '👂'),
        ]),

        deu('Aufforderungen verbinden', 'Une cada dibujo con su orden', '🔗', 'emparejar', [
            ['e' => '📖', 'w' => 'Lies!'],
            ['e' => '✍️', 'w' => 'Schreib!'],
            ['e' => '👂', 'w' => 'Hör zu!'],
            ['e' => '🎨', 'w' => 'Mal!'],
            ['e' => '🪑', 'w' => 'Setz dich!'],
            ['e' => '🧍', 'w' => 'Steh auf!'],
        ]),

        deu('Schreib das Wort', 'Palabras del colegio en alemán', '⌨️', 'teclado',
            ['Schule', 'Klasse', 'Tafel', 'Pause', 'Lehrer']),
    ],
],

[
    'slug'  => 'deutsch-lebensmittel',
    'title' => 'Was isst du gern?',
    'description' => 'Comidas y bebidas en alemán, cómo saben y qué te gusta.',
    'objective' => 'Nombrar alimentos con su artículo, decir cómo saben y expresar gustos con «Ich mag…».',
    'icon' => '🍎', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 11, 'tags' => ['aleman', 'vocabulario', 'alimentacion'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha y repite cada alimento', '🔊', 'pronunciacion', [
            ['e' => '🍎', 'w' => 'der Apfel'],
            ['e' => '🍌', 'w' => 'die Banane'],
            ['e' => '🥕', 'w' => 'die Karotte'],
            ['e' => '🧀', 'w' => 'der Käse'],
            ['e' => '🥛', 'w' => 'die Milch'],
            ['e' => '🍞', 'w' => 'das Brot'],
        ]),

        deu('Essen verbinden', 'Une cada alimento con su palabra', '🔗', 'emparejar', [
            ['e' => '🍓', 'w' => 'die Erdbeere'],
            ['e' => '🍐', 'w' => 'die Birne'],
            ['e' => '🥚', 'w' => 'das Ei'],
            ['e' => '🍅', 'w' => 'die Tomate'],
            ['e' => '🧃', 'w' => 'der Saft'],
            ['e' => '💧', 'w' => 'das Wasser'],
        ]),

        deu('Wie schmeckt das?', 'Cómo sabe y qué te gusta', '😋', 'opcion_multiple', [
            omp('¿Cómo sabe el helado?', ['süß', 'salzig', 'sauer'], 'süß', '🍦'),
            omp('¿Cómo sabe el limón?', ['sauer', 'süß', 'salzig'], 'sauer', '🍋'),
            omp('¿Cómo saben las papas fritas?', ['salzig', 'süß', 'sauer'], 'salzig', '🍟'),
            omp('¿Cómo se dice «Me gustan las manzanas»?', ['Ich mag Äpfel.', 'Ich bin Äpfel.', 'Ich habe Äpfel.'], 'Ich mag Äpfel.', '🍎'),
            omp('¿Cómo se dice «Tengo hambre»?', ['Ich habe Hunger.', 'Ich bin Hunger.', 'Ich mag Hunger.'], 'Ich habe Hunger.', '😋'),
        ]),

        deu('Nur das Obst', 'Toca solo las frutas', '🍓', 'seleccion_imagenes',
            conTitulo('Toca solo las frutas', 'Nur das Obst', [
                ['e' => '🍎', 'n' => 'der Apfel',    'ok' => true],
                ['e' => '🍌', 'n' => 'die Banane',   'ok' => true],
                ['e' => '🍓', 'n' => 'die Erdbeere', 'ok' => true],
                ['e' => '🍐', 'n' => 'die Birne',    'ok' => true],
                ['e' => '🥕', 'n' => 'die Karotte',  'ok' => false],
                ['e' => '🧀', 'n' => 'der Käse',     'ok' => false],
                ['e' => '🍞', 'n' => 'das Brot',     'ok' => false],
                ['e' => '🥛', 'n' => 'die Milch',    'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'deutsch-meine-familie',
    'title' => 'Meine Familie ist toll!',
    'description' => 'La familia en alemán, cómo presentarla y cómo decir la edad.',
    'objective' => 'Nombrar a los miembros de la familia, presentarlos con «mein/meine» y preguntar y decir la edad.',
    'icon' => '👨‍👩‍👧', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'familia'],
    'estaciones' => [

        deu('Die Familie', 'Une cada persona con su palabra', '🔗', 'emparejar', [
            ['e' => '👩', 'w' => 'die Mutter'],
            ['e' => '👨', 'w' => 'der Vater'],
            ['e' => '👧', 'w' => 'die Schwester'],
            ['e' => '👦', 'w' => 'der Bruder'],
            ['e' => '👵', 'w' => 'die Oma'],
            ['e' => '👴', 'w' => 'der Opa'],
        ]),

        deu('Hör zu und sprich nach', 'Presentar a la familia', '🔊', 'pronunciacion', [
            ['e' => '👩', 'w' => 'Das ist meine Mutter.'],
            ['e' => '👨', 'w' => 'Das ist mein Vater.'],
            ['e' => '👧', 'w' => 'Ich habe eine Schwester.'],
            ['e' => '❓', 'w' => 'Wie alt bist du?'],
            ['e' => '7️⃣', 'w' => 'Ich bin sieben Jahre alt.'],
        ]),

        deu('Wer ist das?', 'La familia y la edad', '❓', 'opcion_multiple', [
            omp('¿Cómo se dice «¿Cuántos años tienes?»?', ['Wie alt bist du?', 'Wie heißt du?', 'Wo wohnst du?'], 'Wie alt bist du?'),
            omp('La mamá de mi mamá es mi…', ['Oma', 'Schwester', 'Tante'], 'Oma', '👵'),
            omp('El papá de mi papá es mi…', ['Opa', 'Bruder', 'Onkel'], 'Opa', '👴'),
            omp('¿Cómo se dice «Tengo ocho años»?', ['Ich bin acht Jahre alt.', 'Ich habe acht Jahre.', 'Du bist acht Jahre alt.'], 'Ich bin acht Jahre alt.', '8️⃣'),
            omp('¿Cuál va? «___ Vater ist groß.»', ['Mein', 'Meine'], 'Mein', '👨'),
            omp('¿Cuál va? «___ Mutter ist lustig.»', ['Meine', 'Mein'], 'Meine', '👩'),
        ]),

        deu('Schreib das Wort', 'Escribe la palabra en alemán', '⌨️', 'teclado',
            ['Mutter', 'Vater', 'Oma', 'Opa', 'Bruder']),
    ],
],

[
    'slug'  => 'deutsch-gefuehle',
    'title' => 'Wie fühlst du dich?',
    'description' => 'Las emociones en alemán: cómo te sientes y qué quieres hacer.',
    'objective' => 'Nombrar emociones en alemán, preguntar cómo está alguien y expresar lo que se quiere hacer.',
    'icon' => '😊', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 11, 'tags' => ['aleman', 'vocabulario', 'emociones'],
    'estaciones' => [

        deu('Hör zu und sprich nach', 'Escucha y repite cada emoción', '🔊', 'pronunciacion', [
            ['e' => '😀', 'w' => 'glücklich'],
            ['e' => '😢', 'w' => 'traurig'],
            ['e' => '😠', 'w' => 'wütend'],
            ['e' => '😬', 'w' => 'nervös'],
            ['e' => '😴', 'w' => 'müde'],
            ['e' => '🤒', 'w' => 'krank'],
        ]),

        deu('Wie fühlt er sich?', '¿Cómo se siente?', '🙂', 'opcion_multiple', [
            omp('¿Cómo se siente?', ['traurig', 'glücklich', 'müde'], 'traurig', '😢'),
            omp('¿Cómo se siente?', ['glücklich', 'wütend', 'krank'], 'glücklich', '😀'),
            omp('¿Cómo se siente?', ['müde', 'nervös', 'traurig'], 'müde', '😴'),
            omp('¿Cómo se siente?', ['wütend', 'glücklich', 'müde'], 'wütend', '😠'),
            omp('¿Cómo se siente?', ['krank', 'glücklich', 'nervös'], 'krank', '🤒'),
        ]),

        deu('Wie geht es dir?', 'Preguntar y contar cómo estás', '💬', 'opcion_multiple', [
            omp('¿Cómo se dice «¿Cómo estás?»?', ['Wie geht es dir?', 'Wie alt bist du?', 'Wie heißt du?'], 'Wie geht es dir?'),
            omp('¿Cómo se dice «Estoy feliz»?', ['Ich bin glücklich.', 'Ich habe glücklich.', 'Du bist glücklich.'], 'Ich bin glücklich.', '😀'),
            omp('¿Cómo se dice «¿Quieres jugar?»?', ['Möchtest du spielen?', 'Bist du spielen?', 'Hast du spielen?'], 'Möchtest du spielen?', '⚽'),
            omp('¿Cómo se dice «No tengo ganas»?', ['Ich habe keine Lust.', 'Ich bin keine Lust.', 'Ich mag keine Lust.'], 'Ich habe keine Lust.', '🙅'),
        ]),

        deu('Gefühle verbinden', 'Une cada cara con su palabra', '🔗', 'emparejar', [
            ['e' => '😀', 'w' => 'glücklich'],
            ['e' => '😢', 'w' => 'traurig'],
            ['e' => '😠', 'w' => 'wütend'],
            ['e' => '😬', 'w' => 'nervös'],
            ['e' => '😴', 'w' => 'müde'],
            ['e' => '🤒', 'w' => 'krank'],
        ]),
    ],
],

[
    'slug'  => 'deutsch-gross-oder-klein',
    'title' => 'Groß oder klein?',
    'description' => 'La primera regla de la escritura en alemán: los sustantivos van con mayúscula. Y cada uno tiene su artículo.',
    'objective' => 'Reconocer que los sustantivos se escriben con mayúscula, elegir el artículo correcto y contar sílabas.',
    'icon' => '🔠', 'nivel' => 'primaria-inicial', 'bloque' => 'deutsch-erste-woerter',
    'duracion' => 12, 'tags' => ['aleman', 'escritura', 'lectura'],
    'estaciones' => [

        deu('Groß oder klein?', '¿Cuál está bien escrita?', '🔠', 'opcion_multiple', [
            omp('¿Cuál está bien escrita? (es un animal)', ['Hund', 'hund'], 'Hund', '🐶'),
            omp('¿Cuál está bien escrita? (es un animal)', ['Katze', 'katze'], 'Katze', '🐱'),
            omp('¿Cuál está bien escrita? (es una cosa)', ['Haus', 'haus'], 'Haus', '🏠'),
            omp('¿Cuál está bien escrita? (dice cómo es algo)', ['rot', 'Rot'], 'rot', '🔴'),
            omp('¿Cuál está bien escrita? (es algo que hacemos)', ['spielen', 'Spielen'], 'spielen', '⚽'),
        ]),

        deu('Der, die oder das?', 'Elige el artículo de cada palabra', '🧩', 'opcion_multiple', [
            omp('¿Qué artículo lleva «Hund»?', ['der', 'die', 'das'], 'der', '🐶'),
            omp('¿Qué artículo lleva «Katze»?', ['die', 'der', 'das'], 'die', '🐱'),
            omp('¿Qué artículo lleva «Haus»?', ['das', 'der', 'die'], 'das', '🏠'),
            omp('¿Qué artículo lleva «Sonne»?', ['die', 'das', 'der'], 'die', '☀️'),
            omp('¿Qué artículo lleva «Buch»?', ['das', 'die', 'der'], 'das', '📕'),
            omp('¿Qué artículo lleva «Apfel»?', ['der', 'das', 'die'], 'der', '🍎'),
        ]),

        deu('Silben klatschen', 'Aplaude las sílabas de cada palabra', '👏', 'opcion_multiple', [
            omp('Aplaude: Ba - na - ne. ¿Cuántas sílabas tiene?', ['3', '2', '4'], '3', '🍌'),
            omp('Aplaude: Hund. ¿Cuántas sílabas tiene?', ['1', '2', '3'], '1', '🐶'),
            omp('Aplaude: Kat - ze. ¿Cuántas sílabas tiene?', ['2', '1', '3'], '2', '🐱'),
            omp('Aplaude: Te - le - fon. ¿Cuántas sílabas tiene?', ['3', '2', '4'], '3', '📞'),
        ]),

        deu('Klasse 2 – Quiz', 'Repaso de segundo en alemán', '🏆', 'desafio_final', [
            reto('¿Cuál está bien escrita?', ['der Apfel', 'der apfel'], 'der Apfel', '🍎'),
            reto('«Lies mal!» quiere decir…', ['¡Lee!', '¡Escribe!', '¡Ven aquí!'], '¡Lee!', '📖'),
            reto('¿Cómo sabe el azúcar?', ['süß', 'sauer', 'salzig'], 'süß', '🍬'),
            reto('La mamá de mi mamá es mi…', ['Oma', 'Tante', 'Schwester'], 'Oma', '👵'),
            reto('«Ich bin müde» quiere decir…', ['Estoy cansado', 'Estoy feliz', 'Tengo hambre'], 'Estoy cansado', '😴'),
            reto('¿Qué artículo lleva «Buch»?', ['das', 'der', 'die'], 'das', '📕'),
        ]),
    ],
],

],
];
