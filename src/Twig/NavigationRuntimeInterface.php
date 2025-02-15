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

use Twig\Extension\RuntimeExtensionInterface;

interface NavigationRuntimeInterface extends RuntimeExtensionInterface
{
    public function renderNavigation(string $name, array $parameters = []): string;
}
