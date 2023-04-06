<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Facades\Model as ModelApi;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;

class Paparazzi
{
    public function models(array $models = null): Collection
    {
        return ModelApi::all($models);
    }

    public function model(string $handle): ?Model
    {
        return ModelApi::find($handle);
    }

    public function __call(string $method, array $arguments)
    {
        $model = $this->model(Str::snake($method));

        if ($model && Arr::first($arguments)) {
            $model->content($arguments[0]);
        }

        return $model;
    }
}
