<?php
/**
 * sociales.php — Ciencias Sociales
 *
 * Historia, geografía, cultura y sociedad, de 3 a 12 años.
 *
 * El recorrido va de lo cercano a lo lejano, que es como los niños
 * construyen la idea de sociedad: primero la casa y el barrio, luego el
 * país y sus regiones, y solo después el tiempo histórico y la ciudadanía.
 * Por eso los bloques son tres y no coinciden con las materias escolares
 * sino con esa progresión.
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
    ['slug' => 'historia-y-ciudadania', 'name' => 'Historia y Ciudadanía', 'icon' => '🏛️', 'sort_order' => 3,
     'description' => 'El tiempo, las civilizaciones y cómo funciona la vida en común.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE 1 · MI MUNDO CERCANO (3 a 5 años)
// =====================================================================

[
    'slug'  => 'donde-vive-cada-personaje',
    'title' => '¿Dónde vive cada personaje?',
    'description' => 'Cada ser vivo tiene un lugar donde vive. Descubre cuál es el de cada uno.',
    'objective' => 'Reconocer que todos los seres vivos habitan un lugar y relacionar habitante y hogar.',
    'icon' => '🏡', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 10, 'tags' => ['observacion', 'clasificacion', 'juego'],
    'estaciones' => [

        est('Cada uno en su casa', 'Une cada animal con el lugar donde vive', '🔗', 'emparejar', [
            ['e' => '🐝', 'w' => 'Colmena'],
            ['e' => '🐦', 'w' => 'Nido'],
            ['e' => '🐕', 'w' => 'Casita'],
            ['e' => '🐟', 'w' => 'Pecera'],
            ['e' => '🐴', 'w' => 'Establo'],
            ['e' => '🐰', 'w' => 'Madriguera'],
        ]),

        est('¿Dónde vive?', 'Elige el lugar correcto para cada uno', '🏞️', 'opcion_multiple', [
            omp('¿Dónde vive el pez?',      ['En el agua', 'En el árbol', 'En la cueva'], 'En el agua', '🐟'),
            omp('¿Dónde vive el pájaro?',   ['En un nido', 'En el mar', 'Bajo la tierra'], 'En un nido', '🐦'),
            omp('¿Dónde vive el oso?',      ['En una cueva', 'En una pecera', 'En una colmena'], 'En una cueva', '🐻'),
            omp('¿Dónde vive la vaca?',     ['En el establo', 'En el nido', 'En el río'], 'En el establo', '🐄'),
            omp('¿Dónde vives tú?',         ['En una casa', 'En un nido', 'En una colmena'], 'En una casa', '🧒'),
            omp('¿Dónde vive el camello?',  ['En el desierto', 'En el mar', 'En la nieve'], 'En el desierto', '🐫'),
        ]),

        est('Personas y lugares', 'Cada persona trabaja en un lugar distinto', '👷', 'opcion_multiple', [
            omp('¿Dónde trabaja el médico?',    ['En el hospital', 'En la panadería', 'En el bus'], 'En el hospital', '👩‍⚕️'),
            omp('¿Dónde trabaja la profesora?', ['En la escuela', 'En el hospital', 'En la finca'], 'En la escuela', '👩‍🏫'),
            omp('¿Dónde trabaja el panadero?',  ['En la panadería', 'En la escuela', 'En el mar'], 'En la panadería', '👨‍🍳'),
            omp('¿Dónde trabaja el bombero?',   ['En la estación de bomberos', 'En la biblioteca', 'En la playa'], 'En la estación de bomberos', '👨‍🚒'),
            omp('¿Dónde trabaja el agricultor?', ['En el campo', 'En el hospital', 'En el avión'], 'En el campo', '👨‍🌾'),
        ]),

        est('Memoria de hogares', 'Encuentra las parejas de casas', '🧠', 'memoria',
            ['🏠', '🏢', '⛺', '🏰', '🛖', '🚐']),
    ],
],

[
    'slug'  => 'mi-familia',
    'title' => 'Mi familia',
    'description' => 'Quién es quién en la familia y cómo nos ayudamos entre todos.',
    'objective' => 'Nombrar los miembros de la familia y reconocer que todos aportan al hogar.',
    'icon' => '👨‍👩‍👧', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 10, 'tags' => ['familia', 'vocabulario', 'convivencia'],
    'estaciones' => [

        est('¿Quién es quién?', 'Descubre el nombre de cada familiar', '👪', 'opcion_multiple', [
            omp('La mamá de mi mamá es mi…',      ['Abuela', 'Tía', 'Prima'], 'Abuela', '👵'),
            omp('El hijo de mi tía es mi…',       ['Primo', 'Hermano', 'Abuelo'], 'Primo', '👦'),
            omp('El papá de mi papá es mi…',      ['Abuelo', 'Tío', 'Hermano'], 'Abuelo', '👴'),
            omp('La hermana de mi mamá es mi…',   ['Tía', 'Abuela', 'Prima'], 'Tía', '👩'),
            omp('El hijo de mis papás, además de mí, es mi…', ['Hermano', 'Primo', 'Tío'], 'Hermano', '🧒'),
        ]),

        est('De más pequeño a más grande', 'Ordena las edades de la familia', '📏', 'ordenar_secuencia', [
            'title' => 'Ordena del más pequeño al más grande',
            'items' => ['👶 Bebé', '🧒 Niño', '🧑 Joven', '🧓 Abuelo'],
        ]),

        est('Cada quien ayuda', 'En casa todos colaboran', '🤝', 'opcion_multiple', [
            omp('¿Quién puede ayudar a guardar los juguetes?', ['Yo mismo', 'Nadie', 'Solo los adultos'], 'Yo mismo', '🧸'),
            omp('Si veo el piso mojado, ¿qué hago?',   ['Aviso a un adulto', 'Corro encima', 'No digo nada'], 'Aviso a un adulto', '💧'),
            omp('¿Qué puedo hacer después de comer?',  ['Llevar mi plato', 'Dejarlo todo tirado', 'Esconderlo'], 'Llevar mi plato', '🍽️'),
            omp('Mi hermanito llora. ¿Qué hago?',      ['Lo acompaño y aviso', 'Me río', 'Me voy'], 'Lo acompaño y aviso', '👶'),
        ]),

        est('Une la familia', 'Empareja cada persona con su nombre', '🔗', 'emparejar', [
            ['e' => '👶', 'w' => 'Bebé'],
            ['e' => '👵', 'w' => 'Abuela'],
            ['e' => '👴', 'w' => 'Abuelo'],
            ['e' => '👩', 'w' => 'Mamá'],
            ['e' => '👨', 'w' => 'Papá'],
            ['e' => '🧒', 'w' => 'Hermano'],
        ]),
    ],
],

[
    'slug'  => 'mi-barrio',
    'title' => 'Mi barrio',
    'description' => 'Los lugares del barrio, las personas que nos cuidan y las señales de la calle.',
    'objective' => 'Identificar lugares y servicios del entorno cercano y las normas básicas de la calle.',
    'icon' => '🏘️', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 12, 'tags' => ['oficios', 'observacion', 'seguridad'],
    'estaciones' => [

        est('Lugares del barrio', 'Une cada lugar con su nombre', '🔗', 'emparejar', [
            ['e' => '🏥', 'w' => 'Hospital'],
            ['e' => '🏫', 'w' => 'Escuela'],
            ['e' => '🏪', 'w' => 'Tienda'],
            ['e' => '⛪', 'w' => 'Iglesia'],
            ['e' => '🏞️', 'w' => 'Parque'],
            ['e' => '📚', 'w' => 'Biblioteca'],
        ]),

        est('¿A dónde voy?', 'Elige el lugar del barrio que necesitas', '🧭', 'opcion_multiple', [
            omp('Me duele una muela. ¿A dónde voy?',        ['Al odontólogo', 'A la panadería', 'Al parque'], 'Al odontólogo', '🦷'),
            omp('Quiero un libro prestado. ¿A dónde voy?',  ['A la biblioteca', 'Al hospital', 'A la tienda'], 'A la biblioteca', '📚'),
            omp('Necesito comprar pan. ¿A dónde voy?',      ['A la panadería', 'A la escuela', 'Al hospital'], 'A la panadería', '🍞'),
            omp('Quiero jugar al aire libre. ¿A dónde voy?', ['Al parque', 'Al odontólogo', 'A la farmacia'], 'Al parque', '⚽'),
            omp('Tengo mucha fiebre. ¿A dónde voy?',        ['Al hospital', 'A la tienda', 'Al parque'], 'Al hospital', '🤒'),
        ]),

        est('Los que nos cuidan', 'Reconoce a quienes ayudan en el barrio', '👮', 'opcion_multiple', [
            omp('¿Quién apaga los incendios?',        ['El bombero', 'El panadero', 'El cartero'], 'El bombero', '🚒'),
            omp('¿Quién nos cura cuando estamos enfermos?', ['El médico', 'El conductor', 'El mecánico'], 'El médico', '🩺'),
            omp('¿Quién trae las cartas?',            ['El cartero', 'El bombero', 'El médico'], 'El cartero', '📬'),
            omp('¿Quién recoge la basura?',           ['El recolector', 'El profesor', 'El panadero'], 'El recolector', '♻️'),
            omp('¿Quién conduce el bus?',             ['El conductor', 'El médico', 'El bombero'], 'El conductor', '🚌'),
        ]),

        est('Señales de la calle', 'Aprende qué dice cada señal', '🚦', 'opcion_multiple', [
            omp('El semáforo está en rojo. ¿Qué hago?',  ['Me detengo', 'Cruzo corriendo', 'Cierro los ojos'], 'Me detengo', '🔴'),
            omp('El semáforo está en verde para caminar. ¿Qué hago?', ['Cruzo mirando a los lados', 'Me quedo quieto siempre', 'Me acuesto'], 'Cruzo mirando a los lados', '🟢'),
            omp('¿Por dónde cruzo la calle?',            ['Por la cebra', 'Por la mitad', 'Por donde sea'], 'Por la cebra', '🚸'),
            omp('¿Con quién debo cruzar la calle?',      ['Con un adulto', 'Solo', 'Con mi perro'], 'Con un adulto', '🧑‍🤝‍🧑'),
        ]),
    ],
],

[
    'slug'  => 'conoce-colombia-preescolar',
    'title' => 'Conoce Colombia',
    'description' => 'La bandera, los símbolos y los animales del país donde vivimos.',
    'objective' => 'Reconocer los símbolos patrios de Colombia y algunos animales representativos.',
    'icon' => '🇨🇴', 'nivel' => 'preescolar', 'bloque' => 'mi-mundo-cercano',
    'duracion' => 10, 'tags' => ['cultura', 'observacion', 'colombia'],
    'estaciones' => [

        est('Nuestros símbolos', 'Conoce los símbolos de Colombia', '🇨🇴', 'opcion_multiple', [
            omp('¿Cómo se llama el país donde vivimos?', ['Colombia', 'Brasil', 'México'], 'Colombia', '🇨🇴'),
            omp('¿Qué es esto?',   ['La bandera', 'El escudo', 'El himno'], 'La bandera', '🇨🇴'),
            omp('El himno se…',    ['Canta', 'Come', 'Dibuja'], 'Canta', '🎵'),
            omp('¿Cuál es el ave símbolo de Colombia?', ['El cóndor', 'El pingüino', 'La gallina'], 'El cóndor', '🦅'),
            omp('¿Cuál es la flor símbolo de Colombia?', ['La orquídea', 'El girasol', 'El cactus'], 'La orquídea', '🌸'),
        ]),

        est('Los colores de la bandera', 'Ordena la bandera de arriba hacia abajo', '🎨', 'ordenar_secuencia', [
            'title' => 'Ordena los colores de la bandera, de arriba hacia abajo',
            'items' => ['🟨 Amarillo', '🟦 Azul', '🟥 Rojo'],
        ]),

        est('Animales de Colombia', 'Toca los animales que viven en Colombia', '🦜', 'seleccion_imagenes',
            conTitulo('Toca todos los animales de Colombia', 'Algunos no viven aquí', [
                ['e' => '🦜', 'n' => 'Guacamaya', 'ok' => true],
                ['e' => '🐆', 'n' => 'Jaguar',    'ok' => true],
                ['e' => '🦥', 'n' => 'Perezoso',  'ok' => true],
                ['e' => '🐬', 'n' => 'Delfín rosado', 'ok' => true],
                ['e' => '🐧', 'n' => 'Pingüino',  'ok' => false],
                ['e' => '🐘', 'n' => 'Elefante',  'ok' => false],
                ['e' => '🦁', 'n' => 'León',      'ok' => false],
                ['e' => '🐨', 'n' => 'Koala',     'ok' => false],
            ])),
    ],
],


// =====================================================================
//  BLOQUE 2 · CONOCE COLOMBIA (6 a 8 años)
// =====================================================================

[
    'slug'  => 'arma-el-mapa-de-colombia',
    'title' => 'Arma el mapa de Colombia',
    'description' => 'Las cinco regiones, las ciudades principales y cómo se ubica el país.',
    'objective' => 'Ubicar las regiones naturales de Colombia y sus ciudades más importantes.',
    'icon' => '🗺️', 'nivel' => 'primaria-inicial', 'bloque' => 'conoce-colombia',
    'duracion' => 15, 'tags' => ['geografia', 'mapas', 'colombia'],
    'estaciones' => [

        est('Las cinco regiones', 'Colombia se divide en cinco regiones naturales', '🏞️', 'opcion_multiple', [
            omp('¿Cuántas regiones naturales tiene Colombia?', [4, 5, 6, 7], 5),
            omp('¿Qué región está junto al mar Caribe?',  ['Caribe', 'Amazonía', 'Orinoquía'], 'Caribe', '🏖️'),
            omp('¿Qué región tiene las montañas y la mayoría de las ciudades grandes?', ['Andina', 'Pacífica', 'Amazonía'], 'Andina', '⛰️'),
            omp('¿Qué región es la selva más grande del país?', ['Amazonía', 'Caribe', 'Andina'], 'Amazonía', '🌳'),
            omp('¿Qué región tiene los llanos y el ganado?', ['Orinoquía', 'Pacífica', 'Caribe'], 'Orinoquía', '🐎'),
            omp('¿Qué región está junto al océano Pacífico y es la más lluviosa?', ['Pacífica', 'Andina', 'Orinoquía'], 'Pacífica', '🌧️'),
        ]),

        est('¿Dónde queda?', 'Ubica las ciudades en su región', '📍', 'opcion_multiple', [
            omp('¿En qué región está Bogotá?',     ['Andina', 'Caribe', 'Amazonía'], 'Andina', '🏙️'),
            omp('¿En qué región está Cartagena?',  ['Caribe', 'Andina', 'Pacífica'], 'Caribe', '🏰'),
            omp('¿En qué región está Medellín?',   ['Andina', 'Orinoquía', 'Caribe'], 'Andina', '🌆'),
            omp('¿En qué región está Buenaventura?', ['Pacífica', 'Caribe', 'Amazonía'], 'Pacífica', '⚓'),
            omp('¿En qué región está Leticia?',    ['Amazonía', 'Andina', 'Caribe'], 'Amazonía', '🌴'),
            omp('¿Cuál es la capital de Colombia?', ['Bogotá', 'Medellín', 'Cali'], 'Bogotá', '⭐'),
        ]),

        est('De norte a sur', 'Ordena las ciudades según su ubicación', '🧭', 'ordenar_secuencia', [
            'title' => 'Ordena estas ciudades de norte a sur',
            'items' => ['Santa Marta', 'Medellín', 'Bogotá', 'Cali', 'Leticia'],
        ]),

        est('Sopa de regiones', 'Encuentra las cinco regiones escondidas', '🔍', 'sopa_letras',
            sopa(['CARIBE', 'ANDINA', 'PACIFICA', 'AMAZONIA', 'LLANOS'], 11)),
    ],
],

[
    'slug'  => 'viaje-por-las-regiones',
    'title' => 'Viaje por las regiones',
    'description' => 'Comidas, música, paisajes y costumbres de cada rincón de Colombia.',
    'objective' => 'Relacionar cada región con sus rasgos culturales y naturales característicos.',
    'icon' => '🏞️', 'nivel' => 'primaria-inicial', 'bloque' => 'conoce-colombia',
    'duracion' => 15, 'tags' => ['cultura', 'geografia', 'colombia'],
    'estaciones' => [

        est('Región Caribe', 'Playas, cumbia y sabor', '🏖️', 'opcion_multiple', [
            omp('¿Qué baile es típico de la costa Caribe?', ['La cumbia', 'El bambuco', 'El joropo'], 'La cumbia', '💃'),
            omp('¿Cómo es el clima del Caribe colombiano?', ['Caliente', 'Muy frío', 'Con nieve'], 'Caliente', '☀️'),
            omp('¿Cuál es una comida típica del Caribe?',   ['Arepa de huevo', 'Ajiaco', 'Lechona'], 'Arepa de huevo', '🥚'),
            omp('¿Qué ciudad del Caribe tiene murallas antiguas?', ['Cartagena', 'Cali', 'Pasto'], 'Cartagena', '🏰'),
        ]),

        est('Región Andina', 'Montañas, café y ciudades', '⛰️', 'opcion_multiple', [
            omp('¿Qué producto famoso se cultiva en las montañas andinas?', ['Café', 'Coco', 'Dátiles'], 'Café', '☕'),
            omp('¿Cuál es una sopa típica de Bogotá?',  ['Ajiaco', 'Sancocho de pescado', 'Casabe'], 'Ajiaco', '🍲'),
            omp('¿Qué baile es típico de la región Andina?', ['El bambuco', 'La cumbia', 'El currulao'], 'El bambuco', '🎻'),
            omp('¿Cómo es el clima en las montañas altas?', ['Frío', 'Muy caliente', 'Desértico'], 'Frío', '🧥'),
        ]),

        est('Pacífico, Orinoquía y Amazonía', 'Selva, llanos y ríos', '🌳', 'opcion_multiple', [
            omp('¿Qué baile es típico del Pacífico?',   ['El currulao', 'La cumbia', 'El bambuco'], 'El currulao', '🥁'),
            omp('¿Qué instrumento es típico del Pacífico?', ['La marimba', 'El acordeón', 'La gaita'], 'La marimba', '🎶'),
            omp('¿Qué baile es típico de los Llanos?',  ['El joropo', 'El currulao', 'La cumbia'], 'El joropo', '🎺'),
            omp('¿Cuál es el río más grande de la Amazonía?', ['El Amazonas', 'El Magdalena', 'El Cauca'], 'El Amazonas', '🛶'),
            omp('¿Qué actividad es común en los Llanos?', ['La ganadería', 'La pesca de ballenas', 'La minería de oro en la nieve'], 'La ganadería', '🐄'),
        ]),

        est('Une región y plato típico', 'Cada región tiene su sabor', '🍽️', 'emparejar', [
            ['e' => '🥚', 'w' => 'Caribe'],
            ['e' => '🍲', 'w' => 'Andina'],
            ['e' => '🐟', 'w' => 'Pacífica'],
            ['e' => '🥩', 'w' => 'Orinoquía'],
            ['e' => '🐜', 'w' => 'Amazonía'],
        ]),
    ],
],

[
    'slug'  => 'quien-hizo-que',
    'title' => '¿Quién hizo qué?',
    'description' => 'Personajes de Colombia y del mundo, y aquello por lo que los recordamos.',
    'objective' => 'Asociar personajes históricos y culturales con sus aportes.',
    'icon' => '👤', 'nivel' => 'primaria-inicial', 'bloque' => 'conoce-colombia',
    'duracion' => 12, 'tags' => ['historia', 'cultura', 'memoria'],
    'estaciones' => [

        est('Personajes de Colombia', '¿Por qué los recordamos?', '🇨🇴', 'opcion_multiple', [
            omp('Simón Bolívar es recordado por…',        ['Luchar por la independencia', 'Pintar cuadros', 'Componer canciones'], 'Luchar por la independencia', '⚔️'),
            omp('Gabriel García Márquez fue…',            ['Escritor', 'Futbolista', 'Astronauta'], 'Escritor', '📖'),
            omp('Fernando Botero fue…',                   ['Pintor y escultor', 'Científico', 'Cantante'], 'Pintor y escultor', '🎨'),
            omp('Policarpa Salavarrieta es recordada por…', ['Ayudar a la independencia', 'Inventar el avión', 'Escribir el himno'], 'Ayudar a la independencia', '🕊️'),
            omp('Shakira es conocida en el mundo por…',   ['Su música', 'Sus pinturas', 'Sus mapas'], 'Su música', '🎤'),
        ]),

        est('Personajes del mundo', 'Gente que cambió algo para siempre', '🌍', 'opcion_multiple', [
            omp('Marie Curie fue famosa por…',    ['Sus descubrimientos científicos', 'Sus pinturas', 'Sus canciones'], 'Sus descubrimientos científicos', '🔬'),
            omp('Nelson Mandela luchó por…',      ['La igualdad entre las personas', 'Construir pirámides', 'Pintar cuadros'], 'La igualdad entre las personas', '🕊️'),
            omp('Leonardo da Vinci fue…',         ['Pintor e inventor', 'Futbolista', 'Rey'], 'Pintor e inventor', '🖌️'),
            omp('Los hermanos Wright inventaron…', ['El avión', 'El teléfono', 'La bombilla'], 'El avión', '✈️'),
        ]),

        est('¿Verdadero o falso?', 'Piensa bien antes de responder', '🤔', 'opcion_multiple', [
            omp('Colombia tiene costas en dos océanos.',   ['Verdadero', 'Falso'], 'Verdadero', '🌊'),
            omp('Bogotá queda junto al mar.',              ['Verdadero', 'Falso'], 'Falso', '🏙️'),
            omp('El café se cultiva en la región Andina.', ['Verdadero', 'Falso'], 'Verdadero', '☕'),
            omp('La Amazonía es un desierto.',             ['Verdadero', 'Falso'], 'Falso', '🌴'),
            omp('El cóndor es el ave símbolo de Colombia.', ['Verdadero', 'Falso'], 'Verdadero', '🦅'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE 3 · HISTORIA Y CIUDADANÍA (9 a 12 años)
// =====================================================================

[
    'slug'  => 'detectives-de-la-historia',
    'title' => 'Detectives de la historia',
    'description' => 'Los objetos del pasado cuentan historias. Aprende a leerlas.',
    'objective' => 'Comprender que la historia se reconstruye a partir de evidencias y fuentes.',
    'icon' => '🕵️', 'nivel' => 'primaria-media', 'bloque' => 'historia-y-ciudadania',
    'duracion' => 15, 'tags' => ['historia', 'logica', 'lectura', 'reto'],
    'estaciones' => [

        est('Pistas del pasado', 'Lee la historia y responde', '📜', 'cuento', [
            'slides' => [
                ['img' => '⛏️', 'text' => 'Ana es arqueóloga. Su trabajo es encontrar objetos que dejaron las personas que vivieron hace mucho tiempo.'],
                ['img' => '🏺', 'text' => 'Un día encontró una vasija de barro enterrada. Dentro había semillas de maíz quemadas.'],
                ['img' => '🌽', 'text' => 'Ana pensó: si hay semillas de maíz, aquí vivía gente que sembraba. Y si están quemadas, es que cocinaban.'],
                ['img' => '🏡', 'text' => 'Cerca encontró piedras puestas en círculo. Eso le hizo pensar que allí hubo una casa, no un campamento de paso.'],
                ['img' => '💡', 'text' => 'Ana no vio a esas personas. Pero por los objetos que dejaron pudo saber cómo vivían. Así trabaja la historia.'],
            ],
            'qs' => [
                reto('¿Qué encontró Ana dentro de la vasija?', ['Semillas de maíz', 'Monedas de oro', 'Un mapa'], 'Semillas de maíz'),
                reto('¿Qué le hizo pensar que allí sembraban?', ['Las semillas', 'Las piedras', 'La vasija rota'], 'Las semillas'),
                reto('¿Por qué creyó que allí hubo una casa?', ['Por las piedras en círculo', 'Por el maíz', 'Porque vio a la gente'], 'Por las piedras en círculo'),
                reto('¿Cómo sabemos cómo vivía la gente del pasado?', ['Por los objetos que dejaron', 'Porque nos lo contaron ellos', 'No se puede saber'], 'Por los objetos que dejaron'),
            ],
        ]),

        est('¿De qué época es?', 'Ubica cada objeto en su momento', '🏺', 'opcion_multiple', [
            omp('Una vasija de barro hecha a mano es de…',  ['Hace muchos siglos', 'La semana pasada', 'El futuro'], 'Hace muchos siglos', '🏺'),
            omp('Un teléfono celular es de…',               ['Época actual', 'La Edad Media', 'La prehistoria'], 'Época actual', '📱'),
            omp('Una armadura de caballero es de…',         ['La Edad Media', 'Hoy', 'La prehistoria'], 'La Edad Media', '🛡️'),
            omp('Una punta de flecha de piedra es de…',     ['La prehistoria', 'El siglo pasado', 'Hoy'], 'La prehistoria', '🏹'),
            omp('Una máquina de escribir es de…',           ['Hace unos 100 años', 'La prehistoria', 'El futuro'], 'Hace unos 100 años', '⌨️'),
        ]),

        est('Fuentes de la historia', 'No todo sirve igual para saber del pasado', '🔎', 'opcion_multiple', [
            omp('¿Cuál de estos es una fuente para estudiar el pasado?', ['Una carta antigua', 'Una película de superhéroes', 'Un videojuego'], 'Una carta antigua', '✉️'),
            omp('Un abuelo contando lo que vivió es…',      ['Una fuente oral', 'Un invento', 'Un mito'], 'Una fuente oral', '👴'),
            omp('Una foto de hace 80 años es…',             ['Una fuente visual', 'Una fuente falsa', 'Nada útil'], 'Una fuente visual', '📷'),
            omp('Si dos fuentes dicen cosas distintas, ¿qué hago?', ['Comparo y busco más', 'Creo la primera', 'Dejo de investigar'], 'Comparo y busco más', '⚖️'),
        ]),

        est('El caso del objeto perdido', 'Usa todo lo que aprendiste', '🏆', 'desafio_final', [
            reto('En una excavación aparecen redes y muchos huesos de pescado. ¿A qué se dedicaba esa gente?',
                 ['A la pesca', 'A la ganadería', 'A la minería'], 'A la pesca'),
            reto('Encuentras monedas con la cara de un rey. ¿Qué te dice eso?',
                 ['Que había un reino y comercio', 'Que no había gobierno', 'Que la gente no comerciaba'], 'Que había un reino y comercio'),
            reto('Hallas herramientas de hierro y otras de piedra en capas distintas. ¿Cuáles son más antiguas?',
                 ['Las de piedra', 'Las de hierro', 'Son de la misma época'], 'Las de piedra'),
            reto('¿Qué hace un arqueólogo antes de sacar un objeto del suelo?',
                 ['Anota y fotografía dónde estaba', 'Lo saca rápido', 'Lo lava con jabón'], 'Anota y fotografía dónde estaba'),
        ]),
    ],
],

[
    'slug'  => 'construye-una-linea-del-tiempo',
    'title' => 'Construye una línea del tiempo',
    'description' => 'Antes, después y mucho antes: pon los hechos en su orden.',
    'objective' => 'Ordenar hechos y objetos en el tiempo y manejar las nociones de siglo y década.',
    'icon' => '⏳', 'nivel' => 'primaria-media', 'bloque' => 'historia-y-ciudadania',
    'duracion' => 15, 'tags' => ['historia', 'secuencias', 'logica'],
    'estaciones' => [

        est('Ordena los inventos', 'Del más antiguo al más reciente', '💡', 'ordenar_secuencia', [
            'title' => 'Ordena estos inventos del más antiguo al más reciente',
            'items' => ['🔥 El fuego', '🛞 La rueda', '📜 La escritura', '🖨️ La imprenta', '💡 La bombilla', '📱 El celular'],
        ]),

        est('Ordena las civilizaciones', 'Un viaje de miles de años', '🏛️', 'ordenar_secuencia', [
            'title' => 'Ordena estas civilizaciones de la más antigua a la más reciente',
            'items' => ['🏜️ Egipto antiguo', '🏛️ Grecia antigua', '🦅 Imperio romano', '🌽 Imperio inca', '🚢 Llegada de los europeos a América'],
        ]),

        est('Siglos y décadas', 'Aprende a medir el tiempo largo', '📅', 'opcion_multiple', [
            omp('¿Cuántos años tiene un siglo?',    [10, 50, 100, 1000], 100),
            omp('¿Cuántos años tiene una década?',  [5, 10, 100, 20], 10),
            omp('¿Cuántos años tiene un milenio?',  [100, 500, 1000, 10000], 1000),
            omp('El año 1810 pertenece al siglo…',  ['XIX', 'XVIII', 'XX'], 'XIX'),
            omp('El año 2026 pertenece al siglo…',  ['XXI', 'XX', 'XIX'], 'XXI'),
        ]),

        est('Hechos de Colombia', 'Ordena la historia del país', '🇨🇴', 'ordenar_secuencia', [
            'title' => 'Ordena estos hechos del más antiguo al más reciente',
            'items' => [
                '🌽 Pueblos indígenas habitan el territorio',
                '⛵ Llegada de los españoles',
                '⚔️ Independencia (1810)',
                '🚂 Llega el ferrocarril',
                '💻 Llega el internet',
            ],
        ]),
    ],
],

[
    'slug'  => 'como-funciona-mi-ciudad',
    'title' => '¿Cómo funciona mi ciudad?',
    'description' => 'Quién decide, quién cuida y qué te toca a ti en la vida en común.',
    'objective' => 'Comprender el funcionamiento básico del gobierno local y la relación entre derechos y deberes.',
    'icon' => '🏙️', 'nivel' => 'primaria-media', 'bloque' => 'historia-y-ciudadania',
    'duracion' => 15, 'tags' => ['ciudadania', 'convivencia', 'reto'],
    'estaciones' => [

        est('¿Quién hace qué?', 'Los cargos de una ciudad', '🏛️', 'opcion_multiple', [
            omp('¿Quién gobierna un municipio?',   ['El alcalde', 'El presidente', 'El rector'], 'El alcalde', '🏛️'),
            omp('¿Quién gobierna un departamento?', ['El gobernador', 'El alcalde', 'El juez'], 'El gobernador', '🗺️'),
            omp('¿Quién gobierna el país?',        ['El presidente', 'El alcalde', 'El gobernador'], 'El presidente', '🇨🇴'),
            omp('¿Quién hace las leyes en Colombia?', ['El Congreso', 'El alcalde', 'La policía'], 'El Congreso', '📜'),
            omp('¿Quién resuelve cuando hay un conflicto legal?', ['Un juez', 'El vecino', 'El conductor del bus'], 'Un juez', '⚖️'),
        ]),

        est('Derechos y deberes', 'Van siempre de la mano', '🤝', 'opcion_multiple', [
            omp('Ir a la escuela es un…',                 ['Derecho', 'Castigo', 'Favor'], 'Derecho', '🏫'),
            omp('Cuidar los parques del barrio es un…',   ['Deber', 'Derecho', 'Premio'], 'Deber', '🌳'),
            omp('Que nadie te maltrate es un…',           ['Derecho', 'Deber', 'Privilegio'], 'Derecho', '🛡️'),
            omp('Respetar a los demás es un…',            ['Deber', 'Derecho', 'Opción'], 'Deber', '🙂'),
            omp('Tener un nombre y un documento es un…',  ['Derecho', 'Deber', 'Lujo'], 'Derecho', '📄'),
            omp('Botar la basura en su lugar es un…',     ['Deber', 'Derecho', 'Castigo'], 'Deber', '🗑️'),
        ]),

        est('Así se vota', 'Los pasos de una elección', '🗳️', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para votar',
            'items' => [
                '1️⃣ Los candidatos presentan sus propuestas',
                '2️⃣ Cada ciudadano se informa',
                '3️⃣ Llega el día de la votación',
                '4️⃣ Se depositan los votos',
                '5️⃣ Se cuentan los votos',
                '6️⃣ Se anuncia quién ganó',
            ],
        ]),

        est('Reto de ciudadanía', 'Decide qué harías tú', '🏆', 'desafio_final', [
            reto('En tu barrio no recogen la basura hace días. ¿Qué es lo más útil?',
                 ['Escribir una petición a la alcaldía con los vecinos', 'Quejarse en voz alta y ya', 'Botar la basura en otro barrio'],
                 'Escribir una petición a la alcaldía con los vecinos'),
            reto('Un compañero no puede subir al salón porque no hay rampa. Eso es un problema de…',
                 ['Accesibilidad', 'Suerte', 'Horario'], 'Accesibilidad'),
            reto('¿Qué significa que Colombia sea una democracia?',
                 ['Que los ciudadanos eligen a sus gobernantes', 'Que manda una sola persona para siempre', 'Que no hay leyes'],
                 'Que los ciudadanos eligen a sus gobernantes'),
            reto('Ves que alguien daña un árbol del parque. Lo mejor es…',
                 ['Avisar a un adulto o a la autoridad', 'Dañar otro árbol', 'No hacer nada nunca'],
                 'Avisar a un adulto o a la autoridad'),
            reto('Los impuestos que pagan los adultos sirven para…',
                 ['Escuelas, hospitales y vías', 'Regalos de los políticos', 'Nada'], 'Escuelas, hospitales y vías'),
        ]),
    ],
],

],
];
