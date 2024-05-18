<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

class Layout extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Repositories\LayoutRepository::class;
    }
}
