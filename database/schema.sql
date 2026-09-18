-- =====================================================================
--  ACTIVIDADES EN LÍNEA · Proyecto Final
--  schema.sql — Estructura de base de datos
--
--  Motor: InnoDB · Juego de caracteres: utf8mb4 (soporta tildes, ñ y emojis)
--  Probado en MariaDB 10.4 (XAMPP)
--
--  Principio: estructura limpia y normalizada, sin sofisticación
--  innecesaria. Cada tabla tiene una responsabilidad clara.
-- =====================================================================

SET NAMES utf8mb4;
SET time_zone = '-05:00';   -- Hora de Colombia

CREATE DATABASE IF NOT EXISTS `actividades_en_linea`
    CHARACTER SET utf8mb4
    COLLATE utf8mb4_unicode_ci;

USE `actividades_en_linea`;


-- =====================================================================
--  1. USUARIOS Y ROLES
-- =====================================================================

-- Roles previstos:
--   admin        · administra todo el ecosistema
--   user         · usuario individual (free o con suscripción)
--   teacher      · docente (plan Docente, arquitectura preparada)
--   school_admin · administrador de una institución
--   student      · estudiante inscrito por una institución
--
-- PRIVACIDAD (Decreto 0769 de 2026, Colombia): la plataforma trata datos
-- de menores. Por eso NO se guarda fecha de nacimiento completa (solo el
-- año, suficiente para sugerir nivel) y se registra el correo del adulto
-- responsable cuando la cuenta pertenece a un menor.
CREATE TABLE IF NOT EXISTS `users` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(120)  NOT NULL,
    `email`          VARCHAR(190)  NOT NULL,
    `password`       VARCHAR(255)  NOT NULL COMMENT 'Hash generado con password_hash(); nunca texto plano',
    `role`           ENUM('admin','user','teacher','school_admin','student') NOT NULL DEFAULT 'user',
    `status`         ENUM('active','inactive','pending') NOT NULL DEFAULT 'active',
    `birth_year`     SMALLINT UNSIGNED NULL COMMENT 'Solo el año, no la fecha completa (minimización de datos de menores)',
    `guardian_email` VARCHAR(190)  NULL COMMENT 'Correo del adulto responsable si la cuenta es de un menor',
    `avatar`         VARCHAR(60)   NULL COMMENT 'Personaje elegido: el slug de un shop_items de tipo avatar',
    `accessory`      VARCHAR(60)   NULL COMMENT 'Accesorio puesto sobre el personaje (gorro, capa…)',
    `last_login_at`  DATETIME      NULL,
    `created_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_users_email` (`email`),
    KEY `idx_users_role_status` (`role`, `status`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  2. PLANES COMERCIALES
-- =====================================================================

-- Un plan = un nivel comercial. Los dos precios (mensual/anual) viven en
-- la misma fila para no crear una tabla extra; la suscripción registra
-- cuál de los dos ciclos eligió el usuario.
CREATE TABLE IF NOT EXISTS `plans` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`              VARCHAR(40)   NOT NULL COMMENT 'free | biblioteca | escuela | docente',
    `name`              VARCHAR(80)   NOT NULL,
    `tagline`           VARCHAR(160)  NULL COMMENT 'Frase corta de la tarjeta de precios',
    `description`       TEXT          NULL,
    `price_monthly_cop` INT UNSIGNED  NULL COMMENT 'NULL = no se ofrece en modalidad mensual',
    `price_yearly_cop`  INT UNSIGNED  NULL COMMENT 'NULL = no se ofrece en modalidad anual',
    `catalog_access`    ENUM('partial','full') NOT NULL DEFAULT 'partial'
                        COMMENT 'partial = solo lo marcado como libre; full = 100% del catálogo',
    `manages_courses`   TINYINT(1)    NOT NULL DEFAULT 0 COMMENT '1 = habilita el área Escuela',
    `is_recommended`    TINYINT(1)    NOT NULL DEFAULT 0 COMMENT 'Se destaca visualmente en la página de precios',
    `is_active`         TINYINT(1)    NOT NULL DEFAULT 1 COMMENT '0 = existe en BD pero no se ofrece todavía',
    `sort_order`        SMALLINT      NOT NULL DEFAULT 0,
    `features`          JSON          NULL COMMENT 'Lista de beneficios mostrados en la tarjeta',
    `created_at`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME      NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_plans_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  3. INSTITUCIONES (Plan Escuela · arquitectura preparada)
-- =====================================================================

CREATE TABLE IF NOT EXISTS `schools` (
    `id`             INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `name`           VARCHAR(160) NOT NULL,
    `slug`           VARCHAR(160) NOT NULL,
    `contact_name`   VARCHAR(120) NULL,
    `contact_email`  VARCHAR(190) NULL,
    `contact_phone`  VARCHAR(40)  NULL,
    `city`           VARCHAR(120) NULL,
    `student_quota`  SMALLINT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Cupo de estudiantes contratado',
    `status`         ENUM('active','inactive','pending') NOT NULL DEFAULT 'pending',
    `created_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`     DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_schools_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Relación usuario <-> institución. Un usuario pertenece a una escuela
-- con un papel concreto dentro de ella.
CREATE TABLE IF NOT EXISTS `school_users` (
    `school_id`  INT UNSIGNED NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `role`       ENUM('school_admin','teacher','student') NOT NULL DEFAULT 'student',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`school_id`, `user_id`),
    KEY `idx_school_users_user` (`user_id`),
    CONSTRAINT `fk_school_users_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_school_users_user`   FOREIGN KEY (`user_id`)   REFERENCES `users` (`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  4. SUSCRIPCIONES
-- =====================================================================

-- IMPORTANTE: aquí NO se almacenan datos de tarjetas. Solo la referencia
-- que devuelve la pasarela (Wompi / Mercado Pago / PayU / Stripe) para
-- poder controlar la vigencia de la suscripción.
CREATE TABLE IF NOT EXISTS `subscriptions` (
    `id`                INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`           INT UNSIGNED NOT NULL,
    `plan_id`           INT UNSIGNED NOT NULL,
    `school_id`         INT UNSIGNED NULL COMMENT 'Solo para suscripciones institucionales',
    `billing_cycle`     ENUM('monthly','yearly') NOT NULL DEFAULT 'yearly',
    `amount_cop`        INT UNSIGNED NOT NULL DEFAULT 0 COMMENT 'Monto realmente cobrado, congelado al momento de la compra',
    `status`            ENUM('pending','active','expired','cancelled') NOT NULL DEFAULT 'pending',
    `starts_at`         DATETIME NULL,
    `expires_at`        DATETIME NULL COMMENT 'Al vencer, el usuario regresa automáticamente al acceso Free',
    `payment_provider`  VARCHAR(40)  NULL COMMENT 'wompi | mercadopago | payu | stripe | manual',
    `payment_reference` VARCHAR(120) NULL COMMENT 'ID de transacción de la pasarela. NUNCA datos de tarjeta',
    `created_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`        DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_subs_vigencia` (`user_id`, `status`, `expires_at`),
    KEY `idx_subs_plan` (`plan_id`),
    KEY `idx_subs_school` (`school_id`),
    CONSTRAINT `fk_subs_user`   FOREIGN KEY (`user_id`)   REFERENCES `users` (`id`)   ON DELETE CASCADE,
    CONSTRAINT `fk_subs_plan`   FOREIGN KEY (`plan_id`)   REFERENCES `plans` (`id`),
    CONSTRAINT `fk_subs_school` FOREIGN KEY (`school_id`) REFERENCES `schools` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  5. TAXONOMÍA DEL CATÁLOGO
-- =====================================================================

-- Las categorías se administran desde el panel, nunca se escriben en HTML.
CREATE TABLE IF NOT EXISTS `categories` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`        VARCHAR(60)  NOT NULL,
    `name`        VARCHAR(120) NOT NULL,
    `tagline`     VARCHAR(160) NULL COMMENT 'Bajada corta que se muestra al filtrar por la categoría',
    `icon`        VARCHAR(16)  NULL COMMENT 'Emoji representativo',
    `color`       VARCHAR(20)  NULL COMMENT 'Color hex de acento',
    `sort_order`  SMALLINT     NOT NULL DEFAULT 0,
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_categories_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Bloques dentro de una categoría.
--
-- Una categoría como «Aventura de las Letras» tiene casi 40 actividades:
-- mostrarlas en una sola lista no ayuda a nadie. Los bloques las agrupan
-- por afinidad — Bosque de Vocales, Reino de las Letras, otros mundos —
-- igual que hacía el sitio anterior con sus submundos.
--
-- Es una agrupación de presentación, no un nivel de permisos: el acceso
-- se sigue decidiendo actividad por actividad.
CREATE TABLE IF NOT EXISTS `collections` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `category_id` INT UNSIGNED NULL COMMENT 'Categoría a la que pertenece el bloque',
    `slug`        VARCHAR(60)  NOT NULL,
    `name`        VARCHAR(120) NOT NULL,
    `description` VARCHAR(300) NULL,
    `icon`        VARCHAR(16)  NULL,
    `sort_order`  SMALLINT     NOT NULL DEFAULT 0,
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_collections_slug` (`slug`),
    KEY `idx_collections_categoria` (`category_id`, `sort_order`),
    CONSTRAINT `fk_collections_category` FOREIGN KEY (`category_id`)
        REFERENCES `categories` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Niveles / edades. Es un filtro DENTRO de cada categoría, no una
-- categoría en sí misma (ver docs/reorganizacion-home.md).
CREATE TABLE IF NOT EXISTS `levels` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`       VARCHAR(60)  NOT NULL,
    `name`       VARCHAR(120) NOT NULL,
    `min_age`    TINYINT UNSIGNED NULL,
    `max_age`    TINYINT UNSIGNED NULL,
    `sort_order` SMALLINT     NOT NULL DEFAULT 0,
    `is_active`  TINYINT(1)   NOT NULL DEFAULT 1,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_levels_slug` (`slug`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  6. ACTIVIDADES
-- =====================================================================

-- La actividad es una ENTIDAD, independiente del diseño. Agregar una
-- actividad nueva es insertar una fila, no crear un archivo .php.
--
-- `engine` decide qué motor la dibuja:
--     letra       · motor de lectoescritura (una fila por letra, mismo motor)
--     vocal       · motor de vocales
--     mundo       · motor genérico de estaciones
--     legacy_html · actividad todavía no portada; se sirve el HTML original
--
-- `access_type` define el modelo freemium:
--     free    · toda la actividad es gratuita
--     partial · las primeras `free_stations` estaciones son gratuitas
--     premium · requiere suscripción para entrar
CREATE TABLE IF NOT EXISTS `activities` (
    `id`               INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`             VARCHAR(140) NOT NULL,
    `title`            VARCHAR(160) NOT NULL,
    `description`      VARCHAR(400) NULL,
    `instructions`     TEXT         NULL,
    `objective`        VARCHAR(400) NULL COMMENT 'Objetivo pedagógico',
    `category_id`      INT UNSIGNED NULL,
    `collection_id`    INT UNSIGNED NULL COMMENT 'Bloque dentro de la categoría; opcional',
    `level_id`         INT UNSIGNED NULL,
    `engine`           VARCHAR(40)  NOT NULL DEFAULT 'legacy_html',
    `activity_type`    VARCHAR(40)  NULL COMMENT 'juego | reto | practica | cuento | paquete',
    `content`          JSON         NULL COMMENT 'Datos propios del motor (la letra, sus palabras, etc.)',
    `legacy_file`      VARCHAR(160) NULL COMMENT 'Archivo HTML original mientras la actividad se porta al motor',
    `icon`             VARCHAR(16)  NULL,
    `thumbnail`        VARCHAR(200) NULL,
    `duration_minutes` SMALLINT UNSIGNED NULL,
    `access_type`      ENUM('free','partial','premium') NOT NULL DEFAULT 'partial',
    `free_stations`    TINYINT UNSIGNED NOT NULL DEFAULT 0
                       COMMENT 'Cuántas estaciones iniciales son gratuitas cuando access_type = partial',
    `status`           ENUM('draft','published','archived') NOT NULL DEFAULT 'draft',
    `is_featured`      TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Aparece en "Actividades destacadas"',
    `published_at`     DATETIME     NULL COMMENT 'Alimenta la sección "Nuevas actividades"',
    `created_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`       DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_activities_slug` (`slug`),
    KEY `idx_act_publicadas` (`status`, `published_at`),
    KEY `idx_act_category` (`category_id`),
    KEY `idx_act_collection` (`collection_id`),
    KEY `idx_act_level` (`level_id`),
    KEY `idx_act_access` (`access_type`),
    KEY `idx_act_featured` (`is_featured`),
    CONSTRAINT `fk_act_category`   FOREIGN KEY (`category_id`)   REFERENCES `categories` (`id`)  ON DELETE SET NULL,
    CONSTRAINT `fk_act_collection` FOREIGN KEY (`collection_id`) REFERENCES `collections` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_act_level`      FOREIGN KEY (`level_id`)      REFERENCES `levels` (`id`)      ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Estaciones (minijuegos) dentro de una actividad.
--
-- ESTA ES LA TABLA CLAVE DEL MODELO 30%: el usuario Free entra a todas
-- las actividades pero solo juega las primeras estaciones. El servidor
-- decide estación por estación, y el `config` de una estación bloqueada
-- NUNCA se envía al navegador.
CREATE TABLE IF NOT EXISTS `activity_stations` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `activity_id` INT UNSIGNED NOT NULL,
    `position`    SMALLINT UNSIGNED NOT NULL COMMENT 'Orden dentro de la actividad, empezando en 1',
    `title`       VARCHAR(160) NOT NULL,
    `description` VARCHAR(300) NULL,
    `icon`        VARCHAR(16)  NULL,
    `game_type`   VARCHAR(40)  NOT NULL COMMENT 'seleccion_imagenes | puzle_silabas | teclado | memoria | sopa_letras | ...',
    `config`      JSON         NULL COMMENT 'Datos del minijuego. Contenido protegido: no se sirve si la estación está bloqueada',
    `is_free`     TINYINT(1)   NOT NULL DEFAULT 0 COMMENT 'Excepción manual: fuerza esta estación como gratuita',
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_station_posicion` (`activity_id`, `position`),
    CONSTRAINT `fk_station_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Etiquetas transversales.
--
-- La categoría dice DE QUÉ trata una actividad y es una sola. La etiqueta
-- dice QUÉ PONE EN JUEGO, y son varias: «El misterio del mapa perdido» es
-- de Ciencias Sociales, pero entrena lógica y lectura, y es un reto.
--
-- Sin esto, la única forma de encontrar algo en un catálogo de cientos de
-- actividades sería saber de antemano en qué materia lo guardamos. Con
-- esto, una misma actividad aparece buscando «lógica», «reto» o su
-- materia, sin duplicarla.
--
-- `kind` separa los tres ejes para poder mostrarlos en filas distintas y
-- no mezclar «geografía» con «cuento» en la misma lista de filtros.
CREATE TABLE IF NOT EXISTS `tags` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`       VARCHAR(60)  NOT NULL,
    `name`       VARCHAR(120) NOT NULL,
    `kind`       ENUM('habilidad','tipo','tema','apoyo') NOT NULL DEFAULT 'habilidad' COMMENT 'apoyo = qué obstáculo le quita a quien lo necesita',
    `icon`       VARCHAR(16)  NULL,
    `sort_order` SMALLINT     NOT NULL DEFAULT 0,
    `is_active`  TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_tags_slug` (`slug`),
    KEY `idx_tags_kind` (`kind`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `activity_tags` (
    `activity_id` INT UNSIGNED NOT NULL,
    `tag_id`      INT UNSIGNED NOT NULL,
    PRIMARY KEY (`activity_id`, `tag_id`),
    KEY `idx_activity_tags_tag` (`tag_id`),
    CONSTRAINT `fk_at_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_at_tag`      FOREIGN KEY (`tag_id`)      REFERENCES `tags` (`id`)       ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  7. ÁREA ESCUELA (arquitectura preparada · Fase 6)
--
--  Va antes del progreso porque `activity_progress` referencia cursos:
--  así cada clave foránea se declara dentro de su propia tabla y el
--  script se puede volver a ejecutar sin ALTER TABLE posteriores.
-- =====================================================================

CREATE TABLE IF NOT EXISTS `courses` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `school_id`  INT UNSIGNED NULL COMMENT 'NULL = curso de un docente independiente, sin colegio detrás',
    `teacher_id` INT UNSIGNED NULL COMMENT 'Docente responsable del curso',
    `name`       VARCHAR(160) NOT NULL COMMENT 'Ej: Matemáticas 5°',
    `grade`      VARCHAR(40)  NULL,
    `year`       SMALLINT UNSIGNED NULL,
    `status`     ENUM('active','archived') NOT NULL DEFAULT 'active',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_courses_school` (`school_id`, `status`),
    KEY `idx_courses_teacher` (`teacher_id`),
    CONSTRAINT `fk_courses_school`  FOREIGN KEY (`school_id`)  REFERENCES `schools` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_courses_teacher` FOREIGN KEY (`teacher_id`) REFERENCES `users` (`id`)   ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `course_students` (
    `course_id`  INT UNSIGNED NOT NULL,
    `user_id`    INT UNSIGNED NOT NULL,
    `enrolled_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`course_id`, `user_id`),
    KEY `idx_course_students_user` (`user_id`),
    CONSTRAINT `fk_cs_course` FOREIGN KEY (`course_id`) REFERENCES `courses` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_cs_user`   FOREIGN KEY (`user_id`)   REFERENCES `users` (`id`)   ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Actividades asignadas a un curso, en un orden (ruta de aprendizaje).
CREATE TABLE IF NOT EXISTS `course_activities` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `course_id`   INT UNSIGNED NOT NULL,
    `activity_id` INT UNSIGNED NOT NULL,
    `sort_order`  SMALLINT NOT NULL DEFAULT 0 COMMENT 'Define la secuencia o ruta',
    `due_date`    DATE NULL,
    `assigned_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_course_activity` (`course_id`, `activity_id`),
    KEY `idx_ca_activity` (`activity_id`),
    CONSTRAINT `fk_ca_course`   FOREIGN KEY (`course_id`)   REFERENCES `courses` (`id`)    ON DELETE CASCADE,
    CONSTRAINT `fk_ca_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- =====================================================================
--  8. PROGRESO Y FAVORITOS
-- =====================================================================

-- Una fila por (usuario, estación). El progreso de la actividad completa
-- se calcula agregando sus estaciones: no se duplica información.
-- `course_id` permite distinguir el uso escolar del uso personal sin
-- necesitar una segunda tabla de progreso.
CREATE TABLE IF NOT EXISTS `activity_progress` (
    `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`            INT UNSIGNED NOT NULL,
    `activity_id`        INT UNSIGNED NOT NULL,
    `station_id`         INT UNSIGNED NOT NULL,
    `course_id`          INT UNSIGNED NULL COMMENT 'Contexto escolar, si la actividad fue asignada por un curso',
    `status`             ENUM('in_progress','completed') NOT NULL DEFAULT 'in_progress',
    `percent`            TINYINT UNSIGNED NOT NULL DEFAULT 0,
    `score`              INT UNSIGNED NOT NULL DEFAULT 0,
    `stars`              SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `coins`              SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `attempts`           SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `time_spent_seconds` INT UNSIGNED NOT NULL DEFAULT 0,
    `started_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `completed_at`       DATETIME NULL,
    `updated_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_progress` (`user_id`, `station_id`),
    KEY `idx_progress_actividad` (`activity_id`, `status`),
    KEY `idx_progress_curso` (`course_id`),
    CONSTRAINT `fk_prog_user`     FOREIGN KEY (`user_id`)     REFERENCES `users` (`id`)             ON DELETE CASCADE,
    CONSTRAINT `fk_prog_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`)        ON DELETE CASCADE,
    CONSTRAINT `fk_prog_station`  FOREIGN KEY (`station_id`)  REFERENCES `activity_stations` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_prog_course`   FOREIGN KEY (`course_id`)   REFERENCES `courses` (`id`)           ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


CREATE TABLE IF NOT EXISTS `favorites` (
    `user_id`     INT UNSIGNED NOT NULL,
    `activity_id` INT UNSIGNED NOT NULL,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `activity_id`),
    KEY `idx_fav_activity` (`activity_id`),
    CONSTRAINT `fk_fav_user`     FOREIGN KEY (`user_id`)     REFERENCES `users` (`id`)      ON DELETE CASCADE,
    CONSTRAINT `fk_fav_activity` FOREIGN KEY (`activity_id`) REFERENCES `activities` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  8b. GAMIFICACIÓN
--
--  Las monedas y las estrellas YA se ganaban: `activity_progress` las
--  guarda desde el primer día, y con `GREATEST` al actualizar, así que
--  repetir una estación fácil no las multiplica. Lo que faltaba era
--  gastarlas y verlas.
--
--  NO existe una columna «saldo». El saldo se calcula:
--      ganadas (SUM de activity_progress.coins) − gastadas (esta tabla)
--  Un número guardado se desincroniza en cuanto algo falla a mitad; uno
--  calculado no puede mentir, y además deja auditable en qué se gastó.
-- =====================================================================

-- Lo que se puede canjear: personajes y accesorios.
CREATE TABLE IF NOT EXISTS `shop_items` (
    `id`          INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `slug`        VARCHAR(60)  NOT NULL,
    `name`        VARCHAR(120) NOT NULL,
    `kind`        ENUM('avatar','accesorio') NOT NULL DEFAULT 'avatar',
    `emoji`       VARCHAR(16)  NOT NULL,
    `price_coins` SMALLINT UNSIGNED NOT NULL DEFAULT 0
                  COMMENT '0 = se tiene desde el principio, sin pagar',
    `needs_streak` SMALLINT UNSIGNED NOT NULL DEFAULT 0
                  COMMENT 'Días de racha necesarios; 0 = no pide racha',
    `description` VARCHAR(200) NULL,
    `sort_order`  SMALLINT     NOT NULL DEFAULT 0,
    `is_active`   TINYINT(1)   NOT NULL DEFAULT 1,
    `created_at`  DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_shop_slug` (`slug`),
    KEY `idx_shop_kind` (`kind`, `sort_order`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Lo que cada usuario ya compró. Es también el libro de gastos: el
-- precio se congela al comprar, para que bajar el precio de algo no
-- reescriba lo que alguien pagó en su momento.
CREATE TABLE IF NOT EXISTS `user_items` (
    `user_id`    INT UNSIGNED NOT NULL,
    `item_id`    INT UNSIGNED NOT NULL,
    `paid_coins` SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`user_id`, `item_id`),
    KEY `idx_user_items_item` (`item_id`),
    CONSTRAINT `fk_ui_user` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`)       ON DELETE CASCADE,
    CONSTRAINT `fk_ui_item` FOREIGN KEY (`item_id`) REFERENCES `shop_items` (`id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- Monedas gastadas en ayudas dentro de una actividad.
--
-- `user_items` no sirve para esto: su clave primaria es (usuario,
-- artículo), así que solo admite comprar algo UNA vez. Una pista se paga
-- cada vez que se pide.
--
-- Se guarda la estación para poder responder algo útil más adelante: si
-- una misma estación acumula pistas de muchos niños distintos, el
-- problema no es de los niños, es del ejercicio.
CREATE TABLE IF NOT EXISTS `coin_spends` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `user_id`    INT UNSIGNED NOT NULL,
    `kind`       ENUM('pista','saltar') NOT NULL,
    `station_id` INT UNSIGNED NULL,
    `coins`      SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_gasto_usuario` (`user_id`, `created_at`),
    KEY `idx_gasto_estacion` (`station_id`, `kind`),
    CONSTRAINT `fk_gasto_user`    FOREIGN KEY (`user_id`)    REFERENCES `users` (`id`)             ON DELETE CASCADE,
    CONSTRAINT `fk_gasto_station` FOREIGN KEY (`station_id`) REFERENCES `activity_stations` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  9. CONFIGURACIÓN GENERAL
-- =====================================================================

-- Ajustes editables desde el panel sin tocar código
-- (ej. cuántas estaciones libres por defecto, modo global del catálogo).
CREATE TABLE IF NOT EXISTS `settings` (
    `key`        VARCHAR(80)  NOT NULL,
    `value`      TEXT         NULL,
    `label`      VARCHAR(200) NULL COMMENT 'Descripción legible para el panel de administración',
    `updated_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`key`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;


-- =====================================================================
--  10. COBROS (Fase 4)
-- =====================================================================
--
-- `payments` NO sustituye a `subscriptions`: responden preguntas
-- distintas y confundirlas es el error clásico.
--
--   · `subscriptions` responde «¿hasta cuándo tiene acceso?». Es el
--     permiso, y hay como mucho uno vigente por usuario.
--   · `payments` responde «¿quién pagó qué, cuándo y cómo?». Es el
--     libro contable, con una fila por INTENTO — incluidos los
--     rechazados y los devueltos, que son la mitad de un informe.
--
-- Una renovación es un pago nuevo sobre la misma suscripción. Si el
-- dinero viviera dentro de `subscriptions`, renovar borraría lo cobrado
-- el año anterior.
--
-- Aquí tampoco se guardan datos de tarjetas: solo la referencia que
-- devuelve la pasarela.
CREATE TABLE IF NOT EXISTS `payments` (
    `id`                 INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `reference`          VARCHAR(40)  NOT NULL COMMENT 'Referencia propia, la que ve el cliente: AEL-2026-084512',
    `user_id`            INT UNSIGNED NOT NULL,
    `plan_id`            INT UNSIGNED NOT NULL,
    `subscription_id`    INT UNSIGNED NULL COMMENT 'Se rellena al confirmar: la suscripción que este pago pagó',
    `billing_cycle`      ENUM('monthly','yearly') NOT NULL DEFAULT 'yearly',
    `amount_cop`         INT UNSIGNED NOT NULL COMMENT 'Congelado al crear el pago; no se recalcula si cambia el precio del plan',
    `status`             ENUM('pending','confirmed','rejected','refunded','cancelled') NOT NULL DEFAULT 'pending',
    `method`             VARCHAR(30)  NOT NULL DEFAULT 'transferencia' COMMENT 'transferencia | nequi | efectivo | pasarela | cortesia',
    `provider`           VARCHAR(40)  NULL COMMENT 'wompi | mercadopago | payu | stripe | manual',
    `provider_reference` VARCHAR(120) NULL COMMENT 'Id de transacción de la pasarela. NUNCA datos de tarjeta',
    `provider_status`    VARCHAR(40)  NULL COMMENT 'Estado crudo tal como lo devolvió la pasarela',
    `payer_name`         VARCHAR(120) NULL COMMENT 'Datos de facturación; pueden diferir de los de la cuenta',
    `payer_email`        VARCHAR(190) NULL,
    `payer_document`     VARCHAR(40)  NULL COMMENT 'Cédula o NIT para la factura',
    `proof_reference`    VARCHAR(120) NULL COMMENT 'Número de comprobante que escribe el cliente al transferir',
    `notes`              TEXT         NULL COMMENT 'Nota interna del administrador',
    `confirmed_by`       INT UNSIGNED NULL COMMENT 'Administrador que confirmó; NULL si lo confirmó la pasarela',
    `confirmed_at`       DATETIME     NULL,
    `created_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    `updated_at`         DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    UNIQUE KEY `uk_payments_reference` (`reference`),
    UNIQUE KEY `uk_payments_provider_ref` (`provider`, `provider_reference`),
    KEY `idx_payments_usuario` (`user_id`, `status`),
    KEY `idx_payments_estado`  (`status`, `created_at`),
    KEY `idx_payments_creado`  (`created_at`),
    CONSTRAINT `fk_pay_user`  FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_pay_plan`  FOREIGN KEY (`plan_id`) REFERENCES `plans` (`id`),
    CONSTRAINT `fk_pay_sub`   FOREIGN KEY (`subscription_id`) REFERENCES `subscriptions` (`id`) ON DELETE SET NULL,
    CONSTRAINT `fk_pay_admin` FOREIGN KEY (`confirmed_by`) REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Cada cambio de estado de un pago, con su motivo y su autor.
--
-- Con dinero de por medio, la pregunta que llega por teléfono nunca es
-- «¿está activo?» sino «¿quién dijo que sí y cuándo?». Un estado
-- guardado responde la primera; solo esta tabla responde la segunda.
CREATE TABLE IF NOT EXISTS `payment_events` (
    `id`         INT UNSIGNED NOT NULL AUTO_INCREMENT,
    `payment_id` INT UNSIGNED NOT NULL,
    `type`       VARCHAR(30)  NOT NULL COMMENT 'creado | confirmado | rechazado | reembolsado | anulado | editado | webhook | nota',
    `detail`     TEXT         NULL,
    `actor_id`   INT UNSIGNED NULL COMMENT 'Quién lo hizo. NULL = la pasarela o el propio sistema',
    `created_at` DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    PRIMARY KEY (`id`),
    KEY `idx_evt_pago` (`payment_id`, `created_at`),
    CONSTRAINT `fk_evt_pago`  FOREIGN KEY (`payment_id`) REFERENCES `payments` (`id`) ON DELETE CASCADE,
    CONSTRAINT `fk_evt_actor` FOREIGN KEY (`actor_id`)   REFERENCES `users` (`id`) ON DELETE SET NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
