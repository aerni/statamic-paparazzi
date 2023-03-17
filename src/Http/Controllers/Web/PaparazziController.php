<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Statamic\Facades\Data;
use Illuminate\Support\Str;
use Facades\Statamic\CP\LivePreview;
use Aerni\Paparazzi\Facades\Model;
use Illuminate\Routing\Controller;
use Aerni\Paparazzi\Facades\Layout;
use Aerni\Paparazzi\Facades\Template;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\View\View;

class PaparazziController extends Controller
{
    public function show(string $model, string $layout, string $template, string $contentId = null): View
    {
        throw_unless($model = Model::find(Str::replace('-', '_', $model)), new NotFoundHttpException);
        throw_unless($layout = Layout::find($layout), new NotFoundHttpException);
        throw_unless($template = Template::find("{$model}::{$template}"), new NotFoundHttpException);

        $model
            ->layout($layout)
            ->template($template);

        if ($content = $this->getData($contentId)) {
            $model->content($content);
        }

        return $model->view();
    }

    protected function getData(string $id = null): Entry|Term|null
    {
        if (request()->statamicToken()) {
            return LivePreview::item(request()->statamicToken());
        }

        return Data::find($id);
    }
}
