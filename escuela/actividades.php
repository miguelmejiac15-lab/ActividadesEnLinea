<?php
/**
 * escuela/actividades.php — Asignar actividades al curso
 *
 * Tres formas, de la más gruesa a la más fina, que es el orden en que un
 * docente monta un curso:
 *
 *   1. **Una materia entera** de un nivel: «todo Matemática de tercero».
 *   2. **Un paquete** (los bloques del catálogo): «Operaciones», «Reino
 *      de las Letras». Es la unidad con la que está pensado el contenido.
 *   3. **Sueltas**, buscando por nombre.
 *
 * Antes solo existía la tercera, y montar un curso eran treinta búsquedas
 * y treinta clics. Con 384 actividades eso no es una interfaz, es una
 * penitencia.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');
    $fecha  = post('fecha') ?: null;

    /** Cuenta el resultado de una asignación en tanda. */
    $contar = static function (array $r): void {
        if ($r['nuevas'] > 0) {
            mensaje('ok', $r['nuevas'] . ' actividad(es) añadida(s) al curso.'
                . ($r['repetidas'] > 0 ? ' Otras ' . $r['repetidas'] . ' ya estaban.' : ''));
        } elseif ($r['repetidas'] > 0) {
            // Sin este mensaje, quien pulsa «asignar» y no ve cambios cree
            // que algo falló.
            mensaje('info', 'Esas ' . $r['repetidas'] . ' ya estaban asignadas. Nada que hacer.');
        } else {
            mensaje('info', 'No había nada que añadir.');
        }
    };

    /**
     * Quita una tanda y lo cuenta.
     *
     * Renumera la ruta después: al quitar del medio quedan huecos en
     * `sort_order`, y con huecos el «subir/bajar» de la tabla intercambia
     * con una posición que ya no existe y parece que no hace nada.
     */
    $quitarTanda = static function (array $ids) use ($cursoId): void {
        $n = quitarVarias($cursoId, $ids);

        if ($n > 0) {
            normalizarOrdenDeRuta($cursoId);
            olvidarRuta();
        }

        mensaje($n > 0 ? 'ok' : 'info',
            $n > 0 ? "$n actividad(es) retirada(s) del curso. El progreso ya hecho se conserva."
                   : 'De ese paquete no había nada asignado. Nada que quitar.');
    };

    // ── Un bloque entero ─────────────────────────────────────────────
    if ($accion === 'bloque') {
        $contar(asignarVarias($cursoId, idsDeBloque((int) post('bloque')), $fecha));
    }

    // ── Una materia, opcionalmente de un nivel ───────────────────────
    if ($accion === 'materia') {
        $contar(asignarVarias(
            $cursoId,
            idsDeCategoriaNivel((string) post('categoria'), (string) post('nivel')),
            $fecha
        ));
    }

    // ── Las marcadas en el buscador ──────────────────────────────────
    if ($accion === 'marcadas') {
        $contar(asignarVarias($cursoId, (array) ($_POST['actividades'] ?? []), $fecha));
    }

    // ── Una sola ─────────────────────────────────────────────────────
    if ($accion === 'asignar') {
        $contar(asignarVarias($cursoId, [(int) post('actividad')], $fecha));
    }

    // ── Quitar ───────────────────────────────────────────────────────
    //
    // Cada forma de añadir tiene su forma de deshacer, y por el mismo
    // camino. Se podía meter un paquete de 23 actividades de un clic pero
    // había que sacarlas de 23 en 23 marcando casillas: una equivocación
    // de un segundo costaba cinco minutos de deshacer.
    if ($accion === 'quitar') {
        quitarAsignacion($cursoId, (int) post('actividad'));
        normalizarOrdenDeRuta($cursoId);
        olvidarRuta();
        mensaje('ok', 'Actividad retirada. El progreso ya hecho se conserva.');
    }

    if ($accion === 'quitar_bloque') {
        $quitarTanda(idsDeBloque((int) post('bloque')));
    }

    if ($accion === 'quitar_materia') {
        $quitarTanda(idsDeCategoriaNivel((string) post('categoria'), (string) post('nivel')));
    }

    if ($accion === 'quitar_marcadas') {
        $n = quitarVarias($cursoId, (array) ($_POST['asignadas'] ?? []));

        if ($n > 0) {
            normalizarOrdenDeRuta($cursoId);
            olvidarRuta();
        }

        mensaje($n > 0 ? 'ok' : 'info',
            $n > 0 ? "$n actividad(es) retirada(s). El progreso se conserva."
                   : 'No marcaste ninguna.');
    }

    // ── El orden de la ruta ──────────────────────────────────────────
    //
    // En modo secuencial el orden ES la clase: decide qué hace el niño
    // primero y qué no puede tocar todavía.
    if ($accion === 'subir' || $accion === 'bajar') {
        moverEnRuta($cursoId, (int) post('actividad'), $accion === 'subir' ? -1 : 1);
        olvidarRuta();
    }

    // ── Cómo avanza el estudiante ────────────────────────────────────
    if ($accion === 'modo') {
        guardarModoDeRuta($cursoId, (string) post('ruta_modo'));

        mensaje('ok', post('ruta_modo') === 'libre'
            ? 'Ahora tus estudiantes eligen el orden.'
            : 'Ahora avanzan en orden: cada actividad se abre al terminar la anterior.');
    }

    // Se conservan los filtros al volver, para poder asignar varias
    // seguidas sin rehacer la búsqueda cada vez.
    redirigir('escuela/actividades.php?curso=' . $cursoId
        . '&categoria=' . urlencode(get('categoria'))
        . '&nivel=' . urlencode(get('nivel'))
        . '&buscar=' . urlencode(get('buscar')));
}

$asignadas   = actividadesDelCurso($cursoId);
$yaAsignadas = array_map('intval', array_column($asignadas, 'id'));
$modoRuta    = modoDeRuta($curso);

// ── Buscador ─────────────────────────────────────────────────────────
$fCategoria = get('categoria');
$fNivel     = get('nivel');
$fBuscar    = trim(get('buscar'));

$sql = 'SELECT a.id, a.slug, a.title, a.icon, a.duration_minutes,
               c.name AS categoria, c.icon AS categoria_icon,
               l.name AS nivel,
               (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones
          FROM activities a
     LEFT JOIN categories c ON c.id = a.category_id
     LEFT JOIN levels     l ON l.id = a.level_id
         WHERE a.status = "published"';
$params = [];

if ($fCategoria !== '') { $sql .= ' AND c.slug = ?'; $params[] = $fCategoria; }
if ($fNivel !== '')     { $sql .= ' AND l.slug = ?'; $params[] = $fNivel; }

// `escaparLike()` neutraliza los comodines % y _ que el usuario escriba:
// sin eso, buscar «_» devolvería el catálogo entero.
if ($fBuscar !== '') {
    $sql .= ' AND a.title LIKE ?';
    $params[] = '%' . escaparLike($fBuscar) . '%';
}

$sql .= ' ORDER BY c.sort_order, l.sort_order, a.title LIMIT 80';

$hayFiltro  = $fCategoria !== '' || $fNivel !== '' || $fBuscar !== '';
$resultados = $hayFiltro ? traerTodo($sql, $params) : [];

$categorias = categoriasConConteo();
$niveles    = nivelesActivos();
$directorio = directorioCategorias();

$titulo      = 'Actividades · ' . $curso['name'];
$escuelaZona = 'actividades';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1>Actividades del curso</h1>
        <p class="bajada"><?= e($curso['name']) ?> · <?= count($asignadas) ?> asignadas</p>
    </div>
    <?php if ($asignadas): ?>
        <a class="btn btn-secundario btn-chico"
           href="<?= e(url('escuela/progreso.php?curso=' . $cursoId)) ?>">
            Ver el progreso →
        </a>
    <?php endif; ?>
</header>


<?php
/*
 * ─────────────────────────────────────────────────────────────────────
 *  LAS DOS FORMAS, DICHAS ANTES DE EMPEZAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * Asignar de una en una se podía desde siempre —el buscador de más
 * abajo—, pero la página empezaba con los paquetes y quien llegaba aquí
 * se llevaba la impresión de que era lo único posible: «solo me deja
 * añadir las vocales enteras». La función estaba; lo que faltaba era
 * decirlo antes de que la primera pantalla contestara otra cosa.
 */
?>
<div class="aviso info" style="margin-bottom:18px">
    <b>También puedes asignar una sola actividad.</b>
    Si estás armando tu planeación —hoy la A, mañana la M, el jueves contar hasta 10—
    baja a <a href="#una-a-una">buscar y marcar</a>: eliges exactamente cuáles, de
    cualquier materia, y luego ordenas. Los paquetes de aquí abajo son el atajo para
    cuando quieres la materia entera.
</div>

<!-- ── 1. Paquetes ──────────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Añadir un paquete completo</h2>
    <p class="nota-panel">
        Los paquetes son como está organizado el catálogo. Añadir uno entero suele ser
        lo que quieres al montar el curso; luego se quita lo que sobre.
        Lo que ya esté asignado no se duplica.
    </p>

    <?php foreach ($directorio as $cat): ?>
        <details class="materia" <?= $fCategoria === $cat['slug'] ? 'open' : '' ?>>
            <summary>
                <span class="materia-icono" aria-hidden="true"><?= e($cat['icon']) ?></span>
                <span class="materia-nombre"><?= e($cat['name']) ?></span>
                <span class="materia-total"><?= (int) $cat['total'] ?> actividades</span>
            </summary>

            <div class="materia-cuerpo">

                <?php
                /* La materia entera, filtrando por nivel si se quiere.
                 *
                 * Los dos botones comparten formulario a propósito: así
                 * el «quitar» respeta el nivel elegido en el mismo
                 * selector. Cada uno manda su `accion` como valor del
                 * botón, que es lo que envía el que se pulsa. */
                $deLaMateria = idsDeCategoriaNivel((string) $cat['slug']);
                $enElCurso   = count(array_intersect($deLaMateria, $yaAsignadas));
                ?>
                <form method="post" class="form-linea fila-materia">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="categoria" value="<?= e($cat['slug']) ?>">

                    <label>
                        Nivel
                        <select name="nivel">
                            <option value="">Todos los niveles</option>
                            <?php foreach ($niveles as $n): ?>
                                <option value="<?= e($n['slug']) ?>"><?= e($n['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </label>

                    <button class="btn btn-principal btn-chico"
                            type="submit" name="accion" value="materia">
                        Añadir toda la materia
                    </button>

                    <?php /* Solo si hay algo suyo dentro: un botón que no
                             puede hacer nada solo estorba. */ ?>
                    <?php if ($enElCurso > 0): ?>
                        <button class="btn btn-secundario btn-chico"
                                type="submit" name="accion" value="quitar_materia"
                                onclick="return confirm('¿Quitar del curso las actividades de <?= e($cat['name']) ?> del nivel elegido?\n\nEl progreso ya hecho se conserva.')">
                            Quitar la materia
                        </button>
                        <span class="tenue"><?= $enElCurso ?> en el curso</span>
                    <?php endif; ?>
                </form>

                <div class="rejilla-paquetes">
                    <?php foreach ($cat['bloques'] as $b): ?>
                        <?php
                        // Cuántas de este bloque están ya en el curso: sin
                        // esto no se sabe qué falta por añadir.
                        $delBloque = idsDeBloque((int) $b['id']);
                        $puestas   = count(array_intersect($delBloque, $yaAsignadas));
                        $todas     = $puestas === count($delBloque) && $delBloque !== [];
                        ?>
                        <div class="paquete <?= $todas ? 'completo' : '' ?>">
                            <div class="paquete-cabeza">
                                <span aria-hidden="true"><?= e($b['icon'] ?? '📦') ?></span>
                                <b><?= e($b['name']) ?></b>
                            </div>
                            <p class="paquete-pie"><?= e($b['description'] ?? '') ?></p>

                            <div class="paquete-acciones">
                                <span class="tenue">
                                    <?= $puestas ?>/<?= count($delBloque) ?> en el curso
                                </span>

                                <?php if ($todas): ?>
                                    <span class="pastilla lista">Completo ✓</span>
                                <?php endif; ?>

                                <?php if (!$todas): ?>
                                    <form method="post">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="bloque">
                                        <input type="hidden" name="bloque" value="<?= (int) $b['id'] ?>">
                                        <button class="btn btn-principal btn-chico" type="submit">
                                            Añadir <?= count($delBloque) - $puestas ?>
                                        </button>
                                    </form>
                                <?php endif; ?>

                                <?php
                                /*
                                 * La salida, al lado de la entrada.
                                 *
                                 * Un paquete entero se añade de un clic y
                                 * antes había que sacarlo marcando 23
                                 * casillas en la tabla de abajo. Poner el
                                 * «quitar» aquí es lo que hace que una
                                 * equivocación cueste lo mismo que el
                                 * acierto.
                                 */
                                ?>
                                <?php if ($puestas > 0): ?>
                                    <form method="post">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="quitar_bloque">
                                        <input type="hidden" name="bloque" value="<?= (int) $b['id'] ?>">
                                        <button class="btn btn-secundario btn-chico" type="submit"
                                                onclick="return confirm('¿Quitar del curso las <?= $puestas ?> actividades de «<?= e($b['name']) ?>»?\n\nEl progreso ya hecho se conserva.')">
                                            Quitar <?= $puestas ?>
                                        </button>
                                    </form>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        </details>
    <?php endforeach; ?>
</section>


<!-- ── 2. Buscar sueltas ────────────────────────────────────────────── -->

<section class="bloque-panel" id="una-a-una">
    <h2>Buscar y marcar · una a una</h2>
    <p class="nota-panel">
        Busca por materia, por nivel o por título, marca las que quieras y añádelas.
        Puedes mezclar materias: hoy la A de Letras, mañana «contar hasta 10» de
        Matemática. El orden en que se juegan se decide más abajo.
    </p>

    <form method="get" class="form-linea">
        <input type="hidden" name="curso" value="<?= $cursoId ?>">

        <label>
            Materia
            <select name="categoria">
                <option value="">Todas</option>
                <?php foreach ($categorias as $c): ?>
                    <option value="<?= e($c['slug']) ?>" <?= $fCategoria === $c['slug'] ? 'selected' : '' ?>>
                        <?= e($c['icon'] . ' ' . $c['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Nivel
            <select name="nivel">
                <option value="">Todos</option>
                <?php foreach ($niveles as $n): ?>
                    <option value="<?= e($n['slug']) ?>" <?= $fNivel === $n['slug'] ? 'selected' : '' ?>>
                        <?= e($n['name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </label>

        <label>
            Buscar
            <input type="search" name="buscar" value="<?= e($fBuscar) ?>" placeholder="Título…">
        </label>

        <button class="btn btn-secundario" type="submit">Buscar</button>
    </form>

    <?php if ($resultados): ?>
        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="marcadas">

            <table class="tabla-panel">
                <thead>
                    <tr>
                        <th style="width:34px"></th>
                        <th>Actividad</th>
                        <th>Materia</th>
                        <th>Nivel</th>
                        <th class="num">Estaciones</th>
                    </tr>
                </thead>
                <tbody>
                <?php foreach ($resultados as $a): ?>
                    <?php $ya = in_array((int) $a['id'], $yaAsignadas, true); ?>
                    <tr class="<?= $ya ? 'fila-tenue' : '' ?>">
                        <td>
                            <?php if (!$ya): ?>
                                <input type="checkbox" name="actividades[]" value="<?= (int) $a['id'] ?>">
                            <?php else: ?>
                                <span title="Ya asignada">✓</span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <?= e($a['icon'] ?? '') ?>
                            <a href="<?= e(url('actividades/ver.php?a=' . urlencode($a['slug']))) ?>"
                               target="_blank" rel="noopener">
                                <?= e($a['title']) ?>
                            </a>
                        </td>
                        <td class="tenue"><?= e(($a['categoria_icon'] ?? '') . ' ' . ($a['categoria'] ?? '')) ?></td>
                        <td class="tenue"><?= e($a['nivel'] ?? '') ?></td>
                        <td class="num"><?= (int) $a['estaciones'] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <div class="pie-bloque">
                <button class="btn btn-principal" type="submit">Añadir las marcadas</button>
                <button class="btn btn-secundario btn-chico" type="button"
                        onclick="document.querySelectorAll('[name=\'actividades[]\']').forEach(c=>c.checked=true)">
                    Marcar todas
                </button>
                <label class="fecha-opcional">
                    Para cuándo (opcional)
                    <input type="date" name="fecha">
                </label>
            </div>
        </form>

    <?php elseif ($hayFiltro): ?>
        <p class="nota-panel">No hay actividades con esos filtros.</p>
    <?php else: ?>
        <p class="nota-panel">
            Elige una materia o escribe algo para buscar. Para montar el curso deprisa,
            usa los paquetes de arriba.
        </p>
    <?php endif; ?>
</section>


<!-- ── 3. La ruta: qué hacen y en qué orden ─────────────────────────── -->

<section class="bloque-panel">
    <h2>La ruta del curso</h2>

    <?php if (!$asignadas): ?>
        <p class="nota-panel">
            Todavía no has asignado ninguna actividad. Empieza por un paquete de arriba.
        </p>
    <?php else: ?>

        <?php
        /*
         * El modo va ARRIBA de la lista, no al final: cambia por completo
         * lo que significa el orden de abajo. En secuencial, la fila 1 es
         * lo único que el niño puede tocar el primer día.
         */
        ?>
        <form method="post" class="form-linea" style="margin-bottom:18px">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="modo">

            <label>
                Tus estudiantes
                <select name="ruta_modo" onchange="this.form.submit()">
                    <option value="secuencial" <?= $modoRuta === 'secuencial' ? 'selected' : '' ?>>
                        Avanzan en orden (una se abre al terminar la anterior)
                    </option>
                    <option value="libre" <?= $modoRuta === 'libre' ? 'selected' : '' ?>>
                        Eligen cuál hacer
                    </option>
                </select>
            </label>

            <noscript><button class="btn btn-secundario btn-chico" type="submit">Guardar</button></noscript>
        </form>

        <p class="nota-panel">
            <?php if ($modoRuta === 'secuencial'): ?>
                Cada niño ve <strong>una sola actividad encendida</strong>: la primera que le
                falte. Las de abajo aparecen con candado hasta que llegue.
                El orden de esta tabla es el orden en que las hará.
            <?php else: ?>
                Cada niño ve <strong>las <?= count($asignadas) ?> a la vez</strong> y elige.
                El orden de la tabla es solo el orden en que las ve.
            <?php endif; ?>
            Lo que no esté aquí no lo puede abrir.
        </p>

        <?php
        /*
         * El formulario de quitar vive FUERA de la tabla y las casillas
         * lo señalan con `form="quitar-marcadas"`.
         *
         * Es lo que permite que los botones de orden tengan su propio
         * formulario dentro de cada celda. Envolver la tabla y cerrar el
         * formulario a mitad para intercalar otro produce HTML que el
         * navegador reordena por su cuenta —un `<form>` no puede cruzar
         * filas— y el resultado depende del navegador.
         */
        ?>
        <form method="post" id="quitar-marcadas">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="quitar_marcadas">
        </form>

        <table class="tabla-panel">
            <thead>
                <tr>
                    <th style="width:34px"></th>
                    <th style="width:44px" class="num">#</th>
                    <th>Actividad</th>
                    <th>Materia</th>
                    <th class="num">La terminaron</th>
                    <th>Para cuándo</th>
                    <th style="width:96px">Orden</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($asignadas as $i => $a): ?>
                <tr>
                    <td>
                        <input type="checkbox" form="quitar-marcadas"
                               name="asignadas[]" value="<?= (int) $a['id'] ?>">
                    </td>
                    <td class="num"><b><?= $i + 1 ?></b></td>
                    <td>
                        <?= e($a['icon'] ?? '') ?>
                        <a href="<?= e(url('actividades/ver.php?a=' . urlencode($a['slug']))) ?>"
                           target="_blank" rel="noopener">
                            <?= e($a['title']) ?>
                        </a>
                    </td>
                    <td class="tenue"><?= e(($a['categoria_icon'] ?? '') . ' ' . ($a['categoria'] ?? '')) ?></td>
                    <td class="num"><?= (int) $a['empezaron'] ?></td>
                    <td class="<?= $a['due_date'] ? '' : 'tenue' ?>">
                        <?= $a['due_date'] ? e(fechaLarga($a['due_date'])) : 'Sin fecha' ?>
                    </td>
                    <td class="acciones-fila">
                        <?php if ($i > 0): ?>
                            <form method="post" style="display:inline">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="subir">
                                <input type="hidden" name="actividad" value="<?= (int) $a['id'] ?>">
                                <button class="btn btn-secundario btn-chico" type="submit"
                                        title="Subir">↑</button>
                            </form>
                        <?php endif; ?>

                        <?php if ($i < count($asignadas) - 1): ?>
                            <form method="post" style="display:inline">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="bajar">
                                <input type="hidden" name="actividad" value="<?= (int) $a['id'] ?>">
                                <button class="btn btn-secundario btn-chico" type="submit"
                                        title="Bajar">↓</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <div class="pie-bloque">
            <button class="btn btn-secundario" type="submit" form="quitar-marcadas"
                    onclick="return confirm('¿Quitar las actividades marcadas?\n\nEl progreso ya hecho se conserva.')">
                Quitar las marcadas
            </button>
        </div>
    <?php endif; ?>
</section>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
