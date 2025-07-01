<?php
require_once 'app/mapper/mapper.php';

return function ($args) {
    return [
        'title' => 'Articles',
        'articles' => dbq(db(), 'SELECT * FROM article_plus ORDER BY enabled_at DESC, created_at DESC')->fetchAll(),
    ];
};
