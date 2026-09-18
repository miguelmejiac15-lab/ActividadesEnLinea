<?php
/**
 * admin/pagos/ajustes.php — Cómo se cobra
 *
 * Los datos de la cuenta de recaudo, las credenciales de la pasarela y
 * qué formas de pago se le ofrecen al cliente. Viven en `settings` y no
 * en un archivo de configuración porque los cambia quien administra el
 * negocio, no quien programa: si mañana cambia el número de cuenta,
 * nadie debería tener que abrir un .php.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS DOS FORMAS DE COBRAR NO SE EXCLUYEN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Antes había un único ajuste, `pagos_modo`, que obligaba a elegir entre
 * transferencia y pasarela. Ahora son dos interruptores independientes,
 * porque en Colombia mucha familia prefiere transferir aunque exista el
 * botón de tarjeta, y apagar una de las dos pierde clientes en silencio.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS CREDENCIALES NO SE MUESTRAN
 * ─────────────────────────────────────────────────────────────────────
 *
 * El access token de Mercado Pago sirve para cobrar, consultar y
 * reembolsar. Nunca se escribe entero en esta página: se enseñan los
 * primeros y últimos caracteres, lo justo para comprobar de un vistazo
 * que es el que uno cree. Si se pintara en el formulario quedaría en el
 * código fuente de la página, en la caché del navegador y en cualquier
 * captura de pantalla que acabe en un chat de soporte.
 *
 * Por eso los campos se envían vacíos y **solo se guarda lo que se
 * escriba**: dejar uno en blanco conserva el valor que ya había.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if (!pagosInstalados()) {
    mensaje('error', 'Falta ejecutar la migración de pagos.');
    redirigir('admin/pagos/');
}

/*
 * Campos editables. La lista blanca importa: guarda solo estas claves,
 * así que un campo inventado en el formulario no puede crear un ajuste
 * nuevo ni pisar uno del sistema como `exigir_cuenta_para_jugar`.
 */
$campos = [
    'pagos_titular'        => ['Titular de la cuenta',    'texto',  'A nombre de quién está la cuenta de recaudo.'],
    'pagos_documento'      => ['NIT o cédula',            'texto',  'Aparece junto al titular, para que el cliente compruebe a quién paga.'],
    'pagos_banco'          => ['Banco',                   'texto',  ''],
    'pagos_tipo_cuenta'    => ['Tipo de cuenta',          'texto',  'Ahorros o Corriente.'],
    'pagos_cuenta'         => ['Número de cuenta',        'texto',  ''],
    'pagos_nequi'          => ['Nequi o Daviplata',       'texto',  'Opcional. Si lo pones, se ofrece como segunda forma de pago.'],
    'pagos_correo_soporte' => ['Correo para comprobantes','correo', 'A dónde envía el cliente la foto de la transferencia.'],
    'pagos_instrucciones'  => ['Instrucciones',           'largo',  'Se muestran en la pantalla de pago, encima de los datos de la cuenta.'],
    'pagos_dias_gracia'    => ['Días de gracia',          'numero', 'Días de acceso que se regalan al renovar. Cero es lo normal.'],

    /*
     * Va con los datos de recaudo y no con las credenciales porque no es
     * un secreto: es el nombre con el que nos ve el cliente en su banco.
     */
    'mp_descriptor'        => ['Nombre en el extracto bancario', 'texto',
                               'Lo que el cliente ve en su estado de cuenta un mes después. '
                             . 'Máximo 22 caracteres; se guardan en mayúsculas y sin tildes '
                             . 'porque el banco no las imprime bien. Vacío = ACTIVIDADES EN LINEA.'],
];

/** Credenciales. Se tratan aparte porque no se muestran ni se borran por descuido. */
$secretas = [
    'mp_access_token'    => ['Access token', 'Empieza por APP_USR- (producción) o TEST- (pruebas).'],
    'mp_public_key'      => ['Public key',   'La usa el navegador. No es secreta, pero se trata igual.'],
    'mp_webhook_secreto' => ['Clave secreta del webhook',
                             'La genera Mercado Pago al dar de alta la notificación. Sin ella, sus avisos se rechazan.'],
];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    // ── Formas de pago ───────────────────────────────────────────────
    if (post('accion') === 'formas') {

        // Una casilla desmarcada no se envía: si no llega, está apagada.
        guardarAjuste('pagos_manual_activo', empty($_POST['pagos_manual_activo']) ? '0' : '1');

        $pasarela = (string) post('pagos_pasarela');
        guardarAjuste('pagos_pasarela', $pasarela === 'mercadopago' ? 'mercadopago' : '');
        guardarAjuste('mp_sandbox', empty($_POST['mp_sandbox']) ? '0' : '1');

        /*
         * Aviso, no bloqueo: puede ser el orden en que alguien está
         * configurando. Pero decirlo aquí evita el día perdido buscando
         * por qué el botón de pagar no aparece.
         */
        if ($pasarela === 'mercadopago' && mpAccessToken() === '') {
            mensaje('info', 'Elegiste Mercado Pago pero falta el access token. '
                          . 'Hasta que lo pongas, el botón de pagar no se le muestra a nadie.');
        } elseif (empty($_POST['pagos_manual_activo']) && $pasarela === '') {
            mensaje('info', 'Apagaste las dos formas de pago. La transferencia se sigue '
                          . 'ofreciendo igual: quedarse sin ninguna forma de cobrar sería peor.');
        } else {
            mensaje('ok', 'Formas de pago guardadas.');
        }

        redirigir('admin/pagos/ajustes.php');
    }

    // ── Credenciales ─────────────────────────────────────────────────
    if (post('accion') === 'credenciales') {

        $puestas = 0;

        foreach (array_keys($secretas) as $clave) {
            $valor = trim((string) ($_POST[$clave] ?? ''));

            // Vacío = «no lo cambies». Borrar de verdad se hace con el
            // botón de al lado, que pide confirmación.
            if ($valor === '') {
                continue;
            }

            guardarAjuste($clave, $valor);
            $puestas++;
        }

        /*
         * Con credenciales nuevas, lo que sabíamos de la cuenta deja de
         * valer. Una nota que dice «es de prueba» sobre un token que ya
         * no es ese es peor que no tener ninguna: haría creer que se está
         * probando mientras se cobra de verdad.
         */
        if ($puestas > 0) {
            mpOlvidarCuenta();
        }

        mensaje($puestas > 0 ? 'ok' : 'info',
            $puestas > 0
                ? "Credenciales guardadas: $puestas actualizada" . ($puestas === 1 ? '.' : 's.')
                : 'No escribiste ninguna credencial, así que no se cambió nada.');

        redirigir('admin/pagos/ajustes.php');
    }

    /*
     * ── Datos de la integración ──────────────────────────────────────
     *
     * El número de aplicación y el usuario de prueba NO son credenciales:
     * no abren nada y no se pueden usar para cobrar. Pero se pierden con
     * facilidad —viven en un panel de Mercado Pago al que se entra una
     * vez— y sin el usuario de prueba no hay forma de volver a probar un
     * cobro seis meses después.
     *
     * La CONTRASEÑA del usuario de prueba no se guarda aquí a propósito.
     * Esto se ve en pantalla y acaba en capturas.
     */
    if (post('accion') === 'integracion') {
        guardarAjuste('mp_app_id', trim((string) post('mp_app_id')));
        guardarAjuste('mp_usuario_prueba', trim((string) post('mp_usuario_prueba')));

        mensaje('ok', 'Datos de la integración guardados.');
        redirigir('admin/pagos/ajustes.php');
    }

    if (post('accion') === 'borrar_credenciales') {
        foreach (array_keys($secretas) as $clave) {
            guardarAjuste($clave, '');
        }
        guardarAjuste('pagos_pasarela', '');

        mensaje('info', 'Credenciales borradas y pasarela desconectada. '
                      . 'La transferencia manual sigue funcionando.');
        redirigir('admin/pagos/ajustes.php');
    }

    // ── Probar la conexión ───────────────────────────────────────────
    if (post('accion') === 'probar') {

        /*
         * Se pide algo inofensivo y barato solo para ver si el token
         * sirve. Vale más que cualquier comprobación de formato: un token
         * bien escrito pero revocado pasa cualquier expresión regular y
         * falla en el primer cobro real.
         */
        // Además de comprobar, ANOTA de quién son las credenciales. Es la
        // única forma de que la plataforma sepa si está cobrando de
        // verdad, en vez de creérselo por una casilla.
        $r = mpVerificarCuenta();

        if ($r['ok']) {
            mensaje('ok', sprintf(
                'Conexión correcta. Cuenta «%s», país %s. Es una cuenta de %s.',
                (string) $r['apodo'], (string) $r['pais'],
                $r['prueba'] ? 'PRUEBA: no mueve dinero real'
                             : 'PRODUCCIÓN: mueve dinero REAL'
            ));
        } else {
            mensaje('error', 'No se pudo conectar: ' . $r['error']);
        }

        redirigir('admin/pagos/ajustes.php');
    }

    // ── Cuenta de recaudo ────────────────────────────────────────────
    foreach (array_keys($campos) as $clave) {
        if (array_key_exists($clave, $_POST)) {
            guardarAjuste($clave, trim((string) $_POST[$clave]));
        }
    }

    mensaje('ok', 'Ajustes de cobro guardados.');
    redirigir('admin/pagos/ajustes.php');
}

$urlHook   = url('api/webhook-pago.php');
$hookLocal = mpUrlEsLocal($urlHook);

$titulo    = 'Ajustes de cobro';
$panelZona = 'pagos';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Ajustes de cobro</h1>
        <p>Lo que ve el cliente cuando le toca pagar, y por dónde entra el dinero.</p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/pagos/')) ?>">← Volver a cobros</a>
    </div>
</div>

<?php if (!cobroDisponible()): ?>
    <div class="aviso mal">
        <b>Ahora mismo no se puede cobrar.</b> Hace falta o conectar Mercado Pago,
        o completar los datos de la cuenta de recaudo (titular y cuenta o Nequi).
    </div>
<?php endif; ?>


<!-- ── Qué se le ofrece al cliente ─────────────────────────────────── -->

<form method="post">
    <?= campoCsrf() ?>
    <input type="hidden" name="accion" value="formas">

    <div class="caja">
        <div class="cabeza"><h2>Formas de pago</h2></div>
        <div class="cuerpo">

            <p class="aviso-suave" style="margin-bottom:16px">
                Las dos pueden estar encendidas a la vez, y normalmente conviene:
                quien quiere pagar con tarjeta lo hace en un minuto, y quien prefiere
                transferir no se va.
            </p>

            <label class="casilla">
                <input type="checkbox" name="pagos_manual_activo" value="1"
                       <?= ajuste('pagos_manual_activo', '1') !== '0' ? 'checked' : '' ?>>
                <span>
                    <b>🏦 Transferencia con confirmación manual</b>
                    <span>El cliente transfiere citando la referencia y un administrador
                          confirma desde Cobros.</span>
                </span>
            </label>

            <div class="opciones-acceso" style="margin-top:18px">
                <?php $pasarelaElegida = (string) ajuste('pagos_pasarela', ''); ?>

                <label class="opcion-acceso">
                    <input type="radio" name="pagos_pasarela" value=""
                           <?= $pasarelaElegida === '' ? 'checked' : '' ?>>
                    <span>
                        <b>Sin pasarela</b>
                        <span>Solo transferencia. Es lo que hubo hasta ahora.</span>
                    </span>
                </label>

                <label class="opcion-acceso">
                    <input type="radio" name="pagos_pasarela" value="mercadopago"
                           <?= $pasarelaElegida === 'mercadopago' ? 'checked' : '' ?>>
                    <span>
                        <b>💳 Mercado Pago</b>
                        <span>Tarjetas, PSE y saldo de Mercado Pago. El acceso se concede
                              solo cuando su aviso confirma el pago.</span>
                    </span>
                </label>
            </div>

            <label class="casilla" style="margin-top:18px">
                <input type="checkbox" name="mp_sandbox" value="1"
                       <?= ajuste('mp_sandbox', '1') === '1' ? 'checked' : '' ?>>
                <span>
                    <b>Ambiente de pruebas</b>
                    <span>Usa el checkout de pruebas de Mercado Pago, que no mueve dinero
                          real. Apágalo solo cuando vayas a cobrar de verdad.</span>
                </span>
            </label>

            <div class="pie-formulario" style="margin-top:20px">
                <button class="btn-mini solido" type="submit">Guardar formas de pago</button>
            </div>
        </div>
    </div>
</form>


<!-- ── Credenciales de Mercado Pago ────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>Credenciales de Mercado Pago</h2></div>
    <div class="cuerpo">

        <p style="color:var(--texto);font-size:.9rem;line-height:1.65;margin-bottom:16px">
            Se sacan de
            <a href="https://www.mercadopago.com.co/developers/panel" target="_blank" rel="noopener">
                Mercado Pago → Tus integraciones</a>,
            creando una aplicación. Empieza siempre por las credenciales de
            <b>prueba</b>: son las que no mueven dinero.
        </p>

        <?php
        // Estado real, con el valor enmascarado. Enseñar los últimos
        // caracteres permite comprobar cuál está puesta sin revelarla.
        $estadoCred = [
            'Access token'   => mpAccessToken(),
            'Public key'     => mpPublicKey(),
            'Secreto webhook'=> mpWebhookSecreto(),
        ];
        ?>
        <table class="tabla" style="margin-bottom:20px">
            <tbody>
            <?php foreach ($estadoCred as $nombre => $valor): ?>
                <tr>
                    <td style="width:190px"><?= e($nombre) ?></td>
                    <td>
                        <?php if ($valor === ''): ?>
                            <span class="distintivo rojo">sin poner</span>
                        <?php else: ?>
                            <code><?= e(mpEnmascarar($valor)) ?></code>
                            <?php if (getenv(['Access token' => 'MP_ACCESS_TOKEN',
                                              'Public key' => 'MP_PUBLIC_KEY',
                                              'Secreto webhook' => 'MP_WEBHOOK_SECRETO'][$nombre])): ?>
                                <span class="distintivo azul">desde el entorno</span>
                            <?php endif; ?>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; ?>
            <tr>
                <td>Ambiente</td>
                <td>
                    <span class="distintivo <?= mpEsPruebas() ? 'naranja' : 'verde' ?>">
                        <?= mpEsPruebas() ? 'PRUEBAS' : 'PRODUCCIÓN' ?>
                    </span>
                </td>
            </tr>

            <?php
            /*
             * De quién son de verdad estas credenciales. Es el dato que
             * evita el peor error posible: cobrar de verdad creyendo que
             * se está probando. Solo lo sabe Mercado Pago, así que
             * mientras nadie pulse «Probar la conexión» se dice que no se
             * sabe, en vez de dar por buena la casilla de arriba.
             */
            $cuentaMp = mpCuentaConocida();
            ?>
            <tr>
                <td>Cuenta</td>
                <td>
                    <?php if ($cuentaMp === null): ?>
                        <span class="distintivo gris">sin comprobar</span>
                        <span style="color:var(--texto-tenue);font-size:.86rem">
                            Pulsa «Probar la conexión». El prefijo del token no basta:
                            una aplicación creada dentro de un usuario de prueba emite
                            credenciales <code>APP_USR-</code> que no mueven un peso.
                        </span>
                    <?php else: ?>
                        <b><?= e($cuentaMp['apodo']) ?></b>
                        · <?= e($cuentaMp['pais']) ?>
                        <span class="distintivo <?= $cuentaMp['prueba'] ? 'naranja' : 'rojo' ?>">
                            <?= $cuentaMp['prueba'] ? 'cuenta de prueba' : 'CUENTA REAL' ?>
                        </span>
                        <span style="color:var(--texto-tenue);font-size:.82rem">
                            comprobada el <?= e($cuentaMp['visto']) ?>
                        </span>
                    <?php endif; ?>
                </td>
            </tr>
            </tbody>
        </table>

        <?php if (mpRiesgoDeCobroReal()): ?>
            <div class="aviso mal">
                <b>⚠ Vas a cobrar dinero de verdad.</b>
                La casilla dice «ambiente de pruebas», pero Mercado Pago dice que
                <b><?= e($cuentaMp['apodo']) ?></b> es una cuenta <b>real</b>. Cada cobro
                que se haga va a mover dinero. Apaga la casilla si es lo que quieres, o
                cambia a credenciales de prueba.
            </div>
        <?php endif; ?>

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="credenciales">

            <div class="formulario">
                <?php foreach ($secretas as $clave => [$etiqueta, $ayuda]): ?>
                    <div class="campo">
                        <label for="s-<?= e($clave) ?>"><?= e($etiqueta) ?></label>
                        <?php /* Sin `value`: la credencial nunca se pinta en el HTML. */ ?>
                        <input id="s-<?= e($clave) ?>" name="<?= e($clave) ?>" type="text"
                               autocomplete="off" spellcheck="false" maxlength="255"
                               placeholder="<?= ajuste($clave, '') !== '' || getenv('MP_ACCESS_TOKEN')
                                              ? 'Déjalo vacío para no cambiarlo'
                                              : 'Pega aquí el valor' ?>">
                        <p class="ayuda"><?= e($ayuda) ?></p>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pie-formulario" style="margin-top:20px">
                <button class="btn-mini solido" type="submit">Guardar credenciales</button>
            </div>
        </form>

        <div class="separador" style="margin:22px 0"></div>

        <h3 style="font-size:.95rem;color:var(--oscuro);margin-bottom:8px">
            Datos de la integración
        </h3>

        <p style="color:var(--texto-tenue);font-size:.88rem;line-height:1.6;margin-bottom:14px">
            No son credenciales —no abren nada— pero se pierden con facilidad y sin el
            usuario de prueba no hay forma de volver a ensayar un cobro dentro de seis
            meses. <b>La contraseña del usuario de prueba no se guarda aquí</b>: esta
            pantalla acaba en capturas.
        </p>

        <form method="post">
            <?= campoCsrf() ?>
            <input type="hidden" name="accion" value="integracion">

            <div class="formulario">
                <div class="campo">
                    <label for="i-app">Número de la aplicación</label>
                    <input id="i-app" name="mp_app_id" type="text" maxlength="60"
                           value="<?= e(ajuste('mp_app_id', '')) ?>">
                    <?php if (mpAppIdDiscrepa()): ?>
                        <?php /*
                         * No es un dato decorativo mal puesto: la clave secreta
                         * del webhook es de la APLICACIÓN. Si aquí hay apuntada
                         * una y el token es de otra, se copia la clave de la que
                         * dice el panel y los avisos se rechazan con un 401 que
                         * no señala a nada — porque las dos cosas están bien
                         * escritas, solo que no son de la misma aplicación.
                         */ ?>
                        <p class="ayuda" style="color:var(--malo,#b3261e);font-weight:600">
                            ⚠ El token que está guardado lo emitió la aplicación
                            <b><?= e(mpAppIdDelToken()) ?></b>, no esta.
                            La clave secreta del webhook tiene que salir de
                            <b><?= e(mpAppIdDelToken()) ?></b>: la de otra aplicación
                            hace que todos los avisos se rechacen.
                        </p>
                    <?php endif; ?>
                    <p class="ayuda">El que aparece en «Tus integraciones».</p>
                </div>

                <div class="campo">
                    <label for="i-user">Usuario de prueba (comprador)</label>
                    <input id="i-user" name="mp_usuario_prueba" type="text" maxlength="120"
                           value="<?= e(ajuste('mp_usuario_prueba', '')) ?>">
                    <p class="ayuda">El TESTUSER… con el que se paga en el checkout.</p>
                </div>
            </div>

            <div class="pie-formulario" style="margin-top:14px">
                <button class="btn-mini" type="submit">Guardar datos de la integración</button>
            </div>
        </form>

        <div style="display:flex;gap:10px;flex-wrap:wrap;margin-top:16px">
            <form method="post">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="probar">
                <button class="btn-mini" type="submit" <?= mpConfigurada() ? '' : 'disabled' ?>>
                    Probar la conexión
                </button>
            </form>

            <form method="post"
                  onsubmit="return confirm('¿Borrar las credenciales y desconectar la pasarela?\n\nLa transferencia manual seguirá funcionando.')">
                <?= campoCsrf() ?>
                <input type="hidden" name="accion" value="borrar_credenciales">
                <button class="btn-mini peligro" type="submit">Borrar y desconectar</button>
            </form>
        </div>
    </div>
</div>


<!-- ── Webhook ─────────────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>Aviso de pago (webhook)</h2></div>
    <div class="cuerpo">

        <p style="color:var(--texto);font-size:.9rem;line-height:1.65;margin-bottom:15px">
            En el panel de Mercado Pago, en <b>Notificaciones → Webhooks</b>, se da de alta
            esta dirección para el evento <b>Pagos</b>. Cuando un pago se aprueba, Mercado
            Pago avisa ahí y el acceso se concede solo. Al darla de alta genera una
            <b>clave secreta</b>: esa es la que va arriba, en las credenciales.
        </p>

        <div class="campo" style="margin-bottom:14px">
            <div class="rotulo" style="font-size:.73rem;text-transform:uppercase;letter-spacing:.07em;color:var(--texto-tenue);margin-bottom:5px">
                URL a la que debe avisar
            </div>
            <div class="dato-copiable"><span><?= e($urlHook) ?></span></div>
        </div>

        <?php if ($hookLocal): ?>
            <div class="aviso mal">
                <b>Esta dirección es local, así que Mercado Pago no puede llamarla.</b>
                Desde sus servidores, <code>localhost</code> es su propio localhost.
                Mientras el sitio no esté publicado en una dirección pública, ningún pago
                se activará por webhook.
                <br><br>
                Aun así el cobro funciona para probar: cuando el cliente vuelve de Mercado
                Pago, la pantalla de retorno le pregunta a la API por el estado y lo aplica
                si cuadra. Para probar el webhook de verdad hace falta exponer el sitio
                (ngrok, cloudflared) y poner esa dirección en <code>URL_BASE</code>.
            </div>
        <?php endif; ?>

        <p class="aviso-suave">
            Mercado Pago <b>no manda el estado del pago</b> en el aviso: manda un
            identificador. El servidor va y le pregunta a su API, y comprueba además que el
            <b>monto y la moneda</b> coincidan con lo que se puso a cobrar. Por eso, aunque
            alguien falsificara un aviso entero, no podría regalarse un plan.
        </p>
    </div>
</div>


<!-- ── Cuenta de recaudo ───────────────────────────────────────────── -->

<form method="post">
    <?= campoCsrf() ?>

    <div class="caja">
        <div class="cabeza"><h2>Cuenta de recaudo</h2></div>
        <div class="cuerpo">

            <?php if (!recaudoConfigurado()): ?>
                <div class="aviso info">
                    Faltan datos para poder cobrar por transferencia. Como mínimo hacen
                    falta el <b>titular</b> y un <b>número de cuenta</b> o un <b>Nequi</b>.
                </div>
            <?php endif; ?>

            <div class="formulario">
                <?php foreach ($campos as $clave => [$etiqueta, $tipo, $ayuda]): ?>
                    <div class="campo">
                        <label for="a-<?= e($clave) ?>"><?= e($etiqueta) ?></label>
                        <?php if ($tipo === 'largo'): ?>
                            <textarea id="a-<?= e($clave) ?>" name="<?= e($clave) ?>"
                                      maxlength="600"><?= e(ajuste($clave, '')) ?></textarea>
                        <?php else: ?>
                            <input id="a-<?= e($clave) ?>" name="<?= e($clave) ?>"
                                   type="<?= $tipo === 'correo' ? 'email' : ($tipo === 'numero' ? 'number' : 'text') ?>"
                                   <?= $tipo === 'numero' ? 'min="0" max="60"' : 'maxlength="190"' ?>
                                   value="<?= e(ajuste($clave, '')) ?>">
                        <?php endif; ?>
                        <?php if ($ayuda !== ''): ?>
                            <p class="ayuda"><?= e($ayuda) ?></p>
                        <?php endif; ?>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="pie-formulario" style="margin-top:20px">
                <button class="btn-mini solido" type="submit">Guardar cuenta de recaudo</button>
            </div>
        </div>
    </div>
</form>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
