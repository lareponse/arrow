<?php
require_once 'app/mapper/mapper.php';
require_once 'app/mapper/taxonomy.php';

return function ($args) {

    $sql = 'SELECT * FROM `training_plus` ';
    $current_filter = $_GET['filter'] ?? '';
    switch($_GET['filter'] ?? ''){
        case 'upcoming':    $sql .= " WHERE `start_date` >= NOW()";     break;
        case 'past':        $sql .= " WHERE `start_date` < NOW()";      break;
        case 'certified':   $sql .= " WHERE `certification` = 1";       break;
        case 'published':   $sql .= " WHERE `enabled_at` IS NOT NULL";  break;
        case 'draft':       $sql .= " WHERE `enabled_at` IS NULL";      break;
    }
    $sql .= ' ORDER BY `start_date` DESC';
    return [
        'title' => 'Formations',
        'current_filter' => $current_filter,
        'trainings' => db()->query($sql)->fetchAll(),
    ];
};
