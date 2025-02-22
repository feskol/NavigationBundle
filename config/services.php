<?php

namespace Symfony\Component\DependencyInjection\Loader\Configurator;

use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Navigation\Processor\DefaultNavigationProcessor;
use Feskol\Bundle\NavigationBundle\Navigation\Processor\NavigationProcessorRunner;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

return static function (ContainerConfigurator $container): void {
    $container->services()
        ->set('feskol_navigation.navigation_registry', NavigationRegistry::class)
        ->args([
            abstract_arg('.feskol_navigation.template'),
            abstract_arg('.feskol_navigation.active_as_link'),
        ])
        ->alias(NavigationRegistry::class, 'feskol_navigation.navigation_registry')

        ->set('feskol_navigation.link_service', LinkService::class)
        ->args([
            service('request_stack'),
            service(UrlGeneratorInterface::class),
        ])

        ->set('feskol_navigation.navigation_processor_runner', NavigationProcessorRunner::class)
        ->args([
            tagged_iterator('feskol_navigation.navigation_processor'),
        ])

        ->set('feskol_navigation.navigation_processor.default', DefaultNavigationProcessor::class)
        ->args([
            service('feskol_navigation.link_service'),
        ])
        ->tag('feskol_navigation.navigation_processor', ['priority' => 0]);
    ;
};
