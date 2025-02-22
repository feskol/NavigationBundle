# NavigationBundle

[![Tests](https://github.com/feskol/NavigationBundle/actions/workflows/test.yml/badge.svg)](https://github.com/feskol/NavigationBundle/actions/workflows/test.yml)
[![codecov](https://codecov.io/gh/feskol/NavigationBundle/graph/badge.svg?token=3210T89Z8P)](https://codecov.io/gh/feskol/NavigationBundle)

This bundle is an integration of
the [feskol/php-navigation](https://github.com/feskol/php-navigation) library
with extensions for Symfony applications.

## Installation

### Install the bundle

```bash
composer require feskol/navigation-bundle
```

### Enable the Bundle

FeskolNavigationBundle should be automatically enabled and configured, thanks
to [Flex](https://symfony.com/doc/current/setup/flex.html).

If you don't use Flex, you can manually enable it, by adding the following line
in your project's `config/bundles.php`:

```php
<?php

return [
   // ...
   Feskol\Bundle\NavigationBundle\FeskolNavigationBundle::class => ['all' => true],
];
```

### Configure the bundle (optional)

There are a few configurations available for this bundle. To make use of it,
start by creating a new config file:

```yaml
# config/packages/feskol_navigation.yaml

feskol_navigation:
    # Change the default template
    #template: 'my-navigation-template.html.twig'

    # Default render active links as normal link-tags instead of span-tag
    #active_as_link: true
```

Make sure you `bin/console cache:clear` after you change the config.

## Usage

Please read the documentation. It's available in the `docs` directory of this
bundle:

- Read the [FeskolNavigationBundle documentation](docs/index.md)

## Compatibility

The current version of this bundle has the following requirements:

* PHP `8.1` or newer is required
* Symfony `6.1` or newer is required
