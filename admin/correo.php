<?php
/**
 * admin/correo.php — Correo saliente
 *
 * Sin esto, «he olvidado mi contraseña» no puede enviar nada. Es la única
 * pieza de la que depende, así que la pantalla está montada alrededor de
 * una sola pregunta: **¿sale un correo de verdad?**
 *
 * De ahí el botón de prueba. Un formulario de configuración que se guarda
 * sin decir si funciona deja a quien lo rellena sin saber si acertó, y el
 * primer aviso llega en forma de usuario que no puede entrar.
 *
 * La contraseña del buzón no se pinta nunca en la página: se envía el
 * campo vacío y solo se guarda lo que se escriba.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$campos = [
    'correo_host'        => ['Servidor SMTP',   'texto',  'Gmail: smtp.gmail.com · Outlook: smtp.office365.com'],
    'correo_puerto'      => ['Puerto',          'numero', '587 con STARTTLS, 465 con SSL directo.'],
    'correo_usuario'     => ['Usuario',         'texto',  'Normalmente la dirección completa del buzón.'],
    'correo_desde'       => ['Enviar desde',    'correo', 'Si lo dejas vacío se usa el usuario. Mandar desde otra dirección suele acabar en spam.'],
    'correo_desde_nombre'=> ['Nombre visible',  'texto',  'Lo que ve el destinatario como remitente.'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    // ── Enviar una prueba ────────────────────────────────────────────
    if (post('accion') === 'probar') {

        $destino = trim(post('destino'));

        if (correoValido($destino) === null) {
            mensaje('error', 'Escribe una dirección válida a la que enviar la prueba.');
        } elseif (!correoConfigurado()) {
            mensaje('error', 'Todavía falta configuración: revisa el método, el servidor '
                           . 'y las credenciales.');
        } else {
            $r = enviarCorreo(
                $destino,
                'Prueba de correo · ' . ajuste('sitio_nombre', 'Actividades en Línea'),
                correoPlantilla(
                    'El correo funciona',
                    '<p>Si estás leyendo esto, el envío automático quedó bien configurado.</p>'
                    . '<p>A partir de ahora, quien olvide su contraseña podrá recuperarla '
                    . 'sin que nadie tenga que hacer nada.</p>'
                )
            );

            $r['ok']
                ? mensaje('ok', 'Correo enviado a ' . e($destino) . '. Revisa la bandeja '
                              . '(y la de no deseado).')
                : mensaje('error', 'No se pudo enviar: ' . $r['error']);
        }

        redirigir('admin/correo.php');
    }

    // ── Guardar ──────────────────────────────────────────────────────
    $metodo = (string) post('correo_metodo');
    guardarAjuste('correo_metodo',
        in_array($metodo, ['smtp', 'php', 'ninguno'], true) ? $metodo : 'ninguno');

    $seguridad = (string) post('correo_seguridad');
    guardarAjuste('correo_seguridad',
        in_array($seguridad, ['tls', 'ssl', 'ninguna'], true) ? $seguridad : 'tls');

    foreach (array_keys($campos) as $clave) {
        if (array_key_exists($clave, $_POST)) {
            guardarAjuste($clave, trim((string) $_POST[$clave]));
        }
    }

    // La contraseña: vacío significa «no la cambies».
    $clave = trim((string) ($_POST['correo_clave'] ?? ''));

    if ($clave !== '') {
        guardarAjuste('correo_clave', $clave);
    }

    mensaje('ok', 'Ajustes de correo guardados. Envía una prueba para comprobarlos.');
    redirigir('admin/correo.php');
}

$metodoHoy = correoMetodo();
$yo        = usuarioActual();

$titulo    = 'Correo saliente';
$panelZona = 'correo';
$panelCss  = ['assets/css/panel-datos.css'];

require __DIR__ . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Correo saliente</h1>
        <p>De esto depende que alguien pueda recuperar su contraseña sin ayuda.</p>
    </div>
</div>

<?php if (!correoConfigurado()): ?>
    <div class="aviso mal">
        <b>El envío de correo no está activo.</b>
        Ahora mismo, quien olvide su contraseña no puede recuperarla solo: hay que
        cambiársela a mano desde <b>Personas → su ficha</b>.
    </div>
<?php else: ?>
    <div class="aviso ok">
        Configurado por <b><?= e($metodoHoy === 'smtp' ? 'SMTP' : 'la función mail() de PHP') ?></b>.
        Envía una prueba de vez en cuando: las credenciales caducan sin avisar.
    </div>
<?php endif; ?>


<form method="post">
    <?= campoCsrf() ?>

    <div class="caja">
        <div class="cabeza"><h2>Cómo se envía</h2></div>
        <div class="cuerpo">

            <div class="opciones-acceso">
                <?php
                $metodos = [
                    'smtp' => ['📮 SMTP (recomendado)',
                               'Se conecta a un buzón de verdad —Gmail, el correo del dominio— '
                             . 'y manda desde ahí. Es lo único que funciona en XAMPP.'],
                    'php'  => ['⚙️ Función mail() de PHP',
                               'Usa el servidor de correo de la máquina. En Windows casi nunca '
                             . 'hay uno, y los mensajes se pierden sin avisar.'],
                    'ninguno' => ['🚫 No enviar correo',
                               'La recuperación de contraseña queda desactivada y se lo dice a '
                             . 'quien lo intente, en vez de fingir que envió algo.'],
                ];
                foreach ($metodos as $slug => [$nombre, $desc]):
                ?>
                    <label class="opcion-acceso">
                        <input type="radio" name="correo_metodo" value="<?= e($slug) ?>"
                               <?= $metodoHoy === $slug ? 'checked' : '' ?>>
                        <span>
                            <b><?= e($nombre) ?></b>
                            <span><?= e($desc) ?></span>
                        </span>
                    </label>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Servidor SMTP</h2></div>
        <div class="cuerpo">

            <div class="formulario">
                <?php foreach ($campos as $clave => [$etiqueta, $tipo, $ayuda]): ?>
                    <div class="campo">
                        <label for="c-<?= e($clave) ?>"><?= e($etiqueta) ?></label>
                        <input id="c-<?= e($clave) ?>" name="<?= e($clave) ?>"
                               type="<?= $tipo === 'correo' ? 'email' : ($tipo === 'numero' ? 'number' : 'text') ?>"
                               <?= $tipo === 'numero' ? 'min="1" max="65535"' : 'maxlength="190"' ?>
                               value="<?= e(ajuste($clave, '')) ?>">
                        <?php if ($ayuda !== ''): ?>
                            <p class="ayuda"><?= e($ayuda) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>

                <div class="campo">
                    <label for="c-seg">Seguridad</label>
                    <select id="c-seg" name="correo_seguridad">
                        <?php
                        $segs = ['tls' => 'STARTTLS (puerto 587)',
                                 'ssl' => 'SSL directo (puerto 465)',
                                 'ninguna' => 'Sin cifrar (no recomendado)'];
                        $segHoy = correoSeguridad();
                        foreach ($segs as $v => $t):
                        ?>
                            <option value="<?= e($v) ?>" <?= $segHoy === $v ? 'selected' : '' ?>>
                                <?= e($t) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <p class="ayuda">
                        Sin cifrar, la contraseña del buzón viaja en claro por la red.
                    </p>
                </div>

                <div class="campo">
                    <label for="c-clave">Contraseña del buzón</label>
                    <?php /* Sin `value`: la credencial no se pinta en el HTML. */ ?>
                    <input id="c-clave" name="correo_clave" type="password"
                           autocomplete="new-password" maxlength="190"
                           placeholder="<?= ajuste('correo_clave', '') !== '' || getenv('CORREO_CLAVE')
                                          ? 'Guardada · déjalo vacío para no cambiarla'
                                          : 'Pega aquí la contraseña' ?>">
                    <p class="ayuda">
                        Con Gmail <b>no sirve</b> la contraseña normal: hay que crear una
                        «contraseña de aplicación» en la cuenta de Google. También se
                        puede poner en la variable de entorno <code>CORREO_CLAVE</code>,
                        que gana sobre esta y no queda en la base de datos.
                    </p>
                </div>
            </div>

            <div class="pie-formulario" style="margin-top:20px">
                <button class="btn-mini solido" type="submit">Guardar</button>
            </div>
        </div>
    </div>
</form>


<div class="caja">
    <div class="cabeza"><h2>Enviar una prueba</h2></div>
    <div class="cuerpo">

        <p style="color:var(--texto);font-size:.9rem;line-height:1.6;margin-bottom:15px">
            Es la única forma de saber si funciona. Guarda primero los ajustes y luego
            mándate un correo a ti.
        </p>

        <form method="post" class="formulario">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="probar">

            <div class="campo">
                <label for="destino">Enviar a</label>
                <input id="destino" name="destino" type="email" required maxlength="190"
                       value="<?= e($yo['email'] ?? '') ?>">
            </div>

            <div class="pie-formulario" style="margin-top:16px">
                <button class="btn-mini" type="submit" <?= correoConfigurado() ? '' : 'disabled' ?>>
                    📨 Enviar prueba
                </button>
            </div>
        </form>
    </div>
</div>

<?php require __DIR__ . '/includes/pie-admin.php'; ?>
