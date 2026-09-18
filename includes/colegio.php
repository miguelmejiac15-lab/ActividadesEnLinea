<?php
/**
 * colegio.php — Licencia de colegio
 *
 * Un colegio contrata (o prueba) la plataforma y todos los suyos tienen
 * acceso completo sin pagar uno a uno.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA LICENCIA NO INVENTA UN CAMINO NUEVO DE PERMISOS
 * ─────────────────────────────────────────────────────────────────────
 *
 * Todo el acceso de la plataforma pasa por `suscripcionVigente()`. La
 * licencia hace exactamente lo mismo que un pago: **crea una fila en
 * `subscriptions`**, con `school_id` puesto y sin `expires_at` cuando es
 * indefinida.
 *
 * Es la decisión importante de este archivo. La alternativa —comprobar
 * «¿pertenece a un colegio?» en cada sitio donde hoy se mira la
 * suscripción— duplicaría la lógica de acceso en veinte archivos, y el
 * día que se olvidara uno alguien vería contenido que no le toca. Así, si
 * la suscripción existe, TODO funciona sin tocarse: el catálogo, el área
 * Escuela, «Mi espacio» y el modelo del 30%.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUIÉN CREA A QUIÉN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Solo un administrador de la plataforma crea colegios y da de alta a sus
 * docentes. Un docente del colegio crea sus cursos y sus estudiantes,
 * como cualquier otro docente.
 *
 * Los estudiantes de un colegio se crean **por nombre, sin validación de
 * correo**: son menores que no tienen dirección propia, y pedir una haría
 * inviable dar de alta una clase entera.
 */

declare(strict_types=1);


// =====================================================================
//  ¿ESTÁ INSTALADO?
// =====================================================================

/** ¿Existen las columnas de licencia? Permite desplegar antes de migrar. */
function colegiosInstalados(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "schools"
                AND COLUMN_NAME = "plan_id"'
        );
    }

    return $listo;
}


// =====================================================================
//  LEER
// =====================================================================

function colegioPorId(int $id): ?array
{
    if (!colegiosInstalados()) {
        return null;
    }

    return traerUno(
        'SELECT s.*, p.name AS plan_nombre, p.slug AS plan_slug
           FROM schools s LEFT JOIN plans p ON p.id = s.plan_id
          WHERE s.id = ?',
        [$id]
    );
}

function colegioPorSlug(string $slug): ?array
{
    if (!colegiosInstalados()) {
        return null;
    }

    $slug = slugificar(trim($slug));

    if ($slug === '') {
        return null;
    }

    return traerUno(
        'SELECT s.*, p.name AS plan_nombre, p.slug AS plan_slug
           FROM schools s LEFT JOIN plans p ON p.id = s.plan_id
          WHERE s.slug = ?',
        [$slug]
    );
}

/** Todos los colegios, con sus conteos, para el panel. */
function colegios(): array
{
    if (!colegiosInstalados()) {
        return [];
    }

    return traerTodo(
        'SELECT s.*, p.name AS plan_nombre,
                (SELECT COUNT(*) FROM school_users su
                  WHERE su.school_id = s.id AND su.role = "teacher") AS docentes,
                (SELECT COUNT(*) FROM school_users su
                  WHERE su.school_id = s.id AND su.role = "student") AS estudiantes,
                (SELECT COUNT(*) FROM courses c WHERE c.school_id = s.id) AS cursos
           FROM schools s
      LEFT JOIN plans p ON p.id = s.plan_id
       ORDER BY s.es_prueba DESC, s.name'
    );
}

/** El colegio al que pertenece un usuario, o null. */
function colegioDeUsuario(?int $usuarioId = null): ?array
{
    if (!colegiosInstalados()) {
        return null;
    }

    $usuarioId ??= usuarioActualId();

    if ($usuarioId === null) {
        return null;
    }

    return traerUno(
        'SELECT s.*, su.role AS rol_en_colegio, p.name AS plan_nombre
           FROM school_users su
           JOIN schools s ON s.id = su.school_id
      LEFT JOIN plans p   ON p.id = s.plan_id
          WHERE su.user_id = ? AND s.status = "active"
       ORDER BY su.created_at LIMIT 1',
        [$usuarioId]
    );
}

/** Miembros del colegio, opcionalmente de un rol. */
function miembrosDeColegio(int $colegioId, string $rol = ''): array
{
    $sql = 'SELECT u.id, u.name, u.email, u.role, u.status, u.last_login_at,
                   su.role AS rol_en_colegio, su.created_at AS vinculado,
                   (SELECT COUNT(*) FROM courses c WHERE c.teacher_id = u.id) AS cursos,
                   (SELECT COUNT(*) FROM course_students cs WHERE cs.user_id = u.id) AS matriculas
              FROM school_users su
              JOIN users u ON u.id = su.user_id
             WHERE su.school_id = ?';
    $params = [$colegioId];

    if ($rol !== '') {
        $sql .= ' AND su.role = ?';
        $params[] = $rol;
    }

    $sql .= ' ORDER BY su.role, u.name';

    return traerTodo($sql, $params);
}

/** Cursos del colegio. */
function cursosDeColegio(int $colegioId): array
{
    return traerTodo(
        'SELECT c.*, u.name AS docente,
                (SELECT COUNT(*) FROM course_students cs WHERE cs.course_id = c.id) AS estudiantes,
                (SELECT COUNT(*) FROM course_activities ca WHERE ca.course_id = c.id) AS actividades
           FROM courses c
      LEFT JOIN users u ON u.id = c.teacher_id
          WHERE c.school_id = ?
       ORDER BY c.status = "archived", c.name',
        [$colegioId]
    );
}


// =====================================================================
//  LA LICENCIA
// =====================================================================

/** ¿La licencia del colegio está viva? */
function licenciaVigente(array $colegio): bool
{
    if (($colegio['status'] ?? '') !== 'active') {
        return false;
    }
    if (empty($colegio['plan_id'])) {
        return false;
    }

    $hasta = $colegio['licencia_hasta'] ?? null;

    // NULL es indefinida, que es lo que hace falta para probar sin estar
    // renovando cada mes.
    return $hasta === null || $hasta === '' || strtotime((string) $hasta) > time();
}

/** Texto de la vigencia, para el panel. */
function textoLicencia(array $colegio): string
{
    if (empty($colegio['plan_id'])) {
        return 'sin licencia';
    }
    if (($colegio['status'] ?? '') !== 'active') {
        return 'colegio inactivo';
    }

    $hasta = $colegio['licencia_hasta'] ?? null;

    if ($hasta === null || $hasta === '') {
        return 'indefinida';
    }

    return strtotime((string) $hasta) > time()
        ? 'hasta el ' . fechaLarga((string) $hasta)
        : 'venció el ' . fechaLarga((string) $hasta);
}

/**
 * Da la suscripción que corresponde a la licencia del colegio.
 *
 * No usa `otorgarSuscripcion()` a propósito: aquella suma un periodo a
 * partir de hoy porque viene de un pago, y aquí la fecha la manda el
 * colegio —incluido «sin fecha»—. Mezclarlas haría que una licencia
 * indefinida se convirtiera en una de un año sin que nadie lo pidiera.
 */
function otorgarLicenciaEscolar(int $usuarioId, array $colegio): bool
{
    if (!licenciaVigente($colegio)) {
        return false;
    }

    $planId = (int) $colegio['plan_id'];
    $hasta  = $colegio['licencia_hasta'] ?? null;
    $hasta  = ($hasta === '' ? null : $hasta);

    $vigente = traerUno(
        'SELECT id FROM subscriptions
          WHERE user_id = ? AND school_id = ? AND status = "active" LIMIT 1',
        [$usuarioId, (int) $colegio['id']]
    );

    if ($vigente) {
        ejecutar(
            'UPDATE subscriptions
                SET plan_id = ?, expires_at = ?, status = "active"
              WHERE id = ?',
            [$planId, $hasta, (int) $vigente['id']]
        );

        return true;
    }

    /*
     * `amount_cop = 0` y proveedor «licencia»: una licencia escolar no es
     * un cobro y no debe aparecer en los ingresos. Si figurara como un
     * pago de cero pesos ensuciaría los informes de negocio con filas que
     * no son ventas.
     */
    insertar(
        'INSERT INTO subscriptions
            (user_id, plan_id, school_id, billing_cycle, amount_cop, status,
             starts_at, expires_at, payment_provider, payment_reference)
         VALUES (?, ?, ?, "yearly", 0, "active", NOW(), ?, "licencia", ?)',
        [$usuarioId, $planId, (int) $colegio['id'], $hasta,
         'colegio:' . $colegio['slug']]
    );

    return true;
}

/** Quita la suscripción que venía de este colegio. */
function retirarLicenciaEscolar(int $usuarioId, int $colegioId): void
{
    ejecutar(
        'UPDATE subscriptions SET status = "cancelled"
          WHERE user_id = ? AND school_id = ? AND status = "active"',
        [$usuarioId, $colegioId]
    );
}

/**
 * Vuelve a aplicar la licencia a todos los miembros.
 *
 * Se llama al cambiar el plan o la fecha: sin esto, el cambio valdría
 * solo para quien entrara después, y los que ya estaban se quedarían con
 * la licencia vieja sin que nadie lo notara.
 */
function refrescarLicencias(int $colegioId): int
{
    $colegio = colegioPorId($colegioId);

    if (!$colegio) {
        return 0;
    }

    $n = 0;

    foreach (miembrosDeColegio($colegioId) as $m) {
        if (licenciaVigente($colegio)) {
            if (otorgarLicenciaEscolar((int) $m['id'], $colegio)) { $n++; }
        } else {
            retirarLicenciaEscolar((int) $m['id'], $colegioId);
        }
    }

    return $n;
}


// =====================================================================
//  VINCULAR
// =====================================================================

/**
 * Mete a alguien en el colegio y le da la licencia.
 *
 * @return array{ok:bool, error:?string}
 */
function vincularAColegio(int $colegioId, int $usuarioId, string $rol = 'student'): array
{
    $rol = in_array($rol, ['school_admin', 'teacher', 'student'], true) ? $rol : 'student';

    $colegio = colegioPorId($colegioId);

    if (!$colegio) {
        return ['ok' => false, 'error' => 'Ese colegio no existe.'];
    }

    $usuario = traerUno('SELECT id, name, role FROM users WHERE id = ?', [$usuarioId]);

    if (!$usuario) {
        return ['ok' => false, 'error' => 'Esa cuenta no existe.'];
    }

    $ya = traerValor(
        'SELECT 1 FROM school_users WHERE school_id = ? AND user_id = ?',
        [$colegioId, $usuarioId]
    );

    if (!$ya) {
        ejecutar('INSERT INTO school_users (school_id, user_id, role) VALUES (?, ?, ?)',
                 [$colegioId, $usuarioId, $rol]);
    } else {
        ejecutar('UPDATE school_users SET role = ? WHERE school_id = ? AND user_id = ?',
                 [$rol, $colegioId, $usuarioId]);
    }

    /*
     * El rol EN LA PLATAFORMA se ajusta al del colegio, pero nunca hacia
     * abajo: a un administrador de la plataforma que además sea docente de
     * un colegio no se le degrada la cuenta por vincularlo.
     *
     * `school_admin` sube desde `user` y desde `teacher` —un profesor al
     * que se nombra coordinador no deja de necesitar entrar a sus propios
     * cursos—, pero nunca desde `admin`.
     */
    if ($rol === 'teacher' && $usuario['role'] === 'user') {
        ejecutar('UPDATE users SET role = "teacher" WHERE id = ?', [$usuarioId]);
    }

    if ($rol === 'school_admin' && in_array($usuario['role'], ['user', 'teacher'], true)) {
        ejecutar('UPDATE users SET role = "school_admin" WHERE id = ?', [$usuarioId]);
    }

    otorgarLicenciaEscolar($usuarioId, $colegio);

    return ['ok' => true, 'error' => null];
}

/** Saca a alguien del colegio y le retira la licencia. Su cuenta no se borra. */
function desvincularDeColegio(int $colegioId, int $usuarioId): void
{
    ejecutar('DELETE FROM school_users WHERE school_id = ? AND user_id = ?',
             [$colegioId, $usuarioId]);

    retirarLicenciaEscolar($usuarioId, $colegioId);
}


// =====================================================================
//  CREAR CUENTAS DEL COLEGIO
// =====================================================================

/** Dominio de los usuarios generados de este colegio. */
function dominioDeColegio(array $colegio): string
{
    $d = trim((string) ($colegio['dominio'] ?? ''));

    return $d !== '' ? $d : dominioDeAula();
}

/**
 * Un identificador con forma de correo dentro del colegio.
 *
 * Igual que el de aula pero con el dominio del colegio, para que se vea
 * de un vistazo de dónde sale cada cuenta.
 */
function correoDeColegio(string $nombre, array $colegio): string
{
    $base = preg_replace('/-+/', '', slugificar($nombre)) ?: 'alumno';
    $base = mb_substr($base, 0, 14);

    if ($base === '') {
        $base = 'alumno';
    }

    $dominio = dominioDeColegio($colegio);

    for ($i = 0; $i < 12; $i++) {
        $correo = $base . substr(bin2hex(random_bytes(3)), 0, 4) . '@' . $dominio;

        if (!traerValor('SELECT id FROM users WHERE email = ?', [$correo])) {
            return $correo;
        }
    }

    return $base . bin2hex(random_bytes(5)) . '@' . $dominio;
}

/**
 * Crea una cuenta del colegio: docente o estudiante.
 *
 * Devuelve las credenciales EN CLARO una sola vez, para entregarlas. No
 * se guardan: en la base solo queda el hash.
 *
 * @return array{ok:bool, error:?string, usuario:?array, clave:?string}
 */
function crearCuentaDeColegio(int $colegioId, string $nombre, string $rol,
                              string $correo = ''): array
{
    $vacio = ['ok' => false, 'usuario' => null, 'clave' => null];

    $colegio = colegioPorId($colegioId);

    if (!$colegio) {
        return $vacio + ['error' => 'Ese colegio no existe.'];
    }

    $nombre = trim(preg_replace('/\s+/u', ' ', $nombre) ?? '');

    if (mb_strlen($nombre) < 2) {
        return $vacio + ['error' => 'Falta el nombre.'];
    }
    if (mb_strlen($nombre) > 120) {
        return $vacio + ['error' => "«$nombre»: nombre demasiado largo."];
    }

    $rol = in_array($rol, ['teacher', 'school_admin'], true) ? $rol : 'student';

    // Correo real si lo hay; si no, uno del colegio.
    $correo = trim($correo);

    if ($correo !== '') {
        $valido = correoValido($correo);

        if ($valido === null) {
            return $vacio + ['error' => "«$nombre»: ese correo no es válido."];
        }
        if (traerValor('SELECT id FROM users WHERE email = ?', [$valido])) {
            return $vacio + ['error' => "«$nombre»: ya hay una cuenta con ese correo. "
                                      . 'Búscala y vincúlala en vez de crear otra.'];
        }

        $correo = $valido;
    } else {
        $correo = correoDeColegio($nombre, $colegio);
    }

    $clave = claveMemorable();

    $pdo    = db();
    $propia = !$pdo->inTransaction();

    if ($propia) {
        $pdo->beginTransaction();
    }

    try {
        $id = (int) insertar(
            'INSERT INTO users (name, email, password, role, status)
             VALUES (?, ?, ?, ?, "active")',
            [$nombre, $correo, password_hash($clave, PASSWORD_DEFAULT), $rol]
        );

        if (function_exists('entregarBienvenida')) {
            entregarBienvenida($id);
        }

        $r = vincularAColegio($colegioId, $id, $rol);

        if (!$r['ok']) {
            throw new RuntimeException((string) $r['error']);
        }

        if ($propia) {
            $pdo->commit();
        }

    } catch (Throwable $e) {
        if ($propia && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[colegio] crearCuentaDeColegio: ' . $e->getMessage());

        return $vacio + ['error' => "«$nombre»: no se pudo crear la cuenta."];
    }

    return [
        'ok'      => true,
        'error'   => null,
        'clave'   => $clave,
        'usuario' => ['id' => $id, 'name' => $nombre, 'email' => $correo, 'role' => $rol],
    ];
}

/**
 * Restablece la contraseña de un miembro del colegio.
 *
 * @return array{ok:bool, error:?string, clave:?string}
 */
function restablecerClaveDeColegio(int $colegioId, int $usuarioId): array
{
    $miembro = traerUno(
        'SELECT u.id, u.name, u.role FROM school_users su
           JOIN users u ON u.id = su.user_id
          WHERE su.school_id = ? AND su.user_id = ?',
        [$colegioId, $usuarioId]
    );

    if (!$miembro) {
        return ['ok' => false, 'error' => 'Esa persona no está en este colegio.', 'clave' => null];
    }

    if ($miembro['role'] === 'admin') {
        // Un administrador de la plataforma no cambia de contraseña por
        // estar vinculado a un colegio.
        return ['ok' => false, 'clave' => null,
                'error' => 'Esa es una cuenta de administración: su contraseña se cambia '
                         . 'desde Personas.'];
    }

    $clave = claveMemorable();

    ejecutar('UPDATE users SET password = ? WHERE id = ?',
             [password_hash($clave, PASSWORD_DEFAULT), $usuarioId]);

    return ['ok' => true, 'error' => null, 'clave' => $clave];
}


// =====================================================================
//  LA PUERTA DEL COLEGIO
// =====================================================================

/** Dirección propia del colegio, la que se reparte. */
function urlDeColegio(array $colegio): string
{
    return url('colegio/' . rawurlencode((string) $colegio['slug']));
}

/**
 * Cursos del colegio con la lista abierta ahora mismo.
 *
 * Es lo que se le ofrece a un niño que llega por la dirección del
 * colegio: en vez de acordarse del código de su clase, ve las clases
 * abiertas y elige la suya.
 */
function clasesAbiertasDeColegio(int $colegioId): array
{
    if (!aulaInstalada()) {
        return [];
    }

    $cursos = traerTodo(
        'SELECT c.*, u.name AS docente,
                (SELECT COUNT(*) FROM course_students cs WHERE cs.course_id = c.id) AS estudiantes
           FROM courses c
      LEFT JOIN users u ON u.id = c.teacher_id
          WHERE c.school_id = ? AND c.status = "active"
       ORDER BY c.name',
        [$colegioId]
    );

    return array_values(array_filter($cursos, static function (array $c): bool {
        return usaLista($c) && claseAbierta($c) && !empty($c['access_code']);
    }));
}
