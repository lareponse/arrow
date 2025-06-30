<?php

return function () {
    if (!empty($_POST) && auth(AUTH_VERIFY, 'username', 'password')) {
        header('Location: ' . ($_GET['redirect'] ?: $_SERVER['HTTP_REFERER'] ?: '/'));
        exit;
    }
};
