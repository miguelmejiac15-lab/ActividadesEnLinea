<?php
/**
 * escenas/modo-nino.php — Las maquetas de la guía del modo niño.
 *
 * Cada `[data-escena]` es una pantalla. El guion de `includes/guias.php`
 * dice a cuál saltar y a qué elemento va el ratón.
 *
 * Los ids empiezan por `g-mn-` para no chocar con nada del sitio: esto
 * se pinta dentro de una página normal.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}
?>

<!-- ── Mi espacio ────────────────────────────────────────────────── -->
<div data-escena="inicio">
    <div class="guia-pantalla">
        <h3>Mi espacio</h3>
        <p class="sub">Tu cuenta, tu progreso y tus ajustes.</p>

        <div class="guia-fila">
            <span class="emoji">🧾</span>
            <span class="crece"><b>Mis pagos</b></span>
        </div>

        <div class="guia-fila" id="g-mn-entrar">
            <span class="emoji">🧒</span>
            <span class="crece">
                <b>Modo niño</b>
                <small>Elige qué ve tu hijo en esta cuenta</small>
            </span>
            <span class="guia-boton chico suave">Abrir</span>
        </div>

        <div class="guia-fila">
            <span class="emoji">🚪</span>
            <span class="crece"><b>Cerrar sesión</b></span>
        </div>
    </div>
</div>

<!-- ── Modo niño, sin nada elegido ───────────────────────────────── -->
<div data-escena="vacio" hidden>
    <div class="guia-pantalla">
        <h3>Modo niño</h3>
        <p class="sub">Elige qué actividades quieres dejarle a tu hijo.</p>

        <div id="g-mn-lista"
             style="padding:14px;background:var(--fondo-suave);border-radius:var(--radio-chico);
                    color:var(--texto-tenue);font-size:.78rem;margin-bottom:12px">
            Todavía no has elegido nada.
        </div>

        <b style="font-size:.8rem;color:var(--oscuro);display:block;margin-bottom:7px">
            Agregar una materia entera
        </b>

        <div style="display:flex;gap:6px;flex-wrap:wrap">
            <span class="guia-boton chico suave" id="g-mn-materia">🔤 Aventura de las Letras</span>
            <span class="guia-boton chico suave">🔢 Matemática</span>
            <span class="guia-boton chico suave">🔬 Ciencias</span>
        </div>
    </div>
</div>

<!-- ── Con la lista hecha ────────────────────────────────────────── -->
<div data-escena="conlista" hidden>
    <div class="guia-pantalla">
        <h3>Modo niño</h3>
        <p class="sub">Lo que verá tu hijo (3)</p>

        <div class="guia-fila">
            <span class="emoji">🦋</span>
            <span class="crece"><b>Aventura de la M</b><small>Preescolar · 16 ejercicios</small></span>
            <span class="guia-boton chico suave" id="g-mn-quitar">Quitar</span>
        </div>

        <div class="guia-fila">
            <span class="emoji">🌳</span>
            <span class="crece"><b>Bosque de Vocales</b><small>Preescolar · 12 ejercicios</small></span>
            <span class="guia-boton chico suave">Quitar</span>
        </div>

        <div class="guia-fila">
            <span class="emoji">📖</span>
            <span class="crece"><b>Aventura de la A</b><small>Preescolar · 14 ejercicios</small></span>
            <span class="guia-boton chico suave">Quitar</span>
        </div>

        <b style="font-size:.8rem;color:var(--oscuro);display:block;margin:14px 0 6px">
            PIN de cuatro números
        </b>

        <div style="display:flex;gap:8px;align-items:center">
            <span class="guia-tecleado" id="g-mn-pin"
                  data-vacio="····"
                  style="max-width:110px;text-align:center;letter-spacing:.35em">····</span>
            <span class="guia-boton chico suave" id="g-mn-guardar">Guardar PIN</span>
        </div>

        <div style="margin-top:16px;text-align:center">
            <span class="guia-boton" id="g-mn-encender">Encender el modo niño</span>
        </div>
    </div>
</div>

<!-- ── Encendido ─────────────────────────────────────────────────── -->
<div data-escena="encendido" hidden>
    <div class="guia-pantalla" style="text-align:center">
        <div style="font-size:2.2rem;line-height:1;margin-bottom:8px">🧒</div>

        <h3>El modo niño está encendido</h3>
        <p class="sub">En esta cuenta se ven 3 actividades.</p>

        <div id="g-mn-salir"
             style="max-width:180px;margin:0 auto;padding:12px;background:var(--fondo-suave);
                    border-radius:var(--radio-chico)">
            <small style="color:var(--texto-tenue);font-size:.72rem;display:block;margin-bottom:6px">
                PIN de cuatro números
            </small>
            <span class="guia-tecleado" style="text-align:center;letter-spacing:.35em">····</span>
            <span class="guia-boton chico" style="margin-top:8px;display:block">Salir del modo niño</span>
        </div>
    </div>
</div>

<?php
/*
 * ─────────────────────────────────────────────────────────────────────
 *  EL FINAL: LO QUE VE EL NIÑO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Sin esto la guía terminaba en «ya está encendido», que es el final del
 * TRÁMITE y no el de la historia. Quien la veía seguía sin saber lo
 * único que de verdad quería comprobar: qué le queda a su hijo delante.
 *
 * Por eso esta escena cambia de punto de vista a propósito — deja de ser
 * el panel del adulto y pasa a ser la pantalla del niño, con tarjetas
 * grandes y sin una sola palabra de configuración.
 */
?>
<!-- ── Lo que ve el niño ─────────────────────────────────────────── -->
<div data-escena="loQueVe" hidden>
    <div style="text-align:center;font-size:.72rem;letter-spacing:.09em;text-transform:uppercase;
                color:var(--texto-tenue);margin-bottom:9px">
        Así lo ve tu hijo
    </div>

    <div class="guia-pantalla" style="background:var(--fondo-suave)">
        <h3 style="text-align:center">¡Hola! 👋</h3>
        <p class="sub" style="text-align:center">Esto es lo que tienes para hoy</p>

        <div id="g-mn-suyas"
             style="display:grid;grid-template-columns:repeat(3,1fr);gap:8px">

            <?php
            $suyas = [
                ['🦋', 'Aventura de la M'],
                ['🌳', 'Bosque de Vocales'],
                ['📖', 'Aventura de la A'],
            ];

            foreach ($suyas as [$emoji, $nombre]):
            ?>
                <div style="background:var(--blanco);border:2px solid var(--borde);
                            border-radius:var(--radio-chico);padding:13px 8px;text-align:center">
                    <div style="font-size:1.9rem;line-height:1"><?= $emoji ?></div>
                    <b style="display:block;font-size:.7rem;color:var(--oscuro);margin-top:6px;
                              line-height:1.25"><?= e($nombre) ?></b>
                </div>
            <?php endforeach; ?>

        </div>

        <p style="text-align:center;font-size:.72rem;color:var(--texto-tenue);margin:12px 0 0">
            Y nada más. El resto del catálogo no le aparece.
        </p>
    </div>
</div>
