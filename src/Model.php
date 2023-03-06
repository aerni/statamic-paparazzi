<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Concerns\HasAsset;
use Aerni\Paparazzi\Facades\Paparazzi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Statamic\Contracts\Assets\AssetContainer as Container;
use Statamic\Contracts\Data\Augmentable;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Facades\AssetContainer;

class Model
{
    use HasAsset;

    protected int $width;
    protected int $height;
    protected string $extension;
    protected int $quality;
    protected string $container;
    protected string $directory;
    protected string $layout;
    protected string $template;
    protected Collection $content;

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
        $this->layout = config('paparazzi.defaults.layout', 'layout');
        $this->template = config('paparazzi.defaults.template', 'default');
        $this->content = collect();
    }

    public function setConfig(array $config): self
    {
        Validator::make($config, [
            'width' => 'required|integer',
            'height' => 'required|integer',
            'extension' => 'string|in:png,jpeg,pdf',
            'quality' => 'integer',
            'container' => 'string',
            'directory' => 'string',
            'layout' => 'string',
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

        Validator::make(['extension' => $extension], [
            'extension' => 'in:png,jpeg,pdf',
        ])->validate();

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

    public function layout(string $layout = null): string|self
    {
        if (! $layout) {
            $layout = collect(File::files(config('paparazzi.views')))
                ->firstWhere(fn ($file) => $file->getBasename('.antlers.html') === $this->layout);

            // TODO: Log exception if layout doesn't exist.

            $viewPath = Str::after($layout, 'views/');

            return Str::remove('.antlers.html', $viewPath);
        }

        $this->layout = $layout;

        return $this;
    }

    public function template(string $template = null): Template|self
    {
        if (! $template) {
            // TODO: Log exception if template doesn't exist.
            return $this->templates()->firstWhere(fn ($template) => $template->id() === $this->template);
        }

        $this->template = $template;

        return $this;
    }

    public function content(Entry|Term|Collection|array $content = null): Collection|self
    {
        if (is_null($content)) {
            return $this->content;
        }

        $this->content = $content instanceof Augmentable
            ? $content->toAugmentedCollection()
            : collect($content);

        return $this;
    }

    public function templates(): Collection
    {
        return collect(File::allFiles(config('paparazzi.views')))
            ->filter(fn ($file) => $file->getRelativePath() === $this->id)
            ->map(fn ($file) => new Template($file));
    }

    public function url(): string
    {
        $parameters = strtr(Paparazzi::route(), [
            'model' => $this->id(),
            'template' => $this->template()->id(),
            'contentId' => $this->content()->get('id'),
        ]);

        return Str::of($parameters)
            ->remove(['{', '}'])
            ->replace('_', '-')
            ->prepend(url('/'));
    }

    public function generate(): Generator
    {
        return $this->generator()->generate();
    }

    public function dispatch(): Generator
    {
        return $this->generator()->dispatch();
    }

    public function generator(): Generator
    {
        return new Generator($this);
    }
}
