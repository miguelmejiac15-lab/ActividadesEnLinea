<?php
/**
 * pagos.php — El dinero
 *
 * ─────────────────────────────────────────────────────────────────────
 *  DOS REGLAS QUE NO SE NEGOCIAN
 * ─────────────────────────────────────────────────────────────────────
 *
 *  1. El precio lo pone el servidor, siempre.
 *     Nunca se lee un monto del formulario. Si el navegador pudiera
 *     decir cuánto vale un plan, cualquiera compraría el plan Escuela
 *     por $1.000 cambiando un campo oculto. `crearPago()` recibe el
 *     slug del plan y el ciclo; el monto lo saca de la tabla `plans`.
 *
 *  2. La suscripción se concede en un solo sitio.
 *     `otorgarSuscripcion()` es la única función del proyecto que
 *     escribe en `subscriptions` al cobrar. Da igual si el pago lo
 *     confirmó un administrador a mano o un webhook de la pasarela:
 *     los dos caminos terminan aquí. Dos rutas que conceden acceso son
 *     dos reglas que mantener sincronizadas, y una de las dos siempre
 *     se queda atrás.
 *
 * Nada de esto guarda datos de tarjetas. La plataforma no los ve, no
 * los pide y no tiene dónde ponerlos: solo se archiva la referencia que
 * devuelve la pasarela.
 */

declare(strict_types=1);

// Estados de un pago. Un pago nace pendiente y muere en uno de los
// otros cuatro; ninguno vuelve a pendiente.
define('PAGO_PENDIENTE',   'pending');
define('PAGO_CONFIRMADO',  'confirmed');
define('PAGO_RECHAZADO',   'rejected');
define('PAGO_REEMBOLSADO', 'refunded');
define('PAGO_ANULADO',     'cancelled');


// =====================================================================
//  CATÁLOGOS
// =====================================================================

/**
 * Formas de pago que se aceptan.
 *
 * `cliente` marca cuáles puede elegir el usuario por su cuenta. La
 * cortesía no está en esa lista: la concede un administrador, y nadie
 * debería poder regalarse un plan a sí mismo desde la web.
 */
function metodosPago(): array
{
    return [
        'transferencia' => ['etiqueta' => 'Transferencia bancaria', 'icono' => '🏦', 'cliente' => true],
        'nequi'         => ['etiqueta' => 'Nequi o Daviplata',      'icono' => '📱', 'cliente' => true],
        'pasarela'      => ['etiqueta' => 'Tarjeta o PSE',          'icono' => '💳', 'cliente' => true],
        'efectivo'      => ['etiqueta' => 'Efectivo',               'icono' => '💵', 'cliente' => false],
        'cortesia'      => ['etiqueta' => 'Cortesía',               'icono' => '🎁', 'cliente' => false],
    ];
}

/**
 * Las formas de pago que HOY puede elegir un cliente de verdad.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ NO BASTA CON `metodosPago()`
 * ---------------------------------------------------------------------
 *
 * `metodosPago()` dice lo que la plataforma sabe cobrar. Esto dice lo
 * que se puede cobrar AHORA, con los datos que hay puestos:
 *
 *  · «Transferencia» sin número de cuenta lleva a una pantalla que pide
 *    transferir sin decir a dónde.
 *  · «Nequi» sin número, igual.
 *  · «Tarjeta o PSE» sin pasarela conectada promete un checkout que no
 *    existe.
 *
 * La pantalla de suscripción ya escondía las tres. Lo que no hacía nadie
 * era comprobarlo al RECIBIR el formulario: si llegaba un método raro,
 * el código caía de vuelta a «transferencia» aunque no hubiera cuenta, y
 * se creaba un pago que nadie podía pagar. Ahora las dos preguntan aquí.
 *
 * @return array<string, array> las mismas entradas de metodosPago()
 */
function metodosParaCliente(): array
{
    $datos = datosRecaudo();

    /*
     * El titular hace falta para las dos formas manuales. Un número de
     * cuenta sin un nombre al lado no le dice al cliente a quién le está
     * mandando su dinero, y un adulto prudente no transfiere así.
     */
    $titular = trim((string) ($datos['titular'] ?? '')) !== '';

    $hay = [
        'transferencia' => $titular && trim((string) ($datos['cuenta'] ?? '')) !== '',
        'nequi'         => $titular && trim((string) ($datos['nequi']  ?? '')) !== '',
        'pasarela'      => pasarelaConectada(),
    ];

    $lista = [];

    foreach (metodosPago() as $k => $m) {
        if (empty($m['cliente'])) {
            continue;   // la cortesía la concede un administrador
        }

        // Un método sin condición declarada se ofrece; los tres de
        // arriba, solo si sus datos están puestos.
        if (array_key_exists($k, $hay) && !$hay[$k]) {
            continue;
        }

        $lista[$k] = $m;
    }

    return $lista;
}

/** Nombre legible de una forma de pago, sin romperse ante una desconocida. */
function etiquetaMetodo(?string $metodo): string
{
    $m = metodosPago();
    return $m[$metodo]['etiqueta'] ?? ($metodo ?: '—');
}

function iconoMetodo(?string $metodo): string
{
    $m = metodosPago();
    return $m[$metodo]['icono'] ?? '•';
}

/** Estados con su nombre en español y el color del distintivo. */
function estadosPago(): array
{
    return [
        PAGO_PENDIENTE   => ['etiqueta' => 'Pendiente',   'color' => 'naranja', 'icono' => '⏳'],
        PAGO_CONFIRMADO  => ['etiqueta' => 'Confirmado',  'color' => 'verde',   'icono' => '✅'],
        PAGO_RECHAZADO   => ['etiqueta' => 'Rechazado',   'color' => 'rojo',    'icono' => '❌'],
        PAGO_REEMBOLSADO => ['etiqueta' => 'Reembolsado', 'color' => 'morado',  'icono' => '↩️'],
        PAGO_ANULADO     => ['etiqueta' => 'Anulado',     'color' => 'gris',    'icono' => '⊘'],
    ];
}

function etiquetaEstadoPago(?string $estado): array
{
    $e = estadosPago();
    return $e[$estado] ?? ['etiqueta' => (string) $estado, 'color' => 'gris', 'icono' => '•'];
}

/**
 * ¿Se corrió ya la migración de la Fase 4?
 *
 * Una instalación anterior tiene `subscriptions` pero no `payments`. Sin
 * esta comprobación, entrar a Cobros en ese caso daría un error de SQL
 * en pantalla en vez de decir qué falta por hacer.
 */
function pagosInstalados(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.tables
              WHERE table_schema = DATABASE() AND table_name = "payments"'
        );
    }

    return $listo;
}

/**
 * Modo de cobro configurado.
 *
 * 'manual' = el cliente transfiere y un administrador confirma.
 * Cualquier otro valor es el slug de una pasarela conectada.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ ESTO YA NO DECIDE NADA
 * ─────────────────────────────────────────────────────────────────────
 *
 * `pagos_modo` era excluyente: o transferencia, o pasarela. Resultó ser
 * la pregunta equivocada. En Colombia mucha familia prefiere transferir
 * aunque haya botón de tarjeta, y obligar a elegir uno de los dos pierde
 * clientes por los dos lados.
 *
 * Ahora hay dos interruptores independientes —`pagos_manual_activo` y
 * `pagos_pasarela`— y pueden estar los dos encendidos. Esta función se
 * conserva porque `aplicarAvisoPasarela()` la usa para etiquetar de dónde
 * vino un aviso, pero ya no decide qué se le ofrece al cliente: eso lo
 * responden `manualActivo()` y `pasarelaActiva()`.
 */
function modoPagos(): string
{
    $pasarela = pasarelaActiva();

    return $pasarela !== '' ? $pasarela : 'manual';
}

/**
 * Slug de la pasarela conectada, o cadena vacía si no hay ninguna.
 *
 * Se comprueba que además esté CONFIGURADA, no solo elegida: dejar el
 * ajuste en «mercadopago» sin haber pegado el access token enseñaría un
 * botón de pagar que lleva a un error. Media configuración es peor que
 * ninguna, porque parece que funciona.
 */
function pasarelaActiva(): string
{
    $slug = trim((string) ajuste('pagos_pasarela', ''));

    if ($slug === 'mercadopago') {
        return mpConfigurada() ? 'mercadopago' : '';
    }

    return '';
}

function pasarelaConectada(): bool
{
    return pasarelaActiva() !== '';
}

/**
 * ¿Se ofrece la transferencia manual?
 *
 * Encendida por defecto. Si no hay ninguna pasarela conectada se ofrece
 * igual aunque el ajuste diga que no: quedarse sin ninguna forma de
 * cobrar por un ajuste mal puesto es peor que ignorarlo.
 */
function manualActivo(): bool
{
    if (!pasarelaConectada()) {
        return true;
    }

    return ajuste('pagos_manual_activo', '1') !== '0';
}

/** ¿Hay alguna forma de cobrar lista para usarse? */
function cobroDisponible(): bool
{
    if (pasarelaConectada()) {
        return true;
    }

    return manualActivo() && recaudoConfigurado();
}

/** Datos de la cuenta de recaudo que se le muestran al cliente. */
function datosRecaudo(): array
{
    return [
        'titular'       => ajuste('pagos_titular', ''),
        'documento'     => ajuste('pagos_documento', ''),
        'banco'         => ajuste('pagos_banco', ''),
        'tipo_cuenta'   => ajuste('pagos_tipo_cuenta', ''),
        'cuenta'        => ajuste('pagos_cuenta', ''),
        'nequi'         => ajuste('pagos_nequi', ''),
        'correo'        => ajuste('pagos_correo_soporte', ''),
        'instrucciones' => ajuste('pagos_instrucciones', ''),
    ];
}

/**
 * ¿Están puestos los datos mínimos para poder cobrar?
 *
 * Se comprueba antes de enseñarle a nadie una pantalla de pago: mandar
 * a un cliente a transferir a una cuenta vacía es perderlo dos veces.
 */
function recaudoConfigurado(): bool
{
    $d = datosRecaudo();
    return $d['titular'] !== '' && ($d['cuenta'] !== '' || $d['nequi'] !== '');
}


// =====================================================================
//  PRECIOS
// =====================================================================

/** Normaliza el ciclo de facturación a uno de los dos que existen. */
function cicloValido(?string $ciclo): string
{
    return $ciclo === 'monthly' ? 'monthly' : 'yearly';
}

function etiquetaCiclo(string $ciclo): string
{
    return $ciclo === 'monthly' ? 'Mensual' : 'Anual';
}

/**
 * Precio de un plan en un ciclo, o null si ese plan no se ofrece así.
 *
 * Devolver null y no 0 es deliberado: un plan sin precio mensual no
 * cuesta cero al mes, es que no se puede contratar al mes.
 */
function montoDePlan(array $plan, string $ciclo): ?int
{
    $campo = $ciclo === 'monthly' ? 'price_monthly_cop' : 'price_yearly_cop';
    return isset($plan[$campo]) && $plan[$campo] !== null ? (int) $plan[$campo] : null;
}

/** Cuánto dura un ciclo, en formato de intervalo de MySQL. */
function intervaloCiclo(string $ciclo): string
{
    return $ciclo === 'monthly' ? '1 MONTH' : '1 YEAR';
}


// =====================================================================
//  REFERENCIA
// =====================================================================

/**
 * Genera la referencia que verá el cliente: AEL-2026-084512
 *
 * Es aleatoria y no correlativa a propósito. Una referencia secuencial
 * le cuenta a cualquier cliente cuántas ventas lleva la plataforma —y
 * al competidor también.
 *
 * Se reintenta ante una colisión, pero la garantía real es el índice
 * UNIQUE de la tabla: dos peticiones simultáneas pueden generar la
 * misma cadena y solo la base de datos puede arbitrar eso.
 */
function referenciaPago(): string
{
    for ($intento = 0; $intento < 8; $intento++) {
        $ref = sprintf('AEL-%s-%06d', date('Y'), random_int(0, 999999));
        if (!traerValor('SELECT id FROM payments WHERE reference = ?', [$ref])) {
            return $ref;
        }
    }

    // Ocho colisiones seguidas no pasan por azar. Se cae a algo único
    // por construcción antes que devolver una referencia repetida.
    return 'AEL-' . date('Y') . '-' . strtoupper(bin2hex(random_bytes(5)));
}


// =====================================================================
//  CREAR
// =====================================================================

/**
 * Crea un pago pendiente.
 *
 * No concede nada: solo deja constancia de que alguien quiere comprar
 * algo y por cuánto. El acceso llega con `confirmarPago()`.
 *
 * @param array $extra  payer_name, payer_email, payer_document,
 *                      proof_reference, provider, provider_reference, notes
 * @return array{ok:bool, error:?string, pago:?array}
 */
function crearPago(int $usuarioId, string $planSlug, string $ciclo, string $metodo = 'transferencia', array $extra = []): array
{
    $usuario = traerUno('SELECT id, name, email, status FROM users WHERE id = ?', [$usuarioId]);
    if (!$usuario) {
        return ['ok' => false, 'error' => 'El usuario no existe.', 'pago' => null];
    }
    if ($usuario['status'] !== 'active') {
        return ['ok' => false, 'error' => 'La cuenta está desactivada: no puede contratar.', 'pago' => null];
    }

    $plan = planPorSlug($planSlug);
    if (!$plan) {
        return ['ok' => false, 'error' => 'El plan no existe.', 'pago' => null];
    }
    if ($plan['slug'] === PLAN_FREE) {
        return ['ok' => false, 'error' => 'El plan gratuito no se cobra.', 'pago' => null];
    }
    if ((int) $plan['is_active'] !== 1) {
        return ['ok' => false, 'error' => 'Ese plan no está a la venta.', 'pago' => null];
    }

    $ciclo = cicloValido($ciclo);
    $monto = montoDePlan($plan, $ciclo);

    if ($monto === null) {
        return ['ok' => false, 'error' => 'Ese plan no se ofrece en esa modalidad.', 'pago' => null];
    }

    $metodos = metodosPago();
    if (!isset($metodos[$metodo])) {
        return ['ok' => false, 'error' => 'Forma de pago no reconocida.', 'pago' => null];
    }

    $referencia = referenciaPago();

    $id = insertar(
        'INSERT INTO payments
            (reference, user_id, plan_id, billing_cycle, amount_cop, status, method,
             provider, provider_reference, payer_name, payer_email, payer_document,
             proof_reference, notes)
         VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)',
        [
            $referencia,
            $usuarioId,
            (int) $plan['id'],
            $ciclo,
            $monto,
            PAGO_PENDIENTE,
            $metodo,
            $extra['provider']           ?? ($metodo === 'pasarela' ? modoPagos() : 'manual'),
            $extra['provider_reference'] ?? null,
            trim((string) ($extra['payer_name']  ?? $usuario['name'])),
            trim((string) ($extra['payer_email'] ?? $usuario['email'])),
            ($extra['payer_document'] ?? '') !== '' ? trim((string) $extra['payer_document']) : null,
            ($extra['proof_reference'] ?? '') !== '' ? trim((string) $extra['proof_reference']) : null,
            ($extra['notes'] ?? '') !== '' ? trim((string) $extra['notes']) : null,
        ]
    );

    registrarEventoPago(
        $id,
        'creado',
        sprintf('%s · %s · %s', $plan['name'], etiquetaCiclo($ciclo), precioCop($monto)),
        usuarioActualId()
    );

    return ['ok' => true, 'error' => null, 'pago' => pagoPorId($id)];
}


// =====================================================================
//  CONSULTAR
// =====================================================================

/** Columnas que toda pantalla de pagos necesita, con sus nombres ya resueltos. */
const CAMPOS_PAGO = '
    pg.*,
    u.name  AS usuario,
    u.email AS usuario_email,
    u.role  AS usuario_rol,
    p.name  AS plan,
    p.slug  AS plan_slug,
    adm.name AS confirmado_por';

const UNIONES_PAGO = '
      FROM payments pg
      JOIN users u ON u.id = pg.user_id
      JOIN plans p ON p.id = pg.plan_id
 LEFT JOIN users adm ON adm.id = pg.confirmed_by';

function pagoPorId(int $id): ?array
{
    return traerUno('SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . ' WHERE pg.id = ?', [$id]);
}

function pagoPorReferencia(string $referencia): ?array
{
    return traerUno('SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . ' WHERE pg.reference = ?', [$referencia]);
}

/** Pagos de un usuario, del más reciente al más antiguo. */
function pagosDeUsuario(int $usuarioId, int $limite = 30): array
{
    return traerTodo(
        'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
          WHERE pg.user_id = ?
       ORDER BY pg.created_at DESC
          LIMIT ' . max(1, min(200, $limite)),
        [$usuarioId]
    );
}

/**
 * El pago pendiente más reciente de un usuario, si lo hay.
 *
 * Sirve para no crear un segundo pago cuando alguien vuelve a la
 * pantalla de compra: acabaría transfiriendo una vez y dejando dos
 * referencias abiertas, y quien confirme no sabría cuál cerrar.
 */
function pagoPendienteDe(int $usuarioId, ?int $planId = null): ?array
{
    $sql = 'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
             WHERE pg.user_id = ? AND pg.status = ?';
    $params = [$usuarioId, PAGO_PENDIENTE];

    if ($planId !== null) {
        $sql .= ' AND pg.plan_id = ?';
        $params[] = $planId;
    }

    return traerUno($sql . ' ORDER BY pg.created_at DESC LIMIT 1', $params);
}

/** Historia completa de un pago, del primer evento al último. */
function eventosDePago(int $pagoId): array
{
    return traerTodo(
        'SELECT e.*, u.name AS actor
           FROM payment_events e
      LEFT JOIN users u ON u.id = e.actor_id
          WHERE e.payment_id = ?
       ORDER BY e.created_at, e.id',
        [$pagoId]
    );
}

/** Deja escrito qué pasó con un pago y quién lo hizo. */
function registrarEventoPago(int $pagoId, string $tipo, ?string $detalle = null, ?int $actorId = null): void
{
    insertar(
        'INSERT INTO payment_events (payment_id, type, detail, actor_id) VALUES (?, ?, ?, ?)',
        [$pagoId, mb_substr($tipo, 0, 30), $detalle, $actorId]
    );
}


// =====================================================================
//  CONCEDER ACCESO
// =====================================================================

/**
 * Crea o extiende la suscripción de un usuario. ÚNICO punto de entrada.
 *
 * Si ya tiene una suscripción vigente al MISMO plan, no se crea otra:
 * se le suma el tiempo a la que tiene. Crear una segunda dejaría dos
 * filas activas para la misma persona, y `suscripcionVigente()` —que
 * ordena por vencimiento y toma una— usaría la de fecha más lejana
 * ignorando la otra. El cliente habría pagado un año que nadie cuenta.
 *
 * Al extender se parte de la fecha de vencimiento actual, no de hoy:
 * renovar con un mes de antelación no puede costarle al cliente ese mes.
 *
 * @return int  Id de la suscripción vigente después de la operación.
 */
function otorgarSuscripcion(
    int $usuarioId,
    array $plan,
    string $ciclo,
    int $monto,
    string $proveedor = 'manual',
    ?string $referencia = null
): int {
    $ciclo      = cicloValido($ciclo);
    $intervalo  = intervaloCiclo($ciclo);
    $gracia     = max(0, (int) ajuste('pagos_dias_gracia', '0'));

    $vigente = traerUno(
        "SELECT id, expires_at FROM subscriptions
          WHERE user_id = ? AND plan_id = ? AND status = 'active'
            AND (expires_at IS NULL OR expires_at > NOW())
       ORDER BY expires_at DESC LIMIT 1",
        [$usuarioId, (int) $plan['id']]
    );

    if ($vigente) {
        // Se parte de lo que queda vigente, nunca de una fecha pasada.
        ejecutar(
            "UPDATE subscriptions
                SET expires_at = GREATEST(COALESCE(expires_at, NOW()), NOW())
                                 + INTERVAL $intervalo + INTERVAL ? DAY,
                    billing_cycle     = ?,
                    amount_cop        = ?,
                    payment_provider  = ?,
                    payment_reference = ?
              WHERE id = ?",
            [$gracia, $ciclo, $monto, $proveedor, $referencia, (int) $vigente['id']]
        );

        return (int) $vigente['id'];
    }

    return insertar(
        "INSERT INTO subscriptions
            (user_id, plan_id, billing_cycle, amount_cop, status,
             starts_at, expires_at, payment_provider, payment_reference)
         VALUES (?, ?, ?, ?, 'active', NOW(), NOW() + INTERVAL $intervalo + INTERVAL ? DAY, ?, ?)",
        [$usuarioId, (int) $plan['id'], $ciclo, $monto, $gracia, $proveedor, $referencia]
    );
}


// =====================================================================
//  CAMBIOS DE ESTADO
// =====================================================================

/**
 * Confirma un pago y concede el acceso.
 *
 * Es idempotente: confirmar dos veces el mismo pago no regala dos años.
 * Importa más de lo que parece — un webhook de pasarela reintenta el
 * aviso cuando no recibe respuesta a tiempo, y ese reintento llega con
 * la red lenta justo cuando el administrador está pulsando «Confirmar».
 *
 * Las dos escrituras —marcar el pago y conceder la suscripción— van en
 * una transacción. Si la segunda falla, la primera se deshace: un pago
 * marcado como confirmado sin suscripción detrás es un cliente que pagó
 * y no tiene acceso, y nada en el panel lo delataría.
 */
function confirmarPago(int $id, ?int $adminId = null, array $datos = []): array
{
    $pago = pagoPorId($id);

    if (!$pago) {
        return ['ok' => false, 'error' => 'El pago no existe.'];
    }
    if ($pago['status'] === PAGO_CONFIRMADO) {
        return ['ok' => true, 'error' => null, 'repetido' => true];
    }
    if (in_array($pago['status'], [PAGO_REEMBOLSADO, PAGO_ANULADO], true)) {
        return ['ok' => false, 'error' => 'Un pago ' . etiquetaEstadoPago($pago['status'])['etiqueta']
                                          . ' no se puede confirmar. Registra uno nuevo.'];
    }

    $plan = traerUno('SELECT * FROM plans WHERE id = ?', [(int) $pago['plan_id']]);
    if (!$plan) {
        return ['ok' => false, 'error' => 'El plan de este pago ya no existe.'];
    }

    $pdo = db();
    $propia = !$pdo->inTransaction();

    if ($propia) {
        $pdo->beginTransaction();
    }

    try {
        $suscripcionId = otorgarSuscripcion(
            (int) $pago['user_id'],
            $plan,
            (string) $pago['billing_cycle'],
            (int) $pago['amount_cop'],
            (string) ($pago['provider'] ?: 'manual'),
            (string) ($pago['provider_reference'] ?: $pago['reference'])
        );

        ejecutar(
            'UPDATE payments
                SET status = ?, subscription_id = ?, confirmed_by = ?, confirmed_at = NOW(),
                    provider_reference = COALESCE(?, provider_reference),
                    provider_status    = COALESCE(?, provider_status)
              WHERE id = ?',
            [
                PAGO_CONFIRMADO,
                $suscripcionId,
                $adminId,
                ($datos['provider_reference'] ?? '') !== '' ? $datos['provider_reference'] : null,
                ($datos['provider_status'] ?? '') !== '' ? $datos['provider_status'] : null,
                $id,
            ]
        );

        registrarEventoPago(
            $id,
            'confirmado',
            trim((string) ($datos['motivo'] ?? '')) !== ''
                ? (string) $datos['motivo']
                : 'Acceso concedido hasta ' . (traerValor('SELECT expires_at FROM subscriptions WHERE id = ?', [$suscripcionId]) ?? '—'),
            $adminId
        );

        if ($propia) {
            $pdo->commit();
        }
    } catch (Throwable $e) {
        if ($propia && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Fallo al confirmar el pago ' . $pago['reference'] . ': ' . $e->getMessage());
        return ['ok' => false, 'error' => 'No se pudo confirmar el pago. Quedó registrado en el log.'];
    }

    return ['ok' => true, 'error' => null, 'repetido' => false];
}

/**
 * Rechaza un pago: el dinero nunca llegó, o llegó mal.
 * Exige motivo — «rechazado» sin más no le sirve a nadie dentro de
 * seis meses, y menos al cliente que llama a preguntar.
 */
function rechazarPago(int $id, string $motivo, ?int $adminId = null): array
{
    return cambiarEstadoPago($id, PAGO_RECHAZADO, $motivo, $adminId, 'rechazado');
}

/** Anula un pago pendiente: el cliente desistió, nadie transfirió nada. */
function anularPago(int $id, string $motivo, ?int $adminId = null): array
{
    return cambiarEstadoPago($id, PAGO_ANULADO, $motivo, $adminId, 'anulado');
}

/**
 * Reembolsa un pago ya confirmado y RETIRA el acceso.
 *
 * Devolver el dinero y dejar la suscripción viva es el error que nadie
 * detecta hasta que cuadra las cuentas: el cliente sigue entrando al
 * catálogo completo gratis. Por eso el reembolso cancela la suscripción
 * que este pago concedió.
 */
function reembolsarPago(int $id, string $motivo, ?int $adminId = null): array
{
    $pago = pagoPorId($id);

    if (!$pago) {
        return ['ok' => false, 'error' => 'El pago no existe.'];
    }
    if ($pago['status'] !== PAGO_CONFIRMADO) {
        return ['ok' => false, 'error' => 'Solo se puede reembolsar un pago confirmado.'];
    }
    if (trim($motivo) === '') {
        return ['ok' => false, 'error' => 'Escribe el motivo del reembolso.'];
    }

    $pdo = db();
    $propia = !$pdo->inTransaction();
    if ($propia) {
        $pdo->beginTransaction();
    }

    try {
        if ($pago['subscription_id']) {
            ejecutar(
                "UPDATE subscriptions SET status = 'cancelled' WHERE id = ?",
                [(int) $pago['subscription_id']]
            );
        }

        ejecutar('UPDATE payments SET status = ? WHERE id = ?', [PAGO_REEMBOLSADO, $id]);

        registrarEventoPago($id, 'reembolsado', trim($motivo), $adminId);

        if ($propia) {
            $pdo->commit();
        }
    } catch (Throwable $e) {
        if ($propia && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('Fallo al reembolsar el pago ' . $pago['reference'] . ': ' . $e->getMessage());
        return ['ok' => false, 'error' => 'No se pudo registrar el reembolso.'];
    }

    return ['ok' => true, 'error' => null];
}

/** Motor común de los cambios de estado que no conceden ni retiran acceso. */
function cambiarEstadoPago(int $id, string $nuevo, string $motivo, ?int $adminId, string $evento): array
{
    $pago = pagoPorId($id);

    if (!$pago) {
        return ['ok' => false, 'error' => 'El pago no existe.'];
    }
    if ($pago['status'] === PAGO_CONFIRMADO) {
        return ['ok' => false, 'error' => 'Este pago ya está confirmado. Si hay que deshacerlo, usa el reembolso.'];
    }
    if ($pago['status'] === $nuevo) {
        return ['ok' => true, 'error' => null];
    }
    if (trim($motivo) === '') {
        return ['ok' => false, 'error' => 'Escribe el motivo: sin él, nadie sabrá dentro de seis meses por qué se cerró.'];
    }

    ejecutar('UPDATE payments SET status = ? WHERE id = ?', [$nuevo, $id]);
    registrarEventoPago($id, $evento, trim($motivo), $adminId);

    return ['ok' => true, 'error' => null];
}

/**
 * Edita los datos administrativos de un pago pendiente.
 *
 * El monto se puede corregir —una transferencia llega con una diferencia
 * de $500 por la comisión más veces de lo que gustaría— pero solo
 * mientras el pago siga pendiente. Cambiar el monto de un pago ya
 * confirmado reescribiría un informe contable ya emitido.
 */
function actualizarPago(int $id, array $d, ?int $adminId = null): array
{
    $pago = pagoPorId($id);

    if (!$pago) {
        return ['ok' => false, 'error' => 'El pago no existe.'];
    }

    $campos = [];
    $valores = [];
    $cambios = [];

    // Solo estos campos son editables, y solo con este nombre. Una lista
    // blanca evita que un campo inventado en el formulario llegue al UPDATE.
    $editables = [
        'method'            => 'forma de pago',
        'provider'          => 'pasarela',
        'provider_reference'=> 'referencia de la pasarela',
        'payer_name'        => 'nombre de facturación',
        'payer_email'       => 'correo de facturación',
        'payer_document'    => 'documento',
        'proof_reference'   => 'comprobante',
        'notes'             => 'nota interna',
    ];

    foreach ($editables as $campo => $nombre) {
        if (!array_key_exists($campo, $d)) {
            continue;
        }
        $valor = trim((string) $d[$campo]);
        $valor = $valor === '' ? null : $valor;

        if ($valor === ($pago[$campo] ?? null)) {
            continue;
        }

        $campos[]  = "`$campo` = ?";
        $valores[] = $valor;
        $cambios[] = $nombre;
    }

    if (array_key_exists('amount_cop', $d) && $pago['status'] === PAGO_PENDIENTE) {
        $monto = max(0, (int) $d['amount_cop']);
        if ($monto !== (int) $pago['amount_cop']) {
            $campos[]  = '`amount_cop` = ?';
            $valores[] = $monto;
            $cambios[] = 'monto (' . precioCop((int) $pago['amount_cop']) . ' → ' . precioCop($monto) . ')';
        }
    }

    if (array_key_exists('billing_cycle', $d) && $pago['status'] === PAGO_PENDIENTE) {
        $ciclo = cicloValido((string) $d['billing_cycle']);
        if ($ciclo !== $pago['billing_cycle']) {
            $campos[]  = '`billing_cycle` = ?';
            $valores[] = $ciclo;
            $cambios[] = 'modalidad → ' . etiquetaCiclo($ciclo);
        }
    }

    if (!$campos) {
        return ['ok' => true, 'error' => null, 'sin_cambios' => true];
    }

    $valores[] = $id;
    ejecutar('UPDATE payments SET ' . implode(', ', $campos) . ' WHERE id = ?', $valores);

    registrarEventoPago($id, 'editado', 'Se cambió: ' . implode(', ', $cambios), $adminId);

    return ['ok' => true, 'error' => null, 'sin_cambios' => false];
}

/** Añade una nota a la historia del pago sin cambiar su estado. */
function anotarPago(int $id, string $texto, ?int $adminId = null): array
{
    if (trim($texto) === '') {
        return ['ok' => false, 'error' => 'La nota está vacía.'];
    }
    if (!pagoPorId($id)) {
        return ['ok' => false, 'error' => 'El pago no existe.'];
    }

    registrarEventoPago($id, 'nota', trim($texto), $adminId);
    return ['ok' => true, 'error' => null];
}


// =====================================================================
//  PASARELA · PUENTE
// =====================================================================

/**
 * Traduce el estado que devuelve una pasarela al del proyecto.
 *
 * Cada proveedor lo llama distinto (APPROVED, approved, paid, succeeded)
 * y añadir uno nuevo debe ser añadir una fila aquí, no tocar el webhook.
 * Lo que no se reconoce se deja pendiente a propósito: ante la duda, que
 * lo mire una persona antes que conceder acceso por error.
 */
function estadoDesdePasarela(string $estadoCrudo): string
{
    $mapa = [
        'approved'   => PAGO_CONFIRMADO,
        'paid'       => PAGO_CONFIRMADO,
        'succeeded'  => PAGO_CONFIRMADO,
        'completed'  => PAGO_CONFIRMADO,
        'declined'   => PAGO_RECHAZADO,
        'rejected'   => PAGO_RECHAZADO,
        'failed'     => PAGO_RECHAZADO,
        'error'      => PAGO_RECHAZADO,
        'voided'     => PAGO_ANULADO,
        'cancelled'  => PAGO_ANULADO,
        'canceled'   => PAGO_ANULADO,
        'refunded'   => PAGO_REEMBOLSADO,

        /*
         * Un contracargo es el cliente pidiéndole a su banco que revierta
         * el cobro. El dinero se va igual que en un reembolso, así que el
         * acceso también: tratarlo como «pendiente» dejaría el catálogo
         * abierto a alguien que ya recuperó su plata.
         */
        'charged_back'    => PAGO_REEMBOLSADO,
        'chargeback'      => PAGO_REEMBOLSADO,

        /*
         * `in_process` y `pending` son de Mercado Pago y significan que
         * todavía no se sabe —una transferencia PSE en curso, un efectivo
         * sin abonar—. Caen en el `?? PAGO_PENDIENTE` de abajo, pero se
         * escriben aquí para que quede claro que no son un olvido.
         */
        'in_process' => PAGO_PENDIENTE,
        'pending'    => PAGO_PENDIENTE,
        'authorized' => PAGO_PENDIENTE,
    ];

    return $mapa[strtolower(trim($estadoCrudo))] ?? PAGO_PENDIENTE;
}

/**
 * Aplica el aviso de una pasarela sobre un pago.
 *
 * Se llama desde `api/webhook-pago.php` y desde ningún otro sitio. El
 * webhook se ocupa de comprobar que el aviso es auténtico; esta función
 * se ocupa de qué hacer con él.
 */
function aplicarAvisoPasarela(string $referencia, string $estadoCrudo, array $datos = []): array
{
    $pago = pagoPorReferencia($referencia);

    if (!$pago) {
        return ['ok' => false, 'error' => 'No hay ningún pago con esa referencia.'];
    }

    $estado = estadoDesdePasarela($estadoCrudo);

    ejecutar(
        'UPDATE payments
            SET provider           = COALESCE(?, provider),
                provider_reference = COALESCE(?, provider_reference),
                provider_status    = ?
          WHERE id = ?',
        [
            ($datos['provider'] ?? '') !== '' ? $datos['provider'] : null,
            ($datos['provider_reference'] ?? '') !== '' ? $datos['provider_reference'] : null,
            mb_substr($estadoCrudo, 0, 40),
            (int) $pago['id'],
        ]
    );

    registrarEventoPago(
        (int) $pago['id'],
        'webhook',
        sprintf('La pasarela informó «%s» → %s', $estadoCrudo, etiquetaEstadoPago($estado)['etiqueta']),
        null
    );

    if ($estado === PAGO_CONFIRMADO) {
        return confirmarPago((int) $pago['id'], null, ['provider_status' => $estadoCrudo]);
    }

    if ($estado === PAGO_RECHAZADO) {
        return rechazarPago((int) $pago['id'], 'Rechazado por la pasarela: ' . $estadoCrudo, null);
    }

    if ($estado === PAGO_ANULADO) {
        return anularPago((int) $pago['id'], 'Anulado en la pasarela: ' . $estadoCrudo, null);
    }

    /*
     * REEMBOLSO Y CONTRACARGO.
     *
     * Faltaba esta rama, y era el agujero más caro del cobro: un
     * `refunded` o un `charged_back` caía en el «pendiente o desconocido»
     * de abajo y no hacía nada. El cliente recuperaba su dinero del banco
     * y **conservaba el acceso al catálogo**, sin que nadie se enterara.
     *
     * Un contracargo es el cliente pidiéndole a su banco que revierta el
     * cobro. El dinero se va igual que en un reembolso, así que el acceso
     * también se va. Si fue un error, se vuelve a conceder a mano — que es
     * la dirección correcta en la que equivocarse.
     */
    if ($estado === PAGO_REEMBOLSADO) {
        return reembolsarPago(
            (int) $pago['id'],
            'La pasarela informó «' . $estadoCrudo . '»: el dinero se devolvió.',
            null
        );
    }

    // Pendiente o desconocido: queda registrado y esperando a una persona.
    return ['ok' => true, 'error' => null, 'pendiente' => true];
}
