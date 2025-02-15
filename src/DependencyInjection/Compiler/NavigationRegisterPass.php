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
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\NavigationAttributeInterface;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NavigationRegisterPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has('feskol_navigation.registry')
            || !$container->has('feskol_navigation.attribute.navigation_attribute')) {
            return;
        }

        $registryDef = $container->findDefinition('feskol_navigation.registry');
        $attributeDef = $container->findDefinition('feskol_navigation.attribute.navigation_attribute');

        // Find all services tagged with 'navigation'
        foreach ($container->findTaggedServiceIds('feskol_navigation.navigation') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();

            if (!$class) {
                continue;
            }

            $reflection = new \ReflectionClass($class);
            $attributes = $reflection->getAttributes($attributeDef->getClass());
            foreach ($attributes as $attribute) {
                /** @var NavigationAttributeInterface $instance */
                $instance = $attribute->newInstance();

                $registryDef->addMethodCall('addNavigation', [
                    $instance->getName(),
                    new Reference($id),
                    $instance,
                ]);
            }
        }
    }
}
