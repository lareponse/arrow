<?php

/**
 * Admin dashboard route
 */
return function (...$args) {
    require_once 'app/mapper/article.php';
    require_once 'app/mapper/event.php';
    require_once 'app/mapper/resource.php';
    require_once 'app/mapper/user.php';

    // Get dashboard statistics
    $stats = [
        'articles' => [
            'total' => dbq(db(), "SELECT COUNT(*) FROM articles")->fetchColumn(),
            'published' => dbq(db(), "SELECT COUNT(*) FROM articles WHERE status = 'published'")->fetchColumn(),
            'draft' => dbq(db(), "SELECT COUNT(*) FROM articles WHERE status = 'draft'")->fetchColumn()
        ],
        'events' => [
            'total' => dbq(db(), "SELECT COUNT(*) FROM events")->fetchColumn(),
            'upcoming' => dbq(db(), "SELECT COUNT(*) FROM events WHERE status = 'published' AND start_datetime > NOW()")->fetchColumn(),
            'past' => dbq(db(), "SELECT COUNT(*) FROM events WHERE status = 'published' AND end_datetime < NOW()")->fetchColumn()
        ],
        'resources' => [
            'total' => dbq(db(), "SELECT COUNT(*) FROM resources")->fetchColumn(),
            'published' => dbq(db(), "SELECT COUNT(*) FROM resources WHERE status = 'published'")->fetchColumn()
        ],
        'users' => [
            'total' => dbq(db(), "SELECT COUNT(*) FROM users WHERE status = 'active'")->fetchColumn(),
            'admins' => dbq(db(), "SELECT COUNT(*) FROM users WHERE role IN ('admin', 'editor') AND status = 'active'")->fetchColumn()
        ]
    ];

    // Recent activity
    $recent_articles = dbq(
        db(),
        "SELECT a.id, a.title, a.status, a.created_at, u.full_name as author
         FROM articles a
         JOIN users u ON a.user_id = u.id
         ORDER BY a.created_at DESC
         LIMIT 5"
    )->fetchAll();

    $recent_events = dbq(
        db(),
        "SELECT e.id, e.title, e.start_datetime, e.status, u.full_name as organizer
         FROM events e
         JOIN users u ON e.user_id = u.id
         ORDER BY e.created_at DESC
         LIMIT 5"
    )->fetchAll();

    $recent_resources = dbq(
        db(),
        "SELECT r.id, r.title, r.file_type, r.status, r.created_at, u.full_name as uploader
         FROM resources r
         JOIN users u ON r.user_id = u.id
         ORDER BY r.created_at DESC
         LIMIT 5"
    )->fetchAll();

    return [
        'title' => 'Admin Dashboard - copro.academy',
        'stats' => $stats,
        'recent_articles' => $recent_articles,
        'recent_events' => $recent_events,
        'recent_resources' => $recent_resources
    ];
};
