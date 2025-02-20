<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Navigation;

use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;
use Feskol\Bundle\NavigationBundle\Navigation\DefaultNavigationCompiler;
use Feskol\Bundle\NavigationBundle\Navigation\Link\Link;
use Feskol\Bundle\NavigationBundle\Navigation\Link\LinkService;
use PHPUnit\Framework\TestCase;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Routing\RequestContext;

/**
 * Test class for navigation.
 */
final class CompilerNavigation extends AbstractNavigation
{
    public function __construct()
    {
        $this->setLinks(
            [
                (new Link())->setTitle('Test Link 1')->setRoute('app_first_route'),
                (new Link())->setTitle('Test Link 2')->setRoute('app_second_route')->setRouteParameters(['test' => 2]),
                (new Link())->setTitle('Test Link 3')->setHref('/test')
                    ->addChild(
                        (new Link())->setTitle('Sub Link 1')->setRoute('app_third_sub_route')
                            ->addChild((new Link())->setTitle('Sub-Sub Link 1')->setRoute('app_third_sub_sub_route'))
                    )
                    ->addChild((new Link())->setTitle('Sub Link 2')->setHref('/test/sub/2')),
            ]
        );
    }
}

final class UrlGenerator implements UrlGeneratorInterface
{
    private const URL_MAP = [
        'app_first_route' => '/app/first/route',
        'app_second_route' => '/app/second/route',
        'app_third_sub_route' => '/app/third/sub_route',
        'app_third_sub_sub_route' => '/app/third/sub_sub_route',
    ];

    public function setContext(RequestContext $context): void
    {
    }

    public function getContext(): RequestContext
    {
        return new RequestContext();
    }

    public function generate(string $name, array $parameters = [], int $referenceType = self::ABSOLUTE_PATH): string
    {
        return self::URL_MAP[$name] ?? '';
    }
}

class DefaultNavigationCompilerTest extends TestCase
{
    private function getLinkService(
        string $currentRoute = 'app_first_route',
        array $currentRouteParams = [],
    ): LinkService {
        $requestStack = new RequestStack();
        $requestStack->push(new Request([], [], [
            '_route' => $currentRoute,
            '_route_params' => $currentRouteParams,
        ]));

        return new LinkService(
            $requestStack,
            new UrlGenerator(),
        );
    }

    public function testProcess(): void
    {
        $navigation = new CompilerNavigation();

        $defaultCompiler = new DefaultNavigationCompiler($this->getLinkService('app_third_sub_route'));

        // pre asserts
        $links = $navigation->getItems();

        $link1 = $links[0];
        $link2 = $links[1];
        $link3 = $links[2];

        $linkWithChildren = $link3;
        $childLink1 = $linkWithChildren->getChildren()[0];
        $childLink2 = $linkWithChildren->getChildren()[1];
        $subChildLink = $childLink1->getChildren()[0];

        $linkWillBeActive = $childLink1;

        $this->assertNull($link1->getHref());
        $this->assertNull($link2->getHref());
        $this->assertNull($childLink1->getHref());
        $this->assertNull($subChildLink->getHref());

        // href links => should never change!
        $this->assertEquals('/test', $link3->getHref());
        $this->assertEquals('/test/sub/2', $childLink2->getHref());

        // active status
        $this->assertFalse($linkWillBeActive->isActive());

        // test
        $defaultCompiler->process($navigation);

        // asserts

        // href links => should never change!
        $this->assertEquals('/test', $link3->getHref());
        $this->assertEquals('/test/sub/2', $childLink2->getHref());

        // processed links
        $this->assertEquals('/app/first/route', $link1->getHref());
        $this->assertEquals('/app/second/route', $link2->getHref());
        $this->assertEquals('/app/third/sub_route', $childLink1->getHref());
        $this->assertEquals('/app/third/sub_sub_route', $subChildLink->getHref());

        // active status
        $this->assertTrue($linkWillBeActive->isActive());
    }
}
