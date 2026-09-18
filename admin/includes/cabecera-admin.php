<?php
/**
 * cabecera-admin.php — Plantilla del panel administrativo
 *
 * La página que la incluye puede definir antes:
 *   $titulo     · título del navegador
 *   $panelZona  · 'inicio' | 'actividades' | 'categorias' | 'niveles'
 *                 | 'usuarios' | 'suscripciones' | 'metricas' | 'pagos'
 *                 | 'planes' | 'ajustes'
 *   $panelCss   · hojas de estilo extra, solo para esta pantalla
 *                 (ej. ['assets/css/panel-datos.css'])
 *
 * Nota: no comprueba el rol. Cada página del panel llama a
 * exigirRol('admin') por su cuenta, antes de cualquier consulta. Que la
 * puerta esté en la página y no en la plantilla evita que una página
 * nueva quede desprotegida por olvidar incluir esto.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}

$titulo    = $titulo    ?? 'Panel';
$panelZona = $panelZona ?? '';
$panelCss  = $panelCss  ?? [];
$adminActual = usuarioActual();

/*
 * Cuántos pagos esperan confirmación.
 *
 * Se consulta aquí, en la plantilla, y no en cada página: el aviso tiene
 * que verse desde cualquier pantalla del panel. Un pago pendiente es
 * dinero que ya entró al banco y acceso que el cliente todavía no tiene;
 * si solo se viera entrando a Cobros, se enteraría quien ya iba a mirar.
 *
 * La tabla puede no existir todavía (instalación anterior a la Fase 4),
 * así que el fallo se traga en silencio en vez de tumbar el panel entero.
 */
$pagosEsperando = 0;
try {
    $pagosEsperando = (int) traerValor('SELECT COUNT(*) FROM payments WHERE status = "pending"');
} catch (Throwable $e) {
    $pagosEsperando = 0;
}

/** Marca el enlace de la sección en la que estamos. */
function zonaActiva(string $zona): string
{
    return ($GLOBALS['panelZona'] ?? '') === $zona ? 'activo' : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?> · Panel</title>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/estilo.css')) ?>">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/admin.css')) ?>">
<?php foreach ($panelCss as $hoja): ?>
<link rel="stylesheet" href="<?= e(urlRecurso($hoja)) ?>">
<?php endforeach; ?>
</head>
<body class="panel">

<div class="panel-marco">

    <nav class="lateral" aria-label="Navegación del panel">

        <div class="marca">
            <span>✏️</span>
            <span>
                Panel
                <small><?= e($adminActual['name'] ?? '') ?></small>
            </span>
        </div>

        <div class="grupo">Contenido</div>
        <a class="<?= zonaActiva('inicio') ?>" href="<?= e(url('admin/')) ?>">
            <span class="ico" aria-hidden="true">📊</span> Resumen
        </a>
        <a class="<?= zonaActiva('actividades') ?>" href="<?= e(url('admin/actividades/')) ?>">
            <span class="ico" aria-hidden="true">🎯</span> Actividades
        </a>
        <a class="<?= zonaActiva('categorias') ?>" href="<?= e(url('admin/categorias/')) ?>">
            <span class="ico" aria-hidden="true">🏷️</span> Categorías
        </a>
        <a class="<?= zonaActiva('bloques') ?>" href="<?= e(url('admin/bloques/')) ?>">
            <span class="ico" aria-hidden="true">🗂️</span> Bloques
        </a>
        <a class="<?= zonaActiva('niveles') ?>" href="<?= e(url('admin/niveles/')) ?>">
            <span class="ico" aria-hidden="true">👶</span> Niveles
        </a>

        <div class="grupo">Personas</div>
        <?php if (colegiosInstalados()): ?>
            <a class="<?= zonaActiva('colegios') ?>" href="<?= e(url('admin/colegios/')) ?>">
                <span class="ico" aria-hidden="true">🏫</span> Colegios
            </a>
        <?php endif; ?>
        <a class="<?= zonaActiva('cursos') ?>" href="<?= e(url('admin/cursos/')) ?>">
            <span class="ico" aria-hidden="true">📚</span> Cursos
        </a>
        <a class="<?= zonaActiva('usuarios') ?>" href="<?= e(url('admin/usuarios/')) ?>">
            <span class="ico" aria-hidden="true">👥</span> Usuarios
        </a>

        <div class="grupo">Negocio</div>
        <a class="<?= zonaActiva('metricas') ?>" href="<?= e(url('admin/metricas/')) ?>">
            <span class="ico" aria-hidden="true">📈</span> Métricas
        </a>
        <a class="<?= zonaActiva('pagos') ?>" href="<?= e(url('admin/pagos/')) ?>">
            <span class="ico" aria-hidden="true">🧾</span> Cobros
            <?php if ($pagosEsperando > 0): ?>
                <span class="pendiente-aviso" title="<?= $pagosEsperando ?> pagos esperando confirmación">
                    <?= $pagosEsperando ?>
                </span>
            <?php endif; ?>
        </a>
        <?php
        /*
         * «Listo para cobrar» va en el menú y no escondido dentro de los
         * ajustes: la pregunta que se hace quien está montando esto no es
         * «¿qué ajuste toco?», es «¿por qué no entra dinero?», y hay diez
         * sitios donde puede faltar algo.
         */
        ?>
        <a class="<?= zonaActiva('listo') ?>" href="<?= e(url('admin/pagos/listo.php')) ?>">
            <span class="ico" aria-hidden="true">🚦</span> ¿Listo para cobrar?
        </a>
        <a class="<?= zonaActiva('suscripciones') ?>" href="<?= e(url('admin/suscripciones/')) ?>">
            <span class="ico" aria-hidden="true">💳</span> Suscripciones
        </a>
        <a class="<?= zonaActiva('planes') ?>" href="<?= e(url('admin/planes/')) ?>">
            <span class="ico" aria-hidden="true">🏷️</span> Planes y precios
        </a>

        <div class="grupo">Sistema</div>
        <a class="<?= zonaActiva('ajustes') ?>" href="<?= e(url('admin/ajustes.php')) ?>">
            <span class="ico" aria-hidden="true">⚙️</span> Ajustes
        </a>
        <?php
        /*
         * Si el correo no está configurado se marca: de él depende que
         * alguien pueda recuperar su contraseña sin que un administrador
         * se la cambie a mano, y es fácil no enterarse de que falta.
         */
        ?>
        <a class="<?= zonaActiva('correo') ?>" href="<?= e(url('admin/correo.php')) ?>">
            <span class="ico" aria-hidden="true">📮</span> Correo
            <?php if (!correoConfigurado()): ?>
                <span class="pendiente-aviso" title="El envío de correo no está configurado">!</span>
            <?php endif; ?>
        </a>

        <div class="salida">
            <a href="<?= e(url('index.php')) ?>">
                <span class="ico" aria-hidden="true">🌐</span> Ver el sitio
            </a>
            <a href="<?= e(url('logout.php')) ?>">
                <span class="ico" aria-hidden="true">🚪</span> Salir
            </a>
        </div>

    </nav>

    <main class="area">

        <?php foreach (mensajesPendientes() as $m): ?>
            <div class="aviso <?= e($m['tipo'] === 'error' ? 'mal' : $m['tipo']) ?>"><?= e($m['texto']) ?></div>
        <?php endforeach; ?>
