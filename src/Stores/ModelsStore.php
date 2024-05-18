<?php

namespace Aerni\Paparazzi\Stores;

use Aerni\Paparazzi\Facades\Model;
use Illuminate\Support\Collection;
use Aerni\Paparazzi\Facades\Template;

class ModelsStore extends Store
{
    public function items(): Collection
    {
        return collect(config('paparazzi.models'))
            ->flatMap(function ($model, $handle) {
                return Template::allOfModel($handle)
                    ->mapWithKeys(function ($template) use ($model, $handle) {
                        return [$template->id() => array_merge($model, [
                            'handle' => $handle,
                            'template' => $template->handle()
                        ])];
                    });
            })
            ->map(fn (array $model) => Model::make()->config($model));
    }
}
