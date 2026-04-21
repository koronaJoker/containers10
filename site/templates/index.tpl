<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title><?= $title ?? 'Страница' ?></title>
    <link rel="stylesheet" href="/styles/style.css">
</head>
<body>

<div class="container">
    <div class="card">
        <h1><?= $title ?? '' ?></h1>
        <p><?= $content ?? '' ?></p>
    </div>
</div>

</body>
</html>