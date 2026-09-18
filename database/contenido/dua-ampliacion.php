<?php
/**
 * dua-ampliacion.php — Aprender sin Barreras en preescolar y superior
 *
 * Esta categoría es la segunda más grande del catálogo (45 actividades)
 * pero estaba concentrada en primero y tercero: **dos actividades para
 * quinto y sexto**. Un niño que necesita apoyos no deja de necesitarlos
 * al cumplir diez años; lo que cambia es la forma.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  APOYOS, NO DIAGNÓSTICOS
 * ─────────────────────────────────────────────────────────────────────
 *
 * Se mantiene la regla del resto de la categoría: **ninguna etiqueta ni
 * ningún título lleva el nombre de un trastorno**. Un docente asigna
 * actividades a un curso y el nombre de lo asignado lo ven el niño y sus
 * compañeros; una actividad titulada con un diagnóstico es un dato de
 * salud de un menor puesto a la vista de su clase.
 *
 * Las palabras TDAH y autismo viven en `actividades/apoyos.php`, la guía
 * para adultos. Aquí se nombra el obstáculo: concentrarse, organizarse,
 * entender lo que no se dice.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EN SEXTO EL APOYO ES OTRO
 * ─────────────────────────────────────────────────────────────────────
 *
 * A los once, lo que traba no suele ser leer una consigna: es **sostener
 * una tarea larga hasta el final**, acordarse de que existe, y manejarse
 * con reglas sociales que ya nadie explica. Eso es lo que entrenan estas
 * seis.
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
    ['slug' => 'paso-a-paso', 'name' => 'Paso a Paso', 'icon' => '🪜', 'sort_order' => 3,
     'description' => 'Rutinas anticipadas con imágenes, una cosa a la vez.'],
    ['slug' => 'rutinas-y-anticipacion', 'name' => 'Rutinas y Anticipación', 'icon' => '📅', 'sort_order' => 5,
     'description' => 'Saber qué viene después: secuencias visuales que ordenan el día.'],
    ['slug' => 'atencion-y-foco', 'name' => 'Atención y Foco', 'icon' => '🎯', 'sort_order' => 7,
     'description' => 'Encontrar lo que importa entre todo lo demás, y no soltarlo a mitad de camino.'],
    ['slug' => 'organizarme-solo', 'name' => 'Organizarme Solo', 'icon' => '🎒', 'sort_order' => 9,
     'description' => 'Planear, acordarse y empezar: lo que hace falta antes de hacer la tarea.'],
    ['slug' => 'calma-y-cuerpo', 'name' => 'Calma y Cuerpo', 'icon' => '🌬️', 'sort_order' => 10,
     'description' => 'Notar lo que avisa el cuerpo y saber qué hacer con eso. Sin reloj y sin prisa.'],
    ['slug' => 'entender-a-los-demas', 'name' => 'Entender a los Demás', 'icon' => '🫂', 'sort_order' => 11,
     'description' => 'Caras, tonos y turnos: lo que dicen las personas sin decirlo con palabras.'],
    ['slug' => 'lo-que-no-se-dice', 'name' => 'Lo que No se Dice', 'icon' => '💬', 'sort_order' => 13,
     'description' => 'Frases que no significan lo que dicen, y reglas que nadie explica en voz alta.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR
// =====================================================================

[
    'slug'  => 'que-viene-ahora',
    'title' => '¿Qué viene ahora?',
    'description' => 'Saber qué toca después tranquiliza. Aquí se ve el día entero de un vistazo.',
    'objective' => 'Anticipar la secuencia de la jornada apoyándose en imágenes.',
    'icon' => '⏭️', 'nivel' => 'preescolar', 'bloque' => 'rutinas-y-anticipacion',
    'duracion' => 7, 'tags' => ['anticipacion', 'secuencias', 'observacion'],
    'estaciones' => [

        est('El día en el colegio', 'Ordena lo que pasa', '🏫', 'ordenar_secuencia', [
            'title' => 'Ordena el día en el colegio',
            'items' => ['Llegar y saludar', 'Trabajar en el salón', 'Recreo',
                        'Almuerzo', 'Volver al salón', 'Salir a casa'],
        ]),

        est('¿Qué va después?', 'Piensa en lo que sigue', '➡️', 'opcion_multiple', [
            omp('Después de despertarme…',    ['Me levanto', 'Me duermo', 'Almuerzo'], 'Me levanto', '🌅'),
            omp('Después de comer…',          ['Me lavo los dientes', 'Desayuno otra vez', 'Me acuesto a jugar en la mesa'], 'Me lavo los dientes', '🪥'),
            omp('Después del recreo…',        ['Vuelvo al salón', 'Me voy a casa', 'Desayuno'], 'Vuelvo al salón'),
            omp('Antes de salir a casa…',     ['Recojo mis cosas', 'Empiezo una tarea', 'Me duermo'], 'Recojo mis cosas', '🎒'),
            omp('Saber qué viene me hace sentir…', ['Más tranquilo', 'Más nervioso', 'Igual'], 'Más tranquilo'),
        ]),

        est('Cuando cambia el plan', 'A veces pasa, y se puede', '🔄', 'opcion_multiple', [
            omp('Hoy no hay recreo porque llueve. ¿Qué hago?', ['Jugar dentro', 'Llorar todo el día', 'Salir igual'], 'Jugar dentro', '🌧️'),
            omp('Mi profe de siempre no vino. ¿Qué hago?',    ['Conocer al que vino', 'Irme', 'Esconderme'], 'Conocer al que vino'),
            omp('Cambiaron el salón. ¿Qué hago?',             ['Preguntar dónde es', 'Quedarme afuera', 'Gritar'], 'Preguntar dónde es'),
            omp('Si algo cambia y me pone nervioso…',         ['Lo digo', 'Me lo guardo', 'Me escondo'], 'Lo digo'),
            omp('Los cambios…',                               ['Pasan a veces', 'Nunca pasan', 'Siempre son malos'], 'Pasan a veces'),
        ]),

        est('Une el momento con su dibujo', 'Cada parte del día', '🔗', 'emparejar', [
            ['e' => '🌅', 'w' => 'Mañana'],
            ['e' => '🍽️', 'w' => 'Almuerzo'],
            ['e' => '⚽', 'w' => 'Recreo'],
            ['e' => '📚', 'w' => 'Clase'],
            ['e' => '🎒', 'w' => 'Salida'],
            ['e' => '🌙', 'w' => 'Noche'],
        ]),
    ],
],

[
    'slug'  => 'mirar-una-cosa-a-la-vez',
    'title' => 'Mirar una cosa a la vez',
    'description' => 'Cuando hay mucho en la pantalla, buscar despacio lo que se pide.',
    'objective' => 'Sostener una consigna visual simple filtrando distractores.',
    'icon' => '👁️', 'nivel' => 'preescolar', 'bloque' => 'atencion-y-foco',
    'duracion' => 7, 'tags' => ['foco', 'atencion', 'observacion'],
    'estaciones' => [

        est('Busca solo uno', 'Sin prisa', '🔍', 'seleccion_imagenes',
            conTitulo('Toca solo las manzanas', 'Nada más', [
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '🍐', 'n' => 'Pera',    'ok' => false],
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '🍊', 'n' => 'Naranja', 'ok' => false],
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '🍇', 'n' => 'Uvas',    'ok' => false],
            ])),

        est('Busca el que falta', 'Uno no está', '❓', 'opcion_multiple', [
            omp('🐶 🐱 🐰 ¿cuál NO está? El pez o el gato…', ['El pez', 'El gato', 'El perro'], 'El pez'),
            omp('🔴 🔵 ¿cuál NO está? El verde o el rojo…',  ['El verde', 'El rojo', 'El azul'], 'El verde'),
            omp('☀️ 🌙 ¿cuál NO está? La estrella o la Luna…', ['La estrella', 'La Luna', 'El Sol'], 'La estrella'),
            omp('🚗 🚌 ¿cuál NO está? El avión o el bus…',   ['El avión', 'El bus', 'El carro'], 'El avión'),
            omp('Para encontrar algo conviene…',             ['Mirar despacio', 'Mirar rápido', 'No mirar'], 'Mirar despacio'),
        ]),

        est('Solo los que son iguales', 'Mira bien antes de tocar', '👀', 'seleccion_imagenes',
            conTitulo('Toca todas las estrellas', 'Solo las estrellas', [
                ['e' => '⭐', 'n' => 'Estrella', 'ok' => true],
                ['e' => '❤️', 'n' => 'Corazón',  'ok' => false],
                ['e' => '⭐', 'n' => 'Estrella', 'ok' => true],
                ['e' => '🔺', 'n' => 'Triángulo','ok' => false],
                ['e' => '⭐', 'n' => 'Estrella', 'ok' => true],
                ['e' => '⚪', 'n' => 'Círculo',  'ok' => false],
                ['e' => '⭐', 'n' => 'Estrella', 'ok' => true],
                ['e' => '⬜', 'n' => 'Cuadrado', 'ok' => false],
            ])),

        est('Terminar lo que empecé', 'Hasta el final', '🏁', 'opcion_multiple', [
            omp('Empecé un dibujo y me aburrí. Lo mejor es…', ['Terminarlo aunque sea despacio', 'Dejarlo', 'Romperlo'], 'Terminarlo aunque sea despacio'),
            omp('Si es muy largo, puedo…',        ['Hacerlo por partes', 'Dejarlo', 'Correr'], 'Hacerlo por partes'),
            omp('Si me distraigo, ¿qué hago?',    ['Vuelvo a lo que estaba', 'Empiezo otra cosa', 'Me voy'], 'Vuelvo a lo que estaba'),
            omp('Terminar algo se siente…',       ['Bien', 'Mal', 'Igual'], 'Bien'),
            omp('Descansar un momento y volver es…', ['Buena idea', 'Hacer trampa', 'Rendirse'], 'Buena idea'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR
// =====================================================================

[
    'slug'  => 'tareas-largas-sin-perderse',
    'title' => 'Tareas largas sin perderse',
    'description' => 'Un trabajo de varios días no se hace de una vez: se parte y se agenda.',
    'objective' => 'Descomponer una tarea extensa en pasos con fechas propias.',
    'icon' => '🗓️', 'nivel' => 'primaria-superior', 'bloque' => 'organizarme-solo',
    'duracion' => 14, 'tags' => ['organizacion', 'anticipacion', 'logica'],
    'estaciones' => [

        est('Partir el trabajo', 'Un trabajo grande son cinco pequeños', '✂️', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se hace un trabajo de varios días',
            'items' => ['Leer bien qué piden y para cuándo', 'Partirlo en pasos',
                        'Ponerle fecha a cada paso', 'Hacer el primer paso hoy',
                        'Revisar el avance a mitad', 'Repasar antes de entregar'],
        ]),

        est('¿Por dónde empiezo?', 'Empezar es la mitad', '🚀', 'opcion_multiple', [
            omp('Una tarea grande me agobia. Lo mejor es…', ['Hacer solo el primer paso', 'Esperar a tener ganas', 'Hacerlo todo de golpe'], 'Hacer solo el primer paso'),
            omp('«Lo haré cuando tenga tiempo» suele acabar en…', ['No hacerlo', 'Hacerlo mejor', 'Hacerlo antes'], 'No hacerlo'),
            omp('Para arrancar ayuda…',             ['Ponerme cinco minutos y ya', 'Pensar mucho', 'Ordenar el cuarto primero'], 'Ponerme cinco minutos y ya'),
            omp('Si no entiendo la tarea…',         ['Pregunto el mismo día', 'Espero al final', 'La invento'], 'Pregunto el mismo día'),
            omp('Dejar todo para el último día…',   ['Sale peor casi siempre', 'Sale mejor', 'Da igual'], 'Sale peor casi siempre'),
        ]),

        est('Dónde apunto las cosas', 'La memoria no basta', '📝', 'opcion_multiple', [
            omp('Lo más seguro para acordarme de una entrega es…', ['Apuntarla en un sitio fijo', 'Confiar en la memoria', 'Que me avisen'], 'Apuntarla en un sitio fijo'),
            omp('El sitio para apuntar debe ser…',  ['Siempre el mismo', 'Distinto cada vez', 'Secreto'], 'Siempre el mismo'),
            omp('Conviene mirar la agenda…',        ['Todos los días', 'Una vez al mes', 'Nunca'], 'Todos los días'),
            omp('Apuntar solo «tarea» sirve poco porque…', ['No dice qué ni para cuándo', 'Es corto', 'Es feo'], 'No dice qué ni para cuándo'),
            omp('Tachar lo hecho sirve para…',      ['Ver el avance', 'Nada', 'Gastar tinta'], 'Ver el avance'),
        ]),

        est('Reto de organizarse', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Un trabajo largo se hace…',       ['Por pasos con fecha', 'De una sentada', 'El último día'], 'Por pasos con fecha'),
            reto('La mejor forma de empezar es…',   ['Hacer el paso más pequeño', 'Esperar inspiración', 'Planear una semana'], 'Hacer el paso más pequeño'),
            reto('Las fechas se apuntan…',          ['El día que las dicen', 'Cuando me acuerde', 'Nunca'], 'El día que las dicen'),
            reto('Revisar a mitad sirve para…',     ['Corregir a tiempo', 'Perder tiempo', 'Nada'], 'Corregir a tiempo'),
            reto('Si me atraso, lo mejor es…',      ['Avisar y replanear', 'Callarme', 'Rendirme'], 'Avisar y replanear'),
        ]),
    ],
],

[
    'slug'  => 'concentrarse-cuando-cuesta',
    'title' => 'Concentrarse cuando cuesta',
    'description' => 'Preparar el sitio, quitar lo que distrae y trabajar por tandas.',
    'objective' => 'Aplicar estrategias de control atencional durante el estudio.',
    'icon' => '🎯', 'nivel' => 'primaria-superior', 'bloque' => 'atencion-y-foco',
    'duracion' => 14, 'tags' => ['foco', 'atencion', 'organizacion'],
    'estaciones' => [

        est('Preparar el sitio', 'Antes de empezar', '🪑', 'juego_rapido',
            conTitulo('¿AYUDA a concentrarse?', 'Piensa en tu mesa de estudio', [
                ['e' => '📵', 'n' => 'Celular en otro cuarto',   'ok' => true],
                ['e' => '📱', 'n' => 'Celular al lado, boca arriba','ok' => false],
                ['e' => '💡', 'n' => 'Buena luz',                'ok' => true],
                ['e' => '📺', 'n' => 'Televisor encendido',      'ok' => false],
                ['e' => '🧹', 'n' => 'Mesa despejada',           'ok' => true],
                ['e' => '💧', 'n' => 'Agua a mano',              'ok' => true],
                ['e' => '🎮', 'n' => 'El juego abierto «un rato»','ok' => false],
                ['e' => '📋', 'n' => 'Saber qué voy a hacer',    'ok' => true],
            ])),

        est('Trabajar por tandas', 'Un rato y un descanso', '⏲️', 'opcion_multiple', [
            omp('Estudiar tres horas seguidas suele…', ['Rendir menos al final', 'Rendir más', 'Ser igual'], 'Rendir menos al final'),
            omp('Una tanda razonable para mi edad es…', ['Unos 20 o 25 minutos', 'Tres horas', 'Dos minutos'], 'Unos 20 o 25 minutos'),
            omp('En el descanso conviene…',          ['Levantarse y moverse', 'Abrir el celular', 'Seguir igual'], 'Levantarse y moverse'),
            omp('Empezar por lo más difícil suele…', ['Funcionar, si estoy fresco', 'Ser un error siempre', 'Dar igual'], 'Funcionar, si estoy fresco'),
            omp('Si me distraigo mucho, la culpa es…', ['Casi siempre del entorno', 'Solo mía', 'De nadie'], 'Casi siempre del entorno'),
        ]),

        est('Cuando la cabeza se va', 'Volver sin pelearse consigo mismo', '🔁', 'opcion_multiple', [
            omp('Me di cuenta de que llevo cinco minutos pensando en otra cosa. ¿Qué hago?', ['Vuelvo sin regañarme', 'Me enojo conmigo', 'Lo dejo todo'], 'Vuelvo sin regañarme'),
            omp('Se me ocurre algo que tengo que hacer. Lo mejor es…', ['Apuntarlo y seguir', 'Hacerlo ya', 'Olvidarlo'], 'Apuntarlo y seguir'),
            omp('Distraerse es…',                  ['Normal, le pasa a todos', 'Un defecto raro', 'Imposible de manejar'], 'Normal, le pasa a todos'),
            omp('Si no logro concentrarme hoy…',   ['Cambio de tarea o descanso', 'Me castigo', 'Sigo igual dos horas'], 'Cambio de tarea o descanso'),
            omp('Leer con un lápiz en la mano…',   ['Ayuda a mantener el foco', 'Estorba', 'Da igual'], 'Ayuda a mantener el foco'),
        ]),

        est('Ordena una sesión de estudio', 'Con principio y final', '📚', 'ordenar_secuencia', [
            'title' => 'Ordena una sesión de estudio que funciona',
            'items' => ['Decidir qué voy a hacer', 'Quitar lo que distrae',
                        'Trabajar una tanda', 'Descansar moviéndome',
                        'Otra tanda', 'Revisar qué logré'],
        ]),
    ],
],

[
    'slug'  => 'lo-que-se-dice-sin-decirlo',
    'title' => 'Lo que se dice sin decirlo',
    'description' => 'Ironías, indirectas y frases hechas: cuando las palabras no son literales.',
    'objective' => 'Interpretar lenguaje figurado e intención comunicativa en contexto.',
    'icon' => '🗨️', 'nivel' => 'primaria-superior', 'bloque' => 'lo-que-no-se-dice',
    'duracion' => 14, 'tags' => ['lenguaje-claro', 'social', 'comprension'],
    'estaciones' => [

        est('Frases hechas', 'No significan lo que dicen', '🗣️', 'opcion_multiple', [
            omp('«Está lloviendo a cántaros» quiere decir…', ['Llueve muchísimo', 'Caen vasijas', 'Llueve poco'], 'Llueve muchísimo'),
            omp('«Se me fue el santo al cielo» quiere decir…', ['Se me olvidó', 'Fui a la iglesia', 'Me morí'], 'Se me olvidó'),
            omp('«Meter la pata» es…',              ['Equivocarse', 'Caminar', 'Ganar'], 'Equivocarse'),
            omp('«Costar un ojo de la cara» es…',   ['Ser carísimo', 'Doler', 'Ser feo'], 'Ser carísimo'),
            omp('Estas frases se entienden…',       ['Por costumbre, no por las palabras', 'Palabra por palabra', 'Nunca'], 'Por costumbre, no por las palabras'),
        ]),

        est('El tono cambia todo', 'La misma frase, distinta intención', '🎭', 'opcion_multiple', [
            omp('«Qué bien» dicho con cara de fastidio significa…', ['Lo contrario', 'Lo mismo', 'Nada'], 'Lo contrario'),
            omp('A decir lo contrario de lo que se piensa se le llama…', ['Ironía', 'Mentira', 'Pregunta'], 'Ironía'),
            omp('Si no sé si van en serio, puedo…',  ['Preguntar «¿lo dices en serio?»', 'Adivinar', 'Enojarme'], 'Preguntar «¿lo dices en serio?»'),
            omp('«¿No tienes frío?» en casa suele ser…', ['Una indirecta para cerrar la ventana', 'Una pregunta médica', 'Una orden'], 'Una indirecta para cerrar la ventana'),
            omp('Preguntar cuando no entiendo es…',  ['Lo más sensato', 'Una torpeza', 'De mala educación'], 'Lo más sensato'),
        ]),

        est('Reglas que nadie explica', 'Pero todos esperan', '📋', 'opcion_multiple', [
            omp('En una conversación de grupo, hablar…', ['Por turnos', 'Todos a la vez', 'Solo yo'], 'Por turnos'),
            omp('Si alguien mira el reloj mientras hablo…', ['Puede que tenga prisa', 'Le encanta', 'No significa nada'], 'Puede que tenga prisa'),
            omp('Entrar en un grupo que ya habla se hace…', ['Escuchando primero', 'Cambiando el tema', 'Gritando'], 'Escuchando primero'),
            omp('Contar un detalle larguísimo cuando el otro tiene prisa…', ['Conviene acortarlo', 'Es obligatorio', 'Está bien siempre'], 'Conviene acortarlo'),
            omp('Estas reglas se aprenden…',        ['Observando y preguntando', 'De nacimiento', 'En un libro de leyes'], 'Observando y preguntando'),
        ]),

        est('Reto de lo implícito', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('«Me muero de hambre» significa…', ['Tengo mucha hambre', 'Voy a morir', 'No tengo hambre'], 'Tengo mucha hambre'),
            reto('La ironía dice…',                 ['Lo contrario de lo que se piensa', 'La verdad', 'Un dato'], 'Lo contrario de lo que se piensa'),
            reto('Si dudo de la intención, lo mejor es…', ['Preguntar', 'Suponer lo peor', 'Callarme'], 'Preguntar'),
            reto('Las indirectas…',                 ['Se entienden por el contexto', 'Son literales', 'No existen'], 'Se entienden por el contexto'),
            reto('Que a alguien le cueste esto es…', ['Normal, y se puede aprender', 'Un defecto', 'Imposible de mejorar'], 'Normal, y se puede aprender'),
        ]),
    ],
],

[
    'slug'  => 'calma-en-momentos-dificiles',
    'title' => 'Calma en momentos difíciles',
    'description' => 'Un examen, una discusión, un cambio de última hora: qué hacer con el cuerpo.',
    'objective' => 'Aplicar técnicas de regulación fisiológica ante activación intensa.',
    'icon' => '🪷', 'nivel' => 'primaria-superior', 'bloque' => 'calma-y-cuerpo',
    'duracion' => 13, 'tags' => ['autorregulacion', 'emociones', 'cuerpo'],
    'estaciones' => [

        est('Lo que avisa el cuerpo', 'Antes de que sea tarde', '🫀', 'opcion_multiple', [
            omp('Antes de estallar, el cuerpo suele…', ['Avisar con señales', 'No avisar nunca', 'Dormirse'], 'Avisar con señales'),
            omp('Una señal frecuente es…',        ['Respiración corta y rápida', 'Respiración lenta', 'Frío en los pies'], 'Respiración corta y rápida'),
            omp('Otra señal es…',                 ['Mandíbula o puños apretados', 'Manos sueltas', 'Bostezar'], 'Mandíbula o puños apretados'),
            omp('Notar la señal a tiempo permite…', ['Actuar antes de estallar', 'Nada', 'Estallar mejor'], 'Actuar antes de estallar'),
            omp('Estas señales son…',             ['Normales y de todos', 'Un defecto', 'Una enfermedad'], 'Normales y de todos'),
        ]),

        est('Respiración que funciona', 'Soltar más largo que tomar', '🌬️', 'ordenar_secuencia', [
            'title' => 'Ordena una respiración para calmarse',
            'items' => ['Parar lo que estoy haciendo', 'Soltar los hombros',
                        'Tomar aire contando hasta cuatro', 'Soltarlo contando hasta seis',
                        'Repetir cinco veces', 'Volver a lo que estaba'],
        ]),

        est('Anclarse en el presente', 'Volver al aquí', '⚓', 'opcion_multiple', [
            omp('Nombrar cinco cosas que veo sirve para…', ['Volver al presente', 'Distraerme para siempre', 'Nada'], 'Volver al presente'),
            omp('Apoyar los pies en el suelo y notarlo ayuda porque…', ['Trae la atención al cuerpo', 'Es mágico', 'Cansa'], 'Trae la atención al cuerpo'),
            omp('Beber agua despacio…',           ['Baja un poco la activación', 'La sube', 'Da igual'], 'Baja un poco la activación'),
            omp('Salir un minuto del lugar…',     ['Suele ayudar', 'Es huir', 'Está prohibido'], 'Suele ayudar'),
            omp('Estas técnicas funcionan mejor si…', ['Se practican antes, en calma', 'Se usan solo en crisis', 'No se practican'], 'Se practican antes, en calma'),
        ]),

        est('Reto de la calma', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Lo primero al notar que me altero es…', ['Parar', 'Seguir más rápido', 'Discutir'], 'Parar'),
            reto('Al respirar para calmarse conviene…', ['Soltar más largo que tomar', 'Tomar muy rápido', 'Aguantar mucho'], 'Soltar más largo que tomar'),
            reto('Practicar en calma sirve para…',  ['Que funcione en la crisis', 'Nada', 'Perder tiempo'], 'Que funcione en la crisis'),
            reto('Si nada me funciona hoy…',        ['Pido ayuda', 'Me rindo', 'Lo escondo'], 'Pido ayuda'),
            reto('Calmarse no es…',                 ['Dejar de sentir', 'Bajar la activación', 'Poder pensar'], 'Dejar de sentir'),
        ]),
    ],
],

[
    'slug'  => 'entender-el-punto-de-vista',
    'title' => 'Entender el punto de vista',
    'description' => 'El otro sabe cosas que yo no sé, y no sabe cosas que yo sí.',
    'objective' => 'Adoptar la perspectiva del otro para interpretar conductas y mensajes.',
    'icon' => '🫂', 'nivel' => 'primaria-superior', 'bloque' => 'entender-a-los-demas',
    'duracion' => 14, 'tags' => ['social', 'comprension', 'deduccion'],
    'estaciones' => [

        est('Él no sabe lo que yo sé', 'La información no es la misma', '🧠', 'opcion_multiple', [
            omp('Yo vi dónde quedó el libro; mi amigo no. Él lo buscará…', ['Donde lo dejó él', 'Donde está', 'No lo buscará'], 'Donde lo dejó él'),
            omp('Cuento un chiste de algo que solo yo viví. El otro…', ['No lo entenderá', 'Se reirá igual', 'Ya lo sabe'], 'No lo entenderá'),
            omp('Antes de contar algo conviene…',  ['Dar el contexto', 'Empezar por el final', 'No explicar'], 'Dar el contexto'),
            omp('Si alguien no entiende, quizá…',  ['Le falta información', 'Es tonto', 'No le importa'], 'Le falta información'),
            omp('«Es obvio» suele significar…',    ['Obvio para mí', 'Obvio para todos', 'Verdadero'], 'Obvio para mí'),
        ]),

        est('¿Por qué habrá hecho eso?', 'Busca más de una explicación', '🤔', 'opcion_multiple', [
            omp('Un amigo no me saludó. Puede ser que…', ['No me viera', 'Me odie seguro', 'Nunca más me hable'], 'No me viera'),
            omp('No respondió mi mensaje en una hora. Puede ser que…', ['Esté ocupado', 'Ya no sea mi amigo', 'Me esté ignorando seguro'], 'Esté ocupado'),
            omp('Antes de enojarme conviene…',    ['Pensar otras explicaciones', 'Enojarme ya', 'Dejar de hablarle'], 'Pensar otras explicaciones'),
            omp('La forma más rápida de saberlo es…', ['Preguntar', 'Suponer', 'Preguntarle a otro'], 'Preguntar'),
            omp('Suponer siempre lo peor hace que…', ['Me sienta mal sin motivo', 'Acierte más', 'Me protejan'], 'Me sienta mal sin motivo'),
        ]),

        est('Leer la situación', 'El mismo gesto significa cosas distintas', '🎭', 'opcion_multiple', [
            omp('Alguien serio en un funeral está…', ['Como corresponde', 'Enojado conmigo', 'Aburrido'], 'Como corresponde'),
            omp('Alguien callado en una fiesta puede…', ['Ser tímido o estar cansado', 'Odiar a todos', 'Estar bravo conmigo'], 'Ser tímido o estar cansado'),
            omp('Un «ya voy» rápido mientras trabaja significa…', ['Está ocupado, no molesto', 'Está furioso', 'No irá'], 'Está ocupado, no molesto'),
            omp('Para interpretar bien hace falta mirar…', ['La situación completa', 'Solo la cara', 'Solo las palabras'], 'La situación completa'),
            omp('Si me equivoco interpretando…',   ['Se aclara preguntando', 'Es irreparable', 'Nunca pasa'], 'Se aclara preguntando'),
        ]),

        est('Reto de ponerse en el lugar', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('El otro sabe…',                  ['Cosas distintas a las mías', 'Exactamente lo mismo', 'Todo'], 'Cosas distintas a las mías'),
            reto('Ante una conducta rara, lo útil es…', ['Buscar varias explicaciones', 'Quedarse con la peor', 'Ignorarlo'], 'Buscar varias explicaciones'),
            reto('«Es obvio» significa obvio…',    ['Para mí', 'Para todos', 'Para nadie'], 'Para mí'),
            reto('La mejor forma de aclarar una duda con alguien es…', ['Preguntarle a esa persona', 'Preguntarle a otros', 'Suponer'], 'Preguntarle a esa persona'),
            reto('Dar contexto al contar algo…',   ['Ayuda a que me entiendan', 'Aburre siempre', 'Sobra'], 'Ayuda a que me entiendan'),
        ]),
    ],
],

],
];
