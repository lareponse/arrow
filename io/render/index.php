<h1>HOME</h1>
<p>Lorem ipsum</p>

<?php
return function ($view) {
    $route = [IO_PATH => __DIR__ . DIRECTORY_SEPARATOR . 'layout.php', IO_ARGS => $view];
    $quest = io_quest($route, [], IO_ABSORB);
    http_out(200, $quest[IO_ABSORB], ['Content-Type' => 'text/html; charset=UTF-8']);
}
?>
