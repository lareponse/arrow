DROP VIEW IF EXISTS taxonomy_with_parent;
CREATE VIEW taxonomy_with_parent AS
SELECT
  p.slug      AS parent_slug,
  c.slug,
  p.label     AS parent_label,
  c.label,

  c.sort_order,
  c.enabled_at
FROM taxonomy AS c
JOIN taxonomy AS p ON c.parent_id = p.id
WHERE c.revoked_at IS NULL;