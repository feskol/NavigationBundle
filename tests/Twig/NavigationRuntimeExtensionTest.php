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
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use Feskol\Bundle\NavigationBundle\Navigation\Processor\NavigationProcessorRunner;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Twig\FooNavigation;
use Feskol\Bundle\NavigationBundle\Twig\NavigationRuntimeExtension;
use PHPUnit\Framework\TestCase;
use Twig\Environment;
use Twig\Loader\ArrayLoader;

class NavigationRuntimeExtensionTest extends TestCase
{
    private const DEFAULT_TEMPLATE = 'test-nav.html.twig';
    private NavigationRegistryInterface $navigationRegistry;

    protected function setUp(): void
    {
        $this->navigationRegistry = new NavigationRegistry(
            self::DEFAULT_TEMPLATE,
            false
        );
        $this->navigationRegistry->addNavigation(
            'fooNavigation',
            new FooNavigation()
        );
    }

    public function testRenderNavigation(): void
    {
        $runtime = $this->getNavigationRuntimeExtension($this->getTwigEnvironment());

        $result = $runtime->renderNavigation('fooNavigation');

        $this->assertEquals('Default template loaded!', $result);
    }

    public function testRenderNotExistingNavigation(): void
    {
        $runtime = $this->getNavigationRuntimeExtension($this->getTwigEnvironment());

        $this->expectException(\RuntimeException::class);
        $this->expectExceptionMessage('The navigation with name "notExistingNavigation" does not exist.');

        $runtime->renderNavigation('notExistingNavigation');
    }

    public function testOverrideTemplateLoading(): void
    {
        $this->navigationRegistry->addNavigation(
            'overrideTemplateNavigation',
            new FooNavigation(),
            'override-nav.html.twig'
        );

        $runtime = $this->getNavigationRuntimeExtension($this->getTwigEnvironment(
            'override-nav.html.twig',
            'Override template loaded!'
        ));

        $result = $runtime->renderNavigation('overrideTemplateNavigation');

        $this->assertEquals('Override template loaded!', $result);
    }

    public function testContextAvailable(): void
    {
        $runtime = $this->getNavigationRuntimeExtension($this->getTwigEnvironment(
            self::DEFAULT_TEMPLATE,
            'Items: {{ items|length }} available. activeAsLink: {{ options.activeAsLink ? "true" : "false" }}'
        ));

        $result = $runtime->renderNavigation('fooNavigation');

        $this->assertEquals('Items: 3 available. activeAsLink: false', $result);
    }

    public function testOverrideOptions(): void
    {
        $this->navigationRegistry->addNavigation(
            'overrideActiveAsLinkNavigation',
            new FooNavigation(),
            null,
            true
        );

        $runtime = $this->getNavigationRuntimeExtension($this->getTwigEnvironment(
            self::DEFAULT_TEMPLATE,
            'activeAsLink: {{ options.activeAsLink ? "true" : "false" }}'
        ));

        $result = $runtime->renderNavigation('overrideActiveAsLinkNavigation');

        $this->assertEquals('activeAsLink: true', $result);
    }

    private function getTwigEnvironment(
        string $templateName = 'index',
        string $templateContent = 'Index template',
        array $defaultTemplates = [
            self::DEFAULT_TEMPLATE => 'Default template loaded!',
        ],
    ): Environment {
        return new Environment(new ArrayLoader(\array_merge($defaultTemplates, [
            $templateName => $templateContent,
        ])));
    }

    private function getNavigationRuntimeExtension(Environment $twigEnvironment): NavigationRuntimeExtension
    {
        return new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $twigEnvironment,
            new NavigationProcessorRunner()
        );
    }
}
