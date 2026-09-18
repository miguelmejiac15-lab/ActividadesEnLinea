<?php
/**
 * admin/index.php — Resumen del panel
 *
 * Estado del ecosistema de un vistazo, con accesos directos a lo que
 * más se usa.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$cifras = [
    ['n' => (int) traerValor('SELECT COUNT(*) FROM activities WHERE status = "published"'),
     't' => 'Publicadas',            'c' => ''],
    ['n' => (int) traerValor('SELECT COUNT(*) FROM activities WHERE status = "draft"'),
     't' => 'Borradores',            'c' => 'naranja'],
    ['n' => (int) traerValor('SELECT COUNT(*) FROM activity_stations'),
     't' => 'Estaciones',            'c' => 'morado'],
    ['n' => (int) traerValor('SELECT COUNT(*) FROM users'),
     't' => 'Usuarios',              'c' => 'verde'],
    ['n' => (int) traerValor('SELECT COUNT(*) FROM subscriptions WHERE status = "active" AND (expires_at IS NULL OR expires_at > NOW())'),
     't' => 'Suscripciones activas', 'c' => 'verde'],
    ['n' => (int) traerValor('SELECT COUNT(*) FROM schools'),
     't' => 'Escuelas',              'c' => 'morado'],
];

$porAcceso = traerTodo(
    'SELECT access_type, COUNT(*) AS total FROM activities WHERE status = "published" GROUP BY access_type'
);
$reparto = [];
foreach ($porAcceso as $f) {
    $reparto[$f['access_type']] = (int) $f['total'];
}
$totalPub = array_sum($reparto);

$porCategoria = traerTodo(
    'SELECT c.name, c.icon, c.color, COUNT(a.id) AS total
       FROM categories c
  LEFT JOIN activities a ON a.category_id = c.id AND a.status = "published"
   GROUP BY c.id ORDER BY total DESC, c.sort_order'
);

$recientes = traerTodo(
    'SELECT a.id, a.title, a.slug, a.icon, a.status, a.access_type, a.updated_at,
            c.name AS categoria
       FROM activities a
  LEFT JOIN categories c ON c.id = a.category_id
   ORDER BY a.updated_at DESC LIMIT 8'
);

// Actividades que todavía no tienen estaciones cargadas: son las que
// faltan por portar al motor.
$sinEstaciones = (int) traerValor(
    'SELECT COUNT(*) FROM activities a
      WHERE NOT EXISTS (SELECT 1 FROM activity_stations s WHERE s.activity_id = a.id)'
);

$titulo    = 'Resumen';
$panelZona = 'inicio';

require __DIR__ . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Resumen</h1>
        <p>Estado del ecosistema en tiempo real.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini solido" href="<?= e(url('admin/actividades/editar.php')) ?>">+ Nueva actividad</a>
        <a class="btn-mini" href="<?= e(url('admin/metricas/')) ?>">📈 Métricas del negocio</a>
        <a class="btn-mini" href="<?= e(url('index.php')) ?>">Ver el sitio</a>
    </div>
</div>

<?php
/*
 * El aviso de cobros pendientes va arriba del todo, antes que cualquier
 * cifra de contenido. Un pago sin confirmar es dinero que ya entró al
 * banco y acceso que el cliente todavía no tiene: no puede esperar a
 * que alguien decida entrar a la sección de cobros.
 */
if ($pagosEsperando > 0): ?>
    <div class="aviso info">
        <b><?= $pagosEsperando ?> <?= $pagosEsperando === 1 ? 'pago espera' : 'pagos esperan' ?> confirmación.</b>
        Hasta que se confirmen, esos clientes pagaron y siguen sin acceso.
        <a href="<?= e(url('admin/pagos/?estado=pending')) ?>" style="font-weight:600">Revisarlos →</a>
    </div>
<?php endif; ?>

<div class="cifras">
    <?php foreach ($cifras as $c): ?>
        <div class="cifra-caja <?= e($c['c']) ?>">
            <div class="n"><?= (int) $c['n'] ?></div>
            <div class="t"><?= e($c['t']) ?></div>
        </div>
    <?php endforeach; ?>
</div>

<?php if ($sinEstaciones > 0): ?>
    <div class="aviso info">
        <b><?= $sinEstaciones ?> actividades</b> todavía no tienen estaciones cargadas.
        Hasta que las tengan, el modelo por estaciones no puede aplicarse sobre ellas.
        Puedes cargarlas desde la ficha de cada actividad.
    </div>
<?php endif; ?>

<div class="caja">
    <div class="cabeza">
        <h2>Reparto del catálogo por acceso</h2>
        <span class="distintivo azul">Modo global: <?= e(modoCatalogo()) ?></span>
    </div>
    <div class="cuerpo">
        <?php
        $tipos = [
            ACCESO_LIBRE   => ['🎁 Libres por completo',                     'verde'],
            ACCESO_PARCIAL => ['🎯 Parciales · primeras estaciones gratis',  'naranja'],
            ACCESO_PREMIUM => ['🔒 Solo Biblioteca Completa',                'morado'],
        ];
        foreach ($tipos as $clave => [$etiqueta, $color]):
            $n = $reparto[$clave] ?? 0;
            $pct = $totalPub > 0 ? round($n * 100 / $totalPub) : 0;
            ?>
            <div style="margin-bottom:13px">
                <div style="display:flex;justify-content:space-between;font-size:.88rem;margin-bottom:5px">
                    <span><?= e($etiqueta) ?></span>
                    <b style="color:var(--oscuro)"><?= $n ?> · <?= $pct ?>%</b>
                </div>
                <div style="height:9px;background:var(--borde-suave);border-radius:50px;overflow:hidden">
                    <div style="height:100%;width:<?= $pct ?>%;border-radius:50px;background:var(--<?= $color === 'naranja' ? 'naranja' : ($color === 'morado' ? 'morado' : 'verde') ?>)"></div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
</div>

<div class="caja">
    <div class="cabeza">
        <h2>Actividades por categoría</h2>
        <a class="btn-mini" href="<?= e(url('admin/categorias/')) ?>">Administrar</a>
    </div>
    <div class="tabla-envoltorio">
        <table class="tabla">
            <thead>
                <tr><th>Categoría</th><th>Publicadas</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($porCategoria as $c): ?>
                    <tr>
                        <td><b><?= e($c['icon']) ?> <?= e($c['name']) ?></b></td>
                        <td class="compacta"><?= (int) $c['total'] ?></td>
                        <td style="width:55%">
                            <div style="height:7px;background:var(--borde-suave);border-radius:50px;overflow:hidden">
                                <div style="height:100%;border-radius:50px;background:<?= e($c['color'] ?: '#29b6f6') ?>;width:<?= $totalPub > 0 ? round((int) $c['total'] * 100 / $totalPub) : 0 ?>%"></div>
                            </div>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<div class="caja">
    <div class="cabeza">
        <h2>Modificadas recientemente</h2>
        <a class="btn-mini" href="<?= e(url('admin/actividades/')) ?>">Ver todas</a>
    </div>
    <div class="tabla-envoltorio">
        <table class="tabla">
            <thead>
                <tr><th>Actividad</th><th>Categoría</th><th>Estado</th><th>Acceso</th><th></th></tr>
            </thead>
            <tbody>
                <?php foreach ($recientes as $a): ?>
                    <tr>
                        <td><b><?= e($a['icon']) ?> <?= e($a['title']) ?></b></td>
                        <td><?= e($a['categoria'] ?? '—') ?></td>
                        <td>
                            <span class="distintivo <?= $a['status'] === 'published' ? 'verde' : 'gris' ?>">
                                <?= $a['status'] === 'published' ? 'Publicada' : ucfirst($a['status']) ?>
                            </span>
                        </td>
                        <td>
                            <span class="distintivo <?= $a['access_type'] === 'free' ? 'verde'
                                : ($a['access_type'] === 'partial' ? 'naranja' : 'morado') ?>">
                                <?= e($a['access_type']) ?>
                            </span>
                        </td>
                        <td class="compacta" style="text-align:right">
                            <a class="btn-mini" href="<?= e(url('admin/actividades/editar.php?id=' . (int) $a['id'])) ?>">Editar</a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
</div>

<?php require __DIR__ . '/includes/pie-admin.php'; ?>
