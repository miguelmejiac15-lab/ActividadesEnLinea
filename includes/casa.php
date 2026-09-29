<?php
/**
 * casa.php — Tareas para casa y resumen a la familia
 *
 * Lo pidió una docente: que el niño haga en casa lo que ella deja para la
 * casa, y que la familia sepa cómo va sin tener que aprender otra
 * plataforma.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE YA EXISTÍA Y NO HUBO QUE TOCAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * El niño ya podía entrar desde casa con el usuario y la contraseña de su
 * tarjeta, y lo que jugara de lo asignado ya contaba para su curso
 * (`cursoDeTrabajo()`). Faltaban dos cosas:
 *
 *   1. Distinguir, entre lo asignado, qué es para la casa. Sin eso el
 *      niño en casa veía la ruta de clase entera, y en una ruta en orden
 *      la tarea de casa podía estar detrás de un candado.
 *   2. Que la familia se enterara. El correo del acudiente ya se podía
 *      guardar y no se usaba para nada.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ UN CORREO Y NO UNA CUENTA PARA LOS PADRES
 * ─────────────────────────────────────────────────────────────────────
 *
 * Una cuenta de acudiente exige verificar que quien la abre es de verdad
 * el acudiente de ESE niño; si no, es una puerta al progreso de un menor
 * (Decreto 0769 de 2026). El correo llega a la dirección que registró la
 * docente, que es quien conoce a la familia. Es la forma más corta de
 * cumplir lo pedido sin abrir esa puerta.
 *
 * El envío lo decide la docente, con un botón. No hay envíos automáticos:
 * un correo que llega sin que nadie lo haya decidido es el primer paso
 * para que la familia lo marque como spam.
 */

declare(strict_types=1);

/** Horas mínimas entre dos resúmenes a la misma familia. */
const CASA_HORAS_ENTRE_RESUMENES = 20;


// =====================================================================
//  INSTALACIÓN
// =====================================================================

/** ¿Está aplicada la migración de tareas para casa? */
function casaInstalada(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "course_activities"
                AND COLUMN_NAME = "para_casa"'
        ) && (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.TABLES
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "guardian_reports"'
        );
    }

    return $listo;
}


// =====================================================================
//  MARCAR PARA CASA
// =====================================================================

/**
 * Marca o desmarca actividades del curso como tarea para casa.
 *
 * Solo toca asignaciones QUE YA SON de este curso: los ids llegan de un
 * formulario, y el `WHERE course_id = ?` es lo que impide que cambiar un
 * número marque algo de otro curso.
 *
 * Con fecha, la pone; sin fecha, conserva la que tuviera. Quitar de casa
 * no borra la fecha: sigue siendo la fecha de entrega de clase.
 *
 * @return int cuántas cambiaron
 */
function marcarParaCasa(int $cursoId, array $actividadIds, bool $casa, ?string $fecha = null): int
{
    if (!casaInstalada()) {
        return 0;
    }

    $ids = array_values(array_unique(array_filter(array_map('intval', $actividadIds))));

    if (!$ids) {
        return 0;
    }

    $fecha  = fechaValidaOnull($fecha);
    $marcas = implode(',', array_fill(0, count($ids), '?'));

    $sql    = 'UPDATE course_activities SET para_casa = ?';
    $params = [$casa ? 1 : 0];

    if ($casa && $fecha !== null) {
        $sql     .= ', due_date = ?';
        $params[] = $fecha;
    }

    $sql .= " WHERE course_id = ? AND activity_id IN ($marcas)";

    return ejecutar($sql, array_merge($params, [$cursoId], $ids));
}

/** Una fecha AAAA-MM-DD válida, o null. Un formulario puede mandar cualquier cosa. */
function fechaValidaOnull(?string $fecha): ?string
{
    $fecha = trim((string) $fecha);

    if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $fecha)) {
        return null;
    }

    [$a, $m, $d] = array_map('intval', explode('-', $fecha));

    return checkdate($m, $d, $a) ? $fecha : null;
}


// =====================================================================
//  LO QUE HAY PARA CASA
// =====================================================================

/**
 * Las tareas para casa de un niño en un curso, con cuánto lleva.
 *
 * El avance cuenta estaciones terminadas en cualquier contexto, igual que
 * la ruta: si ya la había hecho en clase o por su cuenta, está hecha.
 */
function tareasDeCasa(int $alumnoId, int $cursoId): array
{
    if (!casaInstalada()) {
        return [];
    }

    $filas = traerTodo(
        'SELECT a.id, a.slug, a.title, a.icon, ca.due_date,
                (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS total,
                (SELECT COUNT(DISTINCT p.station_id) FROM activity_progress p
                  WHERE p.user_id = ? AND p.activity_id = a.id
                    AND p.status = "completed") AS hechas
           FROM course_activities ca
           JOIN activities a ON a.id = ca.activity_id AND a.status = "published"
          WHERE ca.course_id = ? AND ca.para_casa = 1
       ORDER BY ca.due_date IS NULL, ca.due_date, ca.sort_order, ca.id',
        [$alumnoId, $cursoId]
    );

    foreach ($filas as &$f) {
        $f['total']    = (int) $f['total'];
        $f['hechas']   = min((int) $f['hechas'], $f['total']);
        $f['completa'] = $f['total'] > 0 && $f['hechas'] >= $f['total'];
    }
    unset($f);

    return $filas;
}

/** Lo que hizo para este curso en los últimos siete días. */
function semanaDelAlumno(int $alumnoId, int $cursoId): array
{
    $r = traerUno(
        'SELECT COUNT(DISTINCT station_id) AS estaciones, COALESCE(SUM(stars), 0) AS estrellas
           FROM activity_progress
          WHERE user_id = ? AND course_id = ? AND status = "completed"
            AND completed_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)',
        [$alumnoId, $cursoId]
    ) ?: [];

    return [
        'estaciones' => (int) ($r['estaciones'] ?? 0),
        'estrellas'  => (int) ($r['estrellas'] ?? 0),
    ];
}


// =====================================================================
//  EL CORREO DEL ACUDIENTE
// =====================================================================

/**
 * Guarda (o borra, con cadena vacía) el correo del acudiente de un niño.
 *
 * Solo en cuentas de estudiante. Una cuenta de familia (`user`) es de
 * sus padres: su correo de acudiente lo pusieron ellos al registrarse y
 * un docente no tiene por qué cambiarlo. Es la misma línea que traza
 * `restablecerClaveDeEstudiante()`.
 *
 * Quien llama ya comprobó que el curso es suyo y que el niño está en él.
 */
function guardarCorreoAcudiente(int $alumnoId, string $correo): array
{
    $rol = traerValor('SELECT role FROM users WHERE id = ?', [$alumnoId]);

    if ($rol !== 'student') {
        return ['ok' => false,
                'error' => 'Esta cuenta es de una familia: el correo del acudiente lo gestiona ella.'];
    }

    $correo = trim($correo);

    if ($correo === '') {
        ejecutar('UPDATE users SET guardian_email = NULL WHERE id = ?', [$alumnoId]);
        return ['ok' => true, 'error' => null];
    }

    $valido = correoValido($correo);

    if ($valido === null) {
        return ['ok' => false, 'error' => 'Ese correo no tiene un formato válido.'];
    }

    ejecutar('UPDATE users SET guardian_email = ? WHERE id = ?', [$valido, $alumnoId]);

    return ['ok' => true, 'error' => null];
}

/** Cuándo se le envió el último resumen a la familia de este niño, o null. */
function ultimoResumen(int $alumnoId, int $cursoId): ?string
{
    if (!casaInstalada()) {
        return null;
    }

    $f = traerValor(
        'SELECT MAX(created_at) FROM guardian_reports
          WHERE student_id = ? AND course_id = ? AND ok = 1',
        [$alumnoId, $cursoId]
    );

    return $f ?: null;
}

/** ¿Hace muy poco que se le escribió? */
function resumenReciente(int $alumnoId, int $cursoId): bool
{
    if (!casaInstalada()) {
        return false;
    }

    return (bool) traerValor(
        'SELECT COUNT(*) FROM guardian_reports
          WHERE student_id = ? AND course_id = ? AND ok = 1
            AND created_at >= DATE_SUB(NOW(), INTERVAL ? HOUR)',
        [$alumnoId, $cursoId, CASA_HORAS_ENTRE_RESUMENES]
    );
}


// =====================================================================
//  EL RESUMEN
// =====================================================================

/**
 * Arma y envía el resumen a la familia de un niño.
 *
 * @return array{ok:bool, motivo:string, error:?string}
 *         motivo: 'enviado' | 'sin_correo' | 'reciente' | 'fallo' | 'sin_instalar'
 */
function enviarResumenFamilia(array $curso, int $alumnoId): array
{
    if (!casaInstalada()) {
        return ['ok' => false, 'motivo' => 'sin_instalar', 'error' => 'Falta la migración de tareas para casa.'];
    }

    $cursoId = (int) $curso['id'];

    $alumno = traerUno(
        'SELECT u.id, u.name, u.email, u.role, u.guardian_email
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
          WHERE cs.course_id = ? AND cs.user_id = ? AND u.status = "active"',
        [$cursoId, $alumnoId]
    );

    // Solo cuentas de estudiante: una cuenta de familia ya la tienen los
    // padres en la mano, y el texto del correo («la tarjeta del colegio»)
    // no tendría sentido para ellos.
    if (!$alumno || $alumno['role'] !== 'student' || trim((string) $alumno['guardian_email']) === '') {
        return ['ok' => false, 'motivo' => 'sin_correo', 'error' => 'No tiene correo de acudiente.'];
    }

    if (resumenReciente($alumnoId, $cursoId)) {
        return ['ok' => false, 'motivo' => 'reciente',
                'error' => 'A esta familia ya se le escribió en las últimas '
                         . CASA_HORAS_ENTRE_RESUMENES . ' horas.'];
    }

    $docente = usuarioActual();
    $tareas  = tareasDeCasa($alumnoId, $cursoId);
    $semana  = semanaDelAlumno($alumnoId, $cursoId);
    $nombre  = (string) $alumno['name'];

    $asunto = 'Cómo va ' . $nombre . ' en ' . $curso['name'];
    $cuerpo = cuerpoDelResumen($alumno, $curso, $docente, $tareas, $semana);

    $r = enviarCorreo(
        (string) $alumno['guardian_email'],
        $asunto,
        correoPlantilla($asunto, $cuerpo, ['url' => url('login.php'), 'texto' => 'Entrar a Actividades en Línea'])
    );

    ejecutar(
        'INSERT INTO guardian_reports (course_id, student_id, sent_by, ok, error)
         VALUES (?, ?, ?, ?, ?)',
        [$cursoId, $alumnoId, $docente ? (int) $docente['id'] : null,
         $r['ok'] ? 1 : 0, $r['ok'] ? null : mb_substr((string) $r['error'], 0, 250)]
    );

    if (!$r['ok']) {
        error_log('[casa] no se pudo enviar el resumen del estudiante ' . $alumnoId . ': ' . $r['error']);
        return ['ok' => false, 'motivo' => 'fallo', 'error' => $r['error']];
    }

    return ['ok' => true, 'motivo' => 'enviado', 'error' => null];
}

/**
 * El cuerpo del correo.
 *
 * Escrito para una familia, no para un docente: sin «estaciones» ni
 * porcentajes de informe. Qué tiene que hacer en casa, qué ya hizo y cómo
 * entrar. Y la contraseña NUNCA va en el correo —en la base solo existe
 * cifrada, y aunque no fuera así, un correo se reenvía—.
 */
function cuerpoDelResumen(array $alumno, array $curso, ?array $docente, array $tareas, array $semana): string
{
    $nombre = e((string) $alumno['name']);
    $quien  = $docente ? e((string) $docente['name']) : 'su docente';

    $h  = '<p>Hola. Le escribimos de parte de <strong>' . $quien . '</strong>, docente de '
        . $nombre . ' en <strong>' . e((string) $curso['name']) . '</strong>.</p>';

    $pendientes = array_values(array_filter($tareas, static fn($t) => !$t['completa']));
    $hechas     = array_values(array_filter($tareas, static fn($t) => $t['completa']));

    $h .= '<h2 style="font-size:1.05rem;color:#2f3b52;margin:22px 0 8px">🏠 Para hacer en casa</h2>';

    if (!$tareas) {
        $h .= '<p>Por ahora no hay tareas para la casa.</p>';
    } else {
        $h .= '<ul style="padding-left:18px;margin:0">';

        foreach ($pendientes as $t) {
            $h .= '<li style="margin-bottom:6px">' . e(trim(($t['icon'] ?? '') . ' ' . $t['title']))
                . ($t['due_date'] ? ' · <strong>para el ' . e(fechaLarga($t['due_date'])) . '</strong>' : '')
                . ($t['hechas'] > 0 ? ' <span style="color:#8b95a5">(lleva ' . $t['hechas']
                    . ' de ' . $t['total'] . ' partes)</span>' : '')
                . '</li>';
        }

        foreach ($hechas as $t) {
            $h .= '<li style="margin-bottom:6px;color:#4caf50">✓ '
                . e(trim(($t['icon'] ?? '') . ' ' . $t['title'])) . ' · terminada</li>';
        }

        $h .= '</ul>';

        if (!$pendientes) {
            $h .= '<p style="margin-top:10px"><strong>¡Hizo todas las tareas de casa!</strong></p>';
        }
    }

    $h .= '<h2 style="font-size:1.05rem;color:#2f3b52;margin:22px 0 8px">⭐ Esta semana</h2>';

    if ($semana['estaciones'] > 0) {
        $h .= '<p>En los últimos siete días completó <strong>' . $semana['estaciones']
            . '</strong> ' . ($semana['estaciones'] === 1 ? 'ejercicio' : 'ejercicios')
            . ' de su curso y ganó <strong>' . $semana['estrellas'] . ' ⭐</strong>.</p>';
    } else {
        $h .= '<p>En los últimos siete días no ha jugado actividades de su curso.</p>';
    }

    $h .= '<h2 style="font-size:1.05rem;color:#2f3b52;margin:22px 0 8px">💻 Cómo entrar desde casa</h2>'
        . '<p>Desde un computador, tableta o celular, entre con el usuario <strong>'
        . e((string) $alumno['email']) . '</strong> y la contraseña de la tarjeta que se '
        . 'entregó en el colegio. Si se perdió, pídale una nueva a su docente.</p>'
        . '<p>Al entrar, las tareas de casa aparecen arriba, en <em>Para hacer en casa</em>. '
        . 'Lo que haga se guarda y su docente lo ve.</p>';

    $h .= '<p style="font-size:.85rem;color:#8b95a5;margin-top:22px">Recibe este correo porque '
        . 'su docente registró esta dirección como la del acudiente de ' . $nombre
        . '. Si no es correcto, avísele para que la corrija.</p>';

    return $h;
}

/**
 * Envía el resumen a todas las familias del curso que tengan correo.
 *
 * @return array{enviados:int, sin_correo:int, recientes:int, fallidos:int, error:?string}
 */
function enviarResumenesDelCurso(array $curso): array
{
    $res = ['enviados' => 0, 'sin_correo' => 0, 'recientes' => 0, 'fallidos' => 0, 'error' => null];

    $ids = array_map('intval', array_column(traerTodo(
        'SELECT cs.user_id FROM course_students cs
           JOIN users u ON u.id = cs.user_id AND u.status = "active"
          WHERE cs.course_id = ?',
        [(int) $curso['id']]
    ), 'user_id'));

    foreach ($ids as $id) {
        $r = enviarResumenFamilia($curso, $id);

        match ($r['motivo']) {
            'enviado'    => $res['enviados']++,
            'sin_correo' => $res['sin_correo']++,
            'reciente'   => $res['recientes']++,
            default      => $res['fallidos']++,
        };

        if (!$r['ok'] && in_array($r['motivo'], ['fallo', 'sin_instalar'], true)) {
            $res['error'] = $r['error'];

            // Si el correo no está configurado, fallarán todos igual: no
            // tiene sentido intentar los treinta.
            if ($r['motivo'] === 'sin_instalar' || !correoConfigurado()) {
                break;
            }
        }
    }

    return $res;
}

/** Cuántos niños del curso tienen correo de acudiente. */
function familiasConCorreo(int $cursoId): array
{
    $r = traerUno(
        'SELECT COUNT(*) AS total,
                SUM(u.role = "student" AND u.guardian_email IS NOT NULL AND u.guardian_email <> "") AS con_correo
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id AND u.status = "active"
          WHERE cs.course_id = ?',
        [$cursoId]
    ) ?: [];

    return ['total' => (int) ($r['total'] ?? 0), 'con_correo' => (int) ($r['con_correo'] ?? 0)];
}
