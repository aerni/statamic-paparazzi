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

## Working with models

A model will be created for each template that exists for a given model. The ID of a model will constructed from the model's handle and its template. Let's say you have an `open_graph` model with a `default`, `article`, and `video` template. This would result in three models with IDs of `open_graph::default`, `open_graph::article`, and `open_graph::video`.

Use the `Model` facade to get a model by its ID:

```php
use Aerni\Paparazzi\Facades\Model;

Model::find('open_graph::default');
```

Alternatively, you can use the model's handle as the method and pass the template as an argument:

```php
Model::openGraph('default');
```

If you don't pass the template as an argument, you will get the model with the default template as defined in the `config('paparazzi.defaults.template')`. If you don't have a template with that name, it will return the first model it can find.

```php
Model::openGraph();
```

You can also get all the models at once:

```php
Model::all();
```

Or only a selection of models:

```php
Model::all(['open_graph::defaults', 'twitter::article']);
```

Or get all the models of a specific type:

```php
Model::allOfType('open_graph');
```

## Generating an image

Now you can simply call the `generate()` method on the model:

```php
Model::openGraph()->generate();
```

Or generate the image in the background by dispatching a job:

```php
Model::openGraph()->dispatch();
```

If you want the data of an entry or term available in the template, you can add the entry/term with the `content()` method:

```php
Model::openGraph()->content($entry)->generate();
```

Generate the images of all models with the content of an entry.

```php
Model::all()->each->content($entry)->generate();
```

You can also pass a callback to the `generate` or `dispatch` method to configure the browsershot instance.

```php
Model::twitter()->generate(fn ($browsershot) => $browsershot->fullPage());
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
/{preview_url}/{model}

// Example
/paparazzi/open_graph
```

This will return the default template of the `open_graph` model. To show a different model, provide the full ID instead:

```
/paparazzi/open_graph::article
```

You may also change the layout that is used with the `layout` parameter:

```
/paparazzi/open_graph::article?layout=secondary
```

To add the content of an entry or term to the rendered template, add the `content` query parameter containing the entry's or term's ID.

```
// Entry ID
/paparazzi/open_graph::article&content=c3d19675-c7b8-49c5-84eb-9c5eb9713644

// Term ID
/paparazzi/open_graph::article&content=cars::audi
```

Use the optional `site` parameter to get the entry or term in a specific localization.

```
/paparazzi/open_graph::article&content=c3d19675-c7b8-49c5-84eb-9c5eb9713644?site=german
```

## Live Preview

Add a model to the Live Preview of all collections:

```php
public function handle(EntryBlueprintFound $event)
{
    Model::openGraph()->addLivePreviewToCollection();
}
```

Add a model to the Live Preview of a specific collection:

```php
public function handle(EntryBlueprintFound $event)
{
    Model::openGraph()->addLivePreviewToCollection('pages');
}
```

Add a model to the Live Preview of multiple selected collection:

```php
public function handle(EntryBlueprintFound $event)
{
    Model::openGraph()->addLivePreviewToCollection(['pages', 'articles']);
}
```

Add a model to the Live Preview of all taxonomies:

```php
public function handle(EntryBlueprintFound $event)
{
    Model::openGraph()->addLivePreviewToTaxonomy();
}
```

Add a model to the Live Preview of a specific taxonomy:

```php
public function handle(EntryBlueprintFound $event)
{
    Model::openGraph()->addLivePreviewToTaxonomy('categories');
}
```

Add a model to the Live Preview of multiple selected taxonomies:

```php
public function handle(EntryBlueprintFound $event)
{
    Model::openGraph()->addLivePreviewToTaxonomy(['categories', 'tags']);
}
```

You can also add a model to collections and taxonomies at the same time:

```php
public function handle(TermBlueprintFound $event)
{
    Model::openGraph()
        ->addLivePreviewToCollection(['pages', 'articles']);
        ->addLivePreviewToTaxonomy('tags');
}
```

## License
Paparazzi is free to use software but may not be reused in other projects without the express written consent of Michael Aerni.

## Credits
Developed by [Michael Aerni](https://michaelaerni.ch)
