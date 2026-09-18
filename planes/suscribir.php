<?php
/**
 * planes/suscribir.php — Elegir modalidad y empezar el pago
 *
 * Primer paso de la contratación. Aquí solo se decide UNA cosa —anual o
 * mensual— y de ahí se sale con una referencia de pago creada.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO SE PIDE LA CUENTA AQUÍ
 * ─────────────────────────────────────────────────────────────────────
 *
 * Quien llega sin sesión va a registro y vuelve a esta misma pantalla.
 * No se le pide crear la cuenta *dentro* del formulario de pago, y no
 * es un detalle de implementación: la suscripción se cuelga de un
 * usuario, así que un pago sin cuenta detrás no tendría a quién dar
 * acceso. Mezclar los dos formularios en uno hace que un error de
 * contraseña parezca un error de pago.
 *
 * El precio no viaja nunca por el formulario. Se manda el plan y la
 * modalidad; el monto lo pone el servidor leyendo la tabla `plans`.
 * Si el navegador pudiera decir cuánto vale algo, cualquiera compraría
 * el plan Escuela por mil pesos con el inspector abierto.
 */

require_once dirname(__DIR__) . '/config/config.php';

$slug = get('plan', PLAN_BIBLIOTECA);
$plan = planPorSlug($slug);

if (!$plan || (int) $plan['is_active'] !== 1) {
    mensaje('info', 'Ese plan no está disponible.');
    redirigir('planes/');
}

// El plan gratuito no se contrata: se obtiene creando una cuenta.
if ($plan['slug'] === PLAN_FREE) {
    redirigir(usuarioActual() ? 'actividades/' : 'registro.php');
}

/*
 * ─────────────────────────────────────────────────────────────────────
 *  DOS PUERTAS, Y SE PREGUNTA CUÁL
 * ─────────────────────────────────────────────────────────────────────
 *
 * A partir de aquí hace falta cuenta, y hay exactamente dos clases de
 * persona: la que ya la tiene y la que no. Las dos quieren pagar.
 *
 * Se probaron las dos formas de adivinar, y las dos fallan al mismo
 * cliente por la mitad:
 *
 *   · Mandar a todos a `login.php` —lo que hacía `exigirSesion()`— le
 *     pone al cliente NUEVO una contraseña que no tiene, con el enlace
 *     de crear cuenta en pequeño abajo.
 *
 *   · Mandar a todos al registro le pone a quien VUELVE un formulario
 *     de alta para una cuenta que ya existe. Es el mismo error al revés.
 *
 * Así que no se adivina: se enseñan las dos puertas y elige él, que lo
 * sabe sin pensarlo. Cuesta un clic y ahorra el callejón.
 *
 * Y va aquí, en la misma dirección, en vez de en una página nueva: así
 * la dirección `suscribir.php?plan=…` sigue sirviendo para enlazar desde
 * cualquier sitio — una campaña, un correo— sin saber si quien la abre
 * tiene cuenta.
 */
/*
 * ─────────────────────────────────────────────────────────────────────
 *  LA INTENCIÓN DE COMPRAR VIAJA EN LA DIRECCIÓN, NO EN LA SESIÓN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Antes se apuntaba en `$_SESSION['compra_pendiente']`, y ahí se quedaba
 * pegada. Quien se asomaba a ver el precio, se lo pensaba mejor y se iba
 * a crear una cuenta gratis por el menú, llegaba al registro con el plan
 * nombrado, la barra de pasos de un pago y un botón que decía «seguir al
 * pago». Le estábamos cobrando por haber mirado.
 *
 * En la dirección se cancela sola: si el parámetro no está, no hay
 * compra. Entrar a `registro.php` desde cualquier otro sitio del mundo
 * es, por construcción, el camino gratis. No hace falta acordarse de
 * limpiar nada, y por eso ya no se puede olvidar.
 */
if (!haySesion() || !usuarioActual()) {
    $mensual = montoDePlan($plan, 'monthly');
    $anual   = montoDePlan($plan, 'yearly');

    $titulo        = 'Contratar ' . $plan['name'];
    $seccionActiva = 'planes';
    $hojasExtra    = ['assets/css/pago.css'];

    require RUTA_INCLUDES . '/cabecera.php';
    require __DIR__ . '/_dos-puertas.php';
    require RUTA_INCLUDES . '/pie.php';

    exit;
}

exigirSesion();

// Y hace falta una cuenta de verdad: quien entró tocando su nombre en la
// lista de clase no contrata nada.
exigirSesionPlena();

$usuario  = usuarioActual();
$completo = tieneCatalogoCompleto();

$mensual = montoDePlan($plan, 'monthly');
$anual   = montoDePlan($plan, 'yearly');
$ahorro  = ahorroAnual($mensual, $anual);

// Si ya dejó un pago a medias, se retoma ese en vez de abrir otro. Dos
// referencias abiertas para una sola transferencia es la forma más
// rápida de que nadie sepa cuál confirmar.
$pendiente = pagosInstalados() ? pagoPendienteDe((int) $usuario['id'], (int) $plan['id']) : null;

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    if (!pagosInstalados()) {
        mensaje('error', 'El cobro todavía no está habilitado. Escríbenos y lo resolvemos.');
        redirigir('planes/');
    }

    if ($pendiente) {
        redirigir('planes/pagar.php?ref=' . urlencode($pendiente['reference']));
    }

    /*
     * Solo las formas de pago que el cliente puede elegir por su cuenta
     * Y que están de verdad disponibles.
     *
     * La cortesía nunca entra: nadie debería poder regalarse un plan
     * mandando un campo distinto.
     *
     * Antes, ante un método desconocido, se caía de vuelta a
     * «transferencia» a secas — aunque no hubiera ni cuenta bancaria
     * puesta. El pago se creaba y la pantalla siguiente le pedía al
     * cliente transferir sin decirle a dónde. Ahora se cae al primero
     * que exista, y si no existe ninguno no se crea nada.
     */
    $metodos = metodosParaCliente();

    if (!$metodos) {
        mensaje('error', 'Todavía no hay una forma de pago habilitada. Escríbenos y lo resolvemos.');
        redirigir('planes/');
    }

    $metodo = post('metodo', '');

    if (!isset($metodos[$metodo])) {
        $metodo = (string) array_key_first($metodos);
    }

    $r = crearPago((int) $usuario['id'], $plan['slug'], post('ciclo'), $metodo, [
        'payer_document' => post('documento'),
    ]);

    if ($r['ok']) {
        redirigir('planes/pagar.php?ref=' . urlencode($r['pago']['reference']));
    }

    mensaje('error', $r['error']);
}

$titulo        = 'Suscribirse a ' . $plan['name'];
$seccionActiva = 'planes';
$hojasExtra    = ['assets/css/pago.css'];

require RUTA_INCLUDES . '/cabecera.php';
?>

<section class="seccion">
    <div class="contenedor" style="max-width:660px">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('planes/')) ?>">Planes</a>
            <span class="sep">›</span>
            <span><?= e($plan['name']) ?></span>
        </nav>

        <?php if ($completo): ?>

            <div class="aviso ok">
                Ya tienes acceso completo al catálogo.
                <?php if (($v = vencimientoSuscripcion())): ?>
                    Tu suscripción está vigente hasta el <b><?= e(fechaLarga($v)) ?></b>.
                <?php endif; ?>
            </div>

            <p style="color:var(--texto);margin:14px 0 18px;line-height:1.6">
                Puedes renovar cuando quieras: el tiempo nuevo <b>se suma</b> a lo que te queda,
                no lo reemplaza. Renovar con un mes de antelación no te cuesta ese mes.
            </p>

            <a class="btn btn-principal" href="<?= e(url('actividades/')) ?>">Ir al catálogo</a>

        <?php elseif ($pendiente): ?>

            <div class="bloque">
                <h2>Tienes un pago a medias</h2>
                <p style="margin:10px 0 16px;line-height:1.65">
                    Empezaste la contratación de <b><?= e($plan['name']) ?></b> con la referencia
                    <b><?= e($pendiente['reference']) ?></b> por
                    <b><?= e(precioCop((int) $pendiente['amount_cop'])) ?></b>.
                    Sigue con esa misma: abrir otra dejaría dos referencias para una sola
                    transferencia.
                </p>
                <a class="btn btn-principal"
                   href="<?= e(url('planes/pagar.php?ref=' . urlencode($pendiente['reference']))) ?>">
                    Continuar con el pago →
                </a>
            </div>

        <?php else: ?>

            <div class="bloque">
                <h2><?= e($plan['name']) ?></h2>
                <p style="margin-bottom:6px;line-height:1.6"><?= e($plan['description'] ?? '') ?></p>
            </div>

            <form method="post" class="bloque">
                <?= campoCsrf() ?>

                <h2 style="margin-bottom:14px">Elige la modalidad</h2>

                <div class="opciones-plan">
                    <?php if ($anual !== null): ?>
                        <label class="opcion-plan">
                            <input type="radio" name="ciclo" value="yearly" checked>
                            <span class="cuerpo-opcion">
                                <b>Anual · <?= e(precioCop($anual)) ?></b>
                                <span>
                                    Un solo pago para todo el año.
                                    <?php if ($ahorro): ?>
                                        Ahorras <b><?= e(precioCop($ahorro['monto'])) ?></b>
                                        frente a pagar mes a mes (<?= (int) $ahorro['porcentaje'] ?>% menos).
                                    <?php endif; ?>
                                </span>
                            </span>
                        </label>
                    <?php endif; ?>

                    <?php if ($mensual !== null): ?>
                        <label class="opcion-plan">
                            <input type="radio" name="ciclo" value="monthly" <?= $anual === null ? 'checked' : '' ?>>
                            <span class="cuerpo-opcion">
                                <b>Mensual · <?= e(precioCop($mensual)) ?></b>
                                <span>Se renueva cada mes. Puedes dejarlo cuando quieras.</span>
                            </span>
                        </label>
                    <?php endif; ?>
                </div>

                <h2 style="margin:22px 0 14px">Cómo quieres pagar</h2>

                <div class="opciones-plan">
                    <?php
                    /*
                     * La misma lista que comprueba el POST de arriba.
                     *
                     * Antes la regla estaba escrita dos veces —aquí para
                     * esconder los botones, allí para validar— y solo una
                     * de las dos copias miraba si había datos de cobro.
                     * Enseñar «Tarjeta o PSE» sin pasarela conectada, o
                     * «Transferencia» sin cuenta, es una promesa que la
                     * pantalla siguiente no puede cumplir.
                     */
                    $primero = true;
                    foreach (metodosParaCliente() as $k => $m):
                        ?>
                        <label class="opcion-plan">
                            <input type="radio" name="metodo" value="<?= e($k) ?>" <?= $primero ? 'checked' : '' ?>>
                            <span class="cuerpo-opcion">
                                <b><?= e($m['icono'] . ' ' . $m['etiqueta']) ?></b>
                            </span>
                        </label>
                        <?php
                        $primero = false;
                    endforeach;
                    ?>

                    <?php if ($primero): ?>
                        <div class="aviso info" style="margin:0">
                            Todavía no hay una forma de pago habilitada. Escríbenos y lo resolvemos
                            contigo directamente.
                        </div>
                    <?php endif; ?>
                </div>

                <div class="campo-simple" style="margin-top:20px">
                    <label for="doc">Cédula o NIT <span style="font-weight:400;color:var(--texto-tenue)">(opcional)</span></label>
                    <input id="doc" name="documento" type="text" maxlength="40"
                           placeholder="solo si necesitas factura a tu nombre">
                </div>

                <?php if (!$primero): ?>
                    <button class="btn btn-principal" type="submit" style="margin-top:20px;width:100%">
                        Continuar →
                    </button>
                    <p style="text-align:center;color:var(--texto-tenue);font-size:.84rem;margin-top:11px">
                        Todavía no se cobra nada. El siguiente paso te dice cómo pagar.
                    </p>
                <?php endif; ?>
            </form>

            <a class="btn btn-secundario" href="<?= e(url('planes/')) ?>">← Volver a los planes</a>

        <?php endif; ?>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
