<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');


require 'add/core.php';
require 'add/bad/error.php';
require 'add/bad/db.php';
require 'add/bad/ui.php';
require 'add/bad/guard_auth.php';



pdo(...(require 'app/data/credentials.php'));

$_ = request(__DIR__ . '/../io/route');
$route = route();
$response = handle($route);
respond($response);
