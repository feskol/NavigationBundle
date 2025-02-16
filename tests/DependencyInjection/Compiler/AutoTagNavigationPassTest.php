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

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\AutoTagNavigationPass;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooNavigation;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class AutoTagNavigationPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private AutoTagNavigationPass $pass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();

        $this->pass = new AutoTagNavigationPass();
    }

    public function testProcess(): void
    {
        $this->containerBuilder->register(FooNavigation::class, FooNavigation::class);
        $fooNav = $this->containerBuilder->getDefinition(FooNavigation::class);
        $this->assertFalse($fooNav->hasTag('feskol_navigation.navigation'));

        $this->pass->process($this->containerBuilder);

        $this->assertTrue($fooNav->hasTag('feskol_navigation.navigation'));
    }

    public function testProcessContinuesWhenDefinitionDoesNotHaveClass(): void
    {
        $this->containerBuilder->register(FooNavigation::class);

        $fooNav = $this->containerBuilder->getDefinition(FooNavigation::class);
        $this->assertFalse($fooNav->hasTag('feskol_navigation.navigation'));

        $this->pass->process($this->containerBuilder);

        $this->assertFalse($fooNav->hasTag('feskol_navigation.navigation'));
    }
}
