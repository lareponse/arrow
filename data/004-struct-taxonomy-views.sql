DROP VIEW IF EXISTS taxonomy_with_parent;
CREATE VIEW taxonomy_with_parent AS
SELECT
  c.id        AS id,
  c.slug,
  c.label,
  c.sort_order,
  c.enabled_at,

  c.parent_id AS parent_id,
  p.slug      AS parent_slug,
  p.label     AS parent_label

FROM taxonomy AS c
JOIN taxonomy AS p ON c.parent_id = p.id
WHERE c.revoked_at IS NULL;

DROP VIEW IF EXISTS article_with_cateogry;
CREATE VIEW article_with_cateogry AS
SELECT
  a.*,
  c.slug      AS category_slug,
  c.label     AS category_label
FROM article AS a
JOIN taxonomy_with_parent AS c ON a.category_id = c.id
WHERE a.revoked_at IS NULL;

DROP VIEW IF EXISTS training_with_level_trainer;
CREATE VIEW training_with_level_trainer AS
SELECT
  training.*,
  l.slug      AS level_slug,
  l.label     AS level_label,
  trainer.slug      AS trainer_slug,
  trainer.label     AS trainer_label
FROM training
JOIN taxonomy_with_parent AS l ON training.level_id = l.id
JOIN trainer ON training.trainer_id = trainer.id
WHERE training.revoked_at IS NULL;