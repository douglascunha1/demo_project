<?php

namespace Src\Http;

use Closure;

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
                    Response::json(['error' => true, 'message' => "Controller $controller not found"], 500);
                    return;
                }

                $controllerInstance = new $controller();

                if (!method_exists($controllerInstance, $method)) {
                    Response::json(['error' => true, 'message' => "Method $method not found in $controller"], 500);
                    return;
                }

                // Execute the controller action
                echo $controllerInstance->$method(new Request(), new Response(), $matches);
                return;
            }
        }

        // If no route matches
        Response::json(['error' => true, 'message' => 'Route not found'], 404);
    }

    /**
     * This method is responsible for returning the routes
     *
     * @return array
     */
    public static function routes(): array {
        return self::$routes;
    }
}