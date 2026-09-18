<?php
/**
 * pasarela-mercadopago.php — Cobro con Mercado Pago
 *
 * Traduce entre lo que entiende el proyecto —una referencia, un monto en
 * pesos, un estado— y lo que entiende Mercado Pago.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS DOS REGLAS QUE SOSTIENEN TODO ESTO
 * ─────────────────────────────────────────────────────────────────────
 *
 * **1. El estado de un pago solo se cree si lo dice la API de Mercado
 * Pago, consultada por nosotros.**
 *
 * El aviso (webhook) de Mercado Pago NO trae el estado: trae un id y poco
 * más. Con eso hay que ir a preguntar. Es incómodo y es correcto: si el
 * estado viniera en el cuerpo del aviso, quien lograra falsificar un
 * aviso se regalaría un plan. Aquí, aunque alguien falsifique el aviso
 * entero, lo único que consigue es que preguntemos por un id — y la
 * respuesta la da Mercado Pago, no él.
 *
 * Por la misma razón, la pantalla de retorno (`planes/retorno.php`) no
 * concede nada: sus parámetros vienen en la barra de direcciones y
 * cualquiera puede escribir `status=approved`.
 *
 * **2. Se comprueba el MONTO y la MONEDA, no solo el estado.**
 *
 * Un pago aprobado de $1.000 sobre un plan de $99.000 está aprobado. Sin
 * esta comprobación, manipular el precio en el checkout —o reutilizar un
 * pago viejo y barato— daría acceso completo. `verificarPagoMp()`
 * rechaza cualquier cosa que no cuadre con lo que se pidió cobrar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  DÓNDE VIVEN LAS LLAVES
 * ─────────────────────────────────────────────────────────────────────
 *
 * En `settings`, como el resto de la configuración de cobro, para que las
 * cambie quien administra sin tocar código. Se pueden sobreescribir con
 * variables de entorno (`MP_ACCESS_TOKEN`, `MP_WEBHOOK_SECRETO`), que es
 * lo que conviene en un servidor de verdad: así la credencial no queda
 * en un volcado de la base de datos.
 *
 * El access token es una credencial con la que se puede cobrar, consultar
 * y reembolsar. No se muestra entero en ninguna pantalla ni se escribe en
 * ningún log.
 */

declare(strict_types=1);


// =====================================================================
//  CONFIGURACIÓN
// =====================================================================

/**
 * Dirección de la API de Mercado Pago.
 *
 * Se puede cambiar con la variable de entorno `MP_API_BASE`, y existe por
 * una razón concreta: **poder probar el cobro sin cobrar**.
 *
 * Mercado Pago no tiene un servidor de pruebas aparte —el ambiente de
 * pruebas se distingue por las credenciales, no por la dirección—, así
 * que sin esto no hay forma de ejercitar la conciliación, la
 * verificación del monto ni el botón del panel más que pagando de verdad.
 * Con esto se apunta a un doble local y se comprueba, por ejemplo, que un
 * pago aprobado de mil pesos NO abre un plan de noventa y nueve mil.
 *
 * En producción no se define y vale lo de siempre.
 */
function mpApiBase(): string
{
    $base = getenv('MP_API_BASE');

    return is_string($base) && trim($base) !== ''
        ? rtrim(trim($base), '/')
        : 'https://api.mercadopago.com';
}

/** Segundos que se aceptan de antigüedad en la firma de un aviso. */
const MP_VENTANA_FIRMA = 900;

/**
 * Un valor de configuración, con la variable de entorno por delante.
 *
 * El orden importa: si alguien pone la credencial en el entorno del
 * servidor, esa gana sobre lo que haya en la base de datos. Así se puede
 * rotar una llave comprometida sin depender del panel.
 */
function mpConfig(string $clave, string $entorno): string
{
    $delEntorno = getenv($entorno);

    if (is_string($delEntorno) && trim($delEntorno) !== '') {
        return trim($delEntorno);
    }

    return trim((string) ajuste($clave, ''));
}

/**
 * Lo que le aparece al cliente en el extracto de su tarjeta.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ SE NORMALIZA Y NO SE ESCRIBE A MANO
 * ---------------------------------------------------------------------
 *
 * Es el texto que el titular ve en su estado de cuenta un mes después de
 * pagar, cuando ya no se acuerda. Si no lo reconoce, no llama: **abre un
 * desconocimiento con el banco**, y eso cuesta la plata del cobro, la
 * comisión y un punto de reputación con Mercado Pago.
 *
 * Los adquirentes son estrictos y silenciosos con este campo: lo cortan
 * al llegar al límite y las tildes y la Ñ acaban como símbolos raros o
 * como nada. «ACTIVIDADES EN LÍNEA» impreso como «ACTIVIDADES EN L?NEA»
 * es peor que no poner tilde — así que se quitan aquí, a propósito, y no
 * por descuido.
 *
 * Estaba fijo en el código como `ACTIVIDADESEL`, que no lo reconoce
 * nadie. Ahora sale de los ajustes como el resto de la configuración de
 * cobro, para que se pueda cambiar el día que se facture a nombre de una
 * empresa — sin poder escribir algo que el banco vaya a estropear.
 */
function mpDescriptorExtracto(): string
{
    $texto = trim((string) ajuste('mp_descriptor', ''));

    if ($texto === '') {
        $texto = 'ACTIVIDADES EN LINEA';
    }

    // Sin tildes ni Ñ: el banco no las imprime bien.
    $texto = strtr($texto, [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u', 'ñ' => 'n',
        'Á' => 'A', 'É' => 'E', 'Í' => 'I', 'Ó' => 'O', 'Ú' => 'U', 'Ü' => 'U', 'Ñ' => 'N',
    ]);

    // Solo letras, números y espacios, en mayúsculas y sin dobles.
    $texto = strtoupper((string) preg_replace('/[^A-Za-z0-9 ]/', '', $texto));
    $texto = trim((string) preg_replace('/\s+/', ' ', $texto));

    /*
     * El tope son 22 caracteres. Se corta por PALABRA y no por letra: un
     * «ACTIVIDADES EN LINEA S» partido a la mitad se lee peor que el
     * nombre entero sin la última palabra.
     */
    if (mb_strlen($texto) > 22) {
        $corto = '';

        foreach (explode(' ', $texto) as $palabra) {
            $prueba = $corto === '' ? $palabra : "$corto $palabra";

            if (mb_strlen($prueba) > 22) {
                break;
            }

            $corto = $prueba;
        }

        $texto = $corto !== '' ? $corto : mb_substr($texto, 0, 22);
    }

    // Si alguien lo dejó en algo que se normalizó hasta quedar vacío,
    // vale más el nombre de siempre que un extracto en blanco.
    return $texto !== '' ? $texto : 'ACTIVIDADES EN LINEA';
}

function mpAccessToken(): string  { return mpConfig('mp_access_token', 'MP_ACCESS_TOKEN'); }
function mpPublicKey(): string    { return mpConfig('mp_public_key', 'MP_PUBLIC_KEY'); }
function mpWebhookSecreto(): string { return mpConfig('mp_webhook_secreto', 'MP_WEBHOOK_SECRETO'); }

/**
 * El número de aplicación que lleva dentro el propio access token.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ SE SACA DEL TOKEN Y NO SE CREE LO QUE HAY APUNTADO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un token de Mercado Pago tiene esta forma:
 *
 *     APP_USR-<aplicación>-<fecha>-<aleatorio>-<usuario>
 *
 * El segundo trozo es la aplicación que lo emitió, y eso importa por una
 * razón concreta y nada obvia: **la clave secreta del webhook es de la
 * aplicación, no de la cuenta**. Quien tenga dos aplicaciones —lo normal,
 * porque se crea una para probar y otra de verdad— puede acabar copiando
 * la clave de una y el token de la otra. Al hacerlo, los avisos llegan
 * firmados con el secreto de la aplicación equivocada, aquí no cuadran, y
 * se rechazan con un 401 que no explica nada: el secreto está bien
 * escrito, el token está bien escrito, y no hay nada visiblemente mal.
 *
 * `mp_app_id` es un campo que alguien teclea en el panel, así que no
 * puede arbitrar: esto se saca del token, que es el que manda.
 *
 * Devuelve cadena vacía si el token no tiene esa forma —no se inventa
 * nada— y quien llama decide si callarse o avisar.
 */
function mpAppIdDelToken(): string
{
    $partes = explode('-', mpAccessToken());

    // APP_USR-<app>-… o TEST-<app>-…: siempre el segundo trozo.
    return isset($partes[1]) && ctype_digit($partes[1]) ? $partes[1] : '';
}

/**
 * ¿Lo apuntado como número de aplicación contradice al token?
 *
 * Solo dice que sí cuando están las dos cosas y no coinciden: sin nota
 * apuntada no hay contradicción que señalar, y sin token tampoco.
 */
function mpAppIdDiscrepa(): bool
{
    $apuntado = trim((string) ajuste('mp_app_id', ''));
    $real     = mpAppIdDelToken();

    return $apuntado !== '' && $real !== '' && $apuntado !== $real;
}

/**
 * ¿La cuenta detrás de estas credenciales es una cuenta de prueba?
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EL PREFIJO DEL TOKEN NO BASTA, Y CREER QUE SÍ ES PELIGROSO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Parece que `TEST-` significa pruebas y `APP_USR-` significa producción.
 * No es cierto: una aplicación creada DENTRO de un usuario de prueba
 * emite credenciales `APP_USR-` que no mueven un peso. Pasó en esta
 * instalación — un token `APP_USR-…` cuya cuenta resultó ser
 * `TESTUSER8073730326115990290`.
 *
 * Quien decide de verdad es la cuenta, y eso solo lo sabe Mercado Pago.
 * Sus usuarios de prueba se reconocen por el apodo y por el correo, que
 * es de un dominio suyo y no se puede elegir.
 *
 * @param array $datos Respuesta de `/users/me`
 */
function mpCuentaDePrueba(array $datos): bool
{
    $apodo  = strtoupper(trim((string) ($datos['nickname'] ?? '')));
    $correo = strtolower(trim((string) ($datos['email'] ?? '')));

    return str_starts_with($apodo, 'TESTUSER')
        || str_ends_with($correo, '@testuser.com');
}

/**
 * Le pregunta a Mercado Pago de quién son estas credenciales, y lo anota.
 *
 * Se anota porque la respuesta cuesta una llamada a su API y no puede
 * pedirse en cada carga de página. Se borra en cuanto cambian las
 * credenciales: una nota sobre la cuenta anterior es peor que no tener
 * ninguna.
 *
 * @return array{ok:bool, error:?string, apodo:?string, pais:?string, prueba:?bool}
 */
function mpVerificarCuenta(): array
{
    $r = mpApi('GET', '/users/me');

    if (!$r['ok']) {
        return ['ok' => false, 'error' => $r['error'],
                'apodo' => null, 'pais' => null, 'prueba' => null];
    }

    $d      = $r['datos'];
    $apodo  = (string) ($d['nickname'] ?? '');
    $pais   = (string) ($d['site_id'] ?? '');
    $prueba = mpCuentaDePrueba($d);

    if (function_exists('guardarAjuste')) {
        guardarAjuste('mp_cuenta_apodo', $apodo);
        guardarAjuste('mp_cuenta_pais', $pais);
        guardarAjuste('mp_cuenta_prueba', $prueba ? '1' : '0');
        guardarAjuste('mp_cuenta_visto', date('Y-m-d H:i:s'));
        guardarAjuste('mp_cuenta_huella', mpHuellaToken());

        if (function_exists('olvidarAjustes')) {
            olvidarAjustes();
        }
    }

    return ['ok' => true, 'error' => null,
            'apodo' => $apodo, 'pais' => $pais, 'prueba' => $prueba];
}

/**
 * Una huella del token actual, para saber si la nota sigue siendo suya.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ LA NOTA VA ATADA AL TOKEN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Lo que se anota de la cuenta vale para EL TOKEN con el que se
 * comprobó, y para ningún otro. Sin atarla, cambiar el token —desde la
 * variable de entorno, desde otra instalación, desde un volcado de la
 * base— dejaría en pie una nota que dice «es de prueba» sobre unas
 * credenciales que ya no lo son. Y esa nota manda sobre la casilla.
 *
 * Es exactamente el error que este mecanismo viene a evitar, pero al
 * revés: en vez de creerle a una casilla, creerle a una nota vieja.
 *
 * Se guarda un resumen y no el token: no hace falta más para saber si
 * cambió, y así la credencial no se duplica en otra fila de `settings`.
 */
function mpHuellaToken(): string
{
    $t = mpAccessToken();

    return $t === '' ? '' : substr(hash('sha256', $t), 0, 16);
}

/** Olvida lo que sabíamos de la cuenta. Se llama al cambiar credenciales. */
function mpOlvidarCuenta(): void
{
    if (!function_exists('guardarAjuste')) {
        return;
    }

    foreach (['mp_cuenta_apodo', 'mp_cuenta_pais', 'mp_cuenta_prueba',
              'mp_cuenta_visto', 'mp_cuenta_huella'] as $k) {
        guardarAjuste($k, '');
    }

    if (function_exists('olvidarAjustes')) {
        olvidarAjustes();
    }
}

/**
 * Lo que sabemos de la cuenta, o null si nunca se comprobó **o si el
 * token cambió desde entonces**.
 *
 * @return array{apodo:string, pais:string, prueba:?bool, visto:string}|null
 */
function mpCuentaConocida(): ?array
{
    $visto = trim((string) ajuste('mp_cuenta_visto', ''));

    if ($visto === '') {
        return null;
    }

    // La nota es de otro token: no vale. Mejor no saber que saber mal.
    if (trim((string) ajuste('mp_cuenta_huella', '')) !== mpHuellaToken()) {
        return null;
    }

    $p = trim((string) ajuste('mp_cuenta_prueba', ''));

    return [
        'apodo'  => (string) ajuste('mp_cuenta_apodo', ''),
        'pais'   => (string) ajuste('mp_cuenta_pais', ''),
        'prueba' => $p === '' ? null : ($p === '1'),
        'visto'  => $visto,
    ];
}

/**
 * ¿Estamos en el ambiente de pruebas?
 *
 * Tres fuentes, de la más fiable a la menos:
 *
 *   1. El token empieza por `TEST-`. Eso sí es inequívoco.
 *   2. **Lo que dijo Mercado Pago** la última vez que se comprobó la
 *      cuenta. Es la buena: una cuenta de prueba no puede cobrar de
 *      verdad, tenga el token el prefijo que tenga.
 *   3. El ajuste `mp_sandbox`, que es lo que alguien CREE.
 *
 * El orden importa. Si el ajuste dice «pruebas» y la cuenta es real, lo
 * que manda es la cuenta: cobrar de verdad creyendo que se está probando
 * es el peor de los dos errores, y dejar que lo decida una casilla es
 * dejar que lo decida un descuido.
 */
function mpEsPruebas(): bool
{
    if (str_starts_with(mpAccessToken(), 'TEST-')) {
        return true;
    }

    $cuenta = mpCuentaConocida();

    if ($cuenta !== null && $cuenta['prueba'] !== null) {
        return $cuenta['prueba'];
    }

    return ajuste('mp_sandbox', '1') === '1';
}

/**
 * ¿Se va a cobrar de verdad creyendo que se está probando?
 *
 * Es la pregunta que merece un aviso en rojo: la casilla dice pruebas y
 * la cuenta es real. Con `null` significa que nunca se comprobó, y
 * entonces tampoco se puede afirmar lo contrario.
 */
function mpRiesgoDeCobroReal(): bool
{
    if (str_starts_with(mpAccessToken(), 'TEST-')) {
        return false;
    }

    $cuenta = mpCuentaConocida();

    return ajuste('mp_sandbox', '1') === '1'
        && $cuenta !== null
        && $cuenta['prueba'] === false;
}

/** ¿Hay lo mínimo para poder cobrar? */
function mpConfigurada(): bool
{
    return mpAccessToken() !== '';
}

/** ¿Hay lo mínimo para poder RECIBIR avisos? Es una pregunta distinta. */
function mpWebhookConfigurado(): bool
{
    return mpWebhookSecreto() !== '';
}

/**
 * Oculta una credencial para poder enseñarla sin revelarla.
 *
 * Mostrar los últimos caracteres permite comprobar de un vistazo que la
 * llave puesta es la que uno cree, sin que la pantalla —ni una captura de
 * pantalla en un chat de soporte— la regale entera.
 */
function mpEnmascarar(string $valor): string
{
    if ($valor === '') {
        return '';
    }
    if (mb_strlen($valor) <= 8) {
        return str_repeat('•', mb_strlen($valor));
    }

    return mb_substr($valor, 0, 4) . str_repeat('•', 12) . mb_substr($valor, -4);
}


// =====================================================================
//  LLAMADAS A LA API
// =====================================================================

/**
 * Una llamada a la API de Mercado Pago.
 *
 * Devuelve siempre la misma forma, con `ok` y `error`, y NUNCA lanza:
 * quien la llama está a mitad de un cobro o de un webhook, y una
 * excepción ahí deja al cliente mirando una página en blanco después de
 * haber pagado.
 *
 * El token no entra jamás en el mensaje de error ni en el log.
 *
 * @return array{ok:bool, error:?string, codigo:int, datos:array}
 */
function mpApi(string $metodo, string $ruta, ?array $cuerpo = null, array $cabecerasExtra = []): array
{
    $token = mpAccessToken();

    if ($token === '') {
        return ['ok' => false, 'error' => 'Falta el access token de Mercado Pago.',
                'codigo' => 0, 'datos' => []];
    }

    $cabeceras = array_merge([
        'Authorization: Bearer ' . $token,
        'Content-Type: application/json',
        'Accept: application/json',
    ], $cabecerasExtra);

    $ch = curl_init(mpApiBase() . $ruta);

    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_CUSTOMREQUEST  => $metodo,
        CURLOPT_HTTPHEADER     => $cabeceras,
        // Un cobro no puede colgar la petición del cliente indefinidamente.
        CURLOPT_CONNECTTIMEOUT => 8,
        CURLOPT_TIMEOUT        => 20,
        // Verificar el certificado no es opcional: sin esto, cualquiera en
        // la red podría suplantar a la API y decirnos que un pago está
        // aprobado. Si falla, se arregla el CA del servidor, no se apaga.
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_SSL_VERIFYHOST => 2,
        CURLOPT_FOLLOWLOCATION => false,
    ]);

    if ($cuerpo !== null) {
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($cuerpo, JSON_UNESCAPED_UNICODE));
    }

    $respuesta = curl_exec($ch);
    $codigo    = (int) curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $fallo     = curl_error($ch);

    curl_close($ch);

    if ($respuesta === false) {
        return ['ok' => false, 'error' => 'No se pudo contactar con Mercado Pago: ' . $fallo,
                'codigo' => 0, 'datos' => []];
    }

    $datos = json_decode((string) $respuesta, true);
    $datos = is_array($datos) ? $datos : [];

    if ($codigo < 200 || $codigo >= 300) {
        // El mensaje de Mercado Pago se conserva porque suele decir
        // exactamente qué campo está mal, y sin él depurar es a ciegas.
        $detalle = (string) ($datos['message'] ?? $datos['error'] ?? 'sin detalle');

        return ['ok' => false, 'error' => "Mercado Pago respondió $codigo: $detalle",
                'codigo' => $codigo, 'datos' => $datos];
    }

    return ['ok' => true, 'error' => null, 'codigo' => $codigo, 'datos' => $datos];
}


// =====================================================================
//  CREAR EL CHECKOUT
// =====================================================================

/**
 * Crea la «preferencia» y devuelve la URL a la que mandar al cliente.
 *
 * Una preferencia es, en el vocabulario de Mercado Pago, la descripción
 * de lo que se va a cobrar. Se crea desde el servidor —nunca desde el
 * navegador— porque si el monto viajara por el cliente, el cliente podría
 * cambiarlo.
 *
 * @param array $pago Fila de `payments`
 * @return array{ok:bool, error:?string, url:?string, preferencia:?string}
 */
function mpCrearPreferencia(array $pago): array
{
    $vacio = ['ok' => false, 'url' => null, 'preferencia' => null];

    if (!mpConfigurada()) {
        return $vacio + ['error' => 'Mercado Pago no está configurado.'];
    }

    $plan = traerUno('SELECT * FROM plans WHERE id = ?', [(int) $pago['plan_id']]);

    if (!$plan) {
        return $vacio + ['error' => 'El plan de este pago ya no existe.'];
    }

    $monto = (int) $pago['amount_cop'];

    if ($monto <= 0) {
        return $vacio + ['error' => 'El monto del pago no es válido.'];
    }

    $usuario = traerUno('SELECT name, email FROM users WHERE id = ?', [(int) $pago['user_id']]);

    $cuerpo = [
        'items' => [[
            'id'          => (string) $plan['slug'],
            'title'       => 'Plan ' . $plan['name'] . ' · ' . etiquetaCiclo((string) $pago['billing_cycle']),
            'description' => 'Acceso completo al catálogo de Actividades en Línea.',
            'quantity'    => 1,
            'currency_id' => 'COP',
            // El peso colombiano no tiene decimales. Se manda entero para
            // que no haya redondeos por el camino.
            'unit_price'  => $monto,
        ]],

        // La pieza que amarra el pago de Mercado Pago con el nuestro. Es
        // lo que devuelve el webhook y por lo que lo encontramos.
        'external_reference' => (string) $pago['reference'],

        'notification_url' => url('api/webhook-pago.php'),

        'back_urls' => [
            'success' => url('planes/retorno.php?ref=' . urlencode((string) $pago['reference'])),
            'pending' => url('planes/retorno.php?ref=' . urlencode((string) $pago['reference'])),
            'failure' => url('planes/retorno.php?ref=' . urlencode((string) $pago['reference'])),
        ],

        // Lo que le aparece al cliente en el extracto de su tarjeta. Un
        // cargo que no se reconoce acaba en una reclamación al banco.
        'statement_descriptor' => mpDescriptorExtracto(),
    ];

    if ($usuario) {
        $cuerpo['payer'] = [
            'name'  => (string) $usuario['name'],
            'email' => (string) $usuario['email'],
        ];
    }

    /*
     * `auto_return` devuelve al cliente solo cuando aprueban. Mercado Pago
     * lo rechaza si `back_urls.success` no es una URL pública, así que en
     * un XAMPP local se omite: sin esto, la creación entera falla con un
     * «invalid auto_return» que no dice qué pasa.
     */
    if (!mpUrlEsLocal(url(''))) {
        $cuerpo['auto_return'] = 'approved';
    }

    /*
     * La clave de idempotencia evita cobrar dos veces si el cliente pulsa
     * dos veces o si la red reintenta. Va atada a la referencia, que es
     * única por pago.
     */
    $respuesta = mpApi('POST', '/checkout/preferences', $cuerpo, [
        'X-Idempotency-Key: ' . $pago['reference'],
    ]);

    if (!$respuesta['ok']) {
        return $vacio + ['error' => $respuesta['error']];
    }

    $d = $respuesta['datos'];

    // En pruebas hay que usar el `sandbox_init_point`: el otro cobraría
    // de verdad, o fallaría, según las credenciales.
    $url = mpEsPruebas()
        ? (string) ($d['sandbox_init_point'] ?? $d['init_point'] ?? '')
        : (string) ($d['init_point'] ?? '');

    if ($url === '') {
        return $vacio + ['error' => 'Mercado Pago no devolvió la dirección del checkout.'];
    }

    return [
        'ok'          => true,
        'error'       => null,
        'url'         => $url,
        'preferencia' => (string) ($d['id'] ?? ''),
    ];
}

/**
 * ¿Esta dirección es de una máquina local?
 *
 * Se usa para decidir si Mercado Pago va a poder llamarnos de vuelta.
 * Desde su servidor, `localhost` es su propio localhost.
 */
function mpUrlEsLocal(string $url): bool
{
    $host = strtolower((string) parse_url($url, PHP_URL_HOST));

    if ($host === '') {
        return true;
    }

    return $host === 'localhost'
        || $host === '127.0.0.1'
        || $host === '::1'
        || str_ends_with($host, '.local')
        || str_ends_with($host, '.test')
        || (bool) preg_match('/^(10\.|192\.168\.|172\.(1[6-9]|2\d|3[01])\.)/', $host);
}


// =====================================================================
//  VERIFICAR LA FIRMA DE UN AVISO
// =====================================================================

/**
 * Comprueba la firma de un aviso de Mercado Pago.
 *
 * Mercado Pago manda dos cabeceras:
 *
 *     x-signature:  ts=1739452800,v1=3f9a1c…
 *     x-request-id: 8f4b2c1e-…
 *
 * Y firma este texto exacto, con este orden y estos punto y coma:
 *
 *     id:<data.id>;request-id:<x-request-id>;ts:<ts>;
 *
 * NO firma el cuerpo. Intentar validar el cuerpo —que es lo que hace casi
 * todo el mundo la primera vez— falla siempre.
 *
 * @return array{ok:bool, error:?string}
 */
function mpVerificarFirma(string $dataId, string $cabeceraFirma, string $requestId): array
{
    $secreto = mpWebhookSecreto();

    if ($secreto === '') {
        return ['ok' => false, 'error' => 'No hay secreto de webhook configurado.'];
    }

    if ($cabeceraFirma === '') {
        return ['ok' => false, 'error' => 'El aviso llegó sin cabecera de firma.'];
    }

    // ── Partir «ts=…,v1=…» ───────────────────────────────────────────
    $ts = '';
    $v1 = '';

    foreach (explode(',', $cabeceraFirma) as $trozo) {
        $par = explode('=', trim($trozo), 2);

        if (count($par) !== 2) {
            continue;
        }

        $clave = trim($par[0]);
        $valor = trim($par[1]);

        if ($clave === 'ts') { $ts = $valor; }
        if ($clave === 'v1') { $v1 = $valor; }
    }

    if ($ts === '' || $v1 === '') {
        return ['ok' => false, 'error' => 'La cabecera de firma no trae ts y v1.'];
    }

    /*
     * Mercado Pago documenta que un `data.id` alfanumérico se pasa a
     * minúsculas antes de firmar. Los ids numéricos no cambian, así que
     * aplicarlo siempre es correcto y ahorra un caso especial.
     */
    $idParaFirmar = strtolower($dataId);

    $manifiesto = "id:{$idParaFirmar};request-id:{$requestId};ts:{$ts};";
    $esperada   = hash_hmac('sha256', $manifiesto, $secreto);

    // Tiempo constante: comparar con == filtra por el tiempo de fallo
    // cuánto coincide cada intento, y eso reconstruye la firma.
    if (!hash_equals($esperada, $v1)) {
        return ['ok' => false, 'error' => 'La firma no coincide.'];
    }

    /*
     * Ventana de antigüedad. Aquí es una precaución menor y conviene saber
     * por qué: aunque alguien reenviara un aviso capturado, lo único que
     * lograría es que volviéramos a consultar la API y aplicáramos otra
     * vez el mismo resultado, que es idempotente. Lo que de verdad
     * protege es consultar la API; esto solo evita que un aviso viejo
     * reaparezca meses después y ensucie la historia del pago.
     *
     * La ventana es holgada a propósito: los reintentos legítimos de
     * Mercado Pago llegan con firma nueva, pero el reloj del servidor
     * puede ir desviado y rechazar cobros por eso sería mucho peor.
     */
    if (ctype_digit($ts) && abs(time() - (int) $ts) > MP_VENTANA_FIRMA) {
        return ['ok' => false, 'error' => 'La firma está fuera de la ventana de tiempo.'];
    }

    return ['ok' => true, 'error' => null];
}


// =====================================================================
//  CONSULTAR Y VERIFICAR UN PAGO
// =====================================================================

/** Consulta un pago en la API de Mercado Pago. */
function mpConsultarPago(string $paymentId): array
{
    if ($paymentId === '') {
        return ['ok' => false, 'error' => 'Falta el id del pago.', 'codigo' => 0, 'datos' => []];
    }

    return mpApi('GET', '/v1/payments/' . rawurlencode($paymentId));
}

/**
 * Busca en Mercado Pago los pagos hechos contra una referencia nuestra.
 *
 * Hace falta cuando un aviso se pierde. Sin esto, el único que puede
 * desatascar un pago es el propio cliente volviendo a la pantalla de
 * retorno —que trae el id en la dirección— y eso no es un plan: la gente
 * cierra la pestaña.
 *
 * Buscando por `external_reference` se llega al pago sin conocer su id,
 * que es justo lo que le pasa a quien mira el panel al día siguiente.
 *
 * Se devuelven ordenados del más reciente al más antiguo: si alguien
 * reintentó y hubo un rechazo antes de la aprobación, interesa la última.
 *
 * @return array{ok:bool, error:?string, pagos:array}
 */
function mpBuscarPorReferencia(string $referencia): array
{
    if ($referencia === '') {
        return ['ok' => false, 'error' => 'Falta la referencia.', 'pagos' => []];
    }

    $r = mpApi('GET', '/v1/payments/search?sort=date_created&criteria=desc'
                    . '&external_reference=' . rawurlencode($referencia));

    if (!$r['ok']) {
        return ['ok' => false, 'error' => $r['error'], 'pagos' => []];
    }

    return ['ok' => true, 'error' => null, 'pagos' => $r['datos']['results'] ?? []];
}

/**
 * Pone al día un pago nuestro preguntándole a Mercado Pago.
 *
 * Es la red de seguridad de todo el cobro: la usa el botón del panel y el
 * script de conciliación. Hace exactamente lo mismo que el webhook —
 * consultar, verificar monto y moneda, aplicar— pero empezando por
 * nuestra referencia en vez de por su identificador.
 *
 * Si Mercado Pago no conoce ningún pago con esa referencia, NO es un
 * error: significa que el cliente nunca llegó a pagar. Se distingue del
 * fallo de verdad porque son cosas muy distintas para quien mira.
 *
 * @return array{ok:bool, error:?string, mensaje:string, estado:?string, aplicado:bool}
 */
function sincronizarPagoMp(array $pago): array
{
    $referencia = (string) $pago['reference'];

    $busqueda = mpBuscarPorReferencia($referencia);

    if (!$busqueda['ok']) {
        return ['ok' => false, 'error' => $busqueda['error'], 'aplicado' => false,
                'estado' => null,
                'mensaje' => 'No se pudo consultar a Mercado Pago: ' . $busqueda['error']];
    }

    if (!$busqueda['pagos']) {
        return ['ok' => true, 'error' => null, 'aplicado' => false, 'estado' => null,
                'mensaje' => 'Mercado Pago no tiene ningún pago con la referencia '
                           . $referencia . '. Lo más probable es que el cliente abriera '
                           . 'el checkout y no llegara a pagar.'];
    }

    /*
     * De todos los intentos se elige el aprobado si lo hay, y si no el más
     * reciente. Un cliente que falla dos veces y acierta a la tercera deja
     * tres pagos con la misma referencia; quedarse con el último por fecha
     * daría lo correcto casi siempre, pero «casi» no sirve cuando lo que
     * está en juego es si alguien tiene acceso o no.
     */
    $elegido = null;

    foreach ($busqueda['pagos'] as $p) {
        if (($p['status'] ?? '') === 'approved') {
            $elegido = $p;
            break;
        }
    }

    $elegido ??= $busqueda['pagos'][0];

    $verificado = verificarPagoMp((string) ($elegido['id'] ?? ''));

    if (!$verificado['ok']) {
        // El desajuste queda escrito en la historia del pago: es
        // exactamente lo que alguien tiene que ver, y perderlo en un log
        // del servidor es perderlo.
        registrarEventoPago(
            (int) $pago['id'],
            'webhook',
            'Conciliación RECHAZADA: ' . $verificado['error'],
            null
        );

        return ['ok' => false, 'error' => $verificado['error'], 'aplicado' => false,
                'estado' => (string) ($elegido['status'] ?? ''),
                'mensaje' => 'Mercado Pago respondió, pero los datos no cuadran: '
                           . $verificado['error']];
    }

    $resultado = aplicarAvisoPasarela($referencia, (string) $verificado['estado'], [
        'provider'           => 'mercadopago',
        'provider_reference' => $verificado['transaccion'],
    ]);

    $etiqueta = etiquetaEstadoPago(estadoDesdePasarela((string) $verificado['estado']))['etiqueta'];

    return [
        'ok'       => true,
        'error'    => null,
        'aplicado' => (bool) $resultado['ok'],
        'estado'   => (string) $verificado['estado'],
        'mensaje'  => sprintf(
            'Mercado Pago informa «%s» (%s). %s',
            $verificado['estado'],
            $etiqueta,
            $resultado['ok']
                ? 'Se aplicó al pago.'
                : 'No se aplicó: ' . ($resultado['error'] ?? 'sin motivo')
        ),
    ];
}

/**
 * Consulta un pago y comprueba que sea REALMENTE el que esperábamos.
 *
 * Esta es la función que impide que un pago aprobado de mil pesos abra un
 * plan de noventa y nueve mil. Comprueba, en este orden:
 *
 *   1. Que Mercado Pago conozca ese pago.
 *   2. Que su `external_reference` sea la referencia que dice el aviso.
 *   3. Que exista un pago nuestro con esa referencia.
 *   4. Que la MONEDA sea COP.
 *   5. Que el MONTO sea exactamente el que pusimos a cobrar.
 *
 * Cualquier desajuste devuelve `ok:false` con el motivo, y quien llama
 * deja constancia en la historia del pago sin conceder nada. No se
 * «corrige» ni se acepta un pago parcial: un cobro que no cuadra es un
 * asunto para una persona, no para un script.
 *
 * @return array{ok:bool, error:?string, referencia:?string,
 *               estado:?string, transaccion:?string, pago:?array}
 */
function verificarPagoMp(string $paymentId): array
{
    /**
     * Un fallo, con la forma completa.
     *
     * Antes esto se escribía `$vacio + [...]`, y estaba mal de una forma
     * silenciosa: el `+` de arrays **conserva el valor de la izquierda**
     * en las claves repetidas, así que el `'pago' => $pago` de la derecha
     * nunca ganaba al `'pago' => null` de la izquierda. Quien llamaba
     * recibía siempre `pago: null` y por eso el desajuste de monto no se
     * podía anotar en la historia del pago: no se sabía de qué pago era.
     */
    $fallo = static fn(string $error, ?array $pago = null): array => [
        'ok'          => false,
        'error'       => $error,
        'referencia'  => null,
        'estado'      => null,
        'transaccion' => null,
        'pago'        => $pago,
    ];

    $respuesta = mpConsultarPago($paymentId);

    if (!$respuesta['ok']) {
        return $fallo((string) $respuesta['error']);
    }

    $mp = $respuesta['datos'];

    $referencia = trim((string) ($mp['external_reference'] ?? ''));
    $estado     = trim((string) ($mp['status'] ?? ''));
    $moneda     = strtoupper(trim((string) ($mp['currency_id'] ?? '')));
    $montoMp    = (float) ($mp['transaction_amount'] ?? 0);

    if ($referencia === '') {
        return $fallo('El pago de Mercado Pago no trae external_reference.');
    }

    $pago = pagoPorReferencia($referencia);

    if (!$pago) {
        return $fallo("No hay ningún pago nuestro con la referencia $referencia.");
    }

    if ($moneda !== 'COP') {
        return $fallo("La moneda es $moneda y esperábamos COP.", $pago);
    }

    /*
     * Comparación de dinero. Se hace sobre enteros porque el peso no tiene
     * decimales, pero la API devuelve un número con coma flotante; se
     * redondea antes de comparar para que 99000.0 no falle contra 99000.
     */
    $esperado = (int) $pago['amount_cop'];
    $recibido = (int) round($montoMp);

    if ($recibido !== $esperado) {
        return $fallo(
            sprintf(
                'El monto no cuadra: Mercado Pago cobró %s y el pago %s es de %s.',
                precioCop($recibido), $referencia, precioCop($esperado)
            ),
            $pago
        );
    }

    return [
        'ok'          => true,
        'error'       => null,
        'referencia'  => $referencia,
        'estado'      => $estado,
        'transaccion' => (string) ($mp['id'] ?? $paymentId),
        'pago'        => $pago,
    ];
}
