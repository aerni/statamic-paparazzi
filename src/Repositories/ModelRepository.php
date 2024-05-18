<?php

namespace Aerni\Paparazzi\Repositories;

use Aerni\Paparazzi\Model;
use Illuminate\Support\Str;
use Illuminate\Support\Collection;
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
            ->map(fn ($model) => $this->make()->config($model))
            ->values();
    }

    public function find(string $id): ?Model
    {
        if (! $model = $this->store->get($id)) {
            return null;
        }

        return $this->make()->config($model);
    }
}
