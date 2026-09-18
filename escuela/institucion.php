<?php
/**
 * escuela/institucion.php — El panel del administrador de institución
 *
 * La vista de conjunto de UNA organización, para quien la coordina.
 *
 * Vive dentro de `escuela/` a propósito: desde aquí se abren los cursos
 * con las pantallas que ya existen —estudiantes, actividades, progreso,
 * acceso, hoja de credenciales—, que son exactamente las que hacen falta.
 * Montar copias «de coordinación» significaría mantenerlas dos veces y
 * que se separen en cuanto se toque una. Lo que abre esa puerta es
 * `exigirCursoPropio()`, no una excepción de esta página.
 *
 * Lo único propio de aquí es la mirada de arriba: cuántos docentes hay,
 * qué cursos existen, quién no está trabajando y qué niños se quedaron
 * sin clase.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE ESTA PÁGINA NO ENSEÑA
 * ─────────────────────────────────────────────────────────────────────
 *
 * El progreso personal de ningún niño. Todas las cifras salen de cursos
 * de esta institución, así que lo que un menor juega en su casa no
 * aparece en ninguna. Un colegio responde de lo que manda.
 *
 * Y la licencia no se toca desde aquí: el plan, la fecha y el estado son
 * la relación comercial con la plataforma, y se ven pero no se editan.
 */

require_once dirname(__DIR__) . '/config/config.php';

$colegio   = exigirInstitucion();
$colegioId = (int) $colegio['id'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $destino = accionDeInstitucion($colegio, (string) post('accion'), 'escuela/institucion.php');

    redirigir($destino ?? 'escuela/institucion.php');
}

// Credenciales recién generadas: se enseñan una vez y no vuelven.
$credenciales = $_SESSION['colegio_creadas'] ?? [];
unset($_SESSION['colegio_creadas']);

$resumen   = resumenDeInstitucion($colegioId);
$docentes  = docentesDeInstitucion($colegioId);
$cursos    = cursosDeInstitucionConAvance($colegioId);
$sinCurso  = estudiantesSinCurso($colegioId);
$viva      = licenciaVigente($colegio);

// Buscador de estudiantes. Vacío enseña la lista entera, que en un
// colegio de prueba son treinta y en uno de verdad son cuatrocientos.
$buscar = trim(get('buscar'));
$alumnos = estudiantesDeInstitucion($colegioId, $buscar);

$titulo      = $colegio['name'];
$escuelaZona = 'institucion';
require __DIR__ . '/includes/cabecera-escuela.php';
?>

<header class="cabeza">
    <div>
        <h1><?= e($colegio['name']) ?></h1>
        <p class="bajada">
            Licencia <?= e(textoLicencia($colegio)) ?><?php if ($colegio['plan_nombre']): ?>
                · plan <?= e($colegio['plan_nombre']) ?><?php endif; ?>
        </p>
    </div>
    <a class="btn btn-secundario btn-chico" href="<?= e(urlDeColegio($colegio)) ?>"
       target="_blank" rel="noopener">Ver su puerta ↗</a>
</header>

<?php if (!$viva): ?>
    <section class="bloque-panel aviso-atencion">
        <h2>La licencia no está vigente</h2>
        <p class="nota-panel">
            Mientras siga así, los docentes y los estudiantes de la institución no tienen
            acceso completo al catálogo. Esto lo resuelve la plataforma, no la institución:
            escríbenos.
        </p>
    </section>
<?php endif; ?>


<?php if ($credenciales): ?>
    <section class="bloque-panel credenciales-nuevas">
        <h2>🔑 Anota estas contraseñas ahora</h2>
        <p class="nota-panel">
            <strong>No se pueden volver a ver.</strong> En la base solo queda cifrada, que es
            como debe ser. Si se pierden, se restablecen desde las listas de abajo.
        </p>

        <table class="tabla-panel">
            <thead><tr><th>Nombre</th><th>Usuario</th><th>Contraseña</th><th>Entra como</th></tr></thead>
            <tbody>
            <?php foreach ($credenciales as $c): ?>
                <tr>
                    <td><strong><?= e($c['nombre']) ?></strong></td>
                    <td class="mono"><?= e($c['correo']) ?></td>
                    <td class="mono clave-nueva"><?= e($c['clave']) ?></td>
                    <td class="tenue">
                        <?= e(['teacher' => 'docente', 'school_admin' => 'coordinación']
                              [$c['rol']] ?? 'estudiante') ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <button class="btn btn-secundario btn-chico" type="button" onclick="window.print()">
            🖨️ Imprimir
        </button>
    </section>
<?php endif; ?>


<div class="tira-cifras">
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['docentes'] ?></span>
        <span class="cifra-eti">Docentes</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['estudiantes'] ?></span>
        <span class="cifra-eti">Estudiantes</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['cursos'] ?></span>
        <span class="cifra-eti">Cursos activos</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['activos_semana'] ?></span>
        <span class="cifra-eti">Trabajaron esta semana</span>
    </div>
    <div class="cifra">
        <span class="cifra-num"><?= (int) $resumen['estaciones_hechas'] ?></span>
        <span class="cifra-eti">Estaciones de clase</span>
    </div>
</div>

<p class="nota-panel">
    Estas cifras cuentan <strong>solo el trabajo de clase</strong>. Lo que un niño juega por
    su cuenta en casa es suyo y no aparece aquí.
</p>


<!-- ── Cursos ───────────────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Cursos de la institución</h2>

    <?php if (!$cursos): ?>
        <p class="nota-panel">
            Todavía no hay ninguno. Da de alta a un docente y crea su primer curso abajo.
        </p>
    <?php else: ?>
        <table class="tabla-panel">
            <thead>
                <tr>
                    <th>Curso</th><th>Docente</th>
                    <th class="num">Niños</th><th class="num">Actividades</th>
                    <th>Avance</th>
                    <th class="num">Activos</th>
                    <th>Última vez</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($cursos as $c): ?>
                <?php
                $posibles = (int) $c['posibles'];
                $hechas   = min((int) $c['hechas'], $posibles);
                $pc       = $posibles > 0 ? (int) round($hechas * 100 / $posibles) : 0;

                /*
                 * «Dormido» no es un adorno: es la única cifra por la que
                 * un coordinador puede actuar. Un curso con treinta niños
                 * y cero movimiento en una semana es una clase que no
                 * arrancó, y eso no se ve en ninguna otra pantalla.
                 */
                $dormido = (int) $c['estudiantes'] > 0
                        && (int) $c['actividades'] > 0
                        && (int) $c['activos'] === 0
                        && $c['status'] !== 'archived';
                ?>
                <tr class="<?= $c['status'] === 'archived' ? 'fila-tenue' : '' ?>">
                    <td>
                        <a href="<?= e(url('escuela/curso.php?id=' . (int) $c['id'])) ?>">
                            <strong><?= e($c['name']) ?></strong>
                        </a>
                        <?php if ($c['status'] === 'archived'): ?>
                            <span class="etiqueta-archivado">Archivado</span>
                        <?php elseif ($dormido): ?>
                            <span class="etiqueta-archivado">sin movimiento</span>
                        <?php endif; ?>
                        <?php if (aulaInstalada() && usaLista($c) && !empty($c['access_code'])): ?>
                            <br><span class="tenue mono"><?= e($c['access_code']) ?></span>
                            <span class="tenue"><?= claseAbierta($c) ? '· abierta' : '· cerrada' ?></span>
                        <?php endif; ?>
                    </td>
                    <td class="tenue"><?= e($c['docente'] ?? '—') ?></td>
                    <td class="num"><?= (int) $c['estudiantes'] ?></td>
                    <td class="num"><?= (int) $c['actividades'] ?></td>
                    <td>
                        <?php if ($posibles === 0): ?>
                            <span class="tenue">sin empezar</span>
                        <?php else: ?>
                            <div class="barra-avance" style="max-width:120px">
                                <span style="width:<?= $pc ?>%"></span>
                            </div>
                            <span class="tenue"><?= $pc ?>%</span>
                        <?php endif; ?>
                    </td>
                    <td class="num"><?= (int) $c['activos'] ?></td>
                    <td class="tenue">
                        <?= $c['ultimo'] ? e(date('d/m/Y', strtotime((string) $c['ultimo']))) : '—' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>

        <p class="nota-panel">
            «Activos» son los niños que trabajaron algo <strong>de ese curso</strong> en los
            últimos siete días. Lo que jueguen por su cuenta en casa no cuenta aquí.
        </p>
    <?php endif; ?>
</section>


<!-- ── Todos los estudiantes ────────────────────────────────────────── -->

<?php
/*
 * El listado de la institución: nombre, en qué clases está y cuánto
 * lleva. Nada más — ni correo, ni edad, ni acudiente.
 *
 * Un coordinador necesita encontrar a un niño («la mamá de Ana llamó, ¿en
 * qué curso está?») y saber si está trabajando. Para cualquier otra cosa
 * entra en su curso, que es donde ese dato tiene contexto y donde el
 * docente responde por él.
 */
?>
<section class="bloque-panel">
    <h2>Estudiantes (<?= count($alumnos) ?><?= $buscar !== '' ? ' encontrados' : '' ?>)</h2>

    <form method="get" class="form-linea">
        <label>
            Buscar por nombre
            <input type="search" name="buscar" value="<?= e($buscar) ?>"
                   placeholder="Ana…">
        </label>
        <button class="btn btn-secundario" type="submit">Buscar</button>
        <?php if ($buscar !== ''): ?>
            <a class="btn btn-secundario btn-chico"
               href="<?= e(url('escuela/institucion.php')) ?>">Ver todos</a>
        <?php endif; ?>
    </form>

    <?php if (!$alumnos): ?>
        <p class="nota-panel">
            <?= $buscar !== ''
                ? 'Ningún estudiante con ese nombre.'
                : 'Todavía no hay estudiantes en la institución.' ?>
        </p>
    <?php else: ?>
        <table class="tabla-panel">
            <thead>
                <tr>
                    <th>Estudiante</th><th>En</th>
                    <th class="num">Estaciones</th><th>Última vez</th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($alumnos as $a): ?>
                <tr>
                    <td><strong><?= e($a['name']) ?></strong></td>
                    <td class="tenue">
                        <?= $a['clases'] ? e($a['clases']) : '— sin curso —' ?>
                    </td>
                    <td class="num"><?= (int) $a['hechas'] ?></td>
                    <td class="tenue">
                        <?= $a['ultimo'] ? e(date('d/m/Y', strtotime((string) $a['ultimo']))) : 'nunca' ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>


<!-- ── Crear un curso ───────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Crear un curso</h2>

    <?php if (!$docentes): ?>
        <p class="nota-panel">
            Antes hace falta al menos un <strong>docente</strong>: un curso siempre es de
            alguien. Dalo de alta abajo.
        </p>
    <?php else: ?>
        <p class="nota-panel">
            Lo normal es que cada docente cree los suyos. Esto sirve para montar la
            institución de una sentada, sin entrar como cada uno.
        </p>

        <form method="post" class="form-linea">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="curso">

            <label>
                Nombre
                <input type="text" name="curso_nombre" required maxlength="160"
                       placeholder="Primero A">
            </label>

            <label>
                Docente
                <select name="docente_id" required>
                    <?php foreach ($docentes as $d): ?>
                        <option value="<?= (int) $d['id'] ?>"><?= e($d['name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </label>

            <label>
                Grado
                <input type="text" name="curso_grado" maxlength="40" placeholder="Primero">
            </label>

            <label>
                Año
                <input type="number" name="curso_anio" min="2020" max="2100"
                       value="<?= (int) date('Y') ?>">
            </label>

            <button class="btn btn-principal" type="submit">Crear curso y abrirlo</button>
        </form>
    <?php endif; ?>
</section>


<!-- ── Dar de alta gente ────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Dar de alta gente de la institución</h2>
    <p class="nota-panel">
        Un nombre por línea. Se crean las cuentas <strong>con la licencia de la
        institución</strong> y te damos las contraseñas para repartir.
        A los <strong>docentes</strong> conviene ponerles su correo real detrás de una coma
        —lo necesitan para recuperar su contraseña ellos solos—; a los
        <strong>estudiantes</strong> se les genera un usuario que no recibe correo.
    </p>

    <form method="post">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="crear">

        <div class="form-linea" style="margin-bottom:12px">
            <label>
                Se dan de alta como
                <select name="rol">
                    <option value="student">🧒 Estudiantes</option>
                    <option value="teacher">🎓 Docentes</option>
                </select>
            </label>
        </div>

        <?php
        /*
         * Se dice por qué falta la tercera opción. Un límite sin explicar
         * parece un descuido, y quien lo toma por un descuido escribe
         * pidiendo que lo arreglen.
         */
        ?>
        <p class="nota-panel">
            Nombrar a <strong>otra coordinación</strong> no se hace desde aquí: ese rol
            gobierna a todos los docentes y a todos los niños del colegio, así que lo
            reparte —y lo retira— la plataforma. Escríbenos y lo hacemos.
        </p>

        <textarea name="nombres" rows="7" class="area-nombres"
                  placeholder="Ana Pérez&#10;Luis Gómez&#10;Marta Ruiz, marta.ruiz@colegio.edu.co"></textarea>

        <div class="pie-bloque">
            <button class="btn btn-principal" type="submit">Crear y dar licencia</button>
            <span class="tenue">Máximo <?= MAX_CUENTAS_INSTITUCION ?> por tanda.</span>
        </div>
    </form>

    <h2 style="margin-top:26px">O vincular una cuenta que ya existe</h2>
    <p class="nota-panel">
        Para quien ya se registró por su cuenta. Hace falta el correo exacto.
    </p>

    <form method="post" class="form-linea">
        <?= campoCsrf() ?>
        <input type="hidden" name="accion" value="vincular">

        <label>
            Correo
            <input type="email" name="correo" required maxlength="190"
                   placeholder="nombre@correo.com">
        </label>

        <label>
            Como
            <select name="rol">
                <option value="teacher">Docente</option>
                <option value="student">Estudiante</option>
            </select>
        </label>

        <button class="btn btn-secundario" type="submit">Vincular con licencia</button>
    </form>
</section>


<!-- ── Docentes ─────────────────────────────────────────────────────── -->

<section class="bloque-panel">
    <h2>Docentes (<?= count($docentes) ?>)</h2>

    <?php if (!$docentes): ?>
        <p class="nota-panel">Todavía no hay ninguno.</p>
    <?php else: ?>
        <table class="tabla-panel">
            <thead>
                <tr>
                    <th>Nombre</th><th>Usuario</th>
                    <th class="num">Cursos</th><th class="num">Estudiantes</th>
                    <th>Última vez</th><th></th>
                </tr>
            </thead>
            <tbody>
            <?php foreach ($docentes as $d): ?>
                <?php $esCoordinacion = $d['rol_en_colegio'] === 'school_admin'; ?>
                <tr>
                    <td>
                        <strong><?= e($d['name']) ?></strong>
                        <?php if ($esCoordinacion): ?>
                            <span class="etiqueta-archivado">coordinación</span>
                        <?php endif; ?>
                    </td>
                    <td class="tenue mono"><?= e($d['email']) ?></td>
                    <td class="num"><?= (int) $d['cursos'] ?></td>
                    <td class="num"><?= (int) $d['estudiantes'] ?></td>
                    <td class="tenue">
                        <?= $d['last_login_at']
                            ? e(date('d/m/Y', strtotime((string) $d['last_login_at'])))
                            : 'nunca' ?>
                    </td>
                    <td class="acciones-fila">
                        <?php
                        /*
                         * Sobre la coordinación no se actúa desde aquí. El
                         * rol lo reparte la plataforma, así que también es
                         * la plataforma quien lo retira: si no, dos
                         * coordinadores podrían echarse el uno al otro o
                         * generarse contraseñas para entrar en la cuenta
                         * del compañero.
                         */
                        ?>
                        <?php if ($esCoordinacion): ?>
                            <span class="tenue">—</span>
                        <?php else: ?>
                            <form method="post"
                                  onsubmit="return confirm('¿Generar una contraseña nueva para <?= e($d['name']) ?>?\n\nLa anterior dejará de servir.')">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="clave">
                                <input type="hidden" name="usuario" value="<?= (int) $d['id'] ?>">
                                <button class="btn btn-secundario btn-chico" type="submit"
                                        title="Generar una contraseña nueva">🔑</button>
                            </form>

                            <form method="post"
                                  onsubmit="return confirm('¿Sacar a <?= e($d['name']) ?> de la institución?\n\nPierde la licencia. Su cuenta, sus cursos y el progreso no se borran.')">
                                <?= campoCsrf() ?>
                                <input type="hidden" name="accion" value="desvincular">
                                <input type="hidden" name="usuario" value="<?= (int) $d['id'] ?>">
                                <button class="btn btn-secundario btn-chico" type="submit">Sacar</button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</section>


<!-- ── Estudiantes que se quedaron fuera ────────────────────────────── -->

<?php
/*
 * La única lista de niños de esta página, y enseña solo el nombre.
 *
 * No es un directorio: responde a «di de alta treinta y solo veintiocho
 * están en clase, ¿quiénes faltan?», que sin esto se responde comparando
 * dos listas a mano. Para ver a un estudiante concreto hay que entrar en
 * su curso, que es donde tiene sentido mirarlo.
 */
?>
<?php if ($sinCurso): ?>
    <section class="bloque-panel">
        <h2>Sin curso todavía (<?= count($sinCurso) ?>)</h2>
        <p class="nota-panel">
            Tienen cuenta y licencia, pero nadie los ha matriculado. Hasta que estén en un
            curso no reciben ninguna actividad. Los matricula su docente desde
            <strong>Estudiantes</strong>, dentro del curso.
        </p>

        <ul class="lista-ausentes">
            <?php foreach ($sinCurso as $s): ?>
                <li><?= e($s['name']) ?></li>
            <?php endforeach; ?>
        </ul>
    </section>
<?php endif; ?>

<?php require __DIR__ . '/includes/pie-escuela.php'; ?>
