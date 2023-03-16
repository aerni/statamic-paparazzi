<?php

namespace Aerni\Paparazzi;

use Statamic\Providers\AddonServiceProvider;
use Statamic\Statamic;

class ServiceProvider extends AddonServiceProvider
{
    protected $commands = [
        Commands\MakeTheme::class,
    ];

    protected $routes = [
        'web' => __DIR__.'/../routes/web.php',
    ];

    public function bootAddon(): void
    {
        $this->autoPublishConfig();
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
