-- ===============================================
-- 1. CREATE TABLE statements
-- ===============================================

CREATE TABLE taxonomy (
    parent_id INT UNSIGNED NULL,
    label VARCHAR(50) NOT NULL,
    color CHAR(7) NULL,
    sort_order SMALLINT UNSIGNED NOT NULL
) ENGINE=InnoDB;

CREATE TABLE taxonomy_content_type (
    taxonomy_id INT UNSIGNED NOT NULL,
    content_type_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (taxonomy_id, content_type_id)
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
    reading_time TINYINT UNSIGNED NULL,
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
    duration_days TINYINT UNSIGNED NOT NULL,
    duration_hours TINYINT UNSIGNED NOT NULL,
    price_ht DECIMAL(8,2) NOT NULL,
    start_date DATE NOT NULL,
    places_max TINYINT UNSIGNED NULL,
    places_taken TINYINT UNSIGNED NOT NULL DEFAULT 0,
    objectives TEXT NULL,
    prerequisites TEXT NULL,
    avatar VARCHAR(255) NULL,
    trainer_id INT UNSIGNED NULL,
    certification BOOLEAN NOT NULL DEFAULT FALSE
) ENGINE=InnoDB;

CREATE TABLE training_program (
    training_id INT UNSIGNED NOT NULL,
    day_number TINYINT UNSIGNED NOT NULL,
    time_start TIME NOT NULL,
    time_end TIME NOT NULL,
    label VARCHAR(150) NOT NULL,
    description TEXT NULL,
    objectives TEXT NULL
) ENGINE=InnoDB;

CREATE TABLE content_taxonomy (
    content_id INT UNSIGNED NOT NULL,
    taxonomy_id INT UNSIGNED NOT NULL,
    content_type_id INT UNSIGNED NOT NULL,
    PRIMARY KEY (content_id, taxonomy_id, content_type_id)
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

CREATE TABLE newsletter_subscription (
    email VARCHAR(100) NOT NULL,
    subscribedis_on TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
    unsubscribedis_on TIMESTAMP NULL
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
    ADD COLUMN id        INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN slug      VARCHAR(255)     AS(TRIM(BOTH '-' FROM LOWER(REGEXP_REPLACE(label, '[^[:alnum:]]+', '-')))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE taxonomy_content_type
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE trainer
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN slug    VARCHAR(255)     AS(TRIM(BOTH '-' FROM LOWER(REGEXP_REPLACE(label, '[^[:alnum:]]+', '-')))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE article
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN slug    VARCHAR(255)     AS(TRIM(BOTH '-' FROM LOWER(REGEXP_REPLACE(label, '[^[:alnum:]]+', '-')))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE event
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN slug    VARCHAR(255)     AS(TRIM(BOTH '-' FROM LOWER(REGEXP_REPLACE(label, '[^[:alnum:]]+', '-')))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE training
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN slug    VARCHAR(255)     AS(TRIM(BOTH '-' FROM LOWER(REGEXP_REPLACE(label, '[^[:alnum:]]+', '-')))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE training_program
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN slug    VARCHAR(255)     AS(TRIM(BOTH '-' FROM LOWER(REGEXP_REPLACE(label, '[^[:alnum:]]+', '-')))) STORED,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE content_taxonomy
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE contact_request
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE newsletter_subscription
    ADD COLUMN id      INT UNSIGNED     NOT NULL    AUTO_INCREMENT PRIMARY KEY,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

ALTER TABLE user_session
    ADD COLUMN id      CHAR(32)         NOT NULL PRIMARY KEY,
    ADD COLUMN new_on    TIMESTAMP        NOT NULL    DEFAULT CURRENT_TIMESTAMP,
    ADD COLUMN get_on    TIMESTAMP        NULL,
    ADD COLUMN set_on    TIMESTAMP        NULL        DEFAULT NULL ON UPDATE CURRENT_TIMESTAMP,
    ADD COLUMN dis_on    TIMESTAMP        NULL;

-- ===============================================
-- 3. CONSTRAINTS, INDEXES & KEYS (unchanged)
-- ===============================================

ALTER TABLE taxonomy
    ADD CONSTRAINT fk_taxonomy_parent FOREIGN KEY (parent_id) REFERENCES taxonomy(id) ON DELETE CASCADE,
    ADD UNIQUE KEY uk_taxonomy_parent_slug (parent_id, slug),
    ADD INDEX idx_taxonomy_parent (parent_id),
    ADD INDEX idx_taxonomy_dis_on (dis_on);

ALTER TABLE taxonomy_content_type
    ADD CONSTRAINT fk_taxonomy_content_type_taxonomy FOREIGN KEY (taxonomy_id) REFERENCES taxonomy(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_taxonomy_content_type_content FOREIGN KEY (content_type_id) REFERENCES taxonomy(id) ON DELETE CASCADE,
    ADD INDEX idx_taxonomy_content_type_rev (content_type_id, taxonomy_id);

ALTER TABLE trainer
    ADD UNIQUE KEY uk_trainer_email (email),
    ADD INDEX idx_trainer_dis_on (dis_on);

ALTER TABLE article
    ADD CONSTRAINT fk_article_taxonomy FOREIGN KEY (taxonomy_id) REFERENCES taxonomy(id) ON DELETE SET NULL,
    ADD UNIQUE KEY uk_article_slug (slug),
    ADD INDEX idx_article_published (get_on),
    ADD INDEX idx_article_featured (featured),
    ADD INDEX idx_article_taxonomy (taxonomy_id),
    ADD FULLTEXT KEY idx_article_search (label, summary, content);

ALTER TABLE event
    ADD CONSTRAINT fk_event_taxonomy FOREIGN KEY (taxonomy_id) REFERENCES taxonomy(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_event_type FOREIGN KEY (event_type_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
    ADD UNIQUE KEY uk_event_slug (slug),
    ADD CONSTRAINT ck_event_places CHECK (places_taken <= COALESCE(places_max, places_taken)),
    ADD INDEX idx_event_type (event_type_id),
    ADD INDEX idx_event_dis_on_date (dis_on, event_date),
    ADD INDEX idx_event_dis_on (dis_on);

ALTER TABLE training
    ADD CONSTRAINT fk_training_trainer FOREIGN KEY (trainer_id) REFERENCES trainer(id) ON DELETE SET NULL,
    ADD CONSTRAINT fk_training_level FOREIGN KEY (training_level_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
    ADD UNIQUE KEY uk_training_slug (slug),
    ADD CONSTRAINT ck_training_places CHECK (places_taken <= COALESCE(places_max, places_taken)),
    ADD INDEX idx_training_level (training_level_id),
    ADD INDEX idx_training_date (start_date),
    ADD INDEX idx_training_dis_on (dis_on);

ALTER TABLE training_program
    ADD CONSTRAINT fk_training_program_training FOREIGN KEY (training_id) REFERENCES training(id) ON DELETE CASCADE,
    ADD UNIQUE KEY uk_training_program_time (training_id, day_number, time_start),
    ADD CONSTRAINT ck_training_program_time CHECK (time_end > time_start),
    ADD INDEX idx_training_program_day (training_id, day_number);

ALTER TABLE content_taxonomy
    ADD CONSTRAINT fk_content_taxonomy_taxonomy FOREIGN KEY (taxonomy_id) REFERENCES taxonomy(id) ON DELETE CASCADE,
    ADD CONSTRAINT fk_content_taxonomy_content_type FOREIGN KEY (content_type_id) REFERENCES taxonomy(id) ON DELETE CASCADE;

ALTER TABLE contact_request
    ADD CONSTRAINT fk_contact_request_subject FOREIGN KEY (contact_subject_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
    ADD CONSTRAINT fk_contact_request_status FOREIGN KEY (request_status_id) REFERENCES taxonomy(id) ON DELETE RESTRICT,
    ADD INDEX idx_contact_status (request_status_id),
    ADD INDEX idx_contact_new_on (new_on),
    ADD INDEX idx_contact_subject (contact_subject_id);

ALTER TABLE newsletter_subscription
    ADD UNIQUE KEY uk_newsletter_email (email),
    ADD INDEX idx_newsletter_dis_on (dis_on);

ALTER TABLE user_session
    ADD INDEX idx_user_session_activity (last_activity);
