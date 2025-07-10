<?php
// io/route/admin/service/reorder.php
return function ($args) {
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header('HTTP/1.0 405 Method Not Allowed');
        exit;
    }

    $order = $_POST['order'] ?? [];

    foreach ($order as $position => $id) {
        dbq(db(), "UPDATE service SET sort_order = ? WHERE id = ?", [$position, $id]);
    }

    header('Content-Type: application/json');
    echo json_encode(['status' => 'success']);
    exit;
};
