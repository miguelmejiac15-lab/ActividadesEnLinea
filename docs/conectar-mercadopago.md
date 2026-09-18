# Conectar Mercado Pago

Guía para pasar del cobro manual al cobro automático. El código ya está
puesto; lo que falta es la cuenta y cuatro datos.

**El cobro manual sigue funcionando durante todo el proceso.** No hay
ningún momento en el que la plataforma se quede sin poder cobrar.

---

## 1. Crear la aplicación

En <https://www.mercadopago.com.co/developers/panel> → **Tus
integraciones** → **Crear aplicación**.

- Producto: **Checkout Pro** (es el que redirige a la pantalla de Mercado
  Pago; no hay que programar el formulario de la tarjeta).
- Modelo de integración: pagos online.

Al crearla salen dos juegos de credenciales, **de prueba** y **de
producción**. Empieza siempre por las de prueba: son idénticas de usar y
no mueven dinero.

## 2. Pegar las credenciales

Panel → **Cobros → Ajustes de cobro** → *Credenciales de Mercado Pago*.

| Campo | De dónde sale |
|---|---|
| Access token | Credenciales de prueba → *Access token* |
| Public key | Credenciales de prueba → *Public key* |
| Clave secreta del webhook | Se genera en el paso 3 |

Los campos se muestran vacíos a propósito y **sólo se guarda lo que
escribas**: dejar uno en blanco conserva el valor anterior. El access
token nunca se pinta entero en la página — se ve enmascarado
(`APP_•••••••••••••1234`) para poder comprobar cuál está puesto sin
revelarlo.

Con el token puesto, el botón **Probar la conexión** pregunta a la API
quién es el dueño de la cuenta. Es la única comprobación que sirve: un
token bien escrito pero revocado pasa cualquier validación de formato y
falla en el primer cobro real.

> En un servidor de verdad conviene poner el token en las variables de
> entorno `MP_ACCESS_TOKEN` y `MP_WEBHOOK_SECRETO`, que ganan sobre lo
> guardado en la base de datos. Así la credencial no queda en un volcado
> de MySQL. La pantalla marca con «desde el entorno» las que vengan de
> ahí.

## 3. Dar de alta el webhook

En el panel de Mercado Pago, dentro de la aplicación:
**Notificaciones → Webhooks**.

- URL: la que muestra *Ajustes de cobro* (`…/api/webhook-pago.php`)
- Evento: **Pagos**

Al guardarlo, Mercado Pago genera una **clave secreta**. Esa es la que va
en el tercer campo del paso 2. Sin ella, todos sus avisos se rechazan con
un 401 y ningún pago se activa solo.

### Esto no funciona en localhost

Mercado Pago llama al webhook desde sus servidores. Desde allí,
`localhost` es su propio localhost: la llamada nunca llega.

Para probar de punta a punta hay que exponer el sitio:

```
ngrok http 80
```

y poner la dirección que devuelva (`https://algo.ngrok-free.app/proyecto-final`)
en `url_base`, dentro de **`config/credenciales.php`** — que es de donde
`config/config.php` lee la constante `URL_BASE`, no donde está escrita.

**Aun sin webhook se puede probar el cobro completo**: cuando el cliente
vuelve de Mercado Pago, `planes/retorno.php` le pregunta a la API por el
estado del pago y lo aplica si cuadra. En producción eso además hace de
red de seguridad si el webhook falla.

## 4. Encender la pasarela

Panel → **Cobros → Ajustes de cobro** → *Formas de pago*:

- Deja marcada **Transferencia con confirmación manual**.
- Marca **Mercado Pago**.
- Deja marcado **Ambiente de pruebas**.

Las dos formas conviven a propósito. Quien quiere pagar con tarjeta lo
resuelve en un minuto; quien prefiere transferir no se va.

Si eliges Mercado Pago sin haber puesto el access token, la pasarela **no
se da por conectada** y el botón de pagar no se le muestra a nadie: media
configuración es peor que ninguna, porque parece que funciona.

## 5. Probar con tarjetas de prueba

Con el ambiente de pruebas encendido, Mercado Pago acepta sólo sus
tarjetas de prueba, que están en su documentación. El resultado se elige
con el **nombre del titular**:

| Nombre del titular | Resultado |
|---|---|
| `APRO` | Aprobado |
| `OTHE` | Rechazado por error general |
| `CONT` | Pendiente |

Recorrido a probar:

1. Elegir un plan → *Pagar con Mercado Pago*.
2. Pagar con `APRO`. Debe volver a `retorno.php` y quedar **confirmado**,
   con la suscripción activa.
3. Repetir con `OTHE`. Debe decir que no se pudo completar y **no**
   conceder nada.
4. Repetir con `CONT`. Debe quedar **pendiente** — y activarse cuando
   llegue el aviso.

## 6. Pasar a producción

1. Cambiar las credenciales por las de **producción** (el access token
   empieza por `APP_USR-`).
2. Dar de alta el webhook de producción con la URL pública real.
3. **Desmarcar «Ambiente de pruebas».**
4. Hacer un cobro real pequeño y comprobar que llega.

---

## Qué protege el cobro, y cómo

Vale la pena saberlo antes de tocar nada de esto.

**1. El estado de un pago sólo se cree si lo dice la API de Mercado Pago,
consultada por nosotros.** Su aviso no trae el estado, trae un
identificador; el servidor va y pregunta. Por eso, aunque alguien
falsificara un aviso entero, no podría inventarse un «aprobado».

**2. Se comprueba el monto y la moneda, no sólo el estado.** Un pago
aprobado de mil pesos sobre un plan de noventa y nueve mil está aprobado.
`verificarPagoMp()` lo rechaza y lo deja escrito en la historia del pago.

**3. La pantalla de retorno no concede nada por sí misma.** Sus
parámetros vienen en la barra de direcciones y cualquiera puede escribir
`status=approved`. Lo único que hace con ellos es *preguntar*.

**4. La firma se compara en tiempo constante.** Comparar con `==` filtra,
por lo que tarda en fallar, cuánto coincide cada intento.

---

## Cuando un aviso se pierde

Es lo que más va a pasar en producción, así que conviene tenerlo claro
antes: el cliente paga, Mercado Pago avisa, el aviso no llega —se cayó la
red, el sitio estaba reiniciándose, alguien tocó el secreto— y **nadie se
entera**. No hay error ni excepción: solo un cobro pendiente para siempre
y un cliente enfadado tres días después.

Hay tres redes debajo, de la más rápida a la más lenta:

1. **La pantalla de retorno.** Cuando el cliente vuelve de Mercado Pago,
   `planes/retorno.php` consulta la API y aplica lo que responda. Resuelve
   la mayoría de los casos en segundos, sin que nadie haga nada.

2. **El botón del panel.** En **Cobros → ver el pago** hay
   *«🔄 Consultar estado en Mercado Pago»*. Es lo que se usa cuando un
   cliente escribe diciendo que pagó. Busca por la referencia, comprueba
   monto y moneda igual que el webhook, y aplica.

3. **El script de conciliación**, para dejarlo programado:

   ```
   php database/conciliar-pagos.php             # ensayo: dice qué haría
   php database/conciliar-pagos.php --aplicar   # escribe
   php database/conciliar-pagos.php --aplicar --dias=7
   ```

   Recorre los pagos pendientes de pasarela de los últimos 30 días y los
   pone al día. Pensado para correr **cada hora** en el servidor.

   Si avisa de que confirmó pagos, algo va mal con el webhook: revísalo.
   Este script es la red, no el método.

Ninguna de las tres concede nada por su cuenta: las tres pasan por
`verificarPagoMp()`, que comprueba el monto y la moneda.

## Si algo va mal

| Síntoma | Causa habitual |
|---|---|
| El botón de pagar no aparece | Falta el access token, o la pasarela no está marcada |
| «No pudimos abrir la pasarela» | Token inválido o revocado — usa *Probar la conexión* |
| El cliente paga y sigue pendiente | El webhook no llega (¿localhost? ¿secreto mal copiado?). Usa el botón de consultar |
| Los avisos dan 401 | La clave secreta del webhook no coincide con la del panel de Mercado Pago |
| Los avisos dan 401 y el secreto está bien copiado | **La clave es de otra aplicación.** Ver abajo |
| Los avisos dan 503 | No hay clave secreta puesta: sin ella el webhook rechaza todo sin llegar a mirarlo |
| `invalid auto_return` | `URL_BASE` no es pública. El código ya lo evita en local |
| Confirmaste a mano y el dinero no estaba | Nunca confirmes a ojo un cobro de pasarela: pregúntale primero |

### La clave secreta es de la aplicación, no de la cuenta

Es el error que más cuesta encontrar, porque no se ve nada mal: el token
está bien escrito, la clave está bien copiada, y aun así todos los avisos
se rechazan.

Lo normal es tener **dos aplicaciones** —una para probar y otra de
verdad— y cada una tiene su propia clave secreta de webhook. Copiar la
clave de una y el token de la otra produce firmas que no cuadran, y el
401 no puede decirlo porque desde aquí las dos cosas parecen correctas.

La aplicación que manda es **la que emitió el token**, y va dentro del
propio token:

```
APP_USR-<aplicación>-<fecha>-<aleatorio>-<usuario>
             ↑ esta
```

*Ajustes de cobro* avisa en rojo si el número apuntado no es ese.

Todo lo que pasa por el webhook queda en el log de PHP con el prefijo
`[webhook-pago]`, y los rechazos que afectan a un pago identificable
quedan además en su historia, visible en **Cobros → ver el pago**. La
ficha muestra de qué pasarela vino y **el estado con las palabras de
Mercado Pago** (`approved`, `in_process`, `charged_back`), que es lo que
hay que citar si toca abrir un caso con su soporte.

## Probar el cobro sin cobrar

La variable de entorno `MP_API_BASE` cambia la dirección de la API. Sirve
para apuntar a un doble local y ejercitar el cobro entero —incluido que un
pago aprobado por el monto equivocado **no** conceda acceso— sin mover
dinero.

Es **solo** una variable de entorno, nunca un ajuste de la base de datos, y
eso es a propósito: si se pudiera cambiar desde el panel, quien lograra
entrar ahí podría apuntar la verificación de pagos a un servidor suyo y
regalarse el catálogo.

## Archivos

| Archivo | Qué hace |
|---|---|
| `includes/pasarela-mercadopago.php` | Adaptador: preferencias, firma, verificación |
| `includes/pagos.php` | Estados, confirmación, suscripciones (no cambia con la pasarela) |
| `api/webhook-pago.php` | Recibe los avisos y aplica las cuatro puertas |
| `planes/pagar.php` | Ofrece las dos formas de pago |
| `planes/retorno.php` | Vuelta del cliente; consulta y muestra |
| `admin/pagos/ajustes.php` | Credenciales, formas de pago, prueba de conexión |
| `admin/pagos/ver.php` | Ficha del cobro y botón de consultar a Mercado Pago |
| `database/conciliar-pagos.php` | Repesca los pagos cuyo aviso se perdió |
| `database/migracion-mercadopago.php` | Crea los ajustes; idempotente |
