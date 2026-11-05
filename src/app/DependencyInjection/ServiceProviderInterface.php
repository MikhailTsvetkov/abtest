<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use Symfony\Component\DependencyInjection\ContainerBuilder;

interface ServiceProviderInterface
{
    public function register(ContainerBuilder $container): void;
}
