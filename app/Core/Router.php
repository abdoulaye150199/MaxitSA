<?php

namespace App\Core;

use App\Core\App;

class Router
{
    private App $app;

    public function __construct()
    {
        $this->app = App::getInstance();
    }

    public function resolve()
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

            if (class_exists($controller)) {
                $controllerInstance = $this->app->resolve($controller);
                
                if (method_exists($controllerInstance, $action)) {
                    $controllerInstance->$action();
                } else {
                    $this->notFound();
                }
            } else {
                $this->notFound();
            }
        } else {
            $this->notFound();
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo "Page not found.";
    }
}