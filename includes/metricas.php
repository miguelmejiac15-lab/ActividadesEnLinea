<?php
/**
 * metricas.php — Las cifras del negocio
 *
 * Todas las consultas del panel de métricas viven aquí y no dentro de
 * las páginas. La razón es concreta: «cuántos usuarios premium hay» se
 * pregunta en el resumen, en el panel de cobros y en el informe, y si
 * cada pantalla escribe su propio SQL, tarde o temprano tres pantallas
 * dan tres números distintos y ninguna está claramente equivocada.
 *
 * Igual que en gamificación, aquí NO se guarda ningún contador. No hay
 * tabla de «ingresos del mes» ni columna `total_usuarios`. Todo se
 * calcula sobre `payments`, `subscriptions` y `users`, que son los que
 * saben la verdad. Un total guardado se desincroniza en cuanto un pago
 * se reembolsa, y a partir de ahí el informe miente sin avisar.
 *
 * DEFINICIONES —conviene fijarlas, porque cada una se puede leer de dos
 * maneras y la diferencia se nota en la cifra:
 *
 *   · Usuario premium  = tiene una suscripción ACTIVA y con fecha futura.
 *                        No basta el estado: una activa vencida no cuenta.
 *   · Ingreso          = pago en estado `confirmed`. Los pendientes no son
 *                        dinero todavía, y los reembolsados dejaron de serlo.
 *   · MRR              = ingreso mensual normalizado de lo vigente. Un plan
 *                        anual de $99.000 aporta $8.250 al mes, no $99.000.
 *   · Conversión       = usuarios premium ÷ usuarios registrados activos.
 */

declare(strict_types=1);


// =====================================================================
//  RESUMEN
// =====================================================================

/**
 * Las cifras de portada, todas de una vez.
 *
 * Se calcula entera y se cachea en memoria porque el panel la pinta
 * arriba y luego vuelve a necesitar varias de sus cifras al dibujar las
 * gráficas.
 */
function resumenNegocio(): array
{
    static $cache = null;

    if ($cache !== null) {
        return $cache;
    }

    $usuarios       = (int) traerValor('SELECT COUNT(*) FROM users WHERE role <> "admin"');
    $usuariosActivos= (int) traerValor('SELECT COUNT(*) FROM users WHERE role <> "admin" AND status = "active"');

    $premium = (int) traerValor(
        'SELECT COUNT(DISTINCT s.user_id)
           FROM subscriptions s
           JOIN users u ON u.id = s.user_id
          WHERE s.status = "active" AND (s.expires_at IS NULL OR s.expires_at > NOW())
            AND u.role <> "admin"'
    );

    $ingresoTotal = (int) traerValor(
        'SELECT COALESCE(SUM(amount_cop), 0) FROM payments WHERE status = "confirmed"'
    );

    $ingresoMes = (int) traerValor(
        'SELECT COALESCE(SUM(amount_cop), 0) FROM payments
          WHERE status = "confirmed"
            AND confirmed_at >= DATE_FORMAT(NOW(), "%Y-%m-01")'
    );

    $ingresoMesAnterior = (int) traerValor(
        'SELECT COALESCE(SUM(amount_cop), 0) FROM payments
          WHERE status = "confirmed"
            AND confirmed_at >= DATE_FORMAT(NOW() - INTERVAL 1 MONTH, "%Y-%m-01")
            AND confirmed_at <  DATE_FORMAT(NOW(), "%Y-%m-01")'
    );

    $pagosConfirmados = (int) traerValor('SELECT COUNT(*) FROM payments WHERE status = "confirmed"');

    // MRR: lo anual se reparte entre doce. Sumar el valor completo de una
    // anualidad al mes en que se cobró convierte la gráfica en un pico
    // solitario y hace imposible comparar meses.
    $mrr = (int) round((float) traerValor(
        'SELECT COALESCE(SUM(CASE WHEN s.billing_cycle = "monthly"
                                  THEN s.amount_cop
                                  ELSE s.amount_cop / 12 END), 0)
           FROM subscriptions s
          WHERE s.status = "active" AND (s.expires_at IS NULL OR s.expires_at > NOW())'
    ));

    $cache = [
        'usuarios'          => $usuarios,
        'usuarios_activos'  => $usuariosActivos,
        'premium'           => $premium,
        'gratuitos'         => max(0, $usuariosActivos - $premium),
        'nuevos_7'          => (int) traerValor('SELECT COUNT(*) FROM users WHERE role <> "admin" AND created_at >= NOW() - INTERVAL 7 DAY'),
        'nuevos_30'         => (int) traerValor('SELECT COUNT(*) FROM users WHERE role <> "admin" AND created_at >= NOW() - INTERVAL 30 DAY'),
        'conversion'        => $usuariosActivos > 0 ? round($premium * 100 / $usuariosActivos, 1) : 0.0,
        'ingreso_total'     => $ingresoTotal,
        'ingreso_mes'       => $ingresoMes,
        'ingreso_mes_ant'   => $ingresoMesAnterior,
        'variacion_mes'     => $ingresoMesAnterior > 0
                                ? (int) round(($ingresoMes - $ingresoMesAnterior) * 100 / $ingresoMesAnterior)
                                : null,
        'mrr'               => $mrr,
        'ticket_promedio'   => $pagosConfirmados > 0 ? (int) round($ingresoTotal / $pagosConfirmados) : 0,
        'pagos_confirmados' => $pagosConfirmados,
        'pendientes'        => (int) traerValor('SELECT COUNT(*) FROM payments WHERE status = "pending"'),
        'pendientes_monto'  => (int) traerValor('SELECT COALESCE(SUM(amount_cop), 0) FROM payments WHERE status = "pending"'),
        'reembolsado'       => (int) traerValor('SELECT COALESCE(SUM(amount_cop), 0) FROM payments WHERE status = "refunded"'),
        'vencen_30'         => (int) traerValor(
            'SELECT COUNT(*) FROM subscriptions
              WHERE status = "active" AND expires_at BETWEEN NOW() AND NOW() + INTERVAL 30 DAY'
        ),
    ];

    return $cache;
}


// =====================================================================
//  SERIES DE TIEMPO
// =====================================================================

/**
 * Esqueleto de los últimos N meses, del más antiguo al más reciente.
 *
 * Se construye en PHP y no en SQL porque un mes sin ventas no aparece
 * en un GROUP BY, y una gráfica que se salta los meses vacíos dibuja
 * una recta creciente sobre un negocio parado. El hueco es el dato.
 */
function esqueletoMeses(int $meses): array
{
    $abrev = [1=>'ene','feb','mar','abr','may','jun','jul','ago','sep','oct','nov','dic'];
    $serie = [];

    for ($i = $meses - 1; $i >= 0; $i--) {
        $ts    = strtotime("first day of -$i month");
        $clave = date('Y-m', $ts);

        $serie[$clave] = [
            'clave'    => $clave,
            'etiqueta' => $abrev[(int) date('n', $ts)],
            'anio'     => date('Y', $ts),
            'largo'    => $abrev[(int) date('n', $ts)] . ' ' . date('Y', $ts),
            'valor'    => 0,
            'conteo'   => 0,
        ];
    }

    return $serie;
}

/** Ingresos confirmados mes a mes. El eje del negocio. */
function ingresosPorMes(int $meses = 12): array
{
    $serie = esqueletoMeses($meses);

    $filas = traerTodo(
        'SELECT DATE_FORMAT(confirmed_at, "%Y-%m") AS mes,
                SUM(amount_cop) AS monto,
                COUNT(*)        AS pagos
           FROM payments
          WHERE status = "confirmed"
            AND confirmed_at >= DATE_FORMAT(NOW() - INTERVAL ? MONTH, "%Y-%m-01")
       GROUP BY mes',
        [$meses - 1]
    );

    foreach ($filas as $f) {
        if (isset($serie[$f['mes']])) {
            $serie[$f['mes']]['valor']  = (int) $f['monto'];
            $serie[$f['mes']]['conteo'] = (int) $f['pagos'];
        }
    }

    return array_values($serie);
}

/** Cuentas nuevas mes a mes. */
function registrosPorMes(int $meses = 12): array
{
    $serie = esqueletoMeses($meses);

    $filas = traerTodo(
        'SELECT DATE_FORMAT(created_at, "%Y-%m") AS mes, COUNT(*) AS total
           FROM users
          WHERE role <> "admin"
            AND created_at >= DATE_FORMAT(NOW() - INTERVAL ? MONTH, "%Y-%m-01")
       GROUP BY mes',
        [$meses - 1]
    );

    foreach ($filas as $f) {
        if (isset($serie[$f['mes']])) {
            $serie[$f['mes']]['valor']  = (int) $f['total'];
            $serie[$f['mes']]['conteo'] = (int) $f['total'];
        }
    }

    return array_values($serie);
}

/** Registros de los últimos N días. Para ver el efecto de una campaña. */
function registrosPorDia(int $dias = 30): array
{
    $serie = [];
    for ($i = $dias - 1; $i >= 0; $i--) {
        $ts = strtotime("-$i day");
        $serie[date('Y-m-d', $ts)] = [
            'clave'    => date('Y-m-d', $ts),
            'etiqueta' => date('j', $ts),
            'largo'    => date('d/m/Y', $ts),
            'valor'    => 0,
            'conteo'   => 0,
        ];
    }

    $filas = traerTodo(
        'SELECT DATE(created_at) AS dia, COUNT(*) AS total
           FROM users
          WHERE role <> "admin" AND created_at >= CURDATE() - INTERVAL ? DAY
       GROUP BY dia',
        [$dias - 1]
    );

    foreach ($filas as $f) {
        if (isset($serie[$f['dia']])) {
            $serie[$f['dia']]['valor']  = (int) $f['total'];
            $serie[$f['dia']]['conteo'] = (int) $f['total'];
        }
    }

    return array_values($serie);
}


// =====================================================================
//  REPARTOS
// =====================================================================

/**
 * Cuántos usuarios hay en cada plan, con los gratuitos incluidos.
 *
 * Los gratuitos no tienen fila en `subscriptions` —esa es justamente la
 * definición de gratuito— así que se calculan restando. Sin esa resta,
 * la gráfica de «usuarios por plan» describiría solo a quien paga y
 * daría la impresión de un 100% de conversión.
 */
function usuariosPorPlan(): array
{
    $filas = traerTodo(
        'SELECT p.slug, p.name, COUNT(DISTINCT s.user_id) AS total
           FROM subscriptions s
           JOIN plans p ON p.id = s.plan_id
           JOIN users u ON u.id = s.user_id
          WHERE s.status = "active" AND (s.expires_at IS NULL OR s.expires_at > NOW())
            AND u.role <> "admin"
       GROUP BY p.id
       ORDER BY p.sort_order'
    );

    $r = resumenNegocio();

    $reparto = [[
        'slug'  => PLAN_FREE,
        'name'  => 'Gratis',
        'total' => $r['gratuitos'],
    ]];

    foreach ($filas as $f) {
        $reparto[] = ['slug' => $f['slug'], 'name' => $f['name'], 'total' => (int) $f['total']];
    }

    return $reparto;
}

/** Ingresos acumulados por plan. Qué producto sostiene el negocio. */
function ingresosPorPlan(): array
{
    return traerTodo(
        'SELECT p.slug, p.name,
                COUNT(*)          AS pagos,
                SUM(pg.amount_cop) AS monto
           FROM payments pg
           JOIN plans p ON p.id = pg.plan_id
          WHERE pg.status = "confirmed"
       GROUP BY p.id
       ORDER BY monto DESC'
    );
}

/** Ingresos por forma de pago. Dice si vale la pena la pasarela. */
function ingresosPorMetodo(): array
{
    return traerTodo(
        'SELECT method, COUNT(*) AS pagos, SUM(amount_cop) AS monto
           FROM payments
          WHERE status = "confirmed"
       GROUP BY method
       ORDER BY monto DESC'
    );
}

/** Cuántos pagos hay en cada estado, y por cuánto dinero. */
function pagosPorEstado(): array
{
    $filas = traerTodo(
        'SELECT status, COUNT(*) AS pagos, SUM(amount_cop) AS monto
           FROM payments GROUP BY status'
    );

    $mapa = [];
    foreach ($filas as $f) {
        $mapa[$f['status']] = ['pagos' => (int) $f['pagos'], 'monto' => (int) $f['monto']];
    }

    // Todos los estados aparecen aunque estén en cero: una tabla que
    // omite «Rechazado» hace pensar que nunca se rechazó nada.
    $completo = [];
    foreach (array_keys(estadosPago()) as $estado) {
        $completo[$estado] = $mapa[$estado] ?? ['pagos' => 0, 'monto' => 0];
    }

    return $completo;
}

/** Reparto de cuentas por rol: clientes, docentes, estudiantes. */
function usuariosPorRol(): array
{
    $nombres = [
        'user'         => 'Familias',
        'teacher'      => 'Docentes',
        'student'      => 'Estudiantes',
        'school_admin' => 'Admin. escolares',
        'admin'        => 'Administradores',
    ];

    $filas = traerTodo('SELECT role, COUNT(*) AS total FROM users GROUP BY role');

    $mapa = [];
    foreach ($filas as $f) {
        $mapa[$f['role']] = (int) $f['total'];
    }

    $r = [];
    foreach ($nombres as $rol => $nombre) {
        $r[] = ['rol' => $rol, 'name' => $nombre, 'total' => $mapa[$rol] ?? 0];
    }

    return $r;
}


// =====================================================================
//  LISTAS DE TRABAJO
// =====================================================================

/** Lo que hay que atender hoy: pagos esperando confirmación. */
function pagosPendientes(int $limite = 10): array
{
    return traerTodo(
        'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
          WHERE pg.status = "pending"
       ORDER BY pg.created_at
          LIMIT ' . max(1, min(100, $limite))
    );
}

/** Los últimos movimientos, sea cual sea su estado. */
function ultimosPagos(int $limite = 8): array
{
    return traerTodo(
        'SELECT ' . CAMPOS_PAGO . UNIONES_PAGO . '
       ORDER BY pg.created_at DESC
          LIMIT ' . max(1, min(100, $limite))
    );
}

/**
 * Suscripciones que vencen pronto.
 *
 * Es la lista más rentable del panel: renovar a quien ya pagó cuesta
 * mucho menos que conseguir a alguien nuevo, y la ocasión dura solo
 * los días que quedan.
 */
function renovacionesProximas(int $dias = 30): array
{
    return traerTodo(
        'SELECT s.id, s.expires_at, s.billing_cycle, s.amount_cop,
                u.id AS user_id, u.name, u.email,
                p.name AS plan, p.slug AS plan_slug,
                DATEDIFF(s.expires_at, NOW()) AS dias
           FROM subscriptions s
           JOIN users u ON u.id = s.user_id
           JOIN plans p ON p.id = s.plan_id
          WHERE s.status = "active"
            AND s.expires_at BETWEEN NOW() AND NOW() + INTERVAL ? DAY
       ORDER BY s.expires_at',
        [$dias]
    );
}

/** Cuentas registradas recientemente, con su plan si lo tienen. */
function ultimosRegistros(int $limite = 8): array
{
    return traerTodo(
        'SELECT u.id, u.name, u.email, u.role, u.status, u.created_at,
                p.name AS plan
           FROM users u
      LEFT JOIN subscriptions s
             ON s.id = (SELECT s2.id FROM subscriptions s2
                         WHERE s2.user_id = u.id AND s2.status = "active"
                           AND (s2.expires_at IS NULL OR s2.expires_at > NOW())
                      ORDER BY s2.expires_at DESC LIMIT 1)
      LEFT JOIN plans p ON p.id = s.plan_id
          WHERE u.role <> "admin"
       ORDER BY u.created_at DESC
          LIMIT ' . max(1, min(50, $limite))
    );
}
