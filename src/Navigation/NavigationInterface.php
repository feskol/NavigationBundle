<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Navigation;

use Feskol\Navigation\Contracts\LinkInterface;

/**
 * Defines the contract for all navigations.
 */
interface NavigationInterface
{
    /**
     * Returns the links from the Navigation.
     *
     * @return LinkInterface[]
     */
    public function getItems(): array;
}
