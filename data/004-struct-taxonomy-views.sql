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