<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Aerni\Paparazzi\Exceptions\ContentNotFound;
use Aerni\Paparazzi\Exceptions\ModelNotFound;
use Aerni\Paparazzi\Facades\Model;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Exceptions\SiteNotFoundException;
use Statamic\Facades\Data;
use Statamic\View\View;

class ModelController extends Controller
{
    public function __invoke(Request $request): View
    {
        throw_unless($model = Model::find($request->model), new ModelNotFound($request->model));

        if ($request->content) {
            throw_unless($content = Data::find($request->content), new ContentNotFound($request->content));
        }

        if (isset($content) && $content instanceof Entry && $request->site) {
            $content = $content->in($request->site);
            throw_unless($content, new SiteNotFoundException($request->site));
        }

        if (isset($content) && $content instanceof Term && $request->site) {
            $sites = $content->term()->localizations()->keys();
            $content = $sites->contains($request->site) ? $content->in($request->site) : null;
            throw_unless($content, new SiteNotFoundException($request->site));
        }

        if (isset($content)) {
            $model->content($content);
        }

        if ($layout = $request->layout) {
            $model->layout($layout);
        }

        return $model->view();
    }
}
