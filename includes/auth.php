<?php
/**
 * auth.php — Sistema de usuarios
 *
 * Registro, inicio y cierre de sesión, y consulta del usuario actual.
 *
 * Las contraseñas se guardan siempre con password_hash() (bcrypt) y se
 * comprueban con password_verify(). En ningún punto del sistema existe
 * la contraseña en texto plano ni de forma reversible: si alguien
 * obtuviera la base de datos, no obtendría las contraseñas.
 */

declare(strict_types=1);

// =====================================================================
//  ESTADO DE LA SESIÓN
// =====================================================================

/** ¿Hay alguien con sesión iniciada? */
function haySesion(): bool
{
    return !empty($_SESSION['usuario_id']);
}

/**
 * Devuelve el usuario actual desde la base de datos, o null.
 *
 * Se consulta la BD en lugar de confiar en lo guardado en la sesión:
 * si un administrador desactiva la cuenta o cambia el rol, el cambio
 * tiene efecto de inmediato y no cuando el usuario vuelva a entrar.
 */
function usuarioActual(): ?array
{
    static $usuario = null;
    static $consultado = false;

    if ($consultado) {
        return $usuario;
    }
    $consultado = true;

    if (!haySesion()) {
        return null;
    }

    $usuario = traerUno(
        'SELECT id, name, email, role, status, avatar, accessory, birth_year, created_at
           FROM users
          WHERE id = ?',
        [$_SESSION['usuario_id']]
    );

    // La cuenta ya no existe o fue desactivada: se cierra la sesión.
    if (!$usuario || $usuario['status'] !== 'active') {
        cerrarSesion();
        $usuario = null;
    }

    return $usuario;
}

/** Id del usuario actual, o null. */
function usuarioActualId(): ?int
{
    $u = usuarioActual();
    return $u ? (int) $u['id'] : null;
}

/** ¿El usuario actual tiene alguno de estos roles? */
function tieneRol(string ...$roles): bool
{
    $u = usuarioActual();
    return $u !== null && in_array($u['role'], $roles, true);
}

/** ¿Es administrador de la plataforma? */
function esAdmin(): bool
{
    return tieneRol('admin');
}


// =====================================================================
//  GUARDIANES DE PÁGINA
// =====================================================================

/**
 * Exige sesión iniciada. Si no la hay, manda a login recordando a dónde
 * quería ir el usuario, para devolverlo allí después de entrar.
 */
function exigirSesion(): void
{
    if (haySesion() && usuarioActual()) {
        return;
    }
    $_SESSION['destino_tras_login'] = $_SERVER['REQUEST_URI'] ?? '';
    mensaje('info', 'Inicia sesión para continuar.');
    redirigir('login.php');
}

/**
 * A dónde va alguien que acaba de entrar o de crear su cuenta.
 *
 * ---------------------------------------------------------------------
 *  POR QUÉ VIVE AQUÍ Y NO EN `login.php`
 * ---------------------------------------------------------------------
 *
 * Estaba dentro de `login.php`, así que `registro.php` no podía usarla y
 * se apañaba leyendo `destino_tras_login` en crudo. Eso daba un 404 justo
 * después de crear la cuenta, y solo por ese camino:
 *
 *     REQUEST_URI       /proyecto-final/planes/suscribir.php?plan=…
 *     redirigir() añade  http://localhost/proyecto-final
 *     resultado          …/proyecto-final/proyecto-final/planes/…
 *
 * Es decir: el peor sitio posible, porque lo sufría exactamente quien se
 * estaba registrando PARA PAGAR — y lo perdía en el último paso.
 *
 * Aquí la usan los dos y el arreglo no puede volver a desincronizarse.
 */
function destinoTrasLogin(): string
{
    $destino = (string) ($_SESSION['destino_tras_login'] ?? '');
    unset($_SESSION['destino_tras_login']);

    /*
     * Solo se acepta un destino DENTRO del sitio. Si llegara uno de otro
     * sitio, esto sería un salto abierto que sirve para llevar a alguien
     * recién autenticado a una página de phishing.
     */
    if ($destino === '' || preg_match('#^(https?:)?//#i', $destino)) {
        return 'index.php';
    }

    /*
     * `exigirSesion()` guarda `REQUEST_URI`, que viene con la carpeta de
     * la instalación incluida. `redirigir()` espera una ruta RELATIVA a
     * la base y le antepone `URL_BASE`, así que hay que quitarla.
     */
    $base = rtrim((string) parse_url(URL_BASE, PHP_URL_PATH), '/');

    if ($base !== '' && str_starts_with($destino, $base . '/')) {
        $destino = substr($destino, strlen($base));
    }

    return ltrim($destino, '/') !== '' ? ltrim($destino, '/') : 'index.php';
}

/**
 * Lo mismo, pero sin acabar nunca en una pantalla de EMPEZAR a comprar.
 *
 * ---------------------------------------------------------------------
 *  QUIEN NO PIDIÓ PAGAR NO DEBE ACABAR EN LA CAJA
 * ---------------------------------------------------------------------
 *
 * `destino_tras_login` recuerda dónde estaba alguien cuando le pedimos
 * la cuenta, y casi siempre eso es lo que quiere: la actividad que
 * intentaba jugar.
 *
 * Pero también recuerda `planes/suscribir.php` si se asomó a mirar el
 * precio. Y entonces pasa esto: se lo piensa mejor, se va por el menú a
 * crear una cuenta gratis, la crea… y aterriza en la pantalla de pago
 * que acababa de esquivar. Lo vivió como una trampa, y con razón.
 *
 * Así que ese destino concreto se descarta. La intención de comprar hoy
 * viaja en `?comprar=<plan>`, es explícita, y quien la trae no pasa por
 * aquí.
 *
 * `planes/pagar.php` NO se descarta: ese lleva una referencia de un pago
 * YA empezado, y devolver ahí a alguien que se quedó a medias es
 * ayudarle, no venderle.
 */
function destinoTrasLoginSinCompra(): string
{
    $destino = destinoTrasLogin();

    return str_starts_with($destino, 'planes/suscribir.php') ? 'usuario/' : $destino;
}

/** Exige uno de los roles indicados. */
function exigirRol(string ...$roles): void
{
    exigirSesion();

    if (!tieneRol(...$roles)) {
        http_response_code(403);
        exit('No tienes permiso para entrar a esta sección.');
    }
}


// =====================================================================
//  REGISTRO
// =====================================================================

/**
 * Crea una cuenta nueva.
 *
 * @return array{ok:bool, errores:array, usuario_id:?int}
 */
function registrarUsuario(string $nombre, string $correo, string $password, ?string $correoAcudiente = null, ?int $anioNacimiento = null): array
{
    $errores = [];

    $nombre = trim($nombre);
    if (mb_strlen($nombre) < 2 || mb_strlen($nombre) > 120) {
        $errores['name'] = 'Escribe tu nombre (entre 2 y 120 caracteres).';
    }

    $correoNormalizado = correoValido($correo);
    if ($correoNormalizado === null) {
        $errores['email'] = 'El correo no tiene un formato válido.';
    } elseif (traerValor('SELECT 1 FROM users WHERE email = ?', [$correoNormalizado])) {
        $errores['email'] = 'Ya existe una cuenta con este correo.';
    }

    if (mb_strlen($password) < 8) {
        $errores['password'] = 'La contraseña debe tener al menos 8 caracteres.';
    }

    // Si la cuenta es de un menor, se exige el correo del adulto
    // responsable (Decreto 0769 de 2026: tratamiento de datos de menores).
    $anioActual = (int) date('Y');
    if ($anioNacimiento !== null) {
        if ($anioNacimiento < $anioActual - 100 || $anioNacimiento > $anioActual) {
            $errores['birth_year'] = 'El año de nacimiento no es válido.';
        } elseif (($anioActual - $anioNacimiento) < 14) {
            $acudiente = $correoAcudiente ? correoValido($correoAcudiente) : null;
            if ($acudiente === null) {
                $errores['guardian_email'] = 'Para cuentas de menores de 14 años se necesita el correo de un adulto responsable.';
            }
        }
    }

    if ($errores) {
        return ['ok' => false, 'errores' => $errores, 'usuario_id' => null];
    }

    $id = insertar(
        'INSERT INTO users (name, email, password, role, status, birth_year, guardian_email)
         VALUES (?, ?, ?, ?, ?, ?, ?)',
        [
            $nombre,
            $correoNormalizado,
            password_hash($password, PASSWORD_DEFAULT),
            'user',
            'active',
            $anioNacimiento,
            $correoAcudiente ? correoValido($correoAcudiente) : null,
        ]
    );

    entregarBienvenida($id);

    return ['ok' => true, 'errores' => [], 'usuario_id' => $id];
}

/**
 * Le da al recién llegado sus personajes gratuitos y le pone uno.
 *
 * Sin esto, la tienda le ofrecía «canjear por 0 monedas» los cuatro
 * personajes de salida: un botón para comprar algo que ya debería ser
 * suyo. Y hasta pulsarlo aparecía sin cara en la cabecera.
 *
 * Va aquí y no en el sembrador porque el sembrador se ejecuta una vez:
 * repartía a los que ya existían y a nadie más.
 */
function entregarBienvenida(int $usuarioId): void
{
    // La tabla puede no existir todavía en una instalación a medio
    // actualizar; que falte no debe impedir crear la cuenta.
    try {
        ejecutar(
            'INSERT IGNORE INTO user_items (user_id, item_id, paid_coins)
             SELECT ?, id, 0 FROM shop_items
              WHERE price_coins = 0 AND needs_streak = 0 AND is_active = 1',
            [$usuarioId]
        );

        ejecutar("UPDATE users SET avatar = 'leon' WHERE id = ? AND (avatar IS NULL OR avatar = '')",
                 [$usuarioId]);
    } catch (Throwable $e) {
        // El proyecto registra los errores en almacen/logs/ a través de
        // la configuración de PHP; aquí basta con dejar constancia.
        error_log('No se pudo entregar la bienvenida al usuario ' . $usuarioId
                  . ': ' . $e->getMessage());
    }
}


// =====================================================================
//  INICIO Y CIERRE DE SESIÓN
// =====================================================================

/**
 * Verifica credenciales y abre la sesión.
 *
 * El mensaje de error es siempre el mismo tanto si el correo no existe
 * como si la contraseña es incorrecta: decir cuál de los dos falló
 * permitiría averiguar qué correos están registrados.
 *
 * @return array{ok:bool, error:?string}
 */
function iniciarSesion(string $correo, string $password): array
{
    $correoNormalizado = correoValido($correo);
    $generico = 'Correo o contraseña incorrectos.';

    if ($correoNormalizado === null) {
        return ['ok' => false, 'error' => $generico];
    }

    $usuario = traerUno(
        'SELECT id, password, status FROM users WHERE email = ?',
        [$correoNormalizado]
    );

    if (!$usuario || !password_verify($password, $usuario['password'])) {
        return ['ok' => false, 'error' => $generico];
    }

    if ($usuario['status'] !== 'active') {
        return ['ok' => false, 'error' => 'Esta cuenta está desactivada. Escríbenos para reactivarla.'];
    }

    // Si el algoritmo por defecto de PHP cambió (o subió el coste),
    // se regraba el hash aprovechando que aquí sí tenemos la contraseña.
    if (password_needs_rehash($usuario['password'], PASSWORD_DEFAULT)) {
        ejecutar('UPDATE users SET password = ? WHERE id = ?', [
            password_hash($password, PASSWORD_DEFAULT),
            $usuario['id'],
        ]);
    }

    /*
     * ─────────────────────────────────────────────────────────────────
     *  IDENTIFICADOR DE SESIÓN NUEVO, PERO SIN DESTRUIR EL ANTERIOR
     * ─────────────────────────────────────────────────────────────────
     *
     * Se regenera para evitar la fijación de sesión: un atacante que
     * imponga un id conocido antes de que el usuario entre no se queda
     * con la sesión ya autenticada.
     *
     * El `false` importa, y aquí está la causa de un fallo que se veía de
     * vez en cuando: **«el login da error y al refrescar resulta que sí
     * entró»**.
     *
     * Con `true`, PHP borra el archivo de la sesión anterior AL INSTANTE.
     * Si una segunda petición viene con la cookie vieja —el usuario pulsó
     * dos veces, la conexión iba lenta y volvió a darle, o tenía el login
     * abierto en otra pestaña— esa petición encuentra una sesión vacía,
     * se queda sin `csrf_token` y `exigirCsrf()` responde un 403 seco.
     * Mientras tanto la PRIMERA petición había entrado bien, así que al
     * refrescar el usuario aparece dentro. Exactamente el síntoma.
     *
     * Con `false`, el id viejo sigue existiendo unos minutos —hasta que
     * el recolector de PHP lo limpia— y esa segunda petición encuentra su
     * token y se comporta con normalidad.
     *
     * Y no debilita nada, por el ORDEN de estas dos líneas: la sesión
     * vieja se queda con los datos que había ANTES de poner
     * `usuario_id`, o sea, sin autenticar. Quien tuviera el id anterior
     * no hereda la sesión de nadie.
     */
    session_regenerate_id(false);

    $_SESSION['usuario_id'] = (int) $usuario['id'];

    ejecutar('UPDATE users SET last_login_at = NOW() WHERE id = ?', [$usuario['id']]);

    return ['ok' => true, 'error' => null];
}

/** Cierra la sesión y destruye la cookie. */
function cerrarSesion(): void
{
    $_SESSION = [];

    if (ini_get('session.use_cookies')) {
        $p = session_get_cookie_params();
        setcookie(session_name(), '', [
            'expires'  => time() - 42000,
            'path'     => $p['path'],
            'domain'   => $p['domain'],
            'secure'   => $p['secure'],
            'httponly' => $p['httponly'],
            'samesite' => $p['samesite'] ?? 'Lax',
        ]);
    }

    session_destroy();
}
