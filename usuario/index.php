<?php
/**
 * usuario/index.php — Mi espacio
 *
 * Lo primero que ve alguien que ya entró. Su trabajo es responder dos
 * preguntas en el primer vistazo:
 *
 *   1. ¿Qué me deja hacer mi plan?  — dicho en números, no en el nombre
 *      del plan: «juegas 169 de las 506 estaciones» se entiende; «tienes
 *      el plan Gratis» no dice nada.
 *
 *   2. ¿Por dónde sigo?  — continuar lo empezado, o entrar por bloques.
 *
 * La página de inicio pública lleva aquí a quien tiene sesión: no hay
 * dos puertas para lo mismo.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

/*
 * Un estudiante con ruta no aterriza aquí.
 *
 * Esta pantalla está escrita para una familia que ELIGE: doce materias,
 * novedades, favoritas, el medidor del plan. A un niño de seis años con
 * una tarea concreta eso es ruido, y era justo el problema: entraba por
 * su clase y se encontraba el catálogo entero sin saber por dónde
 * empezar. Lo suyo es la ruta. Ver `includes/ruta.php`.
 */
if (enRutaGuiada()) {
    redirigir('usuario/ruta.php');
}

$usuario = usuarioActual();
$acceso  = resumenAccesoUsuario();

$enProgreso  = actividadesEnProgreso((int) $usuario['id'], 4);
$completadas = actividadesCompletadas((int) $usuario['id']);
$favoritas   = favoritasDe((int) $usuario['id'], 4);
$nuevas      = actividadesNuevas(4);
$categorias  = categoriasConConteo();

// Para quien todavía no ha empezado nada, unas cuantas por donde entrar.
$sugeridas = $enProgreso ? [] : actividadesDestacadas(4);

// Las guías que le sirven a esta cuenta. Vacío para un estudiante de
// colegio, que no configura nada.
$misGuias = function_exists('guiasParaMi') ? guiasParaMi() : [];

$titulo        = 'Mi espacio · Actividades en Línea';
$seccionActiva = 'espacio';
$hojasExtra    = $misGuias ? ['assets/css/guia.css'] : [];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion" style="padding-bottom:34px">
    <div class="contenedor">

        <!-- ── Saludo y estado del plan ────────────────────────────── -->
        <div class="panel-bienvenida">

            <div class="bienvenida-texto">
                <h1><?= personajeHtml($usuario, 'chico') ?> Hola, <?= e($usuario['name']) ?></h1>

                <?php if ($acceso['completo']): ?>
                    <p>
                        Tienes <strong><?= e($acceso['plan']) ?></strong>: acceso completo a las
                        <strong><?= (int) $acceso['estaciones'] ?></strong> estaciones de las
                        <strong><?= (int) $acceso['actividades'] ?></strong> actividades,
                        y a todo lo que publiquemos.
                    </p>
                    <?php if ($acceso['vence']): ?>
                        <p class="vigencia">Vigente hasta el <?= e(fechaLarga($acceso['vence'])) ?>.</p>
                    <?php endif; ?>
                <?php else: ?>
                    <p>
                        Con el plan <strong><?= e($acceso['plan']) ?></strong> entras a las
                        <strong><?= (int) $acceso['actividades'] ?></strong> actividades y juegas
                        la primera parte de cada una:
                        <strong><?= (int) $acceso['disponibles'] ?></strong> de
                        <?= (int) $acceso['estaciones'] ?> estaciones.
                    </p>
                <?php endif; ?>
            </div>

            <?php if (!$acceso['completo']): ?>
                <div class="bienvenida-medidor">
                    <div class="medidor">
                        <div class="medidor-relleno" style="width:<?= (int) $acceso['porcentaje'] ?>%"></div>
                    </div>
                    <p class="medidor-pie">
                        <?= (int) $acceso['porcentaje'] ?>% disponible ·
                        <?= (int) $acceso['bloqueadas'] ?> estaciones por desbloquear
                    </p>
                    <a class="btn btn-oro btn-chico btn-bloque" href="<?= e(url('planes/')) ?>">
                        🔓 Desbloquear todo
                    </a>
                </div>
            <?php endif; ?>

        </div>

        <?php
        /*
         * El marcador de juego, justo debajo del plan.
         *
         * Va aquí y no al final por una razón concreta: lo que se ve al
         * aterrizar es lo que se convierte en costumbre. El plan dice qué
         * puedes hacer; esto dice qué has hecho, y es lo que hace volver.
         *
         * Se enseña la racha aunque esté en cero: saber que existe es lo
         * que empuja a empezarla.
         */
        $juego  = billetera();
        $rachaU = racha();
        $semanaU = semanaDeRacha();
        $logrosU = resumenLogros();
        ?>
        <a class="tira-juego" href="<?= e(url('usuario/tienda.php')) ?>">

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

            <span class="tj-dato">
                <span class="tj-ico" aria-hidden="true">🏅</span>
                <b><?= (int) $logrosU['hechos'] ?></b>
                <small>de <?= (int) $logrosU['total'] ?> logros</small>
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


<!-- ═══ Continuar o empezar ═══════════════════════════════════════ -->
<section class="seccion" style="padding-top:0">
    <div class="contenedor">

        <?php if ($enProgreso): ?>

            <div class="titulo-seccion izquierda">
                <small>Sigue donde lo dejaste</small>
                <h2>Tus actividades en curso</h2>
            </div>

            <div class="rejilla-actividades">
                <?php foreach ($enProgreso as $act) {
                    require RUTA_INCLUDES . '/tarjeta-actividad.php';
                } ?>
            </div>

        <?php else: ?>

            <div class="titulo-seccion izquierda">
                <small>Empieza por aquí</small>
                <h2>Todavía no has jugado nada</h2>
                <p>Estas son buenas puertas de entrada.</p>
            </div>

            <div class="rejilla-actividades">
                <?php foreach ($sugeridas as $act) {
                    require RUTA_INCLUDES . '/tarjeta-actividad.php';
                } ?>
            </div>

        <?php endif; ?>

    </div>
</section>


<?php if ($misGuias): ?>
<!-- ═══ Guías: cómo se hace cada cosa ═════════════════════════════ -->
<section class="seccion" style="padding-top:0">
    <div class="contenedor">

        <div class="titulo-seccion izquierda">
            <small>Cómo se hace</small>
            <h2>Guías de un minuto</h2>
            <p>
                Te lo enseñamos paso a paso, con el ratón moviéndose por la pantalla.
                Nada de leer manuales.
            </p>
        </div>

        <div class="guias-mazo">
            <?php foreach ($misGuias as $g): ?>
                <a class="guia-tarjeta"
                   href="<?= e(url('usuario/guia.php?g=' . urlencode($g['slug']))) ?>">
                    <span class="ico" aria-hidden="true"><?= e($g['icono']) ?></span>
                    <b><?= e($g['titulo']) ?></b>
                    <small><?= e($g['resumen']) ?></small>
                    <span class="duracion">▶ <?= e($g['duracion']) ?></span>
                </a>
            <?php endforeach; ?>
        </div>

    </div>
</section>
<?php endif; ?>


<!-- ═══ Entrar por bloques ════════════════════════════════════════ -->
<section class="seccion tenue">
    <div class="contenedor">

        <div class="titulo-seccion izquierda">
            <small>Explorar</small>
            <h2>Elige por dónde entrar</h2>
            <p>Doce materias, cada una organizada en bloques.</p>
        </div>

        <?php
        /*
         * Aquí iban los bloques uno por uno. Funcionaba con seis; hoy hay
         * treinta y seis y sería un muro dentro del panel personal, que es
         * justo lo que el panel viene a evitar.
         *
         * Se muestran las materias, y los bloques quedan donde tienen
         * sentido: en el directorio del catálogo, que existe para eso.
         */
        ?>
        <div class="materias">
            <?php foreach ($categorias as $c): ?>
                <?php if ((int) $c['total'] === 0) { continue; } ?>
                <a class="materia" style="--acento:<?= e($c['color'] ?: '#29b6f6') ?>"
                   href="<?= e(url('actividades/?categoria=' . urlencode($c['slug']))) ?>">
                    <span class="materia-ico" aria-hidden="true"><?= e($c['icon']) ?></span>
                    <span class="materia-nombre"><?= e($c['name']) ?></span>
                    <span class="materia-conteo"><?= (int) $c['total'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:26px">
            <a class="btn btn-secundario" href="<?= e(url('actividades/')) ?>">
                Ver el catálogo por bloques
            </a>
        </div>

    </div>
</section>


<!-- ═══ Novedades, favoritas y cuenta ═════════════════════════════ -->
<section class="seccion">
    <div class="contenedor">

        <div class="ficha">

            <div>
                <?php if ($nuevas): ?>
                    <div class="titulo-seccion izquierda">
                        <small>Recién publicadas</small>
                        <h2>Nuevas actividades</h2>
                    </div>
                    <div class="rejilla-actividades">
                        <?php foreach ($nuevas as $act) {
                            require RUTA_INCLUDES . '/tarjeta-actividad.php';
                        } ?>
                    </div>
                <?php endif; ?>

                <?php if ($favoritas): ?>
                    <div class="titulo-seccion izquierda" style="margin-top:34px">
                        <small>Tus favoritas</small>
                        <h2>Guardadas para después</h2>
                    </div>
                    <div class="rejilla-actividades">
                        <?php foreach ($favoritas as $act) {
                            require RUTA_INCLUDES . '/tarjeta-actividad.php';
                        } ?>
                    </div>
                <?php endif; ?>
            </div>

            <aside>

                <div class="bloque">
                    <h2>Tu avance</h2>
                    <div class="datos-ficha">
                        <div><span>Actividades empezadas</span><b><?= count($enProgreso) ?: '—' ?></b></div>
                        <div><span>Actividades completadas</span><b><?= $completadas ?></b></div>
                        <div><span>Favoritas</span><b><?= count($favoritas) ?></b></div>
                    </div>
                    <?php if ($completadas === 0 && !$enProgreso): ?>
                        <p style="margin-top:12px;font-size:.86rem;color:var(--texto-tenue)">
                            Tu progreso se guarda solo, en cuanto termines tu primera estación.
                        </p>
                    <?php endif; ?>
                </div>

                <div class="bloque">
                    <h2>Tu cuenta</h2>
                    <div class="datos-ficha">
                        <div><span>Nombre</span><b><?= e($usuario['name']) ?></b></div>
                        <div><span>Correo</span><b><?= e($usuario['email']) ?></b></div>
                        <div><span>Plan</span><b><?= e($acceso['plan']) ?></b></div>
                        <?php if ($acceso['vence']): ?>
                            <div><span>Vence</span><b><?= e(fechaLarga($acceso['vence'])) ?></b></div>
                        <?php endif; ?>
                        <div><span>Miembro desde</span><b><?= e(fechaLarga($usuario['created_at'])) ?></b></div>
                    </div>

                    <?php
                    /*
                     * «Mis pagos» solo aparece cuando hay algo que ver. A
                     * quien nunca ha pagado, un enlace a una lista vacía
                     * solo le añade una puerta más que abrir para nada.
                     */
                    $tienePagos = pagosInstalados()
                        && traerValor('SELECT 1 FROM payments WHERE user_id = ? LIMIT 1', [(int) $usuario['id']]);
                    ?>
                    <?php if ($tienePagos): ?>
                        <a class="btn btn-secundario btn-chico btn-bloque" style="margin-top:14px"
                           href="<?= e(url('usuario/pagos.php')) ?>">🧾 Mis pagos</a>
                    <?php endif; ?>

                    <?php
                    /*
                     * El modo niño no se le ofrece a un estudiante de un
                     * colegio: su cuenta ya la gobierna la ruta de su
                     * docente, y un PIN propio sería una forma de
                     * esconderse de ella.
                     */
                    if (function_exists('modoNinoInstalado') && modoNinoInstalado()
                        && !tieneRol('student')):
                    ?>
                        <a class="btn btn-secundario btn-chico btn-bloque" style="margin-top:9px"
                           href="<?= e(url('usuario/modo-nino.php')) ?>">
                            🧒 Modo niño<?= enModoNino() ? ' · encendido' : '' ?>
                        </a>
                    <?php endif; ?>

                    <?php if (esAdmin()): ?>
                        <a class="btn btn-secundario btn-chico btn-bloque" style="margin-top:9px"
                           href="<?= e(url('admin/')) ?>">Panel de administración</a>
                    <?php endif; ?>

                    <a class="btn btn-secundario btn-chico btn-bloque" style="margin-top:9px"
                       href="<?= e(url('logout.php')) ?>">Cerrar sesión</a>
                </div>

            </aside>

        </div>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
