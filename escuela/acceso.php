<?php
/**
 * escuela/acceso.php — Cómo entran tus estudiantes
 *
 * Una pantalla, una decisión. La primera versión repartía esto en dos
 * casillas dentro de «Estudiantes» —«permitir el código» y «pedir PIN»—
 * y el docente tenía que deducir de la combinación cómo iba a entrar su
 * clase. No funcionó, y con razón: nadie quiere combinar interruptores,
 * quiere elegir cómo entran sus niños.
 *
 * Aquí hay tres respuestas a una pregunta, y debajo lo que haga falta
 * según la que se elija.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  SALÓN Y CASA
 * ─────────────────────────────────────────────────────────────────────
 *
 * «Que la dirección sirva en el salón pero que en casa entren con su
 * cuenta» no se puede resolver adivinando dónde está el niño: **el
 * servidor no lo sabe**. La IP falla en cuanto hay datos móviles y la
 * hora falla con la jornada de la tarde.
 *
 * Lo que sí se puede es que el docente **abra la clase** al empezar y se
 * cierre sola. Mientras está abierta, la dirección funciona; cerrada, no
 * abre nada y en casa hace falta la cuenta. Es honesto y es como piensa
 * quien da la clase.
 */

require_once dirname(__DIR__) . '/config/config.php';

exigirEscuela();

$cursoId = getEntero('curso');
$curso   = exigirCursoPropio($cursoId);

if (!aulaInstalada()) {
    mensaje('error', 'Falta la migración del aula. Corre database/migracion-aula.php --aplicar');
    redirigir('escuela/curso.php?id=' . $cursoId);
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');

    // ── Cómo entran ──────────────────────────────────────────────────
    if ($accion === 'modo') {

        $modo = (string) post('modo');
        $modo = in_array($modo, ['cuenta', 'lista', 'ambos'], true) ? $modo : 'cuenta';
        $pin  = !empty($_POST['aula_pin']);

        if ($modo !== 'cuenta') {
            codigoDeCurso($cursoId);   // lo crea si aún no había
        }

        ejecutar('UPDATE courses SET acceso_modo = ?, aula_pin = ?, aula_activa = ?
                   WHERE id = ?',
                 [$modo, $pin ? 1 : 0, $modo === 'cuenta' ? 0 : 1, $cursoId]);

        /*
         * Si se pide PIN, quien no tenga se queda fuera. Se generan los
         * que falten sin preguntar: dejar a media clase sin poder entrar
         * el lunes por la mañana es peor que cualquier otra molestia.
         */
        if ($modo !== 'cuenta' && $pin) {
            $nuevos = generarPinesDelCurso($cursoId, true);

            if ($nuevos) {
                mensaje('ok', 'Guardado. Se generaron ' . count($nuevos)
                            . ' PIN nuevos: imprímelos desde el botón de abajo.');
            } else {
                mensaje('ok', 'Guardado.');
            }
        } else {
            mensaje('ok', 'Guardado.');
        }

        redirigir('escuela/acceso.php?curso=' . $cursoId);
    }

    // ── Abrir y cerrar la clase ──────────────────────────────────────
    if ($accion === 'abrir') {
        $horas = post('horas');
        abrirClase($cursoId, $horas === 'sin_limite' ? null : (int) $horas);

        mensaje('ok', $horas === 'sin_limite'
            ? 'Clase abierta sin límite de hora.'
            : 'Clase abierta durante ' . (int) $horas . ' hora(s).');

        redirigir('escuela/acceso.php?curso=' . $cursoId);
    }

    if ($accion === 'cerrar') {
        cerrarClase($cursoId);
        mensaje('ok', 'Clase cerrada. La dirección ya no abre nada.');
        redirigir('escuela/acceso.php?curso=' . $cursoId);
    }

    // ── Código ───────────────────────────────────────────────────────
    if ($accion === 'nuevo_codigo') {
        $nuevo = cambiarCodigoDeCurso($cursoId);
        mensaje('ok', 'Código nuevo: ' . $nuevo . '. El anterior ya no sirve.');
        redirigir('escuela/acceso.php?curso=' . $cursoId);
    }

    // ── PIN ──────────────────────────────────────────────────────────
    if ($accion === 'pines') {
        $todos  = !empty($_POST['todos']);
        $nuevos = generarPinesDelCurso($cursoId, !$todos);

        mensaje($nuevos ? 'ok' : 'info',
            $nuevos
                ? count($nuevos) . ' PIN generados. Ábrelos en la hoja para imprimir.'
                : 'Todos tienen ya su PIN.');

        redirigir('escuela/acceso.php?curso=' . $cursoId);
    }

    if ($accion === 'pin_uno') {
        $r = ponerPinDeEstudiante($cursoId, (int) post('usuario'));
        mensaje($r['ok'] ? 'ok' : 'error',
            $r['ok'] ? 'PIN nuevo generado. Míralo en la hoja para imprimir.' : $r['error']);

        redirigir('escuela/acceso.php?curso=' . $cursoId);
    }
}

// Relee: el POST pudo cambiarlo todo.
$curso = exigirCursoPropio($cursoId);

$modo    = modoAcceso($curso);
$conPin  = (int) ($curso['aula_pin'] ?? 0) === 1;
$codigo  = $curso['access_code'] ?? null;
$abierta = claseAbierta($curso);

$estudiantes = traerTodo(
    'SELECT u.id, u.name, u.email, u.role, (u.pin_hash IS NOT NULL) AS tiene_pin
       FROM course_students cs JOIN users u ON u.id = cs.user_id
      WHERE cs.course_id = ? ORDER BY u.name',
    [$cursoId]
);

$sinPin = count(array_filter($estudiantes,
    fn($a) => $a['role'] === 'student' && !$a['tiene_pin']));

$titulo      = 'Acceso · ' . $curso['name'];
$escuelaZona = 'acceso';
$cursoActual = $curso;
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1>Cómo entran tus estudiantes</h1>
        <p class="bajada"><?= e($curso['name']) ?> · <?= count($estudiantes) ?> estudiantes</p>
    </div>
    <?php if ($modo !== 'cuenta' && $codigo): ?>
        <a class="btn btn-principal btn-chico"
           href="<?= e(url('escuela/tarjetas.php?curso=' . $cursoId)) ?>">
            🖨️ Hoja para repartir
        </a>
    <?php endif; ?>
</header>


<!-- ── 1. La decisión ───────────────────────────────────────────────── -->

<form method="post">
    <?= campoCsrf() ?>
    <input type="hidden" name="accion" value="modo">

    <section class="bloque-panel">
        <h2>Elige una</h2>

        <label class="opcion-acceso-curso <?= $modo === 'cuenta' ? 'elegida' : '' ?>">
            <input type="radio" name="modo" value="cuenta" <?= $modo === 'cuenta' ? 'checked' : '' ?>>
            <span>
                <b>🔑 Cada uno con su cuenta</b>
                <span>
                    Entran con su usuario y su contraseña, en el salón y en casa.
                    Es lo normal a partir de tercero, y lo único que sirve si quieres
                    que trabajen desde casa sin depender de ti.
                </span>
            </span>
        </label>

        <label class="opcion-acceso-curso <?= $modo === 'lista' ? 'elegida' : '' ?>">
            <input type="radio" name="modo" value="lista" <?= $modo === 'lista' ? 'checked' : '' ?>>
            <span>
                <b>👆 Solo tocando su nombre en la lista</b>
                <span>
                    Escribes una dirección en el tablero, ellos la abren y tocan su nombre.
                    Lo más rápido con niños pequeños. <b>No sirve desde casa</b>: si cierras
                    la clase, la dirección deja de abrir.
                </span>
            </span>
        </label>

        <label class="opcion-acceso-curso <?= $modo === 'ambos' ? 'elegida' : '' ?>">
            <input type="radio" name="modo" value="ambos" <?= $modo === 'ambos' ? 'checked' : '' ?>>
            <span>
                <b>👆🔑 Las dos cosas</b>
                <span>
                    En clase tocan su nombre; en casa entran con su usuario y contraseña.
                    Es lo que suele querer un docente que manda trabajo para la casa.
                </span>
            </span>
        </label>

        <?php /* El PIN solo tiene sentido si se usa la lista. */ ?>
        <div class="sub-opcion <?= $modo === 'cuenta' ? 'apagada' : '' ?>">
            <label class="opcion-aula">
                <input type="checkbox" name="aula_pin" value="1" <?= $conPin ? 'checked' : '' ?>
                       <?= $modo === 'cuenta' ? 'disabled' : '' ?>>
                <span>
                    <b>Pedir un PIN de 4 números al tocar el nombre</b>
                    <span>
                        Evita que un compañero entre en la cuenta de otro. Sin PIN, cualquiera
                        que tenga la dirección puede entrar como cualquiera de la lista.
                        <?php if ($sinPin > 0 && !$conPin): ?>
                            <br><b>Al activarlo se generan los <?= $sinPin ?> PIN que faltan.</b>
                        <?php endif; ?>
                    </span>
                </span>
            </label>
        </div>

        <div class="pie-bloque">
            <button class="btn btn-principal" type="submit">Guardar</button>
        </div>
    </section>
</form>


<?php if ($modo !== 'cuenta' && $codigo): ?>

<!-- ── 2. La dirección y la clase abierta ───────────────────────────── -->

<section class="bloque-panel bloque-aula">
    <h2>La dirección para el tablero</h2>

    <div class="codigo-caja">
        <div>
            <span class="rotulo-codigo">Escribe esto en el tablero</span>
            <span class="url-aula"><?= e(urlDeAula((string) $codigo)) ?></span>
        </div>
        <div>
            <span class="rotulo-codigo">Código</span>
            <span class="codigo-grande"><?= e($codigo) ?></span>
        </div>
    </div>

    <?php /* El estado de la clase, en grande: es lo que hay que mirar. */ ?>
    <div class="estado-clase <?= $abierta ? 'abierta' : 'cerrada' ?>">
        <span class="punto" aria-hidden="true"></span>
        <div>
            <b><?= $abierta ? 'La clase está ABIERTA' : 'La clase está CERRADA' ?></b>
            <span>
                <?php if ($abierta): ?>
                    Ahora mismo pueden entrar tocando su nombre · <?= e(tiempoDeClase($curso)) ?>
                <?php else: ?>
                    La dirección no abre nada. Ábrela cuando empiece la clase.
                <?php endif; ?>
            </span>
        </div>
    </div>

    <div class="pie-bloque">
        <?php if ($abierta): ?>
            <form method="post">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="cerrar">
                <button class="btn btn-secundario" type="submit">Cerrar la clase ahora</button>
            </form>
        <?php endif; ?>

        <form method="post" class="form-linea" style="gap:8px">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="abrir">
            <button class="btn btn-principal btn-chico" type="submit" name="horas" value="1">
                Abrir 1 hora
            </button>
            <button class="btn btn-principal btn-chico" type="submit" name="horas" value="3">
                3 horas
            </button>
            <button class="btn btn-secundario btn-chico" type="submit" name="horas" value="sin_limite">
                Siempre abierta
            </button>
        </form>
    </div>

    <p class="nota-panel" style="margin-top:14px">
        <b>Por qué hay que abrirla y cerrarla.</b>
        No hay forma de saber desde aquí si quien abre la dirección está en tu salón o en
        su casa. Abrirla al empezar la clase y dejar que se cierre sola es lo que de verdad
        limita la lista al rato de trabajo. Si eliges «siempre abierta», la dirección
        funciona también desde casa.
    </p>

    <div class="pie-bloque">
        <a class="btn btn-secundario btn-chico" href="<?= e(urlDeAula((string) $codigo)) ?>"
           target="_blank" rel="noopener">Ver lo que ven ellos ↗</a>

        <form method="post"
              onsubmit="return confirm('¿Cambiar el código?\n\nEl anterior dejará de servir al instante.')">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="nuevo_codigo">
            <button class="btn btn-secundario btn-chico" type="submit">Cambiar el código</button>
        </form>
    </div>
</section>


<!-- ── 3. Quién puede entrar y cómo ─────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Estado de cada estudiante</h2>
    <p class="nota-panel">
        Los usuarios y los PIN se reparten desde la
        <a href="<?= e(url('escuela/tarjetas.php?curso=' . $cursoId)) ?>">hoja para
        imprimir</a>, que trae una tarjeta por niño para recortar.
    </p>

    <?php if (!$estudiantes): ?>
        <p class="nota-panel">
            Todavía no hay nadie.
            <a href="<?= e(url('escuela/estudiantes.php?curso=' . $cursoId)) ?>">Matricula
            a tus estudiantes</a> y vuelve.
        </p>
    <?php else: ?>
        <table class="tabla-panel">
            <thead>
                <tr>
                    <th>Estudiante</th>
                    <th>Usuario</th>
                    <?php if ($conPin): ?><th>PIN</th><?php endif; ?>
                    <th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($estudiantes as $a): ?>
                <tr>
                    <td><strong><?= e($a['name']) ?></strong></td>
                    <td class="tenue mono"><?= e($a['email']) ?></td>

                    <?php if ($conPin): ?>
                        <td>
                            <?php if ($a['role'] !== 'student'): ?>
                                <span class="tenue">cuenta familiar</span>
                            <?php elseif ($a['tiene_pin']): ?>
                                <span class="pastilla lista">puesto</span>
                            <?php else: ?>
                                <span class="pastilla nada">sin PIN</span>
                            <?php endif; ?>
                        </td>
                    <?php endif; ?>

                    <td class="acciones-fila">
                        <?php if ($conPin && $a['role'] === 'student'): ?>
                            <form method="post"
                                  onsubmit="return confirm('¿Generar un PIN nuevo para <?= e($a['name']) ?>?\n\nEl anterior dejará de servir.')">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="pin_uno">
                                <input type="hidden" name="usuario" value="<?= (int) $a['id'] ?>">
                                <button class="btn btn-secundario btn-chico" type="submit">
                                    <?= $a['tiene_pin'] ? 'Rehacer PIN' : 'Generar PIN' ?>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <?php if ($conPin): ?>
            <div class="pie-bloque">
                <?php if ($sinPin > 0): ?>
                    <form method="post">
                        <?= campoCsrf() ?>
                        <input type="hidden" name="accion" value="pines">
                        <button class="btn btn-principal" type="submit">
                            Generar los <?= $sinPin ?> PIN que faltan
                        </button>
                    </form>
                <?php endif; ?>

                <form method="post"
                      onsubmit="return confirm('¿Rehacer TODOS los PIN?\n\nLos que ya se sabían de memoria dejarán de servir.')">
                    <?= campoCsrf() ?>
                    <input type="hidden" name="accion" value="pines">
                    <input type="hidden" name="todos" value="1">
                    <button class="btn btn-secundario btn-chico" type="submit">Rehacer todos</button>
                </form>
            </div>
        <?php endif; ?>
    <?php endif; ?>
</section>

<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
