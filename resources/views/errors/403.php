<?php
use Src\Utils\AssetLoader;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title); ?></title>
    <?php echo AssetLoader::renderCss([
        '/css/errors/403.css'
    ]); ?>
</head>
<body>
<div class="container">
    <h1>403</h1>
    <p>Forbidden - You do not have permission to access this resource.</p>
    <p>Return to the <a href="/">homepage</a>.</p>
</div>
</body>
</html>