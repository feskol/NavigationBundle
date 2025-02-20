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
use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;

class DefaultNavigationCompiler implements NavigationCompilerInterface
{
    public function __construct(
        private readonly LinkService $linkService,
    )
    {
    }

    public function process(NavigationInterface $navigation): void
    {
        foreach ($navigation->getItems() as $item) {
            $this->processItem($item);
        }
    }

    private function processItem(LinkInterface $link): void
    {
        $this->handleActiveStatus($link);
        $this->handleUrl($link);

        if ($link->hasChildren()) {
            foreach ($link->getChildren() as $child) {
                /** @var LinkInterface $child */
                $this->processItem($child);
            }
        }
    }

    private function handleActiveStatus(LinkInterface $link): void
    {
        $active = $this->linkService->isLinkActive($link);
        $link->setIsActive($active);
    }

    private function handleUrl(LinkInterface $link): void
    {
        $url = $this->linkService->generateUrl($link);
        $link->setHref($url);
    }
}
