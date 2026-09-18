<?php
/**
 * usuario/tienda.php — Personaje, accesorios y logros
 *
 * Aquí se gasta lo que se gana jugando. Todo se compra y se equipa con
 * formularios POST normales: los enlaces no cambian nada del estado, así
 * que ningún rastreador ni prefetch puede gastarle las monedas a nadie.
 *
 * Cada compra se vuelve a comprobar en el servidor —precio, saldo y
 * racha— aunque el botón ya estuviera desactivado. Un botón deshabilitado
 * no es una comprobación.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

// ── Acciones ─────────────────────────────────────────────────────────
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $slug   = post('articulo');

    if ($accion === 'comprar') {
        $r = comprarArticulo($slug);
        if ($r['ok']) {
            // Se pone de una vez: quien compra un sombrero lo quiere ver
            // puesto, no buscar un segundo botón para estrenarlo.
            equiparArticulo($slug);
            mensaje('ok', '¡' . ($r['item']['name'] ?? 'Listo') . ' es tuyo! Ya lo llevas puesto.');
        } else {
            mensaje('error', $r['error']);
        }

    } elseif ($accion === 'equipar') {
        $r = equiparArticulo($slug);
        mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Listo, ya lo llevas.' : $r['error']);

    } elseif ($accion === 'quitar') {
        equiparArticulo('');
        mensaje('ok', 'Accesorio quitado.');
    }

    /*
     * Redirigir después de escribir: así recargar la página no vuelve a
     * comprar lo mismo.
     *
     * El `?estreno` sirve para que el retrato celebre al llegar. Va en la
     * dirección y no en la sesión porque no es un dato: es un detalle de
     * presentación que dura un segundo, y si el niño recarga a propósito
     * para volver a verlo, que lo vea.
     */
    $celebrar = in_array($accion, ['comprar', 'equipar'], true);

    redirigir('usuario/tienda.php' . ($celebrar ? '?estreno=1' : ''));
}

$usuario   = usuarioActual();
$wallet    = billetera();
$r         = racha();
$semana    = semanaDeRacha();
$tiene     = articulosDe();
$logros    = resumenLogros();
$personaje = personajeDe($usuario);

$avatares   = articulosTienda('avatar');
$accesorios = articulosTienda('accesorio');

$titulo        = 'Mi personaje · Actividades en Línea';
$seccionActiva = 'espacio';

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('index.php')) ?>">Inicio</a>
            <span class="sep">›</span>
            <a href="<?= e(url('usuario/')) ?>">Mi espacio</a>
            <span class="sep">›</span>
            <span>Mi personaje</span>
        </nav>

        <!-- ── Retrato y marcador ──────────────────────────────────── -->
        <div class="panel-personaje">

            <div class="retrato <?= get('estreno') !== '' ? 'estrenando' : '' ?>">
                <?= personajeHtml($usuario, 'grande') ?>
                <p class="retrato-nombre"><?= e($personaje['nombre']) ?></p>
                <?php if ($personaje['accesorio'] !== null): ?>
                    <form method="post" action="<?= e(url('usuario/tienda.php')) ?>">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="quitar">
                        <button class="btn btn-secundario btn-chico" type="submit">Quitar accesorio</button>
                    </form>
                <?php endif; ?>
            </div>

            <div class="marcador-grande">
                <div class="dato">
                    <span class="ico" aria-hidden="true">🪙</span>
                    <b><?= (int) $wallet['saldo'] ?></b>
                    <span>monedas para gastar</span>
                    <?php if ($wallet['gastadas'] > 0): ?>
                        <small>Has ganado <?= (int) $wallet['ganadas'] ?> en total</small>
                    <?php endif; ?>
                </div>

                <div class="dato">
                    <span class="ico" aria-hidden="true">⭐</span>
                    <b><?= (int) $wallet['estrellas'] ?></b>
                    <span>estrellas</span>
                    <small><?= (int) $wallet['estaciones'] ?> estaciones terminadas</small>
                </div>

                <div class="dato">
                    <span class="ico" aria-hidden="true">🔥</span>
                    <b><?= (int) $r['actual'] ?></b>
                    <span><?= $r['actual'] === 1 ? 'día seguido' : 'días seguidos' ?></span>
                    <?php if ($r['mejor'] > $r['actual']): ?>
                        <small>Tu mejor racha: <?= (int) $r['mejor'] ?></small>
                    <?php endif; ?>
                </div>
            </div>

            <!-- La semana, para que la racha se vea y no solo se cuente. -->
            <div class="semana">
                <?php foreach ($semana as $d): ?>
                    <span class="dia <?= $d['jugado'] ? 'jugado' : '' ?> <?= $d['es_hoy'] ? 'hoy' : '' ?>"
                          title="<?= e($d['fecha']) ?>">
                        <small><?= e($d['letra']) ?></small>
                        <span aria-hidden="true"><?= $d['jugado'] ? '🔥' : '·' ?></span>
                    </span>
                <?php endforeach; ?>
                <p class="semana-pie">
                    <?php if ($r['hoy']): ?>
                        Ya jugaste hoy. La racha sigue viva.
                    <?php elseif ($r['actual'] > 0): ?>
                        Juega hoy una estación y tu racha llega a <?= (int) $r['actual'] + 1 ?>.
                    <?php else: ?>
                        Termina una estación hoy y empieza tu racha.
                    <?php endif; ?>
                </p>
            </div>
        </div>

        <?php
        /**
         * Pinta la rejilla de una sección de la tienda.
         * Se define aquí porque personajes y accesorios se muestran
         * exactamente igual: duplicar el bloque solo garantizaría que uno
         * de los dos se quede atrás al cambiar algo.
         */
        function rejillaTienda(array $items, array $tiene, array $wallet, array $r,
                               ?string $puesto, string $caraActual = ''): void
        {
            echo '<div class="rejilla-tienda">';

            foreach ($items as $it) {
                $suyo   = in_array($it['slug'], $tiene, true);
                $activo = $puesto === $it['slug'];
                $motivo = motivoNoComprar($it, $wallet, $r, $tiene);

                $clase = 'articulo' . ($activo ? ' puesto' : '') . ($suyo ? ' mio' : '');
                echo '<div class="' . $clase . '">';

                /*
                 * Un accesorio se enseña PUESTO sobre el personaje que el
                 * niño tiene ahora, no suelto.
                 *
                 * Un 🎩 flotando en una tarjeta no dice cómo va a quedar
                 * el sombrero en tu león, y eso es justo lo que se está
                 * decidiendo al gastar las monedas. Se usa el mismo HTML y
                 * las mismas clases que el retrato, así que lo que se ve
                 * aquí es exactamente lo que se lleva puesto después.
                 */
                if ($it['kind'] === 'accesorio' && $caraActual !== '') {
                    echo '<span class="articulo-cara" aria-hidden="true">'
                       . '<span class="personaje mediano">'
                       . '<span class="personaje-cara">' . e($caraActual) . '</span>'
                       . '<span class="personaje-accesorio acc-' . e($it['slug']) . '">'
                       . e($it['emoji']) . '</span>'
                       . '</span></span>';
                } else {
                    echo '<span class="articulo-cara" aria-hidden="true">' . e($it['emoji']) . '</span>';
                }

                echo '<b>' . e($it['name']) . '</b>';

                if (!empty($it['description'])) {
                    echo '<p>' . e($it['description']) . '</p>';
                }

                if ($activo) {
                    echo '<span class="sello-puesto">✓ Lo llevas puesto</span>';

                } elseif ($suyo) {
                    echo '<form method="post" action="' . e(url('usuario/tienda.php')) . '">'
                       . campoCsrf()
                       . '<input type="hidden" name="accion" value="equipar">'
                       . '<input type="hidden" name="articulo" value="' . e($it['slug']) . '">'
                       . '<button class="btn btn-principal btn-chico btn-bloque" type="submit">Ponérmelo</button>'
                       . '</form>';

                } elseif ($motivo === 'falta_racha') {
                    echo '<span class="precio bloqueado">🔒 Racha de '
                       . (int) $it['needs_streak'] . ' días</span>';

                } elseif ($motivo === 'faltan_monedas') {
                    $faltan = (int) $it['price_coins'] - $wallet['saldo'];
                    echo '<span class="precio bloqueado">🪙 ' . (int) $it['price_coins'] . '</span>'
                       . '<small class="faltan">Te faltan ' . $faltan . '</small>';

                } else {
                    echo '<form method="post" action="' . e(url('usuario/tienda.php')) . '">'
                       . campoCsrf()
                       . '<input type="hidden" name="accion" value="comprar">'
                       . '<input type="hidden" name="articulo" value="' . e($it['slug']) . '">'
                       . '<button class="btn btn-oro btn-chico btn-bloque" type="submit">'
                       . 'Canjear por 🪙 ' . (int) $it['price_coins']
                       . '</button></form>';
                }

                echo '</div>';
            }

            echo '</div>';
        }
        ?>

        <!-- ── Personajes ──────────────────────────────────────────── -->
        <div class="titulo-seccion izquierda" style="margin-top:36px">
            <small>Quién quieres ser</small>
            <h2>Personajes</h2>
            <p>Los cuatro primeros son tuyos desde el principio. Los demás se canjean
               con monedas, y dos solo se consiguen volviendo varios días seguidos.</p>
        </div>

        <?php rejillaTienda($avatares, $tiene, $wallet, $r, $usuario['avatar'] ?? null); ?>

        <!-- ── Accesorios ──────────────────────────────────────────── -->
        <div class="titulo-seccion izquierda" style="margin-top:36px">
            <small>Para llevar encima</small>
            <h2>Accesorios</h2>
            <p>Se ponen sobre tu personaje. Puedes llevar uno a la vez.</p>
        </div>

        <?php
        /*
         * Se le pasa la cara actual para que cada accesorio se vea PUESTO
         * sobre su personaje. Es la diferencia entre elegir un sombrero y
         * elegir un sombrero sabiendo cómo te queda.
         */
        rejillaTienda($accesorios, $tiene, $wallet, $r,
                      $usuario['accessory'] ?? null, $personaje['emoji']);
        ?>

        <!-- ── Logros ──────────────────────────────────────────────── -->
        <div class="titulo-seccion izquierda" style="margin-top:36px">
            <small><?= (int) $logros['hechos'] ?> de <?= (int) $logros['total'] ?></small>
            <h2>Logros</h2>
            <p>No se compran. Aparecen solos cuando cumples lo que piden.</p>
        </div>

        <div class="rejilla-logros">
            <?php foreach ($logros['lista'] as $l): ?>
                <div class="logro <?= $l['logrado'] ? 'hecho' : '' ?>">
                    <span class="logro-cara" aria-hidden="true"><?= e($l['emoji']) ?></span>
                    <div class="logro-texto">
                        <b><?= e($l['nombre']) ?></b>
                        <small><?= e($l['pista']) ?></small>

                        <?php if (!$l['logrado']): ?>
                            <div class="logro-barra">
                                <div style="width:<?= (int) round($l['hecho'] * 100 / max(1, $l['meta'])) ?>%"></div>
                            </div>
                            <small class="logro-cuenta"><?= (int) $l['hecho'] ?> de <?= (int) $l['meta'] ?></small>
                        <?php else: ?>
                            <span class="logro-sello">✓ Conseguido</span>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div style="margin-top:30px">
            <a class="btn btn-secundario" href="<?= e(url('actividades/')) ?>">
                Ir a jugar y ganar más monedas
            </a>
        </div>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
