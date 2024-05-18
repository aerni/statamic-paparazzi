<?php

namespace Aerni\Paparazzi;

use Statamic\Statamic;
use Aerni\Paparazzi\Config;
use Aerni\Paparazzi\Stores\LayoutsStore;
use Aerni\Paparazzi\Stores\ModelsStore;
use Illuminate\Support\Facades\File;
use Aerni\Paparazzi\Stores\TemplatesStore;
use Statamic\Providers\AddonServiceProvider;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeLayout::class,
        Commands\MakeTemplate::class,
    ];

    protected $routes = [
        'cp' => __DIR__.'/../routes/cp.php',
        'web' => __DIR__.'/../routes/web.php',
    ];

    public function register(): void
    {
        $this->registerStores();
    }

    public function bootAddon(): void
    {
        $this->autoPublishConfig();
    }

    protected function registerStores(): self
    {
        $this->app->singleton(ModelsStore::class, function ($app) {
            return new ModelsStore(config('paparazzi.models'));
        });

        $this->app->singleton(LayoutsStore::class, function ($app) {
            $items = collect(File::allFiles(config('paparazzi.views')))
                ->filter(fn ($file) => empty($file->getRelativePath()));

            return new LayoutsStore($items);
        });

        $this->app->singleton(TemplatesStore::class, function ($app) {
            $items = collect(File::allFiles(config('paparazzi.views')))
                ->filter(fn ($file) => ! empty($file->getRelativePath()));

            return new TemplatesStore($items);
        });

        return $this;
    }

    protected function autoPublishConfig(): self
    {
        Statamic::afterInstalled(function ($command) {
            $command->call('vendor:publish', [
                '--tag' => 'paparazzi-config',
            ]);
        });

        return $this;
    }
}
