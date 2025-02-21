<?php

use Src\Core\App;

require_once __DIR__ . '/../vendor/autoload.php';
require_once __DIR__ . '/../src/routes/main.php';

try {
    App::init();
} catch (Exception $e) {
    echo json_encode(['error' => $e->getMessage()]);
}