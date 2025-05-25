<?php

return function () {


$iterations = 700_000_000;
$array      = ['foo', 'bar'];

// list()
$start = microtime(true);
for ($i = 0; $i < $iterations; ++$i) {
    list($a, $b) = $array;
}
$listTime = microtime(true) - $start;

// short syntax
$start = microtime(true);
for ($i = 0; $i < $iterations; ++$i) {
    [$a, $b] = $array;
}
$shortTime = microtime(true) - $start;

echo "list(): $listTime s\n";
echo "[] = : $shortTime s\n";



    die('dev.php');
};