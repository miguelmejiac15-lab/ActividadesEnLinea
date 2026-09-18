<?php
/**
 * pensamiento.php — Pensamiento y Lógica
 *
 * Esta categoría no es «matemática sin números» ni «juegos». Es la
 * habilidad debajo de todas las materias: observar, comparar, clasificar,
 * encontrar el patrón, deducir y decidir.
 *
 * La diferencia con «Juegos y Retos» es deliberada y conviene tenerla
 * clara al escribir contenido nuevo:
 *     Juegos y Retos      = el FORMATO (contrarreloj, ruleta, desafío)
 *     Pensamiento y Lógica = la HABILIDAD que se entrena
 * Una misma actividad puede ser las dos cosas; para eso están las
 * etiquetas, no para duplicar la actividad en dos categorías.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'pensamiento',
    'name'       => 'Pensamiento y Lógica',
    'tagline'    => 'Observar, encontrar el patrón y deducir la respuesta',
    'icon'       => '🧠',
    'color'      => '#7e57c2',
    'sort_order' => 11,
],

'bloques' => [
    ['slug' => 'observar-y-clasificar', 'name' => 'Observar y Clasificar', 'icon' => '🔍', 'sort_order' => 1,
     'description' => 'Mirar con atención, comparar y agrupar: la base de todo razonamiento.'],
    ['slug' => 'patrones-y-secuencias', 'name' => 'Patrones y Secuencias', 'icon' => '🔁', 'sort_order' => 2,
     'description' => 'Descubrir la regla que se repite y anticipar lo que sigue.'],
    ['slug' => 'retos-de-deduccion', 'name' => 'Retos de Deducción', 'icon' => '🕵️', 'sort_order' => 3,
     'description' => 'Códigos, acertijos y casos que se resuelven pensando.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE 1 · OBSERVAR Y CLASIFICAR (3 a 5 años)
// =====================================================================

[
    'slug'  => 'que-sigue',
    'title' => '¿Qué sigue?',
    'description' => 'Descubre la regla que se repite y adivina qué viene después.',
    'objective' => 'Reconocer y continuar patrones simples de repetición.',
    'icon' => '🔁', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 10, 'tags' => ['patrones', 'observacion', 'logica'],
    'estaciones' => [

        est('Patrones de dos', 'Se repiten de dos en dos', '🔴', 'opcion_multiple', [
            omp('¿Qué sigue?', ['🔴', '🔵', '🟡'], '🔴', '🔵 🔴 🔵 🔴 🔵 ❓', 'texto'),
            omp('¿Qué sigue?', ['⭐', '🌙', '☀️'], '⭐', '⭐ 🌙 ⭐ 🌙 ⭐ ❓', 'texto'),
            omp('¿Qué sigue?', ['🐶', '🐱', '🐭'], '🐱', '🐶 🐱 🐶 🐱 🐶 ❓', 'texto'),
            omp('¿Qué sigue?', ['🍎', '🍌', '🍇'], '🍎', '🍌 🍎 🍌 🍎 🍌 ❓', 'texto'),
            omp('¿Qué sigue?', ['🔺', '🟦', '⚫'], '🟦', '🔺 🟦 🔺 🟦 🔺 ❓', 'texto'),
        ]),

        est('Patrones de tres', 'Ahora la regla es más larga', '🔺', 'opcion_multiple', [
            omp('¿Qué sigue?', ['🔴', '🔵', '🟢'], '🔴', '🔴 🔵 🟢 🔴 🔵 🟢 ❓', 'texto'),
            omp('¿Qué sigue?', ['🐘', '🐁', '🦁'], '🐁', '🐘 🐘 🐁 🐘 🐘 ❓', 'texto'),
            omp('¿Qué sigue?', ['☀️', '☁️', '🌧️'], '☀️', '☀️ ☁️ 🌧️ ☀️ ☁️ 🌧️ ❓', 'texto'),
            omp('¿Qué sigue?', ['🟨', '🟩', '🟪'], '🟩', '🟨 🟩 🟩 🟨 🟩 ❓', 'texto'),
        ]),

        est('Números que siguen', 'Completa la serie de números', '🔢', 'secuencia_numerica', [
            serie([1, 2, null, 4, 5]),
            serie([2, 3, 4, null, 6]),
            serie([5, 6, null, 8, 9]),
            serie([null, 2, 3, 4, 5]),
            serie([6, 7, 8, 9, null]),
        ]),

        est('Ordena la historia', 'Pon los momentos en orden', '📖', 'ordenar_secuencia', [
            'title' => 'Ordena la historia de la semilla',
            'items' => ['🌰 Semilla', '🌱 Brote', '🪴 Planta', '🌳 Árbol'],
        ]),
    ],
],

[
    'slug'  => 'encuentra-el-diferente',
    'title' => 'Encuentra el diferente',
    'description' => 'Mira con atención: uno de ellos no encaja con los demás.',
    'objective' => 'Comparar elementos y detectar el que no comparte el atributo común.',
    'icon' => '🔍', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 10, 'tags' => ['observacion', 'atencion', 'clasificacion'],
    'estaciones' => [

        est('El intruso', '¿Cuál no es igual a los demás?', '🔍', 'opcion_multiple', [
            ompi('¿Cuál es diferente?', ['🍎', '🍎', '🍏', '🍎'], 2),
            ompi('¿Cuál es diferente?', ['🐶', '🐱', '🐶', '🐶'], 1),
            ompi('¿Cuál es diferente?', ['⭐', '⭐', '⭐', '🌟'], 3),
            ompi('¿Cuál es diferente?', ['🔵', '🔷', '🔵', '🔵'], 1),
            ompi('¿Cuál es diferente?', ['🚗', '🚗', '🚙', '🚗'], 2),
        ]),

        est('El que no pertenece', 'Uno no es del mismo grupo', '🗂️', 'opcion_multiple', [
            omp('¿Cuál NO es un animal?',   ['🐶', '🐱', '🌳', '🐦'], '🌳'),
            omp('¿Cuál NO es una fruta?',   ['🍎', '🍌', '🥕', '🍇'], '🥕'),
            omp('¿Cuál NO se come?',        ['🍞', '🧀', '👟', '🍎'], '👟'),
            omp('¿Cuál NO va en el agua?',  ['🐟', '🐬', '🐘', '🦈'], '🐘'),
            omp('¿Cuál NO vuela?',          ['🐦', '✈️', '🚗', '🦋'], '🚗'),
            omp('¿Cuál NO es un vehículo?', ['🚗', '🚌', '🍕', '🚲'], '🍕'),
        ]),

        est('Mira bien', 'Fíjate en el detalle pequeño', '👀', 'opcion_multiple', [
            ompi('¿Cuál es diferente?', ['😀', '😀', '😀', '😃'], 3),
            ompi('¿Cuál es diferente?', ['🌸', '🌺', '🌸', '🌸'], 1),
            ompi('¿Cuál es diferente?', ['🟥', '🟥', '🟧', '🟥'], 2),
            ompi('¿Cuál es diferente?', ['🎈', '🎈', '🎈', '🎀'], 3),
        ]),

        est('Memoria de atención', 'Encuentra las parejas', '🧠', 'memoria',
            ['🍎', '🐶', '⭐', '🚗', '🌳', '🎈']),
    ],
],

[
    'slug'  => 'agrupa-los-objetos',
    'title' => 'Agrupa los objetos',
    'description' => 'Todo se puede clasificar. Descubre por qué unos van juntos.',
    'objective' => 'Clasificar objetos según un criterio y explicar a qué grupo pertenecen.',
    'icon' => '🗂️', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 10, 'tags' => ['clasificacion', 'observacion', 'vocabulario'],
    'estaciones' => [

        est('Toca todas las frutas', 'Solo las frutas, ninguna más', '🍎', 'seleccion_imagenes',
            conTitulo('Toca todas las frutas', 'Deja fuera lo que no lo sea', [
                ['e' => '🍎', 'n' => 'Manzana',  'ok' => true],
                ['e' => '🍌', 'n' => 'Banano',   'ok' => true],
                ['e' => '🍇', 'n' => 'Uvas',     'ok' => true],
                ['e' => '🍓', 'n' => 'Fresa',    'ok' => true],
                ['e' => '🥕', 'n' => 'Zanahoria', 'ok' => false],
                ['e' => '🥦', 'n' => 'Brócoli',  'ok' => false],
                ['e' => '🍞', 'n' => 'Pan',      'ok' => false],
                ['e' => '🧀', 'n' => 'Queso',    'ok' => false],
            ])),

        est('Toca todos los vehículos', 'Cosas que sirven para viajar', '🚗', 'seleccion_imagenes',
            conTitulo('Toca todos los vehículos', 'Deja fuera lo que no lo sea', [
                ['e' => '🚗', 'n' => 'Carro',     'ok' => true],
                ['e' => '🚌', 'n' => 'Bus',       'ok' => true],
                ['e' => '✈️', 'n' => 'Avión',     'ok' => true],
                ['e' => '🚲', 'n' => 'Bicicleta', 'ok' => true],
                ['e' => '🛶', 'n' => 'Canoa',     'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',     'ok' => false],
                ['e' => '🏠', 'n' => 'Casa',      'ok' => false],
                ['e' => '🐶', 'n' => 'Perro',     'ok' => false],
            ])),

        est('¿A qué grupo pertenece?', 'Cada cosa tiene su familia', '🗂️', 'opcion_multiple', [
            omp('¿A qué grupo pertenece?', ['Animales', 'Frutas', 'Vehículos'], 'Animales', '🐘'),
            omp('¿A qué grupo pertenece?', ['Frutas', 'Ropa', 'Animales'], 'Frutas', '🍊'),
            omp('¿A qué grupo pertenece?', ['Ropa', 'Comida', 'Animales'], 'Ropa', '👕'),
            omp('¿A qué grupo pertenece?', ['Vehículos', 'Frutas', 'Ropa'], 'Vehículos', '🚂'),
            omp('¿A qué grupo pertenece?', ['Instrumentos', 'Animales', 'Comida'], 'Instrumentos', '🎸'),
            omp('¿A qué grupo pertenece?', ['Comida', 'Vehículos', 'Ropa'], 'Comida', '🍕'),
        ]),

        est('Grande, mediano y pequeño', 'Ordena por tamaño', '📏', 'ordenar_secuencia', [
            'title' => 'Ordena del más pequeño al más grande',
            'items' => ['🐜 Hormiga', '🐭 Ratón', '🐕 Perro', '🐴 Caballo', '🐘 Elefante'],
        ]),
    ],
],

[
    'slug'  => 'memoria-de-figuras',
    'title' => 'Memoria de figuras',
    'description' => 'Ejercita la memoria visual con tableros cada vez más grandes.',
    'objective' => 'Fortalecer la memoria de trabajo visual y la atención sostenida.',
    'icon' => '🧠', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 12, 'tags' => ['memoria', 'atencion', 'juego'],
    'estaciones' => [

        est('Nivel 1 · Formas', 'Cuatro parejas para empezar', '🔷', 'memoria',
            ['🔺', '🔵', '🟨', '⬛']),

        est('Nivel 2 · Animales', 'Ahora seis parejas', '🐾', 'memoria',
            ['🐶', '🐱', '🐰', '🐸', '🐵', '🦊']),

        est('Nivel 3 · Todo junto', 'Ocho parejas: el reto mayor', '🏆', 'memoria',
            ['⭐', '🌙', '☀️', '☁️', '🌈', '⚡', '❄️', '🔥']),

        est('Observa y responde', 'Cuenta con cuidado', '👀', 'opcion_multiple', [
            omp('¿Cuántas estrellas hay?',  [3, 4, 5, 6], 4, '⭐ ⭐ ⭐ ⭐', 'texto'),
            omp('¿Cuántos corazones hay?',  [4, 5, 6, 7], 6, '❤️ ❤️ ❤️ ❤️ ❤️ ❤️', 'texto'),
            omp('¿Cuántos círculos azules hay?', [2, 3, 4, 5], 3, '🔵 🔴 🔵 🟡 🔵 🔴', 'texto'),
            omp('¿Cuántos animales hay?',   [3, 4, 5, 6], 5, '🐶 🐱 🐰 🐸 🐵', 'texto'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE 2 · PATRONES Y SECUENCIAS (6 a 8 años)
// =====================================================================

[
    'slug'  => 'detective-de-patrones',
    'title' => 'Detective de patrones',
    'description' => 'Los patrones se esconden en números, figuras y letras. Encuéntralos.',
    'objective' => 'Identificar la regla de un patrón y aplicarla para continuar la serie.',
    'icon' => '🔎', 'nivel' => 'primaria-inicial', 'bloque' => 'patrones-y-secuencias',
    'duracion' => 12, 'tags' => ['patrones', 'logica', 'calculo'],
    'estaciones' => [

        est('Patrones de figuras', 'Descubre la regla y continúa', '🔷', 'opcion_multiple', [
            omp('¿Qué sigue?', ['🔺', '🔵', '🟩'], '🔺', '🔺 🔺 🔵 🔺 🔺 🔵 🔺 ❓', 'texto'),
            omp('¿Qué sigue?', ['⬛', '⬜', '🟥'], '⬜', '⬛ ⬜ ⬜ ⬛ ⬜ ⬜ ⬛ ❓', 'texto'),
            omp('¿Qué sigue?', ['🟢', '🟡', '🔴'], '🔴', '🔴 🟡 🟢 🔴 🟡 🟢 ❓', 'texto'),
            omp('¿Qué sigue?', ['🌕', '🌗', '🌑'], '🌑', '🌕 🌗 🌑 🌕 🌗 ❓', 'texto'),
            omp('¿Cuántas figuras se repiten en el patrón?', [2, 3, 4, 5], 3, '🔴 🟡 🟢 🔴 🟡 🟢', 'texto'),
        ]),

        /*
         * Estas series van de dos en dos o de cinco en cinco, y por eso
         * NO usan `secuencia_numerica`: ese minijuego arma sus opciones
         * sumando y restando 1 al número correcto, que es lo apropiado
         * para contar de uno en uno. En una serie de cinco en cinco
         * ofrecería 19, 20, 21 y 22, y entonces el niño no descubre la
         * regla: descarta lo que se ve raro. Aquí las opciones se
         * escriben a mano para que todas encajen con alguna regla falsa.
         */
        est('De dos en dos, de cinco en cinco', 'Descubre el salto de la serie', '🔢', 'opcion_multiple', [
            omp('¿Qué número falta?', [5, 6, 7, 8],       6,  '2 · 4 · ❓ · 8 · 10', 'texto'),
            omp('¿Qué número falta?', [16, 18, 20, 22],   20, '5 · 10 · 15 · ❓ · 25', 'texto'),
            omp('¿Qué número falta?', [25, 30, 35, 45],   30, '10 · 20 · ❓ · 40 · 50', 'texto'),
            omp('¿Qué número falta?', [10, 11, 12, 13],   12, '3 · 6 · 9 · ❓ · 15', 'texto'),
            omp('¿Qué número falta?', [6, 7, 8, 10],      7,  '1 · 3 · 5 · ❓ · 9', 'texto'),
            omp('¿De cuánto en cuánto sube esta serie?', [2, 3, 4, 5], 4, '4 · 8 · 12 · 16 · 20', 'texto'),
        ]),

        est('Series al revés', 'Ahora los números bajan', '⬇️', 'opcion_multiple', [
            omp('¿Qué número falta?', [15, 16, 17, 19],   16, '20 · 18 · ❓ · 14 · 12', 'texto'),
            omp('¿Qué número falta?', [15, 20, 25, 35],   20, '50 · 40 · 30 · ❓ · 10', 'texto'),
            omp('¿Qué número falta?', [60, 70, 80, 90],   80, '100 · 90 · ❓ · 70 · 60', 'texto'),
            omp('¿De cuánto en cuánto baja esta serie?', [2, 3, 5, 10], 3, '30 · 27 · 24 · 21 · 18', 'texto'),
            omp('¿Qué número sigue?', [0, 1, 2, 5],       0,  '12 · 9 · 6 · 3 · ❓', 'texto'),
        ]),

        est('Contar de uno en uno', 'Series seguidas, sin saltos', '1️⃣', 'secuencia_numerica', [
            serie([9, 8, null, 6, 5]),
            serie([12, 13, null, 15, 16]),
            serie([7, 6, 5, null, 3]),
            serie([21, 22, 23, null, 25]),
        ]),

        est('Patrones de letras', 'También las letras siguen reglas', '🔤', 'ordenar_secuencia', [
            'title' => 'Ordena las letras del abecedario',
            'items' => ['A', 'B', 'C', 'D', 'E', 'F', 'G'],
        ]),
    ],
],

[
    'slug'  => 'completa-la-secuencia',
    'title' => 'Completa la secuencia',
    'description' => 'Números, días y meses: todo tiene un orden que se puede completar.',
    'objective' => 'Completar series numéricas y temporales aplicando la regla que las rige.',
    'icon' => '➡️', 'nivel' => 'primaria-inicial', 'bloque' => 'patrones-y-secuencias',
    'duracion' => 12, 'tags' => ['secuencias', 'logica', 'calculo'],
    'estaciones' => [

        est('El número que falta', 'Completa cada serie', '🔢', 'secuencia_numerica', [
            serie([11, 12, null, 14, 15]),
            serie([25, 26, 27, null, 29]),
            serie([40, null, 42, 43, 44]),
            serie([97, 98, 99, null, 101]),
            serie([33, 34, 35, null, 37]),
        ]),

        est('Los días de la semana', 'Ordena la semana completa', '📅', 'ordenar_secuencia', [
            'title' => 'Ordena los días de la semana',
            'items' => ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'],
        ]),

        est('¿Qué viene después?', 'Piensa en el orden del tiempo', '⏰', 'opcion_multiple', [
            omp('Después del martes viene…',    ['Miércoles', 'Lunes', 'Viernes'], 'Miércoles', '📅'),
            omp('Antes del domingo está…',      ['Sábado', 'Lunes', 'Jueves'], 'Sábado', '📅'),
            omp('Después de marzo viene…',      ['Abril', 'Febrero', 'Mayo'], 'Abril', '🗓️'),
            omp('El primer mes del año es…',    ['Enero', 'Diciembre', 'Junio'], 'Enero', '🎆'),
            omp('Después del desayuno viene…',  ['El almuerzo', 'La cena', 'Dormir'], 'El almuerzo', '🍽️'),
            omp('Después de la primavera viene…', ['El verano', 'El invierno', 'El otoño'], 'El verano', '🌻'),
        ]),

        est('Los meses del año', 'Ordena los primeros seis meses', '🗓️', 'ordenar_secuencia', [
            'title' => 'Ordena los meses de enero a junio',
            'items' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
        ]),
    ],
],

[
    'slug'  => 'el-camino-correcto',
    'title' => 'El camino correcto',
    'description' => 'Dale instrucciones a un robot y comprueba si llega a su destino.',
    'objective' => 'Introducir el pensamiento computacional: secuenciar instrucciones y detectar errores.',
    'icon' => '🧭', 'nivel' => 'primaria-inicial', 'bloque' => 'patrones-y-secuencias',
    'duracion' => 12, 'tags' => ['logica', 'secuencias', 'tecnologia'],
    'estaciones' => [

        est('Instrucciones al robot', '¿Qué instrucción hace falta?', '🤖', 'opcion_multiple', [
            omp('El robot mira al frente. Para ir hacia atrás debe…', ['Girar dos veces y avanzar', 'Avanzar', 'Saltar'], 'Girar dos veces y avanzar', '🤖'),
            omp('«Avanza, avanza, gira, avanza». ¿Cuántas veces avanza en total?', [2, 3, 4, 1], 3, '🤖', 'emoji'),
            omp('Si el robot avanza y hay una pared, ¿qué pasa?', ['Choca', 'Vuela', 'Desaparece'], 'Choca', '🧱'),
            omp('Para repetir «avanza» cinco veces sin escribirlo cinco veces, uso…', ['Un bucle', 'Un dibujo', 'Un borrador'], 'Un bucle', '🔁'),
            omp('¿Qué instrucción sobra para llegar en línea recta?', ['Girar', 'Avanzar', 'Empezar'], 'Girar', '➡️'),
        ]),

        est('Pasos de un algoritmo', 'Un algoritmo es una receta de pasos', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para lavarse los dientes',
            'items' => [
                '1️⃣ Tomar el cepillo',
                '2️⃣ Poner la crema',
                '3️⃣ Cepillar los dientes',
                '4️⃣ Enjuagar la boca',
                '5️⃣ Guardar el cepillo',
            ],
        ]),

        est('¿Qué pasa si cambio el orden?', 'En un algoritmo el orden importa', '🔀', 'opcion_multiple', [
            omp('Si me pongo los zapatos antes que las medias…', ['Sale mal', 'Sale igual', 'Sale mejor'], 'Sale mal', '🧦'),
            omp('Si enciendo el horno después de sacar el pan…',  ['Sale mal', 'Sale igual', 'Da lo mismo'], 'Sale mal', '🍞'),
            omp('¿Qué es un algoritmo?',   ['Una lista de pasos en orden', 'Un dibujo', 'Un número'], 'Una lista de pasos en orden', '📋'),
            omp('Si un paso está mal, el resultado…', ['Puede fallar', 'Siempre es correcto', 'No cambia'], 'Puede fallar', '⚠️'),
        ]),

        est('La receta', 'Ordena los pasos de una receta', '🍳', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para preparar un huevo frito',
            'items' => [
                '🥘 Calentar la sartén',
                '🧈 Poner un poco de aceite',
                '🥚 Romper el huevo',
                '⏲️ Esperar a que se cocine',
                '🍽️ Servir en el plato',
            ],
        ]),
    ],
],


// =====================================================================
//  BLOQUE 3 · RETOS DE DEDUCCIÓN (9 a 12 años)
// =====================================================================

[
    'slug'  => 'codigo-secreto',
    'title' => 'Código secreto',
    'description' => 'Mensajes cifrados que solo se leen si descubres la regla.',
    'objective' => 'Aplicar reglas de sustitución y desplazamiento para descifrar mensajes.',
    'icon' => '🔐', 'nivel' => 'primaria-media', 'bloque' => 'retos-de-deduccion',
    'duracion' => 15, 'tags' => ['logica', 'deduccion', 'reto'],
    'estaciones' => [

        est('El cifrado del número', 'Cada letra es un número: A=1, B=2, C=3…', '🔢', 'opcion_multiple', [
            omp('Si A=1, B=2, C=3… ¿qué letra es el 5?',  ['E', 'D', 'F'], 'E', '🔢'),
            omp('¿Qué palabra es 3-1-19-1?',              ['CASA', 'MESA', 'ROSA'], 'CASA', '🏠'),
            omp('¿Qué palabra es 19-15-12?',              ['SOL', 'MAR', 'PAN'], 'SOL', '☀️'),
            omp('¿Qué número corresponde a la letra M?',  [11, 12, 13, 14], 13),
            omp('¿Qué palabra es 13-1-18?',               ['MAR', 'SUR', 'LUZ'], 'MAR', '🌊'),
        ]),

        est('El cifrado del salto', 'Cada letra se corre un lugar en el abecedario', '➡️', 'opcion_multiple', [
            omp('Si cada letra avanza 1 lugar, «A» se escribe…', ['B', 'C', 'Z'], 'B', '🔤'),
            omp('Con ese código, «SOL» se escribe…',   ['TPM', 'RNK', 'SOL'], 'TPM', '☀️'),
            omp('Con ese código, «UBS» significa…',    ['TAR', 'VCT', 'SOL'], 'TAR', '🔤'),
            omp('Si cada letra retrocede 1 lugar, «C» se escribe…', ['B', 'D', 'A'], 'B', '🔤'),
        ]),

        est('Palabras escondidas', 'Encuentra las palabras del código', '🔍', 'sopa_letras',
            sopa(['CLAVE', 'PISTA', 'CODIGO', 'SECRETO', 'ENIGMA'], 11)),

        est('Descifra el mensaje', 'El reto final del código', '🏆', 'desafio_final', [
            reto('En el código A=1, B=2… la palabra 12-21-26 es:', ['LUZ', 'PAZ', 'VOZ'], 'LUZ'),
            reto('«El número secreto es el doble de 7 menos 4». ¿Cuál es?', [10, 12, 14], 10),
            reto('Una caja se abre con 3 números que suman 12 y son consecutivos. Son:', ['3, 4 y 5', '2, 4 y 6', '1, 5 y 6'], '3, 4 y 5'),
            reto('Si en un código «SÍ» es «NO» y «NO» es «SÍ», ¿qué responde alguien que sí tiene hambre?', ['NO', 'SÍ', 'Nada'], 'NO'),
        ]),
    ],
],

[
    'slug'  => 'quien-miente',
    'title' => '¿Quién miente?',
    'description' => 'Acertijos donde solo una respuesta puede ser verdad al mismo tiempo.',
    'objective' => 'Razonar por eliminación y detectar contradicciones lógicas.',
    'icon' => '🤥', 'nivel' => 'primaria-media', 'bloque' => 'retos-de-deduccion',
    'duracion' => 15, 'tags' => ['deduccion', 'logica', 'reto'],
    'estaciones' => [

        est('Solo uno dice la verdad', 'Lee con calma y elimina', '🗣️', 'opcion_multiple', [
            omp('Ana dice: «El dulce está en la caja roja». Beto dice: «No está en la roja». Solo uno dice la verdad. Si abres la roja y está vacía, ¿quién mintió?',
                ['Ana', 'Beto', 'Los dos'], 'Ana', '🎁'),
            omp('Si «todos los perros son animales», entonces…',
                ['Algunos animales son perros', 'Todos los animales son perros', 'Ningún animal es perro'],
                'Algunos animales son perros', '🐶'),
            omp('Si «ningún gato vuela» y Michi es un gato, entonces Michi…',
                ['No vuela', 'Vuela', 'A veces vuela'], 'No vuela', '🐱'),
            omp('Si «todos los cuadrados tienen 4 lados» y esta figura tiene 3 lados, entonces…',
                ['No es un cuadrado', 'Es un cuadrado', 'Podría ser un cuadrado'], 'No es un cuadrado', '🔺'),
        ]),

        est('Deduce quién es quién', 'Usa todas las pistas', '🕵️', 'desafio_final', [
            reto('Ana, Beto y Caro tienen un perro, un gato y un pez. Ana no tiene pelo de animal en la ropa. Beto tiene un perro. ¿Qué tiene Ana?',
                 ['El pez', 'El gato', 'El perro'], 'El pez'),
            reto('Tres cajas: roja, azul y verde. El premio no está en la roja ni en la verde. ¿Dónde está?',
                 ['En la azul', 'En la roja', 'En la verde'], 'En la azul'),
            reto('María es más alta que Juan. Juan es más alto que Pedro. ¿Quién es el más bajo?',
                 ['Pedro', 'Juan', 'María'], 'Pedro'),
            reto('Si hoy es martes, ¿qué día será dentro de 3 días?',
                 ['Viernes', 'Jueves', 'Sábado'], 'Viernes'),
        ]),

        est('Verdadero, falso o no se sabe', 'A veces la respuesta es «no alcanza la información»', '⚖️', 'opcion_multiple', [
            omp('«Todos los pájaros de este parque son negros». Veo un pájaro blanco en el parque. La frase es…',
                ['Falsa', 'Verdadera', 'No se sabe'], 'Falsa', '🐦'),
            omp('«Algunos niños del salón usan gafas». Pedro es del salón. ¿Pedro usa gafas?',
                ['No se sabe', 'Sí', 'No'], 'No se sabe', '👓'),
            omp('«Si llueve, la cancha se moja». La cancha está mojada. ¿Llovió?',
                ['No se sabe: pudo mojarse de otra forma', 'Sí, seguro', 'No'], 'No se sabe: pudo mojarse de otra forma', '🌧️'),
            omp('«Ningún estudiante de este curso tiene 15 años». Ana tiene 15 años. Entonces Ana…',
                ['No es de este curso', 'Es de este curso', 'No se sabe'], 'No es de este curso', '🎓'),
        ]),

        est('Reto de lógica', 'El desafío mayor', '🏆', 'desafio_final', [
            reto('Un caracol sube 3 metros de día y baja 2 de noche. El pozo tiene 5 metros. ¿En cuántos días sale?',
                 [3, 4, 5], 3),
            reto('Tienes 2 monedas que suman 30 pesos y una de ellas no es de 20. ¿Cuáles son?',
                 ['Una de 20 y una de 10', 'Dos de 15', 'Una de 25 y una de 5'], 'Una de 20 y una de 10'),
            reto('En una carrera adelantas al segundo. ¿En qué posición quedas?',
                 ['Segundo', 'Primero', 'Tercero'], 'Segundo'),
            reto('Si 5 máquinas hacen 5 piezas en 5 minutos, ¿cuánto tardan 10 máquinas en hacer 10 piezas?',
                 ['5 minutos', '10 minutos', '2 minutos'], '5 minutos'),
        ]),
    ],
],

[
    'slug'  => 'caso-cerrado',
    'title' => 'Caso cerrado',
    'description' => 'Un misterio con pistas repartidas. Léelo todo y resuélvelo.',
    'objective' => 'Integrar información de varias fuentes para llegar a una conclusión razonada.',
    'icon' => '🗂️', 'nivel' => 'primaria-media', 'bloque' => 'retos-de-deduccion',
    'duracion' => 15, 'tags' => ['deduccion', 'lectura', 'reto'],
    'estaciones' => [

        est('El caso de la biblioteca', 'Lee con atención: cada frase es una pista', '📚', 'cuento', [
            'slides' => [
                ['img' => '📚', 'text' => 'En la biblioteca del colegio desapareció el libro más grande: el atlas del mundo.'],
                ['img' => '🕐', 'text' => 'El atlas estaba a la una de la tarde. A las dos ya no estaba.'],
                ['img' => '🧒', 'text' => 'Entre la una y las dos entraron tres estudiantes: Ana, Beto y Caro.'],
                ['img' => '🎒', 'text' => 'Ana entró con las manos vacías y salió con las manos vacías.'],
                ['img' => '📖', 'text' => 'Beto llevaba un morral pequeño, del tamaño de un cuaderno. El atlas no cabría ahí.'],
                ['img' => '🧳', 'text' => 'Caro llevaba un bolso grande y salió apurada sin despedirse.'],
            ],
            'qs' => [
                reto('¿A qué hora se notó la desaparición?', ['A las dos', 'A la una', 'A las tres'], 'A las dos'),
                reto('¿Por qué Ana queda descartada?', ['Salió con las manos vacías', 'Llegó tarde', 'No sabe leer'], 'Salió con las manos vacías'),
                reto('¿Por qué Beto queda descartado?', ['Su morral era demasiado pequeño', 'No entró', 'Estaba enfermo'], 'Su morral era demasiado pequeño'),
                reto('¿Quién pudo llevarse el atlas?', ['Caro', 'Ana', 'Beto'], 'Caro'),
                reto('¿Qué pista es la más importante contra Caro?', ['Llevaba un bolso grande', 'Salió sin despedirse', 'Llegó primero'], 'Llevaba un bolso grande'),
            ],
        ]),

        est('Analiza las pistas', '¿Qué se puede afirmar de verdad?', '🔍', 'opcion_multiple', [
            omp('Salir apurado, ¿demuestra que alguien es culpable?', ['No, solo es un indicio', 'Sí, siempre', 'Nunca significa nada'], 'No, solo es un indicio', '🏃'),
            omp('Una pista que descarta a un sospechoso sirve para…', ['Reducir las posibilidades', 'Nada', 'Acusar a otro sin más'], 'Reducir las posibilidades', '➖'),
            omp('Si dos testigos dicen cosas distintas, lo mejor es…', ['Buscar una tercera prueba', 'Creer al primero', 'Cerrar el caso'], 'Buscar una tercera prueba', '⚖️'),
            omp('Una conclusión razonada se apoya en…', ['Las pruebas', 'La simpatía', 'La suerte'], 'Las pruebas', '📐'),
        ]),

        est('Cierra el caso', 'Cuatro casos nuevos, cuatro deducciones', '🏆', 'desafio_final', [
            reto('Alguien pisó el pastel. Hay huellas de barro que van del jardín a la cocina. ¿Quién estuvo en el jardín?',
                 ['La persona que pisó el pastel', 'Nadie', 'Todos'], 'La persona que pisó el pastel'),
            reto('La ventana está rota con vidrios hacia adentro. El golpe vino…',
                 ['De afuera', 'De adentro', 'No se puede saber'], 'De afuera'),
            reto('El reloj de la sala se detuvo a las 3:15 y estaba en el suelo. Eso sugiere que…',
                 ['Algo pasó cerca de las 3:15', 'El reloj era viejo', 'Nadie estuvo allí'], 'Algo pasó cerca de las 3:15'),
            reto('Tres tazas de café tibias sobre la mesa indican que…',
                 ['Había tres personas hace poco', 'Nadie vino hoy', 'Hubo una sola persona'], 'Había tres personas hace poco'),
        ]),
    ],
],

],
];
