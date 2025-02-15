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

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\AutoTagNavigationCompilerPass;
use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationCompilerPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
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
                    ->defaultValue('@FeskolNavigationBundle/navigation.html.twig')
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
        $container->services()
            ->get('feskol_navigation.registry')
            ->arg(0, $config['template'])
            ->arg(1, $config['active_as_link'])
        ;
    }

    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new AutoTagNavigationCompilerPass())
            ->addCompilerPass(new NavigationCompilerPass())
        ;
    }
}