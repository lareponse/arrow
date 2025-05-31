<?php
return function () {
    require_once 'app/mapper/article.php';
    require_once 'app/mapper/event.php';

    // Get recent articles
    $recent_articles = articles_get_published(3);

    // Get upcoming events  
    $upcoming_events = events_get_upcoming(3);

    return [
        'status' => 200,
        'body' => render([
            'title' => 'Welcome to copro.academy',
            'recent_articles' => $recent_articles,
            'upcoming_events' => $upcoming_events
        ])
    ];
};
