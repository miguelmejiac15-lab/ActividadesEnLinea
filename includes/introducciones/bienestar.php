<?php
/**
 * Vida y Bienestar · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'mitos-sobre-la-alimentacion' => intro(
        'Un **mito** es algo que mucha gente repite como si fuera cierto, pero que **no tiene pruebas**. Sobre la comida circulan muchos. Para no caer en ellos hay que preguntarse: ¿quién lo dice?, ¿con qué **evidencia**?, ¿le conviene que yo lo crea?',
        ['La **etiqueta** dice la verdad del producto: el primer ingrediente es el que más tiene.', 'Un personaje divertido en la caja **no dice nada** de si es saludable.', 'Comer **variado** es lo que da todos los nutrientes.'],
        [
            completa('Algo que se repite como cierto pero no tiene pruebas es un ___.', 'mito', ['hecho', 'nutriente']),
            identifica('En la lista de ingredientes, el primero es…', 'El que más cantidad tiene', ['El más rico', 'El más barato']),
        ]
    ),

    'que-harias-en-esta-situacion' => intro(
        'Tomar una **decisión responsable** es pensar **antes de actuar**: qué puede pasar, a quién afecta y si necesito ayuda. Pedir ayuda cuando algo te supera no es debilidad: es **buen juicio**.',
        ['Si no estás seguro, pregunta a un **adulto de confianza**.', 'Puedes decir **que no** a algo peligroso y proponer otra cosa.', 'En internet: no des datos a desconocidos y **avisa**.'],
        [
            completa('Pedir ayuda cuando algo te supera es señal de buen ___.', 'juicio', ['humor', 'apetito']),
            identifica('¿Quién es un adulto de confianza?', 'Alguien que te cuida y en quien confías', ['Cualquier persona mayor', 'Alguien que conociste en internet']),
        ]
    ),

    'dormir-y-descansar' => intro(
        'Dormir **no es tiempo perdido**. Mientras duermes, el cuerpo se **repara**, crece y el cerebro **guarda lo que aprendiste** en el día. Un niño de tu edad necesita entre **9 y 11 horas** de sueño.',
        ['La luz de las **pantallas** antes de dormir dificulta el sueño.', 'Acostarse **a la misma hora** ayuda al cuerpo a acostumbrarse.', 'Una rutina de noche: guardar pantallas, cepillarse, leer, dormir.'],
        [
            completa('Un niño de 8 años debe dormir entre 9 y ___ horas.', '11', ['5', '15']),
            identifica('¿Qué hace el cerebro mientras dormimos?', 'Guarda lo aprendido', ['Se apaga del todo', 'Olvida el día']),
        ]
    ),

    'que-hago-si-pasa-algo' => intro(
        'En una **emergencia** lo más importante es **no ponerte en peligro** tú y **pedir ayuda** rápido. En Colombia, el número único de emergencias es el **123**. Hay cosas que **no** se deben hacer porque empeoran la situación.',
        ['1. Respira y mira si hay peligro para ti. 2. Llama a un adulto o al **123**.', '**No muevas** a alguien que se cayó y no puede moverse.', 'Una quemadura leve va bajo **agua fría** un rato.'],
        [
            completa('La línea de emergencias en Colombia es el ___.', '123', ['911', '000']),
            identifica('Alguien se cayó y no se puede mover. ¿Qué hago?', 'No lo muevo y busco ayuda', ['Lo levanto rápido', 'Lo dejo solo y me voy']),
        ]
    ),

    'juegos-predeportivos' => intro(
        'Un **juego predeportivo** es un juego con reglas sencillas que prepara para un deporte de verdad. Las **reglas** existen para que todos jueguen en las **mismas condiciones**, y el **juego limpio** es respetarlas aunque nadie mire.',
        ['**Cooperar**: trabajar con otros por un objetivo común.', '**Competir**: medirse con otro respetando las reglas.', 'Si alguien hace **trampa**, el juego pierde sentido.'],
        [
            completa('Trabajar junto a otros por un objetivo común es ___.', 'cooperar', ['competir', 'hacer trampa']),
            identifica('¿Para qué sirven las reglas de un juego?', 'Para que todos jueguen en las mismas condiciones', ['Para que gane el más grande', 'Para aburrirse']),
        ]
    ),

    'energia-para-moverse' => intro(
        'El cuerpo es como un motor: necesita **combustible** para moverse, y ese combustible sale de los **alimentos**. Cuando haces ejercicio **gastas** energía; si gastas más de la que comes, el cuerpo usa sus **reservas**.',
        ['Antes de jugar: algo **ligero**, como una fruta.', 'Después de comer mucho, conviene **esperar** antes de hacer ejercicio.', 'La **etiqueta** de un alimento dice sus ingredientes y su valor nutricional.'],
        [
            completa('El cuerpo saca la energía de los ___.', 'alimentos', ['zapatos', 'juguetes']),
            identifica('¿Qué es bueno comer antes de una actividad física?', 'Algo ligero, como una fruta', ['Un almuerzo enorme', 'Nada durante dos días']),
        ]
    ),

    'reconocer-mis-emociones' => intro(
        'Las **emociones** son reacciones de tu cuerpo y tu mente ante lo que pasa: alegría, tristeza, enojo, miedo, sorpresa. **Ninguna es mala**: todas avisan de algo. Ponerles **nombre** es el primer paso para manejarlas.',
        ['El **enojo** avisa que algo no está bien.', 'El **miedo** nos protege del peligro.', 'Para calmarte: **respira** lento y exhala todavía más lento.'],
        [
            completa('El miedo sirve para protegernos del ___.', 'peligro', ['juego', 'desayuno']),
            identifica('Estoy muy enojado. ¿Qué hago primero?', 'Respiro despacio antes de actuar', ['Grito', 'Rompo algo']),
        ]
    ),

    'prevenir-accidentes' => intro(
        '**Prevenir** es actuar **antes** de que algo pase. La mayoría de los accidentes en casa, en la calle y en el colegio se pueden evitar si reconocemos los **riesgos** a tiempo.',
        ['Casa: cables pelados, pisos mojados, objetos calientes.', 'Calle: cruzar por la **cebra**, mirar a los dos lados, cinturón **siempre**.', 'Si algo pasa: avisar a un adulto y llamar al **123**.'],
        [
            completa('Actuar antes de que algo pase es ___.', 'prevenir', ['olvidar', 'correr']),
            identifica('¿Por dónde se cruza la calle?', 'Por la cebra o el puente', ['Entre los carros', 'Por donde sea más rápido']),
        ]
    ),

    'deportes-de-equipo' => intro(
        'En un **deporte de equipo** nadie gana solo: se gana pasando el balón, ocupando bien los espacios y apoyando al compañero. Cada deporte tiene sus **reglas**.',
        ['**Voleibol**: hasta **3 toques** por equipo; nadie toca dos veces seguidas.', '**Baloncesto**: 5 jugadores en cancha; se avanza **botando** el balón.', 'Si un compañero falla, **anímalo**.'],
        [
            completa('En voleibol, un equipo puede dar hasta ___ toques antes de pasar el balón.', '3', ['5', '10']),
            identifica('¿Cómo se avanza con el balón en baloncesto?', 'Botándolo', ['Pateándolo', 'Corriendo con él en la mano']),
        ]
    ),

    'natacion-y-agua-segura' => intro(
        'Nadar es divertido y útil, pero el agua **siempre** tiene riesgos, incluso para quien sabe nadar. Por eso hay **reglas de seguridad** que no se negocian.',
        ['**Nunca** nadar sin un adulto cerca.', 'No correr al borde de la piscina: el piso resbala.', 'Si te arrastra una **corriente**, no luches de frente: nada **paralelo a la orilla** y pide ayuda.'],
        [
            completa('Si una corriente me arrastra, nado ___ a la orilla.', 'paralelo', ['directo contra', 'lejos de']),
            identifica('¿Se puede nadar sin un adulto cerca?', 'No', ['Sí, si sé nadar', 'Solo en el mar']),
        ]
    ),

    'el-atletismo' => intro(
        'El **atletismo** reúne las pruebas más antiguas del deporte: **correr**, **saltar** y **lanzar**. Cada prueba exige una **capacidad** distinta del cuerpo.',
        ['**Velocidad**: carreras cortas como los **100 metros**.', '**Resistencia**: carreras largas como el **maratón** (más de 42 km).', 'Salir antes de la señal es una **salida en falso**.'],
        [
            completa('El maratón entrena sobre todo la ___.', 'resistencia', ['velocidad', 'puntería']),
            identifica('¿Qué prueba mide la velocidad pura?', 'Los 100 metros planos', ['El maratón', 'La caminata']),
        ]
    ),

    'higiene-del-sueno-y-rutinas' => intro(
        'Una **rutina** es organizar el día para que quepa todo lo importante: estudiar, jugar, comer y descansar. Con una buena rutina el día rinde más y hay menos carreras de último momento.',
        ['Un buen día tiene **estudio**, **juego**, **comida** y **descanso**.', 'Primero lo **urgente** (lo de mañana), luego lo que puede esperar.', 'Acostarse siempre a la **misma hora**.'],
        [
            completa('Primero se hace lo más ___.', 'urgente', ['divertido', 'fácil']),
            identifica('Tengo tarea para mañana y quiero ver videos. ¿Qué hago primero?', 'La tarea', ['Los videos', 'Nada']),
        ]
    ),

    'cuidar-los-dientes-y-la-piel' => intro(
        'Los **dientes** y la **piel** protegen el cuerpo, pero no se recuperan solos. En la vida tenemos **dos dentaduras**: la de leche y la definitiva. Si se daña un diente definitivo, **no sale otro**.',
        ['Cepillarse después de cada comida y **no compartir** el cepillo.', 'La piel nos protege del exterior; el **sol sin protección** la daña.', 'Lavarse las manos antes de comer y después de ir al baño.'],
        [
            completa('Si se daña un diente definitivo, ___ sale otro.', 'no', ['siempre', 'a veces']),
            identifica('¿Qué daña la piel a largo plazo?', 'El sol sin protección', ['El agua', 'Dormir']),
        ]
    ),

    'el-cuerpo-en-movimiento' => intro(
        'Cuando haces ejercicio, el cuerpo **responde**: el corazón late más rápido y respiras más hondo para llevar **más oxígeno** a los músculos. Con práctica mejoran las **capacidades físicas**.',
        ['**Resistencia**: aguantar un esfuerzo mucho tiempo. **Fuerza**: vencer una resistencia.', '**Velocidad** y **flexibilidad** también se entrenan.', 'Se recomienda al menos **60 minutos** de actividad al día.'],
        [
            completa('Al correr, el corazón late más rápido para llevar más ___ a los músculos.', 'oxígeno', ['agua', 'azúcar']),
            identifica('¿Qué señal indica que debo parar?', 'Dolor o mareo', ['Sudar un poco', 'Respirar rápido']),
        ]
    ),

    'atencion-y-concentracion' => intro(
        'La **atención** es la capacidad de enfocarte en una cosa. Se rompe fácil —notificaciones, ruido, hacer dos cosas a la vez— pero **se entrena** como un músculo.',
        ['El cerebro **no** hace bien dos cosas difíciles a la vez: salta entre ellas.', 'Aprender funciona mejor **preguntándote** y respondiendo sin mirar.', 'Estudiar **repartido** en varios días rinde más que todo la noche anterior.'],
        [
            completa('La concentración se entrena como un ___.', 'músculo', ['celular', 'juguete']),
            identifica('¿Qué distrae más al estudiar?', 'El teléfono con notificaciones', ['Un lápiz', 'Una ventana cerrada']),
        ]
    ),

    'pantallas-y-descanso' => intro(
        'Las pantallas son útiles y divertidas, pero muchas aplicaciones están **diseñadas para engancharte**. Demasiado tiempo cansa los **ojos**, daña la **postura** y puede afectar el **ánimo** y el sueño.',
        ['Ponte un **límite** de tiempo **antes** de empezar.', 'El celular **fuera del cuarto** al dormir.', 'En redes la gente muestra solo lo mejor: compararse suele hacer sentir peor.'],
        [
            completa('Los videos cortos están diseñados para ___.', 'engancharnos', ['aburrirnos', 'dormirnos']),
            identifica('¿Dónde NO conviene tener el celular?', 'En el cuarto al dormir', ['En la maleta apagado', 'En la sala']),
        ]
    ),

    'primeros-auxilios-basicos' => intro(
        'Los **primeros auxilios** son la ayuda inmediata que se da a alguien herido **mientras llega** la ayuda profesional. Saber qué hacer —y sobre todo qué **no** hacer— puede evitar que algo empeore.',
        ['Lo primero: llamar a un adulto o al **123**.', 'Herida pequeña: **lavar con agua limpia**. Golpe: **frío envuelto** en un paño.', 'Quemadura: agua fría corriendo. **Nunca** crema, mantequilla ni pasta de dientes.'],
        [
            completa('En una quemadura leve se pone agua ___ corriendo un rato.', 'fría', ['caliente', 'con sal']),
            identifica('¿Debo mover a alguien que se cayó fuerte?', 'No, puede empeorar una lesión', ['Sí, enseguida', 'Solo si llora']),
        ]
    ),

    'mi-cuerpo-esta-cambiando' => intro(
        'La **pubertad** es la etapa en que el cuerpo empieza a convertirse en el de un adulto. La controlan las **hormonas**, y a cada persona le llega **a su ritmo**: antes o después, todo es normal.',
        ['Se crece de estatura, cambia la voz y el cuerpo.', 'Se suda más: conviene **bañarse a diario** y lavar la ropa de deporte.', 'Que a un compañero le llegue antes **no significa nada**: es su ritmo.'],
        [
            completa('Los cambios de la pubertad los controlan las ___.', 'hormonas', ['vacunas', 'vitaminas']),
            identifica('¿A todos les llega la pubertad a la misma edad?', 'No, cada uno a su ritmo', ['Sí, a los 10 exactos', 'Solo a los niños']),
        ]
    ),

    'leer-una-etiqueta' => intro(
        'La **etiqueta** de un alimento dice lo que tiene de verdad, más allá de los dibujos y los eslóganes del paquete. En Colombia, los **sellos negros de advertencia** avisan cuando algo tiene exceso de azúcar, sal o grasas.',
        ['Los **ingredientes** van de **mayor a menor** cantidad.', '«Natural» o «light» en el paquete **no garantizan** nada por sí solos.', 'Energía rápida: **carbohidratos**. Construir músculo: **proteínas**.'],
        [
            completa('Los ingredientes van ordenados de mayor a ___ cantidad.', 'menor', ['mayor', 'igual']),
            identifica('Una caja con un dibujo animado busca…', 'Que el niño la pida', ['Que sea más sana', 'Enseñar a leer']),
        ]
    ),

    'riesgos-que-se-ven-venir' => intro(
        'Casi ningún accidente es una sorpresa: casi todos **avisan antes**. La mejor herramienta de seguridad es una pregunta: **¿qué podría salir mal?** Y para decidir, se miran dos cosas: qué tan **probable** es y qué tan **grave** sería.',
        ['Probable pero poco grave: tropezar en el salón.', 'Poco probable pero **muy grave**: cruzar una avenida corriendo. **Se evita igual**.', 'Señales de alarma: olor a gas, humo, un cable suelto.'],
        [
            completa('Antes de actuar me pregunto: ¿qué podría salir ___?', 'mal', ['caro', 'lindo']),
            identifica('¿Cuál es una señal de que algo va mal?', 'Olor a gas en la cocina', ['Música en la casa', 'Olor a pan']),
        ]
    ),

    'la-cabeza-tambien-se-cuida' => intro(
        'La **salud mental** es parte de la salud, igual que la del cuerpo. Sentir nervios, estrés o tristeza a veces es **normal**. Pero si dura semanas y no te deja vivir como siempre, es momento de **pedir ayuda**.',
        ['Nervios antes de un examen: **normal**.', 'Tristeza unos días tras una pérdida: **normal**.', 'Lo que ayuda: **hablarlo** con alguien, respirar, moverse, dormir bien.'],
        [
            completa('La salud mental es parte de la ___.', 'salud', ['tarea', 'suerte']),
            identifica('¿Cuál es una señal de alarma?', 'Que la tristeza dure semanas y me impida vivir normal', ['Estar nervioso antes de un examen', 'Reírme con mis amigos']),
        ]
    ),

];
