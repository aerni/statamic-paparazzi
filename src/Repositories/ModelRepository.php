<?php

namespace Aerni\Paparazzi\Repositories;

use Aerni\Paparazzi\Model;
use Illuminate\Support\Collection;
use Aerni\Paparazzi\Facades\Template;
use Aerni\Paparazzi\Stores\ModelsStore;

class ModelRepository
{
    public function __construct(protected ModelsStore $store)
    {
        //
    }

    public function make(): Model
    {
        return new Model();
    }

    public function all(?array $models = null): Collection
    {
        return $this->store
            ->only($models)
            ->flatMap(function ($model, $handle) {
                return Template::allOfModel($handle)->map(fn ($template) => $this->make()->handle($handle)->config($model)->template($template));
            })
            ->values();
    }

    public function find(string $handle): ?Model
    {
        if (! $model = $this->store->get($handle)) {
            return null;
        }

        return $this->make()->handle($handle)->config($model);
    }
}
