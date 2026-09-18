<?php
/**
 * funciones.php — Utilidades comunes
 *
 * Funciones pequeñas que se usan en todo el sitio: escapado de salida,
 * validación de entradas, tokens CSRF, formato de precios y lectura de
 * la configuración guardada en base de datos.
 */

declare(strict_types=1);

// =====================================================================
//  SALIDA SEGURA (defensa contra XSS)
// =====================================================================

/**
 * Escapa texto antes de imprimirlo en HTML.
 *
 * Regla del proyecto: TODO dato que venga de la base de datos o del
 * usuario se imprime con e(). Si un usuario guarda "<script>..." como
 * nombre, aquí se convierte en texto visible y no en código ejecutable.
 */
function e(?string $texto): string
{
    return htmlspecialchars($texto ?? '', ENT_QUOTES | ENT_SUBSTITUTE, 'UTF-8');
}

/** Imprime datos como JSON seguro para incrustar dentro de una etiqueta <script>. */
function jsonSeguro($datos): string
{
    return json_encode(
        $datos,
        JSON_UNESCAPED_UNICODE | JSON_HEX_TAG | JSON_HEX_AMP | JSON_HEX_APOS | JSON_HEX_QUOT
    );
}

/** Construye una URL absoluta dentro del sitio. */
function url(string $ruta = ''): string
{
    return URL_BASE . '/' . ltrim($ruta, '/');
}

/**
 * URL de una hoja de estilos o un script, con la marca de su última
 * modificación.
 *
 * Sin esto el navegador se queda con la copia que ya tenía y los cambios
 * no llegan: se arregla algo en el CSS, el servidor entrega la versión
 * nueva, y el usuario sigue viendo la vieja sin entender por qué. Pasó
 * con el pie de página — el arreglo estaba hecho y publicado, y en el
 * navegador seguía viéndose en una sola columna.
 *
 * La marca es la fecha del archivo, así que se actualiza sola con cada
 * cambio y nadie tiene que acordarse de subir un número a mano.
 */
function urlRecurso(string $ruta): string
{
    $archivo = RUTA_RAIZ . '/' . ltrim($ruta, '/');
    $sello   = is_file($archivo) ? filemtime($archivo) : null;

    return url($ruta) . ($sello ? '?v=' . $sello : '');
}

/** Redirige y termina la ejecución. */
function redirigir(string $ruta): void
{
    header('Location: ' . (preg_match('#^https?://#', $ruta) ? $ruta : url($ruta)));
    exit;
}


// =====================================================================
//  ENTRADAS
// =====================================================================

/** Lee un campo de $_POST ya recortado. */
function post(string $campo, string $porDefecto = ''): string
{
    $valor = $_POST[$campo] ?? $porDefecto;
    return is_string($valor) ? trim($valor) : $porDefecto;
}

/** Lee un campo de $_GET ya recortado. */
function get(string $campo, string $porDefecto = ''): string
{
    $valor = $_GET[$campo] ?? $porDefecto;
    return is_string($valor) ? trim($valor) : $porDefecto;
}

/** Lee un entero de $_GET (0 si no es válido). */
function getEntero(string $campo, int $porDefecto = 0): int
{
    return filter_input(INPUT_GET, $campo, FILTER_VALIDATE_INT) ?: $porDefecto;
}

/** Valida un correo; devuelve el correo normalizado o null. */
function correoValido(string $correo): ?string
{
    $correo = mb_strtolower(trim($correo));
    return filter_var($correo, FILTER_VALIDATE_EMAIL) ? $correo : null;
}

/** Convierte un texto en slug apto para URL: "Aventura de la Ñ" → "aventura-de-la-n". */
function slugificar(string $texto): string
{
    $texto = mb_strtolower(trim($texto), 'UTF-8');
    $texto = strtr($texto, [
        'á'=>'a','é'=>'e','í'=>'i','ó'=>'o','ú'=>'u','ü'=>'u','ñ'=>'n',
        'à'=>'a','è'=>'e','ì'=>'i','ò'=>'o','ù'=>'u','ç'=>'c',
    ]);
    $texto = preg_replace('/[^a-z0-9]+/', '-', $texto) ?? '';
    return trim($texto, '-');
}


// =====================================================================
//  CSRF — protección de formularios
// =====================================================================

/**
 * Devuelve el token CSRF de la sesión, creándolo si no existe.
 *
 * Sin esto, otra página podría enviar un formulario en nombre del
 * usuario mientras tiene la sesión abierta (por ejemplo, cambiar su
 * contraseña). El token demuestra que el formulario salió de este sitio.
 */
function tokenCsrf(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/** Imprime el campo oculto con el token, para incluir en cada formulario. */
function campoCsrf(): string
{
    return '<input type="hidden" name="csrf_token" value="' . e(tokenCsrf()) . '">';
}

/** Comprueba el token recibido. Comparación en tiempo constante. */
function csrfValido(): bool
{
    $enviado = $_POST['csrf_token'] ?? '';
    return is_string($enviado)
        && !empty($_SESSION['csrf_token'])
        && hash_equals($_SESSION['csrf_token'], $enviado);
}

/**
 * Corta la petición si el token CSRF no es válido.
 *
 * Se responde 403 (código estándar): Apache no reconoce el 419 que usan
 * algunos frameworks y lo convierte en un 500, que confundiría el
 * diagnóstico de un problema real.
 */
function exigirCsrf(): void
{
    if (csrfValido()) {
        return;
    }

    /*
     * ─────────────────────────────────────────────────────────────────
     *  UN ENVÍO REPETIDO NO ES UN ATAQUE
     * ─────────────────────────────────────────────────────────────────
     *
     * Si quien envía el formulario YA tiene sesión, lo que casi siempre
     * ha pasado es que pulsó dos veces, o volvió atrás y reenvió, o
     * tenía la página abierta en otra pestaña desde antes de entrar. El
     * token no cuadra, pero la persona está identificada y no hay nada
     * que proteger de ella misma.
     *
     * En ese caso se manda a su espacio con un aviso en su idioma, en
     * vez de un 403 en texto plano que parece que la plataforma se rompió
     * —que es justo lo que veía el usuario cuando el login «fallaba» y al
     * refrescar resultaba que sí había entrado—.
     *
     * Cuando NO hay sesión sigue cortando en seco: ahí sí puede ser una
     * petición de otro sitio, y es lo que el token existe para parar.
     */
    if (function_exists('haySesion') && haySesion()) {
        mensaje('info', 'Ese formulario ya se había enviado. Aquí tienes lo último.');
        redirigir('usuario/');
    }

    http_response_code(403);
    header('Content-Type: text/html; charset=UTF-8');

    exit('<!doctype html><html lang="es"><meta charset="utf-8">'
       . '<title>Formulario caducado</title>'
       . '<body style="font-family:system-ui,sans-serif;max-width:34rem;margin:12vh auto;'
       . 'padding:0 1.2rem;line-height:1.6;color:#4a5568">'
       . '<h1 style="color:#2f3b52;font-size:1.3rem">Ese formulario ya no vale</h1>'
       . '<p>Pasó demasiado tiempo desde que se abrió la página, o se envió dos veces.</p>'
       . '<p><a href="' . e(url('login.php')) . '" style="color:#29b6f6;font-weight:600">'
       . 'Volver a empezar</a></p></body></html>');
}


// =====================================================================
//  MENSAJES ENTRE PÁGINAS (flash)
// =====================================================================

/** Guarda un mensaje para mostrarlo tras una redirección. */
function mensaje(string $tipo, string $texto): void
{
    $_SESSION['flash'][] = ['tipo' => $tipo, 'texto' => $texto];
}

/** Devuelve los mensajes pendientes y los borra. */
function mensajesPendientes(): array
{
    $lista = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $lista;
}


// =====================================================================
//  CONFIGURACIÓN GUARDADA EN BASE DE DATOS
// =====================================================================

/**
 * Lee un ajuste de la tabla `settings`.
 *
 * Se cachea en memoria: la tabla entera se trae una vez y de ahí salen
 * todas las lecturas de la petición, que si no serían decenas.
 *
 * El caché tiene una consecuencia que hay que tener presente: **escribir
 * un ajuste no cambia lo que devuelve esta función**, porque el valor ya
 * está en memoria. Casi siempre da igual —quien guarda ajustes redirige
 * justo después, y la petición siguiente empieza con el caché vacío—
 * pero no siempre: guardar y volver a leer en la misma petición devolvía
 * el valor viejo, y con eso se decidía, por ejemplo, si avisar de que
 * falta el token de la pasarela.
 *
 * Por eso `guardarAjuste()` llama a `olvidarAjustes()`.
 */
function ajuste(string $clave, ?string $porDefecto = null): ?string
{
    $cache = &cacheAjustes();

    if ($cache === null) {
        $cache = [];
        foreach (traerTodo('SELECT `key`, `value` FROM settings') as $fila) {
            $cache[$fila['key']] = $fila['value'];
        }
    }

    return $cache[$clave] ?? $porDefecto;
}

/**
 * El caché de ajustes, por referencia.
 *
 * Existe para que `olvidarAjustes()` pueda vaciarlo: una `static` dentro
 * de `ajuste()` no se puede tocar desde fuera, y separar el estado en su
 * propia función es la forma menos rebuscada de compartirlo entre las dos.
 */
function &cacheAjustes(): ?array
{
    static $cache = null;
    return $cache;
}

/** Olvida el caché de ajustes: la próxima lectura vuelve a la base. */
function olvidarAjustes(): void
{
    $cache = &cacheAjustes();
    $cache = null;
}


// =====================================================================
//  FORMATO
// =====================================================================

/** Formatea un precio en pesos colombianos: 99000 → "$99.000". */
function precioCop(?int $valor): string
{
    if ($valor === null) {
        return '—';
    }
    if ($valor === 0) {
        return '$0';
    }
    return '$' . number_format($valor, 0, ',', '.');
}

/**
 * Ahorro del plan anual frente a pagar 12 meses sueltos.
 * Devuelve null si no hay ambas modalidades.
 */
function ahorroAnual(?int $mensual, ?int $anual): ?array
{
    if (!$mensual || !$anual) {
        return null;
    }
    $doceMeses = $mensual * 12;
    if ($doceMeses <= $anual) {
        return null;
    }
    return [
        'monto'      => $doceMeses - $anual,
        'porcentaje' => (int) round((($doceMeses - $anual) / $doceMeses) * 100),
    ];
}

/** Fecha legible en español: "26 de agosto de 2026". */
function fechaLarga(?string $fecha): string
{
    if (!$fecha) {
        return '—';
    }
    $meses = [1=>'enero','febrero','marzo','abril','mayo','junio',
              'julio','agosto','septiembre','octubre','noviembre','diciembre'];
    $ts = strtotime($fecha);
    return date('j', $ts) . ' de ' . $meses[(int) date('n', $ts)] . ' de ' . date('Y', $ts);
}

/** ¿La actividad se publicó hace poco? Alimenta la etiqueta "Nuevo". */
function esNueva(?string $publicadaEn): bool
{
    if (!$publicadaEn) {
        return false;
    }
    $dias = (int) ajuste('nuevas_actividades_dias', '60');
    return strtotime($publicadaEn) >= strtotime("-{$dias} days");
}
