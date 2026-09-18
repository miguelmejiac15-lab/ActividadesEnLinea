<?php
/**
 * revisar-ortografia.php — Las tildes y la Ñ del catálogo.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ ESTO IMPORTA MÁS DE LO QUE PARECE
 * ---------------------------------------------------------------------
 *
 * «Mecanografía» muestra una palabra y el niño la copia. El motor compara
 * letra a letra, así que si la palabra en pantalla dice LEON, el niño
 * aprende a escribir LEON. La plataforma enseña la falta.
 *
 * Lo mismo vale para «ortografía», «armar palabras», «puzle de sílabas» y
 * «completar»: en todas ellas la palabra escrita ES el contenido.
 *
 * ---------------------------------------------------------------------
 *  CÓMO SE DETECTA SIN UN DICCIONARIO
 * ---------------------------------------------------------------------
 *
 * El catálogo se contradice a sí mismo, y esa contradicción es la prueba.
 * «NIÑO» aparece bien escrito en «carrera de palabras» y como «NINO» en
 * «la neo computadora». No hace falta un diccionario del español: basta
 * con mirar si la MISMA palabra aparece en algún sitio con su tilde.
 *
 * A eso se le suma una lista corta de palabras frecuentes en contenido
 * infantil, para las que nunca están bien escritas en ningún sitio.
 *
 * ---------------------------------------------------------------------
 *  LO QUE NO SE DENUNCIA
 * ---------------------------------------------------------------------
 *
 * La sopa de letras y el crucigrama rellenan la rejilla con un alfabeto
 * sin tildes ni Ñ. Una palabra acentuada dejaría su letra rara brillando
 * entre las demás: sería un chivatazo. Ahí quitar la tilde es una
 * decisión tomada, no un descuido — y la solución correcta no es
 * acentuarla, es elegir otra palabra.
 *
 * Se listan aparte, como aviso, sin contar como falta.
 *
 * Uso:
 *     php database/revisar-ortografia.php
 *     php database/revisar-ortografia.php --todo     (incluye avisos)
 */

declare(strict_types=1);
require_once __DIR__ . '/../config/config.php';

$verTodo = in_array('--todo', $argv, true);

/** Las mecánicas donde la palabra escrita es el contenido. */
const ESCRIBEN = ['teclado', 'ortografia', 'armar_palabras', 'puzle_silabas',
                  'completar_palabra', 'completar_texto', 'pronunciacion'];

/** Las que rellenan rejilla y por eso renuncian a las tildes. */
const REJILLA = ['sopa_letras', 'crucigrama'];

/**
 * ---------------------------------------------------------------------
 *  LOS DISTRACTORES NO SON VERDAD
 * ---------------------------------------------------------------------
 *
 * «La tilde» enseña cuándo se acentúa, y para eso muestra la palabra bien
 * y mal escrita: {"opts":["libro","líbro"],"correct":"libro"}. La falta
 * está ahí a propósito.
 *
 * La primera versión de este revisor se creyó esas opciones y montó un
 * léxico donde «líbro» y «sól» eran las formas correctas. Luego denunció
 * medio catálogo por escribir «libro» sin tilde.
 *
 * Es el mismo error que ya costó un «🐄 ↔ baca» en el enriquecedor: lo
 * que está ahí para descartarse no describe nada.
 */
const MIENTEN = ['ortografia', 'completar_palabra'];

/**
 * Palabras que existen con tilde y sin ella, y las dos están bien.
 *
 * El método de este revisor —comparar el catálogo consigo mismo— no
 * puede distinguir «qué hora es» de «la casa que vimos». Son pares
 * legítimos y denunciarlos sería ruido que tapa las faltas de verdad.
 */
const AMBAS = [
    'que', 'como', 'donde', 'cuando', 'cual', 'cuales', 'quien', 'quienes',
    'cuanto', 'cuanta', 'cuantos', 'cuantas', 'porque', 'adonde',
    'el', 'tu', 'mi', 'si', 'se', 'te', 'de', 'mas', 'aun', 'solo', 'esta',
    'este', 'esa', 'ese', 'aquel', 'una', 'uno', 'un', 'sol', 'libro',
    'camino', 'hacia', 'une', 'sabia', 'seria', 'valla', 'paso', 'canto',
    'hablo', 'llego', 'tomo', 'tomate', 'entro', 'salto', 'gano', 'ando',
    'jugo', 'lavo', 'miro', 'nado', 'salio', 'subio', 'toco', 'uso', 'vario',
    'continuo', 'practico', 'publico', 'numero', 'termino', 'calculo',
    'circulo', 'titulo', 'capitulo', 'articulo', 'animo', 'celebre',
    'domino', 'ejercito', 'integro', 'liquido', 'limite', 'transito',
    'gusto', 'molesto', 'duro', 'dejo', 'olvido', 'cambio', 'reto',
    'quedo', 'invento', 'empujo', 'saque', 'fabrica', 'papas', 'papa',
    'celebro', 'grito', 'llamo', 'llevo', 'mando', 'marco', 'monto',
    'pinto', 'presto', 'pruebo', 'regalo', 'saludo', 'sumo', 'trabajo',
    'unas', 'estas', 'estes', 'callo', 'corto', 'cuido', 'espero',
];

/**
 * Palabras frecuentes en contenido infantil que quizá no estén bien
 * escritas en ningún sitio del catálogo, así que la comparación consigo
 * mismo no las encontraría.
 */
const SEMILLA = [
    'leon' => 'león', 'camion' => 'camión', 'arbol' => 'árbol',
    'pajaro' => 'pájaro', 'musica' => 'música', 'telefono' => 'teléfono',
    'platano' => 'plátano', 'pinguino' => 'pingüino', 'murcielago' => 'murciélago',
    'microfono' => 'micrófono', 'pelicula' => 'película', 'matematicas' => 'matemáticas',
    'corazon' => 'corazón', 'raton' => 'ratón', 'jabon' => 'jabón',
    'balon' => 'balón', 'avion' => 'avión', 'cancion' => 'canción',
    'limon' => 'limón', 'melon' => 'melón', 'dia' => 'día', 'pais' => 'país',
    'rio' => 'río', 'sofa' => 'sofá', 'cafe' => 'café', 'bebe' => 'bebé',
    'mama' => 'mamá', 'papa' => 'papá', 'sabado' => 'sábado',
    'miercoles' => 'miércoles', 'numero' => 'número', 'ultimo' => 'último',
    'rapido' => 'rápido', 'facil' => 'fácil', 'dificil' => 'difícil',
    'lapiz' => 'lápiz', 'azucar' => 'azúcar', 'oceano' => 'océano',
    'aguila' => 'águila', 'tiburon' => 'tiburón', 'delfin' => 'delfín',
    'condor' => 'cóndor', 'buho' => 'búho', 'dragon' => 'dragón',
    'violin' => 'violín', 'futbol' => 'fútbol', 'medico' => 'médico',
    'policia' => 'policía', 'panaderia' => 'panadería', 'libreria' => 'librería',
    'estacion' => 'estación', 'direccion' => 'dirección', 'atencion' => 'atención',
    'educacion' => 'educación', 'informacion' => 'información',
    'operacion' => 'operación', 'multiplicacion' => 'multiplicación',
    'division' => 'división', 'fraccion' => 'fracción', 'nino' => 'niño',
    'nina' => 'niña', 'montana' => 'montaña', 'pina' => 'piña',
    'espanol' => 'español', 'anos' => 'años', 'manana' => 'mañana',
    'pequeno' => 'pequeño', 'sueno' => 'sueño', 'bano' => 'baño',
    'cigüena' => 'cigüeña', 'arana' => 'araña', 'castana' => 'castaña',
    'maiz' => 'maíz', 'pantalon' => 'pantalón', 'raiz' => 'raíz',
    'jirafa' => null, 'planeta' => null,   // nunca llevan: no denunciar
];

/** Sin tildes, sin Ñ y en minúscula: la forma con la que se comparan. */
function pelar(string $w): string
{
    return strtr(mb_strtolower(trim($w)), [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u',
        'ü' => 'u', 'ñ' => 'n',
    ]);
}

/** ¿Lleva alguna tilde o Ñ? */
function tildada(string $w): bool
{
    return preg_match('/[áéíóúüñÁÉÍÓÚÜÑ]/u', $w) === 1;
}

// =====================================================================
//  1 · Leer todo el texto del catálogo
// =====================================================================

$filas = traerTodo(
    'SELECT a.slug, a.title AS actividad, s.id, s.title, s.game_type, s.config,
            c.name AS categoria
       FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
       LEFT JOIN categories c ON c.id = a.category_id
      WHERE a.status = "published"
   ORDER BY a.slug, s.position');

/**
 * Cada palabra suelta que aparece en una estación, con dónde estaba.
 *
 * @var array<int, array{w: string, slug: string, est: int, titulo: string, tipo: string, campo: string}>
 */
$apariciones = [];

/** normalizada => [forma acentuada => cuántas veces] */
$lexico = [];

foreach ($filas as $f) {
    $cfg = json_decode((string) $f['config'], true);

    if (!is_array($cfg)) {
        continue;
    }

    $tipo = (string) $f['game_type'];

    /*
     * El inglés no se corrige con las reglas del español.
     *
     * «Sofa» es como se escribe en inglés y «Sofá» es justamente la
     * opción falsa de ese ejercicio; «name» no es «ñame» y «Crayon» no
     * lleva tilde en su idioma. Acentuar ahí sería estropear la lección.
     *
     * La categoría se llama «Idiomas», no «Inglés»: se mira la que hay,
     * no la que uno esperaría.
     */
    if (stripos((string) ($f['categoria'] ?? ''), 'idioma') !== false) {
        continue;
    }

    /*
     * Y una actividad que enseña PARA QUÉ SIRVE LA TILDE necesita mostrar
     * las dos formas: «Papa» y «Papá», «Estas» y «Estás». Ahí la palabra
     * sin tilde no es un descuido, es la mitad de la explicación.
     */
    $sobreTildes = stripos((string) $f['slug'], 'tilde') !== false
        || stripos((string) $f['actividad'], 'tilde') !== false
        || stripos((string) $f['actividad'], 'acent') !== false;

    // El título y la descripción de la estación también se leen en voz
    // alta y se muestran, así que cuentan.
    $textos = [['t' => (string) $f['title'], 'campo' => 'título']];

    /*
     * Se recorre a mano en vez de con `array_walk_recursive` porque hace
     * falta saber de QUÉ CAMPO viene cada texto.
     *
     * `array_walk_recursive` entrega la clave inmediata, y en una lista
     * como `"syls": ["DIA","MAN","TE"]` esa clave es 0, 1 y 2. Nunca
     * llega a decir «syls», así que era imposible distinguir una sílaba
     * de una palabra. Al bajar por una lista se conserva el nombre del
     * campo que la contiene.
     */
    $recorrer = static function ($v, string $campo) use (&$recorrer, &$textos): void {
        if (is_string($v)) {
            if (trim($v) !== '') {
                $textos[] = ['t' => $v, 'campo' => $campo];
            }
            return;
        }

        if (!is_array($v)) {
            return;
        }

        foreach ($v as $k => $x) {
            // Con clave de texto manda la clave; dentro de una lista se
            // hereda el nombre del campo que la envuelve.
            $recorrer($x, is_string($k) ? $k : $campo);
        }
    };

    $recorrer($cfg, 'datos');

    // Una estación que enseña ortografía guarda faltas a propósito: ni
    // aporta al léxico ni se la denuncia por ellas.
    $miente = in_array($tipo, MIENTEN, true) || $sobreTildes;

    foreach ($textos as $t) {

        /*
         * ── Lo que no es una palabra escrita ─────────────────────────
         *
         *  · `icono:nandu` es el nombre de un archivo SVG. Partirlo daba
         *    «nandu» y este revisor pedía acentuarlo: acentuarlo habría
         *    roto el dibujo.
         *
         *  · `syls` son sílabas sueltas. «DIA» es el trozo de DIA-MAN-TE,
         *    no la palabra «día», y ponerle tilde estropearía el puzle.
         *
         *  · `opts` son las opciones de un ejercicio de elegir, y la
         *    falsa está ahí para descartarse. La buena se nombra aparte.
         */
        if (str_starts_with($t['t'], 'icono:')
            || in_array($t['campo'], ['syls', 'opts', 'opciones', 'extra'], true)) {
            continue;
        }

        // Salvo el campo que dice cuál es la buena, que sí es verdad.
        $verdad = !$miente || in_array($t['campo'], ['correct', 'correcta', 'w'], true);

        foreach (preg_split('/[^\p{L}\p{M}]+/u', $t['t'], -1, PREG_SPLIT_NO_EMPTY) ?: [] as $w) {

            if (mb_strlen($w) < 3) {
                continue;
            }

            $n = pelar($w);

            if (tildada($w) && $verdad) {
                $lexico[$n][mb_strtolower($w)] = ($lexico[$n][mb_strtolower($w)] ?? 0) + 1;
            }

            if (!$verdad) {
                continue;
            }

            $apariciones[] = ['w' => $w, 'slug' => (string) $f['slug'],
                              'est' => (int) $f['id'], 'titulo' => (string) $f['title'],
                              'tipo' => $tipo, 'campo' => $t['campo']];
        }
    }
}

// =====================================================================
//  2 · Buscar las que perdieron la tilde
// =====================================================================

$faltas = [];   // donde la palabra escrita ES el contenido
$otras  = [];   // en enunciados y textos
$avisos = [];   // rejilla: decisión tomada, no descuido

foreach ($apariciones as $ap) {
    if (tildada($ap['w'])) {
        continue;
    }

    $n = pelar($ap['w']);

    // Las dos formas son correctas según la frase: no hay nada que decir.
    if (in_array($n, AMBAS, true)) {
        continue;
    }

    // ¿Cómo debería escribirse?
    $correcta = null;

    if (array_key_exists($n, SEMILLA)) {
        $correcta = SEMILLA[$n];
    } elseif (!empty($lexico[$n])) {
        /*
         * La forma acentuada más frecuente del propio catálogo, y solo si
         * aparece varias veces: una sola vez es tan probable que sea la
         * errata como que sea la buena.
         */
        arsort($lexico[$n]);
        $forma = (string) array_key_first($lexico[$n]);

        if ($lexico[$n][$forma] >= 3) {
            $correcta = $forma;
        }
    }

    if ($correcta === null) {
        continue;
    }

    $caso = $ap + ['correcta' => $correcta];

    if (in_array($ap['tipo'], REJILLA, true)) {
        // En el crucigrama la pista SÍ debe llevar tilde: no es rejilla.
        if (in_array($ap['campo'], ['pista', 'titulo', 'título', 't', 's'], true)) {
            $faltas[] = $caso;
        } else {
            $avisos[] = $caso;
        }
        continue;
    }

    if (in_array($ap['tipo'], ESCRIBEN, true)) {
        $faltas[] = $caso;
    } else {
        $otras[] = $caso;
    }
}

// =====================================================================
//  3 · Contarlo
// =====================================================================

echo "\n";
echo "==========================================================================\n";
echo "  TILDES Y Ñ DEL CATÁLOGO\n";
echo "==========================================================================\n\n";

printf("  Estaciones leídas   : %d\n", count($filas));
printf("  Palabras miradas    : %d\n", count($apariciones));
printf("  Formas con tilde    : %d\n\n", count($lexico));

/** Agrupa por palabra para no repetir la misma cien veces. */
$agrupar = static function (array $lista): array {
    $g = [];
    foreach ($lista as $c) {
        $k = pelar($c['w']) . '|' . $c['correcta'];
        $g[$k]['correcta'] = $c['correcta'];
        $g[$k]['mal']      = $c['w'];
        $g[$k]['donde'][]  = $c['slug'] . ' · ' . $c['titulo'] . ' [' . $c['tipo'] . ']';
    }
    foreach ($g as &$x) { $x['donde'] = array_values(array_unique($x['donde'])); }
    return $g;
};

$gFaltas = $agrupar($faltas);
$gOtras  = $agrupar($otras);
$gAvisos = $agrupar($avisos);

echo "--------------------------------------------------------------------------\n";
printf("  %s FALTAS — la palabra escrita es el contenido (%d sitios, %d palabras)\n",
    $gFaltas ? '❌' : '✅', count($faltas), count($gFaltas));
echo "--------------------------------------------------------------------------\n\n";

if (!$gFaltas) {
    echo "  ✅ Ninguna.\n\n";
}

foreach ($gFaltas as $x) {
    printf("  «%s»  →  «%s»   (%d sitios)\n",
        $x['mal'], mb_strtoupper($x['correcta']), count($x['donde']));
    foreach (array_slice($x['donde'], 0, 4) as $d) {
        printf("      %s\n", $d);
    }
    if (count($x['donde']) > 4) {
        printf("      … y %d más\n", count($x['donde']) - 4);
    }
    echo "\n";
}

echo "--------------------------------------------------------------------------\n";
printf("  ⚠️  En enunciados y textos (%d sitios, %d palabras)\n",
    count($otras), count($gOtras));
echo "--------------------------------------------------------------------------\n\n";

$muestra = $verTodo ? $gOtras : array_slice($gOtras, 0, 20, true);

foreach ($muestra as $x) {
    printf("  «%s» → «%s»  ·  %s%s\n", $x['mal'], $x['correcta'],
        $x['donde'][0], count($x['donde']) > 1 ? ' (+' . (count($x['donde']) - 1) . ')' : '');
}

if (!$verTodo && count($gOtras) > 20) {
    printf("\n  … y %d palabras más. Con --todo se ven todas.\n", count($gOtras) - 20);
}

if ($verTodo || $gAvisos) {
    echo "\n--------------------------------------------------------------------------\n";
    printf("  ℹ️  Rejilla sin tildes: decisión tomada, no descuido (%d palabras)\n",
        count($gAvisos));
    echo "--------------------------------------------------------------------------\n";
    echo "  La sopa y el crucigrama rellenan con un alfabeto sin tildes ni Ñ.\n";
    echo "  Una letra acentuada cantaría dónde está la palabra. Si molesta, se\n";
    echo "  cambia la palabra — nunca se escribe mal.\n\n";

    foreach (array_slice($gAvisos, 0, $verTodo ? 999 : 12, true) as $x) {
        printf("  %-18s (debería ser «%s»)  ·  %s\n",
            $x['mal'], $x['correcta'], $x['donde'][0]);
    }
}

echo "\n==========================================================================\n";

if (!$gFaltas) {
    echo "  ✅ Donde la palabra escrita es el contenido, está bien escrita.\n";
} else {
    printf("  ❌ %d palabras mal escritas en mecánicas de escritura.\n", count($gFaltas));
}

echo "==========================================================================\n\n";

exit($gFaltas ? 1 : 0);
