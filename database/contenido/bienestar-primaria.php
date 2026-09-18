<?php
/**
 * bienestar-primaria.php — Vida y Bienestar de 1.º a 6.º
 *
 * Escrito sobre las mallas de Deporte (K1 a K6) y los ejes de cuerpo y
 * salud del Sachunterricht (K3, K6) de `MallasPrimaria/`.
 *
 * La malla de Deporte es de una materia que se hace con el cuerpo, y eso
 * no cabe en una pantalla: aquí no se puede correr ni nadar. Lo que sí
 * cabe —y es justo lo que la malla pide además del movimiento— es todo lo
 * que la rodea: por qué se calienta antes, qué le pasa al cuerpo cuando se
 * esfuerza, qué alimento da energía, qué reglas tiene cada deporte y cómo
 * se convive en un equipo cuando se gana y cuando se pierde.
 *
 * Ninguna actividad de este archivo le dice a un niño que haga ejercicio
 * frente a la pantalla. Le explica por qué vale la pena apagarla.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'bienestar',
    'name'       => 'Vida y Bienestar',
    'tagline'    => 'Cuidar el cuerpo, la mente y las decisiones de cada día',
    'icon'       => '💚',
    'color'      => '#26a69a',
    'sort_order' => 12,
],

'bloques' => [
    ['slug' => 'movimiento-y-deporte', 'name' => 'Movimiento y Deporte', 'icon' => '⚽', 'sort_order' => 4,
     'description' => 'Habilidades motrices, calentamiento y las reglas de los deportes que se juegan en el colegio.'],
    ['slug' => 'alimentacion-y-energia', 'name' => 'Alimentación y Energía', 'icon' => '🥗', 'sort_order' => 5,
     'description' => 'Qué come el cuerpo, de dónde saca la energía y cómo se hidrata.'],
    ['slug' => 'mente-y-emociones', 'name' => 'Mente y Emociones', 'icon' => '🧘', 'sort_order' => 6,
     'description' => 'Dormir, calmarse, concentrarse y pedir ayuda: la salud que no se ve.'],
    ['slug' => 'seguridad-y-cuidado', 'name' => 'Seguridad y Cuidado', 'icon' => '🚑', 'sort_order' => 7,
     'description' => 'Prevenir accidentes, reaccionar bien y saber a quién acudir.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · MOVIMIENTO Y DEPORTE
// =====================================================================

[
    'slug'  => 'habilidades-motrices',
    'title' => 'Habilidades motrices',
    'description' => 'Correr, saltar, girar, lanzar y trepar: los movimientos básicos del cuerpo.',
    'objective' => 'Reconocer las habilidades motrices básicas y su papel en el desarrollo físico.',
    'icon' => '🏃', 'nivel' => 'primaria-inicial', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 11, 'tags' => ['cuerpo', 'observacion', 'juego'],
    'estaciones' => [

        est('Los movimientos básicos', 'Une la acción con su dibujo', '🔗', 'emparejar', [
            ['e' => '🏃', 'w' => 'Correr'],
            ['e' => '🤸', 'w' => 'Girar'],
            ['e' => '🏀', 'w' => 'Lanzar'],
            ['e' => '🧗', 'w' => 'Trepar'],
            ['e' => '🦘', 'w' => 'Saltar'],
            ['e' => '🚶', 'w' => 'Caminar'],
        ]),

        est('Equilibrio y coordinación', 'Dos capacidades que se entrenan', '🤹', 'opcion_multiple', [
            omp('¿Qué es el equilibrio?',              ['Mantener el cuerpo estable sin caerse', 'Correr rápido', 'Ser fuerte'], 'Mantener el cuerpo estable sin caerse'),
            omp('¿Qué es la coordinación?',            ['Mover varias partes del cuerpo en orden', 'Levantar peso', 'Correr lejos'], 'Mover varias partes del cuerpo en orden'),
            omp('¿Qué ayuda a mejorar el equilibrio?', ['Practicar apoyando un solo pie', 'Comer más', 'Ver televisión'], 'Practicar apoyando un solo pie'),
            omp('¿Se puede mejorar la coordinación?',  ['Sí, con práctica', 'No, se nace con ella', 'Solo de adulto'], 'Sí, con práctica'),
            omp('¿Qué actividad exige mucha coordinación?', ['Driblar un balón', 'Estar sentado', 'Dormir'], 'Driblar un balón'),
        ]),

        est('Antes de moverse', 'El calentamiento', '🔥', 'opcion_multiple', [
            omp('¿Para qué sirve calentar?',           ['Prepara el cuerpo y evita lesiones', 'Para cansarse', 'Para nada'], 'Prepara el cuerpo y evita lesiones'),
            omp('¿Cuándo se calienta?',                ['Antes de la actividad física', 'Después', 'Nunca'], 'Antes de la actividad física'),
            omp('¿Qué se hace al terminar?',           ['Estirar y volver a la calma', 'Parar de golpe', 'Correr más'], 'Estirar y volver a la calma'),
            omp('¿Qué pasa si hago un esfuerzo fuerte sin calentar?', ['Puedo lesionarme', 'Nada', 'Rindo más'], 'Puedo lesionarme'),
            omp('¿Cómo debe empezar un calentamiento?', ['Suave y subiendo poco a poco', 'Al máximo desde el principio', 'Corriendo diez kilómetros'], 'Suave y subiendo poco a poco'),
        ]),

        est('Ordena la clase', 'La estructura de una sesión', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena las partes de una clase de deporte',
            'items' => ['Calentamiento', 'Ejercicios de técnica', 'Juego o práctica', 'Estiramiento', 'Hidratación y descanso'],
        ]),
    ],
],

[
    'slug'  => 'juegos-predeportivos',
    'title' => 'Juegos predeportivos',
    'description' => 'Juegos con reglas que preparan para un deporte de verdad.',
    'objective' => 'Comprender la función de las reglas y del juego limpio en la actividad deportiva.',
    'icon' => '🎯', 'nivel' => 'primaria-media', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 12, 'tags' => ['juego', 'convivencia', 'reto'],
    'estaciones' => [

        est('¿Por qué hay reglas?', 'Sin reglas no hay juego', '📜', 'opcion_multiple', [
            omp('¿Para qué sirven las reglas de un juego?', ['Para que todos jueguen en las mismas condiciones', 'Para molestar', 'Para ganar'], 'Para que todos jueguen en las mismas condiciones'),
            omp('¿Qué pasa si alguien hace trampa?',   ['El juego pierde sentido', 'Gana justamente', 'No pasa nada'], 'El juego pierde sentido'),
            omp('¿Quién hace cumplir las reglas?',     ['El árbitro o los propios jugadores', 'Nadie', 'El público'], 'El árbitro o los propios jugadores'),
            omp('¿Se pueden cambiar las reglas de un juego?', ['Sí, si todos están de acuerdo antes', 'No, nunca', 'Durante el partido'], 'Sí, si todos están de acuerdo antes'),
            omp('¿Qué es un juego predeportivo?',      ['Un juego que prepara para un deporte', 'Un partido oficial', 'Un descanso'], 'Un juego que prepara para un deporte'),
        ]),

        est('Cooperar y competir', 'Dos formas de jugar', '🤝', 'opcion_multiple', [
            omp('¿Qué es cooperar?',                 ['Trabajar junto a otros por un objetivo común', 'Ganarle a otro', 'Jugar solo'], 'Trabajar junto a otros por un objetivo común'),
            omp('¿Qué es competir?',                 ['Medirse con otro respetando las reglas', 'Pelear', 'Hacer trampa'], 'Medirse con otro respetando las reglas'),
            omp('¿Se puede competir y ser amigos?',  ['Sí, perfectamente', 'No', 'Solo fuera del campo'], 'Sí, perfectamente'),
            omp('Si mi equipo pierde, ¿qué hago?',   ['Felicito al otro equipo', 'Me enojo con todos', 'Digo que hicieron trampa'], 'Felicito al otro equipo'),
            omp('Si mi equipo gana, ¿qué hago?',     ['Celebro sin humillar al otro', 'Me burlo', 'Grito que son malos'], 'Celebro sin humillar al otro'),
        ]),

        est('Deportes y sus reglas', 'Cada uno tiene las suyas', '🏅', 'opcion_multiple', [
            omp('¿Cuántos jugadores tiene un equipo de baloncesto en cancha?', ['5', '11', '7'], '5', '🏀'),
            omp('En voleibol, ¿cuántos toques puede dar un equipo antes de pasar el balón?', ['3', '5', '1'], '3', '🏐'),
            omp('¿En qué deporte NO se puede tocar el balón con la mano?', ['Fútbol', 'Baloncesto', 'Voleibol'], 'Fútbol', '⚽'),
            omp('¿Qué prueba del atletismo mide la velocidad corta?', ['Los 100 metros planos', 'El maratón', 'El salto largo'], 'Los 100 metros planos', '🏃'),
            omp('En natación, ¿cuál es el estilo más común para principiantes?', ['El estilo libre', 'La mariposa', 'La espalda doble'], 'El estilo libre', '🏊'),
        ]),

        est('Juego limpio', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es juego limpio?', 'Responde rápido', [
                ['e' => '🤝', 'n' => 'Ayudar a levantarse al rival',  'ok' => true],
                ['e' => '😡', 'n' => 'Insultar al árbitro',           'ok' => false],
                ['e' => '👏', 'n' => 'Aplaudir una buena jugada ajena', 'ok' => true],
                ['e' => '🦶', 'n' => 'Zancadillear a propósito',      'ok' => false],
                ['e' => '📜', 'n' => 'Aceptar una decisión aunque no guste', 'ok' => true],
                ['e' => '🙄', 'n' => 'Abandonar el partido al ir perdiendo', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'el-cuerpo-en-movimiento',
    'title' => 'El cuerpo en movimiento',
    'description' => 'Qué le pasa al cuerpo cuando hace ejercicio: pulso, respiración y fuerza.',
    'objective' => 'Relacionar el esfuerzo físico con las respuestas del organismo y las capacidades motoras.',
    'icon' => '💓', 'nivel' => 'primaria-superior', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 13, 'tags' => ['cuerpo', 'comprension', 'salud'],
    'estaciones' => [

        est('Lo que pasa al esforzarse', 'El cuerpo responde', '📈', 'opcion_multiple', [
            omp('Al correr, el corazón…',              ['Late más rápido', 'Late más lento', 'Se detiene'], 'Late más rápido'),
            omp('¿Por qué late más rápido?',           ['Para llevar más oxígeno a los músculos', 'Por susto', 'Por el calor'], 'Para llevar más oxígeno a los músculos'),
            omp('Al correr, la respiración…',          ['Se acelera', 'Se detiene', 'No cambia'], 'Se acelera'),
            omp('¿Por qué sudamos al hacer ejercicio?', ['Para bajar la temperatura del cuerpo', 'Por debilidad', 'Por el frío'], 'Para bajar la temperatura del cuerpo'),
            omp('¿Qué es la frecuencia cardíaca?',     ['El número de latidos por minuto', 'La fuerza del músculo', 'La velocidad al correr'], 'El número de latidos por minuto'),
        ]),

        est('Las capacidades físicas', 'Cuatro cosas que se entrenan', '💪', 'opcion_multiple', [
            omp('¿Qué es la resistencia?',            ['Aguantar un esfuerzo durante mucho tiempo', 'Levantar mucho peso', 'Ser flexible'], 'Aguantar un esfuerzo durante mucho tiempo'),
            omp('¿Qué es la fuerza?',                 ['La capacidad de vencer una resistencia', 'Correr rápido', 'Estirarse'], 'La capacidad de vencer una resistencia'),
            omp('¿Qué es la velocidad?',              ['Hacer un movimiento en el menor tiempo posible', 'Aguantar mucho', 'Ser fuerte'], 'Hacer un movimiento en el menor tiempo posible'),
            omp('¿Qué es la flexibilidad?',           ['El rango de movimiento de las articulaciones', 'La fuerza', 'La resistencia'], 'El rango de movimiento de las articulaciones'),
            omp('¿Qué capacidad entrena un maratón?', ['La resistencia', 'La velocidad pura', 'La flexibilidad'], 'La resistencia'),
        ]),

        est('Cuánto ejercicio hace falta', 'Lo que recomiendan los expertos', '⏱️', 'opcion_multiple', [
            omp('¿Cuánto ejercicio al día se recomienda para un niño?', ['Al menos 60 minutos', '5 minutos', 'Tres horas seguidas'], 'Al menos 60 minutos'),
            omp('¿Cuenta jugar en el recreo como ejercicio?', ['Sí, claro', 'No', 'Solo si es fútbol'], 'Sí, claro'),
            omp('¿Es bueno pasar muchas horas sentado?', ['No, conviene levantarse a moverse', 'Sí', 'Da igual'], 'No, conviene levantarse a moverse'),
            omp('¿Qué le hace el ejercicio al ánimo?',  ['Lo mejora', 'Lo empeora', 'Nada'], 'Lo mejora'),
            omp('¿Hay que descansar entre entrenamientos?', ['Sí, el cuerpo se recupera descansando', 'No', 'Solo los adultos'], 'Sí, el cuerpo se recupera descansando'),
        ]),

        est('Crucigrama del cuerpo', 'Cada pista es una definición', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'FUERZA',      'pista' => 'Capacidad de vencer una resistencia'],
                ['w' => 'VELOCIDAD',   'pista' => 'Hacer un movimiento en el menor tiempo'],
                ['w' => 'PULSO',       'pista' => 'El latido que se siente en la muñeca'],
                ['w' => 'OXIGENO',     'pista' => 'Lo que la sangre lleva a los músculos'],
                ['w' => 'SUDOR',       'pista' => 'Así baja el cuerpo su temperatura'],
                ['w' => 'AGUA',        'pista' => 'Lo que hay que beber durante el ejercicio'],
                ['w' => 'DESCANSO',    'pista' => 'Lo que necesita el músculo para recuperarse'],
            ])),

        est('Desafío del deportista', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('¿Qué se debe beber durante el ejercicio?', ['Agua', 'Gaseosa', 'Nada'], 'Agua'),
            reto('¿Qué señal indica que debo parar?',        ['Dolor o mareo', 'Cansancio normal', 'Sudar'], 'Dolor o mareo'),
            reto('¿Por qué conviene variar los deportes?',   ['Se trabajan capacidades distintas', 'Para aburrirse menos solamente', 'No conviene'], 'Se trabajan capacidades distintas'),
            reto('¿Hay que competir para estar en forma?',   ['No, basta con moverse con regularidad', 'Sí', 'Solo compitiendo'], 'No, basta con moverse con regularidad'),
            reto('¿Es normal cansarse al hacer ejercicio?',  ['Sí, es parte del proceso', 'No, es mala señal siempre', 'Solo si está mal hecho'], 'Sí, es parte del proceso'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · ALIMENTACIÓN Y ENERGÍA
// =====================================================================

[
    'slug'  => 'los-grupos-de-alimentos',
    'title' => 'Los grupos de alimentos',
    'description' => 'Qué aporta cada alimento y cómo se arma un plato equilibrado.',
    'objective' => 'Clasificar alimentos por su aporte nutricional y componer una comida equilibrada.',
    'icon' => '🍽️', 'nivel' => 'primaria-inicial', 'bloque' => 'alimentacion-y-energia',
    'duracion' => 11, 'tags' => ['alimentacion', 'clasificacion', 'salud'],
    'estaciones' => [

        est('Cada alimento aporta algo', 'Para qué sirve cada grupo', '🗂️', 'opcion_multiple', [
            omp('¿Qué alimentos dan energía rápida?',   ['Los cereales y el pan', 'La carne', 'El agua'], 'Los cereales y el pan', '🍞'),
            omp('¿Qué alimentos ayudan a crecer y formar músculo?', ['Carnes, huevos y legumbres', 'Los dulces', 'El aceite'], 'Carnes, huevos y legumbres', '🥚'),
            omp('¿Qué alimentos dan vitaminas?',        ['Frutas y verduras', 'Los fritos', 'El azúcar'], 'Frutas y verduras', '🥦'),
            omp('¿Qué alimento aporta calcio para los huesos?', ['La leche', 'La papa', 'El arroz'], 'La leche', '🥛'),
            omp('¿Cuántas comidas principales conviene hacer al día?', ['Tres, más un par de refrigerios', 'Una', 'Diez'], 'Tres, más un par de refrigerios'),
        ]),

        est('El plato equilibrado', 'Cómo repartirlo', '🍱', 'opcion_multiple', [
            omp('¿Qué debería ocupar la mitad del plato?', ['Frutas y verduras', 'Dulces', 'Fritos'], 'Frutas y verduras'),
            omp('¿Con qué frecuencia conviene comer dulces?', ['De vez en cuando', 'En cada comida', 'Nunca jamás'], 'De vez en cuando'),
            omp('¿Es importante desayunar?',            ['Sí, da energía para la mañana', 'No', 'Solo los domingos'], 'Sí, da energía para la mañana'),
            omp('¿Qué es una dieta variada?',           ['Comer de todos los grupos', 'Comer siempre lo mismo', 'Comer poco'], 'Comer de todos los grupos'),
            omp('¿Sirve saltarse comidas para estar sano?', ['No, el cuerpo necesita energía regular', 'Sí', 'Solo la cena'], 'No, el cuerpo necesita energía regular'),
        ]),

        est('Clasifica los alimentos', 'Toca las frutas y verduras', '🥕', 'seleccion_imagenes',
            conTitulo('Toca todas las frutas y verduras', 'Los demás alimentos no van aquí', [
                ['e' => '🍎', 'n' => 'Manzana',  'ok' => true],
                ['e' => '🥕', 'n' => 'Zanahoria', 'ok' => true],
                ['e' => '🍗', 'n' => 'Pollo',    'ok' => false],
                ['e' => '🍌', 'n' => 'Banano',   'ok' => true],
                ['e' => '🍞', 'n' => 'Pan',      'ok' => false],
                ['e' => '🥦', 'n' => 'Brócoli',  'ok' => true],
                ['e' => '🧀', 'n' => 'Queso',    'ok' => false],
                ['e' => '🍅', 'n' => 'Tomate',   'ok' => true],
            ])),

        est('El agua', 'El nutriente que se olvida', '💧', 'opcion_multiple', [
            omp('¿Cuánta agua conviene beber al día?',   ['Entre seis y ocho vasos', 'Uno', 'Ninguno'], 'Entre seis y ocho vasos'),
            omp('¿Cuándo hay que beber más agua?',       ['Cuando hace calor o hago ejercicio', 'Cuando hace frío solamente', 'Nunca'], 'Cuando hace calor o hago ejercicio'),
            omp('¿Es la gaseosa un buen sustituto del agua?', ['No, tiene mucha azúcar', 'Sí', 'Es mejor'], 'No, tiene mucha azúcar'),
            omp('¿Qué señal indica que me falta agua?',  ['Sed y orina oscura', 'Hambre', 'Sueño'], 'Sed y orina oscura'),
            omp('¿Puede el cuerpo vivir sin agua?',      ['No, es indispensable', 'Sí, semanas', 'Solo en verano'], 'No, es indispensable'),
        ]),
    ],
],

[
    'slug'  => 'energia-para-moverse',
    'title' => 'Energía para moverse',
    'description' => 'De dónde saca el cuerpo la energía y cómo la gasta al hacer ejercicio.',
    'objective' => 'Relacionar la alimentación con el gasto energético de la actividad física.',
    'icon' => '⚡', 'nivel' => 'primaria-media', 'bloque' => 'alimentacion-y-energia',
    'duracion' => 12, 'tags' => ['alimentacion', 'cuerpo', 'comprension'],
    'estaciones' => [

        est('La energía del cuerpo', 'Entra y sale', '🔋', 'opcion_multiple', [
            omp('¿De dónde saca energía el cuerpo?',     ['De los alimentos', 'Del aire solamente', 'Del sueño'], 'De los alimentos'),
            omp('¿Qué pasa si gasto más energía de la que como?', ['El cuerpo usa sus reservas', 'No pasa nada', 'Engordo'], 'El cuerpo usa sus reservas'),
            omp('¿Qué actividad gasta más energía?',     ['Correr', 'Leer', 'Dormir'], 'Correr'),
            omp('¿Gasta energía el cuerpo cuando duerme?', ['Sí, sigue funcionando', 'No', 'Solo el corazón'], 'Sí, sigue funcionando'),
            omp('¿Qué alimento da energía de forma más sostenida?', ['Los cereales integrales', 'Los dulces', 'El agua'], 'Los cereales integrales'),
        ]),

        est('Antes y después de jugar', 'Qué comer y cuándo', '🍌', 'opcion_multiple', [
            omp('¿Conviene hacer ejercicio justo después de comer mucho?', ['No, conviene esperar', 'Sí', 'Da igual'], 'No, conviene esperar'),
            omp('¿Qué es bueno comer antes de una actividad física?', ['Algo ligero, como una fruta', 'Una comida enorme', 'Nada nunca'], 'Algo ligero, como una fruta'),
            omp('¿Qué conviene tomar durante el ejercicio?', ['Agua', 'Café', 'Nada'], 'Agua'),
            omp('¿Qué ayuda a recuperarse después?',     ['Comer bien, hidratarse y descansar', 'Correr otra vez', 'Saltarse la cena'], 'Comer bien, hidratarse y descansar'),
            omp('¿Es normal tener más hambre los días que me muevo mucho?', ['Sí, el cuerpo pide reponer energía', 'No', 'Es mala señal'], 'Sí, el cuerpo pide reponer energía'),
        ]),

        est('Leer una etiqueta', 'Lo que dice el paquete', '🏷️', 'opcion_multiple', [
            omp('¿Qué información trae la etiqueta de un alimento?', ['Ingredientes y valores nutricionales', 'El precio solamente', 'Nada útil'], 'Ingredientes y valores nutricionales'),
            omp('En la lista de ingredientes, el primero es…', ['El que más cantidad tiene', 'El más sano', 'El más caro'], 'El que más cantidad tiene'),
            omp('Si el azúcar aparece de primero, el producto…', ['Tiene mucha azúcar', 'Es saludable', 'No tiene azúcar'], 'Tiene mucha azúcar'),
            omp('¿Qué son los sellos de advertencia en un empaque?', ['Avisos de exceso de azúcar, sal o grasa', 'Premios', 'Publicidad'], 'Avisos de exceso de azúcar, sal o grasa'),
            omp('¿Sirve mirar la fecha de vencimiento?', ['Sí, siempre', 'No', 'Solo en la leche'], 'Sí, siempre'),
        ]),

        est('Ordena la comida del día', 'Un día equilibrado', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena las comidas del día',
            'items' => ['Desayuno', 'Media mañana', 'Almuerzo', 'Onces', 'Cena'],
        ]),
    ],
],


// =====================================================================
//  BLOQUE · MENTE Y EMOCIONES
// =====================================================================

[
    'slug'  => 'dormir-y-descansar',
    'title' => 'Dormir y descansar',
    'description' => 'Por qué el sueño no es tiempo perdido y cómo dormir mejor.',
    'objective' => 'Reconocer la importancia del sueño y adoptar hábitos de descanso adecuados.',
    'icon' => '😴', 'nivel' => 'primaria-media', 'bloque' => 'mente-y-emociones',
    'duracion' => 11, 'tags' => ['salud', 'autocuidado', 'comprension'],
    'estaciones' => [

        est('Para qué sirve dormir', 'El cuerpo trabaja mientras duermes', '🌙', 'opcion_multiple', [
            omp('¿Qué hace el cuerpo mientras dormimos?', ['Se repara y guarda lo aprendido', 'Nada', 'Solo descansa el corazón'], 'Se repara y guarda lo aprendido'),
            omp('¿Cuántas horas debe dormir un niño de 8 años?', ['Entre 9 y 11', '4', '15'], 'Entre 9 y 11'),
            omp('¿Qué pasa si duermo poco?',            ['Me cuesta concentrarme y me irrito', 'Nada', 'Rindo más'], 'Me cuesta concentrarme y me irrito'),
            omp('¿Ayuda dormir a recordar lo estudiado?', ['Sí, la memoria se consolida durmiendo', 'No', 'Solo si estudio dormido'], 'Sí, la memoria se consolida durmiendo'),
            omp('¿Crecen los niños mientras duermen?',  ['Sí, se libera hormona del crecimiento', 'No', 'Solo de día'], 'Sí, se libera hormona del crecimiento'),
        ]),

        est('Dormir mejor', 'Hábitos que ayudan', '🛏️', 'opcion_multiple', [
            omp('¿Ayuda usar pantallas justo antes de dormir?', ['No, la luz dificulta el sueño', 'Sí', 'Da igual'], 'No, la luz dificulta el sueño'),
            omp('¿Conviene acostarse a la misma hora?',  ['Sí, el cuerpo se acostumbra', 'No', 'Solo entre semana'], 'Sí, el cuerpo se acostumbra'),
            omp('¿Cómo debe estar el cuarto para dormir?', ['Oscuro, silencioso y fresco', 'Con luz fuerte', 'Con la tele encendida'], 'Oscuro, silencioso y fresco'),
            omp('¿Es buena idea cenar muchísimo antes de dormir?', ['No, cuesta más conciliar el sueño', 'Sí', 'Da igual'], 'No, cuesta más conciliar el sueño'),
            omp('¿Qué ayuda a relajarse antes de dormir?', ['Leer o respirar despacio', 'Jugar videojuegos', 'Correr'], 'Leer o respirar despacio'),
        ]),

        est('Ordena la rutina de noche', 'Preparar el descanso', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena una buena rutina antes de dormir',
            'items' => ['Guardar las pantallas', 'Cepillarse los dientes', 'Preparar la ropa del día siguiente', 'Leer un rato', 'Apagar la luz'],
        ]),

        est('Verdadero o falso', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es verdad?', 'Responde rápido', [
                ['e' => '😴', 'n' => 'Dormir ayuda a recordar lo aprendido', 'ok' => true],
                ['e' => '📱', 'n' => 'La pantalla en la cama ayuda a dormir', 'ok' => false],
                ['e' => '🕐', 'n' => 'Acostarse a la misma hora ayuda',      'ok' => true],
                ['e' => '☕', 'n' => 'Las bebidas con cafeína ayudan a dormir', 'ok' => false],
                ['e' => '🌑', 'n' => 'La oscuridad favorece el sueño',       'ok' => true],
                ['e' => '🏃', 'n' => 'Correr justo antes de dormir relaja',  'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'reconocer-mis-emociones',
    'title' => 'Reconocer mis emociones',
    'description' => 'Ponerle nombre a lo que sientes es el primer paso para manejarlo.',
    'objective' => 'Identificar y nombrar emociones propias y aplicar estrategias de autorregulación.',
    'icon' => '💛', 'nivel' => 'primaria-media', 'bloque' => 'mente-y-emociones',
    'duracion' => 12, 'tags' => ['emociones', 'convivencia', 'autocuidado'],
    'estaciones' => [

        est('Ponerle nombre', 'Cada emoción tiene el suyo', '🔗', 'emparejar', [
            ['e' => '😀', 'w' => 'Alegría'],
            ['e' => '😢', 'w' => 'Tristeza'],
            ['e' => '😠', 'w' => 'Enojo'],
            ['e' => '😨', 'w' => 'Miedo'],
            ['e' => '😮', 'w' => 'Sorpresa'],
            ['e' => '😌', 'w' => 'Calma'],
        ]),

        est('Ninguna emoción es mala', 'Todas sirven para algo', '🌈', 'opcion_multiple', [
            omp('¿Es malo sentir enojo?',              ['No, avisa que algo no está bien', 'Sí, siempre', 'Solo en niños'], 'No, avisa que algo no está bien'),
            omp('¿Para qué sirve el miedo?',           ['Para protegernos del peligro', 'Para nada', 'Para molestar'], 'Para protegernos del peligro'),
            omp('¿Qué es lo que sí puede estar mal?',  ['Lo que hago cuando siento la emoción', 'Sentirla', 'Nombrarla'], 'Lo que hago cuando siento la emoción'),
            omp('¿Se pueden sentir dos emociones a la vez?', ['Sí, es normal', 'No', 'Solo de adulto'], 'Sí, es normal'),
            omp('¿Ayuda hablar de lo que siento?',     ['Sí, mucho', 'No', 'Solo si es grave'], 'Sí, mucho'),
        ]),

        est('Calmarse', 'Estrategias que funcionan', '🧘', 'opcion_multiple', [
            omp('Estoy muy enojado. ¿Qué hago primero?',  ['Respiro despacio antes de actuar', 'Grito', 'Rompo algo'], 'Respiro despacio antes de actuar'),
            omp('¿Cómo se respira para calmarse?',        ['Inhalar lento y exhalar más lento', 'Muy rápido', 'Aguantando'], 'Inhalar lento y exhalar más lento'),
            omp('¿Ayuda alejarse un momento de la situación?', ['Sí, da tiempo para pensar', 'No', 'Es de cobardes'], 'Sí, da tiempo para pensar'),
            omp('Si estoy triste y no se me pasa, ¿qué hago?', ['Se lo cuento a un adulto de confianza', 'Me lo guardo', 'Me aíslo siempre'], 'Se lo cuento a un adulto de confianza'),
            omp('¿Qué NO ayuda cuando estoy alterado?',   ['Tomar decisiones importantes en ese momento', 'Respirar', 'Caminar un poco'], 'Tomar decisiones importantes en ese momento'),
        ]),

        est('¿Qué siente?', 'Reconocer la emoción en una situación', '👀', 'opcion_multiple', [
            omp('Se le perdió su mascota. Probablemente siente…', ['Tristeza', 'Alegría', 'Sorpresa'], 'Tristeza', '😢'),
            omp('Le hicieron una fiesta sorpresa. Siente…',      ['Alegría y sorpresa', 'Enojo', 'Miedo'], 'Alegría y sorpresa', '🎉'),
            omp('Le rompieron su dibujo a propósito. Siente…',   ['Enojo', 'Calma', 'Alegría'], 'Enojo', '😠'),
            omp('Va a exponer delante de todos. Puede sentir…',  ['Nervios', 'Aburrimiento', 'Sueño'], 'Nervios', '😰'),
            omp('Un amigo lo defendió. Probablemente siente…',   ['Gratitud', 'Enojo', 'Miedo'], 'Gratitud', '🤗'),
        ]),
    ],
],

[
    'slug'  => 'atencion-y-concentracion',
    'title' => 'Atención y concentración',
    'description' => 'Cómo funciona la atención, qué la rompe y cómo entrenarla para estudiar mejor.',
    'objective' => 'Reconocer factores que afectan la concentración y aplicar estrategias de estudio.',
    'icon' => '🎯', 'nivel' => 'primaria-superior', 'bloque' => 'mente-y-emociones',
    'duracion' => 13, 'tags' => ['atencion', 'autocuidado', 'logica'],
    'estaciones' => [

        est('Qué rompe la concentración', 'Los ladrones de atención', '📵', 'opcion_multiple', [
            omp('¿Qué distrae más al estudiar?',        ['El teléfono con notificaciones', 'Una mesa ordenada', 'El silencio'], 'El teléfono con notificaciones'),
            omp('¿Se puede hacer bien dos cosas difíciles a la vez?', ['No, el cerebro va saltando y rinde menos', 'Sí, perfectamente', 'Solo de noche'], 'No, el cerebro va saltando y rinde menos'),
            omp('¿Cuánto cuesta retomar la concentración tras una interrupción?', ['Varios minutos', 'Nada', 'Un segundo'], 'Varios minutos'),
            omp('¿Ayuda tener el escritorio despejado?', ['Sí', 'No', 'Da igual'], 'Sí'),
            omp('¿Es mejor estudiar con la televisión encendida?', ['No', 'Sí', 'Solo si es documental'], 'No'),
        ]),

        est('Estudiar mejor', 'Técnicas que sí funcionan', '📚', 'opcion_multiple', [
            omp('¿Qué funciona mejor para aprender?',   ['Preguntarse y responder sin mirar', 'Releer muchas veces', 'Subrayar todo'], 'Preguntarse y responder sin mirar'),
            omp('¿Es mejor estudiar todo la noche anterior?', ['No, repartirlo en varios días funciona mejor', 'Sí', 'Da igual'], 'No, repartirlo en varios días funciona mejor'),
            omp('¿Sirve hacer pausas?',                 ['Sí, la atención se recupera', 'No', 'Solo al final'], 'Sí, la atención se recupera'),
            omp('¿Cuánto conviene estudiar seguido a esta edad?', ['Bloques de 20 a 30 minutos', 'Cuatro horas seguidas', 'Un minuto'], 'Bloques de 20 a 30 minutos'),
            omp('¿Ayuda explicarle a otro lo que estudiaste?', ['Sí, mucho: descubres lo que no entendiste', 'No', 'Solo si el otro sabe'], 'Sí, mucho: descubres lo que no entendiste'),
        ]),

        est('Ordena tu sesión de estudio', 'Un plan que funciona', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena una buena sesión de estudio',
            'items' => ['Guardar las distracciones', 'Decidir qué voy a estudiar', 'Estudiar concentrado', 'Comprobar qué recuerdo', 'Hacer una pausa'],
        ]),

        est('Desafío de la atención', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('¿Se entrena la concentración?',        ['Sí, como un músculo', 'No, se nace con ella', 'Solo de adulto'], 'Sí, como un músculo'),
            reto('¿Qué hago si me distraigo mucho?',     ['Vuelvo a la tarea sin culparme', 'Abandono', 'Me castigo'], 'Vuelvo a la tarea sin culparme'),
            reto('¿Ayuda el ejercicio a concentrarse mejor?', ['Sí', 'No', 'Lo empeora'], 'Sí'),
            reto('¿Y dormir bien?',                      ['Sí, es de lo que más ayuda', 'No influye', 'Empeora'], 'Sí, es de lo que más ayuda'),
            reto('¿Es lo mismo estar mucho rato que aprender mucho?', ['No, importa la calidad del estudio', 'Sí', 'Siempre'], 'No, importa la calidad del estudio'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · SEGURIDAD Y CUIDADO
// =====================================================================

[
    'slug'  => 'higiene-y-cuidado-personal',
    'title' => 'Higiene y cuidado personal',
    'description' => 'Lavarse las manos, cepillarse los dientes y por qué esos gestos evitan enfermedades.',
    'objective' => 'Aplicar hábitos de higiene personal y explicar su función preventiva.',
    'icon' => '🧼', 'nivel' => 'primaria-inicial', 'bloque' => 'seguridad-y-cuidado',
    'duracion' => 11, 'tags' => ['salud', 'autocuidado', 'secuencias'],
    'estaciones' => [

        est('Lavarse las manos', 'El gesto que más enfermedades evita', '🧼', 'opcion_multiple', [
            omp('¿Cuándo hay que lavarse las manos?',   ['Antes de comer y después del baño', 'Solo al levantarse', 'Nunca'], 'Antes de comer y después del baño'),
            omp('¿Cuánto tiempo hay que frotarse?',     ['Unos 20 segundos', 'Un segundo', 'Cinco minutos'], 'Unos 20 segundos'),
            omp('¿Basta con mojarse las manos?',        ['No, hace falta jabón', 'Sí', 'Solo con agua caliente'], 'No, hace falta jabón'),
            omp('¿Por qué funciona el jabón?',          ['Arrastra los microbios de la piel', 'Los mata con el olor', 'No funciona'], 'Arrastra los microbios de la piel'),
            omp('¿Qué es un microbio?',                 ['Un ser vivo tan pequeño que no se ve', 'Un insecto', 'Una piedra'], 'Un ser vivo tan pequeño que no se ve'),
        ]),

        est('Cuidar los dientes', 'Se tienen toda la vida', '🦷', 'opcion_multiple', [
            omp('¿Cuántas veces al día hay que cepillarse?', ['Al menos dos', 'Una vez a la semana', 'Nunca'], 'Al menos dos'),
            omp('¿Qué produce las caries?',              ['Las bacterias que se alimentan del azúcar', 'El agua', 'El cepillo'], 'Las bacterias que se alimentan del azúcar'),
            omp('¿Cada cuánto conviene ir al odontólogo?', ['Cada seis meses aproximadamente', 'Nunca', 'Cada diez años'], 'Cada seis meses aproximadamente'),
            omp('¿Sirve la seda dental?',                ['Sí, limpia entre los dientes', 'No', 'Solo para adultos'], 'Sí, limpia entre los dientes'),
            omp('¿Qué pasa si no me cepillo antes de dormir?', ['Las bacterias trabajan toda la noche', 'Nada', 'Los dientes descansan'], 'Las bacterias trabajan toda la noche'),
        ]),

        est('Ordena el aseo', 'La rutina de la mañana', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos del aseo personal',
            'items' => ['Ducharse', 'Secarse', 'Vestirse', 'Cepillarse los dientes', 'Peinarse'],
        ]),

        est('Hábitos sanos', 'Toca lo que cuida tu salud', '💚', 'seleccion_imagenes',
            conTitulo('Toca los hábitos que cuidan tu salud', 'Algunos no ayudan', [
                ['e' => '🧼', 'n' => 'Lavarse las manos',    'ok' => true],
                ['e' => '🦷', 'n' => 'Cepillarse los dientes', 'ok' => true],
                ['e' => '😴', 'n' => 'Dormir suficiente',    'ok' => true],
                ['e' => '🍬', 'n' => 'Comer dulces todo el día', 'ok' => false],
                ['e' => '💧', 'n' => 'Tomar agua',           'ok' => true],
                ['e' => '📺', 'n' => 'Pasar el día sentado', 'ok' => false],
                ['e' => '⚽', 'n' => 'Jugar y moverse',      'ok' => true],
                ['e' => '🌙', 'n' => 'Trasnochar siempre',   'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'prevenir-accidentes',
    'title' => 'Prevenir accidentes',
    'description' => 'Riesgos en casa, en la calle y en el colegio, y cómo actuar si algo pasa.',
    'objective' => 'Identificar situaciones de riesgo y reconocer la respuesta adecuada ante un accidente.',
    'icon' => '🚑', 'nivel' => 'primaria-media', 'bloque' => 'seguridad-y-cuidado',
    'duracion' => 12, 'tags' => ['seguridad', 'autocuidado', 'convivencia'],
    'estaciones' => [

        est('Riesgos en casa', 'Los accidentes más comunes', '🏠', 'opcion_multiple', [
            omp('¿Qué hago si veo un cable pelado?',       ['No lo toco y aviso a un adulto', 'Lo toco', 'Lo mojo'], 'No lo toco y aviso a un adulto'),
            omp('¿Qué hago si se derrama agua en el piso?', ['La seco o aviso para que nadie resbale', 'Corro encima', 'La ignoro'], 'La seco o aviso para que nadie resbale'),
            omp('¿Puedo tomar un medicamento sin permiso?', ['No, nunca', 'Sí, si sé cuál es', 'Solo si es dulce'], 'No, nunca'),
            omp('¿Qué hago si huelo a gas?',               ['No prendo nada y aviso de inmediato', 'Prendo la luz', 'Abro el gas más'], 'No prendo nada y aviso de inmediato'),
            omp('¿Dónde se guardan los productos de limpieza?', ['Lejos del alcance de los niños pequeños', 'En la cocina, a la mano', 'En el baño de los niños'], 'Lejos del alcance de los niños pequeños'),
        ]),

        est('Seguridad en la calle', 'Antes de cruzar', '🚦', 'opcion_multiple', [
            omp('¿Por dónde se cruza la calle?',      ['Por la cebra o el puente', 'Por la mitad', 'Corriendo'], 'Por la cebra o el puente'),
            omp('¿Qué hago antes de cruzar?',         ['Miro a los dos lados', 'Cierro los ojos', 'Corro'], 'Miro a los dos lados'),
            omp('¿Por dónde se camina?',              ['Por el andén', 'Por la vía', 'Entre los carros'], 'Por el andén'),
            omp('¿Sirve el casco en la bicicleta?',   ['Sí, protege la cabeza', 'No', 'Solo si voy rápido'], 'Sí, protege la cabeza', '🚲'),
            omp('¿Se puede ir mirando el teléfono al cruzar?', ['No, es muy peligroso', 'Sí', 'Solo si es corto'], 'No, es muy peligroso'),
        ]),

        est('Si algo pasa', 'Reaccionar sin empeorarlo', '📞', 'opcion_multiple', [
            omp('¿Cuál es el número único de emergencias en Colombia?', ['123', '911', '060'], '123'),
            omp('¿Qué es lo primero al ver un accidente?', ['Avisar a un adulto y no mover al herido', 'Levantarlo', 'Grabarlo'], 'Avisar a un adulto y no mover al herido'),
            omp('¿Qué hago si me hago una herida pequeña?', ['La lavo con agua y aviso', 'La tapo con tierra', 'La ignoro'], 'La lavo con agua y aviso'),
            omp('¿Qué hago si alguien se está atragantando?', ['Pido ayuda a un adulto de inmediato', 'Le doy agua', 'Espero'], 'Pido ayuda a un adulto de inmediato'),
            omp('Al llamar a emergencias, ¿qué se dice primero?', ['Dónde estoy y qué pasó', 'Mi edad', 'Nada'], 'Dónde estoy y qué pasó'),
        ]),

        est('Desafío de la seguridad', 'Cinco decisiones', '🏆', 'desafio_final', [
            reto('Un desconocido te ofrece llevarte. ¿Qué haces?', ['No voy y le cuento a un adulto', 'Voy', 'Le pregunto a dónde'], 'No voy y le cuento a un adulto'),
            reto('¿Debo usar cinturón aunque el viaje sea corto?', ['Sí, siempre', 'No', 'Solo en carretera'], 'Sí, siempre'),
            reto('¿Se puede nadar solo sin un adulto cerca?',     ['No', 'Sí', 'Solo si sé nadar'], 'No'),
            reto('¿Qué es una salida de emergencia?',             ['Una ruta señalizada para evacuar', 'Una puerta cualquiera', 'Una ventana'], 'Una ruta señalizada para evacuar'),
            reto('En un simulacro de evacuación conviene…',       ['Salir en orden y sin correr', 'Correr rápido', 'Quedarse'], 'Salir en orden y sin correr'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'deportes-de-equipo',
    'title' => 'Deportes de equipo',
    'description' => 'Voleibol, baloncesto y fútbol: la cancha, las posiciones y las reglas básicas.',
    'objective' => 'Reconocer las reglas y la dinámica de cooperación de los deportes colectivos.',
    'icon' => '🏐', 'nivel' => 'primaria-media', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 12, 'tags' => ['juego', 'convivencia', 'reto'],
    'estaciones' => [

        est('Voleibol', 'Tres toques y al otro lado', '🏐', 'opcion_multiple', [
            omp('¿Cuántos toques puede dar un equipo antes de pasar?', ['3', '5', '1'], '3'),
            omp('¿Puede un jugador tocar dos veces seguidas?', ['No, salvo en el bloqueo', 'Sí, siempre', 'Sí, tres veces'], 'No, salvo en el bloqueo'),
            omp('¿Cómo se llama el primer golpe del punto?', ['Saque', 'Remate', 'Bloqueo'], 'Saque'),
            omp('¿Qué pasa si el balón cae dentro de mi campo?', ['Punto para el otro equipo', 'Punto para mí', 'Se repite'], 'Punto para el otro equipo'),
            omp('¿Se puede tocar la red?',            ['No, es falta', 'Sí', 'Solo al saltar'], 'No, es falta'),
        ]),

        est('Baloncesto', 'Botar, pasar y encestar', '🏀', 'opcion_multiple', [
            omp('¿Cuántos jugadores tiene un equipo en cancha?', ['5', '6', '11'], '5'),
            omp('¿Cómo se avanza con el balón?',      ['Botándolo', 'Corriendo con él en la mano', 'Pateándolo'], 'Botándolo'),
            omp('¿Qué es «dobles»?',                  ['Dejar de botar y volver a botar', 'Meter dos canastas', 'Jugar de a dos'], 'Dejar de botar y volver a botar'),
            omp('¿Cuánto vale una canasta normal?',   ['2 puntos', '1 punto', '5 puntos'], '2 puntos'),
            omp('¿Cuánto vale un tiro desde detrás de la línea?', ['3 puntos', '2 puntos', '1 punto'], '3 puntos'),
        ]),

        est('Jugar en equipo', 'Más que técnica', '🤝', 'opcion_multiple', [
            omp('¿Qué es más útil en un deporte de equipo?', ['Pasar el balón a quien está mejor ubicado', 'Jugar solo siempre', 'Gritar'], 'Pasar el balón a quien está mejor ubicado'),
            omp('Si un compañero falla, ¿qué hago?',   ['Lo animo', 'Le reclamo', 'Me enojo'], 'Lo animo'),
            omp('¿Para qué sirve comunicarse en la cancha?', ['Para coordinarse y evitar choques', 'Para hacer ruido', 'Para nada'], 'Para coordinarse y evitar choques'),
            omp('¿Quién decide en un partido si hubo falta?', ['El árbitro', 'El público', 'El que grite más'], 'El árbitro'),
            omp('¿Qué es una estrategia de juego?',    ['Un plan acordado para atacar o defender', 'Correr sin más', 'Un descanso'], 'Un plan acordado para atacar o defender'),
        ]),

        est('Reglas relámpago', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es correcto?', 'Responde rápido', [
                ['e' => '🏐', 'n' => 'En voleibol se dan hasta 3 toques', 'ok' => true],
                ['e' => '🏀', 'n' => 'En baloncesto se patea el balón',   'ok' => false],
                ['e' => '⚽', 'n' => 'En fútbol el arquero puede usar las manos en su área', 'ok' => true],
                ['e' => '🏐', 'n' => 'En voleibol se puede tocar la red', 'ok' => false],
                ['e' => '🏀', 'n' => 'Un triple vale 3 puntos',           'ok' => true],
                ['e' => '🤝', 'n' => 'Insultar al rival es parte del juego', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'natacion-y-agua-segura',
    'title' => 'Natación y agua segura',
    'description' => 'Flotar, respirar y sobre todo: las reglas que evitan un accidente en el agua.',
    'objective' => 'Reconocer técnicas básicas de natación y normas de seguridad acuática.',
    'icon' => '🏊', 'nivel' => 'primaria-media', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 12, 'tags' => ['cuerpo', 'seguridad', 'autocuidado'],
    'estaciones' => [

        est('En el agua', 'Cómo se mueve el cuerpo', '🌊', 'opcion_multiple', [
            omp('¿Por qué flota el cuerpo humano?',     ['Porque es menos denso que el agua al llenar de aire los pulmones', 'Porque es liviano', 'Por magia'], 'Porque es menos denso que el agua al llenar de aire los pulmones'),
            omp('¿Qué ayuda a flotar mejor?',           ['Estar relajado y con aire en los pulmones', 'Tensarse', 'Moverse mucho'], 'Estar relajado y con aire en los pulmones'),
            omp('¿Cuándo se respira al nadar?',         ['Al girar la cabeza fuera del agua', 'Bajo el agua', 'No se respira'], 'Al girar la cabeza fuera del agua'),
            omp('¿Cómo se llama el estilo más común?',  ['Estilo libre o crol', 'Mariposa', 'Espalda doble'], 'Estilo libre o crol'),
            omp('¿Qué articulaciones se usan mucho al nadar?', ['Hombros y caderas', 'Solo rodillas', 'Ninguna'], 'Hombros y caderas'),
        ]),

        est('Seguridad en el agua', 'Lo que más importa', '🛟', 'opcion_multiple', [
            omp('¿Se puede nadar sin un adulto cerca?',  ['No', 'Sí, si sé nadar', 'Solo de día'], 'No'),
            omp('¿Se puede correr al borde de una piscina?', ['No, el piso resbala', 'Sí', 'Solo descalzo'], 'No, el piso resbala'),
            omp('¿Se puede clavar de cabeza donde no sé la profundidad?', ['No, nunca', 'Sí', 'Si hay agua, sí'], 'No, nunca'),
            omp('Si alguien se está ahogando, ¿qué hago primero?', ['Aviso a un adulto o socorrista y busco algo que flote', 'Me lanzo a ayudarlo', 'Grito y me voy'], 'Aviso a un adulto o socorrista y busco algo que flote'),
            omp('¿Qué es un salvavidas?',               ['Un elemento que flota y ayuda a mantenerse a flote', 'Un juguete', 'Una regla'], 'Un elemento que flota y ayuda a mantenerse a flote'),
        ]),

        est('En el mar y el río', 'Aguas que no son piscina', '🌊', 'opcion_multiple', [
            omp('¿Qué es una corriente de resaca?',     ['Una corriente que arrastra mar adentro', 'Una ola grande', 'Un pez'], 'Una corriente que arrastra mar adentro'),
            omp('Si me arrastra una corriente, ¿qué hago?', ['No luchar de frente; nadar en paralelo a la orilla y pedir ayuda', 'Nadar directo a la orilla con todas mis fuerzas', 'Quedarme quieto'], 'No luchar de frente; nadar en paralelo a la orilla y pedir ayuda'),
            omp('¿Qué significa una bandera roja en la playa?', ['Prohibido bañarse', 'Agua perfecta', 'Hay comida'], 'Prohibido bañarse', '🚩'),
            omp('¿Son iguales un río y una piscina?',   ['No, el río tiene corriente y fondo irregular', 'Sí', 'El río es más seguro'], 'No, el río tiene corriente y fondo irregular'),
            omp('¿Puedo confiar en un flotador inflable en el mar?', ['No, puede alejarme sin que lo note', 'Sí, siempre', 'Es lo más seguro'], 'No, puede alejarme sin que lo note'),
        ]),

        est('Desafío del agua', 'Cinco decisiones', '🏆', 'desafio_final', [
            reto('¿Cuándo conviene entrar al agua después de comer mucho?', ['Después de un rato de reposo', 'Inmediatamente', 'Nunca más'], 'Después de un rato de reposo'),
            reto('¿Sirve saber nadar para estar seguro?', ['Ayuda, pero no elimina el riesgo', 'Lo elimina del todo', 'No sirve'], 'Ayuda, pero no elimina el riesgo'),
            reto('¿Qué hago antes de entrar a una piscina desconocida?', ['Miro la profundidad', 'Salto de una', 'Corro'], 'Miro la profundidad'),
            reto('¿Por qué hay que ducharse antes de entrar a la piscina?', ['Por higiene, para no ensuciar el agua', 'Por frío', 'Por costumbre'], 'Por higiene, para no ensuciar el agua'),
            reto('¿Es la natación un buen ejercicio?',   ['Sí, trabaja todo el cuerpo sin golpear las articulaciones', 'No', 'Solo para adultos'], 'Sí, trabaja todo el cuerpo sin golpear las articulaciones'),
        ]),
    ],
],

[
    'slug'  => 'pantallas-y-descanso',
    'title' => 'Pantallas y descanso',
    'description' => 'Cuánto tiempo de pantalla es demasiado y qué le hace al cuerpo y al ánimo.',
    'objective' => 'Reconocer los efectos del uso excesivo de pantallas y adoptar hábitos de equilibrio.',
    'icon' => '📱', 'nivel' => 'primaria-superior', 'bloque' => 'mente-y-emociones',
    'duracion' => 12, 'tags' => ['salud', 'autocuidado', 'tecnologia'],
    'estaciones' => [

        est('Qué le hace al cuerpo', 'Efectos reales', '👁️', 'opcion_multiple', [
            omp('¿Qué le pasa a los ojos tras horas de pantalla?', ['Se cansan y se resecan', 'Mejoran', 'Nada'], 'Se cansan y se resecan'),
            omp('¿Qué postura daña la espalda y el cuello?', ['Estar encorvado mirando abajo', 'Estar erguido', 'Estar de pie'], 'Estar encorvado mirando abajo'),
            omp('¿Qué efecto tiene la pantalla antes de dormir?', ['Dificulta conciliar el sueño', 'Ayuda a dormir', 'Ninguno'], 'Dificulta conciliar el sueño'),
            omp('¿Qué es la regla 20-20-20?',          ['Cada 20 minutos mirar lejos 20 segundos', 'Ver 20 minutos al día', 'Descansar 20 horas'], 'Cada 20 minutos mirar lejos 20 segundos'),
            omp('¿Reemplaza la pantalla al juego al aire libre?', ['No, el cuerpo necesita moverse', 'Sí', 'Es mejor'], 'No, el cuerpo necesita moverse'),
        ]),

        est('Qué le hace al ánimo', 'Lo que no se ve', '💭', 'opcion_multiple', [
            omp('¿Por qué cuesta parar de ver videos cortos?', ['Están diseñados para engancharnos', 'Porque somos débiles', 'Por casualidad'], 'Están diseñados para engancharnos'),
            omp('Comparar mi vida con lo que veo en redes…', ['Suele hacerme sentir peor, porque muestran lo mejor', 'Me motiva siempre', 'No influye'], 'Suele hacerme sentir peor, porque muestran lo mejor'),
            omp('¿Es real todo lo que se ve en redes?',  ['No, está seleccionado y editado', 'Sí', 'Casi todo'], 'No, está seleccionado y editado'),
            omp('¿Qué señal indica que uso demasiado la pantalla?', ['Dejo de hacer cosas que me gustaban', 'Me río', 'Aprendo'], 'Dejo de hacer cosas que me gustaban'),
            omp('¿Es mala toda pantalla?',              ['No, depende de para qué y cuánto', 'Sí', 'Solo los juegos'], 'No, depende de para qué y cuánto'),
        ]),

        est('Encontrar el equilibrio', 'Decidir yo, no la aplicación', '⚖️', 'opcion_multiple', [
            omp('¿Qué ayuda a controlar el tiempo de pantalla?', ['Ponerme un límite antes de empezar', 'Confiar en que pararé solo', 'Nada'], 'Ponerme un límite antes de empezar'),
            omp('¿Dónde NO conviene tener el celular?',  ['En el cuarto al dormir', 'En la sala', 'En la maleta'], 'En el cuarto al dormir'),
            omp('¿Qué hago si algo en línea me hace sentir mal?', ['Lo cierro y hablo con un adulto', 'Sigo mirando', 'Lo comparto'], 'Lo cierro y hablo con un adulto'),
            omp('¿Qué actividad conviene alternar con la pantalla?', ['Moverse, leer o estar con otros', 'Otra pantalla', 'Dormir de día'], 'Moverse, leer o estar con otros'),
            omp('¿Quién debe decidir cuánto uso la pantalla?', ['Yo con ayuda de mi familia', 'La aplicación', 'Nadie'], 'Yo con ayuda de mi familia'),
        ]),

        est('Hábitos de pantalla', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es un buen hábito?', 'Responde rápido', [
                ['e' => '⏰', 'n' => 'Ponerme un límite de tiempo',  'ok' => true],
                ['e' => '🛏️', 'n' => 'Dormir con el celular en la cama', 'ok' => false],
                ['e' => '👀', 'n' => 'Descansar la vista cada rato', 'ok' => true],
                ['e' => '🌙', 'n' => 'Ver videos hasta la madrugada', 'ok' => false],
                ['e' => '⚽', 'n' => 'Alternar pantalla y juego afuera', 'ok' => true],
                ['e' => '🍽️', 'n' => 'Comer siempre viendo pantalla', 'ok' => false],
            ])),
    ],
],


[
    'slug'  => 'primeros-auxilios-basicos',
    'title' => 'Primeros auxilios básicos',
    'description' => 'Qué hacer y qué NO hacer ante una herida, un golpe o un desmayo.',
    'objective' => 'Reconocer las acciones adecuadas ante situaciones frecuentes de primeros auxilios.',
    'icon' => '🩹', 'nivel' => 'primaria-superior', 'bloque' => 'seguridad-y-cuidado',
    'duracion' => 13, 'tags' => ['seguridad', 'salud', 'autocuidado'],
    'estaciones' => [

        /*
         * Lo primero que se enseña aquí no es una técnica: es que un niño
         * no es el socorrista. Su papel es avisar y no empeorar la
         * situación. Enseñarle maniobras que no puede ejecutar bien crea
         * más riesgo del que evita.
         */
        est('Lo primero de todo', 'Un niño avisa, no interviene', '📞', 'opcion_multiple', [
            omp('Veo a alguien herido. ¿Qué hago primero?', ['Llamar a un adulto o al 123', 'Moverlo', 'Grabarlo'], 'Llamar a un adulto o al 123'),
            omp('¿Debo mover a alguien que se cayó fuerte?', ['No, puede empeorar una lesión', 'Sí', 'Solo la cabeza'], 'No, puede empeorar una lesión'),
            omp('¿Cuál es el número de emergencias en Colombia?', ['123', '911', '060'], '123'),
            omp('Al llamar, ¿qué digo primero?',      ['Dónde estoy y qué pasó', 'Mi nombre completo', 'Nada'], 'Dónde estoy y qué pasó'),
            omp('¿Debo colgar apenas doy la dirección?', ['No, espero a que me indiquen', 'Sí', 'Da igual'], 'No, espero a que me indiquen'),
        ]),

        est('Heridas y golpes', 'Cosas simples bien hechas', '🩹', 'opcion_multiple', [
            omp('Herida pequeña que sangra poco. ¿Qué hago?', ['La lavo con agua limpia y aviso', 'Le echo tierra', 'La dejo'], 'La lavo con agua limpia y aviso'),
            omp('¿Qué ayuda con un golpe sin herida?',  ['Frío envuelto en un paño', 'Calor', 'Frotar fuerte'], 'Frío envuelto en un paño'),
            omp('¿Se pone hielo directo sobre la piel?', ['No, se envuelve primero', 'Sí', 'Solo si duele'], 'No, se envuelve primero'),
            omp('Si sangra la nariz, ¿qué hago?',       ['Inclino la cabeza un poco hacia adelante y aprieto', 'Echo la cabeza atrás', 'Me acuesto'], 'Inclino la cabeza un poco hacia adelante y aprieto'),
            omp('¿Se revientan las ampollas?',          ['No', 'Sí', 'Solo las grandes'], 'No'),
        ]),

        est('Quemaduras y atragantamiento', 'Dos casos que asustan', '🔥', 'opcion_multiple', [
            omp('Quemadura leve. ¿Qué hago?',          ['Agua fría corriendo un rato y avisar', 'Poner pasta de dientes', 'Poner hielo directo'], 'Agua fría corriendo un rato y avisar'),
            omp('¿Se pone crema o mantequilla en una quemadura?', ['No', 'Sí', 'Solo mantequilla'], 'No'),
            omp('Alguien se atraganta pero tose fuerte. ¿Qué hago?', ['Lo animo a seguir tosiendo y pido ayuda', 'Le doy agua', 'Le golpeo la espalda fuerte'], 'Lo animo a seguir tosiendo y pido ayuda'),
            omp('Si no puede toser ni hablar, ¿qué hago?', ['Pido ayuda a un adulto de inmediato', 'Espero', 'Le doy agua'], 'Pido ayuda a un adulto de inmediato'),
            omp('¿Puedo dar medicamentos a alguien?',   ['No, nunca', 'Sí, si sé cuál', 'Solo pastillas pequeñas'], 'No, nunca'),
        ]),

        est('¿Qué hago?', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es lo correcto?', 'Responde rápido', [
                ['e' => '📞', 'n' => 'Llamar al 123 en una emergencia',   'ok' => true],
                ['e' => '🤸', 'n' => 'Mover a alguien que se golpeó la espalda', 'ok' => false],
                ['e' => '💧', 'n' => 'Lavar una herida con agua limpia',  'ok' => true],
                ['e' => '🧈', 'n' => 'Poner mantequilla en una quemadura', 'ok' => false],
                ['e' => '🧊', 'n' => 'Poner frío envuelto sobre un golpe', 'ok' => true],
                ['e' => '💊', 'n' => 'Darle una pastilla a un compañero', 'ok' => false],
            ])),
    ],
],


[
    'slug'  => 'el-atletismo',
    'title' => 'El atletismo',
    'description' => 'Correr, saltar y lanzar: las pruebas más antiguas del deporte.',
    'objective' => 'Reconocer las modalidades del atletismo y las capacidades que exige cada una.',
    'icon' => '🏃‍♀️', 'nivel' => 'primaria-media', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 12, 'tags' => ['cuerpo', 'reto', 'juego'],
    'estaciones' => [

        est('Las pruebas', 'Tres familias de movimiento', '🏅', 'opcion_multiple', [
            omp('¿Qué prueba mide la velocidad pura?',   ['Los 100 metros planos', 'El maratón', 'El salto largo'], 'Los 100 metros planos'),
            omp('¿Qué prueba mide la resistencia?',      ['El maratón', 'Los 100 metros', 'El lanzamiento'], 'El maratón'),
            omp('¿Cuál es una prueba de salto?',         ['El salto de longitud', 'El maratón', 'La marcha'], 'El salto de longitud'),
            omp('¿Cuál es una prueba de lanzamiento?',   ['La jabalina', 'La valla', 'La marcha'], 'La jabalina'),
            omp('¿Qué es una carrera de relevos?',       ['Varios corredores se pasan un testigo', 'Una carrera individual', 'Un salto'], 'Varios corredores se pasan un testigo'),
        ]),

        est('Qué entrena cada una', 'Distintas capacidades', '💪', 'opcion_multiple', [
            omp('El maratón entrena sobre todo…',        ['La resistencia', 'La velocidad pura', 'La flexibilidad'], 'La resistencia'),
            omp('Los 100 metros entrenan sobre todo…',   ['La velocidad', 'La resistencia', 'La puntería'], 'La velocidad'),
            omp('El lanzamiento de peso entrena…',       ['La fuerza', 'La resistencia', 'La flexibilidad'], 'La fuerza'),
            omp('¿Qué prueba exige más técnica de salto?', ['El salto con garrocha', 'La caminata', 'El maratón'], 'El salto con garrocha'),
            omp('¿Se puede mejorar la velocidad entrenando?', ['Sí, con técnica y práctica', 'No, se nace con ella', 'Solo de adulto'], 'Sí, con técnica y práctica'),
        ]),

        est('Competir bien', 'Reglas y respeto', '🤝', 'opcion_multiple', [
            omp('¿Qué es una salida en falso?',          ['Salir antes de la señal', 'Llegar último', 'Cambiar de carril'], 'Salir antes de la señal'),
            omp('¿Puedo invadir el carril de otro corredor?', ['No, es falta', 'Sí', 'Solo al final'], 'No, es falta'),
            omp('Si pierdo, ¿qué hago?',                 ['Felicito a quien ganó', 'Digo que hizo trampa', 'Me voy'], 'Felicito a quien ganó'),
            omp('¿Qué es una marca personal?',           ['Mi mejor resultado hasta ahora', 'El récord mundial', 'Una falta'], 'Mi mejor resultado hasta ahora'),
            omp('¿Con quién es más útil compararse?',    ['Conmigo mismo de antes', 'Con el mejor del mundo', 'Con nadie'], 'Conmigo mismo de antes'),
        ]),

        est('Atletismo relámpago', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es cierto?', 'Responde rápido', [
                ['e' => '🏃', 'n' => 'El maratón mide más de 40 km',   'ok' => true],
                ['e' => '⏱️', 'n' => 'Los 100 metros son de resistencia', 'ok' => false],
                ['e' => '🥇', 'n' => 'Calentar reduce el riesgo de lesión', 'ok' => true],
                ['e' => '🚩', 'n' => 'Salir antes de la señal es válido', 'ok' => false],
                ['e' => '🤝', 'n' => 'En relevos se pasa un testigo',   'ok' => true],
                ['e' => '💧', 'n' => 'No hay que hidratarse al correr', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'higiene-del-sueno-y-rutinas',
    'title' => 'Rutinas que sostienen el día',
    'description' => 'Organizar el tiempo entre estudio, juego, comida y descanso.',
    'objective' => 'Planificar una rutina diaria equilibrada entre obligaciones y descanso.',
    'icon' => '🗓️', 'nivel' => 'primaria-media', 'bloque' => 'mente-y-emociones',
    'duracion' => 11, 'tags' => ['autocuidado', 'secuencias', 'atencion'],
    'estaciones' => [

        est('Un día equilibrado', 'Ni todo estudio ni todo juego', '⚖️', 'opcion_multiple', [
            omp('¿Qué debe tener un buen día?',        ['Estudio, juego, comida y descanso', 'Solo estudio', 'Solo juego'], 'Estudio, juego, comida y descanso'),
            omp('¿Cuándo conviene hacer la tarea?',    ['A una hora fija, con energía', 'Justo antes de dormir', 'Nunca'], 'A una hora fija, con energía'),
            omp('¿Sirve tener horarios parecidos cada día?', ['Sí, el cuerpo se organiza mejor', 'No', 'Solo en vacaciones'], 'Sí, el cuerpo se organiza mejor'),
            omp('¿Hay que dejar tiempo libre sin planear?', ['Sí, también hace falta', 'No', 'Solo los domingos'], 'Sí, también hace falta'),
            omp('¿Qué pasa si dejo todo para el final del día?', ['Lo hago cansado y peor', 'Lo hago mejor', 'Nada'], 'Lo hago cansado y peor'),
        ]),

        est('Ordena tu tarde', 'Una secuencia que funciona', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena una tarde equilibrada',
            'items' => ['Llegar y descansar un rato', 'Almorzar', 'Hacer la tarea', 'Jugar o hacer deporte', 'Cenar y prepararse para dormir'],
        ]),

        est('Prioridades', 'Qué va primero', '🎯', 'opcion_multiple', [
            omp('Tengo tarea para mañana y quiero ver videos. ¿Qué hago primero?', ['La tarea', 'Los videos', 'Nada'], 'La tarea'),
            omp('¿Qué es más urgente: lo de mañana o lo de la semana que viene?', ['Lo de mañana', 'Lo de la semana que viene', 'Igual'], 'Lo de mañana'),
            omp('Si tengo muchas cosas, ¿qué ayuda?',  ['Hacer una lista y ordenarla', 'Empezar por la más difícil siempre', 'No hacer nada'], 'Hacer una lista y ordenarla'),
            omp('¿Conviene dejar la tarea larga para el último día?', ['No, conviene repartirla', 'Sí', 'Da igual'], 'No, conviene repartirla'),
            omp('¿Qué hago si no me alcanza el tiempo?', ['Aviso y pido ayuda para organizarme', 'Me rindo', 'Me quedo despierto toda la noche'], 'Aviso y pido ayuda para organizarme'),
        ]),

        est('Buenas rutinas', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Ayuda a tener un buen día?', 'Responde rápido', [
                ['e' => '🕐', 'n' => 'Acostarse a la misma hora',   'ok' => true],
                ['e' => '📱', 'n' => 'Empezar el día con pantalla', 'ok' => false],
                ['e' => '🥣', 'n' => 'Desayunar antes de salir',    'ok' => true],
                ['e' => '⏰', 'n' => 'Dejar todo para el último minuto', 'ok' => false],
                ['e' => '⚽', 'n' => 'Moverse un rato cada día',    'ok' => true],
                ['e' => '🌙', 'n' => 'Hacer la tarea de madrugada', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'cuidar-los-dientes-y-la-piel',
    'title' => 'Cuidar los dientes y la piel',
    'description' => 'Dos partes del cuerpo que se descuidan y no se recuperan solas.',
    'objective' => 'Aplicar hábitos de cuidado bucal y protección de la piel.',
    'icon' => '🦷', 'nivel' => 'primaria-media', 'bloque' => 'seguridad-y-cuidado',
    'duracion' => 11, 'tags' => ['salud', 'autocuidado', 'observacion'],
    'estaciones' => [

        est('Los dientes', 'Se tienen toda la vida', '🪥', 'opcion_multiple', [
            omp('¿Cuántas dentaduras tenemos en la vida?', ['Dos: la de leche y la definitiva', 'Una', 'Tres'], 'Dos: la de leche y la definitiva'),
            omp('¿Qué pasa si se daña un diente definitivo?', ['No vuelve a salir otro', 'Sale otro', 'Se cura solo'], 'No vuelve a salir otro'),
            omp('¿Cuándo es más importante cepillarse?',  ['Antes de dormir', 'Solo en la mañana', 'Después del recreo'], 'Antes de dormir'),
            omp('¿Qué produce caries?',                   ['Bacterias que se alimentan de azúcar', 'El agua', 'El cepillo'], 'Bacterias que se alimentan de azúcar'),
            omp('¿Cada cuánto se cambia el cepillo?',     ['Cada tres meses aproximadamente', 'Cada diez años', 'Nunca'], 'Cada tres meses aproximadamente'),
        ]),

        est('La piel', 'La barrera del cuerpo', '🧴', 'opcion_multiple', [
            omp('¿Qué función tiene la piel?',           ['Protege al cuerpo del exterior', 'Solo dar color', 'Ninguna'], 'Protege al cuerpo del exterior'),
            omp('¿Qué daña la piel a largo plazo?',      ['El sol sin protección', 'El agua', 'El jabón'], 'El sol sin protección'),
            omp('¿A qué horas pega más fuerte el sol?',  ['Entre las 10 y las 3', 'Al amanecer', 'De noche'], 'Entre las 10 y las 3'),
            omp('¿Sirve el protector solar en día nublado?', ['Sí, los rayos atraviesan las nubes', 'No', 'Solo en la playa'], 'Sí, los rayos atraviesan las nubes'),
            omp('¿Qué hago si me quemo con el sol?',     ['Me refresco, me hidrato y aviso', 'Me expongo más', 'Nada'], 'Me refresco, me hidrato y aviso'),
        ]),

        est('Higiene diaria', 'Lo básico bien hecho', '🧼', 'opcion_multiple', [
            omp('¿Cuándo hay que lavarse las manos?',    ['Antes de comer y después del baño', 'Solo al levantarse', 'Nunca'], 'Antes de comer y después del baño'),
            omp('¿Sirve compartir el cepillo de dientes?', ['No, nunca', 'Sí', 'Solo en familia'], 'No, nunca'),
            omp('¿Por qué hay que cortarse las uñas?',   ['Debajo se acumulan bacterias', 'Por estética solamente', 'Por nada'], 'Debajo se acumulan bacterias'),
            omp('¿Cada cuánto conviene bañarse?',        ['A diario o según la actividad', 'Una vez a la semana', 'Nunca'], 'A diario o según la actividad'),
            omp('¿Es la higiene solo cuestión de verse bien?', ['No, previene enfermedades', 'Sí', 'Solo en el colegio'], 'No, previene enfermedades'),
        ]),

        est('Ordena el cepillado', 'Paso a paso', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos del cepillado',
            'items' => ['Mojar el cepillo', 'Poner la crema', 'Cepillar arriba y abajo', 'Usar la seda dental', 'Enjuagarse'],
        ]),
    ],
],

],

'reasignar' => [],

];
