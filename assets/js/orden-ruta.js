/**
 * orden-ruta.js — Arrastrar para ordenar las actividades del curso
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ NO SE USA LA API DE ARRASTRE DEL NAVEGADOR
 * ─────────────────────────────────────────────────────────────────────
 *
 * `draggable="true"` y los eventos `dragstart`/`drop` son lo primero
 * que uno busca, y no sirven aquí: **no funcionan con el dedo**. En un
 * móvil o una tableta no se disparan, y buena parte de los docentes
 * arman el curso desde una tableta.
 *
 * Con Pointer Events el mismo código atiende ratón, dedo y lápiz. Es
 * algo más de trabajo y es el único que funciona en los tres sitios.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LAS FLECHAS SE QUEDAN
 * ─────────────────────────────────────────────────────────────────────
 *
 * Arrastrar no se puede hacer con el teclado, y quien navega con
 * teclado o con lector de pantalla se quedaría sin poder ordenar. Las
 * flechas no son un resto del pasado: son la otra mitad de la función.
 */

(function () {
    'use strict';

    const tabla = document.getElementById('tabla-ruta');
    const form  = document.getElementById('orden-ruta');

    if (!tabla || !form) {
        return;
    }

    const cuerpo = tabla.querySelector('tbody');

    // El asa y la pista solo aparecen si este archivo llegó a correr.
    // Sin JavaScript, una columna con ⠿ que no hace nada es una promesa
    // rota; así el docente ve exactamente lo que puede usar.
    tabla.querySelectorAll('td.asa').forEach((td) => { td.hidden = false; });

    const pista = document.getElementById('pista-arrastre');
    if (pista) { pista.hidden = false; }

    let fila = null;      // la que se está moviendo
    let creado = false;   // ¿ya hubo movimiento real?

    /* ── Empezar ─────────────────────────────────────────────────── */

    cuerpo.addEventListener('pointerdown', (ev) => {
        const asa = ev.target.closest('td.asa');

        if (!asa || ev.button !== 0) {
            return;
        }

        fila = asa.closest('tr');
        creado = false;

        // Captura: el puntero sigue mandando eventos aunque el dedo se
        // salga de la fila, que es lo que pasa siempre al arrastrar.
        asa.setPointerCapture(ev.pointerId);

        fila.classList.add('arrastrando');
        document.body.classList.add('arrastrando-fila');

        // Sin esto, en una tableta el gesto desplaza la página en vez de
        // mover la fila.
        ev.preventDefault();
    });

    /* ── Mover ───────────────────────────────────────────────────── */
    //
    // No se mueve un fantasma: se mueve la fila de verdad dentro de la
    // tabla, sobre la marcha. Así el docente ve el resultado final
    // mientras arrastra, sin tener que imaginarlo.

    cuerpo.addEventListener('pointermove', (ev) => {
        if (!fila) {
            return;
        }

        ev.preventDefault();

        const debajo = document.elementFromPoint(ev.clientX, ev.clientY);
        const otra   = debajo && debajo.closest('tr');

        if (!otra || otra === fila || otra.parentElement !== cuerpo) {
            return;
        }

        const caja = otra.getBoundingClientRect();
        const mitad = caja.top + caja.height / 2;

        // Antes o después según de qué lado del centro se soltó: sin
        // esto la fila se queda pegada en un sitio y cuesta colocarla.
        cuerpo.insertBefore(fila, ev.clientY < mitad ? otra : otra.nextSibling);

        creado = true;
    });

    /* ── Soltar ──────────────────────────────────────────────────── */

    function soltar() {
        if (!fila) {
            return;
        }

        fila.classList.remove('arrastrando');
        document.body.classList.remove('arrastrando-fila');
        fila = null;

        renumerar();

        // Solo se guarda si de verdad cambió algo. Un clic sin mover no
        // tiene por qué recargar la página.
        if (creado) {
            guardar();
        }
    }

    cuerpo.addEventListener('pointerup', soltar);
    cuerpo.addEventListener('pointercancel', soltar);

    /* ── Los números de la izquierda ─────────────────────────────── */
    //
    // Se renumeran en el acto. Si se quedaran con el orden viejo hasta
    // recargar, la tabla se contradiría a sí misma justo en el momento
    // en que el docente está comprobando que quedó bien.

    function renumerar() {
        cuerpo.querySelectorAll('tr').forEach((tr, i) => {
            const celda = tr.querySelector('.orden-num b');
            if (celda) { celda.textContent = i + 1; }
        });
    }

    /* ── Guardar ─────────────────────────────────────────────────── */

    function guardar() {
        // Se rehacen los campos ocultos en cada guardado: es más simple
        // que mantenerlos sincronizados, y son unas pocas decenas.
        form.querySelectorAll('input[name="orden[]"]').forEach((i) => i.remove());

        cuerpo.querySelectorAll('tr').forEach((tr) => {
            const campo = document.createElement('input');
            campo.type  = 'hidden';
            campo.name  = 'orden[]';
            campo.value = tr.dataset.id;
            form.appendChild(campo);
        });

        form.submit();
    }
})();
