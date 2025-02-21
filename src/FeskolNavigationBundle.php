<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle;

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationRegisterPass;
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationCompilerInterface;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\ChildDefinition;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Loader\Configurator\ContainerConfigurator;
use Symfony\Component\HttpKernel\Bundle\AbstractBundle;

class FeskolNavigationBundle extends AbstractBundle
{
    public function configure(DefinitionConfigurator $definition): void
    {
        $definition->rootNode()
            ->children()
                ->scalarNode('template')
                    ->cannotBeEmpty()
                    ->info('Replace the default template rendering the navigation list')
                    ->defaultValue('@FeskolNavigation/_navigation.html.twig')
                ->end()
                ->booleanNode('active_as_link')
                    ->info('Set true to render an active navigation item as a link tag')
                    ->defaultFalse()
                ->end()
            ->end()
        ;
    }

    public function loadExtension(array $config, ContainerConfigurator $container, ContainerBuilder $builder): void
    {
        $builder->registerAttributeForAutoconfiguration(
            Navigation::class,
            static function (ChildDefinition $definition, Navigation $attribute): void {
                $definition->addTag('feskol_navigation.navigation');
            }
        );

        $builder->registerForAutoconfiguration(NavigationCompilerInterface::class)
            ->addTag('feskol_navigation.navigation_compiler');

        $container->import('../config/services.php');
        $container->import('../config/twig.php');

        $container->services()
            ->get('feskol_navigation.navigation_registry')
            ->args([
                $config['template'],
                $config['active_as_link'],
            ])
        ;
    }

    public function build(ContainerBuilder $container): void
    {
        $container->addCompilerPass(new NavigationRegisterPass());
    }
}
