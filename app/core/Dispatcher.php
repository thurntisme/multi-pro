<?php
declare(strict_types=1);

namespace App\Core;

use ReflectionMethod;
use Framework\Exceptions\PageNotFoundException;
use UnexpectedValueException;

class Dispatcher
{
    public function __construct(
        private Router $router,
        private Container $container,
        private array $middleware_classes
    ) {
    }

    public function handle(Request $request): Response
    {
        $path = $this->getPath($request->uri);

        $params = $this->router->match($path, $request->method);

        if ($params === false) {
            die("No route matched for '$path' with method '{$request->method}'");
        }

        $controller = $params[0];
        $action = $params[1];
        $middleware_params = $params[2] ?? '';

        $controller_object = $this->container->get($controller);

        $controller_object->setViewer($this->container->get(TemplateViewerInterface::class));

        $controller_object->setResponse($this->container->get(Response::class));

        $args = $this->getActionArguments($controller, $action, $params);

        $controller_handler = new ControllerRequestHandler(
            $controller_object,
            $action,
            $args
        );

        $middlewares = $this->getMiddleware($middleware_params);

        $middleware_handler = new MiddlewareRequestHandler(
            $middlewares,
            $controller_handler
        );

        return $middleware_handler->handle($request);
    }

    private function getMiddleware(string $params): array
    {
        if (empty($params)) {
            return [];
        }

        $middleware = explode("|", $params);

        array_walk($middleware, function (&$value) {
            if (!array_key_exists($value, $this->middleware_classes)) {
                throw new UnexpectedValueException("Middleware '$value' not found in config settings");
            }

            $value = $this->container->get($this->middleware_classes[$value]);
        });

        return $middleware;
    }

    private function getActionArguments(string $controller, string $action, array $params): array
    {
        $args = [];

        $method = new ReflectionMethod($controller, $action);

        foreach ($method->getParameters() as $parameter) {

            $name = $parameter->getName();

            $args[$name] = $params[$name];

        }

        return $args;
    }

    private function getControllerName(array $params): string
    {
        $controller = $params["controller"];

        $controller = str_replace("-", "", ucwords(strtolower($controller), "-"));

        $namespace = "App\Controllers";

        if (array_key_exists("namespace", $params)) {

            $namespace .= "\\" . $params["namespace"];

        }

        return $namespace . "\\" . $controller;
    }

    private function getActionName(array $params): string
    {
        $action = $params["action"];

        $action = lcfirst(str_replace("-", "", ucwords(strtolower($action), "-")));

        return $action;
    }

    private function getPath(string $uri): string
    {
        $path = parse_url($uri, PHP_URL_PATH);

        if ($path === false) {

            throw new UnexpectedValueException("Malformed URL: '$uri'");

        }

        return $path;
    }
}