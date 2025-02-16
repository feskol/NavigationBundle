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

/**
 * Stores and retrieves navigation instances.
 */
class NavigationRegistry implements NavigationRegistryInterface
{
    private array $navigations = [];
    private array $templates = [];
    private array $activeAsLink = [];

    public function __construct(
        private readonly string $defaultTemplate,
        private readonly bool $defaultActiveAsLink,
    ) {
    }

    public function addNavigation(
        string $name,
        NavigationInterface $navigation,
        Navigation $navigationAttribute,
    ): void {
        $this->navigations[$name] = $navigation;
        $this->templates[$name] = $navigationAttribute->getTemplate();
        $this->activeAsLink[$name] = $navigationAttribute->getActiveAsLink();
    }

    public function getNavigation(string $name): ?NavigationInterface
    {
        return $this->navigations[$name] ?? null;
    }

    public function getTemplate(string $name): string
    {
        return $this->templates[$name] ?? $this->defaultTemplate;
    }

    public function getActiveAsLink(string $name): bool
    {
        return $this->activeAsLink[$name] ?? $this->defaultActiveAsLink;
    }
}
