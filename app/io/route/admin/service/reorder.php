<?php
require_once 'add/arrow/arrow.php';

// io/route/admin/service/reorder.php
return function ($args) {
    $order = $_REQUEST['order'] ?? [];
    $service = row(db(), 'service');
    foreach ($order as $position => $id) {
        $service(ROW_RESET|ROW_LOAD, ['id' => $id]);
        $service(ROW_SET|ROW_SAVE, ['sort_order' => $position]);
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
};
