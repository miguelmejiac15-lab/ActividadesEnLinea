/* =====================================================================
   ACTIVIDADES EN LÍNEA · Motor de actividades
   motor.js

   Un solo motor para todas las actividades. Cada tipo de minijuego es
   una función registrada en JUEGOS; agregar un tipo nuevo es añadir una
   entrada, no crear una página.

   Sobre el contenido: esta página se carga SIN los datos de los
   minijuegos. Cuando el jugador abre una estación, se piden a
   api/estacion.php, que comprueba en el servidor si tiene derecho a
   jugarla. Manipular este archivo desde el navegador no desbloquea
   nada, porque lo bloqueado nunca llegó hasta aquí.
   ===================================================================== */

(function () {
    'use strict';

    // ── Estado ───────────────────────────────────────────────────────
    const estado = {
        estaciones: [],
        actual: null,
        paso: 0,
        aciertos: 0,
        intentos: 0,
        inicio: 0,
        base: null,
        csrf: '',
        slug: '',
        // Fallos en la estación que se está jugando. Se reinicia al abrir
        // cada una: la ayuda se ofrece por estación, no por sesión.
        fallos: 0,
        // El minijuego actual deja aquí cómo dar una pista, si sabe.
        pista: null,
        saldo: 0,
        // El personaje que el niño eligió en la tienda. Lo manda el
        // servidor al arrancar; sin sesión viene null.
        personaje: null,
    };

    /*
     * AYUDA CUANDO EL NIÑO SE ATASCA
     *
     * Dos salidas, y las dos cuestan monedas ganadas jugando:
     *
     *   PISTA   descarta una opción equivocada. Sigue teniendo que elegir.
     *   SALTAR  pasa a la siguiente estación, pero la registra SIN
     *           estrellas y SIN monedas. Desatasca; no regala mérito.
     *
     * Se ofrece al TERCER fallo, no al primero: equivocarse forma parte
     * de aprender, y una salida inmediata enseña a comprarla en vez de a
     * intentarlo.
     *
     * Los precios los manda el servidor; aquí solo se dibujan. Y el cobro
     * también lo decide el servidor: manipular esto no da monedas.
     */
    const AYUDA = {
        fallosParaOfrecer: 3,
        precioPista: 5,
        precioSaltar: 12,
    };

    // ── Utilidades ───────────────────────────────────────────────────

    const $ = (sel) => document.querySelector(sel);

    /** Escapa texto antes de meterlo en innerHTML. */
    function esc(t) {
        const d = document.createElement('div');
        d.textContent = t == null ? '' : String(t);
        return d.innerHTML;
    }

    /** Crea un elemento con clase, texto y atributos. */
    function el(tag, clase, texto) {
        const n = document.createElement(tag);
        if (clase) n.className = clase;
        if (texto != null) n.textContent = texto;
        return n;
    }

    /** Baraja una copia del arreglo (Fisher-Yates). */
    function barajar(arr) {
        const a = arr.slice();
        for (let i = a.length - 1; i > 0; i--) {
            const j = Math.floor(Math.random() * (i + 1));
            [a[i], a[j]] = [a[j], a[i]];
        }
        return a;
    }

    /*
     * Idioma con el que se lee en voz alta.
     *
     * Es español salvo que la estación diga otra cosa. Importa de verdad:
     * en las actividades de inglés, leer «cat» con voz española suena
     * «kat» y le enseña al niño justo lo contrario de lo que se busca.
     * Cada estación lo fija al cargarse.
     */
    let idiomaVoz = 'es-ES';

    /**
     * ¿Este niño todavía no lee?
     *
     * Lo dice el servidor con el nivel de la actividad (`sin_lectura`).
     * En preescolar el motor lee en voz alta la instrucción, las opciones
     * y el resultado, porque un niño de cinco años delante de un texto no
     * tiene forma de saber qué le están pidiendo.
     */
    let sinLectura = false;

    /*
     * ─────────────────────────────────────────────────────────────────
     *  LAS VOCES NO ESTÁN LISTAS AL PRINCIPIO
     * ─────────────────────────────────────────────────────────────────
     *
     * `getVoices()` devuelve una lista VACÍA en la primera llamada de la
     * página: el navegador las carga aparte y avisa después con el evento
     * `voiceschanged`. Con la lista vacía no se puede elegir voz, y el
     * navegador usa la suya por defecto — que en un equipo en inglés lee
     * «¿cuántas manzanas hay?» con acento inglés y no se entiende nada.
     *
     * Era el mismo fallo contra el que avisa el comentario del idioma,
     * pero al revés y en silencio: `v.lang` es una PREFERENCIA, no una
     * orden. Si además se elige la voz, deja de serlo.
     */
    let vocesListas = [];

    function cargarVoces() {
        if (!window.speechSynthesis) return;
        try {
            vocesListas = window.speechSynthesis.getVoices() || [];
        } catch (e) {
            vocesListas = [];
        }
    }

    if (window.speechSynthesis) {
        cargarVoces();
        window.speechSynthesis.addEventListener
            ? window.speechSynthesis.addEventListener('voiceschanged', cargarVoces)
            : (window.speechSynthesis.onvoiceschanged = cargarVoces);
    }

    /**
     * Quita los dibujos del texto ANTES de leerlo.
     *
     * Muchos enunciados empiezan con un emoji —«🍓 La fresa es…»— porque
     * en pantalla ayuda. En voz alta estorba: según el navegador, el
     * sintetizador lo lee con su nombre y en inglés («strawberry la fresa
     * es…») o suelta un silencio raro en mitad de la frase.
     *
     * El dibujo ya se ve. Lo que hay que decir son las palabras.
     */
    /**
     * La misma palabra sin tildes ni diéresis. La Ñ se conserva.
     *
     * Sirve para saber si dos palabras se diferencian SOLO en la tilde, y
     * poder decírselo al niño en vez de soltarle un «revisa las letras»
     * cuando todas las letras están bien.
     *
     * La Ñ no entra: no es una A con adorno, es otra letra, y confundir
     * AÑO con ANO no es un desliz de acentuación.
     */
    function sinTildes(texto) {
        const t = String(texto == null ? '' : texto);

        try {
            /*
             * Se separa la letra de su acento y se tira el acento.
             *
             * Van uno a uno y NO como rango: la tilde de la Ñ es U+0303 y
             * cae justo en medio de los acentos, así que un rango
             * U+0300–U+0304 convertiría AÑO en ANO.
             */
            return t.normalize('NFD')
                    .replace(/[̀́̂̈]/g, '')
                    .normalize('NFC');
        } catch (e) {
            return t.replace(/[ÁÀÂÄ]/g, 'A').replace(/[ÉÈÊË]/g, 'E')
                    .replace(/[ÍÌÎÏ]/g, 'I').replace(/[ÓÒÔÖ]/g, 'O')
                    .replace(/[ÚÙÛÜ]/g, 'U');
        }
    }

    function soloPalabras(texto) {
        let t = String(texto == null ? '' : texto);

        try {
            // Pictogramas, símbolos, banderas y los modificadores que los
            // acompañan (tono de piel, variación, unión con ancho cero).
            t = t.replace(/[\p{Extended_Pictographic}\p{Emoji_Presentation}]/gu, ' ')
                 .replace(/[\u{1F3FB}-\u{1F3FF}\u{FE0E}\u{FE0F}\u{200D}\u{20E3}]/gu, ' ');
        } catch (e) {
            // Navegador sin escapes de propiedad Unicode: se limpia el
            // rango principal, que es donde están casi todos.
            t = t.replace(/[\uD800-\uDBFF][\uDC00-\uDFFF]/g, ' ')
                 .replace(/[←-⯿☀-➿️‍]/g, ' ');
        }

        return t.replace(/\s+/g, ' ').trim();
    }

    /** La mejor voz disponible para un idioma, o null si no hay ninguna. */
    function vozPara(idioma) {
        if (!vocesListas.length) return null;

        const pref = String(idioma || '').toLowerCase();
        const base = pref.slice(0, 2);

        // Primero la que coincide entera («es-ES»), luego la del idioma
        // («es-MX» sirve para leer español), y si no hay, ninguna.
        return vocesListas.find((v) => String(v.lang).toLowerCase() === pref)
            || vocesListas.find((v) => String(v.lang).toLowerCase().slice(0, 2) === base)
            || null;
    }

    /**
     * Lee un texto en voz alta, si el navegador puede.
     *
     * Nunca es obligatorio que funcione: si el equipo no tiene voces, la
     * actividad sigue jugándose igual. Por eso el texto también se pinta
     * SIEMPRE, incluso en preescolar. Esconderlo dejaría la actividad
     * inservible en cualquier equipo sin voz sintética — y a un niño sordo
     * lo dejaría fuera del todo.
     */
    /*
     * ─────────────────────────────────────────────────────────────────
     *  LO QUE SE ESTÁ DICIENDO Y NO SE DEBE CORTAR
     * ─────────────────────────────────────────────────────────────────
     *
     * `speechSynthesis` no sabe cancelar una locución suelta: `cancel()`
     * se lleva por delante todo lo que haya, esté empezando o a mitad de
     * palabra. Como no se puede pedir «cancela todo menos esta», se
     * apunta hasta cuándo hay algo que merece terminar y quien cancele
     * por rutina —un cambio de pantalla— lo respeta.
     *
     * Es una MARCA DE TIEMPO y no un booleano a propósito: si `onend` no
     * llega (pasa cuando la pestaña se va al fondo o la voz se atasca),
     * un booleano se quedaría encendido para siempre y la actividad no
     * podría volver a callar nunca.
     */
    let protegidaHasta = 0;

    function hayProtegida() {
        return Date.now() < protegidaHasta;
    }

    /** Cuánto dura, más o menos, decir esto. Para no esperar de más. */
    function duracionAproximada(texto) {
        const palabras = String(texto).split(/\s+/).filter(Boolean).length;
        const porPalabra = sinLectura ? 520 : 440;

        return Math.min(2600, Math.max(900, palabras * porPalabra + 350));
    }

    /**
     * @param {string} texto
     * @param {string|{idioma?:string, encolar?:boolean, proteger?:boolean}} [opciones]
     *        Por compatibilidad, una cadena se entiende como el idioma.
     *
     *        `encolar`  — no corta lo que se está diciendo; se pone detrás.
     *        `proteger` — pide que un cambio de pantalla no la corte.
     */
    function hablar(texto, opciones) {
        if (!window.speechSynthesis || !texto) return;

        const limpio = soloPalabras(texto);
        if (!limpio) return;

        const op = typeof opciones === 'string' ? { idioma: opciones } : (opciones || {});

        try {
            /*
             * Sin `encolar`, esto corta lo anterior, que es lo correcto
             * cuando alguien pulsa el altavoz: quiere oír ESTO, ahora.
             */
            if (!op.encolar) {
                protegidaHasta = 0;
                window.speechSynthesis.cancel();
            }

            const v = new SpeechSynthesisUtterance(limpio);
            const lang = op.idioma || idiomaVoz;
            const voz = vozPara(lang);

            v.lang = lang;
            if (voz) v.voice = voz;

            // Más despacio con los pequeños: a 0.85 un niño de cinco años
            // pierde el principio de la frase mientras entiende el final.
            v.rate = sinLectura ? 0.72 : 0.85;

            if (op.proteger) {
                const dura = duracionAproximada(limpio);

                /*
                 * Encolada, esto no empieza a sonar ya: espera a que
                 * termine lo de delante. Por eso la ventana inicial lleva
                 * un margen para esa espera — sin él la protección
                 * caducaba ANTES de que la frase llegara a empezar, y el
                 * corte volvía exactamente igual.
                 */
                protegidaHasta = Math.max(protegidaHasta, Date.now() + dura + 2000);

                // Cuando empieza de verdad ya se sabe el tiempo exacto.
                v.onstart = () => { protegidaHasta = Date.now() + dura; };

                // Y si acaba antes de lo calculado, se suelta en el acto.
                const soltar = () => { protegidaHasta = 0; };
                v.onend = soltar;
                v.onerror = soltar;
            }

            window.speechSynthesis.speak(v);
        } catch (e) {
            /* si falla, la actividad sigue funcionando sin voz */
        }
    }

    /**
     * Corta cualquier lectura en curso.
     *
     * @param {boolean} [suave] respeta una locución protegida en vez de
     *        cortarla. Lo usan los cortes automáticos —cambiar de
     *        pantalla, encolar la instrucción siguiente—, nunca una
     *        acción del niño.
     */
    function callar(suave) {
        if (!window.speechSynthesis) return;
        if (suave && hayProtegida()) return;

        protegidaHasta = 0;
        try { window.speechSynthesis.cancel(); } catch (e) { /* da igual */ }
    }

    /**
     * Lee varios textos SEGUIDOS, sin cortarse entre uno y otro.
     *
     * `hablar()` cancela lo anterior antes de empezar, que es lo correcto
     * cuando se repite una instrucción. Aquí hace falta lo contrario: la
     * pregunta y luego las opciones, una detrás de otra, como las lee un
     * docente en voz alta. El navegador ya encola los enunciados solo.
     */
    function hablarSeguido(lista) {
        if (!window.speechSynthesis || !Array.isArray(lista)) return;

        /*
         * En suave: si el «¡Muy bien!» del ejercicio anterior todavía se
         * está diciendo, la instrucción nueva espera su turno en vez de
         * cortarlo. El navegador encola solo.
         */
        callar(true);

        try {
            lista.forEach((t) => {
                const limpio = soloPalabras(t);
                if (!limpio) return;

                const v = new SpeechSynthesisUtterance(limpio);
                const voz = vozPara(idiomaVoz);

                v.lang = idiomaVoz;
                if (voz) v.voice = voz;
                v.rate = sinLectura ? 0.72 : 0.85;

                window.speechSynthesis.speak(v);
            });
        } catch (e) {
            /* sin voz, la actividad sigue igual */
        }
    }

    /*
     * ─────────────────────────────────────────────────────────────────
     *  LA COLA DE LO QUE HAY QUE DECIR EN ESTA PANTALLA
     * ─────────────────────────────────────────────────────────────────
     *
     * El problema que resuelve: `titular()` pone la pregunta, y el motor
     * pinta las opciones DESPUÉS. Si cada uno hablara por su cuenta, las
     * opciones se lanzarían antes que la pregunta —o la cortarían— porque
     * la pregunta va con un pequeño retraso.
     *
     * Así, todo lo que se pinta en una pantalla se apunta aquí y se lee
     * junto, en el orden en que se apuntó, cuando el motor ya terminó de
     * dibujar.
     */
    let colaDicha = [];
    let lecturaProgramada = null;

    function decir(texto) {
        const t = soloPalabras(texto);
        if (!t) return;

        colaDicha.push(t);

        if (lecturaProgramada) return;

        /*
         * El respiro antes de empezar no es estético: lanzada en el mismo
         * instante en que se pinta, la voz arranca mientras el niño sigue
         * mirando cómo cambia la pantalla y se pierde la primera palabra.
         */
        lecturaProgramada = setTimeout(() => {
            lecturaProgramada = null;
            const lista = colaDicha;
            colaDicha = [];
            hablarSeguido(lista);
        }, 350);
    }

    /**
     * Olvida lo que faltaba por decir y calla.
     *
     * Calla EN SUAVE porque quien llama es `limpiar()`, o sea un cambio
     * de pantalla. Antes cortaba en seco, y ahí estaba el fallo que se
     * oía: el niño acertaba, empezaba el «¡Muy bien!», y un segundo
     * después el motor pintaba el ejercicio siguiente y lo dejaba en
     * «muy».
     *
     * Lo que sí se tira siempre es la cola de esta pantalla: son cosas
     * que aún no se han dicho y que ya no vienen a cuento.
     */
    function olvidarLoDicho() {
        if (lecturaProgramada) {
            clearTimeout(lecturaProgramada);
            lecturaProgramada = null;
        }
        colaDicha = [];
        callar(true);
    }

    /**
     * Un botón de altavoz que repite un texto.
     *
     * Repetir es la mitad del asunto: un niño pequeño se distrae, la voz
     * pasa una vez y ya no está. Sin forma de volver a oírla, la
     * instrucción se pierde y la actividad se convierte en adivinar.
     */
    function botonOir(texto, clase) {
        const b = el('button', 'btn-oir ' + (clase || ''), '🔊');
        b.type = 'button';
        b.setAttribute('aria-label', 'Escuchar otra vez');
        b.title = 'Escuchar otra vez';
        b.onclick = (ev) => {
            ev.preventDefault();
            ev.stopPropagation();
            hablar(texto);
        };
        return b;
    }

    // ── Interfaz de juego ────────────────────────────────────────────

    const zona = () => $('#zona-juego');

    /*
     * Borra la pantalla y calla.
     *
     * El `olvidarLoDicho()` importa: `limpiar()` se llama al principio de
     * cada pregunta, así que sin esto la voz seguiría leyendo la pregunta
     * anterior encima de la nueva. Al niño le llegarían dos preguntas a la
     * vez y ninguna entera.
     */
    function limpiar() {
        olvidarLoDicho();
        zona().innerHTML = '';
    }

    /**
     * Encabezado del minijuego. **Y la instrucción hablada.**
     *
     * Con `texto` vacío se pinta solo la bajada. Hace falta cuando el
     * título de la estación ya está en la cabecera del reproductor y
     * repetirlo dejaría el mismo texto dos veces seguidas en pantalla.
     *
     * ─────────────────────────────────────────────────────────────────
     *  POR QUÉ EL AUDIO SE ENGANCHA AQUÍ Y NO EN CADA MOTOR
     * ─────────────────────────────────────────────────────────────────
     *
     * Los veintiún minijuegos llaman a `titular()` para poner lo que hay
     * que hacer, y lo llaman otra vez en cada pregunta. Es el único punto
     * por el que pasan todos — el mismo criterio que hace que `avisar()`
     * cuente los fallos en vez de contarlos veintiuna veces.
     *
     * Poner la voz en cada motor serían veintiún sitios donde olvidarse
     * de uno, y el que se olvidara dejaría a un niño de preescolar
     * mirando una frase que no sabe leer, sin ninguna señal de que algo
     * falta.
     *
     * El altavoz aparece SIEMPRE, para todas las edades: también un niño
     * de tercero agradece que le lean un enunciado largo, y una familia
     * que lee con su hijo lo usa igual. Lo que cambia en preescolar es
     * que además **se lee solo**, sin que haya que pulsar nada.
     */
    function titular(texto, sub) {
        const c = el('div', 'juego-titular');

        // Lo que se dice en voz alta: el enunciado, y la bajada solo si no
        // hay enunciado. Leer «Pregunta 3 de 5» detrás de cada pregunta es
        // ruido que tapa lo que importa.
        const dicho = String(texto || sub || '').trim();

        if (texto) {
            const h = el('h2', null, texto);

            if (dicho) h.appendChild(botonOir(dicho, 'en-titulo'));
            c.appendChild(h);
        }

        if (sub) c.appendChild(el('p', null, sub));

        if (!texto && sub && dicho) {
            c.appendChild(botonOir(dicho, 'suelto'));
        }

        zona().appendChild(c);

        // En preescolar se lee sola. Va a la cola para que las opciones
        // que el motor pinte después se lean detrás y no encima.
        if (sinLectura) decir(dicho);

        return c;
    }

    /**
     * Apunta las opciones para que se lean tras la pregunta.
     *
     * Solo las que tienen palabras: leer «🍎» en voz alta no dice nada —o
     * dice «red apple» con voz inglesa, que es peor. El dibujo ya se ve.
     *
     * Es lo que hace un docente de transición: lee la pregunta y después
     * las opciones, señalando cada una. Sin esto, un niño que no lee tiene
     * la pregunta hablada y tres botones mudos.
     */
    function decirOpciones(lista) {
        if (!sinLectura || !Array.isArray(lista)) return;

        lista.forEach((o) => {
            if (!esSoloDibujo(o)) decir(o);
        });
    }

    function panel(clase) {
        const d = el('div', clase || 'juego-panel');
        zona().appendChild(d);
        return d;
    }

    /*
     * ─────────────────────────────────────────────────────────────────
     *  EL PERSONAJE REACCIONA
     * ─────────────────────────────────────────────────────────────────
     *
     * El niño elige su personaje en la tienda y lo paga con las monedas
     * que gana jugando. Hasta ahora no volvía a verlo: el final de la
     * estación felicitaba con un 🎉 igual para todos, y la tienda, las
     * monedas y el personaje existían sin llegar nunca al momento en que
     * de verdad importan.
     *
     * ─────────────────────────────────────────────────────────────────
     *  CUANDO FALLA, EL PERSONAJE ANIMA. NUNCA SE DECEPCIONA.
     * ─────────────────────────────────────────────────────────────────
     *
     * Es la decisión que más importa aquí. Un personaje que pone cara
     * triste porque el niño no acertó convierte el error en un castigo, y
     * un niño castigado por equivocarse deja de intentarlo — que es
     * exactamente lo contrario de lo que hace falta para aprender.
     *
     * Así que hay tres ánimos y ninguno es tristeza: celebra, sonríe o
     * anima. La diferencia entre el 40 % y el 100 % se ve en las
     * estrellas, que es donde tiene que verse.
     */

    /** ¿El equipo pide que no haya animaciones? Se respeta. */
    function sinMovimiento() {
        try {
            return window.matchMedia
                && window.matchMedia('(prefers-reduced-motion: reduce)').matches;
        } catch (e) {
            return false;
        }
    }

    /**
     * El personaje del niño, con un ánimo.
     *
     * `animo` es una clase: `celebra`, `contento`, `anima`, `salta` o
     * `piensa`. Sin personaje —alguien sin sesión— devuelve null y quien
     * llama sigue con su emoji de siempre.
     */
    function personajeEl(animo, clase) {
        if (!estado.personaje || !estado.personaje.emoji) return null;

        const c = el('span', 'personaje-juego ' + (animo || '') + ' ' + (clase || ''));
        c.setAttribute('aria-hidden', 'true');

        c.appendChild(el('span', 'personaje-cara', estado.personaje.emoji));

        if (estado.personaje.accesorio) {
            c.appendChild(el('span', 'personaje-accesorio', estado.personaje.accesorio));
        }

        return c;
    }

    /**
     * Papelillos de fiesta. Solo cuando se ha ganado de verdad.
     *
     * Si cayeran en cada estación terminada dejarían de significar nada:
     * lo que hace que una celebración se sienta como tal es que no
     * ocurra siempre.
     */
    function papelillos(destino, cuantos) {
        if (sinMovimiento()) return;

        const colores = ['#ff9800', '#29b6f6', '#4caf50', '#ab47bc', '#ffd54f', '#ef5350'];
        const capa = el('div', 'papelillos');

        for (let i = 0; i < (cuantos || 22); i++) {
            const p = el('span');
            p.style.left = Math.random() * 100 + '%';
            p.style.background = colores[i % colores.length];
            p.style.animationDelay = (Math.random() * 0.5) + 's';
            p.style.animationDuration = (1.6 + Math.random() * 1.1) + 's';
            capa.appendChild(p);
        }

        destino.appendChild(capa);

        // Se quita al terminar: si se quedaran, cada estación iría dejando
        // una capa más de nodos encima de la anterior.
        setTimeout(() => capa.remove(), 3200);
    }

    /**
     * Anuncia un logro recién conseguido.
     *
     * ─────────────────────────────────────────────────────────────────
     *  POR QUÉ SE ANUNCIA AQUÍ Y NO EN «MI ESPACIO»
     * ─────────────────────────────────────────────────────────────────
     *
     * Los logros se calculan del progreso, así que siempre estuvieron
     * ahí — pero el niño solo los descubría si entraba a mirarlos. El
     * momento en que un logro significa algo es el segundo en que se
     * consigue: ahí es cuando se puede unir con lo que se acaba de
     * hacer.
     *
     * Va flotando y no dentro de la pantalla de resultado a propósito:
     * lo que el niño estaba mirando no debe saltar de sitio porque
     * además haya ganado algo.
     */
    function anunciarLogro(logro, retraso) {
        setTimeout(() => {
            const caja = el('div', 'logro-nuevo');

            caja.appendChild(el('span', 'logro-emoji', logro.emoji || '🏅'));

            const t = el('div', 'logro-texto');
            t.appendChild(el('span', 'logro-eti', '¡Logro conseguido!'));
            t.appendChild(el('span', 'logro-nombre', logro.nombre || ''));
            if (logro.pista) t.appendChild(el('span', 'logro-pista', logro.pista));
            caja.appendChild(t);

            document.body.appendChild(caja);

            /*
             * Igual que la felicitación: se pone detrás de lo que se esté
             * diciendo y se protege. Un logro salta justo cuando el niño
             * acaba de acertar, así que sin encolar cortaba el «¡Muy
             * bien!» — y sin proteger lo cortaba a él la pantalla de
             * resultado.
             */
            if (sinLectura) {
                hablar('¡Conseguiste un logro! ' + (logro.nombre || ''),
                       { encolar: true, proteger: true });
            }

            setTimeout(() => {
                caja.classList.add('saliendo');
                setTimeout(() => caja.remove(), 420);
            }, 4200);
        }, retraso || 0);
    }

    /**
     * Las monedas ganadas, subiendo desde donde se anunciaron.
     *
     * Liga el número que sube en la cabecera con lo que el niño acaba de
     * hacer. Sin esto la recompensa aparece en otra parte de la pantalla
     * y no se asocia con el esfuerzo.
     */
    function volarMonedas(desde, cuantas) {
        if (sinMovimiento() || !desde) return;

        const caja = desde.getBoundingClientRect();
        const n = Math.min(cuantas, 8);

        for (let i = 0; i < n; i++) {
            const m = el('span', 'moneda-vuela', '🪙');
            m.style.left = (caja.left + caja.width / 2 - 12 + (Math.random() * 60 - 30)) + 'px';
            m.style.top  = (caja.top) + 'px';
            m.style.animationDelay = (i * 0.09) + 's';
            document.body.appendChild(m);
            setTimeout(() => m.remove(), 1400 + i * 90);
        }
    }

    /**
     * Pone al día el marcador de la cabecera sin recargar la página.
     *
     * El saldo lo pinta PHP al cargar, así que sin esto el niño ganaba
     * monedas y el número de arriba seguía igual hasta la siguiente
     * página. Ver subir el contador es la mitad de la recompensa.
     */
    function refrescarMarcador(cartera) {
        if (!cartera) return;

        const marcador = document.querySelector('.marcador');
        if (!marcador) return;

        const datos = marcador.querySelectorAll('.marcador-dato b');
        if (datos.length > 0) datos[0].textContent = String(cartera.saldo);
    }

    /**
     * Mensaje de acierto o error.
     *
     * Además cuenta los fallos de la estación. Se hace AQUÍ y no en cada
     * minijuego porque este es el único punto por el que pasan todos:
     * los diecinueve motores llaman a `avisar(texto, false)` cuando el
     * niño se equivoca. Un contador por motor serían diecinueve sitios
     * donde olvidarse de uno.
     */
    function avisar(texto, bien) {
        if (!bien) {
            estado.fallos++;
            if (estado.fallos === AYUDA.fallosParaOfrecer) {
                ofrecerAyuda();
            }
        }

        const previo = $('.juego-aviso');
        if (previo) previo.remove();

        const a = el('div', 'juego-aviso ' + (bien ? 'bien' : 'mal'));

        /*
         * El personaje reacciona en cada respuesta, no solo al final.
         *
         * Va DELANTE del texto y pequeño: acompaña, no interrumpe. Al
         * fallar hace un gesto de ánimo, nunca de decepción — ver a tu
         * propio personaje disgustado contigo es un castigo, y castigar
         * el error enseña a no arriesgarse.
         */
        const quien = personajeEl(bien ? 'salta' : 'anima', 'mini');
        if (quien) a.appendChild(quien);

        a.appendChild(el('span', null, texto));

        zona().appendChild(a);
        setTimeout(() => a.remove(), 1800);

        /*
         * En preescolar el aviso también se dice. Un «¡Correcto!» escrito
         * que aparece y desaparece en dos segundos no lo lee nadie de
         * cinco años: sin voz, el niño no sabe si acertó.
         *
         * Se leen dos palabras, no el aviso entero: los avisos llevan
         * emoji y adornos que en voz alta suenan raro o se leen como
         * nombres de símbolos.
         */
        /*
         * Se ENCOLA y se PROTEGE, no se lanza a secas.
         *
         *  · Encolada, porque varios motores dicen algo justo antes de
         *    avisar —«Selecciona imágenes» lee en voz alta el nombre del
         *    dibujo que el niño acaba de tocar— y lanzarla a secas
         *    cortaba ese nombre a media palabra.
         *
         *  · Protegida, porque el motor pasa al ejercicio siguiente al
         *    segundo y pico, y el cambio de pantalla se la llevaba por
         *    delante. Un «¡Muy bien!» que se oye como «muy» no felicita
         *    a nadie.
         */
        if (sinLectura) {
            hablar(bien ? '¡Muy bien!' : 'Esa no era. Prueba otra vez.',
                   { encolar: true, proteger: true });
        }
    }

    /**
     * Apaga una opción equivocada que siga en pie. Es la pista.
     *
     * No revela la respuesta: reduce las opciones. El niño sigue teniendo
     * que elegir, y si vuelve a pedir pista se apaga otra — pero nunca la
     * última, porque entonces dejaría de haber nada que decidir.
     */
    function descartarUnaMala(contenedor, indiceCorrecto) {
        const botones = Array.from(contenedor.children);

        const candidatas = botones.filter((b, k) =>
            k !== indiceCorrecto && !b.disabled && !b.classList.contains('descartada'));

        // Se deja siempre la correcta y al menos otra en pie.
        if (candidatas.length <= 1) return;

        const elegida = candidatas[Math.floor(Math.random() * candidatas.length)];
        elegida.classList.add('descartada');
        elegida.disabled = true;
    }

    /**
     * Ofrece las dos ayudas, tras varios fallos seguidos.
     *
     * El botón de pista solo aparece si el minijuego sabe dar una: media
     * docena de los diecinueve la tienen, y ofrecerla donde no existe
     * sería cobrar por nada.
     */
    function ofrecerAyuda() {
        if ($('.juego-ayuda')) return;

        const caja = el('div', 'juego-ayuda');
        caja.appendChild(el('p', 'juego-ayuda-titulo', '¿Te atascaste? Puedes usar tus monedas.'));

        const fila = el('div', 'juego-ayuda-botones');

        if (typeof estado.pista === 'function') {
            const b = el('button', 'btn btn-secundario btn-chico',
                '💡 Una pista · 🪙 ' + AYUDA.precioPista);
            b.onclick = () => usarAyuda('pista', b, caja);
            fila.appendChild(b);
        }

        const s = el('button', 'btn btn-secundario btn-chico',
            '⏭️ Saltar esta estación · 🪙 ' + AYUDA.precioSaltar);
        s.onclick = () => usarAyuda('saltar', s, caja);
        fila.appendChild(s);

        caja.appendChild(fila);
        caja.appendChild(el('p', 'juego-ayuda-nota',
            'Saltar te deja pasar, pero esta estación queda sin estrellas.'));

        zona().appendChild(caja);
    }

    /** Pide el cobro al servidor y, si acepta, aplica la ayuda. */
    async function usarAyuda(tipo, boton, caja) {
        boton.disabled = true;

        let res;
        try {
            const r = await fetch(estado.base + 'api/gastar.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: new URLSearchParams({
                    csrf_token: estado.csrf,
                    tipo: tipo,
                    estacion: String(estado.actual ? estado.actual.id : 0),
                }),
            });
            res = await r.json();
        } catch (e) {
            boton.disabled = false;
            avisar('No se pudo usar la ayuda. Inténtalo otra vez.', false);
            return;
        }

        if (!res.ok) {
            boton.disabled = false;
            avisar(res.error || 'No se pudo usar la ayuda.', false);
            return;
        }

        estado.saldo = res.saldo;
        caja.remove();

        if (tipo === 'pista') {
            estado.pista();
            avisar('💡 Descartamos una opción equivocada', true);
        } else {
            /*
             * Saltar registra la estación como pasada pero SIN mérito:
             * cero aciertos sobre cero. `terminarEstacion` lo guarda con
             * 0 estrellas y 0 monedas, que es exactamente lo que se ha
             * ganado — nada. Lo que se compró fue seguir adelante.
             */
            terminarEstacion(0, 0, true);
        }
    }

    function marcarProgreso(hechos, total) {
        const barra = $('#barra-juego');
        const texto = $('#texto-juego');
        const pct = total > 0 ? Math.round((hechos / total) * 100) : 0;
        if (barra) barra.style.width = pct + '%';
        if (texto) texto.textContent = hechos + ' de ' + total;
    }

    /**
     * Separa los ejercicios de sus encabezados.
     *
     * Algunos minijuegos nacieron dentro de «Aventura de las Letras» y
     * llevaban el encabezado escrito dentro del motor: «Elige las que
     * empiezan con la letra». Fuera de lectoescritura eso es falso —
     * una estación de alimentos saludables usa el mismo minijuego.
     *
     * Por eso la estación puede mandar `{t, s, items}` para poner su
     * propio encabezado. Si manda una lista pelada, como hacen las 506
     * estaciones ya migradas, se usan los textos de siempre.
     */
    function desempacar(datos, tituloPorDefecto, subtituloPorDefecto) {
        if (datos && !Array.isArray(datos) && Array.isArray(datos.items)) {
            return {
                items: datos.items,
                t: datos.t || tituloPorDefecto,
                s: datos.s || subtituloPorDefecto,
            };
        }
        return {
            items: Array.isArray(datos) ? datos : [],
            t: tituloPorDefecto,
            s: subtituloPorDefecto,
        };
    }

    /**
     * Pinta el dibujo de un ejercicio.
     *
     * Casi siempre es un emoji y se pinta como texto. Pero el emoji no
     * cubre el español: no hay mesa, ni xilófono, ni flauta, ni ñandú. En
     * esos casos el contenido escribe `icono:mesa` y aquí se sirve el
     * dibujo propio de assets/iconos/mesa.svg.
     *
     * Importa más de lo que parece: sin esto, «MESA» salía ilustrada con
     * una silla. Un niño que está aprendiendo a leer no corrige eso — lo
     * memoriza.
     */
    function dibujo(valor, clase) {
        const v = String(valor == null ? '' : valor);
        const c = clase || 'juego-emoji';

        if (v.indexOf('icono:') === 0) {
            const img = el('img', c + ' juego-icono');
            img.src = estado.base + 'assets/iconos/' + v.slice(6) + '.svg';
            // Decorativo: la palabra siempre está escrita al lado, así que
            // repetirla aquí haría que el lector de pantalla la dijera dos
            // veces.
            img.alt = '';
            return img;
        }

        return el('span', c, v);
    }

    /** Igual, pero devuelve el HTML para los sitios que usan innerHTML. */
    function dibujoHtml(valor, clase) {
        const v = String(valor == null ? '' : valor);
        const c = clase || 'juego-emoji';

        if (v.indexOf('icono:') === 0) {
            return '<img class="' + c + ' juego-icono" alt="" src="' +
                   esc(estado.base + 'assets/iconos/' + v.slice(6) + '.svg') + '">';
        }

        return '<span class="' + c + '">' + esc(v) + '</span>';
    }

    /**
     * ¿El texto es solo un dibujo, sin ninguna palabra ni número?
     *
     * Importa para el tamaño. Un botón que dice «Animales» se lee bien
     * con letra normal; uno que dice «🐘» a esa misma medida es un dibujo
     * diminuto que no se distingue de «🐭». En «¿cuál es más grande?» el
     * dibujo ES la pregunta: si no se ve, el ejercicio no existe.
     */
    function esSoloDibujo(texto) {
        const t = String(texto == null ? '' : texto).trim();
        if (!t) return false;
        try {
            // Ni letras ni números de ningún alfabeto: solo símbolos.
            return !/[\p{L}\p{N}]/u.test(t);
        } catch (e) {
            // Navegador sin escapes de propiedad Unicode: se conforma con
            // descartar el alfabeto latino y los dígitos.
            return !/[A-Za-zÁÉÍÓÚÑáéíóúñ0-9]/.test(t);
        }
    }

    /** ¿Todas las opciones de una lista son dibujos? */
    function todoDibujos(lista) {
        return Array.isArray(lista) && lista.length > 0 && lista.every(esSoloDibujo);
    }

    // ── Registro de minijuegos ───────────────────────────────────────
    // Cada función recibe el contenido de la estación y una función
    // `fin(aciertos, total)` que llama al terminar.

    const JUEGOS = {

        /* 1 · Conoce la letra: escuchar y decir sí o no
         *
         * El encabezado es configurable, y no es un capricho: «¿empieza
         * con esta letra?» solo vale para las letras que de verdad se
         * enseñan en posición inicial. La Ñ casi no existe al principio de
         * palabra en español —niño, piña, araña, montaña— y con la X pasa
         * lo mismo. Para esas, la pregunta correcta es «¿lleva esta
         * letra?», y preguntar la otra sería enseñar algo falso.
         *
         * Sin encabezado propio se conserva el de siempre, así que las
         * estaciones antiguas siguen funcionando igual.
         */
        sonido_letra(datos, fin, extra) {
            const d = desempacar(datos, '¿Empieza con esta letra?', 'Escucha y decide');
            const items = d.items;
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it = items[i];
                titular(d.t, d.s);

                const p = panel('juego-centro');
                const cara = dibujo(it.e);
                cara.setAttribute('role', 'img');
                cara.setAttribute('aria-label', it.n || '');
                p.appendChild(cara);
                p.appendChild(el('div', 'juego-palabra', it.n));

                const oir = el('button', 'btn btn-secundario', '🔊 Escuchar');
                oir.onclick = () => hablar(it.n);
                p.appendChild(oir);

                /*
                 * El «sí» va en verde y el «no» en rojo, no los dos del
                 * mismo color. A los cuatro años el color se lee antes
                 * que la palabra: dos botones idénticos obligan a leer
                 * para elegir, que es justo lo que el niño está
                 * aprendiendo. El símbolo acompaña al color para quien no
                 * distingue verde de rojo.
                 */
                const ops = el('div', 'juego-opciones');
                [['✅ Sí', true, 'juego-si'], ['❌ No', false, 'juego-no']].forEach(([txt, val, clase]) => {
                    const b = el('button', 'btn juego-boton ' + clase, txt);
                    b.onclick = () => {
                        const bien = (!!it.ok === val);
                        if (bien) aciertos++;
                        avisar(bien ? '¡Muy bien! 🎉' : 'Casi… inténtalo en la siguiente', bien);
                        i++;
                        marcarProgreso(i, items.length);
                        setTimeout(pinta, 900);
                    };
                    ops.appendChild(b);
                });
                p.appendChild(ops);

                hablar(it.n);
            }

            if (extra && extra.sonido) hablar(extra.sonido);
            marcarProgreso(0, items.length);
            pinta();
        },

        /* 2 · Seleccionar todas las imágenes correctas */
        seleccion_imagenes(datos, fin) {
            const d = desempacar(datos, 'Toca todas las correctas',
                                        'Elige las que empiezan con la letra');
            const items = barajar(d.items);
            const correctas = items.filter((x) => x.ok).length;
            const elegidas = new Set();

            limpiar();
            titular(d.t, d.s);

            const rej = panel('juego-rejilla');

            items.forEach((it, idx) => {
                const c = el('button', 'juego-carta');
                c.innerHTML = dibujoHtml(it.e) +
                              '<span class="juego-nombre">' + esc(it.n) + '</span>';
                c.onclick = () => {
                    if (c.disabled) return;
                    hablar(it.n);
                    if (it.ok) {
                        c.classList.add('acierto');
                        c.disabled = true;
                        elegidas.add(idx);
                        marcarProgreso(elegidas.size, correctas);
                        if (elegidas.size === correctas) {
                            avisar('¡Las encontraste todas! 🌟', true);
                            setTimeout(() => fin(correctas, correctas), 1100);
                        }
                    } else {
                        c.classList.add('error');
                        avisar('Esa no era', false);
                        setTimeout(() => c.classList.remove('error'), 600);
                    }
                };
                rej.appendChild(c);
            });

            marcarProgreso(0, correctas);
        },

        /* 3 · Puzle de sílabas */
        puzle_silabas(datos, fin) {
            const palabras = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= palabras.length) return fin(aciertos, palabras.length);

                const p = palabras[i];
                const orden = [];

                titular('Ordena las sílabas', 'Forma la palabra correcta');

                const c = panel('juego-centro');
                c.appendChild(dibujo(p.e));

                const huecos = el('div', 'juego-huecos');
                c.appendChild(huecos);

                const bolsa = el('div', 'juego-opciones');
                c.appendChild(bolsa);

                function repinta() {
                    huecos.innerHTML = '';
                    p.syls.forEach((_, k) => {
                        const h = el('span', 'juego-hueco', orden[k] || '');
                        if (orden[k]) h.classList.add('lleno');
                        huecos.appendChild(h);
                    });
                }

                barajar(p.syls).forEach((s) => {
                    const b = el('button', 'btn btn-secundario juego-ficha', s);
                    b.onclick = () => {
                        if (b.disabled) return;
                        const pos = orden.length;
                        if (p.syls[pos] === s) {
                            orden.push(s);
                            b.disabled = true;
                            b.classList.add('usada');
                            repinta();
                            hablar(s);
                            if (orden.length === p.syls.length) {
                                aciertos++;
                                hablar(p.w);
                                avisar('¡' + p.w + '! 🎉', true);
                                i++;
                                marcarProgreso(i, palabras.length);
                                setTimeout(pinta, 1200);
                            }
                        } else {
                            avisar('Esa sílaba va después', false);
                        }
                    };
                    bolsa.appendChild(b);
                });

                repinta();
            }

            marcarProgreso(0, palabras.length);
            pinta();
        },

        /* 4 · Armar palabras letra por letra */
        armar_palabras(datos, fin) {
            const palabras = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= palabras.length) return fin(aciertos, palabras.length);

                const p = palabras[i];
                const letras = p.w.split('');
                let puestas = 0;

                titular('Arma la palabra', 'Toca las letras en orden');

                const c = panel('juego-centro');
                c.appendChild(dibujo(p.e));

                const huecos = el('div', 'juego-huecos');
                c.appendChild(huecos);

                const bolsa = el('div', 'juego-opciones');
                c.appendChild(bolsa);

                function repinta() {
                    huecos.innerHTML = '';
                    letras.forEach((L, k) => {
                        const h = el('span', 'juego-hueco', k < puestas ? L : '');
                        if (k < puestas) h.classList.add('lleno');
                        huecos.appendChild(h);
                    });
                }

                barajar(letras).forEach((L) => {
                    const b = el('button', 'btn btn-secundario juego-ficha', L);
                    b.onclick = () => {
                        if (b.disabled) return;
                        if (letras[puestas] === L) {
                            puestas++;
                            b.disabled = true;
                            b.classList.add('usada');
                            repinta();
                            if (puestas === letras.length) {
                                aciertos++;
                                hablar(p.w);
                                avisar('¡' + p.w + '! 🌟', true);
                                i++;
                                marcarProgreso(i, palabras.length);
                                setTimeout(pinta, 1200);
                            }
                        } else {
                            avisar('Esa letra no va aquí', false);
                        }
                    };
                    bolsa.appendChild(b);
                });

                repinta();
            }

            marcarProgreso(0, palabras.length);
            pinta();
        },

        /* 5 · Teclado: escribir la palabra */
        teclado(datos, fin) {
            const palabras = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= palabras.length) return fin(aciertos, palabras.length);

                const w = String(palabras[i]);
                let intentos = 0;

                titular('Escribe la palabra', 'Usa el teclado');

                const c = panel('juego-centro');
                c.appendChild(el('div', 'juego-palabra grande', w));

                const oir = el('button', 'btn btn-secundario', '🔊 Escuchar');
                oir.onclick = () => hablar(w);
                c.appendChild(oir);

                const campo = el('input', 'juego-campo');
                campo.type = 'text';
                campo.autocomplete = 'off';
                campo.setAttribute('aria-label', 'Escribe ' + w);
                c.appendChild(campo);

                const enviar = el('button', 'btn btn-principal', 'Comprobar');
                c.appendChild(enviar);

                // Salida de emergencia. Sin esto, un niño que todavía no
                // sabe deletrear se queda atascado en esta palabra para
                // siempre, sin forma de seguir. Aparece solo tras varios
                // intentos, para no ofrecer el atajo antes de intentarlo.
                const ayuda = el('div', 'juego-ayuda');
                c.appendChild(ayuda);

                function ofrecerAyuda() {
                    if (ayuda.children.length) return;

                    const pista = el('button', 'btn btn-secundario btn-chico', '👀 Ver la primera letra');
                    pista.onclick = () => {
                        campo.value = w.charAt(0);
                        campo.focus();
                    };

                    const saltar = el('button', 'btn btn-secundario btn-chico', 'Saltar esta palabra →');
                    saltar.onclick = () => {
                        i++;
                        marcarProgreso(i, palabras.length);
                        pinta();
                    };

                    ayuda.appendChild(el('p', 'juego-nota', '¿Te cuesta esta? Puedes pedir una pista o pasar a la siguiente.'));
                    ayuda.appendChild(pista);
                    ayuda.appendChild(saltar);
                }

                function comprobar() {
                    const val = String(campo.value || '').trim().toUpperCase();

                    if (val === w.toUpperCase()) {
                        aciertos++;
                        hablar(w);
                        avisar('¡Perfecto! 🎉', true);
                        i++;
                        marcarProgreso(i, palabras.length);
                        setTimeout(pinta, 1000);
                        return;
                    }

                    intentos++;

                    /*
                     * Si lo único que falla es la tilde, se dice.
                     *
                     * La palabra se muestra bien escrita y el niño la
                     * copia, así que exigir la tilde es correcto — pero
                     * un «Revisa las letras» cuando todas las letras
                     * están bien es sacarle de quicio sin enseñarle
                     * nada. Nombrar el fallo es lo que lo convierte en
                     * una lección.
                     */
                    if (sinTildes(val) === sinTildes(w.toUpperCase())) {
                        avisar('¡Casi! Fíjate en la tilde ✍️', false);
                    } else {
                        avisar('Revisa las letras', false);
                    }

                    campo.select();

                    if (intentos >= 3) ofrecerAyuda();
                }

                enviar.onclick = comprobar;
                campo.onkeydown = (ev) => { if (ev.key === 'Enter') comprobar(); };

                campo.focus();
                hablar(w);
            }

            marcarProgreso(0, palabras.length);
            pinta();
        },

        /* 6 · Ortografía: elegir la escritura correcta */
        ortografia(datos, fin) {
            const items = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it = items[i];
                titular('¿Cómo se escribe?', 'Elige la forma correcta');

                const c = panel('juego-centro');
                c.appendChild(dibujo(it.e));

                const ops = el('div', 'juego-opciones');
                barajar(it.opts).forEach((o) => {
                    const b = el('button', 'btn btn-secundario juego-boton', o);
                    b.onclick = () => {
                        const bien = (o === it.correct);
                        if (bien) {
                            aciertos++;
                            b.classList.add('acierto');
                            hablar(it.correct);
                            avisar('¡Así se escribe! ✅', true);
                            i++;
                            marcarProgreso(i, items.length);
                            setTimeout(pinta, 1100);
                        } else {
                            b.classList.add('error');
                            avisar('Esa no es', false);
                        }
                    };
                    ops.appendChild(b);
                });
                c.appendChild(ops);
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 7 · Ordenar una secuencia */
        ordenar_secuencia(datos, fin) {
            const d = datos || {};

            /*
             * ─────────────────────────────────────────────────────────
             *  UN PASO PUEDE SER TEXTO, DIBUJO, O LAS DOS COSAS
             * ─────────────────────────────────────────────────────────
             *
             * Hasta ahora un paso era una cadena: «Despertarse». Para un
             * niño que no lee eso es una ficha en blanco — podía oír la
             * instrucción pero no tenía forma de saber qué decía cada
             * botón, así que ordenaba a ciegas.
             *
             * Ahora un paso puede venir como `{e, w}`: dibujo y palabra.
             * Las cadenas sueltas siguen valiendo, así que las 209
             * secuencias que ya existían no se tocan.
             */
            const crudos = Array.isArray(d.items) ? d.items : [];

            const items = crudos.map((it) => (
                it && typeof it === 'object'
                    ? { e: it.e || '', w: String(it.w == null ? '' : it.w) }
                    : { e: '', w: String(it == null ? '' : it) }
            ));

            let puestos = 0;

            limpiar();
            titular(d.title || 'Ordena la historia', 'Toca los elementos en orden');

            const c = panel('juego-centro');
            const linea = el('div', 'juego-huecos');
            c.appendChild(linea);

            // Cuando lo que hay que ordenar son dibujos —cinco animales
            // por tamaño, por ejemplo— tienen que verse grandes: si el
            // criterio para ordenar es lo que se ve, hay que poder verlo.
            const dibujos = todoDibujos(items.map((x) => x.w));

            const bolsa = el('div', 'juego-opciones' + (dibujos ? ' dibujos' : ''));
            c.appendChild(bolsa);

            /** Pinta un paso: el dibujo arriba y la palabra debajo. */
            function pintaPaso(destino, it) {
                if (it.e) {
                    destino.appendChild(dibujo(it.e, 'ficha-dibujo'));
                }
                if (it.w) {
                    destino.appendChild(el('span', 'ficha-palabra', it.w));
                }
            }

            function repinta() {
                linea.innerHTML = '';
                items.forEach((it, k) => {
                    const h = el('span', 'juego-hueco' + (dibujos ? ' dibujo' : ''));
                    if (k < puestos) {
                        h.classList.add('lleno');
                        pintaPaso(h, it);
                    }
                    linea.appendChild(h);
                });
            }

            // Se baraja sobre los índices y no sobre los objetos: así el
            // orden correcto se sigue comprobando por posición, aunque
            // dos pasos se llamen igual.
            barajar(items.map((_, k) => k)).forEach((k) => {
                const it = items[k];
                const b = el('button', 'btn btn-secundario juego-ficha grande'
                                       + (it.e ? ' con-dibujo' : ''));
                pintaPaso(b, it);

                b.onclick = () => {
                    if (b.disabled) return;
                    if (puestos === k) {
                        puestos++;
                        b.disabled = true;
                        b.classList.add('usada');
                        repinta();
                        marcarProgreso(puestos, items.length);
                        if (puestos === items.length) {
                            avisar('¡Secuencia completa! 🌟', true);
                            setTimeout(() => fin(items.length, items.length), 1100);
                        }
                    } else {
                        avisar('Ese va más adelante', false);
                    }
                };
                bolsa.appendChild(b);
            });

            // En preescolar se leen los pasos detrás de la instrucción:
            // un botón que no se sabe leer no se puede ordenar.
            decirOpciones(items.map((x) => x.w));

            repinta();
            marcarProgreso(0, items.length);
        },

        /* 8 · Juego rápido: sí o no contra el reloj */
        juego_rapido(datos, fin) {
            const d = desempacar(datos, '¿Lleva la letra?', 'Responde rápido');
            const items = d.items;
            let i = 0, aciertos = 0, tiempo = 30, reloj = null;

            function acabar() {
                if (reloj) clearInterval(reloj);
                fin(aciertos, items.length);
            }

            function pinta() {
                if (i >= items.length) return acabar();

                limpiar();
                const it = items[i];

                titular(d.t, d.s);

                const c = panel('juego-centro');
                const marcador = el('div', 'juego-reloj', '⏱ ' + tiempo + 's');
                c.appendChild(marcador);
                c.appendChild(dibujo(it.e));
                c.appendChild(el('div', 'juego-palabra', it.n));

                // Mismo criterio que en «sonido_letra»: verde para el sí,
                // rojo para el no. Aquí importa más todavía, porque el
                // juego es contrarreloj y no hay tiempo de leer.
                const ops = el('div', 'juego-opciones');
                [['✅ Sí', true, 'juego-si'], ['❌ No', false, 'juego-no']].forEach(([txt, val, clase]) => {
                    const b = el('button', 'btn juego-boton ' + clase, txt);
                    b.onclick = () => {
                        const bien = (!!it.ok === val);
                        if (bien) aciertos++;
                        avisar(bien ? '¡Sí! ⚡' : 'Ups', bien);
                        i++;
                        marcarProgreso(i, items.length);
                        setTimeout(pinta, 550);
                    };
                    ops.appendChild(b);
                });
                c.appendChild(ops);

                if (!reloj) {
                    reloj = setInterval(() => {
                        tiempo--;
                        const m = $('.juego-reloj');
                        if (m) m.textContent = '⏱ ' + tiempo + 's';
                        if (tiempo <= 0) {
                            avisar('¡Se acabó el tiempo!', false);
                            acabar();
                        }
                    }, 1000);
                }
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 9 · Memoria: encontrar las parejas */
        memoria(datos, fin) {
            const base = Array.isArray(datos) ? datos : [];
            const cartas = barajar(base.concat(base));
            const volteadas = [];
            let halladas = 0, bloqueo = false;

            limpiar();
            titular('Encuentra las parejas', 'Toca dos cartas iguales');

            const rej = panel('juego-rejilla memoria');

            cartas.forEach((simbolo) => {
                const c = el('button', 'juego-carta oculta');
                c.dataset.simbolo = simbolo;
                c.innerHTML = '<span class="juego-emoji">?</span>';

                c.onclick = () => {
                    if (bloqueo || !c.classList.contains('oculta')) return;

                    c.classList.remove('oculta');
                    c.innerHTML = dibujoHtml(simbolo);
                    volteadas.push(c);

                    if (volteadas.length === 2) {
                        bloqueo = true;
                        const [a, b] = volteadas;

                        if (a.dataset.simbolo === b.dataset.simbolo) {
                            a.classList.add('acierto');
                            b.classList.add('acierto');
                            halladas++;
                            marcarProgreso(halladas, base.length);
                            volteadas.length = 0;
                            bloqueo = false;
                            if (halladas === base.length) {
                                avisar('¡Todas las parejas! 🧠', true);
                                setTimeout(() => fin(base.length, base.length), 1100);
                            }
                        } else {
                            setTimeout(() => {
                                [a, b].forEach((x) => {
                                    x.classList.add('oculta');
                                    x.innerHTML = '<span class="juego-emoji">?</span>';
                                });
                                volteadas.length = 0;
                                bloqueo = false;
                            }, 850);
                        }
                    }
                };

                rej.appendChild(c);
            });

            marcarProgreso(0, base.length);
        },

        /* 10 · Sopa de letras */
        sopa_letras(datos, fin) {
            const d = datos || {};
            const grid = Array.isArray(d.grid) ? d.grid : [];
            const palabras = Array.isArray(d.words) ? d.words : [];
            const halladas = new Set();
            let primera = null;

            limpiar();
            titular('Sopa de letras', 'Toca la primera y la última letra de cada palabra');

            const c = panel('juego-centro');

            const lista = el('div', 'juego-lista-palabras');
            palabras.forEach((p) => {
                const t = el('span', 'juego-etiqueta', p.w);
                t.dataset.palabra = p.w;
                lista.appendChild(t);
            });
            c.appendChild(lista);

            const tabla = el('div', 'juego-sopa');
            tabla.style.gridTemplateColumns = 'repeat(' + (grid[0] ? grid[0].length : 6) + ', 1fr)';

            grid.forEach((fila, r) => {
                fila.forEach((letra, k) => {
                    const cel = el('button', 'juego-celda', letra);
                    cel.dataset.r = r;
                    cel.dataset.c = k;

                    cel.onclick = () => {
                        if (cel.classList.contains('hallada')) return;

                        if (!primera) {
                            primera = cel;
                            cel.classList.add('elegida');
                            return;
                        }

                        const r1 = +primera.dataset.r, c1 = +primera.dataset.c;
                        const r2 = r, c2 = k;

                        const encontrada = palabras.find((p) => {
                            if (halladas.has(p.w)) return false;
                            const ini = p.cells[0], finc = p.cells[p.cells.length - 1];
                            return (ini[0] === r1 && ini[1] === c1 && finc[0] === r2 && finc[1] === c2) ||
                                   (ini[0] === r2 && ini[1] === c2 && finc[0] === r1 && finc[1] === c1);
                        });

                        primera.classList.remove('elegida');

                        if (encontrada) {
                            halladas.add(encontrada.w);
                            encontrada.cells.forEach(([rr, cc]) => {
                                const t = tabla.querySelector('[data-r="' + rr + '"][data-c="' + cc + '"]');
                                if (t) t.classList.add('hallada');
                            });
                            const et = lista.querySelector('[data-palabra="' + encontrada.w + '"]');
                            if (et) et.classList.add('tachada');
                            hablar(encontrada.w);
                            avisar('¡' + encontrada.w + '! 📝', true);
                            marcarProgreso(halladas.size, palabras.length);

                            if (halladas.size === palabras.length) {
                                setTimeout(() => fin(palabras.length, palabras.length), 1100);
                            }
                        } else {
                            avisar('Ahí no hay palabra', false);
                        }

                        primera = null;
                    };

                    tabla.appendChild(cel);
                });
            });

            c.appendChild(tabla);
            marcarProgreso(0, palabras.length);
        },

        /* 11 · Cuento: leer y responder */
        cuento(datos, fin) {
            const d = datos || {};
            const slides = Array.isArray(d.slides) ? d.slides : [];
            const preguntas = Array.isArray(d.qs) ? d.qs : [];
            let i = 0, qi = 0, aciertos = 0;

            function verSlide() {
                limpiar();
                const s = slides[i];

                titular('Cuento', 'Página ' + (i + 1) + ' de ' + slides.length);

                const c = panel('juego-centro');
                c.appendChild(dibujo(s.img, 'juego-emoji grande'));
                c.appendChild(el('p', 'juego-texto', s.text));

                const oir = el('button', 'btn btn-secundario', '🔊 Escuchar');
                oir.onclick = () => hablar(s.text);
                c.appendChild(oir);

                const sig = el('button', 'btn btn-principal',
                    i < slides.length - 1 ? 'Siguiente →' : 'Responder preguntas →');
                sig.onclick = () => {
                    i++;
                    marcarProgreso(i, slides.length + preguntas.length);
                    if (i < slides.length) verSlide(); else verPregunta();
                };
                c.appendChild(sig);

                hablar(s.text);
            }

            function verPregunta() {
                limpiar();

                if (qi >= preguntas.length) {
                    return fin(aciertos, preguntas.length || 1);
                }

                const q = preguntas[qi];
                titular(q.q, 'Pregunta ' + (qi + 1) + ' de ' + preguntas.length);

                const c = panel('juego-centro');

                /*
                 * La pregunta del cuento también ilustra.
                 *
                 * Sin dibujo, el niño acababa de ver cinco páginas con
                 * imagen y de pronto se encontraba una pantalla de texto
                 * pelado. Si no se pone `e`, se recupera el dibujo de la
                 * página del cuento a la que pertenece la pregunta, que
                 * casi siempre es la que hay que recordar.
                 */
                const img = q.e || (slides[Math.min(qi, slides.length - 1)] || {}).img;
                if (img) c.appendChild(dibujo(img));

                const ops = el('div', 'juego-opciones vertical');

                q.opts.forEach((o, k) => {
                    const b = el('button', 'btn btn-secundario juego-boton', o);
                    b.onclick = () => {
                        olvidarLoDicho();

                        const bien = (k === q.a);
                        if (bien) aciertos++;
                        b.classList.add(bien ? 'acierto' : 'error');
                        avisar(bien ? '¡Correcto! ✅' : 'Esa no era', bien);
                        qi++;
                        marcarProgreso(slides.length + qi, slides.length + preguntas.length);
                        setTimeout(verPregunta, 1100);
                    };
                    ops.appendChild(b);
                });

                c.appendChild(ops);

                decirOpciones(q.opts || []);
            }

            marcarProgreso(0, slides.length + preguntas.length);
            if (slides.length) verSlide(); else verPregunta();
        },

        /* 12 · Opción múltiple: la mecánica más común del catálogo.
               Cada ejercicio trae un enunciado, algo visual y opciones. */
        opcion_multiple(datos, fin) {
            const items = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            /** Dibuja el acompañamiento visual según su tipo. */
            function pintaVisual(contenedor, it) {
                if (!it.visual || it.tipoVisual === 'ninguno') return;

                if (it.tipoVisual === 'emoji') {
                    contenedor.appendChild(dibujo(it.visual));

                } else if (it.tipoVisual === 'color') {
                    const m = el('div', 'juego-muestra');
                    m.style.background = it.visual;
                    contenedor.appendChild(m);

                } else if (it.tipoVisual === 'dosColores') {
                    const par = el('div', 'juego-mezcla');
                    const a = el('div', 'juego-muestra'); a.style.background = it.visual.a;
                    const b = el('div', 'juego-muestra'); b.style.background = it.visual.b;
                    par.appendChild(a);
                    par.appendChild(el('span', 'juego-signo', '+'));
                    par.appendChild(b);
                    par.appendChild(el('span', 'juego-signo', '='));
                    par.appendChild(el('span', 'juego-signo', '?'));
                    contenedor.appendChild(par);

                } else if (it.tipoVisual === 'lista') {
                    const l = el('div', 'juego-compra');
                    (it.visual || []).forEach((p) => {
                        const f = el('div', 'juego-compra-fila');
                        f.innerHTML = '<span>' + esc(p.e || '') + ' ' + esc(p.n || '') + '</span>' +
                                      '<b>$' + esc(p.p) + '</b>';
                        l.appendChild(f);
                    });
                    contenedor.appendChild(l);

                } else {
                    contenedor.appendChild(el('div', 'juego-palabra', it.visual));
                }
            }

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it = items[i];
                titular(it.enunciado, 'Pregunta ' + (i + 1) + ' de ' + items.length);

                const c = panel('juego-centro');
                pintaVisual(c, it);

                // Cómo dar una pista aquí: apagar una opción equivocada.
                // El niño sigue teniendo que elegir entre las que quedan.
                estado.pista = () => descartarUnaMala(ops, it.correcta);

                // Con opciones largas conviene apilarlas para poder leerlas.
                const largas = (it.opciones || []).some((o) => String(o).length > 18);

                // Si las opciones son dibujos y no palabras, se pintan
                // grandes: en «¿cuál es más grande?» el dibujo es la
                // pregunta, y a tamaño de texto no se distingue nada.
                const dibujos = todoDibujos(it.opciones || []);

                const ops = el('div', 'juego-opciones'
                    + (largas ? ' vertical' : '')
                    + (dibujos ? ' dibujos' : ''));

                (it.opciones || []).forEach((o, k) => {
                    const b = el('button', 'btn btn-secundario juego-boton', String(o));
                    b.onclick = () => {
                        // Si el niño ya decidió, la voz sobra: dejarla
                        // hablando encima del acierto tapa el «¡Muy bien!».
                        olvidarLoDicho();

                        const bien = (k === it.correcta);
                        if (bien) aciertos++;
                        b.classList.add(bien ? 'acierto' : 'error');
                        avisar(bien ? '¡Correcto! ✅' : 'Esa no era', bien);
                        i++;
                        marcarProgreso(i, items.length);
                        setTimeout(pinta, 1050);
                    };
                    ops.appendChild(b);
                });

                c.appendChild(ops);

                decirOpciones(it.opciones || []);
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 13 · Operaciones: sumar, restar, multiplicar y contar */
        operacion(datos, fin) {
            const items = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            const SIGNOS = { suma: '+', resta: '−', multiplicacion: '×' };
            const FICHAS = { suma: '🍎', resta: '🎈', multiplicacion: '⚽', contar: '💎' };

            /** Cuatro opciones alrededor del resultado, sin repetir. */
            function opciones(correcto) {
                const set = new Set([correcto]);
                let intento = 0;
                while (set.size < 4 && intento < 60) {
                    intento++;
                    const desvio = Math.floor(Math.random() * 7) - 3;
                    const v = correcto + desvio;
                    if (v >= 0 && v !== correcto) set.add(v);
                }
                // Si el número es muy pequeño puede faltar variedad.
                let extra = correcto + 1;
                while (set.size < 4) { set.add(extra++); }
                return barajar(Array.from(set));
            }

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it = items[i];
                const ficha = FICHAS[it.op] || '🔢';

                titular(
                    it.op === 'contar' ? '¿Cuántos hay?' : 'Resuelve la operación',
                    'Ejercicio ' + (i + 1) + ' de ' + items.length
                );

                const c = panel('juego-centro');

                if (it.op === 'contar') {
                    // Se dibujan los objetos para contarlos de verdad.
                    const grupo = el('div', 'juego-fichas');
                    for (let k = 0; k < it.a; k++) {
                        grupo.appendChild(el('span', 'juego-ficha-obj', ficha));
                    }
                    c.appendChild(grupo);
                } else {
                    const linea = el('div', 'juego-operacion');
                    linea.appendChild(el('span', 'juego-num', String(it.a)));
                    linea.appendChild(el('span', 'juego-signo', SIGNOS[it.op] || '+'));
                    linea.appendChild(el('span', 'juego-num', String(it.b)));
                    linea.appendChild(el('span', 'juego-signo', '='));
                    linea.appendChild(el('span', 'juego-num', '?'));
                    c.appendChild(linea);

                    // Con números pequeños se muestran también los objetos,
                    // para que se pueda resolver contando.
                    if (it.op !== 'multiplicacion' && it.a <= 10 && it.b <= 10) {
                        const g = el('div', 'juego-fichas');
                        const total = it.op === 'suma' ? it.a + it.b : it.a;
                        for (let k = 0; k < total; k++) {
                            const s = el('span', 'juego-ficha-obj', ficha);
                            if (it.op === 'resta' && k >= it.resultado) s.classList.add('tachado');
                            g.appendChild(s);
                        }
                        c.appendChild(g);
                    }
                }

                const ops = el('div', 'juego-opciones');
                opciones(it.resultado).forEach((v) => {
                    const b = el('button', 'btn btn-secundario juego-ficha', String(v));
                    b.onclick = () => {
                        const bien = (v === it.resultado);
                        if (bien) {
                            aciertos++;
                            b.classList.add('acierto');
                            avisar('¡Correcto! 🎉', true);
                            i++;
                            marcarProgreso(i, items.length);
                            setTimeout(pinta, 950);
                        } else {
                            b.classList.add('error');
                            avisar('Cuenta otra vez', false);
                        }
                    };
                    ops.appendChild(b);
                });
                c.appendChild(ops);
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 14 · Número que falta en la secuencia */
        secuencia_numerica(datos, fin) {
            const items = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it = items[i];
                titular('¿Qué número falta?', 'Ejercicio ' + (i + 1) + ' de ' + items.length);

                const c = panel('juego-centro');

                const linea = el('div', 'juego-huecos');
                (it.secuencia || []).forEach((n) => {
                    const h = el('span', 'juego-hueco', n === null ? '?' : String(n));
                    if (n !== null) h.classList.add('lleno');
                    linea.appendChild(h);
                });
                c.appendChild(linea);

                const set = new Set([it.falta]);
                let d = 1;
                while (set.size < 4) {
                    if (it.falta - d > 0) set.add(it.falta - d);
                    if (set.size < 4) set.add(it.falta + d);
                    d++;
                }

                const ops = el('div', 'juego-opciones');
                barajar(Array.from(set)).forEach((v) => {
                    const b = el('button', 'btn btn-secundario juego-ficha', String(v));
                    b.onclick = () => {
                        const bien = (v === it.falta);
                        if (bien) {
                            aciertos++;
                            b.classList.add('acierto');
                            avisar('¡Ese era! ✅', true);
                            i++;
                            marcarProgreso(i, items.length);
                            setTimeout(pinta, 950);
                        } else {
                            b.classList.add('error');
                            avisar('Mira la serie otra vez', false);
                        }
                    };
                    ops.appendChild(b);
                });
                c.appendChild(ops);
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 15 · Emparejar imagen con palabra */
        emparejar(datos, fin) {
            const pares = Array.isArray(datos) ? datos : [];
            let unidos = 0;
            let elegido = null;

            limpiar();
            titular('Une cada imagen con su palabra', 'Toca una imagen y luego su palabra');

            const c = panel('juego-centro');
            const tabla = el('div', 'juego-emparejar');

            const colIzq = el('div', 'juego-columna');
            const colDer = el('div', 'juego-columna');

            barajar(pares).forEach((p) => {
                const b = el('button', 'juego-carta compacta');
                b.innerHTML = dibujoHtml(p.e);
                b.dataset.w = p.w;
                b.onclick = () => {
                    if (b.disabled) return;
                    colIzq.querySelectorAll('.elegida').forEach((x) => x.classList.remove('elegida'));
                    b.classList.add('elegida');
                    elegido = b;
                };
                colIzq.appendChild(b);
            });

            barajar(pares).forEach((p) => {
                const b = el('button', 'btn btn-secundario juego-ficha', p.w);
                b.dataset.w = p.w;
                b.onclick = () => {
                    if (b.disabled) return;
                    if (!elegido) {
                        avisar('Elige primero una imagen', false);
                        return;
                    }
                    if (elegido.dataset.w === p.w) {
                        elegido.classList.remove('elegida');
                        elegido.classList.add('acierto');
                        elegido.disabled = true;
                        b.classList.add('acierto');
                        b.disabled = true;
                        hablar(p.w);
                        unidos++;
                        elegido = null;
                        marcarProgreso(unidos, pares.length);
                        if (unidos === pares.length) {
                            avisar('¡Todas emparejadas! 🌟', true);
                            setTimeout(() => fin(pares.length, pares.length), 1100);
                        }
                    } else {
                        avisar('Esa no es', false);
                    }
                };
                colDer.appendChild(b);
            });

            tabla.appendChild(colIzq);
            tabla.appendChild(colDer);
            c.appendChild(tabla);

            marcarProgreso(0, pares.length);
        },

        /* 16 · Pronunciación: escuchar y repetir */
        pronunciacion(datos, fin) {
            const palabras = Array.isArray(datos) ? datos : [];
            let i = 0;

            function pinta() {
                limpiar();
                if (i >= palabras.length) return fin(palabras.length, palabras.length);

                const p = palabras[i];
                titular('Escucha y repite', 'Di la palabra en voz alta');

                const c = panel('juego-centro');
                c.appendChild(dibujo(p.e));
                c.appendChild(el('div', 'juego-palabra grande', p.w));

                const oir = el('button', 'btn btn-secundario', '🔊 Escuchar de nuevo');
                oir.onclick = () => hablar(p.w);
                c.appendChild(oir);

                // No se evalúa la voz: se confía en el niño. El valor
                // pedagógico está en escuchar y repetir, no en calificar.
                const listo = el('button', 'btn btn-principal', '✅ Ya la dije');
                listo.onclick = () => {
                    i++;
                    marcarProgreso(i, palabras.length);
                    pinta();
                };
                c.appendChild(listo);

                hablar(p.w);
            }

            marcarProgreso(0, palabras.length);
            pinta();
        },

        /* 13 · Completar la palabra con la letra que falta */
        completar_palabra(datos, fin) {
            const items = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            // Las opciones salen de las respuestas del propio ejercicio,
            // más las vocales, para que siempre haya varias donde elegir.
            const posibles = Array.from(new Set(
                items.map((x) => x.a).concat(['A', 'E', 'I', 'O', 'U'])
            ));

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it = items[i];
                titular('¿Qué letra falta?', 'Completa la palabra');

                const c = panel('juego-centro');
                c.appendChild(dibujo(it.e));

                const linea = el('div', 'juego-palabra grande');
                linea.textContent = (it.before || '') + '_' + (it.after || '');
                c.appendChild(linea);

                const ops = el('div', 'juego-opciones');

                // Se garantiza que la correcta esté entre las opciones.
                let eleccion = barajar(posibles.filter((x) => x !== it.a)).slice(0, 3);
                eleccion.push(it.a);
                eleccion = barajar(eleccion);

                eleccion.forEach((op) => {
                    const b = el('button', 'btn btn-secundario juego-ficha', op);
                    b.onclick = () => {
                        const bien = (op === it.a);
                        if (bien) {
                            aciertos++;
                            linea.textContent = (it.before || '') + it.a + (it.after || '');
                            b.classList.add('acierto');
                            hablar(linea.textContent);
                            avisar('¡Correcto! ✅', true);
                            i++;
                            marcarProgreso(i, items.length);
                            setTimeout(pinta, 1200);
                        } else {
                            b.classList.add('error');
                            avisar('Prueba con otra', false);
                        }
                    };
                    ops.appendChild(b);
                });

                c.appendChild(ops);
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 14 · Desafío final: cuestionario */
        desafio_final(datos, fin) {
            const preguntas = Array.isArray(datos) ? datos : [];
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= preguntas.length) return fin(aciertos, preguntas.length);

                const q = preguntas[i];
                titular(q.q, 'Pregunta ' + (i + 1) + ' de ' + preguntas.length);

                const c = panel('juego-centro');

                // El reto también ilustra. Antes no tenía dónde: era el
                // único minijuego de preguntas sin dibujo, y en preescolar
                // eso lo dejaba como un examen de lectura.
                if (q.e) c.appendChild(dibujo(q.e));

                const ops = el('div', 'juego-opciones vertical');

                estado.pista = () => descartarUnaMala(ops, q.a);

                q.opts.forEach((o, k) => {
                    const b = el('button', 'btn btn-secundario juego-boton', o);
                    b.onclick = () => {
                        olvidarLoDicho();

                        const bien = (k === q.a);
                        if (bien) aciertos++;
                        b.classList.add(bien ? 'acierto' : 'error');
                        avisar(bien ? '¡Correcto! 🏆' : 'Esa no era', bien);
                        i++;
                        marcarProgreso(i, preguntas.length);
                        setTimeout(pinta, 1000);
                    };
                    ops.appendChild(b);
                });

                c.appendChild(ops);

                decirOpciones(q.opts || []);
            }

            marcarProgreso(0, preguntas.length);
            pinta();
        },

        /* 19 · Laberinto: programar al robot y verlo moverse
         *
         * Antes esto era una pregunta de opción múltiple: «Botto está en
         * [0,0] y debe llegar a [0,3], ¿qué secuencia necesita?». El niño
         * tenía que imaginarse la cuadrícula en la cabeza, y si se
         * equivocaba no sabía por qué. Eso no es programar: es aritmética
         * de coordenadas disfrazada.
         *
         * Aquí ve el tablero, ve dónde está el robot y ve la meta. Arma
         * la secuencia, la ejecuta, y el robot recorre el camino paso a
         * paso delante de él. Si choca, ve exactamente en qué casilla se
         * equivocó. Ese ciclo —escribir, ejecutar, mirar, corregir— es lo
         * que de verdad se está enseñando.
         */
        laberinto(datos, fin) {
            const niveles = Array.isArray(datos) ? datos : [];
            let n = 0, resueltos = 0;

            const PASOS = { '↑': [-1, 0], '↓': [1, 0], '←': [0, -1], '→': [0, 1] };

            function pinta() {
                limpiar();
                if (n >= niveles.length) return fin(resueltos, niveles.length);

                const lv       = niveles[n];
                const filas    = Number(lv.filas) || 4;
                const columnas = Number(lv.columnas) || 4;
                const inicio   = lv.inicio || [0, 0];
                const meta     = lv.meta   || [0, 0];
                const muros    = (lv.muros || []).map((m) => m[0] + ',' + m[1]);

                const programa = [];
                let intentos = 0;
                let corriendo = false;

                /*
                 * La disposición reproduce la del sitio anterior: título,
                 * bajada, dos fichas de marcador, y debajo el tablero a la
                 * izquierda con el panel de comandos a la derecha. No es
                 * capricho — es la pantalla que el proyecto ya tenía
                 * publicada, y el sistema visual del sitio antiguo es la
                 * fuente de verdad de este rediseño.
                 */
                // Sin título propio: la cabecera del reproductor ya
                // muestra «🕹️ Guía a Botto». Aquí solo va la instrucción.
                titular('', lv.instruccion
                    || 'Usa los botones para agregar comandos y llevar a 🤖 Botto hasta la ⭐ estrella.');

                const c = panel('juego-centro laberinto-centro');

                // ── Marcadores ───────────────────────────────────────
                const marcador = el('div', 'lab-marcador');
                const fichaNivel = el('div', 'lab-ficha',
                    '🗺️ Nivel: ' + (n + 1) + '/' + niveles.length);
                const fichaHechos = el('div', 'lab-ficha', '✅ Resueltos: ' + resueltos);
                marcador.appendChild(fichaNivel);
                marcador.appendChild(fichaHechos);
                c.appendChild(marcador);

                // ── Tablero y panel, uno al lado del otro ────────────
                const mundo = el('div', 'lab-mundo');
                c.appendChild(mundo);

                const caja = el('div', 'lab-caja');
                mundo.appendChild(caja);

                const tablero = el('div', 'laberinto');
                // El número de columnas se pasa como variable y no como
                // grid-template-columns armado aquí: así el CSS puede
                // encoger la casilla en pantalla estrecha sin que el JS
                // tenga que saber nada de tamaños.
                tablero.style.setProperty('--columnas', String(columnas));
                caja.appendChild(tablero);

                function dibujar(pos, choque) {
                    tablero.innerHTML = '';
                    for (let r = 0; r < filas; r++) {
                        for (let k = 0; k < columnas; k++) {
                            const casilla = el('div', 'lab-casilla');
                            const clave = r + ',' + k;

                            if (muros.indexOf(clave) >= 0) {
                                casilla.classList.add('muro');
                                casilla.textContent = '🧱';
                            } else if (r === meta[0] && k === meta[1]) {
                                casilla.classList.add('meta');
                                casilla.textContent = '⭐';
                            }

                            if (pos && r === pos[0] && k === pos[1]) {
                                casilla.classList.add('robot');
                                if (choque) casilla.classList.add('choque');
                                casilla.textContent = '🤖';
                            }

                            tablero.appendChild(casilla);
                        }
                    }
                }

                // ── Panel de comandos ────────────────────────────────
                const panelCmd = el('div', 'lab-panel');
                mundo.appendChild(panelCmd);

                panelCmd.appendChild(el('div', 'lab-titulo', '🎮 Comandos'));

                const mandos = el('div', 'lab-mandos');
                panelCmd.appendChild(mandos);

                // El orden es ⬆️ ⬇️ ⬅️ ➡️ en dos columnas, como en el
                // sitio anterior: los verticales arriba y los laterales
                // abajo.
                ['⬆️', '⬇️', '⬅️', '➡️'].forEach((icono, k) => {
                    const flecha = ['↑', '↓', '←', '→'][k];
                    const nombre = ['Arriba', 'Abajo', 'Izquierda', 'Derecha'][k];
                    const b = el('button', 'lab-flecha', icono);
                    b.setAttribute('aria-label', 'Agregar comando: ' + nombre);
                    b.setAttribute('title', nombre);
                    b.onclick = () => {
                        if (corriendo) return;
                        programa.push(flecha);
                        repintaPrograma();
                    };
                    mandos.appendChild(b);
                });

                panelCmd.appendChild(el('div', 'lab-titulo chico', '📋 Secuencia:'));

                const tira = el('div', 'lab-secuencia');
                panelCmd.appendChild(tira);

                function repintaPrograma() {
                    tira.textContent = programa.length ? programa.join(' ') : '—';
                }

                const acciones = el('div', 'lab-acciones');
                panelCmd.appendChild(acciones);

                const ejecutar = el('button', 'lab-ejecutar', '▶️ Ejecutar');
                acciones.appendChild(ejecutar);

                const borrar = el('button', 'lab-borrar', '✕');
                borrar.setAttribute('aria-label', 'Borrar la secuencia');
                borrar.setAttribute('title', 'Borrar');
                borrar.onclick = () => {
                    if (corriendo) return;
                    programa.length = 0;
                    repintaPrograma();
                    dibujar(inicio);
                };
                acciones.appendChild(borrar);

                // ── Ejecución paso a paso ────────────────────────────
                ejecutar.onclick = () => {
                    if (corriendo) return;

                    if (!programa.length) {
                        avisar('⚠️ ¡Agrega comandos primero!', false);
                        return;
                    }

                    corriendo = true;
                    intentos++;

                    let pos = [inicio[0], inicio[1]];
                    let i = 0;

                    dibujar(pos);

                    const reloj = setInterval(() => {

                        // Se acabaron los pasos sin llegar a la meta.
                        if (i >= programa.length) {
                            clearInterval(reloj);
                            corriendo = false;

                            if (pos[0] === meta[0] && pos[1] === meta[1]) {
                                return llegar();
                            }

                            avisar('🤖 Botto se quedó a medio camino', false);
                            return fallar();
                        }

                        const d = PASOS[programa[i]];
                        i++;

                        const siguiente = [pos[0] + d[0], pos[1] + d[1]];
                        const clave = siguiente[0] + ',' + siguiente[1];

                        const fuera = siguiente[0] < 0 || siguiente[0] >= filas
                                   || siguiente[1] < 0 || siguiente[1] >= columnas;

                        if (fuera || muros.indexOf(clave) >= 0) {
                            clearInterval(reloj);
                            corriendo = false;
                            dibujar(pos, true);
                            avisar(fuera
                                ? '💥 ¡Botto se salió del tablero! Revisa tu secuencia.'
                                : '💥 ¡Botto chocó! Revisa tu secuencia.', false);
                            return fallar();
                        }

                        pos = siguiente;
                        dibujar(pos);

                        // Llegar antes de gastar todos los pasos también vale.
                        if (pos[0] === meta[0] && pos[1] === meta[1]) {
                            clearInterval(reloj);
                            corriendo = false;
                            llegar();
                        }

                    }, 420);
                };

                function llegar() {
                    resueltos++;
                    fichaHechos.textContent = '✅ Resueltos: ' + resueltos;
                    avisar('⭐ ¡Botto llegó a la estrella! ¡Genial!', true);
                    n++;
                    marcarProgreso(n, niveles.length);
                    setTimeout(pinta, 1500);
                }

                /*
                 * Al fallar se borra el programa y se vuelve al inicio,
                 * nunca se pasa de nivel: equivocarse es parte de
                 * programar y no debe costar el ejercicio.
                 *
                 * A partir del tercer intento aparece cuántos pasos hacen
                 * falta como mínimo. Sin esa salida, un niño atascado se
                 * queda atascado, que es exactamente lo que le pasaba en
                 * el minijuego de teclado antes de darle una ayuda.
                 */
                function fallar() {
                    programa.length = 0;
                    repintaPrograma();
                    setTimeout(() => dibujar(inicio), 900);

                    if (intentos >= 3) {
                        const minimo = Math.abs(meta[0] - inicio[0]) + Math.abs(meta[1] - inicio[1]);
                        const pista = $('.lab-pista');
                        if (!pista) {
                            const p = el('p', 'lab-pista',
                                '💡 Pista: se puede llegar en ' + minimo + ' pasos como mínimo.');
                            c.appendChild(p);
                        }
                    }
                }

                dibujar(inicio);
                repintaPrograma();
            }

            marcarProgreso(0, niveles.length);
            pinta();
        },

        /* 20 · Completar huecos en un texto
         *
         * Un texto con palabras quitadas y un banco de fichas debajo. Es
         * distinto de `completar_palabra`, que esconde UNA LETRA dentro de
         * una palabra suelta: aquí lo que falta es una palabra entera y
         * solo se puede acertar leyendo la frase alrededor.
         *
         * Los huecos se llenan EN ORDEN y el siguiente va resaltado. Poder
         * tocarlos en cualquier orden suena más libre, pero obliga al niño
         * a llevar la cuenta de dónde iba en vez de a leer.
         */
        completar_texto(datos, fin) {
            const d = desempacar(datos, 'Completa el texto',
                                        'Toca la palabra que falta en cada hueco');
            const items = d.items;
            let i = 0, aciertos = 0;

            function pinta() {
                limpiar();
                if (i >= items.length) return fin(aciertos, items.length);

                const it     = items[i];
                const huecos = Array.isArray(it.huecos) ? it.huecos : [];
                const trozos = String(it.texto || '').split('___');
                let puestos  = 0;

                titular(d.t, d.s);

                const c = panel('juego-centro');

                if (it.titulo) {
                    c.appendChild(el('div', 'juego-cloze-titulo', it.titulo));
                }

                const parrafo = el('p', 'juego-cloze');
                c.appendChild(parrafo);

                const bolsa = el('div', 'juego-opciones');
                c.appendChild(bolsa);

                function repinta() {
                    parrafo.innerHTML = '';
                    trozos.forEach((t, k) => {
                        parrafo.appendChild(el('span', 'juego-cloze-texto', t));
                        if (k < trozos.length - 1) {
                            const h = el('span', 'juego-cloze-hueco',
                                          k < puestos ? huecos[k] : '');
                            if (k < puestos)       h.classList.add('lleno');
                            else if (k === puestos) h.classList.add('activo');
                            parrafo.appendChild(h);
                        }
                    });
                }

                // Las fichas del banco: las que faltan más los señuelos.
                const fichas = barajar(huecos.concat(it.extra || []));

                // La pista apaga un señuelo, nunca una respuesta válida.
                estado.pista = () => {
                    const sobrantes = Array.from(bolsa.children).filter((b) =>
                        !b.disabled && huecos.indexOf(b.textContent) < 0);
                    if (sobrantes.length) {
                        const elegida = sobrantes[Math.floor(Math.random() * sobrantes.length)];
                        elegida.disabled = true;
                        elegida.classList.add('descartada');
                    }
                };

                fichas.forEach((palabra) => {
                    const b = el('button', 'btn btn-secundario juego-ficha', palabra);
                    b.onclick = () => {
                        if (b.disabled) return;

                        if (huecos[puestos] === palabra) {
                            puestos++;
                            b.disabled = true;
                            b.classList.add('usada');
                            repinta();
                            marcarProgreso(i + puestos / huecos.length, items.length);

                            if (puestos === huecos.length) {
                                aciertos++;
                                hablar(trozos.join(' '));
                                avisar('¡Texto completo! 📖', true);
                                i++;
                                setTimeout(pinta, 1400);
                            }
                        } else {
                            avisar('Esa no encaja ahí. Lee la frase otra vez', false);
                        }
                    };
                    bolsa.appendChild(b);
                });

                repinta();
            }

            marcarProgreso(0, items.length);
            pinta();
        },

        /* 21 · Crucigrama
         *
         * La rejilla y la posición de cada palabra vienen resueltas desde
         * el servidor (ver `crucigrama()` en contenido/ayudas.php): el
         * motor solo dibuja y comprueba.
         *
         * Se resuelve por pistas, no escribiendo letra a letra en la
         * cuadrícula. Con un dedo sobre una tableta, ir casilla por casilla
         * es un ejercicio de puntería, y lo que se está practicando es
         * vocabulario.
         *
         * La pista puede ser un texto («Animal que maúlla») o un DIBUJO.
         * Con dibujo, el mismo motor sirve para un niño que aún no lee.
         */
        crucigrama(datos, fin) {
            const d        = datos || {};
            const filas    = Number(d.filas) || 1;
            const columnas = Number(d.columnas) || 1;
            const palabras = Array.isArray(d.palabras) ? d.palabras : [];
            const resueltas = new Set();

            limpiar();
            titular(d.t || 'Crucigrama', d.s || 'Toca una pista y escribe la palabra');

            const c = panel('juego-centro');

            /*
             * Numeración compartida: si una palabra horizontal y una
             * vertical empiezan en la misma casilla, llevan el MISMO
             * número. Es como funciona cualquier crucigrama y como el niño
             * lo va a ver en papel.
             */
            const inicios = [];
            palabras.forEach((p) => {
                const k = p.fila + ',' + p.col;
                if (inicios.indexOf(k) < 0) inicios.push(k);
            });
            inicios.sort((a, b) => {
                const A = a.split(',').map(Number), B = b.split(',').map(Number);
                return A[0] - B[0] || A[1] - B[1];
            });
            const numero = {};
            inicios.forEach((k, n) => { numero[k] = n + 1; });

            // Qué casillas forman parte de alguna palabra, y con qué letra.
            const activas = {};
            palabras.forEach((p) => {
                const letras = String(p.w).split('');
                letras.forEach((letra, k) => {
                    const r = p.fila + (p.dir === 'v' ? k : 0);
                    const col = p.col + (p.dir === 'h' ? k : 0);
                    activas[r + ',' + col] = letra;
                });
            });

            // ── La rejilla ───────────────────────────────────────────
            const tabla = el('div', 'juego-crucigrama');
            tabla.style.setProperty('--columnas', String(columnas));
            c.appendChild(tabla);

            for (let r = 0; r < filas; r++) {
                for (let k = 0; k < columnas; k++) {
                    const clave = r + ',' + k;
                    const cel = el('div', 'cruci-celda');

                    if (activas[clave] === undefined) {
                        cel.classList.add('vacia');
                    } else {
                        cel.dataset.celda = clave;
                        if (numero[clave]) {
                            cel.appendChild(el('span', 'cruci-num', String(numero[clave])));
                        }
                        cel.appendChild(el('span', 'cruci-letra', ''));
                    }
                    tabla.appendChild(cel);
                }
            }

            /** Escribe una palabra resuelta en la rejilla. */
            function pintarPalabra(p) {
                String(p.w).split('').forEach((letra, k) => {
                    const r = p.fila + (p.dir === 'v' ? k : 0);
                    const col = p.col + (p.dir === 'h' ? k : 0);
                    const cel = tabla.querySelector('[data-celda="' + r + ',' + col + '"]');
                    if (cel) {
                        cel.classList.add('resuelta');
                        cel.querySelector('.cruci-letra').textContent = letra;
                    }
                });
            }

            // ── Las pistas ───────────────────────────────────────────
            const zonaPistas = el('div', 'cruci-pistas');
            c.appendChild(zonaPistas);

            [['h', 'Horizontales'], ['v', 'Verticales']].forEach(([dir, titulo]) => {
                const grupo = palabras.filter((p) => p.dir === dir);
                if (!grupo.length) return;

                const caja = el('div', 'cruci-grupo');
                caja.appendChild(el('h3', 'cruci-grupo-titulo', titulo));

                grupo.sort((a, b) => numero[a.fila + ',' + a.col] - numero[b.fila + ',' + b.col]);

                grupo.forEach((p) => {
                    const n = numero[p.fila + ',' + p.col];
                    const b = el('button', 'btn btn-secundario cruci-pista');
                    b.dataset.palabra = p.w;

                    b.appendChild(el('span', 'cruci-pista-num', String(n)));

                    // Con dibujo, el dibujo ES la pista y va grande.
                    if (p.e) {
                        b.appendChild(dibujo(p.e, 'cruci-pista-dibujo'));
                    }
                    if (p.pista) {
                        b.appendChild(el('span', 'cruci-pista-texto', p.pista));
                    }

                    b.onclick = () => abrir(p, b);
                    caja.appendChild(b);
                });

                zonaPistas.appendChild(caja);
            });

            // ── Responder una pista ──────────────────────────────────
            const respuesta = el('div', 'cruci-respuesta');
            c.appendChild(respuesta);

            function abrir(p, boton) {
                if (resueltas.has(p.w)) return;

                respuesta.innerHTML = '';
                let intentos = 0;

                // Se resalta en la rejilla la palabra que se está pensando.
                tabla.querySelectorAll('.activa').forEach((x) => x.classList.remove('activa'));
                String(p.w).split('').forEach((_, k) => {
                    const r = p.fila + (p.dir === 'v' ? k : 0);
                    const col = p.col + (p.dir === 'h' ? k : 0);
                    const cel = tabla.querySelector('[data-celda="' + r + ',' + col + '"]');
                    if (cel && !cel.classList.contains('resuelta')) cel.classList.add('activa');
                });

                const caja = el('div', 'cruci-caja');

                if (p.e) caja.appendChild(dibujo(p.e, 'juego-emoji'));
                if (p.pista) caja.appendChild(el('p', 'juego-texto', p.pista));

                caja.appendChild(el('p', 'juego-nota',
                    'Tiene ' + String(p.w).length + ' letras'));

                const campo = el('input', 'juego-campo');
                campo.type = 'text';
                campo.autocomplete = 'off';
                campo.setAttribute('aria-label', 'Escribe la palabra de la pista ' +
                                    numero[p.fila + ',' + p.col]);
                caja.appendChild(campo);

                const enviar = el('button', 'btn btn-principal', 'Comprobar');
                caja.appendChild(enviar);

                const ayuda = el('div', 'juego-ayuda');
                caja.appendChild(ayuda);

                function comprobar() {
                    const val = String(campo.value || '').trim().toUpperCase();

                    if (val === String(p.w).toUpperCase()) {
                        resueltas.add(p.w);
                        pintarPalabra(p);
                        boton.classList.add('resuelta');
                        boton.disabled = true;
                        tabla.querySelectorAll('.activa').forEach((x) => x.classList.remove('activa'));
                        respuesta.innerHTML = '';

                        hablar(p.w);
                        avisar('¡' + p.w + '! ✅', true);
                        marcarProgreso(resueltas.size, palabras.length);

                        if (resueltas.size === palabras.length) {
                            setTimeout(() => fin(palabras.length, palabras.length), 1200);
                        }
                        return;
                    }

                    intentos++;
                    avisar('Todavía no. Mira la pista otra vez', false);
                    campo.select();

                    // Misma salida de emergencia que en el teclado: al
                    // tercer intento, la primera letra. Un niño atascado
                    // tiene que poder salir.
                    if (intentos >= 3 && !ayuda.children.length) {
                        const pista = el('button', 'btn btn-secundario btn-chico',
                                          '👀 Ver la primera letra');
                        pista.onclick = () => {
                            campo.value = String(p.w).charAt(0);
                            campo.focus();
                        };
                        ayuda.appendChild(pista);
                    }
                }

                enviar.onclick = comprobar;
                campo.onkeydown = (ev) => { if (ev.key === 'Enter') comprobar(); };

                respuesta.appendChild(caja);
                campo.focus();
            }

            marcarProgreso(0, palabras.length);
        },
    };

    // ── Carga y ciclo de vida ────────────────────────────────────────

    async function abrirEstacion(id) {
        const ficha = estado.estaciones.find((e) => e.id === id);

        // El servidor decide igualmente, pero si ya sabemos que está
        // bloqueada evitamos una petición inútil.
        if (ficha && !ficha.desbloqueada) {
            return mostrarInvitacion();
        }

        limpiar();
        zona().appendChild(el('div', 'juego-cargando', 'Cargando…'));

        let datos;
        try {
            const r = await fetch(estado.base + 'api/estacion.php?estacion=' + encodeURIComponent(id), {
                credentials: 'same-origin',
            });

            try {
                datos = await r.json();
            } catch (errJson) {
                // Algunos servidores sustituyen el cuerpo de las respuestas
                // de error por su propia página, y entonces no hay JSON que
                // leer. En ese caso decidimos por el código de estado: un
                // 402 o un 403 sobre una estación significa que hace falta
                // suscripción, y el jugador merece ver la invitación en vez
                // de un mensaje de error.
                if (r.status === 401 || r.status === 402 || r.status === 403) {
                    return mostrarInvitacion(
                        r.status === 401 ? INVITACION_CUENTA : null
                    );
                }
                throw errJson;
            }
        } catch (e) {
            limpiar();
            zona().appendChild(el('div', 'juego-aviso mal', 'No se pudo cargar la estación.'));
            return;
        }

        if (!datos.ok) {
            if (datos.motivo === 'suscripcion_requerida') {
                return mostrarInvitacion(datos);
            }
            limpiar();
            zona().appendChild(el('div', 'juego-aviso mal', datos.error || 'No se pudo cargar.'));
            return;
        }

        estado.actual = datos.estacion;
        estado.inicio = Date.now();

        // La ayuda se ofrece por estación, no por sesión: quien acaba de
        // resolver una difícil empieza la siguiente con la cuenta a cero.
        estado.fallos = 0;
        estado.pista  = null;

        // Cada estación fija su idioma de lectura. Se reinicia siempre,
        // para que salir de una estación de inglés no deje al resto de
        // la actividad hablando en inglés.
        idiomaVoz = datos.estacion.idioma || 'es-ES';

        /*
         * Y si el niño lee o no. Lo decide el SERVIDOR con el nivel de la
         * actividad; aquí solo se obedece. Si viniera del navegador,
         * bastaría con tocarlo para apagarle la voz a un niño que la
         * necesita — o para encenderla donde estorba.
         */
        sinLectura = datos.actividad.sin_lectura === true;
        document.body.classList.toggle('sin-lectura', sinLectura);

        marcarActivo(id);

        const juego = JUEGOS[datos.estacion.tipo];

        if (!juego) {
            limpiar();
            titular(datos.estacion.titulo, 'Este tipo de minijuego aún no tiene motor');
            zona().appendChild(el('div', 'juego-aviso mal',
                'Tipo no reconocido: ' + datos.estacion.tipo));
            return;
        }

        juego(datos.estacion.contenido, terminarEstacion, {
            sonido: datos.estacion.sonido,
            letra: datos.actividad.letra,
        });
    }

    function marcarActivo(id) {
        document.querySelectorAll('.mapa-estacion').forEach((n) => {
            n.classList.toggle('activa', Number(n.dataset.id) === id);
        });
        const cab = $('#titulo-estacion');
        const ficha = estado.estaciones.find((e) => e.id === id);
        if (cab && ficha) {
            cab.textContent = (ficha.icono ? ficha.icono + ' ' : '') + ficha.titulo;
        }
    }

    /**
     * Se llama cuando un minijuego termina.
     *
     * `saltada` marca que el niño pagó por pasar. La estación se guarda
     * como hecha —para que se desbloquee la siguiente— pero con cero
     * estrellas y cero monedas: comprar el paso no compra el mérito.
     */
    async function terminarEstacion(aciertos, total, saltada) {
        const pct = saltada ? 100
                            : (total > 0 ? Math.round((aciertos / total) * 100) : 100);
        const segundos = Math.round((Date.now() - estado.inicio) / 1000);
        const estrellas = saltada ? 0
                                  : (pct >= 100 ? 3 : (pct >= 70 ? 2 : (pct >= 40 ? 1 : 0)));
        const monedas = saltada ? 0 : aciertos;

        limpiar();

        const c = panel('juego-centro juego-final');

        if (saltada) {
            c.appendChild(el('div', 'juego-emoji grande', '⏭️'));
            c.appendChild(el('h2', null, 'Estación saltada'));
            c.appendChild(el('p', 'juego-texto',
                'Puedes volver cuando quieras y hacerla para ganar sus estrellas.'));
        } else {
            /*
             * ─────────────────────────────────────────────────────────
             *  LA PANTALLA QUE CIERRA EL ESFUERZO
             * ─────────────────────────────────────────────────────────
             *
             * Aquí es donde el personaje tiene que aparecer: es el
             * momento en que el niño acaba de trabajar y espera algo. Un
             * 🎉 igual para todos no era ese algo.
             *
             * Tres ánimos, y NINGUNO es tristeza. La diferencia entre
             * hacerlo bien y hacerlo regular se ve en las estrellas, que
             * es donde se tiene que ver: si además el personaje se
             * decepcionara, el niño aprendería que equivocarse decepciona
             * a alguien, y eso no se le quita en años.
             */
            const perfecto = (estrellas === 3);
            const bien     = (pct >= 70);

            const animo = perfecto ? 'celebra' : (bien ? 'contento' : 'anima');
            const quien = personajeEl(animo, 'grande');

            if (quien) {
                c.appendChild(quien);
            } else {
                // Sin sesión no hay personaje: se conserva el emoji.
                c.appendChild(el('div', 'juego-emoji grande', bien ? '🎉' : '💪'));
            }

            c.appendChild(el('h2', null,
                perfecto ? '¡Perfecto!' : (bien ? '¡Muy bien!' : '¡Casi! Vas a poder')));

            c.appendChild(el('p', 'juego-texto', aciertos + ' de ' + total + ' · ' + pct + '%'));

            // Los papelillos SOLO con las tres estrellas. Si cayeran
            // siempre dejarían de significar nada, que es lo que le pasa
            // a una celebración que no se puede perder.
            if (perfecto) {
                papelillos(c, 26);
            }

            /*
             * El final también se dice. Es la pantalla que cierra el
             * esfuerzo, y en preescolar es justo donde el niño necesita
             * oír que lo hizo bien: el porcentaje y las estrellas no le
             * dicen nada todavía.
             */
            if (sinLectura) {
                decir(perfecto ? '¡Perfecto! Lo hiciste todo bien.'
                    : (bien ? '¡Muy bien! Terminaste.'
                            : '¡Casi! Puedes volver a intentarlo.'));
            }
        }
        c.appendChild(el('div', 'juego-estrellas', '⭐'.repeat(estrellas) + '☆'.repeat(3 - estrellas)));

        /*
         * Las monedas ganadas, en el momento. Se enseñan aquí y no solo
         * en el marcador de la cabecera porque la recompensa tiene que
         * llegar pegada al esfuerzo: un número que sube en otra pantalla,
         * dos clics después, no lo asocia nadie con lo que acaba de hacer.
         */
        let premio = null;

        if (monedas > 0) {
            premio = el('p', 'juego-premio');
            premio.innerHTML = '<span aria-hidden="true">🪙</span> +' + monedas +
                               (monedas === 1 ? ' moneda' : ' monedas');
            c.appendChild(premio);

            // Las monedas suben desde el premio. Es lo que liga el número
            // que crece en la cabecera con lo que se acaba de hacer.
            setTimeout(() => volarMonedas(premio, monedas), 420);
        }

        // Guardar el progreso (solo si hay sesión; el servidor decide).
        try {
            const cuerpo = new URLSearchParams({
                csrf_token: estado.csrf,
                estacion: String(estado.actual.id),
                porcentaje: String(pct),
                puntos: String(saltada ? 0 : aciertos * 10),
                estrellas: String(estrellas),
                monedas: String(monedas),
                segundos: String(segundos),
            });

            const r = await fetch(estado.base + 'api/progreso.php', {
                method: 'POST',
                credentials: 'same-origin',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: cuerpo,
            });
            const res = await r.json();

            if (res.ok && res.guardado) {
                marcarHecha(estado.actual.id);
                c.appendChild(el('p', 'juego-nota',
                    'Progreso guardado · ' + res.resumen.completadas + ' de ' + res.resumen.total + ' estaciones'));

                // El contador de la cabecera, al día en el momento.
                refrescarMarcador(res.cartera);

                /*
                 * Los logros recién conseguidos, uno detrás de otro.
                 *
                 * Se escalonan: dos carteles a la vez se tapan, y el
                 * segundo pasa sin que nadie lo lea. Empiezan después de
                 * la celebración del personaje para no competir con ella.
                 */
                (res.logros_nuevos || []).forEach((logro, i) => {
                    anunciarLogro(logro, 900 + i * 1400);
                });

                // Con un logro, la fiesta se hace grande aunque la
                // estación no haya salido perfecta: conseguir algo que se
                // venía persiguiendo merece papelillos.
                if ((res.logros_nuevos || []).length > 0 && !saltada) {
                    setTimeout(() => papelillos(c, 18), 900);
                }
            } else if (res.ok && !res.guardado) {
                const n = el('p', 'juego-nota');
                n.innerHTML = '<a href="' + estado.base + 'registro.php">Crea una cuenta gratis</a> para guardar tu progreso.';
                c.appendChild(n);
            }
        } catch (e) {
            /* si falla el guardado, el juego no se interrumpe */
        }

        const acciones = el('div', 'juego-opciones');

        const otra = el('button', 'btn btn-secundario', '↻ Repetir');
        otra.onclick = () => abrirEstacion(estado.actual.id);
        acciones.appendChild(otra);

        const sig = siguienteDesbloqueada(estado.actual.id);
        if (sig) {
            const b = el('button', 'btn btn-principal', 'Siguiente estación →');
            b.onclick = () => abrirEstacion(sig.id);
            acciones.appendChild(b);
        } else if (estado.estaciones.some((e) => !e.desbloqueada)) {
            const b = el('button', 'btn btn-oro', '🔓 Desbloquear el resto');
            b.onclick = () => { window.location.href = estado.base + 'planes/'; };
            acciones.appendChild(b);
        }

        c.appendChild(acciones);
    }

    function siguienteDesbloqueada(idActual) {
        const i = estado.estaciones.findIndex((e) => e.id === idActual);
        for (let k = i + 1; k < estado.estaciones.length; k++) {
            if (estado.estaciones[k].desbloqueada) return estado.estaciones[k];
        }
        return null;
    }

    function marcarHecha(id) {
        const n = document.querySelector('.mapa-estacion[data-id="' + id + '"]');
        if (n) n.classList.add('hecha');
        const ficha = estado.estaciones.find((e) => e.id === id);
        if (ficha) ficha.hecha = true;
    }

    /*
     * Respaldo para cuando el servidor responde 401 pero el cuerpo JSON
     * no llega (algún proxy o configuración de Apache lo sustituye por su
     * propia página de error). El texto lo manda normalmente el servidor;
     * esto solo evita que el niño vea «no se pudo cargar» cuando lo único
     * que pasa es que aún no tiene cuenta.
     */
    const INVITACION_CUENTA = {
        motivo: 'cuenta_requerida',
        icono: '🎁',
        titulo: 'Crea tu cuenta para empezar a jugar',
        mensaje: 'Es gratis y toma un minuto. Con tu cuenta guardamos tu progreso, ' +
                 'tus estrellas y por dónde ibas.',
        // Sin `url` a propósito: así se arma abajo con `estado.base`. Una
        // ruta relativa aquí resolvería contra /actividades/ y llevaría a
        // una página que no existe.
        accion: { texto: 'Crear cuenta gratis' },
    };

    function mostrarInvitacion(datos) {
        limpiar();

        const cuenta = datos && datos.motivo === 'cuenta_requerida';

        const c = panel('juego-centro invitacion-juego');
        c.appendChild(el('div', 'juego-emoji grande', cuenta ? '🎁' : '🔒'));
        c.appendChild(el('h2', null,
            (datos && datos.titulo) || 'Esta experiencia hace parte de la Biblioteca Completa.'));
        c.appendChild(el('p', 'juego-texto',
            (datos && datos.mensaje) ||
            'Desbloquea todas las actividades actuales y las nuevas que publiquemos durante tu suscripción.'));

        // Registrarse es gratis y suscribirse no: no deben verse igual.
        const b = el('a', cuenta ? 'btn btn-principal' : 'btn btn-oro',
            (datos && datos.accion && datos.accion.texto) || 'Ver Biblioteca Completa');
        b.href = (datos && datos.accion && datos.accion.url) ||
                 (estado.base + (cuenta ? 'registro.php' : 'planes/'));
        c.appendChild(b);
    }

    // ── Arranque ─────────────────────────────────────────────────────

    function iniciar(config) {
        estado.estaciones = config.estaciones || [];
        estado.base = config.base;
        estado.csrf = config.csrf || '';
        estado.slug = config.slug || '';
        estado.personaje = config.personaje || null;

        document.querySelectorAll('.mapa-estacion').forEach((n) => {
            n.addEventListener('click', () => {
                const id = Number(n.dataset.id);
                const ficha = estado.estaciones.find((e) => e.id === id);
                if (ficha && ficha.desbloqueada) abrirEstacion(id);
                else mostrarInvitacion();
            });
        });

        const primera = estado.estaciones.find((e) => e.desbloqueada);
        if (primera) abrirEstacion(primera.id);
        else mostrarInvitacion();
    }

    window.MotorActividades = { iniciar: iniciar };
})();
