<?php

namespace Aerni\Paparazzi\Actions;

use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;

class GetContentType
{
    public static function handle(mixed $data): mixed
    {
        return match (true) {
            ($data instanceof Entry) => 'collections',
            ($data instanceof Term) => 'taxonomies',
            default => null
        };
    }
}
