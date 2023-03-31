<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Models
    |--------------------------------------------------------------------------
    |
    | The models that will be used when generating the images.
    |
    */

    'models' => [

        'open_graph' => [
            'width' => 1200,
            'height' => 630,
        ],

        'twitter' => [
            'width' => 240,
            'height' => 240,
        ],

        'twitter_large' => [
            'width' => 1200,
            'height' => 630,
        ],

    ],

    /*
    |--------------------------------------------------------------------------
    | Default Model Settings
    |--------------------------------------------------------------------------
    |
    | The default settings that will be used for all models.
    | You can override each setting on the model itself.
    |
    */

    'defaults' => [

        /*
        |--------------------------------------------------------------------------
        | Layout
        |--------------------------------------------------------------------------
        |
        | The default layout view that will be used to generate the images.
        |
        */

        'layout' => 'default',

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        |
        | The default template view that will be used to generate the images.
        |
        */

        'template' => 'default',

        /*
        |--------------------------------------------------------------------------
        | Extension
        |--------------------------------------------------------------------------
        |
        | The default file extension of the generated images.
        |
        */

        'extension' => 'png',

        /*
        |--------------------------------------------------------------------------
        | Quality
        |--------------------------------------------------------------------------
        |
        | The default quality of the generated images.
        | This only applies when your model is using `jpeg` as extension.
        |
        */

        'quality' => 100,

        /*
        |--------------------------------------------------------------------------
        | Asset Container
        |--------------------------------------------------------------------------
        |
        | The default asset container where the generated images will be saved.
        |
        */

        'container' => 'assets',

        /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        |
        | The default directory where the generated images will be saved.
        |
        */

        'directory' => '{type}/{parent}/{site}/{slug}',

        /*
        |--------------------------------------------------------------------------
        | Reference
        |--------------------------------------------------------------------------
        |
        | The default asset reference of the generated images. This is part of the filename.
        |
        */

        'reference' => '{model}-{layout}-{template}-{parent}-{site}-{slug}',

        /*
        |--------------------------------------------------------------------------
        | Replace Latest Asset
        |--------------------------------------------------------------------------
        |
        | Previously generated images of a model will be replaced when a new image is generated.
        | If you want to keep all images, you can set this to `false`.
        |
        */

        'replace' => true,

    ],

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    |
    | The path to the root directory of your layouts and templates.
    |
    */

    'views' => resource_path('views/paparazzi'),

    /*
    |--------------------------------------------------------------------------
    | Preview URL
    |--------------------------------------------------------------------------
    |
    | The base URL where you'll be able to preview your templates.
    | The preview is only available when developing and disabled for all other environments.
    |
    */

    'preview_url' => '/paparazzi',

    /*
    |--------------------------------------------------------------------------
    | Queue
    |--------------------------------------------------------------------------
    |
    | The queue that is used when generating the images.
    |
    */

    'queue' => 'default',

];
