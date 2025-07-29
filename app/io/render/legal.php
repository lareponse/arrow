<?php
return function ($this_html, $args = []) {
    
    $page = $args[0];
    $file = realpath($_SERVER['DOCUMENT_ROOT'] . "/static/legal/{$page}.html");
    if (!file_exists($file))
        throw new InvalidArgumentException("Page '$page' inexistante", 404);
    
    $data = ($args ?? []) + [
        'main' => file_get_contents($file),
        'title' => 'Mentions légales',
        'navbar__link__active' => 'legal',
        'append_css' => '<link rel="stylesheet" href="/static/css/07-legales.css">'
    ];

    return ob_ret_get('app/io/render/layout.php', ($args ?? []) + $data)[1];
};
