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
 * Collects all NavigationProcessors and execute them.
 */
class NavigationProcessorRunner
{
    /**
     * @param NavigationProcessorInterface[] $navigationProcessors
     */
    public function __construct(
        private readonly iterable $navigationProcessors = [],
    ) {
    }

    public function process(NavigationInterface $navigation): void
    {
        foreach ($this->navigationProcessors as $navigationProcessor) {
            $navigationProcessor->process($navigation);
        }
    }
}
