<?php

return function () {
    return [
        'title' => 'Tableau de bord - Copro Academy Admin',
        'stats' => stats(),
        'recent' => [
            'contacts'  => db()->query("SELECT * FROM contact_request_plus LIMIT 5")->fetchAll(),
            'events'    => db()->query("SELECT * FROM event_plus WHERE event_date >= CURRENT_DATE LIMIT 5")->fetchAll(),
        ],
    ];
};

function stats()
{
    $change = 'SELECT COUNT(*) FROM `%s` WHERE `created_at` >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) AND `revoked_at` IS NULL';
    $total = 'SELECT COUNT(*) FROM `%s` WHERE revoked_at IS NULL';

    foreach(['total', 'change'] as $type)
        foreach(['article', 'training', 'event', 'booking'] as $table) 
            $stats["{$table}s_{$type}"] = (int)db()->query(sprintf($$type, $table))->fetchColumn();
        
    return $stats;
}
