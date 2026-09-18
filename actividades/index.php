<?php
/**
 * actividades/index.php — Catálogo público
 *
 * Búsqueda, filtros por categoría, nivel y acceso, ordenamiento y
 * paginación. Todo se resuelve en el servidor con enlaces normales:
 * los filtros quedan en la URL, así que una búsqueda se puede compartir
 * o guardar en favoritos, y funciona sin JavaScript.
 */

require_once dirname(__DIR__) . '/config/config.php';

/*
 * El catálogo no es para un niño que está en una clase.
 *
 * Con la ruta activa vería 384 actividades y podría jugar tres. Un muro
 * de candados no es un catálogo: es una lista de cosas que no puede
 * hacer, y la única pregunta que tiene un niño de seis años delante de la
 * pantalla —«¿qué hago ahora?»— se queda sin responder.
 *
 * El bloqueo de verdad está en `acceso.php`; esto solo evita el muro.
 */
if (enRutaGuiada()) {
    redirigir('usuario/ruta.php');
}

// ── Filtros recibidos ────────────────────────────────────────────────
$fCategoria = get('categoria');
$fBloque    = get('bloque');
$fNivel     = get('nivel');
$fAcceso    = get('acceso');
$fEtiqueta  = get('etiqueta');
$fBuscar    = mb_substr(get('buscar'), 0, 80);
$fOrden     = get('orden', 'categoria');
$pagina     = max(1, getEntero('pagina', 1));

const POR_PAGINA = 36;

/**
 * Cuántas tarjetas se muestran por bloque en la vista general.
 *
 * El catálogo pasó de 66 a 104 actividades. Mostrarlas todas de una vez
 * convierte la página en un muro por el que hay que desplazarse un minuto
 * antes de llegar al final, que es justo lo que los bloques venían a
 * evitar. En la vista general cada bloque enseña una muestra y ofrece
 * entrar; al elegir una categoría o un bloque concreto se ve todo.
 */
const MUESTRA_POR_BLOQUE = 6;

$bloqueActual = $fBloque !== '' ? bloquePorSlug($fBloque) : null;

// Si se pide un bloque, la categoría se deduce de él: evita que un
// enlace con bloque y categoría incompatibles no devuelva nada.
if ($bloqueActual) {
    $fCategoria = $bloqueActual['categoria_slug'];
}

/*
 * DIRECTORIO O LISTA
 *
 * Sin ningún filtro puesto, el catálogo no muestra actividades: muestra
 * las doce materias con sus bloques. Con 149 actividades, una lista
 * completa deja de ser un catálogo y pasa a ser un muro por el que hay
 * que desplazarse un minuto para saber qué hay.
 *
 * En cuanto el visitante elige algo —una materia, un bloque, una edad,
 * una habilidad, o escribe en el buscador— aparecen las tarjetas. Y
 * `?vista=lista` fuerza la lista completa para quien la quiera.
 */
$sinFiltros = $fCategoria === '' && $fBloque === '' && $fNivel === ''
           && $fAcceso === '' && $fEtiqueta === '' && $fBuscar === '';

$directorio = $sinFiltros && get('vista') !== 'lista';

// Se agrupa solo cuando tiene sentido: dentro de un bloque concreto no
// hay nada que agrupar, y al buscar por texto el orden lo manda la
// relevancia, no la estructura.
$agrupar = !$directorio && $bloqueActual === null && $fBuscar === '' && $fOrden === 'categoria';

// Al agrupar NO se pagina. Partir un bloque entre dos páginas rompe
// justamente lo que los bloques vienen a resolver: media docena de
// letras en una página y el resto en la siguiente no es una agrupación,
// es un corte arbitrario. Lo que se recorta es el número de tarjetas
// visibles DENTRO de cada bloque (ver MUESTRA_POR_BLOQUE), que sí
// conserva la estructura.
// En el directorio no se traen tarjetas: solo hace falta el total para
// poder anunciar cuántas actividades hay.
$limite = $directorio ? 1 : ($agrupar ? 300 : POR_PAGINA);
$desde  = ($agrupar || $directorio) ? 0 : ($pagina - 1) * POR_PAGINA;

$resultado = buscarActividades([
    'categoria'      => $fCategoria,
    'bloque'         => $fBloque,
    'nivel'          => $fNivel,
    'acceso'         => $fAcceso,
    'etiqueta'       => $fEtiqueta,
    'buscar'         => $fBuscar,
    'orden'          => $fOrden,
    'limite'         => $limite,
    'desplazamiento' => $desde,
]);

$actividades  = $resultado['filas'];
$totalHallado = $resultado['total'];
$totalPaginas = $agrupar ? 1 : max(1, (int) ceil($totalHallado / POR_PAGINA));

$categorias = categoriasConConteo();
$niveles    = nivelesActivos();
$categoriaActual = $fCategoria ? categoriaPorSlug($fCategoria) : null;
$etiquetaActual  = $fEtiqueta ? etiquetaPorSlug($fEtiqueta) : null;

// Bloques disponibles: los de la categoría elegida, o todos.
$bloques = bloquesDe($fCategoria !== '' ? $fCategoria : null);

// Habilidades y tipos de experiencia. Los temas no se ofrecen como
// filtro: son casi tantos como categorías y confundirían las dos cosas.
$habilidades = etiquetasConConteo('habilidad');
$tipos       = etiquetasConConteo('tipo');
$apoyos      = etiquetasConConteo('apoyo');

$grupos = $agrupar ? agruparEnBloques($actividades) : [];

// En la lista completa cada bloque enseña solo una muestra. Al entrar a
// una categoría concreta se ve entero.
$recortar = $agrupar && $fCategoria === '';

// Materias con sus bloques, solo cuando se va a dibujar el directorio.
$materias = $directorio ? directorioCategorias() : [];

/**
 * Construye una URL del catálogo cambiando solo algunos filtros y
 * conservando el resto. Volver a la página 1 al cambiar un filtro
 * evita quedar en una página que ya no existe.
 */
function urlFiltro(array $cambios): string
{
    $actuales = [
        'categoria' => get('categoria'),
        'bloque'    => get('bloque'),
        'nivel'     => get('nivel'),
        'acceso'    => get('acceso'),
        'etiqueta'  => get('etiqueta'),
        'buscar'    => get('buscar'),
        'orden'     => get('orden'),
        'pagina'    => getEntero('pagina', 1),
    ];

    // Cambiar de categoría invalida el bloque: pertenecía a la anterior.
    if (array_key_exists('categoria', $cambios) && !array_key_exists('bloque', $cambios)) {
        $actuales['bloque'] = '';
    }

    $nuevos = array_merge($actuales, $cambios);

    if (!array_key_exists('pagina', $cambios)) {
        $nuevos['pagina'] = 1;
    }

    // Se descartan los valores vacíos y los que ya son el valor por defecto.
    $nuevos = array_filter($nuevos, static fn($v, $k) =>
        $v !== '' && $v !== null && !($k === 'pagina' && (int) $v === 1),
        ARRAY_FILTER_USE_BOTH
    );

    return url('actividades/') . ($nuevos ? '?' . http_build_query($nuevos) : '');
}

$titulo = $categoriaActual
    ? $categoriaActual['name'] . ' · Actividades en Línea'
    : 'Explorar actividades · Actividades en Línea';
$descripcion   = 'Busca y filtra entre ' . totalActividades() . ' actividades interactivas por categoría, edad y tipo de acceso.';
$seccionActiva = 'actividades';

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('index.php')) ?>">Inicio</a>
            <span class="sep">›</span>
            <?php if ($categoriaActual): ?>
                <a href="<?= e(url('actividades/')) ?>">Explorar</a>
                <span class="sep">›</span>
                <?php if ($bloqueActual): ?>
                    <a href="<?= e(urlFiltro(['categoria' => $categoriaActual['slug'], 'bloque' => ''])) ?>">
                        <?= e($categoriaActual['name']) ?>
                    </a>
                    <span class="sep">›</span>
                    <span><?= e($bloqueActual['name']) ?></span>
                <?php else: ?>
                    <span><?= e($categoriaActual['name']) ?></span>
                <?php endif; ?>
            <?php else: ?>
                <span>Explorar</span>
            <?php endif; ?>
        </nav>

        <div class="titulo-seccion izquierda">
            <small><?= $bloqueActual ? e($categoriaActual['name'] ?? 'Catálogo') : 'Catálogo' ?></small>
            <h2>
                <?php if ($bloqueActual): ?>
                    <?= e(($bloqueActual['icon'] ?? '') . ' ' . $bloqueActual['name']) ?>
                <?php elseif ($categoriaActual): ?>
                    <?= e($categoriaActual['icon'] . ' ' . $categoriaActual['name']) ?>
                <?php else: ?>
                    Explorar actividades
                <?php endif; ?>
            </h2>
            <p>
                <?php if ($bloqueActual): ?>
                    <?= e($bloqueActual['description'] ?? '') ?>
                <?php elseif ($categoriaActual): ?>
                    <?= e($categoriaActual['tagline'] ?? '') ?>
                <?php else: ?>
                    Filtra por categoría, edad o tipo de acceso. La edad es un filtro, no una categoría.
                <?php endif; ?>
            </p>
        </div>

        <?php
        /*
         * Dentro de «Aprender sin Barreras» se ofrece la guía para
         * adultos. Es la categoría con más bloques y la única donde el
         * nombre del bloque no dice a quién le sirve —«Cuando Algo
         * Cambia» no se parece a lo que una familia viene buscando— así
         * que aquí es donde hace falta el puente.
         */
        ?>
        <?php if ($categoriaActual && $categoriaActual['slug'] === 'dua'): ?>
            <a class="tira-apoyos" href="<?= e(url('actividades/apoyos.php')) ?>">
                <span class="tira-apoyos-ico" aria-hidden="true">🧭</span>
                <span>
                    <strong>¿Buscas algo concreto para tu hijo o tu estudiante?</strong>
                    La guía de apoyos explica qué bloque sirve para qué: concentrarse,
                    calmarse, organizarse, entender a los demás.
                </span>
                <span class="tira-apoyos-ir" aria-hidden="true">→</span>
            </a>
        <?php endif; ?>

        <!-- ── Filtros ─────────────────────────────────────────────── -->
        <div class="panel-filtros">

            <form class="fila-buscar" method="get" action="<?= e(url('actividades/')) ?>" role="search">
                <div class="caja-buscar">
                    <span class="lupa" aria-hidden="true">🔍</span>
                    <label class="oculto-visual" for="buscar">Buscar actividades</label>
                    <input id="buscar" type="search" name="buscar"
                           value="<?= e($fBuscar) ?>"
                           placeholder="Buscar por nombre, tema o categoría…">
                </div>

                <?php /* Se conservan los demás filtros al buscar. */ ?>
                <?php foreach (['categoria' => $fCategoria, 'nivel' => $fNivel, 'acceso' => $fAcceso,
                                'etiqueta' => $fEtiqueta, 'orden' => $fOrden] as $k => $v): ?>
                    <?php if ($v !== '' && $v !== 'categoria'): ?>
                        <input type="hidden" name="<?= e($k) ?>" value="<?= e($v) ?>">
                    <?php endif; ?>
                <?php endforeach; ?>

                <button class="btn btn-principal" type="submit">Buscar</button>
            </form>

            <?php
            /*
             * Barra de filtros puestos.
             *
             * Existe porque los filtros de abajo van plegados: sin ella,
             * alguien podría estar viendo un catálogo recortado por una
             * habilidad que eligió hace tres clics y no tener ni idea. Cada
             * ficha se quita sola, y el enlace final los quita todos.
             */
            $puestos = [];

            if ($fBuscar !== '') {
                $puestos[] = ['🔍 «' . $fBuscar . '»', urlFiltro(['buscar' => ''])];
            }
            if ($fNivel !== '') {
                foreach ($niveles as $nv) {
                    if ($nv['slug'] === $fNivel) {
                        $puestos[] = ['👶 ' . $nv['name'], urlFiltro(['nivel' => ''])];
                    }
                }
            }
            if ($etiquetaActual) {
                $puestos[] = [$etiquetaActual['icon'] . ' ' . $etiquetaActual['name'],
                              urlFiltro(['etiqueta' => ''])];
            }
            if ($fAcceso === 'gratis') {
                $puestos[] = ['🎁 Puedo jugar gratis', urlFiltro(['acceso' => ''])];
            } elseif ($fAcceso === 'premium') {
                $puestos[] = ['🔒 Premium', urlFiltro(['acceso' => ''])];
            }
            ?>
            <?php if ($puestos): ?>
                <div class="filtros-puestos">
                    <span class="rotulo">Filtrando por</span>
                    <?php foreach ($puestos as [$texto, $quitar]): ?>
                        <a class="ficha-puesta" href="<?= e($quitar) ?>">
                            <?= e($texto) ?>
                            <span aria-hidden="true">×</span>
                            <span class="oculto-visual">Quitar este filtro</span>
                        </a>
                    <?php endforeach; ?>
                    <a class="limpiar-todo" href="<?= e(url('actividades/')) ?>">Limpiar todo</a>
                </div>
            <?php endif; ?>

            <div class="grupo-filtro">
                <span class="rotulo">Categoría</span>
                <div class="chips">
                    <a class="chip <?= $fCategoria === '' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['categoria' => ''])) ?>">🗺️ Todas</a>
                    <?php foreach ($categorias as $c): ?>
                        <a class="chip <?= $fCategoria === $c['slug'] ? 'activo' : '' ?>"
                           href="<?= e(urlFiltro(['categoria' => $c['slug']])) ?>">
                            <?= e($c['icon']) ?> <?= e($c['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php
            /*
             * Los bloques solo se ofrecen DENTRO de una categoría.
             *
             * Sin categoría elegida hay 36, y salían en seis filas
             * seguidas — el mismo muro que este panel viene a evitar.
             * Además, sueltos no dicen nada: «Mi Cuerpo» y «Seres Vivos»
             * uno al lado del otro no revelan a qué materia pertenece
             * cada uno. El bloque es un subfiltro de la categoría, y así
             * se comporta.
             *
             * En el directorio tampoco: allí cada materia ya muestra los
             * suyos dentro de su ficha.
             */
            ?>
            <?php if ($bloques && !$directorio && $fCategoria !== ''): ?>
                <div class="grupo-filtro">
                    <span class="rotulo">Bloque</span>
                    <div class="chips">
                        <a class="chip <?= $fBloque === '' ? 'activo' : '' ?>"
                           href="<?= e(urlFiltro(['bloque' => ''])) ?>">Todos</a>
                        <?php foreach ($bloques as $b): ?>
                            <a class="chip <?= $fBloque === $b['slug'] ? 'activo' : '' ?>"
                               href="<?= e(urlFiltro(['bloque' => $b['slug']])) ?>">
                                <?= e($b['icon']) ?> <?= e($b['name']) ?>
                                <small><?= (int) $b['total'] ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            /*
             * FILTROS DE SEGUNDO NIVEL, PLEGADOS
             *
             * Categoría y bloque son navegación: se ven siempre. Edad,
             * habilidad, acceso y orden son afinado, y desplegados suman
             * casi treinta fichas más. Entre las doce categorías y todo
             * eso, el panel llegó a mostrar más de cuarenta pastillas
             * iguales antes de la primera actividad — un muro que hay que
             * leer entero para saber qué hace cada cosa.
             *
             * Se usa <details> y no JavaScript: es plegable nativo, va con
             * teclado, lo lee un lector de pantalla y funciona aunque el
             * script no cargue, igual que el menú móvil del sitio.
             *
             * Se abre solo si hay algo puesto ahí dentro: nadie debe tener
             * que adivinar por qué ve un catálogo recortado.
             */
            /*
             * El contador cuenta solo lo que vive DENTRO del plegado. La
             * búsqueda también aparece en la barra de arriba, pero su
             * campo está fuera: incluirla haría que el número no cuadrara
             * con lo que el visitante encuentra al abrir.
             *
             * El orden abre el panel pero no suma: cambiar el orden no
             * recorta el catálogo, así que no es un filtro puesto.
             */
            $dentro = 0;
            foreach ([$fNivel, $fEtiqueta, $fAcceso] as $f) {
                if ($f !== '') {
                    $dentro++;
                }
            }

            $afinado = $dentro > 0 || ($fOrden !== 'categoria' && !$directorio);
            ?>
            <details class="mas-filtros" <?= $afinado ? 'open' : '' ?>>

                <summary>
                    <span class="mas-icono" aria-hidden="true">⚙️</span>
                    <span>Afinar por edad, habilidad o tipo</span>
                    <?php if ($dentro > 0): ?>
                        <span class="mas-cuenta"><?= $dentro ?></span>
                    <?php endif; ?>
                </summary>

                <div class="mas-cuerpo">

            <div class="grupo-filtro">
                <span class="rotulo">Edad o nivel</span>
                <div class="chips">
                    <a class="chip <?= $fNivel === '' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['nivel' => ''])) ?>">Todas las edades</a>
                    <?php foreach ($niveles as $n): ?>
                        <a class="chip <?= $fNivel === $n['slug'] ? 'activo' : '' ?>"
                           href="<?= e(urlFiltro(['nivel' => $n['slug']])) ?>">
                            <?= e($n['name']) ?>
                            <?php if ($n['min_age'] && $n['max_age']): ?>
                                <small><?= (int) $n['min_age'] ?>–<?= (int) $n['max_age'] ?></small>
                            <?php endif; ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php
            /*
             * Habilidad y tipo son transversales: cruzan las materias.
             * Alguien puede querer «un reto de lógica» sin importarle si
             * está en Sociales o en Matemática, y así lo encuentra sin
             * que haya que duplicar la actividad en dos categorías.
             */
            ?>
            <?php if ($habilidades): ?>
                <div class="grupo-filtro">
                    <span class="rotulo">Habilidad</span>
                    <div class="chips">
                        <a class="chip <?= $fEtiqueta === '' ? 'activo' : '' ?>"
                           href="<?= e(urlFiltro(['etiqueta' => ''])) ?>">Todas</a>
                        <?php foreach ($habilidades as $t): ?>
                            <a class="chip <?= $fEtiqueta === $t['slug'] ? 'activo' : '' ?>"
                               href="<?= e(urlFiltro(['etiqueta' => $t['slug']])) ?>">
                                <?= e($t['icon']) ?> <?= e($t['name']) ?>
                                <small><?= (int) $t['total'] ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <?php
            /*
             * APOYOS — la fila que hacía falta.
             *
             * Las demás filas preguntan de qué va la actividad. Esta
             * pregunta qué obstáculo le quita a quien lo necesita:
             * concentrarse, calmarse, organizarse, saber qué viene.
             *
             * Ninguna etiqueta lleva el nombre de un diagnóstico. Quien
             * busca por diagnóstico llega desde la guía para adultos
             * (`apoyos.php`), que sí los nombra y explica qué filtro le
             * corresponde a cada uno.
             */
            ?>
            <?php if ($apoyos): ?>
                <div class="grupo-filtro">
                    <span class="rotulo">
                        Apoyos
                        <a class="enlace-rotulo" href="<?= e(url('actividades/apoyos.php')) ?>">¿qué es esto?</a>
                    </span>
                    <div class="chips">
                        <?php foreach ($apoyos as $t): ?>
                            <a class="chip <?= $fEtiqueta === $t['slug'] ? 'activo' : '' ?>"
                               href="<?= e(urlFiltro(['etiqueta' => $t['slug']])) ?>">
                                <?= e($t['icon']) ?> <?= e($t['name']) ?>
                                <small><?= (int) $t['total'] ?></small>
                            </a>
                        <?php endforeach; ?>
                    </div>
                </div>
            <?php endif; ?>

            <div class="grupo-filtro">
                <span class="rotulo">Acceso y tipo</span>
                <div class="chips">
                    <a class="chip <?= $fAcceso === '' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['acceso' => ''])) ?>">Todo</a>
                    <a class="chip <?= $fAcceso === 'gratis' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['acceso' => 'gratis'])) ?>">🎁 Puedo jugar gratis</a>
                    <a class="chip <?= $fAcceso === 'premium' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['acceso' => 'premium'])) ?>">🔒 Premium</a>

                    <?php foreach ($tipos as $t): ?>
                        <a class="chip <?= $fEtiqueta === $t['slug'] ? 'activo' : '' ?>"
                           href="<?= e(urlFiltro(['etiqueta' => $t['slug']])) ?>">
                            <?= e($t['icon']) ?> <?= e($t['name']) ?>
                            <small><?= (int) $t['total'] ?></small>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php /* Ordenar no aplica al directorio: no hay lista que ordenar. */ ?>
            <?php if (!$directorio): ?>
            <div class="grupo-filtro">
                <span class="rotulo">Ordenar por</span>
                <div class="chips">
                    <a class="chip <?= $fOrden === 'categoria' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['orden' => 'categoria'])) ?>">Categoría</a>
                    <a class="chip <?= $fOrden === 'nuevas' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['orden' => 'nuevas'])) ?>">✨ Más nuevas</a>
                    <a class="chip <?= $fOrden === 'titulo' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['orden' => 'titulo'])) ?>">A–Z</a>
                    <a class="chip <?= $fOrden === 'nivel' ? 'activo' : '' ?>"
                       href="<?= e(urlFiltro(['orden' => 'nivel'])) ?>">Edad</a>
                </div>
            </div>
            <?php endif; ?>

                </div><!-- .mas-cuerpo -->
            </details>

        </div>

        <?php if ($directorio): ?>

        <!-- ── Directorio de materias ──────────────────────────────── -->
        <div class="resumen-resultados">
            <span><b><?= (int) $totalHallado ?></b> actividades en <b><?= count($materias) ?></b> materias</span>
            <span><a href="<?= e(urlFiltro(['vista' => 'lista'])) ?>">Ver todas en una lista →</a></span>
        </div>

        <div class="directorio">
            <?php foreach ($materias as $m): ?>
                <article class="linea" style="--acento:<?= e($m['color'] ?: '#29b6f6') ?>">

                    <a class="linea-cabeza" href="<?= e(urlFiltro(['categoria' => $m['slug']])) ?>">
                        <span class="linea-ico" aria-hidden="true"><?= e($m['icon']) ?></span>
                        <span class="linea-texto">
                            <span class="linea-nombre"><?= e($m['name']) ?></span>
                            <?php if (!empty($m['tagline'])): ?>
                                <span class="linea-bajada"><?= e($m['tagline']) ?></span>
                            <?php endif; ?>
                        </span>
                        <span class="linea-conteo"><?= (int) $m['total'] ?></span>
                    </a>

                    <?php if ($m['bloques']): ?>
                        <div class="submundos">
                            <?php foreach ($m['bloques'] as $b): ?>
                                <a class="submundo"
                                   href="<?= e(urlFiltro(['categoria' => $m['slug'], 'bloque' => $b['slug']])) ?>">
                                    <?= e($b['icon']) ?> <?= e($b['name']) ?>
                                    <small><?= (int) $b['total'] ?></small>
                                </a>
                            <?php endforeach; ?>
                            <a class="submundo todos" href="<?= e(urlFiltro(['categoria' => $m['slug']])) ?>">
                                Ver todo →
                            </a>
                        </div>
                    <?php endif; ?>

                </article>
            <?php endforeach; ?>
        </div>

        <?php else: ?>

        <!-- ── Resultados ──────────────────────────────────────────── -->
        <div class="resumen-resultados">
            <span>
                <b><?= (int) $totalHallado ?></b>
                <?= $totalHallado === 1 ? 'actividad encontrada' : 'actividades encontradas' ?>
                <?php if ($fBuscar !== ''): ?>
                    para “<b><?= e($fBuscar) ?></b>”
                <?php endif; ?>
            </span>
            <?php if ($totalPaginas > 1): ?>
                <span>Página <?= (int) $pagina ?> de <?= (int) $totalPaginas ?></span>
            <?php endif; ?>
        </div>

        <?php if ($actividades): ?>

            <?php if ($agrupar && count($grupos) > 1): ?>

                <?php foreach ($grupos as $g): ?>
                    <?php
                    $totalBloque = count($g['actividades']);

                    // Solo se recortan los bloques de verdad. «Otras
                    // actividades» reúne categorías distintas que no
                    // tienen bloques, así que no existe un único sitio a
                    // dónde mandar al visitante a ver el resto: recortarlo
                    // escondería tarjetas sin ofrecer cómo llegar a ellas.
                    $visibles = ($recortar && $g['bloque'])
                        ? array_slice($g['actividades'], 0, MUESTRA_POR_BLOQUE)
                        : $g['actividades'];

                    $ocultas = $totalBloque - count($visibles);
                    $verTodo = $g['bloque'] ? urlFiltro(['bloque' => $g['bloque']['slug']]) : '';
                    ?>
                    <section class="bloque-catalogo">

                        <?php if ($g['bloque']): ?>
                            <header class="bloque-cabeza">
                                <span class="bloque-icono" aria-hidden="true"><?= e($g['bloque']['icon']) ?></span>
                                <div>
                                    <h3><?= e($g['bloque']['name']) ?></h3>
                                    <p>
                                        <?php if ($g['bloque']['categoria']): ?>
                                            <?= e($g['bloque']['categoria']) ?> ·
                                        <?php endif; ?>
                                        <?= $totalBloque ?>
                                        <?= $totalBloque === 1 ? 'actividad' : 'actividades' ?>
                                    </p>
                                </div>
                                <a class="btn btn-secundario btn-chico" href="<?= e($verTodo) ?>">
                                    <?= $ocultas > 0 ? 'Ver las ' . $totalBloque : 'Ver solo este bloque' ?>
                                </a>
                            </header>
                        <?php else: ?>
                            <header class="bloque-cabeza sencilla">
                                <div>
                                    <h3>Otras actividades</h3>
                                    <p><?= $totalBloque ?>
                                       <?= $totalBloque === 1 ? 'actividad' : 'actividades' ?></p>
                                </div>
                            </header>
                        <?php endif; ?>

                        <div class="rejilla-actividades">
                            <?php foreach ($visibles as $act) {
                                require RUTA_INCLUDES . '/tarjeta-actividad.php';
                            } ?>
                        </div>

                        <?php if ($ocultas > 0): ?>
                            <p class="bloque-mas">
                                <a href="<?= e($verTodo) ?>">
                                    + <?= $ocultas ?>
                                    <?= $ocultas === 1 ? 'actividad más' : 'actividades más' ?>
                                    en este bloque →
                                </a>
                            </p>
                        <?php endif; ?>

                    </section>
                <?php endforeach; ?>

            <?php else: ?>

                <div class="rejilla-actividades">
                    <?php foreach ($actividades as $act) {
                        require RUTA_INCLUDES . '/tarjeta-actividad.php';
                    } ?>
                </div>

            <?php endif; ?>

            <?php if ($totalPaginas > 1): ?>
                <nav class="paginacion" aria-label="Paginación">
                    <?php if ($pagina > 1): ?>
                        <a href="<?= e(urlFiltro(['pagina' => $pagina - 1])) ?>" rel="prev">← Anterior</a>
                    <?php endif; ?>

                    <?php
                    // Ventana de páginas alrededor de la actual.
                    $desde = max(1, $pagina - 2);
                    $hasta = min($totalPaginas, $pagina + 2);
                    for ($i = $desde; $i <= $hasta; $i++):
                        ?>
                        <?php if ($i === $pagina): ?>
                            <span class="actual" aria-current="page"><?= $i ?></span>
                        <?php else: ?>
                            <a href="<?= e(urlFiltro(['pagina' => $i])) ?>"><?= $i ?></a>
                        <?php endif; ?>
                    <?php endfor; ?>

                    <?php if ($pagina < $totalPaginas): ?>
                        <a href="<?= e(urlFiltro(['pagina' => $pagina + 1])) ?>" rel="next">Siguiente →</a>
                    <?php endif; ?>
                </nav>
            <?php endif; ?>

        <?php else: ?>

            <div class="vacio">
                <span class="ico" aria-hidden="true">🔍</span>
                <h3>No encontramos actividades con esos filtros</h3>
                <p>Prueba con otras palabras o quita algún filtro.</p>
                <p style="margin-top:18px">
                    <a class="btn btn-principal" href="<?= e(url('actividades/')) ?>">Ver todo el catálogo</a>
                </p>
            </div>

        <?php endif; ?>

        <?php endif; /* fin de directorio / lista */ ?>

    </div>
</section>

<?php if (convieneInvitarADesbloquear()): ?>
<section class="seccion" style="padding-top:0">
    <div class="contenedor">
        <div class="invitacion">
            <span class="sello">✨ BIBLIOTECA COMPLETA</span>
            <h2>Desbloquea todas las estaciones de todas las actividades</h2>
            <p>
                Con el plan gratuito juegas la primera parte de cada actividad.
                La Biblioteca Completa abre el resto, y las nuevas actividades que publiquemos
                durante tu suscripción quedan incluidas.
            </p>
            <a class="btn btn-oro" href="<?= e(url('planes/')) ?>">Ver Biblioteca Completa</a>
        </div>
    </div>
</section>
<?php endif; ?>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
