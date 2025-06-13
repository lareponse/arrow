<?php

/**
 * Admin articles list route
 */
return function ($quest) {

    require_once 'app/mapper/article.php';

    $page = max(1, (int)($_GET['page'] ?? 1));
    $status = $_GET['status'] ?? 'all';
    $limit = 20;
    $offset = ($page - 1) * $limit;
    // Build WHERE clause
    $where_conditions = [];
    $params = [];

    if ($status !== 'all') {
        $where_conditions[] = "a.status = ?";
        $params[] = $status;
    }

    $where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    // Get articles
    $articles = dbq(db(), 
        "SELECT a.*, u.full_name as author_name, u.username
         FROM articles a
         JOIN users u ON a.user_id = u.id
         {$where_clause}
         ORDER BY a.created_at DESC
         LIMIT {$limit} OFFSET {$offset}",
        $params
    )->fetchAll();
    // Get total count
    $total = dbq(db(), 
        "SELECT COUNT(*) FROM articles a {$where_clause}",
        $params
    )->fetchColumn();


    $total_pages = (int) ceil($total / $limit);
    return [
        'payload' => [
            'title' => 'Manage Articles - Admin',
            'articles' => $articles,
            'current_status' => $status,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ]
    ];
};
