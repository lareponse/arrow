<?php
/**
 * Resources index route - Display list of resources
 */
return function() {
    // Load resource mapper functions
    require_once 'app/mapper/resource.php';
    
    // Get page number from query string
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = 12;
    $offset = ($page - 1) * $limit;
    
    // Get resources
    $resources = resources_get_all($limit, $offset);
    
    // Get total count for pagination
    $total = dbq("SELECT COUNT(*) FROM resources WHERE status = 'published'")->fetchColumn();
    $total_pages = ceil($total / $limit);
    
    return [
        'status' => 200,
        'body' => render([
            'title' => 'Resources - copro.academy',
            'resources' => $resources,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ])
    ];
};