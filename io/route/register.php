<?php

/**
 * Register route
 */
return function ($quest) {
    $errors = [];

    // Check if already logged in
    if (!auth()) {
        // Redirect to home
        header('Location: /');
        exit;
    }

    // Process registration form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $password_confirm = $_POST['password_confirm'] ?? '';
        $full_name = trim($_POST['full_name'] ?? '');

        // Validate username
        if (empty($username)) {
            $errors['username'] = 'Username is required';
        } elseif (strlen($username) < 3 || strlen($username) > 20) {
            $errors['username'] = 'Username must be between 3 and 20 characters';
        } elseif (!preg_match('/^[a-zA-Z0-9_]+$/', $username)) {
            $errors['username'] = 'Username can only contain letters, numbers, and underscores';
        } elseif (user_get_by('username', $username)) {
            $errors['username'] = 'Username already taken';
        }

        // Validate email
        if (empty($email)) {
            $errors['email'] = 'Email is required';
        } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = 'Invalid email format';
        } elseif (user_get_by('email', $email)) {
            $errors['email'] = 'Email already registered';
        }

        // Validate password
        if (empty($password)) {
            $errors['password'] = 'Password is required';
        } elseif (strlen($password) < 8) {
            $errors['password'] = 'Password must be at least 8 characters';
        } elseif ($password !== $password_confirm) {
            $errors['password_confirm'] = 'Passwords do not match';
        }

        // Validate full name
        if (empty($full_name)) {
            $errors['full_name'] = 'Full name is required';
        }

        // If no errors, create user
        if (empty($errors)) {
            $user_id = user_create([
                'username' => $username,
                'password' => $password,
                'email' => $email,
                'full_name' => $full_name,
                'role' => 'user'
            ]);

            if ($user_id) {
                // Create and set token
                $token = user_create_token($user_id);

                // Set cookie
                $cookie_options = [
                    'expires' => time() + 86400 * 30, // 30 days
                    'path' => '/',
                    'domain' => '',
                    'secure' => true,
                    'httponly' => true,
                    'samesite' => 'Lax'
                ];

                setcookie('auth_token', $token, $cookie_options);

                // Redirect to home
                header('Location: /');
                exit;
            } else {
                $errors['general'] = 'Failed to create account. Please try again.';
            }
        }
    }

    return [
        'payload' => [
            'title' => 'Register - copro.academy',
            'errors' => $errors
        ]
    ];
};
