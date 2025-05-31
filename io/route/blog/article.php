<?php

/**
 * Blog article detail route
 */
return function ($slug) {
    // Load article mapper functions
    require_once 'app/mapper/article.php';

    var_dump($slug);
    // Get article by slug
    $article = article_get_by('slug', $slug);

    if (!$article) {
        throw new DomainException('Article not found', 404);
    }

    return [
        'status' => 200,
        'body' => render([
            'title' => $article['title'] . ' - copro.academy',
            'article' => $article
        ])
    ];
};
