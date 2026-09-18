<?php
/**
 * ciencias.php — Ciencias
 *
 * Tres bloques: lo vivo, lo que nos rodea a lo grande, y lo que se puede
 * probar. El orden no es casual — un niño reconoce un perro mucho antes
 * que un planeta, y un planeta antes que un estado de la materia.
 *
 * Aquí no se dan consejos de salud (eso es «Vida y Bienestar»): el cuerpo
 * se trata como objeto de estudio, no como algo que cuidar.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'ciencias',
    'name'       => 'Ciencias',
    'tagline'    => 'Observar el mundo, preguntarse por qué y comprobarlo',
    'icon'       => '🔬',
    'color'      => '#26a69a',
    'sort_order' => 5,
],

'bloques' => [
    ['slug' => 'seres-vivos', 'name' => 'Seres Vivos', 'icon' => '🌱', 'sort_order' => 1,
     'description' => 'Animales, plantas y el cuerpo humano por dentro.'],
    ['slug' => 'la-tierra-y-el-cielo', 'name' => 'La Tierra y el Cielo', 'icon' => '🌍', 'sort_order' => 2,
     'description' => 'El agua, el clima, los planetas y lo que hay más allá.'],
    ['slug' => 'experimentos-y-materia', 'name' => 'Experimentos y Materia', 'icon' => '⚗️', 'sort_order' => 3,
     'description' => 'Estados, fuerzas y cosas que se pueden comprobar.'],
],

'reasignar' => [
    'laboratorio-de-ciencias' => 'experimentos-y-materia',
    'planeta-del-espacio'     => 'la-tierra-y-el-cielo',
],

'actividades' => [


// ── SERES VIVOS ──────────────────────────────────────────────────────

[
    'slug'  => 'animales-y-sus-crias',
    'title' => 'Animales y sus crías',
    'description' => 'Cómo nacen, qué comen y dónde viven los animales.',
    'objective' => 'Relacionar animales con sus crías y clasificarlos por alimentación y hábitat.',
    'icon' => '🐣', 'nivel' => 'preescolar', 'bloque' => 'seres-vivos',
    'duracion' => 10, 'tags' => ['observacion', 'clasificacion', 'vocabulario'],
    'estaciones' => [

        est('¿Cómo se llama su cría?', 'Cada animal joven tiene nombre', '🐣', 'opcion_multiple', [
            omp('La cría del perro es el…',   ['Cachorro', 'Potro', 'Ternero'], 'Cachorro', '🐕'),
            omp('La cría del gato es el…',    ['Gatito', 'Pollito', 'Cordero'], 'Gatito', '🐈'),
            omp('La cría de la gallina es el…', ['Pollito', 'Cachorro', 'Renacuajo'], 'Pollito', '🐔'),
            omp('La cría de la vaca es el…',  ['Ternero', 'Potro', 'Gatito'], 'Ternero', '🐄'),
            omp('La cría de la rana es el…',  ['Renacuajo', 'Pollito', 'Cachorro'], 'Renacuajo', '🐸'),
            omp('La cría del caballo es el…', ['Potro', 'Ternero', 'Cordero'], 'Potro', '🐴'),
        ]),

        est('¿Qué come?', 'Unos comen plantas y otros animales', '🍽️', 'opcion_multiple', [
            omp('La vaca come…',    ['Plantas', 'Carne', 'Piedras'], 'Plantas', '🐄'),
            omp('El león come…',    ['Carne', 'Plantas', 'Papel'], 'Carne', '🦁'),
            omp('El conejo come…',  ['Plantas', 'Carne', 'Insectos solamente'], 'Plantas', '🐰'),
            omp('El oso come…',     ['Plantas y animales', 'Solo plantas', 'Solo piedras'], 'Plantas y animales', '🐻'),
            omp('El animal que solo come plantas se llama…', ['Herbívoro', 'Carnívoro', 'Omnívoro'], 'Herbívoro', '🌿'),
            omp('El animal que come de todo se llama…', ['Omnívoro', 'Herbívoro', 'Carnívoro'], 'Omnívoro', '🍽️'),
        ]),

        est('Toca los que vuelan', 'Solo los que pueden volar', '🦋', 'seleccion_imagenes',
            conTitulo('Toca todos los animales que vuelan', 'Deja fuera los que no', [
                ['e' => '🦅', 'n' => 'Águila',   'ok' => true],
                ['e' => '🦋', 'n' => 'Mariposa', 'ok' => true],
                ['e' => '🐝', 'n' => 'Abeja',    'ok' => true],
                ['e' => '🦇', 'n' => 'Murciélago', 'ok' => true],
                ['e' => '🐟', 'n' => 'Pez',      'ok' => false],
                ['e' => '🐘', 'n' => 'Elefante', 'ok' => false],
                ['e' => '🐍', 'n' => 'Serpiente', 'ok' => false],
                ['e' => '🐢', 'n' => 'Tortuga',  'ok' => false],
            ])),

        est('Une animal y cría', 'Empareja las familias', '🔗', 'emparejar', [
            ['e' => '🐕', 'w' => 'Cachorro'],
            ['e' => '🐔', 'w' => 'Pollito'],
            ['e' => '🐄', 'w' => 'Ternero'],
            ['e' => '🐴', 'w' => 'Potro'],
            ['e' => '🐸', 'w' => 'Renacuajo'],
            ['e' => '🐑', 'w' => 'Cordero'],
        ]),
    ],
],

[
    'slug'  => 'las-plantas',
    'title' => 'Las plantas',
    'description' => 'Sus partes, qué necesitan para vivir y cómo crecen.',
    'objective' => 'Nombrar las partes de una planta y explicar qué necesita para desarrollarse.',
    'icon' => '🌱', 'nivel' => 'preescolar', 'bloque' => 'seres-vivos',
    'duracion' => 10, 'tags' => ['observacion', 'secuencias', 'vocabulario'],
    'estaciones' => [

        est('Las partes de la planta', 'Cada parte hace su trabajo', '🌿', 'opcion_multiple', [
            omp('¿Qué parte sostiene la planta bajo tierra?', ['La raíz', 'La hoja', 'La flor'], 'La raíz', '🌱'),
            omp('¿Qué parte sube el agua desde la raíz?',     ['El tallo', 'La flor', 'El fruto'], 'El tallo', '🌿'),
            omp('¿Qué parte recibe la luz del sol?',          ['Las hojas', 'La raíz', 'La semilla'], 'Las hojas', '🍃'),
            omp('¿De qué parte sale el fruto?',               ['De la flor', 'De la raíz', 'Del tallo'], 'De la flor', '🌸'),
            omp('¿Qué hay dentro del fruto?',                 ['Semillas', 'Hojas', 'Raíces'], 'Semillas', '🍎'),
        ]),

        est('¿Qué necesita para vivir?', 'Sin esto no crece', '💧', 'opcion_multiple', [
            omp('¿Qué necesita una planta para vivir?', ['Agua, luz y aire', 'Solo agua', 'Nada'], 'Agua, luz y aire', '🌞'),
            omp('Si una planta no recibe luz…',    ['Se pone amarilla y débil', 'Crece más rápido', 'No pasa nada'], 'Se pone amarilla y débil', '🌑'),
            omp('Si se riega demasiado…',          ['La raíz se pudre', 'Crece el doble', 'Da flores'], 'La raíz se pudre', '💦'),
            omp('Las plantas toman su alimento del…', ['Sol, el agua y el aire', 'Refrigerador', 'Cielo únicamente'], 'Sol, el agua y el aire', '☀️'),
            omp('Las plantas ayudan a las personas porque…', ['Producen el oxígeno que respiramos', 'Hacen ruido', 'Dan electricidad'], 'Producen el oxígeno que respiramos', '🍃'),
        ]),

        est('Cómo crece una planta', 'Ordena el crecimiento', '🌳', 'ordenar_secuencia', [
            'title' => 'Ordena el crecimiento de una planta',
            'items' => ['🌰 Semilla', '🌱 Brote', '🪴 Tallo con hojas', '🌸 Flor', '🍎 Fruto'],
        ]),

        est('Une parte y función', 'Cada pieza tiene su tarea', '🔗', 'emparejar', [
            ['e' => '🌱', 'w' => 'Raíz'],
            ['e' => '🌿', 'w' => 'Tallo'],
            ['e' => '🍃', 'w' => 'Hoja'],
            ['e' => '🌸', 'w' => 'Flor'],
            ['e' => '🍎', 'w' => 'Fruto'],
        ]),
    ],
],

[
    'slug'  => 'el-cuerpo-por-dentro',
    'title' => 'El cuerpo por dentro',
    'description' => 'Los órganos y los sistemas que te mantienen funcionando.',
    'objective' => 'Identificar los órganos principales y el sistema al que pertenecen.',
    'icon' => '🫀', 'nivel' => 'primaria-media', 'bloque' => 'seres-vivos',
    'duracion' => 15, 'tags' => ['cuerpo', 'clasificacion', 'comprension'],
    'estaciones' => [

        est('¿Qué hace cada órgano?', 'Cada uno tiene un trabajo', '🫀', 'opcion_multiple', [
            omp('¿Qué hace el corazón?',   ['Bombea la sangre', 'Piensa', 'Digiere'], 'Bombea la sangre', '🫀'),
            omp('¿Qué hacen los pulmones?', ['Toman el oxígeno del aire', 'Bombean sangre', 'Filtran'], 'Toman el oxígeno del aire', '🫁'),
            omp('¿Qué hace el cerebro?',   ['Controla todo el cuerpo', 'Respira', 'Digiere'], 'Controla todo el cuerpo', '🧠'),
            omp('¿Qué hace el estómago?',  ['Digiere la comida', 'Bombea sangre', 'Piensa'], 'Digiere la comida', '🍽️'),
            omp('¿Qué hacen los riñones?', ['Filtran la sangre', 'Respiran', 'Ven'], 'Filtran la sangre', '💧'),
            omp('¿Cuál es el órgano más grande del cuerpo?', ['La piel', 'El corazón', 'El hígado'], 'La piel', '🧍'),
        ]),

        est('Los sistemas', 'Los órganos trabajan en equipo', '⚙️', 'opcion_multiple', [
            omp('El corazón pertenece al sistema…',   ['Circulatorio', 'Digestivo', 'Nervioso'], 'Circulatorio', '🫀'),
            omp('Los pulmones pertenecen al sistema…', ['Respiratorio', 'Circulatorio', 'Óseo'], 'Respiratorio', '🫁'),
            omp('El estómago pertenece al sistema…',  ['Digestivo', 'Nervioso', 'Respiratorio'], 'Digestivo', '🍽️'),
            omp('El cerebro pertenece al sistema…',   ['Nervioso', 'Digestivo', 'Circulatorio'], 'Nervioso', '🧠'),
            omp('Los huesos forman el sistema…',      ['Óseo', 'Digestivo', 'Respiratorio'], 'Óseo', '🦴'),
            omp('¿Cuántos huesos tiene aproximadamente un adulto?', [106, 156, 206, 306], 206),
        ]),

        est('El camino de la comida', 'Ordena la digestión', '🍽️', 'ordenar_secuencia', [
            'title' => 'Ordena el camino que recorre la comida',
            'items' => ['👄 Boca', '🫄 Esófago', '🍽️ Estómago', '🌀 Intestino delgado', '🚪 Intestino grueso'],
        ]),

        est('Reto del cuerpo', 'Junta todo lo aprendido', '🏆', 'desafio_final', [
            reto('Cuando corres, el corazón late más rápido porque…',
                 ['Los músculos necesitan más oxígeno', 'Tienes miedo', 'Hace calor'],
                 'Los músculos necesitan más oxígeno'),
            reto('¿Qué transporta el oxígeno por el cuerpo?',
                 ['La sangre', 'Los huesos', 'La piel'], 'La sangre'),
            reto('Si te tapas la nariz y la boca no puedes…',
                 ['Tomar oxígeno', 'Pensar', 'Ver'], 'Tomar oxígeno'),
            reto('Los huesos sirven para…',
                 ['Sostener el cuerpo y proteger órganos', 'Digerir', 'Respirar'],
                 'Sostener el cuerpo y proteger órganos'),
        ]),
    ],
],

[
    'slug'  => 'cadenas-alimenticias',
    'title' => 'Cadenas alimenticias',
    'description' => 'Quién come a quién y qué pasa cuando falta un eslabón.',
    'objective' => 'Construir cadenas alimenticias y prever el efecto de alterar un eslabón.',
    'icon' => '🔗', 'nivel' => 'primaria-media', 'bloque' => 'seres-vivos',
    'duracion' => 15, 'tags' => ['logica', 'secuencias', 'comprension'],
    'estaciones' => [

        est('Los eslabones', 'Cada ser vivo ocupa un lugar', '🌿', 'opcion_multiple', [
            omp('Las plantas son…',        ['Productores', 'Consumidores', 'Descomponedores'], 'Productores', '🌿'),
            omp('Un animal que come plantas es un consumidor…', ['Primario', 'Secundario', 'Terciario'], 'Primario', '🐰'),
            omp('Un animal que come a otro animal es un consumidor…', ['Secundario', 'Primario', 'Productor'], 'Secundario', '🦊'),
            omp('Los hongos y bacterias que descomponen restos son…', ['Descomponedores', 'Productores', 'Depredadores'], 'Descomponedores', '🍄'),
            omp('¿De dónde viene la energía de toda la cadena?', ['Del sol', 'Del agua', 'Del viento'], 'Del sol', '☀️'),
        ]),

        est('Arma la cadena', 'Del productor al último', '➡️', 'ordenar_secuencia', [
            'title' => 'Ordena la cadena, del productor al último consumidor',
            'items' => ['🌿 Hierba', '🦗 Saltamontes', '🐸 Rana', '🐍 Serpiente', '🦅 Águila'],
        ]),

        est('Si falta un eslabón', 'Todo está conectado', '⚠️', 'opcion_multiple', [
            omp('Si desaparecen todas las plantas, los herbívoros…', ['Se quedan sin alimento', 'Comen más', 'No les afecta'], 'Se quedan sin alimento', '🌿'),
            omp('Si desaparece el depredador, sus presas…', ['Se multiplican demasiado', 'Desaparecen', 'No cambian'], 'Se multiplican demasiado', '🐰'),
            omp('Si no hubiera descomponedores…', ['Se acumularían los restos', 'Todo estaría más limpio', 'Nada cambiaría'], 'Se acumularían los restos', '🍄'),
            omp('Un ecosistema está en equilibrio cuando…', ['Cada eslabón se mantiene', 'Sobra un animal', 'Falta comida'], 'Cada eslabón se mantiene', '⚖️'),
        ]),

        est('Cadena marina', 'Otra cadena, mismo principio', '🌊', 'ordenar_secuencia', [
            'title' => 'Ordena la cadena del mar',
            'items' => ['🦠 Fitoplancton', '🦐 Camarón', '🐟 Pez pequeño', '🐠 Pez grande', '🦈 Tiburón'],
        ]),
    ],
],


// ── LA TIERRA Y EL CIELO ─────────────────────────────────────────────

[
    'slug'  => 'el-clima-y-el-agua',
    'title' => 'El clima y el agua',
    'description' => 'El ciclo del agua, la lluvia y por qué cambia el tiempo.',
    'objective' => 'Describir el ciclo del agua y relacionar sus fases con los fenómenos del clima.',
    'icon' => '🌧️', 'nivel' => 'primaria-inicial', 'bloque' => 'la-tierra-y-el-cielo',
    'duracion' => 12, 'tags' => ['secuencias', 'observacion', 'comprension'],
    'estaciones' => [

        est('El ciclo del agua', 'El agua da vueltas sin acabarse', '💧', 'ordenar_secuencia', [
            'title' => 'Ordena el ciclo del agua',
            'items' => [
                '☀️ El sol calienta el agua',
                '💨 El agua se evapora',
                '☁️ Se forman las nubes',
                '🌧️ Llueve',
                '🏞️ El agua vuelve a ríos y mares',
            ],
        ]),

        est('¿Cómo se llama?', 'Cada paso tiene su nombre', '🔤', 'opcion_multiple', [
            omp('Cuando el agua se convierte en vapor se llama…', ['Evaporación', 'Condensación', 'Precipitación'], 'Evaporación', '💨'),
            omp('Cuando el vapor forma nubes se llama…',   ['Condensación', 'Evaporación', 'Filtración'], 'Condensación', '☁️'),
            omp('Cuando cae lluvia se llama…',             ['Precipitación', 'Evaporación', 'Condensación'], 'Precipitación', '🌧️'),
            omp('El agua sólida es…',                      ['Hielo', 'Vapor', 'Lluvia'], 'Hielo', '🧊'),
            omp('El agua en forma de gas es…',             ['Vapor', 'Hielo', 'Nieve'], 'Vapor', '💨'),
        ]),

        est('El tiempo de hoy', 'Reconoce los fenómenos', '🌦️', 'opcion_multiple', [
            omp('¿Qué instrumento mide la temperatura?', ['El termómetro', 'La brújula', 'La regla'], 'El termómetro', '🌡️'),
            omp('El arcoíris aparece cuando hay…',       ['Sol y lluvia a la vez', 'Solo nieve', 'Solo viento'], 'Sol y lluvia a la vez', '🌈'),
            omp('El trueno es el sonido de…',            ['El rayo', 'La lluvia', 'El viento'], 'El rayo', '⚡'),
            omp('En Colombia hablamos de temporada seca y…', ['De lluvias', 'De nieve', 'De otoño'], 'De lluvias', '🌧️'),
            omp('¿Por qué en las montañas altas hace más frío?', ['Están más lejos del calor de la superficie', 'Hay más sol', 'Hay menos agua'], 'Están más lejos del calor de la superficie', '🏔️'),
        ]),
    ],
],

[
    'slug'  => 'el-sistema-solar',
    'title' => 'El sistema solar',
    'description' => 'Los ocho planetas, el sol y por qué tenemos día y noche.',
    'objective' => 'Ordenar los planetas y explicar el día, la noche y las estaciones.',
    'icon' => '🪐', 'nivel' => 'primaria-inicial', 'bloque' => 'la-tierra-y-el-cielo',
    'duracion' => 12, 'tags' => ['secuencias', 'memoria', 'comprension'],
    'estaciones' => [

        est('Los planetas en orden', 'Del más cercano al sol al más lejano', '🪐', 'ordenar_secuencia', [
            'title' => 'Ordena los planetas desde el sol',
            'items' => ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Júpiter', 'Saturno', 'Urano', 'Neptuno'],
        ]),

        est('¿Qué sabes de ellos?', 'Cada planeta tiene lo suyo', '🌌', 'opcion_multiple', [
            omp('¿Cuál es el planeta más grande?',        ['Júpiter', 'Tierra', 'Marte'], 'Júpiter', '🪐'),
            omp('¿Cuál es el planeta más cercano al sol?', ['Mercurio', 'Venus', 'Tierra'], 'Mercurio', '☀️'),
            omp('¿Qué planeta se conoce como el planeta rojo?', ['Marte', 'Venus', 'Saturno'], 'Marte', '🔴'),
            omp('¿Qué planeta tiene anillos muy visibles?', ['Saturno', 'Tierra', 'Mercurio'], 'Saturno', '💍'),
            omp('¿Cuántos planetas tiene el sistema solar?', [6, 7, 8, 9], 8),
            omp('El sol es…',                             ['Una estrella', 'Un planeta', 'Un satélite'], 'Una estrella', '⭐'),
        ]),

        est('Día, noche y estaciones', 'Todo por dos movimientos', '🌗', 'opcion_multiple', [
            omp('El día y la noche ocurren porque la Tierra…', ['Gira sobre sí misma', 'Se acerca al sol', 'Se detiene'], 'Gira sobre sí misma', '🌍'),
            omp('¿Cuánto tarda la Tierra en dar una vuelta sobre sí misma?', ['24 horas', '12 horas', '365 días'], '24 horas', '🕐'),
            omp('¿Cuánto tarda la Tierra en dar la vuelta al sol?', ['365 días', '24 horas', '30 días'], '365 días', '📅'),
            omp('La Luna es un…',   ['Satélite de la Tierra', 'Planeta', 'Estrella'], 'Satélite de la Tierra', '🌙'),
            omp('¿Por qué la Luna cambia de forma en el cielo?', ['Por cómo le da el sol desde donde la vemos', 'Porque crece', 'Porque se rompe'], 'Por cómo le da el sol desde donde la vemos', '🌓'),
        ]),

        est('Memoria del espacio', 'Encuentra las parejas', '🧠', 'memoria',
            ['🌍', '🪐', '☀️', '🌙', '⭐', '🚀', '☄️', '🛰️']),
    ],
],


// ── EXPERIMENTOS Y MATERIA ───────────────────────────────────────────

[
    'slug'  => 'estados-de-la-materia',
    'title' => 'Estados de la materia',
    'description' => 'Sólido, líquido y gas: lo mismo puede ser las tres cosas.',
    'objective' => 'Clasificar la materia por su estado y explicar los cambios entre estados.',
    'icon' => '🧊', 'nivel' => 'primaria-inicial', 'bloque' => 'experimentos-y-materia',
    'duracion' => 12, 'tags' => ['clasificacion', 'observacion', 'logica'],
    'estaciones' => [

        est('¿En qué estado está?', 'Sólido, líquido o gas', '🧊', 'opcion_multiple', [
            omp('¿En qué estado está?', ['Sólido', 'Líquido', 'Gas'], 'Sólido', '🧊'),
            omp('¿En qué estado está?', ['Líquido', 'Sólido', 'Gas'], 'Líquido', '💧'),
            omp('¿En qué estado está?', ['Gas', 'Sólido', 'Líquido'], 'Gas', '💨'),
            omp('¿En qué estado está una piedra?', ['Sólido', 'Líquido', 'Gas'], 'Sólido', '🪨'),
            omp('¿En qué estado está la leche?', ['Líquido', 'Sólido', 'Gas'], 'Líquido', '🥛'),
            omp('¿En qué estado está el aire?', ['Gas', 'Líquido', 'Sólido'], 'Gas', '🌬️'),
        ]),

        est('Los cambios', 'De un estado a otro', '🔄', 'opcion_multiple', [
            omp('Si calientas hielo, se vuelve…',   ['Agua líquida', 'Vapor de una vez', 'Piedra'], 'Agua líquida', '🧊'),
            omp('Si calientas agua hasta hervir, se vuelve…', ['Vapor', 'Hielo', 'Sólida'], 'Vapor', '♨️'),
            omp('Si enfrías mucho el agua, se vuelve…', ['Hielo', 'Vapor', 'Gas'], 'Hielo', '❄️'),
            omp('Pasar de sólido a líquido se llama…',  ['Fusión', 'Evaporación', 'Condensación'], 'Fusión', '💧'),
            omp('Pasar de líquido a gas se llama…',     ['Evaporación', 'Fusión', 'Solidificación'], 'Evaporación', '💨'),
            omp('El vapor que se empaña en un vidrio frío es…', ['Condensación', 'Fusión', 'Evaporación'], 'Condensación', '🪟'),
        ]),

        est('Toca los sólidos', 'Solo lo que tenga forma propia', '🪨', 'seleccion_imagenes',
            conTitulo('Toca todo lo que sea sólido', 'Deja fuera líquidos y gases', [
                ['e' => '🪨', 'n' => 'Piedra',  'ok' => true],
                ['e' => '🧊', 'n' => 'Hielo',   'ok' => true],
                ['e' => '📕', 'n' => 'Libro',   'ok' => true],
                ['e' => '🪵', 'n' => 'Madera',  'ok' => true],
                ['e' => '💧', 'n' => 'Agua',    'ok' => false],
                ['e' => '🥛', 'n' => 'Leche',   'ok' => false],
                ['e' => '💨', 'n' => 'Vapor',   'ok' => false],
                ['e' => '🎈', 'n' => 'Aire del globo', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'fuerzas-y-movimiento',
    'title' => 'Fuerzas y movimiento',
    'description' => 'Empujar, halar, caer: por qué las cosas se mueven como se mueven.',
    'objective' => 'Reconocer fuerzas cotidianas y anticipar su efecto sobre el movimiento.',
    'icon' => '🧲', 'nivel' => 'primaria-media', 'bloque' => 'experimentos-y-materia',
    'duracion' => 15, 'tags' => ['logica', 'observacion', 'comprension', 'reto'],
    'estaciones' => [

        est('¿Qué es una fuerza?', 'Empujar o halar', '💪', 'opcion_multiple', [
            omp('Una fuerza puede…',   ['Mover, parar o deformar algo', 'Solo mover', 'Nada'], 'Mover, parar o deformar algo', '💪'),
            omp('Abrir una puerta es…', ['Halar o empujar', 'Solo mirar', 'Calentar'], 'Halar o empujar', '🚪'),
            omp('La fuerza que te mantiene en el suelo es…', ['La gravedad', 'El viento', 'La luz'], 'La gravedad', '🌍'),
            omp('La fuerza que frena un objeto al deslizarse es…', ['La fricción', 'La gravedad', 'El magnetismo'], 'La fricción', '🛷'),
            omp('Un imán atrae objetos de…', ['Hierro', 'Madera', 'Papel'], 'Hierro', '🧲'),
        ]),

        est('¿Qué pasa si…?', 'Anticipa el resultado', '🤔', 'opcion_multiple', [
            omp('Sueltas una pelota en el aire. ¿Qué pasa?', ['Cae', 'Sube', 'Se queda quieta'], 'Cae', '⚽'),
            omp('Empujas una caja sobre hielo. Se desliza más porque…', ['Hay menos fricción', 'Pesa menos', 'No hay gravedad'], 'Hay menos fricción', '🧊'),
            omp('Una bicicleta sin frenos en bajada…', ['Va cada vez más rápido', 'Se detiene sola', 'Sube'], 'Va cada vez más rápido', '🚲'),
            omp('Dos personas halan una cuerda con la misma fuerza. La cuerda…', ['No se mueve', 'Se rompe siempre', 'Va hacia una'], 'No se mueve', '🪢'),
            omp('Un objeto pesado y uno liviano se sueltan a la vez sin aire. Llegan…', ['Al mismo tiempo', 'Primero el pesado', 'Primero el liviano'], 'Al mismo tiempo', '🪶'),
        ]),

        est('Máquinas simples', 'Herramientas que multiplican la fuerza', '⚙️', 'opcion_multiple', [
            omp('Una rampa para subir algo pesado es un…', ['Plano inclinado', 'Engranaje', 'Resorte'], 'Plano inclinado', '📐'),
            omp('Una barra para levantar algo apoyada en un punto es una…', ['Palanca', 'Polea', 'Rueda'], 'Palanca', '⚖️'),
            omp('Una cuerda que pasa por una rueda para subir cosas es una…', ['Polea', 'Palanca', 'Cuña'], 'Polea', '🪣'),
            omp('Las tijeras son dos…', ['Palancas', 'Poleas', 'Ruedas'], 'Palancas', '✂️'),
            omp('Las máquinas simples sirven para…', ['Hacer más fácil un trabajo', 'Gastar más fuerza', 'Nada'], 'Hacer más fácil un trabajo', '🔧'),
        ]),

        est('Reto del laboratorio', 'Aplica lo que sabes', '🏆', 'desafio_final', [
            reto('Para subir un baúl a un camión sin levantarlo, usarías…',
                 ['Una rampa', 'Un imán', 'Un espejo'], 'Una rampa'),
            reto('Un carro frena mejor con llantas nuevas porque…',
                 ['Tienen más fricción', 'Pesan más', 'Son más bonitas'], 'Tienen más fricción'),
            reto('En la Luna saltas más alto porque…',
                 ['Hay menos gravedad', 'Hay más aire', 'Pesas más'], 'Hay menos gravedad'),
            reto('Un experimento es válido cuando…',
                 ['Otros pueden repetirlo y obtener lo mismo', 'Sale bonito', 'Lo dice un video'],
                 'Otros pueden repetirlo y obtener lo mismo'),
        ]),
    ],
],

],
];
