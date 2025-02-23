<?php

namespace Src\Repositories;

use PDO;
use Src\Models\User;
use Src\Core\DependencyContainer;

/**
 * Represents a user repository
 *
 * Class UserRepository
 *
 * @package Src\Repositories
 */
class UserRepository {
    /**
     * The PDO instance
     *
     * @var PDO
     */
    private PDO $pdo;

    /**
     * UserRepository constructor.
     *
     * @throws \Exception
     */
    public function __construct() {
        $db = DependencyContainer::resolve('database');
        $this->pdo = $db->getConnection();
    }

    /**
     * Find all users
     *
     * @return array
     */
    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM users");
        $users = $stmt->fetchAll();

        return array_map(fn($user) => new User($user), $users);
    }

    /**
     * Find a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function findById(int $id): ?User {
        $stmt = $this->pdo->prepare("SELECT * FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        return $user ? User::createFromArray($user) : null;
    }

    /**
     * Update a user
     *
     * @param int $id
     * @param array $user
     */
    public function update(int $id, array $user): void {
        $stmt = $this->pdo->prepare("UPDATE users SET name = :name, email = :email, password = :password WHERE id = :id");
        $stmt->bindParam(':name', $user['name'], PDO::PARAM_STR);
        $stmt->bindParam(':email', $user['email'], PDO::PARAM_STR);
        $stmt->bindParam(':password', $user['password'], PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    /**
     * Delete a user
     *
     * @param int $id
     */
    public function delete(int $id): void {
        $stmt = $this->pdo->prepare("DELETE FROM users WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
        $stmt->execute();
    }
}
