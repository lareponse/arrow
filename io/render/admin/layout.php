<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Copro.Academy</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        body {
            background-image: url('/assets/jplbg.jpg');
            background-position: right top;
        }
    </style>
</head>

<body>
    <main></main>
</body>

</html>
<?php
return function ($layout, $partial) {
    return preg_replace(
        '/<main\b([^>]*)>\s*<\/main>/i',       // match <main ...></main> (allowing whitespace inside)
        '<main$1>' . $partial . '</main>',    // re-emit the same attributes ($1) + your partial
        $layout
    );
};
