<?php

return function ($args = null) {
    $db = db();
    $data = [];
    $data['article'] = dbq($db, "SELECT * FROM article_plus WHERE slug = :slug", ['slug' => array_pop($args)])->fetch();
    if (!$data) {
        return ['error' => 'Formation non trouvée.'];
    }

    $data['title'] = $data['training']['label'];
    $data['description'] = 'Formation certifiée en gestion de copropriétés. Conformité à la législation belge.';
    return $data;
};
