<?php

/**
 * Admin routes authentication check
 */
return function () {
    require_once __DIR__ . '/../../add/auth.php';

    // Check if user is authenticated
    if (!is_authenticated()) {
        // Redirect to login page
        header('Location: /login?redirect=' . urlencode($_SERVER['REQUEST_URI']));
        exit;
    }

    // Check if user has admin role
    if (!has_role(['admin', 'editor'])) {
        trigger_error('403 Forbidden: Admin access required', E_USER_ERROR);
    }
};
