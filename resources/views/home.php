<?php
use Src\Utils\AssetLoader;
use Src\Views\View;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo htmlspecialchars($title) ?></title>
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

<button type="button" class="btn btn-primary" id="liveToastBtn">Show live toast</button>

<div class="toast-container position-fixed bottom-0 end-0 p-3">
    <div id="liveToast" class="toast" role="alert" aria-live="assertive" aria-atomic="true">
        <div class="toast-header">
            <strong class="me-auto">Bootstrap</strong>
            <small>11 mins ago</small>
            <button type="button" class="btn-close" data-bs-dismiss="toast" aria-label="Close"></button>
        </div>
        <div class="toast-body">
            Hello, world! This is a toast message.
        </div>
    </div>
</div>

<button type="button" class="btn btn-primary" id="btn-click-me">Primary</button>
</body>
</html>
