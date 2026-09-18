# Proyecto Final — Actividades en Línea

Reconstrucción estructural y técnica de la plataforma, sobre PHP 8 + MySQL en XAMPP.
Proyecto independiente: no modifica ni depende del proyecto anterior en
`htdocs/ACTIVIDADES EN LINEA/`, que se conserva intacto como referencia.

> **Pedagogía de avanzada, potenciada por tecnología.**

---

## Instalación

1. Enciende **Apache** y **MySQL** en el panel de XAMPP.
2. Abre <http://localhost/proyecto-final/>
3. Completa el formulario del instalador (base de datos + cuenta de administrador).
4. **Borra `instalar.php`** cuando termine.

El instalador crea la base de datos, carga la estructura y el catálogo, genera tu
cuenta de administrador y escribe `config/credenciales.php`.

No existe ninguna contraseña por defecto: la eliges tú durante la instalación.

---

## Las tres etapas

Las etapas **no son tres carpetas duplicadas**, sino tres niveles de acceso dentro
de un mismo código. Así una actividad se edita una vez, no tres.

| Etapa | Plan | Acceso | Precio |
|---|---|---|---|
| 1 · Descubre | `free` | Todas las actividades, primeras estaciones de cada una | $0 |
| 2 · Accede | `biblioteca` | 100% del catálogo + lo que se publique durante la vigencia | $12.000/mes · **$99.000/año** |
| 3 · Gestiona | `escuela` | Todo lo anterior + cursos, estudiantes, asignaciones y progreso | $6.500.000/año |

`docente` ($149.000/año) existe en la base de datos con `is_active = 0`: la
arquitectura está lista, pero no se ofrece todavía.

---

## El modelo 30%

El usuario Free **entra a todas las actividades** y juega las primeras estaciones de
cada una. Al llegar a la primera estación bloqueada ve la invitación a la Biblioteca
Completa. Prueba el producto de verdad antes de pagar.

```
Aventura de la M — usuario Free
  ✅ 1. Conoce la M          ✅ 4. Armar palabras I
  ✅ 2. Seleccionar imágenes ✅ 5. Teclado I
  ✅ 3. Puzle de sílabas I   🔒 6-15. Biblioteca Completa
```

Se configura por actividad:

- `access_type` — `free` (toda libre) · `partial` (primeras N) · `premium` (toda cerrada)
- `free_stations` — cuántas estaciones iniciales son gratuitas
- `activity_stations.is_free` — excepción manual para una estación concreta

### Por qué este candado sí funciona

En la versión anterior el bloqueo vivía en `gate.js` y consultaba `localStorage`.
Cualquiera podía abrir la consola del navegador, escribir una línea y desbloquear
todo el catálogo: el contenido premium **ya venía descargado** dentro del HTML y el
overlay solo lo tapaba.

Aquí la decisión es del servidor. `prepararEstaciones()` en
[`includes/acceso.php`](includes/acceso.php) envía las estaciones bloqueadas **sin su
`config`**: los datos del minijuego no salen de la base de datos. No hay nada que
desbloquear en el navegador porque nunca llegó.

---

## Hace falta cuenta para jugar

El catálogo entero es público: cualquiera puede ver las 360 actividades, entrar a
cada ficha y saber qué contiene. **Lo que pide cuenta es empezar a jugar.**

La razón no es comercial sino de producto: sin cuenta no hay dónde guardar el
progreso, así que el niño repite estaciones que ya hizo y pierde sus estrellas al
cerrar el navegador. Registrarse es gratis y el plan gratuito sigue siendo el mismo.

La decisión se toma en un solo sitio —`motivoBloqueo()` en
[`includes/acceso.php`](includes/acceso.php)— por el que pasan la API que sirve el
contenido, el mapa de estaciones y el guardado de progreso. Cerrar ahí cierra en
todas partes:

| Situación | `jugar.php` | `api/estacion.php` |
|---|---|---|
| Sin cuenta | Redirige a registro | **401** + invitación a crear cuenta |
| Con cuenta, estación libre | Entra | **200** con contenido |
| Con cuenta, estación premium | Entra al mapa | **403** + invitación a suscribirse |

Son dos puertas distintas y no deben confundirse: a quien no tiene cuenta se le
invita a registrarse (gratis), no a pagar. Pedirle dinero a alguien que todavía no
ha probado nada es la forma más rápida de perderlo.

Los botones lo anuncian **antes** del clic — dicen «Crear cuenta y jugar gratis», no
«Iniciar actividad»: un botón que promete una cosa y entrega un formulario se siente
como una trampa.

Se puede desactivar en el panel de ajustes (`exigir_cuenta_para_jugar`) para una
feria o una demostración.

---

## Navegación

El sitio tiene **dos caras según quién mira**, y cada una ofrece pocos destinos.

**Visitante sin cuenta** — la home es un escaparate, no un catálogo. Responde cuatro
preguntas y nada más: qué es, para quién, cómo funciona y cuánto cuesta.

```
Inicio  ·  Actividades  ·  Planes  ·  Entrar  ·  Comenzar gratis
```

**Con sesión iniciada** — la home redirige a *Mi espacio*: no hay dos puertas para lo
mismo. Allí se dice qué permite su plan **en números**, no por su nombre:

> Con el plan **Gratis** entras a las **360** actividades y juegas la primera parte de
> cada una: **508** de 1.757 estaciones.

```
Mi espacio  ·  Actividades  ·  [🔓 Desbloquear todo, solo si no lo tiene]
```

*Mi espacio* muestra, en este orden: el estado del plan, las actividades en curso
(o sugerencias si aún no ha jugado nada), los bloques por los que entrar, las novedades
y sus favoritas.

### El catálogo es un directorio, no una lista

Sin ningún filtro puesto, `/actividades/` **no muestra actividades**: muestra las doce
materias, cada una con sus bloques dentro y su conteo.

```
🌎 Ciencias Sociales · Mi familia, mi país y el mundo que compartimos      28
   🏠 Mi Mundo Cercano 4   🗺️ Mapas y Territorio 5   ⏳ La Línea del Tiempo 5   Ver todo →
```

Con 360 actividades una lista completa deja de ser un catálogo y pasa a ser un muro.
El directorio reproduce la estructura que el visitante ya tiene en la cabeza cuando
busca algo («matemática, lo de operaciones») en vez de obligarlo a recorrerlo todo.

En cuanto elige una materia, un bloque, una edad, una habilidad o escribe en el
buscador, aparecen las tarjetas. `?vista=lista` fuerza la lista completa; dentro de
ella los bloques grandes se recortan a seis tarjetas con un «+31 actividades más».

### El panel de filtros, en dos niveles

Con doce categorías, catorce habilidades, cinco edades y el resto, el panel llegó a
mostrar **más de cuarenta pastillas idénticas antes de la primera actividad**. Todas
del mismo tamaño, del mismo color y con el mismo peso: un muro que hay que leer
entero para saber qué hace cada cosa.

El arreglo no fue adelgazar los bordes, fue dar jerarquía:

| | Se ve siempre | Va plegado |
|---|---|---|
| **Qué es** | navegación | afinado |
| | Buscar · Categoría · Bloque | Edad · Habilidad · Acceso y tipo · Orden |

**14 pastillas visibles en vez de 42.** El plegado usa `<details>` nativo — va con
teclado, lo lee un lector de pantalla y funciona sin JavaScript, igual que el menú
móvil.

Dos reglas lo hacen seguro:

- **Se abre solo** si hay algo puesto dentro, con un contador de cuántos. Nadie
  debería mirar un catálogo recortado sin saber por qué.
- Encima aparece una **barra de filtros puestos** con una ficha por filtro y su ×
  para quitarlo, más «Limpiar todo». Se ve aunque el grupo esté cerrado.

Los **bloques solo se ofrecen dentro de una categoría**. Sueltos son 82 —eran 36
cuando se tomó esta decisión y el argumento solo se ha hecho más fuerte— y además no
dicen nada: «Mi Cuerpo» y «Seres Vivos» uno al lado del otro no revelan a qué materia
pertenece cada uno.

---

## Estructura

```
proyecto-final/
├── index.php               Inicio
├── login.php  registro.php  logout.php
│
├── config/                 [bloqueado por Apache]
│   ├── config.php          Arranque: rutas, errores, sesión, dependencias
│   ├── database.php        Conexión PDO única + ayudantes de consulta
│   ├── credenciales.php    Secretos (lo genera el instalador)
│   └── credenciales.example.php
│
├── includes/               [bloqueado por Apache]
│   ├── funciones.php       Escapado, validación, CSRF, formato
│   ├── auth.php            Registro, sesión, roles
│   ├── acceso.php          ★ Control de acceso al contenido
│   ├── pagos.php           ★ Cobros: crear, confirmar, reembolsar
│   ├── metricas.php        Consultas del panel de negocio
│   ├── graficas.php        Gráficas en HTML, sin librerías
│   ├── catalogo.php        Consultas del catálogo
│   ├── cabecera.php  pie.php          Plantilla del sitio público
│   ├── cabecera-simple.php  pie-simple.php   Plantilla de formularios
│   └── tarjeta-actividad.php           Tarjeta reutilizable
│
├── database/               [bloqueado por Apache]
│   ├── schema.sql          23 tablas
│   ├── seed.sql            Catálogo real extraído del sitio anterior
│   ├── contenido/          Un archivo por materia: las 288 actividades escritas
│   ├── sembrar-contenido.php    Lleva contenido/ a la base de datos
│   ├── validar-estaciones.php   ¿Se puede terminar cada estación?
│   ├── revisar-catalogo.php     ¿Está el catálogo listo para mostrarse?
│   └── migrar-*.php        Traen las 66 actividades del sitio anterior
│
├── MallasPrimaria/         Mallas curriculares 1.º-6.º · fuente del contenido
│
├── assets/css/estilo.css   Sistema visual
├── actividades/
│   ├── index.php           Catálogo: búsqueda, filtros, paginación
│   ├── ver.php             Ficha de actividad
│   └── jugar.php           Reproductor (motor en Fase 3)
├── planes/
│   ├── index.php           Página de precios
│   ├── suscribir.php       Elegir modalidad y forma de pago
│   └── pagar.php           Referencia, datos de la cuenta, aviso de pago
├── usuario/
│   ├── index.php           Mi espacio
│   ├── tienda.php          Personaje, tienda y logros
│   └── pagos.php           Mis pagos
├── admin/
│   ├── index.php           Resumen
│   ├── actividades/  categorias/  bloques/  niveles/
│   ├── usuarios/           Listado + ficha de cada cuenta
│   ├── metricas/           Cifras del negocio y gráficas
│   ├── pagos/              Cobros, ficha, ajustes de recaudo y CSV
│   ├── planes/             Planes y precios
│   └── suscripciones/      Vigencias
├── escuela/                Área institucional (Fase 6)
├── api/                    Endpoints del motor y webhook de pagos
├── legacy/                 Vacía: ya no queda ninguna actividad sin portar
└── almacen/                Logs [bloqueado por Apache]
```

---

## Base de datos

23 tablas. `actividades_en_linea`, utf8mb4.

**Usuarios y comercio** — `users`, `plans`, `subscriptions`, `schools`, `school_users`
**Cobros** — `payments`, `payment_events`
**Catálogo** — `categories`, `collections`, `levels`, `activities`, `activity_stations`, `tags`, `activity_tags`
**Uso** — `activity_progress`, `favorites`
**Gamificación** — `shop_items`, `user_items`, `coin_spends`
**Escuela** — `courses`, `course_students`, `course_activities`
**Sistema** — `settings`

Notas de diseño:

- `collections` son los **bloques**: agrupan actividades afines dentro de una
  categoría. Es agrupación de presentación, no de permisos — el acceso se sigue
  decidiendo actividad por actividad. Una actividad puede no tener bloque: en
  categorías pequeñas un bloque solo añadiría un título por cada dos tarjetas.
- `activity_stations` es la tabla que hace posible el modelo 30%: el contenido de
  cada minijuego vive en su `config`, y ese campo solo viaja si el usuario tiene
  derecho a jugarlo.
- `activities.engine` decide qué motor dibuja la actividad. Las 23 letras comparten
  un solo motor y se diferencian por `content` — eso reemplaza 23 archivos HTML de
  ~77 KB con estructura idéntica.
- El progreso se guarda por estación; el de la actividad se calcula agregando. No se
  duplica información.
- `subscriptions` **nunca** guarda datos de tarjetas, solo la referencia que devuelve
  la pasarela (Wompi, Mercado Pago, PayU, Stripe) para controlar la vigencia.
- Una suscripción vencida deja de dar acceso sola: la vigencia se comprueba en la
  consulta (`expires_at > NOW()`), sin depender de una tarea que la marque.

---

## Gamificación

**Las monedas y las estrellas ya se ganaban desde el primer día.** `activity_progress`
las guardaba con cada estación terminada — y no se mostraban en ninguna parte. Se
recogían y se tiraban. Lo que faltaba no era medir: era enseñar y poder gastar.

### Nada se guarda si se puede calcular

No hay columna `saldo`, ni `racha_actual`, ni `logros_obtenidos`. Todo sale de
`activity_progress`, que ya sabe qué hizo el niño y cuándo:

```
saldo = SUM(activity_progress.coins) − SUM(user_items.paid_coins)
```

Un contador guardado se desincroniza en cuanto algo falla a mitad —una petición que
se corta, un despliegue, una fila borrada— y a partir de ahí miente para siempre sin
que nadie lo note. Uno calculado no puede mentir. La única excepción es el gasto,
que sí queda escrito porque no se deduce de nada.

### Por qué no se pueden farmear monedas

`api/progreso.php` guarda con `GREATEST(coins, VALUES(coins))`: repetir una estación
fácil cien veces deja las monedas de la mejor vez, no cien veces las mismas. **La
economía se sostiene sola**, sin vigilancia ni límites artificiales.

### La racha no se rompe al acabar el día

Se rompe cuando pasa un día entero sin jugar — cuenta si el último día jugado fue hoy
o ayer. Con la regla estricta, un niño que juega cada tarde vería su racha en cero
cada mañana antes de empezar: desalentar justo a quien está cumpliendo.

### Qué se puede canjear

24 artículos en `shop_items`: 12 personajes y 12 accesorios. Los diez animales son
**los mismos del sitio anterior** — un niño que vuelve debe reencontrar su personaje,
no elegir otro.

| | Cómo se consigue |
|---|---|
| 4 personajes | gratis, al registrarse |
| 6 personajes · 10 accesorios | con monedas, de 10 a 70 |
| 2 personajes · 2 accesorios | **solo con racha** — de 3 a 14 días |

Que el dinero de juego no pueda comprarlo todo es deliberado: si todo tuviera precio,
volver cada día dejaría de tener sentido.

Los precios están puestos para que **la primera compra llegue pronto** —una gorra
cuesta 10 monedas, unas tres estaciones— y las últimas cuesten semanas. Si la primera
recompensa tarda, el niño deja de mirar el contador.

### Gastar monedas sin vender el atajo

Dos ayudas, cuando el niño lleva **tres fallos** en la misma estación — no uno:
equivocarse forma parte de aprender, y una salida inmediata enseña a comprarla en
vez de a intentarlo.

| | Precio | Qué hace |
|---|---|---|
| 💡 **Pista** | 5 🪙 | Apaga una opción equivocada. Sigue teniendo que elegir, y nunca deja menos de dos en pie. |
| ⏭️ **Saltar** | 12 🪙 | Pasa a la siguiente estación, pero la registra **sin estrellas y sin monedas**. |

Saltar cuesta más de lo que la estación habría dado, así que **siempre sale a
pérdida**: es una salida de emergencia, no una estrategia, y menos aún una forma de
farmear progreso. Comprar el paso no compra el mérito.

El contador de fallos vive en `avisar()`, que es el **único punto por el que pasan
todos los errores de los veintiún motores**. Un contador por motor serían
veintiún sitios donde olvidarse de uno — y cada motor nuevo, uno más.

El botón de pista solo aparece donde el minijuego sabe darla —los de opción
múltiple— porque cobrar por una ayuda que no existe sería estafar al niño.

### Los 13 logros también se calculan

Definidos en `includes/gamificacion.php` y comprobados contra el progreso. Sin tabla
de «logros obtenidos»: añadir uno nuevo lo concede retroactivamente a quien ya
cumplía, que es lo justo — el niño hizo el trabajo.

Los que faltan se ven apagados pero se ven: saber qué viene después es parte de lo
que empuja a seguir.

### Dónde aparece

- **Cabecera, en todas las páginas** — personaje, monedas y racha. Un contador que
  solo se ve en su propia pantalla no motiva a nadie.
- **Al terminar una estación** — «🪙 +6 monedas», pegado al esfuerzo. Un número que
  sube dos clics después no lo asocia nadie con lo que acaba de hacer.
- **Mi espacio** — la tira con monedas, estrellas, racha, logros y la semana.
- **`usuario/tienda.php`** — el retrato, la tienda y los logros.

```
php database/sembrar-tienda.php --aplicar
```

Todo se compra con POST y CSRF, y **cada compra se vuelve a comprobar en el
servidor** —precio, saldo y racha— aunque el botón ya estuviera desactivado: un botón
deshabilitado no es una comprobación.

---

## Cobros

```
php database/migracion-pagos.php --aplicar
```

Crea `payments` y `payment_events` y siembra los ajustes de recaudo.

### Dos tablas que no son la misma

`payments` **no sustituye** a `subscriptions`. Responden preguntas distintas y
confundirlas es el error clásico:

| | Responde | Cuántas hay |
|---|---|---|
| `subscriptions` | ¿hasta cuándo tiene acceso? | una vigente por usuario |
| `payments` | ¿quién pagó qué, cuándo y cómo? | una por **intento**, incluidos los fallidos |

Un pago rechazado tiene que quedar escrito —es la mitad de un informe de pagos— y no
cabe en `subscriptions`, porque no concede nada. Y una renovación es un pago nuevo
sobre la misma suscripción: si el dinero viviera dentro de la suscripción, renovar
borraría lo cobrado el año anterior.

`payment_events` guarda cada cambio de estado con su motivo y su autor. Con dinero de
por medio la pregunta que llega por teléfono nunca es «¿está activo?», sino **«¿quién
dijo que sí y cuándo?»**.

### El precio lo pone el servidor

Nunca se lee un monto del formulario. `crearPago()` recibe el slug del plan y el ciclo,
y saca el precio de la tabla `plans`. Si el navegador pudiera decir cuánto vale algo,
cualquiera compraría el plan Escuela por mil pesos con el inspector abierto.

### El acceso se concede en un solo sitio

`otorgarSuscripcion()` es la única función que escribe en `subscriptions` al cobrar.
Da igual si el pago lo confirmó un administrador a mano o el webhook de la pasarela:
los dos caminos terminan ahí. Dos rutas que conceden acceso son dos reglas que
mantener sincronizadas, y una de las dos siempre se queda atrás.

Al renovar, el tiempo **se suma** a lo que queda —se parte de `expires_at`, no de
hoy— para que renovar con un mes de antelación no le cueste al cliente ese mes.

### Confirmar dos veces no regala dos años

`confirmarPago()` es idempotente. Importa más de lo que parece: una pasarela que no
recibe respuesta a tiempo reintenta el aviso, y ese reintento llega justo cuando el
administrador está pulsando «Confirmar». Las dos escrituras —marcar el pago y conceder
la suscripción— van en una transacción: un pago confirmado sin suscripción detrás es
un cliente que pagó y no tiene acceso, y nada en el panel lo delataría.

### El reembolso retira el acceso

Devolver el dinero y dejar la suscripción viva es el error que nadie detecta hasta que
cuadra las cuentas. `reembolsarPago()` cancela la suscripción que ese pago concedió.

### La referencia es el centro de todo

Al banco solo le llega un nombre y un monto. Si dos familias transfieren $99.000 el
mismo día, no hay forma de saber cuál es cuál — por eso cada pago nace con una
referencia (`AEL-2026-084512`) que se pide escrita en la descripción.

Es **aleatoria y no correlativa** a propósito: una referencia secuencial le cuenta a
cualquier cliente cuántas ventas lleva la plataforma, y al competidor también.

Cuando el cliente marca «ya transferí», el pago **no se confirma solo**: se queda
esperando y se guarda el número de comprobante. Confiar en el clic del comprador sería
regalar el catálogo a quien pulse el botón sin pagar.

### Sin pasarela también se cobra

El modo por defecto es `manual`: transferencia o Nequi, y confirmación desde el panel.
Funciona desde el primer día, sin contrato con nadie. Conectar una pasarela después no
cambia el resto del sistema —referencias, estados, espera, informes—: solo añade
`api/webhook-pago.php` como segunda vía de confirmación.

Ese webhook es el único punto por el que una máquina puede conceder acceso, así que es
el que más desconfía: solo POST, firma HMAC en `X-AEL-Firma` comparada con
`hash_equals`, y una referencia que tiene que existir. Un estado que no reconoce lo
deja **pendiente** en vez de adivinar: ante la duda, que lo mire una persona.

### Las gráficas del panel no traen librerías

`includes/graficas.php` dibuja con `<div>` y porcentajes. Una librería de gráficas pesa
más que el panel entero, y un CDN caído deja sin cifras justo el día que se necesitan.
Cada gráfica lleva debajo su **tabla de datos** plegada: una cifra que solo se lee
pasando el ratón por encima no existe para quien navega con teclado ni para quien
imprime el informe.

Los colores no son los del sitio. Los azules y verdes de la marca son claros a
propósito —es un producto para niños— pero sobre fondo blanco no llegan a 3:1 de
contraste. Las gráficas usan los tonos oscuros de las mismas familias, comprobados
para deuteranopía y tritanopía.

Ingresos y registros van en **dos gráficas separadas**, nunca en una con dos ejes:
superponer pesos y personas obliga a inventar una escala común, y esa escala arbitraria
dibuja una correlación que no está en los datos.

Nada se guarda: no hay tabla de «ingresos del mes» ni columna `total_usuarios`. Todo se
calcula sobre `payments`, `subscriptions` y `users` — igual que el saldo de monedas.

---

## Seguridad

| Medida | Dónde |
|---|---|
| Contraseñas con `password_hash()` / `password_verify()` | `includes/auth.php` |
| Rehash automático si cambia el algoritmo | `iniciarSesion()` |
| Consultas preparadas reales (`EMULATE_PREPARES = false`) | `config/database.php` |
| Escapado de toda salida con `e()` | `includes/funciones.php` |
| Token CSRF en cada formulario | `campoCsrf()` / `exigirCsrf()` |
| `session_regenerate_id()` al entrar | contra fijación de sesión |
| Cookie `HttpOnly` + `SameSite=Lax` | `config/config.php` |
| Mensaje de error idéntico para correo y contraseña | evita descubrir qué correos existen |
| Rol y estado releídos de la BD en cada petición | desactivar una cuenta surte efecto ya |
| `config/`, `includes/`, `database/`, `almacen/` sin acceso web | `.htaccess` |
| `MallasPrimaria/` sin acceso web | documentos internos de un colegio real |
| Errores ocultos en producción, registrados en `almacen/logs/` | `config/config.php` |
| El precio de un plan nunca viaja por el formulario | `crearPago()` |
| Un pago solo lo ve su dueño (filtro por sesión, no por URL) | `planes/pagar.php` |
| Webhook con firma HMAC y `hash_equals` | `api/webhook-pago.php` |
| Estado desconocido de la pasarela ⇒ pendiente, no confirmado | `estadoDesdePasarela()` |
| Ningún dato de tarjeta en la base, solo la referencia | `payments` |

**Privacidad (Decreto 0769 de 2026)** — la plataforma trata datos de menores:
se guarda solo el año de nacimiento (no la fecha completa), se pide el correo de un
adulto responsable para menores de 14 años, y las cabeceras desactivan de entrada
geolocalización, cámara y cohortes de publicidad.

---

## Fases

| | Fase | Estado |
|---|---|---|
| 1 | Arquitectura: carpetas, configuración, MySQL, usuarios, control de acceso | ✅ Completada |
| 2 | Frontend público: inicio, catálogo, ficha de actividad, precios | ✅ Completada |
| 3 | Sistema de actividades: CRUD, categorías, niveles, Free/Premium, publicación | ✅ Completada |
| 3b | Motor de juego por estaciones · 23 letras migradas | ✅ Completada |
| 3c | Contenido de primaria 1.º-6.º sobre las mallas curriculares · 200 actividades | ✅ Completada |
| 4 | Cobros: contratación, confirmación, vigencia, métricas | ✅ Completada · pasarela por conectar |
| 5 | Perfil de usuario: Mi cuenta, favoritos, progreso | |
| 6 | Sistema Escuela | |
| 7 | Reportes y estadísticas | |
| 8 | Optimización visual y responsive | |

---

## Catálogo

**360 actividades publicadas · 1.757 estaciones · 11.220 ejercicios · 12 materias · 82 bloques.**

Ninguna materia baja de 18 actividades: una categoría con una sola actividad no es
una categoría, es una promesa incumplida en el menú.

| Categoría | Actividades | Bloques |
|---|---|---|
| ✏️ Aventura de las Letras | 60 | 8 |
| 🔢 Matemática | 44 | 9 |
| 🌐 Idiomas | 34 | 7 |
| 🔬 Ciencias | 33 | 8 |
| 🌎 Ciencias Sociales | 28 | 7 |
| 💚 Vida y Bienestar | 26 | 7 |
| 🎨 Artística | 25 | 7 |
| 🎲 Juegos y Retos | 24 | 5 |
| 💻 Tecnología | 21 | 7 |
| ♿ Aprender sin Barreras | 21 | 6 |
| 🤝 Valores y Convivencia | 20 | 6 |
| 🧠 Pensamiento y Lógica | 18 | 5 |

Las 288 actividades escritas viven en `database/contenido/` (un archivo por materia)
y se siembran con `database/sembrar-contenido.php`. No leen nada del sitio anterior:
se escribieron a mano.

El sembrador **se niega a escribir sobre una actividad de otra categoría**, aunque
coincida el slug. La guarda existe porque el fallo ya ocurrió: una actividad nueva
de Juegos y Retos llamada `carrera-de-palabras` se llevó por delante la de Aventura
de las Letras con el mismo slug, y el informe dijo que todo había salido bien.

```
php database/sembrar-contenido.php             # simulación
php database/sembrar-contenido.php --aplicar   # escribe
php database/validar-estaciones.php --detalle  # comprueba las 1.757
php database/revisar-catalogo.php              # salud del catálogo
```

### De dónde sale el contenido de primaria

Las 200 actividades que llevaron el catálogo de 154 a 354 no se inventaron: se
escribieron sobre las **mallas curriculares de 1.º a 6.º del Colegio Alemán de
Barranquilla**, que están en [`MallasPrimaria/`](MallasPrimaria/) — un documento por
área y por grado, con sus unidades, conceptos clave y objetivos de aprendizaje.

Cada archivo `*-primaria.php` de `database/contenido/` cubre un área, y su cabecera
dice de qué malla salió y qué decisiones se tomaron al traducirla a algo jugable.

Dos criterios sobre qué se tomó y qué no:

- **Deutsch queda fuera.** Es la asignatura de alemán y no entra en el catálogo.
- **Sachunterricht sí entra**, y no es una contradicción: está redactada en alemán
  pero no enseña alemán. Es la materia de conocimiento del medio —clima, agua,
  energía, residuos, vertebrados, insectos, plantas, cuerpo humano— y su contenido
  es de ciencias. Entra traducido; lo que se excluye es el idioma, no el tema.

Lo que **no** se copió es la forma. Una malla se organiza por periodos y competencias
porque la escribe un docente para planear su año; un catálogo se organiza por lo que
una familia busca. «Pensamiento aleatorio y sistemas de datos» es correcto y no lo
busca nadie: aquí ese bloque se llama **Datos y Azar**.

Tampoco se copió lo que no cabe en una pantalla. La malla de Deporte se hace con el
cuerpo y la de Arte con las manos; ninguna actividad de este catálogo le pide a un
niño que corra o pinte frente al computador. Lo que sí cabe —y la malla también
pide— es todo lo que rodea a eso: por qué se calienta antes de un esfuerzo, qué
reglas tiene cada deporte, qué descubrió cada época sobre cómo representar el
volumen. Mirar una obra y saber qué se está mirando es la mitad de la clase de arte,
y es la mitad que una pantalla sí puede dar.

**El nivel «Quinto y sexto» (11-12 años) nació aquí.** Los cuatro niveles originales
se detenían en los 10 porque el catálogo heredado no pasaba de ahí; las mallas llegan
a Klasse 6. Sin ese nivel, todo el contenido de 5.º y 6.º habría caído en «Tercero a
quinto» y el filtro por edad le habría mentido a la familia que busca algo para un
hijo de once años. De paso, ese nivel pasó a llamarse **«Tercero y cuarto»**: con el
nuevo al lado, la palabra «quinto» aparecía en dos opciones seguidas del mismo
desplegable. Solo cambió el nombre — el rango 8-10 sigue igual, porque es lo que
filtra de verdad.

`validar-estaciones.php` no revisa la sintaxis: revisa que un niño pueda terminar
cada estación. Que haya al menos una respuesta correcta; que esté entre las
opciones; que no haya dos opciones idénticas siendo una la correcta —el niño toca
la que se ve igual y el juego le dice que se equivocó—; y que las palabras de una
sopa de letras estén de verdad en la cuadrícula, en las casillas que dice.

`revisar-catalogo.php` responde la otra pregunta, la que no cubría nadie: **¿está
el catálogo en condiciones de mostrarse?** Existe porque el fallo ya ocurrió — dos
actividades quedaron publicadas, con ficha e ícono, y sin una sola estación: la
migración las dejó a medias y nada lo dijo. Sus estaciones validaban perfectamente,
porque no tenían ninguna. Ahora se comprueban catorce cosas: publicadas sin
estaciones, motor antiguo todavía en uso, íconos repetidos dentro de un bloque,
`free_stations` prometiendo más de las que hay, categorías por debajo de diez
actividades, bloques vacíos y actividades sin etiquetar. No escribe nada y devuelve
1 si encuentra algo, para encadenarlo detrás de una siembra.

### Etiquetas transversales

La **categoría** dice de qué trata la actividad y es una sola. La **etiqueta** dice
qué pone en juego, y son varias: *Caso cerrado* está en Pensamiento y Lógica, pero
entrena deducción y lectura, y es un reto.

Sin esto, encontrar algo en un catálogo de cientos de actividades exigiría saber de
antemano en qué materia se guardó. Con esto, una misma actividad aparece buscando
«lógica», «reto» o su materia, **sin duplicarla**.

Se guardan en `tags` + `activity_tags`, en tres ejes (`kind`): `habilidad`, `tema`
y `tipo`. El catálogo ofrece como filtro las habilidades y los tipos; los temas se
guardan pero no se filtran, porque son casi tantos como categorías y confundirían
las dos cosas.

Es también el camino para que **Juegos y Retos** deje de competir con las materias
y pase a ser un formato transversal: un reto de historia sigue siendo de Ciencias
Sociales y se etiqueta `reto`.

Las 66 actividades anteriores quedaron etiquetadas por categoría — una aproximación
gruesa y consciente: es cierta, pero no distingue matices dentro de una materia.

### Los íconos

El ícono es lo primero —y a veces lo único— que un niño de cuatro años lee en una
tarjeta. Dos reglas, y un script que las hace cumplir:

1. **Sale de algo que el niño va a ver dentro de la actividad**, no es un adorno.
   La A lleva ✈️ porque «Avión» es uno de los ejercicios de su primera estación.
2. **Dos actividades del mismo bloque nunca llevan el mismo.** Entre categorías
   distintas da igual —nadie ve juntas una de Letras y una de Ciencias— pero dentro
   de un bloque las tarjetas van una al lado de la otra.

```
php database/arreglar-iconos.php --aplicar      # corrige
php database/arreglar-iconos.php --inventario   # escribe docs/iconos/
```

El inventario en [`docs/iconos/`](docs/iconos/) deja por escrito qué lleva cada
actividad y cada estación, sin tener que abrir la base de datos.

> **Cuidado con la intercalación.** Con `utf8mb4_unicode_ci`, MariaDB considera
> **iguales** a casi todos los emojis: no tienen peso de ordenación. Un
> `GROUP BY icon` informa alegremente de que las 360 actividades comparten el mismo
> ícono. Hay que usar `GROUP BY BINARY icon`.

El sistema visual usa emoji y no archivos de imagen: pesan cero, se ven nítidos a
cualquier tamaño, no hay que licenciarlos y un administrador cambia uno escribiendo
un carácter. Con 360 actividades y 1.757 estaciones serían más de mil archivos que
mantener. Si a una actividad le faltara el ícono, la tarjeta cae al de su materia
antes que a un genérico.

### El dibujo manda

En un producto para niños de 3 a 12 años **el dibujo no acompaña al texto: es el
texto**. Un niño de cuatro años no lee el título de la tarjeta — mira la imagen y
decide. Los tamaños se subieron en todo el sitio con ese criterio:

| | Antes | Ahora |
|---|---|---|
| Cara de la tarjeta de actividad | 3rem · 118px | **4.6rem · 150px** |
| Ficha de actividad | 5.5rem | **7.5rem** |
| Dentro del minijuego | 4.2rem | **5.6rem** |
| Personaje (retrato) | 5rem | **8rem** |
| Personaje (cabecera) | 1.5rem | **2.1rem** |
| Artículo de la tienda | 2.8rem | **4.4rem** |
| Materia en el directorio | 1.9rem | **2.7rem** |

En la tarjeta el dibujo ocupa ahora casi la mitad de la altura, que es la proporción
que tenía el sitio anterior y la razón por la que se veía tan vivo.

### Cuando el emoji no alcanza

Dentro de los ejercicios, cada palabra va con un dibujo — y ahí el emoji **no cubre
el español**: no existe *mesa*, ni *xilófono*, ni *ñandú*. Quien armó el contenido
puso el más parecido, y MESA acabó ilustrada con una silla 🪑. En lectoescritura eso
es peor que no poner nada: el niño que está aprendiendo a leer no corrige esa
asociación, la memoriza.

```
php database/auditar-iconos-palabra.php --todo    # encuentra los sospechosos
php database/arreglar-pares-dibujo.php --aplicar  # corrige los confirmados
```

La auditoría no sabe español, así que no decide: señala los tres indicios que casi
siempre delatan un dibujo prestado —un mismo dibujo para palabras distintas, una
misma palabra con dibujos distintos, y los comodines conocidos— y un humano
resuelve. De 1.353 pares encontró un puñado real.

Las palabras sin emoji posible llevan ahora un **dibujo propio** en
[`assets/iconos/`](assets/iconos/): SVG plano, en el estilo del emoji. El contenido
lo pide con `icono:mesa` y el motor lo entiende. Se dibujan en vez de descargarse
porque los conjuntos con el estilo correcto —Twemoji, OpenMoji, Noto— cubren solo lo
que Unicode define, y el problema es justo que Unicode no define «mesa».

La lista de lo hecho y lo pendiente está en
[`docs/iconos/faltantes.md`](docs/iconos/faltantes.md).

### Color en los botones de sí y no

Los botones «sí» y «no» van en **verde y rojo desde el principio**, no solo al
acertar. A los cuatro años el color se lee antes que la palabra: dos botones
idénticos obligan a leer para poder elegir, que es justo lo que el niño está
aprendiendo. El ✅ y el ❌ acompañan al color para quien no distingue verde de rojo.

### Cuando el dibujo ES la pregunta

En «¿cuál es más grande, 🐭 o 🐘?» las opciones se pintan **grandes**. A tamaño de
texto los dos emojis miden lo mismo y el ejercicio se vuelve imposible. El motor lo
detecta solo: si ninguna opción tiene letras ni números, las agranda.

### Idioma de lectura

Las estaciones de **Idiomas** declaran `en-US` en su `config`, y el motor lee con voz
inglesa. Sin eso, «cat» sonaría «kat» y enseñaría justo lo contrario. Las
instrucciones siguen en español: un niño que aún no lee bien en su lengua no puede
además descifrar la consigna.

## Estado de la migración

**66 de 66 actividades portadas** al motor por estaciones. No queda ninguna
sirviéndose desde su HTML original.

| Familia | Actividades | Estaciones | Migrador |
|---|---|---|---|
| Letras | 23 | 345 | `migrar-letras.php` |
| Vocales | 5 | 55 | `migrar-vocales.php` |
| Mundos | 28 | 74 | `migrar-mundos.php` |
| Preescolar y otras | 8 | 32 | `migrar-extras.php` |
| Paquetes por vocal | 5 | 38 | `migrar-paquetes-vocales.php` |
| Packs de juegos de vocales | 2 | 10 | `contenido/letras-juegos.php` |

Las otras dos entradas del sitio anterior —`Bosque_de_vocales.html` y
`Reino_de_las_letras.html`— no eran actividades sino índices: hoy son los **bloques**
del mismo nombre, y por eso quedan archivadas.

### Dos formatos para las actividades avanzadas

Los diecinueve minijuegos originales se resolvían todos igual: **eligiendo
entre opciones que están a la vista**. Eso funciona a los seis años, pero a
los once un niño ya puede producir la respuesta, no solo reconocerla. Se
añadieron dos motores que exigen justo eso.

**`completar_texto`** — un texto al que le faltan palabras, con un banco de
fichas debajo. Es distinto de `completar_palabra`, que esconde *una letra*
dentro de una palabra suelta: aquí lo que falta es una palabra entera y solo
se acierta leyendo la frase alrededor. Los señuelos encajan gramaticalmente
a propósito — si cantaran mal al oído, el ejercicio se resolvería sin leer.

Los huecos se llenan **en orden**, con el siguiente resaltado. Poder tocarlos
en cualquier orden suena más libre, pero obliga al niño a llevar la cuenta de
dónde iba en vez de a leer.

**`crucigrama`** — rejilla con pistas, y la pista puede ser un texto («Animal
que maúlla») o **un dibujo**. Con dibujo, el mismo motor sirve para un niño
que todavía no lee: por eso cada una de las 33 actividades de letras y
vocales termina ahora en un crucigrama de imágenes, que es su estación más
exigente — hay que reconocer el dibujo, recordar la palabra y escribirla
entera de memoria.

Se resuelve **por pistas, no letra a letra**. Con un dedo sobre una tableta,
ir casilla por casilla es un ejercicio de puntería, y lo que se practica aquí
es vocabulario. Al tercer intento aparece la primera letra, igual que en el
teclado: un niño atascado tiene que poder salir.

#### El encaje lo calcula el servidor

`crucigrama()` en [`contenido/ayudas.php`](database/contenido/ayudas.php)
coloca las palabras cruzándose y devuelve la rejilla resuelta; el motor solo
dibuja y comprueba. Tres reglas hacen que un crucigrama sea resoluble:

1. Donde dos palabras se cruzan, la letra tiene que ser la misma.
2. Antes de la primera letra y después de la última no puede haber otra, o
   al leer la fila aparecerían dos palabras pegadas.
3. Las casillas de al lado deben estar libres salvo en el cruce, o salen
   palabras paralelas formando columnas sin sentido.

Si una palabra no encaja **se omite**: un crucigrama con una palabra menos se
resuelve; uno con una palabra mal cruzada, no. `validar-estaciones.php`
reconstruye la rejilla y rechaza los cruces imposibles y las palabras que no
tocan a ninguna otra.

> El generador falló entero en su primera versión y no formó ni un solo
> crucigrama: la comprobación de encaje capturaba la rejilla **por valor**,
> así que veía siempre el tablero vacío y ninguna palabra encontraba con qué
> cruzarse. Una sola `&` de diferencia.

### Tres palabras por estación no enseñan una letra

El sitio anterior traía **tres palabras por estación**, y la migración las
copió tal cual. La M entera se enseñaba con MANO, MESA y MONO repetidas
quince veces: eso no enseña la M, enseña esas tres palabras. El promedio
de la categoría era de **4,1 palabras por estación**.

```
php database/enriquecer-letras.php             # simulación
php database/enriquecer-letras.php --aplicar   # escribe
```

El vocabulario ampliado vive en
[`contenido/vocabulario-letras.php`](database/contenido/vocabulario-letras.php)
y `enriquecer-letras.php` regenera con él las estaciones de las 23 letras,
las 5 vocales y los 5 paquetes por vocal. **De 4,1 a 9,8 palabras por
estación**; el conjunto de la categoría pasó de 4,7 a 9,0.

Los cupos no son iguales para todas: una estación de sí/no se responde de
un toque y admite dieciséis sin cansar, pero escribir dieciséis palabras
con el teclado sería un castigo.

**Tres estaciones se dejan intactas a propósito** — `ordenar_secuencia`,
`cuento` y `desafio_final`. Tienen una historia concreta y unas preguntas
escritas una a una; añadirles elementos no las enriquece, las rompe.
Enriquecer es dar más vocabulario, no inflar todo lo que se pueda contar.

#### Las listas cortas son la respuesta correcta

La M tiene 22 palabras y la W tiene 5. No es un descuido: **ninguna palabra
entra si no tiene un dibujo que la represente de verdad**. FRAMBUESA se
quedó fuera porque 🫐 son arándanos; INSTRUMENTO, porque ilustrarlo con una
trompeta enseña «trompeta». Con la K, la W, la X, la I y la U lo que falta
no es contenido: es idioma, y estirarlas obligaría a inventar parejas
falsas. Es mejor tener seis palabras bien ilustradas que doce mal.

#### Dónde va la letra

La Ñ, la X, la Y, la Z, el CH, el QU y el GUE/GUI casi no aparecen al
principio de palabra —niño, taxi, playa, lápiz, leche, queso, águila— así
que preguntarle a un niño «¿empieza con Ñ?» sería enseñarle algo falso.
Cada letra declara su `posicion` y el generador cambia la consigna: unas
preguntan «¿empieza con...?» y otras «¿lleva la...?». Por eso `sonido_letra`
admite ahora encabezado propio en el motor.

#### El error que casi se cuela

El filtro que elige los distractores buscaba la etiqueta de la letra dentro
de la palabra. Con «GUE-GUI» eso no existe en ningún sitio: GUITARRA podía
colarse como respuesta **«no lleva GUE/GUI»**, y el niño que acertara habría
recibido un error. Se salvó por el azar de la semilla.

El arreglo son dos cosas: el campo `busca` —lo que de verdad hay que buscar,
`['GUE','GUI']`— y una **autocomprobación dentro del generador**, que
verifica cada `ok` contra la palabra y se niega a callarse. Es el error más
injusto que puede cometer una actividad y el más difícil de ver, porque
nada falla.

### Los dos packs que la migración dejó a medias

`Juegos de Vocales 1` y `2` fueron el único caso en que la migración publicó algo
que no se podía jugar: quedaron en el catálogo con ficha, ícono y descripción, y con
**cero estaciones**. `validar-estaciones.php` las daba por buenas porque no tenían
ninguna estación que validar. De ahí salió `revisar-catalogo.php`.

No se habían portado porque sus cuatro minijuegos —naves, nubes, globos y pesca—
eran de acción en tiempo real, y ninguno de los diecinueve motores hace eso. Pero la
mecánica pedagógica de los cuatro es la misma —«aparece algo, ¿lleva la vocal que
busco?, decide rápido»— y esa sí está cubierta por `juego_rapido`. Se conserva la
temática de cada uno, que es lo que el niño recuerda; se pierde la animación y se
gana que el progreso se guarde y que funcione en un teléfono sin depender de la
puntería sobre un objeto en movimiento.

Los **paquetes por vocal** se habían quedado fuera de la migración y nadie lo notó
hasta que se echaron de menos en el catálogo. Cada uno reúne seis u ocho minijuegos
sobre la misma vocal —empieza-con, buscar imágenes, contar, completar, sílabas,
parejas, memoria y sopa— y se recorren de un tirón: es el formato «paquete» del
sitio anterior. Ninguno necesitó motor nuevo.

Para que no vuelva a pasar sin avisar:

```
php database/auditar-migracion.php
```

Compara los HTML del sitio anterior contra los slugs que existen hoy. Hace falta
porque los migradores ponen `legacy_file = NULL` al terminar, y entonces la base ya
no recuerda de qué archivo vino cada actividad.

Los tres migradores comparten `lector-js.php`, que lee los objetos JavaScript del
proyecto anterior (no son JSON: claves sin comillas, comillas simples, comentarios).
Todos corren primero en simulación y son idempotentes:

```
php database/migrar-letras.php             # simulación
php database/migrar-letras.php --aplicar   # escribe
```

Los archivos HTML originales solo se leen; nunca se modifican.

### El laberinto del robot

`Ciber-Código Robot` preguntaba: *«Botto está en [0,0] y debe llegar a [0,3], ¿qué
secuencia necesita?»*, con cuatro opciones de texto. El niño tenía que dibujarse la
cuadrícula en la cabeza, y al fallar no sabía por qué — no había nada que mirar. Eso
no es programar: es aritmética de coordenadas disfrazada.

El motor `laberinto` muestra el tablero, el robot y la meta. El niño arma la
secuencia con flechas, toca ▶ y **el robot recorre el camino paso a paso delante de
él**. Si choca contra un muro, ve exactamente en qué casilla. Ese ciclo —escribir,
ejecutar, mirar, corregir— es lo que de verdad se está enseñando.

**La pantalla y los cuatro niveles son los del sitio anterior**, no una versión
nueva: `sitio1-free/Ciber_Codigo_Robot.html` ya tenía este juego bien resuelto —
tablero turquesa a la izquierda, panel de comandos a la derecha, ⭐ como meta— y lo
que la migración había hecho era convertirlo en cuatro preguntas de texto. Los
colores están copiados de ese archivo, no aproximados: inventar una paleta
«parecida» habría añadido una tercera que mantener.

Al fallar nunca se pierde el nivel, y al tercer intento aparece cuántos pasos hacen
falta como mínimo: un niño atascado tiene que poder salir.

`validar-estaciones.php` recorre cada laberinto con una búsqueda en anchura y
**comprueba que exista camino hasta la meta**. Un laberinto sin salida dejaría al
niño intentándolo indefinidamente sin que nada le dijera que el imposible no es
culpa suya.

### Cómo se redujeron veinte motores a uno

Los 28 mundos parecían muy distintos entre sí, pero al mirar sus datos casi todos
comparten una misma mecánica: `{ans, opts, ...presentación}` — mostrar algo y elegir la
opción correcta. Lo que cambia es la presentación (emoji, color, escena, lista de
compra), no el juego. Por eso `migrar-mundos.php` reconoce las formas **por su
estructura**, no por el nombre del archivo, y las normaliza a un contrato único.

Aparecieron además otras mecánicas reconocibles: `{a,b}` suma, `{total,remove}` resta,
`[a,b]` multiplicación, `{count}` contar, `{seq,missing}` número que falta,
`{syls,word}` sílabas, `{text,order}` ordenar, `{img,word}` emparejar.

### Las 4 que no se migran, y por qué

| Actividad | Motivo |
|---|---|
| Bosque de las Vocales | Es una **página de navegación** que lista las 5 vocales, no una actividad. El catálogo con filtro por categoría ya hace ese trabajo. |
| Reino de las Letras | Igual: lista las letras. Duplica el catálogo. |
| Juegos de Vocales 1 y 2 | Juegos de acción (naves y nubes, globos y pesca) que generan todo al vuelo. No tienen ejercicios guardados que extraer. |

Las dos primeras son candidatas a archivarse: son puertas de entrada duplicadas.
