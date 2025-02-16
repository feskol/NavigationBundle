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
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\DependencyInjection\Compiler\FooNavigationWithoutAttribute;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\DependencyInjection\Compiler\NavigationRegistryWithoutAddNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\DependencyInjection\Compiler\NavigationRegistryWithoutGetNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooActiveAsLinkNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooTemplateNavigation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Config\Definition\Exception\InvalidConfigurationException;
use Symfony\Component\DependencyInjection\ContainerBuilder;
use Symfony\Component\DependencyInjection\Reference;

class NavigationRegisterPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private NavigationRegisterPass $pass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->pass = new NavigationRegisterPass();
    }

    private function containerAddNavigationRegistry(): void
    {
        $this->containerBuilder->register(NavigationRegistryInterface::class, NavigationRegistry::class)
            ->setArguments(['templates/_navigation.html.twig', false]);
    }

    private function containerAddNavigationClasses(): void
    {
        $this->containerBuilder->register(FooNavigation::class, FooNavigation::class)
            ->addTag('feskol_navigation.navigation');
        $this->containerBuilder->register(FooTemplateNavigation::class, FooTemplateNavigation::class)
            ->addTag('feskol_navigation.navigation');
        $this->containerBuilder->register(FooActiveAsLinkNavigation::class, FooActiveAsLinkNavigation::class)
            ->addTag('feskol_navigation.navigation');
    }

    public function testProcessSkipsWithoutNavigationRegistry(): void
    {
        $this->containerAddNavigationClasses();

        $this->assertFalse($this->containerBuilder->hasDefinition(NavigationRegistryInterface::class));

        $this->pass->process($this->containerBuilder);

        $this->assertFalse($this->containerBuilder->hasDefinition(NavigationRegistryInterface::class));
        // if no error occurs, it's successful
    }

    public function testProcessSkipsWithoutNavigationClasses(): void
    {
        $this->containerAddNavigationRegistry();

        $this->containerBuilder->register(FooNavigationWithoutAttribute::class, FooNavigationWithoutAttribute::class);

        $navigationRegistryDef = $this->containerBuilder->getDefinition(NavigationRegistryInterface::class);

        $this->assertCount(0, $this->containerBuilder->findTaggedServiceIds('feskol_navigation.navigation'));
        $this->assertFalse($navigationRegistryDef->hasMethodCall('addNavigation'));

        $this->pass->process($this->containerBuilder);

        $this->assertFalse($navigationRegistryDef->hasMethodCall('addNavigation'));
    }

    public function testInvalidConfigurationForNavigationRegistryAddNavigation(): void
    {
        $this->containerAddNavigationClasses();

        $this->containerBuilder->register(NavigationRegistryInterface::class, NavigationRegistryWithoutAddNavigation::class);

        $this->expectException(InvalidConfigurationException::class);

        $this->pass->process($this->containerBuilder);
    }

    public function testInvalidConfigurationForNavigationRegistryGetNavigation(): void
    {
        $this->containerAddNavigationRegistry();
        $this->containerAddNavigationClasses();

        $this->containerBuilder->register(NavigationRegistryInterface::class, NavigationRegistryWithoutGetNavigation::class);

        $this->expectException(InvalidConfigurationException::class);

        $this->pass->process($this->containerBuilder);
    }

    public function testProcess(): void
    {
        $this->containerAddNavigationRegistry();
        $this->containerAddNavigationClasses();

        $navigationRegistryDef = $this->containerBuilder->getDefinition(NavigationRegistryInterface::class);

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
}
