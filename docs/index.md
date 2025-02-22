# Docs

## Content
* [Navigation-Attribute](attribute.md)

## How to create a navigation

### 1. Create your navigation class

To create your navigation class, there are two requirements:

- Use the `#[Navigation]`-Attribute. You can configure your navigation with it.
- You need to provide a `getLinks()` method,
  which returns an array reference of `Feskol\Bundle\NavigationBundle\Navigation\Link\LinkInterface` as its elements.
  You can extend the `Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation`

Your navigation classes are normal symfony services.

Because of the `#[Navigation]`-Attribute, your class gets tagged with
`feskol_navigation.navigation` and registered automatically.

This is an implementation of a navigation class:

```php
// src/Navigation/HeaderNavigation.php

namespace App\Navigation;

use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;
use Feskol\Bundle\NavigationBundle\Navigation\Link\Link;
use Symfony\Bundle\SecurityBundle\Security;

#[Navigation('headerNavigation')]
class HeaderNavigation extends AbstractNavigation
{
    public function __construct(private Security $security)
    {
        $this->createNavigation();
    }

    private function createNavigation(): void
    {
        if ($this->security->isGranted('ROLE_USER')) {

            // create a Link
            $dashboardLink = (new Link())
                ->setTitle('Dashboard')
                ->setRoute('app_dashboard');

            // add the Link
            $this->addLink($dashboardLink);
        }

        // create a Link with children links
        $companyLink = (new Link())
                ->setTitle('Company')
                ->setRoute('app_company')
                ->addChild(
                    (new Link())
                        ->setTitle('Company A')
                        ->setRoute('app_company_sub')
                        ->setRouteParameters(['company' => 1])
                )
                ->addChild(
                    (new Link())
                        ->setTitle('Company B')
                        ->setRoute('app_company_sub')
                        ->setRouteParameters(['company' => 2])
                );

        // add the link
        $this->addLink($companyLink);
    }
}
```

### 2. Render in template

To render the navigation, there is a Twig-Function for it:
`feskol_navigation_render()`.
Call the twig function in your template and add the `name` you defined in
the `#[Navigation('headerNavigation')]`-Attribute:

```twig
{{ feskol_navigation_render('headerNavigation') }}
```

The output will be like this (assuming `Company B` is our active route, and we're logged in with `ROLE_USER`):

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

For more detail refer to [Twig functions](twig-functions.md).
