<?php
class Router
{
    private $routes = [];

    public function get($pattern, $callback): void
    {
        $this->addRoute('GET', $pattern, $callback);
    }

    private function addRoute($method, $pattern, $callback): void
    {
        $this->routes[] = [
            'method' => $method,
            'pattern' => '#^' . $pattern . '$#',
            'callback' => $callback,
        ];
    }

    public function dispatch($method, $uri)
    {
        $path = parse_url($uri, PHP_URL_PATH);

        foreach ($this->routes as $route) {
            if ($route['method'] === $method &&
                preg_match($route['pattern'], $path, $matches)) {
                array_shift($matches); // remove full match (e.g. /user/42 -> 42)
                return call_user_func_array($route['callback'], $matches);
            }
        }

        // If no route matches
        http_response_code(404);
        echo "404 Not Found";
    }
}
