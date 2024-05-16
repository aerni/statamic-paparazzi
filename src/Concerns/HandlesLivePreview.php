<?php

namespace Aerni\Paparazzi\Concerns;

use Statamic\Facades\Taxonomy;
use Statamic\Facades\Collection;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;
use Statamic\Contracts\Entries\Collection as StatamicCollection;
use Statamic\Contracts\Taxonomies\Taxonomy as StatamicTaxonomy;

trait HandlesLivePreview
{
    public function addLivePreviewToCollection(string|array|null $collections = null): self
    {
        $collections = is_null($collections)
            ? Collection::all()
            : collect($collections)->map(fn ($collection) => Collection::find($collection))->filter();

        $this->addLivePreviewTo($collections);

        return $this;
    }

    public function addLivePreviewToTaxonomy(string|array|null $taxonomies = null): self
    {
        $taxonomies = is_null($taxonomies)
            ? Taxonomy::all()
            : collect($taxonomies)->map(fn ($taxonomy) => Taxonomy::find($taxonomy))->filter();

        $this->addLivePreviewTo($taxonomies);

        return $this;
    }

    protected function addLivePreviewTo(\Illuminate\Support\Collection $targets): void
    {
        Route::matched(function () use ($targets) {
            $targets->each(function (StatamicCollection|StatamicTaxonomy $target) {
                $target->addPreviewTargets([[
                    'label' => "{$this->name()} – {$this->template()->name()}",
                    'format' => cp_route('paparazzi.live-preview', Crypt::encrypt($this))
                ]]);
            });
        });
    }
}
