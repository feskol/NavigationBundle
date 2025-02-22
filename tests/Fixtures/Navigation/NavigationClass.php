<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation;

use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;
use Feskol\Navigation\Link;

#[Navigation('testNavigation')]
class NavigationClass extends AbstractNavigation implements NavigationInterface
{
    public function getLinks(): array
    {
        return [
            (new Link())->setTitle('link-1'),
            (new Link())->setTitle('link-2'),
            (new Link())->setTitle('link-3'),
        ];
    }
}
