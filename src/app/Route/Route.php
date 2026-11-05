<?php

namespace App\Route;

use App\DependencyInjection\Container;

final class Route
{
    private static self $instance;
    private array $vars = [];

    private function __construct(
        private readonly string $controller,
        private readonly string $action,
    ) {}

    private static function getInstance(string $controller, string $action): self
    {
        self::$instance ??= new self($controller, $action);

        return self::$instance;
    }

    public static function get(
        string $uri,
        string $controller,
        string $action,
    ): void {
        if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
            return;
        }

        $route = self::getInstance($controller, $action);

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

        $route = self::getInstance($controller, $action);

        if (!$route->checkUri($uri)) {
            return;
        }

        $route->run();
    }

    public static function responseNotFound(): void
    {
        header($_SERVER["SERVER_PROTOCOL"] . " 404 Not Found");

        exit;
    }

    private function checkUri(string $routeUri): bool
    {
        // Получаем $requestUri из $_SERVER['REQUEST_URI'], отбрасывая query
        $requestUri = strtok($_SERVER['REQUEST_URI'], '?');

        // Удаляем оборачивающие слэши из $route_uri и $requestUri и приводим к нижнему регистру
        $routeUri = strtolower(trim($routeUri, '/'));
        $requestUri = strtolower(trim($requestUri, '/'));

        // Получаем переменные, если они есть
        $vars = [];
        preg_match_all('/\$([a-zA-Z0-9_]+)/', $routeUri, $vars);
        $varNames = $vars[1];

        // Заменяем переменные на регулярные выражения, если они есть
        $routeUriRegex = preg_replace_callback(
            '/\$([a-zA-Z0-9_]+)/',
            fn ($matches) => '([a-zA-Z0-9_-]+)',
            $routeUri,
        );

        if (preg_match('#^' . $routeUriRegex . '$#', $requestUri, $matches)) {
            // Извлекаем значения переменных
            $varValues = array_slice($matches, 1);

            // Сохраняем переменные, если они есть, в массиве $this->vars
            if (!empty($varNames)) {
                foreach ($varNames as $index => $varName) {
                    $this->vars[$varName] = $varValues[$index];
                }
            }

            return true;
        }

        return false;
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
        die ($controller->{$this->action}(...$this->vars));
    }
}
