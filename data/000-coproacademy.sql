-- ===============================================
-- 1. CREATE TABLE statements
-- ===============================================

CREATE TABLE taxonomy (
    parent_id INT UNSIGNED NULL,
    label VARCHAR(50) NOT NULL,
    color CHAR(7) NULL,
    sort_order SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB;

CREATE TABLE trainer (
    label VARCHAR(100) NOT NULL,
    bio TEXT NULL,
    avatar VARCHAR(255) NULL,
    email VARCHAR(100) NULL,
    hire_date DATE NULL
) ENGINE=InnoDB;

CREATE TABLE article (
    label VARCHAR(200) NOT NULL,
    summary TEXT NULL,
    content LONGTEXT NOT NULL,
    taxonomy_id INT UNSIGNED NULL,
    reading_time SMALLINT UNSIGNED NULL,
    avatar VARCHAR(255) NULL,
    featured BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

CREATE TABLE event (
    label VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    taxonomy_id INT UNSIGNED NULL,
    event_type_id INT UNSIGNED NOT NULL,
    event_date DATETIME NOT NULL,
    duration_minutes SMALLINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8,2) NULL,
    places_max SMALLINT UNSIGNED NULL,
    places_taken SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    avatar VARCHAR(255) NULL,
    speaker VARCHAR(100) NULL,
    location VARCHAR(200) NULL,
    online BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

CREATE TABLE training (
    label VARCHAR(200) NOT NULL,
    description TEXT NOT NULL,
    training_level_id INT UNSIGNED NOT NULL,
    duration_days SMALLINT UNSIGNED NOT NULL,
    duration_hours SMALLINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8,2) NOT NULL,
    start_date DATE NOT NULL,
    places_max SMALLINT UNSIGNED NULL,
    places_taken SMALLINT UNSIGNED NOT NULL DEFAULT 0,
    objectives TEXT NULL,
    prerequisites TEXT NULL,
    avatar VARCHAR(255) NULL,
    trainer_id INT UNSIGNED NULL,
    certification BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

CREATE TABLE training_program (
    training_id INT UNSIGNED NOT NULL,
    day_number SMALLINT UNSIGNED NOT NULL,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    label VARCHAR(150) NOT NULL,
    description TEXT NULL,
    objectives TEXT NULL
) ENGINE=InnoDB;


CREATE TABLE contact_request (
    label VARCHAR(100) NOT NULL,
    email VARCHAR(100) NOT NULL,
    phone VARCHAR(20) NULL,
    company VARCHAR(100) NULL,
    contact_subject_id INT UNSIGNED NOT NULL,
    message TEXT NOT NULL,
    consent BOOLEAN NOT NULL,
    request_status_id INT UNSIGNED NOT NULL
) ENGINE=InnoDB;

CREATE TABLE event_booking (
    event_id INT UNSIGNED NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    booking_status_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (event_id, participant_email)
) ENGINE=InnoDB;


CREATE TABLE training_booking (
    training_id INT UNSIGNED NOT NULL,
    participant_email VARCHAR(100) NOT NULL,
    booking_status_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (training_id, participant_email)
) ENGINE=InnoDB;


CREATE TABLE newsletter_subscription (
    email VARCHAR(100) NOT NULL
) ENGINE=InnoDB;

CREATE TABLE user_session (
    ip_address VARBINARY(16) NOT NULL,
    user_agent TEXT NULL,
    last_activity TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


-- ===============================================
-- 2. AUTO COLUMNS (id, slug, new_on, set_on, dis_on, get_on)
-- ===============================================

ALTER TABLE taxonomy
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN slug      VARCHAR(255)     GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(label, '[^a-z0-9]+', '-'))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE trainer
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN slug      VARCHAR(255)     GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(label, '[^a-z0-9]+', '-'))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE article
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN slug      VARCHAR(255)     GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(label, '[^a-z0-9]+', '-'))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE event
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN slug      VARCHAR(255)     GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(label, '[^a-z0-9]+', '-'))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE training
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN slug      VARCHAR(255)     GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(label, '[^a-z0-9]+', '-'))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE training_program
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN slug      VARCHAR(255) GENERATED ALWAYS AS (LOWER(REGEXP_REPLACE(label, '[^a-z0-9]+', '-'))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE contact_request
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE event_booking
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE training_booking
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE newsletter_subscription
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;

ALTER TABLE user_session
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY FIRST,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL        DEFAULT NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL        DEFAULT NULL;


-- ===============================================
-- 3. CONSTRAINTS & KEYS
-- ===============================================

ALTER TABLE taxonomy
  ADD CONSTRAINT taxonomy_fk_parent_exists
      FOREIGN KEY (parent_id) REFERENCES taxonomy(id) ON DELETE CASCADE,
  ADD CONSTRAINT taxonomy_uk_slug_per_parent
      UNIQUE (parent_id, slug);

ALTER TABLE trainer
  ADD CONSTRAINT trainer_uk_email_unique
      UNIQUE (email);

ALTER TABLE article
  ADD CONSTRAINT article_fk_taxonomy_exists
      FOREIGN KEY (taxonomy_id) REFERENCES taxonomy(id) ON DELETE SET NULL,
  ADD CONSTRAINT article_uk_slug_unique
      UNIQUE (slug);

ALTER TABLE event
  ADD CONSTRAINT event_fk_taxonomy_exists
      FOREIGN KEY (taxonomy_id) REFERENCES taxonomy(id) ON DELETE SET NULL,
  ADD CONSTRAINT event_fk_event_type_exists
      FOREIGN KEY (event_type_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
  ADD CONSTRAINT event_uk_slug_unique
      UNIQUE (slug),
    ADD CONSTRAINT event_ck_price_not_negative
        CHECK (price_ht IS NULL OR price_ht >= 0);

ALTER TABLE training
    ADD CONSTRAINT training_fk_training_level_exists
        FOREIGN KEY (training_level_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
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
        FOREIGN KEY (event_id) REFERENCES event(id) ON DELETE RESTRICT,
    ADD CONSTRAINT event_booking_fk_status_exists
        FOREIGN KEY (booking_status_id) REFERENCES taxonomy(id) ON DELETE RESTRICT;

ALTER TABLE training_booking
    ADD CONSTRAINT training_booking_fk_training_exists
        FOREIGN KEY (training_id) REFERENCES training(id) ON DELETE RESTRICT,
    ADD CONSTRAINT training_booking_fk_status_exists
        FOREIGN KEY (booking_status_id) REFERENCES taxonomy(id) ON DELETE RESTRICT;

ALTER TABLE contact_request
    ADD CONSTRAINT contact_request_fk_contact_subject_exists
        FOREIGN KEY (contact_subject_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
    ADD CONSTRAINT contact_request_fk_request_status_exists
        FOREIGN KEY (request_status_id) REFERENCES taxonomy(id) ON DELETE RESTRICT;

ALTER TABLE newsletter_subscription
    ADD CONSTRAINT newsletter_subscription_uk_email_unique
        UNIQUE (email);


-- ===============================================
-- 4. INDEXES
-- ===============================================

ALTER TABLE taxonomy
    ADD INDEX idx_taxonomy_parent (parent_id),
    ADD INDEX idx_taxonomy_dis_on (dis_on);

ALTER TABLE trainer
    ADD INDEX idx_trainer_dis_on (dis_on);

ALTER TABLE article
    ADD INDEX idx_article_published (get_on),
    ADD INDEX idx_article_featured (featured),
    ADD INDEX idx_article_active_taxonomy (dis_on, taxonomy_id),
    ADD FULLTEXT KEY idx_article_search (label, summary, content);

ALTER TABLE event
    ADD INDEX idx_event_type (event_type_id),
    ADD INDEX idx_event_dis_on_date (dis_on, event_date),
    ADD INDEX idx_event_active_taxonomy (dis_on, taxonomy_id);
ALTER TABLE training
    ADD INDEX idx_training_level (training_level_id),
    ADD INDEX idx_training_date (start_date),
    ADD INDEX idx_training_dis_on (dis_on);

ALTER TABLE training_program
    ADD INDEX idx_training_program_day (training_id, day_number);

ALTER TABLE contact_request
    ADD INDEX idx_contact_status (request_status_id),
    ADD INDEX idx_contact_new_on (new_on),
    ADD INDEX idx_contact_subject (contact_subject_id);

ALTER TABLE newsletter_subscription
    ADD INDEX idx_newsletter_dis_on (dis_on);

ALTER TABLE user_session
    ADD INDEX idx_user_session_activity (last_activity);
