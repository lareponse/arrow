<?php

set_include_path(get_include_path() . PATH_SEPARATOR . __DIR__ . '/../..');

require 'add/core.php';
require 'add/bad/error.php';
require 'add/bad/db.php';
require 'add/bad/ui.php';
require 'add/bad/guard_auth.php';

io(__DIR__ . '/../io/route');
respond(resolve()());

