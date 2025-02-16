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

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NavigationRegisterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(NavigationRegistryInterface::class)) {
            return;
        }

        $taggedServiceIds = $container->findTaggedServiceIds('feskol_navigation.navigation');
        if (0 === \count($taggedServiceIds)) {
            return;
        }

        $registryDef = $container->findDefinition(NavigationRegistryInterface::class);

        // check the required methods of the NavigationRegistryInterface
        foreach (['addNavigation', 'getNavigation'] as $method) {
            if (!\method_exists($registryDef->getClass(), $method)) {
                throw new InvalidConfigurationException(\sprintf('The NavigationRegistry service "%s" must implement the "%s" method. Please implement the "%s" interface in your service class.', $registryDef->getClass(), $method, NavigationRegistryInterface::class));
            }
        }

        // Find all services tagged with 'navigation'
        foreach ($taggedServiceIds as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();
            if (!$class) {
                continue;
            }

            $reflection = new \ReflectionClass($class);
            $attributes = $reflection->getAttributes(Navigation::class);
            foreach ($attributes as $attribute) {
                /** @var Navigation $instance */
                $instance = $attribute->newInstance();

                $registryDef->addMethodCall('addNavigation', [
                    $instance->getName(),
                    new Reference($id),
                    $instance->getTemplate(),
                    $instance->getActiveAsLink(),
                ]);
            }
        }
    }
}
