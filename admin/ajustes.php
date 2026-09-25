<?php
/**
 * admin/ajustes.php — Ajustes generales
 *
 * Configuración que afecta a todo el sitio y que conviene poder cambiar
 * sin tocar código: nombre de marca, modo global del catálogo, cuántos
 * días se considera "nueva" una actividad.
 */

require_once dirname(__DIR__) . '/config/config.php';
require_once RUTA_INCLUDES . '/admin.php';

exigirRol('admin');

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    exigirCsrf();

    // Lista cerrada: solo se guardan estas claves, vengan las que vengan
    // en el formulario.
    $permitidas = [
        'sitio_nombre', 'sitio_testigo', 'catalogo_modo_global',
        'estaciones_libres_def', 'nuevas_actividades_dias', 'plan_recomendado',
        'exigir_cuenta_para_jugar',
        'ga_medicion_id', 'gsc_verificacion',
    ];

    foreach ($permitidas as $clave) {
        // Las casillas de verificación no se envían cuando están
        // desmarcadas: si no llega, es que se apagó.
        if ($clave === 'exigir_cuenta_para_jugar') {
            guardarAjuste($clave, empty($_POST[$clave]) ? '0' : '1');
            continue;
        }

        if (!array_key_exists($clave, $_POST)) {
            continue;
        }

        $valor = trim((string) $_POST[$clave]);

        // Validación por clave: un valor inválido aquí afecta a todo el sitio.
        if ($clave === 'catalogo_modo_global'
            && !in_array($valor, ['partial', 'all-free', 'all-locked'], true)) {
            continue;
        }
        if (in_array($clave, ['estaciones_libres_def', 'nuevas_actividades_dias'], true)) {
            $valor = (string) max(0, min(3650, (int) $valor));
        }
        if ($clave === 'plan_recomendado' && !planPorSlug($valor)) {
            continue;
        }

        /*
         * El identificador de GA4 tiene una forma fija: G- y de
         * ocho a doce caracteres. Aceptar cualquier cosa dejaria
         * una etiqueta rota en todas las paginas publicas, y el
         * sitio seguiria pareciendo medido sin estarlo.
         */
        if ($clave === 'ga_medicion_id' && $valor !== ''
            && !preg_match('/^G-[A-Z0-9]{8,12}$/i', $valor)) {
            mensaje('error', 'El identificador de Analytics debe tener la forma G-XXXXXXXXXX.');
            continue;
        }

        guardarAjuste($clave, $valor);
    }

    // El plan destacado vive en la tabla de planes, no solo en settings:
    // se sincroniza para que la página de precios no se contradiga.
    if (!empty($_POST['plan_recomendado']) && planPorSlug($_POST['plan_recomendado'])) {
        ejecutar('UPDATE plans SET is_recommended = 0');
        ejecutar('UPDATE plans SET is_recommended = 1 WHERE slug = ?', [$_POST['plan_recomendado']]);
    }

    mensaje('ok', 'Ajustes guardados.');
    redirigir('admin/ajustes.php');
}

$planes    = traerTodo('SELECT slug, name FROM plans WHERE is_active = 1 ORDER BY sort_order');
$modoHoy   = modoCatalogo();

$titulo    = 'Ajustes';
$panelZona = 'ajustes';

require __DIR__ . '/includes/cabecera-admin.php';
?>

<div class="encabezado-panel">
    <div>
        <h1>Ajustes</h1>
        <p>Configuración general del sitio.</p>
    </div>
</div>

<form method="post">
    <?= campoCsrf() ?>

    <div class="caja">
        <div class="cabeza"><h2>Marca</h2></div>
        <div class="cuerpo formulario">

            <div class="campo">
                <label for="sitio_nombre">Nombre del sitio</label>
                <input id="sitio_nombre" name="sitio_nombre" type="text" maxlength="120"
                       value="<?= e(ajuste('sitio_nombre', '')) ?>">
            </div>

            <div class="campo">
                <label for="sitio_testigo">Testigo de marca</label>
                <input id="sitio_testigo" name="sitio_testigo" type="text" maxlength="200"
                       value="<?= e(ajuste('sitio_testigo', '')) ?>">
                <p class="ayuda">Aparece bajo el logo, en el pie y en las páginas de formulario.</p>
            </div>

        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Catálogo y modelo freemium</h2></div>
        <div class="cuerpo formulario">

            <div class="campo">
                <label>Modo global del catálogo</label>
                <div class="opciones-acceso">

                    <label class="opcion-acceso">
                        <input type="radio" name="catalogo_modo_global" value="partial"
                               <?= $modoHoy === 'partial' ? 'checked' : '' ?>>
                        <span>
                            <b>🎯 Normal (freemium)</b>
                            <span>Cada actividad se comporta según su propia configuración de acceso. Es el modo habitual.</span>
                        </span>
                    </label>

                    <label class="opcion-acceso">
                        <input type="radio" name="catalogo_modo_global" value="all-free"
                               <?= $modoHoy === 'all-free' ? 'checked' : '' ?>>
                        <span>
                            <b>🎁 Todo abierto</b>
                            <span>
                                Se ignoran los candados y todo el catálogo queda libre.
                                Útil para una campaña, una feria o una demostración.
                            </span>
                        </span>
                    </label>

                    <label class="opcion-acceso">
                        <input type="radio" name="catalogo_modo_global" value="all-locked"
                               <?= $modoHoy === 'all-locked' ? 'checked' : '' ?>>
                        <span>
                            <b>🔒 Todo cerrado</b>
                            <span>
                                Nada es gratuito. Quien tenga suscripción vigente sigue entrando:
                                a un cliente que pagó nunca se le cierra la puerta.
                            </span>
                        </span>
                    </label>

                </div>
            </div>

            <hr class="separador">

            <div class="campo">
                <label class="opcion-acceso">
                    <input type="checkbox" name="exigir_cuenta_para_jugar" value="1"
                           <?= ajuste('exigir_cuenta_para_jugar', '1') === '1' ? 'checked' : '' ?>>
                    <span>
                        <b>👤 Exigir cuenta para jugar</b>
                        <span>
                            El catálogo y las fichas siguen siendo públicos: cualquiera puede
                            mirar qué hay. Lo que pide cuenta es <b>empezar a jugar</b>.
                            Sin cuenta no hay dónde guardar el progreso, así que el niño
                            repetiría estaciones y perdería sus estrellas al cerrar el navegador.
                            Desactívalo solo para una demostración.
                        </span>
                    </span>
                </label>
            </div>

            <hr class="separador">

            <div class="pareja">
                <div class="campo">
                    <label for="estaciones_libres_def">Estaciones gratuitas por defecto</label>
                    <input id="estaciones_libres_def" name="estaciones_libres_def" type="number" min="0" max="255"
                           value="<?= e(ajuste('estaciones_libres_def', '2')) ?>">
                    <p class="ayuda">Valor sugerido al crear una actividad nueva.</p>
                </div>

                <div class="campo">
                    <label for="nuevas_actividades_dias">Días que una actividad es «nueva»</label>
                    <input id="nuevas_actividades_dias" name="nuevas_actividades_dias" type="number" min="1" max="365"
                           value="<?= e(ajuste('nuevas_actividades_dias', '60')) ?>">
                    <p class="ayuda">Durante ese tiempo lleva la etiqueta «Nuevo» en el catálogo.</p>
                </div>
            </div>

        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Medición con Google</h2></div>
        <div class="cuerpo formulario">

            <div class="aviso info" style="margin-bottom:14px">
                <b>Solo se mide la parte pública.</b>
                La etiqueta no se carga en el reproductor, ni en el aula, ni en el
                espacio del estudiante, ni en el panel del colegio: son páginas con
                menores, y sus datos no salen de aquí. Lo que se mide es de dónde
                llegan las familias y los docentes.
            </div>

            <div class="campo">
                <label for="ga_medicion_id">Identificador de Google Analytics</label>
                <input id="ga_medicion_id" name="ga_medicion_id" type="text" maxlength="20"
                       placeholder="G-XXXXXXXXXX"
                       value="<?= e(ajuste('ga_medicion_id', '')) ?>">
                <p class="ayuda">
                    Se copia de Analytics → Administrar → Flujos de datos.
                    Déjalo vacío para no medir nada.
                </p>
            </div>

            <div class="campo">
                <label for="gsc_verificacion">Verificación de Search Console</label>
                <input id="gsc_verificacion" name="gsc_verificacion" type="text" maxlength="120"
                       value="<?= e(ajuste('gsc_verificacion', '')) ?>">
                <p class="ayuda">
                    Solo el contenido de la etiqueta <code>google-site-verification</code>,
                    sin el HTML alrededor. No envía nada a Google: únicamente demuestra
                    que el dominio es tuyo.
                </p>
            </div>

            <?php
            /*
             * El estado REAL, calculado, y no lo que diga un ajuste.
             *
             * En desarrollo la medición está apagada a propósito, y sin
             * decirlo aquí alguien pegaría el identificador, no vería
             * datos y pensaría que está roto.
             */
            ?>
            <p class="ayuda">
                <b>Estado ahora mismo:</b>
                <?php if (gaMedicionId() === ''): ?>
                    sin identificador, no se mide nada.
                <?php elseif (ES_DESARROLLO): ?>
                    configurado, pero <b>apagado</b> porque este entorno es de desarrollo.
                    En producción medirá.
                <?php else: ?>
                    midiendo las páginas públicas.
                <?php endif; ?>
                El mapa del sitio se publica en
                <a href="<?= e(url('sitemap.xml')) ?>" target="_blank" rel="noopener">/sitemap.xml</a>.
            </p>
        </div>
    </div>

    <div class="caja">
        <div class="cabeza"><h2>Comercial</h2></div>
        <div class="cuerpo formulario">

            <div class="campo">
                <label for="plan_recomendado">Plan destacado</label>
                <select id="plan_recomendado" name="plan_recomendado" style="max-width:320px">
                    <?php foreach ($planes as $p): ?>
                        <option value="<?= e($p['slug']) ?>"
                            <?= ajuste('plan_recomendado', 'biblioteca') === $p['slug'] ? 'selected' : '' ?>>
                            <?= e($p['name']) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <p class="ayuda">
                    Se muestra resaltado en la página de precios y en la home.
                    La intención comercial es favorecer la suscripción anual.
                </p>
            </div>

        </div>
    </div>

    <div class="pie-formulario">
        <button class="btn btn-principal" type="submit">Guardar ajustes</button>
        <a class="btn btn-secundario" href="<?= e(url('admin/')) ?>">Cancelar</a>
    </div>

</form>

<?php require __DIR__ . '/includes/pie-admin.php'; ?>
