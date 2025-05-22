<?php

/**
 * Blog article detail route
 */
return function ($slug) {
    // Load article mapper functions
    require_once request()['root'] . '/mapper/article.php';

    var_dump($slug);
    // Get article by slug
    $article = article_get_by('slug', $slug);

    if (!$article) {
        trigger_error('404 Not Found: Article not found', E_USER_ERROR);
    }

    return [
        'status' => 200,
        'body' => render([
            'title' => $article['title'] . ' - copro.academy',
            'article' => $article
        ])
    ];
};
