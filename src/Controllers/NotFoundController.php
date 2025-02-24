<?php

namespace Src\Controllers;

use Src\Http\Request;
use Src\Http\Response;

/**
 * This class is responsible for handling requests that do not match any route
 *
 * Class NotFoundController
 *
 * @package Src\Controllers
 */
class NotFoundController {
    /**
     * Handle the request
     *
     * @param Request $request
     * @param Response $response
     */
    public function index(Request $request, Response $response): void {
        $response->json([
            'error'   => true,
            'success' => false,
            'message' => 'Route not found.'
        ], 404);
    }
}