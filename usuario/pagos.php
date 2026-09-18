<?php
/**
 * usuario/pagos.php — Mis pagos
 *
 * El historial del propio usuario. Existe por una razón muy concreta:
 * quien paga por transferencia y espera una confirmación necesita poder
 * comprobar por su cuenta que su pago sigue vivo, sin escribir a nadie.
 *
 * Solo se muestran los pagos de quien mira. La consulta filtra por su
 * id de sesión, no por un parámetro de la URL: si la referencia
 * decidiera qué se enseña, cambiarla en la barra de direcciones
 * mostraría los datos de facturación de otra familia.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

// Un niño que entró tocando su nombre en la lista de clase no ve datos
// de facturación de la familia. Ver `exigirSesionPlena()`.
exigirSesionPlena();

$usuario = usuarioActual();
$pagos   = pagosInstalados() ? pagosDeUsuario((int) $usuario['id'], 50) : [];

$suscripcion = suscripcionVigente();

$titulo        = 'Mis pagos';
$seccionActiva = 'cuenta';
$hojasExtra    = ['assets/css/pago.css'];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:720px">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('usuario/')) ?>">Mi espacio</a>
            <span class="sep">›</span>
            <span>Mis pagos</span>
        </nav>

        <h1 style="color:var(--oscuro);font-size:1.7rem;margin-bottom:6px">Mis pagos</h1>
        <p style="color:var(--texto-tenue);margin-bottom:24px">
            Todo lo que has contratado, con el estado de cada uno.
        </p>

        <?php if ($suscripcion): ?>
            <div class="aviso ok" style="margin-bottom:22px">
                Tu plan <b><?= e($suscripcion['plan_name']) ?></b> está vigente
                <?php if ($suscripcion['expires_at']): ?>
                    hasta el <b><?= e(fechaLarga($suscripcion['expires_at'])) ?></b>.
                <?php else: ?>
                    sin fecha de vencimiento.
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <?php if ($pagos): ?>

            <div class="lista-pagos">
                <?php foreach ($pagos as $p): ?>
                    <?php $et = etiquetaEstadoPago($p['status']); ?>
                    <div class="fila-pago">
                        <span class="icono" aria-hidden="true"><?= e(iconoMetodo($p['method'])) ?></span>

                        <span class="info">
                            <b><?= e($p['plan']) ?> · <?= e(etiquetaCiclo((string) $p['billing_cycle'])) ?></b>
                            <small><?= e($p['reference']) ?></small>
                        </span>

                        <span class="monto"><?= e(precioCop((int) $p['amount_cop'])) ?></span>

                        <span class="sello <?= e(mb_strtolower($et['etiqueta'])) ?>">
                            <?= e($et['icono']) ?> <?= e($et['etiqueta']) ?>
                        </span>

                        <span style="color:var(--texto-tenue);font-size:.83rem;white-space:nowrap">
                            <?= e(date('d/m/Y', strtotime($p['created_at']))) ?>
                        </span>

                        <?php if ($p['status'] === PAGO_PENDIENTE): ?>
                            <a class="btn btn-principal btn-chico"
                               href="<?= e(url('planes/pagar.php?ref=' . urlencode($p['reference']))) ?>">
                                Continuar
                            </a>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <p style="color:var(--texto-tenue);font-size:.85rem;margin-top:20px;line-height:1.6">
                ¿Algo no cuadra? Escríbenos con la referencia del pago
                <?php if (($c = ajuste('pagos_correo_soporte', '')) !== ''): ?>
                    a <b><?= e($c) ?></b>
                <?php endif; ?>
                y lo revisamos.
            </p>

        <?php else: ?>

            <div class="bloque espera">
                <span class="icono" aria-hidden="true">🧾</span>
                <h2>Todavía no has hecho ningún pago</h2>
                <p>
                    Estás en el plan gratuito: entras a todas las actividades y juegas las
                    primeras estaciones de cada una.
                </p>
                <div style="margin-top:20px">
                    <a class="btn btn-oro" href="<?= e(url('planes/')) ?>">🔓 Ver los planes</a>
                </div>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
