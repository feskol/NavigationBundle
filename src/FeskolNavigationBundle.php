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

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\AutoTagNavigationPass;
use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationPass;
use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationRegisterPass;
use Symfony\Component\Config\Definition\Configurator\DefinitionConfigurator;
use Symfony\Component\DependencyInjection\Compiler\PassConfig;
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
                    ->defaultValue('_navigation.html.twig')
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
        $container->import('../config/services.php');
        $container->import('../config/twig.php');

        $container->parameters()
            ->set('feskol_navigation.template', $config['template'])
            ->set('feskol_navigation.active_as_link', $config['active_as_link'])
        ;
    }

    public function build(ContainerBuilder $container): void
    {
        $container
            ->addCompilerPass(new NavigationPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 100)
            ->addCompilerPass(new AutoTagNavigationPass(), PassConfig::TYPE_BEFORE_OPTIMIZATION, 50)
            ->addCompilerPass(new NavigationRegisterPass())
        ;
    }
}
