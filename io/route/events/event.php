<?php

/**
 * Event detail route
 */
return function ($slug) {
    // Load event mapper functions
    require_once __DIR__ . '/../../../add/event_mapper.php';

    // Get event by slug
    $event = event_get_by_slug($slug);

    if (!$event) {
        trigger_error('404 Not Found: Event not found', E_USER_ERROR);
    }

    return [
        'status' => 200,
        'body' => render('events/event', [
            'title' => $event['title'] . ' - copro.academy',
            'event' => $event
        ])
    ];
};
