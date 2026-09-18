<?php
/**
 * matematica-ampliacion.php — Matemática en preescolar
 *
 * Cinco actividades de preescolar frente a dieciocho de tercero. Faltaba
 * lo anterior al número: **clasificar, comparar y ordenar**, que es
 * donde se construye la idea de cantidad.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  CONTAR NO ES LO PRIMERO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un niño puede recitar «uno, dos, tres…» hasta veinte sin tener ni idea
 * de cuántos son tres. Lo que sostiene el número es otra cosa: saber que
 * tres galletas siguen siendo tres aunque se separen, que hay más en un
 * montón que en otro, y que los objetos se pueden emparejar uno a uno.
 *
 * Estas tres actividades trabajan eso. La cuarta ya cuenta.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'matematica',
    'name'       => 'Matemática',
    'tagline'    => 'Números, formas y datos, de contar hasta razonar',
    'icon'       => '🔢',
    'color'      => '#ef6c00',
    'sort_order' => 2,
],

'bloques' => [
    ['slug' => 'primeros-numeros', 'name' => 'Primeros Números', 'icon' => '🌈', 'sort_order' => 1,
     'description' => 'El comienzo: contar, comparar y reconocer formas y tamaños.'],
    ['slug' => 'logica-y-medida', 'name' => 'Lógica y Medida', 'icon' => '🧩', 'sort_order' => 3,
     'description' => 'Figuras, secuencias, tiempo y razonamiento.'],
],

'actividades' => [

[
    'slug'  => 'muchos-pocos-y-ninguno',
    'title' => 'Muchos, pocos y ninguno',
    'description' => 'Comparar cantidades sin contar: dónde hay más y dónde hay menos.',
    'objective' => 'Comparar cantidades por estimación y correspondencia uno a uno.',
    'icon' => '⚖️', 'nivel' => 'preescolar', 'bloque' => 'primeros-numeros',
    'duracion' => 8, 'tags' => ['logica', 'observacion', 'calculo'],
    'estaciones' => [

        est('¿Dónde hay más?', 'Mira sin contar', '👀', 'opcion_multiple', [
            omp('🍎🍎🍎🍎 o 🍎🍎. ¿Dónde hay más?',   ['En el primero', 'En el segundo', 'Igual'], 'En el primero'),
            omp('⭐ o ⭐⭐⭐⭐⭐. ¿Dónde hay más?',     ['En el segundo', 'En el primero', 'Igual'], 'En el segundo'),
            omp('🐶🐶🐶 o 🐱🐱🐱. ¿Dónde hay más?',    ['Igual', 'En el primero', 'En el segundo'], 'Igual'),
            omp('Si no hay ninguno, hay…',            ['Cero', 'Uno', 'Muchos'], 'Cero'),
            omp('Si hay muchísimos, no hace falta…',  ['Contarlos todos para ver que son más', 'Mirar', 'Nada'], 'Contarlos todos para ver que son más'),
        ]),

        est('Uno para cada uno', 'Emparejar sin contar', '🔗', 'opcion_multiple', [
            omp('Hay 3 niños y 3 sillas. ¿Alcanza?',    ['Sí, una para cada uno', 'No', 'Sobran sillas'], 'Sí, una para cada uno'),
            omp('Hay 4 niños y 2 sillas. ¿Alcanza?',    ['No, faltan sillas', 'Sí', 'Sobran'], 'No, faltan sillas'),
            omp('Hay 2 niños y 5 sillas. Entonces…',    ['Sobran sillas', 'Faltan', 'Alcanza justo'], 'Sobran sillas'),
            omp('Si a cada uno le toca uno y no sobra ninguno, hay…', ['La misma cantidad', 'Más de unos', 'Menos'], 'La misma cantidad'),
            omp('Repartir uno a uno sirve para…',       ['Comparar sin contar', 'Ir más lento', 'Nada'], 'Comparar sin contar'),
        ]),

        est('Lleno y vacío', 'Cantidades en recipientes', '🥤', 'opcion_multiple', [
            omp('Un vaso sin nada está…',    ['Vacío', 'Lleno', 'Medio'], 'Vacío', '🥤'),
            omp('Un vaso hasta el borde está…', ['Lleno', 'Vacío', 'Roto'], 'Lleno'),
            omp('Si tomo la mitad, queda…',  ['Medio vaso', 'Lleno', 'Vacío'], 'Medio vaso'),
            omp('¿Dónde cabe más agua: en una cuchara o en una olla?', ['La olla', 'La cuchara', 'Igual'], 'La olla', '🍲'),
            omp('Si echo agua en un vaso lleno…', ['Se derrama', 'Cabe igual', 'Desaparece'], 'Se derrama'),
        ]),

        est('¿Cuántos ves?', 'Cuenta despacio', '🔢', 'opcion_multiple', [
            omp('🐶🐶 ¿cuántos?',       ['2', '1', '3'], '2'),
            omp('⭐⭐⭐⭐ ¿cuántas?',   ['4', '3', '5'], '4'),
            omp('🍎 ¿cuántas?',          ['1', '2', '0'], '1'),
            omp('🌸🌸🌸 ¿cuántas?',     ['3', '2', '4'], '3'),
            omp('Si no hay ninguno, ¿cuántos hay?', ['0', '1', 'Muchos'], '0'),
        ]),
    ],
],

[
    'slug'  => 'ordenar-por-tamano',
    'title' => 'Ordenar por tamaño',
    'description' => 'Del más pequeño al más grande: seriar es el paso previo a numerar.',
    'objective' => 'Seriar objetos por una magnitud y describir la relación de orden.',
    'icon' => '📏', 'nivel' => 'preescolar', 'bloque' => 'logica-y-medida',
    'duracion' => 8, 'tags' => ['logica', 'secuencias', 'observacion'],
    'estaciones' => [

        est('Del más pequeño al más grande', 'Ordena', '📏', 'ordenar_secuencia', [
            'title' => 'Ordena de lo más pequeño a lo más grande',
            'items' => ['Hormiga', 'Gato', 'Perro', 'Caballo', 'Elefante'],
        ]),

        est('Del más corto al más largo', 'Ordena', '📐', 'ordenar_secuencia', [
            'title' => 'Ordena de lo más corto a lo más largo',
            'items' => ['Lápiz', 'Regla', 'Mesa', 'Carro', 'Bus'],
        ]),

        est('Alto, medio y bajo', 'Compara la altura', '📊', 'opcion_multiple', [
            omp('¿Qué es más alto: un árbol o una flor?', ['El árbol', 'La flor'], 'El árbol', '🌳'),
            omp('¿Qué es más alto: un edificio o una casa?', ['El edificio', 'La casa'], 'El edificio', '🏢'),
            omp('¿Qué es más bajo: una silla o una mesa?', ['La silla', 'La mesa'], 'La silla', '🪑'),
            omp('Si Ana es más alta que Luis y Luis más alto que Sara, la más alta es…', ['Ana', 'Luis', 'Sara'], 'Ana'),
            omp('El del medio en altura es…',  ['Luis', 'Ana', 'Sara'], 'Luis'),
        ]),

        est('Pesado y liviano', 'Comparar el peso', '⚖️', 'opcion_multiple', [
            omp('¿Qué pesa más: una pluma o una piedra?', ['La piedra', 'La pluma'], 'La piedra', '🪨'),
            omp('¿Qué pesa más: un elefante o un ratón?', ['El elefante', 'El ratón'], 'El elefante', '🐘'),
            omp('¿Qué pesa menos: un globo o un libro?',  ['El globo', 'El libro'], 'El globo', '🎈'),
            omp('Lo grande siempre pesa más…',            ['No siempre', 'Siempre', 'Nunca'], 'No siempre'),
            omp('Una caja grande llena de plumas pesa…',  ['Poco', 'Muchísimo', 'Como una piedra'], 'Poco'),
        ]),
    ],
],

[
    'slug'  => 'juntar-y-quitar',
    'title' => 'Juntar y quitar',
    'description' => 'Cuando llegan más hay más; cuando se van, menos. La suma antes del signo.',
    'objective' => 'Resolver situaciones aditivas y sustractivas simples con apoyo visual.',
    'icon' => '➕', 'nivel' => 'preescolar', 'bloque' => 'primeros-numeros',
    'duracion' => 8, 'tags' => ['calculo', 'logica', 'comprension'],
    'estaciones' => [

        est('Si llegan más', 'Juntar cantidades', '➕', 'opcion_multiple', [
            omp('Tenía 2 y llegó 1 más. Ahora hay…',  ['3', '2', '1'], '3', '🍎'),
            omp('Tenía 3 y llegaron 2 más. Ahora hay…', ['5', '4', '6'], '5', '⭐'),
            omp('Tenía 1 y llegó 1 más. Ahora hay…',  ['2', '1', '3'], '2'),
            omp('Tenía 4 y llegó 1 más. Ahora hay…',  ['5', '4', '6'], '5'),
            omp('Cuando llegan más, la cantidad…',    ['Crece', 'Baja', 'Se queda'], 'Crece'),
        ]),

        est('Si se van', 'Quitar cantidades', '➖', 'opcion_multiple', [
            omp('Tenía 3 y se fue 1. Quedan…',     ['2', '3', '4'], '2', '🐶'),
            omp('Tenía 5 y se fueron 2. Quedan…',  ['3', '4', '2'], '3'),
            omp('Tenía 2 y se fueron 2. Quedan…',  ['0', '1', '2'], '0'),
            omp('Tenía 4 y se fue 1. Quedan…',     ['3', '4', '5'], '3'),
            omp('Cuando se van, la cantidad…',     ['Baja', 'Crece', 'Se queda'], 'Baja'),
        ]),

        est('¿Juntar o quitar?', 'Elige qué hacer', '🤔', 'opcion_multiple', [
            omp('«Me regalaron dos más.» ¿Qué hago?', ['Juntar', 'Quitar'], 'Juntar'),
            omp('«Se me perdió uno.» ¿Qué hago?',     ['Quitar', 'Juntar'], 'Quitar'),
            omp('«Vinieron tres amigos más.» ¿Qué hago?', ['Juntar', 'Quitar'], 'Juntar'),
            omp('«Me comí dos galletas.» ¿Qué hago?', ['Quitar', 'Juntar'], 'Quitar'),
            omp('«Puse todo en la misma caja.» ¿Qué hago?', ['Juntar', 'Quitar'], 'Juntar'),
        ]),

        est('Repartir en partes iguales', 'A cada uno lo mismo', '🤝', 'opcion_multiple', [
            omp('4 galletas entre 2 niños. A cada uno…', ['2', '4', '1'], '2', '🍪'),
            omp('6 dulces entre 3 niños. A cada uno…',   ['2', '3', '6'], '2', '🍬'),
            omp('2 manzanas entre 2 niños. A cada uno…', ['1', '2', '0'], '1', '🍎'),
            omp('Si sobra algo al repartir…',            ['No es parte igual todavía', 'Está bien', 'Sobra siempre'], 'No es parte igual todavía'),
            omp('Repartir en partes iguales quiere decir…', ['Que todos reciban lo mismo', 'Que uno reciba más', 'Que nadie reciba'], 'Que todos reciban lo mismo'),
        ]),
    ],
],

],
];
