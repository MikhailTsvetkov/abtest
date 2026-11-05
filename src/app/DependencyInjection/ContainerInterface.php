<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Psr\Container\ContainerInterface as PsrContainerInterface;

interface ContainerInterface extends PsrContainerInterface
{
    /**
     * @template T of object
     * @param class-string<T> $id
     * @return T
     */
    public function make(string $id): object;
}
