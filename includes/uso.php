<?php
/**
 * uso.php — Cuánto y cómo usa la plataforma cada persona
 *
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ SE MIDE Y QUÉ NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Se mide lo que sirve para responder tres preguntas que un docente o una
 * familia sí se hacen:
 *
 *   · ¿Está entrando?          → visitas, días activos, última vez
 *   · ¿Cuánto rato se queda?   → duración de las visitas
 *   · ¿Está aprendiendo algo?  → estaciones terminadas, intentos, materias
 *
 * NO se registra qué páginas concretas visita ni en qué orden. Con datos
 * de menores, cada campo que se guarda hay que poder justificarlo, y un
 * rastro de navegación página a página no responde ninguna de las tres
 * preguntas de arriba: solo engorda la tabla y el riesgo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  CÓMO SE MIDE EL TIEMPO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Una **visita** empieza con la primera petición y termina tras 30
 * minutos sin ninguna. Su duración es `last_seen_at - started_at`.
 *
 * Es una convención, no una medida exacta, y conviene decirlo en voz alta
 * porque el informe lo lee gente que va a tomar decisiones con ello:
 *
 *   · Quien deja la pestaña abierta y se va, cuenta de más hasta el
 *     último clic, no más.
 *   · Quien entra, hace una cosa y cierra, cuenta CERO: solo hubo una
 *     petición y no hay intervalo que medir. Por eso el informe enseña
 *     también el número de visitas, no solo el tiempo.
 *
 * La alternativa —latidos desde el navegador cada pocos segundos— afina
 * un número que nadie va a mirar con esa precisión y gasta batería de una
 * tablet infantil. No compensa.
 */

declare(strict_types=1);

/** Minutos de silencio tras los que una visita se da por terminada. */
const USO_CORTE_MINUTOS = 30;

/** Cada cuántos segundos, como mucho, se toca la fila de la visita. */
const USO_REFRESCO_SEGUNDOS = 60;


// =====================================================================
//  REGISTRO
// =====================================================================

/**
 * Anota que el usuario actual sigue por aquí.
 *
 * La llama `config.php` en cada petición con sesión. Tiene que ser
 * barata: se ejecuta también mientras un niño juega, que genera muchas
 * peticiones a la API.
 *
 * El freno está en `$_SESSION`: entre refresco y refresco no se toca la
 * base para nada. Sin él sería un UPDATE por petición.
 */
function registrarVisita(): void
{
    if (!haySesion() || !usoInstalado()) {
        return;
    }

    $ahora = time();

    // ── ¿Toca escribir? ──────────────────────────────────────────────
    $sesionId = $_SESSION['visita_id']    ?? null;
    $ultima   = (int) ($_SESSION['visita_tocada'] ?? 0);

    if ($sesionId !== null && ($ahora - $ultima) < USO_REFRESCO_SEGUNDOS) {
        return;
    }

    $usuarioId = usuarioActualId();

    if ($usuarioId === null) {
        return;
    }

    /*
     * Si el hueco desde la última señal supera el corte, esto ya es otra
     * visita. Se comprueba contra la BASE y no contra `$_SESSION`, porque
     * la sesión del navegador puede sobrevivir al corte —cookies de una
     * semana— y entonces todo el mes sería una sola visita larguísima.
     */
    if ($sesionId !== null) {
        $vigente = traerUno(
            'SELECT id FROM user_sessions
              WHERE id = ? AND user_id = ?
                AND last_seen_at > DATE_SUB(NOW(), INTERVAL ? MINUTE)',
            [(int) $sesionId, $usuarioId, USO_CORTE_MINUTOS]
        );

        if ($vigente) {
            ejecutar(
                'UPDATE user_sessions SET last_seen_at = NOW(), hits = hits + 1 WHERE id = ?',
                [(int) $sesionId]
            );
            $_SESSION['visita_tocada'] = $ahora;
            return;
        }
    }

    // ── Visita nueva ─────────────────────────────────────────────────
    $_SESSION['visita_id'] = (int) insertar(
        'INSERT INTO user_sessions (user_id, device, ip) VALUES (?, ?, ?)',
        [
            $usuarioId,
            dispositivoDelAgente((string) ($_SERVER['HTTP_USER_AGENT'] ?? '')),
            mb_substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
        ]
    );

    $_SESSION['visita_tocada'] = $ahora;
}

/**
 * Móvil o escritorio, a ojo.
 *
 * Se guarda solo eso —tres valores— y no el agente completo. El agente
 * entero identifica un navegador con bastante precisión y no hace falta
 * para nada: la pregunta útil es «¿usan tablet o computador?», que ayuda
 * a decidir dónde invertir el diseño.
 */
function dispositivoDelAgente(string $agente): string
{
    if ($agente === '') {
        return 'otro';
    }

    return preg_match('/Mobi|Android|iPhone|iPad|iPod|Tablet/i', $agente)
        ? 'movil'
        : 'escritorio';
}

/** ¿Está la tabla? Permite desplegar el código antes que la migración. */
function usoInstalado(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.tables
              WHERE table_schema = DATABASE() AND table_name = "user_sessions"'
        );
    }

    return $listo;
}


// =====================================================================
//  INFORME DE UNA PERSONA
// =====================================================================

/**
 * Todo lo medible de un usuario, en una sola llamada.
 *
 * Son varias consultas y se hacen a propósito por separado: juntarlas con
 * JOINs multiplicaría filas —tres actividades por cinco visitas— y las
 * sumas saldrían infladas. Es el error clásico de los informes, y lo peor
 * es que da un número creíble.
 */
function informeDeUso(int $usuarioId): array
{
    $vacio = [
        'visitas' => 0, 'minutos' => 0, 'media_minutos' => 0, 'dias_activos' => 0,
        'primera' => null, 'ultima' => null, 'hits' => 0,
        'movil' => 0, 'escritorio' => 0,
    ];

    // ── Visitas ──────────────────────────────────────────────────────
    if (usoInstalado()) {
        $s = traerUno(
            'SELECT COUNT(*) AS visitas,
                    COALESCE(SUM(TIMESTAMPDIFF(SECOND, started_at, last_seen_at)), 0) AS segundos,
                    COUNT(DISTINCT DATE(started_at)) AS dias_activos,
                    MIN(started_at) AS primera,
                    MAX(last_seen_at) AS ultima,
                    COALESCE(SUM(hits), 0) AS hits,
                    SUM(device = "movil") AS movil,
                    SUM(device = "escritorio") AS escritorio
               FROM user_sessions WHERE user_id = ?',
            [$usuarioId]
        );

        if ($s) {
            $segundos = (int) $s['segundos'];
            $visitas  = (int) $s['visitas'];

            $vacio = [
                'visitas'       => $visitas,
                'minutos'       => (int) round($segundos / 60),
                'media_minutos' => $visitas > 0 ? (int) round($segundos / 60 / $visitas) : 0,
                'dias_activos'  => (int) $s['dias_activos'],
                'primera'       => $s['primera'],
                'ultima'        => $s['ultima'],
                'hits'          => (int) $s['hits'],
                'movil'         => (int) $s['movil'],
                'escritorio'    => (int) $s['escritorio'],
            ];
        }
    }

    // ── Juego ────────────────────────────────────────────────────────
    $j = traerUno(
        'SELECT COUNT(*) AS estaciones_tocadas,
                SUM(status = "completed") AS estaciones_hechas,
                COUNT(DISTINCT activity_id) AS actividades_tocadas,
                COALESCE(SUM(stars), 0)  AS estrellas,
                COALESCE(SUM(coins), 0)  AS monedas,
                COALESCE(SUM(attempts), 0) AS intentos,
                COALESCE(SUM(time_spent_seconds), 0) AS segundos_jugados,
                MAX(updated_at) AS ultimo_juego
           FROM activity_progress WHERE user_id = ?',
        [$usuarioId]
    ) ?: [];

    // Actividades terminadas del todo: las que tienen todas sus
    // estaciones completadas. No se puede sacar de la consulta de arriba.
    $completas = (int) traerValor(
        'SELECT COUNT(*) FROM (
            SELECT p.activity_id
              FROM activity_progress p
             WHERE p.user_id = ? AND p.status = "completed"
          GROUP BY p.activity_id
            HAVING COUNT(DISTINCT p.station_id) >=
                   (SELECT COUNT(*) FROM activity_stations s
                     WHERE s.activity_id = p.activity_id)
         ) AS t',
        [$usuarioId]
    );

    // ── Por materia ──────────────────────────────────────────────────
    $materias = traerTodo(
        'SELECT c.name AS categoria, c.icon,
                COUNT(DISTINCT p.station_id) AS estaciones,
                COALESCE(SUM(p.time_spent_seconds), 0) AS segundos
           FROM activity_progress p
           JOIN activities a  ON a.id = p.activity_id
           JOIN categories c  ON c.id = a.category_id
          WHERE p.user_id = ? AND p.status = "completed"
       GROUP BY c.id
       ORDER BY estaciones DESC',
        [$usuarioId]
    );

    // ── Cuenta ───────────────────────────────────────────────────────
    $u = traerUno(
        'SELECT id, name, email, role, status, avatar, created_at, last_login_at
           FROM users WHERE id = ?',
        [$usuarioId]
    );

    return [
        'usuario'  => $u,
        'visitas'  => $vacio,
        'juego'    => [
            'estaciones_tocadas'  => (int) ($j['estaciones_tocadas'] ?? 0),
            'estaciones_hechas'   => (int) ($j['estaciones_hechas'] ?? 0),
            'actividades_tocadas' => (int) ($j['actividades_tocadas'] ?? 0),
            'actividades_hechas'  => $completas,
            'estrellas'           => (int) ($j['estrellas'] ?? 0),
            'monedas'             => (int) ($j['monedas'] ?? 0),
            'intentos'            => (int) ($j['intentos'] ?? 0),
            'minutos_jugados'     => (int) round(((int) ($j['segundos_jugados'] ?? 0)) / 60),
            'ultimo_juego'        => $j['ultimo_juego'] ?? null,
        ],
        'materias' => $materias,
    ];
}

/**
 * Actividad día a día, para la gráfica.
 *
 * Devuelve TODOS los días del rango, también los vacíos. Sin los ceros,
 * una gráfica de barras junta el lunes con el viernes y aparenta una
 * constancia que no hubo.
 */
function usoPorDia(int $usuarioId, int $dias = 30): array
{
    $serie = [];

    for ($i = $dias - 1; $i >= 0; $i--) {
        $d = date('Y-m-d', strtotime("-$i days"));
        $serie[$d] = ['fecha' => $d, 'minutos' => 0, 'visitas' => 0, 'estaciones' => 0];
    }

    if (usoInstalado()) {
        $filas = traerTodo(
            'SELECT DATE(started_at) AS d, COUNT(*) AS visitas,
                    COALESCE(SUM(TIMESTAMPDIFF(SECOND, started_at, last_seen_at)), 0) AS segundos
               FROM user_sessions
              WHERE user_id = ? AND started_at > DATE_SUB(CURDATE(), INTERVAL ? DAY)
           GROUP BY DATE(started_at)',
            [$usuarioId, $dias]
        );

        foreach ($filas as $f) {
            if (isset($serie[$f['d']])) {
                $serie[$f['d']]['visitas'] = (int) $f['visitas'];
                $serie[$f['d']]['minutos'] = (int) round(((int) $f['segundos']) / 60);
            }
        }
    }

    $hechas = traerTodo(
        'SELECT DATE(completed_at) AS d, COUNT(*) AS n
           FROM activity_progress
          WHERE user_id = ? AND status = "completed"
            AND completed_at > DATE_SUB(CURDATE(), INTERVAL ? DAY)
       GROUP BY DATE(completed_at)',
        [$usuarioId, $dias]
    );

    foreach ($hechas as $f) {
        if (isset($serie[$f['d']])) {
            $serie[$f['d']]['estaciones'] = (int) $f['n'];
        }
    }

    return array_values($serie);
}

/** Las últimas visitas, una a una. */
function ultimasVisitas(int $usuarioId, int $limite = 15): array
{
    if (!usoInstalado()) {
        return [];
    }

    return traerTodo(
        'SELECT id, started_at, last_seen_at, hits, device,
                TIMESTAMPDIFF(SECOND, started_at, last_seen_at) AS segundos
           FROM user_sessions
          WHERE user_id = ?
       ORDER BY started_at DESC
          LIMIT ' . max(1, min(100, $limite)),
        [$usuarioId]
    );
}

/** Lo último que ha jugado, con nombre de actividad. */
function ultimoJuegoDe(int $usuarioId, int $limite = 10): array
{
    return traerTodo(
        'SELECT a.title AS actividad, a.slug, s.title AS estacion, s.position,
                p.status, p.stars, p.attempts, p.time_spent_seconds, p.updated_at,
                c.icon AS categoria_icon
           FROM activity_progress p
           JOIN activity_stations s ON s.id = p.station_id
           JOIN activities a        ON a.id = p.activity_id
      LEFT JOIN categories c        ON c.id = a.category_id
          WHERE p.user_id = ?
       ORDER BY p.updated_at DESC
          LIMIT ' . max(1, min(50, $limite)),
        [$usuarioId]
    );
}

/**
 * Duración legible.
 *
 * «0 min» para una visita real queda mal y además confunde, así que un
 * rato corto se dice con palabras. Ver la nota de arriba sobre por qué
 * una visita de una sola petición mide cero.
 */
function duracionLegible(int $segundos): string
{
    if ($segundos <= 0)   return 'un momento';
    if ($segundos < 60)   return $segundos . ' s';
    if ($segundos < 3600) return (int) round($segundos / 60) . ' min';

    $h = intdiv($segundos, 3600);
    $m = (int) round(($segundos % 3600) / 60);

    return $m > 0 ? "{$h} h {$m} min" : "{$h} h";
}


// =====================================================================
//  RESUMEN DEL SITIO
// =====================================================================

/** Cifras de uso de toda la plataforma, para el panel. */
function usoDelSitio(int $dias = 30): array
{
    if (!usoInstalado()) {
        return ['activos' => 0, 'visitas' => 0, 'minutos' => 0,
                'media_minutos' => 0, 'movil' => 0, 'escritorio' => 0];
    }

    $r = traerUno(
        'SELECT COUNT(DISTINCT user_id) AS activos,
                COUNT(*) AS visitas,
                COALESCE(SUM(TIMESTAMPDIFF(SECOND, started_at, last_seen_at)), 0) AS segundos,
                SUM(device = "movil") AS movil,
                SUM(device = "escritorio") AS escritorio
           FROM user_sessions
          WHERE started_at > DATE_SUB(NOW(), INTERVAL ? DAY)',
        [$dias]
    ) ?: [];

    $visitas  = (int) ($r['visitas'] ?? 0);
    $segundos = (int) ($r['segundos'] ?? 0);

    return [
        'activos'       => (int) ($r['activos'] ?? 0),
        'visitas'       => $visitas,
        'minutos'       => (int) round($segundos / 60),
        'media_minutos' => $visitas > 0 ? (int) round($segundos / 60 / $visitas) : 0,
        'movil'         => (int) ($r['movil'] ?? 0),
        'escritorio'    => (int) ($r['escritorio'] ?? 0),
    ];
}

/** Quién ha usado más la plataforma. */
function usuariosMasActivos(int $dias = 30, int $limite = 15): array
{
    if (!usoInstalado()) {
        return [];
    }

    return traerTodo(
        'SELECT u.id, u.name, u.email, u.role,
                COUNT(s.id) AS visitas,
                COALESCE(SUM(TIMESTAMPDIFF(SECOND, s.started_at, s.last_seen_at)), 0) AS segundos,
                COUNT(DISTINCT DATE(s.started_at)) AS dias,
                MAX(s.last_seen_at) AS ultima
           FROM user_sessions s
           JOIN users u ON u.id = s.user_id
          WHERE s.started_at > DATE_SUB(NOW(), INTERVAL ? DAY)
       GROUP BY u.id
       ORDER BY segundos DESC
          LIMIT ' . max(1, min(100, $limite)),
        [$dias]
    );
}
