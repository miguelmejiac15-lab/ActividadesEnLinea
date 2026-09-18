<?php
/**
 * artistica-ampliacion.php — Artística en preescolar, primero y superior
 *
 * Artística tenía once actividades de tercero y cuarto y **cuatro o
 * cinco en cada uno de los demás grados**. Es una materia que se enseña
 * los seis años y el catálogo solo la cubría en el medio.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  UNA MATERIA DE HACER, EN UNA PANTALLA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Artística se aprende con las manos, y esto es una pantalla. Así que
 * estas actividades **no pretenden sustituir el taller**: entrenan lo
 * que sí se puede entrenar mirando —reconocer un color, una textura, un
 * ritmo, una intención— y dejan el hacer para la clase.
 *
 * Cada actividad de «Crear con las Manos» termina proponiendo algo que
 * se hace fuera de la pantalla, y eso es deliberado: la estación no
 * puede comprobarlo, pero el docente sí.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'artistica',
    'name'       => 'Artística',
    'tagline'    => 'Mirar, entender y crear con línea, color, forma y movimiento',
    'icon'       => '🎨',
    'color'      => '#ec407a',
    'sort_order' => 3,
],

'bloques' => [
    ['slug' => 'mirar-y-reconocer', 'name' => 'Mirar y Reconocer', 'icon' => '👁️', 'sort_order' => 1,
     'description' => 'Colores, formas y obras: aprender a mirar antes de crear.'],
    ['slug' => 'crear-con-las-manos', 'name' => 'Crear con las Manos', 'icon' => '✂️', 'sort_order' => 2,
     'description' => 'Materiales, técnicas y el paso a paso de hacer algo.'],
    ['slug' => 'musica-y-ritmo', 'name' => 'Música y Ritmo', 'icon' => '🎼', 'sort_order' => 3,
     'description' => 'Instrumentos, sonidos y el pulso que hay detrás de una canción.'],
    ['slug' => 'elementos-del-arte', 'name' => 'Elementos del Arte', 'icon' => '✏️', 'sort_order' => 4,
     'description' => 'Línea, forma, textura, color y composición: el alfabeto con el que se hace cualquier imagen.'],
    ['slug' => 'historia-del-arte', 'name' => 'Historia del Arte', 'icon' => '🏛️', 'sort_order' => 5,
     'description' => 'De las cuevas al Barroco, y lo que cada época descubrió sobre cómo representar el mundo.'],
    ['slug' => 'danza-y-movimiento', 'name' => 'Danza y Movimiento', 'icon' => '💃', 'sort_order' => 7,
     'description' => 'El cuerpo como instrumento: ritmo, espacio y las danzas de nuestras regiones.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR
// =====================================================================

[
    'slug'  => 'los-colores-que-veo',
    'title' => 'Los colores que veo',
    'description' => 'Rojo, azul, amarillo y todos los demás: nombrarlos y encontrarlos.',
    'objective' => 'Reconocer y nombrar colores primarios y secundarios en objetos del entorno.',
    'icon' => '🖍️', 'nivel' => 'preescolar', 'bloque' => 'mirar-y-reconocer',
    'duracion' => 8, 'tags' => ['observacion', 'vocabulario', 'clasificacion'],
    'estaciones' => [

        est('¿De qué color?', 'Mira el dibujo', '🎨', 'opcion_multiple', [
            omp('🍓 La fresa es…',    ['Roja', 'Azul', 'Verde'], 'Roja', '🍓'),
            omp('🍋 El limón es…',    ['Amarillo', 'Morado', 'Negro'], 'Amarillo', '🍋'),
            omp('🌊 El mar se ve…',   ['Azul', 'Rojo', 'Café'], 'Azul', '🌊'),
            omp('🌿 La hoja es…',     ['Verde', 'Rosada', 'Gris'], 'Verde', '🌿'),
            omp('🍆 La berenjena es…',['Morada', 'Amarilla', 'Blanca'], 'Morada', '🍆'),
        ]),

        est('Los tres que lo hacen todo', 'Rojo, azul y amarillo', '🔴', 'opcion_multiple', [
            omp('Los colores primarios son…',       ['Rojo, azul y amarillo', 'Verde, rosa y gris', 'Blanco y negro'], 'Rojo, azul y amarillo'),
            omp('Azul + amarillo da…',              ['Verde', 'Rojo', 'Café'], 'Verde', '🟢'),
            omp('Rojo + amarillo da…',              ['Naranja', 'Azul', 'Verde'], 'Naranja', '🟠'),
            omp('Rojo + azul da…',                  ['Morado', 'Verde', 'Amarillo'], 'Morado', '🟣'),
            omp('Los colores que salen de mezclar se llaman…', ['Secundarios', 'Primarios', 'Grises'], 'Secundarios'),
        ]),

        est('Todo lo que es rojo', 'Búscalos', '🔴', 'seleccion_imagenes',
            conTitulo('Toca todo lo que es ROJO', 'Solo lo rojo', [
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '🍌', 'n' => 'Banano',  'ok' => false],
                ['e' => '🍓', 'n' => 'Fresa',   'ok' => true],
                ['e' => '🥝', 'n' => 'Kiwi',    'ok' => false],
                ['e' => '🌹', 'n' => 'Rosa',    'ok' => true],
                ['e' => '🫐', 'n' => 'Arándano','ok' => false],
                ['e' => '🚒', 'n' => 'Bombero', 'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',   'ok' => false],
            ])),

        est('Memoria de colores', 'Encuentra las parejas', '🧠', 'memoria',
            ['🔴', '🟠', '🟡', '🟢', '🔵', '🟣']),
    ],
],

[
    'slug'  => 'lineas-y-formas',
    'title' => 'Líneas y formas',
    'description' => 'Recta, curva, círculo, cuadrado: con esto se dibuja todo.',
    'objective' => 'Identificar tipos de línea y figuras geométricas básicas en dibujos.',
    'icon' => '✏️', 'nivel' => 'preescolar', 'bloque' => 'elementos-del-arte',
    'duracion' => 8, 'tags' => ['observacion', 'clasificacion', 'logica'],
    'estaciones' => [

        est('¿Qué forma es?', 'Mira los lados', '🔷', 'opcion_multiple', [
            omp('Una forma sin esquinas es un…',     ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo', '⚪'),
            omp('Una forma de tres lados es un…',    ['Triángulo', 'Círculo', 'Cuadrado'], 'Triángulo', '🔺'),
            omp('Una forma de cuatro lados iguales es un…', ['Cuadrado', 'Triángulo', 'Círculo'], 'Cuadrado', '⬜'),
            omp('El sol se parece a un…',            ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo', '☀️'),
            omp('El techo de una casa se parece a un…', ['Triángulo', 'Círculo', 'Óvalo'], 'Triángulo', '🏠'),
        ]),

        est('Líneas', 'Rectas, curvas, en zigzag', '➰', 'opcion_multiple', [
            omp('Una línea sin curvas es…',      ['Recta', 'Curva', 'Zigzag'], 'Recta'),
            omp('Una línea que da vueltas es…',  ['Curva', 'Recta', 'Punteada'], 'Curva'),
            omp('Una línea con picos es…',       ['Zigzag', 'Recta', 'Curva'], 'Zigzag'),
            omp('El horizonte del mar es una línea…', ['Recta', 'Zigzag', 'Espiral'], 'Recta', '🌊'),
            omp('Una montaña se dibuja con líneas…', ['En zigzag', 'Rectas', 'Puntos'], 'En zigzag', '⛰️'),
        ]),

        est('Encuentra las formas', 'En cosas de verdad', '🔍', 'emparejar', [
            ['e' => '🕐', 'w' => 'Círculo'],
            ['e' => '📺', 'w' => 'Rectángulo'],
            ['e' => '🍕', 'w' => 'Triángulo'],
            ['e' => '⭐', 'w' => 'Estrella'],
            ['e' => '❤️', 'w' => 'Corazón'],
            ['e' => '🥚', 'w' => 'Óvalo'],
        ]),

        est('Memoria de formas', 'Encuentra las parejas', '🧠', 'memoria',
            ['⚪', '⬜', '🔺', '⭐', '❤️', '🔷']),
    ],
],

[
    'slug'  => 'sonidos-fuertes-y-suaves',
    'title' => 'Sonidos fuertes y suaves',
    'description' => 'Escuchar y distinguir: fuerte o suave, rápido o lento, largo o corto.',
    'objective' => 'Discriminar cualidades básicas del sonido.',
    'icon' => '🔊', 'nivel' => 'preescolar', 'bloque' => 'musica-y-ritmo',
    'duracion' => 8, 'tags' => ['observacion', 'sensorial', 'juego'],
    'estaciones' => [

        est('¿Fuerte o suave?', 'Piensa cómo suena', '🔉', 'opcion_multiple', [
            omp('Un trueno suena…',      ['Fuerte', 'Suave'], 'Fuerte', '⛈️'),
            omp('Un susurro suena…',     ['Suave', 'Fuerte'], 'Suave', '🤫'),
            omp('Una ambulancia suena…', ['Fuerte', 'Suave'], 'Fuerte', '🚑'),
            omp('Una hoja cayendo suena…', ['Suave', 'Fuerte'], 'Suave', '🍃'),
            omp('Un tambor suena…',      ['Fuerte', 'Suave'], 'Fuerte', '🥁'),
        ]),

        est('¿Rápido o lento?', 'El pulso de la música', '⏱️', 'opcion_multiple', [
            omp('Una canción de cuna es…',  ['Lenta', 'Rápida'], 'Lenta', '🌙'),
            omp('Una canción para bailar es…', ['Rápida', 'Lenta'], 'Rápida', '💃'),
            omp('Una tortuga se mueve…',    ['Lento', 'Rápido'], 'Lento', '🐢'),
            omp('Un caballo corriendo va…', ['Rápido', 'Lento'], 'Rápido', '🐎'),
            omp('Cuando la música va rápido, bailo…', ['Rápido', 'Lento', 'Quieto'], 'Rápido'),
        ]),

        est('¿Qué instrumento es?', 'Cada uno suena distinto', '🎺', 'emparejar', [
            ['e' => '🥁', 'w' => 'Tambor'],
            ['e' => '🎸', 'w' => 'Guitarra'],
            ['e' => '🎹', 'w' => 'Piano'],
            ['e' => '🎺', 'w' => 'Trompeta'],
            ['e' => '🎻', 'w' => 'Violín'],
            ['e' => '🪇', 'w' => 'Maracas'],
        ]),

        est('Todo lo que suena', 'Búscalo', '🔍', 'seleccion_imagenes',
            conTitulo('Toca todo lo que hace música', 'Solo instrumentos', [
                ['e' => '🥁', 'n' => 'Tambor',   'ok' => true],
                ['e' => '📚', 'n' => 'Libro',    'ok' => false],
                ['e' => '🎹', 'n' => 'Piano',    'ok' => true],
                ['e' => '🪑', 'n' => 'Silla',    'ok' => false],
                ['e' => '🎸', 'n' => 'Guitarra', 'ok' => true],
                ['e' => '👟', 'n' => 'Zapato',   'ok' => false],
                ['e' => '🎺', 'n' => 'Trompeta', 'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana',  'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'pintar-y-pegar',
    'title' => 'Pintar y pegar',
    'description' => 'Los materiales del rincón de arte y cómo se usan sin desastres.',
    'objective' => 'Reconocer materiales artísticos básicos y su uso y cuidado.',
    'icon' => '✂️', 'nivel' => 'preescolar', 'bloque' => 'crear-con-las-manos',
    'duracion' => 8, 'tags' => ['observacion', 'vocabulario', 'seguridad'],
    'estaciones' => [

        est('¿Para qué sirve?', 'Cada material hace algo', '🖍️', 'opcion_multiple', [
            omp('El pincel sirve para…',   ['Pintar', 'Cortar', 'Pegar'], 'Pintar', '🖌️'),
            omp('Las tijeras sirven para…',['Cortar', 'Pintar', 'Borrar'], 'Cortar', '✂️'),
            omp('El pegante sirve para…',  ['Pegar', 'Cortar', 'Pintar'], 'Pegar'),
            omp('El crayón sirve para…',   ['Colorear', 'Pegar', 'Cortar'], 'Colorear', '🖍️'),
            omp('El borrador sirve para…', ['Borrar', 'Pintar', 'Pegar'], 'Borrar'),
        ]),

        est('Ordena para hacer un collage', 'Paso a paso', '🪄', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se hace un collage',
            'items' => ['Pensar qué quiero hacer', 'Buscar papeles de colores',
                        'Cortar las formas', 'Pegarlas en la hoja',
                        'Esperar a que seque', 'Recoger y limpiar'],
        ]),

        est('Usar las cosas con cuidado', 'Sin hacerse daño ni ensuciar todo', '🛡️', 'opcion_multiple', [
            omp('Las tijeras se llevan…',    ['Con las puntas hacia abajo', 'Corriendo', 'En el bolsillo'], 'Con las puntas hacia abajo'),
            omp('El pegante se come…',       ['Nunca', 'Un poquito', 'Si sabe bien'], 'Nunca'),
            omp('Antes de pintar pongo…',    ['Un papel o mantel debajo', 'Nada', 'El cuaderno'], 'Un papel o mantel debajo'),
            omp('Al terminar de pintar…',    ['Lavo el pincel', 'Lo tiro', 'Lo dejo con pintura'], 'Lavo el pincel'),
            omp('Si se me cae la pintura…',  ['Aviso y ayudo a limpiar', 'Me voy', 'La piso'], 'Aviso y ayudo a limpiar'),
        ]),

        est('¿Con qué se hace?', 'Cada obra, su material', '🔗', 'emparejar', [
            ['e' => '🖌️', 'w' => 'Pintura'],
            ['e' => '✂️', 'w' => 'Papel recortado'],
            ['e' => '🏺', 'w' => 'Arcilla'],
            ['e' => '🧶', 'w' => 'Lana'],
            ['e' => '🖍️', 'w' => 'Cera'],
            ['e' => '📷', 'w' => 'Foto'],
        ]),
    ],
],


// =====================================================================
//  PRIMARIA INICIAL
// =====================================================================

[
    'slug'  => 'colores-que-transmiten',
    'title' => 'Colores que transmiten',
    'description' => 'Cálidos y fríos: por qué un cuadro azul se siente distinto a uno naranja.',
    'objective' => 'Clasificar colores en cálidos y fríos y asociarlos a sensaciones.',
    'icon' => '🌡️', 'nivel' => 'primaria-inicial', 'bloque' => 'mirar-y-reconocer',
    'duracion' => 10, 'tags' => ['observacion', 'emociones', 'clasificacion'],
    'estaciones' => [

        est('Cálidos y fríos', 'Dos familias de color', '🌡️', 'opcion_multiple', [
            omp('El rojo es un color…',     ['Cálido', 'Frío'], 'Cálido', '🔴'),
            omp('El azul es un color…',     ['Frío', 'Cálido'], 'Frío', '🔵'),
            omp('El naranja es…',           ['Cálido', 'Frío'], 'Cálido', '🟠'),
            omp('El verde suele sentirse…', ['Frío', 'Cálido'], 'Frío', '🟢'),
            omp('Los cálidos recuerdan a…', ['El sol y el fuego', 'El hielo', 'La noche'], 'El sol y el fuego'),
        ]),

        est('¿Cuál es cálido?', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es un color CÁLIDO?', 'Piensa en el sol y el fuego', [
                ['e' => '🔴', 'n' => 'Rojo',     'ok' => true],
                ['e' => '🔵', 'n' => 'Azul',     'ok' => false],
                ['e' => '🟠', 'n' => 'Naranja',  'ok' => true],
                ['e' => '🟣', 'n' => 'Violeta',  'ok' => false],
                ['e' => '🟡', 'n' => 'Amarillo', 'ok' => true],
                ['e' => '🟢', 'n' => 'Verde',    'ok' => false],
                ['e' => '🟤', 'n' => 'Café',     'ok' => true],
                ['e' => '⚪', 'n' => 'Blanco',   'ok' => false],
            ])),

        est('El color y lo que siento', 'No es magia: es costumbre y naturaleza', '💭', 'opcion_multiple', [
            omp('Un cuarto todo rojo se siente…',   ['Más activo', 'Más tranquilo', 'Igual'], 'Más activo'),
            omp('Un cuarto azul claro se siente…',  ['Más tranquilo', 'Más activo', 'Enojado'], 'Más tranquilo'),
            omp('Los colores de alerta suelen ser…', ['Rojo y amarillo', 'Azul y gris', 'Blanco'], 'Rojo y amarillo'),
            omp('Un cuadro de invierno suele tener…', ['Colores fríos', 'Colores cálidos', 'Solo negro'], 'Colores fríos'),
            omp('El color que uso cambia…',         ['Lo que transmite mi dibujo', 'Nada', 'El tamaño'], 'Lo que transmite mi dibujo'),
        ]),

        est('Mezclas y tonos', 'Del claro al oscuro', '🎚️', 'opcion_multiple', [
            omp('Para aclarar un color le añado…',  ['Blanco', 'Negro', 'Rojo'], 'Blanco'),
            omp('Para oscurecerlo le añado…',       ['Negro', 'Blanco', 'Amarillo'], 'Negro'),
            omp('Rojo + blanco da…',                ['Rosado', 'Morado', 'Café'], 'Rosado'),
            omp('Azul + blanco da…',                ['Azul claro', 'Verde', 'Negro'], 'Azul claro'),
            omp('Usar varios tonos de un color se llama…', ['Gama', 'Mezcla rara', 'Error'], 'Gama'),
        ]),
    ],
],

[
    'slug'  => 'el-pulso-de-la-musica',
    'title' => 'El pulso de la música',
    'description' => 'Debajo de toda canción hay un latido constante. Encontrarlo es lo primero.',
    'objective' => 'Reconocer pulso, acento y tempo en piezas musicales.',
    'icon' => '🥁', 'nivel' => 'primaria-inicial', 'bloque' => 'musica-y-ritmo',
    'duracion' => 10, 'tags' => ['patrones', 'observacion', 'secuencias'],
    'estaciones' => [

        est('¿Qué es el pulso?', 'El latido de la canción', '💓', 'opcion_multiple', [
            omp('El pulso de una canción es…',   ['Un latido regular', 'La letra', 'El instrumento'], 'Un latido regular'),
            omp('El pulso va…',                  ['Siempre igual de espaciado', 'Cuando quiere', 'Solo al final'], 'Siempre igual de espaciado'),
            omp('Cuando aplaudo con la canción sigo…', ['El pulso', 'La letra', 'El volumen'], 'El pulso'),
            omp('A la velocidad del pulso se le llama…', ['Tempo', 'Ritmo', 'Melodía'], 'Tempo'),
            omp('Si el tempo es rápido, el pulso va…', ['Más seguido', 'Más lento', 'Igual'], 'Más seguido'),
        ]),

        est('Patrones de palmas', 'Fuertes y suaves', '👏', 'opcion_multiple', [
            omp('FUERTE-suave-suave, FUERTE-suave-suave. ¿Cada cuántos va el fuerte?', ['Cada 3', 'Cada 2', 'Cada 4'], 'Cada 3'),
            omp('FUERTE-suave, FUERTE-suave. ¿Cada cuántos?', ['Cada 2', 'Cada 3', 'Cada 5'], 'Cada 2'),
            omp('Al golpe más fuerte se le llama…',  ['Acento', 'Silencio', 'Nota'], 'Acento'),
            omp('El vals va de…',                    ['Tres en tres', 'Dos en dos', 'Siete en siete'], 'Tres en tres'),
            omp('Un silencio en música es…',         ['Parte del ritmo', 'Un error', 'El final'], 'Parte del ritmo'),
        ]),

        est('Familias de instrumentos', 'Según cómo suenan', '🎼', 'opcion_multiple', [
            omp('El tambor es de…',      ['Percusión', 'Viento', 'Cuerda'], 'Percusión', '🥁'),
            omp('La guitarra es de…',    ['Cuerda', 'Percusión', 'Viento'], 'Cuerda', '🎸'),
            omp('La flauta es de…',      ['Viento', 'Cuerda', 'Percusión'], 'Viento', '🪈'),
            omp('El violín es de…',      ['Cuerda', 'Viento', 'Percusión'], 'Cuerda', '🎻'),
            omp('Las maracas son de…',   ['Percusión', 'Cuerda', 'Viento'], 'Percusión', '🪇'),
        ]),

        est('Une el instrumento con su familia', 'Tres familias', '🔗', 'emparejar', [
            ['e' => '🥁', 'w' => 'Percusión'],
            ['e' => '🎸', 'w' => 'Cuerda'],
            ['e' => '🎺', 'w' => 'Viento metal'],
            ['e' => '🪈', 'w' => 'Viento madera'],
            ['e' => '🎻', 'w' => 'Cuerda frotada'],
            ['e' => '🎹', 'w' => 'Teclado'],
        ]),
    ],
],

[
    'slug'  => 'mi-cuerpo-se-mueve',
    'title' => 'Mi cuerpo se mueve',
    'description' => 'Rápido, lento, alto, bajo: el cuerpo también cuenta cosas.',
    'objective' => 'Explorar calidades del movimiento y el uso del espacio.',
    'icon' => '🕺', 'nivel' => 'primaria-inicial', 'bloque' => 'danza-y-movimiento',
    'duracion' => 10, 'tags' => ['cuerpo', 'observacion', 'juego'],
    'estaciones' => [

        est('Cómo se puede mover', 'Muchas maneras', '🤸', 'opcion_multiple', [
            omp('Moverse muy despacio y suave es un movimiento…', ['Lento', 'Brusco', 'Alto'], 'Lento'),
            omp('Moverse de golpe es…',           ['Brusco', 'Suave', 'Lento'], 'Brusco'),
            omp('Moverse pegado al suelo es a nivel…', ['Bajo', 'Alto', 'Medio'], 'Bajo'),
            omp('Moverse de puntillas y con los brazos arriba es a nivel…', ['Alto', 'Bajo', 'Medio'], 'Alto'),
            omp('Si la música es lenta, me muevo…', ['Lento', 'Rápido', 'Sin moverme'], 'Lento'),
        ]),

        est('El cuerpo cuenta algo', 'Sin decir palabra', '🎭', 'opcion_multiple', [
            omp('Para mostrar alegría me muevo…',  ['Con saltos y ligero', 'Encogido', 'Sin moverme'], 'Con saltos y ligero'),
            omp('Para mostrar cansancio me muevo…', ['Lento y pesado', 'Rápido', 'Saltando'], 'Lento y pesado'),
            omp('Para mostrar miedo me hago…',     ['Pequeño', 'Muy grande', 'Rápido'], 'Pequeño'),
            omp('Un gigante camina…',              ['Lento y grande', 'De puntillas', 'Corriendo'], 'Lento y grande'),
            omp('El movimiento sin palabras se llama…', ['Mímica', 'Canción', 'Dibujo'], 'Mímica'),
        ]),

        est('Danzas de Colombia', 'Cada región tiene la suya', '🇨🇴', 'emparejar', [
            ['e' => '🎺', 'w' => 'Cumbia'],
            ['e' => '🪗', 'w' => 'Vallenato'],
            ['e' => '🎻', 'w' => 'Bambuco'],
            ['e' => '🥁', 'w' => 'Currulao'],
            ['e' => '🪘', 'w' => 'Mapalé'],
            ['e' => '🎸', 'w' => 'Joropo'],
        ]),

        est('Ordena una coreografía', 'Antes de bailar', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se prepara un baile en grupo',
            'items' => ['Escuchar la música', 'Encontrar el pulso',
                        'Inventar los pasos', 'Ensayar por partes',
                        'Ensayar todo seguido', 'Presentarlo'],
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR
// =====================================================================

[
    'slug'  => 'composicion-de-una-imagen',
    'title' => 'La composición de una imagen',
    'description' => 'Dónde se pone cada cosa. La misma foto cambia entera si se mueve el centro.',
    'objective' => 'Reconocer principios de composición: equilibrio, foco y regla de los tercios.',
    'icon' => '🖼️', 'nivel' => 'primaria-superior', 'bloque' => 'elementos-del-arte',
    'duracion' => 14, 'tags' => ['observacion', 'logica', 'patrones'],
    'estaciones' => [

        est('El centro de atención', 'A dónde va el ojo primero', '👁️', 'opcion_multiple', [
            omp('El punto al que va el ojo primero se llama…', ['Foco', 'Fondo', 'Marco'], 'Foco'),
            omp('Un objeto más grande que el resto…',  ['Atrae la mirada', 'Se pierde', 'Da igual'], 'Atrae la mirada'),
            omp('Un color muy distinto al resto…',     ['Atrae la mirada', 'Se esconde', 'Molesta siempre'], 'Atrae la mirada'),
            omp('Si todo tiene el mismo peso visual…', ['El ojo no sabe dónde mirar', 'Es perfecto', 'Es un foco'], 'El ojo no sabe dónde mirar'),
            omp('Dejar espacio vacío alrededor del foco…', ['Lo destaca', 'Lo esconde', 'Es un error'], 'Lo destaca'),
        ]),

        est('La regla de los tercios', 'Ni en el centro exacto ni en la esquina', '📐', 'opcion_multiple', [
            omp('La regla de los tercios divide la imagen en…', ['Nueve partes', 'Dos partes', 'Cien partes'], 'Nueve partes'),
            omp('Lo importante se coloca…',        ['Donde se cruzan las líneas', 'Justo en el centro siempre', 'En una esquina'], 'Donde se cruzan las líneas'),
            omp('El horizonte conviene ponerlo…',  ['En un tercio, arriba o abajo', 'Justo en el medio siempre', 'Fuera'], 'En un tercio, arriba o abajo'),
            omp('Una composición centrada perfecta transmite…', ['Calma y orden', 'Movimiento', 'Caos'], 'Calma y orden'),
            omp('Una composición en diagonal transmite…', ['Movimiento', 'Quietud', 'Nada'], 'Movimiento'),
        ]),

        est('Equilibrio', 'Simétrico o no', '⚖️', 'opcion_multiple', [
            omp('Si las dos mitades son iguales, es equilibrio…', ['Simétrico', 'Asimétrico', 'Roto'], 'Simétrico'),
            omp('Un objeto grande a un lado y dos pequeños al otro es…', ['Asimétrico pero equilibrado', 'Desequilibrado', 'Simétrico'], 'Asimétrico pero equilibrado'),
            omp('Una imagen con todo el peso a un lado se siente…', ['Inestable', 'Tranquila', 'Perfecta'], 'Inestable'),
            omp('La simetría se usa mucho en…',    ['Edificios y retratos formales', 'Fotos de acción', 'Nada'], 'Edificios y retratos formales'),
            omp('El equilibrio no es…',            ['Poner todo en el centro', 'Repartir el peso', 'Una decisión'], 'Poner todo en el centro'),
        ]),

        est('Reto de la composición', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('El foco de una imagen es…',     ['A donde va el ojo primero', 'El marco', 'El color'], 'A donde va el ojo primero'),
            reto('Poner el horizonte en el centro exacto suele ser…', ['Menos interesante', 'Siempre lo mejor', 'Obligatorio'], 'Menos interesante'),
            reto('El espacio vacío sirve para…',  ['Destacar lo importante', 'Rellenar', 'Nada'], 'Destacar lo importante'),
            reto('Una diagonal fuerte sugiere…',  ['Movimiento', 'Quietud', 'Frío'], 'Movimiento'),
            reto('La composición se decide…',     ['Antes de disparar o pintar', 'Al final', 'Nunca'], 'Antes de disparar o pintar'),
        ]),
    ],
],

[
    'slug'  => 'arte-y-su-epoca',
    'title' => 'El arte y su época',
    'description' => 'Impresionismo, cubismo, abstracto: qué buscaba cada uno y por qué.',
    'objective' => 'Relacionar movimientos artísticos modernos con su intención y contexto.',
    'icon' => '🖼️', 'nivel' => 'primaria-superior', 'bloque' => 'historia-del-arte',
    'duracion' => 15, 'tags' => ['historia', 'cultura', 'observacion'],
    'estaciones' => [

        est('Qué buscaba cada movimiento', 'No pintaban distinto por capricho', '🎨', 'opcion_multiple', [
            omp('El impresionismo buscaba pintar…', ['La luz de un instante', 'El detalle exacto', 'Solo formas'], 'La luz de un instante'),
            omp('El cubismo mostraba un objeto…',   ['Desde varios lados a la vez', 'Perfectamente real', 'Sin color'], 'Desde varios lados a la vez'),
            omp('El arte abstracto…',               ['No representa cosas reconocibles', 'Copia la realidad', 'Solo hace retratos'], 'No representa cosas reconocibles'),
            omp('El surrealismo pintaba…',          ['Imágenes de sueño', 'Fotos', 'Mapas'], 'Imágenes de sueño'),
            omp('La fotografía empujó a los pintores a…', ['Buscar algo que la foto no hacía', 'Copiar mejor', 'Dejar de pintar'], 'Buscar algo que la foto no hacía'),
        ]),

        est('Antes y después', 'Ordena en el tiempo', '⏳', 'ordenar_secuencia', [
            'title' => 'Ordena del más antiguo al más reciente',
            'items' => ['Pintura rupestre', 'Arte egipcio', 'Renacimiento',
                        'Impresionismo', 'Cubismo', 'Arte digital'],
        ]),

        est('Arte de Colombia', 'Nombres que conviene conocer', '🇨🇴', 'opcion_multiple', [
            omp('Fernando Botero es famoso por sus figuras…', ['De volumen exagerado', 'Muy delgadas', 'Invisibles'], 'De volumen exagerado'),
            omp('El oro precolombino se trabajaba…',    ['Antes de la llegada de los españoles', 'En 1950', 'Nunca'], 'Antes de la llegada de los españoles'),
            omp('El Museo del Oro está en…',            ['Bogotá', 'Cali', 'Cartagena'], 'Bogotá'),
            omp('La mochila wayúu es un ejemplo de…',   ['Arte tradicional tejido', 'Pintura al óleo', 'Escultura en bronce'], 'Arte tradicional tejido'),
            omp('El arte de un pueblo cuenta…',         ['Cómo vivía y qué le importaba', 'Solo su riqueza', 'Nada'], 'Cómo vivía y qué le importaba'),
        ]),

        est('Reto de historia del arte', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('El impresionismo se fija sobre todo en…', ['La luz', 'La línea exacta', 'El oro'], 'La luz'),
            reto('El cubismo rompe…',               ['El punto de vista único', 'El color', 'El papel'], 'El punto de vista único'),
            reto('Un cuadro abstracto puede…',      ['Transmitir sin representar', 'No transmitir nada', 'Ser un error'], 'Transmitir sin representar'),
            reto('El arte cambia porque…',          ['Cambia lo que la gente necesita expresar', 'Se aburren', 'Por azar'], 'Cambia lo que la gente necesita expresar'),
            reto('Mirar una obra con atención es…', ['Parte de aprender arte', 'Perder tiempo', 'Solo para expertos'], 'Parte de aprender arte'),
        ]),
    ],
],

[
    'slug'  => 'el-color-en-la-practica',
    'title' => 'El color en la práctica',
    'description' => 'Complementarios, contraste y armonía: por qué unas combinaciones funcionan.',
    'objective' => 'Aplicar relaciones del círculo cromático a decisiones de color.',
    'icon' => '🎡', 'nivel' => 'primaria-superior', 'bloque' => 'elementos-del-arte',
    'duracion' => 14, 'tags' => ['observacion', 'logica', 'clasificacion'],
    'estaciones' => [

        est('El círculo cromático', 'Un mapa de los colores', '🎡', 'opcion_multiple', [
            omp('Los colores opuestos en el círculo se llaman…', ['Complementarios', 'Primarios', 'Iguales'], 'Complementarios'),
            omp('El complementario del rojo es…',   ['Verde', 'Azul', 'Naranja'], 'Verde'),
            omp('El complementario del azul es…',   ['Naranja', 'Verde', 'Morado'], 'Naranja'),
            omp('El complementario del amarillo es…', ['Violeta', 'Rojo', 'Verde'], 'Violeta'),
            omp('Dos complementarios juntos hacen…', ['Mucho contraste', 'Poco contraste', 'Nada'], 'Mucho contraste'),
        ]),

        est('Contraste y armonía', 'Cuándo conviene cada uno', '⚖️', 'opcion_multiple', [
            omp('Para que un cartel se vea de lejos conviene…', ['Mucho contraste', 'Poco contraste', 'Todo gris'], 'Mucho contraste'),
            omp('Colores vecinos del círculo dan…', ['Armonía tranquila', 'Contraste fuerte', 'Confusión'], 'Armonía tranquila'),
            omp('Texto claro sobre fondo claro…',   ['No se lee', 'Se lee mejor', 'Es elegante'], 'No se lee'),
            omp('Usar demasiados colores a la vez…', ['Cansa la vista', 'Siempre es mejor', 'Da orden'], 'Cansa la vista'),
            omp('Una paleta limitada suele verse…', ['Más ordenada', 'Pobre siempre', 'Igual'], 'Más ordenada'),
        ]),

        est('El color según el uso', 'Cada oficio tiene sus reglas', '🏷️', 'opcion_multiple', [
            omp('En una señal de peligro se usa…',  ['Rojo o amarillo', 'Azul claro', 'Beige'], 'Rojo o amarillo'),
            omp('En un hospital se usan colores…',  ['Suaves y claros', 'Muy saturados', 'Negros'], 'Suaves y claros'),
            omp('Para que un botón se note en una pantalla…', ['Contrasta con el fondo', 'Es del mismo color', 'Es transparente'], 'Contrasta con el fondo'),
            omp('Para alguien que ve poco color, además del color hay que usar…', ['Formas o texto', 'Más color', 'Nada'], 'Formas o texto'),
            omp('El color comunica…',               ['Antes que el texto', 'Después del texto', 'Nunca'], 'Antes que el texto'),
        ]),

        est('Une el color con lo que suele significar', 'Por costumbre cultural', '🔗', 'emparejar', [
            ['e' => '🔴', 'w' => 'Peligro'],
            ['e' => '🟢', 'w' => 'Permitido'],
            ['e' => '🟡', 'w' => 'Precaución'],
            ['e' => '🔵', 'w' => 'Calma'],
            ['e' => '⚫', 'w' => 'Elegancia'],
            ['e' => '⚪', 'w' => 'Limpieza'],
        ]),
    ],
],

],
];
