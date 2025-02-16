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
        ?string $template = null,
        ?bool $activeAsLink = null,
    ): void {
        $this->navigations[$name] = $navigation;
        $this->templates[$name] = $template;
        $this->activeAsLink[$name] = $activeAsLink;
    }

    public function getNavigation(string $name): ?NavigationInterface
    {
        return $this->navigations[$name] ?? null;
    }

    public function getTemplate(string $name): ?string
    {
        if (\array_key_exists($name, $this->templates)) {
            return $this->templates[$name] ?? $this->defaultTemplate;
        }

        return null;
    }

    public function getActiveAsLink(string $name): ?bool
    {
        if (\array_key_exists($name, $this->activeAsLink)) {
            return $this->activeAsLink[$name] ?? $this->defaultActiveAsLink;
        }

        return null;
    }
}
