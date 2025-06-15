-- ===============================================
-- 1. CREATE TABLE statements
-- ===============================================
DROP TABLE IF EXISTS operator;
CREATE TABLE operator (
    id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    label           VARCHAR(255) NOT NULL,
    email           VARCHAR(255) NOT NULL,
    password_hash   VARCHAR(255) NOT NULL,
    
    status          ENUM('active','inactive','suspended') NOT NULL DEFAULT 'active',
    last_login_at   DATETIME    NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS taxonomy;
CREATE TABLE taxonomy (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(TRIM(BOTH '-' FROM REGEXP_REPLACE(label, '[^a-z0-9]+', '-')), '-{2,}', '-'))) STORED,
    parent_id INT UNSIGNED NULL,
    label VARCHAR(50) NOT NULL,
    color CHAR(7) NULL,
    sort_order SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS trainer;
CREATE TABLE trainer (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(TRIM(BOTH '-' FROM REGEXP_REPLACE(label, '[^a-z0-9]+', '-')), '-{2,}', '-'))) STORED,
    label VARCHAR(100) NOT NULL,
    bio TEXT NULL,
    avatar VARCHAR(255) NULL,
    email VARCHAR(100) NULL,
    hire_date DATE NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS article;
CREATE TABLE article (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(TRIM(BOTH '-' FROM REGEXP_REPLACE(label, '[^a-z0-9]+', '-')), '-{2,}', '-'))) STORED,
    label VARCHAR(200) NOT NULL,
    summary TEXT NULL,
    content LONGTEXT NOT NULL,
    category ENUM('technology', 'business', 'design', 'marketing', 'development', 'management') NULL,
    reading_time SMALLINT UNSIGNED NULL,
    avatar VARCHAR(255) NULL,
    featured BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS event;
CREATE TABLE event (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(TRIM(BOTH '-' FROM REGEXP_REPLACE(label, '[^a-z0-9]+', '-')), '-{2,}', '-'))) STORED,
    label VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    category ENUM('webinar', 'workshop', 'conference', 'masterclass', 'bootcamp') NOT NULL,
    event_date DATETIME NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8,2) NULL,
    places_max SMALLINT UNSIGNED NULL,
    avatar VARCHAR(255) NULL,
    speaker VARCHAR(100) NULL,
    location VARCHAR(200) NULL,
    online BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS training;
CREATE TABLE training (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(TRIM(BOTH '-' FROM REGEXP_REPLACE(label, '[^a-z0-9]+', '-')), '-{2,}', '-'))) STORED,
    label VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    level ENUM('beginner', 'intermediate', 'advanced', 'expert') NOT NULL,
    duration_days SMALLINT UNSIGNED NOT NULL,
    duration_hours SMALLINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8,2) NOT NULL,
    start_date DATE NOT NULL,
    places_max SMALLINT UNSIGNED NULL,
    objectives TEXT NULL,
    prerequisites TEXT NULL,
    avatar VARCHAR(255) NULL,
    trainer_id INT UNSIGNED NULL,
    certification BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

DROP TABLE IF EXISTS training_program;
CREATE TABLE training_program (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    slug VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(TRIM(BOTH '-' FROM REGEXP_REPLACE(label, '[^a-z0-9]+', '-')), '-{2,}', '-'))) STORED,
    training_id INT UNSIGNED NOT NULL,
    day_number SMALLINT UNSIGNED NOT NULL,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    label VARCHAR(150) NOT NULL,
    description TEXT NULL,
    objectives TEXT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS contact_request;
CREATE TABLE contact_request (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    company VARCHAR(100) NULL,
    subject ENUM('general', 'training', 'support', 'partnership', 'billing', 'technical') NOT NULL,
    message TEXT NOT NULL,
    consent BOOLEAN NOT NULL,
    status ENUM('pending', 'processing', 'resolved', 'closed') NOT NULL DEFAULT 'pending'
) ENGINE=InnoDB;

DROP TABLE IF EXISTS event_booking;
CREATE TABLE event_booking (
    event_id INT UNSIGNED NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'pending',
    PRIMARY KEY (event_id, participant_email)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS training_booking;
CREATE TABLE training_booking (
    training_id INT UNSIGNED NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    status ENUM('pending', 'confirmed', 'cancelled', 'completed') NOT NULL DEFAULT 'pending',
    PRIMARY KEY (training_id, participant_email)
) ENGINE=InnoDB;

DROP TABLE IF EXISTS newsletter_subscription;
CREATE TABLE newsletter_subscription (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    email VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

DROP TABLE IF EXISTS operator_session;
CREATE TABLE operator_session (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
    ip_address VARBINARY(16) NOT NULL,
    user_agent TEXT NULL,
    last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;

-- ===============================================
-- 2. TIMESTAMP COLUMNS
-- ===============================================
ALTER TABLE operator
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;


ALTER TABLE taxonomy
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE trainer
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE article
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE event
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE training
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE training_program
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN enabled_at TIMESTAMP NULL DEFAULT NULL,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE contact_request
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE event_booking
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE training_booking
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE newsletter_subscription
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

ALTER TABLE operator_session
    ADD COLUMN created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN updated_at TIMESTAMP NULL DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN revoked_at TIMESTAMP NULL DEFAULT NULL;

-- ===============================================
-- 3. CONSTRAINTS & KEYS
-- ===============================================
ALTER TABLE operator
    ADD CONSTRAINT operator_uk_email    UNIQUE (email),
    ADD INDEX idx_operator_status (status);

ALTER TABLE taxonomy
  ADD CONSTRAINT taxonomy_fk_parent_exists
      FOREIGN KEY (parent_id) REFERENCES taxonomy(id) ON DELETE CASCADE,
  ADD CONSTRAINT taxonomy_uk_slug_per_parent
      UNIQUE (parent_id, slug);

ALTER TABLE trainer
  ADD CONSTRAINT trainer_uk_email_unique
      UNIQUE (email);

ALTER TABLE article
  ADD CONSTRAINT article_uk_slug_unique
      UNIQUE (slug);

ALTER TABLE event
  ADD CONSTRAINT event_uk_slug_unique
      UNIQUE (slug),
    ADD CONSTRAINT event_ck_price_not_negative
        CHECK (price_ht IS NULL OR price_ht >= 0);

ALTER TABLE training
    ADD CONSTRAINT training_fk_trainer_exists
        FOREIGN KEY (trainer_id) REFERENCES trainer(id) ON DELETE SET NULL,
    ADD CONSTRAINT training_uk_slug_unique
        UNIQUE (slug),
    ADD CONSTRAINT training_ck_price_not_negative
        CHECK (price_ht >= 0);

ALTER TABLE training_program
    ADD CONSTRAINT training_program_fk_training_exists
        FOREIGN KEY (training_id) REFERENCES training(id) ON DELETE CASCADE,
    ADD CONSTRAINT training_program_uk_time_slot_unique
        UNIQUE (training_id, day_number, time_start),
    ADD CONSTRAINT training_program_ck_end_after_start
        CHECK (time_end > time_start),
    ADD CONSTRAINT training_program_ck_day_positive
        CHECK (day_number > 0);

ALTER TABLE event_booking
    ADD CONSTRAINT event_booking_fk_event_exists
        FOREIGN KEY (event_id) REFERENCES event(id) ON DELETE RESTRICT;

ALTER TABLE training_booking
    ADD CONSTRAINT training_booking_fk_training_exists
        FOREIGN KEY (training_id) REFERENCES training(id) ON DELETE RESTRICT;

ALTER TABLE newsletter_subscription
    ADD CONSTRAINT newsletter_subscription_uk_email_unique
        UNIQUE (email);

-- ===============================================
-- 4. INDEXES
-- ===============================================

ALTER TABLE taxonomy
    ADD INDEX idx_taxonomy_parent (parent_id),
    ADD INDEX idx_taxonomy_revoked_at (revoked_at);

ALTER TABLE trainer
    ADD INDEX idx_trainer_revoked_at (revoked_at);

ALTER TABLE article
    ADD INDEX idx_article_published (enabled_at),
    ADD INDEX idx_article_featured (featured),
    ADD INDEX idx_article_category (category),
    ADD FULLTEXT KEY idx_article_search (label, summary, content);

ALTER TABLE event
    ADD INDEX idx_event_revoked_at_date (revoked_at, event_date),
    ADD INDEX idx_event_category (category);

ALTER TABLE training
    ADD INDEX idx_training_level (level),
    ADD INDEX idx_training_date (start_date),
    ADD INDEX idx_training_revoked_at (revoked_at);

ALTER TABLE training_program
    ADD INDEX idx_training_program_day (training_id, day_number);

ALTER TABLE contact_request
    ADD INDEX idx_contact_status (status),
    ADD INDEX idx_contact_created_at (created_at),
    ADD INDEX idx_contact_subject (subject);

ALTER TABLE newsletter_subscription
    ADD INDEX idx_newsletter_revoked_at (revoked_at);

ALTER TABLE operator_session
    ADD INDEX idx_operator_session_activity (last_activity);