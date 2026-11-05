<?php

namespace App\ServiceProviders\Database\Contracts;

use App\Config\Contracts\Config;
use App\Database\Contracts\Db as DbContract;
use App\Database\Db as DbImplementation;
use App\DependencyInjection\ServiceProviderInterface;
use PDO;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final readonly class DbServiceProvider implements ServiceProviderInterface
{
    public function __construct(private Config $config) {}

    public function register(ContainerBuilder $container): void
    {
        $config = $this->config->get('database');

        $container->getDefinition(DbImplementation::class)
            ->setArgument('$pdo', new PDO(
                "mysql:host={$config['host']};port={$config['port']};"
                . "dbname={$config['database']};charset=utf8mb4",
                $config['username'],
                $config['password'],
                [
                    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                    PDO::ATTR_EMULATE_PREPARES => 0,
                    PDO::MYSQL_ATTR_INIT_COMMAND => "SET time_zone = '+00:00'",
                ]
            ));

        $container->setAlias(DbContract::class, DbImplementation::class);
    }
}
