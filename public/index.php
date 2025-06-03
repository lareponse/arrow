<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');


require 'add/core.php';
require 'add/bad/error.php';
require 'add/bad/db.php';
require 'add/bad/ui.php';
require 'add/bad/guard_auth.php';

http_guard();

$from      = io(__DIR__ . '/../io/route');
$map       = io_look($from);
$quest     = io_walk(io_read($map));
$response  = deliver($quest) ?: http_response(404, "Not Found", ['Content-Type' => 'text/plain']);

if (is_dev() && empty($response['status']) || $response['status'] >= 400) {
    $response = http_response(404, io_scaffold('in'), ['Content-Type' => 'text/html; charset=UTF-8']);
}

http_echo(...$response);
exit;
