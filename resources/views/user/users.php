<?php

use Src\Models\User;
use Src\Utils\AssetLoader;
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
        '/js/user/script.js'
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

<table class="table table-hover" id="table-user">
    <thead>
    <tr>
        <th scope="col">ID</th>
        <th scope="col">Name</th>
        <th scope="col">Email</th>
        <th scope="col">Password</th>
        <th scope="col">Actions</th>
    </tr>
    </thead>
    <tbody>
    <?php /* @var User $user */ ?>
    <?php foreach ($users as $user) { ?>
        <tr>
            <th scope="row"><?php echo htmlspecialchars($user->getId()); ?></th>
            <td><?php echo htmlspecialchars($user->getName()); ?></td>
            <td><?php echo htmlspecialchars($user->getEmail()); ?></td>
            <td><?php echo htmlspecialchars($user->getPassword()); ?></td>
            <td>
                <a data-id="<?php echo $user->getId(); ?>" class="btn btn-primary show-user">View</a>
                <a data-id="<?php echo $user->getId(); ?>" class="btn btn-warning edit-user">Edit</a>
                <a data-id="<?php echo $user->getId(); ?>" class="btn btn-danger delete-user">Delete</a>
            </td>
        </tr>
    <?php } ?>
    </tbody>
</table>
</body>
</html>
