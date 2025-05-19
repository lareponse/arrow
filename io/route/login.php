<?php

/**
 * Login route
 */
return function () {
    require_once request()['route_root'] . '/../../mapper/user.php';
    
    $error = null;
    

    // Process login form
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $remember = isset($_POST['remember']);

        // Verify credentials
        $user = user_verify($username, $password);

        if ($user) {
            // Create and set token
            $token = user_create_token($user['id']);

            // Set cookie
            $cookie_options = [
                'expires' => $remember ? time() + 86400 * 30 : 0, // 30 days or session
                'path' => '/',
                'domain' => '',
                'secure' => true,
                'httponly' => true,
                'samesite' => 'Lax'
            ];

            setcookie('auth_token', $token, $cookie_options);

            // Redirect to home or requested page
            $redirect = $_GET['redirect'] ?? '/';
            header('Location: ' . $redirect);
            exit;
        } else {
            $error = 'Invalid username or password';
        }
    }


    return [
        'status' => 200,
        'body' => render([
            'title' => 'Login - copro.academy',
            'error' => $error,
        ], 'layout')
    ];
};
