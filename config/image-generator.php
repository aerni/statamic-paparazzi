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

        'open_graph' => ['width' => 1200, 'height' => 628],
        'twitter_summary' => ['width' => 240, 'height' => 240],
        'twitter_summary_large_image' => ['width' => 1200, 'height' => 628],

    ],

    /*
    |--------------------------------------------------------------------------
    | Themes
    |--------------------------------------------------------------------------
    |
    | The path to the view directory of the themes.
    |
    */

    'themes' => resource_path('views/image_generator'),

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
    | An optional path inside your asset container where the images will be saved.
    |
    */

    'directory' => null,

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
