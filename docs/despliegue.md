# Despliegue · actividadesenlinea.com

Cómo llega el código de esta carpeta a producción, y qué hay montado ya.

---

## La forma, y por qué es esta

```
  git push a main
        │
        ▼
  GitHub Actions ─── revisa sintaxis PHP
        │            busca secretos colados
        │            construye la imagen
        ▼
  GHCR (ghcr.io/miguelmejiac15-lab/actividadesenlinea:latest)
        │
        ▼
  Coolify ─── docker pull + reiniciar contenedor
        │
        ▼
  https://actividadesenlinea.com
```

**La imagen se construye en GitHub, nunca en el servidor.** El servidor es
una `t3.small`: 2 vCPU y 2 GB de RAM, de los que Coolify ya ocupa buena
parte. Un `docker build` ahí compite por esa memoria con el sitio que está
atendiendo visitas, y cuando se agota, el kernel no mata al build: mata al
proceso más grande, que suele ser MariaDB. Publicar una corrección tiraría
el sitio.

En el runner de Actions el build es gratis, tiene 4 GB y no atiende a
nadie. El servidor solo hace `docker pull`: segundos en vez de minutos.

---

## Lo que ya está creado en Coolify

| Recurso | UUID | Notas |
|---|---|---|
| Proyecto | `csktpr8hoaqxnsmn9g5umedw` | `actividades-en-linea` |
| Servidor | `twphu9d0sudwa5ixrmuvxolq` | la propia EC2 |
| MariaDB | `eklwpv2l8ponsmouswrjp9jq` | base `actividades_en_linea`, usuario `ael` |
| Aplicación | `qm09ugo28cppwvi2ycpbuqfr` | dominio `https://actividadesenlinea.com` |

El **nombre de host interno de la base es su propio UUID**
(`eklwpv2l8ponsmouswrjp9jq`), no `localhost` ni una IP: los contenedores se
ven entre sí por nombre dentro de la red de Docker.

Las variables de entorno de la aplicación ya están puestas: `DB_HOST`,
`DB_PORT`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD`, `APP_URL`,
`APP_KEY`, `APP_ENV`.

DNS: `actividadesenlinea.com` y `www` ya resuelven a `44.220.49.180`, así
que Let's Encrypt podrá emitir el certificado en el primer despliegue.

---

## Lo que falta hacer a mano

### 1. Tres secretos en GitHub

*Settings → Secrets and variables → Actions → New repository secret*

| Secreto | Valor |
|---|---|
| `COOLIFY_URL` | `http://44.220.49.180:8000` |
| `COOLIFY_TOKEN` | el token de despliegue de Coolify |
| `COOLIFY_APP_UUID` | `qm09ugo28cppwvi2ycpbuqfr` |

Sin ellos el workflow construye y publica la imagen, pero el último paso
falla diciendo exactamente cuál falta.

> No hacen falta credenciales de GHCR: Actions publica con el
> `GITHUB_TOKEN` de la propia ejecución, que caduca al acabar.

### 2. Hacer pública la imagen en GHCR

Un paquete nuevo en GHCR nace **privado** aunque el repositorio sea
público. Coolify no tiene credenciales para bajarlo y el despliegue falla
con `manifest unknown` o `denied`.

Tras el primer build: *github.com/miguelmejiac15-lab?tab=packages* → el
paquete → *Package settings* → **Change visibility → Public**.

La alternativa es darle a Coolify un token de lectura del registro. Como
el código ya es público, hacer pública la imagen no revela nada nuevo.

### 3. Importar el catálogo

La base arranca vacía. El contenedor crea la estructura solo, pero el
catálogo hay que llevarlo: **489 actividades y 3.123 estaciones que no se
pueden reconstruir desde el repositorio**, porque buena parte se migró del
sitio anterior desde archivos que no están aquí (`legacy/` está vacío).
Volver a sembrar daría un catálogo distinto y más pobre.

En local:

```bash
php database/exportar-para-produccion.php
```

Genera un `.sql` en `almacen/` con el catálogo y **sin** usuarios,
progreso, cobros, suscripciones ni credenciales de pasarela. Se importa
desde la terminal de MariaDB en Coolify:

```bash
mysql -u ael -p actividades_en_linea < ael-produccion-….sql
```

### 4. Crear el administrador

El volcado no trae usuarios, así que no hay con qué entrar. Desde la
terminal del contenedor de la aplicación:

```bash
php database/crear-admin.php --correo=tu@correo.com --nombre="Tu Nombre"
```

Genera una contraseña legible y la muestra **una sola vez**. Si ya hay
administradores, se niega y los lista: así ejecutarlo dos veces por
despiste no deja cuentas de sobra.

### 5. Mercado Pago

Tres variables más en Coolify, con las credenciales **rotadas**:

| Variable | De dónde sale |
|---|---|
| `MP_ACCESS_TOKEN` | Credenciales de producción |
| `MP_PUBLIC_KEY` | Credenciales de producción |
| `MP_WEBHOOK_SECRETO` | La que genera Mercado Pago al dar de alta el webhook |

Van en el entorno y **no** en la base: `mpConfig()` prefiere la variable
sobre el ajuste guardado, así que la credencial no queda en un volcado de
MySQL. Por eso el exportador las omite a propósito.

La URL que se registra en Mercado Pago:

```
https://actividadesenlinea.com/api/webhook-pago.php
```

---

## Cómo se configura la aplicación

En XAMPP, con `config/credenciales.php`. En el contenedor ese archivo **no
existe y no puede existir**: la imagen se publica en un registro y sus
capas quedan en caché en cada máquina que la baje, así que un secreto
escrito dentro no se borra — hay que rotarlo.

`config/entorno.php` arma la misma configuración desde variables de
entorno, que se inyectan al arrancar y nunca entran en la imagen.

**El archivo gana sobre el entorno.** Si existe `credenciales.php` se usa
ese y las variables se ignoran. Es a propósito: al revés, unas variables
olvidadas en el perfil del sistema podrían hacer que las pruebas de una
máquina de desarrollo escribieran en la base de producción.

### La cookie de sesión detrás del proxy

Coolify termina el TLS por delante, así que a PHP le llega una petición
HTTP normal por la red interna y `$_SERVER['HTTPS']` está vacío aunque el
visitante esté en HTTPS. Mirando solo eso, la cookie de sesión salía **sin
la marca `Secure`** justo en producción.

Se decide por `URL_BASE`, que dice con qué esquema se publica el sitio, la
escribimos nosotros y —a diferencia de `X-Forwarded-Proto`— no la puede
tocar quien llama.

---

## Si algo va mal

| Síntoma | Causa habitual |
|---|---|
| `exec /usr/local/bin/entrypoint.sh: no such file or directory` | El script se subió con CRLF. Lo evita `.gitattributes` |
| `COPY docker/…: not found` | Se excluyó `docker/` en `.dockerignore`. No filtra el webroot: filtra el contexto de build |
| `manifest unknown` / `denied` al desplegar | El paquete de GHCR sigue privado |
| El contenedor no arranca y dice qué variable falta | Es el entrypoint haciéndolo a propósito |
| `La base no respondió en 60 segundos` | `DB_HOST` no es el UUID del servicio de MariaDB |
| El sitio carga pero no hay actividades | Falta importar el volcado |
| Sesión que no persiste | `APP_URL` no empieza por `https://` |

Los logs salen por `docker logs` (Apache a stdout, errores de PHP a
stderr), así que se leen desde Coolify sin entrar por SSH.

---

## Archivos

| Archivo | Qué hace |
|---|---|
| `Dockerfile` | La imagen: PHP 8.2 + Apache, sin secretos dentro |
| `docker/apache-ael.conf` | `AllowOverride All`, IP real tras el proxy |
| `docker/php-ael.ini` | Errores ocultos, OPcache, límites |
| `docker/entrypoint.sh` | Comprueba config, espera la base, crea estructura |
| `.github/workflows/desplegar.yml` | Revisa, construye, publica y avisa a Coolify |
| `.dockerignore` | Deja fuera de la imagen secretos y `.git` |
| `.gitattributes` | LF obligatorio en scripts |
| `config/entorno.php` | Configuración desde variables de entorno |
| `estado.php` | Healthcheck: comprueba que la base responde |
| `database/exportar-para-produccion.php` | Volcado sin datos personales |
| `database/crear-admin.php` | La primera cuenta |
