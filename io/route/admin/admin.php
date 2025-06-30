<?php

return function () {
    return [
        'title' => 'Tableau de bord - Copro Academy Admin',
        'stats' => stats(),
        'recent' => [
            'contacts' => dbq(db(), "SELECT * FROM contact_request_plus LIMIT 5")->fetchAll(),
            'events' => dbq(db(), "SELECT * FROM event_plus WHERE event_date >= CURRENT_DATE LIMIT 5")->fetchAll(),
        ],
    ];
};

function stats()
{

    // Monthly changes
    $changes = 'SELECT COUNT(*) FROM `%s` 
        WHERE `created_at` >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        AND `revoked_at` IS NULL';


    $stats = [
        'articles_total' => (int)dbq(db(), "SELECT COUNT(*) FROM article WHERE revoked_at IS NULL")->fetchColumn(),
        'articles_change' => (int)dbq(db(), sprintf($changes, 'article'))->fetchColumn(),
        'trainings_total' => (int)dbq(db(), "SELECT COUNT(*) FROM training WHERE revoked_at IS NULL")->fetchColumn(),
        'trainings_change' => (int)dbq(db(), sprintf($changes, 'training'))->fetchColumn(),
        'events_total' => (int)dbq(db(), "SELECT COUNT(*) FROM event WHERE revoked_at IS NULL")->fetchColumn(),
        'events_change' => (int)dbq(db(), sprintf($changes, 'event'))->fetchColumn(),
        'booking_total' => (int)dbq(db(), "SELECT COUNT(*) FROM booking eb  WHERE eb.revoked_at IS NULL ")->fetchColumn(),
        'bookings_change' => (int)dbq(db(), "SELECT COUNT(*) FROM booking tb WHERE tb.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH) AND tb.revoked_at IS NULL ")->fetchColumn()
    ];

    return $stats;
}
