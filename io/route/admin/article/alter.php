<?php
// io/route/admin/article/alter.php

return function ($args = null) {
    $article = [];
    $id = $args['slug'] ?: null;

    if ($id) {
        $article = dbq(db(), "SELECT * FROM article WHERE id = ? AND revoked_at IS NULL", [$id])->fetch();
        if (!$article) {
            throw new DomainException('Article not found', 404);
        }
    }

    // Handle POST submission
    if (!empty($_POST)) {
        $data = [
            'label' => trim($_POST['label'] ?? ''),
            'summary' => trim($_POST['summary'] ?? ''),
            'content' => trim($_POST['content'] ?? ''),
            'category_id' => (int)($_POST['category_id'] ?? 0) ?: null,
            'reading_time' => (int)($_POST['reading_time'] ?? 0) ?: null,
            'featured' => !empty($_POST['featured']),
        ];


        // Auto-generate slug if needed
        if (!$id || empty($article['slug'])) {
            $data['slug'] = generate_slug($data['label'], 'article');
        }

        // Handle file upload
        if (!empty($_FILES['avatar']['tmp_name'])) {
            $upload = handle_image_upload($_FILES['avatar'], 'articles');
            if ($upload) {
                $data['avatar'] = $upload;
            }
        }

        // Handle delete action
        if (($_POST['action'] ?? '') === 'delete' && $id) {
            dbq(db(), "UPDATE article SET revoked_at = NOW() WHERE id = ?", [$id]);
            http_out(302, '', ['Location' => '/admin/article/list']);
        }

        // Insert or update
        if ($id && !empty($_POST['id'])) {
            // Update existing
            $update_data = $data;

            // Handle publication status
            if (!empty($_POST['published']) && !$article['enabled_at']) {
                $update_data['enabled_at'] = date('Y-m-d H:i:s');
            } elseif (empty($_POST['published']) && $article['enabled_at']) {
                $update_data['enabled_at'] = null;
            }

            [$sql, $binds] = qb_update('article', $update_data, ['id' => $id]);
            dbq(db(), $sql, $binds);

            http_out(302, '', ['Location' => "/admin/article/alter/$id"]);
        } else {
            // Insert new
            if (!empty($_POST['published'])) {
                $data['enabled_at'] = date('Y-m-d H:i:s');
            }

            [$sql, $binds] = qb_create('article', null, $data);
            dbq(db(), $sql, $binds);
            $new_id = db()->lastInsertId();

            http_out(302, '', ['Location' => "/admin/article/alter/$new_id"]);
        }
    }

    return [
        'title' => $id ? "Modifier l'article - {$article['label']}" : 'Nouvel article',
        'article' => $article,
        'categories' => tag_by_parent('article-categorie'),
    ];
};


function generate_slug(string $text, ?string $table = null): string
{
    // Basic slug generation
    $slug = strtolower(trim($text));
    $slug = preg_replace('/[^a-z0-9-]/', '-', $slug);
    $slug = preg_replace('/-+/', '-', $slug);
    $slug = trim($slug, '-');

    // Ensure uniqueness if table provided
    if ($table) {
        $base_slug = $slug;
        $counter = 1;

        while (dbq(db(), "SELECT 1 FROM $table WHERE slug = ? AND revoked_at IS NULL", [$slug])->fetchColumn()) {
            $slug = $base_slug . '-' . $counter++;
        }
    }

    return $slug;
}

function handle_image_upload(array $file, string $folder): ?string
{
    // Basic validation
    if ($file['error'] !== UPLOAD_ERR_OK) {
        return null;
    }

    if ($file['size'] > 2 * 1024 * 1024) { // 2MB
        throw new InvalidArgumentException('Image too large', 400);
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        throw new InvalidArgumentException('Invalid image type', 400);
    }

    // Generate filename
    $ext = match ($file['type']) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    };

    $filename = uniqid() . '.' . $ext;
    $upload_dir = $_SERVER['DOCUMENT_ROOT'] . "/uploads/$folder";

    if (!is_dir($upload_dir)) {
        mkdir($upload_dir, 0755, true);
    }

    $target = "$upload_dir/$filename";

    if (move_uploaded_file($file['tmp_name'], $target)) {
        return "/uploads/$folder/$filename";
    }

    return null;
}
