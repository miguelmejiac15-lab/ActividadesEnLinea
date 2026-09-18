<?php
/**
 * planes/index.php — Página de precios
 *
 * Explica primero el valor y después el precio. La Biblioteca Completa
 * se destaca visualmente: la intención comercial es favorecer la
 * suscripción anual, no presentar todas las opciones como equivalentes.
 */

require_once dirname(__DIR__) . '/config/config.php';

$planes   = planesActivos();
$total    = totalActividades();
$usuario  = usuarioActual();
$completo = tieneCatalogoCompleto();
$actual   = planActual();

$titulo      = 'Planes y precios · Actividades en Línea';
$descripcion = 'Empieza gratis o desbloquea toda la biblioteca. Si eres una institución, lleva las actividades a otro nivel.';
$seccionActiva = 'planes';

require RUTA_INCLUDES . '/cabecera.php';
?>

<!-- ═══ Encabezado ════════════════════════════════════════════════ -->
<section class="hero" style="padding:52px 0 44px">
    <div class="contenedor">
        <div class="hero-inner">
            <span class="etiqueta-hero">💡 No compras actividades. Obtienes acceso.</span>
            <h1>Elige cómo quieres <span class="resalte">aprender</span></h1>
            <p class="entrada">
                Empieza gratis o desbloquea toda la biblioteca.
                Si eres una institución, lleva las actividades a otro nivel.
            </p>
        </div>
    </div>
</section>


<!-- ═══ El valor, antes del precio ════════════════════════════════ -->
<section class="seccion">
    <div class="contenedor">

        <div class="titulo-seccion">
            <small>Qué obtienes</small>
            <h2>Una biblioteca que sigue creciendo</h2>
        </div>

        <div class="acciones-grid" style="grid-template-columns:repeat(auto-fit,minmax(230px,1fr))">
            <div class="accion" style="text-align:left;padding:26px 22px">
                <span class="ico" aria-hidden="true">📚</span>
                <b><?= (int) $total ?> actividades</b>
                <p style="font-size:.88rem;color:var(--texto-tenue);margin-top:6px">
                    Listas para usar, clasificadas por categoría y edad.
                </p>
            </div>
            <div class="accion" style="text-align:left;padding:26px 22px">
                <span class="ico" aria-hidden="true">✨</span>
                <b>Novedades incluidas</b>
                <p style="font-size:.88rem;color:var(--texto-tenue);margin-top:6px">
                    Lo que publiquemos durante tu suscripción entra sin costo adicional.
                </p>
            </div>
            <div class="accion" style="text-align:left;padding:26px 22px">
                <span class="ico" aria-hidden="true">🎁</span>
                <b>Prueba real, no demo</b>
                <p style="font-size:.88rem;color:var(--texto-tenue);margin-top:6px">
                    Con el plan gratis entras a todas y juegas la primera parte de cada una.
                </p>
            </div>
            <div class="accion" style="text-align:left;padding:26px 22px">
                <span class="ico" aria-hidden="true">🔓</span>
                <b>Sin permanencia</b>
                <p style="font-size:.88rem;color:var(--texto-tenue);margin-top:6px">
                    Cancela cuando quieras. No guardamos datos de tarjetas.
                </p>
            </div>
        </div>

    </div>
</section>


<!-- ═══ Las tarjetas de plan ══════════════════════════════════════ -->
<section class="seccion tenue">
    <div class="contenedor">

        <?php if ($usuario && $completo): ?>
            <div class="aviso ok" style="max-width:620px;margin:0 auto 32px">
                Ya tienes acceso completo con el plan <b><?= e($actual) ?></b>.
                <?php if (($v = vencimientoSuscripcion())): ?>
                    Vigente hasta el <b><?= e(fechaLarga($v)) ?></b>.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <div class="rejilla-planes">
            <?php foreach ($planes as $p): ?>
                <?php
                $esGratis   = $p['slug'] === PLAN_FREE;
                $esEscuela  = $p['slug'] === PLAN_ESCUELA;
                $esElActual = $usuario && $actual === $p['slug'];

                if ($esElActual) {
                    $enlace = url('usuario/');
                    $texto  = 'Tu plan actual';
                } elseif ($esGratis) {
                    $enlace = $usuario ? url('actividades/') : url('registro.php');
                    $texto  = $usuario ? 'Explorar actividades' : 'Comenzar gratis';
                } elseif ($esEscuela) {
                    $enlace = '#escuela';
                    $texto  = 'Solicitar información';
                } else {
                    $enlace = url('planes/suscribir.php?plan=' . urlencode($p['slug']));
                    $texto  = 'Obtener acceso';
                }
                ?>
                <div class="plan <?= (int) $p['is_recommended'] === 1 ? 'recomendado' : '' ?>"
                     <?= $esEscuela ? 'id="escuela"' : '' ?>>

                    <?php if ((int) $p['is_recommended'] === 1): ?>
                        <span class="cinta">★ EL MÁS ELEGIDO</span>
                    <?php endif; ?>

                    <div class="nivel"><?= e($p['tagline'] ?? '') ?></div>
                    <h3><?= e($p['name']) ?></h3>
                    <p class="lema"><?= e($p['description'] ?? '') ?></p>

                    <div class="precio">
                        <?php if ($esGratis): ?>
                            <span class="cifra">$0</span>
                            <span class="periodo">para siempre</span>
                        <?php elseif ($p['price_yearly_cop'] !== null): ?>
                            <span class="cifra"><?= e(precioCop((int) $p['price_yearly_cop'])) ?></span>
                            <span class="periodo">/ año</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!$esGratis && $p['price_monthly_cop'] !== null): ?>
                        <p class="precio-alterno">
                            o <?= e(precioCop((int) $p['price_monthly_cop'])) ?> / mes
                        </p>
                    <?php endif; ?>

                    <?php if (!empty($p['ahorro'])): ?>
                        <span class="ahorro">
                            El plan anual ahorra <?= e(precioCop($p['ahorro']['monto'])) ?>
                            (<?= (int) $p['ahorro']['porcentaje'] ?>%)
                        </span>
                    <?php endif; ?>

                    <ul class="beneficios">
                        <?php foreach ($p['features'] as $f): ?>
                            <li><?= e($f) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <a class="btn <?= $esElActual
                            ? 'btn-secundario'
                            : ((int) $p['is_recommended'] === 1 ? 'btn-oro' : 'btn-secundario') ?> btn-bloque"
                       href="<?= e($enlace) ?>"><?= e($texto) ?></a>

                </div>
            <?php endforeach; ?>
        </div>

    </div>
</section>


<!-- ═══ Comparación de modalidades ════════════════════════════════ -->
<?php
$biblioteca = null;
foreach ($planes as $p) {
    if ($p['slug'] === PLAN_BIBLIOTECA) { $biblioteca = $p; break; }
}
?>
<?php if ($biblioteca && $biblioteca['price_monthly_cop'] && $biblioteca['price_yearly_cop']): ?>
<section class="seccion">
    <div class="contenedor">

        <div class="titulo-seccion">
            <small>Mensual o anual</small>
            <h2>El plan anual sale mejor</h2>
        </div>

        <div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(250px,1fr));gap:20px;max-width:720px;margin:0 auto">

            <div class="bloque" style="text-align:center;margin:0">
                <div class="nivel" style="color:var(--texto-tenue)">MENSUAL</div>
                <div class="precio">
                    <span class="cifra" style="font-size:2.1rem">
                        <?= e(precioCop((int) $biblioteca['price_monthly_cop'])) ?>
                    </span>
                    <span class="periodo">/ mes</span>
                </div>
                <p style="font-size:.87rem;color:var(--texto-tenue);margin-top:8px">
                    <?= e(precioCop((int) $biblioteca['price_monthly_cop'] * 12)) ?> si pagas doce meses seguidos
                </p>
            </div>

            <div class="bloque" style="text-align:center;margin:0;border:3px solid var(--azul)">
                <div class="nivel">ANUAL · RECOMENDADO</div>
                <div class="precio">
                    <span class="cifra" style="font-size:2.1rem">
                        <?= e(precioCop((int) $biblioteca['price_yearly_cop'])) ?>
                    </span>
                    <span class="periodo">/ año</span>
                </div>
                <?php if (!empty($biblioteca['ahorro'])): ?>
                    <span class="ahorro" style="margin-top:8px">
                        Ahorras <?= e(precioCop($biblioteca['ahorro']['monto'])) ?>
                        (<?= (int) $biblioteca['ahorro']['porcentaje'] ?>%)
                    </span>
                <?php endif; ?>
            </div>

        </div>

    </div>
</section>
<?php endif; ?>


<!-- ═══ Mensaje clave ═════════════════════════════════════════════ -->
<section class="seccion tenue">
    <div class="contenedor">
        <div class="mensaje-clave">
            <h2>No compras actividades. Obtienes acceso a una biblioteca que crece.</h2>
            <p>
                Paga una vez y disfruta todo el año. Todas las nuevas actividades que
                publiquemos durante tu suscripción quedan incluidas.
            </p>
            <?php if (!$completo): ?>
                <p style="margin-top:20px">
                    <a class="btn btn-oro" href="<?= e(url('planes/suscribir.php?plan=biblioteca')) ?>">
                        Obtener la Biblioteca Completa
                    </a>
                </p>
            <?php endif; ?>
        </div>
    </div>
</section>


<!-- ═══ Preguntas ═════════════════════════════════════════════════ -->
<section class="seccion">
    <div class="contenedor" style="max-width:780px">

        <div class="titulo-seccion">
            <small>Preguntas frecuentes</small>
            <h2>Antes de decidir</h2>
        </div>

        <div class="bloque">
            <h2>¿Qué incluye exactamente el plan gratuito?</h2>
            <p>
                Puedes entrar a las <?= (int) $total ?> actividades y jugar la primera parte de
                cada una. No es una demostración recortada: es el producto real, con un límite
                de avance. Así compruebas la calidad antes de pagar.
            </p>
        </div>

        <div class="bloque">
            <h2>Si publican actividades nuevas, ¿las tengo que pagar aparte?</h2>
            <p>
                No. Todo lo que publiquemos mientras tu suscripción esté vigente queda incluido.
                Ese es el sentido del modelo: no vendemos actividades sueltas, damos acceso a una
                biblioteca que crece.
            </p>
        </div>

        <div class="bloque">
            <h2>¿Qué pasa cuando vence mi suscripción?</h2>
            <p>
                Tu cuenta vuelve al acceso gratuito. No se borra nada: tu progreso y tus
                favoritos siguen ahí, y recuperas el acceso completo al renovar.
            </p>
        </div>

        <div class="bloque">
            <h2>¿En qué se diferencia el plan Escuela?</h2>
            <p>
                La Biblioteca Completa es para consumir contenido. El plan Escuela es para
                <b>gestionar el aprendizaje</b>: crear cursos y grupos, inscribir estudiantes,
                asignar actividades en secuencia y hacer seguimiento del progreso por estudiante,
                grupo y curso.
            </p>
        </div>

        <div class="bloque">
            <h2>¿Guardan los datos de mi tarjeta?</h2>
            <p>
                No. El cobro lo procesa la pasarela de pagos y nosotros solo guardamos la
                referencia de la transacción, lo mínimo para saber si tu suscripción está
                vigente. La plataforma trata datos de menores y está diseñada con esa
                responsabilidad desde el principio.
            </p>
        </div>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
