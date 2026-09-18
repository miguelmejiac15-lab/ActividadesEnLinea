<?php
/**
 * colegio/index.php — La puerta del colegio
 *
 * Una dirección propia por organización —`/colegio/piloto`,
 * `/colegio/san-jose`— que se reparte una vez y sirve todo el año.
 *
 * El slug sale de la fila del colegio: aquí no hay ningún nombre de
 * organización escrito en el código, y no debe haberlo. Añadir una
 * institución es crear una fila, no tocar este archivo.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO BASTA CON `/aula/CODIGO`
 * ─────────────────────────────────────────────────────────────────────
 *
 * El código de clase es por curso: cambia con cada grupo y hay que
 * escribirlo en el tablero cada vez. La puerta del colegio es una sola
 * para todos, y desde ella el niño ve **las clases abiertas ahora** y
 * elige la suya. Un niño de primero no se acuerda de un código; sí
 * reconoce el nombre de su clase.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE SE ENSEÑA Y LO QUE NO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Se enseñan **solo las clases abiertas en este momento**, con su nombre
 * y su docente. Nada de listas de estudiantes: para ver nombres hay que
 * entrar en una clase concreta, y esa clase la abre el docente.
 *
 * Fuera del horario de clase, la puerta no muestra ninguna: quien llegue
 * verá que no hay nada abierto y podrá entrar con su cuenta si la tiene.
 * Es la misma idea que abrir y cerrar el salón.
 */

require_once dirname(__DIR__) . '/config/config.php';

$slug    = trim((string) (get('c') ?: get('colegio')));
$colegio = $slug !== '' ? colegioPorSlug($slug) : null;

// Un colegio inactivo o sin licencia no abre. Se trata igual que uno que
// no existe: quien prueba direcciones no averigua cuáles hay.
if ($colegio && ($colegio['status'] !== 'active')) {
    $colegio = null;
}

$clases = $colegio ? clasesAbiertasDeColegio((int) $colegio['id']) : [];

$titulo = $colegio ? $colegio['name'] : 'Entrar a mi colegio';
?>
<!DOCTYPE html>
<html lang="es">
<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1">
<title><?= e($titulo) ?></title>
<?php /* Puerta de un colegio con menores dentro: fuera de los buscadores. */ ?>
<meta name="robots" content="noindex, nofollow">
<link href="https://fonts.googleapis.com/css2?family=Fredoka:wght@400;500;600;700&display=swap" rel="stylesheet">
<link rel="stylesheet" href="<?= e(urlRecurso('assets/css/aula.css')) ?>">
</head>
<body>

<header class="aula-cabeza">
    <span class="aula-logo">🎓 <?= e(ajuste('sitio_nombre', 'Actividades en Línea')) ?></span>
    <?php if ($colegio): ?>
        <span class="aula-clase"><?= e($colegio['name']) ?></span>
    <?php endif; ?>
</header>

<main class="aula-cuerpo">

<?php if (!$colegio): ?>

    <div class="tarjeta-codigo">
        <span class="cara-grande" aria-hidden="true">🏫</span>
        <h1>No encontramos ese colegio</h1>
        <p>Revisa la dirección que te dieron.</p>

        <p class="aula-pie">
            ¿Tienes correo y contraseña?
            <a href="<?= e(url('login.php')) ?>">Entra por aquí</a>
        </p>
    </div>

<?php elseif (!$clases): ?>

    <div class="tarjeta-codigo">
        <span class="cara-grande" aria-hidden="true">⏰</span>
        <h1>Ahora no hay clases abiertas</h1>
        <p>
            Tu profe abrirá la clase cuando empiece. Vuelve a intentarlo en un rato.
        </p>

        <form method="get">
            <input type="hidden" name="c" value="<?= e($colegio['slug']) ?>">
            <button type="submit" class="boton-grande">Volver a probar</button>
        </form>

        <p class="aula-pie">
            ¿Eres profe o tienes tu contraseña?
            <a href="<?= e(url('login.php')) ?>">Entra por aquí</a>
        </p>
    </div>

<?php else: ?>

    <div class="tarjeta-lista">
        <h1>Elige tu clase</h1>

        <ul class="lista-ninos">
            <?php foreach ($clases as $c): ?>
                <li>
                    <a class="fila-nino" href="<?= e(urlDeAula((string) $c['access_code'])) ?>">
                        <span class="cara" aria-hidden="true">📚</span>
                        <span class="nombre">
                            <?= e($c['name']) ?>
                            <?php if (!empty($c['docente'])): ?>
                                <small class="fila-sub"><?= e($c['docente']) ?></small>
                            <?php endif; ?>
                        </span>
                        <span class="jugar">ENTRAR</span>
                    </a>
                </li>
            <?php endforeach; ?>
        </ul>

        <p class="aula-pie">
            ¿Eres profe? <a href="<?= e(url('login.php')) ?>">Entra con tu correo</a>
        </p>
    </div>

<?php endif; ?>

</main>

</body>
</html>
