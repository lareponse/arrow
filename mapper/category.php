<?php
require_once 'add/bad/qb.php';

/**
 * Category data mapper functions
 */

function categories_get_all(): array
{
    return dbq("SELECT * FROM categories ORDER BY name")->fetchAll();
}

function category_get_by(string $field, $value)
{
    return dbq("SELECT * FROM categories WHERE {$field} = ?", [$value])->fetch();
}

function category_create(array $data)
{
    $insert_data = [
        'name' => $data['name'],
        'slug' => $data['slug'],
        'description' => $data['description'] ?? null,
    ];
    $stmt = dbq(...qb_create('categories', null, $insert_data));
    return $stmt->rowCount() > 0 ? db()->lastInsertId() : false;
}

function category_update(int $id, array $data): bool
{
    $update_data = [];
    foreach (['name', 'slug', 'description'] as $field) {
        if (isset($data[$field])) {
            $update_data[$field] = $data[$field];
        }
    }

    if (empty($update_data)) return false;

    $stmt = dbq(...qb_update('categories', $update_data, 'id = ?', [$id]));
    return $stmt->rowCount() > 0;
}

function category_delete(int $id): bool
{
    $stmt = dbq("DELETE FROM categories WHERE id = ?", [$id]);
    return $stmt->rowCount() > 0;
}
