<?php
/**
 * admin/colegios/ver.php — Un colegio por dentro
 *
 * Aquí se da de alta a la gente del colegio. Es el «apartado especial»:
 * solo administración de la plataforma, porque crear cuentas aquí es
 * conceder licencia sin cobrar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  DOCENTES Y ESTUDIANTES SE CREAN DISTINTO, Y ES A PROPÓSITO
 * ─────────────────────────────────────────────────────────────────────
 *
 * A un **docente** conviene ponerle su correo real: va a entrar desde su
 * casa, va a olvidar la contraseña y tiene que poder recuperarla solo.
 *
 * A un **estudiante** no se le pide correo: es un menor que no tiene, y
 * exigirlo haría inviable dar de alta una clase entera. Se le genera un
 * usuario con el dominio del colegio y una contraseña que se pueda copiar
 * de un papel.
 *
 * En los dos casos las credenciales se muestran **una sola vez**: en la
 * base solo queda el hash.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$id      = getEntero('id');
$colegio = $id > 0 ? colegioPorId($id) : null;

if (!$colegio) {
    mensaje('error', 'Ese colegio no existe.');
    redirigir('admin/colegios/');
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $accion = post('accion');

    /*
     * Dar de alta gente, vincular, sacar, crear cursos y restablecer
     * contraseñas son **las mismas acciones** que ejecuta el panel de
     * institución en `escuela/institucion.php`. Viven una sola vez, en
     * `accionDeInstitucion()`: tenerlas dos veces significaría que un
     * arreglo se aplica en una pantalla y se olvida en la otra.
     *
     * Lo que sí es exclusivo de aquí es la licencia —el plan, la fecha y
     * el estado son la relación comercial— y por eso se queda debajo.
     */
    $destino = accionDeInstitucion($colegio, (string) $accion,
                                   'admin/colegios/ver.php?id=' . $id);

    if ($destino !== null) {
        redirigir($destino);
    }

    // ── Datos y licencia ─────────────────────────────────────────────
    if ($accion === 'guardar') {

        $hasta = trim(post('licencia_hasta'));

        ejecutar(
            'UPDATE schools SET name = ?, city = ?, plan_id = ?, licencia_hasta = ?,
                                es_prueba = ?, status = ?, dominio = ?, notas = ?
              WHERE id = ?',
            [
                trim(post('nombre')) !== '' ? trim(post('nombre')) : $colegio['name'],
                trim(post('city')),
                (int) post('plan_id') ?: null,
                $hasta !== '' ? $hasta : null,
                !empty($_POST['es_prueba']) ? 1 : 0,
                post('status') === 'inactive' ? 'inactive' : 'active',
                trim(post('dominio')) !== '' ? trim(post('dominio')) : null,
                trim(post('notas')),
                $id,
            ]
        );

        /*
         * La licencia se vuelve a aplicar a TODOS. Sin esto, cambiar el
         * plan o la fecha valdría solo para quien entrara después, y los
         * que ya estaban se quedarían con la licencia vieja sin que nadie
         * lo notara.
         */
        $n = refrescarLicencias($id);

        mensaje('ok', "Guardado. La licencia se aplicó a $n miembro(s).");
        redirigir('admin/colegios/ver.php?id=' . $id);
    }

    // Cualquier otra cosa que llegue por POST no es una acción de esta
    // pantalla; se vuelve a ella sin hacer nada.
    redirigir('admin/colegios/ver.php?id=' . $id);
}

$colegio = colegioPorId($id);

$credenciales = $_SESSION['colegio_creadas'] ?? [];
unset($_SESSION['colegio_creadas']);

$coordinacion = miembrosDeColegio($id, 'school_admin');
$docentes     = miembrosDeColegio($id, 'teacher');
$estudiantes  = miembrosDeColegio($id, 'student');
$cursos       = cursosDeColegio($id);

// Un curso puede ser de cualquiera que dé clase, y la coordinación
// también puede tener el suyo.
$puedenTenerCurso = array_merge($docentes, $coordinacion);
$planes      = traerTodo('SELECT id, name, slug FROM plans WHERE is_active = 1 ORDER BY sort_order');
$viva        = licenciaVigente($colegio);

$titulo    = $colegio['name'];
$panelZona = 'colegios';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>
            <?= e($colegio['name']) ?>
            <?php if ((int) $colegio['es_prueba'] === 1): ?>
                <span class="distintivo naranja">prueba</span>
            <?php endif; ?>
        </h1>
        <p>
            <?= e(urlDeColegio($colegio)) ?> ·
            licencia <?= e(textoLicencia($colegio)) ?>
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini solido" href="<?= e(urlDeColegio($colegio)) ?>"
           target="_blank" rel="noopener">Ver su puerta ↗</a>
        <a class="btn-mini" href="<?= e(url('admin/colegios/')) ?>">← Volver</a>
    </div>
</div>

<?php if (!$viva): ?>
    <div class="aviso mal">
        <b>La licencia no está vigente.</b>
        Sus miembros no tienen acceso completo. Revisa el plan, la fecha y el estado.
    </div>
<?php endif; ?>

<?php
/*
 * Un colegio sin coordinación se lo dice.
 *
 * El rol existe y su panel funciona, pero está escondido dentro de una
 * de las tres opciones del formulario de más abajo: quien monta un
 * colegio no se entera de que existe, y acaba administrándoselo él desde
 * aquí para siempre. Que un colegio se gobierne solo es justo lo que
 * hace que la plataforma crezca sin crecer el soporte.
 */
?>
<?php if (!$coordinacion && $docentes): ?>
    <div class="aviso info">
        <b>Este colegio no tiene coordinación.</b>
        Mientras no la tenga, cada cosa que necesite —un docente nuevo, un curso, una
        contraseña perdida— tiene que hacerla alguien de la plataforma desde aquí.
        <br><br>
        Nombra a alguien como <b>🏛️ Coordinación</b> en el formulario de abajo y tendrá su
        propio panel: verá todos los cursos del colegio, dará de alta a sus docentes y
        estudiantes, y creará cursos sin depender de nadie.
        <b>Ponle su correo real</b>: lo necesita para recuperar su contraseña.
    </div>
<?php endif; ?>

<?php if ($coordinacion): ?>
    <div class="aviso ok">
        <b>Este colegio se administra solo.</b>
        <?= count($coordinacion) === 1
            ? e($coordinacion[0]['name']) . ' entra'
            : count($coordinacion) . ' personas entran' ?>
        en <code><?= e(url('escuela/institucion.php')) ?></code> y gestiona<?=
            count($coordinacion) === 1 ? '' : 'n' ?> sus docentes, sus estudiantes y sus
        cursos sin pasar por aquí.
    </div>
<?php endif; ?>


<?php if ($credenciales): ?>
    <div class="caja" style="border-left:5px solid var(--naranja)">
        <div class="cabeza"><h2>🔑 Anota estas contraseñas ahora</h2></div>
        <div class="cuerpo">
            <p style="color:var(--texto-tenue);font-size:.9rem;margin-bottom:14px">
                <b>No se pueden volver a ver.</b> En la base solo queda cifrada.
                Si se pierden, se generan otras desde la lista.
            </p>

            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead><tr><th>Nombre</th><th>Usuario</th><th>Contraseña</th><th></th></tr></thead>
                    <tbody>
                    <?php foreach ($credenciales as $c): ?>
                        <tr>
                            <td><b><?= e($c['nombre']) ?></b></td>
                            <td class="compacta"><?= e($c['correo']) ?></td>
                            <td><b style="font-family:ui-monospace,monospace"><?= e($c['clave']) ?></b></td>
                            <td class="compacta">
                                <span class="distintivo <?= $c['rol'] === 'student' ? 'gris' : 'azul' ?>">
                                    <?= e(['teacher' => 'docente', 'school_admin' => 'coordinación']
                                          [$c['rol']] ?? 'estudiante') ?>
                                </span>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>

            <div class="pie-formulario" style="margin-top:14px">
                <button class="btn-mini" type="button" onclick="window.print()">🖨️ Imprimir</button>
            </div>
        </div>
    </div>
<?php endif; ?>


<div class="cifras">
    <div class="cifra-caja">
        <div class="n"><?= count($docentes) ?></div>
        <div class="t">Docentes</div>
    </div>
    <div class="cifra-caja verde">
        <div class="n"><?= count($estudiantes) ?></div>
        <div class="t">Estudiantes</div>
    </div>
    <div class="cifra-caja naranja">
        <div class="n"><?= count($cursos) ?></div>
        <div class="t">Cursos</div>
    </div>
    <div class="cifra-caja <?= $viva ? 'verde' : 'rojo' ?>">
        <div class="n" style="font-size:1.1rem"><?= e(textoLicencia($colegio)) ?></div>
        <div class="t"><?= e($colegio['plan_nombre'] ?? 'sin plan') ?></div>
    </div>
</div>


<!-- ── Crear cuentas ────────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>Dar de alta gente del colegio</h2></div>
    <div class="cuerpo">

        <p style="color:var(--texto);font-size:.9rem;line-height:1.6;margin-bottom:16px">
            Un nombre por línea. Se crean las cuentas <b>con la licencia del colegio</b> y
            te damos las contraseñas para repartir.
            A los <b>docentes</b> conviene ponerles su correo real detrás de una coma —lo
            necesitan para recuperar su contraseña—; a los <b>estudiantes</b> se les
            genera un usuario con el dominio del colegio.
        </p>

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="crear">

            <div class="opciones-acceso" style="margin-bottom:14px">
                <label class="opcion-acceso">
                    <input type="radio" name="rol" value="student" checked>
                    <span>
                        <b>🧒 Estudiantes</b>
                        <span>Sin correo. Se les genera el usuario y una contraseña fácil de copiar.</span>
                    </span>
                </label>
                <label class="opcion-acceso">
                    <input type="radio" name="rol" value="teacher">
                    <span>
                        <b>🎓 Docentes</b>
                        <span>Podrán crear cursos y ver el progreso de sus estudiantes.</span>
                    </span>
                </label>
                <label class="opcion-acceso">
                    <input type="radio" name="rol" value="school_admin">
                    <span>
                        <b>🏛️ Coordinación</b>
                        <span>
                            Ve todos los cursos del colegio y da de alta a su gente.
                            <b>Ponle su correo real</b>: lo va a necesitar.
                        </span>
                    </span>
                </label>
            </div>

            <div class="campo">
                <label for="nombres">Nombres</label>
                <textarea id="nombres" name="nombres" rows="7"
                          placeholder="Ana Pérez&#10;Luis Gómez&#10;Marta Ruiz, marta.ruiz@colegio.edu.co"></textarea>
                <p class="ayuda">Máximo <?= MAX_CUENTAS_INSTITUCION ?> por tanda.</p>
            </div>

            <div class="pie-formulario" style="margin-top:14px">
                <button class="btn-mini solido" type="submit">Crear y dar licencia</button>
            </div>
        </form>

        <div class="separador" style="margin:22px 0"></div>

        <h3 style="font-size:.95rem;color:var(--oscuro);margin-bottom:10px">
            O vincular una cuenta que ya existe
        </h3>

        <form method="post" class="formulario">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="vincular">

            <div class="campo">
                <label for="v-correo">Correo exacto</label>
                <input id="v-correo" name="correo" type="email" required maxlength="190">
            </div>

            <div class="campo">
                <label for="v-rol">Como</label>
                <select id="v-rol" name="rol">
                    <option value="teacher">Docente</option>
                    <option value="student">Estudiante</option>
                    <option value="school_admin">Coordinación</option>
                </select>
            </div>

            <div class="pie-formulario" style="margin-top:14px">
                <button class="btn-mini" type="submit">Vincular con licencia</button>
            </div>
        </form>
    </div>
</div>


<!-- ── Miembros ─────────────────────────────────────────────────────── -->

<?php
$grupos = [
    'Coordinación' => [$coordinacion, 'teacher'],
    'Docentes'     => [$docentes, 'teacher'],
    'Estudiantes'  => [$estudiantes, 'student'],
];

// La coordinación se salta si no hay: es un rol que muchos colegios no
// usan, y una tabla vacía más solo estorba.
if (!$coordinacion) {
    unset($grupos['Coordinación']);
}
?>

<?php foreach ($grupos as $nombre => [$gente, $rol]): ?>
    <div class="caja">
        <div class="cabeza"><h2><?= e($nombre) ?> (<?= count($gente) ?>)</h2></div>
        <div class="cuerpo">
            <?php if (!$gente): ?>
                <p class="sin-datos" style="padding:16px 0">Todavía no hay ninguno.</p>
            <?php else: ?>
                <div class="tabla-envoltorio">
                    <table class="tabla">
                        <thead>
                            <tr>
                                <th>Nombre</th>
                                <th>Usuario</th>
                                <th class="cifra"><?= $rol === 'teacher' ? 'Cursos' : 'Matrículas' ?></th>
                                <th>Última vez</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                        <?php foreach ($gente as $m): ?>
                            <tr>
                                <td>
                                    <a href="<?= e(url('admin/usuarios/ver.php?id=' . (int) $m['id'])) ?>">
                                        <b><?= e($m['name']) ?></b>
                                    </a>
                                </td>
                                <td class="compacta"><?= e($m['email']) ?></td>
                                <td class="cifra">
                                    <?= $rol === 'teacher' ? (int) $m['cursos'] : (int) $m['matriculas'] ?>
                                </td>
                                <td class="compacta" style="color:var(--texto-tenue)">
                                    <?= $m['last_login_at']
                                        ? e(date('d/m/Y', strtotime((string) $m['last_login_at'])))
                                        : 'nunca' ?>
                                </td>
                                <td class="acciones-celda">
                                    <a class="btn-mini" href="<?= e(url('admin/usuarios/uso.php?id=' . (int) $m['id'])) ?>">📊</a>

                                    <form method="post" style="display:inline"
                                          onsubmit="return confirm('¿Generar una contraseña nueva para <?= e($m['name']) ?>?')">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="clave">
                                        <input type="hidden" name="usuario" value="<?= (int) $m['id'] ?>">
                                        <button class="btn-mini" type="submit">🔑</button>
                                    </form>

                                    <form method="post" style="display:inline"
                                          onsubmit="return confirm('¿Sacar a <?= e($m['name']) ?> del colegio?\n\nPierde la licencia. Su cuenta y su progreso no se borran.')">
                                        <?= campoCsrf() ?>
                                        <input type="hidden" name="accion" value="desvincular">
                                        <input type="hidden" name="usuario" value="<?= (int) $m['id'] ?>">
                                        <button class="btn-mini peligro" type="submit">Sacar</button>
                                    </form>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php endforeach; ?>


<!-- ── Cursos ───────────────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza">
        <h2>Cursos del colegio (<?= count($cursos) ?>)</h2>
        <?php if ($cursos): ?>
            <div class="acciones">
                <a class="btn-mini" href="<?= e(url('admin/cursos/?colegio=' . urlencode((string) $colegio['slug']))) ?>">
                    Verlos en Cursos →
                </a>
            </div>
        <?php endif; ?>
    </div>
    <div class="cuerpo">

        <?php if (!$cursos): ?>
            <p class="sin-datos" style="padding:16px 0">
                Todavía no hay ningún curso. Créalo abajo.
            </p>
        <?php else: ?>
            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr>
                            <th>Curso</th><th>Docente</th>
                            <th class="cifra">Estudiantes</th><th class="cifra">Actividades</th>
                            <th>Entran por</th><th></th>
                        </tr>
                    </thead>
                    <tbody>
                    <?php foreach ($cursos as $c): ?>
                        <tr class="<?= $c['status'] === 'archived' ? 'fila-tenue' : '' ?>">
                            <td>
                                <a href="<?= e(url('escuela/curso.php?id=' . (int) $c['id'])) ?>">
                                    <b><?= e($c['name']) ?></b>
                                </a>
                                <?php if ($c['status'] === 'archived'): ?>
                                    <span class="distintivo gris">archivado</span>
                                <?php endif; ?>
                            </td>
                            <td class="compacta"><?= e($c['docente'] ?? '—') ?></td>
                            <td class="cifra"><?= (int) $c['estudiantes'] ?></td>
                            <td class="cifra"><?= (int) $c['actividades'] ?></td>
                            <td class="compacta">
                                <?php if (usaLista($c) && !empty($c['access_code'])): ?>
                                    <code><?= e($c['access_code']) ?></code>
                                    <span class="distintivo <?= claseAbierta($c) ? 'verde' : 'gris' ?>">
                                        <?= claseAbierta($c) ? 'abierta' : 'cerrada' ?>
                                    </span>
                                <?php else: ?>
                                    <span style="color:var(--texto-tenue)">con su cuenta</span>
                                <?php endif; ?>
                            </td>
                            <td class="compacta">
                                <a class="btn-mini" href="<?= e(url('escuela/curso.php?id=' . (int) $c['id'])) ?>">
                                    Abrir
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>

        <div class="separador" style="margin:22px 0"></div>

        <h3 style="font-size:.95rem;color:var(--oscuro);margin-bottom:10px">Crear un curso</h3>

        <?php if (!$puedenTenerCurso): ?>
            <p class="aviso-suave">
                Antes hace falta al menos un <b>docente</b> en el colegio: un curso siempre
                es de alguien. Dalo de alta arriba.
            </p>
        <?php else: ?>
            <p style="color:var(--texto-tenue);font-size:.88rem;line-height:1.6;margin-bottom:14px">
                Lo normal es que cada docente cree los suyos desde su área. Aquí sirve para
                montar el colegio de una sentada, sin entrar como cada uno.
            </p>

            <form method="post">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="curso">

                <div class="formulario">
                    <div class="campo">
                        <label for="cu-nombre">Nombre del curso</label>
                        <input id="cu-nombre" name="curso_nombre" type="text" required
                               maxlength="160" placeholder="Primero A">
                    </div>

                    <div class="campo">
                        <label for="cu-docente">Docente</label>
                        <select id="cu-docente" name="docente_id" required>
                            <?php foreach ($puedenTenerCurso as $d): ?>
                                <option value="<?= (int) $d['id'] ?>"><?= e($d['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="campo">
                        <label for="cu-grado">Grado</label>
                        <input id="cu-grado" name="curso_grado" type="text" maxlength="40"
                               placeholder="Primero">
                    </div>

                    <div class="campo">
                        <label for="cu-anio">Año</label>
                        <input id="cu-anio" name="curso_anio" type="number" min="2020" max="2100"
                               value="<?= (int) date('Y') ?>">
                    </div>
                </div>

                <div class="pie-formulario" style="margin-top:16px">
                    <button class="btn-mini solido" type="submit">
                        Crear curso y abrirlo
                    </button>
                </div>
            </form>
        <?php endif; ?>
    </div>
</div>


<!-- ── Datos y licencia ─────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>Datos y licencia</h2></div>
    <div class="cuerpo">
        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="guardar">

            <div class="formulario">
                <div class="campo">
                    <label for="e-nombre">Nombre</label>
                    <input id="e-nombre" name="nombre" type="text" maxlength="160"
                           value="<?= e($colegio['name']) ?>">
                </div>

                <div class="campo">
                    <label for="e-city">Ciudad</label>
                    <input id="e-city" name="city" type="text" maxlength="120"
                           value="<?= e($colegio['city'] ?? '') ?>">
                </div>

                <div class="campo">
                    <label for="e-plan">Plan de la licencia</label>
                    <select id="e-plan" name="plan_id">
                        <option value="">Sin licencia</option>
                        <?php foreach ($planes as $p): ?>
                            <option value="<?= (int) $p['id'] ?>"
                                <?= (int) $colegio['plan_id'] === (int) $p['id'] ? 'selected' : '' ?>>
                                <?= e($p['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="campo">
                    <label for="e-hasta">Vence el</label>
                    <input id="e-hasta" name="licencia_hasta" type="date"
                           value="<?= e($colegio['licencia_hasta']
                                        ? date('Y-m-d', strtotime((string) $colegio['licencia_hasta']))
                                        : '') ?>">
                    <p class="ayuda">Vacío = indefinida.</p>
                </div>

                <div class="campo">
                    <label for="e-dominio">Dominio de los usuarios</label>
                    <input id="e-dominio" name="dominio" type="text" maxlength="120"
                           value="<?= e($colegio['dominio'] ?? '') ?>">
                </div>

                <div class="campo">
                    <label for="e-status">Estado</label>
                    <select id="e-status" name="status">
                        <option value="active"   <?= $colegio['status'] === 'active' ? 'selected' : '' ?>>Activo</option>
                        <option value="inactive" <?= $colegio['status'] !== 'active' ? 'selected' : '' ?>>Inactivo</option>
                    </select>
                    <p class="ayuda">Inactivo retira la licencia a todos.</p>
                </div>

                <div class="campo ancho">
                    <label for="e-notas">Nota interna</label>
                    <input id="e-notas" name="notas" type="text" maxlength="400"
                           value="<?= e($colegio['notas'] ?? '') ?>">
                </div>
            </div>

            <label class="casilla" style="margin-top:14px">
                <input type="checkbox" name="es_prueba" value="1"
                       <?= (int) $colegio['es_prueba'] === 1 ? 'checked' : '' ?>>
                <span><b>Es un colegio de prueba</b></span>
            </label>

            <div class="pie-formulario" style="margin-top:18px">
                <button class="btn-mini solido" type="submit">Guardar y aplicar a todos</button>
            </div>
        </form>
    </div>
</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
