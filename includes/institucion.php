<?php
/**
 * institucion.php — El administrador de una organización
 *
 * El cuarto rol de la plataforma. Los otros tres ya tenían su sitio:
 *
 *   · admin        · toda la plataforma          → `admin/`
 *   · teacher      · sus cursos                  → `escuela/`
 *   · student      · lo suyo                     → `usuario/`, `aula/`
 *   · school_admin · **su organización, y solo la suya** → aquí
 *
 * El rol existía en la base desde el principio —está en el ENUM de
 * `users.role` y en el de `school_users.role`— pero no tenía ninguna
 * pantalla: quien lo tuviera entraba al área Escuela y se encontraba «no
 * tienes ningún curso», que es un callejón. Esto es lo que le faltaba.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE VE Y LO QUE NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Ve **su** organización: sus docentes, sus estudiantes, sus cursos y el
 * progreso institucional de esos cursos. Nada de otra organización, y
 * nada de la plataforma.
 *
 * Y no ve **el progreso personal de ningún niño**. Es la consecuencia
 * directa de la separación de contextos: todas las cifras de aquí salen
 * de `activity_progress` unida a `courses` por `course_id`, así que lo
 * que un niño hace en su casa (`course_id = 0`) no entra en ninguna. Un
 * colegio responde de lo que manda, no de lo que un menor juega el
 * domingo por la tarde.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO ES UN PANEL NUEVO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Vive dentro de `escuela/` y no en un área propia. Un administrador de
 * organización necesita exactamente las pantallas que ya existen —los
 * estudiantes de un curso, las actividades asignadas, la rejilla de
 * progreso, la hoja de credenciales— y montarlas otra vez significaría
 * mantenerlas dos veces y que se separen en cuanto se toque una.
 *
 * Lo único suyo de verdad es esta puerta y la vista de conjunto. El
 * alcance lo abre `exigirCursoPropio()`, que a un `school_admin` le deja
 * abrir los cursos DE SU ORGANIZACIÓN igual que a un docente los suyos.
 *
 * Y las acciones de dar de alta gente son literalmente las mismas que
 * usa el panel de administración en `admin/colegios/ver.php`: viven en
 * `accionDeInstitucion()` y las llaman los dos.
 */

declare(strict_types=1);


/** Cuántas cuentas se pueden crear de una vez. */
const MAX_CUENTAS_INSTITUCION = 60;


// =====================================================================
//  QUIÉN ADMINISTRA QUÉ
// =====================================================================

/**
 * La organización que administra este usuario, o null.
 *
 * Exige que la organización esté **activa**: si se desactiva, se retiran
 * las licencias de todos y tampoco tiene sentido seguir gobernándola.
 */
function institucionDeAdmin(?int $usuarioId = null): ?array
{
    if (!function_exists('colegiosInstalados') || !colegiosInstalados()) {
        return null;
    }

    $usuarioId ??= usuarioActualId();

    if ($usuarioId === null) {
        return null;
    }

    static $cache = [];

    if (array_key_exists($usuarioId, $cache)) {
        return $cache[$usuarioId];
    }

    /*
     * Manda `school_users.role`, no `users.role`.
     *
     * El rol de plataforma dice qué clase de cuenta es; el de la tabla
     * de pertenencia dice DE QUÉ organización es administrador. Mirar
     * solo el primero dejaría a un `school_admin` sin organización —o
     * peor, habría que adivinar cuál— y adivinar aquí es enseñarle los
     * niños de otro colegio.
     */
    $cache[$usuarioId] = traerUno(
        'SELECT s.*, p.name AS plan_nombre, su.role AS rol_en_colegio
           FROM school_users su
           JOIN schools s ON s.id = su.school_id
      LEFT JOIN plans   p ON p.id = s.plan_id
          WHERE su.user_id = ? AND su.role = "school_admin" AND s.status = "active"
       ORDER BY su.created_at LIMIT 1',
        [$usuarioId]
    );

    return $cache[$usuarioId];
}

/** ¿El usuario actual administra alguna organización? */
function administraInstitucion(): bool
{
    return institucionDeAdmin() !== null;
}

/**
 * ¿Puede este usuario gobernar ESTA organización?
 *
 * El administrador de la plataforma puede con todas, que es lo que le
 * permite dar soporte. Los demás, solo con la suya.
 */
function puedeAdministrarInstitucion(int $colegioId, ?int $usuarioId = null): bool
{
    if ($usuarioId === null && esAdmin()) {
        return true;
    }

    $suya = institucionDeAdmin($usuarioId);

    return $suya !== null && (int) $suya['id'] === $colegioId;
}

/**
 * Puerta del panel de organización. Devuelve la organización o corta.
 *
 * Como `exigirCursoPropio()`: no existe una versión que devuelva null y
 * deje decidir al llamador, porque ese es justo el descuido que termina
 * enseñando los estudiantes de otra institución.
 */
function exigirInstitucion(): array
{
    exigirSesion();

    $colegio = institucionDeAdmin();

    if (!$colegio) {
        mensaje('info', 'No administras ninguna institución.');
        redirigir('escuela/');
    }

    return $colegio;
}


// =====================================================================
//  LA VISTA DE CONJUNTO
// =====================================================================

/**
 * Las cifras de la organización.
 *
 * **Todo pasa por `courses.school_id`.** Es lo que mantiene cada
 * organización dentro de la suya, y de paso lo que deja fuera el
 * progreso personal: `activity_progress.course_id = 0` no une con
 * ningún curso, así que lo que un niño hace por su cuenta no aparece en
 * ninguna de estas cifras. Ver `cursoDeTrabajo()` en `escuela.php`.
 */
function resumenDeInstitucion(int $colegioId): array
{
    $r = traerUno(
        'SELECT
            (SELECT COUNT(*) FROM school_users
              WHERE school_id = ? AND role = "teacher")            AS docentes,
            (SELECT COUNT(*) FROM school_users
              WHERE school_id = ? AND role = "student")            AS estudiantes,
            (SELECT COUNT(*) FROM courses
              WHERE school_id = ? AND status = "active")           AS cursos,
            (SELECT COUNT(*) FROM course_activities ca
               JOIN courses c ON c.id = ca.course_id
              WHERE c.school_id = ?)                               AS asignaciones,
            /* Trabajo DE CLASE terminado. El JOIN con `courses` por
               `course_id` es el que deja fuera lo personal. */
            (SELECT COUNT(*) FROM activity_progress p
               JOIN courses c ON c.id = p.course_id
              WHERE c.school_id = ? AND p.status = "completed")     AS estaciones_hechas,
            (SELECT COUNT(DISTINCT p.user_id) FROM activity_progress p
               JOIN courses c ON c.id = p.course_id
              WHERE c.school_id = ?
                AND p.updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY)) AS activos_semana',
        array_fill(0, 6, $colegioId)
    );

    return $r ?: ['docentes' => 0, 'estudiantes' => 0, 'cursos' => 0,
                  'asignaciones' => 0, 'estaciones_hechas' => 0, 'activos_semana' => 0];
}

/**
 * Los docentes de la organización con lo que lleva cada uno.
 *
 * Cuenta solo los cursos DE ESTA organización. Un docente puede tener
 * además cursos particulares suyos —una profesora que da clases por su
 * cuenta los sábados— y esos no son asunto del colegio.
 */
function docentesDeInstitucion(int $colegioId): array
{
    return traerTodo(
        'SELECT u.id, u.name, u.email, u.status, u.last_login_at,
                su.role AS rol_en_colegio,
                (SELECT COUNT(*) FROM courses c
                  WHERE c.teacher_id = u.id AND c.school_id = su.school_id
                    AND c.status = "active")                 AS cursos,
                (SELECT COUNT(*) FROM course_students cs
                   JOIN courses c ON c.id = cs.course_id
                  WHERE c.teacher_id = u.id AND c.school_id = su.school_id) AS estudiantes
           FROM school_users su
           JOIN users u ON u.id = su.user_id
          WHERE su.school_id = ? AND su.role IN ("teacher", "school_admin")
       ORDER BY su.role = "teacher", u.name',
        [$colegioId]
    );
}

/**
 * Los cursos de la organización con su avance, en una sola consulta.
 *
 * Es lo que un coordinador mira cada lunes: qué clase va, cuál no ha
 * arrancado y cuál lleva tres semanas sin que nadie entre. Sin esto
 * tenía que abrir los cursos uno a uno para enterarse.
 *
 * `hechas` y `posibles` salen del cruce pertenencia × asignación, que es
 * el mismo que usa la rejilla del docente: estaciones terminadas PARA
 * ESTE CURSO frente a las que tendrían que hacerse entre todos. Lo
 * personal (`course_id = 0`) no entra en ninguna de las dos.
 */
function cursosDeInstitucionConAvance(int $colegioId): array
{
    return traerTodo(
        'SELECT c.id, c.name, c.grade, c.status, c.access_code, c.ruta_modo,
                u.name AS docente,
                (SELECT COUNT(*) FROM course_students cs
                  WHERE cs.course_id = c.id)                       AS estudiantes,
                (SELECT COUNT(*) FROM course_activities ca
                  WHERE ca.course_id = c.id)                       AS actividades,

                /* Estaciones que suman entre todos los matriculados. */
                (SELECT COUNT(*)
                   FROM course_activities ca
                   JOIN activity_stations s ON s.activity_id = ca.activity_id
                   JOIN course_students   cs ON cs.course_id = c.id
                  WHERE ca.course_id = c.id)                       AS posibles,

                (SELECT COUNT(*) FROM activity_progress p
                   JOIN course_students cs ON cs.user_id = p.user_id
                                          AND cs.course_id = c.id
                  WHERE p.course_id = c.id AND p.status = "completed") AS hechas,

                (SELECT COUNT(DISTINCT p.user_id) FROM activity_progress p
                  WHERE p.course_id = c.id
                    AND p.updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY)) AS activos,

                (SELECT MAX(p.updated_at) FROM activity_progress p
                  WHERE p.course_id = c.id)                        AS ultimo
           FROM courses c
      LEFT JOIN users u ON u.id = c.teacher_id
          WHERE c.school_id = ?
       ORDER BY c.status = "archived", c.name',
        [$colegioId]
    );
}

/**
 * Todos los estudiantes de la organización, con dónde están.
 *
 * Nombre, cursos en los que está y cuánto trabajo de clase lleva. Nada
 * más: ni correo, ni fecha de nacimiento, ni acudiente. Un coordinador
 * necesita encontrar a un niño y saber si está trabajando; para todo lo
 * demás entra en su curso, que es donde tiene contexto.
 *
 * Se queda dentro de la organización por `c.school_id`, nunca por una
 * lista de ids que venga de la petición.
 */
function estudiantesDeInstitucion(int $colegioId, string $buscar = ''): array
{
    $sql = 'SELECT u.id, u.name,
                   (SELECT COUNT(*) FROM course_students cs
                      JOIN courses c ON c.id = cs.course_id
                     WHERE cs.user_id = u.id AND c.school_id = su.school_id) AS cursos,
                   (SELECT GROUP_CONCAT(c.name ORDER BY c.name SEPARATOR ", ")
                      FROM course_students cs
                      JOIN courses c ON c.id = cs.course_id
                     WHERE cs.user_id = u.id AND c.school_id = su.school_id) AS clases,
                   (SELECT COUNT(*) FROM activity_progress p
                      JOIN courses c ON c.id = p.course_id
                     WHERE p.user_id = u.id AND c.school_id = su.school_id
                       AND p.status = "completed")                           AS hechas,
                   (SELECT MAX(p.updated_at) FROM activity_progress p
                      JOIN courses c ON c.id = p.course_id
                     WHERE p.user_id = u.id AND c.school_id = su.school_id)   AS ultimo
              FROM school_users su
              JOIN users u ON u.id = su.user_id
             WHERE su.school_id = ? AND su.role = "student"';
    $params = [$colegioId];

    // `escaparLike()` neutraliza los comodines: sin él, buscar «_»
    // devolvería la lista entera de menores.
    if (trim($buscar) !== '') {
        $sql .= ' AND u.name LIKE ?';
        $params[] = '%' . escaparLike(trim($buscar)) . '%';
    }

    $sql .= ' ORDER BY u.name LIMIT 400';

    return traerTodo($sql, $params);
}

/**
 * Estudiantes de la organización que no están en ningún curso suyo.
 *
 * No es un directorio: es la respuesta a «di de alta treinta y solo
 * veintiocho están en clase, ¿quiénes faltan?», que sin esto se responde
 * comparando dos listas a mano.
 */
function estudiantesSinCurso(int $colegioId): array
{
    return traerTodo(
        'SELECT u.id, u.name
           FROM school_users su
           JOIN users u ON u.id = su.user_id
          WHERE su.school_id = ? AND su.role = "student"
            AND NOT EXISTS (
                SELECT 1 FROM course_students cs
                  JOIN courses c ON c.id = cs.course_id
                 WHERE cs.user_id = u.id AND c.school_id = su.school_id
            )
       ORDER BY u.name',
        [$colegioId]
    );
}


// =====================================================================
//  ACCIONES COMPARTIDAS
// =====================================================================

/**
 * Las acciones de gobierno de una organización.
 *
 * Las ejecutan **dos pantallas distintas** —el panel de administración
 * en `admin/colegios/ver.php` y el panel de organización en
 * `escuela/institucion.php`— y son exactamente las mismas. Tenerlas dos
 * veces significaría que un arreglo de seguridad se aplica en una y se
 * olvida en la otra.
 *
 * Quien llama ya comprobó el alcance con `exigirRol('admin')` o con
 * `exigirInstitucion()`. Lo que se comprueba AQUÍ es lo que depende de
 * quién eres dentro de la organización, no de a qué página entraste.
 *
 * @return ?string  null si no reconoce la acción; si no, a dónde ir.
 */
function accionDeInstitucion(array $colegio, string $accion, string $volver): ?string
{
    $colegioId = (int) $colegio['id'];
    $esPlataforma = esAdmin();

    switch ($accion) {

        // ── Crear cuentas en tanda ───────────────────────────────────
        case 'crear':
            $rol = post('rol');

            /*
             * Nombrar administradores de la organización es cosa de la
             * plataforma, no de la propia organización.
             *
             * Si un `school_admin` pudiera crear otros, tendría en la
             * mano el rol que gobierna a todos los docentes y a todos
             * los niños del colegio, y podría repartirlo sin que nadie
             * de la plataforma se enterara.
             */
            if ($rol === 'school_admin' && !$esPlataforma) {
                $rol = 'teacher';
            }

            $rol = in_array($rol, ['teacher', 'school_admin'], true) ? $rol : 'student';

            $lineas = preg_split('/\r\n|\r|\n/', (string) post('nombres')) ?: [];
            $lineas = array_values(array_filter(array_map('trim', $lineas), fn($l) => $l !== ''));

            if (!$lineas) {
                mensaje('error', 'Escribe al menos un nombre.');
                return $volver;
            }

            if (count($lineas) > MAX_CUENTAS_INSTITUCION) {
                mensaje('error', 'Son demasiados de una vez (máximo '
                               . MAX_CUENTAS_INSTITUCION . '). Pártelo en tandas.');
                return $volver;
            }

            $creadas = [];
            $fallos  = [];

            foreach ($lineas as $linea) {
                // «Nombre» o «Nombre, correo@colegio.edu.co»
                $partes = array_map('trim', explode(',', $linea, 3));

                $r = crearCuentaDeColegio($colegioId, $partes[0] ?? '', $rol, $partes[1] ?? '');

                if ($r['ok']) {
                    $creadas[] = [
                        'nombre' => $r['usuario']['name'],
                        'correo' => $r['usuario']['email'],
                        'clave'  => $r['clave'],
                        'rol'    => $r['usuario']['role'],
                    ];
                } else {
                    $fallos[] = $r['error'];
                }
            }

            if ($creadas) {
                $_SESSION['colegio_creadas'] = $creadas;
                mensaje('ok', count($creadas) . ' cuenta(s) creada(s) con licencia de la institución.');
            }

            foreach (array_slice($fallos, 0, 5) as $f) {
                mensaje('error', (string) $f);
            }
            if (count($fallos) > 5) {
                mensaje('info', 'Y ' . (count($fallos) - 5) . ' más con problemas.');
            }

            return $volver;

        // ── Vincular una cuenta que ya existe ────────────────────────
        case 'vincular':
            $rol = post('rol');

            if ($rol === 'school_admin' && !$esPlataforma) {
                $rol = 'teacher';
            }

            $rol = in_array($rol, ['teacher', 'school_admin'], true) ? $rol : 'student';

            $cuenta = buscarCuentaPorCorreo(trim(post('correo')));

            if (!$cuenta) {
                // El mismo mensaje exista o no la cuenta: confirmar que
                // un correo está registrado ya es contar algo de alguien.
                mensaje('info', 'No hay ninguna cuenta activa con ese correo.');
                return $volver;
            }

            $r = vincularAColegio($colegioId, (int) $cuenta['id'], $rol);

            mensaje($r['ok'] ? 'ok' : 'error',
                $r['ok'] ? $cuenta['name'] . ' quedó vinculado con licencia de la institución.'
                         : (string) $r['error']);

            return $volver;

        // ── Sacar de la organización ─────────────────────────────────
        case 'desvincular':
            $uid = (int) post('usuario');

            if (!$esPlataforma && !puedeTocarMiembro($colegioId, $uid)) {
                mensaje('error', 'Esa cuenta la gestiona la plataforma, no la institución.');
                return $volver;
            }

            desvincularDeColegio($colegioId, $uid);
            mensaje('ok', 'Sacado de la institución. Su cuenta y su progreso no se borran, '
                        . 'pero pierde la licencia.');

            return $volver;

        // ── Contraseña nueva ─────────────────────────────────────────
        case 'clave':
            $uid = (int) post('usuario');

            if (!$esPlataforma && !puedeTocarMiembro($colegioId, $uid)) {
                mensaje('error', 'Esa cuenta la gestiona la plataforma, no la institución.');
                return $volver;
            }

            $r = restablecerClaveDeColegio($colegioId, $uid);

            if (!$r['ok']) {
                mensaje('error', (string) $r['error']);
                return $volver;
            }

            $u = traerUno('SELECT name, email, role FROM users WHERE id = ?', [$uid]);

            $_SESSION['colegio_creadas'] = [[
                'nombre' => $u['name'], 'correo' => $u['email'],
                'clave'  => $r['clave'], 'rol' => $u['role'],
            ]];
            mensaje('ok', 'Contraseña nueva. Anótala: no se puede volver a ver.');

            return $volver;

        // ── Crear un curso ───────────────────────────────────────────
        //
        // Lo normal es que cada docente cree los suyos. Aquí hace falta
        // igual: al montar la institución se dan de alta los docentes y
        // sus clases de una sentada, y entrar como cada uno de ellos
        // para crear su curso sería absurdo.
        case 'curso':
            $docenteId = (int) post('docente_id');
            $nombre    = trim(post('curso_nombre'));

            /*
             * El docente tiene que ser DE ESTA organización. El id viene
             * de un formulario, y sin esta comprobación se podría colgar
             * un curso del docente de otro colegio —y con él, ver desde
             * aquí a los niños que ese docente matriculara.
             */
            $suyo = traerValor(
                'SELECT 1 FROM school_users
                  WHERE school_id = ? AND user_id = ?
                    AND role IN ("teacher", "school_admin")',
                [$colegioId, $docenteId]
            );

            if ($nombre === '') {
                mensaje('error', 'El curso necesita un nombre.');
                return $volver;
            }

            if (!$suyo) {
                mensaje('error', 'Ese docente no es de esta institución.');
                return $volver;
            }

            $nuevo = crearCurso(
                $docenteId,
                $nombre,
                trim(post('curso_grado')),
                (int) post('curso_anio') ?: (int) date('Y')
            );

            mensaje('ok', 'Curso «' . $nombre . '» creado. '
                        . 'Ya se pueden matricular estudiantes y asignar actividades.');

            return 'escuela/curso.php?id=' . $nuevo;
    }

    return null;
}

/**
 * ¿Puede un administrador de organización tocar a este miembro?
 *
 * No, si es administrador de la plataforma o administrador de la propia
 * organización. Lo primero es evidente; lo segundo evita el juego entre
 * iguales: dos coordinadores echándose el uno al otro, o generándose
 * mutuamente contraseñas para entrar en la cuenta del compañero.
 *
 * El rol `school_admin` lo reparte la plataforma, así que también es la
 * plataforma quien lo retira.
 */
function puedeTocarMiembro(int $colegioId, int $usuarioId): bool
{
    $m = traerUno(
        'SELECT u.role AS rol_plataforma, su.role AS rol_colegio
           FROM school_users su
           JOIN users u ON u.id = su.user_id
          WHERE su.school_id = ? AND su.user_id = ?',
        [$colegioId, $usuarioId]
    );

    if (!$m) {
        return false;
    }

    return $m['rol_plataforma'] !== 'admin' && $m['rol_colegio'] !== 'school_admin';
}
