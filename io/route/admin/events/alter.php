<?php

return function ($quest, $id) {
    require_once 'app/mapper/event.php';

    $errors = [];
    $event = null;
    $is_edit = $id !== null;

    $current_user = whoami();
    if (!$current_user) {
        throw new DomainException('Unauthorized', 401);
    }
    if ($is_edit) {
        $event = dbq("SELECT * FROM events WHERE id = ?", [$id])->fetch();
        if (!$event) {
            throw new DomainException('Event not found', 404);
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
            $existing = dbq(
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
                $data['username'] = whoami();
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
        'payload' => [
            'title' => ($is_edit ? 'Edit Event' : 'Create Event') . ' - Admin',
            'event' => $event,
            'errors' => $errors,
            'is_edit' => $is_edit
        ]
    ];
};