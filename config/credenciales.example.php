<?php
/**
 * credenciales.example.php — Plantilla de credenciales
 *
 * Copia este archivo como `credenciales.php` y ajusta los valores.
 * `credenciales.php` es el ÚNICO archivo con secretos y nunca debe
 * subirse a un repositorio ni publicarse.
 *
 * En XAMPP recién instalado, el usuario de MySQL suele ser 'root'
 * sin contraseña.
 */

return [
    // ── Base de datos ────────────────────────────────────────────────
    'db' => [
        'host'     => '127.0.0.1',
        'port'     => 3306,
        'database' => 'actividades_en_linea',
        'usuario'  => 'root',
        'password' => '',
        'charset'  => 'utf8mb4',
    ],

    // ── Entorno ──────────────────────────────────────────────────────
    // 'desarrollo' muestra los errores en pantalla.
    // 'produccion' los oculta y solo los escribe en almacen/logs/.
    'entorno' => 'desarrollo',

    // ── URL base ─────────────────────────────────────────────────────
    // Sin barra final. En XAMPP: http://localhost/proyecto-final
    'url_base' => 'http://localhost/proyecto-final',

    // ── Clave de la aplicación ───────────────────────────────────────
    // Cadena aleatoria larga. instalar.php genera una automáticamente.
    'app_key' => 'CAMBIAR-POR-UNA-CADENA-ALEATORIA-LARGA',
];
