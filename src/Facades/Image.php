<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

class Image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Image::class;
    }
}
