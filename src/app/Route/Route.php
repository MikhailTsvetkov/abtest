<?php

namespace App\Route;

use App\DependencyInjection\Container;
use App\Http\JsonResponse;

final class Route
{
    private array $vars = [];

    private function __construct(
        private readonly string $controller,
        private readonly string $action,
    ) {}

    public static function get(
        string $uri,
        string $controller,
        string $action,
    ): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return;
        }

        $route = new self($controller, $action);

        if (!$route->checkUri($uri)) {
            return;
        }

        $route->run();
    }

    public static function post(string $uri, string $controller, string $action): void
    {
        if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
            return;
        }

        $route = new self($controller, $action);

        if (!$route->checkUri($uri)) {
            return;
        }

        $route->run();
    }

    public static function responseNotFound(): never
    {
        (new JsonResponse(['error' => 'Страница не найдена.'], 404))->send();
    }

    private function checkUri(string $routeUri): bool
    {
        $requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        if (!is_string($requestUri)) {
            return false;
        }

        $parts = [];
        foreach (explode('/', trim($routeUri, '/')) as $part) {
            $parts[] = preg_match('/^\$([a-zA-Z_][a-zA-Z0-9_]*)$/D', $part, $match)
                ? '(?P<' . $match[1] . '>[a-zA-Z0-9_-]+)'
                : preg_quote($part, '#');
        }

        if (!preg_match('#^' . implode('/', $parts) . '$#D', trim($requestUri, '/'), $matches)) {
            return false;
        }

        foreach ($matches as $name => $value) {
            if (is_string($name)) {
                $this->vars[$name] = $value;
            }
        }

        return true;
    }

    private function run(): void
    {
        // Создаём экземпляр контроллера
        $controller = Container::make($this->controller);

        // Если экшен не существует - 404
        if (!is_callable(array($controller, $this->action))) {
            self::responseNotFound();
        }

        // Выполняем экшен
        $response = $controller->{$this->action}(...$this->vars);
        if ($response instanceof JsonResponse) {
            $response->send();
        }

        echo $response;
        exit;
    }
}
