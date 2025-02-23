<?php

use Src\Models\User;
use Src\Utils\AssetLoader;
use Src\Utils\Functions;
use Src\Views\View;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($title); ?></title>
    <?php echo AssetLoader::renderJs([
        '/dist/assets/main.js',
        '/js/user/script.js',
        '/js/utils/Utils.js'
    ]); ?>
    <?php echo AssetLoader::renderCss([
        '/dist/assets/style.css',
        '/css/user/style.css'
    ]); ?>
</head>
<body>
<?php try {
    View::render('../partials/nav');
} catch (Exception $e) {
    echo $e->getMessage();
} ?>

<table class="table table-hover" id="table-user">
    <thead>
    <tr>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    </tbody>
</table>
</body>
</html>
