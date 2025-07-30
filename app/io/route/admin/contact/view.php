<?php

require_once 'add/arrow/arrow.php';

return function ($args) {

    $contact = row(db(), 'contact_request_plus')(ROW_LOAD | ROW_GET, ['id' => array_pop($args)]);
    !$contact && header('Location: ' . ($_SERVER['HTTP_REFERER'] ?? '/')) && exit;
    return ['contact' => $contact];
};
