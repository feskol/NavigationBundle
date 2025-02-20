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

interface NavigationRegistryInterface
{
    /**
     * Adds a Navigation to the registry.
     */
    public function addNavigation(
        string $name,
        NavigationInterface $navigation,
        ?string $template = null,
        ?bool $activeAsLink = null,
    ): void;

    /**
     * Returns the navigation by name.
     * Returns null if not set.
     */
    public function getNavigation(string $name): ?NavigationInterface;

    /**
     * Returns the templates to render.
     * Returns null if not set.
     */
    public function getTemplate(string $name): ?string;

    /**
     * Returns the ActiveAsLink boolean.
     * Returns null if not set.
     *
     * Determines if the active link should be rendered as a link
     */
    public function getActiveAsLink(string $name): ?bool;
}
