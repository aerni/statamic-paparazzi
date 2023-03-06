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

        'instagram_post' => [
            'width' => 1080,
            'height' => 1080,
            'template' => 'default',
            // 'extension' => 'jpg',
            // 'quality' => 80,
            // 'container' => 'a',
            // 'directory' => 'instagram',
        ],

        'instagram_story' => [
            'width' => 1080,
            'height' => 1920,
        ],

        'open_graph' => [
            'width' => 1200,
            'height' => 628,
        ],

        'twitter_summary' => [
            'width' => 240,
            'height' => 240,
        ],

        'twitter_summary_large_image' => [
            'width' => 1200,
            'height' => 628,
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
        | The quality of the generated images.
        |
        */

        'quality' => 100,

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
