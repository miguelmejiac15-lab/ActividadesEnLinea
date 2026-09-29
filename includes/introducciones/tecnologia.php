<?php
/**
 * Tecnología · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'ciber-codigo-robot' => intro(
        'Un robot no piensa: hace **exactamente** lo que le dicen. Por eso hay que darle las órdenes paso a paso y en el orden correcto. Esa lista de pasos para resolver un problema se llama **algoritmo**, y escribirla para una máquina es **programar**.',
        ['Cada instrucción es un paso: avanza, gira a la derecha, gira a la izquierda.', 'Si un paso está mal, el robot **se equivoca**: hay que revisar y corregir (depurar).', 'Los computadores, por dentro, solo entienden **0 y 1**: el código **binario**.'],
        [
            completa('Una serie de pasos para resolver un problema es un ___.', 'algoritmo', ['robot', 'cable']),
            identifica('Si el robot no llega a la meta, ¿qué hago?', 'Reviso los pasos y corrijo el que falla', ['Lo apago para siempre', 'Le grito más fuerte']),
        ]
    ),

    'la-neo-computadora' => intro(
        'El **computador** tiene partes que se tocan —el **hardware**— y programas que le dicen qué hacer —el **software**—. Usarlo bien también es cuidarse: en internet no todos son quienes dicen ser.',
        ['**Entrada**: teclado, ratón, micrófono. **Salida**: monitor, parlantes, impresora.', 'Con la **mecanografía** escribes sin mirar el teclado.', 'Una **contraseña segura** mezcla letras, números y símbolos, y tiene más de 8 caracteres.'],
        [
            completa('Las partes del computador que se pueden tocar son el ___.', 'hardware', ['software', 'wifi']),
            identifica('Un desconocido en internet te pide tu dirección. ¿Qué haces?', 'No la doy y aviso a un adulto', ['Se la doy si es amable', 'Le mando una foto de mi casa']),
        ]
    ),

    'torre-de-circuitos' => intro(
        'La electricidad solo llega al bombillo si recorre un **circuito cerrado**: un camino completo que sale de la **pila**, pasa por los **cables** y el bombillo, y vuelve. El **interruptor** abre o cierra ese camino.',
        ['**Pila**: da la energía. **Cables**: la llevan. **Bombillo**: la convierte en luz.', 'Interruptor **cerrado** → pasa la corriente → se enciende.', 'Interruptor **abierto** → el camino se corta → se apaga.'],
        [
            completa('La parte del circuito que da la energía es la ___.', 'pila', ['bombilla', 'mesa']),
            identifica('Si el interruptor está abierto, el bombillo…', 'Se queda apagado', ['Se enciende', 'Explota']),
        ]
    ),

    'detective-digital' => intro(
        'En internet, tu **información personal** —nombre completo, dirección, colegio, fotos— vale mucho y hay que protegerla. Un buen detective digital sabe qué se comparte, qué no y a quién pedir ayuda.',
        ['Tu **contraseña** es solo tuya y de tus papás.', 'Si un **desconocido** te escribe, cuéntale a un adulto de confianza.', 'Descansar de las pantallas cuida tus **ojos** y tu cuerpo.'],
        [
            completa('Mi dirección y mi colegio son información ___.', 'privada', ['pública', 'divertida']),
            identifica('¿Cuál es la contraseña más segura?', 'Sol7Luna$92', ['123456', 'mi nombre']),
        ]
    ),

    'bucles-y-repeticiones' => intro(
        'Cuando una instrucción se repite muchas veces, en programación no se escribe cien veces: se usa un **bucle**. Y cuando algo solo debe pasar en ciertos casos, se usa una **condición**: SI pasa esto, ENTONCES haz aquello.',
        ['«Avanza, avanza, avanza, avanza» = **repite 4 veces: avanza**.', '«**SI** llueve **ENTONCES** lleva paraguas».', 'Un bucle que nunca termina es un **bucle infinito**.'],
        [
            completa('Una instrucción que repite pasos se llama ___.', 'bucle', ['condición', 'pantalla']),
            identifica('«Repite 5 veces: avanza 2 pasos». ¿Cuántos pasos en total?', '10', ['7', '5']),
        ]
    ),

    'contrasenas-y-privacidad' => intro(
        'Una **contraseña** es la llave de tu cuenta. Si es fácil de adivinar, cualquiera puede entrar y hacerse pasar por ti. La **privacidad** es tu derecho a decidir qué información tuya ven los demás.',
        ['Débil: 123456, tu nombre, tu fecha de nacimiento.', 'Fuerte: larga y mezclada, como **Ma7#zul-Perro**.', 'Al terminar en un computador compartido, **cierra sesión**.'],
        [
            completa('Una contraseña como «123456» es ___.', 'débil', ['fuerte', 'secreta']),
            identifica('¿Con quién se comparte la contraseña?', 'Con nadie, salvo un adulto responsable', ['Con mis amigos', 'Con quien me la pida']),
        ]
    ),

    'archivos-y-carpetas' => intro(
        'Un **archivo** es cualquier cosa que guardas en el computador: un texto, una foto, una canción. Las **carpetas** sirven para ordenarlos. Con buenos nombres y buen orden encuentras todo en segundos.',
        ['La **extensión** dice qué tipo es: .jpg (imagen), .mp3 (sonido), .pdf (documento).', 'Buen nombre: **tarea-ciencias-celula.pdf**, no «documento1».', 'Una **copia de seguridad** es una segunda copia guardada en otro sitio.'],
        [
            completa('Un archivo .jpg es una ___.', 'imagen', ['canción', 'carpeta']),
            identifica('¿Cuál es un buen nombre de archivo?', 'tarea-sociales-regiones.pdf', ['documento1', 'aaaa']),
        ]
    ),

    'presentaciones-digitales' => intro(
        'Una **presentación** es una serie de **diapositivas** que apoyan lo que dices en voz alta. No reemplazan tu explicación: la acompañan. Lo mejor es **una idea por diapositiva**, con poco texto y una buena imagen.',
        ['Estructura: **portada**, introducción, desarrollo y **cierre**.', 'Las imágenes deben ayudar a entender, no solo decorar.', 'Al exponer, mira al **público**, no a la pantalla.'],
        [
            completa('Lo ideal es poner ___ idea por diapositiva.', 'una', ['diez', 'ninguna']),
            identifica('¿Qué pasa si lleno la diapositiva de texto?', 'Nadie la lee y dejan de escucharme', ['Queda más clara', 'Se ve más bonita']),
        ]
    ),

    'secuencias-y-repeticiones' => intro(
        'Dos ideas hacen que un programa sea corto y potente: los **bucles**, que repiten instrucciones, y las **condiciones**, que toman decisiones. Con ellas se puede guiar a un robot por un laberinto.',
        ['**Bucle**: «repite 4 veces: da un paso».', '**Condición**: «si hay un muro, gira».', 'Girar a la derecha desde el norte → mirar al **este**.'],
        [
            completa('«Si llueve, lleva paraguas» es una ___.', 'condición', ['repetición', 'canción']),
            identifica('El robot mira al norte y gira a la derecha. ¿Hacia dónde mira?', 'Al este', ['Al oeste', 'Al sur']),
        ]
    ),

    'animacion-y-storyboard' => intro(
        'Una **animación** es una ilusión: muchas imágenes fijas, un poco distintas entre sí, pasadas muy rápido. El ojo las une y ve movimiento. Antes de animar se planea con un **storyboard**, un guion dibujado viñeta por viñeta.',
        ['Cada imagen de la animación es un **fotograma**.', 'El **storyboard** evita grabar o dibujar de más.', 'La música y la **voz en off** dan emoción y cuentan la historia.'],
        [
            completa('Cada imagen de una animación se llama ___.', 'fotograma', ['storyboard', 'pixel']),
            identifica('¿Qué es un storyboard?', 'Un guion dibujado viñeta por viñeta', ['Un tipo de cámara', 'Una canción']),
        ]
    ),

    'contrasenas-seguras' => intro(
        'Una contraseña **fuerte** es larga, mezcla mayúsculas, minúsculas, números y símbolos, y **no tiene datos tuyos** que otros puedan averiguar. Y aunque sea perfecta, deja de servir si se comparte.',
        ['Nada de fechas de nacimiento ni nombres de mascotas.', 'Una contraseña **distinta** para cada cuenta.', 'Si sospechas que alguien la sabe, **cámbiala** y avisa a un adulto.'],
        [
            completa('Una contraseña fuerte no incluye datos ___ fáciles de averiguar.', 'personales', ['secretos', 'raros']),
            identifica('¿Cuál es la más segura?', 'Ma7#zul-Perro2026', ['perro', '12345678']),
        ]
    ),

    'buscar-informacion' => intro(
        'En internet hay de todo: información excelente y también errores y mentiras. Buscar bien es escribir **palabras clave precisas**; y usar bien lo encontrado es **comprobar** si es confiable y decir **de dónde** lo sacaste.',
        ['Mejor: «ciclo del agua para niños» que solo «agua».', 'Confiable: dice **quién** lo escribió y **cuándo**.', '**Citar** es indicar la fuente de donde viene la información.'],
        [
            completa('Indicar de dónde viene la información se llama ___.', 'citar', ['copiar', 'borrar']),
            identifica('¿El primer resultado del buscador siempre es el mejor?', 'No, conviene comparar varias fuentes', ['Sí, siempre', 'Solo si tiene fotos']),
        ]
    ),

    'como-funciona-internet' => intro(
        '**Internet** es una red gigante de computadores conectados en todo el mundo. Cuando abres una página, tu **navegador** le pide a un **servidor** —otro computador lejano— que te envíe esa información.',
        ['**URL**: la dirección de una página web.', 'El **candado** en la barra indica que la conexión está cifrada.', '**La nube**: tus archivos guardados en servidores de una empresa.'],
        [
            completa('El computador que guarda y entrega páginas a otros es un ___.', 'servidor', ['monitor', 'teclado']),
            identifica('¿Qué es una URL?', 'La dirección de una página web', ['Un virus', 'Un tipo de cable']),
        ]
    ),

    'huella-digital' => intro(
        'Todo lo que haces en internet deja un **rastro**: lo que publicas, comentas, compartes o buscas. Eso es tu **huella digital**, y puede durar muchos años aunque borres algo, porque alguien pudo guardarlo.',
        ['Antes de publicar: ¿me molestaría que lo viera cualquiera dentro de diez años?', '**Phishing**: engaño para robarte datos haciéndose pasar por alguien.', '**Ciberacoso**: molestar o humillar a alguien por medios digitales. Se denuncia.'],
        [
            completa('El rastro que dejan mis acciones en internet es mi huella ___.', 'digital', ['dactilar', 'escolar']),
            identifica('«¡Ganaste un premio! Da clic aquí» probablemente sea…', 'Un engaño', ['Un premio real', 'Un mensaje del colegio']),
        ]
    ),

    'el-correo-electronico' => intro(
        'El **correo electrónico** es una carta digital. Tiene partes fijas —destinatario, asunto, saludo, mensaje, despedida— y normas de cortesía que se llaman **netiqueta**.',
        ['**Para**: la dirección de quien lo recibe. **Asunto**: de qué se trata, en pocas palabras.', 'Un **adjunto** es un archivo que va con el correo.', 'ESCRIBIR TODO EN MAYÚSCULAS se lee como si **gritaras**.'],
        [
            completa('Las normas de cortesía en internet se llaman ___.', 'netiqueta', ['etiqueta de ropa', 'contraseña']),
            identifica('¿Para qué sirve el asunto de un correo?', 'Para decir en pocas palabras de qué se trata', ['Para poner la contraseña', 'Para despedirse']),
        ]
    ),

    'hojas-de-calculo' => intro(
        'Una **hoja de cálculo** es una tabla gigante donde el computador hace las cuentas por ti. Está formada por **filas** (horizontales, con números) y **columnas** (verticales, con letras). Donde se cruzan hay una **celda**, como A1 o B3.',
        ['Toda **fórmula** empieza con **=**.', '=A1+B1 suma el contenido de esas dos celdas.', 'Si cambias un dato, la fórmula se **recalcula sola**.'],
        [
            completa('Toda fórmula empieza con el signo ___.', '=', ['+', '?']),
            identifica('Si A1 = 5 y A2 = 3, ¿cuánto da =A1*A2?', '15', ['8', '53']),
        ]
    ),

    'inteligencia-artificial' => intro(
        'La **inteligencia artificial** (IA) son programas que aprenden **patrones** a partir de muchísimos datos y con eso responden, dibujan o traducen. No piensa ni siente como una persona, y a veces se **equivoca** con mucha seguridad.',
        ['Úsala para **entender** y tener ideas, no para que haga tu trabajo.', 'Lo que dice hay que **verificarlo**.', 'No le des **datos personales**.'],
        [
            completa('Si una IA me da un dato que suena raro, lo ___.', 'verifico', ['copio', 'publico']),
            identifica('¿Piensa una IA como una persona?', 'No, calcula a partir de patrones', ['Sí, igual', 'Sí, pero más rápido y sin errores']),
        ]
    ),

    'lo-que-dejo-escrito' => intro(
        'Lo que subes hoy puede leerlo alguien dentro de diez años: un profesor, un amigo nuevo, una empresa. En internet **borrar no siempre borra**, porque otros pueden haberlo guardado o fotografiado.',
        ['Lo que publicas en abierto lo puede ver **cualquiera**.', 'No publiques datos como tu colegio, horario o dirección.', 'La foto de otra persona **no se sube sin su permiso**.'],
        [
            completa('La foto de un compañero no se publica sin su ___.', 'permiso', ['filtro', 'nombre']),
            identifica('Borro una foto de una red. ¿Desaparece de todas partes?', 'No, alguien pudo guardarla', ['Sí, siempre', 'Sí, si la borro rápido']),
        ]
    ),

    'no-todo-es-verdad' => intro(
        'En internet circulan fotos retocadas, titulares exagerados y **noticias falsas**. Antes de creer o compartir algo hay que **verificar**: quién lo dice, cuándo, y si otras fuentes serias dicen lo mismo.',
        ['Señales de alarma: MAYÚSCULAS, muchos signos ¡¡!!, sin autor ni fecha.', 'Un **hecho** se puede comprobar; una **opinión**, no.', 'Lee la noticia **entera**, no solo el titular.'],
        [
            completa('Algo que se puede comprobar es un ___.', 'hecho', ['rumor', 'gusto']),
            identifica('Veo una foto impactante. ¿Qué hago primero?', 'Buscar si otros medios serios la publican', ['Compartirla rápido', 'Creerla porque tiene muchos likes']),
        ]
    ),

    'la-hoja-de-calculo' => intro(
        'Una **hoja de cálculo** organiza datos en **filas** y **columnas** y los calcula con **fórmulas**. Sirve para llevar cuentas, comparar datos y hacer gráficas sin sacar las operaciones a mano.',
        ['Filas: de lado a lado. Columnas: de arriba abajo. Celda: donde se cruzan.', 'Las fórmulas empiezan con **=**: «=2+3» da 5.', 'Primero decide **qué quieres averiguar**; luego recoge los datos.'],
        [
            completa('El cruce de una fila y una columna se llama ___.', 'celda', ['tabla', 'hoja']),
            identifica('«=2+3» da como resultado…', '5', ['23', '6']),
        ]
    ),

    'presentar-sin-aburrir' => intro(
        'Una **diapositiva** no es un texto para leer en voz alta: es un **apoyo** para quien escucha. Lo importante lo dices tú. Por eso lleva poco texto, letra grande e imágenes que ayuden.',
        ['**Poco texto y grande**: si no se lee desde el fondo, sobra.', '**Ensaya** en voz alta antes.', 'Mira al **público**, no a la pantalla.'],
        [
            completa('Una diapositiva lleva ___ texto y grande.', 'poco', ['mucho', 'ningún']),
            identifica('Leer la diapositiva palabra por palabra es…', 'Aburrido', ['Lo ideal', 'Obligatorio']),
        ]
    ),

];
