/**
 * pago-tarjeta.js — El formulario de tarjeta, en nuestro sitio
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ ESTE ARCHIVO NO PUEDE LEER LA TARJETA
 * ─────────────────────────────────────────────────────────────────────
 *
 * Los tres campos sensibles —número, código de seguridad y
 * vencimiento— no son `<input>` nuestros: los dibuja el SDK de Mercado
 * Pago dentro de iframes suyos («Secure Fields»). Se les pasa el tipo
 * de letra y el color para que se vean como el resto del formulario,
 * pero su contenido vive en otro documento y la política de mismo
 * origen impide leerlo desde aquí.
 *
 * Eso significa que ni este archivo, ni una extensión del navegador, ni
 * un script inyectado en esta página pueden ver un número de tarjeta.
 * `createCardToken()` lo cambia por un token de un solo uso, y ese
 * token es lo único que sale hacia nuestro servidor.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LO QUE EL CLIENTE VE
 * ─────────────────────────────────────────────────────────────────────
 *
 * Nada de Mercado Pago. Ni logotipo, ni redirección, ni una pestaña
 * nueva. La única excepción posible es la verificación del banco (3D
 * Secure) y esa pantalla es del BANCO que emitió la tarjeta: la decide
 * el emisor y aparecería igual comprando en cualquier otro sitio.
 */

(function () {
    'use strict';

    const raiz = document.getElementById('pago-tarjeta');

    if (!raiz || typeof MercadoPago === 'undefined') {
        return;
    }

    const cfg = JSON.parse(raiz.dataset.config);
    const mp = new MercadoPago(cfg.publicKey, { locale: 'es-CO' });

    const form      = document.getElementById('f-tarjeta');
    const boton     = document.getElementById('b-pagar');
    const aviso     = document.getElementById('pago-aviso');
    const selCuotas = document.getElementById('c-cuotas');
    const marca     = document.getElementById('c-marca');

    let metodoId = '';
    let emisorId = '';

    /* ── Avisos ──────────────────────────────────────────────────── */

    function decir(texto, tipo) {
        aviso.textContent = texto;
        aviso.className = 'aviso ' + (tipo || 'info');
        aviso.hidden = false;
        // Para quien usa lector de pantalla: `alert` lo anuncia solo.
        aviso.setAttribute('role', tipo === 'mal' ? 'alert' : 'status');
    }

    function callar() {
        aviso.hidden = true;
    }

    function ocupado(si, texto) {
        boton.disabled = si;
        boton.textContent = si ? (texto || 'Procesando…') : cfg.textoBoton;
        // `aria-busy` evita que un lector de pantalla lea la página
        // entera de nuevo mientras esperamos al banco.
        form.setAttribute('aria-busy', si ? 'true' : 'false');
    }

    /* ── Los campos seguros ──────────────────────────────────────── */
    //
    // El estilo va aquí y no en el CSS porque el iframe es de otro
    // origen: nuestra hoja de estilos no lo alcanza. Se le pasan los
    // mismos valores que usa el resto del formulario para que no se
    // note la costura.
    const estilo = {
        color: '#2f3b52',
        'font-size': '16px',
        'font-family': "'Fredoka', system-ui, sans-serif",
        placeholderColor: '#8b95a5',
    };

    const numero = mp.fields.create('cardNumber', {
        placeholder: 'Número de la tarjeta',
        style: estilo,
    }).mount('c-numero');

    mp.fields.create('expirationDate', {
        placeholder: 'MM/AA',
        style: estilo,
    }).mount('c-vence');

    mp.fields.create('securityCode', {
        placeholder: 'Código',
        style: estilo,
    }).mount('c-codigo');

    /* ── Al reconocer la tarjeta ─────────────────────────────────── */
    //
    // El SDK avisa en cuanto los primeros dígitos identifican al emisor.
    // Con eso se piden las cuotas reales de ESA tarjeta: ofrecer «12
    // cuotas» a una débito que no las admite termina en un rechazo por
    // `invalid_installments`, que el cliente no entiende.
    numero.on('binChange', async (datos) => {
        const bin = datos.bin;

        if (!bin) {
            metodoId = '';
            emisorId = '';
            marca.textContent = '';
            selCuotas.innerHTML = '<option value="1">1 cuota</option>';
            return;
        }

        try {
            const metodos = await mp.getPaymentMethods({ bin });
            const m = metodos.results && metodos.results[0];

            if (!m) { return; }

            metodoId = m.id;
            marca.textContent = m.name || '';

            const emisores = await mp.getIssuers({ paymentMethodId: metodoId, bin });
            emisorId = emisores && emisores[0] ? emisores[0].id : '';

            const cuotas = await mp.getInstallments({
                amount: String(cfg.monto),
                bin,
                paymentTypeId: 'credit_card',
            });

            selCuotas.innerHTML = '';

            const opciones = (cuotas && cuotas[0] && cuotas[0].payer_costs) || [];

            if (!opciones.length) {
                selCuotas.innerHTML = '<option value="1">1 cuota</option>';
                return;
            }

            opciones.forEach((o) => {
                const op = document.createElement('option');
                op.value = o.installments;
                op.textContent = o.recommended_message
                    || (o.installments + (o.installments === 1 ? ' cuota' : ' cuotas'));
                selCuotas.appendChild(op);
            });
        } catch (e) {
            // Que no se pueda consultar las cuotas no debe impedir pagar
            // en una: se sigue con lo mínimo y el cobro funciona igual.
            selCuotas.innerHTML = '<option value="1">1 cuota</option>';
        }
    });

    /* ── Enviar ──────────────────────────────────────────────────── */

    form.addEventListener('submit', async (ev) => {
        ev.preventDefault();
        callar();

        const titular = form.titular.value.trim();
        const doc     = form.documento.value.trim();

        if (titular === '') {
            decir('Escribe el nombre como aparece en la tarjeta.', 'mal');
            form.titular.focus();
            return;
        }

        if (doc === '') {
            decir('Escribe el número de documento del titular.', 'mal');
            form.documento.focus();
            return;
        }

        ocupado(true);

        let token;

        try {
            // Aquí es donde los datos de la tarjeta salen de los iframes
            // hacia Mercado Pago —nunca hacia nosotros— y vuelven
            // convertidos en un token de un solo uso.
            const r = await mp.fields.createCardToken({
                cardholderName: titular,
                identificationType: form.tipo_documento.value,
                identificationNumber: doc,
            });

            token = r.id;
        } catch (e) {
            ocupado(false);
            decir('Revisa los datos de la tarjeta: ' + describirFallo(e), 'mal');
            return;
        }

        const cuerpo = new URLSearchParams({
            csrf_token: cfg.csrf,
            ref: cfg.ref,
            token: token,
            metodo: metodoId,
            emisor: emisorId,
            cuotas: selCuotas.value || '1',
            documento: doc,
            tipo_documento: form.tipo_documento.value,
            dispositivo: window.MP_DEVICE_SESSION_ID || '',
        });

        try {
            const res = await fetch(cfg.endpoint, {
                method: 'POST',
                headers: { 'Content-Type': 'application/x-www-form-urlencoded' },
                body: cuerpo,
                credentials: 'same-origin',
            });

            const d = await res.json();

            if (d.estado === 'verificar' && d.verificacion) {
                decir('Tu banco pide confirmar el pago. Te llevamos a su pantalla.', 'info');
                irAVerificacion(d.verificacion);
                return;
            }

            if (d.estado === 'aprobado') {
                decir('¡Pago aprobado! Un momento…', 'ok');
                window.location.href = cfg.retorno;
                return;
            }

            if (d.estado === 'revisando') {
                ocupado(false);
                decir(d.aviso, 'info');
                boton.disabled = true;
                return;
            }

            ocupado(false);
            decir(d.error || 'El pago no se pudo completar.', 'mal');
        } catch (e) {
            ocupado(false);
            /*
             * Si la red se cae DESPUÉS de mandar el token, el cobro
             * pudo haberse hecho igual. Por eso no se invita a
             * reintentar: se manda a mirar el estado, que es la única
             * respuesta que no arriesga un doble cargo.
             */
            decir('Se perdió la conexión al confirmar. No vuelvas a pagar: '
                + 'revisa el estado de tu pago en un minuto.', 'mal');
        }
    });

    /* ── 3D Secure ───────────────────────────────────────────────── */
    //
    // Se envía por POST a la dirección del banco, que es como espera
    // recibir el `creq`. Es la única pantalla ajena de todo el proceso,
    // y es del banco emisor.
    function irAVerificacion(v) {
        const f = document.createElement('form');
        f.method = 'POST';
        f.action = v.url;

        if (v.creq) {
            const i = document.createElement('input');
            i.type = 'hidden';
            i.name = 'creq';
            i.value = v.creq;
            f.appendChild(i);
        }

        document.body.appendChild(f);
        f.submit();
    }

    function describirFallo(e) {
        const causa = (e && e.cause && e.cause[0]) || (e && e[0]);
        const cod = causa && (causa.code || causa.description);

        switch (String(cod)) {
            case '205': return 'falta el número.';
            case '208':
            case '209': return 'falta la fecha de vencimiento.';
            case '212':
            case '213': return 'falta el documento.';
            case '214': return 'el documento no es válido.';
            case '220':
            case '221': return 'falta el nombre del titular.';
            case '224': return 'falta el código de seguridad.';
            case 'E301': return 'el número no es válido.';
            case 'E302': return 'el código de seguridad no es válido.';
            case '316': return 'el nombre del titular no es válido.';
            case '322':
            case '323':
            case '324': return 'el documento no es válido.';
            case '325':
            case '326': return 'la fecha de vencimiento no es válida.';
            default: return 'hay un campo incompleto o incorrecto.';
        }
    }
})();
