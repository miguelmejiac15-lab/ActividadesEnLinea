<?php
/**
 * Juegos y Retos · 3.º a 6.º
 *
 * Son repasos que mezclan materias, así que su «antes de empezar» no
 * enseña un tema nuevo: recuerda en pocas líneas lo que se va a poner a
 * prueba y cómo afrontar un reto así.
 */

return [

    'reto-cientifico' => intro(
        'Este reto mezcla varias ramas de las **ciencias**: seres vivos, materia, clima y espacio. Antes de empezar, recuerda las ideas que más aparecen.',
        ['Las plantas producen **oxígeno**; los herbívoros comen **plantas**.', 'Evaporación: líquido → **gas**. La **gravedad** nos mantiene en el suelo.', 'La **rotación** de la Tierra produce el día y la noche.'],
        [
            completa('Pasar de líquido a gas se llama ___.', 'evaporación', ['fusión', 'condensación']),
            identifica('¿Qué gas producen las plantas y respiramos?', 'Oxígeno', ['Humo', 'Vapor']),
        ]
    ),

    'reto-historico' => intro(
        'Este reto repasa **fechas**, **personajes** y **civilizaciones**. En historia no basta con memorizar: importa el **orden** en que pasaron las cosas.',
        ['Un **siglo** son 100 años; una década, 10.', '**1810**: grito de independencia de Colombia. **Simón Bolívar**: el Libertador.', 'Las pirámides más famosas están en **Egipto**; la democracia nació en **Grecia**.'],
        [
            completa('Un siglo tiene ___ años.', '100', ['10', '1.000']),
            identifica('¿Quién es conocido como «el Libertador»?', 'Simón Bolívar', ['Cristóbal Colón', 'Antonio Nariño']),
        ]
    ),

    'reto-de-ingles' => intro(
        'Este reto repasa **vocabulario**, **gramática** y frases útiles de inglés. Recuerda las formas del verbo **to be**, que aparecen en todas partes.',
        ['**I am** · **you are** · **he / she / it is** · **we / they are**.', 'Vocabulario básico: dog (perro), house (casa), water (agua).', '**How much is it?** pregunta el precio.'],
        [
            completa('She ___ a teacher.', 'is', ['am', 'are']),
            identifica('«Casa» en inglés es…', 'House', ['Horse', 'Mouse']),
        ]
    ),

    'reto-de-logica' => intro(
        'En este reto no hay materia: solo **razonamiento**. Patrones, deducciones y acertijos. La clave es **leer con calma** y no responder lo primero que se te ocurra.',
        ['En un patrón, busca la **regla** que pasa de un número al siguiente.', 'Deducir: si Ana > Beto y Beto > Caro, entonces Ana > Caro.', 'En los acertijos, desconfía de lo que **das por hecho**.'],
        [
            completa('Ana es más alta que Beto y Beto más alto que Caro. La más baja es ___.', 'Caro', ['Ana', 'Beto']),
            identifica('Tengo 5 manzanas y me como 2. ¿Cuántas tengo?', '3', ['5', '7']),
        ]
    ),

    'reto-de-tercero' => intro(
        'Este reto mezcla lo más importante de **tercer grado**: números hasta el **millar**, gramática, el **cuerpo humano** y Colombia. Es un repaso: si algo no lo recuerdas, es una buena pista de qué volver a practicar.',
        ['Valor posicional: en 472, el 4 vale **400**.', 'El **adjetivo** dice cómo es algo; el **verbo**, qué pasa.', 'El **corazón** bombea la sangre; el **cráneo** protege el cerebro.'],
        [
            completa('En 472, el 4 vale ___.', '400', ['4', '40']),
            identifica('En «el perro grande», ¿cuál es el adjetivo?', 'Grande', ['Perro', 'El']),
        ]
    ),

    'reto-de-cuarto' => intro(
        'Este reto mezcla lo más importante de **cuarto grado**: números hasta el **millón**, **fracciones**, los **sistemas del cuerpo** y la historia de los primeros pobladores.',
        ['En 45.320, el 4 vale **40.000**.', 'La mitad de 20 es **10**: 1/2 de 20.', 'Los nutrientes se absorben en el **intestino delgado**.'],
        [
            completa('1/2 de 20 es ___.', '10', ['2', '40']),
            identifica('¿Qué gas expulsamos al exhalar?', 'Dióxido de carbono', ['Oxígeno', 'Helio']),
        ]
    ),

    'relampago-de-calculo' => intro(
        'El **cálculo mental** es hacer cuentas **de cabeza**, sin papel. Con práctica se vuelve automático, y eso deja la mente libre para pensar en problemas más difíciles.',
        ['Sumar 9: suma 10 y **quita 1**. 7 + 9 = 17 − 1 = 16.', 'Las **tablas** conviene saberlas de memoria.', 'Primero **acierta**; la velocidad llega sola.'],
        [
            completa('7 + 9 = 7 + 10 − ___.', '1', ['9', '10']),
            identifica('¿Cuánto es 6 × 7?', '42', ['36', '48']),
        ]
    ),

    'relampago-de-palabras' => intro(
        'Este reto rápido repasa **ortografía**, **sinónimos** y **gramática**. Aunque sea contrarreloj, mira cada palabra con atención: una letra cambia todo (casa y caza no son lo mismo).',
        ['**Sinónimos**: significan lo mismo (alegre – contento).', '**Verbo**: acción (correr). **Sustantivo**: nombre (ventana).', 'Revisa la **tilde**: árbol, canción.'],
        [
            completa('«Correr» es un ___.', 'verbo', ['sustantivo', 'adjetivo']),
            identifica('¿Cuál está bien escrita?', 'Árbol', ['Arbol', 'Árvol']),
        ]
    ),

    'relampago-de-ingles' => intro(
        'Este reto rápido repasa **vocabulario** y el verbo **to be** en inglés. Lee cada palabra completa antes de responder: hay parejas que se parecen.',
        ['**I am** · **you are** · **he / she is** · **we / they are**.', 'House = casa. Dog = perro. Water = agua.', 'Colores: **red**, **blue**, **green**, **yellow**.'],
        [
            completa('I ___ a student.', 'am', ['is', 'are']),
            identifica('«Dog» significa…', 'Perro', ['Gato', 'Casa']),
        ]
    ),

    'reto-de-quinto' => intro(
        'Este reto mezcla lo más importante de **quinto grado**: fracciones equivalentes, ecosistemas, Colombia y el razonamiento con textos.',
        ['Fracciones equivalentes: 2/4 = **1/2**.', 'La energía de las cadenas alimenticias viene del **Sol**.', 'Un número **primo** solo se divide entre 1 y sí mismo.'],
        [
            completa('2/4 es equivalente a ___.', '1/2', ['1/4', '2/2']),
            identifica('¿Cuál de estos es un número primo?', '13', ['9', '15']),
        ]
    ),

    'reto-de-sexto' => intro(
        'Este reto mezcla lo más importante de **sexto grado**: decimales, materia y energía, la independencia y el análisis de datos.',
        ['Decimales: 0,5 es **mayor** que 0,05.', 'Un circuito necesita un **camino cerrado**.', 'La independencia se selló en la **Batalla de Boyacá** (1819).'],
        [
            completa('2,5 × 10 = ___.', '25', ['2,50', '250']),
            identifica('¿Qué se celebra el 20 de julio en Colombia?', 'La independencia', ['La Constitución', 'El descubrimiento']),
        ]
    ),

    'reto-de-ciencias' => intro(
        'Este reto recorre toda la ciencia de primaria: **seres vivos**, **cuerpo humano**, **materia** y **universo**. Recuerda las ideas que lo sostienen todo.',
        ['La **célula** es la unidad básica de los seres vivos.', 'El sistema **circulatorio** transporta el oxígeno.', 'La energía de los ecosistemas viene del **Sol**.'],
        [
            completa('La unidad básica de los seres vivos es la ___.', 'célula', ['roca', 'hoja']),
            identifica('¿Dónde empieza la digestión?', 'En la boca', ['En el estómago', 'En los pulmones']),
        ]
    ),

    'reto-de-lengua' => intro(
        'Este reto recorre **gramática**, **ortografía**, **vocabulario** y **literatura**. Recuerda las categorías de palabras y las partes de los textos.',
        ['**Sustantivo**: nombra. **Adjetivo**: describe. **Verbo**: dice qué pasa.', '**Sinónimo**: igual significado. **Antónimo**: significado contrario.', 'Poema: **versos** y estrofas. Fábula: **moraleja**.'],
        [
            completa('Cada línea de un poema se llama ___.', 'verso', ['párrafo', 'viñeta']),
            identifica('¿Cuál es antónimo de «lleno»?', 'Vacío', ['Completo', 'Grande']),
        ]
    ),

    'reto-de-sociales' => intro(
        'Este reto recorre las **Ciencias Sociales** de primaria: la geografía de Colombia, la historia de América y cómo se organiza el país.',
        ['Colombia tiene **seis** regiones naturales y está en **América**.', 'Los primeros pobladores llegaron por el **estrecho de Bering**.', 'La rama **legislativa** hace las leyes; el **alcalde** gobierna el municipio.'],
        [
            completa('Colombia tiene ___ regiones naturales.', 'seis', ['cuatro', 'ocho']),
            identifica('¿Qué es la democracia?', 'El poder del pueblo para decidir', ['El gobierno de un rey', 'Una región de Colombia']),
        ]
    ),

    'maraton-de-crucigramas' => intro(
        'Cuatro crucigramas y ninguno avisa de qué materia es: matemáticas, ciencias, sociales o lengua. Cada pista es una **definición**; tu trabajo es reconocer de qué palabra se trata.',
        ['Lee la pista y pregúntate: ¿de **qué materia** es esto?', 'Cuenta las casillas: te dicen cuántas letras tiene.', 'Empieza por las que sepas: sus letras te ayudan con las demás.'],
        [
            completa('«Parte de un todo dividido en partes iguales» es una ___.', 'fracción', ['célula', 'estrofa']),
            identifica('«Gas que producen las plantas y respiramos» es…', 'Oxígeno', ['Hidrógeno', 'Carbono']),
        ]
    ),

    'textos-con-huecos' => intro(
        'Cuatro textos de materias distintas a los que les faltan palabras. Para completarlos hay que leer **la frase entera** y usar el **contexto**: lo que el resto del texto ya te cuenta.',
        ['Lee todo el párrafo antes de llenar el primer hueco.', 'Pregúntate qué palabra tiene **sentido** en ese lugar.', 'Revisa que concuerde en **género** y **número**.'],
        [
            completa('En el congelador, el agua se vuelve ___.', 'sólida', ['gaseosa', 'caliente']),
            identifica('¿Qué te ayuda a encontrar la palabra que falta?', 'El contexto: lo que dice el resto del texto', ['Adivinar al azar', 'La primera letra de la página']),
        ]
    ),

    'repaso-de-sexto' => intro(
        'Este es el reto que **cierra la primaria**: mezcla matemáticas, lengua, ciencias y sociales del último grado. Tómalo con calma y aprovecha para ver qué dominas y qué te conviene repasar.',
        ['3/4 de 20 = **15**. 0,25 = **1/4**.', 'El **sujeto** es quien realiza la acción: «**Los niños** corren».', 'La Constitución de Colombia es de **1991**.'],
        [
            completa('0,25 es lo mismo que ___.', '1/4', ['1/2', '2/5']),
            identifica('En «Los niños corren», ¿cuál es el sujeto?', 'Los niños', ['Corren', 'Ninguno']),
        ]
    ),

];
