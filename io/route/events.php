<?php

/**
 * Events index route - Display list of events
 */
return function () {
    // Load event mapper functions
    require_once __DIR__ . '/../../add/event_mapper.php';

    // Get upcoming events
    $upcoming_events = events_get_upcoming(5);

    // Get past events
    $past_events = events_get_past(5);

    return [
        'status' => 200,
        'body' => render([
            'title' => 'Events - copro.academy',
            'upcoming_events' => $upcoming_events,
            'past_events' => $past_events
        ], 'layout')
    ];
};
