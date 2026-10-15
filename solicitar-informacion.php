<?php
/**
 * solicitar-informacion.php — Formulario público para pedir información
 *
 * Al que llega del botón «Solicitar información» (plan Escuela) o de un
 * anuncio. Ver includes/solicitudes.php: se guarda, se avisa por correo
 * a ventas y se le cuenta a Meta que llegó un interesado.
 *
 * Sin pregunta de suma, a diferencia del registro: aquí cada campo de más
 * es un interesado menos, y el riesgo de un robot es un correo basura, no
 * una cuenta falsa. Lo frenan la casilla trampa, el tiempo mínimo de
 * llenado y un tope de cinco envíos por hora y conexión.
 *
 * `?perfil=colegio` abre el formulario con ese perfil ya marcado.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';
require_once RUTA_INCLUDES . '/solicitudes.php';

$errores = [];
$enviada = null;

// Lo que se ve en el formulario: lo enviado, o lo que trae el enlace.
$v = [
    'perfil'   => isset(SOLICITUD_PERFILES[(string) get('perfil')]) ? (string) get('perfil') : '',
    'niveles'  => [], 'areas' => [], 'necesidades' => [],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    $robot = motivoRobot();
    $r     = validarSolicitud($_POST);
    $v     = array_merge($_POST, ['niveles' => $r['datos']['grades'], 'areas' => $r['datos']['subjects'],
                                  'necesidades' => $r['datos']['needs']]);
    $errores = $r['errores'];

    if ($robot !== null) {
        error_log('[solicitud] envío bloqueado: ' . $robot);
        $errores = ['general' => 'No pudimos procesar el formulario. Recarga la página e inténtalo otra vez.'];
    } elseif (solicitudesFrenadas()) {
        $errores = ['general' => 'Ya recibimos varias solicitudes desde esta conexión. Escríbenos más tarde o al correo ' . solicitudesCorreo() . '.'];
    }

    if (!$errores) {
        $enviada = registrarSolicitud($r['datos']);
        if (!$enviada['correo']['ok']) {
            error_log('[solicitud] #' . $enviada['id'] . ' guardada, pero el aviso por correo falló: ' . $enviada['correo']['error']);
        }
    }
}

$marcada  = static fn(string $grupo, string $clave) => in_array($clave, (array) ($v[$grupo] ?? []), true) ? 'checked' : '';
$valor    = static fn(string $k) => e((string) ($v[$k] ?? ''));
$conError = static fn(string $k) => isset($errores[$k]) ? 'campo-error' : '';

$titulo = 'Solicitar información';
require RUTA_INCLUDES . '/cabecera-simple.php';
?>
<style>
    /* Un formulario más largo que el de registro: la caja se ensancha. */
    .caja { max-width: 640px; }
    .solicitud fieldset { border: none; padding: 0; margin: 0 0 18px; }
    .solicitud legend { font-weight: 700; color: var(--oscuro); font-size: .9rem; margin-bottom: 8px; padding: 0; }
    .solicitud select, .solicitud textarea {
        width: 100%; padding: 12px 14px; border: 2px solid var(--borde); border-radius: 12px;
        font: inherit; background: #f8fafc; margin-bottom: 14px;
    }
    .solicitud textarea { min-height: 96px; resize: vertical; }
    .opciones { display: flex; flex-wrap: wrap; gap: 8px; }
    .opciones label {
        display: inline-flex; align-items: center; gap: 8px; margin: 0; padding: 9px 12px;
        border: 2px solid var(--borde); border-radius: 12px; font-weight: 500; cursor: pointer;
        background: #fff; font-size: .86rem;
    }
    .opciones label:has(input:checked) { border-color: var(--azul); background: rgba(41,182,246,.08); }
    .opciones input { width: auto; margin: 0; }
    .perfiles { display: grid; gap: 8px; }
    .perfiles label { width: 100%; }
    .autorizo { display: flex; gap: 10px; align-items: flex-start; font-weight: 500; font-size: .84rem; }
    .autorizo input { width: auto; margin-top: 3px; }
    .gracias { text-align: center; }
    .gracias .icono { font-size: 3rem; }
    /* Sin JavaScript se ve todo; con él, solo lo del perfil elegido. */
    .js .solo-para[hidden] { display: none; }
    @media (max-width: 520px) { .solicitud .fila { flex-direction: column; gap: 0; } }
</style>

<?php if ($enviada): ?>

    <div class="gracias">
        <div class="icono" aria-hidden="true">📬</div>
        <h1>¡Recibimos tu solicitud!</h1>
        <p class="sub">
            Gracias, <?= e($r['datos']['name']) ?>. Te contactaremos en máximo un día hábil
            <?= $r['datos']['contact_pref'] ? 'por ' . e(mb_strtolower(SOLICITUD_CONTACTO[$r['datos']['contact_pref']])) : '' ?>.
        </p>
        <p class="sub">Mientras tanto, puedes explorar las actividades gratis.</p>
        <a class="btn-google" href="<?= e(url('actividades/')) ?>">Ver las actividades</a>
    </div>

<?php else: ?>

    <h1>Solicitar información</h1>
    <p class="sub">
        Cuéntanos qué necesitas y te enviamos una propuesta a la medida: para tu hijo,
        para tu clase o para todo tu colegio.
    </p>

    <?php if ($errores): ?>
        <div class="aviso mal">
            <strong>Revisa estos campos:</strong>
            <ul><?php foreach ($errores as $er): ?><li><?= e($er) ?></li><?php endforeach; ?></ul>
        </div>
    <?php endif; ?>

    <form method="post" class="solicitud" novalidate>
        <?= campoCsrf() ?>
        <?= camposAntiRobot() ?>

        <fieldset>
            <legend>¿Quién eres?</legend>
            <div class="opciones perfiles">
                <?php foreach (SOLICITUD_PERFILES as $k => $txt): ?>
                    <label><input type="radio" name="perfil" value="<?= e($k) ?>" required
                        <?= ($v['perfil'] ?? '') === $k ? 'checked' : '' ?>> <?= e($txt) ?></label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <label for="nombre">Nombre completo</label>
        <input id="nombre" name="nombre" class="<?= $conError('nombre') ?>" value="<?= $valor('nombre') ?>" required maxlength="120" autocomplete="name">

        <div class="fila">
            <div>
                <label for="correo">Correo</label>
                <input id="correo" name="correo" type="email" class="<?= $conError('correo') ?>" value="<?= $valor('correo') ?>" required maxlength="190" autocomplete="email">
            </div>
            <div>
                <label for="telefono">WhatsApp o teléfono</label>
                <input id="telefono" name="telefono" type="tel" class="<?= $conError('telefono') ?>" value="<?= $valor('telefono') ?>" required maxlength="40" autocomplete="tel" placeholder="300 123 4567">
            </div>
        </div>

        <label for="ciudad">Ciudad y país</label>
        <input id="ciudad" name="ciudad" class="<?= $conError('ciudad') ?>" value="<?= $valor('ciudad') ?>" required maxlength="120" placeholder="Medellín, Colombia">

        <div class="solo-para" data-perfiles="docente colegio otro">
            <div class="fila">
                <div>
                    <label for="institucion">Institución</label>
                    <input id="institucion" name="institucion" class="<?= $conError('institucion') ?>" value="<?= $valor('institucion') ?>" maxlength="160" autocomplete="organization">
                </div>
                <div>
                    <label for="cargo">Cargo</label>
                    <input id="cargo" name="cargo" value="<?= $valor('cargo') ?>" maxlength="120" placeholder="Rector, coordinadora, docente…">
                </div>
            </div>
        </div>

        <div class="solo-para" data-perfiles="colegio otro">
            <div class="fila">
                <div>
                    <label for="estudiantes">Estudiantes (aprox.)</label>
                    <select id="estudiantes" name="estudiantes" class="<?= $conError('estudiantes') ?>">
                        <option value="">Elige…</option>
                        <?php foreach (SOLICITUD_ESTUDIANTES as $o): ?>
                            <option <?= ($v['estudiantes'] ?? '') === $o ? 'selected' : '' ?>><?= e($o) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label for="docentes">Docentes (aprox.)</label>
                    <select id="docentes" name="docentes">
                        <option value="">Elige…</option>
                        <?php foreach (SOLICITUD_DOCENTES as $o): ?>
                            <option <?= ($v['docentes'] ?? '') === $o ? 'selected' : '' ?>><?= e($o) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <fieldset>
            <legend>¿Para qué niveles?</legend>
            <div class="opciones">
                <?php foreach (SOLICITUD_NIVELES as $k => $txt): ?>
                    <label><input type="checkbox" name="niveles[]" value="<?= e($k) ?>" <?= $marcada('niveles', $k) ?>> <?= e($txt) ?></label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <fieldset>
            <legend>¿Qué áreas te interesan?</legend>
            <div class="opciones">
                <?php foreach (SOLICITUD_AREAS as $k => $txt): ?>
                    <label><input type="checkbox" name="areas[]" value="<?= e($k) ?>" <?= $marcada('areas', $k) ?>> <?= e($txt) ?></label>
                <?php endforeach; ?>
            </div>
        </fieldset>

        <?php foreach (SOLICITUD_NECESIDADES as $perfil => $lista): ?>
            <fieldset class="solo-para" data-perfiles="<?= e($perfil) ?>">
                <legend>¿Qué necesitas?</legend>
                <div class="opciones">
                    <?php foreach ($lista as $k => $txt): ?>
                        <label><input type="checkbox" name="necesidades[]" value="<?= e($k) ?>" <?= $marcada('necesidades', $k) ?>> <?= e($txt) ?></label>
                    <?php endforeach; ?>
                </div>
            </fieldset>
        <?php endforeach; ?>

        <div class="fila">
            <div>
                <label for="contacto">¿Cómo prefieres que te contactemos?</label>
                <select id="contacto" name="contacto">
                    <?php foreach (SOLICITUD_CONTACTO as $k => $txt): ?>
                        <option value="<?= e($k) ?>" <?= ($v['contacto'] ?? '') === $k ? 'selected' : '' ?>><?= e($txt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div>
                <label for="origen">¿Cómo nos conociste?</label>
                <select id="origen" name="origen">
                    <option value="">Elige…</option>
                    <?php foreach (SOLICITUD_ORIGEN as $k => $txt): ?>
                        <option value="<?= e($k) ?>" <?= ($v['origen'] ?? '') === $k ? 'selected' : '' ?>><?= e($txt) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div>

        <label for="mensaje">¿Algo más que debamos saber? <span style="font-weight:400">(opcional)</span></label>
        <textarea id="mensaje" name="mensaje" maxlength="2000" placeholder="Por ejemplo: queremos empezar en febrero con preescolar y primero."><?= $valor('mensaje') ?></textarea>

        <label class="autorizo <?= $conError('autorizo') ?>">
            <input type="checkbox" name="autorizo" value="1" required <?= !empty($v['autorizo']) ? 'checked' : '' ?>>
            <span>Autorizo a Actividades en Línea a usar estos datos para responder mi solicitud
            y enviarme información de la plataforma, según la Ley 1581 de 2012. Puedo pedir
            que los borren cuando quiera.</span>
        </label>

        <button type="submit">Enviar solicitud</button>
    </form>

    <script>
    /*
     * Muestra solo las preguntas del perfil elegido. Sin JavaScript se ven
     * todas y el servidor descarta las que no tocan.
     */
    (function () {
        document.documentElement.classList.add('js');
        var bloques = document.querySelectorAll('.solo-para');
        function aplicar() {
            var marcado = document.querySelector('input[name="perfil"]:checked');
            var perfil = marcado ? marcado.value : '';
            bloques.forEach(function (b) {
                var ver = perfil && b.getAttribute('data-perfiles').split(' ').indexOf(perfil) !== -1;
                b.hidden = !ver;
            });
        }
        document.querySelectorAll('input[name="perfil"]').forEach(function (r) { r.addEventListener('change', aplicar); });
        aplicar();
    })();
    </script>

<?php endif; ?>

<?php require RUTA_INCLUDES . '/pie-simple.php'; ?>
