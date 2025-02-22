# Template

The default template is located
under [templates/_navigation.html.twig](../templates/_navigation.html.twig).    
The template is using twig blocks (`{% block %}`), so it's easy to
override/extend.

## How to create a navigation template

The best way to create your own template would be to extend from the default
template `@FeskolNavigation/_navigation.html.twig`

And then you only need to override the blocks, where you want to change your
template.

For example:

```twig
{% extends "@!FeskolNavigation/_navigation.html.twig" %}

{%- block navigation -%}
    <ul class="navbar-nav">
        {{- block('children') -}}
    </ul>
{%- endblock -%}

{%- block item -%}
    <li class="nav-item">
        {{- block('item_content') -}}
    </li>
{%- endblock -%}

{%- block item_tag_link -%}
    <a class="nav-link{% if item.active or item.activeChildren %} active{% endif %}" {{- block('item_tag_link_default_attributes') -}}>
        {{- block('label_wrapper') -}}
    </a>
{%- endblock -%}
```

## Build from scratch

If you want to build your
navigation without using the provided blocks, you need to know what `variables`
are available to use.  
Refer to
the [src/Twig/NavigationRuntimeExtension.php](../src/Twig/NavigationRuntimeExtension.php)
`renderNavigation()` method where you can find the `$context` array.
