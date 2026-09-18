<?php
/**
 * admin/usuarios/ver.php — Ficha de una cuenta
 *
 * Todo lo que hay que saber de una persona en una pantalla: sus datos,
 * su plan, lo que ha pagado y lo que ha jugado.
 *
 * Existe porque el listado no puede con esto. Una tabla de doscientas
 * filas sirve para encontrar a alguien; para atenderlo hace falta ver
 * su historia entera, y meterla en una fila la volvería ilegible para
 * las otras ciento noventa y nueve.
 *
 * El orden de los bloques sigue al de las preguntas reales de soporte:
 * primero «¿tiene acceso?» —que es el 90% de las llamadas—, luego
 * «¿pagó?» y solo al final «¿quién es?».
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$id = getEntero('id');
$u  = $id > 0 ? traerUno('SELECT * FROM users WHERE id = ?', [$id]) : null;

if (!$u) {
    mensaje('error', 'Esa cuenta no existe.');
    redirigir('admin/usuarios/');
}

$errores = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $yo     = usuarioActualId();

    switch ($accion) {

        case 'guardar':
            $r = actualizarUsuario($id, $_POST);
            if ($r['ok']) {
                mensaje('ok', 'Datos actualizados.');
                redirigir('admin/usuarios/ver.php?id=' . $id);
            }
            $errores = $r['errores'];
            mensaje('error', 'Revisa los campos señalados.');
            break;

        case 'clave':
            $nueva = (string) ($_POST['password'] ?? '');
            $r = restablecerContrasena($id, $nueva);
            if ($r['ok']) {
                /*
                 * Se enseña una vez, en la siguiente carga, y no se guarda
                 * en ningún sitio. Es incómodo a propósito: una contraseña
                 * que se puede volver a consultar no es una contraseña.
                 */
                $_SESSION['clave_nueva'] = $nueva;
                mensaje('ok', 'Contraseña cambiada.');
                redirigir('admin/usuarios/ver.php?id=' . $id);
            }
            mensaje('error', $r['error']);
            break;

        case 'suscribir':
            $r = activarSuscripcion($id, post('plan'), post('ciclo'));
            mensaje($r['ok'] ? 'ok' : 'error',
                $r['ok'] ? 'Suscripción activada a mano. Queda registrada como «manual», sin pago asociado.' : $r['error']);
            redirigir('admin/usuarios/ver.php?id=' . $id);
            break;

        case 'cancelar_sub':
            cancelarSuscripcion((int) ($_POST['sub'] ?? 0));
            mensaje('ok', 'Suscripción cancelada. La cuenta vuelve al acceso gratuito.');
            redirigir('admin/usuarios/ver.php?id=' . $id);
            break;

        case 'vigencia':
            $r = ajustarVigencia((int) ($_POST['sub'] ?? 0), (int) ($_POST['dias'] ?? 0));
            mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Vigencia ajustada.' : $r['error']);
            redirigir('admin/usuarios/ver.php?id=' . $id);
            break;
    }
}

// ── Datos de la ficha ────────────────────────────────────────────────
$suscripcion = traerUno(
    'SELECT s.*, p.name AS plan, p.slug AS plan_slug, p.catalog_access
       FROM subscriptions s
       JOIN plans p ON p.id = s.plan_id
      WHERE s.user_id = ? AND s.status = "active"
        AND (s.expires_at IS NULL OR s.expires_at > NOW())
   ORDER BY s.expires_at DESC LIMIT 1',
    [$id]
);

$historialSub = traerTodo(
    'SELECT s.*, p.name AS plan FROM subscriptions s
       JOIN plans p ON p.id = s.plan_id
      WHERE s.user_id = ? ORDER BY s.created_at DESC LIMIT 20',
    [$id]
);

$pagos = pagosInstalados() ? pagosDeUsuario($id, 20) : [];

$pagado = pagosInstalados()
    ? (int) traerValor('SELECT COALESCE(SUM(amount_cop), 0) FROM payments WHERE user_id = ? AND status = "confirmed"', [$id])
    : 0;

// Uso de la plataforma. Es el dato que convierte una llamada de baja en
// una conversación: quien lleva 40 estaciones hechas no se va igual que
// quien no ha entrado nunca.
$w        = billetera($id);
$r        = racha($id);
$logros   = resumenLogros($id);
$ultimas  = traerTodo(
    'SELECT a.title, a.icon, a.slug, MAX(pr.completed_at) AS cuando,
            COUNT(*) AS estaciones
       FROM activity_progress pr
       JOIN activities a ON a.id = pr.activity_id
      WHERE pr.user_id = ? AND pr.status = "completed"
   GROUP BY a.id
   ORDER BY cuando DESC LIMIT 6',
    [$id]
);

$planes = traerTodo('SELECT slug, name FROM plans WHERE slug <> "free" AND is_active = 1 ORDER BY sort_order');

$claveNueva = $_SESSION['clave_nueva'] ?? null;
unset($_SESSION['clave_nueva']);

$rolesEt = ['user' => 'Familia', 'teacher' => 'Docente', 'school_admin' => 'Admin. escolar',
            'student' => 'Estudiante', 'admin' => 'Administrador'];

$esYo = $id === usuarioActualId();

$titulo    = $u['name'];
$panelZona = 'usuarios';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<nav class="migas" aria-label="Ruta" style="margin-bottom:14px;font-size:.87rem;color:var(--texto-tenue)">
    <a href="<?= e(url('admin/usuarios/')) ?>" style="color:var(--azul)">Usuarios</a>
    <span>›</span>
    <span><?= e($u['name']) ?></span>
</nav>

<div class="ficha-cabeza">
    <div class="identidad">
        <h1>
            <?= personajeHtml($u) ?>
            <?= e($u['name']) ?>
            <?php if ($esYo): ?><span class="distintivo azul">tú</span><?php endif; ?>
        </h1>
        <p style="color:var(--texto-tenue);font-size:.92rem;margin-top:5px">
            <?= e($u['email']) ?> ·
            <span class="distintivo gris"><?= e($rolesEt[$u['role']] ?? $u['role']) ?></span>
            <span class="distintivo <?= $u['status'] === 'active' ? 'verde' : 'rojo' ?>">
                <?= $u['status'] === 'active' ? 'Activa' : ($u['status'] === 'inactive' ? 'Desactivada' : 'Pendiente') ?>
            </span>
            <?php if ($suscripcion): ?>
                <span class="distintivo morado"><?= e($suscripcion['plan']) ?></span>
            <?php else: ?>
                <span class="distintivo gris">Plan gratuito</span>
            <?php endif; ?>
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini solido" href="<?= e(url('admin/usuarios/uso.php?id=' . $id)) ?>">
            📊 Informe de uso
        </a>
        <?php if (pagosInstalados()): ?>
            <a class="btn-mini" href="<?= e(url('admin/pagos/?buscar=' . urlencode($u['email']))) ?>">🧾 Sus cobros</a>
        <?php endif; ?>
        <a class="btn-mini" href="<?= e(url('admin/usuarios/')) ?>">← Volver</a>
    </div>
</div>

<?php if ($claveNueva): ?>
    <div class="caja" style="border-left:5px solid var(--verde)">
        <div class="cuerpo">
            <h2 style="font-size:1rem;margin-bottom:6px">🔑 Contraseña nueva</h2>
            <p style="color:var(--texto-tenue);font-size:.88rem;margin-bottom:12px">
                No se puede volver a ver: en la base solo queda cifrada. Entrégala ahora.
            </p>
            <div class="dato-copiable"><span><?= e($claveNueva) ?></span></div>
        </div>
    </div>
<?php endif; ?>

<div class="cifras">
    <div class="cifra-caja <?= $suscripcion ? 'verde' : '' ?>">
        <div class="n" style="font-size:1.2rem">
            <?= $suscripcion ? e($suscripcion['plan']) : 'Gratis' ?>
        </div>
        <div class="t">Plan actual</div>
        <div class="p">
            <?php if ($suscripcion && $suscripcion['expires_at']): ?>
                hasta el <?= e(date('d/m/Y', strtotime($suscripcion['expires_at']))) ?>
                (<?= max(0, (int) ceil((strtotime($suscripcion['expires_at']) - time()) / 86400)) ?> días)
            <?php elseif ($suscripcion): ?>
                sin fecha de vencimiento
            <?php else: ?>
                primeras estaciones de cada actividad
            <?php endif; ?>
        </div>
    </div>

    <div class="cifra-caja">
        <div class="n moneda"><?= e(precioCop($pagado)) ?></div>
        <div class="t">Ha pagado en total</div>
        <div class="p"><?= count($pagos) ?> <?= count($pagos) === 1 ? 'movimiento' : 'movimientos' ?></div>
    </div>

    <div class="cifra-caja morado">
        <div class="n"><?= (int) $w['estaciones'] ?></div>
        <div class="t">Estaciones completadas</div>
        <div class="p">en <?= (int) $w['actividades'] ?> actividades</div>
    </div>

    <div class="cifra-caja naranja">
        <div class="n"><?= (int) $r['actual'] ?></div>
        <div class="t">Días de racha</div>
        <div class="p">mejor racha: <?= (int) $r['mejor'] ?> · <?= (int) $logros['hechos'] ?>/<?= (int) $logros['total'] ?> logros</div>
    </div>
</div>

<div class="rejilla-panel ancha-izq">

    <div>

        <!-- ── Acceso ─────────────────────────────────────────────── -->
        <div class="caja">
            <div class="cabeza"><h2>Acceso y suscripción</h2></div>
            <div class="cuerpo">

                <?php if ($suscripcion): ?>

                    <div class="datos-lista" style="margin-bottom:18px">
                        <div>
                            <div class="rotulo">Plan</div>
                            <div class="dato"><?= e($suscripcion['plan']) ?></div>
                        </div>
                        <div>
                            <div class="rotulo">Modalidad</div>
                            <div class="dato"><?= e(etiquetaCiclo((string) $suscripcion['billing_cycle'])) ?></div>
                        </div>
                        <div>
                            <div class="rotulo">Vence</div>
                            <div class="dato">
                                <?= $suscripcion['expires_at']
                                    ? e(fechaLarga($suscripcion['expires_at']))
                                    : 'sin vencimiento' ?>
                            </div>
                        </div>
                        <div>
                            <div class="rotulo">Origen</div>
                            <div class="dato suave"><?= e($suscripcion['payment_provider'] ?: '—') ?></div>
                        </div>
                    </div>

                    <div class="separador"></div>

                    <form method="post" class="acciones-caja" style="margin-top:16px">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="vigencia">
                        <input type="hidden" name="sub" value="<?= (int) $suscripcion['id'] ?>">
                        <div class="campo">
                            <label for="v-dias">Ajustar la vigencia</label>
                            <input id="v-dias" name="dias" type="number" step="1" min="-3650" max="3650"
                                   placeholder="p. ej. 15 · o -7 para restar">
                            <p class="ayuda">
                                Días que se suman o se restan sin cobrar nada. Para una disculpa
                                por una caída, o para corregir una activación de más.
                            </p>
                        </div>
                        <button class="btn-mini solido" type="submit">Aplicar</button>
                    </form>

                    <form method="post" style="margin-top:16px"
                          onsubmit="return confirm('¿Cancelar la suscripción de <?= e(addslashes($u['name'])) ?>?\n\nVolverá al acceso gratuito de inmediato.')">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="cancelar_sub">
                        <input type="hidden" name="sub" value="<?= (int) $suscripcion['id'] ?>">
                        <button class="btn-mini peligro" type="submit">Cancelar la suscripción</button>
                    </form>

                <?php else: ?>

                    <p class="aviso-suave" style="margin-bottom:16px">
                        Esta cuenta está en el <b>plan gratuito</b>: entra a todas las actividades
                        y juega las primeras estaciones de cada una.
                    </p>

                    <form method="post" class="acciones-caja">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="suscribir">
                        <div class="campo">
                            <label for="s-plan">Activar un plan a mano</label>
                            <select id="s-plan" name="plan">
                                <?php foreach ($planes as $p): ?>
                                    <option value="<?= e($p['slug']) ?>"><?= e($p['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="campo" style="max-width:150px">
                            <label for="s-ciclo">Modalidad</label>
                            <select id="s-ciclo" name="ciclo">
                                <option value="yearly">Anual</option>
                                <option value="monthly">Mensual</option>
                            </select>
                        </div>
                        <button class="btn-mini solido" type="submit">Activar</button>
                    </form>

                    <p class="ayuda" style="margin-top:11px">
                        Esto concede el acceso <b>sin registrar un cobro</b>, así que no aparecerá en
                        los ingresos. Si el dinero sí entró, regístralo desde
                        <a href="<?= e(url('admin/pagos/')) ?>" style="color:var(--azul);font-weight:600">Cobros</a>
                        para que cuadre la contabilidad.
                    </p>

                <?php endif; ?>
            </div>
        </div>

        <!-- ── Cobros ─────────────────────────────────────────────── -->
        <?php if (pagosInstalados()): ?>
            <div class="caja">
                <div class="cabeza"><h2>Cobros de esta cuenta</h2></div>
                <?php if ($pagos): ?>
                    <div class="tabla-envoltorio">
                        <table class="tabla">
                            <thead>
                                <tr><th>Referencia</th><th>Plan</th><th class="cifra">Monto</th>
                                    <th>Estado</th><th class="cifra">Fecha</th></tr>
                            </thead>
                            <tbody>
                                <?php foreach ($pagos as $pg): ?>
                                    <?php $et = etiquetaEstadoPago($pg['status']); ?>
                                    <tr>
                                        <td class="compacta">
                                            <a href="<?= e(url('admin/pagos/ver.php?id=' . (int) $pg['id'])) ?>"
                                               style="font-family:ui-monospace,Consolas,monospace;font-size:.82rem;color:var(--azul)">
                                                <?= e($pg['reference']) ?>
                                            </a>
                                        </td>
                                        <td class="compacta"><?= e($pg['plan']) ?></td>
                                        <td class="cifra"><?= e(precioCop((int) $pg['amount_cop'])) ?></td>
                                        <td class="compacta">
                                            <span class="distintivo <?= e($et['color']) ?>">
                                                <?= e($et['etiqueta']) ?>
                                            </span>
                                        </td>
                                        <td class="cifra" style="color:var(--texto-tenue);font-size:.82rem">
                                            <?= e(date('d/m/Y', strtotime($pg['created_at']))) ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="cuerpo"><p class="gr-vacia">Esta cuenta nunca ha pagado nada.</p></div>
                <?php endif; ?>
            </div>
        <?php endif; ?>

        <!-- ── Datos ──────────────────────────────────────────────── -->
        <div class="caja">
            <div class="cabeza"><h2>Datos de la cuenta</h2></div>
            <div class="cuerpo">
                <form method="post" class="formulario">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="guardar">

                    <div class="pareja">
                        <div class="campo <?= isset($errores['name']) ? 'error' : '' ?>">
                            <label for="u-name">Nombre</label>
                            <input id="u-name" name="name" type="text" required maxlength="120"
                                   value="<?= e(post('name') ?: $u['name']) ?>">
                            <?php if (isset($errores['name'])): ?>
                                <p class="error-texto"><?= e($errores['name']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="campo <?= isset($errores['email']) ? 'error' : '' ?>">
                            <label for="u-email">Correo</label>
                            <input id="u-email" name="email" type="email" required maxlength="190"
                                   value="<?= e(post('email') ?: $u['email']) ?>">
                            <?php if (isset($errores['email'])): ?>
                                <p class="error-texto"><?= e($errores['email']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pareja">
                        <div class="campo <?= isset($errores['role']) ? 'error' : '' ?>">
                            <label for="u-role">Tipo de cuenta</label>
                            <select id="u-role" name="role">
                                <?php foreach ($rolesEt as $k => $et): ?>
                                    <option value="<?= e($k) ?>" <?= $u['role'] === $k ? 'selected' : '' ?>>
                                        <?= e($et) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                            <?php if (isset($errores['role'])): ?>
                                <p class="error-texto"><?= e($errores['role']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="campo <?= isset($errores['status']) ? 'error' : '' ?>">
                            <label for="u-status">Estado</label>
                            <select id="u-status" name="status" <?= $esYo ? 'disabled' : '' ?>>
                                <option value="active"   <?= $u['status'] === 'active'   ? 'selected' : '' ?>>Activa</option>
                                <option value="pending"  <?= $u['status'] === 'pending'  ? 'selected' : '' ?>>Pendiente</option>
                                <option value="inactive" <?= $u['status'] === 'inactive' ? 'selected' : '' ?>>Desactivada</option>
                            </select>
                            <?php if ($esYo): ?>
                                <p class="ayuda">No puedes desactivar tu propia cuenta.</p>
                            <?php endif; ?>
                            <?php if (isset($errores['status'])): ?>
                                <p class="error-texto"><?= e($errores['status']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pareja">
                        <div class="campo <?= isset($errores['birth_year']) ? 'error' : '' ?>">
                            <label for="u-birth">Año de nacimiento</label>
                            <input id="u-birth" name="birth_year" type="number" inputmode="numeric"
                                   min="<?= (int) date('Y') - 100 ?>" max="<?= (int) date('Y') ?>"
                                   value="<?= e((string) ($u['birth_year'] ?? '')) ?>">
                            <p class="ayuda">Solo el año: la fecha completa no se guarda nunca.</p>
                            <?php if (isset($errores['birth_year'])): ?>
                                <p class="error-texto"><?= e($errores['birth_year']) ?></p>
                            <?php endif; ?>
                        </div>

                        <div class="campo <?= isset($errores['guardian_email']) ? 'error' : '' ?>">
                            <label for="u-guardian">Correo del adulto responsable</label>
                            <input id="u-guardian" name="guardian_email" type="email" maxlength="190"
                                   value="<?= e($u['guardian_email'] ?? '') ?>">
                            <p class="ayuda">Obligatorio para menores de 14 años (Decreto 0769 de 2026).</p>
                            <?php if (isset($errores['guardian_email'])): ?>
                                <p class="error-texto"><?= e($errores['guardian_email']) ?></p>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="pie-formulario">
                        <button class="btn-mini solido" type="submit">Guardar datos</button>
                    </div>
                </form>
            </div>
        </div>

    </div>

    <!-- ── Columna derecha ────────────────────────────────────────── -->
    <div>

        <div class="caja">
            <div class="cabeza"><h2>Actividad reciente</h2></div>
            <div class="cuerpo">
                <?php if ($ultimas): ?>
                    <ul style="list-style:none;display:grid;gap:12px">
                        <?php foreach ($ultimas as $a): ?>
                            <li style="display:flex;gap:11px;align-items:center">
                                <span style="font-size:1.7rem" aria-hidden="true"><?= e($a['icon']) ?></span>
                                <span style="min-width:0">
                                    <b style="display:block;color:var(--oscuro);font-size:.9rem"><?= e($a['title']) ?></b>
                                    <small style="color:var(--texto-tenue)">
                                        <?= (int) $a['estaciones'] ?> estaciones ·
                                        <?= e(date('d/m/Y', strtotime($a['cuando']))) ?>
                                    </small>
                                </span>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p class="gr-vacia">
                        Esta cuenta todavía no ha completado ninguna estación.
                    </p>
                <?php endif; ?>

                <div class="separador"></div>

                <div class="datos-lista" style="margin-top:14px">
                    <div>
                        <div class="rotulo">Monedas</div>
                        <div class="dato">🪙 <?= (int) $w['saldo'] ?></div>
                    </div>
                    <div>
                        <div class="rotulo">Estrellas</div>
                        <div class="dato">⭐ <?= (int) $w['estrellas'] ?></div>
                    </div>
                    <div>
                        <div class="rotulo">Alta</div>
                        <div class="dato suave"><?= e(date('d/m/Y', strtotime($u['created_at']))) ?></div>
                    </div>
                    <div>
                        <div class="rotulo">Última entrada</div>
                        <div class="dato suave">
                            <?= $u['last_login_at'] ? e(date('d/m/Y', strtotime($u['last_login_at']))) : 'nunca' ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="caja">
            <div class="cabeza"><h2>Contraseña</h2></div>
            <div class="cuerpo">
                <p class="aviso-suave" style="margin-bottom:14px">
                    No hay forma de <b>consultar</b> la contraseña de nadie: en la base solo
                    hay un hash, y eso es precisamente lo que la hace segura. Lo que se
                    puede es poner una nueva y dictarla.
                </p>

                <form method="post"
                      onsubmit="return confirm('¿Cambiar la contraseña de <?= e(addslashes($u['name'])) ?>?\n\nLa actual dejará de funcionar.')">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="clave">
                    <div class="campo" style="margin-bottom:12px">
                        <label for="cl-nueva">Contraseña nueva</label>
                        <input id="cl-nueva" name="password" type="text" required minlength="8"
                               value="<?= e(contrasenaSugerida()) ?>">
                        <p class="ayuda">Se propone una fácil de dictar por teléfono.</p>
                    </div>
                    <button class="btn-mini" type="submit">Cambiar contraseña</button>
                </form>
            </div>
        </div>

        <?php if ($historialSub): ?>
            <div class="caja">
                <div class="cabeza"><h2>Historial de suscripciones</h2></div>
                <div class="tabla-envoltorio">
                    <table class="tabla">
                        <thead><tr><th>Plan</th><th>Estado</th><th class="cifra">Vence</th></tr></thead>
                        <tbody>
                            <?php foreach ($historialSub as $s): ?>
                                <?php
                                $vencida = $s['expires_at'] && strtotime($s['expires_at']) <= time();
                                $vigente = $s['status'] === 'active' && !$vencida;
                                ?>
                                <tr>
                                    <td class="compacta"><b><?= e($s['plan']) ?></b></td>
                                    <td class="compacta">
                                        <span class="distintivo <?= $vigente ? 'verde' : ($s['status'] === 'cancelled' ? 'rojo' : 'gris') ?>">
                                            <?= $vigente ? 'Vigente' : ($s['status'] === 'cancelled' ? 'Cancelada' : 'Vencida') ?>
                                        </span>
                                    </td>
                                    <td class="cifra" style="color:var(--texto-tenue);font-size:.82rem">
                                        <?= $s['expires_at'] ? e(date('d/m/Y', strtotime($s['expires_at']))) : '—' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>

    </div>
</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
