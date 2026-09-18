<?php
/**
 * bienestar-ampliacion.php — Vida y Bienestar en los extremos
 *
 * Tenía tres actividades de preescolar y cuatro de quinto y sexto. Las
 * dos puntas donde más cambia el trabajo:
 *
 *   · A los cinco años, el cuerpo se conoce nombrándolo y se cuida con
 *     rutinas. Nada de nutrientes ni de sistemas: partes, hábitos y
 *     «esto me cuida, esto no».
 *
 *   · A los once, el cuerpo está cambiando y aparece lo que nadie
 *     pregunta en voz alta. Aquí se nombra con precisión, sin rodeos y
 *     sin dramatismo, porque la alternativa es que lo averigüen en un
 *     video de internet.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA DE LAS ACTIVIDADES DE CUERPO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Ninguna estación juzga un cuerpo. No se habla de peso «correcto», no
 * se clasifican alimentos en buenos y malos, y no aparece ninguna imagen
 * de un cuerpo ideal. Se habla de lo que el cuerpo NECESITA y de lo que
 * cada uno puede decidir.
 *
 * Un material escolar que le dice a un niño de diez años que su cuerpo
 * está mal hace un daño que no compensa ningún contenido.
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
    ['slug' => 'mi-cuerpo', 'name' => 'Mi Cuerpo', 'icon' => '🧍', 'sort_order' => 1,
     'description' => 'Conocer el cuerpo, para qué sirve cada parte y qué necesita.'],
    ['slug' => 'habitos-saludables', 'name' => 'Hábitos Saludables', 'icon' => '🥗', 'sort_order' => 2,
     'description' => 'Comer, moverse, dormir y asearse: lo que se hace todos los días.'],
    ['slug' => 'decisiones-y-cuidado', 'name' => 'Decisiones y Cuidado', 'icon' => '🛡️', 'sort_order' => 3,
     'description' => 'Pensar antes de actuar, distinguir mitos y saber pedir ayuda.'],
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
//  PREESCOLAR
// =====================================================================

[
    'slug'  => 'mi-cuerpo-por-fuera',
    'title' => 'Mi cuerpo por fuera',
    'description' => 'Cabeza, brazos, piernas: cómo se llama cada parte y para qué sirve.',
    'objective' => 'Nombrar las partes externas del cuerpo y su función básica.',
    'icon' => '🙋', 'nivel' => 'preescolar', 'bloque' => 'mi-cuerpo',
    'duracion' => 8, 'tags' => ['cuerpo', 'vocabulario', 'observacion'],
    'estaciones' => [

        est('¿Cómo se llama?', 'Cada parte tiene su nombre', '🏷️', 'opcion_multiple', [
            omp('Lo que está encima del cuello es la…', ['Cabeza', 'Mano', 'Rodilla'], 'Cabeza', '🧑'),
            omp('Con lo que agarro es la…',             ['Mano', 'Oreja', 'Rodilla'], 'Mano', '✋'),
            omp('Con lo que camino son los…',           ['Pies', 'Codos', 'Dedos'], 'Pies', '🦶'),
            omp('En medio de la pierna está la…',       ['Rodilla', 'Nariz', 'Boca'], 'Rodilla', '🦵'),
            omp('En medio del brazo está el…',          ['Codo', 'Tobillo', 'Hombro'], 'Codo', '💪'),
        ]),

        est('Une la parte con lo que hace', 'Cada una sirve para algo', '🔗', 'emparejar', [
            ['e' => '👀', 'w' => 'Ver'],
            ['e' => '👂', 'w' => 'Oír'],
            ['e' => '👃', 'w' => 'Oler'],
            ['e' => '👅', 'w' => 'Saborear'],
            ['e' => '✋', 'w' => 'Tocar'],
            ['e' => '🦶', 'w' => 'Caminar'],
        ]),

        est('¿Cuántos tengo?', 'Cuenta tus partes', '🔢', 'opcion_multiple', [
            omp('¿Cuántos ojos tengo?',    ['2', '1', '3'], '2', '👀'),
            omp('¿Cuántas narices tengo?', ['1', '2', '0'], '1', '👃'),
            omp('¿Cuántos dedos hay en una mano?', ['5', '4', '10'], '5', '✋'),
            omp('¿Cuántas orejas tengo?',  ['2', '1', '4'], '2', '👂'),
            omp('¿Cuántas piernas tengo?', ['2', '4', '1'], '2', '🦵'),
        ]),

        est('Memoria del cuerpo', 'Encuentra las parejas', '🧠', 'memoria',
            ['👀', '👂', '👃', '✋', '🦶', '🦷']),
    ],
],

[
    'slug'  => 'mi-dia-sano',
    'title' => 'Mi día sano',
    'description' => 'Levantarse, lavarse, comer, jugar y dormir: la rutina que cuida.',
    'objective' => 'Ordenar las rutinas diarias de autocuidado.',
    'icon' => '🌅', 'nivel' => 'preescolar', 'bloque' => 'habitos-saludables',
    'duracion' => 8, 'tags' => ['salud', 'autocuidado', 'secuencias', 'anticipacion'],
    'estaciones' => [

        est('Ordena la mañana', 'Cada cosa a su hora', '🌅', 'ordenar_secuencia', [
            'title' => 'Ordena la rutina de la mañana',
            'items' => ['Despertarse', 'Ir al baño', 'Lavarse la cara',
                        'Desayunar', 'Cepillarse los dientes', 'Salir al colegio'],
        ]),

        est('Ordena la noche', 'Antes de dormir', '🌙', 'ordenar_secuencia', [
            'title' => 'Ordena la rutina de la noche',
            'items' => ['Cenar', 'Cepillarse los dientes', 'Ponerse la pijama',
                        'Que me lean un cuento', 'Apagar la luz', 'Dormir'],
        ]),

        est('¿Esto me cuida?', 'Unas cosas sí y otras no', '💚', 'juego_rapido',
            conTitulo('¿Esto cuida mi cuerpo?', 'Piensa bien', [
                ['e' => '💧', 'n' => 'Tomar agua',          'ok' => true],
                ['e' => '🌙', 'n' => 'Dormir tarde siempre','ok' => false],
                ['e' => '🥕', 'n' => 'Comer verduras',      'ok' => true],
                ['e' => '🍬', 'n' => 'Solo dulces todo el día','ok' => false],
                ['e' => '🏃', 'n' => 'Correr y jugar',      'ok' => true],
                ['e' => '🧼', 'n' => 'Lavarse las manos',   'ok' => true],
                ['e' => '📺', 'n' => 'Pantalla todo el día','ok' => false],
                ['e' => '😴', 'n' => 'Dormir bien',         'ok' => true],
            ])),

        est('Lavarse las manos', 'Cuándo y cómo', '🧼', 'opcion_multiple', [
            omp('¿Cuándo me lavo las manos?',  ['Antes de comer', 'Nunca', 'Solo el domingo'], 'Antes de comer'),
            omp('¿También después de…?',       ['Ir al baño', 'Ver televisión', 'Cantar'], 'Ir al baño'),
            omp('¿Con qué me lavo?',           ['Agua y jabón', 'Solo agua', 'Con tierra'], 'Agua y jabón'),
            omp('¿Cuánto rato?',               ['Mientras canto una canción corta', 'Un segundo', 'Una hora'], 'Mientras canto una canción corta'),
            omp('Lavarse las manos evita…',    ['Enfermarse', 'Crecer', 'Tener hambre'], 'Enfermarse'),
        ]),
    ],
],

[
    'slug'  => 'comer-de-todo',
    'title' => 'Comer de todo',
    'description' => 'Frutas, verduras, cereales y agua: lo que el cuerpo necesita cada día.',
    'objective' => 'Reconocer grupos de alimentos y la importancia de la variedad.',
    'icon' => '🥗', 'nivel' => 'preescolar', 'bloque' => 'alimentacion-y-energia',
    'duracion' => 8, 'tags' => ['alimentacion', 'salud', 'clasificacion'],
    'estaciones' => [

        est('¿Es fruta o verdura?', 'Dos grupos', '🍎', 'opcion_multiple', [
            omp('La manzana es…',   ['Fruta', 'Verdura', 'Carne'], 'Fruta', '🍎'),
            omp('La zanahoria es…', ['Verdura', 'Fruta', 'Pan'], 'Verdura', '🥕'),
            omp('El banano es…',    ['Fruta', 'Verdura', 'Leche'], 'Fruta', '🍌'),
            omp('El brócoli es…',   ['Verdura', 'Fruta', 'Queso'], 'Verdura', '🥦'),
            omp('La fresa es…',     ['Fruta', 'Verdura', 'Huevo'], 'Fruta', '🍓'),
        ]),

        est('El plato de todos los días', 'Un poco de cada cosa', '🍽️', 'seleccion_imagenes',
            conTitulo('Toca lo que conviene comer todos los días', 'Lo demás, de vez en cuando', [
                ['e' => '🍎', 'n' => 'Fruta',      'ok' => true],
                ['e' => '🍭', 'n' => 'Dulces',     'ok' => false],
                ['e' => '🥦', 'n' => 'Verduras',   'ok' => true],
                ['e' => '🥤', 'n' => 'Gaseosa',    'ok' => false],
                ['e' => '🍚', 'n' => 'Arroz',      'ok' => true],
                ['e' => '🍟', 'n' => 'Papas fritas','ok' => false],
                ['e' => '💧', 'n' => 'Agua',       'ok' => true],
                ['e' => '🥚', 'n' => 'Huevo',      'ok' => true],
            ])),

        est('El agua', 'Lo que más se necesita', '💧', 'opcion_multiple', [
            omp('¿Qué se debe tomar más al día?', ['Agua', 'Gaseosa', 'Jugo de caja'], 'Agua', '💧'),
            omp('Cuando hace calor hay que tomar…', ['Más agua', 'Menos agua', 'Igual'], 'Más agua', '☀️'),
            omp('Después de correr, tomo…',       ['Agua', 'Nada', 'Dulces'], 'Agua', '🏃'),
            omp('Si tengo sed, mi cuerpo pide…',  ['Agua', 'Sueño', 'Correr'], 'Agua'),
            omp('¿El cuerpo tiene agua por dentro?', ['Sí, mucha', 'No', 'Un poquito'], 'Sí, mucha'),
        ]),

        est('Une el alimento con su grupo', 'Cada cosa a su grupo', '🔗', 'emparejar', [
            ['e' => '🍎', 'w' => 'Fruta'],
            ['e' => '🥕', 'w' => 'Verdura'],
            ['e' => '🍞', 'w' => 'Cereal'],
            ['e' => '🥛', 'w' => 'Lácteo'],
            ['e' => '🥚', 'w' => 'Proteína'],
            ['e' => '💧', 'w' => 'Agua'],
        ]),
    ],
],

[
    'slug'  => 'moverme-y-jugar',
    'title' => 'Moverme y jugar',
    'description' => 'Correr, saltar, trepar: el cuerpo aprende moviéndose.',
    'objective' => 'Reconocer formas básicas de movimiento y su valor para la salud.',
    'icon' => '🤸', 'nivel' => 'preescolar', 'bloque' => 'movimiento-y-deporte',
    'duracion' => 8, 'tags' => ['cuerpo', 'salud', 'juego'],
    'estaciones' => [

        est('¿Qué estoy haciendo?', 'Cada movimiento tiene su nombre', '🤸', 'opcion_multiple', [
            omp('Ir muy rápido con los pies es…',  ['Correr', 'Saltar', 'Rodar'], 'Correr', '🏃'),
            omp('Subir con los pies y las manos es…', ['Trepar', 'Nadar', 'Dormir'], 'Trepar', '🧗'),
            omp('Despegar los pies del suelo es…', ['Saltar', 'Caminar', 'Sentarse'], 'Saltar', '🤸'),
            omp('Moverse en el agua es…',          ['Nadar', 'Correr', 'Trepar'], 'Nadar', '🏊'),
            omp('Dar vueltas por el suelo es…',    ['Rodar', 'Saltar', 'Volar'], 'Rodar'),
        ]),

        est('¿Con qué parte?', 'Cada movimiento usa algo', '🦵', 'opcion_multiple', [
            omp('Para patear uso…',   ['El pie', 'La oreja', 'La nariz'], 'El pie', '⚽'),
            omp('Para lanzar uso…',   ['La mano', 'El pie', 'La rodilla'], 'La mano', '🤾'),
            omp('Para saltar uso…',   ['Las piernas', 'Los dedos', 'Los ojos'], 'Las piernas'),
            omp('Para cabecear uso…', ['La cabeza', 'La mano', 'El codo'], 'La cabeza'),
            omp('Para agarrar uso…',  ['Las manos', 'Los pies', 'La espalda'], 'Las manos'),
        ]),

        est('Antes y después de jugar', 'El cuerpo se prepara y se calma', '🧘', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se juega cuidándose',
            'items' => ['Calentar moviéndose despacio', 'Jugar y correr',
                        'Descansar', 'Tomar agua', 'Estirarse'],
        ]),

        est('Jugar sin hacerse daño', 'Cuidarse y cuidar al otro', '🛡️', 'opcion_multiple', [
            omp('Si me canso mucho, ¿qué hago?',   ['Descanso', 'Sigo igual', 'Corro más'], 'Descanso'),
            omp('Si me duele algo, ¿qué hago?',    ['Aviso a un adulto', 'Me aguanto', 'Nada'], 'Aviso a un adulto'),
            omp('Para montar bicicleta uso…',      ['Casco', 'Sombrero', 'Nada'], 'Casco', '🚲'),
            omp('¿Empujo a otros cuando juego?',   ['No', 'Sí', 'Si voy perdiendo'], 'No'),
            omp('Si alguien se cae, ¿qué hago?',   ['Ayudo y aviso', 'Me río', 'Sigo'], 'Ayudo y aviso'),
        ]),
    ],
],

[
    'slug'  => 'cuidarme-de-los-peligros',
    'title' => 'Cuidarme de los peligros',
    'description' => 'La estufa, los enchufes, la calle: qué se toca y qué no.',
    'objective' => 'Identificar peligros domésticos y de la vía, y la conducta segura.',
    'icon' => '⚠️', 'nivel' => 'preescolar', 'bloque' => 'seguridad-y-cuidado',
    'duracion' => 8, 'tags' => ['seguridad', 'autocuidado', 'observacion'],
    'estaciones' => [

        est('¿Se toca o no?', 'Algunas cosas son solo para adultos', '✋', 'juego_rapido',
            conTitulo('¿Un niño puede tocarlo solo?', 'Piensa si es peligroso', [
                ['e' => '🧸', 'n' => 'Un peluche',   'ok' => true],
                ['e' => '🔥', 'n' => 'La estufa',    'ok' => false],
                ['e' => '📚', 'n' => 'Un libro',     'ok' => true],
                ['e' => '🔪', 'n' => 'Un cuchillo',  'ok' => false],
                ['e' => '🖍️', 'n' => 'Un crayón',    'ok' => true],
                ['e' => '💊', 'n' => 'Medicamentos', 'ok' => false],
                ['e' => '⚽', 'n' => 'Un balón',     'ok' => true],
                ['e' => '🔌', 'n' => 'Un enchufe',   'ok' => false],
            ])),

        est('En la calle', 'Cruzar con cuidado', '🚦', 'opcion_multiple', [
            omp('¿Cruzo la calle solo?',             ['No, con un adulto', 'Sí', 'Corriendo'], 'No, con un adulto'),
            omp('El semáforo en rojo para las personas significa…', ['Esperar', 'Cruzar', 'Correr'], 'Esperar', '🔴'),
            omp('¿Por dónde se cruza?',              ['Por la cebra', 'Por cualquier parte', 'Por el medio'], 'Por la cebra'),
            omp('Antes de cruzar hay que…',          ['Mirar a los dos lados', 'Cerrar los ojos', 'Correr'], 'Mirar a los dos lados'),
            omp('En el carro voy…',                  ['Con cinturón, en mi silla', 'De pie', 'En las piernas de alguien'], 'Con cinturón, en mi silla'),
        ]),

        est('Pedir ayuda', 'A quién y cuándo', '🆘', 'opcion_multiple', [
            omp('Si me pierdo, ¿qué hago?',           ['Me quedo quieto y busco a alguien de confianza', 'Corro', 'Me escondo'], 'Me quedo quieto y busco a alguien de confianza'),
            omp('Si alguien me hace sentir mal, ¿qué hago?', ['Le cuento a un adulto de confianza', 'Me callo', 'Lloro solo'], 'Le cuento a un adulto de confianza'),
            omp('Si un desconocido me ofrece algo…',  ['Digo que no y aviso', 'Lo acepto', 'Me voy con él'], 'Digo que no y aviso'),
            omp('Mi cuerpo es…',                       ['Mío', 'De todos', 'De nadie'], 'Mío'),
            omp('Si algo me da miedo, ¿qué hago?',     ['Lo cuento', 'Lo guardo', 'Lo olvido'], 'Lo cuento'),
        ]),

        est('Las señales que avisan', 'Los dibujos que dicen «cuidado»', '⚠️', 'emparejar', [
            ['e' => '⚠️', 'w' => 'Peligro'],
            ['e' => '🚫', 'w' => 'Prohibido'],
            ['e' => '🔥', 'w' => 'Quema'],
            ['e' => '☠️', 'w' => 'Venenoso'],
            ['e' => '🚸', 'w' => 'Niños cerca'],
            ['e' => '🆘', 'w' => 'Pedir ayuda'],
        ]),
    ],
],


// =====================================================================
//  PRIMARIA INICIAL
// =====================================================================

[
    'slug'  => 'dormir-para-crecer',
    'title' => 'Dormir para crecer',
    'description' => 'Mientras duermes el cuerpo repara, ordena lo aprendido y crece.',
    'objective' => 'Relacionar el sueño con el crecimiento, la memoria y el ánimo.',
    'icon' => '🛌', 'nivel' => 'primaria-inicial', 'bloque' => 'mente-y-emociones',
    'duracion' => 10, 'tags' => ['salud', 'autocuidado', 'cuerpo'],
    'estaciones' => [

        est('¿Qué pasa mientras duermo?', 'El cuerpo no se apaga', '🌙', 'opcion_multiple', [
            omp('Mientras duermo, el cuerpo…',      ['Se repara y crece', 'Se detiene del todo', 'Adelgaza'], 'Se repara y crece'),
            omp('El cerebro mientras duermo…',      ['Ordena lo que aprendí', 'Se apaga', 'Olvida todo'], 'Ordena lo que aprendí'),
            omp('Un niño de mi edad necesita dormir…', ['Unas 10 horas', '4 horas', '20 horas'], 'Unas 10 horas'),
            omp('Si duermo poco, al otro día…',     ['Me cuesta concentrarme', 'Aprendo más', 'Estoy igual'], 'Me cuesta concentrarme'),
            omp('Dormir poco también afecta…',      ['El ánimo', 'El color de ojos', 'La estatura de hoy'], 'El ánimo'),
        ]),

        est('Lo que ayuda a dormir', 'Y lo que no', '🛏️', 'juego_rapido',
            conTitulo('¿Ayuda a dormir bien?', 'Piensa en la última hora del día', [
                ['e' => '📖', 'n' => 'Leer un rato',          'ok' => true],
                ['e' => '📱', 'n' => 'Pantalla en la cama',   'ok' => false],
                ['e' => '🌡️', 'n' => 'Cuarto fresco y oscuro','ok' => true],
                ['e' => '🥤', 'n' => 'Bebidas con cafeína',   'ok' => false],
                ['e' => '🕘', 'n' => 'Acostarse a la misma hora','ok' => true],
                ['e' => '🍭', 'n' => 'Comer mucho dulce tarde','ok' => false],
                ['e' => '🚿', 'n' => 'Bañarse antes',         'ok' => true],
                ['e' => '🏃', 'n' => 'Correr justo antes',    'ok' => false],
            ])),

        est('Ordena la noche', 'Una rutina que funciona', '🌜', 'ordenar_secuencia', [
            'title' => 'Ordena una buena rutina de sueño',
            'items' => ['Cenar sin exceso', 'Apagar las pantallas',
                        'Cepillarse los dientes', 'Preparar la maleta de mañana',
                        'Leer un rato', 'Apagar la luz'],
        ]),

        est('Reto del sueño', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Dormir sirve para…',           ['Reparar y ordenar lo aprendido', 'Perder tiempo', 'Nada'], 'Reparar y ordenar lo aprendido'),
            reto('La luz de las pantallas…',     ['Hace más difícil dormirse', 'Ayuda a dormir', 'Da igual'], 'Hace más difícil dormirse'),
            reto('Acostarse a la misma hora…',   ['Ayuda al cuerpo', 'Es indiferente', 'Es malo'], 'Ayuda al cuerpo'),
            reto('Si no puedo dormir, conviene…',['Levantarme un rato y volver, sin pantalla', 'Ver videos', 'Quedarme angustiado'], 'Levantarme un rato y volver, sin pantalla'),
            reto('Dormir poco muchos días…',     ['Afecta el aprendizaje', 'No pasa nada', 'Mejora la memoria'], 'Afecta el aprendizaje'),
        ]),
    ],
],

[
    'slug'  => 'los-dientes-por-dentro',
    'title' => 'Los dientes por dentro',
    'description' => 'Cómo son, por qué se pican y cómo se cuidan de verdad.',
    'objective' => 'Comprender la caries y aplicar hábitos de higiene bucal.',
    'icon' => '🦷', 'nivel' => 'primaria-inicial', 'bloque' => 'habitos-saludables',
    'duracion' => 10, 'tags' => ['salud', 'cuerpo', 'autocuidado'],
    'estaciones' => [

        est('Cómo aparece una caries', 'No sale sola', '🦠', 'opcion_multiple', [
            omp('Las caries las hacen…',         ['Bacterias con el azúcar', 'El frío', 'El agua'], 'Bacterias con el azúcar'),
            omp('Lo que se queda entre los dientes es…', ['Comida que alimenta bacterias', 'Vitaminas', 'Nada'], 'Comida que alimenta bacterias'),
            omp('Una caries que no se trata…',   ['Crece y duele', 'Se cura sola', 'Desaparece'], 'Crece y duele'),
            omp('Los dientes de leche…',         ['También hay que cuidarlos', 'No importan', 'No se pican'], 'También hay que cuidarlos'),
            omp('¿Cuándo conviene ir al odontólogo?', ['Aunque no duela nada', 'Solo si duele', 'Nunca'], 'Aunque no duela nada'),
        ]),

        est('Ordena el cepillado', 'Hay una forma que funciona', '🪥', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se cepillan bien los dientes',
            'items' => ['Poner un poco de crema', 'Cepillar por fuera',
                        'Cepillar por dentro', 'Cepillar las muelas',
                        'Cepillar la lengua', 'Enjuagar'],
        ]),

        est('¿Cuidan o pican?', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿CUIDA los dientes?', 'Piensa en el azúcar y en la limpieza', [
                ['e' => '🪥', 'n' => 'Cepillarse 3 veces al día', 'ok' => true],
                ['e' => '🍬', 'n' => 'Dulces entre comidas',      'ok' => false],
                ['e' => '💧', 'n' => 'Tomar agua',                'ok' => true],
                ['e' => '🥤', 'n' => 'Gaseosa a diario',          'ok' => false],
                ['e' => '🧵', 'n' => 'Usar seda dental',          'ok' => true],
                ['e' => '🍎', 'n' => 'Comer fruta',               'ok' => true],
                ['e' => '🦷', 'n' => 'Abrir cosas con los dientes','ok' => false],
                ['e' => '🩺', 'n' => 'Revisión con el odontólogo','ok' => true],
            ])),

        est('Las partes del diente', 'Cada parte tiene su nombre', '🔬', 'opcion_multiple', [
            omp('La parte blanca y dura de afuera es…', ['El esmalte', 'La raíz', 'La encía'], 'El esmalte'),
            omp('Lo que sujeta el diente por dentro es…', ['La raíz', 'El esmalte', 'La lengua'], 'La raíz'),
            omp('La carne rosada alrededor es…',    ['La encía', 'La raíz', 'El esmalte'], 'La encía'),
            omp('Las de atrás, planas, sirven para…', ['Triturar', 'Cortar', 'Silbar'], 'Triturar'),
            omp('Las de adelante sirven para…',     ['Cortar', 'Triturar', 'Tragar'], 'Cortar'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR
// =====================================================================

[
    'slug'  => 'mi-cuerpo-esta-cambiando',
    'title' => 'Mi cuerpo está cambiando',
    'description' => 'La pubertad: qué cambia, cuándo y por qué cada uno va a su ritmo.',
    'objective' => 'Comprender los cambios de la pubertad como proceso normal y variable.',
    'icon' => '🌱', 'nivel' => 'primaria-superior', 'bloque' => 'mi-cuerpo',
    'duracion' => 15, 'tags' => ['cuerpo', 'salud', 'emociones'],
    'estaciones' => [

        est('Qué es la pubertad', 'Un proceso, no un día', '📈', 'opcion_multiple', [
            omp('La pubertad es…',                  ['Una etapa de cambios del cuerpo', 'Una enfermedad', 'Un examen'], 'Una etapa de cambios del cuerpo'),
            omp('¿A todos les llega a la misma edad?', ['No, cada uno a su ritmo', 'Sí, el mismo día', 'Solo a algunos'], 'No, cada uno a su ritmo'),
            omp('Lo que la pone en marcha son…',    ['Las hormonas', 'Las vitaminas', 'El clima'], 'Las hormonas'),
            omp('Ir más rápido o más lento que los demás es…', ['Normal', 'Un problema', 'Raro'], 'Normal'),
            omp('Estos cambios duran…',             ['Varios años', 'Una semana', 'Un día'], 'Varios años'),
        ]),

        est('Cambios que pasan', 'Algunos en todos, otros no', '🔄', 'juego_rapido',
            conTitulo('¿Es un cambio normal de la pubertad?', 'Piensa sin vergüenza: es biología', [
                ['e' => '📏', 'n' => 'Crecer de estatura',        'ok' => true],
                ['e' => '💪', 'n' => 'Cambios en el cuerpo',      'ok' => true],
                ['e' => '🗣️', 'n' => 'Cambios en la voz',         'ok' => true],
                ['e' => '😤', 'n' => 'Cambios de ánimo',          'ok' => true],
                ['e' => '🦷', 'n' => 'Perder todos los dientes',  'ok' => false],
                ['e' => '💦', 'n' => 'Sudar más',                 'ok' => true],
                ['e' => '👁️', 'n' => 'Cambiar de color de ojos',  'ok' => false],
                ['e' => '🌙', 'n' => 'Necesitar dormir más',      'ok' => true],
            ])),

        est('Higiene en esta etapa', 'El cuerpo pide más cuidado', '🚿', 'opcion_multiple', [
            omp('Al sudar más conviene…',        ['Bañarse a diario', 'Bañarse menos', 'Usar más perfume'], 'Bañarse a diario'),
            omp('La ropa de deporte…',           ['Se lava después de usarla', 'Se guarda sin lavar', 'Se airea y ya'], 'Se lava después de usarla'),
            omp('Los granitos en la cara…',      ['Son normales en esta etapa', 'Son por suciedad siempre', 'No le pasan a nadie'], 'Son normales en esta etapa'),
            omp('Si algo del cuerpo me preocupa…', ['Pregunto a un adulto o al médico', 'Busco en cualquier video', 'Me callo'], 'Pregunto a un adulto o al médico'),
            omp('Comparar mi cuerpo con el de otros…', ['No sirve: cada uno va a su ritmo', 'Es útil', 'Es obligatorio'], 'No sirve: cada uno va a su ritmo'),
        ]),

        est('Reto de los cambios', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('La pubertad la controlan…',    ['Las hormonas', 'La comida', 'El colegio'], 'Las hormonas'),
            reto('Que a un compañero le llegue antes significa…', ['Nada: es su ritmo', 'Que es mejor', 'Que algo va mal'], 'Nada: es su ritmo'),
            reto('Los cambios de ánimo en esta etapa son…', ['Frecuentes y normales', 'Un defecto', 'Mentira'], 'Frecuentes y normales'),
            reto('Para dudas sobre mi cuerpo, la mejor fuente es…', ['Un adulto de confianza o el médico', 'Un video cualquiera', 'Un compañero'], 'Un adulto de confianza o el médico'),
            reto('Burlarse del cuerpo de otro es…', ['Hacer daño de verdad', 'Un chiste', 'Normal'], 'Hacer daño de verdad'),
        ]),
    ],
],

[
    'slug'  => 'leer-una-etiqueta',
    'title' => 'Leer una etiqueta',
    'description' => 'Azúcares, porciones y publicidad: lo que dice el paquete y lo que esconde.',
    'objective' => 'Interpretar información nutricional y detectar estrategias publicitarias.',
    'icon' => '🏷️', 'nivel' => 'primaria-superior', 'bloque' => 'alimentacion-y-energia',
    'duracion' => 15, 'tags' => ['alimentacion', 'salud', 'deduccion'],
    'estaciones' => [

        est('Qué dice la etiqueta', 'Los números que importan', '🔢', 'opcion_multiple', [
            omp('La lista de ingredientes va ordenada…', ['De mayor a menor cantidad', 'Por orden alfabético', 'Al azar'], 'De mayor a menor cantidad'),
            omp('Si el azúcar es el primer ingrediente…', ['Es lo que más tiene', 'Es lo que menos tiene', 'No significa nada'], 'Es lo que más tiene'),
            omp('«Porción: 30 g» y el paquete trae 300 g. El paquete tiene…', ['10 porciones', '1 porción', '30 porciones'], '10 porciones'),
            omp('Si una porción tiene 10 g de azúcar y como el paquete entero…', ['Como 100 g', 'Como 10 g', 'No como azúcar'], 'Como 100 g'),
            omp('Los sellos de advertencia avisan de…', ['Exceso de azúcar, sal o grasas', 'Que es delicioso', 'El precio'], 'Exceso de azúcar, sal o grasas'),
        ]),

        est('Trucos de la publicidad', 'Lo que promete el paquete', '📢', 'opcion_multiple', [
            omp('«Natural» en un paquete significa…',  ['Casi nada por sí solo', 'Que no tiene azúcar', 'Que es sano'], 'Casi nada por sí solo'),
            omp('Una caja con un dibujo animado busca…', ['Que el niño la pida', 'Informar', 'Avisar'], 'Que el niño la pida'),
            omp('«Con vitaminas» puede aparecer en algo que además tiene…', ['Mucha azúcar', 'Solo fruta', 'Nada'], 'Mucha azúcar'),
            omp('Lo más fiable del paquete es…',       ['La tabla nutricional y los ingredientes', 'El dibujo', 'El eslogan'], 'La tabla nutricional y los ingredientes'),
            omp('«Light» quiere decir…',               ['Menos de algo, hay que mirar de qué', 'Sin calorías', 'Sano'], 'Menos de algo, hay que mirar de qué'),
        ]),

        est('¿De dónde saca energía el cuerpo?', 'Los tres grandes', '⚡', 'opcion_multiple', [
            omp('La energía rápida viene sobre todo de…', ['Los carbohidratos', 'El agua', 'Las vitaminas'], 'Los carbohidratos'),
            omp('Para construir músculo hacen falta…',    ['Proteínas', 'Azúcares', 'Sal'], 'Proteínas'),
            omp('Las vitaminas y minerales dan…',         ['Funciones, no energía', 'Mucha energía', 'Grasa'], 'Funciones, no energía'),
            omp('El agua aporta…',                        ['Cero calorías y es imprescindible', 'Mucha energía', 'Proteína'], 'Cero calorías y es imprescindible'),
            omp('Comer variado sirve para…',              ['Cubrir todo lo que el cuerpo necesita', 'Engordar', 'Nada'], 'Cubrir todo lo que el cuerpo necesita'),
        ]),

        est('Ordena la decisión', 'Cómo se elige en el supermercado', '🛒', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se elige un producto con criterio',
            'items' => ['Mirar los sellos de advertencia', 'Leer la lista de ingredientes',
                        'Ver cuánta azúcar y sal trae por porción', 'Mirar cuántas porciones trae el paquete',
                        'Comparar con otro producto parecido', 'Decidir'],
        ]),
    ],
],

[
    /*
     * Aquí iba una de primeros auxilios, pero `primeros-auxilios-basicos`
     * ya existe con el mismo nivel y la misma categoría. Esta mira el
     * momento ANTERIOR: el accidente que todavía no ha pasado, que es lo
     * único sobre lo que un niño puede decidir de verdad.
     */
    'slug'  => 'riesgos-que-se-ven-venir',
    'title' => 'Riesgos que se ven venir',
    'description' => 'Casi ningún accidente es una sorpresa. Casi todos avisan antes.',
    'objective' => 'Anticipar riesgos evaluando probabilidad y consecuencia antes de actuar.',
    'icon' => '🔭', 'nivel' => 'primaria-superior', 'bloque' => 'seguridad-y-cuidado',
    'duracion' => 14, 'tags' => ['seguridad', 'autocuidado', 'deduccion'],
    'estaciones' => [

        est('¿Qué podría salir mal?', 'La pregunta que evita casi todo', '🤔', 'opcion_multiple', [
            omp('Voy a cruzar entre dos carros parqueados. ¿Qué podría salir mal?', ['Que no me vean', 'Nada', 'Que llueva'], 'Que no me vean'),
            omp('Corro con las manos en los bolsillos. ¿Qué podría salir mal?', ['No puedo frenar la caída', 'Nada', 'Voy más rápido'], 'No puedo frenar la caída'),
            omp('Me subo a una silla para alcanzar algo alto. Mejor…', ['Pedir ayuda', 'Subirme igual', 'Saltar'], 'Pedir ayuda'),
            omp('Voy en bicicleta sin casco por poco rato. El riesgo…', ['Es el mismo en el primer minuto', 'Baja', 'No existe'], 'Es el mismo en el primer minuto'),
            omp('Hacerse la pregunta antes cuesta…', ['Dos segundos', 'Mucho tiempo', 'Dinero'], 'Dos segundos'),
        ]),

        est('Probable y grave', 'Dos cosas distintas', '📊', 'opcion_multiple', [
            omp('Tropezar en el salón es…',         ['Probable pero poco grave', 'Improbable y grave', 'Imposible'], 'Probable pero poco grave'),
            omp('Cruzar una avenida corriendo es…', ['Menos probable pero muy grave', 'Sin importancia', 'Seguro'], 'Menos probable pero muy grave'),
            omp('Lo que más hay que evitar es lo…', ['Muy grave, aunque sea raro', 'Muy probable y leve', 'Nada'], 'Muy grave, aunque sea raro'),
            omp('«A mí nunca me ha pasado» significa…', ['Que aún no ha pasado', 'Que no puede pasar', 'Que soy inmune'], 'Que aún no ha pasado'),
            omp('Usar cinturón protege contra algo…', ['Raro pero muy grave', 'Frecuente y leve', 'Imposible'], 'Raro pero muy grave'),
        ]),

        est('Señales de que algo va mal', 'El cuerpo y el entorno avisan', '🚩', 'juego_rapido',
            conTitulo('¿Es una SEÑAL de alarma?', 'Algo que debería hacerme parar', [
                ['e' => '💨', 'n' => 'Olor a gas en la cocina',      'ok' => true],
                ['e' => '🎵', 'n' => 'Música en la casa',            'ok' => false],
                ['e' => '💦', 'n' => 'Piso mojado en la escalera',   'ok' => true],
                ['e' => '☀️', 'n' => 'Día soleado',                  'ok' => false],
                ['e' => '🔌', 'n' => 'Un cable pelado',              'ok' => true],
                ['e' => '📚', 'n' => 'Libros sobre la mesa',         'ok' => false],
                ['e' => '🌊', 'n' => 'Corriente fuerte en el río',   'ok' => true],
                ['e' => '🥶', 'n' => 'Mareo y visión borrosa',       'ok' => true],
            ])),

        est('Reto de anticipar', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('La mejor herramienta de seguridad es…', ['Preguntarse qué podría salir mal', 'Correr rápido', 'La suerte'], 'Preguntarse qué podría salir mal'),
            reto('Un riesgo raro pero muy grave…',    ['Se evita igual', 'Se ignora', 'No cuenta'], 'Se evita igual'),
            reto('Si huele a gas, lo primero es…',    ['No encender nada y avisar', 'Encender la luz', 'Abrir la estufa'], 'No encender nada y avisar'),
            reto('«Solo será un momento» suele ser…', ['Una mala razón', 'Suficiente', 'Un dato'], 'Una mala razón'),
            reto('Si un plan me da mala espina…',     ['Lo digo y no lo hago', 'Lo hago igual', 'Callo'], 'Lo digo y no lo hago'),
        ]),
    ],
],

[
    'slug'  => 'la-cabeza-tambien-se-cuida',
    'title' => 'La cabeza también se cuida',
    'description' => 'Estrés, ansiedad y tristeza: cuándo es normal y cuándo hay que pedir ayuda.',
    'objective' => 'Reconocer señales de malestar emocional y vías de apoyo.',
    'icon' => '🧘', 'nivel' => 'primaria-superior', 'bloque' => 'mente-y-emociones',
    'duracion' => 15, 'tags' => ['emociones', 'salud', 'autorregulacion'],
    'estaciones' => [

        est('Normal o para consultar', 'La diferencia está en cuánto dura', '⏳', 'opcion_multiple', [
            omp('Estar nervioso antes de un examen es…', ['Normal', 'Para consultar siempre', 'Raro'], 'Normal'),
            omp('Estar triste unos días tras una pérdida es…', ['Normal', 'Anormal', 'Falso'], 'Normal'),
            omp('Estar triste todos los días durante semanas es…', ['Para hablarlo con alguien', 'Normal', 'Pasajero seguro'], 'Para hablarlo con alguien'),
            omp('Dejar de hacer lo que me gustaba es…',  ['Una señal para consultar', 'Normal', 'Bueno'], 'Una señal para consultar'),
            omp('Pedir ayuda por algo de la cabeza es…', ['Igual que ir al médico por la pierna', 'De débiles', 'Exagerado'], 'Igual que ir al médico por la pierna'),
        ]),

        est('Lo que ayuda de verdad', 'Y lo que solo tapa', '🧭', 'juego_rapido',
            conTitulo('¿Ayuda con el malestar emocional?', 'Piensa a mediano plazo', [
                ['e' => '🗣️', 'n' => 'Hablarlo con alguien',      'ok' => true],
                ['e' => '📱', 'n' => 'Pasar la noche en pantalla', 'ok' => false],
                ['e' => '🏃', 'n' => 'Moverse y hacer deporte',    'ok' => true],
                ['e' => '🤐', 'n' => 'Guardármelo todo',           'ok' => false],
                ['e' => '😴', 'n' => 'Dormir bien',                'ok' => true],
                ['e' => '🍬', 'n' => 'Comer para no sentir',       'ok' => false],
                ['e' => '🎨', 'n' => 'Hacer algo que me gusta',    'ok' => true],
                ['e' => '😠', 'n' => 'Pagarlo con otro',           'ok' => false],
            ])),

        est('Respirar y volver', 'Una técnica que sirve en el momento', '🌬️', 'ordenar_secuencia', [
            'title' => 'Ordena una técnica para calmarse',
            'items' => ['Notar que estoy alterado', 'Parar lo que estoy haciendo',
                        'Respirar hondo contando hasta cuatro', 'Soltar el aire despacio',
                        'Nombrar lo que siento', 'Decidir qué hago ahora'],
        ]),

        est('Reto del cuidado invisible', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('La salud mental es…',              ['Parte de la salud', 'Otra cosa', 'Un invento'], 'Parte de la salud'),
            reto('Una señal de alarma es…',          ['Que dure semanas y me impida vivir normal', 'Un mal día', 'Estar cansado'], 'Que dure semanas y me impida vivir normal'),
            reto('Si un amigo me dice que está muy mal…', ['Lo escucho y se lo digo a un adulto', 'Guardo el secreto', 'Me río'], 'Lo escucho y se lo digo a un adulto'),
            reto('Pedir ayuda a tiempo…',            ['Hace que se resuelva antes', 'Empeora', 'Da igual'], 'Hace que se resuelva antes'),
            reto('Lo que ayuda todos los días es…',  ['Dormir, moverse y hablar', 'Aguantar', 'Distraerse siempre'], 'Dormir, moverse y hablar'),
        ]),
    ],
],

],
];
