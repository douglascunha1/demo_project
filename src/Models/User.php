<?php

namespace Src\Models;

/**
 * Represents a user
 *
 * Class User
 *
 * @package Src\Models
 */
class User {
    public ?int $id;
    public string $name;
    public string $email;
    public string $password;

    /**
     * User constructor.
     *
     * @param array $data
     */
    public function __construct(array $data) {
        $this->id = $data['id'] ?? null;
        $this->name = $data['name'];
        $this->email = $data['email'];
        $this->password = $data['password'];
    }

    /**
     * Get the user ID
     *
     * @return int|null
     */
    public function getId(): ?int {
        return $this->id;
    }

    /**
     * Set the user ID
     *
     * @param int|null $id
     */
    public function setId(?int $id): void {
        $this->id = $id;
    }

    /**
     * Get the user name
     *
     * @return string
     */
    public function getName(): string {
        return $this->name;
    }

    /**
     * Set the user name
     *
     * @param string $name
     */
    public function setName(string $name): void {
        $this->name = $name;
    }

    /**
     * Get the user email
     *
     * @return string
     */
    public function getEmail(): string {
        return $this->email;
    }

    /**
     * Set the user email
     *
     * @param string $email
     */
    public function setEmail(string $email): void {
        $this->email = $email;
    }

    /**
     * Get the user password
     *
     * @return string
     */
    public function getPassword(): string {
        return $this->password;
    }

    /**
     * Set the user password
     *
     * @param string $password
     */
    public function setPassword(string $password): void {
        $this->password = $password;
    }

    /**
     * Create a User object from an array
     *
     * @param array $data
     * @return User
     */
    public static function createFromArray(array $data): User {
        return new self($data);
    }

    /**
     * Convert the user to an array
     *
     * @return array
     */
    public function toArray(): array {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'password' => $this->password
        ];
    }
}
