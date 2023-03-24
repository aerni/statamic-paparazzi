<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Aerni\Paparazzi\Actions\GetModelFromRouteParameters;
use Illuminate\Routing\Controller;
use Statamic\View\View;

class PaparazziController extends Controller
{
    public function __invoke(): View
    {
        return GetModelFromRouteParameters::handle()->view();
    }
}
