<?php

namespace Src\Core;

use Exception;
use PDO;
use Src\Config\Database;

class DependencyContainer {
    /**
     * Array of dependencies registered in the container.
     *
     * @var array
     */
    private static array $dependencies = [];

    /**
     * Register a dependency in the container.
     *
     * @param string $key
     * @param callable $resolver
     */
    public static function register(string $key, callable $resolver): void {
        self::$dependencies[$key] = $resolver;
    }

    /**
     * Resolve a dependency from the container.
     *
     * @param string $key
     * @return mixed
     * @throws Exception
     */
    public static function resolve(string $key): mixed {
        if (!isset(self::$dependencies[$key])) {
            throw new Exception("Dependency {$key} not found in container.");
        }

        return call_user_func(self::$dependencies[$key]);
    }

    /**
     * Get the database connection using the Database class.
     *
     * @return PDO
     * @throws Exception
     */
    public static function getDatabaseConnection(): PDO {
        return Database::getConnection();
    }
}