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
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use ReflectionClass;
use Symfony\Component\DependencyInjection\Compiler\CompilerPassInterface;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NavigationCompilerPass implements CompilerPassInterface
{
    public function process(ContainerBuilder $container): void
    {
        if (!$container->has(NavigationRegistry::class)) {
            return;
        }

        $registryDef = $container->findDefinition(NavigationRegistry::class);

        // Find all services tagged with 'navigation'
        foreach ($container->findTaggedServiceIds('feskol.navigation') as $id => $tags) {
            $definition = $container->getDefinition($id);
            $class = $definition->getClass();

            if (!$class) {
                continue;
            }

            $reflection = new ReflectionClass($class);
            $attributes = $reflection->getAttributes(Navigation::class);
            foreach ($attributes as $attribute) {
                /** @var Navigation $instance */
                $instance = $attribute->newInstance();

                $registryDef->addMethodCall('addNavigation', [
                    $instance->getName(),
                    new Reference($id),
                    $instance->getTemplate(),
                    $instance->getActiveAsLink()
                ]);
            }
        }
    }
}