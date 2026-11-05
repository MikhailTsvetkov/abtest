<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class ServiceProviderLoader
{
    public function load(ContainerBuilder $container, string $file): void
    {
        if (!is_file($file)) {
            return;
        }

        $providers = (static fn (string $path): mixed => require $path)($file);

        if (!is_array($providers) || !array_is_list($providers)) {
            throw new InvalidArgumentException(sprintf(
                'Service provider configuration "%s"' .
                ' must return a list of ServiceProviderInterface instances.',
                $file,
            ));
        }

        $this->register($container, $providers);
    }

    /** @param list<ServiceProviderInterface> $providers */
    public function register(ContainerBuilder $container, array $providers): void
    {
        // Validate the complete list before applying any provider configuration.
        foreach ($providers as $index => $provider) {
            if (!$provider instanceof ServiceProviderInterface) {
                throw new InvalidArgumentException(sprintf(
                    'Service provider at index "%s" must implement %s; %s given.',
                    $index,
                    ServiceProviderInterface::class,
                    get_debug_type($provider),
                ));
            }
        }

        foreach ($providers as $provider) {
            $provider->register($container);
        }
    }
}
