<?php
/**
 * bienestar.php — Vida y Bienestar
 *
 * Se llama «Vida y Bienestar» y no «Salud» a propósito: para un niño de
 * cinco años «salud» suena a médico y a enfermedad, y esto trata de otra
 * cosa — su cuerpo, sus hábitos y su manera de cuidarse.
 *
 * Se cruza con «Valores y Convivencia» sin repetirla:
 *     Valores y Convivencia = mi relación con los demás
 *     Vida y Bienestar      = mi relación conmigo mismo y con mi entorno
 *
 * Nota de contenido: aquí no se dan consejos médicos ni se habla de
 * enfermedades o dietas. Se trabajan hábitos cotidianos y decisiones,
 * que es lo que corresponde a esta edad y lo único que un juego puede
 * enseñar con responsabilidad.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'bienestar',
    'name'       => 'Vida y Bienestar',
    'tagline'    => 'Mi cuerpo, mis hábitos y las decisiones que me cuidan',
    'icon'       => '💚',
    'color'      => '#66bb6a',
    'sort_order' => 12,
],

'bloques' => [
    ['slug' => 'mi-cuerpo', 'name' => 'Mi Cuerpo', 'icon' => '🧍', 'sort_order' => 1,
     'description' => 'Conocer el cuerpo, para qué sirve cada parte y qué necesita.'],
    ['slug' => 'habitos-saludables', 'name' => 'Hábitos Saludables', 'icon' => '🥗', 'sort_order' => 2,
     'description' => 'Comer, moverse, dormir y asearse: lo que se hace todos los días.'],
    ['slug' => 'decisiones-y-cuidado', 'name' => 'Decisiones y Cuidado', 'icon' => '🛡️', 'sort_order' => 3,
     'description' => 'Pensar antes de actuar, distinguir mitos y saber pedir ayuda.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE 1 · MI CUERPO (3 a 5 años)
// =====================================================================

[
    'slug'  => 'conoce-tu-cuerpo',
    'title' => 'Conoce tu cuerpo',
    'description' => 'Las partes del cuerpo, los cinco sentidos y para qué sirve cada uno.',
    'objective' => 'Nombrar las partes del cuerpo y relacionar cada sentido con su función.',
    'icon' => '🧍', 'nivel' => 'preescolar', 'bloque' => 'mi-cuerpo',
    'duracion' => 10, 'tags' => ['cuerpo', 'vocabulario', 'observacion'],
    'estaciones' => [

        est('Une cada parte', 'Empareja la imagen con su nombre', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'Ojos'],
            ['e' => '👂', 'w' => 'Orejas'],
            ['e' => '👃', 'w' => 'Nariz'],
            ['e' => '👄', 'w' => 'Boca'],
            ['e' => '✋', 'w' => 'Manos'],
            ['e' => '🦶', 'w' => 'Pies'],
        ]),

        est('¿Para qué sirve?', 'Cada parte tiene su trabajo', '🔧', 'opcion_multiple', [
            omp('¿Para qué sirven los ojos?',    ['Para ver', 'Para oír', 'Para correr'], 'Para ver', '👁️'),
            omp('¿Para qué sirven las orejas?',  ['Para oír', 'Para oler', 'Para saltar'], 'Para oír', '👂'),
            omp('¿Para qué sirve la nariz?',     ['Para oler', 'Para ver', 'Para escribir'], 'Para oler', '👃'),
            omp('¿Para qué sirven las piernas?', ['Para caminar', 'Para escuchar', 'Para masticar'], 'Para caminar', '🦵'),
            omp('¿Para qué sirven los dientes?', ['Para masticar', 'Para ver', 'Para oír'], 'Para masticar', '🦷'),
        ]),

        est('Los cinco sentidos', 'Con cada sentido descubro el mundo', '🖐️', 'opcion_multiple', [
            omp('¿Con qué sentido escucho la música?', ['El oído', 'La vista', 'El gusto'], 'El oído', '🎵'),
            omp('¿Con qué sentido saboreo la comida?', ['El gusto', 'El olfato', 'El tacto'], 'El gusto', '🍦'),
            omp('¿Con qué sentido siento si algo está frío?', ['El tacto', 'La vista', 'El oído'], 'El tacto', '🧊'),
            omp('¿Con qué sentido huelo una flor?',    ['El olfato', 'El gusto', 'El oído'], 'El olfato', '🌸'),
            omp('¿Cuántos sentidos tenemos?',          [3, 4, 5, 6], 5),
        ]),

        est('Memoria del cuerpo', 'Encuentra las parejas', '🧠', 'memoria',
            ['👁️', '👂', '👃', '👄', '✋', '🦶']),
    ],
],

[
    'slug'  => 'que-necesita-mi-cuerpo',
    'title' => '¿Qué necesita mi cuerpo?',
    'description' => 'Agua, comida, movimiento y descanso: lo que tu cuerpo pide cada día.',
    'objective' => 'Identificar las necesidades básicas del cuerpo y organizarlas en la rutina diaria.',
    'icon' => '🍎', 'nivel' => 'preescolar', 'bloque' => 'mi-cuerpo',
    'duracion' => 10, 'tags' => ['salud', 'clasificacion', 'autocuidado'],
    'estaciones' => [

        est('Lo que ayuda a tu cuerpo', 'Toca todo lo que le hace bien', '💚', 'seleccion_imagenes',
            conTitulo('Toca todo lo que le hace bien a tu cuerpo', 'Piensa antes de tocar', [
                ['e' => '💧', 'n' => 'Agua',      'ok' => true],
                ['e' => '🍎', 'n' => 'Fruta',     'ok' => true],
                ['e' => '😴', 'n' => 'Dormir',    'ok' => true],
                ['e' => '🏃', 'n' => 'Moverse',   'ok' => true],
                ['e' => '🥦', 'n' => 'Verduras',  'ok' => true],
                ['e' => '🍭', 'n' => 'Muchos dulces', 'ok' => false],
                ['e' => '📺', 'n' => 'Pantalla todo el día', 'ok' => false],
                ['e' => '🌙', 'n' => 'Trasnochar', 'ok' => false],
            ])),

        est('Lo que el cuerpo pide', 'Escucha lo que tu cuerpo te dice', '👂', 'opcion_multiple', [
            omp('Tengo sed. ¿Qué necesita mi cuerpo?',     ['Agua', 'Correr', 'Dormir'], 'Agua', '💧'),
            omp('Tengo sueño. ¿Qué necesita mi cuerpo?',   ['Descansar', 'Comer dulces', 'Gritar'], 'Descansar', '😴'),
            omp('Tengo hambre. ¿Qué necesita mi cuerpo?',  ['Comida', 'Televisión', 'Juguetes'], 'Comida', '🍽️'),
            omp('Estoy quieto todo el día. ¿Qué me falta?', ['Moverme', 'Dormir más', 'Comer más dulces'], 'Moverme', '🏃'),
            omp('Me duele algo. ¿Qué hago?',               ['Aviso a un adulto', 'No digo nada', 'Me escondo'], 'Aviso a un adulto', '🤕'),
        ]),

        est('Agua, sueño y movimiento', 'Los tres pilares del día', '⚖️', 'opcion_multiple', [
            omp('¿Cuántas horas debe dormir un niño más o menos?', ['3 horas', '6 horas', '10 horas'], '10 horas', '🛏️'),
            omp('¿Qué bebida es la mejor para la sed?',   ['Agua', 'Gaseosa', 'Jugo de caja'], 'Agua', '💧'),
            omp('¿Cuál es una buena forma de moverse?',   ['Jugar en el parque', 'Ver televisión', 'Estar sentado'], 'Jugar en el parque', '⚽'),
            omp('Antes de dormir es mejor…',              ['Apagar las pantallas', 'Ver videos', 'Comer dulces'], 'Apagar las pantallas', '📴'),
        ]),

        est('Mi día en orden', 'Ordena tu rutina', '🕐', 'ordenar_secuencia', [
            'title' => 'Ordena tu día, de la mañana a la noche',
            'items' => ['🌅 Despertar', '🥣 Desayunar', '🏫 Ir al colegio', '🍽️ Almorzar', '⚽ Jugar', '🛁 Bañarse', '😴 Dormir'],
        ]),
    ],
],

[
    'slug'  => 'buenos-habitos',
    'title' => 'Buenos hábitos',
    'description' => 'Lavarse las manos, cepillarse los dientes y otras cosas de todos los días.',
    'objective' => 'Interiorizar la secuencia de los hábitos de higiene y distinguirlos de los que no lo son.',
    'icon' => '🧼', 'nivel' => 'preescolar', 'bloque' => 'mi-cuerpo',
    'duracion' => 10, 'tags' => ['salud', 'autocuidado', 'secuencias'],
    'estaciones' => [

        est('Lávate las manos', 'Ordena los pasos', '🧼', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para lavarte las manos',
            'items' => ['💧 Mojar las manos', '🧼 Poner jabón', '👏 Frotar bien', '🚿 Enjuagar', '🧻 Secar'],
        ]),

        est('¿Es un buen hábito?', 'Responde rápido: sí o no', '⚡', 'juego_rapido',
            conTitulo('¿Es un buen hábito?', 'Responde rápido', [
                ['e' => '🧼', 'n' => 'Lavarse las manos antes de comer', 'ok' => true],
                ['e' => '🦷', 'n' => 'Cepillarse los dientes',           'ok' => true],
                ['e' => '💧', 'n' => 'Tomar agua',                       'ok' => true],
                ['e' => '😴', 'n' => 'Dormir temprano',                  'ok' => true],
                ['e' => '🍎', 'n' => 'Comer frutas',                     'ok' => true],
                ['e' => '🍬', 'n' => 'Comer dulces todo el día',         'ok' => false],
                ['e' => '🙈', 'n' => 'No bañarse',                       'ok' => false],
                ['e' => '📱', 'n' => 'Usar la pantalla hasta muy tarde', 'ok' => false],
                ['e' => '🤧', 'n' => 'Estornudar sin taparse',           'ok' => false],
                ['e' => '🥤', 'n' => 'Tomar gaseosa en vez de agua',     'ok' => false],
            ])),

        est('¿Cuándo lo hago?', 'Cada hábito tiene su momento', '🕐', 'opcion_multiple', [
            omp('¿Cuándo me lavo las manos?',      ['Antes de comer', 'Nunca', 'Solo el domingo'], 'Antes de comer', '🧼'),
            omp('¿Cuántas veces al día me cepillo los dientes?', ['Una vez a la semana', 'Tres veces al día', 'Nunca'], 'Tres veces al día', '🦷'),
            omp('¿Qué hago al estornudar?',        ['Me tapo con el codo', 'Estornudo a los demás', 'Nada'], 'Me tapo con el codo', '🤧'),
            omp('¿Cuándo me baño?',                ['Todos los días', 'Una vez al mes', 'Nunca'], 'Todos los días', '🛁'),
            omp('Después de ir al baño…',          ['Me lavo las manos', 'Salgo corriendo', 'Me toco la cara'], 'Me lavo las manos', '🚻'),
        ]),

        est('Cepíllate los dientes', 'Ordena los pasos', '🦷', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para cepillarte los dientes',
            'items' => ['🪥 Tomar el cepillo', '🧴 Poner la crema', '😬 Cepillar arriba y abajo', '💧 Enjuagar', '🪥 Guardar el cepillo'],
        ]),
    ],
],


// =====================================================================
//  BLOQUE 2 · HÁBITOS SALUDABLES (6 a 8 años)
// =====================================================================

[
    'slug'  => 'arma-un-plato-saludable',
    'title' => 'Arma un plato saludable',
    'description' => 'Los grupos de alimentos y cómo se combinan en un plato equilibrado.',
    'objective' => 'Clasificar alimentos por grupo y reconocer qué hace equilibrado un plato.',
    'icon' => '🍽️', 'nivel' => 'primaria-inicial', 'bloque' => 'habitos-saludables',
    'duracion' => 12, 'tags' => ['alimentacion', 'clasificacion', 'salud'],
    'estaciones' => [

        est('Los grupos de alimentos', 'Cada alimento pertenece a un grupo', '🗂️', 'opcion_multiple', [
            omp('¿A qué grupo pertenece?', ['Frutas', 'Lácteos', 'Cereales'], 'Frutas', '🍌'),
            omp('¿A qué grupo pertenece?', ['Verduras', 'Frutas', 'Carnes'], 'Verduras', '🥦'),
            omp('¿A qué grupo pertenece?', ['Lácteos', 'Cereales', 'Frutas'], 'Lácteos', '🥛'),
            omp('¿A qué grupo pertenece?', ['Cereales', 'Verduras', 'Lácteos'], 'Cereales', '🍚'),
            omp('¿A qué grupo pertenece?', ['Proteínas', 'Frutas', 'Verduras'], 'Proteínas', '🥚'),
            omp('¿A qué grupo pertenece?', ['Cereales', 'Lácteos', 'Proteínas'], 'Cereales', '🍞'),
        ]),

        est('Toca las verduras', 'Solo verduras, ninguna fruta', '🥬', 'seleccion_imagenes',
            conTitulo('Toca todas las verduras', 'Las frutas no cuentan aquí', [
                ['e' => '🥦', 'n' => 'Brócoli',   'ok' => true],
                ['e' => '🥕', 'n' => 'Zanahoria', 'ok' => true],
                ['e' => '🥬', 'n' => 'Lechuga',   'ok' => true],
                ['e' => '🍅', 'n' => 'Tomate',    'ok' => true],
                ['e' => '🌽', 'n' => 'Maíz',      'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana',   'ok' => false],
                ['e' => '🍌', 'n' => 'Banano',    'ok' => false],
                ['e' => '🍰', 'n' => 'Torta',     'ok' => false],
            ])),

        est('¿Qué le falta al plato?', 'Un plato completo tiene de todo', '🍽️', 'opcion_multiple', [
            omp('El plato tiene arroz y pollo. ¿Qué le falta?',   ['Verduras', 'Más arroz', 'Postre'], 'Verduras', '🍚'),
            omp('El plato tiene solo papas fritas. ¿Qué le falta?', ['Casi todo', 'Nada', 'Más sal'], 'Casi todo', '🍟'),
            omp('El desayuno tiene pan. ¿Qué le agregarías?',     ['Fruta y leche', 'Dulces', 'Gaseosa'], 'Fruta y leche', '🍞'),
            omp('¿Qué debería ocupar la mitad del plato?',        ['Frutas y verduras', 'Carne', 'Postre'], 'Frutas y verduras', '🥗'),
            omp('¿Con qué acompañar la comida?',                  ['Agua', 'Gaseosa', 'Bebida energética'], 'Agua', '💧'),
        ]),

        est('Une alimento y grupo', 'Cada uno a su familia', '🔗', 'emparejar', [
            ['e' => '🍎', 'w' => 'Frutas'],
            ['e' => '🥦', 'w' => 'Verduras'],
            ['e' => '🥛', 'w' => 'Lácteos'],
            ['e' => '🍚', 'w' => 'Cereales'],
            ['e' => '🍗', 'w' => 'Proteínas'],
            ['e' => '💧', 'w' => 'Agua'],
        ]),
    ],
],

[
    'slug'  => 'detectives-de-habitos',
    'title' => 'Detectives de hábitos',
    'description' => 'Observa el día de alguien y descubre qué le está haciendo falta.',
    'objective' => 'Analizar rutinas cotidianas y reconocer qué hábitos conviene ajustar.',
    'icon' => '🔍', 'nivel' => 'primaria-inicial', 'bloque' => 'habitos-saludables',
    'duracion' => 12, 'tags' => ['salud', 'observacion', 'reto'],
    'estaciones' => [

        est('Buen hábito o no', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es un hábito saludable?', 'Responde rápido', [
                ['e' => '🚶', 'n' => 'Caminar al colegio',            'ok' => true],
                ['e' => '🥗', 'n' => 'Comer ensalada',                'ok' => true],
                ['e' => '📚', 'n' => 'Leer antes de dormir',          'ok' => true],
                ['e' => '💧', 'n' => 'Llevar botella de agua',        'ok' => true],
                ['e' => '🛌', 'n' => 'Acostarse a la misma hora',     'ok' => true],
                ['e' => '🍔', 'n' => 'Comer fritos todos los días',   'ok' => false],
                ['e' => '🎮', 'n' => 'Jugar videojuegos seis horas seguidas', 'ok' => false],
                ['e' => '🥤', 'n' => 'Desayunar solo gaseosa',        'ok' => false],
                ['e' => '😪', 'n' => 'Dormir cuatro horas',           'ok' => false],
                ['e' => '🪑', 'n' => 'Pasar todo el día sentado',     'ok' => false],
            ])),

        est('El día de Ana', 'Lee y descubre qué le falta', '📖', 'cuento', [
            'slides' => [
                ['img' => '⏰', 'text' => 'Ana se levantó tardísimo. No alcanzó a desayunar y salió corriendo al colegio.'],
                ['img' => '😴', 'text' => 'En la primera clase le costó poner atención. Tenía sueño y le sonaba el estómago.'],
                ['img' => '🍟', 'text' => 'En el descanso compró papas fritas y una gaseosa. Fue lo único que comió hasta el almuerzo.'],
                ['img' => '📱', 'text' => 'Al llegar a casa se quedó cinco horas con el celular. No salió a jugar.'],
                ['img' => '🌙', 'text' => 'Se acostó a la medianoche viendo videos. Al otro día se volvió a levantar tarde.'],
            ],
            'qs' => [
                reto('¿Por qué a Ana le costó poner atención en clase?', ['Tenía sueño y hambre', 'La clase era aburrida', 'No tenía cuaderno'], 'Tenía sueño y hambre'),
                reto('¿Qué hábito le faltó en la mañana?', ['Desayunar', 'Bañarse', 'Estudiar'], 'Desayunar'),
                reto('¿Qué le sobró en la tarde?', ['Tiempo de pantalla', 'Tiempo de juego', 'Tiempo de comida'], 'Tiempo de pantalla'),
                reto('¿Por qué se repitió el problema al día siguiente?', ['Se acostó muy tarde otra vez', 'Cambió de colegio', 'Perdió el celular'], 'Se acostó muy tarde otra vez'),
                reto('¿Cuál sería el mejor primer cambio para Ana?', ['Acostarse más temprano', 'Comer más papas fritas', 'Dejar de ir al colegio'], 'Acostarse más temprano'),
            ],
        ]),

        est('¿Qué le aconsejarías?', 'Da un consejo razonable', '💡', 'opcion_multiple', [
            omp('Un amigo no desayuna nunca. Le dirías…',       ['Que desayune aunque sea algo pequeño', 'Que tampoco almuerce', 'Nada'], 'Que desayune aunque sea algo pequeño', '🥣'),
            omp('Una amiga pasa el día sentada. Le propondrías…', ['Salir a jugar o caminar', 'Sentarse más cómoda', 'Ver más televisión'], 'Salir a jugar o caminar', '🚶'),
            omp('Alguien toma gaseosa en cada comida. Le dirías…', ['Cambiar por agua casi siempre', 'Tomar el doble', 'Que no importa'], 'Cambiar por agua casi siempre', '💧'),
            omp('Un compañero se duerme en clase. Podría…',     ['Acostarse más temprano', 'Tomar café', 'Faltar al colegio'], 'Acostarse más temprano', '🛌'),
        ]),

        est('Reto de detectives', 'Cierra el caso', '🏆', 'desafio_final', [
            reto('Alguien dice que está sano porque «no se enferma». ¿Es suficiente?',
                 ['No: la salud también es cómo vives cada día', 'Sí, es suficiente', 'Solo importa el peso'],
                 'No: la salud también es cómo vives cada día'),
            reto('¿Qué cambio pequeño da más resultado con el tiempo?',
                 ['Uno que puedas repetir todos los días', 'Uno enorme una sola vez', 'Ninguno'],
                 'Uno que puedas repetir todos los días'),
            reto('El cuerpo pide agua cuando…',
                 ['Sientes sed y también antes', 'Solo cuando hace calor', 'Nunca'],
                 'Sientes sed y también antes'),
            reto('Dormir bien sirve para…',
                 ['Crecer, aprender y tener energía', 'Solo descansar los ojos', 'Nada importante'],
                 'Crecer, aprender y tener energía'),
        ]),
    ],
],

[
    'slug'  => 'cuida-tu-cuerpo',
    'title' => 'Cuida tu cuerpo',
    'description' => 'Higiene, seguridad y emociones: tres formas de cuidarte todos los días.',
    'objective' => 'Relacionar higiene, seguridad física y manejo emocional como parte del autocuidado.',
    'icon' => '🛡️', 'nivel' => 'primaria-inicial', 'bloque' => 'habitos-saludables',
    'duracion' => 12, 'tags' => ['autocuidado', 'seguridad', 'emociones'],
    'estaciones' => [

        est('Higiene todos los días', 'Lo que se hace sin falta', '🧼', 'opcion_multiple', [
            omp('¿Cada cuánto se cambia la ropa interior?', ['Todos los días', 'Una vez a la semana', 'Cuando se rompa'], 'Todos los días', '👕'),
            omp('¿Cuándo se cortan las uñas?',        ['Cuando están largas', 'Nunca', 'Una vez al año'], 'Cuando están largas', '💅'),
            omp('Antes de cocinar hay que…',          ['Lavarse las manos', 'Tocarse la cara', 'Nada'], 'Lavarse las manos', '👐'),
            omp('¿Qué se hace con el cepillo de dientes cuando está gastado?', ['Se cambia', 'Se sigue usando siempre', 'Se presta'], 'Se cambia', '🪥'),
            omp('¿Se puede compartir el cepillo de dientes?', ['No', 'Sí', 'Solo con amigos'], 'No', '🚫'),
        ]),

        est('Seguridad', 'Cuidarse también es prevenir', '🦺', 'opcion_multiple', [
            omp('En bicicleta hay que usar…',        ['Casco', 'Sombrero', 'Nada'], 'Casco', '🚲'),
            omp('En el carro hay que usar…',         ['Cinturón de seguridad', 'Los pies afuera', 'Nada'], 'Cinturón de seguridad', '🚗'),
            omp('Si un desconocido te ofrece algo…', ['Avisas a un adulto de confianza', 'Lo aceptas', 'Te vas con él'], 'Avisas a un adulto de confianza', '🛑'),
            omp('Antes de cruzar la calle…',        ['Miras a ambos lados', 'Corres sin mirar', 'Cierras los ojos'], 'Miras a ambos lados', '🚸'),
            omp('Si hueles a gas en la casa…',      ['Avisas a un adulto y no prendes nada', 'Prendes la luz', 'No haces nada'], 'Avisas a un adulto y no prendes nada', '⚠️'),
            omp('En la piscina hay que…',           ['Estar acompañado de un adulto', 'Bañarse solo', 'Correr en el borde'], 'Estar acompañado de un adulto', '🏊'),
        ]),

        est('Mis emociones', 'Sentirse mal también se cuida', '💛', 'opcion_multiple', [
            omp('Estoy muy bravo. Lo mejor es…',       ['Respirar y calmarme antes de actuar', 'Gritar a alguien', 'Romper algo'], 'Respirar y calmarme antes de actuar', '😤'),
            omp('Estoy triste hace días. Debería…',    ['Contarle a alguien de confianza', 'Guardármelo', 'Fingir que nada pasa'], 'Contarle a alguien de confianza', '😢'),
            omp('Estoy nervioso por un examen. Puedo…', ['Respirar hondo y prepararme', 'No presentarme', 'No dormir'], 'Respirar hondo y prepararme', '😰'),
            omp('¿Está bien sentir miedo a veces?',    ['Sí, es normal', 'No, nunca', 'Solo los bebés sienten miedo'], 'Sí, es normal', '😨'),
            omp('Un amigo está triste. Puedo…',        ['Escucharlo y acompañarlo', 'Burlarme', 'Ignorarlo'], 'Escucharlo y acompañarlo', '🤗'),
        ]),

        est('Palabras del bienestar', 'Encuentra las palabras escondidas', '🔍', 'sopa_letras',
            sopa(['AGUA', 'SUENO', 'FRUTA', 'DEPORTE', 'HIGIENE'], 11)),
    ],
],


[
    'slug'  => 'dormir-y-descansar',
    'title' => 'Dormir y descansar',
    'description' => 'El sueño no es tiempo perdido: es cuando el cuerpo crece y el cerebro ordena.',
    'objective' => 'Comprender la función del sueño y reconocer hábitos que lo favorecen o lo estropean.',
    'icon' => '😴', 'nivel' => 'primaria-inicial', 'bloque' => 'habitos-saludables',
    'duracion' => 12, 'tags' => ['salud', 'autocuidado', 'secuencias'],
    'estaciones' => [

        est('¿Para qué sirve dormir?', 'Mientras duermes pasan cosas', '🌙', 'opcion_multiple', [
            omp('Mientras duermes, tu cuerpo…',   ['Crece y se repara', 'Se detiene del todo', 'No hace nada'], 'Crece y se repara', '📏'),
            omp('Mientras duermes, tu cerebro…',  ['Ordena lo que aprendiste', 'Se apaga', 'Se borra'], 'Ordena lo que aprendiste', '🧠'),
            omp('Si duermo poco, al otro día…',   ['Me cuesta poner atención', 'Aprendo más', 'Nada cambia'], 'Me cuesta poner atención', '😪'),
            omp('Un niño de 8 años necesita dormir alrededor de…', ['5 horas', '7 horas', '10 horas'], '10 horas', '🛏️'),
            omp('Acostarse siempre a la misma hora…', ['Ayuda a dormir mejor', 'Da igual', 'Estorba'], 'Ayuda a dormir mejor', '⏰'),
        ]),

        est('¿Ayuda o estorba?', 'Antes de dormir', '⚡', 'juego_rapido',
            conTitulo('¿Esto ayuda a dormir bien?', 'Responde rápido', [
                ['e' => '📖', 'n' => 'Leer un cuento',              'ok' => true],
                ['e' => '🛁', 'n' => 'Bañarse con agua tibia',      'ok' => true],
                ['e' => '🌑', 'n' => 'Apagar las luces fuertes',    'ok' => true],
                ['e' => '🧘', 'n' => 'Respirar despacio',           'ok' => true],
                ['e' => '⏰', 'n' => 'Acostarse a la misma hora',   'ok' => true],
                ['e' => '📱', 'n' => 'Ver videos hasta tarde',      'ok' => false],
                ['e' => '🥤', 'n' => 'Tomar bebidas con cafeína',   'ok' => false],
                ['e' => '🍬', 'n' => 'Comer muchos dulces de noche', 'ok' => false],
                ['e' => '🎮', 'n' => 'Jugar algo muy emocionante',  'ok' => false],
                ['e' => '💡', 'n' => 'Dejar la luz encendida',      'ok' => false],
            ])),

        est('La rutina de la noche', 'Ordena cómo prepararte', '🌙', 'ordenar_secuencia', [
            'title' => 'Ordena la rutina para dormir bien',
            'items' => [
                '🍲 Cenar sin llenarse demasiado',
                '📴 Apagar las pantallas',
                '🛁 Bañarse',
                '🦷 Cepillarse los dientes',
                '📖 Leer un rato',
                '😴 Apagar la luz y dormir',
            ],
        ]),

        est('¿Cuánto descanso?', 'Descansar no es solo dormir', '🧘', 'opcion_multiple', [
            omp('Descansar también incluye…',   ['Pausas durante el día', 'Solo dormir de noche', 'Nada'], 'Pausas durante el día', '⏸️'),
            omp('Después de mucho rato estudiando conviene…', ['Levantarse y moverse un poco', 'Seguir sin parar', 'Comer dulces'], 'Levantarse y moverse un poco', '🚶'),
            omp('Estar cansado y no querer parar…', ['Hace rendir menos', 'Hace rendir más', 'Da igual'], 'Hace rendir menos', '📉'),
            omp('Si me despierto varias veces en la noche, conviene…', ['Contárselo a un adulto', 'No decir nada', 'Dormir menos'], 'Contárselo a un adulto', '🗣️'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE 3 · DECISIONES Y CUIDADO (9 a 12 años)
// =====================================================================

/*
 * Esta actividad trata una emergencia. Se escribió con un límite claro:
 * enseña a PEDIR AYUDA y a no empeorar la situación, nunca a atender a
 * nadie. Un niño de once años no debe mover a un herido ni decidir si
 * algo es grave, y una actividad que le diera esa idea sería peligrosa
 * aunque las preguntas estuvieran bien.
 */
[
    'slug'  => 'que-hago-si-pasa-algo',
    'title' => '¿Y si pasa algo?',
    'description' => 'Qué hacer y a quién llamar cuando ocurre un accidente cerca de ti.',
    'objective' => 'Saber pedir ayuda con calma en una emergencia y evitar acciones que empeoren la situación.',
    'icon' => '🚨', 'nivel' => 'primaria-media', 'bloque' => 'decisiones-y-cuidado',
    'duracion' => 12, 'tags' => ['seguridad', 'autocuidado', 'logica'],
    'estaciones' => [

        est('Pedir ayuda', 'Lo primero, siempre', '📞', 'opcion_multiple', [
            omp('¿Cuál es la línea de emergencias en Colombia?', ['123', '911', '119'], '123', '📞'),
            omp('Lo primero al ver un accidente es…',   ['Buscar a un adulto y llamar al 123', 'Grabar el video', 'Acercarme a tocar'], 'Buscar a un adulto y llamar al 123', '🚨'),
            omp('Al llamar, lo más importante es decir…', ['Dónde estoy y qué pasó', 'Mi color favorito', 'Nada'], 'Dónde estoy y qué pasó', '📍'),
            omp('Después de llamar, debo…',             ['Quedarme donde me digan y esperar', 'Colgar y correr', 'Llamar otra vez sin parar'], 'Quedarme donde me digan y esperar', '⏳'),
            omp('Si no sé la dirección exacta, digo…',  ['Alguna referencia cercana', 'Nada', 'Que no sé y cuelgo'], 'Alguna referencia cercana', '🏪'),
        ]),

        est('Qué NO hacer', 'A veces ayudar de más empeora', '🚫', 'opcion_multiple', [
            omp('Alguien se cayó y no se puede mover. Yo…', ['NO lo muevo y busco ayuda', 'Lo levanto', 'Lo cargo'], 'NO lo muevo y busco ayuda', '🚫'),
            omp('Hay un cable suelto en el agua. Yo…',   ['No me acerco y aviso', 'Lo quito', 'Lo piso'], 'No me acerco y aviso', '⚡'),
            omp('Huele a gas en la casa. Yo…',           ['No prendo nada y aviso', 'Prendo la luz para ver', 'Enciendo la estufa'], 'No prendo nada y aviso', '🔥'),
            omp('Un compañero se atoró comiendo. Yo…',   ['Llamo a un adulto de inmediato', 'Le doy agua sin avisar', 'Espero a ver'], 'Llamo a un adulto de inmediato', '🆘'),
            omp('Ante una emergencia, correr y gritar sin rumbo…', ['Estorba a quien puede ayudar', 'Ayuda', 'Da igual'], 'Estorba a quien puede ayudar', '🏃'),
        ]),

        est('Mantener la calma', 'Pensar es parte de ayudar', '🧘', 'ordenar_secuencia', [
            'title' => 'Ordena qué hacer al presenciar un accidente',
            'items' => [
                '1️⃣ Respirar y mirar si hay peligro para mí',
                '2️⃣ Llamar a un adulto o al 123',
                '3️⃣ Decir dónde estoy y qué pasó',
                '4️⃣ No mover a nadie',
                '5️⃣ Esperar y hacer lo que me indiquen',
            ],
        ]),

        est('Reto de emergencia', 'Decide bien y rápido', '🏆', 'desafio_final', [
            reto('Estás solo en casa y suena la alarma de humo. Lo primero es…',
                 ['Salir y pedir ayuda a un vecino', 'Buscar el fuego', 'Esconderse'],
                 'Salir y pedir ayuda a un vecino'),
            reto('Te quemaste levemente la mano cocinando. Lo primero es…',
                 ['Poner la mano bajo agua fría y avisar a un adulto', 'Poner hielo directo', 'Aguantar callado'],
                 'Poner la mano bajo agua fría y avisar a un adulto'),
            reto('Un adulto se desmayó frente a ti. Tú…',
                 ['Llamas al 123 y pides ayuda a alguien cercano', 'Lo sientas de golpe', 'Te vas'],
                 'Llamas al 123 y pides ayuda a alguien cercano'),
            reto('¿Por qué es importante saber tu dirección de memoria?',
                 ['Para poder pedir ayuda desde cualquier lugar', 'Para un examen', 'No es importante'],
                 'Para poder pedir ayuda desde cualquier lugar'),
        ]),
    ],
],

[
    'slug'  => 'mitos-sobre-la-alimentacion',
    'title' => 'Mitos sobre la alimentación',
    'description' => 'Muchas cosas que se repiten sobre la comida no son ciertas. Aprende a distinguirlas.',
    'objective' => 'Desarrollar pensamiento crítico frente a afirmaciones comunes sobre alimentación.',
    'icon' => '🧐', 'nivel' => 'primaria-media', 'bloque' => 'decisiones-y-cuidado',
    'duracion' => 15, 'tags' => ['alimentacion', 'logica', 'reto'],
    'estaciones' => [

        est('¿Mito o realidad?', 'Piensa antes de responder', '🤔', 'opcion_multiple', [
            omp('«Saltarse el desayuno ayuda a estar mejor».',      ['Mito', 'Realidad'], 'Mito', '🥣'),
            omp('«El agua es la mejor bebida para la sed».',        ['Realidad', 'Mito'], 'Realidad', '💧'),
            omp('«Todo lo que dice "natural" en el empaque es saludable».', ['Mito', 'Realidad'], 'Mito', '🏷️'),
            omp('«Las frutas enteras alimentan más que el jugo colado».', ['Realidad', 'Mito'], 'Realidad', '🍊'),
            omp('«Comer de noche engorda automáticamente».',        ['Mito', 'Realidad'], 'Mito', '🌙'),
            omp('«Un solo alimento puede curarlo todo».',           ['Mito', 'Realidad'], 'Mito', '🥑'),
        ]),

        est('Lee la etiqueta', 'Los empaques dicen más de lo que parece', '🏷️', 'opcion_multiple', [
            omp('El primer ingrediente de la lista es…',      ['El que más cantidad tiene', 'El más caro', 'El más sano'], 'El que más cantidad tiene', '📋'),
            omp('Si el primer ingrediente es azúcar, el producto…', ['Tiene mucha azúcar', 'Es saludable', 'No tiene azúcar'], 'Tiene mucha azúcar', '🍬'),
            omp('Un sello de advertencia en el empaque sirve para…', ['Avisar que tiene exceso de algo', 'Decorar', 'Indicar el precio'], 'Avisar que tiene exceso de algo', '⚠️'),
            omp('«Light» significa que el producto…',        ['Tiene menos de algo, no que sea sano', 'Es siempre saludable', 'No tiene calorías'], 'Tiene menos de algo, no que sea sano', '🪶'),
            omp('La información más confiable de un empaque está en…', ['La tabla nutricional', 'El dibujo del frente', 'El eslogan'], 'La tabla nutricional', '🔢'),
        ]),

        est('Publicidad y comida', 'No todo lo que anuncian te conviene', '📺', 'opcion_multiple', [
            omp('Un comercial muestra un cereal con un personaje divertido. Eso demuestra que…', ['Nada sobre si es saludable', 'Que es muy sano', 'Que es el mejor'], 'Nada sobre si es saludable', '🎬'),
            omp('¿A quién le conviene que compres ese producto?', ['A quien lo vende', 'A ti siempre', 'A nadie'], 'A quien lo vende', '💰'),
            omp('Para saber si algo es saludable es mejor…', ['Leer la etiqueta', 'Creer al comercial', 'Ver el color del empaque'], 'Leer la etiqueta', '🔍'),
            omp('Si un influencer recomienda un producto…', ['Puede estar pagado por la marca', 'Siempre dice la verdad', 'Es un experto'], 'Puede estar pagado por la marca', '📱'),
        ]),

        est('Reto crítico', 'Demuestra lo que aprendiste', '🏆', 'desafio_final', [
            reto('¿Cuál afirmación está mejor sustentada?',
                 ['«Comer variado ayuda a tener todos los nutrientes»', '«Un solo alimento cura todo»', '«Lo caro es lo más sano»'],
                 '«Comer variado ayuda a tener todos los nutrientes»'),
            reto('Escuchas algo sobre alimentación en internet. Lo primero es…',
                 ['Verificar quién lo dice y con qué evidencia', 'Compartirlo enseguida', 'Creerlo'],
                 'Verificar quién lo dice y con qué evidencia'),
            reto('¿Quién es la fuente más confiable sobre tu alimentación?',
                 ['Un profesional de la salud', 'Un video viral', 'Un comercial'],
                 'Un profesional de la salud'),
            reto('Dos fuentes dicen cosas opuestas. Lo correcto es…',
                 ['Buscar más información y consultar a un adulto', 'Elegir la más divertida', 'Ignorar el tema'],
                 'Buscar más información y consultar a un adulto'),
        ]),
    ],
],

[
    'slug'  => 'que-harias-en-esta-situacion',
    'title' => '¿Qué harías en esta situación?',
    'description' => 'Situaciones reales donde hay que pensar antes de actuar y saber pedir ayuda.',
    'objective' => 'Tomar decisiones responsables en situaciones cotidianas y reconocer cuándo pedir ayuda.',
    'icon' => '🤔', 'nivel' => 'primaria-media', 'bloque' => 'decisiones-y-cuidado',
    'duracion' => 15, 'tags' => ['autocuidado', 'seguridad', 'emociones', 'reto'],
    'estaciones' => [

        est('La tarde de Martín', 'Lee y piensa qué harías tú', '📖', 'cuento', [
            'slides' => [
                ['img' => '🚲', 'text' => 'Martín salió en bicicleta con dos amigos. Solo él llevaba casco.'],
                ['img' => '😅', 'text' => 'Sus amigos se rieron y le dijeron que se lo quitara, que así se veía mejor.'],
                ['img' => '🤔', 'text' => 'Martín pensó un momento. Se sintió incómodo, pero se dejó el casco puesto.'],
                ['img' => '🕳️', 'text' => 'Más adelante uno de sus amigos se cayó en un hueco y se golpeó la cabeza.'],
                ['img' => '📞', 'text' => 'Martín no salió corriendo ni se quedó paralizado: llamó a un adulto de inmediato.'],
            ],
            'qs' => [
                reto('¿Por qué los amigos querían que Martín se quitara el casco?', ['Por cómo se veía', 'Porque pesaba', 'Porque estaba roto'], 'Por cómo se veía'),
                reto('¿Qué hizo Martín ante la burla?', ['Mantuvo su decisión', 'Se quitó el casco', 'Se fue a su casa'], 'Mantuvo su decisión'),
                reto('¿Qué hizo cuando su amigo se cayó?', ['Llamó a un adulto', 'Siguió pedaleando', 'Se escondió'], 'Llamó a un adulto'),
                reto('¿Qué aprendemos de la decisión de Martín?', ['Cuidarse vale más que la burla', 'Hay que hacer lo que digan los amigos', 'El casco no sirve'], 'Cuidarse vale más que la burla'),
            ],
        ]),

        est('Decisiones del día', 'Elige lo más sensato', '⚖️', 'opcion_multiple', [
            omp('Te ofrecen algo de comer que no conoces y estás solo. Lo mejor es…', ['Preguntar a un adulto de confianza', 'Comerlo sin más', 'Guardarlo'], 'Preguntar a un adulto de confianza', '🤨'),
            omp('Tus amigos quieren que hagas algo que sabes que es peligroso. Puedes…', ['Decir que no y proponer otra cosa', 'Hacerlo para que no se burlen', 'Callarte y hacerlo'], 'Decir que no y proponer otra cosa', '🙅'),
            omp('Te sientes mal desde hace varios días. Deberías…', ['Contarle a un adulto', 'Esperar a que pase solo', 'No decir nada'], 'Contarle a un adulto', '🤒'),
            omp('Ves a alguien lastimado en la calle. Lo primero es…', ['Buscar ayuda de un adulto', 'Moverlo tú mismo', 'Seguir de largo'], 'Buscar ayuda de un adulto', '🚑'),
            omp('Estás en una discusión que se está calentando. Puedes…', ['Alejarte y calmarte', 'Gritar más fuerte', 'Empujar'], 'Alejarte y calmarte', '😮‍💨'),
        ]),

        est('Cuidarse en internet', 'También hay que cuidarse en la pantalla', '💻', 'opcion_multiple', [
            omp('Alguien que no conoces te escribe y pide tu dirección. Debes…', ['No responder y avisar a un adulto', 'Dársela', 'Responder con otra cosa'], 'No responder y avisar a un adulto', '🛑'),
            omp('¿Se pueden publicar fotos de otras personas sin permiso?', ['No', 'Sí', 'Solo si son graciosas'], 'No', '📷'),
            omp('Recibes un mensaje que te hace sentir mal. Lo mejor es…', ['Guardarlo y mostrárselo a un adulto', 'Responder con otro insulto', 'Borrarlo y callar'], 'Guardarlo y mostrárselo a un adulto', '💬'),
            omp('Tu contraseña se comparte con…', ['Nadie, salvo un adulto responsable', 'Tus amigos', 'Quien la pida'], 'Nadie, salvo un adulto responsable', '🔑'),
            omp('Un juego pide muchos datos personales. Lo correcto es…', ['Consultar con un adulto antes', 'Dárselos todos', 'Inventar y seguir'], 'Consultar con un adulto antes', '🎮'),
        ]),

        est('Reto de decisiones', 'Última prueba', '🏆', 'desafio_final', [
            reto('Pedir ayuda cuando algo te supera es señal de…',
                 ['Buen juicio', 'Debilidad', 'Miedo'], 'Buen juicio'),
            reto('¿Quién es un adulto de confianza?',
                 ['Alguien que te cuida y en quien confías', 'Cualquier persona mayor', 'Alguien de internet'],
                 'Alguien que te cuida y en quien confías'),
            reto('Antes de una decisión importante conviene…',
                 ['Pensar en las consecuencias', 'Decidir rápido', 'Hacer lo que hagan los demás'],
                 'Pensar en las consecuencias'),
            reto('Si te equivocaste en una decisión, lo mejor es…',
                 ['Contarlo y corregir', 'Ocultarlo', 'Culpar a otro'], 'Contarlo y corregir'),
            reto('Cuidarte a ti mismo también incluye…',
                 ['Cuidar tus emociones', 'Solo el cuerpo', 'Solo la comida'], 'Cuidar tus emociones'),
        ]),
    ],
],

],
];
