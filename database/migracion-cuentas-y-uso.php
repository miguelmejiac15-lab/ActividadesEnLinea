<?php
/**
 * migracion-cuentas-y-uso.php — Fase 7 · Recuperar contraseña y medir uso
 *
 *     php database/migracion-cuentas-y-uso.php             # simulacion
 *     php database/migracion-cuentas-y-uso.php --aplicar   # escribe
 *
 * Trae tres cosas que no existian:
 *
 *   1. `password_resets`  — los enlaces de recuperacion
 *   2. `user_sessions`    — para poder medir cuanto tiempo pasa cada
 *                           usuario en la plataforma
 *   3. Los ajustes del correo saliente
 *
 * ---------------------------------------------------------------------
 *  POR QUE UNA TABLA DE SESIONES Y NO UNA COLUMNA
 * ---------------------------------------------------------------------
 *
 * `users.last_login_at` responde «cuando entro por ultima vez» y nada
 * mas. No responde cuanto se queda, cuantos dias a la semana vuelve, ni
 * si sus visitas duran dos minutos o cuarenta.
 *
 * Con una fila por visita —cuando empezo, cuando se le vio por ultima
 * vez— esas preguntas se responden sumando. Y se responden hacia atras:
 * un contador en la fila del usuario solo sabe el total de hoy.
 *
 * Una «visita» termina cuando pasan 30 minutos sin actividad. Es el corte
 * habitual y es una convencion, no una medida exacta: alguien que deja la
 * pestana abierta y se va a comer cuenta 30 minutos de mas. Se asume a
 * proposito, porque la alternativa —latidos cada pocos segundos desde el
 * navegador— gasta bateria de una tablet infantil para afinar un numero
 * que nadie va a mirar con esa precision.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    exit('Este script solo se ejecuta por linea de comandos.');
}

$aplicar = in_array('--aplicar', $argv ?? [], true);

echo "\n";
echo "===============================================================\n";
echo "  MIGRACION - Fase 7 - Cuentas y uso\n";
echo "  " . ($aplicar ? 'MODO ESCRITURA' : 'SIMULACION - nada se guarda (usa --aplicar)') . "\n";
echo "===============================================================\n\n";

$pdo     = db();
$cambios = 0;

/** ¿Existe la tabla? */
function hayTabla(string $t): bool
{
    return (bool) traerValor(
        'SELECT COUNT(*) FROM information_schema.tables
          WHERE table_schema = DATABASE() AND table_name = ?', [$t]
    );
}


// =====================================================================
//  1. TABLAS
// =====================================================================

$tablas = [

    /*
     * Solo se guarda el HASH del testigo, igual que con una contrasena.
     * Quien leyera esta tabla no podria entrar en ninguna cuenta con lo
     * que hay aqui.
     */
    'password_resets' => "
CREATE TABLE IF NOT EXISTS `password_resets` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`      INT UNSIGNED NOT NULL,
    `token_hash`   CHAR(64) NOT NULL COMMENT 'SHA-256 del testigo. El testigo en claro no se guarda',
    `expires_at`   DATETIME NOT NULL,
    `used_at`      DATETIME NULL COMMENT 'Cuando se uso. Un testigo usado no vuelve a servir',
    `requested_ip` VARCHAR(45) NULL,
    `created_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_reset_token` (`token_hash`),
    KEY `idx_reset_user` (`user_id`, `created_at`),
    CONSTRAINT `fk_reset_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",

    /*
     * Una fila por visita. `last_seen_at` se toca como mucho una vez por
     * minuto: sin ese freno seria un UPDATE por peticion, y un nino
     * jugando genera muchas.
     */
    'user_sessions' => "
CREATE TABLE IF NOT EXISTS `user_sessions` (
    `id`           INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`      INT UNSIGNED NOT NULL,
    `started_at`   DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `last_seen_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `hits`         INT UNSIGNED NOT NULL DEFAULT 1 COMMENT 'Peticiones servidas en esta visita',
    `device`       ENUM('movil','escritorio','otro') NOT NULL DEFAULT 'otro',
    `ip`           VARCHAR(45) NULL,
    PRIMARY KEY (`id`),
    KEY `idx_sesion_user` (`user_id`, `started_at`),
    KEY `idx_sesion_vista` (`last_seen_at`),
    CONSTRAINT `fk_sesion_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci",
];

echo "-- Tablas ------------------------------------------------------\n";

foreach ($tablas as $nombre => $sql) {
    if (hayTabla($nombre)) {
        echo "  [ok] $nombre ya existe.\n";
        continue;
    }

    echo "  [->] Se crea $nombre\n";

    if ($aplicar) {
        $pdo->exec($sql);
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  2. AJUSTES DEL CORREO
// =====================================================================

echo "-- Ajustes del correo saliente ---------------------------------\n";

$ajustes = [
    'correo_metodo' => ['ninguno',
        "Como se envia: smtp, php (funcion mail) o ninguno"],
    'correo_host' => ['',
        'Servidor SMTP. Gmail: smtp.gmail.com'],
    'correo_puerto' => ['587',
        'Puerto SMTP. 587 con STARTTLS, 465 con SSL directo'],
    'correo_seguridad' => ['tls',
        'tls (STARTTLS), ssl (directo) o ninguna'],
    'correo_usuario' => ['',
        'Usuario del buzon, normalmente la direccion completa'],
    'correo_clave' => ['',
        'Contrasena del buzon. Con Gmail, una contrasena de aplicacion'],
    'correo_desde' => ['',
        'Direccion desde la que salen los correos'],
    'correo_desde_nombre' => ['Actividades en Linea',
        'Nombre que se ve como remitente'],
];

foreach ($ajustes as $clave => [$valor, $etiqueta]) {
    if (traerValor('SELECT `key` FROM settings WHERE `key` = ?', [$clave]) !== null) {
        echo "  [ok] $clave ya definido - se respeta su valor.\n";
        continue;
    }

    echo "  [->] $clave  ($etiqueta)\n";

    if ($aplicar) {
        ejecutar('INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)',
                 [$clave, $valor, $etiqueta]);
        $cambios++;
    }
}

echo "\n";


// =====================================================================
//  3. ESTADO
// =====================================================================

echo "-- Estado ------------------------------------------------------\n";

if (hayTabla('password_resets')) {
    printf("  Peticiones de recuperacion : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM password_resets'));
}
if (hayTabla('user_sessions')) {
    printf("  Visitas registradas        : %d\n",
        (int) traerValor('SELECT COUNT(*) FROM user_sessions'));
}

printf("  Envio de correo            : %s\n",
    correoConfigurado() ? 'configurado (' . correoMetodo() . ')' : 'SIN CONFIGURAR');
printf("  Usuarios                   : %d\n",
    (int) traerValor('SELECT COUNT(*) FROM users'));

echo "\n";

if ($aplicar) {
    echo "Listo. $cambios cambio(s) aplicado(s).\n\n";

    if (!correoConfigurado()) {
        echo "Siguiente paso: Panel -> Ajustes -> Correo saliente.\n";
        echo "Sin eso, 'he olvidado mi contrasena' no puede enviar nada y\n";
        echo "el propio formulario se lo dice al usuario en vez de fingir.\n\n";
    }
} else {
    echo "Simulacion terminada. Vuelve a correrlo con --aplicar para escribir.\n\n";
}
