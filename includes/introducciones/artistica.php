<?php
/**
 * Artística · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'grandes-obras' => intro(
        'Algunas obras de arte se vuelven famosas porque cambiaron la forma de pintar, porque cuentan algo importante de su época o porque siguen emocionando siglos después. Conocerlas es conocer a sus **autores** y sus **estilos**.',
        ['**Leonardo da Vinci** pintó «La Mona Lisa»; **Van Gogh**, «La noche estrellada».', '**Realista**: copia la realidad tal cual.', '**Abstracto**: solo formas y colores, sin cosas reconocibles.'],
        [
            completa('Un cuadro solo con formas y colores, sin cosas reconocibles, es ___.', 'abstracto', ['realista', 'un retrato']),
            identifica('¿Quién pintó «La noche estrellada»?', 'Van Gogh', ['Botero', 'Picasso']),
        ]
    ),

    'tecnicas-de-artista' => intro(
        'Una **técnica artística** es una forma de hacer arte con ciertos materiales y pasos. No es lo mismo pintar con agua que pegar papeles o modelar arcilla: cada técnica tiene sus herramientas y sus resultados.',
        ['**Collage**: pegar recortes para formar una imagen (tijeras y pegante).', '**Acuarela**: pintura con agua sobre papel (pinceles y agua).', '**Escultura**: dar forma en tres dimensiones (arcilla, plastilina, madera).'],
        [
            completa('Pegar recortes de papel para formar una imagen se llama ___.', 'collage', ['acuarela', 'escultura']),
            identifica('¿Qué necesito para pintar con acuarela?', 'Agua y pinceles', ['Tijeras y pegante', 'Martillo y clavos']),
        ]
    ),

    'ritmo-y-compas' => intro(
        'Toda canción tiene un **pulso**: un latido constante, como el de un reloj, que puedes seguir con el pie. Sobre ese pulso, las **figuras musicales** indican cuánto dura cada sonido.',
        ['**Redonda** = 4 tiempos; **blanca** = 2; **negra** = 1; **corchea** = medio.', 'El **tempo** es la velocidad: rápido o lento.', 'Las notas: do, re, mi, fa, sol, la, si.'],
        [
            completa('Una blanca dura ___ tiempos.', '2', ['4', '1']),
            identifica('¿Qué es el pulso de una canción?', 'El latido constante que la sostiene', ['La letra', 'El volumen']),
        ]
    ),

    'el-color' => intro(
        'Con solo tres colores se pueden hacer casi todos los demás. Esos tres son los **primarios**: rojo, azul y amarillo. Mezclándolos de dos en dos salen los **secundarios**. Y cada color transmite una sensación.',
        ['Azul + amarillo = **verde**; rojo + amarillo = **naranja**; rojo + azul = **morado**.', '**Cálidos**: rojo, naranja, amarillo (energía, calor).', '**Fríos**: azul, verde, violeta (calma, frescura).'],
        [
            completa('Azul + amarillo = ___.', 'verde', ['naranja', 'morado']),
            identifica('¿Por qué se llaman primarios?', 'Porque no se obtienen mezclando otros', ['Porque son los más bonitos', 'Porque se usan primero']),
        ]
    ),

    'composicion-y-simetria' => intro(
        'La **composición** es cómo se organizan los elementos dentro de una obra: qué va en el centro, qué se repite, dónde hay espacio vacío. Una buena composición guía la mirada.',
        ['**Punto focal**: donde el ojo se detiene primero.', '**Simetría axial**: dos lados iguales respecto a un eje, como una mariposa.', '**Simetría radial**: los elementos giran alrededor de un centro, como una flor.'],
        [
            completa('El lugar donde el ojo se detiene primero es el punto ___.', 'focal', ['final', 'medio']),
            identifica('Una mariposa con las dos alas iguales tiene simetría…', 'Axial', ['Radial', 'Ninguna']),
        ]
    ),

    'arte-rupestre' => intro(
        'El **arte rupestre** son las primeras imágenes que hizo la humanidad, pintadas o grabadas en **cuevas y rocas** hace miles de años. Antes de que existiera la escritura, las imágenes servían para **contar** y **recordar**.',
        ['Pintaban sobre todo **animales** y escenas de **caza**.', 'Usaban pigmentos de tierra, carbón y minerales.', 'En Colombia hay pinturas rupestres en **Chiribiquete** y en la sabana de Bogotá.'],
        [
            completa('El arte pintado en las paredes de cuevas y rocas se llama arte ___.', 'rupestre', ['moderno', 'digital']),
            identifica('¿Qué se pintaba sobre todo?', 'Animales y escenas de caza', ['Retratos de reyes', 'Paisajes de ciudad']),
        ]
    ),

    'egipto-y-las-civilizaciones' => intro(
        'El arte del antiguo **Egipto** seguía reglas muy estrictas porque buscaba ser **claro** y **duradero**. Una de ellas era la **ley de la frontalidad**; otra, la **jerarquía**: el más importante se dibujaba más grande.',
        ['Cara de **perfil**, pero el ojo y los hombros de **frente**.', 'El **faraón** siempre aparece más grande que los demás.', 'En Asia: el **mandala** (diseño circular) y la **caligrafía**.'],
        [
            completa('Dibujar más grande al personaje más importante se llama jerarquía ___.', 'visual', ['musical', 'numérica']),
            identifica('¿Cómo dibujaban los egipcios la cara?', 'De perfil', ['De frente', 'De espaldas']),
        ]
    ),

    'danzas-de-colombia' => intro(
        'Cada región de Colombia tiene sus **danzas tradicionales**, que cuentan su historia y mezclan raíces **indígenas**, **africanas** y **europeas**. Una danza se aprende como una **coreografía**: una secuencia organizada de movimientos.',
        ['**Cumbia**: región Caribe. **Joropo**: Orinoquía (los llanos).', '**Bambuco**: región Andina. **Currulao**: región Pacífica.', 'El Carnaval más famoso es el de **Barranquilla**.'],
        [
            completa('La secuencia organizada de movimientos de un baile es la ___.', 'coreografía', ['partitura', 'comparsa']),
            identifica('¿De qué región es el joropo?', 'Orinoquía', ['Caribe', 'Pacífica']),
        ]
    ),

    'sonido-y-musica' => intro(
        'La música se construye con **sonidos** y **silencios**. Cada sonido tiene cualidades que lo hacen distinto: su **altura**, su **duración**, su **intensidad** y su **timbre**.',
        ['**Altura**: agudo o grave. **Intensidad**: fuerte o suave. **Duración**: largo o corto.', '**Timbre**: lo que distingue un instrumento de otro.', 'Familias: **cuerda** (violín), **viento** (flauta), **percusión** (tambor).'],
        [
            completa('Si un sonido es agudo o grave, hablamos de su ___.', 'altura', ['intensidad', 'duración']),
            identifica('¿A qué familia pertenece el violín?', 'Cuerda', ['Viento', 'Percusión']),
        ]
    ),

    'escultura-y-volumen' => intro(
        'Una pintura tiene **dos dimensiones** (largo y ancho). Una **escultura** tiene **tres**: además tiene profundidad, se puede rodear y mirar desde todos los lados.',
        ['**Tallar**: quitar material (piedra, madera) hasta llegar a la forma.', '**Modelar**: dar forma a un material blando (arcilla, plastilina).', 'En Colombia: las estatuas de piedra de **San Agustín** y la orfebrería en oro.'],
        [
            completa('Una escultura tiene ___ dimensiones.', 'tres', ['dos', 'una']),
            identifica('¿Qué es tallar?', 'Quitar material hasta llegar a la forma', ['Pegar papeles', 'Pintar con agua']),
        ]
    ),

    'el-teatro-y-la-expresion' => intro(
        'En el **teatro**, el cuerpo y la voz son las herramientas del actor. Con **gestos**, **postura** y **tono** se construye un **personaje**. Y una obra es un trabajo en equipo: actores, director, escenógrafo, vestuarista.',
        ['**Lenguaje corporal**: lo que decimos sin palabras.', '**Caracterizar**: darle a un personaje su voz, sus gestos y su forma de moverse.', 'El **director** coordina; el **escenógrafo** diseña el espacio.'],
        [
            completa('Lo que comunicamos con gestos y postura es el lenguaje ___.', 'corporal', ['escrito', 'musical']),
            identifica('¿Quién dirige una obra de teatro?', 'El director', ['El público', 'El escenógrafo']),
        ]
    ),

    'del-renacimiento-al-barroco' => intro(
        'Hace unos 500 años, en el **Renacimiento**, los artistas empezaron a estudiar la naturaleza, la anatomía y las matemáticas para pintar como se ve el mundo real. Después, el **Barroco** buscó **emocionar** con contrastes dramáticos de luz y sombra.',
        ['**Claroscuro**: luz y sombra para dar volumen.', '**Tenebrismo** (Barroco): contraste muy fuerte, fondos casi negros.', 'Antes, el **gótico** llenó las catedrales de luz de colores con los **vitrales**.'],
        [
            completa('El contraste entre luz y sombra para dar volumen se llama ___.', 'claroscuro', ['collage', 'mandala']),
            identifica('¿Qué buscaba el arte barroco?', 'Emocionar y sorprender', ['Copiar a los egipcios', 'Usar solo líneas rectas']),
        ]
    ),

    'proporcion-y-escala' => intro(
        'La **proporción** es la relación de tamaño entre las partes de un dibujo. Si la cabeza sale enorme y el cuerpo diminuto, algo falla. La **escala** permite agrandar o achicar una imagen **sin deformarla**.',
        ['Un adulto mide unas **8 cabezas** de alto; un niño, menos.', 'La **cuadrícula** ayuda a copiar y ampliar manteniendo las proporciones.', '**Encajar**: empezar con formas simples (un óvalo para la cabeza).'],
        [
            completa('La relación de tamaño entre las partes de un dibujo es la ___.', 'proporción', ['perspectiva', 'textura']),
            identifica('¿Para qué sirve la cuadrícula?', 'Para ampliar un dibujo sin deformarlo', ['Para colorear más rápido', 'Para borrar']),
        ]
    ),

    'luz-sombra-y-perspectiva' => intro(
        'Una hoja es plana, pero con dos trucos podemos hacer que un dibujo parezca tener **volumen** y **profundidad**: la **luz y la sombra**, y la **perspectiva**.',
        ['**Escala tonal**: ir del claro al oscuro; da volumen.', '**Punto de fuga**: donde parecen juntarse las líneas paralelas.', 'Lo lejano se ve **más pequeño** y más pálido (perspectiva atmosférica).'],
        [
            completa('El punto donde parecen juntarse las líneas paralelas es el punto de ___.', 'fuga', ['partida', 'color']),
            identifica('¿Cómo se ve lo que está más lejos?', 'Más pequeño', ['Más grande', 'Igual']),
        ]
    ),

    'arte-y-emocion' => intro(
        'El arte no solo busca ser bonito: busca **expresar**. Una obra puede transmitir alegría, tristeza, miedo o protesta con sus colores, sus formas y lo que muestra. Y cada persona puede sentir algo distinto al mirarla.',
        ['Azules y verdes suaves suelen dar **calma**; rojos intensos, **energía** o tensión.', 'Al mirar una obra: primero **observa** qué hay; después **opina**.', '**Autorretrato**: el artista se pinta a sí mismo. **Mural**: obra pintada en un muro.'],
        [
            completa('Un retrato que el artista hace de sí mismo es un ___.', 'autorretrato', ['mural', 'paisaje']),
            identifica('¿Tiene que ser bonita una obra para ser arte?', 'No, puede incomodar y seguir siendo arte', ['Sí, siempre', 'Solo si es antigua']),
        ]
    ),

    'colores-y-mezclas' => intro(
        'El **círculo cromático** ordena los colores para entender cómo se mezclan. Los **primarios** dan los **secundarios**; un primario con un secundario vecino da un **terciario**. Los que están **opuestos** en el círculo son **complementarios**.',
        ['Secundarios: **verde**, **naranja** y **morado**.', 'Complementarios: rojo – verde, azul – naranja, amarillo – morado.', 'Para aclarar se añade **blanco**; para oscurecer, **negro**.'],
        [
            completa('Los colores opuestos en el círculo cromático se llaman ___.', 'complementarios', ['primarios', 'fríos']),
            identifica('¿Cuál es el complementario del rojo?', 'El verde', ['El naranja', 'El rosado']),
        ]
    ),

    'composicion-de-una-imagen' => intro(
        'La misma foto puede verse aburrida o interesante según **dónde** se ponga cada cosa. Eso es la **composición**. Los fotógrafos y pintores usan reglas sencillas para dirigir la mirada.',
        ['**Foco**: el lugar al que va el ojo primero.', '**Regla de los tercios**: divide la imagen en 9 partes y pon lo importante donde se cruzan las líneas.', '**Equilibrio** simétrico (dos mitades iguales) o asimétrico.'],
        [
            completa('La regla de los tercios divide la imagen en ___ partes.', 'nueve', ['tres', 'dos']),
            identifica('¿A qué llamamos el foco de una imagen?', 'A donde va el ojo primero', ['Al marco', 'Al color de fondo']),
        ]
    ),

    'arte-y-su-epoca' => intro(
        'Cada **movimiento artístico** nace en una época y responde a sus preguntas. Hace unos 150 años los artistas dejaron de copiar la realidad al detalle y empezaron a buscar otras cosas: la luz, las formas, las emociones.',
        ['**Impresionismo**: pintar la luz de un instante (Monet).', '**Cubismo**: mostrar un objeto desde varios lados a la vez (Picasso).', '**Botero**, colombiano, es famoso por sus figuras de **volumen exagerado**.'],
        [
            completa('El movimiento que buscaba pintar la luz de un instante es el ___.', 'impresionismo', ['cubismo', 'barroco']),
            identifica('¿Qué hace el cubismo?', 'Muestra un objeto desde varios lados a la vez', ['Pinta solo paisajes', 'Usa solo un color']),
        ]
    ),

    'el-color-en-la-practica' => intro(
        'Elegir colores no es solo cuestión de gusto: hay relaciones que hacen que una combinación **funcione**. Los **complementarios** crean **contraste** y llaman la atención; los **vecinos** del círculo crean **armonía**.',
        ['Para que un cartel se vea de lejos: **mucho contraste**.', 'Colores vecinos (azul, verde, turquesa): **armonía tranquila**.', 'Los colores también **significan**: rojo = peligro; verde = permitido.'],
        [
            completa('Los colores vecinos en el círculo cromático dan ___.', 'armonía', ['contraste', 'peligro']),
            identifica('¿Qué colores se usan en una señal de peligro?', 'Rojo o amarillo', ['Verde claro', 'Rosado pálido']),
        ]
    ),

];
