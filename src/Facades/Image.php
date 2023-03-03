<?php

namespace Aerni\ImageGenerator\Facades;

use Illuminate\Support\Facades\Facade;

class Image extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\ImageGenerator\Image\ImageRepository::class;
    }
}
