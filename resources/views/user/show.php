<?php

use Src\Utils\AssetLoader;
use Src\Views\View;
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title><?php echo htmlspecialchars($title); ?></title>
    <?php echo AssetLoader::renderJs([
        '/dist/assets/main.js'
    ]); ?>
    <?php echo AssetLoader::renderCss([
        '/dist/assets/style.css'
    ]); ?>
</head>
<body>

<?php try {
    View::render('../partials/nav');
} catch (Exception $e) {
    echo $e->getMessage();
} ?>

<h1>User Details</h1>

<table class="table">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
    </tr>
    </thead>
    <tbody>
    <tr>
        <th scope="row"><?php echo htmlspecialchars($user['id']); ?></th>
        <td><?php echo htmlspecialchars($user['name']); ?></td>
        <td><?php echo htmlspecialchars($user['email']); ?></td>
        <td><?php echo htmlspecialchars($user['password']); ?></td>
    </tr>
    </tbody>
</table>

</body>
</html>
