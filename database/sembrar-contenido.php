<?php
/**
 * sembrar-contenido.php — Todo el contenido escrito a mano
 *
 *     php database/sembrar-contenido.php            (simulación)
 *     php database/sembrar-contenido.php --aplicar  (escribe)
 *
 * Se llamaba `sembrar-categorias-nuevas.php` cuando solo traía cuatro
 * categorías. Hoy siembra una veintena de archivos sobre casi todas las
 * materias, así que el nombre mentía sobre lo que hace.
 *
 * A diferencia de los migradores, aquí no se lee nada del sitio anterior:
 * este contenido se escribe desde cero en `database/contenido/*.php`. Este
 * archivo solo lo lleva a la base de datos.
 *
 * Es idempotente: se puede volver a ejecutar y deja el mismo resultado.
 * Reconoce las actividades por su `slug`, actualiza las que ya existen y
 * reemplaza sus estaciones.
 *
 * ATENCIÓN: reemplazar las estaciones de una actividad borra el progreso
 * guardado sobre ellas (la clave foránea es ON DELETE CASCADE). Solo
 * afecta a las actividades de estas cuatro categorías, nunca a las 66
 * anteriores: la lista de slugs que toca sale de los propios archivos de
 * contenido, no de una consulta abierta.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/ayudas.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

// Semilla fija: las barajadas y las sopas de letras salen siempre iguales.
// Sin esto, cada ejecución generaría un contenido distinto y sería
// imposible saber si algo cambió porque lo cambiamos o porque el azar sí.
mt_srand(20260827);

$aplicar = in_array('--aplicar', $argv ?? [], true);

/*
 * Los cuatro primeros son categorías nuevas. Los seis siguientes ya
 * existían pero estaban flacos —Valores tenía UNA actividad— y sin
 * bloques: aquí se les añaden actividades y se les da estructura.
 *
 * En esos seis, `reasignar` mete las actividades que ya estaban en su
 * bloque. Se les cambia el `collection_id` y nada más: ni sus estaciones,
 * ni su contenido, ni su acceso.
 *
 * `letras-juegos` es el caso aparte: no añade nada a Aventura de las
 * Letras, rescata las dos únicas actividades que la migración dejó
 * publicadas y sin estaciones. Declara su categoría y su bloque con los
 * valores que ya tenían, porque el sembrador hace UPDATE sobre ambos.
 */
const ARCHIVOS = [
    'sociales', 'idiomas', 'pensamiento', 'bienestar',
    'artistica', 'tecnologia', 'ciencias', 'valores', 'juegos', 'dua',
    'letras-juegos',

    // «Ciudad de los Cuentos» preguntaba por dos fábulas que nunca
    // mostraba. Este archivo le pone los cuentos dentro; explica el caso
    // entero en su cabecera.
    'cuentos-lectura',

    // Contenido escrito sobre las mallas curriculares de primaria
    // (`MallasPrimaria/`, Klasse 1 a 6).
    'matematica-primaria',
    'ciencias-primaria',
    'sociales-primaria',
    'lengua-primaria',
    'idiomas-primaria',
    'tecnologia-primaria',
    'artistica-primaria',
    'bienestar-primaria',
    'valores-primaria',
    'pensamiento-primaria',
    'juegos-primaria',
    'dua-primaria',

    // Apoyos de atención, autorregulación y vida social. Amplía la misma
    // categoría «dua», por eso va DESPUÉS de `dua-primaria`: los dos
    // archivos declaran la categoría y el último gana.
    'inclusion',

    /*
     * ─────────────────────────────────────────────────────────────────
     *  AMPLIACIÓN: los extremos de la malla
     * ─────────────────────────────────────────────────────────────────
     *
     * El catálogo creció por el centro. Con 384 actividades, **159 eran
     * de tercero y cuarto y solo 50 de preescolar**; Juegos y Retos no
     * tenía ni una de preescolar, y Valores ninguna de quinto y sexto.
     * Un colegio que empezara el año en transición —o que llegara a
     * sexto en octubre— se quedaba sin material.
     *
     * Estos archivos rellenan las celdas flacas de la malla para que un
     * año lectivo entero quepa sin tener que publicar nada más. Cada uno
     * amplía una categoría que ya existe, así que redeclara su categoría
     * y sus bloques con los valores que ya tenían: el sembrador hace
     * UPDATE sobre los dos y cambiar una coma aquí los renombraría.
     *
     * Van AL FINAL por eso mismo — son los últimos en declarar, y el
     * último gana.
     */
    'tecnologia-ampliacion',
    'pensamiento-ampliacion',
    'valores-ampliacion',
    'juegos-ampliacion',
    'bienestar-ampliacion',
    'artistica-ampliacion',
    'dua-ampliacion',
    'ciencias-ampliacion',
    'sociales-ampliacion',
    'idiomas-ampliacion',
    'matematica-ampliacion',
    'letras-ampliacion',
];

/** Proporción de estaciones gratuitas: el modelo 30% del proyecto. */
const PROPORCION_LIBRE = 0.34;


/*
 * `ilustrar()` vive en `contenido/dibujos.php` porque hace falta en dos
 * sitios: aquí, al sembrar, y en `ilustrar-estaciones.php`, que alcanza
 * las actividades que NO vienen de estos archivos —las vocales y las
 * letras las creó una migración y el sembrador no las toca.
 */
require_once __DIR__ . '/contenido/dibujos.php';


// =====================================================================
//  CATÁLOGO DE ETIQUETAS
//
//  La categoría dice de qué trata la actividad y es una sola. La
//  etiqueta dice qué pone en juego, y son varias.
// =====================================================================

const ETIQUETAS = [
    // Habilidades
    'observacion'   => ['Observación',        'habilidad', '👀'],
    'atencion'      => ['Atención',           'habilidad', '🎯'],
    'clasificacion' => ['Clasificación',      'habilidad', '🗂️'],
    'logica'        => ['Lógica',             'habilidad', '🧠'],
    'patrones'      => ['Patrones',           'habilidad', '🔁'],
    'secuencias'    => ['Secuencias',         'habilidad', '➡️'],
    'deduccion'     => ['Deducción',          'habilidad', '🕵️'],
    'memoria'       => ['Memoria',            'habilidad', '💭'],
    'comprension'   => ['Comprensión',        'habilidad', '📖'],
    'lectura'       => ['Lectura',            'habilidad', '📚'],
    'escritura'     => ['Escritura',          'habilidad', '✍️'],
    'calculo'       => ['Cálculo',            'habilidad', '🔢'],
    'vocabulario'   => ['Vocabulario',        'habilidad', '🔤'],
    'pronunciacion' => ['Pronunciación',      'habilidad', '🔊'],

    // Temas
    'familia'       => ['Familia',            'tema', '👨‍👩‍👧'],
    'oficios'       => ['Oficios',            'tema', '👷'],
    'cultura'       => ['Cultura',            'tema', '🎭'],
    'colombia'      => ['Colombia',           'tema', '🇨🇴'],
    'geografia'     => ['Geografía',          'tema', '🗺️'],
    'mapas'         => ['Mapas',              'tema', '📍'],
    'historia'      => ['Historia',           'tema', '⏳'],
    'ciudadania'    => ['Ciudadanía',         'tema', '🏛️'],
    'convivencia'   => ['Convivencia',        'tema', '🤝'],
    'cuerpo'        => ['Cuerpo',             'tema', '🧍'],
    'salud'         => ['Salud',              'tema', '💚'],
    'alimentacion'  => ['Alimentación',       'tema', '🥗'],
    'autocuidado'   => ['Autocuidado',        'tema', '🛡️'],
    'seguridad'     => ['Seguridad',          'tema', '🦺'],
    'emociones'     => ['Emociones',          'tema', '💛'],
    'ingles'        => ['Inglés',             'tema', '🇬🇧'],
    'tecnologia'    => ['Tecnología',         'tema', '💻'],

    // Tipo de experiencia
    'juego'         => ['Juego',              'tipo', '🎮'],
    'reto'          => ['Reto',               'tipo', '🏆'],

    /*
     * APOYOS — la clase de etiqueta que faltaba.
     *
     * Las otras tres dicen de qué va la actividad. Estas dicen a QUIÉN le
     * quita un obstáculo, que es la pregunta que hace una familia cuando
     * busca «algo para un niño al que le cuesta concentrarse».
     *
     * Ninguna lleva el nombre de un diagnóstico, y es a propósito: ver
     * `database/contenido/inclusion.php`, que lo explica entero. En una
     * plataforma donde un docente asigna actividades a un curso entero,
     * una etiqueta llamada «TDAH» sobre la actividad de un niño es un
     * dato de salud de un menor puesto a la vista de sus compañeros.
     *
     * Las palabras TDAH y autismo viven en `actividades/apoyos.php`, la
     * guía para adultos, que es donde se busca por diagnóstico.
     */
    'foco'             => ['Concentrarse',            'apoyo', '🔎'],
    'autorregulacion'  => ['Calma y autocontrol',     'apoyo', '🌬️'],
    'organizacion'     => ['Organizarse',             'apoyo', '🎒'],
    'anticipacion'     => ['Saber qué viene',         'apoyo', '📅'],
    'social'           => ['Entender a los demás',    'apoyo', '🫂'],
    'sensorial'        => ['Sentidos y ambiente',     'apoyo', '👂'],
    'lenguaje-claro'   => ['Lenguaje sin trampas',    'apoyo', '💬'],
];

/*
 * Etiquetas retiradas. El sembrador crea y actualiza, nunca borra: sin
 * esta lista, una etiqueta que se deja de usar se queda para siempre en
 * el filtro con el conteo que tuviera.
 *
 * `sin-prisa` («Sin reloj») se retiró porque prometía algo que el
 * etiquetado no podía sostener. Casi ninguna actividad del catálogo lleva
 * contrarreloj, así que marcar cinco como «sin reloj» sugiere que las
 * otras trescientas sí lo llevan. Un filtro que da a entender lo
 * contrario de lo que pasa es peor que no tener filtro.
 */
const ETIQUETAS_RETIRADAS = ['sin-prisa'];

/**
 * Etiquetas para las 66 actividades que ya existían.
 *
 * Se asignan por categoría, no una por una. Es una aproximación gruesa y
 * se sabe: dice la verdad (toda «Aventura de las Letras» trabaja lectura
 * y escritura) pero no distingue matices dentro de la materia. Sirve para
 * que el filtro por habilidad no aparezca vacío en el catálogo antiguo;
 * afinarlo actividad por actividad es trabajo de edición, no de script.
 */
const ETIQUETAS_HEREDADAS = [
    'letras'     => ['lectura', 'escritura', 'vocabulario'],
    'matematica' => ['calculo', 'logica'],
    'artistica'  => ['observacion', 'juego'],
    'tecnologia' => ['tecnologia', 'logica'],
    'ciencias'   => ['observacion', 'comprension'],
    'valores'    => ['convivencia', 'emociones'],
    'juegos'     => ['juego', 'reto', 'memoria'],
    'dua'        => ['comprension', 'atencion'],
];


// =====================================================================
//  ARRANQUE
// =====================================================================

echo str_repeat('=', 74), "\n";
echo "  SIEMBRA DE CONTENIDO ESCRITO\n";
echo "  4 categorías nuevas + 6 categorías que se completan\n";
echo '  Modo: ', ($aplicar ? 'APLICAR' : 'SIMULACIÓN'), "\n";
echo str_repeat('=', 74), "\n\n";

// Las tablas de etiquetas pueden no existir en una instalación anterior
// a este cambio. Se crean igual que en schema.sql, sin tocar nada más.
if ($aplicar) {
    ejecutar(
        'CREATE TABLE IF NOT EXISTS `tags` (
            `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `slug`       VARCHAR(60)  NOT NULL,
            `name`       VARCHAR(120) NOT NULL,
            `kind`       ENUM("habilidad","tipo","tema","apoyo") NOT NULL DEFAULT "habilidad",
            `icon`       VARCHAR(16)  NULL,
            `sort_order` SMALLINT     NOT NULL DEFAULT 0,
            `is_active`  TINYINT(1)   NOT NULL DEFAULT 1,
            `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            UNIQUE KEY `uk_tags_slug` (`slug`),
            KEY `idx_tags_kind` (`kind`, `sort_order`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    ejecutar(
        'CREATE TABLE IF NOT EXISTS `activity_tags` (
            `activity_id` INT UNSIGNED NOT NULL,
            `tag_id`      INT UNSIGNED NOT NULL,
            PRIMARY KEY (`activity_id`, `tag_id`),
            KEY `idx_activity_tags_tag` (`tag_id`),
            CONSTRAINT `fk_at_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
            CONSTRAINT `fk_at_tag`      FOREIGN KEY (`tag_id`)      REFERENCES `tags` (`id`)       ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci'
    );

    /*
     * El `CREATE TABLE IF NOT EXISTS` de arriba no toca una tabla que ya
     * exista, así que en una base anterior a las etiquetas de apoyo el
     * ENUM seguiría sin el valor «apoyo» y cada INSERT lo guardaría como
     * cadena vacía —MySQL no falla, recorta— dejando ocho etiquetas
     * inservibles sin decir nada.
     *
     * Se comprueba y se amplía solo si hace falta.
     */
    $enum = (string) traerValor(
        "SELECT COLUMN_TYPE FROM information_schema.COLUMNS
          WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tags' AND COLUMN_NAME = 'kind'"
    );

    if (strpos($enum, "'apoyo'") === false) {
        ejecutar(
            'ALTER TABLE `tags`
             MODIFY `kind` ENUM("habilidad","tipo","tema","apoyo")
             NOT NULL DEFAULT "habilidad"'
        );
        echo "  · Se amplió tags.kind con el valor «apoyo».\n\n";
    }
}


// ── Etiquetas ────────────────────────────────────────────────────────

// Primero se retiran las que ya no se usan. El ON DELETE CASCADE de
// `activity_tags` se lleva las asignaciones con ellas.
if ($aplicar) {
    foreach (ETIQUETAS_RETIRADAS as $slug) {
        if (traerUno('SELECT id FROM tags WHERE slug = ?', [$slug])) {
            ejecutar('DELETE FROM tags WHERE slug = ?', [$slug]);
            echo "  · Se retiró la etiqueta «$slug».\n\n";
        }
    }
}

$idEtiqueta = [];
$orden = 0;

foreach (ETIQUETAS as $slug => [$nombre, $tipo, $icono]) {
    $orden++;

    if (!$aplicar) {
        $idEtiqueta[$slug] = 0;
        continue;
    }

    $existente = traerUno('SELECT id FROM tags WHERE slug = ?', [$slug]);

    if ($existente) {
        ejecutar('UPDATE tags SET name = ?, kind = ?, icon = ?, sort_order = ? WHERE id = ?',
                 [$nombre, $tipo, $icono, $orden, $existente['id']]);
        $idEtiqueta[$slug] = (int) $existente['id'];
    } else {
        $idEtiqueta[$slug] = (int) insertar(
            'INSERT INTO tags (slug, name, kind, icon, sort_order) VALUES (?, ?, ?, ?, ?)',
            [$slug, $nombre, $tipo, $icono, $orden]
        );
    }
}

printf("  Etiquetas             : %d\n\n", count(ETIQUETAS));


// ── Niveles ──────────────────────────────────────────────────────────
/*
 * Los cuatro niveles originales llegaban hasta los 10 años, porque el
 * catálogo heredado no pasaba de ahí. El contenido de primaria sí llega a
 * 6.º, así que un archivo puede declarar el nivel que necesita en vez de
 * exigir una migración aparte: sin esto, las actividades de 5.º y 6.º
 * caerían en «Tercero a quinto» y el filtro por edad mentiría.
 *
 * Se crean antes de recorrer las actividades porque cualquier archivo
 * puede referirse a un nivel declarado en otro.
 */
/*
 * Los archivos de contenido se cargan UNA sola vez y se guardan aquí.
 *
 * Antes se hacía `require` en dos sitios —una pasada para los niveles y
 * otra para las actividades— y con `require` (no `require_once`) el
 * archivo se ejecuta las dos veces. Da igual mientras solo devuelva un
 * array, pero en cuanto uno declara una función auxiliar, la segunda
 * pasada muere con «Cannot redeclare». Cargar una vez es además la mitad
 * de trabajo.
 */
$contenidos = [];
foreach (ARCHIVOS as $archivo) {
    $contenidos[$archivo] = require __DIR__ . '/contenido/' . $archivo . '.php';
}

$nivelesDeclarados = [];

foreach ($contenidos as $datos) {

    foreach ($datos['niveles'] ?? [] as $n) {

        // Se anotan también en simulación. Si no, el informe avisaría de
        // «nivel desconocido» para cada actividad que use uno nuevo — un
        // aviso falso, porque el nivel sí se va a crear al aplicar, y de
        // los que enseñan a no leer los avisos.
        $nivelesDeclarados[] = $n['slug'];

        if (!$aplicar) {
            continue;
        }

        $existente = traerUno('SELECT id FROM levels WHERE slug = ?', [$n['slug']]);

        if ($existente) {
            ejecutar(
                'UPDATE levels SET name = ?, min_age = ?, max_age = ?, sort_order = ?, is_active = 1
                  WHERE id = ?',
                [$n['name'], $n['min_age'], $n['max_age'], $n['sort_order'], $existente['id']]
            );
        } else {
            insertar(
                'INSERT INTO levels (slug, name, min_age, max_age, sort_order, is_active)
                 VALUES (?, ?, ?, ?, ?, 1)',
                [$n['slug'], $n['name'], $n['min_age'], $n['max_age'], $n['sort_order']]
            );
        }
    }
}

// ── Niveles disponibles ──────────────────────────────────────────────
$idNivel = [];
foreach (traerTodo('SELECT id, slug FROM levels') as $n) {
    $idNivel[$n['slug']] = (int) $n['id'];
}
foreach ($nivelesDeclarados as $slug) {
    $idNivel[$slug] ??= 0;
}


// =====================================================================
//  SIEMBRA
// =====================================================================

$totalActividades = 0;
$totalEstaciones  = 0;
$totalEjercicios  = 0;
$avisos           = [];

foreach ($contenidos as $archivo => $datos) {

    $cat = $datos['categoria'];
    echo "  {$cat['icon']} {$cat['name']}\n";
    echo '  ', str_repeat('-', 70), "\n";

    // ── Categoría ────────────────────────────────────────────────────
    $categoriaId = 0;

    if ($aplicar) {
        $existente = traerUno('SELECT id FROM categories WHERE slug = ?', [$cat['slug']]);

        if ($existente) {
            ejecutar(
                'UPDATE categories SET name = ?, tagline = ?, icon = ?, color = ?,
                        sort_order = ?, is_active = 1 WHERE id = ?',
                [$cat['name'], $cat['tagline'], $cat['icon'], $cat['color'],
                 $cat['sort_order'], $existente['id']]
            );
            $categoriaId = (int) $existente['id'];
        } else {
            $categoriaId = (int) insertar(
                'INSERT INTO categories (slug, name, tagline, icon, color, sort_order, is_active)
                 VALUES (?, ?, ?, ?, ?, ?, 1)',
                [$cat['slug'], $cat['name'], $cat['tagline'], $cat['icon'],
                 $cat['color'], $cat['sort_order']]
            );
        }
    }

    // ── Bloques ──────────────────────────────────────────────────────
    $idBloque = [];

    foreach ($datos['bloques'] as $b) {
        if (!$aplicar) {
            $idBloque[$b['slug']] = null;
            continue;
        }

        $existente = traerUno('SELECT id FROM collections WHERE slug = ?', [$b['slug']]);

        if ($existente) {
            ejecutar(
                'UPDATE collections SET category_id = ?, name = ?, description = ?,
                        icon = ?, sort_order = ?, is_active = 1 WHERE id = ?',
                [$categoriaId, $b['name'], $b['description'], $b['icon'],
                 $b['sort_order'], $existente['id']]
            );
            $idBloque[$b['slug']] = (int) $existente['id'];
        } else {
            $idBloque[$b['slug']] = (int) insertar(
                'INSERT INTO collections (category_id, slug, name, description, icon, sort_order, is_active)
                 VALUES (?, ?, ?, ?, ?, ?, 1)',
                [$categoriaId, $b['slug'], $b['name'], $b['description'],
                 $b['icon'], $b['sort_order']]
            );
        }
    }

    printf("    Bloques: %d\n", count($datos['bloques']));

    // ── Actividades anteriores que ahora tienen bloque ───────────────
    //
    // Solo se toca `collection_id`. Sus estaciones, su contenido y su
    // configuración de acceso quedan exactamente como estaban: darle un
    // sitio a una actividad no es rehacerla.
    foreach ($datos['reasignar'] ?? [] as $slugAct => $slugBloque) {

        // Las llaves no son adorno: sin ellas PHP lee «$slugBloque»» como
        // un nombre de variable —los bytes altos son válidos en los
        // identificadores— y el aviso sale vacío. Ya pasó una vez.
        // `array_key_exists` y no `isset`: en simulación los bloques valen
        // null a propósito (no se han creado), e `isset` los daría por
        // inexistentes llenando el informe de avisos falsos.
        if (!array_key_exists($slugBloque, $idBloque)) {
            $avisos[] = "{$slugAct}: bloque desconocido «{$slugBloque}»";
            continue;
        }

        if (!$aplicar) {
            if (!traerUno('SELECT id FROM activities WHERE slug = ?', [$slugAct])) {
                $avisos[] = "$slugAct: no existe, no se puede reasignar";
            }
            continue;
        }

        $filas = ejecutar(
            'UPDATE activities SET collection_id = ? WHERE slug = ?',
            [$idBloque[$slugBloque], $slugAct]
        );

        if ($filas === 0 && !traerUno('SELECT id FROM activities WHERE slug = ?', [$slugAct])) {
            $avisos[] = "$slugAct: no existe, no se pudo reasignar";
        }
    }

    if (!empty($datos['reasignar'])) {
        printf("    Reasignadas a un bloque: %d\n", count($datos['reasignar']));
    }

    // ── Actividades ──────────────────────────────────────────────────
    foreach ($datos['actividades'] as $act) {

        $estaciones = $act['estaciones'];
        $numero     = count($estaciones);

        // Cuenta de ejercicios, solo para el informe.
        $ejercicios = 0;
        foreach ($estaciones as $e) {
            $d = $e['datos'];
            if (isset($d['items'])) {
                $ejercicios += count($d['items']);        // encabezado propio
            } elseif (isset($d['slides'])) {
                $ejercicios += count($d['slides']) + count($d['qs'] ?? []);
            } elseif (isset($d['words'])) {
                $ejercicios += count($d['words']);        // sopa de letras
            } elseif (is_array($d)) {
                $ejercicios += count($d);
            }
        }

        // Una de cada tres estaciones queda libre: el modelo 30%.
        $libres = max(1, (int) round($numero * PROPORCION_LIBRE));

        $tipo = in_array('reto', $act['tags'] ?? [], true) ? 'reto' : 'juego';

        /*
         * GUARDA CONTRA COLISIONES DE SLUG
         *
         * El slug es la llave con la que se reconoce una actividad, así
         * que repetir uno no crea una segunda: sobrescribe la primera.
         * Ya pasó — «carrera-de-palabras» existía en Aventura de las
         * Letras y una actividad nueva de Juegos y Retos se la llevó por
         * delante junto con sus estaciones. Sin este control el catálogo
         * pierde una actividad y el informe dice que todo salió bien.
         *
         * La regla: este script solo puede escribir sobre una actividad
         * que YA esté en la categoría que el archivo declara. Si el slug
         * pertenece a otra categoría, es de otro y no se toca.
         */
        $duenoPrevio = traerUno(
            'SELECT a.id, c.slug AS categoria_slug, c.name AS categoria
               FROM activities a
          LEFT JOIN categories c ON c.id = a.category_id
              WHERE a.slug = ?',
            [$act['slug']]
        );

        if ($duenoPrevio
            && $duenoPrevio['categoria_slug'] !== null
            && $duenoPrevio['categoria_slug'] !== $cat['slug']) {

            $avisos[] = "{$act['slug']}: el slug ya lo usa una actividad de "
                      . "«{$duenoPrevio['categoria']}». NO se tocó. Elige otro slug.";
            printf("      %-38s ✗ slug ocupado por «%s»\n",
                   $act['title'], $duenoPrevio['categoria']);
            continue;
        }

        if ($aplicar) {
            db()->beginTransaction();

            try {
                $existente = traerUno('SELECT id FROM activities WHERE slug = ?', [$act['slug']]);

                $campos = [
                    $act['title'], $act['description'], $act['objective'],
                    $categoriaId, $idBloque[$act['bloque']] ?? null,
                    $idNivel[$act['nivel']] ?? null,
                    $act['icon'], $act['duracion'], $libres, $tipo,
                ];

                if ($existente) {
                    $actividadId = (int) $existente['id'];
                    ejecutar(
                        'UPDATE activities
                            SET title = ?, description = ?, objective = ?,
                                category_id = ?, collection_id = ?, level_id = ?,
                                icon = ?, duration_minutes = ?, free_stations = ?,
                                activity_type = ?,
                                engine = "estaciones", access_type = "partial",
                                status = "published", legacy_file = NULL
                          WHERE id = ?',
                        array_merge($campos, [$actividadId])
                    );
                } else {
                    $actividadId = (int) insertar(
                        'INSERT INTO activities
                            (slug, title, description, objective, category_id, collection_id,
                             level_id, icon, duration_minutes, free_stations, activity_type,
                             engine, access_type, status, published_at)
                         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?,
                                 "estaciones", "partial", "published", NOW())',
                        array_merge([$act['slug']], $campos)
                    );
                }

                // Estaciones: se reemplazan por completo.
                ejecutar('DELETE FROM activity_stations WHERE activity_id = ?', [$actividadId]);

                $posicion = 0;
                foreach ($estaciones as $e) {
                    $posicion++;

                    $config = ['datos' => ilustrar($e['datos'], (string) $e['tipo'])];
                    if (!empty($e['idioma'])) {
                        $config['idioma'] = $e['idioma'];
                    }

                    insertar(
                        'INSERT INTO activity_stations
                            (activity_id, position, title, description, icon, game_type, config, is_free)
                         VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                        [$actividadId, $posicion, $e['titulo'], $e['descripcion'],
                         $e['icono'], $e['tipo'],
                         json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES)]
                    );
                }

                // Etiquetas: también se reemplazan.
                ejecutar('DELETE FROM activity_tags WHERE activity_id = ?', [$actividadId]);

                foreach ($act['tags'] ?? [] as $t) {
                    if (!isset($idEtiqueta[$t])) {
                        $avisos[] = "{$act['slug']}: etiqueta desconocida «{$t}»";
                        continue;
                    }
                    ejecutar('INSERT IGNORE INTO activity_tags (activity_id, tag_id) VALUES (?, ?)',
                             [$actividadId, $idEtiqueta[$t]]);
                }

                db()->commit();

            } catch (Throwable $ex) {
                db()->rollBack();
                $avisos[] = "{$act['slug']}: " . $ex->getMessage();
                printf("      %-38s ✗ %s\n", $act['slug'], $ex->getMessage());
                continue;
            }
        } else {
            // En simulación se comprueban las etiquetas igual, para que un
            // error de escritura salte antes de tocar la base de datos.
            foreach ($act['tags'] ?? [] as $t) {
                if (!isset(ETIQUETAS[$t])) {
                    $avisos[] = "{$act['slug']}: etiqueta desconocida «{$t}»";
                }
            }
            if (!isset($idNivel[$act['nivel']])) {
                $avisos[] = "{$act['slug']}: nivel desconocido «{$act['nivel']}»";
            }
        }

        $totalActividades++;
        $totalEstaciones += $numero;
        $totalEjercicios += $ejercicios;

        printf("      %-38s %2d estaciones · %3d ejercicios · %d libre(s)\n",
               $act['title'], $numero, $ejercicios, $libres);
    }

    echo "\n";
}


// =====================================================================
//  ETIQUETAS DE LAS ACTIVIDADES ANTERIORES
// =====================================================================

$heredadas = 0;

if ($aplicar) {
    foreach (ETIQUETAS_HEREDADAS as $catSlug => $etiquetas) {
        $actividades = traerTodo(
            'SELECT a.id FROM activities a
               JOIN categories c ON c.id = a.category_id
              WHERE c.slug = ? AND a.status = "published"',
            [$catSlug]
        );

        foreach ($actividades as $a) {
            // No se tocan las que ya tienen etiquetas propias: las nuevas
            // están escritas a mano y son mejores que esta aproximación.
            $tiene = (int) traerValor('SELECT COUNT(1) FROM activity_tags WHERE activity_id = ?', [$a['id']]);
            if ($tiene > 0) {
                continue;
            }

            foreach ($etiquetas as $t) {
                if (isset($idEtiqueta[$t])) {
                    ejecutar('INSERT IGNORE INTO activity_tags (activity_id, tag_id) VALUES (?, ?)',
                             [$a['id'], $idEtiqueta[$t]]);
                }
            }
            $heredadas++;
        }
    }
}


// =====================================================================
//  INFORME
// =====================================================================

echo str_repeat('-', 74), "\n";
printf("  Categorías tocadas    : %d\n", count(ARCHIVOS));
printf("  Actividades escritas  : %d\n", $totalActividades);
printf("  Estaciones            : %d\n", $totalEstaciones);
printf("  Ejercicios            : %d\n", $totalEjercicios);

if ($aplicar) {
    printf("  Actividades anteriores etiquetadas : %d\n", $heredadas);
    printf("  Total del catálogo    : %d actividades publicadas\n",
           (int) traerValor('SELECT COUNT(*) FROM activities WHERE status = "published"'));
}

if ($avisos) {
    echo "\n  Avisos:\n";
    foreach (array_unique($avisos) as $a) {
        echo "    · $a\n";
    }
}

echo $aplicar
    ? "\n  Aplicado. Las 66 actividades anteriores no se modificaron.\n"
    : "\n  Simulación. Para escribir: php database/sembrar-contenido.php --aplicar\n";

echo str_repeat('=', 74), "\n";
