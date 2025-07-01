<?php
require_once 'app/mapper/mapper.php';

return function ($args) {
    return [
        'title' => 'Articles',
        'articles' => dbq(db(), 'SELECT * FROM article_plus ORDER BY event_date DESC')->fetchAll(),
        'pagination' => compact('page', 'total_pages'),
    ];
};
