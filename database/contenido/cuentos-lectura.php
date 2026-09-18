<?php
/**
 * cuentos-lectura.php — «Ciudad de los Cuentos», ahora con los cuentos
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ ESTABA MAL
 * ─────────────────────────────────────────────────────────────────────
 *
 * La actividad venía del sitio anterior y preguntaba cosas como
 *
 *     ¿Quién despertó al León?
 *     ¿Cómo atraparon los cazadores al León?
 *     ¿Cuál es la moraleja del cuento?
 *
 * en estaciones de opción múltiple **sin haber mostrado ningún cuento**.
 * La descripción decía «Lee el primer cuento y responde preguntas», pero
 * no había nada que leer.
 *
 * Para un niño que no conociera ya la fábula, esas preguntas no se pueden
 * responder: solo se pueden adivinar. Y adivinar con cuatro opciones
 * acierta una de cada cuatro veces, así que el juego le dice que se
 * equivocó tres de cada cuatro por algo que nunca le enseñó. Es el peor
 * tipo de error del catálogo, porque no falla —funciona perfectamente y
 * hace sentir tonto a quien lo juega.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ SE CAMBIA, Y QUÉ NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * **Las diez preguntas se conservan tal cual.** Estaban bien escritas: el
 * problema nunca fueron las preguntas, era que faltaba el texto sobre el
 * que preguntan. Lo único que cambia es el tipo de estación, de
 * `opcion_multiple` a `cuento`, que muestra las diapositivas primero y
 * pregunta después.
 *
 * Las dos fábulas —«El león y el ratón» y «La liebre y la tortuga»— son
 * de Esopo y de dominio público. Se cuentan con frases cortas y una idea
 * por diapositiva, que es lo que pide el nivel de tercero y cuarto.
 *
 * La estación 2, la de ordenar, tampoco se podía resolver antes: sin el
 * cuento no hay forma de saber si el león atrapó al ratón antes o después
 * de que este lo despertara. No se toca su contenido, pero ahora va
 * DESPUÉS del cuento y por eso tiene sentido.
 */

declare(strict_types=1);

return [

/*
 * La categoría y el bloque ya existen. Van aquí con sus valores actuales,
 * exactos, porque el sembrador hace UPDATE sobre ellos: copiarlos mal
 * renombraría «Aventura de las Letras» de rebote.
 */
'categoria' => [
    'slug'       => 'letras',
    'name'       => 'Aventura de las Letras',
    'tagline'    => 'Lectoescritura',
    'icon'       => '✏️',
    'color'      => '#29b6f6',
    'sort_order' => 1,
],

'bloques' => [
    ['slug' => 'mundos-de-lectura', 'name' => 'Mundos de Lectura y Escritura',
     'icon' => '📖', 'sort_order' => 3,
     'description' => 'Sílabas, cuentos, ortografía y escritura en aventuras completas.'],
],

'actividades' => [

[
    'slug'  => 'ciudad-de-los-cuentos',
    'title' => 'Ciudad de los Cuentos',
    'description' => 'Dos fábulas de Esopo para leer completas y luego demostrar '
                   . 'que entendiste lo que pasó.',
    'objective' => 'Comprender dos narraciones breves: identificar personajes, orden de '
                 . 'los hechos y moraleja.',
    'icon' => '📖', 'nivel' => 'primaria-media', 'bloque' => 'mundos-de-lectura',
    'duracion' => 20, 'tags' => ['lectura', 'comprension', 'vocabulario'],
    'estaciones' => [

        // =============================================================
        //  1 · EL LEÓN Y EL RATÓN
        // =============================================================

        est('El león y el ratón', 'Lee la fábula y responde', '🦁', 'cuento', [
            'slides' => [
                ['img' => '🦁', 'text' => 'Un león enorme dormía la siesta a la sombra de un árbol.'],
                ['img' => '🐭', 'text' => 'Un ratoncito pasó corriendo por encima de su melena y lo despertó.'],
                ['img' => '😾', 'text' => 'El león lo atrapó con la pata. «¡Te voy a comer!», rugió.'],
                ['img' => '🙏', 'text' => '«Suéltame, por favor», pidió el ratón. «Algún día te ayudaré».'],
                ['img' => '😂', 'text' => 'Al león le dio tanta risa la idea que lo dejó marchar.'],
                ['img' => '🕸️', 'text' => 'Días después, unos cazadores atraparon al león con una red.'],
                ['img' => '😰', 'text' => 'El león rugía y rugía, pero no podía soltarse.'],
                ['img' => '🐭', 'text' => 'El ratoncito oyó los rugidos y vino corriendo.'],
                ['img' => '✂️', 'text' => 'Con sus dientes pequeños royó la red hasta abrir un hueco.'],
                ['img' => '🤝', 'text' => 'El león quedó libre. Desde entonces fueron amigos.'],
            ],
            'qs' => [
                reto('¿Quién despertó al León?',
                     ['Un cazador', 'El ratón', 'Un pájaro', 'Otro león'], 'El ratón'),
                reto('¿Cómo atraparon los cazadores al León?',
                     ['Con una jaula', 'Con una red', 'Con una trampa', 'Con sogas'], 'Con una red'),
                reto('¿Cómo liberó el ratón al León?',
                     ['Llamó ayuda', 'Royó la red', 'Abrió la jaula', 'Asustó a los cazadores'],
                     'Royó la red'),
                reto('¿Cuál es la moraleja del cuento?',
                     ['Los ratones son más listos', 'Los amigos se ayudan sin importar el tamaño',
                      'Los leones son muy buenos', 'El bosque es peligroso'],
                     'Los amigos se ayudan sin importar el tamaño'),
                reto('¿Cómo terminó la historia?',
                     ['El León comió al ratón', 'El ratón se escapó solo',
                      'El ratón y el León se hicieron amigos', 'El León fue al zoológico'],
                     'El ratón y el León se hicieron amigos'),
            ],
        ]),

        // =============================================================
        //  2 · ORDENAR LO QUE PASÓ
        //
        //  Va justo después del cuento a propósito: ordenar hechos que
        //  uno no ha leído es adivinar.
        // =============================================================

        est('Camino de los Desafíos', 'Ordena lo que pasó en la fábula', '🎯', 'ordenar_secuencia', [
            'title' => 'Ordena lo que pasó en la historia',
            'items' => [
                '🐭 El ratón despertó al León',
                '🦁 El León atrapó al ratoncito',
                '🙏 El ratón pidió ser liberado',
                '🕸️ Los cazadores atraparon al León con una red',
                '✂️ El ratón royó la red y liberó al León',
            ],
        ]),

        // =============================================================
        //  3 · LA LIEBRE Y LA TORTUGA
        // =============================================================

        est('La liebre y la tortuga', 'La gran fábula final', '🐢', 'cuento', [
            'slides' => [
                ['img' => '🐇', 'text' => 'Una liebre presumía todo el día de lo rápido que corría.'],
                ['img' => '🐢', 'text' => 'Cansada de oírla, una tortuga la desafió a una carrera.'],
                ['img' => '😆', 'text' => 'Todos los animales se rieron. ¡La tortuga era lentísima!'],
                ['img' => '🏁', 'text' => 'El zorro dio la señal y la carrera empezó.'],
                ['img' => '💨', 'text' => 'La liebre salió disparada y en un momento ya no se la veía.'],
                ['img' => '🐢', 'text' => 'La tortuga caminaba despacio, paso a paso, sin parar nunca.'],
                ['img' => '😴', 'text' => 'La liebre iba tan adelantada que se echó a dormir bajo un árbol.'],
                ['img' => '🚶', 'text' => 'La tortuga pasó por su lado sin hacer ruido y siguió andando.'],
                ['img' => '😱', 'text' => 'La liebre despertó tarde y corrió como nunca.'],
                ['img' => '🏆', 'text' => 'Pero la tortuga ya estaba cruzando la meta.'],
            ],
            'qs' => [
                reto('¿Quién desafió a la liebre a una carrera?',
                     ['El conejo', 'La tortuga', 'El zorro', 'El caracol'], 'La tortuga'),
                reto('¿Por qué perdió la liebre?',
                     ['Se perdió en el camino', 'Se quedó dormida confiada',
                      'Tropezó y cayó', 'El árbitro la descalificó'], 'Se quedó dormida confiada'),
                reto('¿Cuál es la moraleja de la fábula?',
                     ['Los lentos son mejores', 'La liebre es tramposa',
                      'El que persevera, alcanza', 'Las carreras no son importantes'],
                     'El que persevera, alcanza'),
                reto('¿Quién llegó primero a la meta?',
                     ['La liebre', 'La tortuga', 'Llegaron juntos', 'Ninguno llegó'], 'La tortuga'),
                reto('¿Qué hizo la tortuga durante la carrera?',
                     ['También se echó a descansar', 'Siguió caminando sin parar',
                      'Pidió ayuda a otros animales', 'Tomó un atajo'],
                     'Siguió caminando sin parar'),
            ],
        ]),
    ],
],

],
];
