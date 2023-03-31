<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Facades\Paparazzi;
use Illuminate\Support\Collection;
use Statamic\Facades\Collection as CollectionApi;
use Statamic\Facades\Taxonomy;

class LivePreview
{
    protected Collection $models;

    public function addModel(string|array $models): self
    {
        $this->models = collect($models)
            ->map(function (Model|string $model) {
                return $model instanceof Model ? $model : Paparazzi::model($model);
            });

        return $this;
    }

    public function toCollection(string|array $collections): void
    {
        collect($collections)->each(fn ($collection) => CollectionApi::find($collection)?->addPreviewTargets($this->targets()));
    }

    public function toTaxonomy(string|array $taxonomies): void
    {
        collect($taxonomies)->each(fn ($taxonomy) => Taxonomy::find($taxonomy)?->addPreviewTargets($this->targets()));
    }

    protected function targets(): array
    {
        return $this->models->map(fn ($model) => [
            'label' => $model->name(),
            'format' => $model->livePreviewUrl(),
        ])->all();
    }
}
