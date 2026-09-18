<?php
/**
 * admin/usuarios/index.php — Administrar usuarios
 *
 * Consultar, activar, desactivar y cambiar rol. También permite activar
 * una suscripción a mano, algo necesario mientras no haya pasarela de
 * pagos (y útil después, para cortesías y casos especiales).
 *
 * Dos salvaguardas: no se puede quitar el rol ni desactivar al único
 * administrador activo, y nunca se muestran ni se tocan las contraseñas.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

/*
 * Datos que se devuelven al formulario si la creación falla, para que
 * nadie tenga que escribirlo todo otra vez.
 */
$nuevo    = ['name' => '', 'email' => '', 'role' => 'user', 'status' => 'active',
             'birth_year' => '', 'guardian_email' => ''];
$errores  = [];
$recienCreado = null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $id     = (int) ($_POST['id'] ?? 0);

    if ($accion === 'crear') {

        foreach (array_keys($nuevo) as $campo) {
            $nuevo[$campo] = trim((string) ($_POST[$campo] ?? $nuevo[$campo]));
        }

        // La contraseña no se recuerda entre intentos: si algo falla, se
        // vuelve a generar o a escribir. Guardarla en el formulario la
        // dejaría escrita en el HTML de la página.
        $clave = (string) ($_POST['password'] ?? '');

        $r = crearUsuarioComoAdmin($nuevo + ['password' => $clave]);

        if ($r['ok']) {
            /*
             * La contraseña se enseña UNA vez, aquí, y no se guarda en
             * ningún sitio: en la base solo queda su hash. Si el
             * administrador no la anota ahora, tendrá que ponerle otra.
             * Es incómodo a propósito — una contraseña recuperable no es
             * una contraseña.
             */
            $_SESSION['recien_creado'] = [
                'nombre' => $nuevo['name'],
                'correo' => $nuevo['email'],
                'clave'  => $clave,
            ];
            mensaje('ok', 'Cuenta creada.');
            redirigir('admin/usuarios/');
        }

        $errores = $r['errores'];
        mensaje('error', 'Revisa los datos: la cuenta no se creó.');
    }

    if ($id > 0) {
        switch ($accion) {

            case 'rol':
                $r = cambiarRolUsuario($id, post('rol'));
                mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Rol actualizado.' : $r['error']);
                break;

            case 'estado':
                // El botón viene deshabilitado para uno mismo, pero eso solo
                // afecta a la interfaz: un envío hecho a mano la saltaría.
                // La regla se aplica aquí, donde no se puede eludir.
                if ($id === usuarioActualId() && post('estado') !== 'active') {
                    mensaje('error', 'No puedes desactivar tu propia cuenta.');
                    break;
                }
                $r = cambiarEstadoUsuario($id, post('estado'));
                mensaje($r['ok'] ? 'ok' : 'error', $r['ok'] ? 'Estado actualizado.' : $r['error']);
                break;

            case 'suscribir':
                $r = activarSuscripcion($id, post('plan'), post('ciclo'));
                mensaje($r['ok'] ? 'ok' : 'error',
                    $r['ok'] ? 'Suscripción activada manualmente.' : $r['error']);
                break;
        }
    }

    /*
     * Se redirige después de escribir, salvo cuando la creación falló.
     *
     * Al redirigir se pierden `$errores` y lo que el administrador ya
     * había escrito, así que volvería a un formulario vacío con un
     * «revisa los datos» que no dice qué revisar. En ese caso se pinta la
     * página aquí mismo, con los errores señalados campo por campo.
     */
    if (!$errores) {
        redirigir('admin/usuarios/' . ($_SERVER['QUERY_STRING'] ? '?' . $_SERVER['QUERY_STRING'] : ''));
    }
}

// ── Filtros ──────────────────────────────────────────────────────────
$fBuscar = mb_substr(get('buscar'), 0, 80);
$fRol    = get('rol');
$fEstado = get('estado');

$where  = ['1 = 1'];
$params = [];

if ($fBuscar !== '') {
    $where[] = '(u.name LIKE ? OR u.email LIKE ?)';
    $t = '%' . escaparLike($fBuscar) . '%';
    $params[] = $t;
    $params[] = $t;
}
if (in_array($fRol, ['admin', 'user', 'teacher', 'school_admin', 'student'], true)) {
    $where[] = 'u.role = ?';
    $params[] = $fRol;
}
if (in_array($fEstado, ['active', 'inactive', 'pending'], true)) {
    $where[] = 'u.status = ?';
    $params[] = $fEstado;
}

$usuarios = traerTodo(
    'SELECT u.id, u.name, u.email, u.role, u.status, u.created_at, u.last_login_at,
            p.name AS plan, p.slug AS plan_slug, s.expires_at, s.billing_cycle
       FROM users u
  LEFT JOIN subscriptions s
         ON s.id = (SELECT s2.id FROM subscriptions s2
                     WHERE s2.user_id = u.id AND s2.status = "active"
                       AND (s2.expires_at IS NULL OR s2.expires_at > NOW())
                  ORDER BY s2.expires_at DESC LIMIT 1)
  LEFT JOIN plans p ON p.id = s.plan_id
      WHERE ' . implode(' AND ', $where) . '
   ORDER BY u.created_at DESC
      LIMIT 200',
    $params
);

$planes = traerTodo('SELECT slug, name FROM plans WHERE slug <> "free" ORDER BY sort_order');

// La contraseña recién creada se enseña una sola vez y se descarta.
$recienCreado = $_SESSION['recien_creado'] ?? null;
unset($_SESSION['recien_creado']);

// Una contraseña propuesta, distinta en cada carga.
$sugerida = contrasenaSugerida();

/*
 * Conteo por tipo de cuenta para las pestañas.
 *
 * Se cuenta sobre TODA la tabla y no sobre el filtro puesto: una
 * pestaña que dijera «Docentes 0» porque hay un filtro de estado
 * encima haría pensar que no hay docentes.
 */
$conteoRoles = [];
foreach (traerTodo('SELECT role, COUNT(*) AS total FROM users GROUP BY role') as $f) {
    $conteoRoles[$f['role']] = (int) $f['total'];
}
$totalCuentas = array_sum($conteoRoles);

$titulo    = 'Usuarios';
$panelZona = 'usuarios';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Usuarios</h1>
        <p><?= count($usuarios) ?> <?= count($usuarios) === 1 ? 'cuenta' : 'cuentas' ?> con los filtros puestos · <?= contarAdmins() ?> administradores activos.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/metricas/')) ?>">📈 Métricas</a>
    </div>
</div>

<?php
/*
 * Las pestañas son el mismo filtro `rol` de abajo, pero puesto donde se
 * usa. «Enséñame los docentes» es la pregunta que más se hace en esta
 * pantalla, y esconderla dentro de un desplegable de cinco opciones la
 * convierte en tres clics.
 */
$pestanas = [
    ''             => ['Todas',       '👥', $totalCuentas],
    'user'         => ['Familias',    '👨‍👩‍👧', $conteoRoles['user'] ?? 0],
    'teacher'      => ['Docentes',    '🍎', $conteoRoles['teacher'] ?? 0],
    'student'      => ['Estudiantes', '🎒', $conteoRoles['student'] ?? 0],
    'school_admin' => ['Escuelas',    '🏫', $conteoRoles['school_admin'] ?? 0],
    'admin'        => ['Admins',      '🛡️', $conteoRoles['admin'] ?? 0],
];
?>
<nav class="pestanas" aria-label="Tipo de cuenta">
    <?php foreach ($pestanas as $clave => [$nombre, $ico, $n]): ?>
        <?php
        $q = $_GET;
        $q['rol'] = $clave;
        if ($clave === '') {
            unset($q['rol']);
        }
        ?>
        <a class="<?= $fRol === $clave ? 'activo' : '' ?>"
           href="<?= e(url('admin/usuarios/' . ($q ? '?' . http_build_query($q) : ''))) ?>">
            <span aria-hidden="true"><?= $ico ?></span>
            <?= e($nombre) ?>
            <span class="cuenta"><?= $n ?></span>
        </a>
    <?php endforeach; ?>
</nav>

<?php if ($recienCreado): ?>
    <div class="caja" style="border-left:5px solid var(--verde)">
        <h2 style="font-size:1rem;margin-bottom:6px">✅ Cuenta creada</h2>
        <p style="color:var(--texto-tenue);font-size:.9rem;margin-bottom:12px">
            Esta contraseña <strong>no se puede volver a ver</strong>: en la base solo
            queda cifrada. Anótala o entrégala ahora.
        </p>
        <div class="credenciales">
            <div><span>Nombre</span><b><?= e($recienCreado['nombre']) ?></b></div>
            <div><span>Correo</span><b><?= e($recienCreado['correo']) ?></b></div>
            <div><span>Contraseña</span><b class="clave"><?= e($recienCreado['clave']) ?></b></div>
        </div>
    </div>
<?php endif; ?>

<?php
/*
 * El formulario va plegado. Crear cuentas a mano es lo que menos se hace
 * en esta pantalla —lo normal es que la gente se registre sola— y
 * desplegado empujaría la lista de usuarios fuera de la vista.
 */
?>
<details class="caja crear-usuario" <?= $errores ? 'open' : '' ?>>
    <summary>
        <span aria-hidden="true">➕</span>
        Crear una cuenta a mano
    </summary>

    <p class="ayuda-crear">
        Útil para dar de alta a un docente, a una familia sin correo propio o a una
        cuenta de prueba. Quien se registra por su cuenta no pasa por aquí.
    </p>

    <form method="post" class="rejilla-crear">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="crear">

        <div class="campo">
            <label for="c-name">Nombre</label>
            <input id="c-name" name="name" type="text" required maxlength="120"
                   value="<?= e($nuevo['name']) ?>">
            <?php if (isset($errores['name'])): ?>
                <p class="error-campo"><?= e($errores['name']) ?></p>
            <?php endif; ?>
        </div>

        <div class="campo">
            <label for="c-email">Correo</label>
            <input id="c-email" name="email" type="email" required maxlength="190"
                   value="<?= e($nuevo['email']) ?>">
            <?php if (isset($errores['email'])): ?>
                <p class="error-campo"><?= e($errores['email']) ?></p>
            <?php endif; ?>
        </div>

        <div class="campo">
            <label for="c-password">Contraseña</label>
            <input id="c-password" name="password" type="text" required minlength="8"
                   value="<?= e($sugerida) ?>">
            <p class="ayuda">Se propone una fácil de dictar. Puedes cambiarla.</p>
            <?php if (isset($errores['password'])): ?>
                <p class="error-campo"><?= e($errores['password']) ?></p>
            <?php endif; ?>
        </div>

        <div class="campo">
            <label for="c-role">Rol</label>
            <select id="c-role" name="role">
                <?php foreach (['user' => 'Usuario', 'teacher' => 'Docente',
                                'school_admin' => 'Admin. escolar', 'student' => 'Estudiante',
                                'admin' => 'Administrador'] as $k => $et): ?>
                    <option value="<?= e($k) ?>" <?= $nuevo['role'] === $k ? 'selected' : '' ?>>
                        <?= e($et) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="campo">
            <label for="c-status">Estado</label>
            <select id="c-status" name="status">
                <option value="active"   <?= $nuevo['status'] === 'active'   ? 'selected' : '' ?>>Activo</option>
                <option value="pending"  <?= $nuevo['status'] === 'pending'  ? 'selected' : '' ?>>Pendiente</option>
                <option value="inactive" <?= $nuevo['status'] === 'inactive' ? 'selected' : '' ?>>Desactivado</option>
            </select>
        </div>

        <div class="campo">
            <label for="c-birth">Año de nacimiento</label>
            <input id="c-birth" name="birth_year" type="number" inputmode="numeric"
                   min="<?= (int) date('Y') - 100 ?>" max="<?= (int) date('Y') ?>"
                   value="<?= e($nuevo['birth_year']) ?>" placeholder="opcional">
            <p class="ayuda">Solo el año. No se guarda la fecha completa.</p>
            <?php if (isset($errores['birth_year'])): ?>
                <p class="error-campo"><?= e($errores['birth_year']) ?></p>
            <?php endif; ?>
        </div>

        <div class="campo ancho">
            <label for="c-guardian">Correo del adulto responsable</label>
            <input id="c-guardian" name="guardian_email" type="email" maxlength="190"
                   value="<?= e($nuevo['guardian_email']) ?>" placeholder="obligatorio para menores de 14 años">
            <p class="ayuda">
                Lo exige el Decreto 0769 de 2026 cuando la cuenta es de un menor de 14 años.
            </p>
            <?php if (isset($errores['guardian_email'])): ?>
                <p class="error-campo"><?= e($errores['guardian_email']) ?></p>
            <?php endif; ?>
        </div>

        <div class="campo ancho">
            <button class="btn-mini solido" type="submit">Crear cuenta</button>
        </div>
    </form>
</details>

<div class="caja">

    <form class="barra-filtros" method="get">
        <input type="search" name="buscar" value="<?= e($fBuscar) ?>"
               placeholder="Buscar por nombre o correo…" aria-label="Buscar">

        <select name="rol" aria-label="Rol">
            <option value="">Todos los roles</option>
            <?php foreach (['admin' => 'Administrador', 'user' => 'Usuario', 'teacher' => 'Docente',
                            'school_admin' => 'Admin. escolar', 'student' => 'Estudiante'] as $k => $et): ?>
                <option value="<?= e($k) ?>" <?= $fRol === $k ? 'selected' : '' ?>><?= e($et) ?></option>
            <?php endforeach; ?>
        </select>

        <select name="estado" aria-label="Estado">
            <option value="">Todos los estados</option>
            <option value="active"   <?= $fEstado === 'active'   ? 'selected' : '' ?>>Activos</option>
            <option value="inactive" <?= $fEstado === 'inactive' ? 'selected' : '' ?>>Desactivados</option>
            <option value="pending"  <?= $fEstado === 'pending'  ? 'selected' : '' ?>>Pendientes</option>
        </select>

        <button class="btn-mini solido" type="submit">Filtrar</button>
        <a class="btn-mini" href="<?= e(url('admin/usuarios/')) ?>">Limpiar</a>
    </form>

    <?php if ($usuarios): ?>
        <div class="tabla-envoltorio">
            <table class="tabla">
                <thead>
                    <tr>
                        <th>Usuario</th><th>Rol</th><th>Plan</th>
                        <th>Estado</th><th>Registro</th><th></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($usuarios as $u): ?>
                        <?php $esYo = (int) $u['id'] === usuarioActualId(); ?>
                        <tr>
                            <td>
                                <a href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $u['id'])) ?>"
                                   style="color:var(--oscuro);font-weight:700"><?= e($u['name']) ?></a>
                                <?php if ($esYo): ?>
                                    <span class="distintivo azul" style="margin-left:5px">tú</span>
                                <?php endif; ?>
                                <br><small style="color:var(--texto-tenue)"><?= e($u['email']) ?></small>
                            </td>

                            <td class="compacta">
                                <form class="enlinea" method="post">
                                    <?= campoCsrf() ?>
                                    <input type="hidden" name="accion" value="rol">
                                    <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                    <select name="rol" onchange="this.form.submit()"
                                            style="padding:5px 9px;border:2px solid var(--borde);border-radius:8px;font-family:inherit;font-size:.82rem">
                                        <?php foreach (['user' => 'Usuario', 'teacher' => 'Docente',
                                                        'school_admin' => 'Admin. escolar', 'student' => 'Estudiante',
                                                        'admin' => 'Administrador'] as $k => $et): ?>
                                            <option value="<?= e($k) ?>" <?= $u['role'] === $k ? 'selected' : '' ?>>
                                                <?= e($et) ?>
                                            </option>
                                        <?php endforeach; ?>
                                    </select>
                                </form>
                            </td>

                            <td class="compacta">
                                <?php if ($u['plan']): ?>
                                    <span class="distintivo verde"><?= e($u['plan']) ?></span><br>
                                    <small style="color:var(--texto-tenue)">
                                        hasta <?= e(date('d/m/Y', strtotime($u['expires_at']))) ?>
                                    </small>
                                <?php else: ?>
                                    <span class="distintivo gris">Gratis</span>
                                <?php endif; ?>
                            </td>

                            <td class="compacta">
                                <span class="distintivo <?= $u['status'] === 'active' ? 'verde' : 'rojo' ?>">
                                    <?= $u['status'] === 'active' ? 'Activo'
                                        : ($u['status'] === 'inactive' ? 'Desactivado' : 'Pendiente') ?>
                                </span>
                            </td>

                            <td class="compacta" style="color:var(--texto-tenue);font-size:.83rem">
                                <?= e(date('d/m/Y', strtotime($u['created_at']))) ?>
                            </td>

                            <td>
                                <div class="acciones-celda">
                                    <a class="btn-mini" href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $u['id'])) ?>">Ficha</a>

                                    <?php if (!$u['plan']): ?>
                                        <form class="enlinea" method="post">
                                            <?= campoCsrf() ?>
                                            <input type="hidden" name="accion" value="suscribir">
                                            <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                            <input type="hidden" name="ciclo" value="yearly">
                                            <select name="plan"
                                                    style="padding:5px 9px;border:2px solid var(--borde);border-radius:8px;font-family:inherit;font-size:.8rem">
                                                <?php foreach ($planes as $p): ?>
                                                    <option value="<?= e($p['slug']) ?>"><?= e($p['name']) ?></option>
                                                <?php endforeach; ?>
                                            </select>
                                            <button class="btn-mini" type="submit" title="Activar suscripción anual a mano">
                                                Activar
                                            </button>
                                        </form>
                                    <?php endif; ?>

                                    <form class="enlinea" method="post">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="estado">
                                        <input type="hidden" name="id" value="<?= (int) $u['id'] ?>">
                                        <input type="hidden" name="estado"
                                               value="<?= $u['status'] === 'active' ? 'inactive' : 'active' ?>">
                                        <button class="btn-mini <?= $u['status'] === 'active' ? 'peligro' : '' ?>"
                                                type="submit" <?= $esYo ? 'disabled title="No puedes desactivarte a ti mismo"' : '' ?>>
                                            <?= $u['status'] === 'active' ? 'Desactivar' : 'Activar' ?>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    <?php else: ?>
        <div class="sin-datos">
            <span class="ico" aria-hidden="true">👥</span>
            <h3>No hay usuarios con esos filtros</h3>
        </div>
    <?php endif; ?>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
