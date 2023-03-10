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
    | Default Settings
    |--------------------------------------------------------------------------
    |
    | The default settings that will be used for all of your models.
    | Each of these settings can be overriden on the model.
    |
    */

    'defaults' => [

        /*
        |--------------------------------------------------------------------------
        | Layout
        |--------------------------------------------------------------------------
        |
        | The layout file that will be used to generate the images.
        |
        */

        'layout' => 'layout',

        /*
        |--------------------------------------------------------------------------
        | Template
        |--------------------------------------------------------------------------
        |
        | The template that will be used by default.
        |
        */

        'template' => 'default',

        /*
        |--------------------------------------------------------------------------
        | Extension
        |--------------------------------------------------------------------------
        |
        | The file extension of the generated images.
        |
        */

        'extension' => 'png',

        /*
        |--------------------------------------------------------------------------
        | Quality
        |--------------------------------------------------------------------------
        |
        | The quality of the generated images. This only applies when using `jpeg` as extension.
        |
        */

        'quality' => 100,

        /*
        |--------------------------------------------------------------------------
        | Asset Container
        |--------------------------------------------------------------------------
        |
        | The asset container where the generated images will be saved.
        |
        */

        'container' => 'assets',

        /*
        |--------------------------------------------------------------------------
        | Directory
        |--------------------------------------------------------------------------
        |
        | An optional directory inside your asset container where the images will be saved.
        |
        */

        'directory' => '/',

    ],

    /*
    |--------------------------------------------------------------------------
    | Views
    |--------------------------------------------------------------------------
    |
    | The path to the root directory of your templates.
    |
    */

    'views' => resource_path('views/paparazzi'),

    /*
    |--------------------------------------------------------------------------
    | Preview URL
    |--------------------------------------------------------------------------
    |
    | The base URL where you'll be able to preview your templates.
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
