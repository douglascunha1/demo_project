<?php

namespace Src\Http;

use Closure;
use Exception;
use Src\Views\View;

/**
 * This class is responsible for routing the requests
 *
 * Class Router
 *
 * @package Src\Http
 */
class Router {
    private static array $routes = [];

    /**
     * This method is responsible for adding a route
     *
     * @param string $path
     * @param string $handler
     * @param string $method
     */
    public static function addRoute(string $path, string $handler, string $method = 'GET'): void {
        self::$routes[] = [
            'path'    => $path,
            'handler' => $handler,
            'method'  => strtoupper($method)
        ];
    }

    /**
     * Handles the incoming request and executes the corresponding controller action.
     * @throws Exception
     */
    public static function dispatch(): void {
        $requestPath = Request::path();
        $requestMethod = Request::method();

        foreach (self::$routes as $route) {
            $pattern = '#^' . preg_replace('/\{[\w]+\}/', '([\w-]+)', $route['path']) . '$#';

            if (preg_match($pattern, $requestPath, $matches) && $route['method'] === $requestMethod) {
                array_shift($matches);

                [$controller, $method] = explode('@', $route['handler']);

                if (!class_exists($controller)) {
                    self::render500("Controller $controller not found");
                }

                $controllerInstance = new $controller();

                if (!method_exists($controllerInstance, $method)) {
                    self::render500("Method $method not found in $controller");
                }

                echo $controllerInstance->$method(new Request(), new Response(), $matches);

                return;
            }
        }

        self::render404();
    }

    /**
     * This method is responsible for returning the routes
     *
     * @return array
     */
    public static function routes(): array {
        return self::$routes;
    }

    /**
     * Render the custom 404 page.
     *
     * @param string $message Optional error message.
     * @throws Exception
     */
    private static function render404(string $message = "Page Not Found"): void {
        http_response_code(404);
        View::render('errors/404', ['message' => $message]);
        exit;
    }

    /**
     * Render the custom 500 page.
     *
     * @param string $message Optional error message.
     * @throws Exception
     */
    private static function render500(string $message = "Internal Server Error"): void {
        http_response_code(500);
        View::render('errors/500', ['message' => $message]);
        exit;
    }
}