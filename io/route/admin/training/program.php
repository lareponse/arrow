<?php
require_once 'add/bad/dad/arrow.php';

return function ($args = []) {
    $training = row(db(), 'training')(ROW_LOAD, ['slug' => $args[0], 'revoked_at' => null]);
    $training_id = $training['id'];
    if (!$training_id) {
        http_out(404, 'Training ID required');
    }

    // Get all sessions grouped by day
    $sessions = dbq(db(), "
        SELECT * FROM training_program 
        WHERE training_id = ? 
        ORDER BY day_number, time_start
    ", [$training_id])->fetchAll();

    $program_by_day = [];
    $total_duration = 0;

    foreach ($sessions as $session) {
        $program_by_day[$session['day_number']][] = $session;

        // Calculate session duration in minutes
        $start = strtotime($session['time_start']);
        $end = strtotime($session['time_end']);
        $total_duration += ($end - $start) / 60;
    }

    // Get session for editing if specified
    $edit_session = null;
    if (!empty($_GET['edit'])) {
        $edit_session = dbq(db(), "
            SELECT * FROM training_program 
            WHERE id = ? AND training_id = ?
        ", [(int)$_GET['edit'], $training_id])->fetch();
    }

    // Generate day list for empty days
    $all_days = [];
    for ($day = 1; $day <= $training['duration_days']; $day++) {
        $all_days[$day] = $program_by_day[$day] ?? [];
    }

    return [
        'title' => "Programme - {$training['label']}",
        'training' => $training,
        'program_by_day' => $all_days,
        'sessions' => $sessions,
        'total_duration' => round($total_duration / 60, 1), // hours
        'edit_session' => $edit_session,
    ];
};
