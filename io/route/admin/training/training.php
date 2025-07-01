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

    $trainings = map_with_taxonomy('training', [
        'level_id' => 'level',
        'trainer_id' => 'trainer'
    ], $conditions, [
        'limit' => $limit,
        'offset' => $offset,
        'order' => 'start_date DESC'
    ]);

    $total = map_list('training', $conditions, ['limit' => null]);
    $total_pages = ceil(count($total) / $limit);

    return [
        'title' => 'Formations',
        'trainings' => $trainings,
        'search' => $search,
        'pagination' => compact('page', 'total_pages'),
    ];
};
