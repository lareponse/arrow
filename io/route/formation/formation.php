<?php

return function ($args = null) {
    $db = db();
    if($args){
        $data = dbq($db, "SELECT * FROM training_plus WHERE slug = :slug", ['slug' => array_pop($args)])->fetch();
        if (!$data) {
            return ['error' => 'Formation non trouvée.'];
        }
        $data['title'] = $data['label'];
        $data['description'] = 'Formation certifiée en gestion de copropriétés. Conformité à la législation belge.';
        return $data;
    }
    $data = ['title' => 'Formations', 'description' => 'Découvrez nos formations certifiées en gestion de copropriétés. Programmes adaptés aux professionnels de l\'immobilier et conformes à la législation belge.'];

    $data['formation'] = dbq($db, "SELECT * FROM training_plus WHERE start_date >= CURDATE() ORDER BY start_date DESC LIMIT 10")->fetchAll();

    return $data;
};
