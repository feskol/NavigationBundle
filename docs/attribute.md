# Navigation-Attribute

The `#[Navigation]`-Attribute is for register your navigation.  
Due to the attribute, your class is automatically tagged as
`feskol_navigation.navigation` and passed to
the [NavigationRegistry.php](../src/Navigation/NavigationRegistry.php).

With the `#[Navigation]`-Attribute you can:

- Set `name` to give your navigation a **unique** name. This will be used to
  render the navigation in the [Twig function](twig-functions.md).
- Set `template` to render with
- Set `ActiveAsLink` to render active links as `<a>`-element instead of `<span>`
  -element

## Navigation-Name

The navigation name can be set:

````php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\NavigationInterface;

// Use either
#[Navigation('fooNavigation')]
// Or
#[Navigation(name: 'fooNavigation')]
class FooNavigation extends NavigationInterface
{
    // ...
}
````

> [!IMPORTANT]  
> The `name` must be unique! If you try to add a navigation with a name that
> already exists, a `LogicException` will be thrown.

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
`{{ feskol_navigation_render('fooNavigation') }}`, it will render your
custom template.

> [!NOTE]  
> The default template can be changed through the [config](config.md#template-default-feskolnavigation_navigationhtmltwig).

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
> Note: The default ActiveAsLink behavior can be changed through
> the [config](config.md#active_as_link-default-true).
