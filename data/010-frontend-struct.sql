DROP TABLE IF EXISTS coproacademy;
CREATE TABLE coproacademy (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(100)   NOT NULL UNIQUE,
  label       TEXT           NOT NULL
) ENGINE=InnoDB;

INSERT INTO coproacademy (slug, label) VALUES
  ('email',                'CoProAcademy@contact.be'),
  ('email-response-time',  'Réponse sous 24 h ouvrées'),
  ('telephone',            '+32 510 08 00 01'),
  ('telephone-hours',      'Lundi – Vendredi : 9 h – 17 h'),
  ('adresse',              '292B Rue de Stalle\n1180 Uccle, Belgique'),
  ('facebook',             'https://facebook.com/coproacademy'),
  ('instagram',            'https://instagram.com/coproacademy'),
  ('linkedin',             'https://linkedin.com/company/coproacademy');


DROP TABLE IF EXISTS faq;
CREATE TABLE faq (
  id          INT AUTO_INCREMENT PRIMARY KEY,
  slug        VARCHAR(100)   NOT NULL UNIQUE,
  label       VARCHAR(255)   NOT NULL,
  content     TEXT           NOT NULL,
  sort_order  SMALLINT       UNSIGNED NOT NULL DEFAUlT 0,

  created_at  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP,
  updated_at  DATETIME       NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;


DROP TABLE IF EXISTS service;
CREATE TABLE service (
    id INT AUTO_INCREMENT PRIMARY KEY,
    label VARCHAR(255) NOT NULL,
    content TEXT NOT NULL,
    image_src VARCHAR(255) NOT NULL,
    alt_text VARCHAR(255) NOT NULL,
    link VARCHAR(255) NOT NULL,
    link_text VARCHAR(100) NOT NULL,
    sort_order  SMALLINT       UNSIGNED NOT NULL DEFAUlT 0,
    created_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at DATETIME NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB;
