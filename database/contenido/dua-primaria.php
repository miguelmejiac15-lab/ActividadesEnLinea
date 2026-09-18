<?php
/**
 * dua-primaria.php — Aprender sin Barreras de 1.º a 6.º
 *
 * Esta categoría existe por el Diseño Universal para el Aprendizaje: no
 * son «actividades para niños con dificultades», son las mismas metas de
 * las mallas por otro camino. Un niño que no lee todavía con soltura no
 * tiene por qué quedarse fuera de una actividad de ciencias.
 *
 * TRES REGLAS que cumple todo lo de este archivo:
 *
 * 1. Poco texto y siempre acompañado de dibujo. Nada depende de leer un
 *    párrafo largo.
 * 2. Un paso a la vez. Las estaciones son cortas y no acumulan dos
 *    dificultades a la vez (ni contrarreloj, ni escribir de memoria).
 * 3. Las respuestas se distinguen por FORMA y por PALABRA, no solo por
 *    color: quien no distingue verde de rojo tiene que poder jugar igual.
 *
 * Lo que NO hace este archivo es bajar el contenido. Un pictograma no es
 * una versión fácil de la ciencia: es la misma ciencia sin el obstáculo
 * del texto.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'dua',
    'name'       => 'Aprender sin Barreras',
    'tagline'    => 'El mismo contenido, por el camino que a cada uno le sirve',
    'icon'       => '♿',
    'color'      => '#5e35b1',
    'sort_order' => 8,
],

'bloques' => [
    ['slug' => 'lectura-facil', 'name' => 'Lectura Fácil', 'icon' => '📖', 'sort_order' => 4,
     'description' => 'Textos cortos, frases simples y un dibujo por idea.'],
    ['slug' => 'rutinas-y-anticipacion', 'name' => 'Rutinas y Anticipación', 'icon' => '📅', 'sort_order' => 5,
     'description' => 'Saber qué viene después: secuencias visuales que ordenan el día.'],
    ['slug' => 'un-paso-a-la-vez', 'name' => 'Un Paso a la Vez', 'icon' => '👣', 'sort_order' => 6,
     'description' => 'Contenido de las materias troceado en pasos pequeños y sin prisa.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · LECTURA FÁCIL
// =====================================================================

[
    'slug'  => 'cuento-facil-la-semilla',
    'title' => 'Cuento fácil: la semilla',
    'description' => 'Un cuento corto con frases simples y un dibujo en cada página.',
    'objective' => 'Comprender una narración breve apoyada en imágenes y responder preguntas literales.',
    'icon' => '🌱', 'nivel' => 'primaria-inicial', 'bloque' => 'lectura-facil',
    'duracion' => 10, 'tags' => ['lectura', 'comprension', 'observacion'],
    'estaciones' => [

        est('El cuento', 'Una idea por página', '📖', 'cuento', [
            'slides' => [
                ['img' => '🌰', 'text' => 'Ana tiene una semilla.'],
                ['img' => '🪴', 'text' => 'Ana pone la semilla en la tierra.'],
                ['img' => '💧', 'text' => 'Ana echa agua todos los días.'],
                ['img' => '☀️', 'text' => 'El sol calienta la tierra.'],
                ['img' => '🌻', 'text' => 'La semilla crece. Ahora es una flor.'],
            ],
            'qs' => [
                reto('¿Qué tiene Ana al principio?',   ['Una semilla', 'Una flor', 'Un perro'], 'Una semilla'),
                reto('¿Dónde pone la semilla?',        ['En la tierra', 'En el agua', 'En el aire'], 'En la tierra'),
                reto('¿Qué le echa todos los días?',   ['Agua', 'Arena', 'Leche'], 'Agua'),
                reto('¿Qué calienta la tierra?',       ['El sol', 'La luna', 'El viento'], 'El sol'),
                reto('¿En qué se convierte la semilla?', ['En una flor', 'En un árbol', 'En una piedra'], 'En una flor'),
            ],
        ]),

        est('Une con su dibujo', 'Palabra y dibujo juntos', '🔗', 'emparejar', [
            ['e' => '🌰', 'w' => 'Semilla'],
            ['e' => '💧', 'w' => 'Agua'],
            ['e' => '☀️', 'w' => 'Sol'],
            ['e' => '🌻', 'w' => 'Flor'],
            ['e' => '🪴', 'w' => 'Maceta'],
            ['e' => '🌱', 'w' => 'Brote'],
        ]),

        est('Ordena el cuento', 'Primero, después, al final', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena la historia de la semilla',
            'items' => ['🌰 Semilla', '🪴 En la tierra', '💧 Agua', '🌱 Brote', '🌻 Flor'],
        ]),
    ],
],

[
    'slug'  => 'palabras-con-dibujo',
    'title' => 'Palabras con dibujo',
    'description' => 'Cada palabra viene con su imagen: vocabulario básico sin depender de leer.',
    'objective' => 'Asociar palabras frecuentes con su representación visual.',
    'icon' => '🖼️', 'nivel' => 'primaria-inicial', 'bloque' => 'lectura-facil',
    'duracion' => 10, 'tags' => ['vocabulario', 'lectura', 'observacion'],
    'estaciones' => [

        est('En casa', 'Palabras de la casa', '🏠', 'emparejar', [
            ['e' => '🛏️', 'w' => 'Cama'],
            ['e' => '🚪', 'w' => 'Puerta'],
            ['e' => '🪟', 'w' => 'Ventana'],
            ['e' => '🍽️', 'w' => 'Plato'],
            ['e' => '🪑', 'w' => 'Silla'],
            ['e' => '🛁', 'w' => 'Bañera'],
        ]),

        est('En el colegio', 'Palabras del colegio', '🏫', 'emparejar', [
            ['e' => '📕', 'w' => 'Libro'],
            ['e' => '✏️', 'w' => 'Lápiz'],
            ['e' => '🎒', 'w' => 'Maleta'],
            ['e' => '📐', 'w' => 'Regla'],
            ['e' => '✂️', 'w' => 'Tijeras'],
            ['e' => '🖍️', 'w' => 'Color'],
        ]),

        est('Escucha y repite', 'Di la palabra en voz alta', '🔊', 'pronunciacion', [
            ['e' => '🏠', 'w' => 'Casa'],
            ['e' => '🐶', 'w' => 'Perro'],
            ['e' => '🍎', 'w' => 'Manzana'],
            ['e' => '💧', 'w' => 'Agua'],
            ['e' => '☀️', 'w' => 'Sol'],
        ]),

        est('¿Cuál es?', 'Toca el dibujo correcto', '👆', 'opcion_multiple', [
            omp('¿Cuál es la casa?',    ['🏠', '🚗', '🐶'], '🏠'),
            omp('¿Cuál es el agua?',    ['💧', '🔥', '🍎'], '💧'),
            omp('¿Cuál es el libro?',   ['📕', '⚽', '🐱'], '📕'),
            omp('¿Cuál es el sol?',     ['☀️', '🌙', '⭐'], '☀️'),
            omp('¿Cuál es el perro?',   ['🐶', '🐱', '🐦'], '🐶'),
        ]),
    ],
],

[
    'slug'  => 'frases-cortas',
    'title' => 'Frases cortas',
    'description' => 'Leer frases de pocas palabras y entender qué dicen, con apoyo visual.',
    'objective' => 'Comprender oraciones simples y relacionarlas con una imagen.',
    'icon' => '💬', 'nivel' => 'primaria-media', 'bloque' => 'lectura-facil',
    'duracion' => 11, 'tags' => ['lectura', 'comprension'],
    'estaciones' => [

        est('¿Qué dice la frase?', 'Una frase, un dibujo', '👀', 'opcion_multiple', [
            omp('«El gato duerme.» ¿Qué hace el gato?',   ['Duerme', 'Come', 'Corre'], 'Duerme', '🐱'),
            omp('«El niño come pan.» ¿Qué come?',         ['Pan', 'Sopa', 'Fruta'], 'Pan', '🍞'),
            omp('«Llueve mucho.» ¿Qué tiempo hace?',      ['Llueve', 'Hace sol', 'Nieva'], 'Llueve', '🌧️'),
            omp('«Ana lee un libro.» ¿Qué hace Ana?',     ['Lee', 'Escribe', 'Salta'], 'Lee', '📖'),
            omp('«El perro corre.» ¿Qué hace el perro?',  ['Corre', 'Duerme', 'Ladra'], 'Corre', '🐕'),
        ]),

        est('Completa la frase', '¿Qué palabra falta?', '🧩', 'opcion_multiple', [
            omp('El sol da ___.',        ['luz', 'agua', 'frío'], 'luz', '☀️'),
            omp('El pez vive en el ___.', ['agua', 'árbol', 'cielo'], 'agua', '🐟'),
            omp('Como con el ___.',      ['tenedor', 'zapato', 'libro'], 'tenedor', '🍴'),
            omp('Duermo en la ___.',     ['cama', 'silla', 'mesa'], 'cama', '🛏️'),
            omp('Escribo con el ___.',   ['lápiz', 'plato', 'reloj'], 'lápiz', '✏️'),
        ]),

        est('Verdadero o falso', 'Frases fáciles de comprobar', '✅', 'seleccion_imagenes',
            conTitulo('Toca las frases que son verdad', 'Piensa bien cada una', [
                ['e' => '☀️', 'n' => 'El sol da luz',       'ok' => true],
                ['e' => '🐟', 'n' => 'El pez vive en el agua', 'ok' => true],
                ['e' => '🐘', 'n' => 'El elefante vuela',   'ok' => false],
                ['e' => '🌳', 'n' => 'El árbol es una planta', 'ok' => true],
                ['e' => '🪨', 'n' => 'La piedra come',      'ok' => false],
                ['e' => '💧', 'n' => 'El agua se puede beber', 'ok' => true],
                ['e' => '🚗', 'n' => 'El carro está vivo',  'ok' => false],
                ['e' => '🐦', 'n' => 'El pájaro tiene alas', 'ok' => true],
            ])),

        est('Une la frase con su dibujo', 'Relaciona', '🔗', 'emparejar', [
            ['e' => '🌧️', 'w' => 'Está lloviendo'],
            ['e' => '😴', 'w' => 'Tengo sueño'],
            ['e' => '🍽️', 'w' => 'Tengo hambre'],
            ['e' => '💧', 'w' => 'Tengo sed'],
            ['e' => '🥶', 'w' => 'Tengo frío'],
            ['e' => '😀', 'w' => 'Estoy contento'],
        ]),
    ],
],


// =====================================================================
//  BLOQUE · RUTINAS Y ANTICIPACIÓN
// =====================================================================

[
    'slug'  => 'mi-dia-paso-a-paso',
    'title' => 'Mi día paso a paso',
    'description' => 'Secuencias visuales de las rutinas diarias: saber qué viene después tranquiliza.',
    'objective' => 'Ordenar secuencias de rutinas cotidianas con apoyo visual.',
    'icon' => '📅', 'nivel' => 'primaria-inicial', 'bloque' => 'rutinas-y-anticipacion',
    // `anticipacion` es etiqueta de APOYO (ver `inclusion.php`): dice a
    // quién le quita un obstáculo, no de qué trata la actividad.
    'duracion' => 10, 'tags' => ['secuencias', 'autocuidado', 'atencion', 'anticipacion'],
    'estaciones' => [

        est('La mañana', 'Ordena lo que haces al levantarte', '🌅', 'ordenar_secuencia', [
            'title' => 'Ordena la rutina de la mañana',
            'items' => ['⏰ Despertar', '🚿 Bañarse', '👕 Vestirse', '🥣 Desayunar', '🎒 Salir'],
        ]),

        est('El colegio', 'Ordena la jornada', '🏫', 'ordenar_secuencia', [
            'title' => 'Ordena el día en el colegio',
            'items' => ['🚪 Llegar', '📚 Clases', '⚽ Recreo', '🍽️ Almuerzo', '🏠 Volver a casa'],
        ]),

        est('La noche', 'Ordena lo que haces antes de dormir', '🌙', 'ordenar_secuencia', [
            'title' => 'Ordena la rutina de la noche',
            'items' => ['🍲 Cenar', '🦷 Cepillarse', '👕 Ponerse la pijama', '📖 Leer un cuento', '😴 Dormir'],
        ]),

        est('¿Qué viene después?', 'Anticipar el siguiente paso', '➡️', 'opcion_multiple', [
            omp('Después de despertarme, ¿qué hago?',   ['Me baño', 'Me duermo', 'Ceno'], 'Me baño', '⏰'),
            omp('Después de comer, ¿qué hago?',         ['Lavo mi plato', 'Como otra vez', 'Me acuesto en la mesa'], 'Lavo mi plato', '🍽️'),
            omp('Antes de dormir, ¿qué hago?',          ['Me cepillo los dientes', 'Desayuno', 'Salgo a jugar'], 'Me cepillo los dientes', '🦷'),
            omp('Después de jugar afuera, ¿qué hago?',  ['Me lavo las manos', 'Como sin lavarme', 'Me acuesto sucio'], 'Me lavo las manos', '🧼'),
            omp('Antes de salir de casa, ¿qué reviso?', ['Que llevo mis cosas', 'Nada', 'La televisión'], 'Que llevo mis cosas', '🎒'),
        ]),
    ],
],

[
    'slug'  => 'agenda-visual',
    'title' => 'Agenda visual',
    'description' => 'Organizar la semana y las tareas con imágenes en vez de listas de texto.',
    'objective' => 'Planificar tareas y tiempos usando apoyos visuales y secuencias.',
    'icon' => '🗓️', 'nivel' => 'primaria-media', 'bloque' => 'rutinas-y-anticipacion',
    'duracion' => 11, 'tags' => ['secuencias', 'atencion', 'anticipacion', 'organizacion'],
    'estaciones' => [

        est('Los días de la semana', 'Ordena la semana', '📆', 'ordenar_secuencia', [
            'title' => 'Ordena los días de la semana',
            'items' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes'],
        ]),

        est('Primero lo importante', 'Ordenar las tareas', '🎯', 'opcion_multiple', [
            omp('Tengo tarea y quiero jugar. ¿Qué hago primero?', ['La tarea', 'Jugar', 'Nada'], 'La tarea'),
            omp('¿Qué ayuda a no olvidar una tarea?',   ['Anotarla o dibujarla en un lugar visible', 'Confiar en la memoria', 'No hacerla'], 'Anotarla o dibujarla en un lugar visible'),
            omp('Si una tarea es muy grande, ¿qué hago?', ['La parto en pasos pequeños', 'La dejo para el final', 'No la hago'], 'La parto en pasos pequeños'),
            omp('¿Sirve tener un lugar fijo para estudiar?', ['Sí, ayuda a concentrarse', 'No', 'Da igual'], 'Sí, ayuda a concentrarse'),
            omp('¿Qué hago cuando termino una tarea?',  ['La marco como hecha', 'La olvido', 'Empiezo otra sin revisar'], 'La marco como hecha'),
        ]),

        est('Antes, ahora, después', 'Ubicarse en el tiempo', '⏳', 'opcion_multiple', [
            omp('¿Qué día viene después del lunes?',    ['Martes', 'Domingo', 'Viernes'], 'Martes'),
            omp('¿Qué viene antes del almuerzo?',       ['El desayuno', 'La cena', 'Dormir'], 'El desayuno'),
            omp('Si hoy es miércoles, mañana será…',    ['Jueves', 'Martes', 'Lunes'], 'Jueves'),
            omp('¿Cuántos días tiene una semana?',      ['7', '5', '10'], '7'),
            omp('¿Qué días no hay colegio normalmente?', ['Sábado y domingo', 'Lunes y martes', 'Ninguno'], 'Sábado y domingo'),
        ]),

        est('Memoria de la rutina', 'Encuentra las parejas', '🧠', 'memoria',
            ['⏰', '🚿', '🎒', '📚', '🍽️', '😴']),
    ],
],


// =====================================================================
//  BLOQUE · UN PASO A LA VEZ
// =====================================================================

[
    'slug'  => 'contar-paso-a-paso',
    'title' => 'Contar paso a paso',
    'description' => 'Los números del 1 al 20 con objetos que se pueden contar de uno en uno.',
    'objective' => 'Consolidar el conteo y la correspondencia número-cantidad sin presión de tiempo.',
    'icon' => '🔢', 'nivel' => 'primaria-inicial', 'bloque' => 'un-paso-a-la-vez',
    'duracion' => 11, 'tags' => ['calculo', 'atencion', 'observacion'],
    'estaciones' => [

        est('Cuenta los objetos', 'Uno por uno, sin prisa', '👆', 'operacion', [
            ['op' => 'contar', 'a' => 3,  'resultado' => 3],
            ['op' => 'contar', 'a' => 5,  'resultado' => 5],
            ['op' => 'contar', 'a' => 8,  'resultado' => 8],
            ['op' => 'contar', 'a' => 10, 'resultado' => 10],
            ['op' => 'contar', 'a' => 12, 'resultado' => 12],
        ]),

        est('Sumar contando', 'Se puede resolver contando los dibujos', '➕', 'operacion', [
            ['op' => 'suma', 'a' => 2, 'b' => 1, 'resultado' => 3],
            ['op' => 'suma', 'a' => 3, 'b' => 2, 'resultado' => 5],
            ['op' => 'suma', 'a' => 4, 'b' => 3, 'resultado' => 7],
            ['op' => 'suma', 'a' => 5, 'b' => 4, 'resultado' => 9],
            ['op' => 'suma', 'a' => 6, 'b' => 4, 'resultado' => 10],
        ]),

        est('El número que falta', 'Series de uno en uno', '🔢', 'secuencia_numerica', [
            serie([1, 2, null, 4, 5]),
            serie([5, 6, null, 8, 9]),
            serie([10, 11, null, 13, 14]),
            serie([2, 4, null, 8, 10]),
            serie([15, 16, null, 18, 19]),
        ]),

        est('Cada número, su cantidad', 'Une el número con los objetos', '🔗', 'emparejar', [
            ['e' => '1️⃣', 'w' => 'Uno'],
            ['e' => '2️⃣', 'w' => 'Dos'],
            ['e' => '3️⃣', 'w' => 'Tres'],
            ['e' => '4️⃣', 'w' => 'Cuatro'],
            ['e' => '5️⃣', 'w' => 'Cinco'],
            ['e' => '6️⃣', 'w' => 'Seis'],
        ]),
    ],
],

[
    'slug'  => 'el-cuerpo-paso-a-paso',
    'title' => 'El cuerpo paso a paso',
    'description' => 'Las partes del cuerpo y para qué sirve cada una, con dibujo en cada pregunta.',
    'objective' => 'Reconocer partes del cuerpo y su función mediante apoyo visual directo.',
    'icon' => '🧍', 'nivel' => 'primaria-inicial', 'bloque' => 'un-paso-a-la-vez',
    'duracion' => 10, 'tags' => ['cuerpo', 'vocabulario', 'observacion'],
    'estaciones' => [

        est('Las partes del cuerpo', 'Une cada parte con su nombre', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'Ojo'],
            ['e' => '👂', 'w' => 'Oreja'],
            ['e' => '👃', 'w' => 'Nariz'],
            ['e' => '👄', 'w' => 'Boca'],
            ['e' => '✋', 'w' => 'Mano'],
            ['e' => '🦶', 'w' => 'Pie'],
        ]),

        est('¿Para qué sirve?', 'Cada parte tiene su función', '💡', 'opcion_multiple', [
            omp('¿Para qué sirven los ojos?',   ['Para ver', 'Para oír', 'Para oler'], 'Para ver', '👁️'),
            omp('¿Para qué sirven las orejas?', ['Para oír', 'Para ver', 'Para caminar'], 'Para oír', '👂'),
            omp('¿Para qué sirve la nariz?',    ['Para oler', 'Para ver', 'Para escribir'], 'Para oler', '👃'),
            omp('¿Para qué sirven las manos?',  ['Para tocar y agarrar', 'Para oír', 'Para ver'], 'Para tocar y agarrar', '✋'),
            omp('¿Para qué sirven los pies?',   ['Para caminar', 'Para ver', 'Para hablar'], 'Para caminar', '🦶'),
        ]),

        est('Cuidar mi cuerpo', 'Hábitos con dibujo', '🧼', 'seleccion_imagenes',
            conTitulo('Toca lo que cuida tu cuerpo', 'Mira bien cada dibujo', [
                ['e' => '🧼', 'n' => 'Lavarse las manos', 'ok' => true],
                ['e' => '🦷', 'n' => 'Cepillarse',        'ok' => true],
                ['e' => '💧', 'n' => 'Tomar agua',        'ok' => true],
                ['e' => '🍬', 'n' => 'Solo comer dulces', 'ok' => false],
                ['e' => '😴', 'n' => 'Dormir bien',       'ok' => true],
                ['e' => '🌙', 'n' => 'Trasnochar',        'ok' => false],
            ])),

        est('Memoria del cuerpo', 'Encuentra las parejas', '🧠', 'memoria',
            ['👁️', '👂', '👃', '✋', '🦶', '👄']),
    ],
],

[
    'slug'  => 'los-colores-y-las-formas',
    'title' => 'Los colores y las formas',
    'description' => 'Reconocer colores y formas con opciones que se distinguen por nombre, no solo por color.',
    'objective' => 'Identificar colores y formas básicas con apoyos que no dependen de la visión cromática.',
    'icon' => '🔶', 'nivel' => 'primaria-inicial', 'bloque' => 'un-paso-a-la-vez',
    'duracion' => 10, 'tags' => ['observacion', 'clasificacion', 'vocabulario'],
    'estaciones' => [

        /*
         * Las opciones dicen el NOMBRE del color, no son muestras de color.
         * Un ejercicio donde hay que elegir «el cuadrado de este color»
         * entre tres muestras es imposible para quien no distingue verde de
         * rojo, y el niño no falla por no saber: falla por el diseño.
         */
        est('Los colores', 'La respuesta va con su nombre', '🎨', 'opcion_multiple', [
            omp('¿De qué color es el cielo despejado?',  ['Azul', 'Rojo', 'Café'], 'Azul', '☁️'),
            omp('¿De qué color es el pasto?',            ['Verde', 'Morado', 'Naranja'], 'Verde', '🌿'),
            omp('¿De qué color es el sol que dibujamos?', ['Amarillo', 'Azul', 'Negro'], 'Amarillo', '☀️'),
            omp('¿De qué color es un tomate maduro?',    ['Rojo', 'Azul', 'Blanco'], 'Rojo', '🍅'),
            omp('¿De qué color es la nieve?',            ['Blanco', 'Negro', 'Verde'], 'Blanco', '❄️'),
        ]),

        est('Las formas', 'Cada forma con su nombre', '🔷', 'opcion_multiple', [
            omp('¿Qué forma tiene una pelota?',    ['Redonda', 'Cuadrada', 'Triangular'], 'Redonda', '⚽'),
            omp('¿Qué forma tiene una ventana?',   ['Cuadrada', 'Redonda', 'Triangular'], 'Cuadrada', '🪟'),
            omp('¿Qué forma tiene una porción de pizza?', ['Triangular', 'Redonda', 'Cuadrada'], 'Triangular', '🍕'),
            omp('¿Qué forma tiene un reloj de pared?', ['Redonda', 'Triangular', 'Estrellada'], 'Redonda', '🕐'),
            omp('¿Cuántos lados tiene un cuadrado?', ['4', '3', '5'], '4'),
        ]),

        est('Grande y pequeño', 'Comparar tamaños', '📏', 'opcion_multiple', [
            omp('¿Cuál es más grande?',    ['🐘', '🐭', '🐜'], '🐘'),
            omp('¿Cuál es más pequeño?',   ['🐜', '🐘', '🐴'], '🐜'),
            omp('¿Cuál es más alto?',      ['🌳', '🌱', '🍄'], '🌳'),
            omp('¿Cuál es más pesado?',    ['🪨', '🪶', '🎈'], '🪨'),
            omp('¿Cuál es más largo?',     ['🐍', '🐛', '🐞'], '🐍'),
        ]),

        est('Une forma y objeto', 'Relaciona', '🔗', 'emparejar', [
            ['e' => '⚽', 'w' => 'Redondo'],
            ['e' => '📦', 'w' => 'Cuadrado'],
            ['e' => '🍕', 'w' => 'Triangular'],
            ['e' => '📏', 'w' => 'Alargado'],
            ['e' => '⭐', 'w' => 'Estrellado'],
            ['e' => '🥚', 'w' => 'Ovalado'],
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'las-letras-paso-a-paso',
    'title' => 'Las letras paso a paso',
    'description' => 'Reconocer letras y sílabas sin prisa, con dibujo y sonido en cada ejercicio.',
    'objective' => 'Afianzar el reconocimiento de letras y sílabas con apoyo visual y auditivo.',
    'icon' => '🔡', 'nivel' => 'primaria-inicial', 'bloque' => 'un-paso-a-la-vez',
    'duracion' => 11, 'tags' => ['lectura', 'atencion', 'vocabulario'],
    'estaciones' => [

        est('¿Con qué letra empieza?', 'Mira el dibujo y escucha', '👂', 'opcion_multiple', [
            omp('¿Con qué letra empieza CASA?',    ['C', 'S', 'M'], 'C', '🏠'),
            omp('¿Con qué letra empieza PERRO?',   ['P', 'B', 'R'], 'P', '🐶'),
            omp('¿Con qué letra empieza SOL?',     ['S', 'C', 'L'], 'S', '☀️'),
            omp('¿Con qué letra empieza MESA?',    ['M', 'N', 'S'], 'M', '🪑'),
            omp('¿Con qué letra empieza LUNA?',    ['L', 'N', 'U'], 'L', '🌙'),
        ]),

        est('Cuenta las sílabas', 'Da una palmada por sílaba', '👏', 'opcion_multiple', [
            omp('¿Cuántas sílabas tiene SOL?',       ['1', '2', '3'], '1', '☀️'),
            omp('¿Cuántas sílabas tiene CA-SA?',     ['2', '1', '3'], '2', '🏠'),
            omp('¿Cuántas sílabas tiene PE-LO-TA?',  ['3', '2', '4'], '3', '⚽'),
            omp('¿Cuántas sílabas tiene MA-RI-PO-SA?', ['4', '3', '5'], '4', '🦋'),
            omp('¿Cuántas sílabas tiene PAN?',       ['1', '2', '3'], '1', '🍞'),
        ]),

        est('Arma la palabra', 'Ordena las sílabas', '🧩', 'puzle_silabas', [
            ['e' => '🏠', 'syls' => ['CA', 'SA'],       'w' => 'CASA'],
            ['e' => '🌙', 'syls' => ['LU', 'NA'],       'w' => 'LUNA'],
            ['e' => '🐶', 'syls' => ['PE', 'RRO'],      'w' => 'PERRO'],
            ['e' => '⚽', 'syls' => ['PE', 'LO', 'TA'], 'w' => 'PELOTA'],
            ['e' => '🦋', 'syls' => ['MA', 'RI', 'PO', 'SA'], 'w' => 'MARIPOSA'],
        ]),

        est('Escucha y repite', 'Di la palabra en voz alta', '🔊', 'pronunciacion', [
            ['e' => '🏠', 'w' => 'Casa'],
            ['e' => '☀️', 'w' => 'Sol'],
            ['e' => '🌙', 'w' => 'Luna'],
            ['e' => '🐶', 'w' => 'Perro'],
            ['e' => '🍞', 'w' => 'Pan'],
        ]),
    ],
],

[
    'slug'  => 'pictogramas-del-dia',
    'title' => 'Pictogramas del día',
    'description' => 'Comunicarse con imágenes: pedir, avisar y responder sin necesidad de escribir.',
    'objective' => 'Interpretar y usar pictogramas como sistema alternativo de comunicación.',
    'icon' => '🪧', 'nivel' => 'primaria-inicial', 'bloque' => 'lectura-facil',
    'duracion' => 10, 'tags' => ['comprension', 'convivencia', 'observacion'],
    'estaciones' => [

        est('Pedir con imágenes', 'Cada dibujo dice algo', '🙋', 'opcion_multiple', [
            omp('¿Qué imagen usarías para pedir agua?', ['💧', '📕', '⚽'], '💧'),
            omp('¿Qué imagen usarías para ir al baño?', ['🚻', '🍽️', '🎨'], '🚻'),
            omp('¿Qué imagen usarías para decir que tienes hambre?', ['🍽️', '😴', '🎒'], '🍽️'),
            omp('¿Qué imagen usarías para decir que estás cansado?', ['😴', '😀', '🏃'], '😴'),
            omp('¿Qué imagen usarías para pedir ayuda?', ['🆘', '🎉', '📚'], '🆘'),
        ]),

        est('Señales que hay que conocer', 'Comunicación visual universal', '🚦', 'opcion_multiple', [
            omp('¿Qué significa esta señal en el piso?', ['Cuidado, piso mojado', 'Zona de juegos', 'Salida'], 'Cuidado, piso mojado', '⚠️'),
            omp('¿Qué indica una flecha verde de salida?', ['Por dónde evacuar', 'Dónde entrar', 'Dónde comer'], 'Por dónde evacuar', '🚪'),
            omp('¿Qué significa este símbolo?',        ['Accesible para silla de ruedas', 'Prohibido pasar', 'Zona de silencio'], 'Accesible para silla de ruedas', '♿'),
            omp('¿Qué significa el símbolo de reciclaje?', ['Que el material se puede reciclar', 'Que es basura', 'Que es nuevo'], 'Que el material se puede reciclar', '♻️'),
            omp('¿Por qué son útiles los símbolos?',   ['Los entiende cualquiera sin leer', 'Son bonitos', 'Ocupan poco'], 'Los entiende cualquiera sin leer'),
        ]),

        est('Emociones con dibujo', 'Decir cómo me siento', '💛', 'emparejar', [
            ['e' => '😀', 'w' => 'Estoy bien'],
            ['e' => '😢', 'w' => 'Estoy triste'],
            ['e' => '😠', 'w' => 'Estoy enojado'],
            ['e' => '😨', 'w' => 'Tengo miedo'],
            ['e' => '😴', 'w' => 'Tengo sueño'],
            ['e' => '🤒', 'w' => 'Me siento mal'],
        ]),

        est('Ordena con imágenes', 'Una secuencia sin texto', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para lavarse las manos',
            'items' => ['💧 Mojar', '🧼 Jabón', '👐 Frotar', '🚿 Enjuagar', '🧻 Secar'],
        ]),
    ],
],

[
    'slug'  => 'numeros-con-apoyo-visual',
    'title' => 'Números con apoyo visual',
    'description' => 'Comparar, ordenar y sumar viendo siempre las cantidades dibujadas.',
    'objective' => 'Comparar y operar cantidades pequeñas con representación gráfica permanente.',
    'icon' => '🧮', 'nivel' => 'primaria-media', 'bloque' => 'un-paso-a-la-vez',
    'duracion' => 11, 'tags' => ['calculo', 'observacion', 'atencion'],
    'estaciones' => [

        est('¿Cuántos hay?', 'Cuenta los objetos', '👆', 'operacion', [
            ['op' => 'contar', 'a' => 4,  'resultado' => 4],
            ['op' => 'contar', 'a' => 6,  'resultado' => 6],
            ['op' => 'contar', 'a' => 9,  'resultado' => 9],
            ['op' => 'contar', 'a' => 11, 'resultado' => 11],
            ['op' => 'contar', 'a' => 14, 'resultado' => 14],
        ]),

        est('Más o menos', 'Compara mirando', '⚖️', 'opcion_multiple', [
            omp('¿Cuál es mayor, 3 o 8?',    ['8', '3', 'Iguales'], '8'),
            omp('¿Cuál es menor, 5 o 2?',    ['2', '5', 'Iguales'], '2'),
            omp('¿Cuál es mayor, 10 o 7?',   ['10', '7', 'Iguales'], '10'),
            omp('¿Cuál es menor, 12 o 15?',  ['12', '15', 'Iguales'], '12'),
            omp('¿Cuál está entre 4 y 6?',   ['5', '3', '7'], '5'),
        ]),

        est('Sumas con dibujos', 'Se pueden contar', '➕', 'operacion', [
            ['op' => 'suma', 'a' => 1, 'b' => 2, 'resultado' => 3],
            ['op' => 'suma', 'a' => 3, 'b' => 3, 'resultado' => 6],
            ['op' => 'suma', 'a' => 4, 'b' => 2, 'resultado' => 6],
            ['op' => 'suma', 'a' => 5, 'b' => 3, 'resultado' => 8],
            ['op' => 'suma', 'a' => 6, 'b' => 3, 'resultado' => 9],
        ]),

        est('Restas con dibujos', 'Se ven los que se quitan', '➖', 'operacion', [
            ['op' => 'resta', 'a' => 5,  'b' => 2, 'resultado' => 3],
            ['op' => 'resta', 'a' => 7,  'b' => 3, 'resultado' => 4],
            ['op' => 'resta', 'a' => 9,  'b' => 4, 'resultado' => 5],
            ['op' => 'resta', 'a' => 10, 'b' => 6, 'resultado' => 4],
            ['op' => 'resta', 'a' => 8,  'b' => 8, 'resultado' => 0],
        ]),
    ],
],

[
    'slug'  => 'pedir-ayuda',
    'title' => 'Pedir ayuda',
    'description' => 'A quién acudir, cómo decirlo y por qué pedir ayuda no es un fracaso.',
    'objective' => 'Reconocer cuándo y cómo solicitar apoyo en distintas situaciones.',
    'icon' => '🙋', 'nivel' => 'primaria-media', 'bloque' => 'rutinas-y-anticipacion',
    'duracion' => 11, 'tags' => ['convivencia', 'emociones', 'autorregulacion', 'social'],
    'estaciones' => [

        est('¿Cuándo pido ayuda?', 'No hay que esperar a estar mal', '🤔', 'opcion_multiple', [
            omp('No entiendo la tarea. ¿Qué hago?',   ['Pregunto', 'La dejo en blanco', 'Copio'], 'Pregunto'),
            omp('Me duele algo desde ayer. ¿Qué hago?', ['Le digo a un adulto', 'Espero a que pase', 'No digo nada'], 'Le digo a un adulto'),
            omp('Alguien me está molestando. ¿Qué hago?', ['Se lo cuento a un adulto de confianza', 'Aguanto', 'Me vengo'], 'Se lo cuento a un adulto de confianza'),
            omp('¿Pedir ayuda significa que no sirvo?', ['No, significa que quiero resolverlo', 'Sí', 'A veces'], 'No, significa que quiero resolverlo'),
            omp('¿Cuándo es urgente pedir ayuda?',    ['Cuando alguien está en peligro', 'Nunca', 'Solo en el colegio'], 'Cuando alguien está en peligro'),
        ]),

        est('¿A quién le pido?', 'Identificar a las personas de confianza', '👨‍👩‍👧', 'opcion_multiple', [
            omp('¿Quién es un adulto de confianza?',  ['Alguien que me cuida y me escucha', 'Cualquier desconocido', 'Nadie'], 'Alguien que me cuida y me escucha'),
            omp('En el colegio puedo acudir a…',      ['Mi profesor o la orientadora', 'Nadie', 'Solo a mis amigos'], 'Mi profesor o la orientadora'),
            omp('Si el primer adulto no me ayuda, ¿qué hago?', ['Busco a otro hasta que alguien actúe', 'Me rindo', 'Me callo'], 'Busco a otro hasta que alguien actúe'),
            omp('¿Puedo pedirle ayuda a un compañero?', ['Sí, para muchas cosas', 'Nunca', 'Solo para tareas'], 'Sí, para muchas cosas'),
            omp('¿Cuál es el número de emergencias en Colombia?', ['123', '911', '060'], '123'),
        ]),

        est('¿Cómo lo digo?', 'Explicar con claridad', '💬', 'opcion_multiple', [
            omp('¿Qué es mejor decir?',              ['«No entiendo el paso dos»', '«No entiendo nada»', 'Nada'], '«No entiendo el paso dos»'),
            omp('¿Sirve señalar exactamente qué no entiendo?', ['Sí, así me ayudan mejor', 'No', 'Da igual'], 'Sí, así me ayudan mejor'),
            omp('Si me da vergüenza preguntar en clase, puedo…', ['Preguntar al final o por escrito', 'No preguntar nunca', 'Fingir que entendí'], 'Preguntar al final o por escrito'),
            omp('¿Es probable que otros tengan la misma duda?', ['Sí, casi siempre', 'No', 'Nunca'], 'Sí, casi siempre'),
            omp('¿Qué digo primero en una emergencia?', ['Dónde estoy y qué pasa', 'Mi edad', 'Nada'], 'Dónde estoy y qué pasa'),
        ]),

        est('Pedir ayuda', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Conviene pedir ayuda aquí?', 'Responde rápido', [
                ['e' => '🤕', 'n' => 'Me lastimé y me duele',    'ok' => true],
                ['e' => '✏️', 'n' => 'Se me cayó el lápiz',      'ok' => false],
                ['e' => '😰', 'n' => 'Alguien me amenaza',       'ok' => true],
                ['e' => '📚', 'n' => 'No entiendo la explicación', 'ok' => true],
                ['e' => '🎨', 'n' => 'No sé qué color usar',     'ok' => false],
                ['e' => '🔥', 'n' => 'Veo humo en el salón',     'ok' => true],
            ])),
    ],
],

],

'reasignar' => [],

];
