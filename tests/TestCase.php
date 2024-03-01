<?php

namespace Aerni\Paparazzi\Tests;

use Aerni\Paparazzi\ServiceProvider;
use Illuminate\Contracts\Config\Repository;
use Orchestra\Testbench\TestCase as Orchestra;
use Statamic\Extend\Manifest;
use Statamic\Facades\File;
use Statamic\Providers\StatamicServiceProvider;

class TestCase extends Orchestra
{
    protected function getPackageProviders($app)
    {
        return [
            ServiceProvider::class,
            StatamicServiceProvider::class,
        ];
    }

    protected function defineEnvironment($app)
    {
        $app->make(Manifest::class)->manifest = [
            'aerni/paparazzi' => [
                'id' => 'aerni/paparazzi',
                'namespace' => 'Aerni\\Paparazzi',
                'provider' => 'Aerni\\Paparazzi\\ServiceProvider',
                'autoload' => 'src',
            ],
        ];

        tap($app['config'], function (Repository $config) {
            $config->set('paparazzi', require (__DIR__.'/../config/paparazzi.php'));
        });
    }
}
