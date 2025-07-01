<?php

// /admin/upload/xxxx

return function ($args = null) {
    $upload_to = implode(DIRECTORY_SEPARATOR, $args);
    $base_file = $_SERVER['DOCUMENT_ROOT'] . "/asset/image/$upload_to";
    $result = upload_image($_FILES['avatar'], $base_file);
    if (!$result)
        http_out(400, json_encode(['success' => false, 'error' => 'Invalid file upload']), ['Content-Type' => 'application/json']);

    header('Content-Type: application/json');

    // remove $_SERVER['DOCUMENT_ROOT'] from the result path
    $result = str_replace($_SERVER['DOCUMENT_ROOT'], '', $result);

    echo json_encode(['success' => !!$result, 'url' => $result]);
    die;
};


function upload_image(array $http_post_file, string $abolute_file_path_no_ext, int $max_kb = 2048): ?string
{
    $map ??= [
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/webp' => 'webp'
    ];

    if (empty($http_post_file['tmp_name'])
        || !empty($http_post_file['error'])
        || !isset($http_post_file['type'])
        || !isset($http_post_file['size'])
        || !is_uploaded_file($http_post_file['tmp_name'])
        || $http_post_file['size'] === 0
        || $http_post_file['size'] > $max_kb * 1024
    ) return null;

    // Verify actual file type
    $finfo = finfo_open(FILEINFO_MIME_TYPE);
    if (finfo_file($finfo, $http_post_file['tmp_name']) !== $http_post_file['type']) return null;

    // Unsupported file type
    $ext = $map[$http_post_file['type']] ?? null;
    if (!$ext) return null;

    // get the folder from the absolute file path
    $folder = dirname($abolute_file_path_no_ext);
    is_dir($folder) || mkdir($folder, 0755, true);

    // Generate filename from original name

    $target = preg_replace('#\/\/+#', '/', "{$abolute_file_path_no_ext}");
    $candidate = "{$target}.{$ext}";
    // Rename existing file if it exists
    if (file_exists($candidate)) {
        $timestamp = (int)(microtime(true) * 1000000);
        if(!rename($candidate, "{$target}-{$timestamp}.{$ext}"))
            return null; // Failed to rename existing file
    }
    return move_uploaded_file($http_post_file['tmp_name'], $candidate) ? $candidate : null;
}
