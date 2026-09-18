<?php
/**
 * aula.php — Entrar por código de clase
 *
 * El docente escribe una dirección corta en el tablero, el niño la abre,
 * ve la lista de su clase con las caras de todos, toca su nombre y entra.
 * Sin correo y sin contraseña larga.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE ESTO EXPONE, DICHO SIN RODEOS
 * ─────────────────────────────────────────────────────────────────────
 *
 * **La lista de nombres de la clase queda detrás de un código y nada
 * más.** Quien tenga el código ve quiénes son; y si el curso no exige
 * PIN, puede entrar como cualquiera de ellos.
 *
 * No es un descuido, es el trato: la alternativa es que treinta niños de
 * primero no puedan entrar solos, y entonces la plataforma no se usa. Lo
 * que sí se puede hacer es que ese trato se acepte a conciencia:
 *
 *   · Apagado por defecto en cada curso.
 *   · El curso decide si pide PIN de cuatro cifras.
 *   · El código se puede cambiar, y cambiarlo invalida el anterior.
 *   · Los intentos con código o PIN equivocado se frenan.
 *   · Una sesión abierta así **no puede pagar ni ver facturación**.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ EL CÓDIGO NO LLEVA NI O NI CERO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Lo va a copiar del tablero un niño de seis años. La O y el 0, la I y
 * el 1 se confunden hasta escritos a mano por un adulto. Con 32 letras
 * sin ambigüedad y seis posiciones salen mil millones de combinaciones,
 * de sobra teniendo el freno de intentos.
 */

declare(strict_types=1);

/** Letras y cifras que no se confunden entre sí. */
const AULA_ALFABETO = 'ABCDEFGHJKLMNPQRSTUVWXYZ23456789';

/** Longitud del código de clase. */
const AULA_LARGO = 6;

/** Intentos fallidos que se toleran, por sesión y por franja. */
const AULA_MAX_INTENTOS = 12;

/** Minutos que dura el castigo tras pasarse. */
const AULA_ESPERA_MINUTOS = 10;


// =====================================================================
//  ¿ESTÁ INSTALADO?
// =====================================================================

/**
 * ¿Existen las columnas del aula?
 *
 * Permite desplegar el código antes que la migración sin que nada
 * reviente: sin columnas, el aula sencillamente no se ofrece.
 */
function aulaInstalada(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "courses"
                AND COLUMN_NAME = "access_code"'
        );
    }

    return $listo;
}


// =====================================================================
//  EL CÓDIGO
// =====================================================================

/** Un código nuevo, comprobando que no exista ya. */
function generarCodigoAula(): string
{
    for ($intento = 0; $intento < 20; $intento++) {

        $codigo = '';
        for ($i = 0; $i < AULA_LARGO; $i++) {
            $codigo .= AULA_ALFABETO[random_int(0, strlen(AULA_ALFABETO) - 1)];
        }

        if (!traerValor('SELECT id FROM courses WHERE access_code = ?', [$codigo])) {
            return $codigo;
        }
    }

    // Veinte colisiones seguidas no pasan por azar. Se alarga antes que
    // devolver uno repetido, que mandaría a los niños a otra clase.
    return $codigo . strtoupper(bin2hex(random_bytes(2)));
}

/**
 * El código del curso, creándolo si aún no tiene.
 *
 * Se crea al pedirlo y no al crear el curso porque la inmensa mayoría de
 * los cursos no usarán el aula: generar códigos para todos llenaría el
 * espacio de códigos sin motivo.
 */
function codigoDeCurso(int $cursoId): ?string
{
    if (!aulaInstalada()) {
        return null;
    }

    $codigo = traerValor('SELECT access_code FROM courses WHERE id = ?', [$cursoId]);

    if ($codigo !== null && $codigo !== '') {
        return (string) $codigo;
    }

    $nuevo = generarCodigoAula();
    ejecutar('UPDATE courses SET access_code = ? WHERE id = ?', [$nuevo, $cursoId]);

    return $nuevo;
}

/**
 * Cambia el código. El anterior deja de servir al instante.
 *
 * Es lo que se hace cuando el código se filtró: se cambia, se escribe el
 * nuevo en el tablero y quien tuviera el viejo se queda fuera.
 */
function cambiarCodigoDeCurso(int $cursoId): string
{
    $nuevo = generarCodigoAula();
    ejecutar('UPDATE courses SET access_code = ? WHERE id = ?', [$nuevo, $cursoId]);

    return $nuevo;
}

/** Normaliza lo que teclea alguien: mayúsculas y sin espacios ni guiones. */
function limpiarCodigo(string $codigo): string
{
    $c = strtoupper(trim($codigo));
    $c = preg_replace('/[^A-Z0-9]/', '', $c) ?? '';

    return mb_substr($c, 0, 16);
}

// =====================================================================
//  CÓMO ENTRAN: UNA SOLA DECISIÓN
// =====================================================================

/**
 * Modo de acceso del curso: 'cuenta', 'lista' o 'ambos'.
 *
 * Sustituye a las dos casillas sueltas de la primera versión —«permitir
 * el código» y «pedir PIN»— con las que el docente tenía que deducir cómo
 * iba a entrar su clase. Hay que decidir una cosa, no combinar dos.
 */
function modoAcceso(array $curso): string
{
    $m = (string) ($curso['acceso_modo'] ?? 'cuenta');

    return in_array($m, ['cuenta', 'lista', 'ambos'], true) ? $m : 'cuenta';
}

/** ¿Este curso admite entrar tocando el nombre en la lista? */
function usaLista(array $curso): bool
{
    return in_array(modoAcceso($curso), ['lista', 'ambos'], true);
}

/**
 * ¿La clase está abierta ahora mismo?
 *
 * `clase_abierta_hasta` en NULL significa siempre abierta. Con una fecha,
 * la lista deja de abrir a esa hora.
 *
 * Esto es lo que responde de verdad al «que sirva en el salón pero en
 * casa tengan que entrar con su cuenta»: **no se puede saber desde el
 * servidor si alguien está en el aula o en su casa** —la IP falla en
 * cuanto hay datos móviles, la hora falla con la jornada de la tarde— así
 * que en vez de adivinarlo, lo decide quien da la clase.
 */
function claseAbierta(array $curso): bool
{
    if (!usaLista($curso)) {
        return false;
    }

    $hasta = $curso['clase_abierta_hasta'] ?? null;

    if ($hasta === null || $hasta === '') {
        return true;
    }

    return strtotime((string) $hasta) > time();
}

/** Abre la clase durante unas horas. `null` = sin límite. */
function abrirClase(int $cursoId, ?int $horas): void
{
    if ($horas === null) {
        ejecutar('UPDATE courses SET clase_abierta_hasta = NULL WHERE id = ?', [$cursoId]);
        return;
    }

    $horas = max(1, min(24, $horas));

    ejecutar('UPDATE courses SET clase_abierta_hasta = DATE_ADD(NOW(), INTERVAL ? HOUR)
               WHERE id = ?', [$horas, $cursoId]);
}

/**
 * Cierra la clase ya.
 *
 * Se pone la hora en el pasado en vez de dejarla en NULL: NULL significa
 * «siempre abierta», que es justo lo contrario.
 */
function cerrarClase(int $cursoId): void
{
    ejecutar('UPDATE courses SET clase_abierta_hasta = DATE_SUB(NOW(), INTERVAL 1 MINUTE)
               WHERE id = ?', [$cursoId]);
}

/** Cuánto le queda abierta, en texto. */
function tiempoDeClase(array $curso): string
{
    $hasta = $curso['clase_abierta_hasta'] ?? null;

    if ($hasta === null || $hasta === '') {
        return 'sin límite de hora';
    }

    $faltan = strtotime((string) $hasta) - time();

    if ($faltan <= 0) {
        return 'cerrada';
    }
    if ($faltan < 3600) {
        return 'cierra en ' . max(1, (int) round($faltan / 60)) . ' min';
    }

    return 'cierra a las ' . date('H:i', strtotime((string) $hasta));
}

/**
 * El curso de un código, si la lista está permitida Y la clase abierta.
 *
 * Un curso archivado tampoco abre: al terminar el año, el código escrito
 * en un cuaderno deja de dar acceso a la clase.
 */
function cursoPorCodigo(string $codigo): ?array
{
    if (!aulaInstalada()) {
        return null;
    }

    $codigo = limpiarCodigo($codigo);

    if ($codigo === '') {
        return null;
    }

    $curso = traerUno(
        'SELECT c.*, u.name AS docente
           FROM courses c
      LEFT JOIN users u ON u.id = c.teacher_id
          WHERE c.access_code = ? AND c.status = "active"',
        [$codigo]
    );

    if (!$curso || !usaLista($curso) || !claseAbierta($curso)) {
        return null;
    }

    return $curso;
}

/**
 * Igual que la anterior pero distinguiendo por qué no abre.
 *
 * Solo para la pantalla del niño: si la clase está cerrada conviene
 * decírselo —«tu profe abrirá la clase»— en vez de soltarle que el código
 * no existe, que le haría teclearlo diez veces creyendo que se equivocó.
 *
 * @return array{curso:?array, motivo:string}  motivo: ok|desconocido|cerrada
 */
function abrirPorCodigo(string $codigo): array
{
    if (!aulaInstalada()) {
        return ['curso' => null, 'motivo' => 'desconocido'];
    }

    $codigo = limpiarCodigo($codigo);

    $curso = $codigo === '' ? null : traerUno(
        'SELECT c.*, u.name AS docente
           FROM courses c
      LEFT JOIN users u ON u.id = c.teacher_id
          WHERE c.access_code = ? AND c.status = "active"',
        [$codigo]
    );

    if (!$curso || !usaLista($curso)) {
        return ['curso' => null, 'motivo' => 'desconocido'];
    }

    if (!claseAbierta($curso)) {
        return ['curso' => null, 'motivo' => 'cerrada'];
    }

    return ['curso' => $curso, 'motivo' => 'ok'];
}


// =====================================================================
//  FRENO DE INTENTOS
// =====================================================================

/**
 * ¿Hay que esperar antes de volver a probar?
 *
 * El freno vive en la sesión, no en la base. Es un freno honesto —quien
 * borre las cookies vuelve a tener intentos— y suficiente contra lo que
 * de verdad pasa: alguien probando códigos a mano en un navegador. Un
 * freno de verdad contra un script necesitaría guardar direcciones IP de
 * visitantes, y eso es un dato personal que no quiero recoger para esto.
 */
function aulaFrenada(): bool
{
    $hasta = (int) ($_SESSION['aula_espera_hasta'] ?? 0);

    return $hasta > time();
}

/** Minutos que faltan para poder reintentar. */
function aulaEsperaMinutos(): int
{
    $hasta = (int) ($_SESSION['aula_espera_hasta'] ?? 0);

    return max(0, (int) ceil(($hasta - time()) / 60));
}

/** Anota un fallo y frena si ya van demasiados. */
function aulaFallo(): void
{
    $n = (int) ($_SESSION['aula_fallos'] ?? 0) + 1;
    $_SESSION['aula_fallos'] = $n;

    if ($n >= AULA_MAX_INTENTOS) {
        $_SESSION['aula_espera_hasta'] = time() + AULA_ESPERA_MINUTOS * 60;
        $_SESSION['aula_fallos']       = 0;
    }
}

/** Todo bien: se olvidan los fallos. */
function aulaAcierto(): void
{
    unset($_SESSION['aula_fallos'], $_SESSION['aula_espera_hasta']);
}


// =====================================================================
//  LA LISTA DE LA CLASE
// =====================================================================

/**
 * Los niños de la clase, para pintar la lista.
 *
 * Devuelve lo justo para reconocerse: nombre y avatar. Ni correo, ni
 * progreso, ni fecha de nacimiento. Esta lista se sirve a quien tenga el
 * código, así que lo que no haga falta para tocar tu propio nombre no
 * tiene por qué estar aquí.
 */
function rosterDeAula(int $cursoId): array
{
    return traerTodo(
        'SELECT u.id, u.name, u.avatar, u.accessory,
                (u.pin_hash IS NOT NULL) AS tiene_pin
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
          WHERE cs.course_id = ? AND u.status = "active"
       ORDER BY u.name',
        [$cursoId]
    );
}


// =====================================================================
//  ENTRAR
// =====================================================================

/**
 * Abre sesión como un estudiante del curso.
 *
 * @return array{ok:bool, error:?string, pide_pin:bool}
 */
function entrarPorAula(array $curso, int $usuarioId, string $pin = ''): array
{
    $cursoId = (int) $curso['id'];

    if (aulaFrenada()) {
        return ['ok' => false, 'pide_pin' => false,
                'error' => 'Demasiados intentos. Espera '
                         . aulaEsperaMinutos() . ' minuto(s) y vuelve a probar.'];
    }

    /*
     * Que el niño esté EN ESTE CURSO se comprueba aquí, no antes: el id
     * llega de un formulario. Sin esto, cambiar el número entraría en la
     * cuenta de cualquiera de la plataforma.
     */
    $alumno = traerUno(
        'SELECT u.id, u.name, u.status, u.pin_hash
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
          WHERE cs.course_id = ? AND cs.user_id = ? AND u.status = "active"',
        [$cursoId, $usuarioId]
    );

    if (!$alumno) {
        aulaFallo();
        return ['ok' => false, 'pide_pin' => false,
                'error' => 'Ese nombre no está en esta clase.'];
    }

    // ── PIN, si el curso lo pide ─────────────────────────────────────
    if ((int) ($curso['aula_pin'] ?? 0) === 1) {

        if ($alumno['pin_hash'] === null) {
            /*
             * El curso pide PIN pero este niño no tiene. No se le deja
             * pasar sin más —sería un agujero— y tampoco se le culpa a
             * él: es algo que tiene que arreglar el docente.
             */
            return ['ok' => false, 'pide_pin' => false,
                    'error' => 'Todavía no tienes PIN. Pídeselo a tu profe.'];
        }

        if ($pin === '') {
            return ['ok' => false, 'pide_pin' => true, 'error' => null];
        }

        if (!password_verify($pin, (string) $alumno['pin_hash'])) {
            aulaFallo();
            return ['ok' => false, 'pide_pin' => true, 'error' => 'Ese PIN no es.'];
        }
    }

    // ── Adentro ──────────────────────────────────────────────────────
    aulaAcierto();

    // Sesión nueva: sin esto, quien usara el mismo navegador antes podría
    // conservar su identidad anterior.
    session_regenerate_id(true);

    $_SESSION['usuario_id'] = (int) $alumno['id'];

    /*
     * La marca que distingue esta sesión de una normal. Con ella se
     * bloquea pagar y ver facturación: un niño que toca su nombre en una
     * lista no debe poder contratar nada.
     */
    $_SESSION['via_aula']    = true;
    $_SESSION['aula_curso']  = $cursoId;

    ejecutar('UPDATE users SET last_login_at = NOW() WHERE id = ?', [(int) $alumno['id']]);

    return ['ok' => true, 'error' => null, 'pide_pin' => false];
}

/** ¿La sesión actual se abrió tocando un nombre en la lista de clase? */
function esSesionDeAula(): bool
{
    return !empty($_SESSION['via_aula']);
}

/**
 * Corta si la sesión es de aula.
 *
 * Se llama desde las páginas que mueven dinero o enseñan datos de
 * facturación. El mensaje explica qué hacer en vez de dar un portazo:
 * quien esté delante puede ser una familia usando el equipo del colegio.
 */
function exigirSesionPlena(): void
{
    if (esSesionDeAula()) {
        mensaje('info',
            'Entraste desde la lista de tu clase, y desde ahí no se puede acceder a '
            . 'los datos de pago. Si eres la familia, entra con tu correo y contraseña.');
        redirigir('usuario/');
    }

    /*
     * El modo niño cuenta como sesión no plena, y por la misma razón.
     *
     * Quien está delante de una cuenta en modo niño es un niño con la
     * tableta de su papá: la cuenta está suscrita y con sus datos de pago
     * a mano. Se comprueba AQUÍ y no en cada página porque estas cuatro
     * —suscribir, pagar, el retorno de la pasarela y la facturación— ya
     * llamaban a esta función. Repetir la comprobación en cada una serían
     * cuatro sitios donde olvidarse de uno.
     */
    if (function_exists('enModoNino') && enModoNino()) {
        mensaje('info',
            'La cuenta está en modo niño y desde ahí no se puede pagar ni ver la '
            . 'facturación. Sal del modo con tu PIN y vuelve a entrar aquí.');
        redirigir('usuario/modo-nino.php');
    }
}


// =====================================================================
//  PIN
// =====================================================================

/** ¿El PIN tiene la forma que debe? */
function pinValido(string $pin): bool
{
    return (bool) preg_match('/^\d{4}$/', $pin);
}

/**
 * Un PIN de cuatro cifras, evitando los que nadie debería tener.
 *
 * Se descartan 0000, 1234 y las cuatro cifras iguales. No porque un
 * atacante los pruebe primero —con el freno de intentos daría igual—
 * sino porque si el generador saca «1234» el docente pensará, con razón,
 * que el sistema no se está tomando en serio.
 */
function generarPin(): string
{
    $malos = ['0000', '1234', '1111', '2222', '3333', '4444',
              '5555', '6666', '7777', '8888', '9999', '4321'];

    do {
        $pin = str_pad((string) random_int(0, 9999), 4, '0', STR_PAD_LEFT);
    } while (in_array($pin, $malos, true));

    return $pin;
}

/**
 * Pone un PIN a un estudiante DEL CURSO.
 *
 * Igual que al restablecer contraseñas: solo sobre cuentas de rol
 * `student` que estén en el curso. A una cuenta familiar no se le toca
 * nada — quien la usa es un adulto ajeno a esta clase.
 *
 * @return array{ok:bool, error:?string, pin:?string}
 */
function ponerPinDeEstudiante(int $cursoId, int $usuarioId, string $pin = ''): array
{
    $alumno = traerUno(
        'SELECT u.id, u.name, u.role
           FROM course_students cs
           JOIN users u ON u.id = cs.user_id
          WHERE cs.course_id = ? AND cs.user_id = ?',
        [$cursoId, $usuarioId]
    );

    if (!$alumno) {
        return ['ok' => false, 'error' => 'Ese estudiante no está en este curso.', 'pin' => null];
    }

    if ($alumno['role'] !== 'student') {
        return ['ok' => false, 'pin' => null,
                'error' => 'Esa es una cuenta familiar: entra con su correo y contraseña, '
                         . 'no con PIN.'];
    }

    $pin = $pin !== '' ? $pin : generarPin();

    if (!pinValido($pin)) {
        return ['ok' => false, 'error' => 'El PIN tiene que ser de cuatro cifras.', 'pin' => null];
    }

    ejecutar('UPDATE users SET pin_hash = ? WHERE id = ?',
             [password_hash($pin, PASSWORD_DEFAULT), $usuarioId]);

    return ['ok' => true, 'error' => null, 'pin' => $pin];
}

/**
 * Genera PIN para todos los estudiantes del curso que no tengan.
 *
 * No se le cambia el PIN a quien ya tiene uno: se lo sabe de memoria y
 * cambiárselo sin motivo es dejarlo fuera el lunes por la mañana.
 *
 * @return array Lista de ['nombre' => …, 'pin' => …]
 */
function generarPinesDelCurso(int $cursoId, bool $soloSinPin = true): array
{
    $sql = 'SELECT u.id, u.name
              FROM course_students cs
              JOIN users u ON u.id = cs.user_id
             WHERE cs.course_id = ? AND u.role = "student"';

    if ($soloSinPin) {
        $sql .= ' AND u.pin_hash IS NULL';
    }

    $sql .= ' ORDER BY u.name';

    $hechos = [];

    foreach (traerTodo($sql, [$cursoId]) as $a) {
        $r = ponerPinDeEstudiante($cursoId, (int) $a['id']);

        if ($r['ok']) {
            $hechos[] = ['nombre' => $a['name'], 'pin' => $r['pin']];
        }
    }

    return $hechos;
}

/** Dirección corta que el docente escribe en el tablero. */
function urlDeAula(string $codigo): string
{
    return url('aula/' . rawurlencode($codigo));
}
