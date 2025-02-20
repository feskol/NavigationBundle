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
use PHPUnit\Framework\TestCase;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class LinkTest extends TestCase
{
    /**
     * @dataProvider getTestData
     */
    public function testSetters(string $setter, mixed $value, string $getter, mixed $expected)
    {
        $link = new Link();

        $link->$setter($value);

        $this->assertEquals($expected, $link->$getter());
    }

    public static function getTestData(): array
    {
        return [
            ['setRoute', 'app_test_route', 'getRoute', 'app_test_route'],
            ['setRouteParameters', ['userId' => 92], 'getRouteParameters', ['userId' => 92]],
            ['setUrlReferenceType', UrlGeneratorInterface::ABSOLUTE_URL, 'getUrlReferenceType', UrlGeneratorInterface::ABSOLUTE_URL],
        ];
    }

    public function testDefaultUrlReferenceType(): void
    {
        $link = new Link();

        $this->assertEquals(UrlGeneratorInterface::ABSOLUTE_PATH, $link->getUrlReferenceType());
    }
}
