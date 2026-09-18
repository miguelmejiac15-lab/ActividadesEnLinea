<?php
/**
 * juegos-primaria.php — Juegos y Retos de 1.º a 6.º
 *
 * Esta categoría no aporta contenido nuevo: reordena el que ya existe.
 *
 * Las mallas de `MallasPrimaria/` insisten en algo que el catálogo por
 * materias no permite: la TRANSFERENCIA. «Aplicar lo aprendido en
 * contextos nuevos y variados» es literalmente la definición de «meta de
 * transferencia» que encabeza todas las mallas del colegio. Un niño que
 * resuelve una resta dentro de la unidad de restas no ha demostrado nada
 * todavía: la sabe cuando la reconoce donde no la anunciaron.
 *
 * Por eso los retos son MIXTOS a propósito. Un desafío de 4.º mezcla
 * matemáticas, ciencias y lengua sin avisar de cuál es cada pregunta, que
 * es exactamente la dificultad que el catálogo por materias elimina.
 *
 * Y por eso van contrarreloj o de una sola pasada: aquí no se aprende algo
 * nuevo, se comprueba lo que ya quedó.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'juegos',
    'name'       => 'Juegos y Retos',
    'tagline'    => 'Poner a prueba lo aprendido, mezclado y contrarreloj',
    'icon'       => '🎲',
    'color'      => '#d81b60',
    'sort_order' => 7,
],

'bloques' => [
    ['slug' => 'repaso-por-grado', 'name' => 'Repaso por Grado', 'icon' => '🎓', 'sort_order' => 4,
     'description' => 'Un reto mixto por cada grado de primaria: todas las materias en el mismo desafío.'],
    ['slug' => 'retos-relampago', 'name' => 'Retos Relámpago', 'icon' => '⚡', 'sort_order' => 5,
     'description' => 'Contrarreloj: responder rápido lo que ya se sabe de memoria.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · REPASO POR GRADO
// =====================================================================

[
    'slug'  => 'reto-de-primero',
    'title' => 'Reto de primero',
    'description' => 'Todo lo de 1.º mezclado: números hasta 20, letras, formas y el mundo que me rodea.',
    'objective' => 'Aplicar de forma integrada los aprendizajes de primer grado en un contexto mixto.',
    'icon' => '1️⃣', 'nivel' => 'primaria-inicial', 'bloque' => 'repaso-por-grado',
    'duracion' => 12, 'tags' => ['reto', 'juego', 'logica'],
    'estaciones' => [

        est('Números y cantidades', 'Lo de matemáticas', '🔢', 'operacion', [
            ['op' => 'suma',   'a' => 4,  'b' => 3, 'resultado' => 7],
            ['op' => 'resta',  'a' => 10, 'b' => 4, 'resultado' => 6],
            ['op' => 'contar', 'a' => 8,  'resultado' => 8],
            ['op' => 'suma',   'a' => 9,  'b' => 5, 'resultado' => 14],
            ['op' => 'resta',  'a' => 15, 'b' => 6, 'resultado' => 9],
        ]),

        est('Letras y palabras', 'Lo de lengua', '🔤', 'opcion_multiple', [
            omp('¿Con qué vocal empieza ELEFANTE?',   ['E', 'A', 'I'], 'E', '🐘'),
            omp('¿Cuántas sílabas tiene CA-SA?',      ['2', '3', '1'], '2', '🏠'),
            omp('¿Cuál empieza con M?',               ['Mesa', 'Casa', 'Pato'], 'Mesa'),
            omp('¿Qué letra falta en S_L?',           ['O', 'A', 'E'], 'O', '☀️'),
            omp('¿Cuál NO es una vocal?',             ['M', 'A', 'U'], 'M'),
        ]),

        est('Formas y el mundo', 'Lo de ciencias y formas', '🌍', 'opcion_multiple', [
            omp('¿Cuántos lados tiene un triángulo?',  ['3', '4', '5'], '3', '🔺'),
            omp('¿Está vivo un árbol?',                ['Sí', 'No', 'Solo en verano'], 'Sí', '🌳'),
            omp('¿Qué necesitan las plantas?',         ['Agua y luz', 'Solo aire', 'Nada'], 'Agua y luz', '🌱'),
            omp('¿Con qué sentido veo los colores?',   ['La vista', 'El oído', 'El gusto'], 'La vista', '👁️'),
            omp('¿Qué animal vive en el agua?',        ['El pez', 'El perro', 'El gato'], 'El pez', '🐟'),
        ]),

        est('Desafío de primero', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Cuántos días tiene una semana?',    ['7', '5', '10'], '7'),
            reto('¿Qué número va después del 19?',     ['20', '18', '21'], '20'),
            reto('¿Qué color sale de mezclar azul y amarillo?', ['Verde', 'Morado', 'Naranja'], 'Verde'),
            reto('¿Dónde cruzo la calle?',             ['Por la cebra', 'Por la mitad', 'Corriendo'], 'Por la cebra'),
            reto('¿Cuántas vocales hay?',              ['5', '3', '7'], '5'),
        ]),
    ],
],

[
    'slug'  => 'reto-de-segundo',
    'title' => 'Reto de segundo',
    'description' => 'Todo lo de 2.º mezclado: decenas, sílabas, seres vivos y convivencia.',
    'objective' => 'Aplicar de forma integrada los aprendizajes de segundo grado.',
    'icon' => '2️⃣', 'nivel' => 'primaria-inicial', 'bloque' => 'repaso-por-grado',
    'duracion' => 12, 'tags' => ['reto', 'juego', 'calculo'],
    'estaciones' => [

        est('Cálculo hasta el 100', 'Lo de matemáticas', '🧮', 'operacion', [
            ['op' => 'suma',  'a' => 25, 'b' => 14, 'resultado' => 39],
            ['op' => 'resta', 'a' => 60, 'b' => 25, 'resultado' => 35],
            ['op' => 'suma',  'a' => 48, 'b' => 32, 'resultado' => 80],
            ['op' => 'resta', 'a' => 91, 'b' => 47, 'resultado' => 44],
            ['op' => 'suma',  'a' => 17, 'b' => 8,  'resultado' => 25],
        ]),

        est('Palabras y oraciones', 'Lo de lengua', '✍️', 'opcion_multiple', [
            omp('¿Cuál está bien escrito?',            ['Mi mamá es alta.', 'mi mamá es alta', 'Mi Mamá Es Alta.'], 'Mi mamá es alta.'),
            omp('¿Cuántas sílabas tiene MA-RI-PO-SA?', ['4', '3', '5'], '4', '🦋'),
            omp('¿Qué signo cierra una oración?',      ['El punto', 'La coma', 'El guion'], 'El punto'),
            omp('¿Cuál es un nombre propio?',          ['Colombia', 'país', 'ciudad'], 'Colombia'),
            omp('¿Qué palabra es el plural de «flor»?', ['Flores', 'Flora', 'Florido'], 'Flores'),
        ]),

        est('Seres vivos y convivencia', 'Lo de ciencias y valores', '🌿', 'opcion_multiple', [
            omp('¿Qué produce el oxígeno que respiramos?', ['Las plantas', 'Las piedras', 'El viento'], 'Las plantas', '🌳'),
            omp('¿Qué animal pone huevos?',            ['La gallina', 'La vaca', 'El perro'], 'La gallina', '🐔'),
            omp('Un compañero se cayó. ¿Qué hago?',    ['Lo ayudo a levantarse', 'Me río', 'Me voy'], 'Lo ayudo a levantarse'),
            omp('¿Dónde va una cáscara de fruta?',     ['En los orgánicos', 'En reciclables', 'Al piso'], 'En los orgánicos', '🍌'),
            omp('¿Para qué sirven las reglas del salón?', ['Para que todos estemos bien', 'Para castigar', 'Para nada'], 'Para que todos estemos bien'),
        ]),

        est('Desafío de segundo', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Cuántas decenas tiene el 60?',      ['6', '60', '0'], '6'),
            reto('¿Cuántos meses tiene un año?',       ['12', '10', '30'], '12'),
            reto('¿Cuál es mayor, 45 o 54?',           ['54', '45', 'Iguales'], '54'),
            reto('¿Qué hace un veterinario?',          ['Cuida animales', 'Apaga incendios', 'Vende pan'], 'Cuida animales'),
            reto('¿Cuánto es el doble de 15?',         ['30', '20', '25'], '30'),
        ]),
    ],
],

[
    'slug'  => 'reto-de-tercero',
    'title' => 'Reto de tercero',
    'description' => 'Todo lo de 3.º mezclado: el millar, gramática, el cuerpo y el clima.',
    'objective' => 'Aplicar de forma integrada los aprendizajes de tercer grado.',
    'icon' => '3️⃣', 'nivel' => 'primaria-media', 'bloque' => 'repaso-por-grado',
    'duracion' => 13, 'tags' => ['reto', 'logica', 'calculo'],
    'estaciones' => [

        est('El campo del millar', 'Lo de matemáticas', '🔢', 'opcion_multiple', [
            omp('En 472, ¿cuánto vale el 4?',        ['400', '40', '4'], '400'),
            omp('¿Cuánto es 250 + 175?',             ['425', '325', '435'], '425'),
            omp('¿Cuánto es 7 × 8?',                 ['56', '54', '64'], '56'),
            omp('¿Cuántos centímetros tiene un metro?', ['100', '10', '1.000'], '100'),
            omp('¿Cuál es la decena más cercana a 68?', ['70', '60', '65'], '70'),
        ]),

        est('Gramática y ortografía', 'Lo de lengua', '📖', 'opcion_multiple', [
            omp('En «el perro grande», ¿cuál es el adjetivo?', ['Grande', 'Perro', 'El'], 'Grande'),
            omp('¿Cuál es un verbo?',                ['Correr', 'Rápido', 'Zapato'], 'Correr'),
            omp('¿Cuál es sinónimo de «bonito»?',    ['Hermoso', 'Feo', 'Alto'], 'Hermoso'),
            omp('¿Cómo se escribe bien una pregunta?', ['¿Qué hora es?', 'Qué hora es?', '¿Qué hora es'], '¿Qué hora es?'),
            omp('¿Cuál es el antónimo de «subir»?',  ['Bajar', 'Trepar', 'Escalar'], 'Bajar'),
        ]),

        est('El cuerpo y el clima', 'Lo de ciencias', '🌦️', 'opcion_multiple', [
            omp('¿Qué órgano bombea la sangre?',     ['El corazón', 'El pulmón', 'El hígado'], 'El corazón', '🫀'),
            omp('¿Qué protege el cráneo?',           ['El cerebro', 'El corazón', 'El estómago'], 'El cerebro'),
            omp('¿Qué es el tiempo atmosférico?',    ['Cómo está el día hoy', 'El clima de siempre', 'La hora'], 'Cómo está el día hoy'),
            omp('¿Con qué se mide la temperatura?',  ['El termómetro', 'La regla', 'La balanza'], 'El termómetro', '🌡️'),
            omp('¿Cuántas patas tiene un insecto?',  ['6', '8', '4'], '6', '🐜'),
        ]),

        est('Desafío de tercero', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Cuál es el resultado de 9 × 6?',  ['54', '56', '45'], '54'),
            reto('¿Qué región de Colombia tiene playas en el Caribe?', ['La Caribe', 'La Andina', 'La Amazonía'], 'La Caribe'),
            reto('¿Cuántos lados tiene un hexágono?', ['6', '5', '8'], '6'),
            reto('¿Qué es un algoritmo?',            ['Una lista de pasos en orden', 'Un computador', 'Un número'], 'Una lista de pasos en orden'),
            reto('¿Cuál es la sílaba tónica de «lápiz»?', ['lá', 'piz', 'ninguna'], 'lá'),
        ]),
    ],
],

[
    'slug'  => 'reto-de-cuarto',
    'title' => 'Reto de cuarto',
    'description' => 'Todo lo de 4.º mezclado: el millón, fracciones, sistemas del cuerpo e historia.',
    'objective' => 'Aplicar de forma integrada los aprendizajes de cuarto grado.',
    'icon' => '4️⃣', 'nivel' => 'primaria-media', 'bloque' => 'repaso-por-grado',
    'duracion' => 13, 'tags' => ['reto', 'logica', 'historia'],
    'estaciones' => [

        est('Números grandes y fracciones', 'Lo de matemáticas', '🔢', 'opcion_multiple', [
            omp('En 45.320, ¿cuánto vale el 4?',    ['40.000', '4.000', '400'], '40.000'),
            omp('¿Cuánto es 1/2 de 20?',            ['10', '5', '15'], '10'),
            omp('¿Cuánto es 1/4 de 20?',            ['5', '10', '4'], '5'),
            omp('¿Cuál es el área de un rectángulo de 6 × 4?', ['24', '20', '10'], '24'),
            omp('Redondea 4.732 al millar',         ['5.000', '4.000', '4.700'], '5.000'),
        ]),

        est('El cuerpo por dentro', 'Lo de ciencias', '🫁', 'opcion_multiple', [
            omp('¿Dónde se absorben los nutrientes?', ['En el intestino delgado', 'En la boca', 'En los pulmones'], 'En el intestino delgado'),
            omp('¿Qué gas expulsamos al exhalar?',   ['Dióxido de carbono', 'Oxígeno', 'Helio'], 'Dióxido de carbono'),
            omp('¿Qué transporta el oxígeno en la sangre?', ['Los glóbulos rojos', 'Los huesos', 'La saliva'], 'Los glóbulos rojos'),
            omp('¿Qué es la fotosíntesis?',          ['Cómo las plantas fabrican su alimento con luz', 'Cómo respiran los animales', 'Un tipo de célula'], 'Cómo las plantas fabrican su alimento con luz'),
            omp('¿Qué máquina simple es una rampa?', ['El plano inclinado', 'La polea', 'La palanca'], 'El plano inclinado'),
        ]),

        est('Historia y textos', 'Lo de sociales y lengua', '📜', 'opcion_multiple', [
            omp('¿Por dónde llegaron los primeros pobladores a América?', ['Por el estrecho de Bering', 'Por el Atlántico', 'Por el Amazonas'], 'Por el estrecho de Bering'),
            omp('¿Qué pueblo hizo la balsa muisca de oro?', ['Los muiscas', 'Los incas', 'Los mayas'], 'Los muiscas'),
            omp('¿Qué preguntas responde una noticia?', ['Qué, quién, cuándo, dónde y por qué', 'Solo qué', 'Ninguna'], 'Qué, quién, cuándo, dónde y por qué'),
            omp('«Ayer llovió» es…',                 ['Un hecho', 'Una opinión', 'Un cuento'], 'Un hecho'),
            omp('¿Qué es el gobierno escolar?',      ['La participación de los estudiantes en las decisiones', 'Los profesores', 'Una materia'], 'La participación de los estudiantes en las decisiones'),
        ]),

        est('Desafío de cuarto', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Cuál es el m.c.m. de 4 y 6?',     ['12', '24', '10'], '12'),
            reto('¿Qué es la moraleja?',             ['La enseñanza de una fábula', 'El título', 'El personaje'], 'La enseñanza de una fábula'),
            reto('¿Qué produce la rotación de la Tierra?', ['El día y la noche', 'Las estaciones', 'Las mareas'], 'El día y la noche'),
            reto('¿Cuánto suman los ángulos de un triángulo?', ['180°', '360°', '90°'], '180°'),
            reto('¿Qué es el phishing?',             ['Un engaño para robar datos', 'Un juego', 'Un programa útil'], 'Un engaño para robar datos'),
        ]),
    ],
],

[
    'slug'  => 'reto-de-quinto',
    'title' => 'Reto de quinto',
    'description' => 'Todo lo de 5.º mezclado: fracciones, ecosistemas, regiones y ciudadanía digital.',
    'objective' => 'Aplicar de forma integrada los aprendizajes de quinto grado.',
    'icon' => '5️⃣', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 14, 'tags' => ['reto', 'logica', 'deduccion'],
    'estaciones' => [

        est('Fracciones y datos', 'Lo de matemáticas', '📊', 'opcion_multiple', [
            omp('¿A qué equivale 2/4?',              ['1/2', '1/4', '3/4'], '1/2'),
            omp('¿Cuánto es 1/5 + 2/5?',             ['3/5', '3/10', '2/5'], '3/5'),
            omp('¿Cuál es la media de 2, 4 y 6?',    ['4', '12', '3'], '4'),
            omp('En 3, 5, 5, 7 la moda es…',         ['5', '7', '3'], '5'),
            omp('¿Cuál es la probabilidad de cara al lanzar una moneda?', ['1/2', '1/6', '1'], '1/2'),
        ]),

        est('Ecosistemas y materia', 'Lo de ciencias', '🌿', 'opcion_multiple', [
            omp('¿De dónde viene la energía de una cadena alimenticia?', ['Del Sol', 'Del suelo', 'Del agua'], 'Del Sol'),
            omp('¿Qué hacen los descomponedores?',   ['Devuelven nutrientes al suelo', 'Cazan', 'Hacen fotosíntesis'], 'Devuelven nutrientes al suelo'),
            omp('Agua con azúcar disuelta es una mezcla…', ['Homogénea', 'Heterogénea', 'Pura'], 'Homogénea'),
            omp('¿Cómo separo la sal del agua salada?', ['Evaporando el agua', 'Filtrando', 'Con un imán'], 'Evaporando el agua'),
            omp('¿Qué es un ecosistema?',            ['Los seres vivos de un lugar y su entorno', 'Solo los animales', 'Solo el clima'], 'Los seres vivos de un lugar y su entorno'),
        ]),

        est('Colombia y lo digital', 'Lo de sociales y tecnología', '🌐', 'opcion_multiple', [
            omp('¿Cuántas regiones naturales tiene Colombia?', ['Seis', 'Tres', 'Diez'], 'Seis'),
            omp('¿Qué línea divide la Tierra en norte y sur?', ['El ecuador', 'Greenwich', 'El trópico'], 'El ecuador'),
            omp('¿Cuál es la contraseña más segura?', ['Ma7#zul-Perro2026', '123456', 'password'], 'Ma7#zul-Perro2026'),
            omp('¿Qué es la huella digital?',        ['El rastro que dejan mis acciones en internet', 'Un dibujo', 'Un archivo'], 'El rastro que dejan mis acciones en internet'),
            omp('¿Qué rama del poder hace las leyes?', ['La legislativa', 'La ejecutiva', 'La judicial'], 'La legislativa'),
        ]),

        est('Desafío de quinto', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Cuál de estos es un número primo?', ['13', '15', '21'], '13'),
            reto('¿Qué es la tesis de un texto argumentativo?', ['La idea que se quiere defender', 'El título', 'El final'], 'La idea que se quiere defender'),
            reto('¿Qué es la biodiversidad?',        ['La variedad de seres vivos de un lugar', 'La cantidad de agua', 'El clima'], 'La variedad de seres vivos de un lugar'),
            reto('¿Qué es el consenso?',             ['Una solución que las dos partes aceptan', 'Que uno gane', 'Que nadie hable'], 'Una solución que las dos partes aceptan'),
            reto('¿Cuánto es 0,5 en fracción?',      ['1/2', '1/5', '5/1'], '1/2'),
        ]),
    ],
],

[
    'slug'  => 'reto-de-sexto',
    'title' => 'Reto de sexto',
    'description' => 'Todo lo de 6.º mezclado: decimales, átomos, independencia y pensamiento crítico.',
    'objective' => 'Aplicar de forma integrada los aprendizajes de sexto grado.',
    'icon' => '6️⃣', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 14, 'tags' => ['reto', 'deduccion', 'logica'],
    'estaciones' => [

        est('Decimales y geometría', 'Lo de matemáticas', '📐', 'opcion_multiple', [
            omp('¿Cuál es mayor, 0,5 o 0,05?',       ['0,5', '0,05', 'Iguales'], '0,5'),
            omp('¿Cuánto es 2,5 × 10?',              ['25', '2,50', '250'], '25'),
            omp('¿Cuál es el m.c.d. de 12 y 18?',    ['6', '3', '36'], '6'),
            omp('¿Cuál es el volumen de un cubo de arista 3 cm?', ['27 cm³', '9 cm³', '18 cm³'], '27 cm³'),
            omp('¿Qué transformación gira una figura?', ['La rotación', 'La traslación', 'La reflexión'], 'La rotación'),
        ]),

        est('Materia y energía', 'Lo de ciencias', '⚡', 'opcion_multiple', [
            omp('¿Qué partícula está en el núcleo del átomo?', ['El protón', 'El electrón', 'Ninguna'], 'El protón'),
            omp('¿Qué necesita un circuito para funcionar?', ['Un camino cerrado', 'Estar abierto', 'Estar mojado'], 'Un camino cerrado'),
            omp('¿Qué pasa entre dos polos magnéticos iguales?', ['Se repelen', 'Se atraen', 'Nada'], 'Se repelen'),
            omp('¿Qué sistema coordina el cuerpo con hormonas?', ['El endocrino', 'El digestivo', 'El óseo'], 'El endocrino'),
            omp('¿Qué es un cambio químico?',        ['Uno donde se forma una sustancia nueva', 'Un cambio de forma', 'Un cambio de lugar'], 'Uno donde se forma una sustancia nueva'),
        ]),

        est('Historia y criterio', 'Lo de sociales y pensamiento', '🎺', 'opcion_multiple', [
            omp('¿Qué se celebra el 20 de julio en Colombia?', ['La independencia', 'El día del idioma', 'El trabajo'], 'La independencia'),
            omp('¿En qué batalla se selló la independencia en 1819?', ['Boyacá', 'Bolívar', 'Cartagena'], 'Boyacá'),
            omp('«Todos lo hacen, así que está bien.» ¿Es buen argumento?', ['No', 'Sí', 'A veces'], 'No'),
            omp('Si llueve el piso se moja. El piso está mojado. ¿Llovió?', ['No se puede saber', 'Sí, seguro', 'No'], 'No se puede saber'),
            omp('¿Qué son los sectores económicos?', ['Primario, secundario y terciario', 'Norte, sur y centro', 'Tres regiones'], 'Primario, secundario y terciario'),
        ]),

        est('Desafío de sexto', 'La prueba final de primaria', '🏆', 'desafio_final', [
            reto('¿Qué mide la mediana?',            ['El dato del medio al ordenarlos', 'El promedio', 'El que más se repite'], 'El dato del medio al ordenarlos'),
            reto('¿Qué es el ciclo del carbono?',    ['El recorrido del carbono entre seres vivos y ambiente', 'Un tipo de energía', 'Una máquina'], 'El recorrido del carbono entre seres vivos y ambiente'),
            reto('¿Qué países formaron la Gran Colombia?', ['Colombia, Venezuela, Ecuador y Panamá', 'Solo Colombia', 'Colombia y Perú'], 'Colombia, Venezuela, Ecuador y Panamá'),
            reto('¿A qué género pertenece una obra de teatro?', ['Dramático', 'Lírico', 'Narrativo'], 'Dramático'),
            reto('¿Cuántos grados tiene un giro completo?', ['360°', '180°', '90°'], '360°'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · RETOS RELÁMPAGO
// =====================================================================

[
    'slug'  => 'relampago-de-calculo',
    'title' => 'Relámpago de cálculo',
    'description' => 'Sumas, restas y tablas contrarreloj. Sin papel, solo de cabeza.',
    'objective' => 'Automatizar el cálculo mental básico mediante práctica contrarreloj.',
    'icon' => '⚡', 'nivel' => 'primaria-media', 'bloque' => 'retos-relampago',
    'duracion' => 10, 'tags' => ['calculo', 'reto', 'atencion'],
    'estaciones' => [

        est('Sumas relámpago', '¿Está bien el resultado?', '➕', 'juego_rapido',
            conTitulo('¿Es correcto?', 'Responde rápido', [
                ['e' => '➕', 'n' => '7 + 5 = 12',  'ok' => true],
                ['e' => '➕', 'n' => '8 + 6 = 15',  'ok' => false],
                ['e' => '➕', 'n' => '9 + 9 = 18',  'ok' => true],
                ['e' => '➕', 'n' => '13 + 7 = 21', 'ok' => false],
                ['e' => '➕', 'n' => '25 + 25 = 50', 'ok' => true],
                ['e' => '➕', 'n' => '40 + 30 = 60', 'ok' => false],
                ['e' => '➕', 'n' => '15 + 15 = 30', 'ok' => true],
            ])),

        est('Restas relámpago', '¿Está bien el resultado?', '➖', 'juego_rapido',
            conTitulo('¿Es correcto?', 'Responde rápido', [
                ['e' => '➖', 'n' => '12 − 5 = 7',   'ok' => true],
                ['e' => '➖', 'n' => '20 − 8 = 13',  'ok' => false],
                ['e' => '➖', 'n' => '50 − 25 = 25', 'ok' => true],
                ['e' => '➖', 'n' => '100 − 40 = 70', 'ok' => false],
                ['e' => '➖', 'n' => '18 − 9 = 9',   'ok' => true],
                ['e' => '➖', 'n' => '33 − 11 = 22', 'ok' => true],
                ['e' => '➖', 'n' => '45 − 15 = 35', 'ok' => false],
            ])),

        est('Tablas relámpago', '¿Está bien el resultado?', '✖️', 'juego_rapido',
            conTitulo('¿Es correcto?', 'Responde rápido', [
                ['e' => '✖️', 'n' => '6 × 7 = 42',  'ok' => true],
                ['e' => '✖️', 'n' => '8 × 8 = 62',  'ok' => false],
                ['e' => '✖️', 'n' => '9 × 5 = 45',  'ok' => true],
                ['e' => '✖️', 'n' => '7 × 7 = 47',  'ok' => false],
                ['e' => '✖️', 'n' => '4 × 9 = 36',  'ok' => true],
                ['e' => '✖️', 'n' => '12 × 3 = 36', 'ok' => true],
                ['e' => '✖️', 'n' => '6 × 6 = 42',  'ok' => false],
            ])),

        est('Series relámpago', 'Encuentra el número que falta', '🔢', 'secuencia_numerica', [
            serie([5, 10, null, 20, 25]),
            serie([3, 6, 9, null, 15]),
            serie([20, 18, null, 14, 12]),
            serie([12, 24, null, 48, 60]),
            serie([7, 14, 21, null, 35]),
        ]),
    ],
],

[
    'slug'  => 'relampago-de-palabras',
    'title' => 'Relámpago de palabras',
    'description' => 'Ortografía, sinónimos y gramática contrarreloj.',
    'objective' => 'Afianzar reglas ortográficas y vocabulario mediante práctica rápida.',
    'icon' => '🔤', 'nivel' => 'primaria-media', 'bloque' => 'retos-relampago',
    'duracion' => 10, 'tags' => ['escritura', 'reto', 'vocabulario'],
    'estaciones' => [

        est('Ortografía relámpago', 'Elige la forma correcta', '✅', 'ortografia', [
            ['e' => '🏠', 'opts' => ['casa', 'caza'],       'correct' => 'casa'],
            ['e' => '🌳', 'opts' => ['árbol', 'arbol'],     'correct' => 'árbol'],
            ['e' => '🎵', 'opts' => ['música', 'musica'],   'correct' => 'música'],
            ['e' => '📖', 'opts' => ['había', 'habia'],     'correct' => 'había'],
            ['e' => '🐦', 'opts' => ['pájaro', 'pajaro'],   'correct' => 'pájaro'],
            ['e' => '☕', 'opts' => ['café', 'cafe'],       'correct' => 'café'],
        ]),

        est('Sinónimos relámpago', '¿Significan lo mismo?', '🟰', 'juego_rapido',
            conTitulo('¿Son sinónimos?', 'Responde rápido', [
                ['e' => '😀', 'n' => 'Alegre y contento',   'ok' => true],
                ['e' => '⬆️', 'n' => 'Subir y bajar',       'ok' => false],
                ['e' => '🏃', 'n' => 'Rápido y veloz',      'ok' => true],
                ['e' => '🌞', 'n' => 'Día y noche',         'ok' => false],
                ['e' => '🏠', 'n' => 'Casa y vivienda',     'ok' => true],
                ['e' => '❄️', 'n' => 'Frío y caliente',     'ok' => false],
                ['e' => '😢', 'n' => 'Triste y apenado',    'ok' => true],
            ])),

        est('Gramática relámpago', 'Identifica la categoría', '📖', 'opcion_multiple', [
            omp('«Correr» es un…',      ['Verbo', 'Sustantivo', 'Adjetivo'], 'Verbo'),
            omp('«Ventana» es un…',     ['Sustantivo', 'Verbo', 'Adjetivo'], 'Sustantivo'),
            omp('«Azul» es un…',        ['Adjetivo', 'Verbo', 'Sustantivo'], 'Adjetivo'),
            omp('«Ellos» es un…',       ['Pronombre', 'Verbo', 'Adjetivo'], 'Pronombre'),
            omp('«Rápidamente» es un…', ['Adverbio', 'Sustantivo', 'Artículo'], 'Adverbio'),
        ]),

        est('Escribe rápido', 'Teclea la palabra', '⌨️', 'teclado',
            ['ESCUELA', 'AMISTAD', 'MURCIÉLAGO', 'BIBLIOTECA', 'TRIÁNGULO']),
    ],
],

[
    'slug'  => 'relampago-de-memoria',
    'title' => 'Relámpago de memoria',
    'description' => 'Cuatro tableros de memoria de dificultad creciente.',
    'objective' => 'Entrenar la memoria visual y la atención sostenida.',
    'icon' => '🧠', 'nivel' => 'todas-las-edades', 'bloque' => 'retos-relampago',
    'duracion' => 10, 'tags' => ['memoria', 'atencion', 'juego'],
    'estaciones' => [

        est('Nivel 1: animales', 'Cuatro parejas', '🐾', 'memoria',
            ['🐶', '🐱', '🐰', '🦊']),

        est('Nivel 2: naturaleza', 'Cinco parejas', '🌿', 'memoria',
            ['🌳', '🌻', '🍄', '🌵', '🍁']),

        est('Nivel 3: objetos', 'Seis parejas', '🎒', 'memoria',
            ['📕', '✏️', '🎒', '📐', '✂️', '🖍️']),

        est('Nivel 4: el reto', 'Ocho parejas', '🏆', 'memoria',
            ['⚽', '🎸', '🚀', '🍎', '🧊', '🔔', '🎯', '🗝️']),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'reto-de-ciencias',
    'title' => 'Reto de ciencias',
    'description' => 'Un desafío que recorre seres vivos, cuerpo, materia y universo de una sentada.',
    'objective' => 'Comprobar de forma integrada los aprendizajes de Ciencias Naturales de primaria.',
    'icon' => '🔬', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 13, 'tags' => ['reto', 'comprension', 'logica'],
    'estaciones' => [

        est('Seres vivos', 'Lo básico de la vida', '🌿', 'opcion_multiple', [
            omp('¿Cuál es la unidad básica de los seres vivos?', ['La célula', 'El átomo', 'El órgano'], 'La célula'),
            omp('¿Qué reino incluye a los hongos?',    ['Fungi', 'Plantae', 'Animalia'], 'Fungi'),
            omp('¿Cuántas patas tiene un insecto?',    ['6', '8', '4'], '6'),
            omp('¿Qué produce la fotosíntesis?',       ['Oxígeno y alimento para la planta', 'Solo agua', 'Nada'], 'Oxígeno y alimento para la planta'),
            omp('¿Qué animal es un anfibio?',          ['La rana', 'La serpiente', 'El águila'], 'La rana'),
        ]),

        est('El cuerpo humano', 'Sistemas y funciones', '🫀', 'opcion_multiple', [
            omp('¿Qué sistema transporta el oxígeno?', ['El circulatorio', 'El digestivo', 'El óseo'], 'El circulatorio'),
            omp('¿Dónde empieza la digestión?',        ['En la boca', 'En el estómago', 'En el intestino'], 'En la boca'),
            omp('¿Qué protege las costillas?',         ['Corazón y pulmones', 'El cerebro', 'Los riñones'], 'Corazón y pulmones'),
            omp('¿Qué sistema coordina con hormonas?', ['El endocrino', 'El respiratorio', 'El muscular'], 'El endocrino'),
            omp('¿Qué células nos defienden de infecciones?', ['Los glóbulos blancos', 'Los glóbulos rojos', 'Las neuronas'], 'Los glóbulos blancos'),
        ]),

        est('Materia y energía', 'Cómo funciona el mundo físico', '⚡', 'opcion_multiple', [
            omp('¿A qué temperatura hierve el agua a nivel del mar?', ['100 °C', '50 °C', '0 °C'], '100 °C'),
            omp('¿Cómo separo la sal del agua salada?', ['Evaporando', 'Filtrando', 'Con un imán'], 'Evaporando'),
            omp('¿Qué necesita un circuito para funcionar?', ['Estar cerrado', 'Estar abierto', 'Estar mojado'], 'Estar cerrado'),
            omp('¿Qué viaja más rápido, la luz o el sonido?', ['La luz', 'El sonido', 'Igual'], 'La luz'),
            omp('¿Qué máquina simple es una rampa?',   ['El plano inclinado', 'La polea', 'La palanca'], 'El plano inclinado'),
        ]),

        est('Desafío científico', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿De dónde viene la energía de los ecosistemas?', ['Del Sol', 'Del suelo', 'Del viento'], 'Del Sol'),
            reto('¿Cuál es el planeta más grande del sistema solar?', ['Júpiter', 'Tierra', 'Saturno'], 'Júpiter'),
            reto('¿Qué produce la rotación de la Tierra?', ['El día y la noche', 'Las estaciones', 'Las mareas'], 'El día y la noche'),
            reto('¿Qué gas expulsamos al exhalar?',    ['Dióxido de carbono', 'Oxígeno', 'Nitrógeno'], 'Dióxido de carbono'),
            reto('¿Qué pasa con el volumen del agua al congelarse?', ['Aumenta', 'Disminuye', 'No cambia'], 'Aumenta'),
        ]),
    ],
],

[
    'slug'  => 'reto-de-lengua',
    'title' => 'Reto de lengua',
    'description' => 'Gramática, ortografía, vocabulario y tipos de texto en un solo desafío.',
    'objective' => 'Comprobar de forma integrada los aprendizajes de Lengua y Literatura de primaria.',
    'icon' => '📚', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 13, 'tags' => ['reto', 'lectura', 'escritura'],
    'estaciones' => [

        est('Gramática', 'Las piezas de la oración', '📖', 'opcion_multiple', [
            omp('En «el gato negro duerme», ¿cuál es el sustantivo?', ['Gato', 'Negro', 'Duerme'], 'Gato'),
            omp('¿Cuál es el adjetivo en esa oración?', ['Negro', 'Gato', 'El'], 'Negro'),
            omp('¿Cuál es el verbo?',                  ['Duerme', 'Gato', 'Negro'], 'Duerme'),
            omp('«Ellos» es un…',                      ['Pronombre', 'Verbo', 'Adjetivo'], 'Pronombre'),
            omp('¿Qué partes mínimas tiene una oración?', ['Sujeto y predicado', 'Solo verbo', 'Solo sujeto'], 'Sujeto y predicado'),
        ]),

        est('Ortografía', 'Escribir bien', '✍️', 'ortografia', [
            ['e' => '🌳', 'opts' => ['árbol', 'arbol'],       'correct' => 'árbol'],
            ['e' => '🏨', 'opts' => ['hotel', 'otel'],        'correct' => 'hotel'],
            ['e' => '🦒', 'opts' => ['jirafa', 'girafa'],     'correct' => 'jirafa'],
            ['e' => '🐄', 'opts' => ['vaca', 'baca'],         'correct' => 'vaca'],
            ['e' => '🎵', 'opts' => ['música', 'musica'],     'correct' => 'música'],
            ['e' => '👞', 'opts' => ['zapato', 'sapato'],     'correct' => 'zapato'],
        ]),

        est('Vocabulario y textos', 'Significado y forma', '🔤', 'opcion_multiple', [
            omp('¿Cuál es sinónimo de «veloz»?',       ['Rápido', 'Lento', 'Pesado'], 'Rápido'),
            omp('¿Cuál es antónimo de «lleno»?',       ['Vacío', 'Repleto', 'Completo'], 'Vacío'),
            omp('¿Qué prefijo significa «lo contrario»?', ['des-', 're-', 'pre-'], 'des-'),
            omp('¿Qué tipo de texto es una receta?',   ['Instructivo', 'Narrativo', 'Argumentativo'], 'Instructivo'),
            omp('¿Qué tipo de texto es una noticia?',  ['Informativo', 'Lírico', 'Dramático'], 'Informativo'),
        ]),

        est('Desafío literario', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Cómo se llama cada línea de un poema?', ['Verso', 'Párrafo', 'Escena'], 'Verso'),
            reto('¿Qué es la moraleja?',               ['La enseñanza de una fábula', 'El título', 'El final'], 'La enseñanza de una fábula'),
            reto('¿Cuál es la sílaba tónica de «médico»?', ['mé', 'di', 'co'], 'mé'),
            reto('«Médico» es una palabra…',           ['Esdrújula', 'Aguda', 'Grave'], 'Esdrújula'),
            reto('¿Qué género se escribe para representarse?', ['Dramático', 'Lírico', 'Narrativo'], 'Dramático'),
        ]),
    ],
],

[
    'slug'  => 'relampago-de-ingles',
    'title' => 'Relámpago de inglés',
    'description' => 'Vocabulario y estructuras básicas de inglés contrarreloj.',
    'objective' => 'Automatizar vocabulario y estructuras básicas de inglés mediante práctica rápida.',
    'icon' => '🇬🇧', 'nivel' => 'primaria-media', 'bloque' => 'retos-relampago',
    'duracion' => 10, 'tags' => ['ingles', 'reto', 'vocabulario'],
    'estaciones' => [

        est('Vocabulario relámpago', '¿Está bien traducido?', '⚡', 'juego_rapido',
            conTitulo('¿Es correcta la traducción?', 'Responde rápido', [
                ['e' => '🏠', 'n' => 'House = Casa',      'ok' => true],
                ['e' => '🐶', 'n' => 'Dog = Gato',        'ok' => false],
                ['e' => '📕', 'n' => 'Book = Libro',      'ok' => true],
                ['e' => '💧', 'n' => 'Water = Fuego',     'ok' => false],
                ['e' => '☀️', 'n' => 'Sun = Sol',         'ok' => true],
                ['e' => '🍎', 'n' => 'Apple = Manzana',   'ok' => true],
                ['e' => '👦', 'n' => 'Boy = Niña',        'ok' => false],
            ]), 'en-US'),

        est('Colores y números', 'Lo más básico', '🎨', 'emparejar', [
            ['e' => '🔴', 'w' => 'Red'],
            ['e' => '🔵', 'w' => 'Blue'],
            ['e' => '🟢', 'w' => 'Green'],
            ['e' => '🟡', 'w' => 'Yellow'],
            ['e' => '⚫', 'w' => 'Black'],
            ['e' => '⚪', 'w' => 'White'],
        ], 'en-US'),

        est('Gramática relámpago', 'Elige la forma correcta', '📝', 'opcion_multiple', [
            omp('I ___ a student.',      ['am', 'is', 'are'], 'am'),
            omp('She ___ my sister.',    ['is', 'am', 'are'], 'is'),
            omp('They ___ happy.',       ['are', 'is', 'am'], 'are'),
            omp('He ___ football.',      ['plays', 'play', 'playing'], 'plays'),
            omp('We ___ to school.',     ['go', 'goes', 'going'], 'go'),
        ], 'en-US'),

        est('Escribe en inglés', 'Teclea la palabra', '⌨️', 'teclado',
            ['HOUSE', 'WATER', 'FRIEND', 'SCHOOL', 'FAMILY'], 'en-US'),
    ],
],

[
    'slug'  => 'reto-de-observacion',
    'title' => 'Reto de observación',
    'description' => 'Fijarse en los detalles: encontrar diferencias, intrusos y patrones ocultos.',
    'objective' => 'Entrenar la atención selectiva y la discriminación visual.',
    'icon' => '👀', 'nivel' => 'todas-las-edades', 'bloque' => 'retos-relampago',
    'duracion' => 11, 'tags' => ['observacion', 'atencion', 'reto'],
    'estaciones' => [

        est('Encuentra el intruso', '¿Cuál no encaja?', '🔍', 'opcion_multiple', [
            omp('¿Cuál no encaja con los demás?',   ['🚗', '🐶', '🐱', '🐰'], '🚗'),
            omp('¿Cuál no encaja con los demás?',   ['🍎', '🍌', '🪑', '🍇'], '🪑'),
            omp('¿Cuál no encaja con los demás?',   ['⚽', '🏀', '🏐', '📕'], '📕'),
            omp('¿Cuál no encaja con los demás?',   ['🌳', '🌻', '🌵', '🔨'], '🔨'),
            omp('¿Cuál no encaja con los demás?',   ['🚌', '🚗', '🚲', '🐟'], '🐟'),
        ]),

        est('Sopa de la observación', 'Encuentra las palabras escondidas', '🔤', 'sopa_letras',
            sopa(['MIRAR', 'ATENTO', 'DETALLE', 'BUSCAR', 'ENCONTRAR'], 12)),

        est('Sigue el patrón', '¿Qué viene después?', '🔁', 'opcion_multiple', [
            omp('⭐ 🌙 ⭐ 🌙 … ¿qué sigue?',       ['⭐', '🌙', '☀️'], '⭐'),
            omp('🔴 🔴 🔵 🔴 🔴 🔵 … ¿qué sigue?', ['🔴', '🔵', '🟢'], '🔴'),
            omp('🐶 🐱 🐭 🐶 🐱 🐭 … ¿qué sigue?', ['🐶', '🐱', '🐭'], '🐶'),
            omp('1, 3, 5, 7 … ¿qué sigue?',      ['9', '8', '10'], '9'),
            omp('A, C, E, G … ¿qué sigue?',      ['I', 'H', 'J'], 'I'),
        ]),

        est('Memoria de precisión', 'Ocho parejas', '🧠', 'memoria',
            ['🔑', '🎈', '🧩', '🪁', '🎁', '🔦', '🧭', '⏳']),
    ],
],


[
    'slug'  => 'reto-de-sociales',
    'title' => 'Reto de sociales',
    'description' => 'Geografía, historia, ciudadanía y economía de Colombia en un solo desafío.',
    'objective' => 'Comprobar de forma integrada los aprendizajes de Ciencias Sociales de primaria.',
    'icon' => '🌎', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 13, 'tags' => ['reto', 'colombia', 'historia'],
    'estaciones' => [

        est('Geografía', 'Dónde está cada cosa', '🗺️', 'opcion_multiple', [
            omp('¿Cuántas regiones naturales tiene Colombia?', ['Seis', 'Tres', 'Diez'], 'Seis'),
            omp('¿En qué continente está Colombia?',   ['América', 'Europa', 'África'], 'América'),
            omp('¿Qué dos océanos bañan a Colombia?',  ['Pacífico y Atlántico', 'Índico y Pacífico', 'Solo el Atlántico'], 'Pacífico y Atlántico'),
            omp('¿Qué línea divide la Tierra en norte y sur?', ['El ecuador', 'Greenwich', 'El trópico'], 'El ecuador'),
            omp('¿Cuántos departamentos tiene Colombia?', ['32', '12', '50'], '32'),
        ]),

        est('Historia', 'Cómo llegamos aquí', '⏳', 'opcion_multiple', [
            omp('¿Por dónde llegaron los primeros pobladores a América?', ['Por el estrecho de Bering', 'Por el Atlántico', 'Por el Pacífico sur'], 'Por el estrecho de Bering'),
            omp('¿Qué pueblo construyó Machu Picchu?', ['Los incas', 'Los mayas', 'Los muiscas'], 'Los incas'),
            omp('¿Qué se celebra el 20 de julio?',     ['La independencia de Colombia', 'El día del idioma', 'El trabajo'], 'La independencia de Colombia'),
            omp('¿En qué batalla se selló la independencia en 1819?', ['Boyacá', 'Cartagena', 'Bolívar'], 'Boyacá'),
            omp('¿En qué año llegó Colón a América?',  ['1492', '1810', '1600'], '1492'),
        ]),

        est('Ciudadanía y economía', 'Cómo funciona el país', '🏛️', 'opcion_multiple', [
            omp('¿Qué rama del poder hace las leyes?', ['La legislativa', 'La ejecutiva', 'La judicial'], 'La legislativa'),
            omp('¿Quién gobierna un municipio?',       ['El alcalde', 'El gobernador', 'El presidente'], 'El alcalde'),
            omp('¿Cuáles son los sectores económicos?', ['Primario, secundario y terciario', 'Norte, sur y centro', 'Público y privado solamente'], 'Primario, secundario y terciario'),
            omp('¿Qué producto agrícola es famoso de Colombia?', ['El café', 'El trigo', 'La uva'], 'El café'),
            omp('¿Desde qué edad se vota en Colombia?', ['18 años', '16 años', '21 años'], '18 años'),
        ]),

        est('Desafío social', 'La prueba final', '🏆', 'desafio_final', [
            reto('¿Qué es la democracia?',             ['El poder del pueblo para decidir', 'El poder de uno solo', 'Una ley'], 'El poder del pueblo para decidir'),
            reto('¿Qué países formaron la Gran Colombia?', ['Colombia, Venezuela, Ecuador y Panamá', 'Solo Colombia', 'Colombia y Perú'], 'Colombia, Venezuela, Ecuador y Panamá'),
            reto('¿Qué es el patrimonio cultural?',    ['Lo que una comunidad hereda y conserva', 'Un tipo de dinero', 'Un edificio nuevo'], 'Lo que una comunidad hereda y conserva'),
            reto('¿Qué produce la traslación de la Tierra?', ['Las estaciones', 'El día y la noche', 'Las mareas'], 'Las estaciones'),
            reto('¿Por qué es tan biodiversa Colombia?', ['Por su ubicación y su variedad de climas', 'Por su tamaño', 'Por casualidad'], 'Por su ubicación y su variedad de climas'),
        ]),
    ],
],


[
    'slug'  => 'maraton-de-crucigramas',
    'title' => 'Maratón de crucigramas',
    'description' => 'Cuatro crucigramas mezclados: matemáticas, ciencias, sociales y lengua.',
    'objective' => 'Recuperar vocabulario de todas las materias sin saber de antemano cuál toca.',
    'icon' => '🔠', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 16, 'tags' => ['reto', 'vocabulario', 'logica'],
    'estaciones' => [

        /*
         * Los cuatro crucigramas van sin avisar de qué materia es cada
         * uno. Con la etiqueta puesta, el niño busca en el cajón correcto;
         * sin ella, tiene que reconocer de qué se le está hablando, que es
         * la transferencia que piden las mallas.
         */
        est('Crucigrama uno', 'No se dice de qué materia es', '1️⃣', 'crucigrama',
            crucigrama([
                ['w' => 'FRACCION',  'pista' => 'Parte de un todo, como un medio'],
                ['w' => 'AREA',      'pista' => 'La superficie que ocupa una figura'],
                ['w' => 'RESTA',     'pista' => 'Operación de quitar'],
                ['w' => 'PAR',       'pista' => 'Así es un número que se divide entre dos'],
                ['w' => 'MEDIA',     'pista' => 'La suma dividida entre la cantidad de datos'],
                ['w' => 'CERO',      'pista' => 'El único número que no es positivo ni negativo'],
            ])),

        est('Crucigrama dos', 'Sigue sin decirse', '2️⃣', 'crucigrama',
            crucigrama([
                ['w' => 'OXIGENO',   'pista' => 'Gas que necesitamos para respirar'],
                ['w' => 'RAIZ',      'pista' => 'Absorbe el agua del suelo'],
                ['w' => 'IMAN',      'pista' => 'Atrae el hierro'],
                ['w' => 'MATERIA',   'pista' => 'De esto está hecho todo lo que ocupa espacio'],
                ['w' => 'CALOR',     'pista' => 'Hace que el hielo se derrita'],
                ['w' => 'GERMEN',    'pista' => 'Ser diminuto que puede enfermarnos'],
            ])),

        est('Crucigrama tres', 'Tampoco aquí', '3️⃣', 'crucigrama',
            crucigrama([
                ['w' => 'MAPA',      'pista' => 'Dibujo plano de un territorio'],
                ['w' => 'NORTE',     'pista' => 'Lo que señala siempre la brújula'],
                ['w' => 'RIO',       'pista' => 'Corriente de agua que va al mar'],
                ['w' => 'CIUDAD',    'pista' => 'Lugar con mucha población y servicios'],
                ['w' => 'ISLA',      'pista' => 'Tierra rodeada de agua'],
                ['w' => 'MUNICIPIO', 'pista' => 'Lo gobierna un alcalde'],
            ])),

        est('Crucigrama cuatro', 'El último', '4️⃣', 'crucigrama',
            crucigrama([
                ['w' => 'CUENTO',    'pista' => 'Relato breve con inicio, nudo y desenlace'],
                ['w' => 'VERSO',     'pista' => 'Cada línea de un poema'],
                ['w' => 'PUNTO',     'pista' => 'Signo que cierra una oración'],
                ['w' => 'COMA',      'pista' => 'Signo que separa los elementos de una lista'],
                ['w' => 'NARRADOR',  'pista' => 'Quien cuenta la historia'],
                ['w' => 'RIMA',      'pista' => 'Sonidos iguales al final de dos versos'],
            ])),
    ],
],

[
    'slug'  => 'textos-con-huecos',
    'title' => 'Textos con huecos',
    'description' => 'Cuatro textos de materias distintas a los que les faltan palabras.',
    'objective' => 'Deducir la palabra que falta usando el contexto de textos de varias materias.',
    'icon' => '📝', 'nivel' => 'primaria-superior', 'bloque' => 'repaso-por-grado',
    'duracion' => 15, 'tags' => ['lectura', 'comprension', 'reto'],
    'estaciones' => [

        est('Un texto de ciencias', 'Lee y completa', '🔬', 'completar_texto',
            conTitulo('Completa el texto', 'La respuesta está en la frase', [
                [
                    'titulo' => 'Los estados del agua',
                    'texto'  => 'El agua puede estar en tres estados. En el congelador se vuelve '
                              . '___ y tiene forma propia. En un vaso es ___ y toma la forma del '
                              . 'recipiente. Al hervir se convierte en ___ y ocupa todo el espacio '
                              . 'que encuentra. Lo que hace cambiar de estado es el ___.',
                    'huecos' => ['sólida', 'líquida', 'vapor', 'calor'],
                    'extra'  => ['dura', 'humo', 'frío'],
                ],
            ])),

        est('Un texto de sociales', 'Lee y completa', '🌎', 'completar_texto',
            conTitulo('Completa el texto', 'Fíjate en el sentido de la frase', [
                [
                    'titulo' => 'Vivir en comunidad',
                    'texto'  => 'Las normas no existen para ___, sino para que muchas personas puedan '
                              . 'convivir sin hacerse daño. En una ___ las decisiones se toman entre '
                              . 'todos y se eligen ___ que llevan la voz del grupo. Quien piensa '
                              . 'distinto también tiene ___ a ser escuchado.',
                    'huecos' => ['molestar', 'democracia', 'representantes', 'derecho'],
                    'extra'  => ['ayudar', 'ciudad', 'deber'],
                ],
            ])),

        est('Un texto de salud', 'Lee y completa', '💚', 'completar_texto',
            conTitulo('Completa el texto', 'Piensa en lo que ya sabes del cuerpo', [
                [
                    'titulo' => 'Antes de hacer deporte',
                    'texto'  => 'Nunca se empieza al máximo: primero se ___, para que el cuerpo '
                              . 'entre en calor y evitar una ___. Durante el ejercicio hay que beber '
                              . '___, sobre todo si hace calor. Al terminar conviene ___ y volver a '
                              . 'la calma poco a poco.',
                    'huecos' => ['calienta', 'lesión', 'agua', 'estirar'],
                    'extra'  => ['corre', 'medalla', 'gaseosa'],
                ],
            ])),

        est('Un texto de tecnología', 'Lee y completa', '💻', 'completar_texto',
            conTitulo('Completa el texto', 'El último', [
                [
                    'titulo' => 'Cuidar la cuenta',
                    'texto'  => 'Una buena contraseña es ___ y mezcla letras, números y símbolos. '
                              . 'No se usa la misma en todos los sitios: si roban una, entrarían a '
                              . '___. Tampoco se ___ con nadie, ni con los amigos. Y al terminar en '
                              . 'un computador compartido, hay que ___ sesión.',
                    'huecos' => ['larga', 'todo', 'comparte', 'cerrar'],
                    'extra'  => ['corta', 'nada', 'abrir'],
                ],
            ])),
    ],
],

],

'reasignar' => [],

];
