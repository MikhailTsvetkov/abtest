<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use LogicException;

final class Container
{
    private static ?ContainerInterface $container = null;

    private function __construct() {}

    public static function setContainer(ContainerInterface $container): void
    {
        self::$container = $container;
    }

    /**
     * @template T of object
     * @param class-string<T> $id
     * @return T
     */
    public static function make(string $id): object
    {
        return self::instance()->make($id);
    }

    public static function get(string $id): mixed
    {
        return self::instance()->get($id);
    }

    public static function has(string $id): bool
    {
        return self::instance()->has($id);
    }

    private static function instance(): ContainerInterface
    {
        return self::$container ?? throw new LogicException(
            'Container is not initialized. Load bootstrap.php before accessing services.',
        );
    }
}
