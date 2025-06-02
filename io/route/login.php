<?php

return function ($quest, $request) {

    $redirect = $_GET['redirect'] ?? '/';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        csrf($_POST['csrf_token'] ?? '')    ?: throw new DomainException('Invalid CSRF', 403);
        $username = $_POST['username']      ?: throw new DomainException('Username required', 403);
        $password = $_POST['password']      ?: throw new DomainException('Password required', 403);

        if (auth_post($username, $password)) {
            header('Location: ' . $redirect);
            exit;
        }
    }

    return ['status' => 200, 'body' => render(['redirect' => $redirect, 'username' => $_POST['username']])];
};
