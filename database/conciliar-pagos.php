<?php
/**
 * conciliar-pagos.php — Repesca los pagos cuyo aviso se perdio
 *
 *     php database/conciliar-pagos.php             # simulacion
 *     php database/conciliar-pagos.php --aplicar   # escribe
 *     php database/conciliar-pagos.php --aplicar --dias=7
 *
 * ---------------------------------------------------------------------
 *  PARA QUE SIRVE
 * ---------------------------------------------------------------------
 *
 * El cobro automatico depende de que llegue un aviso HTTP desde Mercado
 * Pago. Casi siempre llega. Cuando no —se cayo la red, el sitio estaba
 * reiniciandose, alguien roto el secreto del webhook— el cliente paga y
 * no recibe nada, y NADIE SE ENTERA: no hay error, no hay excepcion, no
 * hay nada en ningun log. Solo un cobro que se queda pendiente para
 * siempre y un cliente que escribe enfadado tres dias despues.
 *
 * Este script cierra ese hueco. Recorre los pagos pendientes, le pregunta
 * a Mercado Pago por cada uno y aplica lo que responda. Pensado para
 * correr cada hora en el servidor.
 *
 * No sustituye al webhook: el webhook activa el acceso en segundos y esto
 * tarda hasta una hora. Es la red debajo del trapecio, no el trapecio.
 *
 * ---------------------------------------------------------------------
 *  POR QUE NO CONCEDE NADA POR SU CUENTA
 * ---------------------------------------------------------------------
 *
 * Usa `sincronizarPagoMp()`, la misma funcion que el boton del panel, que
 * a su vez pasa por `verificarPagoMp()`: comprueba que la moneda sea COP
 * y que el monto sea EXACTAMENTE el que se puso a cobrar. Un pago
 * aprobado de mil pesos sobre un plan de noventa y nueve mil no abre
 * nada, se anota el desajuste y se sigue.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$argumentos = $argv ?? [];
$aplicar    = in_array('--aplicar', $argumentos, true);

/*
 * Cuantos dias hacia atras. Los pagos muy viejos casi nunca cambian de
 * estado y consultarlos todos cada hora es gastar llamadas a la API sin
 * motivo; treinta dias cubre de sobra cualquier PSE o efectivo lento.
 */
$dias = 30;
foreach ($argumentos as $a) {
    if (preg_match('/^--dias=(\d+)$/', $a, $m)) {
        $dias = max(1, min(365, (int) $m[1]));
    }
}

echo "\n";
echo "===============================================================\n";
echo "  CONCILIACION DE PAGOS CON MERCADO PAGO\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "  Ultimos $dias dias\n";
echo "===============================================================\n\n";

if (!pagosInstalados()) {
    echo "  [!] Falta la migracion de pagos.\n\n";
    exit(1);
}

if (pasarelaActiva() !== 'mercadopago') {
    echo "  [!] No hay ninguna pasarela conectada. Nada que conciliar.\n";
    echo "      Panel -> Cobros -> Ajustes de cobro.\n\n";
    exit(0);
}

/*
 * Solo los pendientes. Un pago confirmado no se vuelve a consultar: si
 * despues se reembolsa o se revierte, Mercado Pago manda su propio aviso
 * y eso llega por el webhook.
 */
$pendientes = traerTodo(
    'SELECT * FROM payments
      WHERE status = ?
        AND created_at > DATE_SUB(NOW(), INTERVAL ? DAY)
        AND (method = "pasarela" OR provider = "mercadopago")
   ORDER BY created_at',
    [PAGO_PENDIENTE, $dias]
);

printf("  Pagos pendientes de pasarela: %d\n\n", count($pendientes));

if (!$pendientes) {
    echo "  Nada que hacer.\n\n";
    exit(0);
}

$resumen = ['confirmados' => 0, 'rechazados' => 0, 'siguen' => 0, 'sin_pago' => 0, 'fallos' => 0];

foreach ($pendientes as $pago) {

    printf("  %-18s  %-10s  %s\n",
        $pago['reference'],
        precioCop((int) $pago['amount_cop']),
        date('d/m/Y', strtotime((string) $pago['created_at']))
    );

    if (!$aplicar) {
        // En simulacion se consulta igual —es una lectura, no cambia
        // nada— porque el valor del ensayo esta justo en ver que
        // responderia Mercado Pago antes de dejarle escribir.
        $busqueda = mpBuscarPorReferencia((string) $pago['reference']);

        if (!$busqueda['ok']) {
            printf("      [!] %s\n", $busqueda['error']);
            $resumen['fallos']++;
        } elseif (!$busqueda['pagos']) {
            echo "      sin pagos en Mercado Pago (el cliente no llego a pagar)\n";
            $resumen['sin_pago']++;
        } else {
            $estados = array_column($busqueda['pagos'], 'status');
            printf("      Mercado Pago tiene: %s\n", implode(', ', $estados));
            in_array('approved', $estados, true)
                ? $resumen['confirmados']++
                : $resumen['siguen']++;
        }

        continue;
    }

    $r = sincronizarPagoMp($pago);

    printf("      %s\n", wordwrap($r['mensaje'], 66, "\n      ", true));

    if (!$r['ok']) {
        $resumen['fallos']++;
        continue;
    }

    if ($r['estado'] === null) {
        $resumen['sin_pago']++;
        continue;
    }

    $ahora = estadoDesdePasarela((string) $r['estado']);

    if ($ahora === PAGO_CONFIRMADO && $r['aplicado']) { $resumen['confirmados']++; }
    elseif ($ahora === PAGO_PENDIENTE)                { $resumen['siguen']++; }
    else                                              { $resumen['rechazados']++; }
}

echo "\n---------------------------------------------------------------\n";
printf("  Se confirmaron        : %d\n", $resumen['confirmados']);
printf("  Rechazados o anulados : %d\n", $resumen['rechazados']);
printf("  Siguen pendientes     : %d\n", $resumen['siguen']);
printf("  Sin pago en la pasarela: %d\n", $resumen['sin_pago']);
printf("  Fallos de consulta    : %d\n", $resumen['fallos']);
echo "\n";

if (!$aplicar) {
    echo "Simulacion. Vuelve a correrlo con --aplicar para que escriba.\n\n";
} elseif ($resumen['confirmados'] > 0) {
    echo "AVISO: se confirmaron pagos que el webhook no habia aplicado.\n";
    echo "Si esto pasa a menudo, algo va mal con el webhook: revisa que la\n";
    echo "URL sea publica y que el secreto coincida con el del panel de\n";
    echo "Mercado Pago. Este script es la red, no el metodo.\n\n";
}

exit($resumen['fallos'] > 0 ? 1 : 0);
