<?php
/**
 * admin/metricas/index.php — Las cifras del negocio
 *
 * Esta pantalla responde cinco preguntas, en este orden, porque es el
 * orden en que se hacen:
 *
 *   1. ¿Cuánta gente hay y cuánta paga?
 *   2. ¿Cuánto entró este mes y cómo va frente al anterior?
 *   3. ¿La cosa sube o baja? (los doce meses)
 *   4. ¿De dónde sale el dinero? (plan y forma de pago)
 *   5. ¿Qué tengo que hacer hoy? (pendientes y renovaciones)
 *
 * Las gráficas no ocupan el primer lugar. Arriba van las cifras sueltas
 * porque cuando la historia es un número, la gráfica correcta es el
 * número: dibujar una barra sola para decir «hay 12 premium» ocupa
 * veinte veces más espacio y se lee peor.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$instalado = pagosInstalados();

// Rango de la serie temporal. Un solo control arriba que afecta a las
// dos gráficas: filtros dentro de cada tarjeta harían que dos gráficas
// vecinas hablaran de periodos distintos sin avisar.
$meses = (int) get('meses', '12');
if (!in_array($meses, [6, 12, 24], true)) {
    $meses = 12;
}

$r = resumenNegocio();

$serieIngresos  = $instalado ? ingresosPorMes($meses) : [];
$serieRegistros = registrosPorMes($meses);
$repartoPlanes  = usuariosPorPlan();
$porPlan        = $instalado ? ingresosPorPlan()   : [];
$porMetodo      = $instalado ? ingresosPorMetodo() : [];
$porEstado      = $instalado ? pagosPorEstado()    : [];
$renovaciones   = renovacionesProximas(30);
$registros      = ultimosRegistros(8);
$roles          = usuariosPorRol();

$titulo    = 'Métricas';
$panelZona = 'metricas';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Métricas</h1>
        <p>Todo se calcula en vivo sobre pagos, suscripciones y cuentas. No hay contadores guardados.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini <?= $meses === 6  ? 'solido' : '' ?>" href="?meses=6">6 meses</a>
        <a class="btn-mini <?= $meses === 12 ? 'solido' : '' ?>" href="?meses=12">12 meses</a>
        <a class="btn-mini <?= $meses === 24 ? 'solido' : '' ?>" href="?meses=24">24 meses</a>
    </div>
</div>

<?php if (!$instalado): ?>
    <div class="aviso info">
        <b>Falta la migración de la Fase 4.</b>
        Las cifras de usuarios ya son reales, pero las de dinero estarán vacías hasta
        que exista la tabla de pagos. Ejecuta:
        <code>php database/migracion-pagos.php --aplicar</code>
    </div>
<?php endif; ?>

<?php
/*
 * ── 1. LAS CIFRAS ────────────────────────────────────────────────────
 *
 * La variación frente al mes anterior va como pie de la cifra y no como
 * gráfica aparte: es el contexto que hace legible el número, no un dato
 * independiente. Sin ella, «$840.000 este mes» no dice si es bueno.
 */
$variacion = '';
if ($r['variacion_mes'] !== null) {
    $signo = $r['variacion_mes'] >= 0 ? 'sube' : 'baja';
    $flecha = $r['variacion_mes'] >= 0 ? '▲' : '▼';
    $variacion = '<span class="' . $signo . '">' . $flecha . ' '
               . abs($r['variacion_mes']) . '%</span> frente al mes pasado';
} elseif ($r['ingreso_mes_ant'] === 0 && $r['ingreso_mes'] > 0) {
    $variacion = 'primer mes con ingresos';
}
?>

<div class="cifras">
    <?= cifraDestacada(
        'Cuentas activas',
        number_format($r['usuarios_activos'], 0, ',', '.'),
        '+' . $r['nuevos_30'] . ' en los últimos 30 días'
    ) ?>

    <?= cifraDestacada(
        'Usuarios premium',
        number_format($r['premium'], 0, ',', '.'),
        'y ' . number_format($r['gratuitos'], 0, ',', '.') . ' en el plan gratuito',
        'verde'
    ) ?>

    <?= cifraDestacada(
        'Conversión a pago',
        str_replace('.', ',', (string) $r['conversion']) . '%',
        'premium ÷ cuentas activas',
        'morado'
    ) ?>

    <div class="cifra-caja verde">
        <div class="n moneda"><?= e(precioCop($r['ingreso_mes'])) ?></div>
        <div class="t">Ingresos de este mes</div>
        <?php if ($variacion !== ''): ?><div class="p"><?= $variacion ?></div><?php endif; ?>
    </div>

    <div class="cifra-caja">
        <div class="n moneda"><?= e(precioCop($r['mrr'])) ?></div>
        <div class="t">Ingreso mensual recurrente</div>
        <div class="p">lo anual repartido entre doce</div>
    </div>

    <div class="cifra-caja <?= $r['pendientes'] > 0 ? 'naranja' : '' ?>">
        <div class="n"><?= (int) $r['pendientes'] ?></div>
        <div class="t">Pagos por confirmar</div>
        <div class="p">
            <?php if ($r['pendientes'] > 0): ?>
                <?= e(precioCop($r['pendientes_monto'])) ?> ·
                <a href="<?= e(url('admin/pagos/?estado=pending')) ?>" style="color:var(--azul);font-weight:600">revisar</a>
            <?php else: ?>
                nada esperando
            <?php endif; ?>
        </div>
    </div>
</div>

<?php
/*
 * ── 2. LAS DOS SERIES ────────────────────────────────────────────────
 *
 * Ingresos y registros van en DOS gráficas y nunca en una con dos ejes.
 * Superponer pesos y personas obliga a inventar una escala común, y esa
 * escala arbitraria dibuja una correlación que no está en los datos.
 */
?>
<div class="rejilla-panel dos">

    <div class="caja">
        <div class="cabeza">
            <h2>Ingresos confirmados por mes</h2>
            <span class="distintivo verde"><?= e(precioCop($r['ingreso_total'])) ?> histórico</span>
        </div>
        <div class="cuerpo">
            <?php if ($instalado): ?>
                <?= graficaBarras($serieIngresos, [
                        'formato' => 'moneda',
                        'titulo'  => 'Ingresos por mes',
                        'id'      => 'ingresos',
                    ]) ?>
            <?php else: ?>
                <p class="gr-vacia">Disponible en cuanto exista la tabla de pagos.</p>
            <?php endif; ?>
        </div>
    </div>

    <div class="caja">
        <div class="cabeza">
            <h2>Cuentas nuevas por mes</h2>
            <span class="distintivo azul"><?= (int) $r['nuevos_7'] ?> esta semana</span>
        </div>
        <div class="cuerpo">
            <?= graficaBarras($serieRegistros, [
                    'formato' => 'numero',
                    'titulo'  => 'Registros por mes',
                    'id'      => 'registros',
                ]) ?>
        </div>
    </div>

</div>

<?php
/*
 * ── 3. DE DÓNDE SALE ─────────────────────────────────────────────────
 *
 * El reparto de planes incluye a los gratuitos. Dibujar solo a quien
 * paga daría una gráfica preciosa con un 100% de conversión y ninguna
 * relación con la realidad.
 */
$colores = [
    PLAN_FREE       => COLOR_NEUTRO,
    PLAN_BIBLIOTECA => colorSerie(0),
    PLAN_ESCUELA    => colorSerie(2),
    PLAN_DOCENTE    => colorSerie(1),
];

$partes = [];
foreach ($repartoPlanes as $p) {
    $partes[] = [
        'etiqueta' => $p['name'],
        'valor'    => (int) $p['total'],
        // El color va atado al plan, no a su puesto en la lista: si un
        // mes Escuela adelanta a Biblioteca, cada uno conserva el suyo.
        'color'    => $colores[$p['slug']] ?? COLOR_NEUTRO,
    ];
}

$maxPlan = $porPlan ? max(array_map(static fn ($f) => (int) $f['monto'], $porPlan)) : 0;
?>

<div class="rejilla-panel dos">

    <div class="caja">
        <div class="cabeza">
            <h2>Usuarios por plan</h2>
        </div>
        <div class="cuerpo">
            <?= graficaReparto($partes) ?>
        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Ingresos por plan</h2></div>
        <?php if ($porPlan): ?>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr><th>Plan</th><th class="cifra">Pagos</th><th class="cifra">Recaudado</th><th></th></tr>
                    </thead>
                    <tbody>
                        <?php foreach ($porPlan as $i => $f): ?>
                            <tr>
                                <td><b><?= e($f['name']) ?></b></td>
                                <td class="cifra"><?= (int) $f['pagos'] ?></td>
                                <td class="cifra"><b><?= e(precioCop((int) $f['monto'])) ?></b></td>
                                <td style="width:38%">
                                    <?= barraFila((int) $f['monto'], $maxPlan, $colores[$f['slug']] ?? colorSerie($i)) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="cuerpo"><p class="gr-vacia">Ningún pago confirmado todavía.</p></div>
        <?php endif; ?>
    </div>

</div>

<div class="rejilla-panel dos">

    <div class="caja">
        <div class="cabeza"><h2>Estado de los cobros</h2>
            <a class="btn-mini" href="<?= e(url('admin/pagos/')) ?>">Ver cobros</a>
        </div>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead><tr><th>Estado</th><th class="cifra">Pagos</th><th class="cifra">Monto</th></tr></thead>
                <tbody>
                    <?php foreach ($porEstado as $estado => $d): ?>
                        <?php $et = etiquetaEstadoPago($estado); ?>
                        <tr>
                            <td>
                                <span class="distintivo <?= e($et['color']) ?>">
                                    <?= e($et['icono']) ?> <?= e($et['etiqueta']) ?>
                                </span>
                            </td>
                            <td class="cifra"><?= (int) $d['pagos'] ?></td>
                            <td class="cifra"><?= e(precioCop((int) $d['monto'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    <?php if (!$porEstado): ?>
                        <tr><td colspan="3" style="color:var(--texto-tenue)">Sin datos todavía.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Cuentas por tipo</h2>
            <a class="btn-mini" href="<?= e(url('admin/usuarios/')) ?>">Administrar</a>
        </div>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead><tr><th>Tipo de cuenta</th><th class="cifra">Cuentas</th><th></th></tr></thead>
                <tbody>
                    <?php $maxRol = max(1, max(array_map(static fn ($f) => (int) $f['total'], $roles))); ?>
                    <?php foreach ($roles as $f): ?>
                        <tr>
                            <td><b><?= e($f['name']) ?></b></td>
                            <td class="cifra"><?= (int) $f['total'] ?></td>
                            <td style="width:45%"><?= barraFila((int) $f['total'], $maxRol) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</div>

<?php if ($porMetodo): ?>
    <div class="caja">
        <div class="cabeza"><h2>Cómo paga la gente</h2></div>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead><tr><th>Forma de pago</th><th class="cifra">Pagos</th><th class="cifra">Recaudado</th></tr></thead>
                <tbody>
                    <?php foreach ($porMetodo as $f): ?>
                        <tr>
                            <td><?= e(iconoMetodo($f['method'])) ?> <b><?= e(etiquetaMetodo($f['method'])) ?></b></td>
                            <td class="cifra"><?= (int) $f['pagos'] ?></td>
                            <td class="cifra"><?= e(precioCop((int) $f['monto'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php endif; ?>

<?php
/*
 * ── 4. QUÉ HACER HOY ─────────────────────────────────────────────────
 *
 * Las renovaciones son la lista más rentable del panel: renovar a quien
 * ya pagó cuesta mucho menos que conseguir a alguien nuevo, y la
 * oportunidad se acaba el día del vencimiento.
 */
?>
<div class="rejilla-panel dos">

    <div class="caja">
        <div class="cabeza">
            <h2>⏰ Vencen en 30 días</h2>
            <span class="distintivo <?= $renovaciones ? 'naranja' : 'gris' ?>">
                <?= count($renovaciones) ?>
            </span>
        </div>
        <?php if ($renovaciones): ?>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead><tr><th>Usuario</th><th>Plan</th><th class="cifra">Faltan</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($renovaciones as $s): ?>
                            <tr>
                                <td>
                                    <b><?= e($s['name']) ?></b><br>
                                    <small style="color:var(--texto-tenue)"><?= e($s['email']) ?></small>
                                </td>
                                <td class="compacta"><?= e($s['plan']) ?></td>
                                <td class="cifra">
                                    <span class="distintivo <?= (int) $s['dias'] <= 7 ? 'rojo' : 'naranja' ?>">
                                        <?= (int) $s['dias'] ?> d
                                    </span>
                                </td>
                                <td class="compacta" style="text-align:right">
                                    <a class="btn-mini" href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $s['user_id'])) ?>">Ficha</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="cuerpo"><p class="gr-vacia">Ninguna suscripción vence en los próximos 30 días.</p></div>
        <?php endif; ?>
    </div>

    <div class="caja">
        <div class="cabeza">
            <h2>Últimos registros</h2>
            <a class="btn-mini" href="<?= e(url('admin/usuarios/')) ?>">Ver todos</a>
        </div>
        <?php if ($registros): ?>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead><tr><th>Cuenta</th><th>Plan</th><th class="cifra">Alta</th><th></th></tr></thead>
                    <tbody>
                        <?php foreach ($registros as $u): ?>
                            <tr>
                                <td>
                                    <b><?= e($u['name']) ?></b><br>
                                    <small style="color:var(--texto-tenue)"><?= e($u['email']) ?></small>
                                </td>
                                <td class="compacta">
                                    <?php if ($u['plan']): ?>
                                        <span class="distintivo verde"><?= e($u['plan']) ?></span>
                                    <?php else: ?>
                                        <span class="distintivo gris">Gratis</span>
                                    <?php endif; ?>
                                </td>
                                <td class="cifra" style="color:var(--texto-tenue);font-size:.83rem">
                                    <?= e(date('d/m/Y', strtotime($u['created_at']))) ?>
                                </td>
                                <td class="compacta" style="text-align:right">
                                    <a class="btn-mini" href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $u['id'])) ?>">Ficha</a>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="cuerpo"><p class="gr-vacia">Todavía no hay cuentas registradas.</p></div>
        <?php endif; ?>
    </div>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
