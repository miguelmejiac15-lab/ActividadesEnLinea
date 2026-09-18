<?php
/**
 * tecnologia-primaria.php — Tecnología de 1.º a 6.º
 *
 * Escrito sobre las mallas de Tecnología (K1 a K6) de `MallasPrimaria/`,
 * que están alineadas con los estándares ISTE y KMK y avanzan así:
 * herramientas ofimáticas y correo (K3), animación y sonido (K4),
 * ciudadanía digital y seguridad (K5 y K6), con programación básica
 * atravesándolo todo.
 *
 * La malla nombra herramientas concretas —PowerPoint, Outlook, OneDrive—
 * porque el colegio las tiene contratadas. Aquí se enseña el CONCEPTO, no
 * el menú: dónde está el botón cambia con cada versión y deja el contenido
 * obsoleto en un año, mientras que «una diapositiva es una idea» sigue
 * siendo cierto en cualquier programa. Además, un niño que solo aprendió
 * dónde estaba el botón no sabe hacer nada cuando le cambian el programa.
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
    ['slug' => 'herramientas-digitales', 'name' => 'Herramientas Digitales', 'icon' => '🖥️', 'sort_order' => 4,
     'description' => 'Presentaciones, correo, archivos y nube: usar bien lo que ya está en el computador.'],
    ['slug' => 'algoritmos-y-codigo', 'name' => 'Algoritmos y Código', 'icon' => '🧩', 'sort_order' => 5,
     'description' => 'Dar instrucciones exactas a una máquina y corregirlas cuando fallan.'],
    ['slug' => 'crear-con-tecnologia', 'name' => 'Crear con Tecnología', 'icon' => '🎬', 'sort_order' => 6,
     'description' => 'Contar historias con animación, imagen y sonido.'],
    ['slug' => 'ciudadania-digital', 'name' => 'Ciudadanía Digital', 'icon' => '🛡️', 'sort_order' => 7,
     'description' => 'Contraseñas, privacidad, huella digital y cómo tratarse bien en línea.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · HERRAMIENTAS DIGITALES
// =====================================================================

[
    'slug'  => 'partes-del-computador',
    'title' => 'Partes del computador',
    'description' => 'Qué hace cada pieza, cuál sirve para entrar información y cuál para sacarla.',
    'objective' => 'Identificar los componentes de un computador y clasificarlos en entrada y salida.',
    'icon' => '🖱️', 'nivel' => 'primaria-inicial', 'bloque' => 'herramientas-digitales',
    'duracion' => 11, 'tags' => ['tecnologia', 'clasificacion', 'observacion'],
    'estaciones' => [

        est('Cada parte con su nombre', 'Une la pieza con su palabra', '🔗', 'emparejar', [
            ['e' => '🖥️', 'w' => 'Pantalla'],
            ['e' => '⌨️', 'w' => 'Teclado'],
            ['e' => '🖱️', 'w' => 'Ratón'],
            ['e' => '🖨️', 'w' => 'Impresora'],
            ['e' => '🔊', 'w' => 'Parlante'],
            ['e' => '📷', 'w' => 'Cámara'],
        ]),

        est('Entrada o salida', 'Meter o sacar información', '↔️', 'opcion_multiple', [
            omp('El teclado sirve para…',     ['Meter información', 'Sacar información', 'Guardar energía'], 'Meter información', '⌨️'),
            omp('La pantalla sirve para…',    ['Sacar información', 'Meter información', 'Enfriar'], 'Sacar información', '🖥️'),
            omp('El micrófono es un dispositivo de…', ['Entrada', 'Salida', 'Almacenamiento'], 'Entrada', '🎤'),
            omp('La impresora es un dispositivo de…', ['Salida', 'Entrada', 'Proceso'], 'Salida', '🖨️'),
            omp('¿Qué parte piensa y hace los cálculos?', ['El procesador', 'La pantalla', 'El cable'], 'El procesador'),
        ]),

        est('Hardware y software', 'Lo que se toca y lo que no', '🧩', 'opcion_multiple', [
            omp('¿Qué es el hardware?',      ['Las partes físicas que se pueden tocar', 'Los programas', 'Internet'], 'Las partes físicas que se pueden tocar'),
            omp('¿Qué es el software?',      ['Los programas y aplicaciones', 'Los cables', 'La pantalla'], 'Los programas y aplicaciones'),
            omp('Un juego instalado es…',    ['Software', 'Hardware', 'Un cable'], 'Software'),
            omp('El ratón es…',              ['Hardware', 'Software', 'Un archivo'], 'Hardware'),
            omp('¿Puede funcionar un computador sin software?', ['No, no sabría qué hacer', 'Sí, igual', 'Solo de día'], 'No, no sabría qué hacer'),
        ]),

        est('Memoria del computador', 'Encuentra las parejas', '🧠', 'memoria',
            ['🖥️', '⌨️', '🖱️', '🖨️', '💾', '🔊']),
    ],
],

[
    'slug'  => 'archivos-y-carpetas',
    'title' => 'Archivos y carpetas',
    'description' => 'Guardar, nombrar y encontrar: tener orden digital ahorra horas.',
    'objective' => 'Organizar archivos en carpetas y reconocer tipos de archivo por su extensión.',
    'icon' => '📁', 'nivel' => 'primaria-media', 'bloque' => 'herramientas-digitales',
    'duracion' => 12, 'tags' => ['tecnologia', 'clasificacion', 'atencion'],
    'estaciones' => [

        est('Tipos de archivo', 'Cada extensión dice qué contiene', '🗂️', 'opcion_multiple', [
            omp('Un archivo .jpg es…',   ['Una imagen', 'Un texto', 'Un sonido'], 'Una imagen', '🖼️'),
            omp('Un archivo .mp3 es…',   ['Un sonido', 'Un video', 'Una imagen'], 'Un sonido', '🎵'),
            omp('Un archivo .pdf es…',   ['Un documento', 'Un juego', 'Una canción'], 'Un documento', '📄'),
            omp('Un archivo .mp4 es…',   ['Un video', 'Una imagen', 'Un texto'], 'Un video', '🎬'),
            omp('¿Qué es la extensión de un archivo?', ['Lo que va después del punto en su nombre', 'Su tamaño', 'Su fecha'], 'Lo que va después del punto en su nombre'),
        ]),

        /*
         * Nombrar bien es lo que más se salta y lo que más cuesta después.
         * «documento1» y «documento final FINAL 2» son el mismo error a
         * distinta escala: el nombre tiene que decir qué hay dentro.
         */
        est('Poner buenos nombres', 'El nombre es para el yo del futuro', '🏷️', 'opcion_multiple', [
            omp('¿Cuál es un buen nombre de archivo?',  ['tarea-ciencias-celula.pdf', 'aaa.pdf', 'nuevo1.pdf'], 'tarea-ciencias-celula.pdf'),
            omp('¿Por qué no conviene llamarlo «documento1»?', ['Porque en un mes no sabré qué es', 'Porque es largo', 'Porque no se puede'], 'Porque en un mes no sabré qué es'),
            omp('¿Qué conviene evitar en los nombres?',  ['Espacios raros y símbolos', 'Letras', 'Números'], 'Espacios raros y símbolos'),
            omp('¿Para qué sirve una carpeta?',          ['Para agrupar archivos relacionados', 'Para borrar', 'Para imprimir'], 'Para agrupar archivos relacionados'),
            omp('Si tengo tareas de varias materias, conviene…', ['Una carpeta por materia', 'Todo junto', 'Borrar las viejas'], 'Una carpeta por materia'),
        ]),

        est('Guardar y respaldar', 'Perder un trabajo duele', '💾', 'opcion_multiple', [
            omp('¿Cada cuánto conviene guardar mientras trabajo?', ['Cada pocos minutos', 'Solo al final', 'Nunca'], 'Cada pocos minutos'),
            omp('¿Qué es una copia de seguridad?',       ['Una segunda copia guardada en otro sitio', 'Una contraseña', 'Un antivirus'], 'Una segunda copia guardada en otro sitio'),
            omp('¿Qué ventaja tiene guardar en la nube?', ['Se puede abrir desde otro dispositivo', 'Pesa menos', 'No se borra nunca'], 'Se puede abrir desde otro dispositivo', '☁️'),
            omp('Si se apaga la luz y no guardé…',       ['Puedo perder el trabajo', 'No pasa nada', 'Se guarda solo siempre'], 'Puedo perder el trabajo'),
            omp('¿Dónde va lo que borro?',               ['A la papelera, y de ahí se puede recuperar', 'Desaparece al instante', 'A internet'], 'A la papelera, y de ahí se puede recuperar', '🗑️'),
        ]),

        est('Ordena el trabajo', 'Los pasos de guardar bien', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para entregar una tarea digital',
            'items' => ['Crear el documento', 'Escribir el contenido', 'Ponerle un nombre claro', 'Guardarlo en su carpeta', 'Enviarlo al profesor'],
        ]),
    ],
],

[
    'slug'  => 'presentaciones-digitales',
    'title' => 'Presentaciones digitales',
    'description' => 'Organizar ideas en diapositivas para que se entiendan de una mirada.',
    'objective' => 'Estructurar una presentación jerarquizando ideas y combinando texto e imagen.',
    'icon' => '📊', 'nivel' => 'primaria-media', 'bloque' => 'herramientas-digitales',
    'duracion' => 13, 'tags' => ['tecnologia', 'escritura', 'observacion'],
    'estaciones' => [

        est('Una idea por diapositiva', 'La regla que salva cualquier presentación', '💡', 'opcion_multiple', [
            omp('¿Cuántas ideas conviene poner por diapositiva?', ['Una', 'Todas las que quepan', 'Ninguna'], 'Una'),
            omp('¿Qué pasa si lleno la diapositiva de texto?',    ['Nadie la lee y me leen a mí en vez de escucharme', 'Se ve más completa', 'Nada'], 'Nadie la lee y me leen a mí en vez de escucharme'),
            omp('¿Para qué sirve el título de una diapositiva?',  ['Para decir de qué va', 'Para decorar', 'Para rellenar'], 'Para decir de qué va'),
            omp('¿Qué tamaño de letra conviene?',                 ['Grande, que se lea desde el fondo', 'Muy pequeña', 'Da igual'], 'Grande, que se lea desde el fondo'),
            omp('¿Conviene leer la diapositiva palabra por palabra?', ['No, la diapositiva acompaña, no reemplaza', 'Sí, siempre', 'Solo el título'], 'No, la diapositiva acompaña, no reemplaza'),
        ]),

        est('Texto e imagen', 'Se acompañan, no compiten', '🖼️', 'opcion_multiple', [
            omp('¿Para qué sirve una imagen en una presentación?', ['Para ayudar a entender la idea', 'Para rellenar espacio', 'Para que sea larga'], 'Para ayudar a entender la idea'),
            omp('¿Conviene usar imágenes borrosas?',   ['No, se ven mal proyectadas', 'Sí', 'Solo al final'], 'No, se ven mal proyectadas'),
            omp('¿Puedo usar cualquier imagen de internet?', ['Debo revisar si tiene permiso de uso', 'Sí, todas', 'Ninguna'], 'Debo revisar si tiene permiso de uso'),
            omp('¿Qué colores se leen mejor proyectados?', ['Alto contraste entre fondo y letra', 'Fondo y letra parecidos', 'Muchos colores'], 'Alto contraste entre fondo y letra'),
            omp('¿Conviene usar diez tipos de letra distintos?', ['No, uno o dos bastan', 'Sí, es más bonito', 'Da igual'], 'No, uno o dos bastan'),
        ]),

        est('Ordena la presentación', 'Toda exposición tiene forma', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena las partes de una presentación',
            'items' => ['Portada con el tema', 'Introducción', 'Desarrollo de las ideas', 'Conclusión', 'Preguntas'],
        ]),

        est('Presentar en público', 'La diapositiva no expone sola', '🎤', 'desafio_final', [
            reto('¿Qué conviene hacer antes de exponer?',   ['Ensayar en voz alta', 'Improvisar', 'Memorizar palabra por palabra'], 'Ensayar en voz alta'),
            reto('¿Hacia dónde debo mirar al exponer?',     ['Al público', 'A la pantalla', 'Al piso'], 'Al público'),
            reto('¿Qué hago si me equivoco?',               ['Sigo con calma', 'Me detengo y me voy', 'Empiezo de nuevo'], 'Sigo con calma'),
            reto('¿Cómo debo hablar?',                      ['Claro y sin correr', 'Muy rápido', 'Muy bajito'], 'Claro y sin correr'),
            reto('¿Qué pasa si la tecnología falla?',       ['Puedo explicar igual con mis palabras', 'Se cancela todo', 'Es culpa del público'], 'Puedo explicar igual con mis palabras'),
        ]),
    ],
],

[
    'slug'  => 'el-correo-electronico',
    'title' => 'El correo electrónico',
    'description' => 'Escribir un correo con asunto, saludo y adjuntos, y las reglas de la netiqueta.',
    'objective' => 'Redactar correos con estructura adecuada aplicando normas de comunicación digital.',
    'icon' => '📧', 'nivel' => 'primaria-superior', 'bloque' => 'herramientas-digitales',
    'duracion' => 13, 'tags' => ['tecnologia', 'escritura', 'convivencia'],
    'estaciones' => [

        est('Partes de un correo', 'Cada campo tiene su función', '✉️', 'opcion_multiple', [
            omp('¿Qué va en el campo «Para»?',      ['La dirección de quien lo recibe', 'Mi nombre', 'El mensaje'], 'La dirección de quien lo recibe'),
            omp('¿Para qué sirve el asunto?',       ['Para decir en pocas palabras de qué se trata', 'Para saludar', 'Para firmar'], 'Para decir en pocas palabras de qué se trata'),
            omp('¿Qué pasa si mando un correo sin asunto?', ['Puede parecer spam y no abrirse', 'Nada', 'Llega más rápido'], 'Puede parecer spam y no abrirse'),
            omp('¿Qué es un archivo adjunto?',      ['Un archivo que viaja con el correo', 'Una firma', 'Un enlace'], 'Un archivo que viaja con el correo', '📎'),
            omp('¿Qué símbolo lleva toda dirección de correo?', ['@', '#', '&'], '@'),
        ]),

        est('La netiqueta', 'Buenos modales en digital', '🙋', 'opcion_multiple', [
            omp('¿Cómo empiezo un correo a un profesor?',  ['Con un saludo respetuoso', 'Sin saludo', 'Con un apodo'], 'Con un saludo respetuoso'),
            omp('¿Qué significa escribir TODO EN MAYÚSCULAS?', ['Se lee como si estuvieras gritando', 'Que es importante', 'Nada'], 'Se lee como si estuvieras gritando'),
            omp('¿Conviene revisar antes de enviar?',      ['Sí, siempre', 'No hace falta', 'Solo si es largo'], 'Sí, siempre'),
            omp('¿Cómo termino un correo formal?',         ['Con una despedida y mi nombre', 'De golpe', 'Con un emoji solamente'], 'Con una despedida y mi nombre'),
            omp('¿Debo responder un correo que me hace una pregunta?', ['Sí, en un tiempo razonable', 'No', 'Solo si quiero'], 'Sí, en un tiempo razonable'),
        ]),

        est('Ordena el correo', 'La estructura de un mensaje', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena las partes de un correo',
            'items' => ['Asunto claro', 'Saludo', 'Motivo del mensaje', 'Despedida', 'Nombre y curso'],
        ]),

        est('¿Está bien escrito?', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es correcto en un correo formal?', 'Responde rápido', [
                ['e' => '📝', 'n' => 'Poner un asunto claro',        'ok' => true],
                ['e' => '🔠', 'n' => 'ESCRIBIR TODO EN MAYÚSCULAS',  'ok' => false],
                ['e' => '🙋', 'n' => 'Saludar antes de pedir algo',  'ok' => true],
                ['e' => '😡', 'n' => 'Responder enojado al instante', 'ok' => false],
                ['e' => '✅', 'n' => 'Revisar la ortografía',        'ok' => true],
                ['e' => '📎', 'n' => 'Olvidar el archivo adjunto',   'ok' => false],
            ])),
    ],
],


// =====================================================================
//  BLOQUE · ALGORITMOS Y CÓDIGO
// =====================================================================

[
    'slug'  => 'que-es-un-algoritmo',
    'title' => '¿Qué es un algoritmo?',
    'description' => 'Una lista de pasos exactos y en orden. Las máquinas no adivinan lo que quisiste decir.',
    'objective' => 'Comprender el concepto de algoritmo y la importancia del orden y la precisión.',
    'icon' => '📝', 'nivel' => 'primaria-inicial', 'bloque' => 'algoritmos-y-codigo',
    'duracion' => 11, 'tags' => ['logica', 'secuencias', 'tecnologia'],
    'estaciones' => [

        est('Pasos en orden', 'Un algoritmo es una receta', '🍳', 'opcion_multiple', [
            omp('¿Qué es un algoritmo?',                ['Una lista de pasos en orden para lograr algo', 'Un computador', 'Un juego'], 'Una lista de pasos en orden para lograr algo'),
            omp('¿Importa el orden de los pasos?',      ['Sí, mucho', 'No', 'Solo al final'], 'Sí, mucho'),
            omp('¿Es una receta de cocina un algoritmo?', ['Sí', 'No', 'Solo si es de postre'], 'Sí'),
            omp('Si me salto un paso, ¿qué pasa?',      ['El resultado sale mal', 'Nada', 'Va más rápido'], 'El resultado sale mal'),
            omp('¿Entiende una máquina lo que «quise decir»?', ['No, hace exactamente lo que le digo', 'Sí, siempre', 'A veces adivina'], 'No, hace exactamente lo que le digo'),
        ]),

        est('Ordena la mañana', 'Un algoritmo del día a día', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para lavarse los dientes',
            'items' => ['Tomar el cepillo', 'Poner la crema', 'Cepillar los dientes', 'Enjuagarse', 'Guardar el cepillo'],
        ]),

        est('El robot obediente', 'Instrucciones exactas', '🤖', 'opcion_multiple', [
            omp('Le digo al robot «ve». ¿Qué le falta saber?',  ['Hacia dónde y cuánto', 'Nada', 'Su nombre'], 'Hacia dónde y cuánto'),
            omp('¿Cuál instrucción es más clara?',              ['Avanza 3 pasos al norte', 'Ve por ahí', 'Muévete un poco'], 'Avanza 3 pasos al norte'),
            omp('Si el robot choca contra un muro, es porque…', ['Las instrucciones estaban mal', 'El robot es malo', 'Los muros se mueven'], 'Las instrucciones estaban mal'),
            omp('¿Qué es depurar o «encontrar el error»?',      ['Revisar los pasos para ver dónde falla', 'Apagar el computador', 'Empezar de cero siempre'], 'Revisar los pasos para ver dónde falla'),
            omp('Si algo falla, ¿de quién suele ser el error?', ['De las instrucciones que escribí', 'Del computador', 'De nadie'], 'De las instrucciones que escribí'),
        ]),

        est('Laberinto del robot', 'Programa el camino y míralo andar', '🕹️', 'laberinto', [
            ['filas' => 4, 'columnas' => 4, 'inicio' => [0, 0], 'meta' => [0, 3], 'muros' => []],
            ['filas' => 4, 'columnas' => 4, 'inicio' => [3, 0], 'meta' => [0, 3], 'muros' => [[1, 1], [2, 2]]],
            ['filas' => 5, 'columnas' => 5, 'inicio' => [4, 0], 'meta' => [0, 4], 'muros' => [[3, 1], [2, 2], [1, 3]]],
        ]),
    ],
],

[
    'slug'  => 'secuencias-y-repeticiones',
    'title' => 'Secuencias y repeticiones',
    'description' => 'Bucles y condiciones: las dos ideas que hacen que un programa sea corto y potente.',
    'objective' => 'Reconocer estructuras de repetición y de decisión en secuencias de instrucciones.',
    'icon' => '🔁', 'nivel' => 'primaria-media', 'bloque' => 'algoritmos-y-codigo',
    'duracion' => 13, 'tags' => ['logica', 'patrones', 'tecnologia'],
    'estaciones' => [

        est('Repetir sin escribir mil veces', 'El bucle', '🔄', 'opcion_multiple', [
            omp('¿Qué es un bucle en programación?',        ['Repetir instrucciones varias veces', 'Borrar el código', 'Un error'], 'Repetir instrucciones varias veces'),
            omp('«Da 4 pasos» se puede escribir como…',     ['Repetir 4 veces: da un paso', 'Da un paso', 'Detente'], 'Repetir 4 veces: da un paso'),
            omp('¿Para qué sirve un bucle?',                ['Para no repetir la misma línea muchas veces', 'Para ir más lento', 'Para borrar'], 'Para no repetir la misma línea muchas veces'),
            omp('Para dibujar un cuadrado con el robot, repito…', ['4 veces: avanza y gira', '1 vez: avanza', '8 veces: gira'], '4 veces: avanza y gira'),
            omp('¿Qué pasa si el bucle nunca termina?',     ['El programa se queda pegado', 'Va más rápido', 'Se arregla solo'], 'El programa se queda pegado'),
        ]),

        est('Si pasa esto, haz aquello', 'La condición', '🔀', 'opcion_multiple', [
            omp('¿Qué es una condición en programación?',   ['Una decisión: si pasa algo, haz esto', 'Una repetición', 'Un dibujo'], 'Una decisión: si pasa algo, haz esto'),
            omp('«Si llueve, lleva paraguas» es…',          ['Una condición', 'Un bucle', 'Un error'], 'Una condición', '🌧️'),
            omp('¿Qué palabra suele iniciar una condición?', ['Si', 'Repetir', 'Fin'], 'Si'),
            omp('«Si el semáforo está en rojo, detente» es…', ['Una condición', 'Un bucle infinito', 'Una variable'], 'Una condición', '🚦'),
            omp('¿Puede una condición tener dos caminos?',  ['Sí: si pasa, haz A; si no, haz B', 'No', 'Solo uno'], 'Sí: si pasa, haz A; si no, haz B'),
        ]),

        est('Sigue el programa', '¿Dónde termina el robot?', '🧭', 'opcion_multiple', [
            omp('El robot mira al norte y gira a la derecha. ¿Hacia dónde mira?', ['Al este', 'Al oeste', 'Al sur'], 'Al este'),
            omp('Mira al este y gira a la derecha. ¿Hacia dónde mira?',           ['Al sur', 'Al norte', 'Al oeste'], 'Al sur'),
            omp('Si gira cuatro veces a la derecha, mira…',                       ['Hacia donde empezó', 'Al sur', 'Al oeste'], 'Hacia donde empezó'),
            omp('Empieza en (0,0) y avanza 3 al este. ¿Dónde está?',              ['En (0,3)', 'En (3,0)', 'En (0,0)'], 'En (0,3)'),
            omp('¿Qué conviene hacer antes de ejecutar un programa largo?',       ['Revisarlo paso a paso', 'Ejecutarlo sin mirar', 'Borrarlo'], 'Revisarlo paso a paso'),
        ]),

        est('Reto del laberinto', 'Tres niveles con muros', '🕹️', 'laberinto', [
            ['filas' => 5, 'columnas' => 5, 'inicio' => [0, 0], 'meta' => [4, 4], 'muros' => [[1, 1], [2, 3], [3, 1]]],
            ['filas' => 5, 'columnas' => 5, 'inicio' => [4, 4], 'meta' => [0, 0], 'muros' => [[3, 3], [2, 2], [1, 1]]],
            ['filas' => 6, 'columnas' => 6, 'inicio' => [5, 0], 'meta' => [0, 5], 'muros' => [[4, 1], [3, 2], [2, 3], [1, 4]]],
        ]),
    ],
],


// =====================================================================
//  BLOQUE · CREAR CON TECNOLOGÍA
// =====================================================================

[
    'slug'  => 'animacion-y-storyboard',
    'title' => 'Animación y storyboard',
    'description' => 'Cómo se crea el movimiento con imágenes fijas y cómo se planea una historia en viñetas.',
    'objective' => 'Comprender el principio de la animación por fotogramas y planificar con un storyboard.',
    'icon' => '🎞️', 'nivel' => 'primaria-media', 'bloque' => 'crear-con-tecnologia',
    'duracion' => 13, 'tags' => ['tecnologia', 'secuencias', 'observacion'],
    'estaciones' => [

        est('¿Cómo se mueve un dibujo?', 'El truco del ojo', '👁️', 'opcion_multiple', [
            omp('¿Cómo se logra la animación?',        ['Mostrando muchas imágenes seguidas y rápidas', 'Con una sola imagen', 'Con sonido'], 'Mostrando muchas imágenes seguidas y rápidas'),
            omp('¿Cómo se llama cada imagen de una animación?', ['Fotograma', 'Página', 'Escena'], 'Fotograma'),
            omp('Si pongo menos fotogramas por segundo, el movimiento se ve…', ['Entrecortado', 'Más suave', 'Igual'], 'Entrecortado'),
            omp('¿Qué es la línea de tiempo en un editor?', ['Donde se ordenan los fotogramas y el sonido', 'El título', 'El menú'], 'Donde se ordenan los fotogramas y el sonido'),
            omp('¿Qué es el stop motion?',             ['Animar objetos reales foto a foto', 'Grabar un video normal', 'Dibujar a mano'], 'Animar objetos reales foto a foto'),
        ]),

        est('El storyboard', 'Planear antes de grabar', '🗒️', 'opcion_multiple', [
            omp('¿Qué es un storyboard?',              ['Un guion dibujado viñeta por viñeta', 'Un resumen escrito', 'La portada'], 'Un guion dibujado viñeta por viñeta'),
            omp('¿Para qué sirve hacerlo antes?',      ['Para saber qué grabar y no perder tiempo', 'Para decorar', 'Para nada'], 'Para saber qué grabar y no perder tiempo'),
            omp('¿Qué muestra cada viñeta?',           ['Un momento de la historia', 'Todo el video', 'El final'], 'Un momento de la historia'),
            omp('¿Qué es un plano general?',           ['Una toma que muestra todo el escenario', 'Un primer plano', 'Un sonido'], 'Una toma que muestra todo el escenario'),
            omp('¿Qué es un primer plano?',            ['Una toma cercana, casi siempre del rostro', 'Una toma lejana', 'El título'], 'Una toma cercana, casi siempre del rostro'),
        ]),

        est('Ordena la producción', 'Del papel a la pantalla', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para hacer una animación',
            'items' => ['Pensar la historia', 'Dibujar el storyboard', 'Crear los personajes', 'Grabar los fotogramas', 'Editar y añadir sonido'],
        ]),

        est('El sonido cuenta', 'Lo que se oye cambia lo que se ve', '🔊', 'opcion_multiple', [
            omp('¿Qué aporta la música a una escena?',    ['Le da emoción y ritmo', 'Nada', 'La hace más larga'], 'Le da emoción y ritmo', '🎵'),
            omp('¿Qué es una voz en off?',                ['Una voz que narra sin verse en pantalla', 'Un ruido', 'La música'], 'Una voz que narra sin verse en pantalla'),
            omp('¿Qué es un efecto de sonido?',           ['Un sonido que acompaña una acción', 'La canción principal', 'El silencio'], 'Un sonido que acompaña una acción'),
            omp('¿Puedo usar cualquier canción en mi video?', ['Debo revisar los derechos de autor', 'Sí, todas', 'Ninguna'], 'Debo revisar los derechos de autor'),
            omp('¿Sirve el silencio en una historia?',    ['Sí, crea tensión o pausa', 'No, es un error', 'Solo al principio'], 'Sí, crea tensión o pausa'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · CIUDADANÍA DIGITAL
// =====================================================================

[
    'slug'  => 'contrasenas-seguras',
    'title' => 'Contraseñas seguras',
    'description' => 'Qué hace fuerte a una contraseña y por qué no se comparten nunca.',
    'objective' => 'Crear contraseñas robustas y reconocer prácticas que ponen en riesgo una cuenta.',
    'icon' => '🔑', 'nivel' => 'primaria-media', 'bloque' => 'ciudadania-digital',
    'duracion' => 12, 'tags' => ['tecnologia', 'seguridad', 'autocuidado'],
    'estaciones' => [

        est('¿Qué hace fuerte a una contraseña?', 'Larga y difícil de adivinar', '💪', 'opcion_multiple', [
            omp('¿Cuál es la contraseña más segura?',  ['Ma7#zul-Perro2026', '123456', 'password'], 'Ma7#zul-Perro2026'),
            omp('¿Es buena idea usar tu fecha de nacimiento?', ['No, es fácil de averiguar', 'Sí', 'Solo el año'], 'No, es fácil de averiguar'),
            omp('¿Qué hace más fuerte una contraseña?', ['Que sea larga y mezcle tipos de caracteres', 'Que sea corta', 'Que sea una palabra común'], 'Que sea larga y mezcle tipos de caracteres'),
            omp('¿Conviene usar la misma contraseña en todo?', ['No, si roban una entran a todo', 'Sí, es más fácil', 'Da igual'], 'No, si roban una entran a todo'),
            omp('¿A quién se le comparte la contraseña?', ['A nadie, ni a los amigos', 'Al mejor amigo', 'A todos'], 'A nadie, ni a los amigos'),
        ]),

        est('Proteger la cuenta', 'Más allá de la contraseña', '🔐', 'opcion_multiple', [
            omp('¿Qué hago si creo que alguien sabe mi contraseña?', ['La cambio de inmediato y aviso a un adulto', 'No hago nada', 'La escribo en un papel'], 'La cambio de inmediato y aviso a un adulto'),
            omp('¿Qué es cerrar sesión?',              ['Salir de la cuenta al terminar', 'Apagar la pantalla', 'Borrar la cuenta'], 'Salir de la cuenta al terminar'),
            omp('¿Por qué cerrar sesión en un computador compartido?', ['Para que nadie más entre a mi cuenta', 'Para ahorrar batería', 'No hace falta'], 'Para que nadie más entre a mi cuenta'),
            omp('¿Qué es la verificación en dos pasos?', ['Una segunda comprobación además de la contraseña', 'Escribirla dos veces', 'Cambiarla dos veces'], 'Una segunda comprobación además de la contraseña'),
            omp('¿Debo guardar mis contraseñas en un papel pegado a la pantalla?', ['No', 'Sí', 'Solo la del correo'], 'No'),
        ]),

        est('Datos que no se comparten', 'Información privada', '🤐', 'seleccion_imagenes',
            conTitulo('Toca lo que NO debes publicar en internet', 'Piensa en quién podría verlo', [
                ['e' => '🏠', 'n' => 'Tu dirección',        'ok' => true],
                ['e' => '📞', 'n' => 'Tu teléfono',         'ok' => true],
                ['e' => '🔑', 'n' => 'Tu contraseña',       'ok' => true],
                ['e' => '🏫', 'n' => 'El nombre de tu colegio', 'ok' => true],
                ['e' => '🎨', 'n' => 'Un dibujo que hiciste', 'ok' => false],
                ['e' => '📚', 'n' => 'Tu libro favorito',   'ok' => false],
                ['e' => '💳', 'n' => 'La tarjeta de tus papás', 'ok' => true],
                ['e' => '⚽', 'n' => 'Tu deporte favorito', 'ok' => false],
            ])),

        est('Contraseña segura', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es una buena práctica?', 'Responde rápido', [
                ['e' => '🔒', 'n' => 'Usar contraseñas distintas',     'ok' => true],
                ['e' => '📢', 'n' => 'Contarle la clave a un amigo',   'ok' => false],
                ['e' => '🔄', 'n' => 'Cambiarla si se filtró',         'ok' => true],
                ['e' => '1️⃣', 'n' => 'Usar 123456 como contraseña',    'ok' => false],
                ['e' => '🚪', 'n' => 'Cerrar sesión al terminar',      'ok' => true],
                ['e' => '📝', 'n' => 'Pegarla en el monitor',          'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'huella-digital',
    'title' => 'Huella digital',
    'description' => 'Todo lo que publicas deja rastro. Qué queda, quién lo ve y por cuánto tiempo.',
    'objective' => 'Comprender la permanencia de la información en línea y sus consecuencias.',
    'icon' => '👣', 'nivel' => 'primaria-superior', 'bloque' => 'ciudadania-digital',
    'duracion' => 14, 'tags' => ['tecnologia', 'seguridad', 'convivencia'],
    'estaciones' => [

        est('¿Qué es la huella digital?', 'El rastro que dejas', '🐾', 'opcion_multiple', [
            omp('¿Qué es la huella digital?',           ['El rastro que dejan tus acciones en internet', 'Un dibujo', 'Una contraseña'], 'El rastro que dejan tus acciones en internet'),
            omp('Si borro una foto que publiqué, ¿desaparece del todo?', ['No, alguien pudo guardarla o copiarla', 'Sí, siempre', 'Solo de noche'], 'No, alguien pudo guardarla o copiarla'),
            omp('¿Quién puede ver lo que publico en abierto?', ['Cualquiera', 'Solo mis amigos', 'Nadie'], 'Cualquiera'),
            omp('¿Puede afectar en el futuro lo que publico hoy?', ['Sí, puede seguir ahí en años', 'No', 'Solo si es video'], 'Sí, puede seguir ahí en años'),
            omp('Antes de publicar algo, conviene preguntarse…', ['¿Me molestaría que lo viera cualquiera?', 'Nada', '¿Cuántos «me gusta» tendrá?'], '¿Me molestaría que lo viera cualquiera?'),
        ]),

        est('Engaños en línea', 'Reconocer una trampa', '🎣', 'opcion_multiple', [
            omp('¿Qué es el phishing?',                 ['Un engaño para robarte datos haciéndose pasar por alguien', 'Un juego', 'Un virus del hardware'], 'Un engaño para robarte datos haciéndose pasar por alguien'),
            omp('«¡Ganaste un premio! Da clic aquí» probablemente sea…', ['Un engaño', 'Un premio real', 'Un correo del colegio'], 'Un engaño'),
            omp('¿Qué hago con un enlace de un remitente desconocido?', ['No lo abro y aviso a un adulto', 'Lo abro', 'Lo reenvío'], 'No lo abro y aviso a un adulto'),
            omp('¿Pide un banco la contraseña por correo?', ['Nunca', 'Siempre', 'A veces'], 'Nunca'),
            omp('Si alguien desconocido me escribe pidiendo fotos, ¿qué hago?', ['No respondo y le cuento a un adulto', 'Le respondo', 'Le mando una'], 'No respondo y le cuento a un adulto'),
        ]),

        est('Tratarse bien en línea', 'Del otro lado hay una persona', '💙', 'opcion_multiple', [
            omp('¿Qué es el ciberacoso?',               ['Molestar o humillar a alguien por medios digitales', 'Una broma sin más', 'Un juego en línea'], 'Molestar o humillar a alguien por medios digitales'),
            omp('Si veo que acosan a un compañero en un grupo, ¿qué hago?', ['No lo apoyo y aviso a un adulto', 'Me río', 'Lo reenvío'], 'No lo apoyo y aviso a un adulto'),
            omp('¿Duele menos un insulto por pantalla?', ['No, duele igual o más', 'Sí', 'No duele'], 'No, duele igual o más'),
            omp('¿Qué hago si alguien me acosa en línea?', ['Guardo la evidencia, bloqueo y aviso', 'Respondo igual', 'Me callo'], 'Guardo la evidencia, bloqueo y aviso'),
            omp('Antes de escribir algo en un chat, conviene…', ['Pensar si se lo diría a la cara', 'Escribirlo rápido', 'Usar mayúsculas'], 'Pensar si se lo diría a la cara'),
        ]),

        est('Completa lo que sabes', 'Coloca cada palabra en su hueco', '📝', 'completar_texto',
            conTitulo('Completa el texto', 'Lee la frase entera antes de elegir', [
                [
                    'titulo' => 'Lo que queda de lo que publicas',
                    'texto'  => 'Todo lo que haces en internet deja un rastro que se llama huella '
                              . '___. Si borras una foto, no desaparece del todo: alguien pudo '
                              . '___ antes. Por eso, antes de publicar algo conviene preguntarse si '
                              . 'te molestaría que lo viera ___.',
                    'huecos' => ['digital', 'guardarla', 'cualquiera'],
                    'extra'  => ['personal', 'borrarla', 'nadie'],
                ],
                [
                    'titulo' => 'Reconocer un engaño',
                    'texto'  => 'El ___ es un engaño en el que alguien se hace pasar por otro para '
                              . 'robarte datos. Un correo que dice «¡Ganaste un premio!» y pide que des '
                              . '___ es casi siempre falso. Un banco nunca pide tu ___ por correo. '
                              . 'Ante la duda, no abras el enlace y avisa a un ___.',
                    'huecos' => ['phishing', 'clic', 'contraseña', 'adulto'],
                    'extra'  => ['virus', 'dinero', 'amigo'],
                ],
            ])),

        est('Desafío digital', 'Cinco decisiones difíciles', '🏆', 'desafio_final', [
            reto('Un juego pide tu nombre real y tu dirección. ¿Qué haces?', ['No los doy y consulto a un adulto', 'Los doy', 'Doy solo la dirección'], 'No los doy y consulto a un adulto'),
            reto('Un amigo comparte una foto tuya sin permiso. ¿Está bien?', ['No, debió pedirte permiso', 'Sí', 'Da igual'], 'No, debió pedirte permiso'),
            reto('Ves una noticia increíble en una red. ¿Qué haces?',       ['Compruebo si es cierta antes de compartir', 'La comparto ya', 'La creo sin más'], 'Compruebo si es cierta antes de compartir'),
            reto('¿Por qué existen las edades mínimas en las redes?',       ['Porque hay contenidos y riesgos para los que hace falta madurez', 'Por capricho', 'Por publicidad'], 'Porque hay contenidos y riesgos para los que hace falta madurez'),
            reto('¿Es internet malo?',                                     ['No, depende de cómo se use', 'Sí', 'Solo de noche'], 'No, depende de cómo se use'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'buscar-informacion',
    'title' => 'Buscar información',
    'description' => 'Cómo buscar bien en internet y cómo saber si lo que encontraste sirve.',
    'objective' => 'Aplicar estrategias de búsqueda y criterios para evaluar fuentes digitales.',
    'icon' => '🔎', 'nivel' => 'primaria-media', 'bloque' => 'herramientas-digitales',
    'duracion' => 12, 'tags' => ['tecnologia', 'comprension', 'deduccion'],
    'estaciones' => [

        est('Buscar mejor', 'Las palabras importan', '⌨️', 'opcion_multiple', [
            omp('¿Qué búsqueda da mejores resultados?', ['«ciclo del agua para niños»', '«agua»', '«quiero saber»'], '«ciclo del agua para niños»'),
            omp('¿Sirve escribir la pregunta completa?', ['Sí, hoy los buscadores la entienden bien', 'No, nunca', 'Solo en inglés'], 'Sí, hoy los buscadores la entienden bien'),
            omp('¿Qué hago si no encuentro nada útil?',  ['Cambio las palabras de búsqueda', 'Me rindo', 'Uso la misma otra vez'], 'Cambio las palabras de búsqueda'),
            omp('¿Es siempre mejor el primer resultado?', ['No, conviene mirar varios', 'Sí', 'Solo si tiene imagen'], 'No, conviene mirar varios'),
            omp('¿Qué es un buscador?',                  ['Un servicio que encuentra páginas por palabras clave', 'Una red social', 'Un virus'], 'Un servicio que encuentra páginas por palabras clave'),
        ]),

        est('¿Es confiable?', 'Evaluar la fuente', '🧐', 'opcion_multiple', [
            omp('¿Qué hace más confiable una página?',   ['Que diga quién la escribió y cuándo', 'Que tenga muchos colores', 'Que sea la primera'], 'Que diga quién la escribió y cuándo'),
            omp('¿Sirve contrastar con otra fuente?',    ['Sí, siempre', 'No hace falta', 'Solo en historia'], 'Sí, siempre'),
            omp('Una página sin autor ni fecha…',        ['Hay que tomarla con cuidado', 'Es la mejor', 'Es igual de buena'], 'Hay que tomarla con cuidado'),
            omp('¿Puede haber errores en internet?',     ['Sí, muchos', 'No', 'Solo en videos'], 'Sí, muchos'),
            omp('¿Qué es plagiar?',                      ['Copiar algo y presentarlo como propio', 'Citar una fuente', 'Resumir'], 'Copiar algo y presentarlo como propio'),
        ]),

        est('Citar y dar crédito', 'Usar el trabajo de otros con honestidad', '📚', 'opcion_multiple', [
            omp('Si uso información de una página, ¿qué hago?', ['Digo de dónde la saqué', 'La copio sin más', 'La escondo'], 'Digo de dónde la saqué'),
            omp('¿Qué es citar una fuente?',             ['Indicar de dónde viene la información', 'Copiar todo', 'Inventar el autor'], 'Indicar de dónde viene la información'),
            omp('¿Está bien copiar y pegar un trabajo entero?', ['No, es plagio', 'Sí', 'Si cambio el título, sí'], 'No, es plagio'),
            omp('¿Qué es resumir con mis palabras?',     ['Explicar lo que entendí sin copiar', 'Traducir', 'Copiar la mitad'], 'Explicar lo que entendí sin copiar'),
            omp('¿Puedo usar cualquier imagen de internet en un trabajo?', ['Debo revisar su licencia y citarla', 'Sí, todas', 'Ninguna'], 'Debo revisar su licencia y citarla'),
        ]),

        est('Verdadero o falso', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es cierto?', 'Responde rápido', [
                ['e' => '🔎', 'n' => 'Conviene comparar varias fuentes',  'ok' => true],
                ['e' => '1️⃣', 'n' => 'El primer resultado siempre es el mejor', 'ok' => false],
                ['e' => '📅', 'n' => 'La fecha de una página importa',    'ok' => true],
                ['e' => '📋', 'n' => 'Copiar y pegar sin citar está bien', 'ok' => false],
                ['e' => '✍️', 'n' => 'Citar la fuente es lo correcto',    'ok' => true],
                ['e' => '🌐', 'n' => 'Todo lo que está en internet es verdad', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'hojas-de-calculo',
    'title' => 'Hojas de cálculo',
    'description' => 'Filas, columnas y fórmulas: organizar datos y hacer que el computador calcule.',
    'objective' => 'Comprender la estructura de una hoja de cálculo y el uso de fórmulas básicas.',
    'icon' => '📗', 'nivel' => 'primaria-superior', 'bloque' => 'herramientas-digitales',
    'duracion' => 13, 'tags' => ['tecnologia', 'calculo', 'logica'],
    'estaciones' => [

        est('Filas, columnas y celdas', 'La cuadrícula', '🔲', 'opcion_multiple', [
            omp('¿Cómo se llaman las líneas horizontales?', ['Filas', 'Columnas', 'Celdas'], 'Filas'),
            omp('¿Y las verticales?',                  ['Columnas', 'Filas', 'Hojas'], 'Columnas'),
            omp('¿Cómo se llama el cruce de una fila y una columna?', ['Celda', 'Tabla', 'Hoja'], 'Celda'),
            omp('¿Cómo se nombra una celda?',          ['Con la letra de la columna y el número de la fila, como B3', 'Solo con un número', 'Con su color'], 'Con la letra de la columna y el número de la fila, como B3'),
            omp('¿Para qué sirve una hoja de cálculo?', ['Para organizar datos y calcular con ellos', 'Para dibujar', 'Para escribir cuentos'], 'Para organizar datos y calcular con ellos'),
        ]),

        est('Las fórmulas', 'Que el computador haga la cuenta', '🧮', 'opcion_multiple', [
            omp('¿Con qué símbolo empieza una fórmula?', ['Con =', 'Con +', 'Con #'], 'Con ='),
            omp('¿Qué hace =A1+B1?',                   ['Suma el contenido de esas dos celdas', 'Escribe A1+B1', 'Borra las celdas'], 'Suma el contenido de esas dos celdas'),
            omp('¿Qué hace =SUMA(A1:A10)?',            ['Suma todas las celdas de A1 a A10', 'Suma solo dos', 'Cuenta las celdas'], 'Suma todas las celdas de A1 a A10'),
            omp('¿Qué pasa si cambio un número de una celda usada en una fórmula?', ['El resultado se actualiza solo', 'Nada', 'Hay que rehacerla'], 'El resultado se actualiza solo'),
            omp('¿Qué ventaja tiene una fórmula frente a calcular a mano?', ['No se equivoca y se actualiza sola', 'Es más lenta', 'Ninguna'], 'No se equivoca y se actualiza sola'),
        ]),

        est('Ordenar y graficar', 'Ver los datos', '📊', 'opcion_multiple', [
            omp('¿Para qué sirve ordenar una columna?', ['Para ver los mayores o menores de un vistazo', 'Para borrarla', 'Para pintarla'], 'Para ver los mayores o menores de un vistazo'),
            omp('¿Qué es filtrar?',                    ['Mostrar solo las filas que cumplen algo', 'Borrar filas', 'Sumar'], 'Mostrar solo las filas que cumplen algo'),
            omp('¿Se puede hacer una gráfica desde una tabla?', ['Sí, con unos clics', 'No', 'Solo a mano'], 'Sí, con unos clics'),
            omp('¿Qué debe llevar la gráfica que genero?', ['Título y ejes claros', 'Solo colores', 'Nada'], 'Título y ejes claros'),
            omp('¿Para qué sirve la primera fila normalmente?', ['Para los títulos de cada columna', 'Para el total', 'Para nada'], 'Para los títulos de cada columna'),
        ]),

        est('Desafío de la hoja', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('Si A1=5 y A2=3, ¿cuánto da =A1*A2?', ['15', '8', '53'], '15'),
            reto('Si A1=10 y A2=2, ¿cuánto da =A1/A2?', ['5', '12', '20'], '5'),
            reto('¿Qué celda está en la columna C, fila 4?', ['C4', '4C', 'CD4'], 'C4'),
            reto('¿Qué pasa si escribo A1+B1 sin el signo igual?', ['Se queda como texto', 'Calcula igual', 'Da error'], 'Se queda como texto'),
            reto('¿Para qué sirve una hoja de cálculo en la vida real?', ['Llevar cuentas, notas, inventarios', 'Solo para el colegio', 'Para jugar'], 'Llevar cuentas, notas, inventarios'),
        ]),
    ],
],

[
    'slug'  => 'inteligencia-artificial',
    'title' => 'Inteligencia artificial',
    'description' => 'Qué es una IA, qué puede hacer, en qué se equivoca y por qué hay que revisarla.',
    'objective' => 'Comprender qué es la inteligencia artificial y usarla de forma crítica y honesta.',
    'icon' => '🤖', 'nivel' => 'primaria-superior', 'bloque' => 'ciudadania-digital',
    'duracion' => 13, 'tags' => ['tecnologia', 'deduccion', 'comprension'],
    'estaciones' => [

        est('¿Qué es una IA?', 'Ni magia ni cerebro', '💭', 'opcion_multiple', [
            omp('¿Qué es la inteligencia artificial?',   ['Programas que aprenden patrones a partir de muchos datos', 'Un robot con sentimientos', 'Magia'], 'Programas que aprenden patrones a partir de muchos datos'),
            omp('¿Piensa una IA como una persona?',      ['No, calcula probabilidades a partir de patrones', 'Sí, igual', 'Piensa mejor'], 'No, calcula probabilidades a partir de patrones'),
            omp('¿De dónde saca lo que sabe?',           ['De los datos con los que fue entrenada', 'De su imaginación', 'De internet en vivo siempre'], 'De los datos con los que fue entrenada'),
            omp('¿Puede equivocarse una IA?',            ['Sí, y a veces con mucha seguridad', 'No', 'Solo en matemáticas'], 'Sí, y a veces con mucha seguridad'),
            omp('¿Siente una IA emociones?',             ['No', 'Sí', 'A veces'], 'No'),
        ]),

        est('Usarla bien', 'Herramienta, no atajo', '🛠️', 'opcion_multiple', [
            omp('Si una IA me hace la tarea entera, ¿qué aprendí?', ['Nada del tema', 'Todo', 'Más que estudiando'], 'Nada del tema'),
            omp('¿Para qué sí sirve bien una IA?',       ['Explicarme algo que no entendí o darme ideas', 'Reemplazar mi trabajo', 'Decidir por mí'], 'Explicarme algo que no entendí o darme ideas'),
            omp('¿Debo revisar lo que me responde?',     ['Sí, siempre', 'No', 'Solo si es largo'], 'Sí, siempre'),
            omp('¿Está bien entregar como propio un texto que escribió una IA?', ['No, hay que decirlo', 'Sí', 'Si lo edito un poco, sí'], 'No, hay que decirlo'),
            omp('Si la IA dice algo que contradice mi libro, ¿qué hago?', ['Compruebo en una fuente confiable', 'Le creo a la IA', 'Le creo al libro sin mirar'], 'Compruebo en una fuente confiable'),
        ]),

        est('Datos y privacidad', 'Lo que le cuento a una máquina', '🔐', 'opcion_multiple', [
            omp('¿Debo darle mis datos personales a una IA?', ['No', 'Sí', 'Solo el nombre'], 'No'),
            omp('¿Qué pasa con lo que escribo en algunos servicios?', ['Puede quedar guardado', 'Se borra siempre', 'Nadie lo ve nunca'], 'Puede quedar guardado'),
            omp('¿Puede una IA generar imágenes falsas de personas reales?', ['Sí, y por eso hay que desconfiar de lo que se ve', 'No', 'Solo dibujos'], 'Sí, y por eso hay que desconfiar de lo que se ve'),
            omp('Si veo un video increíble de alguien famoso, conviene…', ['Comprobar si es real antes de compartirlo', 'Compartirlo ya', 'Creerlo'], 'Comprobar si es real antes de compartirlo'),
            omp('¿Reemplaza la IA a los profesores?',    ['No, es una herramienta más', 'Sí', 'Pronto sí'], 'No, es una herramienta más'),
        ]),

        est('Desafío de la IA', 'Cinco decisiones', '🏆', 'desafio_final', [
            reto('La IA me da un dato que suena raro. ¿Qué hago?', ['Lo verifico', 'Lo uso igual', 'Lo comparto'], 'Lo verifico'),
            reto('¿Puede una IA saber algo que pasó ayer?', ['No siempre: depende de sus datos', 'Sí, todo', 'Nunca nada'], 'No siempre: depende de sus datos'),
            reto('¿Qué es más útil: pedirle la respuesta o pedirle que me explique?', ['Que me explique', 'La respuesta', 'Da igual'], 'Que me explique'),
            reto('¿Qué es un sesgo en una IA?',          ['Un error heredado de los datos con que aprendió', 'Un virus', 'Un idioma'], 'Un error heredado de los datos con que aprendió'),
            reto('¿Quién es responsable de lo que entrego?', ['Yo', 'La IA', 'Nadie'], 'Yo'),
        ]),
    ],
],


[
    'slug'  => 'como-funciona-internet',
    'title' => '¿Cómo funciona internet?',
    'description' => 'Qué pasa realmente cuando abres una página: redes, servidores y direcciones.',
    'objective' => 'Comprender de forma básica la arquitectura de internet y el papel de sus componentes.',
    'icon' => '🌐', 'nivel' => 'primaria-media', 'bloque' => 'algoritmos-y-codigo',
    'duracion' => 12, 'tags' => ['tecnologia', 'comprension', 'logica'],
    'estaciones' => [

        est('Qué es una red', 'Computadores conectados', '🔗', 'opcion_multiple', [
            omp('¿Qué es internet?',                ['Una red enorme de computadores conectados', 'Un programa', 'Una empresa'], 'Una red enorme de computadores conectados'),
            omp('¿Qué es un servidor?',             ['Un computador que guarda y entrega información a otros', 'Un cable', 'Una pantalla'], 'Un computador que guarda y entrega información a otros'),
            omp('Cuando abro una página, ¿qué pasa?', ['Mi dispositivo se la pide a un servidor', 'Aparece de la nada', 'La crea mi computador'], 'Mi dispositivo se la pide a un servidor'),
            omp('¿Es lo mismo internet que la web?', ['No, la web es un servicio dentro de internet', 'Sí', 'La web es más grande'], 'No, la web es un servicio dentro de internet'),
            omp('¿Qué es el wifi?',                 ['Una forma de conectarse a la red sin cables', 'Internet mismo', 'Un programa'], 'Una forma de conectarse a la red sin cables'),
        ]),

        est('Direcciones y enlaces', 'Cómo se encuentra cada cosa', '🔎', 'opcion_multiple', [
            omp('¿Qué es una URL?',                 ['La dirección de una página web', 'Un archivo', 'Un virus'], 'La dirección de una página web'),
            omp('¿Qué significa el candado en la barra de direcciones?', ['Que la conexión está cifrada', 'Que la página es buena', 'Que hay que pagar'], 'Que la conexión está cifrada', '🔒'),
            omp('¿Qué es un enlace o hipervínculo?', ['Un texto o imagen que lleva a otra página', 'Un error', 'Una imagen'], 'Un texto o imagen que lleva a otra página'),
            omp('¿Debo hacer clic en cualquier enlace que me llegue?', ['No, solo en los de confianza', 'Sí', 'Solo los azules'], 'No, solo en los de confianza'),
            omp('¿Qué es descargar un archivo?',    ['Traerlo desde internet a mi dispositivo', 'Borrarlo', 'Enviarlo'], 'Traerlo desde internet a mi dispositivo'),
        ]),

        est('La nube', 'Ni mágica ni en el cielo', '☁️', 'opcion_multiple', [
            omp('¿Qué es «la nube»?',               ['Servidores de otra empresa donde se guardan mis archivos', 'Una nube de verdad', 'Mi disco duro'], 'Servidores de otra empresa donde se guardan mis archivos'),
            omp('¿Qué ventaja tiene guardar en la nube?', ['Puedo abrirlo desde cualquier dispositivo', 'Pesa menos', 'Es más bonito'], 'Puedo abrirlo desde cualquier dispositivo'),
            omp('¿Qué desventaja tiene?',           ['Necesito conexión y confiar en el servicio', 'Ninguna', 'Es lenta siempre'], 'Necesito conexión y confiar en el servicio'),
            omp('Si borro un archivo de la nube desde mi celular…', ['Se borra en todos mis dispositivos', 'Solo en el celular', 'No se borra'], 'Se borra en todos mis dispositivos'),
            omp('¿Conviene tener copia de lo importante en otro sitio?', ['Sí, siempre', 'No', 'Solo fotos'], 'Sí, siempre'),
        ]),

        est('Crucigrama de la red', 'Cada pista es un término de internet', '🔠', 'crucigrama',
            crucigrama([
                ['w' => 'SERVIDOR',  'pista' => 'Computador que guarda y entrega páginas a otros'],
                ['w' => 'ENLACE',    'pista' => 'Texto o imagen que lleva a otra página'],
                ['w' => 'NUBE',      'pista' => 'Donde se guardan archivos fuera de tu dispositivo'],
                ['w' => 'RED',       'pista' => 'Conjunto de computadores conectados'],
                ['w' => 'CLAVE',     'pista' => 'Lo que no se comparte con nadie'],
                ['w' => 'NAVEGADOR', 'pista' => 'Programa con el que se ven las páginas web'],
                ['w' => 'WIFI',      'pista' => 'Conexión a la red sin cables'],
            ])),

        est('Desafío de la red', 'Cinco preguntas', '🏆', 'desafio_final', [
            reto('¿Quién es dueño de internet?',    ['Nadie: es una red de redes', 'Una empresa', 'Un país'], 'Nadie: es una red de redes'),
            reto('¿Qué es un navegador?',           ['Un programa para ver páginas web', 'Internet', 'Un buscador'], 'Un programa para ver páginas web'),
            reto('¿Es lo mismo un navegador que un buscador?', ['No: el buscador es una página dentro del navegador', 'Sí', 'El buscador es más grande'], 'No: el buscador es una página dentro del navegador'),
            reto('¿Qué pasa si se corta la conexión?', ['No puedo pedir información a los servidores', 'Se borra todo', 'Nada'], 'No puedo pedir información a los servidores'),
            reto('¿Viaja la información al instante?', ['Muy rápido, pero no instantáneo', 'Sí, instantáneo', 'Tarda horas'], 'Muy rápido, pero no instantáneo'),
        ]),
    ],
],

],

'reasignar' => [],

];
