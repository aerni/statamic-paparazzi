<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Actions\GetContentParent;
use Aerni\Paparazzi\Actions\GetContentType;
use Aerni\Paparazzi\Concerns\ExistsAsAsset;
use Aerni\Paparazzi\Concerns\HandlesConfig;
use Aerni\Paparazzi\Concerns\HandlesLivePreview;
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
    use HandlesConfig;
    use HandlesLivePreview;

    protected Entry|Term $content;

    protected array $data = [];

    protected string $uid;

    public function __construct()
    {
        $this->uid = uniqid();
        $this->config($this->defaultConfig());
    }

    public function id(): string
    {
        return "{$this->handle()}::{$this->template()}";
    }

    public function handle(?string $handle = null): string|self
    {
        if (is_null($handle)) {
            return $this->get('handle');
        }

        $this->set('handle', $handle);

        return $this;
    }

    public function width(?int $width = null): int|self
    {
        if (is_null($width)) {
            return $this->get('width');
        }

        $this->set('width', $width);

        return $this;
    }

    public function height(?int $height = null): int|self
    {
        if (is_null($height)) {
            return $this->get('height');
        }

        $this->set('height', $height);

        return $this;
    }

    public function extension(?string $extension = null): string|self
    {
        if (is_null($extension)) {
            return $this->get('extension');
        }

        $this->set('extension', $extension);

        return $this;
    }

    public function quality(?int $quality = null): int|self
    {
        if (is_null($quality)) {
            return $this->get('quality');
        }

        $this->set('quality', $quality);

        return $this;
    }

    public function container(?string $container = null): Container|self
    {
        if (is_null($container)) {
            return AssetContainer::findOrFail($this->get('container'));
        }

        $this->set('container', $container);

        return $this;
    }

    public function directory(?string $directory = null): string|self
    {
        if (is_null($directory)) {
            return $this->assembleDirectory($this->get('directory'));
        }

        $this->set('directory', $directory);

        return $this;
    }

    public function reference(?string $reference = null): string|self
    {
        if (is_null($reference)) {
            return $this->assembleReference($this->get('reference'));
        }

        $this->set('reference', $reference);

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

    public function replace(?bool $replace = null): bool|self
    {
        if (is_null($replace)) {
            return $this->get('replace');
        }

        $this->set('replace', $replace);

        return $this;
    }

    public function layout(?string $layout = null): Layout|self
    {
        if (is_null($layout)) {
            return LayoutApi::findOrFail($this->get('layout'));
        }

        $this->set('layout', $layout);

        return $this;
    }

    public function template(?string $template = null): Template|self
    {
        if (is_null($template)) {
            return TemplateApi::findOrFail("{$this->handle()}::{$this->get('template')}");
        }

        $this->set('template', $template);

        return $this;
    }

    public function content(Entry|Term|null $content = null): Entry|Term|null|self
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

    public function generate(?Closure $callback = null): self
    {
        $generator = $this->generator();

        if ($callback) {
            $generator->browsershot($callback);
        }

        return $generator->generate();
    }

    public function dispatch(?Closure $callback = null): self
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
