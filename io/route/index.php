<?php

return function ($args = null) {
    $db = db();

    $data = ['title' => 'Accueil', 'description' => 'Contactez-nous pour toute question sur nos formations, événements ou services. Notre équipe est là pour vous aider.'];

    // 1) On centralise toutes les requêtes dans un tableau [clé => SQL]
    $queries = [
        'faq'              => "SELECT label, content FROM faq ORDER BY sort_order",
        'service'          => "SELECT * FROM service ORDER BY sort_order",
        'recent_articles'   => "SELECT * FROM article_plus WHERE enabled_at<=NOW() ORDER BY featured DESC, enabled_at DESC LIMIT 3",

        'hero_slides'       => "SELECT * FROM hero_slide WHERE is_active=1 ORDER BY sort_order",
        'featured_articles' => "SELECT * FROM article WHERE featured=1 AND enabled_at<=NOW() ORDER BY enabled_at DESC LIMIT 3",
        'upcoming_events'   => "SELECT * FROM event WHERE event_date>=CURDATE() ORDER BY event_date LIMIT 3",
        'benefits'          => "SELECT * FROM benefit ORDER BY sort_order",
    ];

    // 2) Boucle sur le tableau pour exécuter chaque requête
    foreach ($queries as $key => $sql) {
        ($_ = dbq($db, $sql)) && ($_ = $_->fetchAll()) && $data[$key] = $_;
    }

    $sql = "SELECT slug, label FROM `coproacademy`;";
    ($_ = dbq($db, $sql)) && ($_ = $_->fetchAll(PDO::FETCH_KEY_PAIR))   && $data['coproacademy'] = $_;
    // vd($data);

    return $data;
};
