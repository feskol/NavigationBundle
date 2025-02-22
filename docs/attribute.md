# Navigation-Attribute

With the `#[Navigation]`-Attribute you can:

- Set the Navigation-Name
- Set `template` to render with
- Set `ActiveAsLink` to render active links as <a>-element instead of <span>
  -element

## Navigation-Name

The navigation name can be set directly

````php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

#[Navigation('fooNavigation')]
class FooNavigation extends NavigationInterface
{
    // ...
}
````

Or:

```php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

#[Navigation(name: 'fooNavigation')]
class FooNavigation extends NavigationInterface
{
    // ...
}
```

## Template

If you want to render the navigation with a different template, you need to set
the `template` param:

````php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

#[Navigation('fooNavigation', template: 'navigation/_my-custom-navigation.html.twig')]
class FooNavigation extends NavigationInterface
{
    // ...
}
````

That's it. Now when you call the
`{{ feskol_navigation_render('fooNavigation') }}`, it will render with your
custom template.

> [!NOTE]
> The default template can be changed through the [config](config.md).

## ActiveAsLink

If you want to render active navigation items also as links, you can set the
`activeAsLink` param to `true`:

````php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

#[Navigation('fooNavigation', activeAsLink: true)]
class FooNavigation extends NavigationInterface
{
    // ...
}
````
> [!NOTE]
> Note: The default ActiveAsLink behavior can be changed through the [config](config.md).
