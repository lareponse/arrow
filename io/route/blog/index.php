<?php

return function ($quest) {
    // Load article mapper functions
    require_once 'app/mapper/article.php';

    // Get page number from query string
    $page = isset($_GET['page']) ? max(1, (int)$_GET['page']) : 1;
    $limit = 10;
    $offset = ($page - 1) * $limit;

    // Get articles
    $articles = articles_get_published($limit, $offset);

    $total = articles_count_published();
    $total_pages = ceil($total / $limit);

    return [
        'payload' => [
            'title' => 'Blog - copro.academy',
            'articles' => $articles,
            'pagination' => [
                'current' => $page,
                'total' => $total_pages,
                'has_prev' => $page > 1,
                'has_next' => $page < $total_pages
            ]
        ]
    ];
};
