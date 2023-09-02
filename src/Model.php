<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Actions\GetContentParent;
use Aerni\Paparazzi\Actions\GetContentType;
use Aerni\Paparazzi\Concerns\ExistsAsAsset;
use Aerni\Paparazzi\Facades\Layout as LayoutApi;
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
    use ExistsAsAsset;

    protected Config $config;

    protected Entry|Term $content;

    protected array $data = [];

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
        return "{$this->reference()}-{$this->uid}";
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

    public function directory(string $directory = null): string|self
    {
        if (is_null($directory)) {
            return $this->assembleDirectory($this->config->directory());
        }

        $this->config->directory($directory);

        return $this;
    }

    public function reference(string $reference = null): string|self
    {
        if (is_null($reference)) {
            return $this->assembleReference($this->config->reference());
        }

        $this->config->reference($reference);

        return $this;
    }

    protected function assembleDirectory(string $directory): string
    {
        return Path::assemble($this->parseVariables($directory));
    }

    protected function assembleReference(string $reference): string
    {
        return Str::of($this->parseVariables($reference))->slug();
    }

    protected function parseVariables(string $value): string
    {
        $variables = collect([
            '{model}' => $this->handle(),
            '{layout}' => $this->layout()->handle(),
            '{template}' => $this->template()->handle(),
            '{type}' => GetContentType::handle($this->content()),
            '{parent}' => GetContentParent::handle($this->content())?->handle(),
            '{site}' => Site::hasMultiple() ? $this->content()?->locale() : null,
            '{slug}' => $this->content()?->slug(),
        ])
            ->map(fn ($value) => Str::of($value)->slug())
            ->all();

        return strtr($value, $variables);
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

    public function with(string|array $key, mixed $value = null): self
    {
        if (is_array($key)) {
            $this->data = array_merge($this->data, $key);
        } else {
            $this->data[$key] = $value;
        }

        return $this;
    }

    public function data(): array
    {
        return $this->data;
    }

    public function gatherData(): array
    {
        return array_merge($this->data, [
            'model' => $this->config(),
        ]);
    }

    public function templates(): Collection
    {
        return TemplateApi::allOfModel($this->handle());
    }

    public function livePreviewUrl(): string
    {
        return cp_route(
            'paparazzi.live-preview',
            explode('/', $this->parseVariables('{model}/{layout}/{template}'))
        );
    }

    public function view(): View
    {
        return (new View)
            ->layout($this->layout()->view())
            ->template($this->template()->view())
            ->cascadeContent($this->content())
            ->with($this->gatherData());
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
