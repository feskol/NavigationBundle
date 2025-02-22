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

use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkInterface;
use Feskol\Navigation\Contracts\LinkInterface as FesKolPhpNavigationLinkInterface;

/**
 * Defines the contract for all navigations.
 */
interface NavigationInterface extends \Feskol\Navigation\Contracts\NavigationInterface
{
    /**
     * @param LinkInterface $link
     *
     * @return $this
     */
    public function addLink(FesKolPhpNavigationLinkInterface $link): static;

    /**
     * @return LinkInterface[]
     */
    public function getLinks(): array;

    /**
     * @param LinkInterface[] $links
     *
     * @return $this
     */
    public function setLinks(array $links): static;

    /**
     * @param LinkInterface $link
     *
     * @return $this
     */
    public function removeLink(FesKolPhpNavigationLinkInterface $link): static;
}
