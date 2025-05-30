<?php
namespace Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|string $handler): void
    {
        $this->addRoute('GET', $path, $handler);
    }

    public function post(string $path, callable|string $handler): void
    {
        $this->addRoute('POST', $path, $handler);
    }

    public function addRoute(string $method, string $path, callable|string $handler): void
    {
        $this->routes[] = [
            'method' => strtoupper($method),
            'path' => $this->parseRoute($path),
            'handler' => $handler,
            'raw' => $path
        ];
    }

    public function dispatch(string $requestUri, string $requestMethod): void
    {
        $requestUri = parse_url($requestUri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if ($route['method'] !== strtoupper($requestMethod)) {
                continue;
            }

            $pattern = "@^" . preg_replace('/\{(\w+)\}/', '(?P<\1>[^/]+)', $route['raw']) . "$@D";

            if (preg_match($pattern, $requestUri, $matches)) {
                $params = array_filter($matches, 'is_string', ARRAY_FILTER_USE_KEY);

                if (is_callable($route['handler'])) {
                    call_user_func_array($route['handler'], $params);
                } elseif (is_string($route['handler'])) {
                    $this->callController($route['handler'], $params);
                }
                return;
            }
        }

        http_response_code(404);
        echo "404 Not Found";
    }

    private function callController(string $handler, array $params): void
    {
        [$class, $method] = explode('@', $handler);

        $class = "Controller\\$class";

        if (class_exists($class) && method_exists($class, $method)) {
            $controller = new $class;
            call_user_func_array([$controller, $method], $params);
        } else {
            http_response_code(500);
            echo "Handler not found: $handler";
        }
    }

    private function parseRoute(string $route): string
    {
        return rtrim($route, '/') ?: '/';
    }
}
