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
        $user = $this->userService->getUser($params[0]);

        if (!$user) {
            $response->error('User not found', HttpStatusCode::NOT_FOUND);
        }

        View::render('user/show', ['user' => $user->toArray(), 'title' => "User Details"]);
    }
}