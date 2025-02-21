<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Feskol\Bundle\NavigationBundle\Navigation\DefaultNavigationCompiler;
use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('feskol_navigation.navigation_registry', NavigationRegistry::class)
        ->args([
            abstract_arg('.feskol_navigation.template'),
            abstract_arg('.feskol_navigation.active_as_link'),
        ])
        ->alias(NavigationRegistryInterface::class, 'feskol_navigation.navigation_registry')

        ->set('feskol_navigation.link_service', LinkService::class)
        ->args([
            service('request_stack'),
            service(UrlGeneratorInterface::class),
        ])

        ->set('feskol_navigation.navigation_compiler.default', DefaultNavigationCompiler::class)
        ->args([
            service('feskol_navigation.link_service'),
        ])
        ->tag('feskol_navigation.navigation_compiler')
    ;
};
