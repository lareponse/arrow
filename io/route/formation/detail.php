<?php

return function ($args = null) {
    $db = db();
    $data = [];

    $data['training'] = dbq($db, "SELECT * FROM training_plus WHERE slug = :slug", ['slug' => array_pop($args)])->fetch();
    if (!$data) {
        return ['error' => 'Formation non trouvée.'];
    }

    $res = dbq($db, "SELECT * FROM training_program WHERE training_id = :id ORDER BY day_number ASC, time_start ASC", ['id' => $data['training']['id']]);
    $allItems = $res->fetchAll(PDO::FETCH_ASSOC);
    $byDay = [];
    foreach ($allItems as $item) {
        $byDay[$item['day_number']][] = $item;
    }
    $data['training_sessions_by_day'] = $byDay;

    $data['trainer'] = dbq($db, "SELECT * FROM trainer WHERE id = ? AND revoked_at IS NULL", [$data['training']['trainer_id']])->fetch();

    $data['title'] = $data['training']['label'];
    $data['description'] = 'Formation certifiée en gestion de copropriétés. Conformité à la législation belge.';
    return $data;
};
