<?php

namespace App\ServiceProviders\Domain\Categories\Contracts;

use App\DependencyInjection\ServiceProviderInterface;
use App\Domain\Categories\Contracts\CategoriesStorage;
use App\Domain\Categories\Contracts\MysqlCategoriesStorage;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class CategoriesStorageServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->setAlias(
            CategoriesStorage::class,
            MysqlCategoriesStorage::class,
        );
    }
}
