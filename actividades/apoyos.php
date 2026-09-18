<?php
/**
 * actividades/apoyos.php — Guía de apoyos, para adultos
 *
 * Esta página está escrita para una familia o un docente, no para un
 * niño. Es el único sitio de la plataforma donde aparecen las palabras
 * TDAH y autismo, y aparecen a propósito: es como busca un adulto.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ AQUÍ SÍ Y EN EL CATÁLOGO NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * En el catálogo, los bloques y las etiquetas se llaman por el apoyo que
 * dan —«Atención y Foco», «Cuando Algo Cambia»— y nunca por un
 * diagnóstico. La razón es concreta: un docente asigna actividades a un
 * curso, y el nombre de lo asignado lo ven el niño y sus compañeros. Una
 * etiqueta «TDAH» sobre la tarea de un niño es un dato de salud de un
 * menor puesto a la vista de su clase.
 *
 * Pero un padre que llega buscando no escribe «funciones ejecutivas»,
 * escribe el nombre del informe que tiene en la mano. Así que aquí se
 * hace la traducción —una vez, en un sitio dirigido a adultos— y desde
 * cada apoyo se entra al catálogo ya filtrado.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE ESTA PÁGINA NO ES
 * ─────────────────────────────────────────────────────────────────────
 *
 * No es un diagnóstico, ni un tratamiento, ni un sustituto de nadie. Se
 * dice de forma explícita en la página, no en letra pequeña: alguien
 * puede llegar aquí asustado y con un informe reciente, y merece leer
 * con claridad qué es esto y qué no.
 */

require_once dirname(__DIR__) . '/config/config.php';

/*
 * Esta página está escrita para adultos y nombra diagnósticos. Un niño
 * que llegue por su clase no tiene nada que hacer aquí — y menos leyendo
 * sobre TDAH o autismo. Se le devuelve a lo suyo.
 */
if (enRutaGuiada()) {
    redirigir('usuario/ruta.php');
}

/*
 * Cada apoyo se muestra con el número real de actividades que tiene. Si
 * un día un apoyo se queda sin nada, desaparece de la lista en vez de
 * ofrecer un enlace a una página vacía.
 */
$conteos = [];
foreach (etiquetasConConteo('apoyo') as $t) {
    $conteos[$t['slug']] = $t;
}

/**
 * Los apoyos, agrupados por la necesidad que atienden.
 *
 * `perfiles` es la parte que importa: nombra en voz alta a quién le suele
 * servir. Se escribe en condicional —«suele», «a menudo»— porque un
 * diagnóstico no determina lo que alguien necesita, y dos niños con el
 * mismo informe necesitan cosas distintas.
 */
$grupos = [

    [
        'titulo' => 'Concentrarse y terminar',
        'texto'  => 'Empezar una tarea, no perderse a mitad y llegar al final. '
                  . 'También frenar el impulso de contestar antes de leer.',
        'perfiles' => 'Suele hacer falta a niños con TDAH, pero igualmente a '
                    . 'cualquiera que se disperse con facilidad o vaya demasiado rápido.',
        'apoyos' => ['foco', 'organizacion'],
        'bloques' => [
            ['atencion-y-foco',  'Atención y Foco',  '🎯',
             'Encontrar lo que importa entre todo lo demás.'],
            ['parar-y-pensar',   'Parar y Pensar',   '✋',
             'El segundo que va entre leer y contestar.'],
            ['organizarme-solo', 'Organizarme Solo', '🎒',
             'Planear, acordarse y arrancar: lo de antes de la tarea.'],
        ],
    ],

    [
        'titulo' => 'Calmarse y pedir ayuda',
        'texto'  => 'Notar lo que avisa el cuerpo antes de que la emoción se haga '
                  . 'grande, y saber qué hacer y qué pedir en ese momento.',
        'perfiles' => 'Le sirve a quien se frustra rápido, a quien se bloquea, y a '
                    . 'muchos niños con TDAH o autismo. También a cualquiera en una '
                    . 'época difícil.',
        'apoyos' => ['autorregulacion'],
        'bloques' => [
            ['calma-y-cuerpo', 'Calma y Cuerpo', '🌬️',
             'Señales del cuerpo y formas concretas de bajar el volumen. Sin reloj.'],
        ],
    ],

    [
        'titulo' => 'Entender a los demás',
        'texto'  => 'Leer caras y tonos, respetar turnos y entender frases que no '
                  . 'significan lo que dicen sus palabras.',
        'perfiles' => 'Suele hacer falta a niños autistas —incluido lo que antes se '
                    . 'llamaba síndrome de Asperger— y a cualquiera al que le cueste '
                    . 'la parte no dicha de una conversación.',
        'apoyos' => ['social', 'lenguaje-claro'],
        'bloques' => [
            ['entender-a-los-demas', 'Entender a los Demás', '🫂',
             'Caras, tonos y turnos de conversación.'],
            ['lo-que-no-se-dice',    'Lo que No se Dice',    '💬',
             'Frases figuradas y reglas sociales que nadie explica en voz alta.'],
        ],
    ],

    [
        'titulo' => 'Saber qué viene y encajar los cambios',
        'texto'  => 'Anticipar la secuencia del día y tener un plan para cuando algo '
                  . 'sale distinto de lo previsto.',
        'perfiles' => 'Muy útil para niños autistas y para quien necesita rutinas '
                    . 'estables. También para cualquiera que esté empezando en un '
                    . 'sitio nuevo.',
        'apoyos' => ['anticipacion'],
        'bloques' => [
            ['cuando-algo-cambia',     'Cuando Algo Cambia',     '🔄',
             'El plan cambió: qué avisa y qué se puede hacer.'],
            ['rutinas-y-anticipacion', 'Rutinas y Anticipación', '📅',
             'Secuencias visuales que ordenan el día.'],
        ],
    ],

    [
        'titulo' => 'Ruido, luz y texturas',
        'texto'  => 'Cuando el ambiente incomoda de verdad: qué se puede pedir, qué '
                  . 'suele ayudar y por qué a cada persona le molesta algo distinto.',
        'perfiles' => 'Frecuente en niños autistas y en quienes tienen alta '
                    . 'sensibilidad sensorial, con o sin diagnóstico.',
        'apoyos' => ['sensorial'],
        'bloques' => [
            ['mis-sentidos', 'Mis Sentidos', '👂',
             'Intensidades, estrategias y respeto por lo que siente cada uno.'],
        ],
    ],

    [
        'titulo' => 'Ver, oír y leer sin barreras',
        'texto'  => 'El mismo contenido de las materias servido por otro camino: '
                  . 'mucho dibujo, poco texto y un paso a la vez.',
        'perfiles' => 'Para dificultades de visión o audición, para quien todavía no '
                    . 'lee con soltura, y para quien necesita el contenido troceado.',
        'apoyos' => [],
        'bloques' => [
            ['apoyo-visual',    'Apoyo Visual',    '👁️',  'Contrastes, siluetas y figuras grandes.'],
            ['apoyo-auditivo',  'Apoyo Auditivo',  '👂',  'Sonidos, sílabas y señas.'],
            ['lectura-facil',   'Lectura Fácil',   '📖',  'Frases simples y un dibujo por idea.'],
            ['un-paso-a-la-vez', 'Un Paso a la Vez', '👣', 'Las materias troceadas en pasos pequeños.'],
        ],
    ],
];

$totalApoyos = 0;
foreach ($conteos as $t) {
    $totalApoyos += (int) $t['total'];
}

$titulo        = 'Apoyos para aprender';
$seccionActiva = 'actividades';
require RUTA_INCLUDES . '/cabecera.php';
?>

<main class="pagina-apoyos">
    <div class="contenedor">

        <header class="apoyos-cabeza">
            <span class="apoyos-emoji" aria-hidden="true">♿</span>
            <h1>Apoyos para aprender</h1>
            <p class="apoyos-bajada">
                Actividades pensadas para quitar un obstáculo concreto: concentrarse,
                calmarse, organizarse, saber qué viene o entender lo que no se dice.
                Están repartidas por el catálogo y aquí se explica cuál sirve para qué.
            </p>
        </header>

        <?php
        /*
         * Este aviso va ARRIBA y en tamaño normal, no al pie en letra
         * pequeña. Quien llega a esta página puede venir de recibir un
         * informe hace una semana; lo primero que tiene que leer es qué
         * es esto y qué no es.
         */
        ?>
        <section class="apoyos-aclaracion">
            <h2>Antes de empezar</h2>
            <p>
                Esto <strong>no es un diagnóstico ni un tratamiento</strong>, y no sustituye
                a nadie: ni a un psicólogo, ni a un terapeuta, ni al equipo del colegio.
                Son juegos que practican habilidades concretas.
            </p>
            <p>
                <strong>Un niño no necesita ningún diagnóstico para usarlos.</strong> A casi
                todos les viene bien aprender a parar antes de responder o a pedir lo que
                necesitan con palabras. Y al revés: dos niños con el mismo informe pueden
                necesitar cosas muy distintas.
            </p>
            <p class="apoyos-nota">
                Por eso ningún bloque ni ninguna etiqueta del catálogo lleva el nombre de
                un trastorno. En esta plataforma un docente asigna actividades a todo un
                curso, y el nombre de lo asignado lo ven también los compañeros. Los
                bloques se llaman por lo que enseñan; los diagnósticos se nombran aquí,
                en la página para adultos.
            </p>
        </section>

        <?php foreach ($grupos as $g): ?>
            <section class="apoyos-grupo">

                <h2><?= e($g['titulo']) ?></h2>
                <p class="apoyos-texto"><?= e($g['texto']) ?></p>
                <p class="apoyos-perfiles">
                    <span aria-hidden="true">👥</span> <?= e($g['perfiles']) ?>
                </p>

                <?php
                // Filtros directos al catálogo. Solo los que tienen algo.
                $conAlgo = [];
                foreach ($g['apoyos'] as $slug) {
                    if (isset($conteos[$slug]) && (int) $conteos[$slug]['total'] > 0) {
                        $conAlgo[] = $conteos[$slug];
                    }
                }
                ?>

                <?php if ($conAlgo): ?>
                    <div class="apoyos-filtros">
                        <span class="apoyos-rotulo">Filtrar el catálogo:</span>
                        <?php foreach ($conAlgo as $t): ?>
                            <a class="chip"
                               href="<?= e(url('actividades/?etiqueta=' . urlencode($t['slug']))) ?>">
                                <?= e($t['icon']) ?> <?= e($t['name']) ?>
                                <small><?= (int) $t['total'] ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <div class="apoyos-bloques">
                    <?php foreach ($g['bloques'] as [$slug, $nombre, $icono, $pie]): ?>
                        <a class="apoyo-tarjeta"
                           href="<?= e(url('actividades/?bloque=' . urlencode($slug))) ?>">
                            <span class="apoyo-icono" aria-hidden="true"><?= e($icono) ?></span>
                            <span class="apoyo-nombre"><?= e($nombre) ?></span>
                            <span class="apoyo-pie"><?= e($pie) ?></span>
                        </a>
                    <?php endforeach; ?>
                </div>

            </section>
        <?php endforeach; ?>

        <section class="apoyos-cierre">
            <h2>Cómo se usan</h2>
            <ul class="apoyos-consejos">
                <li>
                    <strong>Poco y seguido</strong> funciona mejor que mucho de golpe.
                    Una actividad corta cada día rinde más que una sesión larga el domingo.
                </li>
                <li>
                    <strong>Acompañar la primera vez.</strong> Muchas de estas actividades
                    plantean situaciones —«a Leo le molesta el ruido, ¿qué puede hacer?»—
                    que dan pie a hablar de lo que le pasa a cada uno.
                </li>
                <li>
                    <strong>Ninguna pregunta es sobre quien juega.</strong> Todas hablan de
                    personajes, a propósito: el juego puntúa, y lo que alguien siente de
                    verdad no puede estar bien ni mal.
                </li>
                <li>
                    <strong>Si algo agobia, se para.</strong> El progreso se guarda solo y
                    la actividad sigue donde se dejó.
                </li>
            </ul>

            <div class="apoyos-acciones">
                <a class="btn btn-principal" href="<?= e(url('actividades/?categoria=dua')) ?>">
                    Ver todo «Aprender sin Barreras»
                </a>
                <a class="btn btn-secundario" href="<?= e(url('actividades/')) ?>">
                    Ir al catálogo completo
                </a>
            </div>
        </section>

    </div>
</main>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
