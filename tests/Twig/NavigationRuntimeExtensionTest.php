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
        $runtime = new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $this->getTwigEnvironment()
        );

        $result = $runtime->renderNavigation('fooNavigation');

        $this->assertEquals('Default template loaded!', $result);
    }

    public function testRenderNotExistingNavigation(): void
    {
        $runtime = new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $this->getTwigEnvironment()
        );

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

        $runtime = new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $this->getTwigEnvironment('override-nav.html.twig', 'Override template loaded!')
        );

        $result = $runtime->renderNavigation('overrideTemplateNavigation');

        $this->assertEquals('Override template loaded!', $result);
    }

    public function testContextAvailable(): void
    {
        $runtime = new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $this->getTwigEnvironment(
                self::DEFAULT_TEMPLATE,
                'Items: {{ items|length }} available. activeAsLink: {{ options.activeAsLink ? "true" : "false" }}'
            )
        );

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

        $runtime = new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $this->getTwigEnvironment(
                self::DEFAULT_TEMPLATE,
                'activeAsLink: {{ options.activeAsLink ? "true" : "false" }}'
            )
        );

        $result = $runtime->renderNavigation('overrideActiveAsLinkNavigation');

        $this->assertEquals('activeAsLink: true', $result);
    }

    public function testAdditionalContext(): void
    {
        $runtime = new NavigationRuntimeExtension(
            $this->navigationRegistry,
            $this->getTwigEnvironment(
                self::DEFAULT_TEMPLATE,
                'testBool: {{ testBool ? "true" : "false" }}; testArray: {{ testArray.first }}, {{ testArray.second }}'
            )
        );

        $result = $runtime->renderNavigation('fooNavigation', [
            'testBool' => true,
            'testArray' => [
                'first' => 'FirstElement',
                'second' => 'SecondElement',
            ],
        ]);

        $this->assertEquals('testBool: true; testArray: FirstElement, SecondElement', $result);
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
}
