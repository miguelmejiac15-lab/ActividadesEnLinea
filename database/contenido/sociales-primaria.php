<?php
/**
 * sociales-primaria.php — Ciencias Sociales de 1.º a 6.º
 *
 * Escrito sobre las mallas de «Individuos y Sociedades» (K4, K5, K6) de
 * `MallasPrimaria/` —los archivos que llevan el código R52 en el nombre—
 * más los ejes de espacio, tiempo y convivencia del Sachunterricht.
 *
 * Es un colegio de Barranquilla, y la malla de K4 baja el temario hasta el
 * departamento del Atlántico y sus municipios. Eso se conserva: lo local es
 * lo primero que un niño reconoce como propio, y era la mitad del sentido
 * de la unidad. Pero se trata como ejemplo, no como requisito — las
 * preguntas del bloque funcionan para un niño de cualquier región del país,
 * porque el catálogo no se vende a un solo colegio.
 *
 * La progresión es la de la malla y no es casual: primero el espacio que se
 * puede recorrer (la casa, el barrio), después el que hay que representar
 * (mapas, regiones), y solo al final el tiempo histórico, que es el más
 * abstracto de los tres.
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
    ['slug' => 'mapas-y-territorio', 'name' => 'Mapas y Territorio', 'icon' => '🗺️', 'sort_order' => 4,
     'description' => 'Orientarse, leer un mapa y reconocer los paisajes y regiones donde vive la gente.'],
    ['slug' => 'democracia-y-derechos', 'name' => 'Democracia y Derechos', 'icon' => '🏛️', 'sort_order' => 5,
     'description' => 'Normas, gobierno escolar, derechos y deberes, y por qué vivir juntos exige acuerdos.'],
    ['slug' => 'linea-del-tiempo', 'name' => 'La Línea del Tiempo', 'icon' => '⏳', 'sort_order' => 6,
     'description' => 'De los primeros pobladores a la independencia: cómo llegamos hasta aquí.'],
    ['slug' => 'economia-y-sociedad', 'name' => 'Economía y Sociedad', 'icon' => '💼', 'sort_order' => 7,
     'description' => 'De dónde salen las cosas que usamos y cómo se organiza el trabajo de un país.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · MAPAS Y TERRITORIO
// =====================================================================

[
    'slug'  => 'los-puntos-cardinales',
    'title' => 'Los puntos cardinales',
    'description' => 'Norte, sur, este y oeste: las cuatro palabras que sirven para no perderse.',
    'objective' => 'Orientarse en el espacio usando los puntos cardinales y referencias del entorno.',
    'icon' => '🧭', 'nivel' => 'primaria-inicial', 'bloque' => 'mapas-y-territorio',
    'duracion' => 11, 'tags' => ['observacion', 'geografia', 'logica'],
    'estaciones' => [

        est('Las cuatro direcciones', 'Los puntos cardinales', '🧭', 'opcion_multiple', [
            omp('¿Por dónde sale el sol?',            ['Por el este', 'Por el oeste', 'Por el norte'], 'Por el este', '🌅'),
            omp('¿Por dónde se oculta el sol?',       ['Por el oeste', 'Por el este', 'Por el sur'], 'Por el oeste', '🌇'),
            omp('¿Cuál es el punto cardinal opuesto al norte?', ['El sur', 'El este', 'El oeste'], 'El sur'),
            omp('¿Cuántos puntos cardinales hay?',    ['Cuatro', 'Dos', 'Ocho'], 'Cuatro'),
            omp('¿Qué instrumento señala siempre el norte?', ['La brújula', 'El reloj', 'La regla'], 'La brújula', '🧭'),
        ]),

        est('En el mapa', 'Arriba es el norte', '🗺️', 'opcion_multiple', [
            omp('En un mapa, ¿qué está arriba?',     ['El norte', 'El sur', 'El este'], 'El norte'),
            omp('En un mapa, ¿qué está a la derecha?', ['El este', 'El oeste', 'El norte'], 'El este'),
            omp('En un mapa, ¿qué está abajo?',      ['El sur', 'El norte', 'El oeste'], 'El sur'),
            omp('¿Qué es la leyenda de un mapa?',    ['La explicación de sus símbolos', 'Su título', 'Un cuento'], 'La explicación de sus símbolos'),
            omp('¿Para qué sirve la escala de un mapa?', ['Para saber las distancias reales', 'Para colorear', 'Para el título'], 'Para saber las distancias reales'),
        ]),

        est('Cerca y lejos', 'Ubicarse en el barrio', '🏘️', 'opcion_multiple', [
            omp('Si el parque está frente a mi casa, está…', ['Cerca', 'Lejos', 'En otro país'], 'Cerca'),
            omp('¿Qué es un plano del barrio?',         ['Un dibujo del barrio visto desde arriba', 'Una foto', 'Un cuento'], 'Un dibujo del barrio visto desde arriba'),
            omp('Si camino hacia el norte y me devuelvo, voy hacia…', ['El sur', 'El este', 'El norte otra vez'], 'El sur'),
            omp('¿Qué me ayuda a llegar a un sitio nuevo?', ['Un mapa o alguien que me indique', 'Cerrar los ojos', 'Correr'], 'Un mapa o alguien que me indique'),
            omp('¿Qué es una referencia?',              ['Algo conocido que me ayuda a ubicarme', 'Una calle', 'Un número'], 'Algo conocido que me ayuda a ubicarme'),
        ]),

        est('Memoria del mapa', 'Encuentra las parejas', '🧠', 'memoria',
            ['🧭', '🗺️', '📍', '🏔️', '🌊', '🌳']),
    ],
],

[
    'slug'  => 'paisajes-de-la-tierra',
    'title' => 'Paisajes de la Tierra',
    'description' => 'Montaña, llanura, valle, costa y desierto: cómo es el relieve donde vive la gente.',
    'objective' => 'Reconocer y describir características de los distintos paisajes geográficos.',
    'icon' => '🏔️', 'nivel' => 'primaria-media', 'bloque' => 'mapas-y-territorio',
    'duracion' => 13, 'tags' => ['observacion', 'geografia', 'clasificacion'],
    'estaciones' => [

        est('Cada paisaje con su nombre', 'Une el dibujo con la palabra', '🔗', 'emparejar', [
            ['e' => '🏔️', 'w' => 'Montaña'],
            ['e' => '🏝️', 'w' => 'Isla'],
            ['e' => '🏜️', 'w' => 'Desierto'],
            ['e' => '🌊', 'w' => 'Costa'],
            ['e' => '🌾', 'w' => 'Llanura'],
            ['e' => '🌋', 'w' => 'Volcán'],
        ]),

        est('Cómo es cada uno', 'Características del relieve', '⛰️', 'opcion_multiple', [
            omp('¿Cómo es una llanura?',              ['Plana y extensa', 'Muy alta', 'Con mucha agua'], 'Plana y extensa'),
            omp('¿Qué hay entre dos montañas?',       ['Un valle', 'Una isla', 'Un desierto'], 'Un valle'),
            omp('¿Cómo es el desierto?',              ['Seco y con muy poca lluvia', 'Frío y húmedo', 'Lleno de árboles'], 'Seco y con muy poca lluvia'),
            omp('¿Qué es una isla?',                  ['Tierra rodeada de agua', 'Una montaña alta', 'Un río grande'], 'Tierra rodeada de agua'),
            omp('¿Qué es la meseta?',                 ['Una llanura en lo alto', 'Un valle profundo', 'Una playa'], 'Una llanura en lo alto'),
        ]),

        est('El paisaje y la gente', 'Dónde vivimos cambia cómo vivimos', '👨‍🌾', 'opcion_multiple', [
            omp('¿A qué se dedica la gente de la costa?',      ['A la pesca y al turismo', 'A la minería de montaña', 'Al pastoreo de nieve'], 'A la pesca y al turismo', '🎣'),
            omp('¿Qué se cultiva mejor en una llanura fértil?', ['Cereales y pastos', 'Nada', 'Solo cactus'], 'Cereales y pastos', '🌾'),
            omp('¿Por qué hay pocos cultivos en el desierto?',  ['Falta agua', 'Hace frío', 'Hay muchos árboles'], 'Falta agua'),
            omp('¿Qué cambia el clima según la altura?',       ['Cuanto más alto, más frío', 'Cuanto más alto, más calor', 'No cambia'], 'Cuanto más alto, más frío'),
            omp('¿Qué es el relieve?',                         ['La forma de la superficie terrestre', 'El clima', 'La población'], 'La forma de la superficie terrestre'),
        ]),

        est('Paisaje natural o construido', 'Lo que estaba y lo que hicimos', '🏗️', 'opcion_multiple', [
            omp('¿Cuál es un paisaje natural?',    ['Una selva', 'Una ciudad', 'Una carretera'], 'Una selva', '🌴'),
            omp('¿Cuál es un paisaje construido?', ['Un puente', 'Una montaña', 'Un río'], 'Un puente', '🌉'),
            omp('¿Puede el ser humano cambiar el paisaje?', ['Sí, mucho', 'No', 'Solo en el mar'], 'Sí, mucho'),
            omp('¿Qué pasa cuando se tala un bosque para sembrar?', ['Cambia el paisaje y el ecosistema', 'No pasa nada', 'Llueve más'], 'Cambia el paisaje y el ecosistema'),
            omp('¿Qué es la urbanización?',        ['El crecimiento de las ciudades', 'La lluvia', 'Un tipo de montaña'], 'El crecimiento de las ciudades'),
        ]),
    ],
],

[
    'slug'  => 'regiones-naturales-de-colombia',
    'title' => 'Regiones naturales de Colombia',
    'description' => 'Caribe, Pacífica, Andina, Orinoquía, Amazonía e Insular: seis países dentro de uno.',
    'objective' => 'Identificar las seis regiones naturales de Colombia y sus características.',
    'icon' => '🇨🇴', 'nivel' => 'primaria-media', 'bloque' => 'mapas-y-territorio',
    'duracion' => 14, 'tags' => ['colombia', 'geografia', 'cultura'],
    'estaciones' => [

        est('Las seis regiones', 'Cada una con su carácter', '🗺️', 'opcion_multiple', [
            omp('¿Cuántas regiones naturales tiene Colombia?', ['Seis', 'Tres', 'Diez'], 'Seis'),
            omp('¿Qué región tiene playas en el mar Caribe?',  ['La Caribe', 'La Amazonía', 'La Andina'], 'La Caribe', '🏖️'),
            omp('¿Qué región tiene la selva más grande?',      ['La Amazonía', 'La Caribe', 'La Insular'], 'La Amazonía', '🌳'),
            omp('¿Qué región tiene las tres cordilleras?',     ['La Andina', 'La Orinoquía', 'La Insular'], 'La Andina', '⛰️'),
            omp('¿Qué región son las islas de San Andrés y Providencia?', ['La Insular', 'La Pacífica', 'La Caribe'], 'La Insular', '🏝️'),
        ]),

        est('Clima y naturaleza', 'Lo que distingue a cada región', '🌦️', 'opcion_multiple', [
            omp('¿Qué región es la más lluviosa del país?',   ['La Pacífica', 'La Caribe', 'La Orinoquía'], 'La Pacífica', '🌧️'),
            omp('¿Qué región tiene grandes llanuras de pastos?', ['La Orinoquía', 'La Andina', 'La Insular'], 'La Orinoquía', '🌾'),
            omp('¿Dónde está el páramo, que guarda el agua?',  ['En la región Andina', 'En la Amazonía', 'En la Insular'], 'En la región Andina'),
            omp('¿Qué región es más cálida, la Caribe o la Andina en la montaña?', ['La Caribe', 'La Andina', 'Igual'], 'La Caribe'),
            omp('¿Qué región tiene la mayor biodiversidad de selva?', ['La Amazonía', 'La Insular', 'La Orinoquía'], 'La Amazonía'),
        ]),

        est('Cultura y comida', 'Cada región, su sabor', '🍲', 'opcion_multiple', [
            omp('¿De qué región es el vallenato?',        ['Del Caribe', 'De la Amazonía', 'De la Orinoquía'], 'Del Caribe', '🪗'),
            omp('¿De qué región es el joropo?',           ['De la Orinoquía', 'Del Caribe', 'Del Pacífico'], 'De la Orinoquía'),
            omp('¿De qué región es la marimba de chonta?', ['Del Pacífico', 'De la Andina', 'De la Insular'], 'Del Pacífico'),
            omp('¿Dónde se celebra el Carnaval de Barranquilla?', ['En la región Caribe', 'En la Amazonía', 'En la Andina'], 'En la región Caribe', '🎭'),
            omp('¿Qué es la diversidad cultural?',        ['Que convivan muchas formas de vivir y celebrar', 'Que todos seamos iguales', 'Un tipo de comida'], 'Que convivan muchas formas de vivir y celebrar'),
        ]),

        est('Sopa de regiones', 'Encuentra las regiones escondidas', '🔤', 'sopa_letras',
            sopa(['CARIBE', 'ANDINA', 'PACIFICO', 'AMAZONIA', 'LLANOS'], 11)),

        est('Desafío colombiano', 'Cinco preguntas del país', '🏆', 'desafio_final', [
            reto('¿Cuál es la capital de Colombia?',            ['Bogotá', 'Medellín', 'Cali'], 'Bogotá'),
            reto('¿Qué dos océanos bañan a Colombia?',          ['Pacífico y Atlántico', 'Índico y Pacífico', 'Solo el Pacífico'], 'Pacífico y Atlántico'),
            reto('¿Cómo se llama la división principal del territorio colombiano?', ['Departamentos', 'Provincias', 'Estados'], 'Departamentos'),
            reto('¿Cuántos departamentos tiene Colombia?',      ['32', '10', '50'], '32'),
            reto('¿Por qué Colombia es tan biodiversa?',        ['Por su ubicación y su variedad de climas', 'Por su tamaño solamente', 'Por casualidad'], 'Por su ubicación y su variedad de climas'),
        ]),
    ],
],

[
    'slug'  => 'la-tierra-en-el-universo',
    'title' => 'La Tierra en el universo',
    'description' => 'Coordenadas geográficas, husos horarios y las consecuencias de dónde está Colombia.',
    'objective' => 'Utilizar las coordenadas geográficas para ubicar lugares y explicar la posición de Colombia.',
    'icon' => '🌐', 'nivel' => 'primaria-superior', 'bloque' => 'mapas-y-territorio',
    'duracion' => 15, 'tags' => ['geografia', 'mapas', 'logica'],
    'estaciones' => [

        est('Líneas imaginarias', 'Paralelos y meridianos', '📐', 'opcion_multiple', [
            omp('¿Qué línea divide la Tierra en norte y sur?', ['El ecuador', 'El meridiano de Greenwich', 'El trópico'], 'El ecuador'),
            omp('¿Cómo se llaman las líneas horizontales?',    ['Paralelos', 'Meridianos', 'Ejes'], 'Paralelos'),
            omp('¿Cómo se llaman las líneas verticales?',      ['Meridianos', 'Paralelos', 'Trópicos'], 'Meridianos'),
            omp('¿Qué meridiano marca el punto de partida?',   ['El de Greenwich', 'El ecuador', 'El polar'], 'El de Greenwich'),
            omp('¿Qué son latitud y longitud?',                ['Las coordenadas para ubicar un lugar', 'Dos ciudades', 'Dos continentes'], 'Las coordenadas para ubicar un lugar'),
        ]),

        est('Colombia en el mapa', 'Una posición privilegiada', '📍', 'opcion_multiple', [
            omp('¿Por qué pasa el ecuador cerca de Colombia?', ['Porque está en la zona ecuatorial', 'Por casualidad', 'Por su tamaño'], 'Porque está en la zona ecuatorial'),
            omp('¿Por eso Colombia tiene…',                    ['Poca variación de estaciones', 'Cuatro estaciones marcadas', 'Inviernos con nieve'], 'Poca variación de estaciones'),
            omp('¿En qué continente está Colombia?',           ['América', 'Europa', 'África'], 'América'),
            omp('¿Qué ventaja da tener costas en dos océanos?', ['Facilita el comercio con más países', 'Ninguna', 'Hace más frío'], 'Facilita el comercio con más países', '🚢'),
            omp('¿Con qué países limita Colombia por el sur?', ['Ecuador y Perú', 'Chile y Argentina', 'México y Cuba'], 'Ecuador y Perú'),
        ]),

        est('Día, noche y estaciones', 'Consecuencias del movimiento de la Tierra', '🌗', 'opcion_multiple', [
            omp('¿Qué movimiento produce el día y la noche?', ['La rotación', 'La traslación', 'La gravedad'], 'La rotación'),
            omp('¿Qué movimiento produce las estaciones?',    ['La traslación', 'La rotación', 'El viento'], 'La traslación'),
            omp('¿Por qué hay husos horarios?',               ['Porque la Tierra gira y no amanece a la vez en todas partes', 'Por los idiomas', 'Por los océanos'], 'Porque la Tierra gira y no amanece a la vez en todas partes'),
            omp('Cuando es de día en Colombia, al otro lado del mundo…', ['Es de noche', 'También es de día', 'No hay hora'], 'Es de noche'),
            omp('¿Qué inclinación de la Tierra causa las estaciones?', ['La del eje terrestre', 'La del ecuador', 'La de la Luna'], 'La del eje terrestre'),
        ]),

        est('Crucigrama del planeta', 'Cada pista es una definición', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'ECUADOR',   'pista' => 'Línea que divide la Tierra en norte y sur'],
                ['w' => 'ROTACION',  'pista' => 'Giro de la Tierra sobre sí misma'],
                ['w' => 'MAPA',      'pista' => 'Dibujo plano de un territorio'],
                ['w' => 'ESCALA',    'pista' => 'Dice cuánto mide en la realidad lo del mapa'],
                ['w' => 'BRUJULA',   'pista' => 'Instrumento que señala el norte'],
                ['w' => 'PACIFICO',  'pista' => 'El océano más grande del mundo'],
                ['w' => 'ANDES',     'pista' => 'Cordillera que recorre Suramérica'],
            ])),

        est('Desafío del globo', 'Cinco preguntas de geografía', '🏆', 'desafio_final', [
            reto('¿Cuántos continentes hay?',                  ['Seis o siete según la clasificación', 'Tres', 'Doce'], 'Seis o siete según la clasificación'),
            reto('¿Cuál es el océano más grande?',             ['El Pacífico', 'El Atlántico', 'El Índico'], 'El Pacífico'),
            reto('¿Qué representa un globo terráqueo mejor que un mapa plano?', ['La forma real de la Tierra', 'Los colores', 'Las ciudades'], 'La forma real de la Tierra'),
            reto('Si un lugar está a 0° de latitud, está…',    ['Sobre el ecuador', 'En el polo', 'En Greenwich'], 'Sobre el ecuador'),
            reto('¿Para qué sirven las coordenadas en un GPS?', ['Para ubicar un punto exacto', 'Para medir el tiempo', 'Para el clima'], 'Para ubicar un punto exacto'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · DEMOCRACIA Y DERECHOS
// =====================================================================

[
    'slug'  => 'normas-y-acuerdos',
    'title' => 'Normas y acuerdos',
    'description' => 'Por qué existen las reglas en casa, en la escuela y en la calle, y qué pasa sin ellas.',
    'objective' => 'Reconocer la función de las normas en la convivencia y participar en su cumplimiento.',
    'icon' => '📜', 'nivel' => 'primaria-inicial', 'bloque' => 'democracia-y-derechos',
    'duracion' => 11, 'tags' => ['convivencia', 'ciudadania', 'comprension'],
    'estaciones' => [

        est('¿Para qué sirven las normas?', 'No son castigos', '🤔', 'opcion_multiple', [
            omp('¿Para qué sirven las normas?',            ['Para vivir juntos sin hacernos daño', 'Para molestar', 'Para castigar'], 'Para vivir juntos sin hacernos daño'),
            omp('¿Qué pasaría si nadie respetara el semáforo?', ['Habría accidentes', 'Nada', 'Iríamos más rápido'], 'Habría accidentes', '🚦'),
            omp('¿Quién debe cumplir las normas del salón?', ['Todos, incluidos los adultos', 'Solo los niños', 'Nadie'], 'Todos, incluidos los adultos'),
            omp('Si una norma me parece injusta, ¿qué hago?', ['La discuto y propongo cambiarla', 'La rompo', 'Me callo'], 'La discuto y propongo cambiarla'),
            omp('¿Pueden cambiar las normas?',             ['Sí, si el grupo lo acuerda', 'No, nunca', 'Solo el director'], 'Sí, si el grupo lo acuerda'),
        ]),

        est('Normas de cada lugar', 'Cada sitio tiene las suyas', '🏫', 'opcion_multiple', [
            omp('En la biblioteca conviene…',        ['Hablar bajito', 'Gritar', 'Correr'], 'Hablar bajito', '📚'),
            omp('En la calle debo caminar por…',     ['El andén', 'La mitad de la vía', 'Entre los carros'], 'El andén'),
            omp('En el salón, cuando alguien habla…', ['Escucho y espero mi turno', 'Interrumpo', 'Me tapo los oídos'], 'Escucho y espero mi turno'),
            omp('En casa, si uso algo de otro…',     ['Pido permiso primero', 'Lo tomo sin avisar', 'Lo escondo'], 'Pido permiso primero'),
            omp('En el comedor debo…',               ['Cuidar mis cosas y las de otros', 'Botar comida', 'Correr con el plato'], 'Cuidar mis cosas y las de otros'),
        ]),

        est('Derechos y deberes', 'Van siempre juntos', '⚖️', 'opcion_multiple', [
            omp('¿Qué es un derecho?',               ['Algo que me corresponde por ser persona', 'Un premio', 'Un castigo'], 'Algo que me corresponde por ser persona'),
            omp('¿Qué es un deber?',                 ['Algo que me toca cumplir', 'Un regalo', 'Una opción'], 'Algo que me toca cumplir'),
            omp('Tengo derecho a estudiar. Mi deber es…', ['Asistir y esforzarme', 'No ir', 'Copiar'], 'Asistir y esforzarme'),
            omp('Tengo derecho a jugar. Mi deber es…', ['Respetar a los demás mientras juego', 'Ganar siempre', 'No compartir'], 'Respetar a los demás mientras juego'),
            omp('¿Tienen derechos todos los niños?', ['Sí, todos por igual', 'Solo algunos', 'Solo los grandes'], 'Sí, todos por igual'),
        ]),

        est('Convivencia', 'Decide qué está bien', '⚡', 'juego_rapido',
            conTitulo('¿Está bien hecho?', 'Responde rápido', [
                ['e' => '🤝', 'n' => 'Ayudar a un compañero', 'ok' => true],
                ['e' => '🗑️', 'n' => 'Tirar basura al piso',  'ok' => false],
                ['e' => '🙋', 'n' => 'Pedir la palabra',      'ok' => true],
                ['e' => '😠', 'n' => 'Empujar para pasar',    'ok' => false],
                ['e' => '👂', 'n' => 'Escuchar al que habla', 'ok' => true],
                ['e' => '🤐', 'n' => 'Burlarse de alguien',   'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'el-gobierno-escolar',
    'title' => 'El gobierno escolar',
    'description' => 'Elegir representantes, votar y participar: la democracia empieza en el colegio.',
    'objective' => 'Comprender el gobierno escolar como espacio real de participación democrática.',
    'icon' => '🗳️', 'nivel' => 'primaria-media', 'bloque' => 'democracia-y-derechos',
    'duracion' => 13, 'tags' => ['ciudadania', 'convivencia', 'comprension'],
    'estaciones' => [

        est('¿Qué es el gobierno escolar?', 'Participar de verdad', '🏫', 'opcion_multiple', [
            omp('¿Qué es el gobierno escolar?',            ['La forma en que los estudiantes participan en las decisiones', 'Los profesores mandando', 'Una materia'], 'La forma en que los estudiantes participan en las decisiones'),
            omp('¿Quién es el personero estudiantil?',     ['Un estudiante que defiende los derechos de sus compañeros', 'Un profesor', 'El rector'], 'Un estudiante que defiende los derechos de sus compañeros'),
            omp('¿Cómo se elige al representante de curso?', ['Con el voto de sus compañeros', 'Lo elige el profesor', 'Por sorteo'], 'Con el voto de sus compañeros'),
            omp('¿Qué es el voto secreto?',                ['Que nadie sabe por quién voté', 'Que no se cuenta', 'Que voto dos veces'], 'Que nadie sabe por quién voté'),
            omp('¿Para qué sirve tener representantes?',   ['Para que la voz del grupo llegue a las decisiones', 'Para mandar', 'Para no estudiar'], 'Para que la voz del grupo llegue a las decisiones'),
        ]),

        est('La democracia', 'Decidir entre todos', '🤝', 'opcion_multiple', [
            omp('¿Qué significa democracia?',            ['El poder del pueblo para decidir', 'El poder de uno solo', 'La ley del más fuerte'], 'El poder del pueblo para decidir'),
            omp('En una votación, ¿qué gana?',           ['La opción con más votos', 'La del más fuerte', 'La primera'], 'La opción con más votos'),
            omp('¿Debe respetarse a quien votó distinto?', ['Sí, siempre', 'No', 'Solo si gana'], 'Sí, siempre'),
            omp('¿Qué es la pluralidad?',                ['Que existan muchas formas de pensar y todas cuenten', 'Que todos piensen igual', 'Que nadie hable'], 'Que existan muchas formas de pensar y todas cuenten'),
            omp('¿Sirve la democracia solo para votar?', ['No, también para dialogar y acordar', 'Sí, solo para votar', 'No sirve'], 'No, también para dialogar y acordar'),
        ]),

        est('Ordena una elección', 'Los pasos del proceso', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos de una elección escolar',
            'items' => ['Se presentan los candidatos', 'Cada uno explica sus propuestas', 'Se vota en secreto', 'Se cuentan los votos', 'Se posesiona el elegido'],
        ]),

        est('Los derechos de los niños', 'Lo que a nadie se le puede quitar', '🧒', 'opcion_multiple', [
            omp('¿Tienen los niños derecho a un nombre?',       ['Sí', 'No', 'Solo si lo piden'], 'Sí'),
            omp('¿Tienen los niños derecho a la educación?',    ['Sí, todos', 'Solo algunos', 'No'], 'Sí, todos'),
            omp('¿Tienen los niños derecho a jugar y descansar?', ['Sí, es un derecho', 'No, es un premio', 'Solo en vacaciones'], 'Sí, es un derecho'),
            omp('¿Puede alguien maltratar a un niño?',          ['No, nunca, y hay que denunciarlo', 'Sí, si es su familia', 'A veces'], 'No, nunca, y hay que denunciarlo'),
            omp('Si un derecho mío no se cumple, ¿qué hago?',   ['Le cuento a un adulto de confianza', 'Me quedo callado', 'Me vengo'], 'Le cuento a un adulto de confianza'),
        ]),
    ],
],

[
    'slug'  => 'la-division-de-poderes',
    'title' => 'La división de poderes',
    'description' => 'Ejecutivo, legislativo y judicial: por qué el poder de un país se reparte en tres.',
    'objective' => 'Reconocer las tres ramas del poder público y su función en una democracia.',
    'icon' => '⚖️', 'nivel' => 'primaria-superior', 'bloque' => 'democracia-y-derechos',
    'duracion' => 14, 'tags' => ['ciudadania', 'comprension', 'logica'],
    'estaciones' => [

        est('Las tres ramas', 'Cada una con su trabajo', '🏛️', 'opcion_multiple', [
            omp('¿Qué rama gobierna y ejecuta las leyes?',   ['La ejecutiva', 'La legislativa', 'La judicial'], 'La ejecutiva'),
            omp('¿Qué rama hace las leyes?',                 ['La legislativa', 'La ejecutiva', 'La judicial'], 'La legislativa'),
            omp('¿Qué rama juzga si se cumplen las leyes?',  ['La judicial', 'La ejecutiva', 'La legislativa'], 'La judicial'),
            omp('¿Quién encabeza la rama ejecutiva en Colombia?', ['El presidente', 'Un juez', 'Un senador'], 'El presidente'),
            omp('¿Cómo se llama el conjunto de senadores y representantes?', ['El Congreso', 'La Corte', 'El Gabinete'], 'El Congreso'),
        ]),

        est('¿Por qué se separa el poder?', 'Para que nadie mande solo', '🔐', 'opcion_multiple', [
            omp('¿Por qué se divide el poder en tres?',    ['Para que ninguno tenga todo el control', 'Para hacerlo más lento', 'Por tradición'], 'Para que ninguno tenga todo el control'),
            omp('Si una sola persona hiciera y juzgara las leyes…', ['Podría abusar sin que nadie lo frene', 'Sería más justo', 'No pasaría nada'], 'Podría abusar sin que nadie lo frene'),
            omp('¿Qué es la Constitución?',                ['La norma más importante del país', 'Un libro de historia', 'Una ley cualquiera'], 'La norma más importante del país'),
            omp('¿Puede una ley contradecir la Constitución?', ['No', 'Sí', 'Solo a veces'], 'No'),
            omp('¿En qué año se firmó la Constitución colombiana vigente?', ['1991', '1810', '2000'], '1991'),
        ]),

        est('Participar de mayor', 'Cómo se decide en un país', '🗳️', 'opcion_multiple', [
            omp('¿Desde qué edad se vota en Colombia?',  ['18 años', '15 años', '25 años'], '18 años'),
            omp('¿Qué es el voto?',                      ['Un derecho y un deber ciudadano', 'Una obligación con multa', 'Un juego'], 'Un derecho y un deber ciudadano'),
            omp('¿Cada cuánto se elige presidente en Colombia?', ['Cada cuatro años', 'Cada año', 'Cada diez años'], 'Cada cuatro años'),
            omp('¿Quién gobierna un departamento?',      ['El gobernador', 'El alcalde', 'El presidente'], 'El gobernador'),
            omp('¿Quién gobierna un municipio?',         ['El alcalde', 'El gobernador', 'Un juez'], 'El alcalde'),
        ]),

        est('Desafío ciudadano', 'Cinco preguntas de democracia', '🏆', 'desafio_final', [
            reto('¿Qué pasa si un gobernante no respeta la Constitución?', ['Los jueces pueden frenarlo', 'Nada', 'Se cambia la Constitución'], 'Los jueces pueden frenarlo'),
            reto('¿Es la democracia solo votar cada cuatro años?',  ['No, es participar todo el tiempo', 'Sí', 'Solo en elecciones'], 'No, es participar todo el tiempo'),
            reto('¿Qué es la tutela en Colombia?',                 ['Un recurso para proteger un derecho', 'Un impuesto', 'Una elección'], 'Un recurso para proteger un derecho'),
            reto('¿Qué significa que todos somos iguales ante la ley?', ['La ley se aplica igual a todos', 'Todos ganamos lo mismo', 'Todos pensamos igual'], 'La ley se aplica igual a todos'),
            reto('¿Por qué importa respetar a quien piensa distinto?', ['Porque la pluralidad sostiene la democracia', 'No importa', 'Para evitar problemas'], 'Porque la pluralidad sostiene la democracia'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · LA LÍNEA DEL TIEMPO
// =====================================================================

[
    'slug'  => 'antes-ahora-y-despues',
    'title' => 'Antes, ahora y después',
    'description' => 'Ordenar el tiempo: mi historia, la de mi familia y las huellas del pasado.',
    'objective' => 'Situar hechos en una línea de tiempo y reconocer huellas del pasado en el entorno.',
    'icon' => '⌛', 'nivel' => 'primaria-inicial', 'bloque' => 'linea-del-tiempo',
    'duracion' => 11, 'tags' => ['secuencias', 'historia', 'comprension'],
    'estaciones' => [

        est('Ordena tu vida', 'De bebé a ahora', '👶', 'ordenar_secuencia', [
            'title' => 'Ordena las etapas de la vida',
            'items' => ['👶 Bebé', '🧒 Niño', '🧑 Joven', '🧔 Adulto', '🧓 Anciano'],
        ]),

        est('¿Cuándo pasó?', 'Antes, ahora o después', '🕰️', 'opcion_multiple', [
            omp('El desayuno de hoy fue…',           ['Antes que el almuerzo', 'Después de la cena', 'Mañana'], 'Antes que el almuerzo'),
            omp('Mis abuelos nacieron…',             ['Antes que mis papás', 'Después que yo', 'El mismo día que yo'], 'Antes que mis papás'),
            omp('¿Qué viene después del martes?',    ['Miércoles', 'Lunes', 'Domingo'], 'Miércoles'),
            omp('¿Qué es una línea de tiempo?',      ['Un dibujo que ordena hechos según cuándo pasaron', 'Un reloj', 'Un calendario de pared'], 'Un dibujo que ordena hechos según cuándo pasaron'),
            omp('¿Qué es una década?',               ['Diez años', 'Cien años', 'Un mes'], 'Diez años'),
        ]),

        est('Huellas del pasado', 'Cosas que nos cuentan cómo se vivía', '🏺', 'opcion_multiple', [
            omp('¿Qué es una fuente histórica?',       ['Algo que nos da información del pasado', 'Un río antiguo', 'Un libro nuevo'], 'Algo que nos da información del pasado'),
            omp('¿Cuál es una fuente del pasado?',     ['Una foto antigua', 'Un dibujo de hoy', 'Un plan de mañana'], 'Una foto antigua', '📷'),
            omp('¿Qué estudian los arqueólogos?',      ['Restos y objetos antiguos', 'Los animales vivos', 'El clima de mañana'], 'Restos y objetos antiguos'),
            omp('¿Qué nos cuenta un abuelo cuando habla de su infancia?', ['Cómo era la vida antes', 'Lo que pasará', 'Nada útil'], 'Cómo era la vida antes', '🧓'),
            omp('¿Cambian las cosas con el tiempo?',   ['Sí, todo cambia', 'No', 'Solo la ropa'], 'Sí, todo cambia'),
        ]),

        est('Antes y ahora', 'Cómo cambió la vida diaria', '🔗', 'emparejar', [
            ['e' => '🕯️', 'w' => 'Antes se alumbraba así'],
            ['e' => '💡', 'w' => 'Ahora se alumbra así'],
            ['e' => '✉️', 'w' => 'Antes se escribía así'],
            ['e' => '📱', 'w' => 'Ahora se escribe así'],
            ['e' => '🐎', 'w' => 'Antes se viajaba así'],
            ['e' => '✈️', 'w' => 'Ahora se viaja así'],
        ]),
    ],
],

[
    'slug'  => 'los-primeros-pobladores-de-america',
    'title' => 'Los primeros pobladores de América',
    'description' => 'Cómo llegaron los primeros seres humanos al continente y cómo vivían.',
    'objective' => 'Explicar las teorías del poblamiento de América y las formas de vida de los primeros grupos.',
    'icon' => '🦣', 'nivel' => 'primaria-media', 'bloque' => 'linea-del-tiempo',
    'duracion' => 13, 'tags' => ['historia', 'comprension', 'geografia'],
    'estaciones' => [

        est('¿Cómo llegaron?', 'Las rutas del poblamiento', '🚶', 'opcion_multiple', [
            omp('¿De dónde se cree que vinieron los primeros pobladores?', ['De Asia', 'De Europa', 'De África directamente'], 'De Asia'),
            omp('¿Por dónde cruzaron según la teoría más aceptada?',       ['Por el estrecho de Bering', 'Por el océano Atlántico', 'Por el Amazonas'], 'Por el estrecho de Bering'),
            omp('¿Por qué pudieron cruzar caminando?',                     ['El mar estaba congelado y había un paso de tierra', 'Había un puente', 'Había barcos'], 'El mar estaba congelado y había un paso de tierra'),
            omp('¿A qué se dedicaban los primeros grupos?',                ['A cazar y recolectar', 'A la industria', 'Al comercio con Europa'], 'A cazar y recolectar'),
            omp('¿Por qué se movían de un lugar a otro?',                  ['Seguían a los animales y buscaban alimento', 'Por diversión', 'Por el clima solamente'], 'Seguían a los animales y buscaban alimento'),
        ]),

        est('De nómadas a sedentarios', 'El cambio que lo cambió todo', '🌾', 'opcion_multiple', [
            omp('¿Qué significa nómada?',                 ['Que se traslada sin quedarse en un lugar', 'Que vive siempre en el mismo sitio', 'Que navega'], 'Que se traslada sin quedarse en un lugar'),
            omp('¿Qué significa sedentario?',             ['Que se queda a vivir en un lugar fijo', 'Que viaja mucho', 'Que caza'], 'Que se queda a vivir en un lugar fijo'),
            omp('¿Qué descubrimiento permitió quedarse en un sitio?', ['La agricultura', 'La rueda', 'La escritura'], 'La agricultura'),
            omp('¿Qué apareció cuando la gente se quedó en un lugar?', ['Las aldeas y las primeras ciudades', 'Los aviones', 'El dinero digital'], 'Las aldeas y las primeras ciudades'),
            omp('¿Qué animal se domesticó para ayudar en el trabajo?', ['El perro y luego otros', 'El tigre', 'El águila'], 'El perro y luego otros'),
        ]),

        est('Ordena la prehistoria', 'Los grandes cambios', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena estos cambios en el tiempo',
            'items' => ['Cazar y recolectar', 'Dominar el fuego', 'Sembrar la tierra', 'Vivir en aldeas', 'Construir ciudades'],
        ]),

        est('Herramientas del pasado', 'Con qué se ayudaban', '🪨', 'opcion_multiple', [
            omp('¿De qué eran las primeras herramientas?', ['De piedra', 'De plástico', 'De acero'], 'De piedra'),
            omp('¿Para qué servía el fuego?',              ['Cocinar, calentar y alejar animales', 'Solo para ver', 'Para nada'], 'Cocinar, calentar y alejar animales'),
            omp('¿Qué pintaban en las cuevas?',            ['Animales y escenas de caza', 'Letras', 'Números'], 'Animales y escenas de caza'),
            omp('¿Por qué pintaban en las cuevas?',        ['Para contar y recordar lo que vivían', 'Por aburrimiento', 'Para decorar nada más'], 'Para contar y recordar lo que vivían'),
            omp('¿Cómo sabemos todo esto?',                ['Por los restos que encuentran los arqueólogos', 'Porque lo escribieron', 'Por adivinanza'], 'Por los restos que encuentran los arqueólogos'),
        ]),
    ],
],

[
    'slug'  => 'culturas-prehispanicas',
    'title' => 'Culturas prehispánicas',
    'description' => 'Mayas, aztecas, incas y las comunidades indígenas de Colombia antes de 1492.',
    'objective' => 'Identificar las principales civilizaciones americanas y valorar la herencia indígena.',
    'icon' => '🗿', 'nivel' => 'primaria-media', 'bloque' => 'linea-del-tiempo',
    'duracion' => 14, 'tags' => ['historia', 'cultura', 'colombia'],
    'estaciones' => [

        est('Las grandes civilizaciones', 'Tres pueblos, tres territorios', '🏛️', 'opcion_multiple', [
            omp('¿Dónde vivieron los mayas?',        ['En México y Centroamérica', 'En Perú', 'En Argentina'], 'En México y Centroamérica'),
            omp('¿Dónde vivieron los incas?',        ['En la cordillera de los Andes', 'En el Caribe', 'En Brasil'], 'En la cordillera de los Andes'),
            omp('¿Dónde vivieron los aztecas?',      ['En el centro de México', 'En Chile', 'En Colombia'], 'En el centro de México'),
            omp('¿Qué cultura construyó Machu Picchu?', ['Los incas', 'Los mayas', 'Los aztecas'], 'Los incas'),
            omp('¿Qué inventaron los mayas en matemáticas?', ['El cero y un calendario preciso', 'El álgebra', 'La calculadora'], 'El cero y un calendario preciso'),
        ]),

        est('Pueblos indígenas de Colombia', 'Quiénes estaban aquí', '🇨🇴', 'opcion_multiple', [
            omp('¿Qué pueblo trabajaba el oro y hacía la balsa muisca?', ['Los muiscas', 'Los mayas', 'Los incas'], 'Los muiscas'),
            omp('¿Qué pueblo construyó Ciudad Perdida en la Sierra Nevada?', ['Los tayronas', 'Los aztecas', 'Los guambianos'], 'Los tayronas'),
            omp('¿Qué pueblo hizo las estatuas de San Agustín?', ['La cultura agustiniana', 'Los incas', 'Los mayas'], 'La cultura agustiniana'),
            omp('¿Hay pueblos indígenas en Colombia hoy?',  ['Sí, más de ochenta', 'No, desaparecieron', 'Solo uno'], 'Sí, más de ochenta'),
            omp('¿Qué es la leyenda de El Dorado?',         ['Un relato sobre el ritual del cacique muisca', 'Una película', 'Una ciudad europea'], 'Un relato sobre el ritual del cacique muisca'),
        ]),

        est('Lo que nos dejaron', 'Herencia viva', '🌽', 'opcion_multiple', [
            omp('¿Qué alimento americano se come hoy en todo el mundo?', ['El maíz', 'El trigo', 'El arroz'], 'El maíz', '🌽'),
            omp('¿Qué otro alimento salió de América?',    ['La papa', 'La manzana', 'La uva'], 'La papa', '🥔'),
            omp('¿Qué queda de las lenguas indígenas?',    ['Muchas palabras que usamos a diario', 'Nada', 'Solo números'], 'Muchas palabras que usamos a diario'),
            omp('¿Qué técnica agrícola usaban en las montañas?', ['Las terrazas de cultivo', 'Los invernaderos', 'El riego por goteo'], 'Las terrazas de cultivo'),
            omp('¿Por qué hay que respetar a los pueblos indígenas?', ['Son parte de nuestra identidad y tienen los mismos derechos', 'Porque son pocos', 'No hace falta'], 'Son parte de nuestra identidad y tienen los mismos derechos'),
        ]),

        est('Sopa prehispánica', 'Encuentra las culturas', '🔤', 'sopa_letras',
            sopa(['MUISCA', 'TAYRONA', 'MAYA', 'INCA', 'AZTECA'], 10)),

        est('Desafío del pasado', 'Cinco preguntas de historia', '🏆', 'desafio_final', [
            reto('¿En qué año llegó Colón a América?',       ['1492', '1810', '1600'], '1492'),
            reto('¿Cómo se llama el periodo antes de esa llegada?', ['Prehispánico', 'Republicano', 'Moderno'], 'Prehispánico'),
            reto('¿Qué pasó cuando se encontraron los dos mundos?', ['Se transformaron las dos culturas', 'No cambió nada', 'Solo cambió Europa'], 'Se transformaron las dos culturas'),
            reto('¿Qué llevaron los europeos a América?',    ['Caballos, trigo y enfermedades nuevas', 'Maíz', 'Papa'], 'Caballos, trigo y enfermedades nuevas'),
            reto('¿Qué llevaron de América a Europa?',       ['Maíz, papa, cacao y tomate', 'Trigo', 'Caballos'], 'Maíz, papa, cacao y tomate'),
        ]),
    ],
],

[
    'slug'  => 'la-independencia-de-colombia',
    'title' => 'La independencia de Colombia',
    'description' => 'Del 20 de julio de 1810 a la Gran Colombia: causas, personajes y consecuencias.',
    'objective' => 'Explicar las causas y los hechos principales del proceso de independencia de Colombia.',
    'icon' => '🎺', 'nivel' => 'primaria-superior', 'bloque' => 'linea-del-tiempo',
    'duracion' => 15, 'tags' => ['historia', 'colombia', 'ciudadania'],
    'estaciones' => [

        est('¿Por qué se buscó la independencia?', 'Las causas', '🤔', 'opcion_multiple', [
            omp('¿Quién gobernaba estas tierras antes de 1810?', ['La Corona española', 'Inglaterra', 'Nadie'], 'La Corona española'),
            omp('¿Quiénes eran los criollos?',                   ['Hijos de españoles nacidos en América', 'Los indígenas', 'Los españoles de España'], 'Hijos de españoles nacidos en América'),
            omp('¿Por qué estaban inconformes los criollos?',    ['No podían ocupar los cargos más altos', 'No tenían tierras', 'No hablaban español'], 'No podían ocupar los cargos más altos'),
            omp('¿Qué otra revolución influyó en las ideas de libertad?', ['La Revolución Francesa', 'La Revolución Industrial rusa', 'Ninguna'], 'La Revolución Francesa'),
            omp('¿Qué se cobraba a las colonias y molestaba mucho?', ['Los impuestos', 'La escuela', 'El agua'], 'Los impuestos'),
        ]),

        est('El 20 de julio de 1810', 'El día del florero', '🏺', 'opcion_multiple', [
            omp('¿Qué objeto originó el episodio del 20 de julio?', ['Un florero', 'Una espada', 'Un libro'], 'Un florero'),
            omp('¿En qué ciudad ocurrió?',              ['Santafé de Bogotá', 'Cartagena', 'Popayán'], 'Santafé de Bogotá'),
            omp('¿Qué se creó ese día?',                ['Una junta de gobierno propia', 'Un ejército', 'Una universidad'], 'Una junta de gobierno propia'),
            omp('¿Qué se celebra cada 20 de julio en Colombia?', ['El día de la independencia', 'El día del idioma', 'El día del trabajo'], 'El día de la independencia'),
            omp('¿Terminó la guerra ese mismo día?',    ['No, duró años más', 'Sí', 'Duró un mes'], 'No, duró años más'),
        ]),

        est('Los personajes', 'Quién hizo qué', '🔗', 'emparejar', [
            ['e' => '🎖️', 'w' => 'Simón Bolívar'],
            ['e' => '⚔️', 'w' => 'Francisco de Paula Santander'],
            ['e' => '👩', 'w' => 'Policarpa Salavarrieta'],
            ['e' => '📜', 'w' => 'Antonio Nariño'],
            ['e' => '🐎', 'w' => 'José Antonio Páez'],
            ['e' => '🌺', 'w' => 'Manuela Sáenz'],
        ]),

        est('Ordena la independencia', 'Los hechos en su orden', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los hechos de la independencia',
            'items' => ['Grito del 20 de julio de 1810', 'La Patria Boba', 'La Reconquista española', 'Campaña Libertadora de 1819', 'Creación de la Gran Colombia'],
        ]),

        est('Completa el relato', 'Coloca cada palabra donde corresponde', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Lee la frase entera antes de elegir', [
                [
                    'titulo' => 'El 20 de julio de 1810',
                    'texto'  => 'Aquel viernes, un grupo de ___ pidió prestado un florero a un '
                              . 'comerciante español. La negativa sirvió de excusa para provocar un '
                              . 'alboroto en la plaza de ___. Ese mismo día se formó una ___ de '
                              . 'gobierno propia. No fue el final: la guerra duró ___ años más.',
                    'huecos' => ['criollos', 'Bogotá', 'junta', 'nueve'],
                    'extra'  => ['españoles', 'Cartagena', 'dos'],
                ],
                [
                    'titulo' => 'La Gran Colombia',
                    'texto'  => 'Tras la victoria en la batalla de ___ en 1819, Simón ___ impulsó la '
                              . 'creación de un solo país con Colombia, Venezuela, Ecuador y ___. '
                              . 'Duró poco: las enormes ___ y las diferencias políticas la disolvieron '
                              . 'en 1831.',
                    'huecos' => ['Boyacá', 'Bolívar', 'Panamá', 'distancias'],
                    'extra'  => ['Santander', 'Perú', 'montañas'],
                ],
            ])),

        est('Desafío libertador', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('¿En qué batalla se selló la independencia en 1819?', ['Batalla de Boyacá', 'Batalla de Bolívar', 'Batalla del Pantano'], 'Batalla de Boyacá'),
            reto('¿Qué países formaron la Gran Colombia?',   ['Colombia, Venezuela, Ecuador y Panamá', 'Solo Colombia', 'Colombia y Perú'], 'Colombia, Venezuela, Ecuador y Panamá'),
            reto('¿Por qué se disolvió la Gran Colombia?',    ['Por diferencias políticas y las distancias', 'Por una guerra externa', 'Por el clima'], 'Por diferencias políticas y las distancias'),
            reto('¿Qué defendía el federalismo?',            ['Más autonomía para cada región', 'Un solo gobierno central fuerte', 'La monarquía'], 'Más autonomía para cada región'),
            reto('¿Qué defendía el centralismo?',            ['Un gobierno central fuerte', 'Regiones independientes', 'No gobernar'], 'Un gobierno central fuerte'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · ECONOMÍA Y SOCIEDAD
// =====================================================================

[
    'slug'  => 'los-oficios-y-el-trabajo',
    'title' => 'Los oficios y el trabajo',
    'description' => 'Qué hace cada persona en la comunidad y por qué todos los trabajos importan.',
    'objective' => 'Reconocer distintos oficios, sus herramientas y su aporte a la vida en común.',
    'icon' => '🧰', 'nivel' => 'primaria-inicial', 'bloque' => 'economia-y-sociedad',
    'duracion' => 11, 'tags' => ['oficios', 'convivencia', 'vocabulario'],
    'estaciones' => [

        est('Cada oficio, su herramienta', 'Une el trabajo con lo que usa', '🔗', 'emparejar', [
            ['e' => '🔨', 'w' => 'Carpintero'],
            ['e' => '🩺', 'w' => 'Médico'],
            ['e' => '🚒', 'w' => 'Bombero'],
            ['e' => '✂️', 'w' => 'Peluquero'],
            ['e' => '🚜', 'w' => 'Agricultor'],
            ['e' => '🎨', 'w' => 'Pintor'],
        ]),

        est('¿A quién necesito?', 'Elige el oficio correcto', '🤔', 'opcion_multiple', [
            omp('Se dañó el techo de mi casa. ¿A quién llamo?', ['Al albañil', 'Al panadero', 'Al piloto'], 'Al albañil'),
            omp('Mi mascota está enferma. ¿A quién llevo?',     ['Al veterinario', 'Al mecánico', 'Al chef'], 'Al veterinario', '🐕'),
            omp('Quiero aprender a leer mejor. ¿Quién me ayuda?', ['El profesor', 'El bombero', 'El conductor'], 'El profesor'),
            omp('Se dañó el carro. ¿A quién llamo?',            ['Al mecánico', 'Al dentista', 'Al agricultor'], 'Al mecánico'),
            omp('¿Quién cultiva los alimentos que comemos?',    ['El agricultor', 'El piloto', 'El arquitecto'], 'El agricultor', '👨‍🌾'),
        ]),

        est('Todos hacemos falta', 'Ningún trabajo sobra', '🤝', 'opcion_multiple', [
            omp('¿Hay trabajos más importantes que otros?', ['Todos aportan algo necesario', 'Sí, solo los de oficina', 'Sí, solo los que pagan más'], 'Todos aportan algo necesario'),
            omp('¿Puede una mujer ser ingeniera?',          ['Sí, claro', 'No', 'Solo si es alta'], 'Sí, claro'),
            omp('¿Puede un hombre ser enfermero?',          ['Sí, claro', 'No', 'Solo de noche'], 'Sí, claro'),
            omp('¿Qué es el trabajo en equipo?',            ['Varias personas colaborando por un mismo fin', 'Trabajar solo', 'Mandar a otros'], 'Varias personas colaborando por un mismo fin'),
            omp('¿Qué se necesita para hacer bien un trabajo?', ['Aprender y esforzarse', 'Solo suerte', 'Nada'], 'Aprender y esforzarse'),
        ]),

        est('Adivina el oficio', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Trabaja en un hospital?', 'Responde rápido', [
                ['e' => '👩‍⚕️', 'n' => 'Enfermera',  'ok' => true],
                ['e' => '👨‍🚒', 'n' => 'Bombero',    'ok' => false],
                ['e' => '🩺', 'n' => 'Médico',      'ok' => true],
                ['e' => '👨‍🌾', 'n' => 'Agricultor', 'ok' => false],
                ['e' => '💊', 'n' => 'Farmaceuta',  'ok' => true],
                ['e' => '✈️', 'n' => 'Piloto',      'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'de-donde-vienen-las-cosas',
    'title' => '¿De dónde vienen las cosas?',
    'description' => 'Los tres sectores de la economía: sacar, transformar y llevar hasta nosotros.',
    'objective' => 'Distinguir los sectores económicos y seguir el recorrido de un producto hasta el consumidor.',
    'icon' => '🏭', 'nivel' => 'primaria-superior', 'bloque' => 'economia-y-sociedad',
    'duracion' => 14, 'tags' => ['comprension', 'clasificacion', 'colombia'],
    'estaciones' => [

        est('Los tres sectores', 'Cada uno con su papel', '🗂️', 'opcion_multiple', [
            omp('¿Qué hace el sector primario?',    ['Obtiene recursos de la naturaleza', 'Fabrica productos', 'Vende servicios'], 'Obtiene recursos de la naturaleza'),
            omp('¿Qué hace el sector secundario?',  ['Transforma la materia prima', 'Cultiva', 'Enseña'], 'Transforma la materia prima'),
            omp('¿Qué hace el sector terciario?',   ['Presta servicios', 'Extrae minerales', 'Fabrica autos'], 'Presta servicios'),
            omp('La pesca pertenece al sector…',    ['Primario', 'Secundario', 'Terciario'], 'Primario', '🎣'),
            omp('Un profesor pertenece al sector…', ['Terciario', 'Primario', 'Secundario'], 'Terciario', '👩‍🏫'),
        ]),

        est('Del campo a la mesa', 'El recorrido de un producto', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena el recorrido del pan',
            'items' => ['Se siembra el trigo', 'Se cosecha', 'Se muele para hacer harina', 'Se hornea el pan', 'Se vende en la tienda'],
        ]),

        est('La economía de Colombia', 'Qué produce el país', '🇨🇴', 'opcion_multiple', [
            omp('¿Qué producto agrícola es famoso de Colombia?', ['El café', 'El trigo', 'La uva'], 'El café', '☕'),
            omp('¿Qué mineral se extrae mucho en Colombia?',     ['El carbón', 'El uranio', 'El diamante'], 'El carbón'),
            omp('¿Qué flor exporta Colombia al mundo?',          ['Las rosas y claveles', 'El tulipán', 'El loto'], 'Las rosas y claveles', '🌹'),
            omp('¿Qué es exportar?',                             ['Vender productos a otros países', 'Comprar de afuera', 'Fabricar'], 'Vender productos a otros países'),
            omp('¿Qué es importar?',                             ['Comprar productos de otros países', 'Vender afuera', 'Sembrar'], 'Comprar productos de otros países'),
        ]),

        est('Consumo responsable', 'Lo que compramos tiene consecuencias', '🛒', 'opcion_multiple', [
            omp('¿Qué es consumir de forma responsable?',   ['Comprar lo necesario y pensar en el impacto', 'Comprar todo lo que veo', 'No comprar nunca'], 'Comprar lo necesario y pensar en el impacto'),
            omp('¿Qué es una necesidad?',                   ['Algo indispensable, como comer', 'Algo que quiero pero no necesito', 'Un lujo'], 'Algo indispensable, como comer'),
            omp('¿Qué es un deseo?',                        ['Algo que quiero pero puedo vivir sin ello', 'Algo indispensable', 'Una obligación'], 'Algo que quiero pero puedo vivir sin ello'),
            omp('¿Qué impacto tiene producir muchas cosas?', ['Usa recursos naturales y genera residuos', 'Ninguno', 'Solo bueno'], 'Usa recursos naturales y genera residuos'),
            omp('¿Qué es el ahorro?',                       ['Guardar parte de lo que se tiene para después', 'Gastarlo todo', 'Pedir prestado'], 'Guardar parte de lo que se tiene para después', '🐷'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'mi-municipio',
    'title' => 'Mi municipio',
    'description' => 'Cómo se organiza el lugar donde vivo: alcalde, servicios y vida en común.',
    'objective' => 'Reconocer la organización del municipio y los servicios que presta a sus habitantes.',
    'icon' => '🏘️', 'nivel' => 'primaria-media', 'bloque' => 'economia-y-sociedad',
    'duracion' => 12, 'tags' => ['ciudadania', 'colombia', 'convivencia'],
    'estaciones' => [

        est('Quién gobierna qué', 'De lo cercano a lo lejano', '🏛️', 'opcion_multiple', [
            omp('¿Quién gobierna un municipio?',      ['El alcalde', 'El gobernador', 'El presidente'], 'El alcalde'),
            omp('¿Quién gobierna un departamento?',   ['El gobernador', 'El alcalde', 'Un juez'], 'El gobernador'),
            omp('¿Quién gobierna el país?',           ['El presidente', 'El alcalde', 'El rector'], 'El presidente'),
            omp('¿Cómo se llega a ser alcalde?',      ['Por elección de los ciudadanos', 'Por herencia', 'Por sorteo'], 'Por elección de los ciudadanos'),
            omp('¿Qué es el concejo municipal?',      ['Un grupo elegido que aprueba las normas del municipio', 'La policía', 'Un colegio'], 'Un grupo elegido que aprueba las normas del municipio'),
        ]),

        est('Los servicios públicos', 'Lo que llega a cada casa', '🚰', 'opcion_multiple', [
            omp('¿Cuál es un servicio público?',      ['El agua potable', 'Un juguete', 'Una bicicleta'], 'El agua potable'),
            omp('¿Quién debe cuidar los parques?',    ['El municipio y todos los vecinos', 'Nadie', 'Solo los niños'], 'El municipio y todos los vecinos'),
            omp('¿Qué hace la empresa de aseo?',      ['Recoge y trata los residuos', 'Vende comida', 'Enseña'], 'Recoge y trata los residuos', '🚛'),
            omp('¿Para qué sirven los impuestos?',    ['Para pagar los servicios y obras de todos', 'Para castigar', 'Para nada'], 'Para pagar los servicios y obras de todos'),
            omp('Si se daña una calle, ¿a quién se avisa?', ['A la alcaldía', 'A nadie', 'Al colegio'], 'A la alcaldía'),
        ]),

        est('Vivir en la ciudad o en el campo', 'Dos formas de vida', '🌆', 'opcion_multiple', [
            omp('¿Qué caracteriza a la zona rural?',  ['Menos población y más actividad agrícola', 'Muchos edificios', 'Mucho tráfico'], 'Menos población y más actividad agrícola'),
            omp('¿Qué caracteriza a la zona urbana?', ['Más población y más servicios concentrados', 'Solo cultivos', 'Nada'], 'Más población y más servicios concentrados'),
            omp('¿De dónde viene la comida de la ciudad?', ['Del campo', 'De las fábricas solamente', 'De ninguna parte'], 'Del campo'),
            omp('¿Qué es la migración del campo a la ciudad?', ['Gente que se traslada buscando trabajo o estudio', 'Un viaje de vacaciones', 'Un deporte'], 'Gente que se traslada buscando trabajo o estudio'),
            omp('¿Es mejor la ciudad que el campo?',  ['Ninguna es mejor: son distintas', 'La ciudad', 'El campo'], 'Ninguna es mejor: son distintas'),
        ]),

        est('Desafío del municipio', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Cuántos departamentos tiene Colombia?',    ['32', '12', '50'], '32'),
            reto('¿Qué es la capital de un departamento?',    ['La ciudad donde está su gobierno', 'La más bonita', 'La más grande siempre'], 'La ciudad donde está su gobierno'),
            reto('¿Qué es un corregimiento?',                 ['Una división dentro de un municipio', 'Un país', 'Un continente'], 'Una división dentro de un municipio'),
            reto('¿Qué puedo hacer yo por mi municipio?',     ['Cuidar lo público y participar', 'Nada hasta ser adulto', 'Solo pagar impuestos'], 'Cuidar lo público y participar'),
            reto('¿Por qué importa conocer dónde vivo?',      ['Para entender y mejorar mi entorno', 'Para un examen', 'No importa'], 'Para entender y mejorar mi entorno'),
        ]),
    ],
],

[
    'slug'  => 'patrimonio-y-cultura',
    'title' => 'Patrimonio y cultura',
    'description' => 'Lo que heredamos y hay que cuidar: monumentos, tradiciones y lenguas.',
    'objective' => 'Reconocer el patrimonio cultural y natural y valorar su conservación.',
    'icon' => '🏛️', 'nivel' => 'primaria-superior', 'bloque' => 'linea-del-tiempo',
    'duracion' => 12, 'tags' => ['cultura', 'colombia', 'historia'],
    'estaciones' => [

        est('¿Qué es el patrimonio?', 'Lo que se hereda de todos', '🗝️', 'opcion_multiple', [
            omp('¿Qué es el patrimonio cultural?',       ['Lo que una comunidad hereda y decide conservar', 'Un tipo de dinero', 'Una ley'], 'Lo que una comunidad hereda y decide conservar'),
            omp('¿Cuál es patrimonio material?',         ['Una iglesia antigua', 'Una canción', 'Una receta'], 'Una iglesia antigua'),
            omp('¿Cuál es patrimonio inmaterial?',       ['Una danza tradicional', 'Un monumento', 'Un puente'], 'Una danza tradicional'),
            omp('¿Es patrimonio un parque natural?',     ['Sí, patrimonio natural', 'No', 'Solo si es antiguo'], 'Sí, patrimonio natural'),
            omp('¿Por qué se protege el patrimonio?',    ['Porque si se pierde no se recupera', 'Porque es caro', 'Por turismo solamente'], 'Porque si se pierde no se recupera'),
        ]),

        est('Patrimonio de Colombia', 'Lugares y tradiciones', '🇨🇴', 'opcion_multiple', [
            omp('¿Qué ciudad tiene murallas coloniales famosas?', ['Cartagena', 'Medellín', 'Cali'], 'Cartagena', '🏰'),
            omp('¿Qué parque arqueológico tiene estatuas de piedra?', ['San Agustín', 'Tayrona', 'Amacayacu'], 'San Agustín', '🗿'),
            omp('¿Qué carnaval es patrimonio de la humanidad?', ['El de Barranquilla', 'El de Bogotá', 'El de Cali'], 'El de Barranquilla', '🎭'),
            omp('¿Qué paisaje cafetero es patrimonio mundial?', ['El del Eje Cafetero', 'El del Amazonas', 'El de la Guajira'], 'El del Eje Cafetero', '☕'),
            omp('¿Qué es Ciudad Perdida?',               ['Una ciudad tayrona en la Sierra Nevada', 'Un parque de diversiones', 'Un museo moderno'], 'Una ciudad tayrona en la Sierra Nevada'),
        ]),

        est('Lenguas y saberes', 'También se hereda lo que no se toca', '🗣️', 'opcion_multiple', [
            omp('¿Cuántas lenguas indígenas se hablan en Colombia?', ['Más de sesenta', 'Ninguna', 'Dos'], 'Más de sesenta'),
            omp('¿Qué pasa cuando muere el último hablante de una lengua?', ['Se pierde una forma de ver el mundo', 'Nada', 'Se traduce sola'], 'Se pierde una forma de ver el mundo'),
            omp('¿Es el español la única lengua oficial de Colombia?', ['No, las lenguas indígenas son oficiales en sus territorios', 'Sí', 'Solo el inglés también'], 'No, las lenguas indígenas son oficiales en sus territorios'),
            omp('¿Qué es un saber tradicional?',         ['Conocimiento que pasa de generación en generación', 'Un libro', 'Una ley'], 'Conocimiento que pasa de generación en generación'),
            omp('¿Cómo puedo cuidar el patrimonio?',     ['Conociéndolo, respetándolo y no dañándolo', 'Encerrándolo', 'Ignorándolo'], 'Conociéndolo, respetándolo y no dañándolo'),
        ]),

        est('Sopa del patrimonio', 'Encuentra las palabras', '🔤', 'sopa_letras',
            sopa(['CARNAVAL', 'MURALLA', 'CULTURA', 'DANZA', 'MUSEO'], 11)),
    ],
],

[
    'slug'  => 'los-simbolos-patrios',
    'title' => 'Los símbolos patrios',
    'description' => 'Bandera, escudo e himno: qué representan y por qué se respetan.',
    'objective' => 'Identificar los símbolos patrios de Colombia y su significado.',
    'icon' => '🎌', 'nivel' => 'primaria-inicial', 'bloque' => 'democracia-y-derechos',
    'duracion' => 10, 'tags' => ['colombia', 'cultura', 'ciudadania'],
    'estaciones' => [

        est('La bandera', 'Tres franjas, tres significados', '🇨🇴', 'opcion_multiple', [
            omp('¿Cuántos colores tiene la bandera de Colombia?', ['Tres', 'Dos', 'Cinco'], 'Tres'),
            omp('¿Cuál es la franja más ancha?',       ['La amarilla', 'La azul', 'La roja'], 'La amarilla'),
            omp('¿Qué representa el amarillo?',        ['La riqueza del suelo', 'La sangre', 'Los mares'], 'La riqueza del suelo'),
            omp('¿Qué representa el azul?',            ['Los mares y ríos', 'El oro', 'La sangre'], 'Los mares y ríos'),
            omp('¿Qué representa el rojo?',            ['La sangre de quienes lucharon por la libertad', 'El oro', 'El cielo'], 'La sangre de quienes lucharon por la libertad'),
        ]),

        est('El escudo y el himno', 'Los otros dos símbolos', '🛡️', 'opcion_multiple', [
            omp('¿Qué ave aparece en el escudo de Colombia?', ['El cóndor', 'La paloma', 'El águila'], 'El cóndor', '🦅'),
            omp('¿Qué se hace cuando suena el himno?', ['Se escucha de pie y en silencio', 'Se baila', 'Se habla'], 'Se escucha de pie y en silencio'),
            omp('¿Qué flor es el símbolo nacional?',   ['La orquídea', 'El girasol', 'La rosa'], 'La orquídea', '🌸'),
            omp('¿Qué árbol es el símbolo nacional?',  ['La palma de cera', 'El pino', 'El roble'], 'La palma de cera', '🌴'),
            omp('¿Por qué se respetan los símbolos patrios?', ['Representan a todo el país', 'Porque son bonitos', 'Por obligación sin más'], 'Representan a todo el país'),
        ]),

        est('Ordena la bandera', 'De arriba hacia abajo', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los colores de la bandera de Colombia',
            'items' => ['🟨 Amarillo', '🟦 Azul', '🟥 Rojo'],
        ]),

        est('Memoria patria', 'Encuentra las parejas', '🧠', 'memoria',
            ['🇨🇴', '🦅', '🌸', '🌴', '🎺', '🛡️']),
    ],
],


[
    'slug'  => 'los-continentes-y-oceanos',
    'title' => 'Los continentes y océanos',
    'description' => 'El mapa del mundo: dónde está cada continente y qué océano lo rodea.',
    'objective' => 'Localizar continentes y océanos y reconocer su relación con Colombia.',
    'icon' => '🌏', 'nivel' => 'primaria-media', 'bloque' => 'mapas-y-territorio',
    'duracion' => 12, 'tags' => ['geografia', 'mapas', 'observacion'],
    'estaciones' => [

        est('Los continentes', 'Las grandes masas de tierra', '🗺️', 'opcion_multiple', [
            omp('¿En qué continente está Colombia?',   ['América', 'Europa', 'Asia'], 'América'),
            omp('¿Cuál es el continente más grande?',  ['Asia', 'América', 'África'], 'Asia'),
            omp('¿Qué continente está cubierto de hielo?', ['Antártida', 'Oceanía', 'Europa'], 'Antártida', '🧊'),
            omp('¿En qué continente está Egipto?',     ['África', 'Asia', 'Europa'], 'África'),
            omp('¿En qué continente está Australia?',  ['Oceanía', 'Asia', 'África'], 'Oceanía'),
        ]),

        est('Los océanos', 'El agua que los separa', '🌊', 'opcion_multiple', [
            omp('¿Cuál es el océano más grande?',      ['El Pacífico', 'El Atlántico', 'El Índico'], 'El Pacífico'),
            omp('¿Qué océano separa América de Europa?', ['El Atlántico', 'El Pacífico', 'El Índico'], 'El Atlántico'),
            omp('¿Cuántos océanos se reconocen normalmente?', ['Cinco', 'Dos', 'Diez'], 'Cinco'),
            omp('¿Qué parte del planeta cubre el agua?', ['Cerca de tres cuartas partes', 'La mitad', 'Un cuarto'], 'Cerca de tres cuartas partes'),
            omp('¿Qué dos océanos bañan a Colombia?',  ['Pacífico y Atlántico', 'Índico y Pacífico', 'Solo el Atlántico'], 'Pacífico y Atlántico'),
        ]),

        est('América', 'Nuestro continente', '🌎', 'opcion_multiple', [
            omp('¿En cuántas partes se divide América?', ['Tres: del Norte, Central y del Sur', 'Dos', 'Cinco'], 'Tres: del Norte, Central y del Sur'),
            omp('¿En qué parte está Colombia?',        ['América del Sur', 'América del Norte', 'América Central'], 'América del Sur'),
            omp('¿Qué país está al norte de Colombia?', ['Panamá', 'Ecuador', 'Perú'], 'Panamá'),
            omp('¿Qué cordillera recorre América del Sur?', ['Los Andes', 'Los Alpes', 'El Himalaya'], 'Los Andes'),
            omp('¿Qué río es el más caudaloso de América?', ['El Amazonas', 'El Magdalena', 'El Orinoco'], 'El Amazonas'),
        ]),

        est('Sopa del mundo', 'Encuentra los continentes', '🔤', 'sopa_letras',
            sopa(['AMERICA', 'EUROPA', 'ASIA', 'AFRICA', 'OCEANIA'], 11)),
    ],
],

[
    'slug'  => 'convivir-en-comunidad',
    'title' => 'Convivir en comunidad',
    'description' => 'Servicios, espacios comunes y lo que cada quien aporta al lugar donde vive.',
    'objective' => 'Reconocer los aportes individuales al bienestar colectivo de una comunidad.',
    'icon' => '🏙️', 'nivel' => 'primaria-inicial', 'bloque' => 'democracia-y-derechos',
    'duracion' => 11, 'tags' => ['convivencia', 'ciudadania', 'oficios'],
    'estaciones' => [

        est('Los lugares de todos', 'Espacios comunes', '🏞️', 'opcion_multiple', [
            omp('¿Qué es un espacio público?',      ['Un lugar que puede usar toda la gente', 'La casa de alguien', 'Una tienda'], 'Un lugar que puede usar toda la gente'),
            omp('¿Cuál es un espacio público?',     ['El parque', 'Mi cuarto', 'Un carro'], 'El parque', '🏞️'),
            omp('¿Quién debe cuidar el parque?',    ['Todos los que lo usan', 'Nadie', 'Solo el alcalde'], 'Todos los que lo usan'),
            omp('¿Está bien dañar una banca del parque?', ['No, es de todos', 'Sí', 'Si está vieja, sí'], 'No, es de todos'),
            omp('¿Qué pasa si todos cuidamos lo común?', ['Todos vivimos mejor', 'Nada', 'Se daña igual'], 'Todos vivimos mejor'),
        ]),

        est('Quién ayuda en mi comunidad', 'Personas que cuidan de todos', '👮', 'emparejar', [
            ['e' => '👮', 'w' => 'Policía'],
            ['e' => '👨‍🚒', 'w' => 'Bombero'],
            ['e' => '👩‍⚕️', 'w' => 'Enfermera'],
            ['e' => '👩‍🏫', 'w' => 'Profesora'],
            ['e' => '🧹', 'w' => 'Personal de aseo'],
            ['e' => '🚌', 'w' => 'Conductor'],
        ]),

        est('Lo que yo aporto', 'También cuento', '🙋', 'opcion_multiple', [
            omp('¿Puede un niño aportar a su comunidad?', ['Sí, cuidando y ayudando', 'No hasta ser grande', 'Nunca'], 'Sí, cuidando y ayudando'),
            omp('¿Qué puedo hacer en el parque?',      ['No dejar basura', 'Rayar los juegos', 'Nada'], 'No dejar basura'),
            omp('Si veo a alguien perdido, ¿qué hago?', ['Aviso a un adulto', 'Lo ignoro', 'Me voy con él'], 'Aviso a un adulto'),
            omp('¿Qué es ser buen vecino?',            ['Respetar y ayudar a quien vive cerca', 'No hablar con nadie', 'Hacer ruido'], 'Respetar y ayudar a quien vive cerca'),
            omp('¿Sirve saludar a los vecinos?',       ['Sí, construye confianza', 'No', 'Solo en fiestas'], 'Sí, construye confianza'),
        ]),

        est('Buen ciudadano', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Ayuda a la comunidad?', 'Responde rápido', [
                ['e' => '🗑️', 'n' => 'Botar la basura en la caneca', 'ok' => true],
                ['e' => '🎨', 'n' => 'Rayar una pared ajena',        'ok' => false],
                ['e' => '🤝', 'n' => 'Ayudar a cruzar a un anciano', 'ok' => true],
                ['e' => '📢', 'n' => 'Poner música muy fuerte de noche', 'ok' => false],
                ['e' => '🌳', 'n' => 'Cuidar los árboles del barrio', 'ok' => true],
                ['e' => '💧', 'n' => 'Dejar una llave abierta',      'ok' => false],
            ])),
    ],
],


[
    'slug'  => 'crucigramas-de-colombia',
    'title' => 'Crucigramas de Colombia',
    'description' => 'Geografía, historia y ciudadanía del país, con pistas.',
    'objective' => 'Recuperar vocabulario de Ciencias Sociales a partir de definiciones.',
    'icon' => '🔠', 'nivel' => 'primaria-superior', 'bloque' => 'linea-del-tiempo',
    'duracion' => 15, 'tags' => ['colombia', 'historia', 'reto'],
    'estaciones' => [

        est('Crucigrama de la geografía', 'El territorio y sus formas', '🗺️', 'crucigrama',
            crucigrama([
                ['w' => 'REGION',     'pista' => 'Colombia tiene seis naturales'],
                ['w' => 'PARAMO',     'pista' => 'Ecosistema de montaña que guarda el agua'],
                ['w' => 'LLANURA',    'pista' => 'Terreno plano y extenso'],
                ['w' => 'RELIEVE',    'pista' => 'La forma de la superficie de la tierra'],
                ['w' => 'AMAZONIA',   'pista' => 'La región de la gran selva'],
                ['w' => 'CARIBE',     'pista' => 'La región de la costa norte'],
            ])),

        est('Crucigrama de la historia', 'De los primeros pobladores a hoy', '⏳', 'crucigrama',
            crucigrama([
                ['w' => 'MUISCA',     'pista' => 'Pueblo indígena que trabajaba el oro'],
                ['w' => 'BOLIVAR',    'pista' => 'El Libertador'],
                ['w' => 'FLORERO',    'pista' => 'El objeto del 20 de julio de 1810'],
                ['w' => 'BOYACA',     'pista' => 'Batalla que selló la independencia en 1819'],
                ['w' => 'COLONIA',    'pista' => 'Periodo en que gobernaba la Corona española'],
                ['w' => 'MAIZ',       'pista' => 'Alimento americano que hoy se come en todo el mundo'],
            ])),

        est('Crucigrama de la ciudadanía', 'Cómo se organiza un país', '🏛️', 'crucigrama',
            crucigrama([
                ['w' => 'DEMOCRACIA', 'pista' => 'El poder del pueblo para decidir'],
                ['w' => 'VOTO',       'pista' => 'Con esto se elige y es secreto'],
                ['w' => 'ALCALDE',    'pista' => 'Gobierna un municipio'],
                ['w' => 'CONGRESO',   'pista' => 'Donde se hacen las leyes'],
                ['w' => 'DERECHO',    'pista' => 'Algo que corresponde a toda persona'],
                ['w' => 'DEBER',      'pista' => 'Lo que a cada uno le toca cumplir'],
            ])),

        est('Completa la historia', 'Coloca cada palabra en su hueco', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Lee la frase entera antes de elegir', [
                [
                    'titulo' => 'Los primeros pobladores',
                    'texto'  => 'Hace miles de años, grupos humanos llegaron a América cruzando el '
                              . 'estrecho de ___. Al principio eran ___: se movían siguiendo a los '
                              . 'animales. Cuando aprendieron a sembrar la tierra pudieron quedarse en '
                              . 'un sitio y se volvieron ___, y así nacieron las primeras ___.',
                    'huecos' => ['Bering', 'nómadas', 'sedentarios', 'aldeas'],
                    'extra'  => ['Panamá', 'agricultores', 'ciudades'],
                ],
                [
                    'titulo' => 'Las regiones y la economía',
                    'texto'  => 'El sector ___ saca los recursos de la naturaleza, como el café o el '
                              . 'carbón. El sector ___ los transforma en productos. El sector ___ '
                              . 'presta servicios, como enseñar o curar. Vender esos productos a otros '
                              . 'países se llama ___.',
                    'huecos' => ['primario', 'secundario', 'terciario', 'exportar'],
                    'extra'  => ['inicial', 'importar'],
                ],
            ])),
    ],
],

],

'reasignar' => [],

];
