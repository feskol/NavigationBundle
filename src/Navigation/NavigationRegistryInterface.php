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

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;

interface NavigationRegistryInterface
{
    /**
     * Adds a Navigation to the registry.
     */
    public function addNavigation(
        string $name,
        NavigationInterface $navigation,
        Navigation $navigationAttribute,
    ): void;

    /**
     * Returns the navigation by name.
     */
    public function getNavigation(string $name): ?NavigationInterface;
}
