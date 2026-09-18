<?php
/**
 * admin.php — Operaciones de escritura del panel
 *
 * Las páginas del panel recogen datos y muestran resultados; toda la
 * validación y la escritura vive aquí. Así una regla —por ejemplo, que
 * el slug sea único— se aplica igual venga de donde venga.
 *
 * Este archivo solo se carga desde admin/, nunca en el sitio público.
 */

declare(strict_types=1);

// Las métricas y las gráficas solo se usan dentro del panel, así que se
// cargan aquí y no en config.php: el sitio público no las necesita y no
// tiene por qué pagar su coste en cada visita.
require_once RUTA_INCLUDES . '/metricas.php';
require_once RUTA_INCLUDES . '/graficas.php';

// =====================================================================
//  UTILIDADES
// =====================================================================

/**
 * Genera un slug único para una tabla.
 * Si "aventura-de-la-m" ya existe, prueba "aventura-de-la-m-2", y así.
 */
function slugUnico(string $texto, string $tabla, ?int $idExcluido = null): string
{
    $tablasPermitidas = ['activities', 'categories', 'levels', 'schools'];
    if (!in_array($tabla, $tablasPermitidas, true)) {
        throw new InvalidArgumentException('Tabla no permitida.');
    }

    $base = slugificar($texto) ?: 'sin-titulo';
    $slug = $base;
    $n    = 1;

    while (true) {
        $sql    = "SELECT id FROM `$tabla` WHERE slug = ?";
        $params = [$slug];

        if ($idExcluido !== null) {
            $sql .= ' AND id <> ?';
            $params[] = $idExcluido;
        }

        if (!traerValor($sql, $params)) {
            return $slug;
        }

        $n++;
        $slug = $base . '-' . $n;
    }
}

/** Devuelve null si la cadena está vacía; útil para columnas opcionales. */
function nuloSiVacio(?string $v): ?string
{
    $v = $v === null ? '' : trim($v);
    return $v === '' ? null : $v;
}


// =====================================================================
//  ACTIVIDADES
// =====================================================================

/**
 * Crea o actualiza una actividad.
 *
 * @param array    $datos Campos del formulario
 * @param int|null $id    null para crear, id para actualizar
 * @return array{ok:bool, errores:array, id:?int}
 */
function guardarActividad(array $datos, ?int $id = null): array
{
    $errores = [];

    $titulo = trim((string) ($datos['title'] ?? ''));
    if (mb_strlen($titulo) < 3 || mb_strlen($titulo) > 160) {
        $errores['title'] = 'El título debe tener entre 3 y 160 caracteres.';
    }

    $accesos = [ACCESO_LIBRE, ACCESO_PARCIAL, ACCESO_PREMIUM];
    $acceso  = in_array($datos['access_type'] ?? '', $accesos, true)
        ? $datos['access_type']
        : ACCESO_PARCIAL;

    $estados = ['draft', 'published', 'archived'];
    $estado  = in_array($datos['status'] ?? '', $estados, true) ? $datos['status'] : 'draft';

    $estacionesLibres = max(0, min(255, (int) ($datos['free_stations'] ?? 0)));

    // Una actividad parcial sin estaciones libres queda cerrada de hecho,
    // que casi nunca es lo que se quiere. Se avisa en vez de dejarlo pasar.
    if ($acceso === ACCESO_PARCIAL && $estacionesLibres === 0) {
        $errores['free_stations'] = 'Con acceso parcial debes liberar al menos una estación, '
            . 'o la actividad quedará completamente bloqueada.';
    }

    // Categoría y nivel deben existir de verdad.
    $categoriaId = (int) ($datos['category_id'] ?? 0) ?: null;
    if ($categoriaId !== null && !traerValor('SELECT id FROM categories WHERE id = ?', [$categoriaId])) {
        $errores['category_id'] = 'La categoría seleccionada no existe.';
    }

    $nivelId = (int) ($datos['level_id'] ?? 0) ?: null;
    if ($nivelId !== null && !traerValor('SELECT id FROM levels WHERE id = ?', [$nivelId])) {
        $errores['level_id'] = 'El nivel seleccionado no existe.';
    }

    // El bloque debe pertenecer a la categoría elegida; si no, la
    // actividad aparecería agrupada bajo un encabezado de otra categoría.
    $bloqueId = (int) ($datos['collection_id'] ?? 0) ?: null;
    if ($bloqueId !== null) {
        $bloque = traerUno('SELECT category_id FROM collections WHERE id = ?', [$bloqueId]);
        if (!$bloque) {
            $errores['collection_id'] = 'El bloque seleccionado no existe.';
        } elseif ((int) $bloque['category_id'] !== $categoriaId) {
            $errores['collection_id'] = 'Ese bloque pertenece a otra categoría. '
                . 'Elige un bloque de la categoría seleccionada, o ninguno.';
        }
    }

    $duracion = (int) ($datos['duration_minutes'] ?? 0);
    $duracion = ($duracion > 0 && $duracion <= 600) ? $duracion : null;

    if ($errores) {
        return ['ok' => false, 'errores' => $errores, 'id' => $id];
    }

    // El slug se toma del formulario si viene; si no, del título.
    $slugPedido = trim((string) ($datos['slug'] ?? ''));
    $slug = slugUnico($slugPedido !== '' ? $slugPedido : $titulo, 'activities', $id);

    $campos = [
        'slug'             => $slug,
        'title'            => $titulo,
        'description'      => nuloSiVacio($datos['description'] ?? null),
        'instructions'     => nuloSiVacio($datos['instructions'] ?? null),
        'objective'        => nuloSiVacio($datos['objective'] ?? null),
        'category_id'      => $categoriaId,
        'collection_id'    => $bloqueId,
        'level_id'         => $nivelId,
        'engine'           => nuloSiVacio($datos['engine'] ?? null) ?? 'legacy_html',
        'activity_type'    => nuloSiVacio($datos['activity_type'] ?? null),
        'legacy_file'      => nuloSiVacio($datos['legacy_file'] ?? null),
        'icon'             => nuloSiVacio($datos['icon'] ?? null),
        'duration_minutes' => $duracion,
        'access_type'      => $acceso,
        'free_stations'    => $estacionesLibres,
        'status'           => $estado,
        'is_featured'      => !empty($datos['is_featured']) ? 1 : 0,
    ];

    if ($id === null) {
        // Al publicar por primera vez se sella la fecha: alimenta la
        // sección "Nuevas actividades".
        $campos['published_at'] = $estado === 'published' ? date('Y-m-d H:i:s') : null;

        $columnas = implode(', ', array_map(static fn($c) => "`$c`", array_keys($campos)));
        $marcas   = implode(', ', array_fill(0, count($campos), '?'));

        $nuevoId = insertar(
            "INSERT INTO activities ($columnas) VALUES ($marcas)",
            array_values($campos)
        );

        return ['ok' => true, 'errores' => [], 'id' => $nuevoId];
    }

    // Al actualizar, la fecha de publicación se pone solo si aún no la
    // tenía: republicar algo viejo no debe hacerlo aparecer como nuevo.
    $yaTenia = traerValor('SELECT published_at FROM activities WHERE id = ?', [$id]);
    if ($estado === 'published' && !$yaTenia) {
        $campos['published_at'] = date('Y-m-d H:i:s');
    }

    $asignaciones = implode(', ', array_map(static fn($c) => "`$c` = ?", array_keys($campos)));
    $valores = array_values($campos);
    $valores[] = $id;

    ejecutar("UPDATE activities SET $asignaciones WHERE id = ?", $valores);

    return ['ok' => true, 'errores' => [], 'id' => $id];
}

/** Publica o despublica una actividad. */
function cambiarEstadoActividad(int $id, string $estado): bool
{
    if (!in_array($estado, ['draft', 'published', 'archived'], true)) {
        return false;
    }

    $yaTenia = traerValor('SELECT published_at FROM activities WHERE id = ?', [$id]);

    if ($estado === 'published' && !$yaTenia) {
        ejecutar('UPDATE activities SET status = ?, published_at = NOW() WHERE id = ?', [$estado, $id]);
    } else {
        ejecutar('UPDATE activities SET status = ? WHERE id = ?', [$estado, $id]);
    }

    return true;
}

/**
 * Elimina una actividad.
 * Las estaciones, el progreso y los favoritos caen en cascada por las
 * claves foráneas: no quedan filas huérfanas.
 */
function eliminarActividad(int $id): bool
{
    return ejecutar('DELETE FROM activities WHERE id = ?', [$id]) > 0;
}


// =====================================================================
//  ESTACIONES
// =====================================================================

/**
 * Crea o actualiza una estación.
 *
 * @return array{ok:bool, errores:array, id:?int}
 */
function guardarEstacion(int $actividadId, array $datos, ?int $id = null): array
{
    $errores = [];

    if (!traerValor('SELECT id FROM activities WHERE id = ?', [$actividadId])) {
        return ['ok' => false, 'errores' => ['general' => 'La actividad no existe.'], 'id' => null];
    }

    $titulo = trim((string) ($datos['title'] ?? ''));
    if (mb_strlen($titulo) < 2 || mb_strlen($titulo) > 160) {
        $errores['title'] = 'El título debe tener entre 2 y 160 caracteres.';
    }

    $tipo = trim((string) ($datos['game_type'] ?? ''));
    if ($tipo === '') {
        $errores['game_type'] = 'Indica el tipo de minijuego.';
    }

    // El config es JSON: si viene mal formado, se avisa antes de guardar
    // en vez de dejar una fila que el motor no podrá leer.
    $config = trim((string) ($datos['config'] ?? ''));
    if ($config !== '') {
        json_decode($config, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            $errores['config'] = 'El contenido no es JSON válido: ' . json_last_error_msg();
        }
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores, 'id' => $id];
    }

    $posicion = (int) ($datos['position'] ?? 0);
    if ($posicion < 1) {
        // Sin posición indicada, va al final.
        $posicion = 1 + (int) traerValor(
            'SELECT COALESCE(MAX(position), 0) FROM activity_stations WHERE activity_id = ?',
            [$actividadId]
        );
    }

    $campos = [
        'activity_id' => $actividadId,
        'position'    => $posicion,
        'title'       => $titulo,
        'description' => nuloSiVacio($datos['description'] ?? null),
        'icon'        => nuloSiVacio($datos['icon'] ?? null),
        'game_type'   => $tipo,
        'config'      => $config !== '' ? $config : null,
        'is_free'     => !empty($datos['is_free']) ? 1 : 0,
    ];

    try {
        if ($id === null) {
            $columnas = implode(', ', array_map(static fn($c) => "`$c`", array_keys($campos)));
            $marcas   = implode(', ', array_fill(0, count($campos), '?'));
            $nuevoId  = insertar("INSERT INTO activity_stations ($columnas) VALUES ($marcas)", array_values($campos));
            return ['ok' => true, 'errores' => [], 'id' => $nuevoId];
        }

        $asignaciones = implode(', ', array_map(static fn($c) => "`$c` = ?", array_keys($campos)));
        $valores = array_values($campos);
        $valores[] = $id;
        ejecutar("UPDATE activity_stations SET $asignaciones WHERE id = ?", $valores);

        return ['ok' => true, 'errores' => [], 'id' => $id];

    } catch (PDOException $e) {
        // El índice único (activity_id, position) impide dos estaciones
        // en el mismo puesto.
        if ($e->getCode() === '23000') {
            return [
                'ok' => false,
                'errores' => ['position' => "Ya existe una estación en la posición $posicion."],
                'id' => $id,
            ];
        }
        throw $e;
    }
}

/** Elimina una estación. */
function eliminarEstacion(int $id): bool
{
    return ejecutar('DELETE FROM activity_stations WHERE id = ?', [$id]) > 0;
}

/**
 * Mueve una estación una posición arriba o abajo.
 * Se intercambian las posiciones dentro de una transacción, usando un
 * valor temporal para no chocar con el índice único por el camino.
 */
function moverEstacion(int $id, string $direccion): bool
{
    $est = traerUno('SELECT id, activity_id, position FROM activity_stations WHERE id = ?', [$id]);
    if (!$est) {
        return false;
    }

    $comparador = $direccion === 'arriba' ? '<' : '>';
    $ordenar    = $direccion === 'arriba' ? 'DESC' : 'ASC';

    $vecina = traerUno(
        "SELECT id, position FROM activity_stations
          WHERE activity_id = ? AND position $comparador ?
       ORDER BY position $ordenar LIMIT 1",
        [$est['activity_id'], $est['position']]
    );

    if (!$vecina) {
        return false;   // ya está en un extremo
    }

    db()->beginTransaction();
    try {
        // Posición temporal fuera de rango para liberar el hueco.
        ejecutar('UPDATE activity_stations SET position = 0 WHERE id = ?', [$est['id']]);
        ejecutar('UPDATE activity_stations SET position = ? WHERE id = ?', [$est['position'], $vecina['id']]);
        ejecutar('UPDATE activity_stations SET position = ? WHERE id = ?', [$vecina['position'], $est['id']]);
        db()->commit();
        return true;
    } catch (Throwable $e) {
        db()->rollBack();
        throw $e;
    }
}

/** Renumera las estaciones de una actividad como 1, 2, 3… sin huecos. */
function renumerarEstaciones(int $actividadId): void
{
    $filas = traerTodo(
        'SELECT id FROM activity_stations WHERE activity_id = ? ORDER BY position, id',
        [$actividadId]
    );

    db()->beginTransaction();
    try {
        // Primero se apartan todas a un rango libre, para que la
        // renumeración no choque con el índice único.
        ejecutar('UPDATE activity_stations SET position = position + 1000 WHERE activity_id = ?', [$actividadId]);

        foreach ($filas as $i => $fila) {
            ejecutar('UPDATE activity_stations SET position = ? WHERE id = ?', [$i + 1, $fila['id']]);
        }

        db()->commit();
    } catch (Throwable $e) {
        db()->rollBack();
        throw $e;
    }
}


// =====================================================================
//  CATEGORÍAS Y NIVELES
// =====================================================================

/** Crea o actualiza una categoría. */
function guardarCategoria(array $datos, ?int $id = null): array
{
    $errores = [];

    $nombre = trim((string) ($datos['name'] ?? ''));
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 120) {
        $errores['name'] = 'El nombre debe tener entre 2 y 120 caracteres.';
    }

    $color = trim((string) ($datos['color'] ?? ''));
    if ($color !== '' && !preg_match('/^#[0-9a-fA-F]{6}$/', $color)) {
        $errores['color'] = 'El color debe ser hexadecimal, por ejemplo #29b6f6.';
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores, 'id' => $id];
    }

    $campos = [
        'slug'       => slugUnico(trim((string) ($datos['slug'] ?? '')) ?: $nombre, 'categories', $id),
        'name'       => $nombre,
        'tagline'    => nuloSiVacio($datos['tagline'] ?? null),
        'icon'       => nuloSiVacio($datos['icon'] ?? null),
        'color'      => $color !== '' ? $color : null,
        'sort_order' => (int) ($datos['sort_order'] ?? 0),
        'is_active'  => !empty($datos['is_active']) ? 1 : 0,
    ];

    if ($id === null) {
        $columnas = implode(', ', array_map(static fn($c) => "`$c`", array_keys($campos)));
        $marcas   = implode(', ', array_fill(0, count($campos), '?'));
        return ['ok' => true, 'errores' => [],
                'id' => insertar("INSERT INTO categories ($columnas) VALUES ($marcas)", array_values($campos))];
    }

    $asignaciones = implode(', ', array_map(static fn($c) => "`$c` = ?", array_keys($campos)));
    $valores = array_values($campos);
    $valores[] = $id;
    ejecutar("UPDATE categories SET $asignaciones WHERE id = ?", $valores);

    return ['ok' => true, 'errores' => [], 'id' => $id];
}

/**
 * Elimina una categoría.
 * Las actividades que la usaban quedan sin categoría (la clave foránea
 * es ON DELETE SET NULL), no se borran. Por eso se avisa cuántas son.
 */
function eliminarCategoria(int $id): int
{
    $afectadas = (int) traerValor('SELECT COUNT(*) FROM activities WHERE category_id = ?', [$id]);
    ejecutar('DELETE FROM categories WHERE id = ?', [$id]);
    return $afectadas;
}

// =====================================================================
//  BLOQUES
// =====================================================================

/** Crea o actualiza un bloque del catálogo. */
function guardarBloque(array $datos, ?int $id = null): array
{
    $errores = [];

    $nombre = trim((string) ($datos['name'] ?? ''));
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 120) {
        $errores['name'] = 'El nombre debe tener entre 2 y 120 caracteres.';
    }

    // Un bloque sin categoría no se mostraría en ningún sitio, así que
    // se exige en vez de aceptarlo y dejarlo huérfano.
    $categoriaId = (int) ($datos['category_id'] ?? 0) ?: null;
    if ($categoriaId === null) {
        $errores['category_id'] = 'Elige a qué categoría pertenece el bloque.';
    } elseif (!traerValor('SELECT id FROM categories WHERE id = ?', [$categoriaId])) {
        $errores['category_id'] = 'La categoría seleccionada no existe.';
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores, 'id' => $id];
    }

    $campos = [
        'category_id' => $categoriaId,
        'slug'        => slugUnico(trim((string) ($datos['slug'] ?? '')) ?: $nombre, 'collections', $id),
        'name'        => $nombre,
        'description' => nuloSiVacio($datos['description'] ?? null),
        'icon'        => nuloSiVacio($datos['icon'] ?? null),
        'sort_order'  => (int) ($datos['sort_order'] ?? 0),
        'is_active'   => !empty($datos['is_active']) ? 1 : 0,
    ];

    if ($id === null) {
        $columnas = implode(', ', array_map(static fn($c) => "`$c`", array_keys($campos)));
        $marcas   = implode(', ', array_fill(0, count($campos), '?'));
        return ['ok' => true, 'errores' => [],
                'id' => insertar("INSERT INTO collections ($columnas) VALUES ($marcas)", array_values($campos))];
    }

    $asignaciones = implode(', ', array_map(static fn($c) => "`$c` = ?", array_keys($campos)));
    $valores = array_values($campos);
    $valores[] = $id;
    ejecutar("UPDATE collections SET $asignaciones WHERE id = ?", $valores);

    return ['ok' => true, 'errores' => [], 'id' => $id];
}

/**
 * Elimina un bloque.
 * Las actividades que contenía NO se borran: quedan sin bloque, y se
 * mostrarán bajo «Otras actividades». Devuelve cuántas son.
 */
function eliminarBloque(int $id): int
{
    $afectadas = (int) traerValor('SELECT COUNT(1) FROM activities WHERE collection_id = ?', [$id]);
    ejecutar('DELETE FROM collections WHERE id = ?', [$id]);
    return $afectadas;
}


/** Crea o actualiza un nivel. */
function guardarNivel(array $datos, ?int $id = null): array
{
    $errores = [];

    $nombre = trim((string) ($datos['name'] ?? ''));
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 120) {
        $errores['name'] = 'El nombre debe tener entre 2 y 120 caracteres.';
    }

    $min = (int) ($datos['min_age'] ?? 0) ?: null;
    $max = (int) ($datos['max_age'] ?? 0) ?: null;

    if ($min !== null && $max !== null && $min > $max) {
        $errores['min_age'] = 'La edad mínima no puede ser mayor que la máxima.';
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores, 'id' => $id];
    }

    $campos = [
        'slug'       => slugUnico(trim((string) ($datos['slug'] ?? '')) ?: $nombre, 'levels', $id),
        'name'       => $nombre,
        'min_age'    => $min,
        'max_age'    => $max,
        'sort_order' => (int) ($datos['sort_order'] ?? 0),
        'is_active'  => !empty($datos['is_active']) ? 1 : 0,
    ];

    if ($id === null) {
        $columnas = implode(', ', array_map(static fn($c) => "`$c`", array_keys($campos)));
        $marcas   = implode(', ', array_fill(0, count($campos), '?'));
        return ['ok' => true, 'errores' => [],
                'id' => insertar("INSERT INTO levels ($columnas) VALUES ($marcas)", array_values($campos))];
    }

    $asignaciones = implode(', ', array_map(static fn($c) => "`$c` = ?", array_keys($campos)));
    $valores = array_values($campos);
    $valores[] = $id;
    ejecutar("UPDATE levels SET $asignaciones WHERE id = ?", $valores);

    return ['ok' => true, 'errores' => [], 'id' => $id];
}

/** Elimina un nivel. Devuelve cuántas actividades quedan sin nivel. */
function eliminarNivel(int $id): int
{
    $afectadas = (int) traerValor('SELECT COUNT(*) FROM activities WHERE level_id = ?', [$id]);
    ejecutar('DELETE FROM levels WHERE id = ?', [$id]);
    return $afectadas;
}


// =====================================================================
//  USUARIOS
// =====================================================================

/**
 * Cambia el rol de un usuario.
 * Se impide dejar la plataforma sin ningún administrador.
 */
function cambiarRolUsuario(int $id, string $rol): array
{
    $roles = ['admin', 'user', 'teacher', 'school_admin', 'student'];
    if (!in_array($rol, $roles, true)) {
        return ['ok' => false, 'error' => 'Rol no válido.'];
    }

    $actual = traerUno('SELECT role FROM users WHERE id = ?', [$id]);
    if (!$actual) {
        return ['ok' => false, 'error' => 'El usuario no existe.'];
    }

    if ($actual['role'] === 'admin' && $rol !== 'admin' && contarAdmins() <= 1) {
        return ['ok' => false, 'error' => 'Es el único administrador: no puedes quitarle el rol.'];
    }

    ejecutar('UPDATE users SET role = ? WHERE id = ?', [$rol, $id]);
    return ['ok' => true, 'error' => null];
}

/** Activa o desactiva una cuenta. */
function cambiarEstadoUsuario(int $id, string $estado): array
{
    if (!in_array($estado, ['active', 'inactive', 'pending'], true)) {
        return ['ok' => false, 'error' => 'Estado no válido.'];
    }

    $actual = traerUno('SELECT role, status FROM users WHERE id = ?', [$id]);
    if (!$actual) {
        return ['ok' => false, 'error' => 'El usuario no existe.'];
    }

    if ($actual['role'] === 'admin' && $estado !== 'active' && contarAdmins() <= 1) {
        return ['ok' => false, 'error' => 'Es el único administrador activo: no puedes desactivarlo.'];
    }

    ejecutar('UPDATE users SET status = ? WHERE id = ?', [$estado, $id]);
    return ['ok' => true, 'error' => null];
}

/** Cuántos administradores activos hay. */
function contarAdmins(): int
{
    return (int) traerValor('SELECT COUNT(*) FROM users WHERE role = "admin" AND status = "active"');
}

/**
 * Crea una cuenta desde el panel.
 *
 * Se apoya en `registrarUsuario()` en vez de insertar por su cuenta, y no
 * por ahorrar código: ahí viven la comprobación de correo repetido, la
 * longitud mínima de la contraseña, la entrega de los personajes de
 * bienvenida y —sobre todo— la regla del Decreto 0769: una cuenta de
 * menor de 14 años necesita el correo de un adulto responsable. Duplicar
 * ese INSERT aquí habría dejado una segunda puerta por la que crear
 * cuentas de menores sin acudiente.
 *
 * El rol y el estado se aplican DESPUÉS, con las mismas funciones que usa
 * el cambio manual, para que hereden sus salvaguardas.
 */
function crearUsuarioComoAdmin(array $d): array
{
    $r = registrarUsuario(
        (string) ($d['name'] ?? ''),
        (string) ($d['email'] ?? ''),
        (string) ($d['password'] ?? ''),
        ($d['guardian_email'] ?? '') !== '' ? (string) $d['guardian_email'] : null,
        ($d['birth_year'] ?? '') !== '' ? (int) $d['birth_year'] : null
    );

    if (!$r['ok']) {
        return $r;
    }

    $id = (int) $r['usuario_id'];

    $rol = (string) ($d['role'] ?? 'user');
    if ($rol !== 'user') {
        cambiarRolUsuario($id, $rol);
    }

    $estado = (string) ($d['status'] ?? 'active');
    if ($estado !== 'active') {
        cambiarEstadoUsuario($id, $estado);
    }

    return ['ok' => true, 'errores' => [], 'usuario_id' => $id];
}

/**
 * Contraseña inicial legible, para dictársela a una familia por teléfono.
 *
 * Sin caracteres que se confundan al leerlos en voz alta —ni O ni 0, ni
 * l ni 1— y con un separador que marca dónde respirar. Se usa
 * `random_int`, que es criptográficamente seguro: `rand()` daría claves
 * predecibles a quien conozca el momento de creación de la cuenta.
 */
function contrasenaSugerida(): string
{
    $silabas = ['ma', 'lu', 'so', 'ri', 'ta', 'ne', 'pi', 'co', 'du', 'fa', 'ze', 'bo'];

    $palabra = '';
    for ($i = 0; $i < 3; $i++) {
        $palabra .= $silabas[random_int(0, count($silabas) - 1)];
    }

    return ucfirst($palabra) . '-' . random_int(100, 999);
}


// =====================================================================
//  SUSCRIPCIONES
// =====================================================================

/** Activa manualmente una suscripción (útil antes de tener pasarela). */
function activarSuscripcion(int $usuarioId, string $planSlug, string $ciclo = 'yearly'): array
{
    $plan = planPorSlug($planSlug);
    if (!$plan) {
        return ['ok' => false, 'error' => 'El plan no existe.'];
    }

    if (!traerValor('SELECT id FROM users WHERE id = ?', [$usuarioId])) {
        return ['ok' => false, 'error' => 'El usuario no existe.'];
    }

    $ciclo   = $ciclo === 'monthly' ? 'monthly' : 'yearly';
    $intervalo = $ciclo === 'monthly' ? '1 MONTH' : '1 YEAR';
    $monto   = $ciclo === 'monthly'
        ? (int) ($plan['price_monthly_cop'] ?? 0)
        : (int) ($plan['price_yearly_cop'] ?? 0);

    insertar(
        "INSERT INTO subscriptions
            (user_id, plan_id, billing_cycle, amount_cop, status, starts_at, expires_at, payment_provider)
         VALUES (?, ?, ?, ?, 'active', NOW(), NOW() + INTERVAL $intervalo, 'manual')",
        [$usuarioId, $plan['id'], $ciclo, $monto]
    );

    return ['ok' => true, 'error' => null];
}

/** Cancela una suscripción. */
function cancelarSuscripcion(int $id): bool
{
    return ejecutar("UPDATE subscriptions SET status = 'cancelled' WHERE id = ?", [$id]) > 0;
}

/**
 * Suma o resta días a una suscripción vigente, sin cobrar nada.
 *
 * Es la herramienta de la disculpa: la plataforma estuvo caída dos días,
 * o una familia reclama con razón. Queda escrito en la propia
 * suscripción, y con días negativos también sirve para corregir una
 * activación hecha de más.
 *
 * Nunca deja la fecha en el pasado por accidente: si al restar el
 * resultado cae antes de hoy, se corta en hoy y el acceso termina
 * ahora, no en una fecha inventada hacia atrás.
 */
function ajustarVigencia(int $suscripcionId, int $dias): array
{
    if ($dias === 0) {
        return ['ok' => false, 'error' => 'No has indicado cuántos días.'];
    }
    if (abs($dias) > 3650) {
        return ['ok' => false, 'error' => 'Son demasiados días de una vez (máximo diez años).'];
    }

    $s = traerUno('SELECT id, expires_at FROM subscriptions WHERE id = ?', [$suscripcionId]);
    if (!$s) {
        return ['ok' => false, 'error' => 'La suscripción no existe.'];
    }
    if ($s['expires_at'] === null) {
        return ['ok' => false, 'error' => 'Esta suscripción no tiene fecha de vencimiento.'];
    }

    ejecutar(
        'UPDATE subscriptions
            SET expires_at = GREATEST(expires_at + INTERVAL ? DAY, NOW())
          WHERE id = ?',
        [$dias, $suscripcionId]
    );

    return ['ok' => true, 'error' => null];
}


// =====================================================================
//  EDICIÓN DE UNA CUENTA
// =====================================================================

/**
 * Edita los datos de una cuenta desde el panel.
 *
 * Repite las validaciones de `registrarUsuario()` en vez de llamarla
 * porque aquella crea y esta modifica, y hay una diferencia que importa:
 * al comprobar que el correo no está repetido hay que excluir a la
 * propia cuenta, o guardar sin tocar el correo daría «ya existe».
 *
 * La regla del acudiente se vuelve a aplicar entera. Si no se aplicara
 * aquí, bastaría con crear la cuenta con año 1990 y editarla después a
 * 2016 para saltarse el Decreto 0769 por la puerta de atrás.
 */
function actualizarUsuario(int $id, array $d): array
{
    $actual = traerUno('SELECT * FROM users WHERE id = ?', [$id]);
    if (!$actual) {
        return ['ok' => false, 'errores' => ['general' => 'El usuario no existe.']];
    }

    $errores = [];

    $nombre = trim((string) ($d['name'] ?? ''));
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 120) {
        $errores['name'] = 'El nombre debe tener entre 2 y 120 caracteres.';
    }

    $correo = correoValido((string) ($d['email'] ?? ''));
    if ($correo === null) {
        $errores['email'] = 'El correo no tiene un formato válido.';
    } elseif (traerValor('SELECT 1 FROM users WHERE email = ? AND id <> ?', [$correo, $id])) {
        $errores['email'] = 'Ya hay otra cuenta con este correo.';
    }

    $anio = ($d['birth_year'] ?? '') !== '' ? (int) $d['birth_year'] : null;
    $acudiente = trim((string) ($d['guardian_email'] ?? ''));
    $acudiente = $acudiente !== '' ? correoValido($acudiente) : null;

    $anioActual = (int) date('Y');
    if ($anio !== null) {
        if ($anio < $anioActual - 100 || $anio > $anioActual) {
            $errores['birth_year'] = 'El año de nacimiento no es válido.';
        } elseif (($anioActual - $anio) < 14 && $acudiente === null) {
            $errores['guardian_email'] = 'Una cuenta de menor de 14 años necesita el correo de un adulto responsable.';
        }
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores];
    }

    ejecutar(
        'UPDATE users SET name = ?, email = ?, birth_year = ?, guardian_email = ? WHERE id = ?',
        [$nombre, $correo, $anio, $acudiente, $id]
    );

    // El rol y el estado se cambian con sus propias funciones, que traen
    // las salvaguardas del último administrador. Duplicar aquí el UPDATE
    // dejaría una segunda puerta por la que quedarse sin administradores.
    if (isset($d['role']) && $d['role'] !== $actual['role']) {
        $r = cambiarRolUsuario($id, (string) $d['role']);
        if (!$r['ok']) {
            return ['ok' => false, 'errores' => ['role' => $r['error']]];
        }
    }

    if (isset($d['status']) && $d['status'] !== $actual['status']) {
        if ($id === usuarioActualId() && $d['status'] !== 'active') {
            return ['ok' => false, 'errores' => ['status' => 'No puedes desactivar tu propia cuenta.']];
        }
        $r = cambiarEstadoUsuario($id, (string) $d['status']);
        if (!$r['ok']) {
            return ['ok' => false, 'errores' => ['status' => $r['error']]];
        }
    }

    return ['ok' => true, 'errores' => []];
}

/**
 * Pone una contraseña nueva a una cuenta.
 *
 * No se «recupera» ninguna: en la base solo hay un hash y eso es
 * justamente lo que lo hace seguro. Se pone una nueva, se enseña una
 * vez y quien la recibe debería cambiarla.
 */
function restablecerContrasena(int $id, string $nueva): array
{
    if (mb_strlen($nueva) < 8) {
        return ['ok' => false, 'error' => 'La contraseña debe tener al menos 8 caracteres.'];
    }
    if (!traerValor('SELECT id FROM users WHERE id = ?', [$id])) {
        return ['ok' => false, 'error' => 'El usuario no existe.'];
    }

    ejecutar(
        'UPDATE users SET password = ? WHERE id = ?',
        [password_hash($nueva, PASSWORD_DEFAULT), $id]
    );

    return ['ok' => true, 'error' => null];
}


// =====================================================================
//  PLANES Y PRECIOS
// =====================================================================

/**
 * Edita un plan comercial.
 *
 * El `slug` NO se puede cambiar y no está entre los campos editables.
 * Es la clave por la que preguntan `PLAN_BIBLIOTECA`, el control de
 * acceso y las suscripciones ya vendidas: renombrarlo dejaría a todos
 * los clientes de ese plan sin acceso, y el panel diría que todo está
 * correcto. Se crea un plan nuevo y se desactiva el viejo.
 *
 * Cambiar un precio tampoco toca lo ya vendido: `subscriptions` y
 * `payments` congelan el monto que se cobró en su momento.
 */
function guardarPlan(int $id, array $d): array
{
    $plan = traerUno('SELECT * FROM plans WHERE id = ?', [$id]);
    if (!$plan) {
        return ['ok' => false, 'errores' => ['general' => 'El plan no existe.']];
    }

    $errores = [];

    $nombre = trim((string) ($d['name'] ?? ''));
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 80) {
        $errores['name'] = 'El nombre debe tener entre 2 y 80 caracteres.';
    }

    // Un precio vacío significa «no se ofrece en esa modalidad», que no
    // es lo mismo que costar cero. Por eso null y no 0.
    $mensual = trim((string) ($d['price_monthly_cop'] ?? ''));
    $anual   = trim((string) ($d['price_yearly_cop'] ?? ''));
    $mensual = $mensual === '' ? null : max(0, (int) $mensual);
    $anual   = $anual   === '' ? null : max(0, (int) $anual);

    $activo = !empty($d['is_active']) ? 1 : 0;

    if ($plan['slug'] !== PLAN_FREE && $activo === 1 && $mensual === null && $anual === null) {
        $errores['price_yearly_cop'] = 'Un plan a la venta necesita al menos un precio.';
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores];
    }

    /*
     * Los beneficios llegan como una línea por beneficio, que es como se
     * escriben, y se guardan como JSON, que es como los lee la página de
     * precios. Las líneas vacías se descartan: una viñeta en blanco en
     * la tarjeta de precios parece un fallo de carga.
     */
    $lineas = preg_split('/\R/', (string) ($d['features'] ?? '')) ?: [];
    $lineas = array_values(array_filter(array_map('trim', $lineas), static fn ($l) => $l !== ''));

    ejecutar(
        'UPDATE plans
            SET name = ?, tagline = ?, description = ?,
                price_monthly_cop = ?, price_yearly_cop = ?,
                catalog_access = ?, manages_courses = ?, is_recommended = ?,
                is_active = ?, sort_order = ?, features = ?
          WHERE id = ?',
        [
            $nombre,
            nuloSiVacio(trim((string) ($d['tagline'] ?? ''))),
            nuloSiVacio(trim((string) ($d['description'] ?? ''))),
            $mensual,
            $anual,
            ($d['catalog_access'] ?? 'partial') === 'full' ? 'full' : 'partial',
            !empty($d['manages_courses']) ? 1 : 0,
            !empty($d['is_recommended'])  ? 1 : 0,
            $activo,
            (int) ($d['sort_order'] ?? 0),
            $lineas ? json_encode($lineas, JSON_UNESCAPED_UNICODE) : null,
            $id,
        ]
    );

    return ['ok' => true, 'errores' => []];
}


// =====================================================================
//  AJUSTES
// =====================================================================

/** Guarda un ajuste de la tabla settings. */
function guardarAjuste(string $clave, string $valor): void
{
    ejecutar(
        'INSERT INTO settings (`key`, `value`) VALUES (?, ?)
         ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)',
        [$clave, $valor]
    );

    // `ajuste()` guarda la tabla entera en memoria. Sin esto, leer la
    // clave que se acaba de escribir devolvería el valor anterior durante
    // el resto de la petición.
    olvidarAjustes();
}
