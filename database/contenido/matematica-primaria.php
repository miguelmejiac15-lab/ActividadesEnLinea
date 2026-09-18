<?php
/**
 * matematica-primaria.php — Matemáticas de 1.º a 6.º
 *
 * Escrito sobre las mallas curriculares de primaria del Colegio Alemán
 * (Klasse 1 a 6), que están en `MallasPrimaria/`. No es una traducción del
 * documento: es lo que de ese documento se puede jugar.
 *
 * Los seis bloques nuevos siguen los ejes que usan las mallas —pensamiento
 * numérico-variacional, métrico-geométrico y aleatorio— pero con los
 * nombres que un niño y su familia reconocen. «Pensamiento aleatorio y
 * sistemas de datos» es correcto y no lo busca nadie; «Datos y Azar» sí.
 *
 * La progresión respeta la de la malla, que es acumulativa y no arbitraria:
 * el campo del 20 (K1) antes que el del 100 (K2), el del millar (K3) antes
 * que el del millón (K4), y la fracción (K4) antes que el decimal (K6).
 * Saltarse un peldaño aquí no adelanta a nadie: deja al niño resolviendo
 * con los dedos algo que ya debería resolver de memoria.
 */

declare(strict_types=1);

return [

/*
 * Las mallas llegan a Klasse 6 (11-12 años) y los cuatro niveles del
 * catálogo se detenían en los 10. Sin este nivel, todo lo de 5.º y 6.º
 * caería en «Tercero a quinto» y el filtro por edad estaría mintiéndole
 * a la familia que busca algo para su hijo de once años.
 */
'niveles' => [
    ['slug' => 'primaria-superior', 'name' => 'Quinto y sexto', 'min_age' => 11, 'max_age' => 12, 'sort_order' => 4],

    /*
     * `primaria-media` se llamaba «Tercero a quinto». Con el nivel nuevo al
     * lado, la palabra «quinto» aparecía en dos opciones seguidas del mismo
     * desplegable y quien elige por su hijo no sabía cuál marcar. Solo
     * cambia el NOMBRE: el rango 8-10 se queda como está, porque es lo que
     * filtra de verdad y hay sesenta actividades catalogadas con él. Los
     * diez años caen a propósito en las dos franjas: son la frontera.
     */
    ['slug' => 'primaria-media', 'name' => 'Tercero y cuarto', 'min_age' => 8, 'max_age' => 10, 'sort_order' => 3],

    // Se redeclara solo para empujarlo al final del desplegable.
    ['slug' => 'todas-las-edades', 'name' => 'Todas las edades', 'min_age' => 3, 'max_age' => 12, 'sort_order' => 5],
],

'categoria' => [
    'slug'       => 'matematica',
    'name'       => 'Matemática',
    'tagline'    => 'Números, formas y datos, de contar hasta razonar',
    'icon'       => '🔢',
    'color'      => '#ef6c00',
    'sort_order' => 2,
],

'bloques' => [
    ['slug' => 'valor-posicional', 'name' => 'Valor Posicional', 'icon' => '🔟', 'sort_order' => 4,
     'description' => 'Del campo del veinte al millón: qué vale cada cifra según dónde esté.'],
    ['slug' => 'calculo-mental', 'name' => 'Cálculo Mental', 'icon' => '🧮', 'sort_order' => 5,
     'description' => 'Sumar, restar, multiplicar y dividir con estrategias, no contando con los dedos.'],
    ['slug' => 'fracciones-y-decimales', 'name' => 'Fracciones y Decimales', 'icon' => '🍕', 'sort_order' => 6,
     'description' => 'Partes de un todo, equivalencias y la coma decimal.'],
    ['slug' => 'geometria-y-formas', 'name' => 'Geometría y Formas', 'icon' => '📐', 'sort_order' => 7,
     'description' => 'Figuras, cuerpos, ángulos, simetría y movimientos en el plano.'],
    ['slug' => 'medida-y-magnitudes', 'name' => 'Medida y Magnitudes', 'icon' => '📏', 'sort_order' => 8,
     'description' => 'Longitud, masa, capacidad, tiempo, dinero, perímetro, área y volumen.'],
    ['slug' => 'datos-y-azar', 'name' => 'Datos y Azar', 'icon' => '📊', 'sort_order' => 9,
     'description' => 'Tablas, gráficas, promedios y qué tan probable es que algo pase.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · VALOR POSICIONAL
// =====================================================================

[
    'slug'  => 'numeros-hasta-el-veinte',
    'title' => 'Números hasta el veinte',
    'description' => 'Contar, comparar y colocar los números del 1 al 20 en la recta numérica.',
    'objective' => 'Reconocer el significado de los números hasta el veinte en contextos de conteo, comparación y orden.',
    'icon' => '2️⃣', 'nivel' => 'primaria-inicial', 'bloque' => 'valor-posicional',
    'duracion' => 12, 'tags' => ['calculo', 'observacion', 'juego'],
    'estaciones' => [

        est('Cuenta y asocia', '¿Cuántos hay en cada grupo?', '👀', 'operacion', [
            ['op' => 'contar', 'a' => 7,  'resultado' => 7],
            ['op' => 'contar', 'a' => 12, 'resultado' => 12],
            ['op' => 'contar', 'a' => 15, 'resultado' => 15],
            ['op' => 'contar', 'a' => 9,  'resultado' => 9],
            ['op' => 'contar', 'a' => 18, 'resultado' => 18],
        ]),

        est('La recta numérica', 'Descubre el número que falta', '📍', 'secuencia_numerica', [
            serie([1, 2, null, 4, 5]),
            serie([10, 11, 12, null, 14]),
            serie([15, 16, null, 18, 19]),
            serie([2, 4, 6, null, 10]),
            serie([5, 10, null, 20]),
        ]),

        est('Más y menos', 'Compara dos cantidades', '⚖️', 'opcion_multiple', [
            omp('¿Qué número es mayor?',            ['12', '9', 'Los dos igual'], '12'),
            omp('¿Qué número es menor?',            ['7', '14', 'Los dos igual'], '7'),
            omp('¿Qué número va justo después del 13?', ['14', '12', '15'], '14'),
            omp('¿Qué número va justo antes del 20?',   ['19', '21', '18'], '19'),
            omp('¿Cuál está entre el 6 y el 8?',    ['7', '9', '5'], '7'),
        ]),

        /*
         * «El poder del 5» es de la malla de K1: los números del 6 al 10 se
         * leen como cinco y algo más, y eso es lo que después permite
         * calcular sin contar de uno en uno.
         */
        est('El poder del cinco', 'Cada número, como cinco y algo más', '🖐️', 'opcion_multiple', [
            omp('6 es 5 y…',  ['1', '2', '3'], '1'),
            omp('7 es 5 y…',  ['2', '1', '4'], '2'),
            omp('8 es 5 y…',  ['3', '2', '5'], '3'),
            omp('9 es 5 y…',  ['4', '3', '1'], '4'),
            omp('10 es 5 y…', ['5', '4', '2'], '5'),
        ]),

        est('Ordena los números', 'Del más pequeño al más grande', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena de menor a mayor',
            'items' => ['3', '7', '11', '16', '20'],
        ]),
    ],
],

[
    'slug'  => 'decenas-y-unidades',
    'title' => 'Decenas y unidades',
    'description' => 'Cada número tiene decenas y unidades. Descúbrelas y arma cantidades hasta el 100.',
    'objective' => 'Construir cantidades hasta cien reconociendo el valor de la cifra según su posición.',
    'icon' => '📦', 'nivel' => 'primaria-inicial', 'bloque' => 'valor-posicional',
    'duracion' => 12, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('¿Cuántas decenas?', 'Descompón cada número', '📦', 'opcion_multiple', [
            omp('¿Cuántas decenas tiene 34?',  ['3', '4', '34'], '3'),
            omp('¿Cuántas unidades tiene 34?', ['4', '3', '30'], '4'),
            omp('¿Cuántas decenas tiene 70?',  ['7', '0', '70'], '7'),
            omp('¿Cuántas unidades tiene 70?', ['0', '7', '10'], '0'),
            omp('¿Cuántas decenas tiene 91?',  ['9', '1', '19'], '9'),
        ]),

        est('Arma el número', '¿Qué número se forma?', '🧱', 'opcion_multiple', [
            omp('5 decenas y 2 unidades son…', ['52', '25', '502'], '52'),
            omp('8 decenas y 0 unidades son…', ['80', '8', '18'], '80'),
            omp('1 decena y 9 unidades son…',  ['19', '91', '109'], '19'),
            omp('6 decenas y 7 unidades son…', ['67', '76', '607'], '67'),
            omp('9 decenas y 9 unidades son…', ['99', '90', '909'], '99'),
        ]),

        est('El vecino de al lado', 'El anterior y el siguiente', '↔️', 'secuencia_numerica', [
            serie([28, 29, null, 31]),
            serie([56, null, 58, 59]),
            serie([70, null, 72, 73]),
            serie([10, 20, 30, null, 50]),
            serie([25, 30, null, 40, 45]),
        ]),

        est('¿Cuál es mayor?', 'Compara números de dos cifras', '⚖️', 'opcion_multiple', [
            omp('¿Cuál es mayor, 45 o 54?',  ['54', '45', 'Iguales'], '54'),
            omp('¿Cuál es menor, 38 o 83?',  ['38', '83', 'Iguales'], '38'),
            omp('¿Cuál es mayor, 60 o 59?',  ['60', '59', 'Iguales'], '60'),
            omp('¿Cuál es menor, 100 o 99?', ['99', '100', 'Iguales'], '99'),
            omp('¿Cuál está más cerca de 50, el 47 o el 62?', ['47', '62', 'Los dos igual'], '47'),
        ]),

        est('Escribe la cantidad', 'Escucha y escribe el número en letras', '⌨️', 'teclado',
            ['VEINTE', 'TREINTA', 'CINCUENTA', 'SETENTA', 'CIEN']),
    ],
],

[
    'slug'  => 'orientacion-en-el-millar',
    'title' => 'Orientación en el millar',
    'description' => 'Unidades, decenas y centenas hasta el 1000: leer, escribir y descomponer cantidades.',
    'objective' => 'Leer, escribir, descomponer y ubicar en la recta numérica cantidades hasta el millar.',
    'icon' => '🏔️', 'nivel' => 'primaria-media', 'bloque' => 'valor-posicional',
    'duracion' => 14, 'tags' => ['calculo', 'logica', 'observacion'],
    'estaciones' => [

        est('Unidades, decenas y centenas', '¿Qué vale cada cifra?', '🗂️', 'opcion_multiple', [
            omp('En 348, ¿cuánto vale el 3?',   ['300', '30', '3'], '300'),
            omp('En 348, ¿cuánto vale el 4?',   ['40', '4', '400'], '40'),
            omp('En 705, ¿cuántas decenas hay?', ['0', '7', '5'], '0'),
            omp('En 962, ¿cuánto vale el 9?',   ['900', '90', '9'], '900'),
            omp('¿Qué número tiene 6 centenas, 0 decenas y 3 unidades?', ['603', '630', '63'], '603'),
        ]),

        est('Descompón la cantidad', 'Rompe el número en sus partes', '🧩', 'opcion_multiple', [
            omp('427 = 400 + 20 + …',   ['7', '27', '70'], '7'),
            omp('580 = 500 + … + 0',    ['80', '8', '58'], '80'),
            omp('906 = … + 0 + 6',      ['900', '90', '96'], '900'),
            omp('¿Cuánto es 300 + 40 + 5?', ['345', '3405', '354'], '345'),
            omp('¿Cuánto es 700 + 0 + 9?',  ['709', '79', '790'], '709'),
        ]),

        /*
         * «Nachbarzehner» y «Nachbarhunderter» de la malla: la decena y la
         * centena vecinas. Es lo que hace posible estimar antes de calcular.
         */
        est('La decena y la centena vecinas', '¿Entre qué números está?', '📍', 'opcion_multiple', [
            omp('¿Cuál es la decena más cercana a 47?',  ['50', '40', '45'], '50'),
            omp('¿Cuál es la decena más cercana a 62?',  ['60', '70', '65'], '60'),
            omp('¿Cuál es la centena más cercana a 380?', ['400', '300', '350'], '400'),
            omp('¿Cuál es la centena más cercana a 219?', ['200', '300', '250'], '200'),
            omp('¿Entre qué centenas está el 640?',      ['600 y 700', '500 y 600', '700 y 800'], '600 y 700'),
        ]),

        est('De cien en cien', 'Continúa la serie', '➡️', 'secuencia_numerica', [
            serie([100, 200, null, 400, 500]),
            serie([250, 260, 270, null, 290]),
            serie([500, 505, null, 515]),
            serie([1000, 900, null, 700, 600]),
            serie([125, 150, 175, null, 225]),
        ]),

        est('Desafío del millar', 'Cinco preguntas para cerrar', '🏆', 'desafio_final', [
            reto('¿Cuántas unidades tiene una centena?',   ['100', '10', '1000'], '100'),
            reto('¿Cuántas decenas tiene una centena?',    ['10', '100', '1'], '10'),
            reto('¿Cuál es el número mayor de tres cifras?', ['999', '900', '1000'], '999'),
            reto('¿Cuál es el número menor de tres cifras?', ['100', '111', '001'], '100'),
            reto('¿Qué número sigue después del 999?',     ['1000', '9910', '100'], '1000'),
        ]),
    ],
],

[
    'slug'  => 'el-campo-del-millon',
    'title' => 'El campo del millón',
    'description' => 'Unidades de mil, decenas de mil y el millón: leer y comparar números grandes.',
    'objective' => 'Identificar el valor posicional de cada dígito en números hasta el millón y compararlos.',
    'icon' => '💫', 'nivel' => 'primaria-media', 'bloque' => 'valor-posicional',
    'duracion' => 14, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('Tabla de posiciones', '¿En qué lugar está cada cifra?', '🗄️', 'opcion_multiple', [
            omp('En 45.320, ¿cuánto vale el 4?',   ['40.000', '4.000', '400'], '40.000'),
            omp('En 8.607, ¿cuánto vale el 6?',    ['600', '60', '6.000'], '600'),
            omp('¿Cuántos ceros tiene un millón?', ['6', '5', '7'], '6'),
            omp('En 130.000, ¿cuántas unidades de mil hay?', ['130', '13', '1.300'], '130'),
            omp('¿Cuál de estos es mayor?',        ['99.999', '9.999', '90.099'], '99.999'),
        ]),

        est('Leer números grandes', 'Empareja el número con su nombre', '🔗', 'emparejar', [
            ['e' => '1️⃣', 'w' => 'Mil'],
            ['e' => '🔟', 'w' => 'Diez mil'],
            ['e' => '💯', 'w' => 'Cien mil'],
            ['e' => '💫', 'w' => 'Un millón'],
            ['e' => '📦', 'w' => 'Cien'],
            ['e' => '🎯', 'w' => 'Diez'],
        ]),

        est('Redondear', 'Aproxima a la unidad que se pide', '🎯', 'opcion_multiple', [
            omp('Redondea 4.732 al millar más cercano',   ['5.000', '4.000', '4.700'], '5.000'),
            omp('Redondea 2.180 al millar más cercano',   ['2.000', '3.000', '2.200'], '2.000'),
            omp('Redondea 18.500 a la decena de mil',     ['20.000', '18.000', '10.000'], '20.000'),
            omp('Redondea 649 a la centena más cercana',  ['600', '700', '650'], '600'),
            omp('Redondea 951 al millar más cercano',     ['1.000', '900', '950'], '1.000'),
        ]),

        est('De mil en mil', 'Completa las series grandes', '📈', 'secuencia_numerica', [
            serie([1000, 2000, null, 4000, 5000]),
            serie([12000, 12500, null, 13500]),
            serie([10000, null, 30000, 40000]),
            serie([100000, 200000, 300000, null, 500000]),
            serie([9800, 9850, 9900, null, 10000]),
        ]),

        est('Desafío del millón', 'El último reto', '🏆', 'desafio_final', [
            reto('¿Cuántos miles tiene un millón?',        ['1.000', '100', '10.000'], '1.000'),
            reto('¿Cuál es el número mayor de cinco cifras?', ['99.999', '90.000', '100.000'], '99.999'),
            reto('¿Qué número es 10 veces mayor que 4.500?', ['45.000', '450', '450.000'], '45.000'),
            reto('¿Cuál está más cerca del millón?',       ['998.000', '900.000', '100.000'], '998.000'),
            reto('¿Cuántas centenas hay en 3.400?',        ['34', '3', '340'], '34'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · CÁLCULO MENTAL
// =====================================================================

[
    'slug'  => 'sumas-y-restas-hasta-veinte',
    'title' => 'Sumas y restas hasta veinte',
    'description' => 'Calcular sin contar con los dedos: estrategias para sumar y restar en el campo del veinte.',
    'objective' => 'Resolver sumas y restas hasta el veinte usando estrategias de cálculo mental.',
    'icon' => '➕', 'nivel' => 'primaria-inicial', 'bloque' => 'calculo-mental',
    'duracion' => 12, 'tags' => ['calculo', 'atencion'],
    'estaciones' => [

        est('Sumar hasta diez', 'Empieza por lo fácil', '🍎', 'operacion', [
            ['op' => 'suma', 'a' => 3, 'b' => 4, 'resultado' => 7],
            ['op' => 'suma', 'a' => 5, 'b' => 2, 'resultado' => 7],
            ['op' => 'suma', 'a' => 6, 'b' => 3, 'resultado' => 9],
            ['op' => 'suma', 'a' => 4, 'b' => 4, 'resultado' => 8],
            ['op' => 'suma', 'a' => 2, 'b' => 8, 'resultado' => 10],
        ]),

        est('Pasar de diez', 'Sumas que cruzan la decena', '🔟', 'operacion', [
            ['op' => 'suma', 'a' => 8, 'b' => 5, 'resultado' => 13],
            ['op' => 'suma', 'a' => 7, 'b' => 6, 'resultado' => 13],
            ['op' => 'suma', 'a' => 9, 'b' => 4, 'resultado' => 13],
            ['op' => 'suma', 'a' => 6, 'b' => 8, 'resultado' => 14],
            ['op' => 'suma', 'a' => 9, 'b' => 9, 'resultado' => 18],
        ]),

        est('Restar', 'Quita y cuenta lo que queda', '🎈', 'operacion', [
            ['op' => 'resta', 'a' => 9,  'b' => 3, 'resultado' => 6],
            ['op' => 'resta', 'a' => 12, 'b' => 4, 'resultado' => 8],
            ['op' => 'resta', 'a' => 15, 'b' => 7, 'resultado' => 8],
            ['op' => 'resta', 'a' => 18, 'b' => 9, 'resultado' => 9],
            ['op' => 'resta', 'a' => 20, 'b' => 6, 'resultado' => 14],
        ]),

        /*
         * Los «muros de cálculo» (Rechenmauern) están en la malla de K1 y
         * K2: cada ladrillo es la suma de los dos de abajo. Enseñan a
         * componer y descomponer sin decirlo, y se resuelven en los dos
         * sentidos, que es lo que después será una ecuación.
         */
        est('Muros de cálculo', 'Cada ladrillo es la suma de los dos de abajo', '🧱', 'opcion_multiple', [
            omp('Abajo hay 3 y 4. ¿Qué ladrillo va encima?',  ['7', '12', '1'], '7'),
            omp('Abajo hay 6 y 5. ¿Qué ladrillo va encima?',  ['11', '30', '1'], '11'),
            omp('Encima hay 10 y abajo un 4. ¿Cuál es el otro?', ['6', '14', '40'], '6'),
            omp('Encima hay 15 y abajo un 9. ¿Cuál es el otro?', ['6', '24', '7'], '6'),
            omp('Abajo hay 8 y 8. ¿Qué ladrillo va encima?',  ['16', '64', '0'], '16'),
        ]),

        est('Igualdades', 'Las dos partes valen lo mismo', '⚖️', 'opcion_multiple', [
            omp('10 + 3 = 20 − …',  ['7', '13', '10'], '7'),
            omp('8 + 4 = 6 + …',    ['6', '2', '12'], '6'),
            omp('15 − 5 = 4 + …',   ['6', '10', '14'], '6'),
            omp('9 + 9 = 20 − …',   ['2', '18', '1'], '2'),
            omp('7 + 7 = 7 × …',    ['2', '14', '1'], '2'),
        ]),
    ],
],

[
    'slug'  => 'sumar-y-restar-por-escrito',
    'title' => 'Sumar y restar por escrito',
    'description' => 'El algoritmo escrito con llevadas y prestadas, en el campo del millar.',
    'objective' => 'Aplicar la adición y sustracción por escrito resolviendo problemas del campo del millar.',
    'icon' => '📝', 'nivel' => 'primaria-media', 'bloque' => 'calculo-mental',
    'duracion' => 14, 'tags' => ['calculo', 'atencion'],
    'estaciones' => [

        est('Sumar con llevadas', 'Cuando la columna pasa de diez', '⬆️', 'operacion', [
            ['op' => 'suma', 'a' => 47,  'b' => 38,  'resultado' => 85],
            ['op' => 'suma', 'a' => 156, 'b' => 27,  'resultado' => 183],
            ['op' => 'suma', 'a' => 285, 'b' => 146, 'resultado' => 431],
            ['op' => 'suma', 'a' => 399, 'b' => 101, 'resultado' => 500],
            ['op' => 'suma', 'a' => 634, 'b' => 288, 'resultado' => 922],
        ]),

        est('Restar con prestadas', 'Cuando arriba hay menos que abajo', '⬇️', 'operacion', [
            ['op' => 'resta', 'a' => 82,  'b' => 37,  'resultado' => 45],
            ['op' => 'resta', 'a' => 150, 'b' => 76,  'resultado' => 74],
            ['op' => 'resta', 'a' => 403, 'b' => 128, 'resultado' => 275],
            ['op' => 'resta', 'a' => 700, 'b' => 245, 'resultado' => 455],
            ['op' => 'resta', 'a' => 916, 'b' => 429, 'resultado' => 487],
        ]),

        /*
         * La malla de K3 pide «reconocer palabras claves que ayudan a
         * interpretar y solucionar problemas». Esta estación entrena eso y
         * no la cuenta: el error de los niños casi nunca está en el cálculo,
         * está en decidir si suman o restan.
         */
        est('¿Sumo o resto?', 'Lee bien antes de calcular', '🔍', 'opcion_multiple', [
            omp('Tenía 45 figuritas y me regalan 20. ¿Qué hago?',      ['Sumar', 'Restar', 'Multiplicar'], 'Sumar'),
            omp('Había 80 galletas y se comieron 25. ¿Qué hago?',      ['Restar', 'Sumar', 'Dividir'], 'Restar'),
            omp('¿Cuántos años más tiene un niño de 12 que uno de 8?', ['Restar', 'Sumar', 'Multiplicar'], 'Restar'),
            omp('En el bus iban 30 y suben 14 más. ¿Cuántos van?',     ['Sumar', 'Restar', 'Dividir'], 'Sumar'),
            omp('Un libro tiene 120 páginas y llevo 45. ¿Cuántas me faltan?', ['Restar', 'Sumar', 'Multiplicar'], 'Restar'),
        ]),

        est('Problemas del día', 'Resuelve la situación completa', '🧠', 'desafio_final', [
            reto('Tenía 250 pesos y gasté 90. ¿Cuánto me queda?',        ['160', '340', '150'], '160'),
            reto('En la biblioteca hay 340 libros y llegan 125. ¿Cuántos hay?', ['465', '215', '455'], '465'),
            reto('Un estadio tiene 800 sillas y hay 356 vacías. ¿Cuántas ocupadas?', ['444', '1.156', '544'], '444'),
            reto('Ana leyó 78 páginas y Luis 95. ¿Cuántas más leyó Luis?', ['17', '173', '27'], '17'),
            reto('Junté 125 tapas el lunes y 275 el martes. ¿Cuántas en total?', ['400', '350', '150'], '400'),
        ]),
    ],
],

[
    'slug'  => 'las-tablas-de-multiplicar',
    'title' => 'Las tablas de multiplicar',
    'description' => 'Multiplicar es sumar el mismo número muchas veces. Domina las tablas del 1 al 10.',
    'objective' => 'Comprender la multiplicación como suma repetida y memorizar las tablas del 1 al 10.',
    'icon' => '✖️', 'nivel' => 'primaria-media', 'bloque' => 'calculo-mental',
    'duracion' => 14, 'tags' => ['calculo', 'memoria'],
    'estaciones' => [

        est('Multiplicar es sumar muchas veces', 'De la suma a la multiplicación', '🔁', 'opcion_multiple', [
            omp('3 + 3 + 3 + 3 es lo mismo que…',      ['3 × 4', '3 + 4', '3 − 4'], '3 × 4'),
            omp('5 + 5 + 5 es lo mismo que…',          ['5 × 3', '5 + 3', '15 × 3'], '5 × 3'),
            omp('2 × 6 es lo mismo que…',              ['2+2+2+2+2+2', '2+6', '6−2'], '2+2+2+2+2+2'),
            omp('Hay 4 cajas con 5 lápices. ¿Cuántos lápices?', ['20', '9', '45'], '20'),
            omp('Hay 3 mesas con 6 sillas. ¿Cuántas sillas?',   ['18', '9', '36'], '18'),
        ]),

        est('Tablas fáciles', 'Del 2, del 5 y del 10', '🖐️', 'operacion', [
            ['op' => 'multiplicacion', 'a' => 2,  'b' => 7, 'resultado' => 14],
            ['op' => 'multiplicacion', 'a' => 5,  'b' => 6, 'resultado' => 30],
            ['op' => 'multiplicacion', 'a' => 10, 'b' => 8, 'resultado' => 80],
            ['op' => 'multiplicacion', 'a' => 5,  'b' => 9, 'resultado' => 45],
            ['op' => 'multiplicacion', 'a' => 2,  'b' => 9, 'resultado' => 18],
        ]),

        est('Tablas difíciles', 'Del 6, del 7 y del 8', '🎯', 'operacion', [
            ['op' => 'multiplicacion', 'a' => 7, 'b' => 8, 'resultado' => 56],
            ['op' => 'multiplicacion', 'a' => 6, 'b' => 7, 'resultado' => 42],
            ['op' => 'multiplicacion', 'a' => 8, 'b' => 9, 'resultado' => 72],
            ['op' => 'multiplicacion', 'a' => 6, 'b' => 6, 'resultado' => 36],
            ['op' => 'multiplicacion', 'a' => 7, 'b' => 7, 'resultado' => 49],
        ]),

        est('Doble y mitad', 'Estrategias que ahorran trabajo', '💡', 'opcion_multiple', [
            omp('El doble de 12 es…',       ['24', '14', '6'], '24'),
            omp('La mitad de 18 es…',       ['9', '36', '8'], '9'),
            omp('4 × 6 es el doble de…',    ['2 × 6', '4 × 3', '8 × 6'], '2 × 6'),
            omp('El doble de 25 es…',       ['50', '30', '20'], '50'),
            omp('La mitad de 100 es…',      ['50', '10', '25'], '50'),
        ]),

        est('Desafío de las tablas', 'Contrarreloj: responde rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es correcto el resultado?', 'Sí o no, sin pensarlo mucho', [
                ['e' => '✖️', 'n' => '3 × 4 = 12',  'ok' => true],
                ['e' => '✖️', 'n' => '5 × 5 = 20',  'ok' => false],
                ['e' => '✖️', 'n' => '7 × 2 = 14',  'ok' => true],
                ['e' => '✖️', 'n' => '6 × 3 = 21',  'ok' => false],
                ['e' => '✖️', 'n' => '9 × 1 = 9',   'ok' => true],
                ['e' => '✖️', 'n' => '8 × 4 = 32',  'ok' => true],
                ['e' => '✖️', 'n' => '10 × 7 = 17', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'multiplicar-y-dividir',
    'title' => 'Multiplicar y dividir',
    'description' => 'Dos operaciones que son la misma al revés. Repartir, agrupar y comprobar.',
    'objective' => 'Reconocer la multiplicación y la división como operaciones inversas y resolver problemas con ambas.',
    'icon' => '➗', 'nivel' => 'primaria-media', 'bloque' => 'calculo-mental',
    'duracion' => 14, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('Una es la otra al revés', 'Multiplicación y división', '🔄', 'opcion_multiple', [
            omp('Si 6 × 4 = 24, entonces 24 ÷ 4 = …',  ['6', '4', '24'], '6'),
            omp('Si 7 × 5 = 35, entonces 35 ÷ 7 = …',  ['5', '7', '35'], '5'),
            omp('Si 24 ÷ 8 = 3, entonces 3 × 8 = …',   ['24', '8', '11'], '24'),
            omp('¿Qué número por 9 da 45?',            ['5', '4', '9'], '5'),
            omp('¿Entre qué número divido 30 para obtener 6?', ['5', '6', '30'], '5'),
        ]),

        est('Repartir en partes iguales', 'La división en la vida diaria', '🍰', 'opcion_multiple', [
            omp('12 galletas entre 4 niños. ¿Cuántas a cada uno?',   ['3', '4', '8'], '3'),
            omp('20 lápices en 5 cajas iguales. ¿Cuántos por caja?', ['4', '5', '15'], '4'),
            omp('36 sillas en 6 filas iguales. ¿Cuántas por fila?',  ['6', '30', '9'], '6'),
            omp('45 fichas entre 9 jugadores. ¿Cuántas a cada uno?', ['5', '9', '36'], '5'),
            omp('Tengo 17 dulces para 5 niños. ¿Cuántos sobran?',    ['2', '3', '0'], '2'),
        ]),

        est('Multiplicar por múltiplos de 10', 'El atajo de los ceros', '0️⃣', 'opcion_multiple', [
            omp('7 × 10 = …',   ['70', '7', '700'], '70'),
            omp('12 × 100 = …', ['1.200', '120', '12.000'], '1.200'),
            omp('30 × 4 = …',   ['120', '34', '12'], '120'),
            omp('500 ÷ 10 = …', ['50', '5', '500'], '50'),
            omp('80 × 20 = …',  ['1.600', '160', '100'], '1.600'),
        ]),

        est('Múltiplos y divisores', '¿Cabe justo o sobra algo?', '🧩', 'opcion_multiple', [
            omp('¿Cuál de estos es múltiplo de 5?',    ['35', '32', '38'], '35'),
            omp('¿Cuál de estos es múltiplo de 3?',    ['27', '28', '25'], '27'),
            omp('¿Es 4 divisor de 20?',                ['Sí', 'No', 'Solo a veces'], 'Sí'),
            omp('¿Es 7 divisor de 30?',                ['No', 'Sí', 'Solo a veces'], 'No'),
            omp('¿Cuál NO es múltiplo de 10?',         ['45', '40', '90'], '45'),
        ]),

        est('Desafío de reparto', 'Problemas de multiplicar y dividir', '🏆', 'desafio_final', [
            reto('Una caja trae 6 huevos. ¿Cuántos hay en 8 cajas?',        ['48', '14', '68'], '48'),
            reto('120 estudiantes en grupos de 4. ¿Cuántos grupos salen?',  ['30', '40', '24'], '30'),
            reto('Un bus lleva 42 personas. ¿Cuántas llevan 5 buses?',      ['210', '47', '200'], '210'),
            reto('72 libros en 8 estantes iguales. ¿Cuántos por estante?',  ['9', '8', '64'], '9'),
            reto('Compré 3 cuadernos de 2.500 pesos. ¿Cuánto pagué?',       ['7.500', '2.503', '5.000'], '7.500'),
        ]),
    ],
],

[
    'slug'  => 'divisibilidad-y-numeros-primos',
    'title' => 'Divisibilidad y números primos',
    'description' => 'Reglas para saber si un número se divide sin hacer la cuenta, y qué es un número primo.',
    'objective' => 'Aplicar los criterios de divisibilidad entre 2, 3 y 5 e identificar números primos y compuestos.',
    'icon' => '🔎', 'nivel' => 'primaria-superior', 'bloque' => 'calculo-mental',
    'duracion' => 15, 'tags' => ['calculo', 'logica', 'deduccion'],
    'estaciones' => [

        est('La regla del 2', 'Los números pares', '2️⃣', 'juego_rapido',
            conTitulo('¿Se divide entre 2?', 'Mira la última cifra', [
                ['e' => '🔢', 'n' => '34',  'ok' => true],
                ['e' => '🔢', 'n' => '57',  'ok' => false],
                ['e' => '🔢', 'n' => '120', 'ok' => true],
                ['e' => '🔢', 'n' => '99',  'ok' => false],
                ['e' => '🔢', 'n' => '246', 'ok' => true],
                ['e' => '🔢', 'n' => '381', 'ok' => false],
            ])),

        est('Las reglas del 3 y del 5', 'Dos atajos más', '🖐️', 'opcion_multiple', [
            omp('Un número se divide entre 5 si termina en…', ['0 o 5', '2 o 4', 'Cualquier cifra'], '0 o 5'),
            omp('Un número se divide entre 3 si…', ['La suma de sus cifras se divide entre 3', 'Termina en 3', 'Es par'], 'La suma de sus cifras se divide entre 3'),
            omp('¿Se divide 132 entre 3? (1+3+2 = 6)', ['Sí', 'No', 'Falta información'], 'Sí'),
            omp('¿Se divide 245 entre 5?',             ['Sí', 'No', 'Falta información'], 'Sí'),
            omp('¿Se divide 271 entre 3? (2+7+1 = 10)', ['No', 'Sí', 'Falta información'], 'No'),
        ]),

        est('Primos y compuestos', 'Los que solo se dividen entre 1 y sí mismos', '💎', 'opcion_multiple', [
            omp('¿Cuál de estos es primo?',      ['13', '15', '21'], '13'),
            omp('¿Cuál de estos NO es primo?',   ['9', '7', '11'], '9'),
            omp('¿Es el 2 un número primo?',     ['Sí', 'No', 'Depende'], 'Sí'),
            omp('¿Es el 1 un número primo?',     ['No', 'Sí', 'Depende'], 'No'),
            omp('¿Cuántos divisores tiene un número primo?', ['2', '1', '3'], '2'),
        ]),

        est('Descomponer en factores', 'Todo número compuesto es un producto de primos', '🧬', 'opcion_multiple', [
            omp('12 = 2 × 2 × …',   ['3', '4', '6'], '3'),
            omp('18 = 2 × 3 × …',   ['3', '2', '9'], '3'),
            omp('20 = 2 × 2 × …',   ['5', '4', '10'], '5'),
            omp('¿Cuál es la descomposición de 30?', ['2 × 3 × 5', '2 × 15', '5 × 6'], '2 × 3 × 5'),
            omp('¿Cuál es la descomposición de 8?',  ['2 × 2 × 2', '4 × 2', '8 × 1'], '2 × 2 × 2'),
        ]),

        est('Mínimo común múltiplo y máximo común divisor', 'Dos herramientas para repartir', '🔗', 'desafio_final', [
            reto('¿Cuál es el m.c.m. de 4 y 6?',   ['12', '24', '10'], '12'),
            reto('¿Cuál es el m.c.d. de 12 y 18?', ['6', '3', '36'], '6'),
            reto('¿Cuál es el m.c.m. de 3 y 5?',   ['15', '8', '30'], '15'),
            reto('¿Cuál es el m.c.d. de 20 y 30?', ['10', '5', '60'], '10'),
            reto('Dos buses salen cada 6 y cada 8 minutos. ¿Cada cuánto coinciden?', ['24 minutos', '14 minutos', '48 minutos'], '24 minutos'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · FRACCIONES Y DECIMALES
// =====================================================================

[
    'slug'  => 'que-es-una-fraccion',
    'title' => '¿Qué es una fracción?',
    'description' => 'Partir un todo en partes iguales: medios, tercios y cuartos que se ven y se tocan.',
    'objective' => 'Comprender la fracción como relación parte-todo e identificar sus términos.',
    'icon' => '🍕', 'nivel' => 'primaria-media', 'bloque' => 'fracciones-y-decimales',
    'duracion' => 13, 'tags' => ['calculo', 'observacion', 'logica'],
    'estaciones' => [

        est('Partes iguales', 'Una fracción solo vale si las partes son iguales', '🍰', 'opcion_multiple', [
            omp('Si parto una pizza en 2 partes iguales, cada parte es…',  ['Un medio', 'Un tercio', 'Un cuarto'], 'Un medio', '🍕'),
            omp('Si la parto en 4 partes iguales, cada parte es…',         ['Un cuarto', 'Un medio', 'Un quinto'], 'Un cuarto', '🍕'),
            omp('Si la parto en 3 partes iguales, cada parte es…',         ['Un tercio', 'Un cuarto', 'Un medio'], 'Un tercio', '🍕'),
            omp('¿Sirve partir en 4 pedazos de distinto tamaño?',          ['No, deben ser iguales', 'Sí, da igual', 'Solo con pizza'], 'No, deben ser iguales'),
            omp('Me como 1 de 4 pedazos. ¿Cuánto queda?',                  ['3 de 4', '1 de 4', '4 de 4'], '3 de 4'),
        ]),

        est('Numerador y denominador', 'Cada término dice algo distinto', '🔢', 'opcion_multiple', [
            omp('En 3/5, ¿cuál es el numerador?',        ['3', '5', '8'], '3'),
            omp('En 3/5, ¿cuál es el denominador?',      ['5', '3', '2'], '5'),
            omp('¿Qué indica el denominador?',           ['En cuántas partes se dividió', 'Cuántas partes tomo', 'El total de objetos'], 'En cuántas partes se dividió'),
            omp('¿Qué indica el numerador?',             ['Cuántas partes tomo', 'En cuántas partes se dividió', 'El resultado'], 'Cuántas partes tomo'),
            omp('«Dos tercios» se escribe…',             ['2/3', '3/2', '2,3'], '2/3'),
        ]),

        est('Comparar fracciones', '¿Cuál es más grande?', '⚖️', 'opcion_multiple', [
            omp('¿Qué es mayor, 1/2 o 1/4?',   ['1/2', '1/4', 'Iguales'], '1/2'),
            omp('¿Qué es mayor, 3/5 o 2/5?',   ['3/5', '2/5', 'Iguales'], '3/5'),
            omp('¿Qué es menor, 1/8 o 1/3?',   ['1/8', '1/3', 'Iguales'], '1/8'),
            omp('¿Cuánto vale 4/4?',           ['1 entero', 'Medio', '4 enteros'], '1 entero'),
            omp('¿Qué es mayor, 5/4 o 1 entero?', ['5/4', '1 entero', 'Iguales'], '5/4'),
        ]),

        est('Ordena las fracciones', 'De la más pequeña a la más grande', '📶', 'ordenar_secuencia', [
            'title' => 'Ordena de menor a mayor',
            'items' => ['1/8', '1/4', '1/2', '3/4', '1 entero'],
        ]),
    ],
],

[
    'slug'  => 'fracciones-equivalentes',
    'title' => 'Fracciones equivalentes',
    'description' => 'Distintas fracciones que valen lo mismo, y cómo sumarlas y restarlas.',
    'objective' => 'Reconocer fracciones equivalentes y operar sumas y restas con igual denominador.',
    'icon' => '🟰', 'nivel' => 'primaria-superior', 'bloque' => 'fracciones-y-decimales',
    'duracion' => 15, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('Valen lo mismo', 'Dos formas de escribir la misma cantidad', '🔁', 'opcion_multiple', [
            omp('¿A qué equivale 1/2?',   ['2/4', '1/4', '3/4'], '2/4'),
            omp('¿A qué equivale 2/6?',   ['1/3', '1/2', '2/3'], '1/3'),
            omp('¿A qué equivale 3/9?',   ['1/3', '3/4', '1/9'], '1/3'),
            omp('¿A qué equivale 5/10?',  ['1/2', '1/5', '5/5'], '1/2'),
            omp('¿A qué equivale 4/8?',   ['1/2', '1/4', '4/4'], '1/2'),
        ]),

        est('Clases de fracciones', 'Propia, impropia y número mixto', '🗂️', 'opcion_multiple', [
            omp('¿Cómo es 3/7, con el numerador menor que el denominador?', ['Propia', 'Impropia', 'Mixta'], 'Propia'),
            omp('¿Cómo es 9/4, con el numerador mayor?',                    ['Impropia', 'Propia', 'Equivalente'], 'Impropia'),
            omp('¿Cuánto vale 7/7?',                                        ['1', '7', '0'], '1'),
            omp('¿Qué número mixto es 5/2?',                                ['2 y 1/2', '1 y 2/5', '5 y 1/2'], '2 y 1/2'),
            omp('¿Qué fracción impropia es 1 y 3/4?',                       ['7/4', '4/7', '3/4'], '7/4'),
        ]),

        est('Sumar y restar', 'Con el mismo denominador es directo', '➕', 'opcion_multiple', [
            omp('1/5 + 2/5 = …',   ['3/5', '3/10', '2/5'], '3/5'),
            omp('4/7 + 2/7 = …',   ['6/7', '6/14', '8/7'], '6/7'),
            omp('5/8 − 2/8 = …',   ['3/8', '3/0', '7/8'], '3/8'),
            omp('3/4 + 1/4 = …',   ['1 entero', '4/8', '3/8'], '1 entero'),
            omp('6/9 − 6/9 = …',   ['0', '1', '12/9'], '0'),
        ]),

        est('Fracciones de una cantidad', 'La fracción como operador', '🎯', 'opcion_multiple', [
            omp('¿Cuánto es la mitad de 20?',        ['10', '5', '40'], '10'),
            omp('¿Cuánto es 1/4 de 20?',             ['5', '4', '10'], '5'),
            omp('¿Cuánto es 1/3 de 18?',             ['6', '3', '9'], '6'),
            omp('¿Cuánto es 3/4 de 20?',             ['15', '5', '12'], '15'),
            omp('¿Cuánto es 2/5 de 30?',             ['12', '6', '15'], '12'),
        ]),

        est('Desafío de fracciones', 'Problemas con partes de un todo', '🏆', 'desafio_final', [
            reto('Me comí 3/8 de la pizza. ¿Cuánto queda?',                  ['5/8', '3/8', '8/8'], '5/8'),
            reto('En un curso de 24 niños, 1/3 usa gafas. ¿Cuántos son?',    ['8', '3', '12'], '8'),
            reto('¿Cuál de estas fracciones vale más de un entero?',         ['11/9', '8/9', '9/9'], '11/9'),
            reto('Un vaso está lleno hasta 2/4. ¿Cómo se dice también?',     ['La mitad', 'Un cuarto', 'Tres cuartos'], 'La mitad'),
            reto('Repartí 1/2 pastel y luego 1/4 más. ¿Cuánto repartí?',     ['3/4', '2/6', '1/6'], '3/4'),
        ]),
    ],
],

[
    'slug'  => 'numeros-decimales',
    'title' => 'Números decimales',
    'description' => 'La coma decimal: décimas, centésimas y cómo se relacionan con las fracciones.',
    'objective' => 'Leer, comparar y operar números decimales, relacionándolos con las fracciones decimales.',
    'icon' => '🔸', 'nivel' => 'primaria-superior', 'bloque' => 'fracciones-y-decimales',
    'duracion' => 15, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('Décimas y centésimas', 'Lo que hay después de la coma', '🔍', 'opcion_multiple', [
            omp('¿Cómo se escribe 1/10 en decimal?',   ['0,1', '0,01', '1,0'], '0,1'),
            omp('¿Cómo se escribe 1/100 en decimal?',  ['0,01', '0,1', '1,00'], '0,01'),
            omp('En 3,45, ¿cuál cifra son las décimas?', ['4', '5', '3'], '4'),
            omp('En 3,45, ¿cuál cifra son las centésimas?', ['5', '4', '3'], '5'),
            omp('¿Cómo se escribe «dos con cinco»?',   ['2,5', '25', '0,25'], '2,5'),
        ]),

        est('Comparar decimales', 'La coma cambia todo', '⚖️', 'opcion_multiple', [
            omp('¿Cuál es mayor, 0,5 o 0,05?',   ['0,5', '0,05', 'Iguales'], '0,5'),
            omp('¿Cuál es mayor, 1,2 o 1,20?',   ['Iguales', '1,2', '1,20'], 'Iguales'),
            omp('¿Cuál es menor, 3,7 o 3,07?',   ['3,07', '3,7', 'Iguales'], '3,07'),
            omp('¿Cuál está entre 2 y 3?',       ['2,4', '3,4', '1,4'], '2,4'),
            omp('¿Cuánto vale 0,5 en fracción?', ['1/2', '1/5', '5/1'], '1/2'),
        ]),

        est('Sumar y restar decimales', 'Coma debajo de coma', '📝', 'opcion_multiple', [
            omp('0,5 + 0,3 = …',   ['0,8', '0,08', '8'], '0,8'),
            omp('1,2 + 2,5 = …',   ['3,7', '3,07', '37'], '3,7'),
            omp('4,0 − 1,5 = …',   ['2,5', '3,5', '2,05'], '2,5'),
            omp('0,75 + 0,25 = …', ['1', '0,100', '0,10'], '1'),
            omp('10 − 0,5 = …',    ['9,5', '5', '10,5'], '9,5'),
        ]),

        est('Multiplicar por potencias de 10', 'La coma se corre', '➡️', 'opcion_multiple', [
            omp('2,5 × 10 = …',    ['25', '2,50', '250'], '25'),
            omp('0,7 × 100 = …',   ['70', '7', '700'], '70'),
            omp('35 ÷ 10 = …',     ['3,5', '350', '0,35'], '3,5'),
            omp('4,2 × 100 = …',   ['420', '42', '4.200'], '420'),
            omp('8 ÷ 100 = …',     ['0,08', '0,8', '800'], '0,08'),
        ]),

        est('Desafío decimal', 'Decimales en la vida diaria', '🏆', 'desafio_final', [
            reto('Un lápiz cuesta $1.500 y compro 2. ¿Cuánto pago?',   ['$3.000', '$1.502', '$2.500'], '$3.000'),
            reto('Mido 1,35 m y crecí 5 cm. ¿Cuánto mido?',            ['1,40 m', '1,85 m', '1,36 m'], '1,40 m'),
            reto('Una botella tiene 1,5 L y bebo 0,5 L. ¿Cuánto queda?', ['1 L', '2 L', '0,5 L'], '1 L'),
            reto('¿Cuál es mayor: 0,9 o 0,89?',                        ['0,9', '0,89', 'Iguales'], '0,9'),
            reto('¿Cuánto es 0,25 en fracción?',                       ['1/4', '1/2', '25/10'], '1/4'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · GEOMETRÍA Y FORMAS
// =====================================================================

[
    'slug'  => 'figuras-planas-y-espacio',
    'title' => 'Figuras planas y espacio',
    'description' => 'Círculo, cuadrado, triángulo y rectángulo, y las palabras para ubicarse: arriba, cerca, a la izquierda.',
    'objective' => 'Reconocer figuras planas básicas y emplear nociones espaciales de lateralidad y dirección.',
    'icon' => '🔷', 'nivel' => 'primaria-inicial', 'bloque' => 'geometria-y-formas',
    'duracion' => 12, 'tags' => ['observacion', 'clasificacion', 'juego'],
    'estaciones' => [

        /*
         * Se empareja un OBJETO con su forma, no la forma con su nombre.
         * A los seis años el emoji del cuadrado y la palabra «cuadrado»
         * son el mismo dato dicho dos veces; reconocer que la ventana es
         * un cuadrado sí es el aprendizaje. Además el emoji no tiene
         * rectángulo, y poner uno «parecido» sería enseñar mal la forma.
         */
        est('Cada objeto con su forma', 'Une el objeto con la figura que tiene', '🔗', 'emparejar', [
            ['e' => '🕐', 'w' => 'Círculo'],
            ['e' => '🪟', 'w' => 'Cuadrado'],
            ['e' => '🍕', 'w' => 'Triángulo'],
            ['e' => '📺', 'w' => 'Rectángulo'],
            ['e' => '⭐', 'w' => 'Estrella'],
            ['e' => '💠', 'w' => 'Rombo'],
        ]),

        est('¿Cuántos lados?', 'Cuenta los lados de cada figura', '📐', 'opcion_multiple', [
            omp('¿Cuántos lados tiene un triángulo?',  ['3', '4', '0'], '3', '🔺'),
            omp('¿Cuántos lados tiene un cuadrado?',   ['4', '3', '5'], '4', '🟦'),
            omp('¿Cuántos lados tiene un círculo?',    ['Ninguno', '1', '4'], 'Ninguno', '🔴'),
            omp('¿Cuántas esquinas tiene un cuadrado?', ['4', '2', '8'], '4', '🟦'),
            omp('¿Qué figura tiene 5 lados?',          ['Pentágono', 'Triángulo', 'Rombo'], 'Pentágono'),
        ]),

        est('Arriba, abajo, cerca, lejos', 'Palabras para ubicarse', '🧭', 'opcion_multiple', [
            omp('El techo está… de nosotros',              ['Arriba', 'Abajo', 'Detrás'], 'Arriba', '🏠'),
            omp('Si algo está a mi lado derecho, está…',   ['A la derecha', 'A la izquierda', 'Encima'], 'A la derecha'),
            omp('El piso está…',                           ['Abajo', 'Arriba', 'Al lado'], 'Abajo'),
            omp('Una línea que va de lado a lado es…',     ['Horizontal', 'Vertical', 'Diagonal'], 'Horizontal'),
            omp('Una línea que va de arriba abajo es…',    ['Vertical', 'Horizontal', 'Curva'], 'Vertical'),
        ]),

        est('Memoria de formas', 'Encuentra las parejas', '🧠', 'memoria',
            ['🔴', '🟦', '🔺', '💠', '⬜', '🟨']),
    ],
],

[
    'slug'  => 'simetria-y-patrones',
    'title' => 'Simetría y patrones',
    'description' => 'Figuras que se reflejan como en un espejo y secuencias que se repiten con una regla.',
    'objective' => 'Identificar ejes de simetría y descubrir la regla de formación de un patrón.',
    'icon' => '🦋', 'nivel' => 'primaria-inicial', 'bloque' => 'geometria-y-formas',
    'duracion' => 12, 'tags' => ['observacion', 'patrones', 'logica'],
    'estaciones' => [

        est('Como en un espejo', 'Qué es la simetría', '🪞', 'opcion_multiple', [
            omp('¿Qué es un eje de simetría?',            ['Una línea que divide la figura en dos partes iguales', 'El centro de la figura', 'El lado más largo'], 'Una línea que divide la figura en dos partes iguales'),
            omp('¿Es simétrica una mariposa?',            ['Sí', 'No', 'Solo si vuela'], 'Sí', '🦋'),
            omp('¿Es simétrico el cuerpo humano?',        ['Sí, casi', 'No', 'Solo la cabeza'], 'Sí, casi', '🧍'),
            omp('¿Cuántos ejes de simetría tiene un cuadrado?', ['4', '1', '2'], '4', '🟦'),
            omp('¿Cuántos ejes de simetría tiene un círculo?',  ['Infinitos', '1', 'Ninguno'], 'Infinitos', '🔴'),
        ]),

        est('Continúa el patrón', '¿Qué viene después?', '🔁', 'opcion_multiple', [
            omp('🔴 🔵 🔴 🔵 … ¿qué sigue?',        ['🔴', '🔵', '🟡'], '🔴'),
            omp('⭐ ⭐ 🌙 ⭐ ⭐ 🌙 … ¿qué sigue?',   ['⭐', '🌙', '☀️'], '⭐'),
            omp('🔺 🟦 🟦 🔺 🟦 🟦 … ¿qué sigue?',  ['🔺', '🟦', '🔴'], '🔺'),
            omp('2, 4, 6, 8 … ¿qué sigue?',        ['10', '9', '12'], '10'),
            omp('1, 3, 5, 7 … ¿qué sigue?',        ['9', '8', '11'], '9'),
        ]),

        est('Patrones numéricos', 'Descubre la regla y completa', '🔢', 'secuencia_numerica', [
            serie([3, 6, 9, null, 15]),
            serie([10, 20, null, 40, 50]),
            serie([100, 90, 80, null, 60]),
            serie([4, 8, 12, null, 20]),
            serie([7, 14, 21, null, 35]),
        ]),

        /*
         * Cada palabra va con el dibujo de lo que nombra, nunca con uno
         * «parecido»: el niño que aún está aprendiendo a leer memoriza la
         * asociación que ve, no la corrige después.
         */
        est('Simetría o no', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Tiene eje de simetría?', 'Piensa en el espejo', [
                ['e' => '🦋', 'n' => 'Mariposa',  'ok' => true],
                ['e' => '❤️', 'n' => 'Corazón',   'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',     'ok' => true],
                ['e' => '🍌', 'n' => 'Banano',    'ok' => false],
                ['e' => '⭐', 'n' => 'Estrella',  'ok' => true],
                ['e' => '🐟', 'n' => 'Pez',       'ok' => false],
                ['e' => '⚽', 'n' => 'Balón',     'ok' => true],
            ])),
    ],
],

[
    'slug'  => 'poligonos-y-angulos',
    'title' => 'Polígonos y ángulos',
    'description' => 'Clasificar polígonos por sus lados y reconocer ángulos rectos, agudos y obtusos.',
    'objective' => 'Clasificar polígonos según sus lados y ángulos según su abertura.',
    'icon' => '📐', 'nivel' => 'primaria-media', 'bloque' => 'geometria-y-formas',
    'duracion' => 14, 'tags' => ['observacion', 'clasificacion', 'logica'],
    'estaciones' => [

        est('¿Cómo se llama?', 'Polígonos según sus lados', '🔷', 'opcion_multiple', [
            omp('Un polígono de 3 lados es un…',   ['Triángulo', 'Cuadrilátero', 'Pentágono'], 'Triángulo'),
            omp('Un polígono de 4 lados es un…',   ['Cuadrilátero', 'Triángulo', 'Hexágono'], 'Cuadrilátero'),
            omp('Un polígono de 5 lados es un…',   ['Pentágono', 'Hexágono', 'Octágono'], 'Pentágono'),
            omp('Un polígono de 6 lados es un…',   ['Hexágono', 'Pentágono', 'Heptágono'], 'Hexágono'),
            omp('¿Es el círculo un polígono?',     ['No, no tiene lados rectos', 'Sí', 'Solo a veces'], 'No, no tiene lados rectos'),
        ]),

        est('Regulares e irregulares', 'Cuando todos los lados miden igual', '🟨', 'opcion_multiple', [
            omp('Un polígono regular tiene…',      ['Todos los lados iguales', 'Lados distintos', 'Solo tres lados'], 'Todos los lados iguales'),
            omp('¿Es regular un cuadrado?',        ['Sí', 'No', 'Depende del tamaño'], 'Sí'),
            omp('¿Es regular un rectángulo largo?', ['No', 'Sí', 'Siempre'], 'No'),
            omp('Un triángulo con los tres lados iguales se llama…', ['Equilátero', 'Isósceles', 'Escaleno'], 'Equilátero'),
            omp('Un triángulo con los tres lados distintos se llama…', ['Escaleno', 'Equilátero', 'Isósceles'], 'Escaleno'),
        ]),

        est('Tipos de ángulo', 'Según cuánto se abren', '📏', 'opcion_multiple', [
            omp('Un ángulo de 90° se llama…',            ['Recto', 'Agudo', 'Obtuso'], 'Recto'),
            omp('Un ángulo menor de 90° se llama…',      ['Agudo', 'Obtuso', 'Llano'], 'Agudo'),
            omp('Un ángulo mayor de 90° se llama…',      ['Obtuso', 'Agudo', 'Recto'], 'Obtuso'),
            omp('Un ángulo de 180° se llama…',           ['Llano', 'Recto', 'Completo'], 'Llano'),
            omp('¿Cuántos ángulos rectos tiene un cuadrado?', ['4', '2', '1'], '4'),
        ]),

        est('Rectas que se cruzan', 'Paralelas y perpendiculares', '➕', 'opcion_multiple', [
            omp('Dos rectas que nunca se cruzan son…',    ['Paralelas', 'Perpendiculares', 'Secantes'], 'Paralelas'),
            omp('Dos rectas que se cruzan en 90° son…',   ['Perpendiculares', 'Paralelas', 'Curvas'], 'Perpendiculares'),
            omp('¿Cómo son los rieles del tren?',         ['Paralelos', 'Perpendiculares', 'Se cruzan'], 'Paralelos', '🛤️'),
            omp('¿Cómo son los lados de una cruz?',       ['Perpendiculares', 'Paralelos', 'Iguales'], 'Perpendiculares', '➕'),
            omp('¿Se cortan alguna vez dos rectas paralelas?', ['Nunca', 'Siempre', 'A veces'], 'Nunca'),
        ]),

        est('Crucigrama de geometría', 'Cada pista es una definición', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'POLIGONO',  'pista' => 'Figura cerrada hecha de lados rectos'],
                ['w' => 'ANGULO',    'pista' => 'Abertura entre dos lados que se encuentran'],
                ['w' => 'RECTO',     'pista' => 'Así se llama el ángulo de 90 grados'],
                ['w' => 'ROMBO',     'pista' => 'Cuadrilátero con los cuatro lados iguales, ladeado'],
                ['w' => 'LADO',      'pista' => 'Cada línea recta de un polígono'],
                ['w' => 'SIMETRIA',  'pista' => 'Cuando las dos mitades son iguales'],
                ['w' => 'PARALELAS', 'pista' => 'Rectas que nunca se cruzan'],
            ])),

        est('Desafío geométrico', 'Cinco preguntas de formas', '🏆', 'desafio_final', [
            reto('¿Cuántos lados tiene un octágono?',        ['8', '6', '10'], '8'),
            reto('¿Cuánto suman los ángulos de un triángulo?', ['180°', '360°', '90°'], '180°'),
            reto('¿Qué cuadrilátero tiene lados iguales y ángulos rectos?', ['Cuadrado', 'Rombo', 'Trapecio'], 'Cuadrado'),
            reto('¿Qué figura tiene infinitos ejes de simetría?', ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo'),
            reto('¿Cómo se llama un polígono de 7 lados?',   ['Heptágono', 'Hexágono', 'Octágono'], 'Heptágono'),
        ]),
    ],
],

[
    'slug'  => 'cuerpos-geometricos',
    'title' => 'Cuerpos geométricos',
    'description' => 'La diferencia entre una figura y un cuerpo: prismas, pirámides y cuerpos redondos.',
    'objective' => 'Distinguir figuras planas de cuerpos geométricos y clasificar estos por sus caras.',
    'icon' => '🧊', 'nivel' => 'primaria-media', 'bloque' => 'geometria-y-formas',
    'duracion' => 13, 'tags' => ['observacion', 'clasificacion'],
    'estaciones' => [

        est('Plano o cuerpo', 'Uno se dibuja, el otro se sostiene', '✋', 'opcion_multiple', [
            omp('¿Cuál de estos es un cuerpo geométrico?',  ['Cubo', 'Cuadrado', 'Triángulo'], 'Cubo'),
            omp('¿Cuál de estos es una figura plana?',      ['Círculo', 'Esfera', 'Cilindro'], 'Círculo'),
            omp('Una pelota tiene forma de…',               ['Esfera', 'Círculo', 'Cubo'], 'Esfera', '⚽'),
            omp('Un dado tiene forma de…',                  ['Cubo', 'Cuadrado', 'Pirámide'], 'Cubo', '🎲'),
            omp('Una lata tiene forma de…',                 ['Cilindro', 'Cono', 'Esfera'], 'Cilindro', '🥫'),
        ]),

        est('Caras, aristas y vértices', 'Las partes de un cuerpo', '🔺', 'opcion_multiple', [
            omp('¿Cuántas caras tiene un cubo?',      ['6', '4', '8'], '6', '🎲'),
            omp('¿Cuántas aristas tiene un cubo?',    ['12', '6', '8'], '12'),
            omp('¿Cuántos vértices tiene un cubo?',   ['8', '6', '12'], '8'),
            omp('¿Cuántas caras tiene una esfera?',   ['Ninguna plana', '1', '2'], 'Ninguna plana'),
            omp('¿Qué forma tienen las caras de un cubo?', ['Cuadrados', 'Triángulos', 'Círculos'], 'Cuadrados'),
        ]),

        est('Redondos y no redondos', 'Los que ruedan y los que no', '⚪', 'opcion_multiple', [
            omp('¿Cuál de estos rueda?',              ['Esfera', 'Cubo', 'Pirámide'], 'Esfera'),
            omp('¿Cuál de estos NO rueda?',           ['Cubo', 'Cilindro', 'Cono'], 'Cubo'),
            omp('Un prisma tiene…',                   ['Solo caras planas', 'Una cara curva', 'Ninguna cara'], 'Solo caras planas'),
            omp('Un cono termina en…',                ['Una punta', 'Una cara plana arriba', 'Dos puntas'], 'Una punta'),
            omp('¿Qué cuerpo tiene una base cuadrada y termina en punta?', ['Pirámide', 'Cilindro', 'Cubo'], 'Pirámide'),
        ]),

        est('Cuerpos del día a día', 'Une el objeto con su forma', '🔗', 'emparejar', [
            ['e' => '⚽', 'w' => 'Esfera'],
            ['e' => '🎲', 'w' => 'Cubo'],
            ['e' => '🥫', 'w' => 'Cilindro'],
            ['e' => '🍦', 'w' => 'Cono'],
            ['e' => '📦', 'w' => 'Prisma'],
            ['e' => '⛺', 'w' => 'Pirámide'],
        ]),
    ],
],

[
    'slug'  => 'movimientos-en-el-plano',
    'title' => 'Movimientos en el plano',
    'description' => 'Trasladar, girar y reflejar figuras, y ubicar puntos con coordenadas.',
    'objective' => 'Reconocer traslaciones, rotaciones y reflexiones y ubicar puntos en el primer cuadrante.',
    'icon' => '🧭', 'nivel' => 'primaria-superior', 'bloque' => 'geometria-y-formas',
    'duracion' => 15, 'tags' => ['observacion', 'logica', 'patrones'],
    'estaciones' => [

        est('Tres movimientos', 'Trasladar, rotar y reflejar', '🔄', 'opcion_multiple', [
            omp('Mover una figura sin girarla es una…',        ['Traslación', 'Rotación', 'Reflexión'], 'Traslación'),
            omp('Girar una figura alrededor de un punto es una…', ['Rotación', 'Traslación', 'Reflexión'], 'Rotación'),
            omp('Voltearla como en un espejo es una…',         ['Reflexión', 'Traslación', 'Rotación'], 'Reflexión'),
            omp('En estos tres movimientos, el tamaño de la figura…', ['No cambia', 'Se hace más grande', 'Se hace más pequeño'], 'No cambia'),
            omp('¿Qué movimiento hace la manecilla de un reloj?', ['Rotación', 'Traslación', 'Reflexión'], 'Rotación', '🕐'),
        ]),

        est('Coordenadas', 'Cada punto tiene una dirección', '📍', 'opcion_multiple', [
            omp('En el par (3, 5), ¿cuál es la coordenada x?',   ['3', '5', '8'], '3'),
            omp('En el par (3, 5), ¿cuál es la coordenada y?',   ['5', '3', '2'], '5'),
            omp('¿Cómo se escribe el punto de origen?',          ['(0, 0)', '(1, 1)', '(0, 1)'], '(0, 0)'),
            omp('Para llegar a (4, 2) desde el origen: 4 a la derecha y…', ['2 arriba', '2 abajo', '4 arriba'], '2 arriba'),
            omp('¿Qué punto está más a la derecha, (2,7) o (6,1)?', ['(6,1)', '(2,7)', 'Los dos igual'], '(6,1)'),
        ]),

        est('Figuras congruentes y semejantes', 'Iguales o proporcionales', '🔍', 'opcion_multiple', [
            omp('Dos figuras congruentes son…',        ['Exactamente iguales', 'Parecidas pero de otro tamaño', 'De distinta forma'], 'Exactamente iguales'),
            omp('Dos figuras semejantes son…',         ['De la misma forma pero distinto tamaño', 'Exactamente iguales', 'Sin relación'], 'De la misma forma pero distinto tamaño'),
            omp('Si amplío una foto sin deformarla, la nueva es…', ['Semejante', 'Congruente', 'Distinta'], 'Semejante', '🖼️'),
            omp('Dos cuadrados de 5 cm de lado son…',  ['Congruentes', 'Semejantes solamente', 'Diferentes'], 'Congruentes'),
            omp('¿Cambia la forma en una traslación?', ['No', 'Sí', 'A veces'], 'No'),
        ]),

        est('Desafío del plano', 'Cinco preguntas de movimiento', '🏆', 'desafio_final', [
            reto('¿Qué transformación NO cambia la posición del centro?', ['Rotación sobre su centro', 'Traslación', 'Ninguna'], 'Rotación sobre su centro'),
            reto('Un punto en (0, 4) está…',            ['Sobre el eje vertical', 'Sobre el eje horizontal', 'En el origen'], 'Sobre el eje vertical'),
            reto('Al reflejar una letra R, ¿se puede leer igual?', ['No', 'Sí', 'Siempre'], 'No'),
            reto('¿Cuántos grados tiene un giro completo?', ['360°', '180°', '90°'], '360°'),
            reto('Si traslado un triángulo 3 a la derecha, su área…', ['No cambia', 'Aumenta', 'Disminuye'], 'No cambia'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · MEDIDA Y MAGNITUDES
// =====================================================================

[
    'slug'  => 'medir-longitudes',
    'title' => 'Medir longitudes',
    'description' => 'Del palmo al milímetro: metros, centímetros y sus equivalencias.',
    'objective' => 'Reconocer y utilizar el metro, el centímetro y el milímetro como unidades de longitud.',
    'icon' => '📏', 'nivel' => 'primaria-media', 'bloque' => 'medida-y-magnitudes',
    'duracion' => 13, 'tags' => ['calculo', 'observacion'],
    'estaciones' => [

        /*
         * Los «patrones arbitrarios» de la malla de K3 van primero por una
         * razón: el niño tiene que descubrir que medir con palmos da
         * resultados distintos según quién mida. Esa incomodidad es lo que
         * justifica que exista el metro.
         */
        est('Antes del metro', 'Medir con palmos, pies y pasos', '🖐️', 'opcion_multiple', [
            omp('Si mido la mesa con mi palmo y mi papá con el suyo, ¿da igual?', ['No, sus manos son más grandes', 'Sí, siempre', 'Solo si medimos rápido'], 'No, sus manos son más grandes'),
            omp('¿Por qué inventamos el metro?',            ['Para que todos midan igual', 'Porque es más bonito', 'Para medir más rápido'], 'Para que todos midan igual'),
            omp('¿Qué se mide en metros?',                  ['El largo de un salón', 'El peso de una fruta', 'El tiempo'], 'El largo de un salón'),
            omp('¿Con qué mido el ancho de un cuaderno?',   ['Centímetros', 'Kilómetros', 'Litros'], 'Centímetros', '📓'),
            omp('¿Con qué mido la distancia entre dos ciudades?', ['Kilómetros', 'Centímetros', 'Milímetros'], 'Kilómetros', '🚗'),
        ]),

        est('Equivalencias', 'Cuántos caben en cuántos', '🔄', 'opcion_multiple', [
            omp('¿Cuántos centímetros tiene un metro?',   ['100', '10', '1.000'], '100'),
            omp('¿Cuántos milímetros tiene un centímetro?', ['10', '100', '1'], '10'),
            omp('¿Cuántos metros tiene un kilómetro?',    ['1.000', '100', '10'], '1.000'),
            omp('¿Cuántos milímetros tiene un metro?',    ['1.000', '100', '10'], '1.000'),
            omp('2 metros son…',                          ['200 cm', '20 cm', '2.000 cm'], '200 cm'),
        ]),

        est('¿Cuánto mide?', 'Estima antes de medir', '🤔', 'opcion_multiple', [
            omp('¿Cuánto mide aproximadamente una puerta?',   ['2 metros', '2 centímetros', '2 kilómetros'], '2 metros', '🚪'),
            omp('¿Cuánto mide aproximadamente un lápiz?',     ['17 centímetros', '17 metros', '17 milímetros'], '17 centímetros', '✏️'),
            omp('¿Cuánto mide aproximadamente una hormiga?',  ['5 milímetros', '5 centímetros', '5 metros'], '5 milímetros', '🐜'),
            omp('¿Cuánto mide aproximadamente una cancha de fútbol?', ['100 metros', '100 centímetros', '100 kilómetros'], '100 metros', '⚽'),
            omp('¿Cuánto mide aproximadamente un niño de 8 años?', ['1,30 metros', '13 metros', '13 centímetros'], '1,30 metros', '🧒'),
        ]),

        est('Convierte y calcula', 'Problemas de longitud', '🧮', 'desafio_final', [
            reto('Una cuerda mide 3 m. ¿Cuántos centímetros son?',  ['300 cm', '30 cm', '3.000 cm'], '300 cm'),
            reto('Corto 40 cm de una cinta de 1 m. ¿Cuánto queda?', ['60 cm', '160 cm', '6 cm'], '60 cm'),
            reto('Camino 500 m de ida y 500 m de vuelta. ¿Cuánto en total?', ['1 km', '100 m', '10 km'], '1 km'),
            reto('Un libro mide 25 cm y otro 18 cm. ¿Cuánto más mide el primero?', ['7 cm', '43 cm', '13 cm'], '7 cm'),
            reto('¿Qué es más largo, 150 cm o 1,5 m?',              ['Miden lo mismo', '150 cm', '1,5 m'], 'Miden lo mismo'),
        ]),
    ],
],

[
    'slug'  => 'perimetro-y-area',
    'title' => 'Perímetro y área',
    'description' => 'El contorno y la superficie: dos medidas distintas que se confunden todo el tiempo.',
    'objective' => 'Diferenciar perímetro de área y calcular ambos en rectángulos y cuadrados.',
    'icon' => '🟩', 'nivel' => 'primaria-media', 'bloque' => 'medida-y-magnitudes',
    'duracion' => 14, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('Contorno o superficie', 'La diferencia, de una vez por todas', '🔲', 'opcion_multiple', [
            omp('¿Qué es el perímetro?',        ['La medida del contorno', 'La superficie de adentro', 'La altura'], 'La medida del contorno'),
            omp('¿Qué es el área?',             ['La superficie que ocupa', 'La medida del contorno', 'El número de lados'], 'La superficie que ocupa'),
            omp('Para cercar un terreno necesito calcular…', ['El perímetro', 'El área', 'El volumen'], 'El perímetro'),
            omp('Para poner baldosas en el piso necesito…',  ['El área', 'El perímetro', 'La altura'], 'El área'),
            omp('¿En qué se mide el área?',     ['Unidades cuadradas', 'Centímetros', 'Litros'], 'Unidades cuadradas'),
        ]),

        est('Calcula el perímetro', 'Suma todos los lados', '➕', 'opcion_multiple', [
            omp('Un cuadrado de lado 5 cm. ¿Perímetro?',          ['20 cm', '25 cm', '10 cm'], '20 cm'),
            omp('Un rectángulo de 6 cm y 3 cm. ¿Perímetro?',      ['18 cm', '9 cm', '24 cm'], '18 cm'),
            omp('Un triángulo de lados 4, 5 y 6 cm. ¿Perímetro?', ['15 cm', '20 cm', '120 cm'], '15 cm'),
            omp('Un cuadrado de lado 10 m. ¿Perímetro?',          ['40 m', '100 m', '20 m'], '40 m'),
            omp('Un rectángulo de 8 m y 2 m. ¿Perímetro?',        ['20 m', '16 m', '10 m'], '20 m'),
        ]),

        est('Calcula el área', 'Base por altura', '✖️', 'opcion_multiple', [
            omp('Un cuadrado de lado 5 cm. ¿Área?',           ['25 cm²', '20 cm²', '10 cm²'], '25 cm²'),
            omp('Un rectángulo de 6 cm y 3 cm. ¿Área?',       ['18 cm²', '9 cm²', '36 cm²'], '18 cm²'),
            omp('Un rectángulo de 10 m y 4 m. ¿Área?',        ['40 m²', '28 m²', '14 m²'], '40 m²'),
            omp('Un cuadrado de lado 7 cm. ¿Área?',           ['49 cm²', '28 cm²', '14 cm²'], '49 cm²'),
            omp('Un rectángulo de 12 cm y 5 cm. ¿Área?',      ['60 cm²', '34 cm²', '17 cm²'], '60 cm²'),
        ]),

        est('No siempre van juntos', 'Mismo perímetro, distinta área', '🤯', 'desafio_final', [
            reto('Un rectángulo de 6×2 y otro de 4×4. ¿Cuál tiene más área?', ['El de 4×4', 'El de 6×2', 'Igual'], 'El de 4×4'),
            reto('Esos dos rectángulos, ¿tienen el mismo perímetro?',        ['Sí, los dos 16', 'No', 'Falta información'], 'Sí, los dos 16'),
            reto('Si duplico el lado de un cuadrado, el área…',              ['Se hace cuatro veces mayor', 'Se duplica', 'No cambia'], 'Se hace cuatro veces mayor'),
            reto('Si duplico el lado de un cuadrado, el perímetro…',         ['Se duplica', 'Se cuadruplica', 'No cambia'], 'Se duplica'),
            reto('Una sala de 5 m × 4 m. ¿Cuántas baldosas de 1 m² caben?',  ['20', '18', '9'], '20'),
        ]),
    ],
],

[
    'slug'  => 'masa-capacidad-y-tiempo',
    'title' => 'Masa, capacidad y tiempo',
    'description' => 'Gramos y kilos, litros y mililitros, horas y minutos: medir lo que no es largo.',
    'objective' => 'Reconocer y convertir unidades de masa, capacidad y tiempo en situaciones cotidianas.',
    'icon' => '⚖️', 'nivel' => 'primaria-media', 'bloque' => 'medida-y-magnitudes',
    'duracion' => 14, 'tags' => ['calculo', 'observacion'],
    'estaciones' => [

        est('¿Con qué se mide?', 'Cada magnitud tiene su unidad', '🧰', 'opcion_multiple', [
            omp('¿En qué se mide el peso de una sandía?',   ['Kilogramos', 'Litros', 'Metros'], 'Kilogramos', '🍉'),
            omp('¿En qué se mide la leche de una botella?', ['Litros', 'Kilos', 'Centímetros'], 'Litros', '🥛'),
            omp('¿En qué se mide una clase de colegio?',    ['Minutos', 'Litros', 'Gramos'], 'Minutos', '🕐'),
            omp('¿En qué se mide una cucharada de sal?',    ['Gramos', 'Kilómetros', 'Horas'], 'Gramos', '🧂'),
            omp('¿En qué se mide el jarabe de una cuchara?', ['Mililitros', 'Litros', 'Kilos'], 'Mililitros', '🥄'),
        ]),

        est('Equivalencias', 'Convertir sin equivocarse', '🔄', 'opcion_multiple', [
            omp('¿Cuántos gramos tiene un kilogramo?',   ['1.000', '100', '10'], '1.000'),
            omp('¿Cuántos mililitros tiene un litro?',   ['1.000', '100', '10'], '1.000'),
            omp('¿Cuántos minutos tiene una hora?',      ['60', '100', '30'], '60'),
            omp('¿Cuántas horas tiene un día?',          ['24', '12', '60'], '24'),
            omp('Medio kilo son…',                       ['500 g', '50 g', '5.000 g'], '500 g'),
        ]),

        est('Estima el peso', '¿Cuánto pesa más o menos?', '🤔', 'opcion_multiple', [
            omp('¿Cuánto pesa aproximadamente una manzana?', ['150 gramos', '15 kilos', '1 gramo'], '150 gramos', '🍎'),
            omp('¿Cuánto pesa aproximadamente un niño de 8 años?', ['28 kilos', '280 kilos', '28 gramos'], '28 kilos', '🧒'),
            omp('¿Cuánto pesa aproximadamente un elefante?', ['4.000 kilos', '400 gramos', '40 kilos'], '4.000 kilos', '🐘'),
            omp('¿Cuánto cabe en un vaso?',                 ['250 mililitros', '250 litros', '25 litros'], '250 mililitros', '🥤'),
            omp('¿Cuánto cabe en una piscina?',             ['Miles de litros', 'Un litro', 'Diez mililitros'], 'Miles de litros', '🏊'),
        ]),

        est('El reloj y el calendario', 'Medir el tiempo', '🕐', 'opcion_multiple', [
            omp('¿Cuántos días tiene una semana?',          ['7', '5', '30'], '7'),
            omp('¿Cuántos meses tiene un año?',             ['12', '10', '52'], '12'),
            omp('Si son las 3:00 y pasan 45 minutos, son las…', ['3:45', '4:45', '3:15'], '3:45'),
            omp('Media hora son…',                          ['30 minutos', '50 minutos', '15 minutos'], '30 minutos'),
            omp('Un cuarto de hora son…',                   ['15 minutos', '25 minutos', '40 minutos'], '15 minutos'),
        ]),

        est('El dinero', 'Contar y dar vueltas', '💰', 'desafio_final', [
            reto('Compro algo de $3.500 y pago con $5.000. ¿Cuánto me devuelven?', ['$1.500', '$2.500', '$8.500'], '$1.500'),
            reto('Tres panes de $1.200 cada uno cuestan…',   ['$3.600', '$1.203', '$2.400'], '$3.600'),
            reto('Tengo $10.000 y gasto $6.800. ¿Cuánto queda?', ['$3.200', '$4.200', '$16.800'], '$3.200'),
            reto('¿Cuántos billetes de $2.000 hacen $10.000?', ['5', '4', '20'], '5'),
            reto('Ahorro $500 cada día. ¿Cuánto tengo en una semana?', ['$3.500', '$5.000', '$500'], '$3.500'),
        ]),
    ],
],

[
    'slug'  => 'volumen-y-capacidad',
    'title' => 'Volumen y capacidad',
    'description' => 'Cuánto espacio ocupa un cuerpo y cuánto le cabe dentro. Unidades cúbicas.',
    'objective' => 'Comprender el volumen como unidades cúbicas y relacionarlo con la capacidad.',
    'icon' => '🧊', 'nivel' => 'primaria-superior', 'bloque' => 'medida-y-magnitudes',
    'duracion' => 14, 'tags' => ['calculo', 'logica'],
    'estaciones' => [

        est('¿Qué es el volumen?', 'El espacio que ocupa un cuerpo', '📦', 'opcion_multiple', [
            omp('¿Qué mide el volumen?',                 ['El espacio que ocupa un cuerpo', 'El contorno', 'La superficie'], 'El espacio que ocupa un cuerpo'),
            omp('¿En qué unidades se mide el volumen?',  ['Unidades cúbicas', 'Unidades cuadradas', 'Centímetros'], 'Unidades cúbicas'),
            omp('¿Cuál es la fórmula del volumen de un ortoedro?', ['Largo × ancho × alto', 'Largo × ancho', 'Largo + ancho + alto'], 'Largo × ancho × alto'),
            omp('¿Cuánto vale el volumen de un cubo de arista 2 cm?', ['8 cm³', '4 cm³', '6 cm³'], '8 cm³'),
            omp('¿Cuánto vale el volumen de una caja de 3×2×4 cm?', ['24 cm³', '9 cm³', '18 cm³'], '24 cm³'),
        ]),

        est('Volumen y capacidad', 'Dos caras de lo mismo', '🥤', 'opcion_multiple', [
            omp('¿A cuántos mililitros equivale 1 cm³?',   ['1 mL', '10 mL', '100 mL'], '1 mL'),
            omp('¿A cuántos litros equivale 1.000 cm³?',   ['1 L', '10 L', '0,1 L'], '1 L'),
            omp('Una caja de 10×10×10 cm contiene…',       ['1 litro', '10 litros', '100 litros'], '1 litro'),
            omp('¿Qué mide la capacidad?',                 ['Cuánto cabe dentro', 'Cuánto pesa', 'Cuánto mide de largo'], 'Cuánto cabe dentro'),
            omp('Media botella de 2 L tiene…',             ['1.000 mL', '200 mL', '20 mL'], '1.000 mL'),
        ]),

        est('Calcula el volumen', 'Multiplica las tres dimensiones', '✖️', 'opcion_multiple', [
            omp('Una caja de 5×4×2 cm tiene volumen…',   ['40 cm³', '11 cm³', '20 cm³'], '40 cm³'),
            omp('Un cubo de arista 3 cm tiene volumen…', ['27 cm³', '9 cm³', '18 cm³'], '27 cm³'),
            omp('Una caja de 10×5×2 cm tiene volumen…',  ['100 cm³', '17 cm³', '50 cm³'], '100 cm³'),
            omp('Un cubo de arista 1 m tiene volumen…',  ['1 m³', '3 m³', '6 m³'], '1 m³'),
            omp('Una caja de 6×2×2 cm tiene volumen…',   ['24 cm³', '10 cm³', '12 cm³'], '24 cm³'),
        ]),

        est('Desafío del espacio', 'Volumen en la vida real', '🏆', 'desafio_final', [
            reto('¿Qué ocupa más espacio: un cubo de 2 cm o uno de 3 cm de arista?', ['El de 3 cm', 'El de 2 cm', 'Igual'], 'El de 3 cm'),
            reto('Un acuario de 50×30×20 cm. ¿Cuántos litros son?', ['30 litros', '100 litros', '3 litros'], '30 litros'),
            reto('¿Cuántos cubos de 1 cm³ caben en una caja de 2×2×2?', ['8', '6', '4'], '8'),
            reto('Si duplico las tres aristas de un cubo, el volumen…', ['Se hace 8 veces mayor', 'Se duplica', 'Se cuadruplica'], 'Se hace 8 veces mayor'),
            reto('Una jeringa de 5 mL contiene…',                 ['5 cm³', '50 cm³', '0,5 cm³'], '5 cm³'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · DATOS Y AZAR
// =====================================================================

[
    'slug'  => 'tablas-y-graficas',
    'title' => 'Tablas y gráficas',
    'description' => 'Recoger datos, ordenarlos en una tabla y leerlos en pictogramas y barras.',
    'objective' => 'Organizar datos en tablas de frecuencia e interpretar pictogramas y diagramas de barras.',
    'icon' => '📊', 'nivel' => 'primaria-media', 'bloque' => 'datos-y-azar',
    'duracion' => 14, 'tags' => ['observacion', 'logica', 'calculo'],
    'estaciones' => [

        est('¿Para qué sirven los datos?', 'Contar para decidir', '🤔', 'opcion_multiple', [
            omp('¿Para qué sirve una encuesta?',          ['Para saber qué opina un grupo', 'Para hacer cuentas', 'Para dibujar'], 'Para saber qué opina un grupo'),
            omp('¿Qué es la frecuencia de un dato?',      ['Cuántas veces aparece', 'Cuánto vale', 'Su posición'], 'Cuántas veces aparece'),
            omp('En una tabla de frecuencia, ¿qué va en la primera columna?', ['Las opciones', 'Los totales', 'Los nombres'], 'Las opciones'),
            omp('Si 8 niños prefieren el fútbol y 3 el básquet, ¿cuál gana?', ['Fútbol', 'Básquet', 'Empatan'], 'Fútbol'),
            omp('¿Cuántos niños respondieron en total en ese caso?', ['11', '8', '5'], '11'),
        ]),

        /*
         * Los pictogramas van con clave («cada dibujo vale 2»), que es el
         * punto entero del pictograma y donde se equivocan todos: leen el
         * número de dibujos en vez del valor que representan.
         */
        est('Leer un pictograma', 'Cada dibujo vale varios', '🖼️', 'opcion_multiple', [
            omp('Si cada 🍎 vale 2 frutas y hay 4 dibujos, ¿cuántas frutas son?', ['8', '4', '6'], '8'),
            omp('Si cada ⭐ vale 5 puntos y hay 3 estrellas, ¿cuántos puntos?',   ['15', '8', '3'], '15'),
            omp('Si cada 🚗 vale 10 autos y hay 7 dibujos, ¿cuántos autos?',      ['70', '17', '7'], '70'),
            omp('¿Qué es la clave de un pictograma?',   ['Cuánto vale cada dibujo', 'El título', 'El número de filas'], 'Cuánto vale cada dibujo'),
            omp('Si cada 📚 vale 4 libros y quiero mostrar 12, ¿cuántos dibujo?', ['3', '4', '12'], '3'),
        ]),

        est('Leer un diagrama de barras', 'La barra más alta gana', '📈', 'opcion_multiple', [
            omp('En un diagrama de barras, la barra más alta indica…', ['El dato más frecuente', 'El menos frecuente', 'El promedio'], 'El dato más frecuente'),
            omp('¿Qué se escribe en los ejes?',   ['Las etiquetas y la escala', 'Nada', 'El título'], 'Las etiquetas y la escala'),
            omp('Si la escala va de 2 en 2 y la barra llega al cuarto renglón, vale…', ['8', '4', '2'], '8'),
            omp('¿Qué gráfica sirve para mostrar partes de un total?', ['Circular', 'De barras', 'De línea'], 'Circular'),
            omp('¿Qué gráfica sirve para mostrar un cambio en el tiempo?', ['De línea', 'Circular', 'Pictograma'], 'De línea'),
        ]),

        est('Ordena los pasos', 'Cómo se hace un estudio de datos', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para estudiar unos datos',
            'items' => ['Preguntar', 'Recoger los datos', 'Organizar la tabla', 'Dibujar la gráfica', 'Sacar conclusiones'],
        ]),
    ],
],

[
    'slug'  => 'promedios-y-medidas',
    'title' => 'Promedios y medidas',
    'description' => 'Media, mediana, moda y rango: cuatro formas de resumir muchos datos en un número.',
    'objective' => 'Calcular e interpretar la media, la mediana, la moda y el rango de un conjunto de datos.',
    'icon' => '📉', 'nivel' => 'primaria-superior', 'bloque' => 'datos-y-azar',
    'duracion' => 15, 'tags' => ['calculo', 'logica', 'deduccion'],
    'estaciones' => [

        est('¿Qué es cada una?', 'Cuatro medidas distintas', '🗂️', 'opcion_multiple', [
            omp('¿Qué es la moda?',     ['El dato que más se repite', 'El del medio', 'El promedio'], 'El dato que más se repite'),
            omp('¿Qué es la mediana?',  ['El dato del medio al ordenarlos', 'El que más se repite', 'La suma de todos'], 'El dato del medio al ordenarlos'),
            omp('¿Qué es la media?',    ['La suma dividida entre la cantidad', 'El mayor', 'El menor'], 'La suma dividida entre la cantidad'),
            omp('¿Qué es el rango?',    ['La diferencia entre el mayor y el menor', 'El total', 'El promedio'], 'La diferencia entre el mayor y el menor'),
            omp('Antes de hallar la mediana hay que…', ['Ordenar los datos', 'Sumarlos', 'Multiplicarlos'], 'Ordenar los datos'),
        ]),

        est('Calcula la media', 'Suma y reparte', '➗', 'opcion_multiple', [
            omp('Media de 2, 4 y 6',        ['4', '12', '3'], '4'),
            omp('Media de 5, 5, 5 y 5',     ['5', '20', '4'], '5'),
            omp('Media de 10 y 20',         ['15', '30', '10'], '15'),
            omp('Media de 3, 7, 8 y 2',     ['5', '20', '4'], '5'),
            omp('Media de 100, 200 y 300',  ['200', '600', '150'], '200'),
        ]),

        est('Moda, mediana y rango', 'Sobre los mismos datos', '🔍', 'opcion_multiple', [
            omp('En 3, 5, 5, 7, 9 la moda es…',      ['5', '7', '9'], '5'),
            omp('En 3, 5, 5, 7, 9 la mediana es…',   ['5', '7', '3'], '5'),
            omp('En 3, 5, 5, 7, 9 el rango es…',     ['6', '9', '5'], '6'),
            omp('En 2, 4, 6, 8 la mediana es…',      ['5', '4', '6'], '5'),
            omp('En 1, 1, 2, 8 la moda es…',         ['1', '2', '8'], '1'),
        ]),

        est('¿Cuál uso?', 'Elegir la medida adecuada', '🧠', 'desafio_final', [
            reto('Quiero saber la talla de zapato más vendida. ¿Qué uso?', ['La moda', 'La media', 'El rango'], 'La moda'),
            reto('Quiero la nota promedio del curso. ¿Qué uso?',          ['La media', 'La moda', 'El rango'], 'La media'),
            reto('Quiero saber cuánto se separan el más alto y el más bajo. ¿Qué uso?', ['El rango', 'La media', 'La moda'], 'El rango'),
            reto('Si un dato es enormemente grande, ¿qué medida se distorsiona más?', ['La media', 'La moda', 'La mediana'], 'La media'),
            reto('¿Puede un conjunto de datos tener dos modas?',          ['Sí', 'No', 'Solo con números'], 'Sí'),
        ]),
    ],
],

[
    'slug'  => 'probabilidad-y-azar',
    'title' => 'Probabilidad y azar',
    'description' => 'Seguro, posible o imposible: predecir lo que puede pasar y expresarlo como fracción.',
    'objective' => 'Clasificar eventos como seguros, posibles o imposibles y calcular probabilidades sencillas.',
    'icon' => '🎲', 'nivel' => 'primaria-superior', 'bloque' => 'datos-y-azar',
    'duracion' => 14, 'tags' => ['logica', 'deduccion', 'calculo'],
    'estaciones' => [

        est('Seguro, posible o imposible', 'Tres formas de que algo ocurra', '🔮', 'opcion_multiple', [
            omp('Mañana saldrá el sol. Eso es…',                 ['Seguro', 'Imposible', 'Poco probable'], 'Seguro', '☀️'),
            omp('Sacar un 7 en un dado de 6 caras. Eso es…',     ['Imposible', 'Seguro', 'Posible'], 'Imposible', '🎲'),
            omp('Que llueva el sábado. Eso es…',                 ['Posible', 'Seguro', 'Imposible'], 'Posible', '🌧️'),
            omp('Sacar un número menor que 7 en un dado. Eso es…', ['Seguro', 'Imposible', 'Poco probable'], 'Seguro', '🎲'),
            omp('Que un gato hable español. Eso es…',            ['Imposible', 'Posible', 'Seguro'], 'Imposible', '🐱'),
        ]),

        est('Determinístico o aleatorio', 'Cuando el resultado ya está decidido', '⚙️', 'opcion_multiple', [
            omp('Soltar una piedra y que caiga. Eso es…',        ['Determinístico', 'Aleatorio', 'Imposible'], 'Determinístico'),
            omp('Lanzar una moneda. Eso es…',                    ['Aleatorio', 'Determinístico', 'Seguro'], 'Aleatorio', '🪙'),
            omp('Sacar una bola de una bolsa sin mirar. Eso es…', ['Aleatorio', 'Determinístico', 'Imposible'], 'Aleatorio'),
            omp('Sumar 2 + 2. Eso es…',                          ['Determinístico', 'Aleatorio', 'Probable'], 'Determinístico'),
            omp('Girar una ruleta. Eso es…',                     ['Aleatorio', 'Determinístico', 'Seguro'], 'Aleatorio'),
        ]),

        est('Calcula la probabilidad', 'Casos favorables entre casos posibles', '🧮', 'opcion_multiple', [
            omp('Probabilidad de cara al lanzar una moneda',       ['1/2', '1/6', '1'], '1/2', '🪙'),
            omp('Probabilidad de sacar un 3 en un dado',           ['1/6', '1/3', '3/6'], '1/6', '🎲'),
            omp('Probabilidad de sacar un número par en un dado',  ['3/6', '1/6', '2/6'], '3/6', '🎲'),
            omp('En una bolsa con 2 bolas rojas y 3 azules, sacar roja es…', ['2/5', '3/5', '1/2'], '2/5'),
            omp('¿Cuál es la probabilidad de un evento imposible?', ['0', '1', '1/2'], '0'),
        ]),

        est('Predice el resultado', 'Usar la probabilidad para decidir', '🏆', 'desafio_final', [
            reto('¿Cuál es la probabilidad de un evento seguro?',              ['1', '0', '1/2'], '1'),
            reto('Bolsa con 8 bolas blancas y 2 negras. ¿Qué sale más fácil?', ['Blanca', 'Negra', 'Igual'], 'Blanca'),
            reto('Si lanzo una moneda 10 veces y salen 10 caras, la próxima…', ['Sigue siendo 1/2', 'Será sello seguro', 'Será cara seguro'], 'Sigue siendo 1/2'),
            reto('¿Cuántos resultados posibles tiene lanzar dos monedas?',     ['4', '2', '3'], '4'),
            reto('Ruleta de 4 colores iguales. ¿Probabilidad de uno?',         ['1/4', '1/2', '4'], '1/4'),
        ]),
    ],
],

[
    'slug'  => 'conjuntos',
    'title' => 'Conjuntos',
    'description' => 'Agrupar por una característica, y qué pasa cuando dos grupos se cruzan o se excluyen.',
    'objective' => 'Clasificar y comparar conjuntos y resolver problemas de unión e intersección.',
    'icon' => '⭕', 'nivel' => 'primaria-superior', 'bloque' => 'datos-y-azar',
    'duracion' => 14, 'tags' => ['clasificacion', 'logica', 'deduccion'],
    'estaciones' => [

        est('¿Qué es un conjunto?', 'Un grupo con una regla clara', '📁', 'opcion_multiple', [
            omp('¿Qué es un conjunto?',                    ['Un grupo de elementos con una característica común', 'Un número grande', 'Una operación'], 'Un grupo de elementos con una característica común'),
            omp('¿Cuál NO pertenece al conjunto de los animales?', ['Mesa', 'Perro', 'Gato'], 'Mesa'),
            omp('¿Cuál NO pertenece al conjunto de los números pares?', ['7', '4', '10'], '7'),
            omp('¿Cómo se llama cada cosa dentro de un conjunto?', ['Elemento', 'Grupo', 'Número'], 'Elemento'),
            omp('Un conjunto sin ningún elemento se llama…', ['Vacío', 'Lleno', 'Unitario'], 'Vacío'),
        ]),

        est('Unión e intersección', 'Todo junto, o solo lo que comparten', '🔗', 'opcion_multiple', [
            omp('¿Qué es la unión de dos conjuntos?',      ['Todos los elementos de ambos', 'Solo los comunes', 'Los que no están'], 'Todos los elementos de ambos'),
            omp('¿Qué es la intersección?',                ['Solo los elementos comunes', 'Todos los elementos', 'Los excluidos'], 'Solo los elementos comunes'),
            omp('A = {1,2,3} y B = {3,4}. ¿Cuál es la intersección?', ['{3}', '{1,2,3,4}', '{ }'], '{3}'),
            omp('A = {1,2} y B = {3,4}. ¿Cuál es la unión?', ['{1,2,3,4}', '{ }', '{1,2}'], '{1,2,3,4}'),
            omp('Si dos conjuntos no comparten nada, su intersección es…', ['Vacía', 'Igual a la unión', 'El mayor'], 'Vacía'),
        ]),

        est('Clasifica en grupos', 'Cada cosa a su conjunto', '🗂️', 'opcion_multiple', [
            omp('¿A qué conjunto pertenece el 12?',        ['Los pares', 'Los impares', 'Los primos'], 'Los pares'),
            omp('¿A qué conjunto pertenece el triángulo?', ['Los polígonos', 'Los cuerpos redondos', 'Los números'], 'Los polígonos'),
            omp('¿Cuál pertenece a los múltiplos de 5?',   ['25', '23', '27'], '25'),
            omp('¿Cuál pertenece a las vocales?',          ['E', 'M', 'T'], 'E'),
            omp('¿Cuál pertenece a los mamíferos?',        ['Ballena', 'Rana', 'Serpiente'], 'Ballena'),
        ]),

        est('Problemas de conjuntos', 'Usar los diagramas para decidir', '🏆', 'desafio_final', [
            reto('20 niños juegan fútbol, 15 básquet y 5 los dos. ¿Cuántos juegan fútbol solamente?', ['15', '20', '5'], '15'),
            reto('En el mismo caso, ¿cuántos niños hay en total?',   ['30', '35', '40'], '30'),
            reto('Si un elemento está en A y en B, está en…',        ['La intersección', 'Solo A', 'Ninguno'], 'La intersección'),
            reto('¿Puede un conjunto tener un solo elemento?',       ['Sí, es unitario', 'No', 'Solo si es número'], 'Sí, es unitario'),
            reto('Los perros están dentro del conjunto de los mamíferos. Eso es…', ['Un subconjunto', 'Una intersección vacía', 'Una unión'], 'Un subconjunto'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN · lo que la malla pide y no cabía en la primera pasada
// =====================================================================

[
    'slug'  => 'patrones-y-regularidades',
    'title' => 'Patrones y regularidades',
    'description' => 'Descubrir la regla que gobierna una serie y usarla para predecir lo que sigue.',
    'objective' => 'Identificar la regla de formación de patrones numéricos y geométricos y generalizarla.',
    'icon' => '🔁', 'nivel' => 'primaria-media', 'bloque' => 'calculo-mental',
    'duracion' => 13, 'tags' => ['patrones', 'logica', 'calculo'],
    'estaciones' => [

        est('¿Cuál es la regla?', 'Descubre cómo avanza la serie', '🔍', 'opcion_multiple', [
            omp('2, 4, 6, 8… ¿cuál es la regla?',     ['Sumar 2', 'Sumar 1', 'Multiplicar por 2'], 'Sumar 2'),
            omp('5, 10, 15, 20… ¿cuál es la regla?',  ['Sumar 5', 'Sumar 10', 'Multiplicar por 5'], 'Sumar 5'),
            omp('100, 90, 80… ¿cuál es la regla?',    ['Restar 10', 'Sumar 10', 'Dividir entre 10'], 'Restar 10'),
            omp('1, 2, 4, 8… ¿cuál es la regla?',     ['Multiplicar por 2', 'Sumar 2', 'Sumar 1'], 'Multiplicar por 2'),
            omp('¿Para qué sirve encontrar la regla?', ['Para predecir cualquier término', 'Para nada', 'Para contar más rápido solamente'], 'Para predecir cualquier término'),
        ]),

        est('Completa la serie', 'Aplica la regla que descubriste', '🔢', 'secuencia_numerica', [
            serie([4, 8, 12, null, 20]),
            serie([9, 18, 27, null, 45]),
            serie([50, 45, null, 35, 30]),
            serie([6, 12, 18, null, 30]),
            serie([200, 175, null, 125, 100]),
        ]),

        est('Patrones con figuras', 'La regla también sirve para formas', '🔷', 'opcion_multiple', [
            omp('🔺 🟦 🔺 🟦 … ¿qué sigue?',         ['🔺', '🟦', '⭐'], '🔺'),
            omp('⬜ ⬜ ⬛ ⬜ ⬜ ⬛ … ¿qué sigue?',     ['⬜', '⬛', '🟦'], '⬜'),
            omp('1 punto, 3 puntos, 5 puntos… ¿cuántos siguen?', ['7', '6', '8'], '7'),
            omp('Un triángulo tiene 3 lados, un cuadrado 4, un pentágono 5. ¿Y un hexágono?', ['6', '7', '5'], '6'),
            omp('¿Qué tienen en común los patrones numéricos y los de figuras?', ['Los dos siguen una regla', 'Nada', 'Los dos son dibujos'], 'Los dos siguen una regla'),
        ]),

        est('Generalizar', 'De los ejemplos a la regla', '🧠', 'desafio_final', [
            reto('Si la regla es «sumar 3» y empiezo en 2, ¿cuál es el quinto término?', ['14', '12', '15'], '14'),
            reto('En la serie 10, 20, 30… ¿cuál es el término número 10?', ['100', '90', '110'], '100'),
            reto('Si cada mesa tiene 4 sillas, ¿cuántas sillas hay en 7 mesas?', ['28', '11', '24'], '28'),
            reto('Si un patrón se repite cada 3 elementos, el elemento 9 será igual al…', ['3', '2', '1'], '3'),
            reto('¿Qué es generalizar en matemáticas?', ['Encontrar una regla que sirva para todos los casos', 'Hacer muchos ejemplos', 'Adivinar'], 'Encontrar una regla que sirva para todos los casos'),
        ]),
    ],
],

[
    'slug'  => 'estimar-y-aproximar',
    'title' => 'Estimar y aproximar',
    'description' => 'Calcular «más o menos» antes de calcular exacto, para saber si la respuesta va bien.',
    'objective' => 'Aplicar la estimación como estrategia de control del resultado en situaciones de cálculo.',
    'icon' => '🎯', 'nivel' => 'primaria-media', 'bloque' => 'calculo-mental',
    'duracion' => 12, 'tags' => ['calculo', 'logica', 'observacion'],
    'estaciones' => [

        est('¿Más o menos cuánto?', 'Aproximar sin calcular exacto', '🤔', 'opcion_multiple', [
            omp('198 + 203 es aproximadamente…',   ['400', '300', '500'], '400'),
            omp('49 × 2 es aproximadamente…',      ['100', '50', '200'], '100'),
            omp('997 − 495 es aproximadamente…',   ['500', '400', '1.000'], '500'),
            omp('9,8 + 10,1 es aproximadamente…',  ['20', '10', '30'], '20'),
            omp('¿Para qué sirve estimar antes?',  ['Para detectar si el resultado exacto está muy lejos', 'Para ir más lento', 'Para no calcular'], 'Para detectar si el resultado exacto está muy lejos'),
        ]),

        est('¿Es razonable?', 'Detectar resultados imposibles', '🚨', 'opcion_multiple', [
            omp('«48 + 51 = 990». ¿Es razonable?',   ['No, debería estar cerca de 100', 'Sí', 'Falta información'], 'No, debería estar cerca de 100'),
            omp('«6 × 7 = 420». ¿Es razonable?',     ['No, debería estar cerca de 42', 'Sí', 'Depende'], 'No, debería estar cerca de 42'),
            omp('«100 ÷ 4 = 25». ¿Es razonable?',    ['Sí', 'No', 'Imposible saberlo'], 'Sí'),
            omp('«Un lápiz cuesta 900.000 pesos». ¿Es razonable?', ['No', 'Sí', 'Depende del color'], 'No'),
            omp('«Un salón mide 8 metros de largo». ¿Es razonable?', ['Sí', 'No', 'Imposible'], 'Sí'),
        ]),

        est('Estimar en la vida real', 'Cuentas de todos los días', '🛒', 'opcion_multiple', [
            omp('Llevo 3 cosas de unos 5.000 cada una. ¿Cuánto pagaré más o menos?', ['15.000', '5.000', '50.000'], '15.000'),
            omp('Tengo 20.000 y quiero 4 cosas de 6.000. ¿Me alcanza?', ['No, faltarían unos 4.000', 'Sí, sobra', 'Justo'], 'No, faltarían unos 4.000'),
            omp('Si un paso mide medio metro, ¿cuántos pasos son 10 metros?', ['20', '10', '5'], '20'),
            omp('Un salón tiene 30 estudiantes y hay 6 salones. ¿Cuántos hay más o menos?', ['180', '36', '300'], '180'),
            omp('¿Cuándo conviene el resultado exacto en vez del estimado?', ['Cuando hay que pagar o medir con precisión', 'Nunca', 'Siempre el estimado'], 'Cuando hay que pagar o medir con precisión'),
        ]),

        est('Redondea para estimar', 'Números cómodos', '🔄', 'opcion_multiple', [
            omp('Para estimar 48 + 33, redondeo a…',  ['50 + 30', '40 + 30', '50 + 40'], '50 + 30'),
            omp('Para estimar 197 × 2, redondeo a…',  ['200 × 2', '100 × 2', '190 × 2'], '200 × 2'),
            omp('Redondea 6,8 al entero más cercano', ['7', '6', '6,5'], '7'),
            omp('Redondea 4,2 al entero más cercano', ['4', '5', '4,5'], '4'),
            omp('¿Da lo mismo el resultado estimado y el exacto?', ['No, el estimado es aproximado', 'Sí', 'A veces sí siempre'], 'No, el estimado es aproximado'),
        ]),
    ],
],

[
    'slug'  => 'igualdades-y-ecuaciones',
    'title' => 'Igualdades y ecuaciones',
    'description' => 'El signo igual como equilibrio, y cómo encontrar el número escondido.',
    'objective' => 'Interpretar la igualdad como equivalencia y resolver ecuaciones sencillas.',
    'icon' => '❓', 'nivel' => 'primaria-superior', 'bloque' => 'calculo-mental',
    'duracion' => 13, 'tags' => ['calculo', 'logica', 'deduccion'],
    'estaciones' => [

        /*
         * El error más extendido de toda la primaria: leer «=» como «aquí
         * va el resultado» en vez de «los dos lados valen lo mismo». Quien
         * lo lee mal escribe 3+4=7+2=9 sin ver el problema, y años después
         * no entiende por qué una ecuación se resuelve haciendo lo mismo a
         * los dos lados.
         */
        est('El signo igual', 'No significa «aquí va el resultado»', '⚖️', 'opcion_multiple', [
            omp('¿Qué significa el signo =?',        ['Que los dos lados valen lo mismo', 'Que aquí va el resultado', 'Que hay que sumar'], 'Que los dos lados valen lo mismo'),
            omp('¿Es correcto escribir 3 + 4 = 7 + 2 = 9?', ['No, 7 no es igual a 9', 'Sí', 'Depende'], 'No, 7 no es igual a 9'),
            omp('¿Es cierto que 5 + 3 = 4 + 4?',     ['Sí, los dos dan 8', 'No', 'Falta información'], 'Sí, los dos dan 8'),
            omp('¿Es cierto que 10 − 2 = 2 × 4?',    ['Sí, los dos dan 8', 'No', 'Solo el primero'], 'Sí, los dos dan 8'),
            omp('Si añado 3 a un lado de una igualdad, ¿qué hago con el otro?', ['Añadir 3 también', 'Nada', 'Restar 3'], 'Añadir 3 también'),
        ]),

        est('El número escondido', 'Encuentra la incógnita', '🔍', 'opcion_multiple', [
            omp('x + 5 = 12. ¿Cuánto vale x?',   ['7', '17', '5'], '7'),
            omp('x − 4 = 10. ¿Cuánto vale x?',   ['14', '6', '40'], '14'),
            omp('3 × x = 21. ¿Cuánto vale x?',   ['7', '18', '24'], '7'),
            omp('x ÷ 2 = 8. ¿Cuánto vale x?',    ['16', '4', '10'], '16'),
            omp('20 − x = 12. ¿Cuánto vale x?',  ['8', '32', '12'], '8'),
        ]),

        est('Desigualdades', 'Mayor, menor o igual', '📐', 'opcion_multiple', [
            omp('¿Qué signo va? 7 __ 5',         ['>', '<', '='], '>'),
            omp('¿Qué signo va? 12 __ 20',       ['<', '>', '='], '<'),
            omp('¿Qué signo va? 3 + 4 __ 7',     ['=', '>', '<'], '='),
            omp('¿Qué signo va? 5 × 2 __ 9',     ['>', '<', '='], '>'),
            omp('¿Qué número hace cierto 4 + __ > 10?', ['8', '5', '2'], '8'),
        ]),

        est('Completa el enunciado', 'Coloca cada palabra en su hueco', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Lee la frase entera antes de elegir', [
                [
                    'titulo' => 'Qué significa el signo igual',
                    'texto'  => 'El signo = no quiere decir «aquí va el ___». Quiere decir que los dos '
                              . 'lados valen ___. Por eso 5 + 3 = 4 + ___ es correcto: los dos lados dan '
                              . 'ocho. Y si añado algo a un lado de una igualdad, tengo que añadir lo '
                              . '___ al otro.',
                    'huecos' => ['resultado', 'lo mismo', 'cuatro', 'mismo'],
                    'extra'  => ['total', 'distinto', 'cinco'],
                ],
                [
                    'titulo' => 'Resolver una ecuación',
                    'texto'  => 'El valor que no conocemos se llama ___ y se escribe con una letra. '
                              . 'Para encontrarlo hay que ___ lo que se le hizo: si a un número le '
                              . 'sumaron 5 y dio 12, hay que ___ 5. Al terminar conviene ___ el '
                              . 'resultado en la ecuación original.',
                    'huecos' => ['incógnita', 'deshacer', 'restar', 'comprobar'],
                    'extra'  => ['solución', 'sumar', 'olvidar'],
                ],
            ])),

        est('Problemas con incógnita', 'Traduce el enunciado', '🏆', 'desafio_final', [
            reto('Pensé un número, le sumé 8 y me dio 20. ¿Cuál era?',    ['12', '28', '8'], '12'),
            reto('El triple de un número es 27. ¿Cuál es el número?',     ['9', '24', '81'], '9'),
            reto('Tenía dinero, gasté 5.000 y me quedan 12.000. ¿Cuánto tenía?', ['17.000', '7.000', '12.000'], '17.000'),
            reto('La mitad de un número es 14. ¿Cuál es el número?',      ['28', '7', '16'], '28'),
            reto('¿Qué es una incógnita?',                                ['El valor que hay que averiguar', 'El resultado', 'Un error'], 'El valor que hay que averiguar'),
        ]),
    ],
],

[
    'slug'  => 'graficas-y-representaciones',
    'title' => 'Gráficas y representaciones',
    'description' => 'Barras, líneas y circulares: qué gráfica sirve para cada tipo de dato.',
    'objective' => 'Elegir e interpretar el tipo de gráfica adecuado según los datos que se quieran mostrar.',
    'icon' => '🥧', 'nivel' => 'primaria-superior', 'bloque' => 'datos-y-azar',
    'duracion' => 13, 'tags' => ['observacion', 'logica', 'calculo'],
    'estaciones' => [

        est('¿Qué gráfica uso?', 'Cada una sirve para algo distinto', '🗂️', 'opcion_multiple', [
            omp('Quiero comparar cuántos prefieren cada deporte. ¿Qué uso?', ['Un diagrama de barras', 'Uno de línea', 'Ninguno'], 'Un diagrama de barras'),
            omp('Quiero mostrar cómo cambió la temperatura durante el día. ¿Qué uso?', ['Un diagrama de línea', 'Uno circular', 'Un pictograma'], 'Un diagrama de línea'),
            omp('Quiero mostrar qué parte del total representa cada grupo. ¿Qué uso?', ['Un diagrama circular', 'Uno de línea', 'Una tabla sola'], 'Un diagrama circular'),
            omp('¿Qué gráfica usa dibujos con una clave?', ['El pictograma', 'El de barras', 'El circular'], 'El pictograma'),
            omp('¿Qué debe llevar siempre una gráfica?',  ['Título y ejes etiquetados', 'Colores bonitos', 'Muchos datos'], 'Título y ejes etiquetados'),
        ]),

        est('Leer la gráfica', 'Sacar información de los datos', '🔍', 'opcion_multiple', [
            omp('En una circular, la mitad del círculo representa…',  ['El 50%', 'El 25%', 'El 100%'], 'El 50%'),
            omp('Un cuarto del círculo representa…',                  ['El 25%', 'El 50%', 'El 75%'], 'El 25%'),
            omp('Si todas las porciones suman, ¿cuánto dan?',         ['El 100%', 'El 50%', 'Depende'], 'El 100%'),
            omp('En un diagrama de línea que sube, los valores…',     ['Aumentan', 'Disminuyen', 'No cambian'], 'Aumentan'),
            omp('Si la barra de fútbol es el doble que la de tenis…', ['Lo prefiere el doble de gente', 'Es más alta sin más', 'Es un error'], 'Lo prefiere el doble de gente'),
        ]),

        est('Gráficas que engañan', 'No todo lo que se ve es cierto', '⚠️', 'opcion_multiple', [
            omp('Si una gráfica no empieza en cero, las diferencias…', ['Parecen más grandes de lo que son', 'Se ven igual', 'Desaparecen'], 'Parecen más grandes de lo que son'),
            omp('¿Qué hay que mirar siempre en una gráfica?',   ['La escala de los ejes', 'El color', 'El tamaño de la hoja'], 'La escala de los ejes'),
            omp('¿Puede una gráfica correcta dar una impresión falsa?', ['Sí, según cómo se presente', 'No', 'Solo si tiene errores'], 'Sí, según cómo se presente'),
            omp('Si faltan datos de algunos meses, la gráfica…', ['Puede llevar a conclusiones equivocadas', 'Sigue siendo completa', 'Es mejor'], 'Puede llevar a conclusiones equivocadas'),
            omp('¿Quién decide cómo se muestran los datos?',    ['Quien hace la gráfica, y eso influye', 'Los datos solos', 'Nadie'], 'Quien hace la gráfica, y eso influye'),
        ]),

        est('Porcentajes básicos', 'De cada cien', '💯', 'opcion_multiple', [
            omp('¿Cuánto es el 50% de 100?',   ['50', '25', '100'], '50'),
            omp('¿Cuánto es el 25% de 80?',    ['20', '25', '40'], '20'),
            omp('¿Cuánto es el 10% de 200?',   ['20', '10', '2'], '20'),
            omp('¿Cuánto es el 100% de 45?',   ['45', '100', '4.500'], '45'),
            omp('¿A qué fracción equivale el 50%?', ['1/2', '1/4', '5/10 y también 1/5'], '1/2'),
        ]),
    ],
],

[
    'slug'  => 'numeros-enteros',
    'title' => 'Números enteros',
    'description' => 'Números por debajo del cero: temperaturas bajo cero, deudas y pisos de sótano.',
    'objective' => 'Reconocer los números negativos y ubicarlos en la recta numérica.',
    'icon' => '➖', 'nivel' => 'primaria-superior', 'bloque' => 'valor-posicional',
    'duracion' => 12, 'tags' => ['calculo', 'logica', 'observacion'],
    'estaciones' => [

        est('Por debajo del cero', '¿Dónde se usan?', '🌡️', 'opcion_multiple', [
            omp('¿Qué significa −5 °C?',            ['Cinco grados bajo cero', 'Cinco grados de calor', 'Cinco grados exactos'], 'Cinco grados bajo cero'),
            omp('¿Qué es el piso −2 de un edificio?', ['Dos pisos bajo el nivel de la calle', 'El segundo piso', 'La azotea'], 'Dos pisos bajo el nivel de la calle'),
            omp('Si debo 10.000 pesos, mi saldo es…', ['−10.000', '10.000', '0'], '−10.000'),
            omp('¿Qué número está justo antes del 0?', ['−1', '1', '0,5'], '−1'),
            omp('¿Es el cero positivo o negativo?',   ['Ninguno de los dos', 'Positivo', 'Negativo'], 'Ninguno de los dos'),
        ]),

        est('Ordenar enteros', 'Cuanto más negativo, más pequeño', '📉', 'opcion_multiple', [
            omp('¿Cuál es mayor, −3 o 2?',      ['2', '−3', 'Iguales'], '2'),
            omp('¿Cuál es mayor, −1 o −7?',     ['−1', '−7', 'Iguales'], '−1'),
            omp('¿Cuál es menor, 0 o −4?',      ['−4', '0', 'Iguales'], '−4'),
            omp('¿Cuál hace más frío, −10 °C o −2 °C?', ['−10 °C', '−2 °C', 'Igual'], '−10 °C'),
            omp('En la recta numérica, los negativos están…', ['A la izquierda del cero', 'A la derecha', 'Arriba'], 'A la izquierda del cero'),
        ]),

        est('Sumar y restar enteros', 'Subir y bajar en la recta', '↕️', 'opcion_multiple', [
            omp('Estaba a −3 y subo 5. ¿Dónde estoy?',   ['2', '−8', '8'], '2'),
            omp('Estaba a 4 y bajo 7. ¿Dónde estoy?',    ['−3', '11', '3'], '−3'),
            omp('La temperatura era −2 y bajó 3. ¿Cuál es?', ['−5', '1', '5'], '−5'),
            omp('Estaba a −6 y subo 6. ¿Dónde estoy?',   ['0', '−12', '12'], '0'),
            omp('¿Cuántos grados hay de −5 °C a 5 °C?',  ['10', '0', '5'], '10'),
        ]),

        est('Desafío de los enteros', 'Cinco situaciones reales', '🏆', 'desafio_final', [
            reto('Un submarino está a −40 m y sube 15 m. ¿A qué profundidad queda?', ['−25 m', '−55 m', '25 m'], '−25 m'),
            reto('Amanece a −2 °C y sube 9 grados. ¿Qué temperatura hay?', ['7 °C', '11 °C', '−11 °C'], '7 °C'),
            reto('¿Cuál es el opuesto de 8?',       ['−8', '0', '1/8'], '−8'),
            reto('¿Qué número está entre −2 y 0?',  ['−1', '1', '−3'], '−1'),
            reto('¿Para qué sirven los números negativos?', ['Para medir lo que está por debajo de una referencia', 'Para nada', 'Solo para el frío'], 'Para medir lo que está por debajo de una referencia'),
        ]),
    ],
],


[
    'slug'  => 'la-hora-y-el-calendario',
    'title' => 'La hora y el calendario',
    'description' => 'Leer el reloj, calcular duraciones y moverse por el calendario.',
    'objective' => 'Leer la hora en reloj analógico y digital y calcular intervalos de tiempo.',
    'icon' => '🕰️', 'nivel' => 'primaria-inicial', 'bloque' => 'medida-y-magnitudes',
    'duracion' => 12, 'tags' => ['calculo', 'secuencias', 'observacion'],
    'estaciones' => [

        est('Leer el reloj', 'Las manecillas y los números', '🕐', 'opcion_multiple', [
            omp('¿Qué marca la manecilla corta?',      ['La hora', 'Los minutos', 'Los segundos'], 'La hora'),
            omp('¿Qué marca la manecilla larga?',      ['Los minutos', 'La hora', 'El día'], 'Los minutos'),
            omp('¿Cuántos minutos tiene una hora?',    ['60', '100', '30'], '60'),
            omp('Si la larga está en el 6, han pasado…', ['30 minutos', '6 minutos', '60 minutos'], '30 minutos'),
            omp('Si la larga está en el 3, han pasado…', ['15 minutos', '3 minutos', '30 minutos'], '15 minutos'),
        ]),

        est('Calcular duraciones', 'Cuánto tiempo pasó', '⏱️', 'opcion_multiple', [
            omp('De las 3:00 a las 3:45 pasan…',    ['45 minutos', '15 minutos', '1 hora'], '45 minutos'),
            omp('De las 2:30 a las 3:30 pasa…',     ['1 hora', '30 minutos', '2 horas'], '1 hora'),
            omp('Un recreo empieza a las 10:15 y dura 20 minutos. ¿A qué hora termina?', ['10:35', '10:20', '11:15'], '10:35'),
            omp('Una película empieza a las 4:00 y dura 90 minutos. ¿A qué hora termina?', ['5:30', '5:00', '4:90'], '5:30'),
            omp('¿Cuántas horas tiene un día?',     ['24', '12', '60'], '24'),
        ]),

        est('El calendario', 'Días, semanas y meses', '📅', 'opcion_multiple', [
            omp('¿Cuántos días tiene una semana?',  ['7', '5', '10'], '7'),
            omp('¿Cuántos meses tiene un año?',     ['12', '10', '52'], '12'),
            omp('¿Cuántas semanas tiene un año, aproximadamente?', ['52', '12', '30'], '52'),
            omp('¿Qué mes tiene 28 o 29 días?',     ['Febrero', 'Enero', 'Marzo'], 'Febrero'),
            omp('¿Cuántos días tiene un año bisiesto?', ['366', '365', '360'], '366'),
        ]),

        est('Ordena los meses', 'De enero a diciembre', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los primeros meses del año',
            'items' => ['Enero', 'Febrero', 'Marzo', 'Abril', 'Mayo', 'Junio'],
        ]),
    ],
],

[
    'slug'  => 'problemas-de-varios-pasos',
    'title' => 'Problemas de varios pasos',
    'description' => 'Situaciones que no se resuelven con una sola operación, sino con dos o tres.',
    'objective' => 'Resolver problemas que requieren encadenar varias operaciones.',
    'icon' => '🪜', 'nivel' => 'primaria-superior', 'bloque' => 'calculo-mental',
    'duracion' => 14, 'tags' => ['calculo', 'logica', 'deduccion'],
    'estaciones' => [

        est('Dos operaciones', 'Primero una, luego la otra', '2️⃣', 'opcion_multiple', [
            omp('Compro 3 cuadernos de $4.000 y pago con $20.000. ¿Cuánto me devuelven?', ['$8.000', '$12.000', '$16.000'], '$8.000'),
            omp('Tengo 24 dulces, doy 6 y reparto el resto entre 3. ¿Cuántos a cada uno?', ['6', '8', '18'], '6'),
            omp('Un bus lleva 40 personas, bajan 12 y suben 8. ¿Cuántas van?', ['36', '60', '20'], '36'),
            omp('Leí 45 páginas el lunes y el doble el martes. ¿Cuántas en total?', ['135', '90', '45'], '135'),
            omp('Un cine tiene 12 filas de 15 sillas y hay 20 vacías. ¿Cuántas ocupadas?', ['160', '180', '147'], '160'),
        ]),

        est('¿Qué hago primero?', 'El orden de las operaciones', '🔢', 'opcion_multiple', [
            omp('En 2 + 3 × 4, ¿qué hago primero?',  ['La multiplicación', 'La suma', 'Da igual'], 'La multiplicación'),
            omp('¿Cuánto da 2 + 3 × 4?',             ['14', '20', '9'], '14'),
            omp('¿Cuánto da (2 + 3) × 4?',           ['20', '14', '9'], '20'),
            omp('¿Para qué sirven los paréntesis?',  ['Para indicar qué se hace primero', 'Para decorar', 'Para separar'], 'Para indicar qué se hace primero'),
            omp('¿Cuánto da 10 − 2 × 3?',            ['4', '24', '30'], '4'),
        ]),

        est('Problemas del día a día', 'Situaciones reales', '🛒', 'opcion_multiple', [
            omp('Una caja trae 12 huevos y compro 3 cajas. Se rompen 5. ¿Cuántos quedan?', ['31', '36', '41'], '31'),
            omp('Ahorro $2.000 diarios durante 2 semanas. ¿Cuánto junto?', ['$28.000', '$14.000', '$4.000'], '$28.000'),
            omp('Un tanque tiene 50 L, se usan 18 y se añaden 25. ¿Cuánto hay?', ['57 L', '43 L', '93 L'], '57 L'),
            omp('Recorro 4 km de ida y vuelta durante 5 días. ¿Cuántos km?', ['40 km', '20 km', '9 km'], '40 km'),
            omp('Reparto 100 fichas: 40 a un grupo y el resto entre 4 grupos. ¿Cuántas por grupo?', ['15', '25', '60'], '15'),
        ]),

        est('Desafío de varios pasos', 'Cinco problemas', '🏆', 'desafio_final', [
            reto('Tengo 3 billetes de $10.000 y 4 de $5.000. ¿Cuánto tengo?', ['$50.000', '$70.000', '$35.000'], '$50.000'),
            reto('Si de eso gasto $18.000, ¿cuánto queda?', ['$32.000', '$52.000', '$28.000'], '$32.000'),
            reto('Un curso tiene 30 estudiantes; la mitad juega fútbol y un tercio de ellos es arquero. ¿Cuántos arqueros?', ['5', '10', '15'], '5'),
            reto('Un libro de 200 páginas: leí 1/4 el lunes y 50 el martes. ¿Cuántas faltan?', ['100', '150', '50'], '100'),
            reto('¿Qué conviene hacer antes de calcular un problema largo?', ['Ver qué preguntan y en qué orden resolver', 'Calcular todo lo que se vea', 'Adivinar'], 'Ver qué preguntan y en qué orden resolver'),
        ]),
    ],
],

],

'reasignar' => [],

];
