<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Feskol\Bundle\NavigationBundle\Twig\NavigationExtension;
use Feskol\Bundle\NavigationBundle\Twig\NavigationRuntimeExtension;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('feskol_navigation.twig.extension', NavigationExtension::class)
        ->tag('twig.extension')

        ->set('feskol_navigation.twig.runtime', NavigationRuntimeExtension::class)
        ->tag('twig.runtime')
        ->args([
            service('feskol_navigation.navigation_registry'),
            service('feskol_navigation.navigation_processor_runner'),
            service('twig')
        ])
    ;
};
