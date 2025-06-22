<?php

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
