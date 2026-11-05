<?php

declare(strict_types=1);

namespace App\DependencyInjection;

use ReflectionAttribute;
use ReflectionNamedType;
use Symfony\Component\DependencyInjection\Attribute\Autowire;
use Symfony\Component\DependencyInjection\Attribute\Target;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

/**
 * Discovers constructor dependencies; Symfony remains responsible for wiring them.
 */
final class RegisterConstructorDependenciesPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        $serviceIds = array_keys($container->getDefinitions());

        // New definitions extend the queue. Existing IDs stop discovery at cycles.
        for ($index = 0; $index < count($serviceIds); ++$index) {
            $definition = $container->getDefinition($serviceIds[$index]);

            if (
                !$definition->isAutowired()
                || $definition->isAbstract()
                || $definition->isSynthetic()
                || $definition->getFactory() !== null
            ) {
                continue;
            }

            // Symfony also allows the service ID to supply the class name.
            $class = $container->getParameterBag()->resolveValue(
                $definition->getClass() ?? $serviceIds[$index],
            );
            $constructor = $container->getReflectionClass(
                $class, false,
            )?->getConstructor();

            if ($constructor === null) {
                continue;
            }

            $arguments = $definition->getArguments();
            $bindings = $definition->getBindings();

            foreach ($constructor->getParameters() as $parameter) {
                $name = '$' . $parameter->getName();
                $type = $parameter->getType();

                // Defaults and explicit argument configuration take precedence over discovery.
                if (
                    !$type instanceof ReflectionNamedType || $type->isBuiltin()
                    || $parameter->isDefaultValueAvailable() || $parameter->isVariadic()
                    || array_key_exists($parameter->getPosition(), $arguments)
                    || array_key_exists($name, $arguments)
                    || array_key_exists($parameter->getName(), $arguments)
                ) {
                    continue;
                }

                if (
                    !$definition->hasTag('container.ignore_attributes')
                    && (
                        $parameter->getAttributes(
                            Autowire::class,
                            ReflectionAttribute::IS_INSTANCEOF,
                        )
                        || $parameter->getAttributes(Target::class)
                    )
                ) {
                    continue;
                }

                $dependency = match ($type->getName()) {
                    'self' => $constructor->getDeclaringClass()->getName(),
                    'parent' => $constructor->getDeclaringClass()->getParentClass()->getName(),
                    default => $type->getName(),
                };

                if (
                    array_key_exists($name, $bindings)
                    || array_key_exists($dependency, $bindings)
                    || array_key_exists($dependency.' '.$name, $bindings)
                    || $container->has($dependency)
                    || $container->has($dependency.' '.$name)
                ) {
                    continue;
                }

                $reflection = $container->getReflectionClass($dependency, false);

                // Interfaces, abstract classes and enums require explicit configuration.
                if ($reflection === null || !$reflection->isInstantiable()) {
                    continue;
                }

                $container->register($dependency, $dependency)
                    ->setAutowired(true)
                    ->setAutoconfigured(true)
                    ->setPublic(false);
                $serviceIds[] = $dependency;
            }
        }
    }
}
