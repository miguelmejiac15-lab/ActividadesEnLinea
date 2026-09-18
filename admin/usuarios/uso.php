<?php
/**
 * admin/usuarios/uso.php — Informe de uso de una persona
 *
 * Responde tres preguntas y en este orden, que es el orden en que las
 * hace quien mira:
 *
 *   1. ¿Está entrando?          visitas, días activos, última vez
 *   2. ¿Cuánto rato se queda?   duración de las visitas
 *   3. ¿Está aprendiendo algo?  estaciones terminadas, materias, intentos
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LOS NÚMEROS SE EXPLICAN, NO SE SUELTAN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Cada cifra que puede malinterpretarse lleva su matiz al lado. En
 * concreto el tiempo: una visita se mide desde la primera petición hasta
 * la última, así que quien entra, hace una cosa y cierra cuenta CERO
 * minutos. Sin decirlo, alguien concluiría que ese niño no usa la
 * plataforma, cuando lo que pasa es que la usa a ráfagas.
 *
 * Un informe que se lee mal es peor que no tenerlo: con él se toman
 * decisiones sobre un niño.
 */

require_once dirname(__DIR__, 2) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

$id = getEntero('id');

if ($id <= 0 || !traerValor('SELECT id FROM users WHERE id = ?', [$id])) {
    mensaje('error', 'Esa cuenta no existe.');
    redirigir('admin/usuarios/');
}

$dias = getEntero('dias') ?: 30;
$dias = max(7, min(365, $dias));

$informe  = informeDeUso($id);
$u        = $informe['usuario'];
$v        = $informe['visitas'];
$j        = $informe['juego'];
$serie    = usoPorDia($id, $dias);
$visitas  = ultimasVisitas($id, 12);
$reciente = ultimoJuegoDe($id, 12);

$maxMin = max(1, max(array_column($serie, 'minutos')));

$titulo    = 'Uso · ' . $u['name'];
$panelZona = 'usuarios';
$panelCss  = ['assets/css/panel-datos.css'];

require dirname(__DIR__) . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Uso de <?= e($u['name']) ?></h1>
        <p>
            <?= e($u['email']) ?> ·
            se registró el <?= e(fechaLarga($u['created_at'])) ?>
        </p>
    </div>
    <div class="acciones">
        <a class="btn-mini" href="<?= e(url('admin/usuarios/ver.php?id=' . $id)) ?>">
            ← Volver a la ficha
        </a>
    </div>
</div>

<?php if (!usoInstalado()): ?>
    <div class="aviso mal">
        <b>Falta la tabla de visitas.</b> Corre
        <code>php database/migracion-cuentas-y-uso.php --aplicar</code>.
        Mientras tanto solo se ve lo que ya se guardaba del juego.
    </div>
<?php elseif ($v['visitas'] === 0): ?>
    <div class="aviso info">
        <b>Todavía no hay visitas registradas de esta persona.</b>
        Las visitas empezaron a contarse cuando se instaló esta función, así que lo
        anterior no aparece: no significa que no entrara antes.
    </div>
<?php endif; ?>


<!-- ── 1. ¿Está entrando? ──────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>¿Está entrando?</h2></div>
    <div class="cuerpo">

        <div class="cifras">
            <div class="cifra-caja">
                <div class="n"><?= (int) $v['visitas'] ?></div>
                <div class="t">Visitas</div>
            </div>
            <div class="cifra-caja verde">
                <div class="n"><?= (int) $v['dias_activos'] ?></div>
                <div class="t">Días distintos</div>
            </div>
            <div class="cifra-caja naranja">
                <div class="n"><?= (int) $v['hits'] ?></div>
                <div class="t">Peticiones</div>
            </div>
            <div class="cifra-caja morado">
                <div class="n" style="font-size:1.15rem">
                    <?= $v['ultima'] ? e(fechaLarga($v['ultima'])) : '—' ?>
                </div>
                <div class="t">Última vez</div>
            </div>
        </div>

        <?php if ($v['movil'] + $v['escritorio'] > 0): ?>
            <p class="aviso-suave" style="margin-top:16px">
                Entra sobre todo desde
                <b><?= $v['movil'] >= $v['escritorio'] ? 'móvil o tablet' : 'computador' ?></b>
                (<?= (int) $v['movil'] ?> móvil · <?= (int) $v['escritorio'] ?> escritorio).
            </p>
        <?php endif; ?>
    </div>
</div>


<!-- ── 2. ¿Cuánto rato se queda? ───────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>¿Cuánto rato se queda?</h2></div>
    <div class="cuerpo">

        <div class="cifras">
            <div class="cifra-caja">
                <div class="n"><?= (int) $v['minutos'] ?></div>
                <div class="t">Minutos en total</div>
            </div>
            <div class="cifra-caja verde">
                <div class="n"><?= (int) $v['media_minutos'] ?></div>
                <div class="t">Media por visita</div>
            </div>
            <div class="cifra-caja naranja">
                <div class="n"><?= (int) $j['minutos_jugados'] ?></div>
                <div class="t">Minutos jugando</div>
            </div>
        </div>

        <?php
        /*
         * El matiz que evita leer mal la tabla. Va debajo de las cifras y
         * no en letra pequeña al final: quien mira esto va a sacar
         * conclusiones sobre un niño.
         */
        ?>
        <p class="aviso-suave" style="margin-top:16px">
            <b>Cómo se mide.</b> Una visita va desde la primera petición hasta la última,
            y se cierra tras 30 minutos sin actividad. Quien entra, hace una cosa y cierra
            cuenta <b>cero minutos</b> —solo hubo un instante que medir—, así que el número
            de visitas dice tanto como el tiempo. «Minutos jugando» es distinto: lo cuenta
            el propio juego dentro de cada estación.
        </p>
    </div>
</div>


<!-- ── Día a día ───────────────────────────────────────────────────── -->

<div class="caja">
    <div class="cabeza">
        <h2>Día a día</h2>
        <div class="acciones">
            <?php foreach ([7, 30, 90] as $d): ?>
                <a class="btn-mini <?= $dias === $d ? 'solido' : '' ?>"
                   href="<?= e(url('admin/usuarios/uso.php?id=' . $id . '&dias=' . $d)) ?>">
                    <?= $d ?> días
                </a>
            <?php endforeach; ?>
        </div>
    </div>
    <div class="cuerpo">

        <?php $huboAlgo = array_sum(array_column($serie, 'minutos'))
                        + array_sum(array_column($serie, 'estaciones')) > 0; ?>

        <?php if (!$huboAlgo): ?>
            <p class="sin-datos" style="padding:20px 0">
                Sin actividad en los últimos <?= $dias ?> días.
            </p>
        <?php else: ?>

            <?php
            /*
             * Barras en HTML puro, sin librería. Todos los días del rango
             * aparecen, también los vacíos: sin los huecos, una gráfica
             * junta el lunes con el viernes y aparenta una constancia que
             * no hubo.
             */
            ?>
            <div class="grafica-uso" role="img"
                 aria-label="Minutos por día en los últimos <?= $dias ?> días">
                <?php foreach ($serie as $d): ?>
                    <?php $alto = (int) round($d['minutos'] / $maxMin * 100); ?>
                    <div class="barra-dia"
                         title="<?= e(date('d/m/Y', strtotime($d['fecha']))) ?>: <?= $d['minutos'] ?> min, <?= $d['visitas'] ?> visita(s), <?= $d['estaciones'] ?> estación(es)">
                        <span style="height:<?= max($d['minutos'] > 0 ? 3 : 0, $alto) ?>%"
                              class="<?= $d['estaciones'] > 0 ? 'jugo' : '' ?>"></span>
                    </div>
                <?php endforeach; ?>
            </div>

            <div class="leyenda-uso">
                <span><i class="pt jugo"></i> Días en que además terminó estaciones</span>
                <span><i class="pt"></i> Días en que solo entró</span>
                <span>Alto de la barra = minutos (máx. <?= $maxMin ?>)</span>
            </div>

        <?php endif; ?>
    </div>
</div>


<!-- ── 3. ¿Está aprendiendo algo? ──────────────────────────────────── -->

<div class="caja">
    <div class="cabeza"><h2>¿Está aprendiendo algo?</h2></div>
    <div class="cuerpo">

        <div class="cifras">
            <div class="cifra-caja verde">
                <div class="n"><?= (int) $j['actividades_hechas'] ?></div>
                <div class="t">Actividades completas</div>
            </div>
            <div class="cifra-caja">
                <div class="n"><?= (int) $j['estaciones_hechas'] ?></div>
                <div class="t">Estaciones terminadas</div>
            </div>
            <div class="cifra-caja naranja">
                <div class="n">⭐ <?= (int) $j['estrellas'] ?></div>
                <div class="t">Estrellas</div>
            </div>
            <div class="cifra-caja morado">
                <div class="n"><?= (int) $j['intentos'] ?></div>
                <div class="t">Intentos</div>
            </div>
        </div>

        <p class="aviso-suave" style="margin-top:16px">
            Tocó <b><?= (int) $j['actividades_tocadas'] ?></b> actividades y terminó
            <b><?= (int) $j['actividades_hechas'] ?></b> del todo.
            <?php if ($j['estaciones_hechas'] > 0): ?>
                Le costó
                <b><?= number_format($j['intentos'] / max(1, $j['estaciones_hechas']), 1, ',', '.') ?></b>
                intentos por estación de media — cerca de 1 es que va sobrado, muy alto es
                que el nivel le queda grande.
            <?php endif; ?>
        </p>

        <?php if ($informe['materias']): ?>
            <div class="separador" style="margin:20px 0"></div>
            <h3 style="font-size:.95rem;color:var(--oscuro);margin-bottom:12px">En qué materias</h3>

            <div class="tabla-envoltorio">
                <table class="tabla">
                    <thead>
                        <tr><th>Materia</th><th class="cifra">Estaciones</th><th class="cifra">Tiempo</th></tr>
                    </thead>
                    <tbody>
                    <?php foreach ($informe['materias'] as $m): ?>
                        <tr>
                            <td><?= e($m['icon'] . ' ' . $m['categoria']) ?></td>
                            <td class="cifra"><?= (int) $m['estaciones'] ?></td>
                            <td class="cifra"><?= e(duracionLegible((int) $m['segundos'])) ?></td>
                        </tr>
                    <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>


<!-- ── Detalle ─────────────────────────────────────────────────────── -->

<div class="trio" style="align-items:start">

    <div class="caja">
        <div class="cabeza"><h2>Últimas visitas</h2></div>
        <div class="cuerpo">
            <?php if (!$visitas): ?>
                <p class="sin-datos" style="padding:14px 0">Ninguna todavía.</p>
            <?php else: ?>
                <div class="tabla-envoltorio">
                    <table class="tabla">
                        <thead>
                            <tr><th>Cuándo</th><th>Duró</th><th class="cifra">Pet.</th><th></th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($visitas as $s): ?>
                            <tr>
                                <td class="compacta">
                                    <?= e(date('d/m/Y H:i', strtotime((string) $s['started_at']))) ?>
                                </td>
                                <td class="compacta"><?= e(duracionLegible((int) $s['segundos'])) ?></td>
                                <td class="cifra"><?= (int) $s['hits'] ?></td>
                                <td class="compacta" style="color:var(--texto-tenue)">
                                    <?= $s['device'] === 'movil' ? '📱' : ($s['device'] === 'escritorio' ? '💻' : '') ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Lo último que jugó</h2></div>
        <div class="cuerpo">
            <?php if (!$reciente): ?>
                <p class="sin-datos" style="padding:14px 0">Todavía no ha jugado nada.</p>
            <?php else: ?>
                <div class="tabla-envoltorio">
                    <table class="tabla">
                        <thead>
                            <tr><th>Actividad</th><th>Estación</th><th></th><th>Cuándo</th></tr>
                        </thead>
                        <tbody>
                        <?php foreach ($reciente as $p): ?>
                            <tr>
                                <td class="compacta">
                                    <?= e(($p['categoria_icon'] ?? '') . ' ' . $p['actividad']) ?>
                                </td>
                                <td class="compacta" style="color:var(--texto-tenue)">
                                    <?= (int) $p['position'] ?>. <?= e($p['estacion']) ?>
                                </td>
                                <td class="compacta">
                                    <?php if ($p['status'] === 'completed'): ?>
                                        <span class="distintivo verde">hecha</span>
                                    <?php else: ?>
                                        <span class="distintivo gris">a medias</span>
                                    <?php endif; ?>
                                </td>
                                <td class="compacta" style="color:var(--texto-tenue)">
                                    <?= e(date('d/m H:i', strtotime((string) $p['updated_at']))) ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        </div>
    </div>

</div>

<?php require dirname(__DIR__) . '/includes/pie-admin.php'; ?>
