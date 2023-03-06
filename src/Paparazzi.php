<?php

namespace Aerni\Paparazzi;

use Illuminate\Support\Collection;
use Illuminate\Support\Str;
use Statamic\Facades\URL;

class Paparazzi
{
    public function models(array $select = []): Collection
    {
        $select = collect($select)->map(fn ($model) => Str::snake($model));

        return collect(config('paparazzi.models'))
            ->when(
                $select->isNotEmpty(),
                fn ($models) => $models->only($select)
            )
            ->map(fn ($model, $id) => new Model($id, $model))
            ->values();
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
        return $this->model($method)->content($arguments[0] ?? []);
    }
}
