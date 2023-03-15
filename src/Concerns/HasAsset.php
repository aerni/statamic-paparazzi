<?php

namespace Aerni\Paparazzi\Concerns;

use Statamic\Assets\AssetCollection;
use Statamic\Contracts\Assets\Asset;
use Statamic\Facades\Path;

trait HasAsset
{
    public function assets(): AssetCollection
    {
        // TODO: When generating an asset without an entry, previously generated assets with an entry of the same model will be replaced.
        // This shouldn't happen. We need a better query to filter out explicitly by reference.
        return $this->container()->queryAssets()
            ->where('filename', 'like', "{$this->reference()}%")
            ->orderBy('last_modified', 'desc')
            ->get();
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
        return Path::assemble(
            $this->directory(),
            // $this->content->get('collection')?->handle(), // TODO: Make this configurable. Maybe with a {contentHandle} variable in the config?
            $this->filename()
        );
    }

    public function absolutePath($path = null): string
    {
        return $this->container()->disk()->path($path ?? $this->path());
    }

    public function filename(): string
    {
        return "{$this->reference()}-{$this->uid}.{$this->extension()}";
    }
}
