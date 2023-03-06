<?php

namespace Aerni\Paparazzi\Facades;

use Illuminate\Support\Facades\Facade;

class Paparazzi extends Facade
{
    protected static function getFacadeAccessor()
    {
        return \Aerni\Paparazzi\Paparazzi::class;
    }
}
