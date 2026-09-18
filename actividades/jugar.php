<?php
/**
 * actividades/jugar.php — Reproductor de actividades
 *
 * Dibuja el mapa de estaciones y entrega el control al motor.
 *
 * Detalle importante: esta página envía la lista de estaciones SIN el
 * contenido de los minijuegos. Lo que viaja es el título, el ícono y si
 * está desbloqueada o no. El contenido se pide después, una estación a
 * la vez, a api/estacion.php, que vuelve a comprobar el acceso.
 *
 * Así, ni el HTML ni el JavaScript de esta página contienen nada que un
 * usuario sin suscripción no deba ver.
 */

require_once dirname(__DIR__) . '/config/config.php';

$slug = get('a');
$actividad = $slug !== '' ? actividadPorSlug($slug) : null;

if (!$actividad) {
    http_response_code(404);
    mensaje('info', 'Esa actividad no existe o ya no está publicada.');
    redirigir('actividades/');
}

// ── Puerta de acceso ─────────────────────────────────────────────────

/*
 * Primero la cuenta. Sin sesión no se entra al reproductor: dejarlo
 * entrar para que encuentre las trece estaciones con candado sería
 * enseñarle una puerta abierta a un cuarto cerrado.
 *
 * Se manda a registrarse, no a iniciar sesión: quien llega hasta aquí
 * desde el catálogo casi nunca tiene cuenta todavía. El formulario de
 * registro ofrece el enlace a «ya tengo cuenta» para el otro caso.
 *
 * `destino_tras_login` hace que, al terminar, vuelva a esta misma
 * actividad y no a la portada. Es el mismo mecanismo que usa exigirSesion().
 */
if (exigeCuentaParaJugar() && !usuarioActual()) {
    $_SESSION['destino_tras_login'] = $_SERVER['REQUEST_URI'] ?? '';
    mensaje('info', 'Crea tu cuenta gratis para jugar y guardar tu progreso.');
    redirigir('registro.php');
}

/*
 * La ruta antes que el plan, y con su propio mensaje.
 *
 * Sin esto, un niño que abre una actividad que no le mandaron caería en
 * la ficha con «esta experiencia hace parte de la Biblioteca Completa»:
 * se le estaría pidiendo dinero a un menor por algo que su docente
 * simplemente no le asignó. Se le lleva a su lista y se le dice por qué.
 */
$deRuta = motivoDeRuta($actividad);

if ($deRuta !== null) {
    $aviso = invitacionPorMotivo($deRuta);
    mensaje('info', $aviso['titulo'] . '. ' . $aviso['mensaje']);
    redirigir('usuario/ruta.php');
}

/*
 * El modo niño igual, y ANTES de mirar el acceso: si cayera al `if` de
 * abajo, un niño con la cuenta de su papá —que está suscrita— leería
 * «esta experiencia hace parte de la Biblioteca Completa» por algo que su
 * papá simplemente no le dejó.
 */
$delModo = function_exists('motivoDeModoNino') ? motivoDeModoNino($actividad) : null;

if ($delModo !== null) {
    $aviso = invitacionPorMotivo($delModo);
    mensaje('info', $aviso['titulo'] . '. ' . $aviso['mensaje']);
    redirigir('actividades/');
}

$acceso = accesoActividad($actividad);

if ($acceso === ACCESO_BLOQUEADO) {
    // No se responde "acceso denegado": se lleva a la ficha, que explica
    // qué contiene la actividad y qué la desbloquea.
    mensaje('info', 'Esta experiencia hace parte de la Biblioteca Completa.');
    redirigir('actividades/ver.php?a=' . urlencode($actividad['slug']));
}

$estaciones = estacionesDe((int) $actividad['id']);

// Sin estaciones cargadas no hay nada que jugar todavía.
if (!$estaciones) {
    mensaje('info', 'Esta actividad todavía no tiene sus estaciones cargadas.');
    redirigir('actividades/ver.php?a=' . urlencode($actividad['slug']));
}

$preparadas = prepararEstaciones($actividad, $estaciones);
$resumen    = resumenEstaciones($actividad);
$usuario    = usuarioActual();

// Estaciones ya completadas, para pintarlas como hechas al entrar.
$hechas = [];
if ($usuario) {
    foreach (traerTodo(
        'SELECT station_id FROM activity_progress
          WHERE user_id = ? AND activity_id = ? AND status = "completed"',
        [$usuario['id'], $actividad['id']]
    ) as $f) {
        $hechas[(int) $f['station_id']] = true;
    }
}

// Lo que recibe el motor. Nótese que no hay ningún campo de contenido.
$paraElMotor = array_map(static function (array $e) use ($hechas): array {
    return [
        'id'           => $e['id'],
        'posicion'     => $e['posicion'],
        'titulo'       => $e['titulo'],
        'icono'        => $e['icono'],
        'desbloqueada' => $e['desbloqueada'],
        'hecha'        => isset($hechas[$e['id']]),
    ];
}, $preparadas);

$titulo        = $actividad['title'] . ' · Jugar';
$descripcion   = $actividad['description'] ?? '';
$seccionActiva = 'actividades';

require RUTA_INCLUDES . '/cabecera.php';
?>
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/juego.css')) ?>">

<div class="contenedor">
    <div class="marco-juego">

        <!-- ── Mapa de estaciones ──────────────────────────────────── -->
        <aside class="mapa">
            <h2><?= e($actividad['icon']) ?> <?= e($actividad['title']) ?></h2>
            <p class="resumen">
                <?= (int) $resumen['disponibles'] ?> de <?= (int) $resumen['total'] ?> estaciones disponibles
            </p>

            <?php
            $corteDibujado = false;
            foreach ($preparadas as $est):

                // La línea del corte se dibuja antes de la primera bloqueada.
                if (!$est['desbloqueada'] && !$corteDibujado):
                    $corteDibujado = true; ?>
                    <div class="mapa-corte">🔒 Biblioteca Completa</div>
                <?php endif; ?>

                <button class="mapa-estacion
                        <?= $est['desbloqueada'] ? '' : 'bloqueada' ?>
                        <?= isset($hechas[$est['id']]) ? 'hecha' : '' ?>"
                        data-id="<?= (int) $est['id'] ?>"
                        type="button">
                    <span class="num"><?= (int) $est['posicion'] ?></span>
                    <span class="etiqueta">
                        <?= e($est['icono'] ?? '') ?> <?= e($est['titulo']) ?>
                    </span>
                    <span class="marca" aria-hidden="true">
                        <?php if (!$est['desbloqueada']): ?>🔒
                        <?php elseif (isset($hechas[$est['id']])): ?>✅
                        <?php else: ?>▶️<?php endif; ?>
                    </span>
                </button>

            <?php endforeach; ?>

            <?php if ($resumen['bloqueadas'] > 0 && convieneInvitarADesbloquear()): ?>
                <div class="mapa-invitacion">
                    <p>
                        Te faltan <strong><?= (int) $resumen['bloqueadas'] ?></strong> estaciones
                        de esta actividad.
                    </p>
                    <a class="btn btn-oro btn-chico btn-bloque" href="<?= e(url('planes/')) ?>">
                        Desbloquear todo
                    </a>
                </div>
            <?php endif; ?>

            <div style="margin-top:14px;padding-top:12px;border-top:2px solid var(--borde-suave)">
                <a class="btn btn-secundario btn-chico btn-bloque"
                   href="<?= e(url('actividades/ver.php?a=' . urlencode($actividad['slug']))) ?>">
                    ← Volver a la ficha
                </a>
            </div>
        </aside>

        <!-- ── Escenario ───────────────────────────────────────────── -->
        <section class="escenario">
            <div class="escenario-cabeza">
                <h1 id="titulo-estacion">Elige una estación</h1>
                <div class="barra-envoltorio">
                    <div id="barra-juego"></div>
                </div>
                <span class="contador" id="texto-juego">—</span>
            </div>

            <div id="zona-juego">
                <div class="juego-cargando">Preparando la actividad…</div>
            </div>
        </section>

    </div>
</div>

<script src="<?= e(urlRecurso('assets/js/motor.js')) ?>"></script>
<script>
    // Solo metadatos: ni una palabra, ni una respuesta, ni un dato de
    // minijuego. Todo eso se pide después y solo si el servidor lo permite.
    MotorActividades.iniciar({
        base: <?= jsonSeguro(URL_BASE . '/') ?>,
        slug: <?= jsonSeguro($actividad['slug']) ?>,
        csrf: <?= jsonSeguro(tokenCsrf()) ?>,
        estaciones: <?= jsonSeguro($paraElMotor) ?>,

        /*
         * El personaje que el niño eligió y se compró con sus monedas.
         *
         * Sin esto el motor no lo conoce, y el final de la estación
         * felicitaba con un 🎉 genérico: el mismo para todos. La tienda,
         * las monedas y el personaje existían sin llegar nunca al
         * momento en que de verdad importan.
         */
        personaje: <?= jsonSeguro($usuario ? personajeDe($usuario) : null) ?>
    });
</script>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
