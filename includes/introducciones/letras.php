<?php
/**
 * Aventura de las Letras · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'ciudad-de-los-cuentos' => intro(
        'Una **fábula** es un cuento corto, casi siempre con animales que hablan, que termina con una enseñanza llamada **moraleja**. Las más famosas las contó Esopo, en Grecia, hace unos 2.500 años.',
        ['Quiénes son los **personajes** y cómo es cada uno.', 'En qué **orden** pasan los hechos.', 'Qué **moraleja** deja la historia.'],
        [
            completa('La enseñanza con la que termina una fábula se llama ___.', 'moraleja', ['título', 'rima']),
            identifica('¿Quiénes suelen ser los personajes de una fábula?', 'Animales que hablan', ['Robots', 'Solo reyes']),
        ],
        'En «La liebre y la tortuga», la moraleja es que la constancia vale más que la rapidez.'
    ),

    'mar-de-la-ortografia' => intro(
        'La **ortografía** son las reglas para escribir bien las palabras. Hay letras que suenan igual —la B y la V, la C, la S y la Z— y una que no suena: la **H**. Por eso no basta con oír una palabra: hay que recordar cómo se escribe.',
        ['Elegir la escritura correcta entre varias parecidas.', 'Recordar las palabras que llevan **H** aunque no suene.', 'Saber si una palabra lleva **tilde**.'],
        [
            completa('La letra ___ se escribe, pero no suena.', 'H', ['B', 'S']),
            identifica('¿Cuál está bien escrita?', 'Huevo', ['Uevo', 'Güevo']),
        ],
        '«Hielo» y «huevo» llevan H al principio, aunque al decirlas no se oiga.'
    ),

    'carrera-de-palabras' => intro(
        'Escribir rápido no es correr: es **reconocer** cada letra de la palabra sin tener que pensarla. Cuanto más practicas, más automático se vuelve, y así la cabeza queda libre para pensar en lo que quieres decir.',
        ['Unir cada palabra con su dibujo.', 'Escribir palabras en el **teclado** sin equivocarte.', 'Ganar velocidad sin perder la precisión.'],
        [
            completa('Primero se busca escribir bien y después, más ___.', 'rápido', ['fuerte', 'grande']),
            identifica('¿Qué es más importante al escribir en el teclado?', 'Que la palabra quede bien escrita', ['Terminar primero aunque tenga errores', 'Usar solo un dedo']),
        ]
    ),

    'letra-k' => intro(
        'La **K** suena igual que la C en «casa». Casi no se usa en español: aparece sobre todo en palabras que llegaron de **otros idiomas**, como koala (de Australia), kimono (de Japón) o kiwi (de Nueva Zelanda).',
        ['Suena **ka, ke, ki, ko, ku**.', 'Está en pocas palabras, casi todas prestadas de otras lenguas.', 'También se usa en **kilo**: kilogramo, kilómetro.'],
        [
            completa('La K suena igual que la C de «___».', 'casa', ['cena', 'cielo']),
            identifica('¿Cuál de estas palabras se escribe con K?', 'Koala', ['Queso', 'Casa']),
        ],
        '«Karate» y «kayak» se escriben con K porque vienen del japonés y de la lengua de los inuit.'
    ),

    'letra-v' => intro(
        'En español la **V** y la **B** suenan igual. Por eso la única forma de saber cuál va es **conocer la palabra** y algunas reglas que ayudan.',
        ['Suena **va, ve, vi, vo, vu**, igual que la B.', 'Después de **N** se escribe V: invierno, enviar.', 'Los adjetivos que terminan en **-ava, -ave, -ivo** van con V: nueva, suave, activo.'],
        [
            completa('La V suena igual que la letra ___.', 'B', ['F', 'P']),
            identifica('¿Cuál está bien escrita?', 'Invierno', ['Inbierno', 'Imvierno']),
        ],
        '«Vaca» y «baca» suenan igual, pero la vaca es el animal y la baca es la parrilla del techo de un carro.'
    ),

    'letra-w' => intro(
        'La **W** es una letra que el español tomó prestada. Suele sonar como **gu** (wafle se dice «guafle») y aparece en palabras que vienen del inglés o del alemán.',
        ['Suena casi siempre como **gua, güe, güi**.', 'Aparece en palabras prestadas: wifi, kiwi, wafle, web.', 'Se llama **uve doble** o doble ve.'],
        [
            completa('La W suele sonar como ___.', 'gu', ['ve', 'ere']),
            identifica('¿Cuál de estas palabras lleva W?', 'Kiwi', ['Queso', 'Oso']),
        ]
    ),

    'letra-x' => intro(
        'La **X** casi nunca suena como su nombre. Entre vocales suena **ks**: taxi se dice «tak-si». Y en algunos nombres antiguos, como **México**, suena como la J.',
        ['Entre vocales suena **ks**: taxi, boxeo, saxofón.', 'Al principio de palabra suena casi como **s**: xilófono.', 'En México y Oaxaca suena como **J**.'],
        [
            completa('En «taxi», la X suena ___.', 'ks', ['j', 'ch']),
            identifica('¿En qué palabra la X suena como J?', 'México', ['Taxi', 'Boxeo']),
        ]
    ),

    'letra-y' => intro(
        'La **Y** tiene dos trabajos. Delante de una vocal suena como consonante: **ya, ye, yi, yo, yu**. Sola o al final de palabra suena como la vocal **i**: «y», rey, muy.',
        ['Con vocal: yate, yoyo, playa.', 'Al final de palabra suena **i**: rey, hoy, ley.', 'Sola es una palabra que une: «pan **y** leche».'],
        [
            completa('Al final de «rey», la Y suena como la ___.', 'i', ['a', 'u']),
            identifica('¿En qué palabra la Y suena como consonante?', 'Yate', ['Rey', 'Muy']),
        ]
    ),

    'letra-z' => intro(
        'La **Z** va con **a, o, u**: za, zo, zu. Con la E y la I casi siempre se cambia por **C**: zapato, pero cepillo. Y cuando una palabra termina en Z, su plural se escribe con C: luz → luces.',
        ['**za, zo, zu**: zapato, zorro, azúcar.', 'Con e, i se escribe **ce, ci**.', 'Plural: pez → pe**c**es, luz → lu**c**es.'],
        [
            completa('El plural de «luz» es ___.', 'luces', ['luzes', 'luzs']),
            identifica('¿Cuál está bien escrita?', 'Zapato', ['Sapato', 'Capato']),
        ]
    ),

    'letra-ch' => intro(
        'La **CH** son dos letras que juntas hacen **un solo sonido**: che. A eso se le llama **dígrafo**. No suena ni como C ni como H: suena «ch», como cuando pides silencio.',
        ['Suena **cha, che, chi, cho, chu**.', 'Son dos letras, pero **un sonido**.', 'Está al principio o en medio: chaleco, noche, mochila.'],
        [
            completa('Cuando dos letras hacen un solo sonido se llama ___.', 'dígrafo', ['sílaba', 'tilde']),
            identifica('¿Qué palabra lleva CH?', 'Mochila', ['Casa', 'Hielo']),
        ]
    ),

    'letra-qu' => intro(
        'Para escribir el sonido «k» delante de **e** o **i** usamos **QU**. La **U no suena**: queso se dice «keso». Delante de a, o, u volvemos a la C: casa, cosa, cuna.',
        ['**que, qui**: queso, esquí, mosquito.', 'La **U** está escrita pero **no se pronuncia**.', 'Nunca se escribe «qa» ni «qo».'],
        [
            completa('En «queso», la letra ___ no suena.', 'U', ['E', 'S']),
            identifica('¿Cuál está bien escrita?', 'Parque', ['Parce', 'Parke']),
        ]
    ),

    'letra-gue-gui' => intro(
        'La G tiene dos sonidos. Con a, o, u suena suave: **ga, go, gu**. Pero delante de e, i suena fuerte, como la J: gente, girasol. Para que suene suave con e, i se pone una **U que no suena**: **gue, gui**.',
        ['**gue, gui**: la U está escrita pero no se pronuncia.', 'En «guitarra» la U calla: no se dice «gu-i-ta-rra».', 'Si la U sí debe sonar, lleva **diéresis**: pingüino, cigüeña.'],
        [
            completa('En «guitarra», la U ___.', 'no suena', ['suena fuerte', 'se escribe con tilde']),
            identifica('¿Qué palabra necesita diéresis (ü) porque la U sí suena?', 'Pingüino', ['Guiso', 'Juguete']),
        ],
        'Merengue, hamburguesa y espagueti llevan GUE; guiso, águila y guitarra llevan GUI.'
    ),

    'el-sustantivo-y-el-articulo' => intro(
        'El **sustantivo** es la palabra que **nombra**: personas, animales, cosas o lugares. Delante suele ir un **artículo** (el, la, los, las, un, una), que tiene que coincidir con él en género y número.',
        ['**Común** nombra cualquiera: río, niña. **Propio** nombra uno en especial y va con mayúscula: Magdalena, Sofía.', 'Género: el libro (masculino), la mesa (femenino).', 'Número: el árbol (singular), los árboles (plural).'],
        [
            completa('La palabra que nombra personas, animales o cosas es el ___.', 'sustantivo', ['verbo', 'adjetivo']),
            identifica('¿Cuál es un sustantivo propio?', 'Colombia', ['país', 'ciudad']),
        ],
        'En «la niña lee», «niña» es el sustantivo y «la» es su artículo.'
    ),

    'el-adjetivo' => intro(
        'El **adjetivo** es la palabra que dice **cómo es** algo: grande, azul, frío, alegre. Acompaña al sustantivo y se ajusta a él: si el sustantivo es femenino y plural, el adjetivo también.',
        ['Describe cualidades: «el perro **grande**».', 'Concuerda: las flores **bonitas**, el libro **nuevo**.', 'Sirve para comparar: más **alto** que, menos **rápido** que.'],
        [
            completa('En «la casa roja», el adjetivo es ___.', 'roja', ['casa', 'la']),
            identifica('¿Cuál está bien dicha?', 'Las flores bonitas', ['Las flores bonito', 'La flores bonitas']),
        ]
    ),

    'el-verbo-y-los-tiempos' => intro(
        'El **verbo** es la palabra que dice **qué pasa** en una oración: una acción (correr, leer) o un estado (ser, estar). Cambia según **cuándo** pasa y **quién** lo hace.',
        ['**Pasado**: ya pasó (comí). **Presente**: pasa ahora (como). **Futuro**: pasará (comeré).', 'Se conjuga según la persona: yo voy, nosotros vamos.', 'El **pronombre** reemplaza al sustantivo: María → ella.'],
        [
            completa('«Mañana jugaré» está en tiempo ___.', 'futuro', ['pasado', 'presente']),
            identifica('En «Ana corre rápido», ¿cuál es el verbo?', 'Corre', ['Ana', 'Rápido']),
        ]
    ),

    'mayusculas-y-puntuacion' => intro(
        'Los **signos de puntuación** le dicen al lector dónde parar, dónde respirar y cómo leer. Sin ellos un texto se vuelve una fila de palabras. La **mayúscula** marca dónde empieza una oración y los nombres propios.',
        ['El **punto** cierra una oración; después va mayúscula.', 'La **coma** separa los elementos de una lista.', 'En español, preguntas y exclamaciones llevan signo al **abrir y al cerrar**: ¿…? ¡…!'],
        [
            completa('Después de un punto se escribe con ___.', 'mayúscula', ['minúscula', 'tilde']),
            identifica('¿Cuál está bien escrita?', '¿Cómo estás?', ['Cómo estás?', '¿Cómo estás']),
        ]
    ),

    'sinonimos-y-antonimos' => intro(
        'Los **sinónimos** son palabras que significan **lo mismo o casi lo mismo**: bonito y hermoso. Los **antónimos** significan **lo contrario**: grande y pequeño. Conocerlos ayuda a no repetir palabras y a decir las cosas con más precisión.',
        ['Sinónimos: contento – alegre, rápido – veloz.', 'Antónimos: día – noche, frío – caliente.', 'Cambiar una palabra por su sinónimo no cambia la idea.'],
        [
            completa('«Alegre» y «contento» son ___.', 'sinónimos', ['antónimos', 'verbos']),
            identifica('¿Cuál es el antónimo de «alto»?', 'Bajo', ['Grande', 'Largo']),
        ]
    ),

    'textos-que-informan' => intro(
        'Un **texto informativo** cuenta hechos reales sin inventar nada. La **noticia** es el ejemplo más conocido: responde qué pasó, a quién, cuándo, dónde y por qué.',
        ['Partes de la noticia: **titular**, **entradilla** (lo esencial) y **cuerpo** (los detalles).', 'Un **hecho** se puede comprobar; una **opinión** es lo que alguien piensa.', 'La **entrevista** informa con preguntas y respuestas.'],
        [
            completa('El título de una noticia se llama ___.', 'titular', ['moraleja', 'estrofa']),
            identifica('«Ayer llovió en Barranquilla» es…', 'Un hecho', ['Una opinión', 'Un cuento']),
        ]
    ),

    'textos-que-instruyen' => intro(
        'Un **texto instructivo** explica **cómo hacer algo** paso a paso: una receta, las instrucciones de un juego, el manual de un aparato. El orden importa: si te saltas un paso, puede no salir.',
        ['La receta tiene dos partes: **ingredientes** (o materiales) y **preparación**.', 'Los pasos van en **orden** y suelen estar numerados.', 'Usa verbos que mandan: mezcla, corta, espera.'],
        [
            completa('Un texto instructivo explica cómo ___ algo.', 'hacer', ['soñar', 'opinar']),
            identifica('¿Qué pasa si me salto un paso de la receta?', 'Puede que no salga bien', ['Sale más rico', 'No pasa nada']),
        ]
    ),

    'el-cuento-y-sus-partes' => intro(
        'Un **cuento** es una historia corta inventada. Casi todos siguen el mismo camino en tres partes: **inicio**, **nudo** y **desenlace**. Además tiene personajes, un lugar y un tiempo.',
        ['**Inicio**: se presentan los personajes y el lugar.', '**Nudo**: aparece el problema.', '**Desenlace**: el problema se resuelve.'],
        [
            completa('La parte del cuento donde aparece el problema es el ___.', 'nudo', ['inicio', 'título']),
            identifica('¿Qué se cuenta en el desenlace?', 'Cómo se resuelve el problema', ['Quiénes son los personajes', 'El nombre del autor']),
        ]
    ),

    'comprension-lectora' => intro(
        'Leer no es solo pasar los ojos por las letras: es **entender**. Hay tres niveles. Lo **literal** está escrito tal cual. Lo **inferencial** lo deduces de las pistas. Lo **crítico** es lo que tú opinas sobre lo leído.',
        ['**Literal**: «¿Cómo se llama el personaje?»', '**Inferencial**: «Llegó empapado» → estaba lloviendo.', '**Crítico**: «¿Estuvo bien lo que hizo?»'],
        [
            completa('Una conclusión que saco de las pistas del texto es una ___.', 'inferencia', ['rima', 'moraleja']),
            identifica('«¿Estuvo bien lo que hizo el personaje?» es una pregunta…', 'Crítica', ['Literal', 'De ortografía']),
        ]
    ),

    'la-historieta' => intro(
        'La **historieta** o **cómic** cuenta una historia con dibujos y texto a la vez. Se lee cuadro por cuadro, de izquierda a derecha, y cada dibujo aporta información que el texto no dice.',
        ['Cada cuadro se llama **viñeta**.', 'Lo que dicen los personajes va en **globos**; su forma dice cómo lo dicen.', 'Las **onomatopeyas** escriben sonidos: ¡PUM!, ¡CRASH!'],
        [
            completa('Cada cuadro de una historieta se llama ___.', 'viñeta', ['globo', 'página']),
            identifica('¿Qué es «¡PUM!» en un cómic?', 'Una onomatopeya', ['Un personaje', 'Un título']),
        ]
    ),

    'letras-que-se-confunden' => intro(
        'Algunas letras suenan igual pero se escriben distinto: **B y V**; **C, S y Z**; **G y J** delante de e, i; y la **H**, que no suena. Para acertar sirven las **reglas** y conocer la palabra.',
        ['Antes de **B** y **P** se escribe **M**: bomba, campo.', 'Después de **N** va **V**: enviar, invierno.', 'La **H** muda se aprende palabra por palabra: hotel, huevo, hielo.'],
        [
            completa('Antes de B siempre se escribe ___.', 'M', ['N', 'S']),
            identifica('¿Cuál está bien escrita?', 'Zapato', ['Sapato', 'Capato']),
        ]
    ),

    'el-diccionario' => intro(
        'El **diccionario** reúne las palabras de una lengua en **orden alfabético** y explica qué significa cada una. Sirve para resolver dudas de significado y también de ortografía.',
        ['Las palabras van de la A a la Z; si empiezan igual, se mira la **siguiente letra**.', 'Cada **entrada** dice qué clase de palabra es: s. (sustantivo), v. (verbo).', 'Una palabra puede tener **varios significados**: banco, hoja.'],
        [
            completa('En el diccionario las palabras están en orden ___.', 'alfabético', ['de tamaño', 'de colores']),
            identifica('Si dos palabras empiezan con la misma letra, ¿qué miro?', 'La letra siguiente', ['Cuál es más larga', 'Cuál me gusta más']),
        ]
    ),

    'leer-y-completar' => intro(
        'Cuando a un texto le falta una palabra, la pista está en el **contexto**: lo que dice el resto de la frase. Para completarlo hay que leer la oración **entera**, no solo la palabra de al lado.',
        ['Lee la frase completa antes de elegir.', 'Pregúntate: ¿qué palabra tiene **sentido** aquí?', 'Revisa que concuerde: singular o plural, masculino o femenino.'],
        [
            completa('Hacía tanto frío que el agua se ___.', 'congeló', ['quemó', 'cantó']),
            identifica('¿Qué es el contexto?', 'Lo que dice el resto del texto alrededor de la palabra', ['El título del libro', 'La última letra de la palabra']),
        ]
    ),

    'la-tilde' => intro(
        'En toda palabra hay una sílaba que suena **más fuerte**: la **sílaba tónica**. Según dónde esté, la palabra es **aguda**, **grave** o **esdrújula**, y eso decide si lleva **tilde** (el acento escrito).',
        ['**Aguda** (última sílaba): lleva tilde si termina en **n, s o vocal**: camión, café.', '**Grave** (penúltima): lleva tilde si **no** termina en n, s o vocal: árbol, lápiz.', '**Esdrújula** (antepenúltima): lleva tilde **siempre**: música, pájaro.'],
        [
            completa('Las palabras esdrújulas llevan tilde ___.', 'siempre', ['nunca', 'a veces']),
            identifica('«Camión» es una palabra…', 'Aguda', ['Grave', 'Esdrújula']),
        ]
    ),

    'familias-de-palabras' => intro(
        'Muchas palabras nacen de otra: de **flor** salen florero, florista, floral. Todas comparten una **raíz** y forman una **familia**. Se crean añadiendo **prefijos** (delante) o **sufijos** (detrás).',
        ['**Prefijo**: des-hacer, re-leer, in-útil.', '**Sufijo**: cas-ita, libr-ería, pan-adero.', '**Campo semántico**: palabras del mismo tema, aunque no compartan raíz (cocina: olla, sartén, cuchara).'],
        [
            completa('La parte que se añade delante de una palabra se llama ___.', 'prefijo', ['sufijo', 'artículo']),
            identifica('¿Cuál es de la familia de «pan»?', 'Panadería', ['Pantalón', 'Pantalla']),
        ]
    ),

    'textos-que-convencen' => intro(
        'Un **texto argumentativo** quiere **convencerte** de algo. Tiene una **tesis** (la idea que defiende) y **argumentos** (las razones). La **publicidad** también quiere convencer, pero a veces sin razones, solo con emociones o frases exageradas.',
        ['**Tesis**: lo que se defiende. **Argumento**: la razón que lo sostiene.', 'Un buen argumento se puede **comprobar**.', '«El mejor del mundo» es una afirmación **sin pruebas**.'],
        [
            completa('La razón que sostiene una idea se llama ___.', 'argumento', ['titular', 'verso']),
            identifica('«¡Compra ya, oferta solo por hoy!» quiere…', 'Convencerte', ['Informarte', 'Contarte un cuento']),
        ]
    ),

    'poesia-y-teatro' => intro(
        'La **poesía** (género lírico) expresa sentimientos con palabras que suenan bien juntas: versos, ritmo y rima. El **teatro** (género dramático) se escribe para ser **actuado** delante de un público.',
        ['Cada línea de un poema es un **verso**; un grupo de versos es una **estrofa**.', 'Hay **rima** cuando dos versos terminan con sonidos iguales: canción – corazón.', 'En el teatro, las **acotaciones** indican a los actores qué hacer.'],
        [
            completa('Cada línea de un poema se llama ___.', 'verso', ['párrafo', 'viñeta']),
            identifica('¿Para qué se escribe una obra de teatro?', 'Para representarla', ['Para buscar palabras', 'Para dar instrucciones']),
        ]
    ),

    'escribir-y-exponer' => intro(
        'Escribir bien es un **proceso**: planear, escribir un borrador, revisarlo y corregirlo. Y **exponer** es contar en voz alta lo que sabes, de forma ordenada, para que otros lo entiendan.',
        ['Antes de escribir: **¿para quién** y **para qué**?', '**Coherencia**: todo trata del mismo tema. **Cohesión**: las frases están bien enlazadas.', 'Antes de exponer, **ensaya** en voz alta.'],
        [
            completa('El primer texto, el que luego se corrige, se llama ___.', 'borrador', ['titular', 'índice']),
            identifica('¿Qué es la coherencia?', 'Que todo el texto trate del mismo tema con sentido', ['Que tenga muchas palabras difíciles', 'Que tenga dibujos']),
        ]
    ),

    'crucigramas-de-palabras' => intro(
        'Un **crucigrama** se resuelve con **definiciones**: la pista dice qué significa la palabra y tú la escribes. Estos tres repasan lo que has aprendido de gramática, de significados y de tipos de texto.',
        ['Lee la pista y piensa qué palabra **define**.', 'Cuenta las casillas: te dicen cuántas letras tiene.', 'Las letras que se cruzan con otras palabras son **pistas extra**.'],
        [
            completa('«Palabra de significado parecido» es la definición de ___.', 'sinónimo', ['antónimo', 'prefijo']),
            identifica('¿Qué es un prefijo?', 'Lo que va delante de la raíz de una palabra', ['El final de una palabra', 'Un signo de puntuación']),
        ]
    ),

    'leer-entre-lineas' => intro(
        '**Inferir** es sacar una conclusión a partir de **pistas** del texto. El texto no lo dice directamente, pero te da las señales para deducirlo, como un detective.',
        ['Busca **pistas**: gestos, ropa, lo que hacen los personajes.', 'Distingue lo que **está escrito** de lo que **deduces**.', 'Pregúntate con qué **intención** escribió el autor: informar, convencer, instruir.'],
        [
            completa('Sacar una conclusión a partir de pistas es ___.', 'inferir', ['copiar', 'rimar']),
            identifica('«Se puso el abrigo y los guantes». ¿Qué se deduce?', 'Que hace frío', ['Que tiene hambre', 'Que va a nadar']),
        ]
    ),

    'escribir-para-que-me-entiendan' => intro(
        'Un buen texto casi nunca sale a la primera: se **planea**, se **escribe** y se **revisa**. Los **conectores** (además, sin embargo, por eso) unen las ideas para que el lector no se pierda.',
        ['Antes de escribir: **para quién** y **para qué**.', 'Al revisar, **lee en voz alta**: lo que no suena bien, se corrige.', 'Conectores: **además** (sumar), **sin embargo** (oponer), **por eso** (consecuencia).'],
        [
            completa('Para oponer dos ideas uso el conector «___».', 'sin embargo', ['además', 'por ejemplo']),
            identifica('¿Para qué sirve leer en voz alta lo que escribí?', 'Para encontrar frases que no suenan bien', ['Para escribir más rápido', 'Para no tener que revisar']),
        ]
    ),

];
