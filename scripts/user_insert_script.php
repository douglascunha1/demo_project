<?php

require_once __DIR__ . '/../vendor/autoload.php';

use Dotenv\Dotenv;
use Faker\Factory;
use Faker\Generator;
use Src\Config\Database;
use Src\Core\DependencyContainer;

$startTime = microtime(true);

$dotenv = Dotenv::createImmutable(__DIR__ . '/..');
$dotenv->load();

DependencyContainer::register('database', fn() => new Database());
/** @var Database $pdo */
$pdo = DependencyContainer::resolve('database');
$pdo = $pdo->getConnection();

class DataGenerator {
    private Generator $faker;

    /**
     * DataGenerator constructor.
     */
    public function __construct() {
        $this->faker = Factory::create('pt_BR');
    }

    /**
     * Get a random name.
     *
     * @return string
     */
    public function getName(): string {
        return $this->faker->name;
    }

    /**
     * Get a random email.
     *
     * @return string
     */
    public function getEmail(): string {
        return $this->faker->unique()->email;
    }

    /**
     * Get a hashed password.
     *
     * @return string
     */
    public function getPassword(): string {
        return password_hash('password123', PASSWORD_DEFAULT);
    }
}

class UserInserter {
    private PDO $pdo;
    private DataGenerator $dataGenerator;

    /**
     * UserInserter constructor.
     *
     * @param PDO $pdo
     * @param DataGenerator $dataGenerator
     */
    public function __construct(PDO $pdo, DataGenerator $dataGenerator) {
        $this->pdo = $pdo;
        $this->dataGenerator = $dataGenerator;
    }

    /**
     * Insert a user into the database.
     *
     * @return void
     */
    public function insertUser(): void {
        $name = $this->dataGenerator->getName();
        $email = $this->dataGenerator->getEmail();
        $password = $this->dataGenerator->getPassword();

        $stmt = $this->pdo->prepare("INSERT INTO users (name, email, password) VALUES (:name, :email, :password)");
        $stmt->execute([
            ':name' => $name,
            ':email' => $email,
            ':password' => $password
        ]);

        echo "Usuário inserido: $name ($email)\n";
    }

    /**
     * Insert multiple users into the database.
     *
     * @param int $quantity
     * @return void
     */
    public function insertMultipleUsers(int $quantity): void {
        for ($i = 0; $i < $quantity; $i++) {
            $this->insertUser();
        }

        echo "$quantity usuários inseridos com sucesso!\n";
    }
}

class ScriptRunner {
    private UserInserter $userInserter;

    /**
     * ScriptRunner constructor.
     *
     * @param UserInserter $userInserter
     */
    public function __construct(UserInserter $userInserter) {
        $this->userInserter = $userInserter;
    }

    /**
     * Run the script.
     *
     * @return void
     */
    public function run(): void {
        $this->userInserter->insertMultipleUsers(1000000);
    }
}

$dataGenerator = new DataGenerator();

$userInserter = new UserInserter($pdo, $dataGenerator);

$scriptRunner = new ScriptRunner($userInserter);

$scriptRunner->run();

$endTime = microtime(true);

$executionTime = $endTime - $startTime;

echo "Tempo total de execução: " . number_format($executionTime, 2) . " segundos\n";