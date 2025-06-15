<h1>HOME</h1>
<p>Lorem ipsum</p>

<?php
return function ($view) {
    http(200, io_absorb(__DIR__ . DIRECTORY_SEPARATOR . 'layout.php', $view), ['Content-Type' => 'text/html; charset=UTF-8']);
}
?>
