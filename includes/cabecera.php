<?php
/**
 * cabecera.php — Cabecera del sitio público
 *
 * La página que la incluye puede definir antes:
 *   $titulo        · título del navegador
 *   $descripcion   · meta descripción
 *   $seccionActiva · 'inicio' | 'actividades' | 'planes' | 'cuenta'
 *   $hojasExtra    · hojas de estilo solo para esta página
 *                    (ej. ['assets/css/pago.css'])
 *
 * El menú móvil funciona con una casilla oculta y CSS, sin JavaScript:
 * así la navegación nunca depende de que un script cargue bien.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}

$titulo        = $titulo        ?? ajuste('sitio_nombre', 'Actividades en Línea');
$descripcion   = $descripcion   ?? 'Una biblioteca creciente de experiencias de aprendizaje interactivo listas para usar.';
$seccionActiva = $seccionActiva ?? '';
$hojasExtra    = $hojasExtra    ?? [];

$usuario = usuarioActual();
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?></title>
<meta name="description" content="<?= e($descripcion) ?>">
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/estilo.css')) ?>">
<?php foreach ($hojasExtra as $hoja): ?>
<link rel="stylesheet" href="<?= e(urlRecurso($hoja)) ?>">
<?php endforeach; ?>
<?= etiquetaAnalitica() ?>

<?php /*
 * El favicon, con el lapiz de la marca.
 *
 * Es el SVG y no un .ico a proposito: el manual de marca dice que fuera
 * de la web el simbolo es `lapiz.svg` y no el emoji, porque un emoji lo
 * dibuja el sistema de quien mira y sale distinto en cada equipo. Un
 * .ico rasterizado se veria borroso en las pantallas de hoy; el SVG se
 * adapta a cualquier tamano.
 *
 * `urlRecurso()` le pone la marca de tiempo del archivo: sin eso, el
 * navegador se queda anos con el favicon viejo.
 */ ?>
<link rel="icon" type="image/svg+xml" href="<?= e(urlRecurso("assets/marca/lapiz.svg")) ?>">
<link rel="apple-touch-icon" href="<?= e(urlRecurso("assets/marca/lapiz.svg")) ?>">
</head>
<body>

<a class="saltar" href="#contenido">Saltar al contenido</a>

<header class="cabecera">
    <div class="contenedor">
        <div class="cabecera-fila">

            <a class="logo" href="<?= e(url('index.php')) ?>">
                <span>✏️</span>
                <span>
                    <span class="a">ACTIVIDADES</span>
                    <span class="b">EN</span>
                    <span class="c">LÍNEA</span>
                    <small><?= e(ajuste('sitio_testigo', '')) ?></small>
                </span>
            </a>

            <input type="checkbox" id="menu-toggle" class="menu-toggle" hidden>
            <label class="menu-boton" for="menu-toggle" aria-label="Abrir menú">☰</label>

            <?php
            /*
             * El menú cambia según quién mira, y en ambos casos ofrece
             * pocos destinos.
             *
             * A quien no ha entrado no se le ofrece «Mi cuenta» ni
             * «Panel»: no puede usarlos. Y a quien ya entró no se le
             * repite «Inicio», porque su inicio es su espacio.
             */
            ?>
            <nav class="menu" aria-label="Navegación principal">

                <?php if ($usuario): ?>

                    <?php
                    /*
                     * El marcador no es un destino más del menú: es
                     * estado. Va delante y aparece en TODAS las páginas,
                     * porque un contador que solo se ve en su propia
                     * pantalla no motiva — el niño tiene que ver subir el
                     * número justo después de jugar.
                     *
                     * Las monedas y las estrellas ya se guardaban desde el
                     * primer día. Simplemente no se mostraban en ninguna
                     * parte: se recogían y se tiraban.
                     */
                    $marcador = billetera();
                    $rachaHoy = racha();
                    ?>
                    <a class="marcador" href="<?= e(url('usuario/tienda.php')) ?>"
                       title="Tu personaje, tus monedas y tu racha">

                        <?= personajeHtml($usuario, 'chico') ?>

                        <span class="marcador-dato">
                            <span aria-hidden="true">🪙</span>
                            <b><?= (int) $marcador['saldo'] ?></b>
                            <span class="oculto-visual">monedas</span>
                        </span>

                        <?php if ($rachaHoy['actual'] > 0): ?>
                            <span class="marcador-dato racha">
                                <span aria-hidden="true">🔥</span>
                                <b><?= (int) $rachaHoy['actual'] ?></b>
                                <span class="oculto-visual">días seguidos</span>
                            </span>
                        <?php endif; ?>
                    </a>

                    <?php
                    /*
                     * Un niño con ruta tiene UN destino, no tres.
                     *
                     * «Mi espacio» le redirige a la ruta y el catálogo
                     * también, así que enseñarle los tres enlaces sería
                     * ofrecerle tres puertas que dan al mismo sitio —y
                     * dos de ellas con nombres que no entiende.
                     */
                    ?>
                    <?php if (enRutaGuiada()): ?>

                        <a href="<?= e(url('usuario/ruta.php')) ?>"
                           class="<?= $seccionActiva === 'ruta' ? 'activo' : '' ?>">
                            🎯 Mis actividades
                        </a>

                    <?php else: ?>

                        <a href="<?= e(url('usuario/')) ?>"
                           class="<?= $seccionActiva === 'espacio' ? 'activo' : '' ?>">Mi espacio</a>

                        <a href="<?= e(url('actividades/')) ?>"
                           class="<?= $seccionActiva === 'actividades' ? 'activo' : '' ?>">Actividades</a>

                    <?php endif; ?>

                    <?php
                    /*
                     * «Escuela» solo aparece para quien puede usarla: un
                     * docente, un colegio, o quien tenga el plan que
                     * gestiona cursos. Enseñarle el enlace a una familia
                     * sería ofrecerle una puerta que se le cierra en la
                     * cara al tocarla.
                     */
                    ?>
                    <?php if (puedeEntrarAEscuela()): ?>
                        <a href="<?= e(url('escuela/')) ?>"
                           class="<?= $seccionActiva === 'escuela' ? 'activo' : '' ?>">🏫 Escuela</a>
                    <?php endif; ?>

                    <?php
                    /*
                     * A un niño no se le ofrece comprar. Nunca, tenga o
                     * no el catálogo completo: la cuenta la creó su
                     * docente, la decisión de pagar no es suya y el
                     * botón solo le da algo que tocar para nada.
                     */
                    ?>
                    <?php if (!tieneCatalogoCompleto() && !enRutaGuiada()): ?>
                        <a class="btn-menu" href="<?= e(url('planes/')) ?>">🔓 Desbloquear todo</a>
                    <?php endif; ?>

                <?php else: ?>

                    <a href="<?= e(url('actividades/')) ?>"
                       class="<?= $seccionActiva === 'actividades' ? 'activo' : '' ?>">Actividades</a>

                    <a href="<?= e(url('planes/')) ?>"
                       class="<?= $seccionActiva === 'planes' ? 'activo' : '' ?>">Planes</a>

                    <a href="<?= e(url('login.php')) ?>">Entrar</a>
                    <a class="btn-menu" href="<?= e(url('registro.php')) ?>">Comenzar gratis</a>

                <?php endif; ?>

            </nav>

        </div>
    </div>
</header>

<main id="contenido">

<?php foreach (mensajesPendientes() as $m): ?>
    <div class="contenedor" style="padding-top:18px">
        <div class="aviso <?= e($m['tipo'] === 'error' ? 'mal' : $m['tipo']) ?>"><?= e($m['texto']) ?></div>
    </div>
<?php endforeach; ?>
