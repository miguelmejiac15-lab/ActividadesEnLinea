<?php
/**
 * idiomas-aleman-mittelstufe.php — Deutsch: Klasse 3 y Klasse 4
 *
 * Segunda fase del alemán, sobre las mallas del Colegio Alemán
 * (`MallasPrimaria/ALEMAN/Deutsch K3.docx` y `K4.docx`):
 *
 *   Klasse 3 — otoño y naturaleza, salud y cuerpo, basura y reciclaje,
 *              compras y números del 11 al 20, mascotas, y «Das bin ich!»
 *              como preparación del examen A1.
 *   Klasse 4 — Lektion 7 a 12: la casa, la semana, la hora, el
 *              cumpleaños, los animales favoritos y los países.
 *
 * Mismas reglas de inmersión que la fase 1 (ver idiomas-aleman.php):
 * todo en alemán —también el título y la indicación de cada estación—,
 * sin traducciones, sustantivos con artículo, dictado sin la palabra a la
 * vista y sin ß ni diéresis en sopas de letras y crucigramas.
 *
 * La descripción y el objetivo de cada actividad siguen en español: los
 * leen la familia y el docente en el catálogo, no el niño.
 */

declare(strict_types=1);

// conDibujos() y soloOir() viven en la fase 1.
if (!function_exists('soloOir')) {
    require __DIR__ . '/idiomas-aleman.php';
}

return [

'bloques' => [
    ['slug' => 'deutsch-klasse-3', 'name' => 'Deutsch · Klasse 3', 'icon' => '🍂', 'sort_order' => 10,
     'description' => 'Alemán de tercero: el otoño, la salud, el reciclaje, las compras, las mascotas y la preparación del A1.'],
    ['slug' => 'deutsch-klasse-4', 'name' => 'Deutsch · Klasse 4', 'icon' => '🏠', 'sort_order' => 11,
     'description' => 'Alemán de cuarto: la casa, la semana, la hora, el cumpleaños, los animales y los países.'],
],

'actividades' => [


// =====================================================================
//  DEUTSCH · KLASSE 3
// =====================================================================

[
    'slug'  => 'deutsch-herbst',
    'title' => 'Der Herbst ist da!',
    'description' => 'El otoño en alemán: el tiempo, el bosque y lo que se recoge en la naturaleza.',
    'objective' => 'Describir el tiempo de otoño y nombrar materiales de la naturaleza con su artículo.',
    'icon' => '🍂', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-3',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'naturaleza'],
    'estaciones' => [

        deu('Im Wald', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '🍂', 'w' => 'das Blatt'],
            ['e' => '🌰', 'w' => 'die Kastanie'],
            ['e' => '🍄', 'w' => 'der Pilz'],
            ['e' => '🌲', 'w' => 'der Baum'],
            ['e' => '🦔', 'w' => 'der Igel'],
            ['e' => '🪵', 'w' => 'das Holz'],
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '💨', 'w' => 'Es ist windig.'],
            ['e' => '🌧️', 'w' => 'Es regnet.'],
            ['e' => '🥶', 'w' => 'Mir ist kalt.'],
            ['e' => '🍁', 'w' => 'Die Blätter sind bunt.'],
            ['e' => '🧺', 'w' => 'Wir sammeln Kastanien.'],
        ]),

        deu('Wie ist das Wetter?', 'Wähle die richtige Antwort', '❓', 'opcion_multiple', [
            omp('Wie ist das Wetter?', ['Es ist windig.', 'Es ist heiß.', 'Es schneit.'], 'Es ist windig.', '💨'),
            omp('Wie ist das Wetter?', ['Es regnet.', 'Es ist sonnig.', 'Es ist warm.'], 'Es regnet.', '🌧️'),
            omp('Welche Farben haben die Blätter im Herbst?', ['gelb, rot und braun', 'blau und weiß', 'rosa und lila'], 'gelb, rot und braun', '🍁'),
            omp('Was sammeln wir im Wald?', ['Kastanien', 'Muscheln', 'Schneebälle'], 'Kastanien', '🌰'),
            omp('Es ist kalt. Was sagst du?', ['Mir ist kalt.', 'Ich bin kalt.', 'Mich ist kalt.'], 'Mir ist kalt.', '🥶'),
        ]),

        deu('Herbstwörter', 'Finde die versteckten Wörter', '🔍', 'sopa_letras',
            sopa(['HERBST', 'BLATT', 'BAUM', 'PILZ', 'WIND', 'REGEN'], 10)),

        deu('Diktat', 'Hör zu und schreib das Wort', '⌨️', 'teclado',
            ['Herbst', 'Blatt', 'Baum', 'Wind', 'Regen']),
    ],
],

[
    'slug'  => 'deutsch-gesundheit',
    'title' => 'Ich bin krank',
    'description' => 'La salud en alemán: el cuerpo, cómo te sientes y qué te duele.',
    'objective' => 'Nombrar partes del cuerpo, decir cómo se siente uno y qué le duele, y pedir ayuda.',
    'icon' => '🤒', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-3',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'cuerpo'],
    'estaciones' => [

        deu('Mein Körper', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '👂', 'w' => 'das Ohr'],
            ['e' => '👃', 'w' => 'die Nase'],
            ['e' => '👁️', 'w' => 'das Auge'],
            ['e' => '🦷', 'w' => 'der Zahn'],
            ['e' => '🦵', 'w' => 'das Bein'],
            ['e' => '💪', 'w' => 'der Arm'],
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '🤒', 'w' => 'Ich bin krank.'],
            ['e' => '😴', 'w' => 'Ich bin müde.'],
            ['e' => '🤕', 'w' => 'Mein Kopf tut weh.'],
            ['e' => '🦷', 'w' => 'Mein Zahn tut weh.'],
            ['e' => '😊', 'w' => 'Mir geht es gut.'],
        ]),

        deu('Fragen und Antworten', 'Verbinde jede Frage mit ihrer Antwort', '💬', 'emparejar', [
            ['q' => 'Wie geht es dir?', 'e' => '🤒', 'w' => 'Nicht so gut. Ich bin krank.'],
            ['q' => 'Was tut dir weh?', 'e' => '🤕', 'w' => 'Mein Kopf tut weh.'],
            ['q' => 'Bist du müde?',    'e' => '😴', 'w' => 'Ja, ich bin sehr müde.'],
            ['q' => 'Was brauchst du?', 'e' => '🛏️', 'w' => 'Ich brauche Ruhe.'],
        ]),

        deu('Was ist los?', 'Wähle die richtige Antwort', '❓', 'opcion_multiple', [
            omp('Wie geht es dem Kind?', ['Es ist krank.', 'Es ist glücklich.', 'Es ist hungrig.'], 'Es ist krank.', '🤒'),
            omp('Der Junge hat Zahnschmerzen. Wohin geht er?', ['zum Zahnarzt', 'zum Bäcker', 'zum Friseur'], 'zum Zahnarzt', '🦷'),
            omp('Was hilft, wenn du krank bist?', ['Tee und Ruhe', 'Eis und Fußball', 'Bonbons und Fernsehen'], 'Tee und Ruhe', '🍵'),
            omp('Was tut weh?', ['der Kopf', 'der Tisch', 'der Apfel'], 'der Kopf', '🤕'),
            omp('Was ist gesund?', ['Obst und Gemüse', 'nur Bonbons', 'nur Pommes'], 'Obst und Gemüse', '🥦'),
        ]),

        deu('Gesund-Quiz', 'Zeig, was du weißt', '🏆', 'desafio_final', [
            reto('Ich bin ___. Ich möchte schlafen.', ['müde', 'krank', 'hungrig'], 'müde', '😴'),
            reto('Mein Kopf tut ___.', ['weh', 'gut', 'krank'], 'weh', '🤕'),
            reto('Welches Wort ist richtig?', ['das Ohr', 'der Ohr', 'die Ohr'], 'das Ohr', '👂'),
            reto('Was ist gesund?', ['der Apfel', 'die Chips', 'die Limonade'], 'der Apfel', '🍎'),
            reto('Mir ist ___. Ich brauche eine Jacke.', ['kalt', 'warm', 'heiß'], 'kalt', '🥶'),
        ]),
    ],
],

[
    'slug'  => 'deutsch-muell',
    'title' => 'Wohin kommt der Müll?',
    'description' => 'La basura y el reciclaje en alemán: separar, reutilizar y cuidar el planeta.',
    'objective' => 'Nombrar residuos comunes con su artículo y decidir en qué contenedor va cada uno.',
    'icon' => '♻️', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-3',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'naturaleza'],
    'estaciones' => [

        deu('Was ist das?', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '🍾', 'w' => 'die Flasche'],
            ['e' => '📰', 'w' => 'die Zeitung'],
            ['e' => '🥫', 'w' => 'die Dose'],
            ['e' => '📦', 'w' => 'der Karton'],
            ['e' => '🍌', 'w' => 'die Bananenschale'],
            ['e' => '🔋', 'w' => 'die Batterie'],
        ]),

        deu('In die Papiertonne', 'Tippe auf alles aus Papier', '📰', 'seleccion_imagenes',
            conTitulo('Was kommt in die Papiertonne?', 'Tippe auf alles aus Papier', [
                ['e' => '📰', 'n' => 'die Zeitung',  'ok' => true],
                ['e' => '📦', 'n' => 'der Karton',   'ok' => true],
                ['e' => '📒', 'n' => 'das Heft',     'ok' => true],
                ['e' => '✉️', 'n' => 'der Brief',    'ok' => true],
                ['e' => '🍾', 'n' => 'die Flasche',  'ok' => false],
                ['e' => '🥫', 'n' => 'die Dose',     'ok' => false],
                ['e' => '🍌', 'n' => 'die Bananenschale', 'ok' => false],
                ['e' => '🔋', 'n' => 'die Batterie', 'ok' => false],
            ])),

        deu('Wohin damit?', 'Wähle die richtige Antwort', '♻️', 'opcion_multiple', [
            omp('Wohin kommt die Flasche?', ['in den Glascontainer', 'in die Papiertonne', 'in den Biomüll'], 'in den Glascontainer', '🍾'),
            omp('Wohin kommt die Bananenschale?', ['in den Biomüll', 'in den Glascontainer', 'in die Papiertonne'], 'in den Biomüll', '🍌'),
            omp('Wohin kommt die Zeitung?', ['in die Papiertonne', 'in den Biomüll', 'in den Glascontainer'], 'in die Papiertonne', '📰'),
            omp('Was können wir aus Karton basteln?', ['ein Puppenhaus', 'einen Apfel', 'ein Glas Wasser'], 'ein Puppenhaus', '📦'),
            omp('Was ist gut für die Umwelt?', ['Müll trennen', 'Müll in den Park werfen', 'viel Plastik kaufen'], 'Müll trennen', '🌍'),
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '♻️', 'w' => 'Wir trennen den Müll.'],
            ['e' => '🌍', 'w' => 'Wir schützen die Umwelt.'],
            ['e' => '📦', 'w' => 'Wir basteln mit Karton.'],
            ['e' => '🚮', 'w' => 'Der Müll kommt in den Mülleimer.'],
        ]),

        deu('Diktat', 'Hör zu und schreib das Wort', '⌨️', 'teclado',
            ['Müll', 'Flasche', 'Dose', 'Papier', 'Glas']),
    ],
],

[
    'slug'  => 'deutsch-einkaufen',
    'title' => 'Wie viel kostet das?',
    'description' => 'Ir de compras en alemán: los números del 11 al 20, los precios y el diálogo en la tienda.',
    'objective' => 'Contar del 11 al 20, preguntar y decir precios y participar en un diálogo sencillo de compra.',
    'icon' => '🛒', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-3',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'numeros'],
    'estaciones' => [

        deu('Zahlen von 11 bis 20', 'Verbinde jede Zahl mit ihrem Wort', '🔢', 'emparejar', [
            ['e' => '11', 'w' => 'elf'],
            ['e' => '12', 'w' => 'zwölf'],
            ['e' => '13', 'w' => 'dreizehn'],
            ['e' => '15', 'w' => 'fünfzehn'],
            ['e' => '17', 'w' => 'siebzehn'],
            ['e' => '20', 'w' => 'zwanzig'],
        ]),

        deu('Zähl mit!', 'Bring die Zahlen in die richtige Reihenfolge', '➡️', 'ordenar_secuencia', [
            'title' => 'Ordne die Zahlen',
            'items' => ['elf', 'zwölf', 'dreizehn', 'vierzehn', 'fünfzehn', 'sechzehn'],
        ]),

        deu('Im Laden', 'Wähle die richtige Antwort', '🛒', 'opcion_multiple', [
            omp('Das Heft kostet 15 €. Wie viel kostet das Heft?', ['fünfzehn Euro', 'fünf Euro', 'fünfzig Euro'], 'fünfzehn Euro', '📒'),
            omp('Der Ball kostet 20 €. Wie viel kostet der Ball?', ['zwanzig Euro', 'zwölf Euro', 'zwei Euro'], 'zwanzig Euro', '⚽'),
            omp('Das Eis kostet 2 €. Wie viel kostet das Eis?', ['zwei Euro', 'zwanzig Euro', 'zwölf Euro'], 'zwei Euro', '🍦'),
            omp('Was fragst du im Laden?', ['Wie viel kostet das?', 'Wie alt bist du?', 'Wo wohnst du?'], 'Wie viel kostet das?', '🛒'),
            omp('Zehn plus sieben ist …', ['siebzehn', 'siebzig', 'sieben'], 'siebzehn', '🔢'),
        ]),

        deu('Beim Einkaufen', 'Verbinde jede Frage mit ihrer Antwort', '💬', 'emparejar', [
            ['q' => 'Was möchtest du?',         'e' => '🍎', 'w' => 'Ich möchte drei Äpfel, bitte.'],
            ['q' => 'Wie viel kostet das Brot?', 'e' => '🍞', 'w' => 'Das Brot kostet zwei Euro.'],
            ['q' => 'Brauchst du eine Tüte?',    'e' => '🛍️', 'w' => 'Nein, danke. Ich habe eine Tasche.'],
            ['q' => 'Ist das alles?',            'e' => '🧾', 'w' => 'Ja, das ist alles. Danke!'],
        ]),

        deu('Diktat', 'Hör zu und schreib das Wort', '⌨️', 'teclado',
            ['elf', 'zwölf', 'fünfzehn', 'zwanzig', 'Euro']),
    ],
],

[
    'slug'  => 'deutsch-haustiere',
    'title' => 'Hast du ein Haustier?',
    'description' => 'Las mascotas en alemán: cómo se llaman, qué saben hacer y qué sabes hacer tú.',
    'objective' => 'Nombrar mascotas con su artículo, decir lo que pueden hacer con «können» y hablar de la propia mascota.',
    'icon' => '🐱', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-3',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'animales'],
    'estaciones' => [

        deu('Die Haustiere', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '🐱', 'w' => 'die Katze'],
            ['e' => '🐶', 'w' => 'der Hund'],
            ['e' => '🐹', 'w' => 'der Hamster'],
            ['e' => '🐰', 'w' => 'das Kaninchen'],
            ['e' => '🐦', 'w' => 'der Vogel'],
            ['e' => '🐟', 'w' => 'der Fisch'],
        ]),

        deu('Was können die Tiere?', 'Wähle die richtige Antwort', '❓', 'opcion_multiple', [
            omp('Was kann der Fisch?', ['schwimmen', 'fliegen', 'singen'], 'schwimmen', '🐟'),
            omp('Was kann der Vogel?', ['fliegen', 'schwimmen', 'lesen'], 'fliegen', '🐦'),
            omp('Was kann der Hund?', ['bellen', 'fliegen', 'schreiben'], 'bellen', '🐶'),
            omp('Was kann das Kaninchen?', ['springen', 'sprechen', 'fliegen'], 'springen', '🐰'),
            omp('Was kann die Katze nicht?', ['fliegen', 'klettern', 'schlafen'], 'fliegen', '🐱'),
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '🐶', 'w' => 'Ich habe einen Hund.'],
            ['e' => '🐱', 'w' => 'Meine Katze heißt Mimi.'],
            ['e' => '🦜', 'w' => 'Mein Lieblingstier ist der Papagei.'],
            ['e' => '🏊', 'w' => 'Ich kann schwimmen.'],
            ['e' => '🚲', 'w' => 'Ich kann Fahrrad fahren.'],
        ]),

        deu('Stimmt das?', 'Antworte schnell: ja oder nein', '⚡', 'juego_rapido',
            conTitulo('Stimmt das?', 'Antworte schnell', [
                ['e' => '🐟', 'n' => 'Der Fisch kann schwimmen.',  'ok' => true],
                ['e' => '🐶', 'n' => 'Der Hund kann fliegen.',     'ok' => false],
                ['e' => '🐦', 'n' => 'Der Vogel hat Federn.',      'ok' => true],
                ['e' => '🐱', 'n' => 'Die Katze sagt «Wau».',      'ok' => false],
                ['e' => '🐰', 'n' => 'Das Kaninchen frisst Karotten.', 'ok' => true],
                ['e' => '🐹', 'n' => 'Der Hamster ist sehr groß.', 'ok' => false],
            ])),

        deu('Memory', 'Finde die Paare', '🧠', 'memoria',
            ['🐱', '🐶', '🐹', '🐰', '🐦', '🐟']),
    ],
],

[
    'slug'  => 'deutsch-das-bin-ich',
    'title' => 'Das bin ich!',
    'description' => 'Hablar de uno mismo en alemán: nombre, edad, ciudad, mascota, gustos y amigos. Preparación para el A1.',
    'objective' => 'Presentarse, leer y entender un perfil corto («Steckbrief») y escribir una carta sencilla a un amigo.',
    'icon' => '📝', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-3',
    'duracion' => 15, 'tags' => ['aleman', 'lectura', 'escritura'],
    'estaciones' => [

        deu('Mein Steckbrief', 'Lies und beantworte die Fragen', '📖', 'cuento', [
            'slides' => [
                ['img' => '👧', 'text' => 'Hallo! Ich heiße Lina. Ich bin neun Jahre alt.'],
                ['img' => '🏙️', 'text' => 'Ich wohne in Bogotá. Ich gehe in die dritte Klasse.'],
                ['img' => '🐱', 'text' => 'Ich habe eine Katze. Sie heißt Mimi.'],
                ['img' => '⚽', 'text' => 'Ich spiele gern Fußball. Ich kann schnell laufen.'],
                ['img' => '👭', 'text' => 'Meine beste Freundin heißt Sofia. Wir malen gern zusammen.'],
            ],
            'qs' => [
                reto('Wie alt ist Lina?', ['neun', 'acht', 'zehn'], 'neun', '🎂'),
                reto('Wo wohnt Lina?', ['in Bogotá', 'in Berlin', 'in Cali'], 'in Bogotá', '🏙️'),
                reto('Welches Haustier hat Lina?', ['eine Katze', 'einen Hund', 'einen Fisch'], 'eine Katze', '🐱'),
                reto('Was macht Lina gern?', ['Fußball spielen', 'schwimmen', 'kochen'], 'Fußball spielen', '⚽'),
            ],
        ]),

        deu('Fragen über dich', 'Verbinde jede Frage mit ihrer Antwort', '💬', 'emparejar', [
            ['q' => 'Wie heißt du?',        'e' => '🙋', 'w' => 'Ich heiße Lina.'],
            ['q' => 'Wie alt bist du?',     'e' => '🎂', 'w' => 'Ich bin neun Jahre alt.'],
            ['q' => 'Wo wohnst du?',        'e' => '🏠', 'w' => 'Ich wohne in Bogotá.'],
            ['q' => 'Was machst du gern?',  'e' => '⚽', 'w' => 'Ich spiele gern Fußball.'],
            ['q' => 'Hast du Geschwister?', 'e' => '👦', 'w' => 'Ja, ich habe einen Bruder.'],
        ]),

        deu('Ich oder du?', 'Wähle die richtige Form', '✏️', 'opcion_multiple', [
            omp('Ich ___ Lina.', ['heiße', 'heißt', 'heißen'], 'heiße', '🙋'),
            omp('Wie ___ du?', ['heißt', 'heiße', 'heißen'], 'heißt', '❓'),
            omp('Ich ___ neun Jahre alt.', ['bin', 'bist', 'ist'], 'bin', '🎂'),
            omp('Ich ___ gern Fußball.', ['spiele', 'spielst', 'spielt'], 'spiele', '⚽'),
            omp('Ich ___ schwimmen.', ['kann', 'kannst', 'können'], 'kann', '🏊'),
        ]),

        deu('Ein Brief an Tom', 'Setze die Wörter in die Lücken', '✉️', 'completar_texto',
            conTitulo('Schreib einen Brief', 'Lies den ganzen Satz, bevor du wählst', [
                [
                    'titulo' => 'Lieber Tom,',
                    'texto'  => 'ich ___ Lina und ich bin neun Jahre ___. Ich wohne in Bogotá. '
                              . 'Ich habe eine ___. Sie heißt Mimi. Ich spiele gern ___. '
                              . 'Viele Grüße, Lina',
                    'huecos' => ['heiße', 'alt', 'Katze', 'Fußball'],
                    'extra'  => ['wohne', 'groß', 'Schule'],
                ],
            ])),

        deu('A1-Quiz', 'Zeig, was du weißt', '🏆', 'desafio_final', [
            reto('Wie heißt du?', ['Ich heiße Max.', 'Ich bin neun.', 'Ich wohne hier.'], 'Ich heiße Max.', '🙋'),
            reto('Was kannst du?', ['Ich kann gut malen.', 'Ich habe einen Hund.', 'Ich bin müde.'], 'Ich kann gut malen.', '🎨'),
            reto('Wie viel kostet das?', ['Drei Euro.', 'Drei Jahre.', 'Drei Uhr.'], 'Drei Euro.', '💶'),
            reto('Wohin kommt die Zeitung?', ['in die Papiertonne', 'in den Biomüll', 'in den Glascontainer'], 'in die Papiertonne', '📰'),
            reto('Mir ist kalt. Was ziehe ich an?', ['eine Jacke', 'eine Badehose', 'Sandalen'], 'eine Jacke', '🧥'),
        ]),
    ],
],


// =====================================================================
//  DEUTSCH · KLASSE 4  (Lektion 7 a 12)
// =====================================================================

[
    'slug'  => 'deutsch-mein-haus',
    'title' => 'Mein Haus, mein Zimmer',
    'description' => 'La casa en alemán: las habitaciones, los muebles y lo que se hace en cada lugar.',
    'objective' => 'Nombrar habitaciones y muebles con su artículo, describir el propio cuarto y usar «mein/meine».',
    'icon' => '🏠', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-4',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'casa'],
    'estaciones' => [

        deu('Die Zimmer', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '🛏️', 'w' => 'das Schlafzimmer'],
            ['e' => '🍳', 'w' => 'die Küche'],
            ['e' => '🛁', 'w' => 'das Badezimmer'],
            ['e' => '🛋️', 'w' => 'das Wohnzimmer'],
            ['e' => '🌳', 'w' => 'der Garten'],
            ['e' => '🚗', 'w' => 'die Garage'],
        ]),

        deu('In meinem Zimmer', 'Verbinde jedes Bild mit seinem Wort', '🪑', 'emparejar', [
            ['e' => '🛏️', 'w' => 'das Bett'],
            ['e' => '🪑', 'w' => 'der Stuhl'],
            ['e' => '💡', 'w' => 'die Lampe'],
            ['e' => '📚', 'w' => 'das Regal'],
            ['e' => '🪟', 'w' => 'das Fenster'],
            ['e' => '🚪', 'w' => 'die Tür'],
        ]),

        deu('Wo machst du was?', 'Wähle die richtige Antwort', '❓', 'opcion_multiple', [
            omp('Wo kochst du?', ['in der Küche', 'im Badezimmer', 'im Garten'], 'in der Küche', '🍳'),
            omp('Wo schläfst du?', ['im Schlafzimmer', 'in der Küche', 'in der Garage'], 'im Schlafzimmer', '🛏️'),
            omp('Wo siehst du fern?', ['im Wohnzimmer', 'im Badezimmer', 'in der Garage'], 'im Wohnzimmer', '📺'),
            omp('Wo duschst du?', ['im Badezimmer', 'im Wohnzimmer', 'im Garten'], 'im Badezimmer', '🚿'),
            omp('Das ist ___ Zimmer.', ['mein', 'meine'], 'mein', '🏠'),
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '🏠', 'w' => 'Ich wohne in einem Haus.'],
            ['e' => '🏢', 'w' => 'Wir wohnen in einer Wohnung.'],
            ['e' => '🛏️', 'w' => 'Mein Zimmer ist klein und hell.'],
            ['e' => '📚', 'w' => 'In meinem Zimmer ist ein Regal.'],
        ]),

        deu('Diktat', 'Hör zu und schreib das Wort', '⌨️', 'teclado',
            ['Haus', 'Zimmer', 'Küche', 'Bett', 'Tisch']),
    ],
],

[
    'slug'  => 'deutsch-meine-woche',
    'title' => 'Meine Woche',
    'description' => 'La semana en alemán: las materias, el horario del colegio y el tiempo libre.',
    'objective' => 'Hablar del horario semanal y del tiempo libre, con el verbo en segundo lugar («Am Montag habe ich…»).',
    'icon' => '🗓️', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-4',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'escuela'],
    'estaciones' => [

        deu('Die Schulfächer', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '🔢', 'w' => 'Mathe'],
            ['e' => '📖', 'w' => 'Deutsch'],
            ['e' => '🎨', 'w' => 'Kunst'],
            ['e' => '🎵', 'w' => 'Musik'],
            ['e' => '⚽', 'w' => 'Sport'],
            ['e' => '🔬', 'w' => 'Sachkunde'],
        ]),

        deu('Was machst du am …?', 'Wähle den richtigen Satz', '❓', 'opcion_multiple', [
            omp('Was machst du am Montag?', ['Am Montag habe ich Sport.', 'Am Montag ich habe Sport.', 'Montag Sport habe ich am.'], 'Am Montag habe ich Sport.', '🗓️'),
            omp('Welcher Satz ist richtig?', ['Am Wochenende spiele ich Fußball.', 'Am Wochenende ich spiele Fußball.', 'Ich am Wochenende spiele Fußball.'], 'Am Wochenende spiele ich Fußball.', '⚽'),
            omp('Was ist dein Lieblingsfach?', ['Mein Lieblingsfach ist Kunst.', 'Ich bin Kunst.', 'Kunst ist am Montag.'], 'Mein Lieblingsfach ist Kunst.', '🎨'),
            omp('Was isst du in der Pause?', ['ein Brot', 'ein Bett', 'ein Buch'], 'ein Brot', '🥪'),
            omp('Er ___ gern Musik.', ['hört', 'höre', 'hören'], 'hört', '🎧'),
        ]),

        deu('Fragen und Antworten', 'Verbinde jede Frage mit ihrer Antwort', '💬', 'emparejar', [
            ['q' => 'Wann hast du Mathe?',             'e' => '🔢', 'w' => 'Am Dienstag und am Freitag.'],
            ['q' => 'Was machst du am Nachmittag?',    'e' => '🏊', 'w' => 'Ich gehe schwimmen.'],
            ['q' => 'Was machst du am Wochenende?',    'e' => '🚲', 'w' => 'Ich fahre Fahrrad.'],
            ['q' => 'Was machst du gern?',             'e' => '🎨', 'w' => 'Ich male gern.'],
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '📖', 'w' => 'Am Montag habe ich Deutsch.'],
            ['e' => '🏊', 'w' => 'Am Mittwoch gehe ich schwimmen.'],
            ['e' => '🎹', 'w' => 'Am Donnerstag spiele ich Klavier.'],
            ['e' => '😴', 'w' => 'Am Sonntag schlafe ich lange.'],
        ]),

        deu('Kreuzworträtsel', 'Tippe auf einen Hinweis und schreib das Wort', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'MONTAG',  'pista' => 'Der erste Tag der Woche', 'e' => '1️⃣'],
                ['w' => 'SPORT',   'pista' => 'Laufen, springen, Fußball', 'e' => '⚽'],
                ['w' => 'MUSIK',   'pista' => 'Singen und Instrumente', 'e' => '🎵'],
                ['w' => 'KUNST',   'pista' => 'Malen und basteln', 'e' => '🎨'],
                ['w' => 'SONNTAG', 'pista' => 'Der letzte Tag der Woche', 'e' => '7️⃣'],
                ['w' => 'PAUSE',   'pista' => 'Zeit zum Essen und Spielen in der Schule', 'e' => '🥪'],
            ])),
    ],
],

[
    'slug'  => 'deutsch-meine-zeit',
    'title' => 'Wie spät ist es?',
    'description' => 'La hora y el día en alemán: la rutina, con qué frecuencia y lo que hay que hacer o se quiere hacer.',
    'objective' => 'Decir la hora, contar la rutina diaria y usar «immer, oft, manchmal, nie» y «müssen/möchten».',
    'icon' => '⏰', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-4',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'hora'],
    'estaciones' => [

        deu('Die Uhrzeit', 'Verbinde jede Uhr mit ihrem Satz', '🕒', 'emparejar', [
            ['e' => '🕐', 'w' => 'Es ist ein Uhr.'],
            ['e' => '🕒', 'w' => 'Es ist drei Uhr.'],
            ['e' => '🕕', 'w' => 'Es ist sechs Uhr.'],
            ['e' => '🕘', 'w' => 'Es ist neun Uhr.'],
            ['e' => '🕜', 'w' => 'Es ist halb zwei.'],
        ]),

        deu('Mein Tag', 'Bring den Tag in die richtige Reihenfolge', '🔢', 'ordenar_secuencia', [
            'title' => 'Was machst du zuerst?',
            'items' => [
                ['e' => '⏰', 'w' => 'Ich stehe auf.'],
                ['e' => '🥐', 'w' => 'Ich frühstücke.'],
                ['e' => '🏫', 'w' => 'Ich gehe in die Schule.'],
                ['e' => '📚', 'w' => 'Ich mache Hausaufgaben.'],
                ['e' => '🛏️', 'w' => 'Ich gehe ins Bett.'],
            ],
        ]),

        deu('Wie oft?', 'Wähle das richtige Wort', '❓', 'opcion_multiple', [
            omp('Lina spielt jeden Tag Fußball. Sie spielt ___ Fußball.', ['immer', 'nie', 'manchmal'], 'immer', '⚽'),
            omp('Tom isst keine Pilze. Er isst ___ Pilze.', ['nie', 'oft', 'immer'], 'nie', '🍄'),
            omp('Ich ___ Hausaufgaben machen. Das ist Pflicht.', ['muss', 'möchte', 'kann'], 'muss', '📚'),
            omp('Ich ___ ein Eis essen. Das ist mein Wunsch.', ['möchte', 'muss', 'bin'], 'möchte', '🍦'),
            omp('Um wie viel Uhr frühstückst du?', ['Um sieben Uhr.', 'Am Montag.', 'Im Sommer.'], 'Um sieben Uhr.', '🥐'),
        ]),

        deu('Was hörst du?', 'Hör zu und wähle den Satz', '👂', 'opcion_multiple', [
            soloOir('Es ist acht Uhr.',              ['Es ist acht Uhr.', 'Es ist elf Uhr.', 'Es ist halb acht.']),
            soloOir('Ich frühstücke um sieben Uhr.', ['Ich schlafe um sieben Uhr.', 'Ich frühstücke um sieben Uhr.', 'Ich frühstücke um zehn Uhr.']),
            soloOir('Nach der Schule spiele ich.',   ['Vor der Schule spiele ich.', 'In der Schule spiele ich.', 'Nach der Schule spiele ich.']),
            soloOir('Es ist halb drei.',             ['Es ist halb drei.', 'Es ist drei Uhr.', 'Es ist halb zwei.']),
        ]),

        deu('Diktat', 'Hör zu und schreib das Wort', '⌨️', 'teclado',
            ['Uhr', 'Morgen', 'Abend', 'Frühstück', 'immer']),
    ],
],

[
    'slug'  => 'deutsch-mein-geburtstag',
    'title' => 'Mein Geburtstag',
    'description' => 'El cumpleaños en alemán: la fecha, la invitación, los regalos y cómo felicitar y dar las gracias.',
    'objective' => 'Decir la fecha con números ordinales, responder a una invitación, felicitar y agradecer un regalo.',
    'icon' => '🎂', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-4',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'fiestas'],
    'estaciones' => [

        deu('Die Party', 'Verbinde jedes Bild mit seinem Wort', '🔗', 'emparejar', [
            ['e' => '🎂', 'w' => 'der Kuchen'],
            ['e' => '🎁', 'w' => 'das Geschenk'],
            ['e' => '🎈', 'w' => 'der Luftballon'],
            ['e' => '🕯️', 'w' => 'die Kerze'],
            ['e' => '✉️', 'w' => 'die Einladung'],
            ['e' => '🎉', 'w' => 'die Party'],
        ]),

        deu('Wann hast du Geburtstag?', 'Wähle die richtige Antwort', '❓', 'opcion_multiple', [
            omp('Lina hat am 3. Mai Geburtstag. Wann hat sie Geburtstag?', ['Am dritten Mai.', 'Im dritten Mai.', 'Um dritten Mai.'], 'Am dritten Mai.', '🎂'),
            omp('Heute ist der 1. Juni. Welches Datum ist heute?', ['Heute ist der erste Juni.', 'Heute ist der eins Juni.', 'Heute ist erste Juni.'], 'Heute ist der erste Juni.', '📅'),
            omp('In welchem Monat ist Weihnachten?', ['im Dezember', 'im Juli', 'im März'], 'im Dezember', '🎄'),
            omp('Für wen ist das Geschenk? Für ___!', ['dich', 'du', 'dir'], 'dich', '🎁'),
            omp('Gefällt dir das Geschenk?', ['Ja, es gefällt mir sehr!', 'Ja, ich bin neun.', 'Nein, ich wohne hier.'], 'Ja, es gefällt mir sehr!', '😍'),
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '🎂', 'w' => 'Herzlichen Glückwunsch zum Geburtstag!'],
            ['e' => '🎁', 'w' => 'Vielen Dank für das Geschenk!'],
            ['e' => '🎈', 'w' => 'Kommst du zu meiner Party?'],
            ['e' => '🙋', 'w' => 'Ja, gern! Ich komme.'],
        ]),

        deu('Die Einladung', 'Setze die Wörter in die Lücken', '✉️', 'completar_texto',
            conTitulo('Lies die Einladung', 'Lies den ganzen Satz, bevor du wählst', [
                [
                    'titulo' => 'Einladung',
                    'texto'  => 'Liebe Sofia, ich habe am zehnten ___ Geburtstag. Ich mache eine ___. '
                              . 'Wir essen ___ und spielen im Garten. Kommst du? Deine Lina',
                    'huecos' => ['Mai', 'Party', 'Kuchen'],
                    'extra'  => ['Schule', 'Montag'],
                ],
            ])),

        deu('Geburtstags-Quiz', 'Zeig, was du weißt', '🏆', 'desafio_final', [
            reto('Was sagst du zum Geburtstag?', ['Herzlichen Glückwunsch!', 'Gute Nacht!', 'Guten Appetit!'], 'Herzlichen Glückwunsch!', '🎂'),
            reto('Der 2. März ist der ___ März.', ['zweite', 'zwei', 'zweiten'], 'zweite', '📅'),
            reto('Ich wünsche mir ___ Fahrrad.', ['ein', 'eine', 'einen'], 'ein', '🚲'),
            reto('Kuchen oder Eis? Ich mag ___ Eis.', ['lieber', 'besser', 'viel'], 'lieber', '🍦'),
            reto('Was sagst du für ein Geschenk?', ['Vielen Dank!', 'Tschüss!', 'Wie bitte?'], 'Vielen Dank!', '🎁'),
        ]),
    ],
],

[
    'slug'  => 'deutsch-lieblingstiere',
    'title' => 'Meine Lieblingstiere',
    'description' => 'Los animales en alemán: el plural, qué saben hacer y cuál es tu favorito.',
    'objective' => 'Nombrar animales en singular y plural, preguntar con «welcher/welche/welches» y decir el animal favorito.',
    'icon' => '🦁', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-4',
    'duracion' => 12, 'tags' => ['aleman', 'vocabulario', 'animales'],
    'estaciones' => [

        deu('Eins oder viele?', 'Verbinde jedes Tier mit seinem Plural', '🔗', 'emparejar', [
            ['q' => 'die Katze', 'e' => '🐱', 'w' => 'die Katzen'],
            ['q' => 'der Hund',  'e' => '🐶', 'w' => 'die Hunde'],
            ['q' => 'die Maus',  'e' => '🐭', 'w' => 'die Mäuse'],
            ['q' => 'das Pferd', 'e' => '🐴', 'w' => 'die Pferde'],
            ['q' => 'der Vogel', 'e' => '🐦', 'w' => 'die Vögel'],
        ]),

        deu('Welcher, welche, welches?', 'Wähle das richtige Wort', '❓', 'opcion_multiple', [
            omp('___ Tier magst du? (das Tier)', ['Welches', 'Welcher', 'Welche'], 'Welches', '🐾'),
            omp('___ Hund ist süß? (der Hund)', ['Welcher', 'Welches', 'Welche'], 'Welcher', '🐶'),
            omp('___ Katze ist schwarz? (die Katze)', ['Welche', 'Welcher', 'Welches'], 'Welche', '🐈‍⬛'),
            omp('Welches Tier ist gefährlich?', ['der Hai', 'das Kaninchen', 'der Hamster'], 'der Hai', '🦈'),
            omp('Katzen oder Hunde? Ich mag lieber ___.', ['Hunde', 'Hund', 'Hunden'], 'Hunde', '🐶'),
        ]),

        deu('Stimmt das?', 'Antworte schnell: ja oder nein', '⚡', 'juego_rapido',
            conTitulo('Stimmt das?', 'Antworte schnell', [
                ['e' => '🐘', 'n' => 'Der Elefant ist groß.',        'ok' => true],
                ['e' => '🐢', 'n' => 'Die Schildkröte ist schnell.', 'ok' => false],
                ['e' => '🦁', 'n' => 'Der Löwe ist gefährlich.',     'ok' => true],
                ['e' => '🐧', 'n' => 'Pinguine können fliegen.',     'ok' => false],
                ['e' => '🐒', 'n' => 'Affen können klettern.',       'ok' => true],
                ['e' => '🐟', 'n' => 'Fische können laufen.',        'ok' => false],
            ])),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '🦁', 'w' => 'Mein Lieblingstier ist der Löwe.'],
            ['e' => '🐼', 'w' => 'Ich finde Pandas süß.'],
            ['e' => '🦈', 'w' => 'Haie sind gefährlich.'],
            ['e' => '🐬', 'w' => 'Delfine können springen.'],
        ]),

        deu('Tierwörter', 'Finde die versteckten Wörter', '🔍', 'sopa_letras',
            sopa(['TIGER', 'AFFE', 'PANDA', 'HUND', 'PFERD', 'ZEBRA'], 10)),
    ],
],

[
    'slug'  => 'deutsch-mein-jahr',
    'title' => 'Mein Jahr',
    'description' => 'Países, idiomas, fiestas y vacaciones en alemán: a dónde viajar y qué se habla en cada lugar.',
    'objective' => 'Nombrar países y sus idiomas, hablar de las vacaciones con «nach» y usar el imperativo con «du» e «ihr».',
    'icon' => '🌍', 'nivel' => 'primaria-media', 'bloque' => 'deutsch-klasse-4',
    'duracion' => 15, 'tags' => ['aleman', 'vocabulario', 'cultura'],
    'estaciones' => [

        deu('Länder und Sprachen', 'Verbinde jedes Land mit seiner Sprache', '🗣️', 'emparejar', [
            ['q' => 'Deutschland', 'e' => '🥨', 'w' => 'Deutsch'],
            ['q' => 'Kolumbien',   'e' => '☕', 'w' => 'Spanisch'],
            ['q' => 'Frankreich',  'e' => '🥐', 'w' => 'Französisch'],
            ['q' => 'England',     'e' => '💂', 'w' => 'Englisch'],
            ['q' => 'Italien',     'e' => '🍕', 'w' => 'Italienisch'],
        ]),

        deu('Wohin fährst du?', 'Wähle die richtige Antwort', '❓', 'opcion_multiple', [
            omp('Wohin fährst du in den Ferien?', ['Ich fahre nach Cartagena.', 'Ich fahre in Cartagena.', 'Ich bin Cartagena.'], 'Ich fahre nach Cartagena.', '🏖️'),
            omp('Welche Sprache spricht man in Österreich?', ['Deutsch', 'Spanisch', 'Englisch'], 'Deutsch', '🏔️'),
            omp('Welches Fest ist im Dezember?', ['Weihnachten', 'Ostern', 'Karneval'], 'Weihnachten', '🎄'),
            omp('In welcher Jahreszeit ist es heiß?', ['im Sommer', 'im Winter', 'im Herbst'], 'im Sommer', '☀️'),
            omp('«Komm mit!» sagst du zu einem Kind. Was sagst du zu zwei Kindern?', ['Kommt mit!', 'Kommen mit!', 'Kommst mit!'], 'Kommt mit!', '👫'),
        ]),

        deu('Ferienpläne', 'Verbinde jede Frage mit ihrer Antwort', '💬', 'emparejar', [
            ['q' => 'Wohin möchtest du reisen?',     'e' => '✈️', 'w' => 'Ich möchte nach Deutschland fliegen.'],
            ['q' => 'Was machst du in den Ferien?',  'e' => '🏖️', 'w' => 'Ich schwimme im Meer.'],
            ['q' => 'Welche Sprachen sprichst du?',  'e' => '🗣️', 'w' => 'Ich spreche Spanisch und Deutsch.'],
            ['q' => 'Wann sind die Ferien?',         'e' => '☀️', 'w' => 'Im Juli.'],
        ]),

        deu('Hör zu und sprich nach', 'Sprich die Sätze laut nach', '🔊', 'pronunciacion', [
            ['e' => '✈️', 'w' => 'Ich fliege nach Berlin.'],
            ['e' => '🏖️', 'w' => 'In den Ferien fahre ich ans Meer.'],
            ['e' => '🏔️', 'w' => 'In der Schweiz spricht man Deutsch.'],
            ['e' => '🎆', 'w' => 'Silvester ist am einunddreißigsten Dezember.'],
        ]),

        deu('Klasse 4 – Quiz', 'Zeig, was du weißt', '🏆', 'desafio_final', [
            reto('Wie spät ist es?', ['Es ist drei Uhr.', 'Es ist dreizehn.', 'Es ist drei Jahre.'], 'Es ist drei Uhr.', '🕒'),
            reto('Wo kochst du?', ['in der Küche', 'im Bad', 'im Garten'], 'in der Küche', '🍳'),
            reto('Am Montag ___ ich Sport.', ['habe', 'hast', 'hat'], 'habe', '🗓️'),
            reto('die Maus – die …', ['Mäuse', 'Mauses', 'Mausen'], 'Mäuse', '🐭'),
            reto('Herzlichen …!', ['Glückwunsch', 'Geschenk', 'Geburtstag'], 'Glückwunsch', '🎂'),
            reto('Welche Sprache spricht man in Deutschland?', ['Deutsch', 'Deutschland', 'Deutsche'], 'Deutsch', '🥨'),
        ]),
    ],
],

],
];
