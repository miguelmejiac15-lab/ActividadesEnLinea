-- =====================================================================
--  ACTIVIDADES EN LÍNEA · Proyecto Final
--  seed.sql — Datos iniciales
--
--  El catálogo NO es inventado: se extrajo del sitio actual
--  (sitio1-free/mundos.html y los archivos de actividades) para que la
--  nueva plataforma arranque con el contenido real ya clasificado.
--
--  Contenido sembrado:
--    ·  8 categorías        ·  4 niveles          ·  4 planes
--    · 32 mundos            ·  4 de preescolar
--    · 23 letras            ·  5 vocales          ·  4 paquetes de vocales
--
--  El usuario administrador NO se crea aquí a propósito: lo crea
--  instalar.php pidiendo la contraseña, para no dejar nunca una
--  credencial por defecto escrita en un archivo.
-- =====================================================================

SET NAMES utf8mb4;
USE `actividades_en_linea`;


-- =====================================================================
--  CATEGORÍAS  (administrables desde el panel, nunca escritas en HTML)
-- =====================================================================
INSERT INTO `categories` (`id`, `slug`, `name`, `tagline`, `icon`, `color`, `sort_order`) VALUES
(1, 'letras',     'Aventura de las Letras',   'Lectoescritura',                        '✏️', '#29b6f6', 1),
(2, 'matematica', 'Matemática',               'Números y lógica',                      '🔢', '#ff9800', 2),
(3, 'artistica',  'Artística',                'Arte y música',                         '🎨', '#ab47bc', 3),
(4, 'tecnologia', 'Tecnología',               'Código y computación',                  '💻', '#4caf50', 4),
(5, 'ciencias',   'Ciencias',                 'Ciencia y naturaleza',                  '🔬', '#26a69a', 5),
(6, 'valores',    'Valores y Convivencia',    'Habilidades socioemocionales',          '🤝', '#ef5350', 6),
(7, 'juegos',     'Juegos y Retos',           'Memoria, trivia y agilidad mental',     '🎲', '#ffa726', 7),
(8, 'dua',        'Aprender sin Barreras',    'Actividades accesibles para todos',     '♿', '#4dd0e1', 8);


-- =====================================================================
--  BLOQUES  (agrupan actividades dentro de una categoría)
--
--  «Aventura de las Letras» tiene casi 40 actividades: en una sola lista
--  el visitante no distingue las vocales de las letras ni de los mundos
--  de lectura. Los bloques recuperan la organización que ya tenía el
--  sitio anterior con sus submundos.
-- =====================================================================
INSERT INTO `collections` (`id`, `category_id`, `slug`, `name`, `description`, `icon`, `sort_order`) VALUES
(1, 1, 'bosque-de-vocales',   'Bosque de Vocales',
    'Las cinco vocales, una a una, con sus juegos y sus cuentos.', '🌳', 1),
(2, 1, 'reino-de-las-letras', 'Reino de las Letras',
    'Una aventura por cada letra del abecedario.', '🏰', 2),
(3, 1, 'mundos-de-lectura',   'Mundos de Lectura y Escritura',
    'Sílabas, cuentos, ortografía y escritura en aventuras completas.', '📖', 3),
(4, 2, 'primeros-numeros',    'Primeros Números',
    'El comienzo: contar, comparar y reconocer formas y tamaños.', '🌈', 1),
(5, 2, 'operaciones',         'Operaciones',
    'Sumar, restar, multiplicar y manejar dinero.', '➕', 2),
(6, 2, 'logica-y-medida',     'Lógica y Medida',
    'Figuras, secuencias, tiempo y razonamiento.', '🧩', 3);


-- =====================================================================
--  NIVELES  (filtro DENTRO de cada categoría, no una categoría aparte)
-- =====================================================================
INSERT INTO `levels` (`id`, `slug`, `name`, `min_age`, `max_age`, `sort_order`) VALUES
(1, 'preescolar',      'Preescolar',            3,  5, 1),
(2, 'primaria-inicial','Primero y segundo',     6,  7, 2),
(3, 'primaria-media',  'Tercero a quinto',      8, 10, 3),
(4, 'todas-las-edades','Todas las edades',      3, 12, 4);


-- =====================================================================
--  PLANES COMERCIALES
--  Precios en pesos colombianos, según el documento maestro del proyecto.
-- =====================================================================
INSERT INTO `plans`
    (`id`, `slug`, `name`, `tagline`, `description`,
     `price_monthly_cop`, `price_yearly_cop`, `catalog_access`, `manages_courses`,
     `is_recommended`, `is_active`, `sort_order`, `features`)
VALUES
(1, 'free', 'Gratis',
    'Descubre',
    'Prueba la plataforma de verdad: entra a todas las actividades y juega la primera parte de cada una.',
    0, 0, 'partial', 0, 0, 1, 1,
    JSON_ARRAY(
        'Entra a todas las actividades del catálogo',
        'Juega la primera parte de cada actividad',
        'Todas las categorías y niveles disponibles',
        'Sin tarjeta de crédito'
    )),

(2, 'biblioteca', 'Biblioteca Completa',
    'Accede',
    'Acceso al 100% del catálogo durante un año, incluidas todas las actividades nuevas que publiquemos.',
    12000, 99000, 'full', 0, 1, 1, 2,
    JSON_ARRAY(
        'Acceso al 100% de las actividades',
        'Todas las estaciones de cada actividad desbloqueadas',
        'Las nuevas actividades quedan incluidas',
        'Favoritos y progreso guardado',
        'Sin permanencia'
    )),

(3, 'escuela', 'Escuela',
    'Gestiona',
    'Gestión completa de cursos, estudiantes, asignaciones y progreso para la institución.',
    NULL, 6500000, 'full', 1, 0, 1, 3,
    JSON_ARRAY(
        'Todo lo de Biblioteca Completa',
        'Cursos, grupos y estudiantes',
        'Asignación de actividades y rutas',
        'Seguimiento por estudiante, grupo y curso',
        'Reportes institucionales'
    )),

-- Plan Docente: la arquitectura queda lista, pero no se ofrece todavía
-- (is_active = 0, así no aparece en la página de precios).
(4, 'docente', 'Docente',
    'Acompaña',
    'Catálogo completo más herramientas personales del docente, sin administración institucional.',
    NULL, 149000, 'full', 0, 0, 0, 4,
    JSON_ARRAY(
        'Acceso completo al catálogo',
        'Uso de actividades con estudiantes',
        'Favoritos y organización personal',
        'Compartir actividades'
    ));


-- =====================================================================
--  CONFIGURACIÓN GENERAL
-- =====================================================================
INSERT INTO `settings` (`key`, `value`, `label`) VALUES
('catalogo_modo_global',   'partial',                 'Modo del catálogo: partial (freemium normal) | all-free (todo abierto) | all-locked (todo cerrado)'),
('estaciones_libres_def',  '2',                       'Estaciones gratuitas por defecto al crear una actividad nueva'),
('sitio_nombre',           'Actividades en Línea',    'Nombre del sitio'),
('sitio_testigo',          'Pedagogía de avanzada, potenciada por tecnología', 'Testigo / bajada de marca'),
('plan_recomendado',       'biblioteca',              'Plan que se destaca en la página de precios'),
('nuevas_actividades_dias','60',                      'Cuántos días se considera "nueva" una actividad publicada'),
('exigir_cuenta_para_jugar','1',                     'Exigir cuenta para jugar: 1 (normal) | 0 (se juega sin registrarse; solo para demostraciones)');


-- =====================================================================
--  ACTIVIDADES
--
--  engine = 'legacy_html' → todavía se sirve el archivo original.
--  En la Fase 3 las actividades se portan al motor por estaciones y
--  `legacy_file` se vacía. El campo `free_stations` ya queda definido
--  aquí: ≈30% de las estaciones de cada actividad.
-- =====================================================================

-- ---------------------------------------------------------------------
--  Preescolar (categoría Matemática, nivel Preescolar)
-- ---------------------------------------------------------------------
INSERT INTO `activities`
    (`slug`, `title`, `description`, `category_id`, `level_id`, `engine`, `activity_type`,
     `legacy_file`, `icon`, `duration_minutes`, `access_type`, `free_stations`,
     `status`, `is_featured`, `published_at`)
VALUES
('formas-y-colores', 'Formas y Colores',
 'Aprende los colores y las figuras geométricas jugando. ¡Círculo, cuadrado, triángulo y más!',
 2, 1, 'legacy_html', 'juego', 'Pre_Formas_Colores.html', '🌈', 12, 'partial', 2, 'published', 1, NOW() - INTERVAL 120 DAY),

('tamanos-y-posiciones', 'Tamaños y Posiciones',
 'Grande y pequeño, arriba y abajo, dentro y fuera. ¡Aprende con objetos y animales!',
 2, 1, 'legacy_html', 'juego', 'Pre_Tamanos_Posiciones.html', '📏', 12, 'partial', 2, 'published', 0, NOW() - INTERVAL 118 DAY),

('cuento-hasta-10', 'Cuento hasta 10',
 'Cuenta objetos del 1 al 10, encuentra el número que falta y compara grupos.',
 2, 1, 'legacy_html', 'juego', 'Pre_Contando.html', '🔢', 12, 'partial', 2, 'published', 0, NOW() - INTERVAL 116 DAY),

('familias-de-numeros', 'Familias de Números',
 'Grupos del 1 al 10, familias del 10 al 20 y las decenas hasta el 100.',
 2, 1, 'legacy_html', 'juego', 'Pre_Familias_Numeros.html', '🏠', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 114 DAY);


-- ---------------------------------------------------------------------
--  Los 32 mundos del catálogo
-- ---------------------------------------------------------------------
INSERT INTO `activities`
    (`slug`, `title`, `description`, `category_id`, `level_id`, `engine`, `activity_type`,
     `legacy_file`, `icon`, `duration_minutes`, `access_type`, `free_stations`,
     `status`, `is_featured`, `published_at`)
VALUES
-- Letras
('bosque-de-las-vocales', 'Bosque de las Vocales',
 'Descubre los sonidos mágicos y aventuras de las vocales.',
 1, 1, 'legacy_html', 'juego', 'Bosque_de_vocales.html', '🌳', 15, 'partial', 2, 'published', 1, NOW() - INTERVAL 110 DAY),

('reino-de-las-letras', 'Reino de las Letras',
 'Aprende nuevas letras y forma palabras divertidas.',
 1, 2, 'legacy_html', 'juego', 'Reino_de_las_letras.html', '🏰', 15, 'partial', 2, 'published', 1, NOW() - INTERVAL 108 DAY),

('montana-de-las-silabas', 'Montaña de las Sílabas',
 'Une sílabas secretas saltando en la montaña.',
 1, 2, 'legacy_html', 'juego', 'Montaña_de_las_Silabas.html', '⚡', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 106 DAY),

('ciudad-de-los-cuentos', 'Ciudad de los Cuentos',
 'Lee historias fantásticas y vive desafíos increíbles.',
 1, 3, 'legacy_html', 'cuento', 'Ciudad_de_los_Cuentos.html', '📖', 20, 'partial', 2, 'published', 0, NOW() - INTERVAL 104 DAY),

('taller-de-escritura', 'Taller de Escritura',
 'Sigue los trazos luminosos con tu lápiz mágico.',
 1, 2, 'legacy_html', 'practica', 'Taller_de_Escritura.html', '✏️', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 102 DAY),

('mar-de-la-ortografia', 'Mar de la Ortografía',
 'Pesca las palabras correctas evitando las faltas ortográficas.',
 1, 3, 'legacy_html', 'reto', 'Mar_de_la_Ortografia.html', '🌊', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 100 DAY),

('carrera-de-palabras', 'Carrera de Palabras',
 'Acelera tu auto presionando las letras correctas velozmente.',
 1, 3, 'legacy_html', 'reto', 'Carrera_de_Palabras.html', '🏎️', 10, 'partial', 2, 'published', 1, NOW() - INTERVAL 98 DAY),

-- Matemática
('mina-de-los-numeros', 'Mina de los Números',
 'Descubre hermosas gemas contando tesoros de oro.',
 2, 2, 'legacy_html', 'juego', 'Mina_de_los_Numeros.html', '💎', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 96 DAY),

('valle-de-las-sumas', 'Valle de las Sumas',
 'Une conjuntos de objetos en divertidos huertos frutales.',
 2, 2, 'legacy_html', 'juego', 'Valle_de_las_Sumas.html', '➕', 15, 'partial', 2, 'published', 1, NOW() - INTERVAL 94 DAY),

('canon-de-las-restas', 'Cañón de las Restas',
 'Despeja globos restando números voladores rápidos.',
 2, 2, 'legacy_html', 'juego', 'Canon_de_las_Restas.html', '➖', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 92 DAY),

('isla-de-las-figuras', 'Isla de las Figuras',
 'Encuentra triángulos, círculos y cuadrados escondidos.',
 2, 1, 'legacy_html', 'juego', 'Isla_de_las_Figuras.html', '🛑', 12, 'partial', 2, 'published', 0, NOW() - INTERVAL 90 DAY),

('laberinto-de-logica', 'Laberinto de Lógica',
 'Ordena rompecabezas interactivos y secuencias de colores.',
 2, 3, 'legacy_html', 'reto', 'Laberinto_de_Logica.html', '🧩', 18, 'partial', 2, 'published', 0, NOW() - INTERVAL 88 DAY),

('reloj-del-tiempo', 'Reloj del Tiempo',
 'Aprende a leer las horas y los días del calendario.',
 2, 3, 'legacy_html', 'practica', 'Reloj_del_Tiempo.html', '⏰', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 86 DAY),

('mercado-de-monedas', 'Mercado de Monedas',
 'Compra juguetes simulados calculando el vuelto exacto.',
 2, 3, 'legacy_html', 'juego', 'Mercado_de_Monedas.html', '🛒', 18, 'partial', 2, 'published', 0, NOW() - INTERVAL 84 DAY),

('estadio-multiplicacion', 'Estadio Multiplicación',
 'Anota goles épicos respondiendo las tablas de multiplicar.',
 2, 3, 'legacy_html', 'reto', 'Estadio_Multiplicacion.html', '⚽', 15, 'partial', 2, 'published', 1, NOW() - INTERVAL 82 DAY),

-- Ciencias
('laboratorio-de-ciencias', 'Laboratorio de Ciencias',
 'Clasifica animales, plantas y elementos de la naturaleza.',
 5, 2, 'legacy_html', 'juego', 'Laboratorio_de_Ciencias.html', '🧪', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 80 DAY),

('planeta-del-espacio', 'Planeta del Espacio',
 'Viaja por el sistema solar y reconoce astros lejanos.',
 5, 3, 'legacy_html', 'juego', 'Planeta_del_Espacio.html', '🚀', 15, 'partial', 2, 'published', 1, NOW() - INTERVAL 78 DAY),

-- Valores
('teatro-de-los-valores', 'Teatro de los Valores',
 'Aprende sobre empatía, amistad y convivencia sana.',
 6, 2, 'legacy_html', 'cuento', 'Teatro_de_los_Valores.html', '🎭', 18, 'partial', 2, 'published', 0, NOW() - INTERVAL 76 DAY),

-- Artística
('estacion-musical', 'Estación Musical',
 'Crea ritmos pegajosos y reconoce notas musicales alegres.',
 3, 1, 'legacy_html', 'juego', 'Estacion_Musical.html', '🎵', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 74 DAY),

('galeria-del-arte', 'Galería del Arte',
 'Mezcla colores primarios y pinta lienzos digitales mágicos.',
 3, 1, 'legacy_html', 'juego', 'Galeria_del_Arte.html', '🎨', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 72 DAY),

('taller-de-manualidades', 'Taller de Manualidades',
 'Descubre materiales, herramientas y texturas para crear manualidades.',
 3, 2, 'legacy_html', 'juego', 'Taller_de_Manualidades.html', '🧵', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 70 DAY),

('fabrica-de-disfraces', 'Fábrica de Disfraces',
 'Combina colores, patrones y personajes para crear disfraces creativos.',
 3, 1, 'legacy_html', 'juego', 'Fabrica_de_Disfraces.html', '🎭', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 68 DAY),

-- Juegos y retos
('desafio-de-memoria', 'Desafío de Memoria',
 'Encuentra los pares de cartas ocultas en el menor tiempo.',
 7, 4, 'legacy_html', 'reto', 'Desafio_de_Memoria.html', '🧠', 10, 'partial', 2, 'published', 0, NOW() - INTERVAL 66 DAY),

('ruleta-de-la-fortuna', 'Ruleta de la Fortuna',
 'Gira la gran ruleta interactiva y responde trivias sorpresa.',
 7, 4, 'legacy_html', 'reto', 'Ruleta_de_la_Fortuna.html', '🎡', 12, 'partial', 2, 'published', 1, NOW() - INTERVAL 64 DAY),

-- Tecnología
('ciber-codigo-robot', 'Ciber-Código Robot',
 'Guía a un pequeño robot usando comandos y bloques de lógica.',
 4, 3, 'legacy_html', 'reto', 'Ciber_Codigo_Robot.html', '🤖', 20, 'partial', 2, 'published', 1, NOW() - INTERVAL 62 DAY),

('la-neo-computadora', 'La Neo-Computadora',
 'Aprende las partes de la PC, mecanografía básica y seguridad digital.',
 4, 3, 'legacy_html', 'juego', 'La_Neo_Computadora.html', '💻', 20, 'partial', 2, 'published', 0, NOW() - INTERVAL 60 DAY),

('torre-de-circuitos', 'Torre de Circuitos',
 'Aprende cómo enciende la electricidad y arma tus primeros circuitos.',
 4, 3, 'legacy_html', 'juego', 'Torre_de_Circuitos.html', '🔌', 18, 'partial', 2, 'published', 0, NOW() - INTERVAL 58 DAY),

('detective-digital', 'Detective Digital',
 'Investiga cómo usar contraseñas e internet de forma segura.',
 4, 3, 'legacy_html', 'reto', 'Detective_Digital.html', '🕵️', 18, 'partial', 2, 'published', 0, NOW() - INTERVAL 56 DAY),

-- Aprender sin Barreras (DUA)
('sonidos-y-senas', 'Sonidos y Señas',
 'Escucha, mira y empareja: aprender con oídos y ojos, con lectura en voz alta.',
 8, 4, 'legacy_html', 'juego', 'Sonidos_y_Senas.html', '🔊', 15, 'partial', 2, 'published', 1, NOW() - INTERVAL 30 DAY),

('ritmo-de-silabas', 'Ritmo de Sílabas',
 'Cuenta sílabas y encuentra rimas, con apoyo de lectura en voz alta.',
 8, 2, 'legacy_html', 'juego', 'Ritmo_de_Silabas.html', '🥁', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 28 DAY),

('mundo-de-contrastes', 'Mundo de Contrastes',
 'Compara tamaños, colores y formas con modo de alto contraste.',
 8, 1, 'legacy_html', 'juego', 'Mundo_de_Contrastes.html', '⚫', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 26 DAY),

('camino-paso-a-paso', 'Camino Paso a Paso',
 'Ordena rutinas diarias sin límite de tiempo, a tu propio ritmo.',
 8, 1, 'legacy_html', 'juego', 'Camino_Paso_a_Paso.html', '🧭', 15, 'partial', 2, 'published', 0, NOW() - INTERVAL 24 DAY);


-- ---------------------------------------------------------------------
--  Las 5 vocales · motor "vocal" · 11 estaciones cada una
--  free_stations = 3  (≈30% de 11)
-- ---------------------------------------------------------------------
INSERT INTO `activities`
    (`slug`, `title`, `description`, `category_id`, `level_id`, `engine`, `activity_type`,
     `content`, `legacy_file`, `icon`, `duration_minutes`, `access_type`, `free_stations`,
     `status`, `is_featured`, `published_at`)
VALUES
('vocal-a', 'Aventura de la A', 'Descubre la vocal A con 11 minijuegos: sonidos, imágenes, escritura y su cuento.',
 1, 1, 'legacy_html', 'paquete', JSON_OBJECT('vocal','A'), 'Vocal_A.html', '✈️', 25, 'partial', 3, 'published', 1, NOW() - INTERVAL 150 DAY),
('vocal-e', 'Aventura de la E', 'Descubre la vocal E con 11 minijuegos: sonidos, imágenes, escritura y su cuento.',
 1, 1, 'legacy_html', 'paquete', JSON_OBJECT('vocal','E'), 'Vocal_E.html', '🐘', 25, 'partial', 3, 'published', 0, NOW() - INTERVAL 149 DAY),
('vocal-i', 'Aventura de la I', 'Descubre la vocal I con 11 minijuegos: sonidos, imágenes, escritura y su cuento.',
 1, 1, 'legacy_html', 'paquete', JSON_OBJECT('vocal','I'), 'Vocal_I.html', '🦎', 25, 'partial', 3, 'published', 0, NOW() - INTERVAL 148 DAY),
('vocal-o', 'Aventura de la O', 'Descubre la vocal O con 11 minijuegos: sonidos, imágenes, escritura y su cuento.',
 1, 1, 'legacy_html', 'paquete', JSON_OBJECT('vocal','O'), 'Vocal_O.html', '🐻', 25, 'partial', 3, 'published', 0, NOW() - INTERVAL 147 DAY),
('vocal-u', 'Aventura de la U', 'Descubre la vocal U con 11 minijuegos: sonidos, imágenes, escritura y su cuento.',
 1, 1, 'legacy_html', 'paquete', JSON_OBJECT('vocal','U'), 'Vocal_U.html', '🦄', 25, 'partial', 3, 'published', 0, NOW() - INTERVAL 146 DAY);


-- ---------------------------------------------------------------------
--  Paquetes y juegos de vocales
-- ---------------------------------------------------------------------
INSERT INTO `activities`
    (`slug`, `title`, `description`, `category_id`, `level_id`, `engine`, `activity_type`,
     `legacy_file`, `icon`, `duration_minutes`, `access_type`, `free_stations`,
     `status`, `is_featured`, `published_at`)
VALUES
('vocales-parte-1', 'Vocales · Parte 1', 'Repaso de las cinco vocales en un solo recorrido, primera parte.',
 1, 1, 'legacy_html', 'paquete', 'Paquete_Vocales_Parte1.html', '1️⃣', 20, 'partial', 2, 'published', 0, NOW() - INTERVAL 145 DAY),
('vocales-parte-2', 'Vocales · Parte 2', 'Repaso de las cinco vocales en un solo recorrido, segunda parte.',
 1, 1, 'legacy_html', 'paquete', 'Paquete_Vocales_Parte2.html', '2️⃣', 20, 'partial', 2, 'published', 0, NOW() - INTERVAL 144 DAY),
('juegos-de-vocales-1', 'Juegos de Vocales 1', 'Espacio y nubes: atrapa las vocales correctas mientras vuelas.',
 1, 1, 'legacy_html', 'juego', 'Juegos_Vocales_1.html', '🚀', 12, 'partial', 2, 'published', 0, NOW() - INTERVAL 143 DAY),
('juegos-de-vocales-2', 'Juegos de Vocales 2', 'Globos y pesca: dos retos rápidos para reconocer vocales.',
 1, 1, 'legacy_html', 'juego', 'Juegos_Vocales_2.html', '🎈', 12, 'partial', 2, 'published', 0, NOW() - INTERVAL 142 DAY);


-- ---------------------------------------------------------------------
--  Las 23 letras · motor "letra" · 15 estaciones cada una
--  free_stations = 5  (≈30% de 15)
--
--  Estas 23 filas reemplazan a 23 archivos HTML de ~77 KB con estructura
--  idéntica: mismo motor, distinta letra. `content` guarda lo único que
--  cambia realmente entre ellas.
-- ---------------------------------------------------------------------
INSERT INTO `activities`
    (`slug`, `title`, `description`, `category_id`, `level_id`, `engine`, `activity_type`,
     `content`, `legacy_file`, `icon`, `duration_minutes`, `access_type`, `free_stations`,
     `status`, `is_featured`, `published_at`)
VALUES
('letra-m', 'Aventura de la M', 'Descubre el sonido de la M con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','M'), 'Letra_M.html', '🦋', 30, 'partial', 5, 'published', 1, NOW() - INTERVAL 140 DAY),
('letra-p', 'Aventura de la P', 'Descubre el sonido de la P con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','P'), 'Letra_P.html', '🦆', 30, 'partial', 5, 'published', 1, NOW() - INTERVAL 138 DAY),
('letra-s', 'Aventura de la S', 'Descubre el sonido de la S con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','S'), 'Letra_S.html', '☀️', 30, 'partial', 5, 'published', 1, NOW() - INTERVAL 136 DAY),
('letra-l', 'Aventura de la L', 'Descubre el sonido de la L con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','L'), 'Letra_L.html', '🔍', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 134 DAY),
('letra-t', 'Aventura de la T', 'Descubre el sonido de la T con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','T'), 'Letra_T.html', '🐯', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 132 DAY),
('letra-d', 'Aventura de la D', 'Descubre el sonido de la D con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','D'), 'Letra_D.html', '🐬', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 130 DAY),
('letra-n', 'Aventura de la Ñ', 'Descubre el sonido de la Ñ con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','Ñ'), 'Letra_Ñ.html', '🦩', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 128 DAY),
('letra-b', 'Aventura de la B', 'Descubre el sonido de la B con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','B'), 'Letra_B.html', '🦉', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 126 DAY),
('letra-c', 'Aventura de la C', 'Descubre el sonido de la C con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','C'), 'Letra_C.html', '🐴', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 124 DAY),
('letra-f', 'Aventura de la F', 'Descubre el sonido de la F con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','F'), 'Letra_F.html', '🦩', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 122 DAY),
('letra-g', 'Aventura de la G', 'Descubre el sonido de la G con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','G'), 'Letra_G.html', '🦆', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 120 DAY),
('letra-h', 'Aventura de la H', 'Descubre el sonido de la H con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','H'), 'Letra_H.html', '🚁', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 118 DAY),
('letra-j', 'Aventura de la J', 'Descubre el sonido de la J con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','J'), 'Letra_J.html', '🐆', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 116 DAY),
('letra-k', 'Aventura de la K', 'Descubre el sonido de la K con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','K'), 'Letra_K.html', '🥝', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 114 DAY),
('letra-r', 'Aventura de la R', 'Descubre el sonido de la R con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 2, 'legacy_html', 'paquete', JSON_OBJECT('letra','R'), 'Letra_R.html', '🌹', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 112 DAY),
('letra-v', 'Aventura de la V', 'Descubre el sonido de la V con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','V'), 'Letra_V.html', '🌋', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 110 DAY),
('letra-w', 'Aventura de la W', 'Descubre el sonido de la W con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','W'), 'Letra_W.html', '📶', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 108 DAY),
('letra-x', 'Aventura de la X', 'Descubre el sonido de la X con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','X'), 'Letra_X.html', '🎵', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 106 DAY),
('letra-y', 'Aventura de la Y', 'Descubre el sonido de la Y con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','Y'), 'Letra_Y.html', '⛵', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 104 DAY),
('letra-z', 'Aventura de la Z', 'Descubre el sonido de la Z con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','Z'), 'Letra_Z.html', '🦊', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 102 DAY),
('letra-ch', 'Aventura del CH', 'Descubre el sonido del CH con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','CH'), 'Letra_CH.html', '🍫', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 100 DAY),
('letra-qu', 'Aventura del QU', 'Descubre el sonido del QU con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','QU'), 'Letra_QU.html', '🧀', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 98 DAY),
('letra-gue-gui', 'Aventura del GUE·GUI', 'Descubre el sonido de GUE y GUI con 15 minijuegos: imágenes, sílabas, escritura, teclado y un cuento final.',
 1, 3, 'legacy_html', 'paquete', JSON_OBJECT('letra','GUE·GUI'), 'Letra_GUI.html', '🎸', 30, 'partial', 5, 'published', 0, NOW() - INTERVAL 96 DAY);
