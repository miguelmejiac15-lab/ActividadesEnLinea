<?php
/**
 * inclusion.php — Apoyos para atención, autorregulación y vida social
 *
 * Amplía «Aprender sin Barreras» con lo que le faltaba. La categoría ya
 * cubría el ACCESO al contenido —ver, oír, leer, ir paso a paso— pero no
 * cubría nada de lo que más cuesta a un niño con TDAH o con autismo:
 * sostener la atención, frenar el impulso, organizarse solo, encajar un
 * cambio de planes, leer una cara o soportar un ruido.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NINGÚN BLOQUE SE LLAMA «TDAH» NI «ASPERGER»
 * ─────────────────────────────────────────────────────────────────────
 *
 * Los bloques se llaman por el apoyo que dan —«Atención y Foco», «Parar y
 * Pensar», «Cuando Algo Cambia»— y no por el diagnóstico al que apuntan.
 * No es un eufemismo, son tres razones concretas:
 *
 * 1. En esta plataforma un docente ASIGNA actividades a un curso. Si el
 *    bloque se llamara «Actividades para TDAH», el niño al que se la
 *    asignan vería el nombre, y sus compañeros también. Eso convierte una
 *    ayuda en una etiqueta pública sobre un menor.
 *
 * 2. Un diagnóstico no dice qué necesita alguien. Dos niños con el mismo
 *    informe necesitan cosas distintas, y muchísimos niños sin ningún
 *    diagnóstico necesitan exactamente esto mismo: a nadie le sobra
 *    aprender a parar antes de responder.
 *
 * 3. Nosotros no diagnosticamos. Poner el nombre de un trastorno sobre un
 *    juego insinúa que jugarlo dice algo del niño, y no lo dice.
 *
 * Las palabras TDAH y autismo SÍ aparecen —y tienen que aparecer— en la
 * guía para adultos (`actividades/apoyos.php`), que es donde una familia
 * o un docente busca. Los adultos buscan por diagnóstico; a los niños se
 * les nombra por lo que están aprendiendo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA QUE MÁS CONDICIONA ESTE ARCHIVO
 * ─────────────────────────────────────────────────────────────────────
 *
 * **Nunca se califica una preferencia ni una vivencia.**
 *
 * El motor puntúa: toda pregunta tiene una respuesta correcta y las demás
 * son fallos. Por eso aquí NO se pregunta nunca «¿qué te molesta a ti?»
 * ni «¿te cuesta concentrarte?». Un niño al que el ruido de verdad le
 * duele contestaría la verdad y el juego le diría que se equivoca.
 *
 * Todo se pregunta en tercera persona y sobre QUÉ HACER: «a Leo le molesta
 * el ruido del comedor, ¿qué puede hacer?». Eso sí tiene respuestas
 * mejores y peores, y se puede evaluar sin juzgar a quien juega.
 *
 * De la misma regla salen otras dos:
 *
 * · Nada de contrarreloj en los bloques de calma y de sentidos. Meter
 *   prisa en un ejercicio sobre regularse es enseñar lo contrario.
 * · Ninguna actividad presenta una forma de ser como un error a corregir.
 *   Moverse mucho no está mal; no saber qué hacer con eso, sí cuesta.
 */

declare(strict_types=1);

return [

/*
 * La categoría se repite tal cual la tiene «dua-primaria.php»: el
 * sembrador hace UPDATE sobre ella, así que cualquier diferencia aquí la
 * cambiaría sin querer.
 */
'categoria' => [
    'slug'       => 'dua',
    'name'       => 'Aprender sin Barreras',
    'tagline'    => 'El mismo contenido, por el camino que a cada uno le sirve',
    'icon'       => '♿',
    'color'      => '#5e35b1',
    'sort_order' => 8,
],

'bloques' => [
    ['slug' => 'atencion-y-foco', 'name' => 'Atención y Foco', 'icon' => '🎯', 'sort_order' => 7,
     'description' => 'Encontrar lo que importa entre todo lo demás, y no soltarlo a mitad de camino.'],

    ['slug' => 'parar-y-pensar', 'name' => 'Parar y Pensar', 'icon' => '✋', 'sort_order' => 8,
     'description' => 'La respuesta rápida no siempre es la buena. Aquí se entrena esperar un segundo.'],

    ['slug' => 'organizarme-solo', 'name' => 'Organizarme Solo', 'icon' => '🎒', 'sort_order' => 9,
     'description' => 'Planear, acordarse y empezar: lo que hace falta antes de hacer la tarea.'],

    ['slug' => 'calma-y-cuerpo', 'name' => 'Calma y Cuerpo', 'icon' => '🌬️', 'sort_order' => 10,
     'description' => 'Notar lo que avisa el cuerpo y saber qué hacer con eso. Sin reloj y sin prisa.'],

    ['slug' => 'entender-a-los-demas', 'name' => 'Entender a los Demás', 'icon' => '🫂', 'sort_order' => 11,
     'description' => 'Caras, tonos y turnos: lo que dicen las personas sin decirlo con palabras.'],

    ['slug' => 'cuando-algo-cambia', 'name' => 'Cuando Algo Cambia', 'icon' => '🔄', 'sort_order' => 12,
     'description' => 'El plan cambió. Qué avisa que va a pasar y qué se puede hacer entonces.'],

    ['slug' => 'lo-que-no-se-dice', 'name' => 'Lo que No se Dice', 'icon' => '💬', 'sort_order' => 13,
     'description' => 'Frases que no significan lo que dicen, y reglas que nadie explica en voz alta.'],

    ['slug' => 'mis-sentidos', 'name' => 'Mis Sentidos', 'icon' => '👂', 'sort_order' => 14,
     'description' => 'Ruido, luz y texturas. Qué se puede hacer cuando el ambiente incomoda.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · ATENCIÓN Y FOCO
//
//  Atención selectiva (elegir el objetivo entre distractores) y atención
//  sostenida (no soltarlo). La dificultad sube AÑADIENDO RUIDO, no
//  acortando el tiempo: el reloj mide otra cosa distinta de la atención.
// =====================================================================

[
    'slug'  => 'encuentra-lo-que-te-pido',
    'title' => 'Encuentra lo que te pido',
    'description' => 'Buscar una cosa concreta cuando alrededor hay muchas cosas parecidas.',
    'objective' => 'Sostener un objetivo de búsqueda y filtrar los distractores parecidos.',
    'icon' => '🔎', 'nivel' => 'primaria-inicial', 'bloque' => 'atencion-y-foco',
    'duracion' => 9, 'tags' => ['atencion', 'observacion', 'foco'],
    'estaciones' => [

        est('Solo los perros', 'Hay otros animales cerca', '🐶', 'seleccion_imagenes',
            conTitulo('Toca todos los perros', 'Los demás animales se quedan fuera', [
                ['e' => '🐶', 'n' => 'Perro',   'ok' => true],
                ['e' => '🐕', 'n' => 'Perro',   'ok' => true],
                ['e' => '🐱', 'n' => 'Gato',    'ok' => false],
                ['e' => '🦮', 'n' => 'Perro',   'ok' => true],
                ['e' => '🐰', 'n' => 'Conejo',  'ok' => false],
                ['e' => '🐩', 'n' => 'Perro',   'ok' => true],
                ['e' => '🐺', 'n' => 'Lobo',    'ok' => false],
                ['e' => '🐈', 'n' => 'Gato',    'ok' => false],
            ])),

        est('Solo lo que se come', 'Ahora hay más cosas', '🍎', 'seleccion_imagenes',
            conTitulo('Toca todo lo que se come', 'Hay cosas que no son comida', [
                ['e' => '🍎', 'n' => 'Manzana',  'ok' => true],
                ['e' => '👟', 'n' => 'Zapato',   'ok' => false],
                ['e' => '🍌', 'n' => 'Banano',   'ok' => true],
                ['e' => '📕', 'n' => 'Libro',    'ok' => false],
                ['e' => '🥕', 'n' => 'Zanahoria', 'ok' => true],
                ['e' => '🪑', 'n' => 'Silla',    'ok' => false],
                ['e' => '🍞', 'n' => 'Pan',      'ok' => true],
                ['e' => '✏️', 'n' => 'Lápiz',    'ok' => false],
                ['e' => '🧀', 'n' => 'Queso',    'ok' => true],
            ])),

        est('¿Cuál es el que pido?', 'Lee bien antes de tocar', '👆', 'opcion_multiple', [
            omp('Toca el animal que VUELA.',        ['🦜 Loro', '🐢 Tortuga', '🐟 Pez', '🐘 Elefante'], '🦜 Loro'),
            omp('Toca la fruta que es AMARILLA.',   ['🍌 Banano', '🍎 Manzana', '🍇 Uvas', '🍉 Sandía'], '🍌 Banano'),
            omp('Toca lo que sirve para ESCRIBIR.', ['✏️ Lápiz', '🥄 Cuchara', '🧦 Media', '🔑 Llave'], '✏️ Lápiz'),
            omp('Toca el que tiene RUEDAS.',        ['🚲 Bicicleta', '🛶 Canoa', '🪁 Cometa', '⛵ Velero'], '🚲 Bicicleta'),
            omp('Toca el que se usa de NOCHE.',     ['🔦 Linterna', '🕶️ Gafas de sol', '🩳 Pantaloneta', '🏖️ Sombrilla'], '🔦 Linterna'),
        ]),

        // `memoria` recibe una lista PLANA de símbolos: el motor duplica
        // cada uno para formar la pareja. Un objeto {e,n} aquí saldría
        // impreso como «[object Object]» en las cartas.
        est('Acuérdate de las parejas', 'Sin prisa, mira bien', '🧩', 'memoria',
            ['🔎', '🎯', '👀', '🧠', '✋', '✅']),
    ],
],

[
    'slug'  => 'sigue-hasta-el-final',
    'title' => 'Sigue hasta el final',
    'description' => 'Cadenas de pasos que hay que terminar sin perder el hilo por el camino.',
    'objective' => 'Sostener la atención en una tarea de varios pasos hasta completarla.',
    'icon' => '🧗', 'nivel' => 'primaria-media', 'bloque' => 'atencion-y-foco',
    'duracion' => 11, 'tags' => ['atencion', 'secuencias', 'foco'],
    'estaciones' => [

        est('El camino del recreo', 'Cinco pasos en orden', '🏃', 'ordenar_secuencia', [
            'title' => 'Ordena lo que pasa desde que suena el timbre',
            'items' => ['🔔 Suena el timbre', '📕 Guardo el cuaderno', '🪑 Empujo la silla',
                        '🚪 Salgo en fila', '⚽ Juego en el patio'],
        ]),

        est('Preparar la mochila', 'Ocho pasos: no te saltes ninguno', '🎒', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se prepara la mochila para mañana',
            'items' => ['📋 Miro el horario', '📚 Saco los libros de hoy', '📖 Meto los de mañana',
                        '📝 Reviso si hay tarea', '✏️ Guardo la cartuchera',
                        '🍎 Pongo la lonchera', '🤐 Cierro la mochila', '🚪 La dejo junto a la puerta'],
        ]),

        est('¿Qué falta para terminar?', 'Casi listo no es listo', '🔚', 'opcion_multiple', [
            omp('Nico lavó el plato y lo dejó en el lavaplatos mojado. ¿Qué le falta?',
                ['Secarlo y guardarlo', 'Nada, ya está', 'Volver a lavarlo'], 'Secarlo y guardarlo'),
            omp('Sara hizo toda la tarea pero la dejó en la mesa. ¿Qué le falta?',
                ['Meterla en la mochila', 'Nada, ya está', 'Hacerla otra vez'], 'Meterla en la mochila'),
            omp('Leo pintó el dibujo y no escribió su nombre. ¿Qué le falta?',
                ['Escribir su nombre', 'Nada, ya está', 'Borrar el dibujo'], 'Escribir su nombre'),
            omp('Ana leyó la pregunta y contestó la primera parte. ¿Qué le falta?',
                ['Contestar la segunda parte', 'Nada, ya está', 'Cambiar de página'], 'Contestar la segunda parte'),
            omp('Tomás guardó los colores pero dejó la mesa llena de recortes. ¿Qué le falta?',
                ['Recoger los recortes', 'Nada, ya está', 'Sacar los colores otra vez'], 'Recoger los recortes'),
        ]),

        est('Sopa de la constancia', 'Seis palabras escondidas', '🔤', 'sopa_letras',
            sopa(['SEGUIR', 'FINAL', 'PASO', 'ORDEN', 'META', 'LISTO'], 10)),
    ],
],

[
    'slug'  => 'no-te-despistes',
    'title' => 'No te despistes',
    'description' => 'Elegir bien cuando alrededor hay cosas que llaman la atención y no sirven.',
    'objective' => 'Ejercitar la atención selectiva: distinguir el dato útil del que distrae.',
    'icon' => '🚦', 'nivel' => 'primaria-media', 'bloque' => 'atencion-y-foco',
    'duracion' => 10, 'tags' => ['atencion', 'logica', 'foco'],
    'estaciones' => [

        est('El dato que sirve', 'Sobran datos a propósito', '📊', 'opcion_multiple', [
            omp('Ana tiene 4 canicas rojas, 3 azules y una camiseta verde. ¿Cuántas canicas tiene?',
                ['7', '8', '4', '3'], '7'),
            omp('El bus sale a las 7. Tarda 20 minutos. El conductor se llama Luis. ¿A qué hora llega?',
                ['A las 7:20', 'A las 7:00', 'A las 8:00'], 'A las 7:20'),
            omp('Hay 5 sillas azules y 2 mesas. Se rompió una silla. ¿Cuántas sillas quedan?',
                ['4', '5', '2', '7'], '4'),
            omp('Leo leyó 3 páginas el lunes y 4 el martes. El libro es rojo. ¿Cuántas leyó?',
                ['7', '3', '4', 'Rojo'], '7'),
            omp('En el patio hay 6 niños jugando y llueve. Llegan 2 más. ¿Cuántos hay?',
                ['8', '6', '2', 'Llueve'], '8'),
        ]),

        est('Lee toda la frase', 'La trampa está al final', '🪤', 'opcion_multiple', [
            omp('Toca el color del CIELO en un día despejado, no el del pasto.',
                ['Azul', 'Verde', 'Café', 'Gris'], 'Azul'),
            omp('Di cuántas patas tiene una ARAÑA, no una hormiga.',
                ['8', '6', '4', '2'], '8'),
            omp('Nombra el animal que da LECHE, no el que da huevos.',
                ['Vaca', 'Gallina', 'Pato', 'Rana'], 'Vaca'),
            omp('Señala lo que se usa para el FRÍO, no para el sol.',
                ['Bufanda', 'Gorra', 'Gafas oscuras', 'Sandalias'], 'Bufanda'),
            omp('Di el mes que va DESPUÉS de marzo, no antes.',
                ['Abril', 'Febrero', 'Enero', 'Mayo'], 'Abril'),
        ]),

        est('¿Ayuda a concentrarse?', 'Decide con calma', '🧘', 'juego_rapido',
            conTitulo('¿Esto ayuda a concentrarse?', 'Piensa y responde', [
                ['e' => '📵', 'n' => 'Guardar el celular lejos',      'ok' => true],
                ['e' => '📺', 'n' => 'Dejar la tele encendida',       'ok' => false],
                ['e' => '🧹', 'n' => 'Despejar la mesa',              'ok' => true],
                ['e' => '🎮', 'n' => 'Tener el juego abierto al lado', 'ok' => false],
                ['e' => '💡', 'n' => 'Buena luz sobre el cuaderno',   'ok' => true],
                ['e' => '⏸️', 'n' => 'Hacer una pausa corta cada rato', 'ok' => true],
                ['e' => '🍬', 'n' => 'Cambiar de tarea cada minuto',  'ok' => false],
                ['e' => '📋', 'n' => 'Escribir qué voy a hacer primero', 'ok' => true],
            ])),
    ],
],


// =====================================================================
//  BLOQUE · PARAR Y PENSAR
//
//  Control del impulso. Aquí la respuesta obvia es casi siempre la
//  equivocada, y eso es deliberado: se entrena el segundo que va entre
//  leer y contestar.
// =====================================================================

[
    'slug'  => 'piensa-antes-de-tocar',
    'title' => 'Piensa antes de tocar',
    'description' => 'Preguntas donde la primera respuesta que se ocurre suele estar mal.',
    'objective' => 'Frenar la respuesta impulsiva y revisar el enunciado antes de contestar.',
    'icon' => '✋', 'nivel' => 'primaria-media', 'bloque' => 'parar-y-pensar',
    'duracion' => 10, 'tags' => ['atencion', 'logica', 'foco'],
    'estaciones' => [

        est('La trampa', 'Léelo dos veces', '🧐', 'opcion_multiple', [
            omp('Un granjero tiene 17 ovejas. Se mueren todas menos 9. ¿Cuántas quedan vivas?',
                ['9', '8', '17', 'Ninguna'], '9'),
            omp('¿Cuánta tierra hay en un hueco de 2 metros de hondo?',
                ['Ninguna: un hueco está vacío', '2 metros', '4 metros'], 'Ninguna: un hueco está vacío'),
            omp('Si en una carrera adelantas al que va SEGUNDO, ¿en qué puesto quedas?',
                ['Segundo', 'Primero', 'Tercero'], 'Segundo'),
            omp('Una docena de huevos son 12. ¿Cuántas docenas hay en 24 huevos?',
                ['2', '24', '12'], '2'),
            omp('Un ladrillo pesa 1 kilo más medio ladrillo. ¿Cuánto pesa el ladrillo entero?',
                ['2 kilos', '1 kilo', 'Medio kilo'], '2 kilos'),
        ]),

        est('Antes de responder', 'Qué conviene hacer primero', '⏳', 'opcion_multiple', [
            omp('Estás en un examen y crees saber la respuesta al leer la primera línea. ¿Qué haces?',
                ['Leo la pregunta completa y luego contesto', 'Contesto ya', 'Paso a la siguiente'],
                'Leo la pregunta completa y luego contesto'),
            omp('Un compañero dice algo que te molesta. ¿Qué conviene hacer primero?',
                ['Respirar y pensar qué decir', 'Contestarle gritando', 'Empujarlo'],
                'Respirar y pensar qué decir'),
            omp('Quieres contar algo pero alguien está hablando. ¿Qué haces?',
                ['Espero mi turno', 'Hablo más fuerte', 'Lo interrumpo'], 'Espero mi turno'),
            omp('Terminaste la tarea en dos minutos. ¿Qué conviene hacer?',
                ['Revisarla antes de entregar', 'Entregar de una', 'Guardarla sin mirar'],
                'Revisarla antes de entregar'),
            omp('Te dan ganas de salir corriendo del salón. ¿Qué es lo mejor?',
                ['Pedir permiso para salir un momento', 'Salir sin avisar', 'Aguantar sin decir nada'],
                'Pedir permiso para salir un momento'),
        ]),

        est('Semáforo de la respuesta', 'Rojo, amarillo, verde', '🚦', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos del semáforo para responder bien',
            'items' => ['🔴 Paro', '🟡 Pienso qué me están pidiendo',
                        '🟡 Miro todas las opciones', '🟢 Contesto', '🔁 Reviso'],
        ]),
    ],
],

[
    'slug'  => 'espera-la-senal',
    'title' => 'Espera la señal',
    'description' => 'Responder solo cuando se cumple la condición, y quedarse quieto si no.',
    'objective' => 'Ejercitar la inhibición: actuar ante una señal y contenerse ante otra.',
    'icon' => '🛑', 'nivel' => 'primaria-inicial', 'bloque' => 'parar-y-pensar',
    'duracion' => 9, 'tags' => ['atencion', 'foco', 'juego'],
    'estaciones' => [

        est('Solo si vuela', 'Toca únicamente los que vuelan', '🦅', 'seleccion_imagenes',
            conTitulo('Toca solo los que vuelan', 'Los demás no se tocan', [
                ['e' => '🦅', 'n' => 'Águila',   'ok' => true],
                ['e' => '🐟', 'n' => 'Pez',      'ok' => false],
                ['e' => '🦋', 'n' => 'Mariposa', 'ok' => true],
                ['e' => '🐢', 'n' => 'Tortuga',  'ok' => false],
                ['e' => '🐝', 'n' => 'Abeja',    'ok' => true],
                ['e' => '🐍', 'n' => 'Serpiente', 'ok' => false],
                ['e' => '🦉', 'n' => 'Búho',     'ok' => true],
                ['e' => '🦀', 'n' => 'Cangrejo', 'ok' => false],
            ])),

        est('Solo si es par', 'Los impares se quedan', '🔢', 'seleccion_imagenes',
            conTitulo('Toca solo los números pares', 'Los impares no', [
                ['e' => '2️⃣', 'n' => 'Dos',    'ok' => true],
                ['e' => '3️⃣', 'n' => 'Tres',   'ok' => false],
                ['e' => '4️⃣', 'n' => 'Cuatro', 'ok' => true],
                ['e' => '5️⃣', 'n' => 'Cinco',  'ok' => false],
                ['e' => '6️⃣', 'n' => 'Seis',   'ok' => true],
                ['e' => '7️⃣', 'n' => 'Siete',  'ok' => false],
                ['e' => '8️⃣', 'n' => 'Ocho',   'ok' => true],
                ['e' => '9️⃣', 'n' => 'Nueve',  'ok' => false],
            ])),

        est('Verde sí, rojo no', 'Decide cada uno', '🟢', 'juego_rapido',
            conTitulo('¿Se puede hacer ahora?', 'Piensa antes de decidir', [
                ['e' => '🟢', 'n' => 'El semáforo está en verde: cruzo',   'ok' => true],
                ['e' => '🔴', 'n' => 'El semáforo está en rojo: cruzo',    'ok' => false],
                ['e' => '🙋', 'n' => 'Levanté la mano y me dieron la palabra: hablo', 'ok' => true],
                ['e' => '🗣️', 'n' => 'Otro está hablando: lo interrumpo', 'ok' => false],
                ['e' => '🍽️', 'n' => 'Ya sirvieron a todos: empiezo a comer', 'ok' => true],
                ['e' => '🏃', 'n' => 'Nadie dijo «ya»: salgo corriendo',   'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'revisa-antes-de-entregar',
    'title' => 'Revisa antes de entregar',
    'description' => 'Encontrar el error propio antes de que lo encuentre otro.',
    'objective' => 'Instalar el hábito de revisar el trabajo terminado antes de darlo por bueno.',
    'icon' => '🔁', 'nivel' => 'primaria-media', 'bloque' => 'parar-y-pensar',
    'duracion' => 10, 'tags' => ['atencion', 'escritura', 'foco'],
    'estaciones' => [

        est('¿Dónde está el error?', 'Una cuenta mal en cada grupo', '❌', 'opcion_multiple', [
            omp('¿Cuál de estas cuentas está MAL?',   ['3 + 4 = 8', '2 + 5 = 7', '6 + 1 = 7', '4 + 4 = 8'], '3 + 4 = 8'),
            omp('¿Cuál de estas cuentas está MAL?',   ['10 - 3 = 7', '9 - 4 = 6', '8 - 2 = 6', '7 - 5 = 2'], '9 - 4 = 6'),
            omp('¿Cuál palabra está MAL escrita?',    ['bentana', 'ventana', 'ventilador', 'volar'], 'bentana'),
            omp('¿Cuál palabra está MAL escrita?',    ['ombre', 'hombre', 'hombro', 'hora'], 'ombre'),
            omp('¿Cuál frase está MAL?',              ['Los niño juegan', 'Los niños juegan', 'El niño juega', 'Ella juega'], 'Los niño juegan'),
        ]),

        est('La lista de revisar', 'Cuatro cosas antes de entregar', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena la revisión de una tarea',
            'items' => ['✍️ Termino de escribir', '👀 Releo lo que escribí',
                        '🔤 Miro si falta alguna palabra', '📛 Compruebo que puse mi nombre',
                        '📤 Entrego'],
        ]),

        est('Completa la regla', 'Toca la palabra que falta', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Toca la palabra que va en cada hueco', [
                [
                    'titulo' => 'Revisar no es perder tiempo',
                    'texto'  => 'Cuando termino algo, lo mejor no es entregarlo de ___. Primero lo '
                              . '___ despacio, buscando si me ___ alguna palabra o si hay algún '
                              . '___. Revisar toma poco tiempo y evita tener que hacerlo ___ vez.',
                    'huecos' => ['una', 'leo', 'falta', 'error', 'otra'],
                    'extra'  => ['corro', 'juego', 'nunca'],
                ],
            ])),
    ],
],


// =====================================================================
//  BLOQUE · ORGANIZARME SOLO
//
//  Funciones ejecutivas: planear, acordarse de lo que hay que hacer y
//  arrancar. Es lo que falla cuando un niño «sabe la materia pero no
//  entrega nada».
// =====================================================================

[
    'slug'  => 'que-necesito-para-esto',
    'title' => '¿Qué necesito para esto?',
    'description' => 'Pensar antes de empezar qué hace falta tener a mano.',
    'objective' => 'Anticipar los materiales y condiciones que requiere una tarea.',
    'icon' => '🎒', 'nivel' => 'primaria-inicial', 'bloque' => 'organizarme-solo',
    'duracion' => 9, 'tags' => ['organizacion', 'logica', 'atencion'],
    'estaciones' => [

        est('Para pintar un dibujo', 'Toca lo que sirve', '🎨', 'seleccion_imagenes',
            conTitulo('Toca lo que necesitas para pintar', 'Hay cosas que no hacen falta', [
                ['e' => '🖍️', 'n' => 'Colores',  'ok' => true],
                ['e' => '📄', 'n' => 'Hoja',     'ok' => true],
                ['e' => '🍴', 'n' => 'Tenedor',  'ok' => false],
                ['e' => '🖌️', 'n' => 'Pincel',   'ok' => true],
                ['e' => '⚽', 'n' => 'Balón',    'ok' => false],
                ['e' => '💧', 'n' => 'Agua',     'ok' => true],
                ['e' => '🔦', 'n' => 'Linterna', 'ok' => false],
            ])),

        est('Para ir al colegio', 'Toca lo que va en la mochila', '🏫', 'seleccion_imagenes',
            conTitulo('Toca lo que llevas al colegio', 'Lo demás se queda en casa', [
                ['e' => '📚', 'n' => 'Libros',    'ok' => true],
                ['e' => '✏️', 'n' => 'Cartuchera', 'ok' => true],
                ['e' => '🍎', 'n' => 'Lonchera',  'ok' => true],
                ['e' => '🛏️', 'n' => 'Almohada',  'ok' => false],
                ['e' => '📓', 'n' => 'Cuaderno',  'ok' => true],
                ['e' => '🐕', 'n' => 'El perro',  'ok' => false],
                ['e' => '🧴', 'n' => 'Botella de agua', 'ok' => true],
                ['e' => '🎸', 'n' => 'Guitarra',  'ok' => false],
            ])),

        est('¿Qué se me olvidó?', 'Falta una cosa en cada caso', '🤔', 'opcion_multiple', [
            omp('Voy a escribir pero solo tengo la hoja. ¿Qué me falta?',
                ['El lápiz', 'Otra hoja', 'El borrador de la mesa'], 'El lápiz'),
            omp('Voy a salir y llueve. Tengo la mochila. ¿Qué me falta?',
                ['La sombrilla', 'Otro cuaderno', 'Las gafas de sol'], 'La sombrilla'),
            omp('Voy a jugar fútbol y tengo los guayos. ¿Qué me falta?',
                ['El balón', 'Un libro', 'La cartuchera'], 'El balón'),
            omp('Voy a hacer la tarea de matemáticas y tengo el cuaderno. ¿Qué me falta?',
                ['El libro con los ejercicios', 'Los colores', 'La lonchera'], 'El libro con los ejercicios'),
            omp('Voy a dibujar un círculo perfecto. ¿Qué me ayuda?',
                ['Un compás o algo redondo', 'Una regla', 'Un borrador'], 'Un compás o algo redondo'),
        ]),
    ],
],

[
    'slug'  => 'primero-esto-y-luego-aquello',
    'title' => 'Primero esto y luego aquello',
    'description' => 'Partir una tarea grande en pasos pequeños y ponerlos en orden.',
    'objective' => 'Descomponer una tarea compleja en pasos ordenados y ejecutables.',
    'icon' => '🪜', 'nivel' => 'primaria-media', 'bloque' => 'organizarme-solo',
    'duracion' => 11, 'tags' => ['organizacion', 'secuencias', 'logica'],
    'estaciones' => [

        est('Hacer un trabajo escrito', 'Seis pasos', '📄', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se hace un trabajo escrito',
            'items' => ['❓ Leo qué me piden', '🔍 Busco la información',
                        '🗒️ Escribo las ideas sueltas', '✍️ Redacto el texto',
                        '👀 Lo reviso', '📤 Lo entrego'],
        ]),

        est('Estudiar para una evaluación', 'Seis pasos', '📚', 'ordenar_secuencia', [
            'title' => 'Ordena cómo prepararse para una evaluación',
            'items' => ['📅 Miro qué día es', '📖 Reviso qué temas entran',
                        '✂️ Reparto los temas en varios días', '📝 Estudio un tema por día',
                        '🧠 Me hago preguntas a mí mismo', '😴 Duermo bien la noche antes'],
        ]),

        est('¿Por dónde empiezo?', 'El primer paso importa', '🥇', 'opcion_multiple', [
            omp('Tienes tres tareas y una es para mañana. ¿Por cuál empiezas?',
                ['Por la de mañana', 'Por la más fácil', 'Por la última que me mandaron'],
                'Por la de mañana'),
            omp('La tarea es larga y te da pereza. ¿Qué ayuda más?',
                ['Partirla en trozos y hacer el primero', 'Dejarla para el final', 'Hacerla toda de un tirón'],
                'Partirla en trozos y hacer el primero'),
            omp('No entiendes el enunciado de la tarea. ¿Qué haces primero?',
                ['Vuelvo a leerlo y si no, pregunto', 'La hago a ver qué sale', 'La dejo en blanco'],
                'Vuelvo a leerlo y si no, pregunto'),
            omp('Tienes 40 minutos y dos tareas de 15. ¿Qué conviene?',
                ['Hacer una, descansar un poco y hacer la otra', 'Empezar las dos a la vez', 'Hacer solo una'],
                'Hacer una, descansar un poco y hacer la otra'),
            omp('Terminaste un paso de tres. ¿Qué conviene hacer?',
                ['Seguir con el siguiente paso', 'Volver al primero', 'Dejarlo ahí'],
                'Seguir con el siguiente paso'),
        ]),

        est('Palabras de organizarse', 'Encuéntralas', '🔤', 'sopa_letras',
            sopa(['PLAN', 'PASO', 'AGENDA', 'ORDEN', 'TAREA', 'TIEMPO'], 10)),
    ],
],

[
    'slug'  => 'me-acuerdo-de-todo',
    'title' => 'Me acuerdo de todo',
    'description' => 'Guardar en la cabeza dos o tres cosas a la vez sin que se caiga ninguna.',
    'objective' => 'Ejercitar la memoria de trabajo con instrucciones de varios elementos.',
    'icon' => '🧠', 'nivel' => 'primaria-media', 'bloque' => 'organizarme-solo',
    'duracion' => 10, 'tags' => ['memoria', 'atencion', 'organizacion'],
    'estaciones' => [

        est('Parejas del día', 'Encuentra cada pareja', '🃏', 'memoria',
            ['📅', '⏰', '📝', '🎒', '📚', '🔔', '🍎', '🚪']),

        est('El encargo', 'Tres cosas a la vez', '🛒', 'opcion_multiple', [
            omp('Mamá pidió pan, leche y huevos. Compraste pan y leche. ¿Qué falta?',
                ['Los huevos', 'El pan', 'La leche', 'Nada'], 'Los huevos'),
            omp('La profe dijo: saca el cuaderno, abre en la página 20 y escribe la fecha. Ya sacaste el cuaderno. ¿Qué sigue?',
                ['Abrir en la página 20', 'Escribir la fecha', 'Guardar el cuaderno'], 'Abrir en la página 20'),
            omp('Tenías que llevar la firma, el dinero de la salida y la chaqueta. Llevas la firma y la chaqueta. ¿Qué falta?',
                ['El dinero de la salida', 'La firma', 'La chaqueta'], 'El dinero de la salida'),
            omp('Hay que apagar la luz, cerrar la ventana y sacar la basura. Cerraste la ventana. ¿Qué queda?',
                ['Apagar la luz y sacar la basura', 'Solo la basura', 'Nada'],
                'Apagar la luz y sacar la basura'),
            omp('Si se me olvidan las cosas todo el tiempo, ¿qué ayuda de verdad?',
                ['Escribirlas en una lista', 'Repetirlas mentalmente y ya', 'Confiar en acordarme'],
                'Escribirlas en una lista'),
        ]),

        est('¿Qué venía después?', 'Reconstruye la rutina', '🔄', 'ordenar_secuencia', [
            'title' => 'Ordena la rutina de la mañana',
            'items' => ['⏰ Suena el despertador', '🛏️ Me levanto', '🦷 Me lavo los dientes',
                        '👕 Me visto', '🥣 Desayuno', '🎒 Cojo la mochila', '🚪 Salgo'],
        ]),
    ],
],


// =====================================================================
//  BLOQUE · CALMA Y CUERPO
//
//  Autorregulación. Ningún minijuego de este bloque lleva reloj: meter
//  prisa en un ejercicio sobre calmarse enseña justo lo contrario.
//
//  Y ninguna pregunta es sobre lo que siente QUIEN JUEGA. Siempre se
//  pregunta por un personaje, porque una emoción propia no tiene
//  respuesta correcta y el motor la marcaría como fallo.
// =====================================================================

[
    'slug'  => 'mi-cuerpo-me-avisa',
    'title' => 'El cuerpo avisa',
    'description' => 'Las señales que da el cuerpo antes de que la rabia o el susto se hagan grandes.',
    'objective' => 'Reconocer señales corporales tempranas de enfado, ansiedad o cansancio.',
    'icon' => '🫀', 'nivel' => 'primaria-inicial', 'bloque' => 'calma-y-cuerpo',
    'duracion' => 10, 'tags' => ['autorregulacion', 'emociones', 'cuerpo'],
    'estaciones' => [

        est('¿Qué le pasa al cuerpo?', 'Une la emoción con su señal', '🔗', 'emparejar', [
            ['e' => '😡', 'w' => 'Puños apretados'],
            ['e' => '😰', 'w' => 'Corazón rápido'],
            ['e' => '😢', 'w' => 'Nudo en la garganta'],
            ['e' => '😴', 'w' => 'Ojos pesados'],
            ['e' => '😳', 'w' => 'Cara caliente'],
            ['e' => '🤢', 'w' => 'Dolor de barriga'],
        ]),

        est('La señal temprana', 'Antes de que sea grande', '🚨', 'opcion_multiple', [
            omp('A Leo le aprietan los dientes y le arden las orejas. ¿Qué le está pasando?',
                ['Se está enfadando', 'Tiene sueño', 'Tiene hambre'], 'Se está enfadando'),
            omp('A Sara le tiembla la voz y le sudan las manos antes de exponer. ¿Qué es?',
                ['Está nerviosa', 'Está enfadada', 'Está aburrida'], 'Está nerviosa'),
            omp('Nico bosteza, le pesan los ojos y no entiende lo que lee. ¿Qué necesita?',
                ['Descansar', 'Estudiar más rápido', 'Comer dulces'], 'Descansar'),
            omp('A Ana le duele la barriga cada mañana antes del colegio. ¿Qué conviene?',
                ['Contárselo a un adulto de confianza', 'No decir nada', 'Aguantarse siempre'],
                'Contárselo a un adulto de confianza'),
            omp('¿Para qué sirve notar estas señales temprano?',
                ['Para hacer algo antes de que la emoción crezca', 'Para asustarse más', 'Para nada'],
                'Para hacer algo antes de que la emoción crezca'),
        ]),

        est('Del uno al cinco', 'Ordena de menos a más', '📶', 'ordenar_secuencia', [
            'title' => 'Ordena el enfado de más pequeño a más grande',
            'items' => ['🙂 Tranquilo', '😐 Un poco incómodo', '😕 Molesto',
                        '😠 Enfadado', '🤬 Muy enfadado'],
        ]),
    ],
],

[
    'slug'  => 'respirar-y-volver',
    'title' => 'Respirar y volver',
    'description' => 'Cosas concretas que bajan el volumen cuando algo se hace demasiado grande.',
    'objective' => 'Conocer y elegir estrategias de calma adecuadas a cada situación.',
    'icon' => '🌬️', 'nivel' => 'primaria-inicial', 'bloque' => 'calma-y-cuerpo',
    'duracion' => 10, 'tags' => ['autorregulacion', 'emociones'],
    'estaciones' => [

        est('¿Esto calma?', 'Piensa cada uno sin apuro', '🧘', 'seleccion_imagenes',
            conTitulo('Toca lo que ayuda a calmarse', 'Algunas cosas no ayudan', [
                ['e' => '🌬️', 'n' => 'Respirar despacio',        'ok' => true],
                ['e' => '💧', 'n' => 'Tomar agua',               'ok' => true],
                ['e' => '🚪', 'n' => 'Dar un portazo',           'ok' => false],
                ['e' => '🚶', 'n' => 'Caminar un momento',       'ok' => true],
                ['e' => '📢', 'n' => 'Gritarle a alguien',       'ok' => false],
                ['e' => '🧸', 'n' => 'Abrazar algo suave',       'ok' => true],
                ['e' => '👊', 'n' => 'Pegarle a la pared',       'ok' => false],
                ['e' => '🔢', 'n' => 'Contar hasta diez',        'ok' => true],
                ['e' => '🙋', 'n' => 'Pedir ayuda a un adulto',  'ok' => true],
            ])),

        est('La respiración del cuadrado', 'Cuatro tiempos iguales', '⬜', 'ordenar_secuencia', [
            'title' => 'Ordena la respiración del cuadrado',
            'items' => ['🫁 Tomo aire contando hasta cuatro', '⏸️ Lo aguanto contando hasta cuatro',
                        '💨 Lo suelto contando hasta cuatro', '⏸️ Espero contando hasta cuatro',
                        '🔁 Lo repito tres veces'],
        ]),

        est('¿Qué le sirve a cada uno?', 'No a todos les vale lo mismo', '🤝', 'opcion_multiple', [
            omp('A Leo el ruido del comedor lo desborda. ¿Qué le puede servir?',
                ['Salir un momento a un sitio más tranquilo', 'Gritar más fuerte que los demás', 'Quedarse y aguantar sin decir nada'],
                'Salir un momento a un sitio más tranquilo'),
            omp('Sara se pone muy nerviosa antes de exponer. ¿Qué le puede servir?',
                ['Respirar despacio y practicar antes', 'No dormir la noche anterior', 'Pensar que va a salir fatal'],
                'Respirar despacio y practicar antes'),
            omp('Nico se enfada y quiere romper algo. ¿Qué es mejor?',
                ['Apartarse un momento y avisar a un adulto', 'Romper algo suyo', 'Romper algo de otro'],
                'Apartarse un momento y avisar a un adulto'),
            omp('Ana lleva una hora sentada y no aguanta más quieta. ¿Qué ayuda?',
                ['Pedir una pausa corta para moverse', 'Obligarse a seguir sin parar', 'Levantarse y salir sin avisar'],
                'Pedir una pausa corta para moverse'),
            omp('Después de calmarse, ¿qué toca?',
                ['Volver y arreglar lo que quedó pendiente', 'Hacer como si nada', 'No volver nunca'],
                'Volver y arreglar lo que quedó pendiente'),
        ]),
    ],
],

[
    'slug'  => 'pedir-lo-que-necesito',
    'title' => 'Pedir lo que necesito',
    'description' => 'Poner en palabras lo que hace falta, para que el de al lado pueda ayudar.',
    'objective' => 'Formular peticiones claras de ayuda, pausa o adaptación.',
    'icon' => '🙋', 'nivel' => 'primaria-media', 'bloque' => 'calma-y-cuerpo',
    'duracion' => 10, 'tags' => ['autorregulacion', 'convivencia', 'social'],
    'estaciones' => [

        est('¿Cómo se pide?', 'La frase que funciona', '💬', 'opcion_multiple', [
            omp('Necesitas salir un momento porque hay demasiado ruido. ¿Qué dices?',
                ['«¿Puedo salir un momento? Hay mucho ruido»', 'Nada, me voy', 'Me tapo los oídos y aguanto'],
                '«¿Puedo salir un momento? Hay mucho ruido»'),
            omp('No entendiste la explicación. ¿Qué dices?',
                ['«¿Me lo puede explicar otra vez, por favor?»', 'Nada, ya veré', '«Está mal explicado»'],
                '«¿Me lo puede explicar otra vez, por favor?»'),
            omp('Necesitas más tiempo para terminar. ¿Qué dices?',
                ['«¿Me da unos minutos más, por favor?»', 'Entrego a medias sin decir nada', 'Me quedo callado'],
                '«¿Me da unos minutos más, por favor?»'),
            omp('Un compañero te está molestando. ¿Qué dices primero?',
                ['«Para, no me gusta»', 'Le pego', 'No digo nada nunca'], '«Para, no me gusta»'),
            omp('¿Por qué sirve pedir las cosas con palabras?',
                ['Porque los demás no adivinan lo que necesito', 'Porque queda bonito', 'No sirve'],
                'Porque los demás no adivinan lo que necesito'),
        ]),

        est('Une la necesidad con la frase', 'Cada una con la suya', '🔗', 'emparejar', [
            ['e' => '🔊', 'w' => 'Hay mucho ruido'],
            ['e' => '⏰', 'w' => 'Necesito más tiempo'],
            ['e' => '❓', 'w' => 'No entendí'],
            ['e' => '🚻', 'w' => 'Necesito salir'],
            ['e' => '🤕', 'w' => 'Me duele algo'],
            ['e' => '😔', 'w' => 'Estoy triste'],
        ]),

        est('Completa la petición', 'Toca la palabra que falta', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Toca la palabra que va en cada hueco', [
                [
                    'titulo' => 'Pedir no es molestar',
                    'texto'  => 'Cuando algo me cuesta, lo mejor es ___ ayuda. Nadie puede '
                              . '___ lo que me pasa por dentro si no lo ___. Decirlo con '
                              . 'palabras tranquilas funciona mucho mejor que ___ o quedarse '
                              . 'sin decir ___.',
                    'huecos' => ['pedir', 'adivinar', 'digo', 'gritar', 'nada'],
                    'extra'  => ['correr', 'olvidar', 'todo'],
                ],
            ])),
    ],
],


// =====================================================================
//  BLOQUE · ENTENDER A LOS DEMÁS
//
//  Lectura de caras, tonos y turnos. Todo con personajes: aquí no se le
//  pregunta a nadie por su propia vida social.
// =====================================================================

[
    'slug'  => 'caras-y-lo-que-dicen',
    'title' => 'Caras y lo que dicen',
    'description' => 'Reconocer en una cara si alguien está contento, triste, enfadado o asustado.',
    'objective' => 'Identificar emociones básicas a partir de expresiones faciales.',
    'icon' => '😊', 'nivel' => 'preescolar', 'bloque' => 'entender-a-los-demas',
    'duracion' => 9, 'tags' => ['social', 'emociones', 'observacion'],
    'estaciones' => [

        est('Une cara y nombre', 'Cada cara con su emoción', '🔗', 'emparejar', [
            ['e' => '😀', 'w' => 'Contento'],
            ['e' => '😢', 'w' => 'Triste'],
            ['e' => '😡', 'w' => 'Enfadado'],
            ['e' => '😨', 'w' => 'Asustado'],
            ['e' => '😮', 'w' => 'Sorprendido'],
            ['e' => '😴', 'w' => 'Cansado'],
        ]),

        est('¿Cómo se siente?', 'Mira la cara y decide', '👀', 'opcion_multiple', [
            omp('¿Cómo se siente?', ['Contento', 'Triste', 'Enfadado'], 'Contento', '😄'),
            omp('¿Cómo se siente?', ['Triste', 'Contento', 'Sorprendido'], 'Triste', '😭'),
            omp('¿Cómo se siente?', ['Enfadado', 'Cansado', 'Contento'], 'Enfadado', '😠'),
            omp('¿Cómo se siente?', ['Asustado', 'Contento', 'Aburrido'], 'Asustado', '😱'),
            omp('¿Cómo se siente?', ['Sorprendido', 'Enfadado', 'Triste'], 'Sorprendido', '😲'),
            omp('¿Cómo se siente?', ['Cansado', 'Contento', 'Asustado'], 'Cansado', '🥱'),
        ]),

        est('Parejas de caras', 'Encuentra cada pareja', '🃏', 'memoria',
            ['😀', '😢', '😡', '😨', '😮', '🥱']),
    ],
],

[
    'slug'  => 'que-le-esta-pasando',
    'title' => '¿Qué le está pasando?',
    'description' => 'Deducir cómo se siente alguien por lo que ocurre a su alrededor.',
    'objective' => 'Inferir estados emocionales a partir del contexto de una situación.',
    'icon' => '🕵️', 'nivel' => 'primaria-media', 'bloque' => 'entender-a-los-demas',
    'duracion' => 11, 'tags' => ['social', 'emociones', 'deduccion'],
    'estaciones' => [

        est('La situación', 'Lee y deduce', '📖', 'opcion_multiple', [
            omp('A Ana no la invitaron al cumpleaños y todos hablan de él. ¿Cómo se siente?',
                ['Excluida y triste', 'Contenta', 'Aburrida'], 'Excluida y triste'),
            omp('Leo ganó el concurso de dibujo del colegio. ¿Cómo se siente?',
                ['Orgulloso', 'Asustado', 'Enfadado'], 'Orgulloso'),
            omp('A Nico se le cayó el vaso delante de toda la clase y se rieron. ¿Cómo se siente?',
                ['Avergonzado', 'Orgulloso', 'Tranquilo'], 'Avergonzado'),
            omp('Sara tiene que dormir fuera de casa por primera vez. ¿Cómo se puede sentir?',
                ['Nerviosa', 'Aburrida', 'Enfadada'], 'Nerviosa'),
            omp('A Tomás le prometieron ir al parque y a última hora se canceló. ¿Cómo se siente?',
                ['Decepcionado', 'Sorprendido y contento', 'Orgulloso'], 'Decepcionado'),
        ]),

        est('¿Qué le vendría bien?', 'Ayudar es acertar con qué', '🤲', 'opcion_multiple', [
            omp('Un compañero está solo en el recreo y mira al suelo. ¿Qué ayuda?',
                ['Preguntarle si quiere jugar', 'Señalarlo y reírse', 'Ignorarlo'],
                'Preguntarle si quiere jugar'),
            omp('Alguien está llorando después de una discusión. ¿Qué ayuda?',
                ['Preguntarle si quiere hablar y esperar', 'Decirle que no llore', 'Contarlo a todos'],
                'Preguntarle si quiere hablar y esperar'),
            omp('Un amigo está muy nervioso antes de una prueba. ¿Qué ayuda?',
                ['Decirle algo tranquilo y acompañarlo', 'Decirle que es dificilísima', 'Reírse'],
                'Decirle algo tranquilo y acompañarlo'),
            omp('Alguien se equivocó y está avergonzado. ¿Qué ayuda?',
                ['Restarle importancia con amabilidad', 'Recordárselo cada día', 'Grabarlo'],
                'Restarle importancia con amabilidad'),
            omp('Un compañero está muy contento y quiere contarlo. ¿Qué ayuda?',
                ['Escucharlo y alegrarse con él', 'Cambiar de tema', 'Decirle que no es para tanto'],
                'Escucharlo y alegrarse con él'),
        ]),

        est('¿Ayuda o no ayuda?', 'Decide cada gesto', '⚖️', 'juego_rapido',
            conTitulo('¿Este gesto ayuda a la otra persona?', 'Piensa y decide', [
                ['e' => '👂', 'n' => 'Escuchar sin interrumpir',   'ok' => true],
                ['e' => '📱', 'n' => 'Mirar el celular mientras te habla', 'ok' => false],
                ['e' => '🤗', 'n' => 'Preguntar antes de abrazar', 'ok' => true],
                ['e' => '😂', 'n' => 'Reírse de lo que le duele',  'ok' => false],
                ['e' => '🙋', 'n' => 'Ofrecerle ayuda',            'ok' => true],
                ['e' => '📢', 'n' => 'Contar su secreto',          'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'mi-turno-tu-turno',
    'title' => 'Mi turno, tu turno',
    'description' => 'Cómo funciona una conversación: cuándo hablar, cuándo escuchar y cuándo parar.',
    'objective' => 'Reconocer y respetar los turnos y las señales de una conversación.',
    'icon' => '🗣️', 'nivel' => 'primaria-media', 'bloque' => 'entender-a-los-demas',
    'duracion' => 10, 'tags' => ['social', 'convivencia', 'comprension'],
    'estaciones' => [

        est('Cómo empieza una charla', 'Ponlo en orden', '💬', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se entra en una conversación',
            'items' => ['👀 Me acerco y miro', '⏸️ Espero a que terminen la frase',
                        '👋 Saludo', '❓ Pregunto algo del tema', '👂 Escucho la respuesta'],
        ]),

        est('¿Cuándo hablo?', 'La señal de que es mi turno', '⏱️', 'opcion_multiple', [
            omp('Estoy contando algo y el otro mira el reloj y se aleja. ¿Qué significa?',
                ['Que quizá tiene prisa: conviene terminar', 'Que quiere que siga más rato', 'Nada'],
                'Que quizá tiene prisa: conviene terminar'),
            omp('Le pregunto algo y me contesta con una palabra y se calla. ¿Qué puede significar?',
                ['Que no quiere hablar ahora', 'Que quiere que le pregunte veinte veces más', 'Que está feliz'],
                'Que no quiere hablar ahora'),
            omp('Dos personas están hablando y quiero decir algo. ¿Qué hago?',
                ['Espero una pausa y pido permiso', 'Hablo encima', 'Grito su nombre'],
                'Espero una pausa y pido permiso'),
            omp('Llevo diez minutos hablando de mi tema favorito y el otro no dice nada. ¿Qué hago?',
                ['Le pregunto algo a él', 'Sigo veinte minutos más', 'Hablo más alto'],
                'Le pregunto algo a él'),
            omp('Alguien dice «bueno, me tengo que ir». ¿Qué significa?',
                ['Que la conversación termina', 'Que quiere hablar más', 'Que está enfadado'],
                'Que la conversación termina'),
        ]),

        est('Desafío de la conversación', 'Cinco situaciones', '🏆', 'desafio_final', [
            reto('¿Qué es escuchar de verdad?',
                 ['Prestar atención sin pensar solo en lo que voy a decir', 'Esperar callado mi turno', 'Asentir con la cabeza'],
                 'Prestar atención sin pensar solo en lo que voy a decir'),
            reto('Un amigo cuenta algo importante y quiero contar lo mío. ¿Qué hago?',
                 ['Termino de escuchar y luego cuento', 'Lo interrumpo', 'Cambio de tema'],
                 'Termino de escuchar y luego cuento'),
            reto('¿Cómo sé que a alguien le interesa lo que digo?',
                 ['Me mira, pregunta y responde', 'Está callado', 'Mira el celular'],
                 'Me mira, pregunta y responde'),
            reto('Me equivoqué y dije algo que molestó. ¿Qué hago?',
                 ['Pido disculpas y sigo', 'Me hago el que no pasó nada', 'Me enfado yo'],
                 'Pido disculpas y sigo'),
            reto('¿Está mal preferir hablar poco?',
                 ['No: cada persona es distinta', 'Sí, siempre', 'Solo en el colegio'],
                 'No: cada persona es distinta'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · CUANDO ALGO CAMBIA
//
//  Anticipación y flexibilidad. El objetivo no es que un cambio deje de
//  costar —eso no se logra con un juego— sino que deje de ser una
//  sorpresa: saber qué avisa, y tener un plan para cuando pase.
// =====================================================================

[
    'slug'  => 'hoy-es-diferente',
    'title' => 'Hoy es diferente',
    'description' => 'El plan cambió. Qué se puede hacer cuando el día no sale como estaba pensado.',
    'objective' => 'Responder a un cambio imprevisto con una alternativa razonable.',
    'icon' => '🔄', 'nivel' => 'primaria-inicial', 'bloque' => 'cuando-algo-cambia',
    'duracion' => 10, 'tags' => ['anticipacion', 'autorregulacion', 'logica'],
    'estaciones' => [

        est('¿Y ahora qué?', 'Busca la salida razonable', '🤷', 'opcion_multiple', [
            omp('Iban a ir al parque pero está lloviendo. ¿Qué se puede hacer?',
                ['Jugar dentro y ver si escampa', 'Ir igual y mojarse', 'Enfadarse todo el día'],
                'Jugar dentro y ver si escampa'),
            omp('La profe de siempre faltó y viene otra persona. ¿Qué conviene?',
                ['Preguntarle cómo van a trabajar hoy', 'No hacer nada', 'Salirse del salón'],
                'Preguntarle cómo van a trabajar hoy'),
            omp('Cambiaron el salón de clase sin avisar. ¿Qué hago?',
                ['Preguntar dónde es ahora', 'Quedarme en el pasillo', 'Irme a casa'],
                'Preguntar dónde es ahora'),
            omp('El amigo con el que siempre te sientas hoy no vino. ¿Qué puedes hacer?',
                ['Sentarme con otra persona por hoy', 'No sentarme', 'Sentarme solo y enfadarme'],
                'Sentarme con otra persona por hoy'),
            omp('Se acabó el sabor de helado que siempre pides. ¿Qué se puede hacer?',
                ['Probar otro esta vez', 'No comer helado nunca más', 'Gritarle al vendedor'],
                'Probar otro esta vez'),
        ]),

        est('Plan A y plan B', 'Une cada plan con su alternativa', '🅱️', 'emparejar', [
            ['e' => '🌧️', 'w' => 'Llueve: jugamos dentro'],
            ['e' => '🚌', 'w' => 'Se fue el bus: esperamos el siguiente'],
            ['e' => '🔌', 'w' => 'Se fue la luz: usamos linterna'],
            ['e' => '🤒', 'w' => 'Estoy enfermo: descanso hoy'],
            ['e' => '🚧', 'w' => 'La calle está cerrada: damos la vuelta'],
            ['e' => '📵', 'w' => 'Sin internet: leo un libro'],
        ]),

        est('Los avisos del cambio', 'Ordena lo que pasa', '📣', 'ordenar_secuencia', [
            'title' => 'Ordena lo que pasa cuando algo va a cambiar',
            'items' => ['📣 Alguien avisa que algo va a cambiar', '❓ Pregunto qué va a pasar',
                        '📅 Me lo imagino con el nuevo plan', '🌬️ Respiro si me pone nervioso',
                        '✅ Hago el plan nuevo'],
        ]),
    ],
],

[
    'slug'  => 'lo-que-viene-despues',
    'title' => 'Lo que viene después',
    'description' => 'Saber qué toca a continuación quita casi todo el susto de lo que va a pasar.',
    'objective' => 'Anticipar la secuencia de una jornada o una actividad conocida.',
    'icon' => '📅', 'nivel' => 'primaria-inicial', 'bloque' => 'cuando-algo-cambia',
    'duracion' => 10, 'tags' => ['anticipacion', 'secuencias', 'organizacion'],
    'estaciones' => [

        est('El día del colegio', 'Ponlo en orden', '🏫', 'ordenar_secuencia', [
            'title' => 'Ordena un día normal de colegio',
            'items' => ['🚪 Llego', '🪑 Primera clase', '🍎 Descanso',
                        '📚 Más clases', '🍽️ Almuerzo', '🎒 Salida'],
        ]),

        est('Ir al médico', 'Ponlo en orden', '🩺', 'ordenar_secuencia', [
            'title' => 'Ordena una visita al médico',
            'items' => ['🚗 Vamos', '🪑 Esperamos en la sala', '📛 Nos llaman por el nombre',
                        '🩺 El médico revisa', '💬 Explica qué pasa', '🚪 Salimos'],
        ]),

        est('¿Qué viene ahora?', 'Sabiendo el orden se puede predecir', '⏭️', 'opcion_multiple', [
            omp('Terminó el descanso y sonó el timbre. ¿Qué viene ahora?',
                ['Volver al salón', 'Almorzar', 'Irse a casa'], 'Volver al salón'),
            omp('Ya cenamos y me puse la pijama. ¿Qué viene ahora?',
                ['Lavarme los dientes y dormir', 'Salir a jugar', 'Desayunar'],
                'Lavarme los dientes y dormir'),
            omp('En el supermercado ya llenamos el carro. ¿Qué viene ahora?',
                ['Pagar en la caja', 'Salir corriendo', 'Empezar de nuevo'], 'Pagar en la caja'),
            omp('El profesor dijo: «guarden todo». ¿Qué viene después?',
                ['Cambiar de actividad o salir', 'Sacar más cuadernos', 'Empezar la clase'],
                'Cambiar de actividad o salir'),
            omp('¿Por qué ayuda saber qué viene después?',
                ['Porque así no sorprende y da menos nervios', 'Porque es más rápido', 'No ayuda'],
                'Porque así no sorprende y da menos nervios'),
        ]),
    ],
],

[
    'slug'  => 'cuando-algo-sale-mal',
    'title' => 'Cuando algo sale mal',
    'description' => 'Equivocarse forma parte de aprender. Lo que importa es qué se hace después.',
    'objective' => 'Afrontar el error y la frustración con una respuesta constructiva.',
    'icon' => '🧩', 'nivel' => 'primaria-media', 'bloque' => 'cuando-algo-cambia',
    'duracion' => 10, 'tags' => ['autorregulacion', 'anticipacion', 'convivencia'],
    'estaciones' => [

        est('Después del error', '¿Qué conviene hacer?', '🛠️', 'opcion_multiple', [
            omp('Te equivocaste en toda una página del cuaderno. ¿Qué haces?',
                ['La corrijo o la vuelvo a hacer', 'Arranco la hoja y la escondo', 'Tiro el cuaderno'],
                'La corrijo o la vuelvo a hacer'),
            omp('Perdiste el juego tres veces seguidas. ¿Qué conviene?',
                ['Descansar y volver a intentarlo', 'No volver a jugar nunca', 'Culpar a los demás'],
                'Descansar y volver a intentarlo'),
            omp('Rompiste algo sin querer. ¿Qué haces?',
                ['Lo digo y ayudo a arreglarlo', 'Echo la culpa a otro', 'Lo escondo'],
                'Lo digo y ayudo a arreglarlo'),
            omp('No te salió el dibujo como querías. ¿Qué conviene?',
                ['Intentarlo otra vez o quedarme con lo que sí salió', 'Romper todos mis dibujos', 'No dibujar más'],
                'Intentarlo otra vez o quedarme con lo que sí salió'),
            omp('Le contestaste mal a un amigo cuando estabas enfadado. ¿Qué haces?',
                ['Le pido disculpas cuando esté tranquilo', 'Hago como si nada', 'Me enfado más'],
                'Le pido disculpas cuando esté tranquilo'),
        ]),

        est('Equivocarse es normal', 'Toca lo que es cierto', '✅', 'seleccion_imagenes',
            conTitulo('Toca lo que es verdad sobre equivocarse', 'Algunas frases son falsas', [
                ['e' => '🧠', 'n' => 'Equivocarse ayuda a aprender',        'ok' => true],
                ['e' => '🌍', 'n' => 'Todo el mundo se equivoca',           'ok' => true],
                ['e' => '🚫', 'n' => 'Equivocarse significa ser tonto',     'ok' => false],
                ['e' => '🔁', 'n' => 'Se puede volver a intentar',          'ok' => true],
                ['e' => '😶', 'n' => 'Hay que esconder los errores',        'ok' => false],
                ['e' => '🙋', 'n' => 'Se puede pedir ayuda al equivocarse', 'ok' => true],
                ['e' => '⏳', 'n' => 'Un error dura para siempre',          'ok' => false],
            ])),

        est('Ordena la reparación', 'Qué va primero', '🔧', 'ordenar_secuencia', [
            'title' => 'Ordena qué hacer después de un error',
            'items' => ['🌬️ Respiro', '👀 Miro qué salió mal', '💬 Lo digo si afectó a alguien',
                        '🔧 Lo arreglo si se puede', '🔁 Lo intento otra vez'],
        ]),
    ],
],


// =====================================================================
//  BLOQUE · LO QUE NO SE DICE
//
//  Lenguaje figurado y reglas sociales implícitas. Se explican, no se dan
//  por sabidas: quien las entiende de forma literal no está fallando,
//  está leyendo exactamente lo que las palabras dicen.
// =====================================================================

[
    'slug'  => 'no-siempre-es-literal',
    'title' => 'No siempre es literal',
    'description' => 'Frases hechas que no significan lo que dicen sus palabras, explicadas una a una.',
    'objective' => 'Interpretar expresiones figuradas frecuentes del español.',
    'icon' => '💬', 'nivel' => 'primaria-media', 'bloque' => 'lo-que-no-se-dice',
    'duracion' => 11, 'tags' => ['lenguaje-claro', 'comprension', 'vocabulario'],
    'estaciones' => [

        est('¿Qué quiere decir?', 'La frase no es literal', '🧐', 'opcion_multiple', [
            omp('«Se me fue el santo al cielo» quiere decir…',
                ['Se me olvidó lo que iba a decir', 'Vi un santo', 'Me subí muy alto'],
                'Se me olvidó lo que iba a decir'),
            omp('«Está lloviendo a cántaros» quiere decir…',
                ['Llueve muchísimo', 'Caen cántaros del cielo', 'Llovizna un poco'],
                'Llueve muchísimo'),
            omp('«Me costó un ojo de la cara» quiere decir…',
                ['Fue carísimo', 'Perdí un ojo', 'Me dolió la cara'], 'Fue carísimo'),
            omp('«Échame una mano» quiere decir…',
                ['Ayúdame', 'Lánzame tu mano', 'Salúdame'], 'Ayúdame'),
            omp('«Se me hizo agua la boca» quiere decir…',
                ['Me dio muchas ganas de comerlo', 'Bebí agua', 'Me mojé'],
                'Me dio muchas ganas de comerlo'),
            omp('«Tiene la cabeza en las nubes» quiere decir…',
                ['Está distraído', 'Es muy alto', 'Le gusta volar'], 'Está distraído'),
        ]),

        est('Une la frase con su significado', 'Cada una con el suyo', '🔗', 'emparejar', [
            ['e' => '🌧️', 'w' => 'A cántaros = muchísimo'],
            ['e' => '🤝', 'w' => 'Echar una mano = ayudar'],
            ['e' => '☁️', 'w' => 'Cabeza en las nubes = distraído'],
            ['e' => '💰', 'w' => 'Un ojo de la cara = carísimo'],
            ['e' => '🐔', 'w' => 'Ponerse la piel de gallina = emocionarse'],
            ['e' => '🍰', 'w' => 'Pan comido = muy fácil'],
        ]),

        est('¿Literal o no?', 'Decide con calma', '⚖️', 'juego_rapido',
            conTitulo('¿Esta frase se dice en serio, palabra por palabra?', 'Piensa cada una', [
                ['e' => '🚪', 'n' => '«Cierra la puerta» — sí, es literal',        'ok' => true],
                ['e' => '💀', 'n' => '«Me muero de hambre» — sí, es literal',      'ok' => false],
                ['e' => '💧', 'n' => '«Trae agua» — sí, es literal',               'ok' => true],
                ['e' => '🐎', 'n' => '«Comes como un caballo» — sí, es literal',   'ok' => false],
                ['e' => '🪑', 'n' => '«Siéntate aquí» — sí, es literal',           'ok' => true],
                ['e' => '🔥', 'n' => '«Estoy que ardo de rabia» — sí, es literal', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'el-tono-lo-cambia-todo',
    'title' => 'El tono lo cambia todo',
    'description' => 'Las mismas palabras significan cosas distintas según cómo se digan.',
    'objective' => 'Reconocer que la intención de un mensaje depende del tono y del contexto.',
    'icon' => '🎚️', 'nivel' => 'primaria-superior', 'bloque' => 'lo-que-no-se-dice',
    'duracion' => 10, 'tags' => ['lenguaje-claro', 'social', 'comprension'],
    'estaciones' => [

        est('La misma frase, otra intención', 'El contexto lo aclara', '🎭', 'opcion_multiple', [
            omp('Sacas la peor nota y alguien dice «¡Qué bien te salió!» riéndose. ¿Qué es?',
                ['Una burla', 'Un elogio de verdad', 'Una pregunta'], 'Una burla'),
            omp('Ayudaste mucho y te dicen «Gracias, en serio» mirándote a los ojos. ¿Qué es?',
                ['Un agradecimiento de verdad', 'Una burla', 'Una orden'],
                'Un agradecimiento de verdad'),
            omp('Alguien dice «Qué bonito» mirando algo roto y torciendo la boca. ¿Qué es?',
                ['Ironía: quiere decir lo contrario', 'Le gusta de verdad', 'Está preguntando'],
                'Ironía: quiere decir lo contrario'),
            omp('Alguien te dice «¿Puedes bajar la voz?» en voz baja y sonriendo. ¿Qué es?',
                ['Una petición amable', 'Un insulto', 'Una amenaza'], 'Una petición amable'),
            omp('Si no estoy seguro de si alguien habla en serio, ¿qué es mejor?',
                ['Preguntarle directamente', 'Adivinar', 'Enfadarme por si acaso'],
                'Preguntarle directamente'),
        ]),

        est('¿En serio o en broma?', 'Fíjate en la situación', '🤨', 'opcion_multiple', [
            omp('Tu amigo se cae, se levanta riéndose y dice «Menuda pirueta». ¿Qué es?',
                ['Broma sobre sí mismo', 'Está enfadado contigo', 'Está pidiendo ayuda'],
                'Broma sobre sí mismo'),
            omp('Alguien repite tu forma de hablar mientras los demás se ríen. ¿Qué es?',
                ['Una burla, aunque digan que es broma', 'Un juego amistoso', 'Un elogio'],
                'Una burla, aunque digan que es broma'),
            omp('Una broma le molestó a alguien. ¿Sigue siendo broma?',
                ['No: si molesta, se pide disculpas y se para', 'Sí, siempre', 'Depende de quién la hizo'],
                'No: si molesta, se pide disculpas y se para'),
            omp('No entendiste una broma del grupo. ¿Qué se puede hacer?',
                ['Preguntar de qué se ríen', 'Reírse sin entender', 'Irse enfadado'],
                'Preguntar de qué se ríen'),
            omp('¿Está mal no pillar una ironía?',
                ['No: hay que preguntar y ya', 'Sí, es un error grave', 'Solo si eres mayor'],
                'No: hay que preguntar y ya'),
        ]),

        est('Palabras del lenguaje', 'Encuéntralas', '🔤', 'sopa_letras',
            sopa(['TONO', 'BROMA', 'IRONIA', 'FRASE', 'SERIO', 'GESTO'], 10)),
    ],
],

[
    'slug'  => 'reglas-que-nadie-explica',
    'title' => 'Reglas que nadie explica',
    'description' => 'Normas sociales que casi nadie dice en voz alta, escritas aquí de forma clara.',
    'objective' => 'Conocer explícitamente convenciones sociales habituales del colegio y la casa.',
    'icon' => '🪧', 'nivel' => 'primaria-media', 'bloque' => 'lo-que-no-se-dice',
    'duracion' => 11, 'tags' => ['social', 'convivencia', 'lenguaje-claro'],
    'estaciones' => [

        est('La regla no escrita', 'Aquí sí se explica', '📜', 'opcion_multiple', [
            omp('En una fila del colegio, ¿qué se espera?',
                ['Ponerse al final y esperar', 'Meterse delante si tienes prisa', 'Empujar'],
                'Ponerse al final y esperar'),
            omp('Alguien te presta algo suyo. ¿Qué se espera?',
                ['Devolverlo como estaba y dar las gracias', 'Quedárselo', 'Devolverlo roto sin decir nada'],
                'Devolverlo como estaba y dar las gracias'),
            omp('Entras a un salón donde ya empezó la clase. ¿Qué se espera?',
                ['Entrar en silencio y sentarse', 'Saludar a gritos', 'Quedarse en la puerta'],
                'Entrar en silencio y sentarse'),
            omp('Un compañero te cuenta algo y dice «no lo digas». ¿Qué se espera?',
                ['No contarlo, salvo que sea peligroso para alguien', 'Contarlo a los amigos', 'Publicarlo'],
                'No contarlo, salvo que sea peligroso para alguien'),
            omp('Te invitan a casa de alguien. ¿Qué se espera?',
                ['Preguntar antes de coger cosas', 'Abrir todos los cajones', 'Entrar a todos los cuartos'],
                'Preguntar antes de coger cosas'),
            omp('Estás muy cerca de alguien y se aparta un poco. ¿Qué significa?',
                ['Que quiere más espacio', 'Que quiere que me acerque más', 'Que está enfadado'],
                'Que quiere más espacio'),
        ]),

        est('¿Se espera o no se espera?', 'Piensa cada situación', '🤝', 'juego_rapido',
            conTitulo('¿Esto es lo que se espera en el colegio?', 'Decide cada una', [
                ['e' => '🙋', 'n' => 'Levantar la mano para hablar en clase', 'ok' => true],
                ['e' => '📱', 'n' => 'Contestar el celular en mitad de la clase', 'ok' => false],
                ['e' => '🙏', 'n' => 'Dar las gracias cuando te ayudan',     'ok' => true],
                ['e' => '🍟', 'n' => 'Coger comida del plato de otro sin pedir', 'ok' => false],
                ['e' => '🚪', 'n' => 'Tocar antes de entrar a un lugar cerrado', 'ok' => true],
                ['e' => '🔊', 'n' => 'Hablar encima de quien está exponiendo', 'ok' => false],
                ['e' => '⏰', 'n' => 'Avisar si vas a llegar tarde',         'ok' => true],
            ])),

        est('Desafío de las reglas', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Por qué hay reglas que nadie explica?',
                 ['Porque casi todos las aprendieron mirando, no leyéndolas', 'Porque son secretas', 'Porque no importan'],
                 'Porque casi todos las aprendieron mirando, no leyéndolas'),
            reto('¿Está mal preguntar cuál es la regla?',
                 ['No: preguntar es la mejor forma de saberla', 'Sí, queda raro', 'Solo si eres pequeño'],
                 'No: preguntar es la mejor forma de saberla'),
            reto('Alguien rompe una regla sin saberlo. ¿Qué conviene hacer?',
                 ['Explicársela con amabilidad', 'Reírse', 'Contarlo a todos'],
                 'Explicársela con amabilidad'),
            reto('¿Todas las reglas valen en todas partes?',
                 ['No: cambian según el lugar y la familia', 'Sí, son iguales siempre', 'Solo las del colegio'],
                 'No: cambian según el lugar y la familia'),
            reto('¿Qué hago si una regla me parece injusta?',
                 ['Decirlo con respeto y explicar por qué', 'Romperla sin decir nada', 'Callarme siempre'],
                 'Decirlo con respeto y explicar por qué'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · MIS SENTIDOS
//
//  Regulación sensorial. Aquí se nota más que en ningún otro sitio la
//  regla del encabezado: NO se pregunta qué le molesta a quien juega.
//  Un niño al que el ruido le duele de verdad contestaría la verdad y el
//  motor le diría que se equivocó.
// =====================================================================

[
    'slug'  => 'fuerte-suave-y-en-medio',
    'title' => 'Fuerte, suave y en medio',
    'description' => 'Los sonidos, las luces y las texturas tienen intensidades, y se pueden nombrar.',
    'objective' => 'Nombrar y graduar estímulos sensoriales del entorno cotidiano.',
    'icon' => '🔉', 'nivel' => 'preescolar', 'bloque' => 'mis-sentidos',
    'duracion' => 9, 'tags' => ['sensorial', 'observacion', 'vocabulario'],
    'estaciones' => [

        est('Ordena los ruidos', 'De más suave a más fuerte', '📶', 'ordenar_secuencia', [
            'title' => 'Ordena los sonidos del más suave al más fuerte',
            'items' => ['🤫 Un susurro', '💬 Una conversación', '📻 Un radio encendido',
                        '🚗 Una calle con carros', '🚨 Una sirena'],
        ]),

        est('Une con su sentido', 'Cada cosa con el sentido que usa', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'Ver'],
            ['e' => '👂', 'w' => 'Oír'],
            ['e' => '👃', 'w' => 'Oler'],
            ['e' => '👅', 'w' => 'Saborear'],
            ['e' => '✋', 'w' => 'Tocar'],
            ['e' => '🦶', 'w' => 'Equilibrio'],
        ]),

        est('¿Fuerte o suave?', 'Mira y decide', '🔊', 'opcion_multiple', [
            omp('¿Cómo es este sonido?',  ['Muy fuerte', 'Muy suave', 'No suena'], 'Muy fuerte', '🚨'),
            omp('¿Cómo es este sonido?',  ['Muy suave', 'Muy fuerte', 'Metálico'], 'Muy suave', '🤫'),
            omp('¿Cómo es esta luz?',     ['Muy fuerte', 'Muy suave', 'No hay luz'], 'Muy fuerte', '☀️'),
            omp('¿Cómo es esta luz?',     ['Suave', 'Cegadora', 'De día'], 'Suave', '🕯️'),
            omp('¿Cómo se siente esto?',  ['Suave', 'Áspero', 'Frío como el hielo'], 'Suave', '🧸'),
        ]),
    ],
],

[
    'slug'  => 'lo-que-ayuda-en-cada-sitio',
    'title' => 'Lo que ayuda en cada sitio',
    'description' => 'Cuando el ambiente incomoda, hay cosas concretas que se pueden pedir o hacer.',
    'objective' => 'Elegir estrategias de regulación sensorial adecuadas a cada entorno.',
    'icon' => '🎧', 'nivel' => 'primaria-media', 'bloque' => 'mis-sentidos',
    'duracion' => 11, 'tags' => ['sensorial', 'autorregulacion', 'social'],
    'estaciones' => [

        est('¿Qué le puede servir?', 'Cada situación tiene salida', '🧰', 'opcion_multiple', [
            omp('A Leo el comedor le resulta demasiado ruidoso. ¿Qué puede servirle?',
                ['Comer en un sitio más tranquilo o usar audífonos', 'Gritar más que los demás', 'No comer'],
                'Comer en un sitio más tranquilo o usar audífonos'),
            omp('A Sara la etiqueta de la camiseta le raspa todo el día. ¿Qué puede hacer?',
                ['Pedir que se la corten', 'Aguantar sin decir nada', 'Quitarse la camiseta en clase'],
                'Pedir que se la corten'),
            omp('A Nico la luz del salón le molesta mucho en los ojos. ¿Qué puede pedir?',
                ['Sentarse lejos de la ventana o bajar la persiana', 'Cerrar los ojos toda la clase', 'Salirse siempre'],
                'Sentarse lejos de la ventana o bajar la persiana'),
            omp('A Ana la textura de cierta comida le da mucho rechazo. ¿Qué conviene?',
                ['Decirlo y buscar otra opción parecida', 'Obligarse a comerla llorando', 'No comer nunca más'],
                'Decirlo y buscar otra opción parecida'),
            omp('En una fiesta hay demasiada gente y música. ¿Qué puede servir?',
                ['Salir un rato a un sitio más tranquilo y volver', 'Irse sin avisar a nadie', 'Aguantar hasta explotar'],
                'Salir un rato a un sitio más tranquilo y volver'),
        ]),

        est('La caja de la calma', 'Toca lo que suele ayudar', '📦', 'seleccion_imagenes',
            conTitulo('Toca lo que suele ayudar cuando el ambiente agobia', 'Algunas cosas no ayudan', [
                ['e' => '🎧', 'n' => 'Audífonos que bajan el ruido', 'ok' => true],
                ['e' => '🕶️', 'n' => 'Gafas para la luz fuerte',     'ok' => true],
                ['e' => '🧸', 'n' => 'Algo suave para apretar',      'ok' => true],
                ['e' => '📢', 'n' => 'Subir la música al máximo',    'ok' => false],
                ['e' => '🚪', 'n' => 'Un sitio tranquilo donde ir',  'ok' => true],
                ['e' => '💡', 'n' => 'Encender todas las luces',     'ok' => false],
                ['e' => '🌬️', 'n' => 'Respirar despacio',            'ok' => true],
                ['e' => '🏃', 'n' => 'Correr por el pasillo gritando', 'ok' => false],
            ])),

        est('Completa la idea', 'Toca la palabra que falta', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Toca la palabra que va en cada hueco', [
                [
                    'titulo' => 'No a todos les molesta lo mismo',
                    'texto'  => 'Hay personas a las que un ruido normal les resulta muy ___. '
                              . 'No están exagerando: lo ___ de verdad más fuerte que los demás. '
                              . 'Lo que ayuda es ___ lo que pasa y buscar algo concreto, como un '
                              . 'sitio más ___ o unos audífonos. Cada persona necesita cosas '
                              . '___.',
                    'huecos' => ['molesto', 'sienten', 'decir', 'tranquilo', 'distintas'],
                    'extra'  => ['divertido', 'rápido', 'iguales'],
                ],
            ])),
    ],
],

[
    'slug'  => 'cada-uno-siente-distinto',
    'title' => 'Cada uno siente distinto',
    'description' => 'Que a alguien le moleste algo que a ti no, no significa que exagere.',
    'objective' => 'Comprender la variabilidad sensorial entre personas y responder con respeto.',
    'icon' => '🌈', 'nivel' => 'primaria-superior', 'bloque' => 'mis-sentidos',
    'duracion' => 10, 'tags' => ['sensorial', 'convivencia', 'social'],
    'estaciones' => [

        est('¿Exagera o le pasa de verdad?', 'Piensa antes de juzgar', '🤔', 'opcion_multiple', [
            omp('Un compañero se tapa los oídos cuando suena el timbre. ¿Qué está pasando?',
                ['A él ese sonido le resulta muy fuerte', 'Está fingiendo', 'Quiere llamar la atención'],
                'A él ese sonido le resulta muy fuerte'),
            omp('Alguien no soporta cierta comida ni probándola. ¿Qué conviene?',
                ['Respetarlo y no insistir', 'Obligarlo a comerla', 'Reírse de él'],
                'Respetarlo y no insistir'),
            omp('Una compañera prefiere no dar abrazos. ¿Qué se hace?',
                ['Se le respeta y se saluda de otra forma', 'Se la abraza igual', 'Se le dice que es rara'],
                'Se le respeta y se saluda de otra forma'),
            omp('Alguien necesita moverse mientras escucha para concentrarse. ¿Qué es?',
                ['Una forma válida de concentrarse', 'Falta de respeto', 'Pereza'],
                'Una forma válida de concentrarse'),
            omp('¿Todos sentimos las cosas con la misma intensidad?',
                ['No: cada persona percibe distinto', 'Sí, todos igual', 'Solo los adultos'],
                'No: cada persona percibe distinto'),
        ]),

        est('Cómo se responde', 'Toca lo que respeta al otro', '💚', 'seleccion_imagenes',
            conTitulo('Toca las formas de responder que respetan al otro', 'Algunas no respetan', [
                ['e' => '👂', 'n' => 'Preguntarle qué le ayuda',       'ok' => true],
                ['e' => '😂', 'n' => 'Reírse de lo que le molesta',    'ok' => false],
                ['e' => '🤝', 'n' => 'Ofrecerle cambiar de sitio',     'ok' => true],
                ['e' => '📢', 'n' => 'Hacer el ruido a propósito',     'ok' => false],
                ['e' => '🤐', 'n' => 'Bajar la voz si le molesta',     'ok' => true],
                ['e' => '👉', 'n' => 'Contarlo a todos como algo raro', 'ok' => false],
                ['e' => '🫂', 'n' => 'Acompañarlo si se aparta un rato', 'ok' => true],
            ])),

        est('Desafío de los sentidos', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Por qué a dos personas les molesta distinto el mismo ruido?',
                 ['Porque el cerebro de cada uno procesa distinto', 'Porque una miente', 'Porque una es más joven'],
                 'Porque el cerebro de cada uno procesa distinto'),
            reto('¿Qué es un espacio de calma?',
                 ['Un sitio tranquilo al que ir cuando algo agobia', 'Un castigo', 'Un sitio para dormir'],
                 'Un sitio tranquilo al que ir cuando algo agobia'),
            reto('Alguien te dice que la música está muy alta para él. ¿Qué haces?',
                 ['La bajo o le ofrezco otra opción', 'La subo', 'Le digo que aguante'],
                 'La bajo o le ofrezco otra opción'),
            reto('¿Necesitar audífonos o un sitio tranquilo es una debilidad?',
                 ['No: es saber lo que uno necesita', 'Sí', 'Solo si eres mayor'],
                 'No: es saber lo que uno necesita'),
            reto('¿Qué es lo mejor que puede hacer un compañero?',
                 ['Preguntar en vez de suponer', 'Suponer lo que le pasa', 'No hablarle nunca'],
                 'Preguntar en vez de suponer'),
        ]),
    ],
],

],
];
