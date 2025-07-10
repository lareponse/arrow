<?php
// io/route/admin/service/reorder.php
return function ($args) {


    $order = $_REQUEST['order'] ?? [];
    vd($order);die;
    foreach ($order as $position => $id) {
        dbq(db(), "UPDATE service SET sort_order = ? WHERE id = ?", [$position, $id]);
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
};
