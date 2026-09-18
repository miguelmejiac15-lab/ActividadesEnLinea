<?php
/**
 * artistica-primaria.php — Artística de 1.º a 6.º
 *
 * Escrito sobre las mallas de Arte (K1 a K6) y de Danza (K1, K2) de
 * `MallasPrimaria/`.
 *
 * La malla de Arte tiene una particularidad que aquí se respeta: recorre
 * la historia del arte EN ORDEN CRONOLÓGICO a lo largo de la primaria
 * —rupestre y precolombino en 3.º, Egipto y Asia en 4.º, grecorromano y
 * Renacimiento en 5.º, Barroco y anatomía en 6.º— y en cada etapa enseña
 * la técnica que ese periodo resolvió: el contorno con el arte rupestre,
 * la proporción con el canon griego, el volumen con el claroscuro
 * renacentista, la profundidad con la perspectiva barroca.
 *
 * Eso no es decoración: es la razón de que el orden funcione. Se aprende
 * a dibujar volumen cuando se estudia a quien lo inventó.
 *
 * Lo que NO se puede hacer aquí es lo obvio de una clase de arte —pintar,
 * modelar, recortar— porque es una pantalla. Así que estas actividades
 * entrenan la otra mitad, la que el papel no da: mirar una obra y saber
 * qué se está mirando.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'artistica',
    'name'       => 'Artística',
    'tagline'    => 'Mirar, entender y crear con línea, color, forma y movimiento',
    'icon'       => '🎨',
    'color'      => '#ec407a',
    'sort_order' => 3,
],

'bloques' => [
    ['slug' => 'elementos-del-arte', 'name' => 'Elementos del Arte', 'icon' => '✏️', 'sort_order' => 4,
     'description' => 'Línea, forma, textura, color y composición: el alfabeto con el que se hace cualquier imagen.'],
    ['slug' => 'historia-del-arte', 'name' => 'Historia del Arte', 'icon' => '🏛️', 'sort_order' => 5,
     'description' => 'De las cuevas al Barroco, y lo que cada época descubrió sobre cómo representar el mundo.'],
    ['slug' => 'tecnicas-de-dibujo', 'name' => 'Técnicas de Dibujo', 'icon' => '🖌️', 'sort_order' => 6,
     'description' => 'Proporción, claroscuro y perspectiva: engañar al ojo para que vea volumen y profundidad.'],
    ['slug' => 'danza-y-movimiento', 'name' => 'Danza y Movimiento', 'icon' => '💃', 'sort_order' => 7,
     'description' => 'El cuerpo como instrumento: ritmo, espacio y las danzas de nuestras regiones.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · ELEMENTOS DEL ARTE
// =====================================================================

[
    'slug'  => 'la-linea-y-la-forma',
    'title' => 'La línea y la forma',
    'description' => 'Rectas, curvas y quebradas, y las formas que aparecen cuando se cierran.',
    'objective' => 'Reconocer tipos de línea y las formas básicas que estructuran una imagen.',
    'icon' => '➰', 'nivel' => 'primaria-inicial', 'bloque' => 'elementos-del-arte',
    'duracion' => 11, 'tags' => ['observacion', 'juego'],
    'estaciones' => [

        est('Tipos de línea', 'Cada línea dice algo distinto', '📏', 'opcion_multiple', [
            omp('Una línea que no se dobla nunca es…',   ['Recta', 'Curva', 'Quebrada'], 'Recta'),
            omp('Una línea que se dobla suave es…',      ['Curva', 'Recta', 'Punteada'], 'Curva'),
            omp('Una línea con esquinas en zigzag es…',  ['Quebrada', 'Curva', 'Recta'], 'Quebrada'),
            omp('¿Qué línea transmite más calma?',       ['La curva suave', 'La quebrada en zigzag', 'Ninguna'], 'La curva suave'),
            omp('¿Qué línea transmite tensión o peligro?', ['La quebrada', 'La curva', 'La recta horizontal'], 'La quebrada'),
        ]),

        est('Formas en las cosas', 'Todo se puede reducir a formas', '🔷', 'opcion_multiple', [
            omp('¿Qué forma tiene una rueda?',    ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo', '🛞'),
            omp('¿Qué forma tiene un techo a dos aguas?', ['Triángulo', 'Círculo', 'Óvalo'], 'Triángulo', '🏠'),
            omp('¿Qué forma tiene una ventana común?', ['Cuadrado', 'Círculo', 'Estrella'], 'Cuadrado', '🪟'),
            omp('¿Qué forma tiene un huevo?',     ['Óvalo', 'Cuadrado', 'Triángulo'], 'Óvalo', '🥚'),
            omp('¿Por qué sirve ver las formas en los objetos?', ['Ayuda a dibujarlos', 'No sirve', 'Para contarlos'], 'Ayuda a dibujarlos'),
        ]),

        est('Textura', 'Cómo se siente al mirar', '🪵', 'opcion_multiple', [
            omp('¿Qué es la textura visual?',        ['La sensación de superficie que se ve en una imagen', 'El color', 'El tamaño'], 'La sensación de superficie que se ve en una imagen'),
            omp('¿Cómo es la textura de una piedra?', ['Rugosa', 'Suave', 'Líquida'], 'Rugosa', '🪨'),
            omp('¿Cómo es la textura del algodón?',  ['Suave', 'Áspera', 'Punzante'], 'Suave', '☁️'),
            omp('¿Cómo es la textura de un cactus?', ['Espinosa', 'Suave', 'Lisa'], 'Espinosa', '🌵'),
            omp('¿Cómo se dibuja una textura rugosa?', ['Con muchos puntos y trazos cortos', 'Con una línea recta', 'Sin dibujar nada'], 'Con muchos puntos y trazos cortos'),
        ]),

        est('Memoria de formas', 'Encuentra las parejas', '🧠', 'memoria',
            ['🔵', '🔺', '⬜', '💠', '⭐', '❤️']),
    ],
],

[
    'slug'  => 'el-color',
    'title' => 'El color',
    'description' => 'Primarios, secundarios, cálidos y fríos, y qué siente uno al verlos.',
    'objective' => 'Reconocer el círculo cromático y clasificar colores por mezcla y por temperatura.',
    'icon' => '🌈', 'nivel' => 'primaria-media', 'bloque' => 'elementos-del-arte',
    'duracion' => 12, 'tags' => ['observacion', 'clasificacion', 'juego'],
    'estaciones' => [

        est('Colores primarios', 'Los tres que no se pueden mezclar', '🔴', 'opcion_multiple', [
            omp('¿Cuáles son los colores primarios?',  ['Rojo, azul y amarillo', 'Verde, naranja y morado', 'Blanco y negro'], 'Rojo, azul y amarillo'),
            omp('¿Por qué se llaman primarios?',       ['Porque no se obtienen mezclando otros', 'Porque son bonitos', 'Porque van primero'], 'Porque no se obtienen mezclando otros'),
            omp('¿Es el verde un color primario?',     ['No, es secundario', 'Sí', 'Depende'], 'No, es secundario'),
            omp('¿Cuántos colores primarios hay?',     ['Tres', 'Cinco', 'Siete'], 'Tres'),
            omp('¿Qué es el círculo cromático?',       ['Una rueda que ordena los colores', 'Un pincel', 'Un tipo de papel'], 'Una rueda que ordena los colores'),
        ]),

        est('Mezclar colores', '¿Qué sale de cada mezcla?', '🎨', 'opcion_multiple', [
            omp('Azul + amarillo = …',   ['Verde', 'Morado', 'Naranja'], 'Verde', ['a' => '#1e88e5', 'b' => '#fdd835'], 'dosColores'),
            omp('Rojo + amarillo = …',   ['Naranja', 'Verde', 'Morado'], 'Naranja', ['a' => '#e53935', 'b' => '#fdd835'], 'dosColores'),
            omp('Rojo + azul = …',       ['Morado', 'Verde', 'Naranja'], 'Morado', ['a' => '#e53935', 'b' => '#1e88e5'], 'dosColores'),
            omp('Blanco + rojo = …',     ['Rosado', 'Café', 'Verde'], 'Rosado', ['a' => '#ffffff', 'b' => '#e53935'], 'dosColores'),
            omp('Negro + blanco = …',    ['Gris', 'Café', 'Azul'], 'Gris', ['a' => '#212121', 'b' => '#ffffff'], 'dosColores'),
        ]),

        est('Cálidos y fríos', 'Los colores tienen temperatura', '🌡️', 'opcion_multiple', [
            omp('¿Es el rojo cálido o frío?',       ['Cálido', 'Frío', 'Ninguno'], 'Cálido', '#e53935', 'color'),
            omp('¿Es el azul cálido o frío?',       ['Frío', 'Cálido', 'Ninguno'], 'Frío', '#1e88e5', 'color'),
            omp('¿Qué colores usarías para un fuego?', ['Rojos y naranjas', 'Azules y verdes', 'Grises'], 'Rojos y naranjas', '🔥'),
            omp('¿Qué colores usarías para el mar?',  ['Azules y verdes', 'Rojos y amarillos', 'Cafés'], 'Azules y verdes', '🌊'),
            omp('¿Qué sensación dan los colores fríos?', ['Calma y distancia', 'Energía y cercanía', 'Nada'], 'Calma y distancia'),
        ]),

        est('El color y la emoción', 'Lo que transmite cada uno', '💭', 'opcion_multiple', [
            omp('¿Qué color se asocia con la calma?',    ['Azul', 'Rojo', 'Naranja'], 'Azul', '#1e88e5', 'color'),
            omp('¿Qué color se asocia con la energía?',  ['Rojo', 'Gris', 'Azul claro'], 'Rojo', '#e53935', 'color'),
            omp('¿Qué color se asocia con la naturaleza?', ['Verde', 'Morado', 'Negro'], 'Verde', '#43a047', 'color'),
            omp('¿Qué color se asocia con la alegría?',  ['Amarillo', 'Gris', 'Café'], 'Amarillo', '#fdd835', 'color'),
            omp('¿Significan lo mismo los colores en todas las culturas?', ['No, cambian según la cultura', 'Sí, siempre', 'Solo el rojo'], 'No, cambian según la cultura'),
        ]),
    ],
],

[
    'slug'  => 'composicion-y-simetria',
    'title' => 'Composición y simetría',
    'description' => 'Dónde poner cada cosa: equilibrio, ritmo, patrones y espacio negativo.',
    'objective' => 'Aplicar principios de composición como equilibrio, ritmo, repetición y simetría.',
    'icon' => '⚖️', 'nivel' => 'primaria-media', 'bloque' => 'elementos-del-arte',
    'duracion' => 12, 'tags' => ['observacion', 'patrones', 'logica'],
    'estaciones' => [

        est('Componer', 'Ordenar lo que se ve', '🖼️', 'opcion_multiple', [
            omp('¿Qué es la composición en arte?',    ['Cómo se organizan los elementos en el espacio', 'El color usado', 'El tamaño del papel'], 'Cómo se organizan los elementos en el espacio'),
            omp('¿Qué es el punto focal?',            ['El lugar donde el ojo se detiene primero', 'La esquina', 'El marco'], 'El lugar donde el ojo se detiene primero'),
            omp('¿Qué es el equilibrio visual?',      ['Que el peso visual se reparta bien', 'Que todo sea del mismo tamaño', 'Que haya pocos colores'], 'Que el peso visual se reparta bien'),
            omp('Si pongo todo en una esquina, la imagen se ve…', ['Desequilibrada', 'Equilibrada', 'Simétrica'], 'Desequilibrada'),
            omp('¿Qué es el espacio negativo?',       ['El espacio vacío alrededor de la figura', 'El fondo oscuro', 'Un error'], 'El espacio vacío alrededor de la figura'),
        ]),

        est('Simetría', 'Como en un espejo', '🪞', 'opcion_multiple', [
            omp('¿Qué es la simetría axial?',        ['Los dos lados son iguales respecto a un eje', 'Un color repetido', 'Un tipo de línea'], 'Los dos lados son iguales respecto a un eje'),
            omp('¿Qué es la simetría radial?',       ['Los elementos giran alrededor de un centro', 'Solo dos lados iguales', 'Ninguna repetición'], 'Los elementos giran alrededor de un centro'),
            omp('Un mandala tiene simetría…',        ['Radial', 'Axial solamente', 'Ninguna'], 'Radial', '🔯'),
            omp('Una mariposa tiene simetría…',      ['Axial', 'Radial', 'Ninguna'], 'Axial', '🦋'),
            omp('¿Qué sensación da la simetría?',    ['Orden y calma', 'Caos', 'Movimiento rápido'], 'Orden y calma'),
        ]),

        est('Patrón y ritmo', 'Repetir con intención', '🔁', 'opcion_multiple', [
            omp('¿Qué es un patrón visual?',            ['Una forma que se repite con una regla', 'Un color', 'Un error'], 'Una forma que se repite con una regla'),
            omp('¿Qué es el ritmo visual?',             ['La repetición que guía la mirada', 'El sonido de la obra', 'El tamaño'], 'La repetición que guía la mirada'),
            omp('¿Dónde se ven patrones en la vida diaria?', ['En telas, baldosas y rejas', 'En ninguna parte', 'Solo en museos'], 'En telas, baldosas y rejas'),
            omp('Las grecas de las culturas indígenas son…', ['Patrones decorativos con significado', 'Errores', 'Firmas'], 'Patrones decorativos con significado'),
            omp('Si rompo el patrón en un punto, ese punto…', ['Llama la atención', 'Se esconde', 'Desaparece'], 'Llama la atención'),
        ]),

        est('Continúa el patrón', '¿Qué sigue?', '➡️', 'opcion_multiple', [
            omp('🔴 🔵 🔴 🔵 … ¿qué sigue?',       ['🔴', '🔵', '🟢'], '🔴'),
            omp('🔺 🔺 ⬜ 🔺 🔺 ⬜ … ¿qué sigue?', ['🔺', '⬜', '🔵'], '🔺'),
            omp('💠 ⭐ 💠 ⭐ … ¿qué sigue?',       ['💠', '⭐', '🔺'], '💠'),
            omp('🟨 🟨 🟦 🟨 🟨 🟦 … ¿qué sigue?', ['🟨', '🟦', '🟥'], '🟨'),
            omp('⬛ ⬜ ⬜ ⬛ ⬜ ⬜ … ¿qué sigue?',  ['⬛', '⬜', '🟫'], '⬛'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · HISTORIA DEL ARTE
// =====================================================================

[
    'slug'  => 'arte-rupestre',
    'title' => 'Arte rupestre',
    'description' => 'Las primeras imágenes de la humanidad: por qué se pintaba en las cuevas.',
    'objective' => 'Reconocer las características del arte paleolítico y su función comunicativa.',
    'icon' => '🦬', 'nivel' => 'primaria-media', 'bloque' => 'historia-del-arte',
    'duracion' => 12, 'tags' => ['historia', 'observacion', 'cultura'],
    'estaciones' => [

        est('Las primeras pinturas', 'Arte de hace 30.000 años', '🕯️', 'opcion_multiple', [
            omp('¿Dónde se pintaba el arte rupestre?',   ['En las paredes de las cuevas', 'En papel', 'En telas'], 'En las paredes de las cuevas'),
            omp('¿Qué se pintaba sobre todo?',           ['Animales y escenas de caza', 'Retratos', 'Paisajes urbanos'], 'Animales y escenas de caza'),
            omp('¿Con qué colores se pintaba?',          ['Tonos tierra: ocre, rojo y negro', 'Verdes y azules', 'Fluorescentes'], 'Tonos tierra: ocre, rojo y negro'),
            omp('¿De dónde salían esos colores?',        ['De minerales, tierra y carbón', 'De tubos de pintura', 'De flores'], 'De minerales, tierra y carbón'),
            omp('¿Para qué se cree que pintaban?',       ['Para contar, recordar y por rituales', 'Para vender', 'Para decorar la sala'], 'Para contar, recordar y por rituales'),
        ]),

        est('Contar sin palabras', 'La imagen como lenguaje', '💬', 'opcion_multiple', [
            omp('¿Se puede contar una historia sin escribir?', ['Sí, con imágenes', 'No', 'Solo con música'], 'Sí, con imágenes'),
            omp('¿Qué es un símbolo?',                   ['Una imagen que representa una idea', 'Un color', 'Una firma'], 'Una imagen que representa una idea'),
            omp('¿Cómo se representaba el movimiento?',  ['Dibujando patas repetidas o en distinta posición', 'Con flechas', 'No se representaba'], 'Dibujando patas repetidas o en distinta posición'),
            omp('¿Qué técnica usaban para hacer manos?', ['Soplaban pigmento alrededor de la mano', 'Las calcaban', 'Las tallaban'], 'Soplaban pigmento alrededor de la mano', '🖐️'),
            omp('¿Siguen siendo importantes esas imágenes hoy?', ['Sí, son la memoria más antigua que tenemos', 'No', 'Solo para los guías'], 'Sí, son la memoria más antigua que tenemos'),
        ]),

        est('Arte precolombino', 'Símbolo y ornamento en América', '🗿', 'opcion_multiple', [
            omp('¿Qué material trabajaban mucho los muiscas?', ['El oro', 'El plástico', 'El vidrio'], 'El oro', '🥇'),
            omp('¿Qué son las grecas?',                  ['Bordes decorativos con formas geométricas', 'Retratos', 'Firmas'], 'Bordes decorativos con formas geométricas'),
            omp('¿Qué representaban muchas figuras precolombinas?', ['Dioses y animales sagrados', 'Paisajes de ciudad', 'Escenas de escuela'], 'Dioses y animales sagrados'),
            omp('¿En qué se hacía la cerámica?',         ['En arcilla modelada y cocida', 'En metal fundido', 'En papel'], 'En arcilla modelada y cocida', '🏺'),
            omp('¿Por qué hay que conservar estas obras?', ['Son parte de nuestra identidad y no se pueden rehacer', 'Porque son caras', 'No hace falta'], 'Son parte de nuestra identidad y no se pueden rehacer'),
        ]),

        est('Sopa del arte antiguo', 'Encuentra las palabras', '🔤', 'sopa_letras',
            sopa(['CUEVA', 'OCRE', 'BISONTE', 'ORO', 'GRECA'], 10)),
    ],
],

[
    'slug'  => 'egipto-y-las-civilizaciones',
    'title' => 'Egipto y las civilizaciones',
    'description' => 'Frontalidad, jerarquía y símbolo en el arte de Egipto, Mesopotamia y Asia.',
    'objective' => 'Identificar convenciones del arte antiguo y su relación con la organización social.',
    'icon' => '𓂀', 'nivel' => 'primaria-media', 'bloque' => 'historia-del-arte',
    'duracion' => 13, 'tags' => ['historia', 'cultura', 'observacion'],
    'estaciones' => [

        est('La ley de la frontalidad', 'Por qué los egipcios se dibujaban así', '👁️', 'opcion_multiple', [
            omp('¿Cómo dibujaban los egipcios la cara?',   ['De perfil', 'De frente', 'De espaldas'], 'De perfil'),
            omp('¿Y el ojo?',                              ['De frente, aunque la cara vaya de perfil', 'De perfil', 'Cerrado'], 'De frente, aunque la cara vaya de perfil'),
            omp('¿Por qué mezclaban las vistas?',          ['Para mostrar cada parte en su forma más reconocible', 'Por error', 'Por moda'], 'Para mostrar cada parte en su forma más reconocible'),
            omp('¿Buscaban que se pareciera a la realidad?', ['No, buscaban que se entendiera', 'Sí, era una foto', 'Nunca dibujaban personas'], 'No, buscaban que se entendiera'),
            omp('¿Qué eran los jeroglíficos?',             ['Su forma de escritura con signos', 'Un tipo de pintura', 'Un edificio'], 'Su forma de escritura con signos'),
        ]),

        est('El tamaño manda', 'Jerarquía visual', '📏', 'opcion_multiple', [
            omp('¿Por qué el faraón se dibujaba más grande?', ['Para mostrar que era el más importante', 'Porque era alto', 'Por error'], 'Para mostrar que era el más importante'),
            omp('¿Cómo se llama esa regla?',           ['Jerarquía visual', 'Perspectiva', 'Simetría'], 'Jerarquía visual'),
            omp('¿Qué nos dice el arte sobre esa sociedad?', ['Que estaba muy organizada por rangos', 'Nada', 'Que eran pintores'], 'Que estaba muy organizada por rangos'),
            omp('¿Aparecía la gente común en el arte egipcio?', ['Sí, pero más pequeña', 'Nunca', 'Solo ellos'], 'Sí, pero más pequeña'),
            omp('¿Usamos hoy la jerarquía visual?',    ['Sí, en carteles y publicidad', 'No', 'Solo en museos'], 'Sí, en carteles y publicidad'),
        ]),

        est('Arte de Asia', 'India, China y Japón', '🏯', 'opcion_multiple', [
            omp('¿Qué es un mandala?',                 ['Un diseño circular con simetría radial', 'Un retrato', 'Un paisaje'], 'Un diseño circular con simetría radial'),
            omp('¿Qué es la caligrafía?',              ['El arte de escribir con trazo expresivo', 'Un tipo de pintura al óleo', 'Una escultura'], 'El arte de escribir con trazo expresivo', '🖌️'),
            omp('¿Qué animal mítico aparece mucho en el arte chino?', ['El dragón', 'El unicornio', 'El grifo'], 'El dragón', '🐉'),
            omp('¿Qué flor es un símbolo importante en el arte asiático?', ['El loto', 'El girasol', 'El tulipán'], 'El loto', '🪷'),
            omp('¿Cómo son los paisajes del arte chino tradicional?', ['Fluidos, con montañas y niebla', 'Geométricos', 'Muy oscuros'], 'Fluidos, con montañas y niebla', '⛰️'),
        ]),

        est('Desafío del arte antiguo', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Qué buscaba el arte egipcio?',      ['Ser claro y duradero', 'Ser realista', 'Ser abstracto'], 'Ser claro y duradero'),
            reto('¿Qué material usaban para las pirámides?', ['Piedra', 'Madera', 'Vidrio'], 'Piedra'),
            reto('¿Qué es un amuleto?',                ['Un objeto pequeño con valor protector', 'Una pintura grande', 'Un edificio'], 'Un objeto pequeño con valor protector'),
            reto('¿Qué tienen en común estas culturas antiguas?', ['Usaban el arte para su religión y su poder', 'Pintaban paisajes', 'No dibujaban personas'], 'Usaban el arte para su religión y su poder'),
            reto('¿Por qué conocemos hoy su arte?',    ['Porque se conservó en tumbas y templos', 'Porque lo escribieron', 'Por casualidad'], 'Porque se conservó en tumbas y templos'),
        ]),
    ],
],

[
    'slug'  => 'del-renacimiento-al-barroco',
    'title' => 'Del Renacimiento al Barroco',
    'description' => 'Cuando el arte aprendió a mirar: proporción, luz, volumen y drama.',
    'objective' => 'Reconocer los aportes del Renacimiento y el Barroco a la representación visual.',
    'icon' => '🕯️', 'nivel' => 'primaria-superior', 'bloque' => 'historia-del-arte',
    'duracion' => 14, 'tags' => ['historia', 'observacion', 'cultura'],
    'estaciones' => [

        est('El Renacimiento', 'Observar la realidad', '🔍', 'opcion_multiple', [
            omp('¿Qué caracterizó al Renacimiento?',     ['Observar la naturaleza y usar la matemática', 'Copiar la Edad Media', 'Pintar sin mirar'], 'Observar la naturaleza y usar la matemática'),
            omp('¿Qué es el claroscuro?',                ['El contraste entre luz y sombra para dar volumen', 'Un color', 'Un tipo de marco'], 'El contraste entre luz y sombra para dar volumen'),
            omp('¿Qué es el sfumato?',                   ['Difuminar los bordes para suavizar la forma', 'Pintar con líneas duras', 'Usar solo negro'], 'Difuminar los bordes para suavizar la forma'),
            omp('¿Qué inventó el Renacimiento para dar profundidad?', ['La perspectiva lineal', 'El color', 'La firma'], 'La perspectiva lineal'),
            omp('¿Por qué se estudiaba anatomía?',       ['Para dibujar el cuerpo humano con exactitud', 'Por curiosidad médica solamente', 'No se estudiaba'], 'Para dibujar el cuerpo humano con exactitud'),
        ]),

        est('El Barroco', 'La luz como protagonista', '🌓', 'opcion_multiple', [
            omp('¿Qué es el tenebrismo?',                ['Un contraste muy fuerte entre luz y oscuridad', 'Pintar de noche', 'Usar poco color'], 'Un contraste muy fuerte entre luz y oscuridad'),
            omp('¿Qué buscaba el arte barroco?',         ['Emocionar y sorprender', 'Ser sencillo y plano', 'Ser geométrico'], 'Emocionar y sorprender'),
            omp('¿Cómo son las composiciones barrocas?', ['Dinámicas, con diagonales y movimiento', 'Estáticas y simétricas', 'Vacías'], 'Dinámicas, con diagonales y movimiento'),
            omp('¿Qué efecto produce iluminar solo una parte?', ['Dirige la mirada hacia ahí', 'Oscurece todo', 'Nada'], 'Dirige la mirada hacia ahí'),
            omp('¿Qué es el escorzo?',                   ['Dibujar algo acortado por la perspectiva', 'Un color oscuro', 'Un tipo de pincel'], 'Dibujar algo acortado por la perspectiva'),
        ]),

        est('El gótico y la luz', 'Vitrales y catedrales', '⛪', 'opcion_multiple', [
            omp('¿Qué son los vitrales?',                ['Vidrios de colores que filtran la luz', 'Pinturas al óleo', 'Esculturas'], 'Vidrios de colores que filtran la luz'),
            omp('¿Qué buscaban las catedrales góticas?', ['Altura y mucha luz de color', 'Ser bajas y oscuras', 'Ser pequeñas'], 'Altura y mucha luz de color'),
            omp('¿Qué simbolizaba la luz en el arte gótico?', ['Lo divino', 'El poder militar', 'La riqueza'], 'Lo divino'),
            omp('¿Para qué servían las imágenes en las iglesias?', ['Para contar historias a quien no sabía leer', 'Para decorar sin más', 'Para vender'], 'Para contar historias a quien no sabía leer'),
            omp('¿Qué contraste usaba el gótico?',       ['Colores intensos contra piedra oscura', 'Blanco sobre blanco', 'Solo grises'], 'Colores intensos contra piedra oscura'),
        ]),

        est('Ordena la historia del arte', 'De lo más antiguo a lo más reciente', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena estos periodos en el tiempo',
            'items' => ['Arte rupestre', 'Arte egipcio', 'Arte grecorromano', 'Gótico', 'Renacimiento', 'Barroco'],
        ]),
    ],
],


// =====================================================================
//  BLOQUE · TÉCNICAS DE DIBUJO
// =====================================================================

[
    'slug'  => 'proporcion-y-escala',
    'title' => 'Proporción y escala',
    'description' => 'El canon del cuerpo humano y cómo ampliar una imagen sin deformarla.',
    'objective' => 'Aplicar nociones de proporción y usar la cuadrícula para cambiar de escala.',
    'icon' => '📐', 'nivel' => 'primaria-superior', 'bloque' => 'tecnicas-de-dibujo',
    'duracion' => 13, 'tags' => ['observacion', 'calculo', 'logica'],
    'estaciones' => [

        est('¿Qué es la proporción?', 'La relación entre las partes', '⚖️', 'opcion_multiple', [
            omp('¿Qué es la proporción en un dibujo?',    ['La relación de tamaño entre las partes', 'El color usado', 'El tipo de papel'], 'La relación de tamaño entre las partes'),
            omp('Si la cabeza sale enorme y el cuerpo diminuto, hay un problema de…', ['Proporción', 'Color', 'Textura'], 'Proporción'),
            omp('¿Qué es el canon en el dibujo de la figura humana?', ['Una medida de referencia, como «ocho cabezas»', 'Un tipo de lápiz', 'Un color'], 'Una medida de referencia, como «ocho cabezas»'),
            omp('¿Cuántas cabezas mide aproximadamente un adulto según el canon clásico?', ['Ocho', 'Tres', 'Veinte'], 'Ocho'),
            omp('¿Un niño tiene la misma proporción que un adulto?', ['No, la cabeza es proporcionalmente más grande', 'Sí, igual', 'Es más pequeña'], 'No, la cabeza es proporcionalmente más grande'),
        ]),

        est('La cuadrícula', 'Ampliar sin deformar', '🔲', 'opcion_multiple', [
            omp('¿Para qué sirve dibujar con cuadrícula?', ['Para copiar o ampliar manteniendo las proporciones', 'Para colorear', 'Para firmar'], 'Para copiar o ampliar manteniendo las proporciones'),
            omp('Si duplico el tamaño de cada casilla, la imagen…', ['Se duplica sin deformarse', 'Se deforma', 'Se reduce'], 'Se duplica sin deformarse'),
            omp('¿Qué pasa si hago las casillas de distinto tamaño?', ['La imagen se deforma', 'Queda igual', 'Queda más bonita'], 'La imagen se deforma'),
            omp('¿Qué es la escala?',                    ['La relación entre el tamaño del dibujo y el real', 'El color', 'La textura'], 'La relación entre el tamaño del dibujo y el real'),
            omp('Un mapa a escala 1:100 significa que…', ['1 cm del mapa son 100 cm reales', 'El mapa mide 100 cm', 'Hay 100 mapas'], '1 cm del mapa son 100 cm reales'),
        ]),

        est('Encajar la figura', 'Formas simples primero', '🔷', 'opcion_multiple', [
            omp('¿Qué significa «encajar» un dibujo?',   ['Empezar con formas geométricas simples', 'Terminar los detalles', 'Colorear'], 'Empezar con formas geométricas simples'),
            omp('¿Con qué forma se empieza una cabeza?', ['Un óvalo o un círculo', 'Un cuadrado exacto', 'Una estrella'], 'Un óvalo o un círculo'),
            omp('¿Cuándo se hacen los detalles?',        ['Al final, cuando la estructura está bien', 'Al principio', 'Nunca'], 'Al final, cuando la estructura está bien'),
            omp('¿Por qué se dibuja primero suave?',     ['Para poder corregir sin dañar el papel', 'Porque es más rápido', 'Por costumbre'], 'Para poder corregir sin dañar el papel'),
            omp('¿Qué es el dibujo gestual?',            ['Un boceto rápido que captura el movimiento', 'Un dibujo muy detallado', 'Una firma'], 'Un boceto rápido que captura el movimiento'),
        ]),

        est('¿Está bien proporcionado?', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es correcto lo que dice?', 'Responde rápido', [
                ['e' => '📐', 'n' => 'La cuadrícula ayuda a ampliar bien', 'ok' => true],
                ['e' => '🧍', 'n' => 'Un adulto mide unas 8 cabezas',     'ok' => true],
                ['e' => '👶', 'n' => 'Un bebé tiene la cabeza pequeña',   'ok' => false],
                ['e' => '✏️', 'n' => 'Conviene encajar antes de detallar', 'ok' => true],
                ['e' => '🖍️', 'n' => 'Los detalles van primero',          'ok' => false],
                ['e' => '🗺️', 'n' => 'La escala relaciona dibujo y realidad', 'ok' => true],
            ])),
    ],
],

[
    'slug'  => 'luz-sombra-y-perspectiva',
    'title' => 'Luz, sombra y perspectiva',
    'description' => 'Cómo se finge el volumen en una hoja plana y cómo se dibuja la profundidad.',
    'objective' => 'Aplicar la escala tonal y la perspectiva lineal para representar volumen y profundidad.',
    'icon' => '🌗', 'nivel' => 'primaria-superior', 'bloque' => 'tecnicas-de-dibujo',
    'duracion' => 14, 'tags' => ['observacion', 'logica'],
    'estaciones' => [

        est('La escala tonal', 'Del blanco al negro', '⬛', 'opcion_multiple', [
            omp('¿Qué es una escala tonal?',           ['Una gradación del claro al oscuro', 'Una lista de colores', 'Un tipo de papel'], 'Una gradación del claro al oscuro'),
            omp('¿Para qué sirve?',                    ['Para dar la ilusión de volumen', 'Para colorear rápido', 'Para firmar'], 'Para dar la ilusión de volumen'),
            omp('¿Dónde va la zona más clara de un objeto?', ['Donde le da la luz', 'Donde está la sombra', 'En el borde'], 'Donde le da la luz'),
            omp('¿Cómo se llama la sombra que el objeto proyecta en el suelo?', ['Sombra arrojada', 'Sombra propia', 'Reflejo'], 'Sombra arrojada'),
            omp('Si no hay sombras, el dibujo se ve…',  ['Plano', 'Con volumen', 'Más real'], 'Plano'),
        ]),

        est('La perspectiva', 'Lo lejano se ve más pequeño', '🛤️', 'opcion_multiple', [
            omp('¿Qué es el punto de fuga?',           ['El punto donde parecen juntarse las líneas paralelas', 'El centro del papel', 'La firma'], 'El punto donde parecen juntarse las líneas paralelas'),
            omp('¿Qué pasa con lo que está más lejos?', ['Se ve más pequeño', 'Se ve más grande', 'Se ve igual'], 'Se ve más pequeño'),
            omp('¿Qué es la línea del horizonte?',     ['La línea a la altura de nuestros ojos', 'El borde del papel', 'El suelo'], 'La línea a la altura de nuestros ojos'),
            omp('¿Cuántos puntos de fuga puede tener un dibujo?', ['Uno, dos o más', 'Solo uno', 'Ninguno'], 'Uno, dos o más'),
            omp('Los rieles de un tren parecen juntarse a lo lejos por…', ['La perspectiva', 'Un error de construcción', 'El viento'], 'La perspectiva', '🛤️'),
        ]),

        est('Perspectiva atmosférica', 'La distancia también cambia el color', '🏔️', 'opcion_multiple', [
            omp('¿Cómo se ven las montañas lejanas?',   ['Más pálidas y azuladas', 'Más intensas', 'Más oscuras'], 'Más pálidas y azuladas'),
            omp('¿Por qué pasa eso?',                   ['El aire y la distancia difuminan el color', 'Están pintadas mal', 'Por la lluvia'], 'El aire y la distancia difuminan el color'),
            omp('Para que algo se vea lejano, conviene…', ['Bajar el contraste y enfriar el color', 'Subir el contraste', 'Usar rojo intenso'], 'Bajar el contraste y enfriar el color'),
            omp('¿Qué se ve más nítido?',               ['Lo que está cerca', 'Lo que está lejos', 'Todo igual'], 'Lo que está cerca'),
            omp('¿Cómo se llama esta técnica?',         ['Perspectiva atmosférica', 'Perspectiva lineal', 'Claroscuro'], 'Perspectiva atmosférica'),
        ]),

        est('Desafío del dibujante', 'Cinco preguntas de técnica', '🏆', 'desafio_final', [
            reto('¿Qué da la sensación de volumen en un dibujo plano?', ['La luz y la sombra', 'El tamaño del papel', 'La firma'], 'La luz y la sombra'),
            reto('¿Qué es una trama cruzada?',          ['Líneas que se cruzan para oscurecer una zona', 'Un color', 'Un marco'], 'Líneas que se cruzan para oscurecer una zona'),
            reto('¿Qué es un bodegón?',                 ['Una composición de objetos inmóviles', 'Un retrato', 'Un paisaje'], 'Una composición de objetos inmóviles'),
            reto('¿Qué se dibuja primero, la estructura o el detalle?', ['La estructura', 'El detalle', 'Da igual'], 'La estructura'),
            reto('¿Por qué se entrecierran los ojos al observar un modelo?', ['Para ver mejor las masas de luz y sombra', 'Para descansar', 'Por costumbre'], 'Para ver mejor las masas de luz y sombra'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · DANZA Y MOVIMIENTO
// =====================================================================

[
    'slug'  => 'ritmo-y-cuerpo',
    'title' => 'Ritmo y cuerpo',
    'description' => 'Pulso, ritmo y las formas de moverse en el espacio.',
    'objective' => 'Reconocer el pulso y el ritmo y explorar las posibilidades de movimiento del cuerpo.',
    'icon' => '🥁', 'nivel' => 'primaria-inicial', 'bloque' => 'danza-y-movimiento',
    'duracion' => 11, 'tags' => ['observacion', 'patrones', 'cuerpo'],
    'estaciones' => [

        est('Pulso y ritmo', 'No son lo mismo', '🎵', 'opcion_multiple', [
            omp('¿Qué es el pulso en música?',        ['El latido constante que se repite igual', 'La melodía', 'La letra'], 'El latido constante que se repite igual'),
            omp('¿Qué es el ritmo?',                  ['La combinación de sonidos largos y cortos', 'El volumen', 'El instrumento'], 'La combinación de sonidos largos y cortos'),
            omp('Al aplaudir con una canción sigo el…', ['Pulso', 'Color', 'Volumen'], 'Pulso', '👏'),
            omp('¿Qué es el tempo?',                  ['La velocidad de la música', 'El instrumento', 'La letra'], 'La velocidad de la música'),
            omp('Una música rápida invita a…',        ['Moverse con energía', 'Quedarse quieto', 'Dormir'], 'Moverse con energía'),
        ]),

        est('Cómo se mueve el cuerpo', 'Las posibilidades del movimiento', '🤸', 'opcion_multiple', [
            omp('¿Qué es un movimiento locomotor?',   ['El que traslada el cuerpo de un lugar a otro', 'El que se hace en el sitio', 'Un salto solamente'], 'El que traslada el cuerpo de un lugar a otro'),
            omp('¿Cuál es un movimiento locomotor?',  ['Caminar', 'Girar la cabeza', 'Aplaudir'], 'Caminar'),
            omp('¿Cuál se hace sin desplazarse?',     ['Girar en el sitio', 'Correr', 'Saltar hacia adelante'], 'Girar en el sitio'),
            omp('¿Qué son los niveles en la danza?',  ['Alto, medio y bajo', 'Rápido y lento', 'Fuerte y suave'], 'Alto, medio y bajo'),
            omp('¿Para qué sirve calentar antes de bailar?', ['Para evitar lesiones', 'Para cansarse', 'Para nada'], 'Para evitar lesiones'),
        ]),

        est('Sigue la secuencia', 'Ordena los pasos de una coreografía', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena una secuencia de movimiento',
            'items' => ['Calentar', 'Aprender los pasos', 'Practicar con música', 'Ensayar en grupo', 'Presentar'],
        ]),

        est('Memoria del ritmo', 'Encuentra las parejas', '🧠', 'memoria',
            ['🥁', '🎸', '🎹', '🎺', '🎻', '🪘']),
    ],
],

[
    'slug'  => 'danzas-de-colombia',
    'title' => 'Danzas de Colombia',
    'description' => 'Cumbia, joropo, bambuco y currulao: cada región baila lo suyo.',
    'objective' => 'Identificar danzas tradicionales colombianas y relacionarlas con su región.',
    'icon' => '💃', 'nivel' => 'primaria-media', 'bloque' => 'danza-y-movimiento',
    'duracion' => 12, 'tags' => ['cultura', 'colombia', 'observacion'],
    'estaciones' => [

        est('Cada danza, su región', 'Une el baile con su tierra', '🗺️', 'opcion_multiple', [
            omp('¿De qué región es la cumbia?',       ['Caribe', 'Andina', 'Orinoquía'], 'Caribe', '🥁'),
            omp('¿De qué región es el joropo?',       ['Orinoquía', 'Pacífica', 'Caribe'], 'Orinoquía', '🪕'),
            omp('¿De qué región es el bambuco?',      ['Andina', 'Caribe', 'Amazonía'], 'Andina', '🎸'),
            omp('¿De qué región es el currulao?',     ['Pacífica', 'Andina', 'Orinoquía'], 'Pacífica', '🪘'),
            omp('¿Qué instrumento acompaña al currulao?', ['La marimba', 'El acordeón', 'El violín'], 'La marimba'),
        ]),

        est('Instrumentos', 'Con qué se hace la música', '🎼', 'emparejar', [
            ['e' => '🪗', 'w' => 'Acordeón'],
            ['e' => '🥁', 'w' => 'Tambor'],
            ['e' => '🪘', 'w' => 'Marimba'],
            ['e' => '🎸', 'w' => 'Tiple'],
            ['e' => '🪕', 'w' => 'Arpa'],
            ['e' => '🎺', 'w' => 'Trompeta'],
        ]),

        est('Fiestas y carnavales', 'Cuando un pueblo baila junto', '🎭', 'opcion_multiple', [
            omp('¿Dónde se celebra el Carnaval más famoso de Colombia?', ['Barranquilla', 'Bogotá', 'Medellín'], 'Barranquilla', '🎭'),
            omp('¿Qué es una comparsa?',              ['Un grupo que desfila bailando con un mismo tema', 'Un instrumento', 'Un disfraz solo'], 'Un grupo que desfila bailando con un mismo tema'),
            omp('¿Para qué sirve una fiesta tradicional?', ['Para reunir a la comunidad y mantener su cultura', 'Solo para descansar', 'Para nada'], 'Para reunir a la comunidad y mantener su cultura'),
            omp('¿Se puede bailar sin saber pasos exactos?', ['Sí, moverse con el ritmo ya es bailar', 'No', 'Solo los expertos'], 'Sí, moverse con el ritmo ya es bailar'),
            omp('¿De dónde vienen las raíces de nuestras danzas?', ['De la mezcla indígena, africana y europea', 'Solo de Europa', 'De un solo pueblo'], 'De la mezcla indígena, africana y europea'),
        ]),

        est('Desafío del bailarín', 'Cinco preguntas de danza', '🏆', 'desafio_final', [
            reto('¿Qué se hace siempre antes de bailar?',   ['Calentar', 'Comer mucho', 'Correr una hora'], 'Calentar'),
            reto('¿Qué es la coreografía?',                 ['La secuencia organizada de movimientos', 'La música', 'El vestuario'], 'La secuencia organizada de movimientos'),
            reto('¿Para qué sirve el vestuario en una danza?', ['Acompaña y comunica la historia', 'Solo para verse bien', 'Para nada'], 'Acompaña y comunica la historia'),
            reto('¿Qué se necesita para bailar en grupo?',  ['Coordinación y escuchar a los demás', 'Ser el mejor', 'Ir por libre'], 'Coordinación y escuchar a los demás'),
            reto('¿Es la danza solo entretenimiento?',      ['No, también es identidad y expresión', 'Sí', 'Solo en carnaval'], 'No, también es identidad y expresión'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'sonido-y-musica',
    'title' => 'Sonido y música',
    'description' => 'Altura, duración e intensidad: los elementos con que se construye la música.',
    'objective' => 'Reconocer las cualidades del sonido y las familias de instrumentos.',
    'icon' => '🎼', 'nivel' => 'primaria-media', 'bloque' => 'danza-y-movimiento',
    'duracion' => 12, 'tags' => ['observacion', 'clasificacion', 'patrones'],
    'estaciones' => [

        est('Las cualidades del sonido', 'Cuatro formas de describirlo', '🔊', 'opcion_multiple', [
            omp('¿Qué es la altura de un sonido?',    ['Si es agudo o grave', 'Si es fuerte o suave', 'Si dura mucho'], 'Si es agudo o grave'),
            omp('¿Qué es la intensidad?',             ['Si es fuerte o suave', 'Si es agudo o grave', 'Su duración'], 'Si es fuerte o suave'),
            omp('¿Qué es la duración?',               ['Cuánto tiempo suena', 'Cuánto se oye', 'Su color'], 'Cuánto tiempo suena'),
            omp('¿Qué es el timbre?',                 ['Lo que distingue a un instrumento de otro', 'El volumen', 'La velocidad'], 'Lo que distingue a un instrumento de otro'),
            omp('Un ratón hace un sonido ___ y un león uno ___', ['agudo / grave', 'grave / agudo', 'largo / corto'], 'agudo / grave'),
        ]),

        est('Familias de instrumentos', 'Según cómo producen el sonido', '🎻', 'opcion_multiple', [
            omp('¿A qué familia pertenece el violín?',  ['Cuerda', 'Viento', 'Percusión'], 'Cuerda', '🎻'),
            omp('¿A qué familia pertenece la flauta?',  ['Viento', 'Cuerda', 'Percusión'], 'Viento', '🪈'),
            omp('¿A qué familia pertenece el tambor?',  ['Percusión', 'Cuerda', 'Viento'], 'Percusión', '🥁'),
            omp('¿Cómo suena un instrumento de cuerda?', ['Vibra una cuerda', 'Vibra el aire dentro', 'Se golpea'], 'Vibra una cuerda'),
            omp('¿Y uno de percusión?',                ['Se golpea o se sacude', 'Se sopla', 'Se frota una cuerda'], 'Se golpea o se sacude'),
        ]),

        est('Ritmo y silencio', 'La música también se hace con pausas', '⏸️', 'opcion_multiple', [
            omp('¿Sirve el silencio en la música?',    ['Sí, forma parte del ritmo', 'No', 'Solo al final'], 'Sí, forma parte del ritmo'),
            omp('¿Qué es el compás?',                  ['La forma de agrupar los pulsos', 'La letra', 'El instrumento'], 'La forma de agrupar los pulsos'),
            omp('¿Qué es una melodía?',                ['Una sucesión de sonidos que se reconoce', 'El ritmo solo', 'El volumen'], 'Una sucesión de sonidos que se reconoce'),
            omp('¿Qué es la armonía?',                 ['Varios sonidos a la vez que suenan bien juntos', 'Un solo sonido', 'El silencio'], 'Varios sonidos a la vez que suenan bien juntos'),
            omp('¿Se puede hacer música solo con el cuerpo?', ['Sí: palmas, chasquidos, voz', 'No', 'Solo cantando'], 'Sí: palmas, chasquidos, voz'),
        ]),

        est('Instrumentos y su familia', 'Une cada uno', '🔗', 'emparejar', [
            ['e' => '🎸', 'w' => 'Guitarra: cuerda'],
            ['e' => '🎺', 'w' => 'Trompeta: viento'],
            ['e' => '🥁', 'w' => 'Tambor: percusión'],
            ['e' => '🎹', 'w' => 'Piano: teclado'],
            ['e' => '🎻', 'w' => 'Violín: cuerda frotada'],
            ['e' => '🪗', 'w' => 'Acordeón: aire'],
        ]),
    ],
],

[
    'slug'  => 'escultura-y-volumen',
    'title' => 'Escultura y volumen',
    'description' => 'Arte que se puede rodear: modelar, tallar y construir en tres dimensiones.',
    'objective' => 'Distinguir técnicas escultóricas y reconocer el volumen como lenguaje artístico.',
    'icon' => '🗿', 'nivel' => 'primaria-media', 'bloque' => 'elementos-del-arte',
    'duracion' => 12, 'tags' => ['observacion', 'comprension', 'cultura'],
    'estaciones' => [

        est('Dos dimensiones y tres', 'La diferencia', '📦', 'opcion_multiple', [
            omp('¿Cuántas dimensiones tiene un dibujo en papel?', ['Dos', 'Tres', 'Una'], 'Dos'),
            omp('¿Y una escultura?',                   ['Tres', 'Dos', 'Cuatro'], 'Tres'),
            omp('¿Qué se puede hacer con una escultura que no con un cuadro?', ['Rodearla y verla por todos lados', 'Colgarla', 'Pintarla'], 'Rodearla y verla por todos lados'),
            omp('¿Qué es el volumen en arte?',         ['El espacio que ocupa una forma', 'El sonido', 'El color'], 'El espacio que ocupa una forma'),
            omp('¿Es una escultura siempre de piedra?', ['No, puede ser de muchos materiales', 'Sí', 'Solo de mármol'], 'No, puede ser de muchos materiales'),
        ]),

        est('Técnicas', 'Quitar, añadir o unir', '🔨', 'opcion_multiple', [
            omp('¿Qué es tallar?',                     ['Quitar material hasta llegar a la forma', 'Añadir material', 'Pegar piezas'], 'Quitar material hasta llegar a la forma'),
            omp('¿Qué es modelar?',                    ['Dar forma añadiendo y moviendo material blando', 'Quitar piedra', 'Soldar'], 'Dar forma añadiendo y moviendo material blando'),
            omp('¿Qué material se modela con las manos?', ['La arcilla', 'El mármol', 'El acero'], 'La arcilla', '🏺'),
            omp('¿Qué es el ensamblaje?',              ['Unir piezas distintas para formar una obra', 'Tallar', 'Pintar'], 'Unir piezas distintas para formar una obra'),
            omp('¿Se puede hacer escultura con material reciclado?', ['Sí, y es muy común hoy', 'No', 'Solo en el colegio'], 'Sí, y es muy común hoy'),
        ]),

        est('Escultura y cultura', 'Obras que representan a un pueblo', '🏛️', 'opcion_multiple', [
            omp('¿Qué son las estatuas de San Agustín?', ['Esculturas prehispánicas en piedra', 'Edificios modernos', 'Pinturas'], 'Esculturas prehispánicas en piedra'),
            omp('¿Qué material usaban mucho los orfebres precolombinos?', ['El oro', 'El plástico', 'El vidrio'], 'El oro'),
            omp('¿Para qué se hacían muchas esculturas antiguas?', ['Por motivos religiosos o rituales', 'Para decorar casas', 'Para vender'], 'Por motivos religiosos o rituales'),
            omp('¿Qué es un monumento?',               ['Una obra que recuerda a alguien o algo importante', 'Un edificio cualquiera', 'Un cuadro'], 'Una obra que recuerda a alguien o algo importante'),
            omp('¿Por qué se conservan las esculturas antiguas?', ['Son testimonio de cómo vivían y creían', 'Porque son caras', 'Por costumbre'], 'Son testimonio de cómo vivían y creían'),
        ]),

        est('Objetos y su forma', 'Reconoce el volumen', '🔗', 'emparejar', [
            ['e' => '🏺', 'w' => 'Vasija de arcilla'],
            ['e' => '🗿', 'w' => 'Estatua de piedra'],
            ['e' => '🧱', 'w' => 'Construcción'],
            ['e' => '🪵', 'w' => 'Talla en madera'],
            ['e' => '🥇', 'w' => 'Pieza de metal'],
            ['e' => '🎭', 'w' => 'Máscara'],
        ]),
    ],
],

[
    'slug'  => 'arte-y-emocion',
    'title' => 'Arte y emoción',
    'description' => 'Por qué una obra nos hace sentir algo, y por qué no todos sentimos lo mismo.',
    'objective' => 'Interpretar obras de arte relacionando recursos visuales con emociones.',
    'icon' => '💗', 'nivel' => 'primaria-superior', 'bloque' => 'historia-del-arte',
    'duracion' => 12, 'tags' => ['emociones', 'observacion', 'comprension'],
    'estaciones' => [

        est('Lo que transmite una obra', 'El arte comunica', '🖼️', 'opcion_multiple', [
            omp('¿Puede una obra de arte transmitir tristeza?', ['Sí', 'No', 'Solo con texto'], 'Sí'),
            omp('¿Qué colores suelen dar sensación de calma?', ['Azules y verdes suaves', 'Rojos intensos', 'Negro puro'], 'Azules y verdes suaves'),
            omp('¿Qué línea transmite más agitación?',  ['La quebrada y diagonal', 'La horizontal', 'La curva suave'], 'La quebrada y diagonal'),
            omp('¿Qué efecto tiene una obra muy oscura?', ['Suele sentirse dramática o tensa', 'Alegre', 'Ninguno'], 'Suele sentirse dramática o tensa'),
            omp('¿Sienten todos lo mismo ante una obra?', ['No, depende de la experiencia de cada uno', 'Sí', 'Solo los artistas'], 'No, depende de la experiencia de cada uno'),
        ]),

        est('Para qué hacemos arte', 'Muchas razones', '🎨', 'opcion_multiple', [
            omp('¿Para qué se hace arte?',             ['Para expresar, contar, recordar y también protestar', 'Solo para decorar', 'Para vender'], 'Para expresar, contar, recordar y también protestar'),
            omp('¿Tiene que ser bonito para ser arte?', ['No, puede incomodar y aun así ser arte', 'Sí', 'Siempre'], 'No, puede incomodar y aun así ser arte'),
            omp('¿Puede el arte contar algo de su época?', ['Sí, es un documento de su tiempo', 'No', 'Solo el antiguo'], 'Sí, es un documento de su tiempo'),
            omp('¿Hace falta saber dibujar para hacer arte?', ['No, hay muchas formas de crear', 'Sí', 'Solo para pintar'], 'No, hay muchas formas de crear'),
            omp('¿Está bien que una obra me guste y a otro no?', ['Sí, es normal', 'No', 'Uno se equivoca'], 'Sí, es normal'),
        ]),

        est('Mirar una obra', 'Cómo se observa con calma', '👁️', 'opcion_multiple', [
            omp('¿Qué es lo primero al mirar una obra?', ['Ver qué hay, antes de opinar', 'Decir si me gusta', 'Buscar el precio'], 'Ver qué hay, antes de opinar'),
            omp('¿Qué es el punto focal de una obra?',  ['Donde el ojo se detiene primero', 'La esquina', 'El marco'], 'Donde el ojo se detiene primero'),
            omp('¿Ayuda saber cuándo se hizo una obra?', ['Sí, explica muchas decisiones del artista', 'No', 'Solo la fecha'], 'Sí, explica muchas decisiones del artista'),
            omp('Si no entiendo una obra, ¿qué hago?',  ['Miro más, pregunto y leo sobre ella', 'La descarto', 'Digo que es mala'], 'Miro más, pregunto y leo sobre ella'),
            omp('¿Hay una única interpretación correcta?', ['No, pero unas están mejor fundadas que otras', 'Sí', 'Todas valen igual'], 'No, pero unas están mejor fundadas que otras'),
        ]),

        est('Desafío del espectador', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Qué es un autorretrato?',           ['Un retrato que el artista hace de sí mismo', 'Un paisaje', 'Una copia'], 'Un retrato que el artista hace de sí mismo'),
            reto('¿Qué es un mural?',                  ['Una obra pintada sobre un muro', 'Un cuadro pequeño', 'Una escultura'], 'Una obra pintada sobre un muro'),
            reto('¿Qué es el arte abstracto?',         ['El que no representa cosas reconocibles', 'El muy realista', 'El antiguo'], 'El que no representa cosas reconocibles'),
            reto('¿Puede el arte cambiar cómo pensamos?', ['Sí, muchas veces lo ha hecho', 'No', 'Solo el moderno'], 'Sí, muchas veces lo ha hecho'),
            reto('¿Qué hace un museo?',                ['Conserva, estudia y muestra obras', 'Solo las vende', 'Las esconde'], 'Conserva, estudia y muestra obras'),
        ]),
    ],
],


[
    'slug'  => 'colores-y-mezclas',
    'title' => 'Colores y mezclas',
    'description' => 'Practicar el círculo cromático: complementarios, gamas y qué sale de cada mezcla.',
    'objective' => 'Aplicar el círculo cromático para predecir mezclas y reconocer complementarios.',
    'icon' => '🖌️', 'nivel' => 'primaria-superior', 'bloque' => 'elementos-del-arte',
    'duracion' => 12, 'tags' => ['observacion', 'logica', 'juego'],
    'estaciones' => [

        est('Secundarios y terciarios', 'Lo que sale de mezclar', '🎨', 'opcion_multiple', [
            omp('¿Cuáles son los tres colores secundarios?', ['Verde, naranja y morado', 'Rojo, azul y amarillo', 'Blanco, negro y gris'], 'Verde, naranja y morado'),
            omp('¿Cómo se obtiene un color terciario?',  ['Mezclando un primario con un secundario vecino', 'Mezclando dos primarios', 'Con blanco'], 'Mezclando un primario con un secundario vecino'),
            omp('Rojo + naranja da…',                    ['Rojo anaranjado', 'Verde', 'Morado'], 'Rojo anaranjado', ['a' => '#e53935', 'b' => '#fb8c00'], 'dosColores'),
            omp('Azul + verde da…',                      ['Azul verdoso', 'Naranja', 'Rojo'], 'Azul verdoso', ['a' => '#1e88e5', 'b' => '#43a047'], 'dosColores'),
            omp('¿Cuántos colores hay en un círculo cromático básico?', ['Doce', 'Tres', 'Cien'], 'Doce'),
        ]),

        est('Complementarios', 'Los opuestos se realzan', '🔄', 'opcion_multiple', [
            omp('¿Qué son los colores complementarios?', ['Los que están opuestos en el círculo cromático', 'Los que se parecen', 'Los primarios'], 'Los que están opuestos en el círculo cromático'),
            omp('¿Cuál es el complementario del rojo?',  ['El verde', 'El naranja', 'El morado'], 'El verde'),
            omp('¿Cuál es el complementario del azul?',  ['El naranja', 'El verde', 'El morado'], 'El naranja'),
            omp('¿Cuál es el complementario del amarillo?', ['El morado', 'El verde', 'El rojo'], 'El morado'),
            omp('¿Qué pasa si pongo dos complementarios juntos?', ['Se ven más intensos', 'Se apagan', 'Desaparecen'], 'Se ven más intensos'),
        ]),

        est('Claro y oscuro', 'Aclarar y oscurecer un color', '⬜', 'opcion_multiple', [
            omp('¿Cómo se aclara un color?',        ['Añadiendo blanco', 'Añadiendo negro', 'Añadiendo agua siempre'], 'Añadiendo blanco'),
            omp('¿Cómo se oscurece?',               ['Añadiendo negro', 'Añadiendo blanco', 'Con más color'], 'Añadiendo negro'),
            omp('¿Cómo se llama un color con blanco añadido?', ['Un tinte', 'Una sombra', 'Un primario'], 'Un tinte'),
            omp('¿Qué es una gama o escala de un color?', ['Sus variaciones del más claro al más oscuro', 'Su complementario', 'Su nombre'], 'Sus variaciones del más claro al más oscuro'),
            omp('¿Qué pasa si mezclo demasiados colores?', ['Sale un gris o café sucio', 'Sale blanco', 'Sale más brillante'], 'Sale un gris o café sucio'),
        ]),

        est('Desafío del color', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Es el negro un color primario?',  ['No', 'Sí', 'A veces'], 'No'),
            reto('¿Qué colores usarías para un atardecer?', ['Naranjas, rojos y morados', 'Azules fríos', 'Grises'], 'Naranjas, rojos y morados'),
            reto('¿Qué transmite una obra en tonos fríos?', ['Calma o distancia', 'Energía', 'Nada'], 'Calma o distancia'),
            reto('¿Se puede hacer arte solo en blanco y negro?', ['Sí, y es muy expresivo', 'No', 'Solo fotos'], 'Sí, y es muy expresivo'),
            reto('¿Por qué el círculo cromático es circular?', ['Para mostrar cómo se relacionan los colores entre sí', 'Por decoración', 'Por casualidad'], 'Para mostrar cómo se relacionan los colores entre sí'),
        ]),
    ],
],

[
    'slug'  => 'el-teatro-y-la-expresion',
    'title' => 'El teatro y la expresión',
    'description' => 'El cuerpo y la voz como herramientas: gesto, personaje y trabajo en escena.',
    'objective' => 'Reconocer los recursos expresivos del teatro y el trabajo colectivo de una puesta en escena.',
    'icon' => '🎬', 'nivel' => 'primaria-media', 'bloque' => 'danza-y-movimiento',
    'duracion' => 12, 'tags' => ['emociones', 'convivencia', 'observacion'],
    'estaciones' => [

        est('El cuerpo habla', 'Comunicación sin palabras', '🤸', 'opcion_multiple', [
            omp('¿Qué es el lenguaje corporal?',    ['Lo que comunicamos con gestos y postura', 'Hablar fuerte', 'Cantar'], 'Lo que comunicamos con gestos y postura'),
            omp('Si alguien cruza los brazos y mira al piso, probablemente…', ['Está incómodo o cerrado', 'Está feliz', 'Está bailando'], 'Está incómodo o cerrado'),
            omp('¿Puede un actor transmitir tristeza sin hablar?', ['Sí, con el cuerpo y el rostro', 'No', 'Solo con música'], 'Sí, con el cuerpo y el rostro'),
            omp('¿Qué es la mímica?',               ['Actuar solo con gestos, sin palabras', 'Hablar bajito', 'Cantar'], 'Actuar solo con gestos, sin palabras'),
            omp('¿Para qué sirve la expresión facial en escena?', ['Para que se entienda la emoción desde lejos', 'Para hacer reír', 'Para nada'], 'Para que se entienda la emoción desde lejos'),
        ]),

        est('Hacer un personaje', 'Ponerse en otra piel', '🎭', 'opcion_multiple', [
            omp('¿Qué es caracterizar un personaje?', ['Darle voz, gestos y forma de moverse propios', 'Ponerle nombre', 'Vestirlo'], 'Darle voz, gestos y forma de moverse propios'),
            omp('¿Hace falta parecerse al personaje?', ['No, se construye actuando', 'Sí', 'Solo en el físico'], 'No, se construye actuando'),
            omp('¿Puede un mismo texto interpretarse de varias formas?', ['Sí, y por eso el teatro es vivo', 'No', 'Solo una forma es correcta'], 'Sí, y por eso el teatro es vivo'),
            omp('¿Qué es la improvisación?',        ['Crear en el momento, sin guion previo', 'Olvidar el texto', 'Un error'], 'Crear en el momento, sin guion previo'),
            omp('¿Qué se necesita para improvisar bien en grupo?', ['Escuchar y aceptar lo que propone el otro', 'Hablar más', 'Ir por libre'], 'Escuchar y aceptar lo que propone el otro'),
        ]),

        est('Detrás del escenario', 'Una obra la hacen muchos', '🎪', 'opcion_multiple', [
            omp('¿Quién dirige una obra de teatro?',  ['El director', 'El público', 'El actor principal'], 'El director'),
            omp('¿Qué hace el escenógrafo?',          ['Diseña el espacio donde ocurre la obra', 'Actúa', 'Vende entradas'], 'Diseña el espacio donde ocurre la obra'),
            omp('¿Para qué sirve la iluminación?',    ['Dirige la mirada y crea ambiente', 'Solo para ver', 'Para nada'], 'Dirige la mirada y crea ambiente'),
            omp('¿Qué es el vestuario?',              ['La ropa que ayuda a construir el personaje', 'Un adorno', 'El escenario'], 'La ropa que ayuda a construir el personaje'),
            omp('¿Es el público parte de la obra?',   ['Sí, el teatro ocurre entre escena y público', 'No', 'Solo aplaude'], 'Sí, el teatro ocurre entre escena y público'),
        ]),

        est('Roles del teatro', 'Une cada uno con su trabajo', '🔗', 'emparejar', [
            ['e' => '🎭', 'w' => 'Actor'],
            ['e' => '🎬', 'w' => 'Director'],
            ['e' => '✍️', 'w' => 'Dramaturgo'],
            ['e' => '💡', 'w' => 'Iluminador'],
            ['e' => '👗', 'w' => 'Vestuarista'],
            ['e' => '🎨', 'w' => 'Escenógrafo'],
        ]),
    ],
],

],

'reasignar' => [],

];
