<?php

namespace Aerni\Paparazzi\Http\Controllers\Web;

use Aerni\AdvancedSeo\Facades\SocialImage;
use Facades\Statamic\CP\LivePreview;
use Illuminate\Http\Response;
use Illuminate\Routing\Controller;
use Illuminate\Support\Str;
use Statamic\Contracts\Entries\Entry;
use Statamic\Exceptions\NotFoundHttpException;
use Statamic\Facades\Data;
use Statamic\Taxonomies\LocalizedTerm;
use Statamic\View\View;

class ImageController extends Controller
{
    public function show(string $theme, string $type, string $id): Response
    {
        // Throw if the social images generator is disabled.
        throw_unless(config('advanced-seo.social_images.generator.enabled', false), new NotFoundHttpException);

        // Throw if no data was found.
        throw_unless($data = $this->getData($id), new NotFoundHttpException);

        // Throw if the data is not an entry or term.
        throw_unless($data instanceof Entry || $data instanceof LocalizedTerm, new NotFoundHttpException());

        // Throw if the social image type is not supported.
        throw_unless($model = SocialImage::findModel(Str::replace('-', '_', $type)), new NotFoundHttpException);

        $template = $model['templates']->get(Str::replace('-', '_', $theme)) // Get the template based on the theme in the request.
            ?? $model['templates']->get('default') // If no theme is set, use the default theme.
            ?? $model['templates']->first(); // If the default doesn't exist either, fall back to the first theme.

        // Prevent an infinite loop when an image is generated in the augment method of the SocialImageFieldtype.
        $data->set('seo_generate_social_images', false);

        $view = (new View)
            ->template($template)
            ->layout($model['layout'])
            ->cascadeContent($data)
            ->with($model);

        return response($view)->header('X-Robots-Tag', 'noindex, nofollow');
    }

    protected function getData(string $id): ?Entry
    {
        if (request()->statamicToken()) {
            return LivePreview::item(request()->statamicToken());
        }

        return Data::find($id);
    }
}
