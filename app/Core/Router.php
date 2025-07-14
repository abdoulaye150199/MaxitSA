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
        
        // Debug
        error_log("URI parsée: " . $uri);
        error_log("Routes disponibles: " . print_r(array_keys($routes), true));

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
                error_log("Contrôleur non trouvé: " . $controller);
                $this->notFound();
            }
        } else {
            error_log("Route non trouvée pour URI: " . $uri);
            $this->notFound();
        }
    }

    private function notFound(): void
    {
        http_response_code(404);
        echo "Page not found. URI: " . $_SERVER['REQUEST_URI'];
        echo "<br>Routes disponibles: " . implode(', ', array_keys($GLOBALS['routes'] ?? []));
    }
}