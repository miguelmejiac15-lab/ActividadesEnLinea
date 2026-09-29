<?php
/**
 * Ciencias Sociales · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'detectives-de-la-historia' => intro(
        'La **historia** estudia lo que pasó antes que nosotros. Como nadie puede viajar al pasado, los historiadores trabajan como detectives: reúnen **pistas** llamadas **fuentes** y las interpretan.',
        ['**Fuentes materiales**: objetos, vasijas, herramientas, edificios.', '**Fuentes escritas**: cartas, diarios, periódicos.', '**Fuentes orales**: lo que cuentan las personas que lo vivieron.'],
        [
            completa('Las pistas que usan los historiadores para estudiar el pasado se llaman ___.', 'fuentes', ['cuentos', 'mapas']),
            identifica('Un abuelo contando lo que vivió es una fuente…', 'Oral', ['Escrita', 'Material']),
        ],
        'Si en una excavación aparecen redes y huesos de pescado, esa gente probablemente vivía de la pesca.'
    ),

    'construye-una-linea-del-tiempo' => intro(
        'Una **línea del tiempo** ordena los hechos **del más antiguo al más reciente**, de izquierda a derecha. Sirve para ver qué pasó antes, qué después y cuánto tiempo hubo entre una cosa y otra.',
        ['Una **década** = 10 años.', 'Un **siglo** = 100 años.', 'Un **milenio** = 1.000 años.'],
        [
            completa('Un siglo tiene ___ años.', '100', ['10', '1.000']),
            identifica('¿Qué se inventó primero?', 'La rueda', ['El automóvil', 'El teléfono']),
        ]
    ),

    'como-funciona-mi-ciudad' => intro(
        'En una ciudad vivimos muchas personas juntas, así que hacen falta **reglas** y personas que **gobiernen**. En Colombia, el **alcalde** gobierna el municipio y el **gobernador**, el departamento. Todos tenemos **derechos** y también **deberes**.',
        ['Un **derecho** es algo que te corresponde: educación, salud, jugar.', 'Un **deber** es algo que te toca hacer: respetar, cuidar lo común.', 'Los ciudadanos **eligen** a sus gobernantes votando.'],
        [
            completa('El municipio lo gobierna el ___.', 'alcalde', ['gobernador', 'presidente']),
            identifica('Cuidar los parques del barrio es un…', 'Deber', ['Derecho', 'Juego']),
        ]
    ),

    'paisajes-de-la-tierra' => intro(
        'El **relieve** son las formas de la superficie de la Tierra: montañas, llanuras, valles, costas, desiertos. El paisaje influye en cómo vive la gente: qué cultiva, en qué trabaja, cómo construye.',
        ['**Montaña**: elevación alta. **Valle**: la tierra baja entre montañas.', '**Llanura**: terreno plano y extenso.', 'Un paisaje **natural** lo hizo la naturaleza; uno **construido**, las personas.'],
        [
            completa('La tierra baja entre dos montañas se llama ___.', 'valle', ['isla', 'llanura']),
            identifica('¿Cuál es un paisaje construido?', 'Un puente', ['Una selva', 'Un río']),
        ]
    ),

    'regiones-naturales-de-colombia' => intro(
        'Colombia se divide en **seis regiones naturales**, cada una con su relieve, su clima, su gente y su cultura: **Caribe**, **Pacífica**, **Andina**, **Orinoquía**, **Amazonía** e **Insular**.',
        ['**Andina**: las montañas; ahí están Bogotá y Medellín.', '**Pacífica**: una de las zonas más lluviosas del planeta.', '**Orinoquía**: los llanos. **Amazonía**: la selva. **Insular**: las islas, como San Andrés.'],
        [
            completa('Colombia tiene ___ regiones naturales.', 'seis', ['cuatro', 'diez']),
            identifica('¿De qué región es el vallenato?', 'Del Caribe', ['De la Amazonía', 'De la Insular']),
        ]
    ),

    'el-gobierno-escolar' => intro(
        'El **gobierno escolar** es la forma en que los estudiantes **participan** en las decisiones del colegio. Es la **democracia** en pequeño: se presentan candidatos, se votan propuestas y gana la mayoría.',
        ['**Democracia**: el poder de decidir lo tiene el pueblo.', 'El **personero estudiantil** defiende los derechos de los compañeros.', 'Votar bien es **informarse** de las propuestas antes.'],
        [
            completa('El estudiante que defiende los derechos de sus compañeros es el ___.', 'personero', ['rector', 'alcalde']),
            identifica('En una votación, ¿qué opción gana?', 'La que tiene más votos', ['La del más grande', 'La primera que se dijo']),
        ]
    ),

    'los-primeros-pobladores-de-america' => intro(
        'Hace miles de años no había personas en América. La teoría más aceptada dice que llegaron desde **Asia** cruzando el **estrecho de Bering** cuando estaba congelado. Eran **nómadas**: se movían siguiendo a los animales.',
        ['**Nómada**: se traslada sin quedarse en un lugar.', '**Sedentario**: vive en un lugar fijo; pasó cuando aprendieron a **cultivar**.', 'Usaban herramientas de **piedra** y dominaron el **fuego**.'],
        [
            completa('Un grupo que se traslada sin quedarse en un lugar es ___.', 'nómada', ['sedentario', 'urbano']),
            identifica('¿De dónde se cree que vinieron los primeros pobladores de América?', 'De Asia', ['De Europa', 'De la Luna']),
        ]
    ),

    'culturas-prehispanicas' => intro(
        '**Prehispánico** quiere decir **antes de la llegada de los españoles** en 1492. Para entonces en América ya había grandes civilizaciones, con ciudades, calendarios y conocimientos admirables.',
        ['**Mayas**: México y Centroamérica. **Aztecas**: México. **Incas**: los Andes.', 'En Colombia: **muiscas** (orfebrería, la balsa muisca) y **tayronas** (Ciudad Perdida).', 'América le dio al mundo el **maíz**, la **papa** y el cacao.'],
        [
            completa('Colón llegó a América en el año ___.', '1492', ['1810', '1991']),
            identifica('¿Qué pueblo construyó Ciudad Perdida en la Sierra Nevada?', 'Los tayronas', ['Los aztecas', 'Los incas']),
        ]
    ),

    'mi-municipio' => intro(
        'El **municipio** es el lugar donde vives y se organiza para atender a sus habitantes. Lo gobierna un **alcalde** y ofrece **servicios públicos**: agua, luz, recolección de basura, parques. Colombia tiene **32 departamentos**, y cada uno reúne varios municipios.',
        ['Municipio → **alcalde**; departamento → **gobernador**.', 'Zona **urbana**: la ciudad. Zona **rural**: el campo.', 'Lo público lo cuidamos **entre todos**.'],
        [
            completa('Colombia tiene ___ departamentos.', '32', ['12', '100']),
            identifica('¿Cuál es un servicio público?', 'El agua potable', ['Un juguete', 'Un celular']),
        ]
    ),

    'los-continentes-y-oceanos' => intro(
        'La Tierra está cubierta en su mayoría por **océanos**. Las grandes extensiones de tierra se llaman **continentes**: América, Europa, Asia, África, Oceanía y la Antártida. Colombia está en **América del Sur**.',
        ['El continente más grande es **Asia**.', 'El océano más grande es el **Pacífico**.', 'Colombia tiene costas en dos: el **Pacífico** y el **Atlántico** (mar Caribe).'],
        [
            completa('El océano más grande del planeta es el ___.', 'Pacífico', ['Atlántico', 'Índico']),
            identifica('¿En qué continente está Colombia?', 'América', ['Europa', 'África']),
        ]
    ),

    'la-tierra-en-el-universo' => intro(
        'Para ubicar cualquier lugar del planeta se usan **líneas imaginarias**. El **ecuador** divide la Tierra en norte y sur, y pasa muy cerca de Colombia. Por eso aquí no hay cuatro estaciones y el día dura casi igual todo el año.',
        ['**Paralelos**: líneas horizontales (latitud).', '**Meridianos**: líneas verticales (longitud).', '**Rotación** → día y noche. **Traslación** → el año y las estaciones.'],
        [
            completa('La línea que divide la Tierra en norte y sur es el ___.', 'ecuador', ['meridiano', 'trópico']),
            identifica('¿Qué movimiento produce el día y la noche?', 'La rotación', ['La traslación', 'Las mareas']),
        ]
    ),

    'la-division-de-poderes' => intro(
        'En una democracia el poder no lo tiene una sola persona: se **divide en tres ramas** para que cada una vigile a las otras y nadie abuse. La norma que lo organiza todo es la **Constitución**.',
        ['**Ejecutiva**: gobierna (presidente, gobernadores, alcaldes).', '**Legislativa**: hace las leyes (el Congreso).', '**Judicial**: juzga y hace cumplir las leyes (los jueces).'],
        [
            completa('La rama que hace las leyes es la ___.', 'legislativa', ['ejecutiva', 'judicial']),
            identifica('¿Por qué se divide el poder en tres?', 'Para que ninguno tenga todo el control', ['Para que haya más fiestas', 'Porque tres es un buen número']),
        ]
    ),

    'la-independencia-de-colombia' => intro(
        'Durante casi 300 años este territorio fue gobernado por la **Corona española**. Los **criollos** —hijos de españoles nacidos en América— querían gobernarse a sí mismos. El **20 de julio de 1810**, en Santafé de Bogotá, empezó ese proceso de **independencia**.',
        ['El episodio empezó con un **florero** que un comerciante español se negó a prestar.', 'La independencia se consolidó en la **Batalla de Boyacá** (1819).', 'Líderes: **Simón Bolívar**, **Francisco de Paula Santander**, Antonio Nariño.'],
        [
            completa('El grito de independencia fue el 20 de julio de ___.', '1810', ['1492', '1991']),
            identifica('¿Quiénes eran los criollos?', 'Hijos de españoles nacidos en América', ['Soldados del rey', 'Comerciantes de Asia']),
        ]
    ),

    'de-donde-vienen-las-cosas' => intro(
        'Todo lo que usamos pasó por varias manos. La **economía** se organiza en tres **sectores**: el que saca los recursos de la naturaleza, el que los transforma y el que los lleva hasta nosotros.',
        ['**Primario**: obtiene recursos (agricultura, pesca, minería).', '**Secundario**: transforma la materia prima (fábricas).', '**Terciario**: servicios (transporte, comercio, salud).'],
        [
            completa('Cultivar café pertenece al sector ___.', 'primario', ['secundario', 'terciario']),
            identifica('¿Qué hace el sector secundario?', 'Transforma la materia prima', ['Siembra la tierra', 'Da clases']),
        ]
    ),

    'patrimonio-y-cultura' => intro(
        'El **patrimonio** es lo que una comunidad **hereda** de quienes vivieron antes y decide **conservar**. Puede ser **material** (edificios, estatuas) o **inmaterial** (lenguas, fiestas, músicas, saberes).',
        ['Patrimonio material: las murallas de **Cartagena**, las estatuas de **San Agustín**.', 'Patrimonio inmaterial: el Carnaval de Barranquilla, las lenguas indígenas.', 'En Colombia se hablan **más de sesenta** lenguas indígenas.'],
        [
            completa('Lo que una comunidad hereda y decide conservar es su ___.', 'patrimonio', ['basura', 'deuda']),
            identifica('¿Cuál es patrimonio inmaterial?', 'Una lengua indígena', ['Una iglesia antigua', 'Una muralla']),
        ]
    ),

    'crucigramas-de-colombia' => intro(
        'Estos crucigramas repasan lo aprendido sobre **Colombia**: su geografía, su historia y cómo se organiza como país. Cada pista es una definición o un dato que ya viste en otras actividades.',
        ['Regiones: Caribe, Pacífica, Andina, Orinoquía, Amazonía, Insular.', 'Historia: pueblos indígenas, independencia, Bolívar.', 'Ciudadanía: democracia, Constitución, derechos.'],
        [
            completa('La región de la selva, al sur de Colombia, es la ___.', 'Amazonía', ['Orinoquía', 'Caribe']),
            identifica('¿Qué es la democracia?', 'El poder del pueblo para decidir', ['El gobierno de un rey', 'Un tipo de montaña']),
        ]
    ),

    'como-se-organiza-un-pais' => intro(
        'Un país necesita **reglas** y alguien que las haga cumplir. En Colombia la **Constitución de 1991** es la norma más importante. El poder se reparte en tres ramas y el territorio en niveles: **nación**, **departamentos** y **municipios**.',
        ['**Presidente** → el país. **Gobernador** → el departamento. **Alcalde** → el municipio.', 'Ninguna ley puede ir **contra la Constitución**.', 'Legislativo hace leyes; ejecutivo gobierna; judicial juzga.'],
        [
            completa('La Constitución colombiana actual es de ___.', '1991', ['1810', '2020']),
            identifica('¿Quién está al frente de un departamento?', 'El gobernador', ['El alcalde', 'El personero']),
        ]
    ),

    'de-donde-sale-lo-que-uso' => intro(
        'Detrás de una camiseta o un paquete de café hay una **cadena productiva**: alguien cultivó la materia prima, alguien la transformó y alguien la transportó y vendió. En el **precio** va el trabajo de todas esas personas.',
        ['**Primario**: cultivar el algodón. **Secundario**: hacer la tela y coserla. **Terciario**: transportarla y venderla.', 'Si hay **poco** de algo y mucha gente lo quiere, el precio **sube**.', 'Todo trabajador tiene derecho a **pago justo** y **descanso**; el trabajo infantil se debe evitar.'],
        [
            completa('Tostar y empacar el café pertenece al sector ___.', 'secundario', ['primario', 'terciario']),
            identifica('Si hay poco de algo y mucha gente lo quiere, el precio…', 'Sube', ['Baja', 'Desaparece']),
        ]
    ),

];
