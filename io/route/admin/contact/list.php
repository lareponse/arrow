<?php
require_once 'app/mapper/mapper.php';
require_once 'app/mapper/taxonomy.php';

return function ($args) {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $conditions = [];
    if ($status = $_GET['status'] ?? '') {
        $status_id = tag_id_by_slug($status, 'contact_demande-statut');
        if ($status_id) $conditions['status_id'] = $status_id;
    }

    $contacts = map_with_taxonomy('contact_request', [
        'status_id' => 'status',
        'subject_id' => 'subject'
    ], $conditions, [
        'limit' => $limit,
        'offset' => $offset,
        'order' => 'created_at DESC'
    ]);

    $total = map_list('contact_request', $conditions, ['limit' => null]);
    $total_pages = ceil(count($total) / $limit);

    $statuses = tag_by_parent('contact_demande-statut');

    return [
        'title' => 'Demandes de contact',
        'contacts' => $contacts,
        'statuses' => $statuses,
        'current_status' => $status,
        'pagination' => compact('page', 'total_pages'),
    ];
};
