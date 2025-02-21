<?php

namespace Src\Controllers;

use Exception;
use Src\Http\HttpStatusCode;
use Src\Http\Request;
use Src\Http\Response;
use Src\Services\UserService;
use Src\Views\View;

/**
 * This class is responsible for handling user related requests
 *
 * Class UserController
 *
 * @package Src\Controllers
 */
class UserController {
    /**
     * The user service
     *
     * @var UserService
     */
    private UserService $userService;

    /**
     * UserController constructor.
     */
    public function __construct() {
        $this->userService = new UserService();
    }

    /**
     * Get all users
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function index(Request $request, Response $response): void {
        $users = $this->userService->getUsers();

        $response->json($users);
    }

    /**
     * Get a user by ID
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @throws Exception
     */
    public function show(Request $request, Response $response, array $params): void {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            $response->setStatusCode(HttpStatusCode::BAD_REQUEST);

            View::render('errors/400', ['title' => 'Bad Request', 'message' => 'Invalid user ID.']);

            return;
        }
        $user = $this->userService->getUser($params[0]);

        if (!$user) {
            $response->setStatusCode(HttpStatusCode::NOT_FOUND);

            View::render('errors/404', ['title' => 'User Not Found', 'message' => 'User not found.']);

            return;
        }

        View::render('user/show', ['user' => $user->toArray(), 'title' => "User Details"]);
    }
}