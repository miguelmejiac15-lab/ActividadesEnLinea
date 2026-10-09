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
    return gaMedicionId() !== '' && paginaMedible();
}

/**
 * Las puertas 2 a 4: ¿esta página, con quien la mira, se puede medir?
 *
 * Aparte del identificador para que Google y Meta pasen por EXACTAMENTE
 * el mismo filtro. Dos copias de estas reglas acabarían diferenciándose,
 * y el día que una zona de niños se añadiera a una sola, el otro tercero
 * seguiría recibiendo datos de ella.
 */
function paginaMedible(): bool
{
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
     * Una cuenta propia de un menor. El registro abierto acepta cuentas
     * de chicos con el correo de un acudiente; su sesión no es de aula
     * ni de estudiante, pero sigue siendo la de un menor.
     */
    if (function_exists('usuarioActual') && esCuentaDeMenor(usuarioActual())) {
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

    // Lo mismo para Meta: verificar el dominio no envía nada.
    $verificacionMeta = metaVerificacion();

    if ($verificacionMeta !== '') {
        $salida .= '<meta name="facebook-domain-verification" content="'
                 . e($verificacionMeta) . '">' . "\n";
    }

    $salida .= etiquetaMeta();

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


// =====================================================================
//  PÍXEL DE META (Facebook e Instagram)
//
//  Sirve para una sola cosa: saber si los anuncios dirigidos a familias
//  y docentes traen registros y compras. Por eso:
//
//   - Pasa por el mismo filtro que Google (`paginaMedible()`): nunca se
//     carga donde hay un niño. Las condiciones de Meta además prohíben
//     enviarle datos de menores de 13 años.
//   - Sin coincidencia avanzada: no se envía ni correo, ni nombre, ni
//     teléfono. Y sin `autoConfig`, que haría a Meta leer los botones y
//     los datos de la página por su cuenta.
//   - Con consentimiento: si el ajuste lo pide (por defecto, sí), no se
//     carga nada de Meta hasta que la persona acepta.
// =====================================================================

/** El identificador del píxel, o cadena vacía. Entorno primero, ajuste después. */
function metaPixelId(): string
{
    $delEntorno = getenv('META_PIXEL_ID');

    if (is_string($delEntorno) && trim($delEntorno) !== '') {
        return trim($delEntorno);
    }

    return trim((string) ajuste('meta_pixel_id', ''));
}

/** El código de verificación de dominio de Meta, o cadena vacía. */
function metaVerificacion(): string
{
    $delEntorno = getenv('META_VERIFICACION');

    if (is_string($delEntorno) && trim($delEntorno) !== '') {
        return trim($delEntorno);
    }

    return trim((string) ajuste('meta_verificacion', ''));
}

/** ¿Hay que pedir permiso antes de cargar el píxel? Por defecto, sí. */
function metaPideConsentimiento(): bool
{
    return ajuste('meta_consentimiento', '1') !== '0';
}

/**
 * Apunta un evento para enviarlo a Meta.
 *
 * No se envía en el acto: la página que lo produce puede ser una
 * redirección, o una zona privada donde el píxel no se carga. Se guarda
 * en la sesión y sale en la siguiente página medible que vea la persona.
 * Si en media hora no llega a ninguna, se descarta.
 *
 * `$clave` evita contar dos veces lo mismo: recargar la página del pago
 * aprobado no debe registrar otra compra.
 *
 * @param array<string, string|int|float> $datos solo datos del negocio:
 *        valor, moneda, plan. Nunca datos de la persona.
 */
function metaEvento(string $nombre, array $datos = [], ?string $clave = null): void
{
    if (metaPixelId() === '' || session_status() !== PHP_SESSION_ACTIVE) {
        return;
    }

    if ($clave !== null) {
        $enviados = $_SESSION['meta_enviados'] ?? [];
        if (in_array($clave, $enviados, true)) {
            return;
        }
        $enviados[] = $clave;
        $_SESSION['meta_enviados'] = array_slice($enviados, -30);
    }

    $_SESSION['meta_eventos'][] = [
        'nombre' => $nombre,
        'datos'  => $datos,
        'id'     => $clave ?? bin2hex(random_bytes(8)),
        'cuando' => time(),
    ];
}

/** Lo que va dentro del `<head>` para Meta. Vacío si no corresponde. */
function etiquetaMeta(): string
{
    $id = metaPixelId();

    if ($id === '' || !paginaMedible()) {
        return '';
    }

    // Los eventos pendientes salen ahora, y solo una vez.
    $eventos = [];
    foreach ($_SESSION['meta_eventos'] ?? [] as $ev) {
        if (time() - (int) $ev['cuando'] <= 1800) {
            $eventos[] = ['n' => $ev['nombre'], 'd' => (object) $ev['datos'], 'id' => $ev['id']];
        }
    }
    unset($_SESSION['meta_eventos']);

    $config = [
        'id'          => $id,
        'eventos'     => $eventos,
        'consentir'   => metaPideConsentimiento(),
    ];

    /*
     * El cargador oficial de Meta, pero detrás de la decisión de la
     * persona. La decisión se guarda en una cookie propia de seis meses
     * (`ael_cookies` = si | no), que no identifica a nadie.
     */
    return '<script>' . "\n"
         . '(function(){'
         . 'var C=' . jsonSeguro($config) . ';'
         . 'function decision(){var m=document.cookie.match(/(?:^|; )ael_cookies=(si|no)/);return m?m[1]:null;}'
         . 'function guardar(v){document.cookie="ael_cookies="+v+"; max-age=15552000; path=/; SameSite=Lax"+(location.protocol==="https:"?"; Secure":"");}'
         . 'function cargar(){'
         .   'if(window.fbq)return;'
         .   '!function(f,b,e,v,n,t,s){if(f.fbq)return;n=f.fbq=function(){n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};'
         .   'if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";n.queue=[];t=b.createElement(e);t.async=!0;'
         .   't.src=v;s=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}(window,document,"script","https://connect.facebook.net/es_LA/fbevents.js");'
         .   'fbq("set","autoConfig",false,C.id);'
         .   'fbq("init",C.id);'
         .   'fbq("track","PageView");'
         .   'C.eventos.forEach(function(ev){fbq("track",ev.n,ev.d,{eventID:ev.id});});'
         . '}'
         . 'function aviso(){'
         .   'var a=document.createElement("div");a.className="aviso-cookies";a.setAttribute("role","dialog");a.setAttribute("aria-label","Cookies");'
         .   'a.innerHTML=\'<p>Usamos una cookie de Meta para saber si nuestros anuncios en Facebook e Instagram le sirven a las familias y docentes. \''
         .     '+\'Nunca se activa donde juegan los niños ni envía sus datos.</p>\''
         .     '+\'<div class="aviso-cookies-botones"><button type="button" class="btn btn-secundario btn-chico" data-v="no">No, gracias</button>\''
         .     '+\'<button type="button" class="btn btn-principal btn-chico" data-v="si">Aceptar</button></div>\';'
         .   'a.addEventListener("click",function(e){var v=e.target&&e.target.getAttribute("data-v");if(!v)return;guardar(v);a.remove();if(v==="si")cargar();});'
         .   'document.body.appendChild(a);'
         . '}'
         . 'if(!C.consentir||decision()==="si"){cargar();}'
         . 'else if(decision()===null){'
         .   'if(document.readyState==="loading")document.addEventListener("DOMContentLoaded",aviso);else aviso();'
         . '}'
         . '})();' . "\n"
         . '</script>' . "\n";
}

/**
 * ¿La cuenta es de alguien menor de 18 años?
 *
 * Con el año de nacimiento —lo único que se guarda— no se sabe el día
 * exacto: quien cumple 18 este año cuenta como menor hasta el siguiente.
 * Mejor esperar unos meses de más que medir a un menor.
 */
function esCuentaDeMenor(?array $usuario): bool
{
    $anio = (int) ($usuario['birth_year'] ?? 0);

    return $anio > 0 && ((int) date('Y') - $anio) <= 18;
}
