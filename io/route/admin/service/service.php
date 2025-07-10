<?php
// io/route/admin/service/list.php
return function ($args) {
    $services = dbq(db(), "
        SELECT id, label, LEFT(content, 100) as preview, 
               image_src, link, link_text, sort_order, created_at
        FROM service 
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();

    return [
        'title' => 'Services - Page d\'accueil',
        'services' => $services
    ];
};
