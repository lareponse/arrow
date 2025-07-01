<?php

// /admin/upload/xxxx

return function($args=null){
    $upload_to = implode(DIRECTORY_SEPARATOR, $args);
    $result = upload_image($_FILES['avatar'], $_SERVER['DOCUMENT_ROOT'] . "/asset/image/$upload_to/");
    header('Content-Type: application/json');
    echo json_encode(['success' => !!$result, 'url' => $result]);
    die;
};


function upload_image(array $file, string $folder, int $max_kb = 2048, ?array $map = null): ?string
{
    $map ??= [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];
    if ($file['error'] || $file['size'] > $max_kb * 1024) return null;

    // Verify actual file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (finfo_file($finfo, $file['tmp_name']) !== $file['type']) return null;

    // Unsupported file type
    $ext = $map[$file['type']] ?? null;
    if (!$ext) return null;

    is_dir($folder) || mkdir($folder, 0755, true);

    // Generate filename from original name
    $base = preg_replace('/[^\w-]/', '', pathinfo($file['name'], PATHINFO_FILENAME)) ?: 'upload';
    $filename = "$base.$ext";
    $target = "$folder/$filename";

    // Rename existing file if it exists
    if (file_exists($target)) {
        $timestamp = (int)(microtime(true) * 1000000);
        $old_target = "$folder/$base-$timestamp.$ext";
        rename($target, $old_target);
    }

    return move_uploaded_file($file['tmp_name'], $target) ? $target : null;
}
