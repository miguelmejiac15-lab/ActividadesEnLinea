<?php
/**
 * Idiomas · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 *
 * La explicación va en español —es lo que el niño necesita para entender
 * la regla— y los ejemplos, en inglés.
 */

return [

    'english-detective' => intro(
        'Para entender una historia en otro idioma no hace falta saber **todas** las palabras. Un buen lector hace de detective: usa las **palabras que conoce**, los dibujos y el **contexto** para deducir el resto.',
        ['Busca primero **quién**, **qué** y **dónde**.', 'Si una palabra no la sabes, sigue leyendo: el resto de la frase te da pistas.', 'Los verbos con **-ed** o formas como **was** hablan del **pasado**.'],
        [
            completa('Si no entiendo una palabra, sigo leyendo y la deduzco por el ___.', 'contexto', ['diccionario de colores', 'título']),
            identifica('«Hungry» significa…', 'Con hambre', ['Feliz', 'Cansado']),
        ]
    ),

    'complete-the-conversation' => intro(
        'Una **conversación** es un ir y venir: alguien pregunta y el otro responde. Para responder bien en inglés hay que **entender la pregunta** y elegir una respuesta que **tenga sentido** en esa situación.',
        ['«What is your name?» → **My name is…**', '«How old are you?» → **I am … years old.**', 'Para ser amable: **please**, **thank you**, **excuse me**.'],
        [
            completa('«How old are you?» → I am ten years ___.', 'old', ['name', 'time']),
            identifica('Estás perdido. ¿Qué dices?', 'Excuse me, where is the station?', ['Good night!', 'I am ten years old.']),
        ]
    ),

    'my-home' => intro(
        'Para describir una casa en inglés se usan los nombres de las **habitaciones** (rooms) y las **preposiciones de lugar**, que dicen **dónde** está cada cosa.',
        ['**Bedroom**: cuarto. **Kitchen**: cocina. **Bathroom**: baño. **Living room**: sala.', '**On**: encima. **In**: dentro. **Under**: debajo. **Next to**: al lado.', 'The cat is **on** the table = el gato está encima de la mesa.'],
        [
            completa('The ball is ___ the box. (dentro)', 'in', ['on', 'under']),
            identifica('¿Dónde cocinas? Where do you cook?', 'In the kitchen', ['In the bedroom', 'In the garden']),
        ]
    ),

    'the-weather' => intro(
        'Para hablar del **clima** (weather) en inglés casi siempre se empieza con **It\'s…**: It\'s sunny, It\'s raining. Y según el clima decidimos qué ponernos y qué hacer.',
        ['**Sunny**: soleado. **Rainy**: lluvioso. **Cloudy**: nublado. **Windy**: ventoso.', 'Estaciones: **spring**, **summer**, **autumn**, **winter**.', 'It\'s raining → you need an **umbrella**.'],
        [
            completa('«Soleado» en inglés es ___.', 'sunny', ['rainy', 'windy']),
            identifica('Which season is the coldest?', 'Winter', ['Summer', 'Spring']),
        ]
    ),

    'clothes-and-colours' => intro(
        'En inglés el **color** va **antes** de la cosa que describe, al revés que en español: «camiseta azul» es **blue T-shirt**. Y para decir lo que llevas puesto se usa **I\'m wearing…**',
        ['**T-shirt**: camiseta. **Trousers**: pantalón. **Shoes**: zapatos. **Dress**: vestido.', '**Red**, **blue**, **green**, **yellow**, **black**, **white**.', 'I\'m wearing a **red** dress.'],
        [
            completa('«Camiseta azul» se dice «a ___ T-shirt».', 'blue', ['red', 'green']),
            identifica('En inglés, ¿dónde va el color?', 'Antes del sustantivo', ['Después del sustantivo', 'Al final de la frase']),
        ]
    ),

    'hobbies-and-free-time' => intro(
        'Un **hobby** es lo que te gusta hacer en tu tiempo libre. Para contarlo se usa el **presente simple**, y para decir cada cuánto lo haces, los **adverbios de frecuencia**.',
        ['**Always** (siempre), **usually** (casi siempre), **sometimes** (a veces), **never** (nunca).', 'I **play** football. She **plays** football (con he/she/it se añade **-s**).', 'What\'s your favourite hobby?'],
        [
            completa('She ___ books every night.', 'reads', ['read', 'reading']),
            identifica('«Nunca» se dice…', 'Never', ['Always', 'Sometimes']),
        ]
    ),

    'telling-the-time' => intro(
        'Para decir la **hora** en inglés se pregunta **What time is it?** Las horas en punto llevan **o\'clock** y las medias, **half past**. También aprenderás los días y los meses, que en inglés se escriben con **mayúscula**.',
        ['3:00 = **three o\'clock**. 4:30 = **half past four**.', 'Días: Monday, Tuesday, Wednesday…', 'Meses: **January** es el primero, **December** el último.'],
        [
            completa('3:00 se dice three ___.', 'o\'clock', ['half', 'past']),
            identifica('¿Cuál es el primer mes del año?', 'January', ['December', 'Monday']),
        ]
    ),

    'asking-questions' => intro(
        'Las preguntas en inglés suelen empezar con una **wh-word** que dice qué quieres saber. Elegir la correcta es la clave para que te entiendan.',
        ['**What**: qué. **Where**: dónde. **When**: cuándo.', '**Who**: quién. **Why**: por qué. **How**: cómo.', 'Respuestas cortas: Do you…? → **Yes, I do.** Are you…? → **Yes, I am.**'],
        [
            completa('___ do you live? (dónde)', 'Where', ['What', 'Who']),
            identifica('¿Qué palabra pregunta por una persona?', 'Who', ['Where', 'When']),
        ]
    ),

    'days-and-routines' => intro(
        'Una **rutina** es lo que haces todos los días. Para contarla en inglés se usa el **presente simple** y palabras que ordenan: **before** (antes), **after** (después), **then** (luego).',
        ['**Wake up**: despertarse. **Have breakfast**: desayunar. **Go to school**: ir al colegio.', 'I wake up **at** seven (a las siete).', 'I brush my teeth **after** breakfast.'],
        [
            completa('I have breakfast ___ I go to school. (antes)', 'before', ['after', 'then']),
            identifica('«Me levanto a las siete» se dice…', 'I wake up at seven', ['I go to bed at seven', 'I am seven']),
        ]
    ),

    'jobs-and-people' => intro(
        'Los **oficios** (jobs) se nombran en inglés con palabras que muchas veces se parecen al español. Para decir qué hace cada persona se usa el presente con **-s**: A doctor **helps** sick people.',
        ['**Doctor**, **teacher**, **vet**, **pilot**, **firefighter**, **cook**.', 'Describir: She **is** tall. She **has** curly hair.', 'A teacher works **at a school**.'],
        [
            completa('A vet helps ___.', 'animals', ['planes', 'books']),
            identifica('Who flies a plane?', 'A pilot', ['A teacher', 'A cook']),
        ]
    ),

    'sports-and-games' => intro(
        'En inglés los deportes van con tres verbos distintos según el tipo: **play**, **go** o **do**. Y para decir lo que sabes hacer se usa **can**.',
        ['**Play** + deportes con pelota: play football, play tennis.', '**Go** + deportes en **-ing**: go swimming, go cycling.', '**Do** + artes marciales y gimnasia: do karate. **I can swim** = sé nadar.'],
        [
            completa('___ swimming', 'go', ['play', 'do']),
            identifica('«No sé jugar tenis» se dice…', 'I can\'t play tennis', ['I can play tennis', 'I go tennis']),
        ]
    ),

    'my-city' => intro(
        'Una ciudad tiene **lugares** donde se atienden las necesidades de la comunidad: el hospital, la biblioteca, el banco. Y para moverte por ella necesitas saber **pedir y dar indicaciones** en inglés.',
        ['**Hospital**, **library** (biblioteca), **bank**, **park**, **supermarket**.', '**Turn left**: gire a la izquierda. **Turn right**: a la derecha.', '**Go straight**: siga derecho.'],
        [
            completa('«Gire a la izquierda» se dice turn ___.', 'left', ['right', 'straight']),
            identifica('You want to borrow a book. Where do you go?', 'To the library', ['To the bank', 'To the hospital']),
        ]
    ),

    'living-things' => intro(
        'Los **living things** (seres vivos) nacen, crecen, se alimentan y se reproducen; las **non-living things** no. Cada ser vivo tiene su **habitat**: el lugar donde encuentra lo que necesita para sobrevivir.',
        ['All living things need **water** and **food**.', 'Plants need **light**, **water** and **soil**.', 'Camel → **desert**. Polar bear → **Arctic**.'],
        [
            completa('A rock is a ___ thing.', 'non-living', ['living', 'happy']),
            identifica('Where does a camel live?', 'In the desert', ['In the Arctic', 'In the sea']),
        ]
    ),

    'health-and-feelings' => intro(
        'Para decir que algo **duele** en inglés se usa **I have a…** con palabras que terminan en **-ache** (dolor). Y para hablar de cómo te sientes, **I am…** o **I feel…**',
        ['**Headache**: dolor de cabeza. **Stomachache**: dolor de estómago.', 'Partes del cuerpo: **eye**, **ear**, **nose**, **mouth**, **hand**.', 'Emociones: **happy**, **sad**, **angry**, **scared**, **tired**.'],
        [
            completa('«Me duele la cabeza» se dice I have a ___.', 'headache', ['stomachache', 'happy']),
            identifica('«Triste» se dice…', 'Sad', ['Happy', 'Tired']),
        ]
    ),

    'the-past' => intro(
        'Para contar lo que **ya pasó** se usa el **pasado simple**. Muchos verbos solo añaden **-ed**, pero otros —los **irregulares**— cambian de forma y hay que aprenderlos.',
        ['Regulares: play → **played**, watch → **watched**.', 'Irregulares: go → **went**, eat → **ate**, see → **saw**.', 'To be: I/he/she **was**; you/we/they **were**.'],
        [
            completa('go → ___', 'went', ['goed', 'gone']),
            identifica('They ___ very happy.', 'were', ['was', 'is']),
        ]
    ),

    'inventions-and-space' => intro(
        'Los **inventos** nacen para resolver un problema: la rueda, para mover cosas pesadas; el teléfono, para hablar a distancia. Aquí conocerás sus nombres en inglés, palabras del espacio y cómo hablar del **futuro**.',
        ['**Light bulb**: bombillo. **Wheel**: rueda. **Telephone**: teléfono.', '**Star**, **planet**, **moon**, **rocket**, **astronaut**.', 'Futuro: **I will go** (iré). Plan: **I\'m going to study** (voy a estudiar).'],
        [
            completa('«Estrella» en inglés es ___.', 'star', ['planet', 'moon']),
            identifica('«Iré mañana» se dice…', 'I will go tomorrow', ['I went tomorrow', 'I go yesterday']),
        ]
    ),

    'how-we-communicate' => intro(
        'Nos comunicamos con **palabras** (comunicación verbal) y también con **gestos** y expresiones (no verbal). En inglés, como en español, hay formas **corteses** de pedir y agradecer, y una carta tiene partes fijas.',
        ['**Verbal**: using words. **Non-verbal**: gestures and facial expressions.', 'Cortés: **Could you help me, please?** — **Thank you.**', 'Una carta empieza con la fecha y **Dear…** (Querido/a).'],
        [
            completa('Para agradecer se dice thank ___.', 'you', ['please', 'dear']),
            identifica('What is non-verbal communication?', 'Gestures and facial expressions', ['Writing letters', 'Using words']),
        ]
    ),

    'comparing-things' => intro(
        'Para **comparar** en inglés, a los adjetivos cortos se les añade **-er** (más…que) y para decir que algo es **el más** se añade **-est**. Algunos son irregulares, como good → better → best.',
        ['Comparativo: big → **bigger** than.', 'Superlativo: big → **the biggest**.', 'Irregular: good → **better** → **the best**.'],
        [
            completa('An elephant is ___ than a mouse.', 'bigger', ['biggest', 'big']),
            identifica('A cheetah is the ___ land animal.', 'fastest', ['faster', 'fast']),
        ]
    ),

    'reading-in-english' => intro(
        'Leer en inglés es como armar un rompecabezas: no necesitas todas las piezas para ver la imagen. Lo importante es captar la **idea general** y la **información clave**: quién, qué, dónde y cuándo.',
        ['Lee una vez completa sin detenerte.', 'Si no entiendes una palabra, **sigue** y dedúcela por el **contexto**.', 'Relee para buscar los datos que te preguntan.'],
        [
            completa('No hace falta entender todas las palabras: basta con la idea ___.', 'general', ['difícil', 'última']),
            identifica('Si no entiendo una palabra, ¿qué hago primero?', 'Sigo leyendo y la deduzco por el contexto', ['Dejo de leer', 'Me invento el final']),
        ]
    ),

    'places-and-directions' => intro(
        'Para ubicar algo en un mapa o en la calle se usan **preposiciones de lugar** y **verbos de dirección**. Con ellos puedes preguntar **How do I get to…?** y entender la respuesta.',
        ['**Between**: entre. **In front of**: delante de. **Next to**: al lado de. **Behind**: detrás.', '**Go straight**, **turn left**, **turn right**.', 'Transporte: **by bus**, **by car**, **on foot** (a pie).'],
        [
            completa('«Next to» significa al ___ de.', 'lado', ['frente', 'fondo']),
            identifica('¿Qué frase pide una dirección?', 'How do I get to the park?', ['I like the park.', 'The park is big.']),
        ]
    ),

    'feelings-and-friends' => intro(
        'Saber decir **cómo te sientes** y **preguntarle a un amigo** cómo está es parte de cuidarse entre todos, también en inglés. Unas pocas frases bastan para ofrecer ayuda.',
        ['**I am happy / sad / tired / angry / surprised.**', '**How do you feel?** = ¿Cómo te sientes?', '**Are you OK?** — **Can I help you?** — **Thank you for helping me.**'],
        [
            completa('«Estoy cansado» se dice I am ___.', 'tired', ['happy', 'hungry']),
            identifica('A friend is sad. What can you say?', 'Are you OK? Do you want to talk?', ['Go away!', 'I am hungry.']),
        ]
    ),

];
