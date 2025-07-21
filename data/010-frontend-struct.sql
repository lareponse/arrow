-- Table to store company data
DROP TABLE IF EXISTS coproacademy;
CREATE TABLE coproacademy (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  label TEXT NOT NULL
) ENGINE = InnoDB;

-- Table to store FAQ entries
DROP TABLE IF EXISTS faq;
CREATE TABLE faq (
  id INT AUTO_INCREMENT PRIMARY KEY,
  slug VARCHAR(100) NOT NULL UNIQUE,
  label VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAUlT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- Table to store service information
DROP TABLE IF EXISTS service;
CREATE TABLE service (
  id INT AUTO_INCREMENT PRIMARY KEY,
  label VARCHAR(255) NOT NULL,
  content TEXT NOT NULL,
  image_src VARCHAR(255) NOT NULL,
  alt_text VARCHAR(255) NOT NULL,
  link VARCHAR(255) NOT NULL,
  link_text VARCHAR(100) NOT NULL,
  sort_order SMALLINT UNSIGNED NOT NULL DEFAUlT 0,
  created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;

-- Table to store homepage “hero” carousel slides
DROP TABLE IF EXISTS hero_slide;
CREATE TABLE hero_slide (
  id INT AUTO_INCREMENT PRIMARY KEY,
  image_path VARCHAR(255) NOT NULL COMMENT 'Path or URL to the slide image',
  alt_text VARCHAR(255) NULL COMMENT 'Alt text for accessibility',
  title VARCHAR(255) NULL COMMENT 'Main headline on the slide',
  subtitle VARCHAR(255) NULL COMMENT 'Secondary headline',
  description TEXT NULL COMMENT 'Optional descriptive text/html',
  cta_text VARCHAR(100) NULL COMMENT 'Text for the call-to-action button',
  cta_url VARCHAR(255) NULL COMMENT 'Link URL for the CTA button',
  order_index INT NOT NULL DEFAULT 0 COMMENT 'Sorting order of slides',
  created_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE = InnoDB;