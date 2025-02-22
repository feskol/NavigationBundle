# Twig functions

The FeskolNavigationBundle provides `Twig functions` to render the navigation.

> Before using the Twig functions, ensure you have a working navigation class.  
> Refer to [how to create a navigation](index.md) for guidance.

## Function: `feskol_navigation_render()`

The `feskol_navigation_render()` function is used for rendering your navigation in Twig.  
It requires the following parameter:

- `string $name` - The navigation name defined with the
  `#[Navigation('headerNavigation')]` attribute.

When calling `feskol_navigation_render()`, it renders the Twig template. If you have defined a template through the `#[Navigation]` attribute (see [attribute.md](attribute.md)), that template will be rendered. Otherwise, the [default template](../templates/_navigation.html.twig) is used unless specified otherwise in the [config file](config.md).

The implementation of the `feskol_navigation_render()` Twig function can be found in the [NavigationRuntimeExtension.php](../src/Twig/NavigationRuntimeExtension.php).

### Usage xample

```twig
{{ feskol_navigation_render('headerNavigation') }}
```

