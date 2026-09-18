<?php
/**
 * api/progreso.php — Guarda el avance del jugador
 *
 * Recibe el resultado de una estación terminada y lo registra.
 *
 * El progreso solo se guarda para usuarios con sesión. Normalmente no
 * llega aquí nadie sin ella —jugar exige cuenta, ver acceso.php— pero si
 * un administrador abre el catálogo para una demostración, un visitante
 * anónimo puede jugar y entonces su avance simplemente no se almacena:
 * no tendría a quién pertenecer, y guardar datos de alguien que no ha
 * creado una cuenta sería recoger información sin motivo.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Cache-Control: no-store, private');

function responder(array $datos, int $codigo = 200): never
{
    http_response_code($codigo);
    echo jsonSeguro($datos);
    exit;
}

// Solo POST: guardar algo con una petición GET permitiría dispararlo
// desde cualquier página ajena.
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    responder(['ok' => false, 'error' => 'Método no permitido.'], 405);
}

if (!csrfValido()) {
    responder(['ok' => false, 'error' => 'Sesión del formulario expirada.'], 403);
}

$usuarioId = usuarioActualId();

// Sin sesión no hay error: simplemente no se guarda nada.
if ($usuarioId === null) {
    responder(['ok' => true, 'guardado' => false, 'motivo' => 'sin_sesion']);
}

$estacionId = (int) ($_POST['estacion'] ?? 0);
if ($estacionId <= 0) {
    responder(['ok' => false, 'error' => 'Falta la estación.'], 400);
}

$estacion = traerUno(
    'SELECT id, activity_id, position, is_free FROM activity_stations WHERE id = ?',
    [$estacionId]
);
if (!$estacion) {
    responder(['ok' => false, 'error' => 'La estación no existe.'], 404);
}

$actividad = traerUno(
    'SELECT id, access_type, free_stations, status FROM activities WHERE id = ?',
    [$estacion['activity_id']]
);

// Se vuelve a comprobar el acceso: no se registra progreso de contenido
// que el usuario no tenía derecho a jugar.
if (!$actividad || !puedeJugarEstacion($actividad, $estacion)) {
    responder(['ok' => false, 'error' => 'Sin acceso a esta estación.'], 403);
}

// ── Valores recibidos, acotados ──────────────────────────────────────
// Vienen del navegador, así que se limitan a rangos razonables: nadie
// debe poder inscribir 9.000 estrellas manipulando la petición.
$porcentaje = max(0, min(100,  (int) ($_POST['porcentaje'] ?? 0)));
$puntos     = max(0, min(10000,(int) ($_POST['puntos']     ?? 0)));
$estrellas  = max(0, min(3,    (int) ($_POST['estrellas']  ?? 0)));
$monedas    = max(0, min(100,  (int) ($_POST['monedas']    ?? 0)));
$segundos   = max(0, min(7200, (int) ($_POST['segundos']   ?? 0)));

$completada = $porcentaje >= 100;
$estado     = $completada ? 'completed' : 'in_progress';

/*
 * ─────────────────────────────────────────────────────────────────────
 *  ¿ESTO CUENTA PARA UN CURSO O ES SUYO?
 * ─────────────────────────────────────────────────────────────────────
 *
 * Lo decide el servidor, nunca la petición: si el navegador pudiera
 * decir para qué curso cuenta lo que hace, cualquiera podría rellenar el
 * informe de otro curso o vaciar el suyo. Ver `cursoDeTrabajo()`.
 */
$cursoId = cursoDeTrabajo($usuarioId, (int) $actividad['id']);

/*
 * ─────────────────────────────────────────────────────────────────────
 *  UNA ESTACIÓN PAGA UNA VEZ
 * ─────────────────────────────────────────────────────────────────────
 *
 * Ahora puede haber dos filas para la misma estación —la personal y la
 * del curso— y `billetera()` suma monedas de todas. Sin este freno, un
 * niño que juega algo en casa y luego lo repite para clase cobraría dos
 * veces por el mismo trabajo.
 *
 * Las estrellas y las monedas son del NIÑO y no del contexto: se ganan
 * la primera vez, en el contexto que sea, y la segunda fila registra que
 * lo hizo pero no vuelve a pagar. Así el docente ve su trabajo y la
 * gamificación sigue cuadrando.
 */
$yaCobrada = (int) traerValor(
    'SELECT COUNT(1) FROM activity_progress
      WHERE user_id = ? AND station_id = ? AND course_id <> ?
        AND (coins > 0 OR stars > 0)',
    [$usuarioId, $estacionId, $cursoId]
);

if ($yaCobrada > 0) {
    $monedas   = 0;
    $estrellas = 0;
}

/*
 * ─────────────────────────────────────────────────────────────────────
 *  QUÉ LOGROS TENÍA ANTES DE ESTA ESTACIÓN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Los logros no se guardan en ninguna tabla: se CALCULAN a partir del
 * progreso. Eso está bien —un contador guardado se desincroniza— pero
 * deja sin respuesta la pregunta que hace falta aquí: ¿cuál es NUEVO?
 *
 * Se resuelve mirando antes y después. Es el único momento en que se
 * puede saber: cuando el niño vuelva a entrar mañana, el logro ya
 * llevará ahí un día y no habrá nada que celebrar.
 *
 * Cuesta un puñado de COUNT, y ocurre una vez por estación terminada.
 */
$logrosAntes = $completada
    ? array_column(array_filter(logros($usuarioId), static fn($l) => $l['logrado']), 'slug')
    : [];

// Una fila por (usuario, estación, contexto). Se actualiza en vez de
// acumular filas: el índice único uk_progress_contexto lo garantiza.
ejecutar(
    'INSERT INTO activity_progress
        (user_id, activity_id, station_id, course_id, status, percent, score, stars, coins,
         attempts, time_spent_seconds, completed_at)
     VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, 1, ?, ' . ($completada ? 'NOW()' : 'NULL') . ')
     ON DUPLICATE KEY UPDATE
        status             = IF(VALUES(percent) >= percent, VALUES(status), status),
        percent            = GREATEST(percent, VALUES(percent)),
        score              = GREATEST(score,   VALUES(score)),
        stars              = GREATEST(stars,   VALUES(stars)),
        coins              = GREATEST(coins,   VALUES(coins)),
        attempts           = attempts + 1,
        time_spent_seconds = time_spent_seconds + VALUES(time_spent_seconds),
        completed_at       = COALESCE(completed_at, ' . ($completada ? 'NOW()' : 'NULL') . ')',
    [$usuarioId, $actividad['id'], $estacionId, $cursoId, $estado,
     $porcentaje, $puntos, $estrellas, $monedas, $segundos]
);

/*
 * ── Resumen de la actividad ──────────────────────────────────────────
 *
 * Para el NIÑO, que es quien lo ve en pantalla, da igual el contexto:
 * una estación terminada está terminada. Por eso se cuenta
 * `DISTINCT station_id` en vez de filas — con las dos filas de una misma
 * estación, contar filas diría «5 de 4».
 */
$resumen = traerUno(
    'SELECT COUNT(DISTINCT station_id) AS hechas,
            COALESCE(SUM(stars), 0)  AS estrellas,
            COALESCE(SUM(coins), 0)  AS monedas
       FROM activity_progress
      WHERE user_id = ? AND activity_id = ? AND status = "completed"',
    [$usuarioId, $actividad['id']]
);

$totalEstaciones = (int) traerValor(
    'SELECT COUNT(1) FROM activity_stations WHERE activity_id = ?',
    [$actividad['id']]
);

/*
 * Los logros que acaban de conseguirse con ESTA estación.
 *
 * Se devuelven enteros —nombre, dibujo y pista— para que el motor pueda
 * celebrarlos sin tener que volver a preguntar. Son pocos y pequeños.
 */
$logrosNuevos = [];

if ($completada) {
    foreach (logros($usuarioId) as $l) {
        if ($l['logrado'] && !in_array($l['slug'], $logrosAntes, true)) {
            $logrosNuevos[] = [
                'slug'   => $l['slug'],
                'nombre' => $l['nombre'],
                'emoji'  => $l['emoji'],
                'pista'  => $l['pista'],
            ];
        }
    }
}

// La billetera al día, para que el marcador suba en el momento y no en
// la siguiente carga de página.
$carteraAhora = billetera($usuarioId);

responder([
    'ok'       => true,
    'guardado' => true,
    'resumen'  => [
        'completadas' => (int) $resumen['hechas'],
        'total'       => $totalEstaciones,
        'estrellas'   => (int) $resumen['estrellas'],
        'monedas'     => (int) $resumen['monedas'],
    ],
    'cartera' => [
        'saldo'     => (int) $carteraAhora['saldo'],
        'estrellas' => (int) $carteraAhora['estrellas'],
    ],
    'logros_nuevos' => $logrosNuevos,
]);
