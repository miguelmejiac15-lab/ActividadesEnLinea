<?php
/**
 * juegos-ampliacion.php — Juegos y Retos para los pequeños
 *
 * Esta categoría **no tenía ni una actividad de preescolar**. Y es la que
 * un docente de transición más necesita: el repaso en forma de juego es
 * lo único que un niño de cinco años acepta hacer por tercera vez.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  UN RETO PARA UN NIÑO DE CINCO AÑOS NO ES UNA PRUEBA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Las de primaria usan `desafio_final` y `contrarreloj`, que es lo
 * correcto a partir de los ocho: medirse tiene gracia cuando ya se
 * domina algo. Antes no: un contrarreloj a los cinco años solo enseña
 * que uno es lento.
 *
 * Por eso las de preescolar se apoyan en **memoria, emparejar y
 * seleccionar**, donde el niño gana por mirar bien y no por ir deprisa.
 * El único `juego_rapido` que aparece pide decidir sí o no sobre cosas
 * que ya conoce, no calcular.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'juegos',
    'name'       => 'Juegos y Retos',
    'tagline'    => 'Poner a prueba lo aprendido, mezclado y contrarreloj',
    'icon'       => '🎲',
    'color'      => '#d81b60',
    'sort_order' => 7,
],

'bloques' => [
    ['slug' => 'contrarreloj', 'name' => 'Contrarreloj', 'icon' => '⏱️', 'sort_order' => 1,
     'description' => 'Responder rápido y sin dudar. El reloj es parte del juego.'],
    ['slug' => 'desafios-por-materia', 'name' => 'Desafíos por Materia', 'icon' => '🏆', 'sort_order' => 2,
     'description' => 'Un reto final por cada materia, para medirse de verdad.'],
    ['slug' => 'juegos-de-memoria', 'name' => 'Juegos de Memoria', 'icon' => '🧠', 'sort_order' => 3,
     'description' => 'Tableros y series que ponen a trabajar la memoria.'],
    ['slug' => 'repaso-por-grado', 'name' => 'Repaso por Grado', 'icon' => '🎓', 'sort_order' => 4,
     'description' => 'Un reto mixto por cada grado de primaria: todas las materias en el mismo desafío.'],
    ['slug' => 'retos-relampago', 'name' => 'Retos Relámpago', 'icon' => '⚡', 'sort_order' => 5,
     'description' => 'Contrarreloj: responder rápido lo que ya se sabe de memoria.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR · ganar por mirar bien
// =====================================================================

[
    'slug'  => 'memoria-de-animales',
    'title' => 'Memoria de animales',
    'description' => 'Encuentra las parejas de animales y acuérdate de dónde estaba cada una.',
    'objective' => 'Ejercitar la memoria visual de trabajo con apoyos de imagen.',
    'icon' => '🐾', 'nivel' => 'preescolar', 'bloque' => 'juegos-de-memoria',
    'duracion' => 7, 'tags' => ['memoria', 'juego', 'observacion'],
    'estaciones' => [

        est('Animales de la casa', 'Encuentra las parejas', '🐶', 'memoria',
            ['🐶', '🐱', '🐰', '🐹', '🐦', '🐠']),

        est('Animales de la finca', 'Encuentra las parejas', '🐄', 'memoria',
            ['🐄', '🐷', '🐔', '🐑', '🐴', '🦆']),

        est('Animales de la selva', 'Encuentra las parejas', '🦁', 'memoria',
            ['🦁', '🐘', '🐒', '🦒', '🐯', '🦜']),

        est('¿Quién dice así?', 'Cada animal, su sonido', '🔊', 'opcion_multiple', [
            omp('¿Quién dice «guau»?',  ['El perro', 'El gato', 'La vaca'], 'El perro', '🐶'),
            omp('¿Quién dice «miau»?',  ['El gato', 'El pato', 'El caballo'], 'El gato', '🐱'),
            omp('¿Quién dice «muu»?',   ['La vaca', 'La oveja', 'La gallina'], 'La vaca', '🐄'),
            omp('¿Quién dice «pío»?',   ['El pollito', 'El cerdo', 'El burro'], 'El pollito', '🐤'),
            omp('¿Quién dice «cuac»?',  ['El pato', 'El perro', 'El león'], 'El pato', '🦆'),
        ]),
    ],
],

[
    'slug'  => 'memoria-de-colores-y-formas',
    'title' => 'Memoria de colores y formas',
    'description' => 'Parejas de colores, figuras y frutas para entrenar la mirada.',
    'objective' => 'Ejercitar memoria visual y reconocimiento de atributos básicos.',
    'icon' => '🔴', 'nivel' => 'preescolar', 'bloque' => 'juegos-de-memoria',
    'duracion' => 7, 'tags' => ['memoria', 'observacion', 'juego'],
    'estaciones' => [

        est('Colores', 'Encuentra las parejas', '🎨', 'memoria',
            ['🔴', '🔵', '🟡', '🟢', '🟣', '🟠']),

        est('Formas', 'Encuentra las parejas', '🔷', 'memoria',
            ['🔺', '🔷', '⭐', '❤️', '⬛', '⚪']),

        est('Frutas', 'Encuentra las parejas', '🍎', 'memoria',
            ['🍎', '🍌', '🍇', '🍊', '🍓', '🍉']),

        est('¿De qué color es?', 'Mira bien', '👀', 'opcion_multiple', [
            omp('¿De qué color es el banano?',  ['Amarillo', 'Azul', 'Morado'], 'Amarillo', '🍌'),
            omp('¿De qué color es el pasto?',   ['Verde', 'Rojo', 'Negro'], 'Verde', '🌱'),
            omp('¿De qué color es el cielo de día?', ['Azul', 'Café', 'Rosado'], 'Azul', '☁️'),
            omp('¿De qué color es el tomate?',  ['Rojo', 'Verde', 'Blanco'], 'Rojo', '🍅'),
            omp('¿De qué color es la nieve?',   ['Blanco', 'Negro', 'Naranja'], 'Blanco', '❄️'),
        ]),
    ],
],

[
    'slug'  => 'encuentra-lo-que-pido',
    'title' => 'Encuentra lo que pido',
    'description' => 'Un montón de dibujos y una sola instrucción: toca los que cumplen.',
    'objective' => 'Sostener una consigna mientras se exploran varios estímulos visuales.',
    'icon' => '🔍', 'nivel' => 'preescolar', 'bloque' => 'contrarreloj',
    'duracion' => 8, 'tags' => ['atencion', 'observacion', 'juego', 'foco'],
    'estaciones' => [

        est('Todo lo que se come', 'Solo la comida', '🍽️', 'seleccion_imagenes',
            conTitulo('Toca todo lo que se come', 'Lo demás, no', [
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '👟', 'n' => 'Zapato',  'ok' => false],
                ['e' => '🍞', 'n' => 'Pan',     'ok' => true],
                ['e' => '📱', 'n' => 'Celular', 'ok' => false],
                ['e' => '🧀', 'n' => 'Queso',   'ok' => true],
                ['e' => '🪑', 'n' => 'Silla',   'ok' => false],
                ['e' => '🍌', 'n' => 'Banano',  'ok' => true],
                ['e' => '🔑', 'n' => 'Llave',   'ok' => false],
            ])),

        est('Todo lo que vuela', 'Por el aire', '🕊️', 'seleccion_imagenes',
            conTitulo('Toca todo lo que vuela', 'Mira bien', [
                ['e' => '🦅', 'n' => 'Águila',   'ok' => true],
                ['e' => '🐟', 'n' => 'Pez',      'ok' => false],
                ['e' => '✈️', 'n' => 'Avión',    'ok' => true],
                ['e' => '🐘', 'n' => 'Elefante', 'ok' => false],
                ['e' => '🦋', 'n' => 'Mariposa', 'ok' => true],
                ['e' => '🚗', 'n' => 'Carro',    'ok' => false],
                ['e' => '🎈', 'n' => 'Globo',    'ok' => true],
                ['e' => '🐢', 'n' => 'Tortuga',  'ok' => false],
            ])),

        est('Todo lo que es redondo', 'Sin esquinas', '⚪', 'seleccion_imagenes',
            conTitulo('Toca todo lo que es redondo', 'Nada de esquinas', [
                ['e' => '⚽', 'n' => 'Balón',   'ok' => true],
                ['e' => '📕', 'n' => 'Libro',   'ok' => false],
                ['e' => '🍊', 'n' => 'Naranja', 'ok' => true],
                ['e' => '📺', 'n' => 'Televisor','ok' => false],
                ['e' => '🪙', 'n' => 'Moneda',  'ok' => true],
                ['e' => '🚪', 'n' => 'Puerta',  'ok' => false],
                ['e' => '🕐', 'n' => 'Reloj',   'ok' => true],
                ['e' => '📏', 'n' => 'Regla',   'ok' => false],
            ])),

        est('Todo lo que hace ruido', 'Suena', '🔊', 'seleccion_imagenes',
            conTitulo('Toca todo lo que hace ruido', 'Piensa si suena', [
                ['e' => '🔔', 'n' => 'Campana', 'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',  'ok' => false],
                ['e' => '🥁', 'n' => 'Tambor',  'ok' => true],
                ['e' => '🧦', 'n' => 'Media',   'ok' => false],
                ['e' => '📣', 'n' => 'Megáfono','ok' => true],
                ['e' => '🍃', 'n' => 'Hoja',    'ok' => false],
                ['e' => '🎺', 'n' => 'Trompeta','ok' => true],
                ['e' => '☁️', 'n' => 'Nube',    'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'rapido-si-o-no',
    'title' => 'Rápido: ¿sí o no?',
    'description' => 'Decide sin pensarlo mucho. Cosas que ya sabes.',
    'objective' => 'Responder con rapidez sobre conocimientos ya consolidados del entorno.',
    'icon' => '🏁', 'nivel' => 'preescolar', 'bloque' => 'retos-relampago',
    'duracion' => 7, 'tags' => ['juego', 'atencion', 'observacion'],
    'estaciones' => [

        est('¿Es un animal?', 'Rápido', '🐾', 'juego_rapido',
            conTitulo('¿Es un animal?', 'Decide rápido', [
                ['e' => '🐶', 'n' => 'Perro',   'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',   'ok' => false],
                ['e' => '🐝', 'n' => 'Abeja',   'ok' => true],
                ['e' => '🚗', 'n' => 'Carro',   'ok' => false],
                ['e' => '🐢', 'n' => 'Tortuga', 'ok' => true],
                ['e' => '🌸', 'n' => 'Flor',    'ok' => false],
                ['e' => '🦜', 'n' => 'Loro',    'ok' => true],
                ['e' => '🏠', 'n' => 'Casa',    'ok' => false],
            ])),

        est('¿Se come?', 'Rápido', '🍽️', 'juego_rapido',
            conTitulo('¿Se come?', 'Decide rápido', [
                ['e' => '🍕', 'n' => 'Pizza',   'ok' => true],
                ['e' => '✏️', 'n' => 'Lápiz',   'ok' => false],
                ['e' => '🥕', 'n' => 'Zanahoria','ok' => true],
                ['e' => '🧸', 'n' => 'Peluche', 'ok' => false],
                ['e' => '🍪', 'n' => 'Galleta', 'ok' => true],
                ['e' => '🪑', 'n' => 'Silla',   'ok' => false],
                ['e' => '🥛', 'n' => 'Leche',   'ok' => true],
                ['e' => '📖', 'n' => 'Libro',   'ok' => false],
            ])),

        est('¿Es grande?', 'Comparado con un niño', '📏', 'juego_rapido',
            conTitulo('¿Es más grande que tú?', 'Decide rápido', [
                ['e' => '🐘', 'n' => 'Elefante', 'ok' => true],
                ['e' => '🐜', 'n' => 'Hormiga',  'ok' => false],
                ['e' => '🚌', 'n' => 'Bus',      'ok' => true],
                ['e' => '🪙', 'n' => 'Moneda',   'ok' => false],
                ['e' => '🏠', 'n' => 'Casa',     'ok' => true],
                ['e' => '🔑', 'n' => 'Llave',    'ok' => false],
                ['e' => '🌳', 'n' => 'Árbol',    'ok' => true],
                ['e' => '🍓', 'n' => 'Fresa',    'ok' => false],
            ])),

        est('¿Es de día?', 'Cosas del día y de la noche', '☀️', 'juego_rapido',
            conTitulo('¿Se ve de DÍA?', 'Decide rápido', [
                ['e' => '☀️', 'n' => 'El Sol',     'ok' => true],
                ['e' => '🌙', 'n' => 'La Luna',    'ok' => false],
                ['e' => '🌈', 'n' => 'Arcoíris',   'ok' => true],
                ['e' => '⭐', 'n' => 'Las estrellas','ok' => false],
                ['e' => '🦋', 'n' => 'Mariposa',   'ok' => true],
                ['e' => '🦉', 'n' => 'Búho',       'ok' => false],
                ['e' => '🌻', 'n' => 'Girasol',    'ok' => true],
                ['e' => '🦇', 'n' => 'Murciélago', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'reto-de-los-numeros-pequenos',
    'title' => 'Reto de los números pequeños',
    'description' => 'Contar hasta diez, comparar y ver cuál falta.',
    'objective' => 'Repasar conteo, comparación y serie numérica hasta diez.',
    'icon' => '🧮', 'nivel' => 'preescolar', 'bloque' => 'desafios-por-materia',
    'duracion' => 8, 'tags' => ['calculo', 'juego', 'logica'],
    'estaciones' => [

        est('¿Cuántos hay?', 'Cuenta despacio', '🔢', 'opcion_multiple', [
            omp('🍎🍎🍎 ¿cuántas manzanas?',      ['3', '2', '4'], '3'),
            omp('⭐⭐⭐⭐⭐ ¿cuántas estrellas?',  ['5', '4', '6'], '5'),
            omp('🐶🐶 ¿cuántos perros?',          ['2', '3', '1'], '2'),
            omp('🚗🚗🚗🚗 ¿cuántos carros?',      ['4', '3', '5'], '4'),
            omp('🌸 ¿cuántas flores?',            ['1', '2', '0'], '1'),
        ]),

        est('¿Cuál falta?', 'Los números van en orden', '❓', 'secuencia_numerica', [
            serie([1, 2, null, 4, 5]),
            serie([3, 4, 5, null, 7]),
            serie([6, 7, 8, null, 10]),
            serie([2, 3, 4, null, 6]),
            serie([5, 6, 7, null, 9]),
        ]),

        est('¿Más o menos?', 'Compara las cantidades', '⚖️', 'opcion_multiple', [
            omp('¿Qué es más: 3 o 5?',   ['5', '3', 'Iguales'], '5'),
            omp('¿Qué es menos: 2 o 7?', ['2', '7', 'Iguales'], '2'),
            omp('¿Qué es más: 9 o 4?',   ['9', '4', 'Iguales'], '9'),
            omp('¿Qué es menos: 8 o 1?', ['1', '8', 'Iguales'], '1'),
            omp('¿6 y 6 son…?',          ['Iguales', '6 es más', '6 es menos'], 'Iguales'),
        ]),

        est('Ordena los números', 'Del más pequeño al más grande', '📶', 'ordenar_secuencia', [
            'title' => 'Ordena del más pequeño al más grande',
            'items' => ['1', '3', '5', '7', '9'],
        ]),
    ],
],

[
    'slug'  => 'reto-de-las-primeras-letras',
    'title' => 'Reto de las primeras letras',
    'description' => 'Las vocales, los sonidos y con qué letra empieza cada palabra.',
    'objective' => 'Repasar reconocimiento de vocales y sonido inicial de palabras.',
    'icon' => '🔤', 'nivel' => 'preescolar', 'bloque' => 'desafios-por-materia',
    'duracion' => 8, 'tags' => ['lectura', 'juego', 'vocabulario'],
    'estaciones' => [

        est('¿Con qué empieza?', 'El primer sonido', '🔤', 'opcion_multiple', [
            omp('🍎 Manzana empieza con…',  ['M', 'A', 'S'], 'M'),
            omp('☀️ Sol empieza con…',      ['S', 'O', 'L'], 'S'),
            omp('🐘 Elefante empieza con…', ['E', 'L', 'T'], 'E'),
            omp('🏠 Casa empieza con…',     ['C', 'A', 'S'], 'C'),
            omp('🐟 Pez empieza con…',      ['P', 'E', 'Z'], 'P'),
        ]),

        est('Las cinco vocales', 'A, E, I, O, U', '🅰️', 'opcion_multiple', [
            omp('¿Cuántas vocales hay?',           ['5', '3', '10'], '5'),
            omp('¿Cuál NO es vocal?',              ['M', 'A', 'O'], 'M'),
            omp('Después de la A viene la…',       ['E', 'I', 'U'], 'E'),
            omp('La última vocal es la…',          ['U', 'A', 'O'], 'U'),
            omp('🐘 Elefante empieza con la vocal…', ['E', 'A', 'I'], 'E'),
        ]),

        est('Memoria de vocales', 'Encuentra las parejas', '🧠', 'memoria',
            ['🅰️', '🇪', '🇮', '🅾️', '🇺', '🔤']),

        est('Ordena las vocales', 'Como en la canción', '🎵', 'ordenar_secuencia', [
            'title' => 'Ordena las vocales',
            'items' => ['A', 'E', 'I', 'O', 'U'],
        ]),
    ],
],

[
    'slug'  => 'reto-del-cuerpo-y-la-casa',
    'title' => 'Reto del cuerpo y la casa',
    'description' => 'Las partes del cuerpo, los cuartos de la casa y para qué sirve cada uno.',
    'objective' => 'Repasar vocabulario del cuerpo y del entorno doméstico.',
    'icon' => '🏠', 'nivel' => 'preescolar', 'bloque' => 'desafios-por-materia',
    'duracion' => 8, 'tags' => ['cuerpo', 'vocabulario', 'juego'],
    'estaciones' => [

        est('¿Con qué parte?', 'Cada parte hace algo', '🧍', 'opcion_multiple', [
            omp('¿Con qué veo?',      ['Los ojos', 'Las manos', 'Los pies'], 'Los ojos', '👀'),
            omp('¿Con qué oigo?',     ['Los oídos', 'La nariz', 'La boca'], 'Los oídos', '👂'),
            omp('¿Con qué camino?',   ['Los pies', 'Las manos', 'Los ojos'], 'Los pies', '🦶'),
            omp('¿Con qué agarro?',   ['Las manos', 'Los pies', 'La nariz'], 'Las manos', '✋'),
            omp('¿Con qué huelo?',    ['La nariz', 'La boca', 'Las manos'], 'La nariz', '👃'),
        ]),

        est('Cada cuarto, su cosa', 'Une lo que va junto', '🔗', 'emparejar', [
            ['e' => '🛏️', 'w' => 'Cuarto'],
            ['e' => '🍳', 'w' => 'Cocina'],
            ['e' => '🛁', 'w' => 'Baño'],
            ['e' => '🛋️', 'w' => 'Sala'],
            ['e' => '🚪', 'w' => 'Entrada'],
            ['e' => '🌳', 'w' => 'Patio'],
        ]),

        est('¿Dónde lo hago?', 'Cada cosa en su lugar', '📍', 'opcion_multiple', [
            omp('¿Dónde duermo?',        ['En el cuarto', 'En la cocina', 'En el baño'], 'En el cuarto', '🛏️'),
            omp('¿Dónde me baño?',       ['En el baño', 'En la sala', 'En el patio'], 'En el baño', '🛁'),
            omp('¿Dónde se cocina?',     ['En la cocina', 'En el cuarto', 'En el baño'], 'En la cocina', '🍳'),
            omp('¿Dónde veo televisión?', ['En la sala', 'En el baño', 'En la nevera'], 'En la sala', '📺'),
            omp('¿Dónde me lavo las manos?', ['En el lavamanos', 'En la cama', 'En la silla'], 'En el lavamanos', '🚰'),
        ]),

        est('Memoria de la casa', 'Encuentra las parejas', '🧠', 'memoria',
            ['🛏️', '🍳', '🛁', '🛋️', '🚪', '🪟']),
    ],
],

[
    'slug'  => 'reto-de-los-opuestos',
    'title' => 'Reto de los opuestos',
    'description' => 'Grande y pequeño, arriba y abajo, dentro y fuera.',
    'objective' => 'Reconocer pares de conceptos opuestos del entorno inmediato.',
    'icon' => '↔️', 'nivel' => 'preescolar', 'bloque' => 'desafios-por-materia',
    'duracion' => 8, 'tags' => ['vocabulario', 'logica', 'juego'],
    'estaciones' => [

        est('¿Cuál es lo contrario?', 'Piensa en el opuesto', '↔️', 'opcion_multiple', [
            omp('Lo contrario de grande es…',  ['Pequeño', 'Alto', 'Gordo'], 'Pequeño'),
            omp('Lo contrario de arriba es…',  ['Abajo', 'Al lado', 'Dentro'], 'Abajo'),
            omp('Lo contrario de día es…',     ['Noche', 'Tarde', 'Sol'], 'Noche'),
            omp('Lo contrario de frío es…',    ['Caliente', 'Fresco', 'Mojado'], 'Caliente'),
            omp('Lo contrario de lleno es…',   ['Vacío', 'Pesado', 'Nuevo'], 'Vacío'),
        ]),

        est('Une los opuestos', 'De dos en dos', '🔗', 'emparejar', [
            ['e' => '☀️', 'w' => 'Noche'],
            ['e' => '🔥', 'w' => 'Frío'],
            ['e' => '⬆️', 'w' => 'Abajo'],
            ['e' => '😊', 'w' => 'Triste'],
            ['e' => '🐘', 'w' => 'Pequeño'],
            ['e' => '🚀', 'w' => 'Lento'],
        ]),

        est('¿Es lo contrario?', 'Rápido', '⚡', 'juego_rapido',
            conTitulo('¿Son OPUESTOS?', 'Decide rápido', [
                ['e' => '⬆️', 'n' => 'Arriba y abajo',   'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana y fruta',  'ok' => false],
                ['e' => '🌞', 'n' => 'Día y noche',      'ok' => true],
                ['e' => '🐶', 'n' => 'Perro y gato',     'ok' => false],
                ['e' => '🔓', 'n' => 'Abierto y cerrado','ok' => true],
                ['e' => '🚗', 'n' => 'Carro y bus',      'ok' => false],
                ['e' => '🧊', 'n' => 'Frío y caliente',  'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol y planta',   'ok' => false],
            ])),

        est('¿Dónde está?', 'Dentro, fuera, encima, debajo', '📍', 'opcion_multiple', [
            omp('El pez está… del agua',      ['Dentro', 'Fuera', 'Encima'], 'Dentro', '🐠'),
            omp('El pájaro está… del árbol',  ['Encima', 'Debajo', 'Dentro'], 'Encima', '🐦'),
            omp('La raíz está… de la tierra', ['Debajo', 'Encima', 'Al lado'], 'Debajo', '🌱'),
            omp('El gato está… de la caja si se ve entero', ['Fuera', 'Dentro', 'Debajo'], 'Fuera', '🐱'),
            omp('El libro está… de la mesa',  ['Encima', 'Dentro', 'Debajo'], 'Encima', '📚'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA INICIAL · medirse empieza a tener gracia
// =====================================================================

[
    'slug'  => 'relampago-de-sumas',
    'title' => 'Relámpago de sumas',
    'description' => 'Sumas hasta veinte, rápido y sin contar con los dedos.',
    'objective' => 'Automatizar sumas básicas hasta 20.',
    'icon' => '➕', 'nivel' => 'primaria-inicial', 'bloque' => 'retos-relampago',
    'duracion' => 9, 'tags' => ['calculo', 'juego', 'reto'],
    'estaciones' => [

        est('Sumar hasta diez', 'Rápido', '➕', 'opcion_multiple', [
            omp('3 + 4 =', ['7', '6', '8'], '7'),
            omp('5 + 2 =', ['7', '8', '6'], '7'),
            omp('6 + 3 =', ['9', '8', '10'], '9'),
            omp('4 + 4 =', ['8', '7', '9'], '8'),
            omp('2 + 7 =', ['9', '8', '10'], '9'),
        ]),

        est('Pasar de diez', 'Con llevada', '🔟', 'opcion_multiple', [
            omp('8 + 5 =',  ['13', '12', '14'], '13'),
            omp('7 + 6 =',  ['13', '12', '14'], '13'),
            omp('9 + 4 =',  ['13', '12', '14'], '13'),
            omp('8 + 7 =',  ['15', '14', '16'], '15'),
            omp('9 + 9 =',  ['18', '17', '19'], '18'),
        ]),

        est('¿Cuánto falta?', 'Para llegar a diez', '🎯', 'opcion_multiple', [
            omp('7 + ? = 10', ['3', '2', '4'], '3'),
            omp('4 + ? = 10', ['6', '5', '7'], '6'),
            omp('8 + ? = 10', ['2', '3', '1'], '2'),
            omp('5 + ? = 10', ['5', '4', '6'], '5'),
            omp('1 + ? = 10', ['9', '8', '10'], '9'),
        ]),

        est('Reto de sumas', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('6 + 6 =',   ['12', '11', '13'], '12'),
            reto('10 + 7 =',  ['17', '16', '18'], '17'),
            reto('9 + 3 =',   ['12', '11', '13'], '12'),
            reto('Tenía 8 y me dieron 5. Tengo…', ['13', '12', '3'], '13'),
            reto('¿Cuánto le falta a 6 para 10?', ['4', '3', '5'], '4'),
        ]),
    ],
],

[
    'slug'  => 'relampago-de-restas',
    'title' => 'Relámpago de restas',
    'description' => 'Restas hasta veinte, y saber cuánto falta.',
    'objective' => 'Automatizar restas básicas hasta 20.',
    'icon' => '➖', 'nivel' => 'primaria-inicial', 'bloque' => 'retos-relampago',
    'duracion' => 9, 'tags' => ['calculo', 'juego', 'reto'],
    'estaciones' => [

        est('Restar hasta diez', 'Rápido', '➖', 'opcion_multiple', [
            omp('9 − 4 =', ['5', '4', '6'], '5'),
            omp('7 − 3 =', ['4', '3', '5'], '4'),
            omp('8 − 5 =', ['3', '2', '4'], '3'),
            omp('6 − 6 =', ['0', '1', '6'], '0'),
            omp('10 − 7 =',['3', '2', '4'], '3'),
        ]),

        est('Pasar de diez', 'Con préstamo', '🔟', 'opcion_multiple', [
            omp('13 − 5 =', ['8', '7', '9'], '8'),
            omp('15 − 7 =', ['8', '7', '9'], '8'),
            omp('12 − 8 =', ['4', '3', '5'], '4'),
            omp('16 − 9 =', ['7', '6', '8'], '7'),
            omp('20 − 6 =', ['14', '13', '15'], '14'),
        ]),

        est('¿Cuánto más tiene?', 'Restar para comparar', '⚖️', 'opcion_multiple', [
            omp('Ana tiene 9 y Luis 4. Ana tiene… más',  ['5', '4', '13'], '5'),
            omp('Hay 12 y me llevo 5. Quedan…',          ['7', '6', '8'], '7'),
            omp('Tenía 15 y gasté 8. Me quedan…',        ['7', '8', '6'], '7'),
            omp('¿Cuánto le falta a 11 para 20?',        ['9', '8', '10'], '9'),
            omp('20 − 20 =',                              ['0', '20', '1'], '0'),
        ]),

        est('Reto de restas', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('14 − 6 =',  ['8', '7', '9'], '8'),
            reto('11 − 3 =',  ['8', '7', '9'], '8'),
            reto('18 − 9 =',  ['9', '8', '10'], '9'),
            reto('Tenía 20 y perdí 12. Me quedan…', ['8', '7', '9'], '8'),
            reto('Si 7 + 5 = 12, entonces 12 − 5 =', ['7', '5', '12'], '7'),
        ]),
    ],
],

[
    'slug'  => 'memoria-de-primero',
    'title' => 'Memoria de primero',
    'description' => 'Tableros de memoria con lo que se ve en primero: letras, números y animales.',
    'objective' => 'Ejercitar memoria visual con contenidos del grado.',
    'icon' => '🃏', 'nivel' => 'primaria-inicial', 'bloque' => 'juegos-de-memoria',
    'duracion' => 8, 'tags' => ['memoria', 'juego', 'atencion'],
    'estaciones' => [

        est('Números', 'Encuentra las parejas', '🔢', 'memoria',
            ['1️⃣', '2️⃣', '3️⃣', '4️⃣', '5️⃣', '6️⃣']),

        est('Transportes', 'Encuentra las parejas', '🚗', 'memoria',
            ['🚗', '🚌', '✈️', '🚲', '🚂', '⛵']),

        est('Naturaleza', 'Encuentra las parejas', '🌳', 'memoria',
            ['🌳', '🌸', '🍄', '🌵', '🍃', '🌻']),

        est('¿Cuál vi antes?', 'Recuerda lo que salió', '👁️', 'opcion_multiple', [
            omp('En el tablero de transportes, ¿había un avión?', ['Sí', 'No'], 'Sí'),
            omp('¿Había un barco?',                               ['Sí', 'No'], 'Sí'),
            omp('¿Había un helicóptero?',                         ['No', 'Sí'], 'No'),
            omp('En el de naturaleza, ¿había un cactus?',         ['Sí', 'No'], 'Sí'),
            omp('¿Había un pingüino?',                            ['No', 'Sí'], 'No'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR · el repaso que cierra el ciclo
// =====================================================================

[
    'slug'  => 'repaso-de-sexto',
    'title' => 'Repaso de sexto',
    'description' => 'Todo lo del último grado mezclado: el reto que cierra la primaria.',
    'objective' => 'Integrar contenidos de todas las áreas del último grado de primaria.',
    'icon' => '🎓', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 16, 'tags' => ['reto', 'juego', 'logica'],
    'estaciones' => [

        est('Matemáticas', 'Lo esencial del grado', '🔢', 'opcion_multiple', [
            omp('¿Cuánto es 3/4 de 20?',                 ['15', '16', '12'], '15'),
            omp('0,25 es lo mismo que…',                 ['1/4', '1/2', '2/5'], '1/4'),
            omp('El área de un rectángulo de 6 × 4 es…', ['24', '20', '10'], '24'),
            omp('El 10 % de 350 es…',                    ['35', '3,5', '350'], '35'),
            omp('Si un ángulo mide 90°, es…',            ['Recto', 'Agudo', 'Obtuso'], 'Recto'),
        ]),

        est('Lengua y ciencias', 'Dos materias, cinco preguntas', '🔬', 'opcion_multiple', [
            omp('El sujeto de «Los niños corren» es…',   ['Los niños', 'Corren', 'Los'], 'Los niños'),
            omp('Una fábula siempre deja…',              ['Una moraleja', 'Un misterio', 'Un poema'], 'Una moraleja'),
            omp('La fotosíntesis la hacen…',             ['Las plantas', 'Los animales', 'Las rocas'], 'Las plantas'),
            omp('El agua hierve, a nivel del mar, a…',   ['100 °C', '50 °C', '0 °C'], '100 °C'),
            omp('El aparato que bombea sangre es…',      ['El corazón', 'El pulmón', 'El hígado'], 'El corazón'),
        ]),

        est('Sociales y ciudadanía', 'El país y la vida en común', '🌎', 'opcion_multiple', [
            omp('Colombia tiene… regiones naturales',    ['Seis', 'Tres', 'Diez'], 'Seis'),
            omp('La Constitución de Colombia es de…',    ['1991', '1810', '2000'], '1991'),
            omp('Un derecho viene siempre con…',         ['Un deber', 'Un premio', 'Un castigo'], 'Un deber'),
            omp('El voto en democracia es…',             ['Una forma de decidir entre todos', 'Un impuesto', 'Un premio'], 'Una forma de decidir entre todos'),
            omp('La línea imaginaria que divide la Tierra en dos es…', ['El ecuador', 'El meridiano cero', 'El trópico'], 'El ecuador'),
        ]),

        est('Reto final de primaria', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('1/2 + 1/4 =',                     ['3/4', '2/6', '1/6'], '3/4'),
            reto('«Rápidamente» es un…',            ['Adverbio', 'Sustantivo', 'Verbo'], 'Adverbio'),
            reto('La energía del Sol llega como…',  ['Luz y calor', 'Sonido', 'Viento'], 'Luz y calor'),
            reto('Un ecosistema se rompe cuando…',  ['Desaparece una especie clave', 'Llueve', 'Amanece'], 'Desaparece una especie clave'),
            reto('Comprobar una noticia en otra fuente es…', ['Lo correcto', 'Perder el tiempo', 'Desconfiado'], 'Lo correcto'),
        ]),
    ],
],

],
];
