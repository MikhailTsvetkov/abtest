<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerInterface as SymfonyContainerInterface;

final readonly class ServiceContainer implements ContainerInterface
{
    public function __construct(private SymfonyContainerInterface $container) {}

    public function make(string $id): object
    {
        return $this->container->get($id);
    }

    public function get(string $id): mixed
    {
        return $this->container->get($id);
    }

    public function has(string $id): bool
    {
        return $this->container->has($id);
    }
}
