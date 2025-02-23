# Docs

# How to Create a Navigation

## 1. Create Your Navigation Class

To create a navigation class, you need to meet the following requirements:

- Use the `#[Navigation]` attribute. Refer
  to [Navigation Attribute](attribute.md) for more details.
- Implement a `getLinks()` method that returns **array reference** of objects
  implementing `Feskol\Bundle\NavigationBundle\Navigation\Link\LinkInterface`.

There is already a class you can extend from to meet the requirements: 
`Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation`. 

Here is an example of how a navigation class will look with the provided
classes:

```php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;

#[Navigation('headerNavigation')]
class HeaderNavigation extends AbstractNavigation
{

}
```

That's it. With this, you now meet all the requirements for a navigation
class.  
The only thing missing are now the links.
---
To add links, you can use the provided methods from `AbstractNavigation`:

```php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;
use Feskol\Bundle\NavigationBundle\Navigation\Link\Link;

#[Navigation('headerNavigation')]
class HeaderNavigation extends AbstractNavigation
{
    public function __construct() {
    
        // set all Links
        $linksArray = [
            (new Link())->setTitle('Dashboard')->setRoute('app_dashboard'),
            (new Link())->setTitle('Contact')->setRoute('app_contact'),
        ];
        $this->setLinks($linksArray);
        
        // add one Link
        $link = (new Link())->setTitle('About us')->setRoute('app_about_us');
        $this->addLink($link);
    }
}
```

---
Navigation classes are standard Symfony services. So you can autowire any
Dependency you want in your `__construct()`.

Here is an example implementation of a navigation class:

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
            // Create a link
            $dashboardLink = (new Link())
                ->setTitle('Dashboard')
                ->setRoute('app_dashboard');

            // Add the link
            $this->addLink($dashboardLink);
        }

        // Create a link with child links
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

        // Add the link
        $this->addLink($companyLink);
    }
}
```

## 2. Render in template

To render the navigation, there is a Twig-Function for it:
`feskol_navigation_render()`.
Call the twig function in your template and add the `name` you defined in
the `#[Navigation('headerNavigation')]`-Attribute:

```twig
{{ feskol_navigation_render('headerNavigation') }}
```

For more detail refer to [Twig functions](twig-functions.md).

The output will be like this (assuming `Company B` is our active route, and
we're logged in with `ROLE_USER`):

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

If you want to adjust the template, have a look at
the [Template documentation](template.md).

## Learn more

* [Navigation-Attribute: What can it do?](attribute.md)
* [Twig functions](twig-functions.md)
* [Config](config.md)
* [Template: How to create your own?](template.md)
* [Add additional Data like `icons` or `images` to your navigation](additional-link-data.md)
* [How to render your navigation twice but with a different template?](same-navigation-different-template.md)
