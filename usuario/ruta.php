<?php
/**
 * usuario/ruta.php — Mis actividades
 *
 * Lo que ve un niño que entró por su clase: los pasos que le mandó su
 * docente, en orden, y **uno solo encendido**.
 *
 * Sustituye a «Mi espacio» para las cuentas de estudiante. Aquella
 * pantalla está escrita para una familia que elige —doce materias,
 * novedades, favoritas, el medidor del plan— y para un niño de seis años
 * con una tarea concreta es ruido: al abrirla tenía 384 actividades
 * delante y ninguna indicación de por dónde empezar.
 *
 * Aquí no hay nada que elegir. Hay lo que hay que hacer.
 *
 * La gamificación se queda entera —monedas, estrellas, racha— porque es
 * lo que hace volver, y no tiene nada que ver con qué contenido se abre.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

$usuario   = usuarioActual();
$usuarioId = (int) $usuario['id'];

/*
 * Cambiar de clase, para el niño que está en dos.
 *
 * Se comprueba la matrícula ANTES de guardarlo: `?clase=8` es un dato del
 * navegador. Y se guarda en `aula_curso`, que ya es «la clase en la que
 * estoy trabajando ahora» y la que mira `cursoDeTrabajo()` para decidir
 * a qué curso cuenta lo que juegue — que es justo lo que el niño acaba de
 * decir al elegir. Aquella función la vuelve a validar por su cuenta.
 */
$elegida = getEntero('clase');

if ($elegida > 0) {
    $suya = traerValor(
        'SELECT 1 FROM course_students cs
           JOIN courses c ON c.id = cs.course_id AND c.status = "active"
          WHERE cs.user_id = ? AND cs.course_id = ?',
        [$usuarioId, $elegida]
    );

    if ($suya) {
        $_SESSION['aula_curso'] = $elegida;
        olvidarRuta();
    }

    redirigir('usuario/ruta.php');
}

/*
 * Quien no vive en una ruta —una familia, un docente, un estudiante al
 * que todavía no le han asignado nada— tiene su espacio de siempre. No
 * se le deja aquí mirando una lista vacía.
 */
if (!enRutaGuiada()) {
    redirigir('usuario/');
}

$curso   = cursoDeRuta();
$pasos   = rutaActual()['pasos'];
$resumen = resumenDeRuta($pasos);
$ahora   = siguienteDeRuta();

// Las otras clases del niño, si está en más de una.
$misCursos = array_values(array_filter(
    cursosDelEstudiante($usuarioId),
    static fn(array $c): bool => (int) $c['actividades'] > 0
));

$titulo        = 'Mis actividades';
$seccionActiva = 'ruta';
$hojasExtra    = ['assets/css/ruta.css'];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor">

        <div class="ruta-cabeza">
            <h1><?= personajeHtml($usuario, 'chico') ?> Hola, <?= e($usuario['name']) ?></h1>
            <p class="ruta-clase">
                <?= e($curso['name']) ?><?php if (!empty($curso['docente'])): ?>
                    · con <?= e($curso['docente']) ?><?php endif; ?>
            </p>

            <div class="ruta-medidor" role="img"
                 aria-label="Llevas <?= (int) $resumen['porcentaje'] ?> por ciento">
                <span style="width:<?= (int) $resumen['porcentaje'] ?>%"></span>
            </div>
            <p class="ruta-medidor-pie">
                <?= (int) $resumen['completas'] ?> de <?= (int) $resumen['pasos'] ?>
                actividades terminadas
            </p>
        </div>

        <?php
        /*
         * El selector de clase solo aparece si de verdad hay más de una.
         * Un niño con una sola clase no debe ver un control que no hace
         * nada.
         */
        ?>
        <?php if (count($misCursos) > 1): ?>
            <div class="ruta-clases">
                <?php foreach ($misCursos as $c): ?>
                    <a class="<?= (int) $c['id'] === (int) $curso['id'] ? 'activa' : '' ?>"
                       href="<?= e(url('usuario/ruta.php?clase=' . (int) $c['id'])) ?>">
                        <?= e($c['name']) ?>
                    </a>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>


        <?php if (!$pasos): ?>

            <div class="ruta-final">
                <span class="ruta-emoji" aria-hidden="true">🌱</span>
                <h2>Todavía no hay nada que hacer</h2>
                <p>Tu profe te mandará actividades pronto. Vuelve a mirar más tarde.</p>
            </div>

        <?php else: ?>

            <ol class="ruta-pasos">
                <?php foreach ($pasos as $p): ?>
                    <?php
                    $esAhora = !empty($p['es_siguiente']);
                    $hecha   = !empty($p['completa']);
                    $cerrada = empty($p['abierta']);

                    $clase = $hecha ? 'hecha' : ($esAhora ? 'ahora' : ($cerrada ? 'cerrada' : ''));
                    ?>
                    <li class="ruta-paso <?= $clase ?>">

                        <span class="ruta-numero" aria-hidden="true">
                            <?php if ($hecha): ?>✓<?php elseif ($cerrada): ?>🔒<?php else: ?><?= (int) $p['paso'] ?><?php endif; ?>
                        </span>

                        <div>
                            <h2>
                                <?= e($p['icon'] ?? '') ?> <?= e($p['title']) ?>
                            </h2>

                            <p class="ruta-meta">
                                <?php if ($hecha): ?>
                                    ¡Terminada! ⭐
                                <?php elseif ($cerrada): ?>
                                    Primero termina la de arriba
                                <?php else: ?>
                                    <?= (int) $p['hechas'] ?> de <?= (int) $p['total'] ?> partes
                                    <?php if (!empty($p['due_date'])): ?>
                                        · para el <?= e(fechaLarga($p['due_date'])) ?>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </p>

                            <?php if (!$cerrada && !$hecha && (int) $p['total'] > 0): ?>
                                <div class="ruta-barra">
                                    <span style="width:<?= (int) round($p['hechas'] * 100 / max(1, $p['total'])) ?>%"></span>
                                </div>
                            <?php endif; ?>
                        </div>

                        <?php
                        /*
                         * Botón SOLO en lo que se puede tocar. Un botón
                         * apagado en un paso cerrado es una invitación a
                         * pulsarlo veinte veces.
                         */
                        ?>
                        <?php if (!$cerrada): ?>
                            <a class="btn <?= $esAhora ? 'btn-principal' : 'btn-secundario btn-chico' ?>"
                               href="<?= e(url('actividades/jugar.php?a=' . urlencode($p['slug']))) ?>">
                                <?= $hecha ? 'Otra vez' : ($esAhora ? '▶ JUGAR' : 'Jugar') ?>
                            </a>
                        <?php endif; ?>

                    </li>
                <?php endforeach; ?>
            </ol>

            <?php if ($resumen['terminada']): ?>
                <div class="ruta-final">
                    <span class="ruta-emoji" aria-hidden="true">🏆</span>
                    <h2>¡Terminaste todo!</h2>
                    <p>
                        Hiciste las <?= (int) $resumen['pasos'] ?> actividades de
                        <?= e($curso['name']) ?>. Puedes repetir la que quieras.
                    </p>
                </div>
            <?php elseif ($ahora === null): ?>
                <?php
                /*
                 * Ni terminada ni con un paso disponible: pasa cuando lo
                 * asignado está sin estaciones cargadas. Se le dice, en
                 * vez de dejarlo mirando una lista muerta.
                 */
                ?>
                <div class="ruta-final">
                    <span class="ruta-emoji" aria-hidden="true">🛠️</span>
                    <h2>Esto todavía no está listo</h2>
                    <p>Avisa a tu profe: las actividades que te mandó aún no se pueden jugar.</p>
                </div>
            <?php endif; ?>

        <?php endif; ?>


        <?php
        /*
         * El marcador va ABAJO y no arriba, al revés que en «Mi espacio».
         * Arriba tiene que estar lo que hay que hacer; las monedas son el
         * premio de después, y puestas delante compiten con la tarea.
         */
        $juego   = billetera();
        $rachaU  = racha();
        $semanaU = semanaDeRacha();
        ?>
        <a class="tira-juego" style="margin-top:26px"
           href="<?= e(url('usuario/tienda.php')) ?>">

            <span class="tj-dato">
                <span class="tj-ico" aria-hidden="true">🪙</span>
                <b><?= (int) $juego['saldo'] ?></b>
                <small>monedas</small>
            </span>

            <span class="tj-dato">
                <span class="tj-ico" aria-hidden="true">⭐</span>
                <b><?= (int) $juego['estrellas'] ?></b>
                <small>estrellas</small>
            </span>

            <span class="tj-dato">
                <span class="tj-ico" aria-hidden="true">🔥</span>
                <b><?= (int) $rachaU['actual'] ?></b>
                <small><?= $rachaU['actual'] === 1 ? 'día seguido' : 'días seguidos' ?></small>
            </span>

            <span class="tj-semana">
                <?php foreach ($semanaU as $d): ?>
                    <span class="tj-dia <?= $d['jugado'] ? 'jugado' : '' ?> <?= $d['es_hoy'] ? 'hoy' : '' ?>"
                          title="<?= e($d['fecha']) ?>"><?= e($d['letra']) ?></span>
                <?php endforeach; ?>
            </span>

            <span class="tj-ir">Mi personaje →</span>
        </a>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
