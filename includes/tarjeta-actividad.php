<?php
/**
 * tarjeta-actividad.php — Tarjeta de actividad reutilizable
 *
 * Se usa en el catálogo, en el espacio personal y en las relacionadas.
 * Una sola definición: si cambia la tarjeta, cambia en todo el sitio.
 *
 * Uso:  $act = $fila;  require RUTA_INCLUDES . '/tarjeta-actividad.php';
 *
 * IMPORTANTE — sobre los nombres de las variables
 *
 * Un `require` comparte el ámbito de quien lo llama: cualquier variable
 * que se cree aquí sobrescribe la del archivo que incluye la tarjeta.
 * Eso ya rompió una página: aquí se usaba `$acceso` y la página que
 * pintaba las tarjetas tenía su propio `$acceso` con el resumen del
 * plan; tras la primera tarjeta valía otra cosa y la página moría.
 *
 * Por eso todas las variables de este archivo llevan el prefijo
 * `tarjeta_`. La única que se lee de fuera es `$act`.
 */

if (!defined('RUTA_RAIZ') || !isset($act)) {
    return;
}

$tarjeta_acceso   = accesoActividad($act);
$tarjeta_etiqueta = etiquetaAcceso($act);
$tarjeta_nueva    = esNueva($act['published_at'] ?? null);
$tarjeta_url      = url('actividades/ver.php?a=' . urlencode($act['slug']));

// El botón dice lo que el visitante puede hacer de verdad. Sin cuenta,
// lo primero que va a pasar es que se le pida crearla: mejor que lo sepa
// antes de hacer clic que después.
if ($tarjeta_acceso === ACCESO_BLOQUEADO) {
    $tarjeta_boton = 'Desbloquear actividad';
} elseif (exigeCuentaParaJugar() && !usuarioActual()) {
    $tarjeta_boton = 'Crear cuenta para jugar';
} else {
    $tarjeta_boton = 'Iniciar actividad';
}

// Progreso, cuando la tarjeta viene de una consulta que lo trae.
$tarjeta_hechas     = isset($act['hechas'])     ? (int) $act['hechas']     : null;
$tarjeta_estaciones = isset($act['estaciones']) ? (int) $act['estaciones'] : null;
?>
<article class="tarjeta cat-<?= e($act['categoria_slug'] ?? '') ?>">

    <a href="<?= e($tarjeta_url) ?>" aria-label="<?= e($act['title']) ?>">
        <div class="tarjeta-cara" style="<?= !empty($act['categoria_color'])
            ? 'background:' . e($act['categoria_color']) . '14' : '' ?>">
            <?php
            /*
             * Si a una actividad le falta el ícono, la tarjeta cae al de
             * su materia antes que a un genérico. Un 🎯 no dice nada; el
             * ✏️ de Aventura de las Letras al menos dice de qué va.
             * (Pasó con «Aventura de la M», que se quedó sin ícono.)
             */
            $tarjeta_icono = $act['icon'] ?: ($act['categoria_icon'] ?? '') ?: '🎯';
            ?>
            <span aria-hidden="true"><?= e($tarjeta_icono) ?></span>

            <?php if ($tarjeta_nueva): ?>
                <span class="insignia-nueva">Nuevo</span>
            <?php endif; ?>

            <span class="acceso <?= e($tarjeta_etiqueta['clase']) ?>">
                <?= e($tarjeta_etiqueta['icono'] . ' ' . $tarjeta_etiqueta['texto']) ?>
            </span>
        </div>
    </a>

    <div class="tarjeta-cuerpo">

        <div class="tarjeta-meta">
            <?php if (!empty($act['categoria'])): ?>
                <span class="meta"><?= e($act['categoria_icon']) ?> <?= e($act['categoria']) ?></span>
            <?php endif; ?>
            <?php if (!empty($act['nivel'])): ?>
                <span class="meta">👶 <?= e($act['nivel']) ?></span>
            <?php endif; ?>
            <?php if (!empty($act['duration_minutes'])): ?>
                <span class="meta">⏱ <?= (int) $act['duration_minutes'] ?> min</span>
            <?php endif; ?>
        </div>

        <h3><a href="<?= e($tarjeta_url) ?>"><?= e($act['title']) ?></a></h3>

        <p><?= e($act['description'] ?? '') ?></p>

        <?php if ($tarjeta_hechas !== null && $tarjeta_estaciones > 0): ?>
            <div class="tarjeta-progreso">
                <div class="tarjeta-progreso-barra">
                    <div style="width:<?= (int) round($tarjeta_hechas * 100 / $tarjeta_estaciones) ?>%"></div>
                </div>
                <span><?= $tarjeta_hechas ?> de <?= $tarjeta_estaciones ?> estaciones</span>
            </div>
        <?php endif; ?>

        <a class="btn <?= $tarjeta_acceso === ACCESO_BLOQUEADO ? 'btn-oro' : 'btn-principal' ?> btn-chico btn-bloque"
           href="<?= e($tarjeta_url) ?>"><?= e($tarjeta_boton) ?></a>

    </div>
</article>
<?php
// Se limpian las variables propias para no dejar rastro en el ámbito de
// quien incluyó la tarjeta.
unset($tarjeta_acceso, $tarjeta_etiqueta, $tarjeta_nueva, $tarjeta_url,
      $tarjeta_boton, $tarjeta_hechas, $tarjeta_estaciones, $tarjeta_icono);
