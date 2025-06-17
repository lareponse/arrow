-- =====================================================
--  Add taxonomy entries for ENUM→FK conversions
-- =====================================================

-- 1) contact_request.status → taxonomy
INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('contact_request-status', NULL, 'Statut de la demande', NULL, 1);
SET @parent_cr_status = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('contact_request-status-pending',    @parent_cr_status, 'En attente',    NULL, 1),
  ('contact_request-status-processing', @parent_cr_status, 'En cours',      NULL, 2),
  ('contact_request-status-resolved',   @parent_cr_status, 'Résolu',        NULL, 3),
  ('contact_request-status-closed',     @parent_cr_status, 'Fermé',         NULL, 4);


-- 2) event_booking.status → taxonomy
INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('event_booking-status', NULL, 'Statut de la réservation', NULL, 1);
SET @parent_eb_status = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('event_booking-status-pending',   @parent_eb_status, 'En attente',    NULL, 1),
  ('event_booking-status-confirmed', @parent_eb_status, 'Confirmé',      NULL, 2),
  ('event_booking-status-cancelled', @parent_eb_status, 'Annulé',        NULL, 3),
  ('event_booking-status-completed', @parent_eb_status, 'Terminé',       NULL, 4);


-- 3) training_booking.status → taxonomy
INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('training_booking-status', NULL, 'Statut de l’inscription', NULL, 1);
SET @parent_tb_status = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('training_booking-status-pending',   @parent_tb_status, 'En attente',    NULL, 1),
  ('training_booking-status-confirmed', @parent_tb_status, 'Confirmé',      NULL, 2),
  ('training_booking-status-cancelled', @parent_tb_status, 'Annulé',        NULL, 3),
  ('training_booking-status-completed', @parent_tb_status, 'Terminé',       NULL, 4);


-- 4) training.level → taxonomy
INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('training-level', NULL, 'Niveau de formation', NULL, 1);
SET @parent_training_level = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
  ('training-level-beginner',     @parent_training_level, 'Débutant',       NULL, 1),
  ('training-level-intermediate', @parent_training_level, 'Intermédiaire',  NULL, 2),
  ('training-level-advanced',     @parent_training_level, 'Avancé',         NULL, 3),
  ('training-level-expert',       @parent_training_level, 'Expert',         NULL, 4);


-- 5) contact_request.subject → taxonomy
INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
 ('contact_request-subject', NULL, 'Sujet de la demande', NULL, 2);
SET @parent_cr_subject = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
 ('contact_request-subject-general',     @parent_cr_subject, 'Général',        NULL, 1),
 ('contact_request-subject-training',    @parent_cr_subject, 'Formation',      NULL, 2),
 ('contact_request-subject-support',     @parent_cr_subject, 'Support',        NULL, 3),
 ('contact_request-subject-partnership', @parent_cr_subject, 'Partenariat',    NULL, 4),
 ('contact_request-subject-billing',     @parent_cr_subject, 'Facturation',    NULL, 5),
 ('contact_request-subject-technical',   @parent_cr_subject, 'Technique',      NULL, 6);

-- 6) article.category → taxonomy
INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
 ('article-category', NULL, 'Catégorie d\'article', NULL, 3);
SET @parent_article_category = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
 ('article-category-investment',    @parent_article_category, 'Investissement',     NULL, 1),
 ('article-category-market',        @parent_article_category, 'Marché',             NULL, 2),
 ('article-category-financing',     @parent_article_category, 'Financement',        NULL, 3),
 ('article-category-legal',         @parent_article_category, 'Juridique',          NULL, 4),
 ('article-category-taxation',      @parent_article_category, 'Fiscalité',          NULL, 5),
 ('article-category-construction',  @parent_article_category, 'Construction',       NULL, 6),
 ('article-category-renovation',    @parent_article_category, 'Rénovation',         NULL, 7),
 ('article-category-management',    @parent_article_category, 'Gestion locative',   NULL, 8);

-- 7) event.category → taxonomy

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
 ('event-category', NULL, 'Catégorie d\'événement', NULL, 4);
SET @parent_event_category = LAST_INSERT_ID();

INSERT INTO taxonomy (slug, parent_id, label, color, sort_order)
VALUES
 ('event-category-webinar',       @parent_event_category, 'Webinaire',          NULL, 1),
 ('event-category-workshop',      @parent_event_category, 'Atelier pratique',   NULL, 2),
 ('event-category-conference',    @parent_event_category, 'Conférence',         NULL, 3),
 ('event-category-masterclass',   @parent_event_category, 'Masterclass',        NULL, 4),
 ('event-category-visit',         @parent_event_category, 'Visite guidée',      NULL, 5),
 ('event-category-networking',    @parent_event_category, 'Networking',         NULL, 6);