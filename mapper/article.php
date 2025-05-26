<?php

/**
 * Article data mapper functions
 */

/**
 * Get all published articles
 * 
 * @param int $limit Maximum number of articles to return
 * @param int $offset Starting position
 * @return array Articles data
 */
function articles_get_published(int $limit = 10, int $offset = 0): array
{
    // limit and offset should be positive integers
    if ($limit < 1 || $offset < 0) {
        return [];
    }
    $sql = "SELECT a.*, u.username as author, u.full_name as author_name
            FROM articles a
            LEFT JOIN users u ON a.user_id = u.id
            WHERE a.status = 'published'
            ORDER BY a.created_at DESC
            LIMIT {$limit} OFFSET {$offset}"; // typed parameters prevent SQL injection

    return pdo($sql)->fetchAll();
}

function articles_count_published()
{
    $q = "SELECT COUNT(*) FROM articles WHERE status = 'published'";
    return (int)pdo($q)->fetchColumn();
}
/**
 * Get published article by slug
 * 
 * @param string $slug Article slug
 * @return array|false Article data or false if not found
 */
function article_get_by_slug($slug)
{
    $article = pdo(
        "SELECT a.*, u.username as author, u.full_name as author_name
         FROM articles a
         JOIN users u ON a.user_id = u.id
         WHERE a.slug = ? AND a.status = 'published'",
        [$slug]
    )->fetch();

    if (!$article) {
        return false;
    }

    // Get article categories
    $article['categories'] = pdo(
        "SELECT c.id, c.name, c.slug
         FROM categories c
         JOIN article_category ac ON c.id = ac.category_id
         WHERE ac.article_id = ?",
        [$article['id']]
    )->fetchAll();

    return $article;
}

/**
 * Create a new article
 * 
 * @param array $data Article data
 * @return int|false New article ID or false on failure
 */
function article_create($data)
{
    return pdo(function () use ($data) {

        $insert_data = [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? null,
            'image_url' => $data['image_url'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'user_id' => $data['user_id'],
        ];
        $stmt = pdo(...qb_create('articles', $insert_data));

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $article_id = pdo()->lastInsertId();

        // Add categories if provided
        if (!empty($data['categories'])) {
            foreach ($data['categories'] as $category_id) {
                $stmt = pdo(...qb_create('article_category', ['article_id' => $article_id, 'category_id' => $category_id]));
            }
        }

        return $article_id;
    });
}

/**
 * Update an existing article
 * 
 * @param int $id Article ID
 * @param array $data Updated article data
 * @return bool Success status
 */
function article_update($id, $data)
{
    return pdo(function () use ($id, $data) {
        $updateData = [];

        foreach (['title', 'slug', 'content', 'excerpt', 'image_url', 'status'] as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            return false;
        }
        $stmt = pdo(...qb_update('articles', $updateData, 'id = ?', [$id]));

        if ($stmt->rowCount() === 0) {
            return false;
        }

        // Update categories if provided
        if (isset($data['categories'])) {
            // Remove existing categories
            pdo("DELETE FROM article_category WHERE article_id = ?", [$id]);

            // Add new categories
            foreach ($data['categories'] as $category_id)
                $stmt = pdo(...qb_create('article_category', ['article_id' => $id, 'category_id' => $category_id]));
        }

        return true;
    });
}

/**
 * Delete an article
 * 
 * @param int $id Article ID
 * @return bool Success status
 */
function article_delete(int $id)
{
    return pdo(function () use ($id) {
        // Categories will be deleted via ON DELETE CASCADE
        $stmt = pdo("DELETE FROM articles WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    });
}
