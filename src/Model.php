<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Actions\GetContentParent;
use Aerni\Paparazzi\Concerns\HasAsset;
use Aerni\Paparazzi\Facades\Layout as LayoutApi;
use Aerni\Paparazzi\Facades\Paparazzi;
use Aerni\Paparazzi\Facades\Template as TemplateApi;
use Closure;
use Illuminate\Support\Collection;
use Statamic\Contracts\Assets\AssetContainer as Container;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Facades\AssetContainer;
use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Statamic\Support\Str;
use Statamic\View\View;

class Model
{
    use HasAsset;

    protected Config $config;
    protected Entry|Term $content;
    protected int $uid;

    public function __construct(protected string $id, array $config)
    {
        $this->config = new Config($config);
        $this->uid = time();
    }

    public function config(): array
    {
        return $this->config->all();
    }

    public function handle(): string
    {
        return $this->id();
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
            return TemplateApi::find("{$this->id()}::{$this->config->template()}");
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
        return TemplateApi::allOfModel($this->id);
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
            'model' => $this->id(),
            'layout' => $this->layout()->name(),
            'template' => $this->template()->name(),
            'contentId' => $this->content()?->id(),
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
        return Str::slugToTitle($this->id());
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
