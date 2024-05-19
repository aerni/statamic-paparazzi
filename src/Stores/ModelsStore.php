<?php

namespace Aerni\Paparazzi\Stores;

use Spatie\Blink\Blink;
use Aerni\Paparazzi\Model;
use Aerni\Paparazzi\Template;
use Illuminate\Support\Collection;

class ModelsStore extends Store
{
    public function items(): Collection
    {
        return Blink::global()->once('paparazzi-models', function () {
            return collect(config('paparazzi.models'))
                ->map(fn ($config, $handle) => (new Model())->handle($handle)->config($config))
                ->flatMap(fn (Model $model) =>
                    $model->templates()->mapWithKeys(fn (Template $template) => [$template->id() => $model])
                );
        });
    }
}
