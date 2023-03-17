<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

class LivePreview extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\LivePreview::class;
    }
}
