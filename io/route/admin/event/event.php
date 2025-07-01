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

    return [
        'title' => 'Événements',
        'events' => dbq(db(), 'SELECT * FROM event_plus ORDER BY event_date DESC')->fetchAll(),

        // 'search' => $search,
        'current_status' => $status
    ];
};
