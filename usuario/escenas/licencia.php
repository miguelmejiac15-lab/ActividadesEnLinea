<?php
/**
 * escenas/licencia.php — Maquetas de la guía «La licencia y los cupos».
 *
 * Ids con prefijo `g-li-`.
 *
 * Esta guía existe porque es lo único del área del coordinador que falla
 * por el paso del tiempo y no por un clic: la licencia vence sola, y el
 * día que vence el colegio entero se queda fuera. Conviene que se sepa
 * antes, no ese día.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}
?>

<!-- ── La licencia vigente ──────────────────────────────────────── -->
<div data-escena="inicio">
    <div class="guia-pantalla">
        <h3>Colegio Piloto</h3>
        <p class="sub">Panel de institución</p>

        <div class="guia-fila" id="g-li-licencia">
            <span class="emoji">📅</span>
            <span class="crece">
                <b>Licencia vigente</b>
                <small>hasta el 30 de noviembre de 2026</small>
            </span>
            <span class="guia-chip">activa</span>
        </div>

        <div class="guia-fila" id="g-li-cupos">
            <span class="emoji">🎟️</span>
            <span class="crece">
                <b>Cupos</b>
                <small>124 de 200 usados</small>
            </span>
            <span class="guia-chip">76 libres</span>
        </div>
    </div>
</div>

<!-- ── Qué es un cupo ───────────────────────────────────────────── -->
<div data-escena="cupos" hidden>
    <div class="guia-pantalla">
        <h3>Qué ocupa un cupo</h3>

        <div id="g-li-queocupa">
            <div class="guia-fila">
                <span class="emoji">🧒</span>
                <span class="crece"><b>Cada estudiante</b><small>ocupa uno</small></span>
                <span class="guia-chip">sí</span>
            </div>
            <div class="guia-fila">
                <span class="emoji">👩‍🏫</span>
                <span class="crece"><b>Los profesores</b><small>no ocupan cupo</small></span>
                <span class="guia-chip suave">no</span>
            </div>
            <div class="guia-fila">
                <span class="emoji">🏛️</span>
                <span class="crece"><b>Usted</b><small>tampoco</small></span>
                <span class="guia-chip suave">no</span>
            </div>
        </div>

        <p class="sub" style="margin-top:10px">
            Así el colegio no gasta licencia en quien enseña, solo en quien
            aprende.
        </p>
    </div>
</div>

<!-- ── Sin cupos ────────────────────────────────────────────────── -->
<div data-escena="lleno" hidden>
    <div class="guia-pantalla">
        <h3>Dar de alta estudiantes</h3>

        <div id="g-li-lleno" class="guia-aviso">
            <b>No quedan cupos</b>
            <small>
                200 de 200 usados. Puede liberar cupos desvinculando
                estudiantes que ya no están en el colegio, o ampliar el plan.
            </small>
        </div>

        <p class="sub" style="margin-top:10px">
            Se avisa <b>antes</b> de crear las cuentas, no después: crear
            veinte y que entren doce sería peor que no crear ninguna.
        </p>
    </div>
</div>

<!-- ── Licencia vencida ─────────────────────────────────────────── -->
<div data-escena="vencida" hidden>
    <div class="guia-pantalla">
        <h3>Colegio Piloto</h3>

        <div id="g-li-vencida" class="guia-aviso">
            <b>La licencia no está vigente</b>
            <small>Venció el 30 de noviembre de 2026</small>
        </div>

        <div class="guia-fila">
            <span class="emoji">🧒</span>
            <span class="crece">
                <b>Sus estudiantes</b>
                <small>siguen entrando, pero solo a la parte gratuita</small>
            </span>
        </div>

        <div class="guia-fila">
            <span class="emoji">📊</span>
            <span class="crece">
                <b>El avance</b>
                <small>no se borra: espera a que se renueve</small>
            </span>
        </div>
    </div>
</div>

<!-- ── Renovar ──────────────────────────────────────────────────── -->
<div data-escena="renovar" hidden>
    <div class="guia-pantalla">
        <h3>Renovar</h3>

        <div id="g-li-renovar">
            <div class="guia-fila">
                <span class="emoji">➕</span>
                <span class="crece">
                    <b>El tiempo nuevo se suma</b>
                    <small>no reemplaza lo que le quede</small>
                </span>
            </div>
            <div class="guia-fila">
                <span class="emoji">🗓️</span>
                <span class="crece">
                    <b>Renovar antes no cuesta ese mes</b>
                    <small>por eso conviene no esperar al último día</small>
                </span>
            </div>
        </div>

        <p class="sub" style="margin-top:10px">
            Si el colegio paga por transferencia, avise con tiempo: la
            confirmación no es inmediata.
        </p>
    </div>
</div>
