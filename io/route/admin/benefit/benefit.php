<?php
//io/route/admin/benefit/benefit.php
return function ($args) {
    $benefits = db()->query("
        SELECT id, icon, title, LEFT(description, 100) as preview, 
               sort_order, is_active, created_at
        FROM benefit 
        WHERE is_active = 1
        ORDER BY sort_order ASC, created_at DESC
    ")->fetchAll();
    return [
        'title' => 'Benefits - Page d\'accueil',
        'benefits' => $benefits
    ];
};