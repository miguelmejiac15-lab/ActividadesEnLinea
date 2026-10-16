<?php
/**
 * migracion-idiomas-inmersion.php — Inglés y alemán en inmersión
 *
 *     php database/migracion-idiomas-inmersion.php             # simulacion
 *     php database/migracion-idiomas-inmersion.php --aplicar   # escribe
 *
 * Revisión de los docentes de alemán (octubre de 2026), extendida a
 * inglés: las preguntas van en el idioma que se aprende y no en español,
 * sin ejercicios de traducción, con pares pregunta ↔ respuesta y dibujos
 * en las opciones que lo necesitan. El contenido nuevo está en
 * database/contenido/idiomas*.php.
 *
 * A diferencia del sembrador, esto NO borra estaciones: actualiza cada
 * una en su sitio (misma fila, mismo id), así el progreso de los niños
 * sigue colgando de ella. Si una actividad tiene ahora una estación más,
 * se añade al final; si tiene menos, las sobrantes se dejan y se avisa.
 *
 * Se ejecuta UNA vez: al terminar deja el ajuste
 * `migracion_idiomas_inmersion` y las siguientes veces no hace nada. Así
 * no se pelea con las migraciones de limpieza que corren en cada
 * despliegue. Solo tablas de catálogo.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once __DIR__ . '/contenido/ayudas.php';
require_once __DIR__ . '/contenido/dibujos.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

$aplicar = in_array('--aplicar', $argv, true);
const MARCA = 'migracion_idiomas_inmersion';

echo "===============================================================\n";
echo "  MIGRACION - Idiomas en inmersion\n";
echo '  ', $aplicar ? 'MODO ESCRITURA' : 'SIMULACION (usa --aplicar para escribir)', "\n";
echo "===============================================================\n\n";

if (traerValor('SELECT COUNT(*) FROM settings WHERE `key` = ?', [MARCA])) {
    exit("  [ok] Ya se aplicó. Nada que hacer.\n");
}

mt_srand(20260827);   // igual que el sembrador

// Los generadores de Repaso, Desafío final, Memoria…, sin su recorrido.
define('ENRIQUECER_SOLO_FUNCIONES', true);
require_once __DIR__ . '/enriquecer-actividades.php';

/** La misma estación generada, hecha de nuevo con la cosecha nueva. */
function regenerada(string $titulo, array $cosecha, array $estaciones): ?array
{
    $candidatas = [
        generarRepaso($cosecha, 0), generarRepaso($cosecha, 1),
        generarEmparejar($cosecha), generarMemoria($cosecha),
        generarCrucigrama($cosecha), generarSopa($cosecha),
        generarLaOtraCara($estaciones), generarRepasoExtra($cosecha),
    ];
    foreach ($candidatas as $c) {
        if ($c && $c['titulo'] === $titulo) {
            return $c;
        }
    }
    return null;
}

$regeneradas  = 0;
$actualizadas = 0;
$nuevas       = 0;
$avisos       = [];

foreach (['idiomas', 'idiomas-primaria', 'idiomas-ampliacion', 'idiomas-aleman'] as $archivo) {
    $contenido = require __DIR__ . "/contenido/$archivo.php";

    foreach ($contenido['actividades'] as $act) {
        $actividadId = (int) traerValor('SELECT id FROM activities WHERE slug = ?', [$act['slug']]);
        if (!$actividadId) {
            $avisos[] = "{$act['slug']}: no existe en la base, se salta";
            continue;
        }

        $enBase = [];
        foreach (traerTodo('SELECT id, position, title, description, icon, game_type, config
                              FROM activity_stations WHERE activity_id = ? ORDER BY position', [$actividadId]) as $f) {
            $enBase[(int) $f['position']] = $f;
        }

        $pos = 0;
        foreach ($act['estaciones'] as $e) {
            $pos++;
            $config = ['datos' => ilustrar($e['datos'], (string) $e['tipo'])];
            if (!empty($e['idioma'])) {
                $config['idioma'] = $e['idioma'];
            }
            $json = json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES);

            $fila = $enBase[$pos] ?? null;

            if (!$fila) {
                echo "  [+] {$act['slug']} E$pos «{$e['titulo']}» (nueva)\n";
                $nuevas++;
                if ($aplicar) {
                    insertar('INSERT INTO activity_stations
                                 (activity_id, position, title, description, icon, game_type, config, is_free)
                              VALUES (?, ?, ?, ?, ?, ?, ?, 0)',
                             [$actividadId, $pos, $e['titulo'], $e['descripcion'], $e['icono'], $e['tipo'], $json]);
                }
                continue;
            }

            $igual = $fila['title'] === $e['titulo'] && $fila['game_type'] === $e['tipo']
                && json_decode((string) $fila['config'], true) == $config;
            if ($igual) {
                continue;
            }

            echo "  [->] {$act['slug']} E$pos «{$e['titulo']}»"
               . ($fila['game_type'] !== $e['tipo'] ? " ({$fila['game_type']} → {$e['tipo']})" : '') . "\n";
            $actualizadas++;
            if ($aplicar) {
                ejecutar('UPDATE activity_stations
                             SET title = ?, description = ?, icon = ?, game_type = ?, config = ?
                           WHERE id = ?',
                         [$e['titulo'], $e['descripcion'], $e['icono'], $e['tipo'], $json, (int) $fila['id']]);
            }
        }

        /*
         * Las estaciones de más son las que generó enriquecer-actividades.php
         * (Repaso, Desafío final, Memoria…) con las preguntas VIEJAS, en
         * español. Se vuelven a generar con el contenido nuevo, en su
         * misma fila, y ahora con el idioma de la actividad.
         */
        if (count($enBase) > $pos) {
            $filas = [];
            foreach ($act['estaciones'] as $e) {
                $filas[] = ['title' => $e['titulo'], 'game_type' => $e['tipo'],
                            'config' => json_encode(['datos' => ilustrar($e['datos'], (string) $e['tipo'])],
                                                    JSON_UNESCAPED_UNICODE)];
            }
            $cosecha = cosecharDe($filas);
            $idioma  = $act['estaciones'][0]['idioma'] ?? null;

            foreach ($enBase as $p => $fila) {
                if ($p <= $pos) {
                    continue;
                }
                $cfg = json_decode((string) $fila['config'], true) ?: [];
                if (($cfg['origen'] ?? '') !== MARCA_GENERADA) {
                    $avisos[] = "{$act['slug']} E$p «{$fila['title']}»: sobra y no es generada, se deja";
                    continue;
                }

                $nueva = regenerada((string) $fila['title'], $cosecha, $filas);
                if (!$nueva) {
                    $avisos[] = "{$act['slug']} E$p «{$fila['title']}»: no se pudo regenerar, se deja";
                    continue;
                }

                $config = ['datos' => $nueva['datos'], 'origen' => MARCA_GENERADA];
                if ($idioma) {
                    $config['idioma'] = $idioma;
                }

                echo "  [~] {$act['slug']} E$p «{$fila['title']}» (generada, regenerada)\n";
                $regeneradas++;
                if ($aplicar) {
                    ejecutar('UPDATE activity_stations SET game_type = ?, config = ? WHERE id = ?',
                             [$nueva['tipo'],
                              json_encode($config, JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
                              (int) $fila['id']]);
                }
            }
        }
    }
}

foreach ($avisos as $a) {
    echo "  [!!] $a\n";
}

echo "\n$actualizadas estacion(es) actualizada(s), $nuevas nueva(s), $regeneradas generada(s) rehecha(s).\n";

if ($aplicar) {
    ejecutar('INSERT INTO settings (`key`, `value`, `label`) VALUES (?, ?, ?)',
             [MARCA, date('Y-m-d H:i:s'), 'Inmersión en idiomas aplicada (no repetir)']);
    echo "Listo.\n";
} else {
    echo "Simulacion. Nada se escribio. Repite con --aplicar\n";
}
