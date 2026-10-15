<?php
/**
 * migracion-solicitudes.php — Solicitudes de información (formulario público)
 *
 *     php database/migracion-solicitudes.php             # simulacion
 *     php database/migracion-solicitudes.php --aplicar   # escribe
 *
 * El botón «Solicitar información» del plan Escuela apuntaba a la misma
 * tarjeta y no hacía nada. Ahora abre un formulario, y cada solicitud se
 * GUARDA aquí antes de intentar avisar por correo: si el correo falla o
 * no está configurado, la solicitud sigue en Panel → Solicitudes. Con
 * pauta pagada detrás, perder un interesado es perder dinero.
 *
 * Crea `info_requests` y el ajuste `solicitudes_correo` (a dónde avisar).
 * Solo añade; repetirla no cambia nada.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

$hayTabla = (bool) traerValor(
    'SELECT COUNT(*) FROM information_schema.TABLES WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = ?',
    ['info_requests']
);

if ($hayTabla) {
    echo "  [ok] info_requests ya existe.\n";
} else {
    echo "  [->] Se crea info_requests\n";
    if ($aplicar) {
        ejecutar('CREATE TABLE IF NOT EXISTS `info_requests` (
            `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
            `profile`        ENUM("familia","docente","colegio","otro") NOT NULL,
            `name`           VARCHAR(120) NOT NULL,
            `email`          VARCHAR(190) NOT NULL,
            `phone`          VARCHAR(40)  NULL COMMENT "WhatsApp o teléfono",
            `city`           VARCHAR(120) NULL COMMENT "Ciudad y país",
            `institution`    VARCHAR(160) NULL,
            `job_title`      VARCHAR(120) NULL COMMENT "Cargo en la institución",
            `students`       VARCHAR(40)  NULL COMMENT "Rango de estudiantes",
            `teachers`       VARCHAR(40)  NULL COMMENT "Rango de docentes",
            `grades`         VARCHAR(200) NULL COMMENT "Niveles de interés, separados por coma",
            `subjects`       VARCHAR(300) NULL COMMENT "Áreas de interés, separadas por coma",
            `needs`          VARCHAR(300) NULL COMMENT "Necesidades marcadas, separadas por coma",
            `contact_pref`   VARCHAR(40)  NULL COMMENT "Cómo prefiere que lo contacten",
            `source`         VARCHAR(60)  NULL COMMENT "Cómo nos conoció",
            `message`        TEXT         NULL,
            `consent_at`     DATETIME     NOT NULL COMMENT "Cuándo autorizó el tratamiento de datos",
            `ip_hash`        CHAR(64)     NOT NULL COMMENT "SHA-256 de la IP, para frenar abusos; nunca en claro",
            `status`         ENUM("nueva","contactada","cerrada") NOT NULL DEFAULT "nueva",
            `email_sent`     TINYINT(1)   NOT NULL DEFAULT 0,
            `email_error`    VARCHAR(255) NULL,
            `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
            PRIMARY KEY (`id`),
            KEY `idx_ir_estado` (`status`, `created_at`),
            KEY `idx_ir_ip` (`ip_hash`, `created_at`)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci');
    }
}

if (traerValor('SELECT COUNT(*) FROM settings WHERE `key` = "solicitudes_correo"')) {
    echo "  [ok] ajuste solicitudes_correo ya existe.\n";
} else {
    echo "  [->] Se crea el ajuste solicitudes_correo = actividadesenlinea1577@gmail.com\n";
    if ($aplicar) {
        ejecutar('INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)', [
            'solicitudes_correo', 'actividadesenlinea1577@gmail.com',
            'Correo que recibe las solicitudes de información',
        ]);
    }
}

echo $aplicar ? "Listo.\n" : "Simulacion. Nada se escribio. Repite con --aplicar\n";
