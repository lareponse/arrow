<?php
require_once 'add/bad/qb.php';

/**
 * Resource data mapper functions
 */

/**
 * Get all published resources
 * 
 * @param int $limit Maximum number of resources to return
 * @param int $offset Starting position
 * @return array Resources data
 */
function resources_get_all(int $limit = 10, int $offset = 0)
{
    return dbq(
        "SELECT r.*, u.username as uploader
         FROM resources r
         JOIN users u ON r.user_id = u.id
         WHERE r.status = 'published'
         ORDER BY r.created_at DESC
         LIMIT $limit OFFSET $offset")->fetchAll();
}

/**
 * Get published resource by slug
 * 
 * @param string $slug Resource slug
 * @return array|false Resource data or false if not found
 */
function resource_get_by_slug($slug)
{
    return dbq(
        "SELECT r.*, u.username as uploader, u.full_name as uploader_name
         FROM resources r
         JOIN users u ON r.user_id = u.id
         WHERE r.slug = ? AND r.status = 'published'",
        [$slug]
    )->fetch();
}

/**
 * Create a new resource
 * 
 * @param array $data Resource data
 * @return int|false New resource ID or false on failure
 */
function resource_create($data)
{
    $insert_data = [
        'title' => $data['title'],
        'slug' => $data['slug'],
        'description' => $data['description'] ?? null,
        'file_path' => $data['file_path'],
        'file_type' => $data['file_type'],
        'file_size' => $data['file_size'],
        'status' => $data['status'] ?? 'draft',
        'user_id' => $data['user_id'],
    ];
    $stmt = dbq(...qb_create('resources', $insert_data));

    return $stmt->rowCount() > 0 ? db()->lastInsertId() : false;
}

/**
 * Update an existing resource
 * 
 * @param int $id Resource ID
 * @param array $data Updated resource data
 * @return bool Success status
 */
function resource_update($id, $data)
{
    $updateData = [];

    foreach (
        [
            'title',
            'slug',
            'description',
            'file_path',
            'file_type',
            'file_size',
            'status'
        ] as $field
    ) {
        if (isset($data[$field])) {
            $updateData[$field] = $data[$field];
        }
    }

    if (empty($updateData)) {
        return false;
    }
    $stmt = dbq(...qb_update('resources', $updateData, 'id = ?', [$id]));
    return $stmt->rowCount() > 0;
}

/**
 * Delete a resource
 * 
 * @param int $id Resource ID
 * @return bool Success status
 */
function resource_delete($id)
{
    // Get file path before deleting record
    $resource = dbq("SELECT file_path FROM resources WHERE id = ?", [$id])->fetch();

    if (!$resource) {
        return false;
    }

    $stmt = dbq("DELETE FROM resources WHERE id = ?", [$id]);

    if ($stmt->rowCount() > 0) {
        // Delete physical file
        $file_path = __DIR__ . '/../public/' . $resource['file_path'];
        if (file_exists($file_path)) {
            unlink($file_path);
        }
        return true;
    }

    return false;
}
