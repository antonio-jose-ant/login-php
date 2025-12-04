<?php

namespace App\Router;

class Router
{
    private static array $routes = [];

    public static function add(string $method, string $path, callable $action)
    {
        self::$routes[] = [
            'method' => strtoupper($method),
            'path'   => $path,
            'action' => $action
        ];
    }

    public static function get(string $path, callable $action)
    {
        self::add('GET', $path, $action);
    }

    public static function post(string $path, callable $action)
    {
        self::add('POST', $path, $action);
    }

    public static function put(string $path, callable $action)
    {
        self::add('PUT', $path, $action);
    }

    public static function delete(string $path, callable $action)
    {
        self::add('DELETE', $path, $action);
    }

    public static function dispatch($prs)
    {
        $requestUri = parse_url($prs, PHP_URL_PATH);
        $method      = $_SERVER['REQUEST_METHOD'];

        foreach (self::$routes as $route) {
            // Convertir rutas dinámicas "/users/{id}" a regex
            $pattern = preg_replace('#\{[a-zA-Z_]+\}#', '([a-zA-Z0-9_-]+)', $route['path']);
            $pattern = '#^' . $pattern . '$#';

            if ($route['method'] === $method && preg_match($pattern, $requestUri, $matches)) {
                array_shift($matches); // remover coincidencia completa
                return call_user_func_array($route['action'], $matches);
            }
        }

        http_response_code(404);
        echo json_encode(["error" => "Ruta no encontrada","data"=>[$requestUri,$method ]]);
        exit;
    }
}
