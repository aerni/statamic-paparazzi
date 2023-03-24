<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Aerni\Paparazzi\Actions\GetModelFromRouteParameters;
use Illuminate\Routing\Controller;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Term;
use Statamic\View\View;

class TermModelController extends Controller
{
    public function __invoke(): View
    {
        $model = GetModelFromRouteParameters::handle();

        $parameters = request()->route()->parameters();

        $term = Term::find("{$parameters['taxonomy']}::{$parameters['term']}");

        if (isset($parameters['site'])) {
            $site = $parameters['site'];
            $sites = $term?->term()->localizations()->keys();
            $termExistsInSite = $sites?->contains($site);
            $term = $termExistsInSite ? $term->in($site) : null;
        }

        throw_unless($term, new NotFoundHttpException());

        return $model
            ->content($term)
            ->view();
    }
}
