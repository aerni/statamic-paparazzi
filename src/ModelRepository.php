<?php

namespace Aerni\Paparazzi;

use Illuminate\Support\Collection;

class ModelRepository
{
    protected Collection $models;

    public function __construct()
    {
        $this->models = collect(config('paparazzi.models'));
    }

    public function make(string $handle, array $config): Model
    {
        return $this->resolve($handle, $config);
    }

    public function all(?array $models = null): Collection
    {
        return $this->models
            ->only($models)
            ->map(fn ($model, $handle) => $this->resolve($handle, $model))
            ->values();
    }

    public function find(string $handle): ?Model
    {
        $model = $this->models->get($handle);

        return $model ? $this->resolve($handle, $model) : null;
    }

    protected function resolve(string $handle, array $config): Model
    {
        return new Model($handle, $config);
    }
}
