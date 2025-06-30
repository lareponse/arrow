<?php
require_once 'app/mapper/mapper.php';
require_once 'app/mapper/taxonomy.php';

return function ($args) {
    vd($args);
    $conditions = [];
    if ($status = $_GET['status'] ?? '') {
        $status_id = tag_id_by_slug($status, 'contact_demande-statut');
        if ($status_id) $conditions['status_id'] = $status_id;
    }

    $statuses = tag_by_parent('contact_demande-statut');

    return [
        'title' => 'Demandes de contact',
        'contact_requests' => map_list('contact_request_plus'),
        'statuses' => $statuses,
        'current_status' => $status
    ];
};
