<?php
require_once 'add/bad/arrow.php';

return function ($args = null) {
    [$training_slug, $session_id] = $args;
    $training = row(db(), 'training');
    $training = $training(ROW_LOAD | ROW_GET, ['slug' => $training_slug, 'revoked_at' => null]);
    if (!$training) {
        http_out(302, '', ['Location' => '/admin/training', 'X-Message' => 'Training not found']);
    }

    $session = row(db(), 'training_program');
    if($session_id)
        $session(ROW_LOAD, ['id' => $session_id]);

    if (!empty($_POST)) {
        (require_once __DIR__.'/post.php')($training, $session);
    }

    $session = $session(ROW_GET);
    
    $training_id = $training['id'];
    // Get all sessions grouped by day
    $sessions = qp(db(), "
        SELECT * FROM training_program 
        WHERE training_id = ? 
        ORDER BY day_number, time_start
    ", [$training_id])->fetchAll();

    $program_by_day = [];
    $total_duration = 0;

    foreach ($sessions as $ordered) {
        $program_by_day[$ordered['day_number']][] = $ordered;

        // Calculate session duration in minutes
        $start = strtotime($ordered['time_start']);
        $end = strtotime($ordered['time_end']);
        $total_duration += ($end - $start) / 60;
    }

    // Generate day list for empty days
    $all_days = [];
    for ($day = 1; $day <= $training['duration_days']; $day++) {
        $all_days[$day] = $program_by_day[$day] ?? [];
    }
    return [
        'title' => "Programme - {$training['label']}",
        'training' => ($training),
        'program_by_day' => $all_days,
        'sessions' => $sessions,
        'total_duration' => round($total_duration / 60, 1), // hours
        'edit_session' => $session,
    ];
};
