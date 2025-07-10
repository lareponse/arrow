SET FOREIGN_KEY_CHECKS = 0;

TRUNCATE TABLE coproacademy;
INSERT INTO coproacademy (slug, label) VALUES
  ('email',                'info@copro.academy.be'),
  ('email-response-time',  'Réponse sous 24 h ouvrées'),
  ('telephone',            '+32 510 08 00 01'),
  ('telephone-hours',      'Lundi – Vendredi : 9 h – 17 h'),
  ('adresse',              '292B Rue de Stalle\n1180 Uccle, Belgique'),
  ('facebook',             'https://facebook.com/coproacademy'),
  ('instagram',            'https://instagram.com/coproacademy'),
  ('linkedin',             'https://linkedin.com/company/coproacademy');


TRUNCATE TABLE faq;
INSERT INTO faq (slug, label, content) VALUES ('comment-sinscrire-formation','Comment s\'inscrire à une formation ?','Vous pouvez vous inscrire directement via notre formulaire de contact en sélectionnant "Inscription à une formation". Nous vous recontacterons pour finaliser votre inscription et vous communiquer les modalités pratiques.');
INSERT INTO faq (slug, label, content) VALUES ('formations-certifiees','Vos formations sont-elles certifiées ?','Oui, toutes nos formations sont certifiées et reconnues dans le cadre de la formation professionnelle continue. Elles donnent droit à des attestations de formation.');
INSERT INTO faq (slug, label, content) VALUES ('formations-sur-mesure','Proposez-vous des formations sur mesure ?','Absolument ! Nous pouvons adapter nos programmes à vos besoins spécifiques. Contactez-nous pour discuter de vos objectifs et nous élaborerons une solution personnalisée.');
INSERT INTO faq (slug, label, content) VALUES ('regions-intervention','Dans quelles régions intervenez-vous ?','Nous intervenons principalement en région de Bruxelles-Capitale et en Wallonie. Pour des formations sur site, nous nous déplaçons selon la taille du groupe.');
INSERT INTO faq (slug, label, content) VALUES ('tarifs-formation','Quels sont vos tarifs de formation ?','Nos tarifs varient en fonction de la formation choisie et du nombre de participants. Contactez-nous pour un devis personnalisé adapté à vos besoins.');
INSERT INTO faq (slug, label, content) VALUES ('formateurs-experts','Qui sont vos formateurs ?','Nos formateurs sont des experts reconnus dans leur domaine, avec une expérience significative en formation professionnelle. Ils allient théorie et pratique pour une pédagogie efficace.');
INSERT INTO faq (slug, label, content) VALUES ('modalites-paiement','Quelles sont les modalités de paiement ?','Nous acceptons les paiements par virement bancaire, chèque ou carte de crédit. Un acompte peut être demandé pour confirmer votre inscription. Les détails vous seront fournis lors de la finalisation de votre inscription.');



TRUNCATE TABLE service;
INSERT INTO service (label, image_src, alt_text, content, link, link_text, sort_order) VALUES
('Gestion de copropriétés','/static/assets/hero.webp','Gestion de copropriétés','Accompagnement professionnel et outils adaptés pour optimiser la gestion de votre copropriété.','/contact','En savoir plus',1),
('Formations certifiées','/static/assets/hero.webp','Formations certifiées','Maîtrisez les aspects juridiques et techniques grâce à nos programmes de formation reconnus.','/formation','Voir les formations',2),
('Actualités & Webinaires','/static/assets/hero.webp','Actualités et webinaires','Restez informé des évolutions législatives et participez à nos sessions d\'information.','/article','Explorer',3);



TRUNCATE TABLE trainer;
INSERT INTO trainer (slug, label, title, bio, avatar, email, hire_date) 
VALUES 
('sophie-dubois','Sophie Dubois','Avocate spécialisée en médiation','Me. Sophie Dubois est avocate spécialisée en résolution de conflits et médiation au sein de copropriétés, avec plus de 10 ans d’expérience.','/static/assets/trainers/sophie-dubois.jpg','sophie.dubois@example.com','2019-04-15'),
('julien-martin','Julien Martin','Expert en gestion financière','Julien Martin est expert en gestion financière de copropriétés et formateur reconnu pour ses analyses budgétaires approfondies.','/static/assets/trainers/julien-martin.jpg','julien.martin@example.com','2020-09-01'),
('amina-traore','Amina Traoré','Experte en urbanisme','Amina Traoré est experte en urbanisme et inclusion sociale, d’origine malienne et active à Bruxelles.','/static/assets/trainers/amina-traore.jpg','amina.traore@example.be','2018-07-20'),
('samir-el-haddad','Samir El Haddad','Spécialiste en rénovation durable','Samir El Haddad est spécialiste en rénovation durable, issu de la communauté marocaine de Belgique.','/static/assets/trainers/samir-el-haddad.jpg','samir.haddad@example.be','2017-11-05'),
('nguyen-anh','Nguyen Anh','Consultant en digitalisation','Nguyen Anh, d’origine vietnamienne, est consultant en digitalisation de la gestion de copropriété.','/static/assets/trainers/nguyen-anh.jpg','nguyen.anh@example.be','2020-03-17'),
('marie-louise-lemaire','Marie-Louise Lemaire','Experte en fiscalité immobilière','Marie-Louise Lemaire est formatrice wallonne de Namur, experte en fiscalité immobilière.','/static/assets/trainers/marie-louise-lemaire.jpg','marie.lemaire@example.be','2018-05-30'),
('dirk-de-smet','Dirk De Smet','Consultant en gouvernance immobilière','Dirk De Smet est consultant en gouvernance immobilière pour le Brabant flamand.','/static/assets/trainers/dirk-de-smet.jpg','dirk.desmet@example.be','2015-09-17'),
('fabrice-mbala','Fabrice Mbala','Expert en contentieux','Fabrice Mbala est expert en contentieux et médiation, d’origine congolaise et basé à Liège.','/static/assets/trainers/fabrice-mbala.jpg','fabrice.mbala@example.be','2016-02-14'),
('celine-francois','Céline François','Experte en fiscalité immobilière','Céline François est experte en fiscalité immobilière et accompagne les copropriétaires dans leurs déclarations fiscales.','/static/assets/trainers/celine-francois.jpg','celine.francois@example.com','2022-02-28'),
('ferhat-kaya','Ferhat Kaya','Ingénieur en efficacité énergétique','Ferhat Kaya est ingénieur en efficacité énergétique d’origine turque, intervenant à Anderlecht sur des projets de rénovation durable.','/static/assets/trainers/ferhat-kaya.jpg','ferhat.kaya@example.be','2019-09-23');

TRUNCATE TABLE article;

SET @cat_marche           = (SELECT id FROM taxonomy WHERE slug = 'categorie-marche');
SET @cat_financement      = (SELECT id FROM taxonomy WHERE slug = 'categorie-financement');
SET @cat_juridique        = (SELECT id FROM taxonomy WHERE slug = 'categorie-juridique');
SET @cat_construction     = (SELECT id FROM taxonomy WHERE slug = 'categorie-construction');
SET @cat_gestion_locative = (SELECT id FROM taxonomy WHERE slug = 'categorie-gestion_locative');

INSERT INTO article (
  slug,
  label,
  summary,
  content,
  category_id,
  reading_time,
  featured,
  section1_label,
  section1_content,
  section2_label,
  section2_content,
  section3_label,
  section3_content,
  section4_label,
  section4_content,
  section5_label,
  section5_content
) VALUES
(
  'investir-immobilier-locatif-2025',
  'Investir dans l''immobilier locatif en 2025',
  'Un guide complet pour optimiser vos investissements locatifs dans le contexte économique actuel.',
  'Dans ce guide, nous passons en revue les principaux leviers pour maximiser votre rendement locatif en 2025, de la sélection des zones à l''optimisation fiscale.',
  @cat_marche,
  7,
  FALSE,
  'Zones à fort rendement',
  'Les villes universitaires et les métropoles secondaires offrent souvent des rendements supérieurs grâce à une forte demande locative. Analysez le prix au m², le taux d''occupation et la dynamique démographique pour cibler les secteurs porteurs.',
  'Stratégies de financement',
  'Comparez les prêts amortissables, le crédit in fine et les plateformes de financement participatif. Prenez en compte votre profil d''emprunteur, la durée du prêt et les garanties exigées pour réduire le coût global du crédit.',
  'Optimisation fiscale',
  'Explorez les dispositifs comme le régime LMNP, le déficit foncier ou le statut LMP pour alléger votre imposition. Vérifiez les conditions d’éligibilité, les plafonds et les délais de déclaration avant de vous engager.',
  'Gestion locative',
  'Décidez entre gestion directe et délégation à une agence. Comparez les honoraires, la qualité de service et les outils de suivi pour garantir un bon taux de recouvrement et limiter la vacance.',
  'Risques et précautions',
  'Anticipez la vacance locative, les impayés et la dégradation du bien. Mettez en place un garant solide, souscrivez une assurance loyers impayés et planifiez des états des lieux réguliers.'
),
(
  'analyse-marche-immobilier-post-pandemie',
  'Analyse du marché immobilier post-pandémie',
  'Retour sur les évolutions du marché immobilier depuis la pandémie et perspectives pour les prochains mois.',
  'Cette analyse détaille l''impact de la pandémie sur les prix, la demande et les comportements des acteurs, pour mieux comprendre les tendances actuelles.',
  @cat_financement,
  6,
  FALSE,
  'Évolution des prix',
  'Depuis 2020, les prix ont fluctué selon les régions : forte hausse en périphérie, stagnation dans les centres-villes. Les taux bas ont soutenu la demande malgré un contexte économique incertain.',
  'Comportement des acheteurs',
  'Les acheteurs privilégient désormais les logements avec espaces extérieurs et un espace bureau. La digitalisation des visites a accéléré certaines transactions, mais nécessite une adaptation des méthodes de vente.',
  'Demande locative',
  'Le marché étudiant et le segment intermédiaire ont rebondi, tandis que la location de courtes durées reste volatile, dépendante des flux touristiques et des restrictions sanitaires.',
  'Perspectives futures',
  'Anticipation d’une remontée graduelle des taux directeurs en fin d’année 2025, ce qui pourrait freiner la croissance des prix et inciter les investisseurs à diversifier leurs portefeuilles.',
  NULL,
  NULL
),
(
  'solutions-financement-travaux-renovation',
  'Solutions de financement pour travaux de rénovation',
  'Panorama des options de financement pour financer vos travaux de rénovation de copropriété.',
  'Découvrez les principales solutions pour financer vos projets de rénovation, adaptées aux copropriétaires et aux petites structures.',
  @cat_juridique,
  8,
  TRUE,
  'Prêts bancaires',
  'Les prêts travaux classiques, à taux fixe ou variable, restent une base fiable. Comparez les offres, négociez la durée et les frais de dossier pour optimiser le coût global de votre crédit.',
  'Subventions publiques',
  'Profitez des aides de l’ANAH, des collectivités locales et des Certificats d’Économies d’Énergie (CEE). Préparez un dossier complet pour maximiser le montant des aides accordées.',
  'Aides fiscales',
  'Le Crédit d’Impôt pour la Transition Énergétique (CITE), la TVA réduite à 5,5 % et les CEE peuvent réduire significativement votre facture. Vérifiez les conditions de cumul avant d’engager les travaux.',
  NULL,
  NULL,
  NULL,
  NULL
),
(
  'nouvelles-deductions-fiscales-coproprietaires',
  'Les nouvelles déductions fiscales pour copropriétaires',
  'Zoom sur les récents changements fiscaux et les déductions disponibles pour vos travaux.',
  'Cette synthèse présente les dernières mesures fiscales impactant les copropriétés et les opportunités de déductions pour optimiser vos charges.',
  @cat_construction,
  5,
  FALSE,
  'Barèmes actuels',
  'Les taux applicables aux travaux d’isolation, de rénovation énergétique et de mise en sécurité ont été relevés. Consultez les nouveaux plafonds et pourcentages pour calculer votre réduction d’impôt.',
  'Conditions d’éligibilité',
  'Seuls les travaux votés en assemblée générale, réalisés par des professionnels et conformes aux normes en vigueur donnent droit à la déduction. Conservez tous les justificatifs.',
  'Démarches administratives',
  'Déclarez vos dépenses au centre des finances publiques dans les délais impartis et joignez les factures détaillées. Un suivi rigoureux évite tout risque de redressement fiscal.',
  NULL,
  NULL,
  NULL,
  NULL
),
(
  'tendances-renovation-energetique-batiments',
  'Tendances de la rénovation énergétique des bâtiments',
  'Étude des dernières tendances et innovations en rénovation énergétique.',
  'Nous explorons les innovations et les meilleures pratiques pour réduire la consommation d’énergie dans les bâtiments, du résidentiel au tertiaire.',
  @cat_gestion_locative,
  9,
  TRUE,
  'Matériaux innovants',
  'Les isolants biosourcés (ouate de cellulose, fibre de bois) gagnent en popularité pour leurs performances thermiques et leur faible empreinte carbone. Idéal pour des projets éco-responsables.',
  'Technologies émergentes',
  'Ventilation double flux connectée, pompes à chaleur hybrides et régulation intelligente de la température permettent des économies d’énergie supérieures et un meilleur confort intérieur.',
  'Retour sur investissement',
  'Calculez le temps de retour en comparant le gain énergétique annuel et le coût des travaux : il se situe généralement entre 5 et 10 ans selon l’ampleur des travaux.',
  'Normes et certifications',
  'RT 2020, BREEAM et HQE définissent les standards de performance. Choisissez le label adapté pour valoriser votre patrimoine et sécuriser votre financement.',
  NULL,
  NULL
);

TRUNCATE TABLE event;
SET @cat_atelier_pratique = (
  SELECT id
  FROM taxonomy
  WHERE slug = 'categorie-atelier_pratique'
);
INSERT INTO event (
  slug,
  category_id,
  label,
  content,
  event_date,
  duration_minutes,
  price_ht,
  places_max,
  avatar,
  speaker,
  location,
  online
) VALUES
('atelier-hebdomadaire-introduction', @cat_atelier_pratique, 'Atelier hebdomadaire: Introduction', 'Description de l''atelier hebdomadaire 1.', '2025-07-01 10:00:00', 60, 0.00, 50, '/static/assets/event1.jpg', 'Alice Dupont', 'En ligne', TRUE),
('atelier-hebdomadaire-techniques-avancees', @cat_atelier_pratique, 'Atelier hebdomadaire: Techniques avancées', 'Description de l''atelier hebdomadaire 2.', '2025-07-08 14:00:00', 90, 50.00, 30, '/static/assets/event2.jpg', 'Bob Martin', 'Salle de conférence A, Bruxelles', FALSE),
('atelier-hebdomadaire-gestion-projets', @cat_atelier_pratique, 'Atelier hebdomadaire: Gestion de projets', 'Description de l''atelier hebdomadaire 3.', '2025-07-15 11:00:00', 75, 0.00, 100, '/static/assets/event3.jpg', 'Caroline Legrand', 'En ligne', TRUE),
('atelier-hebdomadaire-leadership', @cat_atelier_pratique, 'Atelier hebdomadaire: Leadership', 'Description de l''atelier hebdomadaire 4.', '2025-07-22 15:00:00', 120, 75.00, 40, '/static/assets/event4.jpg', 'David Petit', 'Salle B, Bruxelles', FALSE),
('atelier-hebdomadaire-innovation', @cat_atelier_pratique, 'Atelier hebdomadaire: Innovation', 'Description de l''atelier hebdomadaire 5.', '2025-07-29 09:30:00', 60, 0.00, 80, '/static/assets/event5.jpg', 'Émilie Dupuis', 'En ligne', TRUE),
('atelier-hebdomadaire-marketing-digital', @cat_atelier_pratique, 'Atelier hebdomadaire: Marketing digital', 'Description de l''atelier hebdomadaire 6.', '2025-08-05 13:00:00', 90, 100.00, 25, '/static/assets/event6.jpg', 'François Dubois', 'Centre C, Bruxelles', FALSE),
('atelier-hebdomadaire-compliance', @cat_atelier_pratique, 'Atelier hebdomadaire: Compliance', 'Description de l''atelier hebdomadaire 7.', '2025-08-12 16:00:00', 60, 20.00, 60, '/static/assets/event7.jpg', 'Géraldine Mercier', 'En ligne', TRUE);


TRUNCATE TABLE contact_request;
SET @sub_general      = (SELECT id FROM taxonomy WHERE slug = 'sujet-general');
SET @sub_formation    = (SELECT id FROM taxonomy WHERE slug = 'sujet-formation');
SET @sub_support      = (SELECT id FROM taxonomy WHERE slug = 'sujet-support');
SET @sub_partenariat  = (SELECT id FROM taxonomy WHERE slug = 'sujet-partenariat');
SET @sub_facturation  = (SELECT id FROM taxonomy WHERE slug = 'sujet-facturation');

SET @status_waiting   = (SELECT id FROM taxonomy WHERE slug = 'statut-en-attente'   AND parent_id = (SELECT id FROM taxonomy WHERE slug = 'contact_demande-statut'));
SET @status_en_cours  = (SELECT id FROM taxonomy WHERE slug = 'statut-en-cours'    AND parent_id = (SELECT id FROM taxonomy WHERE slug = 'contact_demande-statut'));
SET @status_resolu    = (SELECT id FROM taxonomy WHERE slug = 'statut-resolu'       AND parent_id = (SELECT id FROM taxonomy WHERE slug = 'contact_demande-statut'));
SET @status_ferme     = (SELECT id FROM taxonomy WHERE slug = 'statut-ferme'        AND parent_id = (SELECT id FROM taxonomy WHERE slug = 'contact_demande-statut'));

INSERT INTO contact_request (
  label,
  content,
  email,
  phone,
  company,
  subject_id,
  consent,
  status_id
) VALUES
('Demande d''infos général', 'Bonjour, je souhaiterais avoir des informations générales sur vos services.', 'maxime@example.com', '+32 475 12 34 56', 'MaxiCo SARL',    @sub_general,   TRUE, @status_waiting),
('Inscription formation',   'Je suis intéressé par la formation gestion financière. Merci de me recontacter.',          'laura@example.org', NULL,              'Laura & Co',     @sub_formation, TRUE, @status_en_cours),
('Problème technique site', 'Impossible de soumettre le formulaire, je reçois une erreur 500.',                      'thierry@example.net', '+32 473 98 76 54', NULL,             @sub_support,   TRUE, @status_resolu),
('Demande partenariat',     'Bonjour, je souhaiterais discuter d’un partenariat pour un prochain événement.',            'contact@partenaire.com', NULL,         'Partenaires Ltd',@sub_partenariat,TRUE, @status_waiting),
('Question facturation',    'Je n''ai pas reçu ma facture pour la formation du mois dernier, pouvez-vous m'' aider ?',   'client@example.com', '+32 460 11 22 33', NULL,             @sub_facturation,TRUE,@status_ferme);


TRUNCATE TABLE training;
SET @lvl_debutant      = (SELECT id FROM taxonomy WHERE slug = 'niveau-debutant');
SET @lvl_intermediaire = (SELECT id FROM taxonomy WHERE slug = 'niveau-intermediaire');
SET @lvl_avance        = (SELECT id FROM taxonomy WHERE slug = 'niveau-avance');

INSERT INTO training (
  slug, level_id, label, content,
  duration_days, duration_hours,
  price_ht, start_date,
  places_max, objectives, prerequisites,
  avatar, trainer_id, certification
) VALUES
('introduction-gestion-copropriete', @lvl_debutant, 'Introduction à la gestion de copropriété', 'Formation de base pour comprendre les enjeux de la gestion collective et acquérir les fondamentaux juridiques et pratiques.', 2, 14, 450.00, '2025-09-15', NULL, 'Maîtriser le cadre légal belge; Comprendre les rôles et responsabilités; Gérer les assemblées générales', NULL, '/static/assets/hero.webp', NULL, FALSE),
('gestion-financiere-copropriete', @lvl_intermediaire, 'Gestion financière de copropriété', 'Approfondissement des aspects financiers : budgets, comptes, provisions, et gestion des impayés.', 3, 21, 650.00, '2025-10-22', NULL, 'Élaborer et suivre un budget; Gérer les provisions et fonds; Traiter les impayés efficacement', NULL, '/static/assets/hero.webp', NULL, FALSE),
('contentieux-mediation-copropriete', @lvl_avance, 'Contentieux et médiation en copropriété', 'Gestion des conflits, procédures contentieuses et techniques de médiation adaptées aux copropriétés.', 2, 14, 550.00, '2025-11-18', NULL, 'Prévenir et gérer les conflits; Maîtriser les procédures judiciaires; Utiliser la médiation efficacement', NULL, '/static/assets/hero.webp', NULL, FALSE),
('reglementation-energetique-travaux', @lvl_debutant, 'Réglementation énergétique et travaux', 'Comprendre les obligations énergétiques et la gestion des travaux d\'amélioration dans les copropriétés.', 1, 7, 320.00, '2025-12-12', NULL, 'Connaître la réglementation PEB; Organiser les travaux énergétiques; Optimiser les aides et subventions', NULL, '/static/assets/hero.webp', NULL, FALSE);


TRUNCATE TABLE training_program;
SET @t_intro = (SELECT id FROM training WHERE slug = 'introduction-gestion-copropriete');
SET @t_gf    = (SELECT id FROM training WHERE slug = 'gestion-financiere-copropriete');
SET @t_cm    = (SELECT id FROM training WHERE slug = 'contentieux-mediation-copropriete');
SET @t_reg   = (SELECT id FROM training WHERE slug = 'reglementation-energetique-travaux');
INSERT INTO training_program (
  slug, label, content, training_id, day_number, time_start, time_end, objectives
) VALUES
('introduction-gestion-copropriete-day1-session1','Introduction à la gestion de copropriété',NULL,@t_intro,1,'09:00:00','10:30:00',NULL),
('introduction-gestion-copropriete-day1-session2','Cadre juridique et obligations',NULL,@t_intro,1,'10:40:00','12:10:00',NULL),
('introduction-gestion-copropriete-day1-game1','Jeu de cloture',NULL,@t_intro,1,'12:10:00','12:25:00',NULL),
('introduction-gestion-copropriete-day1-game2','Activité de groupe',NULL,@t_intro,1,'13:10:00','13:25:00',NULL),
('introduction-gestion-copropriete-day1-session3','Rôle des organes de la copropriété',NULL,@t_intro,1,'13:25:00','14:55:00',NULL),
('introduction-gestion-copropriete-day1-session4','Assemblées générales et décisions',NULL,@t_intro,1,'15:05:00','16:35:00',NULL),
('introduction-gestion-copropriete-day1-free','Espace libre (Q&R)',NULL,@t_intro,1,'16:35:00','17:05:00',NULL),

('introduction-gestion-copropriete-day2-session1','Gestion quotidienne et budget',NULL,@t_intro,2,'09:00:00','10:30:00',NULL),
('introduction-gestion-copropriete-day2-session2','Maintenance et charges',NULL,@t_intro,2,'10:40:00','12:10:00',NULL),
('introduction-gestion-copropriete-day2-game1','Jeu de cloture',NULL,@t_intro,2,'12:10:00','12:25:00',NULL),
('introduction-gestion-copropriete-day2-game2','Activité de groupe',NULL,@t_intro,2,'13:10:00','13:25:00',NULL),
('introduction-gestion-copropriete-day2-session3','Risques et responsabilités',NULL,@t_intro,2,'13:25:00','14:55:00',NULL),
('introduction-gestion-copropriete-day2-session4','Synthèse et étude de cas',NULL,@t_intro,2,'15:05:00','16:35:00',NULL),
('introduction-gestion-copropriete-day2-free','Espace libre (Q&R)',NULL,@t_intro,2,'16:35:00','17:05:00',NULL),

('gestion-financiere-copropriete-day1-session1','Principes de budgétisation',NULL,@t_gf,1,'09:00:00','10:30:00',NULL),
('gestion-financiere-copropriete-day1-session2','Suivi des comptes',NULL,@t_gf,1,'10:40:00','12:10:00',NULL),
('gestion-financiere-copropriete-day1-game1','Jeu de cloture',NULL,@t_gf,1,'12:10:00','12:25:00',NULL),
('gestion-financiere-copropriete-day1-game2','Activité de groupe',NULL,@t_gf,1,'13:10:00','13:25:00',NULL),
('gestion-financiere-copropriete-day1-session3','Provisions et fonds',NULL,@t_gf,1,'13:25:00','14:55:00',NULL),
('gestion-financiere-copropriete-day1-session4','Gestion des impayés',NULL,@t_gf,1,'15:05:00','16:35:00',NULL),
('gestion-financiere-copropriete-day1-free','Espace libre (Q&R)',NULL,@t_gf,1,'16:35:00','17:05:00',NULL),

('gestion-financiere-copropriete-day2-session1','Analyse financière avancée',NULL,@t_gf,2,'09:00:00','10:30:00',NULL),
('gestion-financiere-copropriete-day2-session2','Tableaux de bord',NULL,@t_gf,2,'10:40:00','12:10:00',NULL),
('gestion-financiere-copropriete-day2-game1','Jeu de cloture',NULL,@t_gf,2,'12:10:00','12:25:00',NULL),
('gestion-financiere-copropriete-day2-game2','Activité de groupe',NULL,@t_gf,2,'13:10:00','13:25:00',NULL),
('gestion-financiere-copropriete-day2-session3','Prévisions et planning',NULL,@t_gf,2,'13:25:00','14:55:00',NULL),
('gestion-financiere-copropriete-day2-session4','Cas pratiques',NULL,@t_gf,2,'15:05:00','16:35:00',NULL),
('gestion-financiere-copropriete-day2-free','Espace libre (Q&R)',NULL,@t_gf,2,'16:35:00','17:05:00',NULL),

('gestion-financiere-copropriete-day3-session1','Audit et conformité',NULL,@t_gf,3,'09:00:00','10:30:00',NULL),
('gestion-financiere-copropriete-day3-session2','Optimisation des coûts',NULL,@t_gf,3,'10:40:00','12:10:00',NULL),
('gestion-financiere-copropriete-day3-game1','Jeu de cloture',NULL,@t_gf,3,'12:10:00','12:25:00',NULL),
('gestion-financiere-copropriete-day3-game2','Activité de groupe',NULL,@t_gf,3,'13:10:00','13:25:00',NULL),
('gestion-financiere-copropriete-day3-session3','Reporting financier',NULL,@t_gf,3,'13:25:00','14:55:00',NULL),
('gestion-financiere-copropriete-day3-session4','Atelier de restitution',NULL,@t_gf,3,'15:05:00','16:35:00',NULL),
('gestion-financiere-copropriete-day3-free','Espace libre (Q&R)',NULL,@t_gf,3,'16:35:00','17:05:00',NULL),

('contentieux-mediation-copropriete-day1-session1','Identification des conflits',NULL,@t_cm,1,'09:00:00','10:30:00',NULL),
('contentieux-mediation-copropriete-day1-session2','Procédures contentieuses',NULL,@t_cm,1,'10:40:00','12:10:00',NULL),
('contentieux-mediation-copropriete-day1-game1','Jeu de cloture',NULL,@t_cm,1,'12:10:00','12:25:00',NULL),
('contentieux-mediation-copropriete-day1-game2','Activité de groupe',NULL,@t_cm,1,'13:10:00','13:25:00',NULL),
('contentieux-mediation-copropriete-day1-session3','Techniques de médiation',NULL,@t_cm,1,'13:25:00','14:55:00',NULL),
('contentieux-mediation-copropriete-day1-session4','Simulation de cas',NULL,@t_cm,1,'15:05:00','16:35:00',NULL),
('contentieux-mediation-copropriete-day1-free','Espace libre (Q&R)',NULL,@t_cm,1,'16:35:00','17:05:00',NULL),

('contentieux-mediation-copropriete-day2-session1','Stratégies de résolution',NULL,@t_cm,2,'09:00:00','10:30:00',NULL),
('contentieux-mediation-copropriete-day2-session2','Négociation et consensus',NULL,@t_cm,2,'10:40:00','12:10:00',NULL),
('contentieux-mediation-copropriete-day2-game1','Jeu de cloture',NULL,@t_cm,2,'12:10:00','12:25:00',NULL),
('contentieux-mediation-copropriete-day2-game2','Activité de groupe',NULL,@t_cm,2,'13:10:00','13:25:00',NULL),
('contentieux-mediation-copropriete-day2-session3','Cadre légal approfondi',NULL,@t_cm,2,'13:25:00','14:55:00',NULL),
('contentieux-mediation-copropriete-day2-session4','Retours d’expérience',NULL,@t_cm,2,'15:05:00','16:35:00',NULL),
('contentieux-mediation-copropriete-day2-free','Espace libre (Q&R)',NULL,@t_cm,2,'16:35:00','17:05:00',NULL),

('reglementation-energetique-travaux-day1-session1','Réglementation énergétique PEB',NULL,@t_reg,1,'09:00:00','10:30:00',NULL),
('reglementation-energetique-travaux-day1-session2','Obligations et subventions',NULL,@t_reg,1,'10:40:00','12:10:00',NULL),
('reglementation-energetique-travaux-day1-game1','Jeu de cloture',NULL,@t_reg,1,'12:10:00','12:25:00',NULL),
('reglementation-energetique-travaux-day1-game2','Activité de groupe',NULL,@t_reg,1,'13:10:00','13:25:00',NULL),
('reglementation-energetique-travaux-day1-session3','Planification des travaux',NULL,@t_reg,1,'13:25:00','14:55:00',NULL),
('reglementation-energetique-travaux-day1-session4','Études de cas',NULL,@t_reg,1,'15:05:00','16:35:00',NULL),
('reglementation-energetique-travaux-day1-free','Espace libre (Q&R)',NULL,@t_reg,1,'16:35:00','17:05:00',NULL);

SET FOREIGN_KEY_CHECKS = 1;