<?php
declare(strict_types=1);

namespace App\Core;

class Router
{
    private array $routes = [];

    public function get(string $path, callable|array $action): void
    {
        $this->addRoute('GET', $path, $action);
    }

    public function post(string $path, callable|array $action): void
    {
        $this->addRoute('POST', $path, $action);
    }

    public function put(string $path, callable|array $action): void
    {
        $this->addRoute('PUT', $path, $action);
    }

    public function delete(string $path, callable|array $action): void
    {
        $this->addRoute('DELETE', $path, $action);
    }

    private function addRoute(string $method, string $path, callable|array $params): void
    {
        // Convert {param} to named regex
        $pattern = preg_replace('#\{([a-zA-Z_][a-zA-Z0-9_]*)\}#', '(?P<$1>[^/]+)', $path);
        $pattern = "#^$pattern$#";

        $this->routes[] = [
            'method' => $method,
            'path' => $path,
            'pattern' => $pattern,
            'params' => $params,
        ];
    }

    public function match(string $path, string $method): array|bool
    {
        $path = urldecode($path);
        $path = trim($path, "/");

        foreach ($this->routes as $route) {
            $pattern = $this->getPatternFromRoutePath($route["path"]);
            if (preg_match($pattern, $path, $matches)) {
                $matches = array_filter($matches, "is_string", ARRAY_FILTER_USE_KEY);
                $params = array_merge($matches, $route["params"], ['method' => $route["method"]]);
                if (array_key_exists("method", $params)) {
                    if (strtolower($method) !== strtolower($params["method"])) {
                        continue;
                    }
                }
                return $params;
            }
        }

        return false;
    }

    private function getPatternFromRoutePath(string $route_path): string
    {
        $route_path = trim($route_path, "/");
        $segments = explode("/", $route_path);
        $segments = array_map(function (string $segment): string {
            if (preg_match("#^\{([a-z][a-z0-9]*)\}$#", $segment, $matches)) {
                return "(?<" . $matches[1] . ">[^/]*)";
            }
            if (preg_match("#^\{([a-z][a-z0-9]*):(.+)\}$#", $segment, $matches)) {
                return "(?<" . $matches[1] . ">" . $matches[2] . ")";
            }

            return $segment;
        }, $segments);

        return "#^" . implode("/", $segments) . "$#iu";
    }
}