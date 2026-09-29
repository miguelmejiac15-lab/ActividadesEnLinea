<?php
/**
 * Aprender sin Barreras · 3.º a 6.º
 *
 * Aquí la introducción es todavía más corta y directa que en el resto:
 * frases breves, una idea por línea y nada de dobles sentidos. Estas
 * actividades existen justamente para quien necesita el camino claro.
 */

return [

    'frases-cortas' => intro(
        'Una **frase** dice algo completo. Casi siempre tiene **quién** (el gato) y **qué hace** (duerme). Si sabes esas dos cosas, entiendes la frase.',
        ['Busca **quién**: el gato, el niño.', 'Busca **qué hace**: duerme, come.', 'Mira el **dibujo**: te ayuda a comprobar.'],
        [
            completa('«El gato duerme». El gato ___.', 'duerme', ['come', 'corre']),
            identifica('«El niño come pan». ¿Quién come?', 'El niño', ['El pan', 'El gato']),
        ]
    ),

    'agenda-visual' => intro(
        'Una **agenda visual** muestra con dibujos lo que vas a hacer y en qué orden. Así no tienes que recordarlo todo de memoria: lo miras y lo sabes.',
        ['Los días van en orden: lunes, martes, miércoles…', 'Primero lo **importante**; después, lo demás.', 'Anota o dibuja las tareas en un lugar que **veas**.'],
        [
            completa('Después del lunes viene el ___.', 'martes', ['domingo', 'jueves']),
            identifica('Tengo tarea y quiero jugar. ¿Qué hago primero?', 'La tarea', ['Jugar', 'Dormir']),
        ]
    ),

    'numeros-con-apoyo-visual' => intro(
        'Los números dicen **cuántos** hay. Si ves los objetos dibujados, puedes **contarlos**, **compararlos** y **sumarlos** sin perderte.',
        ['**Mayor**: el que tiene más. **Menor**: el que tiene menos.', '**Sumar** es juntar. **Restar** es quitar.', 'Cuenta despacio, tocando cada dibujo.'],
        [
            completa('8 es ___ que 3.', 'mayor', ['menor', 'igual']),
            identifica('Tengo 5 y quito 2. ¿Cuántos quedan?', '3', ['7', '2']),
        ]
    ),

    'pedir-ayuda' => intro(
        '**Pedir ayuda** es decir lo que necesitas a alguien que puede ayudarte. No es un fracaso: es una forma inteligente de avanzar.',
        ['Pide ayuda si no entiendes, si te duele algo o si tienes miedo.', 'Busca a un **adulto de confianza**: profe, orientadora, familia.', 'Di **exactamente** qué necesitas: «No entiendo el paso dos».'],
        [
            completa('Si no entiendo la tarea, ___.', 'pregunto', ['me callo', 'la dejo']),
            identifica('¿Qué es mejor decir?', '«No entiendo el paso dos»', ['«No sé nada»', 'No decir nada']),
        ]
    ),

    'sigue-hasta-el-final' => intro(
        'Muchas tareas tienen **varios pasos**. Una tarea está terminada solo cuando haces **el último paso**. A veces nos olvidamos del final.',
        ['Piensa: ¿cuál es el **último** paso?', 'Revisa: ¿ya lo hice?', 'Ejemplo: lavar el plato → secarlo → **guardarlo**.'],
        [
            completa('Una tarea está terminada cuando hago el ___ paso.', 'último', ['primer', 'más fácil']),
            identifica('Sara hizo la tarea y la dejó en la mesa. ¿Qué le falta?', 'Meterla en la mochila', ['Nada', 'Hacerla otra vez']),
        ]
    ),

    'no-te-despistes' => intro(
        'A veces hay datos que **no sirven** para la pregunta. Están ahí para despistar. Concentrarse es elegir solo lo que **sirve**.',
        ['Lee la pregunta **primero**.', 'Busca solo los datos que responden la pregunta.', 'Lo demás, **ignóralo**.'],
        [
            completa('Para responder, uso solo los datos que ___.', 'sirven', ['brillan', 'son largos']),
            identifica('Ana tiene 4 canicas rojas, 3 azules y una camiseta verde. ¿Cuántas canicas tiene?', '7', ['8', '4']),
        ]
    ),

    'piensa-antes-de-tocar' => intro(
        'A veces la **primera** respuesta que se nos ocurre está **mal**. Por eso conviene **parar** un segundo y leer bien antes de responder. Es como un semáforo.',
        ['🔴 **Paro**.', '🟡 **Pienso** qué me están pidiendo.', '🟢 **Respondo**.'],
        [
            completa('Antes de responder, primero ___.', 'paro', ['grito', 'adivino']),
            identifica('Un granjero tiene 17 ovejas. Se mueren todas menos 9. ¿Cuántas quedan?', '9', ['8', '17']),
        ]
    ),

    'revisa-antes-de-entregar' => intro(
        '**Revisar** es volver a mirar tu trabajo antes de entregarlo. Así encuentras tus errores antes que nadie. Toma poco tiempo y evita repetir todo.',
        ['Termino → **releo despacio** → corrijo → entrego.', 'Busco palabras que faltan y cuentas mal hechas.', 'Revisar **no es desconfiar** de ti: es cuidarte.'],
        [
            completa('Antes de entregar, ___ mi trabajo.', 'reviso', ['rompo', 'escondo']),
            identifica('¿Cuál de estas cuentas está mal?', '3 + 4 = 8', ['2 + 2 = 4', '5 + 1 = 6']),
        ]
    ),

    'primero-esto-y-luego-aquello' => intro(
        'Una tarea **grande** asusta. Si la **partes en pasos pequeños** y los pones en orden, se vuelve fácil. Solo tienes que hacer un paso a la vez.',
        ['Primero: leer qué me piden.', 'Luego: hacer los pasos **en orden**.', 'Si hay varias tareas, empiezo por la **más urgente**.'],
        [
            completa('Una tarea grande se parte en pasos ___.', 'pequeños', ['gigantes', 'secretos']),
            identifica('Tienes tres tareas y una es para mañana. ¿Por cuál empiezas?', 'Por la de mañana', ['Por la más divertida', 'Por ninguna']),
        ]
    ),

    'me-acuerdo-de-todo' => intro(
        'La **memoria de trabajo** es la que usamos para guardar **dos o tres cosas** mientras las hacemos. Como un encargo: pan, leche y huevos.',
        ['**Repite** la lista en voz baja.', 'Tacha lo que ya hiciste.', 'Pregúntate: ¿**qué falta**?'],
        [
            completa('Mamá pidió pan, leche y huevos. Compré pan y leche. Faltan los ___.', 'huevos', ['panes', 'dulces']),
            identifica('¿Qué ayuda a no olvidar un encargo?', 'Repetirlo en voz baja', ['Pensar en otra cosa', 'Correr']),
        ]
    ),

    'pedir-lo-que-necesito' => intro(
        'Nadie sabe lo que te pasa por dentro si **no lo dices**. Pedir lo que necesitas con palabras tranquilas ayuda a que te ayuden.',
        ['Si hay mucho ruido: «¿Puedo salir un momento?».', 'Si no entendiste: «¿Me lo explica otra vez, por favor?».', 'Si te falta tiempo: «¿Puedo tener un poco más de tiempo?».'],
        [
            completa('Si no entendí, pido que me lo ___ otra vez.', 'expliquen', ['griten', 'escondan']),
            identifica('¿Qué funciona mejor?', 'Decirlo con palabras tranquilas', ['Gritar', 'No decir nada']),
        ]
    ),

    'que-le-esta-pasando' => intro(
        'Podemos saber **cómo se siente** alguien mirando **lo que le pasa**. Si no lo invitaron a una fiesta, quizá está triste. Si ganó algo, quizá está orgulloso.',
        ['Mira la **situación**: ¿qué le pasó?', 'Piensa: ¿cómo me sentiría yo?', 'Si no estás seguro, **pregúntale**.'],
        [
            completa('A Ana no la invitaron al cumpleaños. Se siente ___.', 'triste', ['feliz', 'orgullosa']),
            identifica('Un compañero está solo y mira al suelo. ¿Qué ayuda?', 'Preguntarle si quiere jugar', ['Reírse', 'Ignorarlo']),
        ]
    ),

    'mi-turno-tu-turno' => intro(
        'Una **conversación** es como un juego de turnos: uno habla y el otro **escucha**; luego cambian. Hay señales que dicen cuándo hablar y cuándo parar.',
        ['Espera a que el otro **termine** su frase.', 'Si mira el reloj o se aleja, quizá tiene **prisa**.', 'Escuchar de verdad es prestar atención, no solo esperar tu turno.'],
        [
            completa('En una conversación, primero escucho y luego ___.', 'hablo', ['me voy', 'grito']),
            identifica('Estoy hablando y el otro mira el reloj y se aleja. ¿Qué significa?', 'Que quizá tiene prisa', ['Que le encanta', 'Nada']),
        ]
    ),

    'cuando-algo-sale-mal' => intro(
        'Todo el mundo se **equivoca**. Equivocarse es parte de aprender. Lo importante es **qué haces después**.',
        ['**Respiro**.', 'Miro **qué salió mal**.', 'Lo **corrijo** o lo intento otra vez.'],
        [
            completa('Equivocarse ayuda a ___.', 'aprender', ['rendirse', 'esconderse']),
            identifica('Perdiste el juego tres veces. ¿Qué conviene?', 'Descansar y volver a intentarlo', ['Romper el juego', 'No jugar nunca más']),
        ]
    ),

    'no-siempre-es-literal' => intro(
        'Algunas frases **no significan lo que dicen sus palabras**. Se llaman **frases hechas**. «Llueve a cántaros» no habla de cántaros: quiere decir que **llueve mucho**.',
        ['**Literal**: significa exactamente lo que dice. «Cierra la puerta».', '**No literal**: tiene otro significado. «Echar una mano» = ayudar.', 'Si no entiendes una frase, **pregunta** qué significa.'],
        [
            completa('«Echar una mano» significa ___.', 'ayudar', ['lanzar una mano', 'saludar']),
            identifica('«Está lloviendo a cántaros» quiere decir…', 'Llueve muchísimo', ['Caen cántaros del cielo', 'Llueve poquito']),
        ]
    ),

    'reglas-que-nadie-explica' => intro(
        'Hay reglas que casi **nadie dice en voz alta**. La gente las aprende mirando. Aquí están **escritas claras**, para que no tengas que adivinarlas.',
        ['En la fila: te pones **al final** y esperas.', 'Si te prestan algo: lo devuelves bien y das **las gracias**.', 'Si no sabes cuál es la regla, **pregunta**. Está bien.'],
        [
            completa('En una fila, me pongo al ___.', 'final', ['principio', 'lado']),
            identifica('¿Está mal preguntar cuál es la regla?', 'No, es la mejor forma de saberla', ['Sí, siempre', 'Solo en el colegio']),
        ]
    ),

    'lo-que-ayuda-en-cada-sitio' => intro(
        'A algunas personas un ruido, una luz o una etiqueta de ropa les molesta **mucho**. No exageran: lo sienten **de verdad** más fuerte. Hay cosas concretas que ayudan.',
        ['Mucho ruido: **audífonos** o un sitio más tranquilo.', 'Luz fuerte: **gafas** o sentarse lejos de la ventana.', 'Primero se **dice** qué molesta; luego se busca la solución.'],
        [
            completa('Si hay mucho ruido, pueden ayudar unos ___.', 'audífonos', ['zapatos', 'guantes']),
            identifica('A Sara la etiqueta de la camiseta le raspa. ¿Qué puede hacer?', 'Pedir que se la corten', ['Aguantar todo el día', 'Llorar sin decir nada']),
        ]
    ),

    'el-tono-lo-cambia-todo' => intro(
        'Las **mismas palabras** pueden significar cosas distintas según **cómo** se digan. El **tono** de voz, la cara y la situación dicen si es en serio, en broma o una burla.',
        ['«¡Qué bien!» con sonrisa = alegría. Con risa burlona = **burla**.', 'Mira también la **cara** y lo que está pasando.', 'Si otros se ríen **de alguien**, no es broma: es burla.'],
        [
            completa('Las mismas palabras cambian según el ___.', 'tono', ['color', 'papel']),
            identifica('Alguien imita tu forma de hablar y los demás se ríen. ¿Qué es?', 'Una burla', ['Un halago', 'Un juego limpio']),
        ]
    ),

    'cada-uno-siente-distinto' => intro(
        'El **cerebro** de cada persona procesa los sonidos, las luces y los sabores **de forma distinta**. Por eso algo que a ti no te molesta a otro le puede agobiar. No exagera: **lo siente así**.',
        ['Si a alguien le molesta algo, **respétalo**.', 'Puedes **preguntarle** qué le ayuda.', 'Un **espacio de calma** es un sitio tranquilo al que ir cuando algo agobia.'],
        [
            completa('Si algo le molesta a un compañero, lo ___.', 'respeto', ['obligo', 'ignoro']),
            identifica('Un compañero se tapa los oídos con el timbre. ¿Qué pasa?', 'Ese sonido le resulta muy fuerte', ['Está jugando', 'No quiere estudiar']),
        ]
    ),

    'tareas-largas-sin-perderse' => intro(
        'Un trabajo de **varios días** no se hace de una vez. Se **parte** en pasos y a cada paso se le pone una **fecha**. Así nunca queda todo para el final.',
        ['Leo qué piden y **para cuándo**.', 'Lo parto en pasos con **fecha**.', 'Lo apunto **siempre en el mismo sitio**.'],
        [
            completa('Un trabajo largo se hace por pasos con ___.', 'fecha', ['prisa', 'miedo']),
            identifica('Una tarea grande me agobia. ¿Qué es lo mejor?', 'Hacer solo el primer paso', ['Esperar a tener tiempo', 'No hacerla']),
        ]
    ),

    'concentrarse-cuando-cuesta' => intro(
        'Concentrarse es más fácil si **preparas el sitio** y trabajas por **tandas cortas**. La cabeza se va a veces, y eso es normal: lo importante es **volver**.',
        ['El celular **en otro cuarto**.', 'Tandas de unos **20 o 25 minutos** con descansos.', 'Si te acuerdas de algo, **apúntalo** y sigue.'],
        [
            completa('Una tanda de estudio razonable es de unos ___ minutos.', '20', ['180', '2']),
            identifica('Llevo cinco minutos pensando en otra cosa. ¿Qué hago?', 'Vuelvo a la tarea sin regañarme', ['Lo dejo todo', 'Me regaño mucho']),
        ]
    ),

    'lo-que-se-dice-sin-decirlo' => intro(
        'A veces las palabras **no dicen lo que parecen**. La **ironía** es decir lo contrario de lo que se piensa: «¡Qué bien!» con cara de fastidio. Las **frases hechas** tienen otro significado: «me muero de hambre».',
        ['**Ironía**: lo contrario de lo que se piensa.', 'Frase hecha: «se me fue el santo al cielo» = **se me olvidó**.', 'Mira el **tono**, la **cara** y la **situación**.'],
        [
            completa('Decir lo contrario de lo que se piensa se llama ___.', 'ironía', ['rima', 'verdad']),
            identifica('«Me muero de hambre» significa…', 'Tengo mucha hambre', ['Estoy enfermo', 'No quiero comer']),
        ]
    ),

    'calma-en-momentos-dificiles' => intro(
        'Antes de «estallar», el cuerpo **avisa**: respiración rápida, corazón acelerado, hombros tensos. Si notas esas señales, puedes usar técnicas para **calmar el cuerpo**.',
        ['**Para** lo que estás haciendo.', '**Respira**: toma aire y suéltalo **más largo** de lo que lo tomaste.', 'Nombra **cinco cosas que ves** y siente los pies en el suelo.'],
        [
            completa('Al respirar para calmarme, suelto el aire más ___ de lo que lo tomo.', 'largo', ['rápido', 'fuerte']),
            identifica('Lo primero al notar que me altero es…', 'Parar', ['Gritar', 'Correr']),
        ]
    ),

    'entender-el-punto-de-vista' => intro(
        'Cada persona **sabe cosas distintas**. Yo sé cosas que el otro no sabe, y al revés. Por eso, antes de enojarme por lo que alguien hizo, conviene pensar en **varias explicaciones**.',
        ['Si un amigo no me saludó, quizá **no me vio**.', 'Si no responde un mensaje, quizá está **ocupado**.', 'Ponerse en el lugar del otro evita muchos malentendidos.'],
        [
            completa('Ante algo raro, busco varias ___.', 'explicaciones', ['peleas', 'excusas']),
            identifica('Un amigo no me saludó. Puede ser que…', 'No me viera', ['Me odie seguro', 'Esté bravo para siempre']),
        ]
    ),

];
