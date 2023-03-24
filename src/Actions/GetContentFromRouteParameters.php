<?php

namespace Aerni\Paparazzi\Actions;

use Facades\Statamic\CP\LivePreview;
use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;
use Statamic\Facades\Entry as EntryApi;
use Statamic\Facades\Term as TermApi;

class GetContentFromRouteParameters
{
    public static function handle(): Entry|Term|null
    {
        if (request()->statamicToken()) {
            return LivePreview::item(request()->statamicToken());
        }

        $parameters = request()->route()->parameters();

        if (! isset($parameters['contentType'])) {
            return null;
        }

        $content = match ($parameters['contentType']) {
            ('collection') => EntryApi::find($parameters['contentId']),
            ('taxonomy') => TermApi::find($parameters['contentId']),
            default => null
        };

        if (isset($parameters['contentSite'])) {
            return $content?->in($parameters['contentSite']);
        }

        return $content;
    }
}
