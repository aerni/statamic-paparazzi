<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Actions\GetContentParent;
use Aerni\Paparazzi\Facades\Paparazzi;
use Illuminate\Support\Collection;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;

class LivePreview
{
    protected Collection $models;

    public function add(string|array $models): self
    {
        $this->models = collect($models)
            ->map(function (Model|string $model) {
                return $model instanceof Model ? $model : Paparazzi::model($model);
            });

        return $this;
    }

    public function to(Entry|Term $content): void
    {
        $targets = $this->models->map(fn ($model) => [
            'label' => $model->name(),
            'format' => $model->cpUrl(),
        ])->all();

        GetContentParent::handle($content)->addPreviewTargets($targets);
    }
}
