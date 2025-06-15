<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');

require 'add/bad/dad/dev.php';
require 'add/bad/error.php';
require 'add/bad/io.php';
require 'add/bad/auth.php';

define('HOME_BASE', realpath(__DIR__ . '/../io'));
define('SAFE_PATH', io_guard(http_guard(4096, 9)));
define('FILE_ROOT', 'index');

$continue   = io_start(HOME_BASE . '/route', SAFE_PATH, FILE_ROOT);
$mirror     = io_start(HOME_BASE . '/render/', SAFE_PATH, FILE_ROOT, $continue);
vd(0, $mirror);die;

$mirror     = io_absorb($mirror[IO_PATH], $continue[IO_ARGS] ?: []);
if (is_array($mirror)) {
    http(...$mirror);
    exit;
}

http(404, 'Not Found');
