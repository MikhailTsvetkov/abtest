<?php

declare(strict_types=1);

namespace App\Http\Request;

use App\Http\Exceptions\HttpException;

final class Request
{
    private function __construct() {}

    public static function wantsJson(): bool
    {
        return str_contains(strtolower($_SERVER['HTTP_ACCEPT'] ?? ''), 'application/json');
    }

    public static function id(string $value): int
    {
        $id = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1]],
        );

        if (!ctype_digit($value) || $id === false) {
            throw new HttpException(404, 'Страница не найдена.');
        }

        return $id;
    }

    public static function queryString(string $name, string $default): string
    {
        $value = $_GET[$name] ?? $default;

        if (!is_string($value)) {
            throw new HttpException(
                400, "Параметр $name должен быть строкой.",
            );
        }

        return $value;
    }

    public static function queryInt(string $name, int $default, int $max = PHP_INT_MAX): int
    {
        $value = self::queryString($name, (string) $default);

        $number = filter_var(
            $value,
            FILTER_VALIDATE_INT,
            ['options' => ['min_range' => 1, 'max_range' => $max]],
        );

        if (!ctype_digit($value) || $number === false) {
            throw new HttpException(
                400, "Параметр $name должен быть целым числом от 1 до $max.",
            );
        }

        return $number;
    }
}
