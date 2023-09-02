![Statamic](https://flat.badgen.net/badge/Statamic/3.4+/FF269E) ![Packagist version](https://flat.badgen.net/packagist/v/aerni/paparazzi/latest) ![Packagist Total Downloads](https://flat.badgen.net/packagist/dt/aerni/paparazzi)

# Paparazzi
This addon provides an easy-to-use interface to generate images of your entries and terms. A common use case would be to generate social images for Open Graph and Twitter.

## Prerequisite

Paparazzi uses [Browsershot](https://github.com/spatie/browsershot) to generate the images and requires a working installation of [Puppeteer](https://github.com/puppeteer/puppeteer) on your server and local machine.

## Installation
Install the addon using Composer:

```bash
composer require aerni/paparazzi
```

The config will be automatically published to `config/paparazzi.php` as part of the installation process.

## Configuring Models

The first thing you should do is to configure your models in `config/paparazzi.php`. You can add as many models as you like. The only requirement for a model is the `width` and `height`. All other configuration options are passed down from the `defaults` array. You may override any default value by setting it on the model itself.

```php
'models' => [

    'open_graph' => [
        'width' => 1200,
        'height' => 630,
    ],

    'instagram_post' => [
        'width' => 1080,
        'height' => 1080,
        'extension' => 'jpeg',
        'quality' => 80,
        'container' => 'instagram',
    ],

],
```

## Layouts & Templates

Next, you should create your first layout and templates for your models. Use the following commands to do so:

| Command              | Description                                |
| -------------------- | ------------------------------------------ |
| `paparazzi:layout`   | Create a new Paparazzi layout view         |
| `paparazzi:template` | Create a new Paparazzi model template view |

The views will be saved to `resources/views/paparazzi`. If you'd like to use another path, you can change it in the config.

## Generating an image

Get a single paparazzi model by handle:

```php
use Aerni\Paparazzi\Facades\Paparazzi;

// Get a model using a camel case method of its handle:
Paparazzi::openGraph();

// Or get a model by its handle:
Paparazzi::model('open_graph');
```

Now you can simply call the `generate()` method on the model:

```php
Paparazzi::openGraph()->generate();
```

Or generate the image in the background by dispatching a job:

```php
Paparazzi::openGraph()->dispatch();
```

If you want the data of an entry or term available in the template, you can add the entry/term with the `content()` method:

```php
Paparazzi::openGraph()->content($entry)->generate();
```

You can also get all the Paparazzi models at once:

```php
Paparazzi::models();
```

Or only a selection of models:

```php
Paparazzi::models(['open_graph', 'twitter_summary']);
```

Generate the images of all models with the content of an entry.

```php
Paparazzi::models()->each->content($entry)->generate();
```

You can also pass a callback to the `generate` or `dispatch` method to configure the browsershot instance.

```php
Paparazzi::twitter()->generate(fn ($browsershot) => $browsershot->fullPage());
```

## Asset management

The generated images will be saved as a Statamic asset. The asset container, directory, and file reference can be changed in the config.

### Variables
You may use a couple of variables to customize the directory and file reference.

| Variable     | Description                                       | Availability
| ------------ | ------------------------------------------------- | ------------------------------------
| `{model}`    | The handle of the model                           | Always
| `{layout}`   | The handle of the layout                          | Always
| `{template}` | The handle of the template                        | Always
| `{type}`     | Evaluates to either `collections` or `taxonomies` | Only with content
| `{parent}`   | The handle of the collection or taxonomy          | Only with content
| `{site}`     | The site of the entry or term                     | Only with content and on multi-sites
| `{slug}`     | The slug of the entry or term                     | Only with content

### File Reference
The reference is used to get the generated images that belong to a model.

```php
'directory' => '{type}/{parent}/{site}/{slug}',
'reference' => '{model}-{layout}-{template}-{parent}-{site}-{slug}',
```

## Previewing Templates

You may preview your templates in the browser according to the following URL schema. The preview is only available in `local` environment.

```
// Schema
/{preview_url}/{model}/{layout}/{template}

// Example
/paparazzi/open-graph/default/default
```

Preview a template with the content of an entry:

```
// Schema:
/{preview_url}/{model}/{layout}/{template}/collections/{entry}/{site?}

// Example:
/paparazzi/open-graph/default/default/collections/c3d19675-c7b8-49c5-84eb-9c5eb9713644
```

Use the optional `site` parameter to get the entry in a different localization.

```
// Schema:
/{preview_url}/{model}/{layout}/{template}/collections/{entry}/{site?}

// Example:
/paparazzi/open-graph/default/default/collections/c3d19675-c7b8-49c5-84eb-9c5eb9713644/german
```

Preview a template with the content of a term:

```
// Schema:
/{preview_url}/{model}/{layout}/{template}/taxonomies/{taxonomy}/{term}/{site?}

// Example:
/paparazzi/open-graph/default/default/taxonomies/tags/tag-1
```

Use the optional `site` parameter to get the term in a different localization.

```
// Schema:
/{preview_url}/{model}/{layout}/{template}/taxonomies/{taxonomy}/{term}/{site?}

// Example:
/paparazzi/open-graph/default/default/taxonomies/tags/tag-1/german
```

> All parameters are kebab case, e.g. model `twitter_summary` becomes `twitter-summary`.

## Live Preview

Add a single model to a single collection:

```php
public function handle(EntryBlueprintFound $event)
{
    LivePreview::addModel('open_graph')
        ->toCollection('pages');
}
```

Add multiple models to multiple collections:

```php
public function handle(EntryBlueprintFound $event)
{
    LivePreview::addModel(['open_graph', 'twitter'])
        ->toCollection(['pages', 'articles']);
}
```

You can also add Live Preview to taxonomies:

```php
public function handle(TermBlueprintFound $event)
{
    LivePreview::addModel('open_graph')->toTaxonomy('tags');
}
```

## License
Paparazzi is free to use software but may not be reused in other projects without the express written consent of Michael Aerni.

## Credits
Developed by [Michael Aerni](https://michaelaerni.ch)
