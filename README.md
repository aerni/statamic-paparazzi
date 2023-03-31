![Statamic](https://flat.badgen.net/badge/Statamic/4.0+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/paparazzi/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/paparazzi)

# Paparazzi
This addon provides an easy-to-use interface to generate images of your entries and terms. A common use case would be to generate social images for Open Graph and Twitter.

## Installation
Install the addon using Composer:

```bash
composer require aerni/paparazzi
```

The config will be automatically published to `config/paparazzi.php` as part of the installation process.

## Commands

There are a number of helpful commands to help you create layouts and templates for your models.

| Command                               | Description                           |
| ------------------------------------- | ------------------------------------- |
| `paparazzi:layout {name?}`            | Create a new Paparazzi layout         |
| `paparazzi:template {model?} {name?}` | Create a new Paparazzi model template |

## Getting started

### Prerequisite

Paparazzi uses [Browsershot](https://github.com/spatie/browsershot) to generate the images and requires a working installation of [Puppeteer](https://github.com/puppeteer/puppeteer) on your server and local machine.

### Configure Models

The first thing you should do is to configure your models in `config/paparazzi.php`.

### Create a layout and template

```bash
php please paparazzi:layout
```

```bash
php please paparazzi:template
```

## License
Paparazzi is **commercial software** but has an open-source codebase. If you want to use it in production, you'll need to [buy a license from the Statamic Marketplace](https://statamic.com/addons/aerni/paparazzi).
>Livewire Forms is **NOT** free software.

## Credits
Developed by[ Michael Aerni](https://www.michaelaerni.ch)
