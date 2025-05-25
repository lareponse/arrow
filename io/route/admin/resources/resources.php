<?php

return function () {
    require_once request()['root'] . '/mapper/resource.php';

    $page = max(1, (int)($_GET['page'] ?? 1));
    $status = $_GET['status'] ?? 'all';
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $where_conditions = [];
    $params = [];

    if ($status !== 'all') {
        $where_conditions[] = "r.status = ?";
        $params[] = $status;
    }

    $where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    $resources = pdo(
        "SELECT r.*, u.full_name as uploader_name, u.username
         FROM resources r
         JOIN users u ON r.user_id = u.id
         {$where_clause}
         ORDER BY r.created_at DESC
         LIMIT {$limit} OFFSET {$offset}",
        $params
    )->fetchAll();

    $total = pdo(
        "SELECT COUNT(*) FROM resources r {$where_clause}",
        $params
    )->fetchColumn();

    $total_pages = ceil($total / $limit);

    return [
        'status' => 200,
        'body' => render([
            'title' => 'Manage Resources - Admin',
            'resources' => $resources,
            'current_status' => $status,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ], __FILE__)
    ];
};