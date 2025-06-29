<?php

require_once 'add/bad/dad/arrow.php';

return function ($args) {
    auth(AUTH_GUARD);

    $contact = row(db(), 'contact_request_plus')(ROW_LOAD, ['id' => array_pop($args)]);
    !$contact && http_back() && exit;

    return ['contact' => $contact];
};
