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

use Feskol\Bundle\NavigationBundle\Navigation\NavigationCompilerInterface;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

class TestNavigationCompiler implements NavigationCompilerInterface
{
    public function process(NavigationInterface $navigation): void
    {
    }
}
