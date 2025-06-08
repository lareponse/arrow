<h1>HOME</h1>
<p>Lorem ipsum</p>
<?php
return function ($raw_html) {
    // Add custom headers
    http_respond(200, $raw_html, [
        'Content-Type' => 'text/html; charset=UTF-8',
        'X-Custom' => 'special-view'
    ]);
};
