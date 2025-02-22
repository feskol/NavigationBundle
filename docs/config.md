# Config

## Create the config file

To create the config file, go to your projects `config/packages` folder and
create a new file called `feskol_navigation.yaml`

```yaml
# config/packages/feskol_navigation.yaml

feskol_navigation:
# activate to change the default template
#template: 'my-template.html.twig'

# activate to render active links as normal link-tags instead of span-tag
#active_as_link: true
```

## Config explained

### `template`

With the `template` config, you can change the default template for rendering
Navigations.  
Refer to [How to create a navigation template](template.md) for template
guidance.

### `active_as_link`

If the `active_as_link` config is set to `true`, then the links that have their
`isActive`-status set to `true`, will also be rendered as normal links (
`<a href="">Active Link</a>`) instead of using span-elements (
`<span>Active Link</span>`).

If it's set to `false`, then the active links will be rendered as span-elements
(`<span>Active Link</span>`).
