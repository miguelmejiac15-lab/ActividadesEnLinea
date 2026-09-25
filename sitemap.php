<?php
/**
 * sitemap.php — El mapa del sitio para los buscadores
 *
 * Se sirve como `/sitemap.xml` (lo reescribe el .htaccess de la raíz).
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ SE GENERA Y NO SE ESCRIBE A MANO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Son casi quinientas fichas y cada semana se publican más. Un archivo
 * escrito a mano estaría desactualizado el mismo día, y un sitemap que
 * miente es peor que no tener ninguno: Google reduce la frecuencia de
 * rastreo cuando encuentra direcciones que no existen.
 *
 * Saliendo de la base, el mapa siempre dice la verdad — incluida la
 * fecha de la última modificación de cada ficha, que es lo que le indica
 * al buscador qué volver a mirar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  SOLO ENTRA LO PÚBLICO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Fichas de actividades publicadas y páginas de catálogo. Nada del área
 * escolar, del aula ni del panel: ahí hay nombres de menores, y un
 * sitemap es una invitación explícita a rastrear.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

header('Content-Type: application/xml; charset=utf-8');

/*
 * Media hora de caché.
 *
 * El contenido cambia por despliegues, no por minuto, y un buscador
 * puede pedir este archivo a menudo: generarlo con 500 filas en cada
 * petición sería regalarle tiempo de CPU al rastreador.
 */
header('Cache-Control: public, max-age=1800');

/** Una entrada del mapa, con su fecha si se conoce. */
function urlDelMapa(string $ruta, ?string $fecha = null, string $frecuencia = 'weekly', string $peso = '0.6'): string
{
    $x  = "  <url>\n";
    $x .= '    <loc>' . htmlspecialchars(url($ruta), ENT_XML1, 'UTF-8') . "</loc>\n";

    if ($fecha) {
        // W3C datetime, que es el formato que pide la especificación.
        $x .= '    <lastmod>' . date('Y-m-d', strtotime($fecha)) . "</lastmod>\n";
    }

    $x .= '    <changefreq>' . $frecuencia . "</changefreq>\n";
    $x .= '    <priority>' . $peso . "</priority>\n";
    $x .= "  </url>\n";

    return $x;
}

echo '<?xml version="1.0" encoding="UTF-8"?>', "\n";
echo '<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">', "\n";

// ── Las páginas fijas ────────────────────────────────────────────────
echo urlDelMapa('', null, 'weekly', '1.0');
echo urlDelMapa('actividades/', null, 'daily', '0.9');
echo urlDelMapa('planes/', null, 'monthly', '0.8');

// ── Las categorías ───────────────────────────────────────────────────
foreach (traerTodo('SELECT slug FROM categories WHERE is_active = 1 ORDER BY sort_order') as $c) {
    echo urlDelMapa('actividades/?categoria=' . urlencode((string) $c['slug']), null, 'weekly', '0.7');
}

// ── Las fichas publicadas ────────────────────────────────────────────
//
// `updated_at` da el `lastmod`: sin él, el buscador no sabe si algo
// cambió y vuelve a rastrear las 487 cada vez.
foreach (traerTodo(
    'SELECT slug, updated_at FROM activities
      WHERE status = "published" ORDER BY updated_at DESC'
) as $a) {
    echo urlDelMapa(
        'actividades/ver.php?a=' . urlencode((string) $a['slug']),
        $a['updated_at'],
        'monthly',
        '0.6'
    );
}

echo '</urlset>', "\n";
