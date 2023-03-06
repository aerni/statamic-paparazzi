<?php

namespace Aerni\Paparazzi\Concerns;

use Statamic\Facades\Path;
use Statamic\Contracts\Assets\Asset;

trait HasAsset
{
    // TODO: Instead of looking for an exact path we should query the asset by likeness. This way we can add a unique ID to the assets path to get around caching issues.
    // TODO: But maybe that's not a good idea. Better would be to replace the asset so Statamic updates all its references too.
    public function asset(): ?Asset
    {
        return $this->container()->asset($this->path());
    }

    public function delete(): self
    {
        $this->asset()?->delete();

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
        return "{$this->assetId()}.{$this->extension()}";
    }

    public function assetId(): string
    {
        return "{$this->content->get('id')}_{$this->id()}";
    }
}
