<?php
/**
 * gamificacion.php — Monedas, estrellas, racha, logros y tienda
 *
 * ─────────────────────────────────────────────────────────────────────
 *  PRINCIPIO: NADA SE GUARDA SI SE PUEDE CALCULAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * No hay columna «saldo», ni «racha_actual», ni «logros_obtenidos». Todo
 * sale de `activity_progress`, que ya registra qué hizo el niño y cuándo.
 *
 * La razón no es purismo: un contador guardado se desincroniza en cuanto
 * algo falla a mitad —una petición que se corta, un despliegue, una fila
 * borrada— y a partir de ahí miente para siempre, sin que nadie lo note.
 * Un número calculado no puede mentir: si el niño jugó, está ahí.
 *
 * La única excepción es el gasto, que sí necesita quedar escrito porque
 * no se deduce de nada (`user_items`).
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ LAS MONEDAS NO SE PUEDEN FARMEAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * `api/progreso.php` guarda con `GREATEST(coins, VALUES(coins))`: repetir
 * una estación fácil cien veces deja las monedas de la mejor vez, no cien
 * veces las mismas. La economía se sostiene sola, sin vigilancia.
 */

declare(strict_types=1);


// =====================================================================
//  BILLETERA
// =====================================================================

/**
 * Monedas y estrellas del usuario.
 *
 * @return array{ganadas:int, gastadas:int, saldo:int, estrellas:int,
 *                estaciones:int, actividades:int}
 */
function billetera(?int $usuarioId = null): array
{
    $usuarioId ??= usuarioActualId();

    if ($usuarioId === null) {
        return ['ganadas' => 0, 'gastadas' => 0, 'saldo' => 0,
                'estrellas' => 0, 'estaciones' => 0, 'actividades' => 0];
    }

    /*
     * `COUNT(DISTINCT station_id)` y no `COUNT(1)`.
     *
     * Desde que el progreso distingue contexto puede haber dos filas para
     * la misma estación —la personal y la del curso—, y contar filas le
     * diría al niño que ha terminado el doble de estaciones de las que
     * ha jugado.
     *
     * Las monedas y las estrellas SÍ se suman tal cual: `api/progreso.php`
     * ya se encarga de que una estación pague una sola vez, así que la
     * segunda fila viene con ceros.
     */
    $p = traerUno(
        'SELECT COALESCE(SUM(coins), 0)  AS monedas,
                COALESCE(SUM(stars), 0)  AS estrellas,
                COUNT(DISTINCT station_id)  AS estaciones,
                COUNT(DISTINCT activity_id) AS actividades
           FROM activity_progress
          WHERE user_id = ? AND status = "completed"',
        [$usuarioId]
    );

    $ganadas = (int) ($p['monedas'] ?? 0);

    // Se gasta en dos sitios: la tienda (compras únicas) y las ayudas
    // dentro de una actividad (repetibles). Los dos cuentan.
    $gastadas = (int) traerValor(
        'SELECT COALESCE(SUM(paid_coins), 0) FROM user_items WHERE user_id = ?',
        [$usuarioId]
    ) + (int) traerValor(
        'SELECT COALESCE(SUM(coins), 0) FROM coin_spends WHERE user_id = ?',
        [$usuarioId]
    );

    return [
        'ganadas'     => $ganadas,
        'gastadas'    => $gastadas,
        // Nunca negativo: si algo saliera mal, el niño no debe quedar en
        // números rojos por un fallo nuestro.
        'saldo'       => max(0, $ganadas - $gastadas),
        'estrellas'   => (int) ($p['estrellas'] ?? 0),
        'estaciones'  => (int) ($p['estaciones'] ?? 0),
        'actividades' => (int) ($p['actividades'] ?? 0),
    ];
}


// =====================================================================
//  AYUDAS DENTRO DE UNA ACTIVIDAD
//
//  Dos formas de gastar monedas cuando el niño se atasca. Ninguna le
//  vende avanzar sin aprender:
//
//    PISTA   descarta una opción equivocada. Sigue teniendo que elegir.
//    SALTAR  deja pasar a la estación siguiente, pero la registra SIN
//            estrellas y SIN monedas. Desatasca; no regala mérito.
//
//  El precio de saltar es mayor que lo que la estación habría dado, así
//  que saltar siempre sale a pérdida. Es una salida de emergencia, no
//  una estrategia — y menos aún una forma de farmear progreso.
// =====================================================================

const PRECIO_PISTA  = 5;
const PRECIO_SALTAR = 12;

/**
 * Cuántas veces hay que fallar antes de que se ofrezca ayuda.
 *
 * Tres, no una: equivocarse forma parte de aprender, y ofrecer la salida
 * al primer error enseña a comprarla en vez de a intentarlo.
 */
const FALLOS_PARA_AYUDA = 3;

/** Precio de cada ayuda, para el navegador. */
function preciosAyuda(): array
{
    return ['pista' => PRECIO_PISTA, 'saltar' => PRECIO_SALTAR];
}

/**
 * Cobra una ayuda. Devuelve ['ok'=>bool, 'error'=>?string, 'saldo'=>int].
 *
 * El saldo se lee del servidor, nunca del navegador: quien manipule el
 * JavaScript solo consigue que le respondan que no le alcanza.
 */
function gastarMonedas(string $tipo, ?int $estacionId = null): array
{
    $usuarioId = usuarioActualId();

    if ($usuarioId === null) {
        return ['ok' => false, 'error' => 'Necesitas una cuenta.', 'saldo' => 0];
    }

    $precios = preciosAyuda();

    if (!isset($precios[$tipo])) {
        return ['ok' => false, 'error' => 'Esa ayuda no existe.', 'saldo' => 0];
    }

    $precio = $precios[$tipo];
    $saldo  = billetera($usuarioId)['saldo'];

    if ($precio > $saldo) {
        return ['ok' => false, 'error' => 'No te alcanzan las monedas.', 'saldo' => $saldo];
    }

    insertar(
        'INSERT INTO coin_spends (user_id, kind, station_id, coins) VALUES (?, ?, ?, ?)',
        [$usuarioId, $tipo, $estacionId, $precio]
    );

    return ['ok' => true, 'error' => null, 'saldo' => $saldo - $precio];
}


// =====================================================================
//  RACHA
// =====================================================================

/**
 * Días seguidos jugando.
 *
 * La racha NO se rompe al terminar el día: se rompe cuando pasa un día
 * entero sin jugar. Es decir, cuenta si el último día jugado fue hoy o
 * ayer. Con la regla estricta, un niño que juega cada tarde vería su
 * racha en cero cada mañana antes de empezar, que es desalentar
 * exactamente a quien está cumpliendo.
 *
 * @return array{actual:int, mejor:int, dias:array, hoy:bool}
 */
function racha(?int $usuarioId = null): array
{
    $usuarioId ??= usuarioActualId();

    if ($usuarioId === null) {
        return ['actual' => 0, 'mejor' => 0, 'dias' => [], 'hoy' => false];
    }

    $filas = traerTodo(
        'SELECT DISTINCT DATE(completed_at) AS dia
           FROM activity_progress
          WHERE user_id = ? AND completed_at IS NOT NULL
       ORDER BY dia DESC',
        [$usuarioId]
    );

    $dias = array_map(static fn($f) => $f['dia'], $filas);

    if (!$dias) {
        return ['actual' => 0, 'mejor' => 0, 'dias' => [], 'hoy' => false];
    }

    $hoy   = new DateTimeImmutable('today');
    $ayer  = $hoy->modify('-1 day');
    $ultimo = new DateTimeImmutable($dias[0]);

    // ── Racha actual ─────────────────────────────────────────────────
    $actual = 0;

    if ($ultimo >= $ayer) {
        $actual   = 1;
        $anterior = $ultimo;

        foreach (array_slice($dias, 1) as $d) {
            $dia = new DateTimeImmutable($d);
            if ($anterior->modify('-1 day') == $dia) {
                $actual++;
                $anterior = $dia;
            } else {
                break;
            }
        }
    }

    // ── Mejor racha histórica ────────────────────────────────────────
    $mejor = 1;
    $seguidos = 1;
    for ($i = 1; $i < count($dias); $i++) {
        $a = new DateTimeImmutable($dias[$i - 1]);
        $b = new DateTimeImmutable($dias[$i]);

        if ($a->modify('-1 day') == $b) {
            $seguidos++;
            $mejor = max($mejor, $seguidos);
        } else {
            $seguidos = 1;
        }
    }

    return [
        'actual' => $actual,
        'mejor'  => max($mejor, $actual),
        'dias'   => $dias,
        'hoy'    => $dias[0] === $hoy->format('Y-m-d'),
    ];
}

/** Los siete últimos días, para pintar la tira de la semana. */
function semanaDeRacha(?int $usuarioId = null): array
{
    $r = racha($usuarioId);
    $jugados = array_flip($r['dias']);

    $semana = [];
    for ($i = 6; $i >= 0; $i--) {
        $d = (new DateTimeImmutable('today'))->modify("-$i day");
        $semana[] = [
            'fecha'  => $d->format('Y-m-d'),
            'letra'  => mb_substr(['D', 'L', 'M', 'X', 'J', 'V', 'S'][(int) $d->format('w')], 0, 1),
            'jugado' => isset($jugados[$d->format('Y-m-d')]),
            'es_hoy' => $i === 0,
        ];
    }

    return $semana;
}


// =====================================================================
//  LOGROS
//
//  Se definen aquí y se comprueban contra el progreso. No hay tabla de
//  «logros obtenidos»: añadir un logro nuevo lo concede retroactivamente
//  a quien ya cumplía, que es lo justo — el niño hizo el trabajo.
// =====================================================================

/**
 * @return array Lista de ['slug','nombre','emoji','pista','logrado','meta','hecho']
 */
function logros(?int $usuarioId = null): array
{
    $b = billetera($usuarioId);
    $r = racha($usuarioId);

    $usuarioId ??= usuarioActualId();

    // Cuántas actividades terminó por completo (todas sus estaciones).
    $completas = $usuarioId === null ? 0 : (int) traerValor(
        'SELECT COUNT(1) FROM (
             SELECT a.id
               FROM activities a
               JOIN activity_progress p ON p.activity_id = a.id AND p.user_id = ?
                                       AND p.status = "completed"
           GROUP BY a.id
             HAVING COUNT(p.id) >= (SELECT COUNT(1) FROM activity_stations s
                                     WHERE s.activity_id = a.id)
         ) AS t',
        [$usuarioId]
    );

    // En cuántas materias distintas ha jugado algo.
    $materias = $usuarioId === null ? 0 : (int) traerValor(
        'SELECT COUNT(DISTINCT a.category_id)
           FROM activity_progress p
           JOIN activities a ON a.id = p.activity_id
          WHERE p.user_id = ? AND p.status = "completed"',
        [$usuarioId]
    );

    // Estaciones resueltas con las tres estrellas.
    $perfectas = $usuarioId === null ? 0 : (int) traerValor(
        'SELECT COUNT(1) FROM activity_progress
          WHERE user_id = ? AND stars >= 3',
        [$usuarioId]
    );

    /*
     * Las metas suben despacio al principio. El primer logro se consigue
     * terminando UNA estación: quien acaba de empezar necesita ver que
     * el sistema le responde, no una lista de cosas lejanas.
     */
    $definiciones = [
        ['primer-paso',  'Primer paso',      '👣', 'Termina tu primera estación',        $b['estaciones'], 1],
        ['diez',         'Van diez',         '🔟', 'Termina 10 estaciones',              $b['estaciones'], 10],
        ['cincuenta',    'Medio centenar',   '🏅', 'Termina 50 estaciones',              $b['estaciones'], 50],
        ['cien',         'Cien estaciones',  '💯', 'Termina 100 estaciones',             $b['estaciones'], 100],

        ['completista',  'De principio a fin', '✅', 'Termina una actividad entera',     $completas, 1],
        ['cinco-enteras', 'Cinco enteras',   '🎯', 'Termina 5 actividades completas',    $completas, 5],

        ['tres-estrellas', 'Sin fallar',     '⭐', 'Consigue 3 estrellas en una estación', $perfectas, 1],
        ['diez-perfectas', 'Puntería',       '🌟', 'Consigue 3 estrellas en 10 estaciones', $perfectas, 10],

        ['racha-3',      'Tres días',        '🔥', 'Juega 3 días seguidos',              $r['mejor'], 3],
        ['racha-7',      'Una semana',       '🔥', 'Juega 7 días seguidos',              $r['mejor'], 7],

        ['explorador',   'Explorador',       '🧭', 'Juega en 3 materias distintas',      $materias, 3],
        ['trotamundos',  'Trotamundos',      '🌍', 'Juega en 6 materias distintas',      $materias, 6],

        ['ahorrador',    'Ahorrador',        '🪙', 'Reúne 100 monedas',                  $b['ganadas'], 100],
    ];

    $salida = [];
    foreach ($definiciones as [$slug, $nombre, $emoji, $pista, $hecho, $meta]) {
        $salida[] = [
            'slug'    => $slug,
            'nombre'  => $nombre,
            'emoji'   => $emoji,
            'pista'   => $pista,
            'hecho'   => min($hecho, $meta),
            'meta'    => $meta,
            'logrado' => $hecho >= $meta,
        ];
    }

    return $salida;
}

/** Cuántos logros lleva conseguidos, de cuántos hay. */
function resumenLogros(?int $usuarioId = null): array
{
    $todos = logros($usuarioId);
    $hechos = array_filter($todos, static fn($l) => $l['logrado']);

    return ['hechos' => count($hechos), 'total' => count($todos), 'lista' => $todos];
}


// =====================================================================
//  PERSONAJE Y TIENDA
// =====================================================================

/** Personaje por defecto para quien todavía no eligió ninguno. */
const AVATAR_POR_DEFECTO = 'leon';

/** Artículos de la tienda, opcionalmente de un tipo. */
function articulosTienda(?string $tipo = null): array
{
    $sql = 'SELECT id, slug, name, kind, emoji, price_coins, needs_streak, description
              FROM shop_items WHERE is_active = 1';
    $params = [];

    if ($tipo !== null) {
        $sql .= ' AND kind = ?';
        $params[] = $tipo;
    }

    return traerTodo($sql . ' ORDER BY kind, price_coins, sort_order, name', $params);
}

/** Slugs de lo que el usuario ya tiene. */
function articulosDe(?int $usuarioId = null): array
{
    $usuarioId ??= usuarioActualId();

    if ($usuarioId === null) {
        return [];
    }

    $filas = traerTodo(
        'SELECT i.slug FROM user_items ui
           JOIN shop_items i ON i.id = ui.item_id
          WHERE ui.user_id = ?',
        [$usuarioId]
    );

    return array_map(static fn($f) => $f['slug'], $filas);
}

/**
 * ¿Puede el usuario comprar este artículo? Devuelve null si sí, o el
 * motivo por el que no.
 */
function motivoNoComprar(array $item, array $wallet, array $r, array $tiene): ?string
{
    if (in_array($item['slug'], $tiene, true)) {
        return 'ya_lo_tienes';
    }
    if ((int) $item['needs_streak'] > 0 && $r['mejor'] < (int) $item['needs_streak']) {
        return 'falta_racha';
    }
    if ((int) $item['price_coins'] > $wallet['saldo']) {
        return 'faltan_monedas';
    }
    return null;
}

/**
 * Compra un artículo. Devuelve ['ok'=>bool, 'error'=>?string].
 *
 * Todo se vuelve a comprobar aquí aunque la interfaz ya lo hubiera hecho:
 * el precio, el saldo y la racha se leen del servidor, nunca del
 * formulario. Un botón deshabilitado no es una comprobación.
 */
function comprarArticulo(string $slug): array
{
    $usuarioId = usuarioActualId();

    if ($usuarioId === null) {
        return ['ok' => false, 'error' => 'Necesitas una cuenta.'];
    }

    $item = traerUno('SELECT * FROM shop_items WHERE slug = ? AND is_active = 1', [$slug]);

    if (!$item) {
        return ['ok' => false, 'error' => 'Ese artículo no existe.'];
    }

    $motivo = motivoNoComprar($item, billetera($usuarioId), racha($usuarioId), articulosDe($usuarioId));

    if ($motivo === 'ya_lo_tienes')   { return ['ok' => false, 'error' => 'Ya lo tienes.']; }
    if ($motivo === 'falta_racha')    { return ['ok' => false, 'error' => 'Todavía no alcanzas la racha que pide.']; }
    if ($motivo === 'faltan_monedas') { return ['ok' => false, 'error' => 'No te alcanzan las monedas.']; }

    // INSERT IGNORE: si el niño toca el botón dos veces seguidas, no se
    // le cobra dos veces.
    ejecutar(
        'INSERT IGNORE INTO user_items (user_id, item_id, paid_coins) VALUES (?, ?, ?)',
        [$usuarioId, $item['id'], (int) $item['price_coins']]
    );

    return ['ok' => true, 'error' => null, 'item' => $item];
}

/** Pone un personaje o accesorio, si el usuario lo tiene. */
function equiparArticulo(string $slug): array
{
    $usuarioId = usuarioActualId();

    if ($usuarioId === null) {
        return ['ok' => false, 'error' => 'Necesitas una cuenta.'];
    }

    // Quitar el accesorio es legítimo y no requiere tener nada.
    if ($slug === '') {
        ejecutar('UPDATE users SET accessory = NULL WHERE id = ?', [$usuarioId]);
        return ['ok' => true, 'error' => null];
    }

    $item = traerUno('SELECT * FROM shop_items WHERE slug = ? AND is_active = 1', [$slug]);

    if (!$item) {
        return ['ok' => false, 'error' => 'Ese artículo no existe.'];
    }

    if (!in_array($slug, articulosDe($usuarioId), true)) {
        return ['ok' => false, 'error' => 'Todavía no tienes eso.'];
    }

    $columna = $item['kind'] === 'avatar' ? 'avatar' : 'accessory';
    ejecutar("UPDATE users SET `$columna` = ? WHERE id = ?", [$slug, $usuarioId]);

    return ['ok' => true, 'error' => null];
}

/**
 * El personaje del usuario, listo para pintar.
 *
 * @return array{emoji:string, nombre:string, accesorio:?string}
 */
function personajeDe(?array $usuario = null): array
{
    $usuario ??= usuarioActual();

    $slug = $usuario['avatar'] ?? AVATAR_POR_DEFECTO;

    $avatar = traerUno('SELECT name, emoji FROM shop_items WHERE slug = ? AND kind = "avatar"', [$slug])
           ?? traerUno('SELECT name, emoji FROM shop_items WHERE slug = ? AND kind = "avatar"', [AVATAR_POR_DEFECTO]);

    $accesorio = null;
    $accSlug   = null;

    if (!empty($usuario['accessory'])) {
        $a = traerUno('SELECT slug, emoji FROM shop_items WHERE slug = ? AND kind = "accesorio"',
                      [$usuario['accessory']]);

        if ($a) {
            $accesorio = $a['emoji'];
            $accSlug   = $a['slug'];
        }
    }

    return [
        'emoji'     => $avatar['emoji'] ?? '🦁',
        'nombre'    => $avatar['name']  ?? 'León',
        'accesorio' => $accesorio,

        /*
         * El slug del accesorio viaja con él, y no es un detalle: es lo
         * que permite ponerle el sombrero en la cabeza y las gafas en los
         * ojos. Ver `personajeHtml()`.
         */
        'accesorio_slug' => $accSlug,
    ];
}

/**
 * Pinta el personaje con su accesorio PUESTO.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  CADA ACCESORIO VA DONDE VA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Antes todos se pegaban en la esquina superior derecha con la misma
 * regla: el sombrero flotaba al lado de la cabeza, las gafas flotaban al
 * lado de la cabeza y la bufanda también. Parecía que al personaje le
 * hubieran caído cosas encima, no que las llevara puestas.
 *
 * Por eso sale el slug en una clase: `acc-sombrero` va arriba y centrado,
 * `acc-gafas` sobre los ojos, `acc-bufanda` en el cuello. Lo coloca el
 * CSS, que es donde se puede afinar mirando el resultado.
 *
 * Se mide todo en `em`, así que el accesorio sigue al personaje sea del
 * tamaño que sea: el mismo HTML vale para el marcador de la cabecera y
 * para la celebración a pantalla completa.
 */
function personajeHtml(?array $usuario = null, string $clase = ''): string
{
    $p = personajeDe($usuario);

    $html = '<span class="personaje ' . e($clase) . '">'
          . '<span class="personaje-cara">' . e($p['emoji']) . '</span>';

    if ($p['accesorio'] !== null) {
        $html .= '<span class="personaje-accesorio acc-' . e((string) $p['accesorio_slug'])
               . '" aria-hidden="true">' . e($p['accesorio']) . '</span>';
    }

    return $html . '</span>';
}
