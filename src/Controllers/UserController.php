<?php

namespace Src\Controllers;

use Exception;
use Mpdf\Mpdf;
use Mpdf\MpdfException;
use Mpdf\Output\Destination;
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Src\Http\HttpStatusCode;
use Src\Models\User;
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
     * @param array $args
     * @return Response
     * @throws Exception
     */
    public function index(Request $request, Response $response, array $args): Response {
        View::render('user/users', ['title' => 'Home']);

        exit();
    }

    /**
     * Get all users by filters
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function showUsersByFilters(Request $request, Response $response, array $args): Response {
        $params = $request->getQueryParams();

        $draw = $params['draw'];
        $start = $params['start'];
        $length = $params['length'];
        $searchValue = $params['search']['value'];
        $orderColumn = $params['order']['0']['column'];
        $orderDir = $params['order']['0']['dir'];

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

        $response->getBody()->write(json_encode([
            'draw' => intval($draw),
            'recordsTotal' => $users['total'] ?? 0,
            'recordsFiltered' => $users['total'] ?? 0,
            'data' => $users['data'] ?? []
        ]));

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get all users
     *
     * @param Request $request
     * @param Response $response
     * @param array $args
     * @return Response
     */
    public function showUsers(Request $request, Response $response, array $args): Response {
        $users = $this->userService->getUsers();

        $payload = json_encode($users);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Get a user by ID
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws Exception
     */
    public function show(Request $request, Response $response, array $params): Response {
        if (!isset($params['id'])) {
            return $response->withStatus(HttpStatusCode::BAD_REQUEST);
        }

        $user = $this->userService->getUser($params['id']);

        if (!$user) {
            return $response->withStatus(HttpStatusCode::NOT_FOUND, 'User not found');
        }

        $payload = json_encode($user);

        $response->getBody()->write($payload);

        return $response->withHeader('Content-Type', 'application/json');
    }

    /**
     * Update a user by ID
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function update(Request $request, Response $response, array $params): Response {
        if (!isset($params['id'])) {
            return $response->withStatus(HttpStatusCode::BAD_REQUEST);
        }

        $user = $this->userService->getUser($params['id']);

        if (!$user) {
            return $response->withStatus(HttpStatusCode::NOT_FOUND);
        }

        $data = $request->getParsedBody();

        $errors = Validator::validate($data, [
            'name' => 'required|string|min:3|max:100',
            'email' => 'required|email|min:3|max:100',
            'password' => 'string|min:6|max:255'
        ]);

        if (!empty($errors)) {
            $response->getBody()->write(json_encode(['errors' => $errors]));
            return $response->withStatus(HttpStatusCode::BAD_REQUEST);
        }

        $user->setName($data['name']);
        $user->setEmail($data['email']);

        if (isset($data['password'])) {
            $user->setPassword($data['password']);
        }

        try {
            $this->userService->updateUser($params['id'], $user->toArray());
            $response->getBody()->write(json_encode($user));
            $response->withStatus(HttpStatusCode::OK);

            return $response->withHeader('Content-Type', 'application/json');
        } catch (Exception $e) {
            return $response->withStatus(HttpStatusCode::INTERNAL_SERVER_ERROR);
        }
    }

    /**
     * Delete a user by ID
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     */
    public function delete(Request $request, Response $response, array $params): Response {
        if (!isset($params['id'])) {
            return $response->withStatus(HttpStatusCode::BAD_REQUEST);
        }

        $user = $this->userService->getUser($params['id']);

        if (!$user) {
            return $response->withStatus(HttpStatusCode::NOT_FOUND);
        }

        $this->userService->deleteUser($params['id']);

        return $response->withStatus(HttpStatusCode::NO_CONTENT);
    }

    /**
     * Download a PDF with all users
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     * @return Response
     * @throws MpdfException
     */
    public function pdf(Request $request, Response $response, array $params): Response {
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '900000M');
        ini_set("pcre.backtrack_limit", "20000000");
        $users = $this->userService->getUsers();

        $html = '
        <html>
        <head>
            <style>
                body { font-family: Arial, sans-serif; }
                h1 { color: #333; text-align: center; }
                table { width: 100%; border-collapse: collapse; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; width: 25%; }
                th { background-color: #f2f2f2; }
            </style>
        </head>
        <body>
            <h1>Users</h1>
            <table>
                <tr>
                    <th>ID</th>
                    <th>Name</th>
                    <th>Email</th>
                    <th>Password</th>
                </tr>';

            /** @var User $user */
            foreach ($users as $user) {
                $html .= "
                <tr>
                    <td>{$user->getId()}</td>
                    <td>{$user->getName()}</td>
                    <td>{$user->getEmail()}</td>
                    <td>{$user->getPassword()}</td>
                </tr>";
            }

            $html .= '
            </table>
        </body>
        </html>';

        $mpdf = new Mpdf(['tempDir' => __DIR__ . '/../../tmp']);
        $mpdf->WriteHTML($html);
        $mpdf->Output('users.pdf', Destination::DOWNLOAD);

        exit();
    }

    /**
     * Download an Excel file with all users
     *
     * @param Request $request
     * @param Response $response
     * @param array $params
     */
    public function excel(Request $request, Response $response, array $params): Response {
        ini_set('max_execution_time', '300');
        ini_set('memory_limit', '900000M');
        ini_set("pcre.backtrack_limit", "20000000");

        $users = $this->userService->getUsers();

        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setCellValue('A1', 'ID');
        $sheet->setCellValue('B1', 'Name');
        $sheet->setCellValue('C1', 'Email');
        $sheet->setCellValue('D1', 'Password');

        $row = 2;
        /** @var User $user */
        foreach ($users as $user) {
            $sheet->setCellValue('A' . $row, $user->getId());
            $sheet->setCellValue('B' . $row, $user->getName());
            $sheet->setCellValue('C' . $row, $user->getEmail());
            $sheet->setCellValue('D' . $row, $user->getPassword());
            $row++;
        }

        $writer = new Xlsx($spreadsheet);
        $filePath = __DIR__ . '/../../tmp/users.xlsx';
        $writer->save($filePath);

        header('Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet');
        header('Content-Disposition: attachment; filename="users.xlsx"');
        header('Content-Length: ' . filesize($filePath));
        readfile($filePath);

        exit;
    }
}