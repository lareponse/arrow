<?php

return function ($id = null) {
    require_once request()['root'] . '/mapper/event.php';

    $errors = [];
    $event = null;
    $is_edit = $id !== null;

    $current_user = auth_user_active();
    if (!$current_user) {
        trigger_error('401 Unauthorized', E_USER_ERROR);
    }
    $user = auth_user_fetch($current_user);

    if ($is_edit) {
        $event = pdo("SELECT * FROM events WHERE id = ?", [$id])->fetch();
        if (!$event) {
            trigger_error('404 Not Found: Event not found', E_USER_ERROR);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
        $location = trim($_POST['location'] ?? '');
        $start_datetime = trim($_POST['start_datetime'] ?? '');
        $end_datetime = trim($_POST['end_datetime'] ?? '');
        $image_url = trim($_POST['image_url'] ?? '');
        $status = $_POST['status'] ?? 'draft';

        if (empty($title)) {
            $errors['title'] = 'Title is required';
        }

        if (empty($slug)) {
            $errors['slug'] = 'Slug is required';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors['slug'] = 'Slug must contain only lowercase letters, numbers, and hyphens';
        } else {
            $existing = pdo(
                "SELECT id FROM events WHERE slug = ? AND id != ?",
                [$slug, $id ?? 0]
            )->fetch();
            if ($existing) {
                $errors['slug'] = 'Slug already exists';
            }
        }

        if (empty($description)) {
            $errors['description'] = 'Description is required';
        }

        if (empty($start_datetime)) {
            $errors['start_datetime'] = 'Start date and time is required';
        }

        if (empty($end_datetime)) {
            $errors['end_datetime'] = 'End date and time is required';
        } elseif (!empty($start_datetime) && strtotime($end_datetime) <= strtotime($start_datetime)) {
            $errors['end_datetime'] = 'End time must be after start time';
        }

        if (empty($errors)) {
            $data = [
                'title' => $title,
                'slug' => $slug,
                'description' => $description,
                'location' => $location ?: null,
                'start_datetime' => $start_datetime,
                'end_datetime' => $end_datetime,
                'image_url' => $image_url ?: null,
                'status' => $status
            ];

            if ($is_edit) {
                $success = event_update($id, $data);
                if ($success) {
                    header('Location: /admin/events?success=updated');
                    exit;
                }
                $errors['general'] = 'Failed to update event';
            } else {
                $data['user_id'] = $user['id'];
                $event_id = event_create($data);
                if ($event_id) {
                    header('Location: /admin/events?success=created');
                    exit;
                }
                $errors['general'] = 'Failed to create event';
            }
        }

        $event = array_merge($event ?? [], [
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'location' => $location,
            'start_datetime' => $start_datetime,
            'end_datetime' => $end_datetime,
            'image_url' => $image_url,
            'status' => $status
        ]);
    }

    return [
        'status' => 200,
        'body' => render([
            'title' => ($is_edit ? 'Edit Event' : 'Create Event') . ' - Admin',
            'event' => $event,
            'errors' => $errors,
            'is_edit' => $is_edit
        ], __FILE__)
    ];
};