<?php

/**
 * Admin article create/edit route
 */
return function ($id = null) {

    require_once 'app/mapper/article.php';

    $errors = [];
    $article = null;
    $is_edit = $id !== null;

    // Get current user
    $current_user = whoami();

    if (!$current_user) {
        throw new DomainException('Unauthorized', 401);
    }

    // If editing, fetch existing article
    if ($is_edit) {
        $article = dbq(
            "SELECT * FROM articles WHERE id = ?",
            [$id]
        )->fetch();

        if (!$article) {
            throw new DomainException('Article not found', 404);
        }

        // Get categories
        $article['categories'] = dbq(
            "SELECT category_id FROM article_category WHERE article_id = ?",
            [$id]
        )->fetchAll(PDO::FETCH_COLUMN);
    }

    // Get all categories
    $categories = dbq("SELECT * FROM categories ORDER BY name")->fetchAll();

    // Handle form submission
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $excerpt = trim($_POST['excerpt'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        $status = $_POST['status'] ?? 'draft';
        $selected_categories = $_POST['categories'] ?? [];

        // Validation
        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }

        if (empty($slug)) {
            $errors['slug'] = 'Slug is required';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors['slug'] = 'Slug must contain only lowercase letters, numbers, and hyphens';
        } else {
            // Check slug uniqueness
            $existing = dbq(
                "SELECT id FROM articles WHERE slug = ? AND id != ?",
                [$slug, $id ?? 0]
            )->fetch();
            if ($existing) {
                $errors['slug'] = 'Slug already exists';
            }
        }

        if (empty($content)) {
            $errors['content'] = 'Content is required';
        }

        if (!in_array($status, ['draft', 'published'])) {
            $errors['status'] = 'Invalid status';
        }

        // If no errors, save
        if (empty($errors)) {
            $data = [
                'title' => $title,
                'slug' => $slug,
                'content' => $content,
                'excerpt' => $excerpt ?: null,
                'image_url' => $image_url ?: null,
                'status' => $status,
                'categories' => array_map('intval', $selected_categories)
            ];

            if ($is_edit) {
                $success = article_update($id, $data);
                if ($success) {
                    header('Location: /admin/articles?success=updated');
                    exit;
                } else {
                    $errors['general'] = 'Failed to update article';
                }
            } else {
                $data['username'] = whoami();
                $article_id = article_create($data);
                if ($article_id) {
                    header('Location: /admin/articles?success=created');
                    exit;
                } else {
                    $errors['general'] = 'Failed to create article';
                }
            }
        }

        // Preserve form data on error
        $article = array_merge($article ?? [], [
            'title' => $title,
            'slug' => $slug,
            'content' => $content,
            'excerpt' => $excerpt,
            'image_url' => $image_url,
            'status' => $status,
            'categories' => $selected_categories
        ]);
    }

    return [
        'status' => 200,
        'body' => render([
            'title' => ($is_edit ? 'Edit Article' : 'Create Article') . ' - Admin',
            'article' => $article,
            'categories' => $categories,
            'errors' => $errors,
            'is_edit' => $is_edit
        ], __FILE__)
    ];
};
