<?php

/**
 * Blog article detail route
 */
return function ($slug) {
    // Load article mapper functions
    require_once __DIR__ . '/../../../add/article_mapper.php';

    // Get article by slug
    $article = article_get_by_slug($slug);

    if (!$article) {
        trigger_error('404 Not Found: Article not found', E_USER_ERROR);
    }

    return [
        'status' => 200,
        'body' => render([
            'title' => $article['title'] . ' - copro.academy',
            'article' => $article
        ], 'layout')
    ];
};
