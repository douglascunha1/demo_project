<?php

namespace Src\Core;

use Dotenv\Dotenv;
use Exception;
use Src\Config\Database;
use Src\Controllers\UserController;
use Src\Repositories\UserRepository;
use Src\Services\UserService;
use Src\Http\Router;

/**
 * This class is responsible for initializing the application.
 *
 * Class App
 *
 * @package Src\Core
 */
class App {
    /**
     * Initializes the application.
     *
     * @throws Exception
     * @return void
     */
    public static function init(): void {
        self::loadEnvironment();
        self::registerDependencies();
        self::handleRequest();
    }

    /**
     * Load the environment variables from the `.env` file.
     *
     * @throws Exception
     * @return void
     */
    private static function loadEnvironment(): void {
        $dotenv = Dotenv::createImmutable(__DIR__ . '/../../');
        $dotenv->load();
    }

    /**
     * Register the application dependencies.
     *
     * @return void
     */
    private static function registerDependencies(): void {
        DependencyContainer::register('database', fn() => new Database());
        DependencyContainer::register('userRepository', fn() => new UserRepository());
        DependencyContainer::register('userService', fn() => new UserService());
        DependencyContainer::register('userController', fn() => new UserController());
    }

    /**
     * Handle the incoming request.
     *
     * @throws Exception
     * @return void
     */
    private static function handleRequest(): void {
        Router::dispatch();
    }
}
