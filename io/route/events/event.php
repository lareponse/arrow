<?php

/**
 * Event detail route
 */
return function ($slug) {
    // Load event mapper functions
    require_once 'app/mapper/event.php';

    // Get event by slug
    $event = event_get_by_slug($slug);

    if (!$event) {
        throw new DomainException('Event not found', 404);
    }

    return [
        'payload' => [
            'title' => $event['title'] . ' - copro.academy',
            'event' => $event
        ],
    ];
};
