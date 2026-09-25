<?php
/**
 * analitica.php — Google Analytics y Search Console
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA DECISIÓN QUE GOBIERNA ESTE ARCHIVO
 * ─────────────────────────────────────────────────────────────────────
 *
 * La etiqueta de Google **no se carga en las páginas donde hay un niño**.
 * Ni en el reproductor, ni en el aula, ni en el espacio del estudiante,
 * ni en el panel del colegio.
 *
 * No es prudencia de más. La plataforma trata datos de menores
 * identificados con nombre y curso (Decreto 0769 de 2026), y Analytics
 * no es un contador de visitas: construye un perfil de comportamiento
 * —qué toca, cuánto tarda, desde dónde, con qué dispositivo— y lo manda
 * a un tercero. Hacer eso con la sesión de un niño de seis años es
 * exactamente lo que el proyecto se comprometió a no hacer, y además
 * las condiciones de Google prohíben enviarle datos de menores.
 *
 * Lo que sí se mide es la parte pública: la portada, los planes, las
 * fichas del catálogo. Ahí es donde están las preguntas que Analytics
 * responde bien —de dónde llega la gente, qué buscó, dónde abandona— y
 * ahí no hay ningún niño con sesión abierta.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO INTERNO NO SE SUSTITUYE
 * ─────────────────────────────────────────────────────────────────────
 *
 * El progreso, el tiempo de juego y el uso por curso ya se miden dentro
 * (`includes/uso.php`, Panel → Métricas) y se quedan ahí: son datos
 * pedagógicos, no de mercadeo, y no tienen por qué salir del servidor.
 * Google mide la adquisición; el panel mide el aprendizaje.
 */

declare(strict_types=1);


/**
 * El identificador de medición de GA4 (`G-XXXXXXXXXX`), o cadena vacía.
 *
 * Como las credenciales de la pasarela: primero la variable de entorno,
 * después el ajuste. Así se puede cambiar en el servidor sin tocar la
 * base, y apagarlo de golpe si hiciera falta.
 */
function gaMedicionId(): string
{
    $delEntorno = getenv('GA_MEDICION_ID');

    if (is_string($delEntorno) && trim($delEntorno) !== '') {
        return trim($delEntorno);
    }

    return trim((string) ajuste('ga_medicion_id', ''));
}

/** El código de verificación de Search Console, o cadena vacía. */
function gscVerificacion(): string
{
    $delEntorno = getenv('GSC_VERIFICACION');

    if (is_string($delEntorno) && trim($delEntorno) !== '') {
        return trim($delEntorno);
    }

    return trim((string) ajuste('gsc_verificacion', ''));
}

/**
 * ¿Esta petición concreta se puede medir?
 *
 * ─────────────────────────────────────────────────────────────────────
 *  SE PREGUNTA POR LA PÁGINA, NO POR EL SITIO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Una casilla global de «activar Analytics» sería más simple y estaría
 * mal: la misma instalación sirve una portada pública y el cuaderno de
 * un niño. Lo que decide no es si la medición está encendida, sino
 * **quién está delante de esta página**.
 *
 * Las cuatro puertas, en orden:
 *
 *   1. Hace falta un identificador configurado.
 *   2. Nada en desarrollo: medir localhost ensucia las cifras con las
 *      visitas del propio equipo.
 *   3. Nada si quien mira es —o puede ser— un menor: sesión de aula,
 *      rol de estudiante o modo niño.
 *   4. Nada en las zonas privadas: admin, escuela, colegio, aula,
 *      espacio del usuario y el reproductor.
 */
function analiticaPermitida(): bool
{
    if (gaMedicionId() === '') {
        return false;
    }

    // En XAMPP no se mide: son visitas de quien está programando.
    if (defined('ES_DESARROLLO') && ES_DESARROLLO) {
        return false;
    }

    /*
     * Un niño, por cualquiera de los tres caminos por los que puede
     * estar delante de la pantalla.
     *
     * Se comprueba con `function_exists()` porque este archivo se carga
     * antes que algunos módulos del área escolar.
     */
    if (function_exists('esSesionDeAula') && esSesionDeAula()) {
        return false;
    }

    if (function_exists("enModoNino") && enModoNino()) {
        return false;
    }

    if (function_exists('tieneRol') && usuarioActual() && tieneRol('student')) {
        return false;
    }

    /*
     * Las zonas privadas, por la ruta.
     *
     * Se mira `SCRIPT_NAME` y no `REQUEST_URI`: el segundo lo controla
     * quien visita —puede traer `?x=/planes/`— y aquí se está decidiendo
     * si se envían datos a un tercero. Se compara contra lo que el
     * servidor va a ejecutar de verdad.
     */
    $ruta = (string) ($_SERVER['SCRIPT_NAME'] ?? '');

    foreach (['/admin/', '/escuela/', '/colegio/', '/aula/', '/usuario/', '/api/'] as $zona) {
        if (str_contains($ruta, $zona)) {
            return false;
        }
    }

    // El reproductor es donde el niño juega, aunque entre con la cuenta
    // de su familia.
    if (str_contains($ruta, 'jugar.php')) {
        return false;
    }

    return true;
}

/**
 * Lo que va dentro del `<head>`.
 *
 * Devuelve cadena vacía cuando no corresponde medir, y así quien lo
 * llama no tiene que saber ninguna de las reglas de arriba.
 */
function etiquetaAnalitica(): string
{
    $salida = '';

    /*
     * La verificación de Search Console va SIEMPRE que esté configurada,
     * incluso donde no se mide.
     *
     * Son cosas distintas y conviene no confundirlas: esta etiqueta no
     * envía nada a Google, solo demuestra que el dominio es tuyo. Si
     * solo se pusiera en las páginas medidas y Google pidiera verificar
     * justo otra, la verificación fallaría sin motivo aparente.
     */
    $verificacion = gscVerificacion();

    if ($verificacion !== '') {
        $salida .= '<meta name="google-site-verification" content="'
                 . e($verificacion) . '">' . "\n";
    }

    if (!analiticaPermitida()) {
        return $salida;
    }

    $id = gaMedicionId();

    /*
     * `anonymize_ip` y la publicidad apagada.
     *
     * Aunque aquí no haya menores con sesión, esta es una plataforma
     * infantil: quien llega a la portada suele ser el papá o la profe de
     * un niño. No se construyen audiencias publicitarias con eso, y el
     * proyecto se comprometió a no meter publicidad intrusiva.
     */
    $salida .= '<script async src="https://www.googletagmanager.com/gtag/js?id='
             . rawurlencode($id) . '"></script>' . "\n"
             . '<script>' . "\n"
             . 'window.dataLayer = window.dataLayer || [];' . "\n"
             . 'function gtag(){dataLayer.push(arguments);}' . "\n"
             . 'gtag("js", new Date());' . "\n"
             . 'gtag("config", ' . jsonSeguro($id) . ', {'
             . '"anonymize_ip": true, '
             . '"allow_google_signals": false, '
             . '"allow_ad_personalization_signals": false'
             . '});' . "\n"
             . '</script>' . "\n";

    return $salida;
}
