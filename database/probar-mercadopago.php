<?php
/**
 * probar-mercadopago.php — El cobro contra Mercado Pago DE VERDAD
 *
 * Las suites del proyecto prueban la pasarela contra un doble local: la
 * firma, la verificación del monto, la conciliación y los reembolsos. Eso
 * cubre nuestra parte, y no cubre la suya — que el token sirva, que la
 * cuenta esté en Colombia, que la preferencia se cree con los campos que
 * ellos esperan hoy.
 *
 * Este script es el otro lado: habla con la API real del ambiente de
 * pruebas y dice si el cobro funciona de punta a punta.
 *
 *     php database/probar-mercadopago.php                 · revisa y no toca nada
 *     php database/probar-mercadopago.php --cobro         · crea un cobro de prueba
 *     php database/probar-mercadopago.php --verificar REF · pregunta por uno
 *     php database/probar-mercadopago.php --limpiar       · borra los de prueba
 *
 * Con `--token=TEST-…` se puede probar una credencial SIN guardarla. Sirve
 * para comprobar que una llave nueva sirve antes de ponerla en el panel.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  ESTE SCRIPT NO COBRA DINERO DE VERDAD
 * ─────────────────────────────────────────────────────────────────────
 *
 * Y se niega a funcionar si detecta que el token es de producción, salvo
 * que se insista con `--en-serio`. Crear una preferencia con credenciales
 * reales genera un cobro real: barato de hacer por descuido, caro de
 * explicar.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$args = $argv ?? [];

/** El valor de un argumento con forma `--clave=valor`. */
function arg(string $clave, string $porDefecto = ''): string
{
    foreach ($GLOBALS['args'] as $a) {
        if (str_starts_with((string) $a, "--$clave=")) {
            return trim(substr((string) $a, strlen($clave) + 3));
        }
    }
    return $porDefecto;
}

$hacerCobro = in_array('--cobro', $args, true);
$limpiar    = in_array('--limpiar', $args, true);
$enSerio    = in_array('--en-serio', $args, true);
$verificar  = '';

foreach ($args as $i => $a) {
    if ($a === '--verificar') {
        $verificar = trim((string) ($args[$i + 1] ?? ''));
    }
}

/*
 * Un token pasado por la línea de comandos gana sobre el guardado, y no
 * se guarda. `mpAccessToken()` mira primero la variable de entorno, así
 * que ponerla ahí es la forma de probar sin tocar la base.
 */
$tokenSuelto = arg('token');

if ($tokenSuelto !== '') {
    putenv('MP_ACCESS_TOKEN=' . $tokenSuelto);
}

$SEP = str_repeat('=', 74);

echo "\n$SEP\n  MERCADO PAGO · PRUEBA CONTRA LA API REAL\n$SEP\n\n";


// =====================================================================
//  0. ¿HAY CON QUÉ?
// =====================================================================

$token = mpAccessToken();

if ($token === '') {
    echo "  ✗ No hay access token.\n\n";
    echo "    Se saca de Mercado Pago → Tus integraciones → tu aplicación →\n";
    echo "    Credenciales de prueba. Es la línea que empieza por TEST-.\n\n";
    echo "    Ponlo en el panel (Cobros → Ajustes) o pruébalo sin guardarlo:\n";
    echo "      php database/probar-mercadopago.php --token=TEST-...\n\n";
    exit(1);
}

/*
 * De dónde salió el token, de verdad.
 *
 * Antes solo distinguía `--token=` del resto, así que un token puesto en
 * `MP_ACCESS_TOKEN` se anunciaba como «ajustes de la plataforma». Es un
 * rótulo pequeño en el peor sitio posible: quien lo lee está intentando
 * averiguar precisamente QUÉ CREDENCIAL ESTÁ VIVA, y creer que la que
 * manda es la guardada cuando manda la del entorno lleva a cambiar la
 * equivocada — y a pensar que ya no se cobra cuando se sigue cobrando.
 */
$delEntorno = getenv('MP_ACCESS_TOKEN');

$origen = $tokenSuelto !== ''
    ? 'línea de comandos (no se guarda)'
    : ((is_string($delEntorno) && trim($delEntorno) !== '')
        ? 'variable de entorno MP_ACCESS_TOKEN (manda sobre los ajustes)'
        : 'ajustes de la plataforma');

printf("  Token      : %s\n", mpEnmascarar($token));
printf("  Origen     : %s\n", $origen);
printf("  API        : %s\n", mpApiBase());
printf("  URL_BASE   : %s%s\n", URL_BASE,
    mpUrlEsLocal(URL_BASE) ? '  ← local: el webhook no puede llegar' : '');

echo "\n";


// =====================================================================
//  1. ¿SIRVE EL TOKEN, Y DE QUIÉN ES?
// =====================================================================

echo "1. La identidad de la cuenta\n";

$yo = mpApi('GET', '/users/me');

if (!$yo['ok']) {
    echo "  ✗ " . $yo['error'] . "\n\n";
    echo "    Un 401 significa que el token no sirve: revocado, mal copiado\n";
    echo "    o de otra aplicación. Vuelve a copiarlo entero.\n\n";
    exit(1);
}

$d = $yo['datos'];

printf("  ✓ Cuenta   : %s (id %s)\n", (string) ($d['nickname'] ?? '?'), (string) ($d['id'] ?? '?'));
printf("    Correo   : %s\n", (string) ($d['email'] ?? '?'));
printf("    País     : %s\n", (string) ($d['site_id'] ?? '?'));

/*
 * ─────────────────────────────────────────────────────────────────────
 *  QUIÉN DECIDE SI ESTO ES UNA PRUEBA
 * ─────────────────────────────────────────────────────────────────────
 *
 * No el prefijo del token. Una aplicación creada dentro de un usuario de
 * prueba emite credenciales `APP_USR-` que no mueven un peso, y este
 * script llegó a rechazarlas como si fueran de producción.
 *
 * Lo decide la CUENTA, y eso lo acaba de decir Mercado Pago.
 */
$pruebas = mpCuentaDePrueba($d);

printf("    Es cuenta de %s\n", $pruebas ? 'PRUEBA (no mueve dinero real)'
                                         : 'PRODUCCIÓN ← mueve dinero REAL');

if (!$pruebas && ($hacerCobro || $verificar !== '') && !$enSerio) {
    echo "\n  ✗ Mercado Pago dice que esta cuenta NO es de prueba.\n";
    echo "    Crear una preferencia con ella genera un cobro REAL.\n";
    echo "    Si de verdad es lo que quieres, añade --en-serio.\n\n";
    exit(1);
}

/*
 * El país no es un detalle. La plataforma cobra en pesos colombianos y
 * `verificarPagoMp()` rechaza cualquier pago cuya moneda no sea COP: una
 * cuenta de otro país cobraría en su moneda y todos los pagos se
 * rechazarían por «la moneda es ARS y esperábamos COP».
 */
if ((string) ($d['site_id'] ?? '') !== 'MCO') {
    printf("\n  ⚠ La cuenta es de %s, no de Colombia (MCO).\n",
        (string) ($d['site_id'] ?? '?'));
    echo "    La plataforma cobra en COP y rechaza cualquier pago en otra\n";
    echo "    moneda. Con esta cuenta, todos los pagos se rechazarían.\n";
}

echo "\n";


// =====================================================================
//  2. LIMPIAR
// =====================================================================

const NOTA_PRUEBA = 'Cobro de prueba de probar-mercadopago.php';

if ($limpiar) {
    echo "2. Limpiando los cobros de prueba\n";

    $filas = traerTodo('SELECT id, reference FROM payments WHERE notes = ?', [NOTA_PRUEBA]);

    foreach ($filas as $f) {
        ejecutar('DELETE FROM payment_events WHERE payment_id = ?', [(int) $f['id']]);
        ejecutar('DELETE FROM payments WHERE id = ?', [(int) $f['id']]);
        printf("  · %s borrado\n", $f['reference']);
    }

    printf("  ✓ %d cobro(s) de prueba fuera.\n\n", count($filas));
    exit(0);
}


// =====================================================================
//  3. VERIFICAR UNO
// =====================================================================

if ($verificar !== '') {
    echo "3. Preguntando por la referencia $verificar\n";

    $pago = pagoPorReferencia($verificar);

    if (!$pago) {
        echo "  ✗ No hay ningún cobro nuestro con esa referencia.\n\n";
        exit(1);
    }

    printf("  Nuestro    : %s · %s · %s\n", $pago['reference'],
        precioCop((int) $pago['amount_cop']), $pago['status']);

    $r = sincronizarPagoMp((string) $pago['reference']);

    printf("  Mercado Pago: %s\n", $r['mensaje']);

    if ($r['aplicado']) {
        $pago = pagoPorReferencia($verificar);
        printf("  ✓ Quedó en «%s».\n", $pago['status']);

        $sus = traerUno(
            'SELECT p.name, s.expires_at FROM subscriptions s
               JOIN plans p ON p.id = s.plan_id
              WHERE s.user_id = ? AND s.status = "active"
           ORDER BY s.id DESC LIMIT 1', [(int) $pago['user_id']]);

        if ($sus) {
            printf("  ✓ Suscripción concedida: %s hasta %s\n",
                $sus['name'], (string) ($sus['expires_at'] ?? 'sin vencimiento'));
        }
    }

    echo "\n";
    exit($r['ok'] ? 0 : 1);
}


// =====================================================================
//  4. CREAR UN COBRO DE PRUEBA
// =====================================================================

if (!$hacerCobro) {
    echo "2. Lo que falta para cobrar\n";

    $puntos = [
        'Access token'            => mpAccessToken() !== '',
        'Public key'             => mpPublicKey() !== '',
        'Clave secreta del aviso' => mpWebhookSecreto() !== '',
        'Pasarela elegida'        => pasarelaActiva() === 'mercadopago',
        'Dirección pública'       => !mpUrlEsLocal(URL_BASE),
    ];

    foreach ($puntos as $nombre => $listo) {
        printf("  %s %s\n", $listo ? '✓' : '·', $nombre);
    }

    /*
     * La invitación cambia según la cuenta, y tiene que cambiar.
     *
     * Con una cuenta de producción, «crear un cobro de prueba» es una
     * frase falsa: ese cobro es real. El guardado de más arriba lo
     * impide y exige `--en-serio`, pero invitar primero y negarse
     * después enseña al que lee a añadir la bandera sin entender qué
     * hace — que es exactamente cómo se cobra sin querer.
     */
    if ($pruebas) {
        echo "\n  Para crear un cobro de prueba de verdad:\n";
        echo "    php database/probar-mercadopago.php --cobro\n\n";
    } else {
        echo "\n  ⚠ Esta cuenta es de PRODUCCIÓN: aquí no hay cobros de prueba.\n";
        echo "    Cualquier cobro que se cree con ella es real y alguien\n";
        echo "    tendría que pagarlo de verdad.\n\n";
        echo "    Para probar sin mover dinero, usa las credenciales de\n";
        echo "    prueba de tu aplicación:\n";
        echo "      php database/probar-mercadopago.php --token=TEST-...\n\n";
    }

    exit(0);
}

echo "2. Creando un cobro de prueba\n";

$plan = traerUno(
    'SELECT slug, name FROM plans
      WHERE is_active = 1 AND slug <> "free" AND price_yearly_cop > 0
   ORDER BY price_yearly_cop LIMIT 1');

if (!$plan) {
    echo "  ✗ No hay ningún plan activo con precio anual.\n\n";
    exit(1);
}

/*
 * El cobro se le hace a una cuenta de administración de verdad, no a una
 * inventada: `crearPago()` exige un usuario activo, y el correo viaja a
 * Mercado Pago como `payer`. Se elige el administrador más antiguo.
 */
$quien = traerUno(
    'SELECT id, name, email FROM users
      WHERE role = "admin" AND status = "active" ORDER BY id LIMIT 1');

if (!$quien) {
    echo "  ✗ No hay ninguna cuenta de administración activa a la que cobrarle.\n\n";
    exit(1);
}

$r = crearPago((int) $quien['id'], (string) $plan['slug'], 'yearly', 'pasarela', [
    'provider' => 'mercadopago',
    'notes'    => NOTA_PRUEBA,
]);

if (!$r['ok']) {
    echo "  ✗ " . $r['error'] . "\n\n";
    exit(1);
}

$pago = $r['pago'];

printf("  ✓ Cobro     : %s\n", $pago['reference']);
printf("    Plan      : %s (%s)\n", $plan['name'], precioCop((int) $pago['amount_cop']));
printf("    A nombre de: %s <%s>\n", $quien['name'], $quien['email']);

echo "\n3. Creando la preferencia en Mercado Pago\n";

$pref = mpCrearPreferencia($pago);

if (!$pref['ok']) {
    echo "  ✗ " . $pref['error'] . "\n\n";
    echo "    Si dice «invalid auto_return», el sitio está en localhost y la\n";
    echo "    plataforma ya lo tiene en cuenta; cualquier otro mensaje suele\n";
    echo "    nombrar el campo que Mercado Pago no aceptó.\n\n";
    exit(1);
}

printf("  ✓ Preferencia: %s\n", $pref['preferencia']);

echo "\n$SEP\n  AHORA PAGA TÚ\n$SEP\n\n";

echo "  Abre esta dirección en una ventana de incógnito:\n\n";
echo "    " . $pref['url'] . "\n\n";

echo "  Entra con tu USUARIO DE PRUEBA (el TESTUSER…, no tu cuenta real) y\n";
echo "  paga con una tarjeta de prueba:\n\n";
echo "    Aprobada  : 5031 7557 3453 0604 · 11/30 · 123 · nombre APRO\n";
echo "    Rechazada : la misma, con el nombre OTHE\n";
echo "    Documento : CC 12345678\n\n";

echo "  Al terminar, comprueba que llegó:\n\n";
echo "    php database/probar-mercadopago.php --verificar " . $pago['reference'] . "\n\n";

if (mpUrlEsLocal(URL_BASE)) {
    echo "  ⚠ El sitio está en localhost, así que Mercado Pago NO podrá avisar\n";
    echo "    por webhook. El cobro funciona igual: la línea de arriba le\n";
    echo "    pregunta a su API y aplica el resultado, que es exactamente lo\n";
    echo "    que hace la pantalla de retorno cuando el cliente vuelve.\n\n";
}

echo "  Cuando acabes, para no dejar rastro:\n";
echo "    php database/probar-mercadopago.php --limpiar\n\n";
