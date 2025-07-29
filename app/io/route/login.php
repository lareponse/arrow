<?php

return function () {
    if (!empty($_POST) && auth(AUTH_VERIFY, 'username', 'password')) {
        header('Location: /admin');
        exit;
    }
};
