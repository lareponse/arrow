<?php
// io/route/admin/service/alter.php
return function ($args) {
    $id = $args[0] ?? null;
    $service = null;

    if ($id) {
        $service = dbq(db(), "SELECT * FROM service WHERE id = ?", [$id])->fetch();
        if (!$service) {
            header('HTTP/1.0 404 Not Found');
            exit('Service non trouvé');
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $label = trim($_POST['label'] ?? '');
        $content = trim($_POST['content'] ?? '');
        $image_src = trim($_POST['image_src'] ?? '');
        $alt_text = trim($_POST['alt_text'] ?? '');
        $link = trim($_POST['link'] ?? '');
        $link_text = trim($_POST['link_text'] ?? '');
        $sort_order = (int)($_POST['sort_order'] ?? 0);

        if (!$label || !$content || !$image_src || !$alt_text) {
            return [
                'title' => $id ? 'Modifier le service' : 'Nouveau service',
                'service' => $_POST,
                'error' => 'Le titre, contenu, image et texte alt sont obligatoires'
            ];
        }

        if ($_POST['action'] ?? '' === 'delete' && $id) {
            dbq(db(), "DELETE FROM service WHERE id = ?", [$id]);
            header('Location: /admin/service/list');
            exit;
        }

        if ($id) {
            dbq(db(), "
                UPDATE service 
                SET label = ?, content = ?, image_src = ?, alt_text = ?, 
                    link = ?, link_text = ?, sort_order = ?, updated_at = NOW() 
                WHERE id = ?
            ", [$label, $content, $image_src, $alt_text, $link, $link_text, $sort_order, $id]);
        } else {
            dbq(db(), "
                INSERT INTO service (label, content, image_src, alt_text, link, link_text, sort_order, created_at, updated_at) 
                VALUES (?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
            ", [$label, $content, $image_src, $alt_text, $link, $link_text, $sort_order]);
        }

        header('Location: /admin/service/list');
        exit;
    }

    return [
        'title' => $id ? 'Modifier le service' : 'Nouveau service',
        'service' => $service
    ];
};
