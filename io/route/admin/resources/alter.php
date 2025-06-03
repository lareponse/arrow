<?php

return function ($id = null) {
    require_once 'app/mapper/resource.php';

    $errors = [];
    $resource = null;
    $is_edit = $id !== null;

    $current_user = whoami();
    if (!$current_user) {
        throw new DomainException('Unauthorized', 401);
    }

    if ($is_edit) {
        $resource = dbq("SELECT * FROM resources WHERE id = ?", [$id])->fetch();
        if (!$resource) {
            throw new DomainException('Resource not found', 404);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $title = trim($_POST['title'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');
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
                "SELECT id FROM resources WHERE slug = ? AND id != ?",
                [$slug, $id ?? 0]
            )->fetch();
            if ($existing) {
                $errors['slug'] = 'Slug already exists';
            }
        }

        // Handle file upload for new resources
        $file_data = null;
        if (!$is_edit && isset($_FILES['file']) && $_FILES['file']['error'] === UPLOAD_ERR_OK) {
            $upload_dir = 'public/uploads/resources/';
            if (!is_dir($upload_dir)) {
                mkdir($upload_dir, 0755, true);
            }

            $file_info = $_FILES['file'];
            $file_extension = strtolower(pathinfo($file_info['name'], PATHINFO_EXTENSION));
            $filename = uniqid() . '.' . $file_extension;
            $file_path = $upload_dir . $filename;

            if (move_uploaded_file($file_info['tmp_name'], $file_path)) {
                $file_data = [
                    'file_path' => 'uploads/resources/' . $filename,
                    'file_type' => $file_info['type'],
                    'file_size' => $file_info['size']
                ];
            } else {
                $errors['file'] = 'Failed to upload file';
            }
        } elseif (!$is_edit) {
            $errors['file'] = 'File is required for new resources';
        }

        if (empty($errors)) {
            $data = [
                'title' => $title,
                'slug' => $slug,
                'description' => $description ?: null,
                'status' => $status
            ];

            if ($file_data) {
                $data = array_merge($data, $file_data);
            }

            if ($is_edit) {
                $success = resource_update($id, $data);
                if ($success) {
                    header('Location: /admin/resources?success=updated');
                    exit;
                }
                $errors['general'] = 'Failed to update resource';
            } else {
                $data['username'] = whoami();
                $resource_id = resource_create($data);
                if ($resource_id) {
                    header('Location: /admin/resources?success=created');
                    exit;
                }
                $errors['general'] = 'Failed to create resource';
            }
        }

        $resource = array_merge($resource ?? [], [
            'title' => $title,
            'slug' => $slug,
            'description' => $description,
            'status' => $status
        ]);
    }

    return [
        'payload' => [
            'title' => ($is_edit ? 'Edit Resource' : 'Create Resource') . ' - Admin',
            'resource' => $resource,
            'errors' => $errors,
            'is_edit' => $is_edit
        ]
    ];
};
