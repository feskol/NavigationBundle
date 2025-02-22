<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Twig;

use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Navigation\Processor\NavigationProcessorRunner;
use Twig\Environment;
use Twig\Error\LoaderError;
use Twig\Error\RuntimeError;
use Twig\Error\SyntaxError;
use Twig\Extension\RuntimeExtensionInterface;

class NavigationRuntimeExtension implements RuntimeExtensionInterface
{
    public function __construct(
        private readonly NavigationRegistry $navigationRegistry,
        private readonly NavigationProcessorRunner $navigationProcessorRunner,
        private readonly Environment $twig,
    ) {
    }

    /**
     * @throws RuntimeError
     * @throws SyntaxError
     * @throws LoaderError
     */
    public function renderNavigation(string $name): string
    {
        $navigation = $this->navigationRegistry->getNavigation($name);
        if (null === $navigation) {
            throw new \RuntimeException(\sprintf('The navigation with name "%s" does not exist.', $name));
        }

        $this->navigationProcessorRunner->process($navigation);

        $template = $this->navigationRegistry->getTemplate($name);

        $context = [
            'navigationTitle' => $navigation->getTitle(),
            'items' => $navigation->getLinks(),
            'options' => [
                'activeAsLink' => $this->navigationRegistry->getActiveAsLink($name),
            ],
        ];

        return $this->twig->render($template, $context);
    }
}
