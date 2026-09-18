<?php
/**
 * juegos.php — Juegos y Retos
 *
 * Aquí se aplica la decisión de convertir esta categoría en un formato
 * transversal en vez de una materia más:
 *
 *     Juegos y Retos       = el FORMATO (contrarreloj, ruleta, desafío)
 *     Las demás categorías = el CONTENIDO
 *
 * Por eso los bloques son formatos, no temas, y por eso los «retos por
 * materia» llevan la etiqueta de la habilidad que entrenan además de la
 * de `reto`: un Reto Histórico aparece buscando «historia» aunque viva en
 * esta categoría. Sin las etiquetas esto no funcionaría y habría que
 * duplicar la actividad en dos sitios.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'juegos',
    'name'       => 'Juegos y Retos',
    'tagline'    => 'El mismo saber, en formato de desafío',
    'icon'       => '🎲',
    'color'      => '#ffa726',
    'sort_order' => 7,
],

'bloques' => [
    ['slug' => 'contrarreloj', 'name' => 'Contrarreloj', 'icon' => '⏱️', 'sort_order' => 1,
     'description' => 'Responder rápido y sin dudar. El reloj es parte del juego.'],
    ['slug' => 'desafios-por-materia', 'name' => 'Desafíos por Materia', 'icon' => '🏆', 'sort_order' => 2,
     'description' => 'Un reto final por cada materia, para medirse de verdad.'],
    ['slug' => 'juegos-de-memoria', 'name' => 'Juegos de Memoria', 'icon' => '🧠', 'sort_order' => 3,
     'description' => 'Tableros y series que ponen a trabajar la memoria.'],
],

'reasignar' => [
    'desafio-de-memoria'   => 'juegos-de-memoria',
    'ruleta-de-la-fortuna' => 'desafios-por-materia',
],

'actividades' => [


// ── CONTRARRELOJ ─────────────────────────────────────────────────────

/*
 * OJO con el slug: «carrera-de-palabras» ya existe en Aventura de las
 * Letras (un juego de carreras tecleando letras). Este es otro juego —de
 * ortografía contrarreloj— y por eso se llama distinto. El slug es la
 * llave con la que el sembrador reconoce una actividad: repetirlo no crea
 * una segunda, sobrescribe la primera.
 */
[
    'slug'  => 'carrera-ortografica',
    'title' => 'Carrera Ortográfica',
    'description' => 'Treinta segundos para decidir. ¿Está bien escrita o no?',
    'objective' => 'Automatizar el reconocimiento ortográfico bajo presión de tiempo.',
    'icon' => '⏱️', 'nivel' => 'primaria-inicial', 'bloque' => 'contrarreloj',
    'duracion' => 10, 'tags' => ['escritura', 'atencion', 'juego', 'reto'],
    'estaciones' => [

        est('¿Está bien escrita?', 'Responde rápido: sí o no', '⚡', 'juego_rapido',
            conTitulo('¿Está bien escrita?', 'Responde rápido', [
                ['e' => '🏠', 'n' => 'casa',    'ok' => true],
                ['e' => '🐄', 'n' => 'baca',    'ok' => false],
                ['e' => '🌳', 'n' => 'árbol',   'ok' => true],
                ['e' => '🚗', 'n' => 'coche',   'ok' => true],
                ['e' => '🐝', 'n' => 'aveja',   'ok' => false],
                ['e' => '📚', 'n' => 'libro',   'ok' => true],
                ['e' => '🌊', 'n' => 'holas',   'ok' => false],
                ['e' => '🎈', 'n' => 'globo',   'ok' => true],
                ['e' => '🕰️', 'n' => 'reloj',   'ok' => true],
                ['e' => '🦁', 'n' => 'leon',    'ok' => false],
            ])),

        est('¿Empieza con vocal?', 'Otra vez contra el reloj', '🔤', 'juego_rapido',
            conTitulo('¿Empieza con vocal?', 'Responde rápido', [
                ['e' => '🐘', 'n' => 'Elefante', 'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana',  'ok' => false],
                ['e' => '☂️', 'n' => 'Paraguas', 'ok' => false],
                ['e' => '🦅', 'n' => 'Águila',   'ok' => true],
                ['e' => '🏝️', 'n' => 'Isla',     'ok' => true],
                ['e' => '🐕', 'n' => 'Perro',    'ok' => false],
                ['e' => '👁️', 'n' => 'Ojo',      'ok' => true],
                ['e' => '🍇', 'n' => 'Uvas',     'ok' => true],
                ['e' => '🌙', 'n' => 'Luna',     'ok' => false],
                ['e' => '🐻', 'n' => 'Oso',      'ok' => true],
            ])),

        /*
         * Aquí NO se usa `ordenar_secuencia` para armar una palabra letra
         * por letra: ese minijuego compara por valor y no admite elementos
         * repetidos, así que «PALABRA» —con tres aes— obligaría a
         * distinguirlas de algún modo visible y el niño vería «A2» en un
         * botón. Para armar palabras está `armar_palabras`, que sí lleva
         * la cuenta por posición.
         */
        est('Vuelta final', 'Elige la escritura correcta', '🏁', 'ortografia', [
            ['e' => '🐄', 'opts' => ['vaca', 'baca', 'vaka'],       'correct' => 'vaca'],
            ['e' => '🐝', 'opts' => ['abeja', 'aveja', 'abegla'],   'correct' => 'abeja'],
            ['e' => '🌊', 'opts' => ['olas', 'holas', 'ollas'],     'correct' => 'olas'],
            ['e' => '🦁', 'opts' => ['león', 'leon', 'lion'],        'correct' => 'león'],
            ['e' => '🏠', 'opts' => ['casa', 'kasa', 'caza'],       'correct' => 'casa'],
            ['e' => '📚', 'opts' => ['biblioteca', 'bibioteca', 'viblioteca'], 'correct' => 'biblioteca'],
        ]),
    ],
],

[
    'slug'  => 'carrera-de-numeros',
    'title' => 'Carrera de Números',
    'description' => 'Cálculo mental contra el reloj. Sin lápiz ni papel.',
    'objective' => 'Automatizar operaciones básicas y comparaciones numéricas.',
    'icon' => '🔢', 'nivel' => 'primaria-inicial', 'bloque' => 'contrarreloj',
    'duracion' => 10, 'tags' => ['calculo', 'atencion', 'juego', 'reto'],
    'estaciones' => [

        est('¿Es correcto?', 'Sí o no, rápido', '⚡', 'juego_rapido',
            conTitulo('¿La operación es correcta?', 'Responde rápido', [
                ['e' => '➕', 'n' => '2 + 3 = 5',   'ok' => true],
                ['e' => '➕', 'n' => '4 + 4 = 9',   'ok' => false],
                ['e' => '➖', 'n' => '7 − 2 = 5',   'ok' => true],
                ['e' => '✖️', 'n' => '3 × 3 = 6',   'ok' => false],
                ['e' => '➕', 'n' => '6 + 6 = 12',  'ok' => true],
                ['e' => '➖', 'n' => '10 − 4 = 7',  'ok' => false],
                ['e' => '✖️', 'n' => '5 × 2 = 10',  'ok' => true],
                ['e' => '➕', 'n' => '8 + 5 = 13',  'ok' => true],
                ['e' => '✖️', 'n' => '4 × 3 = 15',  'ok' => false],
                ['e' => '➖', 'n' => '9 − 9 = 0',   'ok' => true],
            ])),

        est('Cálculo relámpago', 'Elige el resultado', '⚡', 'opcion_multiple', [
            omp('7 + 8 =',  [13, 14, 15, 16], 15),
            omp('12 − 5 =', [6, 7, 8, 9], 7),
            omp('6 × 4 =',  [20, 22, 24, 28], 24),
            omp('9 + 9 =',  [16, 17, 18, 19], 18),
            omp('20 − 8 =', [10, 11, 12, 13], 12),
            omp('7 × 3 =',  [18, 20, 21, 24], 21),
            omp('15 + 6 =', [19, 20, 21, 22], 21),
            omp('30 − 12 =', [16, 17, 18, 19], 18),
        ]),

        est('¿Cuál es mayor?', 'Compara sin pensarlo mucho', '⚖️', 'opcion_multiple', [
            omp('¿Cuál es mayor?', [47, 74], 74),
            omp('¿Cuál es mayor?', [108, 99], 108),
            omp('¿Cuál es mayor?', [250, 205], 250),
            omp('¿Cuál es mayor?', [1000, 999], 1000),
            omp('¿Cuál es mayor?', [36, 63], 63),
            omp('¿Cuál es mayor?', [512, 521], 521),
        ]),
    ],
],


// ── DESAFÍOS POR MATERIA ─────────────────────────────────────────────

[
    'slug'  => 'reto-matematico',
    'title' => 'Reto Matemático',
    'description' => 'Problemas que se resuelven pensando, no solo calculando.',
    'objective' => 'Aplicar operaciones a problemas de varios pasos.',
    'icon' => '🔢', 'nivel' => 'primaria-inicial', 'bloque' => 'desafios-por-materia',
    'duracion' => 12, 'tags' => ['calculo', 'logica', 'reto'],
    'estaciones' => [

        est('Problemas del día', 'Lee bien antes de calcular', '🧮', 'opcion_multiple', [
            omp('Tengo 12 galletas y regalo 5. ¿Cuántas quedan?', [5, 6, 7, 8], 7, '🍪'),
            omp('Hay 4 mesas con 5 sillas cada una. ¿Cuántas sillas hay?', [9, 15, 20, 25], 20, '🪑'),
            omp('Un lápiz cuesta $800. ¿Cuánto cuestan 3?', [1600, 2400, 2800, 3200], 2400, '✏️'),
            omp('Tengo $5.000 y gasto $1.800. ¿Cuánto me queda?', [3000, 3200, 3800, 4200], 3200, '💵'),
            omp('Reparto 18 dulces entre 3 niños por igual. ¿Cuántos le tocan a cada uno?', [3, 5, 6, 9], 6, '🍬'),
        ]),

        est('Dos pasos', 'Aquí hay que hacer dos cuentas', '🔀', 'opcion_multiple', [
            omp('Compro 2 panes de $1.200 y pago con $5.000. ¿Cuánto me devuelven?', [1600, 2400, 2600, 3800], 2600, '🍞'),
            omp('Hay 3 cajas con 6 bolas y saco 4. ¿Cuántas quedan?', [12, 14, 16, 18], 14, '⚽'),
            omp('Leo 15 páginas el lunes y 12 el martes. Faltan 23. ¿Cuántas tiene el libro?', [40, 45, 50, 55], 50, '📖'),
            omp('Un bus lleva 24 pasajeros, bajan 9 y suben 5. ¿Cuántos van?', [18, 20, 22, 24], 20, '🚌'),
        ]),

        est('Desafío final', 'Los más difíciles', '🏆', 'desafio_final', [
            reto('Si una docena son 12, ¿cuántas son tres docenas?', [24, 36, 30], 36),
            reto('La mitad de 48 es…', [22, 24, 26], 24),
            reto('¿Cuánto es el doble de 25 más 10?', [50, 55, 60], 60),
            reto('Un cuadrado tiene 4 lados de 7 cm. ¿Cuánto mide su contorno?', [21, 28, 14], 28),
            reto('Si tres cuadernos cuestan $9.000, uno cuesta…', [2500, 3000, 4500], 3000),
        ]),
    ],
],

[
    'slug'  => 'reto-cientifico',
    'title' => 'Reto Científico',
    'description' => 'Un desafío que mezcla seres vivos, materia, clima y espacio.',
    'objective' => 'Integrar conocimientos de distintas áreas de ciencias en un mismo reto.',
    'icon' => '🔬', 'nivel' => 'primaria-media', 'bloque' => 'desafios-por-materia',
    'duracion' => 12, 'tags' => ['comprension', 'logica', 'reto'],
    'estaciones' => [

        est('Ronda de seres vivos', 'Plantas, animales y cuerpo', '🌱', 'opcion_multiple', [
            omp('¿Qué gas producen las plantas y respiramos?', ['Oxígeno', 'Nitrógeno', 'Humo'], 'Oxígeno', '🍃'),
            omp('Un animal que solo come plantas es…', ['Herbívoro', 'Carnívoro', 'Omnívoro'], 'Herbívoro', '🐄'),
            omp('¿Qué órgano bombea la sangre?', ['El corazón', 'El pulmón', 'El hígado'], 'El corazón', '🫀'),
            omp('¿De dónde sacan las plantas su energía?', ['Del sol', 'Del suelo solamente', 'Del viento'], 'Del sol', '☀️'),
            omp('Los hongos que descomponen restos son…', ['Descomponedores', 'Productores', 'Depredadores'], 'Descomponedores', '🍄'),
        ]),

        est('Ronda de materia y clima', 'Estados, fuerzas y agua', '⚗️', 'opcion_multiple', [
            omp('Pasar de líquido a gas se llama…', ['Evaporación', 'Fusión', 'Condensación'], 'Evaporación', '💨'),
            omp('La fuerza que nos mantiene en el suelo es…', ['La gravedad', 'La fricción', 'El magnetismo'], 'La gravedad', '🌍'),
            omp('¿Qué fase del ciclo del agua forma las nubes?', ['Condensación', 'Precipitación', 'Evaporación'], 'Condensación', '☁️'),
            omp('El hielo es agua en estado…', ['Sólido', 'Líquido', 'Gaseoso'], 'Sólido', '🧊'),
            omp('Un imán atrae objetos de…', ['Hierro', 'Plástico', 'Madera'], 'Hierro', '🧲'),
        ]),

        est('Desafío del científico', 'Todo junto', '🏆', 'desafio_final', [
            reto('¿Cuál es el planeta más grande del sistema solar?', ['Júpiter', 'Saturno', 'La Tierra'], 'Júpiter'),
            reto('¿Por qué tenemos día y noche?', ['La Tierra gira sobre sí misma', 'El sol se apaga', 'La Luna tapa el sol'], 'La Tierra gira sobre sí misma'),
            reto('Un experimento es confiable cuando…', ['Otros lo repiten y da lo mismo', 'Sale una sola vez', 'Lo dice un video'], 'Otros lo repiten y da lo mismo'),
            reto('Si desaparecen las plantas de un ecosistema…', ['Se rompe toda la cadena', 'Nada cambia', 'Hay más animales'], 'Se rompe toda la cadena'),
            reto('El órgano más grande del cuerpo humano es…', ['La piel', 'El hígado', 'El corazón'], 'La piel'),
        ]),
    ],
],

[
    'slug'  => 'reto-historico',
    'title' => 'Reto Histórico',
    'description' => 'Fechas, personajes y civilizaciones en un solo desafío.',
    'objective' => 'Integrar nociones de tiempo histórico, geografía y ciudadanía.',
    'icon' => '⏳', 'nivel' => 'primaria-media', 'bloque' => 'desafios-por-materia',
    'duracion' => 12, 'tags' => ['historia', 'memoria', 'reto'],
    'estaciones' => [

        est('Ronda de Colombia', 'Lo que pasó aquí', '🇨🇴', 'opcion_multiple', [
            omp('¿En qué año se declaró la independencia de Colombia?', [1810, 1819, 1886, 1991], 1810, '⚔️'),
            omp('Simón Bolívar es conocido como…', ['El Libertador', 'El Escritor', 'El Navegante'], 'El Libertador', '🐎'),
            omp('¿Cuál es la capital de Colombia?', ['Bogotá', 'Cali', 'Barranquilla'], 'Bogotá', '🏙️'),
            omp('¿Cuántas regiones naturales tiene Colombia?', [4, 5, 6, 7], 5, '🗺️'),
            omp('¿Quién gobierna un municipio?', ['El alcalde', 'El presidente', 'El gobernador'], 'El alcalde', '🏛️'),
        ]),

        est('Ronda del mundo', 'Civilizaciones y tiempo', '🌍', 'opcion_multiple', [
            omp('¿Cuántos años tiene un siglo?', [10, 50, 100, 1000], 100),
            omp('Las pirámides más famosas están en…', ['Egipto', 'Grecia', 'Japón'], 'Egipto', '🏜️'),
            omp('¿Qué civilización americana construyó Machu Picchu?', ['Los incas', 'Los mayas', 'Los aztecas'], 'Los incas', '🏔️'),
            omp('La imprenta sirvió para…', ['Reproducir libros en cantidad', 'Viajar', 'Cocinar'], 'Reproducir libros en cantidad', '🖨️'),
            omp('El año 2026 pertenece al siglo…', ['XXI', 'XX', 'XIX'], 'XXI', '📅'),
        ]),

        est('Ordena la historia', 'De lo más antiguo a lo más reciente', '📜', 'ordenar_secuencia', [
            'title' => 'Ordena estos hechos del más antiguo al más reciente',
            'items' => [
                '🏜️ Se construyen las pirámides de Egipto',
                '🏛️ Nace la democracia en Grecia',
                '⛵ Llegada de los europeos a América',
                '⚔️ Independencia de Colombia',
                '🚀 Llegada del hombre a la Luna',
                '💻 Se populariza el internet',
            ],
        ]),
    ],
],

[
    'slug'  => 'reto-de-ingles',
    'title' => 'Reto de Inglés',
    'description' => 'Vocabulario, frases y comprensión en un desafío contrarreloj.',
    'objective' => 'Consolidar el vocabulario y las estructuras básicas de inglés aprendidas.',
    'icon' => '🇬🇧', 'nivel' => 'primaria-media', 'bloque' => 'desafios-por-materia',
    'duracion' => 12, 'tags' => ['ingles', 'vocabulario', 'reto'],
    'estaciones' => [

        est('Vocabulary round', 'Del español al inglés, rápido', '🔤', 'opcion_multiple', [
            omp('«Perro» en inglés es…',   ['Dog', 'Cat', 'Duck'], 'Dog', '🐶'),
            omp('«Casa» en inglés es…',    ['House', 'Horse', 'Mouse'], 'House', '🏠'),
            omp('«Agua» en inglés es…',    ['Water', 'Winter', 'Waiter'], 'Water', '💧'),
            omp('«Rojo» en inglés es…',    ['Red', 'Green', 'Blue'], 'Red', '🔴'),
            omp('«Amigo» en inglés es…',   ['Friend', 'Family', 'Father'], 'Friend', '🧑‍🤝‍🧑'),
            omp('«Libro» en inglés es…',   ['Book', 'Box', 'Ball'], 'Book', '📕'),
        ], 'en-US'),

        est('Grammar round', 'Elige la forma correcta', '✅', 'opcion_multiple', [
            omp('I ___ happy.',        ['am', 'is', 'are'], 'am', '😀'),
            omp('She ___ a teacher.',  ['is', 'am', 'are'], 'is', '👩‍🏫'),
            omp('They ___ my friends.', ['are', 'is', 'am'], 'are', '👥'),
            omp('¿Cuál está bien escrita?', ['I have two cats', 'I has two cats', 'I having two cats'], 'I have two cats', '🐱'),
            omp('¿Cuál está bien escrita?', ['Where is my book?', 'Where my book is?', 'Is where my book?'], 'Where is my book?', '📕'),
        ], 'en-US'),

        est('Final challenge', 'El reto mayor', '🏆', 'desafio_final', [
            reto('«How much is it?» se usa para preguntar…', ['El precio', 'La hora', 'El nombre'], 'El precio'),
            reto('The opposite of «big» is…', ['Small', 'Tall', 'Long'], 'Small'),
            reto('«Good night» se dice…', ['Al ir a dormir', 'Al llegar en la mañana', 'Al comer'], 'Al ir a dormir'),
            reto('Choose the correct sentence:', ['She is my sister', 'She my sister is', 'Is she my sister'], 'She is my sister'),
            reto('«Thank you» se responde con…', ['You are welcome', 'Good morning', 'How are you'], 'You are welcome'),
        ], 'en-US'),
    ],
],

[
    'slug'  => 'reto-de-logica',
    'title' => 'Reto de Lógica',
    'description' => 'Acertijos, patrones y deducciones sin ninguna materia de por medio.',
    'objective' => 'Ejercitar razonamiento puro: patrones, deducción y pensamiento crítico.',
    'icon' => '🧠', 'nivel' => 'primaria-media', 'bloque' => 'desafios-por-materia',
    'duracion' => 15, 'tags' => ['logica', 'deduccion', 'patrones', 'reto'],
    'estaciones' => [

        est('Patrones', 'Encuentra la regla', '🔁', 'opcion_multiple', [
            omp('¿Qué número sigue?', [24, 26, 30, 32], 32, '2 · 4 · 8 · 16 · ❓', 'texto'),
            omp('¿Qué número sigue?', [20, 21, 25, 26], 25, '1 · 4 · 9 · 16 · ❓', 'texto'),
            omp('¿Qué número falta?',  [11, 12, 13, 14], 13, '1 · 3 · 5 · 7 · 9 · 11 · ❓', 'texto'),
            omp('¿Qué letra sigue?',  ['G', 'H', 'I', 'J'], 'I', 'A · C · E · G · ❓', 'texto'),
            omp('¿Qué número sigue?', [15, 18, 21, 25], 21, '1 · 3 · 6 · 10 · 15 · ❓', 'texto'),
        ]),

        est('Deducción', 'Solo hay una respuesta posible', '🕵️', 'opcion_multiple', [
            omp('Ana es más alta que Beto. Beto es más alto que Caro. ¿Quién es la más baja?', ['Caro', 'Ana', 'Beto'], 'Caro', '📏'),
            omp('Si todos los X son Y, y esto es un X, entonces…', ['Es un Y', 'No es un Y', 'No se sabe'], 'Es un Y', '🔷'),
            omp('En una caja hay solo bolas rojas. Saco una. Es…', ['Roja', 'Azul', 'No se sabe'], 'Roja', '🔴'),
            omp('Hoy es jueves. ¿Qué día será en 4 días?', ['Lunes', 'Domingo', 'Sábado'], 'Lunes', '📅'),
            omp('Si «no todos los pájaros vuelan» es cierto, entonces…', ['Al menos uno no vuela', 'Ninguno vuela', 'Todos vuelan'], 'Al menos uno no vuela', '🐦'),
        ]),

        est('Acertijos', 'Piensa dos veces', '🤔', 'opcion_multiple', [
            omp('Tengo 5 manzanas y me como 2. ¿Cuántas tengo?', [2, 3, 5, 7], 3, '🍎'),
            omp('Un ladrillo pesa 1 kg más medio ladrillo. ¿Cuánto pesa el ladrillo?', [1, 1.5, 2, 3], 2, '🧱'),
            omp('Si hay 6 hermanos y cada uno tiene una hermana, ¿cuántos niños hay en la familia?', [7, 12, 6, 8], 7, '👨‍👩‍👧‍👦'),
            omp('¿Cuántos meses tienen 28 días?', [1, 2, 11, 12], 12, '📅'),
        ]),

        est('Desafío maestro', 'Los cuatro más difíciles', '🏆', 'desafio_final', [
            reto('Un caracol sube 3 m de día y baja 2 de noche en un pozo de 5 m. ¿En cuántos días sale?',
                 [3, 4, 5], 3),
            reto('En una carrera adelantas al último. ¿Es posible?',
                 ['No, no se puede adelantar al último', 'Sí, quedas último', 'Sí, quedas primero'],
                 'No, no se puede adelantar al último'),
            reto('Si 5 gatos cazan 5 ratones en 5 minutos, ¿cuánto tardan 100 gatos en cazar 100 ratones?',
                 ['5 minutos', '100 minutos', '20 minutos'], '5 minutos'),
            reto('Dos padres y dos hijos van a pescar y traen 3 pescados, uno cada uno. ¿Cómo?',
                 ['Son abuelo, padre e hijo', 'Uno mintió', 'Un pescado se perdió'],
                 'Son abuelo, padre e hijo'),
        ]),
    ],
],


// ── JUEGOS DE MEMORIA ────────────────────────────────────────────────

[
    'slug'  => 'memoria-relampago',
    'title' => 'Memoria Relámpago',
    'description' => 'Tableros que crecen y series que hay que retener.',
    'objective' => 'Ampliar progresivamente la memoria de trabajo visual.',
    // ⚡ y no 🧠: el cerebro ya lo lleva «Desafío de Memoria», que está
    // en este mismo bloque. Además el relámpago está en el propio nombre.
    'icon' => '⚡', 'nivel' => 'todas-las-edades', 'bloque' => 'juegos-de-memoria',
    'duracion' => 12, 'tags' => ['memoria', 'atencion', 'juego'],
    'estaciones' => [

        est('Nivel 1 · Frutas', 'Cuatro parejas', '🍎', 'memoria',
            ['🍎', '🍌', '🍇', '🍓']),

        est('Nivel 2 · Transporte', 'Seis parejas', '🚗', 'memoria',
            ['🚗', '🚌', '✈️', '🚲', '🚂', '🚢']),

        est('Nivel 3 · Deportes', 'Ocho parejas', '⚽', 'memoria',
            ['⚽', '🏀', '🎾', '🏐', '🏈', '⚾', '🏓', '🥎']),

        est('Nivel 4 · Todo mezclado', 'Diez parejas: el reto mayor', '🏆', 'memoria',
            ['🍎', '🚗', '⚽', '🐶', '🌳', '⭐', '🎵', '📕', '🔑', '🎈']),
    ],
],

],
];
