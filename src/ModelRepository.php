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

    public function all(): Collection
    {
        return $this->models
            ->map(fn ($model, $id) => $this->resolve($id, $model))
            ->values();
    }

    public function select(): Collection
    {
        return $this->models
            ->only(func_get_args())
            ->map(fn ($model, $id) => $this->resolve($id, $model))
            ->values();
    }

    public function find(string $id): ?Model
    {
        $model = $this->models->get($id);

        return $model ? $this->resolve($id, $model) : null;
    }

    protected function resolve(string $id, array $config): Model
    {
        return new Model($id, $config);
    }
}
