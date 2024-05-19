<?php

namespace Aerni\Paparazzi\Stores;

use Aerni\Paparazzi\Model;
use Aerni\Paparazzi\Template;
use Illuminate\Support\Collection;

class ModelsStore extends Store
{
    protected string $key = 'paparazzi-models';

    protected function makeItems(): Collection
    {
        return collect(config('paparazzi.models'))
            ->map(fn ($config, $handle) => (new Model())->handle($handle)->config($config))
            ->flatMap(function (Model $model) {
                return $model->templates()->mapWithKeys(function (Template $template) use ($model) {
                    $model = clone $model->template($template->handle());
                    return [$model->id() => $model];
                });
            });
    }
}
