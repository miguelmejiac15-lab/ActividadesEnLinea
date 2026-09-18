<?php
/**
 * valores-ampliacion.php — Convivencia en los dos extremos
 *
 * Valores y Convivencia **no tenía ni una sola actividad de quinto y
 * sexto**, que es justo donde más falta hace: es la edad en que aparecen
 * el grupo cerrado, la presión de los pares y el conflicto que ya no se
 * resuelve con «dense la mano».
 *
 * Y tenía tres de preescolar, donde el trabajo es otro y es igual de
 * necesario: ponerle nombre a lo que se siente antes de poder manejarlo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE ESTAS ACTIVIDADES NO HACEN
 * ─────────────────────────────────────────────────────────────────────
 *
 * No juzgan al niño. Ninguna pregunta dice «¿eres de los que…?» ni
 * plantea un dilema donde una respuesta lo deja mal. Se pregunta **qué
 * conviene hacer**, no qué clase de persona es quien responde: un niño
 * que se siente evaluado moralmente por una pantalla deja de responder lo
 * que piensa y empieza a responder lo que cree que se espera, y entonces
 * la actividad no enseña nada.
 *
 * Y no prometen que todo se arregla. «A veces hay que pedir ayuda de un
 * adulto» es una respuesta correcta en varias estaciones, a propósito.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'valores',
    'name'       => 'Valores y Convivencia',
    'tagline'    => 'Vivir juntos: acuerdos, diferencias y conflictos bien resueltos',
    'icon'       => '🤝',
    'color'      => '#fb8c00',
    'sort_order' => 6,
],

'bloques' => [
    ['slug' => 'mis-emociones', 'name' => 'Mis Emociones', 'icon' => '💛', 'sort_order' => 1,
     'description' => 'Ponerle nombre a lo que siento y saber qué hacer con ello.'],
    ['slug' => 'vivir-juntos', 'name' => 'Vivir Juntos', 'icon' => '🤝', 'sort_order' => 2,
     'description' => 'Normas, acuerdos y cómo resolver un problema sin pelear.'],
    ['slug' => 'respeto-y-diferencia', 'name' => 'Respeto y Diferencia', 'icon' => '🌈', 'sort_order' => 3,
     'description' => 'Nadie es igual a otro, y eso no es un problema que resolver.'],
    ['slug' => 'resolver-conflictos', 'name' => 'Resolver Conflictos', 'icon' => '🕊️', 'sort_order' => 4,
     'description' => 'Qué hacer cuando hay un problema con otro, y qué NO hacer.'],
    ['slug' => 'inclusion-y-diversidad', 'name' => 'Inclusión y Diversidad', 'icon' => '🌍', 'sort_order' => 5,
     'description' => 'Ser distintos y valer lo mismo: reconocer y frenar la exclusión.'],
    ['slug' => 'responsabilidad-y-cuidado', 'name' => 'Responsabilidad y Cuidado', 'icon' => '🤲', 'sort_order' => 6,
     'description' => 'Hacerse cargo de lo propio, de lo común y de los demás.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR · ponerle nombre a lo que pasa
// =====================================================================

[
    'slug'  => 'caras-que-hablan',
    'title' => 'Caras que hablan',
    'description' => 'La cara dice cómo se siente alguien, aunque no lo diga con palabras.',
    'objective' => 'Reconocer emociones básicas en expresiones faciales.',
    'icon' => '😊', 'nivel' => 'preescolar', 'bloque' => 'mis-emociones',
    'duracion' => 8, 'tags' => ['emociones', 'observacion', 'social'],
    'estaciones' => [

        est('¿Cómo se siente?', 'Mira la cara', '🙂', 'opcion_multiple', [
            omp('😊 se siente…',  ['Contento', 'Triste', 'Bravo'], 'Contento', '😊'),
            omp('😢 se siente…',  ['Triste', 'Contento', 'Asustado'], 'Triste', '😢'),
            omp('😠 se siente…',  ['Bravo', 'Contento', 'Cansado'], 'Bravo', '😠'),
            omp('😨 se siente…',  ['Asustado', 'Contento', 'Bravo'], 'Asustado', '😨'),
            omp('😴 se siente…',  ['Con sueño', 'Bravo', 'Asustado'], 'Con sueño', '😴'),
        ]),

        est('Une la cara con el nombre', 'Cada emoción se llama de una forma', '🔗', 'emparejar', [
            ['e' => '😊', 'w' => 'Alegría'],
            ['e' => '😢', 'w' => 'Tristeza'],
            ['e' => '😠', 'w' => 'Rabia'],
            ['e' => '😨', 'w' => 'Miedo'],
            ['e' => '😮', 'w' => 'Sorpresa'],
            ['e' => '🥰', 'w' => 'Cariño'],
        ]),

        est('¿Cuándo me siento así?', 'Cada emoción tiene su momento', '💭', 'opcion_multiple', [
            omp('Me regalan algo. Me siento…',        ['Contento', 'Bravo', 'Asustado'], 'Contento', '🎁'),
            omp('Se me rompe mi juguete. Me siento…', ['Triste', 'Contento', 'Con sueño'], 'Triste', '🧸'),
            omp('Alguien me quita el turno. Me siento…', ['Bravo', 'Contento', 'Dormido'], 'Bravo'),
            omp('Escucho un ruido muy fuerte. Me siento…', ['Asustado', 'Contento', 'Aburrido'], 'Asustado', '🔊'),
            omp('Mi mamá me abraza. Me siento…',      ['Querido', 'Bravo', 'Asustado'], 'Querido', '🤗'),
        ]),

        est('Memoria de emociones', 'Encuentra las parejas', '🧠', 'memoria',
            ['😊', '😢', '😠', '😨', '😮', '🥰']),
    ],
],

[
    'slug'  => 'cuando-me-da-rabia',
    'title' => 'Cuando me da rabia',
    'description' => 'La rabia no es mala. Lo que se hace con ella sí puede serlo.',
    'objective' => 'Identificar señales corporales de la rabia y estrategias de autorregulación.',
    'icon' => '🌬️', 'nivel' => 'preescolar', 'bloque' => 'mis-emociones',
    'duracion' => 8, 'tags' => ['emociones', 'autorregulacion', 'convivencia'],
    'estaciones' => [

        est('Lo que avisa el cuerpo', 'La rabia se siente antes de estallar', '🫀', 'opcion_multiple', [
            omp('Cuando me da rabia, la cara se pone…',  ['Caliente', 'Fría', 'Azul'], 'Caliente'),
            omp('Las manos se ponen…',                   ['Apretadas', 'Sueltas', 'Frías'], 'Apretadas'),
            omp('El corazón va…',                        ['Rápido', 'Despacio', 'Igual'], 'Rápido'),
            omp('La voz sale…',                          ['Más fuerte', 'Más bajita', 'Igual'], 'Más fuerte'),
            omp('Si noto todo eso, lo mejor es…',        ['Parar un momento', 'Gritar', 'Pegar'], 'Parar un momento'),
        ]),

        est('¿Qué hago con la rabia?', 'Unas cosas ayudan y otras no', '✋', 'juego_rapido',
            conTitulo('¿Esto ayuda cuando estoy bravo?', 'Piensa qué pasa después', [
                ['e' => '🌬️', 'n' => 'Respirar hondo',      'ok' => true],
                ['e' => '👊', 'n' => 'Pegarle a alguien',    'ok' => false],
                ['e' => '🚶', 'n' => 'Alejarme un rato',     'ok' => true],
                ['e' => '🗣️', 'n' => 'Gritar a mi amigo',    'ok' => false],
                ['e' => '💧', 'n' => 'Tomar agua',           'ok' => true],
                ['e' => '🧸', 'n' => 'Romper un juguete',    'ok' => false],
                ['e' => '🧑', 'n' => 'Contarle a un adulto', 'ok' => true],
                ['e' => '🤫', 'n' => 'Guardarme todo',       'ok' => false],
            ])),

        est('Respirar como el globo', 'Un truco que sirve siempre', '🎈', 'ordenar_secuencia', [
            'title' => 'Ordena la respiración del globo',
            'items' => ['Parar lo que estoy haciendo', 'Tomar aire por la nariz',
                        'Inflar la barriga como un globo', 'Soltar el aire despacio por la boca',
                        'Repetir tres veces'],
        ]),

        est('Decirlo con palabras', 'Se puede decir sin pelear', '💬', 'opcion_multiple', [
            omp('Me quitaron el turno. Digo…',      ['«Era mi turno»', 'Nada', 'Un grito'], '«Era mi turno»'),
            omp('No me dejan jugar. Digo…',         ['«Quiero jugar también»', 'Nada', 'Empujo'], '«Quiero jugar también»'),
            omp('Me dijeron algo feo. Digo…',       ['«Eso me dolió»', 'Algo peor', 'Nada'], '«Eso me dolió»'),
            omp('Si no me escuchan, ¿qué hago?',    ['Busco a un adulto', 'Pego', 'Grito más'], 'Busco a un adulto'),
            omp('Decir lo que siento sirve para…',  ['Que me entiendan', 'Molestar', 'Nada'], 'Que me entiendan'),
        ]),
    ],
],

[
    'slug'  => 'jugar-con-otros',
    'title' => 'Jugar con otros',
    'description' => 'Prestar, esperar el turno y dejar entrar al que llega: lo que hace que el juego dure.',
    'objective' => 'Reconocer conductas prosociales básicas en el juego compartido.',
    'icon' => '🧩', 'nivel' => 'preescolar', 'bloque' => 'vivir-juntos',
    'duracion' => 8, 'tags' => ['convivencia', 'social', 'juego'],
    'estaciones' => [

        est('¿Está bien o no?', 'Piensa cómo se siente el otro', '⚖️', 'juego_rapido',
            conTitulo('¿Está bien hacerlo?', 'Piensa en el otro niño', [
                ['e' => '🤝', 'n' => 'Prestar un juguete',      'ok' => true],
                ['e' => '😤', 'n' => 'Quitarlo de un tirón',    'ok' => false],
                ['e' => '🧍', 'n' => 'Esperar mi turno',        'ok' => true],
                ['e' => '🚫', 'n' => 'Decir «tú no juegas»',    'ok' => false],
                ['e' => '👋', 'n' => 'Invitar al que está solo','ok' => true],
                ['e' => '😾', 'n' => 'Burlarme si pierde',      'ok' => false],
                ['e' => '👏', 'n' => 'Felicitar al que gana',   'ok' => true],
                ['e' => '💨', 'n' => 'Hacer trampa',            'ok' => false],
            ])),

        est('El turno', 'Cada uno cuando le toca', '🔄', 'opcion_multiple', [
            omp('Estamos en fila. ¿Qué hago?',             ['Espero mi turno', 'Me cuelo', 'Empujo'], 'Espero mi turno'),
            omp('Mi amigo está usando el columpio. ¿Qué hago?', ['Espero', 'Lo bajo', 'Grito'], 'Espero', '🛝'),
            omp('Ya jugué mucho rato. ¿Qué hago?',         ['Le doy el turno a otro', 'Sigo', 'Me escondo'], 'Le doy el turno a otro'),
            omp('Alguien se cuela. ¿Qué le digo?',         ['«Hay una fila»', 'Nada', 'Lo empujo'], '«Hay una fila»'),
            omp('Esperar el turno sirve para…',            ['Que todos jueguen', 'Perder tiempo', 'Nada'], 'Que todos jueguen'),
        ]),

        est('El que está solo', 'Un compañero afuera es un problema de todos', '👋', 'opcion_multiple', [
            omp('Veo a un niño solo en el recreo. ¿Qué hago?', ['Lo invito a jugar', 'Lo ignoro', 'Me río'], 'Lo invito a jugar'),
            omp('Dice que no quiere. ¿Qué hago?',          ['Respeto y le digo que puede venir después', 'Insisto mucho', 'Me enojo'], 'Respeto y le digo que puede venir después'),
            omp('Alguien nuevo llegó al salón. ¿Qué hago?',['Le hablo y le muestro todo', 'Nada', 'Me alejo'], 'Le hablo y le muestro todo'),
            omp('Si veo que dejan a alguien afuera…',      ['Lo invito o aviso', 'Miro', 'Me río'], 'Lo invito o aviso'),
            omp('Un grupo donde caben más es…',            ['Mejor para todos', 'Peor', 'Igual'], 'Mejor para todos'),
        ]),

        est('Ordena un juego en grupo', 'Antes de jugar hay acuerdos', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena cómo empieza un juego entre varios',
            'items' => ['Decidir a qué jugamos', 'Ponernos de acuerdo en las reglas',
                        'Repartir los turnos', 'Jugar', 'Recoger entre todos'],
        ]),
    ],
],

[
    'slug'  => 'somos-diferentes',
    'title' => 'Somos diferentes',
    'description' => 'Cada uno es de una forma, y todos valemos lo mismo.',
    'objective' => 'Reconocer y valorar diferencias visibles entre las personas sin jerarquizarlas.',
    'icon' => '🧑‍🤝‍🧑', 'nivel' => 'preescolar', 'bloque' => 'respeto-y-diferencia',
    'duracion' => 8, 'tags' => ['convivencia', 'social', 'observacion'],
    'estaciones' => [

        est('Nadie es igual a otro', 'Y eso está bien', '🌈', 'opcion_multiple', [
            omp('¿Todos tenemos el mismo pelo?',      ['No', 'Sí', 'Casi'], 'No'),
            omp('¿Todos somos del mismo tamaño?',     ['No', 'Sí', 'Casi'], 'No'),
            omp('¿A todos nos gusta lo mismo?',       ['No', 'Sí', 'Siempre'], 'No'),
            omp('¿Ser diferente es malo?',            ['No', 'Sí', 'A veces'], 'No'),
            omp('¿Valemos todos lo mismo?',           ['Sí', 'No', 'Depende'], 'Sí'),
        ]),

        est('Cada uno a su manera', 'Hay muchas formas de hacer lo mismo', '🦽', 'opcion_multiple', [
            omp('Un niño en silla de ruedas, ¿puede jugar?', ['Sí, de otra forma', 'No', 'Solo mirar'], 'Sí, de otra forma'),
            omp('Un niño que no oye, ¿puede hablar conmigo?', ['Sí, con señas o dibujos', 'No', 'Nunca'], 'Sí, con señas o dibujos'),
            omp('Un niño que usa gafas, ¿ve?',        ['Sí, con sus gafas', 'No', 'A medias'], 'Sí, con sus gafas'),
            omp('Si alguien necesita más tiempo…',    ['Lo espero', 'Me adelanto', 'Me río'], 'Lo espero'),
            omp('Si alguien hace las cosas distinto…', ['Está bien', 'Está mal', 'Hay que corregirlo'], 'Está bien'),
        ]),

        est('Lo que compartimos', 'Distintos por fuera, iguales en lo importante', '💛', 'seleccion_imagenes',
            conTitulo('Toca lo que TODOS necesitamos', 'Da igual cómo seamos', [
                ['e' => '❤️', 'n' => 'Cariño',      'ok' => true],
                ['e' => '💎', 'n' => 'Ser rico',    'ok' => false],
                ['e' => '🍎', 'n' => 'Comida',      'ok' => true],
                ['e' => '🏆', 'n' => 'Ganar siempre','ok' => false],
                ['e' => '🏠', 'n' => 'Un hogar',    'ok' => true],
                ['e' => '📚', 'n' => 'Aprender',    'ok' => true],
                ['e' => '👟', 'n' => 'Zapatos caros','ok' => false],
                ['e' => '😴', 'n' => 'Descansar',   'ok' => true],
            ])),

        est('Palabras que cuidan', 'Lo que se dice deja marca', '💬', 'opcion_multiple', [
            omp('Un compañero habla distinto. Le digo…', ['«Cuéntame más»', 'Me burlo', 'Nada'], '«Cuéntame más»'),
            omp('Alguien come algo que no conozco. Digo…', ['«¿A qué sabe?»', '«¡Guácala!»', 'Nada'], '«¿A qué sabe?»'),
            omp('Si alguien se burla de otro, yo…',     ['Digo que no está bien', 'Me río', 'Me voy'], 'Digo que no está bien'),
            omp('Reírse DE alguien y reírse CON alguien…', ['No es lo mismo', 'Es igual', 'Da lo mismo'], 'No es lo mismo'),
            omp('Una palabra fea puede…',               ['Doler mucho', 'No hacer nada', 'Ser graciosa'], 'Doler mucho'),
        ]),
    ],
],

[
    'slug'  => 'cuido-lo-que-es-de-todos',
    'title' => 'Cuido lo que es de todos',
    'description' => 'El salón, los juguetes y el parque son de todos, y eso obliga a algo.',
    'objective' => 'Reconocer el cuidado de los bienes comunes como responsabilidad compartida.',
    'icon' => '🤲', 'nivel' => 'preescolar', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 8, 'tags' => ['convivencia', 'autocuidado', 'ciudadania'],
    'estaciones' => [

        est('¿De quién es?', 'Mío, tuyo o de todos', '🏫', 'opcion_multiple', [
            omp('Los juguetes del salón son…',   ['De todos', 'Míos', 'Del profe'], 'De todos'),
            omp('Mi lonchera es…',               ['Mía', 'De todos', 'De nadie'], 'Mía', '🎒'),
            omp('El parque del barrio es…',      ['De todos', 'Mío', 'De nadie'], 'De todos', '🛝'),
            omp('Si algo es de todos, hay que…', ['Cuidarlo entre todos', 'Llevárselo', 'Romperlo'], 'Cuidarlo entre todos'),
            omp('Si rompo algo del salón…',      ['Aviso y ayudo a arreglarlo', 'Lo escondo', 'Culpo a otro'], 'Aviso y ayudo a arreglarlo'),
        ]),

        est('Recoger y ordenar', 'Cada cosa a su sitio', '🧹', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se recoge el salón',
            'items' => ['El profe avisa que se acabó el juego', 'Dejar de jugar',
                        'Guardar cada cosa en su caja', 'Botar la basura',
                        'Sentarse en el puesto'],
        ]),

        est('La basura en su sitio', 'Dónde va cada cosa', '🗑️', 'opcion_multiple', [
            omp('La cáscara del banano va…',     ['A la basura', 'Al piso', 'Al bolsillo'], 'A la basura', '🍌'),
            omp('El papel usado va…',            ['A la caneca de papel', 'Al piso', 'Al agua'], 'A la caneca de papel', '📄'),
            omp('Si veo basura en el piso…',     ['La recojo', 'La piso', 'La ignoro'], 'La recojo'),
            omp('Botar basura al suelo es…',     ['Ensuciar lo de todos', 'Normal', 'Rápido'], 'Ensuciar lo de todos'),
            omp('El agua del lavamanos…',        ['Se cierra al terminar', 'Se deja abierta', 'Se juega con ella'], 'Se cierra al terminar', '🚰'),
        ]),

        est('Mis cosas y mis tareas', 'Hacerse cargo de lo propio', '🎒', 'opcion_multiple', [
            omp('Al llegar a casa, la maleta…',  ['La guardo en su sitio', 'La tiro', 'La dejo en la puerta'], 'La guardo en su sitio'),
            omp('Mis zapatos van…',              ['Donde van siempre', 'En cualquier parte', 'Debajo de la cama'], 'Donde van siempre'),
            omp('Si prometo algo…',              ['Lo cumplo', 'Lo olvido', 'Lo cambio'], 'Lo cumplo'),
            omp('Ayudar en casa es…',            ['Cosa de todos', 'Solo de los grandes', 'Un castigo'], 'Cosa de todos'),
            omp('Si me equivoco, lo mejor es…',  ['Decirlo', 'Esconderlo', 'Culpar a otro'], 'Decirlo'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR · lo que aparece a los diez
// =====================================================================

[
    'slug'  => 'presion-del-grupo',
    'title' => 'La presión del grupo',
    'description' => 'Hacer algo solo porque lo hacen todos es la forma más común de equivocarse.',
    'objective' => 'Reconocer la presión de pares y practicar formas de negarse sin romper el vínculo.',
    'icon' => '👥', 'nivel' => 'primaria-superior', 'bloque' => 'vivir-juntos',
    'duracion' => 15, 'tags' => ['convivencia', 'social', 'autocuidado'],
    'estaciones' => [

        est('«Todos lo hacen»', 'La frase que más convence y menos razones tiene', '🗣️', 'opcion_multiple', [
            omp('«Todos lo hacen» es una razón…',      ['Mala', 'Buena', 'Definitiva'], 'Mala'),
            omp('Si todos se equivocan, el error es…', ['Error igual', 'Correcto', 'Menos grave'], 'Error igual'),
            omp('«Si no lo haces no eres del grupo» es…', ['Presión', 'Amistad', 'Un consejo'], 'Presión'),
            omp('Un amigo de verdad…',                 ['Acepta un no', 'Insiste siempre', 'Se enoja'], 'Acepta un no'),
            omp('Decir que no cuesta más cuando…',     ['Están mirando todos', 'Estoy solo', 'Es de día'], 'Están mirando todos'),
        ]),

        est('Formas de decir que no', 'Hay más de una, y algunas funcionan mejor', '✋', 'opcion_multiple', [
            omp('¿Cuál funciona mejor?', ['«No, gracias» y cambiar de tema', 'Quedarse callado', 'Reírse y hacerlo'], '«No, gracias» y cambiar de tema'),
            omp('¿Cuál funciona mejor?', ['«No me interesa, los alcanzo luego»', 'Discutir una hora', 'Insultar'], '«No me interesa, los alcanzo luego»'),
            omp('Si insisten mucho, conviene…', ['Irme del lugar', 'Quedarme y ceder', 'Gritar'], 'Irme del lugar'),
            omp('Tener una excusa preparada…',  ['Ayuda', 'Es mentir', 'No sirve'], 'Ayuda'),
            omp('Si ya cedí una vez, ¿puedo negarme la siguiente?', ['Sí, siempre', 'No', 'Solo si nadie sabe'], 'Sí, siempre'),
        ]),

        est('¿Presión o acuerdo?', 'No todo lo del grupo es presión', '⚖️', 'juego_rapido',
            conTitulo('¿Esto es PRESIÓN indebida?', 'Piensa si te están quitando la decisión', [
                ['e' => '😬', 'n' => '«Si no lo haces, no eres mi amigo»', 'ok' => true],
                ['e' => '🤝', 'n' => '«Decidimos entre todos las reglas»', 'ok' => false],
                ['e' => '📵', 'n' => '«Todos mandan eso, mándalo tú»',     'ok' => true],
                ['e' => '📚', 'n' => '«Estudiemos juntos el viernes»',     'ok' => false],
                ['e' => '🤐', 'n' => '«No le cuentes a nadie, ni a tus papás»', 'ok' => true],
                ['e' => '⚽', 'n' => '«¿Te unes al equipo?»',              'ok' => false],
            ])),

        est('Reto de decidir solo', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('La mejor señal de alarma es…',       ['«No le cuentes a nadie»', 'Que sean muchos', 'Que sea divertido'], '«No le cuentes a nadie»'),
            reto('Ceder por miedo a quedar fuera es…', ['Muy común, y se puede revertir', 'Imposible', 'Definitivo'], 'Muy común, y se puede revertir'),
            reto('Si un amigo está en problemas por presión…', ['Le ayudo a salir y aviso si hace falta', 'Me río', 'Lo dejo'], 'Le ayudo a salir y aviso si hace falta'),
            reto('Pedir ayuda a un adulto es…',        ['Una buena decisión', 'Ser sapo', 'De bebés'], 'Una buena decisión'),
            reto('Mi decisión final la tomo…',         ['Yo', 'El grupo', 'El más fuerte'], 'Yo'),
        ]),
    ],
],

[
    'slug'  => 'el-acoso-y-quien-mira',
    'title' => 'El acoso y quien mira',
    'description' => 'En el acoso hay tres papeles, y el de quien mira es el que puede cambiarlo todo.',
    'objective' => 'Distinguir conflicto de acoso e identificar el papel del observador.',
    'icon' => '🛡️', 'nivel' => 'primaria-superior', 'bloque' => 'inclusion-y-diversidad',
    'duracion' => 16, 'tags' => ['convivencia', 'social', 'seguridad'],
    'estaciones' => [

        est('¿Conflicto o acoso?', 'No es lo mismo', '⚖️', 'opcion_multiple', [
            omp('Dos amigos discuten una vez y se arreglan. Es…', ['Un conflicto', 'Acoso', 'Nada'], 'Un conflicto'),
            omp('A un niño lo molestan todos los días los mismos. Es…', ['Acoso', 'Un conflicto', 'Un juego'], 'Acoso'),
            omp('En el acoso, ¿hay igualdad de fuerzas?', ['No, uno tiene más poder', 'Sí', 'Siempre'], 'No, uno tiene más poder'),
            omp('El acoso se repite…',                    ['En el tiempo', 'Una vez', 'Nunca'], 'En el tiempo'),
            omp('«Era jugando» cuando el otro sufre es…', ['Una excusa', 'Verdad', 'Suficiente'], 'Una excusa'),
        ]),

        est('Los tres papeles', 'Quien lo hace, quien lo sufre y quien mira', '👥', 'opcion_multiple', [
            omp('Quien mira y se ríe…',                  ['Está ayudando al acoso', 'No hace nada', 'Lo frena'], 'Está ayudando al acoso'),
            omp('Quien mira y se calla…',                ['Deja que siga', 'Lo frena', 'Está a salvo'], 'Deja que siga'),
            omp('Quien mira y avisa…',                   ['Puede frenarlo', 'Empeora todo', 'Es un sapo'], 'Puede frenarlo'),
            omp('Avisar a un adulto de un acoso es…',    ['Proteger a alguien', 'Acusar', 'Cobardía'], 'Proteger a alguien'),
            omp('El grupo que no se ríe…',               ['Le quita la gracia al acoso', 'No cambia nada', 'Empeora'], 'Le quita la gracia al acoso'),
        ]),

        est('Qué hacer', 'Acciones concretas, no buenas intenciones', '🧭', 'juego_rapido',
            conTitulo('¿Esto AYUDA a frenar el acoso?', 'Piensa en el efecto real', [
                ['e' => '🧑‍🏫', 'n' => 'Contárselo a un adulto',      'ok' => true],
                ['e' => '😂', 'n' => 'Reírme de la burla',            'ok' => false],
                ['e' => '🤝', 'n' => 'Acompañar al que lo sufre',     'ok' => true],
                ['e' => '📱', 'n' => 'Reenviar el video de la burla', 'ok' => false],
                ['e' => '🗣️', 'n' => 'Decir «déjalo ya» en el momento','ok' => true],
                ['e' => '🙈', 'n' => 'Hacer como que no vi nada',     'ok' => false],
                ['e' => '📝', 'n' => 'Guardar pruebas de lo que pasa','ok' => true],
                ['e' => '👊', 'n' => 'Pegarle al que molesta',        'ok' => false],
            ])),

        est('Cuando pasa en línea', 'El ciberacoso no se apaga al salir del colegio', '📱', 'opcion_multiple', [
            omp('Lo peor del acoso en línea es que…',   ['No para al llegar a casa', 'Es más corto', 'No duele'], 'No para al llegar a casa'),
            omp('Si recibo mensajes que me hacen daño…', ['Guardo pruebas y aviso', 'Respondo peor', 'Los borro y callo'], 'Guardo pruebas y aviso'),
            omp('Reenviar una burla es…',               ['Participar', 'Neutral', 'Ayudar'], 'Participar'),
            omp('Bloquear y reportar sirve para…',      ['Cortar el contacto', 'Nada', 'Empeorar'], 'Cortar el contacto'),
            omp('Si me piden que no le cuente a nadie…', ['Más razón para contarlo', 'Cumplo', 'Lo pienso'], 'Más razón para contarlo'),
        ]),
    ],
],

[
    'slug'  => 'mediar-en-un-conflicto',
    'title' => 'Mediar en un conflicto',
    'description' => 'Ayudar a que dos se entiendan sin tomar partido ni decidir por ellos.',
    'objective' => 'Aplicar los pasos de la mediación entre pares.',
    'icon' => '🕊️', 'nivel' => 'primaria-superior', 'bloque' => 'resolver-conflictos',
    'duracion' => 15, 'tags' => ['convivencia', 'social', 'comprension'],
    'estaciones' => [

        est('Qué hace un mediador', 'Y qué no hace', '🧑‍⚖️', 'opcion_multiple', [
            omp('Un mediador…',                     ['Ayuda a que hablen', 'Decide quién gana', 'Castiga'], 'Ayuda a que hablen'),
            omp('¿Toma partido por uno?',           ['No', 'Sí', 'Por el amigo'], 'No'),
            omp('¿Quién decide la solución?',       ['Los dos que discuten', 'El mediador', 'El profe'], 'Los dos que discuten'),
            omp('El mediador escucha…',             ['A los dos por igual', 'Al que habla más', 'Al que tiene razón'], 'A los dos por igual'),
            omp('Si el conflicto es grave o hay violencia…', ['Llamo a un adulto', 'Medio yo', 'Lo dejo'], 'Llamo a un adulto'),
        ]),

        est('Ordena la mediación', 'Tiene pasos, y el orden importa', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos de una mediación',
            'items' => ['Acordar las reglas: hablar por turnos y sin insultos',
                        'Que cada uno cuente lo que pasó', 'Que cada uno diga cómo se sintió',
                        'Buscar juntos varias soluciones', 'Elegir una que sirva a los dos',
                        'Quedar en revisar si funcionó'],
        ]),

        est('Escuchar de verdad', 'Casi nadie escucha: casi todos esperan su turno', '👂', 'opcion_multiple', [
            omp('Escuchar bien es…',                 ['Entender antes de responder', 'Esperar mi turno', 'Estar callado'], 'Entender antes de responder'),
            omp('«Entonces lo que te molestó fue…» sirve para…', ['Comprobar que entendí', 'Ganar', 'Rellenar'], 'Comprobar que entendí'),
            omp('Interrumpir hace que el otro…',     ['Se cierre', 'Hable más', 'Entienda'], 'Se cierre'),
            omp('Hablar de lo que pasó, no de cómo es la persona, sirve para…', ['Que no se defienda', 'Ganar la discusión', 'Nada'], 'Que no se defienda'),
            omp('«Siempre haces lo mismo» es…',      ['Un ataque', 'Un dato', 'Una solución'], 'Un ataque'),
        ]),

        est('Reto del mediador', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Lo primero en una mediación es…',  ['Acordar las reglas', 'Decidir quién tiene razón', 'Castigar'], 'Acordar las reglas'),
            reto('Si uno de los dos no quiere mediar…', ['No se puede obligar', 'Se hace igual', 'Gana el otro'], 'No se puede obligar'),
            reto('Una buena solución es la que…',    ['Sirve a los dos', 'Gana uno', 'Es rápida'], 'Sirve a los dos'),
            reto('Si hay golpes, el mediador…',      ['Llama a un adulto', 'Sigue', 'Se mete'], 'Llama a un adulto'),
            reto('Revisar después si funcionó es…',  ['Parte del acuerdo', 'Innecesario', 'Desconfiar'], 'Parte del acuerdo'),
        ]),
    ],
],

[
    'slug'  => 'lo-justo-y-lo-igual',
    'title' => 'Lo justo y lo igual',
    'description' => 'Dar lo mismo a todos no siempre es justo. A veces justo es dar distinto.',
    'objective' => 'Distinguir igualdad de equidad mediante situaciones concretas.',
    'icon' => '⚖️', 'nivel' => 'primaria-superior', 'bloque' => 'inclusion-y-diversidad',
    'duracion' => 14, 'tags' => ['convivencia', 'logica', 'ciudadania'],
    'estaciones' => [

        est('Igual no siempre es justo', 'Un ejemplo lo aclara todo', '📦', 'opcion_multiple', [
            omp('Tres niños de distinta altura quieren ver por encima de una cerca. Darles la MISMA caja a cada uno es…', ['Igual, pero no justo', 'Justo', 'Imposible'], 'Igual, pero no justo'),
            omp('Darle más cajas al más bajito es…', ['Equitativo', 'Injusto', 'Igual'], 'Equitativo'),
            omp('Quitar la cerca sería…',            ['Resolver el problema de raíz', 'Peor', 'Igual'], 'Resolver el problema de raíz'),
            omp('A un niño que no ve bien se le sienta adelante. Eso es…', ['Equidad', 'Favoritismo', 'Injusticia'], 'Equidad'),
            omp('Dar más tiempo en un examen a quien lo necesita es…', ['Equidad', 'Trampa', 'Injusto'], 'Equidad'),
        ]),

        est('¿Igualdad o equidad?', 'Decide en cada caso', '⚖️', 'juego_rapido',
            conTitulo('¿Es EQUIDAD (dar según lo que cada uno necesita)?', 'Lo contrario sería dar lo mismo a todos', [
                ['e' => '🦽', 'n' => 'Una rampa en la entrada',         'ok' => true],
                ['e' => '🍎', 'n' => 'Una manzana a cada uno',          'ok' => false],
                ['e' => '👓', 'n' => 'Letra grande para quien ve poco', 'ok' => true],
                ['e' => '📏', 'n' => 'El mismo pupitre para todos',     'ok' => false],
                ['e' => '🈂️', 'n' => 'Traductor para quien no habla el idioma', 'ok' => true],
                ['e' => '⏰', 'n' => 'La misma hora de entrada',        'ok' => false],
            ])),

        est('Los derechos de todos', 'Lo que no se le puede quitar a nadie', '📜', 'opcion_multiple', [
            omp('El derecho a estudiar lo tiene…',   ['Todo niño', 'Solo quien paga', 'Solo el que saca buenas notas'], 'Todo niño'),
            omp('¿Se puede excluir a alguien por su origen?', ['No', 'Sí', 'A veces'], 'No'),
            omp('Un derecho viene siempre con…',     ['Un deber', 'Un premio', 'Nada'], 'Un deber'),
            omp('Si a alguien le niegan un derecho…', ['Hay que decirlo', 'Es su problema', 'Es normal'], 'Hay que decirlo'),
            omp('«Ajuste razonable» significa…',     ['Cambiar algo para que otro pueda participar', 'Bajar el nivel', 'Hacer trampa'], 'Cambiar algo para que otro pueda participar'),
        ]),

        est('Reto de lo justo', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Igualdad es dar…',                 ['Lo mismo a todos', 'Según la necesidad', 'Nada'], 'Lo mismo a todos'),
            reto('Equidad es dar…',                  ['Según la necesidad', 'Lo mismo a todos', 'Al que llegue'], 'Según la necesidad'),
            reto('Una rampa beneficia…',             ['A más gente de la que se cree', 'Solo a uno', 'A nadie'], 'A más gente de la que se cree'),
            reto('Excluir a alguien «porque es distinto» es…', ['Discriminación', 'Una opinión', 'Normal'], 'Discriminación'),
            reto('La mejor solución suele…',         ['Quitar la barrera para todos', 'Compensar uno a uno siempre', 'No hacer nada'], 'Quitar la barrera para todos'),
        ]),
    ],
],

[
    'slug'  => 'acuerdos-que-se-cumplen',
    'title' => 'Acuerdos que se cumplen',
    'description' => 'Una norma impuesta se rompe; una acordada entre todos se sostiene.',
    'objective' => 'Participar en la construcción de normas y comprender su función.',
    'icon' => '🗳️', 'nivel' => 'primaria-superior', 'bloque' => 'vivir-juntos',
    'duracion' => 14, 'tags' => ['convivencia', 'ciudadania', 'logica'],
    'estaciones' => [

        est('¿Para qué sirve una norma?', 'No es para fastidiar', '📏', 'opcion_multiple', [
            omp('Las normas de tránsito existen para…', ['Que nadie salga herido', 'Fastidiar', 'Recaudar'], 'Que nadie salga herido'),
            omp('Una norma buena es…',                  ['Clara y con una razón', 'Larga', 'Secreta'], 'Clara y con una razón'),
            omp('Si nadie entiende para qué es una norma…', ['Nadie la cumple', 'Se cumple igual', 'Mejor'], 'Nadie la cumple'),
            omp('Las normas se pueden…',                ['Revisar y cambiar', 'Nunca cambiar', 'Ignorar'], 'Revisar y cambiar'),
            omp('Una norma que se acuerda entre todos…', ['Se cumple más', 'Se cumple menos', 'Es igual'], 'Se cumple más'),
        ]),

        est('Escribir una buena norma', 'En positivo y concreta', '✍️', 'opcion_multiple', [
            omp('¿Cuál está mejor escrita?', ['Hablamos por turnos', 'No hagas cosas malas', 'Pórtate bien'], 'Hablamos por turnos'),
            omp('¿Cuál está mejor escrita?', ['Dejamos el salón recogido al salir', 'No ensuciar', 'Sé limpio'], 'Dejamos el salón recogido al salir'),
            omp('Una norma en positivo dice…', ['Qué SÍ hacer', 'Qué no hacer', 'Nada'], 'Qué SÍ hacer'),
            omp('«Ser respetuoso» es…',       ['Demasiado vago', 'Perfecto', 'Corto'], 'Demasiado vago'),
            omp('Conviene que las normas sean…', ['Pocas y claras', 'Muchísimas', 'Secretas'], 'Pocas y claras'),
        ]),

        est('Ordena cómo se acuerda', 'De la propuesta al compromiso', '🤝', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se construyen las normas del curso',
            'items' => ['Hablar de qué problemas hay', 'Proponer normas entre todos',
                        'Discutir cuáles sirven', 'Votar las que quedan',
                        'Escribirlas y firmarlas', 'Revisarlas al final del periodo'],
        ]),

        est('Cuando alguien incumple', 'La consecuencia no es venganza', '⚖️', 'opcion_multiple', [
            omp('Una buena consecuencia…',    ['Repara el daño', 'Humilla', 'Es al azar'], 'Repara el daño'),
            omp('Si alguien ensucia, lo lógico es…', ['Que limpie', 'Que se quede sin recreo un mes', 'Nada'], 'Que limpie'),
            omp('La consecuencia debe conocerse…', ['Antes, no después', 'Después', 'Nunca'], 'Antes, no después'),
            omp('Aplicar la norma solo a algunos es…', ['Injusto', 'Práctico', 'Normal'], 'Injusto'),
            omp('Pedir perdón sin reparar el daño es…', ['Insuficiente', 'Suficiente', 'De más'], 'Insuficiente'),
        ]),
    ],
],

[
    'slug'  => 'cuidar-lo-comun',
    'title' => 'Cuidar lo común',
    'description' => 'El agua, el parque, el planeta: lo que es de todos no es de nadie en particular.',
    'objective' => 'Relacionar decisiones individuales con su efecto acumulado sobre bienes comunes.',
    'icon' => '🌍', 'nivel' => 'primaria-superior', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 14, 'tags' => ['ciudadania', 'convivencia', 'logica'],
    'estaciones' => [

        est('El efecto de muchos', 'Uno solo no se nota; mil sí', '➗', 'opcion_multiple', [
            omp('Si una persona bota un papel, casi no se nota. Si lo botan 1000…', ['Hay un basurero', 'Sigue igual', 'Se limpia solo'], 'Hay un basurero'),
            omp('Dejar el grifo abierto 5 minutos al día, un año, gasta…', ['Muchísima agua', 'Casi nada', 'Nada'], 'Muchísima agua'),
            omp('«Total, uno más da igual» es…',      ['El razonamiento que destruye lo común', 'Correcto', 'Científico'], 'El razonamiento que destruye lo común'),
            omp('Si todos piensan «que lo arregle otro»…', ['No lo arregla nadie', 'Se arregla', 'Da igual'], 'No lo arregla nadie'),
            omp('Mi acción individual importa porque…', ['Se suma a la de los demás', 'No importa', 'Es única'], 'Se suma a la de los demás'),
        ]),

        est('Decisiones de todos los días', 'Pequeñas, y muchas', '🔁', 'juego_rapido',
            conTitulo('¿Cuida lo común?', 'Piensa en el efecto si lo hacen todos', [
                ['e' => '🚰', 'n' => 'Cerrar el grifo al cepillarme', 'ok' => true],
                ['e' => '🗑️', 'n' => 'Tirar basura al río',          'ok' => false],
                ['e' => '💡', 'n' => 'Apagar la luz al salir',        'ok' => true],
                ['e' => '🚿', 'n' => 'Ducharme media hora',           'ok' => false],
                ['e' => '♻️', 'n' => 'Separar los residuos',          'ok' => true],
                ['e' => '🌳', 'n' => 'Romper ramas del parque',       'ok' => false],
                ['e' => '🚲', 'n' => 'Ir en bicicleta cerca',         'ok' => true],
                ['e' => '🔌', 'n' => 'Dejar todo enchufado siempre',  'ok' => false],
            ])),

        est('Participar de verdad', 'Quejarse no es participar', '🙋', 'opcion_multiple', [
            omp('El parque del barrio está descuidado. Lo más útil es…', ['Organizar con otros y pedirlo formalmente', 'Quejarse en casa', 'Nada'], 'Organizar con otros y pedirlo formalmente'),
            omp('El gobierno escolar sirve para…',  ['Que los estudiantes decidan cosas', 'Adornar', 'Premiar'], 'Que los estudiantes decidan cosas'),
            omp('Votar sin informarse es…',         ['Desaprovechar el voto', 'Lo normal', 'Mejor'], 'Desaprovechar el voto'),
            omp('Una propuesta buena viene con…',   ['Cómo se haría', 'Solo la queja', 'Culpables'], 'Cómo se haría'),
            omp('Si mi propuesta pierde la votación…', ['La acepto y sigo participando', 'Me retiro', 'Saboteo'], 'La acepto y sigo participando'),
        ]),

        est('Reto de lo común', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('«Lo común» es lo que…',            ['Es de todos y de nadie en particular', 'No tiene dueño y da igual', 'Es del Estado solamente'], 'Es de todos y de nadie en particular'),
            reto('El daño al bien común suele venir de…', ['Muchas acciones pequeñas', 'Una sola grande', 'Nadie'], 'Muchas acciones pequeñas'),
            reto('Participar es…',                   ['Proponer y hacerse cargo', 'Quejarse', 'Votar y ya'], 'Proponer y hacerse cargo'),
            reto('Si cuido lo común, gano…',         ['Yo también', 'Nada', 'Solo los otros'], 'Yo también'),
            reto('La mejor forma de empezar es…',    ['Por lo que está en mi mano', 'Esperando a otros', 'Con una ley'], 'Por lo que está en mi mano'),
        ]),
    ],
],

[
    'slug'  => 'emociones-dificiles',
    'title' => 'Emociones difíciles',
    'description' => 'Envidia, vergüenza, frustración: las que nadie enseña a nombrar.',
    'objective' => 'Nombrar emociones complejas y reconocer respuestas adaptativas.',
    'icon' => '💭', 'nivel' => 'primaria-superior', 'bloque' => 'mis-emociones',
    'duracion' => 14, 'tags' => ['emociones', 'autorregulacion', 'social'],
    'estaciones' => [

        est('Ponerle nombre', 'Lo que se nombra se maneja mejor', '🏷️', 'opcion_multiple', [
            omp('Mi amigo ganó lo que yo quería y me molesta. Eso es…', ['Envidia', 'Miedo', 'Alegría'], 'Envidia'),
            omp('Me equivoqué delante de todos y quiero desaparecer. Eso es…', ['Vergüenza', 'Rabia', 'Sorpresa'], 'Vergüenza'),
            omp('Lo intenté cinco veces y no me sale. Eso es…', ['Frustración', 'Envidia', 'Cariño'], 'Frustración'),
            omp('Hice algo que sé que estuvo mal. Eso es…', ['Culpa', 'Miedo', 'Orgullo'], 'Culpa'),
            omp('Sentir estas emociones es…',       ['Normal en todos', 'Raro', 'Malo'], 'Normal en todos'),
        ]),

        est('¿Qué hago con eso?', 'La emoción no se elige; la reacción sí', '🧭', 'opcion_multiple', [
            omp('Siento envidia de un amigo. Lo útil es…', ['Preguntarme qué quiero yo y trabajarlo', 'Hablar mal de él', 'Alejarme'], 'Preguntarme qué quiero yo y trabajarlo'),
            omp('Siento vergüenza por un error. Lo útil es…', ['Recordar que a todos les pasa', 'Esconderme para siempre', 'Culpar a otro'], 'Recordar que a todos les pasa'),
            omp('Siento frustración. Lo útil es…',  ['Descansar y volver a intentar', 'Rendirme', 'Romper todo'], 'Descansar y volver a intentar'),
            omp('Siento culpa. Lo útil es…',        ['Reparar lo que pueda', 'Olvidarlo', 'Castigarme'], 'Reparar lo que pueda'),
            omp('Si una emoción no se va en días…', ['Hablo con alguien de confianza', 'Espero', 'La ignoro'], 'Hablo con alguien de confianza'),
        ]),

        est('Lo que dice el cuerpo', 'Cada emoción se nota en algún sitio', '🫀', 'emparejar', [
            ['e' => '😰', 'w' => 'Sudor y corazón rápido'],
            ['e' => '😳', 'w' => 'Cara caliente'],
            ['e' => '😖', 'w' => 'Nudo en el estómago'],
            ['e' => '😤', 'w' => 'Puños apretados'],
            ['e' => '😔', 'w' => 'Hombros caídos'],
            ['e' => '😌', 'w' => 'Respiración tranquila'],
        ]),

        est('Reto de las emociones', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Una emoción difícil es…',          ['Información, no un defecto', 'Un error', 'Algo que ocultar'], 'Información, no un defecto'),
            reto('La envidia avisa de…',             ['Algo que yo quiero', 'Que el otro es malo', 'Nada'], 'Algo que yo quiero'),
            reto('La frustración aparece cuando…',   ['Algo cuesta más de lo esperado', 'Todo sale bien', 'Descanso'], 'Algo cuesta más de lo esperado'),
            reto('Hablar de lo que siento con alguien…', ['Ayuda', 'Es de débiles', 'Empeora'], 'Ayuda'),
            reto('Lo que sí puedo elegir es…',       ['Qué hago con la emoción', 'No sentirla', 'Que no exista'], 'Qué hago con la emoción'),
        ]),
    ],
],

[
    'slug'  => 'la-palabra-que-repara',
    'title' => 'La palabra que repara',
    'description' => 'Pedir perdón bien no es decir «perdón»: es reconocer, reparar y cambiar.',
    'objective' => 'Reconocer los componentes de una disculpa efectiva y de la reparación del daño.',
    'icon' => '🤲', 'nivel' => 'primaria-superior', 'bloque' => 'resolver-conflictos',
    'duracion' => 13, 'tags' => ['convivencia', 'social', 'emociones'],
    'estaciones' => [

        est('Perdones que no reparan', 'Algunos empeoran las cosas', '🚫', 'opcion_multiple', [
            omp('«Perdón si te ofendiste» es…',      ['Culpar al otro', 'Una buena disculpa', 'Suficiente'], 'Culpar al otro'),
            omp('«Perdón, pero tú empezaste» es…',   ['Una excusa', 'Una disculpa', 'Justicia'], 'Una excusa'),
            omp('«Ya dije perdón, ¿qué más quieres?» es…', ['No reparar', 'Reparar', 'Generoso'], 'No reparar'),
            omp('Una disculpa buena reconoce…',      ['Lo que hice y el daño', 'Mis buenas intenciones', 'Lo que hizo el otro'], 'Lo que hice y el daño'),
            omp('Después de la disculpa hace falta…', ['Cambiar la conducta', 'Nada', 'Un regalo'], 'Cambiar la conducta'),
        ]),

        est('Ordena una disculpa de verdad', 'Tiene partes', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos de una disculpa que repara',
            'items' => ['Decir qué hice, sin excusas', 'Reconocer el daño que causó',
                        'Pedir perdón de verdad', 'Preguntar cómo puedo repararlo',
                        'Hacerlo', 'No repetirlo'],
        ]),

        est('Recibir una disculpa', 'También se aprende', '🤝', 'opcion_multiple', [
            omp('Si alguien se disculpa de verdad, puedo…', ['Aceptar o decir que necesito tiempo', 'Estar obligado a perdonar ya', 'Vengarme'], 'Aceptar o decir que necesito tiempo'),
            omp('Perdonar significa…',               ['Soltar el rencor', 'Olvidar que pasó', 'Aceptar que se repita'], 'Soltar el rencor'),
            omp('¿Estoy obligado a volver a ser amigo?', ['No', 'Sí', 'Siempre'], 'No'),
            omp('Si se repite lo mismo muchas veces…', ['La disculpa no valía', 'Hay que perdonar igual', 'Da igual'], 'La disculpa no valía'),
            omp('Reparar puede ser…',                ['Arreglar, devolver o acompañar', 'Solo hablar', 'Pagar siempre'], 'Arreglar, devolver o acompañar'),
        ]),

        est('Reto de reparar', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Lo esencial de una disculpa es…',  ['Reconocer el daño', 'La palabra perdón', 'La rapidez'], 'Reconocer el daño'),
            reto('«Perdón, pero…» suele…',           ['Anular la disculpa', 'Mejorarla', 'Aclararla'], 'Anular la disculpa'),
            reto('Reparar es…',                      ['Hacer algo concreto', 'Decir algo bonito', 'Esperar'], 'Hacer algo concreto'),
            reto('Quien recibe la disculpa…',        ['Decide cuándo perdona', 'Debe perdonar ya', 'No decide'], 'Decide cuándo perdona'),
            reto('La mejor prueba de una disculpa es…', ['Que no se repita', 'Que sea larga', 'Que haya testigos'], 'Que no se repita'),
        ]),
    ],
],

],
];
