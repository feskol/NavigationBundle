<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Processor;

use Feskol\Bundle\NavigationBundle\Navigation\Link\Link;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;
use Feskol\Bundle\NavigationBundle\Navigation\Processor\NavigationProcessorInterface;

class FooNavigationProcessor implements NavigationProcessorInterface
{
    public function process(NavigationInterface $navigation): void
    {
        $navigation->addLink((new Link())->setTitle(self::class));
    }
}
