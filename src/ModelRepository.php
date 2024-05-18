<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Stores\ModelsStore;
use Illuminate\Support\Collection;

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
            ->map(fn ($model, $handle) => $this->make()->handle($handle)->config($model))
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
