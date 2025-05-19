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
function articles_get_all($limit = 10, $offset = 0)
{
    return db_state(
        "SELECT a.*, u.username as author
         FROM articles a
         JOIN users u ON a.user_id = u.id
         WHERE a.status = 'published'
         ORDER BY a.created_at DESC
         LIMIT ? OFFSET ?",
        [$limit, $offset]
    )->fetchAll();
}

/**
 * Get published article by slug
 * 
 * @param string $slug Article slug
 * @return array|false Article data or false if not found
 */
function article_get_by_slug($slug)
{
    $article = db_state(
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
    $article['categories'] = db_state(
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
    return db_transaction(function () use ($data) {
        $stmt = db_create('articles', [
            'title' => $data['title'],
            'slug' => $data['slug'],
            'content' => $data['content'],
            'excerpt' => $data['excerpt'] ?? null,
            'image_url' => $data['image_url'] ?? null,
            'status' => $data['status'] ?? 'draft',
            'user_id' => $data['user_id'],
        ]);

        if ($stmt->rowCount() === 0) {
            return false;
        }

        $article_id = db()->lastInsertId();

        // Add categories if provided
        if (!empty($data['categories'])) {
            foreach ($data['categories'] as $category_id) {
                db_create('article_category', [
                    'article_id' => $article_id,
                    'category_id' => $category_id
                ]);
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
    return db_transaction(function () use ($id, $data) {
        $updateData = [];

        foreach (['title', 'slug', 'content', 'excerpt', 'image_url', 'status'] as $field) {
            if (isset($data[$field])) {
                $updateData[$field] = $data[$field];
            }
        }

        if (empty($updateData)) {
            return false;
        }

        $stmt = db_update('articles', $updateData, 'id = ?', [$id]);

        if ($stmt->rowCount() === 0) {
            return false;
        }

        // Update categories if provided
        if (isset($data['categories'])) {
            // Remove existing categories
            db_state("DELETE FROM article_category WHERE article_id = ?", [$id]);

            // Add new categories
            foreach ($data['categories'] as $category_id) {
                db_create('article_category', [
                    'article_id' => $id,
                    'category_id' => $category_id
                ]);
            }
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
function article_delete($id)
{
    return db_transaction(function () use ($id) {
        // Categories will be deleted via ON DELETE CASCADE
        $stmt = db_state("DELETE FROM articles WHERE id = ?", [$id]);
        return $stmt->rowCount() > 0;
    });
}
