<?php

use Dotenv\Dotenv;
use Slim\Factory\AppFactory;
use Src\Config\Database;
use Src\Core\DependencyContainer;

require_once __DIR__ . '/../vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

DependencyContainer::register('database', fn() => new Database());
/** @var Database $pdo */
$pdo = DependencyContainer::resolve('database');
$pdo = $pdo->getConnection();

$app = AppFactory::create();
$app->addBodyParsingMiddleware();

require_once __DIR__ . '/../src/routes/main.php';

$app->run();