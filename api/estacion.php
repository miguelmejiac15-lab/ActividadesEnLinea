<?php
/**
 * api/estacion.php — Entrega el contenido de una estación
 *
 * ─────────────────────────────────────────────────────────────────────
 *  PUNTO CRÍTICO DE SEGURIDAD
 * ─────────────────────────────────────────────────────────────────────
 *
 * Este es el único camino por el que el contenido de un minijuego llega
 * al navegador, y no lo entrega sin comprobar antes que el visitante
 * tenga derecho a jugarlo.
 *
 * La página del reproductor envía la lista de estaciones SIN contenido.
 * Cuando el jugador abre una, el navegador la pide aquí y el servidor
 * decide. Así, aunque alguien manipule el JavaScript de la página, no
 * obtiene nada: el contenido nunca estuvo allí.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

header('Content-Type: application/json; charset=utf-8');
// Respuesta personal según el plan del visitante: no debe cachearse en
// un proxy compartido y servirse luego a otra persona.
header('Cache-Control: no-store, private');

/** Responde en JSON y termina. */
function responder(array $datos, int $codigo = 200): never
{
    http_response_code($codigo);
    echo jsonSeguro($datos);
    exit;
}

// ── Parámetros ───────────────────────────────────────────────────────
$estacionId = getEntero('estacion');

if ($estacionId <= 0) {
    responder(['ok' => false, 'error' => 'Falta el identificador de la estación.'], 400);
}

// ── Estación y su actividad ──────────────────────────────────────────
$estacion = traerUno(
    'SELECT s.id, s.activity_id, s.position, s.title, s.description,
            s.icon, s.game_type, s.config, s.is_free
       FROM activity_stations s
      WHERE s.id = ?',
    [$estacionId]
);

if (!$estacion) {
    responder(['ok' => false, 'error' => 'La estación no existe.'], 404);
}

/*
 * El NIVEL viaja con la actividad, y no es un adorno: en preescolar el
 * niño todavía no lee, así que el motor tiene que leerle todo en voz
 * alta. Sin este dato el navegador no puede saberlo — el nivel vive en
 * la base y la decisión no puede quedar en manos de la petición.
 */
$actividad = traerUno(
    'SELECT a.id, a.slug, a.title, a.status, a.access_type, a.free_stations,
            a.content, l.slug AS nivel
       FROM activities a
  LEFT JOIN levels l ON l.id = a.level_id
      WHERE a.id = ?',
    [$estacion['activity_id']]
);

// Una actividad despublicada no se sirve, aunque se conozca el id de la
// estación. Los administradores sí pueden, para poder revisar borradores.
if (!$actividad || ($actividad['status'] !== 'published' && !esAdmin())) {
    responder(['ok' => false, 'error' => 'La actividad no está disponible.'], 404);
}

// ── La puerta ────────────────────────────────────────────────────────
// Si el visitante no puede jugar esta estación, se responde con la
// invitación que corresponda y NADA del contenido.
//
// Son dos puertas distintas: sin cuenta no se juega nada (401), y con
// cuenta pero sin suscripción no se juega lo premium (403). El catálogo
// se puede seguir mirando entero en los dos casos.
$motivo = motivoBloqueo($actividad, $estacion);

if ($motivo !== null) {
    responder(
        invitacionPorMotivo($motivo),
        $motivo === 'cuenta_requerida' ? 401 : 403
    );
}

// ── Contenido ────────────────────────────────────────────────────────
// A partir de aquí el acceso está confirmado.
$config = json_decode($estacion['config'] ?? 'null', true);
$comun  = json_decode($actividad['content'] ?? 'null', true);

// Progreso previo, si hay sesión.
$progreso = null;
if (($uid = usuarioActualId()) !== null) {
    $progreso = traerUno(
        'SELECT status, percent, score, stars, attempts
           FROM activity_progress
          WHERE user_id = ? AND station_id = ?',
        [$uid, $estacion['id']]
    );
}

responder([
    'ok'       => true,
    'estacion' => [
        'id'          => (int) $estacion['id'],
        'posicion'    => (int) $estacion['position'],
        'titulo'      => $estacion['title'],
        'descripcion' => $estacion['description'],
        'icono'       => $estacion['icon'],
        'tipo'        => $estacion['game_type'],
        'contenido'   => $config['datos'] ?? null,
        'sonido'      => $config['sonido'] ?? ($comun['sonido'] ?? null),
        // Idioma con el que el motor debe leer en voz alta esta estación.
        // Solo lo declaran las actividades de Idiomas; el resto calla y
        // el motor asume español.
        'idioma'      => $config['idioma'] ?? ($comun['idioma'] ?? null),
    ],
    'actividad' => [
        'slug'  => $actividad['slug'],
        'letra' => $comun['letra'] ?? null,
        'nivel' => $actividad['nivel'],

        /*
         * «Este niño todavía no lee.»
         *
         * Lo decide el servidor a partir del nivel de la actividad, no el
         * navegador: es lo que hace que el motor lea en voz alta la
         * instrucción, las opciones y el resultado, en vez de dejar al
         * niño delante de un texto que no puede descifrar.
         */
        'sin_lectura' => ($actividad['nivel'] ?? '') === 'preescolar',
    ],
    'progreso' => $progreso,
]);
