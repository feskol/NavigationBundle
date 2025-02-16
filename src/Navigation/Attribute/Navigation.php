<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Navigation\Attribute;

/**
 * Marks navigation classes for automatic discovery.
 */
#[\Attribute(\Attribute::TARGET_CLASS)]
class Navigation
{
    /**
     * @param string      $name         The unique name of the Navigation
     * @param string|null $template     The template to render from. If NULL, the default template will be used => default can be changed in config
     * @param bool|null   $activeAsLink If the active link should be rendered as a link (<a>-tag) or not. If NULL, the default value will be used => default can be changed in config
     */
    public function __construct(
        private string $name,
        private ?string $template = null,
        private ?bool $activeAsLink = null,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function setName(string $name): void
    {
        $this->name = $name;
    }

    public function getTemplate(): ?string
    {
        return $this->template;
    }

    public function setTemplate(?string $template): void
    {
        $this->template = $template;
    }

    public function getActiveAsLink(): ?bool
    {
        return $this->activeAsLink;
    }

    public function setActiveAsLink(?bool $activeAsLink): void
    {
        $this->activeAsLink = $activeAsLink;
    }
}
