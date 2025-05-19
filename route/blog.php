<?php

/**
 * Blog index route - Display list of articles
 */
return function () {
    // Load article mapper functions
    require_once __DIR__ . '/../../add/article_mapper.php';

    // Get page number from query string
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Get articles
    $articles = articles_get_all($limit, $offset);

    // Get total count for pagination
    $total = db_state("SELECT COUNT(*) FROM articles WHERE status = 'published'")->fetchColumn();
    $total_pages = ceil($total / $limit);

    return [
        'status' => 200,
        'body' => render([
            'title' => 'Blog - copro.academy',
            'articles' => $articles,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ], __FILE__, 'layout')
    ];
};
