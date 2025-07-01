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
        if ($status === 'active') {
            $conditions['enabled_at IS NOT'] = null;
        } elseif ($status === 'inactive') {
            $conditions['enabled_at'] = null;
        }
    }

    $trainers = map_list('trainer', $conditions, [
        'limit' => $limit,
        'offset' => $offset,
        'order' => 'label ASC'
    ]);

    // Add training counts for each trainer
    foreach ($trainers as &$trainer) {
        $training_count = dbq(db(), "
            SELECT COUNT(*) as count
            FROM training 
            WHERE trainer_id = ? AND revoked_at IS NULL
        ", [$trainer['id']])->fetch();
        $trainer['training_count'] = $training_count['count'] ?? 0;
    }

    $total = map_list('trainer', $conditions, ['limit' => null]);
    $total_pages = ceil(count($total) / $limit);

    return [
        'title' => 'Formateurs',
        'trainers' => $trainers,
        'search' => $search,
        'current_status' => $status,
    ];
};
