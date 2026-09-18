<?php
/**
 * crear-admin.php — La primera cuenta de administrador
 *
 *     php database/crear-admin.php --correo=tu@correo.com --nombre="Tu Nombre"
 *     php database/crear-admin.php --correo=… --nombre=… --clave=…
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ HACE FALTA ESTO
 * ─────────────────────────────────────────────────────────────────────
 *
 * En XAMPP la primera cuenta la crea `instalar.php` por el navegador, y
 * el README manda borrarlo al terminar — con razón: un instalador que
 * sigue accesible es un formulario público que crea administradores.
 *
 * En producción eso deja un hueco: la base se importa desde un volcado
 * que NO trae usuarios (a propósito: llevaba 26 cuentas de prueba, varias
 * de menores), así que no hay con qué entrar al panel. Este script llena
 * ese hueco desde la línea de comandos, que es donde debe estar: para
 * ejecutarlo hay que tener acceso al servidor, no solo conocer una URL.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  NO TIENE CAMINO CORTO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Pasa por `crearUsuarioComoAdmin()`, que a su vez pasa por
 * `registrarUsuario()`. Podría insertar la fila directamente y sería diez
 * líneas, pero entonces se saltaría la validación del correo, el mínimo
 * de la contraseña, el hasheo con `password_hash()` y la entrega de los
 * personajes de bienvenida. Una cuenta creada por un atajo es una cuenta
 * distinta de las demás, y las diferencias se descubren tarde.
 */

declare(strict_types=1);

require_once dirname(__DIR__) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

if (PHP_SAPI !== 'cli') {
    http_response_code(403);
    exit("Este script solo se ejecuta desde la línea de comandos.\n");
}

/** El valor de un argumento `--clave=valor`. */
function argumento(string $clave, string $porDefecto = ''): string
{
    foreach ($GLOBALS['argv'] as $a) {
        if (str_starts_with((string) $a, "--$clave=")) {
            return trim(substr((string) $a, strlen($clave) + 3));
        }
    }

    return $porDefecto;
}

$correo = argumento('correo');
$nombre = argumento('nombre');
$clave  = argumento('clave');

if ($correo === '' || $nombre === '') {
    exit(
        "Uso:\n"
        . "  php database/crear-admin.php --correo=tu@correo.com --nombre=\"Tu Nombre\"\n\n"
        . "Sin --clave se genera una legible y se muestra una sola vez.\n"
    );
}

/*
 * Si ya hay administradores, se avisa y no se sigue sin `--otro`.
 *
 * El caso que esto evita no es crear dos cuentas: es ejecutar el script
 * dos veces por no recordar si la primera funcionó, y acabar con
 * administradores de sobra sin saber cuántos hay. En un sistema que
 * maneja datos de menores, «no sé cuánta gente tiene acceso total» es un
 * problema en sí mismo.
 */
$admins = (int) traerValor("SELECT COUNT(*) FROM users WHERE role = 'admin'");

if ($admins > 0 && !in_array('--otro', $argv, true)) {
    echo "Ya hay $admins cuenta(s) de administrador:\n\n";

    foreach (traerTodo("SELECT name, email, created_at FROM users WHERE role = 'admin' ORDER BY id") as $a) {
        printf("  %-28s %-34s %s\n", $a['name'], $a['email'], $a['created_at']);
    }

    exit("\nSi de verdad quieres otra, repite con --otro\n");
}

$generada = false;

if ($clave === '') {
    $clave    = contrasenaSugerida();
    $generada = true;
}

$r = crearUsuarioComoAdmin([
    'name'     => $nombre,
    'email'    => $correo,
    'password' => $clave,
    'role'     => 'admin',
    'status'   => 'active',
]);

if (!$r['ok']) {
    echo "✗ No se pudo crear la cuenta:\n\n";

    foreach ($r['errores'] as $campo => $error) {
        printf("  %-16s %s\n", is_string($campo) ? $campo : '', $error);
    }

    exit(1);
}

echo "\n✓ Administrador creado\n\n";
printf("  Nombre  : %s\n", $nombre);
printf("  Correo  : %s\n", $correo);

if ($generada) {
    printf("  Clave   : %s\n", $clave);
    echo "\n  ⚠ Esta clave no se vuelve a mostrar: solo se guarda su hash.\n";
    echo "    Cámbiala al entrar por una tuya.\n";
} else {
    echo "  Clave   : la que pasaste en --clave\n";
    echo "\n  ⚠ Queda en el historial del shell. Considera limpiarlo.\n";
}

echo "\n  Entra en " . URL_BASE . "/login.php\n\n";
