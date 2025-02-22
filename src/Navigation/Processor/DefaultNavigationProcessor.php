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

use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkInterface;
use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

/**
 * A navigation processor that updates the links active status and generates URLs.
 */
class DefaultNavigationProcessor implements NavigationProcessorInterface
{
    public function __construct(
        private readonly LinkService $linkService,
    ) {
    }

    public function process(NavigationInterface $navigation): void
    {
        foreach ($navigation->getLinks() as $item) {
            $this->processItem($item);
        }
    }

    /**
     * Processes a single navigation item, updating its active status and URL.
     * If the item has children, it recursively processes them as well.
     */
    private function processItem(LinkInterface $link): void
    {
        $this->handleActiveStatus($link);
        $this->handleUrl($link);

        if ($link->hasChildren()) {
            foreach ($link->getChildren() as $child) {
                /* @var LinkInterface $child */
                $this->processItem($child);
            }
        }
    }

    /**
     * Determines if a link is active and updates its status accordingly.
     */
    private function handleActiveStatus(LinkInterface $link): void
    {
        $active = $this->linkService->isLinkActive($link);
        $link->setIsActive($active);
    }

    /**
     * Generates and sets the correct URL for the given link.
     */
    private function handleUrl(LinkInterface $link): void
    {
        $url = $this->linkService->generateUrl($link);
        $link->setHref($url);
    }
}
