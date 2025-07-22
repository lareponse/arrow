<?php
//io/route/admin/home.php
return function ($args) {
    $slides = db()->query("
        SELECT id, image_path, alt_text, title, LEFT(description, 100) as preview, 
               cta_text, cta_url, sort_order, created_at
        FROM hero_slide 
        WHERE revoked_at IS NULL
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();

    $benefits = db()->query("
        SELECT id, icon, title, LEFT(description, 100) as preview, 
               sort_order, is_active, created_at
        FROM benefit 
        WHERE is_active = 1
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();

    $services = db()->query("
        SELECT id, label, LEFT(content, 100) as preview, 
               image_src, link, link_text, sort_order, created_at
        FROM service 
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();


    return [
        'title' => 'Page d\'accueil',
        'benefits' => $benefits,
        'slides' => $slides,
        'services' => $services
    ];
};
