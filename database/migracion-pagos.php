<?php
/**
 * migracion-pagos.php — Fase 4 · Estructura de la pasarela de pagos
 *
 * Crea las dos tablas que faltaban para poder cobrar y siembra los
 * ajustes de la pasarela. Es idempotente: se puede correr las veces que
 * haga falta sin duplicar nada.
 *
 *     php database/migracion-pagos.php             # simulación
 *     php database/migracion-pagos.php --aplicar   # escribe
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ UNA TABLA `payments` SI YA EXISTE `subscriptions`
 * ─────────────────────────────────────────────────────────────────────
 *
 * No son lo mismo y confundirlas es el error clásico:
 *
 *   · `subscriptions` responde «¿hasta cuándo tiene acceso?». Es el
 *     permiso. Hay como mucho una vigente por usuario.
 *   · `payments` responde «¿quién pagó qué, cuándo y cómo?». Es el
 *     libro contable. Hay una fila por INTENTO, incluidos los que
 *     fallaron, los rechazados y los devueltos.
 *
 * Un pago rechazado tiene que quedar escrito —es la mitad de un informe
 * de pagos— y no puede vivir en `subscriptions`, porque no concede nada.
 * Y una renovación es un pago nuevo sobre la misma suscripción: si el
 * dinero viviera en la suscripción, renovar borraría el historial de lo
 * cobrado el año anterior.
 *
 * `payment_events` guarda cada cambio de estado con su motivo y su
 * autor. Con dinero de por medio, «esta suscripción está activa» no
 * basta: hay que poder responder quién la activó, cuándo y por qué.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

$aplicar = in_array('--aplicar', $argv ?? [], true);

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por línea de comandos.');
}

echo "\n";
echo "═══════════════════════════════════════════════════════════════\n";
echo "  MIGRACIÓN · Fase 4 · Pagos\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACIÓN — nada se guarda (usa --aplicar)') . "\n";
echo "═══════════════════════════════════════════════════════════════\n\n";


// =====================================================================
//  1. TABLAS
// =====================================================================

$tablas = [

    'payments' => "
CREATE TABLE IF NOT EXISTS `payments` (
    `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference`          VARCHAR(40)  NOT NULL COMMENT 'Referencia propia, la que ve el cliente: AEL-2026-084512',
    `user_id`            INT UNSIGNED NOT NULL,
    `plan_id`            INT UNSIGNED NOT NULL,
    `subscription_id`    INT UNSIGNED NULL COMMENT 'Se rellena al confirmar: la suscripción que este pago pagó',
    `billing_cycle`      ENUM('monthly','yearly') NOT NULL DEFAULT 'yearly',
    `amount_cop`         INT UNSIGNED NOT NULL COMMENT 'Monto congelado al crear el pago; no se recalcula si cambia el precio del plan',
    `status`             ENUM('pending','confirmed','rejected','refunded','cancelled') NOT NULL DEFAULT 'pending',
    `method`             VARCHAR(30)  NOT NULL DEFAULT 'transferencia' COMMENT 'transferencia | nequi | efectivo | pasarela | cortesia',
    `provider`           VARCHAR(40)  NULL COMMENT 'wompi | mercadopago | payu | stripe | manual',
    `provider_reference` VARCHAR(120) NULL COMMENT 'Id de transacción de la pasarela. NUNCA datos de tarjeta',
    `provider_status`    VARCHAR(40)  NULL COMMENT 'Estado crudo tal como lo devolvió la pasarela',
    `payer_name`         VARCHAR(120) NULL COMMENT 'Datos de facturación; pueden diferir de los de la cuenta',
    `payer_email`        VARCHAR(190) NULL,
    `payer_document`     VARCHAR(40)  NULL COMMENT 'Cédula o NIT para la factura',
    `proof_reference`    VARCHAR(120) NULL COMMENT 'Número de comprobante que escribe el cliente al transferir',
    `notes`              TEXT         NULL COMMENT 'Nota interna del administrador',
    `confirmed_by`       INT UNSIGNED NULL COMMENT 'Administrador que confirmó; NULL si lo confirmó la pasarela',
    `confirmed_at`       DATETIME     NULL,
    `created_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_payments_reference` (`reference`),
    UNIQUE KEY `uk_payments_provider_ref` (`provider`, `provider_reference`),
    KEY `idx_payments_usuario`  (`user_id`, `status`),
    KEY `idx_payments_estado`   (`status`, `created_at`),
    KEY `idx_payments_creado`   (`created_at`),
    CONSTRAINT `fk_pay_user`  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pay_plan`  FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
    CONSTRAINT `fk_pay_sub`   FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_pay_admin` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    'payment_events' => "
CREATE TABLE IF NOT EXISTS `payment_events` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payment_id` INT UNSIGNED NOT NULL,
    `type`       VARCHAR(30)  NOT NULL COMMENT 'creado | confirmado | rechazado | reembolsado | anulado | editado | webhook | nota',
    `detail`     TEXT         NULL,
    `actor_id`   INT UNSIGNED NULL COMMENT 'Quién lo hizo. NULL = lo hizo la pasarela o el propio sistema',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_evt_pago` (`payment_id`, `created_at`),
    CONSTRAINT `fk_evt_pago`  FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_evt_actor` FOREIGN KEY (`actor_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

foreach ($tablas as $nombre => $sql) {
    $existe = (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.tables
          WHERE table_schema = DATABASE() AND table_name = ?',
        [$nombre]
    );

    if ($existe) {
        echo "  · Tabla `$nombre` ya existe — se deja como está.\n";
        continue;
    }

    if ($aplicar) {
        db()->exec($sql);
        echo "  ✔ Tabla `$nombre` creada.\n";
    } else {
        echo "  → Se crearía la tabla `$nombre`.\n";
    }
}

echo "\n";


// =====================================================================
//  2. AJUSTES DE COBRO
// =====================================================================
//
// Viven en `settings` y no en el código porque los cambia quien
// administra, no quien programa: si mañana cambia el número de cuenta,
// nadie debería tener que abrir un archivo .php para corregirlo.
//
// El secreto del webhook se genera aleatorio la primera vez. Si el
// script se vuelve a correr NO se regenera: cambiarlo sin avisar a la
// pasarela dejaría de aceptar sus avisos de pago en silencio.

$ajustes = [
    'pagos_modo' => [
        'manual',
        'Modo de cobro: manual (transferencia con confirmación) o el slug de una pasarela',
    ],
    'pagos_titular' => [
        'Actividades en Línea S.A.S.',
        'Titular de la cuenta bancaria que se muestra al cliente',
    ],
    'pagos_documento' => [
        '',
        'NIT o cédula del titular',
    ],
    'pagos_banco' => [
        'Bancolombia',
        'Banco de la cuenta de recaudo',
    ],
    'pagos_tipo_cuenta' => [
        'Ahorros',
        'Tipo de cuenta: Ahorros o Corriente',
    ],
    'pagos_cuenta' => [
        '',
        'Número de la cuenta de recaudo',
    ],
    'pagos_nequi' => [
        '',
        'Número de Nequi o Daviplata (opcional)',
    ],
    'pagos_correo_soporte' => [
        '',
        'Correo al que el cliente envía el comprobante',
    ],
    'pagos_instrucciones' => [
        'Transfiere el valor exacto y escribe la referencia del pago en la '
        . 'descripción. Confirmamos en menos de 24 horas hábiles.',
        'Texto que se muestra en la pantalla de pago',
    ],
    'pagos_webhook_secreto' => [
        bin2hex(random_bytes(24)),
        'Secreto compartido con la pasarela para validar sus avisos (webhook)',
    ],
    'pagos_dias_gracia' => [
        '0',
        'Días de acceso que se conceden de más al renovar (0 = ninguno)',
    ],
];

foreach ($ajustes as $clave => [$valor, $etiqueta]) {
    $existe = traerValor('SELECT `key` FROM settings WHERE `key` = ?', [$clave]);

    if ($existe !== null) {
        echo "  · Ajuste `$clave` ya definido — se respeta su valor actual.\n";
        continue;
    }

    if ($aplicar) {
        ejecutar(
            'INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)',
            [$clave, $valor, $etiqueta]
        );
        echo "  ✔ Ajuste `$clave` creado.\n";
    } else {
        echo "  → Se crearía el ajuste `$clave`.\n";
    }
}


// =====================================================================
//  3. RESULTADO
// =====================================================================

echo "\n───────────────────────────────────────────────────────────────\n";

if ($aplicar) {
    echo "  Listo. La Fase 4 ya tiene dónde escribir.\n";
    echo "  Siguiente paso: Panel → Cobros → Ajustes de cobro,\n";
    echo "  para poner los datos de la cuenta de recaudo.\n";
} else {
    echo "  Simulación terminada. Nada se escribió.\n";
    echo "  Vuelve a ejecutar con --aplicar cuando estés conforme.\n";
}

echo "───────────────────────────────────────────────────────────────\n\n";
