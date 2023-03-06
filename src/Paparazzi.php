<?php

namespace Aerni\Paparazzi;

use Statamic\Facades\URL;
use Illuminate\Support\Str;

class Paparazzi
{
    public function make(Model $model): Image
    {
        return Image::make($model);
    }

    public function model(string $id): ?Model
    {
        $id = Str::snake($id);

        $config = config("paparazzi.models.{$id}");

        return $config
            ? new Model($id, $config)
            : null;
    }

    public function route(): string
    {
        $baseUrl = config('paparazzi.preview_url', '/paparazzi');
        $parameters = '/{model}/{template}/{contentId}';

        return URL::assemble($baseUrl, $parameters);
    }

    public function __call(string $method, array $arguments)
    {
        if ($model = $this->model($method)) {
            return $this->make($model)->with($arguments[0]);
        }
    }
}
