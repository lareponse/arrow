<?php

return function ($args = null) {
    $db = db();

    $data = ['title' => 'Articles & Événements', 'description' => 'Découvrez nos articles et événements sur la gestion de copropriétés. Actualités juridiques, webinaires et conférences pour les professionnels de l\'immobilier .'];

    $data['articles_events'] = dbq($db, "SELECT * FROM articles_events ORDER BY featured DESC, enabled_at DESC LIMIT 10")->fetchAll();

    return $data;
};
