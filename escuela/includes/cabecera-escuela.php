<?php
/**
 * cabecera-escuela.php — Plantilla del área Escuela
 *
 * La página que la incluye puede definir antes:
 *   $titulo       · título del navegador
 *   $escuelaZona  · 'cursos' | 'curso' | 'estudiantes' | 'actividades' | 'progreso'
 *   $cursoActual  · fila del curso, si estamos dentro de uno
 *
 * Igual que en el panel de administración, **no comprueba permisos**:
 * cada página llama a `exigirEscuela()` por su cuenta antes de consultar
 * nada. Que la puerta esté en la página y no en la plantilla evita que una
 * página nueva quede abierta por olvidar incluir esto.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}

$titulo      = $titulo      ?? 'Escuela';
$escuelaZona = $escuelaZona ?? '';
$cursoActual = $cursoActual ?? null;
$docente     = usuarioActual();

function zonaEscuela(string $zona): string
{
    return ($GLOBALS['escuelaZona'] ?? '') === $zona ? 'activo' : '';
}
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?> · Escuela</title>
<?php /* Progreso de menores identificados: fuera de los buscadores. */ ?>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/estilo.css')) ?>">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/admin.css')) ?>">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/escuela.css')) ?>">

<?php
/*
 * Las guías se enlazan desde aquí con sus tarjetas, y esas tarjetas
 * necesitan `guia.css`. Se carga siempre en el panel y no condicionado a
 * una variable: el panel son nueve páginas, y una condición que hay que
 * acordarse de poner en nueve sitios acaba faltando en uno.
 */
?>
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/guia.css')) ?>">
</head>
<body class="panel">

<div class="panel-marco">

    <nav class="lateral" aria-label="Navegación de la escuela">

        <div class="marca">
            <span>🏫</span>
            <span>
                Escuela
                <small><?= e($docente['name'] ?? '') ?></small>
            </span>
        </div>

        <?php
        /*
         * Quien coordina una institución entra por aquí, no por «Mis
         * cursos»: normalmente no tiene ninguno propio y esa pantalla le
         * daba un «todavía no tienes ningún curso» que era un callejón.
         */
        ?>
        <?php $miInstitucion = administraInstitucion() ? institucionDeAdmin() : null; ?>

        <?php if ($miInstitucion): ?>
            <div class="grupo">Institución</div>
            <a class="<?= zonaEscuela('institucion') ?>" href="<?= e(url('escuela/institucion.php')) ?>">
                <span class="ico" aria-hidden="true">🏛️</span> <?= e($miInstitucion['name']) ?>
            </a>
        <?php endif; ?>

        <div class="grupo">Mis cursos</div>
        <a class="<?= zonaEscuela('cursos') ?>" href="<?= e(url('escuela/')) ?>">
            <span class="ico" aria-hidden="true">📚</span> Todos los cursos
        </a>

        <?php if ($cursoActual): ?>
            <div class="grupo"><?= e($cursoActual['name']) ?></div>
            <a class="<?= zonaEscuela('curso') ?>"
               href="<?= e(url('escuela/curso.php?id=' . (int) $cursoActual['id'])) ?>">
                <span class="ico" aria-hidden="true">📋</span> Resumen
            </a>
            <a class="<?= zonaEscuela('estudiantes') ?>"
               href="<?= e(url('escuela/estudiantes.php?curso=' . (int) $cursoActual['id'])) ?>">
                <span class="ico" aria-hidden="true">🧒</span> Estudiantes
            </a>
            <a class="<?= zonaEscuela('actividades') ?>"
               href="<?= e(url('escuela/actividades.php?curso=' . (int) $cursoActual['id'])) ?>">
                <span class="ico" aria-hidden="true">🎯</span> Actividades
            </a>
            <a class="<?= zonaEscuela('progreso') ?>"
               href="<?= e(url('escuela/progreso.php?curso=' . (int) $cursoActual['id'])) ?>">
                <span class="ico" aria-hidden="true">📈</span> Progreso
            </a>

            <?php
            /*
             * La dirección con la que entran los niños, siempre a la vista.
             *
             * Estaba solo dentro de «Estudiantes» y ahí no se encuentra:
             * es lo que un docente busca a diario —para escribirlo en el
             * tablero cada mañana— y lo buscaba en «Progreso», que es
             * donde está mirando cuando le hace falta.
             */
            ?>
            <?php if (aulaInstalada()): ?>
                <a class="<?= zonaEscuela('acceso') ?>"
                   href="<?= e(url('escuela/acceso.php?curso=' . (int) $cursoActual['id'])) ?>">
                    <span class="ico" aria-hidden="true">🔑</span> Acceso
                </a>
            <?php endif; ?>

            <?php
            /*
             * La dirección con la que entran los niños, y si la clase está
             * abierta ahora. Las dos cosas son lo que un docente mira cada
             * mañana, así que van en el menú y no dentro de una pantalla.
             */
            ?>
            <?php if (aulaInstalada() && usaLista($cursoActual)
                      && !empty($cursoActual['access_code'])): ?>
                <?php $abiertaAhora = claseAbierta($cursoActual); ?>
                <div class="codigo-lateral <?= $abiertaAhora ? '' : 'cerrada' ?>">
                    <span class="codigo-eti">
                        <?= $abiertaAhora ? 'Clase abierta' : 'Clase cerrada' ?>
                    </span>
                    <a class="codigo-valor"
                       href="<?= e(urlDeAula((string) $cursoActual['access_code'])) ?>"
                       target="_blank" rel="noopener"
                       title="Abrir la lista como la ven ellos">
                        <?= e($cursoActual['access_code']) ?>
                    </a>
                    <span class="codigo-url"><?= e(urlDeAula((string) $cursoActual['access_code'])) ?></span>
                </div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="salida">
            <?php if (esAdmin()): ?>
                <a href="<?= e(url('admin/')) ?>">
                    <span class="ico" aria-hidden="true">⚙️</span> Panel de administración
                </a>
            <?php endif; ?>
            <a href="<?= e(url('actividades/')) ?>">
                <span class="ico" aria-hidden="true">🌐</span> Ver el catálogo
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
