<?php

namespace Src\Config;

use PDO;
use PDOException;
use Exception;

/**
 * This class is responsible for managing the database connection.
 *
 * Class Database
 *
 * @package Src\Config
 */
class Database {
    /**
     * The active database connection.
     *
     * @var PDO
     */
    private PDO $connection;

    /**
     * Establish the database connection.
     *
     * @throws Exception
     */
    public function __construct() {
        try {
            $host = $_ENV['DB_HOST'] ?? 'localhost';
            $dbname = $_ENV['DB_NAME'] ?? 'test';
            $user = $_ENV['DB_USER'] ?? 'root';
            $password = $_ENV['DB_PASSWORD'] ?? '';

            $dsn = "mysql:host={$host};dbname={$dbname};charset=utf8";

            $options = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES => false,
            ];

            $this->connection = new PDO($dsn, $user, $password, $options);
        } catch (PDOException $e) {
            throw new Exception("Failed to connect to the database: " . $e->getMessage());
        }
    }

    /**
     * Return the active database connection.
     *
     * @return PDO
     */
    public function getConnection(): PDO {
        return $this->connection;
    }
}
