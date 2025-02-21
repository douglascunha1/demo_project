<?php

namespace Src\Http;

/**
 * This class is responsible for handling the request
 *
 * Class Request
 *
 * @package Src\Http
 */
class Request {
    /**
     * Get the path of the request
     *
     * @return mixed
     */
    public static function path(): mixed {
        return parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
    }

    /**
     * Get the request method
     *
     * @return string
     */
    public static function method(): string {
        return strtoupper($_SERVER['REQUEST_METHOD']);
    }

    /**
     * Get the request body
     */
    public static function body(): array {
        return json_decode(file_get_contents('php://input'), true) ?? [];
    }
}