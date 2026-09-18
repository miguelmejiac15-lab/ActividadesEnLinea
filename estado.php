<?php
/**
 * estado.php — ¿Está viva la aplicación?
 *
 * Lo consulta el HEALTHCHECK del contenedor y, a través de él, Coolify
 * para decidir si un despliegue salió bien o hay que revertirlo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ COMPRUEBA LA BASE Y NO SOLO QUE PHP RESPONDE
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un `echo "ok"` a secas diría que todo va bien mientras el sitio
 * devuelve 503 en todas sus páginas: Apache y PHP arrancan
 * perfectamente sin base de datos. Coolify daría el despliegue por bueno
 * y el error lo descubriría un visitante.
 *
 * Así que se hace la consulta más barata posible —un SELECT 1— que es la
 * que de verdad distingue «vivo» de «arrancado».
 *
 * ─────────────────────────────────────────────────────────────────────
 *  NO DICE NADA MÁS QUE «ok»
 * ─────────────────────────────────────────────────────────────────────
 *
 * Es una dirección pública y sin autenticación, así que no puede contar
 * versiones, nombres de servidor ni mensajes de error: eso es justo lo
 * que busca quien está mirando qué hay detrás. El detalle del fallo va al
 * log, donde lo lee quien administra.
 *
 * La lista completa de comprobaciones —credenciales, webhook, precios—
 * está en el panel (`includes/puesta-en-marcha.php`), detrás de sesión de
 * administrador.
 */

declare(strict_types=1);

require_once __DIR__ . '/config/config.php';

header('Content-Type: text/plain; charset=utf-8');
// Un estado en caché no es un estado.
header('Cache-Control: no-store, private');

try {
    traerValor('SELECT 1');
} catch (Throwable $e) {
    error_log('[estado] la base no responde: ' . $e->getMessage());

    http_response_code(503);
    exit('sin base de datos');
}

echo 'ok';
