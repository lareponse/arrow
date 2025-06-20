<?php
// io/route/admin/article/alter.php

require_once 'app/mapper/taxonomy.php';
require_once 'add/bad/dad/db_row.php';

return function ($slug = null) {
    $article = [];

    $slug = $slug[0] ?: null;
    $row = row_innit('article');

    if ($slug) {
        $row = row($row, ROW_RELOAD, ['slug' => $slug]);
        $article = dbq(db(), "SELECT * FROM article WHERE slug = ? AND revoked_at IS NULL", [$slug])->fetch();
        if (!$article) {
            throw new DomainException('Article not found', 404);
        }
    }
    vd($article);
    // Handle POST submission
    if (!empty($_POST)) {

        $clean = $_POST;
        $clean['category_id'] = tag_id_by_slug($_POST['category_slug'], 'article-categorie') ?: null;
        $clean['reading_time'] = (int)($_POST['reading_time'] ?? 0) ?: null;
        $clean['featured'] = (int)!empty($_POST['featured']);

        $row = row($row, ROW_FIELD);
        $row = row($row, ROW_IMPORT, $clean);
        $row = row($row, ROW_PERSIST | ROW_RELOAD);
        http_out(302, '', ['Location' => "/admin/article/alter/". $row[ROW_SAVED]['slug']]);
        
        // // Handle file upload
        // if (!empty($_FILES['avatar']['tmp_name'])) {
        //     $upload = handle_image_upload($_FILES['avatar'], 'articles');
        //     if ($upload) {
        //         $data['avatar'] = $upload;
        //     }
        // }

        // // Handle delete action
        // if (($_POST['action'] ?? '') === 'delete' && $id) {
        //     dbq(db(), "UPDATE article SET revoked_at = NOW() WHERE id = ?", [$id]);
        //     http_out(302, '', ['Location' => '/admin/article/list']);
        // }

        // // Insert or update
        // if ($id && !empty($_POST['id'])) {
        //     // Update existing
        //     $update_data = $data;

        //     // Handle publication status
        //     if (!empty($_POST['published']) && !$article['enabled_at']) {
        //         $update_data['enabled_at'] = date('Y-m-d H:i:s');
        //     } elseif (empty($_POST['published']) && $article['enabled_at']) {
        //         $update_data['enabled_at'] = null;
        //     }

        //     [$sql, $binds] = qb_update('article', $update_data, ['id' => $id]);
        //     dbq(db(), $sql, $binds);

        //     http_out(302, '', ['Location' => "/admin/article/alter/$id"]);
        // } else {
        //     // Insert new
        //     if (!empty($_POST['published'])) {
        //         $data['enabled_at'] = date('Y-m-d H:i:s');
        //     }

        //     [$sql, $binds] = qb_create('article', null, $data);
        //     dbq(db(), $sql, $binds);
        //     $new_id = db()->lastInsertId();

        //     http_out(302, '', ['Location' => "/admin/article/alter/$new_id"]);
        // }
    }

    return [
        'title' => $id ? "Modifier l'article - {$article['label']}" : 'Nouvel article',
        'article' => $article,
        'categories' => (tag_by_parent('article-categorie')),
    ];
};

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
