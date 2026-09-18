<?php
/**
 * valores.php — Valores y Convivencia
 *
 * Era la categoría más flaca del catálogo: una sola actividad. Los tres
 * bloques van de dentro hacia afuera — primero reconocer lo que siento,
 * después convivir con los de al lado, y por último respetar a quien es
 * distinto de mí.
 *
 * El límite con «Vida y Bienestar» se sostiene así:
 *     Valores y Convivencia = mi relación con los demás
 *     Vida y Bienestar      = mi relación conmigo mismo y con mi entorno
 * Por eso «cuando me enojo» está aquí (lo que hago con los otros) y
 * «mis emociones» del otro lado trata de reconocerlas en mí.
 *
 * Nota de contenido: el bloque de matoneo describe qué hacer y a quién
 * acudir. No juzga a nadie ni pone al niño a resolver solo algo que le
 * corresponde a un adulto.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'valores',
    'name'       => 'Valores y Convivencia',
    'tagline'    => 'Reconocer lo que siento, convivir y respetar al que es distinto',
    'icon'       => '🤝',
    'color'      => '#ef5350',
    'sort_order' => 6,
],

'bloques' => [
    ['slug' => 'mis-emociones', 'name' => 'Mis Emociones', 'icon' => '💛', 'sort_order' => 1,
     'description' => 'Ponerle nombre a lo que siento y saber qué hacer con ello.'],
    ['slug' => 'vivir-juntos', 'name' => 'Vivir Juntos', 'icon' => '🤝', 'sort_order' => 2,
     'description' => 'Normas, acuerdos y cómo resolver un problema sin pelear.'],
    ['slug' => 'respeto-y-diferencia', 'name' => 'Respeto y Diferencia', 'icon' => '🌈', 'sort_order' => 3,
     'description' => 'Nadie es igual a otro, y eso no es un problema que resolver.'],
],

'reasignar' => [
    'teatro-de-los-valores' => 'vivir-juntos',
],

'actividades' => [


// ── MIS EMOCIONES ────────────────────────────────────────────────────

[
    'slug'  => 'conoce-tus-emociones',
    'title' => 'Conoce tus emociones',
    'description' => 'Alegría, tristeza, rabia, miedo: aprende a reconocerlas y nombrarlas.',
    'objective' => 'Identificar emociones básicas en gestos y situaciones, y nombrarlas.',
    'icon' => '💛', 'nivel' => 'preescolar', 'bloque' => 'mis-emociones',
    'duracion' => 10, 'tags' => ['emociones', 'observacion', 'vocabulario'],
    'estaciones' => [

        est('¿Qué siente?', 'Mira la cara y adivina', '😀', 'opcion_multiple', [
            omp('¿Qué siente?', ['Alegría', 'Tristeza', 'Rabia'], 'Alegría', '😀'),
            omp('¿Qué siente?', ['Tristeza', 'Alegría', 'Sorpresa'], 'Tristeza', '😢'),
            omp('¿Qué siente?', ['Rabia', 'Alegría', 'Miedo'], 'Rabia', '😠'),
            omp('¿Qué siente?', ['Miedo', 'Alegría', 'Rabia'], 'Miedo', '😨'),
            omp('¿Qué siente?', ['Sorpresa', 'Tristeza', 'Rabia'], 'Sorpresa', '😮'),
            omp('¿Qué siente?', ['Cansancio', 'Rabia', 'Alegría'], 'Cansancio', '😴'),
        ]),

        est('¿Cómo me sentiría?', 'Cada situación trae una emoción', '💭', 'opcion_multiple', [
            omp('Me regalaron lo que quería. Me siento…',    ['Feliz', 'Triste', 'Bravo'], 'Feliz', '🎁'),
            omp('Se me perdió mi juguete favorito. Me siento…', ['Triste', 'Feliz', 'Sorprendido'], 'Triste', '🧸'),
            omp('Alguien me quitó algo sin pedir. Me siento…', ['Bravo', 'Feliz', 'Con sueño'], 'Bravo', '😠'),
            omp('Oigo un ruido fuerte en la oscuridad. Siento…', ['Miedo', 'Alegría', 'Hambre'], 'Miedo', '🌑'),
            omp('Mi amigo volvió después de mucho tiempo. Siento…', ['Alegría', 'Rabia', 'Miedo'], 'Alegría', '🤗'),
        ]),

        est('Todas valen', 'No hay emociones malas', '💚', 'opcion_multiple', [
            omp('¿Está bien sentir rabia?',      ['Sí, lo importante es qué hago con ella', 'No, nunca', 'Solo los adultos'], 'Sí, lo importante es qué hago con ella', '😠'),
            omp('¿Está bien llorar?',            ['Sí', 'No', 'Solo los bebés'], 'Sí', '😢'),
            omp('Si siento algo feo, puedo…',    ['Contárselo a alguien de confianza', 'Guardármelo siempre', 'Fingir'], 'Contárselo a alguien de confianza', '🗣️'),
            omp('Las emociones…',                ['Van y vienen', 'Duran para siempre', 'No existen'], 'Van y vienen', '🌊'),
        ]),

        est('Memoria de las caras', 'Encuentra las parejas', '🧠', 'memoria',
            ['😀', '😢', '😠', '😨', '😮', '😴']),
    ],
],

[
    'slug'  => 'cuando-me-enojo',
    'title' => 'Cuando me enojo',
    'description' => 'La rabia se siente en el cuerpo. Aprende a notarla y a calmarte.',
    'objective' => 'Reconocer señales corporales de la rabia y practicar estrategias de autorregulación.',
    'icon' => '😤', 'nivel' => 'preescolar', 'bloque' => 'mis-emociones',
    'duracion' => 10, 'tags' => ['emociones', 'autocuidado', 'convivencia'],
    'estaciones' => [

        est('La rabia en el cuerpo', '¿Dónde la sientes?', '🫀', 'opcion_multiple', [
            omp('Cuando me da rabia, mi corazón…',  ['Late más rápido', 'Se detiene', 'No cambia'], 'Late más rápido', '🫀'),
            omp('Cuando me da rabia, mis manos…',   ['Se aprietan', 'Se enfrían siempre', 'Desaparecen'], 'Se aprietan', '✊'),
            omp('Cuando me da rabia, mi respiración…', ['Se acelera', 'Se detiene', 'No cambia'], 'Se acelera', '💨'),
            omp('Notar esas señales sirve para…',   ['Darme cuenta antes de estallar', 'Nada', 'Enojarme más'], 'Darme cuenta antes de estallar', '💡'),
        ]),

        est('El semáforo de la calma', 'Rojo, amarillo, verde', '🚦', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos del semáforo de la calma',
            'items' => [
                '🔴 Rojo: me detengo',
                '🟡 Amarillo: respiro y pienso',
                '🟢 Verde: actúo con calma',
            ],
        ]),

        est('¿Qué hago?', 'Elige la salida que no lastima', '🤔', 'opcion_multiple', [
            omp('Me quitaron el turno. ¿Qué hago?',      ['Digo que me molestó', 'Empujo', 'Grito'], 'Digo que me molestó', '🗣️'),
            omp('Estoy a punto de estallar. ¿Qué hago?', ['Respiro hondo tres veces', 'Pego', 'Rompo algo'], 'Respiro hondo tres veces', '😮‍💨'),
            omp('Un amigo me hizo enojar. ¿Qué hago?',   ['Hablo con él cuando esté calmado', 'Lo insulto', 'No le vuelvo a hablar nunca'], 'Hablo con él cuando esté calmado', '🤝'),
            omp('Si no me calmo solo, puedo…',           ['Pedir ayuda a un adulto', 'Aguantar callado', 'Esconderme siempre'], 'Pedir ayuda a un adulto', '🧑'),
            omp('Después de calmarme, es bueno…',        ['Arreglar lo que pasó', 'Olvidarlo sin más', 'Enojarme otra vez'], 'Arreglar lo que pasó', '🔧'),
        ]),
    ],
],

[
    'slug'  => 'pedir-y-dar-ayuda',
    'title' => 'Pedir y dar ayuda',
    'description' => 'Nadie puede solo con todo, y ayudar también se aprende.',
    'objective' => 'Reconocer cuándo pedir ayuda, a quién, y cómo ofrecerla a otros.',
    'icon' => '🤲', 'nivel' => 'preescolar', 'bloque' => 'mis-emociones',
    'duracion' => 10, 'tags' => ['convivencia', 'emociones', 'seguridad'],
    'estaciones' => [

        est('¿A quién le pido ayuda?', 'Adultos de confianza', '🧑', 'opcion_multiple', [
            omp('Me perdí en un centro comercial. Le pido ayuda a…', ['Un guardia o alguien del almacén', 'Cualquiera que pase', 'A nadie'], 'Un guardia o alguien del almacén', '🏬'),
            omp('Me siento mal en el colegio. Le digo a…', ['Mi profesora', 'A nadie', 'Solo a un amigo'], 'Mi profesora', '🏫'),
            omp('Un adulto de confianza es…',   ['Alguien que me cuida y en quien confío', 'Cualquier persona grande', 'Un desconocido amable'], 'Alguien que me cuida y en quien confío', '💚'),
            omp('Pedir ayuda es señal de…',     ['Buen juicio', 'Debilidad', 'Miedo'], 'Buen juicio', '💪'),
        ]),

        est('¿Cómo ayudo?', 'Ayudar bien también tiene su forma', '🤲', 'opcion_multiple', [
            omp('Un compañero se cayó. ¿Qué hago?', ['Aviso a un adulto y lo acompaño', 'Me río', 'Sigo jugando'], 'Aviso a un adulto y lo acompaño', '🤕'),
            omp('Alguien no entiende la tarea. Puedo…', ['Explicarle con paciencia', 'Hacérsela toda', 'Burlarme'], 'Explicarle con paciencia', '📓'),
            omp('Un niño nuevo está solo en el descanso. Puedo…', ['Invitarlo a jugar', 'Ignorarlo', 'Señalarlo'], 'Invitarlo a jugar', '👋'),
            omp('Antes de ayudar a alguien conviene…', ['Preguntarle si quiere ayuda', 'Hacerlo sin avisar', 'Nada'], 'Preguntarle si quiere ayuda', '❓'),
            omp('Ayudar a alguien con discapacidad significa…', ['Preguntar qué necesita, no decidir por él', 'Hacer todo por él', 'Ignorarlo'], 'Preguntar qué necesita, no decidir por él', '♿'),
        ]),

        est('Palabras que ayudan', 'Cuatro que abren puertas', '🗝️', 'ordenar_secuencia', [
            'title' => 'Ordena la frase para pedir algo con amabilidad',
            'items' => ['Hola,', '¿me ayudas', 'con esto,', 'por favor?'],
        ]),
    ],
],


// ── VIVIR JUNTOS ─────────────────────────────────────────────────────

[
    'slug'  => 'las-normas-del-salon',
    'title' => 'Las normas del salón',
    'description' => 'Por qué existen las reglas y qué pasa cuando nadie las cumple.',
    'objective' => 'Comprender la función de las normas y distinguir acuerdos justos de arbitrarios.',
    'icon' => '📜', 'nivel' => 'primaria-inicial', 'bloque' => 'vivir-juntos',
    'duracion' => 12, 'tags' => ['convivencia', 'ciudadania', 'logica'],
    'estaciones' => [

        est('¿Para qué sirven las normas?', 'No son castigos', '📜', 'opcion_multiple', [
            omp('Las normas del salón sirven para…', ['Que todos puedan aprender bien', 'Castigar', 'Aburrir'], 'Que todos puedan aprender bien', '🏫'),
            omp('«Levantar la mano para hablar» sirve para…', ['Que todos alcancen a escuchar', 'Cansar el brazo', 'Nada'], 'Que todos alcancen a escuchar', '✋'),
            omp('Si nadie cumple las normas…',      ['Nadie puede trabajar tranquilo', 'Todo mejora', 'Da igual'], 'Nadie puede trabajar tranquilo', '🌀'),
            omp('Una buena norma es…',              ['Clara y para todos igual', 'Secreta', 'Solo para algunos'], 'Clara y para todos igual', '⚖️'),
            omp('Las normas se pueden…',            ['Revisar y cambiar entre todos', 'Nunca cambiar', 'Ignorar'], 'Revisar y cambiar entre todos', '🔄'),
        ]),

        est('¿Justa o injusta?', 'Piensa si aplica para todos', '⚖️', 'opcion_multiple', [
            omp('«Todos guardamos nuestros útiles al terminar». Es…', ['Justa', 'Injusta'], 'Justa', '🎒'),
            omp('«Solo los niños salen a recreo, las niñas no». Es…', ['Injusta', 'Justa'], 'Injusta', '🚫'),
            omp('«Quien llegue tarde entra en silencio». Es…', ['Justa', 'Injusta'], 'Justa', '🚪'),
            omp('«El que se sienta adelante manda». Es…', ['Injusta', 'Justa'], 'Injusta', '🪑'),
            omp('«Todos ayudamos a recoger el salón». Es…', ['Justa', 'Injusta'], 'Justa', '🧹'),
        ]),

        est('Construir un acuerdo', 'Así se decide entre todos', '🗳️', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para acordar una norma del salón',
            'items' => [
                '1️⃣ Notar un problema que afecta a todos',
                '2️⃣ Hablarlo en grupo',
                '3️⃣ Proponer soluciones',
                '4️⃣ Elegir una entre todos',
                '5️⃣ Escribirla y ponerla a la vista',
                '6️⃣ Revisar después si funcionó',
            ],
        ]),
    ],
],

[
    'slug'  => 'resolver-sin-pelear',
    'title' => 'Resolver sin pelear',
    'description' => 'Un conflicto no es una pelea. Aprende a resolverlo hablando.',
    'objective' => 'Aplicar pasos de resolución pacífica de conflictos y expresarse sin agredir.',
    'icon' => '🕊️', 'nivel' => 'primaria-inicial', 'bloque' => 'vivir-juntos',
    'duracion' => 12, 'tags' => ['convivencia', 'emociones', 'logica'],
    'estaciones' => [

        est('Conflicto no es pelea', 'Se pueden separar', '🤔', 'opcion_multiple', [
            omp('Un conflicto es…',        ['Que dos quieran cosas distintas', 'Una pelea a golpes', 'Un castigo'], 'Que dos quieran cosas distintas', '↔️'),
            omp('¿Se pueden tener conflictos con un amigo?', ['Sí, y se arreglan', 'No, entonces no es amigo', 'Nunca'], 'Sí, y se arreglan', '🤝'),
            omp('La pelea aparece cuando…', ['No sabemos resolver el conflicto', 'Siempre', 'Nunca'], 'No sabemos resolver el conflicto', '💥'),
            omp('Lo primero para resolver algo es…', ['Calmarse', 'Ganar', 'Gritar más'], 'Calmarse', '😮‍💨'),
        ]),

        est('Hablar en primera persona', 'Decir lo que siento sin acusar', '🗣️', 'opcion_multiple', [
            omp('¿Cuál frase acusa menos?', ['«Me sentí mal cuando pasó eso»', '«Tú siempre me haces lo mismo»', '«Eres insoportable»'], '«Me sentí mal cuando pasó eso»', '💬'),
            omp('¿Cuál frase acusa menos?', ['«Necesito que me esperes»', '«Nunca me esperas»', '«Eres egoísta»'], '«Necesito que me esperes»', '💬'),
            omp('Decir «siempre» y «nunca» en una discusión…', ['Empeora las cosas', 'Ayuda', 'Da igual'], 'Empeora las cosas', '⚠️'),
            omp('Escuchar al otro sirve para…', ['Entender qué le pasó', 'Perder', 'Ganar tiempo'], 'Entender qué le pasó', '👂'),
            omp('Pedir perdón de verdad incluye…', ['Reconocer qué hice y repararlo', 'Solo decir «perdón»', 'Culpar al otro'], 'Reconocer qué hice y repararlo', '🙏'),
        ]),

        est('Los pasos', 'Ordena cómo se resuelve', '🕊️', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para resolver un conflicto',
            'items' => [
                '1️⃣ Calmarse los dos',
                '2️⃣ Cada uno cuenta qué pasó',
                '3️⃣ Cada uno escucha sin interrumpir',
                '4️⃣ Buscar una solución que sirva a ambos',
                '5️⃣ Cumplir lo acordado',
            ],
        ]),

        est('¿Qué harías?', 'Situaciones del descanso', '🏫', 'opcion_multiple', [
            omp('Dos quieren el mismo balón. Lo mejor es…', ['Acordar turnos', 'Que se lo lleve el más fuerte', 'Esconderlo'], 'Acordar turnos', '⚽'),
            omp('Alguien te empujó sin querer y se disculpó. Puedes…', ['Aceptar la disculpa', 'Empujarlo igual', 'Acusarlo'], 'Aceptar la disculpa', '🙂'),
            omp('Una discusión se está saliendo de control. Conviene…', ['Buscar a un adulto', 'Subir el tono', 'Llamar más gente'], 'Buscar a un adulto', '🧑'),
            omp('Un amigo te contó un secreto y otro te lo pregunta. Debes…', ['Guardar el secreto', 'Contarlo', 'Cambiarlo'], 'Guardar el secreto', '🤐'),
        ]),
    ],
],

[
    'slug'  => 'el-valor-de-la-palabra',
    'title' => 'El valor de la palabra',
    'description' => 'Decir la verdad, cumplir lo prometido y hacerse cargo de un error.',
    'objective' => 'Valorar la honestidad y la responsabilidad como base de la confianza.',
    'icon' => '🤞', 'nivel' => 'primaria-inicial', 'bloque' => 'vivir-juntos',
    'duracion' => 12, 'tags' => ['convivencia', 'ciudadania', 'logica'],
    'estaciones' => [

        est('Honestidad', 'La verdad cuesta menos a la larga', '🫱', 'opcion_multiple', [
            omp('Rompí algo sin querer. Lo mejor es…',   ['Decirlo', 'Esconderlo', 'Culpar a otro'], 'Decirlo', '🏺'),
            omp('Encontré una billetera en el parque. Debo…', ['Entregarla a un adulto', 'Quedármela', 'Botarla'], 'Entregarla a un adulto', '👛'),
            omp('Copiar en un examen es…',   ['Engañar, aunque nadie se dé cuenta', 'Ser vivo', 'Normal'], 'Engañar, aunque nadie se dé cuenta', '📝'),
            omp('Si miento y me descubren, lo que más se daña es…', ['La confianza', 'Mi cuaderno', 'Mi ropa'], 'La confianza', '💔'),
            omp('Recuperar la confianza toma…', ['Tiempo y hechos', 'Un segundo', 'Nada'], 'Tiempo y hechos', '⏳'),
        ]),

        est('Cumplir lo prometido', 'Una promesa es un compromiso', '🤞', 'opcion_multiple', [
            omp('Prometí ayudar y me dio pereza. Debo…', ['Cumplir o avisar a tiempo', 'Desaparecer', 'Nada'], 'Cumplir o avisar a tiempo', '⏰'),
            omp('Antes de prometer algo conviene…',  ['Pensar si de verdad puedo', 'Decir que sí a todo', 'No pensar'], 'Pensar si de verdad puedo', '🤔'),
            omp('Si no voy a poder cumplir, lo correcto es…', ['Avisar cuanto antes', 'Callarme', 'Inventar excusa'], 'Avisar cuanto antes', '📞'),
            omp('Alguien que cumple lo que dice se gana…', ['Confianza', 'Dinero', 'Silencio'], 'Confianza', '🤝'),
        ]),

        est('Hacerse cargo', 'Un error se repara', '🔧', 'cuento', [
            'slides' => [
                ['img' => '⚽', 'text' => 'Julián pateó el balón en el patio y rompió la ventana del salón de al lado.'],
                ['img' => '😰', 'text' => 'Nadie lo vio. Podía irse y nadie sabría que fue él.'],
                ['img' => '🤔', 'text' => 'Pensó en callar. Pero se imaginó a su profesora buscando al culpable entre todos.'],
                ['img' => '🗣️', 'text' => 'Fue a la coordinación y contó lo que pasó.'],
                ['img' => '🔧', 'text' => 'Le pidieron ayudar a arreglar el daño. No fue agradable, pero nadie más pagó por él.'],
            ],
            'qs' => [
                reto('¿Qué habría pasado si Julián callaba?', ['Buscarían al culpable entre todos', 'No pasaría nada', 'Se arreglaría solo'], 'Buscarían al culpable entre todos'),
                reto('¿Por qué decidió contarlo?', ['Para que nadie más pagara por su error', 'Porque lo vieron', 'Por miedo al castigo'], 'Para que nadie más pagara por su error'),
                reto('Hacerse cargo de un error significa…', ['Reconocerlo y repararlo', 'Pedir perdón y ya', 'Olvidarlo'], 'Reconocerlo y repararlo'),
                reto('Lo más difícil de la historia fue…', ['Decidir contarlo', 'Romper la ventana', 'Jugar fútbol'], 'Decidir contarlo'),
            ],
        ]),
    ],
],


// ── RESPETO Y DIFERENCIA ─────────────────────────────────────────────

[
    'slug'  => 'todos-somos-diferentes',
    'title' => 'Todos somos diferentes',
    'description' => 'Culturas, capacidades y familias distintas: la diferencia no es un defecto.',
    'objective' => 'Valorar la diversidad y reconocer estereotipos y prejuicios frecuentes.',
    'icon' => '🌈', 'nivel' => 'primaria-media', 'bloque' => 'respeto-y-diferencia',
    'duracion' => 15, 'tags' => ['convivencia', 'cultura', 'ciudadania'],
    'estaciones' => [

        est('Diferentes y iguales', 'En qué somos distintos y en qué no', '🌈', 'opcion_multiple', [
            omp('Las personas se diferencian en…',   ['Muchas cosas, y está bien', 'Nada', 'Solo la altura'], 'Muchas cosas, y está bien', '👥'),
            omp('Todos tenemos los mismos…',         ['Derechos', 'Gustos', 'Talentos'], 'Derechos', '⚖️'),
            omp('Una familia puede estar formada por…', ['Muchas combinaciones distintas', 'Solo papá, mamá e hijos', 'Solo abuelos'], 'Muchas combinaciones distintas', '👨‍👩‍👧'),
            omp('Alguien que habla otra lengua en casa…', ['Tiene una riqueza más', 'Habla mal', 'Debe cambiar'], 'Tiene una riqueza más', '🗣️'),
            omp('Una persona con discapacidad…',     ['Tiene los mismos derechos que todos', 'Necesita lástima', 'No puede aprender'], 'Tiene los mismos derechos que todos', '♿'),
        ]),

        est('Estereotipos', 'Frases que parecen normales y no lo son', '🚫', 'opcion_multiple', [
            omp('«Los niños no lloran» es…',              ['Un estereotipo', 'Una verdad'], 'Un estereotipo', '😢'),
            omp('«Las niñas no juegan fútbol» es…',       ['Un estereotipo', 'Una verdad'], 'Un estereotipo', '⚽'),
            omp('«La gente de esa región es toda igual» es…', ['Un prejuicio', 'Un dato'], 'Un prejuicio', '🗺️'),
            omp('Un estereotipo es…',                     ['Meter a todos en un mismo molde', 'Un tipo de letra', 'Una norma'], 'Meter a todos en un mismo molde', '📦'),
            omp('Cuando oigo un estereotipo puedo…',      ['Cuestionarlo con respeto', 'Repetirlo', 'Reírme'], 'Cuestionarlo con respeto', '🗨️'),
        ]),

        est('Culturas del mundo', 'Distintas formas de vivir', '🌍', 'opcion_multiple', [
            omp('En Colombia se hablan…',    ['El español y muchas lenguas indígenas', 'Solo español', 'Solo inglés'], 'El español y muchas lenguas indígenas', '🇨🇴'),
            omp('Que un país tenga varias culturas lo hace…', ['Más rico', 'Más pobre', 'Igual'], 'Más rico', '🎭'),
            omp('Las tradiciones de una comunidad merecen…', ['Respeto', 'Burla', 'Olvido'], 'Respeto', '🪘'),
            omp('Conocer otra cultura sirve para…', ['Entender que hay más de una forma de vivir', 'Copiarla toda', 'Criticarla'], 'Entender que hay más de una forma de vivir', '🌏'),
        ]),
    ],
],

[
    'slug'  => 'frente-al-matoneo',
    'title' => 'Frente al matoneo',
    'description' => 'Qué es, cómo se reconoce y qué hacer si te pasa o si lo ves.',
    'objective' => 'Reconocer situaciones de acoso escolar y saber a quién acudir y cómo actuar.',
    'icon' => '🛡️', 'nivel' => 'primaria-media', 'bloque' => 'respeto-y-diferencia',
    'duracion' => 15, 'tags' => ['convivencia', 'seguridad', 'emociones', 'reto'],
    'estaciones' => [

        est('¿Qué es el matoneo?', 'No es lo mismo que un conflicto', '🛡️', 'opcion_multiple', [
            omp('El matoneo es…',   ['Agredir a alguien una y otra vez a propósito', 'Un desacuerdo cualquiera', 'Un juego'], 'Agredir a alguien una y otra vez a propósito', '⚠️'),
            omp('Una diferencia entre conflicto y matoneo es que el matoneo…', ['Se repite y hay desequilibrio de poder', 'Dura un día', 'Es entre iguales'], 'Se repite y hay desequilibrio de poder', '⚖️'),
            omp('Poner apodos hirientes todos los días es…', ['Matoneo', 'Un chiste', 'Cariño'], 'Matoneo', '🗯️'),
            omp('Dejar a alguien por fuera del grupo a propósito y siempre es…', ['Matoneo', 'Normal', 'Su culpa'], 'Matoneo', '🚷'),
            omp('El matoneo por internet se llama…', ['Ciberacoso', 'Correo', 'Chat'], 'Ciberacoso', '📱'),
        ]),

        est('Si me pasa a mí', 'No es tu culpa y no estás solo', '💚', 'opcion_multiple', [
            omp('Si me hacen matoneo, la culpa es…',  ['De quien agrede, nunca mía', 'Mía', 'De nadie'], 'De quien agrede, nunca mía', '💚'),
            omp('Lo primero que debo hacer es…',      ['Contárselo a un adulto de confianza', 'Aguantar', 'Vengarme'], 'Contárselo a un adulto de confianza', '🗣️'),
            omp('Si es por internet, conviene…',      ['Guardar capturas y no responder', 'Responder con otro insulto', 'Borrar todo'], 'Guardar capturas y no responder', '📸'),
            omp('Si el primer adulto no me ayuda…',   ['Busco a otro hasta que alguien actúe', 'Me rindo', 'Me callo'], 'Busco a otro hasta que alguien actúe', '🔁'),
            omp('Callar el matoneo hace que…',        ['Siga pasando', 'Se acabe solo', 'Mejore'], 'Siga pasando', '🤐'),
        ]),

        est('Si lo veo', 'Mirar y callar también cuenta', '👀', 'opcion_multiple', [
            omp('Veo que molestan a un compañero. Lo peor que puedo hacer es…', ['Reírme o callar', 'Avisar', 'Acompañarlo'], 'Reírme o callar', '😐'),
            omp('Reírse de la agresión…',      ['Le da fuerza al que agrede', 'No influye', 'Ayuda a la víctima'], 'Le da fuerza al que agrede', '😆'),
            omp('Algo seguro que puedo hacer es…', ['Avisar a un adulto', 'Enfrentarme yo solo', 'Grabar y publicar'], 'Avisar a un adulto', '🧑'),
            omp('Acompañar a quien está siendo agredido…', ['Ayuda mucho', 'No sirve', 'Empeora'], 'Ayuda mucho', '🤝'),
            omp('Avisar no es «sapear» porque…', ['Se trata de proteger a alguien', 'Sí es sapear', 'Da igual'], 'Se trata de proteger a alguien', '🛡️'),
        ]),

        est('Reto de convivencia', 'Piensa antes de responder', '🏆', 'desafio_final', [
            reto('Un compañero recibe apodos crueles a diario. Lo mejor que puedes hacer es…',
                 ['Acompañarlo y avisar a un adulto', 'Ignorarlo', 'Ponerle otro apodo'],
                 'Acompañarlo y avisar a un adulto'),
            reto('Alguien reenvía una foto para burlarse de otro. Tú…',
                 ['No la reenvías y lo reportas', 'La reenvías', 'La comentas'],
                 'No la reenvías y lo reportas'),
            reto('«Es que él se lo busca» es…',
                 ['Una excusa: nadie merece ser agredido', 'Una razón válida', 'Un hecho'],
                 'Una excusa: nadie merece ser agredido'),
            reto('El matoneo se detiene sobre todo cuando…',
                 ['El grupo deja de aplaudirlo y los adultos actúan', 'La víctima se defiende sola', 'Pasa el tiempo'],
                 'El grupo deja de aplaudirlo y los adultos actúan'),
        ]),
    ],
],

[
    'slug'  => 'cuidar-lo-de-todos',
    'title' => 'Cuidar lo de todos',
    'description' => 'El parque, el salón, el planeta: lo que es de todos también es tuyo.',
    'objective' => 'Comprender la noción de bien común y asumir responsabilidades con el entorno.',
    'icon' => '🌳', 'nivel' => 'primaria-media', 'bloque' => 'respeto-y-diferencia',
    'duracion' => 15, 'tags' => ['ciudadania', 'convivencia', 'clasificacion'],
    'estaciones' => [

        est('Lo que es de todos', 'El bien común', '🏞️', 'opcion_multiple', [
            omp('El parque del barrio es…',   ['De todos', 'De nadie', 'Del que llegue primero'], 'De todos', '🏞️'),
            omp('Rayar una pared pública afecta a…', ['Todo el vecindario', 'A nadie', 'Solo al dueño'], 'Todo el vecindario', '🧱'),
            omp('Los libros de la biblioteca son…', ['De todos y hay que devolverlos', 'Míos', 'De la profesora'], 'De todos y hay que devolverlos', '📚'),
            omp('Cuidar lo público es responsabilidad de…', ['Todos', 'Solo del gobierno', 'De nadie'], 'Todos', '🤝'),
            omp('Si veo que alguien daña algo público, puedo…', ['Avisar a un adulto o a la autoridad', 'Ayudarle', 'No mirar'], 'Avisar a un adulto o a la autoridad', '📢'),
        ]),

        est('Separar la basura', 'Cada cosa en su lugar', '♻️', 'opcion_multiple', [
            omp('Una botella plástica va en…', ['Reciclable', 'Orgánico', 'No aprovechable'], 'Reciclable', '🧴'),
            omp('Una cáscara de banano va en…', ['Orgánico', 'Reciclable', 'Vidrio'], 'Orgánico', '🍌'),
            omp('Una hoja de papel limpia va en…', ['Reciclable', 'Orgánico', 'No aprovechable'], 'Reciclable', '📄'),
            omp('Una servilleta usada va en…',  ['No aprovechable', 'Reciclable', 'Vidrio'], 'No aprovechable', '🧻'),
            omp('Reciclar sirve para…',         ['Que los materiales se vuelvan a usar', 'Llenar más basureros', 'Nada'], 'Que los materiales se vuelvan a usar', '♻️'),
        ]),

        est('Pequeñas acciones', 'Lo que sí está en tus manos', '💧', 'opcion_multiple', [
            omp('Cerrar la llave mientras me cepillo…', ['Ahorra mucha agua', 'No cambia nada', 'Gasta más'], 'Ahorra mucha agua', '🚰'),
            omp('Apagar la luz al salir de un cuarto…', ['Ahorra energía', 'Daña el bombillo', 'No sirve'], 'Ahorra energía', '💡'),
            omp('Llevar mi propia botella al colegio…', ['Reduce basura plástica', 'Da lo mismo', 'Contamina más'], 'Reduce basura plástica', '🍶'),
            omp('Si cada uno hace un poco…',           ['El efecto se suma', 'No pasa nada', 'Solo importa lo grande'], 'El efecto se suma', '➕'),
        ]),

        est('Toca lo reciclable', 'Solo lo que se puede reciclar', '♻️', 'seleccion_imagenes',
            conTitulo('Toca todo lo que se puede reciclar', 'Deja fuera lo que no', [
                ['e' => '🧴', 'n' => 'Botella plástica', 'ok' => true],
                ['e' => '📄', 'n' => 'Papel limpio',     'ok' => true],
                ['e' => '🥫', 'n' => 'Lata',             'ok' => true],
                ['e' => '📦', 'n' => 'Cartón',           'ok' => true],
                ['e' => '🍌', 'n' => 'Cáscara',          'ok' => false],
                ['e' => '🧻', 'n' => 'Servilleta usada', 'ok' => false],
                ['e' => '🩹', 'n' => 'Curita usada',     'ok' => false],
            ])),
    ],
],

],
];
