<?php
require_once 'app/mapper/taxonomy.php';
return function ($args = null) {
    $db = db();

    $data = ['title' => 'Articles & Événements', 'description' => 'Découvrez nos articles et événements sur la gestion de copropriétés. Actualités juridiques, webinaires et conférences pour les professionnels de l\'immobilier .'];

    $data['articles_events'] = dbq($db, "SELECT * FROM articles_events ORDER BY featured DESC, unified_date DESC")->fetchAll();

    $data['article_categorie'] = tag_by_parent('article-categorie');
    $data['evenement_categorie'] = tag_by_parent('evenement-categorie');

    return $data;
};
