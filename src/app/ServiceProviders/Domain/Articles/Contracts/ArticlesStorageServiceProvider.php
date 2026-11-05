<?php

namespace App\ServiceProviders\Domain\Articles\Contracts;

use App\DependencyInjection\ServiceProviderInterface;
use App\Domain\Articles\Contracts\ArticlesStorage;
use App\Domain\Articles\Contracts\MysqlArticlesStorage;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class ArticlesStorageServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->setAlias(
            ArticlesStorage::class,
            MysqlArticlesStorage::class,
        );
    }
}
