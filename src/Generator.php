<?php

namespace Aerni\Paparazzi;

use Closure;
use Statamic\View\View;
use Spatie\Browsershot\Browsershot;
use Illuminate\Support\Facades\File;
use Aerni\Paparazzi\Jobs\GenerateAssetJob;

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
            ->html($this->view()->render())
            ->windowSize($this->model->width(), $this->model->height())
            ->setScreenshotType($this->model->extension(), $quality);

        if ($this->callback) {
            call_user_func($this->callback, $this->browsershot);
        }

        return $this;
    }

    public function generate(): self
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

        $this->browsershot->save($this->model->absolutePath());

        $this->model->container()
            ->makeAsset($this->model->path())
            ->save();

        return $this;
    }

    public function dispatch(): self
    {
        $this->hydrateBrowsershot(); // Hydrate browsershot with the callbacks
        unset($this->callback); // Remove the callbacks to avoid closure serialization exception

        GenerateAssetJob::dispatch($this);

        return $this;
    }

    protected function view(): View
    {
        return (new View)
            ->layout($this->model->layout()->view())
            ->template($this->model->template()->view())
            ->with($this->model->content()->toArray());
    }

    protected function ensureDirectoryExists(): void
    {
        $directory = $this->model->absolutePath(pathinfo($this->model->path(), PATHINFO_DIRNAME));

        File::ensureDirectoryExists($directory);
    }
}
