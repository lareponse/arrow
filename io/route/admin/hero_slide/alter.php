<?php
require_once 'add/bad/arrow.php';
// io/route/admin/hero_slide/alter.php
return function ($args) {
    $id = $args[0] ?? null;
    $slide = row(db(), 'hero_slide');
    if ($id) {
        $slide(ROW_LOAD, ['id' => $id, 'revoked_at' => null]);
        if (!$slide) {
            header('HTTP/1.0 404 Not Found');
            exit('Slide non trouvé');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {

        $clean = [];
        $clean['image_path'] = trim($_POST['image_path'] ?? '');
        $clean['alt_text'] = trim($_POST['alt_text'] ?? '');
        $clean['title'] = trim($_POST['title'] ?? '');
        $clean['subtitle'] = trim($_POST['subtitle'] ?? '');
        $clean['description'] = trim($_POST['description'] ?? '');
        $clean['cta_text'] = trim($_POST['cta_text'] ?? '');
        $clean['cta_url'] = trim($_POST['cta_url'] ?? '');
        $clean['sort_order'] = (int)($_POST['sort_order'] ?? 0);

        if ($_POST['action'] ?? '' === 'delete' && $id) {
            qp(db(), "UPDATE hero_slide SET revoked_at = NOW() WHERE id = ?", [$id]);
            header('Location: /admin/hero_slide');
            exit;
        }

        $slide(ROW_SET | ROW_SAVE, $clean);

        header('Location: /admin/hero_slide');
        exit;
    }

    return [
        'title' => $id ? 'Modifier le slide' : 'Nouveau slide',
        'slide' => $slide(ROW_GET)
    ];
};
