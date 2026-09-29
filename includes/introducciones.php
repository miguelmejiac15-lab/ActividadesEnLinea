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

    return [
        'titulo'    => $actividad['title'] ?? '',
        'icono'     => $actividad['icon'] ?? '',
        'idea'      => $i['idea'],
        'claves'    => $i['claves'],
        'ejemplo'   => $i['ejemplo'],
        'preguntas' => $preguntas,
    ];
}
