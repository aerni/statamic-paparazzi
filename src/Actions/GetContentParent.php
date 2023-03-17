<?php

namespace Aerni\Paparazzi\Actions;

use Statamic\Contracts\Entries\Entry;
use Statamic\Contracts\Taxonomies\Term;

class GetContentParent
{
    public static function handle(mixed $data): mixed
    {
        return match (true) {
            ($data instanceof Entry) => $data->collection(),
            ($data instanceof Term) => $data->taxonomy(),
            default => null
        };
    }
}
