# Docs

## Content
* [Navigation-Attribute](attribute.md)

## How to create a navigation

### 1. Create your navigation class

To create your navigation class, there are two requirements:

- Use the `#[Navigation]`-Attribute. You can configure your navigation with it.
- You need to provide a `getItems()` method. You can implement the
  `Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface`

Your navigation classes are normal symfony services.

Because of the `#[Navigation]`-Attribute, your class gets tagged with
`feskol_navigation.navigation` and registered automatically.

This is the most basic creation of a navigation class:

```php
// src/Navigation/HeaderNavigation.php

namespace App\Navigation;

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;
use Feskol\Navigation\Contracts\LinkInterface;
use Feskol\Navigation\Link;

#[Navigation('fooNavigation')]
class FooNavigation extends NavigationInterface
{
    public function getItems(): array
    {
        return [
            (new Link())->setTitle('About Us')->setHref('/about-us'),
            (new Link())->setTitle('Contact')->setHref('/contact'),
            (new Link())->setTitle('Login')->setHref('/login'),
        ];
    }
}
```

Here is a more realistic class:

```php
// src/Navigation/HeaderNavigation.php

namespace App\Navigation;

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;
use Feskol\Navigation\Contracts\LinkInterface;
use Feskol\Navigation\Link;
use Symfony\Bundle\SecurityBundle\Security;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

#[Navigation('headerNavigation')]
class HeaderNavigation extends NavigationInterface
{
    public function __construct(
        private Security              $security,
        private RequestStack          $requestStack,
        private UrlGeneratorInterface $urlGenerator,
    )
    {
    }

    private function createHref(string $route, array $params = []) {
        return $this->urlGenerator->generate($route, $params);
    }

    private function isActive(string $route, array $params = []) {
        $currentRoute = $this->requestStack->getCurrentRequest()?->attributes->get('_route');
        $currentParams = $this->requestStack->getMainRequest()?->attributes->get('_route_params');

        return $currentRoute === $route && $currentParams === $params;
    }

    public function getItems(): array
    {
        $navigationItems = [];

        if ($this->security->isGranted('ROLE_USER')) {
             $dashboardLink = (new Link())
                ->setTitle('Dashboard')
                ->setHref($this->createHref('app_dashboard'))
                ->setIsActive($this->isActive('app_dashboard'));

            $navigationItems[] = $dashboardLink;
        }

        $companyLink = (new Link())
                ->setTitle('Company')
                ->setHref($this->createHref('app_company'))
                ->setIsActive($this->isActive('app_company'))
                ->addChild(
                    (new Link())
                        ->setTitle('Company A')
                        ->setHref($this->createHref('app_company_sub', ['company' => 1]))
                        ->setIsActive($this->isActive('app_company_sub',  ['company' => 1]))
                )
                ->addChild(
                    (new Link())
                        ->setTitle('Company B')
                        ->setHref($this->createHref('app_company_sub', ['company' => 2]))
                        ->setIsActive($this->isActive('app_company_sub',  ['company' => 2]))
                );
        $navigationItems[] = $companyLink;

        return $navigationItems;
    }
}
```

### 2. Render in template

To render the navigation there is a Twig-Function for it:
`feskol_navigation_render()`.
Call the twig function in your template and add the `name` you defined in
the `#[Navigation('headerNavigation')]`-Attribute:

```twig
{{ feskol_navigation_render('headerNavigation') }}
```

The output will be like this (we assume `Company B` is our active route, and we're logged in):

```html
<ul>
    <li><a href="/dashboard">Dashboard</a></li>
    <li class="active-children">
        <a href="/company">Company</a>
        <ul>
            <li><a href="/company-sub/1">Company A</a></li>
            <li class="active"><a href="/company-sub/2">Company B</a></li>
        </ul>
    </li>
</ul>
```
