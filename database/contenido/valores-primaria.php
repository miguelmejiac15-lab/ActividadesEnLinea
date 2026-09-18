<?php
/**
 * valores-primaria.php — Valores y Convivencia de 1.º a 6.º
 *
 * Escrito sobre el eje «Demokratie & Gesellschaft» del Sachunterricht
 * (K3, K4) y los ejes de pluralidad y respeto de las mallas de Individuos
 * y Sociedades (K4, K5) de `MallasPrimaria/`.
 *
 * La malla alemana es inusualmente concreta en esto y vale la pena
 * seguirla de cerca: no habla de «valores» en abstracto, sino de
 * estrategias verificables —evitación, consenso, compromiso— y nombra el
 * acoso escolar por su nombre, junto con la humillación, el insulto y el
 * lenguaje discriminatorio como formas de abuso de poder.
 *
 * Eso es lo que se conserva aquí. Una actividad que dijera «hay que ser
 * buenos compañeros» no le sirve a nadie: el niño ya lo sabe y no le dice
 * qué hacer el lunes en el recreo. Lo que cambia algo es tener practicada
 * una respuesta concreta antes de necesitarla.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'valores',
    'name'       => 'Valores y Convivencia',
    'tagline'    => 'Vivir juntos: acuerdos, diferencias y conflictos bien resueltos',
    'icon'       => '🤝',
    'color'      => '#fb8c00',
    'sort_order' => 6,
],

'bloques' => [
    ['slug' => 'resolver-conflictos', 'name' => 'Resolver Conflictos', 'icon' => '🕊️', 'sort_order' => 4,
     'description' => 'Qué hacer cuando hay un problema con otro, y qué NO hacer.'],
    ['slug' => 'inclusion-y-diversidad', 'name' => 'Inclusión y Diversidad', 'icon' => '🌍', 'sort_order' => 5,
     'description' => 'Ser distintos y valer lo mismo: reconocer y frenar la exclusión.'],
    ['slug' => 'responsabilidad-y-cuidado', 'name' => 'Responsabilidad y Cuidado', 'icon' => '🤲', 'sort_order' => 6,
     'description' => 'Hacerse cargo de lo propio, de lo común y de los demás.'],
],

'actividades' => [


// =====================================================================
//  BLOQUE · RESOLVER CONFLICTOS
// =====================================================================

[
    'slug'  => 'que-es-un-conflicto',
    'title' => '¿Qué es un conflicto?',
    'description' => 'Un conflicto no es una pelea: es un desacuerdo, y se puede resolver bien o mal.',
    'objective' => 'Distinguir conflicto de agresión y reconocer las causas frecuentes de un desacuerdo.',
    'icon' => '💢', 'nivel' => 'primaria-inicial', 'bloque' => 'resolver-conflictos',
    'duracion' => 11, 'tags' => ['convivencia', 'emociones', 'comprension'],
    'estaciones' => [

        est('Conflicto no es pelea', 'La diferencia importa', '⚖️', 'opcion_multiple', [
            omp('¿Qué es un conflicto?',                ['Un desacuerdo entre dos o más personas', 'Una pelea a golpes', 'Un castigo'], 'Un desacuerdo entre dos o más personas'),
            omp('¿Es malo tener un conflicto?',         ['No, es normal; lo que importa es cómo se resuelve', 'Sí, siempre', 'Solo entre amigos'], 'No, es normal; lo que importa es cómo se resuelve'),
            omp('¿Se puede resolver un conflicto hablando?', ['Sí, casi siempre', 'No', 'Solo entre adultos'], 'Sí, casi siempre'),
            omp('¿Qué convierte un conflicto en algo grave?', ['Responder con agresión', 'Hablar del tema', 'Pedir ayuda'], 'Responder con agresión'),
            omp('Si dos quieren el mismo juguete, eso es…', ['Un conflicto', 'Un delito', 'Una amistad'], 'Un conflicto'),
        ]),

        est('¿Por qué discutimos?', 'Las causas más comunes', '🔍', 'opcion_multiple', [
            omp('¿Cuál suele ser una causa de conflicto?', ['Querer lo mismo al mismo tiempo', 'Ser amigos', 'Compartir'], 'Querer lo mismo al mismo tiempo'),
            omp('¿Puede un malentendido causar un conflicto?', ['Sí, muy a menudo', 'No', 'Solo por escrito'], 'Sí, muy a menudo'),
            omp('Si alguien me empuja sin querer, ¿es una agresión?', ['No, fue un accidente', 'Sí, siempre', 'Depende del día'], 'No, fue un accidente'),
            omp('¿Cómo sé si fue a propósito?',        ['Preguntando antes de reaccionar', 'Suponiendo lo peor', 'Devolviéndolo'], 'Preguntando antes de reaccionar'),
            omp('¿Qué pasa si no aclaro un malentendido?', ['Puede crecer y empeorar', 'Se arregla solo siempre', 'Nada'], 'Puede crecer y empeorar'),
        ]),

        est('¿Qué hago primero?', 'Antes de reaccionar', '🛑', 'opcion_multiple', [
            omp('Estoy muy enojado. ¿Qué hago primero?',  ['Me calmo antes de hablar', 'Grito', 'Empujo'], 'Me calmo antes de hablar'),
            omp('¿Sirve gritar más fuerte para tener razón?', ['No, solo empeora', 'Sí', 'A veces'], 'No, solo empeora'),
            omp('¿Qué es escuchar de verdad?',            ['Dejar que el otro termine y tratar de entenderlo', 'Esperar mi turno para hablar', 'Interrumpir'], 'Dejar que el otro termine y tratar de entenderlo'),
            omp('¿Puedo pedir ayuda para resolver algo?', ['Sí, y no es de cobardes', 'No', 'Solo si es grave'], 'Sí, y no es de cobardes'),
            omp('¿Sirve la violencia para resolver un conflicto?', ['No, crea uno nuevo', 'Sí, es rápida', 'A veces'], 'No, crea uno nuevo'),
        ]),

        est('Buena o mala reacción', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Ayuda a resolver el conflicto?', 'Responde rápido', [
                ['e' => '👂', 'n' => 'Escuchar al otro',       'ok' => true],
                ['e' => '🤛', 'n' => 'Empujar',                'ok' => false],
                ['e' => '💬', 'n' => 'Decir cómo me siento',   'ok' => true],
                ['e' => '📢', 'n' => 'Gritar más fuerte',      'ok' => false],
                ['e' => '🙋', 'n' => 'Pedir ayuda a un adulto', 'ok' => true],
                ['e' => '🤐', 'n' => 'Dejar de hablarle para siempre', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'hablar-sin-herir',
    'title' => 'Hablar sin herir',
    'description' => 'Decir lo que molesta sin atacar: hablar de lo que siento, no de lo que el otro es.',
    'objective' => 'Aplicar la comunicación asertiva para expresar una molestia sin agredir.',
    'icon' => '💬', 'nivel' => 'primaria-media', 'bloque' => 'resolver-conflictos',
    'duracion' => 12, 'tags' => ['convivencia', 'emociones', 'comprension'],
    'estaciones' => [

        /*
         * El «mensaje yo» es lo más práctico que tiene este bloque: el
         * niño ya sabe que insultar está mal, lo que no sabe es qué decir
         * en su lugar. Se practica con frases reales del recreo, no con
         * definiciones.
         */
        est('Hablar de mí, no del otro', 'El mensaje que no ataca', '🗣️', 'opcion_multiple', [
            omp('¿Cuál es una mejor forma de decirlo?',   ['Me molesta cuando me interrumpen', 'Eres un grosero', 'Nunca te callas'], 'Me molesta cuando me interrumpen'),
            omp('¿Cuál ataca a la persona?',              ['Eres un mentiroso', 'No me gustó que no me contaras', 'Me sentí mal'], 'Eres un mentiroso'),
            omp('¿Por qué funciona mejor hablar de lo que siento?', ['El otro no se pone a la defensiva', 'Porque suena bonito', 'No funciona'], 'El otro no se pone a la defensiva'),
            omp('«Siempre» y «nunca» en una queja…',      ['Suelen ser exagerados y enfadan más', 'Ayudan mucho', 'Son necesarios'], 'Suelen ser exagerados y enfadan más'),
            omp('¿Cuál es una petición clara?',           ['¿Puedes esperar a que termine de hablar?', 'Ya sabes lo que hiciste', 'Nada, olvídalo'], '¿Puedes esperar a que termine de hablar?'),
        ]),

        est('Las tres salidas', 'Evitar, ceder o acordar', '🔀', 'opcion_multiple', [
            omp('¿Qué es un acuerdo o consenso?',      ['Una solución que las dos partes aceptan', 'Que uno gane', 'Que nadie hable'], 'Una solución que las dos partes aceptan'),
            omp('¿Qué es un compromiso?',              ['Cada uno cede un poco', 'Uno cede todo', 'Nadie cede'], 'Cada uno cede un poco'),
            omp('¿Cuándo conviene evitar el conflicto?', ['Cuando es algo sin importancia', 'Siempre', 'Nunca'], 'Cuando es algo sin importancia'),
            omp('¿Qué pasa si siempre cedo yo?',       ['Me acabo sintiendo mal y el problema sigue', 'Es lo ideal', 'Nada'], 'Me acabo sintiendo mal y el problema sigue'),
            omp('¿Cuál es la mejor solución en general?', ['La que las dos partes pueden cumplir', 'La que impone el más fuerte', 'Ninguna'], 'La que las dos partes pueden cumplir'),
        ]),

        est('Ordena la conversación', 'Cómo resolver paso a paso', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos para resolver un conflicto',
            'items' => ['Calmarse', 'Contar cada uno lo que pasó', 'Escuchar al otro sin interrumpir', 'Buscar soluciones juntos', 'Acordar una y cumplirla'],
        ]),

        est('Ponte en su lugar', 'La empatía en la práctica', '🫂', 'opcion_multiple', [
            omp('¿Qué es la empatía?',                    ['Entender cómo se siente el otro', 'Estar de acuerdo siempre', 'Tener lástima'], 'Entender cómo se siente el otro'),
            omp('Un compañero llegó callado y triste. ¿Qué hago?', ['Le pregunto si está bien', 'Me burlo', 'Lo ignoro'], 'Le pregunto si está bien'),
            omp('¿Hace falta estar de acuerdo para ser empático?', ['No, basta con entender al otro', 'Sí', 'Solo con amigos'], 'No, basta con entender al otro'),
            omp('¿Qué me ayuda a entender a alguien?',    ['Preguntarle y escuchar', 'Suponer', 'Preguntarle a otros'], 'Preguntarle y escuchar'),
            omp('Si le hice daño a alguien sin querer, ¿qué hago?', ['Pido disculpas de verdad', 'Digo que fue su culpa', 'Hago como si nada'], 'Pido disculpas de verdad'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · INCLUSIÓN Y DIVERSIDAD
// =====================================================================

[
    'slug'  => 'todos-diferentes-todos-iguales',
    'title' => 'Todos diferentes, todos iguales',
    'description' => 'Somos distintos en muchas cosas y valemos exactamente lo mismo.',
    'objective' => 'Valorar la diversidad y reconocer la igualdad de derechos entre las personas.',
    'icon' => '🌍', 'nivel' => 'primaria-inicial', 'bloque' => 'inclusion-y-diversidad',
    'duracion' => 11, 'tags' => ['convivencia', 'ciudadania', 'cultura'],
    'estaciones' => [

        est('En qué somos distintos', 'Y eso está bien', '🎨', 'opcion_multiple', [
            omp('¿En qué se diferencian las personas?',  ['En muchas cosas: aspecto, gustos, historia', 'En nada', 'Solo en la edad'], 'En muchas cosas: aspecto, gustos, historia'),
            omp('¿Vale más alguien por ser más alto?',   ['No', 'Sí', 'Depende'], 'No'),
            omp('¿Vale más alguien por hablar otro idioma?', ['No, solo es distinto', 'Sí', 'Solo si es inglés'], 'No, solo es distinto'),
            omp('¿Qué tenemos todos en común?',          ['Los mismos derechos', 'El mismo color de pelo', 'La misma edad'], 'Los mismos derechos'),
            omp('¿Sería mejor un mundo donde todos fueran iguales?', ['No, la variedad lo hace más rico', 'Sí', 'Da igual'], 'No, la variedad lo hace más rico'),
        ]),

        est('Cada uno es bueno en algo', 'Distintas capacidades', '⭐', 'opcion_multiple', [
            omp('Si a un compañero le cuesta leer, ¿es menos inteligente?', ['No, cada uno aprende distinto', 'Sí', 'Un poco'], 'No, cada uno aprende distinto'),
            omp('¿Qué hago si un compañero necesita ayuda?', ['Se la ofrezco sin hacerlo sentir mal', 'Lo hago por él', 'Lo dejo solo'], 'Se la ofrezco sin hacerlo sentir mal'),
            omp('Una persona en silla de ruedas necesita…', ['Espacios accesibles, no lástima', 'Que decidan por ella', 'Nada'], 'Espacios accesibles, no lástima', '♿'),
            omp('¿Qué es una rampa en la entrada de un edificio?', ['Una forma de que todos puedan entrar', 'Un adorno', 'Un obstáculo'], 'Una forma de que todos puedan entrar'),
            omp('¿Es lo mismo tratar igual que tratar justo?', ['No siempre: algunos necesitan más apoyo', 'Sí', 'Nunca'], 'No siempre: algunos necesitan más apoyo'),
        ]),

        est('Culturas del mundo', 'Distintas formas de vivir', '🌏', 'opcion_multiple', [
            omp('¿Por qué la gente come cosas distintas en cada país?', ['Por su historia, su clima y su cultura', 'Porque unos saben más', 'Por casualidad'], 'Por su historia, su clima y su cultura'),
            omp('¿Es una costumbre rara por ser distinta a la mía?', ['No, solo es diferente', 'Sí', 'Depende del país'], 'No, solo es diferente'),
            omp('¿Cuántos pueblos indígenas hay en Colombia?', ['Más de ochenta', 'Ninguno', 'Dos'], 'Más de ochenta'),
            omp('¿Qué se puede aprender de otra cultura?',  ['Otras formas de resolver la vida', 'Nada', 'Solo comida'], 'Otras formas de resolver la vida'),
            omp('¿Qué es el respeto a la diversidad?',      ['Aceptar y valorar que somos distintos', 'Ignorar las diferencias', 'Que todos se parezcan'], 'Aceptar y valorar que somos distintos'),
        ]),

        est('Memoria del mundo', 'Encuentra las parejas', '🧠', 'memoria',
            ['🌍', '🌎', '🌏', '🕊️', '🤝', '❤️']),
    ],
],

[
    'slug'  => 'frenar-el-acoso',
    'title' => 'Frenar el acoso',
    'description' => 'Qué es el acoso escolar, en qué se diferencia de una broma y qué hacer al verlo.',
    'objective' => 'Reconocer el acoso escolar como abuso de poder y actuar frente a él.',
    'icon' => '🛑', 'nivel' => 'primaria-media', 'bloque' => 'inclusion-y-diversidad',
    'duracion' => 13, 'tags' => ['convivencia', 'seguridad', 'emociones'],
    'estaciones' => [

        est('Broma o acoso', 'La diferencia no es sutil', '🔍', 'opcion_multiple', [
            omp('¿Cuándo una broma deja de ser broma?',   ['Cuando el otro no se ríe y pide que pare', 'Nunca', 'Cuando lo dice un profesor'], 'Cuando el otro no se ríe y pide que pare'),
            omp('¿Qué caracteriza al acoso escolar?',     ['Se repite y busca hacer daño a alguien más débil', 'Ocurre una vez', 'Es entre iguales que discuten'], 'Se repite y busca hacer daño a alguien más débil'),
            omp('¿Es acoso poner apodos que hieren?',     ['Sí, si se repite y hace daño', 'No, son solo palabras', 'Solo si es delante de todos'], 'Sí, si se repite y hace daño'),
            omp('¿Es acoso dejar a alguien fuera del grupo a propósito, siempre?', ['Sí, la exclusión también es acoso', 'No', 'Solo si le pegan'], 'Sí, la exclusión también es acoso'),
            omp('¿Duele menos un insulto que un golpe?',  ['No, puede doler más y durar más', 'Sí', 'Nunca duele'], 'No, puede doler más y durar más'),
        ]),

        est('El papel de quien mira', 'Nadie es neutral', '👀', 'opcion_multiple', [
            omp('Si veo que acosan a alguien y me río, ¿qué estoy haciendo?', ['Ayudando a que siga', 'Nada', 'Defendiendo'], 'Ayudando a que siga'),
            omp('¿Qué puedo hacer si veo acoso?',       ['No reírme, acompañar a quien lo sufre y avisar', 'Grabarlo', 'Irme'], 'No reírme, acompañar a quien lo sufre y avisar'),
            omp('¿Es «sapear» contar que están acosando a alguien?', ['No, es proteger a una persona', 'Sí', 'Depende'], 'No, es proteger a una persona'),
            omp('¿Por qué muchos no dicen nada?',       ['Por miedo a ser los siguientes', 'Porque no les importa', 'Porque está bien'], 'Por miedo a ser los siguientes'),
            omp('¿Qué cambia si alguien se acerca a quien está solo?', ['Mucho: deja de estar aislado', 'Nada', 'Empeora'], 'Mucho: deja de estar aislado'),
        ]),

        est('Si me pasa a mí', 'Qué hacer', '🆘', 'opcion_multiple', [
            omp('Si me están acosando, ¿de quién es la culpa?', ['De quien acosa, nunca mía', 'Mía', 'De los dos'], 'De quien acosa, nunca mía'),
            omp('¿Qué es lo primero que debo hacer?',  ['Contárselo a un adulto de confianza', 'Aguantar', 'Vengarme'], 'Contárselo a un adulto de confianza'),
            omp('¿Sirve devolver la agresión?',        ['No, casi siempre empeora todo', 'Sí', 'Siempre'], 'No, casi siempre empeora todo'),
            omp('Si el primer adulto no me ayuda, ¿qué hago?', ['Se lo cuento a otro hasta que alguien actúe', 'Me rindo', 'Me callo'], 'Se lo cuento a otro hasta que alguien actúe'),
            omp('Si pasa por internet, ¿qué conviene?', ['Guardar la evidencia, bloquear y avisar', 'Borrarlo todo', 'Responder igual'], 'Guardar la evidencia, bloquear y avisar'),
        ]),

        est('Desafío de la convivencia', 'Cinco situaciones reales', '🏆', 'desafio_final', [
            reto('Un grupo se burla del acento de un compañero nuevo. ¿Qué hago?', ['Les digo que paren y lo acompaño', 'Me río', 'Miro para otro lado'], 'Les digo que paren y lo acompaño'),
            reto('Alguien crea un chat para burlarse de otro. ¿Qué hago?',       ['No participo y aviso a un adulto', 'Me uno', 'Lo leo sin decir nada'], 'No participo y aviso a un adulto'),
            reto('Un compañero siempre queda último al armar equipos. ¿Qué hago?', ['Lo elijo yo la próxima vez', 'Nada', 'Me río'], 'Lo elijo yo la próxima vez'),
            reto('¿Qué es el respeto?',                                          ['Tratar a otro como persona aunque piense distinto', 'Tenerle miedo', 'Estar de acuerdo'], 'Tratar a otro como persona aunque piense distinto'),
            reto('¿De quién es la responsabilidad de frenar el acoso?',           ['De todos los que lo ven, y de los adultos', 'Solo de la víctima', 'De nadie'], 'De todos los que lo ven, y de los adultos'),
        ]),
    ],
],


// =====================================================================
//  BLOQUE · RESPONSABILIDAD Y CUIDADO
// =====================================================================

[
    'slug'  => 'mis-responsabilidades',
    'title' => 'Mis responsabilidades',
    'description' => 'Lo que me toca a mí: mis cosas, mis tareas y las consecuencias de mis actos.',
    'objective' => 'Asumir responsabilidades propias y reconocer la consecuencia de las decisiones.',
    'icon' => '🎒', 'nivel' => 'primaria-inicial', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 11, 'tags' => ['convivencia', 'autocuidado', 'comprension'],
    'estaciones' => [

        est('Lo que me toca', 'Cada uno tiene sus tareas', '✅', 'opcion_multiple', [
            omp('¿Quién debe organizar mi maleta?',      ['Yo', 'Siempre otro', 'Nadie'], 'Yo', '🎒'),
            omp('¿Quién debe recoger mis juguetes?',     ['Yo, que los saqué', 'Mi hermano', 'Nadie'], 'Yo, que los saqué'),
            omp('Si prometo algo, ¿qué debo hacer?',     ['Cumplirlo', 'Olvidarlo', 'Cambiarlo sin avisar'], 'Cumplirlo'),
            omp('Si no puedo cumplir algo, ¿qué hago?',  ['Aviso a tiempo y explico', 'Desaparezco', 'Miento'], 'Aviso a tiempo y explico'),
            omp('¿Qué es ser responsable?',              ['Hacerme cargo de lo que me corresponde', 'Hacer todo perfecto', 'Obedecer sin pensar'], 'Hacerme cargo de lo que me corresponde'),
        ]),

        est('Actos y consecuencias', 'Todo lo que hago tiene efecto', '🔗', 'opcion_multiple', [
            omp('Dejo la llave abierta. ¿Consecuencia?',    ['Se desperdicia agua', 'Ninguna', 'Sale más agua para todos'], 'Se desperdicia agua'),
            omp('No estudio para una evaluación. ¿Consecuencia?', ['Probablemente me irá mal', 'Ninguna', 'Me irá mejor'], 'Probablemente me irá mal'),
            omp('Rompo algo de otro sin querer. ¿Qué hago?', ['Lo digo y ayudo a arreglarlo', 'Lo escondo', 'Culpo a otro'], 'Lo digo y ayudo a arreglarlo'),
            omp('¿Sirve echarle la culpa a otro?',          ['No, el problema sigue y pierdo confianza', 'Sí', 'A veces'], 'No, el problema sigue y pierdo confianza'),
            omp('¿Puedo equivocarme?',                      ['Sí, y lo importante es repararlo', 'No', 'Solo una vez'], 'Sí, y lo importante es repararlo'),
        ]),

        est('Cuidar lo de todos', 'Lo común también es mío', '🏫', 'opcion_multiple', [
            omp('¿De quién son los pupitres del salón?', ['De todos, y hay que cuidarlos', 'De nadie', 'Del colegio, no me importa'], 'De todos, y hay que cuidarlos'),
            omp('¿Qué hago con mi basura en el parque?', ['La llevo hasta una caneca', 'La dejo ahí', 'La escondo'], 'La llevo hasta una caneca'),
            omp('¿Está bien rayar una pared?',           ['No, daña un espacio de todos', 'Sí, si es bonito', 'Solo con lápiz'], 'No, daña un espacio de todos'),
            omp('¿Qué es un bien público?',              ['Algo que es de todos, como un parque', 'Algo que es de nadie', 'Algo del alcalde'], 'Algo que es de todos, como un parque'),
            omp('¿Qué pasa si nadie cuida lo común?',    ['Se daña y todos perdemos', 'Nada', 'Lo arregla alguien'], 'Se daña y todos perdemos'),
        ]),

        est('¿Es responsable?', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es una actitud responsable?', 'Responde rápido', [
                ['e' => '📚', 'n' => 'Entregar la tarea a tiempo',   'ok' => true],
                ['e' => '🗑️', 'n' => 'Dejar basura en el salón',     'ok' => false],
                ['e' => '🤝', 'n' => 'Cumplir lo que prometí',       'ok' => true],
                ['e' => '👉', 'n' => 'Culpar a otro de lo que hice', 'ok' => false],
                ['e' => '💧', 'n' => 'Cerrar la llave del agua',     'ok' => true],
                ['e' => '⏰', 'n' => 'Llegar siempre tarde a propósito', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'cuidar-el-entorno',
    'title' => 'Cuidar el entorno',
    'description' => 'Las decisiones pequeñas de cada día que suman: agua, energía, residuos y consumo.',
    'objective' => 'Adoptar hábitos de consumo responsable y valorar su impacto colectivo.',
    'icon' => '🌱', 'nivel' => 'primaria-media', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 12, 'tags' => ['convivencia', 'ciudadania', 'observacion'],
    'estaciones' => [

        est('Pequeñas decisiones', 'Suman más de lo que parece', '🔢', 'opcion_multiple', [
            omp('Si un millón de personas cierran la llave al cepillarse…', ['Se ahorra muchísima agua', 'No cambia nada', 'Se gasta más'], 'Se ahorra muchísima agua'),
            omp('¿Sirve que solo yo recicle?',            ['Sí, y además da ejemplo', 'No', 'Solo si reciclan todos'], 'Sí, y además da ejemplo'),
            omp('¿Qué gasta menos: una ducha corta o llenar la tina?', ['La ducha corta', 'La tina', 'Igual'], 'La ducha corta'),
            omp('¿Qué hago con la luz de un cuarto vacío?', ['La apago', 'La dejo', 'Enciendo otra'], 'La apago'),
            omp('¿Por qué importa lo que hace una sola persona?', ['Porque somos muchos y el efecto se suma', 'No importa', 'Solo si es adulto'], 'Porque somos muchos y el efecto se suma'),
        ]),

        est('Necesito o quiero', 'La diferencia que cambia el consumo', '🛒', 'opcion_multiple', [
            omp('¿Qué es una necesidad?',               ['Algo indispensable para vivir', 'Algo que me gusta', 'Un capricho'], 'Algo indispensable para vivir'),
            omp('¿Qué es un deseo?',                    ['Algo que quiero pero no necesito', 'Algo obligatorio', 'Una tarea'], 'Algo que quiero pero no necesito'),
            omp('¿Es el agua una necesidad o un deseo?', ['Una necesidad', 'Un deseo', 'Ninguno'], 'Una necesidad'),
            omp('Antes de pedir algo nuevo, conviene preguntarse…', ['¿De verdad lo necesito?', '¿Cuánto cuesta?', 'Nada'], '¿De verdad lo necesito?'),
            omp('¿Qué es el consumo responsable?',      ['Comprar pensando en lo que hace falta y su impacto', 'No comprar nada', 'Comprar lo más barato'], 'Comprar pensando en lo que hace falta y su impacto'),
        ]),

        est('Cuidar a los animales', 'Responsabilidad con otros seres', '🐾', 'opcion_multiple', [
            omp('¿Qué significa tener una mascota?',    ['Un compromiso de años, no un juguete', 'Un juguete', 'Un regalo temporal'], 'Un compromiso de años, no un juguete'),
            omp('¿Qué necesita una mascota?',           ['Comida, agua, salud, espacio y cariño', 'Solo comida', 'Nada'], 'Comida, agua, salud, espacio y cariño'),
            omp('¿Está bien abandonar un animal?',      ['No, nunca', 'Sí, si molesta', 'Depende'], 'No, nunca'),
            omp('¿Qué hago si veo un animal maltratado?', ['Aviso a un adulto o a las autoridades', 'Nada', 'Me río'], 'Aviso a un adulto o a las autoridades'),
            omp('¿Se pueden tener animales silvestres como mascota?', ['No, su lugar es la naturaleza y es ilegal', 'Sí', 'Solo los pequeños'], 'No, su lugar es la naturaleza y es ilegal'),
        ]),

        est('Desafío del ciudadano', 'Cinco decisiones', '🏆', 'desafio_final', [
            reto('Ves a alguien tirando basura al río. ¿Qué haces?', ['Le digo y aviso a un adulto', 'Nada', 'Tiro yo también'], 'Le digo y aviso a un adulto'),
            reto('¿Qué se puede hacer con la ropa que ya no me queda?', ['Donarla o pasarla a alguien', 'Botarla', 'Guardarla para siempre'], 'Donarla o pasarla a alguien'),
            reto('¿Por qué importa apagar los aparatos?', ['La energía cuesta recursos y contamina producirla', 'Por el ruido', 'No importa'], 'La energía cuesta recursos y contamina producirla'),
            reto('¿Qué es un huerto escolar?',           ['Un espacio para cultivar y aprender a cuidar', 'Un jardín decorativo', 'Un depósito'], 'Un espacio para cultivar y aprender a cuidar'),
            reto('¿Puede un niño hacer algo por el ambiente?', ['Sí, mucho, empezando por su casa', 'No', 'Solo cuando crezca'], 'Sí, mucho, empezando por su casa'),
        ]),
    ],
],


// =====================================================================
//  AMPLIACIÓN
// =====================================================================

[
    'slug'  => 'la-amistad',
    'title' => 'La amistad',
    'description' => 'Qué hace a un buen amigo, cómo se cuida una amistad y cómo se repara.',
    'objective' => 'Reconocer las características de una relación de amistad sana.',
    'icon' => '👫', 'nivel' => 'primaria-inicial', 'bloque' => 'inclusion-y-diversidad',
    'duracion' => 11, 'tags' => ['convivencia', 'emociones', 'comprension'],
    'estaciones' => [

        est('¿Qué es un buen amigo?', 'No es el que siempre te da la razón', '💛', 'opcion_multiple', [
            omp('¿Qué hace un buen amigo?',          ['Te escucha y te acompaña', 'Te obliga a cosas', 'Se burla de ti'], 'Te escucha y te acompaña'),
            omp('¿Puede un amigo estar en desacuerdo conmigo?', ['Sí, y decírtelo con respeto', 'No', 'Nunca'], 'Sí, y decírtelo con respeto'),
            omp('Si un amigo me pide hacer algo que sé que está mal, ¿qué hago?', ['Digo que no', 'Lo hago para no perderlo', 'Me callo'], 'Digo que no'),
            omp('¿Cuántos amigos hay que tener?',    ['No importa el número, sino cómo te tratan', 'Muchos', 'Uno solo'], 'No importa el número, sino cómo te tratan'),
            omp('¿Se puede ser amigo de alguien muy distinto a mí?', ['Sí, claro', 'No', 'Solo si le gusta lo mismo'], 'Sí, claro'),
        ]),

        est('Cuidar la amistad', 'También hay que trabajarla', '🌱', 'opcion_multiple', [
            omp('¿Qué mantiene una amistad?',        ['La confianza y el tiempo compartido', 'Los regalos', 'Nada'], 'La confianza y el tiempo compartido'),
            omp('Si le prometo algo a un amigo, ¿qué hago?', ['Lo cumplo', 'Lo olvido', 'Lo cambio'], 'Lo cumplo'),
            omp('Si me cuenta un secreto, ¿qué hago?', ['Lo guardo, salvo que alguien corra peligro', 'Lo cuento', 'Lo publico'], 'Lo guardo, salvo que alguien corra peligro'),
            omp('¿Está bien tener otros amigos además?', ['Sí, no es una traición', 'No', 'Solo uno a la vez'], 'Sí, no es una traición'),
            omp('¿Puede una amistad tener discusiones?', ['Sí, y se pueden arreglar', 'No', 'Se acaba ahí'], 'Sí, y se pueden arreglar'),
        ]),

        est('Reparar', 'Después de una pelea', '🩹', 'opcion_multiple', [
            omp('Me peleé con un amigo. ¿Qué puedo hacer?', ['Hablar con él cuando estemos calmados', 'No hablarle nunca más', 'Contarlo a todos'], 'Hablar con él cuando estemos calmados'),
            omp('¿Qué es pedir disculpas de verdad?', ['Reconocer lo que hice y no repetirlo', 'Decir «perdón» rápido', 'Echar la culpa'], 'Reconocer lo que hice y no repetirlo'),
            omp('Si me piden disculpas, ¿debo aceptar de inmediato?', ['Puedo necesitar tiempo, y está bien', 'Sí, siempre al instante', 'Nunca'], 'Puedo necesitar tiempo, y está bien'),
            omp('¿Se puede recuperar la confianza?',  ['Sí, con tiempo y hechos', 'Nunca', 'Al instante'], 'Sí, con tiempo y hechos'),
            omp('¿Y si la amistad hace daño siempre?', ['Está bien alejarse', 'Hay que aguantar', 'Nunca alejarse'], 'Está bien alejarse'),
        ]),

        est('Buen amigo o no', 'Decide rápido', '⚡', 'juego_rapido',
            conTitulo('¿Es propio de un buen amigo?', 'Responde rápido', [
                ['e' => '👂', 'n' => 'Escuchar cuando estás mal',   'ok' => true],
                ['e' => '🤐', 'n' => 'Contar tus secretos a otros', 'ok' => false],
                ['e' => '🎉', 'n' => 'Alegrarse por tus logros',    'ok' => true],
                ['e' => '😒', 'n' => 'Burlarse de ti frente a otros', 'ok' => false],
                ['e' => '🤝', 'n' => 'Cumplir lo que prometió',     'ok' => true],
                ['e' => '👉', 'n' => 'Obligarte a hacer algo que no quieres', 'ok' => false],
            ])),
    ],
],

[
    'slug'  => 'honestidad-y-confianza',
    'title' => 'Honestidad y confianza',
    'description' => 'Por qué la verdad importa aunque cueste, y qué pasa cuando se rompe la confianza.',
    'objective' => 'Valorar la honestidad y reconocer las consecuencias del engaño.',
    'icon' => '🤞', 'nivel' => 'primaria-media', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 12, 'tags' => ['convivencia', 'ciudadania', 'comprension'],
    'estaciones' => [

        est('Decir la verdad', 'Aunque no convenga', '💬', 'opcion_multiple', [
            omp('Rompí algo sin querer. ¿Qué hago?',   ['Lo digo yo mismo', 'Espero a que lo descubran', 'Culpo a otro'], 'Lo digo yo mismo'),
            omp('¿Por qué es mejor decir la verdad pronto?', ['El problema crece si se oculta', 'Porque es más rápido', 'No es mejor'], 'El problema crece si se oculta'),
            omp('¿Qué pasa cuando alguien miente mucho?', ['Dejan de creerle aunque diga la verdad', 'Nada', 'Le creen más'], 'Dejan de creerle aunque diga la verdad'),
            omp('¿Es honesto copiar en una evaluación?', ['No', 'Sí, si nadie ve', 'Depende de la nota'], 'No'),
            omp('¿A quién engaña alguien que copia?',  ['Sobre todo a sí mismo', 'Al profesor solamente', 'A nadie'], 'Sobre todo a sí mismo'),
        ]),

        est('La confianza', 'Cuesta años y se rompe en un minuto', '🔗', 'opcion_multiple', [
            omp('¿Qué es la confianza?',              ['Creer que el otro va a actuar bien', 'Un regalo', 'Una obligación'], 'Creer que el otro va a actuar bien'),
            omp('¿Cómo se gana la confianza?',        ['Cumpliendo lo que se dice, muchas veces', 'Pidiéndola', 'Con regalos'], 'Cumpliendo lo que se dice, muchas veces'),
            omp('¿Cómo se pierde?',                   ['Con un engaño puede bastar', 'Nunca se pierde', 'Con el tiempo sola'], 'Con un engaño puede bastar'),
            omp('¿Se puede recuperar la confianza perdida?', ['Sí, pero cuesta mucho más que ganarla', 'Nunca', 'Al instante'], 'Sí, pero cuesta mucho más que ganarla'),
            omp('¿Por qué importa la confianza en un grupo?', ['Sin ella no se puede trabajar juntos', 'No importa', 'Solo entre adultos'], 'Sin ella no se puede trabajar juntos'),
        ]),

        est('Situaciones difíciles', 'Cuando no es tan obvio', '🤔', 'opcion_multiple', [
            omp('Encuentro dinero en el patio. ¿Qué hago?', ['Lo entrego a un adulto', 'Me lo quedo', 'Lo reparto'], 'Lo entrego a un adulto'),
            omp('Vi a alguien hacer algo malo y me piden que no diga nada. ¿Qué hago?', ['Lo cuento si hay alguien perjudicado', 'Me callo siempre', 'Lo cuento a todos'], 'Lo cuento si hay alguien perjudicado'),
            omp('Me dieron más vueltas de las que corresponden. ¿Qué hago?', ['Lo devuelvo', 'Me lo quedo', 'No digo nada'], 'Lo devuelvo'),
            omp('¿Está bien mentir para no herir a alguien?', ['A veces se puede ser amable sin mentir', 'Siempre', 'Nunca se puede ser amable'], 'A veces se puede ser amable sin mentir'),
            omp('¿Cambia lo correcto según si me ven o no?', ['No', 'Sí', 'Depende'], 'No'),
        ]),

        est('Desafío de la integridad', 'Cinco decisiones', '🏆', 'desafio_final', [
            reto('Me equivoqué en un trabajo en grupo. ¿Qué hago?', ['Lo reconozco y ayudo a arreglarlo', 'Culpo al grupo', 'Me callo'], 'Lo reconozco y ayudo a arreglarlo'),
            reto('Un compañero me pide copiar. ¿Qué hago?',   ['Le digo que no y le ofrezco explicarle', 'Le dejo copiar', 'Lo acuso'], 'Le digo que no y le ofrezco explicarle'),
            reto('¿Qué es la integridad?',                    ['Actuar igual aunque nadie mire', 'Ser el mejor', 'Obedecer'], 'Actuar igual aunque nadie mire'),
            reto('¿Vale más una nota alta obtenida copiando?', ['No, no refleja lo que sé', 'Sí', 'Depende'], 'No, no refleja lo que sé'),
            reto('¿Por qué cuesta a veces decir la verdad?',  ['Por miedo a las consecuencias', 'Porque es aburrido', 'No cuesta'], 'Por miedo a las consecuencias'),
        ]),
    ],
],

[
    'slug'  => 'esfuerzo-y-perseverancia',
    'title' => 'Esfuerzo y perseverancia',
    'description' => 'Equivocarse es parte de aprender, y rendirse rápido es lo único que sí lo impide.',
    'objective' => 'Valorar el esfuerzo sostenido y reinterpretar el error como parte del aprendizaje.',
    'icon' => '🧗', 'nivel' => 'primaria-media', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 12, 'tags' => ['emociones', 'autocuidado', 'comprension'],
    'estaciones' => [

        /*
         * Lo que se entrena aquí no es «echarle ganas»: es cambiar la
         * lectura del error. Un niño que cree que fallar demuestra que no
         * sirve deja de intentar; uno que cree que fallar es información
         * vuelve a intentarlo. La diferencia no está en el esfuerzo, está
         * en lo que cree que significa el fallo.
         */
        est('Equivocarse', 'Es información, no un veredicto', '🔁', 'opcion_multiple', [
            omp('Fallé un ejercicio. ¿Qué significa?',  ['Que todavía no lo domino', 'Que soy malo en eso', 'Que no debo intentarlo'], 'Que todavía no lo domino'),
            omp('¿Qué se aprende de un error?',        ['Dónde está el fallo para corregirlo', 'Nada', 'Que hay que rendirse'], 'Dónde está el fallo para corregirlo'),
            omp('¿Aprende alguien sin equivocarse nunca?', ['Casi nadie', 'Sí, todos', 'Los inteligentes sí'], 'Casi nadie'),
            omp('Una palabra que cambia todo: «no lo sé» o…', ['«todavía no lo sé»', '«nunca lo sabré»', '«no importa»'], '«todavía no lo sé»'),
            omp('¿Es lo mismo fallar que fracasar?',   ['No, fallar es parte del camino', 'Sí', 'Fallar es peor'], 'No, fallar es parte del camino'),
        ]),

        est('El esfuerzo', 'Lo que sí depende de mí', '💪', 'opcion_multiple', [
            omp('¿Se puede mejorar en algo que me cuesta?', ['Sí, con práctica adecuada', 'No', 'Solo si tengo talento'], 'Sí, con práctica adecuada'),
            omp('¿Sirve practicar siempre igual sin corregir?', ['No mucho: hay que corregir el error', 'Sí', 'Es lo mejor'], 'No mucho: hay que corregir el error'),
            omp('¿Qué es más útil, practicar 10 minutos cada día o 2 horas una vez?', ['10 minutos cada día', '2 horas una vez', 'Da igual'], '10 minutos cada día'),
            omp('Si algo me sale a la primera, ¿aprendí mucho?', ['Probablemente ya lo sabía', 'Sí, muchísimo', 'No aprendí nada nunca'], 'Probablemente ya lo sabía'),
            omp('¿Está bien pedir ayuda?',             ['Sí, es parte de aprender', 'No, es rendirse', 'Solo al final'], 'Sí, es parte de aprender'),
        ]),

        est('Cuando quiero rendirme', 'Estrategias reales', '🧗', 'opcion_multiple', [
            omp('Llevo rato atascado. ¿Qué hago primero?', ['Descanso un momento y vuelvo', 'Abandono', 'Sigo enojado'], 'Descanso un momento y vuelvo'),
            omp('Si algo es muy difícil, ¿qué ayuda?', ['Partirlo en pasos más pequeños', 'Hacerlo todo de golpe', 'Dejarlo'], 'Partirlo en pasos más pequeños'),
            omp('¿Ayuda comparar mi avance con el de otro?', ['Ayuda más compararme conmigo mismo de antes', 'Sí, siempre', 'Nunca me comparo'], 'Ayuda más compararme conmigo mismo de antes'),
            omp('¿Qué hago cuando por fin lo logro?',  ['Reconozco el esfuerzo que me costó', 'Lo olvido', 'Digo que fue fácil'], 'Reconozco el esfuerzo que me costó'),
            omp('¿Qué es la perseverancia?',           ['Seguir intentando de forma inteligente', 'Repetir lo mismo mil veces', 'No parar nunca'], 'Seguir intentando de forma inteligente'),
        ]),

        est('Desafío del esfuerzo', 'Cinco situaciones', '🏆', 'desafio_final', [
            reto('Saqué mala nota. ¿Qué es lo más útil?',   ['Ver qué fallé y practicarlo', 'Enojarme', 'Decir que la prueba estaba mal'], 'Ver qué fallé y practicarlo'),
            reto('No me sale un ejercicio de matemáticas. ¿Qué hago?', ['Pido que me expliquen la parte que no entiendo', 'Copio la respuesta', 'Lo dejo en blanco'], 'Pido que me expliquen la parte que no entiendo'),
            reto('¿Qué tienen en común quienes llegan lejos?', ['Practicaron mucho tiempo y corrigieron', 'Nacieron sabiendo', 'Tuvieron suerte'], 'Practicaron mucho tiempo y corrigieron'),
            reto('¿Es útil el aburrimiento al practicar?',  ['Puede avisar de que hay que cambiar la forma de practicar', 'Siempre significa parar', 'No significa nada'], 'Puede avisar de que hay que cambiar la forma de practicar'),
            reto('¿Qué es más importante: el resultado o el proceso?', ['El proceso enseña, el resultado solo mide', 'El resultado', 'Ninguno'], 'El proceso enseña, el resultado solo mide'),
        ]),
    ],
],


[
    'slug'  => 'trabajo-en-equipo',
    'title' => 'Trabajo en equipo',
    'description' => 'Repartir tareas, escuchar propuestas y sacar adelante algo entre varios.',
    'objective' => 'Aplicar estrategias de colaboración y reparto justo de responsabilidades.',
    'icon' => '🧑‍🤝‍🧑', 'nivel' => 'primaria-media', 'bloque' => 'responsabilidad-y-cuidado',
    'duracion' => 12, 'tags' => ['convivencia', 'comprension', 'logica'],
    'estaciones' => [

        est('Repartir el trabajo', 'Que a nadie le toque todo', '📋', 'opcion_multiple', [
            omp('¿Cómo se reparte bien un trabajo en grupo?', ['Según lo que cada uno puede aportar, y de forma pareja', 'Todo al que más sabe', 'Nadie hace nada'], 'Según lo que cada uno puede aportar, y de forma pareja'),
            omp('Si un compañero no hace su parte, ¿qué hago primero?', ['Hablo con él', 'Lo acuso de una', 'Hago su parte callado'], 'Hablo con él'),
            omp('¿Es justo que uno haga todo el trabajo?',  ['No', 'Sí, si es el más rápido', 'Da igual'], 'No'),
            omp('¿Qué conviene acordar al principio?',      ['Quién hace qué y para cuándo', 'Nada', 'El título'], 'Quién hace qué y para cuándo'),
            omp('¿Sirve poner una fecha antes de la entrega real?', ['Sí, deja margen para revisar', 'No', 'Solo si sobra tiempo'], 'Sí, deja margen para revisar'),
        ]),

        est('Escuchar propuestas', 'La mejor idea puede no ser la mía', '👂', 'opcion_multiple', [
            omp('Un compañero propone algo distinto a lo mío. ¿Qué hago?', ['Lo escucho y lo valoro', 'Lo descarto', 'Me enojo'], 'Lo escucho y lo valoro'),
            omp('¿Cómo se decide entre dos ideas buenas?',  ['Se comparan sus ventajas y se acuerda', 'Gana el que grita', 'Se hacen las dos'], 'Se comparan sus ventajas y se acuerda'),
            omp('¿Está bien criticar una idea?',            ['Sí, la idea; nunca a la persona', 'No, nunca', 'Solo si soy el líder'], 'Sí, la idea; nunca a la persona'),
            omp('Si mi idea no se elige, ¿qué hago?',       ['Apoyo la que se eligió', 'Me retiro', 'Saboteo'], 'Apoyo la que se eligió'),
            omp('¿Qué es un buen líder de grupo?',          ['Quien organiza y hace que todos participen', 'Quien manda', 'Quien hace todo'], 'Quien organiza y hace que todos participen'),
        ]),

        est('Cuando algo sale mal', 'Responder como grupo', '🔧', 'opcion_multiple', [
            omp('El trabajo salió mal. ¿De quién es la responsabilidad?', ['De todo el grupo', 'Del que menos hizo', 'De nadie'], 'De todo el grupo'),
            omp('¿Sirve buscar culpables?',              ['Sirve más buscar soluciones', 'Sí, mucho', 'Es lo primero'], 'Sirve más buscar soluciones'),
            omp('Si veo que vamos tarde, ¿qué hago?',    ['Lo digo pronto para reorganizar', 'Espero al final', 'Me callo'], 'Lo digo pronto para reorganizar'),
            omp('¿Qué se hace al terminar un trabajo en grupo?', ['Revisarlo entre todos', 'Entregarlo sin mirar', 'Repartir culpas'], 'Revisarlo entre todos'),
            omp('¿Qué se aprende de un trabajo en grupo que salió mal?', ['Cómo organizarnos mejor la próxima vez', 'Nada', 'Que hay que trabajar solo'], 'Cómo organizarnos mejor la próxima vez'),
        ]),

        est('Ordena el trabajo en grupo', 'Los pasos', '🔢', 'ordenar_secuencia', [
            'title' => 'Ordena los pasos de un trabajo en equipo',
            'items' => ['Entender qué se pide', 'Proponer ideas entre todos', 'Repartir las tareas', 'Hacer cada parte', 'Juntar, revisar y entregar'],
        ]),
    ],
],

],

'reasignar' => [],

];
