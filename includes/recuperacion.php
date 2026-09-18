<?php
/**
 * recuperacion.php — Recuperar la contraseña por correo
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS CUATRO REGLAS
 * ─────────────────────────────────────────────────────────────────────
 *
 * **1. El enlace se guarda cifrado, igual que una contraseña.**
 *
 * En la base solo queda el hash del testigo. Quien consiguiera leer la
 * tabla —un volcado filtrado, una inyección— no podría entrar en ninguna
 * cuenta con lo que hay ahí. Guardar el testigo en claro sería guardar
 * una llave maestra de todas las cuentas que estén recuperando.
 *
 * **2. No se dice nunca si un correo existe.**
 *
 * La pantalla responde lo mismo tanto si la cuenta existe como si no.
 * Distinguir los dos casos convierte el formulario en un comprobador de
 * direcciones: se prueban mil correos y se sabe cuáles están registrados,
 * que es justo lo que busca quien prepara un ataque dirigido.
 *
 * **3. El testigo caduca y se usa una sola vez.**
 *
 * Una hora. Y al usarlo se marca. Un enlace que sigue sirviendo después
 * de cambiar la contraseña es una segunda llave suelta, y los enlaces
 * quedan en el historial del navegador y en los buzones para siempre.
 *
 * **4. Cambiar la contraseña cierra las demás sesiones.**
 *
 * Quien recupera su cuenta suele hacerlo porque cree que alguien entró.
 * Si el intruso sigue con su sesión abierta, cambiar la contraseña no
 * sirve de nada. Ver `cerrarOtrasSesiones()`.
 */

declare(strict_types=1);

/** Cuánto vive un enlace de recuperación. */
const RECUPERACION_MINUTOS = 60;

/** Cuántas veces se puede pedir por correo y por hora. */
const RECUPERACION_MAX_POR_HORA = 5;


// =====================================================================
//  PEDIR
// =====================================================================

/**
 * Crea una petición de recuperación y manda el correo.
 *
 * Devuelve SIEMPRE la misma forma haya cuenta o no. Quien llama no
 * necesita saber si existía —y no debe—, solo si hubo un problema
 * técnico que merezca contarse.
 *
 * @return array{ok:bool, enviado:bool, error:?string}
 */
function pedirRecuperacion(string $correo): array
{
    $correo = correoValido($correo);

    if ($correo === null) {
        return ['ok' => false, 'enviado' => false, 'error' => 'Esa dirección no es válida.'];
    }

    $usuario = traerUno(
        'SELECT id, name, email, status FROM users WHERE email = ?',
        [$correo]
    );

    /*
     * Cuenta inexistente o desactivada: se responde «ok» sin enviar nada.
     * Es la regla 2. Cuesta al depurar y protege a los usuarios.
     */
    if (!$usuario || $usuario['status'] !== 'active') {
        return ['ok' => true, 'enviado' => false, 'error' => null];
    }

    // ── Límite de peticiones ─────────────────────────────────────────
    $recientes = (int) traerValor(
        'SELECT COUNT(*) FROM password_resets
          WHERE user_id = ? AND created_at > DATE_SUB(NOW(), INTERVAL 1 HOUR)',
        [(int) $usuario['id']]
    );

    if ($recientes >= RECUPERACION_MAX_POR_HORA) {
        /*
         * También se responde «ok». Decir «has pedido demasiados» a quien
         * escribe un correo ajeno le confirmaría que esa cuenta existe y
         * que alguien está intentando entrar en ella.
         */
        return ['ok' => true, 'enviado' => false, 'error' => null];
    }

    if (!correoConfigurado()) {
        return ['ok' => false, 'enviado' => false,
                'error' => 'El envío de correo no está configurado en el sitio.'];
    }

    // ── Crear el testigo ─────────────────────────────────────────────
    //
    // 32 bytes de azar criptográfico. En la base va solo su hash.
    $testigo = bin2hex(random_bytes(32));

    insertar(
        'INSERT INTO password_resets (user_id, token_hash, expires_at, requested_ip)
         VALUES (?, ?, DATE_ADD(NOW(), INTERVAL ? MINUTE), ?)',
        [
            (int) $usuario['id'],
            hash('sha256', $testigo),
            RECUPERACION_MINUTOS,
            mb_substr((string) ($_SERVER['REMOTE_ADDR'] ?? ''), 0, 45),
        ]
    );

    // ── Enviar ───────────────────────────────────────────────────────
    $enlace = url('restablecer.php?t=' . urlencode($testigo));

    $cuerpo  = '<p>Hola, ' . e($usuario['name']) . '.</p>';
    $cuerpo .= '<p>Alguien pidió recuperar la contraseña de esta cuenta. '
             . 'Si fuiste tú, pulsa el botón y elige una nueva.</p>';
    $cuerpo .= '<p>El enlace sirve <strong>una sola vez</strong> y caduca en '
             . RECUPERACION_MINUTOS . ' minutos.</p>';

    $resultado = enviarCorreo(
        (string) $usuario['email'],
        'Recupera tu contraseña',
        correoPlantilla('Recupera tu contraseña', $cuerpo,
            ['url' => $enlace, 'texto' => 'Elegir una contraseña nueva'])
        . '<div style="max-width:520px;margin:14px auto 0;font-size:.82rem;'
        . 'color:#8b95a5;text-align:center">Si no pediste esto, puedes ignorar '
        . 'este mensaje: tu contraseña no cambia hasta que uses el enlace.</div>'
    );

    if (!$resultado['ok']) {
        // Se deja constancia del motivo real, que es lo que hace falta
        // para arreglarlo, sin enseñárselo a quien está en la pantalla.
        error_log('[recuperacion] no se pudo enviar a la cuenta '
                . $usuario['id'] . ': ' . $resultado['error']);

        return ['ok' => false, 'enviado' => false,
                'error' => 'No pudimos enviar el correo. Inténtalo en unos minutos.'];
    }

    return ['ok' => true, 'enviado' => true, 'error' => null];
}


// =====================================================================
//  USAR
// =====================================================================

/**
 * Busca la petición viva que corresponde a un testigo.
 *
 * Devuelve null si no existe, si caducó o si ya se usó. Los tres casos
 * se tratan igual a propósito: al usuario le da lo mismo —el enlace no
 * sirve— y distinguirlos filtraría información sobre testigos ajenos.
 */
function recuperacionPorTestigo(string $testigo): ?array
{
    $testigo = trim($testigo);

    if ($testigo === '' || !ctype_xdigit($testigo)) {
        return null;
    }

    return traerUno(
        'SELECT r.*, u.name, u.email, u.status
           FROM password_resets r
           JOIN users u ON u.id = r.user_id
          WHERE r.token_hash = ?
            AND r.used_at IS NULL
            AND r.expires_at > NOW()
            AND u.status = "active"',
        [hash('sha256', $testigo)]
    );
}

/**
 * Consume un testigo y cambia la contraseña.
 *
 * @return array{ok:bool, error:?string, usuario:?array}
 */
function usarRecuperacion(string $testigo, string $nueva): array
{
    $peticion = recuperacionPorTestigo($testigo);

    if (!$peticion) {
        return ['ok' => false, 'usuario' => null,
                'error' => 'Ese enlace ya no sirve. Pide uno nuevo.'];
    }

    $problema = problemaDeContrasena($nueva);

    if ($problema !== null) {
        return ['ok' => false, 'usuario' => null, 'error' => $problema];
    }

    $pdo    = db();
    $propia = !$pdo->inTransaction();

    if ($propia) {
        $pdo->beginTransaction();
    }

    try {
        /*
         * Se marca como usado CONDICIONANDO a que siga sin usar. Si dos
         * peticiones llegan a la vez —el usuario pulsa dos veces, o el
         * navegador reintenta— solo una de las dos actualiza una fila, y
         * la otra se encuentra con cero y se detiene.
         */
        $marcadas = ejecutar(
            'UPDATE password_resets SET used_at = NOW()
              WHERE id = ? AND used_at IS NULL',
            [(int) $peticion['id']]
        );

        if ($marcadas === 0) {
            if ($propia) { $pdo->rollBack(); }
            return ['ok' => false, 'usuario' => null,
                    'error' => 'Ese enlace ya se usó. Pide uno nuevo.'];
        }

        ejecutar(
            'UPDATE users SET password = ? WHERE id = ?',
            [password_hash($nueva, PASSWORD_DEFAULT), (int) $peticion['user_id']]
        );

        // Las demás peticiones vivas de esta cuenta dejan de servir: si
        // alguien pidió varios enlaces, los sobrantes son llaves sueltas.
        ejecutar(
            'UPDATE password_resets SET used_at = NOW()
              WHERE user_id = ? AND used_at IS NULL',
            [(int) $peticion['user_id']]
        );

        if ($propia) {
            $pdo->commit();
        }

    } catch (Throwable $e) {
        if ($propia && $pdo->inTransaction()) {
            $pdo->rollBack();
        }
        error_log('[recuperacion] ' . $e->getMessage());
        return ['ok' => false, 'usuario' => null,
                'error' => 'No se pudo cambiar la contraseña. Inténtalo otra vez.'];
    }

    return ['ok' => true, 'error' => null, 'usuario' => [
        'id'    => (int) $peticion['user_id'],
        'name'  => (string) $peticion['name'],
        'email' => (string) $peticion['email'],
    ]];
}

/**
 * Qué le pasa a una contraseña, o null si está bien.
 *
 * Se pide longitud y nada más. Obligar a mayúsculas, números y símbolos
 * produce «Password1!» en todas partes: reglas que se cumplen con el
 * mínimo esfuerzo y una contraseña que nadie recuerda. Ocho caracteres y
 * que no sea de las cuatro obvias es más honesto.
 */
function problemaDeContrasena(string $clave): ?string
{
    if (mb_strlen($clave) < 8) {
        return 'La contraseña necesita al menos 8 caracteres.';
    }
    if (mb_strlen($clave) > 200) {
        return 'Esa contraseña es demasiado larga.';
    }

    $obvias = ['12345678', 'contraseña', 'password', 'qwertyui', '11111111', 'abcd1234'];

    if (in_array(mb_strtolower(trim($clave)), $obvias, true)) {
        return 'Esa contraseña es de las más usadas del mundo. Elige otra.';
    }

    return null;
}

/** Limpia peticiones viejas. Lo llama el script de mantenimiento. */
function limpiarRecuperaciones(int $dias = 7): int
{
    return ejecutar(
        'DELETE FROM password_resets WHERE created_at < DATE_SUB(NOW(), INTERVAL ? DAY)',
        [max(1, $dias)]
    );
}
