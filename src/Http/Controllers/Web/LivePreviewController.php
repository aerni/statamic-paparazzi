<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Aerni\Paparazzi\Actions\GetModelFromRouteParameters;
use Facades\Statamic\CP\LivePreview;
use Illuminate\Routing\Controller;
use Statamic\View\View;

class LivePreviewController extends Controller
{
    public function __invoke(): View
    {
        return GetModelFromRouteParameters::handle()
            ->content(LivePreview::item(request()->statamicToken()))
            ->view();
    }
}
