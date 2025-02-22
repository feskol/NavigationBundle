<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\DependencyInjection\Compiler;

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationRegisterPass;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\DependencyInjection\Compiler\FooNavigationWithoutAttribute;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooActiveAsLinkNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooMultipleNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooTemplateNavigation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Definition;
use Symfony\Component\DependencyInjection\Reference;

class NavigationRegisterPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private NavigationRegisterPass $pass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->containerBuilder->register(NavigationRegistry::class, NavigationRegistry::class)
            ->setArguments(['templates/_navigation.html.twig', false]);

        $this->pass = new NavigationRegisterPass();
    }

    private function getNavigationRegistryDefinition(): Definition
    {
        return $this->containerBuilder->getDefinition(NavigationRegistry::class);
    }

    public function testProcessSkipsWithoutNavigationClasses(): void
    {
        $this->containerBuilder->register(FooNavigationWithoutAttribute::class, FooNavigationWithoutAttribute::class);

        $navigationRegistryDef = $this->getNavigationRegistryDefinition();

        $this->assertCount(0, $this->containerBuilder->findTaggedServiceIds('feskol_navigation.navigation'));
        $this->assertFalse($navigationRegistryDef->hasMethodCall('addNavigation'));

        $this->pass->process($this->containerBuilder);

        $this->assertFalse($navigationRegistryDef->hasMethodCall('addNavigation'));
    }

    public function testProcess(): void
    {
        $this->containerBuilder->register(FooNavigation::class, FooNavigation::class)
            ->addTag('feskol_navigation.navigation');
        $this->containerBuilder->register(FooTemplateNavigation::class, FooTemplateNavigation::class)
            ->addTag('feskol_navigation.navigation');
        $this->containerBuilder->register(FooActiveAsLinkNavigation::class, FooActiveAsLinkNavigation::class)
            ->addTag('feskol_navigation.navigation');

        $navigationRegistryDef = $this->getNavigationRegistryDefinition();

        $this->assertFalse($navigationRegistryDef->hasMethodCall('addNavigation'));

        $this->pass->process($this->containerBuilder);

        $this->assertTrue($navigationRegistryDef->hasMethodCall('addNavigation'));
        $this->assertCount(3, $navigationRegistryDef->getMethodCalls());

        $this->assertEquals(
            [
                ['addNavigation', ['mainNavigation', new Reference(FooNavigation::class), null, null]],
                ['addNavigation', ['TemplateNavigation', new Reference(FooTemplateNavigation::class), '@TestTemplate/test.html.twig', null]],
                ['addNavigation', ['ActiveAsLinkNavigation', new Reference(FooActiveAsLinkNavigation::class), null, true]],
            ],
            $navigationRegistryDef->getMethodCalls()
        );
    }

    public function testProcessWithNavigationWithMultipleAttributes(): void
    {
        $this->containerBuilder->register(FooMultipleNavigation::class, FooMultipleNavigation::class)
            ->addTag('feskol_navigation.navigation');

        $navigationRegistryDef = $this->getNavigationRegistryDefinition();
        $this->assertFalse($navigationRegistryDef->hasMethodCall('addNavigation'));

        $this->pass->process($this->containerBuilder);

        $this->assertTrue($navigationRegistryDef->hasMethodCall('addNavigation'));
        $this->assertCount(2, $navigationRegistryDef->getMethodCalls());

        $this->assertEquals(
            [
                ['addNavigation', ['firstNavigation', new Reference(FooMultipleNavigation::class), 'first/navigation.html.twig', true]],
                ['addNavigation', ['secondNavigation', new Reference(FooMultipleNavigation::class), 'second/navigation.html.twig', false]],
            ],
            $navigationRegistryDef->getMethodCalls()
        );
    }
}
