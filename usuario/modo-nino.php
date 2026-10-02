<?php
/**
 * usuario/modo-nino.php — Preparar la cuenta para prestársela a un niño.
 *
 * Una sola pantalla que hace tres cosas: elegir qué se ve, poner el PIN y
 * encender o apagar el modo.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ ESTA PÁGINA NO SE BLOQUEA EN MODO NIÑO
 * ---------------------------------------------------------------------
 *
 * Es la única puerta de salida. Si se bloqueara como las de pago, la
 * cuenta quedaría encerrada para siempre y solo se podría rescatar desde
 * la base de datos.
 *
 * Con el modo encendido enseña únicamente el formulario del PIN: nada de
 * la lista ni de los botones de quitar, porque quien está delante puede
 * ser el niño.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirSesion();

$usuario   = usuarioActual();
$usuarioId = (int) $usuario['id'];

if (!modoNinoInstalado()) {
    mensaje('error', 'El modo niño todavía no está instalado en esta plataforma.');
    redirigir('usuario/');
}

/*
 * Un estudiante de un colegio no configura esto.
 *
 * Su cuenta ya la gobierna la ruta que le puso su docente, y dejarle
 * encender un modo con PIN propio le daría una forma de esconderse de
 * ella. Es la misma distinción por rol que hace `ruta.php`.
 */
if (tieneRol('student')) {
    mensaje('info', 'Las actividades de tu cuenta las prepara tu profe.');
    redirigir('usuario/');
}

$enModo = enModoNino();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion', '');

    /*
     * Con el modo encendido, lo ÚNICO que se acepta es apagarlo.
     *
     * Sin esta guarda, un niño curioso podría mandar «vaciar» desde las
     * herramientas del navegador y quedarse con el catálogo entero: el
     * modo con la selección vacía no cierra nada.
     */
    if ($enModo && $accion !== 'apagar') {
        mensaje('error', 'Primero sal del modo niño con tu PIN.');
        redirigir('usuario/modo-nino.php');
    }

    if ($accion === 'apagar') {
        $r = apagarModoNino($usuarioId, (string) post('pin', ''));

        if ($r['ok']) {
            mensaje('ok', 'Saliste del modo niño. Ya tienes todo el catálogo otra vez.');
        } else {
            mensaje('error', $r['error']);
        }

        redirigir('usuario/modo-nino.php');
    }

    if ($accion === 'encender') {
        $r = encenderModoNino($usuarioId);

        if ($r['ok']) {
            mensaje('ok', 'Listo. Tu hijo solo verá lo que elegiste.');
        } else {
            mensaje('error', $r['error']);
        }

        redirigir('usuario/modo-nino.php');
    }

    if ($accion === 'pin') {
        $r = guardarPinDeModoNino($usuarioId, (string) post('pin', ''));
        mensaje($r['ok'] ? 'ok' : 'error',
            $r['ok'] ? 'PIN guardado.' : $r['error']);
        redirigir('usuario/modo-nino.php');
    }

    /*
     * Después de asignar se vuelve al mismo paso del recorrido (la misma
     * materia y el mismo bloque), para que el adulto siga eligiendo sin
     * tener que volver a navegar desde el principio.
     */
    $volver = 'usuario/modo-nino.php?' . http_build_query(array_filter([
        'materia' => post('materia', ''),
        'bloque'  => post('bloque', ''),
        'nivel'   => post('nivel', ''),
        'buscar'  => post('buscar', ''),
    ], 'strlen'));
    $ancla = post('bloque', '') !== '' ? '#paso-actividades'
           : (post('materia', '') !== '' ? '#paso-bloques' : '');

    if ($accion === 'agregar') {
        $puestas = 0;

        foreach ((array) ($_POST['actividades'] ?? []) as $id) {
            if (agregarAlModoNino($usuarioId, (int) $id)) {
                $puestas++;
            }
        }

        mensaje($puestas > 0 ? 'ok' : 'info',
            $puestas > 0
                ? ($puestas === 1 ? 'Se asignó 1 actividad.' : "Se asignaron $puestas actividades.")
                : 'No se asignó nada: marca al menos una.');

        redirigir($volver . $ancla);
    }

    if ($accion === 'agregar_grupo') {
        $puestas = agregarGrupoAlModoNino(
            $usuarioId, (string) post('tipo', ''), (string) post('slug', ''));

        mensaje($puestas > 0 ? 'ok' : 'info',
            $puestas > 0
                ? ($puestas === 1 ? 'Se asignó 1 actividad.' : "Se asignaron $puestas actividades.")
                : 'Ahí no había nada nuevo que asignar.');

        redirigir($volver . $ancla);
    }

    if ($accion === 'quitar') {
        quitarDelModoNino($usuarioId, (int) post('actividad', 0));
        mensaje('ok', 'Quitada de la lista.');
        redirigir($volver . '#lista');
    }

    if ($accion === 'quitar_grupo') {
        $quitadas = quitarGrupoDelModoNino(
            $usuarioId, (string) post('tipo', ''), (string) post('slug', ''));
        mensaje('ok', $quitadas === 1 ? 'Se quitó 1 actividad.' : "Se quitaron $quitadas actividades.");
        redirigir($volver . '#lista');
    }

    if ($accion === 'vaciar') {
        vaciarModoNino($usuarioId);
        mensaje('ok', 'Lista vacía. Ahora elige una materia y asigna lo que quieras.');
        redirigir('usuario/modo-nino.php#paso-materia');
    }

    redirigir('usuario/modo-nino.php');
}

$seleccion = seleccionDeModoNino($usuarioId);
$tienePin  = tienePinDeModoNino($usuarioId);

/*
 * El buscador de esta página NO puede usar `buscarActividades()`.
 *
 * Esa función ya aplica el recorte del modo niño, así que con el modo
 * encendido devolvería solo lo que ya está elegido y sería imposible
 * añadir nada nuevo. Aquí hace falta ver el catálogo completo, que es
 * justo lo que el adulto está repartiendo.
 */
$categorias = traerTodo(
    'SELECT slug, name, icon FROM categories WHERE is_active = 1 ORDER BY sort_order');
$niveles = traerTodo(
    'SELECT slug, name FROM levels WHERE is_active = 1 ORDER BY sort_order');

$fMateria = trim((string) get('materia', ''));
$fBloque  = trim((string) get('bloque', ''));
$fNivel   = trim((string) get('nivel', ''));
$fBuscar  = trim((string) get('buscar', ''));

/*
 * El recorrido guiado: materia → bloques de esa materia → actividades
 * del bloque. Cada bloque dice cuántas tiene y cuántas ya están en la
 * lista, para decidir de un vistazo entre «todo el bloque» o «una por una».
 */
$materia = null;
foreach ($categorias as $c) {
    if ($c['slug'] === $fMateria) {
        $materia = $c;
    }
}

$bloques = [];
$bloque  = null;
$delBloque = [];

if (!$enModo && $materia) {
    $bloques = traerTodo(
        'SELECT b.slug, b.name, b.icon, b.description,
                COUNT(a.id) AS total,
                SUM(m.activity_id IS NOT NULL) AS elegidas
           FROM collections b
           JOIN categories c ON c.id = b.category_id
           JOIN activities a ON a.collection_id = b.id AND a.status = "published"
      LEFT JOIN child_mode_activities m ON m.activity_id = a.id AND m.user_id = ?
          WHERE c.slug = ? AND b.is_active = 1
       GROUP BY b.id
       ORDER BY b.sort_order, b.name',
        [$usuarioId, $materia['slug']]
    );

    foreach ($bloques as $b) {
        if ($b['slug'] === $fBloque) {
            $bloque = $b;
        }
    }
}

if ($bloque) {
    $delBloque = traerTodo(
        'SELECT a.id, a.title, a.icon, l.name AS nivel,
                (m.activity_id IS NOT NULL) AS elegida,
                (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones
           FROM activities a
           JOIN collections b ON b.id = a.collection_id
      LEFT JOIN levels l ON l.id = a.level_id
      LEFT JOIN child_mode_activities m ON m.activity_id = a.id AND m.user_id = ?
          WHERE b.slug = ? AND a.status = "published"
       ORDER BY l.sort_order, a.title',
        [$usuarioId, $bloque['slug']]
    );
}

/* La lista actual, agrupada por materia y bloque para poder quitar en grupo. */
$agrupada = [];
foreach ($seleccion as $a) {
    $cs = (string) $a['categoria_slug'];
    $bs = (string) $a['bloque_slug'];
    $agrupada[$cs]['nombre'] = (string) $a['categoria'];
    $agrupada[$cs]['icono']  = (string) $a['categoria_icono'];
    $agrupada[$cs]['bloques'][$bs]['nombre'] = (string) $a['bloque'];
    $agrupada[$cs]['bloques'][$bs]['icono']  = (string) $a['bloque_icono'];
    $agrupada[$cs]['bloques'][$bs]['items'][] = $a;
}

/** Los campos ocultos que devuelven al mismo paso tras una acción. */
$campoVolver = static function () use ($fMateria, $fBloque, $fNivel, $fBuscar): string {
    $html = '';
    foreach (['materia' => $fMateria, 'bloque' => $fBloque, 'nivel' => $fNivel, 'buscar' => $fBuscar] as $k => $v) {
        if ($v !== '') {
            $html .= '<input type="hidden" name="' . $k . '" value="' . e($v) . '">';
        }
    }
    return $html;
};

$candidatas = [];

if (!$enModo && ($fNivel !== '' || $fBuscar !== '')) {
    $where  = ['a.status = "published"'];
    $params = [];

    if ($fNivel !== '') { $where[] = 'l.slug = ?'; $params[] = $fNivel; }

    if ($fBuscar !== '') {
        $where[] = '(a.title LIKE ? OR a.description LIKE ?)';
        $t = '%' . escaparLike($fBuscar) . '%';
        $params[] = $t;
        $params[] = $t;
    }

    // Fuera las que ya están elegidas: reofrecerlas es ruido.
    $where[] = 'NOT EXISTS (SELECT 1 FROM child_mode_activities m
                             WHERE m.user_id = ? AND m.activity_id = a.id)';
    $params[] = $usuarioId;

    $candidatas = traerTodo(
        'SELECT a.id, a.slug, a.title, a.icon,
                c.name AS categoria, l.name AS nivel,
                (SELECT COUNT(*) FROM activity_stations s WHERE s.activity_id = a.id) AS estaciones
           FROM activities a
      LEFT JOIN categories c ON c.id = a.category_id
      LEFT JOIN levels     l ON l.id = a.level_id
          WHERE ' . implode(' AND ', $where) . '
       ORDER BY c.sort_order, a.title
          LIMIT 60',
        $params
    );
}

$titulo        = 'Modo niño';
$seccionActiva = 'cuenta';

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:820px">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('usuario/')) ?>">Mi espacio</a>
            <span class="sep">›</span>
            <span>Modo niño</span>
        </nav>

        <h1 style="color:var(--oscuro);font-size:1.7rem;margin-bottom:6px">Modo niño</h1>
        <p style="color:var(--texto-tenue);margin-bottom:24px">
            Elige qué actividades quieres dejarle a tu hijo. Mientras el modo esté
            encendido, en esta cuenta solo se ven esas.
        </p>

        <?php if ($enModo): ?>

            <!-- ============================================================
                 ENCENDIDO · solo la salida
                 ============================================================ -->

            <div class="tarjeta" style="padding:26px;text-align:center">
                <div style="font-size:3rem;line-height:1;margin-bottom:12px">🧒</div>

                <h2 style="color:var(--oscuro);font-size:1.3rem;margin-bottom:8px">
                    El modo niño está encendido
                </h2>

                <p style="color:var(--texto-tenue);margin-bottom:6px">
                    En esta cuenta se ven
                    <b style="color:var(--oscuro)"><?= count($seleccion) ?></b>
                    <?= count($seleccion) === 1 ? 'actividad' : 'actividades' ?>.
                </p>

                <p style="color:var(--texto-tenue);margin-bottom:22px;font-size:.92rem">
                    Para volver a tener todo el catálogo, escribe tu PIN.
                </p>

                <form method="post" style="max-width:260px;margin:0 auto">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="apagar">

                    <div class="campo-simple">
                        <label for="pin-salir">PIN de cuatro números</label>
                        <input id="pin-salir" name="pin" type="password"
                               inputmode="numeric" pattern="[0-9]*" maxlength="4"
                               autocomplete="off" required
                               style="text-align:center;letter-spacing:.5em;font-size:1.3rem">
                    </div>

                    <button class="btn btn-principal" style="width:100%;margin-top:12px">
                        Salir del modo niño
                    </button>
                </form>
            </div>

            <div class="aviso info" style="margin-top:18px">
                Mientras el modo está encendido no se puede pagar, ver la facturación ni
                cambiar la lista. Es a propósito: así la cuenta se puede prestar sin
                preocuparse.
            </div>

        <?php else: ?>

            <!-- ============================================================
                 APAGADO · preparar
                 ============================================================ -->

            <style>
                .mn-pasos{display:flex;gap:6px;flex-wrap:wrap;margin-bottom:18px;font-size:.85rem}
                .mn-pasos span{padding:5px 11px;border-radius:999px;background:var(--fondo-suave);color:var(--texto-tenue)}
                .mn-pasos span.hecho{background:var(--verde);color:#fff}
                .mn-pasos span.ahora{background:var(--oscuro);color:#fff}
                .mn-paso{font-size:.78rem;font-weight:700;letter-spacing:.06em;text-transform:uppercase;color:var(--texto-tenue);margin-bottom:4px}
                .mn-materias{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:10px}
                .mn-materia{display:flex;flex-direction:column;align-items:center;gap:4px;padding:14px 8px;border:2px solid var(--fondo-suave);border-radius:var(--radio-chico);text-decoration:none;color:var(--oscuro);text-align:center;font-weight:600;font-size:.95rem;background:#fff}
                .mn-materia:hover{border-color:var(--oscuro)}
                .mn-materia.activa{border-color:var(--oscuro);background:var(--fondo-suave)}
                .mn-materia .ic{font-size:1.9rem;line-height:1}
                .mn-bloque{display:flex;align-items:center;gap:12px;padding:12px;border:2px solid var(--fondo-suave);border-radius:var(--radio-chico);flex-wrap:wrap}
                .mn-bloque.activo{border-color:var(--oscuro)}
                .mn-bloque .ic{font-size:1.7rem;flex:none}
                .mn-bloque .acciones{display:flex;gap:6px;flex-wrap:wrap}
                .mn-check{display:flex;align-items:center;gap:10px;padding:9px 10px;border-radius:var(--radio-chico);cursor:pointer;background:var(--fondo-suave)}
                .mn-check input{width:20px;height:20px;flex:none}
                .mn-check.ya{opacity:.65;cursor:default}
                .mn-grupo summary{cursor:pointer;list-style:none;display:flex;align-items:center;gap:10px;padding:10px 12px;background:var(--fondo-suave);border-radius:var(--radio-chico)}
                .mn-grupo summary::-webkit-details-marker{display:none}
                .mn-grupo[open] summary{border-bottom-left-radius:0;border-bottom-right-radius:0}
                .mn-grupo ul{list-style:none;margin:0;padding:6px 12px 10px;display:grid;gap:6px;border:2px solid var(--fondo-suave);border-top:0;border-radius:0 0 var(--radio-chico) var(--radio-chico)}
            </style>

            <?php
            $pasoActual = $bloque ? 3 : ($materia ? 2 : 1);
            ?>
            <div class="mn-pasos" aria-label="Pasos">
                <span class="<?= $pasoActual === 1 ? 'ahora' : 'hecho' ?>">1 · Materia</span>
                <span class="<?= $pasoActual === 2 ? 'ahora' : ($pasoActual > 2 ? 'hecho' : '') ?>">2 · Bloque</span>
                <span class="<?= $pasoActual === 3 ? 'ahora' : '' ?>">3 · Actividades</span>
                <span class="<?= $seleccion && $tienePin ? 'hecho' : '' ?>">4 · PIN y encender</span>
            </div>

            <!-- 0 · Lo que ya tiene, con la salida rápida de «empezar de cero» -->
            <div class="tarjeta" id="lista" style="padding:22px;margin-bottom:18px">
                <?php if (!$seleccion): ?>
                    <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:6px">
                        La lista de tu hijo está vacía
                    </h2>
                    <p style="color:var(--texto-tenue)">
                        Empieza abajo: elige una materia, luego un bloque (por ejemplo
                        <b>Bosque de Vocales</b>) y decide si le asignas el bloque entero o
                        solo algunas actividades.
                    </p>
                <?php else: ?>
                    <div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
                        <div>
                            <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:4px">
                                Tu hijo tiene <?= count($seleccion) ?>
                                <?= count($seleccion) === 1 ? 'actividad asignada' : 'actividades asignadas' ?>
                            </h2>
                            <p style="color:var(--texto-tenue);font-size:.92rem">
                                ¿Quieres empezar de cero? Vacía todo con un clic y vuelve a asignar.
                            </p>
                        </div>
                        <form method="post"
                              onsubmit="return confirm('¿Quitar las <?= count($seleccion) ?> actividades y empezar de cero?')">
                            <?= campoCsrf() ?>
                            <input type="hidden" name="accion" value="vaciar">
                            <button class="btn btn-principal">🧹 Vaciar todo y empezar de cero</button>
                        </form>
                    </div>

                    <details style="margin-top:14px" <?= count($agrupada) <= 2 && count($seleccion) <= 12 ? 'open' : '' ?>>
                        <summary style="cursor:pointer;color:var(--oscuro);font-weight:600">
                            Ver o quitar por bloque
                        </summary>

                        <div style="display:grid;gap:14px;margin-top:12px">
                            <?php foreach ($agrupada as $cSlug => $cat): ?>
                                <div>
                                    <div style="display:flex;justify-content:space-between;align-items:center;gap:8px;margin-bottom:6px">
                                        <b style="color:var(--oscuro)"><?= e($cat['icono']) ?> <?= e($cat['nombre']) ?></b>
                                        <?php if (count($cat['bloques']) > 1): ?>
                                            <form method="post">
                                                <?= campoCsrf() ?><?= $campoVolver() ?>
                                                <input type="hidden" name="accion" value="quitar_grupo">
                                                <input type="hidden" name="tipo" value="categoria">
                                                <input type="hidden" name="slug" value="<?= e((string) $cSlug) ?>">
                                                <button class="btn btn-secundario btn-chico">Quitar toda la materia</button>
                                            </form>
                                        <?php endif; ?>
                                    </div>

                                    <div style="display:grid;gap:6px">
                                        <?php foreach ($cat['bloques'] as $bSlug => $bl): ?>
                                            <details class="mn-grupo">
                                                <summary>
                                                    <span style="font-size:1.3rem"><?= e($bl['icono']) ?></span>
                                                    <span style="flex:1;min-width:0">
                                                        <b style="color:var(--oscuro)"><?= e($bl['nombre']) ?></b>
                                                        <small style="color:var(--texto-tenue)">
                                                            · <?= count($bl['items']) ?>
                                                            <?= count($bl['items']) === 1 ? 'actividad' : 'actividades' ?> ▾
                                                        </small>
                                                    </span>
                                                    <form method="post" style="flex:none">
                                                        <?= campoCsrf() ?><?= $campoVolver() ?>
                                                        <input type="hidden" name="accion" value="quitar_grupo">
                                                        <input type="hidden" name="tipo" value="bloque">
                                                        <input type="hidden" name="slug" value="<?= e((string) $bSlug) ?>">
                                                        <button class="btn btn-secundario btn-chico">Quitar bloque</button>
                                                    </form>
                                                </summary>
                                                <ul>
                                                    <?php foreach ($bl['items'] as $a): ?>
                                                        <li style="display:flex;align-items:center;gap:10px">
                                                            <span style="font-size:1.2rem;flex:none"><?= e((string) $a['icon']) ?></span>
                                                            <span style="flex:1;min-width:0">
                                                                <?= e($a['title']) ?>
                                                                <small style="color:var(--texto-tenue)">· <?= e((string) $a['nivel']) ?></small>
                                                            </span>
                                                            <form method="post" style="flex:none">
                                                                <?= campoCsrf() ?><?= $campoVolver() ?>
                                                                <input type="hidden" name="accion" value="quitar">
                                                                <input type="hidden" name="actividad" value="<?= (int) $a['id'] ?>">
                                                                <button class="btn btn-secundario btn-chico"
                                                                        aria-label="Quitar <?= e($a['title']) ?>">Quitar</button>
                                                            </form>
                                                        </li>
                                                    <?php endforeach; ?>
                                                </ul>
                                            </details>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    </details>
                <?php endif; ?>
            </div>

            <!-- 1 · Materia -->
            <div class="tarjeta" id="paso-materia" style="padding:22px;margin-bottom:18px">
                <div class="mn-paso">Paso 1</div>
                <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:12px">
                    ¿Qué materia quieres asignar?
                </h2>

                <div class="mn-materias">
                    <?php foreach ($categorias as $c): ?>
                        <a class="mn-materia <?= $materia && $materia['slug'] === $c['slug'] ? 'activa' : '' ?>"
                           href="<?= e(url('usuario/modo-nino.php?materia=' . rawurlencode($c['slug']) . '#paso-bloques')) ?>">
                            <span class="ic"><?= e((string) $c['icon']) ?></span>
                            <?= e($c['name']) ?>
                        </a>
                    <?php endforeach; ?>
                </div>
            </div>

            <?php if ($materia): ?>
                <!-- 2 · Bloque -->
                <div class="tarjeta" id="paso-bloques" style="padding:22px;margin-bottom:18px">
                    <div class="mn-paso">Paso 2 · <?= e((string) $materia['icon']) ?> <?= e($materia['name']) ?></div>
                    <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:6px">
                        ¿Le asignas un bloque entero o eliges una por una?
                    </h2>
                    <p style="color:var(--texto-tenue);font-size:.92rem;margin-bottom:14px">
                        <b>Asignar todo el bloque</b> pone todas sus actividades de una vez.
                        <b>Elegir una por una</b> te deja marcar solo las que quieras.
                    </p>

                    <?php if (!$bloques): ?>
                        <p style="color:var(--texto-tenue)">Esta materia todavía no tiene actividades publicadas.</p>
                    <?php else: ?>
                        <div style="display:grid;gap:8px">
                            <?php foreach ($bloques as $b):
                                $total    = (int) $b['total'];
                                $elegidas = (int) $b['elegidas'];
                                $completo = $elegidas >= $total;
                            ?>
                                <div class="mn-bloque <?= $bloque && $bloque['slug'] === $b['slug'] ? 'activo' : '' ?>">
                                    <span class="ic"><?= e((string) $b['icon']) ?></span>
                                    <span style="flex:1;min-width:180px">
                                        <b style="color:var(--oscuro);display:block"><?= e($b['name']) ?></b>
                                        <small style="color:var(--texto-tenue)">
                                            <?= $total ?> <?= $total === 1 ? 'actividad' : 'actividades' ?>
                                            <?php if ($completo): ?>
                                                · <span style="color:var(--verde)">✓ todas asignadas</span>
                                            <?php elseif ($elegidas > 0): ?>
                                                · <?= $elegidas ?> ya asignada<?= $elegidas === 1 ? '' : 's' ?>
                                            <?php endif; ?>
                                        </small>
                                    </span>
                                    <span class="acciones">
                                        <?php if (!$completo): ?>
                                            <form method="post">
                                                <?= campoCsrf() ?>
                                                <input type="hidden" name="materia" value="<?= e($materia['slug']) ?>">
                                                <input type="hidden" name="accion" value="agregar_grupo">
                                                <input type="hidden" name="tipo" value="bloque">
                                                <input type="hidden" name="slug" value="<?= e($b['slug']) ?>">
                                                <button class="btn btn-principal btn-chico">Asignar todo el bloque</button>
                                            </form>
                                        <?php endif; ?>
                                        <a class="btn btn-secundario btn-chico"
                                           href="<?= e(url('usuario/modo-nino.php?materia=' . rawurlencode($materia['slug'])
                                                . '&bloque=' . rawurlencode($b['slug']) . '#paso-actividades')) ?>">
                                            Elegir una por una
                                        </a>
                                    </span>
                                </div>
                            <?php endforeach; ?>
                        </div>

                        <?php if (count($bloques) > 1): ?>
                            <form method="post" style="margin-top:14px">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="materia" value="<?= e($materia['slug']) ?>">
                                <input type="hidden" name="accion" value="agregar_grupo">
                                <input type="hidden" name="tipo" value="categoria">
                                <input type="hidden" name="slug" value="<?= e($materia['slug']) ?>">
                                <button class="btn btn-secundario btn-chico">
                                    O asignar toda <?= e($materia['name']) ?> (<?= array_sum(array_column($bloques, 'total')) ?>)
                                </button>
                            </form>
                        <?php endif; ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <?php if ($bloque): ?>
                <!-- 3 · Actividades del bloque -->
                <div class="tarjeta" id="paso-actividades" style="padding:22px;margin-bottom:18px">
                    <div class="mn-paso">Paso 3 · <?= e((string) $bloque['icon']) ?> <?= e($bloque['name']) ?></div>
                    <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:6px">
                        Marca las que quieres asignar
                    </h2>

                    <form method="post" id="form-bloque">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="materia" value="<?= e($materia['slug']) ?>">
                        <input type="hidden" name="bloque" value="<?= e($bloque['slug']) ?>">
                        <input type="hidden" name="accion" value="agregar">

                        <div style="display:flex;gap:8px;flex-wrap:wrap;margin-bottom:12px">
                            <button type="button" class="btn btn-secundario btn-chico" data-marcar="1">Marcar todas</button>
                            <button type="button" class="btn btn-secundario btn-chico" data-marcar="0">Desmarcar todas</button>
                        </div>

                        <ul style="list-style:none;margin:0 0 14px;padding:0;display:grid;gap:6px">
                            <?php foreach ($delBloque as $a): $ya = (bool) $a['elegida']; ?>
                                <li>
                                    <label class="mn-check <?= $ya ? 'ya' : '' ?>">
                                        <?php if ($ya): ?>
                                            <input type="checkbox" checked disabled>
                                        <?php else: ?>
                                            <input type="checkbox" name="actividades[]" value="<?= (int) $a['id'] ?>">
                                        <?php endif; ?>
                                        <span style="font-size:1.3rem"><?= e((string) $a['icon']) ?></span>
                                        <span style="flex:1;min-width:0">
                                            <b style="color:var(--oscuro);display:block;font-size:.95rem"><?= e($a['title']) ?></b>
                                            <small style="color:var(--texto-tenue)">
                                                <?= e((string) $a['nivel']) ?> · <?= (int) $a['estaciones'] ?> ejercicios
                                                <?= $ya ? ' · <span style="color:var(--verde)">✓ ya asignada</span>' : '' ?>
                                            </small>
                                        </span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <button class="btn btn-principal" id="btn-asignar">Asignar las marcadas</button>
                        <a class="btn btn-secundario"
                           href="<?= e(url('usuario/modo-nino.php?materia=' . rawurlencode($materia['slug']) . '#paso-bloques')) ?>">
                            Volver a los bloques
                        </a>
                    </form>
                </div>

                <script>
                (function () {
                    var f = document.getElementById('form-bloque');
                    if (!f) return;
                    var cajas = f.querySelectorAll('input[name="actividades[]"]');
                    var btn = document.getElementById('btn-asignar');
                    function contar() {
                        var n = 0;
                        cajas.forEach(function (c) { if (c.checked) n++; });
                        btn.textContent = n ? 'Asignar las ' + n + ' marcadas' : 'Asignar las marcadas';
                    }
                    f.querySelectorAll('[data-marcar]').forEach(function (b) {
                        b.addEventListener('click', function () {
                            var v = b.getAttribute('data-marcar') === '1';
                            cajas.forEach(function (c) { c.checked = v; });
                            contar();
                        });
                    });
                    cajas.forEach(function (c) { c.addEventListener('change', contar); });
                })();
                </script>
            <?php endif; ?>

            <!-- Búsqueda libre, para quien sabe exactamente qué quiere -->
            <details class="tarjeta" style="padding:18px 22px;margin-bottom:18px" <?= $fBuscar !== '' || $fNivel !== '' ? 'open' : '' ?>>
                <summary style="cursor:pointer;color:var(--oscuro);font-weight:600">
                    🔎 ¿Buscas algo concreto? Busca por nombre o por grado
                </summary>

                <form method="get" action="<?= e(url('usuario/modo-nino.php')) ?>#buscador" id="buscador"
                      style="display:flex;flex-wrap:wrap;gap:8px;align-items:end;margin:14px 0 16px">
                    <div class="campo-simple" style="flex:1;min-width:160px;margin:0">
                        <label for="buscar">Buscar</label>
                        <input id="buscar" name="buscar" type="search"
                               value="<?= e($fBuscar) ?>" placeholder="Sumas, letra M, colores…">
                    </div>

                    <div class="campo-simple" style="margin:0">
                        <label for="nivel">Grado</label>
                        <select id="nivel" name="nivel">
                            <option value="">Todos</option>
                            <?php foreach ($niveles as $n): ?>
                                <option value="<?= e($n['slug']) ?>"
                                    <?= $fNivel === $n['slug'] ? 'selected' : '' ?>>
                                    <?= e($n['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <button class="btn btn-secundario">Buscar</button>
                </form>

                <?php if ($candidatas): ?>
                    <form method="post">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="nivel" value="<?= e($fNivel) ?>">
                        <input type="hidden" name="buscar" value="<?= e($fBuscar) ?>">
                        <input type="hidden" name="accion" value="agregar">

                        <ul style="list-style:none;margin:0 0 14px;padding:0;display:grid;gap:6px;max-height:340px;overflow-y:auto">
                            <?php foreach ($candidatas as $a): ?>
                                <li>
                                    <label class="mn-check">
                                        <input type="checkbox" name="actividades[]"
                                               value="<?= (int) $a['id'] ?>">
                                        <span style="font-size:1.3rem"><?= e((string) $a['icon']) ?></span>
                                        <span style="flex:1;min-width:0">
                                            <b style="color:var(--oscuro);display:block;font-size:.95rem"><?= e($a['title']) ?></b>
                                            <small style="color:var(--texto-tenue)">
                                                <?= e((string) $a['categoria']) ?> ·
                                                <?= e((string) $a['nivel']) ?> ·
                                                <?= (int) $a['estaciones'] ?> ejercicios
                                            </small>
                                        </span>
                                    </label>
                                </li>
                            <?php endforeach; ?>
                        </ul>

                        <button class="btn btn-principal">Asignar las marcadas</button>
                    </form>
                <?php elseif ($fBuscar !== '' || $fNivel !== ''): ?>
                    <p style="color:var(--texto-tenue)">
                        No quedó nada que asignar con esa búsqueda. Puede que ya estén todas
                        en la lista.
                    </p>
                <?php endif; ?>
            </details>

            <!-- 3 · El PIN -->
            <div class="tarjeta" style="padding:22px;margin-bottom:18px">
                <div class="mn-paso">Paso 4</div>
                <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:6px">
                    <?= $tienePin ? 'Cambiar el PIN' : 'Pon un PIN' ?>
                </h2>
                <p style="color:var(--texto-tenue);font-size:.92rem;margin-bottom:14px">
                    Cuatro números. Es lo que te pediremos para salir del modo niño, así que
                    tu hijo no debería saberlo.
                </p>

                <form method="post" style="display:flex;gap:10px;align-items:end;flex-wrap:wrap">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="pin">

                    <div class="campo-simple" style="margin:0;max-width:170px">
                        <label for="pin">PIN</label>
                        <input id="pin" name="pin" type="password"
                               inputmode="numeric" pattern="[0-9]{4}" maxlength="4"
                               autocomplete="new-password" required
                               style="text-align:center;letter-spacing:.4em">
                    </div>

                    <button class="btn btn-secundario">Guardar PIN</button>

                    <?php if ($tienePin): ?>
                        <span style="color:var(--verde);font-size:.9rem">✓ Ya tienes uno puesto</span>
                    <?php endif; ?>
                </form>
            </div>

            <!-- 4 · Encender -->
            <div class="tarjeta" style="padding:22px;text-align:center">
                <?php if (!$seleccion || !$tienePin): ?>
                    <p style="color:var(--texto-tenue);margin-bottom:14px">
                        Para encender el modo te falta
                        <?php if (!$seleccion && !$tienePin): ?>
                            <b>elegir actividades</b> y <b>poner un PIN</b>.
                        <?php elseif (!$seleccion): ?>
                            <b>elegir al menos una actividad</b>.
                        <?php else: ?>
                            <b>poner un PIN</b>.
                        <?php endif; ?>
                    </p>
                    <button class="btn btn-principal" disabled>Encender el modo niño</button>
                <?php else: ?>
                    <p style="color:var(--texto-tenue);margin-bottom:14px">
                        Tu hijo verá <b style="color:var(--oscuro)"><?= count($seleccion) ?></b>
                        <?= count($seleccion) === 1 ? 'actividad' : 'actividades' ?>.
                        Para salir necesitarás tu PIN.
                    </p>
                    <form method="post">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="encender">
                        <button class="btn btn-principal">Encender el modo niño</button>
                    </form>
                <?php endif; ?>
            </div>

        <?php endif; ?>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
