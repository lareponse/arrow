<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="/assets/css/admin.css">
    <style>
        body {
            background-image: url('/assets/jplbg.jpg');
            background-position: right top;
        }
    </style>
</head>

<body>
    <main>
        <?= implode(',', slot('main')); ?>
    </main>
</body>

</html>