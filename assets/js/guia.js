/* =====================================================================
   guia.js — Guías animadas: un recorrido del ratón por la pantalla

   POR QUÉ ESTO Y NO UN VIDEO

   Tres guías en video pesarían decenas de megas, y esta plataforma se
   usa en el celular de una familia con datos contados. Pero el motivo de
   verdad es otro: un video envejece mal. Cambia un botón de sitio y el
   video sigue enseñando el botón viejo, sin avisar — y regrabar cuesta
   una tarde. Esto se corrige editando una línea de texto.

   Lo que se ve NO es una captura: son los mismos componentes del sitio
   (`.tarjeta`, `.btn`, `.campo-simple`) dibujados en pequeño. Así una
   guía se parece a la pantalla de verdad porque COMPARTE su hoja de
   estilos, no porque alguien se acordó de actualizar una imagen.

   ---------------------------------------------------------------------
    EL GUION
   ---------------------------------------------------------------------

   Cada paso es un objeto:

       { escena: 'lista',            ← a qué pantalla saltar (opcional)
         texto: 'Toca Agregar…',     ← lo que se lee abajo
         a: '#btn-agregar',          ← a dónde va el ratón
         hacer: 'clic',              ← clic | escribir | marcar | mirar
         valor: 'letra m',           ← para «escribir»
         pausa: 900 }                ← ms extra al terminar

   Nada de esto sabe nada de las guías concretas: el guion llega como
   datos desde PHP.
   ===================================================================== */

(function () {
    'use strict';

    const raiz = document.querySelector('[data-guia]');

    if (!raiz || !window.GUIA) {
        return;
    }

    const guion   = window.GUIA.pasos || [];
    const escena  = raiz.querySelector('.guia-escena');
    const cursor  = raiz.querySelector('.guia-cursor');
    const pieTxt  = raiz.querySelector('.guia-pie-texto');
    const barra   = raiz.querySelector('.guia-avance-relleno');
    const contador= raiz.querySelector('.guia-contador');
    const btnPlay = raiz.querySelector('.guia-play');
    const btnAtras= raiz.querySelector('.guia-atras');
    const btnSig  = raiz.querySelector('.guia-siguiente');
    const lista   = raiz.querySelector('.guia-lista');

    let indice    = 0;
    let corriendo = false;
    let temporiza = null;

    /*
     * Quien pidió menos movimiento no ve ninguno.
     *
     * No se le da una versión peor: se le da la MISMA guía como lista de
     * pasos numerados, que para mucha gente es más útil que la animación
     * —se lee al ritmo de uno y se puede volver atrás con los ojos—.
     */
    const quieto = window.matchMedia
        && window.matchMedia('(prefers-reduced-motion: reduce)').matches;

    // =================================================================
    //  ESCENAS
    // =================================================================

    /** Muestra una pantalla y esconde las demás. */
    function ponerEscena(nombre) {
        if (!nombre) return;

        escena.querySelectorAll('[data-escena]').forEach((e) => {
            e.hidden = (e.dataset.escena !== nombre);
        });
    }

    /** Deja las escenas como estaban al empezar. */
    function reiniciarEscenas() {
        escena.querySelectorAll('[data-guia-limpiar]').forEach((e) => {
            e.hidden = true;
        });

        escena.querySelectorAll('.guia-tecleado').forEach((e) => {
            e.textContent = e.dataset.vacio || '';
            e.classList.remove('lleno');
        });

        escena.querySelectorAll('.guia-marca').forEach((e) => {
            e.classList.remove('marcada');
        });

        escena.querySelectorAll('.guia-resaltado').forEach((e) => {
            e.classList.remove('guia-resaltado');
        });
    }

    // =================================================================
    //  EL RATÓN
    // =================================================================

    /**
     * Lleva el cursor sobre un elemento.
     *
     * Las coordenadas se miden CONTRA LA ESCENA y no contra la ventana:
     * la guía se encoge en un teléfono, y con posiciones absolutas el
     * ratón acabaría señalando fuera de la caja.
     */
    function moverA(selector) {
        const destino = selector ? escena.querySelector(selector) : null;

        if (!destino) {
            return Promise.resolve(null);
        }

        const caja = destino.getBoundingClientRect();
        const marco = escena.getBoundingClientRect();

        const x = caja.left - marco.left + caja.width / 2;
        const y = caja.top - marco.top + caja.height / 2;

        cursor.style.transform = 'translate(' + x + 'px, ' + y + 'px)';
        destino.classList.add('guia-senalado');

        // El tiempo del viaje está en el CSS; aquí solo se espera.
        return esperar(quieto ? 0 : 620).then(() => {
            destino.classList.remove('guia-senalado');
            return destino;
        });
    }

    function esperar(ms) {
        return new Promise((r) => { temporiza = setTimeout(r, ms); });
    }

    // =================================================================
    //  LO QUE HACE EL RATÓN AL LLEGAR
    // =================================================================

    function hacerClic(el) {
        if (!el) return Promise.resolve();

        cursor.classList.add('pulsando');

        const onda = document.createElement('span');
        onda.className = 'guia-onda';

        const caja = el.getBoundingClientRect();
        const marco = escena.getBoundingClientRect();
        onda.style.left = (caja.left - marco.left + caja.width / 2) + 'px';
        onda.style.top  = (caja.top - marco.top + caja.height / 2) + 'px';

        escena.appendChild(onda);
        setTimeout(() => onda.remove(), 650);

        el.classList.add('guia-pulsado');

        return esperar(220).then(() => {
            cursor.classList.remove('pulsando');
            el.classList.remove('guia-pulsado');

            // Un clic puede revelar algo: lo dice el propio elemento.
            if (el.dataset.revela) {
                const abre = escena.querySelector(el.dataset.revela);
                if (abre) abre.hidden = false;
            }
        });
    }

    /** Escribe letra a letra, que es la mitad de la gracia. */
    function teclear(el, texto) {
        if (!el || !texto) return Promise.resolve();

        el.classList.add('lleno');

        if (quieto) {
            el.textContent = texto;
            return Promise.resolve();
        }

        el.textContent = '';

        return new Promise((listo) => {
            let i = 0;

            (function letra() {
                el.textContent = texto.slice(0, ++i);

                if (i >= texto.length) {
                    return listo();
                }

                temporiza = setTimeout(letra, 55);
            })();
        });
    }

    function marcar(el) {
        if (el) el.classList.add('marcada');
        return esperar(quieto ? 0 : 200);
    }

    function mirar(el) {
        if (el) el.classList.add('guia-resaltado');
        return esperar(quieto ? 0 : 400);
    }

    // =================================================================
    //  REPRODUCCIÓN
    // =================================================================

    function pintarEstado() {
        const total = guion.length;
        const paso  = guion[indice] || {};

        pieTxt.textContent = paso.texto || '';
        barra.style.width  = ((indice + 1) / total * 100) + '%';
        contador.textContent = (indice + 1) + ' de ' + total;

        if (lista) {
            lista.querySelectorAll('li').forEach((li, i) => {
                li.classList.toggle('ahora', i === indice);
            });
        }

        btnAtras.disabled = (indice === 0);
        btnSig.disabled   = (indice >= total - 1);
    }

    /** Ejecuta un paso entero y devuelve cuándo terminó. */
    function reproducirPaso(i) {
        const paso = guion[i];
        if (!paso) return Promise.resolve();

        indice = i;
        pintarEstado();

        if (paso.escena) {
            ponerEscena(paso.escena);
        }

        return moverA(paso.a).then((el) => {
            switch (paso.hacer) {
                case 'clic':     return hacerClic(el);
                case 'escribir': return hacerClic(el).then(() => teclear(el, paso.valor));
                case 'marcar':   return hacerClic(el).then(() => marcar(el));
                case 'mirar':    return mirar(el);
                default:         return Promise.resolve();
            }
        }).then(() => esperar(paso.pausa || (quieto ? 0 : 900)));
    }

    function siguiente() {
        if (!corriendo) return;

        if (indice >= guion.length - 1) {
            return terminar();
        }

        reproducirPaso(indice + 1).then(siguiente);
    }

    function arrancar() {
        if (corriendo) return pausar();

        // Si estaba al final, empieza de nuevo.
        if (indice >= guion.length - 1) {
            reiniciarEscenas();
            indice = -1;
        }

        corriendo = true;
        raiz.classList.add('corriendo');
        btnPlay.textContent = '❚❚ Pausar';
        btnPlay.setAttribute('aria-label', 'Pausar la guía');

        reproducirPaso(indice + 1).then(siguiente);
    }

    function pausar() {
        corriendo = false;
        clearTimeout(temporiza);
        raiz.classList.remove('corriendo');
        btnPlay.textContent = '▶ Reanudar';
        btnPlay.setAttribute('aria-label', 'Reanudar la guía');
    }

    function terminar() {
        corriendo = false;
        clearTimeout(temporiza);
        raiz.classList.remove('corriendo');
        raiz.classList.add('acabada');
        btnPlay.textContent = '↻ Verla otra vez';
        btnPlay.setAttribute('aria-label', 'Ver la guía otra vez');
    }

    /** Saltar a mano: pausa y reconstruye hasta ese paso. */
    function irA(i) {
        pausar();
        raiz.classList.remove('acabada');

        i = Math.max(0, Math.min(guion.length - 1, i));

        /*
         * Se rehace desde el principio en vez de saltar directo.
         *
         * Un paso depende de los anteriores —el campo que se escribió, la
         * casilla que se marcó— así que saltar al cuarto sin haber hecho
         * los tres primeros enseñaría una pantalla que nunca existió.
         * Se rehacen sin animación, que es instantáneo.
         */
        reiniciarEscenas();

        for (let k = 0; k <= i; k++) {
            const paso = guion[k];
            if (paso.escena) ponerEscena(paso.escena);

            const el = paso.a ? escena.querySelector(paso.a) : null;
            if (!el) continue;

            if (paso.hacer === 'escribir') {
                el.textContent = paso.valor || '';
                el.classList.add('lleno');
            } else if (paso.hacer === 'marcar') {
                el.classList.add('marcada');
            }

            if (el.dataset.revela) {
                const abre = escena.querySelector(el.dataset.revela);
                if (abre) abre.hidden = false;
            }
        }

        indice = i;
        pintarEstado();
        moverA(guion[i].a);
    }

    // =================================================================
    //  CONTROLES
    // =================================================================

    btnPlay.addEventListener('click', arrancar);
    btnAtras.addEventListener('click', () => irA(indice - 1));
    btnSig.addEventListener('click', () => irA(indice + 1));

    if (lista) {
        lista.querySelectorAll('li button').forEach((b, i) => {
            b.addEventListener('click', () => irA(i));
        });
    }

    // Con el teclado: espacio reproduce, flechas mueven.
    raiz.addEventListener('keydown', (ev) => {
        if (ev.target.tagName === 'BUTTON' && ev.key === ' ') return;

        if (ev.key === ' ')          { ev.preventDefault(); arrancar(); }
        if (ev.key === 'ArrowRight') { ev.preventDefault(); irA(indice + 1); }
        if (ev.key === 'ArrowLeft')  { ev.preventDefault(); irA(indice - 1); }
    });

    // Al cambiar de tamaño, el cursor se queda donde no era.
    window.addEventListener('resize', () => {
        if (!corriendo && guion[indice]) moverA(guion[indice].a);
    });

    // =================================================================
    //  ARRANQUE
    // =================================================================

    reiniciarEscenas();
    ponerEscena(guion[0] && guion[0].escena);
    pintarEstado();
    moverA(guion[0] && guion[0].a);

    if (quieto) {
        raiz.classList.add('sin-movimiento');
    }
})();
