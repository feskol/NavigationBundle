<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Navigation\Link;

use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class Link extends \Feskol\Navigation\Link implements LinkInterface
{
    private ?string $route = null;
    private array $routeParameters = [];
    private int $urlReferenceType = UrlGeneratorInterface::ABSOLUTE_PATH;

    /**
     * @inheritDoc
     */
    public function getRoute(): ?string
    {
        return $this->route;
    }

    public function setRoute(?string $route): static
    {
        $this->route = $route;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getRouteParameters(): array
    {
        return $this->routeParameters;
    }

    public function setRouteParameters(array $routeParameters): static
    {
        $this->routeParameters = $routeParameters;

        return $this;
    }

    /**
     * @inheritDoc
     */
    public function getUrlReferenceType(): int
    {
        return $this->urlReferenceType;
    }

    public function setUrlReferenceType(int $urlReferenceType): static
    {
        $this->urlReferenceType = $urlReferenceType;

        return $this;
    }
}
