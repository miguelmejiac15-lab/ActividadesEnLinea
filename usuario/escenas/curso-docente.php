<?php
/**
 * escenas/curso-docente.php — Maquetas de la guía del docente.
 *
 * Ids con prefijo `g-cd-`.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}
?>

<!-- ── Área Escuela, sin cursos ──────────────────────────────────── -->
<div data-escena="inicio">
    <div class="guia-pantalla">
        <h3>Escuela</h3>
        <p class="sub">Sus cursos y el avance de sus estudiantes.</p>

        <div style="padding:16px;background:var(--fondo-suave);border-radius:var(--radio-chico);
                    text-align:center;color:var(--texto-tenue);font-size:.78rem">
            Todavía no tiene cursos.
        </div>

        <div style="margin-top:14px;text-align:center">
            <span class="guia-boton" id="g-cd-nuevo">+ Crear un curso</span>
        </div>
    </div>
</div>

<!-- ── Crear el curso ────────────────────────────────────────────── -->
<div data-escena="nuevo" hidden>
    <div class="guia-pantalla">
        <h3>Nuevo curso</h3>
        <p class="sub">Con el nombre que usted usa para reconocerlo.</p>

        <b style="font-size:.78rem;color:var(--oscuro);display:block;margin-bottom:5px">Nombre</b>
        <span class="guia-tecleado" id="g-cd-nombre" data-vacio="Ej. Primero B">Ej. Primero B</span>

        <b style="font-size:.78rem;color:var(--oscuro);display:block;margin:11px 0 5px">Grado</b>
        <span class="guia-tecleado">Primero</span>

        <div style="margin-top:14px;text-align:center">
            <span class="guia-boton" id="g-cd-crear">Crear el curso</span>
        </div>
    </div>
</div>

<!-- ── Estudiantes ───────────────────────────────────────────────── -->
<div data-escena="estudiantes" hidden>
    <div class="guia-pantalla">
        <h3>Primero B · Estudiantes</h3>
        <p class="sub">Escríbalos, o tráigalos del curso del año pasado.</p>

        <b style="font-size:.78rem;color:var(--oscuro);display:block;margin-bottom:5px">
            Nombre del estudiante
        </b>

        <div style="display:flex;gap:8px;align-items:center">
            <span class="guia-tecleado" id="g-cd-alumno" data-vacio="Nombre y apellido"
                  style="flex:1">Nombre y apellido</span>
            <span class="guia-boton chico">Agregar</span>
        </div>

        <div id="g-cd-importar"
             style="margin-top:14px;padding:11px 13px;background:var(--fondo-suave);
                    border-radius:var(--radio-chico);display:flex;gap:10px;align-items:center">
            <span style="font-size:1.2rem">📥</span>
            <span style="flex:1">
                <b style="color:var(--oscuro);font-size:.8rem;display:block">Importar de otro curso</b>
                <small style="color:var(--texto-tenue);font-size:.73rem">
                    Los de primero pasan a segundo sin volver a escribirlos
                </small>
            </span>
        </div>
    </div>
</div>

<!-- ── Asignar actividades ───────────────────────────────────────── -->
<div data-escena="actividades" hidden>
    <div class="guia-pantalla">
        <h3>Primero B · Actividades</h3>
        <p class="sub">Marque lo que quiere que trabajen y asígnelo.</p>

        <div class="guia-fila">
            <span class="guia-marca" id="g-cd-marca1"></span>
            <span class="emoji">🦋</span>
            <span class="crece"><b>Aventura de la M</b><small>16 ejercicios</small></span>
        </div>

        <div class="guia-fila">
            <span class="guia-marca" id="g-cd-marca2"></span>
            <span class="emoji">🔢</span>
            <span class="crece"><b>Valle de las Sumas</b><small>6 ejercicios</small></span>
        </div>

        <div class="guia-fila">
            <span class="guia-marca"></span>
            <span class="emoji">🌳</span>
            <span class="crece"><b>Bosque de Vocales</b><small>12 ejercicios</small></span>
        </div>

        <div style="margin-top:14px;text-align:center">
            <span class="guia-boton" id="g-cd-asignar">Asignar las marcadas</span>
        </div>
    </div>
</div>

<!-- ── El avance del curso ───────────────────────────────────────── -->
<div data-escena="progreso" hidden>
    <div class="guia-pantalla">
        <h3>Primero B · Avance</h3>
        <p class="sub">Quién va adelante y quién no ha empezado.</p>

        <div id="g-cd-barras">
            <?php
            /*
             * Tres barras con números distintos a propósito: una llena,
             * una a medias y una en cero. Es lo que un docente quiere
             * poder distinguir de un vistazo, y una maqueta donde todos
             * van igual no demuestra nada.
             */
            $alumnos = [
                ['Ana Restrepo',   86, 'var(--verde)'],
                ['Beto Cárdenas',  42, 'var(--azul)'],
                ['Carla Duarte',    0, 'var(--borde-fuerte)'],
            ];

            foreach ($alumnos as [$nombre, $pct, $color]):
            ?>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:9px">
                    <span style="flex:0 0 96px;font-size:.78rem;color:var(--oscuro)">
                        <?= e($nombre) ?>
                    </span>
                    <span style="flex:1;height:8px;background:var(--borde-suave);
                                 border-radius:4px;overflow:hidden">
                        <span style="display:block;height:100%;width:<?= (int) $pct ?>%;
                                     background:<?= $color ?>;border-radius:4px"></span>
                    </span>
                    <span style="flex:0 0 34px;text-align:right;font-size:.74rem;
                                 color:var(--texto-tenue)"><?= (int) $pct ?>%</span>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</div>

<?php
/*
 * ─────────────────────────────────────────────────────────────────────
 *  EL FINAL: LA OTRA MITAD DE LA PANTALLA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un docente que ve las barras sabe QUÉ MIDE, pero no qué le queda
 * delante a su alumno. Y eso es justo lo que decide si confía en
 * asignar: si sospecha que el niño puede irse por su cuenta a cualquier
 * parte, no asigna nada y usa la plataforma como catálogo suelto.
 *
 * Verlo cierra el argumento mejor que cualquier frase.
 */
?>
<!-- ── Lo que le queda al estudiante ─────────────────────────────── -->
<div data-escena="loQueVeElNino" hidden>
    <div style="text-align:center;font-size:.72rem;letter-spacing:.09em;text-transform:uppercase;
                color:var(--texto-tenue);margin-bottom:9px">
        Así lo ve Ana cuando entra
    </div>

    <div class="guia-pantalla" style="background:var(--fondo-suave)">
        <h3 style="text-align:center">Mi ruta</h3>
        <p class="sub" style="text-align:center">Lo que te mandó tu profe</p>

        <div id="g-cd-ruta">
            <?php
            /*
             * Tres estados distintos a propósito: hecha, la de ahora, y
             * la que todavía no se abre. Una maqueta donde las tres se
             * ven igual no enseña que la ruta va EN ORDEN.
             */
            $ruta = [
                ['🦋', 'Aventura de la M', 'hecha'],
                ['🔢', 'Valle de las Sumas', 'ahora'],
                ['🌳', 'Bosque de Vocales', 'luego'],
            ];

            foreach ($ruta as [$emoji, $nombre, $estado]):

                $borde = match ($estado) {
                    'hecha' => 'var(--verde)',
                    'ahora' => 'var(--azul)',
                    default => 'var(--borde)',
                };

                $marca = match ($estado) {
                    'hecha' => '<span style="color:var(--verde);font-size:.95rem">✓ Lista</span>',
                    'ahora' => '<span class="guia-boton chico">Empezar</span>',
                    default => '<span style="color:var(--texto-tenue);font-size:.85rem">🔒</span>',
                };
            ?>
                <div style="display:flex;align-items:center;gap:10px;padding:9px 11px;
                            background:var(--blanco);border:2px solid <?= $borde ?>;
                            border-radius:var(--radio-chico);margin-bottom:7px;
                            <?= $estado === 'luego' ? 'opacity:.55' : '' ?>">
                    <span style="font-size:1.3rem"><?= $emoji ?></span>
                    <b style="flex:1;font-size:.8rem;color:var(--oscuro)"><?= e($nombre) ?></b>
                    <?= $marca ?>
                </div>
            <?php endforeach; ?>
        </div>

        <p style="text-align:center;font-size:.72rem;color:var(--texto-tenue);margin:10px 0 0">
            En orden, y sin nada más que la distraiga.
        </p>
    </div>
</div>
