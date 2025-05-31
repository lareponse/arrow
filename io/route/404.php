
<?php
// io/route/404.php
return function () {
    return [
        'status' => 404,
        'body' => render([
            'title' => 'Page Not Found - copro.academy'
        ], __FILE__)
    ];
};

// io/route/admin/categories/alter.php
return function ($id = null) {
    require_once 'app/mapper/category.php';

    $errors = [];
    $category = null;
    $is_edit = $id !== null;

    if ($is_edit) {
        $category = category_get_by('id', $id);
        if (!$category) {
            trigger_error('404 Not Found: Category not found', E_USER_ERROR);
        }
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $slug = trim($_POST['slug'] ?? '');
        $description = trim($_POST['description'] ?? '');

        if (empty($name)) {
            $errors['name'] = 'Name is required';
        }

        if (empty($slug)) {
            $errors['slug'] = 'Slug is required';
        } elseif (!preg_match('/^[a-z0-9-]+$/', $slug)) {
            $errors['slug'] = 'Slug must contain only lowercase letters, numbers, and hyphens';
        } else {
            $existing = dbq(
                "SELECT id FROM categories WHERE slug = ? AND id != ?",
                [$slug, $id ?? 0]
            )->fetch();
            if ($existing) {
                $errors['slug'] = 'Slug already exists';
            }
        }

        if (empty($errors)) {
            $data = compact('name', 'slug', 'description');

            if ($is_edit) {
                $success = category_update($id, $data);
                if ($success) {
                    header('Location: /admin/categories?success=updated');
                    exit;
                }
                $errors['general'] = 'Failed to update category';
            } else {
                $category_id = category_create($data);
                if ($category_id) {
                    header('Location: /admin/categories?success=created');
                    exit;
                }
                $errors['general'] = 'Failed to create category';
            }
        }

        $category = array_merge($category ?? [], compact('name', 'slug', 'description'));
    }

    return [
        'status' => 200,
        'body' => render([
            'title' => ($is_edit ? 'Edit Category' : 'Create Category') . ' - Admin',
            'category' => $category,
            'errors' => $errors,
            'is_edit' => $is_edit
        ], __FILE__)
    ];
};
