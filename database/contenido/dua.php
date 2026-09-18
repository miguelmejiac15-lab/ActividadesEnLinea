<?php
/**
 * dua.php — Aprender sin Barreras
 *
 * Diseño Universal para el Aprendizaje: no es una categoría «para niños
 * con discapacidad», es contenido pensado para que la vía de entrada no
 * sea siempre la misma. Un niño que aún no lee puede entrar por imagen;
 * uno que no oye bien, por contraste visual; uno que se desorganiza, por
 * una rutina anticipada. Todos ganan.
 *
 * De ahí los tres bloques: apoyo visual, apoyo auditivo y paso a paso.
 *
 * Restricciones de escritura que se respetan aquí:
 *   · enunciados cortos y de una sola idea;
 *   · nunca se pide leer para poder responder cuando se puede evitar;
 *   · las opciones son pocas —dos o tres— y muy distintas entre sí;
 *   · el contenido no describe ni evalúa ninguna condición del niño.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'dua',
    'name'       => 'Aprender sin Barreras',
    'tagline'    => 'Varias formas de entrar al mismo aprendizaje',
    'icon'       => '♿',
    'color'      => '#4dd0e1',
    'sort_order' => 8,
],

'bloques' => [
    ['slug' => 'apoyo-visual', 'name' => 'Apoyo Visual', 'icon' => '👁️', 'sort_order' => 1,
     'description' => 'Contrastes, siluetas y figuras grandes y claras.'],
    ['slug' => 'apoyo-auditivo', 'name' => 'Apoyo Auditivo', 'icon' => '👂', 'sort_order' => 2,
     'description' => 'Distinguir sonidos, sílabas y señas.'],
    ['slug' => 'paso-a-paso', 'name' => 'Paso a Paso', 'icon' => '🪜', 'sort_order' => 3,
     'description' => 'Rutinas anticipadas con imágenes, una cosa a la vez.'],
],

'reasignar' => [
    'mundo-de-contrastes' => 'apoyo-visual',
    'sonidos-y-senas'     => 'apoyo-auditivo',
    'ritmo-de-silabas'    => 'apoyo-auditivo',
    'camino-paso-a-paso'  => 'paso-a-paso',
],

'actividades' => [


// ── APOYO VISUAL ─────────────────────────────────────────────────────

[
    'slug'  => 'siluetas-y-contornos',
    'title' => 'Siluetas y contornos',
    'description' => 'Reconocer las cosas solo por su forma, sin colores ni detalles.',
    'objective' => 'Discriminar objetos por su contorno, apoyando la percepción visual de la forma.',
    'icon' => '👁️', 'nivel' => 'preescolar', 'bloque' => 'apoyo-visual',
    'duracion' => 10, 'tags' => ['observacion', 'atencion', 'clasificacion'],
    'estaciones' => [

        est('¿Qué es?', 'Solo por la forma', '⬛', 'opcion_multiple', [
            omp('¿Qué es?', ['Un árbol', 'Un carro'], 'Un árbol', '🌳'),
            omp('¿Qué es?', ['Una casa', 'Un pez'], 'Una casa', '🏠'),
            omp('¿Qué es?', ['Un pez', 'Una silla'], 'Un pez', '🐟'),
            omp('¿Qué es?', ['Una estrella', 'Un carro'], 'Una estrella', '⭐'),
            omp('¿Qué es?', ['Una taza', 'Un árbol'], 'Una taza', '☕'),
        ]),

        est('Grande o pequeño', 'Compara los dos', '📏', 'opcion_multiple', [
            omp('¿Cuál es más grande?', ['🐘', '🐜'], '🐘'),
            omp('¿Cuál es más grande?', ['🏠', '🔑'], '🏠'),
            omp('¿Cuál es más grande?', ['🐋', '🐟'], '🐋'),
            omp('¿Cuál es más pequeño?', ['🐭', '🐴'], '🐭'),
            omp('¿Cuál es más pequeño?', ['🍒', '🍉'], '🍒'),
        ]),

        est('Encuentra el igual', 'Uno es distinto', '🔍', 'opcion_multiple', [
            ompi('¿Cuál es diferente?', ['⭐', '⭐', '🌙'], 2),
            ompi('¿Cuál es diferente?', ['🔵', '🔴', '🔵'], 1),
            ompi('¿Cuál es diferente?', ['🐶', '🐶', '🐱'], 2),
            ompi('¿Cuál es diferente?', ['🟩', '🟨', '🟩'], 1),
        ]),

        est('Parejas de formas', 'Encuentra las iguales', '🧠', 'memoria',
            ['⬛', '⭕', '🔺', '⭐']),
    ],
],

[
    'slug'  => 'grande-y-claro',
    'title' => 'Grande y claro',
    'description' => 'Contraste, tamaño y posición: mirar sin cansarse.',
    'objective' => 'Ejercitar la discriminación visual con alto contraste y estímulos amplios.',
    'icon' => '🔆', 'nivel' => 'primaria-inicial', 'bloque' => 'apoyo-visual',
    'duracion' => 10, 'tags' => ['observacion', 'atencion', 'logica'],
    'estaciones' => [

        est('Claro y oscuro', '¿Cuál se ve mejor?', '🔆', 'opcion_multiple', [
            omp('¿Este color es claro u oscuro?', ['Claro', 'Oscuro'], 'Claro', '#fff59d', 'color'),
            omp('¿Este color es claro u oscuro?', ['Oscuro', 'Claro'], 'Oscuro', '#1a237e', 'color'),
            omp('¿Este color es claro u oscuro?', ['Claro', 'Oscuro'], 'Claro', '#ffffff', 'color'),
            omp('¿Este color es claro u oscuro?', ['Oscuro', 'Claro'], 'Oscuro', '#212121', 'color'),
            omp('Para leer mejor conviene…', ['Letra oscura sobre fondo claro', 'Letra clara sobre fondo claro'], 'Letra oscura sobre fondo claro', '📖'),
        ]),

        est('¿Dónde está?', 'Arriba, abajo, izquierda, derecha', '🧭', 'opcion_multiple', [
            omp('El sol está…',      ['Arriba', 'Abajo'], 'Arriba', '☀️'),
            omp('Las raíces están…', ['Abajo', 'Arriba'], 'Abajo', '🌱'),
            omp('El techo está…',    ['Arriba', 'Abajo'], 'Arriba', '🏠'),
            omp('Los zapatos van…',  ['Abajo', 'Arriba'], 'Abajo', '👟'),
            omp('El sombrero va…',   ['Arriba', 'Abajo'], 'Arriba', '🎩'),
        ]),

        est('Cuenta lo que ves', 'Mira con calma', '🔢', 'opcion_multiple', [
            omp('¿Cuántas estrellas hay?',   [2, 3, 4], 3, '⭐ ⭐ ⭐', 'texto'),
            omp('¿Cuántos círculos hay?',    [3, 4, 5], 4, '🔵 🔵 🔵 🔵', 'texto'),
            omp('¿Cuántos corazones hay?',   [1, 2, 3], 2, '❤️ ❤️', 'texto'),
            omp('¿Cuántos cuadrados hay?',   [4, 5, 6], 5, '⬛ ⬛ ⬛ ⬛ ⬛', 'texto'),
        ]),
    ],
],


// ── APOYO AUDITIVO ───────────────────────────────────────────────────

[
    'slug'  => 'escucha-y-distingue',
    'title' => 'Escucha y distingue',
    'description' => 'Sonidos parecidos que no son iguales. Afina el oído.',
    'objective' => 'Discriminar sonidos y fonemas semejantes, base de la lectura y del habla clara.',
    'icon' => '👂', 'nivel' => 'primaria-inicial', 'bloque' => 'apoyo-auditivo',
    'duracion' => 12, 'tags' => ['pronunciacion', 'atencion', 'lectura'],
    'estaciones' => [

        /*
         * Pares que se diferencian en un solo sonido. Los seis son
         * palabras reales y cada una lleva su propio dibujo.
         *
         * La versión anterior tenía dos defectos serios: usaba «bato»,
         * que no existe —el niño lo repetía como si existiera— y le ponía
         * un ratón a «pato». En una actividad de discriminación auditiva
         * eso enseña justo lo contrario de lo que busca.
         */
        est('Escucha y repite', 'Toca para oír, luego repite', '🔊', 'pronunciacion', [
            ['e' => '🦆', 'w' => 'pato'],
            ['e' => '🐱', 'w' => 'gato'],
            ['e' => '🏠', 'w' => 'casa'],
            ['e' => '🍵', 'w' => 'taza'],
            ['e' => '🐟', 'w' => 'pez'],
            ['e' => '🦶', 'w' => 'pie'],
        ]),

        est('¿Suenan igual?', 'Dos palabras muy parecidas', '🎧', 'opcion_multiple', [
            omp('«pato» y «pato» suenan…',   ['Igual', 'Distinto'], 'Igual', '🔊'),
            omp('«casa» y «caza» suenan…',   ['Igual', 'Distinto'], 'Igual', '🔊'),
            omp('«mar» y «mal» suenan…',     ['Distinto', 'Igual'], 'Distinto', '🔊'),
            omp('«pino» y «vino» suenan…',   ['Distinto', 'Igual'], 'Distinto', '🔊'),
            omp('«sol» y «col» suenan…',     ['Distinto', 'Igual'], 'Distinto', '🔊'),
        ]),

        est('¿Con qué empieza?', 'El primer sonido de la palabra', '🔤', 'opcion_multiple', [
            omp('¿Con qué sonido empieza «sol»?',   ['S', 'C', 'L'], 'S', '☀️'),
            omp('¿Con qué sonido empieza «mesa»?',  ['M', 'S', 'A'], 'M', '🪑'),
            omp('¿Con qué sonido empieza «pato»?',  ['P', 'T', 'O'], 'P', '🦆'),
            omp('¿Con qué sonido empieza «luna»?',  ['L', 'N', 'U'], 'L', '🌙'),
            omp('¿Con qué sonido empieza «rana»?',  ['R', 'N', 'A'], 'R', '🐸'),
        ]),

        est('Cuenta las sílabas', 'Da una palmada por sílaba', '👏', 'opcion_multiple', [
            omp('¿Cuántas sílabas tiene «sol»?',      [1, 2, 3], 1, '☀️'),
            omp('¿Cuántas sílabas tiene «casa»?',     [1, 2, 3], 2, '🏠'),
            omp('¿Cuántas sílabas tiene «pelota»?',   [2, 3, 4], 3, '⚽'),
            omp('¿Cuántas sílabas tiene «mariposa»?', [3, 4, 5], 4, '🦋'),
            omp('¿Cuántas sílabas tiene «pan»?',      [1, 2, 3], 1, '🍞'),
        ]),
    ],
],

[
    'slug'  => 'lengua-de-senas',
    'title' => 'Lengua de señas',
    'description' => 'Primeras señas de la Lengua de Señas Colombiana para saludar y pedir.',
    'objective' => 'Reconocer señas básicas de la LSC y comprender que es una lengua completa.',
    'icon' => '🤟', 'nivel' => 'todas-las-edades', 'bloque' => 'apoyo-auditivo',
    'duracion' => 12, 'tags' => ['convivencia', 'vocabulario', 'cultura'],
    'estaciones' => [

        est('¿Qué es la lengua de señas?', 'Una lengua de verdad', '🤟', 'opcion_multiple', [
            omp('La lengua de señas es…',   ['Una lengua completa', 'Gestos improvisados', 'Un juego'], 'Una lengua completa', '🤟'),
            omp('En Colombia se usa la…',   ['Lengua de Señas Colombiana', 'Lengua de Señas única mundial', 'Ninguna'], 'Lengua de Señas Colombiana', '🇨🇴'),
            omp('¿Hay una sola lengua de señas en el mundo?', ['No, hay muchas', 'Sí, una sola', 'Solo dos'], 'No, hay muchas', '🌍'),
            omp('Para hablar con una persona sorda conviene…', ['Mirarla de frente al hablar', 'Gritar', 'Taparse la boca'], 'Mirarla de frente al hablar', '👀'),
            omp('Un intérprete de lengua de señas sirve para…', ['Traducir entre dos lenguas', 'Hablar más fuerte', 'Enseñar a oír'], 'Traducir entre dos lenguas', '🧑‍🏫'),
        ]),

        est('Señas para saludar', 'Une cada seña con su palabra', '👋', 'emparejar', [
            ['e' => '👋', 'w' => 'Hola'],
            ['e' => '🙏', 'w' => 'Gracias'],
            ['e' => '🤲', 'w' => 'Por favor'],
            ['e' => '👍', 'w' => 'Sí'],
            ['e' => '👎', 'w' => 'No'],
            ['e' => '🤗', 'w' => 'Amigo'],
        ]),

        est('El alfabeto con las manos', 'Deletrear con la mano se llama dactilología', '🔤', 'opcion_multiple', [
            omp('Deletrear una palabra letra por letra con la mano se llama…', ['Dactilología', 'Caligrafía', 'Ortografía'], 'Dactilología', '✋'),
            omp('Se usa sobre todo para…',   ['Nombres propios y palabras sin seña', 'Todo siempre', 'Nada'], 'Nombres propios y palabras sin seña', '🅰️'),
            omp('¿Cuántas letras deletrearías para «ANA»?', [2, 3, 4], 3, '✋'),
            omp('En la lengua de señas también importa…', ['La expresión de la cara', 'Solo las manos', 'La voz'], 'La expresión de la cara', '😊'),
        ]),
    ],
],


// ── PASO A PASO ──────────────────────────────────────────────────────

[
    'slug'  => 'pictogramas-del-dia',
    'title' => 'Pictogramas del día',
    'description' => 'Una imagen para cada cosa: entender sin necesidad de leer.',
    'objective' => 'Asociar pictogramas con acciones y objetos, como vía de acceso alternativa al texto.',
    'icon' => '🖼️', 'nivel' => 'preescolar', 'bloque' => 'paso-a-paso',
    // Ojo: este mismo slug se vuelve a declarar en `dua-primaria.php`, que
    // se siembra después y gana. Editar las etiquetas aquí no tiene efecto.
    'duracion' => 10, 'tags' => ['vocabulario', 'observacion', 'comprension'],
    'estaciones' => [

        est('¿Qué dice esta imagen?', 'Una imagen, una acción', '🖼️', 'opcion_multiple', [
            omp('¿Qué dice?', ['Comer', 'Dormir'], 'Comer', '🍽️'),
            omp('¿Qué dice?', ['Dormir', 'Correr'], 'Dormir', '🛏️'),
            omp('¿Qué dice?', ['Lavarse las manos', 'Leer'], 'Lavarse las manos', '🧼'),
            omp('¿Qué dice?', ['Leer', 'Comer'], 'Leer', '📖'),
            omp('¿Qué dice?', ['Jugar', 'Dormir'], 'Jugar', '⚽'),
            omp('¿Qué dice?', ['Ir al baño', 'Cantar'], 'Ir al baño', '🚻'),
        ]),

        est('Une imagen y palabra', 'Cada dibujo tiene su nombre', '🔗', 'emparejar', [
            ['e' => '🍽️', 'w' => 'Comer'],
            ['e' => '🛏️', 'w' => 'Dormir'],
            ['e' => '🧼', 'w' => 'Lavarse'],
            ['e' => '📖', 'w' => 'Leer'],
            ['e' => '⚽', 'w' => 'Jugar'],
            ['e' => '🚌', 'w' => 'Viajar'],
        ]),

        est('Sí o no', 'Responde con calma', '👍', 'opcion_multiple', [
            omp('¿Esta imagen es de comer?',   ['Sí', 'No'], 'Sí', '🍽️'),
            omp('¿Esta imagen es de dormir?',  ['No', 'Sí'], 'No', '⚽'),
            omp('¿Esta imagen es de lavarse?', ['Sí', 'No'], 'Sí', '🧼'),
            omp('¿Esta imagen es de leer?',    ['No', 'Sí'], 'No', '🚌'),
        ]),
    ],
],

[
    'slug'  => 'mi-rutina-con-imagenes',
    'title' => 'Mi rutina con imágenes',
    'description' => 'Saber qué viene después tranquiliza. Arma tu día con dibujos.',
    'objective' => 'Anticipar la secuencia de actividades del día mediante apoyos visuales.',
    'icon' => '🪜', 'nivel' => 'preescolar', 'bloque' => 'paso-a-paso',
    // `anticipacion` es una etiqueta de APOYO: ver `inclusion.php`. Estas
    // actividades ya hacían ese trabajo antes de que existiera la etiqueta.
    'duracion' => 10, 'tags' => ['secuencias', 'autocuidado', 'atencion', 'anticipacion'],
    'estaciones' => [

        est('La mañana', 'Ordena solo la mañana', '🌅', 'ordenar_secuencia', [
            'title' => 'Ordena la mañana',
            'items' => ['😴 Despertar', '🧼 Lavarse la cara', '🥣 Desayunar', '🎒 Alistar la maleta'],
        ]),

        est('La tarde', 'Ahora la tarde', '🌤️', 'ordenar_secuencia', [
            'title' => 'Ordena la tarde',
            'items' => ['🏠 Llegar a casa', '🍽️ Almorzar', '📓 Hacer tareas', '⚽ Jugar'],
        ]),

        est('La noche', 'Y para terminar, la noche', '🌙', 'ordenar_secuencia', [
            'title' => 'Ordena la noche',
            'items' => ['🍲 Cenar', '🛁 Bañarse', '🦷 Cepillarse', '📖 Cuento', '😴 Dormir'],
        ]),

        est('¿Qué viene después?', 'Anticipa el siguiente paso', '➡️', 'opcion_multiple', [
            omp('Después de despertar viene…',  ['Lavarse la cara', 'Dormir'], 'Lavarse la cara', '😴'),
            omp('Después de almorzar viene…',   ['Hacer tareas', 'Desayunar'], 'Hacer tareas', '🍽️'),
            omp('Después de bañarse viene…',    ['Ponerse la pijama', 'Salir a jugar'], 'Ponerse la pijama', '🛁'),
            omp('Lo último del día es…',        ['Dormir', 'Desayunar'], 'Dormir', '🌙'),
            omp('Saber qué viene después me hace sentir…', ['Tranquilo', 'Perdido'], 'Tranquilo', '💚'),
        ]),
    ],
],

],
];
