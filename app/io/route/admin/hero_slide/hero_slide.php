<?php
//io/route/admin/hero_slide/hero_slide.php
return function ($args) {
    $slides = db()->query("
        SELECT id, image_path, alt_text, title, LEFT(description, 100) as preview, 
               cta_text, cta_url, sort_order, created_at
        FROM hero_slide 
        WHERE revoked_at IS NULL
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();

    return [
        'title' => 'Hero Slides - Page d\'accueil',
        'slides' => $slides
    ];
};
