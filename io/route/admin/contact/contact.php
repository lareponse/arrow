<?php
require_once 'app/mapper/taxonomy.php';

return function ($args) {
    
    $sql = "SELECT * FROM `contact_request_plus`";
    
    if ($status = $_GET['status'] ?? '') {
        $status_id = (int)tag_id_by_slug($status, 'contact_demande-statut');
        if ($status_id) 
            $sql .= " WHERE `status_id` = $status_id";
    }
    $statuses = tag_by_parent('contact_demande-statut');

    return [
        'title' => 'Demandes de contact',
        'contact_requests' => db()->query($sql)->fetchAll(),
        'statuses' => $statuses,
        'current_status' => $status
    ];
};
