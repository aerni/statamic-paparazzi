<?php

namespace Aerni\Paparazzi;

use Closure;
use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
use Statamic\Facades\AssetContainer;
use Statamic\Contracts\Entries\Entry;
use Aerni\Paparazzi\Concerns\HasAsset;
use Aerni\Paparazzi\Facades\Paparazzi;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Contracts\Data\Augmentable;
use Aerni\Paparazzi\Facades\Layout as LayoutApi;
use Aerni\Paparazzi\Facades\Template as TemplateApi;
use Statamic\Contracts\Assets\AssetContainer as Container;

class Model
{
    use HasAsset;

    protected Config $config;
    protected Collection $content;
    protected int $uid;

    public function __construct(protected string $id, array $config)
    {
        $this->config = new Config($config);
        $this->content = collect();
        $this->uid = time();
    }

    public function config(): array
    {
        return $this->config->all();
    }

    public function id(string $id = null): string|self
    {
        if (is_null($id)) {
            return $this->id;
        }

        $this->id = $id;

        return $this;
    }

    public function width(int $width = null): int|self
    {
        if (is_null($width)) {
            return $this->config->width();
        }

        $this->config->width($width);

        return $this;
    }

    public function height(int $height = null): int|self
    {
        if (is_null($height)) {
            return $this->config->height();
        }

        $this->config->height($height);

        return $this;
    }

    public function extension(string $extension = null): string|self
    {
        if (is_null($extension)) {
            return $this->config->extension();
        }

        $this->config->extension($extension);

        return $this;
    }

    public function quality(int $quality = null): int|self
    {
        if (is_null($quality)) {
            return $this->config->quality();
        }

        $this->config->quality($quality);

        return $this;
    }

    public function container(string $container = null): Container|self
    {
        if (is_null($container)) {
            return AssetContainer::find($this->config->container());
        }

        $this->config->container($container);

        return $this;
    }

    public function directory(string $directory = null): string|null|self
    {
        if (is_null($directory)) {
            return $this->config->directory() ?? $this->defaultDirectory();
        }

        $this->config->directory($directory);

        return $this;
    }

    protected function defaultDirectory(): ?string
    {
        $segments = array_filter([
            $this->content->get('collection'),
            $this->content->get('taxonomy'),
            Site::hasMultiple() ? $this->content->get('locale') : null,
            $this->content->get('slug'),
        ]);

        return Path::assemble($segments);
    }

    public function replace(bool $replace = null): bool|self
    {
        if (is_null($replace)) {
            return $this->config->replace();
        }

        $this->config->replace($replace);

        return $this;
    }

    public function layout(string $layout = null): Layout|self
    {
        if (is_null($layout)) {
            // TODO: Log exception if layout doesn't exist.
            return LayoutApi::find($this->config->layout());
        }

        $this->config->layout($layout);

        return $this;
    }

    public function template(string $template = null): Template|self
    {
        if (is_null($template)) {
            // TODO: Log exception if template doesn't exist.
            return TemplateApi::find("{$this->id()}::{$this->config->template()}");
        }

        $this->config->template($template);

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
        return TemplateApi::allOfModel($this->id);
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

    public function generate(Closure $callback = null): self
    {
        $generator = $this->generator();

        if ($callback) {
            $generator->browsershot($callback);
        }

        return $generator->generate();
    }

    public function dispatch(Closure $callback = null): self
    {
        $generator = $this->generator();

        if ($callback) {
            $generator->browsershot($callback);
        }

        return $generator->dispatch();
    }

    public function generator(): Generator
    {
        return new Generator($this);
    }
}
