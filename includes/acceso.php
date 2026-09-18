<?php
/**
 * acceso.php — Control de acceso al contenido
 *
 * ─────────────────────────────────────────────────────────────────────
 *  ESTE ES EL ARCHIVO MÁS IMPORTANTE DE LA PLATAFORMA.
 * ─────────────────────────────────────────────────────────────────────
 *
 * Aquí se decide qué puede ver cada usuario. La regla del proyecto es
 * que el sistema NUNCA confía en la interfaz: el candado no es un
 * overlay que tapa contenido ya descargado, sino una decisión del
 * servidor que determina qué se envía al navegador.
 *
 * En la versión anterior el bloqueo vivía en gate.js y consultaba
 * localStorage. Cualquiera podía escribir una línea en la consola del
 * navegador y desbloquear todo el catálogo, porque el contenido premium
 * ya venía descargado dentro del HTML. Aquí eso es imposible: el
 * `config` de una estación bloqueada no sale nunca del servidor.
 *
 * MODELO FREEMIUM (30%)
 *   El usuario Free entra a TODAS las actividades, pero solo juega las
 *   primeras estaciones de cada una. Al llegar a la primera estación
 *   bloqueada ve la invitación a la Biblioteca Completa. Así prueba el
 *   producto de verdad antes de pagar.
 */

declare(strict_types=1);

// Resultados posibles de evaluar una actividad.
define('ACCESO_TOTAL',     'total');      // se puede jugar completa
define('ACCESO_LIMITADO',  'limitado');   // solo las primeras estaciones
define('ACCESO_BLOQUEADO', 'bloqueado');  // requiere suscripción para entrar


// =====================================================================
//  PLAN Y SUSCRIPCIÓN DEL USUARIO
// =====================================================================

/**
 * Devuelve la suscripción vigente del usuario actual, o null.
 *
 * "Vigente" significa estado activo Y fecha de vencimiento en el futuro.
 * La comprobación de la fecha la hace MySQL en la misma consulta, así
 * que una suscripción vencida deja de dar acceso automáticamente, sin
 * necesidad de una tarea que la marque como expirada.
 */
function suscripcionVigente(): ?array
{
    static $suscripcion = null;
    static $consultada = false;

    if ($consultada) {
        return $suscripcion;
    }
    $consultada = true;

    $usuarioId = usuarioActualId();
    if ($usuarioId === null) {
        return null;
    }

    $suscripcion = traerUno(
        "SELECT s.id, s.plan_id, s.billing_cycle, s.starts_at, s.expires_at,
                p.slug AS plan_slug, p.name AS plan_name,
                p.catalog_access, p.manages_courses
           FROM subscriptions s
           JOIN plans p ON p.id = s.plan_id
          WHERE s.user_id = ?
            AND s.status = 'active'
            AND (s.expires_at IS NULL OR s.expires_at > NOW())
       ORDER BY s.expires_at DESC
          LIMIT 1",
        [$usuarioId]
    );

    return $suscripcion;
}

/**
 * Plan efectivo del usuario en este momento.
 * Sin suscripción vigente —o sin sesión— el plan es siempre 'free'.
 */
function planActual(): string
{
    $s = suscripcionVigente();
    return $s['plan_slug'] ?? PLAN_FREE;
}

/**
 * ¿El usuario tiene acceso al catálogo completo?
 *
 * Lo tienen: el administrador, y quien posea una suscripción vigente a
 * un plan marcado con catalog_access = 'full'.
 */
function tieneCatalogoCompleto(): bool
{
    if (esAdmin()) {
        return true;
    }
    $s = suscripcionVigente();
    return $s !== null && $s['catalog_access'] === 'full';
}

/** ¿El usuario puede administrar cursos (área Escuela)? */
function puedeGestionarCursos(): bool
{
    if (esAdmin()) {
        return true;
    }
    $s = suscripcionVigente();
    return $s !== null && (int) $s['manages_courses'] === 1;
}

/** Fecha de vencimiento legible, para "Mi cuenta". */
function vencimientoSuscripcion(): ?string
{
    $s = suscripcionVigente();
    return $s['expires_at'] ?? null;
}

/**
 * ¿Tiene sentido invitar a este visitante a desbloquear contenido?
 *
 * No lo tiene si ya tiene el catálogo completo, ni si el catálogo está
 * abierto para todos: ofrecer desbloquear algo que ya está abierto
 * confunde y resta credibilidad a la oferta.
 *
 * Se usa para decidir si mostrar las llamadas comerciales de desbloqueo,
 * no para conceder acceso: eso lo decide accesoActividad().
 */
function convieneInvitarADesbloquear(): bool
{
    if (tieneCatalogoCompleto()) {
        return false;
    }
    return modoCatalogo() !== 'all-free';
}


// =====================================================================
//  MODO GLOBAL DEL CATÁLOGO
// =====================================================================

/**
 * Modo global configurado en el panel:
 *   partial    · funcionamiento normal freemium (por defecto)
 *   all-free   · todo abierto (útil para una campaña o demostración)
 *   all-locked · todo cerrado
 */
function modoCatalogo(): string
{
    $modo = ajuste('catalogo_modo_global', 'partial');
    return in_array($modo, ['partial', 'all-free', 'all-locked'], true) ? $modo : 'partial';
}


// =====================================================================
//  ACCESO A UNA ACTIVIDAD
// =====================================================================

/**
 * Evalúa qué acceso tiene el usuario actual sobre una actividad.
 *
 * @param array $actividad Fila de `activities` (necesita access_type y free_stations)
 * @return string ACCESO_TOTAL | ACCESO_LIMITADO | ACCESO_BLOQUEADO
 */
function accesoActividad(array $actividad): string
{
    // El administrador siempre ve todo, para poder revisar el contenido.
    if (esAdmin()) {
        return ACCESO_TOTAL;
    }

    /*
     * La ruta del estudiante manda por encima de TODO lo demás, incluido
     * el modo «todo abierto»: a un niño de una clase no se le abre el
     * catálogo entero porque haya una campaña en marcha.
     *
     * Y solo puede cerrar. Lo que sí se puede jugar lo siguen decidiendo
     * las líneas de abajo con el plan de siempre — si la ruta pudiera
     * conceder, cualquier docente asignaría contenido premium a su curso
     * y estaría repartiendo la biblioteca completa. Ver `ruta.php`.
     */
    if (function_exists('motivoDeRuta') && motivoDeRuta($actividad) !== null) {
        return ACCESO_BLOQUEADO;
    }

    /*
     * El modo niño, igual: cierra por encima de todo y no abre nada.
     *
     * Va junto a la ruta y no dentro de ella porque son dos cosas
     * distintas — la ruta es lo que un docente le asignó a la cuenta de
     * un alumno; esto es lo que un adulto eligió dejar a la vista en SU
     * PROPIA cuenta antes de prestarle la tableta a su hijo.
     *
     * Las dos pueden estar activas a la vez y las dos tienen que poder
     * cerrar: si un padre presta su cuenta y además hay ruta, lo que el
     * niño ve es lo que sobrevive a las dos.
     */
    if (function_exists('motivoDeModoNino') && motivoDeModoNino($actividad) !== null) {
        return ACCESO_BLOQUEADO;
    }

    $modo = modoCatalogo();
    if ($modo === 'all-free') {
        return ACCESO_TOTAL;
    }

    // Una suscripción vigente abre el 100% del catálogo, incluso en
    // modo all-locked: quien pagó no debe quedarse fuera.
    if (tieneCatalogoCompleto()) {
        return ACCESO_TOTAL;
    }

    if ($modo === 'all-locked') {
        return ACCESO_BLOQUEADO;
    }

    switch ($actividad['access_type'] ?? ACCESO_PARCIAL) {
        case ACCESO_LIBRE:
            return ACCESO_TOTAL;

        case ACCESO_PREMIUM:
            return ACCESO_BLOQUEADO;

        case ACCESO_PARCIAL:
        default:
            // Con 0 estaciones libres, "parcial" equivale a bloqueada.
            return ((int) ($actividad['free_stations'] ?? 0) > 0)
                ? ACCESO_LIMITADO
                : ACCESO_BLOQUEADO;
    }
}

/** ¿Puede el usuario al menos abrir la actividad? */
function puedeAbrirActividad(array $actividad): bool
{
    return accesoActividad($actividad) !== ACCESO_BLOQUEADO;
}


// =====================================================================
//  ACCESO A UNA ESTACIÓN  (el corazón del modelo 30%)
// =====================================================================

/**
 * ¿Hace falta tener cuenta para jugar?
 *
 * Sí, salvo que un administrador lo desactive desde los ajustes (útil
 * para una demostración o una feria). Es un ajuste y no una constante
 * porque es una decisión comercial, no técnica.
 *
 * Por qué se exige cuenta: sin ella el progreso no se puede guardar —no
 * hay a quién atribuirlo—, así que el niño repite estaciones ya hechas y
 * pierde sus estrellas al cerrar el navegador. Y el catálogo se puede
 * seguir mirando entero sin registrarse: lo que pide cuenta es empezar a
 * jugar, no mirar.
 */
function exigeCuentaParaJugar(): bool
{
    return ajuste('exigir_cuenta_para_jugar', '1') === '1';
}

/**
 * Por qué NO se puede jugar esta estación, o null si sí se puede.
 *
 * Devuelve el motivo y no solo un sí/no porque los dos casos piden
 * respuestas distintas: a quien no tiene cuenta hay que invitarlo a
 * registrarse (gratis), y a quien la tiene, a suscribirse. Confundirlos
 * es pedirle dinero a alguien que todavía no ha probado nada.
 *
 * @return string|null 'cuenta_requerida' | 'suscripcion_requerida' | null
 */
function motivoBloqueo(array $actividad, array $estacion): ?string
{
    // El administrador entra siempre, para poder revisar el contenido.
    if (esAdmin()) {
        return null;
    }

    if (exigeCuentaParaJugar() && usuarioActualId() === null) {
        return 'cuenta_requerida';
    }

    /*
     * La ruta antes que el plan, y con motivo propio.
     *
     * `accesoActividad()` ya devuelve BLOQUEADO por esto, pero si se
     * dejara caer hasta abajo el niño recibiría «suscríbete a la
     * Biblioteca Completa»: pedirle dinero a un menor por algo que su
     * docente simplemente no le mandó.
     */
    if (function_exists('motivoDeRuta')) {
        $deRuta = motivoDeRuta($actividad);

        if ($deRuta !== null) {
            return $deRuta;
        }
    }

    /*
     * Y el modo niño antes del plan, por lo mismo: si cayera hasta abajo,
     * el niño vería «suscríbete a la Biblioteca Completa» por una
     * actividad que su propio papá decidió no dejarle — y encima en una
     * cuenta que YA está suscrita.
     */
    if (function_exists('motivoDeModoNino')) {
        $delModo = motivoDeModoNino($actividad);

        if ($delModo !== null) {
            return $delModo;
        }
    }

    $acceso = accesoActividad($actividad);

    if ($acceso === ACCESO_TOTAL) {
        return null;
    }
    if ($acceso === ACCESO_BLOQUEADO) {
        return 'suscripcion_requerida';
    }

    // Acceso limitado: son gratuitas las primeras `free_stations`
    // estaciones, más cualquiera marcada manualmente como libre.
    if ((int) ($estacion['is_free'] ?? 0) === 1) {
        return null;
    }

    return (int) $estacion['position'] <= (int) ($actividad['free_stations'] ?? 0)
        ? null
        : 'suscripcion_requerida';
}

/**
 * ¿Puede el usuario jugar esta estación concreta?
 *
 * Este es el único punto por el que pasan todas las decisiones de juego:
 * la API que sirve el contenido, el mapa de estaciones y el guardado de
 * progreso. Cerrar aquí cierra en todas partes a la vez.
 *
 * @param array $actividad Fila de `activities`
 * @param array $estacion  Fila de `activity_stations` (position, is_free)
 */
function puedeJugarEstacion(array $actividad, array $estacion): bool
{
    return motivoBloqueo($actividad, $estacion) === null;
}

/** Textos de la invitación que corresponde a cada motivo de bloqueo. */
function invitacionPorMotivo(string $motivo): array
{
    /*
     * Los dos motivos de ruta hablan con un niño de seis años, no con
     * quien compra: nada de planes, nada de precios, y una sola cosa que
     * hacer. Van primero para que ninguno caiga por descuido en el texto
     * comercial de más abajo.
     */
    if ($motivo === 'fuera_de_ruta') {
        return [
            'ok'      => false,
            'motivo'  => 'fuera_de_ruta',
            'titulo'  => 'Esta no es de tu clase',
            'mensaje' => 'Tu profe todavía no te la ha mandado. Mira lo que sí tienes '
                       . 'para hacer hoy.',
            'accion'  => ['texto' => 'Ver mis actividades', 'url' => url('usuario/ruta.php')],
        ];
    }

    if ($motivo === 'ruta_pendiente') {
        $falta = function_exists('pasoQueFalta') ? pasoQueFalta() : null;

        return [
            'ok'      => false,
            'motivo'  => 'ruta_pendiente',
            'titulo'  => 'Todavía no te toca esta',
            'mensaje' => $falta
                ? 'Primero termina «' . $falta['title'] . '».'
                : 'Primero termina la actividad anterior.',
            'accion'  => ['texto' => 'Ir a la que me toca', 'url' => url('usuario/ruta.php')],
        ];
    }

    /*
     * También le habla al niño, no a quien compra. Aquí además sería
     * absurdo mencionar planes: la cuenta está suscrita — lo que pasa es
     * que su papá eligió qué dejarle.
     */
    if ($motivo === 'fuera_del_modo_nino') {
        return [
            'ok'      => false,
            'motivo'  => 'fuera_del_modo_nino',
            'titulo'  => 'Esta no está en tu lista',
            'mensaje' => 'Aquí tienes las actividades que te dejaron preparadas.',
            'accion'  => ['texto' => 'Ver mis actividades', 'url' => url('actividades/')],
        ];
    }

    if ($motivo === 'cuenta_requerida') {
        return [
            'ok'      => false,
            'motivo'  => 'cuenta_requerida',
            'titulo'  => 'Crea tu cuenta para empezar a jugar',
            'mensaje' => 'Es gratis y toma un minuto. Con tu cuenta guardamos tu progreso, '
                       . 'tus estrellas y por dónde ibas.',
            'accion'  => ['texto' => 'Crear cuenta gratis', 'url' => url('registro.php')],
        ];
    }

    return [
        'ok'      => false,
        'motivo'  => 'suscripcion_requerida',
        'titulo'  => 'Esta experiencia hace parte de la Biblioteca Completa.',
        'mensaje' => 'Desbloquea todas las actividades actuales y las nuevas que publiquemos '
                   . 'durante tu suscripción.',
        'accion'  => ['texto' => 'Ver Biblioteca Completa', 'url' => url('planes/')],
    ];
}

/**
 * Prepara la lista de estaciones para enviarla al navegador.
 *
 * Las estaciones bloqueadas se devuelven —el usuario debe VERLAS para
 * saber qué se está perdiendo— pero sin su `config`. Los datos del
 * minijuego (palabras, imágenes, respuestas) no salen del servidor.
 * Es la diferencia entre un candado real y un candado dibujado.
 *
 * @param array $actividad  Fila de `activities`
 * @param array $estaciones Filas de `activity_stations`, ordenadas por position
 */
function prepararEstaciones(array $actividad, array $estaciones): array
{
    $preparadas = [];

    foreach ($estaciones as $estacion) {
        $desbloqueada = puedeJugarEstacion($actividad, $estacion);

        $preparadas[] = [
            'id'           => (int) $estacion['id'],
            'posicion'     => (int) $estacion['position'],
            'titulo'       => $estacion['title'],
            'descripcion'  => $estacion['description'],
            'icono'        => $estacion['icon'],
            'tipo'         => $estacion['game_type'],
            'desbloqueada' => $desbloqueada,
            // Aquí está la protección: el contenido solo viaja si el
            // usuario tiene derecho a jugarlo.
            'config'       => $desbloqueada
                ? json_decode($estacion['config'] ?? 'null', true)
                : null,
        ];
    }

    return $preparadas;
}

/**
 * Corta la petición si el usuario no puede jugar esta estación.
 * Se usa en los endpoints de api/ que sirven el contenido del minijuego.
 */
function exigirAccesoEstacion(array $actividad, array $estacion): void
{
    $motivo = motivoBloqueo($actividad, $estacion);

    if ($motivo === null) {
        return;
    }

    // 401 cuando falta la cuenta y 403 cuando falta la suscripción: son
    // dos situaciones distintas y el navegador debe poder distinguirlas
    // aunque el cuerpo de la respuesta se pierda por el camino.
    http_response_code($motivo === 'cuenta_requerida' ? 401 : 403);
    header('Content-Type: application/json; charset=utf-8');
    echo jsonSeguro(invitacionPorMotivo($motivo));
    exit;
}


// =====================================================================
//  CONSULTAS DE CATÁLOGO CON CONTEXTO DE ACCESO
// =====================================================================

/**
 * Resumen de lo que el plan del usuario le permite hacer, en números.
 *
 * Sirve para decírselo con claridad en su espacio personal: no «tienes
 * el plan Gratis», sino «juegas 169 de las 506 estaciones». Un plan se
 * entiende mejor por lo que abre que por su nombre.
 */
function resumenAccesoUsuario(): array
{
    $totalActividades = (int) traerValor(
        'SELECT COUNT(1) FROM activities WHERE status = "published"'
    );

    $totalEstaciones = (int) traerValor(
        'SELECT COUNT(1) FROM activity_stations s
           JOIN activities a ON a.id = s.activity_id
          WHERE a.status = "published"'
    );

    $completo = tieneCatalogoCompleto();

    if ($completo) {
        $disponibles = $totalEstaciones;
    } else {
        // Se cuentan las estaciones que caen dentro del corte gratuito de
        // cada actividad, más las marcadas como excepción.
        $disponibles = (int) traerValor(
            'SELECT COUNT(1)
               FROM activity_stations s
               JOIN activities a ON a.id = s.activity_id
              WHERE a.status = "published"
                AND (
                    a.access_type = "free"
                    OR s.is_free = 1
                    OR (a.access_type = "partial" AND s.position <= a.free_stations)
                )'
        );

        // En modo «todo abierto» no hay nada bloqueado.
        if (modoCatalogo() === 'all-free') {
            $disponibles = $totalEstaciones;
        }
    }

    $suscripcion = suscripcionVigente();
    $plan = $suscripcion
        ? traerUno('SELECT name, slug FROM plans WHERE id = ?', [$suscripcion['plan_id']])
        : traerUno('SELECT name, slug FROM plans WHERE slug = ?', [PLAN_FREE]);

    return [
        'plan'          => $plan['name'] ?? 'Gratis',
        'plan_slug'     => $plan['slug'] ?? PLAN_FREE,
        'completo'      => $completo,
        'actividades'   => $totalActividades,
        'estaciones'    => $totalEstaciones,
        'disponibles'   => $disponibles,
        'bloqueadas'    => max(0, $totalEstaciones - $disponibles),
        'porcentaje'    => $totalEstaciones > 0
            ? (int) round($disponibles * 100 / $totalEstaciones)
            : 0,
        'vence'         => $suscripcion['expires_at'] ?? null,
    ];
}

/**
 * Cuenta cuántas estaciones tiene una actividad y cuántas puede jugar
 * el usuario actual. Alimenta la etiqueta "3 de 15 estaciones libres".
 */
function resumenEstaciones(array $actividad): array
{
    $total = (int) traerValor(
        'SELECT COUNT(*) FROM activity_stations WHERE activity_id = ?',
        [$actividad['id']]
    );

    $acceso = accesoActividad($actividad);

    if ($acceso === ACCESO_TOTAL) {
        $disponibles = $total;
    } elseif ($acceso === ACCESO_BLOQUEADO) {
        $disponibles = 0;
    } else {
        $disponibles = min((int) ($actividad['free_stations'] ?? 0), $total);
    }

    return [
        'total'        => $total,
        'disponibles'  => $disponibles,
        'bloqueadas'   => max(0, $total - $disponibles),
        'acceso'       => $acceso,
    ];
}

/**
 * Texto de la etiqueta de acceso que se muestra en cada tarjeta del
 * catálogo. Un usuario con catálogo completo no ve etiquetas de candado.
 */
function etiquetaAcceso(array $actividad): array
{
    switch (accesoActividad($actividad)) {
        case ACCESO_TOTAL:
            return tieneCatalogoCompleto()
                ? ['texto' => 'Incluida', 'clase' => 'incluida', 'icono' => '✓']
                : ['texto' => 'Gratis',   'clase' => 'gratis',   'icono' => '🎁'];

        case ACCESO_LIMITADO:
            $r = resumenEstaciones($actividad);
            return [
                'texto' => $r['total'] > 0
                    ? "{$r['disponibles']} de {$r['total']} gratis"
                    : 'Prueba gratis',
                'clase' => 'parcial',
                'icono' => '🎁',
            ];

        default:
            return ['texto' => 'Premium', 'clase' => 'premium', 'icono' => '🔒'];
    }
}
