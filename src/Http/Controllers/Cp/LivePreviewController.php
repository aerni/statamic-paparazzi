<?php

namespace Aerni\Paparazzi\Http\Controllers\Cp;

use Statamic\View\View;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Facades\Statamic\CP\LivePreview;
use Illuminate\Support\Facades\Crypt;

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
