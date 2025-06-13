<?php

/**
 * Admin events listing route
 */
return function ($response, ...$args) {

    require_once 'app/mapper/event.php';
    $page = max(1, (int)($_GET['page'] ?? 1));
    $status = $_GET['status'] ?? 'all';
    $limit = 20;
    $offset = ($page - 1) * $limit;

    // Build WHERE clause
    $where_conditions = [];
    $params = [];

    if ($status !== 'all') {
        $where_conditions[] = "e.status = ?";
        $params[] = $status;
    }

    $where_clause = $where_conditions ? 'WHERE ' . implode(' AND ', $where_conditions) : '';

    // Get events with organizer information
    $events = dbq(db(), 
        "SELECT e.*, u.full_name as organizer_name, u.username
         FROM events e
         JOIN users u ON e.user_id = u.id
         {$where_clause}
         ORDER BY e.start_datetime DESC
         LIMIT {$limit} OFFSET {$offset}",
        $params
    )->fetchAll();

    // Get total count for pagination
    $total = dbq(db(), 
        "SELECT COUNT(*) FROM events e {$where_clause}",
        $params
    )->fetchColumn();

    $total_pages = ceil($total / $limit);

    return [
        'payload' => [
            'title' => 'Manage Events - Admin',
            'events' => $events,
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