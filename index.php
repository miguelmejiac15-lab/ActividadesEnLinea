<?php
/**
 * index.php — Página de inicio
 *
 * Esta página es un escaparate, no un catálogo. Responde cuatro
 * preguntas y nada más: qué es, para quién, cómo funciona y cuánto
 * cuesta.
 *
 * Antes mostraba además ocho actividades destacadas, cuatro novedades y
 * las ocho categorías: veinte tarjetas compitiendo entre sí, y ninguna
 * de ellas útil para alguien que todavía no sabe qué es la plataforma.
 * Explorar el catálogo tiene su propia página, y quien ya entró tiene su
 * propio espacio.
 *
 * Quien ya inició sesión no necesita el escaparate: se le lleva
 * directamente a su espacio.
 */

require_once __DIR__ . '/config/config.php';

if (haySesion() && usuarioActual()) {
    redirigir('usuario/');
}

$categorias = categoriasConConteo();
$planes     = planesActivos();
$total      = totalActividades();

$totalEstaciones = (int) traerValor(
    'SELECT COUNT(1) FROM activity_stations s
       JOIN activities a ON a.id = s.activity_id
      WHERE a.status = "published"'
);

$titulo      = 'Actividades en Línea · Aprender también puede ser una aventura';
$descripcion = 'Explora actividades interactivas, retos y experiencias diseñadas para aprender haciendo. '
             . $total . ' actividades listas para usar, y creciendo.';
$seccionActiva = 'inicio';

require RUTA_INCLUDES . '/cabecera.php';
?>

<!-- ═══ 1. QUÉ ES ═════════════════════════════════════════════════ -->
<section class="hero">
    <span class="flotante f1" aria-hidden="true">⭐</span>
    <span class="flotante f2" aria-hidden="true">🚀</span>
    <span class="flotante f3" aria-hidden="true">🧩</span>
    <span class="flotante f4" aria-hidden="true">🎨</span>

    <div class="contenedor">
        <div class="hero-inner">

            <span class="etiqueta-hero">🎒 Una biblioteca que sigue creciendo</span>

            <h1>Aprender también puede ser <span class="resalte">una aventura</span></h1>

            <p class="entrada">
                Explora actividades interactivas, retos y experiencias diseñadas para
                aprender haciendo.
            </p>

            <div class="hero-acciones">
                <a class="btn btn-principal" href="<?= e(url('registro.php')) ?>">
                    Comenzar gratis
                </a>
                <a class="btn btn-secundario" href="<?= e(url('actividades/')) ?>">
                    Ver el catálogo
                </a>
            </div>

            <div class="hero-cifras">
                <div><b><?= (int) $total ?></b> actividades</div>
                <div><b><?= (int) $totalEstaciones ?></b> ejercicios guiados</div>
                <div><b>3–12</b> años</div>
            </div>

        </div>
    </div>
</section>


<!-- ═══ 2. PARA QUIÉN ES ══════════════════════════════════════════ -->
<section class="seccion">
    <div class="contenedor">

        <div class="titulo-seccion">
            <small>Para quién es</small>
            <h2>Pensado para tres manos distintas</h2>
        </div>

        <div class="acciones-grid" style="grid-template-columns:repeat(auto-fit,minmax(250px,1fr))">

            <div class="accion" style="text-align:left;padding:28px 24px">
                <span class="ico" aria-hidden="true">🧒</span>
                <b>Niñas y niños</b>
                <p style="font-size:.9rem;color:var(--texto-tenue);margin-top:7px;line-height:1.5">
                    Cada actividad avanza por estaciones cortas, con lectura en voz alta y
                    sin castigos por equivocarse.
                </p>
            </div>

            <div class="accion" style="text-align:left;padding:28px 24px">
                <span class="ico" aria-hidden="true">👩‍🏫</span>
                <b>Docentes</b>
                <p style="font-size:.9rem;color:var(--texto-tenue);margin-top:7px;line-height:1.5">
                    Actividades listas para usar, clasificadas por materia y edad. Sin preparar
                    nada antes de la clase.
                </p>
            </div>

            <div class="accion" style="text-align:left;padding:28px 24px">
                <span class="ico" aria-hidden="true">👨‍👩‍👧</span>
                <b>Familias</b>
                <p style="font-size:.9rem;color:var(--texto-tenue);margin-top:7px;line-height:1.5">
                    Para acompañar en casa lo que se ve en el colegio, al ritmo de cada niño.
                </p>
            </div>

        </div>

    </div>
</section>


<!-- ═══ 3. CÓMO FUNCIONA ══════════════════════════════════════════ -->
<!-- Aquí se explica el modelo freemium sin llamarlo así: se cuenta lo
     que el usuario va a vivir, no cómo lo llamamos nosotros. -->
<section class="seccion tenue">
    <div class="contenedor">

        <div class="titulo-seccion">
            <small>Cómo funciona</small>
            <h2>De la curiosidad al juego, en tres pasos</h2>
        </div>

        <div class="pasos">

            <div class="paso">
                <span class="paso-num">1</span>
                <div>
                    <b>Elige una actividad</b>
                    <p>
                        El catálogo está organizado por materia y, dentro de cada una, en
                        bloques: el Bosque de Vocales, el Reino de las Letras, Operaciones…
                        Puedes filtrar además por edad.
                    </p>
                </div>
            </div>

            <div class="paso">
                <span class="paso-num">2</span>
                <div>
                    <b>Juega estación por estación</b>
                    <p>
                        Cada actividad es un recorrido de ejercicios cortos: reconocer sonidos,
                        armar palabras, resolver operaciones, leer un cuento. Se avanza a su
                        propio ritmo.
                    </p>
                </div>
            </div>

            <div class="paso">
                <span class="paso-num">3</span>
                <div>
                    <b>Prueba antes de pagar</b>
                    <p>
                        Con la cuenta gratuita entras a <strong>todas</strong> las actividades y
                        juegas la primera parte de cada una. Cuando quieras seguir, la Biblioteca
                        Completa abre el resto.
                    </p>
                </div>
            </div>

        </div>

    </div>
</section>


<!-- ═══ 4. QUÉ HAY DENTRO ═════════════════════════════════════════ -->
<!-- Un vistazo, no el catálogo: un enlace por materia que da la medida
     de lo que hay, y una puerta única para verlo entero. -->
<?php
/*
 * El número de materias iba escrito a mano («Ocho materias»). Al crear
 * cuatro categorías nuevas el titular pasó a mentir sin que nadie lo
 * notara, porque el número de actividades sí se contaba solo. Ahora los
 * dos salen de la base de datos.
 */
$nombresNumero = [
    8 => 'Ocho', 9 => 'Nueve', 10 => 'Diez', 11 => 'Once', 12 => 'Doce',
    13 => 'Trece', 14 => 'Catorce', 15 => 'Quince',
];
$cuantasMaterias = count($categorias);
$materiasEnPalabras = $nombresNumero[$cuantasMaterias] ?? (string) $cuantasMaterias;
?>
<section class="seccion">
    <div class="contenedor">

        <div class="titulo-seccion">
            <small>Qué hay dentro</small>
            <h2><?= e($materiasEnPalabras) ?> materias, <?= (int) $total ?> actividades</h2>
        </div>

        <div class="materias">
            <?php foreach ($categorias as $c): ?>
                <a class="materia"
                   style="--acento:<?= e($c['color'] ?: '#29b6f6') ?>"
                   href="<?= e(url('actividades/?categoria=' . urlencode($c['slug']))) ?>">
                    <span class="materia-ico" aria-hidden="true"><?= e($c['icon']) ?></span>
                    <span class="materia-nombre"><?= e($c['name']) ?></span>
                    <span class="materia-conteo"><?= (int) $c['total'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>

        <div style="text-align:center;margin-top:30px">
            <a class="btn btn-principal" href="<?= e(url('actividades/')) ?>">
                Explorar el catálogo completo
            </a>
        </div>

    </div>
</section>


<!-- ═══ 5. PLANES ═════════════════════════════════════════════════ -->
<section class="seccion tenue">
    <div class="contenedor">

        <div class="titulo-seccion">
            <small>Planes</small>
            <h2>No compras actividades. Obtienes acceso a una biblioteca que crece.</h2>
            <p>Empieza gratis. Si te sirve, desbloquea todo por un año.</p>
        </div>

        <div class="rejilla-planes">
            <?php foreach ($planes as $p): ?>
                <?php
                $esGratis  = $p['slug'] === PLAN_FREE;
                $esEscuela = $p['slug'] === PLAN_ESCUELA;

                if ($esGratis) {
                    $enlace = url('registro.php');
                    $texto  = 'Comenzar gratis';
                } elseif ($esEscuela) {
                    $enlace = url('planes/#escuela');
                    $texto  = 'Solicitar información';
                } else {
                    $enlace = url('planes/suscribir.php?plan=' . urlencode($p['slug']));
                    $texto  = 'Obtener acceso';
                }
                ?>
                <div class="plan <?= (int) $p['is_recommended'] === 1 ? 'recomendado' : '' ?>">

                    <?php if ((int) $p['is_recommended'] === 1): ?>
                        <span class="cinta">★ EL MÁS ELEGIDO</span>
                    <?php endif; ?>

                    <div class="nivel"><?= e($p['tagline'] ?? '') ?></div>
                    <h3><?= e($p['name']) ?></h3>
                    <p class="lema"><?= e($p['description'] ?? '') ?></p>

                    <div class="precio">
                        <?php if ($esGratis): ?>
                            <span class="cifra">$0</span>
                            <span class="periodo">para siempre</span>
                        <?php elseif ($p['price_yearly_cop'] !== null): ?>
                            <span class="cifra"><?= e(precioCop((int) $p['price_yearly_cop'])) ?></span>
                            <span class="periodo">/ año</span>
                        <?php endif; ?>
                    </div>

                    <?php if (!$esGratis && $p['price_monthly_cop'] !== null): ?>
                        <p class="precio-alterno">o <?= e(precioCop((int) $p['price_monthly_cop'])) ?> / mes</p>
                    <?php endif; ?>

                    <?php if (!empty($p['ahorro'])): ?>
                        <span class="ahorro">
                            Ahorras <?= e(precioCop($p['ahorro']['monto'])) ?>
                            (<?= (int) $p['ahorro']['porcentaje'] ?>%)
                        </span>
                    <?php endif; ?>

                    <ul class="beneficios">
                        <?php foreach ($p['features'] as $f): ?>
                            <li><?= e($f) ?></li>
                        <?php endforeach; ?>
                    </ul>

                    <a class="btn <?= (int) $p['is_recommended'] === 1 ? 'btn-oro' : 'btn-secundario' ?> btn-bloque"
                       href="<?= e($enlace) ?>"><?= e($texto) ?></a>

                </div>
            <?php endforeach; ?>
        </div>

        <p style="text-align:center;margin-top:24px;font-size:.92rem;color:var(--texto-tenue)">
            ¿Dudas? <a href="<?= e(url('planes/')) ?>" style="color:var(--azul);font-weight:600">Mira el detalle de cada plan</a>
        </p>

    </div>
</section>

<?php require RUTA_INCLUDES . '/pie.php'; ?>
