<?php
/**
 * idiomas-aleman.php — Deutsch: Kindergarten y Klasse 1–2
 *
 * Primera fase del alemán, sobre las mallas del Colegio Alemán (Deutsche
 * Schule, `MallasPrimaria/ALEMAN`, Klasse 1 a 6). Klasse 1 y 2 son
 * alfabetización en alemán como segunda lengua (libro Zebra): sonidos
 * iniciales, primeras palabras, temas del día a día y las fiestas
 * alemanas comparadas con las colombianas. Preescolar no tiene malla en
 * la carpeta: toma los temas de Klasse 1 en su versión más sencilla.
 *
 * INMERSIÓN (revisión de los docentes de alemán, octubre de 2026):
 *   - Las preguntas y las indicaciones van EN ALEMÁN, no en español. El
 *     motor traduce además sus textos fijos («Schreib das Wort»,
 *     «Richtig!») cuando la estación declara `de-DE`.
 *   - Nada de ejercicios de traducción («¿Cómo se dice…?»): el significado
 *     lo da el dibujo o la situación.
 *   - El teclado es un dictado: la palabra se oye, no se ve.
 *   - Las opciones que lo necesitan llevan su dibujo (la ropa, las
 *     órdenes de clase).
 *
 * Y las reglas del alemán:
 *   - Los sustantivos van SIEMPRE con su artículo (der, die, das) y con
 *     mayúscula.
 *   - Sin ß en sopas de letras ni crucigramas (en mayúscula pasa a «SS»).
 *     En los juegos de escribir el motor pone botones ä ö ü ß.
 *
 * Fase 2 (Klasse 3–6, A1 y A2) se escribe después de esta revisión.
 */

declare(strict_types=1);

$idiomas = require __DIR__ . '/idiomas-ampliacion.php';

if (!function_exists('conDibujos')) {
    /** Una pregunta con un dibujo pequeño en cada opción, en el mismo orden. */
    function conDibujos(array $pregunta, array $dibujos): array
    {
        $pregunta['dibujos'] = array_values($dibujos);
        return $pregunta;
    }

    /**
     * Una pregunta que solo se oye: el motor dice `oir` y no muestra la
     * frase (ver `opcion_multiple` en motor.js). El enunciado se guarda
     * igual para los repasos generados.
     */
    function soloOir(string $frase, array $opciones): array
    {
        return omp($frase, $opciones, $frase, '🎧') + ['oir' => $frase];
    }
}

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

        deu('Welche Farbe ist das?', 'Elige el color', '🎨', 'opcion_multiple', [
            omp('Welche Farbe ist das?', ['rot', 'blau', 'grün'], 'rot', '🔴'),
            omp('Welche Farbe ist das?', ['blau', 'gelb', 'schwarz'], 'blau', '🔵'),
            omp('Welche Farbe ist das?', ['gelb', 'weiß', 'rot'], 'gelb', '🟡'),
            omp('Welche Farbe ist das?', ['grün', 'blau', 'orange'], 'grün', '🟢'),
            omp('Welche Farbe ist das?', ['orange', 'lila', 'braun'], 'orange', '🟠'),
            omp('Welche Farbe ist das?', ['lila', 'braun', 'grün'], 'lila', '🟣'),
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
            conTitulo('Tippe auf alles, was rot ist', 'Was ist rot?', [
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

        deu('Welches Tier ist das?', 'Elige el nombre del animal', '🐾', 'opcion_multiple', [
            omp('Welches Tier ist das?', ['der Hund', 'die Katze', 'der Vogel'], 'der Hund', '🐶'),
            omp('Welches Tier ist das?', ['die Katze', 'die Kuh', 'der Fisch'], 'die Katze', '🐱'),
            omp('Welches Tier ist das?', ['der Fisch', 'das Pferd', 'der Vogel'], 'der Fisch', '🐟'),
            omp('Welches Tier ist das?', ['der Löwe', 'der Bär', 'die Ente'], 'der Löwe', '🦁'),
            omp('Welches Tier ist das?', ['der Elefant', 'der Affe', 'der Hase'], 'der Elefant', '🐘'),
            omp('Welches Tier ist das?', ['der Affe', 'die Kuh', 'der Frosch'], 'der Affe', '🐵'),
        ]),

        deu('Tiere verbinden', 'Une cada animal con su palabra', '🔗', 'emparejar', [
            ['e' => '🐰', 'w' => 'der Hase'],
            ['e' => '🐸', 'w' => 'der Frosch'],
            ['e' => '🦆', 'w' => 'die Ente'],
            ['e' => '🐷', 'w' => 'das Schwein'],
            ['e' => '🐻', 'w' => 'der Bär'],
            ['e' => '🐝', 'w' => 'die Biene'],
        ]),

        deu('Auf dem Bauernhof', 'Toca solo los animales de la granja', '🚜', 'seleccion_imagenes',
            conTitulo('Welche Tiere leben auf dem Bauernhof?', 'Tippe auf alle Tiere vom Bauernhof', [
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
            omp('Was ist das?', ['das Ohr', 'die Nase', 'das Auge'], 'das Ohr', '👂'),
            omp('Was ist das?', ['die Nase', 'der Mund', 'die Hand'], 'die Nase', '👃'),
            omp('Was ist das?', ['der Mund', 'das Ohr', 'der Fuß'], 'der Mund', '👄'),
            omp('Was ist das?', ['die Hand', 'der Fuß', 'der Arm'], 'die Hand', '✋'),
            omp('Was ist das?', ['das Bein', 'der Arm', 'der Kopf'], 'das Bein', '🦵'),
            omp('Was ist das?', ['der Arm', 'das Bein', 'die Hand'], 'der Arm', '💪'),
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
    'description' => 'Saludar, despedirse, dar las gracias y presentarse en alemán.',
    'objective' => 'Usar saludos, despedidas y palabras de cortesía en alemán según el momento, y responder preguntas personales sencillas.',
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
            omp('Am Morgen in der Schule sagst du …', ['Guten Morgen!', 'Gute Nacht!', 'Tschüss!'], 'Guten Morgen!', '🌅'),
            omp('Du gehst ins Bett. Du sagst …', ['Gute Nacht!', 'Guten Morgen!', 'Hallo!'], 'Gute Nacht!', '🌙'),
            omp('Du bekommst ein Geschenk. Du sagst …', ['Danke!', 'Tschüss!', 'Hallo!'], 'Danke!', '🎁'),
            omp('Du gehst nach Hause. Du sagst …', ['Tschüss!', 'Danke!', 'Guten Morgen!'], 'Tschüss!', '🚶'),
            omp('Du siehst deine Freundin. Du sagst …', ['Hallo!', 'Gute Nacht!', 'Bitte!'], 'Hallo!', '🛝'),
        ]),

        deu('Tag und Nacht', 'Une cada momento del día con su saludo', '🔗', 'emparejar', [
            ['e' => '🌅', 'w' => 'Guten Morgen'],
            ['e' => '☀️', 'w' => 'Guten Tag'],
            ['e' => '🌆', 'w' => 'Guten Abend'],
            ['e' => '🌙', 'w' => 'Gute Nacht'],
            ['e' => '👋', 'w' => 'Hallo'],
            ['e' => '🙏', 'w' => 'Danke'],
        ]),

        // Las tres preguntas a la vista, y cada una con su respuesta: así
        // lo pidieron los docentes, en vez de traducirlas una a una.
        deu('Wie heißt du?', 'Une cada pregunta con su respuesta', '🙋', 'emparejar', [
            ['q' => 'Wie heißt du?',    'e' => '🙋', 'w' => 'Ich heiße Ana.'],
            ['q' => 'Wie alt bist du?', 'e' => '🎂', 'w' => 'Ich bin fünf Jahre alt.'],
            ['q' => 'Wie geht es dir?', 'e' => '🙂', 'w' => 'Mir geht es gut.'],
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

        deu('Hör zu und sprich nach', 'Los útiles del colegio', '🔊', 'pronunciacion', [
            ['e' => '✏️', 'w' => 'der Bleistift'],
            ['e' => '🖍️', 'w' => 'der Buntstift'],
            ['e' => '📕', 'w' => 'das Buch'],
            ['e' => '📓', 'w' => 'das Heft'],
            ['e' => '✂️', 'w' => 'die Schere'],
            ['e' => '🎒', 'w' => 'die Schultasche'],
        ]),

        deu('Was ist das?', 'Elige el nombre', '❓', 'opcion_multiple', [
            omp('Was ist das?', ['der Bleistift', 'das Buch', 'die Schere'], 'der Bleistift', '✏️'),
            omp('Was ist das?', ['das Lineal', 'das Heft', 'der Stuhl'], 'das Lineal', '📏'),
            omp('Was ist das?', ['die Schultasche', 'die Schere', 'der Tisch'], 'die Schultasche', '🎒'),
            omp('Was ist das?', ['der Stuhl', 'der Tisch', 'das Buch'], 'der Stuhl', '🪑'),
            omp('Was ist das?', ['das Heft', 'das Lineal', 'die Tafel'], 'das Heft', '📓'),
            omp('Was ist das?', ['die Schere', 'der Buntstift', 'die Schultasche'], 'die Schere', '✂️'),
        ]),

        deu('Der Anfangslaut', 'El sonido con que empieza', '🔤', 'opcion_multiple', [
            omp('Womit fängt «Buch» an?', ['B', 'D', 'P'], 'B', '📕'),
            omp('Womit fängt «Heft» an?', ['H', 'F', 'E'], 'H', '📓'),
            omp('Womit fängt «Schere» an?', ['Sch', 'S', 'Ch'], 'Sch', '✂️'),
            omp('Womit fängt «Lineal» an?', ['L', 'N', 'I'], 'L', '📏'),
            omp('Womit fängt «Apfel» an?', ['A', 'E', 'O'], 'A', '🍎'),
        ]),

        deu('Diktat', 'Escucha la palabra y escríbela', '⌨️', 'teclado',
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
            omp('Welche Zahl ist das?', ['sieben', 'acht', 'neun'], 'sieben', '7️⃣'),
            omp('Welche Zahl ist das?', ['elf', 'eins', 'acht'], 'elf', '11', 'texto'),
            omp('Welche Zahl ist das?', ['zwölf', 'zwei', 'zwanzig'], 'zwölf', '12', 'texto'),
            omp('Welche Zahl ist das?', ['fünfzehn', 'fünf', 'fünfzig'], 'fünfzehn', '15', 'texto'),
            omp('Welche Zahl ist das?', ['zwanzig', 'zwölf', 'zehn'], 'zwanzig', '20', 'texto'),
        ]),

        deu('Die Wochentage', 'Ordena los días de la semana', '📅', 'ordenar_secuencia', [
            'title' => 'Ordne die Wochentage',
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
            omp('Welcher Monat kommt nach Januar?', ['Februar', 'März', 'Dezember'], 'Februar', '🗓️'),
            omp('Welcher Monat ist der letzte im Jahr?', ['Dezember', 'Januar', 'November'], 'Dezember', '📅'),
            omp('In welchem Monat ist Weihnachten?', ['im Dezember', 'im Juli', 'im April'], 'im Dezember', '🎄'),
            omp('Wann schneit es in Deutschland?', ['im Winter', 'im Sommer', 'im Frühling'], 'im Winter', '❄️'),
            omp('Wie viele Monate hat ein Jahr?', ['zwölf', 'zehn', 'sieben'], 'zwölf', '📅'),
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
            omp('Welche Farbe hat die Banane?', ['gelb', 'blau', 'rot'], 'gelb', '🍌'),
            omp('Welche Farbe hat der Apfel?', ['rot', 'grün', 'lila'], 'rot', '🍎'),
            omp('Welche Farbe hat der Baum?', ['grün', 'gelb', 'weiß'], 'grün', '🌳'),
            omp('Welche Farbe hat der Elefant?', ['grau', 'gelb', 'grün'], 'grau', '🐘'),
            omp('Welche Farbe hat die Wolke?', ['weiß', 'schwarz', 'orange'], 'weiß', '☁️'),
        ]),

        deu('Ich sehe was, was du nicht siehst', 'Veo, veo: escucha el color y toca la cosa', '👀', 'opcion_multiple', [
            omp('Ich sehe was, was du nicht siehst, und das ist rot.', ['🍓', '🍌', '🥒'], '🍓', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist gelb.', ['🌻', '🍆', '🐸'], '🌻', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist grün.', ['🐸', '🍊', '🧀'], '🐸', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist blau.', ['🐳', '🍒', '🐥'], '🐳', '👀'),
            omp('Ich sehe was, was du nicht siehst, und das ist orange.', ['🍊', '🍇', '🥦'], '🍊', '👀'),
        ]),

        deu('Alles, was grün ist', 'Toca todo lo que es verde', '🐸', 'seleccion_imagenes',
            conTitulo('Tippe auf alles, was grün ist', 'Was ist grün?', [
                ['e' => '🐸', 'n' => 'der Frosch',     'ok' => true],
                ['e' => '🥒', 'n' => 'die Gurke',      'ok' => true],
                ['e' => '🌳', 'n' => 'der Baum',       'ok' => true],
                ['e' => '🥦', 'n' => 'der Brokkoli',   'ok' => true],
                ['e' => '🍓', 'n' => 'die Erdbeere',   'ok' => false],
                ['e' => '🍌', 'n' => 'die Banane',     'ok' => false],
                ['e' => '🐷', 'n' => 'das Schwein',    'ok' => false],
                ['e' => '☀️', 'n' => 'die Sonne',      'ok' => false],
            ])),

        deu('Diktat', 'Escucha el color y escríbelo', '⌨️', 'teclado',
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
            omp('Wie ist das Wetter?', ['Es ist sonnig.', 'Es regnet.', 'Es schneit.'], 'Es ist sonnig.', '☀️'),
            omp('Wie ist das Wetter?', ['Es regnet.', 'Es ist windig.', 'Es ist sonnig.'], 'Es regnet.', '🌧️'),
            omp('Wie ist das Wetter?', ['Es schneit.', 'Es regnet.', 'Es ist bewölkt.'], 'Es schneit.', '❄️'),
            omp('Wie ist das Wetter?', ['Es ist windig.', 'Es schneit.', 'Es ist sonnig.'], 'Es ist windig.', '💨'),
            omp('Wie ist das Wetter?', ['Es ist bewölkt.', 'Es ist sonnig.', 'Es schneit.'], 'Es ist bewölkt.', '☁️'),
        ]),

        deu('Die Kleidung', 'Une cada prenda con su palabra', '🔗', 'emparejar', [
            ['e' => '👕', 'w' => 'das T-Shirt'],
            ['e' => '👖', 'w' => 'die Hose'],
            ['e' => '👗', 'w' => 'das Kleid'],
            ['e' => '🧥', 'w' => 'die Jacke'],
            ['e' => '🧣', 'w' => 'der Schal'],
            ['e' => '👟', 'w' => 'der Schuh'],
        ]),

        // Cada opción con el dibujo de la prenda: así se entiende la
        // pregunta aunque todavía no se conozcan todas las palabras.
        deu('Was ziehst du an?', '¿Qué te pones con este clima?', '🧥', 'opcion_multiple', [
            conDibujos(omp('Es ist kalt und es schneit. Was ziehst du an?',
                ['die Jacke', 'das T-Shirt', 'die Sonnenbrille'], 'die Jacke', '❄️'), ['🧥', '👕', '🕶️']),
            conDibujos(omp('Es ist heiß und sonnig. Was ziehst du an?',
                ['das T-Shirt', 'der Schal', 'die Jacke'], 'das T-Shirt', '☀️'), ['👕', '🧣', '🧥']),
            conDibujos(omp('Es regnet. Was nimmst du mit?',
                ['den Regenschirm', 'die Sonnenbrille', 'den Ball'], 'den Regenschirm', '🌧️'), ['☂️', '🕶️', '⚽']),
            conDibujos(omp('Es ist windig und kalt. Was ziehst du an?',
                ['den Schal', 'das Kleid', 'die Badehose'], 'den Schal', '💨'), ['🧣', '👗', '🩳']),
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
            omp('Wann kommt der Nikolaus?', ['am 6. Dezember', 'am 24. Dezember', 'am 31. Oktober'], 'am 6. Dezember', '🎅'),
            omp('Was sagst du an Weihnachten?', ['Frohe Weihnachten!', 'Frohe Ostern!', 'Guten Morgen!'], 'Frohe Weihnachten!', '🎄'),
            omp('Was versteckt der Osterhase?', ['Ostereier', 'Brezeln', 'Schuhe'], 'Ostereier', '🐰'),
            omp('Wo ist der berühmteste Karneval in Kolumbien?', ['in Barranquilla', 'in Berlin', 'in München'], 'in Barranquilla', '🎭'),
            omp('Was isst man auf dem Oktoberfest?', ['die Brezel', 'die Arepa', 'den Pandebono'], 'die Brezel', '🥨'),
        ]),

        deu('Klasse 1 – Quiz', 'Repaso de primero', '🏆', 'desafio_final', [
            reto('Was ist das?', ['das Buch', 'der Buch', 'die Buch'], 'das Buch', '📕'),
            reto('Welche Zahl ist «fünfzehn»?', ['15', '50', '5'], '15', '🔢'),
            reto('Welcher Tag kommt nach Montag?', ['Dienstag', 'Sonntag', 'Freitag'], 'Dienstag', '📅'),
            reto('Wie ist das Wetter?', ['Es regnet.', 'Es ist sonnig.', 'Es schneit.'], 'Es regnet.', '🌧️'),
            reto('Welche Farbe ist das?', ['grün', 'grau', 'rot'], 'grün', '🟢'),
            reto('Was sagst du an Ostern?', ['Frohe Ostern!', 'Frohe Weihnachten!', 'Gute Nacht!'], 'Frohe Ostern!', '🐰'),
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

        // La orden solo se OYE —no se lee— y se escoge la oración que
        // se oyó. Así lo pidieron los docentes: escuchar, no traducir.
        deu('Was hörst du?', 'Escucha la orden y escoge la oración', '👂', 'opcion_multiple', [
            soloOir('Lies das Buch!',        ['Lies das Buch!', 'Sing ein Lied!', 'Setz dich, bitte!']),
            soloOir('Schreib deinen Namen!', ['Schau mal!', 'Schreib deinen Namen!', 'Komm her!']),
            soloOir('Setz dich, bitte!',     ['Steh auf!', 'Mal ein Bild!', 'Setz dich, bitte!']),
            soloOir('Mal ein Bild!',         ['Mal ein Bild!', 'Lies das Buch!', 'Hör gut zu!']),
            soloOir('Hör gut zu!',           ['Sei leise!', 'Hör gut zu!', 'Zeig mal!']),
        ]),

        deu('Aufforderungen verbinden', 'Une cada dibujo con su orden', '🔗', 'emparejar', [
            ['e' => '📖', 'w' => 'Lies!'],
            ['e' => '✍️', 'w' => 'Schreib!'],
            ['e' => '👂', 'w' => 'Hör zu!'],
            ['e' => '🎨', 'w' => 'Mal!'],
            ['e' => '🪑', 'w' => 'Setz dich!'],
            ['e' => '🧍', 'w' => 'Steh auf!'],
        ]),

        deu('Diktat', 'Escucha la palabra y escríbela', '⌨️', 'teclado',
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
            omp('Wie schmeckt das Eis?', ['süß', 'salzig', 'sauer'], 'süß', '🍦'),
            omp('Wie schmeckt die Zitrone?', ['sauer', 'süß', 'salzig'], 'sauer', '🍋'),
            omp('Wie schmecken die Pommes?', ['salzig', 'süß', 'sauer'], 'salzig', '🍟'),
            omp('Du magst Äpfel. Was sagst du?', ['Ich mag Äpfel.', 'Ich bin Äpfel.', 'Ich habe Äpfel.'], 'Ich mag Äpfel.', '🍎'),
            omp('Dein Bauch ist leer. Was sagst du?', ['Ich habe Hunger.', 'Ich bin müde.', 'Ich mag Hunger.'], 'Ich habe Hunger.', '😋'),
        ]),

        deu('Nur das Obst', 'Toca solo las frutas', '🍓', 'seleccion_imagenes',
            conTitulo('Tippe nur auf das Obst', 'Was ist Obst?', [
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
            omp('Die Mutter von meiner Mutter ist meine …', ['Oma', 'Schwester', 'Tante'], 'Oma', '👵'),
            omp('Der Vater von meinem Vater ist mein …', ['Opa', 'Bruder', 'Onkel'], 'Opa', '👴'),
            omp('Du bist acht. Was sagst du?', ['Ich bin acht Jahre alt.', 'Ich habe acht Jahre.', 'Du bist acht Jahre alt.'], 'Ich bin acht Jahre alt.', '8️⃣'),
            omp('Mein oder meine? «___ Vater ist groß.»', ['Mein', 'Meine'], 'Mein', '👨'),
            omp('Mein oder meine? «___ Mutter ist lustig.»', ['Meine', 'Mein'], 'Meine', '👩'),
        ]),

        deu('Fragen und Antworten', 'Une cada pregunta con su respuesta', '💬', 'emparejar', [
            ['q' => 'Wie alt bist du?',             'e' => '🎂', 'w' => 'Ich bin sieben Jahre alt.'],
            ['q' => 'Wer ist das?',                 'e' => '👩', 'w' => 'Das ist meine Mutter.'],
            ['q' => 'Hast du Geschwister?',         'e' => '👧', 'w' => 'Ja, ich habe eine Schwester.'],
            ['q' => 'Wie heißt dein Vater?',        'e' => '👨', 'w' => 'Mein Vater heißt Juan.'],
        ]),

        deu('Diktat', 'Escucha la palabra y escríbela', '⌨️', 'teclado',
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

        deu('Wie fühlt sich das Kind?', '¿Cómo se siente?', '🙂', 'opcion_multiple', [
            omp('Wie fühlt sich das Kind?', ['traurig', 'glücklich', 'müde'], 'traurig', '😢'),
            omp('Wie fühlt sich das Kind?', ['glücklich', 'wütend', 'krank'], 'glücklich', '😀'),
            omp('Wie fühlt sich das Kind?', ['müde', 'nervös', 'traurig'], 'müde', '😴'),
            omp('Wie fühlt sich das Kind?', ['wütend', 'glücklich', 'müde'], 'wütend', '😠'),
            omp('Wie fühlt sich das Kind?', ['krank', 'glücklich', 'nervös'], 'krank', '🤒'),
        ]),

        deu('Wie geht es dir?', 'Une cada pregunta con su respuesta', '💬', 'emparejar', [
            ['q' => 'Wie geht es dir?',        'e' => '🙂', 'w' => 'Mir geht es gut.'],
            ['q' => 'Bist du traurig?',        'e' => '😢', 'w' => 'Ja, ich bin traurig.'],
            ['q' => 'Möchtest du spielen?',    'e' => '⚽', 'w' => 'Ja, gern!'],
            ['q' => 'Möchtest du schlafen?',   'e' => '😴', 'w' => 'Nein, ich habe keine Lust.'],
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
            omp('Welches Wort ist richtig?', ['Hund', 'hund'], 'Hund', '🐶'),
            omp('Welches Wort ist richtig?', ['Katze', 'katze'], 'Katze', '🐱'),
            omp('Welches Wort ist richtig?', ['Haus', 'haus'], 'Haus', '🏠'),
            omp('Welches Wort ist richtig? Der Apfel ist …', ['rot', 'Rot'], 'rot', '🍎'),
            omp('Welches Wort ist richtig? Wir …', ['spielen', 'Spielen'], 'spielen', '⚽'),
        ]),

        deu('Der, die oder das?', 'Elige el artículo de cada palabra', '🧩', 'opcion_multiple', [
            omp('Der, die oder das? ___ Hund', ['der', 'die', 'das'], 'der', '🐶'),
            omp('Der, die oder das? ___ Katze', ['die', 'der', 'das'], 'die', '🐱'),
            omp('Der, die oder das? ___ Haus', ['das', 'der', 'die'], 'das', '🏠'),
            omp('Der, die oder das? ___ Sonne', ['die', 'das', 'der'], 'die', '☀️'),
            omp('Der, die oder das? ___ Buch', ['das', 'die', 'der'], 'das', '📕'),
            omp('Der, die oder das? ___ Apfel', ['der', 'das', 'die'], 'der', '🍎'),
        ]),

        deu('Silben klatschen', 'Aplaude las sílabas de cada palabra', '👏', 'opcion_multiple', [
            omp('Klatsch mit: Ba – na – ne. Wie viele Silben?', ['3', '2', '4'], '3', '🍌'),
            omp('Klatsch mit: Hund. Wie viele Silben?', ['1', '2', '3'], '1', '🐶'),
            omp('Klatsch mit: Kat – ze. Wie viele Silben?', ['2', '1', '3'], '2', '🐱'),
            omp('Klatsch mit: Te – le – fon. Wie viele Silben?', ['3', '2', '4'], '3', '📞'),
        ]),

        deu('Klasse 2 – Quiz', 'Repaso de segundo', '🏆', 'desafio_final', [
            reto('Welches Wort ist richtig?', ['der Apfel', 'der apfel'], 'der Apfel', '🍎'),
            reto('Lies mal! Was machst du?', ['📖', '✍️', '🙋'], '📖'),
            reto('Wie schmeckt der Zucker?', ['süß', 'sauer', 'salzig'], 'süß', '🍬'),
            reto('Die Mutter von meiner Mutter ist meine …', ['Oma', 'Tante', 'Schwester'], 'Oma', '👵'),
            reto('Wie fühlt sich das Kind?', ['müde', 'glücklich', 'wütend'], 'müde', '😴'),
            reto('Der, die oder das? ___ Buch', ['das', 'der', 'die'], 'das', '📕'),
        ]),
    ],
],

],
];
