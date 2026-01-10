<?php

require_once __DIR__ . '/Route.php';

class Router {
    private array $routes = [];

    public function __construct() {
        $this->loadRoutes();
    }

    private function loadRoutes() {
        $controllerDir = __DIR__ . '/../controllers/';
        $files = scandir($controllerDir);
        foreach ($files as $file) {
            if (pathinfo($file, PATHINFO_EXTENSION) === 'php') {
                $className = pathinfo($file, PATHINFO_FILENAME);
                require_once $controllerDir . $file;
                $reflection = new ReflectionClass($className);
                foreach ($reflection->getMethods() as $method) {
                    $attributes = $method->getAttributes(Route::class);
                    if (!empty($attributes)) {
                        $routeAttr = $attributes[0]->newInstance();
                        $this->routes[] = [
                            'path' => $routeAttr->path,
                            'methods' => $routeAttr->methods,
                            'controller' => $className,
                            'action' => $method->getName(),
                            'params' => $this->extractParams($routeAttr->path)
                        ];
                    }
                }
            }
        }
    }

    private function extractParams(string $path): array {
        preg_match_all('/\{(\w+)\}/', $path, $matches);
        return $matches[1];
    }

    public function dispatch(string $uri, string $method) {
        $uri = parse_url($uri, PHP_URL_PATH);
        // Handle method spoofing for PUT/DELETE via POST
        if ($method === 'POST' && isset($_POST['_method'])) {
            $method = strtoupper($_POST['_method']);
        }
        foreach ($this->routes as $route) {
            if (!in_array($method, $route['methods'])) {
                continue;
            }
            $pattern = preg_replace('/\{(\w+)\}/', '([^/]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';
            if (preg_match($pattern, $uri, $matches)) {
                array_shift($matches); // Remove full match
                $controller = new $route['controller']();
                $params = array_combine($route['params'], $matches);
                // For methods that need additional POST data, they can access $_POST
                return call_user_func_array([$controller, $route['action']], $params);
            }
        }
        // No route found
        http_response_code(404);
        echo '404 Not Found';
    }
}