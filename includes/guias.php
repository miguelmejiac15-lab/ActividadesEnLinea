<?php
/**
 * guias.php — Las guías animadas y quién puede verlas.
 *
 * =====================================================================
 *  UNA GUÍA ES UN GUION, NO UN VIDEO
 * =====================================================================
 *
 * Cada guía son dos cosas: unas ESCENAS —maquetas de la pantalla, hechas
 * con los mismos componentes del sitio— y un GUION que dice a dónde va
 * el ratón y qué se explica en cada momento.
 *
 * Así una guía se parece a la pantalla de verdad porque comparte su hoja
 * de estilos, no porque alguien se acordó de actualizar una captura. Y
 * corregirla cuesta una línea de texto, no una tarde de grabación.
 *
 * =====================================================================
 *  A QUIÉN SE LE ENSEÑA CADA UNA
 * =====================================================================
 *
 * `paraQuien()` decide, y decide por lo que la persona PUEDE HACER, no
 * por su rol a secas: quien entra al área Escuela ve las de docente sea
 * por rol o por plan, que es la misma regla que usa `puedeEntrarAEscuela()`.
 *
 * Enseñarle a alguien cómo se hace algo que no puede hacer es peor que no
 * enseñarle nada: le hace buscar un botón que no existe en su pantalla.
 */

declare(strict_types=1);


/**
 * Todas las guías, en orden de presentación.
 *
 * `pasos` es lo que ejecuta `assets/js/guia.js`:
 *   escena  — a qué maqueta saltar
 *   texto   — lo que se lee abajo mientras pasa
 *   a       — selector del elemento al que va el ratón
 *   hacer   — clic | escribir | marcar | mirar
 *   valor   — lo que se teclea, para «escribir»
 *   pausa   — ms extra al terminar el paso
 */
function guiasDisponibles(): array
{
    return [

        // ─────────────────────────────────────────────────────────────
        'modo-nino' => [
            'titulo'   => 'Preparar la cuenta para tu hijo',
            'resumen'  => 'Elige qué actividades ve, pon un PIN y enciende el modo niño.',
            'icono'    => '🧒',
            'duracion' => '1 min',
            'para'     => 'suscriptor',
            'escenas'  => 'modo-nino',
            'pasos'    => [
                [
                    'escena' => 'inicio',
                    'texto'  => 'Desde «Mi espacio», entra a Modo niño.',
                    'a'      => '#g-mn-entrar',
                    'hacer'  => 'clic',
                ],
                [
                    'escena' => 'vacio',
                    'texto'  => 'Al principio la lista está vacía. Lo que elijas aquí es '
                              . 'todo lo que tu hijo va a ver.',
                    'a'      => '#g-mn-lista',
                    'hacer'  => 'mirar',
                ],
                [
                    'texto'  => 'Puedes agregar una materia entera de un golpe. Toca la que '
                              . 'quieras trabajar esta semana.',
                    'a'      => '#g-mn-materia',
                    'hacer'  => 'clic',
                    'pausa'  => 1100,
                ],
                [
                    'escena' => 'conlista',
                    'texto'  => 'Ya están en la lista. Si te equivocaste, cada una tiene su '
                              . 'botón de quitar.',
                    'a'      => '#g-mn-quitar',
                    'hacer'  => 'mirar',
                ],
                [
                    'texto'  => 'Ahora el PIN: cuatro números que tu hijo no deba saber.',
                    'a'      => '#g-mn-pin',
                    'hacer'  => 'escribir',
                    'valor'  => '••••',
                ],
                [
                    'texto'  => 'Guarda el PIN. Es lo que te pediremos para salir del modo.',
                    'a'      => '#g-mn-guardar',
                    'hacer'  => 'clic',
                ],
                [
                    'texto'  => 'Y ya puedes encenderlo.',
                    'a'      => '#g-mn-encender',
                    'hacer'  => 'clic',
                    'pausa'  => 1200,
                ],
                [
                    'escena' => 'encendido',
                    'texto'  => 'Mientras esté encendido no se puede pagar ni cambiar la '
                              . 'lista. Para salir, tu PIN.',
                    'a'      => '#g-mn-salir',
                    'hacer'  => 'mirar',
                    'pausa'  => 1600,
                ],
                [
                    'escena' => 'loQueVe',
                    'texto'  => 'Y esto es lo que le queda a tu hijo: solo las tres que '
                              . 'elegiste, grandes y sin nada más alrededor.',
                    'a'      => '#g-mn-suyas',
                    'hacer'  => 'mirar',
                    'pausa'  => 2200,
                ],
            ],
        ],

        // ─────────────────────────────────────────────────────────────
        'curso-docente' => [
            'titulo'   => 'Armar tu curso y asignar actividades',
            'resumen'  => 'Crea el curso, agrega estudiantes y elige qué trabajan esta semana.',
            'icono'    => '🏫',
            'duracion' => '1 min',
            'para'     => 'docente',
            'escenas'  => 'curso-docente',
            'pasos'    => [
                [
                    'escena' => 'inicio',
                    'texto'  => 'En el área Escuela, empieza creando un curso.',
                    'a'      => '#g-cd-nuevo',
                    'hacer'  => 'clic',
                ],
                [
                    'escena' => 'nuevo',
                    'texto'  => 'Ponle el nombre con el que usted lo reconoce.',
                    'a'      => '#g-cd-nombre',
                    'hacer'  => 'escribir',
                    'valor'  => 'Primero B',
                ],
                [
                    'texto'  => 'Y lo crea.',
                    'a'      => '#g-cd-crear',
                    'hacer'  => 'clic',
                    'pausa'  => 1100,
                ],
                [
                    'escena' => 'estudiantes',
                    'texto'  => 'Ahora los estudiantes. Puede escribirlos uno a uno…',
                    'a'      => '#g-cd-alumno',
                    'hacer'  => 'escribir',
                    'valor'  => 'Ana Restrepo',
                ],
                [
                    'texto'  => '…o importar el curso del año pasado, y los de primero pasan '
                              . 'a segundo sin volver a teclear nada.',
                    'a'      => '#g-cd-importar',
                    'hacer'  => 'mirar',
                    'pausa'  => 1400,
                ],
                [
                    'escena' => 'actividades',
                    'texto'  => 'Con el curso armado, elija qué van a trabajar. Marque lo que '
                              . 'quiera asignar.',
                    'a'      => '#g-cd-marca1',
                    'hacer'  => 'marcar',
                ],
                [
                    'texto'  => 'Todas las que haga falta.',
                    'a'      => '#g-cd-marca2',
                    'hacer'  => 'marcar',
                ],
                [
                    'texto'  => 'Y las asigna. Si se equivoca, se quitan igual de fácil.',
                    'a'      => '#g-cd-asignar',
                    'hacer'  => 'clic',
                    'pausa'  => 1200,
                ],
                [
                    'escena' => 'progreso',
                    'texto'  => 'Y ve el avance de todo el curso en una pantalla: quién va '
                              . 'adelante y quién no ha empezado.',
                    'a'      => '#g-cd-barras',
                    'hacer'  => 'mirar',
                    'pausa'  => 1800,
                ],
                [
                    'escena' => 'loQueVeElNino',
                    'texto'  => 'Y esto es lo que le queda a Ana: solo lo que usted asignó, '
                              . 'en orden, y sin nada más que la distraiga.',
                    'a'      => '#g-cd-ruta',
                    'hacer'  => 'mirar',
                    'pausa'  => 2200,
                ],
            ],
        ],

        // ─────────────────────────────────────────────────────────────
        'institucion' => [
            'titulo'   => 'Administrar su institución',
            'resumen'  => 'Cree profesores, ábrales sus cursos y vea el avance del colegio.',
            'icono'    => '🏛️',
            'duracion' => '1 min',
            'para'     => 'coordinador',
            'escenas'  => 'institucion',
            'pasos'    => [
                [
                    'escena' => 'inicio',
                    'texto'  => 'Como coordinador, usted administra su colegio sin pedirle '
                              . 'permiso a nadie. Empiece por los profesores.',
                    'a'      => '#g-in-docentes',
                    'hacer'  => 'clic',
                ],
                [
                    'escena' => 'docentes',
                    'texto'  => 'Cree una cuenta de profesor con su nombre y su correo.',
                    'a'      => '#g-in-nombre',
                    'hacer'  => 'escribir',
                    'valor'  => 'Marta Gómez',
                ],
                [
                    'texto'  => 'La plataforma le genera una clave para entregarle.',
                    'a'      => '#g-in-crear',
                    'hacer'  => 'clic',
                    'pausa'  => 1100,
                ],
                [
                    'escena' => 'conDocente',
                    'texto'  => 'Ya puede entrar. Ahora ábrale su curso.',
                    'a'      => '#g-in-curso',
                    'hacer'  => 'clic',
                ],
                [
                    'escena' => 'curso',
                    'texto'  => 'El curso se crea a nombre de ese profesor, que será quien '
                              . 'asigne las actividades del día a día.',
                    'a'      => '#g-in-asignar',
                    'hacer'  => 'mirar',
                    'pausa'  => 1300,
                ],
                [
                    'escena' => 'resumen',
                    'texto'  => 'Desde el panel ve todos los cursos, sus estudiantes y cómo '
                              . 'va cada uno.',
                    'a'      => '#g-in-resumen',
                    'hacer'  => 'mirar',
                    'pausa'  => 1800,
                ],
                [
                    'escena' => 'cadena',
                    'texto'  => 'Y ahí termina su parte: Marta sigue sola con su curso y sus '
                              . 'estudiantes reciben lo suyo. Usted no entra curso por curso.',
                    'a'      => '#g-in-cadena',
                    'hacer'  => 'mirar',
                    'pausa'  => 2200,
                ],
            ],
        ],

        /*
         * ─────────────────────────────────────────────────────────────
         *  POR QUÉ EL COORDINADOR TIENE TRES Y NO UNA
         * ─────────────────────────────────────────────────────────────
         *
         * «Administrar su institución» enseña a crear un profesor y
         * abrirle un curso, y ahí se acababa. Pero eso es el primer día:
         * lo que un coordinador hace de verdad, y lo que más preguntas
         * genera, es meter a los niños y entender por qué un día deja de
         * funcionar.
         *
         * Se parten en tres y no en una larga a propósito: son tres
         * momentos distintos del año —montar el colegio, matricular el
         * curso, renovar— y nadie busca ayuda de los tres a la vez.
         */

        'matricular' => [
            'titulo'   => 'Matricular a los estudiantes',
            'resumen'  => 'Cree las cuentas del curso, reparta las claves y déles el código de clase.',
            'icono'    => '🧒',
            'duracion' => '1 min',
            'para'     => 'coordinador',
            'escenas'  => 'matricular',
            'pasos'    => [
                [
                    'escena' => 'inicio',
                    'texto'  => 'El curso ya existe, pero está vacío. Toca dar de alta a los '
                              . 'estudiantes.',
                    'a'      => '#g-ma-alta',
                    'hacer'  => 'clic',
                ],
                [
                    'escena' => 'lista',
                    'texto'  => 'Puede pegar la lista del curso entera, un nombre por línea. '
                              . 'No se pide correo: un niño de cinco años no tiene.',
                    'a'      => '#g-ma-nombres',
                    'hacer'  => 'escribir',
                    'valor'  => "Ana Pérez\nLuis Acero\nSara Díaz",
                ],
                [
                    'texto'  => 'La plataforma crea las cuentas y genera una clave para cada una.',
                    'a'      => '#g-ma-crear',
                    'hacer'  => 'clic',
                    'pausa'  => 1100,
                ],
                [
                    'escena' => 'claves',
                    'texto'  => 'Anótelas ahora: no se vuelven a mostrar, solo se guarda su '
                              . 'huella. Son legibles para poder dictarlas sin confundir '
                              . 'un uno con una ele.',
                    'a'      => '#g-ma-claves',
                    'hacer'  => 'mirar',
                    'pausa'  => 2400,
                ],
                [
                    'escena' => 'codigo',
                    'texto'  => 'Y esto es lo que se escribe en el tablero. El niño entra, '
                              . 'toca su nombre en la lista y ya está dentro.',
                    'a'      => '#g-ma-codigo',
                    'hacer'  => 'mirar',
                    'pausa'  => 2000,
                ],
                [
                    'escena' => 'cierre',
                    'texto'  => 'Desde aquí cada uno ve lo suyo: Marta su curso, los niños '
                              . 'lo que Marta les puso, y usted el colegio entero.',
                    'a'      => '#g-ma-cierre',
                    'hacer'  => 'mirar',
                    'pausa'  => 2200,
                ],
            ],
        ],

        'licencia' => [
            'titulo'   => 'La licencia y los cupos',
            'resumen'  => 'Cuántos estudiantes caben, qué pasa cuando se acaban y qué cambia al vencer.',
            'icono'    => '🎟️',
            'duracion' => '1 min',
            'para'     => 'coordinador',
            'escenas'  => 'licencia',
            'pasos'    => [
                [
                    'escena' => 'inicio',
                    'texto'  => 'Dos números gobiernan el colegio: hasta cuándo va la licencia '
                              . 'y cuántos cupos quedan.',
                    'a'      => '#g-li-licencia',
                    'hacer'  => 'mirar',
                    'pausa'  => 1800,
                ],
                [
                    'escena' => 'cupos',
                    'texto'  => 'Un cupo lo ocupa cada estudiante. Los profesores y usted no '
                              . 'gastan licencia: solo gasta quien aprende.',
                    'a'      => '#g-li-queocupa',
                    'hacer'  => 'mirar',
                    'pausa'  => 2200,
                ],
                [
                    'escena' => 'lleno',
                    'texto'  => 'Si no quedan, se avisa ANTES de crear las cuentas. Crear '
                              . 'veinte y que entren doce sería peor que no crear ninguna.',
                    'a'      => '#g-li-lleno',
                    'hacer'  => 'mirar',
                    'pausa'  => 2400,
                ],
                [
                    'escena' => 'vencida',
                    'texto'  => 'El día que la licencia vence nadie pierde nada: los niños '
                              . 'siguen entrando a la parte gratuita y el avance espera.',
                    'a'      => '#g-li-vencida',
                    'hacer'  => 'mirar',
                    'pausa'  => 2400,
                ],
                [
                    'escena' => 'renovar',
                    'texto'  => 'Al renovar, el tiempo nuevo se suma al que quede. Por eso '
                              . 'adelantarse no cuesta nada, y esperar al último día sí.',
                    'a'      => '#g-li-renovar',
                    'hacer'  => 'mirar',
                    'pausa'  => 2400,
                ],
            ],
        ],

    ];
}

/** Una guía por su nombre, o null. */
function guiaPorSlug(string $slug): ?array
{
    $g = guiasDisponibles();

    if (!isset($g[$slug])) {
        return null;
    }

    return $g[$slug] + ['slug' => $slug];
}

/**
 * ¿A quién se le enseña esta guía?
 *
 * Se decide por lo que la persona PUEDE HACER y no por su rol a secas.
 * Un docente de un colegio con licencia y un particular con plan Escuela
 * ven las mismas pantallas, así que les sirve la misma guía — es la misma
 * regla que aplica `puedeEntrarAEscuela()`.
 *
 * Y a nadie se le enseña algo que no puede hacer: buscar un botón que no
 * está en su pantalla es peor que no haber visto la guía.
 */
function guiasParaMi(): array
{
    if (!function_exists('usuarioActual') || usuarioActual() === null) {
        return [];
    }

    $mias = [];

    $esCoordinador = tieneRol('school_admin') || esAdmin();
    $esDocente     = function_exists('puedeEntrarAEscuela') && puedeEntrarAEscuela();

    /*
     * Un estudiante de colegio no ve ninguna: su cuenta la gobierna la
     * ruta que le puso su docente, y no configura nada.
     */
    if (tieneRol('student')) {
        return [];
    }

    foreach (guiasDisponibles() as $slug => $g) {
        $vale = match ($g['para']) {
            'suscriptor'  => true,               // cualquier cuenta personal
            'docente'     => $esDocente,
            'coordinador' => $esCoordinador,
            default       => false,
        };

        if ($vale) {
            $mias[$slug] = $g + ['slug' => $slug];
        }
    }

    return $mias;
}

/** ¿Puede esta persona ver esta guía concreta? */
function puedoVerGuia(string $slug): bool
{
    return array_key_exists($slug, guiasParaMi());
}
