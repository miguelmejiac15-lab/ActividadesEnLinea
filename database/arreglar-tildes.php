<?php
/**
 * arreglar-tildes.php — Pone las tildes que faltan en las estaciones de
 * escribir que NO vienen de ningún archivo de contenido.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ HACE FALTA UN SCRIPT Y NO BASTA CON EDITAR UN ARCHIVO
 * ---------------------------------------------------------------------
 *
 * Casi todo el catálogo se siembra desde `database/contenido/*.php`, y
 * ahí una falta se corrige editando el archivo y volviendo a sembrar.
 *
 * Pero quedan estaciones heredadas del sitio anterior que viven solo en
 * la base de datos: «La Neo-Computadora» es una de ellas, y su
 * mecanografía enseñaba a escribir LEON y NINO. Ningún sembrador las
 * vuelve a escribir, así que hay que tocarlas donde están.
 *
 * ---------------------------------------------------------------------
 *  LO QUE NO TOCA
 * ---------------------------------------------------------------------
 *
 *  · La sopa y el crucigrama, que renuncian a las tildes por la rejilla.
 *  · Las opciones falsas de un ejercicio de ortografía.
 *  · Idiomas: «Sofa» en inglés está bien escrito.
 *  · Las sílabas sueltas de un puzle.
 *
 * Todo eso lo explica `revisar-ortografia.php`, que es quien encuentra lo
 * que este script arregla. Corre ese primero.
 *
 * Uso:
 *     php database/arreglar-tildes.php              (simulación)
 *     php database/arreglar-tildes.php --aplicar
 */

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

$aplicar = in_array('--aplicar', $argv, true);

/**
 * Palabra mal escrita => como se escribe.
 *
 * En MAYÚSCULAS porque así están en las mecánicas de escribir. Solo se
 * cambia la palabra ENTERA: nada de reemplazar dentro de otra, que
 * convertiría CAMIONETA en CAMIÓNETA.
 */
const ARREGLOS = [
    'LEON' => 'LEÓN',           'NINO' => 'NIÑO',
    'NINA' => 'NIÑA',           'ARBOL' => 'ÁRBOL',
    'ARBOLES' => 'ÁRBOLES',     'LAPIZ' => 'LÁPIZ',
    'LAPICES' => 'LÁPICES',     'MURCIELAGO' => 'MURCIÉLAGO',
    'TRIANGULO' => 'TRIÁNGULO', 'CAMION' => 'CAMIÓN',
    'AVION' => 'AVIÓN',         'BALON' => 'BALÓN',
    'RATON' => 'RATÓN',         'CORAZON' => 'CORAZÓN',
    'JAMON' => 'JAMÓN',         'JABON' => 'JABÓN',
    'LIMON' => 'LIMÓN',         'TAZON' => 'TAZÓN',
    'COLCHON' => 'COLCHÓN',     'PANTALON' => 'PANTALÓN',
    'DRAGON' => 'DRAGÓN',       'VOLCAN' => 'VOLCÁN',
    'IMAN' => 'IMÁN',           'AUTOBUS' => 'AUTOBÚS',
    'AZUCAR' => 'AZÚCAR',       'MAIZ' => 'MAÍZ',
    'BEBE' => 'BEBÉ',           'BUHO' => 'BÚHO',
    'SOFA' => 'SOFÁ',           'GRUA' => 'GRÚA',
    'MUSICA' => 'MÚSICA',       'FABRICA' => 'FÁBRICA',
    'MAQUINA' => 'MÁQUINA',     'BRUJULA' => 'BRÚJULA',
    'BATERIA' => 'BATERÍA',     'SANDIA' => 'SANDÍA',
    'POLICIA' => 'POLICÍA',     'PLATANO' => 'PLÁTANO',
    'PINGUINO' => 'PINGÜINO',   'DELFIN' => 'DELFÍN',
    'JABALI' => 'JABALÍ',       'AGUILA' => 'ÁGUILA',
    'TIBURON' => 'TIBURÓN',     'VIBORA' => 'VÍBORA',
    'VIOLIN' => 'VIOLÍN',       'ESQUI' => 'ESQUÍ',
    'SATELITE' => 'SATÉLITE',   'SEMAFORO' => 'SEMÁFORO',
    'TERMOMETRO' => 'TERMÓMETRO', 'TELEFONO' => 'TELÉFONO',
    'MICROFONO' => 'MICRÓFONO', 'XILOFONO' => 'XILÓFONO',
    'SAXOFON' => 'SAXOFÓN',     'HIPOPOTAMO' => 'HIPOPÓTAMO',
    'EXPLOSION' => 'EXPLOSIÓN', 'MEXICO' => 'MÉXICO',
    'JARDIN' => 'JARDÍN',       'CRAYON' => 'CRAYÓN',
];

/** Solo donde la palabra escrita ES el contenido. */
const MECANICAS = ['teclado', 'armar_palabras'];

$filas = traerTodo(
    'SELECT s.id, s.game_type, s.title, s.config, a.slug, c.name AS categoria
       FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
       LEFT JOIN categories c ON c.id = a.category_id
      WHERE s.game_type IN ("' . implode('", "', MECANICAS) . '")');

echo "\n";
echo "==========================================================================\n";
echo "  TILDES QUE FALTAN EN LAS ESTACIONES DE ESCRIBIR\n";
echo "==========================================================================\n\n";

$cambiadas = 0;
$palabras  = 0;

foreach ($filas as $f) {

    // El inglés no se acentúa con las reglas del español.
    if (stripos((string) ($f['categoria'] ?? ''), 'idioma') !== false) {
        continue;
    }

    $cfg = json_decode((string) $f['config'], true);

    if (!is_array($cfg) || !isset($cfg['datos'])) {
        continue;
    }

    $hechos = [];

    /** Cambia la palabra entera, nunca un trozo de otra. */
    $arreglar = static function ($v) use (&$hechos) {
        if (!is_string($v)) {
            return $v;
        }

        $bien = ARREGLOS[mb_strtoupper($v)] ?? null;

        if ($bien === null || $bien === $v) {
            return $v;
        }

        $hechos[] = "$v → $bien";

        return $bien;
    };

    if ($f['game_type'] === 'teclado') {
        // Los datos son la lista de palabras, a pelo.
        $cfg['datos'] = array_map($arreglar, (array) $cfg['datos']);
    } else {
        // «Armar palabras» guarda {e, w}: solo se toca la palabra.
        foreach ($cfg['datos'] as $i => $it) {
            if (is_array($it) && isset($it['w'])) {
                $cfg['datos'][$i]['w'] = $arreglar($it['w']);
            }
        }
    }

    if (!$hechos) {
        continue;
    }

    $cambiadas++;
    $palabras += count($hechos);

    printf("  %-32s %-18s %s\n      %s\n",
        $f['slug'], $f['game_type'], $f['title'], implode('  ·  ', $hechos));

    if ($aplicar) {
        ejecutar('UPDATE activity_stations SET config = ? WHERE id = ?',
            [json_encode($cfg, JSON_UNESCAPED_UNICODE), (int) $f['id']]);
    }
}

echo "\n--------------------------------------------------------------------------\n";
printf("  Estaciones tocadas : %d\n", $cambiadas);
printf("  Palabras corregidas: %d\n\n", $palabras);

if (!$cambiadas) {
    echo "  ✅ Nada que corregir.\n";
} elseif ($aplicar) {
    echo "  ✅ Aplicado.\n";
} else {
    echo "  Simulación. Para escribir:\n";
    echo "    php database/arreglar-tildes.php --aplicar\n";
}

echo "==========================================================================\n\n";
