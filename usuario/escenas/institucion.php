<?php
/**
 * escenas/institucion.php — Maquetas de la guía del coordinador.
 *
 * Ids con prefijo `g-in-`.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}
?>

<!-- ── El panel de la institución ────────────────────────────────── -->
<div data-escena="inicio">
    <div class="guia-pantalla">
        <h3>Colegio Piloto</h3>
        <p class="sub">Panel de institución</p>

        <div class="guia-fila" id="g-in-docentes">
            <span class="emoji">👩‍🏫</span>
            <span class="crece">
                <b>Profesores</b>
                <small>Cree sus cuentas y entrégueles la clave</small>
            </span>
            <span class="guia-boton chico suave">Abrir</span>
        </div>

        <div class="guia-fila">
            <span class="emoji">🏫</span>
            <span class="crece"><b>Cursos</b><small>Todos los del colegio</small></span>
        </div>

        <div class="guia-fila">
            <span class="emoji">📊</span>
            <span class="crece"><b>Avance</b><small>Cómo va cada curso</small></span>
        </div>
    </div>
</div>

<!-- ── Crear un profesor ─────────────────────────────────────────── -->
<div data-escena="docentes" hidden>
    <div class="guia-pantalla">
        <h3>Profesores</h3>
        <p class="sub">Todavía no hay ninguno.</p>

        <b style="font-size:.78rem;color:var(--oscuro);display:block;margin-bottom:5px">Nombre</b>
        <span class="guia-tecleado" id="g-in-nombre" data-vacio="Nombre y apellido">Nombre y apellido</span>

        <b style="font-size:.78rem;color:var(--oscuro);display:block;margin:11px 0 5px">Correo</b>
        <span class="guia-tecleado">marta@colegio.edu.co</span>

        <div style="margin-top:14px;text-align:center">
            <span class="guia-boton" id="g-in-crear">Crear profesor</span>
        </div>
    </div>
</div>

<!-- ── Ya hay un profesor ────────────────────────────────────────── -->
<div data-escena="conDocente" hidden>
    <div class="guia-pantalla">
        <h3>Profesores</h3>
        <p class="sub">1 profesor en el colegio.</p>

        <div class="guia-fila">
            <span class="emoji">👩‍🏫</span>
            <span class="crece">
                <b>Marta Gómez</b>
                <small>Clave temporal: <b style="color:var(--azul)">sol-verde-24</b></small>
            </span>
        </div>

        <div id="g-in-curso"
             style="margin-top:13px;padding:11px 13px;background:var(--fondo-suave);
                    border-radius:var(--radio-chico);display:flex;gap:10px;align-items:center">
            <span style="font-size:1.2rem">➕</span>
            <span style="flex:1">
                <b style="color:var(--oscuro);font-size:.8rem;display:block">Abrirle un curso</b>
                <small style="color:var(--texto-tenue);font-size:.73rem">
                    A nombre de Marta Gómez
                </small>
            </span>
        </div>
    </div>
</div>

<!-- ── El curso creado ───────────────────────────────────────────── -->
<div data-escena="curso" hidden>
    <div class="guia-pantalla">
        <h3>Tercero A</h3>
        <p class="sub">Profesora: Marta Gómez · 0 estudiantes</p>

        <div id="g-in-asignar"
             style="padding:13px;background:var(--fondo-suave);border-radius:var(--radio-chico);
                    color:var(--texto-tenue);font-size:.78rem;line-height:1.5">
            A partir de aquí, la profesora agrega a sus estudiantes y asigna las
            actividades del día a día. Usted no tiene que entrar a cada curso.
        </div>
    </div>
</div>

<!-- ── El resumen del colegio ────────────────────────────────────── -->
<div data-escena="resumen" hidden>
    <div class="guia-pantalla">
        <h3>Colegio Piloto · Avance</h3>
        <p class="sub">Todos los cursos, de un vistazo.</p>

        <div id="g-in-resumen">
            <?php
            $cursos = [
                ['Tercero A', 'Marta Gómez',   24, 71, 'var(--verde)'],
                ['Primero B', 'Luis Peña',     22, 48, 'var(--azul)'],
                ['Quinto C',  'Ana Villa',     19, 23, 'var(--naranja)'],
            ];

            foreach ($cursos as [$curso, $docente, $n, $pct, $color]):
            ?>
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:10px">
                    <span style="flex:0 0 110px">
                        <b style="font-size:.78rem;color:var(--oscuro);display:block">
                            <?= e($curso) ?>
                        </b>
                        <small style="font-size:.7rem;color:var(--texto-tenue)">
                            <?= e($docente) ?> · <?= (int) $n ?>
                        </small>
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
 *  EL FINAL: LA CADENA COMPLETA
 * ─────────────────────────────────────────────────────────────────────
 *
 * La guía terminaba en el resumen del colegio, que es lo que el
 * coordinador ve — pero no lo que el coordinador necesita creer. Lo que
 * tiene que quedarle claro es que su trabajo TERMINA aquí: creó la
 * cuenta, abrió el curso, y a partir de ahí la profesora sigue sola y el
 * niño recibe lo suyo.
 *
 * Por eso el cierre enseña los tres eslabones a la vez. Un coordinador
 * que no ve esto sigue creyendo que tendrá que entrar a cada curso.
 */
?>
<!-- ── La cadena, de punta a punta ───────────────────────────────── -->
<div data-escena="cadena" hidden>
    <div style="text-align:center;font-size:.72rem;letter-spacing:.09em;text-transform:uppercase;
                color:var(--texto-tenue);margin-bottom:9px">
        Y a partir de ahí, solo
    </div>

    <div id="g-in-cadena" style="display:grid;gap:8px">
        <?php
        $eslabones = [
            ['🏛️', 'Usted', 'Creó la profesora y su curso.', 'hecho'],
            ['👩‍🏫', 'Marta', 'Agrega a sus estudiantes y asigna lo de la semana.', ''],
            ['🧒', 'Sus estudiantes', 'Entran y solo ven lo que Marta les mandó.', ''],
        ];

        foreach ($eslabones as $i => [$emoji, $quien, $que, $estado]):
        ?>
            <div style="display:flex;align-items:center;gap:11px;padding:11px 13px;
                        background:var(--blanco);border:1px solid var(--borde);
                        border-left:4px solid <?= $estado === 'hecho' ? 'var(--verde)' : 'var(--azul)' ?>;
                        border-radius:var(--radio-chico)">
                <span style="font-size:1.4rem;flex:none"><?= $emoji ?></span>
                <span style="flex:1">
                    <b style="display:block;font-size:.82rem;color:var(--oscuro)"><?= e($quien) ?></b>
                    <small style="font-size:.74rem;color:var(--texto-tenue)"><?= e($que) ?></small>
                </span>
                <?php if ($estado === 'hecho'): ?>
                    <span style="color:var(--verde);font-size:.95rem">✓</span>
                <?php endif; ?>
            </div>
        <?php endforeach; ?>
    </div>

    <p style="text-align:center;font-size:.73rem;color:var(--texto-tenue);margin:12px 0 0">
        Usted no tiene que entrar curso por curso. Solo mirar cuando quiera.
    </p>
</div>
