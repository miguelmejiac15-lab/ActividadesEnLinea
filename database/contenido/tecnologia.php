<?php
/**
 * tecnologia.php — Tecnología
 *
 * Tres bloques que responden tres preguntas distintas: cómo funciona una
 * máquina, cómo se le dan órdenes, y cómo cuidarse cuando se usa.
 *
 * El tercer bloque se cruza a propósito con «Vida y Bienestar», pero no
 * lo repite: allí se trabaja qué hacer cuando algo te incomoda en línea
 * —una cuestión emocional y de pedir ayuda—, y aquí cómo funcionan de
 * verdad una contraseña o un rastro digital.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'tecnologia',
    'name'       => 'Tecnología',
    'tagline'    => 'Cómo funcionan las máquinas, cómo se les habla y cómo cuidarse',
    'icon'       => '💻',
    'color'      => '#4caf50',
    'sort_order' => 4,
],

'bloques' => [
    ['slug' => 'como-funciona', 'name' => 'Cómo Funciona', 'icon' => '🔌', 'sort_order' => 1,
     'description' => 'Las partes de una máquina y qué hace cada una.'],
    ['slug' => 'pensar-como-programador', 'name' => 'Pensar como Programador', 'icon' => '🤖', 'sort_order' => 2,
     'description' => 'Instrucciones, orden, repeticiones y errores.'],
    ['slug' => 'internet-seguro', 'name' => 'Internet Seguro', 'icon' => '🛡️', 'sort_order' => 3,
     'description' => 'Contraseñas, huella digital y qué se comparte y qué no.'],
],

'reasignar' => [
    'la-neo-computadora'  => 'como-funciona',
    'torre-de-circuitos'  => 'como-funciona',
    'ciber-codigo-robot'  => 'pensar-como-programador',
    'detective-digital'   => 'internet-seguro',
],

'actividades' => [


// ── CÓMO FUNCIONA ────────────────────────────────────────────────────

[
    'slug'  => 'maquinas-de-todos-los-dias',
    'title' => 'Máquinas de todos los días',
    'description' => 'La nevera, el bus, el celular: para qué sirve cada máquina que te rodea.',
    'objective' => 'Reconocer máquinas cotidianas y relacionarlas con la tarea que resuelven.',
    'icon' => '🔧', 'nivel' => 'preescolar', 'bloque' => 'como-funciona',
    'duracion' => 10, 'tags' => ['observacion', 'clasificacion', 'vocabulario'],
    'estaciones' => [

        est('¿Para qué sirve?', 'Cada máquina resuelve algo', '🔧', 'opcion_multiple', [
            omp('¿Para qué sirve la nevera?',   ['Mantener la comida fría', 'Calentar la casa', 'Lavar ropa'], 'Mantener la comida fría', '🧊'),
            omp('¿Para qué sirve la lavadora?', ['Lavar la ropa', 'Cocinar', 'Ver videos'], 'Lavar la ropa', '🫧'),
            omp('¿Para qué sirve el reloj?',    ['Ver la hora', 'Llamar', 'Cocinar'], 'Ver la hora', '⏰'),
            omp('¿Para qué sirve la licuadora?', ['Triturar alimentos', 'Planchar', 'Barrer'], 'Triturar alimentos', '🥤'),
            omp('¿Para qué sirve el ventilador?', ['Mover el aire', 'Guardar comida', 'Escribir'], 'Mover el aire', '💨'),
        ]),

        est('¿Con qué funciona?', 'Las máquinas necesitan energía', '⚡', 'opcion_multiple', [
            omp('La nevera funciona con…',    ['Electricidad', 'Agua', 'Viento'], 'Electricidad', '🔌'),
            omp('La bicicleta funciona con…', ['La fuerza de tus piernas', 'Electricidad', 'Gasolina'], 'La fuerza de tus piernas', '🚲'),
            omp('Un molino de viento funciona con…', ['Viento', 'Gasolina', 'Pilas'], 'Viento', '🌬️'),
            omp('Una linterna funciona con…', ['Pilas', 'Agua', 'Viento'], 'Pilas', '🔦'),
            omp('Un panel solar funciona con…', ['El sol', 'La lluvia', 'El frío'], 'El sol', '☀️'),
        ]),

        est('Toca las máquinas', 'Solo lo que sea una máquina', '⚙️', 'seleccion_imagenes',
            conTitulo('Toca todas las máquinas', 'Deja fuera lo que no lo sea', [
                ['e' => '🚗', 'n' => 'Carro',     'ok' => true],
                ['e' => '💻', 'n' => 'Computador', 'ok' => true],
                ['e' => '⏰', 'n' => 'Reloj',      'ok' => true],
                ['e' => '🚲', 'n' => 'Bicicleta',  'ok' => true],
                ['e' => '🌳', 'n' => 'Árbol',      'ok' => false],
                ['e' => '🍎', 'n' => 'Manzana',    'ok' => false],
                ['e' => '🐶', 'n' => 'Perro',      'ok' => false],
                ['e' => '☁️', 'n' => 'Nube',       'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'partes-del-computador',
    'title' => 'Partes del computador',
    'description' => 'Qué es cada pieza, cuál sirve para meter datos y cuál para sacarlos.',
    'objective' => 'Nombrar las partes de un computador y distinguir dispositivos de entrada y de salida.',
    'icon' => '🖥️', 'nivel' => 'primaria-inicial', 'bloque' => 'como-funciona',
    'duracion' => 12, 'tags' => ['tecnologia', 'clasificacion', 'vocabulario'],
    'estaciones' => [

        est('¿Cómo se llama?', 'Cada parte tiene su nombre', '🖱️', 'opcion_multiple', [
            omp('¿Cómo se llama esta parte?', ['Pantalla', 'Teclado', 'Impresora'], 'Pantalla', '🖥️'),
            omp('¿Cómo se llama esta parte?', ['Teclado', 'Ratón', 'Parlante'], 'Teclado', '⌨️'),
            omp('¿Cómo se llama esta parte?', ['Ratón', 'Teclado', 'Cámara'], 'Ratón', '🖱️'),
            omp('¿Cómo se llama esta parte?', ['Impresora', 'Pantalla', 'Teclado'], 'Impresora', '🖨️'),
            omp('¿Cómo se llama esta parte?', ['Parlantes', 'Ratón', 'Cámara'], 'Parlantes', '🔊'),
        ]),

        est('Entrada o salida', 'Unos meten información y otros la sacan', '↔️', 'opcion_multiple', [
            omp('El teclado es un dispositivo de…',  ['Entrada', 'Salida'], 'Entrada', '⌨️'),
            omp('La pantalla es un dispositivo de…', ['Salida', 'Entrada'], 'Salida', '🖥️'),
            omp('El ratón es un dispositivo de…',    ['Entrada', 'Salida'], 'Entrada', '🖱️'),
            omp('La impresora es un dispositivo de…', ['Salida', 'Entrada'], 'Salida', '🖨️'),
            omp('El micrófono es un dispositivo de…', ['Entrada', 'Salida'], 'Entrada', '🎤'),
            omp('Los parlantes son dispositivos de…', ['Salida', 'Entrada'], 'Salida', '🔊'),
        ]),

        est('Por dentro', 'Lo que no se ve también trabaja', '🧠', 'opcion_multiple', [
            omp('¿Qué parte «piensa» y hace los cálculos?',   ['El procesador', 'La pantalla', 'El cable'], 'El procesador', '🧠'),
            omp('¿Dónde se guardan los archivos?',            ['En el disco', 'En la pantalla', 'En el ratón'], 'En el disco', '💾'),
            omp('Los programas que usas se llaman…',          ['Software', 'Hardware', 'Cables'], 'Software', '📀'),
            omp('Las piezas que puedes tocar se llaman…',     ['Hardware', 'Software', 'Internet'], 'Hardware', '🔩'),
            omp('Si apagas el computador de golpe, puedes…',  ['Perder lo que no guardaste', 'Ganar espacio', 'Nada'], 'Perder lo que no guardaste', '⚠️'),
        ]),

        est('Une parte y función', 'Empareja cada pieza', '🔗', 'emparejar', [
            ['e' => '⌨️', 'w' => 'Escribir'],
            ['e' => '🖱️', 'w' => 'Señalar'],
            ['e' => '🖥️', 'w' => 'Mostrar'],
            ['e' => '🖨️', 'w' => 'Imprimir'],
            ['e' => '🔊', 'w' => 'Sonar'],
            ['e' => '💾', 'w' => 'Guardar'],
        ]),
    ],
],


// ── PENSAR COMO PROGRAMADOR ──────────────────────────────────────────

[
    'slug'  => 'mi-primer-algoritmo',
    'title' => 'Mi primer algoritmo',
    'description' => 'Una máquina hace exactamente lo que le dices, ni más ni menos.',
    'objective' => 'Escribir y ordenar secuencias de instrucciones precisas.',
    'icon' => '📋', 'nivel' => 'primaria-inicial', 'bloque' => 'pensar-como-programador',
    'duracion' => 12, 'tags' => ['logica', 'secuencias', 'tecnologia'],
    'estaciones' => [

        est('Instrucciones exactas', 'La máquina no adivina', '🤖', 'opcion_multiple', [
            omp('Le dices a un robot «trae agua». Él pregunta «¿de dónde?». Eso pasa porque…',
                ['La instrucción no era precisa', 'El robot es tonto', 'El agua no existe'], 'La instrucción no era precisa', '🤖'),
            omp('Un algoritmo es…',   ['Una lista de pasos en orden', 'Un número', 'Un cable'], 'Una lista de pasos en orden', '📋'),
            omp('Si dos pasos están al revés, el resultado…', ['Puede salir mal', 'Es igual', 'Es mejor'], 'Puede salir mal', '🔀'),
            omp('¿Cuál es una instrucción precisa?', ['Avanza 3 pasos', 'Ve por ahí', 'Muévete un poco'], 'Avanza 3 pasos', '➡️'),
            omp('Antes de escribir el algoritmo conviene…', ['Saber qué quiero lograr', 'Empezar sin pensar', 'Borrar todo'], 'Saber qué quiero lograr', '🎯'),
        ]),

        est('El camino del robot', 'Ordena las instrucciones', '🧭', 'ordenar_secuencia', [
            'title' => 'Ordena las instrucciones para que el robot salga de casa',
            'items' => [
                '1️⃣ Levantarse de la silla',
                '2️⃣ Caminar hasta la puerta',
                '3️⃣ Girar la manija',
                '4️⃣ Abrir la puerta',
                '5️⃣ Salir',
                '6️⃣ Cerrar la puerta',
            ],
        ]),

        est('Encuentra el error', 'Un paso está mal puesto', '🐞', 'opcion_multiple', [
            omp('«Sirve el jugo · toma el vaso · bebe». ¿Qué está mal?',
                ['Sirve antes de tomar el vaso', 'Beber al final', 'Nada'], 'Sirve antes de tomar el vaso', '🥤'),
            omp('«Ponte los zapatos · ponte las medias». ¿Qué está mal?',
                ['Los zapatos van después de las medias', 'Nada', 'Faltan los zapatos'], 'Los zapatos van después de las medias', '🧦'),
            omp('«Apaga el horno · mete el pan · enciende el horno». ¿Qué falta?',
                ['Sacar el pan al final', 'Nada', 'Otro horno'], 'Sacar el pan al final', '🍞'),
            omp('Cuando un programa hace algo raro, decimos que tiene un…',
                ['Error', 'Premio', 'Color'], 'Error', '🐞'),
        ]),
    ],
],

[
    'slug'  => 'bucles-y-repeticiones',
    'title' => 'Bucles y repeticiones',
    'description' => 'Cuando algo se repite, no se escribe cien veces: se usa un bucle.',
    'objective' => 'Reconocer patrones repetitivos y expresarlos como bucles con condición.',
    'icon' => '🔁', 'nivel' => 'primaria-media', 'bloque' => 'pensar-como-programador',
    'duracion' => 15, 'tags' => ['logica', 'patrones', 'tecnologia', 'reto'],
    'estaciones' => [

        est('¿Qué es un bucle?', 'Repetir sin repetirse', '🔁', 'opcion_multiple', [
            omp('Un bucle sirve para…',   ['Repetir instrucciones', 'Borrar el programa', 'Cambiar el color'], 'Repetir instrucciones', '🔁'),
            omp('«Avanza» cuatro veces se escribe mejor como…', ['Repite 4 veces: avanza', 'Avanza avanza avanza avanza', 'Avanza mucho'], 'Repite 4 veces: avanza', '➡️'),
            omp('«Repite 3 veces: avanza, gira». ¿Cuántas instrucciones se ejecutan?', [3, 5, 6, 9], 6),
            omp('Un bucle que nunca termina se llama…', ['Bucle infinito', 'Bucle corto', 'Bucle feliz'], 'Bucle infinito', '♾️'),
            omp('Para dibujar un cuadrado con un robot: «repite 4 veces: avanza y gira…»', ['90 grados', '45 grados', '180 grados'], '90 grados', '⬜'),
        ]),

        est('Condiciones', 'Hacer algo solo si pasa algo', '❓', 'opcion_multiple', [
            omp('«SI llueve ENTONCES lleva paraguas». Si no llueve…', ['No lleva paraguas', 'Lleva paraguas', 'Se moja'], 'No lleva paraguas', '☂️'),
            omp('«SI la nota es mayor que 3 ENTONCES aprobó». Con nota 2…', ['No aprobó', 'Aprobó', 'No se sabe'], 'No aprobó', '📝'),
            omp('«MIENTRAS haya obstáculo: gira». El robot gira hasta que…', ['No haya obstáculo', 'Se apague', 'Siempre'], 'No haya obstáculo', '🤖'),
            omp('Una condición devuelve…', ['Verdadero o falso', 'Un color', 'Un sonido'], 'Verdadero o falso', '⚖️'),
        ]),

        est('Cuenta las repeticiones', 'Haz el cálculo mentalmente', '🔢', 'opcion_multiple', [
            omp('«Repite 5 veces: avanza 2 pasos». ¿Cuántos pasos en total?', [7, 10, 12, 25], 10),
            omp('«Repite 3 veces: repite 2 veces: salta». ¿Cuántos saltos?', [5, 6, 8, 9], 6),
            omp('«Repite 10 veces: suma 1» empezando en 0. ¿Cuánto queda?', [1, 9, 10, 11], 10),
            omp('«Repite 4 veces: avanza y gira 90°». ¿Qué figura dibuja?', ['Un cuadrado', 'Un triángulo', 'Un círculo'], 'Un cuadrado', '⬜'),
        ]),

        est('Reto del programador', 'Piensa como una máquina', '🏆', 'desafio_final', [
            reto('¿Cuántos lados tiene la figura de «repite 3 veces: avanza y gira 120°»?',
                 [3, 4, 5], 3),
            reto('Un programa se repite para siempre y no responde. Probablemente sea…',
                 ['Un bucle infinito', 'Falta de tinta', 'Poco volumen'], 'Un bucle infinito'),
            reto('¿Qué hace más fácil corregir un programa largo?',
                 ['Dividirlo en partes pequeñas', 'Escribirlo todo seguido', 'Borrarlo'],
                 'Dividirlo en partes pequeñas'),
            reto('Si un algoritmo funciona con 3 datos pero falla con 300, el problema es de…',
                 ['Eficiencia', 'Color', 'Ortografía'], 'Eficiencia'),
        ]),
    ],
],


// ── INTERNET SEGURO ──────────────────────────────────────────────────

[
    'slug'  => 'contrasenas-y-privacidad',
    'title' => 'Contraseñas y privacidad',
    'description' => 'Qué hace fuerte a una contraseña y por qué no se prestan.',
    'objective' => 'Distinguir contraseñas seguras de inseguras y comprender qué protege cada una.',
    'icon' => '🔑', 'nivel' => 'primaria-media', 'bloque' => 'internet-seguro',
    'duracion' => 12, 'tags' => ['seguridad', 'tecnologia', 'logica'],
    'estaciones' => [

        est('¿Fuerte o débil?', 'Mira bien cada una', '🔐', 'opcion_multiple', [
            omp('«123456» es una contraseña…',        ['Débil', 'Fuerte'], 'Débil', '🔓'),
            omp('«miNombre2016» es una contraseña…',  ['Débil', 'Fuerte'], 'Débil', '🔓'),
            omp('«Tr3n-Azul!Vuela» es una contraseña…', ['Fuerte', 'Débil'], 'Fuerte', '🔒'),
            omp('«contraseña» es una contraseña…',    ['Débil', 'Fuerte'], 'Débil', '🔓'),
            omp('Una contraseña fuerte es larga y…',  ['Mezcla letras, números y símbolos', 'Fácil de adivinar', 'Igual en todo lado'], 'Mezcla letras, números y símbolos', '🔑'),
            omp('Usar la misma contraseña en todo es…', ['Peligroso', 'Recomendable', 'Obligatorio'], 'Peligroso', '⚠️'),
        ]),

        est('¿Qué se comparte y qué no?', 'No todo es para publicar', '🤐', 'opcion_multiple', [
            omp('Tu contraseña se comparte con…',      ['Nadie, salvo un adulto responsable', 'Tus amigos', 'Quien la pida'], 'Nadie, salvo un adulto responsable', '🔑'),
            omp('Tu dirección de casa en un chat público…', ['No se publica', 'Se publica', 'Da igual'], 'No se publica', '🏠'),
            omp('Una foto tuya con el uniforme del colegio…', ['Dice dónde estudias: cuidado', 'No dice nada', 'Es obligatoria'], 'Dice dónde estudias: cuidado', '👕'),
            omp('Tu nombre de usuario en un juego…',   ['Mejor que no sea tu nombre real', 'Debe ser tu nombre completo', 'Da igual'], 'Mejor que no sea tu nombre real', '🎮'),
            omp('El número de teléfono de tus papás…', ['Es privado', 'Es público', 'Se publica'], 'Es privado', '📞'),
        ]),

        est('Cierra bien', 'Terminar de usar también importa', '🚪', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para usar un computador prestado con seguridad',
            'items' => [
                '1️⃣ Entrar con mi usuario',
                '2️⃣ Usar el computador',
                '3️⃣ Guardar mi trabajo',
                '4️⃣ Cerrar sesión',
                '5️⃣ Revisar que no quedó nada mío abierto',
            ],
        ]),
    ],
],

[
    'slug'  => 'huella-digital',
    'title' => 'Huella digital',
    'description' => 'Todo lo que haces en internet deja rastro. Aprende a mirar el tuyo.',
    'objective' => 'Comprender qué es la huella digital y evaluar la fiabilidad de lo que se encuentra en línea.',
    'icon' => '👣', 'nivel' => 'primaria-media', 'bloque' => 'internet-seguro',
    'duracion' => 15, 'tags' => ['seguridad', 'logica', 'comprension', 'reto'],
    'estaciones' => [

        est('¿Qué deja rastro?', 'Más cosas de las que crees', '👣', 'opcion_multiple', [
            omp('Un comentario que escribes y luego borras…', ['Pudo quedar guardado o capturado', 'Desaparece del todo', 'Nunca existió'], 'Pudo quedar guardado o capturado', '💬'),
            omp('Una foto que publicas puede…',        ['Ser copiada por otros', 'Volver sola', 'Borrarse sola'], 'Ser copiada por otros', '📷'),
            omp('Tu huella digital es…',               ['El rastro que dejas al usar internet', 'Tu dedo', 'Tu contraseña'], 'El rastro que dejas al usar internet', '👣'),
            omp('Antes de publicar algo conviene preguntarse…', ['¿Me molestaría que lo viera cualquiera?', '¿Tengo batería?', 'Nada'], '¿Me molestaría que lo viera cualquiera?', '🤔'),
        ]),

        est('¿Es confiable?', 'No todo lo que está en internet es cierto', '🧐', 'opcion_multiple', [
            omp('Una página sin autor ni fecha es…',   ['Menos confiable', 'Más confiable', 'Igual'], 'Menos confiable', '❓'),
            omp('Un titular en mayúsculas que promete algo increíble suele ser…', ['Un anzuelo', 'Una noticia seria', 'Un estudio'], 'Un anzuelo', '📢'),
            omp('Para verificar algo, lo mejor es…',   ['Buscarlo en varias fuentes', 'Creer la primera', 'Preguntar en un chat'], 'Buscarlo en varias fuentes', '🔍'),
            omp('Una enciclopedia o un sitio oficial suele ser…', ['Más confiable', 'Menos confiable', 'Igual que un meme'], 'Más confiable', '📚'),
            omp('Compartir una noticia sin verificarla…', ['Ayuda a que se riegue aunque sea falsa', 'No tiene efecto', 'La vuelve verdadera'], 'Ayuda a que se riegue aunque sea falsa', '🔁'),
        ]),

        est('El caso del video viral', 'Lee y decide', '📱', 'cuento', [
            'slides' => [
                ['img' => '📱', 'text' => 'A Sofía le llega un video: dice que en su ciudad cerraron todos los colegios mañana.'],
                ['img' => '😮', 'text' => 'El video no dice quién lo grabó ni cuándo. Solo tiene música y letras grandes.'],
                ['img' => '👥', 'text' => 'Tres compañeros ya lo reenviaron. Uno escribió «es verdad, me lo mandó mi primo».'],
                ['img' => '🔍', 'text' => 'Sofía entra a la página de la Secretaría de Educación. No hay ningún aviso.'],
                ['img' => '🤔', 'text' => 'Sofía decide no reenviarlo y preguntarle a un adulto antes de hacer nada.'],
            ],
            'qs' => [
                reto('¿Qué le faltaba al video?', ['Autor y fecha', 'Música', 'Colores'], 'Autor y fecha'),
                reto('«Me lo mandó mi primo» es una prueba…', ['Débil', 'Fuerte', 'Definitiva'], 'Débil'),
                reto('¿Qué hizo bien Sofía?', ['Buscar la fuente oficial', 'Reenviarlo rápido', 'Borrar su cuenta'], 'Buscar la fuente oficial'),
                reto('Que muchos lo reenvíen significa que…', ['Es popular, no que sea cierto', 'Es cierto', 'Es oficial'], 'Es popular, no que sea cierto'),
            ],
        ]),

        est('Reto digital', 'Última prueba', '🏆', 'desafio_final', [
            reto('Un desconocido te pide una foto tuya. Lo correcto es…',
                 ['No enviarla y avisar a un adulto', 'Enviarla', 'Pedirle una a él'], 'No enviarla y avisar a un adulto'),
            reto('Un correo dice que ganaste un premio y pide tu contraseña. Es…',
                 ['Un engaño', 'Un premio real', 'Un juego'], 'Un engaño'),
            reto('¿Qué protege mejor una cuenta?',
                 ['Contraseña fuerte y verificación en dos pasos', 'Un nombre bonito', 'Muchos amigos'],
                 'Contraseña fuerte y verificación en dos pasos'),
            reto('Si alguien publica algo tuyo sin permiso, puedes…',
                 ['Pedir que lo baje y contarle a un adulto', 'Publicar algo suyo', 'No hacer nada nunca'],
                 'Pedir que lo baje y contarle a un adulto'),
        ]),
    ],
],

],
];
