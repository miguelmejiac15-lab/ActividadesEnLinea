<?php
/**
 * lengua-primaria.php — Lengua y Literatura de 1.º a 6.º
 *
 * Escrito sobre las mallas de Lengua y Literatura (K1 a K6) de
 * `MallasPrimaria/`.
 *
 * «Aventura de las Letras» ya tenía 44 actividades, pero casi todas eran
 * de lectoescritura inicial: una letra, sus palabras, sus sílabas. Es la
 * mitad de la materia y la que corresponde a los seis años. Lo que faltaba
 * es lo que la malla pide de 3.º en adelante y no existía en el catálogo:
 * gramática, ortografía, tipos de texto y géneros literarios.
 *
 * Los cinco bloques nuevos empiezan donde termina el abecedario. Por eso
 * ninguno repite letras ni sílabas: un niño de cuarto que entra buscando
 * «adjetivos» y encuentra la vocal A cierra la página.
 *
 * Los ejemplos son en español de Colombia, con la tradición oral del
 * Caribe que la malla de K5 pide por su nombre.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'letras',
    'name'       => 'Aventura de las Letras',
    'tagline'    => 'Lectoescritura',
    'icon'       => '✏️',
    'color'      => '#29b6f6',
    'sort_order' => 1,
],

'bloques' => [
    ['slug' => 'gramatica', 'name' => 'Gramática', 'icon' => '📖', 'sort_order' => 4,
     'description' => 'Sustantivo, artículo, adjetivo, pronombre y verbo: las piezas con las que se arma una oración.'],
    ['slug' => 'ortografia-y-acentuacion', 'name' => 'Ortografía y Acentuación', 'icon' => '✍️', 'sort_order' => 5,
     'description' => 'Tildes, mayúsculas, signos de puntuación y las reglas que más se equivocan.'],
    ['slug' => 'vocabulario-y-significado', 'name' => 'Vocabulario y Significado', 'icon' => '🔤', 'sort_order' => 6,
     'description' => 'Sinónimos, antónimos, prefijos, sufijos y palabras que se agrupan por su sentido.'],
    ['slug' => 'tipos-de-texto', 'name' => 'Tipos de Texto', 'icon' => '📰', 'sort_order' => 7,
     'description' => 'Narrar, informar, instruir y convencer: cada intención pide un texto distinto.'],
    ['slug' => 'generos-literarios', 'name' => 'Géneros Literarios', 'icon' => '🎭', 'sort_order' => 8,
     'description' => 'Cuento, fábula, poesía, teatro y la tradición oral que se cuenta sin libro.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · GRAMÁTICA
// =====================================================================

[
    'slug'  => 'el-sustantivo-y-el-articulo',
    'title' => 'El sustantivo y el artículo',
    'description' => 'Las palabras que nombran cosas y las palabritas que van delante.',
    'objective' => 'Identificar sustantivos comunes y propios y concordar el artículo en género y número.',
    'icon' => '🏷️', 'nivel' => 'primaria-media', 'bloque' => 'gramatica',
    'duracion' => 13, 'tags' => ['escritura', 'vocabulario', 'lectura'],
    'estaciones' => [

        est('¿Qué es un sustantivo?', 'Las palabras que nombran', '📛', 'opcion_multiple', [
            omp('¿Qué nombra un sustantivo?',        ['Personas, animales, cosas o lugares', 'Acciones', 'Cualidades'], 'Personas, animales, cosas o lugares'),
            omp('¿Cuál de estas es un sustantivo?',  ['Perro', 'Correr', 'Rápido'], 'Perro', '🐕'),
            omp('¿Cuál de estas es un sustantivo?',  ['Montaña', 'Saltar', 'Bonito'], 'Montaña', '🏔️'),
            omp('¿Cuál NO es un sustantivo?',        ['Comer', 'Mesa', 'Ciudad'], 'Comer'),
            omp('«Barranquilla» es un sustantivo…',  ['Propio', 'Común', 'Colectivo'], 'Propio'),
        ]),

        est('Común o propio', 'Con mayúscula o sin ella', '🔠', 'opcion_multiple', [
            omp('¿Cómo se escribe un sustantivo propio?', ['Con mayúscula inicial', 'Con minúscula', 'Todo en mayúscula'], 'Con mayúscula inicial'),
            omp('«río» es un sustantivo…',                ['Común', 'Propio', 'Adjetivo'], 'Común'),
            omp('«Magdalena» es un sustantivo…',          ['Propio', 'Común', 'Verbo'], 'Propio'),
            omp('¿Cuál va con mayúscula?',                ['Colombia', 'país', 'ciudad'], 'Colombia'),
            omp('«rebaño» nombra un conjunto. Es un sustantivo…', ['Colectivo', 'Propio', 'Individual'], 'Colectivo', '🐑'),
        ]),

        est('Género y número', 'El artículo tiene que concordar', '⚖️', 'opcion_multiple', [
            omp('¿Qué artículo va con «casa»?',      ['La', 'El', 'Los'], 'La'),
            omp('¿Qué artículo va con «árbol»?',     ['El', 'La', 'Las'], 'El'),
            omp('¿Qué artículo va con «flores»?',    ['Las', 'La', 'El'], 'Las'),
            omp('¿Qué artículo va con «cuadernos»?', ['Los', 'El', 'Las'], 'Los'),
            omp('¿Cuál está mal escrito?',           ['El casa', 'La casa', 'Las casas'], 'El casa'),
        ]),

        est('Singular y plural', 'Escribe la palabra en plural', '⌨️', 'teclado',
            ['LIBROS', 'ÁRBOLES', 'FLORES', 'LÁPICES', 'CIUDADES']),
    ],
],

[
    'slug'  => 'el-adjetivo',
    'title' => 'El adjetivo',
    'description' => 'Las palabras que dicen cómo es algo, y cómo se comparan entre sí.',
    'objective' => 'Reconocer adjetivos calificativos y usarlos concordando con el sustantivo.',
    'icon' => '🎨', 'nivel' => 'primaria-media', 'bloque' => 'gramatica',
    'duracion' => 12, 'tags' => ['escritura', 'vocabulario'],
    'estaciones' => [

        est('¿Cómo es?', 'El adjetivo describe', '🔍', 'opcion_multiple', [
            omp('¿Qué hace un adjetivo?',            ['Dice cómo es algo', 'Nombra cosas', 'Indica acción'], 'Dice cómo es algo'),
            omp('En «el perro grande», ¿cuál es el adjetivo?', ['Grande', 'Perro', 'El'], 'Grande'),
            omp('En «la casa azul», ¿cuál es el adjetivo?',    ['Azul', 'Casa', 'La'], 'Azul'),
            omp('¿Cuál de estas es un adjetivo?',    ['Rápido', 'Correr', 'Carro'], 'Rápido'),
            omp('¿Cuál NO es un adjetivo?',          ['Ventana', 'Alta', 'Vieja'], 'Ventana'),
        ]),

        est('Concordancia', 'El adjetivo se ajusta al sustantivo', '🔗', 'opcion_multiple', [
            omp('¿Cómo se dice bien?',    ['Las flores bonitas', 'Las flores bonito', 'Las flor bonitas'], 'Las flores bonitas'),
            omp('¿Cómo se dice bien?',    ['El libro nuevo', 'El libro nueva', 'La libro nuevo'], 'El libro nuevo'),
            omp('¿Cómo se dice bien?',    ['Los perros negros', 'Los perros negra', 'Las perros negros'], 'Los perros negros'),
            omp('¿Cómo se dice bien?',    ['Una casa grande', 'Un casa grande', 'Una casa grandes'], 'Una casa grande'),
            omp('¿Cómo se dice bien?',    ['Unos niños felices', 'Unos niños feliz', 'Una niños felices'], 'Unos niños felices'),
        ]),

        est('Comparar', 'Más, menos o igual', '📊', 'opcion_multiple', [
            omp('El elefante es ___ que el ratón.',   ['más grande', 'menos grande', 'tan pequeño'], 'más grande', '🐘'),
            omp('La tortuga es ___ que la liebre.',   ['más lenta', 'más rápida', 'igual de rápida'], 'más lenta', '🐢'),
            omp('¿Cuál es el superlativo de «grande»?', ['Grandísimo', 'Más grande', 'Grandecito'], 'Grandísimo'),
            omp('«Tan alto como» expresa…',           ['Igualdad', 'Superioridad', 'Inferioridad'], 'Igualdad'),
            omp('¿Cuál es el comparativo irregular de «bueno»?', ['Mejor', 'Más bueno', 'Buenísimo'], 'Mejor'),
        ]),

        est('Describe la imagen', 'Elige el adjetivo que corresponde', '🖼️', 'opcion_multiple', [
            omp('¿Cómo es el sol?',        ['Brillante', 'Oscuro', 'Húmedo'], 'Brillante', '☀️'),
            omp('¿Cómo es el hielo?',      ['Frío', 'Caliente', 'Blando'], 'Frío', '🧊'),
            omp('¿Cómo es el algodón?',    ['Suave', 'Áspero', 'Duro'], 'Suave', '☁️'),
            omp('¿Cómo es el limón?',      ['Ácido', 'Dulce', 'Salado'], 'Ácido', '🍋'),
            omp('¿Cómo es una tortuga?',   ['Lenta', 'Veloz', 'Ruidosa'], 'Lenta', '🐢'),
        ]),
    ],
],

[
    'slug'  => 'el-verbo-y-los-tiempos',
    'title' => 'El verbo y los tiempos',
    'description' => 'La palabra que dice qué pasa, y cuándo: pasado, presente y futuro.',
    'objective' => 'Identificar el verbo en una oración y reconocer sus tres tiempos básicos.',
    'icon' => '🏃', 'nivel' => 'primaria-media', 'bloque' => 'gramatica',
    'duracion' => 13, 'tags' => ['escritura', 'lectura', 'logica'],
    'estaciones' => [

        est('¿Qué es un verbo?', 'La palabra de la acción', '⚡', 'opcion_multiple', [
            omp('¿Qué expresa un verbo?',            ['Una acción o un estado', 'Una cualidad', 'Un nombre'], 'Una acción o un estado'),
            omp('En «Ana corre rápido», ¿cuál es el verbo?', ['Corre', 'Ana', 'Rápido'], 'Corre'),
            omp('¿Cuál de estas es un verbo?',       ['Saltar', 'Salto alto', 'Deportista'], 'Saltar'),
            omp('¿En qué terminan los verbos en infinitivo?', ['-ar, -er, -ir', '-ito, -ita', '-mente'], '-ar, -er, -ir'),
            omp('¿Cuál NO es un verbo?',             ['Mesa', 'Comer', 'Escribir'], 'Mesa'),
        ]),

        est('Pasado, presente y futuro', 'Cuándo ocurre la acción', '🕰️', 'opcion_multiple', [
            omp('«Yo comí» está en…',      ['Pasado', 'Presente', 'Futuro'], 'Pasado'),
            omp('«Yo como» está en…',      ['Presente', 'Pasado', 'Futuro'], 'Presente'),
            omp('«Yo comeré» está en…',    ['Futuro', 'Presente', 'Pasado'], 'Futuro'),
            omp('«Ayer jugamos» está en…', ['Pasado', 'Futuro', 'Presente'], 'Pasado'),
            omp('«Mañana iremos» está en…', ['Futuro', 'Pasado', 'Presente'], 'Futuro'),
        ]),

        est('Conjuga bien', 'La persona y el número', '👤', 'opcion_multiple', [
            omp('Yo ___ al colegio.',      ['voy', 'vas', 'van'], 'voy'),
            omp('Nosotros ___ un cuento.', ['leemos', 'lee', 'leo'], 'leemos'),
            omp('Ellos ___ fútbol.',       ['juegan', 'juega', 'juego'], 'juegan'),
            omp('Tú ___ muy bien.',        ['cantas', 'canta', 'cantamos'], 'cantas'),
            omp('Ella ___ una carta.',     ['escribe', 'escribo', 'escriben'], 'escribe'),
        ]),

        est('Los pronombres', 'Palabras que reemplazan al nombre', '🔄', 'opcion_multiple', [
            omp('¿Qué hace un pronombre?',   ['Reemplaza al sustantivo', 'Describe', 'Indica acción'], 'Reemplaza al sustantivo'),
            omp('En «María canta», ¿qué pronombre reemplaza a María?', ['Ella', 'Él', 'Nosotros'], 'Ella'),
            omp('¿Qué pronombre usas para hablar de ti?', ['Yo', 'Tú', 'Ellos'], 'Yo'),
            omp('¿Qué pronombre usas para dos o más, incluyéndote?', ['Nosotros', 'Ustedes', 'Ellas'], 'Nosotros'),
            omp('¿Cuál es un pronombre?',    ['Ellos', 'Corre', 'Verde'], 'Ellos'),
        ]),

        est('Desafío gramatical', 'Cinco preguntas de repaso', '🏆', 'desafio_final', [
            reto('En «El gato negro duerme», ¿cuál es el sustantivo?', ['Gato', 'Negro', 'Duerme'], 'Gato'),
            reto('En esa misma oración, ¿cuál es el adjetivo?',        ['Negro', 'Gato', 'Duerme'], 'Negro'),
            reto('En esa misma oración, ¿cuál es el verbo?',           ['Duerme', 'Gato', 'El'], 'Duerme'),
            reto('¿Qué partes mínimas necesita una oración?',          ['Sujeto y predicado', 'Solo verbo', 'Solo sustantivo'], 'Sujeto y predicado'),
            reto('En «Los niños juegan», ¿cuál es el sujeto?',         ['Los niños', 'Juegan', 'Los'], 'Los niños'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · ORTOGRAFÍA Y ACENTUACIÓN
// =====================================================================

[
    'slug'  => 'mayusculas-y-puntuacion',
    'title' => 'Mayúsculas y puntuación',
    'description' => 'El punto, la coma y las mayúsculas: lo que hace que un texto se pueda leer.',
    'objective' => 'Aplicar el uso de mayúsculas y los signos de puntuación básicos.',
    'icon' => '❗', 'nivel' => 'primaria-media', 'bloque' => 'ortografia-y-acentuacion',
    'duracion' => 12, 'tags' => ['escritura', 'lectura', 'atencion'],
    'estaciones' => [

        est('¿Cuándo va mayúscula?', 'Tres reglas y ya', '🔠', 'opcion_multiple', [
            omp('¿Va mayúscula al empezar una oración?',   ['Sí, siempre', 'No', 'Solo a veces'], 'Sí, siempre'),
            omp('¿Va mayúscula después de un punto?',      ['Sí', 'No', 'Solo si es nombre'], 'Sí'),
            omp('¿Va mayúscula en los nombres propios?',   ['Sí', 'No', 'Solo apellidos'], 'Sí'),
            omp('¿Va mayúscula en los días de la semana en español?', ['No', 'Sí', 'Solo el lunes'], 'No'),
            omp('¿Cuál está bien escrito?',                ['Vivo en Colombia.', 'vivo en colombia.', 'Vivo En Colombia.'], 'Vivo en Colombia.'),
        ]),

        est('El punto y la coma', 'Cada uno hace una pausa distinta', '⏸️', 'opcion_multiple', [
            omp('¿Qué signo cierra una oración completa?',  ['El punto', 'La coma', 'Los dos puntos'], 'El punto'),
            omp('¿Qué signo separa elementos de una lista?', ['La coma', 'El punto', 'El guion'], 'La coma'),
            omp('¿Cuál está bien?',   ['Compré pan, leche y huevos.', 'Compré pan leche y huevos', 'Compré, pan leche, y huevos.'], 'Compré pan, leche y huevos.'),
            omp('¿Qué signo va antes de una enumeración?',  ['Los dos puntos', 'El punto', 'El guion'], 'Los dos puntos'),
            omp('¿Qué signo indica una pausa larga entre párrafos?', ['El punto y aparte', 'La coma', 'El guion'], 'El punto y aparte'),
        ]),

        est('Preguntar y exclamar', 'En español van dos signos', '❓', 'opcion_multiple', [
            omp('¿Cómo se escribe bien una pregunta en español?', ['¿Cómo estás?', 'Cómo estás?', '¿Cómo estás'], '¿Cómo estás?'),
            omp('¿Cómo se escribe bien una exclamación?',         ['¡Qué alegría!', 'Qué alegría!', '¡Qué alegría'], '¡Qué alegría!'),
            omp('¿Cuántos signos lleva una pregunta en español?', ['Dos: al abrir y al cerrar', 'Uno', 'Ninguno'], 'Dos: al abrir y al cerrar'),
            omp('¿Qué expresa el signo de exclamación?',          ['Sorpresa, alegría o énfasis', 'Una duda', 'Una pausa'], 'Sorpresa, alegría o énfasis'),
            omp('¿Va mayúscula después de un signo de interrogación de cierre?', ['Sí, normalmente', 'No', 'Nunca'], 'Sí, normalmente'),
        ]),

        est('Corrige la escritura', 'Elige la forma correcta', '✅', 'ortografia', [
            ['e' => '📝', 'opts' => ['Hoy es lunes.', 'hoy es Lunes.'],       'correct' => 'Hoy es lunes.'],
            ['e' => '❓', 'opts' => ['¿Dónde vives?', 'Dónde vives?'],        'correct' => '¿Dónde vives?'],
            ['e' => '❗', 'opts' => ['¡Cuidado!', 'Cuidado!'],                'correct' => '¡Cuidado!'],
            ['e' => '🏙️', 'opts' => ['Vivo en Bogotá.', 'vivo en bogotá.'],   'correct' => 'Vivo en Bogotá.'],
            ['e' => '🛒', 'opts' => ['Traje papas, arroz y sal.', 'Traje papas arroz y sal'], 'correct' => 'Traje papas, arroz y sal.'],
        ]),
    ],
],

[
    'slug'  => 'la-tilde',
    'title' => 'La tilde',
    'description' => 'Sílaba tónica, agudas, graves y esdrújulas: cuándo lleva tilde una palabra.',
    'objective' => 'Clasificar palabras por su acento y aplicar las reglas generales de tildación.',
    'icon' => '🔺', 'nivel' => 'primaria-superior', 'bloque' => 'ortografia-y-acentuacion',
    'duracion' => 14, 'tags' => ['escritura', 'atencion', 'logica'],
    'estaciones' => [

        est('La sílaba tónica', 'La que suena más fuerte', '🔊', 'opcion_multiple', [
            omp('¿Qué es la sílaba tónica?',           ['La que se pronuncia con más fuerza', 'La primera', 'La última siempre'], 'La que se pronuncia con más fuerza'),
            omp('En «ca-sa», ¿cuál es la tónica?',     ['ca', 'sa', 'ninguna'], 'ca'),
            omp('En «pa-pel», ¿cuál es la tónica?',    ['pel', 'pa', 'ninguna'], 'pel'),
            omp('En «lá-piz», ¿cuál es la tónica?',    ['lá', 'piz', 'ninguna'], 'lá'),
            omp('¿Tienen todas las palabras sílaba tónica?', ['Sí', 'No', 'Solo las largas'], 'Sí'),
        ]),

        est('Agudas, graves y esdrújulas', 'Según dónde cae el acento', '📐', 'opcion_multiple', [
            omp('Una palabra aguda tiene la fuerza en…',    ['La última sílaba', 'La penúltima', 'La antepenúltima'], 'La última sílaba'),
            omp('Una palabra grave tiene la fuerza en…',    ['La penúltima sílaba', 'La última', 'La antepenúltima'], 'La penúltima sílaba'),
            omp('Una palabra esdrújula tiene la fuerza en…', ['La antepenúltima sílaba', 'La última', 'La penúltima'], 'La antepenúltima sílaba'),
            omp('«Camión» es una palabra…',                 ['Aguda', 'Grave', 'Esdrújula'], 'Aguda'),
            omp('«Médico» es una palabra…',                 ['Esdrújula', 'Aguda', 'Grave'], 'Esdrújula'),
        ]),

        est('Las reglas', 'Cuándo se pone la tilde', '📏', 'opcion_multiple', [
            omp('Las agudas llevan tilde cuando terminan en…', ['n, s o vocal', 'consonante', 'nunca'], 'n, s o vocal'),
            omp('Las graves llevan tilde cuando terminan en…', ['consonante que no sea n ni s', 'vocal', 'siempre'], 'consonante que no sea n ni s'),
            omp('¿Cuándo llevan tilde las esdrújulas?',        ['Siempre', 'Nunca', 'Solo si terminan en vocal'], 'Siempre'),
            omp('¿Lleva tilde «reloj»?',                       ['No, termina en j', 'Sí', 'Solo en plural'], 'No, termina en j'),
            omp('¿Lleva tilde «árbol»?',                       ['Sí, es grave terminada en l', 'No', 'Solo en singular'], 'Sí, es grave terminada en l'),
        ]),

        est('¿Con tilde o sin tilde?', 'Elige la escritura correcta', '✍️', 'ortografia', [
            ['e' => '🚌', 'opts' => ['autobús', 'autobus'],   'correct' => 'autobús'],
            ['e' => '🌳', 'opts' => ['árbol', 'arbol'],       'correct' => 'árbol'],
            ['e' => '🎵', 'opts' => ['música', 'musica'],     'correct' => 'música'],
            ['e' => '🐦', 'opts' => ['pájaro', 'pajaro'],     'correct' => 'pájaro'],
            ['e' => '☕', 'opts' => ['café', 'cafe'],         'correct' => 'café'],
            ['e' => '📖', 'opts' => ['libro', 'líbro'],       'correct' => 'libro'],
        ]),

        est('Completa las reglas', 'Coloca cada palabra en su hueco', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Lee la regla completa antes de elegir', [
                [
                    'titulo' => 'Dónde cae la fuerza',
                    'texto'  => 'La sílaba que se pronuncia con más fuerza se llama sílaba ___. '
                              . 'Si cae en la última sílaba, la palabra es ___; si cae en la '
                              . 'penúltima, es ___; y si cae en la antepenúltima, es ___. '
                              . 'Estas últimas llevan tilde ___.',
                    'huecos' => ['tónica', 'aguda', 'grave', 'esdrújula', 'siempre'],
                    'extra'  => ['átona', 'nunca', 'llana'],
                ],
                [
                    'titulo' => 'La tilde que cambia el significado',
                    'texto'  => 'Algunas palabras se escriben igual pero significan cosas distintas, '
                              . 'y la tilde es lo único que las separa. «Papa» es un alimento y «papá» '
                              . 'es una ___. Esa tilde se llama ___. Por eso, poner o quitar una tilde '
                              . 'no es un detalle: puede cambiar el ___ de toda la frase.',
                    'huecos' => ['persona', 'diacrítica', 'sentido'],
                    'extra'  => ['sílaba', 'esdrújula'],
                ],
            ])),

        est('Desafío de la tilde', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('¿Qué diferencia hay entre «papa» y «papá»?',  ['La tilde cambia el significado', 'Ninguna', 'Solo el sonido'], 'La tilde cambia el significado'),
            reto('¿Qué es la tilde diacrítica?',                ['La que distingue palabras iguales con distinto significado', 'La de las esdrújulas', 'La de los verbos'], 'La que distingue palabras iguales con distinto significado'),
            reto('¿Cuál lleva tilde: «el» o «él» cuando es pronombre?', ['él', 'el', 'Ninguno'], 'él'),
            reto('«Sofá» es una palabra…',                      ['Aguda con tilde', 'Grave', 'Esdrújula'], 'Aguda con tilde'),
            reto('¿Cuántas tildes puede llevar una palabra?',   ['Solo una', 'Dos', 'Las que necesite'], 'Solo una'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · VOCABULARIO Y SIGNIFICADO
// =====================================================================

[
    'slug'  => 'sinonimos-y-antonimos',
    'title' => 'Sinónimos y antónimos',
    'description' => 'Palabras que significan lo mismo y palabras que significan lo contrario.',
    'objective' => 'Reconocer y usar sinónimos y antónimos para enriquecer el vocabulario.',
    'icon' => '↔️', 'nivel' => 'primaria-media', 'bloque' => 'vocabulario-y-significado',
    'duracion' => 12, 'tags' => ['vocabulario', 'lectura'],
    'estaciones' => [

        est('Sinónimos', 'Distinta palabra, mismo sentido', '🟰', 'opcion_multiple', [
            omp('¿Cuál es sinónimo de «bonito»?',   ['Hermoso', 'Feo', 'Grande'], 'Hermoso'),
            omp('¿Cuál es sinónimo de «contento»?', ['Alegre', 'Triste', 'Cansado'], 'Alegre'),
            omp('¿Cuál es sinónimo de «rápido»?',   ['Veloz', 'Lento', 'Pesado'], 'Veloz'),
            omp('¿Cuál es sinónimo de «casa»?',     ['Vivienda', 'Calle', 'Puerta'], 'Vivienda'),
            omp('¿Cuál es sinónimo de «empezar»?',  ['Comenzar', 'Terminar', 'Parar'], 'Comenzar'),
        ]),

        est('Antónimos', 'Lo contrario', '↔️', 'opcion_multiple', [
            omp('¿Cuál es antónimo de «grande»?',   ['Pequeño', 'Enorme', 'Gigante'], 'Pequeño'),
            omp('¿Cuál es antónimo de «frío»?',     ['Caliente', 'Helado', 'Fresco'], 'Caliente'),
            omp('¿Cuál es antónimo de «subir»?',    ['Bajar', 'Trepar', 'Ascender'], 'Bajar'),
            omp('¿Cuál es antónimo de «claro»?',    ['Oscuro', 'Brillante', 'Nítido'], 'Oscuro'),
            omp('¿Cuál es antónimo de «lleno»?',    ['Vacío', 'Repleto', 'Completo'], 'Vacío'),
        ]),

        est('Empareja contrarios', 'Une cada palabra con su opuesto', '🔗', 'emparejar', [
            ['e' => '☀️', 'w' => 'Día y noche'],
            ['e' => '🔥', 'w' => 'Calor y frío'],
            ['e' => '⬆️', 'w' => 'Subir y bajar'],
            ['e' => '😀', 'w' => 'Alegre y triste'],
            ['e' => '🐘', 'w' => 'Grande y pequeño'],
            ['e' => '🏃', 'w' => 'Rápido y lento'],
        ]),

        est('Cambia la palabra', 'Escribe un sinónimo', '⌨️', 'teclado',
            ['ALEGRE', 'VELOZ', 'HERMOSO', 'ENORME', 'AMIGO']),
    ],
],

[
    'slug'  => 'familias-de-palabras',
    'title' => 'Familias de palabras',
    'description' => 'Prefijos, sufijos y campos semánticos: cómo las palabras se agrupan y se transforman.',
    'objective' => 'Formar palabras con prefijos y sufijos y agrupar términos por campo semántico.',
    'icon' => '🌳', 'nivel' => 'primaria-superior', 'bloque' => 'vocabulario-y-significado',
    'duracion' => 14, 'tags' => ['vocabulario', 'logica', 'clasificacion'],
    'estaciones' => [

        est('Prefijos', 'Lo que va delante cambia el sentido', '⬅️', 'opcion_multiple', [
            omp('¿Qué significa el prefijo «des-»?',   ['Lo contrario', 'Repetición', 'Antes'], 'Lo contrario'),
            omp('«Hacer» con el prefijo «des-» es…',   ['Deshacer', 'Rehacer', 'Prehacer'], 'Deshacer'),
            omp('¿Qué significa el prefijo «re-»?',    ['Repetir de nuevo', 'Lo contrario', 'Muy grande'], 'Repetir de nuevo'),
            omp('¿Qué significa el prefijo «pre-»?',   ['Antes', 'Después', 'Contra'], 'Antes'),
            omp('«Ver» con el prefijo «pre-» es…',     ['Prever', 'Rever', 'Desver'], 'Prever'),
        ]),

        est('Sufijos', 'Lo que va al final también', '➡️', 'opcion_multiple', [
            omp('«Casa» con el sufijo «-ita» es…',     ['Casita', 'Casota', 'Descasa'], 'Casita'),
            omp('¿Qué indica el sufijo «-ito»?',       ['Que es pequeño', 'Que es grande', 'Que es feo'], 'Que es pequeño'),
            omp('¿Qué indica el sufijo «-ote»?',       ['Que es grande', 'Que es pequeño', 'Que es nuevo'], 'Que es grande'),
            omp('«Panadero» viene de…',                ['Pan', 'Padre', 'Pana'], 'Pan'),
            omp('¿Qué indica el sufijo «-ero» en «zapatero»?', ['El oficio', 'El tamaño', 'El lugar'], 'El oficio'),
        ]),

        est('Campo semántico', 'Palabras del mismo tema', '🗂️', 'opcion_multiple', [
            omp('¿Cuál NO pertenece al campo de la cocina?', ['Bicicleta', 'Olla', 'Cuchara'], 'Bicicleta'),
            omp('¿Cuál NO pertenece al campo del colegio?',  ['Sartén', 'Cuaderno', 'Pupitre'], 'Sartén'),
            omp('¿Cuál NO pertenece al campo de los animales?', ['Silla', 'Perro', 'Gato'], 'Silla'),
            omp('¿Qué es un campo semántico?',               ['Un grupo de palabras relacionadas por su significado', 'Palabras que riman', 'Palabras con tilde'], 'Un grupo de palabras relacionadas por su significado'),
            omp('¿Cuál pertenece al campo de la música?',    ['Guitarra', 'Martillo', 'Zapato'], 'Guitarra', '🎸'),
        ]),

        est('Palabras de la misma familia', 'Comparten una raíz', '🌱', 'opcion_multiple', [
            omp('¿Cuál es de la familia de «flor»?',     ['Florero', 'Flotar', 'Fluir'], 'Florero'),
            omp('¿Cuál es de la familia de «libro»?',    ['Librería', 'Libre', 'Libra'], 'Librería'),
            omp('¿Cuál es de la familia de «mar»?',      ['Marino', 'Martillo', 'Marzo'], 'Marino'),
            omp('¿Cuál es de la familia de «leche»?',    ['Lechero', 'Lecho', 'Lectura'], 'Lechero'),
            omp('¿Qué comparten las palabras de una familia?', ['La misma raíz', 'La misma tilde', 'El mismo número de letras'], 'La misma raíz'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · TIPOS DE TEXTO
// =====================================================================

[
    'slug'  => 'textos-que-informan',
    'title' => 'Textos que informan',
    'description' => 'La noticia, el periódico y la radio: contar lo que pasó sin inventar nada.',
    'objective' => 'Reconocer la estructura de una noticia y distinguir hecho de opinión.',
    'icon' => '📰', 'nivel' => 'primaria-media', 'bloque' => 'tipos-de-texto',
    'duracion' => 13, 'tags' => ['lectura', 'comprension', 'escritura'],
    'estaciones' => [

        est('Partes de una noticia', 'Cómo se arma la información', '🗞️', 'opcion_multiple', [
            omp('¿Cómo se llama el título de una noticia?', ['Titular', 'Portada', 'Firma'], 'Titular'),
            omp('¿Qué es la entradilla?',                   ['El primer párrafo con lo esencial', 'La foto', 'El final'], 'El primer párrafo con lo esencial'),
            omp('¿Qué preguntas responde una noticia?',     ['Qué, quién, cuándo, dónde y por qué', 'Solo qué', 'Ninguna'], 'Qué, quién, cuándo, dónde y por qué'),
            omp('¿Debe una noticia inventar datos?',        ['No, nunca', 'Sí, si es más interesante', 'A veces'], 'No, nunca'),
            omp('¿Quién escribe las noticias?',             ['El periodista', 'El poeta', 'El científico'], 'El periodista'),
        ]),

        est('Hecho u opinión', 'La diferencia que hay que ver', '🔍', 'opcion_multiple', [
            omp('«Ayer llovió en Barranquilla» es…',        ['Un hecho', 'Una opinión', 'Un cuento'], 'Un hecho'),
            omp('«La lluvia es lo más aburrido del mundo» es…', ['Una opinión', 'Un hecho', 'Un dato'], 'Una opinión'),
            omp('«El partido terminó 2 a 1» es…',           ['Un hecho', 'Una opinión', 'Una fábula'], 'Un hecho'),
            omp('«Ese equipo juega horrible» es…',          ['Una opinión', 'Un hecho', 'Un dato'], 'Una opinión'),
            omp('¿Qué se puede comprobar?',                 ['Un hecho', 'Una opinión', 'Los dos igual'], 'Un hecho'),
        ]),

        est('La entrevista', 'Preguntar para saber', '🎤', 'opcion_multiple', [
            omp('¿Qué es una entrevista?',               ['Un diálogo de preguntas y respuestas para informar', 'Un cuento', 'Una lista'], 'Un diálogo de preguntas y respuestas para informar'),
            omp('¿Qué debe preparar el entrevistador?',  ['Las preguntas', 'Las respuestas', 'Nada'], 'Las preguntas'),
            omp('¿Cómo debe ser una buena pregunta?',    ['Clara y abierta', 'Confusa', 'Que se responda con sí o no siempre'], 'Clara y abierta'),
            omp('¿Qué se hace después de la entrevista?', ['Se ordena y se escribe', 'Se olvida', 'Se inventa el resto'], 'Se ordena y se escribe'),
            omp('¿Hay que pedir permiso para grabar a alguien?', ['Sí, siempre', 'No', 'Solo si es famoso'], 'Sí, siempre'),
        ]),

        est('Ordena la noticia', 'De lo más importante a lo secundario', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena las partes de una noticia',
            'items' => ['Titular', 'Entradilla', 'Cuerpo de la noticia', 'Detalles secundarios', 'Firma del periodista'],
        ]),
    ],
],

[
    'slug'  => 'textos-que-instruyen',
    'title' => 'Textos que instruyen',
    'description' => 'Recetas, instrucciones y manuales: textos para hacer algo paso a paso.',
    'objective' => 'Reconocer la estructura de un texto instructivo y seguir su secuencia.',
    'icon' => '📋', 'nivel' => 'primaria-media', 'bloque' => 'tipos-de-texto',
    'duracion' => 12, 'tags' => ['lectura', 'secuencias', 'comprension'],
    'estaciones' => [

        est('¿Cómo es un instructivo?', 'Su forma tiene un porqué', '🧾', 'opcion_multiple', [
            omp('¿Para qué sirve un texto instructivo?', ['Para explicar cómo hacer algo', 'Para contar una historia', 'Para opinar'], 'Para explicar cómo hacer algo'),
            omp('¿Qué dos partes tiene una receta?',     ['Ingredientes y preparación', 'Título y firma', 'Personajes y final'], 'Ingredientes y preparación'),
            omp('¿Importa el orden de los pasos?',       ['Sí, mucho', 'No', 'Solo al final'], 'Sí, mucho'),
            omp('¿En qué forma verbal suelen ir las instrucciones?', ['Imperativo: mezcla, corta, agrega', 'Pasado', 'Condicional'], 'Imperativo: mezcla, corta, agrega'),
            omp('¿Cuál es un texto instructivo?',        ['El manual de un juguete', 'Una poesía', 'Una noticia'], 'El manual de un juguete'),
        ]),

        est('Ordena la receta', 'Paso a paso', '🍳', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para hacer una limonada',
            'items' => ['Reunir los ingredientes', 'Exprimir los limones', 'Mezclar con agua', 'Agregar azúcar', 'Servir con hielo'],
        ]),

        est('Sigue las instrucciones', 'Leer bien antes de actuar', '👣', 'opcion_multiple', [
            omp('La receta dice «primero precalentar el horno». ¿Cuándo lo hago?', ['Antes que todo lo demás', 'Al final', 'No importa'], 'Antes que todo lo demás'),
            omp('Si me salto un paso, ¿qué puede pasar?',   ['Que no salga bien', 'Nada', 'Que salga mejor'], 'Que no salga bien'),
            omp('¿Qué hago si no entiendo una instrucción?', ['La releo o pregunto', 'La invento', 'La salto'], 'La releo o pregunto'),
            omp('¿Qué palabras marcan el orden?',           ['Primero, luego, después, finalmente', 'Bonito, feo', 'Yo, tú'], 'Primero, luego, después, finalmente'),
            omp('¿Para qué sirven los dibujos en un instructivo?', ['Ayudan a entender el paso', 'Decoran', 'No sirven'], 'Ayudan a entender el paso'),
        ]),

        est('Ordena el día', 'Secuencias de la vida diaria', '⏰', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para prepararte al colegio',
            'items' => ['Levantarse', 'Bañarse', 'Vestirse', 'Desayunar', 'Salir de casa'],
        ]),
    ],
],

[
    'slug'  => 'textos-que-convencen',
    'title' => 'Textos que convencen',
    'description' => 'La publicidad y el texto argumentativo: cómo se defiende una idea y cómo no dejarse engañar.',
    'objective' => 'Reconocer la intención persuasiva de un texto y distinguir argumento de manipulación.',
    'icon' => '📢', 'nivel' => 'primaria-superior', 'bloque' => 'tipos-de-texto',
    'duracion' => 14, 'tags' => ['lectura', 'comprension', 'deduccion'],
    'estaciones' => [

        est('Argumentar', 'Defender una idea con razones', '💬', 'opcion_multiple', [
            omp('¿Qué es un argumento?',                 ['Una razón que sostiene una idea', 'Una pelea', 'Un cuento'], 'Una razón que sostiene una idea'),
            omp('¿Qué es la tesis de un texto argumentativo?', ['La idea que se quiere defender', 'El título', 'El final'], 'La idea que se quiere defender'),
            omp('¿Cuál es un buen argumento?',           ['Uno apoyado en datos o ejemplos', 'Gritar más fuerte', 'Repetirlo mucho'], 'Uno apoyado en datos o ejemplos'),
            omp('¿Se puede argumentar sin insultar?',    ['Sí, siempre', 'No', 'Solo entre adultos'], 'Sí, siempre'),
            omp('¿Qué pasa si alguien me da un mejor argumento?', ['Puedo cambiar de opinión', 'Debo insistir igual', 'Me enojo'], 'Puedo cambiar de opinión'),
        ]),

        /*
         * La publicidad se trata como lo que es: un texto con una intención
         * que el niño ya está consumiendo todos los días. Enseñarle a
         * reconocer el truco es más útil que prohibirle la pantalla.
         */
        est('La publicidad', 'Todo anuncio quiere algo de ti', '📺', 'opcion_multiple', [
            omp('¿Qué busca un anuncio publicitario?',     ['Que compres o hagas algo', 'Informarte sin más', 'Enseñarte'], 'Que compres o hagas algo'),
            omp('«El mejor del mundo» es…',                ['Una afirmación sin pruebas', 'Un dato comprobado', 'Una ley'], 'Una afirmación sin pruebas'),
            omp('¿Por qué salen niños felices en los anuncios de juguetes?', ['Para que asocies el juguete con la felicidad', 'Porque son actores baratos', 'Por casualidad'], 'Para que asocies el juguete con la felicidad'),
            omp('¿Qué es la letra pequeña de un anuncio?', ['Condiciones que no quieren que leas', 'Un adorno', 'El precio'], 'Condiciones que no quieren que leas'),
            omp('Antes de pedir algo que vi en un anuncio, conviene…', ['Preguntarme si de verdad lo necesito', 'Pedirlo ya', 'Creer todo'], 'Preguntarme si de verdad lo necesito'),
        ]),

        est('Detecta la intención', '¿Para qué se escribió este texto?', '🎯', 'opcion_multiple', [
            omp('«Compra ya, oferta por hoy» quiere…',     ['Convencerte', 'Informarte', 'Entretenerte'], 'Convencerte'),
            omp('«El agua hierve a 100 °C» quiere…',       ['Informarte', 'Convencerte', 'Emocionarte'], 'Informarte'),
            omp('«Había una vez un dragón» quiere…',       ['Entretenerte', 'Convencerte', 'Instruirte'], 'Entretenerte'),
            omp('«Mezcle la harina con el agua» quiere…',  ['Instruirte', 'Convencerte', 'Emocionarte'], 'Instruirte'),
            omp('«Deberíamos reciclar más» quiere…',       ['Convencerte', 'Instruirte', 'Entretenerte'], 'Convencerte'),
        ]),

        est('Desafío del lector crítico', 'No creerse todo', '🏆', 'desafio_final', [
            reto('Si un texto no dice de dónde saca un dato, conviene…', ['Desconfiar y comprobarlo', 'Creerlo igual', 'Compartirlo'], 'Desconfiar y comprobarlo'),
            reto('¿Qué es una fuente confiable?',              ['Una que se puede verificar y dice quién la firma', 'La primera que aparece', 'La más compartida'], 'Una que se puede verificar y dice quién la firma'),
            reto('Antes de compartir algo en internet, conviene…', ['Comprobar si es cierto', 'Compartirlo rápido', 'No leerlo'], 'Comprobar si es cierto'),
            reto('¿Qué es una noticia falsa?',                 ['Información inventada que parece real', 'Una noticia vieja', 'Una opinión'], 'Información inventada que parece real'),
            reto('¿Todo lo que está escrito es verdad?',       ['No', 'Sí', 'Solo en internet'], 'No'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · GÉNEROS LITERARIOS
// =====================================================================

[
    'slug'  => 'el-cuento-y-sus-partes',
    'title' => 'El cuento y sus partes',
    'description' => 'Inicio, nudo y desenlace, con sus personajes, su tiempo y su lugar.',
    'objective' => 'Reconocer la estructura del texto narrativo y sus elementos.',
    'icon' => '📕', 'nivel' => 'primaria-media', 'bloque' => 'generos-literarios',
    'duracion' => 13, 'tags' => ['lectura', 'comprension', 'secuencias'],
    'estaciones' => [

        est('Las tres partes', 'Todo cuento tiene esta forma', '📐', 'opcion_multiple', [
            omp('¿Cómo se llama el comienzo de un cuento?', ['Inicio', 'Nudo', 'Desenlace'], 'Inicio'),
            omp('¿Dónde aparece el problema?',              ['En el nudo', 'En el inicio', 'En el desenlace'], 'En el nudo'),
            omp('¿Cómo se llama el final?',                 ['Desenlace', 'Nudo', 'Prólogo'], 'Desenlace'),
            omp('¿Quién cuenta la historia?',               ['El narrador', 'El lector', 'El editor'], 'El narrador'),
            omp('¿Quién es el protagonista?',               ['El personaje principal', 'El malo', 'El autor'], 'El personaje principal'),
        ]),

        est('Un cuento corto', 'Lee y responde', '📖', 'cuento', [
            'slides' => [
                ['img' => '🐢', 'text' => 'En un charco vivía una tortuga muy lenta llamada Nina. Todos los animales del monte se burlaban de ella.'],
                ['img' => '🐇', 'text' => 'Un día, una liebre presumida la retó a una carrera hasta el árbol grande. Nina aceptó sin dudar.'],
                ['img' => '🏃', 'text' => 'La liebre salió corriendo y sacó mucha ventaja. Como iba tan adelantada, decidió dormir una siesta bajo un mango.'],
                ['img' => '🐢', 'text' => 'Nina caminó despacio, pero sin parar ni una sola vez. Pasó junto a la liebre dormida sin hacer ruido.'],
                ['img' => '🏆', 'text' => 'Cuando la liebre despertó, Nina ya estaba tocando el árbol. Desde ese día nadie volvió a burlarse de ella.'],
            ],
            'qs' => [
                reto('¿Cómo se llamaba la tortuga?',      ['Nina', 'Lola', 'Tina'], 'Nina'),
                reto('¿Quién retó a quién?',              ['La liebre retó a la tortuga', 'La tortuga retó a la liebre', 'Nadie retó a nadie'], 'La liebre retó a la tortuga'),
                reto('¿Por qué perdió la liebre?',        ['Se durmió confiada', 'Se lastimó', 'Se perdió'], 'Se durmió confiada'),
                reto('¿Qué hizo la tortuga para ganar?',  ['Caminar sin parar', 'Correr muy rápido', 'Hacer trampa'], 'Caminar sin parar'),
                reto('¿Cuál es la enseñanza del cuento?', ['La constancia vale más que la rapidez', 'Hay que dormir siesta', 'Las liebres son malas'], 'La constancia vale más que la rapidez'),
            ],
        ]),

        est('Ordena la historia', 'Del principio al final', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los momentos del cuento',
            'items' => ['Se presenta a los personajes', 'Aparece el problema', 'Los personajes intentan resolverlo', 'Se resuelve el problema', 'Se cuenta cómo quedó todo'],
        ]),

        est('La fábula', 'Cuento con moraleja', '🦊', 'opcion_multiple', [
            omp('¿Qué es una fábula?',                  ['Un relato breve con enseñanza', 'Una noticia', 'Un poema largo'], 'Un relato breve con enseñanza'),
            omp('¿Quiénes suelen ser los personajes de una fábula?', ['Animales que hablan', 'Científicos', 'Robots'], 'Animales que hablan'),
            omp('¿Cómo se llama la enseñanza final?',   ['Moraleja', 'Titular', 'Prólogo'], 'Moraleja'),
            omp('¿Es larga o corta una fábula?',        ['Corta', 'Muy larga', 'Depende'], 'Corta'),
            omp('¿Qué diferencia hay entre cuento y fábula?', ['La fábula siempre deja una enseñanza explícita', 'Ninguna', 'El cuento es más corto'], 'La fábula siempre deja una enseñanza explícita'),
        ]),
    ],
],

[
    'slug'  => 'poesia-y-teatro',
    'title' => 'Poesía y teatro',
    'description' => 'El género lírico y el dramático: versos que suenan y textos escritos para actuarse.',
    'objective' => 'Distinguir los géneros lírico y dramático y reconocer sus recursos característicos.',
    'icon' => '🎭', 'nivel' => 'primaria-superior', 'bloque' => 'generos-literarios',
    'duracion' => 14, 'tags' => ['lectura', 'comprension', 'vocabulario'],
    'estaciones' => [

        est('El género lírico', 'La poesía', '🎼', 'opcion_multiple', [
            omp('¿Cómo se llama cada línea de un poema?', ['Verso', 'Párrafo', 'Escena'], 'Verso'),
            omp('¿Cómo se llama un grupo de versos?',     ['Estrofa', 'Capítulo', 'Acto'], 'Estrofa'),
            omp('¿Qué es la rima?',                       ['La coincidencia de sonidos al final de los versos', 'El título', 'La longitud'], 'La coincidencia de sonidos al final de los versos'),
            omp('¿Qué expresa sobre todo la poesía?',     ['Sentimientos y emociones', 'Instrucciones', 'Noticias'], 'Sentimientos y emociones'),
            omp('«Sus ojos son dos luceros» es…',         ['Una metáfora', 'Un dato', 'Una instrucción'], 'Una metáfora'),
        ]),

        est('Rimas', '¿Cuáles suenan igual?', '🔔', 'opcion_multiple', [
            omp('¿Qué rima con «canción»?',   ['Corazón', 'Cantar', 'Música'], 'Corazón'),
            omp('¿Qué rima con «flor»?',      ['Color', 'Planta', 'Jardín'], 'Color'),
            omp('¿Qué rima con «mar»?',       ['Cantar', 'Agua', 'Playa'], 'Cantar'),
            omp('¿Qué rima con «estrella»?',  ['Bella', 'Cielo', 'Noche'], 'Bella'),
            omp('¿Qué rima con «amigo»?',     ['Contigo', 'Amistad', 'Cariño'], 'Contigo'),
        ]),

        est('El género dramático', 'El teatro', '🎬', 'opcion_multiple', [
            omp('¿Para qué se escribe una obra de teatro?', ['Para representarla', 'Para leerla en silencio', 'Para cantarla'], 'Para representarla'),
            omp('¿Cómo se llaman las indicaciones para los actores?', ['Acotaciones', 'Versos', 'Titulares'], 'Acotaciones'),
            omp('¿Cómo se llama cada parte grande de una obra?', ['Acto', 'Estrofa', 'Párrafo'], 'Acto'),
            omp('¿Qué es un diálogo?',                     ['La conversación entre personajes', 'La descripción del lugar', 'El título'], 'La conversación entre personajes'),
            omp('¿Qué es un monólogo?',                    ['Cuando un personaje habla solo', 'Una conversación', 'Una canción'], 'Cuando un personaje habla solo'),
        ]),

        est('La tradición oral del Caribe', 'Historias que se cuentan sin libro', '🪘', 'opcion_multiple', [
            omp('¿Qué es la tradición oral?',              ['Historias que pasan de boca en boca', 'Libros antiguos', 'Periódicos viejos'], 'Historias que pasan de boca en boca'),
            omp('¿Cómo se conservaban antes de la escritura?', ['Contándolas y recordándolas', 'Grabándolas', 'No se conservaban'], 'Contándolas y recordándolas'),
            omp('¿Qué es una leyenda?',                    ['Un relato tradicional con algo de real y algo de fantástico', 'Una noticia', 'Un manual'], 'Un relato tradicional con algo de real y algo de fantástico'),
            omp('¿Qué es un mito?',                        ['Un relato que explica el origen de algo', 'Una receta', 'Una carta'], 'Un relato que explica el origen de algo'),
            omp('¿Por qué importa conservar la tradición oral?', ['Es parte de nuestra identidad cultural', 'Por costumbre', 'No importa'], 'Es parte de nuestra identidad cultural'),
        ]),

        est('Crucigrama literario', 'Cada pista es un término del género', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'VERSO',    'pista' => 'Cada línea de un poema'],
                ['w' => 'ESTROFA',  'pista' => 'Grupo de versos'],
                ['w' => 'RIMA',     'pista' => 'Sonidos iguales al final de dos versos'],
                ['w' => 'ACTO',     'pista' => 'Cada parte grande de una obra de teatro'],
                ['w' => 'DIALOGO',  'pista' => 'Conversación entre personajes'],
                ['w' => 'MITO',     'pista' => 'Relato que explica el origen de algo'],
                ['w' => 'LEYENDA',  'pista' => 'Relato tradicional con algo real y algo fantástico'],
            ])),

        est('Desafío literario', 'Cinco preguntas de géneros', '🏆', 'desafio_final', [
            reto('¿A qué género pertenece un poema?',       ['Lírico', 'Narrativo', 'Dramático'], 'Lírico'),
            reto('¿A qué género pertenece una obra de teatro?', ['Dramático', 'Lírico', 'Narrativo'], 'Dramático'),
            reto('¿A qué género pertenece una novela?',     ['Narrativo', 'Lírico', 'Dramático'], 'Narrativo'),
            reto('¿Qué género se escribe en verso?',        ['El lírico', 'El narrativo', 'El instructivo'], 'El lírico'),
            reto('¿Qué tienen en común todos los géneros literarios?', ['Usan el lenguaje de forma artística', 'Se escriben en verso', 'Son largos'], 'Usan el lenguaje de forma artística'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'comprension-lectora',
    'title' => 'Comprensión lectora',
    'description' => 'Leer no es descifrar letras: es entender lo que dice, lo que insinúa y lo que opina.',
    'objective' => 'Responder preguntas literales, inferenciales y críticas sobre un texto.',
    'icon' => '🔍', 'nivel' => 'primaria-media', 'bloque' => 'tipos-de-texto',
    'duracion' => 14, 'tags' => ['lectura', 'comprension', 'deduccion'],
    'estaciones' => [

        /*
         * Los tres niveles de lectura que pide la malla de K3: literal,
         * inferencial y crítico. Aquí van sobre el MISMO texto, uno detrás
         * de otro, porque la diferencia entre ellos solo se ve comparando:
         * la primera respuesta está escrita, la segunda hay que deducirla y
         * la tercera no está en el texto en absoluto.
         */
        est('Lee el cuento', 'Después vienen las preguntas', '📖', 'cuento', [
            'slides' => [
                ['img' => '🏫', 'text' => 'Camilo era nuevo en el colegio. El primer día se sentó solo en la última fila y no habló con nadie.'],
                ['img' => '⚽', 'text' => 'En el recreo, los demás armaron un partido. Camilo se quedó mirando desde la reja, con las manos en los bolsillos.'],
                ['img' => '🙋', 'text' => 'Entonces Lucía se acercó y le preguntó si sabía jugar de arquero. Camilo no dijo nada, pero se quitó la chaqueta.'],
                ['img' => '🥅', 'text' => 'Camilo atajó tres tiros seguidos. Cuando sonó el timbre, tres niños le preguntaron su nombre.'],
                ['img' => '😊', 'text' => 'Al día siguiente, Camilo llegó temprano y se sentó en la segunda fila.'],
            ],
            'qs' => [
                reto('¿Dónde se sentó Camilo el primer día?',      ['En la última fila', 'En la primera fila', 'En el patio'], 'En la última fila'),
                reto('¿Quién se le acercó en el recreo?',          ['Lucía', 'El profesor', 'Nadie'], 'Lucía'),
                reto('¿Por qué se quitó la chaqueta?',             ['Porque aceptó jugar', 'Porque tenía calor', 'Porque se iba'], 'Porque aceptó jugar'),
                reto('¿Cómo crees que se sentía Camilo al principio?', ['Solo y tímido', 'Muy alegre', 'Enojado'], 'Solo y tímido'),
                reto('¿Qué nos dice que ya se sentía mejor al final?', ['Llegó temprano y se sentó más adelante', 'Que atajó tres tiros', 'Que sonó el timbre'], 'Llegó temprano y se sentó más adelante'),
            ],
        ]),

        est('Lo que está escrito', 'Nivel literal', '📄', 'opcion_multiple', [
            omp('¿Qué es una pregunta literal?',     ['Una cuya respuesta está escrita en el texto', 'Una de opinión', 'Una que hay que deducir'], 'Una cuya respuesta está escrita en el texto'),
            omp('«¿Cómo se llama el personaje?» es una pregunta…', ['Literal', 'Inferencial', 'Crítica'], 'Literal'),
            omp('Para responder una pregunta literal conviene…', ['Volver al texto y buscarlo', 'Adivinar', 'Opinar'], 'Volver al texto y buscarlo'),
            omp('¿Puede haber dos respuestas literales distintas?', ['No, está escrito', 'Sí, muchas', 'Siempre'], 'No, está escrito'),
            omp('Si no encuentro la respuesta en el texto, quizá…', ['No sea una pregunta literal', 'El texto esté mal', 'No importa'], 'No sea una pregunta literal'),
        ]),

        est('Lo que se deduce', 'Nivel inferencial', '🕵️', 'opcion_multiple', [
            omp('¿Qué es una inferencia?',           ['Una conclusión que saco de las pistas del texto', 'Una copia del texto', 'Una opinión libre'], 'Una conclusión que saco de las pistas del texto'),
            omp('«Llegó empapado y cerró el paraguas.» ¿Qué pasó?', ['Estaba lloviendo', 'Hacía sol', 'Se cayó al río'], 'Estaba lloviendo'),
            omp('«Se le llenaron los ojos de lágrimas.» ¿Cómo se siente?', ['Emocionado o triste', 'Aburrido', 'Con sueño'], 'Emocionado o triste'),
            omp('«El plato quedó vacío en un minuto.» ¿Qué deduces?', ['Tenía mucha hambre', 'No le gustó', 'Se lo llevó'], 'Tenía mucha hambre'),
            omp('Una inferencia debe apoyarse en…',  ['Pistas del texto', 'Lo que yo quiera', 'El título'], 'Pistas del texto'),
        ]),

        est('Lo que opino', 'Nivel crítico', '💭', 'opcion_multiple', [
            omp('¿Qué es una pregunta crítica?',     ['Una que pide valorar o juzgar lo leído', 'Una que copia el texto', 'Una que deduce'], 'Una que pide valorar o juzgar lo leído'),
            omp('«¿Estuvo bien lo que hizo el personaje?» es…', ['Crítica', 'Literal', 'Inferencial'], 'Crítica'),
            omp('Al responder una pregunta crítica debo…', ['Dar mi opinión y explicar por qué', 'Copiar el texto', 'Callarme'], 'Dar mi opinión y explicar por qué'),
            omp('¿Pueden dos lectores opinar distinto del mismo texto?', ['Sí, si los dos argumentan', 'No', 'Solo si uno se equivoca'], 'Sí, si los dos argumentan'),
            omp('¿Vale una opinión sin ninguna razón detrás?', ['Vale poco: hay que sostenerla', 'Sí, igual', 'Siempre'], 'Vale poco: hay que sostenerla'),
        ]),
    ],
],

[
    'slug'  => 'la-historieta',
    'title' => 'La historieta',
    'description' => 'Viñetas, globos y onomatopeyas: contar una historia con imagen y texto a la vez.',
    'objective' => 'Reconocer los elementos del cómic y su función narrativa.',
    'icon' => '💥', 'nivel' => 'primaria-media', 'bloque' => 'tipos-de-texto',
    'duracion' => 12, 'tags' => ['lectura', 'observacion', 'escritura'],
    'estaciones' => [

        est('Las partes de una historieta', 'Cada elemento tiene su nombre', '🗯️', 'opcion_multiple', [
            omp('¿Cómo se llama cada cuadro de una historieta?', ['Viñeta', 'Página', 'Escena'], 'Viñeta'),
            omp('¿Dónde va lo que dice un personaje?',   ['En el globo de diálogo', 'En el título', 'Al pie'], 'En el globo de diálogo'),
            omp('¿Cómo se representa un pensamiento?',   ['Con un globo de nubecitas', 'Con un globo puntiagudo', 'Sin globo'], 'Con un globo de nubecitas'),
            omp('¿Qué es una onomatopeya?',              ['Una palabra que imita un sonido, como ¡PUM!', 'Un personaje', 'Un color'], 'Una palabra que imita un sonido, como ¡PUM!'),
            omp('¿Qué es la cartela o recuadro de texto?', ['Donde habla el narrador', 'Donde habla el personaje', 'El título'], 'Donde habla el narrador'),
        ]),

        est('Leer las imágenes', 'La imagen también narra', '👀', 'opcion_multiple', [
            omp('Si un globo es puntiagudo y con letras grandes, el personaje…', ['Grita', 'Susurra', 'Piensa'], 'Grita'),
            omp('Si el globo es pequeño y con letra fina…', ['Habla bajito', 'Grita', 'Canta'], 'Habla bajito'),
            omp('¿Qué indican unas líneas de movimiento detrás de un personaje?', ['Que se está moviendo rápido', 'Que está quieto', 'Que llueve'], 'Que se está moviendo rápido'),
            omp('¿En qué orden se leen las viñetas en español?', ['De izquierda a derecha y de arriba abajo', 'De derecha a izquierda', 'Al azar'], 'De izquierda a derecha y de arriba abajo'),
            omp('¿Qué pasa entre una viñeta y la siguiente?', ['El lector completa lo que no se ve', 'No pasa nada', 'Se repite todo'], 'El lector completa lo que no se ve'),
        ]),

        est('Ordena la historieta', 'De la primera viñeta a la última', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena las viñetas de esta historia',
            'items' => ['El niño ve el balón en el techo', 'Busca una escalera', 'Sube con cuidado', 'Alcanza el balón', 'Baja y juega con sus amigos'],
        ]),

        est('Sonidos del cómic', 'Une el sonido con lo que lo produce', '🔗', 'emparejar', [
            ['e' => '💥', 'w' => 'Explosión'],
            ['e' => '🚪', 'w' => 'Portazo'],
            ['e' => '💧', 'w' => 'Goteo'],
            ['e' => '⚡', 'w' => 'Rayo'],
            ['e' => '🔔', 'w' => 'Campana'],
            ['e' => '👏', 'w' => 'Aplauso'],
        ]),
    ],
],

[
    'slug'  => 'escribir-y-exponer',
    'title' => 'Escribir y exponer',
    'description' => 'Planear un texto antes de escribirlo, revisarlo después y presentarlo en voz alta.',
    'objective' => 'Aplicar el proceso de planificación, escritura y revisión, y exponer oralmente.',
    'icon' => '🎤', 'nivel' => 'primaria-superior', 'bloque' => 'tipos-de-texto',
    'duracion' => 14, 'tags' => ['escritura', 'comprension', 'lectura'],
    'estaciones' => [

        est('Antes de escribir', 'El plan textual', '🗺️', 'opcion_multiple', [
            omp('¿Qué es lo primero antes de escribir?', ['Decidir para quién escribo y para qué', 'Escribir sin parar', 'Buscar el título'], 'Decidir para quién escribo y para qué'),
            omp('¿Qué es el propósito de un texto?',     ['Lo que quiero lograr: informar, convencer, entretener', 'Su longitud', 'Su título'], 'Lo que quiero lograr: informar, convencer, entretener'),
            omp('¿Escribo igual para un amigo que para el rector?', ['No, cambia el registro', 'Sí', 'Solo cambia el saludo'], 'No, cambia el registro'),
            omp('¿Para qué sirve hacer un esquema antes?', ['Para no perderme y no repetir', 'Para tardar más', 'Para nada'], 'Para no perderme y no repetir'),
            omp('¿Qué es una lluvia de ideas?',          ['Anotar todo lo que se me ocurre sin filtrar', 'El texto final', 'La corrección'], 'Anotar todo lo que se me ocurre sin filtrar'),
        ]),

        est('Coherencia y cohesión', 'Que se entienda de principio a fin', '🔗', 'opcion_multiple', [
            omp('¿Qué es la coherencia?',            ['Que todo el texto trate del mismo tema con sentido', 'Que tenga muchas palabras', 'Que rime'], 'Que todo el texto trate del mismo tema con sentido'),
            omp('¿Qué es la cohesión?',              ['Que las frases estén bien enlazadas', 'Que sea largo', 'Que tenga dibujos'], 'Que las frases estén bien enlazadas'),
            omp('¿Cuál es un conector de tiempo?',   ['Después', 'Porque', 'Sin embargo'], 'Después'),
            omp('¿Cuál es un conector de causa?',    ['Porque', 'Luego', 'Además'], 'Porque'),
            omp('¿Cuál sirve para contrastar?',      ['Sin embargo', 'Y', 'Entonces'], 'Sin embargo'),
        ]),

        est('Revisar', 'Ningún texto sale bien a la primera', '✏️', 'opcion_multiple', [
            omp('¿Qué se hace después de escribir el borrador?', ['Revisarlo y corregirlo', 'Entregarlo', 'Borrarlo'], 'Revisarlo y corregirlo'),
            omp('¿Qué reviso primero?',              ['Si se entiende la idea', 'Las comas', 'El color'], 'Si se entiende la idea'),
            omp('¿Sirve que otro lea mi texto?',     ['Sí, ve lo que yo ya no veo', 'No', 'Solo el profesor'], 'Sí, ve lo que yo ya no veo'),
            omp('¿Qué reviso al final?',             ['Ortografía y puntuación', 'El tema', 'La idea principal'], 'Ortografía y puntuación'),
            omp('Si una frase no se entiende, ¿qué hago?', ['La reescribo más simple', 'La dejo', 'Le añado palabras'], 'La reescribo más simple'),
        ]),

        est('Exponer en voz alta', 'Hablar para que te entiendan', '🎤', 'desafio_final', [
            reto('¿Qué conviene hacer antes de exponer?',  ['Ensayar en voz alta', 'Memorizar palabra por palabra', 'Improvisar'], 'Ensayar en voz alta'),
            reto('¿A qué velocidad conviene hablar?',      ['Ni muy rápido ni muy lento', 'Lo más rápido posible', 'Muy despacio siempre'], 'Ni muy rápido ni muy lento'),
            reto('¿Para qué sirven las pausas al hablar?', ['Dan tiempo a entender y a respirar', 'Para nada', 'Para perder tiempo'], 'Dan tiempo a entender y a respirar'),
            reto('¿Hacia dónde miro?',                     ['Al público', 'Al piso', 'A mis notas todo el rato'], 'Al público'),
            reto('¿Es normal ponerse nervioso?',           ['Sí, y se pasa al empezar', 'No', 'Solo a los malos'], 'Sí, y se pasa al empezar'),
        ]),
    ],
],

[
    'slug'  => 'letras-que-se-confunden',
    'title' => 'Letras que se confunden',
    'description' => 'B y V, C, S y Z, G y J, H muda: las que más se equivocan y sus reglas.',
    'objective' => 'Aplicar reglas ortográficas de las grafías que suenan igual.',
    'icon' => '🔠', 'nivel' => 'primaria-media', 'bloque' => 'ortografia-y-acentuacion',
    'duracion' => 12, 'tags' => ['escritura', 'atencion'],
    'estaciones' => [

        est('B o V', 'Suenan igual, se escriben distinto', '🅱️', 'ortografia', [
            ['e' => '🚌', 'opts' => ['bus', 'vus'],           'correct' => 'bus'],
            ['e' => '🐄', 'opts' => ['vaca', 'baca'],         'correct' => 'vaca'],
            ['e' => '📕', 'opts' => ['libro', 'livro'],       'correct' => 'libro'],
            ['e' => '🎻', 'opts' => ['violín', 'biolín'],     'correct' => 'violín'],
            ['e' => '🌳', 'opts' => ['árbol', 'árvol'],       'correct' => 'árbol'],
            ['e' => '🏠', 'opts' => ['ventana', 'bentana'],   'correct' => 'ventana'],
        ]),

        est('C, S o Z', 'Tres letras, un sonido', '🇸', 'ortografia', [
            ['e' => '🏠', 'opts' => ['casa', 'caza'],         'correct' => 'casa'],
            ['e' => '👞', 'opts' => ['zapato', 'sapato'],     'correct' => 'zapato'],
            ['e' => '🌹', 'opts' => ['rosa', 'roza'],         'correct' => 'rosa'],
            ['e' => '🍰', 'opts' => ['delicioso', 'delisioso'], 'correct' => 'delicioso'],
            ['e' => '🐟', 'opts' => ['pez', 'pes'],           'correct' => 'pez'],
            ['e' => '💃', 'opts' => ['danza', 'dansa'],       'correct' => 'danza'],
        ]),

        est('G, J y la H muda', 'La que no suena', '🇭', 'ortografia', [
            ['e' => '🏨', 'opts' => ['hotel', 'otel'],        'correct' => 'hotel'],
            ['e' => '🥚', 'opts' => ['huevo', 'uevo'],        'correct' => 'huevo'],
            ['e' => '🦒', 'opts' => ['jirafa', 'girafa'],     'correct' => 'jirafa'],
            ['e' => '🧊', 'opts' => ['hielo', 'ielo'],        'correct' => 'hielo'],
            ['e' => '👦', 'opts' => ['gente', 'jente'],       'correct' => 'gente'],
            ['e' => '🐔', 'opts' => ['hoja', 'oja'],          'correct' => 'hoja'],
        ]),

        est('Las reglas', 'Por qué se escribe así', '📏', 'opcion_multiple', [
            omp('¿Qué letras llevan siempre B delante?',    ['M: bomba, hombro', 'N', 'R'], 'M: bomba, hombro'),
            omp('¿Se escriben con V las palabras que empiezan por «vice-»?', ['Sí', 'No', 'A veces'], 'Sí'),
            omp('¿Suena la H en español?',                  ['No, es muda', 'Sí', 'Solo al principio'], 'No, es muda'),
            omp('¿Cómo se escriben los verbos terminados en «-ger» y «-gir»?', ['Con G: proteger, dirigir', 'Con J', 'Con X'], 'Con G: proteger, dirigir'),
            omp('¿Qué hago si dudo de cómo se escribe una palabra?', ['La busco en el diccionario', 'La invento', 'La evito'], 'La busco en el diccionario'),
        ]),
    ],
],


[
    'slug'  => 'adivinanzas-y-trabalenguas',
    'title' => 'Adivinanzas y trabalenguas',
    'description' => 'Jugar con las palabras: rimas, retahílas y juegos de la tradición oral.',
    'objective' => 'Disfrutar del juego lingüístico y reconocer recursos de la tradición oral.',
    'icon' => '🎪', 'nivel' => 'primaria-inicial', 'bloque' => 'generos-literarios',
    'duracion' => 11, 'tags' => ['vocabulario', 'lectura', 'juego'],
    'estaciones' => [

        est('Adivina, adivinador', 'Piensa antes de responder', '🤔', 'opcion_multiple', [
            omp('Blanco por dentro, verde por fuera. Si quieres saber, espera. ¿Qué es?', ['La pera', 'La sandía', 'El limón'], 'La pera', '🍐'),
            omp('Tengo dientes y no muerdo, tengo mango y no soy fruta. ¿Qué soy?', ['Un peine', 'Un perro', 'Un cuchillo'], 'Un peine'),
            omp('Vuela sin alas, silba sin boca. ¿Qué es?', ['El viento', 'El pájaro', 'El avión'], 'El viento', '💨'),
            omp('Oro parece, plata no es. ¿Qué es?', ['El plátano', 'La moneda', 'La estrella'], 'El plátano', '🍌'),
            omp('¿Qué hace divertida una adivinanza?', ['Que juega con el doble sentido', 'Que es larga', 'Que es difícil'], 'Que juega con el doble sentido'),
        ]),

        est('Rimas', '¿Cuáles suenan parecido?', '🔔', 'opcion_multiple', [
            omp('¿Qué rima con «gato»?',     ['Zapato', 'Perro', 'Casa'], 'Zapato'),
            omp('¿Qué rima con «sol»?',      ['Caracol', 'Luna', 'Nube'], 'Caracol'),
            omp('¿Qué rima con «pan»?',      ['Can', 'Queso', 'Leche'], 'Can'),
            omp('¿Qué rima con «ratón»?',    ['Corazón', 'Ratita', 'Queso'], 'Corazón'),
            omp('¿Qué rima con «flor»?',     ['Color', 'Planta', 'Jardín'], 'Color'),
        ]),

        est('Trabalenguas', 'Dilo sin equivocarte', '👅', 'pronunciacion', [
            ['e' => '🐘', 'w' => 'Tres tristes tigres tragaban trigo'],
            ['e' => '🌾', 'w' => 'El cielo está enladrillado'],
            ['e' => '🐕', 'w' => 'Pablito clavó un clavito'],
            ['e' => '🍫', 'w' => 'Como poco coco como'],
            ['e' => '🌸', 'w' => 'Rosa Rizo reza en ruso'],
        ]),

        est('Juegos de palabras', 'La lengua también se juega', '🎈', 'opcion_multiple', [
            omp('¿Qué es un trabalenguas?',      ['Una frase difícil de decir rápido', 'Un cuento', 'Un poema triste'], 'Una frase difícil de decir rápido'),
            omp('¿Qué es una retahíla?',         ['Un texto que se recita seguido y con ritmo', 'Un dibujo', 'Una carta'], 'Un texto que se recita seguido y con ritmo'),
            omp('¿Para qué sirven estos juegos?', ['Para mejorar la pronunciación y la memoria', 'Para nada', 'Para dormir'], 'Para mejorar la pronunciación y la memoria'),
            omp('¿De dónde vienen las adivinanzas?', ['De la tradición oral, se cuentan de generación en generación', 'De internet', 'De un solo autor'], 'De la tradición oral, se cuentan de generación en generación'),
            omp('¿Se pueden inventar adivinanzas nuevas?', ['Sí, claro', 'No', 'Solo los adultos'], 'Sí, claro'),
        ]),
    ],
],

[
    'slug'  => 'el-diccionario',
    'title' => 'El diccionario',
    'description' => 'Buscar una palabra, entender su definición y descubrir cuántos significados tiene.',
    'objective' => 'Usar el diccionario para resolver dudas de significado y ortografía.',
    'icon' => '📔', 'nivel' => 'primaria-media', 'bloque' => 'vocabulario-y-significado',
    'duracion' => 12, 'tags' => ['vocabulario', 'lectura', 'clasificacion'],
    'estaciones' => [

        est('El orden alfabético', 'Así se busca', '🔤', 'ordenar_secuencia', [
            'title' => 'Ordena estas palabras alfabéticamente',
            'items' => ['Ave', 'Barco', 'Casa', 'Dedo', 'Escuela'],
        ]),

        est('Buscar rápido', 'Estrategias', '⚡', 'opcion_multiple', [
            omp('Para buscar «mesa», ¿por dónde abro el diccionario?', ['Por la mitad', 'Por el principio', 'Por el final'], 'Por la mitad'),
            omp('Si dos palabras empiezan igual, ¿qué miro?', ['La siguiente letra', 'La última letra', 'Su longitud'], 'La siguiente letra'),
            omp('¿Va antes «casa» o «cara»?',      ['Cara', 'Casa', 'Da igual'], 'Cara'),
            omp('¿Va antes «sol» o «sal»?',        ['Sal', 'Sol', 'Da igual'], 'Sal'),
            omp('¿Para qué sirven las palabras guía de cada página?', ['Indican la primera y la última de esa página', 'Son adorno', 'Son ejemplos'], 'Indican la primera y la última de esa página'),
        ]),

        est('Leer una entrada', 'Lo que trae cada palabra', '📖', 'opcion_multiple', [
            omp('¿Qué indica «s.» o «sust.» en el diccionario?', ['Que es un sustantivo', 'Que es singular', 'Que es raro'], 'Que es un sustantivo'),
            omp('¿Qué indica «v.» o «verb.»?',      ['Que es un verbo', 'Que es viejo', 'Que es vulgar'], 'Que es un verbo'),
            omp('Si una palabra tiene 1., 2. y 3., significa que…', ['Tiene varios significados', 'Está repetida', 'Es un error'], 'Tiene varios significados'),
            omp('¿Sirve el diccionario para saber cómo se escribe algo?', ['Sí', 'No', 'Solo para significados'], 'Sí'),
            omp('¿Qué es un sinónimo en la entrada?', ['Otra palabra de significado parecido', 'Un ejemplo', 'La traducción'], 'Otra palabra de significado parecido'),
        ]),

        est('Palabras con varios sentidos', 'La misma palabra, distinto significado', '🔀', 'opcion_multiple', [
            omp('«Banco» puede ser…',       ['Un asiento o una entidad financiera', 'Solo un asiento', 'Solo un edificio'], 'Un asiento o una entidad financiera'),
            omp('«Hoja» puede ser…',        ['De un árbol o de un cuaderno', 'Solo de árbol', 'Solo de papel'], 'De un árbol o de un cuaderno'),
            omp('«Cola» puede ser…',        ['De un animal o una fila de personas', 'Solo de animal', 'Solo una fila'], 'De un animal o una fila de personas'),
            omp('¿Cómo sé cuál significado es?', ['Por el contexto de la frase', 'Adivinando', 'Siempre es el primero'], 'Por el contexto de la frase'),
            omp('¿Cómo se llaman las palabras que se escriben igual y significan cosas distintas?', ['Homónimas', 'Sinónimas', 'Antónimas'], 'Homónimas'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN · textos con huecos y crucigramas
// =====================================================================

[
    'slug'  => 'leer-y-completar',
    'title' => 'Leer y completar',
    'description' => 'Textos a los que les faltan palabras. Solo se aciertan leyendo la frase entera.',
    'objective' => 'Usar el contexto de un texto para deducir la palabra que falta.',
    'icon' => '📝', 'nivel' => 'primaria-media', 'bloque' => 'tipos-de-texto',
    'duracion' => 14, 'tags' => ['lectura', 'comprension', 'deduccion'],
    'estaciones' => [

        /*
         * Un texto con huecos no se resuelve reconociendo una palabra: se
         * resuelve entendiendo qué pide la frase. Por eso los señuelos son
         * siempre palabras que encajarían gramaticalmente pero no por
         * significado — si el señuelo cantara mal al oído, el ejercicio se
         * resolvería sin leer.
         */
        est('Textos de la naturaleza', 'Empieza por lo fácil', '🌿', 'completar_texto',
            conTitulo('Completa el texto', 'Lee toda la frase antes de elegir', [
                [
                    'titulo' => 'La abeja',
                    'texto'  => 'La abeja vuela de ___ en flor buscando néctar. Al posarse, el '
                              . '___ se le pega al cuerpo y lo lleva a la siguiente planta. Sin ese '
                              . 'viaje, muchas plantas no podrían dar ___.',
                    'huecos' => ['flor', 'polen', 'frutos'],
                    'extra'  => ['árbol', 'agua'],
                ],
                [
                    'titulo' => 'El invierno del oso',
                    'texto'  => 'Cuando llega el frío y escasea la ___, el oso se refugia en su '
                              . 'cueva y baja el ritmo de su cuerpo. Así ___ energía durante meses. '
                              . 'Al llegar la primavera, ___ y sale a buscar alimento.',
                    'huecos' => ['comida', 'ahorra', 'despierta'],
                    'extra'  => ['nieve', 'gasta'],
                ],
            ])),

        est('Textos del día a día', 'Situaciones que ya conoces', '🏠', 'completar_texto',
            conTitulo('Completa el texto', 'Fíjate en lo que va antes y después', [
                [
                    'titulo' => 'Una receta',
                    'texto'  => 'Primero se ___ el horno a 180 grados. Mientras tanto, se '
                              . '___ la harina con los huevos hasta que no queden grumos. '
                              . 'Se vierte en el molde y se hornea ___ minutos.',
                    'huecos' => ['precalienta', 'mezcla', 'treinta'],
                    'extra'  => ['apaga', 'separa'],
                ],
                [
                    'titulo' => 'En la biblioteca',
                    'texto'  => 'Para llevarte un libro tienes que ___ tu carné en el mostrador. '
                              . 'El préstamo dura dos ___. Si lo devuelves tarde, no podrás pedir '
                              . 'otro durante un tiempo, así que conviene anotar la ___.',
                    'huecos' => ['presentar', 'semanas', 'fecha'],
                    'extra'  => ['esconder', 'horas'],
                ],
            ])),

        est('Textos que explican', 'Más largos y con más huecos', '🔬', 'completar_texto',
            conTitulo('Completa el texto', 'Aquí hacen falta más palabras', [
                [
                    'titulo' => 'Cómo se hace el pan',
                    'texto'  => 'El pan empieza con ___ de trigo, que se muele hasta convertirse en '
                              . '___. Se mezcla con agua, sal y ___, que es un hongo diminuto. La masa '
                              . 'reposa y ___ porque la levadura produce gas. Al final se mete al '
                              . '___ y sale dorada.',
                    'huecos' => ['granos', 'harina', 'levadura', 'crece', 'horno'],
                    'extra'  => ['semillas', 'azúcar', 'frío'],
                ],
            ])),

        est('¿Qué palabra falta?', 'Elige entre parecidas', '🎯', 'opcion_multiple', [
            omp('El niño ___ el libro en la mesa.',        ['dejó', 'bebió', 'corrió'], 'dejó'),
            omp('Hacía tanto frío que el agua se ___.',    ['congeló', 'evaporó', 'quemó'], 'congeló'),
            omp('Llegó tarde ___ perdió el bus.',          ['porque', 'aunque', 'mientras'], 'porque'),
            omp('Estudió mucho, ___ no aprobó.',           ['sin embargo', 'además', 'entonces'], 'sin embargo'),
            omp('___ terminar la tarea, salió a jugar.',   ['Después de', 'Antes de', 'Durante'], 'Después de'),
        ]),
    ],
],

[
    'slug'  => 'crucigramas-de-palabras',
    'title' => 'Crucigramas de palabras',
    'description' => 'Tres crucigramas con pistas: gramática, sinónimos y tipos de texto.',
    'objective' => 'Recuperar vocabulario de la materia a partir de una definición.',
    'icon' => '🔠', 'nivel' => 'primaria-superior', 'bloque' => 'vocabulario-y-significado',
    'duracion' => 15, 'tags' => ['vocabulario', 'lectura', 'reto'],
    'estaciones' => [

        est('Crucigrama de gramática', 'Las piezas de la oración', '📖', 'crucigrama',
            crucigrama([
                ['w' => 'VERBO',       'pista' => 'Palabra que expresa una acción'],
                ['w' => 'ADJETIVO',    'pista' => 'Palabra que dice cómo es algo'],
                ['w' => 'SUSTANTIVO',  'pista' => 'Palabra que nombra personas o cosas'],
                ['w' => 'SUJETO',      'pista' => 'De quién se dice algo en la oración'],
                ['w' => 'PLURAL',      'pista' => 'Cuando hay más de uno'],
                ['w' => 'TILDE',       'pista' => 'La rayita que marca la sílaba fuerte'],
            ])),

        est('Crucigrama de significados', 'Sinónimos y contrarios', '↔️', 'crucigrama',
            crucigrama([
                ['w' => 'ALEGRE',    'pista' => 'Sinónimo de contento'],
                ['w' => 'VELOZ',     'pista' => 'Sinónimo de rápido'],
                ['w' => 'HERMOSO',   'pista' => 'Sinónimo de bonito'],
                ['w' => 'VACIO',     'pista' => 'Lo contrario de lleno'],
                ['w' => 'OSCURO',    'pista' => 'Lo contrario de claro'],
                ['w' => 'BAJAR',     'pista' => 'Lo contrario de subir'],
            ])),

        est('Crucigrama de textos', 'Cada texto tiene su nombre', '📰', 'crucigrama',
            crucigrama([
                ['w' => 'NOTICIA',    'pista' => 'Texto que cuenta un hecho real y reciente'],
                ['w' => 'RECETA',     'pista' => 'Texto que explica cómo preparar algo'],
                ['w' => 'FABULA',     'pista' => 'Relato breve con animales y una enseñanza'],
                ['w' => 'MORALEJA',   'pista' => 'La enseñanza que deja ese relato'],
                ['w' => 'TITULAR',    'pista' => 'El título grande de una noticia'],
                ['w' => 'ENTREVISTA', 'pista' => 'Diálogo de preguntas y respuestas para informar'],
            ])),

        est('Desafío del vocabulario', 'Cinco preguntas finales', '🏆', 'desafio_final', [
            reto('¿Qué es un sinónimo?',            ['Una palabra de significado parecido', 'Una palabra contraria', 'Una palabra larga'], 'Una palabra de significado parecido'),
            reto('¿Qué es un prefijo?',             ['Lo que va delante de la raíz', 'Lo que va detrás', 'La tilde'], 'Lo que va delante de la raíz'),
            reto('¿Qué es un campo semántico?',     ['Palabras relacionadas por su significado', 'Palabras que riman', 'Palabras con tilde'], 'Palabras relacionadas por su significado'),
            reto('«Deshacer» lleva el prefijo…',    ['des-', 're-', 'pre-'], 'des-'),
            reto('¿Para qué sirve tener más vocabulario?', ['Para entender y decir más cosas', 'Para escribir largo', 'Para nada'], 'Para entender y decir más cosas'),
        ]),
    ],
],

],

'reasignar' => [],

];
