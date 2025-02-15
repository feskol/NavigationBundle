<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Navigation\Attribute;

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooActiveAsLinkNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooNavigation;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute\FooTemplateNavigation;
use PHPUnit\Framework\TestCase;

class NavigationTest extends TestCase
{
    /**
     * @dataProvider getTestData
     */
    public function testLoadFromAttribute(
        string $className,
        string $getter,
        mixed $expectedReturn,
    ): void {
        $navigation = (new \ReflectionClass($className))->getAttributes(Navigation::class)[0]->newInstance();

        $this->assertEquals($expectedReturn, $navigation->$getter());
    }

    public static function getTestData(): iterable
    {
        return [
            [FooNavigation::class, 'getName', 'mainNavigation'],
            [FooNavigation::class, 'getActiveAsLink', null],
            [FooNavigation::class, 'getTemplate', null],

            [FooTemplateNavigation::class, 'getName', 'TemplateNavigation'],
            [FooTemplateNavigation::class, 'getTemplate', '@TestTemplate/test.html.twig'],

            [FooActiveAsLinkNavigation::class, 'getName', 'ActiveAsLinkNavigation'],
            [FooActiveAsLinkNavigation::class, 'getActiveAsLink', true],
        ];
    }
}
