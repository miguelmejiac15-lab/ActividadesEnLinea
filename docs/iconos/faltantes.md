# Palabras sin dibujo propio

Dentro de los minijuegos, cada ejercicio empareja una palabra con un dibujo. El
emoji no cubre el español: no existe *mesa*, ni *xilófono*, ni *ñandú*. Cuando eso
pasa, quien armó el contenido puso el más parecido que encontró — y el niño acaba
viendo una silla debajo de la palabra MESA.

En una actividad de lectoescritura eso es peor que no poner nada: el niño que está
aprendiendo a leer no corrige la asociación, la memoriza.

Detectado con:

```
php database/auditar-iconos-palabra.php --todo
```

---

## Ya resuelto

Dibujos propios en [`assets/iconos/`](../../assets/iconos/), SVG, en el mismo estilo
plano y de color del emoji. El contenido los pide con el prefijo `icono:`, que el
motor entiende (ver `dibujo()` en `assets/js/motor.js`).

| Palabra | Llevaba | Ahora | Usos |
|---|---|---|---|
| MESA | 🪑 silla · 🍽️ cubiertos | `icono:mesa` | 6 |
| ÑAME | 🌿 hierba genérica | `icono:name` | 5 |
| XILÓFONO | 🎵 nota musical | `icono:xilofono` | 2 |
| ÑANDÚ | 🦩 flamenco *(el dibujo de la F)* | `icono:nandu` | 2 |
| FLAUTA | 🎻 violín | `icono:flauta` | 1 |

Y tres donde el emoji correcto **sí existía** y solo estaba mal puesto:

| Palabra | Llevaba | Ahora |
|---|---|---|
| PATO | 🐭 ratón · 🐦 pájaro genérico | 🦆 |
| ERIZO | 🦉 búho | 🦔 |

---

## Pendiente

Ordenado por cuántas veces aparece. Todas están en «Aventura de las Letras», y no
es casualidad: **las letras difíciles del español —Ñ, W, X, Q, Y, K— tienen muy
pocas palabras, y casi ninguna tiene emoji.**

| Palabra | Lleva hoy | Usos | Dónde |
|---|---|---|---|
| XEROX | 📄 hoja de papel | 5 | `letra-x` |
| PIPA | 🎵 nota musical · 🪈 flauta | 4 | `letra-p`, `montana-de-las-silabas` |
| WOMBAT | 🐾 huellas | 2 | `letra-w` |
| WASABI | 🌿 hierba genérica | 2 | `letra-w` |
| YUCA | 🌿 hierba genérica | 2 | `letra-y` |
| QUIJADA | 🦷 diente | 2 | `letra-qu` |
| XILOGRAFÍA | 🖼️ cuadro | 2 | `letra-x` |
| CHINCHILLA | 🐭 ratón | 1 | `letra-ch` |
| PUMA | 🐆 leopardo | 1 | `letra-p` |
| TALLO · HIERBA · INVIERNO · ENREDADO | 🌿 | 1 c/u | varias |

### Dos de estas no necesitan un dibujo, necesitan otra palabra

**XEROX** es una marca comercial, y **XILOGRAFÍA** una técnica de grabado. Ninguna
de las dos la va a reconocer un niño de seis años, así que darles un dibujo mejor
no arregla el problema de fondo: no son palabras con las que se aprenda la X.

Para la X en español casi solo hay *xilófono* y *taxi* —donde la X va en medio, que
también sirve—. Conviene decidirlo como contenido, no como ícono.

---

## Por qué se dibujan y no se descargan

El sistema visual del sitio es emoji. Un ícono de línea de una librería tipo Tabler
o Lucide se vería como de otro producto puesto al lado de un 🦆.

Los conjuntos que sí tienen el estilo correcto —Twemoji, OpenMoji, Noto— cubren
**solo lo que Unicode define**, y el problema es justamente que Unicode no define
«mesa» ni «ñandú». No hay nada que descargar.

Dibujarlos como SVG en el propio proyecto resuelve las tres cosas a la vez:
sin licencias que arrastrar en un producto que trata datos de menores, consistencia
total con el resto, y unos pocos kilobytes que escalan a cualquier tamaño.

## Cómo añadir uno

1. Dibuja `assets/iconos/<nombre>.svg` con `viewBox="0 0 128 128"`, colores planos
   y un `role="img"` con su `aria-label`.
2. Añade la línea a `CAMBIOS` en `database/arreglar-pares-dibujo.php`, indicando
   qué dibujo llevaba y por qué estaba mal.
3. `php database/arreglar-pares-dibujo.php` para ver qué tocaría, y `--aplicar`.
4. `php database/validar-estaciones.php` para comprobar que nada se rompió.
