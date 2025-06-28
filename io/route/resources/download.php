<?php

/**
 * Resource download route
 */
return function ($slug) {
    // Load resource mapper functions
    require_once __DIR__ . '/../../../add/resource_mapper.php';
    require_once __DIR__ . '/../../../add/auth.php';

    // Get resource by slug
    $resource = resource_get_by_slug($slug);

    if (!$resource) {
        throw new DomainException('Resource not found', 404);
    }

    // Check if user is authenticated for protected resources
    if (isset($resource['is_protected']) && $resource['is_protected'] && !auth()) {
        // Redirect to login page
        header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }
    $data_insert = [
        'resource_id' => $resource['id'],
        'user_id' => auth(),
        'ip_address' => $_SERVER['REMOTE_ADDR'],
        'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? '',
        'download_time' => date('Y-m-d H:i:s')
    ];
    dbq(db(), ...qb_create('resource_downloads', null, $data_insert));

    // Get file path
    $file_path = __DIR__ . '/../../../public/' . $resource['file_path'];

    if (!file_exists($file_path)) {
        throw new DomainException('Resource file not found', 404);
    }

    // Set appropriate headers
    header('Content-Type: ' . $resource['file_type']);
    header('Content-Disposition: attachment; filename="' . basename($resource['file_path']) . '"');
    header('Content-Length: ' . $resource['file_size']);

    // Output file
    readfile($file_path);
    exit;
};
