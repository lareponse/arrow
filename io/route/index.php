<?php
return function ($quest) {
    require_once 'app/mapper/article.php';
    require_once 'app/mapper/event.php';

    // Get recent articles
    $recent_articles = articles_get_published(3);
    // vd('multiwhoami', 2, whoami(), auth_http(), auth_token('auth_token'));

    // Get upcoming events  
    $upcoming_events = events_get_upcoming(3);

    return [
        'payload' => [
            'title' => 'Welcome to copro.academy',
            'recent_articles' => $recent_articles,
            'upcoming_events' => $upcoming_events
        ]
    ];
};
