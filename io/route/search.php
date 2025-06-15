<?php

return function ($quest) {
    $query = trim($_GET['q'] ?? '');
    $results = [];

    if ($query) {
        require_once 'app/mapper/article.php';
        require_once 'app/mapper/event.php';
        require_once 'app/mapper/resource.php';

        // Search articles
        $articles = dbq(db(), 
            "SELECT 'article' as type, id, title, slug, excerpt as description, created_at
             FROM article 
             WHERE status = 'published' AND (title LIKE ? OR content LIKE ?)
             ORDER BY created_at DESC LIMIT 10",
            ["%{$query}%", "%{$query}%"]
        )->fetchAll();

        // Search events
        $events = dbq(db(), 
            "SELECT 'event' as type, id, title, slug, description, start_datetime as created_at
             FROM event 
             WHERE status = 'published' AND (title LIKE ? OR description LIKE ?)
             ORDER BY start_datetime DESC LIMIT 10",
            ["%{$query}%", "%{$query}%"]
        )->fetchAll();

        // Search resources
        $resources = dbq(db(), 
            "SELECT 'resource' as type, id, title, slug, description, created_at
             FROM resources 
             WHERE status = 'published' AND (title LIKE ? OR description LIKE ?)
             ORDER BY created_at DESC LIMIT 10",
            ["%{$query}%", "%{$query}%"]
        )->fetchAll();

        $results = array_merge($articles, $events, $resources);
    }

    return [
        'payload' => [
            'title' => $query ? "Search Results for '{$query}'" : 'Search - copro.academy',
            'query' => $query,
            'results' => $results
        ]
    ];
};