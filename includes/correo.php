<?php
/**
 * correo.php — Enviar correo sin depender de nada instalado
 *
 * El proyecto no usa Composer y XAMPP no trae un servidor de correo que
 * funcione, así que `mail()` en Windows falla o se traga los mensajes en
 * silencio. Por eso aquí hay un cliente SMTP pequeño: se pegan los datos
 * de una cuenta —Gmail, el correo del dominio, el que sea— en el panel y
 * el envío funciona sin instalar nada.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA QUE MÁS IMPORTA: NUNCA MENTIR SOBRE UN ENVÍO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un correo que no sale tiene que decirse. Es tentador devolver `true` y
 * dejar que el usuario espere un mensaje que nunca llega —queda más
 * limpio en pantalla— pero eso convierte «recuperar la contraseña» en una
 * puerta cerrada sin cartel: la persona reintenta, revisa el spam, y se
 * va.
 *
 * `enviarCorreo()` devuelve siempre si salió o no, con el motivo. Quien
 * llama decide qué contarle al usuario, pero decide sabiendo la verdad.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA CONTRASEÑA DEL BUZÓN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Se puede poner en `settings` o —mejor en un servidor de verdad— en la
 * variable de entorno `CORREO_CLAVE`, que gana. No se escribe nunca en un
 * log ni se muestra entera en el panel.
 */

declare(strict_types=1);


// =====================================================================
//  CONFIGURACIÓN
// =====================================================================

/** Cómo se envía: 'smtp', 'php' (la función mail) o 'ninguno'. */
function correoMetodo(): string
{
    $m = trim((string) ajuste('correo_metodo', 'ninguno'));

    return in_array($m, ['smtp', 'php'], true) ? $m : 'ninguno';
}

function correoHost(): string     { return trim((string) ajuste('correo_host', '')); }
function correoPuerto(): int      { return max(1, (int) ajuste('correo_puerto', '587')); }
function correoUsuario(): string  { return trim((string) ajuste('correo_usuario', '')); }

/** 'tls' (STARTTLS, puerto 587), 'ssl' (directo, 465) o 'ninguna'. */
function correoSeguridad(): string
{
    $s = trim((string) ajuste('correo_seguridad', 'tls'));

    return in_array($s, ['tls', 'ssl', 'ninguna'], true) ? $s : 'tls';
}

function correoClave(): string
{
    $env = getenv('CORREO_CLAVE');

    if (is_string($env) && trim($env) !== '') {
        return trim($env);
    }

    return (string) ajuste('correo_clave', '');
}

/** Desde qué dirección sale el correo. */
function correoDesde(): string
{
    $d = trim((string) ajuste('correo_desde', ''));

    // Si no se puso una, se usa la cuenta SMTP: mandar desde una
    // dirección que no es la autenticada hace que medio mundo lo marque
    // como spam, cuando no lo rechaza directamente.
    return $d !== '' ? $d : correoUsuario();
}

function correoDesdeNombre(): string
{
    return trim((string) ajuste('correo_desde_nombre', ajuste('sitio_nombre', 'Actividades en Línea')));
}

/** ¿Hay lo mínimo para que un correo pueda salir? */
function correoConfigurado(): bool
{
    $metodo = correoMetodo();

    if ($metodo === 'ninguno') {
        return false;
    }
    if ($metodo === 'php') {
        return correoDesde() !== '';
    }

    return correoHost() !== '' && correoUsuario() !== '' && correoClave() !== '';
}


// =====================================================================
//  ENVÍO
// =====================================================================

/**
 * Envía un correo. Devuelve si salió y por qué no, si no salió.
 *
 * @return array{ok:bool, error:?string}
 */
function enviarCorreo(string $para, string $asunto, string $html, string $texto = ''): array
{
    $para = correoValido($para) ?? '';

    if ($para === '') {
        return ['ok' => false, 'error' => 'La dirección de destino no es válida.'];
    }

    if (!correoConfigurado()) {
        return ['ok' => false, 'error' => 'El envío de correo no está configurado.'];
    }

    // Una versión en texto plano siempre, aunque nadie la lea: sin ella
    // varios filtros puntúan el mensaje como sospechoso.
    if ($texto === '') {
        $texto = trim(html_entity_decode(strip_tags(
            preg_replace('/<br\s*\/?>/i', "\n", $html) ?? $html
        ), ENT_QUOTES, 'UTF-8'));
    }

    return correoMetodo() === 'smtp'
        ? smtpEnviar($para, $asunto, $html, $texto)
        : mailPhpEnviar($para, $asunto, $html, $texto);
}

/** Cabeceras comunes a los dos métodos. */
function correoCabeceras(string $limite): array
{
    return [
        'From: ' . correoNombreDireccion(correoDesdeNombre(), correoDesde()),
        'MIME-Version: 1.0',
        'Content-Type: multipart/alternative; boundary="' . $limite . '"',
        'Date: ' . date('r'),
        // Un Message-ID propio evita que algunos servidores lo descarten.
        'Message-ID: <' . bin2hex(random_bytes(12)) . '@' . correoDominio() . '>',
    ];
}

/** Dominio del remitente, para el Message-ID. */
function correoDominio(): string
{
    $partes = explode('@', correoDesde());

    return count($partes) === 2 && $partes[1] !== '' ? $partes[1] : 'localhost';
}

/**
 * «Nombre <direccion@ejemplo.com>», con el nombre saneado.
 *
 * Los saltos de línea se quitan a propósito: un nombre con `\r\n` dentro
 * permitiría inyectar cabeceras y, con ellas, destinatarios ocultos. Es
 * la vulnerabilidad clásica de los formularios de contacto.
 */
function correoNombreDireccion(string $nombre, string $direccion): string
{
    $nombre    = trim(str_replace(["\r", "\n", '"'], '', $nombre));
    $direccion = trim(str_replace(["\r", "\n"], '', $direccion));

    return $nombre !== '' ? '"' . $nombre . '" <' . $direccion . '>' : $direccion;
}

/** Cuerpo multipart con las dos versiones. */
function correoCuerpo(string $limite, string $html, string $texto): string
{
    $c  = "--$limite\r\n";
    $c .= "Content-Type: text/plain; charset=UTF-8\r\n";
    $c .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $c .= chunk_split(base64_encode($texto)) . "\r\n";

    $c .= "--$limite\r\n";
    $c .= "Content-Type: text/html; charset=UTF-8\r\n";
    $c .= "Content-Transfer-Encoding: base64\r\n\r\n";
    $c .= chunk_split(base64_encode($html)) . "\r\n";

    $c .= "--$limite--\r\n";

    return $c;
}

/** Envío con la función `mail()` de PHP. */
function mailPhpEnviar(string $para, string $asunto, string $html, string $texto): array
{
    $limite = 'lim' . bin2hex(random_bytes(8));

    $enviado = @mail(
        $para,
        // El asunto va codificado: sin esto, las tildes llegan rotas.
        '=?UTF-8?B?' . base64_encode($asunto) . '?=',
        correoCuerpo($limite, $html, $texto),
        implode("\r\n", correoCabeceras($limite))
    );

    return $enviado
        ? ['ok' => true, 'error' => null]
        : ['ok' => false, 'error' => 'La función mail() de PHP falló. '
                                   . 'En XAMPP normalmente no hay servidor de correo: usa SMTP.'];
}


// =====================================================================
//  CLIENTE SMTP
// =====================================================================

/**
 * Envía por SMTP hablando el protocolo a mano.
 *
 * Son unas pocas órdenes y evitan meter una dependencia entera en un
 * proyecto que no usa ninguna. Cubre lo que hace falta: STARTTLS, TLS
 * directo y autenticación LOGIN, que es lo que piden Gmail y casi todos
 * los correos de dominio.
 */
function smtpEnviar(string $para, string $asunto, string $html, string $texto): array
{
    $host      = correoHost();
    $puerto    = correoPuerto();
    $seguridad = correoSeguridad();

    $destino = $seguridad === 'ssl' ? "ssl://$host:$puerto" : "tcp://$host:$puerto";

    $contexto = stream_context_create(['ssl' => [
        'verify_peer'       => true,
        'verify_peer_name'  => true,
        'allow_self_signed' => false,
    ]]);

    $conexion = @stream_socket_client(
        $destino, $errNo, $errStr, 15, STREAM_CLIENT_CONNECT, $contexto
    );

    if (!$conexion) {
        return ['ok' => false, 'error' => "No se pudo conectar a $host:$puerto ($errStr)"];
    }

    stream_set_timeout($conexion, 20);

    /** Lee una respuesta, incluidas las de varias líneas. */
    $leer = static function () use ($conexion): string {
        $todo = '';

        while (($linea = fgets($conexion, 1024)) !== false) {
            $todo .= $linea;
            // «250-algo» continúa; «250 algo» termina.
            if (strlen($linea) < 4 || $linea[3] !== '-') {
                break;
            }
        }

        return $todo;
    };

    /** Manda una orden y devuelve la respuesta. */
    $decir = static function (string $orden) use ($conexion, $leer): string {
        fwrite($conexion, $orden . "\r\n");
        return $leer();
    };

    /** ¿La respuesta empieza por el código esperado? */
    $esperar = static fn(string $r, string $codigo): bool => str_starts_with(trim($r), $codigo);

    $fallo = static function (string $motivo) use ($conexion): array {
        @fclose($conexion);
        return ['ok' => false, 'error' => $motivo];
    };

    // ── Saludo ───────────────────────────────────────────────────────
    if (!$esperar($leer(), '220')) {
        return $fallo('El servidor no saludó como un SMTP.');
    }

    $yo = correoDominio();

    if (!$esperar($decir("EHLO $yo"), '250')) {
        return $fallo('El servidor rechazó el saludo (EHLO).');
    }

    // ── STARTTLS ─────────────────────────────────────────────────────
    if ($seguridad === 'tls') {
        if (!$esperar($decir('STARTTLS'), '220')) {
            return $fallo('El servidor no aceptó STARTTLS. Prueba con SSL directo (puerto 465).');
        }

        $cifrado = @stream_socket_enable_crypto(
            $conexion, true, STREAM_CRYPTO_METHOD_TLS_CLIENT
        );

        if ($cifrado !== true) {
            // Se corta en vez de seguir en claro: la contraseña del buzón
            // viaja en el paso siguiente.
            return $fallo('No se pudo cifrar la conexión con TLS.');
        }

        // Tras cifrar hay que volver a presentarse.
        if (!$esperar($decir("EHLO $yo"), '250')) {
            return $fallo('El servidor rechazó el saludo después de TLS.');
        }
    }

    // ── Autenticación ────────────────────────────────────────────────
    if (!$esperar($decir('AUTH LOGIN'), '334')) {
        return $fallo('El servidor no aceptó AUTH LOGIN.');
    }
    if (!$esperar($decir(base64_encode(correoUsuario())), '334')) {
        return $fallo('El servidor rechazó el usuario.');
    }
    if (!$esperar($decir(base64_encode(correoClave())), '235')) {
        // El motivo NO incluye la respuesta del servidor: algunos la
        // devuelven con la orden completa, y ahí va la clave en base64.
        return $fallo('Usuario o contraseña del buzón incorrectos. '
                    . 'Con Gmail hace falta una «contraseña de aplicación».');
    }

    // ── Sobre y contenido ────────────────────────────────────────────
    if (!$esperar($decir('MAIL FROM:<' . correoDesde() . '>'), '250')) {
        return $fallo('El servidor rechazó la dirección del remitente.');
    }
    if (!$esperar($decir('RCPT TO:<' . $para . '>'), '250')) {
        return $fallo('El servidor rechazó la dirección de destino.');
    }
    if (!$esperar($decir('DATA'), '354')) {
        return $fallo('El servidor no aceptó empezar el mensaje.');
    }

    $limite  = 'lim' . bin2hex(random_bytes(8));
    $mensaje = implode("\r\n", array_merge(
        ['To: ' . $para, 'Subject: =?UTF-8?B?' . base64_encode($asunto) . '?='],
        correoCabeceras($limite)
    )) . "\r\n\r\n" . correoCuerpo($limite, $html, $texto);

    /*
     * Un punto solo al principio de una línea termina el mensaje. Si el
     * contenido trae uno, hay que doblarlo o el correo se corta ahí.
     */
    $mensaje = preg_replace('/^\./m', '..', $mensaje) ?? $mensaje;

    fwrite($conexion, $mensaje . "\r\n.\r\n");

    if (!$esperar($leer(), '250')) {
        return $fallo('El servidor no aceptó el mensaje.');
    }

    $decir('QUIT');
    @fclose($conexion);

    return ['ok' => true, 'error' => null];
}


// =====================================================================
//  PLANTILLA
// =====================================================================

/**
 * Envuelve un mensaje en una plantilla sencilla.
 *
 * Sin imágenes externas y con los estilos en línea, que es lo único que
 * respetan los clientes de correo. Nada de tipografías descargadas: la
 * mitad de los lectores las bloquean y el resultado sería peor que usar
 * las de sistema desde el principio.
 */
function correoPlantilla(string $titulo, string $cuerpoHtml, ?array $boton = null): string
{
    $sitio = e((string) ajuste('sitio_nombre', 'Actividades en Línea'));

    $html  = '<div style="background:#f8fafc;padding:28px 12px;font-family:'
           . 'system-ui,-apple-system,Segoe UI,Roboto,sans-serif;color:#4a5568">';
    $html .= '<div style="max-width:520px;margin:0 auto;background:#ffffff;'
           . 'border:1px solid #e2e8f0;border-radius:16px;padding:30px 28px">';

    $html .= '<div style="font-size:1.15rem;font-weight:700;color:#2f3b52;'
           . 'margin-bottom:20px">🎓 ' . $sitio . '</div>';

    $html .= '<h1 style="font-size:1.3rem;color:#2f3b52;margin:0 0 14px">'
           . e($titulo) . '</h1>';

    $html .= '<div style="font-size:.97rem;line-height:1.65">' . $cuerpoHtml . '</div>';

    if ($boton !== null) {
        $html .= '<div style="margin:26px 0 8px">'
               . '<a href="' . e($boton['url']) . '" '
               . 'style="display:inline-block;background:#29b6f6;color:#ffffff;'
               . 'text-decoration:none;font-weight:600;padding:13px 26px;'
               . 'border-radius:50px">' . e($boton['texto']) . '</a></div>';

        // El enlace también en texto: si el botón no se ve —y a veces no
        // se ve— tiene que quedar otra forma de llegar.
        $html .= '<p style="font-size:.8rem;color:#8b95a5;line-height:1.5;'
               . 'word-break:break-all;margin-top:14px">'
               . 'Si el botón no funciona, copia esta dirección en tu navegador:<br>'
               . e($boton['url']) . '</p>';
    }

    $html .= '</div>';
    $html .= '<p style="text-align:center;font-size:.78rem;color:#8b95a5;margin-top:18px">'
           . 'Este mensaje se envió automáticamente. No hace falta responderlo.</p>';
    $html .= '</div>';

    return $html;
}
