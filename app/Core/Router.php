<?php

namespace App\Core;

class Router
{
    private array $routes = [];
    private Session $session;

    public function __construct()
    {
        // Ne pas appeler App::getInstance() dans le constructeur
        // La session sera initialisée plus tard si nécessaire
    }

    private function getSession(): Session
    {
        if (!isset($this->session)) {
            $session = App::getDependency('session');
        }
        return $this->session;
    }

    public function addRoute(string $method, string $path, string $controller, string $action, array $middlewares = [])
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $path,
            'controller' => $controller,
            'action' => $action,
            'middlewares' => $middlewares
        ];
    }

    public function get(string $path, string $controller, string $action, array $middlewares = [])
    {
        $this->addRoute('GET', $path, $controller, $action, $middlewares);
    }

    public function post(string $path, string $controller, string $action, array $middlewares = [])
    {
        $this->addRoute('POST', $path, $controller, $action, $middlewares);
    }

    public function dispatch(string $method, string $uri)
    {
        $method = strtoupper($method);
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && $this->matchPath($route['path'], $uri)) {
 
                if (!empty($route['middlewares'])) {
                    Middleware::handle($route['middlewares']);
                }

                $controllerClass = $route['controller'];
                $action = $route['action'];
                
                if (class_exists($controllerClass)) {
                    $controller = new $controllerClass();
                    
                    if (method_exists($controller, $action)) {
                        return $controller->$action();
                    }
                }
                
                throw new \Exception("Contrôleur ou action non trouvé : {$controllerClass}::{$action}");
            }
        }
        
        http_response_code(404);
        echo "Page non trouvée";
    }

    public function resolve()
    {
        try {
            $method = $_SERVER['REQUEST_METHOD'];
            $uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        
            require dirname(__DIR__, 2) . '/routes/route.web.php';
            
            foreach ($routes as $path => $route) {
                if ($this->matchPath($path, $uri)) {

                    if (!empty($route['middlewares'])) {
                        Middleware::handle($route['middlewares']);
                    }
                    
                    $controllerClass = $route['controller'];
                    $action = $route['action'];
                    
                    if (!class_exists($controllerClass)) {
                        throw new \Exception("Contrôleur non trouvé: {$controllerClass}");
                    }
                    
                    $controller = new $controllerClass();
                    if (!method_exists($controller, $action)) {
                        throw new \Exception("Action non trouvée: {$action}");
                    }
                    
                    return $controller->$action();
                }
            }
            
            
        } catch (\Exception $e) {
            error_log($e->getMessage());
            http_response_code(500);
            echo "Erreur serveur: " . $e->getMessage();
        }
    }

    private function matchPath(string $routePath, string $uri): bool 
    {
        return rtrim($routePath, '/') === rtrim($uri, '/');
    }

    public function redirect(string $url)
    {
        header("Location: {$url}");
        exit;
    }
}