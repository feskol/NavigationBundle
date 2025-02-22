<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Navigation\Processor;

use Feskol\Bundle\NavigationBundle\Navigation\Processor\NavigationProcessorRunner;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Processor\BarNavigationProcessor;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Processor\FooNavigationProcessor;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Processor\ProcessorNavigation;
use PHPUnit\Framework\TestCase;

class NavigationProcessorRunnerTest extends TestCase
{
    public function testProcess(): void
    {
        $nav = new ProcessorNavigation();
        $this->assertCount(0, $nav->getLinks());

        $navigationProcessorRunner = new NavigationProcessorRunner([
            new FooNavigationProcessor(),
            new BarNavigationProcessor(),
        ]);

        $navigationProcessorRunner->process($nav);

        $this->assertCount(2, $nav->getLinks());

        $this->assertEquals(FooNavigationProcessor::class, $nav->getLinks()[0]->getTitle());
        $this->assertEquals(BarNavigationProcessor::class, $nav->getLinks()[1]->getTitle());
    }
}
