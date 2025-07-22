<?php
require_once 'app/mapper/taxonomy.php';

return function ($args = null) {
    $db = db();
    if($args){
        $data = qp($db, "SELECT * FROM training_plus WHERE slug = :slug", ['slug' => array_pop($args)])->fetch();
        if (!$data) {
            return ['error' => 'Formation non trouvée.'];
        }
        $data['title'] = $data['label'];
        $data['description'] = 'Formation certifiée en gestion de copropriétés. Conformité à la législation belge.';
        return $data;
    }
    $data = ['title' => 'Formations', 'description' => 'Découvrez nos formations certifiées en gestion de copropriétés. Programmes adaptés aux professionnels de l\'immobilier et conformes à la législation belge.'];

    $data['formation'] = $db->query("SELECT * FROM training_plus WHERE start_date >= CURDATE() ORDER BY start_date DESC LIMIT 10")->fetchAll();
    $data['formation_niveau'] = (tag_by_parent('formation-niveau'));
    $data['benefits'] = $db->query("SELECT * FROM benefit ORDER BY sort_order")->fetchAll();


    return $data;
};
