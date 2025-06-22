<?php
require_once 'add/bad/dad/qb.php';

return function ($quest) {
    
    
    $errors = [];
    $success = false;

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $name = trim($_POST['name'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $message = trim($_POST['message'] ?? '');

        if (empty($name)) $errors['name'] = 'Name is required';
        if (empty($email)) $errors['email'] = 'Email is required';
        elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors['email'] = 'Invalid email';
        if (empty($message)) $errors['message'] = 'Message is required';

        if (empty($errors)) {
            // Store contact message
            $insert_data = [
                'name' => $name,
                'email' => $email,
                'message' => $message,
                'created_at' => date('Y-m-d H:i:s')
            ];
            $stmt = dbq(db(), ...qb_create('contact_messages', $insert_data));
            $success = $stmt->rowCount() > 0;

            if ($success) {
                // Clear form
                $_POST = [];
            }
        }
    }

    return [
        'payload' => [
            'title' => 'Contact Us - copro.academy',
            'errors' => $errors,
            'success' => $success
        ]
    ];
};