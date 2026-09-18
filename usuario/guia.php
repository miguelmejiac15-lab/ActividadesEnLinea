<?php
/**
 * usuario/guia.php — Reproductor de una guía animada.
 *
 * Una dirección por guía (`?g=modo-nino`), para poder enlazarla desde
 * cualquier sitio y para que quien la comparte comparta la guía y no «la
 * página de ayuda».
 *
 * ---------------------------------------------------------------------
 *  SE COMPRUEBA QUIÉN PUEDE VERLA
 * ---------------------------------------------------------------------
 *
 * No por secreto —una guía no enseña datos de nadie— sino por utilidad:
 * enseñarle a un suscriptor cómo crear profesores le hace buscar un botón
 * que no está en su pantalla, y concluir que la plataforma está rota.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

$slug = trim((string) get('g'));
$guia = $slug !== '' ? guiaPorSlug($slug) : null;

if (!$guia || !puedoVerGuia($slug)) {
    mensaje('info', 'Esa guía no está disponible para tu cuenta.');
    redirigir('usuario/');
}

$escenas = __DIR__ . '/escenas/' . basename((string) $guia['escenas']) . '.php';

if (!is_file($escenas)) {
    mensaje('error', 'Esa guía no se pudo cargar.');
    redirigir('usuario/');
}

$otras = array_diff_key(guiasParaMi(), [$slug => true]);

$titulo        = $guia['titulo'];
$seccionActiva = 'cuenta';
$hojasExtra    = ['assets/css/guia.css'];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:940px">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('usuario/')) ?>">Mi espacio</a>
            <span class="sep">›</span>
            <span>Guías</span>
        </nav>

        <div style="display:grid;grid-template-columns:minmax(0,1fr) 230px;gap:22px;align-items:start">

            <!-- ── El reproductor ───────────────────────────────── -->
            <div class="guia" data-guia tabindex="0"
                 role="region" aria-label="Guía: <?= e($guia['titulo']) ?>">

                <div class="guia-cabeza">
                    <span class="ico" aria-hidden="true"><?= e($guia['icono']) ?></span>
                    <div>
                        <h2><?= e($guia['titulo']) ?></h2>
                        <p><?= e($guia['resumen']) ?></p>
                    </div>
                </div>

                <div class="guia-escena">
                    <?php require $escenas; ?>

                    <?php
                    /*
                     * El cursor va dibujado y no es un emoji ni una
                     * imagen: un emoji lo pinta el sistema de quien mira
                     * —en cada equipo sale otra flecha— y una imagen se
                     * ve borrosa en pantalla de retina.
                     */
                    ?>
                    <span class="guia-cursor" aria-hidden="true">
                        <svg viewBox="0 0 24 24">
                            <path d="M5 2.5 19 12l-6.2 1.2L9.8 19.5Z"
                                  fill="#fff" stroke="#2f3b52" stroke-width="1.6"
                                  stroke-linejoin="round"/>
                        </svg>
                    </span>
                </div>

                <div class="guia-pie">
                    <p class="guia-pie-texto" role="status" aria-live="polite"></p>

                    <div class="guia-avance" role="presentation">
                        <div class="guia-avance-relleno"></div>
                    </div>

                    <div class="guia-mandos">
                        <button type="button" class="btn btn-principal btn-chico guia-play"
                                aria-label="Reproducir la guía">▶ Reproducir</button>

                        <button type="button" class="btn btn-secundario btn-chico guia-atras"
                                aria-label="Paso anterior">‹ Atrás</button>

                        <button type="button" class="btn btn-secundario btn-chico guia-siguiente"
                                aria-label="Paso siguiente">Siguiente ›</button>

                        <span class="guia-contador"></span>
                    </div>
                </div>
            </div>

            <!-- ── Los pasos, para saltar a uno ─────────────────── -->
            <aside>
                <h3 style="font-size:.8rem;text-transform:uppercase;letter-spacing:.07em;
                           color:var(--texto-tenue);margin-bottom:9px">Pasos</h3>

                <ol class="guia-lista">
                    <?php foreach ($guia['pasos'] as $i => $paso): ?>
                        <li<?= $i === 0 ? ' class="ahora"' : '' ?>>
                            <button type="button">
                                <span class="n"><?= $i + 1 ?></span>
                                <span><?= e(mb_strimwidth($paso['texto'], 0, 62, '…')) ?></span>
                            </button>
                        </li>
                    <?php endforeach; ?>
                </ol>

                <?php if ($otras): ?>
                    <h3 style="font-size:.8rem;text-transform:uppercase;letter-spacing:.07em;
                               color:var(--texto-tenue);margin:22px 0 9px">Otras guías</h3>

                    <?php foreach ($otras as $o): ?>
                        <a class="guia-tarjeta" style="margin-bottom:8px"
                           href="<?= e(url('usuario/guia.php?g=' . urlencode($o['slug']))) ?>">
                            <span class="ico"><?= e($o['icono']) ?></span>
                            <b><?= e($o['titulo']) ?></b>
                            <span class="duracion"><?= e($o['duracion']) ?></span>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </aside>

        </div>

        <p style="margin-top:20px;color:var(--texto-tenue);font-size:.86rem">
            Esto es una demostración: lo que se ve aquí no se puede tocar.
            <a href="<?= e(url('usuario/')) ?>" style="color:var(--azul);font-weight:600">
                Vuelve a tu espacio
            </a>
            para hacerlo de verdad.
        </p>

    </div>
</section>

<script>
    window.GUIA = <?= json_encode(
        ['pasos' => $guia['pasos']],
        JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    ) ?>;
</script>
<script src="<?= e(url('assets/js/guia.js')) ?>" defer></script>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
