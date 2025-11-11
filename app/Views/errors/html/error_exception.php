<!doctype html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Error</title>
</head>
<body>
    <h1><?= esc($title ?? 'Error') ?></h1>
    <p><?= esc($message ?? 'An error occurred') ?></p>
</body>
</html>
