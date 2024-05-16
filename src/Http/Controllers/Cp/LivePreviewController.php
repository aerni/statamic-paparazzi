<?php

namespace Aerni\Paparazzi\Http\Controllers\Cp;

use Facades\Statamic\CP\LivePreview;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Statamic\View\View;

class LivePreviewController extends Controller
{
    public function __invoke(Request $request): View
    {
        $model = Crypt::decrypt($request->modelToken);

        if (! $model->content()) {
            $model->content(LivePreview::item(request()->statamicToken()));
        }

        return $model->view();
    }
}
