<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler;

use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use Feskol\Bundle\NavigationBundle\Twig\NavigationRuntimeInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NavigationPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(NavigationRegistryInterface::class)) {
            $container->setAlias(NavigationRegistryInterface::class, 'feskol_navigation.registry')
                ->setPublic(true);
        }

        if (!$container->has(NavigationRuntimeInterface::class)) {
            $container->setAlias(NavigationRuntimeInterface::class, 'feskol_navigation.twig.runtime');
        }
    }
}
