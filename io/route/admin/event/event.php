<?php
require_once 'app/mapper/mapper.php';

return function ($args) {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $conditions = [];
    if ($search = $_GET['q'] ?? '') {
        $conditions = ['label LIKE' => "%$search%"];
    }

    if ($status = $_GET['status'] ?? '') {
        if ($status === 'upcoming') {
            $conditions['event_date >='] = date('Y-m-d H:i:s');
        } elseif ($status === 'past') {
            $conditions['event_date <'] = date('Y-m-d H:i:s');
        } elseif ($status === 'published') {
            $conditions['enabled_at IS NOT'] = null;
        } elseif ($status === 'draft') {
            $conditions['enabled_at'] = null;
        }
    }

    $events = map_with_taxonomy('event', ['category_id' => 'category'], $conditions, [
        'limit' => $limit,
        'offset' => $offset,
        'order' => 'event_date DESC'
    ]);

    // Add booking counts
    foreach ($events as &$event) {
        $bookings = dbq(db(), "
            SELECT COUNT(*) as count
            FROM booking 
            WHERE event_id = ? AND revoked_at IS NULL
        ", [$event['id']])->fetch();
        $event['bookings_count'] = $bookings['count'] ?? 0;
    }

    $total = map_list('event', $conditions, ['limit' => null]);
    $total_pages = ceil(count($total) / $limit);

    return [
        'title' => 'Événements',
        'events' => $events,
        'search' => $search,
        'current_status' => $status,
        'pagination' => compact('page', 'total_pages'),
    ];
};
