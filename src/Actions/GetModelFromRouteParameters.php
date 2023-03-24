<?php

namespace Aerni\Paparazzi\Actions;

use Aerni\Paparazzi\Facades\Layout;
use Aerni\Paparazzi\Facades\Model as ModelApi;
use Aerni\Paparazzi\Facades\Template;
use Aerni\Paparazzi\Model;
use Illuminate\Support\Str;
use Statamic\Exceptions\NotFoundHttpException;

class GetModelFromRouteParameters
{
    public static function handle(): Model
    {
        $parameters = request()->route()->parameters();

        throw_unless($model = ModelApi::find(Str::replace('-', '_', $parameters['model'])), new NotFoundHttpException);
        throw_unless($layout = Layout::find($parameters['layout']), new NotFoundHttpException);
        throw_unless($template = Template::find("{$model}::{$parameters['template']}"), new NotFoundHttpException);

        return $model
            ->layout($layout)
            ->template($template);
    }
}
