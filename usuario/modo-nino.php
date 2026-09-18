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

    if ($accion === 'agregar') {
        $puestas = 0;

        foreach ((array) ($_POST['actividades'] ?? []) as $id) {
            if (agregarAlModoNino($usuarioId, (int) $id)) {
                $puestas++;
            }
        }

        mensaje($puestas > 0 ? 'ok' : 'info',
            $puestas > 0
                ? ($puestas === 1 ? 'Se agregó 1 actividad.' : "Se agregaron $puestas actividades.")
                : 'No se agregó nada: marca al menos una.');

        redirigir('usuario/modo-nino.php?' . http_build_query([
            'categoria' => get('categoria', ''),
            'nivel'     => get('nivel', ''),
            'buscar'    => get('buscar', ''),
        ]));
    }

    if ($accion === 'agregar_grupo') {
        $puestas = agregarGrupoAlModoNino(
            $usuarioId, (string) post('tipo', ''), (string) post('slug', ''));

        mensaje($puestas > 0 ? 'ok' : 'info',
            $puestas > 0 ? "Se agregaron $puestas actividades." : 'Ahí no había nada nuevo que agregar.');

        redirigir('usuario/modo-nino.php');
    }

    if ($accion === 'quitar') {
        quitarDelModoNino($usuarioId, (int) post('actividad', 0));
        mensaje('ok', 'Quitada de la lista.');
        redirigir('usuario/modo-nino.php');
    }

    if ($accion === 'vaciar') {
        vaciarModoNino($usuarioId);
        mensaje('ok', 'Lista vacía.');
        redirigir('usuario/modo-nino.php');
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

$fCat    = trim((string) get('categoria', ''));
$fNivel  = trim((string) get('nivel', ''));
$fBuscar = trim((string) get('buscar', ''));

$candidatas = [];

if (!$enModo && ($fCat !== '' || $fNivel !== '' || $fBuscar !== '')) {
    $where  = ['a.status = "published"'];
    $params = [];

    if ($fCat !== '')   { $where[] = 'c.slug = ?'; $params[] = $fCat; }
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

            <!-- 1 · La lista -->
            <div class="tarjeta" style="padding:22px;margin-bottom:18px">
                <div style="display:flex;justify-content:space-between;align-items:baseline;gap:12px;flex-wrap:wrap;margin-bottom:14px">
                    <h2 style="color:var(--oscuro);font-size:1.15rem">
                        Lo que verá tu hijo
                        <span style="color:var(--texto-tenue);font-weight:500">
                            (<?= count($seleccion) ?>)
                        </span>
                    </h2>

                    <?php if ($seleccion): ?>
                        <form method="post" onsubmit="return confirm('¿Vaciar la lista entera?')">
                            <?= campoCsrf() ?>
                            <input type="hidden" name="accion" value="vaciar">
                            <button class="btn btn-secundario btn-chico">Vaciar</button>
                        </form>
                    <?php endif; ?>
                </div>

                <?php if (!$seleccion): ?>
                    <p style="color:var(--texto-tenue)">
                        Todavía no has elegido nada. Búscalas abajo y agrégalas — por
                        materia entera si quieres ir rápido.
                    </p>
                <?php else: ?>
                    <ul style="list-style:none;margin:0;padding:0;display:grid;gap:8px">
                        <?php foreach ($seleccion as $a): ?>
                            <li style="display:flex;align-items:center;gap:12px;padding:10px 12px;background:var(--fondo-suave);border-radius:var(--radio-chico)">
                                <span style="font-size:1.5rem;flex:none"><?= e((string) $a['icon']) ?></span>
                                <span style="flex:1;min-width:0">
                                    <b style="color:var(--oscuro);display:block"><?= e($a['title']) ?></b>
                                    <small style="color:var(--texto-tenue)">
                                        <?= e((string) $a['categoria']) ?> ·
                                        <?= e((string) $a['nivel']) ?> ·
                                        <?= (int) $a['estaciones'] ?> ejercicios
                                    </small>
                                </span>
                                <form method="post" style="flex:none">
                                    <?= campoCsrf() ?>
                                    <input type="hidden" name="accion" value="quitar">
                                    <input type="hidden" name="actividad" value="<?= (int) $a['id'] ?>">
                                    <button class="btn btn-secundario btn-chico"
                                            aria-label="Quitar <?= e($a['title']) ?>">Quitar</button>
                                </form>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php endif; ?>
            </div>

            <!-- 2 · Agregar -->
            <div class="tarjeta" style="padding:22px;margin-bottom:18px">
                <h2 style="color:var(--oscuro);font-size:1.15rem;margin-bottom:6px">
                    Agregar actividades
                </h2>
                <p style="color:var(--texto-tenue);font-size:.92rem;margin-bottom:14px">
                    Una materia entera de un golpe, o busca las que quieras.
                </p>

                <div style="display:flex;flex-wrap:wrap;gap:8px;margin-bottom:18px">
                    <?php foreach ($categorias as $c): ?>
                        <form method="post">
                            <?= campoCsrf() ?>
                            <input type="hidden" name="accion" value="agregar_grupo">
                            <input type="hidden" name="tipo" value="categoria">
                            <input type="hidden" name="slug" value="<?= e($c['slug']) ?>">
                            <button class="btn btn-secundario btn-chico">
                                <?= e((string) $c['icon']) ?> <?= e($c['name']) ?>
                            </button>
                        </form>
                    <?php endforeach; ?>
                </div>

                <form method="get" style="display:flex;flex-wrap:wrap;gap:8px;align-items:end;margin-bottom:16px">
                    <div class="campo-simple" style="flex:1;min-width:160px;margin:0">
                        <label for="buscar">Buscar</label>
                        <input id="buscar" name="buscar" type="search"
                               value="<?= e($fBuscar) ?>" placeholder="Sumas, letra M, colores…">
                    </div>

                    <div class="campo-simple" style="margin:0">
                        <label for="nivel">Nivel</label>
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
                        <input type="hidden" name="accion" value="agregar">

                        <ul style="list-style:none;margin:0 0 14px;padding:0;display:grid;gap:6px;max-height:340px;overflow-y:auto">
                            <?php foreach ($candidatas as $a): ?>
                                <li>
                                    <label style="display:flex;align-items:center;gap:10px;padding:8px 10px;border-radius:var(--radio-chico);cursor:pointer">
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

                        <button class="btn btn-principal">Agregar las marcadas</button>
                    </form>
                <?php elseif ($fBuscar !== '' || $fNivel !== '' || $fCat !== ''): ?>
                    <p style="color:var(--texto-tenue)">
                        No quedó nada que agregar con esa búsqueda. Puede que ya estén todas
                        en tu lista.
                    </p>
                <?php endif; ?>
            </div>

            <!-- 3 · El PIN -->
            <div class="tarjeta" style="padding:22px;margin-bottom:18px">
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
