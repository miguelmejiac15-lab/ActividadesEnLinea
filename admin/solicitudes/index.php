<?php
/**
 * admin/solicitudes — Las solicitudes de información del formulario público
 *
 * Aquí está TODA solicitud, se haya podido avisar por correo o no. Si el
 * aviso falló, la fila lo dice: es la señal de que hay que revisar Panel →
 * Correo antes de que se pierda el siguiente interesado.
 */

declare(strict_types=1);

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';
require_once RUTA_INCLUDES . '/solicitudes.php';

exigirRol('admin');

$instalada = (bool) traerValor(
    'SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "info_requests"'
);

if ($instalada && $_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();
    $id     = (int) ($_POST['id'] ?? 0);
    $estado = (string) post('estado');
    if ($id > 0 && in_array($estado, ['nueva', 'contactada', 'cerrada'], true)) {
        ejecutar('UPDATE info_requests SET status = ? WHERE id = ?', [$estado, $id]);
        mensaje('ok', 'Solicitud #' . $id . ' marcada como ' . $estado . '.');
    }
    redirigir('admin/solicitudes/' . (get('ver') !== '' ? '?ver=' . urlencode(get('ver')) : ''));
}

$filtro = in_array(get('ver'), ['nueva', 'contactada', 'cerrada'], true) ? get('ver') : '';
$lista  = $instalada
    ? traerTodo('SELECT * FROM info_requests' . ($filtro ? ' WHERE status = ?' : '') . ' ORDER BY created_at DESC LIMIT 300',
                $filtro ? [$filtro] : [])
    : [];
$conteo = $instalada
    ? array_column(traerTodo('SELECT status, COUNT(*) n FROM info_requests GROUP BY status'), 'n', 'status')
    : [];

$nombres = static function (?string $csv, array $lista): string {
    if (!$csv) return '';
    return implode(', ', array_map(static fn($k) => $lista[$k] ?? $k, explode(',', $csv)));
};

$titulo    = 'Solicitudes';
$panelZona = 'solicitudes';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Solicitudes de información</h1>
        <p>
            <?= (int) ($conteo['nueva'] ?? 0) ?> nuevas ·
            <?= (int) ($conteo['contactada'] ?? 0) ?> contactadas ·
            <?= (int) ($conteo['cerrada'] ?? 0) ?> cerradas.
            Los avisos llegan a <b><?= e(solicitudesCorreo()) ?></b>.
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini <?= $filtro === '' ? 'solido' : '' ?>" href="<?= e(url('admin/solicitudes/')) ?>">Todas</a>
        <a class="btn-mini <?= $filtro === 'nueva' ? 'solido' : '' ?>" href="<?= e(url('admin/solicitudes/?ver=nueva')) ?>">Nuevas</a>
        <a class="btn-mini <?= $filtro === 'contactada' ? 'solido' : '' ?>" href="<?= e(url('admin/solicitudes/?ver=contactada')) ?>">Contactadas</a>
        <a class="btn-mini" href="<?= e(url('solicitar-informacion.php')) ?>" target="_blank" rel="noopener">Ver el formulario ↗</a>
    </div>
</div>

<?php if (!$instalada): ?>

    <div class="aviso mal">
        <b>Falta crear la tabla de solicitudes.</b>
        Ejecuta <code>php database/migracion-solicitudes.php --aplicar</code>.
    </div>

<?php else: ?>

    <?php if (!correoConfigurado()): ?>
        <div class="aviso mal" style="margin-bottom:14px">
            <b>El correo no está configurado:</b> las solicitudes se guardan aquí, pero no llega
            ningún aviso a <?= e(solicitudesCorreo()) ?>. Configúralo en
            <a href="<?= e(url('admin/correo.php')) ?>">Panel → Correo</a>.
        </div>
    <?php endif; ?>

    <?php if (!$lista): ?>
        <p>Todavía no hay solicitudes<?= $filtro ? ' en este estado' : '' ?>.</p>
    <?php else: ?>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead>
                    <tr><th>Fecha</th><th>Quién</th><th>Contacto</th><th>Qué necesita</th><th>Estado</th></tr>
                </thead>
                <tbody>
                <?php foreach ($lista as $s): ?>
                    <tr>
                        <td class="compacta">
                            <?= e(date('d/m/Y H:i', strtotime($s['created_at']))) ?><br>
                            <small style="color:var(--texto-tenue)">#<?= (int) $s['id'] ?>
                                <?= $s['source'] ? '· ' . e(SOLICITUD_ORIGEN[$s['source']] ?? $s['source']) : '' ?></small>
                            <?php if (!(int) $s['email_sent']): ?>
                                <br><small style="color:#c62828" title="<?= e((string) $s['email_error']) ?>">✉ aviso no enviado</small>
                            <?php endif; ?>
                        </td>
                        <td>
                            <b><?= e($s['name']) ?></b><br>
                            <small><?= e(ucfirst($s['profile'])) ?><?= $s['institution'] ? ' · ' . e($s['institution']) : '' ?>
                                <?= $s['job_title'] ? ' (' . e($s['job_title']) . ')' : '' ?></small><br>
                            <small style="color:var(--texto-tenue)"><?= e((string) $s['city']) ?></small>
                        </td>
                        <td>
                            <a href="mailto:<?= e($s['email']) ?>"><?= e($s['email']) ?></a><br>
                            <?php $tel = preg_replace('/\D/', '', (string) $s['phone']); ?>
                            <?php if ($tel !== ''): ?>
                                <a href="https://wa.me/<?= e(strlen($tel) === 10 ? '57' . $tel : $tel) ?>" target="_blank" rel="noopener">
                                    WhatsApp <?= e((string) $s['phone']) ?></a>
                            <?php endif; ?>
                            <?php if ($s['contact_pref']): ?>
                                <br><small>Prefiere: <?= e(SOLICITUD_CONTACTO[$s['contact_pref']] ?? $s['contact_pref']) ?></small>
                            <?php endif; ?>
                        </td>
                        <td style="max-width:360px">
                            <?php if ($s['students'] || $s['teachers']): ?>
                                <small><b><?= e((string) $s['students']) ?></b> estudiantes · <b><?= e((string) $s['teachers']) ?></b> docentes</small><br>
                            <?php endif; ?>
                            <small><?= e($nombres($s['needs'], SOLICITUD_NECESIDADES[$s['profile']] ?? [])) ?></small>
                            <?php if ($s['grades']): ?><br><small style="color:var(--texto-tenue)">Niveles: <?= e($nombres($s['grades'], SOLICITUD_NIVELES)) ?></small><?php endif; ?>
                            <?php if ($s['subjects']): ?><br><small style="color:var(--texto-tenue)">Áreas: <?= e($nombres($s['subjects'], SOLICITUD_AREAS)) ?></small><?php endif; ?>
                            <?php if ($s['message']): ?><br><small>«<?= e($s['message']) ?>»</small><?php endif; ?>
                        </td>
                        <td>
                            <form method="post" style="display:flex;gap:6px;align-items:center">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="id" value="<?= (int) $s['id'] ?>">
                                <select name="estado" onchange="this.form.submit()" aria-label="Estado"
                                        style="padding:6px 8px;border:2px solid var(--borde);border-radius:8px;font-family:inherit">
                                    <?php foreach (['nueva' => 'Nueva', 'contactada' => 'Contactada', 'cerrada' => 'Cerrada'] as $k => $txt): ?>
                                        <option value="<?= $k ?>" <?= $s['status'] === $k ? 'selected' : '' ?>><?= $txt ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </form>
                        </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>

<?php endif; ?>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
