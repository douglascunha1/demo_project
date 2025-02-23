<?php

namespace Src\Services;

use Src\Models\User;
use Src\Models\UserFilters;
use Src\Repositories\UserRepository;

/**
 * Represents a user service
 *
 * Class UserService
 *
 * @package Src\Services
 */
class UserService {
    /**
     * The user repository
     *
     * @var UserRepository
     */
    private UserRepository $userRepository;

    /**
     * UserService constructor.
     */
    public function __construct() {
        $this->userRepository = new UserRepository();
    }

    /**
     * Get all users
     *
     * @return array
     */
    public function getUsers(): array {
        return $this->userRepository->findAll();
    }

    /**
     * Find users by filters
     *
     * @param array $filters
     * @return array
     */
    public function getUsersByFilters(array $filters): array {
        $userFilters = UserFilters::createFromArray($filters);

        $users = $this->userRepository->findByFilters($userFilters);

        return [
            'total' => $users['total'] ?? 0,
            'data' => $users['data'] ?? []
        ];
    }

    /**
     * Get a user by ID
     *
     * @param int $id
     * @return User|null
     */
    public function getUser(int $id): ?User {
        return $this->userRepository->findById($id);
    }

    /**
     * Update a user
     *
     * @param int $id
     * @param array $user
     * @return void
     */
    public function updateUser(int $id, array $user): void {
        $this->userRepository->update($id, $user);
    }

    /**
     * Delete a user
     *
     * @param int $id
     * @return void
     */
    public function deleteUser(int $id): void {
        $this->userRepository->delete($id);
    }
}
