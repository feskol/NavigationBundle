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

use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Exception\InvalidParameterException;
use Symfony\Component\Routing\Exception\MissingMandatoryParametersException;
use Symfony\Component\Routing\Exception\RouteNotFoundException;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LinkService
{
    private const ATTRIBUTE_ROUTE = '_route';
    private const ATTRIBUTE_ROUTE_PARAMS = '_route_params';

    public function __construct(
        private readonly RequestStack $requestStack,
        private readonly UrlGeneratorInterface $urlGenerator,
    ) {
    }

    private function getCurrentRoute(): ?string
    {
        return $this->requestStack->getCurrentRequest()?->attributes->get(self::ATTRIBUTE_ROUTE);
    }

    private function getCurrentRouteParams(): ?array
    {
        return $this->requestStack->getCurrentRequest()?->attributes->get(self::ATTRIBUTE_ROUTE_PARAMS);
    }

    /**
     * Checks if the Link is active.
     */
    public function isLinkActive(LinkInterface $link): bool
    {
        // if the route is not set, return the current active status
        if (null === $link->getRoute()) {
            return $link->isActive();
        }

        $currentRouteParams = $this->getCurrentRouteParams();
        $linkRouteParams = $link->getRouteParameters();

        \ksort($linkRouteParams);
        \ksort($currentRouteParams);

        return $this->getCurrentRoute() === $link->getRoute() && $currentRouteParams === $linkRouteParams;
    }

    /**
     * Returns the url for the Link.
     *
     * @throws RouteNotFoundException
     * @throws MissingMandatoryParametersException
     * @throws InvalidParameterException
     */
    public function generateUrl(LinkInterface $link): ?string
    {
        // if the route is not set, return the href
        if (null === $link->getRoute()) {
            return $link->getHref();
        }

        return $this->urlGenerator->generate($link->getRoute(), $link->getRouteParameters(), $link->getUrlReferenceType());
    }
}
