<?php
/**
 * migracion-mercadopago.php — Fase 6 · Pasarela de pago
 *
 *     php database/migracion-mercadopago.php             # simulacion
 *     php database/migracion-mercadopago.php --aplicar   # escribe
 *
 * ---------------------------------------------------------------------
 *  POR QUE `pagos_modo` SE PARTE EN DOS
 * ---------------------------------------------------------------------
 *
 * `pagos_modo` era excluyente: o transferencia manual, o pasarela. Eso
 * obligaba a elegir, y en Colombia mucha familia prefiere transferir
 * aunque haya boton de tarjeta. Perder a esa mitad de los clientes por un
 * ajuste no tiene sentido cuando el cobro manual ya funciona entero.
 *
 * Ahora son dos interruptores independientes:
 *
 *   pagos_manual_activo   1 = se ofrece transferencia
 *   pagos_pasarela        '' o 'mercadopago'
 *
 * `pagos_modo` se conserva sin tocar por si algo viejo lo lee; lo que
 * decide ahora es `pasarelaActiva()` y `manualActivo()` en pagos.php.
 *
 * ---------------------------------------------------------------------
 *  LAS CREDENCIALES
 * ---------------------------------------------------------------------
 *
 * Se crean VACIAS a proposito. Este script no puede inventarlas y dejar
 * un valor de mentira haria que `pasarelaActiva()` diera por conectada
 * una pasarela que no responde: el cliente veria un boton de pagar que
 * lleva a un error.
 *
 * El access token de Mercado Pago sirve para cobrar, consultar y
 * reembolsar. Se puede poner aqui (queda en `settings`) o, mejor en un
 * servidor de verdad, en las variables de entorno MP_ACCESS_TOKEN y
 * MP_WEBHOOK_SECRETO, que ganan sobre la base de datos.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 6 - Pasarela de pago (Mercado Pago)\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

if (!pagosInstalados()) {
    echo "  [!] Falta la fase 4. Corre antes:\n";
    echo "      php database/migracion-pagos.php --aplicar\n\n";
    exit(1);
}

$cambios = 0;


// =====================================================================
//  1. AJUSTES NUEVOS
// =====================================================================

echo "-- Ajustes -----------------------------------------------------\n";

$ajustes = [
    'pagos_manual_activo' => [
        '1',
        'Se ofrece la transferencia manual (1) o no (0)',
    ],
    'pagos_pasarela' => [
        '',
        "Pasarela conectada: vacio o 'mercadopago'",
    ],
    'mp_access_token' => [
        '',
        'Mercado Pago: access token (privado, sirve para cobrar y reembolsar)',
    ],
    'mp_public_key' => [
        '',
        'Mercado Pago: public key',
    ],
    'mp_webhook_secreto' => [
        '',
        'Mercado Pago: clave secreta con la que firma sus avisos',
    ],
    'mp_sandbox' => [
        '1',
        'Ambiente de pruebas (1) o produccion (0)',
    ],
];

foreach ($ajustes as $clave => [$valor, $etiqueta]) {
    $existe = traerValor('SELECT `key` FROM settings WHERE `key` = ?', [$clave]);

    if ($existe !== null) {
        echo "  [ok] $clave ya definido - se respeta su valor.\n";
        continue;
    }

    echo "  [->] $clave  ($etiqueta)\n";

    // Se inserta directo y no con `guardarAjuste()`: esa vive en
    // `includes/admin.php`, que config.php no carga, y ademas aqui hace
    // falta escribir tambien la etiqueta que describe el ajuste.
    if ($aplicar) {
        ejecutar(
            'INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)',
            [$clave, $valor, $etiqueta]
        );
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  2. COLUMNA provider_status
// =====================================================================

/*
 * `aplicarAvisoPasarela()` la escribe desde la fase 4, pero la tabla se
 * creo antes en algunas instalaciones. Sin la columna, cada aviso de la
 * pasarela reventaria con un error de SQL justo despues de que el cliente
 * pagara: el peor momento posible.
 */

echo "-- payments.provider_status ------------------------------------\n";

$tiene = traerValor(
    "SELECT COLUMN_NAME FROM information_schema.COLUMNS
      WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'payments'
        AND COLUMN_NAME = 'provider_status'"
);

if ($tiene !== null) {
    echo "  [ok] Ya existe.\n";
} else {
    echo "  [->] Falta. Se anade.\n";
    if ($aplicar) {
        db()->exec(
            "ALTER TABLE `payments`
             ADD COLUMN `provider_status` VARCHAR(40) NULL
             COMMENT 'Ultimo estado crudo informado por la pasarela'
             AFTER `provider_reference`"
        );
        echo "  [ok] Anadida.\n";
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  3. ESTADO ACTUAL
// =====================================================================

echo "-- Estado del cobro --------------------------------------------\n";

$token   = mpAccessToken();
$secreto = mpWebhookSecreto();

printf("  Transferencia manual : %s\n", manualActivo() ? 'ofrecida' : 'oculta');
printf("  Datos de recaudo     : %s\n", recaudoConfigurado() ? 'completos' : 'INCOMPLETOS');
printf("  Pasarela elegida     : %s\n", ajuste('pagos_pasarela', '') ?: '(ninguna)');
printf("  Access token         : %s\n", $token !== '' ? mpEnmascarar($token) : '(sin poner)');
printf("  Secreto del webhook  : %s\n", $secreto !== '' ? mpEnmascarar($secreto) : '(sin poner)');
printf("  Pasarela operativa   : %s\n", pasarelaConectada() ? 'SI' : 'no');
printf("  Ambiente             : %s\n", mpEsPruebas() ? 'PRUEBAS' : 'PRODUCCION');
printf("  Pagos registrados    : %d\n", (int) traerValor('SELECT COUNT(*) FROM payments'));

echo "\n";

if ($aplicar) {
    echo "Listo. $cambios cambio(s) aplicado(s).\n\n";

    echo "Que falta para cobrar de verdad:\n\n";
    echo "  1. Crear la aplicacion en https://www.mercadopago.com.co/developers\n";
    echo "  2. Copiar el Access Token y la Public Key de PRUEBAS al panel:\n";
    echo "     Panel -> Cobros -> Ajustes de cobro\n";
    echo "  3. En el panel de Mercado Pago, dar de alta el webhook apuntando a:\n";
    echo "       " . url('api/webhook-pago.php') . "\n";
    echo "     y copiar la clave secreta que genera.\n";
    echo "  4. Poner `pagos_pasarela` en 'mercadopago'.\n\n";

    if (mpUrlEsLocal(url(''))) {
        echo "  AVISO: la URL base es local (" . url('') . ").\n";
        echo "  Mercado Pago no puede llamar a un localhost, asi que el webhook\n";
        echo "  no llegara y ningun pago se activara solo. Para probar de punta a\n";
        echo "  punta hace falta exponer el sitio (ngrok, cloudflared) y poner esa\n";
        echo "  direccion en URL_BASE.\n\n";
    }
} else {
    echo "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
}
