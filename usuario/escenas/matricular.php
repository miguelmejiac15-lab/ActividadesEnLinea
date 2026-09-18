<?php
/**
 * escenas/matricular.php — Maquetas de la guía «Matricular estudiantes».
 *
 * Ids con prefijo `g-ma-`.
 *
 * Son maquetas, no la pantalla real: se dibujan aquí a propósito para que
 * la guía pueda enseñar el recorrido completo sin crear cuentas de verdad
 * ni depender de que el colegio ya tenga gente dentro.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}
?>

<!-- ── El curso todavía vacío ───────────────────────────────────── -->
<div data-escena="inicio">
    <div class="guia-pantalla">
        <h3>Transición A</h3>
        <p class="sub">Curso de Marta Gómez · 0 estudiantes</p>

        <div class="guia-fila" id="g-ma-alta">
            <span class="emoji">🧒</span>
            <span class="crece">
                <b>Dar de alta estudiantes</b>
                <small>Uno a uno, o pegando la lista del curso</small>
            </span>
            <span class="guia-boton chico suave">Abrir</span>
        </div>

        <div class="guia-fila">
            <span class="emoji">🔗</span>
            <span class="crece">
                <b>Vincular una cuenta que ya existe</b>
                <small>Si el niño ya jugaba en casa</small>
            </span>
        </div>
    </div>
</div>

<!-- ── Pegar la lista ───────────────────────────────────────────── -->
<div data-escena="lista" hidden>
    <div class="guia-pantalla">
        <h3>Dar de alta estudiantes</h3>
        <p class="sub">Escriba un nombre por línea</p>

        <span class="guia-tecleado" id="g-ma-nombres"
              data-vacio="Un nombre por línea">Un nombre por línea</span>

        <p class="sub" style="margin-top:10px">
            No se pide correo: un niño de cinco años no tiene, y exigirlo
            obligaría a inventarlo.
        </p>

        <div style="margin-top:12px">
            <span class="guia-boton" id="g-ma-crear">Crear las cuentas</span>
        </div>
    </div>
</div>

<!-- ── Las claves, una sola vez ─────────────────────────────────── -->
<div data-escena="claves" hidden>
    <div class="guia-pantalla">
        <h3>🔑 Anote estas contraseñas ahora</h3>
        <p class="sub">No se vuelven a mostrar: solo se guarda su huella</p>

        <div id="g-ma-claves">
            <div class="guia-fila">
                <span class="emoji">🧒</span>
                <span class="crece"><b>Ana Pérez</b><small>ana.perez</small></span>
                <span class="guia-chip">malusori</span>
            </div>
            <div class="guia-fila">
                <span class="emoji">🧒</span>
                <span class="crece"><b>Luis Acero</b><small>luis.acero</small></span>
                <span class="guia-chip">tanepico</span>
            </div>
            <div class="guia-fila">
                <span class="emoji">🧒</span>
                <span class="crece"><b>Sara Díaz</b><small>sara.diaz</small></span>
                <span class="guia-chip">duzabofa</span>
            </div>
        </div>

        <p class="sub" style="margin-top:10px">
            Son legibles a propósito, para poder dictarlas en voz alta sin
            confundir un uno con una ele.
        </p>
    </div>
</div>

<!-- ── El código de clase ───────────────────────────────────────── -->
<div data-escena="codigo" hidden>
    <div class="guia-pantalla">
        <h3>Transición A</h3>
        <p class="sub">3 estudiantes · listos para entrar</p>

        <div id="g-ma-codigo" class="guia-fila" style="justify-content:center">
            <span class="crece" style="text-align:center">
                <small>Código de clase</small>
                <b style="font-size:1.6rem;letter-spacing:.14em">Y66K7MR</b>
                <small>actividadesenlinea.com/aula</small>
            </span>
        </div>

        <p class="sub" style="margin-top:10px">
            Esto es lo que se escribe en el tablero. El niño entra, toca su
            nombre en la lista y ya está dentro.
        </p>
    </div>
</div>

<!-- ── Quién ve qué ─────────────────────────────────────────────── -->
<div data-escena="cierre" hidden>
    <div class="guia-pantalla">
        <h3>Y de aquí en adelante</h3>

        <div id="g-ma-cierre">
            <div class="guia-fila">
                <span class="emoji">👩‍🏫</span>
                <span class="crece">
                    <b>Marta</b>
                    <small>asigna las actividades y ve el avance de su curso</small>
                </span>
            </div>
            <div class="guia-fila">
                <span class="emoji">🧒</span>
                <span class="crece">
                    <b>Sus estudiantes</b>
                    <small>solo ven lo que Marta les puso</small>
                </span>
            </div>
            <div class="guia-fila">
                <span class="emoji">🏛️</span>
                <span class="crece">
                    <b>Usted</b>
                    <small>ve el colegio entero, sin entrar curso por curso</small>
                </span>
            </div>
        </div>
    </div>
</div>
