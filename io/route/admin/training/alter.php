<?php
require_once 'app/mapper/taxonomy.php';
require_once 'add/bad/dad/db_row.php';

return function ($slug = null) {
    $slug = $slug[0] ?: null;
    $training = row(db(), 'training');

    if ($slug) {
        $training(ROW_LOAD, ['slug' => $slug]);
        if (!$training(ROW_GET)) {
            http_out(404, 'Training not found');
        }
    }

    if (!empty($_POST)) {
        $clean = $_POST;

        // Handle taxonomy relationships
        $clean['level_id'] = tag_id_by_slug($_POST['level_slug'], 'formation-niveau') ?: null;

        // Handle trainer relationship
        if (!empty($_POST['trainer_id'])) {
            $trainer_exists = dbq(db(), "SELECT id FROM trainer WHERE id = ? AND revoked_at IS NULL", [$_POST['trainer_id']])->fetch();
            $clean['trainer_id'] = $trainer_exists ? (int)$_POST['trainer_id'] : null;
        } else {
            $clean['trainer_id'] = null;
        }

        // Convert numeric fields
        $clean['duration_days'] = max(1, (int)($_POST['duration_days'] ?? 1));
        $clean['duration_hours'] = max(1, (int)($_POST['duration_hours'] ?? 7));
        $clean['price_ht'] = max(0, (float)($_POST['price_ht'] ?? 0));
        $clean['places_max'] = (int)($_POST['places_max'] ?? 0) ?: null;
        $clean['certification'] = (int)!empty($_POST['certification']);

        // Handle publication
        if (!empty($_POST['published']) && empty($training(ROW_GET, ['enabled_at']))) {
            $clean['enabled_at'] = date('Y-m-d H:i:s');
        } elseif (empty($_POST['published'])) {
            $clean['enabled_at'] = null;
        }

        // Handle file upload
        if (!empty($_FILES['avatar']['name'])) {
            $clean['avatar'] = upload($_FILES['avatar'], $_SERVER['DOCUMENT_ROOT'] . '/asset/image/training/');
        }

        $training(ROW_FIELDS);
        $training(ROW_SET, $clean);
        $training(ROW_SAVE);
        $training(ROW_LOAD);

        http_out(302, '', ['Location' => "/admin/training/alter/" . $training(ROW_GET, ['slug'])]);
    }

    // Get trainers for dropdown
    $trainers = dbq(db(), "
        SELECT id, label, email 
        FROM trainer 
        WHERE revoked_at IS NULL 
        ORDER BY label
    ")->fetchAll();

    return [
        'title' => $slug ? "Modifier la formation" : 'Nouvelle formation',
        'training' => $training(ROW_GET),
        'levels' => tag_by_parent('formation-niveau'),
        'trainers' => $trainers,
    ];
};

function upload(array $file, string $folder): ?string
{
    if ($file['error'] !== UPLOAD_ERR_OK || $file['size'] > 2 * 1024 * 1024) {
        return null;
    }

    $allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!in_array($file['type'], $allowed)) {
        return null;
    }

    $ext = match ($file['type']) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp',
    };

    $filename = uniqid() . '.' . $ext;
    if (!is_dir($folder)) mkdir($folder, 0755, true);

    $target = "$folder/$filename";
    return move_uploaded_file($file['tmp_name'], $target) ? $target : null;
}
