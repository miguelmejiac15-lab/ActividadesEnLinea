<?php
/**
 * letras-ampliacion.php — Lengua para quinto y sexto
 *
 * Aventura de las Letras es la categoría más grande del catálogo —62
 * actividades— pero **solo seis llegaban a quinto y sexto**. A esa edad
 * el trabajo ya no es descifrar: es entender lo que no está escrito y
 * escribir para que otro entienda.
 */

declare(strict_types=1);

return [

'categoria' => [
    'slug'       => 'letras',
    'name'       => 'Aventura de las Letras',
    'tagline'    => 'Lectoescritura',
    'icon'       => '✏️',
    'color'      => '#29b6f6',
    'sort_order' => 1,
],

'bloques' => [
    ['slug' => 'tipos-de-texto', 'name' => 'Tipos de Texto', 'icon' => '📰', 'sort_order' => 7,
     'description' => 'Narrar, informar, instruir y convencer: cada intención pide un texto distinto.'],
    ['slug' => 'vocabulario-y-significado', 'name' => 'Vocabulario y Significado', 'icon' => '🔤', 'sort_order' => 6,
     'description' => 'Sinónimos, antónimos, prefijos, sufijos y palabras que se agrupan por su sentido.'],
],

'actividades' => [

[
    'slug'  => 'leer-entre-lineas',
    'title' => 'Leer entre líneas',
    'description' => 'Lo que el texto no dice pero se puede deducir de lo que sí dice.',
    'objective' => 'Realizar inferencias a partir de indicios textuales explícitos.',
    'icon' => '🧐', 'nivel' => 'primaria-superior', 'bloque' => 'tipos-de-texto',
    'duracion' => 15, 'tags' => ['comprension', 'deduccion', 'lectura'],
    'estaciones' => [

        est('Lo que se deduce', 'El texto lo sugiere, no lo dice', '🕵️', 'opcion_multiple', [
            omp('«Ana entró temblando y se quitó la bufanda.» ¿Qué tiempo hacía?', ['Frío', 'Calor', 'No se puede saber'], 'Frío'),
            omp('«Se le llenaron los ojos de lágrimas y no pudo seguir hablando.» Ana está…', ['Emocionada o triste', 'Alegre', 'Aburrida'], 'Emocionada o triste'),
            omp('«El plato quedó vacío y él sonreía.» Seguramente…', ['Le gustó la comida', 'No comió', 'Estaba enfermo'], 'Le gustó la comida'),
            omp('«Miró el reloj tres veces en un minuto.» Seguramente…', ['Tiene prisa', 'Está tranquilo', 'No sabe la hora'], 'Tiene prisa'),
            omp('Deducir es…',                    ['Sacar una conclusión de las pistas', 'Inventar', 'Copiar'], 'Sacar una conclusión de las pistas'),
        ]),

        est('¿Está escrito o lo deduje?', 'Dos cosas distintas', '⚖️', 'juego_rapido',
            conTitulo('¿Está LITERAL en el texto?', 'Texto: «Luis llegó empapado y dejó el paraguas roto en la puerta.»', [
                ['e' => '💧', 'n' => 'Luis llegó empapado',       'ok' => true],
                ['e' => '🌧️', 'n' => 'Estaba lloviendo',          'ok' => false],
                ['e' => '☂️', 'n' => 'El paraguas estaba roto',   'ok' => true],
                ['e' => '💨', 'n' => 'Hubo mucho viento',         'ok' => false],
                ['e' => '🚪', 'n' => 'Lo dejó en la puerta',      'ok' => true],
                ['e' => '😠', 'n' => 'Luis estaba enojado',       'ok' => false],
            ])),

        est('La intención del autor', 'Para qué escribió esto', '🎯', 'opcion_multiple', [
            omp('Un texto que explica cómo armar un mueble quiere…', ['Instruir', 'Convencer', 'Entretener'], 'Instruir'),
            omp('Un anuncio de un producto quiere…',  ['Convencer', 'Informar sin más', 'Instruir'], 'Convencer'),
            omp('Una noticia quiere…',                ['Informar', 'Vender', 'Hacer reír'], 'Informar'),
            omp('Un cuento quiere sobre todo…',       ['Entretener y conmover', 'Instruir', 'Vender'], 'Entretener y conmover'),
            omp('Saber la intención ayuda a…',        ['Leer con la desconfianza justa', 'Leer más rápido', 'Nada'], 'Leer con la desconfianza justa'),
        ]),

        est('Reto de la inferencia', 'Cinco para cerrar', '🏆', 'desafio_final', [
            reto('Inferir es…',                  ['Concluir a partir de pistas', 'Copiar el texto', 'Adivinar sin base'], 'Concluir a partir de pistas'),
            reto('«Se puso el abrigo y los guantes» sugiere…', ['Frío', 'Calor', 'Nada'], 'Frío'),
            reto('Lo que NO está en el texto ni se deduce de él es…', ['Una invención', 'Una inferencia', 'Un dato'], 'Una invención'),
            reto('Un texto que quiere convencer suele…', ['Elegir los datos que le convienen', 'Dar todos los datos', 'No tener datos'], 'Elegir los datos que le convienen'),
            reto('Para inferir bien hay que…',   ['Apoyarse en lo que el texto sí dice', 'Ignorar el texto', 'Leer el título'], 'Apoyarse en lo que el texto sí dice'),
        ]),
    ],
],

[
    'slug'  => 'escribir-para-que-me-entiendan',
    'title' => 'Escribir para que me entiendan',
    'description' => 'Planear, escribir, revisar: un texto bueno casi nunca sale a la primera.',
    'objective' => 'Aplicar el proceso de escritura y revisar la claridad del propio texto.',
    'icon' => '✍️', 'nivel' => 'primaria-superior', 'bloque' => 'tipos-de-texto',
    'duracion' => 15, 'tags' => ['escritura', 'comprension', 'organizacion'],
    'estaciones' => [

        est('Antes de escribir', 'Tres preguntas', '🗺️', 'opcion_multiple', [
            omp('Antes de escribir conviene saber…', ['Para quién escribo', 'Cuántas hojas', 'El color del papel'], 'Para quién escribo'),
            omp('También conviene saber…',           ['Para qué escribo', 'Qué hora es', 'Nada'], 'Para qué escribo'),
            omp('Un texto para un niño pequeño usa…', ['Frases cortas y palabras conocidas', 'Palabras difíciles', 'Párrafos largos'], 'Frases cortas y palabras conocidas'),
            omp('Un esquema previo sirve para…',     ['No perderse a mitad', 'Gastar tiempo', 'Rellenar'], 'No perderse a mitad'),
            omp('Escribir sin plan suele dar…',      ['Un texto desordenado', 'El mejor texto', 'Un texto corto'], 'Un texto desordenado'),
        ]),

        est('Ordena el proceso', 'Escribir tiene pasos', '🪜', 'ordenar_secuencia', [
            'title' => 'Ordena el proceso de escritura',
            'items' => ['Pensar para quién y para qué', 'Hacer una lista de ideas',
                        'Ordenar las ideas', 'Escribir el borrador',
                        'Leerlo en voz alta', 'Corregir y pasar a limpio'],
        ]),

        est('Revisar lo escrito', 'Lo que se busca al releer', '🔎', 'opcion_multiple', [
            omp('Leer en voz alta sirve para…',      ['Detectar frases que no suenan', 'Ir más rápido', 'Nada'], 'Detectar frases que no suenan'),
            omp('Una frase de cinco renglones sin puntos…', ['Conviene partirla', 'Es elegante', 'Es correcta'], 'Conviene partirla'),
            omp('Repetir la misma palabra seis veces…', ['Se arregla con sinónimos', 'Está bien', 'Es obligatorio'], 'Se arregla con sinónimos'),
            omp('Un párrafo debería tener…',         ['Una idea principal', 'Todas las ideas', 'Ninguna'], 'Una idea principal'),
            omp('Corregir la ortografía va…',        ['Al final, en la revisión', 'Antes de empezar', 'Nunca'], 'Al final, en la revisión'),
        ]),

        est('Conectores', 'Las palabras que unen ideas', '🔗', 'opcion_multiple', [
            omp('Para añadir una idea uso…',     ['Además', 'Pero', 'Sin embargo'], 'Además'),
            omp('Para oponer dos ideas uso…',    ['Sin embargo', 'Además', 'También'], 'Sin embargo'),
            omp('Para dar una causa uso…',       ['Porque', 'Aunque', 'Además'], 'Porque'),
            omp('Para concluir uso…',            ['Por lo tanto', 'Además', 'También'], 'Por lo tanto'),
            omp('Un texto sin conectores se lee…', ['A saltos', 'Mejor', 'Más rápido'], 'A saltos'),
        ]),
    ],
],

],
];
