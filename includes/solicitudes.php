<?php
/**
 * solicitudes.php — Solicitudes de información del formulario público
 *
 * Quien llega desde un anuncio y quiere saber más —una familia, un docente,
 * un rector— deja aquí lo que necesita. El orden de las cosas importa:
 *
 *   1. Se GUARDA la solicitud.
 *   2. Se intenta avisar por correo al buzón de ventas.
 *   3. Se le cuenta a Meta que llegó un interesado («Lead»).
 *
 * Si el correo falla, la solicitud ya está en Panel → Solicitudes con el
 * motivo del fallo: nadie que pidió información se queda sin respuesta
 * porque una contraseña de Gmail caducó.
 *
 * Las preguntas cambian según quién escribe. A un colegio le importa
 * cuántos estudiantes y docentes tiene y si quiere usarla en todos los
 * grados; a una familia, la edad de su hijo y si es para reforzar en casa.
 * Preguntarle a una mamá por «número de docentes» la hace dudar de si el
 * formulario es para ella.
 */

declare(strict_types=1);

/** Quién escribe. */
const SOLICITUD_PERFILES = [
    'familia' => 'Soy mamá, papá o acudiente',
    'docente' => 'Soy docente',
    'colegio' => 'Represento un colegio o institución',
    'otro'    => 'Otro (fundación, biblioteca, secretaría de educación…)',
];

/** Niveles. */
const SOLICITUD_NIVELES = [
    'preescolar'     => 'Preescolar (3 a 5 años)',
    'primero-segundo' => 'Primero y segundo',
    'tercero-cuarto' => 'Tercero y cuarto',
    'quinto-sexto'   => 'Quinto y sexto',
];

/** Áreas: las categorías del catálogo, dichas como las nombra un colegio. */
const SOLICITUD_AREAS = [
    'lectoescritura' => 'Lectoescritura',
    'matematicas'    => 'Matemáticas',
    'ciencias'       => 'Ciencias naturales',
    'sociales'       => 'Ciencias sociales',
    'ingles'         => 'Inglés',
    'tecnologia'     => 'Tecnología',
    'artistica'      => 'Artística',
    'valores'        => 'Valores y convivencia',
    'pensamiento'    => 'Pensamiento lógico',
    'inclusion'      => 'Inclusión y apoyos (DUA)',
];

/** Lo que necesita, por perfil: cada uno ve solo las suyas. */
const SOLICITUD_NECESIDADES = [
    'familia' => [
        'refuerzo-casa'  => 'Reforzar en casa lo que ve en el colegio',
        'aprender-leer'  => 'Que aprenda a leer y escribir',
        'matematicas'    => 'Mejorar en matemáticas',
        'pantalla-util'  => 'Que el tiempo de pantalla sea educativo',
        'apoyo-especial' => 'Apoyo por una dificultad de aprendizaje o atención',
    ],
    'docente' => [
        'proyectar-clase' => 'Proyectar actividades en clase',
        'tareas-casa'     => 'Asignar tareas para la casa',
        'seguimiento'     => 'Ver el progreso de mis estudiantes',
        'inclusion'       => 'Actividades para estudiantes con apoyos (DUA)',
        'planear'         => 'Ahorrar tiempo al planear',
    ],
    'colegio' => [
        'licencias'      => 'Licencias para todos los estudiantes',
        'docentes'       => 'Acceso para los docentes',
        'informes'       => 'Informes de progreso por curso',
        'familias'       => 'Que las familias sigan el avance desde casa',
        'capacitacion'   => 'Capacitación para docentes',
        'piloto'         => 'Hacer una prueba piloto',
        'inclusion'      => 'Apoyo a la inclusión (DUA)',
    ],
    'otro' => [
        'licencias'   => 'Licencias para un grupo de niños',
        'alianza'     => 'Una alianza o convenio',
        'capacitacion' => 'Capacitación',
    ],
];

const SOLICITUD_ESTUDIANTES = ['Menos de 50', '50 a 200', '200 a 500', '500 a 1.000', 'Más de 1.000'];
const SOLICITUD_DOCENTES    = ['Menos de 10', '10 a 30', '30 a 60', 'Más de 60'];
const SOLICITUD_CONTACTO    = ['whatsapp' => 'WhatsApp', 'llamada' => 'Llamada', 'correo' => 'Correo', 'reunion' => 'Reunión virtual'];
const SOLICITUD_ORIGEN      = ['facebook' => 'Facebook', 'instagram' => 'Instagram', 'google' => 'Google', 'recomendacion' => 'Me lo recomendaron', 'colegio' => 'En mi colegio', 'otro' => 'Otro'];

/** A dónde llegan los avisos: entorno, ajuste, o el buzón de siempre. */
function solicitudesCorreo(): string
{
    $delEntorno = getenv('SOLICITUDES_CORREO');
    if (is_string($delEntorno) && trim($delEntorno) !== '') {
        return trim($delEntorno);
    }

    return trim((string) ajuste('solicitudes_correo', 'actividadesenlinea1577@gmail.com'));
}

/** Huella de la IP para frenar abusos sin guardarla en claro. */
function solicitudHuellaIp(): string
{
    return hash('sha256', (function_exists('ipCliente') ? ipCliente() : ($_SERVER['REMOTE_ADDR'] ?? '')) . '|' . APP_KEY);
}

/** ¿Esta conexión ya mandó demasiadas en la última hora? */
function solicitudesFrenadas(): bool
{
    return (int) traerValor(
        'SELECT COUNT(*) FROM info_requests WHERE ip_hash = ? AND created_at > NOW() - INTERVAL 1 HOUR',
        [solicitudHuellaIp()]
    ) >= 5;
}

/** Solo las claves que existen en la lista; nada que no se ofreció. */
function solicitudElegidas($dadas, array $lista): array
{
    $dadas = is_array($dadas) ? $dadas : [];

    return array_values(array_filter($dadas, static fn($k) => is_string($k) && isset($lista[$k])));
}

/**
 * Valida lo enviado. Devuelve los datos limpios y los errores por campo.
 *
 * @return array{datos: array<string, mixed>, errores: array<string, string>}
 */
function validarSolicitud(array $p): array
{
    $e = [];
    $t = static fn(string $k, int $max) => mb_substr(trim((string) ($p[$k] ?? '')), 0, $max);

    $perfil = (string) ($p['perfil'] ?? '');
    if (!isset(SOLICITUD_PERFILES[$perfil])) {
        $e['perfil'] = 'Cuéntanos quién eres.';
        $perfil = 'otro';
    }

    $d = [
        'profile'      => $perfil,
        'name'         => $t('nombre', 120),
        'email'        => correoValido((string) ($p['correo'] ?? '')) ?? '',
        'phone'        => preg_replace('/[^\d+ ]/', '', $t('telefono', 40)),
        'city'         => $t('ciudad', 120),
        'institution'  => $t('institucion', 160),
        'job_title'    => $t('cargo', 120),
        'students'     => in_array($p['estudiantes'] ?? '', SOLICITUD_ESTUDIANTES, true) ? $p['estudiantes'] : null,
        'teachers'     => in_array($p['docentes'] ?? '', SOLICITUD_DOCENTES, true) ? $p['docentes'] : null,
        'grades'       => solicitudElegidas($p['niveles'] ?? [], SOLICITUD_NIVELES),
        'subjects'     => solicitudElegidas($p['areas'] ?? [], SOLICITUD_AREAS),
        'needs'        => solicitudElegidas($p['necesidades'] ?? [], SOLICITUD_NECESIDADES[$perfil] ?? []),
        'contact_pref' => isset(SOLICITUD_CONTACTO[$p['contacto'] ?? '']) ? $p['contacto'] : null,
        'source'       => isset(SOLICITUD_ORIGEN[$p['origen'] ?? '']) ? $p['origen'] : null,
        'message'      => $t('mensaje', 2000),
    ];

    if (mb_strlen($d['name']) < 2) {
        $e['nombre'] = 'Escribe tu nombre.';
    }
    if ($d['email'] === '') {
        $e['correo'] = 'Escribe un correo válido para poder responderte.';
    }
    if (strlen(preg_replace('/\D/', '', $d['phone'])) < 7) {
        $e['telefono'] = 'Escribe un número de WhatsApp o teléfono (con indicativo si no es de Colombia).';
    }
    if ($d['city'] === '') {
        $e['ciudad'] = 'Escribe tu ciudad.';
    }
    if (in_array($perfil, ['colegio', 'otro'], true) && $d['institution'] === '') {
        $e['institucion'] = 'Escribe el nombre de la institución.';
    }
    if ($perfil === 'colegio' && $d['students'] === null) {
        $e['estudiantes'] = 'Elige cuántos estudiantes tiene la institución, aproximadamente.';
    }
    if (empty($p['autorizo'])) {
        $e['autorizo'] = 'Necesitamos tu autorización para usar estos datos y responderte.';
    }

    return ['datos' => $d, 'errores' => $e];
}

/**
 * Guarda la solicitud, avisa por correo y apunta el evento para Meta.
 *
 * @return array{id: int, correo: array{ok: bool, error: ?string}}
 */
function registrarSolicitud(array $d): array
{
    $id = (int) insertar(
        'INSERT INTO info_requests
            (profile, name, email, phone, city, institution, job_title, students, teachers,
             grades, subjects, needs, contact_pref, source, message, consent_at, ip_hash)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), ?)',
        [
            $d['profile'], $d['name'], $d['email'], $d['phone'] ?: null, $d['city'] ?: null,
            $d['institution'] ?: null, $d['job_title'] ?: null, $d['students'], $d['teachers'],
            implode(',', $d['grades']) ?: null, implode(',', $d['subjects']) ?: null,
            implode(',', $d['needs']) ?: null, $d['contact_pref'], $d['source'],
            $d['message'] ?: null, solicitudHuellaIp(),
        ]
    );

    $correo = avisarSolicitud($id, $d);

    ejecutar('UPDATE info_requests SET email_sent = ?, email_error = ? WHERE id = ?',
             [$correo['ok'] ? 1 : 0, $correo['ok'] ? null : mb_substr((string) $correo['error'], 0, 255), $id]);

    // Para la pauta: llegó un interesado. Solo el perfil, nada de la persona.
    if (function_exists('metaLead')) {
        metaLead((string) $d['profile'], 'solicitud-' . $id);
    }

    return ['id' => $id, 'correo' => $correo];
}

/** El correo al buzón de ventas, con todo lo que dejó la persona. */
function avisarSolicitud(int $id, array $d): array
{
    $etiquetas = static function (array $claves, array $lista): string {
        return implode(', ', array_map(static fn($k) => $lista[$k] ?? $k, $claves));
    };

    $filas = [
        'Quién escribe'      => SOLICITUD_PERFILES[$d['profile']] ?? $d['profile'],
        'Nombre'             => $d['name'],
        'Correo'             => $d['email'],
        'WhatsApp / teléfono' => $d['phone'],
        'Ciudad'             => $d['city'],
        'Institución'        => $d['institution'],
        'Cargo'              => $d['job_title'],
        'Estudiantes'        => $d['students'],
        'Docentes'           => $d['teachers'],
        'Niveles'            => $etiquetas($d['grades'], SOLICITUD_NIVELES),
        'Áreas'              => $etiquetas($d['subjects'], SOLICITUD_AREAS),
        'Necesita'           => $etiquetas($d['needs'], SOLICITUD_NECESIDADES[$d['profile']] ?? []),
        'Prefiere contacto por' => SOLICITUD_CONTACTO[$d['contact_pref'] ?? ''] ?? '',
        'Nos conoció por'    => SOLICITUD_ORIGEN[$d['source'] ?? ''] ?? '',
        'Mensaje'            => $d['message'],
    ];

    $html = '<h2 style="font-family:Arial,sans-serif;color:#2f3b52">Nueva solicitud de información #' . $id . '</h2>'
          . '<table cellpadding="8" style="font-family:Arial,sans-serif;font-size:14px;border-collapse:collapse">';
    foreach ($filas as $k => $v) {
        if (trim((string) $v) === '') continue;
        $html .= '<tr><td style="background:#eef4fa;font-weight:bold;vertical-align:top">' . e($k) . '</td>'
               . '<td style="border-bottom:1px solid #e2e8f0">' . nl2br(e((string) $v)) . '</td></tr>';
    }
    $html .= '</table><p style="font-family:Arial,sans-serif;color:#8b95a5;font-size:12px">'
           . 'Pulsa «Responder» para escribirle directamente. Todas las solicitudes quedan en Panel → Solicitudes.</p>';

    $asunto = 'Solicitud de información: ' . ($d['institution'] ?: $d['name'])
            . ' (' . ucfirst($d['profile']) . ')';

    return enviarCorreo(solicitudesCorreo(), $asunto, $html, '', $d['email']);
}
