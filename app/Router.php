<?php
class Router {
    private $routes = [];

    public function add($method, $path, $controller, $action) {
        $path = str_replace('/', '\/', $path);
        $path = preg_replace('/\{[a-zA-Z0-9_]+\}/', '([a-zA-Z0-9_-]+)', $path);
        
        $this->routes[] = [
            'method' => $method,
            'path' => '/^' . $path . '$/',
            'controller' => $controller,
            'action' => $action
        ];
    }
    
    public function get($path, $controller, $action) {
        $this->add('GET', $path, $controller, $action);
    }
    
    public function post($path, $controller, $action) {
        $this->add('POST', $path, $controller, $action);
    }

    public function dispatch($url) {
        $method = $_SERVER['REQUEST_METHOD'];
        $url = "/" . trim($url, '/');
        if($url == '//') rtrim($url, '/');
        
        foreach ($this->routes as $route) {
            if ($route['method'] === $method && preg_match($route['path'], $url, $matches)) {
                array_shift($matches); // remove full match
                $controllerName = $route['controller'];
                $controller = new $controllerName();
                call_user_func_array([$controller, $route['action']], $matches);
                return;
            }
        }

        http_response_code(404);
        require_once '../app/views/404.php';
    }
}
