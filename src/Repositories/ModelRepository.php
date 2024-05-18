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
        return $this->store->items()->only($models);
    }

    public function find(string $id): ?Model
    {
        return $this->store->item($id);
    }

    public function findByHandle(string $handle): Collection
    {
        return $this->store->items()
            ->filter(fn (Model $model) => $model->handle() === $handle);
    }

    public function __call(string $method, array $arguments): ?Model
    {
        $models = $this->findByHandle(Str::snake($method));

        if (empty($arguments)) {
            return $models->firstWhere(fn (Model $model) => $model->template()->isDefault())
                ?? $models->first();
        }

        return $models->firstWhere(fn (Model $model) => $model->template()->handle() === $arguments[0]);
    }
}
