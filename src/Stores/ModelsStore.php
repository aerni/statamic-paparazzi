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
            ->flatMap(fn (Model $model) =>
                $model->templates()->mapWithKeys(fn (Template $template) => [$template->id() => $model])
            );
    }
}
