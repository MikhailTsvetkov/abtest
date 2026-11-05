<?php

declare(strict_types=1);

namespace App\ServiceProviders;

use App\DependencyInjection\ServiceProviderInterface;
use App\View\PageRenderer;
use App\View\SmartyFactory;
use Smarty\Smarty;
use Symfony\Component\DependencyInjection\ContainerBuilder;

final class SmartyServiceProvider implements ServiceProviderInterface
{
    public function register(ContainerBuilder $container): void
    {
        $container->register(Smarty::class, Smarty::class)
            ->setFactory([SmartyFactory::class, 'create'])
            ->setArgument(0, dirname(__DIR__, 3));

        // The front controller also uses the renderer for error pages.
        $container->getDefinition(PageRenderer::class)->setPublic(true);
    }
}
