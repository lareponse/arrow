<h1>HOME</h1>
<p>Lorem ipsum</p>

<?php
// return function ($view) {
//     $route = [IO_PATH => __DIR__ . DIRECTORY_SEPARATOR . 'layout.php', IO_ARGS => $view];
//     $quest = io_quest($route, [], IO_ABSORB);
//     http_out(200, $quest[IO_ABSORB], ['Content-Type' => 'text/html; charset=UTF-8']);
// }
return function ($this_html, $args = []) {
    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + ['main' => $this_html, 'css' => '/asset/css/alter.css'])[1];
};
