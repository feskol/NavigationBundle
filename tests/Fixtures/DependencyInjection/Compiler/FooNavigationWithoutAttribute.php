<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Fixtures\DependencyInjection\Compiler;

use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

class FooNavigationWithoutAttribute extends AbstractNavigation implements NavigationInterface
{
    public function getLinks(): array
    {
        return [];
    }
}
