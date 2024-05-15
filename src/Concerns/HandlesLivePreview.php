<?php

namespace Aerni\Paparazzi\Concerns;

use Statamic\Facades\Collection;
use Statamic\Facades\Taxonomy;

trait HandlesLivePreview
{
    public function addLivePreviewToCollection(string|array|null $collections = null): self
    {
        $collections = is_null($collections)
            ? Collection::all()
            : collect($collections)->map(fn ($collection) => Collection::find($collection));

        $collections->filter()->each(fn ($collection) => $collection->addPreviewTargets([$this->livePrevieTarget()]));

        return $this;
    }

    public function addLivePreviewToTaxonomy(string|array|null $taxonomies = null): self
    {
        $taxonomies = is_null($taxonomies)
            ? Taxonomy::all()
            : collect($taxonomies)->map(fn ($taxonomy) => Taxonomy::find($taxonomy));

        $taxonomies->filter()->each(fn ($taxonomy) => $taxonomy->addPreviewTargets([$this->livePrevieTarget()]));

        return $this;
    }

    protected function livePrevieTarget(): array
    {
        return [
            'label' => "{$this->name()} – {$this->template()->name()}",
            'format' => $this->livePreviewUrl(),
        ];
    }

    protected function livePreviewUrl(): string
    {
        return cp_route(
            'paparazzi.live-preview',
            explode('/', $this->parseVariables('{model}/{layout}/{template}'))
        );
    }
}
