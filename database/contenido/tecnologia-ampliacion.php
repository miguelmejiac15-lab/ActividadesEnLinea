<?php
/**
 * tecnologia-ampliacion.php — Los extremos de Tecnología
 *
 * Esta categoría estaba montada casi entera sobre primaria media: trece
 * actividades de tercero y cuarto, **una sola de preescolar** y cuatro de
 * quinto y sexto. Un colegio que la asignara a un curso de transición no
 * encontraba nada, y en sexto se quedaba sin material antes de octubre.
 *
 * Aquí se rellenan los dos extremos.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ ES TECNOLOGÍA EN PREESCOLAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * No es usar el computador. Es entender que **las máquinas se manejan
 * con órdenes y las órdenes van en orden**, que es exactamente el
 * pensamiento que luego se llama programar. Un niño de cinco años que
 * sabe decir «primero abro la nevera, después saco la leche, después
 * cierro» ya está algoritmizando; lo que le falta es que alguien se lo
 * nombre.
 *
 * Por eso ninguna estación de preescolar pide teclear ni arrastrar cosas
 * con precisión: piden reconocer, ordenar y decidir.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  Y QUÉ ES EN QUINTO Y SEXTO
 * ─────────────────────────────────────────────────────────────────────
 *
 * A esa edad el niño ya vive en internet. Lo que le falta no es manejar
 * una herramienta, es **desconfiar con criterio**: saber que una imagen
 * puede estar fabricada, que un buscador ordena por negocio y no por
 * verdad, y que lo que sube hoy lo lee alguien dentro de diez años.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'tecnologia',
    'name'       => 'Tecnología',
    'tagline'    => 'Crear, programar y moverse seguro en el mundo digital',
    'icon'       => '💻',
    'color'      => '#7e57c2',
    'sort_order' => 4,
],

'bloques' => [
    ['slug' => 'como-funciona', 'name' => 'Cómo Funciona', 'icon' => '🔌', 'sort_order' => 1,
     'description' => 'Las partes de una máquina y qué hace cada una.'],
    ['slug' => 'pensar-como-programador', 'name' => 'Pensar como Programador', 'icon' => '🤖', 'sort_order' => 2,
     'description' => 'Instrucciones, orden, repeticiones y errores.'],
    ['slug' => 'herramientas-digitales', 'name' => 'Herramientas Digitales', 'icon' => '🖥️', 'sort_order' => 4,
     'description' => 'Presentaciones, correo, archivos y nube: usar bien lo que ya está en el computador.'],
    ['slug' => 'ciudadania-digital', 'name' => 'Ciudadanía Digital', 'icon' => '🛡️', 'sort_order' => 7,
     'description' => 'Contraseñas, privacidad, huella digital y cómo tratarse bien en línea.'],
],

'actividades' => [


// =====================================================================
//  PREESCOLAR · las máquinas de todos los días
// =====================================================================

[
    'slug'  => 'maquinas-de-mi-casa',
    'title' => 'Máquinas de mi casa',
    'description' => 'La nevera, la licuadora, la lavadora: aparatos que hacen un trabajo por nosotros.',
    'objective' => 'Reconocer artefactos del entorno cercano e identificar para qué sirve cada uno.',
    'icon' => '🏠', 'nivel' => 'preescolar', 'bloque' => 'como-funciona',
    'duracion' => 8, 'tags' => ['observacion', 'tecnologia', 'clasificacion'],
    'estaciones' => [

        est('¿Para qué sirve?', 'Cada máquina hace un trabajo', '🔧', 'opcion_multiple', [
            omp('¿Para qué sirve la nevera?',    ['Para enfriar la comida', 'Para calentar', 'Para lavar'], 'Para enfriar la comida', '🧊'),
            omp('¿Para qué sirve la licuadora?', ['Para mezclar', 'Para planchar', 'Para barrer'], 'Para mezclar', '🥤'),
            omp('¿Para qué sirve la lavadora?',  ['Para lavar la ropa', 'Para cocinar', 'Para leer'], 'Para lavar la ropa', '👕'),
            omp('¿Para qué sirve la plancha?',   ['Para quitar las arrugas', 'Para enfriar', 'Para cortar'], 'Para quitar las arrugas', '👔'),
            omp('¿Para qué sirve el ventilador?',['Para dar aire fresco', 'Para dar calor', 'Para lavar'], 'Para dar aire fresco', '🌀'),
        ]),

        est('Cada cosa con su trabajo', 'Une la máquina con lo que hace', '🔗', 'emparejar', [
            ['e' => '🧊', 'w' => 'Enfría'],
            ['e' => '🔥', 'w' => 'Calienta'],
            ['e' => '💨', 'w' => 'Da aire'],
            ['e' => '🧹', 'w' => 'Limpia'],
            ['e' => '💡', 'w' => 'Alumbra'],
            ['e' => '📺', 'w' => 'Muestra'],
        ]),

        est('¿Necesita electricidad?', 'Unas se enchufan y otras no', '🔌', 'juego_rapido',
            conTitulo('¿Se enchufa para funcionar?', 'Piensa si lleva cable o pilas', [
                ['e' => '📺', 'n' => 'Televisor', 'ok' => true],
                ['e' => '✏️', 'n' => 'Lápiz',     'ok' => false],
                ['e' => '🔦', 'n' => 'Linterna',  'ok' => true],
                ['e' => '⚽', 'n' => 'Balón',     'ok' => false],
                ['e' => '🧊', 'n' => 'Nevera',    'ok' => true],
                ['e' => '📖', 'n' => 'Libro',     'ok' => false],
                ['e' => '🌀', 'n' => 'Ventilador','ok' => true],
                ['e' => '🥄', 'n' => 'Cuchara',   'ok' => false],
            ])),

        est('Las máquinas se cuidan', 'Qué sí y qué no', '🛡️', 'opcion_multiple', [
            omp('¿Puedo meter los dedos en el enchufe?',    ['Nunca', 'Sí', 'Solo un poquito'], 'Nunca', '⚠️'),
            omp('¿Puedo tocar la plancha caliente?',        ['No, quema', 'Sí', 'Con una mano'], 'No, quema', '🔥'),
            omp('¿Qué hago si un aparato echa humo?',       ['Aviso a un adulto', 'Lo toco', 'Lo escondo'], 'Aviso a un adulto', '💨'),
            omp('¿Se puede jugar con agua cerca de un aparato enchufado?', ['No', 'Sí', 'Un poco'], 'No', '💧'),
            omp('¿Quién enchufa las cosas en casa?',        ['Un adulto', 'Yo solo', 'Nadie'], 'Un adulto', '🧑'),
        ]),
    ],
],

[
    /*
     * Slug propio: `partes-del-computador` ya existe y es de primero.
     * Esta es la versión de preescolar, más corta y sin teclear nada.
     */
    'slug'  => 'conozco-el-computador',
    'title' => 'Conozco el computador',
    'description' => 'La pantalla, el teclado, el ratón: cómo se llama cada parte y qué hace.',
    'objective' => 'Nombrar los componentes básicos de un computador y su función.',
    'icon' => '🖥️', 'nivel' => 'preescolar', 'bloque' => 'como-funciona',
    'duracion' => 8, 'tags' => ['observacion', 'tecnologia', 'vocabulario'],
    'estaciones' => [

        est('¿Cómo se llama?', 'Cada parte tiene su nombre', '🏷️', 'opcion_multiple', [
            omp('Donde se ven los dibujos se llama…',   ['Pantalla', 'Teclado', 'Ratón'], 'Pantalla', '🖥️'),
            omp('Lo que tiene todas las letras es el…', ['Teclado', 'Pantalla', 'Cable'], 'Teclado', '⌨️'),
            omp('Lo que se mueve con la mano es el…',   ['Ratón', 'Teclado', 'Enchufe'], 'Ratón', '🖱️'),
            omp('Por donde sale el sonido son los…',    ['Parlantes', 'Botones', 'Cables'], 'Parlantes', '🔊'),
            omp('Para escuchar sin molestar uso…',      ['Audífonos', 'Parlantes', 'La pantalla'], 'Audífonos', '🎧'),
        ]),

        est('Une cada parte', 'Dibujo y nombre', '🔗', 'emparejar', [
            ['e' => '🖥️', 'w' => 'Pantalla'],
            ['e' => '⌨️', 'w' => 'Teclado'],
            ['e' => '🖱️', 'w' => 'Ratón'],
            ['e' => '🔊', 'w' => 'Parlante'],
            ['e' => '🎧', 'w' => 'Audífonos'],
            ['e' => '🖨️', 'w' => 'Impresora'],
        ]),

        est('Memoria del computador', 'Encuentra las parejas', '🧠', 'memoria',
            ['🖥️', '⌨️', '🖱️', '🔊', '🎧', '🖨️']),

        est('¿Qué uso para eso?', 'Elige la parte correcta', '🤔', 'opcion_multiple', [
            omp('Para escribir mi nombre uso el…',   ['Teclado', 'Parlante', 'Ratón'], 'Teclado'),
            omp('Para señalar un dibujo uso el…',    ['Ratón', 'Teclado', 'Cable'], 'Ratón'),
            omp('Para oír una canción uso los…',     ['Parlantes', 'Botones', 'Cables'], 'Parlantes'),
            omp('Para ver el juego miro la…',        ['Pantalla', 'Silla', 'Mesa'], 'Pantalla'),
            omp('Para sacar el dibujo en papel uso la…', ['Impresora', 'Pantalla', 'Cámara'], 'Impresora'),
        ]),
    ],
],

[
    'slug'  => 'primero-esto-despues-aquello',
    'title' => 'Primero esto, después aquello',
    'description' => 'Las cosas se hacen en un orden. Si lo cambias, no funciona.',
    'objective' => 'Ordenar secuencias de acciones cotidianas reconociendo que el orden importa.',
    'icon' => '1️⃣', 'nivel' => 'preescolar', 'bloque' => 'pensar-como-programador',
    'duracion' => 8, 'tags' => ['secuencias', 'logica', 'tecnologia'],
    'estaciones' => [

        est('Lavarse las manos', 'Ordena los pasos', '🧼', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se lavan las manos',
            'items' => ['Abrir el agua', 'Mojarse las manos', 'Poner jabón', 'Frotar', 'Enjuagar', 'Cerrar el agua'],
        ]),

        est('Ponerse los zapatos', 'Ordena los pasos', '👟', 'ordenar_secuencia', [
            'title' => 'Ordena cómo te pones los zapatos',
            'items' => ['Sentarse', 'Ponerse las medias', 'Meter el pie en el zapato', 'Amarrar los cordones'],
        ]),

        est('¿Está bien el orden?', 'Algunos están al revés', '🔄', 'opcion_multiple', [
            omp('¿Qué va primero: abrir la puerta o salir?', ['Abrir la puerta', 'Salir'], 'Abrir la puerta', '🚪'),
            omp('¿Qué va primero: servir la sopa o sentarse?', ['Sentarse', 'Servir la sopa'], 'Sentarse', '🍲'),
            omp('¿Qué va primero: encender el computador o usarlo?', ['Encenderlo', 'Usarlo'], 'Encenderlo', '💻'),
            omp('¿Qué va primero: quitarse los zapatos o entrar a la piscina?', ['Quitarse los zapatos', 'Entrar a la piscina'], 'Quitarse los zapatos', '🏊'),
            omp('Si hago los pasos al revés, ¿qué pasa?', ['No funciona', 'Funciona igual', 'Funciona mejor'], 'No funciona'),
        ]),

        est('Dibujar una casa', 'Ordena los pasos', '🏠', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se dibuja una casa',
            'items' => ['Tomar el lápiz', 'Dibujar el cuadrado', 'Dibujar el techo', 'Dibujar la puerta', 'Pintar con colores'],
        ]),
    ],
],

[
    'slug'  => 'robots-que-obedecen',
    'title' => 'Robots que obedecen',
    'description' => 'Un robot no piensa: hace exactamente lo que se le dice, ni más ni menos.',
    'objective' => 'Comprender que una máquina ejecuta instrucciones literales, sin interpretarlas.',
    'icon' => '🦾', 'nivel' => 'preescolar', 'bloque' => 'pensar-como-programador',
    'duracion' => 9, 'tags' => ['logica', 'secuencias', 'tecnologia'],
    'estaciones' => [

        est('¿Qué hace el robot?', 'Hace justo lo que le dices', '🤖', 'opcion_multiple', [
            omp('Le digo «camina». ¿Qué hace?',            ['Camina', 'Corre', 'Se sienta'], 'Camina'),
            omp('Le digo «para». ¿Qué hace?',              ['Se detiene', 'Sigue', 'Salta'], 'Se detiene'),
            omp('No le digo nada. ¿Qué hace?',             ['Nada', 'Lo que quiera', 'Se apaga'], 'Nada'),
            omp('Le digo «salta» tres veces. ¿Cuántas veces salta?', ['Tres', 'Una', 'Ninguna'], 'Tres'),
            omp('¿El robot adivina lo que quiero?',        ['No, hay que decírselo', 'Sí', 'A veces'], 'No, hay que decírselo'),
        ]),

        est('Camino del robot', 'Ordena las órdenes', '🧭', 'ordenar_secuencia', [
            'title' => 'Ordena las órdenes para que el robot salga de la casa',
            'items' => ['Levantarse', 'Caminar hasta la puerta', 'Abrir la puerta', 'Salir'],
        ]),

        est('Órdenes claras', 'Una orden buena no se puede entender de dos formas', '💬', 'opcion_multiple', [
            omp('¿Cuál orden es más clara?', ['Da tres pasos', 'Ve para allá', 'Muévete un poco'], 'Da tres pasos'),
            omp('¿Cuál orden es más clara?', ['Toma el vaso rojo', 'Toma eso', 'Toma alguno'], 'Toma el vaso rojo'),
            omp('¿Cuál orden es más clara?', ['Gira a la derecha', 'Gira por ahí', 'Da la vuelta'], 'Gira a la derecha'),
            omp('Si la orden no es clara, el robot…', ['No sabe qué hacer', 'Adivina bien', 'Pregunta'], 'No sabe qué hacer'),
            omp('¿Quién le dice al robot qué hacer?', ['Una persona', 'Nadie', 'Otro robot siempre'], 'Una persona'),
        ]),

        est('Repetir y repetir', 'A veces se repite lo mismo', '🔁', 'opcion_multiple', [
            omp('«Aplaude 4 veces.» ¿Cuántos aplausos?',   ['4', '1', '8'], '4', '👏'),
            omp('«Da 2 pasos, 3 veces.» ¿Cuántos pasos en total?', ['6', '5', '2'], '6', '👣'),
            omp('¿Para qué sirve repetir una orden?',      ['Para no decirla muchas veces', 'Para confundir', 'Para nada'], 'Para no decirla muchas veces'),
            omp('«Salta hasta que suene el timbre.» ¿Cuándo para?', ['Cuando suene el timbre', 'Nunca', 'Enseguida'], 'Cuando suene el timbre', '🔔'),
            omp('Si no le digo cuándo parar, el robot…',   ['Sigue para siempre', 'Se cansa', 'Se apaga solo'], 'Sigue para siempre'),
        ]),
    ],
],

[
    'slug'  => 'la-pantalla-y-el-tiempo',
    'title' => 'La pantalla y el tiempo',
    'description' => 'Las pantallas son divertidas, y también hay que saber cuándo apagarlas.',
    'objective' => 'Reconocer hábitos saludables de uso de pantallas en la primera infancia.',
    'icon' => '⏰', 'nivel' => 'preescolar', 'bloque' => 'ciudadania-digital',
    'duracion' => 8, 'tags' => ['tecnologia', 'autocuidado', 'salud'],
    'estaciones' => [

        est('¿Cuándo apago?', 'Hay momentos para todo', '⏰', 'opcion_multiple', [
            omp('¿Veo pantalla mientras como?',            ['No', 'Sí', 'Siempre'], 'No', '🍽️'),
            omp('¿Veo pantalla justo antes de dormir?',    ['No, cuesta dormirse', 'Sí', 'Toda la noche'], 'No, cuesta dormirse', '🌙'),
            omp('Si me duelen los ojos, ¿qué hago?',       ['Descanso la vista', 'Sigo', 'Me acerco más'], 'Descanso la vista', '👀'),
            omp('¿Quién decide cuánto tiempo veo pantalla?', ['Un adulto conmigo', 'Yo solo', 'Nadie'], 'Un adulto conmigo'),
            omp('Cuando se acaba el tiempo, ¿qué hago?',   ['Apago sin pelear', 'Lloro', 'Me escondo'], 'Apago sin pelear'),
        ]),

        est('Jugar de muchas formas', 'La pantalla no es el único juego', '🎨', 'seleccion_imagenes',
            conTitulo('Toca los juegos que NO necesitan pantalla', 'Hay muchísimos', [
                ['e' => '⚽', 'n' => 'Fútbol',      'ok' => true],
                ['e' => '📱', 'n' => 'Celular',     'ok' => false],
                ['e' => '🧩', 'n' => 'Rompecabezas','ok' => true],
                ['e' => '📺', 'n' => 'Televisor',   'ok' => false],
                ['e' => '🎨', 'n' => 'Pintar',      'ok' => true],
                ['e' => '🚲', 'n' => 'Bicicleta',   'ok' => true],
                ['e' => '🎮', 'n' => 'Videojuego',  'ok' => false],
                ['e' => '📖', 'n' => 'Libro',       'ok' => true],
            ])),

        est('Cuidar los ojos y el cuerpo', 'Cómo se ve pantalla sin hacerse daño', '🪑', 'opcion_multiple', [
            omp('¿Me siento derecho o acostado?',   ['Derecho', 'Acostado', 'De cabeza'], 'Derecho', '🪑'),
            omp('¿La pantalla va muy cerca o lejos?', ['Un poco lejos', 'Pegada a la cara', 'Encima'], 'Un poco lejos'),
            omp('¿Con luz o a oscuras?',            ['Con luz', 'A oscuras', 'Da igual'], 'Con luz', '💡'),
            omp('Cada rato conviene…',              ['Levantarse y moverse', 'Quedarse quieto', 'Acercarse más'], 'Levantarse y moverse'),
            omp('Si la pantalla me pone bravo, ¿qué hago?', ['Descanso', 'Sigo enojado', 'Grito'], 'Descanso'),
        ]),

        est('Pedir permiso', 'Nada se descarga solo', '🙋', 'opcion_multiple', [
            omp('¿Puedo bajar un juego sin avisar?',  ['No, pido permiso', 'Sí', 'Si es gratis sí'], 'No, pido permiso'),
            omp('Si sale algo que no entiendo, ¿qué hago?', ['Llamo a un adulto', 'Toco todo', 'Lo escondo'], 'Llamo a un adulto'),
            omp('Si sale un dibujo que me asusta…',  ['Aviso y apago', 'Sigo mirando', 'Subo el volumen'], 'Aviso y apago'),
            omp('¿Le doy mi nombre a un juego?',     ['No sin un adulto', 'Sí', 'Y mi dirección'], 'No sin un adulto'),
            omp('¿Puedo hablar con desconocidos en un juego?', ['No', 'Sí', 'Si son simpáticos'], 'No'),
        ]),
    ],
],

[
    'slug'  => 'dibujos-en-la-pantalla',
    'title' => 'Dibujos en la pantalla',
    'description' => 'Los botones que aparecen en la pantalla y qué pasa al tocarlos.',
    'objective' => 'Identificar íconos digitales de uso frecuente y anticipar su efecto.',
    'icon' => '🔲', 'nivel' => 'preescolar', 'bloque' => 'como-funciona',
    'duracion' => 8, 'tags' => ['observacion', 'tecnologia', 'vocabulario'],
    'estaciones' => [

        est('¿Qué hace este botón?', 'Los dibujos dicen qué pasa', '🔲', 'opcion_multiple', [
            omp('El botón ▶️ sirve para…',   ['Empezar', 'Parar', 'Borrar'], 'Empezar', '▶️'),
            omp('El botón ⏸️ sirve para…',   ['Pausar', 'Empezar', 'Subir'], 'Pausar', '⏸️'),
            omp('El botón 🔊 sirve para…',   ['Oír', 'Ver', 'Escribir'], 'Oír', '🔊'),
            omp('El botón 🏠 lleva a…',      ['El inicio', 'El final', 'Otro juego'], 'El inicio', '🏠'),
            omp('El botón ❌ sirve para…',   ['Cerrar', 'Abrir', 'Guardar'], 'Cerrar', '❌'),
        ]),

        est('Une el botón con lo que hace', 'Dibujo y acción', '🔗', 'emparejar', [
            ['e' => '▶️', 'w' => 'Empezar'],
            ['e' => '⏸️', 'w' => 'Pausar'],
            ['e' => '🔊', 'w' => 'Sonido'],
            ['e' => '🏠', 'w' => 'Inicio'],
            ['e' => '🔍', 'w' => 'Buscar'],
            ['e' => '🗑️', 'w' => 'Borrar'],
        ]),

        est('Memoria de botones', 'Encuentra las parejas', '🧠', 'memoria',
            ['▶️', '⏸️', '🔊', '🏠', '🔍', '🗑️']),

        est('Cuidado con el bote de basura', 'Algunos botones no se deshacen', '🗑️', 'opcion_multiple', [
            omp('Si toco 🗑️, lo que borro…',      ['Se va', 'Se guarda', 'Se copia'], 'Se va'),
            omp('Antes de borrar algo conviene…', ['Preguntar', 'Borrar rápido', 'Cerrar los ojos'], 'Preguntar'),
            omp('Si borré algo sin querer, ¿qué hago?', ['Aviso a un adulto', 'Me quedo callado', 'Borro más'], 'Aviso a un adulto'),
            omp('El botón 💾 sirve para…',        ['Guardar', 'Borrar', 'Cerrar'], 'Guardar', '💾'),
            omp('¿Conviene guardar antes de cerrar?', ['Sí', 'No', 'Da igual'], 'Sí'),
        ]),
    ],
],

[
    'slug'  => 'de-donde-viene-lo-que-uso',
    'title' => '¿De dónde viene lo que uso?',
    'description' => 'La silla, el vaso, el lápiz: alguien los hizo, con un material y para algo.',
    'objective' => 'Reconocer objetos artificiales, su material y la necesidad que resuelven.',
    'icon' => '🪑', 'nivel' => 'preescolar', 'bloque' => 'como-funciona',
    'duracion' => 8, 'tags' => ['observacion', 'clasificacion', 'tecnologia'],
    'estaciones' => [

        est('¿Lo hizo una persona?', 'Natural o fabricado', '🏭', 'juego_rapido',
            conTitulo('¿Lo fabricó una persona?', 'Piensa si crece o se hace', [
                ['e' => '🪑', 'n' => 'Silla',   'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',   'ok' => false],
                ['e' => '👟', 'n' => 'Zapato',  'ok' => true],
                ['e' => '🪨', 'n' => 'Piedra',  'ok' => false],
                ['e' => '🚲', 'n' => 'Bicicleta','ok' => true],
                ['e' => '🌸', 'n' => 'Flor',    'ok' => false],
                ['e' => '🥤', 'n' => 'Vaso',    'ok' => true],
                ['e' => '☁️', 'n' => 'Nube',    'ok' => false],
            ])),

        est('¿De qué está hecho?', 'Cada cosa con su material', '🧱', 'opcion_multiple', [
            omp('Una ventana suele ser de…',  ['Vidrio', 'Pan', 'Agua'], 'Vidrio', '🪟'),
            omp('Un lápiz suele ser de…',     ['Madera', 'Vidrio', 'Tela'], 'Madera', '✏️'),
            omp('Una camiseta es de…',        ['Tela', 'Metal', 'Piedra'], 'Tela', '👕'),
            omp('Una cuchara suele ser de…',  ['Metal', 'Papel', 'Agua'], 'Metal', '🥄'),
            omp('Un cuaderno es de…',         ['Papel', 'Vidrio', 'Metal'], 'Papel', '📓'),
        ]),

        est('¿Para qué se inventó?', 'Cada invento resuelve algo', '💡', 'opcion_multiple', [
            omp('¿Para qué se inventó el paraguas?',  ['Para no mojarse', 'Para comer', 'Para dormir'], 'Para no mojarse', '☂️'),
            omp('¿Para qué se inventó la rueda?',     ['Para mover cosas', 'Para pintar', 'Para cocinar'], 'Para mover cosas', '🛞'),
            omp('¿Para qué se inventó la cuchara?',   ['Para comer sopa', 'Para escribir', 'Para correr'], 'Para comer sopa', '🥄'),
            omp('¿Para qué se inventó el zapato?',    ['Para cuidar el pie', 'Para ver', 'Para oír'], 'Para cuidar el pie', '👟'),
            omp('¿Para qué se inventó la bombilla?',  ['Para alumbrar de noche', 'Para enfriar', 'Para lavar'], 'Para alumbrar de noche', '💡'),
        ]),

        est('Une el objeto con su material', 'Dibujo y material', '🔗', 'emparejar', [
            ['e' => '✏️', 'w' => 'Madera'],
            ['e' => '🪟', 'w' => 'Vidrio'],
            ['e' => '👕', 'w' => 'Tela'],
            ['e' => '🥄', 'w' => 'Metal'],
            ['e' => '📓', 'w' => 'Papel'],
            ['e' => '🧱', 'w' => 'Ladrillo'],
        ]),
    ],
],


// =====================================================================
//  PRIMARIA INICIAL · empezar a manejarlo
// =====================================================================

[
    'slug'  => 'el-teclado-por-dentro',
    'title' => 'El teclado por dentro',
    'description' => 'La barra espaciadora, el Enter, el borrar: las teclas que más se usan.',
    'objective' => 'Identificar las teclas de función básica y su efecto al escribir.',
    'icon' => '⌨️', 'nivel' => 'primaria-inicial', 'bloque' => 'herramientas-digitales',
    'duracion' => 10, 'tags' => ['tecnologia', 'escritura', 'observacion'],
    'estaciones' => [

        est('¿Qué hace esta tecla?', 'Las que más se usan', '⌨️', 'opcion_multiple', [
            omp('La barra espaciadora sirve para…', ['Separar palabras', 'Borrar', 'Bajar'], 'Separar palabras'),
            omp('La tecla Enter sirve para…',       ['Bajar de renglón', 'Subir', 'Borrar todo'], 'Bajar de renglón'),
            omp('La tecla Retroceso sirve para…',   ['Borrar la letra anterior', 'Escribir', 'Guardar'], 'Borrar la letra anterior'),
            omp('La tecla Mayúsculas sirve para…',  ['Escribir en mayúscula', 'Borrar', 'Cerrar'], 'Escribir en mayúscula'),
            omp('Para escribir la Ñ uso…',          ['La tecla Ñ', 'La N dos veces', 'El ratón'], 'La tecla Ñ'),
        ]),

        est('Ordena para escribir una frase', 'Los pasos, en orden', '📝', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se escribe una frase en el computador',
            'items' => ['Abrir el programa de escribir', 'Hacer clic donde va el texto',
                        'Escribir las palabras', 'Poner el punto final', 'Guardar'],
        ]),

        est('Errores al escribir', 'Qué hacer cuando algo sale mal', '🔧', 'opcion_multiple', [
            omp('Escribí una letra de más. ¿Qué uso?',      ['Retroceso', 'Enter', 'Espacio'], 'Retroceso'),
            omp('Todo me sale en MAYÚSCULAS. Seguro dejé activada…', ['Bloq Mayús', 'Enter', 'La Ñ'], 'Bloq Mayús'),
            omp('Las palabras salen pegadas. Me falta…',    ['El espacio', 'El Enter', 'Borrar'], 'El espacio'),
            omp('Quiero empezar otro párrafo. Uso…',        ['Enter', 'Espacio', 'Retroceso'], 'Enter'),
            omp('Borré sin querer. Muchas veces se arregla con…', ['Deshacer', 'Apagar', 'Gritar'], 'Deshacer'),
        ]),

        est('Une la tecla con lo que hace', 'Tecla y efecto', '🔗', 'emparejar', [
            ['e' => '␣', 'w' => 'Separa palabras'],
            ['e' => '↩️', 'w' => 'Baja de renglón'],
            ['e' => '⌫', 'w' => 'Borra'],
            ['e' => '⇧', 'w' => 'Mayúscula'],
            ['e' => '💾', 'w' => 'Guarda'],
            ['e' => '↺', 'w' => 'Deshace'],
        ]),
    ],
],

[
    /*
     * `archivos-y-carpetas` ya existe y es de primaria media. Esta entra
     * antes: solo guardar con buen nombre y volver a encontrarlo.
     */
    'slug'  => 'guardar-y-encontrar',
    'title' => 'Guardar y encontrar',
    'description' => 'Guardar con un nombre que se entienda, y saber dónde quedó.',
    'objective' => 'Comprender la organización de archivos en carpetas y la importancia del nombre.',
    'icon' => '🗄️', 'nivel' => 'primaria-inicial', 'bloque' => 'herramientas-digitales',
    'duracion' => 10, 'tags' => ['tecnologia', 'clasificacion', 'organizacion'],
    'estaciones' => [

        est('¿Qué es una carpeta?', 'Un cajón dentro del computador', '📁', 'opcion_multiple', [
            omp('Una carpeta sirve para…',       ['Guardar archivos juntos', 'Borrar', 'Imprimir'], 'Guardar archivos juntos'),
            omp('¿Puede haber una carpeta dentro de otra?', ['Sí', 'No', 'Solo una vez'], 'Sí'),
            omp('Si guardo todo suelto, después…', ['No encuentro nada', 'Todo es más fácil', 'Va más rápido'], 'No encuentro nada'),
            omp('¿Qué nombre de carpeta es mejor?', ['Tareas de Ciencias', 'Cosas', 'aaaa'], 'Tareas de Ciencias'),
            omp('Si borro la carpeta, los archivos de dentro…', ['Se van también', 'Se quedan', 'Se copian'], 'Se van también'),
        ]),

        est('Buenos nombres', 'Un nombre que se entienda dentro de un mes', '🏷️', 'opcion_multiple', [
            omp('¿Cuál nombre es mejor para una tarea?', ['tarea-ciencias-plantas', 'sin titulo 3', 'ddd'], 'tarea-ciencias-plantas'),
            omp('¿Cuál nombre es mejor para una foto del paseo?', ['paseo-parque', 'IMG0012', 'x'], 'paseo-parque'),
            omp('¿Conviene poner la fecha en el nombre?', ['Sí, ayuda a ordenar', 'No', 'Nunca'], 'Sí, ayuda a ordenar'),
            omp('¿Por qué importa el nombre?',   ['Para encontrarlo después', 'Para que pese menos', 'Para nada'], 'Para encontrarlo después'),
            omp('«Documento (1) copia final FINAL» es un nombre…', ['Confuso', 'Perfecto', 'Corto'], 'Confuso'),
        ]),

        est('Ordena el escritorio', 'Cada archivo a su carpeta', '🗂️', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para guardar bien una tarea',
            'items' => ['Terminar la tarea', 'Tocar Guardar como', 'Elegir la carpeta de la materia',
                        'Escribir un nombre claro', 'Aceptar'],
        ]),

        est('¿Dónde lo guardo?', 'Cada cosa en su sitio', '📂', 'opcion_multiple', [
            omp('Una foto del paseo va en la carpeta…', ['Fotos', 'Tareas', 'Juegos'], 'Fotos'),
            omp('Un trabajo de Matemáticas va en…',     ['Tareas', 'Fotos', 'Música'], 'Tareas'),
            omp('Una canción va en…',                   ['Música', 'Tareas', 'Fotos'], 'Música'),
            omp('Si no sé dónde guardarlo, ¿qué hago?', ['Creo una carpeta con nombre claro', 'Lo dejo suelto', 'Lo borro'], 'Creo una carpeta con nombre claro'),
            omp('¿Para qué sirve buscar por nombre?',   ['Para encontrarlo rápido', 'Para borrarlo', 'Para copiarlo'], 'Para encontrarlo rápido'),
        ]),
    ],
],

[
    'slug'  => 'mi-primera-contrasena',
    'title' => 'Mi primera contraseña',
    'description' => 'Una contraseña es una llave. No se presta, no se deja pegada en la pantalla.',
    'objective' => 'Reconocer qué hace segura a una contraseña y por qué no se comparte.',
    'icon' => '🔐', 'nivel' => 'primaria-inicial', 'bloque' => 'ciudadania-digital',
    'duracion' => 10, 'tags' => ['tecnologia', 'seguridad', 'autocuidado'],
    'estaciones' => [

        est('¿Es una buena contraseña?', 'Unas se adivinan en un segundo', '🔐', 'opcion_multiple', [
            omp('¿Cuál es mejor contraseña?',      ['PerroAzul47', '123456', 'abc'], 'PerroAzul47'),
            omp('¿Es buena idea usar mi nombre?',  ['No, se adivina', 'Sí', 'Solo el apellido'], 'No, se adivina'),
            omp('¿Es buena idea usar mi fecha de nacimiento?', ['No', 'Sí', 'Si la escribo al revés sí'], 'No'),
            omp('Una buena contraseña es…',        ['Larga y difícil de adivinar', 'Corta', 'Igual en todo'], 'Larga y difícil de adivinar'),
            omp('¿Uso la misma contraseña en todo?', ['No', 'Sí', 'Solo en tres sitios'], 'No'),
        ]),

        est('¿A quién se la digo?', 'A casi nadie', '🤫', 'juego_rapido',
            conTitulo('¿Le puedo decir mi contraseña?', 'Piensa bien', [
                ['e' => '👨‍👩‍👧', 'n' => 'A mis papás',       'ok' => true],
                ['e' => '🧑‍🏫', 'n' => 'A mi profe',        'ok' => true],
                ['e' => '🧒', 'n' => 'A un compañero',      'ok' => false],
                ['e' => '👤', 'n' => 'A un desconocido',    'ok' => false],
                ['e' => '🎮', 'n' => 'A alguien de un juego','ok' => false],
                ['e' => '📝', 'n' => 'Pegada en la pantalla','ok' => false],
            ])),

        est('Si algo sale mal', 'Qué hacer y a quién avisar', '🚨', 'opcion_multiple', [
            omp('Creo que alguien sabe mi contraseña. ¿Qué hago?', ['La cambio y aviso', 'Nada', 'La digo a más gente'], 'La cambio y aviso'),
            omp('Alguien entró a mi cuenta. ¿A quién aviso?',      ['A un adulto de confianza', 'A nadie', 'Al que entró'], 'A un adulto de confianza'),
            omp('Un mensaje me pide mi contraseña. ¿Qué hago?',    ['No la doy y aviso', 'La doy', 'La doy si es urgente'], 'No la doy y aviso'),
            omp('¿Dónde NO se apunta una contraseña?',             ['En un papel pegado a la pantalla', 'En un sitio seguro', 'En la memoria'], 'En un papel pegado a la pantalla'),
            omp('Si se me olvida, ¿qué hago?',                     ['Pido ayuda para cambiarla', 'Uso la de otro', 'Me rindo'], 'Pido ayuda para cambiarla'),
        ]),

        est('Reto de la llave', 'Cinco preguntas para cerrar', '🏆', 'desafio_final', [
            reto('Una contraseña es como…',            ['Una llave', 'Un juguete', 'Un adorno'], 'Una llave'),
            reto('¿Cuál se adivina más rápido?',       ['1234', 'Mariposa9Roja', 'TigreVerde72'], '1234'),
            reto('Prestar la contraseña a un amigo es…', ['Mala idea', 'Buena idea', 'Obligatorio'], 'Mala idea'),
            reto('Si un juego pide mi dirección, ¿qué hago?', ['No la doy y aviso', 'La doy', 'La invento y sigo'], 'No la doy y aviso'),
            reto('Cerrar sesión al terminar es…',      ['Importante', 'Innecesario', 'Peligroso'], 'Importante'),
        ]),
    ],
],

[
    'slug'  => 'buscar-sin-perderse',
    'title' => 'Buscar sin perderse',
    'description' => 'Escribir bien lo que se busca, y mirar más de un resultado.',
    'objective' => 'Formular búsquedas simples y comparar resultados antes de darlos por buenos.',
    'icon' => '🔍', 'nivel' => 'primaria-inicial', 'bloque' => 'herramientas-digitales',
    'duracion' => 10, 'tags' => ['tecnologia', 'comprension', 'logica'],
    'estaciones' => [

        est('¿Qué escribo para buscar?', 'Palabras clave, no frases largas', '⌨️', 'opcion_multiple', [
            omp('Quiero saber qué comen las tortugas. Busco…', ['qué comen las tortugas', 'hola', 'tortuga bonita'], 'qué comen las tortugas'),
            omp('Quiero fotos de la Luna. Busco…',             ['fotos de la Luna', 'cosas', 'brillo'], 'fotos de la Luna'),
            omp('Si escribo mal la palabra, ¿qué pasa?',       ['Salen cosas que no busco', 'Sale lo mismo', 'No pasa nada'], 'Salen cosas que no busco'),
            omp('¿Conviene poner muchas palabras o pocas y claras?', ['Pocas y claras', 'Muchísimas', 'Una letra'], 'Pocas y claras'),
            omp('¿Miro solo el primer resultado?',             ['No, comparo varios', 'Sí', 'Solo el último'], 'No, comparo varios'),
        ]),

        est('¿Me lo creo?', 'No todo lo que sale es verdad', '🤔', 'opcion_multiple', [
            omp('Una página dice que los perros vuelan. ¿Me lo creo?', ['No', 'Sí', 'Depende del color'], 'No'),
            omp('Dos páginas dicen cosas distintas. ¿Qué hago?',       ['Busco una tercera', 'Creo la primera', 'Lo dejo'], 'Busco una tercera'),
            omp('¿Quién puede escribir en internet?',                  ['Cualquiera', 'Solo científicos', 'Solo profesores'], 'Cualquiera'),
            omp('Una página de un museo suele ser…',                   ['Más confiable', 'Menos confiable', 'Igual'], 'Más confiable'),
            omp('Si no entiendo algo, ¿a quién le pregunto?',          ['A un adulto o mi profe', 'A nadie', 'Lo copio igual'], 'A un adulto o mi profe'),
        ]),

        est('Ordena una búsqueda', 'Los pasos', '🧭', 'ordenar_secuencia', [
            'title' => 'Ordena cómo se busca algo bien',
            'items' => ['Pensar qué quiero saber', 'Escribir palabras clave', 'Mirar varios resultados',
                        'Comparar lo que dicen', 'Escribirlo con mis palabras'],
        ]),

        est('Copiar no es aprender', 'Con mis palabras', '✍️', 'opcion_multiple', [
            omp('Encontré el texto perfecto. ¿Lo copio tal cual?', ['Lo leo y lo escribo con mis palabras', 'Lo copio', 'Lo copio y cambio una coma'], 'Lo leo y lo escribo con mis palabras'),
            omp('Si uso una idea de otro, conviene…',   ['Decir de dónde salió', 'Callarlo', 'Borrarlo'], 'Decir de dónde salió'),
            omp('¿Por qué no se copia sin más?',        ['Porque así no se aprende', 'Porque es lento', 'Porque no cabe'], 'Porque así no se aprende'),
            omp('Una imagen de internet…',              ['Tiene dueño', 'Es de nadie', 'Es mía'], 'Tiene dueño'),
            omp('Lo mejor para recordar algo es…',      ['Explicarlo con mis palabras', 'Copiarlo', 'Imprimirlo'], 'Explicarlo con mis palabras'),
        ]),
    ],
],

[
    'slug'  => 'ordenes-que-se-repiten',
    'title' => 'Órdenes que se repiten',
    'description' => 'Un bucle es decir «haz esto cuatro veces» en vez de decirlo cuatro veces.',
    'objective' => 'Identificar repeticiones en una secuencia y expresarlas como un bucle.',
    'icon' => '🔂', 'nivel' => 'primaria-inicial', 'bloque' => 'pensar-como-programador',
    'duracion' => 11, 'tags' => ['logica', 'patrones', 'tecnologia'],
    'estaciones' => [

        est('¿Cuántas veces?', 'Contar repeticiones', '🔢', 'opcion_multiple', [
            omp('«Avanza 1, repite 5 veces.» ¿Cuánto avanza?',  ['5', '1', '10'], '5'),
            omp('«Gira 90°, repite 4 veces.» ¿Qué dibuja?',     ['Un cuadrado', 'Un círculo', 'Una línea'], 'Un cuadrado'),
            omp('«Salta 2, repite 3 veces.» ¿Cuántos saltos?',  ['6', '5', '3'], '6'),
            omp('«Avanza 3, gira, repite 3 veces.» ¿Qué dibuja?', ['Un triángulo', 'Un cuadrado', 'Un punto'], 'Un triángulo'),
            omp('Repetir sirve para…',                          ['Escribir menos órdenes', 'Confundir', 'Ir más lento'], 'Escribir menos órdenes'),
        ]),

        est('El patrón que se repite', 'Encuentra la regla', '🔁', 'secuencia_numerica', [
            serie([2, 4, 6, null, 10]),
            serie([5, 10, 15, null, 25]),
            serie([10, 20, 30, null, 50]),
            serie([3, 6, 9, null, 15]),
            serie([4, 8, 12, null, 20]),
        ]),

        est('Ordena el programa', 'Las órdenes van en orden', '📋', 'ordenar_secuencia', [
            'title' => 'Ordena el programa para dibujar un cuadrado',
            'items' => ['Poner el lápiz en el papel', 'Repetir 4 veces: avanzar y girar',
                        'Levantar el lápiz', 'Guardar el dibujo'],
        ]),

        est('¿Dónde está el error?', 'Un programa con un fallo', '🐞', 'opcion_multiple', [
            omp('El robot debía dar 3 pasos y dio 2. Falta…', ['Una repetición', 'Apagarlo', 'Otro robot'], 'Una repetición'),
            omp('El robot gira siempre y no para. Le falta…', ['Decirle cuándo parar', 'Más pilas', 'Otra orden'], 'Decirle cuándo parar'),
            omp('El dibujo salió torcido. Seguramente…',      ['Un giro está mal', 'El papel está mal', 'Es normal'], 'Un giro está mal'),
            omp('¿Cómo encuentro el error?',                  ['Sigo las órdenes una a una', 'Empiezo de cero', 'Adivino'], 'Sigo las órdenes una a una'),
            omp('A un error en un programa se le dice…',      ['Bug', 'Premio', 'Final'], 'Bug'),
        ]),
    ],
],


// =====================================================================
//  PRIMARIA SUPERIOR · desconfiar con criterio
// =====================================================================

[
    'slug'  => 'lo-que-dejo-escrito',
    'title' => 'Lo que dejo escrito',
    'description' => 'Huella digital: lo que subes hoy puede leerlo alguien dentro de diez años.',
    'objective' => 'Analizar la permanencia de la información publicada y sus consecuencias futuras.',
    'icon' => '🪞', 'nivel' => 'primaria-superior', 'bloque' => 'ciudadania-digital',
    'duracion' => 14, 'tags' => ['tecnologia', 'ciudadania', 'seguridad'],
    'estaciones' => [

        est('¿Se borra de verdad?', 'Lo que sube casi nunca desaparece', '♻️', 'opcion_multiple', [
            omp('Borro una foto de una red. ¿Desaparece de todas partes?', ['No, alguien pudo guardarla', 'Sí', 'Solo si la borro rápido'], 'No, alguien pudo guardarla'),
            omp('Un mensaje «que se borra solo» se puede…',  ['Fotografiar con otra pantalla', 'Recuperar nunca', 'Olvidar'], 'Fotografiar con otra pantalla'),
            omp('¿Quién puede ver lo que publico en abierto?', ['Cualquiera', 'Solo mis amigos', 'Nadie'], 'Cualquiera'),
            omp('Antes de publicar conviene preguntarse…',    ['¿Me molestaría que lo viera cualquiera?', '¿Tengo prisa?', '¿Está de moda?'], '¿Me molestaría que lo viera cualquiera?'),
            omp('«Huella digital» significa…',                ['El rastro que dejo en internet', 'Mi dedo', 'Mi contraseña'], 'El rastro que dejo en internet'),
        ]),

        est('¿Esto se publica?', 'Decide qué es privado', '🔒', 'juego_rapido',
            conTitulo('¿Es seguro publicarlo en abierto?', 'Piensa quién más lo va a leer', [
                ['e' => '🏫', 'n' => 'Mi colegio y horario', 'ok' => false],
                ['e' => '🎨', 'n' => 'Un dibujo mío',        'ok' => true],
                ['e' => '🏠', 'n' => 'Mi dirección',         'ok' => false],
                ['e' => '📞', 'n' => 'Mi teléfono',          'ok' => false],
                ['e' => '⚽', 'n' => 'Mi equipo favorito',   'ok' => true],
                ['e' => '🔑', 'n' => 'Mi contraseña',        'ok' => false],
                ['e' => '📚', 'n' => 'Un libro que me gustó','ok' => true],
                ['e' => '✈️', 'n' => 'Que estaré de viaje',  'ok' => false],
            ])),

        est('Foto de otro', 'La cara de alguien no es mía para publicarla', '📸', 'opcion_multiple', [
            omp('Tengo una foto graciosa de un compañero. ¿La subo?', ['No sin su permiso', 'Sí', 'Si es graciosa sí'], 'No sin su permiso'),
            omp('Me piden borrar una foto donde salgo con otro. ¿Qué hago?', ['La borro', 'Discuto', 'Subo otra'], 'La borro'),
            omp('Reenviar una foto privada de alguien es…', ['Grave, aunque no la hiciera yo', 'Normal', 'Solo un juego'], 'Grave, aunque no la hiciera yo'),
            omp('¿Qué hago si veo que reenvían algo así?',  ['No lo reenvío y aviso a un adulto', 'Lo reenvío', 'Me río'], 'No lo reenvío y aviso a un adulto'),
            omp('El permiso para publicar una foto lo da…', ['Quien sale en ella', 'Quien la tomó siempre', 'Cualquiera'], 'Quien sale en ella'),
        ]),

        est('Reto de la huella', 'Cinco preguntas para cerrar', '🏆', 'desafio_final', [
            reto('Lo que publico en abierto lo puede ver…',   ['Cualquiera, ahora y después', 'Solo hoy', 'Solo mis amigos'], 'Cualquiera, ahora y después'),
            reto('Publicar la foto de otro sin permiso es…',  ['No se hace', 'Normal', 'Obligatorio'], 'No se hace'),
            reto('«Esto se borra en 24 horas» significa que…', ['Alguien pudo guardarlo antes', 'Desaparece del mundo', 'Nadie lo vio'], 'Alguien pudo guardarlo antes'),
            reto('Decir dónde estoy en tiempo real es…',      ['Arriesgado', 'Recomendable', 'Divertido y seguro'], 'Arriesgado'),
            reto('Si algo en línea me hace sentir mal, lo primero es…', ['Contárselo a un adulto', 'Responder enojado', 'Callarme'], 'Contárselo a un adulto'),
        ]),
    ],
],

[
    'slug'  => 'no-todo-es-verdad',
    'title' => 'No todo lo que veo es verdad',
    'description' => 'Fotos retocadas, titulares que exageran y noticias inventadas: cómo comprobar.',
    'objective' => 'Aplicar criterios de verificación básicos a información digital.',
    'icon' => '🕵️', 'nivel' => 'primaria-superior', 'bloque' => 'ciudadania-digital',
    'duracion' => 15, 'tags' => ['tecnologia', 'deduccion', 'comprension'],
    'estaciones' => [

        est('Señales de alarma', 'Qué hace sospechosa a una noticia', '🚩', 'opcion_multiple', [
            omp('Un titular en MAYÚSCULAS con muchos signos suele ser…', ['Sospechoso', 'Más verdadero', 'Oficial'], 'Sospechoso'),
            omp('Una noticia sin fecha ni autor es…',        ['Menos confiable', 'Más confiable', 'Igual'], 'Menos confiable'),
            omp('«Los médicos no quieren que sepas esto» es…', ['Un gancho', 'Un dato', 'Una fuente'], 'Un gancho'),
            omp('Si una noticia me da mucha rabia enseguida…', ['Desconfío y compruebo', 'La reenvío', 'La creo'], 'Desconfío y compruebo'),
            omp('¿Qué hago antes de reenviar algo?',          ['Comprobarlo en otra fuente', 'Reenviarlo rápido', 'Cambiarle el título'], 'Comprobarlo en otra fuente'),
        ]),

        est('Hecho u opinión', 'Se comprueba o no se comprueba', '⚖️', 'juego_rapido',
            conTitulo('¿Es un HECHO comprobable?', 'Una opinión no se puede comprobar', [
                ['e' => '🌡️', 'n' => 'El agua hierve a 100 °C', 'ok' => true],
                ['e' => '🍕', 'n' => 'La pizza es lo más rico',  'ok' => false],
                ['e' => '🇨🇴', 'n' => 'Bogotá es la capital',    'ok' => true],
                ['e' => '🎵', 'n' => 'Esa canción es horrible',  'ok' => false],
                ['e' => '🌙', 'n' => 'La Luna gira alrededor de la Tierra', 'ok' => true],
                ['e' => '⚽', 'n' => 'El fútbol es aburrido',    'ok' => false],
                ['e' => '📏', 'n' => 'Un metro tiene 100 cm',    'ok' => true],
                ['e' => '🎨', 'n' => 'El azul es el mejor color','ok' => false],
            ])),

        est('Comprobar una imagen', 'Las fotos también mienten', '🖼️', 'opcion_multiple', [
            omp('Una foto puede estar…',                    ['Retocada o sacada de contexto', 'Siempre intacta', 'Siempre falsa'], 'Retocada o sacada de contexto'),
            omp('Veo una foto impactante. Lo primero es…',  ['Buscar si otros medios la publican', 'Reenviarla', 'Guardarla'], 'Buscar si otros medios la publican'),
            omp('Una foto vieja usada como noticia de hoy es…', ['Sacada de contexto', 'Correcta', 'Un error del papel'], 'Sacada de contexto'),
            omp('Una imagen hecha por computador puede parecer…', ['Una foto real', 'Siempre un dibujo', 'Un texto'], 'Una foto real'),
            omp('La mejor defensa es…',                     ['Comprobar en varias fuentes', 'Creer al primero', 'No leer nada'], 'Comprobar en varias fuentes'),
        ]),

        est('Ordena la comprobación', 'Antes de creer y de reenviar', '🔎', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para comprobar una noticia',
            'items' => ['Leerla entera, no solo el titular', 'Mirar quién la publica y cuándo',
                        'Buscar la misma noticia en otro medio', 'Comparar lo que dicen',
                        'Decidir si se cree y si se reenvía'],
        ]),
    ],
],

[
    'slug'  => 'la-hoja-de-calculo',
    'title' => 'La hoja de cálculo',
    'description' => 'Filas, columnas y fórmulas: hacer que el computador saque las cuentas.',
    'objective' => 'Reconocer la estructura de una hoja de cálculo y el uso de fórmulas simples.',
    'icon' => '🧾', 'nivel' => 'primaria-superior', 'bloque' => 'herramientas-digitales',
    'duracion' => 14, 'tags' => ['tecnologia', 'calculo', 'logica'],
    'estaciones' => [

        est('Filas, columnas y celdas', 'Cómo se llama cada cosa', '🔲', 'opcion_multiple', [
            omp('Las que van de lado a lado son…',        ['Filas', 'Columnas', 'Celdas'], 'Filas'),
            omp('Las que van de arriba abajo son…',       ['Columnas', 'Filas', 'Hojas'], 'Columnas'),
            omp('El cuadrito donde se escribe es la…',    ['Celda', 'Fila', 'Hoja'], 'Celda'),
            omp('La celda B3 está en la columna…',        ['B', '3', 'B3'], 'B'),
            omp('¿Para qué sirve una hoja de cálculo?',   ['Organizar datos y calcular', 'Dibujar', 'Oír música'], 'Organizar datos y calcular'),
        ]),

        est('Fórmulas que calculan solas', 'El computador hace la cuenta', '🧮', 'opcion_multiple', [
            omp('Toda fórmula empieza por…',              ['=', '+', 'Un punto'], '='),
            omp('«=2+3» da como resultado…',              ['5', '23', '=2+3'], '5'),
            omp('«=SUMA(A1:A5)» suma…',                   ['De A1 hasta A5', 'Solo A1 y A5', 'Nada'], 'De A1 hasta A5'),
            omp('Si cambio un número, el resultado…',     ['Se actualiza solo', 'Se queda igual', 'Se borra'], 'Se actualiza solo'),
            omp('¿Para qué sirve el PROMEDIO?',           ['Saber el valor típico', 'Sumar todo', 'Contar'], 'Saber el valor típico'),
        ]),

        est('Leer una tabla', 'Los datos cuentan algo', '📋', 'opcion_multiple', [
            omp('Lunes 4, martes 7, miércoles 2. ¿Cuál fue el mejor día?', ['Martes', 'Lunes', 'Miércoles'], 'Martes'),
            omp('¿Cuántos en total esos tres días?',      ['13', '11', '14'], '13'),
            omp('¿Cuál es el promedio de 4, 7 y 2… aproximado?', ['Algo más de 4', '7', '2'], 'Algo más de 4'),
            omp('Una gráfica de barras sirve para…',      ['Comparar de un vistazo', 'Escribir', 'Guardar'], 'Comparar de un vistazo'),
            omp('Si la tabla no tiene títulos…',          ['No se entiende qué es cada número', 'Se entiende igual', 'Es mejor'], 'No se entiende qué es cada número'),
        ]),

        est('Ordena el trabajo con datos', 'De la pregunta a la respuesta', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para trabajar con datos',
            'items' => ['Decidir qué quiero averiguar', 'Recoger los datos',
                        'Escribirlos en la tabla con títulos', 'Calcular con una fórmula',
                        'Hacer una gráfica', 'Explicar qué se ve'],
        ]),
    ],
],

[
    'slug'  => 'presentar-sin-aburrir',
    'title' => 'Presentar sin aburrir',
    'description' => 'Una diapositiva no es un texto para leer en voz alta: es un apoyo.',
    'objective' => 'Aplicar criterios básicos de diseño y oratoria a una presentación digital.',
    'icon' => '📽️', 'nivel' => 'primaria-superior', 'bloque' => 'herramientas-digitales',
    'duracion' => 13, 'tags' => ['tecnologia', 'comprension', 'escritura'],
    'estaciones' => [

        est('¿Qué va en una diapositiva?', 'Poco texto, grande', '📝', 'opcion_multiple', [
            omp('¿Cuánto texto va en una diapositiva?',    ['Poco y grande', 'Todo lo que voy a decir', 'Nada nunca'], 'Poco y grande'),
            omp('Leer la diapositiva palabra por palabra es…', ['Aburrido', 'Lo correcto', 'Obligatorio'], 'Aburrido'),
            omp('Una imagen sirve para…',                  ['Ayudar a entender', 'Rellenar', 'Ocupar espacio'], 'Ayudar a entender'),
            omp('Muchos colores y animaciones…',           ['Distraen', 'Ayudan siempre', 'Son obligatorios'], 'Distraen'),
            omp('El título de una diapositiva debe…',      ['Decir de qué trata', 'Ser largo', 'Estar en clave'], 'Decir de qué trata'),
        ]),

        est('Ordena la presentación', 'Tiene principio, medio y final', '🎬', 'ordenar_secuencia', [
            'title' => 'Ordena las partes de una presentación',
            'items' => ['Portada con el tema y mi nombre', 'Decir de qué voy a hablar',
                        'Explicar las ideas principales', 'Poner un ejemplo',
                        'Cerrar con la conclusión', 'Preguntas'],
        ]),

        est('Hablar en público', 'Lo que se practica sale bien', '🎤', 'opcion_multiple', [
            omp('¿Conviene ensayar antes?',            ['Sí, en voz alta', 'No', 'Solo mentalmente'], 'Sí, en voz alta'),
            omp('¿Miro a la pantalla o al público?',   ['Al público', 'A la pantalla', 'Al suelo'], 'Al público'),
            omp('Si me equivoco, ¿qué hago?',          ['Sigo con calma', 'Empiezo de cero', 'Me callo'], 'Sigo con calma'),
            omp('La voz debe ser…',                    ['Clara y sin prisa', 'Muy rápida', 'Muy bajita'], 'Clara y sin prisa'),
            omp('¿Cuántas ideas por diapositiva?',     ['Una idea principal', 'Todas', 'Ninguna'], 'Una idea principal'),
        ]),

        est('Reto de la presentación', 'Cinco preguntas para cerrar', '🏆', 'desafio_final', [
            reto('Una diapositiva con un párrafo entero es…', ['Un error común', 'Lo ideal', 'Obligatorio'], 'Un error común'),
            reto('La letra pequeña en el fondo del salón…',   ['No se lee', 'Se lee mejor', 'Da igual'], 'No se lee'),
            reto('Copiar imágenes sin decir de dónde son…',   ['Está mal', 'Está bien', 'Es lo normal'], 'Está mal'),
            reto('Lo primero que hay que tener claro es…',    ['Qué quiero que entiendan', 'El color de fondo', 'La animación'], 'Qué quiero que entiendan'),
            reto('Ensayar sirve para…',                       ['Controlar el tiempo y los nervios', 'Perder el tiempo', 'Nada'], 'Controlar el tiempo y los nervios'),
        ]),
    ],
],

],
];
