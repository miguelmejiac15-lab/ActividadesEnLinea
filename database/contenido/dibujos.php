<?php
/**
 * dibujos.php — De un concepto a un dibujo
 *
 * Un niño de preescolar no lee. Si un ejercicio solo tiene texto, para él
 * es una pantalla en blanco: puede oírlo, pero no tiene dónde mirar
 * mientras piensa. En una actividad de identificar imágenes, sin dibujo
 * no hay actividad.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  POR QUÉ ESTO ES UN DICCIONARIO Y NO 692 EDICIONES A MANO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Faltaban dibujos en 692 ejercicios. Ponerlos uno a uno en los archivos
 * de contenido habría funcionado esta vez y habría vuelto a fallar con la
 * siguiente tanda de actividades, porque nada obliga a acordarse.
 *
 * Aquí el dibujo se deduce del propio enunciado al sembrar. El que
 * escribe contenido sigue pudiendo poner el suyo —y gana siempre—, pero
 * si no pone ninguno, el enunciado ya trae la pista: «¿De qué color es el
 * banano?» lleva la palabra «banano» dentro.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  LA REGLA QUE EVITA EL DESASTRE: NUNCA DELATAR LA RESPUESTA
 * ─────────────────────────────────────────────────────────────────────
 *
 * El dibujo sale SIEMPRE del enunciado, jamás de la opción correcta.
 * Ilustrar «¿Cuál es un animal?» con 🐶 no es ayudar: es dar la
 * respuesta, y convierte el ejercicio en tocar el botón que coincide con
 * el dibujo de arriba.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  Y LA QUE EVITA EL RIDÍCULO: MEJOR NINGUNO QUE UNO MALO
 * ─────────────────────────────────────────────────────────────────────
 *
 * Un dibujo equivocado es peor que ninguno. Un niño que está aprendiendo
 * no corrige lo que ve: lo memoriza. Si «MESA» sale ilustrada con una
 * silla, aprende que eso es una mesa.
 *
 * Por eso se busca **por palabra completa**, con los acentos
 * normalizados. El límite de palabra es lo que impide que «oso» case
 * dentro de «peligroso» o «mano» dentro de «manosear» — un niño que ve un
 * oso en una pregunta sobre peligros se queda con el oso.
 *
 * Si no hay una coincidencia clara, no se pone nada.
 */

declare(strict_types=1);

/**
 * Concepto → dibujo.
 *
 * Las claves van SIN TILDES y en minúscula: así se escriben una vez y
 * valen para «pájaro», «pajaro» y «Pájaros». La búsqueda normaliza el
 * texto de la misma forma antes de comparar.
 *
 * El orden no importa, pero sí la longitud: `dibujoDe()` prueba primero
 * las claves largas, para que «lavarse las manos» gane sobre «manos».
 */
const DIBUJOS = [

    // ── Animales ─────────────────────────────────────────────────────
    'perro' => '🐶', 'perros' => '🐶', 'cachorro' => '🐶',
    'gato' => '🐱', 'gatos' => '🐱', 'gatito' => '🐱',
    'pez' => '🐠', 'peces' => '🐠', 'pescado' => '🐟',
    'pajaro' => '🐦', 'pajaros' => '🐦', 'ave' => '🐦', 'aves' => '🐦',
    'vaca' => '🐄', 'vacas' => '🐄',
    'caballo' => '🐴', 'caballos' => '🐴',
    'cerdo' => '🐷', 'marrano' => '🐷',
    'oveja' => '🐑', 'ovejas' => '🐑',
    'gallina' => '🐔', 'gallinas' => '🐔', 'pollito' => '🐤', 'pollo' => '🐔',
    'pato' => '🦆', 'patos' => '🦆',
    'raton' => '🐭', 'ratones' => '🐭',
    'conejo' => '🐰', 'conejos' => '🐰',
    'elefante' => '🐘', 'elefantes' => '🐘',
    'leon' => '🦁', 'leones' => '🦁',
    'tigre' => '🐯', 'mono' => '🐵', 'monos' => '🐵',
    'jirafa' => '🦒', 'oso' => '🐻', 'osos' => '🐻',
    'mariposa' => '🦋', 'mariposas' => '🦋',
    'abeja' => '🐝', 'abejas' => '🐝',
    'hormiga' => '🐜', 'hormigas' => '🐜',
    'araña' => '🕷️', 'tortuga' => '🐢', 'tortugas' => '🐢',
    'serpiente' => '🐍', 'culebra' => '🐍',
    'rana' => '🐸', 'caracol' => '🐌', 'delfin' => '🐬', 'ballena' => '🐳',
    'pinguino' => '🐧', 'aguila' => '🦅', 'buho' => '🦉', 'loro' => '🦜',
    'murcielago' => '🦇', 'camello' => '🐪', 'cangrejo' => '🦀', 'pulpo' => '🐙',
    'animal' => '🐾', 'animales' => '🐾', 'mascota' => '🐾',

    // ── Comida ───────────────────────────────────────────────────────
    'manzana' => '🍎', 'manzanas' => '🍎',
    'banano' => '🍌', 'bananos' => '🍌', 'platano' => '🍌',
    'naranja' => '🍊', 'naranjas' => '🍊',
    'fresa' => '🍓', 'fresas' => '🍓',
    'uva' => '🍇', 'uvas' => '🍇',
    'sandia' => '🍉', 'limon' => '🍋', 'pera' => '🍐', 'piña' => '🍍',
    'mango' => '🥭', 'aguacate' => '🥑', 'coco' => '🥥', 'kiwi' => '🥝',
    'tomate' => '🍅', 'zanahoria' => '🥕', 'zanahorias' => '🥕',
    /*
     * Sin «papa» a secas: al quitar las tildes, «papa» y «papá» son la
     * misma clave. Un ejercicio sobre la familia sacaría una patata, o al
     * revés. Se dejan solo las formas que no admiten confusión.
     */
    'brocoli' => '🥦', 'maiz' => '🌽', 'papas' => '🥔', 'patata' => '🥔',
    'cebolla' => '🧅', 'ajo' => '🧄', 'lechuga' => '🥬',
    'pan' => '🍞', 'queso' => '🧀', 'huevo' => '🥚', 'huevos' => '🥚',
    'leche' => '🥛', 'agua' => '💧', 'jugo' => '🧃',
    'arroz' => '🍚', 'sopa' => '🍲', 'pizza' => '🍕', 'hamburguesa' => '🍔',
    'galleta' => '🍪', 'galletas' => '🍪',
    'dulce' => '🍬', 'dulces' => '🍬', 'caramelo' => '🍬',
    'torta' => '🎂', 'pastel' => '🎂', 'helado' => '🍦',
    'chocolate' => '🍫', 'miel' => '🍯', 'sal' => '🧂',
    'fruta' => '🍎', 'frutas' => '🍎', 'verdura' => '🥦', 'verduras' => '🥦',
    'comida' => '🍽️', 'desayuno' => '🥣', 'almuerzo' => '🍽️', 'cena' => '🍽️',
    'gaseosa' => '🥤', 'cafe' => '☕',

    // ── Cuerpo ───────────────────────────────────────────────────────
    'ojo' => '👀', 'ojos' => '👀', 'vista' => '👀',
    'oreja' => '👂', 'orejas' => '👂', 'oido' => '👂', 'oidos' => '👂',
    'nariz' => '👃', 'olfato' => '👃',
    'boca' => '👄', 'lengua' => '👅', 'gusto' => '👅',
    'diente' => '🦷', 'dientes' => '🦷',
    'mano' => '✋', 'manos' => '✋', 'tacto' => '✋', 'dedo' => '👆', 'dedos' => '✋',
    'pie' => '🦶', 'pies' => '🦶',
    'pierna' => '🦵', 'piernas' => '🦵', 'rodilla' => '🦵',
    'brazo' => '💪', 'brazos' => '💪', 'codo' => '💪',
    'cabeza' => '🧑', 'pelo' => '💇', 'cabello' => '💇',
    'corazon' => '🫀', 'cerebro' => '🧠', 'hueso' => '🦴', 'huesos' => '🦴',
    'cuerpo' => '🧍', 'piel' => '🤚',

    // ── Casa y objetos ───────────────────────────────────────────────
    'casa' => '🏠', 'hogar' => '🏠',
    'cama' => '🛏️', 'cuarto' => '🛏️', 'dormitorio' => '🛏️',
    'cocina' => '🍳', 'baño' => '🛁', 'sala' => '🛋️',
    'puerta' => '🚪', 'ventana' => '🪟', 'silla' => '🪑', 'mesa' => 'icono:mesa',
    'nevera' => '🧊', 'refrigerador' => '🧊',
    'estufa' => '🔥', 'plancha' => '👔', 'lavadora' => '👕',
    'licuadora' => '🥤', 'ventilador' => '🌀',
    'television' => '📺', 'televisor' => '📺', 'radio' => '📻',
    'telefono' => '📞', 'celular' => '📱',
    'computador' => '🖥️', 'computadora' => '🖥️', 'pantalla' => '🖥️',
    // Sin «ratón»: a estas edades es el animal, y ya está más arriba. La
    // clave repetida la ganaba el periférico y salía un 🖱️ en un
    // ejercicio de animales.
    'teclado' => '⌨️', 'impresora' => '🖨️',
    'parlante' => '🔊', 'parlantes' => '🔊', 'audifonos' => '🎧',
    'enchufe' => '🔌', 'bombilla' => '💡', 'luz' => '💡',
    'llave' => '🔑', 'reloj' => '🕐', 'espejo' => '🪞',
    'jabon' => '🧼', 'toalla' => '🧻', 'cepillo' => '🪥',
    'cuchara' => '🥄', 'tenedor' => '🍴', 'cuchillo' => '🔪',
    'plato' => '🍽️', 'vaso' => '🥤', 'olla' => '🍲', 'taza' => '☕',
    'escoba' => '🧹', 'basura' => '🗑️', 'caneca' => '🗑️',
    'juguete' => '🧸', 'juguetes' => '🧸', 'peluche' => '🧸',
    'pelota' => '⚽', 'balon' => '⚽', 'globo' => '🎈',
    'libro' => '📚', 'libros' => '📚', 'cuaderno' => '📓',
    'lapiz' => '✏️', 'lapices' => '✏️', 'crayon' => '🖍️', 'color' => '🖍️',
    'borrador' => '🧽', 'tijera' => '✂️', 'tijeras' => '✂️', 'pegante' => '🧴',
    'pincel' => '🖌️', 'regla' => '📏', 'maleta' => '🎒', 'mochila' => '🎒',
    'ropa' => '👕', 'camiseta' => '👕', 'camisa' => '👔', 'pantalon' => '👖',
    'zapato' => '👟', 'zapatos' => '👟', 'media' => '🧦', 'medias' => '🧦',
    'sombrero' => '🎩', 'gorra' => '🧢', 'gafas' => '👓',
    'abrigo' => '🧥', 'chaqueta' => '🧥', 'bufanda' => '🧣', 'guantes' => '🧤',
    'paraguas' => '☂️', 'sombrilla' => '☂️',
    'dinero' => '💰', 'moneda' => '🪙', 'monedas' => '🪙', 'billete' => '💵',
    'papel' => '📄', 'carta' => '✉️', 'regalo' => '🎁',
    'piedra' => '🪨', 'madera' => '🪵', 'vidrio' => '🪟', 'metal' => '🥄',
    'tela' => '🧵', 'ladrillo' => '🧱', 'arena' => '🏖️',

    // ── Transporte ───────────────────────────────────────────────────
    'carro' => '🚗', 'carros' => '🚗', 'auto' => '🚗',
    'bus' => '🚌', 'buseta' => '🚌', 'camion' => '🚚',
    'bicicleta' => '🚲', 'moto' => '🏍️',
    'tren' => '🚆', 'avion' => '✈️', 'barco' => '⛵', 'lancha' => '🚤',
    'ambulancia' => '🚑', 'bombero' => '🚒', 'policia' => '🚓',
    'rueda' => '🛞', 'semaforo' => '🚦',

    // ── Naturaleza y clima ───────────────────────────────────────────
    'sol' => '☀️', 'luna' => '🌙', 'estrella' => '⭐', 'estrellas' => '⭐',
    'nube' => '☁️', 'nubes' => '☁️', 'lluvia' => '🌧️', 'llueve' => '🌧️',
    'viento' => '💨', 'nieve' => '❄️', 'frio' => '🥶', 'calor' => '🥵',
    'tormenta' => '⛈️', 'arcoiris' => '🌈', 'fuego' => '🔥',
    'arbol' => '🌳', 'arboles' => '🌳', 'planta' => '🌱', 'plantas' => '🌱',
    'flor' => '🌸', 'flores' => '🌸', 'hoja' => '🍃', 'hojas' => '🍃',
    'semilla' => '🌰', 'raiz' => '🌱', 'pasto' => '🌿', 'bosque' => '🌲',
    'montaña' => '⛰️', 'mar' => '🌊', 'rio' => '🏞️', 'playa' => '🏖️',
    'tierra' => '🌍', 'planeta' => '🌍', 'mundo' => '🌎', 'cielo' => '☁️',
    'dia' => '☀️', 'noche' => '🌙', 'mañana' => '🌅', 'tarde' => '🌆',
    'invierno' => '❄️', 'verano' => '☀️',

    // ── Personas y familia ───────────────────────────────────────────
    'familia' => '👪', 'mama' => '👩', 'madre' => '👩', 'padre' => '👨',
    'hermano' => '🧒', 'hermana' => '👧', 'bebe' => '👶',
    'abuela' => '👵', 'abuelo' => '👴', 'tio' => '👨', 'tia' => '👩',
    'primo' => '🧒', 'prima' => '👧',
    'niño' => '🧒', 'niña' => '👧', 'niños' => '🧒',
    'amigo' => '🤝', 'amigos' => '🤝', 'compañero' => '🧒',
    'profesor' => '🧑‍🏫', 'profesora' => '🧑‍🏫', 'profe' => '🧑‍🏫',
    'medico' => '🩺', 'doctor' => '🩺', 'enfermera' => '🩺',
    'vecino' => '🏘️', 'vecinos' => '🏘️', 'adulto' => '🧑',

    // ── Lugares ──────────────────────────────────────────────────────
    'colegio' => '🏫', 'escuela' => '🏫', 'salon' => '🏫',
    'hospital' => '🏥', 'tienda' => '🏪', 'parque' => '🛝',
    'biblioteca' => '📚', 'iglesia' => '⛪', 'barrio' => '🏘️',
    'ciudad' => '🏙️', 'campo' => '🌾', 'finca' => '🚜',
    'calle' => '🛣️', 'carretera' => '🛣️', 'puente' => '🌉',
    'colombia' => '🇨🇴', 'bandera' => '🚩',

    // ── Emociones ────────────────────────────────────────────────────
    'alegria' => '😊', 'alegre' => '😊', 'contento' => '😊', 'feliz' => '😊',
    'tristeza' => '😢', 'triste' => '😢', 'llorar' => '😢',
    'rabia' => '😠', 'enojo' => '😠', 'bravo' => '😠', 'enojado' => '😠',
    'miedo' => '😨', 'asustado' => '😨', 'susto' => '😨',
    'sorpresa' => '😮', 'cariño' => '🥰', 'amor' => '❤️',
    'vergüenza' => '😳', 'calma' => '🌬️', 'tranquilo' => '😌',
    'sueño' => '😴', 'dormir' => '😴', 'cansado' => '😴',
    'emocion' => '💛', 'emociones' => '💛', 'sentir' => '💛',

    // ── Rutinas y acciones ───────────────────────────────────────────
    'despertarse' => '⏰', 'levantarse' => '🛏️', 'bañarse' => '🚿',
    'ducharse' => '🚿', 'vestirse' => '👕', 'peinarse' => '💇',
    'desayunar' => '🥣', 'almorzar' => '🍽️', 'cenar' => '🍽️',
    'lavarse' => '🧼', 'lavar' => '🧼', 'cepillarse' => '🪥',
    'estudiar' => '📚', 'leer' => '📖', 'escribir' => '✍️', 'dibujar' => '🎨',
    'jugar' => '🧸', 'correr' => '🏃', 'saltar' => '🤸', 'caminar' => '🚶',
    'nadar' => '🏊', 'trepar' => '🧗', 'bailar' => '💃', 'cantar' => '🎤',
    'escuchar' => '👂', 'mirar' => '👀', 'hablar' => '🗣️',
    'comer' => '🍽️', 'beber' => '🥤', 'tomar' => '🥤',
    'recoger' => '🧹', 'ordenar' => '🗂️', 'guardar' => '📦',
    'ayudar' => '🤝', 'compartir' => '🤝', 'esperar' => '⏳', 'turno' => '🔄',
    'contar' => '🔢', 'sumar' => '➕', 'restar' => '➖', 'repartir' => '🤲',
    'crecer' => '📈', 'sembrar' => '🌱', 'regar' => '💧',
    'pintar' => '🎨', 'cortar' => '✂️', 'pegar' => '🧴',
    'apagar' => '💡', 'encender' => '💡', 'abrir' => '🔓', 'cerrar' => '🔒',
    'buscar' => '🔍', 'encontrar' => '🔍', 'perder' => '❓',

    // ── Escuela y nociones ───────────────────────────────────────────
    'numero' => '🔢', 'numeros' => '🔢', 'cantidad' => '🔢',
    'letra' => '🔤', 'letras' => '🔤', 'palabra' => '💬', 'palabras' => '💬',
    'vocal' => '🔤', 'vocales' => '🔤',
    'circulo' => '⚪', 'cuadrado' => '⬜', 'triangulo' => '🔺',
    'figura' => '🔷', 'figuras' => '🔷', 'forma' => '🔷', 'formas' => '🔷',
    'grande' => '🐘', 'pequeño' => '🐜', 'alto' => '📏', 'bajo' => '📏',
    'largo' => '📏', 'corto' => '📏', 'pesado' => '🪨', 'liviano' => '🎈',
    'lleno' => '🥛', 'vacio' => '🥛',
    'rojo' => '🔴', 'azul' => '🔵', 'amarillo' => '🟡', 'verde' => '🟢',
    'morado' => '🟣', 'naranjado' => '🟠', 'negro' => '⚫', 'blanco' => '⚪',
    'colores' => '🌈',
    'semana' => '📅', 'dias' => '📅', 'mes' => '📅', 'año' => '📅',
    'hora' => '🕐', 'tiempo' => '⏳', 'antes' => '⏮️', 'despues' => '⏭️',
    'musica' => '🎵', 'cancion' => '🎵', 'sonido' => '🔊', 'ruido' => '📢',
    'tambor' => '🥁', 'guitarra' => '🎸', 'piano' => '🎹', 'trompeta' => '🎺',
    'flauta' => 'icono:flauta', 'violin' => '🎻', 'maracas' => '🪇',
    'cuento' => '📖', 'historia' => '📖', 'dibujo' => '🎨', 'arte' => '🎨',

    // ── Salud y seguridad ────────────────────────────────────────────
    'salud' => '💚', 'enfermo' => '🤒', 'medicina' => '💊', 'medicamento' => '💊',
    'peligro' => '⚠️', 'cuidado' => '⚠️', 'seguridad' => '🦺',
    'casco' => '⛑️', 'cinturon' => '🔒', 'ayuda' => '🆘', 'emergencia' => '🚨',
    'herida' => '🩹', 'quemadura' => '🔥', 'golpe' => '🤕',
    'ejercicio' => '🏃', 'deporte' => '⚽', 'descanso' => '😴',
    'higiene' => '🧼', 'limpio' => '✨', 'sucio' => '🧽',

    // ── Tecnología ───────────────────────────────────────────────────
    'robot' => '🤖', 'maquina' => '⚙️', 'maquinas' => '⚙️',
    'internet' => '🌐', 'contraseña' => '🔑', 'archivo' => '📄',
    'carpeta' => '📁', 'foto' => '📷', 'camara' => '📷', 'video' => '🎬',
    'boton' => '🔲', 'orden' => '📋', 'ordenes' => '📋', 'instruccion' => '📋',

    // ── Palabras que aparecían sin dibujo y sí tienen uno claro ──────
    'unicornio' => '🦄', 'iguana' => '🦎', 'dinosaurio' => '🦕',
    'disfraz' => '🎭', 'cumpleaños' => '🎂', 'fiesta' => '🎉',
    'brote' => '🌱', 'tallo' => '🌿', 'fruto' => '🍎',
    'enjuagar' => '🚿', 'frotar' => '🧼', 'secar' => '🧻',
    'despertar' => '⏰', 'sentarse' => '🪑', 'parar' => '✋', 'repetir' => '🔁',
    'volar' => '🕊️', 'vuela' => '🕊️', 'nadar' => '🏊',
    'tarea' => '📝', 'fila' => '🚶', 'turno' => '🔄',
    'arriba' => '⬆️', 'abajo' => '⬇️', 'dentro' => '📦', 'fuera' => '🚪',
    'encima' => '⬆️', 'debajo' => '⬇️', 'lado' => '↔️', 'lados' => '↔️',
    'linea' => '📏', 'escala' => '📏', 'mitad' => '½',
    'joven' => '🧑', 'anciano' => '👴', 'persona' => '🧑', 'gente' => '👥',
    'respirar' => '🌬️', 'soplar' => '🌬️',
    'semaforo' => '🚦', 'cebra' => '🚸',

    // Los días. Todos con el mismo dibujo a propósito: en un ejercicio de
    // ordenarlos, siete iconos distintos darían la respuesta.
    'lunes' => '📅', 'martes' => '📅', 'miercoles' => '📅', 'jueves' => '📅',
    'viernes' => '📅', 'sabado' => '📅', 'domingo' => '📅',

    // Inglés de preescolar: las palabras que aparecen en los enunciados.
    'hello' => '👋', 'goodbye' => '👋', 'family' => '👪', 'house' => '🏠',
    'mother' => '👩', 'father' => '👨', 'sister' => '👧', 'brother' => '🧒',
    'baby' => '👶', 'dog' => '🐶', 'bird' => '🐦', 'fish' => '🐠',
    'horse' => '🐴', 'rabbit' => '🐰', 'duck' => '🦆', 'ball' => '⚽',
    'tree' => '🌳', 'car' => '🚗', 'red' => '🔴', 'blue' => '🔵',
    'green' => '🟢', 'yellow' => '🟡', 'black' => '⚫', 'white' => '⚪',
    'orange' => '🟠', 'purple' => '🟣',

    // ── Segunda pasada: lo que seguía saliendo sin dibujo ────────────
    'juego' => '🎮', 'juegos' => '🎮',
    /*
     * Sin «mañana» aquí: ya está más arriba como 🌅, y en preescolar
     * casi siempre es «la mañana» de la rutina y no «mañana» el día
     * siguiente. La clave repetida la ganaba esta y salía un ⏭️ en
     * «Ordena la rutina de la mañana».
     */
    'ayer' => '⏮️', 'hoy' => '📅', 'rutina' => '📋',
    'voz' => '🗣️', 'ruidos' => '📢', 'grito' => '📢',
    'desconocido' => '🚫', 'extraño' => '🚫',
    'prometo' => '🤝', 'promesa' => '🤝', 'prometer' => '🤝',
    'roto' => '🔧', 'rompo' => '🔧', 'romper' => '🔧',
    'borro' => '🗑️', 'borrar' => '🗑️', 'borra' => '🗑️',
    'equivoco' => '🤔', 'equivocarse' => '🤔', 'error' => '🤔',
    'necesita' => '❗', 'necesitan' => '❗', 'necesito' => '❗',
    'permiso' => '🙋', 'gracias' => '🙏', 'perdon' => '🤲',
    'salon' => '🏫', 'recreo' => '⚽', 'fila' => '🚶',
    'velas' => '🕯️', 'regalos' => '🎁',
    'alguien' => '🧑', 'nadie' => '🚫', 'todos' => '👥',
    'reglas' => '📋', 'acuerdo' => '🤝', 'decidir' => '🤔',
];

/**
 * Los números, para cuando el enunciado ES un número.
 *
 * «1 es…», «¿Cuántos son 3?». El dibujo honesto ahí es la cifra misma:
 * un niño de preescolar reconoce el 3 mucho antes de leer «tres».
 *
 * Va aparte del diccionario porque se busca distinto —una cifra suelta,
 * no una palabra— y porque **no se usa en las secuencias que hay que
 * ordenar**: ahí pondría la respuesta encima de cada ficha.
 */
const DIBUJOS_CIFRAS = [
    '0' => '0️⃣', '1' => '1️⃣', '2' => '2️⃣', '3' => '3️⃣', '4' => '4️⃣',
    '5' => '5️⃣', '6' => '6️⃣', '7' => '7️⃣', '8' => '8️⃣', '9' => '9️⃣',
    '10' => '🔟',
];

/**
 * La cifra del enunciado, si el enunciado es poco más que una cifra.
 *
 * Se exige que el texto sea CORTO. «1 es…» sí; «Había 3 niños y llegaron
 * 5 más, ¿cuántos hay?» no — ahí la cifra no resume nada y el dibujo
 * bueno es otro.
 */
function cifraDe(string $texto): ?string
{
    $t = trim($texto);

    if (mb_strlen($t) > 14) {
        return null;
    }

    if (preg_match('/^(\d{1,2})\b/u', $t, $m) !== 1) {
        return null;
    }

    return DIBUJOS_CIFRAS[$m[1]] ?? null;
}


/**
 * Preguntas que no hablan de una cosa, sino de una tarea.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  NO TODO EJERCICIO TIENE UN SUSTANTIVO QUE DIBUJAR
 * ─────────────────────────────────────────────────────────────────────
 *
 * «¿Cuál es diferente?», «¿Qué hago?», «¿Cuántos hay?». Son casi cuatro
 * de cada diez de los que quedaban sin dibujo, y ninguno habla de una
 * manzana ni de un perro: hablan de lo que hay que HACER.
 *
 * Para esos, el dibujo honesto no es un objeto —sería inventárselo— sino
 * el icono de la tarea. Al niño le dice de qué va el ejercicio antes de
 * oír la voz entera, y siempre el mismo para el mismo tipo de pregunta,
 * que es como se convierte en una señal que reconoce.
 *
 * Se prueban DESPUÉS del diccionario de cosas: si el enunciado nombra un
 * banano, manda el banano.
 *
 * Las claves son trozos que se buscan tal cual dentro del texto ya
 * normalizado, sin límite de palabra: aquí sí interesa que «cuant» pille
 * «cuántos» y «cuántas».
 */
const DIBUJOS_TAREA = [
    'cual es diferente' => '🔍',
    'cual no pertenece' => '🔍',
    'cual sobra'        => '🔍',
    'cual no es'        => '🔍',
    'diferente'         => '🔍',
    'no pertenece'      => '🔍',

    'cuant'             => '🔢',
    'mayor'             => '⚖️',
    'menor'             => '⚖️',
    'mas grande'        => '⚖️',
    'mas pequeñ'        => '⚖️',

    'que hago'          => '🤔',
    'que hacemos'       => '🤔',
    'que debo hacer'    => '🤔',
    'que digo'          => '🗣️',
    'le digo'           => '🗣️',
    'que pasa'          => '➡️',
    'va primero'        => '1️⃣',
    'que sigue'         => '➡️',
    'que viene'         => '➡️',
    'va despues'        => '➡️',

    'como suena'        => '🔊',
    'suena'             => '🔊',
    'se escucha'        => '🔊',

    'empieza con'       => '🔤',
    'empiezan con'      => '🔤',
    'lleva la letra'    => '🔤',

    'elige'             => '👆',
    'toca todo'         => '👆',
    'marca'             => '👆',

    'respuesta correcta'=> '✅',
    'esta bien'         => '⚖️',
    'esta mal'          => '⚖️',
    'es verdad'         => '⚖️',
    'tiene sentido'     => '🤔',

    'que falta'         => '❓',
    'cual falta'        => '❓',
    'quien soy'         => '❓',

    'donde'             => '📍',
    'para que sirve'    => '🔧',
    'de que color'      => '🌈',
    'de que esta hecho' => '🧱',

    'en ingles es'      => '🇬🇧',
    'en ingles'         => '🇬🇧',
    'seres vivos'       => '🌱',
    'lo mejor es'       => '👍',
    'que hay que'       => '🤔',
    'conviene'          => '👍',
    'no se come'        => '🍽️',
    'se come'           => '🍽️',
    'a quien'           => '🧑',
    'con que'           => '🔧',
    'para decir'        => '🗣️',
    'respondo'          => '🗣️',

    // Aritmética contada con palabras: lo que se hace, no lo que hay.
    'llego'             => '➕',
    'llegaron'          => '➕',
    'llegan'            => '➕',
    'me dieron'         => '➕',
    'se fue'            => '➖',
    'se fueron'         => '➖',
    'quedan'            => '➖',
    'se me perdio'      => '➖',
    'me comi'           => '➖',
    'reparto'           => '🤲',
    'a cada uno'        => '🤲',

    // Saludos de inglés, que se preguntan en español.
    'how are you'       => '🗣️',
    'thank you'         => '🙏',
    'my name is'        => '🙋',
    'como te llamas'    => '🙋',
    'como estas'        => '🗣️',
];


/**
 * Palabras que NUNCA deben elegirse como dibujo.
 *
 * Son las que aparecen en casi cualquier enunciado y producirían un
 * dibujo sin relación con lo que se pregunta.
 */
const DIBUJOS_PROHIBIDOS = ['color', 'orden', 'forma', 'tiempo', 'cosa', 'cosas'];


/**
 * Si el texto empieza por un dibujo, lo separa del resto.
 *
 * Mucho contenido escribe «🎂 En un cumpleaños…»: el dibujo está, pero
 * dentro de la frase y del tamaño de una letra. Sacado aparte se pinta
 * grande, que es donde lo mira un niño de cinco años.
 *
 * @return array{emoji:string, resto:string}|null
 */
function emojiDelante(string $texto): ?array
{
    $t = ltrim($texto);

    if ($t === '') {
        return null;
    }

    try {
        // Un pictograma y los modificadores que lo acompañan: tono de
        // piel, selector de variación y uniones de ancho cero (la familia
        // 👩‍👧 son tres símbolos unidos).
        $patron = '/^((?:[\p{Extended_Pictographic}]'
                . '[\x{1F3FB}-\x{1F3FF}\x{FE0E}\x{FE0F}\x{20E3}]*'
                . '(?:\x{200D}[\p{Extended_Pictographic}]'
                . '[\x{1F3FB}-\x{1F3FF}\x{FE0E}\x{FE0F}]*)*)+)\s*(.+)$/us';
    } catch (Throwable $e) {
        return null;
    }

    if (preg_match($patron, $t, $m) !== 1) {
        return null;
    }

    $resto = trim((string) ($m[2] ?? ''));

    /*
     * Tiene que quedar una FRASE de verdad, con letras o números.
     *
     * No basta con `!== ''`, y costó un fallo real: «🏗️» es el pictograma
     * más un selector de variación invisible. Al no quedar nada para el
     * resto, la expresión retrocedía y le entregaba el selector — así que
     * el paso quedaba con dibujo 🏗 y palabra «◌», invisible. Tres pasos
     * así parecían el mismo y el validador los daba por repetidos.
     *
     * Si el dibujo ERA todo el texto, aquí no hay nada que separar.
     */
    if (preg_match('/[\p{L}\p{N}]/u', $resto) !== 1) {
        return null;
    }

    return ['emoji' => (string) $m[1], 'resto' => $resto];
}

/**
 * El primer dibujo que aparezca en el texto, esté donde esté.
 *
 * Último recurso, para frases como «Si toco 🗑️, lo que borro…»: el
 * dibujo está dentro de la oración y del tamaño de una letra. Se copia
 * arriba para que se vea, **sin quitarlo del texto** — ahí forma parte de
 * la frase y quitarlo la dejaría coja.
 */
function primerEmoji(string $texto): ?string
{
    try {
        $ok = preg_match('/([\p{Extended_Pictographic}]'
                       . '[\x{1F3FB}-\x{1F3FF}\x{FE0E}\x{FE0F}\x{20E3}]*)/u',
                         $texto, $m);
    } catch (Throwable $e) {
        return null;
    }

    return $ok === 1 ? (string) $m[1] : null;
}

/**
 * Le pone dibujo a una estación que se escribió sin él.
 *
 * ─────────────────────────────────────────────────────────────────────
 *  EL ÚNICO SITIO QUE ALCANZA A TODO EL CATÁLOGO
 * ─────────────────────────────────────────────────────────────────────
 *
 * `omp()`, `reto()` y `ordenar()` ilustran solos lo que se escribe con
 * ellos, pero eso deja fuera dos cosas: el contenido escrito con la
 * forma cruda —una lista de cadenas dentro de `ordenar_secuencia`— y,
 * sobre todo, **las actividades que no vienen de los archivos de
 * contenido**. Las vocales y las letras las creó una migración y el
 * sembrador ni las mira.
 *
 * Por eso esto es una función aparte y no un detalle del sembrador: la
 * usan los dos caminos, el de sembrar y el de repasar lo que ya está.
 *
 * No pisa nada: si el autor puso dibujo, ese gana siempre.
 */
function ilustrar($datos, string $tipo)
{
    if (!is_array($datos)) {
        return $datos;
    }

    // ── Secuencias: cada paso, su dibujo ─────────────────────────────
    if ($tipo === 'ordenar_secuencia' && isset($datos['items']) && is_array($datos['items'])) {
        $datos['items'] = array_map(static function ($it) {
            if (is_array($it)) {
                $w = (string) ($it['w'] ?? '');

                /*
                 * Reparación: si la palabra no tiene ni una letra ni un
                 * número, no es una palabra. Pasó con los pasos que eran
                 * solo dibujo —«🏗️»— al separarlos mal: quedaba el
                 * dibujo y un selector de variación invisible de palabra.
                 * Se devuelve al dibujo suelto, que es lo que era.
                 */
                if (($it['e'] ?? '') !== '' && preg_match('/[\p{L}\p{N}]/u', $w) !== 1) {
                    return (string) $it['e'];
                }

                if (($it['e'] ?? '') === '' && $w !== '') {
                    $e = dibujoDe($w);
                    if ($e !== null) { $it['e'] = $e; }
                }
                return $it;
            }

            $palabra = (string) $it;

            /*
             * Si el paso YA era un dibujo suelto, se deja como estaba:
             * hay secuencias que se ordenan justo por lo que se ve, y
             * ponerle una palabra debajo sería contestar el ejercicio.
             */
            $delante = emojiDelante($palabra);

            if ($delante !== null) {
                return ['w' => $delante['resto'], 'e' => $delante['emoji']];
            }

            $e = dibujoDe($palabra);

            return $e !== null ? ['w' => $palabra, 'e' => $e] : $palabra;
        }, $datos['items']);
    }

    // ── Preguntas de un cuento ───────────────────────────────────────
    if ($tipo === 'cuento' && isset($datos['qs']) && is_array($datos['qs'])) {
        $datos['qs'] = array_map(static function ($q) {
            if (is_array($q) && ($q['e'] ?? '') === '' && isset($q['q'])) {
                $e = dibujoDe((string) $q['q']);
                if ($e !== null) { $q['e'] = $e; }
            }
            return $q;
        }, $datos['qs']);
    }

    // ── Desafíos y opción múltiple ───────────────────────────────────
    if (in_array($tipo, ['desafio_final', 'opcion_multiple'], true)) {
        foreach ($datos as $k => $d) {
            if (!is_array($d)) {
                continue;
            }

            if ($tipo === 'desafio_final' && trim((string) ($d['e'] ?? '')) === ''
                && isset($d['q'])) {

                $texto   = (string) $d['q'];
                $delante = emojiDelante($texto);

                if ($delante !== null) {
                    $datos[$k]['e'] = $delante['emoji'];
                    $datos[$k]['q'] = $delante['resto'];
                } else {
                    $e = cifraDe($texto) ?? dibujoDe($texto);
                    if ($e !== null) { $datos[$k]['e'] = $e; }
                }
            }

            /*
             * `=== null` no bastaba: hay contenido antiguo que escribe
             * `'visual' => ''`, y una cadena vacía se pinta igual de
             * blanca que un null pero no entraba aquí.
             *
             * Y `visual` no siempre es un emoji: «mezcla de colores» y
             * «lista de la compra» guardan ahí un array con lo que hay
             * que pintar. Esos YA tienen dibujo —uno mejor que un
             * emoji— y el `(string)` los convertía en «Array» con un
             * aviso de PHP por cada uno.
             */
            if ($tipo === 'opcion_multiple'
                && !is_array($d['visual'] ?? null)
                && trim((string) ($d['visual'] ?? '')) === ''
                && isset($d['enunciado'])) {

                $texto = (string) $d['enunciado'];

                /*
                 * Si el enunciado YA empieza por un dibujo —«🎂 En un
                 * cumpleaños…»— ese es el bueno: lo eligió quien escribió
                 * el ejercicio. Se saca del texto y se pone donde se
                 * pinta grande. Dejarlo dentro de la frase lo deja del
                 * tamaño de una letra.
                 */
                $delante = emojiDelante($texto);

                if ($delante !== null) {
                    $datos[$k]['visual']     = $delante['emoji'];
                    $datos[$k]['tipoVisual'] = 'emoji';
                    $datos[$k]['enunciado']  = $delante['resto'];
                    continue;
                }

                // La cifra antes que el diccionario: en «1 es…» el dibujo
                // bueno es el 1️⃣, no lo que diga una palabra suelta.
                // Y si nada de eso da, al menos el dibujo que ya esté
                // escondido dentro de la frase.
                $e = cifraDe($texto) ?? dibujoDe($texto) ?? primerEmoji($texto);

                if ($e !== null) {
                    $datos[$k]['visual']     = $e;
                    $datos[$k]['tipoVisual'] = 'emoji';
                }
            }
        }
    }

    return $datos;
}

/** Quita tildes y pasa a minúscula, para comparar sin sorpresas. */
function dibujosNormalizar(string $s): string
{
    $s = mb_strtolower(trim($s));

    return strtr($s, [
        'á' => 'a', 'é' => 'e', 'í' => 'i', 'ó' => 'o', 'ú' => 'u', 'ü' => 'u',
        'à' => 'a', 'è' => 'e', 'ì' => 'i', 'ò' => 'o', 'ù' => 'u',
    ]);
}

/**
 * El dibujo que le corresponde a un texto, o null si no hay ninguno claro.
 *
 * Busca la palabra más larga del diccionario que aparezca COMPLETA en el
 * texto. Que sea la más larga importa: en «lavarse las manos» conviene
 * 🧼 y no ✋, y la clave larga es la que describe mejor la frase.
 *
 * El mínimo es de TRES letras y no de cuatro: «pez», «sol», «pan», «mar»
 * y «ojo» son vocabulario central de preescolar, y dejarlas fuera costaba
 * más dibujos de los que salvaba. Lo que protege de los falsos positivos
 * es el límite de palabra, no el largo.
 *
 * @param string $texto      El enunciado. Nunca la respuesta.
 * @param int    $minLargo   Largo mínimo de la palabra que puede ganar.
 */
function dibujoDe(string $texto, int $minLargo = 3): ?string
{
    static $ordenadas = null;

    if ($ordenadas === null) {
        $ordenadas = DIBUJOS;

        // Las claves largas primero: «lavarse» debe ganarle a «lavar».
        uksort($ordenadas, static fn($a, $b) => mb_strlen((string) $b) <=> mb_strlen((string) $a));
    }

    $t = dibujosNormalizar($texto);

    if ($t === '') {
        return null;
    }

    foreach ($ordenadas as $clave => $emoji) {
        $c = dibujosNormalizar((string) $clave);

        if (mb_strlen($c) < $minLargo || in_array($c, DIBUJOS_PROHIBIDOS, true)) {
            continue;
        }

        /*
         * Palabra COMPLETA, no trozo. Sin esto «oso» casaría dentro de
         * «peligroso» y «mano» dentro de «manosear»: el niño vería un oso
         * en una pregunta sobre peligros y se quedaría con eso.
         *
         * El delimitador no puede ser `\b` de PCRE, que no entiende la ñ
         * ni las tildes; se usan los caracteres que de verdad separan
         * palabras en español.
         */
        $patron = '/(?:^|[\s¿?¡!.,;:()«»"\'\-–—])'
                . preg_quote($c, '/')
                . '(?:$|[\s¿?¡!.,;:()«»"\'\-–—])/u';

        if (preg_match($patron, $t) === 1) {
            return (string) $emoji;
        }
    }

    /*
     * Ninguna cosa. Puede ser una pregunta sobre una TAREA —«¿cuál es
     * diferente?»— y entonces el dibujo honesto es el de la tarea, no un
     * objeto inventado.
     *
     * Aquí se busca el trozo tal cual, sin límite de palabra: interesa
     * que «cuant» pille «cuántos» y «cuántas» de una vez.
     */
    static $tareas = null;

    if ($tareas === null) {
        $tareas = DIBUJOS_TAREA;
        uksort($tareas, static fn($a, $b) => mb_strlen((string) $b) <=> mb_strlen((string) $a));
    }

    foreach ($tareas as $clave => $emoji) {
        if (mb_strpos($t, dibujosNormalizar((string) $clave)) !== false) {
            return (string) $emoji;
        }
    }

    return null;
}
