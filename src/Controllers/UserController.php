<?php

namespace Src\Controllers;

use Exception;
use Src\Http\HttpStatusCode;
use Src\Http\Request;
use Src\Http\Response;
use Src\Services\UserService;
use Src\Utils\Validator;
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
     * @throws Exception
     */
    public function index(Request $request, Response $response): void {

        View::render('user/users', ['title' => 'Home']);
    }

    /**
     * Get all users and return them as JSON
     *
     * @param Request $request
     * @param Response $response
     * @return void
     */
    public function showUsers(Request $request, Response $response): void {
        $draw = $request->input('draw');
        $start = $request->input('start');
        $length = $request->input('length');
        $searchValue = $request->input('search.value');
        $orderColumn = $request->input('order.0.column');
        $orderDir = $request->input('order.0.dir');

        $page = ($start / $length) + 1;
        $perPage = $length;

        $columns = ['name', 'email', 'password'];
        $orderBy = $columns[$orderColumn] ?? 'id';

        $users = $this->userService->getUsersByFilters([
            'page' => $page,
            'perPage' => $perPage,
            'search' => $searchValue,
            'orderBy' => $orderBy,
            'orderDir' => $orderDir
        ]);

        $response->json([
            'draw' => intval($draw),
            'recordsTotal' => $users['total'] ?? 0,
            'recordsFiltered' => $users['total'] ?? 0,
            'data' => $users['data'] ?? []
        ]);
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

            return;
        }

        $user = $this->userService->getUser($params[0]);

        if (!$user) {
            $response->setStatusCode(HttpStatusCode::NOT_FOUND);

            return;
        }

        $response->json($user->toArray());
    }

    public function update(Request $request, Response $response, array $params): void {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            $response->setStatusCode(HttpStatusCode::BAD_REQUEST);

            return;
        }

        $user = $this->userService->getUser($params[0]);

        if (!$user) {
            $response->setStatusCode(HttpStatusCode::NOT_FOUND);

            return;
        }

        $data = $request->body();

        $errors = Validator::validate($data, [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|min:3|max:100',
            'password' => 'string|min:6|max:255'
        ]);

        if (!empty($errors)) {
            $response->json(['errors' => $errors], HttpStatusCode::UNPROCESSABLE_ENTITY);

            return;
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);
        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }

        try {
            $this->userService->updateUser($params[0], $user->toArray());
            $response->setStatusCode(HttpStatusCode::NO_CONTENT);
            $response->json($user->toArray());
        } catch (Exception $e) {
            $response->setStatusCode(HttpStatusCode::INTERNAL_SERVER_ERROR);
            $response->json(['error' => $e->getMessage()]);
        }
    }


    /**
     * Delete a user by ID
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @throws Exception
     */
    public function delete(Request $request, Response $response, array $params): void {
        if (!isset($params[0]) || !is_numeric($params[0])) {
            $response->setStatusCode(HttpStatusCode::BAD_REQUEST);

            return;
        }

        $user = $this->userService->getUser($params[0]);

        if (!$user) {
            $response->setStatusCode(HttpStatusCode::NOT_FOUND);

            return;
        }

        $this->userService->deleteUser($params[0]);

        $response->setStatusCode(HttpStatusCode::NO_CONTENT);
    }
}