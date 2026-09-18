<?php
/**
 * puesta-en-marcha.php — ¿Está la plataforma lista para cobrar?
 *
 * Una lista de comprobación que se calcula sola, no una escrita a mano
 * que alguien tenga que acordarse de actualizar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ ESTO EXISTE
 * ─────────────────────────────────────────────────────────────────────
 *
 * El cobro tiene diez piezas —credenciales, webhook, precios, datos de
 * recaudo, correo, dirección pública— y **basta que falte una para que
 * no entre un peso**. Casi todas fallan en silencio: el botón de pagar
 * simplemente no aparece, o aparece y el acceso no se concede nunca.
 *
 * Buscar cuál de las diez falta, a mano y por primera vez, es una tarde
 * perdida. Esto la responde en un vistazo y, sobre todo, distingue
 * **lo que impide cobrar** de lo que solo conviene.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  NO CAMBIA NADA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Solo lee y opina. Ninguna comprobación escribe en la base ni llama a
 * Mercado Pago: probar la conexión de verdad es un botón aparte, porque
 * gasta una llamada a su API y no debe ocurrir cada vez que alguien abre
 * una página.
 */

declare(strict_types=1);


/** Gravedad de cada punto. */
const PM_BLOQUEA = 'bloquea';   // sin esto no entra dinero
const PM_CONVIENE = 'conviene'; // se puede cobrar, pero algo va a doler
const PM_LISTO    = 'listo';


/**
 * Un punto de la lista.
 *
 * @param bool $ok        ¿está resuelto?
 * @param string $grave   PM_BLOQUEA o PM_CONVIENE si NO lo está
 */
function pmPunto(string $titulo, bool $ok, string $grave, string $queHacer,
                 string $donde = '', string $detalle = ''): array
{
    return [
        'titulo'  => $titulo,
        'ok'      => $ok,
        'estado'  => $ok ? PM_LISTO : $grave,
        'hacer'   => $queHacer,
        'donde'   => $donde,
        'detalle' => $detalle,
    ];
}

/**
 * La lista entera, por bloques.
 *
 * @return array<string, array{titulo:string, puntos:array}>
 */
function listaDePuestaEnMarcha(): array
{
    $bloques = [];

    // ── 1. Qué se vende ──────────────────────────────────────────────
    $planesCobrables = traerTodo(
        'SELECT slug, name, price_monthly_cop, price_yearly_cop
           FROM plans
          WHERE is_active = 1 AND slug <> "free"
            AND (price_monthly_cop > 0 OR price_yearly_cop > 0)'
    );

    $sinPrecio = traerTodo(
        'SELECT slug, name FROM plans
          WHERE is_active = 1 AND slug <> "free"
            AND COALESCE(price_monthly_cop, 0) = 0
            AND COALESCE(price_yearly_cop, 0) = 0'
    );

    $bloques['catalogo'] = [
        'titulo' => 'Qué se vende',
        'puntos' => [
            pmPunto(
                'Hay al menos un plan de pago con precio',
                $planesCobrables !== [],
                PM_BLOQUEA,
                'Ponle precio a un plan. Sin precio no hay nada que cobrar y la página '
                . 'de planes no ofrece el botón.',
                'admin/planes/',
                $planesCobrables
                    ? count($planesCobrables) . ' plan(es) con precio: '
                      . implode(', ', array_column($planesCobrables, 'name'))
                    : ''
            ),
            pmPunto(
                'Ningún plan activo se quedó sin precio',
                $sinPrecio === [],
                PM_CONVIENE,
                'Un plan activo a cero se le muestra al cliente y no se puede comprar. '
                . 'O le pones precio, o lo desactivas.',
                'admin/planes/',
                $sinPrecio ? 'Sin precio: ' . implode(', ', array_column($sinPrecio, 'name')) : ''
            ),
            pmPunto(
                'El catálogo tiene contenido publicado',
                (int) traerValor('SELECT COUNT(*) FROM activities WHERE status = "published"') > 0,
                PM_BLOQUEA,
                'Siembra el contenido antes de cobrar por él.',
                'admin/actividades/',
                traerValor('SELECT COUNT(*) FROM activities WHERE status = "published"')
                    . ' actividades · '
                    . traerValor('SELECT COUNT(*) FROM activity_stations') . ' estaciones'
            ),
        ],
    ];

    // ── 2. Por dónde entra el dinero ─────────────────────────────────
    $puntosCobro = [
        pmPunto(
            'Hay alguna forma de cobrar encendida',
            cobroDisponible(),
            PM_BLOQUEA,
            'Conecta Mercado Pago o completa los datos de la cuenta de recaudo. '
            . 'Con ninguna de las dos, la página de pago no tiene qué ofrecer.',
            'admin/pagos/ajustes.php'
        ),
    ];

    if (manualActivo()) {
        $puntosCobro[] = pmPunto(
            'Datos de la transferencia completos',
            recaudoConfigurado(),
            PM_BLOQUEA,
            'Hacen falta el titular y una cuenta o un Nequi. El cliente tiene que saber '
            . 'a quién le transfiere.',
            'admin/pagos/ajustes.php'
        );

        $puntosCobro[] = pmPunto(
            'Hay correo para recibir los comprobantes',
            trim((string) ajuste('pagos_correo_soporte', '')) !== '',
            PM_CONVIENE,
            'Sin él, quien transfiere no sabe a dónde mandar la foto y el pago se queda '
            . 'pendiente para siempre.',
            'admin/pagos/ajustes.php'
        );
    }

    $bloques['cobro'] = ['titulo' => 'Por dónde entra el dinero', 'puntos' => $puntosCobro];

    // ── 3. Mercado Pago ──────────────────────────────────────────────
    $urlHook   = url('api/webhook-pago.php');
    $hookLocal = mpUrlEsLocal($urlHook);
    $elegida   = pasarelaActiva() === 'mercadopago';

    /*
     * ─────────────────────────────────────────────────────────────────
     *  EL WEBHOOK NO IMPIDE COBRAR, PERO NO PUEDE FALTAR EN PRODUCCIÓN
     * ─────────────────────────────────────────────────────────────────
     *
     * Sin webhook el dinero entra igual: al volver del checkout, la
     * pantalla de retorno le pregunta a la API y concede el acceso. Lo
     * que se pierde es el caso en que el cliente cierra la pestaña — ahí
     * el pago queda pendiente hasta que alguien concilie.
     *
     * En pruebas eso es exactamente lo que toca (en localhost no puede
     * ser de otra forma). Con dinero real es otra cosa: un cliente que
     * paga y no recibe acceso es una devolución y una reseña mala.
     *
     * Por eso la gravedad depende del ambiente en vez de ser fija. Decir
     * «esto impide cobrar» cuando no lo impide gasta la única señal roja
     * que tiene la lista.
     */
    $graveHook = $elegida && !mpEsPruebas() ? PM_BLOQUEA : PM_CONVIENE;

    $bloques['mercadopago'] = [
        'titulo' => 'Mercado Pago',
        'puntos' => [
            pmPunto(
                'Está elegida como pasarela',
                $elegida,
                PM_CONVIENE,
                'Mientras no la elijas, solo se cobra por transferencia. Las dos pueden '
                . 'estar encendidas a la vez.',
                'admin/pagos/ajustes.php'
            ),
            pmPunto(
                'Access token puesto',
                mpAccessToken() !== '',
                $elegida ? PM_BLOQUEA : PM_CONVIENE,
                'Se saca de Mercado Pago → Tus integraciones → tu aplicación → '
                . 'Credenciales. Empieza por las de PRUEBA.',
                'admin/pagos/ajustes.php',
                mpAccessToken() !== '' ? mpEnmascarar(mpAccessToken()) : ''
            ),
            pmPunto(
                'Public key puesta',
                mpPublicKey() !== '',
                PM_CONVIENE,
                'La usa el navegador en el checkout.',
                'admin/pagos/ajustes.php'
            ),
            pmPunto(
                'Clave secreta del webhook puesta',
                mpWebhookConfigurado(),
                $graveHook,
                'La genera Mercado Pago al dar de alta la notificación. Sin ella sus '
                . 'avisos se rechazan. El cobro funciona igual —la pantalla de retorno '
                . 'consulta la API— pero quien cierre la pestaña se queda esperando.',
                'admin/pagos/ajustes.php'
            ),
            pmPunto(
                'La dirección del webhook es pública',
                !$hookLocal,
                $graveHook,
                'Mercado Pago no puede llamar a localhost: desde sus servidores, '
                . '«localhost» son ellos. Publica el sitio y pon su dirección real en '
                . 'URL_BASE, dentro de config/config.php.',
                '',
                $urlHook
            ),
            pmPunto(
                'Todavía en ambiente de PRUEBAS',
                mpEsPruebas(),
                PM_CONVIENE,
                'Estás en producción: los cobros mueven dinero real. Si aún estás '
                . 'probando, vuelve a pruebas.',
                'admin/pagos/ajustes.php',
                mpEsPruebas() ? 'Correcto mientras pruebas' : '⚠ COBRANDO DE VERDAD'
            ),

            /*
             * El punto que de verdad puede costar dinero.
             *
             * La casilla «ambiente de pruebas» dice lo que alguien CREE.
             * Quien sabe si la cuenta cobra de verdad es Mercado Pago, y
             * solo lo dice si se le pregunta. Hasta entonces no se puede
             * afirmar ni lo uno ni lo otro, y por eso esto pide
             * comprobarla en vez de dar por buena la casilla.
             */
            pmPunto(
                'La cuenta está comprobada contra Mercado Pago',
                mpCuentaConocida() !== null,
                PM_CONVIENE,
                'Pulsa «Probar la conexión»: hasta entonces la plataforma solo sabe lo '
                . 'que dice la casilla de ambiente, y el prefijo del token no basta — '
                . 'una app dentro de un usuario de prueba emite credenciales APP_USR- '
                . 'que no mueven un peso.',
                'admin/pagos/ajustes.php',
                (static function (): string {
                    $c = mpCuentaConocida();

                    if ($c === null) {
                        return '';
                    }

                    return sprintf('%s · %s · cuenta de %s · comprobada el %s',
                        $c['apodo'], $c['pais'],
                        $c['prueba'] === null ? '¿?' : ($c['prueba'] ? 'PRUEBA' : 'PRODUCCIÓN'),
                        $c['visto']);
                })()
            ),

            pmPunto(
                'Sin riesgo de cobrar de verdad creyendo que se prueba',
                !mpRiesgoDeCobroReal(),
                PM_BLOQUEA,
                '⚠ La casilla dice «pruebas» pero Mercado Pago dice que esta cuenta es '
                . 'REAL. Cada cobro va a mover dinero de verdad. Apaga la casilla si es '
                . 'lo que quieres, o cambia a credenciales de prueba.',
                'admin/pagos/ajustes.php'
            ),
        ],
    ];

    // ── 4. Lo que hace falta alrededor ───────────────────────────────
    $correoListo = trim((string) ajuste('correo_host', '')) !== ''
                || trim((string) ajuste('correo_modo', '')) === 'php';

    $bloques['alrededor'] = [
        'titulo' => 'Lo que hace falta alrededor',
        'puntos' => [
            pmPunto(
                'El correo sale',
                $correoListo,
                PM_CONVIENE,
                'Sin correo nadie puede recuperar su contraseña y nadie recibe el recibo. '
                . 'No impide cobrar; impide sostenerlo.',
                'admin/correo.php'
            ),
            pmPunto(
                'El sitio no está en localhost',
                !mpUrlEsLocal(URL_BASE),
                PM_CONVIENE,
                'En localhost se puede probar todo menos recibir avisos de Mercado Pago '
                . 'y enviar enlaces que alguien pueda abrir.',
                '',
                URL_BASE
            ),
            pmPunto(
                'Hay un administrador con correo real',
                (int) traerValor(
                    'SELECT COUNT(*) FROM users
                      WHERE role = "admin" AND status = "active"
                        AND email NOT LIKE "%.local"'
                ) > 0,
                PM_CONVIENE,
                'Si la única cuenta de administración tiene un correo que no existe, '
                . 'perderla es perder el panel.',
                'admin/usuarios/'
            ),
        ],
    ];

    return $bloques;
}

/**
 * Resume la lista en un semáforo.
 *
 * @return array{bloquean:int, convienen:int, listos:int, total:int, puede_cobrar:bool}
 */
function resumenPuestaEnMarcha(array $bloques): array
{
    $b = $c = $l = 0;

    foreach ($bloques as $bloque) {
        foreach ($bloque['puntos'] as $p) {
            if ($p['estado'] === PM_LISTO)        { $l++; }
            elseif ($p['estado'] === PM_BLOQUEA)  { $b++; }
            else                                   { $c++; }
        }
    }

    return [
        'bloquean'     => $b,
        'convienen'    => $c,
        'listos'       => $l,
        'total'        => $b + $c + $l,
        'puede_cobrar' => $b === 0,
    ];
}
