<?php

namespace Aerni\Paparazzi\Concerns;

use Statamic\Facades\Path;
use Statamic\Facades\Site;
use Illuminate\Support\Str;
use Statamic\Assets\AssetCollection;
use Statamic\Contracts\Assets\Asset;

trait HasAsset
{
    protected string $reference;

    public function assets(): AssetCollection
    {
        $assets = $this->container()->queryAssets()
            ->where('filename', 'like', "{$this->reference()}%")
            ->orderBy('last_modified', 'desc')
            ->get();

        /**
         * We only want to get assets that actually belong to this model.
         * If this model's reference is `instagram-post-default`,
         * we don't want to get assets of model with reference `instagram-post-default-pages-home-default`
         * I couldn't make `regexp` in the query work instead of using `like`. So we have to filter here instead.
         */
        return $assets->filter(fn ($asset) => $this->assetBelongsToModel($asset));
    }

    protected function assetBelongsToModel(Asset $asset): bool
    {
        $assetReference = Str::beforeLast($asset->filename(), '-');

        return Str::contains($this->reference(), $assetReference);
    }

    public function makeAsset(): Asset
    {
        return $this->container()->makeAsset($this->path());
    }

    public function latestAsset(): ?Asset
    {
        return $this->assets()->first();
    }

    public function oldAssets(): AssetCollection
    {
        return $this->assets()->skip(1);
    }

    public function deleteOldAssets(): self
    {
        $this->oldAssets()->each->delete();

        return $this;
    }

    public function deleteAssets(): self
    {
        $this->assets()->each->delete();

        return $this;
    }

    public function path(): string
    {
        return Path::assemble($this->directory(), $this->filename());
    }

    public function absolutePath($path = null): string
    {
        return $this->container()->disk()->path($path ?? $this->path());
    }

    public function filename(): string
    {
        return "{$this->reference()}-{$this->uid}.{$this->extension()}";
    }

    public function reference(string $reference = null): string|self
    {
        if (is_null($reference)) {
            return $this->reference ?? $this->defaultReference();
        }

        $this->reference = $reference;

        return $this;
    }

    protected function defaultReference(): string
    {
        $reference = collect([
            'id' => $this->id(),
            'template' => $this->template()->name(),
            'collection' => $this->content->get('collection'),
            'taxonomy' => $this->content->get('taxonomy'),
            'site' => Site::hasMultiple() ? $this->content->get('locale') : null,
            'slug' => $this->content->get('slug'),
        ])->filter()->implode('-');

        return Str::slug($reference);
    }
}
