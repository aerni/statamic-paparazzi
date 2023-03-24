<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Aerni\Paparazzi\Actions\GetModelFromRouteParameters;
use Illuminate\Routing\Controller;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Entry;
use Statamic\View\View;

class EntryModelController extends Controller
{
    public function __invoke(): View
    {
        $model = GetModelFromRouteParameters::handle();

        $parameters = request()->route()->parameters();

        $entry = Entry::find($parameters['entry']);

        if (isset($parameters['site'])) {
            $entry = $entry?->in($parameters['site']);
        }

        throw_unless($entry, new NotFoundHttpException());

        return $model
            ->content($entry)
            ->view();
    }
}
