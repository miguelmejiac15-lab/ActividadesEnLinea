<?php
/**
 * seo.php — Lo que leen los buscadores y los asistentes de IA
 *
 * Tres cosas, todas invisibles para quien navega:
 *
 *   1. La URL canónica: cuál es LA dirección de una página. El catálogo
 *      se puede pedir con veinte combinaciones de filtros; sin canónica,
 *      Google las trata como veinte páginas casi iguales y reparte entre
 *      ellas lo que debería sumar una.
 *   2. Las etiquetas Open Graph: el título y la descripción con que se ve
 *      un enlace compartido por WhatsApp o redes.
 *   3. Datos estructurados (JSON-LD de schema.org): qué ES cada página
 *      —una organización, un recurso educativo para 8 a 10 años, unas
 *      preguntas frecuentes— dicho en un formato que los buscadores y los
 *      modelos de lenguaje leen sin adivinar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  SOLO PÁGINAS PÚBLICAS, Y SOLO DATOS QUE YA SE VEN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Nada de esto sale en zonas con sesión, y nada dice algo que la página
 * no muestre ya: marcar datos que el visitante no ve es justo lo que los
 * buscadores castigan. Ningún dato de usuarios, y menos de menores.
 */

declare(strict_types=1);

/** Nombre del sitio, tal como lo configura el panel. */
function seoSitio(): string
{
    return (string) ajuste('sitio_nombre', 'Actividades en Línea');
}

/** Un bloque `<script type="application/ld+json">` listo para imprimir. */
function jsonLd(array $datos): string
{
    $json = json_encode(
        ['@context' => 'https://schema.org'] + $datos,
        JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES | JSON_HEX_TAG | JSON_HEX_AMP
    );

    return $json === false ? '' : '<script type="application/ld+json">' . $json . "</script>\n";
}

/** La organización. Va en la portada: es quien está detrás del sitio. */
function esquemaOrganizacion(): array
{
    return [
        '@type'       => 'Organization',
        '@id'         => url('') . '#organizacion',
        'name'        => seoSitio(),
        'url'         => url(''),
        'logo'        => url('assets/marca/lapiz.svg'),
        'slogan'      => 'Pedagogía de avanzada, potenciada por tecnología',
        'description' => 'Plataforma colombiana de actividades educativas interactivas para niños '
                       . 'de preescolar y primaria, para familias, docentes y colegios.',
        'areaServed'  => 'CO',
    ];
}

/**
 * El sitio, con su buscador.
 *
 * `SearchAction` le dice al buscador cómo buscar dentro del catálogo:
 * es lo que permite que aparezca una caja de búsqueda bajo el resultado.
 */
function esquemaSitio(): array
{
    return [
        '@type'           => 'WebSite',
        '@id'             => url('') . '#sitio',
        'name'            => seoSitio(),
        'url'             => url(''),
        'inLanguage'      => 'es',
        'publisher'       => ['@id' => url('') . '#organizacion'],
        'potentialAction' => [
            '@type'       => 'SearchAction',
            'target'      => url('actividades/') . '?buscar={search_term_string}',
            'query-input' => 'required name=search_term_string',
        ],
    ];
}

/** La plataforma como aplicación educativa web, con su entrada gratuita. */
function esquemaPlataforma(int $totalActividades): array
{
    return [
        '@type'               => 'WebApplication',
        'name'                => seoSitio(),
        'url'                 => url(''),
        'applicationCategory' => 'EducationalApplication',
        'operatingSystem'     => 'Cualquiera con navegador web',
        'inLanguage'          => 'es',
        'description'         => 'Más de ' . (int) (floor($totalActividades / 100) * 100)
                               . ' actividades interactivas con explicación, audio y ejercicios, '
                               . 'para niños de 3 a 12 años. Se puede probar gratis.',
        'audience'            => [
            '@type'           => 'EducationalAudience',
            'educationalRole' => ['student', 'parent', 'teacher'],
        ],
        'offers'              => [
            '@type'         => 'Offer',
            'price'         => '0',
            'priceCurrency' => 'COP',
            'description'   => 'Plan gratis: todas las actividades, la primera parte de cada una.',
        ],
        'publisher'           => ['@id' => url('') . '#organizacion'],
    ];
}

/**
 * Una actividad como recurso educativo.
 *
 * Todo lo que va aquí ya se ve en la ficha: el nombre, lo que se
 * aprende, la edad, la materia y cuántos ejercicios tiene.
 */
function esquemaRecursoAprendizaje(array $actividad, int $estaciones): array
{
    $r = [
        '@type'                 => 'LearningResource',
        'name'                  => (string) $actividad['title'],
        'url'                   => url('actividades/ver.php?a=' . urlencode((string) $actividad['slug'])),
        'inLanguage'            => 'es',
        'learningResourceType'  => 'Actividad interactiva',
        'interactivityType'     => 'active',
        'educationalUse'        => ['práctica', 'refuerzo'],
        'isAccessibleForFree'   => ($actividad['access_type'] ?? '') === 'free',
        'provider'              => ['@type' => 'Organization', 'name' => seoSitio(), 'url' => url('')],
    ];

    if (!empty($actividad['description'])) {
        $r['description'] = (string) $actividad['description'];
    }
    if (!empty($actividad['objective'])) {
        $r['teaches'] = (string) $actividad['objective'];
    }
    if (!empty($actividad['categoria'])) {
        $r['about'] = (string) $actividad['categoria'];
    }
    if (!empty($actividad['nivel'])) {
        $r['educationalLevel'] = (string) $actividad['nivel'];
    }
    if (isset($actividad['min_age'], $actividad['max_age'])) {
        $r['typicalAgeRange'] = (int) $actividad['min_age'] . '-' . (int) $actividad['max_age'];
    }
    if ($estaciones > 0) {
        $r['hasPart'] = ['@type' => 'ItemList', 'numberOfItems' => $estaciones,
                         'name' => 'Estaciones de la actividad'];
    }
    if (!empty($actividad['duration_minutes'])) {
        $r['timeRequired'] = 'PT' . (int) $actividad['duration_minutes'] . 'M';
    }

    return $r;
}

/** Migas de pan: [['nombre' => …, 'url' => …], …] en orden. */
function esquemaMigas(array $migas): array
{
    $items = [];

    foreach (array_values($migas) as $i => $m) {
        $items[] = ['@type' => 'ListItem', 'position' => $i + 1,
                    'name' => (string) $m['nombre'], 'item' => (string) $m['url']];
    }

    return ['@type' => 'BreadcrumbList', 'itemListElement' => $items];
}

/** Preguntas frecuentes: [['p' => pregunta, 'r' => respuesta], …]. */
function esquemaFaq(array $preguntas): array
{
    return [
        '@type'      => 'FAQPage',
        'mainEntity' => array_map(static fn(array $f): array => [
            '@type'          => 'Question',
            'name'           => $f['p'],
            'acceptedAnswer' => ['@type' => 'Answer', 'text' => $f['r']],
        ], array_values($preguntas)),
    ];
}

/**
 * Las preguntas frecuentes de la portada.
 *
 * Viven aquí y no en la plantilla porque salen dos veces —el HTML que se
 * lee y el JSON-LD que lee el buscador— y tienen que decir exactamente lo
 * mismo. Respuestas de dos o tres frases: es la medida que un asistente
 * de IA cita tal cual.
 */
function preguntasFrecuentes(int $totalActividades): array
{
    $cifra = (int) (floor($totalActividades / 100) * 100);

    return [
        ['p' => '¿Qué es Actividades en Línea?',
         'r' => 'Es una plataforma de actividades educativas interactivas para niños de preescolar y primaria. '
              . 'Cada actividad combina una explicación, audio y ejercicios por estaciones, de lo más sencillo a lo más difícil.'],
        ['p' => '¿Para qué edades son las actividades?',
         'r' => 'Para niños de 3 a 12 años: preescolar, primero y segundo, tercero y cuarto, y quinto y sexto. '
              . 'Cada actividad indica su nivel y se puede filtrar por edad en el catálogo.'],
        ['p' => '¿Qué materias incluye?',
         'r' => 'Lectoescritura, matemática, ciencias, ciencias sociales, inglés, artística, tecnología, '
              . 'pensamiento lógico, valores, vida y bienestar, y una línea para aprender sin barreras. '
              . 'Hoy son más de ' . $cifra . ' actividades y la biblioteca sigue creciendo.'],
        ['p' => '¿Se puede usar gratis?',
         'r' => 'Sí. Con una cuenta gratuita se entra a todas las actividades y se juega la primera parte de cada una. '
              . 'La Biblioteca Completa abre todas las estaciones.'],
        ['p' => '¿Sirve para docentes y colegios?',
         'r' => 'Sí. Con la Licencia Escuela el docente crea cursos, asigna actividades en el orden que quiera, '
              . 'deja tareas para la casa y consulta el progreso de cada estudiante.'],
        ['p' => '¿En qué dispositivos funciona?',
         'r' => 'En cualquier computador, tableta o celular con navegador e internet. No hay que instalar nada.'],
        ['p' => '¿Cómo cuidan los datos de los niños?',
         'r' => 'Las cuentas de menores de 14 años piden el correo de un adulto responsable, no hay publicidad y '
              . 'no guardamos datos de tarjetas. Las listas de estudiantes de los colegios no son visibles para buscadores.'],
    ];
}
