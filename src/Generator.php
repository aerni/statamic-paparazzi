<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Jobs\GenerateAssetJob;
use Closure;
use Illuminate\Support\Facades\File;
use Spatie\Browsershot\Browsershot;

class Generator
{
    protected Browsershot $browsershot;
    protected $callback;

    public function __construct(protected Model $model)
    {
        //
    }

    public function browsershot(Closure $callback): Browsershot|self
    {
        $this->callback = $callback;

        return $this;
    }

    protected function hydrateBrowsershot(): self
    {
        // Quality is only supported for jpeg.
        $quality = $this->model->extension() === 'jpeg'
            ? $this->model->quality() : null;

        $this->browsershot = (new Browsershot())
            ->html($this->model->view()->render())
            ->windowSize($this->model->width(), $this->model->height())
            ->setScreenshotType($this->model->extension(), $quality)
            ->waitUntilNetworkIdle();

        if ($this->callback) {
            call_user_func($this->callback, $this->browsershot);
        }

        return $this;
    }

    public function generate(): Model
    {
        $this->ensureDirectoryExists();

        /**
         * Only hydrate browserhot if it hasn't already happened.
         * We don't want to override previously hydrated browsershot settings,
         * which is the case when this method is called from a dispatched job.
         */
        if (! isset($this->browsershot)) {
            $this->hydrateBrowsershot();
        }

        $latestAsset = $this->model->latestAsset();

        $this->browsershot->save($this->model->absolutePath());

        $asset = $this->model->makeAsset();

        if ($latestAsset && $this->model->replace()) {
            $asset->replace($latestAsset, true);
        }

        $asset->save();

        return $this->model;
    }

    public function dispatch(): Model
    {
        $this->hydrateBrowsershot(); // Hydrate browsershot with the callbacks
        unset($this->callback); // Remove the callbacks to avoid closure serialization exception

        GenerateAssetJob::dispatch($this);

        return $this->model;
    }

    protected function ensureDirectoryExists(): void
    {
        $directory = $this->model->absolutePath($this->model->directory());

        File::ensureDirectoryExists($directory);
    }
}
