<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Navigation\Link;

use Feskol\Bundle\NavigationBundle\Navigation\Link\Link;
use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LinkServiceTest extends TestCase
{
    public function testIsLinkActive(): void
    {
        $link = new Link();
        $link->setRoute('app_test_active');
        $link->setRouteParameters(['user' => 2]);

        $linkService = $this->getLinkService('app_test_active', ['user' => 2]);

        $this->assertTrue($linkService->isLinkActive($link));
    }

    public function testIsLinkActiveWithDifferentParameterOrder(): void
    {
        $link = new Link();
        $link->setRoute('app_test_active_param_order');
        $link->setRouteParameters(['user' => 2, 'user_active' => true, 'user_test' => 'yes']);

        $linkService = $this->getLinkService(
            'app_test_active_param_order',
            ['user_test' => 'yes', 'user' => 2, 'user_active' => true]
        );

        $this->assertTrue($linkService->isLinkActive($link));
    }

    public function testIsLinkNotActive(): void
    {
        $link = new Link();
        $link->setRoute('app_test');
        $link->setRouteParameters(['id' => 1]);

        $linkService = $this->getLinkService();

        $this->assertFalse($linkService->isLinkActive($link));
    }

    public function testGenerateUrl(): void
    {
        $link = new Link();
        $link->setHref('/test-href/link');
        $link->setRoute('app_test');
        $link->setRouteParameters(['id' => 1]);

        $linkService = $this->getLinkService('index', [], '/test/1');

        $this->assertNotEquals('/test-href/link', $linkService->generateUrl($link));
        $this->assertEquals('/test/1', $linkService->generateUrl($link));
    }

    public function testGenerateUrlFallbackHref(): void
    {
        $link = new Link();
        $link->setHref('/test-href/link');

        $linkService = $this->getLinkService();

        $this->assertEquals('/test-href/link', $linkService->generateUrl($link));
    }

    private function getLinkService(
        string $currentRoute = 'index',
        array $currentRouteParams = [],
        string $urlGenerateResult = '/index',
    ): LinkService {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [
            '_route' => $currentRoute,
            '_route_params' => $currentRouteParams,
        ]));

        $urlGenerator = $this->createMock(UrlGeneratorInterface::class);
        $urlGenerator->expects($this->any())
            ->method('generate')
            ->willReturn($urlGenerateResult);

        return new LinkService(
            $requestStack,
            $urlGenerator,
        );
    }
}
