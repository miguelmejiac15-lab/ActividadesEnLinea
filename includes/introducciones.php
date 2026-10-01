<?php
/**
 * introducciones.php — La página «Antes de empezar» de cada actividad
 *
 * Un niño de ocho a doce años que abre «Ciclos y equilibrio» no debería
 * encontrarse de entrada con «¿Qué gas liberan las plantas?». Primero
 * necesita saber de qué se está hablando: una definición, las dos o tres
 * ideas que luego se van a practicar y un ejemplo. Después, dos preguntas
 * cortas —completar una frase, identificar algo— para comprobar que lo
 * entendió. Y entonces sí, las estaciones.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ VIVE EN EL CÓDIGO Y NO EN LA BASE
 * ─────────────────────────────────────────────────────────────────────
 *
 * Publicar una introducción nueva no debe exigir tocar la base de
 * producción: con esto, basta con desplegar la imagen. Ninguna tabla
 * cambia y no hay migración que pueda fallar a mitad.
 *
 * Tampoco pasa por el candado de estaciones, y es a propósito: es el
 * contexto que hace falta para jugar, no contenido premium. El plan
 * gratuito la ve entera.
 *
 * Los textos están en `includes/introducciones/`, un archivo por materia.
 * Cada uno devuelve `[slug => intro(...)]`.
 */

declare(strict_types=1);

/**
 * Una introducción.
 *
 * @param string   $idea      La definición o el contexto: dos o tres
 *                            frases. `**así**` marca el término clave.
 * @param string[] $claves    Las ideas que luego se practican, de dos a
 *                            cuatro. Cortas: se leen de un vistazo.
 * @param array[]  $preguntas De `completa()` o `identifica()`. Dos bastan.
 * @param string|null $ejemplo Un caso concreto, si ayuda.
 */
function intro(string $idea, array $claves, array $preguntas, ?string $ejemplo = null): array
{
    return [
        'idea'      => $idea,
        'claves'    => array_values($claves),
        'ejemplo'   => $ejemplo,
        'preguntas' => array_values($preguntas),
    ];
}

/**
 * Una introducción BREVE, para los más pequeños (preescolar, primero y
 * segundo).
 *
 * A los cuatro o seis años no hace falta una definición ni una prueba de
 * comprensión: hace falta saber **de qué va esto y qué voy a hacer**.
 * Una o dos frases muy cortas —«La vocal A es la primera letra. Su
 * sonido es a»— y la lista de lo que hará, que se lee en voz alta sola.
 *
 * La lista «Vas a…» NO se escribe a mano: sale de las estaciones de la
 * actividad (`queSeHaceEn()`). Así nunca promete un juego que no está, y
 * si mañana se añade una estación aparece sola.
 *
 * @param string      $idea   Una o dos frases cortas. `**así**` resalta.
 * @param string|null $dibujo Emoji grande; sin él, el ícono de la actividad.
 */
function breve(string $idea, ?string $dibujo = null): array
{
    return [
        'modo'      => 'breve',
        'idea'      => $idea,
        'dibujo'    => $dibujo,
        'claves'    => [],
        'ejemplo'   => null,
        'preguntas' => [],
    ];
}

/**
 * Lo que se hace en una actividad, dicho para un niño, a partir de sus
 * estaciones. Sin repetir, en el orden en que aparecen, y el reto final
 * siempre al final. Como mucho cinco: más no se recuerda.
 *
 * @param string[] $tipos game_type de cada estación, en orden
 * @return array<int, array{icono:string, texto:string}>
 */
function queSeHaceEn(array $tipos): array
{
    static $como = [
        'sonido_letra'       => ['👂', 'escuchar palabras'],
        'pronunciacion'      => ['🗣️', 'repetir en voz alta'],
        'seleccion_imagenes' => ['👆', 'tocar los dibujos correctos'],
        'opcion_multiple'    => ['✅', 'elegir la respuesta'],
        'juego_rapido'       => ['⚡', 'decir sí o no'],
        'emparejar'          => ['🔗', 'unir parejas'],
        'completar_palabra'  => ['✏️', 'completar palabras'],
        'completar_texto'    => ['📝', 'completar frases'],
        'puzle_silabas'      => ['🧩', 'armar palabras con sílabas'],
        'armar_palabras'     => ['🔤', 'armar palabras letra por letra'],
        'teclado'            => ['⌨️', 'escribir con el teclado'],
        'ortografia'         => ['🔍', 'elegir cómo se escribe'],
        'ordenar_secuencia'  => ['🔢', 'poner las cosas en orden'],
        'operacion'          => ['🧮', 'contar y hacer cuentas'],
        'secuencia_numerica' => ['➡️', 'completar series de números'],
        'memoria'            => ['🃏', 'encontrar parejas escondidas'],
        'sopa_letras'        => ['🔎', 'buscar palabras escondidas'],
        'crucigrama'         => ['🧩', 'llenar un crucigrama'],
        'cuento'             => ['📖', 'escuchar un cuento'],
        'laberinto'          => ['🤖', 'guiar a un robot'],
    ];

    $vistos = [];
    $lista  = [];
    $reto   = false;

    foreach ($tipos as $t) {
        if ($t === 'desafio_final') {
            $reto = true;
            continue;
        }
        if (!isset($como[$t])) {
            continue;
        }

        [$icono, $texto] = $como[$t];

        if (isset($vistos[$texto])) {
            continue;
        }
        $vistos[$texto] = true;
        $lista[] = ['icono' => $icono, 'texto' => $texto];
    }

    $lista = array_slice($lista, 0, $reto ? 4 : 5);

    if ($reto) {
        $lista[] = ['icono' => '🏆', 'texto' => 'superar un reto final'];
    }

    return $lista;
}

/**
 * Completar: una frase con un hueco (`___`) y opciones para llenarlo.
 * La primera respuesta es la correcta; el motor las baraja.
 */
function completa(string $frase, string $correcta, array $otras): array
{
    return ['tipo' => 'completar', 'enunciado' => $frase,
            'correcta' => $correcta, 'otras' => array_values($otras)];
}

/** Identificar: una pregunta y opciones. La correcta va primero. */
function identifica(string $pregunta, string $correcta, array $otras): array
{
    return ['tipo' => 'identificar', 'enunciado' => $pregunta,
            'correcta' => $correcta, 'otras' => array_values($otras)];
}

/** Todas las introducciones, por slug. Se cargan una vez por petición. */
function introducciones(): array
{
    static $todas = null;

    if ($todas !== null) {
        return $todas;
    }

    $todas = [];

    foreach (glob(RUTA_INCLUDES . '/introducciones/*.php') ?: [] as $archivo) {
        $lote = require $archivo;

        if (is_array($lote)) {
            $todas += $lote;
        }
    }

    return $todas;
}

/**
 * La introducción de una actividad, lista para el motor, o null.
 *
 * Las opciones salen ya mezcladas con su respuesta y sin marcar cuál es:
 * el motor recibe el índice de la correcta, como en `opcion_multiple`.
 */
function introduccionDe(array $actividad): ?array
{
    $i = introducciones()[$actividad['slug'] ?? ''] ?? null;

    if (!$i) {
        return null;
    }

    $preguntas = [];

    foreach ($i['preguntas'] as $p) {
        $opciones = array_merge([$p['correcta']], $p['otras']);
        shuffle($opciones);

        $preguntas[] = [
            'tipo'      => $p['tipo'],
            'enunciado' => $p['enunciado'],
            'opciones'  => $opciones,
            'correcta'  => array_search($p['correcta'], $opciones, true),
        ];
    }

    $breve = ($i['modo'] ?? '') === 'breve';

    return [
        'modo'      => $breve ? 'breve' : 'completa',
        'titulo'    => $actividad['title'] ?? '',
        'icono'     => ($breve ? ($i['dibujo'] ?? null) : null) ?: ($actividad['icon'] ?? ''),
        'idea'      => $i['idea'],
        'claves'    => $i['claves'],
        'ejemplo'   => $i['ejemplo'],
        'preguntas' => $preguntas,
        // Lo que va a hacer, sacado de sus estaciones reales.
        'haras'     => $breve ? queSeHaceEn(tiposDeEstaciones((int) ($actividad['id'] ?? 0))) : [],
    ];
}

/** Los tipos de minijuego de una actividad, en el orden de sus estaciones. */
function tiposDeEstaciones(int $actividadId): array
{
    if ($actividadId <= 0 || !function_exists('traerTodo')) {
        return [];
    }

    return array_column(traerTodo(
        'SELECT game_type FROM activity_stations WHERE activity_id = ? ORDER BY position',
        [$actividadId]
    ), 'game_type');
}
