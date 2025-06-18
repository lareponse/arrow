<?php
require_once 'add/bad/db.php';

function tag_by_parent(string $slug): array
{
    $sql = "SELECT slug, label FROM taxonomy_with_parent WHERE parent_slug = ? ORDER BY sort_order, label";
    return dbq(db(), $sql, [$slug])->fetchAll(PDO::FETCH_KEY_PAIR);
}