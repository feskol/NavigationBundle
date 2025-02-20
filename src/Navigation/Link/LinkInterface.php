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

/**
 * Represents a navigation link for Symfony applications.
 */
interface LinkInterface extends \Feskol\Navigation\Contracts\LinkInterface
{
    /**
     * The route name, e.g. "app_login".
     */
    public function getRoute(): ?string;

    /**
     * The parameters for the route.
     */
    public function getRouteParameters(): array;

    /**
     * With this referenceType, you can control how the Url is generated.
     *
     * @see UrlGeneratorInterface::generate()
     */
    public function getUrlReferenceType(): int;
}
