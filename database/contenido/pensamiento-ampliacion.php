<?php
/**
 * pensamiento-ampliacion.php — Pensamiento y Lógica en todos los grados
 *
 * Era la categoría más flaca del catálogo: 18 actividades repartidas en
 * seis grados. Y es justo la que un docente busca cuando un niño «no
 * entiende los problemas» — que casi nunca es un problema de cuentas.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EN PREESCOLAR, ANTES DE DEDUCIR HAY QUE COMPARAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * A los cinco años no se razona con enunciados: se razona con cosas.
 * Igual y distinto, más y menos, lo que va junto y lo que sobra. Todo el
 * pensamiento posterior se apoya en eso, y sin embargo suele darse por
 * sabido.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EN QUINTO Y SEXTO, EL TRABAJO ES DUDAR BIEN
 * ─────────────────────────────────────────────────────────────────────
 *
 * A esa edad el error típico ya no es no saber: es estar seguro
 * demasiado pronto. Aquí se entrenan las trampas que más engañan —la
 * causa que no es causa, la media que esconde, el caso único que se toma
 * por regla— porque son las que se usan para convencerlos toda la vida.
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
    ['slug' => 'observar-y-clasificar', 'name' => 'Observar y Clasificar', 'icon' => '🔍', 'sort_order' => 1,
     'description' => 'Mirar con atención, comparar y agrupar: la base de todo razonamiento.'],
    ['slug' => 'patrones-y-secuencias', 'name' => 'Patrones y Secuencias', 'icon' => '🔁', 'sort_order' => 2,
     'description' => 'Descubrir la regla que se repite y anticipar lo que sigue.'],
    ['slug' => 'retos-de-deduccion', 'name' => 'Retos de Deducción', 'icon' => '🕵️', 'sort_order' => 3,
     'description' => 'Códigos, acertijos y casos que se resuelven pensando.'],
    ['slug' => 'resolver-problemas', 'name' => 'Resolver Problemas', 'icon' => '🧩', 'sort_order' => 4,
     'description' => 'Qué hacer cuando no se sabe la respuesta: entender, planear, probar y comprobar.'],
    ['slug' => 'pensamiento-critico', 'name' => 'Pensamiento Crítico', 'icon' => '🔍', 'sort_order' => 5,
     'description' => 'Distinguir hecho de opinión, detectar trampas de razonamiento y pedir pruebas.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR · comparar antes de razonar
// =====================================================================

[
    'slug'  => 'igual-o-distinto',
    'title' => 'Igual o distinto',
    'description' => 'Mirar dos cosas y decir en qué se parecen y en qué no.',
    'objective' => 'Comparar objetos por atributos perceptibles: color, forma, tamaño y cantidad.',
    'icon' => '👀', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 8, 'tags' => ['observacion', 'logica', 'clasificacion'],
    'estaciones' => [

        est('¿Cuál es distinto?', 'Uno no es como los demás', '🔍', 'opcion_multiple', [
            omp('¿Cuál no es una fruta?',   ['🚗', '🍎', '🍌'], '🚗'),
            omp('¿Cuál no es un animal?',   ['🌳', '🐶', '🐱'], '🌳'),
            omp('¿Cuál no se come?',        ['👟', '🍞', '🧀'], '👟'),
            omp('¿Cuál no vuela?',          ['🐟', '🦅', '🦋'], '🐟'),
            omp('¿Cuál no es redondo?',     ['📏', '⚽', '🍊'], '📏'),
        ]),

        est('Grande y pequeño', 'Comparar tamaños', '📏', 'opcion_multiple', [
            omp('¿Qué es más grande, un elefante o un ratón?', ['El elefante', 'El ratón'], 'El elefante', '🐘'),
            omp('¿Qué es más pequeño, una hormiga o un perro?', ['La hormiga', 'El perro'], 'La hormiga', '🐜'),
            omp('¿Qué es más alto, un árbol o una flor?',      ['El árbol', 'La flor'], 'El árbol', '🌳'),
            omp('¿Qué es más largo, un tren o un carro?',      ['El tren', 'El carro'], 'El tren', '🚆'),
            omp('¿Qué pesa más, una piedra o una pluma?',      ['La piedra', 'La pluma'], 'La piedra', '🪨'),
        ]),

        est('Los que van juntos', 'Cada cosa con su grupo', '🗂️', 'seleccion_imagenes',
            conTitulo('Toca todos los animales', 'Solo los animales', [
                ['e' => '🐶', 'n' => 'Perro',   'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',   'ok' => false],
                ['e' => '🐱', 'n' => 'Gato',    'ok' => true],
                ['e' => '🚗', 'n' => 'Carro',   'ok' => false],
                ['e' => '🐦', 'n' => 'Pájaro',  'ok' => true],
                ['e' => '🍎', 'n' => 'Manzana', 'ok' => false],
                ['e' => '🐠', 'n' => 'Pez',     'ok' => true],
                ['e' => '🏠', 'n' => 'Casa',    'ok' => false],
            ])),

        est('Parejas iguales', 'Encuentra las que se repiten', '🧠', 'memoria',
            ['🍎', '🐶', '⭐', '🌸', '🚗', '⚽']),
    ],
],

[
    'slug'  => 'lo-que-sigue',
    'title' => 'Lo que sigue',
    'description' => 'Cuando algo se repite, se puede adivinar qué viene después.',
    'objective' => 'Reconocer y continuar patrones simples de repetición.',
    'icon' => '🔁', 'nivel' => 'preescolar', 'bloque' => 'patrones-y-secuencias',
    'duracion' => 8, 'tags' => ['patrones', 'secuencias', 'logica'],
    'estaciones' => [

        est('¿Qué viene ahora?', 'Sigue el patrón', '➡️', 'opcion_multiple', [
            omp('🔴 🔵 🔴 🔵 ¿qué sigue?',   ['🔴', '🔵', '🟢'], '🔴'),
            omp('⭐ ⭐ 🌙 ⭐ ⭐ ¿qué sigue?', ['🌙', '⭐', '☀️'], '🌙'),
            omp('🐶 🐱 🐶 🐱 ¿qué sigue?',   ['🐶', '🐱', '🐭'], '🐶'),
            omp('🍎 🍌 🍎 🍌 ¿qué sigue?',   ['🍎', '🍌', '🍇'], '🍎'),
            omp('👏 👏 👏 ¿qué sigue?',       ['👏', '🦶', '🙌'], '👏'),
        ]),

        est('Ordena el día', 'Las cosas pasan en orden', '🌅', 'ordenar_secuencia', [
            'title' => 'Ordena el día de la mañana a la noche',
            'items' => ['Despertarse', 'Desayunar', 'Ir al colegio', 'Almorzar', 'Jugar', 'Dormir'],
        ]),

        est('Crecer poco a poco', 'Todo tiene su orden', '🌱', 'ordenar_secuencia', [
            'title' => 'Ordena cómo crece una planta',
            'items' => ['Semilla', 'Brote pequeño', 'Planta con hojas', 'Planta con flor'],
        ]),

        est('Números que siguen', 'Cuenta hacia adelante', '🔢', 'secuencia_numerica', [
            serie([1, 2, 3, null, 5]),
            serie([2, 3, 4, null, 6]),
            serie([5, 6, 7, null, 9]),
            serie([1, 3, 5, null, 9]),
            serie([2, 4, 6, null, 10]),
        ]),
    ],
],

[
    'slug'  => 'pistas-y-adivinanzas',
    'title' => 'Pistas y adivinanzas',
    'description' => 'Con dos o tres pistas se puede saber de qué se está hablando.',
    'objective' => 'Combinar varias pistas para identificar un objeto o animal.',
    'icon' => '🕵️', 'nivel' => 'preescolar', 'bloque' => 'retos-de-deduccion',
    'duracion' => 8, 'tags' => ['deduccion', 'logica', 'comprension'],
    'estaciones' => [

        est('¿Quién soy?', 'Escucha las pistas', '❓', 'opcion_multiple', [
            omp('Tengo cuatro patas, ladro y cuido la casa. Soy el…', ['Perro', 'Gato', 'Pez'], 'Perro', '🐶'),
            omp('Soy amarillo, redondo y alumbro de día. Soy el…',   ['Sol', 'La Luna', 'Una estrella'], 'Sol', '☀️'),
            omp('Vivo en el agua y nado. Soy el…',                   ['Pez', 'Pájaro', 'Gato'], 'Pez', '🐠'),
            omp('Tengo hojas, tronco y soy alto. Soy el…',           ['Árbol', 'Carro', 'Libro'], 'Árbol', '🌳'),
            omp('Doy leche, digo «muu» y vivo en la finca. Soy la…', ['Vaca', 'Gallina', 'Oveja'], 'Vaca', '🐄'),
        ]),

        est('Dos pistas juntas', 'Las dos tienen que cumplirse', '🔗', 'opcion_multiple', [
            omp('Es rojo Y se come. ¿Cuál es?',        ['🍎', '🚗', '🍌'], '🍎'),
            omp('Es amarillo Y se come. ¿Cuál es?',    ['🍌', '🌻', '🚕'], '🍌'),
            omp('Tiene ruedas Y es grande. ¿Cuál es?', ['🚌', '🚲', '🛹'], '🚌'),
            omp('Vuela Y es un animal. ¿Cuál es?',     ['🦅', '✈️', '🎈'], '🦅'),
            omp('Es blanco Y se bebe. ¿Cuál es?',      ['🥛', '☁️', '🧊'], '🥛'),
        ]),

        est('¿Dónde está?', 'Arriba, abajo, dentro, fuera', '📍', 'opcion_multiple', [
            omp('El pájaro vuela… del árbol',   ['Arriba', 'Abajo', 'Dentro'], 'Arriba', '🐦'),
            omp('El pez nada… del agua',        ['Dentro', 'Fuera', 'Encima'], 'Dentro', '🐠'),
            omp('La raíz está… de la tierra',   ['Debajo', 'Encima', 'Al lado'], 'Debajo', '🌱'),
            omp('El sombrero va… de la cabeza', ['Encima', 'Debajo', 'Dentro'], 'Encima', '🎩'),
            omp('Los zapatos van… de los pies', ['En', 'Sobre la cabeza', 'En las manos'], 'En', '👟'),
        ]),

        est('Lo que no puede ser', 'Algunas cosas son imposibles', '🚫', 'opcion_multiple', [
            omp('¿Un pez puede subir a un árbol?',  ['No', 'Sí', 'A veces'], 'No'),
            omp('¿Una piedra puede volar sola?',    ['No', 'Sí', 'Si es pequeña'], 'No'),
            omp('¿La noche puede ser de día?',      ['No', 'Sí', 'En verano'], 'No'),
            omp('¿Un bebé puede ser más viejo que su abuela?', ['No', 'Sí', 'Depende'], 'No'),
            omp('¿Puede llover hacia arriba?',      ['No', 'Sí', 'Con viento'], 'No'),
        ]),
    ],
],

[
    'slug'  => 'ordenar-mi-mundo',
    'title' => 'Ordenar mi mundo',
    'description' => 'Juntar lo que va junto: por color, por forma, por para qué sirve.',
    'objective' => 'Clasificar objetos aplicando un criterio y reconociendo cuál se usó.',
    'icon' => '🧺', 'nivel' => 'preescolar', 'bloque' => 'observar-y-clasificar',
    'duracion' => 8, 'tags' => ['clasificacion', 'logica', 'observacion'],
    'estaciones' => [

        est('Por para qué sirve', 'Cada cosa a su grupo', '🎯', 'seleccion_imagenes',
            conTitulo('Toca todo lo que sirve para comer', 'Solo lo de comer', [
                ['e' => '🥄', 'n' => 'Cuchara', 'ok' => true],
                ['e' => '✏️', 'n' => 'Lápiz',   'ok' => false],
                ['e' => '🍽️', 'n' => 'Plato',   'ok' => true],
                ['e' => '👟', 'n' => 'Zapato',  'ok' => false],
                ['e' => '🍴', 'n' => 'Tenedor', 'ok' => true],
                ['e' => '📖', 'n' => 'Libro',   'ok' => false],
                ['e' => '🥤', 'n' => 'Vaso',    'ok' => true],
                ['e' => '⚽', 'n' => 'Balón',   'ok' => false],
            ])),

        est('¿Dónde vive?', 'Cada animal en su casa', '🏡', 'emparejar', [
            ['e' => '🐠', 'w' => 'El agua'],
            ['e' => '🐦', 'w' => 'El nido'],
            ['e' => '🐝', 'w' => 'La colmena'],
            ['e' => '🐄', 'w' => 'La finca'],
            ['e' => '🦁', 'w' => 'La selva'],
            ['e' => '🐧', 'w' => 'El hielo'],
        ]),

        est('Lo que va junto', 'Parejas que se usan a la vez', '🔗', 'emparejar', [
            ['e' => '👟', 'w' => 'Medias'],
            ['e' => '✏️', 'w' => 'Cuaderno'],
            ['e' => '🪥', 'w' => 'Crema dental'],
            ['e' => '🔑', 'w' => 'Puerta'],
            ['e' => '☂️', 'w' => 'Lluvia'],
            ['e' => '🧦', 'w' => 'Pie'],
        ]),

        est('¿Cuál sobra?', 'Uno no pertenece al grupo', '🧐', 'opcion_multiple', [
            omp('Ropa: camiseta, pantalón, zapato, ¿y el…?', ['Plato', 'Sombrero', 'Media'], 'Plato', '🍽️'),
            omp('Frutas: manzana, banano, uva, ¿y la…?',     ['Silla', 'Naranja', 'Pera'], 'Silla', '🪑'),
            omp('Transporte: bus, carro, avión, ¿y el…?',    ['Pan', 'Tren', 'Barco'], 'Pan', '🍞'),
            omp('Colores: rojo, azul, verde, ¿y el…?',       ['Perro', 'Amarillo', 'Morado'], 'Perro', '🐶'),
            omp('Del colegio: cuaderno, lápiz, borrador, ¿y la…?', ['Almohada', 'Regla', 'Tijera'], 'Almohada', '🛏️'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA INICIAL · razonar con enunciados
// =====================================================================

[
    'slug'  => 'si-entonces',
    'title' => 'Si pasa esto, entonces…',
    'description' => 'Una cosa lleva a otra. Aprender a encadenar consecuencias.',
    'objective' => 'Establecer relaciones causa-efecto sencillas y anticipar consecuencias.',
    'icon' => '➡️', 'nivel' => 'primaria-inicial', 'bloque' => 'retos-de-deduccion',
    'duracion' => 10, 'tags' => ['logica', 'deduccion', 'comprension'],
    'estaciones' => [

        est('¿Qué va a pasar?', 'Piensa en la consecuencia', '🔮', 'opcion_multiple', [
            omp('Si dejo un helado al sol, entonces…',      ['Se derrite', 'Se congela', 'Crece'], 'Se derrite', '🍦'),
            omp('Si no riego la planta, entonces…',         ['Se seca', 'Florece', 'Se hace grande'], 'Se seca', '🌱'),
            omp('Si llueve mucho, entonces el río…',        ['Crece', 'Se seca', 'Desaparece'], 'Crece', '🌧️'),
            omp('Si no duermo bien, entonces al otro día…', ['Tengo sueño', 'Tengo más energía', 'Crezco más'], 'Tengo sueño', '😴'),
            omp('Si suelto un vaso, entonces…',             ['Se cae', 'Sube', 'Se queda quieto'], 'Se cae', '🥤'),
        ]),

        est('La causa', 'Al revés: ¿por qué pasó?', '⏮️', 'opcion_multiple', [
            omp('El suelo está mojado. Seguramente…',       ['Llovió', 'Hizo sol', 'Hubo viento'], 'Llovió'),
            omp('La planta se secó. Seguramente…',          ['No la regaron', 'La regaron mucho', 'Le dio sombra'], 'No la regaron'),
            omp('Todos tienen abrigo. Seguramente…',        ['Hace frío', 'Hace calor', 'Es de noche'], 'Hace frío'),
            omp('El pan está duro. Seguramente…',           ['Es viejo', 'Es nuevo', 'Está caliente'], 'Es viejo'),
            omp('Hay huellas en la arena. Seguramente…',    ['Alguien pasó', 'Nunca pasó nadie', 'Llovió'], 'Alguien pasó'),
        ]),

        est('Ordena la cadena', 'Una cosa lleva a la otra', '⛓️', 'ordenar_secuencia', [
            'title' => 'Ordena qué pasa cuando llueve',
            'items' => ['Se forman las nubes', 'Llueve', 'Se moja la tierra',
                        'Las plantas crecen', 'Hay comida'],
        ]),

        est('¿Verdadero o falso?', 'Piensa antes de responder', '⚖️', 'juego_rapido',
            conTitulo('¿Es verdad?', 'Piensa si siempre es así', [
                ['e' => '🌧️', 'n' => 'Si llueve, el suelo se moja', 'ok' => true],
                ['e' => '☀️', 'n' => 'Si hay sol, siempre hace frío','ok' => false],
                ['e' => '🌙', 'n' => 'De noche no se ve el Sol',     'ok' => true],
                ['e' => '🐟', 'n' => 'Los peces viven fuera del agua','ok' => false],
                ['e' => '🔥', 'n' => 'El fuego quema',               'ok' => true],
                ['e' => '🧊', 'n' => 'El hielo está caliente',       'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'la-regla-escondida',
    'title' => 'La regla escondida',
    'description' => 'En toda serie hay una regla. Encontrarla es medio ejercicio.',
    'objective' => 'Deducir la regla de formación de una serie y aplicarla.',
    'icon' => '⛓️', 'nivel' => 'primaria-inicial', 'bloque' => 'patrones-y-secuencias',
    'duracion' => 11, 'tags' => ['patrones', 'logica', 'calculo'],
    'estaciones' => [

        est('¿De cuánto en cuánto?', 'Encuentra el paso', '🔢', 'secuencia_numerica', [
            serie([3, 6, 9, null, 15]),
            serie([10, 20, 30, null, 50]),
            serie([4, 8, 12, null, 20]),
            serie([20, 18, 16, null, 12]),
            serie([50, 45, 40, null, 30]),
        ]),

        est('¿Cuál es la regla?', 'Dilo con palabras', '📝', 'opcion_multiple', [
            omp('2, 4, 6, 8. La regla es…',      ['Sumar 2', 'Sumar 1', 'Restar 2'], 'Sumar 2'),
            omp('10, 20, 30, 40. La regla es…',  ['Sumar 10', 'Sumar 1', 'Multiplicar por 2'], 'Sumar 10'),
            omp('9, 8, 7, 6. La regla es…',      ['Restar 1', 'Sumar 1', 'Restar 2'], 'Restar 1'),
            omp('5, 10, 15, 20. La regla es…',   ['Sumar 5', 'Sumar 2', 'Restar 5'], 'Sumar 5'),
            omp('100, 90, 80. La regla es…',     ['Restar 10', 'Sumar 10', 'Restar 1'], 'Restar 10'),
        ]),

        est('Patrones con dibujos', 'No todo son números', '🎨', 'opcion_multiple', [
            omp('🔺 🔵 🔺 🔵 🔺 ¿qué sigue?',      ['🔵', '🔺', '🟩'], '🔵'),
            omp('🌞 🌞 🌙 🌞 🌞 🌙 ¿qué sigue?',    ['🌞', '🌙', '⭐'], '🌞'),
            omp('🍎 🍎 🍌 🍎 🍎 ¿qué sigue?',       ['🍌', '🍎', '🍇'], '🍌'),
            omp('⬆️ ➡️ ⬇️ ⬅️ ⬆️ ¿qué sigue?',      ['➡️', '⬆️', '⬇️'], '➡️'),
            omp('Un patrón es algo que…',           ['Se repite con una regla', 'Pasa una vez', 'No tiene orden'], 'Se repite con una regla'),
        ]),

        est('Crece cada vez más', 'El paso también puede cambiar', '📈', 'opcion_multiple', [
            omp('1, 2, 4, 8, 16. Cada número es…',  ['El doble del anterior', 'Uno más', 'Dos más'], 'El doble del anterior'),
            omp('Después de 16, ¿qué sigue?',       ['32', '18', '20'], '32'),
            omp('1, 3, 6, 10. Se suma 2, 3, 4… ¿qué sigue?', ['15', '13', '12'], '15'),
            omp('Una serie que crece cada vez más rápido va…', ['Multiplicando', 'Restando', 'Igual'], 'Multiplicando'),
            omp('¿Para qué sirve encontrar la regla?', ['Para saber qué viene sin contar todo', 'Para nada', 'Para ir más lento'], 'Para saber qué viene sin contar todo'),
        ]),
    ],
],

[
    'slug'  => 'leer-el-enunciado',
    'title' => 'Leer bien el enunciado',
    'description' => 'La mitad de los errores no son de cuentas: son de no leer la pregunta.',
    'objective' => 'Identificar datos y pregunta en un enunciado antes de operar.',
    'icon' => '📖', 'nivel' => 'primaria-inicial', 'bloque' => 'resolver-problemas',
    'duracion' => 11, 'tags' => ['comprension', 'logica', 'calculo'],
    'estaciones' => [

        est('¿Qué me preguntan?', 'La pregunta, no el número', '❓', 'opcion_multiple', [
            omp('«Ana tiene 5 y le dan 3. ¿Cuántas tiene?» La pregunta es…', ['Cuántas tiene al final', 'Cuántas le dieron', 'Cómo se llama'], 'Cuántas tiene al final'),
            omp('¿Cuántas tiene Ana al final?',     ['8', '5', '3'], '8'),
            omp('«Luis tenía 9 y perdió 4.» La respuesta es…', ['5', '13', '4'], '5'),
            omp('«Hay 3 cajas con 2 pelotas cada una.» En total hay…', ['6', '5', '3'], '6'),
            omp('«Reparto 8 dulces entre 2 niños.» A cada uno le tocan…', ['4', '8', '2'], '4'),
        ]),

        est('¿Sumo o resto?', 'Elegir la operación', '➕', 'opcion_multiple', [
            omp('«Me regalaron más.» ¿Qué hago?',        ['Sumar', 'Restar', 'Nada'], 'Sumar'),
            omp('«Se me perdieron algunos.» ¿Qué hago?', ['Restar', 'Sumar', 'Multiplicar'], 'Restar'),
            omp('«¿Cuántos faltan para llegar a 10?»',   ['Restar', 'Sumar', 'Dividir'], 'Restar'),
            omp('«Junté las dos cajas.» ¿Qué hago?',     ['Sumar', 'Restar', 'Nada'], 'Sumar'),
            omp('«¿Cuántos más tiene Ana que Luis?»',    ['Restar', 'Sumar', 'Multiplicar'], 'Restar'),
        ]),

        est('Datos que sobran', 'No todo número sirve', '🗑️', 'opcion_multiple', [
            omp('«Ana tiene 7 años y 4 lápices. Le dan 2 lápices más.» ¿Qué dato sobra?', ['Que tiene 7 años', 'Los 4 lápices', 'Los 2 lápices'], 'Que tiene 7 años'),
            omp('¿Cuántos lápices tiene Ana ahora?', ['6', '11', '9'], '6'),
            omp('«El bus salió a las 8 y lleva 20 personas. Suben 5.» ¿Qué sobra?', ['La hora', 'Las 20 personas', 'Las 5 que suben'], 'La hora'),
            omp('¿Cuántas personas van ahora?',      ['25', '20', '28'], '25'),
            omp('Si falta un dato, ¿qué hago?',      ['Digo que no se puede resolver', 'Me lo invento', 'Pongo cero'], 'Digo que no se puede resolver'),
        ]),

        est('Ordena cómo se resuelve', 'Siempre igual', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para resolver un problema',
            'items' => ['Leer el problema entero', 'Ver qué preguntan',
                        'Buscar los datos que sirven', 'Hacer la operación',
                        'Comprobar si la respuesta tiene sentido'],
        ]),
    ],
],

[
    'slug'  => 'quien-lo-hizo',
    'title' => '¿Quién lo hizo?',
    'description' => 'Casos con pistas: descartar hasta que solo quede una respuesta posible.',
    'objective' => 'Aplicar razonamiento por descarte con dos o tres condiciones.',
    'icon' => '🔦', 'nivel' => 'primaria-inicial', 'bloque' => 'retos-de-deduccion',
    'duracion' => 11, 'tags' => ['deduccion', 'logica', 'reto'],
    'estaciones' => [

        est('Descartar', 'Lo que no puede ser, se quita', '❌', 'opcion_multiple', [
            omp('Ana, Luis o Sara. No fue una niña. ¿Quién fue?', ['Luis', 'Ana', 'Sara'], 'Luis'),
            omp('Rojo, azul o verde. No es el color del cielo despejado. ¿Cuál queda… si además no es rojo?', ['Verde', 'Azul', 'Rojo'], 'Verde'),
            omp('Perro, gato o pez. Tiene plumas… ninguno. ¿Qué concluyo?', ['Ninguno cumple', 'El perro', 'El pez'], 'Ninguno cumple'),
            omp('Es un animal, vive en el agua y no es pez. Puede ser…', ['Un pulpo', 'Un gato', 'Un pájaro'], 'Un pulpo'),
            omp('Descartar sirve para…', ['Quedarse con lo posible', 'Adivinar', 'Perder tiempo'], 'Quedarse con lo posible'),
        ]),

        est('El caso de la merienda', 'Tres pistas, una respuesta', '🍎', 'opcion_multiple', [
            omp('Ana no comió fruta. Luis comió banano. Sara comió lo que quedó: galleta o manzana. Si Ana comió galleta, Sara comió…', ['Manzana', 'Galleta', 'Banano'], 'Manzana'),
            omp('¿Quién comió banano?',        ['Luis', 'Ana', 'Sara'], 'Luis'),
            omp('¿Ana comió fruta?',           ['No', 'Sí', 'No se sabe'], 'No'),
            omp('¿La manzana es fruta?',       ['Sí', 'No', 'A veces'], 'Sí'),
            omp('Por eso Ana no pudo comer…',  ['Manzana', 'Galleta', 'Nada'], 'Manzana'),
        ]),

        est('Más alto, más bajo', 'Ordenar por comparaciones', '📏', 'opcion_multiple', [
            omp('Ana es más alta que Luis. Luis más alto que Sara. ¿Quién es la más alta?', ['Ana', 'Luis', 'Sara'], 'Ana'),
            omp('¿Quién es la más baja?',      ['Sara', 'Ana', 'Luis'], 'Sara'),
            omp('¿Luis es más alto que Ana?',  ['No', 'Sí', 'Igual'], 'No'),
            omp('Pedro tiene más que Ana. Ana más que Luis. ¿Quién tiene menos?', ['Luis', 'Pedro', 'Ana'], 'Luis'),
            omp('Si A > B y B > C, entonces A…', ['Es mayor que C', 'Es menor que C', 'Es igual a C'], 'Es mayor que C'),
        ]),

        est('Reto de detective', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Si no es rojo ni azul, y solo hay rojo, azul y verde, es…', ['Verde', 'Rojo', 'Azul'], 'Verde'),
            reto('Todos los gatos tienen bigotes. Michi es gato. Entonces Michi…', ['Tiene bigotes', 'No tiene', 'Quizá'], 'Tiene bigotes'),
            reto('Si llueve me mojo. Estoy seco. Entonces…', ['No llovió o me cubrí', 'Llovió', 'Siempre llueve'], 'No llovió o me cubrí'),
            reto('Ana llegó antes que Luis y después que Sara. ¿Quién llegó primero?', ['Sara', 'Ana', 'Luis'], 'Sara'),
            reto('Para resolver un caso hace falta…', ['Usar todas las pistas', 'Adivinar', 'Una sola pista'], 'Usar todas las pistas'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA MEDIA · la pieza que faltaba
// =====================================================================

[
    'slug'  => 'tablas-y-rejillas-logicas',
    'title' => 'Tablas y rejillas lógicas',
    'description' => 'Cuando hay muchas pistas, una tabla las ordena y la respuesta aparece sola.',
    'objective' => 'Organizar información en una tabla de doble entrada para resolver por descarte.',
    'icon' => '🧮', 'nivel' => 'primaria-media', 'bloque' => 'retos-de-deduccion',
    'duracion' => 13, 'tags' => ['deduccion', 'logica', 'clasificacion'],
    'estaciones' => [

        est('Tres niños, tres mascotas', 'Usa todas las pistas', '🐾', 'opcion_multiple', [
            omp('Ana no tiene perro. Luis tiene gato. Sara no tiene pez. ¿Qué tiene Sara?', ['Perro', 'Gato', 'Pez'], 'Perro'),
            omp('Entonces Ana tiene…',        ['Pez', 'Perro', 'Gato'], 'Pez'),
            omp('¿Qué pista fue la más útil?', ['Que Luis tiene gato', 'El nombre de Ana', 'Ninguna'], 'Que Luis tiene gato'),
            omp('Una pista negativa («no tiene») sirve para…', ['Descartar', 'Confirmar directo', 'Nada'], 'Descartar'),
            omp('Si dos pistas se contradicen, entonces…', ['Alguna está mal', 'Las dos valen', 'Da igual'], 'Alguna está mal'),
        ]),

        est('Tres materias, tres días', 'Ordena con la tabla', '📅', 'opcion_multiple', [
            omp('Matemáticas no es lunes. Ciencias es miércoles. Lengua no es lunes. ¿Qué hay el lunes?', ['Ninguna de esas tres', 'Matemáticas', 'Lengua'], 'Ninguna de esas tres'),
            omp('Si además hay Arte el lunes, entonces Matemáticas es…', ['Martes', 'Lunes', 'Miércoles'], 'Martes'),
            omp('¿Y Lengua?',                 ['Ya no cabe en esos días', 'Lunes', 'Miércoles'], 'Ya no cabe en esos días'),
            omp('La tabla sirve para…',       ['Ver qué queda libre', 'Adivinar', 'Escribir bonito'], 'Ver qué queda libre'),
            omp('Cuando una casilla queda sola en su fila…', ['Esa es la respuesta', 'Hay que borrar', 'Se repite'], 'Esa es la respuesta'),
        ]),

        est('Ordena el método', 'Cómo se llena una rejilla', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se resuelve con una tabla',
            'items' => ['Escribir las opciones en filas y columnas', 'Marcar lo que NO puede ser',
                        'Marcar lo que SÍ está confirmado', 'Tachar el resto de esa fila y columna',
                        'Leer lo que queda'],
        ]),

        est('Reto de la rejilla', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('En una rejilla, una X significa…',  ['Que no puede ser', 'Que sí es', 'Nada'], 'Que no puede ser'),
            reto('Si confirmo una casilla, en su fila…', ['Todo lo demás se tacha', 'Todo se confirma', 'No pasa nada'], 'Todo lo demás se tacha'),
            reto('Con 3 personas y 3 objetos, cada persona tiene…', ['Uno distinto', 'Todos', 'Ninguno'], 'Uno distinto'),
            reto('Una pista que no aporta nada…',     ['Se deja de lado', 'Se usa igual', 'Invalida todo'], 'Se deja de lado'),
            reto('Si al final quedan dos posibles, entonces…', ['Falta una pista', 'Elijo cualquiera', 'Está resuelto'], 'Falta una pista'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR · dudar bien
// =====================================================================

[
    'slug'  => 'causa-o-casualidad',
    'title' => 'Causa o casualidad',
    'description' => 'Que dos cosas pasen juntas no significa que una cause la otra.',
    'objective' => 'Distinguir correlación de causalidad en afirmaciones cotidianas.',
    'icon' => '🔗', 'nivel' => 'primaria-superior', 'bloque' => 'pensamiento-critico',
    'duracion' => 15, 'tags' => ['logica', 'deduccion', 'comprension'],
    'estaciones' => [

        est('¿De verdad lo causa?', 'Piensa si hay otra explicación', '🤨', 'opcion_multiple', [
            omp('«En verano se vende más helado Y hay más ahogamientos. El helado causa ahogamientos.»', ['Falso: los dos suben por el calor', 'Verdadero', 'Depende del sabor'], 'Falso: los dos suben por el calor'),
            omp('«Me puse la camiseta de la suerte y ganamos.» Eso es…', ['Casualidad', 'Causa', 'Ciencia'], 'Casualidad'),
            omp('«Estudié y saqué buena nota.» Aquí sí hay…', ['Una causa razonable', 'Solo casualidad', 'Un error'], 'Una causa razonable'),
            omp('Para decir que A causa B hace falta…', ['Probarlo, no solo verlo junto', 'Verlo una vez', 'Que suene bien'], 'Probarlo, no solo verlo junto'),
            omp('«Los pueblos con más cigüeñas tienen más bebés.» Seguramente…', ['Los dos dependen de otra cosa', 'Las cigüeñas los traen', 'Es mentira que haya cigüeñas'], 'Los dos dependen de otra cosa'),
        ]),

        est('Trampas de razonamiento', 'Argumentos que no lo son', '🪤', 'opcion_multiple', [
            omp('«Lo dice mucha gente, así que es verdad.» Eso es…', ['Una trampa', 'Una prueba', 'Un experimento'], 'Una trampa'),
            omp('«Es famoso, así que sabe de medicina.» Eso es…',   ['Una trampa', 'Una prueba', 'Correcto'], 'Una trampa'),
            omp('«O estás conmigo o estás contra mí.» Eso es…',     ['Falso dilema', 'Verdad', 'Una pregunta'], 'Falso dilema'),
            omp('«Siempre se ha hecho así, luego está bien.» Eso es…', ['Una trampa', 'Una razón buena', 'Un dato'], 'Una trampa'),
            omp('Un buen argumento se apoya en…',                   ['Pruebas', 'Gritos', 'Repetición'], 'Pruebas'),
        ]),

        est('Hecho, opinión y suposición', 'Tres cosas distintas', '⚖️', 'juego_rapido',
            conTitulo('¿Es un HECHO comprobable?', 'No opinión, no suposición', [
                ['e' => '🌍', 'n' => 'La Tierra gira alrededor del Sol', 'ok' => true],
                ['e' => '🎬', 'n' => 'Esa película es la mejor',          'ok' => false],
                ['e' => '📏', 'n' => 'Un kilómetro tiene 1000 metros',    'ok' => true],
                ['e' => '🤔', 'n' => 'Seguro que mañana llueve',          'ok' => false],
                ['e' => '💧', 'n' => 'El agua se congela a 0 °C',         'ok' => true],
                ['e' => '🍫', 'n' => 'El chocolate es mejor que la fruta','ok' => false],
                ['e' => '🇨🇴', 'n' => 'Colombia está en Suramérica',      'ok' => true],
                ['e' => '👕', 'n' => 'Ese color te queda mal',            'ok' => false],
            ])),

        est('Pedir pruebas', 'La pregunta que desarma casi todo', '🔬', 'opcion_multiple', [
            omp('Alguien afirma algo raro. La mejor pregunta es…', ['¿Cómo lo sabes?', '¿Quién eres?', '¿Y qué?'], '¿Cómo lo sabes?'),
            omp('«Un estudio lo demuestra.» Conviene preguntar…',  ['Cuál estudio y quién lo hizo', 'Nada más', 'Si es largo'], 'Cuál estudio y quién lo hizo'),
            omp('Un caso único («a mi tío le pasó») sirve como…',  ['Anécdota, no prueba', 'Prueba completa', 'Ley'], 'Anécdota, no prueba'),
            omp('Cambiar de opinión ante una prueba nueva es…',    ['Señal de que pienso bien', 'Debilidad', 'Un error'], 'Señal de que pienso bien'),
            omp('Quien afirma algo extraordinario debe…',          ['Probarlo', 'Repetirlo más alto', 'Enojarse'], 'Probarlo'),
        ]),
    ],
],

[
    'slug'  => 'estimar-antes-de-calcular',
    'title' => 'Estimar antes de calcular',
    'description' => 'Saber más o menos cuánto va a dar evita entregar un resultado absurdo.',
    'objective' => 'Estimar órdenes de magnitud y usar la estimación para validar un resultado.',
    'icon' => '📐', 'nivel' => 'primaria-superior', 'bloque' => 'resolver-problemas',
    'duracion' => 14, 'tags' => ['calculo', 'logica', 'deduccion'],
    'estaciones' => [

        est('Más o menos, ¿cuánto?', 'Redondea y calcula rápido', '🎯', 'opcion_multiple', [
            omp('198 + 203 es más o menos…',      ['400', '200', '800'], '400'),
            omp('49 × 21 es más o menos…',        ['1000', '100', '10000'], '1000'),
            omp('997 − 498 es más o menos…',      ['500', '1500', '50'], '500'),
            omp('1205 ÷ 4 es más o menos…',       ['300', '30', '3000'], '300'),
            omp('9,8 × 10,2 es más o menos…',     ['100', '10', '1000'], '100'),
        ]),

        est('¿Tiene sentido?', 'Un resultado absurdo se detecta solo', '🤔', 'opcion_multiple', [
            omp('Un niño mide 15 metros. ¿Tiene sentido?',           ['No', 'Sí', 'Depende'], 'No'),
            omp('Repartí 10 entre 3 y a cada uno le tocaron 20.',    ['No tiene sentido', 'Correcto', 'Depende'], 'No tiene sentido'),
            omp('Tenía 50, gasté 20 y me quedan 70.',                ['No tiene sentido', 'Correcto', 'A veces'], 'No tiene sentido'),
            omp('Un salón de clase mide 8 metros de largo.',         ['Tiene sentido', 'Imposible', 'Demasiado'], 'Tiene sentido'),
            omp('Una persona pesa 3 kilos.',                          ['No tiene sentido', 'Normal', 'Sí, de adulto'], 'No tiene sentido'),
        ]),

        est('Órdenes de magnitud', '¿Decenas, cientos o miles?', '🔢', 'opcion_multiple', [
            omp('¿Cuántos estudiantes caben en un salón?',  ['Unas decenas', 'Unos miles', 'Unos millones'], 'Unas decenas'),
            omp('¿Cuántas personas viven en una ciudad grande?', ['Millones', 'Decenas', 'Cientos'], 'Millones'),
            omp('¿Cuántos días tiene un año?',              ['Cientos', 'Decenas', 'Millones'], 'Cientos'),
            omp('¿Cuántos pelos tiene una cabeza?',          ['Cien mil, más o menos', 'Cien', 'Diez'], 'Cien mil, más o menos'),
            omp('¿Cuántos minutos tiene un día?',            ['Más de mil', 'Menos de cien', 'Un millón'], 'Más de mil'),
        ]),

        est('Ordena la comprobación', 'Estimar, calcular, comparar', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se usa la estimación',
            'items' => ['Leer el problema', 'Estimar más o menos cuánto va a dar',
                        'Hacer el cálculo exacto', 'Comparar con la estimación',
                        'Si no se parecen, revisar'],
        ]),
    ],
],

[
    'slug'  => 'argumentar-y-convencer',
    'title' => 'Argumentar y convencer',
    'description' => 'Una opinión con razones y pruebas vale; una opinión a secas, no.',
    'objective' => 'Construir un argumento con afirmación, razón y prueba, y detectar los que no la tienen.',
    'icon' => '🗣️', 'nivel' => 'primaria-superior', 'bloque' => 'pensamiento-critico',
    'duracion' => 15, 'tags' => ['comprension', 'logica', 'escritura'],
    'estaciones' => [

        est('Las tres partes', 'Afirmación, razón y prueba', '🧱', 'opcion_multiple', [
            omp('«Hay que dormir 9 horas» es una…',                 ['Afirmación', 'Prueba', 'Razón'], 'Afirmación'),
            omp('«Porque el cuerpo se repara al dormir» es una…',   ['Razón', 'Afirmación', 'Prueba'], 'Razón'),
            omp('«Un estudio de pediatría lo midió» es una…',       ['Prueba', 'Opinión', 'Afirmación'], 'Prueba'),
            omp('Un argumento sin razones es…',                     ['Solo una opinión', 'Más fuerte', 'Una prueba'], 'Solo una opinión'),
            omp('Lo que hace fuerte a un argumento es…',            ['La prueba', 'El volumen', 'La insistencia'], 'La prueba'),
        ]),

        est('¿Cuál argumento es mejor?', 'Compara dos formas de decir lo mismo', '⚖️', 'opcion_multiple', [
            omp('¿Cuál convence más?', ['Reciclar reduce la basura del relleno', 'Reciclar es lo mejor', 'Hay que reciclar y ya'], 'Reciclar reduce la basura del relleno'),
            omp('¿Cuál convence más?', ['Leer mejora el vocabulario', 'Leer es bonito', 'Todos deberían leer'], 'Leer mejora el vocabulario'),
            omp('¿Cuál convence más?', ['El casco reduce las lesiones graves', 'El casco se ve bien', 'Póntelo porque sí'], 'El casco reduce las lesiones graves'),
            omp('Un argumento que ataca a la persona en vez de la idea es…', ['Una trampa', 'Fuerte', 'Correcto'], 'Una trampa'),
            omp('Escuchar el argumento contrario sirve para…', ['Pensar mejor el propio', 'Perder', 'Nada'], 'Pensar mejor el propio'),
        ]),

        est('Ordena un texto que convence', 'Tiene una estructura', '📝', 'ordenar_secuencia', [
            'title' => 'Ordena las partes de un texto argumentativo',
            'items' => ['Presentar el tema', 'Decir mi posición', 'Dar la primera razón con su prueba',
                        'Dar una segunda razón', 'Reconocer lo que dice el otro lado',
                        'Cerrar repitiendo la posición'],
        ]),

        est('Reto de argumentar', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('«Porque sí» es…',                        ['No es una razón', 'Una razón', 'Una prueba'], 'No es una razón'),
            reto('Reconocer lo bueno del otro lado hace mi texto…', ['Más creíble', 'Más débil', 'Más largo'], 'Más creíble'),
            reto('Un dato inventado que suena bien es…',   ['Mentir', 'Convencer', 'Argumentar'], 'Mentir'),
            reto('Si me dan una prueba mejor que la mía…', ['Cambio de opinión', 'Grito más', 'La ignoro'], 'Cambio de opinión'),
            reto('La diferencia entre discutir y argumentar es…', ['Las razones', 'El volumen', 'Quién empieza'], 'Las razones'),
        ]),
    ],
],

[
    /*
     * `problemas-de-varios-pasos` ya existe en Matemática, y allí el
     * trabajo es calcular. Aquí es PARTIR: decidir qué se puede calcular
     * primero, que es lo que falla antes de llegar a la cuenta.
     */
    'slug'  => 'partir-el-problema',
    'title' => 'Partir el problema',
    'description' => 'Cuando un problema no se resuelve con una sola operación, hay que partirlo.',
    'objective' => 'Descomponer problemas multi-etapa identificando resultados intermedios.',
    'icon' => '🪜', 'nivel' => 'primaria-superior', 'bloque' => 'resolver-problemas',
    'duracion' => 15, 'tags' => ['calculo', 'logica', 'comprension'],
    'estaciones' => [

        est('¿Cuántos pasos hacen falta?', 'Algunos piden dos operaciones', '🔢', 'opcion_multiple', [
            omp('«Compro 3 cuadernos de $2000 y pago con $10000. ¿Cuánto me devuelven?» ¿Cuántos pasos?', ['Dos', 'Uno', 'Tres'], 'Dos'),
            omp('¿Cuál es el primer paso?',      ['Multiplicar 3 × 2000', 'Restar de 10000', 'Dividir'], 'Multiplicar 3 × 2000'),
            omp('¿Cuánto costaron los cuadernos?', ['6000', '5000', '2000'], '6000'),
            omp('¿Cuánto devuelven?',            ['4000', '6000', '8000'], '4000'),
            omp('Al resultado del primer paso se le llama…', ['Resultado intermedio', 'Respuesta final', 'Dato que sobra'], 'Resultado intermedio'),
        ]),

        est('Parte el problema', 'Uno grande son varios pequeños', '✂️', 'opcion_multiple', [
            omp('«Un bus lleva 40 personas. Bajan 12 y suben 7. ¿Cuántas van?» Primero…', ['Resto 12', 'Sumo 7', 'Multiplico'], 'Resto 12'),
            omp('¿Cuántas quedan tras bajar 12?', ['28', '33', '40'], '28'),
            omp('¿Y después de subir 7?',         ['35', '28', '47'], '35'),
            omp('«6 cajas de 12 lápices, reparto entre 8 niños.» Primero…', ['Multiplico 6 × 12', 'Divido entre 8', 'Sumo'], 'Multiplico 6 × 12'),
            omp('¿Cuántos lápices le tocan a cada niño?', ['9', '12', '6'], '9'),
        ]),

        est('¿Qué me falta saber?', 'A veces falta un dato', '❓', 'opcion_multiple', [
            omp('«Compré lápices y pagué $10000. ¿Cuánto me devuelven?» Falta…', ['Cuánto costaron', 'Mi nombre', 'La hora'], 'Cuánto costaron'),
            omp('«Recorrí el camino en 2 horas. ¿A qué velocidad iba?» Falta…', ['La distancia', 'El día', 'El color'], 'La distancia'),
            omp('«El rectángulo mide 5 de largo. ¿Cuál es su área?» Falta…', ['El ancho', 'El color', 'El perímetro'], 'El ancho'),
            omp('Si falta un dato, la respuesta correcta es…', ['«No se puede resolver»', 'Inventarlo', 'Cero'], '«No se puede resolver»'),
            omp('¿Cómo sé qué dato falta?',   ['Miro qué necesito para cada paso', 'Adivino', 'Sumo todo'], 'Miro qué necesito para cada paso'),
        ]),

        est('Ordena el plan', 'Antes de calcular, planear', '🗺️', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se ataca un problema de varios pasos',
            'items' => ['Leer y entender qué preguntan', 'Anotar los datos',
                        'Decidir qué se puede calcular primero', 'Calcular el resultado intermedio',
                        'Usarlo para llegar al final', 'Comprobar que tiene sentido'],
        ]),
    ],
],

],
];
