<?php

namespace Aerni\Paparazzi;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Statamic\Facades\AssetContainer;
use Illuminate\Support\Facades\Validator;
use Statamic\Contracts\Assets\AssetContainer as Container;

class Model
{
    protected int $width;
    protected int $height;
    protected string $extension;
    protected int $quality;
    protected string $container;
    protected string $directory;
    protected string $template;

    public function __construct(protected string $id, array $config)
    {
        $this->initDefaultConfig();
        $this->setConfig($config);
    }

    protected function initDefaultConfig(): void
    {
        $this->extension = config('paparazzi.defaults.extension', 'png');
        $this->quality = config('paparazzi.defaults.quality', 100);
        $this->container = config('paparazzi.defaults.container', 'assets');
        $this->directory = config('paparazzi.defaults.directory', '/');
        $this->template = config('paparazzi.defaults.template', 'default');
    }

    public function setConfig(array $config): self
    {
        Validator::make($config, [
            'width' => 'required|integer',
            'height' => 'required|integer',
            'extension' => 'string',
            'quality' => 'integer',
            'container' => 'string',
            'directory' => 'string',
            'template' => 'string',
        ])->validate();

        foreach ($config as $key => $value) {
            if (property_exists($this, $key)) {
                $this->$key = $value;
            }
        }

        return $this;
    }

    public function id(string $id = null): string|self
    {
        if (! $id) {
            return $this->id;
        }

        $this->id = $id;

        return $this;
    }

    public function width(int $width = null): int|self
    {
        if (! $width) {
            return $this->width;
        }

        $this->width = $width;

        return $this;
    }

    public function height(int $height = null): int|self
    {
        if (! $height) {
            return $this->height;
        }

        $this->height = $height;

        return $this;
    }

    public function extension(string $extension = null): string|self
    {
        if (! $extension) {
            return $this->extension;
        }

        $this->extension = $extension;

        return $this;
    }

    public function quality(int $quality = null): int|self
    {
        if (! $quality) {
            return $this->quality;
        }

        $this->quality = $quality;

        return $this;
    }

    public function container(string $container = null): Container|self
    {
        if (! $container) {
            return AssetContainer::find($this->container);
        }

        $this->container = $container;

        return $this;
    }

    public function directory(string $directory = null): string|self
    {
        if (! $directory) {
            return $this->directory;
        }

        $this->directory = $directory;

        return $this;
    }

    public function template(string $template = null): Template|self
    {
        if (! $template) {
            return $this->templates()->firstWhere(fn ($template) => $template->id() === $this->template);
        }

        $this->template = $template;

        return $this;
    }

    public function templates(): Collection
    {
        return collect(File::allFiles(config('paparazzi.views')))
            ->filter(fn ($file) => $file->getRelativePath() === $this->id)
            ->map(fn ($file) => new Template($file));
    }
}
