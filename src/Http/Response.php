<?php

namespace Src\Http;

/**
 * This class is responsible for handling the response and sending it back to the client
 *
 * Class Response
 *
 * @package Src\Http
 */
class Response {
    /**
     * Send status code to the client
     *
     * @param int $code
     * @return void
     */
    public static function setStatusCode(int $code): void {
        http_response_code($code);
    }

    /**
     * Redirect the request to a different location
     *
     * @param string $url
     */
    public static function redirect(string $url) {
        header('Location: ' . $url);
        exit();
    }

    /**
     * Send JSON response to the client
     *
     * @param array $data
     * @param int $code
     */
    public static function json(array $data = [], int $code = 200) {
        self::setStatusCode($code);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit();
    }

    /**
     * Send a 404 Not Found response
     *
     * @param string $message
     * @return void
     */
    public static function notFound(string $message = 'Resource not found'): void {
        self::json([
            'error' => true,
            'message' => $message
        ], 404);
    }

    /**
     * Send a 405 Method Not Allowed response
     *
     * @param string $message
     * @return void
     */
    public static function methodNotAllowed(string $message = 'Method not allowed'): void {
        self::json([
            'error' => true,
            'message' => $message
        ], 405);
    }

    /**
     * Send a 500 Internal Server Error response
     *
     * @param string $message
     * @return void
     */
    public static function internalServerError(string $message = 'Internal server error'): void {
        self::json([
            'error' => true,
            'message' => $message
        ], 500);
    }

    /**
     * Send a success response
     *
     * @param array $data
     * @param string $message
     * @param int $code
     * @return void
     */
    public static function success(array $data = [], string $message = 'Success', int $code = 200): void
    {
        self::json([
            'success' => true,
            'message' => $message,
            'data' => $data
        ], $code);
    }

    /**
     * Send an error response
     *
     * @param string $message
     * @param int $code
     * @return void
     */
    public static function error(string $message = 'An error occurred', int $code = 400): void {
        self::json([
            'error' => true,
            'message' => $message
        ], $code);
    }
}