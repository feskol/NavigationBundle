# Render the same navigation with a different template

If you have a navigation, you want to render twice in your template but each
with a different template, you can just add another `#[Navigation]`-Attribute,
that loads a different template:

```php
use Feskol\Bundle\NavigationBundle\Navigation\Attribute\Navigation;
use Feskol\Bundle\NavigationBundle\Navigation\AbstractNavigation;

#[Navigation('headerNavigation')] // loads the default template
#[Navigation('headerNavigationSecond', template: '_second-nav.html.twig')] // loads the "_second-nav.html.twig" template
class HeaderNavigation extends AbstractNavigation
{

}
```

And now you can call the [twig functions](twig-functions.md) to render each
navigation:

```twig
{# loads the default template #}
{{ feskol_navigation_render('headerNavigation') }} 

{# loads the "_second-nav.html.twig" template #}
{{ feskol_navigation_render('headerNavigationSecond') }} 
```
