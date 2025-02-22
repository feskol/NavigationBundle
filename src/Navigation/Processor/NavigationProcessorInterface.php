<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Navigation\Processor;

use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

/**
 * Defines the contract for navigation processors.
 */
interface NavigationProcessorInterface
{
    /**
     * Processes the given navigation object.
     *
     * @param NavigationInterface $navigation the navigation structure to modify
     */
    public function process(NavigationInterface $navigation): void;
}
