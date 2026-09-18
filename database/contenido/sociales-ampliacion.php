<?php
/**
 * sociales-ampliacion.php — Sociales en preescolar y en sexto
 *
 * Cuatro actividades de preescolar y seis de quinto y sexto. En la
 * primera punta falta lo más básico —quién soy, dónde vivo, quién me
 * cuida— y en la última falta lo que ya se puede entender de verdad:
 * cómo se organiza un país y de dónde salen las cosas.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'sociales',
    'name'       => 'Ciencias Sociales',
    'tagline'    => 'Mi familia, mi país y el mundo que compartimos',
    'icon'       => '🌎',
    'color'      => '#5c6bc0',
    'sort_order' => 9,
],

'bloques' => [
    ['slug' => 'mi-mundo-cercano', 'name' => 'Mi Mundo Cercano', 'icon' => '🏠', 'sort_order' => 1,
     'description' => 'La familia, la casa y el barrio: lo primero que un niño reconoce como suyo.'],
    ['slug' => 'conoce-colombia', 'name' => 'Conoce Colombia', 'icon' => '🇨🇴', 'sort_order' => 2,
     'description' => 'Regiones, mapas, comidas y personajes del país.'],
    ['slug' => 'democracia-y-derechos', 'name' => 'Democracia y Derechos', 'icon' => '🏛️', 'sort_order' => 5,
     'description' => 'Normas, gobierno escolar, derechos y deberes, y por qué vivir juntos exige acuerdos.'],
    ['slug' => 'economia-y-sociedad', 'name' => 'Economía y Sociedad', 'icon' => '💼', 'sort_order' => 7,
     'description' => 'De dónde salen las cosas que usamos y cómo se organiza el trabajo de un país.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR
// =====================================================================

[
    'slug'  => 'mi-familia-y-yo',
    'title' => 'Mi familia y yo',
    'description' => 'Quién vive conmigo, cómo se llaman y qué hacemos juntos.',
    'objective' => 'Reconocer los miembros de la familia y los vínculos de parentesco básicos.',
    'icon' => '👪', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 8, 'tags' => ['familia', 'vocabulario', 'convivencia'],
    'estaciones' => [

        est('¿Quién es quién?', 'Los nombres de la familia', '👪', 'opcion_multiple', [
            omp('La mamá de mi mamá es mi…',   ['Abuela', 'Tía', 'Prima'], 'Abuela', '👵'),
            omp('El papá de mi papá es mi…',   ['Abuelo', 'Tío', 'Hermano'], 'Abuelo', '👴'),
            omp('El hijo de mi tía es mi…',    ['Primo', 'Hermano', 'Abuelo'], 'Primo'),
            omp('El hermano de mi mamá es mi…', ['Tío', 'Primo', 'Abuelo'], 'Tío'),
            omp('Los que viven conmigo son mi…', ['Familia', 'Clase', 'Equipo'], 'Familia'),
        ]),

        est('Hay muchas familias', 'Y todas son familias', '🌈', 'opcion_multiple', [
            omp('¿Todas las familias son iguales?',  ['No', 'Sí', 'Casi'], 'No'),
            omp('Una familia puede ser de…',         ['Muchos tamaños', 'Solo cuatro', 'Solo tres'], 'Muchos tamaños'),
            omp('¿Un niño criado por sus abuelos tiene familia?', ['Sí', 'No', 'A medias'], 'Sí'),
            omp('Lo importante de una familia es…',  ['Que se cuiden', 'Que sean muchos', 'Que sean iguales'], 'Que se cuiden'),
            omp('Si una familia es distinta a la mía…', ['Está bien', 'Está mal', 'Hay que cambiarla'], 'Está bien'),
        ]),

        est('Lo que hacemos juntos', 'La vida en casa', '🏠', 'opcion_multiple', [
            omp('¿Quién ayuda en casa?',      ['Todos', 'Solo la mamá', 'Nadie'], 'Todos'),
            omp('Una tarea que puedo hacer yo es…', ['Guardar mis juguetes', 'Cocinar solo', 'Manejar'], 'Guardar mis juguetes'),
            omp('Si alguien de mi casa está triste…', ['Lo acompaño', 'Me alejo', 'Me río'], 'Lo acompaño'),
            omp('Comer juntos sirve para…',   ['Compartir y hablar', 'Nada', 'Ir rápido'], 'Compartir y hablar'),
            omp('Decir «gracias» y «por favor» es…', ['Tratar bien a los demás', 'Perder tiempo', 'De grandes'], 'Tratar bien a los demás'),
        ]),

        est('Memoria de la familia', 'Encuentra las parejas', '🧠', 'memoria',
            ['👶', '🧒', '👩', '👨', '👵', '👴']),
    ],
],

[
    'slug'  => 'donde-vivo',
    'title' => 'Dónde vivo',
    'description' => 'Mi casa, mi calle, mi barrio: lo que hay alrededor y para qué sirve.',
    'objective' => 'Reconocer lugares del entorno próximo y su función social.',
    'icon' => '🛣️', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 8, 'tags' => ['observacion', 'vocabulario', 'geografia'],
    'estaciones' => [

        est('¿Para qué sirve ese lugar?', 'Cada sitio hace algo', '🏥', 'opcion_multiple', [
            omp('Al colegio voy a…',       ['Aprender', 'Dormir', 'Comprar'], 'Aprender', '🏫'),
            omp('Al hospital voy si…',     ['Estoy enfermo', 'Tengo hambre', 'Quiero jugar'], 'Estoy enfermo', '🏥'),
            omp('A la tienda voy a…',      ['Comprar', 'Estudiar', 'Nadar'], 'Comprar', '🏪'),
            omp('Al parque voy a…',        ['Jugar', 'Comprar', 'Curarme'], 'Jugar', '🛝'),
            omp('En la biblioteca hay…',   ['Libros', 'Comida', 'Carros'], 'Libros', '📚'),
        ]),

        est('Quién trabaja ahí', 'Cada oficio en su lugar', '👷', 'emparejar', [
            ['e' => '🏥', 'w' => 'Médico'],
            ['e' => '🏫', 'w' => 'Profesor'],
            ['e' => '🚒', 'w' => 'Bombero'],
            ['e' => '🏪', 'w' => 'Tendero'],
            ['e' => '🚌', 'w' => 'Conductor'],
            ['e' => '🌾', 'w' => 'Campesino'],
        ]),

        est('Cuidar mi barrio', 'Es de todos', '🧹', 'opcion_multiple', [
            omp('La basura va…',              ['A la caneca', 'Al piso', 'Al río'], 'A la caneca', '🗑️'),
            omp('En el parque conviene…',     ['Cuidar las plantas', 'Romper ramas', 'Rayar todo'], 'Cuidar las plantas'),
            omp('Si veo algo roto en la calle…', ['Aviso a un adulto', 'Lo rompo más', 'Nada'], 'Aviso a un adulto'),
            omp('Saludar a los vecinos es…',  ['Tratar bien', 'Innecesario', 'Molestar'], 'Tratar bien'),
            omp('Los ruidos muy fuertes de noche…', ['Molestan a los vecinos', 'Están bien', 'Ayudan'], 'Molestan a los vecinos'),
        ]),

        est('Todo lo que hay en mi barrio', 'Búscalo', '🔍', 'seleccion_imagenes',
            conTitulo('Toca los lugares del barrio', 'Lo que no es un lugar, no', [
                ['e' => '🏫', 'n' => 'Colegio',  'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana',  'ok' => false],
                ['e' => '🏥', 'n' => 'Hospital', 'ok' => true],
                ['e' => '🐶', 'n' => 'Perro',    'ok' => false],
                ['e' => '🏪', 'n' => 'Tienda',   'ok' => true],
                ['e' => '☀️', 'n' => 'Sol',      'ok' => false],
                ['e' => '⛪', 'n' => 'Iglesia',  'ok' => true],
                ['e' => '🛝', 'n' => 'Parque',   'ok' => true],
            ])),
    ],
],

[
    'slug'  => 'mi-pais-se-llama-colombia',
    'title' => 'Mi país se llama Colombia',
    'description' => 'La bandera, el nombre y algunas cosas que nos hacen ser de aquí.',
    'objective' => 'Reconocer símbolos patrios y elementos de identidad nacional.',
    'icon' => '🇨🇴', 'nivel' => 'preescolar', 'bloque' => 'conoce-colombia',
    'duracion' => 8, 'tags' => ['colombia', 'cultura', 'observacion'],
    'estaciones' => [

        est('Mi bandera', 'Tres colores', '🇨🇴', 'opcion_multiple', [
            omp('La bandera de Colombia tiene…', ['Tres colores', 'Un color', 'Diez colores'], 'Tres colores', '🇨🇴'),
            omp('El color de arriba es…',        ['Amarillo', 'Rojo', 'Verde'], 'Amarillo'),
            omp('El del medio es…',              ['Azul', 'Verde', 'Blanco'], 'Azul'),
            omp('El de abajo es…',               ['Rojo', 'Negro', 'Morado'], 'Rojo'),
            omp('Mi país se llama…',             ['Colombia', 'Colonia', 'Caribe'], 'Colombia'),
        ]),

        est('Cosas de Colombia', 'Lo que se ve por aquí', '☕', 'opcion_multiple', [
            omp('Una bebida muy de Colombia es el…', ['Café', 'Té verde', 'Sake'], 'Café', '☕'),
            omp('Una fruta muy de aquí es…',     ['El banano', 'El kiwi', 'El dátil'], 'El banano', '🍌'),
            omp('Un animal de nuestros montes es el…', ['Cóndor', 'Pingüino', 'Oso polar'], 'Cóndor', '🦅'),
            omp('Una comida típica es la…',      ['Arepa', 'Pizza', 'Hamburguesa'], 'Arepa'),
            omp('La capital de Colombia es…',    ['Bogotá', 'Lima', 'Quito'], 'Bogotá'),
        ]),

        est('Paisajes de mi país', 'Hay de todo', '🏞️', 'emparejar', [
            ['e' => '🏔️', 'w' => 'Montaña'],
            ['e' => '🌊', 'w' => 'Mar'],
            ['e' => '🌴', 'w' => 'Selva'],
            ['e' => '🏜️', 'w' => 'Desierto'],
            ['e' => '🏞️', 'w' => 'Río'],
            ['e' => '🌾', 'w' => 'Llanura'],
        ]),

        est('Somos muchos y distintos', 'Y todos de aquí', '🌈', 'opcion_multiple', [
            omp('En Colombia la gente habla…',   ['Español, y también lenguas indígenas', 'Solo inglés', 'Un solo idioma en todo'], 'Español, y también lenguas indígenas'),
            omp('¿Todos en Colombia comen lo mismo?', ['No, cambia por región', 'Sí', 'Solo arepa'], 'No, cambia por región'),
            omp('La música cambia…',             ['De una región a otra', 'Nunca', 'Solo en la radio'], 'De una región a otra'),
            omp('Ser de aquí y ser distinto…',   ['Se puede a la vez', 'Es imposible', 'Está mal'], 'Se puede a la vez'),
            omp('Las personas indígenas son…',   ['Colombianas también', 'De otro país', 'Del pasado'], 'Colombianas también'),
        ]),
    ],
],

[
    // `antes-ahora-y-despues` ya existe y es de primero. Esta va antes.
    'slug'  => 'ayer-hoy-y-manana',
    'title' => 'Ayer, hoy y mañana',
    'description' => 'Ayer, hoy y mañana: las primeras nociones de tiempo.',
    'objective' => 'Ordenar hechos en el tiempo usando nociones temporales básicas.',
    'icon' => '⏳', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 8, 'tags' => ['secuencias', 'logica', 'vocabulario'],
    'estaciones' => [

        est('¿Antes o después?', 'Piensa el orden', '⏳', 'opcion_multiple', [
            omp('¿Qué pasa antes: nacer o crecer?',  ['Nacer', 'Crecer'], 'Nacer', '👶'),
            omp('¿Qué pasa antes: desayunar o almorzar?', ['Desayunar', 'Almorzar'], 'Desayunar'),
            omp('¿Qué pasa después del lunes?',      ['Martes', 'Domingo', 'Sábado'], 'Martes'),
            omp('¿Qué pasa antes: sembrar o cosechar?', ['Sembrar', 'Cosechar'], 'Sembrar', '🌱'),
            omp('Lo que pasó ayer es…',              ['Pasado', 'Futuro', 'Presente'], 'Pasado'),
        ]),

        est('Cómo crecemos', 'Ordena las etapas', '👶', 'ordenar_secuencia', [
            'title' => 'Ordena cómo crece una persona',
            'items' => ['Bebé', 'Niño', 'Joven', 'Adulto', 'Anciano'],
        ]),

        est('Los días de la semana', 'Siempre en el mismo orden', '📅', 'ordenar_secuencia', [
            'title' => 'Ordena los días de la semana',
            'items' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
        ]),

        est('Antes no había', 'Las cosas cambian con el tiempo', '📻', 'opcion_multiple', [
            omp('Hace mucho, para viajar lejos se usaba…', ['Caballo o barco', 'Avión', 'Metro'], 'Caballo o barco', '🐴'),
            omp('Antes no había…',              ['Celulares', 'Agua', 'Sol'], 'Celulares', '📱'),
            omp('Mis abuelos de niños jugaban…', ['Con otras cosas', 'Con lo mismo', 'Con nada'], 'Con otras cosas'),
            omp('Las fotos antiguas suelen ser…', ['En blanco y negro', 'En colores', 'Movidas'], 'En blanco y negro', '📷'),
            omp('Saber cómo era antes sirve para…', ['Entender cómo llegamos aquí', 'Nada', 'Copiar'], 'Entender cómo llegamos aquí'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR
// =====================================================================

[
    'slug'  => 'como-se-organiza-un-pais',
    'title' => 'Cómo se organiza un país',
    'description' => 'Tres poderes, un alcalde, un gobernador: quién decide qué.',
    'objective' => 'Reconocer la división de poderes y los niveles de gobierno en Colombia.',
    'icon' => '🏛️', 'nivel' => 'primaria-superior', 'bloque' => 'democracia-y-derechos',
    'duracion' => 15, 'tags' => ['ciudadania', 'colombia', 'logica'],
    'estaciones' => [

        est('Los tres poderes', 'Para que ninguno pueda todo', '⚖️', 'opcion_multiple', [
            omp('El poder que hace las leyes es el…',    ['Legislativo', 'Ejecutivo', 'Judicial'], 'Legislativo'),
            omp('El que gobierna y ejecuta es el…',      ['Ejecutivo', 'Legislativo', 'Judicial'], 'Ejecutivo'),
            omp('El que juzga si se cumplieron es el…',  ['Judicial', 'Ejecutivo', 'Legislativo'], 'Judicial'),
            omp('¿Por qué están separados?',             ['Para que ninguno tenga todo el poder', 'Por costumbre', 'Por el dinero'], 'Para que ninguno tenga todo el poder'),
            omp('En Colombia, el Congreso pertenece al poder…', ['Legislativo', 'Judicial', 'Ejecutivo'], 'Legislativo'),
        ]),

        est('Quién manda dónde', 'Municipio, departamento, país', '🗺️', 'opcion_multiple', [
            omp('Al frente de un municipio está el…',      ['Alcalde', 'Gobernador', 'Presidente'], 'Alcalde'),
            omp('Al frente de un departamento está el…',   ['Gobernador', 'Alcalde', 'Juez'], 'Gobernador'),
            omp('Al frente del país está el…',             ['Presidente', 'Alcalde', 'Gobernador'], 'Presidente'),
            omp('Arreglar un parque del barrio es cosa del…', ['Municipio', 'País entero', 'Nadie'], 'Municipio'),
            omp('A alcaldes y gobernadores los elige…',    ['La gente votando', 'El presidente', 'El Congreso'], 'La gente votando'),
        ]),

        est('La Constitución', 'La norma que está por encima', '📜', 'opcion_multiple', [
            omp('La Constitución colombiana actual es de…', ['1991', '1810', '1886'], '1991'),
            omp('La Constitución es…',                ['La norma más importante', 'Una ley más', 'Un libro de historia'], 'La norma más importante'),
            omp('Si una ley contradice la Constitución…', ['No vale', 'Vale igual', 'Se cambia la Constitución'], 'No vale'),
            omp('La tutela sirve para…',              ['Proteger un derecho rápido', 'Cobrar', 'Votar'], 'Proteger un derecho rápido'),
            omp('Los derechos fundamentales son de…', ['Todas las personas', 'Solo los adultos', 'Solo los ciudadanos ricos'], 'Todas las personas'),
        ]),

        est('Reto de la organización del país', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Hacer leyes le toca al…',       ['Legislativo', 'Judicial', 'Ejecutivo'], 'Legislativo'),
            reto('El alcalde gobierna…',          ['Un municipio', 'Un país', 'Un continente'], 'Un municipio'),
            reto('La separación de poderes evita…', ['Que uno solo tenga todo el poder', 'Que se pierda tiempo', 'Los impuestos'], 'Que uno solo tenga todo el poder'),
            reto('La Constitución de 1991 introdujo…', ['La tutela', 'El voto', 'El Congreso'], 'La tutela'),
            reto('Votar es…',                     ['Un derecho y un deber', 'Solo un derecho', 'Una obligación con multa'], 'Un derecho y un deber'),
        ]),
    ],
],

[
    'slug'  => 'de-donde-sale-lo-que-uso',
    'title' => 'De dónde sale lo que uso',
    'description' => 'De la materia prima al producto: quién trabaja en cada paso.',
    'objective' => 'Reconocer los sectores económicos siguiendo la cadena de un producto.',
    'icon' => '🚚', 'nivel' => 'primaria-superior', 'bloque' => 'economia-y-sociedad',
    'duracion' => 15, 'tags' => ['oficios', 'logica', 'secuencias'],
    'estaciones' => [

        est('Los tres sectores', 'Sacar, transformar, servir', '🏭', 'opcion_multiple', [
            omp('Cultivar café pertenece al sector…',   ['Primario', 'Secundario', 'Terciario'], 'Primario'),
            omp('Tostar y empacar el café es sector…',  ['Secundario', 'Primario', 'Terciario'], 'Secundario'),
            omp('Venderlo en una cafetería es sector…', ['Terciario', 'Primario', 'Secundario'], 'Terciario'),
            omp('La minería es del sector…',            ['Primario', 'Terciario', 'Secundario'], 'Primario'),
            omp('Un profesor trabaja en el sector…',    ['Terciario', 'Primario', 'Secundario'], 'Terciario'),
        ]),

        est('La cadena de una camiseta', 'Ordena los pasos', '👕', 'ordenar_secuencia', [
            'title' => 'Ordena cómo llega una camiseta a la tienda',
            'items' => ['Cultivar el algodón', 'Hilar y tejer la tela',
                        'Cortar y coser la camiseta', 'Transportarla',
                        'Ponerla en la tienda', 'Venderla'],
        ]),

        est('El precio de las cosas', 'Por qué cuesta lo que cuesta', '💰', 'opcion_multiple', [
            omp('En el precio está incluido…',      ['El trabajo de mucha gente', 'Solo el material', 'Nada'], 'El trabajo de mucha gente'),
            omp('Si hay poco de algo y mucha gente lo quiere, el precio…', ['Sube', 'Baja', 'Se queda'], 'Sube'),
            omp('Si hay muchísimo de algo, el precio suele…', ['Bajar', 'Subir', 'Desaparecer'], 'Bajar'),
            omp('Un producto traído de lejos suele costar más por…', ['El transporte', 'El color', 'La marca solamente'], 'El transporte'),
            omp('Comprar a un productor local ayuda a…', ['La economía cercana', 'Nadie', 'Subir precios'], 'La economía cercana'),
        ]),

        est('Trabajo y derechos', 'No todo trabajo es igual', '⚖️', 'opcion_multiple', [
            omp('El trabajo infantil es…',      ['Algo que hay que evitar', 'Bueno para aprender', 'Obligatorio'], 'Algo que hay que evitar'),
            omp('Un trabajador tiene derecho a…', ['Descanso y pago justo', 'Nada', 'Solo pago'], 'Descanso y pago justo'),
            omp('El trabajo del hogar…',        ['Es trabajo, aunque no se pague', 'No es trabajo', 'Solo si se cobra'], 'Es trabajo, aunque no se pague'),
            omp('Ahorrar sirve para…',          ['Poder afrontar imprevistos', 'Nada', 'Gastar más rápido'], 'Poder afrontar imprevistos'),
            omp('Un presupuesto es…',           ['Un plan de lo que entra y sale', 'Un impuesto', 'Un préstamo'], 'Un plan de lo que entra y sale'),
        ]),
    ],
],

],
];
