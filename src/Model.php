<?php

namespace Aerni\Paparazzi;

use Closure;
use Statamic\View\View;
use Statamic\Support\Str;
use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Illuminate\Support\Collection;
use Statamic\Facades\AssetContainer;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Aerni\Paparazzi\Concerns\ExistsAsAsset;
use Aerni\Paparazzi\Actions\GetContentParent;
use Aerni\Paparazzi\Facades\Layout as LayoutApi;
use Aerni\Paparazzi\Facades\Template as TemplateApi;
use Statamic\Contracts\Assets\AssetContainer as Container;

class Model
{
    use ExistsAsAsset;

    protected Config $config;
    protected Entry|Term $content;
    protected int $uid;

    public function __construct(protected string $handle, array $config)
    {
        $this->config = new Config($config);
        $this->uid = time();
    }

    public function config(): array
    {
        return $this->config->all();
    }

    public function id(): string
    {
        return $this->handle();
    }

    public function handle(string $handle = null): string|self
    {
        if (is_null($handle)) {
            return $this->handle;
        }

        $this->handle = $handle;

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
            'root' => '/',
            'parent' => GetContentParent::handle($this->content()),
            'site' => Site::hasMultiple() ? $this->content()?->locale() : null,
            'slug' => $this->content()?->slug(),
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
            return LayoutApi::find($this->config->layout());
        }

        $this->config->layout($layout);

        return $this;
    }

    public function template(string $template = null): Template|self
    {
        if (is_null($template)) {
            return TemplateApi::find("{$this->handle()}::{$this->config->template()}");
        }

        $this->config->template($template);

        return $this;
    }

    public function content(Entry|Term $content = null): Entry|Term|null|self
    {
        if (is_null($content)) {
            return $this->content ?? null;
        }

        $this->content = $content;

        return $this;
    }

    public function templates(): Collection
    {
        return TemplateApi::allOfModel($this->handle());
    }

    public function cpUrl(): string
    {
        return cp_route('paparazzi', $this->routeParameters());
    }

    public function webUrl(): string
    {
        return route('paparazzi', $this->routeParameters());
    }

    protected function routeParameters(): array
    {
        return collect([
            'model' => $this->handle(),
            'layout' => $this->layout()->name(),
            'template' => $this->template()->name(),
            'contentId' => $this->content()?->id(), // TODO: Should this be the slug instead?
        ])
        ->map(fn ($value) => Str::of($value)->slug('-')->toString())
        ->filter()
        ->all();
    }

    public function view(): View
    {
        return (new View)
            ->layout($this->layout()->view())
            ->template($this->template()->view())
            ->cascadeContent($this->content())
            ->with(['model' => $this->config()]);
    }

    public function name(): string
    {
        return Str::slugToTitle($this->handle());
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

    public function __toString(): string
    {
        return $this->handle();
    }
}
