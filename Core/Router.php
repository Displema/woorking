<?php

namespace Core;

use JsonException;

class Router
{
    private $routes = [];

    public function get($pattern, $callback): void
    {
        $this->addRoute('GET', $pattern, $callback);
    }

    public function post($pattern, $callback): void
    {
        $this->addRoute('POST', $pattern, $callback);
    }

    private function addRoute($method, $pattern, $callback): void
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'callback' => $callback,
        ];
    }

    /**
     * @throws JsonException
     */
    public function dispatch($method, $uri)
    {
        $path = parse_url($uri, PHP_URL_PATH);
        foreach ($this->routes as $route) {
            if ($route['method'] === $method &&
                preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches); // remove full match (e.g. /user/42 -> 42)

                $matches[] = $_GET;
                // If POST and JSON, parse and add as last argument
                if ($method === 'POST') {
                    $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
                    if (str_contains($contentType, 'application/json')) {
                        $json = json_decode(file_get_contents('php://input'), true, 512, JSON_THROW_ON_ERROR);
                        $matches[] = $json;
                    } else {
                        // Optionally: add $_POST for form-encoded data
                        $matches[] = $_POST;
                    }
                }

                return call_user_func_array($route['callback'], $matches);
            }
        }


        // If no route matches
        http_response_code(404);
        echo "404 Not Found";
        return null;
    }
}
