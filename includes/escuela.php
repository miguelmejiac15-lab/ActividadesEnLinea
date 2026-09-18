<?php
/**
 * escuela.php — Cursos, estudiantes, asignaciones y progreso
 *
 * El área Escuela responde a una pregunta que el resto de la plataforma no
 * se hace: **¿cómo va cada niño?** Todo lo demás está pensado para un niño
 * jugando solo; esto está pensado para un docente con treinta.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA DE ORO: UN DOCENTE SOLO VE LO SUYO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Aquí se manejan datos de progreso de menores identificados con nombre y
 * apellido. Cualquier consulta que devuelva algo de un estudiante pasa
 * ANTES por `exigirCursoPropio()`, que comprueba que el curso pertenezca a
 * quien pregunta.
 *
 * Nunca se filtra por el id que venga en la URL sin más: `?curso=8` es un
 * dato del navegador, no una autorización. El patrón es siempre
 * «cárgalo comprobando el dueño», no «cárgalo y luego mira».
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ EL PROGRESO NO SE GUARDA AQUÍ
 * ─────────────────────────────────────────────────────────────────────
 *
 * No hay tabla de «progreso del curso». Todo sale de `activity_progress`,
 * que ya existe porque es donde el motor escribe cuando un niño termina
 * una estación. Un contador aparte tendría que mantenerse sincronizado y
 * se desincronizaría el día que algo fallara a mitad — y entonces el
 * informe mentiría sin que nadie lo notara.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  PROGRESO DE CLASE Y PROGRESO DE CASA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Desde la fase 11, `activity_progress.course_id` **sí se escribe**:
 * 0 para lo que el niño hace por su cuenta y el id del curso para lo que
 * hace como trabajo de clase. Lo decide el servidor en `cursoDeTrabajo()`.
 *
 * Estas consultas devuelven las DOS cifras por separado:
 *
 *   · `hechas`   estaciones terminadas PARA ESTE CURSO
 *   · `de_casa`  estaciones de esas mismas actividades que ya había
 *                terminado por su cuenta
 *
 * Y esa segunda cifra se conserva a propósito. Un niño que jugó la
 * actividad el mes pasado en su casa la sabe igual; si el docente solo
 * viera un cero, mandaría a repetir trabajo a quien ya lo domina. Lo que
 * pedía el proyecto era no MEZCLARLAS, no esconder una de las dos.
 *
 * La pertenencia sigue mandando: estudiantes del curso
 * (`course_students`) × actividades asignadas (`course_activities`).
 */

declare(strict_types=1);


// =====================================================================
//  PUERTA DE ENTRADA
// =====================================================================

/**
 * ¿Quién puede entrar al área Escuela?
 *
 * El administrador siempre. Los demás, si su plan gestiona cursos, o si
 * tienen rol de docente o de administrador de colegio — un profesor de un
 * colegio con licencia entra por su rol, no por una suscripción propia.
 */
function puedeEntrarAEscuela(): bool
{
    if (esAdmin()) {
        return true;
    }
    if (tieneRol('teacher', 'school_admin')) {
        return true;
    }
    return puedeGestionarCursos();
}

/** Corta la ejecución si el visitante no tiene por qué estar aquí. */
function exigirEscuela(): void
{
    exigirSesion();

    if (!puedeEntrarAEscuela()) {
        mensaje('info', 'El área Escuela hace parte del plan Escuela.');
        redirigir('planes/');
    }
}

/**
 * Carga un curso COMPROBANDO que sea de quien pregunta.
 *
 * Devuelve el curso o corta la ejecución. No existe una versión que
 * devuelva null y deje al llamador decidir: eso es justo el descuido que
 * termina enseñando el curso de otro colegio.
 *
 * «De quien pregunta» son tres cosas distintas, y ninguna es «el id que
 * venía en la URL»:
 *
 *   · un **docente**, sus cursos;
 *   · un **administrador de institución**, los de SU institución —es lo
 *     que le deja usar estas mismas pantallas sin duplicarlas, ver
 *     `includes/institucion.php`—;
 *   · el **administrador de la plataforma**, cualquiera, para soporte.
 */
function exigirCursoPropio(int $cursoId): array
{
    $usuarioId = usuarioActualId();

    $sql = 'SELECT c.*, u.name AS docente
              FROM courses c
         LEFT JOIN users u ON u.id = c.teacher_id
             WHERE c.id = ?';
    $params = [$cursoId];

    // El administrador puede abrir cualquiera, para dar soporte.
    if (!esAdmin()) {
        $institucion = function_exists('institucionDeAdmin')
            ? institucionDeAdmin($usuarioId)
            : null;

        if ($institucion) {
            /*
             * El colegio se compara contra el que salió de
             * `school_users`, NUNCA contra nada que venga de la
             * petición. Un `?colegio=` en la URL aquí sería la puerta a
             * los cursos de cualquier otra institución.
             */
            $sql .= ' AND (c.teacher_id = ? OR c.school_id = ?)';
            $params[] = $usuarioId;
            $params[] = (int) $institucion['id'];
        } else {
            $sql .= ' AND c.teacher_id = ?';
            $params[] = $usuarioId;
        }
    }

    $curso = traerUno($sql, $params);

    if (!$curso) {
        // Mismo mensaje exista o no: decir «ese curso no es tuyo» le
        // confirma a quien prueba números que el curso 8 existe.
        mensaje('error', 'Ese curso no existe o no es tuyo.');
        redirigir('escuela/');
    }

    return $curso;
}


// =====================================================================
//  CURSOS
// =====================================================================

/** Los cursos de un docente, con sus conteos. */
function cursosDelDocente(int $docenteId): array
{
    return traerTodo(
        'SELECT c.*,
                (SELECT COUNT(*) FROM course_students cs WHERE cs.course_id = c.id) AS estudiantes,
                (SELECT COUNT(*) FROM course_activities ca WHERE ca.course_id = c.id) AS actividades
           FROM courses c
          WHERE c.teacher_id = ?
       ORDER BY c.status = "archived", c.year DESC, c.name',
        [$docenteId]
    );
}

/**
 * Crea un curso y devuelve su id.
 *
 * `school_id` sale del colegio del docente, si tiene alguno. Un docente
 * independiente —una profesora particular, alguien que enseña a cinco
 * niños en casa— no pertenece a ninguno, y ahí va NULL: es justo cómo se
 * escribe «este curso no es de ningún colegio». Poner un 0 sería inventar
 * un colegio que no existe, y de hecho la clave foránea lo rechaza. Ver
 * `database/migracion-escuela.php`.
 *
 * Atarlo aquí y no dejarlo para después importa: sin el `school_id`, el
 * curso no sale en la ficha del colegio ni en su puerta, y el docente no
 * tiene forma de arreglarlo desde ninguna pantalla.
 */
function crearCurso(int $docenteId, string $nombre, string $grado, ?int $anio): int
{
    $colegioId = null;

    if (function_exists('colegiosInstalados') && colegiosInstalados()) {
        $colegio = colegioDeUsuario($docenteId);

        if ($colegio) {
            $colegioId = (int) $colegio['id'];
        }
    }

    return (int) insertar(
        'INSERT INTO courses (school_id, teacher_id, name, grade, year, status)
         VALUES (?, ?, ?, ?, ?, "active")',
        [$colegioId, $docenteId, $nombre, $grado !== '' ? $grado : null, $anio ?: null]
    );
}


// =====================================================================
//  ESTUDIANTES
// =====================================================================

/**
 * Estudiantes de un curso, con su progreso ya resumido.
 *
 * El resumen sale de una sola consulta con agregados. Traer los alumnos y
 * después pedir el progreso de cada uno serían treinta consultas por
 * pantalla, y la lista es lo primero que ve el docente cada mañana.
 */
function estudiantesDelCurso(int $cursoId): array
{
    /*
     * Cuántas estaciones hay que hacer en este curso. Es el denominador
     * de la barra de progreso, y es el mismo para todos: lo que el
     * docente asignó.
     *
     * Va en su propia consulta y no dentro de la de abajo porque ahí
     * habría que multiplicarla por cada alumno y por cada fila de
     * progreso; el número saldría inflado y la barra mentiría.
     */
    $total = (int) traerValor(
        'SELECT COUNT(*)
           FROM course_activities ca
           JOIN activity_stations s ON s.activity_id = ca.activity_id
          WHERE ca.course_id = ?',
        [$cursoId]
    );

    $alumnos = traerTodo(
        'SELECT u.id, u.name, u.email, u.avatar, cs.enrolled_at,
                COUNT(DISTINCT CASE WHEN p.status = "completed" AND p.course_id = cs.course_id
                                    THEN p.station_id END) AS estaciones_hechas,
                COUNT(DISTINCT CASE WHEN p.status = "completed" AND p.course_id = 0
                                    THEN p.station_id END) AS estaciones_de_casa,
                COALESCE(SUM(CASE WHEN p.course_id = cs.course_id THEN p.stars END), 0) AS estrellas,
                COALESCE(SUM(CASE WHEN p.course_id = cs.course_id THEN p.coins END), 0) AS monedas,
                MAX(CASE WHEN p.course_id = cs.course_id THEN p.updated_at END) AS ultima_vez
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
      LEFT JOIN course_activities ca ON ca.course_id = cs.course_id
      LEFT JOIN activity_progress p  ON p.user_id = u.id
                                    AND p.activity_id = ca.activity_id
          WHERE cs.course_id = ?
       GROUP BY u.id
       ORDER BY u.name',
        [$cursoId]
    );

    foreach ($alumnos as &$a) {
        $hechas = min((int) $a['estaciones_hechas'], $total);

        $a['estaciones_totales'] = $total;
        $a['porcentaje'] = $total > 0 ? (int) round($hechas * 100 / $total) : 0;
    }
    unset($a);

    return $alumnos;
}

/**
 * Los cursos DE LOS QUE se puede traer gente a este.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EL CASO QUE RESUELVE: EL PASO DE AÑO
 * ─────────────────────────────────────────────────────────────────────
 *
 * En diciembre el grupo entero de primero pasa a segundo. Con «traer de
 * mis otros cursos» había que marcar treinta casillas una a una; aquí se
 * elige el curso de origen y vienen todos de una vez.
 *
 * Se devuelve cuántos hay y **cuántos faltan por traer**: un curso del
 * que ya se importó todo no tiene nada que ofrecer, y ofrecerlo igual
 * hace pulsar un botón que no va a hacer nada.
 *
 * Solo cursos del MISMO docente —o de su institución si la administra—,
 * que es el mismo alcance que `exigirCursoPropio()`. Poder importar de
 * cualquier curso de la plataforma sería un directorio de menores con
 * otro nombre.
 */
function cursosParaImportar(int $cursoId, int $docenteId): array
{
    $sql = 'SELECT c.id, c.name, c.grade, c.year, c.status,
                   (SELECT COUNT(*) FROM course_students cs
                     WHERE cs.course_id = c.id) AS estudiantes,
                   (SELECT COUNT(*) FROM course_students cs
                     WHERE cs.course_id = c.id
                       AND cs.user_id NOT IN (
                           SELECT user_id FROM course_students WHERE course_id = ?
                       )) AS nuevos
              FROM courses c
             WHERE c.id <> ?';
    $params = [$cursoId, $cursoId];

    $institucion = function_exists('institucionDeAdmin')
        ? institucionDeAdmin($docenteId)
        : null;

    if (esAdmin()) {
        // El administrador da soporte: ve los del mismo colegio que este
        // curso, no todos los de la plataforma.
        $sql .= ' AND (c.school_id = (SELECT school_id FROM courses WHERE id = ?)
                       OR c.teacher_id = (SELECT teacher_id FROM courses WHERE id = ?))';
        $params[] = $cursoId;
        $params[] = $cursoId;

    } elseif ($institucion) {
        $sql .= ' AND (c.teacher_id = ? OR c.school_id = ?)';
        $params[] = $docenteId;
        $params[] = (int) $institucion['id'];

    } else {
        $sql .= ' AND c.teacher_id = ?';
        $params[] = $docenteId;
    }

    // Los archivados también: el curso del año pasado suele estarlo, y es
    // justo de donde hay que traer a los niños.
    $sql .= ' ORDER BY c.status = "archived", c.year DESC, c.name';

    return array_values(array_filter(
        traerTodo($sql, $params),
        static fn(array $c): bool => (int) $c['estudiantes'] > 0
    ));
}

/**
 * Trae a este curso los estudiantes de otro.
 *
 * Devuelve cuántos entraron y cuántos ya estaban. No borra a nadie del
 * curso de origen: pasar de primero a segundo no es mudarse, y el
 * historial del año anterior tiene que seguir en su sitio para que el
 * informe de ese curso siga diciendo la verdad.
 *
 * @return array{ok:bool, error:?string, nuevos:int, repetidos:int}
 */
function importarEstudiantes(int $cursoDestino, int $cursoOrigen, int $docenteId): array
{
    $vacio = ['ok' => false, 'nuevos' => 0, 'repetidos' => 0];

    if ($cursoOrigen === $cursoDestino) {
        return $vacio + ['error' => 'Ese es el mismo curso.'];
    }

    /*
     * El curso de origen tiene que estar entre los que este docente
     * puede ver. El id viene de un formulario, y sin esta comprobación
     * cambiar un número traería a la clase los niños de cualquier otro
     * colegio.
     */
    $permitidos = array_map('intval', array_column(
        cursosParaImportar($cursoDestino, $docenteId), 'id'));

    if (!in_array($cursoOrigen, $permitidos, true)) {
        return $vacio + ['error' => 'Ese curso no es tuyo o ya no tiene estudiantes.'];
    }

    $alumnos = traerTodo(
        'SELECT user_id FROM course_students WHERE course_id = ?', [$cursoOrigen]);

    $nuevos = 0;
    $repetidos = 0;

    foreach ($alumnos as $a) {
        // `matricularEstudiante()` ya devuelve false si estaba, y de paso
        // le da la licencia del colegio si el curso es de uno. Repetir esa
        // lógica aquí sería la forma de que se separen.
        if (matricularEstudiante($cursoDestino, (int) $a['user_id'])) {
            $nuevos++;
        } else {
            $repetidos++;
        }
    }

    return ['ok' => true, 'error' => null, 'nuevos' => $nuevos, 'repetidos' => $repetidos];
}

/**
 * Busca cuentas para matricular.
 *
 * Se busca por correo EXACTO, no por nombre ni por coincidencia parcial.
 * Un buscador de personas por nombre dentro de una plataforma con datos de
 * menores es un directorio de menores: quien quisiera podría ir tecleando
 * letras y listar niños. Con el correo exacto hay que saber a quién se
 * busca de antemano, que es justo lo que pasa cuando un docente matricula
 * a su propio grupo.
 */
function buscarCuentaPorCorreo(string $correo): ?array
{
    $correo = correoValido($correo);

    if ($correo === null) {
        return null;
    }

    return traerUno(
        'SELECT id, name, email, role FROM users WHERE email = ? AND status = "active"',
        [$correo]
    );
}

/**
 * Los estudiantes que este docente ya tiene en ALGUNO de sus cursos.
 *
 * Resuelve el caso más común y más pesado: el mismo grupo de niños pasa
 * de «Matemáticas 3.º B» a «Lengua 3.º B», y sin esto habría que volver a
 * teclear treinta correos.
 *
 * No es un directorio de la plataforma: solo devuelve gente que este
 * docente ya matriculó él mismo. Buscar a cualquiera por nombre seguiría
 * siendo un listado de menores, y eso no se hace.
 */
function misEstudiantes(int $docenteId, int $excluirCurso = 0): array
{
    return traerTodo(
        'SELECT DISTINCT u.id, u.name, u.email, u.avatar,
                (SELECT COUNT(*) FROM course_students x
                   JOIN courses xc ON xc.id = x.course_id
                  WHERE x.user_id = u.id AND xc.teacher_id = ?) AS cursos
           FROM course_students cs
           JOIN courses c ON c.id = cs.course_id
           JOIN users   u ON u.id = cs.user_id
          WHERE c.teacher_id = ?
            AND u.status = "active"
            AND u.id NOT IN (SELECT user_id FROM course_students WHERE course_id = ?)
       ORDER BY u.name',
        [$docenteId, $docenteId, $excluirCurso]
    );
}


// =====================================================================
//  CREAR CUENTAS DE ESTUDIANTE
// =====================================================================

/**
 * Dominio de las cuentas que crea un docente.
 *
 * Los niños de primaria no suelen tener correo, pero el inicio de sesión
 * de la plataforma pide uno. Así que se fabrica: es un identificador con
 * forma de correo, no una dirección que reciba nada, y por eso el dominio
 * es claramente interno.
 */
function dominioDeAula(): string
{
    $d = trim((string) ajuste('escuela_dominio_aula', 'aula.local'));

    return $d !== '' ? $d : 'aula.local';
}

/**
 * Un identificador con forma de correo, corto y único.
 *
 * Corto a propósito: lo va a teclear un niño de siete años. El sufijo de
 * cuatro caracteres evita que dos «Ana Pérez» choquen sin obligar a
 * numerar a mano.
 */
function correoDeAula(string $nombre): string
{
    $base = slugificar($nombre);
    $base = preg_replace('/-+/', '', $base) ?: 'alumno';
    $base = mb_substr($base, 0, 14);

    if ($base === '') {
        $base = 'alumno';
    }

    for ($intento = 0; $intento < 12; $intento++) {
        $correo = $base . substr(bin2hex(random_bytes(3)), 0, 4) . '@' . dominioDeAula();

        if (!traerValor('SELECT id FROM users WHERE email = ?', [$correo])) {
            return $correo;
        }
    }

    return $base . bin2hex(random_bytes(5)) . '@' . dominioDeAula();
}

/**
 * Una contraseña que un niño pueda copiar de un papel.
 *
 * Dos palabras y un número. Una cadena aleatoria de doce caracteres es
 * más fuerte y a la vez peor: acaba escrita en la tapa del cuaderno
 * porque nadie la puede teclear bien. Estas cuentas las gestiona el
 * docente y se pueden restablecer en un clic, así que la fuerza importa
 * menos que poder entrar el primer día.
 */
function claveMemorable(): string
{
    $a = ['gato', 'perro', 'tigre', 'panda', 'lobo', 'zorro', 'buho', 'rana',
          'pez', 'oso', 'mono', 'pato', 'gallo', 'ciervo', 'foca'];
    $b = ['azul', 'verde', 'rojo', 'feliz', 'listo', 'veloz', 'grande', 'suave',
          'alegre', 'valiente', 'curioso', 'tranquilo'];

    return $a[random_int(0, count($a) - 1)] . '-'
         . $b[random_int(0, count($b) - 1)] . '-'
         . random_int(10, 99);
}

/**
 * Crea la cuenta de un estudiante y la matricula en el curso.
 *
 * Devuelve las credenciales EN CLARO una sola vez, para que el docente
 * las entregue. No se guardan en ningún sitio: si se pierden, se
 * restablecen, que es una operación de un clic.
 *
 * @return array{ok:bool, error:?string, usuario:?array, clave:?string}
 */
function crearCuentaEstudiante(int $cursoId, string $nombre, string $correo = '',
                               string $correoAcudiente = ''): array
{
    $nombre = trim(preg_replace('/\s+/u', ' ', $nombre) ?? '');

    if (mb_strlen($nombre) < 2) {
        return ['ok' => false, 'error' => 'Falta el nombre.', 'usuario' => null, 'clave' => null];
    }
    if (mb_strlen($nombre) > 120) {
        return ['ok' => false, 'error' => 'Ese nombre es demasiado largo.',
                'usuario' => null, 'clave' => null];
    }

    // Correo real si lo hay; si no, uno de aula.
    $correo = trim($correo);

    if ($correo !== '') {
        $valido = correoValido($correo);

        if ($valido === null) {
            return ['ok' => false, 'error' => "«$nombre»: ese correo no es válido.",
                    'usuario' => null, 'clave' => null];
        }
        if (traerValor('SELECT id FROM users WHERE email = ?', [$valido])) {
            return ['ok' => false, 'error' => "«$nombre»: ya hay una cuenta con ese correo. "
                                            . 'Búscala en vez de crear otra.',
                    'usuario' => null, 'clave' => null];
        }

        $correo = $valido;
    } else {
        $correo = correoDeAula($nombre);
    }

    $clave = claveMemorable();

    $acudiente = trim($correoAcudiente) !== '' ? correoValido($correoAcudiente) : null;

    $pdo    = db();
    $propia = !$pdo->inTransaction();

    if ($propia) {
        $pdo->beginTransaction();
    }

    try {
        $id = (int) insertar(
            'INSERT INTO users (name, email, password, role, status, guardian_email)
             VALUES (?, ?, ?, "student", "active", ?)',
            [$nombre, $correo, password_hash($clave, PASSWORD_DEFAULT), $acudiente]
        );

        ejecutar('INSERT INTO course_students (course_id, user_id) VALUES (?, ?)',
                 [$cursoId, $id]);

        // Si el curso es de una organización con licencia, el estudiante
        // la recibe: si no, se encontraría media actividad bloqueada.
        darLicenciaDelCurso($cursoId, $id);

        // Las mismas monedas y logros que recibe quien se registra solo:
        // una cuenta creada por el docente no empieza en desventaja.
        if (function_exists('entregarBienvenida')) {
            entregarBienvenida($id);
        }

        if ($propia) {
            $pdo->commit();
        }

    } catch (Throwable $e) {
        if ($propia && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[escuela] crearCuentaEstudiante: ' . $e->getMessage());

        return ['ok' => false, 'error' => "«$nombre»: no se pudo crear la cuenta.",
                'usuario' => null, 'clave' => null];
    }

    return [
        'ok'      => true,
        'error'   => null,
        'clave'   => $clave,
        'usuario' => ['id' => $id, 'name' => $nombre, 'email' => $correo],
    ];
}

/**
 * Restablece la contraseña de un estudiante DEL CURSO.
 *
 * Solo funciona sobre alguien matriculado en el curso que se pasa, y ese
 * curso ya vino comprobado por `exigirCursoPropio()`. Un docente no puede
 * cambiarle la contraseña a cualquiera: solo a quien tiene en clase.
 *
 * Y solo a cuentas de estudiante. Si el niño comparte cuenta con una
 * familia que paga —rol `user`— cambiarle la clave le cerraría el acceso
 * a un adulto que no tiene nada que ver con este curso.
 *
 * @return array{ok:bool, error:?string, clave:?string}
 */
function restablecerClaveDeEstudiante(int $cursoId, int $usuarioId): array
{
    $alumno = traerUno(
        'SELECT u.id, u.name, u.role
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
          WHERE cs.course_id = ? AND cs.user_id = ?',
        [$cursoId, $usuarioId]
    );

    if (!$alumno) {
        return ['ok' => false, 'error' => 'Ese estudiante no está en este curso.', 'clave' => null];
    }

    if ($alumno['role'] !== 'student') {
        return ['ok' => false, 'clave' => null,
                'error' => 'Esa cuenta no la creaste tú: es una cuenta familiar. '
                         . 'Su contraseña la cambia quien la usa, desde «¿olvidaste tu contraseña?».'];
    }

    $clave = claveMemorable();

    ejecutar('UPDATE users SET password = ? WHERE id = ?',
             [password_hash($clave, PASSWORD_DEFAULT), $usuarioId]);

    return ['ok' => true, 'error' => null, 'clave' => $clave];
}

/**
 * Matricula a alguien. Devuelve false si ya estaba.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  MATRICULAR EN UN CURSO DE COLEGIO DA LA LICENCIA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Si el curso pertenece a una organización con licencia viva, el
 * estudiante entra también en la organización y recibe el acceso.
 *
 * Sin esto, el flujo institucional no funciona de verdad: el docente
 * asigna una actividad, el niño la abre y se encuentra la mitad de las
 * estaciones bloqueadas porque la plataforma lo trata como una familia
 * sin plan. La licencia la paga (o la prueba) el colegio precisamente
 * para que sus estudiantes puedan trabajar.
 */
function matricularEstudiante(int $cursoId, int $usuarioId): bool
{
    $ya = traerValor(
        'SELECT COUNT(1) FROM course_students WHERE course_id = ? AND user_id = ?',
        [$cursoId, $usuarioId]
    );

    if ((int) $ya > 0) {
        return false;
    }

    ejecutar(
        'INSERT INTO course_students (course_id, user_id) VALUES (?, ?)',
        [$cursoId, $usuarioId]
    );

    darLicenciaDelCurso($cursoId, $usuarioId);

    return true;
}

/**
 * Si el curso es de una organización con licencia, vincula al estudiante.
 *
 * Se llama al matricular. Aparte para que `crearCuentaEstudiante()` —que
 * inserta la matrícula dentro de su propia transacción— pueda usarla
 * igual sin duplicar la regla.
 */
function darLicenciaDelCurso(int $cursoId, int $usuarioId): void
{
    if (!function_exists('colegiosInstalados') || !colegiosInstalados()) {
        return;
    }

    $colegioId = (int) traerValor('SELECT school_id FROM courses WHERE id = ?', [$cursoId]);

    if ($colegioId <= 0) {
        return;   // curso de un docente independiente
    }

    $colegio = colegioPorId($colegioId);

    if (!$colegio || !licenciaVigente($colegio)) {
        return;
    }

    vincularAColegio($colegioId, $usuarioId, 'student');
}

/**
 * Saca a un estudiante del curso.
 *
 * Su progreso NO se borra: se le quita el vínculo con el curso y nada más.
 * Borrar lo que un niño hizo porque cambió de grupo sería destruir su
 * trabajo, y además el histórico del curso dejaría de cuadrar.
 */
function retirarEstudiante(int $cursoId, int $usuarioId): void
{
    ejecutar(
        'DELETE FROM course_students WHERE course_id = ? AND user_id = ?',
        [$cursoId, $usuarioId]
    );
}


// =====================================================================
//  ACTIVIDADES ASIGNADAS
// =====================================================================

/** Actividades asignadas a un curso, con cuántos las terminaron. */
function actividadesDelCurso(int $cursoId): array
{
    return traerTodo(
        'SELECT ca.id AS asignacion_id, ca.due_date, ca.sort_order, ca.assigned_at,
                a.id, a.slug, a.title, a.icon, a.duration_minutes,
                c.name AS categoria, c.icon AS categoria_icon,
                (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones,
                (SELECT COUNT(DISTINCT p.user_id)
                   FROM activity_progress p
                   JOIN course_students cs ON cs.user_id = p.user_id
                                          AND cs.course_id = ca.course_id
                  WHERE p.activity_id = a.id
                    AND p.status = "completed"
                    AND p.course_id = ca.course_id) AS empezaron,

                /* Los que ya la habían hecho por su cuenta antes de que
                   se la mandaran: no es trabajo de clase, pero saberlo
                   evita mandarles a repetir algo que ya dominan. */
                (SELECT COUNT(DISTINCT p.user_id)
                   FROM activity_progress p
                   JOIN course_students cs ON cs.user_id = p.user_id
                                          AND cs.course_id = ca.course_id
                  WHERE p.activity_id = a.id
                    AND p.status = "completed"
                    AND p.course_id = 0) AS de_casa
           FROM course_activities ca
           JOIN activities a ON a.id = ca.activity_id
      LEFT JOIN categories c ON c.id = a.category_id
          WHERE ca.course_id = ?
       ORDER BY ca.sort_order, ca.assigned_at',
        [$cursoId]
    );
}

/** Asigna una actividad. Devuelve false si ya estaba asignada. */
function asignarActividad(int $cursoId, int $actividadId, ?string $fechaLimite): bool
{
    $ya = traerValor(
        'SELECT COUNT(1) FROM course_activities WHERE course_id = ? AND activity_id = ?',
        [$cursoId, $actividadId]
    );

    if ((int) $ya > 0) {
        return false;
    }

    $siguiente = (int) traerValor(
        'SELECT COALESCE(MAX(sort_order), 0) + 1 FROM course_activities WHERE course_id = ?',
        [$cursoId]
    );

    ejecutar(
        'INSERT INTO course_activities (course_id, activity_id, sort_order, due_date)
         VALUES (?, ?, ?, ?)',
        [$cursoId, $actividadId, $siguiente, $fechaLimite ?: null]
    );

    return true;
}

/**
 * Asigna varias actividades de una vez.
 *
 * Es lo que convierte «asignar un bloque entero» en un clic en vez de
 * quince. Devuelve cuántas entraron y cuántas ya estaban, porque las dos
 * cifras importan: «asigné 12» y «ya tenías 3» son mensajes distintos y
 * el segundo evita que alguien piense que algo falló.
 *
 * @return array{nuevas:int, repetidas:int, invalidas:int}
 */
function asignarVarias(int $cursoId, array $actividadIds, ?string $fechaLimite = null): array
{
    $ids = array_values(array_unique(array_filter(array_map('intval', $actividadIds))));

    if (!$ids) {
        return ['nuevas' => 0, 'repetidas' => 0, 'invalidas' => 0];
    }

    /*
     * Se filtra contra la base ANTES de insertar: los ids llegan de un
     * formulario y un formulario se edita. Solo entran actividades que
     * existen y están publicadas — asignar un borrador manda al curso
     * entero a una página vacía.
     */
    $marcas = implode(',', array_fill(0, count($ids), '?'));

    $validos = array_map('intval', array_column(
        traerTodo("SELECT id FROM activities
                    WHERE id IN ($marcas) AND status = 'published'", $ids),
        'id'
    ));

    $ya = array_map('intval', array_column(
        traerTodo('SELECT activity_id FROM course_activities WHERE course_id = ?', [$cursoId]),
        'activity_id'
    ));

    $nuevas = array_values(array_diff($validos, $ya));

    $orden = (int) traerValor(
        'SELECT COALESCE(MAX(sort_order), 0) FROM course_activities WHERE course_id = ?',
        [$cursoId]
    );

    foreach ($nuevas as $actividadId) {
        $orden++;
        ejecutar(
            'INSERT INTO course_activities (course_id, activity_id, sort_order, due_date)
             VALUES (?, ?, ?, ?)',
            [$cursoId, $actividadId, $orden, $fechaLimite ?: null]
        );
    }

    return [
        'nuevas'    => count($nuevas),
        'repetidas' => count(array_intersect($validos, $ya)),
        'invalidas' => count($ids) - count($validos),
    ];
}

/** Ids de las actividades publicadas de un bloque. */
function idsDeBloque(int $bloqueId): array
{
    return array_map('intval', array_column(
        // Ojo: `activities` NO tiene `sort_order` —el orden dentro de un
        // bloque es el de creación—, así que se ordena por id.
        traerTodo('SELECT id FROM activities
                    WHERE collection_id = ? AND status = "published"
                 ORDER BY id', [$bloqueId]),
        'id'
    ));
}

/**
 * Ids de las actividades publicadas de una categoría, opcionalmente de un
 * nivel concreto.
 *
 * Sirve para «todo Matemática de tercero y cuarto», que es como piensa un
 * docente al montar el curso: por materia y por edad, no actividad a
 * actividad.
 */
function idsDeCategoriaNivel(string $categoriaSlug, string $nivelSlug = ''): array
{
    $sql = 'SELECT a.id
              FROM activities a
              JOIN categories c ON c.id = a.category_id
         LEFT JOIN levels     l ON l.id = a.level_id
             WHERE a.status = "published" AND c.slug = ?';
    $params = [$categoriaSlug];

    if ($nivelSlug !== '') {
        $sql .= ' AND l.slug = ?';
        $params[] = $nivelSlug;
    }

    $sql .= ' ORDER BY l.sort_order, a.id';

    return array_map('intval', array_column(traerTodo($sql, $params), 'id'));
}

/** Quita varias asignaciones. El progreso ya hecho se conserva. */
function quitarVarias(int $cursoId, array $actividadIds): int
{
    $ids = array_values(array_unique(array_filter(array_map('intval', $actividadIds))));

    if (!$ids) {
        return 0;
    }

    $marcas = implode(',', array_fill(0, count($ids), '?'));

    return ejecutar(
        "DELETE FROM course_activities WHERE course_id = ? AND activity_id IN ($marcas)",
        array_merge([$cursoId], $ids)
    );
}

/** Quita una asignación. El progreso ya hecho se conserva. */
function quitarAsignacion(int $cursoId, int $actividadId): void
{
    ejecutar(
        'DELETE FROM course_activities WHERE course_id = ? AND activity_id = ?',
        [$cursoId, $actividadId]
    );
}


// =====================================================================
//  CONTEXTO: ¿ESTO ES TRABAJO DE CLASE O SUYO?
// =====================================================================

/**
 * Para qué curso cuenta lo que este usuario está jugando ahora.
 *
 * Devuelve el id del curso, o **0 si es progreso personal**.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA, EN UNA FRASE
 * ─────────────────────────────────────────────────────────────────────
 *
 * **Si la actividad está asignada a un curso donde el niño está
 * matriculado, cuenta para ese curso. Si no, es suya.**
 *
 * Es la línea que entienden las dos partes sin explicársela: el docente
 * espera ver lo que mandó —lo haga el niño en clase o en casa el
 * domingo—, y la familia espera que lo que el niño elige por su cuenta
 * no acabe en un informe escolar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ LO DECIDE EL SERVIDOR Y NO EL NAVEGADOR
 * ─────────────────────────────────────────────────────────────────────
 *
 * No se acepta un «curso» que venga en la petición de guardado. Si el
 * cliente pudiera decir para qué curso cuenta lo que hace, cualquiera
 * podría rellenar el informe de otro curso, o vaciar el suyo.
 *
 * El único dato de sesión que se usa es `aula_curso`, que lo pone el
 * propio servidor cuando el niño entra por la lista de su clase, y aun
 * así se vuelve a comprobar aquí contra la matrícula y la asignación.
 */
function cursoDeTrabajo(int $usuarioId, int $actividadId): int
{
    if ($usuarioId <= 0 || $actividadId <= 0) {
        return 0;
    }

    // Cursos DEL USUARIO donde esta actividad está asignada.
    $candidatos = traerTodo(
        'SELECT c.id
           FROM course_students cs
           JOIN courses c            ON c.id = cs.course_id AND c.status = "active"
           JOIN course_activities ca ON ca.course_id = c.id AND ca.activity_id = ?
          WHERE cs.user_id = ?
       ORDER BY ca.assigned_at DESC, c.id DESC',
        [$actividadId, $usuarioId]
    );

    if (!$candidatos) {
        return 0;
    }

    $ids = array_map('intval', array_column($candidatos, 'id'));

    /*
     * Si entró por la lista de su clase, gana ese curso. Es el caso
     * inequívoco: está en clase, con su profe delante.
     */
    $deAula = (int) ($_SESSION['aula_curso'] ?? 0);

    if ($deAula > 0 && in_array($deAula, $ids, true)) {
        return $deAula;
    }

    /*
     * Si no, el curso que la asignó más recientemente. Con la actividad
     * en dos cursos suyos hay que elegir uno, y el último en mandarla es
     * el que está trabajando eso ahora.
     */
    return $ids[0];
}


// =====================================================================
//  PROGRESO
// =====================================================================

/**
 * Rejilla de progreso: una fila por estudiante, una columna por actividad.
 *
 * Es la pantalla que de verdad usa un docente, y por eso se arma con DOS
 * consultas y no con una por celda. Con 30 estudiantes y 10 actividades,
 * una consulta por celda serían 300 consultas para pintar una tabla.
 *
 * @return array{estudiantes:array, actividades:array, celdas:array}
 */
function rejillaDeProgreso(int $cursoId): array
{
    $estudiantes = estudiantesDelCurso($cursoId);
    $actividades = actividadesDelCurso($cursoId);

    if (!$estudiantes || !$actividades) {
        return ['estudiantes' => $estudiantes, 'actividades' => $actividades, 'celdas' => []];
    }

    // Progreso de todos los estudiantes en todas las actividades del curso,
    // de una sola vez.
    $filas = traerTodo(
        'SELECT p.user_id, p.activity_id,
                COUNT(DISTINCT CASE WHEN p.status = "completed" AND p.course_id = cs.course_id
                                    THEN p.station_id END) AS hechas,
                COUNT(DISTINCT CASE WHEN p.status = "completed" AND p.course_id = 0
                                    THEN p.station_id END) AS de_casa,
                COALESCE(SUM(CASE WHEN p.course_id = cs.course_id THEN p.stars END), 0) AS estrellas,
                MAX(CASE WHEN p.course_id = cs.course_id THEN p.updated_at END) AS ultima
           FROM activity_progress p
           JOIN course_students   cs ON cs.user_id     = p.user_id     AND cs.course_id = ?
           JOIN course_activities ca ON ca.activity_id = p.activity_id AND ca.course_id = cs.course_id
       GROUP BY p.user_id, p.activity_id',
        [$cursoId]
    );

    $celdas = [];
    foreach ($filas as $f) {
        $celdas[(int) $f['user_id']][(int) $f['activity_id']] = [
            'hechas'    => (int) $f['hechas'],
            'de_casa'   => (int) $f['de_casa'],
            'estrellas' => (int) $f['estrellas'],
            'ultima'    => $f['ultima'],
        ];
    }

    return [
        'estudiantes' => $estudiantes,
        'actividades' => $actividades,
        'celdas'      => $celdas,
    ];
}

/**
 * Detalle de un estudiante dentro de un curso: qué hizo, estación a estación.
 */
function detalleDeEstudiante(int $cursoId, int $usuarioId): array
{
    /*
     * `p` es lo que hizo PARA ESTE CURSO. `casa` es la misma estación
     * hecha por su cuenta.
     *
     * Las dos por separado y las dos visibles: si el docente solo viera
     * la primera, mandaría a repetir trabajo a un niño que ya se sabe la
     * actividad de haberla jugado en casa.
     */
    return traerTodo(
        'SELECT a.id AS actividad_id, a.slug, a.title, a.icon,
                s.id AS estacion_id, s.position, s.title AS estacion,
                p.status, p.stars, p.coins, p.attempts, p.time_spent_seconds,
                p.completed_at, p.updated_at,
                (casa.id IS NOT NULL AND casa.status = "completed") AS hecha_en_casa,
                (SELECT COUNT(*) FROM activity_stations x WHERE x.activity_id = a.id) AS total_estaciones
           FROM course_activities ca
           JOIN activities a ON a.id = ca.activity_id
      LEFT JOIN activity_stations s ON s.activity_id = a.id
      LEFT JOIN activity_progress p    ON p.station_id = s.id
                                     AND p.user_id = ? AND p.course_id = ?
      LEFT JOIN activity_progress casa ON casa.station_id = s.id
                                      AND casa.user_id = ? AND casa.course_id = 0
          WHERE ca.course_id = ?
       ORDER BY ca.sort_order, a.id, s.position',
        [$usuarioId, $cursoId, $usuarioId, $cursoId]
    );
}

/**
 * Comprueba que un estudiante esté de verdad en el curso.
 *
 * Sin esto, cambiar el número en `?estudiante=` mostraría el progreso de
 * cualquier niño de la plataforma. El curso ya se validó antes con
 * `exigirCursoPropio()`; esto cierra el segundo parámetro.
 */
function exigirEstudianteDelCurso(int $cursoId, int $usuarioId): array
{
    $alumno = traerUno(
        'SELECT u.id, u.name, u.email, u.avatar, u.birth_year
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
          WHERE cs.course_id = ? AND cs.user_id = ?',
        [$cursoId, $usuarioId]
    );

    if (!$alumno) {
        mensaje('error', 'Ese estudiante no está en este curso.');
        redirigir('escuela/curso.php?id=' . $cursoId);
    }

    return $alumno;
}

/** Resumen de un curso para la tarjeta y la cabecera. */
function resumenDelCurso(int $cursoId): array
{
    $r = traerUno(
        'SELECT
            (SELECT COUNT(*) FROM course_students   WHERE course_id = ?) AS estudiantes,
            (SELECT COUNT(*) FROM course_activities WHERE course_id = ?) AS actividades,
            /* «Activos» EN ESTE CURSO. Contar tambien lo que hicieron por
               su cuenta inflaría la cifra con niños que esa semana no
               tocaron nada de lo que mandó el docente, y ese número se
               mira justo para saber quién no está trabajando. */
            (SELECT COUNT(DISTINCT p.user_id)
               FROM activity_progress p
               JOIN course_students cs ON cs.user_id = p.user_id AND cs.course_id = ?
              WHERE p.course_id = cs.course_id
                AND p.updated_at > DATE_SUB(NOW(), INTERVAL 7 DAY))      AS activos_semana,
            /* Solo lo hecho PARA ESTE CURSO: es la cifra de la que
               responde el docente. Lo que hicieron en casa se ve en la
               rejilla, marcado aparte. */
            (SELECT COUNT(*)
               FROM activity_progress p
               JOIN course_students   cs ON cs.user_id     = p.user_id     AND cs.course_id = ?
               JOIN course_activities ca ON ca.activity_id = p.activity_id AND ca.course_id = cs.course_id
              WHERE p.status = "completed" AND p.course_id = cs.course_id) AS estaciones_hechas',
        [$cursoId, $cursoId, $cursoId, $cursoId]
    );

    return $r ?: ['estudiantes' => 0, 'actividades' => 0, 'activos_semana' => 0, 'estaciones_hechas' => 0];
}
