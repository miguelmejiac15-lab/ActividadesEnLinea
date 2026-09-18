<?php
/**
 * ciencias-primaria.php — Ciencias Naturales de 1.º a 6.º
 *
 * Escrito sobre dos fuentes de `MallasPrimaria/`: las mallas de Ciencias
 * Naturales (K4, K5, K6) y las de Sachunterricht (K3 a K6).
 *
 * Sachunterricht está redactada en alemán, pero NO es la asignatura de
 * alemán —esa es «Deutsch», y queda fuera por decisión del proyecto—. Es
 * la materia de conocimiento del medio: clima, agua, energía, residuos,
 * vertebrados, insectos, plantas y cuerpo humano. Su contenido es de
 * ciencias, así que entra aquí traducido; lo que no entra es el idioma.
 *
 * Las mallas repiten a propósito la célula en K4, K5 y K6, cada vez más
 * hondo. Aquí eso no se repite tres veces: se reparte en tres actividades
 * de niveles distintos, porque un catálogo con tres fichas casi iguales
 * parece un error de carga aunque sea una progresión deliberada.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'ciencias',
    'name'       => 'Ciencias',
    'tagline'    => 'Los seres vivos, la materia y la energía que mueve el mundo',
    'icon'       => '🔬',
    'color'      => '#43a047',
    'sort_order' => 5,
],

'bloques' => [
    ['slug' => 'clasificar-la-vida', 'name' => 'Clasificar la Vida', 'icon' => '🦎', 'sort_order' => 4,
     'description' => 'De la célula a los reinos: cómo se ordena la enorme variedad de seres vivos.'],
    ['slug' => 'el-cuerpo-por-dentro', 'name' => 'El Cuerpo por Dentro', 'icon' => '🫀', 'sort_order' => 5,
     'description' => 'Los sistemas que trabajan juntos para mantenernos vivos.'],
    ['slug' => 'ecosistemas', 'name' => 'Ecosistemas y Equilibrio', 'icon' => '🌿', 'sort_order' => 6,
     'description' => 'Quién come a quién, cómo circula la materia y qué pasa cuando se rompe el equilibrio.'],
    ['slug' => 'materia-y-energia', 'name' => 'Materia y Energía', 'icon' => '⚡', 'sort_order' => 7,
     'description' => 'De qué está hecho todo, cómo cambia y qué hace que las cosas se muevan.'],
    ['slug' => 'el-planeta-que-habitamos', 'name' => 'El Planeta que Habitamos', 'icon' => '🌍', 'sort_order' => 8,
     'description' => 'Clima, agua, residuos y el universo del que formamos parte.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · CLASIFICAR LA VIDA
// =====================================================================

[
    'slug'  => 'vivo-o-no-vivo',
    'title' => '¿Vivo o no vivo?',
    'description' => 'Qué hace que algo esté vivo: nacer, crecer, alimentarse, reproducirse y morir.',
    'objective' => 'Identificar las características que distinguen a un ser vivo de un objeto inerte.',
    'icon' => '🌱', 'nivel' => 'primaria-inicial', 'bloque' => 'clasificar-la-vida',
    'duracion' => 11, 'tags' => ['observacion', 'clasificacion', 'comprension'],
    'estaciones' => [

        est('¿Está vivo?', 'Decide mirando el dibujo', '👀', 'seleccion_imagenes',
            conTitulo('Toca todo lo que está vivo', 'Cuidado: algunos parecen vivos y no lo están', [
                ['e' => '🐶', 'n' => 'Perro',    'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',    'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',   'ok' => false],
                ['e' => '🦋', 'n' => 'Mariposa', 'ok' => true],
                ['e' => '🚗', 'n' => 'Carro',    'ok' => false],
                ['e' => '🌻', 'n' => 'Girasol',  'ok' => true],
                ['e' => '💧', 'n' => 'Agua',     'ok' => false],
                ['e' => '🐟', 'n' => 'Pez',      'ok' => true],
            ])),

        est('Lo que hacen los seres vivos', 'Cinco cosas que los definen', '🔄', 'opcion_multiple', [
            omp('¿Qué hacen todos los seres vivos?',       ['Nacen, crecen y mueren', 'Se mueven rápido', 'Hacen ruido'], 'Nacen, crecen y mueren'),
            omp('¿Necesita alimentarse un ser vivo?',      ['Sí, siempre', 'No', 'Solo los animales'], 'Sí, siempre'),
            omp('¿Se reproducen los seres vivos?',         ['Sí, tienen crías o semillas', 'No', 'Solo las plantas'], 'Sí, tienen crías o semillas'),
            omp('Un carro se mueve. ¿Está vivo?',          ['No, no nace ni crece', 'Sí, porque se mueve', 'Solo si tiene gasolina'], 'No, no nace ni crece'),
            omp('¿Crece una planta?',                      ['Sí', 'No', 'Solo si la pintan'], 'Sí'),
        ]),

        est('Animales y plantas', 'Dos grandes grupos de seres vivos', '🗂️', 'opcion_multiple', [
            omp('¿Qué necesitan las plantas para vivir?',   ['Luz, agua y aire', 'Solo agua', 'Nada'], 'Luz, agua y aire', '🌻'),
            omp('¿Cómo se alimentan casi todas las plantas?', ['Fabrican su alimento con la luz', 'Comen insectos', 'No comen'], 'Fabrican su alimento con la luz', '☀️'),
            omp('¿Se mueven las plantas de lugar?',         ['No, están fijas al suelo', 'Sí, caminan', 'Solo de noche'], 'No, están fijas al suelo'),
            omp('¿De dónde toma agua una planta?',          ['Por las raíces', 'Por las hojas', 'Por las flores'], 'Por las raíces', '🌱'),
            omp('¿Qué animal nace de un huevo?',            ['La gallina', 'El perro', 'El caballo'], 'La gallina', '🥚'),
        ]),

        est('Memoria de seres vivos', 'Encuentra las parejas', '🧠', 'memoria',
            ['🐶', '🌳', '🦋', '🐟', '🌻', '🐢']),
    ],
],

[
    'slug'  => 'la-celula',
    'title' => 'La célula',
    'description' => 'La unidad más pequeña de todo lo que está vivo, y sus partes principales.',
    'objective' => 'Reconocer la célula como unidad estructural y funcional de los seres vivos.',
    'icon' => '🔬', 'nivel' => 'primaria-media', 'bloque' => 'clasificar-la-vida',
    'duracion' => 13, 'tags' => ['observacion', 'comprension', 'clasificacion'],
    'estaciones' => [

        est('La unidad de la vida', 'Todo lo vivo está hecho de células', '🧫', 'opcion_multiple', [
            omp('¿Qué es la célula?',                     ['La unidad más pequeña de un ser vivo', 'Un órgano', 'Un tipo de animal'], 'La unidad más pequeña de un ser vivo'),
            omp('¿Se pueden ver las células a simple vista?', ['No, hace falta un microscopio', 'Sí, siempre', 'Solo de noche'], 'No, hace falta un microscopio', '🔬'),
            omp('¿Están hechas de células las plantas?',  ['Sí', 'No', 'Solo las flores'], 'Sí'),
            omp('¿Cuántas células tiene una bacteria?',   ['Una', 'Miles', 'Ninguna'], 'Una'),
            omp('Un ser con muchas células se llama…',    ['Pluricelular', 'Unicelular', 'Mineral'], 'Pluricelular'),
        ]),

        est('Partes de la célula', 'Cada parte hace un trabajo', '🧩', 'opcion_multiple', [
            omp('¿Qué parte rodea y protege a la célula?',      ['La membrana', 'El núcleo', 'El citoplasma'], 'La membrana'),
            omp('¿Qué parte guarda la información y dirige?',   ['El núcleo', 'La membrana', 'La pared'], 'El núcleo'),
            omp('¿Cómo se llama el líquido de dentro?',         ['Citoplasma', 'Núcleo', 'Membrana'], 'Citoplasma'),
            omp('¿Qué orgánulo produce la energía?',            ['La mitocondria', 'El núcleo', 'La pared'], 'La mitocondria'),
            omp('¿Qué orgánulo hace la fotosíntesis en las plantas?', ['El cloroplasto', 'La mitocondria', 'El núcleo'], 'El cloroplasto'),
        ]),

        est('Célula animal y vegetal', 'Parecidas, pero no iguales', '⚖️', 'opcion_multiple', [
            omp('¿Qué tiene la célula vegetal y no la animal?', ['Pared celular', 'Núcleo', 'Membrana'], 'Pared celular'),
            omp('¿Cuál tiene cloroplastos?',                    ['La vegetal', 'La animal', 'Las dos'], 'La vegetal'),
            omp('¿Cuál de las dos tiene núcleo?',               ['Las dos', 'Solo la animal', 'Solo la vegetal'], 'Las dos'),
            omp('¿Por qué las plantas son verdes?',             ['Por la clorofila de los cloroplastos', 'Por el agua', 'Por la tierra'], 'Por la clorofila de los cloroplastos', '🌿'),
            omp('¿Qué forma suele tener la célula vegetal?',    ['Más rígida y rectangular', 'Siempre redonda', 'Triangular'], 'Más rígida y rectangular'),
        ]),

        est('De la célula al organismo', 'Ordena los niveles', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena de lo más pequeño a lo más grande',
            'items' => ['Célula', 'Tejido', 'Órgano', 'Sistema', 'Organismo'],
        ]),
    ],
],

[
    'slug'  => 'los-reinos-de-la-naturaleza',
    'title' => 'Los reinos de la naturaleza',
    'description' => 'Cinco grandes grupos para ordenar todos los seres vivos del planeta.',
    'objective' => 'Clasificar seres vivos en los cinco reinos según sus características generales.',
    'icon' => '👑', 'nivel' => 'primaria-media', 'bloque' => 'clasificar-la-vida',
    'duracion' => 13, 'tags' => ['clasificacion', 'comprension', 'observacion'],
    'estaciones' => [

        est('Los cinco reinos', 'Cómo se ordena la vida', '🗃️', 'opcion_multiple', [
            omp('¿A qué reino pertenece un perro?',      ['Animal', 'Vegetal', 'Fungi'], 'Animal', '🐶'),
            omp('¿A qué reino pertenece un pino?',       ['Vegetal', 'Animal', 'Mónera'], 'Vegetal', '🌲'),
            omp('¿A qué reino pertenece un hongo?',      ['Fungi', 'Vegetal', 'Animal'], 'Fungi', '🍄'),
            omp('¿A qué reino pertenece una bacteria?',  ['Mónera', 'Protista', 'Fungi'], 'Mónera', '🦠'),
            omp('¿Cuántos reinos se estudian normalmente?', ['Cinco', 'Tres', 'Diez'], 'Cinco'),
        ]),

        est('Cómo se alimentan', 'La diferencia clave entre reinos', '🍽️', 'opcion_multiple', [
            omp('Las plantas fabrican su propio alimento. Eso las hace…', ['Autótrofas', 'Heterótrofas', 'Carnívoras'], 'Autótrofas'),
            omp('Los animales comen a otros seres. Eso los hace…',        ['Heterótrofos', 'Autótrofos', 'Minerales'], 'Heterótrofos'),
            omp('¿Cómo se alimentan los hongos?',                         ['Absorbiendo materia de otros', 'Con la luz del sol', 'No se alimentan'], 'Absorbiendo materia de otros'),
            omp('¿Es un hongo una planta?',                               ['No, es su propio reino', 'Sí', 'Solo el champiñón'], 'No, es su propio reino'),
            omp('¿Puede una planta vivir en total oscuridad?',            ['No, necesita luz', 'Sí', 'Solo si es grande'], 'No, necesita luz'),
        ]),

        est('Vertebrados', 'Los animales con columna', '🦴', 'opcion_multiple', [
            omp('¿Qué tienen los vertebrados?',            ['Columna vertebral', 'Caparazón', 'Antenas'], 'Columna vertebral'),
            omp('¿A qué grupo pertenece la vaca?',         ['Mamíferos', 'Aves', 'Reptiles'], 'Mamíferos', '🐄'),
            omp('¿A qué grupo pertenece la rana?',         ['Anfibios', 'Reptiles', 'Peces'], 'Anfibios', '🐸'),
            omp('¿A qué grupo pertenece la serpiente?',    ['Reptiles', 'Anfibios', 'Mamíferos'], 'Reptiles', '🐍'),
            omp('¿Qué caracteriza a los mamíferos?',       ['Alimentan a sus crías con leche', 'Ponen huevos siempre', 'Tienen escamas'], 'Alimentan a sus crías con leche'),
        ]),

        est('Invertebrados e insectos', 'Los que no tienen columna', '🐝', 'opcion_multiple', [
            omp('¿Cuántas patas tiene un insecto?',       ['6', '8', '4'], '6', '🐜'),
            omp('¿En cuántas partes se divide el cuerpo de un insecto?', ['3: cabeza, tórax y abdomen', '2', '5'], '3: cabeza, tórax y abdomen'),
            omp('¿Es la araña un insecto?',               ['No, tiene 8 patas', 'Sí', 'Solo algunas'], 'No, tiene 8 patas', '🕷️'),
            omp('¿Cómo se llama el cambio de oruga a mariposa?', ['Metamorfosis', 'Fotosíntesis', 'Digestión'], 'Metamorfosis', '🦋'),
            omp('¿Por qué son importantes las abejas?',   ['Polinizan las plantas', 'Hacen ruido', 'Comen hojas'], 'Polinizan las plantas', '🐝'),
        ]),

        est('Desafío de la clasificación', 'Cinco preguntas para cerrar', '🏆', 'desafio_final', [
            reto('¿Qué animal es un invertebrado?',        ['El caracol', 'El gato', 'El águila'], 'El caracol'),
            reto('¿Qué tienen en común aves y mamíferos?', ['Los dos son vertebrados', 'Los dos vuelan', 'Los dos dan leche'], 'Los dos son vertebrados'),
            reto('¿Cómo respiran los peces?',              ['Por branquias', 'Por pulmones', 'Por la piel solamente'], 'Por branquias'),
            reto('¿Qué grupo pone huevos y tiene plumas?', ['Aves', 'Reptiles', 'Anfibios'], 'Aves'),
            reto('La rana vive en el agua de pequeña y en tierra de adulta. Es un…', ['Anfibio', 'Reptil', 'Pez'], 'Anfibio'),
        ]),
    ],
],

[
    'slug'  => 'las-plantas-por-dentro',
    'title' => 'Las plantas por dentro',
    'description' => 'Raíz, tallo, hoja y flor: qué hace cada parte y cómo se reproduce una planta.',
    'objective' => 'Nombrar los órganos de una planta con flor, describir sus funciones y su reproducción.',
    'icon' => '🌺', 'nivel' => 'primaria-media', 'bloque' => 'clasificar-la-vida',
    'duracion' => 13, 'tags' => ['observacion', 'comprension'],
    'estaciones' => [

        est('Las partes de la planta', 'Cada una con su trabajo', '🌱', 'opcion_multiple', [
            omp('¿Qué parte sujeta la planta y absorbe agua?', ['La raíz', 'La hoja', 'La flor'], 'La raíz'),
            omp('¿Qué parte transporta el agua hacia arriba?', ['El tallo', 'La raíz', 'El fruto'], 'El tallo'),
            omp('¿En qué parte se hace la fotosíntesis?',      ['En las hojas', 'En la raíz', 'En el fruto'], 'En las hojas', '🍃'),
            omp('¿Qué parte sirve para reproducirse?',         ['La flor', 'El tallo', 'La raíz'], 'La flor', '🌸'),
            omp('¿Qué protege a la semilla?',                  ['El fruto', 'La hoja', 'La raíz'], 'El fruto', '🍎'),
        ]),

        est('La fotosíntesis', 'Cómo fabrica su comida una planta', '☀️', 'opcion_multiple', [
            omp('¿Qué necesita la planta para la fotosíntesis?', ['Luz, agua y dióxido de carbono', 'Solo tierra', 'Solo aire'], 'Luz, agua y dióxido de carbono'),
            omp('¿Qué gas libera la planta?',                    ['Oxígeno', 'Dióxido de carbono', 'Vapor de agua solamente'], 'Oxígeno'),
            omp('¿Qué sustancia verde captura la luz?',          ['La clorofila', 'El agua', 'La savia'], 'La clorofila'),
            omp('¿Por qué son importantes las plantas para nosotros?', ['Producen el oxígeno que respiramos', 'Hacen ruido', 'Dan sombra solamente'], 'Producen el oxígeno que respiramos'),
            omp('¿Cuándo hace fotosíntesis la planta?',          ['Cuando hay luz', 'Solo de noche', 'Nunca'], 'Cuando hay luz'),
        ]),

        est('De semilla a planta', 'Ordena el crecimiento', '🌾', 'ordenar_secuencia', [
            'title' => 'Ordena el ciclo de una planta',
            'items' => ['Semilla', 'Brote', 'Planta joven', 'Planta con flor', 'Fruto con semillas'],
        ]),

        est('Polinización', 'Cómo viaja el polen', '🐝', 'opcion_multiple', [
            omp('¿Qué es la polinización?',                 ['El paso del polen de una flor a otra', 'El riego de la planta', 'La caída de las hojas'], 'El paso del polen de una flor a otra'),
            omp('¿Quién ayuda más a polinizar?',            ['Los insectos', 'Los peces', 'Las piedras'], 'Los insectos', '🐝'),
            omp('¿Qué más puede transportar el polen?',     ['El viento', 'La tierra', 'La sombra'], 'El viento', '💨'),
            omp('¿Qué se forma después de la fecundación?', ['El fruto con semillas', 'Una hoja nueva', 'Una raíz'], 'El fruto con semillas'),
            omp('¿En qué se diferencian un árbol de hoja caduca y uno perenne?', ['El caduco pierde las hojas en una época', 'Ninguno pierde hojas', 'El perenne no crece'], 'El caduco pierde las hojas en una época'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · EL CUERPO POR DENTRO
// =====================================================================

[
    'slug'  => 'los-cinco-sentidos',
    'title' => 'Los cinco sentidos',
    'description' => 'Vista, oído, olfato, gusto y tacto: cómo nos enteramos de lo que pasa afuera.',
    'objective' => 'Relacionar cada sentido con su órgano y con la información que aporta.',
    'icon' => '👁️', 'nivel' => 'primaria-inicial', 'bloque' => 'el-cuerpo-por-dentro',
    'duracion' => 11, 'tags' => ['observacion', 'cuerpo', 'comprension'],
    'estaciones' => [

        est('Cada sentido, su órgano', 'Une el sentido con su parte del cuerpo', '🔗', 'emparejar', [
            ['e' => '👁️', 'w' => 'Vista'],
            ['e' => '👂', 'w' => 'Oído'],
            ['e' => '👃', 'w' => 'Olfato'],
            ['e' => '👅', 'w' => 'Gusto'],
            ['e' => '✋', 'w' => 'Tacto'],
            ['e' => '🧠', 'w' => 'Cerebro'],
        ]),

        est('¿Con qué lo noto?', 'Elige el sentido correcto', '🤔', 'opcion_multiple', [
            omp('¿Con qué sentido noto que la sopa está caliente?', ['Tacto', 'Vista', 'Oído'], 'Tacto', '🍲'),
            omp('¿Con qué sentido sé que suena un timbre?',        ['Oído', 'Gusto', 'Olfato'], 'Oído', '🔔'),
            omp('¿Con qué sentido huelo el pan recién hecho?',     ['Olfato', 'Vista', 'Tacto'], 'Olfato', '🍞'),
            omp('¿Con qué sentido sé que un limón es ácido?',      ['Gusto', 'Oído', 'Vista'], 'Gusto', '🍋'),
            omp('¿Con qué sentido veo los colores?',               ['Vista', 'Tacto', 'Olfato'], 'Vista', '🌈'),
        ]),

        est('Cuidar los sentidos', 'Hábitos que los protegen', '🛡️', 'opcion_multiple', [
            omp('¿Es bueno mirar el sol directamente?',       ['No, daña los ojos', 'Sí', 'Solo al mediodía'], 'No, daña los ojos'),
            omp('¿Es bueno oír música muy fuerte con audífonos?', ['No, daña el oído', 'Sí, siempre', 'Solo de noche'], 'No, daña el oído'),
            omp('¿Qué hago si algo huele muy fuerte y me marea?', ['Me alejo y aviso', 'Lo huelo más', 'No hago nada'], 'Me alejo y aviso'),
            omp('¿Debo meterme objetos en el oído?',          ['No, nunca', 'Sí, para limpiarlo', 'Solo palillos'], 'No, nunca'),
            omp('¿Cada cuánto conviene revisar la vista?',    ['Una vez al año', 'Nunca', 'Cada diez años'], 'Una vez al año'),
        ]),

        est('Memoria de los sentidos', 'Encuentra las parejas', '🧠', 'memoria',
            ['👁️', '👂', '👃', '👅', '✋', '🧠']),
    ],
],

[
    'slug'  => 'huesos-y-musculos',
    'title' => 'Huesos y músculos',
    'description' => 'El sistema locomotor: lo que nos sostiene y lo que nos permite movernos.',
    'objective' => 'Identificar los principales huesos y músculos y explicar cómo producen el movimiento.',
    'icon' => '🦴', 'nivel' => 'primaria-media', 'bloque' => 'el-cuerpo-por-dentro',
    'duracion' => 13, 'tags' => ['cuerpo', 'observacion', 'comprension'],
    'estaciones' => [

        est('El esqueleto', 'Lo que nos sostiene', '💀', 'opcion_multiple', [
            omp('¿Para qué sirve el esqueleto?',           ['Sostiene el cuerpo y protege los órganos', 'Solo para pesar', 'Para respirar'], 'Sostiene el cuerpo y protege los órganos'),
            omp('¿Qué protege el cráneo?',                 ['El cerebro', 'El corazón', 'El estómago'], 'El cerebro', '🧠'),
            omp('¿Qué protegen las costillas?',            ['Corazón y pulmones', 'El cerebro', 'Los riñones'], 'Corazón y pulmones', '🫁'),
            omp('¿Cómo se llama la columna de huesos de la espalda?', ['Columna vertebral', 'Cráneo', 'Fémur'], 'Columna vertebral'),
            omp('¿Cuál es el hueso más largo del cuerpo?', ['El fémur', 'La costilla', 'El cráneo'], 'El fémur'),
        ]),

        est('Las articulaciones', 'Donde se unen dos huesos', '🔗', 'opcion_multiple', [
            omp('¿Qué es una articulación?',           ['La unión entre dos huesos', 'Un músculo', 'Un nervio'], 'La unión entre dos huesos'),
            omp('¿Qué articulación tienes en el brazo?', ['El codo', 'La rodilla', 'El tobillo'], 'El codo'),
            omp('¿Qué articulación tienes en la pierna?', ['La rodilla', 'El codo', 'La muñeca'], 'La rodilla'),
            omp('¿Podríamos movernos sin articulaciones?', ['No, seríamos rígidos', 'Sí, igual', 'Solo al correr'], 'No, seríamos rígidos'),
            omp('¿Qué articulación gira en casi todas las direcciones?', ['El hombro', 'La rodilla', 'El codo'], 'El hombro'),
        ]),

        est('Los músculos', 'Lo que tira de los huesos', '💪', 'opcion_multiple', [
            omp('¿Cómo mueven los músculos a los huesos?', ['Contrayéndose y relajándose', 'Empujando el aire', 'Con electricidad del sol'], 'Contrayéndose y relajándose'),
            omp('¿Cuál es el músculo que nunca descansa?', ['El corazón', 'El bíceps', 'El de la pierna'], 'El corazón', '🫀'),
            omp('¿Qué une el músculo al hueso?',           ['El tendón', 'La piel', 'La sangre'], 'El tendón'),
            omp('¿Qué pasa con los músculos si hacemos ejercicio?', ['Se fortalecen', 'Desaparecen', 'Se vuelven huesos'], 'Se fortalecen'),
            omp('Al doblar el brazo, ¿qué músculo se contrae?', ['El bíceps', 'El corazón', 'El diafragma'], 'El bíceps'),
        ]),

        est('Desafío del movimiento', 'Cuidar el sistema locomotor', '🏆', 'desafio_final', [
            reto('¿Qué ayuda a tener huesos fuertes?',           ['El calcio y el ejercicio', 'Dormir todo el día', 'Comer solo dulces'], 'El calcio y el ejercicio'),
            reto('¿Por qué hay que calentar antes de hacer deporte?', ['Para evitar lesiones', 'Para cansarse antes', 'No hace falta'], 'Para evitar lesiones'),
            reto('¿Qué alimento aporta mucho calcio?',           ['La leche', 'El azúcar', 'El aceite'], 'La leche'),
            reto('¿Qué pasa si me siento siempre torcido?',      ['Puede afectar mi columna', 'Nada', 'Crezco más'], 'Puede afectar mi columna'),
            reto('¿Cuántos huesos tiene aproximadamente un adulto?', ['206', '50', '1.000'], '206'),
        ]),
    ],
],

[
    'slug'  => 'sistemas-del-cuerpo-humano',
    'title' => 'Sistemas del cuerpo humano',
    'description' => 'Digestivo, respiratorio y circulatorio: tres sistemas que trabajan sin descanso.',
    'objective' => 'Diferenciar los sistemas digestivo, respiratorio y circulatorio y sus órganos principales.',
    'icon' => '🫀', 'nivel' => 'primaria-media', 'bloque' => 'el-cuerpo-por-dentro',
    'duracion' => 15, 'tags' => ['cuerpo', 'comprension', 'clasificacion'],
    'estaciones' => [

        est('El sistema digestivo', 'El viaje de la comida', '🍽️', 'opcion_multiple', [
            omp('¿Dónde empieza la digestión?',          ['En la boca', 'En el estómago', 'En el intestino'], 'En la boca', '👄'),
            omp('¿Qué tubo lleva la comida al estómago?', ['El esófago', 'La tráquea', 'La vena'], 'El esófago'),
            omp('¿Dónde se absorben los nutrientes?',    ['En el intestino delgado', 'En la boca', 'En los pulmones'], 'En el intestino delgado'),
            omp('¿Para qué sirve la saliva?',            ['Ablanda la comida y empieza a digerirla', 'Para hablar', 'Para respirar'], 'Ablanda la comida y empieza a digerirla'),
            omp('¿Qué órgano produce la bilis?',         ['El hígado', 'El corazón', 'El pulmón'], 'El hígado'),
        ]),

        est('El sistema respiratorio', 'El viaje del aire', '🫁', 'opcion_multiple', [
            omp('¿Qué gas necesitamos del aire?',        ['Oxígeno', 'Dióxido de carbono', 'Nitrógeno'], 'Oxígeno'),
            omp('¿Qué gas expulsamos al exhalar?',       ['Dióxido de carbono', 'Oxígeno', 'Helio'], 'Dióxido de carbono'),
            omp('¿Por dónde entra el aire?',             ['Por la nariz', 'Por el esófago', 'Por el estómago'], 'Por la nariz', '👃'),
            omp('¿Qué músculo nos ayuda a respirar?',    ['El diafragma', 'El bíceps', 'El corazón'], 'El diafragma'),
            omp('¿Cuántos pulmones tenemos?',            ['Dos', 'Uno', 'Cuatro'], 'Dos'),
        ]),

        est('El sistema circulatorio', 'El viaje de la sangre', '🩸', 'opcion_multiple', [
            omp('¿Qué órgano bombea la sangre?',         ['El corazón', 'El pulmón', 'El hígado'], 'El corazón', '🫀'),
            omp('¿Qué llevan las arterias?',             ['Sangre desde el corazón', 'Aire', 'Comida'], 'Sangre desde el corazón'),
            omp('¿Qué transporta el oxígeno en la sangre?', ['Los glóbulos rojos', 'Los huesos', 'La saliva'], 'Los glóbulos rojos'),
            omp('¿Qué células nos defienden de infecciones?', ['Los glóbulos blancos', 'Los glóbulos rojos', 'Las plaquetas'], 'Los glóbulos blancos'),
            omp('¿Qué es el pulso?',                     ['El latido que se siente en las arterias', 'La respiración', 'El sudor'], 'El latido que se siente en las arterias'),
        ]),

        est('El viaje completo', 'Ordena el recorrido de la comida', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena el recorrido de los alimentos',
            'items' => ['Boca', 'Esófago', 'Estómago', 'Intestino delgado', 'Intestino grueso'],
        ]),

        est('Todo conectado', 'Los sistemas trabajan juntos', '🏆', 'desafio_final', [
            reto('¿Qué sistema lleva el oxígeno a todo el cuerpo?',   ['El circulatorio', 'El digestivo', 'El óseo'], 'El circulatorio'),
            reto('¿De dónde saca el cuerpo la energía?',              ['De los alimentos', 'Del sueño solamente', 'Del aire solamente'], 'De los alimentos'),
            reto('Si corro, mi corazón late más rápido porque…',      ['Los músculos necesitan más oxígeno', 'Tengo miedo', 'Hace calor'], 'Los músculos necesitan más oxígeno'),
            reto('¿Qué sistema elimina los desechos líquidos?',       ['El excretor', 'El respiratorio', 'El óseo'], 'El excretor'),
            reto('¿Puede funcionar un sistema sin los demás?',        ['No, todos dependen entre sí', 'Sí, cada uno solo', 'Solo el digestivo'], 'No, todos dependen entre sí'),
        ]),
    ],
],

[
    'slug'  => 'nervioso-y-hormonal',
    'title' => 'Nervioso y hormonal',
    'description' => 'Los dos sistemas que coordinan todo lo demás, y los cambios de la pubertad.',
    'objective' => 'Describir la función del sistema nervioso y endocrino en la coordinación del organismo.',
    'icon' => '🧠', 'nivel' => 'primaria-superior', 'bloque' => 'el-cuerpo-por-dentro',
    'duracion' => 15, 'tags' => ['cuerpo', 'comprension', 'salud'],
    'estaciones' => [

        est('El sistema nervioso', 'El centro de mando', '⚡', 'opcion_multiple', [
            omp('¿Cuáles son las partes del sistema nervioso central?', ['Cerebro y médula espinal', 'Corazón y pulmones', 'Huesos y músculos'], 'Cerebro y médula espinal'),
            omp('¿Qué transmite las señales por el cuerpo?',            ['Los nervios', 'Las venas', 'Los tendones'], 'Los nervios'),
            omp('¿Qué órgano dirige el pensamiento y la memoria?',      ['El cerebro', 'El corazón', 'El hígado'], 'El cerebro'),
            omp('Si toco algo caliente y quito la mano sin pensar, es un…', ['Acto reflejo', 'Acto voluntario', 'Sueño'], 'Acto reflejo'),
            omp('¿Qué protege al cerebro?',                             ['El cráneo', 'Las costillas', 'La piel solamente'], 'El cráneo'),
        ]),

        est('El sistema endocrino', 'Mensajeros químicos', '🧪', 'opcion_multiple', [
            omp('¿Qué produce el sistema endocrino?',    ['Hormonas', 'Sangre', 'Huesos'], 'Hormonas'),
            omp('¿Cómo viajan las hormonas?',            ['Por la sangre', 'Por los nervios', 'Por el aire'], 'Por la sangre'),
            omp('¿Qué glándula controla el crecimiento?', ['La hipófisis', 'El hígado', 'El pulmón'], 'La hipófisis'),
            omp('¿Qué hormona regula el azúcar en la sangre?', ['La insulina', 'La adrenalina', 'La saliva'], 'La insulina'),
            omp('¿Qué diferencia hay entre nervios y hormonas?', ['Los nervios son rápidos, las hormonas más lentas', 'Son lo mismo', 'Las hormonas son eléctricas'], 'Los nervios son rápidos, las hormonas más lentas'),
        ]),

        /*
         * La pubertad está en la malla de K6 y en el Sachunterricht de K6.
         * Se trata con nombres correctos y sin rodeos: un niño de once años
         * que no encuentra la palabra la busca en otro sitio, y ese otro
         * sitio casi nunca la explica mejor.
         */
        est('Los cambios de la pubertad', 'Lo que le pasa al cuerpo al crecer', '🌱', 'opcion_multiple', [
            omp('¿Qué es la pubertad?',                    ['La etapa de cambios que prepara al cuerpo para ser adulto', 'Una enfermedad', 'Un tipo de deporte'], 'La etapa de cambios que prepara al cuerpo para ser adulto'),
            omp('¿Qué provoca esos cambios?',              ['Las hormonas', 'La comida', 'El clima'], 'Las hormonas'),
            omp('¿Todos los cuerpos cambian al mismo ritmo?', ['No, cada uno a su tiempo', 'Sí, todos igual', 'Solo en verano'], 'No, cada uno a su tiempo'),
            omp('¿Es normal sentir emociones más intensas en esta etapa?', ['Sí, es parte del cambio', 'No', 'Solo si se duerme mal'], 'Sí, es parte del cambio'),
            omp('Si tengo dudas sobre mi cuerpo, ¿qué hago?', ['Preguntar a un adulto de confianza', 'Guardármelo', 'Buscar en cualquier sitio'], 'Preguntar a un adulto de confianza'),
        ]),

        est('Cuidar la cabeza y el cuerpo', 'Hábitos que sostienen todo', '🏆', 'desafio_final', [
            reto('¿Cuántas horas debe dormir un niño de 11 años?', ['9 a 11 horas', '4 horas', '15 horas'], '9 a 11 horas'),
            reto('¿Qué le hace el ejercicio al cerebro?',          ['Le ayuda a concentrarse mejor', 'Lo cansa siempre', 'Nada'], 'Le ayuda a concentrarse mejor'),
            reto('¿Qué pasa si no duermo bien?',                   ['Me cuesta concentrarme y estoy irritable', 'Nada', 'Crezco más'], 'Me cuesta concentrarme y estoy irritable'),
            reto('¿Es normal pedir ayuda cuando me siento mal?',   ['Sí, siempre', 'No, hay que aguantar', 'Solo si es grave'], 'Sí, siempre'),
            reto('¿Qué hace la adrenalina cuando nos asustamos?',  ['Acelera el corazón y nos prepara para reaccionar', 'Nos duerme', 'Nos da hambre'], 'Acelera el corazón y nos prepara para reaccionar'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · ECOSISTEMAS Y EQUILIBRIO
// =====================================================================

[
    'slug'  => 'cadenas-alimenticias',
    'title' => 'Cadenas alimenticias',
    'description' => 'Quién come a quién: productores, consumidores y descomponedores.',
    'objective' => 'Explicar el flujo de energía en un ecosistema mediante cadenas alimenticias.',
    'icon' => '🍃', 'nivel' => 'primaria-media', 'bloque' => 'ecosistemas',
    'duracion' => 13, 'tags' => ['comprension', 'logica', 'clasificacion'],
    'estaciones' => [

        est('Los tres papeles', 'Cada ser vivo tiene el suyo', '🎭', 'opcion_multiple', [
            omp('¿Quiénes son los productores?',        ['Las plantas', 'Los leones', 'Los hongos'], 'Las plantas', '🌿'),
            omp('¿Qué come un consumidor primario?',    ['Plantas', 'Otros animales', 'Piedras'], 'Plantas', '🐄'),
            omp('¿Qué come un consumidor secundario?',  ['Consumidores primarios', 'Plantas solamente', 'Nada'], 'Consumidores primarios', '🦁'),
            omp('¿Qué hacen los descomponedores?',      ['Descomponen restos y devuelven nutrientes al suelo', 'Cazan', 'Hacen fotosíntesis'], 'Descomponen restos y devuelven nutrientes al suelo', '🍄'),
            omp('¿De dónde viene la energía de toda la cadena?', ['Del Sol', 'Del suelo', 'Del agua'], 'Del Sol', '☀️'),
        ]),

        est('Arma la cadena', 'Ordena quién come a quién', '➡️', 'ordenar_secuencia', [
            'title' => 'Ordena la cadena, desde donde empieza la energía',
            'items' => ['☀️ Sol', '🌿 Hierba', '🐰 Conejo', '🦊 Zorro', '🍄 Hongo'],
        ]),

        est('Herbívoros, carnívoros y omnívoros', 'Según lo que comen', '🍽️', 'opcion_multiple', [
            omp('Un animal que solo come plantas es…',    ['Herbívoro', 'Carnívoro', 'Omnívoro'], 'Herbívoro', '🐄'),
            omp('Un animal que solo come carne es…',      ['Carnívoro', 'Herbívoro', 'Omnívoro'], 'Carnívoro', '🦁'),
            omp('Un animal que come de todo es…',         ['Omnívoro', 'Herbívoro', 'Carnívoro'], 'Omnívoro', '🐻'),
            omp('¿Qué es el ser humano?',                 ['Omnívoro', 'Carnívoro', 'Herbívoro'], 'Omnívoro', '🧒'),
            omp('¿Qué pasa si desaparecen todos los herbívoros?', ['Los carnívoros se quedan sin alimento', 'Nada', 'Crecen más plantas para siempre'], 'Los carnívoros se quedan sin alimento'),
        ]),

        est('Ecosistemas de Colombia', 'Distintos lugares, distinta vida', '🗺️', 'opcion_multiple', [
            omp('¿Qué ecosistema tiene mucha lluvia y árboles altos?', ['La selva', 'El desierto', 'El páramo'], 'La selva', '🌴'),
            omp('¿Qué ecosistema colombiano guarda el agua en la montaña?', ['El páramo', 'La playa', 'El desierto'], 'El páramo', '⛰️'),
            omp('¿Qué animal vive en el mar?',            ['El delfín', 'El oso de anteojos', 'La danta'], 'El delfín', '🐬'),
            omp('¿Qué es la biodiversidad?',              ['La variedad de seres vivos de un lugar', 'La cantidad de agua', 'El clima'], 'La variedad de seres vivos de un lugar'),
            omp('Colombia es uno de los países con más…', ['Biodiversidad del mundo', 'Desiertos', 'Nieve'], 'Biodiversidad del mundo'),
        ]),
    ],
],

[
    'slug'  => 'ciclos-y-equilibrio',
    'title' => 'Ciclos y equilibrio',
    'description' => 'El agua, el oxígeno y el carbono dan vueltas sin parar. Qué pasa cuando algo lo rompe.',
    'objective' => 'Describir los ciclos del agua y del carbono y reconocer alteraciones del equilibrio ecológico.',
    'icon' => '♻️', 'nivel' => 'primaria-superior', 'bloque' => 'ecosistemas',
    'duracion' => 15, 'tags' => ['comprension', 'logica', 'observacion'],
    'estaciones' => [

        est('El ciclo del agua', 'El agua no se gasta, da vueltas', '💧', 'opcion_multiple', [
            omp('¿Cómo se llama cuando el agua se convierte en vapor?', ['Evaporación', 'Condensación', 'Precipitación'], 'Evaporación'),
            omp('¿Cómo se llama cuando el vapor forma nubes?',          ['Condensación', 'Evaporación', 'Infiltración'], 'Condensación', '☁️'),
            omp('¿Cómo se llama cuando cae la lluvia?',                 ['Precipitación', 'Evaporación', 'Condensación'], 'Precipitación', '🌧️'),
            omp('¿Qué hace que el agua se evapore?',                    ['El calor del Sol', 'El viento solamente', 'La noche'], 'El calor del Sol', '☀️'),
            omp('¿Se acaba el agua del planeta al usarla?',             ['No, circula en un ciclo', 'Sí, desaparece', 'Solo la del mar'], 'No, circula en un ciclo'),
        ]),

        est('Ordena el ciclo', 'Un viaje que no termina', '🔄', 'ordenar_secuencia', [
            'title' => 'Ordena el ciclo del agua',
            'items' => ['El sol calienta el agua', 'El agua se evapora', 'Se forman las nubes', 'Cae la lluvia', 'El agua vuelve a los ríos'],
        ]),

        est('Oxígeno y carbono', 'Dos gases que también circulan', '🌬️', 'opcion_multiple', [
            omp('¿Qué gas producen las plantas y usamos para respirar?', ['Oxígeno', 'Dióxido de carbono', 'Nitrógeno'], 'Oxígeno'),
            omp('¿Qué gas expulsamos y usan las plantas?',               ['Dióxido de carbono', 'Oxígeno', 'Vapor'], 'Dióxido de carbono'),
            omp('¿Qué actividad humana libera mucho dióxido de carbono?', ['Quemar combustibles', 'Sembrar árboles', 'Caminar'], 'Quemar combustibles'),
            omp('¿Qué pasa si se talan muchos árboles?',                 ['Se produce menos oxígeno', 'Nada', 'Llueve más'], 'Se produce menos oxígeno'),
            omp('¿Cómo se llama el calentamiento por exceso de gases?',  ['Efecto invernadero', 'Ciclo del agua', 'Erosión'], 'Efecto invernadero'),
        ]),

        est('Cuando se rompe el equilibrio', 'Lo que hacemos tiene consecuencias', '⚠️', 'opcion_multiple', [
            omp('¿Qué es la contaminación?',                    ['Sustancias dañinas en el ambiente', 'Un tipo de planta', 'Una estación del año'], 'Sustancias dañinas en el ambiente'),
            omp('¿Qué pasa si se contamina un río?',            ['Mueren los seres vivos que dependen de él', 'Nada', 'Crece más rápido'], 'Mueren los seres vivos que dependen de él'),
            omp('¿Qué es una especie en peligro de extinción?', ['Una que puede desaparecer para siempre', 'Una nueva', 'Una muy común'], 'Una que puede desaparecer para siempre'),
            omp('¿Qué ayuda a proteger un ecosistema?',         ['Crear áreas protegidas', 'Talar árboles', 'Tirar basura'], 'Crear áreas protegidas'),
            omp('¿Qué puedo hacer yo desde casa?',              ['Ahorrar agua y separar residuos', 'Nada, soy pequeño', 'Dejar luces prendidas'], 'Ahorrar agua y separar residuos'),
        ]),

        /*
         * Completar huecos: el primer ejercicio del catálogo donde la
         * respuesta no está en el dibujo sino en la frase. Se acierta
         * leyendo lo que va antes y después del hueco, que es exactamente
         * la comprensión lectora aplicada a un texto de ciencias.
         */
        est('Completa el ciclo del agua', 'Lee y coloca cada palabra en su hueco', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Toca la palabra que falta en cada hueco', [
                [
                    'titulo' => 'El viaje del agua',
                    'texto'  => 'El calor del ___ hace que el agua de los ríos y del mar se ___ '
                              . 'y suba en forma de vapor. Arriba, el aire está más frío y el vapor se '
                              . '___ formando las ___. Cuando las gotas pesan demasiado, caen en forma '
                              . 'de ___ y el agua vuelve a empezar el mismo recorrido.',
                    'huecos' => ['sol', 'evapore', 'condensa', 'nubes', 'lluvia'],
                    'extra'  => ['viento', 'derrite', 'montañas'],
                ],
                [
                    'titulo' => 'Una cadena alimenticia',
                    'texto'  => 'Las plantas son los ___ porque fabrican su propio alimento con la luz. '
                              . 'El conejo se come la hierba, así que es un consumidor ___. El zorro se '
                              . 'come al conejo: es un consumidor ___. Cuando mueren, los ___ devuelven '
                              . 'los nutrientes al suelo.',
                    'huecos' => ['productores', 'primario', 'secundario', 'descomponedores'],
                    'extra'  => ['minerales', 'carnívoros'],
                ],
            ])),

        est('Desafío del equilibrio', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('Si desaparecen las abejas, ¿qué pasa con las plantas?', ['Muchas no podrían reproducirse', 'Nada', 'Crecen más'], 'Muchas no podrían reproducirse'),
            reto('¿Qué es un ecosistema?',                     ['Los seres vivos de un lugar y su entorno', 'Solo los animales', 'Solo el clima'], 'Los seres vivos de un lugar y su entorno'),
            reto('¿De dónde viene la energía de todos los ecosistemas?', ['Del Sol', 'Del suelo', 'Del viento'], 'Del Sol'),
            reto('¿Qué hacen las bacterias del suelo?',        ['Descomponen y devuelven nutrientes', 'Producen luz', 'Cazan insectos'], 'Descomponen y devuelven nutrientes'),
            reto('¿Por qué importa la biodiversidad?',         ['Un ecosistema variado resiste mejor los cambios', 'Solo por belleza', 'No importa'], 'Un ecosistema variado resiste mejor los cambios'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · MATERIA Y ENERGÍA
// =====================================================================

[
    'slug'  => 'la-materia-y-sus-estados',
    'title' => 'La materia y sus estados',
    'description' => 'Sólido, líquido y gas: propiedades de la materia y qué la hace cambiar de estado.',
    'objective' => 'Reconocer las propiedades de la materia y describir los cambios de estado.',
    'icon' => '🧊', 'nivel' => 'primaria-media', 'bloque' => 'materia-y-energia',
    'duracion' => 13, 'tags' => ['observacion', 'comprension', 'clasificacion'],
    'estaciones' => [

        est('Tres estados', 'Sólido, líquido y gaseoso', '🔺', 'opcion_multiple', [
            omp('¿En qué estado está el hielo?',      ['Sólido', 'Líquido', 'Gaseoso'], 'Sólido', '🧊'),
            omp('¿En qué estado está el agua del vaso?', ['Líquido', 'Sólido', 'Gaseoso'], 'Líquido', '🥛'),
            omp('¿En qué estado está el vapor?',      ['Gaseoso', 'Sólido', 'Líquido'], 'Gaseoso', '💨'),
            omp('¿Qué estado tiene forma propia?',    ['El sólido', 'El líquido', 'El gas'], 'El sólido'),
            omp('¿Qué estado ocupa todo el recipiente?', ['El gas', 'El sólido', 'Ninguno'], 'El gas'),
        ]),

        est('Cambios de estado', 'El calor manda', '🔥', 'opcion_multiple', [
            omp('Cuando el hielo se derrite es…',       ['Fusión', 'Evaporación', 'Solidificación'], 'Fusión'),
            omp('Cuando el agua hierve y se hace vapor es…', ['Evaporación', 'Fusión', 'Condensación'], 'Evaporación'),
            omp('Cuando el vapor se enfría y se hace agua es…', ['Condensación', 'Fusión', 'Evaporación'], 'Condensación'),
            omp('Cuando el agua se congela es…',        ['Solidificación', 'Fusión', 'Condensación'], 'Solidificación'),
            omp('¿A qué temperatura hierve el agua a nivel del mar?', ['100 °C', '50 °C', '0 °C'], '100 °C'),
        ]),

        est('Propiedades de la materia', 'Masa, peso y volumen', '⚖️', 'opcion_multiple', [
            omp('¿Qué es la masa?',                  ['La cantidad de materia de un cuerpo', 'El espacio que ocupa', 'Su color'], 'La cantidad de materia de un cuerpo'),
            omp('¿Qué es el volumen?',               ['El espacio que ocupa', 'La cantidad de materia', 'Su temperatura'], 'El espacio que ocupa'),
            omp('¿Con qué se mide la masa?',         ['Con la balanza', 'Con la regla', 'Con el termómetro'], 'Con la balanza', '⚖️'),
            omp('¿Cambia la masa de un objeto en la Luna?', ['No, cambia su peso', 'Sí, desaparece', 'Se duplica'], 'No, cambia su peso'),
            omp('¿Qué flota en el agua?',            ['Lo menos denso que el agua', 'Lo más pesado', 'Todo'], 'Lo menos denso que el agua'),
        ]),

        est('Flota o se hunde', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Flota en el agua?', 'Piensa en la densidad', [
                ['e' => '🪵', 'n' => 'Madera',  'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',  'ok' => false],
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '🔑', 'n' => 'Llave',   'ok' => false],
                ['e' => '🧊', 'n' => 'Hielo',   'ok' => true],
                ['e' => '🪙', 'n' => 'Moneda',  'ok' => false],
                ['e' => '🛟', 'n' => 'Salvavidas', 'ok' => true],
            ])),
    ],
],

[
    'slug'  => 'mezclas-y-separacion',
    'title' => 'Mezclas y separación',
    'description' => 'Sustancias puras y mezclas, y cómo separarlas: filtrar, decantar, tamizar.',
    'objective' => 'Diferenciar mezclas homogéneas de heterogéneas y aplicar métodos de separación.',
    'icon' => '🧪', 'nivel' => 'primaria-superior', 'bloque' => 'materia-y-energia',
    'duracion' => 14, 'tags' => ['clasificacion', 'observacion', 'logica'],
    'estaciones' => [

        est('Puro o mezclado', 'Dos formas de encontrar la materia', '🔍', 'opcion_multiple', [
            omp('¿Qué es una sustancia pura?',         ['Está formada por un solo tipo de materia', 'Tiene muchas cosas', 'Siempre es líquida'], 'Está formada por un solo tipo de materia'),
            omp('¿Es el agua de mar una mezcla?',      ['Sí, agua con sales', 'No, es pura', 'Solo en invierno'], 'Sí, agua con sales', '🌊'),
            omp('¿Es una ensalada una mezcla?',        ['Sí, y se ven sus partes', 'No', 'Solo si tiene tomate'], 'Sí, y se ven sus partes', '🥗'),
            omp('En una mezcla homogénea, las partes…', ['No se distinguen a simple vista', 'Se ven claramente', 'Están separadas'], 'No se distinguen a simple vista'),
            omp('En una mezcla heterogénea, las partes…', ['Se distinguen a simple vista', 'No se ven', 'Se evaporan'], 'Se distinguen a simple vista'),
        ]),

        est('Clasifica la mezcla', 'Homogénea o heterogénea', '🗂️', 'opcion_multiple', [
            omp('Agua con azúcar disuelta es…',    ['Homogénea', 'Heterogénea', 'Pura'], 'Homogénea'),
            omp('Agua con arena es…',              ['Heterogénea', 'Homogénea', 'Pura'], 'Heterogénea'),
            omp('Aceite y agua forman una mezcla…', ['Heterogénea', 'Homogénea', 'Pura'], 'Heterogénea'),
            omp('El aire es una mezcla…',          ['Homogénea de gases', 'Heterogénea', 'Pura'], 'Homogénea de gases'),
            omp('Una sopa con verduras es…',       ['Heterogénea', 'Homogénea', 'Pura'], 'Heterogénea'),
        ]),

        est('Métodos de separación', 'Cada mezcla pide su método', '🧰', 'opcion_multiple', [
            omp('¿Cómo separo arena del agua?',           ['Filtrando', 'Evaporando', 'Imantando'], 'Filtrando'),
            omp('¿Cómo separo la sal del agua salada?',   ['Evaporando el agua', 'Filtrando', 'Con un imán'], 'Evaporando el agua'),
            omp('¿Cómo separo aceite del agua?',          ['Decantando', 'Filtrando', 'Con un imán'], 'Decantando'),
            omp('¿Cómo separo clavos de la arena?',       ['Con un imán', 'Filtrando', 'Evaporando'], 'Con un imán', '🧲'),
            omp('¿Cómo separo piedras grandes de arena fina?', ['Tamizando', 'Evaporando', 'Decantando'], 'Tamizando'),
        ]),

        est('Crucigrama de la materia', 'Cada pista es una definición', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'MEZCLA',     'pista' => 'Dos o más sustancias juntas sin combinarse'],
                ['w' => 'FILTRAR',    'pista' => 'Separar un sólido de un líquido con un colador'],
                ['w' => 'IMAN',       'pista' => 'Sirve para separar el hierro de la arena'],
                ['w' => 'MASA',       'pista' => 'La cantidad de materia que tiene un cuerpo'],
                ['w' => 'VOLUMEN',    'pista' => 'El espacio que ocupa un cuerpo'],
                ['w' => 'SAL',        'pista' => 'Queda en el fondo al evaporar agua de mar'],
                ['w' => 'PURA',       'pista' => 'Así es una sustancia de un solo tipo de materia'],
            ])),

        est('Desafío del laboratorio', 'Aplica lo aprendido', '🏆', 'desafio_final', [
            reto('Tengo agua con hojas. ¿Qué método uso?',        ['Filtración', 'Evaporación', 'Imantación'], 'Filtración'),
            reto('Al evaporar agua salada, ¿qué queda en el fondo?', ['La sal', 'El agua', 'Nada'], 'La sal'),
            reto('¿Es un cambio físico derretir hielo?',          ['Sí, sigue siendo agua', 'No, es químico', 'Depende'], 'Sí, sigue siendo agua'),
            reto('¿Es un cambio químico quemar un papel?',        ['Sí, se forma otra sustancia', 'No', 'Solo si es blanco'], 'Sí, se forma otra sustancia'),
            reto('¿Se puede recuperar el papel quemado?',         ['No, el cambio químico no se deshace fácil', 'Sí, mojándolo', 'Sí, enfriándolo'], 'No, el cambio químico no se deshace fácil'),
        ]),
    ],
],

[
    'slug'  => 'fuerza-luz-y-sonido',
    'title' => 'Fuerza, luz y sonido',
    'description' => 'Qué mueve las cosas, cómo se comporta la luz y cómo viaja el sonido.',
    'objective' => 'Relacionar fuerzas con el movimiento y describir fenómenos de luz y sonido.',
    'icon' => '💡', 'nivel' => 'primaria-media', 'bloque' => 'materia-y-energia',
    'duracion' => 14, 'tags' => ['observacion', 'comprension', 'logica'],
    'estaciones' => [

        est('Las fuerzas', 'Empujar, halar y frenar', '💪', 'opcion_multiple', [
            omp('¿Qué es una fuerza?',                    ['Un empujón o un halón sobre un objeto', 'Un color', 'Un sonido'], 'Un empujón o un halón sobre un objeto'),
            omp('¿Qué hace la fuerza de gravedad?',       ['Atrae los objetos hacia la Tierra', 'Los empuja al cielo', 'Los ilumina'], 'Atrae los objetos hacia la Tierra', '🌍'),
            omp('¿Qué fuerza frena una pelota que rueda?', ['La fricción', 'La luz', 'El sonido'], 'La fricción', '⚽'),
            omp('¿Dónde hay menos fricción?',             ['Sobre el hielo', 'Sobre la arena', 'Sobre el pasto'], 'Sobre el hielo', '⛸️'),
            omp('¿Puede una fuerza cambiar la forma de algo?', ['Sí, como al aplastar plastilina', 'No', 'Solo el calor'], 'Sí, como al aplastar plastilina'),
        ]),

        est('La luz', 'Viaja recto y rebota', '🔦', 'opcion_multiple', [
            omp('¿Cómo viaja la luz?',                  ['En línea recta', 'En zigzag', 'Hacia abajo'], 'En línea recta'),
            omp('¿Qué pasa cuando la luz choca con un espejo?', ['Se refleja', 'Desaparece', 'Se calienta'], 'Se refleja', '🪞'),
            omp('Un material que deja pasar toda la luz es…', ['Transparente', 'Opaco', 'Translúcido'], 'Transparente'),
            omp('Un material que no deja pasar la luz es…', ['Opaco', 'Transparente', 'Brillante'], 'Opaco'),
            omp('¿Por qué se forma una sombra?',        ['Porque un objeto opaco bloquea la luz', 'Porque hace frío', 'Por el viento'], 'Porque un objeto opaco bloquea la luz'),
        ]),

        est('El sonido', 'Vibración que viaja', '🔊', 'opcion_multiple', [
            omp('¿Cómo se produce el sonido?',          ['Por vibraciones', 'Por la luz', 'Por el color'], 'Por vibraciones'),
            omp('¿Por dónde viaja el sonido?',          ['Por el aire, el agua y los sólidos', 'Solo por el aire', 'Por el vacío'], 'Por el aire, el agua y los sólidos'),
            omp('¿Hay sonido en el espacio vacío?',     ['No, no hay aire que vibre', 'Sí', 'Solo de día'], 'No, no hay aire que vibre', '🚀'),
            omp('¿Qué viaja más rápido, la luz o el sonido?', ['La luz', 'El sonido', 'Igual'], 'La luz'),
            omp('Por eso, en una tormenta…',            ['Vemos el rayo antes de oír el trueno', 'Oímos antes de ver', 'Todo llega junto'], 'Vemos el rayo antes de oír el trueno', '⛈️'),
        ]),

        est('Máquinas simples', 'Herramientas que facilitan el trabajo', '⚙️', 'opcion_multiple', [
            omp('¿Qué es una máquina simple?',        ['Un instrumento que facilita el trabajo', 'Un motor eléctrico', 'Un computador'], 'Un instrumento que facilita el trabajo'),
            omp('¿Qué máquina simple es un balancín?', ['La palanca', 'La polea', 'El tornillo'], 'La palanca'),
            omp('¿Qué máquina usa una cuerda y una rueda para subir cosas?', ['La polea', 'La palanca', 'La cuña'], 'La polea'),
            omp('¿Qué máquina simple es una rampa?',  ['El plano inclinado', 'La polea', 'La rueda'], 'El plano inclinado'),
            omp('¿Qué máquina simple es un hacha?',   ['La cuña', 'La polea', 'La palanca'], 'La cuña', '🪓'),
        ]),
    ],
],

[
    'slug'  => 'electricidad-y-magnetismo',
    'title' => 'Electricidad y magnetismo',
    'description' => 'Circuitos, conductores y aislantes, imanes y polos: la energía que mueve los aparatos.',
    'objective' => 'Identificar los componentes de un circuito eléctrico y explicar la atracción magnética.',
    'icon' => '🔌', 'nivel' => 'primaria-superior', 'bloque' => 'materia-y-energia',
    'duracion' => 14, 'tags' => ['comprension', 'logica', 'tecnologia'],
    'estaciones' => [

        est('El circuito eléctrico', 'Un camino cerrado para la corriente', '🔋', 'opcion_multiple', [
            omp('¿Qué necesita un circuito para funcionar?', ['Un camino cerrado', 'Estar abierto', 'Estar mojado'], 'Un camino cerrado'),
            omp('¿Qué componente da la energía?',      ['La pila', 'El bombillo', 'El cable'], 'La pila', '🔋'),
            omp('¿Qué componente transforma la energía en luz?', ['El bombillo', 'La pila', 'El interruptor'], 'El bombillo', '💡'),
            omp('¿Para qué sirve el interruptor?',     ['Para abrir o cerrar el circuito', 'Para dar energía', 'Para dar luz'], 'Para abrir o cerrar el circuito'),
            omp('Si el circuito está abierto, el bombillo…', ['No se enciende', 'Se enciende igual', 'Explota'], 'No se enciende'),
        ]),

        est('Conductores y aislantes', 'Por dónde pasa y por dónde no', '🧤', 'opcion_multiple', [
            omp('¿Cuál es un buen conductor?',        ['El cobre', 'El plástico', 'La madera'], 'El cobre'),
            omp('¿Cuál es un aislante?',              ['El plástico', 'El hierro', 'El aluminio'], 'El plástico'),
            omp('¿Por qué los cables llevan plástico por fuera?', ['Para aislar y protegernos', 'Por el color', 'Para pesar menos'], 'Para aislar y protegernos'),
            omp('¿Es el agua peligrosa cerca de la electricidad?', ['Sí, conduce la corriente', 'No', 'Solo si está caliente'], 'Sí, conduce la corriente', '⚠️'),
            omp('¿Qué hago si veo un cable pelado?',  ['Aviso a un adulto y no lo toco', 'Lo toco', 'Lo mojo'], 'Aviso a un adulto y no lo toco'),
        ]),

        est('Los imanes', 'Atraer y repeler', '🧲', 'opcion_multiple', [
            omp('¿Qué materiales atrae un imán?',     ['El hierro y el acero', 'El plástico', 'El papel'], 'El hierro y el acero'),
            omp('¿Cuántos polos tiene un imán?',      ['Dos: norte y sur', 'Uno', 'Cuatro'], 'Dos: norte y sur'),
            omp('¿Qué pasa entre dos polos iguales?', ['Se repelen', 'Se atraen', 'Nada'], 'Se repelen'),
            omp('¿Qué pasa entre dos polos distintos?', ['Se atraen', 'Se repelen', 'Se rompen'], 'Se atraen'),
            omp('¿Por qué funciona una brújula?',     ['La Tierra actúa como un gran imán', 'Por el viento', 'Por el sol'], 'La Tierra actúa como un gran imán', '🧭'),
        ]),

        est('Ahorrar energía', 'La electricidad no es infinita', '🏆', 'desafio_final', [
            reto('¿Qué gasta energía sin que lo notemos?',     ['Los aparatos en espera', 'Los libros', 'Las sillas'], 'Los aparatos en espera'),
            reto('¿Qué bombillo gasta menos?',                 ['El LED', 'El incandescente', 'Da igual'], 'El LED'),
            reto('¿De dónde sale la electricidad de tu casa?', ['De centrales eléctricas', 'De la nada', 'Del cable solamente'], 'De centrales eléctricas'),
            reto('¿Cuál es una fuente de energía renovable?',  ['El sol', 'El carbón', 'El petróleo'], 'El sol'),
            reto('¿Qué puedo hacer para ahorrar energía?',     ['Apagar lo que no uso', 'Dejar todo encendido', 'Nada'], 'Apagar lo que no uso'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · EL PLANETA QUE HABITAMOS
// =====================================================================

[
    'slug'  => 'el-tiempo-y-el-clima',
    'title' => 'El tiempo y el clima',
    'description' => 'Nubes, lluvia, temperatura y viento: observar el tiempo y ver cómo nos afecta.',
    'objective' => 'Reconocer los fenómenos atmosféricos y su influencia en la vida de las personas.',
    'icon' => '🌦️', 'nivel' => 'primaria-inicial', 'bloque' => 'el-planeta-que-habitamos',
    'duracion' => 12, 'tags' => ['observacion', 'comprension'],
    'estaciones' => [

        est('¿Qué tiempo hace?', 'Une el símbolo con su nombre', '🔗', 'emparejar', [
            ['e' => '☀️', 'w' => 'Soleado'],
            ['e' => '🌧️', 'w' => 'Lluvioso'],
            ['e' => '☁️', 'w' => 'Nublado'],
            ['e' => '⛈️', 'w' => 'Tormenta'],
            ['e' => '💨', 'w' => 'Ventoso'],
            ['e' => '🌫️', 'w' => 'Neblina'],
        ]),

        est('Cómo nos afecta', 'El tiempo cambia lo que hacemos', '🧥', 'opcion_multiple', [
            omp('Si llueve mucho, ¿qué me pongo?',       ['Impermeable y botas', 'Vestido de baño', 'Nada especial'], 'Impermeable y botas', '🌧️'),
            omp('Si hace mucho sol, ¿qué me pongo?',     ['Gorra y protector solar', 'Abrigo grueso', 'Bufanda'], 'Gorra y protector solar', '☀️'),
            omp('¿Qué actividad NO se puede hacer con tormenta?', ['Jugar en el parque', 'Leer en casa', 'Dibujar'], 'Jugar en el parque', '⛈️'),
            omp('¿A quién le importa mucho el clima para trabajar?', ['Al agricultor', 'Al bibliotecario', 'Al pintor de casas'], 'Al agricultor', '👨‍🌾'),
            omp('¿Con qué instrumento se mide la temperatura?', ['El termómetro', 'La regla', 'La balanza'], 'El termómetro', '🌡️'),
        ]),

        est('Tiempo y clima no son lo mismo', 'Uno es de hoy, otro de siempre', '📅', 'opcion_multiple', [
            omp('¿Qué es el tiempo atmosférico?',   ['Cómo está el día hoy', 'Cómo es siempre en un lugar', 'La hora'], 'Cómo está el día hoy'),
            omp('¿Qué es el clima?',                ['Cómo suele ser el tiempo en un lugar durante años', 'Cómo está hoy', 'La temperatura de ahora'], 'Cómo suele ser el tiempo en un lugar durante años'),
            omp('«Hoy llueve» habla de…',           ['El tiempo', 'El clima', 'La estación'], 'El tiempo'),
            omp('«En la costa hace calor todo el año» habla de…', ['El clima', 'El tiempo', 'La lluvia de hoy'], 'El clima'),
            omp('¿Quién estudia el tiempo?',        ['El meteorólogo', 'El geólogo', 'El biólogo'], 'El meteorólogo'),
        ]),

        est('Observa el cielo', 'Decide si es verdad', '⚡', 'juego_rapido',
            conTitulo('¿Es verdad?', 'Responde rápido', [
                ['e' => '☁️', 'n' => 'Las nubes son gotitas de agua', 'ok' => true],
                ['e' => '🌈', 'n' => 'El arcoíris sale al llover con sol', 'ok' => true],
                ['e' => '❄️', 'n' => 'La nieve cae cuando hace calor', 'ok' => false],
                ['e' => '💨', 'n' => 'El viento es aire en movimiento', 'ok' => true],
                ['e' => '⚡', 'n' => 'El trueno se ve antes que el rayo', 'ok' => false],
                ['e' => '🌡️', 'n' => 'El termómetro mide la lluvia', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'el-agua-un-tesoro',
    'title' => 'El agua, un tesoro',
    'description' => 'El agua como disolvente, qué flota y qué se hunde, y por qué hay que cuidarla.',
    'objective' => 'Investigar propiedades del agua y valorar su importancia para todos los seres vivos.',
    'icon' => '💧', 'nivel' => 'primaria-media', 'bloque' => 'el-planeta-que-habitamos',
    'duracion' => 13, 'tags' => ['observacion', 'comprension', 'seguridad'],
    'estaciones' => [

        est('El agua disuelve', 'No todo se disuelve igual', '🥄', 'opcion_multiple', [
            omp('¿Qué se disuelve en agua?',           ['La sal', 'La arena', 'El aceite'], 'La sal', '🧂'),
            omp('¿Qué NO se disuelve en agua?',        ['El aceite', 'El azúcar', 'La sal'], 'El aceite', '🫒'),
            omp('Cuando algo se disuelve, ¿desaparece?', ['No, sigue ahí aunque no se vea', 'Sí', 'Solo de noche'], 'No, sigue ahí aunque no se vea'),
            omp('¿Qué ayuda a disolver más rápido?',   ['Revolver y calentar', 'Enfriar', 'Esperar quieto'], 'Revolver y calentar'),
            omp('Por eso al agua se le llama…',        ['El disolvente universal', 'Un mineral', 'Un gas'], 'El disolvente universal'),
        ]),

        est('Agua para la vida', 'Todos dependemos de ella', '🌍', 'opcion_multiple', [
            omp('¿Puede un ser vivo vivir sin agua?',    ['No', 'Sí, mucho tiempo', 'Solo los peces'], 'No'),
            omp('¿Qué parte del cuerpo humano es agua?', ['Más de la mitad', 'Casi nada', 'Un décimo'], 'Más de la mitad'),
            omp('¿Cuánta del agua del planeta es dulce?', ['Muy poca', 'Casi toda', 'La mitad'], 'Muy poca'),
            omp('¿Cómo respiran los peces bajo el agua?', ['Con branquias', 'Con pulmones', 'Por la piel'], 'Con branquias', '🐟'),
            omp('¿Qué le permite al pez mantenerse a media agua?', ['La vejiga natatoria', 'Las escamas', 'Los ojos'], 'La vejiga natatoria'),
        ]),

        est('Cuidar el agua', 'Cada gota cuenta', '🚰', 'opcion_multiple', [
            omp('¿Qué ahorra más agua al lavarse los dientes?', ['Cerrar la llave mientras cepillo', 'Dejarla abierta', 'Usar dos vasos'], 'Cerrar la llave mientras cepillo'),
            omp('¿Qué hago si veo una llave goteando?',   ['Aviso para que la arreglen', 'La ignoro', 'La abro más'], 'Aviso para que la arreglen'),
            omp('¿Qué contamina un río?',                 ['Tirar basura y químicos', 'Que llueva', 'Los peces'], 'Tirar basura y químicos'),
            omp('¿Qué gasta más agua?',                   ['Bañarse media hora', 'Bañarse cinco minutos', 'Lavarse las manos'], 'Bañarse media hora'),
            omp('¿Es potable toda el agua?',              ['No, solo la tratada', 'Sí, toda', 'Solo la del río'], 'No, solo la tratada'),
        ]),

        est('Desafío del agua', 'Cinco preguntas para cerrar', '🏆', 'desafio_final', [
            reto('¿A qué temperatura se congela el agua?',     ['0 °C', '100 °C', '10 °C'], '0 °C'),
            reto('¿Qué pasa con el volumen del agua al congelarse?', ['Aumenta', 'Disminuye', 'No cambia'], 'Aumenta'),
            reto('Por eso el hielo…',                          ['Flota en el agua', 'Se hunde', 'Se disuelve'], 'Flota en el agua'),
            reto('¿Qué es el agua potable?',                   ['La que se puede beber sin riesgo', 'La del mar', 'La de lluvia'], 'La que se puede beber sin riesgo'),
            reto('¿Por qué no se debe beber agua de un río sin tratar?', ['Puede tener microbios', 'Sabe mal', 'Está fría'], 'Puede tener microbios'),
        ]),
    ],
],

[
    'slug'  => 'residuos-y-reciclaje',
    'title' => 'Residuos y reciclaje',
    'description' => 'Separar la basura, entender por qué sirve y qué hacer con los residuos peligrosos.',
    'objective' => 'Clasificar residuos según su material y valorar la separación en la fuente.',
    'icon' => '🗑️', 'nivel' => 'primaria-media', 'bloque' => 'el-planeta-que-habitamos',
    'duracion' => 13, 'tags' => ['clasificacion', 'convivencia', 'observacion'],
    'estaciones' => [

        est('¿En qué caneca va?', 'Cada residuo a su sitio', '♻️', 'opcion_multiple', [
            omp('¿Dónde va una botella de plástico?',  ['Reciclables', 'Orgánicos', 'Peligrosos'], 'Reciclables', '🍶'),
            omp('¿Dónde va una cáscara de banano?',    ['Orgánicos', 'Reciclables', 'Peligrosos'], 'Orgánicos', '🍌'),
            omp('¿Dónde va una hoja de papel limpia?', ['Reciclables', 'Orgánicos', 'Peligrosos'], 'Reciclables', '📄'),
            omp('¿Dónde va una pila usada?',           ['Punto especial de residuos peligrosos', 'Orgánicos', 'Reciclables'], 'Punto especial de residuos peligrosos', '🔋'),
            omp('¿Dónde va una servilleta usada con comida?', ['No aprovechables', 'Reciclables', 'Peligrosos'], 'No aprovechables'),
        ]),

        est('Por qué separar', 'No es una manía, es útil', '🤔', 'opcion_multiple', [
            omp('¿Para qué sirve separar la basura?',   ['Para poder reciclar los materiales', 'Para que se vea bonito', 'Para nada'], 'Para poder reciclar los materiales'),
            omp('¿Qué pasa si mezclo papel con comida?', ['El papel ya no se puede reciclar', 'Nada', 'Se recicla mejor'], 'El papel ya no se puede reciclar'),
            omp('¿Cuánto tarda en degradarse una botella de plástico?', ['Cientos de años', 'Una semana', 'Un mes'], 'Cientos de años'),
            omp('¿Qué se puede hacer con las cáscaras de fruta?', ['Compost para las plantas', 'Nada', 'Quemarlas'], 'Compost para las plantas'),
            omp('¿Qué es un material de varias capas, como una caja de jugo?', ['Un material compuesto', 'Papel puro', 'Vidrio'], 'Un material compuesto'),
        ]),

        est('Las tres erres', 'Reducir, reutilizar, reciclar', '3️⃣', 'opcion_multiple', [
            omp('¿Cuál es la primera y más importante erre?', ['Reducir', 'Reciclar', 'Reutilizar'], 'Reducir'),
            omp('Usar un frasco de vidrio como portalápices es…', ['Reutilizar', 'Reciclar', 'Reducir'], 'Reutilizar'),
            omp('Llevar bolsa de tela al mercado es…',       ['Reducir', 'Reciclar', 'Botar'], 'Reducir', '🛍️'),
            omp('Convertir botellas viejas en ropa nueva es…', ['Reciclar', 'Reducir', 'Reutilizar'], 'Reciclar'),
            omp('¿Cuál es el residuo que menos contamina?',  ['El que no se produce', 'El de plástico', 'El de vidrio'], 'El que no se produce'),
        ]),

        est('Clasifica rápido', 'Contrarreloj', '⚡', 'juego_rapido',
            conTitulo('¿Es reciclable?', 'Decide rápido', [
                ['e' => '🍶', 'n' => 'Botella de vidrio', 'ok' => true],
                ['e' => '📰', 'n' => 'Periódico',         'ok' => true],
                ['e' => '🍕', 'n' => 'Resto de comida',   'ok' => false],
                ['e' => '🥫', 'n' => 'Lata de aluminio',  'ok' => true],
                ['e' => '🧻', 'n' => 'Papel higiénico usado', 'ok' => false],
                ['e' => '📦', 'n' => 'Caja de cartón',    'ok' => true],
                ['e' => '🔋', 'n' => 'Pila',              'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'el-universo-y-el-sistema-solar',
    'title' => 'El universo y el sistema solar',
    'description' => 'Estrellas, planetas y satélites: dónde estamos dentro de todo lo que existe.',
    'objective' => 'Describir los elementos del sistema solar y la posición de la Tierra en el universo.',
    'icon' => '🪐', 'nivel' => 'primaria-superior', 'bloque' => 'el-planeta-que-habitamos',
    'duracion' => 14, 'tags' => ['observacion', 'comprension', 'geografia'],
    'estaciones' => [

        est('¿Qué hay allá arriba?', 'Estrellas, planetas y satélites', '✨', 'opcion_multiple', [
            omp('¿Qué es el Sol?',                    ['Una estrella', 'Un planeta', 'Un satélite'], 'Una estrella', '☀️'),
            omp('¿Qué es la Luna?',                   ['Un satélite de la Tierra', 'Una estrella', 'Un planeta'], 'Un satélite de la Tierra', '🌙'),
            omp('¿Qué es la Tierra?',                 ['Un planeta', 'Una estrella', 'Una galaxia'], 'Un planeta', '🌍'),
            omp('¿Cómo se llama nuestra galaxia?',    ['La Vía Láctea', 'Andrómeda', 'El Sistema Solar'], 'La Vía Láctea'),
            omp('¿Producen luz propia los planetas?', ['No, reflejan la del Sol', 'Sí', 'Solo de noche'], 'No, reflejan la del Sol'),
        ]),

        est('Los planetas en orden', 'Del más cercano al Sol al más lejano', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los planetas desde el Sol',
            'items' => ['Mercurio', 'Venus', 'Tierra', 'Marte', 'Júpiter'],
        ]),

        est('Rotación y traslación', 'Dos movimientos, dos consecuencias', '🔄', 'opcion_multiple', [
            omp('¿Qué es la rotación?',                  ['El giro de la Tierra sobre sí misma', 'La vuelta alrededor del Sol', 'El giro de la Luna'], 'El giro de la Tierra sobre sí misma'),
            omp('¿Qué produce la rotación?',             ['El día y la noche', 'Las estaciones', 'Las mareas'], 'El día y la noche'),
            omp('¿Cuánto tarda la rotación?',            ['24 horas', 'Un año', 'Un mes'], '24 horas'),
            omp('¿Qué es la traslación?',                ['La vuelta de la Tierra alrededor del Sol', 'El giro sobre sí misma', 'El movimiento de la Luna'], 'La vuelta de la Tierra alrededor del Sol'),
            omp('¿Cuánto tarda la traslación?',          ['365 días', '24 horas', '30 días'], '365 días'),
        ]),

        est('Desafío espacial', 'Cinco preguntas del universo', '🏆', 'desafio_final', [
            reto('¿Cuál es el planeta más grande del sistema solar?', ['Júpiter', 'Tierra', 'Marte'], 'Júpiter'),
            reto('¿Qué planeta se conoce como el planeta rojo?',      ['Marte', 'Venus', 'Saturno'], 'Marte'),
            reto('¿Por qué hay vida en la Tierra y no en Marte?',     ['Tiene agua líquida, aire y temperatura adecuada', 'Es más grande', 'Está más lejos'], 'Tiene agua líquida, aire y temperatura adecuada'),
            reto('¿Qué planeta tiene anillos muy visibles?',          ['Saturno', 'Mercurio', 'Venus'], 'Saturno'),
            reto('¿Cuánto tarda la luz del Sol en llegar a la Tierra?', ['Unos 8 minutos', 'Un segundo', 'Un día'], 'Unos 8 minutos'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'microbios-y-defensas',
    'title' => 'Microbios y defensas',
    'description' => 'Seres vivos que no se ven: cuáles enferman, cuáles ayudan y cómo se defiende el cuerpo.',
    'objective' => 'Reconocer los microorganismos y las medidas básicas de prevención de enfermedades.',
    'icon' => '🦠', 'nivel' => 'primaria-media', 'bloque' => 'clasificar-la-vida',
    'duracion' => 12, 'tags' => ['salud', 'comprension', 'observacion'],
    'estaciones' => [

        est('Lo que no se ve', 'Seres vivos diminutos', '🔬', 'opcion_multiple', [
            omp('¿Qué es un microorganismo?',           ['Un ser vivo tan pequeño que necesita microscopio', 'Un insecto', 'Un mineral'], 'Un ser vivo tan pequeño que necesita microscopio'),
            omp('¿Son todos los microbios dañinos?',    ['No, muchos son útiles', 'Sí, todos', 'Solo los grandes'], 'No, muchos son útiles'),
            omp('¿Qué microbio ayuda a hacer el yogur?', ['Las bacterias', 'Los virus', 'Ninguno'], 'Las bacterias', '🥛'),
            omp('¿Qué microorganismo hace crecer el pan?', ['La levadura, que es un hongo', 'Un virus', 'Una bacteria dañina'], 'La levadura, que es un hongo', '🍞'),
            omp('¿Dónde hay bacterias útiles en el cuerpo?', ['En el intestino', 'En el cerebro', 'En los huesos'], 'En el intestino'),
        ]),

        est('Cómo se contagian', 'Las vías de transmisión', '🤧', 'opcion_multiple', [
            omp('¿Cómo se transmite una gripa?',        ['Por gotitas al toser o estornudar', 'Por mirar a alguien', 'Por el frío solo'], 'Por gotitas al toser o estornudar'),
            omp('¿Dónde hay que estornudar?',           ['En el codo o un pañuelo', 'En la mano', 'Al aire'], 'En el codo o un pañuelo'),
            omp('¿Por qué lavarse las manos evita enfermedades?', ['Arrastra los microbios antes de que entren al cuerpo', 'Los mata con el olor', 'No las evita'], 'Arrastra los microbios antes de que entren al cuerpo'),
            omp('¿Se puede contagiar algo por compartir un vaso?', ['Sí', 'No', 'Solo si está frío'], 'Sí'),
            omp('¿Por qué se cocina bien la carne?',    ['Para eliminar microbios peligrosos', 'Para que sepa mejor solamente', 'Por costumbre'], 'Para eliminar microbios peligrosos'),
        ]),

        est('Las defensas del cuerpo', 'El sistema inmune', '🛡️', 'opcion_multiple', [
            omp('¿Qué células nos defienden de las infecciones?', ['Los glóbulos blancos', 'Los glóbulos rojos', 'Las neuronas'], 'Los glóbulos blancos'),
            omp('¿Qué es la fiebre?',                   ['Una defensa del cuerpo ante una infección', 'Una enfermedad en sí misma', 'Un microbio'], 'Una defensa del cuerpo ante una infección'),
            omp('¿Para qué sirve una vacuna?',          ['Enseña al cuerpo a defenderse antes de enfermar', 'Cura cualquier cosa', 'Da energía'], 'Enseña al cuerpo a defenderse antes de enfermar', '💉'),
            omp('¿Qué es la primera barrera del cuerpo?', ['La piel', 'El hígado', 'El corazón'], 'La piel'),
            omp('¿Ayuda dormir bien y comer sano a las defensas?', ['Sí, mucho', 'No', 'Solo comer'], 'Sí, mucho'),
        ]),

        est('Prevenir', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Ayuda a no enfermarse?', 'Responde rápido', [
                ['e' => '🧼', 'n' => 'Lavarse las manos',       'ok' => true],
                ['e' => '🤧', 'n' => 'Estornudar en la mano',   'ok' => false],
                ['e' => '💉', 'n' => 'Ponerse las vacunas',     'ok' => true],
                ['e' => '🥤', 'n' => 'Compartir el mismo vaso', 'ok' => false],
                ['e' => '😴', 'n' => 'Dormir suficiente',       'ok' => true],
                ['e' => '🍖', 'n' => 'Comer carne cruda',       'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'energias-del-futuro',
    'title' => 'Energías del futuro',
    'description' => 'Renovables y no renovables: de dónde sale la energía y cuál se acaba.',
    'objective' => 'Clasificar fuentes de energía y valorar el uso de las renovables.',
    'icon' => '☀️', 'nivel' => 'primaria-superior', 'bloque' => 'materia-y-energia',
    'duracion' => 13, 'tags' => ['comprension', 'clasificacion', 'observacion'],
    'estaciones' => [

        est('¿Se acaba o no?', 'Renovable y no renovable', '♻️', 'opcion_multiple', [
            omp('¿Qué es una energía renovable?',      ['Una que no se agota, como el sol o el viento', 'Una que se acaba', 'Una que no contamina nunca'], 'Una que no se agota, como el sol o el viento'),
            omp('¿Es el petróleo renovable?',          ['No, tardó millones de años en formarse', 'Sí', 'Depende del país'], 'No, tardó millones de años en formarse'),
            omp('¿Es el viento renovable?',            ['Sí', 'No', 'Solo en la costa'], 'Sí', '💨'),
            omp('¿Es el carbón renovable?',            ['No', 'Sí', 'A veces'], 'No'),
            omp('¿Cuál de estas es renovable?',        ['La energía solar', 'El gas natural', 'El petróleo'], 'La energía solar', '☀️'),
        ]),

        est('Cómo se genera', 'De la fuente al enchufe', '🔌', 'opcion_multiple', [
            omp('¿Qué aprovecha una represa hidroeléctrica?', ['La fuerza del agua en movimiento', 'El calor del sol', 'El viento'], 'La fuerza del agua en movimiento', '💧'),
            omp('¿Qué aprovecha un panel solar?',      ['La luz del sol', 'El viento', 'El agua'], 'La luz del sol'),
            omp('¿Qué mueve un aerogenerador?',        ['El viento', 'El agua', 'El carbón'], 'El viento'),
            omp('¿De dónde viene la mayor parte de la electricidad en Colombia?', ['De las hidroeléctricas', 'Del carbón', 'Del sol'], 'De las hidroeléctricas'),
            omp('¿Qué se necesita para transportar la electricidad?', ['Redes y cables', 'Tuberías de agua', 'Camiones'], 'Redes y cables'),
        ]),

        est('El costo de la energía', 'Todo tiene consecuencias', '⚖️', 'opcion_multiple', [
            omp('¿Qué produce quemar combustibles fósiles?', ['Gases que calientan el planeta', 'Oxígeno', 'Nada'], 'Gases que calientan el planeta'),
            omp('¿Tienen impacto también las renovables?',   ['Sí, aunque menor: ocupan terreno y recursos', 'No, ninguno', 'Solo la solar'], 'Sí, aunque menor: ocupan terreno y recursos'),
            omp('¿Cuál es la energía que menos contamina?',  ['La que no se llega a usar', 'La solar', 'La eólica'], 'La que no se llega a usar'),
            omp('¿Qué es la eficiencia energética?',         ['Conseguir lo mismo gastando menos energía', 'Usar más energía', 'Apagar todo siempre'], 'Conseguir lo mismo gastando menos energía'),
            omp('¿Qué aparato de la casa suele gastar más?', ['La nevera, que está siempre encendida', 'La bombilla LED', 'El reloj'], 'La nevera, que está siempre encendida'),
        ]),

        est('Desafío energético', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('¿De dónde viene, en el fondo, casi toda la energía de la Tierra?', ['Del Sol', 'Del centro de la Tierra', 'De las máquinas'], 'Del Sol'),
            reto('¿Se puede crear energía de la nada?',   ['No, solo se transforma', 'Sí', 'Solo en laboratorios'], 'No, solo se transforma'),
            reto('¿Qué pasa con la energía cuando se «gasta»?', ['Se transforma, casi siempre en calor', 'Desaparece', 'Se guarda sola'], 'Se transforma, casi siempre en calor'),
            reto('¿Qué bombillo consume menos?',          ['El LED', 'El incandescente', 'Da igual'], 'El LED'),
            reto('¿Qué puedo hacer yo para gastar menos energía?', ['Apagar lo que no uso y aprovechar la luz del día', 'Nada', 'Comprar más aparatos'], 'Apagar lo que no uso y aprovechar la luz del día'),
        ]),
    ],
],

[
    'slug'  => 'el-suelo-y-las-rocas',
    'title' => 'El suelo y las rocas',
    'description' => 'De qué está hecho el suelo, cómo se forma y por qué importa cuidarlo.',
    'objective' => 'Describir la composición del suelo y reconocer procesos de erosión y conservación.',
    'icon' => '⛰️', 'nivel' => 'primaria-media', 'bloque' => 'el-planeta-que-habitamos',
    'duracion' => 12, 'tags' => ['observacion', 'comprension', 'geografia'],
    'estaciones' => [

        est('¿De qué está hecho el suelo?', 'Más de lo que parece', '🪨', 'opcion_multiple', [
            omp('¿Qué hay en el suelo además de tierra?', ['Agua, aire, minerales y restos de seres vivos', 'Solo tierra', 'Solo piedras'], 'Agua, aire, minerales y restos de seres vivos'),
            omp('¿Qué es el humus?',                   ['Materia orgánica que hace fértil el suelo', 'Una roca', 'Un mineral puro'], 'Materia orgánica que hace fértil el suelo'),
            omp('¿Para qué sirven las lombrices?',     ['Airean el suelo y lo enriquecen', 'Se lo comen todo', 'Nada'], 'Airean el suelo y lo enriquecen', '🪱'),
            omp('¿Puede crecer una planta en cualquier suelo?', ['No, necesita nutrientes y agua', 'Sí', 'Solo en arena'], 'No, necesita nutrientes y agua'),
            omp('¿Cuánto tarda en formarse un centímetro de suelo fértil?', ['Cientos de años', 'Un mes', 'Un día'], 'Cientos de años'),
        ]),

        est('Tipos de roca', 'Tres formas de originarse', '🗿', 'opcion_multiple', [
            omp('¿Cómo se forman las rocas ígneas?',   ['Al enfriarse el magma', 'Por capas de sedimento', 'Por presión y calor'], 'Al enfriarse el magma', '🌋'),
            omp('¿Cómo se forman las sedimentarias?',  ['Por acumulación de capas de sedimentos', 'Por lava', 'Por rayos'], 'Por acumulación de capas de sedimentos'),
            omp('¿Cómo se forman las metamórficas?',   ['Por presión y calor sobre otras rocas', 'Por lluvia', 'Por el viento'], 'Por presión y calor sobre otras rocas'),
            omp('¿Dónde se encuentran fósiles casi siempre?', ['En rocas sedimentarias', 'En rocas ígneas', 'En el agua'], 'En rocas sedimentarias', '🦴'),
            omp('¿Es la arena una roca?',              ['Son fragmentos muy pequeños de roca', 'No tiene relación', 'Es un mineral puro'], 'Son fragmentos muy pequeños de roca'),
        ]),

        est('La erosión', 'El paisaje cambia despacio', '🌬️', 'opcion_multiple', [
            omp('¿Qué es la erosión?',                 ['El desgaste del suelo y las rocas', 'La siembra', 'Una lluvia fuerte'], 'El desgaste del suelo y las rocas'),
            omp('¿Qué provoca erosión?',               ['El agua, el viento y el hielo', 'Solo el sol', 'Nada natural'], 'El agua, el viento y el hielo'),
            omp('¿Qué pasa si se talan los árboles de una ladera?', ['El suelo se erosiona más rápido', 'Nada', 'Crece más pasto'], 'El suelo se erosiona más rápido'),
            omp('¿Qué ayuda a proteger el suelo?',     ['Las raíces de las plantas', 'El cemento', 'Los carros'], 'Las raíces de las plantas'),
            omp('¿Se puede recuperar rápido un suelo erosionado?', ['No, tarda muchísimo', 'Sí, en un mes', 'Sí, con lluvia'], 'No, tarda muchísimo'),
        ]),

        est('Sopa de la Tierra', 'Encuentra las palabras', '🔤', 'sopa_letras',
            sopa(['SUELO', 'ROCA', 'HUMUS', 'MAGMA', 'FOSIL'], 10)),
    ],
],

[
    'slug'  => 'los-sentidos-y-el-cerebro',
    'title' => 'Los sentidos y el cerebro',
    'description' => 'Cómo la información llega al cerebro y por qué a veces nos engaña.',
    'objective' => 'Relacionar los órganos de los sentidos con el procesamiento cerebral de la información.',
    'icon' => '👁️‍🗨️', 'nivel' => 'primaria-superior', 'bloque' => 'el-cuerpo-por-dentro',
    'duracion' => 13, 'tags' => ['cuerpo', 'observacion', 'comprension'],
    'estaciones' => [

        est('Del sentido al cerebro', 'El viaje de la señal', '⚡', 'opcion_multiple', [
            omp('¿Qué órgano interpreta lo que ven los ojos?', ['El cerebro', 'El ojo mismo', 'El corazón'], 'El cerebro'),
            omp('¿Cómo llega la señal desde el ojo al cerebro?', ['Por el nervio óptico', 'Por la sangre', 'Por el aire'], 'Por el nervio óptico'),
            omp('¿Vemos con los ojos o con el cerebro?',  ['Con los dos: el ojo capta, el cerebro interpreta', 'Solo con los ojos', 'Solo con el cerebro'], 'Con los dos: el ojo capta, el cerebro interpreta'),
            omp('¿Qué parte del ojo deja entrar la luz?', ['La pupila', 'La ceja', 'El párpado'], 'La pupila'),
            omp('¿Por qué la pupila cambia de tamaño?',   ['Para regular cuánta luz entra', 'Por el color', 'Por el sueño'], 'Para regular cuánta luz entra'),
        ]),

        est('Cuando el cerebro se equivoca', 'Ilusiones y percepción', '🌀', 'opcion_multiple', [
            omp('¿Qué es una ilusión óptica?',         ['Algo que vemos distinto de como es', 'Un dibujo bonito', 'Un error del ojo solamente'], 'Algo que vemos distinto de como es'),
            omp('¿Por qué existen las ilusiones ópticas?', ['Porque el cerebro completa e interpreta lo que ve', 'Porque los ojos fallan', 'Por magia'], 'Porque el cerebro completa e interpreta lo que ve'),
            omp('¿Puede el cerebro rellenar información que falta?', ['Sí, lo hace constantemente', 'No', 'Solo de noche'], 'Sí, lo hace constantemente'),
            omp('¿Sirven las ilusiones para algo en ciencia?', ['Sí, ayudan a entender cómo percibimos', 'No', 'Solo para juegos'], 'Sí, ayudan a entender cómo percibimos'),
            omp('Si dos personas ven lo mismo, ¿lo perciben igual?', ['No siempre, la experiencia influye', 'Sí, siempre', 'Nunca'], 'No siempre, la experiencia influye'),
        ]),

        est('Cuidar los sentidos', 'Prevenir daños', '🛡️', 'opcion_multiple', [
            omp('¿Qué daña el oído con el tiempo?',    ['El ruido fuerte y sostenido', 'El silencio', 'La música suave'], 'El ruido fuerte y sostenido'),
            omp('¿A qué volumen conviene usar audífonos?', ['A un nivel en que se oiga hablar alrededor', 'Al máximo', 'Da igual'], 'A un nivel en que se oiga hablar alrededor'),
            omp('¿Qué protege los ojos del sol?',      ['Gafas con filtro UV', 'Mirar de reojo', 'Nada'], 'Gafas con filtro UV', '🕶️'),
            omp('¿Qué es la regla 20-20-20 para las pantallas?', ['Cada 20 minutos, mirar lejos 20 segundos', 'Ver 20 videos', 'Descansar 20 horas'], 'Cada 20 minutos, mirar lejos 20 segundos'),
            omp('¿Puede recuperarse la audición perdida por ruido?', ['Normalmente no', 'Sí, siempre', 'En una semana'], 'Normalmente no'),
        ]),

        est('Desafío de la percepción', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Cuántos sentidos se estudian tradicionalmente?', ['Cinco', 'Tres', 'Diez'], 'Cinco'),
            reto('¿Qué sentido detecta la temperatura?',  ['El tacto', 'El gusto', 'El oído'], 'El tacto'),
            reto('¿Qué pasa si me tapo la nariz al comer?', ['Casi no distingo los sabores', 'Sabe igual', 'Sabe más'], 'Casi no distingo los sabores'),
            reto('¿Por qué el gusto y el olfato están relacionados?', ['El sabor depende mucho del aroma', 'Están en el mismo órgano', 'No lo están'], 'El sabor depende mucho del aroma'),
            reto('¿Qué protege al oído interno?',         ['El hueso del cráneo', 'La piel', 'El pelo'], 'El hueso del cráneo'),
        ]),
    ],
],


[
    'slug'  => 'los-animales-y-sus-crias',
    'title' => 'Los animales y sus crías',
    'description' => 'Cómo nacen, crecen y cambian los animales: huevos, metamorfosis y cuidados.',
    'objective' => 'Describir ciclos de vida animales y formas de reproducción y cuidado de las crías.',
    'icon' => '🐣', 'nivel' => 'primaria-inicial', 'bloque' => 'clasificar-la-vida',
    'duracion' => 11, 'tags' => ['observacion', 'comprension', 'clasificacion'],
    'estaciones' => [

        est('¿Cómo nacen?', 'Del huevo o del vientre', '🥚', 'opcion_multiple', [
            omp('¿Cómo nace un pollito?',        ['De un huevo', 'Del vientre', 'De una semilla'], 'De un huevo', '🐣'),
            omp('¿Cómo nace un perrito?',        ['Del vientre de su madre', 'De un huevo', 'De una flor'], 'Del vientre de su madre', '🐶'),
            omp('¿Cómo nace una tortuga?',       ['De un huevo', 'Del vientre', 'De una raíz'], 'De un huevo', '🐢'),
            omp('¿Cómo se llaman los animales que nacen de huevo?', ['Ovíparos', 'Vivíparos', 'Mamíferos'], 'Ovíparos'),
            omp('¿Y los que nacen del vientre?', ['Vivíparos', 'Ovíparos', 'Reptiles'], 'Vivíparos'),
        ]),

        est('La metamorfosis', 'Cambiar por completo', '🦋', 'ordenar_secuencia', [
            'title' => 'Ordena el ciclo de la mariposa',
            'items' => ['🥚 Huevo', '🐛 Oruga', '🪺 Crisálida', '🦋 Mariposa'],
        ]),

        est('Cuidar a las crías', 'Cada especie a su manera', '🐦', 'opcion_multiple', [
            omp('¿Qué hacen las aves con sus huevos?',   ['Los incuban hasta que nacen', 'Los abandonan siempre', 'Se los comen'], 'Los incuban hasta que nacen'),
            omp('¿Con qué alimentan los mamíferos a sus crías?', ['Con leche', 'Con hojas', 'Con agua'], 'Con leche'),
            omp('¿Necesitan todas las crías cuidados?',  ['No: algunas se valen solas al nacer', 'Sí, todas', 'Ninguna'], 'No: algunas se valen solas al nacer'),
            omp('¿Por qué hay animales que ponen muchísimos huevos?', ['Porque pocos llegan a adultos', 'Por gusto', 'Por error'], 'Porque pocos llegan a adultos'),
            omp('¿Qué es un ciclo de vida?',             ['Las etapas desde que nace hasta que se reproduce', 'Un día', 'Un tipo de animal'], 'Las etapas desde que nace hasta que se reproduce'),
        ]),

        est('Crías y adultos', 'Une cada uno', '🔗', 'emparejar', [
            ['e' => '🐣', 'w' => 'Pollito y gallina'],
            ['e' => '🐛', 'w' => 'Oruga y mariposa'],
            ['e' => '🐸', 'w' => 'Renacuajo y rana'],
            ['e' => '🐴', 'w' => 'Potro y caballo'],
            ['e' => '🐑', 'w' => 'Cordero y oveja'],
            ['e' => '🐕', 'w' => 'Cachorro y perro'],
        ]),
    ],
],

[
    'slug'  => 'el-metodo-cientifico',
    'title' => 'El método científico',
    'description' => 'Cómo se investiga de verdad: observar, preguntar, suponer, probar y concluir.',
    'objective' => 'Aplicar los pasos del método científico a una pregunta investigable.',
    'icon' => '🔭', 'nivel' => 'primaria-media', 'bloque' => 'materia-y-energia',
    'duracion' => 13, 'tags' => ['logica', 'observacion', 'deduccion'],
    'estaciones' => [

        est('Los pasos', 'No es adivinar', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos del método científico',
            'items' => ['Observar algo', 'Hacerse una pregunta', 'Proponer una hipótesis', 'Hacer el experimento', 'Sacar conclusiones'],
        ]),

        est('¿Qué es una hipótesis?', 'Una respuesta que se puede probar', '💡', 'opcion_multiple', [
            omp('¿Qué es una hipótesis?',              ['Una posible respuesta que se puede comprobar', 'Una certeza', 'Una opinión sin más'], 'Una posible respuesta que se puede comprobar'),
            omp('¿Puede una hipótesis resultar falsa?', ['Sí, y eso también enseña', 'No', 'Nunca'], 'Sí, y eso también enseña'),
            omp('«Las plantas crecen más con luz» es…', ['Una hipótesis comprobable', 'Una opinión', 'Una ley'], 'Una hipótesis comprobable'),
            omp('«El azul es el mejor color» es…',     ['Una opinión, no una hipótesis', 'Una hipótesis', 'Un experimento'], 'Una opinión, no una hipótesis'),
            omp('¿Qué hace un científico si su hipótesis falla?', ['Propone otra y vuelve a probar', 'Oculta el resultado', 'Abandona'], 'Propone otra y vuelve a probar'),
        ]),

        est('El experimento', 'Cambiar una sola cosa', '⚗️', 'opcion_multiple', [
            omp('Para probar si la luz afecta a una planta, ¿qué debo cambiar?', ['Solo la luz', 'La luz, el agua y la tierra', 'Nada'], 'Solo la luz'),
            omp('¿Por qué solo una cosa a la vez?',    ['Para saber cuál causó el cambio', 'Para ir más rápido', 'Por costumbre'], 'Para saber cuál causó el cambio'),
            omp('¿Qué es un grupo de control?',        ['El que se deja sin el cambio, para comparar', 'El que se estudia', 'El que sobra'], 'El que se deja sin el cambio, para comparar'),
            omp('¿Por qué se repite un experimento?',  ['Para comprobar que no fue casualidad', 'Por aburrimiento', 'No se repite'], 'Para comprobar que no fue casualidad'),
            omp('¿Qué se hace con los datos obtenidos?', ['Se registran y se analizan', 'Se ignoran', 'Se cambian si no gustan'], 'Se registran y se analizan'),
        ]),

        est('Desafío del investigador', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Qué instrumento se usa para ver lo muy pequeño?', ['El microscopio', 'El telescopio', 'La lupa de aumento cero'], 'El microscopio'),
            reto('¿Qué instrumento se usa para ver lo muy lejano?',  ['El telescopio', 'El microscopio', 'La balanza'], 'El telescopio'),
            reto('¿Por qué los científicos publican sus resultados?', ['Para que otros los comprueben', 'Para presumir', 'Por obligación'], 'Para que otros los comprueben'),
            reto('¿Puede la ciencia cambiar de opinión?',           ['Sí, si aparecen nuevas evidencias', 'No', 'Nunca'], 'Sí, si aparecen nuevas evidencias'),
            reto('¿Qué es más fuerte, una opinión o una evidencia?', ['La evidencia', 'La opinión', 'Igual'], 'La evidencia'),
        ]),
    ],
],


[
    'slug'  => 'crucigramas-de-ciencias',
    'title' => 'Crucigramas de ciencias',
    'description' => 'Cuatro crucigramas para recordar el vocabulario de toda la materia.',
    'objective' => 'Recuperar términos científicos a partir de su definición.',
    'icon' => '🔠', 'nivel' => 'primaria-superior', 'bloque' => 'materia-y-energia',
    'duracion' => 16, 'tags' => ['comprension', 'reto', 'logica'],
    'estaciones' => [

        est('Crucigrama de los seres vivos', 'Cada pista es una definición', '🌿', 'crucigrama',
            crucigrama([
                ['w' => 'CELULA',    'pista' => 'La unidad más pequeña de un ser vivo'],
                ['w' => 'NUCLEO',    'pista' => 'Parte de la célula que guarda la información'],
                ['w' => 'REINO',     'pista' => 'Cada uno de los cinco grandes grupos de seres vivos'],
                ['w' => 'HONGO',     'pista' => 'Ni planta ni animal: tiene su propio reino'],
                ['w' => 'ANFIBIO',   'pista' => 'Vive en el agua de pequeño y en tierra de adulto'],
                ['w' => 'INSECTO',   'pista' => 'Animal de seis patas y tres partes del cuerpo'],
            ])),

        est('Crucigrama del cuerpo humano', 'Sistemas y órganos', '🫀', 'crucigrama',
            crucigrama([
                ['w' => 'CORAZON',   'pista' => 'Órgano que bombea la sangre'],
                ['w' => 'PULMON',    'pista' => 'Ahí llega el aire que respiramos'],
                ['w' => 'ESOFAGO',   'pista' => 'Tubo que lleva la comida al estómago'],
                ['w' => 'CEREBRO',   'pista' => 'Dirige el cuerpo desde dentro del cráneo'],
                ['w' => 'HUESO',     'pista' => 'Pieza dura que forma el esqueleto'],
                ['w' => 'TENDON',    'pista' => 'Une el músculo con el hueso'],
            ])),

        est('Crucigrama de la energía', 'Fuerzas, luz y sonido', '⚡', 'crucigrama',
            crucigrama([
                ['w' => 'ENERGIA',   'pista' => 'No se crea ni se destruye, solo se transforma'],
                ['w' => 'FRICCION',  'pista' => 'Fuerza que frena una pelota que rueda'],
                ['w' => 'GRAVEDAD',  'pista' => 'Fuerza que atrae los objetos hacia la Tierra'],
                ['w' => 'SOMBRA',    'pista' => 'Se forma cuando un objeto opaco bloquea la luz'],
                ['w' => 'ESPEJO',    'pista' => 'Superficie donde la luz se refleja'],
                ['w' => 'PALANCA',   'pista' => 'Máquina simple: un balancín es una'],
            ])),

        est('Crucigrama del planeta', 'Ecosistemas y universo', '🌍', 'crucigrama',
            crucigrama([
                ['w' => 'ECOSISTEMA', 'pista' => 'Los seres vivos de un lugar y su entorno'],
                ['w' => 'CADENA',     'pista' => 'Así se llama la secuencia de quién come a quién'],
                ['w' => 'RECICLAR',   'pista' => 'Convertir un residuo en un material nuevo'],
                ['w' => 'PLANETA',    'pista' => 'La Tierra es uno'],
                ['w' => 'SATELITE',   'pista' => 'La Luna lo es de la Tierra'],
                ['w' => 'ESTRELLA',   'pista' => 'El Sol es una'],
            ])),
    ],
],

],

'reasignar' => [],

];
