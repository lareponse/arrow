<?php

/**
 * Logout route
 */
return function ($quest) {
    // Clear auth cookie
    auth(AUTH_REVOKE);
    setcookie('auth_token', '', [
        'expires' => time() - 3600,
        'path' => '/',
        'domain' => '',
        'secure' => true,
        'httponly' => true,
        'samesite' => 'Lax'
    ]);

    // Redirect to home
    header('Location: /');
    exit;
};
