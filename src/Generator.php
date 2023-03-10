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
    protected $callbacks = [];

    public function __construct(protected Model $model)
    {
        $this->browsershot = new Browsershot();
    }

    public function browsershot(Closure $callback): Browsershot|self
    {
        $this->callbacks[] = $callback;

        return $this;
    }

    public function generate(): self
    {
        $this->ensureDirectoryExists();

        // Quality is only supported for jpeg.
        $quality = $this->model->extension() === 'jpeg'
            ? $this->model->quality() : null;

        $browsershot = $this->browsershot
            ->html($this->view()->render())
            ->windowSize($this->model->width(), $this->model->height())
            ->setScreenshotType($this->model->extension(), $quality);

        foreach ($this->callbacks as $callback) {
            $callback($browsershot);
        }

        $browsershot->save($this->model->absolutePath());

        $this->model->container()
            ->makeAsset($this->model->path())
            ->save();

        return $this;
    }

    public function dispatch(): self
    {
        GenerateAssetJob::dispatch($this);

        return $this;
    }

    public function view(): View
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
