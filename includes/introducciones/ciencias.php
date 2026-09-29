<?php
/**
 * Ciencias · 3.º a 6.º
 * «Antes de empezar» de cada actividad. Ver includes/introducciones.php.
 */

return [

    'planeta-del-espacio' => intro(
        'El **sistema solar** es el **Sol** y todo lo que gira a su alrededor: ocho planetas, sus lunas, asteroides y cometas. El Sol es una **estrella**: una bola enorme de gas muy caliente que produce luz y calor.',
        ['Los planetas **no** tienen luz propia: reflejan la del Sol.', 'La **Luna** es el satélite natural de la Tierra.', 'Nuestro sistema solar está dentro de una galaxia: la **Vía Láctea**.'],
        [
            completa('El Sol es una ___.', 'estrella', ['planeta', 'luna']),
            identifica('¿Cuál es el único planeta con vida conocida?', 'La Tierra', ['Marte', 'Mercurio']),
        ]
    ),

    'el-cuerpo-por-dentro' => intro(
        'Un **órgano** es una parte del cuerpo con un trabajo concreto: el corazón bombea, los pulmones respiran. Varios órganos que trabajan juntos forman un **sistema**.',
        ['**Corazón** → sistema circulatorio: mueve la sangre.', '**Pulmones** → sistema respiratorio: toman el oxígeno.', '**Estómago e intestinos** → sistema digestivo: aprovechan la comida.'],
        [
            completa('Varios órganos que trabajan juntos forman un ___.', 'sistema', ['hueso', 'músculo']),
            identifica('¿Qué hace el corazón?', 'Bombea la sangre', ['Digiere la comida', 'Piensa']),
        ]
    ),

    'cadenas-alimenticias' => intro(
        'Una **cadena alimenticia** muestra **quién se come a quién** en un ecosistema. Por ella pasa la **energía**, que empieza en el Sol y va de un ser vivo a otro.',
        ['**Productores**: las plantas, que fabrican su alimento con la luz del Sol.', '**Consumidores**: los animales. Herbívoros (plantas), carnívoros (carne), omnívoros (de todo).', '**Descomponedores**: hongos y bacterias, que devuelven los restos al suelo.'],
        [
            completa('Las plantas son los ___ de la cadena alimenticia.', 'productores', ['consumidores', 'descomponedores']),
            identifica('Un animal que solo come plantas es…', 'Herbívoro', ['Carnívoro', 'Omnívoro']),
        ],
        'Sol → hierba → conejo → zorro.'
    ),

    'fuerzas-y-movimiento' => intro(
        'Una **fuerza** es un **empujón o un halón**. Las fuerzas pueden poner algo en movimiento, frenarlo, cambiarle la dirección o deformarlo. Algunas no se ven, como la **gravedad**, que hace caer las cosas.',
        ['La **gravedad** atrae todo hacia la Tierra.', 'La **fricción** frena el movimiento: por eso en el hielo se resbala.', 'Las **máquinas simples** (rampa, palanca, polea) ayudan a hacer menos fuerza.'],
        [
            completa('La fuerza que hace caer una pelota se llama ___.', 'gravedad', ['fricción', 'electricidad']),
            identifica('¿Para qué sirve una rampa?', 'Para subir algo pesado haciendo menos fuerza', ['Para que algo pese menos', 'Para frenar']),
        ]
    ),

    'la-celula' => intro(
        'La **célula** es la unidad **más pequeña de la vida**: todo ser vivo está hecho de una o de muchísimas células. Son tan pequeñas que solo se ven con **microscopio**.',
        ['**Membrana**: la envuelve y protege.', '**Núcleo**: guarda la información y dirige a la célula.', 'La célula **vegetal** tiene además pared celular y **cloroplastos**.'],
        [
            completa('Todo ser vivo está formado por ___.', 'células', ['rocas', 'átomos de oro']),
            identifica('¿Qué parte dirige a la célula?', 'El núcleo', ['La membrana', 'La pared']),
        ],
        'Células → tejidos → órganos → sistemas → organismo.'
    ),

    'los-reinos-de-la-naturaleza' => intro(
        'Para estudiar la enorme variedad de seres vivos, los científicos los **clasifican** en cinco **reinos** según cómo son y cómo se alimentan: **animal**, **vegetal**, **hongos**, **protistas** y **móneras** (bacterias).',
        ['**Autótrofos**: fabrican su alimento (las plantas).', '**Heterótrofos**: se alimentan de otros (los animales).', 'Los animales se dividen en **vertebrados** (con columna) e **invertebrados**.'],
        [
            completa('Los animales que tienen columna vertebral son los ___.', 'vertebrados', ['invertebrados', 'insectos']),
            identifica('¿A qué reino pertenece un pino?', 'Vegetal', ['Animal', 'Hongos']),
        ]
    ),

    'las-plantas-por-dentro' => intro(
        'Cada parte de una planta tiene un trabajo. Y las plantas hacen algo que ningún animal puede: fabricar su propio alimento con la luz del Sol. Eso se llama **fotosíntesis**.',
        ['**Raíz**: sujeta y absorbe agua. **Tallo**: la transporta. **Hoja**: hace la fotosíntesis. **Flor**: la reproducción.', 'Fotosíntesis: luz + agua + dióxido de carbono → alimento + **oxígeno**.', '**Polinización**: el polen pasa de una flor a otra, casi siempre gracias a insectos.'],
        [
            completa('El proceso con el que la planta fabrica su alimento se llama ___.', 'fotosíntesis', ['digestión', 'respiración']),
            identifica('¿Qué gas liberan las plantas?', 'Oxígeno', ['Humo', 'Vapor de sal']),
        ]
    ),

    'huesos-y-musculos' => intro(
        'El **sistema locomotor** nos permite movernos. Lo forman los **huesos** (el esqueleto), que sostienen y protegen, y los **músculos**, que tiran de los huesos para moverlos.',
        ['El **cráneo** protege el cerebro; las **costillas**, el corazón y los pulmones.', 'Una **articulación** es la unión entre dos huesos: codo, rodilla.', 'Los músculos se **contraen** (se acortan) y se **relajan**.'],
        [
            completa('La unión entre dos huesos se llama ___.', 'articulación', ['músculo', 'nervio']),
            identifica('¿Qué protege el cráneo?', 'El cerebro', ['El estómago', 'Los pulmones']),
        ]
    ),

    'sistemas-del-cuerpo-humano' => intro(
        'Tres sistemas trabajan sin parar para darnos energía. El **digestivo** saca los nutrientes de la comida, el **respiratorio** toma el oxígeno del aire y el **circulatorio** lleva ambos a todo el cuerpo por la sangre.',
        ['Digestivo: boca → esófago → estómago → intestinos.', 'Respiratorio: tomamos **oxígeno** y expulsamos **dióxido de carbono**.', 'Circulatorio: el **corazón** bombea la sangre por arterias y venas.'],
        [
            completa('El gas que necesitamos tomar del aire es el ___.', 'oxígeno', ['dióxido de carbono', 'humo']),
            identifica('¿Dónde empieza la digestión?', 'En la boca', ['En el estómago', 'En el corazón']),
        ]
    ),

    'la-materia-y-sus-estados' => intro(
        '**Materia** es todo lo que tiene masa y ocupa un lugar. Puede estar en tres **estados**: **sólido** (forma fija), **líquido** (toma la forma del recipiente) y **gaseoso** (se esparce). El calor la hace cambiar de uno a otro.',
        ['**Fusión**: sólido → líquido (el hielo se derrite).', '**Evaporación**: líquido → gas (el agua hierve).', '**Solidificación**: líquido → sólido (el agua se congela).'],
        [
            completa('Cuando el hielo se derrite ocurre la ___.', 'fusión', ['evaporación', 'condensación']),
            identifica('¿En qué estado está el vapor?', 'Gaseoso', ['Sólido', 'Líquido']),
        ]
    ),

    'fuerza-luz-y-sonido' => intro(
        'Tres fenómenos que vivimos todo el día. Una **fuerza** mueve o frena las cosas. La **luz** viaja en línea recta y rebota en los espejos. El **sonido** nace de una **vibración** y viaja por el aire, el agua o los sólidos.',
        ['La **gravedad** atrae todo hacia la Tierra.', 'La luz se **refleja** en un espejo.', 'Sin vibración no hay sonido; en el vacío del espacio no se oye nada.'],
        [
            completa('El sonido se produce por ___.', 'vibraciones', ['colores', 'imanes']),
            identifica('¿Cómo viaja la luz?', 'En línea recta', ['En zigzag', 'En círculos']),
        ]
    ),

    'el-agua-un-tesoro' => intro(
        'Sin **agua** no hay vida: más de la mitad de nuestro cuerpo es agua. Es un gran **disolvente**: muchas sustancias, como la sal y el azúcar, se mezclan con ella hasta desaparecer. Y el agua dulce que podemos usar es poca.',
        ['Se **disuelven**: sal, azúcar. **No** se disuelven: aceite, arena.', 'El agua se congela a **0 °C** y hierve a **100 °C**.', 'Cuidarla: cerrar la llave, arreglar goteras, no contaminar ríos.'],
        [
            completa('El agua se congela a ___ grados.', '0', ['100', '50']),
            identifica('¿Qué NO se disuelve en el agua?', 'El aceite', ['La sal', 'El azúcar']),
        ]
    ),

    'residuos-y-reciclaje' => intro(
        'Un **residuo** es lo que sobra y ya no usamos. Si se **separa** bien, muchos materiales pueden **reciclarse** y volver a usarse. Si se mezclan con comida, se ensucian y ya no sirven.',
        ['Las **tres erres**: **Reducir** (la más importante), Reutilizar y Reciclar.', 'En Colombia: **blanca** para reciclables, **verde** para orgánicos, **negra** para lo que no se aprovecha.', 'Pilas y medicamentos son residuos **peligrosos**: van a puntos especiales.'],
        [
            completa('La primera y más importante de las tres erres es ___.', 'reducir', ['reciclar', 'reutilizar']),
            identifica('¿Dónde va una cáscara de banano?', 'Orgánicos', ['Reciclables', 'Peligrosos']),
        ]
    ),

    'microbios-y-defensas' => intro(
        'Los **microorganismos** o microbios son seres vivos tan pequeños que solo se ven con microscopio. Algunos causan enfermedades, pero **muchos son útiles**: ayudan a digerir o a hacer el yogur. El cuerpo tiene **defensas** contra los dañinos.',
        ['Se contagian por **gotitas** al toser, por las manos o por agua sucia.', 'Los **glóbulos blancos** nos defienden; la fiebre es una defensa.', 'Prevenir: **lavarse las manos**, estornudar en el codo, vacunarse.'],
        [
            completa('Las células que nos defienden de las infecciones son los glóbulos ___.', 'blancos', ['rojos', 'verdes']),
            identifica('¿Son dañinos todos los microbios?', 'No, muchos son útiles', ['Sí, todos', 'Solo los grandes']),
        ]
    ),

    'el-suelo-y-las-rocas' => intro(
        'El **suelo** es la capa de la Tierra donde crecen las plantas. No es solo tierra: tiene **minerales** de rocas desgastadas, agua, aire y **humus**, que son restos de seres vivos que lo hacen fértil.',
        ['Rocas **ígneas**: magma que se enfría. **Sedimentarias**: capas que se acumulan.', 'La **erosión** es el desgaste por el agua, el viento y el hielo.', 'Los árboles y las plantas **protegen** el suelo de la erosión.'],
        [
            completa('La materia orgánica que hace fértil el suelo se llama ___.', 'humus', ['arena', 'magma']),
            identifica('¿Qué es la erosión?', 'El desgaste del suelo y las rocas', ['Una planta', 'Un volcán']),
        ]
    ),

    'el-metodo-cientifico' => intro(
        'El **método científico** es la forma en que los científicos investigan para no engañarse: **observar**, **preguntar**, proponer una **hipótesis**, **experimentar** y sacar **conclusiones**.',
        ['Una **hipótesis** es una posible respuesta que se puede **comprobar**.', 'En un experimento se cambia **una sola cosa** a la vez.', 'Si la hipótesis resulta falsa, **también se aprende**.'],
        [
            completa('Una posible respuesta que se puede comprobar es una ___.', 'hipótesis', ['conclusión', 'observación']),
            identifica('Para saber si la luz afecta a una planta, ¿qué cambio?', 'Solo la luz', ['La luz y el agua', 'Todo a la vez']),
        ]
    ),

    'nervioso-y-hormonal' => intro(
        'Dos sistemas **coordinan** todo el cuerpo. El **nervioso** manda mensajes muy rápidos por los nervios, dirigido por el cerebro. El **endocrino** manda mensajes más lentos con **hormonas** que viajan por la sangre.',
        ['Sistema nervioso central: **cerebro** y **médula espinal**.', 'Las **hormonas** controlan el crecimiento y los cambios del cuerpo.', 'La **pubertad** es la etapa en que las hormonas preparan el cuerpo para ser adulto.'],
        [
            completa('Las hormonas viajan por el cuerpo a través de la ___.', 'sangre', ['piel', 'saliva']),
            identifica('¿Qué órganos forman el sistema nervioso central?', 'Cerebro y médula espinal', ['Corazón y pulmones', 'Estómago e hígado']),
        ]
    ),

    'ciclos-y-equilibrio' => intro(
        'En la naturaleza, el agua, el oxígeno y el carbono **no se gastan: circulan**. Pasan del aire a los seres vivos, al suelo y de vuelta, en **ciclos**. Cuando algo rompe un ciclo, todo el ecosistema pierde su **equilibrio**.',
        ['Ciclo del agua: **evaporación** → **condensación** (nubes) → **precipitación** (lluvia).', 'Las plantas toman **dióxido de carbono** y liberan **oxígeno**; nosotros al revés.', 'La **contaminación** rompe el equilibrio.'],
        [
            completa('Cuando el vapor se enfría y forma nubes ocurre la ___.', 'condensación', ['evaporación', 'fusión']),
            identifica('¿Qué gas producen las plantas y usamos para respirar?', 'Oxígeno', ['Dióxido de carbono', 'Humo']),
        ]
    ),

    'mezclas-y-separacion' => intro(
        'Una **sustancia pura** tiene un solo tipo de materia. Una **mezcla** junta dos o más. Si no se distinguen sus partes es **homogénea** (agua con azúcar); si se ven, es **heterogénea** (agua con arena).',
        ['**Filtrar**: separa un sólido de un líquido (arena y agua).', '**Evaporar**: el agua se va y queda lo disuelto (la sal).', '**Tamizar**: separa sólidos de distinto tamaño. **Decantar**: deja que lo pesado se asiente.'],
        [
            completa('Agua con arena es una mezcla ___.', 'heterogénea', ['homogénea', 'pura']),
            identifica('¿Cómo separo la sal del agua salada?', 'Evaporando el agua', ['Con un imán', 'Soplando']),
        ]
    ),

    'electricidad-y-magnetismo' => intro(
        'La **electricidad** es energía que viaja por un camino llamado **circuito**. Para que funcione, el camino tiene que estar **cerrado**. El **magnetismo** es la fuerza de los imanes, que atraen el hierro.',
        ['Un circuito: **pila** (energía), **cables** y un bombillo; si se abre, se apaga.', '**Conductores** dejan pasar la electricidad (cobre); **aislantes**, no (plástico).', 'Un imán tiene dos **polos**: iguales se repelen, distintos se atraen.'],
        [
            completa('Los materiales que no dejan pasar la electricidad son ___.', 'aislantes', ['conductores', 'imanes']),
            identifica('¿Qué necesita un circuito para funcionar?', 'Un camino cerrado', ['Un imán', 'Estar mojado']),
        ]
    ),

    'el-universo-y-el-sistema-solar' => intro(
        'El **universo** es todo lo que existe: miles de millones de galaxias con miles de millones de estrellas. La nuestra es la **Vía Láctea**, y en ella el **Sol** con sus ocho planetas forma el **sistema solar**.',
        ['Orden: Mercurio, Venus, **Tierra**, Marte, Júpiter, Saturno, Urano, Neptuno.', '**Rotación**: la Tierra gira sobre sí misma → día y noche (24 h).', '**Traslación**: gira alrededor del Sol → un año (365 días).'],
        [
            completa('El giro de la Tierra sobre sí misma, que produce el día y la noche, es la ___.', 'rotación', ['traslación', 'gravedad']),
            identifica('¿Cuál es el planeta más grande?', 'Júpiter', ['Marte', 'La Tierra']),
        ]
    ),

    'energias-del-futuro' => intro(
        'La **energía** es lo que permite que las cosas funcionen y se muevan. Las fuentes **renovables** no se agotan (sol, viento, agua). Las **no renovables** tardaron millones de años en formarse y se acaban (petróleo, carbón, gas).',
        ['Un **panel solar** aprovecha la luz; una **hidroeléctrica**, el agua en movimiento.', 'Quemar combustibles fósiles produce gases que **calientan el planeta**.', 'La energía no se crea de la nada: **se transforma**.'],
        [
            completa('Una energía que no se agota es ___.', 'renovable', ['fósil', 'eterna']),
            identifica('¿Cuál es una fuente no renovable?', 'El petróleo', ['El viento', 'El sol']),
        ]
    ),

    'los-sentidos-y-el-cerebro' => intro(
        'Los **sentidos** captan información —luz, sonidos, olores, sabores, texturas— y los **nervios** la llevan al **cerebro**, que es quien la **interpreta**. Por eso a veces el cerebro se equivoca y vemos **ilusiones**.',
        ['Vista, oído, olfato, gusto y tacto.', 'El **nervio óptico** lleva lo que ve el ojo al cerebro.', 'El ruido fuerte y constante **daña el oído** para siempre.'],
        [
            completa('El órgano que interpreta lo que captan los sentidos es el ___.', 'cerebro', ['corazón', 'estómago']),
            identifica('¿Qué es una ilusión óptica?', 'Algo que vemos distinto de como es', ['Un sueño', 'Un tipo de gafas']),
        ]
    ),

    'crucigramas-de-ciencias' => intro(
        'Estos crucigramas repasan el **vocabulario científico** de toda la materia: seres vivos, cuerpo humano, energía y planeta. En ciencias cada palabra tiene un significado preciso, y conocerlo es parte de entender.',
        ['Lee la pista: es la **definición** de la palabra.', 'Cuenta las casillas para saber cuántas letras tiene.', 'Usa las letras que se **cruzan** como pistas extra.'],
        [
            completa('Un animal que vive parte en el agua y parte en la tierra es un ___.', 'anfibio', ['mamífero', 'insecto']),
            identifica('¿Qué es la fricción?', 'Una fuerza que frena el movimiento', ['Un tipo de energía solar', 'Un órgano del cuerpo']),
        ]
    ),

];
