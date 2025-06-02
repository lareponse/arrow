<?php

return function ($quest, $request) {
    require_once 'app/mapper/user.php';

    $page = max(1, (int)($_GET['page'] ?? 1));
    $status = $_GET['status'] ?? 'all';
    $role = $_GET['role'] ?? 'all';
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $where_conditions = [];
    $params = [];

    if ($status !== 'all') {
        $where_conditions[] = "status = ?";
        $params[] = $status;
    }

    if ($role !== 'all') {
        $where_conditions[] = "role = ?";
        $params[] = $role;
    }

    $where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    $users = dbq(
        "SELECT id, username, email, full_name, role, status, created_at
         FROM users
         {$where_clause}
         ORDER BY created_at DESC
         LIMIT {$limit} OFFSET {$offset}",
        $params
    )->fetchAll();

    $total = dbq(
        "SELECT COUNT(*) FROM users {$where_clause}",
        $params
    )->fetchColumn();

    $total_pages = ceil($total / $limit);

    return [
        'status' => 200,
        'body' => render([
            'title' => 'Manage Users - Admin',
            'users' => $users,
            'current_status' => $status,
            'current_role' => $role,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ], __FILE__)
    ];
};