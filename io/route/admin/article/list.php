<?php
require_once 'app/mapper/mapper.php';

return function ($args) {
    $page = max(1, (int)($_GET['page'] ?? 1));
    $limit = 20;
    $offset = ($page - 1) * $limit;

    $conditions = [];
    if ($search = $_GET['q'] ?? '') {
        $conditions = ['label LIKE' => "%$search%"];
    }

    $articles = map_with_taxonomy('article', ['category_id' => 'category'], $conditions, [
        'limit' => $limit,
        'offset' => $offset,
        'order' => 'created_at DESC'
    ]);

    $total = map_list('article', $conditions, ['limit' => null]);
    $total_pages = ceil(count($total) / $limit);

    return [
        'title' => 'Articles',
        'articles' => $articles,
        'search' => $search,
        'pagination' => compact('page', 'total_pages'),
    ];
};
