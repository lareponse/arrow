<?php
require_once 'add/bad/dad/db_row.php';

return function ($training_id = null) {
    $training_id = (int)($training_id[0] ?? 0);

    if (!$training_id) {
        http_out(404, 'Training ID required');
    }

    // Verify training exists and get duration
    $training = dbq(db(), "
        SELECT id, label, slug, duration_days, start_date 
        FROM training 
        WHERE id = ? AND revoked_at IS NULL
    ", [$training_id])->fetch();

    if (!$training) {
        http_out(404, 'Training not found');
    }

    // Handle session deletion
    if (!empty($_GET['delete'])) {
        $delete_id = (int)$_GET['delete'];
        dbq(db(), "DELETE FROM training_program WHERE id = ? AND training_id = ?", [$delete_id, $training_id]);
        http_out(302, '', ['Location' => "/admin/training/program/$training_id"]);
    }

    // Handle session duplication
    if (!empty($_GET['duplicate'])) {
        $source_id = (int)$_GET['duplicate'];
        $target_day = (int)($_GET['to_day'] ?? 0);

        if ($target_day >= 1 && $target_day <= $training['duration_days']) {
            $source = dbq(db(), "SELECT * FROM training_program WHERE id = ?", [$source_id])->fetch();
            if ($source) {
                dbq(db(), "
                    INSERT INTO training_program (slug, label, training_id, day_number, time_start, time_end, description, objectives)
                    VALUES (?, ?, ?, ?, ?, ?, ?, ?)
                ", [
                    $source['slug'] . '-j' . $target_day,
                    $source['label'],
                    $training_id,
                    $target_day,
                    $source['time_start'],
                    $source['time_end'],
                    $source['description'],
                    $source['objectives']
                ]);
            }
        }
        http_out(302, '', ['Location' => "/admin/training/program/$training_id"]);
    }

    // Handle form submission (create/update session)
    if (!empty($_POST)) {
        $session = row(db(), 'training_program');
        $is_edit = !empty($_POST['id']);

        if ($is_edit) {
            $session(ROW_LOAD, ['id' => (int)$_POST['id']]);
            if (!$session(ROW_GET) || $session(ROW_GET, ['training_id']) != $training_id) {
                http_out(404, 'Session not found');
            }
        }

        // Validate and clean data
        $clean = [
            'training_id' => $training_id,
            'day_number' => max(1, min($training['duration_days'], (int)($_POST['day_number'] ?? 1))),
            'time_start' => $_POST['time_start'] ?: null,
            'time_end' => $_POST['time_end'] ?: null,
            'label' => trim($_POST['label'] ?: ''),
            'description' => trim($_POST['description'] ?: '') ?: null,
            'objectives' => trim($_POST['objectives'] ?: '') ?: null,
        ];

        // Generate slug if not provided
        if (empty($_POST['slug']) && $clean['label']) {
            $base_slug = strtolower(preg_replace('/[^a-z0-9]+/i', '-', $clean['label']));
            $base_slug = trim($base_slug, '-');
            $clean['slug'] = $base_slug . '-j' . $clean['day_number'];
        } else {
            $clean['slug'] = trim($_POST['slug'] ?: '');
        }

        // Validate required fields
        if (!$clean['label'] || !$clean['time_start'] || !$clean['time_end']) {
            http_out(400, 'Missing required fields: label, time_start, time_end');
        }

        // Validate time order
        if (strtotime($clean['time_end']) <= strtotime($clean['time_start'])) {
            http_out(400, 'End time must be after start time');
        }

        // Check for time conflicts (same day, overlapping times)
        $conflict_query = "
            SELECT id FROM training_program 
            WHERE training_id = ? AND day_number = ?
            AND ((time_start < ? AND time_end > ?) OR (time_start < ? AND time_end > ?))
        ";
        $conflict_params = [
            $training_id,
            $clean['day_number'],
            $clean['time_end'],
            $clean['time_start'],
            $clean['time_end'],
            $clean['time_start']
        ];

        if ($is_edit) {
            $conflict_query .= " AND id != ?";
            $conflict_params[] = (int)$_POST['id'];
        }

        $conflict = dbq(db(), $conflict_query, $conflict_params)->fetch();
        if ($conflict) {
            http_out(400, 'Time conflict with existing session');
        }

        // Save session
        $session(ROW_FIELDS);
        $session(ROW_SET, $clean);
        $session(ROW_SAVE);

        http_out(302, '', ['Location' => "/admin/training/program/$training_id"]);
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
