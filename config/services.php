<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('feskol_navigation.registry', NavigationRegistry::class)
        ->args([
            abstract_arg('.feskol_navigation.template'),
            abstract_arg('.feskol_navigation.active_as_link'),
        ])
    ;
};
