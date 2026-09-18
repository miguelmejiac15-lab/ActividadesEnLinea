<?php
/**
 * artistica.php — Artística
 *
 * Completa la categoría, que tenía cuatro actividades sueltas y ningún
 * bloque. Los bloques van por lo que el niño HACE, no por disciplina:
 * mirar, crear con las manos, y sonar. Un niño de cuatro años no sabe si
 * está en «artes plásticas» o en «expresión musical», pero sí sabe si
 * está mirando algo o haciéndolo.
 *
 * `reasignar` mete las cuatro que ya existían en su bloque. No se tocan
 * sus estaciones ni su contenido: solo se les da un sitio.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'artistica',
    'name'       => 'Artística',
    'tagline'    => 'Mirar, crear y sonar: el arte como cosa de hacer',
    'icon'       => '🎨',
    'color'      => '#ab47bc',
    'sort_order' => 3,
],

'bloques' => [
    ['slug' => 'mirar-y-reconocer', 'name' => 'Mirar y Reconocer', 'icon' => '👁️', 'sort_order' => 1,
     'description' => 'Colores, formas y obras: aprender a mirar antes de crear.'],
    ['slug' => 'crear-con-las-manos', 'name' => 'Crear con las Manos', 'icon' => '✂️', 'sort_order' => 2,
     'description' => 'Materiales, técnicas y el paso a paso de hacer algo.'],
    ['slug' => 'musica-y-ritmo', 'name' => 'Música y Ritmo', 'icon' => '🎼', 'sort_order' => 3,
     'description' => 'Instrumentos, sonidos y el pulso que hay detrás de una canción.'],
],

// Actividades que ya existían y ahora pertenecen a un bloque.
'reasignar' => [
    'galeria-del-arte'       => 'mirar-y-reconocer',
    'fabrica-de-disfraces'   => 'crear-con-las-manos',
    'taller-de-manualidades' => 'crear-con-las-manos',
    'estacion-musical'       => 'musica-y-ritmo',
],

'actividades' => [


// ── MIRAR Y RECONOCER ────────────────────────────────────────────────

[
    'slug'  => 'formas-en-el-arte',
    'title' => 'Formas en el arte',
    'description' => 'Círculos, cuadrados y triángulos escondidos en todo lo que te rodea.',
    'objective' => 'Reconocer figuras geométricas básicas y encontrarlas en objetos cotidianos.',
    'icon' => '🔺', 'nivel' => 'preescolar', 'bloque' => 'mirar-y-reconocer',
    'duracion' => 10, 'tags' => ['observacion', 'clasificacion', 'juego'],
    'estaciones' => [

        est('¿Qué figura es?', 'Reconoce cada forma', '🔷', 'opcion_multiple', [
            omp('¿Qué figura es?', ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo', '⭕'),
            omp('¿Qué figura es?', ['Cuadrado', 'Círculo', 'Estrella'], 'Cuadrado', '⬛'),
            omp('¿Qué figura es?', ['Triángulo', 'Rectángulo', 'Círculo'], 'Triángulo', '🔺'),
            omp('¿Qué figura es?', ['Estrella', 'Corazón', 'Cuadrado'], 'Estrella', '⭐'),
            omp('¿Qué figura es?', ['Corazón', 'Rombo', 'Círculo'], 'Corazón', '❤️'),
        ]),

        est('Formas en las cosas', 'Todo tiene una forma escondida', '🔍', 'opcion_multiple', [
            omp('¿Qué forma tiene una pizza entera?', ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo', '🍕'),
            omp('¿Qué forma tiene una ventana?',      ['Cuadrado', 'Círculo', 'Estrella'], 'Cuadrado', '🪟'),
            omp('¿Qué forma tiene el techo de una casa dibujada?', ['Triángulo', 'Círculo', 'Cuadrado'], 'Triángulo', '🏠'),
            omp('¿Qué forma tiene una rueda?',        ['Círculo', 'Cuadrado', 'Triángulo'], 'Círculo', '🛞'),
            omp('¿Qué forma tiene un libro cerrado?', ['Rectángulo', 'Círculo', 'Estrella'], 'Rectángulo', '📕'),
        ]),

        est('Toca los círculos', 'Solo lo que sea redondo', '⭕', 'seleccion_imagenes',
            conTitulo('Toca todo lo que sea redondo', 'Deja fuera lo que no lo sea', [
                ['e' => '⚽', 'n' => 'Balón',   'ok' => true],
                ['e' => '🍕', 'n' => 'Pizza',   'ok' => true],
                ['e' => '🌕', 'n' => 'Luna',    'ok' => true],
                ['e' => '🪙', 'n' => 'Moneda',  'ok' => true],
                ['e' => '📕', 'n' => 'Libro',   'ok' => false],
                ['e' => '🪟', 'n' => 'Ventana', 'ok' => false],
                ['e' => '📐', 'n' => 'Escuadra', 'ok' => false],
            ])),

        est('Memoria de figuras', 'Encuentra las parejas', '🧠', 'memoria',
            ['⭕', '⬛', '🔺', '⭐', '❤️', '🔷']),
    ],
],

[
    'slug'  => 'el-circulo-cromatico',
    'title' => 'El círculo cromático',
    'description' => 'Colores primarios, secundarios y qué pasa cuando se mezclan.',
    'objective' => 'Distinguir colores primarios de secundarios y anticipar el resultado de una mezcla.',
    // 🌈 y no 🎨: la paleta ya la usa «Galería del Arte», que está en
    // este mismo bloque, y dos tarjetas vecinas con el mismo dibujo no
    // se distinguen de un vistazo.
    'icon' => '🌈', 'nivel' => 'primaria-inicial', 'bloque' => 'mirar-y-reconocer',
    'duracion' => 12, 'tags' => ['observacion', 'logica', 'vocabulario'],
    'estaciones' => [

        est('Los tres primarios', 'De estos tres salen todos los demás', '🔴', 'opcion_multiple', [
            omp('¿Cuántos colores primarios hay?', [2, 3, 4, 5], 3),
            omp('¿Este color es primario o secundario?', ['Primario', 'Secundario'], 'Primario', '#e53935', 'color'),
            omp('¿Este color es primario o secundario?', ['Primario', 'Secundario'], 'Primario', '#1e88e5', 'color'),
            omp('¿Este color es primario o secundario?', ['Primario', 'Secundario'], 'Primario', '#fdd835', 'color'),
            omp('¿Este color es primario o secundario?', ['Secundario', 'Primario'], 'Secundario', '#43a047', 'color'),
            omp('¿Este color es primario o secundario?', ['Secundario', 'Primario'], 'Secundario', '#fb8c00', 'color'),
        ]),

        est('Mezcla los colores', '¿Qué sale de juntar estos dos?', '🖌️', 'opcion_multiple', [
            omp('¿Qué color sale?', ['Verde', 'Morado', 'Naranja'], 'Verde',
                ['a' => '#1e88e5', 'b' => '#fdd835'], 'dosColores'),
            omp('¿Qué color sale?', ['Naranja', 'Verde', 'Morado'], 'Naranja',
                ['a' => '#e53935', 'b' => '#fdd835'], 'dosColores'),
            omp('¿Qué color sale?', ['Morado', 'Verde', 'Café'], 'Morado',
                ['a' => '#e53935', 'b' => '#1e88e5'], 'dosColores'),
            omp('¿Qué color sale?', ['Rosado', 'Verde', 'Azul'], 'Rosado',
                ['a' => '#e53935', 'b' => '#ffffff'], 'dosColores'),
            omp('¿Qué color sale?', ['Gris', 'Amarillo', 'Rojo'], 'Gris',
                ['a' => '#000000', 'b' => '#ffffff'], 'dosColores'),
        ]),

        est('Cálidos y fríos', 'Los colores también tienen temperatura', '🌡️', 'opcion_multiple', [
            omp('¿Este color es cálido o frío?', ['Cálido', 'Frío'], 'Cálido', '#e53935', 'color'),
            omp('¿Este color es cálido o frío?', ['Frío', 'Cálido'], 'Frío', '#1e88e5', 'color'),
            omp('¿Este color es cálido o frío?', ['Cálido', 'Frío'], 'Cálido', '#fb8c00', 'color'),
            omp('¿Este color es cálido o frío?', ['Frío', 'Cálido'], 'Frío', '#43a047', 'color'),
            omp('¿Qué colores usarías para dibujar el fuego?', ['Cálidos', 'Fríos'], 'Cálidos', '🔥'),
            omp('¿Qué colores usarías para dibujar el hielo?', ['Fríos', 'Cálidos'], 'Fríos', '🧊'),
        ]),

        est('Une color y nombre', 'Empareja cada muestra', '🔗', 'emparejar', [
            ['e' => '🔴', 'w' => 'Rojo'],
            ['e' => '🔵', 'w' => 'Azul'],
            ['e' => '🟡', 'w' => 'Amarillo'],
            ['e' => '🟢', 'w' => 'Verde'],
            ['e' => '🟠', 'w' => 'Naranja'],
            ['e' => '🟣', 'w' => 'Morado'],
        ]),
    ],
],

[
    'slug'  => 'grandes-obras',
    'title' => 'Grandes obras',
    'description' => 'Cuadros famosos, quién los pintó y por qué se recuerdan.',
    'objective' => 'Reconocer obras y autores célebres y describir qué hace especial a cada una.',
    'icon' => '🖼️', 'nivel' => 'primaria-media', 'bloque' => 'mirar-y-reconocer',
    'duracion' => 15, 'tags' => ['cultura', 'observacion', 'memoria'],
    'estaciones' => [

        est('¿Quién lo pintó?', 'Autores que cambiaron el arte', '🖌️', 'opcion_multiple', [
            omp('¿Quién pintó «La Mona Lisa»?',        ['Leonardo da Vinci', 'Picasso', 'Van Gogh'], 'Leonardo da Vinci', '🖼️'),
            omp('¿Quién pintó «La noche estrellada»?', ['Van Gogh', 'Botero', 'Miguel Ángel'], 'Van Gogh', '🌌'),
            omp('¿Quién pintó figuras muy redondeadas y es colombiano?', ['Fernando Botero', 'Frida Kahlo', 'Monet'], 'Fernando Botero', '🎨'),
            omp('¿Quién pintó muchos autorretratos y era mexicana?', ['Frida Kahlo', 'Van Gogh', 'Dalí'], 'Frida Kahlo', '🌺'),
            omp('¿Quién pintó el techo de la Capilla Sixtina?', ['Miguel Ángel', 'Picasso', 'Botero'], 'Miguel Ángel', '⛪'),
        ]),

        est('Estilos de arte', 'No todo el arte se parece', '🎭', 'opcion_multiple', [
            omp('Un cuadro que copia la realidad tal cual se llama…', ['Realista', 'Abstracto', 'Surrealista'], 'Realista', '📷'),
            omp('Un cuadro solo con formas y colores, sin cosas reconocibles, es…', ['Abstracto', 'Realista', 'Retrato'], 'Abstracto', '🟦'),
            omp('Un cuadro con relojes derretidos y cosas imposibles es…', ['Surrealista', 'Realista', 'Paisaje'], 'Surrealista', '🕰️'),
            omp('Un cuadro de la cara de una persona es un…', ['Retrato', 'Paisaje', 'Bodegón'], 'Retrato', '🧑'),
            omp('Un cuadro de frutas y objetos sobre una mesa es un…', ['Bodegón', 'Retrato', 'Paisaje'], 'Bodegón', '🍎'),
            omp('Un cuadro de montañas y cielo es un…', ['Paisaje', 'Retrato', 'Bodegón'], 'Paisaje', '🏔️'),
        ]),

        est('Une obra y autor', 'Cada cuadro tiene su firma', '🔗', 'emparejar', [
            ['e' => '🖼️', 'w' => 'Leonardo da Vinci'],
            ['e' => '🌌', 'w' => 'Van Gogh'],
            ['e' => '🌺', 'w' => 'Frida Kahlo'],
            ['e' => '⛪', 'w' => 'Miguel Ángel'],
            ['e' => '🎈', 'w' => 'Fernando Botero'],
        ]),

        est('Reto del museo', 'Demuestra que sabes mirar', '🏆', 'desafio_final', [
            reto('¿Para qué sirve el marco de un cuadro?',
                 ['Protegerlo y separarlo de la pared', 'Hacerlo más caro', 'Nada'], 'Protegerlo y separarlo de la pared'),
            reto('Si un cuadro no se parece a nada real, probablemente sea…',
                 ['Abstracto', 'Un retrato', 'Un bodegón'], 'Abstracto'),
            reto('¿Por qué en los museos no se puede tocar las obras?',
                 ['La grasa de las manos las daña con el tiempo', 'Porque sí', 'Para que no se muevan'],
                 'La grasa de las manos las daña con el tiempo'),
            reto('Una escultura se diferencia de un cuadro en que…',
                 ['Tiene volumen y se ve por todos lados', 'Es más grande', 'Siempre es de piedra'],
                 'Tiene volumen y se ve por todos lados'),
        ]),
    ],
],


// ── CREAR CON LAS MANOS ──────────────────────────────────────────────

[
    'slug'  => 'tecnicas-de-artista',
    'title' => 'Técnicas de artista',
    'description' => 'Collage, acuarela, escultura: cada técnica pide sus materiales.',
    'objective' => 'Identificar técnicas artísticas y los materiales y pasos que cada una requiere.',
    'icon' => '🖌️', 'nivel' => 'primaria-media', 'bloque' => 'crear-con-las-manos',
    'duracion' => 12, 'tags' => ['clasificacion', 'secuencias', 'vocabulario'],
    'estaciones' => [

        est('¿Qué técnica es?', 'Cada una tiene su nombre', '🎨', 'opcion_multiple', [
            omp('Pegar recortes de papel para formar una imagen se llama…', ['Collage', 'Acuarela', 'Escultura'], 'Collage', '✂️'),
            omp('Pintar con agua y pigmento sobre papel es…',   ['Acuarela', 'Óleo', 'Grabado'], 'Acuarela', '💧'),
            omp('Dar forma al barro con las manos es…',         ['Modelado', 'Dibujo', 'Collage'], 'Modelado', '🏺'),
            omp('Dibujar con lápiz sin color se llama…',        ['Dibujo a lápiz', 'Acuarela', 'Óleo'], 'Dibujo a lápiz', '✏️'),
            omp('Una obra con volumen que se ve por todos lados es una…', ['Escultura', 'Pintura', 'Fotografía'], 'Escultura', '🗿'),
        ]),

        est('¿Qué material necesito?', 'Cada técnica pide lo suyo', '🧰', 'opcion_multiple', [
            omp('Para hacer un collage necesito…',   ['Tijeras y pegante', 'Un horno', 'Un martillo'], 'Tijeras y pegante', '✂️'),
            omp('Para pintar con acuarela necesito…', ['Agua y pinceles', 'Barro', 'Alambre'], 'Agua y pinceles', '🖌️'),
            omp('Para modelar necesito…',            ['Arcilla o plastilina', 'Papel de seda', 'Acuarelas'], 'Arcilla o plastilina', '🏺'),
            omp('Para dibujar necesito…',            ['Lápiz y papel', 'Cemento', 'Un pincel gordo'], 'Lápiz y papel', '✏️'),
        ]),

        est('Pasos de un collage', 'El orden importa', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para hacer un collage',
            'items' => [
                '1️⃣ Pensar qué imagen quiero',
                '2️⃣ Buscar papeles y revistas',
                '3️⃣ Recortar las piezas',
                '4️⃣ Acomodarlas sin pegar',
                '5️⃣ Pegar cuando ya me gusta',
                '6️⃣ Dejar secar',
            ],
        ]),

        est('Une técnica y herramienta', 'Cada oficio, su instrumento', '🔗', 'emparejar', [
            ['e' => '✂️', 'w' => 'Collage'],
            ['e' => '🖌️', 'w' => 'Acuarela'],
            ['e' => '🏺', 'w' => 'Modelado'],
            ['e' => '✏️', 'w' => 'Dibujo'],
            ['e' => '📷', 'w' => 'Fotografía'],
        ]),
    ],
],


// ── MÚSICA Y RITMO ───────────────────────────────────────────────────

[
    'slug'  => 'instrumentos-del-mundo',
    'title' => 'Instrumentos del mundo',
    'description' => 'Cuerda, viento y percusión: cómo suena cada familia.',
    'objective' => 'Clasificar instrumentos musicales por familia y reconocer los típicos de Colombia.',
    'icon' => '🎺', 'nivel' => 'primaria-inicial', 'bloque' => 'musica-y-ritmo',
    'duracion' => 12, 'tags' => ['clasificacion', 'cultura', 'observacion'],
    'estaciones' => [

        est('Las tres familias', 'Los instrumentos se agrupan por cómo suenan', '🎼', 'opcion_multiple', [
            omp('¿A qué familia pertenece?', ['Cuerda', 'Viento', 'Percusión'], 'Cuerda', '🎸'),
            omp('¿A qué familia pertenece?', ['Viento', 'Cuerda', 'Percusión'], 'Viento', '🎺'),
            omp('¿A qué familia pertenece?', ['Percusión', 'Cuerda', 'Viento'], 'Percusión', '🥁'),
            omp('¿A qué familia pertenece?', ['Cuerda', 'Percusión', 'Viento'], 'Cuerda', '🎻'),
            omp('¿A qué familia pertenece?', ['Viento', 'Percusión', 'Cuerda'], 'Viento', '🎷'),
            omp('¿A qué familia pertenece?', ['Percusión', 'Viento', 'Cuerda'], 'Percusión', '🪘'),
        ]),

        est('¿Cómo suena?', 'Cada familia se toca distinto', '🔊', 'opcion_multiple', [
            omp('Un instrumento de cuerda suena cuando…',    ['Se pulsan o frotan sus cuerdas', 'Se sopla', 'Se golpea'], 'Se pulsan o frotan sus cuerdas', '🎸'),
            omp('Un instrumento de viento suena cuando…',    ['Se sopla aire', 'Se golpea', 'Se enchufa'], 'Se sopla aire', '🎺'),
            omp('Un instrumento de percusión suena cuando…', ['Se golpea o sacude', 'Se sopla', 'Se frota una cuerda'], 'Se golpea o sacude', '🥁'),
            omp('El piano se toca…',                         ['Presionando teclas', 'Soplando', 'Frotando'], 'Presionando teclas', '🎹'),
        ]),

        est('Instrumentos de Colombia', 'Los que suenan en nuestras regiones', '🇨🇴', 'opcion_multiple', [
            omp('¿Qué instrumento es típico del Pacífico?',  ['La marimba', 'El acordeón', 'El violín'], 'La marimba', '🎵'),
            omp('¿Qué instrumento es típico del vallenato?', ['El acordeón', 'La marimba', 'El piano'], 'El acordeón', '🪗'),
            omp('¿Qué instrumento de cuerda es típico de la región Andina?', ['El tiple', 'El saxofón', 'El tambor'], 'El tiple', '🎸'),
            omp('¿Qué instrumento acompaña el joropo llanero?', ['El arpa', 'La flauta traversa', 'El clarinete'], 'El arpa', '🎼'),
            omp('La gaita colombiana es un instrumento de…',  ['Viento', 'Cuerda', 'Percusión'], 'Viento', '🎶'),
        ]),

        est('Une instrumento y familia', 'Clasifícalos todos', '🔗', 'emparejar', [
            ['e' => '🎸', 'w' => 'Cuerda'],
            ['e' => '🎺', 'w' => 'Viento'],
            ['e' => '🥁', 'w' => 'Percusión'],
            ['e' => '🎹', 'w' => 'Teclado'],
            ['e' => '🪗', 'w' => 'Acordeón'],
        ]),
    ],
],

[
    'slug'  => 'ritmo-y-compas',
    'title' => 'Ritmo y compás',
    'description' => 'Las figuras musicales, el pulso y cómo se cuenta la música.',
    'objective' => 'Reconocer las figuras rítmicas básicas y su duración relativa.',
    'icon' => '🎼', 'nivel' => 'primaria-media', 'bloque' => 'musica-y-ritmo',
    'duracion' => 12, 'tags' => ['patrones', 'calculo', 'secuencias'],
    'estaciones' => [

        est('Las figuras', 'Cada figura dura distinto', '🎵', 'opcion_multiple', [
            omp('¿Cuántos tiempos dura una redonda?',   [1, 2, 3, 4], 4, '𝅝', 'texto'),
            omp('¿Cuántos tiempos dura una blanca?',    [1, 2, 3, 4], 2, '𝅗𝅥', 'texto'),
            omp('¿Cuántos tiempos dura una negra?',     [1, 2, 3, 4], 1, '♩', 'texto'),
            omp('¿Cuántas negras caben en una blanca?', [1, 2, 3, 4], 2),
            omp('¿Cuántas negras caben en una redonda?', [2, 3, 4, 8], 4),
            omp('¿Cuántas corcheas caben en una negra?', [1, 2, 3, 4], 2, '♪ ♪ = ♩', 'texto'),
        ]),

        est('Las notas', 'Siete nombres que se repiten', '🎶', 'ordenar_secuencia', [
            'title' => 'Ordena las notas de la escala, de grave a aguda',
            'items' => ['Do', 'Re', 'Mi', 'Fa', 'Sol', 'La', 'Si'],
        ]),

        est('El pulso', 'La música se cuenta', '👏', 'opcion_multiple', [
            omp('El pulso de una canción es…',       ['El latido constante que la sostiene', 'La letra', 'El volumen'], 'El latido constante que la sostiene', '🫀'),
            omp('Una canción rápida tiene un tempo…', ['Rápido', 'Lento', 'Silencioso'], 'Rápido', '🏃'),
            omp('Un compás de 4/4 tiene…',           ['4 tiempos por compás', '4 canciones', '4 instrumentos'], '4 tiempos por compás', '4️⃣'),
            omp('El silencio en música…',            ['También dura y se cuenta', 'No importa', 'Es un error'], 'También dura y se cuenta', '🤫'),
        ]),

        est('Patrones rítmicos', 'El ritmo también es un patrón', '🔁', 'opcion_multiple', [
            omp('¿Qué sigue en el patrón?', ['♩', '♪', '𝅗𝅥'], '♩', '♩ ♪ ♩ ♪ ♩ ❓', 'texto'),
            omp('¿Qué sigue en el patrón?', ['♪', '♩', '𝅝'], '♪', '♩ ♪ ♪ ♩ ♪ ❓', 'texto'),
            omp('¿Cuántos tiempos suman ♩ + ♩ + 𝅗𝅥 ?', [3, 4, 5, 2], 4),
            omp('¿Cuántos tiempos suman 𝅗𝅥 + 𝅗𝅥 ?',      [2, 3, 4, 8], 4),
        ]),
    ],
],

],
];
