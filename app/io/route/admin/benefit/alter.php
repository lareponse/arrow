<?php
require_once 'add/bad/arrow.php';
// io/route/admin/benefit/alter.php
return function ($args) {
    $id = $args[0] ?? null;
    $benefit = row(db(), 'benefit');
    if ($id) {
        $benefit(ROW_LOAD, ['id' => $id]);
        if (!$benefit) {
            header('HTTP/1.0 404 Not Found');
            exit('Benefit non trouvé');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $clean = [];
        $clean['icon'] = trim($_POST['icon'] ?? '');
        $clean['title'] = trim($_POST['title'] ?? '');
        $clean['description'] = trim($_POST['description'] ?? '');
        $clean['sort_order'] = (int)($_POST['sort_order'] ?? 0);
        $clean['is_active'] = isset($_POST['is_active']) ? 1 : 0;

        if ($_POST['action'] ?? '' === 'delete' && $id) {
            qp(db(), "DELETE FROM benefit WHERE id = ?", [$id]);
            header('Location: /admin/benefit');
            exit;
        }

        $benefit(ROW_SET | ROW_SAVE, $clean);

        header('Location: /admin/benefit');
        exit;
    }

    return [
        'title' => $id ? 'Modifier le benefit' : 'Nouveau benefit',
        'benefit' => $benefit(ROW_GET)
    ];
};
