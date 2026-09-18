<?php
/**
 * planes/_dos-puertas.php — Entrar o crear cuenta, para poder pagar.
 *
 * Lo pinta `suscribir.php` cuando quien llega no tiene sesión. No es una
 * página con dirección propia: el trozo de decisión vive dentro de la
 * dirección del plan para que un enlace de campaña siga funcionando sin
 * saber si quien lo abre es cliente.
 *
 * ---------------------------------------------------------------------
 *  LAS DOS PUERTAS PESAN LO MISMO
 * ---------------------------------------------------------------------
 *
 * Ninguna de las dos es «la principal» y ninguna se ofrece en letra
 * pequeña. Un cliente que vuelve y uno nuevo valen igual, y cada uno sabe
 * cuál es la suya sin tener que leer nada.
 *
 * Lo que sí va aparte y en voz baja es probar gratis: quien llegó hasta
 * aquí venía a pagar, y ponerle delante una alternativa gratuita del
 * mismo tamaño es quitarle la venta uno mismo. Pero esconderla del todo
 * sería peor — hay quien pulsa «Obtener acceso» solo para ver el precio.
 *
 * Espera `$plan`, `$mensual` y `$anual` de quien lo incluye.
 */

if (!defined('RUTA_RAIZ')) {
    exit('Acceso directo no permitido.');
}
?>

<section class="seccion">
    <div class="contenedor" style="max-width:660px">

        <nav class="migas" aria-label="Ruta de navegación">
            <a href="<?= e(url('planes/')) ?>">Planes</a>
            <span class="sep">›</span>
            <span><?= e($plan['name']) ?></span>
        </nav>

        <header style="text-align:center;margin-bottom:28px">
            <h1 style="color:var(--oscuro);font-size:1.7rem;margin-bottom:8px">
                Contratar <?= e($plan['name']) ?>
            </h1>

            <p style="color:var(--texto-tenue)">
                <?php if ($anual > 0): ?>
                    <b style="color:var(--oscuro);font-size:1.15rem"><?= e(precioCop($anual)) ?></b>
                    al año
                    <?php if ($mensual > 0): ?>
                        · o <?= e(precioCop($mensual)) ?> al mes
                    <?php endif; ?>
                <?php elseif ($mensual > 0): ?>
                    <b style="color:var(--oscuro);font-size:1.15rem"><?= e(precioCop($mensual)) ?></b>
                    al mes
                <?php endif; ?>
            </p>

            <p style="color:var(--texto-tenue);font-size:.93rem;margin-top:10px">
                El acceso se guarda en una cuenta, así que antes del pago hace falta una.
                Sigues aquí mismo, sin salir de esta ventana.
            </p>
        </header>

        <div class="dos-puertas">

            <?php
            /*
             * `?comprar=<plan>` es TODA la intención de compra.
             *
             * Va en el enlace y no en la sesión: así el registro y el
             * login saben que vienen de aquí, y cualquier otra forma de
             * llegar a esas dos pantallas es, sin más, el camino gratis.
             */
            $con = '?comprar=' . urlencode((string) $plan['slug']);
            ?>

            <a class="puerta" href="<?= e(url('login.php' . $con)) ?>">
                <span class="puerta-ico" aria-hidden="true">🔑</span>
                <b>Ya tengo cuenta</b>
                <span class="puerta-sub">Entras y sigues directo al pago.</span>
                <span class="puerta-btn">Entrar y pagar</span>
            </a>

            <a class="puerta" href="<?= e(url('registro.php' . $con)) ?>">
                <span class="puerta-ico" aria-hidden="true">✨</span>
                <b>Soy nuevo</b>
                <span class="puerta-sub">Creas la cuenta en un minuto y sigues al pago.</span>
                <span class="puerta-btn">Crear cuenta y pagar</span>
            </a>

        </div>

        <?php
        /*
         * La salida gratuita, a propósito en voz baja.
         *
         * Es `registro.php` a secas, sin parámetro: por eso queda limpia
         * sin tener que cancelar nada. Antes hacía falta un `?probar=1`
         * que BORRABA la compra apuntada en la sesión — y una marca que
         * hay que acordarse de borrar es una marca que algún día se
         * queda puesta.
         */
        ?>
        <p style="text-align:center;margin-top:26px;color:var(--texto-tenue);font-size:.92rem">
            ¿Solo quieres probar primero?
            <a href="<?= e(url('registro.php')) ?>"
               style="color:var(--azul);font-weight:600">
                Crea una cuenta gratis
            </a>
            y juega la primera parte de cada actividad, sin tarjeta.
        </p>

    </div>
</section>
