<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Twig;

use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Navigation\Processor\NavigationProcessorRunner;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Twig\FooNavigation;
use Feskol\Bundle\NavigationBundle\Twig\NavigationExtension;
use Feskol\Bundle\NavigationBundle\Twig\NavigationRuntimeExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;
use Twig\RuntimeLoader\FactoryRuntimeLoader;
use Twig\RuntimeLoader\RuntimeLoaderInterface;

class NavigationExtensionTest extends TestCase
{
    public function testExtension(): void
    {
        $twig = new Environment(new ArrayLoader([
            'index' => '{{ feskol_navigation_render("fooNavigation") }}',
            'test-nav.html.twig' => 'Template loaded!',
        ]));
        $twig->addExtension(new NavigationExtension());
        $twig->addRuntimeLoader($this->getExtensionRuntimeLoader($twig));

        $result = $twig->load('index')->render();

        $this->assertEquals('Template loaded!', $result);
    }

    private function getExtensionRuntimeLoader(Environment $twig): RuntimeLoaderInterface
    {
        $navigationRegistry = new NavigationRegistry(
            'test-nav.html.twig',
            false
        );
        $navigationRegistry->addNavigation(
            'fooNavigation',
            new FooNavigation()
        );

        return new FactoryRuntimeLoader([
            NavigationRuntimeExtension::class => fn () => new NavigationRuntimeExtension(
                $navigationRegistry,
                new NavigationProcessorRunner(),
                $twig
            ),
        ]);
    }
}
