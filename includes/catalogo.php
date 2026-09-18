<?php
/**
 * catalogo.php — Consultas del catálogo
 *
 * Todas las lecturas de actividades, categorías, niveles y planes pasan
 * por aquí. Las páginas no escriben SQL: piden datos a estas funciones.
 *
 * Ventaja concreta: cuando en la Fase 3 las actividades dejen de ser
 * archivos HTML y pasen al motor por estaciones, solo cambia este
 * archivo; el frontend sigue igual.
 */

declare(strict_types=1);

/** Campos que necesita una tarjeta de actividad. Se reutiliza en cada consulta. */
const CAMPOS_TARJETA = '
    a.id, a.slug, a.title, a.description, a.icon, a.thumbnail,
    a.duration_minutes, a.access_type, a.free_stations, a.activity_type,
    a.engine, a.legacy_file, a.published_at, a.collection_id,
    c.name AS categoria, c.slug AS categoria_slug, c.icon AS categoria_icon, c.color AS categoria_color,
    c.sort_order AS categoria_orden,
    l.name AS nivel, l.slug AS nivel_slug, l.min_age, l.max_age,
    b.name AS bloque, b.slug AS bloque_slug, b.icon AS bloque_icon, b.sort_order AS bloque_orden';

const UNIONES_TARJETA = '
    FROM activities a
    LEFT JOIN categories  c ON c.id = a.category_id
    LEFT JOIN levels      l ON l.id = a.level_id
    LEFT JOIN collections b ON b.id = a.collection_id';


// =====================================================================
//  TAXONOMÍA
// =====================================================================

/** Categorías activas con cuántas actividades publicadas tiene cada una. */
function categoriasConConteo(): array
{
    /*
     * El recorte va en el ON del LEFT JOIN, no en el WHERE.
     *
     * En el WHERE convertiría el LEFT JOIN en un JOIN normal y las
     * categorías sin actividades visibles desaparecerían con él — pero
     * desaparecerían MAL, sin pasar por el `HAVING` de abajo, que es
     * quien decide eso a conciencia.
     */
    $recorte = recorteDeModoNino('a');

    /*
     * Y en modo niño se esconden las materias que quedaron en cero.
     *
     * Enseñarle «Matemática · 0 actividades» a un niño es un callejón sin
     * salida: toca, no hay nada, y no entiende por qué. Fuera del modo
     * siguen saliendo todas, porque ahí un cero sí informa a un adulto de
     * que falta contenido.
     */
    $having = $recorte['sql'] !== '' ? ' HAVING total > 0' : '';

    return traerTodo(
        'SELECT c.id, c.slug, c.name, c.tagline, c.icon, c.color,
                COUNT(a.id) AS total
           FROM categories c
      LEFT JOIN activities a ON a.category_id = c.id AND a.status = "published"'
           . $recorte['sql'] . '
          WHERE c.is_active = 1
       GROUP BY c.id' . $having . '
       ORDER BY c.sort_order',
        $recorte['params']
    );
}

/** Niveles activos, para el filtro de edad dentro de cada categoría. */
function nivelesActivos(): array
{
    return traerTodo(
        'SELECT id, slug, name, min_age, max_age
           FROM levels WHERE is_active = 1 ORDER BY sort_order'
    );
}

/** Una categoría por su slug, o null. */
function categoriaPorSlug(string $slug): ?array
{
    return traerUno('SELECT * FROM categories WHERE slug = ? AND is_active = 1', [$slug]);
}


// =====================================================================
//  BLOQUES
//
//  Un bloque agrupa actividades afines dentro de una categoría, para que
//  «Aventura de las Letras» no sea una lista de casi 40 tarjetas seguidas.
// =====================================================================

/** Bloques activos, opcionalmente los de una sola categoría. */
function bloquesDe(?string $categoriaSlug = null): array
{
    // Igual que en las categorías: el recorte en el ON, y los bloques que
    // quedan vacíos se esconden solo dentro del modo niño.
    $recorte = recorteDeModoNino('a');

    $sql = 'SELECT b.id, b.slug, b.name, b.description, b.icon, b.sort_order,
                   c.slug AS categoria_slug, c.name AS categoria, c.icon AS categoria_icon,
                   COUNT(a.id) AS total
              FROM collections b
         LEFT JOIN categories c ON c.id = b.category_id
         LEFT JOIN activities a ON a.collection_id = b.id AND a.status = "published"'
              . $recorte['sql'] . '
             WHERE b.is_active = 1';

    $params = $recorte['params'];

    if ($categoriaSlug !== null && $categoriaSlug !== '') {
        $sql .= ' AND c.slug = ?';
        $params[] = $categoriaSlug;
    }

    $sql .= ' GROUP BY b.id';

    if ($recorte['sql'] !== '') {
        $sql .= ' HAVING total > 0';
    }

    $sql .= ' ORDER BY c.sort_order, b.sort_order, b.name';

    return traerTodo($sql, $params);
}

/**
 * Categorías con sus bloques dentro, para el directorio del catálogo.
 *
 * Con 149 actividades, listar todas las tarjetas de entrada ya no es un
 * catálogo sino un muro. El directorio enseña las doce materias y, dentro
 * de cada una, sus bloques: la misma estructura que el visitante tiene en
 * la cabeza cuando busca algo («matemática, lo de operaciones»).
 *
 * Se resuelve con dos consultas y no con una por categoría: doce
 * consultas para dibujar una página es la clase de detalle que no se nota
 * hasta que hay tráfico.
 */
function directorioCategorias(): array
{
    $categorias = categoriasConConteo();
    $bloques    = bloquesDe();

    $porCategoria = [];
    foreach ($bloques as $b) {
        // Un bloque sin actividades publicadas no se ofrece: llevaría a
        // una página vacía.
        if ((int) $b['total'] > 0) {
            $porCategoria[$b['categoria_slug']][] = $b;
        }
    }

    foreach ($categorias as &$c) {
        $c['bloques'] = $porCategoria[$c['slug']] ?? [];
    }
    unset($c);

    // Las categorías sin ninguna actividad publicada no se muestran.
    return array_values(array_filter($categorias, static fn($c) => (int) $c['total'] > 0));
}

/** Un bloque por su slug, o null. */
function bloquePorSlug(string $slug): ?array
{
    return traerUno(
        'SELECT b.*, c.slug AS categoria_slug, c.name AS categoria, c.color AS categoria_color
           FROM collections b
      LEFT JOIN categories c ON c.id = b.category_id
          WHERE b.slug = ? AND b.is_active = 1',
        [$slug]
    );
}

/**
 * Reparte una lista de actividades en sus bloques, conservando el orden.
 *
 * Las que no pertenecen a ningún bloque van juntas al final: en las
 * categorías pequeñas un bloque no aporta nada, y forzarlo solo añadiría
 * un título de sección por cada dos tarjetas.
 *
 * @return array Lista de ['bloque' => ?array, 'actividades' => array]
 */
function agruparEnBloques(array $actividades): array
{
    $grupos = [];
    $sueltas = [];

    foreach ($actividades as $a) {
        if (empty($a['bloque_slug'])) {
            $sueltas[] = $a;
            continue;
        }

        $clave = $a['bloque_slug'];

        if (!isset($grupos[$clave])) {
            $grupos[$clave] = [
                'bloque' => [
                    'slug'      => $a['bloque_slug'],
                    'name'      => $a['bloque'],
                    'icon'      => $a['bloque_icon'],
                    'orden'     => (int) ($a['bloque_orden'] ?? 0),
                    'categoria' => $a['categoria'] ?? null,
                    'cat_orden' => (int) ($a['categoria_orden'] ?? 0),
                ],
                'actividades' => [],
            ];
        }

        $grupos[$clave]['actividades'][] = $a;
    }

    // Primero por categoría y luego por bloque. Ordenar solo por el orden
    // del bloque entremezclaría categorías distintas, porque cada una
    // numera sus bloques desde 1.
    uasort($grupos, static function ($x, $y) {
        return [$x['bloque']['cat_orden'], $x['bloque']['orden']]
           <=> [$y['bloque']['cat_orden'], $y['bloque']['orden']];
    });

    $salida = array_values($grupos);

    if ($sueltas) {
        $salida[] = ['bloque' => null, 'actividades' => $sueltas];
    }

    return $salida;
}


// =====================================================================
//  ETIQUETAS
//
//  La categoría dice de qué trata la actividad y es una sola; la
//  etiqueta dice qué pone en juego, y son varias. Con 104 actividades
//  ya no basta con saber la materia para encontrar algo: alguien puede
//  querer «un reto de lógica» sin importarle si es de sociales o de
//  matemática.
// =====================================================================

/**
 * Etiquetas con cuántas actividades publicadas tiene cada una.
 * Las vacías no se devuelven: un filtro que no lleva a ninguna parte
 * solo estorba.
 */
function etiquetasConConteo(?string $tipo = null): array
{
    $sql = 'SELECT t.id, t.slug, t.name, t.kind, t.icon, COUNT(a.id) AS total
              FROM tags t
              JOIN activity_tags at ON at.tag_id = t.id
              JOIN activities a ON a.id = at.activity_id AND a.status = "published"
             WHERE t.is_active = 1';

    $params = [];

    if ($tipo !== null) {
        $sql .= ' AND t.kind = ?';
        $params[] = $tipo;
    }

    $sql .= ' GROUP BY t.id ORDER BY t.kind, t.sort_order';

    return traerTodo($sql, $params);
}

/** Etiquetas de una actividad concreta. */
function etiquetasDe(int $actividadId): array
{
    return traerTodo(
        'SELECT t.slug, t.name, t.kind, t.icon
           FROM tags t
           JOIN activity_tags at ON at.tag_id = t.id
          WHERE at.activity_id = ? AND t.is_active = 1
       ORDER BY t.kind, t.sort_order',
        [$actividadId]
    );
}

/** Una etiqueta por su slug, o null. */
function etiquetaPorSlug(string $slug): ?array
{
    return traerUno('SELECT * FROM tags WHERE slug = ? AND is_active = 1', [$slug]);
}


// =====================================================================
//  BÚSQUEDA Y FILTRADO DEL CATÁLOGO
// =====================================================================

/**
 * Busca actividades publicadas.
 *
 * Los filtros se arman con marcadores, nunca concatenando valores: el
 * texto que escriba el visitante en el buscador no puede convertirse en
 * parte de la consulta.
 *
 * @param array $f  categoria, nivel, buscar, acceso, orden, limite, desplazamiento
 * @return array{filas:array, total:int}
 */
function buscarActividades(array $f = []): array
{
    $where  = ['a.status = "published"'];
    $params = [];

    if (!empty($f['categoria'])) {
        $where[] = 'c.slug = ?';
        $params[] = $f['categoria'];
    }

    if (!empty($f['nivel'])) {
        $where[] = 'l.slug = ?';
        $params[] = $f['nivel'];
    }

    if (!empty($f['bloque'])) {
        $where[] = 'b.slug = ?';
        $params[] = $f['bloque'];
    }

    // El filtro por etiqueta se resuelve con EXISTS y no con un JOIN:
    // una actividad tiene varias etiquetas, y un JOIN la devolvería
    // repetida una vez por cada una que coincida.
    if (!empty($f['etiqueta'])) {
        $where[] = 'EXISTS (SELECT 1 FROM activity_tags at
                              JOIN tags t ON t.id = at.tag_id
                             WHERE at.activity_id = a.id AND t.slug = ?)';
        $params[] = $f['etiqueta'];
    }

    if (!empty($f['acceso'])) {
        // 'gratis' agrupa lo que un usuario Free puede tocar de algún modo.
        if ($f['acceso'] === 'gratis') {
            $where[] = 'a.access_type IN ("free", "partial")';
        } elseif ($f['acceso'] === 'premium') {
            $where[] = 'a.access_type = "premium"';
        }
    }

    if (!empty($f['buscar'])) {
        $where[] = '(a.title LIKE ? OR a.description LIKE ? OR c.name LIKE ?)';
        // escape_like protege los comodines % y _ que pudiera escribir el usuario.
        $termino = '%' . escaparLike($f['buscar']) . '%';
        $params[] = $termino;
        $params[] = $termino;
        $params[] = $termino;
    }

    $sqlWhere = 'WHERE ' . implode(' AND ', $where);

    /*
     * El recorte del modo niño va DENTRO de la consulta, no después.
     *
     * Filtrando el resultado en PHP el total y la paginación mentirían:
     * el catálogo diría «487 actividades» y pintaría las seis que su papá
     * le dejó. Aquí el total también sale recortado, que es lo correcto —
     * para esa cuenta, en ese momento, el catálogo ES eso.
     */
    $recorte  = recorteDeModoNino('a');
    $sqlWhere .= $recorte['sql'];
    $params    = array_merge($params, $recorte['params']);

    // El orden se elige de una lista cerrada: nunca se interpola texto
    // del usuario dentro de un ORDER BY.
    $ordenes = [
        'nuevas'    => 'a.published_at DESC, a.id DESC',
        'titulo'    => 'a.title ASC',
        // Por defecto se ordena por bloque: así el catálogo llega ya
        // agrupado y la vista no tiene que reordenarlo.
        'categoria' => 'c.sort_order ASC, COALESCE(b.sort_order, 999) ASC, a.id ASC',
        'nivel'     => 'l.sort_order ASC, a.title ASC',
    ];
    $orden = $ordenes[$f['orden'] ?? ''] ?? $ordenes['categoria'];

    $total = (int) traerValor(
        'SELECT COUNT(*) ' . UNIONES_TARJETA . ' ' . $sqlWhere,
        $params
    );

    // El tope existe para que un LIMIT disparatado no pueda pedir el
    // catálogo entero muchas veces. Tiene que quedar por encima del
    // número de actividades publicadas: la vista agrupada las trae todas
    // de una vez, y un tope más bajo recortaría el catálogo en silencio
    // en vez de dar un error — el peor tipo de fallo, porque nadie lo ve.
    $limite = max(1, min(400, (int) ($f['limite'] ?? 24)));
    $desde  = max(0, (int) ($f['desplazamiento'] ?? 0));

    // LIMIT/OFFSET se insertan como enteros ya validados: PDO no admite
    // marcadores en esa posición con sentencias preparadas reales.
    $filas = traerTodo(
        'SELECT ' . CAMPOS_TARJETA . ' ' . UNIONES_TARJETA . ' ' . $sqlWhere .
        ' ORDER BY ' . $orden . " LIMIT $limite OFFSET $desde",
        $params
    );

    return ['filas' => $filas, 'total' => $total];
}

/** Escapa los comodines de LIKE para que se busquen como texto normal. */
function escaparLike(string $texto): string
{
    return str_replace(['\\', '%', '_'], ['\\\\', '\%', '\_'], $texto);
}


// =====================================================================
//  SELECCIONES PARA LA PÁGINA DE INICIO
// =====================================================================

/**
 * El recorte del modo niño, tolerando que el módulo no esté cargado.
 *
 * Existe para que las ocho consultas de este archivo puedan pedirlo sin
 * preguntar primero si la función está: con el módulo ausente devuelve
 * un fragmento vacío y la consulta queda exactamente como estaba.
 *
 * @return array{sql:string, params:array}
 */
function recorteDeModoNino(string $alias = 'a'): array
{
    return function_exists('filtroDeModoNino')
        ? filtroDeModoNino($alias)
        : ['sql' => '', 'params' => []];
}

/** Actividades marcadas como destacadas. */
function actividadesDestacadas(int $limite = 6): array
{
    $limite  = max(1, min(24, $limite));
    $recorte = recorteDeModoNino('a');

    return traerTodo(
        'SELECT ' . CAMPOS_TARJETA . ' ' . UNIONES_TARJETA . '
          WHERE a.status = "published" AND a.is_featured = 1' . $recorte['sql'] . '
       ORDER BY a.published_at DESC
          LIMIT ' . $limite,
        $recorte['params']
    );
}

/**
 * Actividades publicadas recientemente.
 * Sostiene el argumento comercial: la biblioteca crece.
 */
function actividadesNuevas(int $limite = 4): array
{
    $limite  = max(1, min(24, $limite));
    $recorte = recorteDeModoNino('a');

    return traerTodo(
        'SELECT ' . CAMPOS_TARJETA . ' ' . UNIONES_TARJETA . '
          WHERE a.status = "published" AND a.published_at IS NOT NULL' . $recorte['sql'] . '
       ORDER BY a.published_at DESC, a.id DESC
          LIMIT ' . $limite,
        $recorte['params']
    );
}

/** Cuántas actividades publicadas hay en total. */
function totalActividades(): int
{
    $recorte = recorteDeModoNino('a');

    return (int) traerValor(
        'SELECT COUNT(*) FROM activities a
          WHERE a.status = "published"' . $recorte['sql'],
        $recorte['params']
    );
}

/**
 * Actividades que el usuario ya empezó y no ha terminado.
 * Es lo primero que quiere ver al entrar: seguir donde lo dejó.
 */
function actividadesEnProgreso(int $usuarioId, int $limite = 4): array
{
    $limite = max(1, min(12, $limite));

    $recorte = recorteDeModoNino('a');

    return traerTodo(
        'SELECT ' . CAMPOS_TARJETA . ',
                COUNT(p.id) AS hechas,
                (SELECT COUNT(1) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones,
                MAX(p.updated_at) AS ultima
           ' . UNIONES_TARJETA . '
           JOIN activity_progress p ON p.activity_id = a.id AND p.user_id = ?
          WHERE a.status = "published"' . $recorte['sql'] . '
       GROUP BY a.id
         HAVING hechas < estaciones
       ORDER BY ultima DESC
          LIMIT ' . $limite,
        array_merge([$usuarioId], $recorte['params'])
    );
}

/** Cuántas actividades ha completado por entero el usuario. */
function actividadesCompletadas(int $usuarioId): int
{
    return (int) traerValor(
        'SELECT COUNT(1) FROM (
             SELECT a.id
               FROM activities a
               JOIN activity_progress p ON p.activity_id = a.id AND p.user_id = ?
                                       AND p.status = "completed"
           GROUP BY a.id
             HAVING COUNT(p.id) >= (SELECT COUNT(1) FROM activity_stations s WHERE s.activity_id = a.id)
         ) AS t',
        [$usuarioId]
    );
}

/** Favoritas del usuario. */
function favoritasDe(int $usuarioId, int $limite = 8): array
{
    $limite = max(1, min(24, $limite));

    $recorte = recorteDeModoNino('a');

    return traerTodo(
        'SELECT ' . CAMPOS_TARJETA . '
           ' . UNIONES_TARJETA . '
           JOIN favorites f ON f.activity_id = a.id
          WHERE f.user_id = ? AND a.status = "published"' . $recorte['sql'] . '
       ORDER BY f.created_at DESC
          LIMIT ' . $limite,
        array_merge([$usuarioId], $recorte['params'])
    );
}


// =====================================================================
//  UNA ACTIVIDAD
// =====================================================================

/** Actividad publicada por su slug, o null. */
function actividadPorSlug(string $slug): ?array
{
    return traerUno(
        'SELECT ' . CAMPOS_TARJETA . ', a.instructions, a.objective, a.content
           ' . UNIONES_TARJETA . '
          WHERE a.slug = ? AND a.status = "published"',
        [$slug]
    );
}

/** Estaciones de una actividad, en orden. */
function estacionesDe(int $actividadId): array
{
    return traerTodo(
        'SELECT id, activity_id, position, title, description, icon, game_type, config, is_free
           FROM activity_stations
          WHERE activity_id = ?
       ORDER BY position',
        [$actividadId]
    );
}

/** Actividades parecidas, para seguir explorando desde la ficha. */
function actividadesRelacionadas(array $actividad, int $limite = 4): array
{
    $limite  = max(1, min(12, $limite));
    $recorte = recorteDeModoNino('a');

    return traerTodo(
        'SELECT ' . CAMPOS_TARJETA . ' ' . UNIONES_TARJETA . '
          WHERE a.status = "published"
            AND a.id <> ?
            AND a.category_id = (SELECT category_id FROM activities WHERE id = ?)'
            . $recorte['sql'] . '
       ORDER BY RAND()
          LIMIT ' . $limite,
        array_merge([$actividad['id'], $actividad['id']], $recorte['params'])
    );
}


// =====================================================================
//  PLANES
// =====================================================================

/** Planes que se ofrecen hoy, en orden de presentación. */
function planesActivos(): array
{
    $planes = traerTodo(
        'SELECT id, slug, name, tagline, description,
                price_monthly_cop, price_yearly_cop, catalog_access,
                manages_courses, is_recommended, features
           FROM plans
          WHERE is_active = 1
       ORDER BY sort_order'
    );

    foreach ($planes as &$p) {
        $p['features'] = json_decode($p['features'] ?? '[]', true) ?: [];
        $p['ahorro']   = ahorroAnual(
            $p['price_monthly_cop'] !== null ? (int) $p['price_monthly_cop'] : null,
            $p['price_yearly_cop']  !== null ? (int) $p['price_yearly_cop']  : null
        );
    }

    return $planes;
}

/** Un plan por su slug. */
function planPorSlug(string $slug): ?array
{
    return traerUno('SELECT * FROM plans WHERE slug = ?', [$slug]);
}
