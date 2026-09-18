<?php
/**
 * ciencias-ampliacion.php — Ciencias en preescolar
 *
 * Ciencias tenía **dos actividades de preescolar** frente a dieciséis de
 * tercero. Y es la materia donde un niño de cinco años tiene más que
 * decir: a esa edad ya observa, ya compara y ya pregunta por qué.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  CIENCIA A LOS CINCO AÑOS ES OBSERVAR, NO EXPLICAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * No hay células, ni fotosíntesis, ni estados de la materia con sus
 * nombres. Hay **lo que se ve**: qué está vivo y qué no, qué necesita un
 * ser vivo, qué hace el agua, qué pasa de día y de noche.
 *
 * Poner el vocabulario técnico antes que la observación produce niños
 * que recitan «fotosíntesis» y no saben que una planta necesita luz.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'ciencias',
    'name'       => 'Ciencias',
    'tagline'    => 'Los seres vivos, la materia y la energía que mueve el mundo',
    'icon'       => '🔬',
    'color'      => '#43a047',
    'sort_order' => 5,
],

'bloques' => [
    ['slug' => 'seres-vivos', 'name' => 'Seres Vivos', 'icon' => '🌱', 'sort_order' => 1,
     'description' => 'Animales, plantas y el cuerpo humano por dentro.'],
    ['slug' => 'la-tierra-y-el-cielo', 'name' => 'La Tierra y el Cielo', 'icon' => '🌍', 'sort_order' => 2,
     'description' => 'El agua, el clima, los planetas y lo que hay más allá.'],
    ['slug' => 'experimentos-y-materia', 'name' => 'Experimentos y Materia', 'icon' => '⚗️', 'sort_order' => 3,
     'description' => 'Estados, fuerzas y cosas que se pueden comprobar.'],
],

'actividades' => [

[
    // `vivo-o-no-vivo` ya existe y es de primero. Esta es la de preescolar.
    'slug'  => 'esta-vivo-o-no',
    'title' => '¿Está vivo?',
    'description' => 'Lo que crece, come y se mueve solo está vivo. Lo demás, no.',
    'objective' => 'Distinguir seres vivos de objetos inertes por sus características observables.',
    'icon' => '🌿', 'nivel' => 'preescolar', 'bloque' => 'seres-vivos',
    'duracion' => 8, 'tags' => ['observacion', 'clasificacion', 'logica'],
    'estaciones' => [

        est('¿Está vivo?', 'Piensa si crece y come', '🌱', 'seleccion_imagenes',
            conTitulo('Toca todo lo que está VIVO', 'Crece, come y se mueve solo', [
                ['e' => '🐶', 'n' => 'Perro',    'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',   'ok' => false],
                ['e' => '🌳', 'n' => 'Árbol',    'ok' => true],
                ['e' => '🚗', 'n' => 'Carro',    'ok' => false],
                ['e' => '🦋', 'n' => 'Mariposa', 'ok' => true],
                ['e' => '🪑', 'n' => 'Silla',    'ok' => false],
                ['e' => '🌻', 'n' => 'Girasol',  'ok' => true],
                ['e' => '⚽', 'n' => 'Balón',    'ok' => false],
            ])),

        est('¿Qué hacen los seres vivos?', 'Todos hacen lo mismo', '🔄', 'opcion_multiple', [
            omp('Los seres vivos…',              ['Crecen', 'Se quedan igual', 'Se apagan'], 'Crecen'),
            omp('Los seres vivos necesitan…',    ['Comida o agua', 'Pilas', 'Enchufe'], 'Comida o agua'),
            omp('Un carro se mueve porque…',     ['Alguien lo maneja', 'Está vivo', 'Come'], 'Alguien lo maneja'),
            omp('Un árbol está vivo aunque…',    ['No camine', 'No tenga hojas nunca', 'Sea de plástico'], 'No camine'),
            omp('¿Un peluche está vivo?',        ['No', 'Sí', 'A veces'], 'No', '🧸'),
        ]),

        est('¿Qué necesita para vivir?', 'Sin esto no se puede', '💧', 'opcion_multiple', [
            omp('Una planta necesita…',   ['Agua y luz', 'Pilas', 'Ruido'], 'Agua y luz', '🌱'),
            omp('Un perro necesita…',     ['Comida y agua', 'Enchufe', 'Nada'], 'Comida y agua', '🐶'),
            omp('Un pez necesita…',       ['Agua', 'Aire seco', 'Tierra'], 'Agua', '🐠'),
            omp('Yo necesito…',           ['Comer, beber y dormir', 'Solo jugar', 'Nada'], 'Comer, beber y dormir'),
            omp('Si una planta no recibe agua…', ['Se seca', 'Crece más', 'Se pone azul'], 'Se seca'),
        ]),

        est('Memoria de seres vivos', 'Encuentra las parejas', '🧠', 'memoria',
            ['🐶', '🌳', '🦋', '🐠', '🌻', '🐢']),
    ],
],

[
    'slug'  => 'animales-y-sus-casas',
    'title' => 'Animales y sus casas',
    'description' => 'Dónde vive cada animal, qué come y cómo se mueve.',
    'objective' => 'Relacionar animales con su hábitat, alimentación y forma de desplazarse.',
    'icon' => '🐾', 'nivel' => 'preescolar', 'bloque' => 'seres-vivos',
    'duracion' => 8, 'tags' => ['observacion', 'clasificacion', 'vocabulario'],
    'estaciones' => [

        est('¿Dónde vive?', 'Cada animal en su lugar', '🏡', 'opcion_multiple', [
            omp('El pez vive en…',      ['El agua', 'El árbol', 'La cueva'], 'El agua', '🐠'),
            omp('El pájaro vive en…',   ['El nido', 'El agua', 'La madriguera'], 'El nido', '🐦'),
            omp('La abeja vive en…',    ['La colmena', 'El río', 'El nido'], 'La colmena', '🐝'),
            omp('El pingüino vive donde hace…', ['Mucho frío', 'Mucho calor', 'Lluvia siempre'], 'Mucho frío', '🐧'),
            omp('El camello vive en…',  ['El desierto', 'El hielo', 'El mar'], 'El desierto', '🐪'),
        ]),

        est('¿Cómo se mueve?', 'Cada uno a su manera', '🏃', 'opcion_multiple', [
            omp('El pez se mueve…',      ['Nadando', 'Volando', 'Corriendo'], 'Nadando', '🐠'),
            omp('El águila se mueve…',   ['Volando', 'Nadando', 'Reptando'], 'Volando', '🦅'),
            omp('La serpiente se mueve…',['Reptando', 'Volando', 'Saltando'], 'Reptando', '🐍'),
            omp('El conejo se mueve…',   ['Saltando', 'Nadando', 'Volando'], 'Saltando', '🐰'),
            omp('El caballo se mueve…',  ['Corriendo', 'Volando', 'Nadando'], 'Corriendo', '🐴'),
        ]),

        est('¿Qué come?', 'Plantas, carne o las dos', '🍽️', 'opcion_multiple', [
            omp('La vaca come…',      ['Pasto', 'Carne', 'Piedras'], 'Pasto', '🐄'),
            omp('El león come…',      ['Carne', 'Pasto', 'Pan'], 'Carne', '🦁'),
            omp('El conejo come…',    ['Zanahorias y hierba', 'Carne', 'Metal'], 'Zanahorias y hierba', '🐰'),
            omp('La abeja busca…',    ['Néctar de las flores', 'Carne', 'Piedras'], 'Néctar de las flores', '🐝'),
            omp('Los que comen plantas se llaman…', ['Herbívoros', 'Carnívoros', 'Robots'], 'Herbívoros'),
        ]),

        est('Une el animal con su casa', 'Cada uno a su sitio', '🔗', 'emparejar', [
            ['e' => '🐝', 'w' => 'Colmena'],
            ['e' => '🐦', 'w' => 'Nido'],
            ['e' => '🐠', 'w' => 'Agua'],
            ['e' => '🐻', 'w' => 'Cueva'],
            ['e' => '🐄', 'w' => 'Establo'],
            ['e' => '🐜', 'w' => 'Hormiguero'],
        ]),
    ],
],

[
    'slug'  => 'las-plantas-crecen',
    'title' => 'Las plantas crecen',
    'description' => 'De la semilla a la flor: qué necesita una planta y qué partes tiene.',
    'objective' => 'Reconocer partes de la planta y el ciclo de crecimiento.',
    'icon' => '🌻', 'nivel' => 'preescolar', 'bloque' => 'seres-vivos',
    'duracion' => 8, 'tags' => ['observacion', 'secuencias', 'clasificacion'],
    'estaciones' => [

        est('Las partes de una planta', 'Cada una hace algo', '🌱', 'opcion_multiple', [
            omp('Lo que está bajo tierra es…',  ['La raíz', 'La flor', 'La hoja'], 'La raíz'),
            omp('Lo verde y plano son las…',    ['Hojas', 'Raíces', 'Semillas'], 'Hojas', '🌿'),
            omp('Lo que sostiene la planta es el…', ['Tallo', 'Pétalo', 'Fruto'], 'Tallo'),
            omp('Lo de colores que atrae a las abejas es la…', ['Flor', 'Raíz', 'Hoja'], 'Flor', '🌸'),
            omp('La raíz sirve para…',          ['Tomar agua de la tierra', 'Volar', 'Dar sombra'], 'Tomar agua de la tierra'),
        ]),

        est('Cómo crece', 'Ordena los pasos', '🌱', 'ordenar_secuencia', [
            'title' => 'Ordena cómo crece una planta',
            'items' => ['Semilla', 'La semilla se abre', 'Sale un brote',
                        'Crecen las hojas', 'Aparece la flor', 'Sale el fruto'],
        ]),

        est('¿Qué necesita?', 'Sin esto no crece', '💧', 'seleccion_imagenes',
            conTitulo('Toca lo que necesita una planta', 'Solo lo que de verdad necesita', [
                ['e' => '💧', 'n' => 'Agua',    'ok' => true],
                ['e' => '🍬', 'n' => 'Dulces',  'ok' => false],
                ['e' => '☀️', 'n' => 'Luz',     'ok' => true],
                ['e' => '📺', 'n' => 'Televisor','ok' => false],
                ['e' => '🪴', 'n' => 'Tierra',  'ok' => true],
                ['e' => '🎵', 'n' => 'Música',  'ok' => false],
                ['e' => '💨', 'n' => 'Aire',    'ok' => true],
                ['e' => '🔌', 'n' => 'Enchufe', 'ok' => false],
            ])),

        est('Lo que nos dan las plantas', 'Más de lo que parece', '🎁', 'opcion_multiple', [
            omp('De la planta sale…',        ['La fruta que como', 'El plástico', 'El metal'], 'La fruta que como', '🍎'),
            omp('Los árboles nos dan…',      ['Sombra y aire limpio', 'Ruido', 'Electricidad'], 'Sombra y aire limpio', '🌳'),
            omp('El papel se hace de…',      ['Árboles', 'Piedras', 'Agua'], 'Árboles', '📄'),
            omp('Si corto todos los árboles…', ['Se daña el aire', 'No pasa nada', 'Hay más sombra'], 'Se daña el aire'),
            omp('Para cuidar una planta…',   ['La riego y le doy luz', 'La tapo', 'La piso'], 'La riego y le doy luz'),
        ]),
    ],
],

[
    'slug'  => 'el-agua-que-veo',
    'title' => 'El agua que veo',
    'description' => 'Líquida, dura como el hielo o en forma de vapor: el agua cambia.',
    'objective' => 'Reconocer los estados del agua en situaciones cotidianas.',
    'icon' => '💧', 'nivel' => 'preescolar', 'bloque' => 'experimentos-y-materia',
    'duracion' => 8, 'tags' => ['observacion', 'logica', 'clasificacion'],
    'estaciones' => [

        est('¿Cómo está el agua?', 'Puede cambiar de forma', '🧊', 'opcion_multiple', [
            omp('El hielo es agua…',        ['Dura', 'Líquida', 'Invisible'], 'Dura', '🧊'),
            omp('El agua del vaso es…',     ['Líquida', 'Dura', 'Un gas'], 'Líquida', '🥤'),
            omp('El vapor de la sopa es…',  ['Agua en el aire', 'Humo', 'Polvo'], 'Agua en el aire', '🍲'),
            omp('Si dejo hielo al sol…',    ['Se derrite', 'Se endurece', 'Desaparece'], 'Se derrite', '☀️'),
            omp('Si meto agua al congelador…', ['Se hace hielo', 'Se evapora', 'Se pinta'], 'Se hace hielo'),
        ]),

        est('¿Flota o se hunde?', 'Prueba a adivinar', '🛟', 'juego_rapido',
            conTitulo('¿Flota en el agua?', 'Piensa si es pesado o ligero', [
                ['e' => '🪵', 'n' => 'Madera',  'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',  'ok' => false],
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => true],
                ['e' => '🔑', 'n' => 'Llave',   'ok' => false],
                ['e' => '🧊', 'n' => 'Hielo',   'ok' => true],
                ['e' => '🪙', 'n' => 'Moneda',  'ok' => false],
                ['e' => '🍃', 'n' => 'Hoja',    'ok' => true],
                ['e' => '🔨', 'n' => 'Martillo','ok' => false],
            ])),

        est('¿De dónde viene el agua?', 'Un viaje que se repite', '🌧️', 'ordenar_secuencia', [
            'title' => 'Ordena el viaje del agua',
            'items' => ['El sol calienta el mar', 'El agua sube como vapor',
                        'Se forman las nubes', 'Llueve', 'El agua vuelve al río y al mar'],
        ]),

        est('Cuidar el agua', 'No hay infinita', '🚰', 'opcion_multiple', [
            omp('Mientras me cepillo, el grifo…',  ['Se cierra', 'Se deja abierto', 'Se abre más'], 'Se cierra'),
            omp('Una llave que gotea…',            ['Gasta mucha agua al día', 'No gasta nada', 'Ahorra'], 'Gasta mucha agua al día'),
            omp('Bañarse mucho rato…',             ['Gasta mucha agua', 'Ahorra', 'Da igual'], 'Gasta mucha agua'),
            omp('El agua sucia del río…',          ['Hace daño a los animales', 'No pasa nada', 'Es mejor'], 'Hace daño a los animales'),
            omp('¿Se puede beber cualquier agua?', ['No, tiene que estar limpia', 'Sí', 'Solo la del río'], 'No, tiene que estar limpia'),
        ]),
    ],
],

[
    'slug'  => 'dia-noche-y-el-clima',
    'title' => 'El día, la noche y el clima',
    'description' => 'Por qué hay día y noche, y qué ropa se pone según el tiempo.',
    'objective' => 'Reconocer el ciclo día-noche y asociar estados del tiempo con conductas.',
    'icon' => '🌗', 'nivel' => 'preescolar', 'bloque' => 'la-tierra-y-el-cielo',
    'duracion' => 8, 'tags' => ['observacion', 'secuencias', 'logica'],
    'estaciones' => [

        est('De día y de noche', 'Qué se ve en cada uno', '🌗', 'opcion_multiple', [
            omp('De día se ve…',          ['El Sol', 'Las estrellas', 'Nada'], 'El Sol', '☀️'),
            omp('De noche se ve…',        ['La Luna', 'El Sol', 'El arcoíris'], 'La Luna', '🌙'),
            omp('De noche casi todos…',   ['Dormimos', 'Vamos al colegio', 'Almorzamos'], 'Dormimos'),
            omp('El Sol sale por la…',    ['Mañana', 'Noche', 'Madrugada de todos los días a las 12'], 'Mañana'),
            omp('Hay día y noche porque la Tierra…', ['Gira', 'Se apaga', 'Se esconde'], 'Gira', '🌍'),
        ]),

        est('¿Qué tiempo hace?', 'Mira el cielo', '🌦️', 'opcion_multiple', [
            omp('☀️ significa que hace…',  ['Sol', 'Lluvia', 'Nieve'], 'Sol', '☀️'),
            omp('🌧️ significa que…',       ['Llueve', 'Hace sol', 'Hace calor'], 'Llueve', '🌧️'),
            omp('❄️ significa que hace…',  ['Frío', 'Calor', 'Viento'], 'Frío', '❄️'),
            omp('💨 significa que hay…',   ['Viento', 'Sol', 'Nieve'], 'Viento', '💨'),
            omp('⛈️ significa que hay…',   ['Tormenta', 'Sol', 'Arcoíris'], 'Tormenta', '⛈️'),
        ]),

        est('¿Qué me pongo?', 'Ropa según el tiempo', '🧥', 'opcion_multiple', [
            omp('Si llueve me pongo…',     ['Botas e impermeable', 'Sandalias', 'Gafas de sol'], 'Botas e impermeable', '🌧️'),
            omp('Si hace mucho sol llevo…', ['Gorra y protector', 'Abrigo grueso', 'Bufanda'], 'Gorra y protector', '☀️'),
            omp('Si hace frío me pongo…',  ['Abrigo', 'Pantaloneta', 'Nada'], 'Abrigo', '🧥'),
            omp('Si hace calor me pongo…', ['Ropa fresca', 'Tres suéteres', 'Botas de nieve'], 'Ropa fresca'),
            omp('Antes de salir conviene…', ['Mirar el tiempo', 'Nada', 'Salir corriendo'], 'Mirar el tiempo'),
        ]),

        est('Une el tiempo con lo que hago', 'Cada clima, su plan', '🔗', 'emparejar', [
            ['e' => '☀️', 'w' => 'Gorra'],
            ['e' => '🌧️', 'w' => 'Paraguas'],
            ['e' => '❄️', 'w' => 'Abrigo'],
            ['e' => '💨', 'w' => 'Cometa'],
            ['e' => '🌊', 'w' => 'Traje de baño'],
            ['e' => '🌙', 'w' => 'Pijama'],
        ]),
    ],
],

[
    'slug'  => 'probar-para-saber',
    'title' => 'Probar para saber',
    'description' => 'Cuando no se sabe algo, se prueba. Eso es hacer ciencia.',
    'objective' => 'Iniciar el método de indagación: predecir, probar y comparar.',
    'icon' => '⚗️', 'nivel' => 'preescolar', 'bloque' => 'experimentos-y-materia',
    'duracion' => 8, 'tags' => ['observacion', 'logica', 'deduccion'],
    'estaciones' => [

        est('¿Qué crees que pasará?', 'Adivina antes de probar', '🔮', 'opcion_multiple', [
            omp('Si suelto una pelota, ¿qué pasa?',    ['Se cae', 'Sube', 'Se queda'], 'Se cae', '⚽'),
            omp('Si pongo un papel en agua, ¿qué pasa?', ['Se moja', 'Se seca', 'Crece'], 'Se moja', '📄'),
            omp('Si tapo una vela, ¿qué pasa?',        ['Se apaga', 'Crece', 'Se prende más'], 'Se apaga', '🕯️'),
            omp('Si dejo un helado afuera, ¿qué pasa?', ['Se derrite', 'Se congela más', 'Nada'], 'Se derrite', '🍦'),
            omp('A adivinar antes de probar se le dice…', ['Predecir', 'Copiar', 'Olvidar'], 'Predecir'),
        ]),

        est('Ordena un experimento', 'Los pasos de la ciencia', '🔬', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se hace un experimento',
            'items' => ['Hacerse una pregunta', 'Adivinar qué va a pasar',
                        'Probarlo', 'Mirar bien qué pasó', 'Contar lo que vi'],
        ]),

        est('Los sentidos para observar', 'Se observa con todo el cuerpo', '👀', 'opcion_multiple', [
            omp('Para ver el color uso…',   ['Los ojos', 'La nariz', 'Las manos'], 'Los ojos', '👀'),
            omp('Para saber si está frío uso…', ['Las manos', 'Los ojos', 'Los oídos'], 'Las manos', '✋'),
            omp('Para saber si suena uso…', ['Los oídos', 'La nariz', 'La lengua'], 'Los oídos', '👂'),
            omp('Para saber si huele uso…', ['La nariz', 'Los ojos', 'Los pies'], 'La nariz', '👃'),
            omp('¿Puedo probar todo con la lengua?', ['No, puede ser peligroso', 'Sí', 'Siempre'], 'No, puede ser peligroso'),
        ]),

        est('Lo que se puede comprobar', 'Y lo que no', '⚖️', 'juego_rapido',
            conTitulo('¿Se puede COMPROBAR mirando?', 'Piensa si se ve o no', [
                ['e' => '💧', 'n' => 'Si el agua moja',      'ok' => true],
                ['e' => '💭', 'n' => 'Lo que sueña mi perro','ok' => false],
                ['e' => '🧊', 'n' => 'Si el hielo se derrite','ok' => true],
                ['e' => '🎨', 'n' => 'Cuál color es el mejor','ok' => false],
                ['e' => '⚽', 'n' => 'Si el balón rueda',    'ok' => true],
                ['e' => '🌟', 'n' => 'Si las estrellas piden deseos','ok' => false],
                ['e' => '🌱', 'n' => 'Si la planta creció',  'ok' => true],
                ['e' => '😊', 'n' => 'Si la sopa es rica',   'ok' => false],
            ])),
    ],
],

],
];
