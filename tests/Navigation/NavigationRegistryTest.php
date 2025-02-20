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

use Feskol\Bundle\NavigationBundle\Navigation\NavigationRegistry;
use Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\NavigationClass;
use PHPUnit\Framework\TestCase;

class NavigationRegistryTest extends TestCase
{
    public function testAddNavigation(): void
    {
        $navigationClass = new NavigationClass();
        $navRegistry = new NavigationRegistry('template/test-nav.html.twig', false);

        $navRegistry->addNavigation(
            'testNavigation',
            $navigationClass,
        );

        $this->assertSame($navigationClass, $navRegistry->getNavigation('testNavigation'));
    }

    public function testReturnDefaultTemplate(): void
    {
        $navRegistry = new NavigationRegistry('template/test-nav.html.twig', false);

        $navRegistry->addNavigation(
            'testNavigation',
            new NavigationClass(),
        );

        $this->assertEquals('template/test-nav.html.twig', $navRegistry->getTemplate('testNavigation'));
    }

    public function testReturnDefaultActiveAsLink(): void
    {
        $navRegistry = new NavigationRegistry('template/test-nav.html.twig', false);

        $navRegistry->addNavigation(
            'testNavigation',
            new NavigationClass(),
        );

        $this->assertFalse($navRegistry->getActiveAsLink('testNavigation'));
    }

    public function testReturnSetTemplateValue(): void
    {
        $navRegistry = new NavigationRegistry('template/test-nav.html.twig', false);

        $navRegistry->addNavigation(
            'testNavigation',
            new NavigationClass(),
            'my-custom-template/my-custom-template.html.twig'
        );

        $this->assertEquals(
            'my-custom-template/my-custom-template.html.twig',
            $navRegistry->getTemplate('testNavigation')
        );
    }

    public function testReturnSetActiveAsLinkValue(): void
    {
        $navRegistry = new NavigationRegistry('template/test-nav.html.twig', false);

        $navRegistry->addNavigation(
            'testNavigation',
            new NavigationClass(),
            null,
            true
        );

        $this->assertTrue($navRegistry->getActiveAsLink('testNavigation'));
    }

    public function testNotAddedNavigation(): void
    {
        $navRegistry = new NavigationRegistry('template/test-nav.html.twig', false);

        $this->assertNull($navRegistry->getNavigation('testNavigation'));
        $this->assertNull($navRegistry->getTemplate('testNavigation'));
        $this->assertNull($navRegistry->getActiveAsLink('testNavigation'));
    }
}
