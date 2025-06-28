<?php

return function ($args=null) {

    $redirect = $_GET['redirect'] ?? '/';

    if ($_SERVER['REQUEST_METHOD'] === 'POST'){
        $username = $_POST['username']      ?: throw new DomainException('Username required', 403);
        $password = $_POST['password']      ?: throw new DomainException('Password required', 403);

        if (auth($username, $password)) {
            header('Location: ' . $redirect);
            exit;
        }
    }
};
