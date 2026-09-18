<?php
/**
 * ruta.php — La ruta de aprendizaje del estudiante
 *
 * Un niño que entra por su clase no debe ver el catálogo entero. Debe ver
 * **lo que su docente le mandó, en el orden en que se lo mandó**, y una
 * sola cosa que hacer a continuación.
 *
 * Hasta ahora no era así: un estudiante de un colegio con licencia recibe
 * `catalog_access = 'full'` —que es lo correcto para que no se encuentre
 * media actividad con candado— y con eso se le abrían las 384 actividades
 * del catálogo. Tenía acceso a todo y ninguna indicación de por dónde
 * empezar.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA RUTA NUNCA ABRE NADA: SOLO CIERRA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Es la decisión que sostiene este archivo. La ruta se aplica ANTES del
 * cálculo normal de acceso y solo puede devolver «esto no». Lo que sí se
 * puede jugar lo sigue decidiendo `acceso.php` con el plan de siempre.
 *
 * Si la ruta pudiera conceder, un docente sin licencia asignaría
 * cualquier actividad premium a su curso y estaría repartiendo la
 * biblioteca completa gratis. Restringir y conceder son dos poderes muy
 * distintos, y este archivo solo tiene el primero.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  A QUIÉN SE LE APLICA
 * ─────────────────────────────────────────────────────────────────────
 *
 * A las cuentas con rol `student` —las que crean un docente o un colegio
 * por nombre— que estén matriculadas en algún curso activo con
 * actividades asignadas.
 *
 * **No** a una familia que paga (`user`) aunque su hijo esté además en la
 * clase de alguien: ese niño tiene su propia vida en la plataforma y su
 * familia pagó por el catálogo entero. La distinción por rol ya existía
 * en el proyecto y es justo la que hace `restablecerClaveDeEstudiante()`
 * cuando se niega a tocar una cuenta de familia.
 *
 * Y tampoco a un estudiante que aún no tiene nada asignado: en vez de
 * dejarlo encerrado en una ruta vacía, se le deja el catálogo con su plan
 * de siempre hasta que su docente le mande algo.
 */

declare(strict_types=1);


/** ¿Está instalada la columna del modo de ruta? */
function rutaInstalada(): bool
{
    static $listo = null;

    if ($listo === null) {
        $listo = (bool) traerValor(
            'SELECT COUNT(*) FROM information_schema.COLUMNS
              WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = "courses"
                AND COLUMN_NAME = "ruta_modo"'
        );
    }

    return $listo;
}

/** El modo de un curso, tolerando instalaciones sin migrar. */
function modoDeRuta(array $curso): string
{
    return ($curso['ruta_modo'] ?? 'secuencial') === 'libre' ? 'libre' : 'secuencial';
}


// =====================================================================
//  QUÉ CURSO MANDA
// =====================================================================

/** Los cursos activos de un estudiante que ya tienen actividades. */
function cursosDelEstudiante(int $usuarioId): array
{
    if ($usuarioId <= 0) {
        return [];
    }

    return traerTodo(
        'SELECT c.*, u.name AS docente,
                (SELECT COUNT(*) FROM course_activities ca WHERE ca.course_id = c.id) AS actividades
           FROM course_students cs
           JOIN courses c   ON c.id = cs.course_id AND c.status = "active"
      LEFT JOIN users   u   ON u.id = c.teacher_id
          WHERE cs.user_id = ?
       ORDER BY cs.enrolled_at DESC, c.id DESC',
        [$usuarioId]
    );
}

/**
 * El curso cuya ruta se está siguiendo ahora mismo.
 *
 * Misma regla que `cursoDeTrabajo()`, y por el mismo motivo: si entró por
 * la lista de su clase gana ese curso —está en clase, con su profe
 * delante—; si no, el último en el que lo matricularon.
 *
 * `aula_curso` lo pone el servidor al entrar por la lista, no el
 * navegador, y aun así se vuelve a comprobar contra la matrícula.
 */
function cursoDeRuta(?int $usuarioId = null): ?array
{
    $usuarioId ??= usuarioActualId();

    if ($usuarioId === null) {
        return null;
    }

    $cache = &cacheDeRuta();

    if (array_key_exists($usuarioId, $cache['curso'])) {
        return $cache['curso'][$usuarioId];
    }

    $cursos = array_values(array_filter(
        cursosDelEstudiante($usuarioId),
        static fn(array $c): bool => (int) $c['actividades'] > 0
    ));

    if (!$cursos) {
        return $cache['curso'][$usuarioId] = null;
    }

    $deAula = (int) ($_SESSION['aula_curso'] ?? 0);

    if ($deAula > 0) {
        foreach ($cursos as $c) {
            if ((int) $c['id'] === $deAula) {
                return $cache['curso'][$usuarioId] = $c;
            }
        }
    }

    return $cache['curso'][$usuarioId] = $cursos[0];
}

/**
 * ¿Este usuario vive dentro de una ruta guiada?
 *
 * Rol `student` + un curso con actividades. El administrador nunca, para
 * poder revisar el contenido.
 */
function enRutaGuiada(?int $usuarioId = null): bool
{
    if ($usuarioId === null) {
        if (esAdmin() || !tieneRol('student')) {
            return false;
        }
    } else {
        $rol = traerValor('SELECT role FROM users WHERE id = ?', [$usuarioId]);

        if ($rol !== 'student') {
            return false;
        }
    }

    return cursoDeRuta($usuarioId) !== null;
}


// =====================================================================
//  LA RUTA
// =====================================================================

/**
 * Las actividades del curso, en orden, con el estado de este niño.
 *
 * Cada paso trae:
 *   · `total` / `hechas` · estaciones de la actividad y las terminadas
 *   · `completa`         · las hizo todas
 *   · `abierta`          · puede jugarla ahora
 *   · `es_siguiente`     · es la que le toca
 *
 * Todo sale de una sola consulta con agregados. Es la pantalla de inicio
 * de un niño: pedir el progreso actividad por actividad serían veinte
 * consultas cada vez que abre la aplicación.
 *
 * **El progreso que cuenta aquí es el del curso Y el personal.** Si el
 * niño ya había jugado esa actividad en su casa, la ruta la da por hecha:
 * obligarlo a repetir lo que ya domina para poder avanzar es exactamente
 * lo que la separación de contextos vino a evitar del otro lado, en la
 * rejilla del docente.
 */
function rutaDelEstudiante(int $usuarioId, array $curso): array
{
    $cursoId = (int) $curso['id'];

    $pasos = traerTodo(
        'SELECT ca.sort_order, ca.due_date,
                a.id, a.slug, a.title, a.icon, a.duration_minutes,
                cat.name AS categoria, cat.icon AS categoria_icon,
                (SELECT COUNT(*) FROM activity_stations s
                  WHERE s.activity_id = a.id) AS total,
                (SELECT COUNT(DISTINCT p.station_id) FROM activity_progress p
                  WHERE p.user_id = ? AND p.activity_id = a.id
                    AND p.status = "completed") AS hechas
           FROM course_activities ca
           JOIN activities a    ON a.id = ca.activity_id
      LEFT JOIN categories cat  ON cat.id = a.category_id
          WHERE ca.course_id = ? AND a.status = "published"
       ORDER BY ca.sort_order, ca.assigned_at, ca.id',
        [$usuarioId, $cursoId]
    );

    $secuencial = modoDeRuta($curso) === 'secuencial';
    $anteriorOk = true;
    $yaHayUna   = false;

    foreach ($pasos as $i => &$p) {
        $total  = (int) $p['total'];
        $hechas = min((int) $p['hechas'], $total);

        $p['total']    = $total;
        $p['hechas']   = $hechas;
        $p['completa'] = $total > 0 && $hechas >= $total;
        $p['paso']     = $i + 1;

        /*
         * En modo secuencial se abre la primera y, a partir de ahí, cada
         * una cuando la anterior está completa. En modo libre, todas.
         *
         * Una actividad sin estaciones cargadas NO frena la ruta: sería
         * un descuido del catálogo dejando a la clase entera parada, sin
         * que el docente pueda ni enterarse de por qué.
         */
        $p['abierta'] = !$secuencial || $anteriorOk;

        if ($secuencial) {
            $anteriorOk = $p['completa'] || $total === 0;
        }

        $p['es_siguiente'] = false;

        if (!$yaHayUna && $p['abierta'] && !$p['completa'] && $total > 0) {
            $p['es_siguiente'] = true;
            $yaHayUna = true;
        }
    }
    unset($p);

    return $pasos;
}

/** Resumen de la ruta, para la cabecera del niño y el saludo. */
function resumenDeRuta(array $pasos): array
{
    $completas = 0;
    $estaciones = 0;
    $hechas = 0;

    foreach ($pasos as $p) {
        if ($p['completa']) { $completas++; }
        $estaciones += (int) $p['total'];
        $hechas     += (int) $p['hechas'];
    }

    return [
        'pasos'      => count($pasos),
        'completas'  => $completas,
        'estaciones' => $estaciones,
        'hechas'     => $hechas,
        'porcentaje' => $estaciones > 0 ? (int) round($hechas * 100 / $estaciones) : 0,
        'terminada'  => $pasos !== [] && $completas === count($pasos),
    ];
}

/**
 * La caché de la ruta, devuelta POR REFERENCIA.
 *
 * Un `static` normal no se puede vaciar desde fuera, y hace falta
 * poderlo: quien reordena la ruta o termina una estación en la misma
 * petición se quedaría mirando la foto anterior. Es el mismo patrón que
 * usa `ajuste()` con `cacheAjustes()`, y por el mismo motivo.
 */
function &cacheDeRuta(): array
{
    static $cache = ['curso' => [], 'ruta' => null];

    return $cache;
}

/**
 * La ruta del usuario actual, calculada una sola vez por petición.
 *
 * `motivoDeRuta()` se llama una vez por estación —y una actividad tiene
 * quince—, así que sin esta caché el reproductor haría quince veces la
 * misma consulta para pintar un solo mapa.
 */
function rutaActual(): array
{
    $cache = &cacheDeRuta();

    if ($cache['ruta'] !== null) {
        return $cache['ruta'];
    }

    $curso = enRutaGuiada() ? cursoDeRuta() : null;

    if (!$curso) {
        return $cache['ruta'] = ['curso' => null, 'pasos' => [], 'por_actividad' => []];
    }

    $pasos = rutaDelEstudiante((int) usuarioActualId(), $curso);

    $porActividad = [];
    foreach ($pasos as $p) {
        $porActividad[(int) $p['id']] = $p;
    }

    return $cache['ruta'] = [
        'curso' => $curso, 'pasos' => $pasos, 'por_actividad' => $porActividad,
    ];
}

/**
 * Olvida la ruta cacheada.
 *
 * La llama quien cambia algo de lo que la ruta depende dentro de la misma
 * petición: reordenar, asignar, quitar o guardar progreso. Sin esto, el
 * docente reordena y ve la lista vieja.
 */
function olvidarRuta(): void
{
    $cache = &cacheDeRuta();
    $cache = ['curso' => [], 'ruta' => null];
}


// =====================================================================
//  LA PUERTA
// =====================================================================

/**
 * Por qué la ruta NO deja jugar esta actividad, o null si la deja.
 *
 * Devuelve dos motivos distintos y no un sí/no porque un niño necesita
 * respuestas distintas: «esto no es de tu clase» y «primero termina lo
 * anterior» son dos situaciones que no se parecen en nada.
 *
 * @return string|null 'fuera_de_ruta' | 'ruta_pendiente' | null
 */
function motivoDeRuta(array $actividad): ?string
{
    if (!rutaInstalada()) {
        return null;
    }

    $ruta = rutaActual();

    if (!$ruta['curso']) {
        return null;
    }

    $paso = $ruta['por_actividad'][(int) ($actividad['id'] ?? 0)] ?? null;

    if ($paso === null) {
        return 'fuera_de_ruta';
    }

    return $paso['abierta'] ? null : 'ruta_pendiente';
}

/** El paso que el niño tiene que hacer ahora, o null. */
function siguienteDeRuta(): ?array
{
    foreach (rutaActual()['pasos'] as $p) {
        if ($p['es_siguiente']) {
            return $p;
        }
    }

    return null;
}

/**
 * El paso anterior sin terminar, para poder decir QUÉ falta.
 *
 * «Primero termina lo anterior» no sirve de nada sin el nombre: el niño
 * no sabe cuál es «lo anterior».
 */
function pasoQueFalta(): ?array
{
    foreach (rutaActual()['pasos'] as $p) {
        if (!$p['completa'] && (int) $p['total'] > 0) {
            return $p;
        }
    }

    return null;
}


// =====================================================================
//  ORDENAR LA RUTA  (lo usa el docente)
// =====================================================================

/**
 * Renumera las asignaciones de un curso 1..N.
 *
 * Se llama antes de mover nada. Al asignar un paquete entero todas las
 * actividades entraban con números correlativos, pero al quitar alguna
 * quedan huecos, y con huecos —o con empates de datos viejos— un
 * intercambio de posiciones no hace lo que parece.
 */
function normalizarOrdenDeRuta(int $cursoId): void
{
    $filas = traerTodo(
        'SELECT id FROM course_activities
          WHERE course_id = ? ORDER BY sort_order, assigned_at, id',
        [$cursoId]
    );

    $n = 0;
    foreach ($filas as $f) {
        $n++;
        ejecutar('UPDATE course_activities SET sort_order = ? WHERE id = ?', [$n, (int) $f['id']]);
    }
}

/**
 * Sube o baja una actividad dentro de la ruta del curso.
 *
 * @param int $delta -1 para subir, 1 para bajar.
 */
function moverEnRuta(int $cursoId, int $actividadId, int $delta): bool
{
    $delta = $delta < 0 ? -1 : 1;

    normalizarOrdenDeRuta($cursoId);

    $esta = traerUno(
        'SELECT id, sort_order FROM course_activities
          WHERE course_id = ? AND activity_id = ?',
        [$cursoId, $actividadId]
    );

    if (!$esta) {
        return false;
    }

    $destino = (int) $esta['sort_order'] + $delta;

    $vecina = traerUno(
        'SELECT id, sort_order FROM course_activities
          WHERE course_id = ? AND sort_order = ?',
        [$cursoId, $destino]
    );

    // Ya está en un extremo: no es un error, simplemente no hay a dónde.
    if (!$vecina) {
        return false;
    }

    ejecutar('UPDATE course_activities SET sort_order = ? WHERE id = ?',
             [$destino, (int) $esta['id']]);
    ejecutar('UPDATE course_activities SET sort_order = ? WHERE id = ?',
             [(int) $esta['sort_order'], (int) $vecina['id']]);

    return true;
}

/** Cambia el modo de la ruta de un curso. */
function guardarModoDeRuta(int $cursoId, string $modo): void
{
    if (!rutaInstalada()) {
        return;
    }

    ejecutar('UPDATE courses SET ruta_modo = ? WHERE id = ?',
             [$modo === 'libre' ? 'libre' : 'secuencial', $cursoId]);
}
