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

INSERT INTO faq (slug, label, content) VALUES ('comment-sinscrire-formation','Comment s\'inscrire à une formation ?','Vous pouvez vous inscrire directement via notre formulaire de contact en sélectionnant "Inscription à une formation". Nous vous recontacterons pour finaliser votre inscription et vous communiquer les modalités pratiques.');
INSERT INTO faq (slug, label, content) VALUES ('formations-certifiees','Vos formations sont-elles certifiées ?','Oui, toutes nos formations sont certifiées et reconnues dans le cadre de la formation professionnelle continue. Elles donnent droit à des attestations de formation.');
INSERT INTO faq (slug, label, content) VALUES ('formations-sur-mesure','Proposez-vous des formations sur mesure ?','Absolument ! Nous pouvons adapter nos programmes à vos besoins spécifiques. Contactez-nous pour discuter de vos objectifs et nous élaborerons une solution personnalisée.');
INSERT INTO faq (slug, label, content) VALUES ('regions-intervention','Dans quelles régions intervenez-vous ?','Nous intervenons principalement en région de Bruxelles-Capitale et en Wallonie. Pour des formations sur site, nous nous déplaçons selon la taille du groupe.');
INSERT INTO faq (slug, label, content) VALUES ('tarifs-formation','Quels sont vos tarifs de formation ?','Nos tarifs varient en fonction de la formation choisie et du nombre de participants. Contactez-nous pour un devis personnalisé adapté à vos besoins.');
INSERT INTO faq (slug, label, content) VALUES ('formateurs-experts','Qui sont vos formateurs ?','Nos formateurs sont des experts reconnus dans leur domaine, avec une expérience significative en formation professionnelle. Ils allient théorie et pratique pour une pédagogie efficace.');
INSERT INTO faq (slug, label, content) VALUES ('modalites-paiement','Quelles sont les modalités de paiement ?','Nous acceptons les paiements par virement bancaire, chèque ou carte de crédit. Un acompte peut être demandé pour confirmer votre inscription. Les détails vous seront fournis lors de la finalisation de votre inscription.');


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

INSERT INTO service (label, image_src, alt_text, content, link, link_text, sort_order) VALUES
('Gestion de copropriétés','/static/assets/hero.jpeg','Gestion de copropriétés','Accompagnement professionnel et outils adaptés pour optimiser la gestion de votre copropriété.','/contact','En savoir plus',1),
('Formations certifiées','/static/assets/hero.jpeg','Formations certifiées','Maîtrisez les aspects juridiques et techniques grâce à nos programmes de formation reconnus.','/formation','Voir les formations',2),
('Actualités & Webinaires','/static/assets/hero.jpeg','Actualités et webinaires','Restez informé des évolutions législatives et participez à nos sessions d\'information.','/article','Explorer',3);

INSERT INTO article (
    slug,
    label,
    summary,
    content,
    category_id,
    reading_time,
    avatar,
    featured
) VALUES
('investir-immobilier-locatif-2025', 'Investir dans l\'immobilier locatif en 2025', 'Un guide complet pour optimiser vos investissements locatifs dans le contexte économique actuel.', 'Un guide complet pour optimiser vos investissements locatifs dans le contexte économique actuel. Nous passons en revue les zones à fort rendement, les stratégies de financement et les risques à surveiller.', 25, 7, '/static/assets/immobilier-locatif.jpeg', FALSE),
('analyse-marche-immobilier-post-pandemie', 'Analyse du marché immobilier post-pandémie', 'Retour sur les évolutions du marché immobilier depuis la pandémie et perspectives pour les prochains mois.', 'Retour sur les évolutions du marché immobilier depuis la pandémie et perspectives pour les prochains mois. Les prix, la demande et les comportements des acheteurs sont passés au crible.', 26, 6, '/static/assets/marche-post-pandemie.jpeg', FALSE),
('solutions-financement-travaux-renovation', 'Solutions de financement pour travaux de rénovation', 'Panorama des options de financement pour financer vos travaux de rénovation de copropriété.', 'Panorama des options de financement pour financer vos travaux de rénovation de copropriété. Retrouvez un comparatif des prêts, subventions et aides fiscales disponibles.', 27, 8, '/static/assets/financement-renovation.jpeg', TRUE),
('nouvelles-deductions-fiscales-coproprietaires', 'Les nouvelles déductions fiscales pour copropriétaires', 'Zoom sur les récents changements fiscaux et les déductions disponibles pour vos travaux.', 'Zoom sur les récents changements fiscaux et les déductions disponibles pour vos travaux. Les barèmes, conditions d\'éligibilité et démarches sont détaillés.', 29, 5, '/static/assets/deductions-fiscales.jpeg', FALSE),
('tendances-renovation-energetique-batiments', 'Tendances de la rénovation énergétique des bâtiments', 'Étude des dernières tendances et innovations en rénovation énergétique.', 'Étude des dernières tendances et innovations en rénovation énergétique. Des matériaux aux technologies, tout savoir pour planifier vos projets.', 31, 9, '/static/assets/renovation-energetique.jpeg', TRUE);


INSERT INTO event (
  slug, label, content, category_id, event_date,
  duration_minutes, price_ht, places_max, avatar, speaker,
  location, online
) VALUES
('atelier-hebdomadaire-introduction','Atelier hebdomadaire: Introduction','Description de l''atelier hebdomadaire 1.',1,'2025-07-01 10:00:00',60,0.00,50,'/static/assets/event1.jpg','Alice Dupont','En ligne',TRUE),
('atelier-hebdomadaire-techniques-avancees','Atelier hebdomadaire: Techniques avancées','Description de l''atelier hebdomadaire 2.',1,'2025-07-08 14:00:00',90,50.00,30,'/static/assets/event2.jpg','Bob Martin','Salle de conférence A, Bruxelles',FALSE),
('atelier-hebdomadaire-gestion-projets','Atelier hebdomadaire: Gestion de projets','Description de l''atelier hebdomadaire 3.',1,'2025-07-15 11:00:00',75,0.00,100,'/static/assets/event3.jpg','Caroline Legrand','En ligne',TRUE),
('atelier-hebdomadaire-leadership','Atelier hebdomadaire: Leadership','Description de l''atelier hebdomadaire 4.',1,'2025-07-22 15:00:00',120,75.00,40,'/static/assets/event4.jpg','David Petit','Salle B, Bruxelles',FALSE),
('atelier-hebdomadaire-innovation','Atelier hebdomadaire: Innovation','Description de l''atelier hebdomadaire 5.',1,'2025-07-29 09:30:00',60,0.00,80,'/static/assets/event5.jpg','Émilie Dupuis','En ligne',TRUE),
('atelier-hebdomadaire-marketing-digital','Atelier hebdomadaire: Marketing digital','Description de l''atelier hebdomadaire 6.',1,'2025-08-05 13:00:00',90,100.00,25,'/static/assets/event6.jpg','François Dubois','Centre C, Bruxelles',FALSE),
('atelier-hebdomadaire-compliance','Atelier hebdomadaire: Compliance','Description de l''atelier hebdomadaire 7.',1,'2025-08-12 16:00:00',60,20.00,60,'/static/assets/event7.jpg','Géraldine Mercier','En ligne',TRUE);
