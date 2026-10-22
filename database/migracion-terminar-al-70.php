<?php
/**
 * migracion-terminar-al-70.php — Las estaciones bien hechas, terminadas
 *
 *     php database/migracion-terminar-al-70.php             # simulacion
 *     php database/migracion-terminar-al-70.php --aplicar   # escribe
 *
 * Una estación contaba como terminada solo con el 100 %. Quien llegaba
 * al final con un error veía «¡Muy bien! Terminaste», pero la estación
 * quedaba «empezada»: la actividad no se completaba y la ruta del curso
 * no avanzaba. Desde ahora se termina con el 70 % (ver api/progreso.php).
 *
 * Esto aplica la misma regla a lo que ya jugaron: las filas guardadas
 * con 70 % o más pasan a `completed`, con la fecha de su última jugada.
 * No borra nada ni toca monedas, estrellas ni porcentajes. Es repetible:
 * la segunda vez no encuentra nada que cambiar.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);

echo "===============================================================\n";
echo "  MIGRACION - Estaciones terminadas desde el 70 %\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

$f = traerUno(
    'SELECT COUNT(*) AS filas, COUNT(DISTINCT user_id) AS ninos
       FROM activity_progress
      WHERE status = "in_progress" AND percent >= 70'
);

printf("  Estaciones bien hechas que figuraban como empezadas: %d (de %d niños)\n",
       (int) $f['filas'], (int) $f['ninos']);

if ((int) $f['filas'] === 0) {
    exit("  [ok] Nada que cambiar.\n");
}

if ($aplicar) {
    $n = ejecutar(
        'UPDATE activity_progress
            SET status = "completed",
                completed_at = COALESCE(completed_at, updated_at, NOW())
          WHERE status = "in_progress" AND percent >= 70'
    );
    echo "Listo. $n estacion(es) marcadas como terminadas.\n";
} else {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
