<?php

namespace Aerni\ImageGenerator;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\GenerateImage::class,
        Commands\MakeTheme::class,
    ];

    protected $routes = [
        'actions' => __DIR__.'/../routes/actions.php',
    ];

    public function bootAddon(): void
    {
        $this->autoPublishConfig();
    }

    protected function autoPublishConfig(): self
    {
        Statamic::afterInstalled(function ($command) {
            $command->call('vendor:publish', [
                '--tag' => 'image-generator-config',
            ]);
        });

        return $this;
    }
}
