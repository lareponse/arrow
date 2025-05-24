<?php

declare(strict_types=1);

$badge = __DIR__ . '/../../add';
require $badge.'/core.php';
require $badge.'/bad/error.php';
require $badge.'/bad/db.php';
require $badge.'/bad/ui.php';
require $badge.'/bad/security.php';
require $badge.'/bad/auth_sql.php';

require $badge.'/bad/dev.php';


list($dsn, $u, $p) = require '../data/credentials.php';
// $dsn = null;
db($dsn, $u, $p, [
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
]);

$route_root = __DIR__ . '/../io/route';
$route = route($route_root);

$response = handle($route);
respond($response);
