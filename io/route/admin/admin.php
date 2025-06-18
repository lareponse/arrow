<?php

return function() {
    return [
        'title' => 'Tableau de bord - Copro Academy Admin',
        'stats' => stats(),
        'recent' => recent(),
    ];
};


function stats()
{
    $stats = [
        'articles_total' => (int)dbq(db(), "
            SELECT COUNT(*) FROM article WHERE revoked_at IS NULL
        ")->fetchColumn(),

        'trainings_total' => (int)dbq(db(), "
            SELECT COUNT(*) FROM training WHERE revoked_at IS NULL
        ")->fetchColumn(),

        'events_total' => (int)dbq(db(), "
            SELECT COUNT(*) FROM event WHERE revoked_at IS NULL
        ")->fetchColumn(),

        'bookings_total' => (int)dbq(db(), "
            SELECT COUNT(*) FROM event_booking eb 
            JOIN event e ON eb.event_id = e.id 
            WHERE eb.revoked_at IS NULL AND e.revoked_at IS NULL
        ")->fetchColumn() + (int)dbq(db(), "
            SELECT COUNT(*) FROM training_booking tb 
            JOIN training t ON tb.training_id = t.id 
            WHERE tb.revoked_at IS NULL AND t.revoked_at IS NULL
        ")->fetchColumn(),
    ];

    // Monthly changes
    $stats['articles_change'] = (int)dbq(db(), "
        SELECT COUNT(*) FROM article 
        WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        AND revoked_at IS NULL
    ")->fetchColumn();

    $stats['trainings_change'] = (int)dbq(db(), "
        SELECT COUNT(*) FROM training 
        WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        AND revoked_at IS NULL
    ")->fetchColumn();

    $stats['events_change'] = (int)dbq(db(), "
        SELECT COUNT(*) FROM event 
        WHERE created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        AND revoked_at IS NULL
    ")->fetchColumn();

    $stats['bookings_change'] = (int)dbq(db(), "
        SELECT COUNT(*) FROM event_booking eb
        JOIN event e ON eb.event_id = e.id
        WHERE eb.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        AND eb.revoked_at IS NULL AND e.revoked_at IS NULL
    ")->fetchColumn() + (int)dbq(db(), "
        SELECT COUNT(*) FROM training_booking tb
        JOIN training t ON tb.training_id = t.id
        WHERE tb.created_at >= DATE_SUB(CURRENT_DATE, INTERVAL 1 MONTH)
        AND tb.revoked_at IS NULL AND t.revoked_at IS NULL
    ")->fetchColumn();

    return $stats;
}

function recent()
{
    return [
        'contacts' => dbq(db(), "
            SELECT cr.id, cr.label, cr.email, cr.subject_id, cr.status_id, cr.created_at,
                   ts.label as subject_label, tst.label as status_label
            FROM contact_request cr
            LEFT JOIN taxonomy ts ON cr.subject_id = ts.id
            LEFT JOIN taxonomy tst ON cr.status_id = tst.id
            WHERE cr.revoked_at IS NULL
            ORDER BY cr.created_at DESC 
            LIMIT 5
        ")->fetchAll(),

        'events' => dbq(db(), "
            SELECT e.id, e.label, e.category_id, e.event_date, e.places_max,
                   tc.label as category_label,
                   COUNT(eb.event_id) as bookings_count
            FROM event e
            LEFT JOIN taxonomy tc ON e.category_id = tc.id
            LEFT JOIN event_booking eb ON e.id = eb.event_id AND eb.revoked_at IS NULL
            WHERE e.event_date >= CURRENT_DATE 
            AND e.revoked_at IS NULL
            GROUP BY e.id, e.label, e.category_id, e.event_date, e.places_max, tc.label
            ORDER BY e.event_date ASC
            LIMIT 5
        ")->fetchAll(),
    ];
}