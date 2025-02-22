<?php

/**
 * This file is part of the NavigationBundle project.
 *
 * (c) Festim Kolgeci <festim.kolgei@pm.me>
 *
 * For complete copyright and license details, please refer
 * to the LICENSE file distributed with this source code.
 */

namespace Feskol\Bundle\NavigationBundle\Tests\Fixtures\Navigation\Attribute;

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;

#[Navigation('firstNavigation', template: 'first/navigation.html.twig', activeAsLink: true)]
#[Navigation('secondNavigation', template: 'second/navigation.html.twig', activeAsLink: false)]
class FooMultipleNavigation
{
}
