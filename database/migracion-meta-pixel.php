<?php
/**
 * migracion-meta-pixel.php — Deja configurado el píxel de Meta
 *
 *     php database/migracion-meta-pixel.php             # simulacion
 *     php database/migracion-meta-pixel.php --aplicar   # escribe
 *
 * Crea los ajustes del píxel (Panel → Ajustes) con el conjunto de datos
 * «Actividades en Línea» (1673744254417728) y el aviso de cookies
 * encendido. Solo si todavía no existen: si alguien los cambió o los
 * vació desde el panel, se respeta. Toca únicamente `settings`.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

$ajustes = [
    'meta_pixel_id'       => ['1673744254417728', 'Identificador del píxel de Meta (solo páginas públicas)'],
    'meta_verificacion'   => ['', 'Verificación de dominio de Meta'],
    'meta_consentimiento' => ['1', 'Pedir permiso antes de activar el píxel de Meta'],
];

foreach ($ajustes as $clave => [$valor, $etiqueta]) {
    if (traerValor('SELECT COUNT(*) FROM settings WHERE `key` = ?', [$clave])) {
        echo "  [ok] $clave ya existe, no se toca.\n";
        continue;
    }

    echo "  [->] Se crea $clave = «{$valor}»\n";
    if ($aplicar) {
        ejecutar('INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)', [$clave, $valor, $etiqueta]);
    }
}

echo $aplicar ? "Listo.\n" : "Simulacion. Nada se escribio. Repite con --aplicar\n";
