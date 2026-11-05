<?php

declare(strict_types=1);

require_once __DIR__ . '/vendor/autoload.php';

use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\YamlFileLoader;
use Symfony\Component\Config\FileLocator;
use App\DependencyInjection\RegisterConstructorDependenciesPass;
use App\DependencyInjection\ServiceProviderLoader;
use App\DependencyInjection\Container;
use App\DependencyInjection\ContainerInterface;

$container = new ContainerBuilder();

$loader = new YamlFileLoader($container, new FileLocator(__DIR__ . '/src/config'));
$loader->load('services.yml');

(new ServiceProviderLoader())->load($container, __DIR__ . '/src/config/providers.php');

// Discover dependencies before Symfony processes autoconfiguration and autowiring.
$container->addCompilerPass(new RegisterConstructorDependenciesPass(), priority: 200);

$container->compile();

Container::setContainer($container->get(ContainerInterface::class));
