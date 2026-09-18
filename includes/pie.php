<?php
/**
 * pie.php — Pie del sitio público
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}

$categoriasPie = categoriasConConteo();
?>

</main>

<footer class="pie">
    <div class="contenedor">

        <div class="pie-rejilla">

            <div class="pie-marca">
                <div class="marca-pie">
                    ✏️ <span class="a">ACTIVIDADES</span>
                    <span class="b">EN</span>
                    <span class="c">LÍNEA</span>
                </div>
                <p class="lema-pie"><?= e(ajuste('sitio_testigo', '')) ?></p>
                <p class="lema-pie" style="margin-top:10px">
                    Una biblioteca de experiencias de aprendizaje que crece constantemente.
                </p>
            </div>

            <div>
                <h4>Explorar</h4>
                <ul>
                    <li><a href="<?= e(url('actividades/')) ?>">Todas las actividades</a></li>
                    <li><a href="<?= e(url('actividades/?orden=nuevas')) ?>">Nuevas actividades</a></li>
                    <?php /* La guía para adultos: quien la busca, la busca aquí. */ ?>
                    <li><a href="<?= e(url('actividades/apoyos.php')) ?>">♿ Apoyos para aprender</a></li>
                    <li><a href="<?= e(url('planes/')) ?>">Planes y precios</a></li>
                </ul>
            </div>

            <div>
                <h4>Materias</h4>
                <ul>
                    <?php foreach (array_slice($categoriasPie, 0, 5) as $c): ?>
                        <li>
                            <a href="<?= e(url('actividades/?categoria=' . urlencode($c['slug']))) ?>">
                                <?= e($c['icon']) ?> <?= e($c['name']) ?>
                            </a>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div>
                <h4>Cuenta</h4>
                <ul>
                    <?php if (usuarioActual()): ?>
                        <li><a href="<?= e(url('usuario/')) ?>">Mi espacio</a></li>
                        <li><a href="<?= e(url('logout.php')) ?>">Cerrar sesión</a></li>
                    <?php else: ?>
                        <li><a href="<?= e(url('registro.php')) ?>">Crear cuenta gratis</a></li>
                        <li><a href="<?= e(url('login.php')) ?>">Iniciar sesión</a></li>
                    <?php endif; ?>
                </ul>
            </div>

        </div>

        <div class="pie-abajo">
            <span>© <?= date('Y') ?> <?= e(ajuste('sitio_nombre', 'Actividades en Línea')) ?></span>
            <span>Cuidamos los datos de niñas y niños desde el diseño.</span>
        </div>

    </div>
</footer>

</body>
</html>
