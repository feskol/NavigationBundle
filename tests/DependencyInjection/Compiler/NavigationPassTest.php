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

use Feskol\Bundle\NavigationBundle\DependencyInjection\Compiler\NavigationPass;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistryInterface;
use PHPUnit\Framework\TestCase;
use Symfony\Component\DependencyInjection\ContainerBuilder;

class NavigationPassTest extends TestCase
{
    private ContainerBuilder $containerBuilder;
    private NavigationPass $pass;

    protected function setUp(): void
    {
        $this->containerBuilder = new ContainerBuilder();
        $this->pass = new NavigationPass();
    }

    public function testProcess(): void
    {
        $this->assertFalse($this->containerBuilder->has(NavigationRegistryInterface::class));

        $this->pass->process($this->containerBuilder);

        $this->assertTrue($this->containerBuilder->has(NavigationRegistryInterface::class));
        $this->assertSame('feskol_navigation.registry', (string) $this->containerBuilder->getAlias(NavigationRegistryInterface::class));
    }

    public function testProcessWithAlreadyDefinedServices(): void
    {
        $this->containerBuilder->register(NavigationRegistryInterface::class, \stdClass::class);
        $this->assertTrue($this->containerBuilder->has(NavigationRegistryInterface::class));

        $this->pass->process($this->containerBuilder);

        $this->assertFalse($this->containerBuilder->hasAlias(NavigationRegistryInterface::class));
    }
}
