<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('feskol_navigation.registry', NavigationRegistry::class)
        ->args([
            abstract_arg('.feskol_navigation.template'),
            abstract_arg('.feskol_navigation.active_as_link'),
        ])
        ->alias(NavigationRegistryInterface::class, 'feskol_navigation.registry')

        ->set('feskol_navigation.link_service', LinkService::class)
        ->args([
            service('request_stack'),
            service(UrlGeneratorInterface::class),
        ])
    ;
};
