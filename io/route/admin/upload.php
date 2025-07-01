<?php

// /admin/upload/xxxx

return function($args=null){
    $upload_to = implode(DIRECTORY_SEPARATOR, $args);
    $base_folder = $_SERVER['DOCUMENT_ROOT'] . "/asset/image/$upload_to/";
    $result = upload_image($_FILES['avatar'], $base_folder);
    if (!$result) 
        http_out(400, json_encode(['success' => false, 'error' => 'Invalid file upload']), ['Content-Type' => 'application/json']);
    
    header('Content-Type: application/json');

    // remove $_SERVER['DOCUMENT_ROOT'] from the result path
    $result = str_replace($_SERVER['DOCUMENT_ROOT'], '', $result);

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
    $target = preg_replace('#\/\/+#', '/', "$folder/$filename");

    // Rename existing file if it exists
    if (file_exists($target)) {
        $timestamp = (int)(microtime(true) * 1000000);
        $old_target = "$folder/$base-$timestamp.$ext";
        rename($target, $old_target);
    }

    return move_uploaded_file($file['tmp_name'], $target) ? $target : null;
}
