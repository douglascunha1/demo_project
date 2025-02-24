<?php

use Slim\Routing\RouteCollectorProxy;
use Src\Controllers\UserController;

$app->get('/', [UserController::class, 'index']);
$app->get('/users', [UserController::class, 'showUsers']);
$app->get('/users/showUsersByFilters', [UserController::class, 'showUsersByFilters']);
$app->group('/users', function (RouteCollectorProxy $group) {
    $group->get('/pdf', [UserController::class, 'pdf']);
    $group->get('/excel', [UserController::class, 'excel']);
});
$app->get('/users/{id}', [UserController::class, 'show']);
$app->put('/users/{id}', [UserController::class, 'update']);
$app->delete('/users/{id}', [UserController::class, 'delete']);