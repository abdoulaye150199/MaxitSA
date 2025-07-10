<?php

namespace App\Core;

use App\Core;

class Router
{
    public static function resolve()
    {
        global $routes;

        $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

        if (array_key_exists($uri, $routes)) {
            $route = $routes[$uri];
            $controller = $route['controller'];
            $action = $route['action'];

            if (!empty($route['middlewares'])) {
                Middleware::handle($route['middlewares']);
            }

            if (class_exists($controller) && method_exists($controller, $action)) {
                $controllerInstance = new $controller();
                $controllerInstance->$action();
            } else {
                http_response_code(404);
                echo "Page not found.";
            }
        } else {
            http_response_code(404);
            echo "Page not found.";
        }
    }
}
