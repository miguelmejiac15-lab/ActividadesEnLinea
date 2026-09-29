<?php
/**
 * Matemática · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'laberinto-de-logica' => intro(
        'Un **patrón** es algo que se repite siguiendo una **regla**. Si descubres la regla, puedes adivinar qué viene después, sea una fila de colores, de figuras o de números.',
        ['Mira qué se **repite** y cada cuánto.', 'En los números, pregúntate cuánto **aumenta** o **disminuye** cada paso.', 'Comprueba tu regla con **todos** los elementos, no solo con dos.'],
        [
            completa('En la serie 2, 4, 6, 8… la regla es sumar ___.', '2', ['4', '1']),
            identifica('🔴 🔵 🔴 🔵 🔴 … ¿qué sigue?', '🔵', ['🔴', '🟢']),
        ]
    ),

    'reloj-del-tiempo' => intro(
        'El **reloj** mide las horas del día y el **calendario**, los días, semanas y meses. En el reloj de agujas, la **corta** marca la hora y la **larga**, los minutos.',
        ['Cuando la aguja larga está en el **12**, es la hora en punto.', 'La semana tiene **7 días**; el año, **12 meses**.', 'Los días y los meses siempre van en el **mismo orden**.'],
        [
            completa('La aguja corta del reloj marca la ___.', 'hora', ['fecha', 'semana']),
            identifica('¿Qué día viene después del miércoles?', 'Jueves', ['Martes', 'Viernes']),
        ]
    ),

    'mercado-de-monedas' => intro(
        'Cuando compramos, pagamos con **dinero** y a veces nos devuelven **vueltas** (el cambio). Para saber cuánto nos deben devolver se **resta**: lo que pagué menos lo que costó.',
        ['Para saber cuánto cuesta todo junto, **sumo** los precios.', '**Vueltas** = lo que pagué − lo que cuesta.', 'Revisa siempre que te devuelvan lo correcto.'],
        [
            completa('Si algo cuesta $3 y pago con $5, me devuelven $___.', '2', ['8', '3']),
            identifica('Para saber el cambio que me deben, ¿qué operación hago?', 'Una resta', ['Una suma', 'Una multiplicación']),
        ]
    ),

    'estadio-multiplicacion' => intro(
        '**Multiplicar** es una forma rápida de sumar el mismo número varias veces. 4 × 3 quiere decir «4 veces 3», o sea 3 + 3 + 3 + 3 = 12. Las **tablas** son esos resultados que conviene saber de memoria.',
        ['4 × 3 = 3 + 3 + 3 + 3 = **12**.', 'El orden no cambia el resultado: 4 × 3 = 3 × 4.', 'Cualquier número por **10** se escribe con un cero al final.'],
        [
            completa('5 × 3 es lo mismo que 5 + 5 + ___.', '5', ['3', '10']),
            identifica('¿Cuánto es 6 × 2?', '12', ['8', '62']),
        ]
    ),

    'orientacion-en-el-millar' => intro(
        'En nuestro sistema cada cifra vale según el **lugar** que ocupa. En 348, el 3 no vale 3: vale **300**, porque está en el lugar de las **centenas**. Es el **valor posicional**.',
        ['**Unidades** (U), **decenas** (D) = 10 unidades, **centenas** (C) = 100 unidades.', 'Descomponer: 427 = 400 + 20 + 7.', 'Diez centenas forman una **unidad de mil**: 1.000.'],
        [
            completa('En 348, el 4 vale ___.', '40', ['4', '400']),
            identifica('¿Cuántas decenas tiene una centena?', '10', ['100', '1']),
        ]
    ),

    'el-campo-del-millon' => intro(
        'Los números grandes se leen por **grupos de tres cifras**: unidades, miles y millones. El punto que separa esos grupos ayuda a leerlos: 45.320 se lee «cuarenta y cinco mil trescientos veinte».',
        ['Cada lugar vale **10 veces** más que el de su derecha.', '1.000 = mil; 10.000 = diez mil; 1.000.000 = un **millón**.', '**Redondear** es cambiar un número por el «redondo» más cercano: 4.732 → 5.000.'],
        [
            completa('En 45.320, el 4 vale ___.', '40.000', ['4.000', '400']),
            identifica('¿Cuántos miles tiene un millón?', '1.000', ['100', '10']),
        ]
    ),

    'sumar-y-restar-por-escrito' => intro(
        'Para sumar o restar números grandes se colocan **uno debajo del otro**, alineando unidades con unidades y decenas con decenas, y se opera de **derecha a izquierda**.',
        ['**Llevar**: si las unidades suman 10 o más, pasa una decena a la columna siguiente.', '**Prestar**: si arriba hay menos que abajo, se pide una decena a la columna de la izquierda.', 'Se **suma** cuando algo se junta o aumenta; se **resta** cuando se quita o disminuye.'],
        [
            completa('Al sumar 47 + 38, las unidades dan 15: escribo 5 y ___ 1.', 'llevo', ['borro', 'resto']),
            identifica('«Había 80 galletas y se comieron 25». ¿Qué operación hago?', 'Restar', ['Sumar', 'Multiplicar']),
        ]
    ),

    'las-tablas-de-multiplicar' => intro(
        '**Multiplicar** es sumar el mismo número muchas veces: 3 + 3 + 3 + 3 es **3 × 4**. Saber las tablas del 1 al 10 de memoria hace que todo lo demás —dividir, fracciones, áreas— sea mucho más fácil.',
        ['3 × 4 = 3 + 3 + 3 + 3 = **12**.', 'El **doble** es multiplicar por 2; la **mitad** es dividir entre 2.', 'Truco: 5 × 4 es lo mismo que 4 × 5.'],
        [
            completa('5 + 5 + 5 es lo mismo que 5 × ___.', '3', ['5', '15']),
            identifica('¿Cuál es el doble de 12?', '24', ['6', '14']),
        ]
    ),

    'multiplicar-y-dividir' => intro(
        '**Dividir** es **repartir en partes iguales**. Y es la multiplicación al revés: si 6 × 4 = 24, entonces 24 ÷ 4 = 6. Por eso una sirve para comprobar la otra.',
        ['12 galletas entre 4 niños: 12 ÷ 4 = **3** a cada uno.', 'Son **operaciones inversas**: una deshace lo que hace la otra.', 'Un **múltiplo** de 5 es un resultado de la tabla del 5: 5, 10, 15…'],
        [
            completa('Si 7 × 5 = 35, entonces 35 ÷ 7 = ___.', '5', ['7', '42']),
            identifica('Dividir es…', 'Repartir en partes iguales', ['Juntar dos cantidades', 'Quitar una cantidad']),
        ]
    ),

    'que-es-una-fraccion' => intro(
        'Una **fracción** representa las **partes iguales** en que se divide un todo. En 3/4, el número de abajo (**denominador**) dice en cuántas partes se partió, y el de arriba (**numerador**), cuántas se toman.',
        ['1/2 es **un medio**; 1/3, **un tercio**; 1/4, **un cuarto**.', 'Las partes tienen que ser **iguales**.', 'Entre más partes, más pequeña cada una: 1/2 es mayor que 1/4.'],
        [
            completa('En 3/5, el denominador es ___.', '5', ['3', '8']),
            identifica('Si parto una pizza en 4 partes iguales, cada parte es…', 'Un cuarto', ['Un medio', 'Un tercio']),
        ],
        'Si comes 3 de las 8 porciones de una torta, comiste 3/8.'
    ),

    'poligonos-y-angulos' => intro(
        'Un **polígono** es una figura plana cerrada formada por **lados rectos**. Se nombra según cuántos lados tiene. Donde se juntan dos lados se forma un **ángulo**, que se mide en grados.',
        ['3 lados: **triángulo**; 4: **cuadrilátero**; 5: **pentágono**; 6: **hexágono**.', 'Ángulo **recto** = 90° (como la esquina de una hoja); **agudo**, menos; **obtuso**, más.', 'Rectas **paralelas** nunca se cruzan; **perpendiculares** se cruzan en ángulo recto.'],
        [
            completa('Un ángulo de 90° se llama ___.', 'recto', ['agudo', 'obtuso']),
            identifica('¿Cómo se llama un polígono de 6 lados?', 'Hexágono', ['Pentágono', 'Octágono']),
        ]
    ),

    'cuerpos-geometricos' => intro(
        'Una **figura plana** se dibuja en una hoja: tiene largo y ancho. Un **cuerpo geométrico** ocupa espacio: tiene además **altura** y se puede tomar con la mano, como un dado o una pelota.',
        ['Partes de un cuerpo: **caras** (superficies), **aristas** (bordes) y **vértices** (esquinas).', 'Los **poliedros** tienen caras planas: cubo, prisma, pirámide.', 'Los **cuerpos redondos** ruedan: esfera, cilindro, cono.'],
        [
            completa('Un dado tiene forma de ___.', 'cubo', ['esfera', 'círculo']),
            identifica('¿Cuál de estos es una figura plana?', 'Círculo', ['Esfera', 'Cilindro']),
        ]
    ),

    'medir-longitudes' => intro(
        '**Medir** es comparar algo con una **unidad**. Antes se medía con el palmo o el pie, pero cada persona los tiene de distinto tamaño. Por eso se inventó el **metro**: una unidad igual para todos.',
        ['1 metro (m) = **100 centímetros** (cm).', '1 centímetro = **10 milímetros** (mm).', 'Para distancias largas se usa el **kilómetro**: 1 km = 1.000 m.'],
        [
            completa('Un metro tiene ___ centímetros.', '100', ['10', '1.000']),
            identifica('¿Por qué se inventó el metro?', 'Para que todos midan igual', ['Porque el palmo es muy largo', 'Para medir el peso']),
        ]
    ),

    'perimetro-y-area' => intro(
        'El **perímetro** es la medida del **contorno** de una figura: lo que mide la cerca de un jardín. El **área** es la **superficie** que ocupa: cuánto pasto cabe dentro. Son medidas distintas.',
        ['Perímetro: **sumar** todos los lados.', 'Área de un rectángulo: **largo × ancho**.', 'El área se mide en unidades **cuadradas**: cm², m².'],
        [
            completa('La medida del contorno de una figura es el ___.', 'perímetro', ['área', 'volumen']),
            identifica('Un cuadrado de lado 5 cm, ¿qué área tiene?', '25 cm²', ['20 cm', '10 cm²']),
        ]
    ),

    'masa-capacidad-y-tiempo' => intro(
        'No todo se mide en metros. La **masa** (lo que pesa algo) se mide en **gramos** y **kilogramos**; la **capacidad** (cuánto líquido cabe) en **litros** y **mililitros**; y el **tiempo** en horas, minutos y segundos.',
        ['1 kilogramo = **1.000 gramos**.', '1 litro = **1.000 mililitros**.', '1 hora = **60 minutos**; 1 minuto = **60 segundos**.'],
        [
            completa('Un kilogramo tiene ___ gramos.', '1.000', ['100', '10']),
            identifica('¿En qué se mide la leche de una botella?', 'Litros', ['Kilómetros', 'Metros']),
        ]
    ),

    'tablas-y-graficas' => intro(
        'Los **datos** son información que recogemos, por ejemplo con una **encuesta**. Para entenderlos los ordenamos en una **tabla** y los dibujamos en una **gráfica**, donde se ven de un vistazo.',
        ['La **frecuencia** es cuántas veces aparece un dato.', 'En un **pictograma**, cada dibujo vale una cantidad: mira la clave.', 'En un **diagrama de barras**, la barra más alta es el dato más frecuente.'],
        [
            completa('Cuántas veces aparece un dato se llama ___.', 'frecuencia', ['promedio', 'título']),
            identifica('Si cada 🍎 vale 2 frutas y hay 4 🍎, ¿cuántas frutas son?', '8', ['4', '6']),
        ]
    ),

    'patrones-y-regularidades' => intro(
        'Una **regularidad** es algo que se repite siempre igual. En una serie numérica, la **regla** dice cómo pasar de un número al siguiente. Si la conoces, puedes predecir cualquier término, aunque esté muy lejos.',
        ['2, 4, 6, 8… regla: **sumar 2**.', 'También hay reglas de restar, multiplicar o de figuras que se alternan.', '**Generalizar** es usar la regla para calcular sin escribir toda la serie.'],
        [
            completa('En 5, 10, 15, 20…, la regla es sumar ___.', '5', ['10', '2']),
            identifica('¿Qué sigue en 4, 8, 12, 16…?', '20', ['18', '24']),
        ]
    ),

    'estimar-y-aproximar' => intro(
        '**Estimar** es calcular «más o menos» antes de hacer la cuenta exacta. Se hace **redondeando** los números. Sirve para saber si un resultado es **razonable** o si algo salió mal.',
        ['198 + 203 es aproximadamente 200 + 200 = **400**.', 'Si la cuenta exacta da algo muy lejos de la estimación, **revísala**.', 'En la tienda, estimar te dice si te **alcanza** el dinero.'],
        [
            completa('Para estimar 48 + 33, redondeo a 50 + ___.', '30', ['40', '33']),
            identifica('«48 + 51 = 990». ¿Es razonable?', 'No, debería dar cerca de 100', ['Sí, está perfecto', 'Sí, porque tiene tres cifras']),
        ]
    ),

    'divisibilidad-y-numeros-primos' => intro(
        'Un número es **divisible** entre otro cuando la división es **exacta**, sin sobrante. Hay reglas para saberlo sin hacer la cuenta. Y un **número primo** es el que solo se divide entre 1 y entre sí mismo.',
        ['Entre **2**: termina en cifra par. Entre **5**: termina en 0 o 5.', 'Entre **3**: la suma de sus cifras es múltiplo de 3 (27 → 2 + 7 = 9).', 'Primos: 2, 3, 5, 7, 11, 13… Los demás son **compuestos**.'],
        [
            completa('Un número primo solo se divide entre 1 y entre ___.', 'sí mismo', ['2', '10']),
            identifica('¿Cuál de estos es primo?', '13', ['9', '15']),
        ]
    ),

    'fracciones-equivalentes' => intro(
        'Dos fracciones son **equivalentes** cuando representan **la misma cantidad** aunque se escriban distinto: 1/2 = 2/4 = 4/8. Se obtienen multiplicando o dividiendo arriba y abajo por el mismo número.',
        ['1/2 → multiplico por 2 arriba y abajo → **2/4**.', 'Fracción **propia**: numerador menor (3/7). **Impropia**: mayor (9/4).', 'Con igual denominador se suman los numeradores: 1/5 + 2/5 = **3/5**.'],
        [
            completa('1/2 es equivalente a 2/___.', '4', ['2', '3']),
            identifica('¿Cuánto es 1/5 + 2/5?', '3/5', ['3/10', '2/5']),
        ]
    ),

    'numeros-decimales' => intro(
        'Los **números decimales** sirven para expresar partes de la unidad. Lo que va después de la **coma** son **décimas** (1/10), **centésimas** (1/100) y milésimas. Los usamos con el dinero, las estaturas y las medidas.',
        ['0,1 = una **décima** = 1/10.', '0,01 = una **centésima** = 1/100.', 'Para comparar, mira cifra por cifra desde la coma: 0,5 es mayor que 0,05.'],
        [
            completa('0,1 se lee «una ___».', 'décima', ['centésima', 'unidad']),
            identifica('¿Cuál es mayor?', '0,5', ['0,05', '0,005']),
        ],
        'Si mides 1,35 m, mides 1 metro y 35 centímetros.'
    ),

    'movimientos-en-el-plano' => intro(
        'Una figura se puede **mover** sin cambiar su forma ni su tamaño de tres maneras: **deslizarla**, **girarla** o **reflejarla** como en un espejo. Y para ubicar un punto se usan **coordenadas**: dos números (x, y).',
        ['**Traslación**: se desliza sin girar.', '**Rotación**: gira alrededor de un punto.', '**Reflexión**: se voltea como en un espejo.', 'En (3, 5), el 3 es la **x** (horizontal) y el 5 la **y** (vertical).'],
        [
            completa('Mover una figura sin girarla es una ___.', 'traslación', ['rotación', 'reflexión']),
            identifica('En el punto (3, 5), ¿cuál es la coordenada x?', '3', ['5', '8']),
        ]
    ),

    'volumen-y-capacidad' => intro(
        'El **volumen** es el **espacio que ocupa** un cuerpo. La **capacidad** es **cuánto le cabe dentro**. Se relacionan: una caja de 10 × 10 × 10 cm ocupa 1.000 cm³ y le cabe exactamente **1 litro**.',
        ['El volumen se mide en unidades **cúbicas**: cm³, m³.', 'Volumen de una caja: **largo × ancho × alto**.', '1 cm³ = **1 mL**; 1.000 cm³ = **1 L**.'],
        [
            completa('El volumen de una caja se calcula multiplicando largo × ancho × ___.', 'alto', ['peso', 'perímetro']),
            identifica('¿Qué mide la capacidad?', 'Cuánto cabe dentro', ['Cuánto pesa', 'El contorno']),
        ]
    ),

    'promedios-y-medidas' => intro(
        'Cuando hay muchos datos, conviene **resumirlos en un número**. Hay cuatro formas y cada una responde algo distinto: la **media**, la **mediana**, la **moda** y el **rango**.',
        ['**Media** (promedio): sumo todos y divido entre cuántos son.', '**Mediana**: el dato del medio cuando están ordenados.', '**Moda**: el que más se repite. **Rango**: el mayor menos el menor.'],
        [
            completa('El dato que más se repite se llama ___.', 'moda', ['media', 'rango']),
            identifica('¿Cuál es la media de 2, 4 y 6?', '4', ['6', '12']),
        ]
    ),

    'probabilidad-y-azar' => intro(
        'La **probabilidad** mide qué tan fácil es que algo pase. Un suceso puede ser **seguro**, **posible** o **imposible**. Cuando depende del azar se expresa como fracción: casos que me sirven / casos posibles.',
        ['Lanzar una moneda: **1/2** de sacar cara.', 'Un dado: **1/6** de sacar un 3.', 'Seguro = 1; imposible = 0.'],
        [
            completa('La probabilidad de sacar cara al lanzar una moneda es ___.', '1/2', ['1/6', '2']),
            identifica('Sacar un 7 en un dado de 6 caras es…', 'Imposible', ['Seguro', 'Muy probable']),
        ]
    ),

    'conjuntos' => intro(
        'Un **conjunto** es un grupo de elementos que comparten una **característica**: los números pares, los animales, las vocales. Dos conjuntos se pueden **unir** o mirar qué tienen **en común**.',
        ['**Unión**: todos los elementos de los dos conjuntos.', '**Intersección**: solo los que están en **ambos**.', 'Un **diagrama de Venn** los dibuja como círculos que se cruzan.'],
        [
            completa('Los elementos que están en los dos conjuntos a la vez forman la ___.', 'intersección', ['unión', 'resta']),
            identifica('¿Cuál NO pertenece al conjunto de los animales?', 'Mesa', ['Perro', 'Gallina']),
        ]
    ),

    'igualdades-y-ecuaciones' => intro(
        'El signo **=** no significa «aquí va el resultado»: significa que **los dos lados valen lo mismo**, como una balanza en equilibrio. Una **ecuación** es una igualdad con un número escondido que hay que encontrar.',
        ['5 + 3 = 4 + 4 es correcto: los dos lados dan 8.', 'En x + 5 = 12, la **x** es el número escondido (**incógnita**).', 'Para despejarla haces la operación **contraria**: 12 − 5 = 7.'],
        [
            completa('Si x + 5 = 12, entonces x = ___.', '7', ['17', '5']),
            identifica('¿Qué significa el signo =?', 'Que los dos lados valen lo mismo', ['Que ahí va el resultado', 'Que hay que sumar']),
        ]
    ),

    'graficas-y-representaciones' => intro(
        'Cada tipo de **gráfica** sirve para algo distinto. Elegir bien es parte de mostrar la información con honestidad, y leerlas con cuidado evita que nos engañen.',
        ['**Barras**: comparar cantidades entre grupos.', '**Línea**: ver cómo algo **cambia en el tiempo**.', '**Circular**: ver qué **parte del total** es cada grupo (en %).', 'Mira siempre la **escala** de los ejes.'],
        [
            completa('Para mostrar cómo cambió la temperatura durante el día uso una gráfica de ___.', 'línea', ['barras', 'pastel']),
            identifica('En una gráfica circular, la mitad del círculo representa…', 'El 50%', ['El 25%', 'El 100%']),
        ]
    ),

    'numeros-enteros' => intro(
        'Los **números enteros** incluyen los positivos, el cero y los **negativos**. Los negativos llevan el signo **−** y sirven para lo que está **por debajo del cero**: temperaturas bajo cero, sótanos, deudas.',
        ['−5 °C = cinco grados **bajo cero**.', 'En la recta numérica, los negativos van a la **izquierda** del cero.', 'Entre dos negativos, es mayor el más **cercano al cero**: −1 > −7.'],
        [
            completa('El piso −2 de un edificio está ___ del nivel de la calle.', 'debajo', ['encima', 'al lado']),
            identifica('¿Cuál es mayor?', '−1', ['−7', '−10']),
        ]
    ),

    'problemas-de-varios-pasos' => intro(
        'Algunos problemas no se resuelven con una sola cuenta: hay que hacer **dos o tres operaciones seguidas**. La clave es **leer bien**, separar lo que te preguntan y decidir en qué **orden** calcular.',
        ['1. ¿Qué me preguntan? 2. ¿Qué datos tengo? 3. ¿Qué hago primero?', 'En una expresión, la **multiplicación y la división** van antes que la suma y la resta.', 'Al final, **revisa** que la respuesta tenga sentido.'],
        [
            completa('En 2 + 3 × 4, primero se hace la ___.', 'multiplicación', ['suma', 'resta']),
            identifica('¿Cuánto da 2 + 3 × 4?', '14', ['20', '9']),
        ]
    ),

];
