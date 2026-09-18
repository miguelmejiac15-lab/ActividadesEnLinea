<?php
/**
 * actividades/ver.php — Ficha de una actividad
 *
 * Muestra la información completa y, sobre todo, hace VISIBLE el modelo
 * freemium: la lista de estaciones indica cuáles puede jugar el
 * visitante ahora mismo y cuáles abre la Biblioteca Completa.
 *
 * Cuando una estación está bloqueada no se muestra un "acceso denegado":
 * se muestra qué contiene y qué la desbloquea. Es una oportunidad
 * comercial, no un error.
 */

require_once dirname(__DIR__) . '/config/config.php';

$slug = get('a');
$actividad = $slug !== '' ? actividadPorSlug($slug) : null;

if (!$actividad) {
    http_response_code(404);
    $titulo = 'Actividad no encontrada';
    $seccionActiva = 'actividades';
    require RUTA_INCLUDES . '/cabecera.php';
    ?>
    <section class="seccion">
        <div class="contenedor">
            <div class="vacio">
                <span class="ico" aria-hidden="true">🧭</span>
                <h3>No encontramos esta actividad</h3>
                <p>Puede que haya cambiado de nombre o ya no esté publicada.</p>
                <p style="margin-top:18px">
                    <a class="btn btn-principal" href="<?= e(url('actividades/')) ?>">Ver el catálogo</a>
                </p>
            </div>
        </div>
    </section>
    <?php
    require RUTA_INCLUDES . '/pie.php';
    exit;
}

/*
 * La ficha es una página comercial: enseña lo que contiene la actividad y
 * ofrece desbloquearla. A un niño de una clase eso no le sirve para nada
 * y le ofrece algo que ni puede ni debe comprar. Se le devuelve a su
 * lista con el motivo en palabras suyas.
 */
$deRuta = motivoDeRuta($actividad);

if ($deRuta !== null) {
    $aviso = invitacionPorMotivo($deRuta);
    mensaje('info', $aviso['titulo'] . '. ' . $aviso['mensaje']);
    redirigir('usuario/ruta.php');
}

/*
 * Y lo mismo con el modo niño, por la misma razón y una más.
 *
 * La API ya cierra las estaciones, así que el niño no podía JUGARLA — pero
 * la ficha seguía abriéndose si llegaba a su dirección, y enseñaba el
 * título, la descripción y la lista de ejercicios de algo que su papá
 * decidió no dejarle. Esconder una actividad del catálogo y dejarla
 * visible por URL es esconderla solo a medias.
 *
 * Encima la ficha ofrece desbloquear, y aquí la cuenta YA está suscrita:
 * sería ofrecerle comprar algo que ya está pagado.
 */
$delModo = function_exists('motivoDeModoNino') ? motivoDeModoNino($actividad) : null;

if ($delModo !== null) {
    $aviso = invitacionPorMotivo($delModo);
    mensaje('info', $aviso['titulo'] . '. ' . $aviso['mensaje']);
    redirigir('actividades/');
}

// ── Estado de acceso ─────────────────────────────────────────────────
$acceso     = accesoActividad($actividad);
$etiqueta   = etiquetaAcceso($actividad);
$resumen    = resumenEstaciones($actividad);
$estaciones = estacionesDe((int) $actividad['id']);
$preparadas = prepararEstaciones($actividad, $estaciones);
$relacionadas = actividadesRelacionadas($actividad, 4);
$completo   = tieneCatalogoCompleto();
$usuario    = usuarioActual();

$titulo      = $actividad['title'] . ' · Actividades en Línea';
$descripcion = $actividad['description'] ?? '';
$seccionActiva = 'actividades';

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('index.php')) ?>">Inicio</a>
            <span class="sep">›</span>
            <a href="<?= e(url('actividades/')) ?>">Explorar</a>
            <?php if (!empty($actividad['categoria_slug'])): ?>
                <span class="sep">›</span>
                <a href="<?= e(url('actividades/?categoria=' . urlencode($actividad['categoria_slug']))) ?>">
                    <?= e($actividad['categoria']) ?>
                </a>
            <?php endif; ?>
            <span class="sep">›</span>
            <span><?= e($actividad['title']) ?></span>
        </nav>

        <div class="ficha">

            <!-- ── Columna principal ──────────────────────────────── -->
            <div>

                <div class="ficha-cara" style="<?= !empty($actividad['categoria_color'])
                    ? 'background:' . e($actividad['categoria_color']) . '14' : '' ?>">
                    <span aria-hidden="true"><?= e($actividad['icon'] ?: '🎯') ?></span>
                    <span class="acceso <?= e($etiqueta['clase']) ?>">
                        <?= e($etiqueta['icono'] . ' ' . $etiqueta['texto']) ?>
                    </span>
                    <?php if (esNueva($actividad['published_at'])): ?>
                        <span class="insignia-nueva">Nuevo</span>
                    <?php endif; ?>
                </div>

                <h1><?= e($actividad['title']) ?></h1>

                <p class="descripcion"><?= e($actividad['description'] ?? '') ?></p>

                <?php
                /*
                 * Etiquetas: qué pone en juego esta actividad, más allá de
                 * su materia. Cada una lleva al catálogo ya filtrado, que
                 * es como se descubre «otra parecida» sin tener que
                 * adivinar en qué categoría la guardamos.
                 *
                 * Se llama `$etiquetasActividad` y no `$etiquetas` porque
                 * en este archivo `$etiqueta` ya es el sello de acceso.
                 */
                $etiquetasActividad = etiquetasDe((int) $actividad['id']);
                ?>
                <?php if ($etiquetasActividad): ?>
                    <div class="etiquetas">
                        <?php foreach ($etiquetasActividad as $t): ?>
                            <a class="etiqueta"
                               href="<?= e(url('actividades/?etiqueta=' . urlencode($t['slug']))) ?>">
                                <?= e($t['icon']) ?> <?= e($t['name']) ?>
                            </a>
                        <?php endforeach; ?>
                    </div>
                <?php endif; ?>

                <?php if (!empty($actividad['objective'])): ?>
                    <div class="bloque">
                        <h2>🎯 Objetivo de aprendizaje</h2>
                        <p><?= e($actividad['objective']) ?></p>
                    </div>
                <?php endif; ?>

                <?php if (!empty($actividad['instructions'])): ?>
                    <div class="bloque">
                        <h2>📋 Cómo se juega</h2>
                        <p><?= nl2br(e($actividad['instructions'])) ?></p>
                    </div>
                <?php endif; ?>

                <!-- ── Estaciones: el modelo 30%, visible ──────────── -->
                <?php if ($preparadas): ?>
                    <div class="bloque">
                        <h2>
                            🗺️ Estaciones
                            <span style="float:right;font-size:.82rem;font-weight:600;color:var(--texto-tenue)">
                                <?= (int) $resumen['disponibles'] ?> de <?= (int) $resumen['total'] ?> disponibles
                            </span>
                        </h2>

                        <div class="estaciones">
                            <?php foreach ($preparadas as $est): ?>
                                <div class="estacion <?= $est['desbloqueada'] ? 'abierta' : 'cerrada' ?>">
                                    <span class="num"><?= (int) $est['posicion'] ?></span>
                                    <span class="titulo">
                                        <?= e($est['icono'] ?? '') ?> <?= e($est['titulo']) ?>
                                    </span>
                                    <span class="marca" aria-hidden="true">
                                        <?= $est['desbloqueada'] ? '▶️' : '🔒' ?>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if ($resumen['bloqueadas'] > 0 && !$completo): ?>
                            <p style="margin-top:14px;font-size:.89rem;color:var(--texto-tenue)">
                                Las <?= (int) $resumen['bloqueadas'] ?> estaciones restantes se abren
                                con la Biblioteca Completa.
                            </p>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div class="bloque">
                        <h2>🗺️ Estaciones</h2>
                        <p style="color:var(--texto-tenue);font-size:.92rem">
                            Esta actividad todavía se sirve en su formato original.
                            Sus estaciones se cargarán cuando se porte al motor nuevo,
                            y entonces podrás ver aquí cuáles están disponibles con tu plan.
                        </p>
                    </div>
                <?php endif; ?>

            </div>

            <!-- ── Columna lateral ────────────────────────────────── -->
            <aside>

                <div class="bloque">
                    <h2>Ficha</h2>
                    <div class="datos-ficha">
                        <?php if (!empty($actividad['categoria'])): ?>
                            <div>
                                <span>Categoría</span>
                                <b><?= e($actividad['categoria_icon'] . ' ' . $actividad['categoria']) ?></b>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($actividad['nivel'])): ?>
                            <div>
                                <span>Nivel</span>
                                <b>
                                    <?= e($actividad['nivel']) ?>
                                    <?php if ($actividad['min_age'] && $actividad['max_age']): ?>
                                        (<?= (int) $actividad['min_age'] ?>–<?= (int) $actividad['max_age'] ?> años)
                                    <?php endif; ?>
                                </b>
                            </div>
                        <?php endif; ?>

                        <?php if (!empty($actividad['activity_type'])): ?>
                            <div><span>Tipo</span><b><?= e(ucfirst($actividad['activity_type'])) ?></b></div>
                        <?php endif; ?>

                        <?php if (!empty($actividad['duration_minutes'])): ?>
                            <div><span>Duración</span><b>≈ <?= (int) $actividad['duration_minutes'] ?> minutos</b></div>
                        <?php endif; ?>

                        <?php if ($resumen['total'] > 0): ?>
                            <div><span>Estaciones</span><b><?= (int) $resumen['total'] ?></b></div>
                        <?php endif; ?>

                        <div>
                            <span>Acceso</span>
                            <b><?= e($etiqueta['icono'] . ' ' . $etiqueta['texto']) ?></b>
                        </div>

                        <?php if (!empty($actividad['published_at'])): ?>
                            <div><span>Publicada</span><b><?= e(fechaLarga($actividad['published_at'])) ?></b></div>
                        <?php endif; ?>
                    </div>
                </div>

                <?php if ($acceso === ACCESO_BLOQUEADO): ?>

                    <!-- Bloqueada: oportunidad comercial, no error. -->
                    <div class="invitacion">
                        <span class="sello">🔒 BIBLIOTECA COMPLETA</span>
                        <h2>Esta experiencia hace parte de la Biblioteca Completa.</h2>
                        <p>
                            Desbloquea todas las actividades actuales y las nuevas que publiquemos
                            durante tu suscripción.
                        </p>
                        <a class="btn btn-oro btn-bloque" href="<?= e(url('planes/')) ?>">
                            Ver Biblioteca Completa
                        </a>
                    </div>

                <?php else: ?>

                    <?php
                    /*
                     * Sin cuenta, el botón lleva igualmente al reproductor
                     * —que es quien decide— pero anuncia lo que va a pasar.
                     * Un botón que dice «Iniciar» y termina en un formulario
                     * de registro se siente como una trampa.
                     */
                    $pedirCuenta = exigeCuentaParaJugar() && !$usuario;

                    // Una actividad sin estaciones no se puede jugar. Antes
                    // el botón llevaba al reproductor, que rebotaba de vuelta
                    // a esta misma página: el niño pulsaba «jugar» y volvía
                    // al punto de partida sin entender por qué.
                    $sinEstaciones = (int) $resumen['total'] === 0;
                    ?>
                    <div class="bloque">
                        <?php if ($sinEstaciones): ?>
                            <div class="aviso info">
                                <b>Todavía no está lista.</b>
                                Esta experiencia del sitio anterior aún no se ha
                                trasladado. Mientras tanto puedes entrar a las demás
                                de <?= e($actividad['categoria'] ?? 'esta materia') ?>.
                            </div>
                            <a class="btn btn-secundario btn-bloque" style="margin-top:12px"
                               href="<?= e(url('actividades/?categoria=' . urlencode($actividad['categoria_slug'] ?? ''))) ?>">
                                Ver otras de <?= e($actividad['categoria'] ?? 'la materia') ?>
                            </a>
                        <?php else: ?>
                        <a class="btn btn-principal btn-bloque"
                           href="<?= e(url('actividades/jugar.php?a=' . urlencode($actividad['slug']))) ?>">
                            <?= $pedirCuenta ? '🎁 Crear cuenta y jugar gratis' : '▶️ Iniciar actividad' ?>
                        </a>
                        <?php endif; ?>

                        <?php if ($pedirCuenta): ?>
                            <p style="margin-top:13px;font-size:.87rem;color:var(--texto-tenue);text-align:center">
                                La cuenta es gratis y guarda tu progreso, tus estrellas
                                y por dónde ibas.
                            </p>
                        <?php endif; ?>

                        <?php if ($acceso === ACCESO_LIMITADO && !$completo): ?>
                            <p style="margin-top:13px;font-size:.87rem;color:var(--texto-tenue);text-align:center">
                                Juegas las primeras
                                <?= $resumen['total'] > 0
                                    ? (int) $resumen['disponibles'] . ' de ' . (int) $resumen['total']
                                    : (int) $actividad['free_stations'] ?>
                                estaciones con tu plan gratuito.
                            </p>
                            <a class="btn btn-oro btn-bloque" style="margin-top:11px"
                               href="<?= e(url('planes/')) ?>">
                                🔓 Desbloquear todas
                            </a>
                        <?php endif; ?>

                        <?php if (!$usuario && !$pedirCuenta): ?>
                            <p style="margin-top:13px;font-size:.85rem;color:var(--texto-tenue);text-align:center">
                                <a href="<?= e(url('registro.php')) ?>" style="color:var(--azul);font-weight:600">
                                    Crea una cuenta gratis
                                </a>
                                para guardar tu progreso.
                            </p>
                        <?php endif; ?>
                    </div>

                <?php endif; ?>

            </aside>

        </div>

    </div>
</section>


<?php if ($relacionadas): ?>
<section class="seccion tenue">
    <div class="contenedor">
        <div class="titulo-seccion izquierda">
            <small>Sigue explorando</small>
            <h2>Más de <?= e($actividad['categoria'] ?? 'esta categoría') ?></h2>
        </div>
        <div class="rejilla-actividades">
            <?php foreach ($relacionadas as $act) {
                require RUTA_INCLUDES . '/tarjeta-actividad.php';
            } ?>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
