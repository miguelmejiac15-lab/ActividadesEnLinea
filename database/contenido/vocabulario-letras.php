<?php
/**
 * vocabulario-letras.php — Banco de palabras de «Aventura de las Letras»
 *
 * POR QUÉ EXISTE
 *
 * Las 23 letras y las 5 vocales se migraron del sitio anterior tal cual, y
 * ese sitio traía **tres palabras por estación**. Una letra entera se
 * enseñaba con MANO, MESA y MONO repetidas quince veces: el niño no
 * aprendía la M, aprendía esas tres palabras. El promedio de toda la
 * categoría era de 4,1 palabras por estación.
 *
 * Aquí está el vocabulario ampliado, y `enriquecer-letras.php` lo usa para
 * regenerar las estaciones. El objetivo es un promedio de unas diez
 * palabras por estación.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA QUE MANDA SOBRE TODAS: EL DIBUJO TIENE QUE SER EL CORRECTO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Ninguna palabra entra aquí si no tiene un dibujo que la represente de
 * verdad. Nada de «el más parecido»: en lectoescritura, un niño que ve una
 * silla debajo de la palabra MESA no corrige esa asociación, la memoriza
 * (ver `docs/iconos/faltantes.md`).
 *
 * Eso descarta palabras que encajarían de maravilla. FRAMBUESA se quedó
 * fuera porque 🫐 son arándanos; INSTRUMENTO, porque ilustrarlo con una
 * trompeta enseña «trompeta».
 *
 * Por eso hay letras con veintitantas palabras y otras con cinco. **K, W,
 * X, la I y la U son cortas a propósito**: el español tiene muy pocas
 * palabras con ellas y casi ninguna tiene emoji. Inflar esas listas
 * obligaría a inventar parejas falsas, y es mejor tener seis palabras bien
 * ilustradas que doce mal. Lo que falta ahí no es contenido: es idioma.
 *
 * Cuando el emoji no existe pero la palabra vale la pena, se usa el dibujo
 * propio con el prefijo `icono:` (`assets/iconos/*.svg`).
 *
 * ─────────────────────────────────────────────────────────────────────
 *  DÓNDE VA LA LETRA
 * ─────────────────────────────────────────────────────────────────────
 *
 * `posicion` dice si la letra se enseña al principio de la palabra
 * (`inicial`) o en cualquier sitio (`contiene`). La Ñ, la X, la Y, la Z,
 * el CH, el QU y el GUE/GUI casi no aparecen al principio en español
 * —niño, taxi, playa, lápiz, leche, queso, águila— así que preguntar
 * «¿empieza con...?» sería enseñar algo falso. El generador cambia la
 * consigna según este campo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  FORMATO
 * ─────────────────────────────────────────────────────────────────────
 *
 *   'w'    la palabra, en mayúsculas y con su tilde
 *   'e'    su dibujo: un emoji, o `icono:nombre` para los propios
 *   'syls' su división silábica, escrita a mano
 *
 * Las sílabas van a mano y no calculadas: separar en español pide reglas
 * de diptongo, hiato y grupo consonántico que un algoritmo corto falla, y
 * fallarlas aquí es enseñar a leer mal.
 *
 * Dentro de una misma letra no se repite ningún dibujo: el minijuego de
 * memoria busca las parejas por el símbolo y dos iguales la harían
 * ambigua. Entre letras distintas sí puede repetirse — nadie ve la M y la
 * P a la vez.
 *
 * Los distractores —las palabras que NO llevan la letra— no se escriben
 * por letra: salen del pozo común de abajo, y el generador descarta solo
 * las que contengan la letra que se está enseñando.
 */

declare(strict_types=1);

return [

// =====================================================================
//  POZO COMÚN DE DISTRACTORES
//
//  Palabras frecuentes con dibujo inequívoco. El generador toma de aquí
//  las respuestas «no» de los ejercicios de sí/no, saltándose las que
//  contengan la letra que se enseña — si no, la M usaría «Cama» como
//  distractor y el ejercicio no tendría respuesta correcta.
// =====================================================================

'distractores' => [
    ['n' => 'Sol',       'e' => '☀️'],
    ['n' => 'Luna',      'e' => '🌙'],
    ['n' => 'Casa',      'e' => '🏠'],
    ['n' => 'Perro',     'e' => '🐶'],
    ['n' => 'Gato',      'e' => '🐱'],
    ['n' => 'Árbol',     'e' => '🌳'],
    ['n' => 'Flor',      'e' => '🌸'],
    ['n' => 'Agua',      'e' => '💧'],
    ['n' => 'Pez',       'e' => '🐟'],
    ['n' => 'Pato',      'e' => '🦆'],
    ['n' => 'Rana',      'e' => '🐸'],
    ['n' => 'León',      'e' => '🦁'],
    ['n' => 'Libro',     'e' => '📕'],
    ['n' => 'Silla',     'e' => '🪑'],
    ['n' => 'Reloj',     'e' => '⏰'],
    ['n' => 'Globo',     'e' => '🎈'],
    ['n' => 'Barco',     'e' => '🚢'],
    ['n' => 'Tren',      'e' => '🚆'],
    ['n' => 'Nube',      'e' => '☁️'],
    ['n' => 'Fuego',     'e' => '🔥'],
    ['n' => 'Huevo',     'e' => '🥚'],
    ['n' => 'Queso',     'e' => '🧀'],
    ['n' => 'Pan',       'e' => '🍞'],
    ['n' => 'Uva',       'e' => '🍇'],
    ['n' => 'Piña',      'e' => '🍍'],
    ['n' => 'Fresa',     'e' => '🍓'],
    ['n' => 'Llave',     'e' => '🔑'],
    ['n' => 'Vaca',      'e' => '🐄'],
    ['n' => 'Oso',       'e' => '🐻'],
    ['n' => 'Abeja',     'e' => '🐝'],
    ['n' => 'Regalo',    'e' => '🎁'],
    ['n' => 'Tijeras',   'e' => '✂️'],
    ['n' => 'Guitarra',  'e' => '🎸'],
    ['n' => 'Tortuga',   'e' => '🐢'],
    ['n' => 'Zapato',    'e' => '👞'],
    ['n' => 'Ojo',       'e' => '👁️'],
    ['n' => 'Nariz',     'e' => '👃'],
    ['n' => 'Mano',      'e' => '✋'],
    ['n' => 'Corazón',   'e' => '❤️'],
    ['n' => 'Estrella',  'e' => '⭐'],
    ['n' => 'Camisa',    'e' => '👕'],
    ['n' => 'Reina',     'e' => '👑'],
    ['n' => 'Cebolla',   'e' => '🧅'],
    ['n' => 'Melón',     'e' => '🍈'],
    ['n' => 'Sombrero',  'e' => '🎩'],
    ['n' => 'Cohete',    'e' => '🚀'],
],


// =====================================================================
//  LAS LETRAS
// =====================================================================

'letras' => [

// ── M ────────────────────────────────────────────────────────────────
'letra-m' => [
    'letra' => 'M', 'sonido' => 'Mmmmm', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'MANO',        'e' => '✋',          'syls' => ['MA', 'NO']],
        ['w' => 'MESA',        'e' => 'icono:mesa',  'syls' => ['ME', 'SA']],
        ['w' => 'MONO',        'e' => '🐵',          'syls' => ['MO', 'NO']],
        ['w' => 'MAR',         'e' => '🌊',          'syls' => ['MAR']],
        ['w' => 'MIEL',        'e' => '🍯',          'syls' => ['MIEL']],
        ['w' => 'MAPA',        'e' => '🗺️',          'syls' => ['MA', 'PA']],
        ['w' => 'MOTO',        'e' => '🛵',          'syls' => ['MO', 'TO']],
        ['w' => 'MAGO',        'e' => '🧙',          'syls' => ['MA', 'GO']],
        ['w' => 'MAÍZ',        'e' => '🌽',          'syls' => ['MA', 'ÍZ']],
        ['w' => 'MOSCA',       'e' => '🪰',          'syls' => ['MOS', 'CA']],
        ['w' => 'MEDUSA',      'e' => '🪼',          'syls' => ['ME', 'DU', 'SA']],
        ['w' => 'MANZANA',     'e' => '🍎',          'syls' => ['MAN', 'ZA', 'NA']],
        ['w' => 'MARIPOSA',    'e' => '🦋',          'syls' => ['MA', 'RI', 'PO', 'SA']],
        ['w' => 'MONTAÑA',     'e' => '⛰️',          'syls' => ['MON', 'TA', 'ÑA']],
        ['w' => 'MÚSICA',      'e' => '🎵',          'syls' => ['MÚ', 'SI', 'CA']],
        ['w' => 'MALETA',      'e' => '🧳',          'syls' => ['MA', 'LE', 'TA']],
        ['w' => 'MARTILLO',    'e' => '🔨',          'syls' => ['MAR', 'TI', 'LLO']],
        ['w' => 'MOCHILA',     'e' => '🎒',          'syls' => ['MO', 'CHI', 'LA']],
        ['w' => 'MEDALLA',     'e' => '🏅',          'syls' => ['ME', 'DA', 'LLA']],
        ['w' => 'MURCIÉLAGO',  'e' => '🦇',          'syls' => ['MUR', 'CIÉ', 'LA', 'GO']],
        ['w' => 'MICRÓFONO',   'e' => '🎤',          'syls' => ['MI', 'CRÓ', 'FO', 'NO']],
        ['w' => 'MICROSCOPIO', 'e' => '🔬',          'syls' => ['MI', 'CROS', 'CO', 'PIO']],
    ],
],

// ── P ────────────────────────────────────────────────────────────────
'letra-p' => [
    'letra' => 'P', 'sonido' => 'Ppp', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'PAN',        'e' => '🍞', 'syls' => ['PAN']],
        ['w' => 'PIE',        'e' => '🦶', 'syls' => ['PIE']],
        ['w' => 'PEZ',        'e' => '🐟', 'syls' => ['PEZ']],
        ['w' => 'PERA',       'e' => '🍐', 'syls' => ['PE', 'RA']],
        ['w' => 'PATO',       'e' => '🦆', 'syls' => ['PA', 'TO']],
        ['w' => 'PERRO',      'e' => '🐶', 'syls' => ['PE', 'RRO']],
        ['w' => 'PIÑA',       'e' => '🍍', 'syls' => ['PI', 'ÑA']],
        ['w' => 'PULPO',      'e' => '🐙', 'syls' => ['PUL', 'PO']],
        ['w' => 'PIZZA',      'e' => '🍕', 'syls' => ['PIZ', 'ZA']],
        ['w' => 'PUERTA',     'e' => '🚪', 'syls' => ['PUER', 'TA']],
        ['w' => 'PIANO',      'e' => '🎹', 'syls' => ['PIA', 'NO']],
        ['w' => 'PINCEL',     'e' => '🖌️', 'syls' => ['PIN', 'CEL']],
        ['w' => 'PUENTE',     'e' => '🌉', 'syls' => ['PUEN', 'TE']],
        ['w' => 'PALOMA',     'e' => '🕊️', 'syls' => ['PA', 'LO', 'MA']],
        ['w' => 'PELOTA',     'e' => '⚽', 'syls' => ['PE', 'LO', 'TA']],
        ['w' => 'POLICÍA',    'e' => '👮', 'syls' => ['PO', 'LI', 'CÍ', 'A']],
        ['w' => 'PLÁTANO',    'e' => '🍌', 'syls' => ['PLÁ', 'TA', 'NO']],
        ['w' => 'PALMERA',    'e' => '🌴', 'syls' => ['PAL', 'ME', 'RA']],
        ['w' => 'PATINETA',   'e' => '🛹', 'syls' => ['PA', 'TI', 'NE', 'TA']],
        ['w' => 'PARAGUAS',   'e' => '☂️', 'syls' => ['PA', 'RA', 'GUAS']],
        ['w' => 'PINGÜINO',   'e' => '🐧', 'syls' => ['PIN', 'GÜI', 'NO']],
        ['w' => 'PANTALÓN',   'e' => '👖', 'syls' => ['PAN', 'TA', 'LÓN']],
    ],
],

// ── S ────────────────────────────────────────────────────────────────
'letra-s' => [
    'letra' => 'S', 'sonido' => 'Sssss', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'SOL',        'e' => '☀️', 'syls' => ['SOL']],
        ['w' => 'SAL',        'e' => '🧂', 'syls' => ['SAL']],
        ['w' => 'SEIS',       'e' => '6️⃣', 'syls' => ['SEIS']],
        ['w' => 'SAPO',       'e' => '🐸', 'syls' => ['SA', 'PO']],
        ['w' => 'SOFÁ',       'e' => '🛋️', 'syls' => ['SO', 'FÁ']],
        ['w' => 'SILLA',      'e' => '🪑', 'syls' => ['SI', 'LLA']],
        ['w' => 'SOPA',       'e' => '🍲', 'syls' => ['SO', 'PA']],
        ['w' => 'SOBRE',      'e' => '✉️', 'syls' => ['SO', 'BRE']],
        ['w' => 'SIETE',      'e' => '7️⃣', 'syls' => ['SIE', 'TE']],
        ['w' => 'SIRENA',     'e' => '🧜', 'syls' => ['SI', 'RE', 'NA']],
        ['w' => 'SEMILLA',    'e' => '🌱', 'syls' => ['SE', 'MI', 'LLA']],
        ['w' => 'SANDÍA',     'e' => '🍉', 'syls' => ['SAN', 'DÍ', 'A']],
        ['w' => 'SERRUCHO',   'e' => '🪚', 'syls' => ['SE', 'RRU', 'CHO']],
        ['w' => 'SATÉLITE',   'e' => '🛰️', 'syls' => ['SA', 'TÉ', 'LI', 'TE']],
        ['w' => 'SEMÁFORO',   'e' => '🚦', 'syls' => ['SE', 'MÁ', 'FO', 'RO']],
        ['w' => 'SOMBRERO',   'e' => '🎩', 'syls' => ['SOM', 'BRE', 'RO']],
        ['w' => 'SERPIENTE',  'e' => '🐍', 'syls' => ['SER', 'PIEN', 'TE']],
        ['w' => 'SANDALIA',   'e' => '👡', 'syls' => ['SAN', 'DA', 'LIA']],
    ],
],

// ── L ────────────────────────────────────────────────────────────────
'letra-l' => [
    'letra' => 'L', 'sonido' => 'Lllll', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'LUZ',        'e' => '💡', 'syls' => ['LUZ']],
        ['w' => 'LUNA',       'e' => '🌙', 'syls' => ['LU', 'NA']],
        ['w' => 'LOBO',       'e' => '🐺', 'syls' => ['LO', 'BO']],
        ['w' => 'LORO',       'e' => '🦜', 'syls' => ['LO', 'RO']],
        ['w' => 'LAZO',       'e' => '🎀', 'syls' => ['LA', 'ZO']],
        ['w' => 'LEÓN',       'e' => '🦁', 'syls' => ['LE', 'ÓN']],
        ['w' => 'LECHE',      'e' => '🥛', 'syls' => ['LE', 'CHE']],
        ['w' => 'LLAVE',      'e' => '🔑', 'syls' => ['LLA', 'VE']],
        ['w' => 'LIBRO',      'e' => '📕', 'syls' => ['LI', 'BRO']],
        ['w' => 'LÁPIZ',      'e' => '✏️', 'syls' => ['LÁ', 'PIZ']],
        ['w' => 'LIMÓN',      'e' => '🍋', 'syls' => ['LI', 'MÓN']],
        ['w' => 'LENTES',     'e' => '👓', 'syls' => ['LEN', 'TES']],
        ['w' => 'LANCHA',     'e' => '🚤', 'syls' => ['LAN', 'CHA']],
        ['w' => 'LECHUGA',    'e' => '🥬', 'syls' => ['LE', 'CHU', 'GA']],
        ['w' => 'LADRILLO',   'e' => '🧱', 'syls' => ['LA', 'DRI', 'LLO']],
        ['w' => 'LINTERNA',   'e' => '🔦', 'syls' => ['LIN', 'TER', 'NA']],
        ['w' => 'LEOPARDO',   'e' => '🐆', 'syls' => ['LE', 'O', 'PAR', 'DO']],
        ['w' => 'LAGARTIJA',  'e' => '🦎', 'syls' => ['LA', 'GAR', 'TI', 'JA']],
    ],
],

// ── T ────────────────────────────────────────────────────────────────
'letra-t' => [
    'letra' => 'T', 'sonido' => 'Ttt', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'TÉ',         'e' => '🍵', 'syls' => ['TÉ']],
        ['w' => 'TAZA',       'e' => '☕', 'syls' => ['TA', 'ZA']],
        ['w' => 'TORO',       'e' => '🐂', 'syls' => ['TO', 'RO']],
        ['w' => 'TREN',       'e' => '🚆', 'syls' => ['TREN']],
        ['w' => 'TAXI',       'e' => '🚕', 'syls' => ['TA', 'XI']],
        ['w' => 'TIGRE',      'e' => '🐯', 'syls' => ['TI', 'GRE']],
        ['w' => 'TORTA',      'e' => '🎂', 'syls' => ['TOR', 'TA']],
        ['w' => 'TOMATE',     'e' => '🍅', 'syls' => ['TO', 'MA', 'TE']],
        ['w' => 'TAMBOR',     'e' => '🥁', 'syls' => ['TAM', 'BOR']],
        ['w' => 'TIBURÓN',    'e' => '🦈', 'syls' => ['TI', 'BU', 'RÓN']],
        ['w' => 'TENEDOR',    'e' => '🍴', 'syls' => ['TE', 'NE', 'DOR']],
        ['w' => 'TORNILLO',   'e' => '🔩', 'syls' => ['TOR', 'NI', 'LLO']],
        ['w' => 'TROMPETA',   'e' => '🎺', 'syls' => ['TROM', 'PE', 'TA']],
        ['w' => 'TORTUGA',    'e' => '🐢', 'syls' => ['TOR', 'TU', 'GA']],
        ['w' => 'TIJERAS',    'e' => '✂️', 'syls' => ['TI', 'JE', 'RAS']],
        ['w' => 'TRACTOR',    'e' => '🚜', 'syls' => ['TRAC', 'TOR']],
        ['w' => 'TELEVISOR',  'e' => '📺', 'syls' => ['TE', 'LE', 'VI', 'SOR']],
        ['w' => 'TERMÓMETRO', 'e' => '🌡️', 'syls' => ['TER', 'MÓ', 'ME', 'TRO']],
        ['w' => 'TELÉFONO',   'e' => '📞', 'syls' => ['TE', 'LÉ', 'FO', 'NO']],
    ],
],

// ── D ────────────────────────────────────────────────────────────────
'letra-d' => [
    'letra' => 'D', 'sonido' => 'Ddd', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'DOS',        'e' => '2️⃣', 'syls' => ['DOS']],
        ['w' => 'DIEZ',       'e' => '🔟', 'syls' => ['DIEZ']],
        ['w' => 'DADO',       'e' => '🎲', 'syls' => ['DA', 'DO']],
        ['w' => 'DEDO',       'e' => '👆', 'syls' => ['DE', 'DO']],
        ['w' => 'DONA',       'e' => '🍩', 'syls' => ['DO', 'NA']],
        ['w' => 'DISCO',      'e' => '💿', 'syls' => ['DIS', 'CO']],
        ['w' => 'DUCHA',      'e' => '🚿', 'syls' => ['DU', 'CHA']],
        ['w' => 'DULCE',      'e' => '🍬', 'syls' => ['DUL', 'CE']],
        ['w' => 'DÓLAR',      'e' => '💵', 'syls' => ['DÓ', 'LAR']],
        ['w' => 'DIENTE',     'e' => '🦷', 'syls' => ['DIEN', 'TE']],
        ['w' => 'DELFÍN',     'e' => '🐬', 'syls' => ['DEL', 'FÍN']],
        ['w' => 'DRAGÓN',     'e' => '🐉', 'syls' => ['DRA', 'GÓN']],
        ['w' => 'DOCTOR',     'e' => '👨‍⚕️', 'syls' => ['DOC', 'TOR']],
        ['w' => 'DINERO',     'e' => '💰', 'syls' => ['DI', 'NE', 'RO']],
        ['w' => 'DESIERTO',   'e' => '🏜️', 'syls' => ['DE', 'SIER', 'TO']],
        ['w' => 'DIAMANTE',   'e' => '💎', 'syls' => ['DIA', 'MAN', 'TE']],
        ['w' => 'DINOSAURIO', 'e' => '🦕', 'syls' => ['DI', 'NO', 'SAU', 'RIO']],
    ],
],

// ── Ñ ────────────────────────────────────────────────────────────────
//
// La única letra del abecedario que casi no existe al principio de
// palabra: ñame y ñandú, y poco más. Se enseña donde de verdad aparece.
'letra-n' => [
    'letra' => 'Ñ', 'sonido' => 'Ññ', 'posicion' => 'contiene',
    'palabras' => [
        ['w' => 'UÑA',        'e' => '💅',           'syls' => ['U', 'ÑA']],
        ['w' => 'AÑO',        'e' => '📅',           'syls' => ['A', 'ÑO']],
        ['w' => 'PUÑO',       'e' => '👊',           'syls' => ['PU', 'ÑO']],
        ['w' => 'NIÑO',       'e' => '🧒',           'syls' => ['NI', 'ÑO']],
        ['w' => 'PIÑA',       'e' => '🍍',           'syls' => ['PI', 'ÑA']],
        ['w' => 'BAÑO',       'e' => '🛁',           'syls' => ['BA', 'ÑO']],
        ['w' => 'LEÑA',       'e' => '🪵',           'syls' => ['LE', 'ÑA']],
        ['w' => 'ÑAME',       'e' => 'icono:name',   'syls' => ['ÑA', 'ME']],
        ['w' => 'SUEÑO',      'e' => '😴',           'syls' => ['SUE', 'ÑO']],
        ['w' => 'MUÑECO',     'e' => '⛄',           'syls' => ['MU', 'ÑE', 'CO']],
        ['w' => 'MAÑANA',     'e' => '🌅',           'syls' => ['MA', 'ÑA', 'NA']],
        ['w' => 'CABAÑA',     'e' => '🛖',           'syls' => ['CA', 'BA', 'ÑA']],
        ['w' => 'ARAÑA',      'e' => '🕷️',           'syls' => ['A', 'RA', 'ÑA']],
        ['w' => 'SEÑAL',      'e' => '🚦',           'syls' => ['SE', 'ÑAL']],
        ['w' => 'ESPAÑA',     'e' => '🇪🇸',           'syls' => ['ES', 'PA', 'ÑA']],
        ['w' => 'ÑANDÚ',      'e' => 'icono:nandu',  'syls' => ['ÑAN', 'DÚ']],
        ['w' => 'MONTAÑA',    'e' => '⛰️',           'syls' => ['MON', 'TA', 'ÑA']],
    ],
],

// ── B ────────────────────────────────────────────────────────────────
'letra-b' => [
    'letra' => 'B', 'sonido' => 'Bbb', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'BOCA',       'e' => '👄', 'syls' => ['BO', 'CA']],
        ['w' => 'BOTA',       'e' => '👢', 'syls' => ['BO', 'TA']],
        ['w' => 'BEBÉ',       'e' => '👶', 'syls' => ['BE', 'BÉ']],
        ['w' => 'BÚHO',       'e' => '🦉', 'syls' => ['BÚ', 'HO']],
        ['w' => 'BURRO',      'e' => '🫏', 'syls' => ['BU', 'RRO']],
        ['w' => 'BANCO',      'e' => '🏦', 'syls' => ['BAN', 'CO']],
        ['w' => 'BOLSA',      'e' => '🛍️', 'syls' => ['BOL', 'SA']],
        ['w' => 'BARCO',      'e' => '🚢', 'syls' => ['BAR', 'CO']],
        ['w' => 'BOSQUE',     'e' => '🌲', 'syls' => ['BOS', 'QUE']],
        ['w' => 'BALÓN',      'e' => '⚽', 'syls' => ['BA', 'LÓN']],
        ['w' => 'BANANO',     'e' => '🍌', 'syls' => ['BA', 'NA', 'NO']],
        ['w' => 'BATERÍA',    'e' => '🔋', 'syls' => ['BA', 'TE', 'RÍ', 'A']],
        ['w' => 'BRÚJULA',    'e' => '🧭', 'syls' => ['BRÚ', 'JU', 'LA']],
        ['w' => 'BALLENA',    'e' => '🐳', 'syls' => ['BA', 'LLE', 'NA']],
        ['w' => 'BANDERA',    'e' => '🚩', 'syls' => ['BAN', 'DE', 'RA']],
        ['w' => 'BOMBILLA',   'e' => '💡', 'syls' => ['BOM', 'BI', 'LLA']],
        ['w' => 'BOMBERO',    'e' => '👨‍🚒', 'syls' => ['BOM', 'BE', 'RO']],
        ['w' => 'BICICLETA',  'e' => '🚲', 'syls' => ['BI', 'CI', 'CLE', 'TA']],
    ],
],

// ── C ────────────────────────────────────────────────────────────────
'letra-c' => [
    'letra' => 'C', 'sonido' => 'Ccc', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'CASA',       'e' => '🏠', 'syls' => ['CA', 'SA']],
        ['w' => 'CAMA',       'e' => '🛏️', 'syls' => ['CA', 'MA']],
        ['w' => 'COCO',       'e' => '🥥', 'syls' => ['CO', 'CO']],
        ['w' => 'CERDO',      'e' => '🐷', 'syls' => ['CER', 'DO']],
        ['w' => 'CARRO',      'e' => '🚗', 'syls' => ['CA', 'RRO']],
        ['w' => 'CEBRA',      'e' => '🦓', 'syls' => ['CE', 'BRA']],
        ['w' => 'CACTUS',     'e' => '🌵', 'syls' => ['CAC', 'TUS']],
        ['w' => 'CAMISA',     'e' => '👕', 'syls' => ['CA', 'MI', 'SA']],
        ['w' => 'CONEJO',     'e' => '🐰', 'syls' => ['CO', 'NE', 'JO']],
        ['w' => 'CEREZA',     'e' => '🍒', 'syls' => ['CE', 'RE', 'ZA']],
        ['w' => 'CEBOLLA',    'e' => '🧅', 'syls' => ['CE', 'BO', 'LLA']],
        ['w' => 'COHETE',     'e' => '🚀', 'syls' => ['CO', 'HE', 'TE']],
        ['w' => 'CAMIÓN',     'e' => '🚚', 'syls' => ['CA', 'MIÓN']],
        ['w' => 'CANDADO',    'e' => '🔒', 'syls' => ['CAN', 'DA', 'DO']],
        ['w' => 'CAMPANA',    'e' => '🔔', 'syls' => ['CAM', 'PA', 'NA']],
        ['w' => 'CANGREJO',   'e' => '🦀', 'syls' => ['CAN', 'GRE', 'JO']],
        ['w' => 'CORAZÓN',    'e' => '❤️', 'syls' => ['CO', 'RA', 'ZÓN']],
        ['w' => 'CABALLO',    'e' => '🐴', 'syls' => ['CA', 'BA', 'LLO']],
        ['w' => 'CASTILLO',   'e' => '🏰', 'syls' => ['CAS', 'TI', 'LLO']],
        ['w' => 'CALABAZA',   'e' => '🎃', 'syls' => ['CA', 'LA', 'BA', 'ZA']],
    ],
],

// ── F ────────────────────────────────────────────────────────────────
'letra-f' => [
    'letra' => 'F', 'sonido' => 'Fffff', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'FOCA',       'e' => '🦭',           'syls' => ['FO', 'CA']],
        ['w' => 'FLOR',       'e' => '🌸',           'syls' => ['FLOR']],
        ['w' => 'FOTO',       'e' => '📷',           'syls' => ['FO', 'TO']],
        ['w' => 'FUEGO',      'e' => '🔥',           'syls' => ['FUE', 'GO']],
        ['w' => 'FRESA',      'e' => '🍓',           'syls' => ['FRE', 'SA']],
        ['w' => 'FRIJOL',     'e' => '🫘',           'syls' => ['FRI', 'JOL']],
        ['w' => 'FIESTA',     'e' => '🎉',           'syls' => ['FIES', 'TA']],
        ['w' => 'FIDEOS',     'e' => '🍜',           'syls' => ['FI', 'DE', 'OS']],
        ['w' => 'FLECHA',     'e' => '➡️',           'syls' => ['FLE', 'CHA']],
        ['w' => 'FLAUTA',     'e' => 'icono:flauta', 'syls' => ['FLAU', 'TA']],
        ['w' => 'FÁBRICA',    'e' => '🏭',           'syls' => ['FÁ', 'BRI', 'CA']],
        ['w' => 'FLAMENCO',   'e' => '🦩',           'syls' => ['FLA', 'MEN', 'CO']],
        ['w' => 'FANTASMA',   'e' => '👻',           'syls' => ['FAN', 'TAS', 'MA']],
        // Nada de 🫐 para FRAMBUESA: ese emoji son arándanos.
    ],
],

// ── G ────────────────────────────────────────────────────────────────
'letra-g' => [
    'letra' => 'G', 'sonido' => 'Gggg', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'GATO',       'e' => '🐱', 'syls' => ['GA', 'TO']],
        ['w' => 'GRÚA',       'e' => '🏗️', 'syls' => ['GRÚ', 'A']],
        ['w' => 'GANSO',      'e' => '🦢', 'syls' => ['GAN', 'SO']],
        ['w' => 'GRIFO',      'e' => '🚰', 'syls' => ['GRI', 'FO']],
        ['w' => 'GORRA',      'e' => '🧢', 'syls' => ['GO', 'RRA']],
        ['w' => 'GLOBO',      'e' => '🎈', 'syls' => ['GLO', 'BO']],
        ['w' => 'GAFAS',      'e' => '👓', 'syls' => ['GA', 'FAS']],
        ['w' => 'GRILLO',     'e' => '🦗', 'syls' => ['GRI', 'LLO']],
        ['w' => 'GUANTE',     'e' => '🧤', 'syls' => ['GUAN', 'TE']],
        ['w' => 'GUSANO',     'e' => '🐛', 'syls' => ['GU', 'SA', 'NO']],
        ['w' => 'GALLINA',    'e' => '🐔', 'syls' => ['GA', 'LLI', 'NA']],
        ['w' => 'GALLETA',    'e' => '🍪', 'syls' => ['GA', 'LLE', 'TA']],
        ['w' => 'GIRASOL',    'e' => '🌻', 'syls' => ['GI', 'RA', 'SOL']],
        ['w' => 'GORILA',     'e' => '🦍', 'syls' => ['GO', 'RI', 'LA']],
        ['w' => 'GASOLINA',   'e' => '⛽', 'syls' => ['GA', 'SO', 'LI', 'NA']],
        ['w' => 'GUITARRA',   'e' => '🎸', 'syls' => ['GUI', 'TA', 'RRA']],
    ],
],

// ── H ────────────────────────────────────────────────────────────────
'letra-h' => [
    'letra' => 'H', 'sonido' => 'La H no suena', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'HOJA',        'e' => '🍃', 'syls' => ['HO', 'JA']],
        ['w' => 'HILO',        'e' => '🧵', 'syls' => ['HI', 'LO']],
        ['w' => 'HUESO',       'e' => '🦴', 'syls' => ['HUE', 'SO']],
        ['w' => 'HUEVO',       'e' => '🥚', 'syls' => ['HUE', 'VO']],
        ['w' => 'HIELO',       'e' => '🧊', 'syls' => ['HIE', 'LO']],
        ['w' => 'HACHA',       'e' => '🪓', 'syls' => ['HA', 'CHA']],
        ['w' => 'HONGO',       'e' => '🍄', 'syls' => ['HON', 'GO']],
        ['w' => 'HOTEL',       'e' => '🏨', 'syls' => ['HO', 'TEL']],
        ['w' => 'HIERBA',      'e' => '🌿', 'syls' => ['HIER', 'BA']],
        ['w' => 'HUELLA',      'e' => '👣', 'syls' => ['HUE', 'LLA']],
        ['w' => 'HELADO',      'e' => '🍦', 'syls' => ['HE', 'LA', 'DO']],
        ['w' => 'HORMIGA',     'e' => '🐜', 'syls' => ['HOR', 'MI', 'GA']],
        ['w' => 'HOSPITAL',    'e' => '🏥', 'syls' => ['HOS', 'PI', 'TAL']],
        ['w' => 'HIPOPÓTAMO',  'e' => '🦛', 'syls' => ['HI', 'PO', 'PÓ', 'TA', 'MO']],
        ['w' => 'HAMBURGUESA', 'e' => '🍔', 'syls' => ['HAM', 'BUR', 'GUE', 'SA']],
    ],
],

// ── J ────────────────────────────────────────────────────────────────
'letra-j' => [
    'letra' => 'J', 'sonido' => 'Jjjj', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'JUGO',       'e' => '🧃', 'syls' => ['JU', 'GO']],
        ['w' => 'JOYA',       'e' => '💎', 'syls' => ['JO', 'YA']],
        ['w' => 'JARRA',      'e' => '🏺', 'syls' => ['JA', 'RRA']],
        ['w' => 'JEANS',      'e' => '👖', 'syls' => ['JEANS']],
        ['w' => 'JAMÓN',      'e' => '🍖', 'syls' => ['JA', 'MÓN']],
        ['w' => 'JABÓN',      'e' => '🧼', 'syls' => ['JA', 'BÓN']],
        ['w' => 'JABALÍ',     'e' => '🐗', 'syls' => ['JA', 'BA', 'LÍ']],
        ['w' => 'JAGUAR',     'e' => '🐆', 'syls' => ['JA', 'GUAR']],
        ['w' => 'JIRAFA',     'e' => '🦒', 'syls' => ['JI', 'RA', 'FA']],
        ['w' => 'JARDÍN',     'e' => '🌷', 'syls' => ['JAR', 'DÍN']],
        ['w' => 'JINETE',     'e' => '🏇', 'syls' => ['JI', 'NE', 'TE']],
        ['w' => 'JERINGA',    'e' => '💉', 'syls' => ['JE', 'RIN', 'GA']],
        ['w' => 'JUGUETE',    'e' => '🧸', 'syls' => ['JU', 'GUE', 'TE']],
    ],
],

// ── K ────────────────────────────────────────────────────────────────
//
// Seis palabras. No es un descuido: la K no es una letra del español
// patrimonial y casi todo lo que la lleva es un préstamo. Inflar la lista
// exigiría inventar dibujos falsos, y eso no se hace.
'letra-k' => [
    'letra' => 'K', 'sonido' => 'Kkk', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'KIWI',       'e' => '🥝', 'syls' => ['KI', 'WI']],
        ['w' => 'KOALA',      'e' => '🐨', 'syls' => ['KO', 'A', 'LA']],
        ['w' => 'KAYAK',      'e' => '🛶', 'syls' => ['KA', 'YAK']],
        ['w' => 'KIMONO',     'e' => '👘', 'syls' => ['KI', 'MO', 'NO']],
        ['w' => 'KARATE',     'e' => '🥋', 'syls' => ['KA', 'RA', 'TE']],
        ['w' => 'KIOSCO',     'e' => '🏪', 'syls' => ['KIOS', 'CO']],
    ],
],

// ── R ────────────────────────────────────────────────────────────────
'letra-r' => [
    'letra' => 'R', 'sonido' => 'Rrrr', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'REY',        'e' => '👑', 'syls' => ['REY']],
        ['w' => 'RANA',       'e' => '🐸', 'syls' => ['RA', 'NA']],
        ['w' => 'ROSA',       'e' => '🌹', 'syls' => ['RO', 'SA']],
        ['w' => 'RAYO',       'e' => '⚡', 'syls' => ['RA', 'YO']],
        ['w' => 'RAMA',       'e' => '🌿', 'syls' => ['RA', 'MA']],
        ['w' => 'ROPA',       'e' => '👕', 'syls' => ['RO', 'PA']],
        ['w' => 'REMO',       'e' => '🛶', 'syls' => ['RE', 'MO']],
        ['w' => 'RUEDA',      'e' => '🛞', 'syls' => ['RUE', 'DA']],
        ['w' => 'RADIO',      'e' => '📻', 'syls' => ['RA', 'DIO']],
        ['w' => 'RELOJ',      'e' => '⏰', 'syls' => ['RE', 'LOJ']],
        ['w' => 'ROBOT',      'e' => '🤖', 'syls' => ['RO', 'BOT']],
        ['w' => 'RATÓN',      'e' => '🐭', 'syls' => ['RA', 'TÓN']],
        ['w' => 'REGALO',     'e' => '🎁', 'syls' => ['RE', 'GA', 'LO']],
        ['w' => 'RAQUETA',    'e' => '🏸', 'syls' => ['RA', 'QUE', 'TA']],
        ['w' => 'RINOCERONTE', 'e' => '🦏', 'syls' => ['RI', 'NO', 'CE', 'RON', 'TE']],
    ],
],

// ── V ────────────────────────────────────────────────────────────────
'letra-v' => [
    'letra' => 'V', 'sonido' => 'Vvv', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'VACA',       'e' => '🐄', 'syls' => ['VA', 'CA']],
        ['w' => 'VELA',       'e' => '🕯️', 'syls' => ['VE', 'LA']],
        ['w' => 'VASO',       'e' => '🥤', 'syls' => ['VA', 'SO']],
        ['w' => 'VENDA',      'e' => '🩹', 'syls' => ['VEN', 'DA']],
        ['w' => 'VIRUS',      'e' => '🦠', 'syls' => ['VI', 'RUS']],
        ['w' => 'VIAJE',      'e' => '🧳', 'syls' => ['VIA', 'JE']],
        ['w' => 'VÍBORA',     'e' => '🐍', 'syls' => ['VÍ', 'BO', 'RA']],
        ['w' => 'VIENTO',     'e' => '💨', 'syls' => ['VIEN', 'TO']],
        ['w' => 'VIOLETA',    'e' => '🟣', 'syls' => ['VIO', 'LE', 'TA']],
        ['w' => 'VENADO',     'e' => '🦌', 'syls' => ['VE', 'NA', 'DO']],
        ['w' => 'VERDURA',    'e' => '🥦', 'syls' => ['VER', 'DU', 'RA']],
        ['w' => 'VESTIDO',    'e' => '👗', 'syls' => ['VES', 'TI', 'DO']],
        ['w' => 'VIOLÍN',     'e' => '🎻', 'syls' => ['VIO', 'LÍN']],
        ['w' => 'VOLCÁN',     'e' => '🌋', 'syls' => ['VOL', 'CÁN']],
        ['w' => 'VELERO',     'e' => '⛵', 'syls' => ['VE', 'LE', 'RO']],
        ['w' => 'VENTANA',    'e' => '🪟', 'syls' => ['VEN', 'TA', 'NA']],
    ],
],

// ── W ────────────────────────────────────────────────────────────────
//
// Cinco. La W es letra prestada y en español no hay más con dibujo real.
'letra-w' => [
    'letra' => 'W', 'sonido' => 'Uve doble', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'WIFI',       'e' => '📶', 'syls' => ['WI', 'FI']],
        ['w' => 'WOK',        'e' => '🍳', 'syls' => ['WOK']],
        ['w' => 'WAFLE',      'e' => '🧇', 'syls' => ['WA', 'FLE']],
        ['w' => 'WEB',        'e' => '🌐', 'syls' => ['WEB']],
        ['w' => 'KIWI',       'e' => '🥝', 'syls' => ['KI', 'WI']],
    ],
],

// ── X ────────────────────────────────────────────────────────────────
//
// La X inicial casi no existe en español —solo xilófono— así que esta
// letra se enseña donde de verdad aparece: en medio de la palabra.
'letra-x' => [
    'letra' => 'X', 'sonido' => 'Equis', 'posicion' => 'contiene',
    'palabras' => [
        ['w' => 'TAXI',        'e' => '🚕',              'syls' => ['TA', 'XI']],
        ['w' => 'TEXTO',       'e' => '📄',              'syls' => ['TEX', 'TO']],
        ['w' => 'BOXEO',       'e' => '🥊',              'syls' => ['BO', 'XE', 'O']],
        ['w' => 'SEXTO',       'e' => '6️⃣',              'syls' => ['SEX', 'TO']],
        ['w' => 'MÉXICO',      'e' => '🇲🇽',              'syls' => ['MÉ', 'XI', 'CO']],
        ['w' => 'EXAMEN',      'e' => '📝',              'syls' => ['E', 'XA', 'MEN']],
        ['w' => 'SAXOFÓN',     'e' => '🎷',              'syls' => ['SA', 'XO', 'FÓN']],
        ['w' => 'EXPLOSIÓN',   'e' => '💥',              'syls' => ['EX', 'PLO', 'SIÓN']],
        ['w' => 'EXTINTOR',    'e' => '🧯',              'syls' => ['EX', 'TIN', 'TOR']],
        ['w' => 'EXPERIMENTO', 'e' => '🧪',              'syls' => ['EX', 'PE', 'RI', 'MEN', 'TO']],
        ['w' => 'XILÓFONO',    'e' => 'icono:xilofono',  'syls' => ['XI', 'LÓ', 'FO', 'NO']],
    ],
],

// ── Y ────────────────────────────────────────────────────────────────
'letra-y' => [
    'letra' => 'Y', 'sonido' => 'Yyy', 'posicion' => 'contiene',
    'palabras' => [
        ['w' => 'YOYO',       'e' => '🪀', 'syls' => ['YO', 'YO']],
        ['w' => 'YATE',       'e' => '⛵', 'syls' => ['YA', 'TE']],
        ['w' => 'YEMA',       'e' => '🥚', 'syls' => ['YE', 'MA']],
        ['w' => 'HOYO',       'e' => '🕳️', 'syls' => ['HO', 'YO']],
        ['w' => 'MAYO',       'e' => '📅', 'syls' => ['MA', 'YO']],
        ['w' => 'RAYO',       'e' => '⚡', 'syls' => ['RA', 'YO']],
        ['w' => 'JOYA',       'e' => '💎', 'syls' => ['JO', 'YA']],
        ['w' => 'YEGUA',      'e' => '🐴', 'syls' => ['YE', 'GUA']],
        ['w' => 'PLAYA',      'e' => '🏖️', 'syls' => ['PLA', 'YA']],
        ['w' => 'PAYASO',     'e' => '🤡', 'syls' => ['PA', 'YA', 'SO']],
        ['w' => 'DESAYUNO',   'e' => '🥣', 'syls' => ['DE', 'SA', 'YU', 'NO']],
    ],
],

// ── Z ────────────────────────────────────────────────────────────────
'letra-z' => [
    'letra' => 'Z', 'sonido' => 'Zzz', 'posicion' => 'contiene',
    'palabras' => [
        ['w' => 'LUZ',        'e' => '💡', 'syls' => ['LUZ']],
        ['w' => 'PEZ',        'e' => '🐟', 'syls' => ['PEZ']],
        ['w' => 'TAZA',       'e' => '☕', 'syls' => ['TA', 'ZA']],
        ['w' => 'ZORRO',      'e' => '🦊', 'syls' => ['ZO', 'RRO']],
        ['w' => 'PIZZA',      'e' => '🍕', 'syls' => ['PIZ', 'ZA']],
        ['w' => 'ARROZ',      'e' => '🍚', 'syls' => ['A', 'RROZ']],
        ['w' => 'NARIZ',      'e' => '👃', 'syls' => ['NA', 'RIZ']],
        ['w' => 'LÁPIZ',      'e' => '✏️', 'syls' => ['LÁ', 'PIZ']],
        ['w' => 'TAZÓN',      'e' => '🥣', 'syls' => ['TA', 'ZÓN']],
        ['w' => 'AZÚCAR',     'e' => '🍬', 'syls' => ['A', 'ZÚ', 'CAR']],
        ['w' => 'ZAPATO',     'e' => '👞', 'syls' => ['ZA', 'PA', 'TO']],
        ['w' => 'CEREZA',     'e' => '🍒', 'syls' => ['CE', 'RE', 'ZA']],
        ['w' => 'MANZANA',    'e' => '🍎', 'syls' => ['MAN', 'ZA', 'NA']],
        ['w' => 'ZANAHORIA',  'e' => '🥕', 'syls' => ['ZA', 'NA', 'HO', 'RIA']],
        ['w' => 'ZAPATILLA',  'e' => '👟', 'syls' => ['ZA', 'PA', 'TI', 'LLA']],
    ],
],

// ── CH ───────────────────────────────────────────────────────────────
'letra-ch' => [
    'letra' => 'CH', 'sonido' => 'Chchch', 'posicion' => 'contiene',
    'palabras' => [
        ['w' => 'CHILE',      'e' => '🌶️', 'syls' => ['CHI', 'LE']],
        ['w' => 'LECHE',      'e' => '🥛', 'syls' => ['LE', 'CHE']],
        ['w' => 'NOCHE',      'e' => '🌙', 'syls' => ['NO', 'CHE']],
        ['w' => 'COCHE',      'e' => '🚗', 'syls' => ['CO', 'CHE']],
        ['w' => 'DUCHA',      'e' => '🚿', 'syls' => ['DU', 'CHA']],
        ['w' => 'HACHA',      'e' => '🪓', 'syls' => ['HA', 'CHA']],
        ['w' => 'CHOCLO',     'e' => '🌽', 'syls' => ['CHO', 'CLO']],
        ['w' => 'CHALECO',    'e' => '🦺', 'syls' => ['CHA', 'LE', 'CO']],
        ['w' => 'CUCHARA',    'e' => '🥄', 'syls' => ['CU', 'CHA', 'RA']],
        ['w' => 'MOCHILA',    'e' => '🎒', 'syls' => ['MO', 'CHI', 'LA']],
        ['w' => 'LECHUGA',    'e' => '🥬', 'syls' => ['LE', 'CHU', 'GA']],
        ['w' => 'CHUPETA',    'e' => '🍭', 'syls' => ['CHU', 'PE', 'TA']],
        ['w' => 'COLCHÓN',    'e' => '🛏️', 'syls' => ['COL', 'CHÓN']],
        ['w' => 'CUCHILLO',   'e' => '🔪', 'syls' => ['CU', 'CHI', 'LLO']],
        ['w' => 'CHAQUETA',   'e' => '🧥', 'syls' => ['CHA', 'QUE', 'TA']],
        ['w' => 'CHANCLA',    'e' => '🩴', 'syls' => ['CHAN', 'CLA']],
        ['w' => 'CHOCOLATE',  'e' => '🍫', 'syls' => ['CHO', 'CO', 'LA', 'TE']],
    ],
],

// ── QU ───────────────────────────────────────────────────────────────
'letra-qu' => [
    'letra' => 'QU', 'sonido' => 'Qqq', 'posicion' => 'contiene',
    'palabras' => [
        ['w' => 'QUESO',      'e' => '🧀', 'syls' => ['QUE', 'SO']],
        ['w' => 'ESQUÍ',      'e' => '⛷️', 'syls' => ['ES', 'QUÍ']],
        ['w' => 'PARQUE',     'e' => '🏞️', 'syls' => ['PAR', 'QUE']],
        ['w' => 'BOSQUE',     'e' => '🌲', 'syls' => ['BOS', 'QUE']],
        ['w' => 'PAQUETE',    'e' => '📦', 'syls' => ['PA', 'QUE', 'TE']],
        ['w' => 'RAQUETA',    'e' => '🏸', 'syls' => ['RA', 'QUE', 'TA']],
        ['w' => 'CHAQUETA',   'e' => '🧥', 'syls' => ['CHA', 'QUE', 'TA']],
        ['w' => 'MÁQUINA',    'e' => '⚙️', 'syls' => ['MÁ', 'QUI', 'NA']],
        ['w' => 'MOSQUITO',   'e' => '🦟', 'syls' => ['MOS', 'QUI', 'TO']],
        ['w' => 'ESQUELETO',  'e' => '💀', 'syls' => ['ES', 'QUE', 'LE', 'TO']],
        ['w' => 'MAQUILLAJE', 'e' => '💄', 'syls' => ['MA', 'QUI', 'LLA', 'JE']],
    ],
],

// ── GUE · GUI ────────────────────────────────────────────────────────
'letra-gue-gui' => [
    'letra' => 'GUE-GUI', 'sonido' => 'Gue, gui', 'posicion' => 'contiene',
    /*
     * `busca` existe solo para esta entrada, y hace falta: «GUE-GUI» es
     * una ETIQUETA, no algo que aparezca dentro de una palabra. Sin esto,
     * el generador buscaría la cadena literal «GUE-GUI» dentro de
     * «GUITARRA», no la encontraría, y colaría GUITARRA como respuesta
     * «no lleva GUE/GUI». El niño acertaría y el juego le diría que se
     * equivocó — el peor error posible en un ejercicio.
     */
    'busca' => ['GUE', 'GUI'],
    'palabras' => [
        ['w' => 'GUISO',       'e' => '🍲', 'syls' => ['GUI', 'SO']],
        ['w' => 'ÁGUILA',      'e' => '🦅', 'syls' => ['Á', 'GUI', 'LA']],
        ['w' => 'GUISANTE',    'e' => '🫛', 'syls' => ['GUI', 'SAN', 'TE']],
        ['w' => 'GUEPARDO',    'e' => '🐆', 'syls' => ['GUE', 'PAR', 'DO']],
        ['w' => 'GUITARRA',    'e' => '🎸', 'syls' => ['GUI', 'TA', 'RRA']],
        ['w' => 'JUGUETE',     'e' => '🧸', 'syls' => ['JU', 'GUE', 'TE']],
        ['w' => 'MERENGUE',    'e' => '🍰', 'syls' => ['ME', 'REN', 'GUE']],
        ['w' => 'ESPAGUETI',   'e' => '🍝', 'syls' => ['ES', 'PA', 'GUE', 'TI']],
        ['w' => 'HORMIGUERO',  'e' => '🐜', 'syls' => ['HOR', 'MI', 'GUE', 'RO']],
        ['w' => 'HAMBURGUESA', 'e' => '🍔', 'syls' => ['HAM', 'BUR', 'GUE', 'SA']],
    ],
],


// =====================================================================
//  LAS VOCALES
// =====================================================================

'vocal-a' => [
    'letra' => 'A', 'sonido' => 'Aaaa', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'ALA',        'e' => '🪶', 'syls' => ['A', 'LA']],
        ['w' => 'AGUA',       'e' => '💧', 'syls' => ['A', 'GUA']],
        ['w' => 'ABEJA',      'e' => '🐝', 'syls' => ['A', 'BE', 'JA']],
        ['w' => 'ÁRBOL',      'e' => '🌳', 'syls' => ['ÁR', 'BOL']],
        ['w' => 'ANCLA',      'e' => '⚓', 'syls' => ['AN', 'CLA']],
        ['w' => 'ARROZ',      'e' => '🍚', 'syls' => ['A', 'RROZ']],
        ['w' => 'ARAÑA',      'e' => '🕷️', 'syls' => ['A', 'RA', 'ÑA']],
        ['w' => 'AVIÓN',      'e' => '✈️', 'syls' => ['A', 'VIÓN']],
        ['w' => 'ANTENA',     'e' => '📡', 'syls' => ['AN', 'TE', 'NA']],
        ['w' => 'ANILLO',     'e' => '💍', 'syls' => ['A', 'NI', 'LLO']],
        ['w' => 'ARDILLA',    'e' => '🐿️', 'syls' => ['AR', 'DI', 'LLA']],
        ['w' => 'ALACRÁN',    'e' => '🦂', 'syls' => ['A', 'LA', 'CRÁN']],
        ['w' => 'ÁGUILA',     'e' => '🦅', 'syls' => ['Á', 'GUI', 'LA']],
        ['w' => 'AUTOBÚS',    'e' => '🚌', 'syls' => ['AU', 'TO', 'BÚS']],
        ['w' => 'AGUACATE',   'e' => '🥑', 'syls' => ['A', 'GUA', 'CA', 'TE']],
        ['w' => 'AMBULANCIA', 'e' => '🚑', 'syls' => ['AM', 'BU', 'LAN', 'CIA']],
        ['w' => 'ASTRONAUTA', 'e' => '👨‍🚀', 'syls' => ['AS', 'TRO', 'NAU', 'TA']],
    ],
],

'vocal-e' => [
    'letra' => 'E', 'sonido' => 'Eeee', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'ERIZO',      'e' => '🦔', 'syls' => ['E', 'RI', 'ZO']],
        ['w' => 'ELOTE',      'e' => '🌽', 'syls' => ['E', 'LO', 'TE']],
        ['w' => 'ESQUÍ',      'e' => '⛷️', 'syls' => ['ES', 'QUÍ']],
        ['w' => 'ESPADA',     'e' => '🗡️', 'syls' => ['ES', 'PA', 'DA']],
        ['w' => 'ESCOBA',     'e' => '🧹', 'syls' => ['ES', 'CO', 'BA']],
        ['w' => 'ESPEJO',     'e' => '🪞', 'syls' => ['ES', 'PE', 'JO']],
        ['w' => 'ESPONJA',    'e' => '🧽', 'syls' => ['ES', 'PON', 'JA']],
        ['w' => 'ESCUELA',    'e' => '🏫', 'syls' => ['ES', 'CUE', 'LA']],
        ['w' => 'ENCHUFE',    'e' => '🔌', 'syls' => ['EN', 'CHU', 'FE']],
        ['w' => 'ESTADIO',    'e' => '🏟️', 'syls' => ['ES', 'TA', 'DIO']],
        ['w' => 'ENSALADA',   'e' => '🥗', 'syls' => ['EN', 'SA', 'LA', 'DA']],
        ['w' => 'ESTRELLA',   'e' => '⭐', 'syls' => ['ES', 'TRE', 'LLA']],
        ['w' => 'ESCALERA',   'e' => '🪜', 'syls' => ['ES', 'CA', 'LE', 'RA']],
        ['w' => 'ELEFANTE',   'e' => '🐘', 'syls' => ['E', 'LE', 'FAN', 'TE']],
        ['w' => 'ESQUELETO',  'e' => '💀', 'syls' => ['ES', 'QUE', 'LE', 'TO']],
    ],
],

'vocal-i' => [
    'letra' => 'I', 'sonido' => 'Iiii', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'ISLA',       'e' => '🏝️', 'syls' => ['IS', 'LA']],
        ['w' => 'IMÁN',       'e' => '🧲', 'syls' => ['I', 'MÁN']],
        ['w' => 'IGLESIA',    'e' => '⛪', 'syls' => ['I', 'GLE', 'SIA']],
        ['w' => 'INVIERNO',   'e' => '❄️', 'syls' => ['IN', 'VIER', 'NO']],
        ['w' => 'IMPRESORA',  'e' => '🖨️', 'syls' => ['IM', 'PRE', 'SO', 'RA']],
    ],
    // Cinco palabras. La I inicial es de las más pobres del español y no
    // se estira con dibujos prestados: «instrumento» ilustrado con una
    // trompeta enseña «trompeta», no «instrumento».
],

'vocal-o' => [
    'letra' => 'O', 'sonido' => 'Oooo', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'OSO',        'e' => '🐻', 'syls' => ['O', 'SO']],
        ['w' => 'OJO',        'e' => '👁️', 'syls' => ['O', 'JO']],
        ['w' => 'OLA',        'e' => '🌊', 'syls' => ['O', 'LA']],
        ['w' => 'ORO',        'e' => '🥇', 'syls' => ['O', 'RO']],
        ['w' => 'OCHO',       'e' => '8️⃣', 'syls' => ['O', 'CHO']],
        ['w' => 'OLLA',       'e' => '🍲', 'syls' => ['O', 'LLA']],
        ['w' => 'OLIVO',      'e' => '🫒', 'syls' => ['O', 'LI', 'VO']],
        ['w' => 'OTOÑO',      'e' => '🍂', 'syls' => ['O', 'TO', 'ÑO']],
        ['w' => 'OSTRA',      'e' => '🦪', 'syls' => ['OS', 'TRA']],
        ['w' => 'OVEJA',      'e' => '🐑', 'syls' => ['O', 'VE', 'JA']],
        ['w' => 'OREJA',      'e' => '👂', 'syls' => ['O', 'RE', 'JA']],
        ['w' => 'ORUGA',      'e' => '🐛', 'syls' => ['O', 'RU', 'GA']],
        ['w' => 'OFICINA',    'e' => '🏢', 'syls' => ['O', 'FI', 'CI', 'NA']],
    ],
],

'vocal-u' => [
    'letra' => 'U', 'sonido' => 'Uuuu', 'posicion' => 'inicial',
    'palabras' => [
        ['w' => 'UVA',        'e' => '🍇', 'syls' => ['U', 'VA']],
        ['w' => 'UNO',        'e' => '1️⃣', 'syls' => ['U', 'NO']],
        ['w' => 'UÑA',        'e' => '💅', 'syls' => ['U', 'ÑA']],
        ['w' => 'URNA',       'e' => '🗳️', 'syls' => ['UR', 'NA']],
        ['w' => 'UNIVERSO',   'e' => '🌌', 'syls' => ['U', 'NI', 'VER', 'SO']],
        ['w' => 'UNIFORME',   'e' => '👔', 'syls' => ['U', 'NI', 'FOR', 'ME']],
        ['w' => 'UNICORNIO',  'e' => '🦄', 'syls' => ['U', 'NI', 'COR', 'NIO']],
        ['w' => 'UNIVERSIDAD', 'e' => '🎓', 'syls' => ['U', 'NI', 'VER', 'SI', 'DAD']],
    ],
],

],

];
