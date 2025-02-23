<?php

use Src\Controllers\HomeController;
use Src\Http\Router;
use Src\Controllers\UserController;

Router::addRoute('/', HomeController::class . '@index', 'GET');
Router::addRoute('/api/users/search', UserController::class . '@showUsersByFilters', 'GET');
Router::addRoute('/api/users', UserController::class . '@showUsers', 'GET');
Router::addRoute('/users', UserController::class . '@index', 'GET');
Router::addRoute('/users/{id}', UserController::class . '@show', 'GET');
Router::addRoute('/users', UserController::class . '@store', 'POST');
Router::addRoute('/users/{id}', UserController::class . '@update', 'PUT');
Router::addRoute('/users/{id}', UserController::class . '@delete', 'DELETE');