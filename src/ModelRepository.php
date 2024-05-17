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

    public function make(): Model
    {
        return new Model();
    }

    public function all(?array $models = null): Collection
    {
        return $this->models
            ->only($models)
            ->map(fn ($config) => $this->make()->config($config))
            ->values();
    }

    public function find(string $handle): ?Model
    {
        if (! $config = $this->models->get($handle)) {
            return null;
        }

        return $this->make()->config($config);
    }
}
