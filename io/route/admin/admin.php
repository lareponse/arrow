<?php

/**
 * Admin dashboard route
 */
return function () {
    require_once request()['root'] . '/mapper/article.php';
    require_once request()['root'] . '/mapper/event.php';
    require_once request()['root'] . '/mapper/resource.php';
    require_once request()['root'] . '/mapper/user.php';

    // Get dashboard statistics
    $stats = [
        'articles' => [
            'total' => pdo("SELECT COUNT(*) FROM articles")->fetchColumn(),
            'published' => pdo("SELECT COUNT(*) FROM articles WHERE status = 'published'")->fetchColumn(),
            'draft' => pdo("SELECT COUNT(*) FROM articles WHERE status = 'draft'")->fetchColumn()
        ],
        'events' => [
            'total' => pdo("SELECT COUNT(*) FROM events")->fetchColumn(),
            'upcoming' => pdo("SELECT COUNT(*) FROM events WHERE status = 'published' AND start_datetime > NOW()")->fetchColumn(),
            'past' => pdo("SELECT COUNT(*) FROM events WHERE status = 'published' AND end_datetime < NOW()")->fetchColumn()
        ],
        'resources' => [
            'total' => pdo("SELECT COUNT(*) FROM resources")->fetchColumn(),
            'published' => pdo("SELECT COUNT(*) FROM resources WHERE status = 'published'")->fetchColumn()
        ],
        'users' => [
            'total' => pdo("SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn(),
            'admins' => pdo("SELECT COUNT(*) FROM users WHERE role IN ('admin', 'editor') AND status = 'active'")->fetchColumn()
        ]
    ];

    // Recent activity
    $recent_articles = pdo(
        "SELECT a.id, a.title, a.status, a.created_at, u.full_name as author
         FROM articles a
         JOIN users u ON a.user_id = u.id
         ORDER BY a.created_at DESC
         LIMIT 5"
    )->fetchAll();

    $recent_events = pdo(
        "SELECT e.id, e.title, e.start_datetime, e.status, u.full_name as organizer
         FROM events e
         JOIN users u ON e.user_id = u.id
         ORDER BY e.created_at DESC
         LIMIT 5"
    )->fetchAll();

    $recent_resources = pdo(
        "SELECT r.id, r.title, r.file_type, r.status, r.created_at, u.full_name as uploader
         FROM resources r
         JOIN users u ON r.user_id = u.id
         ORDER BY r.created_at DESC
         LIMIT 5"
    )->fetchAll();

    return [
        'status' => 200,
        'body' => render([
            'title' => 'Admin Dashboard - copro.academy',
            'stats' => $stats,
            'recent_articles' => $recent_articles,
            'recent_events' => $recent_events,
            'recent_resources' => $recent_resources
        ], __FILE__)
    ];
};
