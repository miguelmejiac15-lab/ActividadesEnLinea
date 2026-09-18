<?php
/**
 * modo-nino.php — La cuenta prestada a un niño.
 *
 * =====================================================================
 *  ESTO SOLO CIERRA. NUNCA ABRE.
 * =====================================================================
 *
 * Es la misma regla de `ruta.php` y por la misma razón. El modo niño se
 * cuelga del cálculo normal de acceso y solo puede devolver «esto no». Lo
 * que sí se puede jugar lo sigue decidiendo `acceso.php` con el plan de
 * siempre.
 *
 * Si pudiera conceder, bastaría con encender el modo niño y meter en la
 * selección una actividad premium para tener la biblioteca gratis.
 * Restringir y conceder son dos poderes muy distintos, y este archivo
 * solo tiene el primero.
 *
 * =====================================================================
 *  UNA SOLA CUENTA
 * =====================================================================
 *
 * El adulto y el niño son el mismo usuario: el mismo progreso, las mismas
 * monedas, el mismo personaje. No hay matrícula, no hay curso y no hay
 * segunda suscripción — es una preferencia de la cuenta.
 *
 * Por eso no se reutilizó la ruta del estudiante, que necesita dos
 * cuentas: la del docente que asigna y la del niño que juega.
 *
 * =====================================================================
 *  EL PIN PROTEGE LA SALIDA, NO LA ENTRADA
 * =====================================================================
 *
 * Encender el modo no pide nada: quien está delante es el adulto, que ya
 * entró con su contraseña. Apagarlo sí, porque para entonces quien está
 * delante puede ser el niño.
 *
 * Es al revés que `users.pin_hash`, que protege la ENTRADA de un
 * estudiante a su clase. Por eso son dos columnas y no una.
 */

declare(strict_types=1);


// =====================================================================
//  INSTALACIÓN
// =====================================================================

/** ¿Está hecha la migración? Permite convivir con instalaciones viejas. */
function modoNinoInstalado(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "users"
                AND COLUMN_NAME = "child_mode_on"'
        );
    }

    return $listo;
}


// =====================================================================
//  ESTADO
// =====================================================================

/**
 * El estado del modo para una cuenta, consultado una sola vez.
 *
 * Se guarda por referencia en una estática porque `accesoActividad()` se
 * llama una vez por tarjeta del catálogo: sin caché, abrir el catálogo
 * serían cuatrocientas consultas.
 */
function &cacheDeModoNino(): array
{
    static $cache = ['listo' => false, 'activo' => false, 'ids' => [], 'usuario' => null];
    return $cache;
}

/** Olvida lo consultado. Se llama al cambiar la selección o el estado. */
function olvidarModoNino(): void
{
    $cache = &cacheDeModoNino();
    $cache = ['listo' => false, 'activo' => false, 'ids' => [], 'usuario' => null];
}

/** Carga el estado del usuario de la sesión, si hace falta. */
function cargarModoNino(): array
{
    $cache = &cacheDeModoNino();

    $usuarioId = usuarioActualId();

    // Si cambió el usuario de la sesión, lo de antes ya no vale.
    if ($cache['listo'] && $cache['usuario'] === $usuarioId) {
        return $cache;
    }

    $cache = ['listo' => true, 'activo' => false, 'ids' => [], 'usuario' => $usuarioId];

    if ($usuarioId === null || !modoNinoInstalado()) {
        return $cache;
    }

    $encendido = (int) traerValor(
        'SELECT child_mode_on FROM users WHERE id = ?', [$usuarioId]);

    if ($encendido !== 1) {
        return $cache;
    }

    $ids = array_map('intval', array_column(traerTodo(
        'SELECT activity_id FROM child_mode_activities WHERE user_id = ?',
        [$usuarioId]
    ), 'activity_id'));

    /*
     * Encendido pero con la selección vacía NO cierra nada.
     *
     * Es la misma decisión que toma la ruta con un estudiante sin
     * actividades asignadas: dejar a un niño ante un catálogo vacío, sin
     * nada que tocar y sin saber por qué, es peor que no haber encendido
     * el modo. Si no hay selección, el modo no está haciendo su trabajo.
     */
    if (!$ids) {
        return $cache;
    }

    $cache['activo'] = true;
    $cache['ids']    = $ids;

    return $cache;
}

/** ¿Está la cuenta actual en modo niño, con selección de verdad? */
function enModoNino(): bool
{
    $c = cargarModoNino();
    return $c['activo'];
}

/** Los ids que el adulto eligió. Vacío si el modo no está activo. */
function actividadesDelModoNino(): array
{
    $c = cargarModoNino();
    return $c['ids'];
}

/** ¿Tiene esta cuenta un PIN puesto? */
function tienePinDeModoNino(?int $usuarioId = null): bool
{
    if (!modoNinoInstalado()) {
        return false;
    }

    $usuarioId = $usuarioId ?? usuarioActualId();

    if ($usuarioId === null) {
        return false;
    }

    return traerValor('SELECT child_mode_pin FROM users WHERE id = ?', [$usuarioId]) !== null;
}


// =====================================================================
//  LA PUERTA
// =====================================================================

/**
 * ¿Por qué no puede entrar a esta actividad?
 *
 * Devuelve `'fuera_del_modo_nino'` o null. Se engancha en
 * `accesoActividad()` junto a `motivoDeRuta()`.
 *
 * El administrador queda fuera: necesita ver todo el catálogo para
 * revisarlo, y si encendiera el modo en su cuenta se quedaría sin poder
 * hacer su trabajo.
 */
function motivoDeModoNino(array $actividad): ?string
{
    if (function_exists('esAdmin') && esAdmin()) {
        return null;
    }

    if (!enModoNino()) {
        return null;
    }

    $id = (int) ($actividad['id'] ?? 0);

    if ($id <= 0) {
        return null;
    }

    return in_array($id, actividadesDelModoNino(), true) ? null : 'fuera_del_modo_nino';
}

/**
 * El trozo de SQL que recorta un listado a la selección.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ UN FRAGMENTO Y NO UN FILTRO EN PHP
 * ---------------------------------------------------------------------
 *
 * Filtrar después de consultar rompería la paginación y los contadores:
 * el catálogo diría «24 actividades» y pintaría tres. El recorte tiene
 * que ir dentro de la consulta para que el total también sea verdad.
 *
 * Devuelve `['sql' => '', 'params' => []]` cuando el modo no está activo,
 * así quien lo usa puede pegarlo siempre sin preguntar.
 *
 * @param string $alias  el alias de `activities` en esa consulta
 * @return array{sql:string, params:array}
 */
function filtroDeModoNino(string $alias = 'a'): array
{
    if (function_exists('esAdmin') && esAdmin()) {
        return ['sql' => '', 'params' => []];
    }

    if (!enModoNino()) {
        return ['sql' => '', 'params' => []];
    }

    $ids = actividadesDelModoNino();

    /*
     * Los marcadores se generan a partir del NÚMERO de ids, y los ids van
     * como parámetros. Nunca se interpolan: aunque hoy vengan de la base
     * de datos y sean enteros, el día que alguien los pase desde un
     * formulario esto tiene que seguir siendo seguro.
     */
    $marcadores = implode(', ', array_fill(0, count($ids), '?'));

    return [
        'sql'    => " AND {$alias}.id IN ($marcadores)",
        'params' => $ids,
    ];
}


// =====================================================================
//  LA SELECCIÓN
// =====================================================================

/** Lo que el adulto eligió, con sus datos, para pintarlo. */
function seleccionDeModoNino(?int $usuarioId = null): array
{
    if (!modoNinoInstalado()) {
        return [];
    }

    $usuarioId = $usuarioId ?? usuarioActualId();

    if ($usuarioId === null) {
        return [];
    }

    return traerTodo(
        'SELECT a.id, a.slug, a.title, a.icon, a.access_type, a.free_stations,
                c.name AS categoria, c.slug AS categoria_slug,
                l.name AS nivel,
                m.sort_order,
                (SELECT COUNT(*) FROM activity_stations s
                  WHERE s.activity_id = a.id) AS estaciones
           FROM child_mode_activities m
           JOIN activities a ON a.id = m.activity_id AND a.status = "published"
      LEFT JOIN categories c ON c.id = a.category_id
      LEFT JOIN levels     l ON l.id = a.level_id
          WHERE m.user_id = ?
       ORDER BY m.sort_order ASC, a.title ASC',
        [$usuarioId]
    );
}

/**
 * Añade una actividad a la selección.
 *
 * Es idempotente: añadir dos veces la misma no la duplica ni falla, lo
 * garantiza la clave primaria compuesta.
 */
function agregarAlModoNino(int $usuarioId, int $actividadId): bool
{
    if (!modoNinoInstalado() || $usuarioId <= 0 || $actividadId <= 0) {
        return false;
    }

    // Que exista y esté publicada. Una actividad en borrador dentro de la
    // selección sería un hueco que el niño no entendería.
    $existe = traerValor(
        'SELECT id FROM activities WHERE id = ? AND status = "published"',
        [$actividadId]);

    if (!$existe) {
        return false;
    }

    $siguiente = (int) traerValor(
        'SELECT COALESCE(MAX(sort_order), 0) + 1 FROM child_mode_activities
          WHERE user_id = ?', [$usuarioId]);

    ejecutar(
        'INSERT INTO child_mode_activities (user_id, activity_id, sort_order)
         VALUES (?, ?, ?)
         ON DUPLICATE KEY UPDATE sort_order = sort_order',
        [$usuarioId, $actividadId, $siguiente]
    );

    olvidarModoNino();

    return true;
}

/** Quita una actividad de la selección. */
function quitarDelModoNino(int $usuarioId, int $actividadId): bool
{
    if (!modoNinoInstalado() || $usuarioId <= 0) {
        return false;
    }

    ejecutar(
        'DELETE FROM child_mode_activities WHERE user_id = ? AND activity_id = ?',
        [$usuarioId, $actividadId]
    );

    olvidarModoNino();

    return true;
}

/** Vacía la selección entera. */
function vaciarModoNino(int $usuarioId): void
{
    if (!modoNinoInstalado() || $usuarioId <= 0) {
        return;
    }

    ejecutar('DELETE FROM child_mode_activities WHERE user_id = ?', [$usuarioId]);
    olvidarModoNino();
}

/**
 * Añade de golpe todas las actividades de un bloque o de una categoría.
 *
 * Elegir de una en una es insufrible con 487 actividades, y un adulto que
 * se cansa a la tercera acaba por no usar el modo.
 */
function agregarGrupoAlModoNino(int $usuarioId, string $tipo, string $slug): int
{
    if (!modoNinoInstalado() || $usuarioId <= 0) {
        return 0;
    }

    $columna = match ($tipo) {
        'bloque'    => 'b.slug',
        'categoria' => 'c.slug',
        'nivel'     => 'l.slug',
        default     => null,
    };

    if ($columna === null) {
        return 0;
    }

    $filas = traerTodo(
        "SELECT a.id
           FROM activities a
      LEFT JOIN categories  c ON c.id = a.category_id
      LEFT JOIN collections b ON b.id = a.collection_id
      LEFT JOIN levels      l ON l.id = a.level_id
          WHERE a.status = 'published' AND $columna = ?",
        [$slug]
    );

    $puestas = 0;

    foreach ($filas as $f) {
        if (agregarAlModoNino($usuarioId, (int) $f['id'])) {
            $puestas++;
        }
    }

    return $puestas;
}


// =====================================================================
//  ENCENDER Y APAGAR
// =====================================================================

/**
 * Guarda el PIN con el que se saldrá del modo.
 *
 * Cuatro dígitos: es el que va a teclear un adulto delante de un niño
 * impaciente, no la contraseña de un banco. Se guarda hasheado igual que
 * cualquier contraseña — que sea corto no justifica guardarlo en claro.
 */
function guardarPinDeModoNino(int $usuarioId, string $pin): array
{
    if (!modoNinoInstalado()) {
        return ['ok' => false, 'error' => 'El modo niño todavía no está instalado.'];
    }

    $pin = trim($pin);

    if (preg_match('/^\d{4}$/', $pin) !== 1) {
        return ['ok' => false, 'error' => 'El PIN son cuatro números, del 0 al 9.'];
    }

    ejecutar('UPDATE users SET child_mode_pin = ? WHERE id = ?',
        [password_hash($pin, PASSWORD_DEFAULT), $usuarioId]);

    return ['ok' => true, 'error' => null];
}

/**
 * Enciende el modo.
 *
 * Pide dos cosas y las dos son para no dejar a nadie encerrado: que haya
 * selección —si no, el niño se queda ante un catálogo vacío— y que haya
 * PIN —si no, el modo no se podría apagar nunca—.
 */
function encenderModoNino(int $usuarioId): array
{
    if (!modoNinoInstalado()) {
        return ['ok' => false, 'error' => 'El modo niño todavía no está instalado.'];
    }

    $cuantas = (int) traerValor(
        'SELECT COUNT(*) FROM child_mode_activities m
           JOIN activities a ON a.id = m.activity_id AND a.status = "published"
          WHERE m.user_id = ?', [$usuarioId]);

    if ($cuantas === 0) {
        return ['ok' => false,
                'error' => 'Primero elige al menos una actividad. Si no, tu hijo no '
                         . 'encontraría nada que hacer.'];
    }

    if (!tienePinDeModoNino($usuarioId)) {
        return ['ok' => false,
                'error' => 'Primero pon un PIN de cuatro números. Sin él no podrías '
                         . 'volver a salir del modo niño.'];
    }

    ejecutar('UPDATE users SET child_mode_on = 1 WHERE id = ?', [$usuarioId]);
    olvidarModoNino();

    return ['ok' => true, 'error' => null];
}

/**
 * Apaga el modo, comprobando el PIN.
 *
 * Es la única puerta de salida, así que aquí no hay atajos: sin PIN
 * correcto no se apaga, y da igual quién lo pida.
 */
function apagarModoNino(int $usuarioId, string $pin): array
{
    if (!modoNinoInstalado()) {
        return ['ok' => false, 'error' => 'El modo niño todavía no está instalado.'];
    }

    $hash = traerValor('SELECT child_mode_pin FROM users WHERE id = ?', [$usuarioId]);

    /*
     * Sin PIN guardado se puede salir sin más.
     *
     * No es un agujero: para encender hace falta PIN, así que llegar aquí
     * sin él significa que alguien lo borró por la base de datos. Dejar la
     * cuenta encerrada para siempre sería peor que dejarla salir.
     */
    if ($hash === null) {
        ejecutar('UPDATE users SET child_mode_on = 0 WHERE id = ?', [$usuarioId]);
        olvidarModoNino();
        return ['ok' => true, 'error' => null];
    }

    if (!password_verify(trim($pin), (string) $hash)) {
        return ['ok' => false, 'error' => 'Ese PIN no es.'];
    }

    ejecutar('UPDATE users SET child_mode_on = 0 WHERE id = ?', [$usuarioId]);
    olvidarModoNino();

    return ['ok' => true, 'error' => null];
}

/**
 * Corta si la cuenta está en modo niño.
 *
 * Para las páginas que mueven dinero o tocan los datos de la cuenta. Un
 * niño con la tableta de su papá no debe poder contratar un plan ni
 * cambiar el correo de la cuenta.
 *
 * Es el mismo criterio que `exigirSesionPlena()` aplica a la sesión de
 * aula, y por el mismo motivo.
 */
function exigirFueraDeModoNino(): void
{
    if (!enModoNino()) {
        return;
    }

    mensaje('info',
        'La cuenta está en modo niño. Sal del modo con tu PIN para entrar aquí.');
    redirigir('usuario/modo-nino.php');
}
