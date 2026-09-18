<?php
/**
 * pensamiento-primaria.php — Pensamiento y Lógica de 1.º a 6.º
 *
 * Esta categoría no sale de una malla concreta: sale de una columna que
 * aparece en TODAS. Las mallas de `MallasPrimaria/` repiten, materia tras
 * materia, las mismas habilidades bajo el título «Habilidades y actitudes»
 * —solucionar problemas, argumentar, representar, clasificar, deducir,
 * transferir lo aprendido a un contexto nuevo— y las de Matemáticas las
 * nombran explícitamente: «pensamiento estratégico», «establecer
 * generalizaciones», «interpretar y resolver problemas».
 *
 * Aquí se entrenan solas, sin el contenido de una materia encima. Un niño
 * que falla un problema de matemáticas muchas veces no falla la cuenta:
 * falla al entender qué le preguntan, y eso no se arregla practicando más
 * cuentas.
 *
 * Los dos bloques nuevos son los que faltaban: cómo se ataca un problema
 * cuando no se sabe la respuesta, y cómo se decide si algo es verdad.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'pensamiento',
    'name'       => 'Pensamiento y Lógica',
    'tagline'    => 'Observar, deducir, resolver problemas y no creerse todo',
    'icon'       => '🧠',
    'color'      => '#8d6e63',
    'sort_order' => 11,
],

'bloques' => [
    ['slug' => 'resolver-problemas', 'name' => 'Resolver Problemas', 'icon' => '🧩', 'sort_order' => 4,
     'description' => 'Qué hacer cuando no se sabe la respuesta: entender, planear, probar y comprobar.'],
    ['slug' => 'pensamiento-critico', 'name' => 'Pensamiento Crítico', 'icon' => '🔍', 'sort_order' => 5,
     'description' => 'Distinguir hecho de opinión, detectar trampas de razonamiento y pedir pruebas.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · RESOLVER PROBLEMAS
// =====================================================================

[
    'slug'  => 'entender-el-problema',
    'title' => 'Entender el problema',
    'description' => 'Antes de calcular hay que saber qué preguntan. La mitad de los errores están aquí.',
    'objective' => 'Identificar datos, incógnita y pregunta en un problema antes de resolverlo.',
    'icon' => '❓', 'nivel' => 'primaria-media', 'bloque' => 'resolver-problemas',
    'duracion' => 12, 'tags' => ['logica', 'comprension', 'deduccion'],
    'estaciones' => [

        /*
         * La estación clave del bloque, y la que casi nunca se entrena:
         * se pregunta QUÉ HAY QUE HACER, no cuánto da. El niño que sabe
         * elegir la operación resuelve problemas nuevos; el que solo sabe
         * calcular resuelve los que ya ha visto.
         */
        est('¿Qué me preguntan?', 'Identifica la pregunta, no el número', '🎯', 'opcion_multiple', [
            omp('«Tenía 12 y me dieron 5. ¿Cuántos tengo?» ¿Qué hago?', ['Sumar', 'Restar', 'Dividir'], 'Sumar'),
            omp('«Tenía 12 y perdí 5. ¿Cuántos quedan?» ¿Qué hago?',    ['Restar', 'Sumar', 'Multiplicar'], 'Restar'),
            omp('«Hay 4 cajas de 6. ¿Cuántos en total?» ¿Qué hago?',    ['Multiplicar', 'Sumar', 'Restar'], 'Multiplicar'),
            omp('«Reparto 20 entre 5. ¿Cuántos a cada uno?» ¿Qué hago?', ['Dividir', 'Multiplicar', 'Restar'], 'Dividir'),
            omp('«¿Cuántos más tiene Ana que Luis?» ¿Qué hago?',        ['Restar', 'Sumar', 'Multiplicar'], 'Restar'),
        ]),

        est('Datos que sobran', 'No todo número sirve', '🗑️', 'opcion_multiple', [
            omp('«Ana tiene 8 años y 5 canicas. Perdió 2 canicas. ¿Cuántas le quedan?» ¿Qué dato sobra?', ['Que tiene 8 años', 'Que tenía 5 canicas', 'Que perdió 2'], 'Que tiene 8 años'),
            omp('En ese problema, la respuesta es…',        ['3', '6', '13'], '3'),
            omp('«Un bus lleva 40 personas, sale a las 8 y llegan 12 más. ¿Cuántas van?» ¿Qué dato sobra?', ['La hora de salida', 'Las 40 personas', 'Las 12 que suben'], 'La hora de salida'),
            omp('¿Por qué a veces sobran datos en un problema?', ['Para comprobar si entendí la pregunta', 'Por error', 'Para hacerlo largo'], 'Para comprobar si entendí la pregunta'),
            omp('¿Qué hago si me falta un dato?',           ['Digo que no se puede resolver', 'Me lo invento', 'Uso otro cualquiera'], 'Digo que no se puede resolver'),
        ]),

        est('Ordena la estrategia', 'Cómo se ataca un problema', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para resolver un problema',
            'items' => ['Leer y entender qué preguntan', 'Ver qué datos tengo', 'Pensar un plan', 'Hacer las operaciones', 'Comprobar si la respuesta tiene sentido'],
        ]),

        est('¿Tiene sentido?', 'Comprobar antes de entregar', '🤔', 'opcion_multiple', [
            omp('Un niño mide 15 metros. ¿Tiene sentido?',        ['No, es imposible', 'Sí', 'Depende'], 'No, es imposible'),
            omp('Repartí 10 dulces entre 3 y le tocaron 20 a cada uno. ¿Tiene sentido?', ['No, no puede tocar más de lo que había', 'Sí', 'A veces'], 'No, no puede tocar más de lo que había'),
            omp('Tenía 50, gasté 20 y me quedan 70. ¿Tiene sentido?', ['No, gastar no aumenta', 'Sí', 'Depende'], 'No, gastar no aumenta'),
            omp('¿Para qué sirve estimar antes de calcular?',     ['Para saber si el resultado va por buen camino', 'Para nada', 'Para ir más lento'], 'Para saber si el resultado va por buen camino'),
            omp('Si mi resultado es rarísimo, ¿qué hago?',        ['Reviso el procedimiento', 'Lo entrego igual', 'Cambio el número'], 'Reviso el procedimiento'),
        ]),
    ],
],

[
    'slug'  => 'estrategias-para-resolver',
    'title' => 'Estrategias para resolver',
    'description' => 'Dibujar, hacer una tabla, buscar un patrón, ir hacia atrás o probar con un caso pequeño.',
    'objective' => 'Aplicar distintas estrategias heurísticas para abordar problemas no rutinarios.',
    'icon' => '🧩', 'nivel' => 'primaria-superior', 'bloque' => 'resolver-problemas',
    'duracion' => 14, 'tags' => ['logica', 'deduccion', 'reto'],
    'estaciones' => [

        est('Dibujarlo', 'Un dibujo resuelve medio problema', '✏️', 'opcion_multiple', [
            omp('¿Cuándo ayuda hacer un dibujo?',        ['Cuando el problema habla de posiciones o repartos', 'Nunca', 'Solo en dibujo'], 'Cuando el problema habla de posiciones o repartos'),
            omp('«Hay 5 postes en fila, separados 2 m. ¿Cuánto mide del primero al último?»', ['8 metros', '10 metros', '5 metros'], '8 metros'),
            omp('¿Por qué muchos responden 10 en ese problema?', ['Porque cuentan postes en vez de espacios', 'Porque suman mal', 'Porque no leen'], 'Porque cuentan postes en vez de espacios'),
            omp('¿Cuántos espacios hay entre 5 postes?', ['4', '5', '6'], '4'),
            omp('¿Qué me habría mostrado un dibujo?',    ['Que hay un espacio menos que postes', 'Nada', 'El resultado exacto sin pensar'], 'Que hay un espacio menos que postes'),
        ]),

        est('Buscar el patrón', 'Si se repite, hay una regla', '🔁', 'secuencia_numerica', [
            serie([2, 4, 6, null, 10]),
            serie([5, 10, 15, null, 25]),
            serie([100, 90, null, 70, 60]),
            serie([3, 9, 15, null, 27]),
            serie([11, 22, 33, null, 55]),
        ]),

        est('Empezar por lo pequeño', 'Si es difícil, hazlo más fácil', '🔬', 'opcion_multiple', [
            omp('Un problema con 100 objetos me abruma. ¿Qué hago?', ['Lo pruebo primero con 3 y busco la regla', 'Me rindo', 'Adivino'], 'Lo pruebo primero con 3 y busco la regla'),
            omp('Si 2 personas se dan la mano una vez, hay 1 apretón. Con 3 personas hay…', ['3', '2', '6'], '3'),
            omp('Con 4 personas hay…',                    ['6', '4', '8'], '6'),
            omp('¿Qué estrategia acabo de usar?',         ['Resolver casos pequeños y buscar la regla', 'Adivinar', 'Calcular todo'], 'Resolver casos pequeños y buscar la regla'),
            omp('¿Sirve hacer una tabla con los casos?',  ['Sí, deja ver el patrón', 'No', 'Solo en estadística'], 'Sí, deja ver el patrón'),
        ]),

        est('Ir hacia atrás', 'Empezar por el final', '⏪', 'opcion_multiple', [
            omp('«Pensé un número, le sumé 5 y me dio 12.» ¿Cuál era?', ['7', '17', '5'], '7'),
            omp('«Le resté 3 y me dio 10.» ¿Cuál era?',   ['13', '7', '30'], '13'),
            omp('«Lo multipliqué por 2 y me dio 18.» ¿Cuál era?', ['9', '36', '16'], '9'),
            omp('«Gasté la mitad y me quedaron 20.» ¿Cuánto tenía?', ['40', '10', '25'], '40'),
            omp('¿En qué consiste ir hacia atrás?',       ['Deshacer cada paso desde el resultado', 'Adivinar', 'Empezar de cero'], 'Deshacer cada paso desde el resultado'),
        ]),

        est('Desafío de estrategias', 'Cinco problemas para pensar', '🏆', 'desafio_final', [
            reto('Si hoy es martes, ¿qué día será en 7 días?',      ['Martes', 'Miércoles', 'Lunes'], 'Martes'),
            reto('Un caracol sube 3 m de día y baja 2 de noche. ¿Cuánto avanza al día?', ['1 metro', '5 metros', '3 metros'], '1 metro'),
            reto('Tengo monedas de 500 y de 1.000 y en total 3.000 con 4 monedas. ¿Cuántas de 1.000?', ['2', '3', '1'], '2'),
            reto('¿Cuántos minutos hay en un cuarto de hora?',      ['15', '20', '25'], '15'),
            reto('Si un tren sale a las 9:40 y viaja 50 minutos, llega a las…', ['10:30', '10:40', '9:90'], '10:30'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · PENSAMIENTO CRÍTICO
// =====================================================================

[
    'slug'  => 'hecho-u-opinion',
    'title' => 'Hecho u opinión',
    'description' => 'Lo que se puede comprobar y lo que alguien piensa: dos cosas que se confunden todo el día.',
    'objective' => 'Distinguir afirmaciones verificables de opiniones y pedir evidencia.',
    'icon' => '⚖️', 'nivel' => 'primaria-media', 'bloque' => 'pensamiento-critico',
    'duracion' => 12, 'tags' => ['logica', 'comprension', 'deduccion'],
    'estaciones' => [

        est('¿Se puede comprobar?', 'La prueba del hecho', '🔬', 'opcion_multiple', [
            omp('«El agua hierve a 100 °C a nivel del mar» es…', ['Un hecho', 'Una opinión', 'Un cuento'], 'Un hecho'),
            omp('«La sopa es asquerosa» es…',            ['Una opinión', 'Un hecho', 'Un dato'], 'Una opinión'),
            omp('«Colombia tiene 32 departamentos» es…', ['Un hecho', 'Una opinión', 'Una creencia'], 'Un hecho'),
            omp('«El mejor deporte es el fútbol» es…',   ['Una opinión', 'Un hecho', 'Un dato'], 'Una opinión'),
            omp('¿Qué distingue a un hecho?',            ['Que se puede comprobar', 'Que lo dice mucha gente', 'Que suena bien'], 'Que se puede comprobar'),
        ]),

        est('Pedir pruebas', 'Quién afirma, demuestra', '🧾', 'opcion_multiple', [
            omp('Alguien dice algo increíble. ¿Qué pregunto?', ['¿Cómo lo sabes?', 'Nada', '¿Y qué más?'], '¿Cómo lo sabes?'),
            omp('¿Es cierto algo porque lo repitan mucho?', ['No', 'Sí', 'Si son muchos, sí'], 'No'),
            omp('¿Es cierto algo porque lo diga alguien famoso?', ['No, hay que ver las pruebas', 'Sí', 'Depende de quién'], 'No, hay que ver las pruebas'),
            omp('¿Qué es una fuente confiable?',         ['Una que se puede verificar y dice quién responde', 'La primera que aparece', 'La más compartida'], 'Una que se puede verificar y dice quién responde'),
            omp('Si dos fuentes serias dicen lo mismo…', ['Es más probable que sea cierto', 'Es seguro que es falso', 'Da igual'], 'Es más probable que sea cierto'),
        ]),

        est('Trampas del razonamiento', 'Errores frecuentes', '🪤', 'opcion_multiple', [
            omp('«Todos lo hacen, así que está bien.» ¿Es un buen argumento?', ['No, que sea común no lo hace correcto', 'Sí', 'A veces'], 'No, que sea común no lo hace correcto'),
            omp('«Si no estás conmigo, estás contra mí.» ¿Qué falla?', ['Presenta solo dos opciones cuando hay más', 'Nada', 'Es muy larga'], 'Presenta solo dos opciones cuando hay más'),
            omp('«Eres pequeño, no puedes tener razón.» ¿Qué falla?', ['Ataca a la persona, no al argumento', 'Nada', 'Es cierta'], 'Ataca a la persona, no al argumento'),
            omp('«Llovió después de que bailé, luego mi baile causó la lluvia.» ¿Qué falla?', ['Que ocurra después no significa que sea la causa', 'Nada', 'Faltan datos del baile'], 'Que ocurra después no significa que sea la causa'),
            omp('¿Qué hago si no estoy seguro de algo?', ['Digo que no lo sé y lo averiguo', 'Invento', 'Cambio de tema'], 'Digo que no lo sé y lo averiguo'),
        ]),

        est('Hecho u opinión', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es un hecho comprobable?', 'Responde rápido', [
                ['e' => '🌡️', 'n' => 'El hielo se derrite con calor',   'ok' => true],
                ['e' => '🍦', 'n' => 'El helado de vainilla es el mejor', 'ok' => false],
                ['e' => '🌍', 'n' => 'La Tierra gira alrededor del Sol', 'ok' => true],
                ['e' => '🎨', 'n' => 'El azul es el color más bonito',   'ok' => false],
                ['e' => '📏', 'n' => 'Un metro tiene 100 centímetros',   'ok' => true],
                ['e' => '🎵', 'n' => 'Esa canción es aburrida',          'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'deducir-y-concluir',
    'title' => 'Deducir y concluir',
    'description' => 'Sacar conclusiones a partir de pistas, y saber cuándo NO se puede concluir.',
    'objective' => 'Aplicar razonamiento deductivo y reconocer los límites de la información disponible.',
    'icon' => '🕵️', 'nivel' => 'primaria-superior', 'bloque' => 'pensamiento-critico',
    'duracion' => 13, 'tags' => ['deduccion', 'logica', 'reto'],
    'estaciones' => [

        est('Si esto, entonces aquello', 'Deducción básica', '➡️', 'opcion_multiple', [
            omp('Todos los perros son animales. Firulais es un perro. Entonces…', ['Firulais es un animal', 'Firulais es un gato', 'No se puede saber'], 'Firulais es un animal'),
            omp('Si llueve, el piso se moja. Está lloviendo. Entonces…', ['El piso está mojado', 'El piso está seco', 'No se puede saber'], 'El piso está mojado'),
            omp('Si llueve, el piso se moja. El piso está mojado. Entonces…', ['No se puede saber si llovió', 'Llovió seguro', 'No llovió'], 'No se puede saber si llovió'),
            omp('¿Por qué en el caso anterior no se puede concluir?', ['El piso pudo mojarse por otra causa', 'Porque falta lluvia', 'Porque está mal escrito'], 'El piso pudo mojarse por otra causa'),
            omp('Todos los gatos maúllan. Esto maúlla. ¿Es un gato?', ['No necesariamente', 'Sí, seguro', 'Nunca'], 'No necesariamente'),
        ]),

        est('Caso cerrado', 'Deducir con pistas', '🔎', 'opcion_multiple', [
            omp('Ana, Luis y Sara tienen 7, 8 y 9 años. Ana no es la menor y Sara es la mayor. ¿Cuántos tiene Ana?', ['8', '7', '9'], '8'),
            omp('En ese caso, ¿cuántos tiene Luis?',      ['7', '8', '9'], '7'),
            omp('Hay una pelota roja, una azul y una verde. La roja no está a la izquierda y la verde está en el medio. ¿Cuál está a la izquierda?', ['La azul', 'La roja', 'La verde'], 'La azul'),
            omp('Pedro es más alto que Juan. Juan es más alto que Ema. ¿Quién es el más bajo?', ['Ema', 'Juan', 'Pedro'], 'Ema'),
            omp('En ese caso, ¿Pedro es más alto que Ema?', ['Sí, seguro', 'No', 'No se puede saber'], 'Sí, seguro'),
        ]),

        est('Cuando no alcanza la información', 'Saber que no se sabe', '🤷', 'opcion_multiple', [
            omp('«Un animal tiene cuatro patas.» ¿Es un perro?', ['No se puede saber', 'Sí', 'No, es un gato'], 'No se puede saber'),
            omp('«Sara llegó tarde.» ¿Se quedó dormida?',  ['No se puede saber', 'Sí', 'No'], 'No se puede saber'),
            omp('¿Es un error decir «no tengo suficiente información»?', ['No, es la respuesta correcta a veces', 'Sí', 'Solo en clase'], 'No, es la respuesta correcta a veces'),
            omp('¿Qué es una suposición?',                 ['Algo que doy por cierto sin comprobarlo', 'Un hecho', 'Una prueba'], 'Algo que doy por cierto sin comprobarlo'),
            omp('¿Qué riesgo tienen las suposiciones?',    ['Que si son falsas, todo lo que sigue falla', 'Ninguno', 'Que son lentas'], 'Que si son falsas, todo lo que sigue falla'),
        ]),

        est('Desafío del detective', 'Cinco casos para resolver', '🏆', 'desafio_final', [
            reto('Tres cajas: una tiene manzanas, otra peras y otra ambas. Todas están mal etiquetadas. ¿Cuántas hay que abrir como mínimo para saberlo todo?', ['1', '2', '3'], '1'),
            reto('Si todos los A son B, y todos los B son C, entonces todos los A son…', ['C', 'Ninguno', 'No se puede saber'], 'C'),
            reto('En una carrera adelanté al segundo. ¿En qué puesto voy?', ['Segundo', 'Primero', 'Tercero'], 'Segundo'),
            reto('Un padre y un hijo suman 40 años. El padre tiene 30 más. ¿Cuántos tiene el hijo?', ['5', '10', '20'], '5'),
            reto('Si algunas flores son rojas y todas las rosas son flores, ¿todas las rosas son rojas?', ['No se puede concluir', 'Sí', 'No, ninguna'], 'No se puede concluir'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'clasificar-y-comparar',
    'title' => 'Clasificar y comparar',
    'description' => 'Agrupar por criterios, encontrar el intruso y ver qué comparten cosas distintas.',
    'objective' => 'Clasificar elementos según criterios explícitos y justificar la agrupación.',
    'icon' => '🗂️', 'nivel' => 'primaria-inicial', 'bloque' => 'resolver-problemas',
    'duracion' => 11, 'tags' => ['clasificacion', 'observacion', 'logica'],
    'estaciones' => [

        est('¿Cuál no pertenece?', 'Encuentra el intruso', '🔍', 'opcion_multiple', [
            omp('¿Cuál NO es un animal?',        ['Mesa', 'Perro', 'Gato'], 'Mesa'),
            omp('¿Cuál NO es una fruta?',        ['Zanahoria', 'Manzana', 'Banano'], 'Zanahoria'),
            omp('¿Cuál NO es un número par?',    ['7', '4', '10'], '7'),
            omp('¿Cuál NO vuela?',               ['Pingüino', 'Águila', 'Colibrí'], 'Pingüino'),
            omp('¿Cuál NO es un medio de transporte?', ['Sombrero', 'Bus', 'Avión'], 'Sombrero'),
        ]),

        est('¿Por qué van juntos?', 'Descubre el criterio', '🧩', 'opcion_multiple', [
            omp('Perro, gato y caballo van juntos porque…', ['Todos son mamíferos', 'Todos vuelan', 'Todos son rojos'], 'Todos son mamíferos'),
            omp('2, 4 y 6 van juntos porque…',   ['Todos son pares', 'Todos son impares', 'Todos son primos'], 'Todos son pares'),
            omp('Triángulo, cuadrado y pentágono van juntos porque…', ['Todos son polígonos', 'Todos son redondos', 'Todos tienen 3 lados'], 'Todos son polígonos'),
            omp('Lápiz, esfero y marcador van juntos porque…', ['Todos sirven para escribir', 'Todos son azules', 'Todos son de madera'], 'Todos sirven para escribir'),
            omp('¿Puede un mismo grupo clasificarse de varias formas?', ['Sí, según el criterio que use', 'No', 'Solo una'], 'Sí, según el criterio que use'),
        ]),

        est('Comparar', 'En qué se parecen y en qué no', '⚖️', 'opcion_multiple', [
            omp('¿En qué se parecen un pez y un delfín?', ['Los dos viven en el agua', 'Los dos son mamíferos', 'Los dos tienen patas'], 'Los dos viven en el agua'),
            omp('¿En qué se diferencian?',        ['El delfín es mamífero y respira aire', 'En nada', 'El pez es mamífero'], 'El delfín es mamífero y respira aire'),
            omp('¿En qué se parecen un cuadrado y un rectángulo?', ['Los dos tienen 4 lados y ángulos rectos', 'Los dos son redondos', 'Los dos tienen 3 lados'], 'Los dos tienen 4 lados y ángulos rectos'),
            omp('¿En qué se diferencian?',        ['El cuadrado tiene los 4 lados iguales', 'En nada', 'El rectángulo es redondo'], 'El cuadrado tiene los 4 lados iguales'),
            omp('¿Para qué sirve comparar?',      ['Para entender mejor cada cosa', 'Para elegir la mejor', 'Para nada'], 'Para entender mejor cada cosa'),
        ]),

        est('Clasifica los seres vivos', 'Toca solo los animales', '🐾', 'seleccion_imagenes',
            conTitulo('Toca todos los animales', 'Las plantas y los objetos no van', [
                ['e' => '🐶', 'n' => 'Perro',   'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',   'ok' => false],
                ['e' => '🦋', 'n' => 'Mariposa', 'ok' => true],
                ['e' => '🪑', 'n' => 'Silla',   'ok' => false],
                ['e' => '🐟', 'n' => 'Pez',     'ok' => true],
                ['e' => '🌻', 'n' => 'Girasol', 'ok' => false],
                ['e' => '🐦', 'n' => 'Pájaro',  'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',  'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'acertijos-y-enigmas',
    'title' => 'Acertijos y enigmas',
    'description' => 'Problemas que no se resuelven calculando, sino mirándolos de otra forma.',
    'objective' => 'Resolver acertijos aplicando pensamiento lateral y revisión de suposiciones.',
    'icon' => '🎩', 'nivel' => 'primaria-media', 'bloque' => 'pensamiento-critico',
    'duracion' => 12, 'tags' => ['reto', 'deduccion', 'logica'],
    'estaciones' => [

        est('Adivinanzas clásicas', 'Piensa en la imagen, no en la palabra', '🤔', 'opcion_multiple', [
            omp('Cuanto más le quitas, más grande se hace. ¿Qué es?', ['Un hueco', 'Un pastel', 'Una montaña'], 'Un hueco'),
            omp('Tiene ciudades pero no casas, ríos pero no agua. ¿Qué es?', ['Un mapa', 'Un país', 'Un libro'], 'Un mapa'),
            omp('Va y viene sin moverse del sitio. ¿Qué es?', ['Un camino', 'Un carro', 'Un reloj'], 'Un camino'),
            omp('Cuanto más se seca, más se moja. ¿Qué es?', ['Una toalla', 'Una esponja seca', 'El sol'], 'Una toalla'),
            omp('¿Qué tienen las adivinanzas en común?', ['Piden mirar la cosa de otra manera', 'Piden calcular', 'Son imposibles'], 'Piden mirar la cosa de otra manera'),
        ]),

        est('Revisar la suposición', 'La trampa está en lo que doy por hecho', '🪤', 'opcion_multiple', [
            omp('Un gallo pone un huevo en el techo. ¿Hacia dónde rueda?', ['Los gallos no ponen huevos', 'A la derecha', 'A la izquierda'], 'Los gallos no ponen huevos'),
            omp('¿Cuántos animales de cada especie llevó Moisés en el arca?', ['Ninguno, fue Noé', 'Dos', 'Siete'], 'Ninguno, fue Noé'),
            omp('Si un avión se estrella en la frontera, ¿dónde se entierra a los supervivientes?', ['A los supervivientes no se les entierra', 'En un país', 'En el otro'], 'A los supervivientes no se les entierra'),
            omp('¿Qué falla en estas preguntas?',  ['Damos por cierta una parte del enunciado sin revisarla', 'Están mal escritas', 'Falta información'], 'Damos por cierta una parte del enunciado sin revisarla'),
            omp('¿Qué conviene hacer antes de responder rápido?', ['Releer el enunciado completo', 'Contestar lo primero', 'Adivinar'], 'Releer el enunciado completo'),
        ]),

        est('Lógica pura', 'Deducir con reglas', '🧠', 'opcion_multiple', [
            omp('Tengo dos monedas que suman 600 y una no es de 500. ¿Cuáles son?', ['Una de 500 y una de 100', 'Dos de 300', 'Es imposible'], 'Una de 500 y una de 100'),
            omp('¿Qué pesa más: un kilo de plumas o un kilo de plomo?', ['Pesan lo mismo', 'El plomo', 'Las plumas'], 'Pesan lo mismo'),
            omp('Si tres gatos cazan tres ratones en tres minutos, ¿cuánto tardan en cazar uno cada gato?', ['Tres minutos', 'Un minuto', 'Nueve minutos'], 'Tres minutos'),
            omp('Ayer tenía 8 años y el año que viene tendré 11. ¿Es posible?', ['Sí, si hoy es 1 de enero y cumplo el 31 de diciembre', 'No, es imposible', 'Solo en años bisiestos'], 'Sí, si hoy es 1 de enero y cumplo el 31 de diciembre'),
            omp('¿Qué tienen en común estos acertijos?', ['Parecen imposibles hasta que se revisa el supuesto', 'Son trampas sin solución', 'Requieren calculadora'], 'Parecen imposibles hasta que se revisa el supuesto'),
        ]),

        est('Desafío del enigma', 'Cinco retos finales', '🏆', 'desafio_final', [
            reto('Si hay 5 manzanas y me llevo 3, ¿cuántas tengo?', ['3', '2', '5'], '3'),
            reto('¿Cuántos meses tienen 28 días?',    ['Todos', 'Uno', 'Dos'], 'Todos'),
            reto('Un granjero tiene 17 ovejas y todas menos 9 se escapan. ¿Cuántas quedan?', ['9', '8', '17'], '9'),
            reto('¿Qué se rompe sin tocarlo?',        ['El silencio', 'Un vaso', 'Una rama'], 'El silencio'),
            reto('¿Cuál es la mejor estrategia con un acertijo?', ['Leer despacio y cuestionar lo obvio', 'Responder rápido', 'Adivinar'], 'Leer despacio y cuestionar lo obvio'),
        ]),
    ],
],

[
    'slug'  => 'causa-y-consecuencia',
    'title' => 'Causa y consecuencia',
    'description' => 'Distinguir qué produjo qué, y por qué dos cosas juntas no siempre están relacionadas.',
    'objective' => 'Identificar relaciones causales y distinguirlas de simples coincidencias.',
    'icon' => '⛓️', 'nivel' => 'primaria-superior', 'bloque' => 'pensamiento-critico',
    'duracion' => 13, 'tags' => ['logica', 'deduccion', 'comprension'],
    'estaciones' => [

        est('¿Qué causó qué?', 'El orden importa', '➡️', 'opcion_multiple', [
            omp('«No estudié y me fue mal.» ¿Cuál es la causa?', ['No estudiar', 'Que me fuera mal', 'Ninguna'], 'No estudiar'),
            omp('«Llovió y se inundó la calle.» ¿Cuál es la consecuencia?', ['Que se inundó', 'Que llovió', 'Las dos'], 'Que se inundó'),
            omp('«Regué la planta y creció.» ¿Cuál es la causa?', ['Regarla', 'Que creciera', 'El sol'], 'Regarla'),
            omp('¿Puede una causa tener varias consecuencias?', ['Sí', 'No', 'Solo una'], 'Sí'),
            omp('¿Puede una consecuencia tener varias causas?', ['Sí, casi siempre', 'No', 'Nunca'], 'Sí, casi siempre'),
        ]),

        est('Coincidencia no es causa', 'El error más común', '🎲', 'opcion_multiple', [
            omp('«Cada vez que llevo mi camiseta roja, mi equipo gana.» ¿Es una causa?', ['No, es una coincidencia', 'Sí', 'Depende del equipo'], 'No, es una coincidencia'),
            omp('«En verano se venden más helados y hay más gente en la playa.» ¿Uno causa el otro?', ['No, los dos los causa el calor', 'Sí, el helado lleva gente a la playa', 'Sí, la playa da hambre'], 'No, los dos los causa el calor'),
            omp('¿Qué significa que dos cosas ocurran juntas?', ['Que pueden o no estar relacionadas', 'Que una causa la otra', 'Que son iguales'], 'Que pueden o no estar relacionadas'),
            omp('¿Cómo se comprueba una causa de verdad?', ['Cambiando una cosa y viendo si cambia la otra', 'Mirando', 'Preguntando'], 'Cambiando una cosa y viendo si cambia la otra'),
            omp('¿Qué es un experimento controlado?', ['Uno donde solo cambia lo que quiero probar', 'Uno donde cambia todo', 'Una encuesta'], 'Uno donde solo cambia lo que quiero probar'),
        ]),

        est('Cadenas de consecuencias', 'Una cosa lleva a otra', '⛓️', 'opcion_multiple', [
            omp('Si talo el bosque, ¿qué puede pasar después?', ['El suelo se erosiona y hay menos lluvia', 'Nada', 'Crece más bosque'], 'El suelo se erosiona y hay menos lluvia'),
            omp('Si me acuesto muy tarde, ¿qué pasa al día siguiente?', ['Rindo menos y estoy irritable', 'Nada', 'Rindo más'], 'Rindo menos y estoy irritable'),
            omp('Si nadie recoge la basura del barrio, ¿qué pasa?', ['Se acumula, atrae plagas y enferma', 'Nada', 'Se va sola'], 'Se acumula, atrae plagas y enferma'),
            omp('¿Qué es pensar en consecuencias antes de actuar?', ['Anticipar qué puede pasar después', 'Tener miedo', 'No hacer nada'], 'Anticipar qué puede pasar después'),
            omp('¿Todas las consecuencias son inmediatas?', ['No, algunas tardan años', 'Sí', 'Solo las malas'], 'No, algunas tardan años'),
        ]),

        est('Ordena la cadena', 'De la causa al efecto final', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena esta cadena de causas y consecuencias',
            'items' => ['Se talan los árboles', 'El suelo queda sin raíces', 'La lluvia arrastra la tierra', 'El río se llena de sedimento', 'Mueren los peces'],
        ]),
    ],
],


[
    'slug'  => 'series-y-analogias',
    'title' => 'Series y analogías',
    'description' => 'Completar secuencias y descubrir qué relación une dos cosas.',
    'objective' => 'Resolver series lógicas y analogías identificando la relación subyacente.',
    'icon' => '🔗', 'nivel' => 'primaria-media', 'bloque' => 'resolver-problemas',
    'duracion' => 12, 'tags' => ['patrones', 'logica', 'deduccion'],
    'estaciones' => [

        est('Analogías', '¿Qué relación hay?', '↔️', 'opcion_multiple', [
            omp('Pájaro es a volar como pez es a…',   ['Nadar', 'Correr', 'Saltar'], 'Nadar'),
            omp('Día es a noche como frío es a…',     ['Calor', 'Nieve', 'Invierno'], 'Calor'),
            omp('Zapato es a pie como guante es a…',  ['Mano', 'Cabeza', 'Brazo'], 'Mano'),
            omp('Médico es a hospital como profesor es a…', ['Colegio', 'Casa', 'Parque'], 'Colegio'),
            omp('Cachorro es a perro como potro es a…', ['Caballo', 'Vaca', 'Gato'], 'Caballo'),
        ]),

        est('Series de figuras', 'Descubre la regla', '🔷', 'opcion_multiple', [
            omp('🔺 🔺 🔵 🔺 🔺 🔵 … ¿qué sigue?',    ['🔺', '🔵', '⬜'], '🔺'),
            omp('⬜ ⬛ ⬜ ⬛ … ¿qué sigue?',           ['⬜', '⬛', '🔵'], '⬜'),
            omp('⭐ ⭐ ⭐ 🌙 ⭐ ⭐ ⭐ 🌙 … ¿qué sigue?', ['⭐', '🌙', '☀️'], '⭐'),
            omp('Si el patrón se repite cada 4, ¿qué figura ocupa el lugar 8?', ['La misma que el lugar 4', 'La del lugar 1', 'Ninguna'], 'La misma que el lugar 4'),
            omp('¿Qué hay que buscar en una serie?',  ['La regla que la genera', 'El elemento más bonito', 'La longitud'], 'La regla que la genera'),
        ]),

        est('Series numéricas', 'Completa el hueco', '🔢', 'secuencia_numerica', [
            serie([1, 4, 7, null, 13]),
            serie([20, 17, null, 11, 8]),
            serie([8, 16, 24, null, 40]),
            serie([90, 80, 70, null, 50]),
            serie([15, 30, 45, null, 75]),
        ]),

        est('Desafío de la lógica', 'Cinco retos', '🏆', 'desafio_final', [
            reto('Libro es a leer como comida es a…',   ['Comer', 'Cocinar', 'Comprar'], 'Comer'),
            reto('Si A vale 1 y B vale 2, ¿cuánto vale D?', ['4', '3', '5'], '4'),
            reto('¿Qué número sigue: 2, 6, 10, 14…?',   ['18', '16', '20'], '18'),
            reto('Grande es a pequeño como alto es a…', ['Bajo', 'Ancho', 'Largo'], 'Bajo'),
            reto('¿Qué habilidad entrenan las analogías?', ['Ver relaciones entre ideas', 'Contar', 'Memorizar'], 'Ver relaciones entre ideas'),
        ]),
    ],
],

],

'reasignar' => [],

];
