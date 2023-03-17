<?php

namespace Aerni\Paparazzi;

use Aerni\Paparazzi\Facades\Model as ModelApi;
use Illuminate\Support\Arr;
use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Facades\URL;

class Paparazzi
{
    public function models(): Collection
    {
        return func_get_args()
            ? ModelApi::select(...func_get_args())
            : ModelApi::all();
    }

    public function model(string $id): ?Model
    {
        return ModelApi::find($id);
    }

    public function route(): string
    {
        $baseUrl = config('paparazzi.preview_url', '/paparazzi');
        $parameters = '/{model}/{layout}/{template}/{contentId?}';

        return URL::assemble($baseUrl, $parameters);
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
