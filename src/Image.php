<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Facades\Paparazzi;
use Closure;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Spatie\Browsershot\Browsershot;
use Statamic\Contracts\Assets\Asset;
use Statamic\Contracts\Entries\Entry;
use Statamic\Facades\Path;

class Image
{
    protected Model $model;
    protected Browsershot $browsershot;
    protected Entry $entry;

    public function __construct(Model $model)
    {
        $this->model = $model;
        $this->browsershot = new Browsershot();
    }

    public static function make(Model $model): self
    {
        return new static($model);
    }

    // TODO: This should also work with any other data like Term or array.
    // Should we use a similar way like Statamic's View that has with and cascadeContent or something like that?
    public function with(Entry $entry): self
    {
        $this->entry = $entry;

        return $this;
    }

    public function model(Closure $callback = null): Model|self
    {
        if (! $callback) {
            return $this->model;
        }

        $this->model = $callback($this->model);

        return $this;
    }

    public function browsershot(Closure $callback = null): Browsershot|self
    {
        if (! $callback) {
            return $this->browsershot;
        }

        $this->browsershot = $callback($this->browsershot);

        return $this;
    }

    public function generate(): self
    {
        // TODO: Delete previously generated assets. Can we use Statamic's replace method on the Asset class for this?
        $this->ensureDirectoryExists();

        Browsershot::url($this->url())
            ->windowSize($this->model->width(), $this->model->height())
            ->setScreenshotType($this->model->extension(), $this->model->quality())
            ->save($this->absolutePath()); // TODO: Save in a temporary file an delete after.

        $this->model->container()->makeAsset($this->path())->save();

        return $this;
    }

    public function asset(): ?Asset
    {
        return $this->model->container()->asset($this->path());
    }

    public function delete(): void
    {
        $this->asset()?->delete();
    }

    public function path(): string
    {
        return Path::assemble(
            config('paparazzi.directory'),
            $this->entry->collection,
            $this->filename()
        );
    }

    protected function absolutePath($path = null): string
    {
        return $this->model->container()->disk()->path($path ?? $this->path());
    }

    public function filename(): string
    {
        return "{$this->id()}.png";
    }

    public function id(): string
    {
        return "{$this->entry->id}_{$this->name()}";
    }

    public function name(): string
    {
        return $this->name ?? Str::snake(class_basename($this));
    }

    public function url(): string
    {
        $parameters = strtr(Paparazzi::route(), [
            'model' => $this->model->id(),
            'template' => $this->model->template()->id(),
            'contentId' => $this->entry->id,
        ]);

        return Str::of($parameters)
            ->remove(['{', '}'])
            ->replace('_', '-')
            ->prepend(url('/'));
    }

    protected function ensureDirectoryExists(): void
    {
        $directory = $this->absolutePath(pathinfo($this->path(), PATHINFO_DIRNAME));

        File::ensureDirectoryExists($directory);
    }
}
