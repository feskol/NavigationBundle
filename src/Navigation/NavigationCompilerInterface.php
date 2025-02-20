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

interface NavigationCompilerInterface
{
    /**
     * You can modify the navigation here before it is return in twig.
     */
    public function process(NavigationInterface $navigation): void;
}
