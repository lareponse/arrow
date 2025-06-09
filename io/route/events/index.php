<?php

/**
 * Events index route - Display list of events
 */
return function ($quest) {
    // Load event mapper functions
    require_once 'app/mapper/event.php';

    // Get upcoming events
    $upcoming_events = events_get_upcoming(5);

    // Get past events
    return [
        'payload' => [
            'title' => 'Events - copro.academy',
            'upcoming_events' => $upcoming_events,
            'past_events' => $past_events
        ]
    ];
};
