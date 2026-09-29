<?php
/**
 * Pensamiento y Lógica · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'codigo-secreto' => intro(
        'Un **código secreto** o **cifrado** es una regla para cambiar las letras de un mensaje y que solo lo entienda quien conoce la regla. Descifrar es aplicar la regla **al revés**.',
        ['**Cifrado del número**: cada letra se cambia por su posición: A=1, B=2, C=3…', '**Cifrado del salto**: cada letra avanza un número fijo de lugares: con salto 1, A→B.', 'Para descifrar el salto, se **retrocede** el mismo número.'],
        [
            completa('Si A=1, B=2, C=3…, la letra 5 es la ___.', 'E', ['D', 'F']),
            identifica('Si cada letra avanza 1 lugar, «SOL» se escribe…', 'TPM', ['RNK', 'SOL']),
        ]
    ),

    'quien-miente' => intro(
        'En los acertijos de lógica, dos afirmaciones **contrarias no pueden ser verdad a la vez**. Si una es cierta, la otra es falsa. Razonar **por eliminación** es ir descartando lo imposible hasta que solo quede una opción.',
        ['Busca **contradicciones**: dos cosas que no pueden pasar juntas.', 'Descarta lo que las pistas **prohíben**.', 'A veces la respuesta correcta es «**no se sabe**»: la información no alcanza.'],
        [
            completa('Ir descartando opciones hasta que solo quede una es razonar por ___.', 'eliminación', ['suerte', 'costumbre']),
            identifica('«Algunos niños del salón usan gafas». Pedro es del salón. ¿Pedro usa gafas?', 'No se sabe', ['Sí, seguro', 'No, nunca']),
        ]
    ),

    'caso-cerrado' => intro(
        'Un buen detective no acusa por una sola pista. Reúne **todas** las pistas, separa los **hechos** de las **suposiciones** y solo concluye cuando todo encaja.',
        ['Un **indicio** sugiere algo, pero no lo demuestra.', 'Una pista que **descarta** a un sospechoso también ayuda.', 'La conclusión tiene que explicar **todas** las pistas, no solo una.'],
        [
            completa('Algo que sugiere una respuesta pero no la demuestra es un ___.', 'indicio', ['hecho', 'veredicto']),
            identifica('Alguien salió apurado. ¿Eso demuestra que es culpable?', 'No, solo es un indicio', ['Sí, seguro', 'Sí, si corre mucho']),
        ]
    ),

    'entender-el-problema' => intro(
        'La mitad de los errores en un problema no están en la cuenta, sino **antes**: en no entender qué se pregunta. Por eso primero se lee con calma y se separan tres cosas: los **datos**, lo que **sobra** y la **pregunta**.',
        ['¿Qué me **preguntan**? ¿Qué **datos** tengo?', 'Algunos datos **sobran** y no se usan.', 'Al final, revisa si la respuesta **tiene sentido**.'],
        [
            completa('«Tenía 12 y perdí 5». Para saber cuántos quedan hay que ___.', 'restar', ['sumar', 'multiplicar']),
            identifica('«Ana tiene 8 años y 5 canicas. Perdió 2. ¿Cuántas le quedan?» ¿Qué dato sobra?', 'Que tiene 8 años', ['Que tiene 5 canicas', 'Que perdió 2']),
        ]
    ),

    'hecho-u-opinion' => intro(
        'Un **hecho** es algo que **se puede comprobar**: el agua hierve a 100 °C. Una **opinión** es lo que alguien **piensa o siente**: la sopa está deliciosa. Las dos son válidas, pero no son lo mismo.',
        ['Ante algo increíble, pregunta: **¿cómo lo sabes?**', 'Que algo se repita mucho **no lo hace cierto**.', '«Todos lo hacen» **no es** un buen argumento.'],
        [
            completa('Algo que se puede comprobar es un ___.', 'hecho', ['gusto', 'deseo']),
            identifica('«El helado de vainilla es el mejor» es…', 'Una opinión', ['Un hecho', 'Una ley']),
        ]
    ),

    'acertijos-y-enigmas' => intro(
        'Muchos acertijos no se resuelven haciendo cuentas, sino **mirando el problema de otra forma**. La trampa suele estar en una **suposición** que haces sin darte cuenta. Leer con atención cada palabra es la clave.',
        ['Revisa lo que **das por hecho**: ¿los gallos ponen huevos?', 'Lee **exactamente** lo que dice: «un kilo de plumas» pesa un kilo.', 'Si parece demasiado fácil, **vuelve a leer**.'],
        [
            completa('¿Qué pesa más, un kilo de plumas o un kilo de plomo? Pesan ___.', 'lo mismo', ['más las plumas', 'más el plomo']),
            identifica('Un gallo pone un huevo en el techo. ¿Hacia dónde rueda?', 'Los gallos no ponen huevos', ['Hacia la derecha', 'Hacia la izquierda']),
        ]
    ),

    'series-y-analogias' => intro(
        'Una **serie** es una secuencia que sigue una **regla**. Una **analogía** compara dos parejas que tienen la **misma relación**: «pájaro es a volar como pez es a nadar».',
        ['En una serie, busca cuánto **cambia** cada paso: 1, 4, 7… (suma 3).', 'En una analogía, primero di en voz alta **qué relación** tiene la primera pareja.', 'Luego busca la palabra que cumpla **esa misma** relación.'],
        [
            completa('Pájaro es a volar como pez es a ___.', 'nadar', ['agua', 'comer']),
            identifica('¿Qué sigue en 1, 4, 7, …?', '10', ['8', '11']),
        ]
    ),

    'tablas-y-rejillas-logicas' => intro(
        'Cuando un acertijo tiene muchas pistas, la cabeza se enreda. Una **rejilla lógica** —una tabla con las opciones en filas y columnas— las ordena: se marca con **X** lo que **no puede ser** y la respuesta aparece sola.',
        ['Una **X** significa «no puede ser».', 'Si una casilla se **confirma**, se tacha todo lo demás de su fila y su columna.', 'Si solo queda una opción libre, **esa es**.'],
        [
            completa('En una rejilla lógica, una X significa que ___ puede ser.', 'no', ['sí', 'tal vez']),
            identifica('Ana no tiene perro. Luis tiene gato. Sara no tiene pez. ¿Qué tiene Sara?', 'Perro', ['Gato', 'Pez']),
        ]
    ),

    'estrategias-para-resolver' => intro(
        'Algunos problemas no se resuelven con una cuenta directa. Para esos hay **estrategias**: formas de atacarlos cuando no sabes por dónde empezar.',
        ['**Dibujar**: cuando hay posiciones o repartos.', '**Buscar un patrón** y **probar con un caso pequeño**: si 100 abruma, prueba con 3.', '**Ir hacia atrás**: si sumé 5 y me dio 12, resto 5 → 7.'],
        [
            completa('«Pensé un número, le sumé 5 y me dio 12». El número era ___.', '7', ['17', '12']),
            identifica('Un problema con 100 objetos me abruma. ¿Qué hago?', 'Lo pruebo primero con pocos y busco la regla', ['Lo dejo', 'Invento el resultado']),
        ]
    ),

    'deducir-y-concluir' => intro(
        '**Deducir** es sacar una conclusión que **tiene que ser verdad** si las pistas lo son: «Todos los perros son animales. Firulais es un perro. Entonces Firulais es un animal». Pero igual de importante es saber cuándo **no alcanza** la información.',
        ['Si todos los A son B, y todos los B son C, **todos los A son C**.', '«Un animal tiene cuatro patas»: ¿es un perro? **No se puede saber**.', 'No inventes lo que las pistas no dicen.'],
        [
            completa('Si todos los perros son animales y Firulais es un perro, Firulais es un ___.', 'animal', ['gato', 'pez']),
            identifica('«Sara llegó tarde». ¿Se quedó dormida?', 'No se puede saber', ['Sí, seguro', 'No, nunca']),
        ]
    ),

    'causa-y-consecuencia' => intro(
        'Una **causa** es lo que **produce** algo; la **consecuencia** es lo que **resulta**. «Llovió (causa) y se inundó la calle (consecuencia)». Pero cuidado: que dos cosas pasen juntas **no siempre** significa que una cause la otra.',
        ['Pregúntate: ¿qué pasó **primero** y qué produjo qué?', 'Una consecuencia puede causar otra: son **cadenas**.', 'La camiseta de la suerte no hace ganar: es **coincidencia**.'],
        [
            completa('«No estudié y me fue mal». No estudiar es la ___.', 'causa', ['consecuencia', 'coincidencia']),
            identifica('«Cada vez que llevo mi camiseta roja, mi equipo gana». ¿Es una causa?', 'No, es una coincidencia', ['Sí, siempre', 'Sí, porque es roja']),
        ]
    ),

    'causa-o-casualidad' => intro(
        'Que dos cosas **pasen juntas** no significa que una **cause** la otra. En verano se vende más helado y también hay más ahogamientos, pero el helado no ahoga a nadie: los dos suben por el **calor**. Distinguir eso evita muchas trampas.',
        ['**Casualidad**: coinciden, pero una no produce la otra.', 'A veces hay una **tercera causa** escondida.', '«Lo dice mucha gente» o «es famoso» **no son pruebas**.'],
        [
            completa('Si dos cosas coinciden pero una no produce la otra, es ___.', 'casualidad', ['causa', 'consecuencia']),
            identifica('«Un estudio lo demuestra». ¿Qué conviene preguntar?', 'Cuál estudio y quién lo hizo', ['Nada, ya está demostrado', 'Si es un estudio bonito']),
        ]
    ),

    'estimar-antes-de-calcular' => intro(
        '**Estimar** es saber **más o menos** cuánto va a dar algo antes de calcularlo. Sirve para detectar resultados **absurdos**: si estimas 400 y la cuenta da 4.000, algo salió mal.',
        ['Redondea: 198 + 203 ≈ 200 + 200 = **400**.', 'Piensa en el **orden de magnitud**: ¿decenas, miles, millones?', 'Si el resultado no tiene sentido, **revisa**.'],
        [
            completa('198 + 203 es más o menos ___.', '400', ['40', '4.000']),
            identifica('Un niño mide 15 metros. ¿Tiene sentido?', 'No', ['Sí', 'Solo si es alto']),
        ]
    ),

    'argumentar-y-convencer' => intro(
        'Una **opinión** a secas no convence. Un **argumento** sí, porque tiene tres partes: la **afirmación** (lo que sostengo), la **razón** (por qué) y la **prueba** (un dato o ejemplo que lo respalda).',
        ['Afirmación: «Hay que dormir 9 horas».', 'Razón: «**porque** el cuerpo se repara al dormir».', '«**Porque sí**» no es una razón. Reconocer lo bueno del otro lado te hace **más creíble**.'],
        [
            completa('La parte del argumento que explica el porqué es la ___.', 'razón', ['afirmación', 'despedida']),
            identifica('«Porque sí» es…', 'No es una razón', ['Una prueba', 'El mejor argumento']),
        ]
    ),

    'partir-el-problema' => intro(
        'Cuando un problema no se resuelve con una sola operación, hay que **partirlo** en pasos más pequeños. Cada paso da un **resultado intermedio** que sirve para el siguiente.',
        ['Compro 3 cuadernos de $2.000 y pago con $10.000: **paso 1**, 3 × 2.000 = 6.000; **paso 2**, 10.000 − 6.000 = 4.000.', 'Anota cada resultado intermedio.', 'Si no puedes resolverlo, pregúntate: ¿**qué dato me falta**?'],
        [
            completa('Un bus lleva 40 personas y bajan 12. Primero ___ 12.', 'resto', ['sumo', 'multiplico']),
            identifica('«Compré lápices y pagué $10.000. ¿Cuánto me devuelven?» ¿Qué falta saber?', 'Cuánto costaron', ['De qué color eran', 'Quién los vendió']),
        ]
    ),

];
